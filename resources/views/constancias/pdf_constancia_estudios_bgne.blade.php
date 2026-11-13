<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de estudios - {{ $nombre }}</title>
@php
    $logoPath = public_path($plantel['logo'] ?? 'img/logo.png');
    if (!file_exists($logoPath)) $logoPath = public_path('img/logo.png');
    $logoSrc = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : '';
@endphp
    <style>
        /* Carta vertical. El pie con el lema queda fijo al final de la hoja. */
        @page { size: letter portrait; margin: 12mm 22mm 22mm 22mm; }

        * { box-sizing: border-box; }
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10.5pt;
            color: #111111;
            margin: 0;
        }
        b { font-weight: bold; }

        /* ---------- Membrete ---------- */
        .membrete { width: 100%; border-collapse: collapse; margin-left: -10mm; }
        .membrete td { padding: 0; vertical-align: middle; }
        .membrete .logo-cell { width: 30mm; text-align: center; }
        .membrete .logo { width: 25mm; }
        .banda-titulo {
            background: #1A56DB;
            color: #FFFFFF;
            font-weight: bold;
            font-size: 15pt;
            text-align: center;
            padding: 1.6mm 2mm 1mm;
        }
        .banda-datos {
            background: #B3DCF7;
            color: #1F2937;
            font-size: 7.5pt;
            text-align: center;
            padding: 0.8mm 2mm 1mm;
            line-height: 1.35;
        }

        /* ---------- Cuerpo ---------- */
        .oficio { text-align: right; margin-top: 8mm; line-height: 1.8; }
        .asunto { text-align: right; font-weight: bold; margin-top: 3mm; }
        .destinatario { margin-top: 6mm; font-weight: bold; font-size: 11pt; line-height: 2.2; }
        .texto { text-align: justify; line-height: 1.85; text-transform: uppercase; }
        .texto-primero { margin-top: 4mm; text-indent: 4mm; }
        .alumno { text-align: center; font-weight: bold; margin: 5mm 0; }
        .parrafo { margin-top: 4mm; }
        .atentamente { text-align: center; font-weight: bold; letter-spacing: 3pt; margin-top: 7mm; }

        /* ---------- Firma ---------- */
        .firma { margin-top: 17mm; text-align: center; page-break-inside: avoid; }
        .firma-linea { width: 72mm; border-top: 0.8pt solid #111111; margin: 0 auto 2mm; }
        .firma-nombre { font-weight: bold; line-height: 1.6; }

        /* ---------- Pie ---------- */
        .pie {
            position: fixed;
            left: 0; right: 0; bottom: -12mm;
            background: #6F9EEA;
            color: #FFFFFF;
            font-size: 8pt;
            text-align: center;
            padding: 1.6mm 0;
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
            <div class="banda-titulo">{{ $plantel['nombre'] }}</div>
            <div class="banda-datos">
                {{ $plantel['direccion'] }}<br>
                CLAVE CT {{ $plantel['cct'] }}
            </div>
        </td>
    </tr>
</table>

@if($oficio)
    <div class="oficio">OFICIO NO. {{ $oficio }}</div>
@endif
<div class="asunto" @if(!$oficio) style="margin-top: 12mm;" @endif>ASUNTO: CONSTANCIA DE ESTUDIOS</div>

<div class="destinatario">
    A QUIEN CORRESPONDA:<br>
    PRESENTE.
</div>

<div class="texto texto-primero">
    EL QUE SUSCRIBE {{ $plantel['director'] }}, DIRECTOR DEL {{ $plantel['nombre'] }}, CON CLAVE DE CENTRO
    DE TRABAJO <b>{{ $plantel['cct'] }}</b>, CON DOMICILIO EN {{ $plantel['direccion_texto'] }}, HACE CONSTAR QUE {{ $tratamiento }} C.
</div>

<div class="alumno">{{ $nombre }}</div>

@if($tipo === 'concluyo')
    <div class="texto">
        {{-- Sin directivas pegadas a palabras: Blade no las reconoce (p. ej. "SUPERIOR@if") --}}
        CON MATRÍCULA <b>{{ $matricula ?: 'S/N' }}</b> CONCLUYÓ SATISFACTORIAMENTE SU BACHILLERATO EN ESTA INSTITUCIÓN
        DE EDUCACIÓN MEDIA SUPERIOR{!! $ultimaMateria ? ', CON FECHA DE ÚLTIMA MATERIA <b>' . e($ultimaMateria) . '</b>' : '' !!}{!! $fechaCertificado ? ' Y TENIENDO FECHA DE EXPEDICIÓN DE CERTIFICADO <b>' . e($fechaCertificado) . '</b>' : '' !!}{!! $folio ? ', FOLIO DE CERTIFICADO <b>' . e($folio) . '</b>' : '' !!}.
    </div>
@else
    <div class="texto">
        CON MATRÍCULA <b>{{ $matricula ?: 'S/N' }}</b>, SE ENCUENTRA ACTUALMENTE CURSANDO EL <b>{{ $trimestre }} TRIMESTRE</b>
        DEL {{ $plantel['programa'] }}@if($periodo), EN UN PERIODO COMPRENDIDO <b>{{ $periodo }}</b>@endif, EN ESTA INSTITUCIÓN.
        @if($extracurricular)
            ASIMISMO, SE ENCUENTRA CURSANDO UN CURSO <b>EXTRACURRICULAR DE {{ $curso }}</b>, EL CUAL SE IMPARTE
            @if($dias) LOS DÍAS {{ $dias }}@endif EN HORARIO DE {{ $horario }}.
        @endif
    </div>
@endif

<div class="texto parrafo">
    POR LO ANTERIORMENTE EXPUESTO Y PARA CUALQUIER FIN QUE A LA PARTE INTERESADA CONVENGAN, SE EXTIENDE LA
    PRESENTE EN LA CIUDAD DE {{ $plantel['ciudad'] }} {{ $diaTexto }}.
</div>

<div class="atentamente">ATENTAMENTE</div>

<div class="firma">
    <div class="firma-linea"></div>
    <div class="firma-nombre">
        {{ $plantel['firmante'] }}<br>
        {{ $plantel['firmante_cargo'] }}
    </div>
</div>

</body>
</html>
