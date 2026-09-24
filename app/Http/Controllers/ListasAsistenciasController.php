<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ListasAsistenciasController extends Controller
{
    public function index()
    {
        return view('listas_asistencias.index');
    }

    public function bti()
    {
        return view('listas_asistencias.bti');
    }

    public function bgneS()
    {
        return view('listas_asistencias.bgneS');
    }

    public function bgneD()
    {
        return view('listas_asistencias.bgneD');
    }

    public function moduloImprimir()
    {
        $docentes = collect();
        $grupos = collect();
        $horarios = collect();
        $materias = collect();
        $apiUrl = config('services.api.base_url');

        // 1. Intentar obtener datos desde la API del backend (para producción o entornos desacoplados)
        try {
            $resGrupos = \Illuminate\Support\Facades\Http::timeout(3)->get($apiUrl . '/grupos', ['limit' => 1000]);
            if ($resGrupos->successful()) {
                $gData = $resGrupos->json()['data'] ?? $resGrupos->json() ?? [];
                $grupos = collect(array_map(fn($item) => (object)$item, $gData));
            }
        } catch (\Throwable $e) {}

        try {
            $resDoc = \Illuminate\Support\Facades\Http::timeout(3)->get($apiUrl . '/docentes');
            if ($resDoc->successful()) {
                $dData = $resDoc->json()['data'] ?? $resDoc->json() ?? [];
                $docentes = collect(array_map(fn($item) => (object)$item, $dData));
            }
        } catch (\Throwable $e) {}

        // 2. Si la API no respondió o estamos en local con BD directa:
        try {
            if ($docentes->isEmpty()) {
                $docentes = DB::table('tb_docentes')
                    ->where('statusDocente', 'ACTIVO')
                    ->orderBy('apPaternoDocente')
                    ->orderBy('nombreDocente')
                    ->select('idDocente', 'nombreDocente', 'apPaternoDocente', 'apMaternoDocente')
                    ->get();
            }

            if ($grupos->isEmpty()) {
                $grupos = DB::table('tb_grupos')
                    ->where('statusGrupo', 'ACTIVO')
                    ->orderBy('clave')
                    ->select('id', 'clave', 'id_centroTrabajo', 'fechaInicio', 'fechaFin', 'modalidadHorario', 'id_nivel_academico', 'id_tipoPeriodo')
                    ->get();
            }

            $horarios = DB::table('tb_horarios as h')
                ->join('tb_materias as m', 'h.id_materia', '=', 'm.id')
                ->select('h.id_grupo', 'h.id_docente', 'h.id_materia', 'm.nombreMateria', 'h.diaSemana')
                ->get();

            $materias = DB::table('tb_materias')
                ->where('estatusMateria', 'ACTIVA')
                ->orderBy('nombreMateria')
                ->select('id', 'nombreMateria', 'idCentroTrabajo', 'id_nivel_academico')
                ->get();
        } catch (\Throwable $eDb) {
            // Silencioso: en entornos donde MySQL no está conectado en Laravel, nunca arroja 500
        }

        return view('reportes.imprimir', compact('docentes', 'grupos', 'horarios', 'materias'));
    }

    public function getAlumnosGrupo($id)
    {
        $apiUrl = config('services.api.base_url');

        // 1. Intentar primero por API
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(4)->get($apiUrl . '/alumnos_by_grupo/' . $id);
            if ($response->successful()) {
                $raw = $response->json();
                $alumnosApi = $raw['data'] ?? $raw ?? [];
                $alumnosApi = array_values(array_filter($alumnosApi, function($item) {
                    $st = strtoupper($item['statusAlumno'] ?? $item['status'] ?? 'ACTIVO');
                    return $st === 'ACTIVO';
                }));
                return response()->json([
                    'success' => true,
                    'total' => count($alumnosApi),
                    'data' => $alumnosApi
                ]);
            }
        } catch (\Throwable $e) {}

        // 2. Fallback a base de datos directa
        try {
            $alumnos = DB::table('tb_alumnos as a')
                ->leftJoin('tb_alumnogrupo as ag', 'a.idAlumno', '=', 'ag.idAlumno')
                ->where(function($q) use ($id) {
                    $q->where('a.idGrupo', $id)->orWhere('ag.idGrupo', $id);
                })
                ->where('a.statusAlumno', 'ACTIVO')
                ->select('a.idAlumno', 'a.numeroControl', 'a.nombre', 'a.apPaterno', 'a.apMaterno', 'a.statusAlumno')
                ->distinct()
                ->orderBy('a.apPaterno')
                ->orderBy('a.apMaterno')
                ->orderBy('a.nombre')
                ->get();

            return response()->json([
                'success' => true,
                'total' => $alumnos->count(),
                'data' => $alumnos
            ]);
        } catch (\Throwable $eDb) {}

        return response()->json([
            'success' => true,
            'total' => 0,
            'data' => []
        ]);
    }

    public function generarPdfAsistencia(Request $request)
    {
        $apiUrl = config('services.api.base_url');
        $idGrupo = $request->get('id_grupo');
        $idDocente = $request->get('id_docente');
        $docenteNombreParam = $request->get('docente_nombre');
        $materiaNombreParam = $request->get('materia');
        $trimestreParam = $request->get('trimestre', '1');

        $grupo = null;

        // Intentar obtener grupo por DB
        try {
            $grupo = DB::table('tb_grupos')->where('id', $idGrupo)->first();
        } catch (\Throwable $e) {}

        // Intentar por API si no se obtuvo por DB
        if (!$grupo) {
            try {
                $res = \Illuminate\Support\Facades\Http::timeout(4)->get($apiUrl . '/getGrupo/' . $idGrupo);
                if ($res->successful()) {
                    $grupo = (object)$res->json();
                }
            } catch (\Throwable $e) {}
        }

        // Si aún no existe, construir objeto seguro con parámetros del request
        if (!$grupo) {
            $grupo = (object)[
                'id' => $idGrupo,
                'clave' => $request->get('clave_grupo', 'GRUPO'),
                'id_centroTrabajo' => $request->get('id_centro_trabajo', 3),
                'fechaInicio' => $request->get('fecha_inicio', date('Y-m-d')),
                'modalidadHorario' => $request->get('modalidad', 'DOMINGO')
            ];
        }

        // Determinar Docente
        $docenteNombre = $docenteNombreParam;
        if ((!$docenteNombre || $docenteNombre === 'Docente no seleccionado (general)') && $idDocente) {
            try {
                $doc = DB::table('tb_docentes')->where('idDocente', $idDocente)->first();
                if ($doc) {
                    $docenteNombre = trim("{$doc->nombreDocente} {$doc->apPaternoDocente} {$doc->apMaternoDocente}");
                }
            } catch (\Throwable $e) {}
        }
        if (!$docenteNombre || $docenteNombre === 'Docente no seleccionado (general)') {
            $docenteNombre = 'DOCENTE ASIGNADO';
        }

        // Determinar Asignatura
        $materiaNombre = $materiaNombreParam;
        if (!$materiaNombre && $idDocente && $idGrupo) {
            try {
                $horario = DB::table('tb_horarios as h')
                    ->join('tb_materias as m', 'h.id_materia', '=', 'm.id')
                    ->where('h.id_grupo', $idGrupo)
                    ->where('h.id_docente', $idDocente)
                    ->select('m.nombreMateria')
                    ->first();
                if ($horario) {
                    $materiaNombre = $horario->nombreMateria;
                }
            } catch (\Throwable $e) {}
        }
        if (!$materiaNombre) {
            $materiaNombre = 'MATERIA GENERAL';
        }

        // Determinar Trimestre / Semestre
        preg_match('/\d+/', (string)$trimestreParam, $matches);
        $trimestreNum = !empty($matches) ? intval($matches[0]) : 1;
        $centroId = $grupo->id_centroTrabajo ?? $grupo->idCentroTrabajo ?? 3;
        $isBti = ($centroId == 2);
        $periodLabel = $isBti ? "{$trimestreNum}° SEMESTRE" : "{$trimestreNum}TO TRIMESTRE";

        // Determinar grupo display
        $claveGrupo = $grupo->clave ?? 'GRUPO';
        $grupoDisplay = "{$claveGrupo} {$periodLabel}";

        // Calcular las 13 semanas (BGNE) o fechas del semestre (BTI)
        $fechaInicioStr = $request->get('fecha_inicio', $grupo->fechaInicio ?? date('Y-m-d'));
        try {
            $startDate = Carbon::parse($fechaInicioStr);
        } catch (\Throwable $e) {
            $startDate = Carbon::now();
        }

        $weeksOffset = ($trimestreNum - 1) * 13;
        $periodStartDate = $startDate->copy()->addWeeks($weeksOffset);

        // Detectar si el grupo es de Sábado o Domingo
        $modalidad = strtoupper($grupo->modalidadHorario ?? '');
        $isDomingo = str_contains($modalidad, 'DOMINGO');
        $isSabado = str_contains($modalidad, 'SABADO') || str_contains($modalidad, 'SÁBADO');

        $dayLetter = $isDomingo ? 'D' : ($isSabado ? 'S' : 'D');

        $mesesNombres = [
            1 => 'ENE', 2 => 'FEB', 3 => 'MAR', 4 => 'ABR',
            5 => 'MAY', 6 => 'JUN', 7 => 'JUL', 8 => 'AGO',
            9 => 'SEP', 10 => 'OCT', 11 => 'NOV', 12 => 'DIC'
        ];

        $columnasFechas = [];
        $totalSemanas = 13;

        for ($i = 0; $i < $totalSemanas; $i++) {
            $date = $periodStartDate->copy()->addWeeks($i);
            $mesNum = $date->month;
            $mesNom = $mesesNombres[$mesNum] ?? strtoupper($date->translatedFormat('F'));

            $eval = null;
            if ($i == 5 || $i == 6) {
                $eval = 'P.1';
            } elseif ($i == 11 || $i == 12) {
                $eval = 'P.2';
            }

            $columnasFechas[] = [
                'index' => $i,
                'fecha_full' => $date->format('Y-m-d'),
                'dia' => $date->format('j'),
                'mes' => $mesNom,
                'mes_num' => $mesNum,
                'letra_dia' => $dayLetter,
                'eval' => $eval
            ];
        }

        // Agrupar meses consecutivos para colspan
        $mesesAgrupados = [];
        foreach ($columnasFechas as $col) {
            $lastIndex = count($mesesAgrupados) - 1;
            if ($lastIndex >= 0 && $mesesAgrupados[$lastIndex]['mes'] === $col['mes']) {
                $mesesAgrupados[$lastIndex]['colspan']++;
            } else {
                $mesesAgrupados[] = [
                    'mes' => $col['mes'],
                    'colspan' => 1
                ];
            }
        }

        // Obtener alumnos reales del grupo (primero API, luego DB)
        $rawAlumnos = [];
        try {
            $resAlumnos = \Illuminate\Support\Facades\Http::timeout(4)->get($apiUrl . '/alumnos_by_grupo/' . $idGrupo);
            if ($resAlumnos->successful()) {
                $json = $resAlumnos->json();
                $rawAlumnos = $json['data'] ?? $json ?? [];
                $rawAlumnos = array_values(array_filter($rawAlumnos, function($item) {
                    $itemArr = (array)$item;
                    $st = strtoupper($itemArr['statusAlumno'] ?? $itemArr['status'] ?? 'ACTIVO');
                    return $st === 'ACTIVO';
                }));
            }
        } catch (\Throwable $e) {}

        if (empty($rawAlumnos)) {
            try {
                $rawAlumnos = DB::table('tb_alumnos as a')
                    ->leftJoin('tb_alumnogrupo as ag', 'a.idAlumno', '=', 'ag.idAlumno')
                    ->where(function($q) use ($idGrupo) {
                        $q->where('a.idGrupo', $idGrupo)->orWhere('ag.idGrupo', $idGrupo);
                    })
                    ->where('a.statusAlumno', 'ACTIVO')
                    ->select('a.idAlumno', 'a.numeroControl', 'a.nombre', 'a.apPaterno', 'a.apMaterno')
                    ->distinct()
                    ->orderBy('a.apPaterno')
                    ->orderBy('a.apMaterno')
                    ->orderBy('a.nombre')
                    ->get()
                    ->toArray();
            } catch (\Throwable $eDb) {}
        }

        $listaAlumnos = [];
        $num = 1;
        foreach ($rawAlumnos as $al) {
            $item = (array)$al;
            $nombre = $item['nombre'] ?? '';
            $apPaterno = $item['apPaterno'] ?? $item['ap_paterno'] ?? '';
            $apMaterno = $item['apMaterno'] ?? $item['ap_materno'] ?? '';
            $matricula = $item['numeroControl'] ?? $item['matricula'] ?? $item['num_control'] ?? '';

            $nombreCompleto = trim("{$apPaterno} {$apMaterno} {$nombre}");
            if (empty($nombreCompleto)) continue;

            $listaAlumnos[] = [
                'num' => $num++,
                'nombre' => $nombreCompleto,
                'matricula' => $matricula
            ];
        }

        // Ordenar alfabéticamente
        usort($listaAlumnos, function($a, $b) {
            return strcmp($a['nombre'], $b['nombre']);
        });

        // Reasignar numeración secuencial
        for ($k = 0; $k < count($listaAlumnos); $k++) {
            $listaAlumnos[$k]['num'] = $k + 1;
        }

        // Asegurar un mínimo de 22 filas para que la hoja conserve su estructura física
        $totalFilasDeseadas = max(22, count($listaAlumnos) + 3);
        $currNum = count($listaAlumnos) + 1;
        while (count($listaAlumnos) < $totalFilasDeseadas) {
            $listaAlumnos[] = [
                'num' => $currNum++,
                'nombre' => '',
                'matricula' => ''
            ];
        }

        // Cargar vista PDF
        $cctLabel = ($centroId == 3) ? 'BGNE' : (($centroId == 2) ? 'BTI' : 'INF');
        $pdf = Pdf::loadView('listas_asistencias.pdf_asistencia_oficial', [
            'docente' => mb_strtoupper($docenteNombre, 'UTF-8'),
            'asignatura' => mb_strtoupper($materiaNombre, 'UTF-8'),
            'grupo' => mb_strtoupper($grupoDisplay, 'UTF-8'),
            'columnasFechas' => $columnasFechas,
            'mesesAgrupados' => $mesesAgrupados,
            'alumnos' => $listaAlumnos,
            'totalSemanas' => $totalSemanas,
            'cct' => $cctLabel
        ])->setPaper('letter', 'landscape');

        return $pdf->stream("lista_asistencia_{$claveGrupo}.pdf");
    }

    public function generarPdfAttendance()
    {
        // Método de test anterior conservado
        $materia = 'Programación Orientada a Objetos';
        $docente = 'Ing. Juan Carlos Pérez Gómez';
        $grupo = '3° Semestre - Grupo A';
        
        $alumnos = [
            ['num' => 1, 'num_control' => '20260001', 'nombre' => 'Pérez López Juan'],
            ['num' => 2, 'num_control' => '20260002', 'nombre' => 'Gómez García María'],
            ['num' => 3, 'num_control' => '20260003', 'nombre' => 'Hernández Ruiz Carlos'],
            ['num' => 4, 'num_control' => '20260004', 'nombre' => 'Martínez Díaz Sofía'],
            ['num' => 5, 'num_control' => '20260005', 'nombre' => 'Sánchez Torres Pedro'],
        ];
        $pdf = Pdf::loadView('listas_asistencias.pdf_formato', compact('materia', 'docente', 'grupo', 'alumnos'));
        return $pdf->stream('lista_asistencia.pdf');
    }
}