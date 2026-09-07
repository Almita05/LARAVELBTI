<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Asistencia - {{ $grupo }}</title>
    <style>
        @page {
            size: letter landscape;
            margin: 10mm 12mm 10mm 12mm;
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
            font-size: 8pt;
        }

        .header-container {
            width: 100%;
            margin-bottom: 8px;
            position: relative;
        }

        .school-title {
            text-align: center;
            font-size: 15pt;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 0;
            color: #000;
        }

        .sheet-title {
            text-align: center;
            font-size: 12pt;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin: 3px 0 0 0;
            color: #000;
        }

        .logo-box {
            position: absolute;
            right: 0;
            top: -5px;
            width: 75px;
            text-align: right;
        }

        .logo-img {
            height: 70px;
            object-fit: contain;
        }

        /* Metadatos (Docente, Asignatura, Grupo) */
        .meta-table {
            width: 58%;
            border-collapse: separate;
            border-spacing: 0 3px;
            margin-top: 5px;
            margin-bottom: 8px;
            font-size: 8pt;
        }

        .meta-label {
            font-weight: bold;
            text-transform: uppercase;
            padding-right: 6px;
            width: 20%;
            vertical-align: middle;
            font-size: 7.8pt;
        }

        .meta-val-box {
            border: 1.2px solid #000;
            padding: 2.5px 8px;
            background: #fff;
            text-transform: uppercase;
            font-weight: 600;
            font-size: 8pt;
            border-radius: 3px;
        }

        /* Tabla de Asistencia */
        .attendance-grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2px;
            font-size: 7.2pt;
        }

        .attendance-grid th, 
        .attendance-grid td {
            border: 1px solid #000;
            padding: 2.5px 2px;
            text-align: center;
            vertical-align: middle;
        }

        .th-alumnos {
            background-color: #dae3f3;
            color: #000;
            font-weight: bold;
            font-size: 8pt;
            text-align: center;
            width: 250px;
        }

        .th-mes {
            background-color: #dae3f3;
            font-weight: bold;
            font-size: 7.5pt;
            text-transform: uppercase;
            padding: 3px 1px;
        }

        .th-dia {
            font-weight: bold;
            font-size: 7.2pt;
            background-color: #f2f2f2;
            height: 16px;
        }

        .th-letra {
            font-weight: bold;
            font-size: 7pt;
            background-color: #ffffff;
            height: 15px;
        }

        .eval-highlight {
            background-color: #b4c6e7 !important;
            font-weight: bold;
        }

        .td-alumno-nombre {
            text-align: left !important;
            padding-left: 6px !important;
            font-size: 7.2pt;
            height: 17px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-transform: uppercase;
        }

        .td-check-cell {
            width: 23px;
            height: 17px;
        }

        /* Indicador de evaluaciones arriba de la tabla */
        .eval-top-bar {
            width: 100%;
            margin-bottom: 2px;
            border-collapse: collapse;
            font-size: 6.8pt;
            font-weight: bold;
        }

        .eval-badge-cell {
            background-color: #8ea9db;
            color: #000;
            border: 1px solid #000;
            text-align: center;
            padding: 1px 0;
            font-size: 7pt;
            font-weight: 800;
        }

        /* Firmas */
        .footer-signatures {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
            font-size: 7.5pt;
        }

        .sig-line {
            border-top: 1px solid #000;
            width: 70%;
            margin: 0 auto 3px auto;
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
        <!-- Fila de evaluaciones P.1 y P.2 alineadas con sus semanas -->
        <tr>
            <th style="border: none; background: transparent;" rowspan="1"></th>
            @foreach($columnasFechas as $f)
                @if($f['eval'])
                    <th class="eval-badge-cell" style="border: 1px solid #000; background-color: #b4c6e7; font-size: 7.2pt;">
                        {{ $f['eval'] }}
                    </th>
                @else
                    <th style="border: none; background: transparent; height: 14px;"></th>
                @endif
            @endforeach
        </tr>

        <!-- Fila 1: Meses agrupados -->
        <tr>
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

        <!-- Fila 3: Letra del día (D / S / etc.) -->
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
                <td class="td-alumno-nombre">
                    @if(!empty($al['nombre']))
                        {{ $al['nombre'] }}
                    @else
                        &nbsp;
                    @endif
                </td>
                @for($col = 0; $col < $totalSemanas; $col++)
                    <td class="td-check-cell"></td>
                @endfor
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
            <span style="font-size: 6.8pt; color: #333;">{{ $docente }}</span>
        </td>
        <td style="width: 20%;"></td>
        <td style="width: 40%; text-align: center;">
            <div class="sig-line"></div>
            <strong>Control Escolar / Dirección</strong><br>
            <span style="font-size: 6.8pt; color: #333;">Sello y Firma de Validación</span>
        </td>
    </tr>
</table>

</body>
</html>
