@extends('layouts.app')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@php
    /*
     * NOTA: las secciones que el JS muestra/oculta usan el atributo `hidden`
     * (las utilidades de display de Tailwind son !important).
     */
    $card      = ' tw:border tw:border-slate-200 tw:bg-white tw:shadow-card';
    $label     = 'tw:mb-1.5 tw:block tw:text-[0.8125rem] tw:font-medium tw:text-slate-700';
    $input     = 'tw:h-10 tw:w-full tw:border tw:border-slate-200 tw:bg-white tw:px-3 tw:text-sm tw:text-slate-900 tw:shadow-xs tw:outline-none tw:transition tw:placeholder:text-slate-400 tw:hover:border-slate-300 tw:focus:border-brand-500 tw:focus:ring-4 tw:focus:ring-brand-500/10';
    $textarea  = 'tw:w-full tw:resize-y tw:border tw:border-slate-200 tw:bg-white tw:px-3 tw:py-2.5 tw:text-sm tw:leading-relaxed tw:text-slate-900 tw:shadow-xs tw:outline-none tw:transition tw:placeholder:text-slate-400 tw:hover:border-slate-300 tw:focus:border-brand-500 tw:focus:ring-4 tw:focus:ring-brand-500/10';
    $btnBase   = 'tw:inline-flex tw:cursor-pointer tw:items-center tw:justify-center tw:gap-2 tw:text-sm tw:font-semibold tw:transition-all tw:duration-150 tw:focus-visible:outline-2 tw:focus-visible:outline-offset-2 tw:focus-visible:outline-brand-500 tw:active:scale-[0.98] tw:disabled:cursor-not-allowed tw:disabled:opacity-60';
    $btnPrimary   = $btnBase . ' tw:h-10 tw:border-0 tw:bg-brand-700 tw:px-4 tw:text-white tw:shadow-brand tw:hover:bg-brand-800';
    $btnSecondary = $btnBase . ' tw:h-9 tw:border tw:border-slate-200 tw:bg-white tw:px-3 tw:text-slate-700 tw:shadow-xs tw:hover:bg-slate-50';
    $th   = 'tw:px-4 tw:py-2.5 tw:text-left tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-500';
    $searchWrap = 'tw:relative tw:w-full tw:sm:w-80';
    $search = 'tw:h-9 tw:w-full tw:border tw:border-slate-200 tw:bg-white tw:pr-3 tw:pl-9 tw:text-[0.8125rem] tw:text-slate-900 tw:shadow-xs tw:outline-none tw:transition tw:placeholder:text-slate-400 tw:focus:border-brand-500 tw:focus:ring-4 tw:focus:ring-brand-500/10';
    $sectionTitle = 'tw:m-0 tw:flex tw:items-center tw:gap-2 tw:text-[0.8125rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-500';
@endphp

<div class="tw:mx-auto tw:flex tw:max-w-[1600px] tw:flex-col tw:gap-6">

    {{-- ===================== ENCABEZADO ===================== --}}
    <header class="tw:flex tw:flex-col tw:gap-1">
        <h1 class="tw:m-0 tw:text-[1.5rem] tw:font-bold tw:tracking-tight tw:text-slate-900">Reportes de indisciplina</h1>
        <p class="tw:m-0 tw:text-sm tw:text-slate-500">Bachillerato Tecnológico Interamericano, Elige un grupo y después al alumno para registrar o consultar sus reportes.</p>
    </header>

    {{-- ===================== 1. GRUPOS ===================== --}}
    <section id="seccion-grupos" class="{{ $card }} tw:overflow-hidden">
        <div class="tw:flex tw:flex-col tw:gap-3 tw:border-b tw:border-slate-100 tw:px-5 tw:py-4 tw:sm:flex-row tw:sm:items-center tw:sm:justify-between">
            <div>
                <h2 class="tw:m-0 tw:text-base tw:font-semibold tw:text-slate-900">Grupos activos</h2>
                <p id="lbl-conteo-grupos" class="tw:m-0 tw:mt-0.5 tw:text-[0.8125rem] tw:text-slate-500">Cargando…</p>
            </div>
            <div class="{{ $searchWrap }}">
                <i data-lucide="search" class="tw:pointer-events-none tw:absolute tw:left-3 tw:top-1/2 tw:size-4 tw:-translate-y-1/2 tw:text-slate-400"></i>
                <input type="search" id="grupoSearch" class="{{ $search }}" placeholder="Buscar grupo, semestre o modalidad…" aria-label="Buscar grupo"
                    oninput="filtrarTabla(this.value, 'gruposTablaBody')">
            </div>
        </div>

        <div class="tw:overflow-x-auto">
            <table class="tw:w-full tw:min-w-[640px] tw:border-collapse">
                <thead class="tw:bg-slate-50">
                    <tr class="tw:border-b tw:border-slate-200">
                        <th class="{{ $th }} tw:pl-5">Grupo</th>
                        <th class="{{ $th }}">Semestre / nivel</th>
                        <th class="{{ $th }}">Modalidad / horario</th>
                        <th class="{{ $th }} tw:pr-5 tw:text-right"><span class="tw:sr-only">Acción</span></th>
                    </tr>
                </thead>
                <tbody id="gruposTablaBody" class="tw:divide-y tw:divide-slate-100"></tbody>
            </table>
        </div>
    </section>

    {{-- ===================== 2. ALUMNOS DEL GRUPO ===================== --}}
    <section id="seccion-alumnos" class="{{ $card }} tw:overflow-hidden" hidden>
        <div class="tw:flex tw:flex-col tw:gap-3 tw:border-b tw:border-slate-100 tw:px-5 tw:py-4 tw:sm:flex-row tw:sm:items-center tw:sm:justify-between">
            <div class="tw:flex tw:min-w-0 tw:items-center tw:gap-3">
                <button type="button" class="{{ $btnSecondary }} tw:w-9 tw:px-0" onclick="regresarAGrupos()" aria-label="Regresar a grupos" title="Regresar a grupos">
                    <i data-lucide="arrow-left" class="tw:size-4"></i>
                </button>
                <div class="tw:min-w-0">
                    <nav aria-label="Ruta" class="tw:flex tw:items-center tw:gap-1 tw:text-[0.75rem] tw:text-slate-500">
                        <button type="button" class="tw:cursor-pointer tw:border-0 tw:bg-transparent tw:p-0 tw:text-slate-500 tw:hover:text-brand-700 tw:hover:underline" onclick="regresarAGrupos()">Grupos</button>
                        <i data-lucide="chevron-right" class="tw:size-3"></i>
                        <span>Alumnos</span>
                    </nav>
                    <h2 class="tw:m-0 tw:flex tw:items-center tw:gap-2 tw:text-base tw:font-semibold tw:text-slate-900">
                        Grupo <span id="lbl-grupo-seleccionado" class="tw:font-mono tw:text-brand-700"></span>
                        <span id="lbl-conteo-alumnos" class="tw:text-[0.8125rem] tw:font-normal tw:text-slate-500"></span>
                    </h2>
                </div>
            </div>
            <div class="{{ $searchWrap }}">
                <i data-lucide="search" class="tw:pointer-events-none tw:absolute tw:left-3 tw:top-1/2 tw:size-4 tw:-translate-y-1/2 tw:text-slate-400"></i>
                <input type="search" id="alumnoSearch" class="{{ $search }}" placeholder="Buscar por nombre o matrícula…" aria-label="Buscar alumno"
                    oninput="filtrarTabla(this.value, 'alumnosTablaBody')">
            </div>
        </div>

        <div class="tw:overflow-x-auto">
            <table class="tw:w-full tw:min-w-[640px] tw:border-collapse">
                <thead class="tw:bg-slate-50">
                    <tr class="tw:border-b tw:border-slate-200">
                        <th class="{{ $th }} tw:pl-5">Alumno</th>
                        <th class="{{ $th }}">Matrícula</th>
                        <th class="{{ $th }}">Estatus</th>
                        <th class="{{ $th }} tw:pr-5 tw:text-right"><span class="tw:sr-only">Acción</span></th>
                    </tr>
                </thead>
                <tbody id="alumnosTablaBody" class="tw:divide-y tw:divide-slate-100"></tbody>
            </table>
        </div>
    </section>
</div>

{{-- ===================== MODAL: REPORTES DEL ALUMNO ===================== --}}
<div class="modal fade" id="modalReportesIndisciplina" tabindex="-1" aria-labelledby="modalReportesLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalReportesLabel"><i data-lucide="shield-alert" class="tw:size-5"></i> Reportes de indisciplina</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                {{-- Alumno seleccionado --}}
                <div class="tw:mb-5 tw:flex tw:items-center tw:gap-3 tw:border tw:border-slate-200 tw:bg-slate-50/60 tw:p-4">
                    <span id="lbl-rep-alumno-ini" class="tw:inline-flex tw:size-11 tw:shrink-0 tw:items-center tw:justify-center tw:bg-brand-50 tw:text-sm tw:font-semibold tw:text-brand-700 tw:ring-1 tw:ring-brand-200">—</span>
                    <div class="tw:min-w-0">
                        <p id="lbl-rep-alumno-nom" class="tw:m-0 tw:truncate tw:text-[0.9375rem] tw:font-semibold tw:text-slate-900">—</p>
                        <p class="tw:m-0 tw:mt-0.5 tw:text-[0.8125rem] tw:text-slate-500">
                            Matrícula <span id="lbl-rep-alumno-mat" class="tw:font-mono tw:text-slate-700">—</span>
                            <span class="tw:-ml-1 tw:text-slate-500">, </span>
                            Grupo <span id="lbl-rep-alumno-grupo" class="tw:font-mono tw:text-slate-700">—</span>
                        </p>
                    </div>
                </div>

                <div class="tw:grid tw:grid-cols-1 tw:gap-6 tw:lg:grid-cols-[minmax(0,5fr)_minmax(0,7fr)]">
                    {{-- Registrar reporte --}}
                    <div class="tw:lg:border-r tw:lg:border-slate-100 tw:lg:pr-6">
                        <h3 class="{{ $sectionTitle }} tw:mb-4"><i data-lucide="file-plus-2" class="tw:size-4"></i> Nuevo reporte</h3>
                        <form id="formAlumnoReporte" class="tw:flex tw:flex-col tw:gap-4" onsubmit="guardarReporteAlumno(event)">
                            <input type="hidden" id="rep_id_alumno">
                            <input type="hidden" id="rep_alumno_nombre">

                            <div>
                                <label for="rep_tutor_nombre" class="{{ $label }}">Nombre del tutor</label>
                                <input type="text" id="rep_tutor_nombre" class="{{ $input }}" placeholder="Nombre completo del padre, madre o tutor legal" autocomplete="off" required>
                            </div>

                            <div>
                                <span class="{{ $label }}" id="lbl-parcial">Parcial</span>
                                <div role="radiogroup" aria-labelledby="lbl-parcial" class="tw:grid tw:grid-cols-3 tw:gap-1 tw:bg-slate-100 tw:p-1">
                                    @foreach ([1, 2, 3] as $p)
                                        <label class="tw:cursor-pointer">
                                            <input type="radio" name="rep_parcial" value="{{ $p }}" class="tw:peer tw:sr-only" @checked($p === 1)>
                                            <span class="tw:flex tw:h-8 tw:items-center tw:justify-center tw:text-[0.8125rem] tw:font-medium tw:text-slate-600 tw:transition tw:peer-checked:bg-white tw:peer-checked:font-semibold tw:peer-checked:text-brand-700 tw:peer-checked:shadow-xs tw:peer-focus-visible:ring-2 tw:peer-focus-visible:ring-brand-500">Parcial {{ $p }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <div class="tw:flex tw:items-baseline tw:justify-between">
                                    <label for="rep_incidente" class="{{ $label }}">Descripción del incidente</label>
                                    <span id="rep_incidente_count" class="tw:text-[0.6875rem] tw:tabular-nums tw:text-slate-400">0 / 1000</span>
                                </div>
                                <textarea id="rep_incidente" class="{{ $textarea }}" rows="5" maxlength="1000" placeholder="Describe con claridad qué ocurrió, cuándo y dónde…" required
                                    oninput="document.getElementById('rep_incidente_count').textContent = this.value.length + ' / 1000'"></textarea>
                            </div>

                            <button type="submit" id="btnGuardarReporte" class="{{ $btnPrimary }} tw:w-full">
                                <i data-lucide="printer" class="tw:size-4"></i> Registrar e imprimir formato
                            </button>
                        </form>
                    </div>

                    {{-- Historial --}}
                    <div class="tw:min-w-0">
                        <div class="tw:mb-4 tw:flex tw:items-center tw:justify-between tw:gap-2">
                            <h3 class="{{ $sectionTitle }}"><i data-lucide="history" class="tw:size-4"></i> Historial</h3>
                            <div id="rep-resumen-parciales" class="tw:flex tw:flex-wrap tw:gap-1.5"></div>
                        </div>
                        <div class="tw:overflow-hidden tw:border tw:border-slate-200">
                            <div class="tw:overflow-x-auto">
                                <table class="tw:w-full tw:min-w-[520px] tw:border-collapse">
                                    <thead class="tw:bg-slate-50">
                                        <tr class="tw:border-b tw:border-slate-200">
                                            <th class="{{ $th }}">Folio</th>
                                            <th class="{{ $th }}">Parcial</th>
                                            <th class="{{ $th }}">Fecha</th>
                                            <th class="{{ $th }}">Tutor</th>
                                            <th class="{{ $th }} tw:text-right"><span class="tw:sr-only">Acciones</span></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabla-rep-alumno-historial" class="tw:divide-y tw:divide-slate-100"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const BRAND = '#26687B';
const DIRECTOR = 'ING. FAUSTO MAURO LEYVA FLORES';
const esc = (v) => String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
const refrescarIconos = () => { if (window.lucide?.createIcons) lucide.createIcons(); };
const normalizar = (s) => String(s ?? '').normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase();
const iniciales = (n) => String(n || '').trim().split(/\s+/).filter(Boolean).slice(0, 2).map(p => p[0].toUpperCase()).join('') || '—';
const mensajeError = (resp, fallback) => (resp && (resp.message || resp.error)) || fallback;

/** Fecha robusta: acepta "2026-11-13", "2026-11-13 10:00:00" o ISO. */
function parseFecha(f) {
    const m = String(f ?? '').match(/^(\d{4})-(\d{2})-(\d{2})/);
    if (m) return new Date(+m[1], +m[2] - 1, +m[3]);
    const d = new Date(f);
    return isNaN(d) ? null : d;
}
const fmtFecha = (f, opts) => { const d = parseFecha(f); return d ? d.toLocaleDateString('es-MX', opts) : '—'; };

async function fetchJson(url, options = {}) {
    const res = await fetch(url, { headers: { Accept: 'application/json' }, ...options });
    let data = null;
    try { data = await res.json(); } catch (e) { /* respuesta no JSON */ }
    if (!res.ok && !data) throw new Error(`HTTP ${res.status}`);
    if (!res.ok && data && data.success === undefined) data.success = false;
    return data;
}

const TONO = {
    success: 'tw:bg-success-100 tw:text-success-700',
    brand:   'tw:bg-brand-50 tw:text-brand-700 tw:ring-1 tw:ring-brand-200',
    warning: 'tw:bg-warning-100 tw:text-warning-600',
    danger:  'tw:bg-danger-100 tw:text-danger-600',
    slate:   'tw:bg-slate-100 tw:text-slate-600',
};
const badge = (texto, tono = 'slate') =>
    `<span class="tw:inline-flex tw:items-center tw:gap-1 tw:whitespace-nowrap tw:rounded-none tw:px-2.5 tw:py-0.5 tw:text-[0.6875rem] tw:font-semibold ${TONO[tono]}">${esc(texto)}</span>`;

const filaMensaje = (cols, icon, texto, tono = 'tw:text-slate-400', extra = '') => `
    <tr data-mensaje="1"><td colspan="${cols}">
        <div class="tw:flex tw:flex-col tw:items-center tw:px-6 tw:py-12 tw:text-center">
            <span class="tw:inline-flex tw:size-11 tw:items-center tw:justify-center tw:bg-slate-100 ${tono}"><i data-lucide="${icon}" class="tw:size-5"></i></span>
            <p class="tw:m-0 tw:mt-3 tw:text-[0.8125rem] tw:text-slate-500">${texto}</p>${extra}
        </div>
    </td></tr>`;
const filaCargando = (cols, texto) => `
    <tr data-mensaje="1"><td colspan="${cols}">
        <div class="tw:flex tw:flex-col tw:items-center tw:gap-3 tw:py-14 tw:text-[0.8125rem] tw:text-slate-500">
            <span class="tw:inline-block tw:size-6 tw:animate-spin tw:rounded-full tw:border-2 tw:border-slate-200 tw:border-t-brand-700"></span>${texto}
        </div>
    </td></tr>`;

const btnAccion = (icon, label, tono, attrs) => {
    const tonos = {
        brand:  'tw:text-brand-700 tw:hover:bg-brand-50 tw:hover:ring-brand-200',
        danger: 'tw:text-slate-500 tw:hover:bg-danger-50 tw:hover:text-danger-600 tw:hover:ring-danger-200',
    };
    return `<button type="button" ${attrs} aria-label="${label}" title="${label}"
        class="tw:inline-flex tw:size-8 tw:cursor-pointer tw:items-center tw:justify-center tw:border-0 tw:bg-transparent tw:ring-1 tw:ring-transparent tw:transition tw:focus-visible:outline-2 tw:focus-visible:outline-brand-500 ${tonos[tono]}">
        <i data-lucide="${icon}" class="tw:size-4"></i></button>`;
};

let grupoActual = null;
let alumnosGrupo = [];
let alumnoActual = null;
let historialActual = [];

document.addEventListener('DOMContentLoaded', () => {
    cargarGruposBTI();

    // Delegación de eventos: evita incrustar nombres/JSON dentro de atributos onclick
    document.getElementById('gruposTablaBody').addEventListener('click', (e) => {
        const tr = e.target.closest('tr[data-id]');
        if (tr) cargarAlumnosDelGrupo(tr.dataset.id, tr.dataset.clave);
    });
    document.getElementById('alumnosTablaBody').addEventListener('click', (e) => {
        const btn = e.target.closest('[data-idx]');
        if (btn) abrirReportesIndisciplinaAlumno(alumnosGrupo[+btn.dataset.idx]);
    });
    document.getElementById('tabla-rep-alumno-historial').addEventListener('click', (e) => {
        const btn = e.target.closest('[data-accion]');
        if (!btn) return;
        const rep = historialActual[+btn.dataset.rep];
        if (btn.dataset.accion === 'imprimir') imprimirFormatoReporteAlumno(rep);
        if (btn.dataset.accion === 'eliminar') eliminarReporteAlumno(rep.id, rep.id_alumno);
    });
});

// ==========================================
// GRUPOS (BTI: idCentroTrabajo = 2)
// ==========================================
async function cargarGruposBTI() {
    const tbody = document.getElementById('gruposTablaBody');
    const conteo = document.getElementById('lbl-conteo-grupos');
    tbody.innerHTML = filaCargando(4, 'Cargando grupos del BTI…');

    try {
        const resp = await fetchJson('/catalogos/grupos?idCentroTrabajo=2&statusGrupo=ACTIVO');
        const list = resp?.data || (Array.isArray(resp) ? resp : []);
        conteo.textContent = `${list.length} ${list.length === 1 ? 'grupo' : 'grupos'}`;

        if (list.length === 0) {
            tbody.innerHTML = filaMensaje(4, 'users-round', 'No se encontraron grupos activos para BTI.');
        } else {
            tbody.innerHTML = list.map(g => `
                <tr data-id="${esc(g.id)}" data-clave="${esc(g.clave)}" class="tw:cursor-pointer tw:transition-colors tw:hover:bg-slate-50">
                    <td class="tw:px-4 tw:py-3 tw:pl-5"><span class="tw:font-mono tw:text-sm tw:font-semibold tw:text-slate-900">${esc(g.clave)}</span></td>
                    <td class="tw:px-4 tw:py-3 tw:text-sm tw:text-slate-700">${esc(g.nombre_nivel || '—')}</td>
                    <td class="tw:px-4 tw:py-3">${badge(g.modalidadHorario || 'General', 'brand')}</td>
                    <td class="tw:px-4 tw:py-3 tw:pr-5 tw:text-right">
                        <button type="button" class="tw:inline-flex tw:cursor-pointer tw:items-center tw:gap-1.5 tw:border-0 tw:bg-transparent tw:px-2.5 tw:py-1.5 tw:text-[0.8125rem] tw:font-semibold tw:text-brand-700 tw:transition tw:hover:bg-brand-50">
                            Ver alumnos <i data-lucide="chevron-right" class="tw:size-4"></i>
                        </button>
                    </td>
                </tr>`).join('');
        }
    } catch (err) {
        console.error(err);
        conteo.textContent = '—';
        tbody.innerHTML = filaMensaje(4, 'circle-alert', 'No se pudieron cargar los grupos de BTI.', 'tw:text-danger-600',
            `<button type="button" class="tw:mt-3 tw:cursor-pointer tw:border-0 tw:bg-transparent tw:text-[0.8125rem] tw:font-semibold tw:text-brand-700 tw:hover:underline" onclick="cargarGruposBTI()">Reintentar</button>`);
    }
    filtrarTabla(document.getElementById('grupoSearch').value, 'gruposTablaBody');
    refrescarIconos();
}

// ==========================================
// ALUMNOS DEL GRUPO
// ==========================================
async function cargarAlumnosDelGrupo(idGrupo, claveGrupo) {
    grupoActual = { id: idGrupo, clave: claveGrupo };
    const tbody = document.getElementById('alumnosTablaBody');
    document.getElementById('lbl-grupo-seleccionado').textContent = claveGrupo;
    document.getElementById('lbl-conteo-alumnos').textContent = '';
    document.getElementById('alumnoSearch').value = '';
    tbody.innerHTML = filaCargando(4, 'Cargando alumnos del grupo…');

    document.getElementById('seccion-grupos').hidden = true;
    document.getElementById('seccion-alumnos').hidden = false;
    refrescarIconos();

    try {
        const resp = await fetchJson(`/alumnos/grupo/${encodeURIComponent(idGrupo)}`);
        alumnosGrupo = (resp?.data || []).map(a => ({
            ...a,
            nombreCompleto: `${a.nombre ?? ''} ${a.apPaterno ?? ''} ${a.apMaterno ?? ''}`.replace(/\s+/g, ' ').trim().toUpperCase(),
            matricula: a.numeroControl || a.idAlumno,
        }));
        document.getElementById('lbl-conteo-alumnos').textContent = `(${alumnosGrupo.length} ${alumnosGrupo.length === 1 ? 'alumno' : 'alumnos'})`;

        if (alumnosGrupo.length === 0) {
            tbody.innerHTML = filaMensaje(4, 'user-x', 'No hay alumnos asignados a este grupo.');
        } else {
            tbody.innerHTML = alumnosGrupo.map((a, i) => `
                <tr class="tw:transition-colors tw:hover:bg-slate-50">
                    <td class="tw:px-4 tw:py-3 tw:pl-5">
                        <div class="tw:flex tw:items-center tw:gap-3">
                            <span class="tw:inline-flex tw:size-8 tw:shrink-0 tw:items-center tw:justify-center tw:bg-slate-100 tw:text-[0.6875rem] tw:font-semibold tw:text-slate-600">${esc(iniciales(a.nombreCompleto))}</span>
                            <span class="tw:text-sm tw:font-medium tw:text-slate-900">${esc(a.nombreCompleto)}</span>
                        </div>
                    </td>
                    <td class="tw:px-4 tw:py-3 tw:font-mono tw:text-[0.8125rem] tw:text-slate-600">${esc(a.matricula)}</td>
                    <td class="tw:px-4 tw:py-3">${badge(a.statusAlumno || '—', a.statusAlumno === 'ACTIVO' ? 'success' : 'slate')}</td>
                    <td class="tw:px-4 tw:py-3 tw:pr-5 tw:text-right">
                        <button type="button" data-idx="${i}" class="tw:inline-flex tw:h-8 tw:cursor-pointer tw:items-center tw:gap-1.5 tw:border tw:border-slate-200 tw:bg-white tw:px-3 tw:text-[0.8125rem] tw:font-semibold tw:text-slate-700 tw:shadow-xs tw:transition tw:hover:border-brand-200 tw:hover:bg-brand-50 tw:hover:text-brand-700">
                            <i data-lucide="shield-alert" class="tw:size-4"></i> Reportes
                        </button>
                    </td>
                </tr>`).join('');
        }
    } catch (err) {
        console.error(err);
        tbody.innerHTML = filaMensaje(4, 'circle-alert', 'No se pudo cargar el listado de alumnos.', 'tw:text-danger-600');
    }
    refrescarIconos();
}

function regresarAGrupos() {
    document.getElementById('seccion-alumnos').hidden = true;
    document.getElementById('seccion-grupos').hidden = false;
}

/** Filtro cliente (sin acentos); muestra un aviso si nada coincide. */
function filtrarTabla(valor, tbodyId) {
    const tbody = document.getElementById(tbodyId);
    const q = normalizar(valor).trim();
    let visibles = 0, total = 0;

    tbody.querySelectorAll('tr').forEach(row => {
        if (row.dataset.mensaje || row.dataset.sinResultados) return;
        total++;
        const ok = !q || normalizar(row.textContent).includes(q);
        row.hidden = !ok;
        if (ok) visibles++;
    });

    tbody.querySelector('tr[data-sin-resultados]')?.remove();
    if (total > 0 && visibles === 0) {
        tbody.insertAdjacentHTML('beforeend',
            filaMensaje(4, 'search-x', `Sin resultados para “${esc(valor.trim())}”.`).replace('data-mensaje="1"', 'data-sin-resultados="1"'));
        refrescarIconos();
    }
}

// ==========================================
// MODAL: REPORTES DEL ALUMNO
// ==========================================
function abrirReportesIndisciplinaAlumno(alumno) {
    if (!alumno) return;
    alumnoActual = alumno;

    document.getElementById('rep_id_alumno').value = alumno.idAlumno;
    document.getElementById('rep_alumno_nombre').value = alumno.nombreCompleto;
    document.getElementById('lbl-rep-alumno-nom').textContent = alumno.nombreCompleto;
    document.getElementById('lbl-rep-alumno-ini').textContent = iniciales(alumno.nombreCompleto);
    document.getElementById('lbl-rep-alumno-mat').textContent = alumno.matricula;
    document.getElementById('lbl-rep-alumno-grupo').textContent = grupoActual?.clave || '—';

    document.getElementById('rep_tutor_nombre').value = alumno.tutor || '';
    document.getElementById('rep_incidente').value = '';
    document.getElementById('rep_incidente_count').textContent = '0 / 1000';
    document.querySelector('input[name="rep_parcial"][value="1"]').checked = true;

    cargarHistorialReportesAlumno(alumno.idAlumno);
    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalReportesIndisciplina')).show();
}

async function cargarHistorialReportesAlumno(idAlumno) {
    const tbody = document.getElementById('tabla-rep-alumno-historial');
    const resumen = document.getElementById('rep-resumen-parciales');
    resumen.innerHTML = '';
    tbody.innerHTML = filaCargando(5, 'Cargando historial…');

    try {
        const resp = await fetchJson(`/reportes-indisciplina?id_alumno=${encodeURIComponent(idAlumno)}`);
        if (!resp?.success) {
            tbody.innerHTML = filaMensaje(5, 'circle-alert', esc(mensajeError(resp, 'No se pudo cargar el historial.')), 'tw:text-danger-600');
            return refrescarIconos();
        }

        historialActual = resp.data || [];
        if (historialActual.length === 0) {
            tbody.innerHTML = filaMensaje(5, 'shield-check', 'Este alumno no tiene reportes registrados.', 'tw:text-success-700');
            return refrescarIconos();
        }

        // Resumen por parcial
        const porParcial = historialActual.reduce((acc, r) => { acc[r.parcial] = (acc[r.parcial] || 0) + 1; return acc; }, {});
        resumen.innerHTML = Object.keys(porParcial).sort().map(p =>
            badge(`P${p}: ${porParcial[p]}`, porParcial[p] >= 3 ? 'danger' : porParcial[p] === 2 ? 'warning' : 'slate')).join('');

        tbody.innerHTML = historialActual.map((rep, i) => `
            <tr class="tw:transition-colors tw:hover:bg-slate-50">
                <td class="tw:px-4 tw:py-2.5 tw:font-mono tw:text-[0.8125rem] tw:font-semibold tw:text-slate-900">${esc(rep.folio)}</td>
                <td class="tw:px-4 tw:py-2.5">${badge(`Parcial ${rep.parcial}`, 'brand')}</td>
                <td class="tw:px-4 tw:py-2.5 tw:whitespace-nowrap tw:text-[0.8125rem] tw:text-slate-600">${fmtFecha(rep.fecha, { day: '2-digit', month: 'short', year: 'numeric' })}</td>
                <td class="tw:max-w-[180px] tw:truncate tw:px-4 tw:py-2.5 tw:text-[0.8125rem] tw:text-slate-700" title="${esc(rep.tutor_nombre)}">${esc(rep.tutor_nombre || '—')}</td>
                <td class="tw:px-4 tw:py-2.5 tw:text-right">
                    <div class="tw:inline-flex tw:gap-1">
                        ${btnAccion('printer', 'Imprimir formato', 'brand', `data-accion="imprimir" data-rep="${i}"`)}
                        ${btnAccion('trash-2', 'Eliminar reporte', 'danger', `data-accion="eliminar" data-rep="${i}"`)}
                    </div>
                </td>
            </tr>`).join('');
    } catch (err) {
        console.error(err);
        tbody.innerHTML = filaMensaje(5, 'wifi-off', 'Error de red al cargar el historial.', 'tw:text-danger-600');
    }
    refrescarIconos();
}

async function guardarReporteAlumno(event) {
    event.preventDefault();
    const btn = document.getElementById('btnGuardarReporte');
    const idAlumno = document.getElementById('rep_id_alumno').value;
    const payload = {
        id_alumno: idAlumno,
        alumno_nombre: document.getElementById('rep_alumno_nombre').value,
        tutor_nombre: document.getElementById('rep_tutor_nombre').value.trim(),
        incidente: document.getElementById('rep_incidente').value.trim(),
        parcial: document.querySelector('input[name="rep_parcial"]:checked')?.value || '1',
    };

    btn.disabled = true;
    Swal.fire({ title: 'Registrando reporte…', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    try {
        const resp = await fetchJson('/reportes-indisciplina', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, Accept: 'application/json' },
            body: JSON.stringify(payload),
        });

        if (resp?.success) {
            await Swal.fire({ icon: 'success', title: 'Reporte registrado', text: `Folio ${resp.data?.folio ?? ''}. Se abrirá el formato para imprimir.`, confirmButtonColor: BRAND });
            document.getElementById('rep_incidente').value = '';
            document.getElementById('rep_incidente_count').textContent = '0 / 1000';
            cargarHistorialReportesAlumno(idAlumno);
            if (resp.data) imprimirFormatoReporteAlumno({ ...payload, ...resp.data });
        } else {
            Swal.fire({ icon: 'error', title: 'No se pudo guardar', text: mensajeError(resp, 'No se pudo guardar el reporte.'), confirmButtonColor: BRAND });
        }
    } catch (err) {
        console.error(err);
        Swal.fire({ icon: 'error', title: 'Error de red', text: 'Ocurrió un problema de comunicación con el servidor.', confirmButtonColor: BRAND });
    } finally {
        btn.disabled = false;
    }
}

async function eliminarReporteAlumno(idReporte, idAlumno) {
    const { isConfirmed } = await Swal.fire({
        title: '¿Eliminar reporte?',
        text: 'Esta acción no se puede deshacer y borrará el registro del historial.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#64748B',
        reverseButtons: true,
    });
    if (!isConfirmed) return;

    Swal.fire({ title: 'Eliminando…', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    try {
        const resp = await fetchJson(`/reportes-indisciplina/${encodeURIComponent(idReporte)}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, Accept: 'application/json' },
        });
        if (resp?.success) {
            Swal.fire({ icon: 'success', title: 'Reporte eliminado', timer: 1400, showConfirmButton: false });
            cargarHistorialReportesAlumno(idAlumno ?? alumnoActual?.idAlumno);
        } else {
            Swal.fire({ icon: 'error', title: 'Error', text: mensajeError(resp, 'No se pudo eliminar el reporte.'), confirmButtonColor: BRAND });
        }
    } catch (err) {
        console.error(err);
        Swal.fire({ icon: 'error', title: 'Error de red', text: 'Ocurrió un problema al comunicarse con el servidor.', confirmButtonColor: BRAND });
    }
}

// ==========================================
// FORMATO IMPRESO (carta, ahorro de tinta)
// ==========================================
function imprimirFormatoReporteAlumno(rep) {
    if (!rep) return;
    const fecha = fmtFecha(rep.fecha || new Date().toISOString(), { day: 'numeric', month: 'long', year: 'numeric' }).toUpperCase();
    const logo = `${location.origin}/img/logo.png`;
    const grupo = grupoActual?.clave || '';
    const campo = (v) => `<span class="campo">${esc(v || '')}</span>`;

    const win = window.open('', '_blank', 'height=900,width=820');
    if (!win) {
        Swal.fire({ icon: 'info', title: 'Ventana bloqueada', text: 'Permite las ventanas emergentes para imprimir el formato.', confirmButtonColor: BRAND });
        return;
    }

    win.document.write(`<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8">
<title>Reporte de indisciplina ${esc(rep.folio)}</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@600&display=swap" rel="stylesheet">
<style>
    @page { size: letter portrait; margin: 16mm 18mm; }
    * { box-sizing: border-box; }
    html, body { margin: 0; padding: 0; background: #fff; color: #0F172A; font-family: 'Inter', Arial, sans-serif; font-size: 10pt; line-height: 1.55; }
    .header { display: flex; align-items: center; justify-content: space-between; gap: 16px; border-bottom: 2px solid ${BRAND}; padding-bottom: 14px; margin-bottom: 18px; }
    .header-left { display: flex; align-items: center; gap: 14px; }
    .logo { height: 50px; width: auto; object-fit: contain; }
    .inst { margin: 0; font-size: 13pt; font-weight: 700; letter-spacing: -0.01em; text-transform: uppercase; }
    .dir { margin: 3px 0 0; font-size: 7.5pt; color: #475569; }
    .folio { text-align: right; font-size: 8pt; color: #475569; white-space: nowrap; }
    .folio strong { display: inline-block; margin-bottom: 4px; padding: 3px 9px; border: 1px solid ${BRAND}; border-radius: 0; color: ${BRAND}; font-family: 'JetBrains Mono', monospace; font-size: 9pt; }
    h1 { margin: 6px 0 20px; text-align: center; font-size: 12pt; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; }
    p { margin: 0 0 12px; text-align: justify; }
    .campo { display: inline-block; min-width: 55%; border-bottom: 1px solid #334155; padding: 0 6px; font-weight: 600; text-transform: uppercase; }
    .meta { display: flex; gap: 10px; margin: 0 0 16px; }
    .meta div { flex: 1; border: 1px solid #94A3B8; border-radius: 0; padding: 7px 11px; }
    .meta span { display: block; font-size: 7pt; font-weight: 600; color: #64748B; text-transform: uppercase; }
    .meta b { font-size: 9.5pt; text-transform: uppercase; }
    .incidente { border: 1px solid #94A3B8; border-radius: 0; padding: 12px 14px; min-height: 110px; white-space: pre-wrap; text-align: left; margin-bottom: 16px; }
    .nota { font-size: 9pt; font-style: italic; color: #334155; text-align: center; margin: 18px 0 0; }
    .firmas { display: flex; justify-content: space-between; align-items: flex-end; gap: 24px; margin-top: 56px; }
    .firma { flex: 1; text-align: center; }
    .firma .linea { border-top: 1px solid #0F172A; padding-top: 5px; font-size: 8pt; font-weight: 600; text-transform: uppercase; }
    .firma .rol { font-size: 7.5pt; color: #64748B; text-transform: uppercase; }
    .sello { width: 120px; height: 80px; border: 1px dashed #94A3B8; border-radius: 0; display: flex; align-items: center; justify-content: center; font-size: 7.5pt; color: #94A3B8; letter-spacing: 0.1em; text-transform: uppercase; }
</style></head>
<body>
    <div class="header">
        <div class="header-left">
            <img src="${logo}" class="logo" alt="">
            <div>
                <p class="inst">Bachillerato Tecnológico Interamericano</p>
                <p class="dir">Avenida Benito Juárez 901, Colonia Centro, Teziutlán, Puebla · Tel. 231-312-3979</p>
            </div>
        </div>
        <div class="folio"><strong>${esc(rep.folio || '—')}</strong><br>${fecha}</div>
    </div>

    <h1>Reporte por indisciplina</h1>

    <div class="meta">
        <div><span>Grupo</span><b>${esc(grupo || '—')}</b></div>
        <div><span>Parcial</span><b>${esc(rep.parcial ? rep.parcial + '° parcial' : '—')}</b></div>
        <div><span>Fecha</span><b>${fecha}</b></div>
    </div>

    <p><strong>Sr.(a):</strong> ${campo(rep.tutor_nombre || 'S/N')}</p>
    <p>El que suscribe, <strong>${DIRECTOR}</strong>, director del Bachillerato Tecnológico Interamericano, por este conducto le informa que el(la) alumno(a):</p>
    <p>${campo(rep.alumno_nombre)}</p>
    <p>incurrió en una falta de indisciplina, ya que:</p>
    <div class="incidente">${esc(rep.incidente)}</div>

    <p class="nota">Esperamos su valiosa contribución para que su hijo(a) mejore su comportamiento y nos ayude a sacarlo adelante en su educación, formándolo como un buen ciudadano.</p>

    <div class="firmas">
        <div class="firma"><div class="linea">&nbsp;</div><div class="rol">Nombre y firma del alumno</div></div>
        <div class="sello">Sello</div>
        <div class="firma"><div class="linea">${DIRECTOR}</div><div class="rol">Director</div></div>
    </div>
</body></html>`);
    win.document.close();

    // Imprimir cuando el logo y las fuentes estén listos (antes se imprimía con un timeout fijo)
    const imprimir = () => { win.focus(); win.print(); };
    win.onafterprint = () => win.close();
    const img = win.document.querySelector('.logo');
    const listo = Promise.all([
        win.document.fonts ? win.document.fonts.ready : Promise.resolve(),
        img.complete ? Promise.resolve() : new Promise(r => { img.onload = img.onerror = r; }),
    ]);
    Promise.race([listo, new Promise(r => setTimeout(r, 2500))]).then(imprimir);
}
</script>
@endsection
