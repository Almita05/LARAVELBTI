<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de estudios - {{ $nombre }}</title>
@php
    $logoPath = public_path($plantel['logo'] ?? 'img/logo.png');
    $logoSrc = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : '';
@endphp
    <style>
        /* Carta vertical. El pie con el lema queda fijo al final de la hoja. */
        @page { size: letter portrait; margin: 14mm 20mm 22mm 20mm; }

        * { box-sizing: border-box; }
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10.5pt;
            color: #111111;
            margin: 0;
        }
        b { font-weight: bold; }

        /* ---------- Encabezado institucional ---------- */
        .membrete { width: 100%; border-collapse: collapse; }
        .membrete td { padding: 0; vertical-align: middle; }
        .membrete .logo-cell { width: 30mm; text-align: center; }
        .membrete .logo { width: 26mm; height: 26mm; }
        .banda-titulo {
            background: #1A56DB;
            color: #FFFFFF;
            font-family: Helvetica, Arial, sans-serif; white-space: nowrap;
            font-weight: bold;
            font-size: 14pt;
            text-align: center;
            padding: 2.2mm 2mm 1.2mm;
            letter-spacing: 0;
        }
        .banda-datos {
            background: #6F9EEA;
            color: #FFFFFF;
            font-size: 7pt;
            text-align: center;
            padding: 0.8mm 2mm 1mm;
            line-height: 1.3;
        }
        .banda-datos .sep { display: inline-block; width: 18mm; }

        /* ---------- Oficio ---------- */
        .oficio { text-align: right; margin-top: 10mm; line-height: 1.8; font-size: 10.5pt; }
        .destinatario { margin-top: 8mm; font-weight: bold; line-height: 1.8; }

        .texto { text-align: justify; line-height: 1.8; text-transform: uppercase; }
        .texto-sangria { margin: 7mm 0 0 13mm; }
        .alumno { text-align: center; font-weight: bold; margin: 5mm 0 5mm 13mm; }
        .lugar-fecha { text-align: center; margin-top: 6mm; }

        /* ---------- Firma ---------- */
        .firma { margin-top: 18mm; text-align: center; page-break-inside: avoid; }
        .firma-linea { width: 75mm; border-top: 0.8pt solid #111111; margin: 0 auto 2mm; }
        .firma-nombre { font-weight: bold; line-height: 1.7; }

        /* ---------- Pie ---------- */
        .pie {
            position: fixed;
            left: 0; right: 0; bottom: -10mm;
            background: #6F9EEA;
            color: #FFFFFF;
            font-size: 7.5pt;
            text-align: center;
            padding: 2.4mm 0;
        }
    </style>
</head>
<body>

<div class="pie">{{ $plantel['lema'] }}</div>

<table class="membrete">
    <tr>
        <td class="logo-cell">
            @if($logoSrc)<img src="{{ $logoSrc }}" class="logo" alt="Logo">@endif
        </td>
        <td>
            <div class="banda-titulo">{{ $plantel['encabezado'] }}</div>
            <div class="banda-datos">
                {{ $plantel['direccion'] }}<br>
                Clave SEP {{ $plantel['clave_sep'] }}<span class="sep"></span>{{ $plantel['rvoe'] }}
            </div>
        </td>
    </tr>
</table>

<div class="oficio">
    OFICIO NO. {{ $oficio }}<br>
    ASUNTO: <b>CONSTANCIA DE ESTUDIO</b>
</div>

<div class="destinatario">
    A QUIEN CORRESPONDA.<br>
    PRESENTE.
</div>

<div class="texto texto-sangria">
    EL QUE SUSCRIBE {{ $plantel['director'] }}, {{ $plantel['director_cargo'] }} DENOMINADO <b>{{ $plantel['nombre'] }}</b>, CON CLAVE OPERATIVA <b>{{ $plantel['clave_operativa'] }}</b>
    Y CLAVE DE CENTRO DE TRABAJO <b>{{ $plantel['cct'] }}</b>, UBICADO EN {{ $plantel['direccion_texto'] }}.
    HACE CONSTAR QUE EL C.
</div>

<div class="alumno">{{ $nombre }}</div>

<div class="texto">
    CON NÚMERO DE CONTROL <b>{{ $matricula ?: 'S/N' }}</b> SE ENCUENTRA CURSANDO ACTUALMENTE EL
    <b>{{ $semestre }}</b>@if($periodo) EN UN PERIODO COMPRENDIDO {{ $periodo }}@endif, CORRESPONDIENTE AL PLAN
    DE ESTUDIOS DEL {{ $plantel['sistema'] }}, EN ESTA INSTITUCIÓN.<br>
    SIN MÁS POR EL MOMENTO Y PARA CUALQUIER FIN QUE A LA PARTE INTERESADA CONVENGAN, SE EXTIENDE LA
    PRESENTE EN LA CIUDAD DE {{ $plantel['ciudad'] }} {{ $diaTexto }}.
</div>

<div class="lugar-fecha">{{ $plantel['ciudad'] }} A {{ $fechaFirma }}</div>

<div class="firma">
    <div class="firma-linea"></div>
    <div class="firma-nombre">
        {{ $plantel['director'] }}<br>
        {{ $plantel['director_cargo'] }}
    </div>
</div>

</body>
</html>
