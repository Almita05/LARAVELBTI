<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

/**
 * Gestión de cuentas de personal (todas las rutas con middleware solo.admin).
 * Consume la API Flask /personal; la API cifra las contraseñas, valida la unicidad del usuario
 * contra administradores/docentes/personal y registra la bitácora.
 */
class PersonalController extends Controller
{
    private function api(string $ruta = ''): string
    {
        return config('services.api.base_url') . '/personal' . $ruta;
    }

    /** Respuesta uniforme: mensajes de validación de la API tal cual; errores internos, genéricos. */
    private function responder($response, string $fallback)
    {
        if ($response->successful()) {
            return response()->json(['success' => true, 'data' => $response->json()]);
        }
        $status = $response->status();
        $mensaje = in_array($status, [400, 404, 409], true) ? ($response->json('error') ?? $fallback) : $fallback;
        if ($status >= 500) {
            Log::error("API personal respondió {$status}", ['body' => mb_substr($response->body(), 0, 500)]);
        }
        return response()->json(['success' => false, 'message' => $mensaje], $status >= 500 ? 502 : $status);
    }

    private function reglasCuenta(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            // Sin espacios ni caracteres raros: es el nombre con el que se inicia sesión
            'usuario' => ['required', 'string', 'max:100', 'regex:/^[A-Za-z0-9._@ñÑ-]+$/u'],
            'rol' => 'required|string|max:100',
            'permisos_modulos' => 'nullable|array',
            'permisos_modulos.*' => ['string', 'regex:/^[a-z_]+(:(ver|crear|eliminar|consultar))?$/'],
            'idBiometrico' => 'nullable|string|max:50',
            'es_servicio_social' => 'nullable',
            'horas_objetivo' => 'nullable|numeric|min:0|max:2000',
        ];
    }

    private function mensajes(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'usuario.regex' => 'El usuario solo puede tener letras, números, punto, guion, guion bajo o @ (sin espacios).',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.letters' => 'La contraseña debe incluir al menos una letra.',
            'password.numbers' => 'La contraseña debe incluir al menos un número.',
            'permisos_modulos.*.regex' => 'Hay un permiso con formato inválido.',
            'status.in' => 'El estatus debe ser ACTIVO o INACTIVO.',
            'max' => 'El campo :attribute es demasiado largo.',
            'numeric' => 'El campo :attribute debe ser numérico.',
        ];
    }

    private function atributos(): array
    {
        return ['nombre' => 'nombre', 'usuario' => 'usuario', 'password' => 'contraseña', 'rol' => 'rol', 'status' => 'estatus', 'horas_objetivo' => 'horas objetivo'];
    }

    private function payload(Request $request): array
    {
        return [
            'nombre' => trim($request->nombre),
            'usuario' => trim($request->usuario),
            'rol' => $request->rol,
            // Vacío = sin permisos (la API ya no asigna permisos por defecto)
            'permisos_modulos' => implode(',', (array) $request->input('permisos_modulos', [])),
            'idBiometrico' => $request->filled('idBiometrico') ? trim($request->idBiometrico) : null,
            'es_servicio_social' => ($request->boolean('es_servicio_social') || $request->rol === 'Servicio Social') ? 1 : 0,
            'horas_objetivo' => $request->filled('horas_objetivo') ? (int) $request->horas_objetivo : null,
        ];
    }

    public function index()
    {
        return view('personal.index');
    }

    /** Devuelve TODAS las cuentas (la API pagina de 500 en 500). */
    public function lista()
    {
        $todos = [];
        $page = 1;
        do {
            $response = Http::timeout(10)->get($this->api(), ['page' => $page, 'limit' => 500]);
            if ($response->failed()) {
                Log::error('No se pudo obtener la lista de personal', ['status' => $response->status()]);
                return response()->json(['data' => [], 'message' => 'No se pudo cargar la lista de personal.'], 502);
            }
            $todos = array_merge($todos, $response->json('data') ?? []);
            $totalPaginas = (int) ($response->json('total_pages') ?? 1);
        } while (++$page <= $totalPaginas && $page <= 50);

        return response()->json(['data' => $todos, 'total' => count($todos)]);
    }

    public function store(Request $request)
    {
        $request->validate($this->reglasCuenta() + [
            'password' => ['required', 'string', Password::min(8)->letters()->numbers()],
        ], $this->mensajes(), $this->atributos());

        $payload = $this->payload($request) + [
            'password' => $request->password, // la API la cifra
            'status' => 'ACTIVO',
        ];

        return $this->responder(Http::timeout(10)->post($this->api(), $payload), 'Error al registrar el personal');
    }

    public function show($id)
    {
        $response = Http::timeout(10)->get($this->api('/' . (int) $id));
        if ($response->successful()) {
            return $response->json();
        }
        return $this->responder($response, 'Error al obtener los detalles del personal');
    }

    public function update(Request $request, $id)
    {
        $request->validate($this->reglasCuenta() + [
            'status' => 'required|in:ACTIVO,INACTIVO',
            'password' => ['nullable', 'string', Password::min(8)->letters()->numbers()],
        ], $this->mensajes(), $this->atributos());

        $payload = $this->payload($request) + ['status' => $request->status];
        if ($request->filled('password')) {
            $payload['password'] = $request->password;
        }

        return $this->responder(Http::timeout(10)->put($this->api('/' . (int) $id), $payload), 'Error al actualizar el personal');
    }

    /** Baja lógica: la cuenta pasa a INACTIVO (se conserva su historial). */
    public function destroy($id)
    {
        $response = Http::timeout(10)->delete($this->api('/' . (int) $id));
        if ($response->successful()) {
            return response()->json(['success' => true, 'message' => 'Cuenta de personal desactivada correctamente']);
        }
        return $this->responder($response, 'Error al desactivar la cuenta de personal');
    }
}
