<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta de calificaciones - {{ $grupo }}</title>
@php
    /*
     * Carta horizontal, 261mm útiles. Las columnas de captura quedan vacías
     * para que el docente las llene a mano.
     * El alto de fila se ajusta para que hasta ~22 alumnos quepan en una hoja;
     * con más alumnos la tabla continúa en otra hoja y repite el encabezado.
     */
    $n = max(1, count($alumnos));
    $altoFila = round(max(5.4, min(7.2, 100 / $n)), 2); // mm
    $columnasPct = 4; // "% ____" además de la columna de examen
@endphp
    <style>
        @page { size: letter landscape; margin: 8mm 9mm 8mm 9mm; }

        @include('pdf.partials.estilos-base')

        .pdf-header { padding-bottom: 12px; margin-bottom: 12px; }

        /* Datos del acta: dos columnas de campos con línea para escribir */
        .campos { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .campos td { padding: 3px 0; vertical-align: bottom; }
        .campos .lbl {
            width: 26mm;
            font-family: 'Inter SemiBold', 'Inter', sans-serif;
            font-size: 7.5pt;
            color: #475569;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .campos .lbl-der { width: 34mm; padding-left: 10mm; }
        .campos .val {
            border-bottom: 0.75pt solid #64748B;
            font-family: 'Inter', sans-serif;
            font-weight: bold;
            font-size: 9pt;
            color: #0F172A;
            text-transform: uppercase;
            padding-left: 4px;
            height: 5.5mm;
        }

        /* Tabla del acta */
        .acta { width: 100%; }
        .acta thead th.th-primario {
            font-size: 6.5pt;
            line-height: 1.15;
            padding: 3px 2px;
            vertical-align: middle;
        }
        .acta thead th.th-pct { font-family: 'JetBrains Mono', monospace; font-size: 6.5pt; }
        .acta td { height: {{ $altoFila }}mm; }
        .acta td.td-num { font-size: 7pt; padding: 0 3px; }
        .acta td.td-nombre { font-size: 8pt; padding: 0 5px; }

        .firma-docente { width: 100%; border-collapse: collapse; margin-top: 12mm; page-break-inside: avoid; }
        .firma-docente td { padding: 0; text-align: center; }
    </style>
</head>
<body>

@include('pdf.partials.encabezado', [
    'titulo' => 'Bachillerato Tecnológico Interamericano',
    'subtitulo' => 'Control de calificaciones · ' . $ciclo,
    'badge' => 'Control escolar',
])

<table class="campos">
    <tr>
        <td class="lbl">Docente:</td><td class="val">{{ $docente }}</td>
        <td class="lbl lbl-der">Examen:</td><td class="val" style="width: 62mm;">{{ $examen }}</td>
    </tr>
    <tr>
        <td class="lbl">Asignatura:</td><td class="val">{{ $asignatura }}</td>
        <td class="lbl lbl-der">Fecha de examen:</td><td class="val">{{ $fechaExamen }}</td>
    </tr>
    <tr>
        <td class="lbl">Grupo:</td><td class="val">{{ $grupo }}</td>
        <td class="lbl lbl-der">Fecha de entrega:</td><td class="val">{{ $fechaEntrega }}</td>
    </tr>
</table>

<table class="pdf-table acta">
    <thead>
        <tr>
            <th class="th-primario" style="width: 6mm;">N°</th>
            <th class="th-primario" style="width: 29mm;">Apellido paterno</th>
            <th class="th-primario" style="width: 29mm;">Apellido materno</th>
            <th class="th-primario" style="width: 35mm;">Nombre(s)</th>
            <th class="th-primario th-pct" style="width: 11mm;">EXAMEN<br>__%</th>
            @for ($i = 0; $i < $columnasPct; $i++)
                <th class="th-primario th-pct" style="width: 11mm;">__%</th>
            @endfor
            <th class="th-primario" style="width: 11mm;">Núm. de faltas</th>
            <th class="th-primario" style="width: 13mm;">Calificación 0-10</th>
            <th class="th-primario" style="width: 18mm;">Calificación con letra</th>
            <th class="th-primario" style="width: 23mm;">Firma del alumno</th>
            <th class="th-primario">Observaciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($alumnos as $i => $al)
            <tr>
                <td class="td-num">{{ $i + 1 }}</td>
                <td class="td-nombre">{{ $al['apPaterno'] }}</td>
                <td class="td-nombre">{{ $al['apMaterno'] }}</td>
                <td class="td-nombre">{{ $al['nombre'] }}</td>
                @for ($c = 0; $c < $columnasPct + 1; $c++)
                    <td class="td-check"></td>
                @endfor
                <td class="td-check"></td>
                <td class="td-check"></td>
                <td class="td-check"></td>
                <td class="td-check"></td>
                <td class="td-check"></td>
            </tr>
        @empty
            <tr><td colspan="{{ 4 + $columnasPct + 1 + 5 }}" class="td-nombre" style="text-align: center; color: #64748B;">El grupo no tiene alumnos activos.</td></tr>
        @endforelse
    </tbody>
</table>

<table class="firma-docente">
    <tr>
        <td style="width: 62%;"></td>
        <td>
            <div class="firma-linea" style="width: 100%;"></div>
            <div class="firma-rol">Nombre y firma del docente</div>
            @if ($docente)<div class="firma-detalle">{{ $docente }}</div>@endif
        </td>
    </tr>
</table>

</body>
</html>
