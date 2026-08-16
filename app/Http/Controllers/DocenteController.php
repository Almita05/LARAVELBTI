<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class DocenteController extends Controller
{
    public function index()
    {
        return view('docentes.index');
    }

    public function lista()
    {
        $url = config('services.api.base_url') . '/docentes';

        $response = Http::get($url);

        if ($response->failed()) {
            return response()->json([
                'data' => []
            ]);
        }

        return $response->json();
    }

    public function store(Request $request)
{
    $url = config('services.api.base_url') . '/createDocentes';

    $payload = [
        "nombreDocente" => $request->nombreDocente,
        "apPaternoDocente" => $request->apPaternoDocente,
        "apMaternoDocente" => $request->apMaternoDocente,
        "correoDocente" => $request->correoDocente,
        "telefonoDocente" => $request->telefonoDocente,
        "statusDocente" => $request->statusDocente,
        "observacionesDocente" => $request->observacionesDocente,
        "nivelEstudios" => $request->nivelEstudios,
        "fechaNacimiento" => $request->fechaNacimiento,
        "idBiometrico" => $request->idBiometrico,
        "usuario" => $request->usuario ? trim($request->usuario) : null
    ];

    if ($request->filled('password')) {
        $payload['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
    }

    $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ])->post($url, $payload);

    // 🔥 DEBUG REAL (IMPORTANTE)
    if ($response->failed()) {
        return response()->json([
            'success' => false,
            'status' => $response->status(),
            'error' => $response->body()
        ], 500);
    }

    return response()->json([
        'success' => true,
        'data' => $response->json()
    ]);
}

public function show($id)
{
    $url = config('services.api.base_url') . '/docentes';

    $response = Http::get($url);

    if ($response->failed()) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener los detalles del docente'
        ], 500);
    }

    $docentesData = $response->json();
    $docente = null;
    if (isset($docentesData['data']) && is_array($docentesData['data'])) {
        foreach ($docentesData['data'] as $d) {
            if ($d['idDocente'] == $id) {
                $docente = $d;
                break;
            }
        }
    }

    return response()->json([
        'success' => true,
        'data' => $docente
    ]);
}

public function update(Request $request, $id)
{
    $url = config('services.api.base_url') . '/updateDocente/' . $id;

    $payload = [
        "nombreDocente" => $request->nombreDocente,
        "apPaternoDocente" => $request->apPaternoDocente,
        "apMaternoDocente" => $request->apMaternoDocente,
        "correoDocente" => $request->correoDocente,
        "telefonoDocente" => $request->telefonoDocente,
        "statusDocente" => $request->statusDocente,
        "observacionesDocente" => $request->observacionesDocente,
        "nivelEstudios" => $request->nivelEstudios,
        "fechaNacimiento" => $request->fechaNacimiento,
        "idBiometrico" => $request->idBiometrico,
        "usuario" => $request->usuario ? trim($request->usuario) : null
    ];

    if ($request->filled('password')) {
        $payload['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
    }

    $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ])->put($url, $payload);

    if ($response->failed()) {
        return response()->json([
            'success' => false,
            'status' => $response->status(),
            'error' => $response->body()
        ], 500);
    }

    return response()->json([
        'success' => true,
        'data' => $response->json()
    ]);
}

public function destroy($id)
{
    $url = config('services.api.base_url') . '/deleteDocente/' . $id;

    $response = Http::delete($url);

    if ($response->failed()) {
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar el docente'
        ], 500);
    }

    return response()->json([
        'success' => true,
        'message' => 'Docente eliminado correctamente'
    ]);
}

public function guardarCredenciales(Request $request, $id)
{
    $request->validate([
        'usuario' => 'required|string|max:100',
        'password' => 'nullable|string|min:4',
        'permisos_modulos' => 'nullable|array'
    ]);

    $modulosList = '';
    if ($request->has('permisos_modulos') && is_array($request->permisos_modulos)) {
        $modulosList = implode(',', $request->permisos_modulos);
    }

    $payload = [
        'usuario' => trim($request->usuario),
        'permisos_modulos' => $modulosList
    ];

    if ($request->filled('password')) {
        $payload['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
    }

    $url = config('services.api.base_url') . '/docentes/' . $id . '/credenciales';
    $response = Http::post($url, $payload);

    if ($response->failed()) {
        $resData = $response->json();
        return response()->json([
            'success' => false,
            'message' => $resData['error'] ?? 'Error al actualizar credenciales'
        ], 400);
    }

    return response()->json([
        'success' => true,
        'message' => 'Credenciales del docente actualizadas correctamente'
    ]);
}

public function horarioDocente()
{
    return view('docentes.horario');
}

public function getHorarioDocente($id)
{
    $url = config('services.api.base_url') . '/getHorariosDocente/' . $id;
    $response = Http::get($url);

    if ($response->failed()) {
        return response()->json([], $response->status());
    }

    return response()->json($response->json());
}

public function pendientes()
{
    return view('docentes.pendientes');
}

public function getPendingItems()
{
    $idDocente = session('id_docente');
    
    // Allow admin preview
    if (!$idDocente && request()->has('id_docente') && strtoupper(session('rol')) === 'ADMIN') {
        $idDocente = request()->query('id_docente');
    }

    if (!$idDocente) {
        return response()->json([
            'prorrogas' => [],
            'por_finalizar' => [],
            'calificaciones_pendientes' => []
        ]);
    }

    $url = config('services.api.base_url') . '/docentes/' . $idDocente . '/pendientes';
    $response = Http::get($url);

    if ($response->failed()) {
        return response()->json([
            'prorrogas' => [],
            'por_finalizar' => [],
            'calificaciones_pendientes' => []
        ], $response->status());
    }

    return response()->json($response->json());
}
}