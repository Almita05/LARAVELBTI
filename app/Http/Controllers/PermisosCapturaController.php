<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermisosCapturaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (strtoupper(session('rol')) !== 'ADMIN') {
                    abort(403, 'Acceso denegado. Solo administradores pueden gestionar permisos.');
                }
                return $next($request);
            }),
        ];
    }

    public function index()
    {
        return view('permisos_captura.index');
    }

    public function lista()
    {
        $permisos = DB::table('tb_docente_permisos_captura as p')
            ->leftJoin('tb_docentes as d', 'p.id_docente', '=', 'd.idDocente')
            ->leftJoin('tb_grupos as g', 'p.id_grupo', '=', 'g.id')
            ->leftJoin('tb_materias as m', 'p.id_materia', '=', 'm.id')
            ->select(
                'p.*',
                DB::raw("CONCAT(d.nombreDocente, ' ', IFNULL(d.apPaternoDocente, ''), ' ', IFNULL(d.apMaternoDocente, '')) as nombre_docente"),
                'g.clave as clave_grupo',
                'm.nombreMateria as nombre_materia',
                'm.clave as clave_materia'
            )
            ->orderBy('p.id', 'desc')
            ->get();

        return response()->json($permisos);
    }

    public function getDocentes()
    {
        $docentes = DB::table('tb_docentes')
            ->select('idDocente as id', DB::raw("CONCAT(nombreDocente, ' ', IFNULL(apPaternoDocente, ''), ' ', IFNULL(apMaternoDocente, '')) as nombre"))
            ->where('statusDocente', 'ACTIVO')
            ->orderBy('nombreDocente')
            ->get();

        return response()->json($docentes);
    }

    public function getGrupos()
    {
        $grupos = DB::table('tb_grupos')
            ->select('id', 'clave')
            ->where('statusGrupo', 'ACTIVO')
            ->orderBy('clave')
            ->get();

        return response()->json($grupos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_docente' => 'required|integer',
            'id_grupo' => 'required|integer',
            'id_materia' => 'required|integer',
            'fecha_limite' => 'nullable|date',
            'permitir_modificar_pasados' => 'required|boolean',
        ]);

        // Check if permission already exists for this combination
        $exists = DB::table('tb_docente_permisos_captura')
            ->where('id_docente', $request->id_docente)
            ->where('id_grupo', $request->id_grupo)
            ->where('id_materia', $request->id_materia)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe un permiso registrado para este docente en esta asignatura y grupo.'
            ], 422);
        }

        DB::table('tb_docente_permisos_captura')->insert([
            'id_docente' => $request->id_docente,
            'id_grupo' => $request->id_grupo,
            'id_materia' => $request->id_materia,
            'fecha_limite' => $request->fecha_limite ?: null,
            'permitir_modificar_pasados' => $request->permitir_modificar_pasados,
            'habilitado' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permiso asignado correctamente.'
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fecha_limite' => 'nullable|date',
            'permitir_modificar_pasados' => 'required|boolean',
            'habilitado' => 'required|boolean',
        ]);

        DB::table('tb_docente_permisos_captura')
            ->where('id', $id)
            ->update([
                'fecha_limite' => $request->fecha_limite ?: null,
                'permitir_modificar_pasados' => $request->permitir_modificar_pasados,
                'habilitado' => $request->habilitado,
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Permiso actualizado correctamente.'
        ]);
    }

    public function destroy($id)
    {
        DB::table('tb_docente_permisos_captura')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Permiso eliminado correctamente.'
        ]);
    }

    public function getMatrizAvance()
    {
        $hoy = date('Y-m-d');

        $horarios = DB::table('tb_horarios as h')
            ->join('tb_docentes as d', 'h.id_docente', '=', 'd.idDocente')
            ->join('tb_grupos as g', 'h.id_grupo', '=', 'g.id')
            ->join('tb_materias as m', 'h.id_materia', '=', 'm.id')
            ->select(
                'h.id_docente',
                'h.id_grupo',
                'h.id_materia',
                DB::raw("CONCAT(d.nombreDocente, ' ', IFNULL(d.apPaternoDocente, ''), ' ', IFNULL(d.apMaternoDocente, '')) as nombre_docente"),
                'g.clave as clave_grupo',
                'g.fechaFin as fecha_fin_grupo',
                'g.statusGrupo as status_grupo',
                'g.id_nivel_academico as id_nivel_grupo',
                'm.nombreMateria as nombre_materia',
                'm.clave as clave_materia',
                'm.id_nivel_academico as id_nivel_materia'
            )
            ->where(function($q) {
                $q->whereNull('m.id_nivel_academico')
                  ->orWhereRaw('m.id_nivel_academico = g.id_nivel_academico');
            })
            ->distinct()
            ->orderBy('g.clave', 'asc')
            ->orderBy('nombre_docente', 'asc')
            ->get();

        $matriz = [];

        foreach ($horarios as $h) {
            $alumnos = DB::table('tb_alumnogrupo as ag')
                ->join('tb_alumnos as a', 'ag.idAlumno', '=', 'a.idAlumno')
                ->where('ag.idGrupo', $h->id_grupo)
                ->where('ag.estado', 'ACTIVO')
                ->where('a.statusAlumno', 'ACTIVO')
                ->select('a.idAlumno')
                ->get();

            $totalAlumnos = count($alumnos);

            $gradedCount = 0;
            if ($totalAlumnos > 0) {
                $alumnosIds = $alumnos->pluck('idAlumno')->toArray();
                $gradedCount = DB::table('tb_calificaciones')
                    ->where('idMateria', $h->id_materia)
                    ->where('idGrupo', $h->id_grupo)
                    ->whereIn('idAlumno', $alumnosIds)
                    ->whereNotNull('calificacion')
                    ->count();
            }

            $permiso = DB::table('tb_docente_permisos_captura')
                ->where('id_docente', $h->id_docente)
                ->where('id_grupo', $h->id_grupo)
                ->where('id_materia', $h->id_materia)
                ->first();

            $estado = 'pendiente';
            $motivo = 'Captura en tiempo ordinario';
            $fechaLimite = $h->fecha_fin_grupo;

            if ($totalAlumnos === 0) {
                $estado = 'sin_alumnos';
                $motivo = 'Sin alumnos inscritos';
            } elseif ($gradedCount === $totalAlumnos) {
                $estado = 'completo';
                $motivo = 'Captura completa';
            } else {
                if ($permiso && !$permiso->habilitado) {
                    $estado = 'deshabilitado';
                    $motivo = 'Bloqueado por administrador';
                } else {
                    $esPeriodoPasado = false;
                    if ($h->id_nivel_grupo && $h->id_nivel_materia) {
                        $nivelGrupo = DB::table('tb_niveles_academicos')->where('id', $h->id_nivel_grupo)->first();
                        $nivelMateria = DB::table('tb_niveles_academicos')->where('id', $h->id_nivel_materia)->first();
                        if ($nivelGrupo && $nivelMateria && $nivelMateria->numero < $nivelGrupo->numero) {
                            $esPeriodoPasado = true;
                        }
                    }
                    if (strtoupper($h->status_grupo) !== 'ACTIVO') {
                        $esPeriodoPasado = true;
                    }

                    if ($esPeriodoPasado) {
                        if ($permiso && $permiso->permitir_modificar_pasados) {
                            if ($permiso->fecha_limite) {
                                $fechaLimite = $permiso->fecha_limite;
                                if ($hoy > $permiso->fecha_limite) {
                                    $estado = 'expirado';
                                    $motivo = 'Prórroga histórica vencida';
                                } else {
                                    $estado = 'prorroga';
                                    $motivo = 'Prórroga histórica activa';
                                }
                            } else {
                                $estado = 'prorroga';
                                $motivo = 'Autorización histórica activa';
                            }
                        } else {
                            $estado = 'bloqueado_pasado';
                            $motivo = 'Periodo pasado - Requiere permiso';
                        }
                    } else {
                        if ($permiso && $permiso->fecha_limite) {
                            $fechaLimite = $permiso->fecha_limite;
                            if ($hoy > $permiso->fecha_limite) {
                                $estado = 'expirado';
                                $motivo = 'Prórroga vencida';
                            } else {
                                $estado = 'prorroga';
                                $motivo = 'Prórroga activa';
                            }
                        } else {
                            if ($h->fecha_fin_grupo) {
                                if ($hoy > $h->fecha_fin_grupo) {
                                    $estado = 'expirado';
                                    $motivo = 'Plazo vencido';
                                } else {
                                    $estado = 'pendiente';
                                    $motivo = 'En tiempo ordinario';
                                }
                            } else {
                                $estado = 'pendiente';
                                $motivo = 'En tiempo ordinario';
                            }
                        }
                    }
                }
            }

            $matriz[] = [
                'id_docente' => $h->id_docente,
                'nombre_docente' => $h->nombre_docente,
                'id_grupo' => $h->id_grupo,
                'clave_grupo' => $h->clave_grupo,
                'id_materia' => $h->id_materia,
                'nombre_materia' => $h->nombre_materia,
                'clave_materia' => $h->clave_materia,
                'alumnos_totales' => $totalAlumnos,
                'alumnos_calificados' => $gradedCount,
                'estado' => $estado,
                'motivo' => $motivo,
                'fecha_limite' => $fechaLimite,
                'permiso' => $permiso
            ];
        }

        return response()->json($matriz);
    }

    public function getGruposPorCct($cct_id)
    {
        $grupos = DB::table('tb_grupos')
            ->where('id_centroTrabajo', $cct_id)
            ->where('statusGrupo', 'ACTIVO')
            ->select('id', 'clave')
            ->orderBy('clave', 'asc')
            ->get();

        return response()->json($grupos);
    }

    public function getGrupoConfig($grupo_id)
    {
        $group = DB::table('tb_grupos')->where('id', $grupo_id)->first();
        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Grupo no encontrado.'
            ], 404);
        }

        $config = DB::table('tb_grupo_periodos_captura')->where('id_grupo', $grupo_id)->first();
        
        $levels = DB::table('tb_niveles_academicos')
            ->where('id_tipoPeriodo', $group->id_tipoPeriodo)
            ->where('activo', 1)
            ->select('id', 'nombre', 'numero')
            ->orderBy('numero', 'asc')
            ->get();

        $isBgne = (int)$group->id_centroTrabajo === 3;

        if (!$config) {
            return response()->json([
                'success' => true,
                'isBgne' => $isBgne,
                'levels' => $levels,
                'config' => [
                    'id_grupo' => (int)$grupo_id,
                    'id_nivel_academico' => $group->id_nivel_academico,
                    'captura_habilitada' => 1,
                    'p1_habilitado' => 1, 'p1_fecha_inicio' => null, 'p1_fecha_fin' => null,
                    'p2_habilitado' => 1, 'p2_fecha_inicio' => null, 'p2_fecha_fin' => null,
                    'p3_habilitado' => 1, 'p3_fecha_inicio' => null, 'p3_fecha_fin' => null,
                    'semestral_habilitado' => 1, 'semestral_fecha_inicio' => null, 'semestral_fecha_fin' => null,
                    'extraordinario_habilitado' => 1, 'extraordinario_fecha_inicio' => null, 'extraordinario_fecha_fin' => null
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'isBgne' => $isBgne,
            'levels' => $levels,
            'config' => $config
        ]);
    }

    public function saveGrupoConfig(Request $request, $grupo_id)
    {
        DB::table('tb_grupo_periodos_captura')->updateOrInsert(
            ['id_grupo' => $grupo_id],
            [
                'id_nivel_academico' => $request->id_nivel_academico ?: null,
                'captura_habilitada' => $request->captura_habilitada ? 1 : 0,
                
                'p1_habilitado' => $request->p1_habilitado ? 1 : 0,
                'p1_fecha_inicio' => $request->p1_fecha_inicio ?: null,
                'p1_fecha_fin' => $request->p1_fecha_fin ?: null,

                'p2_habilitado' => $request->p2_habilitado ? 1 : 0,
                'p2_fecha_inicio' => $request->p2_fecha_inicio ?: null,
                'p2_fecha_fin' => $request->p2_fecha_fin ?: null,

                'p3_habilitado' => $request->p3_habilitado ? 1 : 0,
                'p3_fecha_inicio' => $request->p3_fecha_inicio ?: null,
                'p3_fecha_fin' => $request->p3_fecha_fin ?: null,

                'semestral_habilitado' => $request->semestral_habilitado ? 1 : 0,
                'semestral_fecha_inicio' => $request->semestral_fecha_inicio ?: null,
                'semestral_fecha_fin' => $request->semestral_fecha_fin ?: null,

                'extraordinario_habilitado' => $request->extraordinario_habilitado ? 1 : 0,
                'extraordinario_fecha_inicio' => $request->extraordinario_fecha_inicio ?: null,
                'extraordinario_fecha_fin' => $request->extraordinario_fecha_fin ?: null,

                'updated_at' => now()
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Configuración de captura para el grupo guardada correctamente.'
        ]);
    }

    public function getCcts()
    {
        $ccts = DB::table('tb_centrotrabajo')->select('id', 'clave', 'nombre')->get();
        return response()->json($ccts);
    }
}
