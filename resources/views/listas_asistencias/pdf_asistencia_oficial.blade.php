<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Asistencia - {{ $grupo }}</title>
@php
    /*
     * Solo cálculos de presentación: las fechas llegan ya calculadas desde el
     * controlador (columnasFechas / mesesAgrupados) y aquí no se modifican.
     *
     * Geometría (carta horizontal): 279.4mm - 2 x 9mm de margen = 261mm útiles.
     * Todas las columnas de fecha reciben exactamente el mismo ancho, sin importar
     * si el día tiene 1 o 2 dígitos; el nombre del alumno absorbe el sobrante.
     */
    $esEscolarizado = $isEscolarizado ?? false;
    $totalCols = max(1, count($columnasFechas));

    $anchoUtil = 261;
    // dompdf no aplica border-box en celdas: suma bordes y padding al ancho declarado.
    $bordeCol = 0.34; // bordes de 0.75pt
    $anchoTabla = $anchoUtil - ($totalCols + 2) * $bordeCol - 4.5;
    $anchoNum = 7;
    $anchoNombreMin = 72;
    $anchoFecha = floor(min(13, ($anchoTabla - $anchoNum - $anchoNombreMin) / $totalCols) * 100) / 100;
    $anchoNombre = $anchoTabla - $anchoNum - ($anchoFecha * $totalCols);

    // Fila de evaluaciones (P.1 / P.2): se agrupan columnas consecutivas iguales
    $segmentosEval = [];
    $hayEval = false;
    foreach ($columnasFechas as $f) {
        $ev = $f['eval'] ?? null;
        if ($ev) $hayEval = true;
        $last = count($segmentosEval) - 1;
        if ($last >= 0 && $segmentosEval[$last]['eval'] === $ev) {
            $segmentosEval[$last]['colspan']++;
        } else {
            $segmentosEval[] = ['eval' => $ev, 'colspan' => 1];
        }
    }
    $mostrarEval = !$esEscolarizado && $hayEval;

    // Clases por columna: viernes (cierre de semana) y columnas de evaluación
    $clasesCol = [];
    foreach ($columnasFechas as $i => $f) {
        $clasesCol[$i] = trim(
            (($f['letra_dia'] ?? '') === 'V' ? 'vie ' : '') .
            (!empty($f['eval']) ? 'eval' : '')
        );
    }

    $modalidadLabel = $esEscolarizado
        ? 'Escolarizado'
        : ((($columnasFechas[0]['letra_dia'] ?? '') === 'D') ? 'Dominical' : 'Sabatino');

    // Alto de fila adaptable: el padding vertical (máx. 6px) se reduce lo necesario
    // para que todas las filas + firmas quepan en una sola hoja.
    // Calibrado sobre el render real de dompdf: una fila sin padding mide ~4.9mm.
    $altoDisponible = $mostrarEval ? 111 : 117;            // mm para el cuerpo de la tabla
    $altoFila = $altoDisponible / max(1, count($alumnos));
    $padV = round(max(0.2, min(1.6, ($altoFila - 4.9) / 2)), 2); // 1.6mm ≈ 6px
    // Con muchas filas la celda de asistencia baja de 18px a ~4.2mm para conservar una sola hoja
    $filasApretadas = ($altoFila - 4.9) / 2 < 0.3;

    // Nombre en una sola línea (nowrap): si no cabe a 8.5pt se reduce la fuente
    // para que dompdf no ensanche la columna y saque la tabla de la hoja.
    $fuenteNombre = function ($nombre) use (&$anchoNombre) {
        $len = mb_strlen($nombre ?? "");
        if ($len === 0) return null;
        $pt = ($anchoNombre - 5) / ($len * 0.64 * 0.3528);
        return $pt >= 8.5 ? null : max(6, floor($pt * 10) / 10);
    };
    $compacto = $padV < 1.6;
@endphp
    <style>
        @page {
            size: letter landscape;
            margin: 8mm 9mm 7mm 9mm;
        }

        @include('pdf.partials.estilos-base')

        /* ---------- Tabla de asistencia ---------- */
        .grid {
            width: {{ $anchoTabla }}mm;
        }

        .grid th,
        .grid td {
            overflow: hidden;
            padding: 0;               /* dompdf aplica 1px por defecto y desborda la hoja */
            vertical-align: middle;
        }

        .col-num { width: {{ $anchoNum }}mm; }
        .col-nombre { width: {{ $anchoNombre }}mm; }
        .col-fecha { width: {{ $anchoFecha }}mm; }

        .grid thead th.th-nombre {
            text-align: left;
            padding-left: 8px;
        }

        .grid thead th.th-secundario { height: 4.2mm; padding: 2px 0; }
        .grid tbody td.td-check { min-width: 0; padding: 0; }
        .grid tbody td.td-num { padding-left: 4px; padding-right: 4px; }
        .grid tbody td.td-nombre { padding-left: 8px; padding-right: 8px; }
        .grid thead th.th-primario { padding-top: 4px; padding-bottom: 4px; }
        .grid thead th.th-nombre { padding-left: 8px; }

        .grid thead th.th-secundario.eval,
        .grid tbody td.eval {
            background-color: #FFFDF5;
        }


        .grid tbody td.td-num,
        .grid tbody td.td-nombre { padding-top: {{ $padV }}mm; padding-bottom: {{ $padV }}mm; }

        .grid thead th.th-mes { padding: 4px 0; }
        .grid thead th.th-mes.angosto { font-size: 6pt; padding: 4px 0; }

        @if($filasApretadas)
        .grid tbody td.td-check { height: 4.2mm; }
        .grid tbody td.td-num,
        .grid tbody td.td-nombre { padding-top: 0; padding-bottom: 0; }
        @endif

        /* Detalle: cierre de semana más visible */
        .grid .vie { border-right: 1.3pt solid #0F172A; }

        /* Franja de evaluaciones alineada con las columnas de fecha */
        .eval-strip {
            width: {{ $anchoUtil }}mm;
            border-collapse: collapse;
            margin-bottom: 2px;
        }

        .eval-strip td {
            padding: 0;
            height: 4.4mm;
            text-align: center;
            vertical-align: middle;
        }

        .eval-strip td.eval-badge {
            background-color: #FFFFFF;
            color: #92400E;
            border: 0.75pt solid #B45309;
            font-family: 'Inter SemiBold', 'Inter', sans-serif;
            font-size: 7pt;
            letter-spacing: 0.05em;
        }

        .pdf-firmas { margin-top: {{ $compacto ? '7mm' : '12mm' }}; }
    </style>
</head>
<body>

@include('pdf.partials.encabezado', [
    'titulo' => 'Bachillerato Tecnológico Interamericano',
    'subtitulo' => 'Lista de Asistencia',
    'badge' => trim(($cct ?? '') . ' - ' . $modalidadLabel, ' -'),
])

@include('pdf.partials.metadatos', ['items' => [
    'Docente' => $docente,
    'Asignatura' => $asignatura,
    'Grupo' => $grupo,
]])

@if($mostrarEval)
    <table class="eval-strip">
        <tr>
            <td style="width: {{ $anchoUtil - $totalCols * ($anchoFecha + $bordeCol) }}mm;"></td>
            @foreach($segmentosEval as $seg)
                <td class="{{ $seg['eval'] ? 'eval-badge' : '' }}" style="width: {{ $seg['colspan'] * ($anchoFecha + $bordeCol) }}mm;">{{ $seg['eval'] ?? '' }}</td>
            @endforeach
        </tr>
    </table>
@endif

<table class="pdf-table grid">
    <thead>
        <tr>
            <th class="th-primario col-num" rowspan="3">#</th>
            <th class="th-primario th-nombre col-nombre" rowspan="3">Nombre del alumno</th>
            @foreach($mesesAgrupados as $m)
                @php $anchoMes = $m['colspan'] * $anchoFecha; @endphp
                {{-- Un mes con 1-2 columnas no cabe completo: se reduce o se omite para no ensanchar la tabla --}}
                <th class="th-primario th-mes {{ $anchoMes < 9 ? 'angosto' : '' }}" colspan="{{ $m['colspan'] }}">{{ $anchoMes >= 6 ? $m['mes'] : '' }}</th>
            @endforeach
        </tr>
        <tr>
            @foreach($columnasFechas as $i => $f)
                <th class="th-secundario col-fecha {{ $clasesCol[$i] }}">{{ $f['dia'] }}</th>
            @endforeach
        </tr>
        <tr>
            @foreach($columnasFechas as $i => $f)
                <th class="th-secundario col-fecha {{ $clasesCol[$i] }}">{{ $f['letra_dia'] }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($alumnos as $al)
            <tr>
                <td class="td-num col-num">{{ $al['num'] }}</td>
                @php $fs = $fuenteNombre($al['nombre'] ?? ''); @endphp
                <td class="td-nombre col-nombre"@if($fs) style="font-size: {{ $fs }}pt;"@endif>
                    @if(!empty($al['nombre'])){{ $al['nombre'] }}@else&nbsp;@endif
                </td>
                @foreach($columnasFechas as $i => $f)
                    <td class="td-check col-fecha {{ $clasesCol[$i] }}"></td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

<table class="pdf-firmas">
    <tr>
        <td style="width: 40%;">
            <div class="firma-linea"></div>
            <div class="firma-rol">Firma del Docente</div>
            <div class="firma-detalle">{{ $docente }}</div>
        </td>
        <td style="width: 20%;"></td>
        <td style="width: 40%;">
            <div class="firma-linea"></div>
            <div class="firma-rol">Control Escolar / Dirección</div>
            <div class="firma-detalle">Sello y firma de validación</div>
        </td>
    </tr>
</table>

</body>
</html>
