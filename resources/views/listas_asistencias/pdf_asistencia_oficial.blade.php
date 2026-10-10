<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Asistencia - {{ $grupo }}</title>
    <style>
        @page {
            size: letter landscape;
            margin: 4mm 6mm 4mm 6mm;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            color: #000;
            background: #fff;
            font-size: 7.2pt;
            line-height: 1.15;
        }

        .header-container {
            width: 100%;
            margin-bottom: 2px;
            position: relative;
        }

        .school-title {
            text-align: center;
            font-size: 11.5pt;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin: 0;
            color: #000;
        }

        .sheet-title {
            text-align: center;
            font-size: 9.5pt;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin: 1px 0 0 0;
            color: #000;
        }

        .logo-box {
            position: absolute;
            right: 0;
            top: -2px;
            width: 55px;
            text-align: right;
        }

        .logo-img {
            height: 42px;
            object-fit: contain;
        }

        /* Metadatos (Docente, Asignatura, Grupo) */
        .meta-table {
            width: 60%;
            border-collapse: separate;
            border-spacing: 0 1.5px;
            margin-top: 2px;
            margin-bottom: 4px;
            font-size: 7.2pt;
        }

        .meta-label {
            font-weight: bold;
            text-transform: uppercase;
            padding-right: 5px;
            width: 18%;
            vertical-align: middle;
            font-size: 7pt;
        }

        .meta-val-box {
            border: 1px solid #000;
            padding: 1.5px 6px;
            background: #fff;
            text-transform: uppercase;
            font-weight: 600;
            font-size: 7.2pt;
            border-radius: 2px;
        }

        /* Tabla de Asistencia */
        .attendance-grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1px;
            font-size: 6.5pt;
        }

        .attendance-grid th, 
        .attendance-grid td {
            border: 0.8px solid #000;
            padding: 1px 1px;
            text-align: center;
            vertical-align: middle;
        }

        .th-num {
            background-color: #dae3f3;
            color: #000;
            font-weight: bold;
            font-size: 6.8pt;
            width: 16px;
        }

        .th-alumnos {
            background-color: #dae3f3;
            color: #000;
            font-weight: bold;
            font-size: 7.2pt;
            text-align: center;
            width: {{ ($isEscolarizado ?? false) ? '175px' : '230px' }};
        }

        .th-mes {
            background-color: #dae3f3;
            font-weight: bold;
            font-size: 6.5pt;
            text-transform: uppercase;
            padding: 1.5px 1px;
        }

        .th-dia {
            font-weight: bold;
            font-size: 6.2pt;
            background-color: #f2f2f2;
            height: 12px;
        }

        .th-letra {
            font-weight: bold;
            font-size: 5.8pt;
            background-color: #ffffff;
            height: 11px;
        }

        .eval-highlight {
            background-color: #b4c6e7 !important;
            font-weight: bold;
        }

        .td-num {
            font-size: 6.2pt;
            font-weight: bold;
            width: 16px;
        }

        .td-alumno-nombre {
            text-align: left !important;
            padding-left: 4px !important;
            font-size: 6.5pt;
            height: 13.5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-transform: uppercase;
        }

        .td-check-cell {
            height: 13.5px;
        }

        .eval-badge-cell {
            background-color: #8ea9db;
            color: #000;
            border: 1px solid #000;
            text-align: center;
            padding: 1px 0;
            font-size: 6.8pt;
            font-weight: 800;
        }

        /* Firmas */
        .footer-signatures {
            width: 100%;
            margin-top: 8px;
            border-collapse: collapse;
            font-size: 6.5pt;
        }

        .sig-line {
            border-top: 0.8px solid #000;
            width: 55%;
            margin: 0 auto 2px auto;
        }
    </style>
</head>
<body>

@php
    $logoPath = public_path('img/logo.png');
    $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : '';
@endphp

<div class="header-container">
    @if($logoBase64)
        <div class="logo-box">
            <img src="{{ $logoBase64 }}" class="logo-img" alt="Logo">
        </div>
    @endif
    <h1 class="school-title">Bachillerato Tecnológico Interamericano</h1>
    <h2 class="sheet-title">Lista de Asistencia</h2>
</div>

<!-- Metadatos de la clase -->
<table class="meta-table">
    <tr>
        <td class="meta-label">Docente :</td>
        <td class="meta-val-box">{{ $docente }}</td>
    </tr>
    <tr>
        <td class="meta-label">Asignatura :</td>
        <td class="meta-val-box">{{ $asignatura }}</td>
    </tr>
    <tr>
        <td class="meta-label">Grupo :</td>
        <td class="meta-val-box">{{ $grupo }}</td>
    </tr>
</table>

<!-- Tabla de Asistencia -->
<table class="attendance-grid">
    <thead>
        @if(!($isEscolarizado ?? false) && collect($columnasFechas)->contains(fn($f) => !empty($f['eval'])))
            <!-- Fila de evaluaciones P.1 y P.2 solo para sabatino/dominical -->
            <tr>
                <th style="border: none; background: transparent;" colspan="2"></th>
                @foreach($columnasFechas as $f)
                    @if($f['eval'])
                        <th class="eval-badge-cell" style="border: 1px solid #000; background-color: #b4c6e7;">
                            {{ $f['eval'] }}
                        </th>
                    @else
                        <th style="border: none; background: transparent; height: 11px;"></th>
                    @endif
                @endforeach
            </tr>
        @endif

        <!-- Fila 1: Meses agrupados -->
        <tr>
            <th class="th-num" rowspan="3">#</th>
            <th class="th-alumnos" rowspan="3">NOMBRE DEL ALUMNO</th>
            @foreach($mesesAgrupados as $m)
                <th class="th-mes" colspan="{{ $m['colspan'] }}">
                    {{ $m['mes'] }}
                </th>
            @endforeach
        </tr>

        <!-- Fila 2: Días del mes -->
        <tr>
            @foreach($columnasFechas as $f)
                <th class="th-dia {{ $f['eval'] ? 'eval-highlight' : '' }}">
                    {{ $f['dia'] }}
                </th>
            @endforeach
        </tr>

        <!-- Fila 3: Letra del día (L, M, M, J, V / S / D) -->
        <tr>
            @foreach($columnasFechas as $f)
                <th class="th-letra {{ $f['eval'] ? 'eval-highlight' : '' }}">
                    {{ $f['letra_dia'] }}
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($alumnos as $idx => $al)
            <tr>
                <td class="td-num">{{ $al['num'] }}</td>
                <td class="td-alumno-nombre">
                    @if(!empty($al['nombre']))
                        {{ $al['nombre'] }}
                    @else
                        &nbsp;
                    @endif
                </td>
                @foreach($columnasFechas as $f)
                    <td class="td-check-cell"></td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Firmas al pie -->
<table class="footer-signatures">
    <tr>
        <td style="width: 40%; text-align: center;">
            <div class="sig-line"></div>
            <strong>Firma del Docente</strong><br>
            <span style="font-size: 6.2pt; color: #333;">{{ $docente }}</span>
        </td>
        <td style="width: 20%;"></td>
        <td style="width: 40%; text-align: center;">
            <div class="sig-line"></div>
            <strong>Control Escolar / Dirección</strong><br>
            <span style="font-size: 6.2pt; color: #333;">Sello y Firma de Validación</span>
        </td>
    </tr>
</table>

</body>
</html>
