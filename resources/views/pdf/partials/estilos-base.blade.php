{{--
    Estilos compartidos para todos los PDF generados con dompdf.

    dompdf solo distingue "normal" y "bold" dentro de una familia, así que cada
    peso de Inter se registra como familia propia (Inter Medium / Inter SemiBold).
    Tampoco soporta flexbox: los layouts "flex" se resuelven con tablas.

    Uso: @include('pdf.partials.estilos-base') dentro de <style>.
--}}
@php $fontDir = str_replace('\\', '/', resource_path('fonts/pdf')); @endphp
@font-face { font-family: 'Inter'; font-weight: normal; font-style: normal; src: url('{{ $fontDir }}/Inter-400.ttf') format('truetype'); }
@font-face { font-family: 'Inter'; font-weight: bold; font-style: normal; src: url('{{ $fontDir }}/Inter-700.ttf') format('truetype'); }
@font-face { font-family: 'Inter Medium'; font-weight: normal; font-style: normal; src: url('{{ $fontDir }}/Inter-500.ttf') format('truetype'); }
@font-face { font-family: 'Inter SemiBold'; font-weight: normal; font-style: normal; src: url('{{ $fontDir }}/Inter-600.ttf') format('truetype'); }
@font-face { font-family: 'JetBrains Mono'; font-weight: normal; font-style: normal; src: url('{{ $fontDir }}/JetBrainsMono-600.ttf') format('truetype'); }

* { box-sizing: border-box; }

body {
    font-family: 'Inter', Helvetica, Arial, sans-serif;
    margin: 0;
    padding: 0;
    color: #0F172A;
    background: #FFFFFF;
    font-size: 8pt;
    line-height: 1.2;
}

/* Pesos de Inter (ver nota arriba) */
.fw-500 { font-family: 'Inter Medium', 'Inter', sans-serif; font-weight: normal; }
.fw-600 { font-family: 'Inter SemiBold', 'Inter', sans-serif; font-weight: normal; }
.fw-700 { font-family: 'Inter', sans-serif; font-weight: bold; }
.mono   { font-family: 'JetBrains Mono', 'Courier New', monospace; font-weight: normal; }

/* ---------- Page header ---------- */
.pdf-header {
    border-bottom: 2px solid #1E5B66;
    padding-bottom: 16px;
    margin-bottom: 16px;
}

.pdf-header table { width: 100%; border-collapse: collapse; }
.pdf-header td { padding: 0; vertical-align: middle; }
.pdf-header .logo-cell { width: 66px; }

.pdf-logo {
    height: 50px;
    width: auto;
    margin-right: 16px;
}

.pdf-title {
    font-family: 'Inter', sans-serif;
    font-size: 14pt;
    font-weight: bold;
    color: #0F172A;
    margin: 0;
    letter-spacing: -0.01em;
    text-transform: uppercase;
}

.pdf-subtitle {
    font-family: 'Inter Medium', 'Inter', sans-serif;
    font-weight: normal;
    font-size: 10pt;
    color: #475569;
    letter-spacing: 0.05em;
    margin: 4px 0 0 0;
    text-transform: uppercase;
}

.pdf-header .badge-cell { text-align: right; width: 1%; white-space: nowrap; padding-left: 16px; }

.pdf-badge {
    display: inline-block;
    background-color: #FFFFFF;
    color: #1E5B66;
    border: 1px solid #1E5B66;
    font-family: 'Inter SemiBold', 'Inter', sans-serif;
    font-weight: normal;
    font-size: 9pt;
    padding: 4px 10px;
    border-radius: 4px;
    text-transform: uppercase;
}

.pdf-badge-sub {
    font-family: 'Inter Medium', 'Inter', sans-serif;
    font-weight: normal;
    font-size: 8pt;
    color: #64748B;
    text-align: right;
    margin-top: 6px;
    text-transform: uppercase;
}

/* ---------- Metadatos (cajas sin borde izquierdo) ---------- */
.pdf-meta {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.pdf-meta td.meta-box {
    background-color: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 6px;
    padding: 10px 14px;
    vertical-align: top;
}

.pdf-meta td.meta-gap { width: 12px; padding: 0; border: none; background: transparent; }

.meta-label {
    display: block;
    font-family: 'Inter SemiBold', 'Inter', sans-serif;
    font-weight: normal;
    font-size: 7pt;
    color: #64748B;
    margin-bottom: 4px;
    text-transform: uppercase;
}

.meta-value {
    display: block;
    font-family: 'Inter', sans-serif;
    font-size: 9pt;
    font-weight: bold;
    color: #0F172A;
    text-transform: uppercase;
}

/* ---------- Tablas de datos (sin "efecto Excel") ---------- */
.pdf-table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Inter', sans-serif;
}

.pdf-table th,
.pdf-table td { border: 0.75pt solid #64748B; }

.pdf-table thead th.th-primario {
    background-color: #FFFFFF;
    color: #0F172A;
    font-family: 'Inter SemiBold', 'Inter', sans-serif;
    font-weight: normal;
    font-size: 8pt;
    text-align: center;
    padding: 4px;
    border: 0.75pt solid #64748B;
    border-bottom: 1.2pt solid #1E5B66;
    text-transform: uppercase;
}

.pdf-table thead th.th-secundario {
    background-color: #FFFFFF;
    color: #0F172A;
    font-family: 'JetBrains Mono', 'Courier New', monospace;
    font-weight: normal;
    font-size: 7pt;
    text-align: center;
    border: 0.75pt solid #64748B;
    padding: 2px 0;
}

.pdf-table td.td-num {
    text-align: center;
    font-size: 8pt;
    color: #64748B;
    border: 0.75pt solid #64748B;
    padding: 6px 4px;
}

.pdf-table td.td-nombre {
    font-family: 'Inter Medium', 'Inter', sans-serif;
    font-weight: normal;
    font-size: 8.5pt;
    color: #0F172A;
    border: 0.75pt solid #64748B;
    padding: 6px 8px;
    text-align: left;
    text-transform: uppercase;
    white-space: nowrap;
}

.pdf-table td.td-check {
    border: 0.75pt solid #64748B;
    height: 18px;
    min-width: 14px;
}

/* ---------- Firmas ---------- */
.pdf-firmas { width: 100%; border-collapse: collapse; }
.pdf-firmas td { text-align: center; vertical-align: bottom; padding: 0; }

.firma-linea {
    border-top: 1px solid #334155;
    width: 72%;
    margin: 0 auto 5px auto;
}

.firma-rol {
    font-family: 'Inter SemiBold', 'Inter', sans-serif;
    font-weight: normal;
    font-size: 7.5pt;
    color: #0F172A;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.firma-detalle {
    margin-top: 2px;
    font-size: 7pt;
    color: #64748B;
}
