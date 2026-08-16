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
        $baseApiUrl = config('services.api.base_url');

        $params = [];
        if ($request->has('id_materia')) {
            $params['id_materia'] = $request->get('id_materia');
        }

        if (session('rol') === 'DOCENTE') {
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
        $alumnos = $attendanceData['alumnos'];
        $fechas = $attendanceData['fechas'];
        $asistencias = $attendanceData['asistencias'];
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
            $fechas = array_values(array_filter($fechas, function($f) use ($activeLevel) {
                return intval($f['id_nivel_academico']) === intval($activeLevel);
            }));

            // Si la materia seleccionada por defecto no pertenece al nivel activo y hay materias en dicho nivel, redireccionar
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

        // Proxy de guardado masivo a Flask
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
