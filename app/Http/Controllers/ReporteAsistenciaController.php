<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ReporteAsistenciaController extends Controller
{
    public function index(Request $request)
    {
        $baseApiUrl = config('services.api.base_url');
        
        // Obtener listado de grupos activos a través de la API para mantener consistencia
        $response = Http::get($baseApiUrl . '/grupos', [
            'limit' => 1000,
            'status_grupo' => 'ACTIVO'
        ]);

        $grupos = [];
        if ($response->successful()) {
            $data = $response->json();
            $grupos = $data['data'] ?? [];
        } else {
            // Fallback directo a la base de datos si la API falla
            $grupos = DB::table('tb_grupos')
                ->where('statusGrupo', 'ACTIVO')
                ->orderBy('clave', 'asc')
                ->get()
                ->toArray();
            // Convertir stdClass a array
            $grupos = array_map(function($g) {
                return (array)$g;
            }, $grupos);
        }

        return view('alumnos.reportes_asistencias', compact('grupos'));
    }

    public function getGrupoReport(Request $request, $id_grupo)
    {
        // 1. Obtener metadatos del grupo
        $grupo = DB::table('tb_grupos')
            ->where('id', $id_grupo)
            ->first();

        if (!$grupo) {
            return response()->json(['error' => 'Grupo no encontrado'], 404);
        }

        // 2. Obtener alumnos inscritos activos en el grupo
        $alumnos = DB::table('tb_alumnogrupo')
            ->join('tb_alumnos', 'tb_alumnogrupo.idAlumno', '=', 'tb_alumnos.idAlumno')
            ->where('tb_alumnogrupo.idGrupo', $id_grupo)
            ->where('tb_alumnogrupo.estado', 'ACTIVO')
            ->select('tb_alumnos.idAlumno', 'tb_alumnos.nombre', 'tb_alumnos.apPaterno', 'tb_alumnos.apMaterno', 'tb_alumnos.numeroControl')
            ->orderBy('tb_alumnos.apPaterno', 'asc')
            ->orderBy('tb_alumnos.apMaterno', 'asc')
            ->orderBy('tb_alumnos.nombre', 'asc')
            ->get();

        // 3. Obtener materias y docentes asociados en tb_horarios
        $materias = DB::table('tb_horarios')
            ->join('tb_materias', 'tb_horarios.id_materia', '=', 'tb_materias.id')
            ->leftJoin('tb_docentes', 'tb_horarios.id_docente', '=', 'tb_docentes.idDocente')
            ->where('tb_horarios.id_grupo', $id_grupo)
            ->where('tb_horarios.es_prehorario', 0)
            ->select(
                'tb_materias.id as id_materia',
                'tb_materias.nombreMateria',
                'tb_materias.clave',
                'tb_horarios.id_docente',
                DB::raw("CONCAT_WS(' ', tb_docentes.nombreDocente, COALESCE(tb_docentes.apPaternoDocente, ''), COALESCE(tb_docentes.apMaternoDocente, '')) as docente_nombre")
            )
            ->distinct()
            ->orderBy('tb_materias.nombreMateria', 'asc')
            ->get();

        // Si no hay horarios configurados, traer materias según el CCT del grupo
        if ($materias->isEmpty()) {
            $materias = DB::table('tb_materias')
                ->where('idCentroTrabajo', $grupo->id_centroTrabajo)
                ->orWhereNull('idCentroTrabajo')
                ->select(
                    'id as id_materia',
                    'nombreMateria',
                    'clave',
                    DB::raw("NULL as id_docente"),
                    DB::raw("'Sin docente asignado' as docente_nombre")
                )
                ->orderBy('nombreMateria', 'asc')
                ->get();
        }

        // 4. Calcular estadísticas de asistencia para cada materia
        $reporteMaterias = [];
        foreach ($materias as $mat) {
            // Contar asistencias registradas
            $stats = DB::table('tb_asistencias_alumnos')
                ->where('id_grupo', $id_grupo)
                ->where('id_materia', $mat->id_materia)
                ->select(
                    DB::raw("SUM(CASE WHEN estatus = 'A' THEN 1 ELSE 0 END) as total_a"),
                    DB::raw("SUM(CASE WHEN estatus = 'F' THEN 1 ELSE 0 END) as total_f"),
                    DB::raw("SUM(CASE WHEN estatus = 'R' THEN 1 ELSE 0 END) as total_r"),
                    DB::raw("SUM(CASE WHEN estatus = 'J' THEN 1 ELSE 0 END) as total_j"),
                    DB::raw("COUNT(*) as total_registros")
                )
                ->first();

            $total_a = intval($stats->total_a ?? 0);
            $total_f = intval($stats->total_f ?? 0);
            $total_r = intval($stats->total_r ?? 0);
            $total_j = intval($stats->total_j ?? 0);
            $total_registros = intval($stats->total_registros ?? 0);

            // Calcular porcentaje: (Asistencias + Retardos) / (Asistencias + Retardos + Faltas)
            $valid_denominator = $total_a + $total_r + $total_f;
            $percentage = 100.0;
            if ($valid_denominator > 0) {
                $percentage = round((($total_a + $total_r) / $valid_denominator) * 100, 1);
            } elseif ($total_registros == 0) {
                $percentage = null; // Indica que no hay pases de lista registrados
            }

            $reporteMaterias[] = [
                'id_materia' => $mat->id_materia,
                'nombreMateria' => $mat->nombreMateria,
                'clave' => $mat->clave,
                'docente_nombre' => $mat->docente_nombre ?: 'Sin docente asignado',
                'asistencias' => $total_a,
                'faltas' => $total_f,
                'retardos' => $total_r,
                'justificadas' => $total_j,
                'total_registros' => $total_registros,
                'porcentaje' => $percentage
            ];
        }

        return response()->json([
            'grupo' => [
                'id' => $grupo->id,
                'clave' => $grupo->clave,
                'horario' => $grupo->horario,
                'modalidad' => $grupo->modalidadHorario
            ],
            'alumnos' => $alumnos,
            'materias_reporte' => $reporteMaterias
        ]);
    }

    public function getAlumnoHistorial(Request $request, $id_grupo, $id_alumno)
    {
        // 1. Obtener datos del alumno
        $alumno = DB::table('tb_alumnos')
            ->where('idAlumno', $id_alumno)
            ->first();

        if (!$alumno) {
            return response()->json(['error' => 'Alumno no encontrado'], 404);
        }

        // 2. Obtener todas las fechas únicas de pases de lista registrados para este grupo
        $fechasReg = DB::table('tb_asistencias_alumnos')
            ->where('id_grupo', $id_grupo)
            ->select('fecha')
            ->distinct()
            ->orderBy('fecha', 'desc')
            ->pluck('fecha');

        // 3. Consultar los horarios programados del grupo (para emparejar qué materias le tocaban en cada día)
        $horariosGrupo = DB::table('tb_horarios')
            ->join('tb_materias', 'tb_horarios.id_materia', '=', 'tb_materias.id')
            ->leftJoin('tb_docentes', 'tb_horarios.id_docente', '=', 'tb_docentes.idDocente')
            ->where('tb_horarios.id_grupo', $id_grupo)
            ->where('tb_horarios.es_prehorario', 0)
            ->select(
                'tb_horarios.id_horario',
                'tb_horarios.diaSemana',
                'tb_horarios.horaInicio',
                'tb_horarios.horaFin',
                'tb_horarios.aula',
                'tb_horarios.id_materia',
                'tb_materias.nombreMateria',
                'tb_horarios.id_docente',
                DB::raw("CONCAT_WS(' ', tb_docentes.nombreDocente, COALESCE(tb_docentes.apPaternoDocente, ''), COALESCE(tb_docentes.apMaternoDocente, '')) as docente_nombre")
            )
            ->orderBy('tb_horarios.horaInicio', 'asc')
            ->get();

        // 4. Mapear asistencias ya guardadas para este alumno
        $asistenciasAlumno = DB::table('tb_asistencias_alumnos')
            ->where('id_grupo', $id_grupo)
            ->where('id_alumno', $id_alumno)
            ->get()
            ->groupBy(function($item) {
                return is_string($item->fecha) ? $item->fecha : $item->fecha->format('Y-m-d');
            });

        // 5. Armar el historial cronológico
        $historial = [];
        
        // Mapeador de día de la semana para carbon/dates
        // diaSemana en tb_horarios: 1=Lunes, 2=Martes, 3=Miércoles, 4=Jueves, 5=Viernes, 6=Sábado, 7=Domingo
        foreach ($fechasReg as $fechaObj) {
            $fechaStr = is_string($fechaObj) ? $fechaObj : $fechaObj->format('Y-m-d');
            $timestamp = strtotime($fechaStr);
            $dayOfWeek = intval(date('N', $timestamp)); // 1 (Lunes) a 7 (Domingo)

            // Filtrar las materias que tocan ese día de la semana en el horario
            $clasesDelDia = $horariosGrupo->filter(function($h) use ($dayOfWeek) {
                return intval($h->diaSemana) === $dayOfWeek;
            });

            // Si hay clases programadas, cruzarlas con el estatus
            $clasesList = [];
            foreach ($clasesDelDia as $clase) {
                // Buscar si hay asistencia registrada para este alumno, materia y fecha
                $asistenciaFecha = $asistenciasAlumno->get($fechaStr);
                $registro = null;
                if ($asistenciaFecha) {
                    $registro = $asistenciaFecha->first(function($val) use ($clase) {
                        return intval($val->id_materia) === intval($clase->id_materia);
                    });
                }

                $estatus = 'SIN_REGISTRO';
                $observaciones = '';
                if ($registro) {
                    $estatus = $registro->estatus ?: 'SIN_REGISTRO';
                    $observaciones = $registro->observaciones ?: '';
                }

                $clasesList[] = [
                    'horaInicio' => substr($clase->horaInicio, 0, 5),
                    'horaFin' => substr($clase->horaFin, 0, 5),
                    'nombreMateria' => $clase->nombreMateria,
                    'docente_nombre' => $clase->docente_nombre ?: 'Sin docente asignado',
                    'aula' => $clase->aula ?: 'Sin aula',
                    'estatus' => $estatus,
                    'observaciones' => $observaciones
                ];
            }

            // Si no hay clases programadas oficialmente en el horario para ese día (pero se tomó asistencia extraordinaria por ejemplo)
            // o si se quiere registrar los pases de lista que existan aunque no coincidan con el día del horario
            $asistenciaFecha = $asistenciasAlumno->get($fechaStr);
            if ($asistenciaFecha && $clasesDelDia->isEmpty()) {
                foreach ($asistenciaFecha as $reg) {
                    $materiaName = DB::table('tb_materias')->where('id', $reg->id_materia)->value('nombreMateria') ?: 'Materia Desconocida';
                    $docenteName = DB::table('tb_docentes')->where('idDocente', $reg->id_docente)->select(DB::raw("CONCAT_WS(' ', nombreDocente, COALESCE(apPaternoDocente, ''), COALESCE(apMaternoDocente, '')) as nombre"))->value('nombre') ?: 'Sin docente asignado';
                    
                    $clasesList[] = [
                        'horaInicio' => '--:--',
                        'horaFin' => '--:--',
                        'nombreMateria' => $materiaName,
                        'docente_nombre' => $docenteName,
                        'aula' => 'Extraordinaria',
                        'estatus' => $reg->estatus ?: 'SIN_REGISTRO',
                        'observaciones' => $reg->observaciones ?: ''
                    ];
                }
            }

            if (!empty($clasesList)) {
                $historial[] = [
                    'fecha' => date('d-m-Y', $timestamp),
                    'dia_nombre' => $this->getDiaNombre(date('l', $timestamp)),
                    'clases' => $clasesList
                ];
            }
        }

        return response()->json([
            'alumno' => [
                'idAlumno' => $alumno->idAlumno,
                'nombreCompleto' => trim("{$alumno->apPaterno} {$alumno->apMaterno} {$alumno->nombre}"),
                'numeroControl' => $alumno->numeroControl
            ],
            'historial' => $historial
        ]);
    }

    private function getDiaNombre($engDay)
    {
        $dias = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo'
        ];
        return $dias[$engDay] ?? $engDay;
    }
}
