<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Muestra la vista principal del dashboard.
     */
    public function index()
    {
        if (session('rol') === 'DOCENTE') {
            return redirect()->route('horarios_docentes');
        }
        return view('principal.home');
    }

    /**
     * Obtiene las métricas y datos del dashboard (vía Flask API con fallback a DB local).
     */
    public function stats()
    {
        // 1. Intentar obtener datos desde el backend Flask
        try {
            $url = rtrim(config('services.api.base_url', 'http://127.0.0.1:5000'), '/') . '/dashboard/stats';
            $response = Http::timeout(4)->get($url);

            if ($response->successful()) {
                return response()->json($response->json());
            }
        } catch (\Exception $e) {
            Log::warning('DashboardController: Falló llamada a Flask API, usando fallback local DB: ' . $e->getMessage());
        }

        // 2. Fallback de alta resiliencia directo a la Base de Datos
        try {
            // Alumnos
            $alumnos = DB::table('tb_alumnos')->selectRaw("
                COUNT(*) as total_alumnos,
                SUM(CASE WHEN statusAlumno = 'ACTIVO' THEN 1 ELSE 0 END) as alumnos_activos,
                SUM(CASE WHEN statusAlumno = 'CERTIFICADO' THEN 1 ELSE 0 END) as alumnos_certificados,
                SUM(CASE WHEN statusAlumno = 'INACTIVO' THEN 1 ELSE 0 END) as alumnos_inactivos,
                SUM(CASE WHEN modalidad_estudio = 'ONLINE' THEN 1 ELSE 0 END) as modalidad_online,
                SUM(CASE WHEN modalidad_estudio = 'PRESENCIAL' OR modalidad_estudio IS NULL THEN 1 ELSE 0 END) as modalidad_presencial
            ")->first();

            $totalDocentes = DB::table('tb_docentes')->count();
            $totalGrupos = DB::table('tb_grupos')->count();
            $totalMaterias = DB::table('tb_materias')->count();

            // Asistencias
            $asistencias = DB::table('tb_asistencias_alumnos')->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN estatus = 'A' THEN 1 ELSE 0 END) as presentes,
                SUM(CASE WHEN estatus = 'F' THEN 1 ELSE 0 END) as faltas,
                SUM(CASE WHEN estatus = 'R' THEN 1 ELSE 0 END) as retardos,
                SUM(CASE WHEN estatus = 'J' THEN 1 ELSE 0 END) as justificaciones
            ")->first();

            $totAsist = (int)($asistencias->total ?? 0);
            $presentes = (int)($asistencias->presentes ?? 0);
            $faltas = (int)($asistencias->faltas ?? 0);
            $retardos = (int)($asistencias->retardos ?? 0);
            $justificaciones = (int)($asistencias->justificaciones ?? 0);
            $tasaAsistencia = $totAsist > 0 ? round(($presentes / $totAsist) * 100, 1) : 100.0;

            // Calificaciones
            $calificaciones = DB::table('tb_calificaciones')->selectRaw("
                COUNT(*) as total,
                AVG(calificacion) as promedio,
                SUM(CASE WHEN calificacion >= 6 THEN 1 ELSE 0 END) as aprobados,
                SUM(CASE WHEN calificacion < 6 THEN 1 ELSE 0 END) as reprobados
            ")->first();

            $totCalif = (int)($calificaciones->total ?? 0);
            $aprobados = (int)($calificaciones->aprobados ?? 0);
            $reprobados = (int)($calificaciones->reprobados ?? 0);
            $promedio = round((float)($calificaciones->promedio ?? 0), 2);
            $tasaAprobacion = $totCalif > 0 ? round(($aprobados / $totCalif) * 100, 1) : 100.0;

            // Grupos con alumnos
            $gruposConAlumnos = DB::table('tb_grupos as g')
                ->leftJoin('tb_alumnogrupo as ag', 'ag.idGrupo', '=', 'g.id')
                ->select('g.id', 'g.clave', 'g.modalidad', 'g.horario', DB::raw('COUNT(ag.idAlumno) as total_alumnos'))
                ->groupBy('g.id', 'g.clave', 'g.modalidad', 'g.horario')
                ->orderByDesc('total_alumnos')
                ->orderBy('g.clave')
                ->get();

            // Reportes recientes
            $reportesRecientes = [];
            try {
                $reportesRecientes = DB::table('tb_reportes_indisciplina')
                    ->select('id', 'folio', 'id_alumno', 'alumno_nombre', 'tutor_nombre', 'fecha', 'incidente', 'parcial')
                    ->orderByDesc('id')
                    ->limit(5)
                    ->get()
                    ->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'folio' => $item->folio,
                            'id_alumno' => $item->id_alumno,
                            'alumno_nombre' => $item->alumno_nombre,
                            'tutor_nombre' => $item->tutor_nombre,
                            'fecha' => $item->fecha ? date('d/m/Y', strtotime($item->fecha)) : '',
                            'incidente' => $item->incidente,
                            'parcial' => $item->parcial
                        ];
                    });
            } catch (\Exception $e) {
                $reportesRecientes = [];
            }

            // Centros
            $centros = DB::table('tb_centrotrabajo')->select('id', 'clave', 'nombre')->get();

            return response()->json([
                'success' => true,
                'kpis' => [
                    'total_alumnos' => (int)($alumnos->total_alumnos ?? 0),
                    'alumnos_activos' => (int)($alumnos->alumnos_activos ?? 0),
                    'alumnos_certificados' => (int)($alumnos->alumnos_certificados ?? 0),
                    'alumnos_inactivos' => (int)($alumnos->alumnos_inactivos ?? 0),
                    'modalidad_online' => (int)($alumnos->modalidad_online ?? 0),
                    'modalidad_presencial' => (int)($alumnos->modalidad_presencial ?? 0),
                    'total_docentes' => $totalDocentes,
                    'total_grupos' => $totalGrupos,
                    'total_materias' => $totalMaterias,
                    'tasa_asistencia' => $tasaAsistencia,
                    'presentes' => $presentes,
                    'faltas' => $faltas,
                    'retardos' => $retardos,
                    'justificaciones' => $justificaciones,
                    'promedio_general' => $promedio,
                    'aprobados' => $aprobados,
                    'reprobados' => $reprobados,
                    'tasa_aprobacion' => $tasaAprobacion,
                    'total_reportes' => count($reportesRecientes)
                ],
                'charts' => [
                    'grupos' => $gruposConAlumnos->map(function ($g) {
                        return [
                            'id' => $g->id,
                            'clave' => $g->clave,
                            'total' => (int)$g->total_alumnos
                        ];
                    }),
                    'estatus_alumnos' => [
                        'activos' => (int)($alumnos->alumnos_activos ?? 0),
                        'certificados' => (int)($alumnos->alumnos_certificados ?? 0),
                        'inactivos' => (int)($alumnos->alumnos_inactivos ?? 0)
                    ],
                    'asistencias' => [
                        'presentes' => $presentes,
                        'faltas' => $faltas,
                        'retardos' => $retardos,
                        'justificaciones' => $justificaciones
                    ],
                    'calificaciones' => [
                        'aprobados' => $aprobados,
                        'reprobados' => $reprobados
                    ]
                ],
                'centros' => $centros,
                'reportes_recientes' => $reportesRecientes
            ]);
        } catch (\Exception $e) {
            Log::error('Error crítico en DashboardController fallback: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar métricas: ' . $e->getMessage()
            ], 500);
        }
    }
}
