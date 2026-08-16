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

    $hoy = date('Y-m-d');
    $proximos15Dias = date('Y-m-d', strtotime('+15 days'));

    // 1. Prórrogas activas
    $prorrogas = DB::table('tb_docente_permisos_captura as p')
        ->join('tb_grupos as g', 'p.id_grupo', '=', 'g.id')
        ->join('tb_materias as m', 'p.id_materia', '=', 'm.id')
        ->join('tb_horarios as h', function($join) {
            $join->on('p.id_grupo', '=', 'h.id_grupo')
                 ->on('p.id_materia', '=', 'h.id_materia')
                 ->on('p.id_docente', '=', 'h.id_docente');
        })
        ->select(
            'p.*',
            'g.clave as clave_grupo',
            'm.nombreMateria as nombre_materia',
            'm.clave as clave_materia',
            DB::raw("DATEDIFF(p.fecha_limite, '$hoy') as dias_restantes")
        )
        ->where('p.id_docente', $idDocente)
        ->where('p.habilitado', 1)
        ->where('p.fecha_limite', '>=', $hoy)
        ->orderBy('p.fecha_limite', 'asc')
        ->get();

    // 2. Grupos por finalizar (próximos 15 días)
    $porFinalizar = DB::table('tb_horarios as h')
        ->join('tb_grupos as g', 'h.id_grupo', '=', 'g.id')
        ->select(
            'g.id',
            'g.clave as clave_grupo',
            'g.fechaFin as fecha_fin',
            DB::raw("DATEDIFF(g.fechaFin, '$hoy') as dias_restantes")
        )
        ->where('h.id_docente', $idDocente)
        ->where('g.statusGrupo', 'ACTIVO')
        ->where('g.fechaFin', '>=', $hoy)
        ->where('g.fechaFin', '<=', $proximos15Dias)
        ->distinct()
        ->orderBy('g.fechaFin', 'asc')
        ->get();

    // 3. Calificaciones pendientes por capturar
    $clases = DB::table('tb_horarios as h')
        ->join('tb_grupos as g', 'h.id_grupo', '=', 'g.id')
        ->join('tb_materias as m', 'h.id_materia', '=', 'm.id')
        ->select(
            'g.id as id_grupo',
            'g.clave as clave_grupo',
            'm.id as id_materia',
            'm.nombreMateria as nombre_materia',
            'm.clave as clave_materia'
        )
        ->where('h.id_docente', $idDocente)
        ->where('g.statusGrupo', 'ACTIVO')
        ->where(function($q) {
            $q->whereNull('m.id_nivel_academico')
              ->orWhereColumn('m.id_nivel_academico', 'g.id_nivel_academico');
        })
        ->distinct()
        ->get();

    $calificacionesPendientes = [];

    foreach ($clases as $clase) {
        $alumnos = DB::table('tb_alumnogrupo as ag')
            ->join('tb_alumnos as a', 'ag.idAlumno', '=', 'a.idAlumno')
            ->where('ag.idGrupo', $clase->id_grupo)
            ->where('ag.estado', 'ACTIVO')
            ->where('a.statusAlumno', 'ACTIVO')
            ->select('a.idAlumno')
            ->get();

        if ($alumnos->isEmpty()) {
            continue;
        }

        $alumnosIds = $alumnos->pluck('idAlumno')->toArray();

        $calificadosCount = DB::table('tb_calificaciones')
            ->where('idMateria', $clase->id_materia)
            ->where('idGrupo', $clase->id_grupo)
            ->whereIn('idAlumno', $alumnosIds)
            ->whereNotNull('calificacion')
            ->count();

        $totalAlumnos = count($alumnosIds);
        $faltantes = $totalAlumnos - $calificadosCount;

        if ($faltantes > 0) {
            $permissionResult = app(GrupoController::class)->checkCapturaPermission($clase->id_grupo, $clase->id_materia);
            
            $calificacionesPendientes[] = [
                'id_grupo' => $clase->id_grupo,
                'clave_grupo' => $clase->clave_grupo,
                'id_materia' => $clase->id_materia,
                'nombre_materia' => $clase->nombre_materia,
                'clave_materia' => $clase->clave_materia,
                'alumnos_totales' => $totalAlumnos,
                'alumnos_faltantes' => $faltantes,
                'bloqueado' => !$permissionResult['allowed'],
                'motivo_bloqueo' => $permissionResult['reason']
            ];
        }
    }

    return response()->json([
        'prorrogas' => $prorrogas,
        'por_finalizar' => $porFinalizar,
        'calificaciones_pendientes' => $calificacionesPendientes
    ]);
}
}