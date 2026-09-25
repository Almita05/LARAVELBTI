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

            return view('alumnos.asistencias_grid', compact('grupo', 'alumnos', 'fechas', 'asistencias', 'materias', 'selected_materia_id'));
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
                return response()->json([
                    'mensaje' => 'Asistencias generales guardadas correctamente en todas las materias del grupo.'
                ]);
            }
        }

        // Proxy de guardado a Flask (para materia individual o fallback de general)
        $response = Http::post($baseApiUrl . '/asistencias/alumnos/guardar', $data);

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
}
