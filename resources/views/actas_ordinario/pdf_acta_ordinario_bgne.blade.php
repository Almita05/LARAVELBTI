<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Control de calificaciones finales - {{ $grupo }}</title>
@php
    /*
     * Formato BGNE (sabatino/dominical). Carta horizontal, 261mm útiles.
     * Columnas de captura vacías para llenado a mano.
     */
    $n = max(1, count($alumnos));
    $altoFila = round(max(5.4, min(7.2, 100 / $n)), 2); // mm
    $columnasPct = 2;     // "% ____" además de la de examen
    $columnasLibres = 2;  // columnas sin encabezado, como en el formato en papel
@endphp
    <style>
        @page { size: letter landscape; margin: 8mm 9mm 8mm 9mm; }

        @include('pdf.partials.estilos-base')

        .pdf-header { padding-bottom: 12px; margin-bottom: 12px; }

        /* Bloque de datos: réplica de la cabecera del formato en papel */
        .datos { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .datos td {
            border: 0.75pt solid #64748B;
            padding: 4px 7px;
            vertical-align: middle;
            height: 6.5mm;
        }
        .datos .lbl {
            font-family: 'Inter SemiBold', 'Inter', sans-serif;
            font-size: 7pt;
            color: #475569;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .datos .val {
            font-family: 'Inter', sans-serif;
            font-weight: bold;
            font-size: 8.5pt;
            color: #0F172A;
            text-transform: uppercase;
        }
        .datos .modulo, .datos .sistema { text-align: center; }
        .datos .modulo .num { display: block; font-size: 14pt; font-weight: bold; line-height: 1; color: #0F172A; }
        .datos .modulo .txt,
        .datos .sistema .txt {
            display: block;
            font-family: 'Inter SemiBold', 'Inter', sans-serif;
            font-size: 6.5pt;
            color: #475569;
            text-transform: uppercase;
        }
        .datos .sistema .val { display: block; font-size: 9pt; }

        /* Tabla de calificaciones */
        .acta { width: 100%; }
        .acta thead th.th-primario { font-size: 6.5pt; line-height: 1.15; padding: 3px 2px; vertical-align: middle; }
        .acta thead th.th-pct { font-family: 'JetBrains Mono', monospace; font-size: 6.5pt; }
        .acta td { height: {{ $altoFila }}mm; }
        .acta td.td-num { font-size: 7pt; padding: 0 3px; }
        .acta td.td-nombre { font-size: 8pt; padding: 0 5px; }

        .pie { width: 100%; border-collapse: collapse; margin-top: 14mm; page-break-inside: avoid; }
        .pie td { padding: 0; vertical-align: bottom; }
        .nota { font-size: 7.5pt; color: #334155; }
        .nota strong { font-family: 'Inter SemiBold', 'Inter', sans-serif; font-weight: normal; }
    </style>
</head>
<body>

@include('pdf.partials.encabezado', [
    'titulo' => 'Bachillerato Interamericano',
    'subtitulo' => 'Control de calificaciones finales',
    'badge' => $grupo,
    'logo' => $logo,
])

<table class="datos">
    <tr>
        <td class="lbl" style="width: 22mm;">Asignatura:</td>
        <td class="val" style="width: 74mm;">{{ $asignatura }}</td>
        <td class="modulo" rowspan="2" style="width: 17mm;">
            <span class="num">{{ $trimestre ? $trimestre . '°' : '' }}</span>
            <span class="txt">Módulo</span>
        </td>
        <td class="lbl" style="width: 26mm;">Fecha de inicio:</td>
        <td class="val" style="width: 44mm;">{{ $fechaInicio }}</td>
        <td class="lbl" style="width: 32mm;">Fecha de entrega:</td>
        <td class="sistema" rowspan="2">
            <span class="txt">Sistema</span>
            <span class="val">{{ $sistema }}</span>
        </td>
    </tr>
    <tr>
        <td class="lbl">Docente:</td>
        <td class="val">{{ $docente }}</td>
        <td class="lbl">Fecha de fin:</td>
        <td class="val">{{ $fechaFin }}</td>
        <td class="val">{{ $fechaEntrega }}</td>
    </tr>
</table>

<table class="pdf-table acta">
    <thead>
        <tr>
            <th class="th-primario" style="width: 6mm;">N°</th>
            <th class="th-primario" style="width: 31mm;">Apellido paterno</th>
            <th class="th-primario" style="width: 31mm;">Apellido materno</th>
            <th class="th-primario" style="width: 38mm;">Nombre(s)</th>
            <th class="th-primario th-pct" style="width: 13mm;">%EXA<br>____</th>
            @for ($i = 0; $i < $columnasPct; $i++)
                <th class="th-primario th-pct" style="width: 13mm;">%<br>____</th>
            @endfor
            @for ($i = 0; $i < $columnasLibres; $i++)
                <th class="th-primario" style="width: 13mm;"></th>
            @endfor
            <th class="th-primario" style="width: 19mm;">Calificación con letra</th>
            <th class="th-primario" style="width: 24mm;">Firma del alumno</th>
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
                @for ($c = 0; $c < 1 + $columnasPct + $columnasLibres + 3; $c++)
                    <td class="td-check"></td>
                @endfor
            </tr>
        @empty
            <tr><td colspan="{{ 4 + 1 + $columnasPct + $columnasLibres + 3 }}" class="td-nombre" style="text-align: center; color: #64748B;">El grupo no tiene alumnos activos.</td></tr>
        @endforelse
    </tbody>
</table>

<table class="pie">
    <tr>
        <td style="width: 50%;" class="nota"><strong>NOTA:</strong> Asignar calificación final con número entero.</td>
        <td style="width: 12%;"></td>
        <td style="text-align: center;">
            <div class="firma-linea" style="width: 100%;"></div>
            <div class="firma-rol">Firma del docente</div>
            @if ($docente)<div class="firma-detalle">{{ $docente }}</div>@endif
        </td>
    </tr>
</table>

</body>
</html>
