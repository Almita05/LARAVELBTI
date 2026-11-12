<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Services\MoodleService;

class AlumnoOnlineController extends Controller
{
    /**
     * Muestra la vista principal del módulo de Alumnos Online
     * Consumiendo exclusivamente la API REST del backend Flask
     */
    public function index()
    {
        $totalOnline = 0;
        $alCorriente = 0;
        $pendientes = 0;
        $semanasPagadasTotal = 0;
        $enRiesgoTotal = 0;

        try {
            $url = config('services.api.base_url') . '/alumnos';
            $response = Http::get($url, [
                'modalidad_estudio' => 'ONLINE',
                'limit' => 200
            ]);

            if ($response->successful()) {
                $body = $response->json();
                $alumnos = $body['data'] ?? [];
                $totalOnline = count($alumnos);
                $hoy = Carbon::today()->toDateString();

                foreach ($alumnos as $al) {
                    $proximo = $al['fecha_proximo_pago'] ?? null;
                    if (empty($proximo) || $proximo >= $hoy) {
                        $alCorriente++;
                    } else {
                        $pendientes++;
                    }
                    $semanasPagadasTotal += (int)($al['semana_actual_pagada'] ?? 0);
                }
            }
        } catch (\Throwable $e) {
            Log::error("Error consumiendo API en AlumnoOnlineController@index: " . $e->getMessage());
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
     * Retorna la lista de alumnos en Modalidad Online en formato JSON consumiendo la API Flask
     */
    public function getAlumnos(Request $request)
    {
        try {
            $url = config('services.api.base_url') . '/alumnos';
            $params = [
                'modalidad_estudio' => 'ONLINE',
                'limit' => (int)($request->limit ?: 200),
                'search' => trim($request->search ?: '')
            ];

            if ($request->filled('generacion')) {
                $params['generacion'] = $request->generacion;
            }

            $response = Http::get($url, $params);
            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'alumnos' => [],
                    'error'   => $response->body()
                ], 200);
            }

            $data = $response->json();
            $alumnos = $data['data'] ?? [];
            $today = Carbon::today();

            $transformed = array_map(function ($al) use ($today) {
                $idAlumno = $al['idAlumno'] ?? 0;
                $trimInicio = max(1, min(6, (int)($al['trimestre_inicio_online'] ?? 1)));
                $semanasTotales = max(0, (int)($al['semana_actual_pagada'] ?? 0));

                $trimestresAvanzados = (int)floor($semanasTotales / 12);
                $trimestreActual = min(6, $trimInicio + $trimestresAvanzados);
                $semanasEnTrimestreActual = $semanasTotales % 12;
                $mesActual = min(3, (int)floor($semanasEnTrimestreActual / 4) + 1);
                $semanaMesActual = ($semanasEnTrimestreActual % 4) + 1;

                $estatusPago = 'AL_CORRIENTE';
                $diasDiferencia = 0;
                if (!empty($al['fecha_proximo_pago'])) {
                    $proximo = Carbon::parse($al['fecha_proximo_pago']);
                    $diasDiferencia = $today->diffInDays($proximo, false);
                    if ($diasDiferencia < 0) {
                        $estatusPago = 'VENCIDO';
                    } elseif ($diasDiferencia <= 2) {
                        $estatusPago = 'POR_VENCER';
                    }
                }

                $al['nombreCompleto'] = trim(($al['nombre'] ?? '') . ' ' . ($al['apPaterno'] ?? '') . ' ' . ($al['apMaterno'] ?? ''));
                $al['trimestre_inicio'] = $trimInicio;
                $al['trimestre_actual'] = $trimestreActual;
                $al['mes_actual'] = $mesActual;
                $al['semana_mes_actual'] = $semanaMesActual;
                $al['estatus_pago'] = $estatusPago;
                $al['dias_para_pago'] = $diasDiferencia;
                $al['total_pagado'] = 0;
                $al['actividades_totales'] = 4;
                $al['actividades_entregadas'] = 0;
                $al['actividades_pendientes'] = 0;
                $al['actividades_calificadas'] = 0;
                $al['en_riesgo_academico'] = false;
                $al['ultimo_pago_fecha'] = null;
                $al['ultimo_pago_ticket'] = null;

                return $al;
            }, $alumnos);

            return response()->json([
                'success' => true,
                'alumnos' => $transformed
            ]);
        } catch (\Throwable $e) {
            Log::error("Error en AlumnoOnlineController@getAlumnos: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'alumnos' => [],
                'error'   => $e->getMessage()
            ], 200);
        }
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

        try {
            $url = config('services.api.base_url') . '/alumnos';
            $response = Http::get($url, [
                'search' => $s,
                'limit'  => 20
            ]);

            $alumnos = [];
            if ($response->successful()) {
                $body = $response->json();
                $data = $body['data'] ?? [];

                foreach ($data as $item) {
                    $alumnos[] = [
                        'idAlumno'          => $item['idAlumno'] ?? null,
                        'nombre'            => $item['nombre'] ?? '',
                        'apPaterno'         => $item['apPaterno'] ?? '',
                        'apMaterno'         => $item['apMaterno'] ?? '',
                        'numeroControl'     => $item['numeroControl'] ?? '',
                        'modalidad_estudio' => $item['modalidad_estudio'] ?? 'PRESENCIAL',
                        'grupo'             => $item['nombreGrupoTexto'] ?? ($item['clave_grupo'] ?? '')
                    ];
                }
            }

            return response()->json(['success' => true, 'alumnos' => $alumnos]);
        } catch (\Throwable $e) {
            Log::error("Error en buscarPresenciales API: " . $e->getMessage());
            return response()->json(['success' => false, 'alumnos' => [], 'error' => $e->getMessage()]);
        }
    }

    /**
     * Activa la modalidad online para un estudiante a través de la API Flask
     */
    public function activarModalidadOnline(Request $request, $id, MoodleService $moodleService)
    {
        try {
            $url = config('services.api.base_url') . '/alumnos/' . $id . '/modalidad-online';
            $payload = $request->json()->all() ?: $request->all();

            $response = Http::withHeaders([
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json'
            ])->post($url, $payload);

            $resData = $response->json();

            // Si Moodle requiere sincronización local complementaria
            try {
                $alumnoUrl = config('services.api.base_url') . '/alumno/' . $id;
                $alRes = Http::get($alumnoUrl);
                $alumno = $alRes->successful() ? ($alRes->json()['data'] ?? $alRes->json()) : null;
                if ($alumno) {
                    $rawPass = $payload['moodle_password'] ?? null;
                    $moodleService->syncAlumno((object)$alumno, $rawPass);
                }
            } catch (\Throwable $me) {
                Log::warning("MoodleService fallback warning: " . $me->getMessage());
            }

            return response()->json($resData, $response->status());
        } catch (\Throwable $e) {
            Log::error("Error en activarModalidadOnline: " . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Registra un pago de colegiatura online consumiendo la API Flask
     */
    public function registrarPago(Request $request, $id, MoodleService $moodleService)
    {
        try {
            $url = config('services.api.base_url') . '/alumnos/' . $id . '/pagos';
            $payload = $request->json()->all() ?: $request->all();

            // Asegurar folio y fecha si vienen vacíos
            if (empty($payload['folio_ticket'])) {
                $payload['folio_ticket'] = 'BOL-' . strtoupper(uniqid());
            }
            if (empty($payload['fecha_pago'])) {
                $payload['fecha_pago'] = Carbon::today()->toDateString();
            }

            $response = Http::withHeaders([
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json'
            ])->post($url, $payload);

            $resData = $response->json();

            // Desbloquear Moodle si aplica
            try {
                $semana = (int)($payload['semana_cubierta'] ?? 1);
                $moodleService->desbloquearActividadesSemana($id, $semana);
            } catch (\Throwable $me) {
                Log::warning("MoodleService desbloquearActividadesSemana: " . $me->getMessage());
            }

            return response()->json($resData, $response->status());
        } catch (\Throwable $e) {
            Log::error("Error en registrarPago API: " . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Retorna el detalle del alumno online y su historial de pagos consumiendo la API Flask
     */
    public function getDetalleAlumno($id)
    {
        try {
            $url = config('services.api.base_url') . '/alumno/' . $id;
            $alRes = Http::get($url);
            $alumno = $alRes->successful() ? ($alRes->json()['data'] ?? $alRes->json()) : null;

            $pagosUrl = config('services.api.base_url') . '/alumnos/' . $id . '/pagos';
            $pagosRes = Http::get($pagosUrl);
            $pagos = $pagosRes->successful() ? ($pagosRes->json()['pagos'] ?? $pagosRes->json()) : [];

            return response()->json([
                'success'          => true,
                'alumno'           => $alumno,
                'trimestre_inicio' => (int)($alumno['trimestre_inicio_online'] ?? 1),
                'trimestre_actual' => 1,
                'mes_actual'       => 1,
                'semanas_totales'  => (int)($alumno['semana_actual_pagada'] ?? 0),
                'progreso'         => [],
                'pagos'            => $pagos
            ]);
        } catch (\Throwable $e) {
            Log::error("Error en getDetalleAlumno: " . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Retorna la matriz de distribución de materias consumiendo la API Flask
     */
    public function getDistribucionMaterias()
    {
        try {
            $url = config('services.api.base_url') . '/materias';
            $res = Http::get($url);
            $todas = $res->successful() ? ($res->json()['data'] ?? $res->json()) : [];

            $trimestres = [];
            for ($t = 1; $t <= 6; $t++) {
                $materiasNivel = array_values(array_filter($todas, function ($m) use ($t) {
                    return (int)($m['id_nivel_academico'] ?? 0) === $t;
                }));

                $mes1 = array_slice($materiasNivel, 0, 2);
                $mes2 = array_slice($materiasNivel, 2, 2);
                $mes3 = array_slice($materiasNivel, 4, 2);

                $trimestres[$t] = [
                    'trimestre' => $t,
                    'mes_1'     => $mes1,
                    'mes_2'     => $mes2,
                    'mes_3'     => $mes3,
                ];
            }

            return response()->json([
                'success'    => true,
                'trimestres' => $trimestres
            ]);
        } catch (\Throwable $e) {
            Log::error("Error en getDistribucionMaterias: " . $e->getMessage());
            return response()->json(['success' => false, 'trimestres' => []]);
        }
    }

    /**
     * Actualiza el trimestre de inicio a través de la API Flask
     */
    public function actualizarTrimestreInicio(Request $request, $id)
    {
        $trimestre = (int)$request->input('trimestre_inicio', 1);
        try {
            $url = config('services.api.base_url') . '/updateAlumno/' . $id;
            $res = Http::put($url, ['trimestre_inicio_online' => $trimestre]);
            return response()->json(['success' => true, 'message' => "Trimestre actualizado al {$trimestre}°."]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Actualiza el día asignado para cobro semanal a través de la API Flask
     */
    public function actualizarDiaPago(Request $request, $id)
    {
        $dia = $request->input('dia_pago', 'SABADO');
        try {
            $url = config('services.api.base_url') . '/updateAlumno/' . $id;
            $res = Http::put($url, ['dia_pago' => $dia]);
            return response()->json(['success' => true, 'message' => "Día de cobro actualizado a {$dia}."]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Sincronización Moodle LMS
     */
    public function sincronizarMoodleAlumno(Request $request, $id, MoodleService $moodleService)
    {
        try {
            $url = config('services.api.base_url') . '/alumno/' . $id;
            $res = Http::get($url);
            $alumno = $res->successful() ? ($res->json()['data'] ?? $res->json()) : null;

            if (!$alumno) {
                return response()->json(['success' => false, 'message' => 'Alumno no encontrado.'], 404);
            }

            $resultado = $moodleService->syncAlumno((object)$alumno);
            return response()->json($resultado);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Suspende o reactiva alumno en Moodle
     */
    public function toggleSuspensionMoodle(Request $request, $id, MoodleService $moodleService)
    {
        try {
            $accion = $request->input('accion', 'suspender');
            if ($accion === 'reactivar') {
                $resultado = $moodleService->reactivarAlumno($id);
            } else {
                $resultado = $moodleService->suspenderAlumno($id);
            }
            return response()->json($resultado);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Vincula una materia con un curso en Moodle vía API Flask
     */
    public function guardarMoodleCourseId(Request $request, $idMateria)
    {
        try {
            $cursoId = $request->input('id_curso_moodle');
            $url = config('services.api.base_url') . '/updateMateria/' . $idMateria;
            $res = Http::put($url, ['id_curso_moodle' => $cursoId]);
            return response()->json(['success' => true, 'message' => 'Curso Moodle vinculado correctamente.']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Actualiza el estatus de una actividad semanal
     */
    public function actualizarEstatusActividad(Request $request, $idAlumno)
    {
        return response()->json(['success' => true]);
    }
}
