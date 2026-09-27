<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AsistenciaAlumnoController extends Controller
{
    public function index(Request $request)
    {
        $baseApiUrl = config('services.api.base_url');

        $params = [
            'limit' => 1000,
            'status_grupo' => 'ACTIVO'
        ];

        // Si el usuario es docente, filtrar grupos por su id_docente
        if (session('rol') === 'DOCENTE') {
            $params['id_docente'] = session('id_docente');
        }

        // Obtener listado de grupos activos
        $response = Http::get($baseApiUrl . '/grupos', $params);

        $grupos = [];
        if ($response->successful()) {
            $data = $response->json();
            $grupos = $data['data'] ?? [];
        }

        // Obtener IDs de grupos que ya pasaron lista hoy desde la API
        $responseHoy = Http::get($baseApiUrl . '/asistencias/alumnos/hoy');
        $gruposConAsistenciaHoy = $responseHoy->successful() ? $responseHoy->json() : [];
        
        return view('alumnos.asistencias_index', compact('grupos', 'gruposConAsistenciaHoy'));
    }

    public function grupoGrid(Request $request, $id_grupo)
    {
        try {
            $baseApiUrl = config('services.api.base_url');
            $esDocente = session('rol') === 'DOCENTE';
            $esGeneral = !$esDocente && (!$request->has('id_materia') || $request->get('id_materia') === 'general');

            $params = [];
            if (!$esGeneral && $request->has('id_materia')) {
                $params['id_materia'] = $request->get('id_materia');
            }

            if ($esDocente) {
                $params['id_docente'] = session('id_docente');
            } elseif ($request->has('id_docente')) {
                $params['id_docente'] = $request->get('id_docente');
            }

            // Obtener la matriz de asistencia de Flask
            $response = Http::get($baseApiUrl . '/asistencias/alumnos/grupo/' . $id_grupo, $params);

            if ($response->failed()) {
                return redirect()->route('asistencias_alumnos')->with('error', 'Error al obtener datos de asistencia de la API.');
            }

            $attendanceData = $response->json();

            if (isset($attendanceData['error'])) {
                return redirect()->route('asistencias_alumnos')->with('error', $attendanceData['error']);
            }

            $grupo = $attendanceData['grupo'];
            $rawAlumnos = $attendanceData['alumnos'] ?? [];
            $alumnos = array_values(array_filter($rawAlumnos, function($a) {
                $st = strtoupper($a['statusAlumno'] ?? $a['estado'] ?? 'ACTIVO');
                return $st === 'ACTIVO';
            }));
            $fechas = $attendanceData['fechas'] ?? [];
            $asistencias = $attendanceData['asistencias'] ?? [];
            $materias = $attendanceData['materias'] ?? [];
            $selected_materia_id = $attendanceData['selected_materia_id'] ?? null;

            // Obtener nivel académico activo del grupo de la respuesta API de Python
            $activeLevel = $grupo['active_level'] ?? null;

            if ($activeLevel) {
                // Filtrar materias del nivel activo o nivel común/nulo
                $materias = array_values(array_filter($materias, function($m) use ($activeLevel) {
                    return is_null($m['id_nivel_academico']) || intval($m['id_nivel_academico']) === intval($activeLevel);
                }));

                // Filtrar fechas del nivel activo
                $fechasFiltradas = array_values(array_filter($fechas, function($f) use ($activeLevel) {
                    return intval($f['id_nivel_academico']) === intval($activeLevel);
                }));

                // Si el filtrado contiene fechas, asignarlo; caso contrario mantener todas las fechas
                if (!empty($fechasFiltradas)) {
                    $fechas = $fechasFiltradas;
                }
            }

            // Si es Pase de Lista General (Administradores)
            if ($esGeneral) {
                $selected_materia_id = 'general';

                // Consolidar asistencias del grupo para la vista general si la BD directa está disponible
                try {
                    $asistenciasRaw = \Illuminate\Support\Facades\DB::table('tb_asistencias_alumnos')
                        ->where('id_grupo', $id_grupo)
                        ->select('id_alumno', 'fecha', 'id_nivel_academico', 'estatus', 'observaciones')
                        ->orderBy('updated_at', 'desc')
                        ->get();

                    if ($asistenciasRaw->isNotEmpty()) {
                        $mapAsistencias = [];
                        foreach ($asistenciasRaw as $ar) {
                            $key = $ar->fecha . '_' . $ar->id_alumno;
                            if (!isset($mapAsistencias[$key])) {
                                $mapAsistencias[$key] = [
                                    'id_alumno' => $ar->id_alumno,
                                    'fecha' => $ar->fecha,
                                    'id_nivel_academico' => $ar->id_nivel_academico,
                                    'estatus' => $ar->estatus,
                                    'observaciones' => $ar->observaciones
                                ];
                            }
                        }
                        $asistencias = array_values($mapAsistencias);
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning("Direct DB query in grupoGrid failed: " . $e->getMessage() . ". Using API attendance data.");
                }
            } else {
                // Validación para docentes o materias individuales seleccionadas
                if (!empty($materias)) {
                    $materiaIds = array_map(function($m) { return intval($m['idMateria']); }, $materias);
                    if ($selected_materia_id !== null && !in_array(intval($selected_materia_id), $materiaIds)) {
                        return redirect()->route('asistencias_alumnos.grupo', [
                            'id_grupo' => $id_grupo,
                            'id_materia' => $materias[0]['idMateria']
                        ]);
                    }
                }
            }

                        $reaperturasFechas = $attendanceData['reaperturas_fechas'] ?? [];
            try {
                $dbReaperturas = \Illuminate\Support\Facades\DB::table('tb_asistencias_reaperturas')
                    ->where('id_grupo', $id_grupo)
                    ->where('habilitado', 1)
                    ->pluck('fecha')
                    ->map(function($f) { return is_string($f) ? substr($f, 0, 10) : (string)$f; })
                    ->toArray();
                $reaperturasFechas = array_values(array_unique(array_merge($reaperturasFechas, $dbReaperturas)));
            } catch (\Throwable $e) {}

            return view('alumnos.asistencias_grid', compact('grupo', 'alumnos', 'fechas', 'asistencias', 'materias', 'selected_materia_id', 'reaperturasFechas'));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Error en grupoGrid para grupo $id_grupo: " . $e->getMessage() . " en " . $e->getFile() . ":" . $e->getLine());
            return redirect()->route('asistencias_alumnos')->with('error', 'Ocurrió un problema al cargar el grupo: ' . $e->getMessage());
        }
    }

    public function guardar(Request $request)
    {
        $baseApiUrl = config('services.api.base_url');

        $rol = session('rol');
        $data = $request->all();

        // Si el usuario es docente, forzar id_docente de la sesión y validar que la fecha sea estrictamente hoy
        if ($rol === 'DOCENTE') {
            $data['id_docente'] = session('id_docente');
            
            $hoy = date('Y-m-d');
            $asistencias = $request->get('asistencias', []);
            foreach ($asistencias as $a) {
                if ($a['fecha'] !== $hoy) {
                    return response()->json([
                        'error' => 'Como docente, solo tienes permitido registrar la asistencia del día de hoy.'
                    ], 403);
                }
            }

            // Validar si el docente ya envió previamente el pase de lista de hoy
            $id_grupo = $data['id_grupo'] ?? null;
            $id_materia = $data['id_materia'] ?? null;
            if ($id_grupo) {
                $queryAsistenciasHoy = \Illuminate\Support\Facades\DB::table('tb_asistencias_alumnos')
                    ->where('id_grupo', $id_grupo)
                    ->where('fecha', $hoy)
                    ->whereNotNull('estatus');

                if ($id_materia && $id_materia !== 'general') {
                    $queryAsistenciasHoy->where('id_materia', $id_materia);
                }

                $reaperturaActiva = false;
                try {
                    $reaperturaActiva = \Illuminate\Support\Facades\DB::table('tb_asistencias_reaperturas')
                        ->where('id_grupo', $id_grupo)
                        ->where('fecha', $hoy)
                        ->where('habilitado', 1)
                        ->where(function($q) use ($id_materia) {
                            $q->whereNull('id_materia')
                              ->orWhere('id_materia', 0);
                            if ($id_materia && $id_materia !== 'general') {
                                $q->orWhere('id_materia', $id_materia);
                            }
                        })
                        ->exists();
                } catch (\Throwable $e) {}

                if ($queryAsistenciasHoy->exists() && !$reaperturaActiva) {
                    return response()->json([
                        'error' => 'El pase de lista de hoy ya fue enviado previamente y se encuentra cerrado. Solo el administrador puede realizar modificaciones.'
                    ], 403);
                }
            }
        }

        // Si es Pase de Lista General (guardar para todas las materias del grupo)
        if (($data['id_materia'] ?? null) === 'general') {
            $id_grupo = $data['id_grupo'];
            $asistenciasList = $data['asistencias'] ?? [];

            $dbDirectSucceeded = false;
            try {
                // Obtener todas las materias y docentes del grupo desde tb_horarios
                $horarios = \Illuminate\Support\Facades\DB::table('tb_horarios as h')
                    ->join('tb_materias as m', 'h.id_materia', '=', 'm.id')
                    ->where('h.id_grupo', $id_grupo)
                    ->select('h.id_materia', 'h.id_docente', 'm.id_nivel_academico')
                    ->distinct()
                    ->get();

                // Si el grupo no tiene horarios configurados, buscar materias asignables al CCT del grupo
                if ($horarios->isEmpty()) {
                    $grupoInfo = \Illuminate\Support\Facades\DB::table('tb_grupos')->where('id', $id_grupo)->first();
                    $cct = $grupoInfo->id_centroTrabajo ?? 3;
                    $materiasDb = \Illuminate\Support\Facades\DB::table('tb_materias')
                        ->where(function($q) use ($cct) {
                            $q->where('idCentroTrabajo', $cct)->orWhereNull('idCentroTrabajo');
                        })
                        ->get();
                    $horarios = $materiasDb->map(function($m) {
                        return (object)[
                            'id_materia' => $m->id,
                            'id_docente' => 1,
                            'id_nivel_academico' => $m->id_nivel_academico
                        ];
                    });
                }

                foreach ($asistenciasList as $a) {
                    $idAlumno = $a['id_alumno'];
                    $fecha = $a['fecha'];
                    $estatus = $a['estatus'] ?? null;
                    $obs = $a['observaciones'] ?? null;
                    $idNivel = $a['id_nivel_academico'] ?? null;

                    if ($estatus === null || $estatus === '') {
                        \Illuminate\Support\Facades\DB::table('tb_asistencias_alumnos')
                            ->where('id_grupo', $id_grupo)
                            ->where('id_alumno', $idAlumno)
                            ->where('fecha', $fecha)
                            ->delete();
                    } else {
                        foreach ($horarios as $h) {
                            \Illuminate\Support\Facades\DB::table('tb_asistencias_alumnos')->upsert([
                                'id_alumno' => $idAlumno,
                                'id_materia' => $h->id_materia,
                                'id_docente' => $h->id_docente ?: 1,
                                'id_grupo' => $id_grupo,
                                'fecha' => $fecha,
                                'id_nivel_academico' => $idNivel ?: $h->id_nivel_academico,
                                'estatus' => $estatus,
                                'observaciones' => $obs,
                            ], ['id_alumno', 'id_grupo', 'id_materia', 'fecha'], ['estatus', 'id_nivel_academico', 'observaciones', 'id_docente']);
                        }
                    }
                }
                $dbDirectSucceeded = true;
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("Direct DB guardar failed: " . $e->getMessage() . ". Delegating to API.");
            }

            if ($dbDirectSucceeded) {
                if ($rol === 'DOCENTE') {
                    try {
                        \Illuminate\Support\Facades\DB::table('tb_asistencias_reaperturas')
                            ->where('id_grupo', $id_grupo)
                            ->where('fecha', date('Y-m-d'))
                            ->where('habilitado', 1)
                            ->update(['habilitado' => 0, 'updated_at' => now()]);
                    } catch (\Throwable $e) {}
                }
                return response()->json([
                    'mensaje' => 'Asistencias generales guardadas correctamente en todas las materias del grupo.'
                ]);
            }
        }

        // Proxy de guardado a Flask (para materia individual o fallback de general)
        $response = Http::post($baseApiUrl . '/asistencias/alumnos/guardar', $data);

        if ($response->successful() && $rol === 'DOCENTE') {
            try {
                $id_grp = $data['id_grupo'] ?? null;
                $hoy = date('Y-m-d');
                if ($id_grp) {
                    \Illuminate\Support\Facades\DB::table('tb_asistencias_reaperturas')
                        ->where('id_grupo', $id_grp)
                        ->where('fecha', $hoy)
                        ->where('habilitado', 1)
                        ->update(['habilitado' => 0, 'updated_at' => now()]);
                }
            } catch (\Throwable $e) {}
        }

        return response()->json($response->json(), $response->status());
    }

    public function justificar(Request $request)
    {
        // Solo administradores pueden justificar
        if (session('rol') !== 'ADMIN') {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $baseApiUrl = config('services.api.base_url');

        // Enviar petición de justificación a Flask
        $response = Http::post($baseApiUrl . '/asistencias/alumnos/justificar', [
            'id_alumno' => $request->get('id_alumno'),
            'fecha_inicio' => $request->get('fecha_inicio'),
            'fecha_fin' => $request->get('fecha_fin'),
            'motivo' => $request->get('motivo')
        ]);

        return response()->json($response->json(), $response->status());
    }

    public function reabrirPase(Request $request)
    {
        if (session('rol') === 'DOCENTE') {
            return response()->json(['error' => 'No tienes permisos para reabrir pases de lista.'], 403);
        }

        $id_grupo = $request->input('id_grupo');
        $fecha = $request->input('fecha') ?? date('Y-m-d');
        $id_materia = $request->input('id_materia');
        $habilitar = $request->boolean('habilitar', true);
        $usuario = session('usuario') ?? session('nombre') ?? 'Administrador';

        try {
            \Illuminate\Support\Facades\DB::statement("
                CREATE TABLE IF NOT EXISTS tb_asistencias_reaperturas (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    id_grupo INT NOT NULL,
                    id_materia INT NULL,
                    fecha DATE NOT NULL,
                    autorizado_por VARCHAR(100) NULL,
                    habilitado TINYINT(1) NOT NULL DEFAULT 1,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    KEY idx_reapertura_lookup (id_grupo, fecha, habilitado)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
        } catch (\Throwable $e) {}

        try {
            $matId = ($id_materia && $id_materia !== 'general') ? intval($id_materia) : null;
            if ($habilitar) {
                \Illuminate\Support\Facades\DB::table('tb_asistencias_reaperturas')->insert([
                    'id_grupo' => $id_grupo,
                    'id_materia' => $matId,
                    'fecha' => $fecha,
                    'autorizado_por' => $usuario,
                    'habilitado' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                \Illuminate\Support\Facades\DB::table('tb_asistencias_reaperturas')
                    ->where('id_grupo', $id_grupo)
                    ->where('fecha', $fecha)
                    ->update(['habilitado' => 0, 'updated_at' => now()]);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Direct DB reabrir failed: " . $e->getMessage());
        }

        // Notificar a API Flask
        try {
            $baseApiUrl = config('services.api.base_url');
            \Illuminate\Support\Facades\Http::post($baseApiUrl . '/asistencias/alumnos/reabrir', [
                'id_grupo' => $id_grupo,
                'fecha' => $fecha,
                'id_materia' => ($id_materia && $id_materia !== 'general') ? intval($id_materia) : null,
                'autorizado_por' => $usuario,
                'habilitar' => $habilitar
            ]);
        } catch (\Throwable $e) {}

        return response()->json([
            'success' => true,
            'habilitado' => $habilitar,
            'mensaje' => $habilitar
                ? 'Se ha habilitado el permiso al docente para realizar/modificar el pase de lista de la fecha ' . $fecha . '.'
                : 'Se ha cerrado el permiso al docente para la fecha ' . $fecha . '.'
        ]);
    }
}
