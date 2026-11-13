<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\RestablecerPasswordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

/**
 * Autenticación. Todas las fuentes se consultan vía API (nunca la BD directa) y la contraseña
 * se verifica dentro de la API: el hash nunca llega a Laravel.
 *
 * Orden: tb_users (Auth::attempt con ApiUserProvider) → docentes → personal.
 * Si el usuario existe en una fuente pero la contraseña no coincide, se siguen revisando las demás
 * (cuentas antiguas con el mismo nombre en dos tablas ya no se bloquean entre sí).
 */
class AuthController extends Controller
{
    /**
     * Destino tras el login según el rol de tb_users.
     * Hoy ambos dashboards son /home (DashboardController); si se crea un dashboard
     * de supervisión propio, basta con cambiar RUTA_SUPERVISION.
     */
    private const RUTA_SUPERVISION = 'home';
    private const RUTA_ESCUELA = 'home';

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string|max:100',
            'password' => 'required|string|max:255',
        ]);

        $usuario = trim($request->usuario);
        $password = (string) $request->password;
        $api = config('services.api.base_url');

        $passwordIncorrecta = false;
        $bloqueo = null;

        try {
            // 1. Usuarios administrativos (tb_users)
            $res = Http::timeout(5)->get("{$api}/usuarios/by-username/" . rawurlencode($usuario));
            if ($res->successful()) {
                if (!$res->json('data.activo')) {
                    $bloqueo = 'Usuario inactivo';
                } elseif (Auth::attempt(['usuario' => $usuario, 'password' => $password, 'activo' => true])) {
                    return $this->iniciarSesionAdministrativa($request);
                } else {
                    $passwordIncorrecta = true;
                }
            }

            // 2. Docentes
            $res = Http::timeout(8)->post("{$api}/docentes/login", ['usuario' => $usuario, 'password' => $password]);
            if ($res->successful() && ($d = $res->json('data'))) {
                $request->session()->regenerate();
                session([
                    'origen' => 'docente',
                    'usuario_id' => $d['idDocente'],
                    'usuario' => $d['usuario'] ?: $d['correoDocente'],
                    'nombre' => trim(($d['nombreDocente'] ?? '') . ' ' . ($d['apPaternoDocente'] ?? '') . ' ' . ($d['apMaternoDocente'] ?? '')),
                    'rol' => 'DOCENTE',
                    'id_docente' => $d['idDocente'],
                    'modulos' => $d['permisos_modulos'] ?? 'horarios,pendientes,grupos',
                ]);
                return redirect('/home');
            }
            [$passwordIncorrecta, $bloqueo] = $this->acumular($res, $passwordIncorrecta, $bloqueo);

            // 3. Personal
            $res = Http::timeout(8)->post("{$api}/personal/login", ['usuario' => $usuario, 'password' => $password]);
            if ($res->successful() && ($p = $res->json('data'))) {
                $request->session()->regenerate();
                session([
                    'origen' => 'personal',
                    'usuario_id' => $p['idPersonal'],
                    'usuario' => $p['usuario'],
                    'nombre' => $p['nombre'],
                    'rol' => 'PERSONAL',
                    'rol_nombre' => $p['rol'],
                    // Sin permisos asignados = sin módulos (ya no se otorgan permisos por defecto)
                    'modulos' => (string) ($p['permisos_modulos'] ?? ''),
                ]);
                return redirect('/home');
            }
            [$passwordIncorrecta, $bloqueo] = $this->acumular($res, $passwordIncorrecta, $bloqueo);
        } catch (\Throwable $e) {
            Log::error('Login: no se pudo contactar la API: ' . $e->getMessage());
            return back()->withInput($request->only('usuario'))
                ->with('error', 'No se pudo conectar con el servidor. Intenta de nuevo en unos minutos.');
        }

        // Mensaje genérico ante contraseña incorrecta o usuario inexistente (no revela qué cuentas existen)
        $mensaje = $passwordIncorrecta ? 'Usuario o contraseña incorrectos' : ($bloqueo ?? 'Usuario o contraseña incorrectos');
        return back()->withInput($request->only('usuario'))->with('error', $mensaje);
    }

    /** 401 = contraseña incorrecta; 403 = cuenta bloqueada (inactiva / sin contraseña); 404 = no existe. */
    private function acumular($res, bool $passwordIncorrecta, ?string $bloqueo): array
    {
        if ($res->status() === 401) $passwordIncorrecta = true;
        if ($res->status() === 403) $bloqueo ??= $res->json('error');
        return [$passwordIncorrecta, $bloqueo];
    }

    private function iniciarSesionAdministrativa(Request $request)
    {
        $request->session()->regenerate(); // evita fijación de sesión

        /** @var User $user */
        $user = Auth::user();

        // El resto de la app (middleware auth.session, has_perm, menú) lee estas variables
        session([
            'origen' => 'usuarios',
            'usuario_id' => $user->id,
            'usuario' => $user->usuario,
            'nombre' => $user->name,
            'rol' => $user->esAdmin() ? 'ADMIN' : strtoupper((string) $user->rol),
        ]);

        return $user->rol === User::ROL_ADMIN
            ? redirect()->route(self::RUTA_SUPERVISION)
            : redirect()->route(self::RUTA_ESCUELA);
    }

    /**
     * Restablece la contraseña de una cuenta de tb_users (ruta protegida con solo.admin).
     * Si la cuenta destino es admin, además se exige `correo_maestro` = MASTER_RECOVERY_EMAIL.
     * Para un admin sin acceso al sistema: `php artisan usuarios:restablecer-password`.
     */
    public function restablecerPassword(Request $request, RestablecerPasswordService $servicio)
    {
        $datos = $request->validate([
            'usuario' => 'required|string|max:100',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'correo_maestro' => 'nullable|string|email|max:255',
        ]);

        $cuenta = $servicio->buscar($datos['usuario']);
        if (!$cuenta) {
            return response()->json(['success' => false, 'message' => 'El usuario no existe.'], 404);
        }

        $servicio->restablecer($cuenta, $datos['password'], $datos['correo_maestro'] ?? null, 'web:' . session('usuario'));

        return $request->expectsJson()
            ? response()->json(['success' => true, 'message' => 'Contraseña restablecida correctamente.'])
            : back()->with('success', 'Contraseña restablecida correctamente.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flush();      // Borra todas las variables de sesión
        $request->session()->invalidate(); // Invalida la sesión
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
