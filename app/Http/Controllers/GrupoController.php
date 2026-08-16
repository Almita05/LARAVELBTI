<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class GrupoController extends Controller
{
    public function index()
    {
        return view('grupos.index');
    }

    public function capturaCalificaciones()
    {
        return view('grupos.captura_calificaciones');
    }

    public function getCalificacionesGrupoMateria(Request $request, $id_grupo)
    {
        $idDocente = $request->query('id_docente') ?: session('id_docente');
        $isDocente = strtoupper(session('rol')) === 'DOCENTE';

        $idMateria = $request->id_materia;
        if ($isDocente && $idDocente && !$idMateria) {
            // Find the first subject taught by this teacher in this group that belongs to the group's current level (or null)
            $group = DB::table('tb_grupos')->where('id', $id_grupo)->first();
            $groupLevel = $group ? $group->id_nivel_academico : null;

            $firstHorario = DB::table('tb_horarios as h')
                ->join('tb_materias as m', 'h.id_materia', '=', 'm.id')
                ->where('h.id_grupo', $id_grupo)
                ->where('h.id_docente', $idDocente)
                ->where(function($q) use ($groupLevel) {
                    $q->whereNull('m.id_nivel_academico');
                    if ($groupLevel) {
                        $q->orWhere('m.id_nivel_academico', $groupLevel);
                    }
                })
                ->orderBy('h.id_materia', 'asc')
                ->first();
            if ($firstHorario) {
                $idMateria = $firstHorario->id_materia;
            }
        }

        $url = config('services.api.base_url') . '/grupos/' . $id_grupo . '/calificaciones-materia';
        $params = [];
        if ($idMateria) {
            $params['id_materia'] = $idMateria;
        }
        $response = Http::get($url, $params);
        
        if ($response->failed()) {
            return response()->json($response->json(), $response->status());
        }

        $data = $response->json();
        
        // Check grade capture permission for the active subject
        if (isset($data['success']) && $data['success'] && isset($data['data'])) {
            // Filter materias to only show the teacher's active subjects matching the group's current level
            // If a specific docente ID is requested or active, resolve the materiaSeleccionada to match that teacher
            if ($idDocente && isset($data['data']['materias'])) {
                $idMateriaSeleccionada = $data['data']['idMateriaSeleccionada'] ?? null;
                if ($idMateriaSeleccionada) {
                    foreach ($data['data']['materias'] as $m) {
                        if ((int)$m['idMateria'] === (int)$idMateriaSeleccionada && (int)$m['id_docente'] === (int)$idDocente) {
                            $data['data']['materiaSeleccionada'] = $m;
                            break;
                        }
                    }
                }
            }

            // Filter materias to only show the teacher's active subjects matching the group's current level (if logged-in role is DOCENTE)
            if ($isDocente && $idDocente && isset($data['data']['materias'])) {
                $group = DB::table('tb_grupos')->where('id', $id_grupo)->first();
                $groupLevel = $group ? $group->id_nivel_academico : null;

                $data['data']['materias'] = array_values(array_filter($data['data']['materias'], function($m) use ($idDocente, $groupLevel) {
                    $matchDocente = isset($m['id_docente']) && (int)$m['id_docente'] === (int)$idDocente;
                    $matchLevel = !isset($m['id_nivel_academico']) || $m['id_nivel_academico'] === null || ($groupLevel && (int)$m['id_nivel_academico'] === (int)$groupLevel);
                    return $matchDocente && $matchLevel;
                }));
            }

            $idMateriaSeleccionada = $data['data']['idMateriaSeleccionada'] ?? null;
            if ($idMateriaSeleccionada) {
                $permissionResult = $this->checkCapturaPermission($id_grupo, $idMateriaSeleccionada);
                if (!$permissionResult['allowed']) {
                    $data['data']['solo_lectura'] = true;
                    $data['data']['mensaje_restriccion'] = $permissionResult['reason'];
                } else {
                    $data['data']['solo_lectura'] = false;
                }

                // Add Group-level configuration to response payload
                $idDocente = session('id_docente');
                $permiso = $idDocente ? DB::table('tb_docente_permisos_captura')
                    ->where('id_docente', $idDocente)
                    ->where('id_grupo', $id_grupo)
                    ->where('id_materia', $idMateriaSeleccionada)
                    ->first() : null;

                $cfg = DB::table('tb_grupo_periodos_captura')->where('id_grupo', $id_grupo)->first();

                $hasActiveSpecialPermission = ($permiso && $permiso->habilitado && (!$permiso->fecha_limite || date('Y-m-d') <= $permiso->fecha_limite));

                // Lock the subject if the active capture semester doesn't match the subject's semester
                if (strtoupper(session('rol')) === 'DOCENTE' && !$hasActiveSpecialPermission && $cfg && $cfg->id_nivel_academico !== null) {
                    $materiaNivel = $data['data']['materiaSeleccionada']['id_nivel_academico'] ?? null;
                    if ($materiaNivel !== null && (int)$materiaNivel !== (int)$cfg->id_nivel_academico) {
                        $levelName = DB::table('tb_niveles_academicos')->where('id', $cfg->id_nivel_academico)->value('nombre') ?: 'Nivel ' . $cfg->id_nivel_academico;
                        $data['data']['solo_lectura'] = true;
                        $data['data']['mensaje_restriccion'] = "La captura para este semestre está deshabilitada. El semestre habilitado es: " . $levelName . ".";
                    }
                }

                $data['data']['cct_config'] = [
                    'captura_p1' => $hasActiveSpecialPermission ? true : $this->isPeriodOpen($cfg ? $cfg->p1_habilitado : 1, $cfg ? $cfg->p1_fecha_inicio : null, $cfg ? $cfg->p1_fecha_fin : null),
                    'captura_p2' => $hasActiveSpecialPermission ? true : $this->isPeriodOpen($cfg ? $cfg->p2_habilitado : 1, $cfg ? $cfg->p2_fecha_inicio : null, $cfg ? $cfg->p2_fecha_fin : null),
                    'captura_p3' => $hasActiveSpecialPermission ? true : $this->isPeriodOpen($cfg ? $cfg->p3_habilitado : 1, $cfg ? $cfg->p3_fecha_inicio : null, $cfg ? $cfg->p3_fecha_fin : null),
                    'captura_semestral' => $hasActiveSpecialPermission ? true : $this->isPeriodOpen($cfg ? $cfg->semestral_habilitado : 1, $cfg ? $cfg->semestral_fecha_inicio : null, $cfg ? $cfg->semestral_fecha_fin : null),
                    'captura_extraordinario' => $hasActiveSpecialPermission ? true : $this->isPeriodOpen($cfg ? $cfg->extraordinario_habilitado : 1, $cfg ? $cfg->extraordinario_fecha_inicio : null, $cfg ? $cfg->extraordinario_fecha_fin : null),
                ];

                $data['data']['grupo_fechas_limite'] = $cfg ? [
                    'p1_inicio' => $cfg->p1_fecha_inicio,
                    'p1_fin' => $cfg->p1_fecha_fin,
                    'p2_inicio' => $cfg->p2_fecha_inicio,
                    'p2_fin' => $cfg->p2_fecha_fin,
                    'p3_inicio' => $cfg->p3_fecha_inicio,
                    'p3_fin' => $cfg->p3_fecha_fin,
                    'semestral_inicio' => $cfg->semestral_fecha_inicio,
                    'semestral_fin' => $cfg->semestral_fecha_fin,
                    'extraordinario_inicio' => $cfg->extraordinario_fecha_inicio,
                    'extraordinario_fin' => $cfg->extraordinario_fecha_fin,
                ] : null;
            }
        }

        return response()->json($data, $response->status());
    }

    public function saveCalificacionesGrupoMateria(Request $request, $id_grupo, $id_materia)
    {
        // Enforce grade capture permission check
        $permissionResult = $this->checkCapturaPermission($id_grupo, $id_materia);
        if (!$permissionResult['allowed']) {
            return response()->json([
                'success' => false,
                'message' => $permissionResult['reason']
            ], 403);
        }

        // Backend column locks validation (applies only to teachers; admins/coordinators bypass this)
        if (strtoupper(session('rol')) === 'DOCENTE') {
            $idDocente = session('id_docente');
            $permiso = $idDocente ? DB::table('tb_docente_permisos_captura')
                ->where('id_docente', $idDocente)
                ->where('id_grupo', $id_grupo)
                ->where('id_materia', $id_materia)
                ->first() : null;

            $hasActiveSpecialPermission = ($permiso && $permiso->habilitado && (!$permiso->fecha_limite || date('Y-m-d') <= $permiso->fecha_limite));

            $cfg = DB::table('tb_grupo_periodos_captura')->where('id_grupo', $id_grupo)->first();

            // Validate active capture level
            if (!$hasActiveSpecialPermission && $cfg && $cfg->id_nivel_academico !== null) {
                $materia = DB::table('tb_materias')->where('id', $id_materia)->first();
                $materiaNivel = $materia ? $materia->id_nivel_academico : null;
                if ($materiaNivel !== null && (int)$materiaNivel !== (int)$cfg->id_nivel_academico) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La captura para este semestre está deshabilitada en este grupo.'
                    ], 403);
                }
            }

            if (!$hasActiveSpecialPermission) {
                $group = DB::table('tb_grupos')->where('id', $id_grupo)->first();
                $isBgne = $group && (int)$group->id_centroTrabajo === 3;

                $p1Open = $this->isPeriodOpen($cfg ? $cfg->p1_habilitado : 1, $cfg ? $cfg->p1_fecha_inicio : null, $cfg ? $cfg->p1_fecha_fin : null);
                $p2Open = $this->isPeriodOpen($cfg ? $cfg->p2_habilitado : 1, $cfg ? $cfg->p2_fecha_inicio : null, $cfg ? $cfg->p2_fecha_fin : null);
                $p3Open = $this->isPeriodOpen($cfg ? $cfg->p3_habilitado : 1, $cfg ? $cfg->p3_fecha_inicio : null, $cfg ? $cfg->p3_fecha_fin : null);
                $semOpen = $this->isPeriodOpen($cfg ? $cfg->semestral_habilitado : 1, $cfg ? $cfg->semestral_fecha_inicio : null, $cfg ? $cfg->semestral_fecha_fin : null);
                $extOpen = $this->isPeriodOpen($cfg ? $cfg->extraordinario_habilitado : 1, $cfg ? $cfg->extraordinario_fecha_inicio : null, $cfg ? $cfg->extraordinario_fecha_fin : null);

                $calificaciones = $request->input('calificaciones', []);
                foreach ($calificaciones as $item) {
                    $idAlumno = $item['idAlumno'] ?? null;
                    if (!$idAlumno) continue;

                    $existing = DB::table('tb_calificaciones')
                        ->where('idGrupo', $id_grupo)
                        ->where('idMateria', $id_materia)
                        ->where('idAlumno', $idAlumno)
                        ->first();

                    $compare = function ($key, $isOpen, $field) use ($item, $existing) {
                        if (!$isOpen) {
                            $incomingVal = isset($item[$key]) && $item[$key] !== '' && $item[$key] !== null ? (float)$item[$key] : null;
                            $dbVal = $existing && isset($existing->$field) && $existing->$field !== null ? (float)$existing->$field : null;
                            if ($incomingVal !== $dbVal) {
                                return false;
                            }
                        }
                        return true;
                    };

                    if ($isBgne) {
                        // For BGNE, validate final grade (mapped to P1 config) and extraordinary grade
                        $incomingCal = isset($item['calificacion']) && $item['calificacion'] !== '' && $item['calificacion'] !== null ? (float)$item['calificacion'] : null;
                        $dbCal = $existing && isset($existing->calificacion) && $existing->calificacion !== null ? (float)$existing->calificacion : null;
                        if (!$p1Open && $incomingCal !== $dbCal) {
                            return response()->json(['success' => false, 'message' => 'No tiene permisos para modificar la Calificación Final (fuera de fecha o bloqueado).'], 403);
                        }
                        if (!$compare('extraordinario', $extOpen, 'extraordinario')) {
                            return response()->json(['success' => false, 'message' => 'No tiene permisos para modificar la calificación del Examen Extraordinario (fuera de fecha o bloqueado).'], 403);
                        }
                    } else {
                        // For BTI / regular, validate all periods
                        if (!$compare('parcial1', $p1Open, 'parcial1')) {
                            return response()->json(['success' => false, 'message' => 'No tiene permisos para modificar la calificación del 1er. Parcial (fuera de fecha o bloqueado).'], 403);
                        }
                        if (!$compare('parcial2', $p2Open, 'parcial2')) {
                            return response()->json(['success' => false, 'message' => 'No tiene permisos para modificar la calificación del 2do. Parcial (fuera de fecha o bloqueado).'], 403);
                        }
                        if (!$compare('parcial3', $p3Open, 'parcial3')) {
                            return response()->json(['success' => false, 'message' => 'No tiene permisos para modificar la calificación del 3er. Parcial (fuera de fecha o bloqueado).'], 403);
                        }
                        if (!$compare('semestral', $semOpen, 'semestral')) {
                            return response()->json(['success' => false, 'message' => 'No tiene permisos para modificar la calificación del Examen Semestral (fuera de fecha o bloqueado).'], 403);
                        }
                        if (!$compare('extraordinario', $extOpen, 'extraordinario')) {
                            return response()->json(['success' => false, 'message' => 'No tiene permisos para modificar la calificación del Examen Extraordinario (fuera de fecha o bloqueado).'], 403);
                        }
                    }
                }
            }
        }

        $url = config('services.api.base_url') . '/grupos/' . $id_grupo . '/calificaciones-materia/' . $id_materia;
        $response = Http::post($url, $request->all());
        return response()->json($response->json(), $response->status());
    }

    /**
     * Helper to validate if a docente has grade capturing permissions.
     */
    public function checkCapturaPermission($idGrupo, $idMateria)
    {
        // Admins can always capture/modify grades
        if (strtoupper(session('rol')) === 'ADMIN') {
            return [
                'allowed' => true,
                'reason' => 'Acceso administrador total.'
            ];
        }

        $idDocente = session('id_docente');
        if (!$idDocente) {
            return [
                'allowed' => false,
                'reason' => 'Identificación de docente no encontrada en la sesión.'
            ];
        }

        $hoy = date('Y-m-d');

        // Check explicit permission exception
        $permiso = DB::table('tb_docente_permisos_captura')
            ->where('id_docente', $idDocente)
            ->where('id_grupo', $idGrupo)
            ->where('id_materia', $idMateria)
            ->first();

        // If explicitly disabled by admin, block it
        if ($permiso && !$permiso->habilitado) {
            return [
                'allowed' => false,
                'reason' => 'La captura de calificaciones ha sido deshabilitada para esta asignatura por administración.'
            ];
        }

        // Check if teacher is scheduled if there is no explicit override record
        if (!$permiso) {
            $horarioExist = DB::table('tb_horarios')
                ->where('id_docente', $idDocente)
                ->where('id_grupo', $idGrupo)
                ->where('id_materia', $idMateria)
                ->exists();

            if (!$horarioExist) {
                return [
                    'allowed' => false,
                    'reason' => 'No tienes asignada esta asignatura en este grupo.'
                ];
            }
        }

        $grupo = DB::table('tb_grupos')->where('id', $idGrupo)->first();
        $materia = DB::table('tb_materias')->where('id', $idMateria)->first();

        if (!$grupo) {
            return [
                'allowed' => false,
                'reason' => 'El grupo no existe.'
            ];
        }

        // Check group config locks (if no special override exists)
        if (!$permiso) {
            $grpConfig = DB::table('tb_grupo_periodos_captura')->where('id_grupo', $idGrupo)->first();
            if ($grpConfig && !$grpConfig->captura_habilitada) {
                return [
                    'allowed' => false,
                    'reason' => 'La captura de calificaciones está deshabilitada temporalmente para este grupo.'
                ];
            }
        }

        // Check if group is inactive
        if (strtoupper($grupo->statusGrupo) !== 'ACTIVO') {
            if (!$permiso || !$permiso->permitir_modificar_pasados) {
                return [
                    'allowed' => false,
                    'reason' => 'El grupo se encuentra inactivo. Requiere autorización especial de administración.'
                ];
            }
        }

        // Check if subject is from a past semester/trimester
        if ($grupo && $materia) {
            $nivelGrupo = DB::table('tb_niveles_academicos')->where('id', $grupo->id_nivel_academico)->first();
            $nivelMateria = DB::table('tb_niveles_academicos')->where('id', $materia->id_nivel_academico)->first();

            if ($nivelGrupo && $nivelMateria) {
                if ($nivelMateria->numero < $nivelGrupo->numero) {
                    if (!$permiso || !$permiso->permitir_modificar_pasados) {
                        return [
                            'allowed' => false,
                            'reason' => 'Esta asignatura pertenece a un periodo anterior (' . $nivelMateria->nombre . '). Requiere autorización de administración para modificar calificaciones pasadas.'
                        ];
                    }
                }
            }
        }

        // Check date deadline
        if ($permiso && $permiso->fecha_limite) {
            if ($hoy > $permiso->fecha_limite) {
                return [
                    'allowed' => false,
                    'reason' => 'El periodo extraordinario de captura expiró el ' . date('d/m/Y', strtotime($permiso->fecha_limite)) . '.'
                ];
            }
        } else {
            // Check standard group deadline
            if ($grupo->fechaFin) {
                if ($hoy > $grupo->fechaFin) {
                    return [
                        'allowed' => false,
                        'reason' => 'El periodo ordinario de captura finalizó el ' . date('d/m/Y', strtotime($grupo->fechaFin)) . '.'
                    ];
                }
            }
        }

        return [
            'allowed' => true,
            'reason' => 'Permiso concedido.'
        ];
    }

    public function lista(Request $request)
    {
        $url = config('services.api.base_url') . '/grupos';

        $params = [
            'page' => $request->page ?? 1,
            'limit' => $request->limit ?? 50,
            'search' => $request->search ?? ''
        ];

        if (session('rol') === 'DOCENTE' && session()->has('id_docente')) {
            $params['id_docente'] = session('id_docente');
        }

        if ($request->filled('id_centro_trabajo')) {
            $params['id_centro_trabajo'] = $request->id_centro_trabajo;
        }
        if ($request->filled('status_grupo')) {
            $params['status_grupo'] = $request->status_grupo;
        }
        if ($request->filled('modalidad_horario')) {
            $params['modalidadHorario'] = $request->modalidad_horario;
        }
        if ($request->filled('dia')) {
            $params['dia'] = $request->dia;
        }

        $response = Http::get($url, $params);

        if ($response->failed()) {
            return response()->json([
                'data' => [],
                'total' => 0,
                'page' => 1,
                'total_pages' => 1
            ]);
        }

        return $response->json();
    }


    public function modalAlta()
    {
        $base = config('services.api.base_url');

        // Centros
        $resCentros = Http::get($base . '/centroTrabajo');
        $centros = $resCentros->successful() ? $resCentros->json() : [];

        // Planes
        $resPlanes = Http::get($base . '/getPlanesEstudio');
        $planes = $resPlanes->successful() ? $resPlanes->json() : [];

        // Periodos
        $resPeriodos = Http::get($base . '/tipoPeriodo');
        $periodos = $resPeriodos->successful() ? $resPeriodos->json() : [];

        // Niveles Académicos
        $resNiveles = Http::get($base . '/getNivelAcademico');
        $niveles = $resNiveles->successful() ? $resNiveles->json() : [];

        return view('grupos.modalAlta', compact('centros', 'planes', 'periodos', 'niveles'));
    }

    public function store(Request $request)
    {
        $url = config('services.api.base_url') . '/createGrupos';
        
        $modalidad = strtoupper($request->modalidadHorario);
        $diasClase = [];
        if (str_contains($modalidad, 'SABADO') || str_contains($modalidad, 'SÁBADO')) {
            $diasClase = ['SABADO'];
        } elseif (str_contains($modalidad, 'DOMINGO')) {
            $diasClase = ['DOMINGO'];
        } else {
            $diasClase = ['LUNES-VIERNES'];
        }

        $response = Http::post($url, [
            'clave' => $request->clave,
            'fechaCreacion' => $request->fechaCreacion,
            'fechaInicio' => $request->fechaInicio,
            'fechaFin' => $request->fechaFin,
            'id_centroTrabajo' => $request->id_centroTrabajo,
            'id_planEstudios' => $request->id_planEstudios,
            'id_tipoPeriodo' => $request->id_tipoPeriodo,
            'modalidadHorario' => $request->modalidadHorario,
            'id_nivel_academico' => $request->id_nivel_academico,
            'statusGrupo' => $request->statusGrupo ?? 'ACTIVO',
            'diasClase' => $diasClase,
        ]);

        if ($response->failed()) {
            $errData = $response->json();
            $errMsg = $errData['error'] ?? 'Error al guardar el grupo en el servidor backend';
            return response()->json([
                'message' => $errMsg
            ], 500);
        }

        return response()->json([
            'message' => 'Guardado correctamente'
        ]);
    }

    public function show($id)
    {
        $url = config('services.api.base_url') . '/getGrupo/' . $id;

        $response = Http::get($url);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los detalles del grupo'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $response->json()
        ]);
    }

    public function update(Request $request, $id)
    {
        $url = config('services.api.base_url') . '/updateGrupo/' . $id;
        
        $modalidad = strtoupper($request->modalidadHorario);
        $diasClase = [];
        if (str_contains($modalidad, 'SABADO') || str_contains($modalidad, 'SÁBADO')) {
            $diasClase = ['SABADO'];
        } elseif (str_contains($modalidad, 'DOMINGO')) {
            $diasClase = ['DOMINGO'];
        } else {
            $diasClase = ['LUNES-VIERNES'];
        }

        $response = Http::put($url, [
            'clave' => $request->clave,
            'fechaCreacion' => $request->fechaCreacion,
            'fechaInicio' => $request->fechaInicio,
            'fechaFin' => $request->fechaFin,
            'id_centroTrabajo' => $request->id_centroTrabajo,
            'id_planEstudios' => $request->id_planEstudios,
            'id_tipoPeriodo' => $request->id_tipoPeriodo,
            'modalidadHorario' => $request->modalidadHorario,
            'id_nivel_academico' => $request->id_nivel_academico,
            'statusGrupo' => $request->statusGrupo ?? 'ACTIVO',
            'diasClase' => $diasClase,
        ]);

        if ($response->failed()) {
            $errData = $response->json();
            $errMsg = $errData['error'] ?? 'Error al actualizar el grupo en el servidor backend';
            return response()->json([
                'success' => false,
                'message' => $errMsg
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Grupo actualizado correctamente'
        ]);
    }

    public function destroy($id)
    {
        $url = config('services.api.base_url') . '/deleteGrupo/' . $id;
        $response = Http::delete($url);

        if ($response->failed()) {
            $err = $response->json();
            return response()->json([
                'success' => false,
                'message' => $err['error'] ?? 'Error al eliminar el grupo en el servidor backend'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Grupo eliminado correctamente'
        ]);
    }

    private function isPeriodOpen($enabled, $start, $end)
    {
        if (!$enabled) return false;
        
        $hoy = date('Y-m-d');
        if ($start && $hoy < $start) return false;
        if ($end && $hoy > $end) return false;
        
        return true;
    }
}
