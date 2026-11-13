@extends('layouts.app')

@section('content')
@php
    $card = ' tw:border tw:border-slate-200 tw:bg-white tw:shadow-card';
    $meta = 'tw:flex tw:min-w-0 tw:items-center tw:gap-2 tw:text-[0.8125rem] tw:text-slate-600';
    $metaIcon = 'tw:size-4 tw:shrink-0 tw:text-slate-400';
    $th = 'tw:border-b tw:border-slate-200 tw:bg-slate-50 tw:px-3 tw:py-2.5 tw:text-center tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-500';
@endphp

<div class="tw:mx-auto tw:flex tw:max-w-[1600px] tw:flex-col tw:gap-6">

    {{-- ===================== ENCABEZADO + BUSCADOR ===================== --}}
    <header class="tw:flex tw:flex-col tw:gap-4 tw:lg:flex-row tw:lg:items-center tw:lg:justify-between">
        <div class="tw:flex tw:items-start tw:gap-4">
            <a href="{{ route('docentes') }}" id="btn-regresar-docentes" aria-label="Volver al listado de docentes" title="Listado de docentes"
                class="tw:mt-0.5 tw:inline-flex tw:size-10 tw:shrink-0 tw:items-center tw:justify-center tw:border tw:border-slate-200 tw:bg-white tw:text-slate-500 tw:no-underline tw:shadow-xs tw:transition-colors tw:hover:bg-slate-50 tw:hover:text-slate-900">
                <i data-lucide="arrow-left" class="tw:size-[18px]"></i>
            </a>
            <div>
                <h1 class="tw:m-0 tw:text-[1.5rem] tw:font-bold tw:tracking-tight tw:text-slate-900">Horario docente</h1>
                <p class="tw:m-0 tw:mt-1 tw:text-sm tw:text-slate-500">Carga horaria consolidada en BTI y BGNE.</p>
            </div>
        </div>

        {{-- Buscador (se oculta para el rol DOCENTE) --}}
        <div id="docente-search-wrap" class="tw:relative tw:w-full tw:lg:w-[380px]">
            <label for="docente-search" class="tw:sr-only">Buscar docente</label>
            <i data-lucide="search" class="tw:pointer-events-none tw:absolute tw:left-3.5 tw:top-1/2 tw:size-[18px] tw:-translate-y-1/2 tw:text-slate-400"></i>
            <input type="text" id="docente-search" autocomplete="off" placeholder="Buscar docente por nombre, correo o ID…"
                class="tw:h-11 tw:w-full tw:border tw:border-slate-200 tw:bg-white tw:pr-3 tw:pl-11 tw:text-sm tw:text-slate-900 tw:shadow-xs tw:outline-none tw:transition tw:placeholder:text-slate-400 tw:hover:border-slate-300 tw:focus:border-brand-500 tw:focus:ring-4 tw:focus:ring-brand-500/10">
            {{-- Bootstrap .d-none controla la visibilidad --}}
            <div id="search-results" class="d-none tw:absolute tw:inset-x-0 tw:top-full tw:z-30 tw:mt-1.5 tw:max-h-72 tw:overflow-y-auto tw:border tw:border-slate-200 tw:bg-white tw:p-1 tw:shadow-raised"></div>
        </div>
    </header>

    {{-- ===================== ESTADO VACÍO ===================== --}}
    <div id="pantalla-vacia" class="{{ $card }}">
        <div class="tw:flex tw:flex-col tw:items-center tw:px-6 tw:py-16 tw:text-center">
            <span class="tw:inline-flex tw:size-14 tw:items-center tw:justify-center tw:bg-brand-50 tw:text-brand-700 tw:ring-1 tw:ring-brand-200">
                <i data-lucide="calendar-search" class="tw:size-6"></i>
            </span>
            <p class="tw:m-0 tw:mt-4 tw:text-base tw:font-semibold tw:text-slate-900">Consulta el horario de un docente</p>
            <p class="tw:m-0 tw:mt-1 tw:max-w-md tw:text-[0.8125rem] tw:text-slate-500">Búscalo en el campo superior para ver su carga horaria semanal y descargar el reporte oficial.</p>
        </div>
    </div>

    {{-- ===================== PANEL DEL DOCENTE (el JS controla su display) ===================== --}}
    <div id="panel-horario" style="display: none;">
        <div class="tw:flex tw:flex-col tw:gap-6">

            {{-- Ficha del docente --}}
            <section class="{{ $card }} tw:overflow-hidden">
                <div class="tw:flex tw:flex-col tw:gap-6 tw:p-6 tw:xl:flex-row tw:xl:items-center tw:xl:justify-between">

                    {{-- Identidad --}}
                    <div class="tw:flex tw:min-w-0 tw:items-start tw:gap-4">
                        <span id="docente-iniciales" class="tw:inline-flex tw:size-14 tw:shrink-0 tw:items-center tw:justify-center tw:bg-gradient-to-br tw:from-brand-500 tw:to-brand-700 tw:text-lg tw:font-semibold tw:text-white tw:shadow-brand">—</span>
                        <div class="tw:min-w-0">
                            <div class="tw:flex tw:flex-wrap tw:items-center tw:gap-2">
                                <h2 id="docente-nombre" class="tw:m-0 tw:text-lg tw:font-semibold tw:tracking-tight tw:text-slate-900">—</h2>
                                <span id="docente-estatus" class="tw:inline-flex tw:items-center tw:rounded-none tw:bg-success-100 tw:px-2.5 tw:py-0.5 tw:text-[0.6875rem] tw:font-semibold tw:text-success-700">ACTIVO</span>
                            </div>
                            <div class="tw:mt-3 tw:grid tw:grid-cols-1 tw:gap-x-6 tw:gap-y-2 tw:sm:grid-cols-2">
                                <span class="{{ $meta }}" title="Correo electrónico"><i data-lucide="mail" class="{{ $metaIcon }}"></i><span id="docente-correo" class="tw:truncate">—</span></span>
                                <span class="{{ $meta }}" title="Teléfono"><i data-lucide="phone" class="{{ $metaIcon }}"></i><span id="docente-telefono">—</span></span>
                                <span class="{{ $meta }}" title="Nivel de estudios"><i data-lucide="graduation-cap" class="{{ $metaIcon }}"></i><span id="docente-estudios">—</span></span>
                                <span class="{{ $meta }}" title="ID biométrico"><i data-lucide="fingerprint" class="{{ $metaIcon }}"></i><span>ID biométrico <strong id="docente-biometrico" class="tw:font-mono tw:font-semibold tw:text-slate-700">—</strong></span></span>
                            </div>
                        </div>
                    </div>

                    {{-- Carga + acción --}}
                    <div class="tw:flex tw:flex-wrap tw:items-stretch tw:gap-3 tw:xl:shrink-0">
                        <div class="tw:min-w-[96px] tw:border tw:border-slate-200 tw:bg-slate-50 tw:px-4 tw:py-3">
                            <span class="tw:flex tw:items-center tw:gap-1.5 tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-500"><span class="tw:size-2 tw:rounded-full tw:bg-brand-700"></span>BTI</span>
                            <span id="resumen-bti" class="tw:mt-1 tw:block tw:text-xl tw:font-bold tw:tabular-nums tw:text-slate-900">0h</span>
                        </div>
                        <div class="tw:min-w-[96px] tw:border tw:border-slate-200 tw:bg-slate-50 tw:px-4 tw:py-3">
                            <span class="tw:flex tw:items-center tw:gap-1.5 tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-500"><span class="tw:size-2 tw:rounded-full tw:bg-accent-400"></span>BGNE</span>
                            <span id="resumen-bgne" class="tw:mt-1 tw:block tw:text-xl tw:font-bold tw:tabular-nums tw:text-slate-900">0h</span>
                        </div>
                        <div class="tw:min-w-[120px] tw:bg-gradient-to-br tw:from-brand-600 tw:to-brand-800 tw:px-4 tw:py-3 tw:text-white tw:shadow-brand">
                            <span class="tw:block tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-white/70">Total semanal</span>
                            <span id="resumen-total" class="tw:mt-1 tw:block tw:text-xl tw:font-bold tw:tabular-nums">0h</span>
                        </div>
                        <button type="button" onclick="imprimirReporteHorario()"
                            class="tw:inline-flex tw:cursor-pointer tw:items-center tw:justify-center tw:gap-2 tw:self-center tw:border tw:border-slate-200 tw:bg-white tw:px-4 tw:py-2.5 tw:text-sm tw:font-semibold tw:text-slate-700 tw:shadow-xs tw:transition-colors tw:hover:border-brand-200 tw:hover:bg-brand-50 tw:hover:text-brand-700">
                            <i data-lucide="printer" class="tw:size-4"></i> Imprimir reporte oficial
                        </button>
                    </div>
                </div>
            </section>

            {{-- Calendarios --}}
            <div class="tw:grid tw:grid-cols-1 tw:items-start tw:gap-6 tw:2xl:grid-cols-[minmax(0,5fr)_minmax(0,2.4fr)]">

                {{-- BTI --}}
                <section class="{{ $card }} tw:min-w-0 tw:overflow-hidden">
                    <header class="tw:flex tw:flex-wrap tw:items-center tw:justify-between tw:gap-2 tw:border-b tw:border-slate-100 tw:px-6 tw:py-4">
                        <div class="tw:flex tw:items-center tw:gap-3">
                            <span class="tw:inline-flex tw:size-9 tw:items-center tw:justify-center tw:bg-brand-50 tw:text-brand-700"><i data-lucide="school" class="tw:size-[18px]"></i></span>
                            <div>
                                <h3 class="tw:m-0 tw:text-[0.9375rem] tw:font-semibold tw:text-slate-900">BTI, Escolarizado</h3>
                                <p class="tw:m-0 tw:text-xs tw:text-slate-500">Lunes a viernes</p>
                            </div>
                        </div>
                        <span class=" tw:bg-slate-100 tw:px-2 tw:py-0.5 tw:font-mono tw:text-[0.6875rem] tw:font-semibold tw:text-slate-600">CCT 21PCT0073R</span>
                    </header>
                    <div class="tw:overflow-x-auto tw:p-4">
                        {{-- El JS alterna el display de la tabla: sin utilidades de display --}}
                        <table class="tw:w-full tw:min-w-[720px] tw:table-fixed tw:border-separate tw:border-spacing-0 tw:overflow-hidden tw:border tw:border-slate-200">
                            <thead>
                                <tr>
                                    <th class="{{ $th }} tw:w-[104px]">Hora</th>
                                    @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'] as $dia)
                                        <th class="{{ $th }} tw:border-l">{{ $dia }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody id="bti-tbody"></tbody>
                        </table>
                        <div id="bti-no-schedule" style="display: none;">
                            <div class="tw:flex tw:flex-col tw:items-center tw:border tw:border-dashed tw:border-slate-200 tw:bg-slate-50/60 tw:px-6 tw:py-10 tw:text-center">
                                <i data-lucide="calendar-x" class="tw:size-6 tw:text-slate-400"></i>
                                <p class="tw:m-0 tw:mt-2 tw:text-[0.8125rem] tw:text-slate-500">Sin materias asignadas en BTI.</p>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- BGNE --}}
                <section class="{{ $card }} tw:min-w-0 tw:overflow-hidden">
                    <header class="tw:flex tw:flex-wrap tw:items-center tw:justify-between tw:gap-2 tw:border-b tw:border-slate-100 tw:px-6 tw:py-4">
                        <div class="tw:flex tw:items-center tw:gap-3">
                            <span class="tw:inline-flex tw:size-9 tw:items-center tw:justify-center tw:bg-accent-100 tw:text-accent-600"><i data-lucide="landmark" class="tw:size-[18px]"></i></span>
                            <div>
                                <h3 class="tw:m-0 tw:text-[0.9375rem] tw:font-semibold tw:text-slate-900">BGNE, Fin de semana</h3>
                                <p class="tw:m-0 tw:text-xs tw:text-slate-500">Sábado y domingo</p>
                            </div>
                        </div>
                        <span class=" tw:bg-slate-100 tw:px-2 tw:py-0.5 tw:font-mono tw:text-[0.6875rem] tw:font-semibold tw:text-slate-600">CCT 21PBH0353G</span>
                    </header>
                    <div class="tw:overflow-x-auto tw:p-4">
                        <table id="bgne-table" class="tw:w-full tw:min-w-[380px] tw:table-fixed tw:border-separate tw:border-spacing-0 tw:overflow-hidden tw:border tw:border-slate-200">
                            <thead>
                                <tr>
                                    <th class="{{ $th }} tw:w-[104px]">Hora</th>
                                    <th class="{{ $th }} tw:border-l">Sábado</th>
                                    <th class="{{ $th }} tw:border-l">Domingo</th>
                                </tr>
                            </thead>
                            <tbody id="bgne-tbody"></tbody>
                        </table>
                        <div id="bgne-no-schedule" style="display: none;">
                            <div class="tw:flex tw:flex-col tw:items-center tw:border tw:border-dashed tw:border-slate-200 tw:bg-slate-50/60 tw:px-6 tw:py-10 tw:text-center">
                                <i data-lucide="calendar-x" class="tw:size-6 tw:text-slate-400"></i>
                                <p class="tw:m-0 tw:mt-2 tw:text-[0.8125rem] tw:text-slate-500">Sin materias asignadas en BGNE.</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<script>
const loggedInDocenteId = @json(session('id_docente'));
const isDocenteRole = @json(session('rol') === 'DOCENTE');
let selectDocente = null;
let docentesMap = {};
let horarioActual = [];
let docenteActual = null;

const esc = (v) => String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

// Estructura fija de horas BTI
const BTI_HOURS = [
    { start: "07:30", end: "08:20", type: "class" },
    { start: "08:20", end: "09:10", type: "class" },
    { start: "09:10", end: "10:00", type: "class" },
    { start: "10:00", end: "10:30", type: "receso" },
    { start: "10:30", end: "11:20", type: "class" },
    { start: "11:20", end: "12:10", type: "class" },
    { start: "12:10", end: "13:00", type: "class" },
    { start: "13:00", end: "13:50", type: "class" }
];

/*
 * Clases de las celdas. Se conservan los nombres legacy (time-col, receso-row, class-block,
 * class-materia, class-grupo, class-aula) porque el reporte oficial impreso copia este HTML
 * y los estiliza con su propia hoja; las utilidades tw: solo aplican en pantalla.
 */
const CELL = {
    time: 'time-col tw:border-t tw:border-slate-100 tw:bg-slate-50/70 tw:px-3 tw:py-2 tw:text-center tw:align-middle tw:font-mono tw:text-[0.6875rem] tw:font-semibold tw:whitespace-nowrap tw:text-slate-500',
    day: 'tw:h-[64px] tw:border-t tw:border-l tw:border-slate-100 tw:p-1.5 tw:align-top',
    receso: 'tw:border-t tw:border-slate-100 tw:bg-[repeating-linear-gradient(135deg,#F8FAFC_0,#F8FAFC_6px,#F1F5F9_6px,#F1F5F9_12px)] tw:py-1.5 tw:text-center tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.2em] tw:text-slate-400',
};
const BLOCK_TONE = {
    bti: 'tw:border-l-brand-700 tw:bg-brand-50 tw:hover:bg-brand-100',
    bgne: 'tw:border-l-accent-600 tw:bg-accent-100/70 tw:hover:bg-accent-100',
};

function bloqueClase(mc, tono) {
    const grupo = `${mc.grupo_clave ?? ''}${mc.nivel_nombre ? ` (${mc.nivel_nombre})` : ''}`;
    return `
        <div class="class-block tw:mb-1 tw:border-l-[3px] tw:px-2.5 tw:py-1.5 tw:transition-colors tw:last:mb-0 ${BLOCK_TONE[tono]}" title="${esc(mc.materia_nombre)}, ${esc(grupo)}, ${esc(mc.aula)}">
            <div class="class-materia tw:truncate tw:text-xs tw:font-semibold tw:leading-snug tw:text-slate-900">${esc(mc.materia_nombre)}</div>
            <div class="tw:mt-0.5 tw:flex tw:items-center tw:justify-between tw:gap-2 tw:text-[0.6875rem]">
                <span class="class-grupo tw:truncate tw:font-semibold tw:text-brand-700">${esc(grupo)}</span>
                <span class="class-aula tw:shrink-0 tw:text-slate-500">${esc(mc.aula)}</span>
            </div>
        </div>`;
}

document.addEventListener("DOMContentLoaded", function() {
    if (isDocenteRole && loggedInDocenteId) {
        // Ocultar buscador y botón de regreso para el rol docente
        const searchContainer = document.getElementById("docente-search-wrap");
        if (searchContainer) searchContainer.remove();
        const returnBtn = document.getElementById("btn-regresar-docentes");
        if (returnBtn) returnBtn.remove();

        fetch(`/docentes/${loggedInDocenteId}`)
            .then(res => res.json())
            .then(resp => {
                if (resp.success && resp.data) {
                    const d = resp.data;
                    docentesMap[d.idDocente] = {
                        ...d,
                        nombreCompleto: `${d.nombreDocente} ${d.apPaternoDocente ?? ''} ${d.apMaternoDocente ?? ''}`.trim()
                    };
                    seleccionarDocente(d.idDocente);
                }
            })
            .catch(err => console.error("Error al cargar datos del docente:", err));
    } else {
        cargarListaDocentes();
    }
});

function cargarListaDocentes() {
    fetch('/docentes/lista')
        .then(res => res.json())
        .then(data => {
            const list = Array.isArray(data.data) ? data.data : [];
            list.forEach(d => {
                docentesMap[d.idDocente] = {
                    ...d,
                    nombreCompleto: `${d.nombreDocente} ${d.apPaternoDocente ?? ''} ${d.apMaternoDocente ?? ''}`.trim()
                };
            });

            const searchInput = document.getElementById("docente-search");
            const searchResults = document.getElementById("search-results");
            const normalizeStr = str => (str || '').normalize("NFD").replace(/[̀-ͯ]/g, "").toLowerCase();

            searchInput.addEventListener("input", function() {
                const query = normalizeStr(this.value).trim();
                if (query.length === 0) {
                    resetPantalla();
                    searchResults.classList.add("d-none");
                    return;
                }
                if (query.length < 2) {
                    searchResults.classList.add("d-none");
                    return;
                }

                const matches = Object.values(docentesMap).filter(d =>
                    normalizeStr(d.nombreCompleto).includes(query) ||
                    normalizeStr(d.correoDocente).includes(query) ||
                    d.idDocente.toString().includes(query)
                );

                searchResults.innerHTML = matches.length === 0
                    ? '<div class="tw:px-3 tw:py-4 tw:text-center tw:text-[0.8125rem] tw:text-slate-500">No se encontraron docentes</div>'
                    : matches.map(d => `
                        <button type="button" onclick="seleccionarDocenteAutocompletar(${Number(d.idDocente)})"
                            class="tw:flex tw:w-full tw:cursor-pointer tw:items-center tw:gap-3 tw:border-0 tw:bg-transparent tw:px-3 tw:py-2 tw:text-left tw:transition-colors tw:hover:bg-brand-50">
                            <span class="tw:inline-flex tw:size-8 tw:shrink-0 tw:items-center tw:justify-center tw:bg-slate-100 tw:text-[0.6875rem] tw:font-semibold tw:text-slate-600">${esc(iniciales(d.nombreCompleto))}</span>
                            <span class="tw:min-w-0">
                                <span class="tw:block tw:truncate tw:text-[0.8125rem] tw:font-semibold tw:text-slate-900">${esc(d.nombreCompleto)}</span>
                                <span class="tw:block tw:truncate tw:text-[0.6875rem] tw:text-slate-500">ID ${esc(d.idDocente)}, ${esc(d.correoDocente || 'Sin correo')}</span>
                            </span>
                        </button>`).join("");
                searchResults.classList.remove("d-none");
            });

            // Cerrar resultados al hacer clic fuera
            document.addEventListener("click", function(e) {
                if (!searchResults.contains(e.target) && e.target !== searchInput) {
                    searchResults.classList.add("d-none");
                }
            });

            window.seleccionarDocenteAutocompletar = function(id) {
                const d = docentesMap[id];
                if (d) {
                    searchInput.value = d.nombreCompleto;
                    searchResults.classList.add("d-none");
                    seleccionarDocente(id);
                }
            };
        })
        .catch(err => console.error("Error al cargar listado de docentes:", err));
}

function iniciales(nombre) {
    return String(nombre || '').trim().split(/\s+/).filter(Boolean).slice(0, 2).map(p => p[0].toUpperCase()).join('') || '—';
}

function seleccionarDocente(id) {
    docenteActual = docentesMap[id];

    document.getElementById("docente-nombre").textContent = docenteActual.nombreCompleto;
    document.getElementById("docente-iniciales").textContent = iniciales(docenteActual.nombreCompleto);
    document.getElementById("docente-biometrico").textContent = docenteActual.idBiometrico || "Sin ID";
    document.getElementById("docente-correo").textContent = docenteActual.correoDocente || "Sin correo";
    document.getElementById("docente-estudios").textContent = docenteActual.nivelEstudios || "Sin nivel registrado";
    document.getElementById("docente-telefono").textContent = docenteActual.telefonoDocente || "Sin teléfono";

    const est = document.getElementById("docente-estatus");
    const activo = docenteActual.statusDocente === "ACTIVO";
    est.textContent = docenteActual.statusDocente || "—";
    est.className = 'tw:inline-flex tw:items-center tw:px-2.5 tw:py-0.5 tw:text-[0.6875rem] tw:font-semibold ' +
        (activo ? 'tw:bg-success-100 tw:text-success-700' : 'tw:bg-danger-100 tw:text-danger-600');

    cargarHorariosServicio(id);
}

function resetPantalla() {
    docenteActual = null;
    horarioActual = [];
    document.getElementById("panel-horario").style.display = "none";
    document.getElementById("pantalla-vacia").style.display = "block";
}

function formatTimeHM(timeStr) {
    if (!timeStr) return "";
    const parts = timeStr.split(":");
    return `${parts[0].padStart(2, "0")}:${parts[1].padStart(2, "0")}`;
}

function cargarHorariosServicio(idDocente) {
    fetch(`/docentes/${idDocente}/horario`)
        .then(res => res.json())
        .then(horarios => {
            horarioActual = Array.isArray(horarios) ? horarios : [];
            renderCalendars();
            document.getElementById("pantalla-vacia").style.display = "none";
            document.getElementById("panel-horario").style.display = "block";
        })
        .catch(err => console.error("Error al cargar el horario del docente:", err));
}

function renderCalendars() {
    const btiClasses = [];
    const bgneClasses = [];

    horarioActual.forEach(c => {
        c.horaInicioFormatted = formatTimeHM(c.horaInicio);
        c.horaFinFormatted = formatTimeHM(c.horaFin);
        if (c.id_centroTrabajo === 2 || c.diaSemana <= 5) btiClasses.push(c);
        else if (c.id_centroTrabajo === 3 || c.diaSemana >= 6) bgneClasses.push(c);
    });

    document.getElementById("resumen-bti").textContent = `${btiClasses.length}h`;
    document.getElementById("resumen-bgne").textContent = `${bgneClasses.length}h`;
    document.getElementById("resumen-total").textContent = `${btiClasses.length + bgneClasses.length}h`;

    // 1. BTI (Lunes=1 … Viernes=5)
    const btiBody = document.getElementById("bti-tbody");
    if (btiClasses.length > 0) {
        document.getElementById("bti-no-schedule").style.display = "none";
        btiBody.closest("table").style.display = "table";

        btiBody.innerHTML = BTI_HOURS.map(block => {
            if (block.type === "receso") {
                return `<tr class="receso-row">
                    <td class="${CELL.time}">${block.start} - ${block.end}</td>
                    <td colspan="5" class="${CELL.receso}">Receso</td>
                </tr>`;
            }
            let row = `<tr><td class="${CELL.time}">${block.start} - ${block.end}</td>`;
            for (let day = 1; day <= 5; day++) {
                const clases = btiClasses.filter(c => parseInt(c.diaSemana) === day && c.horaInicioFormatted === block.start);
                row += `<td class="${CELL.day}">${clases.map(mc => bloqueClase(mc, 'bti')).join('')}</td>`;
            }
            return row + `</tr>`;
        }).join('');
    } else {
        btiBody.innerHTML = "";
        btiBody.closest("table").style.display = "none";
        document.getElementById("bti-no-schedule").style.display = "block";
    }

    // 2. BGNE (Sábado=6, Domingo=7) con intervalos dinámicos
    const bgneBody = document.getElementById("bgne-tbody");
    if (bgneClasses.length > 0) {
        document.getElementById("bgne-no-schedule").style.display = "none";
        bgneBody.closest("table").style.display = "table";

        const intervalos = [...new Set(bgneClasses.map(c => `${c.horaInicioFormatted} - ${c.horaFinFormatted}`))].sort();
        bgneBody.innerHTML = intervalos.map(interval => {
            const [start] = interval.split(" - ");
            let row = `<tr><td class="${CELL.time}">${interval}</td>`;
            for (let day = 6; day <= 7; day++) {
                const clases = bgneClasses.filter(c => parseInt(c.diaSemana) === day && c.horaInicioFormatted === start);
                row += `<td class="${CELL.day}">${clases.map(mc => bloqueClase(mc, 'bgne')).join('')}</td>`;
            }
            return row + `</tr>`;
        }).join('');
    } else {
        bgneBody.innerHTML = "";
        bgneBody.closest("table").style.display = "none";
        document.getElementById("bgne-no-schedule").style.display = "block";
    }
}

// FUNCIÓN DE IMPRESIÓN OFICIAL DEL HORARIO
function imprimirReporteHorario() {
    if (!docenteActual) return;
    
    // Obtener los HTMLs de las tablas actualmente renderizadas
    const btiClasses = horarioActual.filter(c => c.id_centroTrabajo === 2 || c.diaSemana <= 5);
    const bgneClasses = horarioActual.filter(c => c.id_centroTrabajo === 3 || c.diaSemana >= 6);

    let btiTableHtml = "";
    if (btiClasses.length > 0) {
        btiTableHtml = `
            <h3 class="seccion-titulo">I. Horario de Bachillerato Tecnológico Interamericano (BTI) - Escolarizado</h3>
            <table class="report-table">
                <thead>
                    <tr>
                        <th class="time-header">Hora</th>
                        <th>Lunes</th>
                        <th>Martes</th>
                        <th>Miércoles</th>
                        <th>Jueves</th>
                        <th>Viernes</th>
                    </tr>
                </thead>
                <tbody>
                    ${document.getElementById("bti-tbody").innerHTML}
                </tbody>
            </table>
        `;
    } else {
        btiTableHtml = `
            <h3 class="seccion-titulo">I. Horario de Bachillerato Tecnológico Interamericano (BTI) - Escolarizado</h3>
            <p class="no-classes">El docente no cuenta con carga horaria en el plantel BTI.</p>
        `;
    }

    let bgneTableHtml = "";
    if (bgneClasses.length > 0) {
        bgneTableHtml = `
            <h3 class="seccion-titulo">II. Horario de Bachillerato General No Escolarizado (BGNE) - Fin de Semana</h3>
            <table class="report-table">
                <thead>
                    <tr>
                        <th class="time-header">Hora</th>
                        <th>Sábado</th>
                        <th>Domingo</th>
                    </tr>
                </thead>
                <tbody>
                    ${document.getElementById("bgne-tbody").innerHTML}
                </tbody>
            </table>
        `;
    } else {
        bgneTableHtml = `
            <h3 class="seccion-titulo">II. Horario de Bachillerato General No Escolarizado (BGNE) - Fin de Semana</h3>
            <p class="no-classes">El docente no cuenta con carga horaria en el plantel BGNE.</p>
        `;
    }

    const totalHours = btiClasses.length + bgneClasses.length;
    
    // Crear la ventana de impresión
    const win = window.open("", "_blank");
    win.document.write(`
        <html>
            <head>
                <title>Reporte de Carga Horaria - ${docenteActual.nombreCompleto}</title>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
                <style>
                    body {
                        font-family: 'Segoe UI', Arial, sans-serif;
                        color: #1e293b;
                        padding: 10px 20px;
                        margin: 0;
                        background: #ffffff;
                    }
                    .report-header {
                        display: flex;
                        align-items: center;
                        border-bottom: 2px double rgb(38, 104, 123);
                        padding-bottom: 8px;
                        margin-bottom: 12px;
                    }
                    .logo-placeholder {
                        width: 50px;
                        height: 50px;
                        margin-right: 15px;
                        border-radius:0;
                        background: #f1f5f9;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: rgb(38, 104, 123);
                        font-size: 1.5rem;
                        border: 2px solid rgb(38, 104, 123);
                    }
                    .header-info h1 {
                        font-size: 1.15rem;
                        font-weight: 800;
                        margin: 0 0 3px 0;
                        color: rgb(38, 104, 123);
                        text-transform: uppercase;
                    }
                    .header-info h2 {
                        font-size: 0.8rem;
                        font-weight: 700;
                        margin: 0;
                        color: #475569;
                    }
                    .info-card {
                        background: #f8fafc;
                        border: 1px solid #e2e8f0;
                        border-radius: 0;
                        padding: 8px 12px;
                        margin-bottom: 12px;
                        display: grid;
                        grid-template-columns: 2fr 1fr 1fr 1fr;
                        gap: 8px 12px;
                    }
                    .info-group {
                        display: flex;
                        flex-direction: column;
                    }
                    .info-lbl {
                        font-size: 0.65rem;
                        font-weight: 700;
                        color: #64748b;
                        text-transform: uppercase;
                    }
                    .info-val {
                        font-size: 0.78rem;
                        font-weight: 600;
                        color: #0f172a;
                    }
                    .seccion-titulo {
                        font-size: 0.85rem;
                        font-weight: 700;
                        color: rgb(38, 104, 123);
                        margin: 10px 0 6px 0;
                        padding-bottom: 3px;
                        border-bottom: 1px solid #cbd5e1;
                    }
                    .report-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 12px;
                    }
                    .report-table th {
                        background: rgb(38, 104, 123) !important;
                        color: white !important;
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                        font-size: 0.68rem;
                        font-weight: 700;
                        text-transform: uppercase;
                        padding: 4px 3px;
                        border: 1px solid #cbd5e1;
                        text-align: center;
                    }
                    .report-table td {
                        border: 1px solid #cbd5e1;
                        padding: 3px 4px;
                        font-size: 0.65rem;
                        text-align: center;
                        vertical-align: middle;
                        height: auto;
                        background: #ffffff;
                    }
                    .time-header {
                        width: 100px;
                    }
                    .report-table td.time-col {
                        background: #f8fafc !important;
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                        font-weight: 700;
                        color: #475569;
                        height: auto;
                    }
                    .receso-row {
                        background: #f1f5f9 !important;
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                        height: 18px !important;
                        font-weight: 700;
                        color: #64748b;
                        letter-spacing: 2px;
                    }
                    .class-block {
                        border-left: 2px solid rgb(38, 104, 123);
                        padding-left: 4px;
                        text-align: left;
                        margin-bottom: 4px;
                    }
                    .class-block:last-child {
                        margin-bottom: 0;
                    }
                    .class-materia {
                        font-weight: 700;
                        font-size: 0.65rem;
                        color: #0f172a;
                        line-height: 1.1;
                    }
                    .class-grupo {
                        font-size: 0.6rem;
                        font-weight: 700;
                        color: rgb(38, 104, 123);
                    }
                    .class-aula {
                        font-size: 0.6rem;
                        color: #64748b;
                    }
                    .no-classes {
                        font-size: 0.72rem;
                        color: #64748b;
                        font-style: italic;
                        padding: 8px;
                        background: #f8fafc;
                        border: 1px dashed #cbd5e1;
                        border-radius: 0;
                        text-align: center;
                    }
                    /* Signatures Section */
                    .signatures-container {
                        margin-top: 20px;
                        display: flex;
                        justify-content: space-between;
                        padding: 0 20px;
                    }
                    .sig-box {
                        width: 200px;
                        text-align: center;
                    }
                    .sig-line {
                        border-top: 1px solid #1e293b;
                        margin-top: 25px;
                        padding-top: 3px;
                        font-size: 0.72rem;
                        font-weight: 700;
                    }
                    .sig-title {
                        font-size: 0.65rem;
                        color: #64748b;
                        text-transform: uppercase;
                    }
                    @media print {
                        body {
                            padding: 0;
                            margin: 1cm;
                        }
                        .no-print {
                            display: none;
                        }
                    }
                </style>
            </head>
            <body>
                <!-- ENCABEZADO -->
                <div class="report-header" style="justify-content: center; text-align: center;">
                    <div class="header-info">
                        <h1>Bachillerato Tecnológico Interamericano</h1>
                        <h2>Carga Horaria y Distribución del Personal Docente</h2>
                    </div>
                </div>

                <!-- DATOS DEL DOCENTE -->
                <div class="info-card">
                    <div class="info-group">
                        <span class="info-lbl">Nombre del Docente</span>
                        <span class="info-val">${docenteActual.nombreCompleto}</span>
                    </div>
                    <div class="info-group">
                        <span class="info-lbl">Horas BTI (CCT: 21PCT0073R)</span>
                        <span class="info-val">${btiClasses.length} horas</span>
                    </div>
                    <div class="info-group">
                        <span class="info-lbl">Horas BGNE (CCT: 21PBH0353G)</span>
                        <span class="info-val">${bgneClasses.length} horas</span>
                    </div>
                    <div class="info-group">
                        <span class="info-lbl">Carga Horaria Semanal</span>
                        <span class="info-val" style="color: rgb(38, 104, 123); font-weight: 800;">${totalHours} HORAS</span>
                    </div>
                </div>

                <!-- TABLAS DE HORARIOS -->
                ${btiTableHtml}
                ${bgneTableHtml}

                <!-- FIRMAS -->
                <div class="signatures-container">
                    <div class="sig-box">
                        <div class="sig-line">Firma del Docente</div>
                        <div class="sig-title">${docenteActual.nombreCompleto}</div>
                    </div>
                    <div class="sig-box">
                        <div class="sig-line">Firma de Dirección</div>
                        <div class="sig-title">Director del Plantel</div>
                    </div>
                </div>
            </body>
        </html>
    `);
    win.document.close();
    win.focus();
    setTimeout(() => {
        win.print();
        win.close();
    }, 450);
}
</script>
@endsection
