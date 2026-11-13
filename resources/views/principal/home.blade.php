@extends('layouts.app')

@section('content')
@php
    // Estilos reutilizados en la vista
    $card = ' tw:border tw:border-slate-200 tw:bg-white tw:shadow-card';
    $kpiCard = $card . ' tw:group tw:relative tw:overflow-hidden tw:p-5 tw:transition-all tw:duration-200 tw:hover:-translate-y-0.5 tw:hover:border-brand-200 tw:hover:shadow-raised';
    $kpiIcon = 'tw:inline-flex tw:size-10 tw:items-center tw:justify-center tw:bg-brand-50 tw:text-brand-700 tw:ring-1 tw:ring-brand-200/70 tw:transition-colors tw:group-hover:bg-brand-700 tw:group-hover:text-white';
    $kpiLabel = 'tw:text-[0.8125rem] tw:font-medium tw:text-slate-500';
    $kpiValue = 'tw:mt-3 tw:mb-0 tw:text-[2rem] tw:font-bold tw:leading-none tw:tracking-tight tw:text-slate-900 tw:tabular-nums';
    $kpiFooter = 'tw:mt-4 tw:flex tw:items-center tw:justify-between tw:gap-2 tw:border-t tw:border-slate-100 tw:pt-3 tw:text-xs tw:text-slate-500';
    $chip = 'tw:inline-flex tw:items-center tw:gap-1 tw:px-2.5 tw:py-0.5 tw:text-[0.6875rem] tw:font-semibold';
    $skeleton = '<span class="tw:inline-block tw:h-8 tw:w-24 tw:bg-[linear-gradient(90deg,#F1F5F9_25%,#E2E8F0_50%,#F1F5F9_75%)] tw:bg-[length:200%_100%] tw:animate-shimmer" data-skeleton></span>';
    $panelTitle = 'tw:m-0 tw:text-[0.9375rem] tw:font-semibold tw:tracking-tight tw:text-slate-900';
    $panelSubtitle = 'tw:m-0 tw:mt-0.5 tw:text-[0.8125rem] tw:text-slate-500';

    $accesos = [
        ['route' => 'alumnos',                          'icon' => 'search',          'title' => 'Buscador de Alumnos', 'hint' => 'Expedientes y CURP'],
        ['route' => 'grupos.captura_calificaciones',    'icon' => 'notebook-pen',    'title' => 'Capturar Notas',      'hint' => 'Parciales y semestral'],
        ['route' => 'asistencias_alumnos',              'icon' => 'clipboard-check', 'title' => 'Pase de Lista',       'hint' => 'Control de asistencias'],
        ['route' => 'reportes.imprimir',                'icon' => 'printer',         'title' => 'Módulo de Impresión', 'hint' => 'Kardex, boletas, credenciales'],
        ['route' => 'horarios',                         'icon' => 'calendar-clock',  'title' => 'Horarios',            'hint' => 'Aulas y profesores'],
        ['route' => 'reportes_indisciplina.index_view', 'icon' => 'shield-alert',    'title' => 'Indisciplina',        'hint' => 'Reportes y citatorios', 'danger' => true],
    ];

    $ccts = [
        ['icon' => 'school',      'nombre' => 'Bachillerato Tecnológico Interamericano', 'clave' => '21PCT0073R', 'modalidad' => 'Escolarizado',     'tone' => 'tw:bg-success-100 tw:text-success-700 tw:ring-success-200'],
        ['icon' => 'book-marked', 'nombre' => 'Bachillerato General No Escolarizado',    'clave' => '21PBH0353G', 'modalidad' => 'Fines de semana',  'tone' => 'tw:bg-brand-50 tw:text-brand-700 tw:ring-brand-200'],
        ['icon' => 'cpu',         'nombre' => 'Informática y Computación',               'clave' => '21PBT0322I', 'modalidad' => 'Técnico',          'tone' => 'tw:bg-slate-100 tw:text-slate-700 tw:ring-slate-200'],
    ];
@endphp

<div class="tw:mx-auto tw:flex tw:max-w-[1600px] tw:flex-col tw:gap-6">

    {{-- ===================== ENCABEZADO ===================== --}}
    <section class="tw:flex tw:flex-col tw:gap-4 tw:lg:flex-row tw:lg:items-end tw:lg:justify-between">
        <div class="tw:min-w-0">
            <div class="tw:flex tw:flex-wrap tw:items-center tw:gap-2.5">
                <span class="{{ $chip }} tw:bg-success-100 tw:text-success-700 tw:ring-1 tw:ring-success-200">
                    <span class="tw:size-1.5 tw:rounded-full tw:bg-success-700 tw:animate-pulse-dot"></span>
                    Sistema en línea
                </span>
                <span class="tw:text-xs tw:font-medium tw:text-slate-400">Panel de Control Escolar</span>
            </div>
            <h1 id="greeting-title" class="tw:mt-2 tw:mb-0 tw:text-[1.625rem] tw:font-bold tw:tracking-tight tw:text-slate-900">
                ¡Bienvenido, {{ session('nombre') }}!
            </h1>
            <p class="tw:m-0 tw:mt-1 tw:text-sm tw:text-slate-500">Monitoreo en tiempo real de indicadores académicos y matrícula.</p>
        </div>

        <div class="tw:flex tw:flex-wrap tw:items-center tw:gap-2.5">
            <div class="tw:inline-flex tw:h-10 tw:items-center tw:gap-2 tw:border tw:border-slate-200 tw:bg-white tw:px-3.5 tw:text-[0.8125rem] tw:font-medium tw:text-slate-600 tw:shadow-xs">
                <i data-lucide="calendar" class="tw:size-4 tw:text-brand-700"></i>
                <span id="current-date-text">Cargando fecha…</span>
            </div>
            <button type="button" id="btnRefreshStats" onclick="cargarMetricasDashboard()"
                class="tw:inline-flex tw:h-10 tw:cursor-pointer tw:items-center tw:gap-2 tw:border-0 tw:bg-brand-700 tw:px-4 tw:text-[0.8125rem] tw:font-semibold tw:text-white tw:shadow-brand tw:transition-all tw:hover:bg-brand-800 tw:active:scale-[0.98] tw:disabled:cursor-wait tw:disabled:opacity-80 tw:focus-visible:outline-2 tw:focus-visible:outline-offset-2 tw:focus-visible:outline-brand-500">
                <span id="refreshIcon" class="tw:inline-flex"><i data-lucide="refresh-cw" class="tw:size-4"></i></span>
                Actualizar indicadores
            </button>
        </div>
    </section>

    {{-- ===================== KPIs ===================== --}}
    <section class="tw:grid tw:grid-cols-1 tw:gap-4 tw:sm:grid-cols-2 tw:xl:grid-cols-4">

        {{-- KPI destacado: Matrícula --}}
        <article class="tw:group tw:relative tw:overflow-hidden tw:bg-gradient-to-br tw:from-brand-600 tw:via-brand-700 tw:to-brand-800 tw:p-5 tw:text-white tw:shadow-brand tw:transition-all tw:duration-200 tw:hover:-translate-y-0.5">
            <span class="tw:pointer-events-none tw:absolute tw:-top-12 tw:-right-10 tw:size-40 tw:bg-white/10"></span>
            <span class="tw:pointer-events-none tw:absolute tw:-bottom-16 tw:right-10 tw:size-32 tw:bg-accent-400/15"></span>
            <div class="tw:relative tw:flex tw:items-start tw:justify-between">
                <span class="tw:text-[0.8125rem] tw:font-medium tw:text-white/75">Matrícula activa</span>
                <span class="tw:inline-flex tw:size-10 tw:items-center tw:justify-center tw:bg-white/15 tw:ring-1 tw:ring-white/20">
                    <i data-lucide="graduation-cap" class="tw:size-5"></i>
                </span>
            </div>
            <h3 id="kpi-activos" class="tw:relative tw:mt-3 tw:mb-0 tw:text-[2rem] tw:font-bold tw:leading-none tw:tracking-tight tw:tabular-nums"><span class="tw:inline-block tw:h-8 tw:w-24 tw:bg-white/20 tw:animate-pulse" data-skeleton></span></h3>
            <div class="tw:relative tw:mt-4 tw:flex tw:items-center tw:justify-between tw:gap-2 tw:border-t tw:border-white/15 tw:pt-3 tw:text-xs tw:text-white/75">
                <span class="{{ $chip }} tw:bg-white/15 tw:text-white">
                    <span id="kpi-certificados">0</span> certificados
                </span>
                <span title="Total histórico registrado">Total: <strong id="kpi-total-alumnos" class="tw:font-semibold tw:text-white">--</strong></span>
            </div>
        </article>

        {{-- KPI: Docentes --}}
        <article class="{{ $kpiCard }}">
            <div class="tw:flex tw:items-start tw:justify-between">
                <span class="{{ $kpiLabel }}">Plantilla docente</span>
                <span class="{{ $kpiIcon }}"><i data-lucide="presentation" class="tw:size-5"></i></span>
            </div>
            <h3 id="kpi-docentes" class="{{ $kpiValue }}">{!! $skeleton !!}</h3>
            <div class="{{ $kpiFooter }}">
                <span class="{{ $chip }} tw:bg-slate-100 tw:text-slate-700">Docentes activos</span>
                <span><strong id="kpi-materias" class="tw:font-semibold tw:text-slate-700">--</strong> materias</span>
            </div>
        </article>

        {{-- KPI: Grupos --}}
        <article class="{{ $kpiCard }}">
            <div class="tw:flex tw:items-start tw:justify-between">
                <span class="{{ $kpiLabel }}">Grupos escolares</span>
                <span class="{{ $kpiIcon }}"><i data-lucide="layout-grid" class="tw:size-5"></i></span>
            </div>
            <h3 id="kpi-grupos" class="{{ $kpiValue }}">{!! $skeleton !!}</h3>
            <div class="{{ $kpiFooter }}">
                <span class="{{ $chip }} tw:bg-slate-100 tw:text-slate-700">BTI y BGNE</span>
                <span>Entre semana, sábado y domingo</span>
            </div>
        </article>

        {{-- KPI: Asistencia --}}
        <article class="{{ $kpiCard }}">
            <div class="tw:flex tw:items-start tw:justify-between">
                <span class="{{ $kpiLabel }}">Tasa de asistencia</span>
                <span class="{{ $kpiIcon }}"><i data-lucide="user-check" class="tw:size-5"></i></span>
            </div>
            <h3 id="kpi-asistencia" class="{{ $kpiValue }}">{!! $skeleton !!}</h3>
            <div class="{{ $kpiFooter }}">
                <span id="badge-asistencia-status" class="{{ $chip }} tw:bg-slate-100 tw:text-slate-700">Calculando…</span>
                <span><strong id="kpi-presentes" class="tw:font-semibold tw:text-slate-700">0</strong> presentes, <strong id="kpi-faltas" class="tw:font-semibold tw:text-slate-700">0</strong> faltas</span>
            </div>
        </article>
    </section>

    {{-- ===================== GRÁFICAS ===================== --}}
    <section class="tw:grid tw:grid-cols-1 tw:gap-4 tw:lg:grid-cols-3">

        <article class="{{ $card }} tw:p-6 tw:lg:col-span-2">
            <header class="tw:flex tw:items-start tw:justify-between tw:gap-4">
                <div>
                    <h2 class="{{ $panelTitle }}">Alumnos por grupo</h2>
                    <p class="{{ $panelSubtitle }}">Distribución de estudiantes inscritos en los grupos activos</p>
                </div>
                <span class="{{ $chip }} tw:shrink-0 tw:bg-brand-50 tw:text-brand-700 tw:ring-1 tw:ring-brand-200">Ciclo actual</span>
            </header>
            <div class="tw:relative tw:mt-5 tw:h-[300px]">
                <canvas id="chartGrupos"></canvas>
                {{-- Bootstrap d-none controla la visibilidad del contenedor externo --}}
                <div id="chartGruposEmpty" class="d-none">
                    <div class="tw:absolute tw:inset-0 tw:flex tw:flex-col tw:items-center tw:justify-center tw:gap-2 tw:border tw:border-dashed tw:border-slate-200 tw:bg-slate-50/60">
                        <span class="tw:inline-flex tw:size-11 tw:items-center tw:justify-center tw:bg-white tw:text-slate-400 tw:shadow-xs"><i data-lucide="inbox" class="tw:size-5"></i></span>
                        <p class="tw:m-0 tw:text-[0.8125rem] tw:text-slate-500">No hay datos de grupos disponibles</p>
                    </div>
                </div>
            </div>
        </article>

        <article class="{{ $card }} tw:flex tw:flex-col tw:p-6">
            <header>
                <h2 class="{{ $panelTitle }}">Estatus del alumnado</h2>
                <p class="{{ $panelSubtitle }}">Composición histórica y activa</p>
            </header>
            <div class="tw:relative tw:mt-5 tw:h-[220px]">
                <canvas id="chartEstatus"></canvas>
                <div class="tw:pointer-events-none tw:absolute tw:inset-0 tw:flex tw:flex-col tw:items-center tw:justify-center">
                    <span id="donut-center-total" class="tw:text-[1.75rem] tw:font-bold tw:leading-none tw:tracking-tight tw:text-slate-900 tw:tabular-nums">--</span>
                    <span class="tw:mt-1 tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.08em] tw:text-slate-400">Activos</span>
                </div>
            </div>
            <dl class="tw:m-0 tw:mt-auto tw:grid tw:grid-cols-3 tw:gap-2 tw:border-t tw:border-slate-100 tw:pt-4">
                @foreach ([['Activos', 'legend-activos', 'tw:bg-brand-700'], ['Certificados', 'legend-certificados', 'tw:bg-success-500'], ['Histórico', 'legend-inactivos', 'tw:bg-slate-300']] as [$lbl, $id, $dot])
                <div class=" tw:bg-slate-50 tw:px-3 tw:py-2.5 tw:text-center">
                    <dt class="tw:flex tw:items-center tw:justify-center tw:gap-1.5 tw:text-[0.6875rem] tw:font-medium tw:text-slate-500">
                        <span class="tw:size-2 tw:rounded-full {{ $dot }}"></span>{{ $lbl }}
                    </dt>
                    <dd id="{{ $id }}" class="tw:m-0 tw:mt-1 tw:text-base tw:font-bold tw:text-slate-900 tw:tabular-nums">0</dd>
                </div>
                @endforeach
            </dl>
        </article>
    </section>

    {{-- ===================== ACCESOS RÁPIDOS ===================== --}}
    <section>
        <div class="tw:mb-3 tw:flex tw:items-baseline tw:justify-between">
            <h2 class="tw:m-0 tw:text-[0.9375rem] tw:font-semibold tw:tracking-tight tw:text-slate-900">Accesos rápidos</h2>
            <span class="tw:text-xs tw:text-slate-400">Módulos más utilizados</span>
        </div>
        <div class="tw:grid tw:grid-cols-1 tw:gap-3 tw:sm:grid-cols-2 tw:lg:grid-cols-3">
            @foreach ($accesos as $a)
            @php $danger = $a['danger'] ?? false; @endphp
            <a href="{{ route($a['route']) }}"
                @class([
                    'tw:group tw:flex tw:items-center tw:gap-3 tw:border tw:border-slate-200 tw:bg-white tw:p-4 tw:no-underline tw:shadow-xs tw:transition-all tw:duration-200 tw:hover:-translate-y-0.5 tw:hover:shadow-raised',
                    'tw:hover:border-danger-200' => $danger,
                    'tw:hover:border-brand-200' => !$danger,
                ])>
                <span @class([
                    'tw:inline-flex tw:size-11 tw:shrink-0 tw:items-center tw:justify-center tw:transition-colors tw:duration-200',
                    'tw:bg-danger-50 tw:text-danger-600 tw:group-hover:bg-danger-600 tw:group-hover:text-white' => $danger,
                    'tw:bg-brand-50 tw:text-brand-700 tw:group-hover:bg-brand-700 tw:group-hover:text-white' => !$danger,
                ])>
                    <i data-lucide="{{ $a['icon'] }}" class="tw:size-5"></i>
                </span>
                <span class="tw:flex tw:min-w-0 tw:flex-1 tw:flex-col">
                    <span class="tw:truncate tw:text-[0.8125rem] tw:font-semibold tw:text-slate-900">{{ $a['title'] }}</span>
                    <span class="tw:truncate tw:text-xs tw:text-slate-500">{{ $a['hint'] }}</span>
                </span>
                <i data-lucide="arrow-up-right" class="tw:size-4 tw:shrink-0 tw:text-slate-300 tw:transition-colors tw:group-hover:text-slate-500"></i>
            </a>
            @endforeach
        </div>
    </section>

    {{-- ===================== INCIDENCIAS + CCTs ===================== --}}
    <section class="tw:grid tw:grid-cols-1 tw:gap-4 tw:lg:grid-cols-3">

        <article class="{{ $card }} tw:overflow-hidden tw:lg:col-span-2">
            <header class="tw:flex tw:items-start tw:justify-between tw:gap-4 tw:px-6 tw:pt-6 tw:pb-4">
                <div>
                    <h2 class="{{ $panelTitle }}">Últimas incidencias de indisciplina</h2>
                    <p class="{{ $panelSubtitle }}">Seguimiento de conducta de los alumnos</p>
                </div>
                <a href="{{ route('reportes_indisciplina.index_view') }}"
                    class="tw:inline-flex tw:shrink-0 tw:items-center tw:gap-1 tw:px-2.5 tw:py-1.5 tw:text-[0.8125rem] tw:font-semibold tw:text-brand-700 tw:no-underline tw:transition-colors tw:hover:bg-brand-50 tw:hover:text-brand-800">
                    Ver todas <i data-lucide="arrow-right" class="tw:size-4"></i>
                </a>
            </header>
            <div class="tw:overflow-x-auto">
                <table class="tw:w-full tw:border-collapse tw:text-left">
                    <thead>
                        <tr class="tw:border-y tw:border-slate-200 tw:bg-slate-50">
                            @foreach (['Folio', 'Alumno', 'Incidente', 'Fecha'] as $th)
                            <th class="tw:px-6 tw:py-2.5 tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-500">{{ $th }}</th>
                            @endforeach
                            <th class="tw:px-6 tw:py-2.5 tw:text-center tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-500">Parcial</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-reportes-body" class="tw:divide-y tw:divide-slate-100">
                        <tr>
                            <td colspan="5" class="tw:px-6 tw:py-10 tw:text-center">
                                <span class="tw:inline-flex tw:items-center tw:gap-2 tw:text-[0.8125rem] tw:text-slate-500">
                                    <span class="tw:inline-block tw:size-4 tw:animate-spin tw:rounded-full tw:border-2 tw:border-slate-200 tw:border-t-brand-700"></span>
                                    Cargando incidencias…
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </article>

        <article class="{{ $card }} tw:p-6">
            <header class="tw:mb-4">
                <h2 class="{{ $panelTitle }}">Oferta educativa</h2>
                <p class="{{ $panelSubtitle }}">Centros de trabajo incorporados</p>
            </header>
            <ul class="tw:m-0 tw:flex tw:list-none tw:flex-col tw:gap-2.5 tw:p-0">
                @foreach ($ccts as $c)
                <li class="tw:group tw:flex tw:items-center tw:gap-3 tw:border tw:border-slate-200 tw:p-3 tw:transition-colors tw:hover:border-brand-200 tw:hover:bg-brand-50/50">
                    <span class="tw:inline-flex tw:size-10 tw:shrink-0 tw:items-center tw:justify-center tw:bg-slate-100 tw:text-slate-600 tw:transition-colors tw:group-hover:bg-white tw:group-hover:text-brand-700">
                        <i data-lucide="{{ $c['icon'] }}" class="tw:size-5"></i>
                    </span>
                    <div class="tw:min-w-0 tw:flex-1">
                        <p class="tw:m-0 tw:truncate tw:text-[0.8125rem] tw:font-semibold tw:text-slate-900" title="{{ $c['nombre'] }}">{{ $c['nombre'] }}</p>
                        <p class="tw:m-0 tw:mt-0.5 tw:text-xs tw:text-slate-500">CCT <span class="tw:font-mono tw:font-semibold tw:text-slate-700">{{ $c['clave'] }}</span></p>
                    </div>
                    <span class="{{ $chip }} tw:shrink-0 tw:ring-1 {{ $c['tone'] }}">{{ $c['modalidad'] }}</span>
                </li>
                @endforeach
            </ul>
        </article>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
// =========================================================
// LÓGICA DINÁMICA DEL DASHBOARD (AJAX + CHART.JS)
// =========================================================
const BRAND = { 700: '#26687B', 800: '#1F5565', success: '#10B981', successHover: '#059669', muted: '#CBD5E1', mutedHover: '#94A3B8' };
const NOMBRE_USUARIO = @json(session('nombre'));
let chartGruposInstance = null;
let chartEstatusInstance = null;

const esc = (v) => String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
const refrescarIconos = () => { if (window.lucide?.createIcons) lucide.createIcons(); };

document.addEventListener('DOMContentLoaded', function () {
    actualizarSaludoYFecha();
    cargarMetricasDashboard();
});

function actualizarSaludoYFecha() {
    const ahora = new Date();
    const hora = ahora.getHours();
    const saludo = (hora >= 5 && hora < 12) ? 'Buenos días' : (hora >= 12 && hora < 19) ? 'Buenas tardes' : 'Buenas noches';

    const titleEl = document.getElementById('greeting-title');
    if (titleEl) titleEl.textContent = `¡${saludo}${NOMBRE_USUARIO ? ', ' + NOMBRE_USUARIO : ''}!`;

    const fecha = ahora.toLocaleDateString('es-MX', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    const dateTextEl = document.getElementById('current-date-text');
    if (dateTextEl) dateTextEl.textContent = fecha.charAt(0).toUpperCase() + fecha.slice(1);
}

async function cargarMetricasDashboard() {
    const icon = document.getElementById('refreshIcon');
    const btn = document.getElementById('btnRefreshStats');
    icon?.classList.add('tw:animate-spin');
    if (btn) btn.disabled = true;

    try {
        const response = await fetch("{{ route('dashboard.stats') }}", {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (response.status === 401) {
            window.location.href = "{{ route('login') }}";
            return;
        }
        if (!response.ok) throw new Error(`HTTP error ${response.status}`);

        const data = await response.json();
        if (data.success && data.kpis) {
            actualizarKPIs(data.kpis);
            renderizarGraficoGrupos(data.charts ? data.charts.grupos : []);
            renderizarGraficoEstatus(data.charts ? data.charts.estatus_alumnos : null);
            renderizarReportesRecientes(data.reportes_recientes || []);
        }
    } catch (err) {
        console.error('Error al cargar métricas del dashboard:', err);
        ['kpi-activos', 'kpi-docentes', 'kpi-grupos', 'kpi-asistencia'].forEach(id => {
            const el = document.getElementById(id);
            if (el && el.querySelector('[data-skeleton]')) el.textContent = '0';
        });
        const tbody = document.getElementById('tabla-reportes-body');
        if (tbody && tbody.querySelector('.tw\\:animate-spin')) {
            tbody.innerHTML = filaMensaje('circle-alert', 'tw:text-danger-500', 'No fue posible cargar las incidencias. Intenta actualizar.');
            refrescarIconos();
        }
    } finally {
        setTimeout(() => icon?.classList.remove('tw:animate-spin'), 500);
        if (btn) btn.disabled = false;
    }
}

function animarNumero(idElemento, valorFinal, esPorcentaje = false) {
    const el = document.getElementById(idElemento);
    if (!el) return;

    const pasos = 25;
    const incremento = valorFinal / pasos;
    let actual = 0, contador = 0;

    const timer = setInterval(() => {
        contador++;
        actual += incremento;
        if (contador >= pasos) { actual = valorFinal; clearInterval(timer); }
        if (esPorcentaje) el.textContent = `${actual.toFixed(1)}%`;
        else if (Number.isInteger(valorFinal)) el.textContent = Math.round(actual).toLocaleString('es-MX');
        else el.textContent = actual.toFixed(2);
    }, 800 / pasos);
}

function actualizarKPIs(kpis) {
    animarNumero('kpi-activos', kpis.alumnos_activos || 0);
    animarNumero('kpi-docentes', kpis.total_docentes || 0);
    animarNumero('kpi-grupos', kpis.total_grupos || 0);
    animarNumero('kpi-asistencia', kpis.tasa_asistencia || 0, true);

    const set = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = (v || 0).toLocaleString('es-MX'); };
    set('kpi-certificados', kpis.alumnos_certificados);
    set('kpi-total-alumnos', kpis.total_alumnos);
    set('kpi-materias', kpis.total_materias);
    set('kpi-presentes', kpis.presentes);
    set('kpi-faltas', kpis.faltas);
    set('legend-activos', kpis.alumnos_activos);
    set('legend-certificados', kpis.alumnos_certificados);
    set('legend-inactivos', kpis.alumnos_inactivos);

    // Semáforo de asistencia
    const badge = document.getElementById('badge-asistencia-status');
    if (badge) {
        const tasa = Number(kpis.tasa_asistencia) || 0;
        const base = 'tw:inline-flex tw:items-center tw:gap-1 tw:px-2.5 tw:py-0.5 tw:text-[0.6875rem] tw:font-semibold';
        const [texto, tono] = tasa >= 90 ? ['Alta presencia', 'tw:bg-success-100 tw:text-success-700']
            : tasa >= 75 ? ['Presencia media', 'tw:bg-warning-100 tw:text-warning-600']
            : ['Presencia baja', 'tw:bg-danger-100 tw:text-danger-600'];
        badge.className = `${base} ${tono}`;
        badge.textContent = texto;
    }
}

const tooltipBase = {
    backgroundColor: 'rgba(15, 23, 42, 0.95)',
    padding: 10,
    cornerRadius: 8,
    displayColors: false,
};

function renderizarGraficoGrupos(grupos) {
    const canvas = document.getElementById('chartGrupos');
    if (!canvas) return;
    const emptyState = document.getElementById('chartGruposEmpty');

    if (!grupos || grupos.length === 0) {
        emptyState?.classList.remove('d-none');
        return;
    }
    emptyState?.classList.add('d-none');

    const topGrupos = grupos.slice(0, 8);
    chartGruposInstance?.destroy();

    chartGruposInstance = new Chart(canvas.getContext('2d'), {
        type: 'bar',
        data: {
            labels: topGrupos.map(g => g.clave),
            datasets: [{
                label: 'Alumnos asignados',
                data: topGrupos.map(g => g.total),
                backgroundColor: BRAND[700],
                hoverBackgroundColor: BRAND[800],
                borderWidth: 0,
                borderRadius: 8,
                borderSkipped: false,
                barPercentage: 0.55,
                categoryPercentage: 0.7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    ...tooltipBase,
                    titleFont: { family: "'JetBrains Mono', monospace", size: 12, weight: 'bold' },
                    bodyFont: { family: "'Inter', sans-serif", size: 12 },
                    callbacks: { label: (ctx) => ` ${ctx.parsed.y} alumno(s) inscritos` }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, color: '#64748B', font: { family: "'Inter', sans-serif", size: 11 } },
                    grid: { color: '#F1F5F9' },
                    border: { display: false }
                },
                x: {
                    ticks: { color: '#475569', font: { family: "'JetBrains Mono', monospace", size: 11, weight: '600' } },
                    grid: { display: false },
                    border: { color: '#E2E8F0' }
                }
            }
        }
    });
}

function renderizarGraficoEstatus(estatus) {
    const canvas = document.getElementById('chartEstatus');
    if (!canvas || !estatus) return;

    chartEstatusInstance?.destroy();
    const total = (estatus.activos || 0) + (estatus.certificados || 0) + (estatus.inactivos || 0);

    const centerTotalEl = document.getElementById('donut-center-total');
    if (centerTotalEl) centerTotalEl.textContent = (estatus.activos || 0).toLocaleString('es-MX');

    chartEstatusInstance = new Chart(canvas.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Activos', 'Certificados', 'Histórico'],
            datasets: [{
                data: [estatus.activos || 0, estatus.certificados || 0, estatus.inactivos || 0],
                backgroundColor: [BRAND[700], BRAND.success, BRAND.muted],
                hoverBackgroundColor: [BRAND[800], BRAND.successHover, BRAND.mutedHover],
                borderWidth: 3,
                borderColor: '#FFFFFF',
                hoverOffset: 4,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '74%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    ...tooltipBase,
                    titleFont: { family: "'Inter', sans-serif", size: 12, weight: 'bold' },
                    bodyFont: { family: "'Inter', sans-serif", size: 12 },
                    callbacks: {
                        label: (ctx) => {
                            const val = ctx.parsed;
                            const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                            return ` ${ctx.label}: ${val.toLocaleString('es-MX')} (${pct}%)`;
                        }
                    }
                }
            }
        }
    });
}

function filaMensaje(icono, color, texto) {
    return `
        <tr>
            <td colspan="5" class="tw:px-6 tw:py-10 tw:text-center">
                <span class="tw:inline-flex tw:flex-col tw:items-center tw:gap-2 tw:text-[0.8125rem] tw:text-slate-500">
                    <i data-lucide="${icono}" class="tw:size-6 ${color}"></i>
                    ${esc(texto)}
                </span>
            </td>
        </tr>`;
}

function renderizarReportesRecientes(reportes) {
    const tbody = document.getElementById('tabla-reportes-body');
    if (!tbody) return;

    if (!reportes || reportes.length === 0) {
        tbody.innerHTML = filaMensaje('circle-check', 'tw:text-success-700', 'No se registran incidencias recientes de indisciplina.');
        refrescarIconos();
        return;
    }

    tbody.innerHTML = reportes.map(r => `
        <tr class="tw:transition-colors tw:hover:bg-slate-50">
            <td class="tw:px-6 tw:py-3 tw:align-middle">
                <span class="tw:inline-flex tw:bg-slate-100 tw:px-2 tw:py-0.5 tw:font-mono tw:text-[0.6875rem] tw:font-semibold tw:text-slate-700">${esc(r.folio)}</span>
            </td>
            <td class="tw:px-6 tw:py-3 tw:align-middle">
                <div class="tw:text-xs tw:font-semibold tw:uppercase tw:text-slate-900">${esc(r.alumno_nombre || 'Alumno')}</div>
                <div class="tw:mt-0.5 tw:text-[0.6875rem] tw:text-slate-500">${r.tutor_nombre ? 'Tutor: ' + esc(r.tutor_nombre) : 'Sin tutor registrado'}</div>
            </td>
            <td class="tw:max-w-[240px] tw:truncate tw:px-6 tw:py-3 tw:align-middle tw:text-xs tw:text-slate-700" title="${esc(r.incidente)}">${esc(r.incidente)}</td>
            <td class="tw:whitespace-nowrap tw:px-6 tw:py-3 tw:align-middle tw:font-mono tw:text-xs tw:text-slate-600">${esc(r.fecha || '--')}</td>
            <td class="tw:px-6 tw:py-3 tw:text-center tw:align-middle">
                <span class="tw:inline-flex tw:rounded-none tw:bg-brand-50 tw:px-2.5 tw:py-0.5 tw:text-[0.6875rem] tw:font-bold tw:text-brand-700 tw:ring-1 tw:ring-brand-200">P${esc(r.parcial || 1)}</span>
            </td>
        </tr>`).join('');
}
</script>
@endsection
