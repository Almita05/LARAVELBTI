<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Services\MoodleService;

class AlumnoOnlineController extends Controller
{
    /**
     * Muestra la vista principal del módulo de Alumnos Online
     */
    public function index()
    {
        $totalOnline = 0;
        $alCorriente = 0;
        $pendientes = 0;
        $semanasPagadasTotal = 0;
        $enRiesgoTotal = 0;

        try {
            // Estadísticas para KPIs
            $totalOnline = DB::table('tb_alumnos')
                ->where('modalidad_estudio', 'ONLINE')
                ->count();

            $alCorriente = DB::table('tb_alumnos')
                ->where('modalidad_estudio', 'ONLINE')
                ->where(function ($q) {
                    $q->whereNull('fecha_proximo_pago')
                      ->orWhere('fecha_proximo_pago', '>=', Carbon::today()->toDateString());
                })
                ->count();

            $pendientes = DB::table('tb_alumnos')
                ->where('modalidad_estudio', 'ONLINE')
                ->whereNotNull('fecha_proximo_pago')
                ->where('fecha_proximo_pago', '<', Carbon::today()->toDateString())
                ->count();

            try {
                $semanasPagadasTotal = DB::table('tb_alumnos')
                    ->where('modalidad_estudio', 'ONLINE')
                    ->sum('semanas_totales_pagadas') ?? 0;
            } catch (\Throwable $e) {
                $semanasPagadasTotal = 0;
            }

            // Alumnos con rezago académico: al corriente en colegiatura pero deben 2 o más actividades
            $alumnosOnline = DB::table('tb_alumnos')
                ->where('modalidad_estudio', 'ONLINE')
                ->get();

            $hoy = Carbon::today()->toDateString();

            foreach ($alumnosOnline as $al) {
                $esAlCorriente = empty($al->fecha_proximo_pago) || $al->fecha_proximo_pago >= $hoy;
                if ($esAlCorriente) {
                    try {
                        $pendientesAct = DB::table('tb_alumno_actividades_online')
                            ->where('id_alumno', $al->idAlumno)
                            ->where('estatus', 'LIBERADA')
                            ->count();
                        if ($pendientesAct >= 2) {
                            $enRiesgoTotal++;
                        }
                    } catch (\Throwable $e) {
                        // Tabla de actividades aún no creada
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error("Error cargando métricas en AlumnoOnlineController@index: " . $e->getMessage());
        }

        return view('alumnos.online', compact(
            'totalOnline',
            'alCorriente',
            'pendientes',
            'semanasPagadasTotal',
            'enRiesgoTotal'
        ));
    }

    /**
     * Retorna la lista de alumnos en Modalidad Online en formato JSON
     */
    public function getAlumnos(Request $request)
    {
        try {
            $query = DB::table('tb_alumnos as a')
                ->leftJoin('tb_grupos as g', 'a.idGrupo', '=', 'g.id')
                ->where('a.modalidad_estudio', 'ONLINE')
                ->select(
                    'a.idAlumno',
                    'a.nombre',
                    'a.apPaterno',
                    'a.apMaterno',
                    'a.numeroControl',
                    'a.curp',
                    'a.celularAlumno',
                    'a.correoAlumno',
                    'a.statusAlumno',
                    'a.modalidad_estudio',
                    'a.dia_pago',
                    'a.moodle_user_id',
                    'a.moodle_username',
                    'a.trimestre_inicio_online',
                    'a.semana_actual_pagada',
                    'a.semanas_totales_pagadas',
                    'a.fecha_proximo_pago',
                    'g.clave as clave_grupo'
                );

            if ($request->filled('search')) {
                $s = trim($request->search);
                $query->where(function ($q) use ($s) {
                    $q->where('a.nombre', 'LIKE', "%{$s}%")
                      ->orWhere('a.apPaterno', 'LIKE', "%{$s}%")
                      ->orWhere('a.apMaterno', 'LIKE', "%{$s}%")
                      ->orWhere('a.numeroControl', 'LIKE', "%{$s}%")
                      ->orWhere('a.correoAlumno', 'LIKE', "%{$s}%");
                });
            }

            if ($request->filled('trimestre')) {
                $query->where('a.trimestre_inicio_online', (int)$request->trimestre);
            }

            $alumnos = $query->orderBy('a.idAlumno', 'desc')->get();

            $today = Carbon::today();

            $alumnos->transform(function ($al) use ($today) {
                $trimInicio = max(1, min(6, (int)($al->trimestre_inicio_online ?? 1)));
                $semanasTotales = max(0, (int)($al->semanas_totales_pagadas ?? 0));

                // 12 semanas por trimestre
                $trimestresAvanzados = (int)floor($semanasTotales / 12);
                $trimestreActual = min(6, $trimInicio + $trimestresAvanzados);

                // Mes dentro del trimestre (1, 2 o 3)
                $semanasEnTrimestreActual = $semanasTotales % 12;
                $mesActual = min(3, (int)floor($semanasEnTrimestreActual / 4) + 1);

                // Semana dentro del mes actual (1 a 4)
                $semanaMesActual = ($semanasEnTrimestreActual % 4) + 1;

                // Estatus de pago
                $estatusPago = 'AL_CORRIENTE';
                $diasDiferencia = 0;
                if (!empty($al->fecha_proximo_pago)) {
                    $proximo = Carbon::parse($al->fecha_proximo_pago);
                    $diasDiferencia = $today->diffInDays($proximo, false);
                    if ($diasDiferencia < 0) {
                        $estatusPago = 'VENCIDO';
                    } elseif ($diasDiferencia <= 2) {
                        $estatusPago = 'POR_VENCER';
                    }
                }

                // Último pago
                $ultimoPago = null;
                $totalPagado = 0;
                try {
                    $ultimoPago = DB::table('tb_alumno_pagos')
                        ->where('id_alumno', $al->idAlumno)
                        ->orderBy('id', 'desc')
                        ->first();

                    $totalPagado = DB::table('tb_alumno_pagos')
                        ->where('id_alumno', $al->idAlumno)
                        ->sum('monto') ?? 0;
                } catch (\Throwable $e) {}

                // Conteo granular de actividades
                $totalLiberadas = 0;
                $totalEntregadas = 0;
                $totalPendientes = 0;
                $totalCalificadas = 0;
                try {
                    $acts = DB::table('tb_alumno_actividades_online')
                        ->where('id_alumno', $al->idAlumno)
                        ->get();

                    $totalLiberadas = $acts->where('estatus', '!=', 'BLOQUEADA')->count();
                    $totalEntregadas = $acts->whereIn('estatus', ['ENTREGADA', 'CALIFICADA'])->count();
                    $totalPendientes = $acts->where('estatus', 'LIBERADA')->count();
                    $totalCalificadas = $acts->where('estatus', 'CALIFICADA')->count();
                } catch (\Throwable $e) {}

                $al->nombreCompleto = trim("{$al->nombre} {$al->apPaterno} {$al->apMaterno}");
                $al->trimestre_inicio = $trimInicio;
                $al->trimestre_actual = $trimestreActual;
                $al->mes_actual = $mesActual;
                $al->semana_mes_actual = $semanaMesActual;
                $al->estatus_pago = $estatusPago;
                $al->dias_para_pago = $diasDiferencia;
                $al->total_pagado = (float)$totalPagado;
                $al->actividades_totales = $totalLiberadas;
                $al->actividades_entregadas = $totalEntregadas;
                $al->actividades_pendientes = $totalPendientes;
                $al->actividades_calificadas = $totalCalificadas;
                $al->en_riesgo_academico = ($estatusPago !== 'VENCIDO' && $totalPendientes >= 2);
                $al->ultimo_pago_fecha = $ultimoPago ? $ultimoPago->fecha_pago : null;
                $al->ultimo_pago_ticket = $ultimoPago ? $ultimoPago->folio_ticket : null;

                return $al;
            });

            return response()->json([
                'success' => true,
                'alumnos' => $alumnos
            ]);
        } catch (\Throwable $e) {
            Log::error("Error en AlumnoOnlineController@getAlumnos: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'alumnos' => [],
                'error' => $e->getMessage()
            ], 200);
        }
    }

    /**
     * Actualiza el trimestre de inicio del alumno online (1° a 6°)
     */
    public function actualizarTrimestreInicio(Request $request, $id)
    {
        $request->validate([
            'trimestre_inicio' => 'required|integer|min:1|max:6'
        ]);

        $trimestre = (int)$request->trimestre_inicio;

        DB::table('tb_alumnos')
            ->where('idAlumno', $id)
            ->update([
                'trimestre_inicio_online' => $trimestre,
                'updateAt' => Carbon::now()
            ]);

        return response()->json([
            'success' => true,
            'message' => "Trimestre de inicio actualizado al {$trimestre}° Trimestre."
        ]);
    }

    /**
     * Actualiza el día asignado para el cobro semanal (Lunes a Domingo)
     */
    public function actualizarDiaPago(Request $request, $id)
    {
        $request->validate([
            'dia_pago' => 'required|in:LUNES,MARTES,MIERCOLES,JUEVES,VIERNES,SABADO,DOMINGO'
        ]);

        DB::table('tb_alumnos')
            ->where('idAlumno', $id)
            ->update([
                'dia_pago' => $request->dia_pago,
                'updateAt' => Carbon::now()
            ]);

        return response()->json([
            'success' => true,
            'message' => "Día de cobro actualizado a {$request->dia_pago}."
        ]);
    }

    /**
     * Registra un pago de colegiatura online y libera semanas de actividades
     */
    public function registrarPago(Request $request, $id, MoodleService $moodleService)
    {
        $request->validate([
            'semanas_pagadas' => 'required|integer|min:1|max:12',
            'monto'           => 'required|numeric|min:0',
            'metodo_pago'     => 'nullable|string',
            'folio_ticket'    => 'nullable|string',
            'fecha_pago'      => 'nullable|date',
            'observaciones'   => 'nullable|string'
        ]);

        $alumno = DB::table('tb_alumnos')->where('idAlumno', $id)->first();
        if (!$alumno) {
            return response()->json(['success' => false, 'message' => 'Alumno no encontrado.'], 404);
        }

        $semanasPagadas = (int)$request->semanas_pagadas;
        $monto = (float)$request->monto;
        $fechaPago = $request->fecha_pago ?: Carbon::today()->toDateString();
        $folioTicket = trim($request->folio_ticket ?: ('BOL-' . strtoupper(uniqid())));
        $metodoPago = $request->metodo_pago ?: 'EFECTIVO';

        $semanasPrevias = (int)($alumno->semanas_totales_pagadas ?: 0);
        $nuevasSemanasTotales = $semanasPrevias + $semanasPagadas;

        // Calcular trimestre y mes cubiertos
        $trimInicio = max(1, min(6, (int)($alumno->trimestre_inicio_online ?: 1)));
        $trimestreCubierto = min(6, $trimInicio + (int)floor($semanasPrevias / 12));
        $mesCubierto = min(3, (int)floor(($semanasPrevias % 12) / 4) + 1);

        // Nueva fecha de próximo pago
        $fechaBase = !empty($alumno->fecha_proximo_pago) ? Carbon::parse($alumno->fecha_proximo_pago) : Carbon::today();
        // Si ya estaba vencida por mucho tiempo, partir de hoy
        if ($fechaBase->lt(Carbon::today())) {
            $fechaBase = Carbon::today();
        }
        $nuevaFechaProximoPago = $fechaBase->copy()->addWeeks($semanasPagadas)->toDateString();

        // 1. Insertar el pago en tb_alumno_pagos
        $pagoId = DB::table('tb_alumno_pagos')->insertGetId([
            'id_alumno'           => $id,
            'fecha_pago'          => $fechaPago,
            'folio_ticket'        => $folioTicket,
            'semana_cubierta'     => $semanasPrevias + 1,
            'semanas_pagadas'     => $semanasPagadas,
            'id_nivel_academico'  => $trimestreCubierto,
            'mes_bloque'          => $mesCubierto,
            'monto'               => $monto,
            'metodo_pago'         => $metodoPago,
            'observaciones'       => $request->observaciones,
            'registrado_por'      => session('usuario') ?? 'ADMIN',
            'created_at'          => Carbon::now()
        ]);

        // 2. Actualizar acumulados del alumno
        DB::table('tb_alumnos')
            ->where('idAlumno', $id)
            ->update([
                'semana_actual_pagada'    => ($semanasPrevias % 4) + $semanasPagadas,
                'semanas_totales_pagadas' => $nuevasSemanasTotales,
                'fecha_proximo_pago'      => $nuevaFechaProximoPago,
                'updateAt'                => Carbon::now()
            ]);

        // 3. Liberar las semanas correspondientes en tb_alumno_actividades_online
        // Para cada una de las semanas pagadas
        for ($i = 1; $i <= $semanasPagadas; $i++) {
            $semanaAbsoluta = $semanasPrevias + $i;
            $trimestre = min(6, $trimInicio + (int)floor(($semanaAbsoluta - 1) / 12));
            $semanaEnTrimestre = (($semanaAbsoluta - 1) % 12) + 1;
            $mesBloque = min(3, (int)floor(($semanaEnTrimestre - 1) / 4) + 1);
            $semanaEnMes = (($semanaEnTrimestre - 1) % 4) + 1;

            // Obtener las materias del mes bloque de este trimestre
            $materiasMes = DB::table('tb_materias')
                ->where('idCentroTrabajo', 3)
                ->where('id_nivel_academico', $trimestre)
                ->where('mes_bloque', $mesBloque)
                ->get();

            foreach ($materiasMes as $mat) {
                // Insertar o actualizar estatus LIBERADA
                $existente = DB::table('tb_alumno_actividades_online')
                    ->where('id_alumno', $id)
                    ->where('id_materia', $mat->id)
                    ->where('semana', $semanaEnMes)
                    ->first();

                if ($existente) {
                    if ($existente->estatus === 'BLOQUEADA') {
                        DB::table('tb_alumno_actividades_online')
                            ->where('id', $existente->id)
                            ->update([
                                'estatus'          => 'LIBERADA',
                                'id_pago'          => $pagoId,
                                'fecha_liberacion' => Carbon::now()
                            ]);
                    }
                } else {
                    DB::table('tb_alumno_actividades_online')->insert([
                        'id_alumno'           => $id,
                        'id_materia'          => $mat->id,
                        'id_nivel_academico'  => $trimestre,
                        'mes_bloque'          => $mesBloque,
                        'semana'              => $semanaEnMes,
                        'id_pago'             => $pagoId,
                        'fecha_liberacion'    => Carbon::now(),
                        'estatus'             => 'LIBERADA',
                        'created_at'          => Carbon::now()
                    ]);
                }
            }
        }

        // 4. Automatización con Moodle (FASE 2: Desbloqueo y Enrolamiento)
        $moodleMsg = '';
        try {
            if (!empty($alumno->moodle_user_id)) {
                // Reactivar cuenta en Moodle si estaba suspendida
                $moodleService->reactivarAlumno($id);

                // Enrolar automáticamente en las materias del bloque activo
                $enrolRes = $moodleService->enrolStudentInActiveMaterias($id);
                if (!empty($enrolRes['enrolados'])) {
                    $moodleMsg = ' • Enrolado en Moodle: ' . implode(', ', $enrolRes['enrolados']);
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Error en sincronización Moodle al pagar alumno {$id}: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => "Pago de {$semanasPagadas} semana(s) registrado correctamente. Ticket: {$folioTicket}.{$moodleMsg}",
            'nuevo_total_semanas' => $nuevasSemanasTotales,
            'fecha_proximo_pago' => $nuevaFechaProximoPago
        ]);
    }

    /**
     * Retorna el detalle académico completo del alumno: materias, avance por semanas y pagos
     */
    public function getDetalleAlumno($id)
    {
        $alumno = DB::table('tb_alumnos as a')
            ->leftJoin('tb_grupos as g', 'a.idGrupo', '=', 'g.id')
            ->where('a.idAlumno', $id)
            ->select('a.*', 'g.clave as clave_grupo')
            ->first();

        if (!$alumno) {
            return response()->json(['success' => false, 'message' => 'Alumno no encontrado.'], 404);
        }

        $trimInicio = max(1, min(6, (int)($alumno->trimestre_inicio_online ?: 1)));
        $semanasTotales = max(0, (int)($alumno->semanas_totales_pagadas ?: 0));

        $trimestresAvanzados = (int)floor($semanasTotales / 12);
        $trimestreActual = min(6, $trimInicio + $trimestresAvanzados);
        $semanasEnTrimestreActual = $semanasTotales % 12;
        $mesActual = min(3, (int)floor($semanasEnTrimestreActual / 4) + 1);

        // Obtener las materias de los trimestres desde el de inicio hasta el actual
        $trimestresList = range($trimInicio, $trimestreActual);

        $materiasData = [];

        foreach ($trimestresList as $tNum) {
            $materiasTrimestre = DB::table('tb_materias')
                ->where('idCentroTrabajo', 3)
                ->where('id_nivel_academico', $tNum)
                ->orderBy('mes_bloque')
                ->orderBy('par_materia_orden')
                ->get();

            $meses = [];
            for ($m = 1; $m <= 3; $m++) {
                $matsMes = $materiasTrimestre->where('mes_bloque', $m)->values();

                $matsArray = [];
                foreach ($matsMes as $mat) {
                    // Semanas de esta materia
                    $semanas = [];
                    for ($s = 1; $s <= 4; $s++) {
                        $act = DB::table('tb_alumno_actividades_online')
                            ->where('id_alumno', $id)
                            ->where('id_materia', $mat->id)
                            ->where('semana', $s)
                            ->first();

                        $semanas[] = [
                            'semana'           => $s,
                            'estatus'          => $act ? $act->estatus : 'BLOQUEADA',
                            'calificacion'     => $act ? $act->calificacion : null,
                            'fecha_liberacion' => $act ? $act->fecha_liberacion : null,
                            'fecha_entrega'    => $act ? $act->fecha_entrega : null,
                        ];
                    }

                    $matsArray[] = [
                        'id'                => $mat->id,
                        'nombreMateria'     => $mat->nombreMateria,
                        'clave'             => $mat->clave,
                        'par_materia_orden' => $mat->par_materia_orden,
                        'semanas'           => $semanas
                    ];
                }

                $meses[] = [
                    'mes_bloque' => $m,
                    'materias'   => $matsArray
                ];
            }

            $materiasData[] = [
                'trimestre' => $tNum,
                'es_actual' => ($tNum === $trimestreActual),
                'meses'     => $meses
            ];
        }

        // Historial de pagos
        $pagos = DB::table('tb_alumno_pagos')
            ->where('id_alumno', $id)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success'          => true,
            'alumno'           => $alumno,
            'trimestre_inicio' => $trimInicio,
            'trimestre_actual' => $trimestreActual,
            'mes_actual'       => $mesActual,
            'semanas_totales'  => $semanasTotales,
            'progreso'         => $materiasData,
            'pagos'            => $pagos
        ]);
    }

    /**
     * Retorna la matriz de distribución oficial de materias de los 6 trimestres de BGNE
     */
    public function getDistribucionMaterias()
    {
        $trimestres = [];

        for ($t = 1; $t <= 6; $t++) {
            $materias = DB::table('tb_materias')
                ->where('idCentroTrabajo', 3)
                ->where('id_nivel_academico', $t)
                ->orderBy('mes_bloque')
                ->orderBy('par_materia_orden')
                ->get();

            $meses = [];
            for ($m = 1; $m <= 3; $m++) {
                $meses[$m] = $materias->where('mes_bloque', $m)->values();
            }

            $trimestres[$t] = [
                'trimestre' => $t,
                'mes_1'     => $meses[1] ?? [],
                'mes_2'     => $meses[2] ?? [],
                'mes_3'     => $meses[3] ?? [],
            ];
        }

        return response()->json([
            'success'     => true,
            'trimestres'  => $trimestres
        ]);
    }

    /**
     * Busca alumnos para cambiarlos o integrarlos a Modalidad Online
     */
    public function buscarPresenciales(Request $request)
    {
        $s = trim($request->search ?: '');
        if (strlen($s) < 2) {
            return response()->json(['success' => true, 'alumnos' => []]);
        }

        $words = array_filter(explode(' ', $s));

        $query = DB::table('tb_alumnos as a')
            ->leftJoin('tb_grupos as g', 'a.idGrupo', '=', 'g.id');

        $query->where(function ($q) use ($words, $s) {
            // Coincidencia con nombre completo concatenado
            $q->whereRaw("CONCAT_WS(' ', a.nombre, a.apPaterno, a.apMaterno) LIKE ?", ["%{$s}%"])
              ->orWhere('a.numeroControl', 'LIKE', "%{$s}%")
              ->orWhere('a.idAlumno', '=', $s);

            // Coincidencia con todas las palabras individuales
            if (count($words) > 1) {
                $q->orWhere(function ($multi) use ($words) {
                    foreach ($words as $w) {
                        $multi->where(function ($sub) use ($w) {
                            $sub->where('a.nombre', 'LIKE', "%{$w}%")
                                ->orWhere('a.apPaterno', 'LIKE', "%{$w}%")
                                ->orWhere('a.apMaterno', 'LIKE', "%{$w}%")
                                ->orWhere('a.numeroControl', 'LIKE', "%{$w}%")
                                ->orWhere('a.idAlumno', 'LIKE', "%{$w}%");
                        });
                    }
                });
            }
        });

        $alumnos = $query
            ->select(
                'a.idAlumno', 
                'a.nombre', 
                'a.apPaterno', 
                'a.apMaterno', 
                'a.numeroControl', 
                'a.modalidad_estudio',
                'g.clave as grupo'
            )
            ->limit(20)
            ->get();

        return response()->json(['success' => true, 'alumnos' => $alumnos]);
    }

    /**
     * Cambia a un alumno a Modalidad Online
     */
    public function activarModalidadOnline(Request $request, $id, MoodleService $moodleService)
    {
        $request->validate([
            'dia_pago'         => 'required|in:LUNES,MARTES,MIERCOLES,JUEVES,VIERNES,SABADO,DOMINGO',
            'trimestre_inicio' => 'required|integer|min:1|max:6',
            'moodle_password'  => 'nullable|string'
        ]);

        $alumno = DB::table('tb_alumnos')->where('idAlumno', $id)->first();
        if (!$alumno) {
            return response()->json(['success' => false, 'message' => 'Alumno no encontrado.'], 404);
        }

        // Actualizar en BD local
        DB::table('tb_alumnos')
            ->where('idAlumno', $id)
            ->update([
                'modalidad_estudio'        => 'ONLINE',
                'dia_pago'                 => $request->dia_pago,
                'trimestre_inicio_online'  => (int)$request->trimestre_inicio,
                'fecha_proximo_pago'       => Carbon::today()->addDays(7)->toDateString(),
                'updateAt'                 => Carbon::now()
            ]);

        // Sincronizar en Moodle si aplica
        $moodleMsg = '';
        try {
            $alumnoActualizado = DB::table('tb_alumnos')->where('idAlumno', $id)->first();
            $rawPass = $request->moodle_password;
            $moodleRes = $moodleService->syncAlumno($alumnoActualizado, $rawPass);
            if ($moodleRes['success']) {
                $moodleMsg = " • Acceso Moodle sincronizado ({$moodleRes['moodle_username']})";
            }
        } catch (\Throwable $e) {
            Log::warning("Error sincronizando Moodle para alumno {$id}: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => "Alumno integrado con éxito a Modalidad Online{$moodleMsg}."
        ]);
    }

    /**
     * Sincroniza al alumno en Moodle, verifica su estatus de pago y enrola en materias activas
     */
    public function sincronizarMoodleAlumno(Request $request, $id, MoodleService $moodleService)
    {
        $alumno = DB::table('tb_alumnos')->where('idAlumno', $id)->first();
        if (!$alumno) {
            return response()->json(['success' => false, 'message' => 'Alumno no encontrado.'], 404);
        }

        try {
            $rawPass = $request->moodle_password;
            $resSync = $moodleService->syncAlumno($alumno, $rawPass);

            if (!$resSync['success']) {
                return response()->json(['success' => false, 'message' => $resSync['error'] ?? 'Error sincronizando con Moodle.'], 400);
            }

            // Evaluar pago y enrolar
            $moodleService->actualizarEstatusAccesoPorPago($id);
            $enrolRes = $moodleService->enrolStudentInActiveMaterias($id);

            $detalleEnrol = '';
            if (!empty($enrolRes['enrolados'])) {
                $detalleEnrol = '<br><strong>Materias enroladas:</strong> ' . implode(', ', $enrolRes['enrolados']);
            }

            return response()->json([
                'success' => true,
                'message' => "Cuenta Moodle sincronizada ({$resSync['moodle_username']}).{$detalleEnrol}",
                'username' => $resSync['moodle_username']
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Excepción: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Suspende o reactiva manualmente el acceso del alumno a Moodle
     */
    public function toggleSuspensionMoodle(Request $request, $id, MoodleService $moodleService)
    {
        $alumno = DB::table('tb_alumnos')->where('idAlumno', $id)->first();
        if (!$alumno || empty($alumno->moodle_user_id)) {
            return response()->json(['success' => false, 'message' => 'El alumno no tiene cuenta Moodle vinculada.'], 400);
        }

        $suspender = (bool)$request->suspender;

        try {
            if ($suspender) {
                $moodleService->suspenderAlumno($id);
                $msg = 'Acceso a Moodle suspendido temporalmente.';
            } else {
                $moodleService->reactivarAlumno($id);
                $moodleService->enrolStudentInActiveMaterias($id);
                $msg = 'Acceso a Moodle reactivado con éxito.';
            }

            return response()->json(['success' => true, 'message' => $msg]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Guarda el ID del curso de Moodle asociado a una materia
     */
    public function guardarMoodleCourseId(Request $request, $idMateria)
    {
        $request->validate([
            'id_curso_moodle' => 'nullable|integer|min:1'
        ]);

        $moodleCourseId = $request->id_curso_moodle ?: null;

        DB::table('tb_materias')
            ->where('id', $idMateria)
            ->update([
                'id_curso_moodle' => $moodleCourseId,
                'updateAt'        => Carbon::now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'ID de curso en Moodle actualizado correctamente.'
        ]);
    }

    /**
     * Actualiza el estatus de entrega o calificación de una actividad semanal
     */
    public function actualizarEstatusActividad(Request $request, $idAlumno)
    {
        $request->validate([
            'id_materia'   => 'required|integer',
            'semana'       => 'required|integer|min:1|max:4',
            'estatus'      => 'required|in:BLOQUEADA,LIBERADA,ENTREGADA,CALIFICADA',
            'calificacion' => 'nullable|numeric|min:0|max:100',
            'fecha_entrega'=> 'nullable|date'
        ]);

        $act = DB::table('tb_alumno_actividades_online')
            ->where('id_alumno', $idAlumno)
            ->where('id_materia', $request->id_materia)
            ->where('semana', $request->semana)
            ->first();

        $updateData = [
            'estatus'   => $request->estatus,
            'updated_at'=> Carbon::now()
        ];

        if ($request->has('calificacion')) {
            $updateData['calificacion'] = $request->calificacion;
        }

        if ($request->estatus === 'ENTREGADA') {
            $updateData['fecha_entrega'] = $request->fecha_entrega ?: Carbon::now();
        } elseif ($request->estatus === 'LIBERADA') {
            $updateData['fecha_entrega'] = null;
            $updateData['calificacion'] = null;
        }

        if ($act) {
            DB::table('tb_alumno_actividades_online')
                ->where('id', $act->id)
                ->update($updateData);
        } else {
            $materia = DB::table('tb_materias')->where('id', $request->id_materia)->first();
            $updateData['id_alumno'] = $idAlumno;
            $updateData['id_materia'] = $request->id_materia;
            $updateData['id_nivel_academico'] = $materia ? $materia->id_nivel_academico : 1;
            $updateData['mes_bloque'] = $materia ? $materia->mes_bloque : 1;
            $updateData['semana'] = $request->semana;
            $updateData['created_at'] = Carbon::now();

            DB::table('tb_alumno_actividades_online')->insert($updateData);
        }

        return response()->json([
            'success' => true,
            'message' => 'Estatus de actividad semanal actualizado correctamente.'
        ]);
    }
}

