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
        // 1. Docentes activos
        $docentes = DB::table('tb_docentes')
            ->where('statusDocente', 'ACTIVO')
            ->orderBy('apPaternoDocente')
            ->orderBy('nombreDocente')
            ->select('idDocente', 'nombreDocente', 'apPaternoDocente', 'apMaternoDocente')
            ->get();

        // 2. Grupos activos
        $grupos = DB::table('tb_grupos')
            ->where('statusGrupo', 'ACTIVO')
            ->orderBy('clave')
            ->select('id', 'clave', 'id_centroTrabajo', 'fechaInicio', 'fechaFin', 'modalidadHorario', 'id_nivel_academico', 'id_tipoPeriodo')
            ->get();

        // 3. Horarios y asignaciones de materia-docente-grupo
        $horarios = DB::table('tb_horarios as h')
            ->join('tb_materias as m', 'h.id_materia', '=', 'm.id')
            ->select('h.id_grupo', 'h.id_docente', 'h.id_materia', 'm.nombreMateria', 'h.diaSemana')
            ->get();

        // 4. Catálogo de materias
        $materias = DB::table('tb_materias')
            ->where('estatusMateria', 'ACTIVA')
            ->orderBy('nombreMateria')
            ->select('id', 'nombreMateria', 'idCentroTrabajo', 'id_nivel_academico')
            ->get();

        return view('reportes.imprimir', compact('docentes', 'grupos', 'horarios', 'materias'));
    }

    public function getAlumnosGrupo($id)
    {
        $alumnos = DB::table('tb_alumnos as a')
            ->leftJoin('tb_alumnogrupo as ag', 'a.idAlumno', '=', 'ag.idAlumno')
            ->where(function($q) use ($id) {
                $q->where('a.idGrupo', $id)->orWhere('ag.idGrupo', $id);
            })
            ->where('a.statusAlumno', '!=', 'BAJA_DEFINITIVA')
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
    }

    public function generarPdfAsistencia(Request $request)
    {
        $idGrupo = $request->get('id_grupo');
        $idDocente = $request->get('id_docente');
        $docenteNombreParam = $request->get('docente_nombre');
        $materiaNombreParam = $request->get('materia');
        $trimestreParam = $request->get('trimestre', '1');

        $grupo = DB::table('tb_grupos')->where('id', $idGrupo)->first();
        if (!$grupo) {
            abort(404, 'Grupo no encontrado.');
        }

        // Determinar Docente
        $docenteNombre = $docenteNombreParam;
        if ($idDocente) {
            $doc = DB::table('tb_docentes')->where('idDocente', $idDocente)->first();
            if ($doc) {
                $docenteNombre = trim("{$doc->nombreDocente} {$doc->apPaternoDocente} {$doc->apMaternoDocente}");
            }
        }
        if (!$docenteNombre) {
            $docenteNombre = 'DOCENTE ASIGNADO';
        }

        // Determinar Asignatura
        $materiaNombre = $materiaNombreParam;
        if (!$materiaNombre && $idDocente && $idGrupo) {
            $horario = DB::table('tb_horarios as h')
                ->join('tb_materias as m', 'h.id_materia', '=', 'm.id')
                ->where('h.id_grupo', $idGrupo)
                ->where('h.id_docente', $idDocente)
                ->select('m.nombreMateria')
                ->first();
            if ($horario) {
                $materiaNombre = $horario->nombreMateria;
            }
        }
        if (!$materiaNombre) {
            $materiaNombre = 'MATERIA GENERAL';
        }

        // Determinar Trimestre / Semestre
        // Extraer número de trimestre/semestre si viene como string
        preg_match('/\d+/', (string)$trimestreParam, $matches);
        $trimestreNum = !empty($matches) ? intval($matches[0]) : 1;
        $isBti = ($grupo->id_centroTrabajo == 2);
        $periodLabel = $isBti ? "{$trimestreNum}° SEMESTRE" : "{$trimestreNum}TO TRIMESTRE";

        // Determinar grupo display
        $grupoDisplay = "{$grupo->clave} {$periodLabel}";

        // Calcular las 13 semanas (BGNE) o fechas del semestre (BTI)
        // Usamos la misma lógica oficial de horarios/index.blade.php
        $fechaInicioStr = $request->get('fecha_inicio', $grupo->fechaInicio);
        $startDate = Carbon::parse($fechaInicioStr);

        $weeksOffset = ($trimestreNum - 1) * 13;
        $periodStartDate = $startDate->copy()->addWeeks($weeksOffset);

        // Detectar si el grupo es de Sábado o Domingo
        $modalidad = strtoupper($grupo->modalidadHorario ?? '');
        $isDomingo = str_contains($modalidad, 'DOMINGO');
        $isSabado = str_contains($modalidad, 'SABADO') || str_contains($modalidad, 'SÁBADO');

        $dayLetter = $isDomingo ? 'D' : ($isSabado ? 'S' : 'D');

        $mesesNombres = [
            1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL',
            5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO',
            9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'
        ];

        $columnasFechas = [];
        $totalSemanas = 13;

        for ($i = 0; $i < $totalSemanas; $i++) {
            $date = $periodStartDate->copy()->addWeeks($i);
            $mesNum = $date->month;
            $mesNom = $mesesNombres[$mesNum] ?? strtoupper($date->translatedFormat('F'));
            if ($mesNum == 1) $mesNom = 'ENE'; // abreviatura habitual como en el Excel

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

        // Obtener alumnos reales del grupo
        $alumnos = DB::table('tb_alumnos as a')
            ->leftJoin('tb_alumnogrupo as ag', 'a.idAlumno', '=', 'ag.idAlumno')
            ->where(function($q) use ($idGrupo) {
                $q->where('a.idGrupo', $idGrupo)->orWhere('ag.idGrupo', $idGrupo);
            })
            ->where('a.statusAlumno', '!=', 'BAJA_DEFINITIVA')
            ->select('a.idAlumno', 'a.numeroControl', 'a.nombre', 'a.apPaterno', 'a.apMaterno')
            ->distinct()
            ->orderBy('a.apPaterno')
            ->orderBy('a.apMaterno')
            ->orderBy('a.nombre')
            ->get();

        $listaAlumnos = [];
        $num = 1;
        foreach ($alumnos as $al) {
            $nombreCompleto = trim("{$al->apPaterno} {$al->apMaterno} {$al->nombre}");
            $listaAlumnos[] = [
                'num' => $num++,
                'nombre' => $nombreCompleto,
                'matricula' => $al->numeroControl ?? ''
            ];
        }

        // Asegurar un mínimo de 22 filas para que la hoja quede con buen formato físico para firmas
        $totalFilasDeseadas = max(22, count($listaAlumnos) + 3);
        while (count($listaAlumnos) < $totalFilasDeseadas) {
            $listaAlumnos[] = [
                'num' => $num++,
                'nombre' => '',
                'matricula' => ''
            ];
        }

        // Cargar vista PDF
        $pdf = Pdf::loadView('listas_asistencias.pdf_asistencia_oficial', [
            'docente' => mb_strtoupper($docenteNombre, 'UTF-8'),
            'asignatura' => mb_strtoupper($materiaNombre, 'UTF-8'),
            'grupo' => mb_strtoupper($grupoDisplay, 'UTF-8'),
            'columnasFechas' => $columnasFechas,
            'mesesAgrupados' => $mesesAgrupados,
            'alumnos' => $listaAlumnos,
            'totalSemanas' => $totalSemanas,
            'cct' => $grupo->id_centroTrabajo == 3 ? 'BGNE' : ($grupo->id_centroTrabajo == 2 ? 'BTI' : 'INF')
        ])->setPaper('letter', 'landscape');

        return $pdf->stream("lista_asistencia_{$grupo->clave}.pdf");
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