@extends('layouts.app')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* Apilamiento: SweetAlert por encima de los modales de captura */
.swal2-container { z-index: 99999 !important; }
.modal { z-index: 1200 !important; }
.modal-backdrop { z-index: 1150 !important; }

.ti-spin { animation: ti-spin-anim 1s linear infinite; display: inline-block; }
@keyframes ti-spin-anim { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

/* ---------------------------------------------------------------
   Tabla de captura de calificaciones (filas generadas por JS)
   --------------------------------------------------------------- */
.table-calif-oficial {
    min-width: 1100px;
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 0.8125rem;
    color: #0F172A;
}
.table-calif-oficial thead th {
    position: sticky;
    top: 0;
    z-index: 2;
    background: #F8FAFC;
    color: #64748B;
    font-weight: 600;
    font-size: 0.6875rem;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    padding: 10px 8px;
    border-bottom: 1px solid #E2E8F0;
    text-align: center;
    vertical-align: bottom;
    white-space: nowrap;
}
.table-calif-oficial thead th:nth-child(-n+4) { text-align: left; }
.table-calif-oficial tbody td {
    border-bottom: 1px solid #F1F5F9;
    padding: 6px 8px;
    vertical-align: middle;
    background-color: #FFFFFF;
}
.table-calif-oficial tbody tr:hover td { background-color: #F8FAFC !important; }
.table-calif-oficial tbody td:first-child { color: #94A3B8; font-variant-numeric: tabular-nums; }
.table-calif-oficial tfoot td {
    position: sticky;
    bottom: 0;
    z-index: 2;
    background: #F8FAFC !important;
    border: 0 !important;
    border-top: 1px solid #E2E8F0 !important;
    padding: 10px 8px !important;
    font-weight: 700;
    font-variant-numeric: tabular-nums;
}
.td-calif-letra { font-size: 0.6875rem !important; letter-spacing: 0.04em; }

.input-calif-celda {
    width: 100%;
    max-width: 64px;
    margin: 0 auto;
    display: block;
    height: 32px;
    text-align: center;
    font-weight: 700;
    font-variant-numeric: tabular-nums;
    border: 1px solid #E2E8F0;
    background: #FFFFFF;
    padding: 0 4px;
    font-size: 0.875rem;
    border-radius: 0;
    transition: border-color .15s ease, box-shadow .15s ease;
    -moz-appearance: textfield;
}
.input-calif-celda::-webkit-outer-spin-button,
.input-calif-celda::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
.input-calif-celda:hover { border-color: #CBD5E1; }
.input-calif-celda::placeholder { color: #CBD5E1; font-weight: 500; font-size: 0.75rem; }
.input-calif-celda:focus {
    border-color: #26687B;
    outline: none;
    box-shadow: 0 0 0 3px rgba(38, 104, 123, 0.15);
}
.input-calif-celda:disabled { background: #F8FAFC; color: #94A3B8; cursor: not-allowed; border-color: #F1F5F9; }
.input-calif-celda[readonly] { border-color: transparent; background: transparent !important; cursor: default; box-shadow: none; }

.input-observaciones-celda {
    width: 100%;
    height: 32px;
    border: 1px solid transparent;
    background: transparent;
    padding: 0 8px;
    font-size: 0.8125rem;
    border-radius: 0;
    transition: border-color .15s ease, background-color .15s ease;
}
.input-observaciones-celda:hover { border-color: #E2E8F0; background: #FFFFFF; }
.input-observaciones-celda:focus { border-color: #26687B; background: #FFFFFF; outline: none; box-shadow: 0 0 0 3px rgba(38, 104, 123, 0.15); }

/* Chips y botones usados por el JS dentro de la tabla */
.chip-equiv {
    display: inline-flex; align-items: center; height: 22px; padding: 0 8px; border-radius: 0;
    background: #FEF3C7; color: #B45309; font-size: 0.6875rem; font-weight: 700; letter-spacing: .02em;
}
.btn-equiv {
    display: inline-flex; align-items: center; height: 22px; padding: 0 6px; border-radius: 0;
    border: 1px solid #FDE68A; background: #FFFBEB; color: #B45309;
    font-size: 0.625rem; font-weight: 700; letter-spacing: .04em; cursor: pointer; transition: background-color .15s ease;
}
.btn-equiv:hover:not(:disabled) { background: #FEF3C7; }
.btn-equiv:disabled { opacity: .4; cursor: not-allowed; }
.btn-equiv-undo {
    display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; border-radius: 0;
    border: 0; background: transparent; color: #94A3B8; cursor: pointer;
}
.btn-equiv-undo:hover:not(:disabled) { background: #F1F5F9; color: #0F172A; }

/* Tabla de asistencias (filas generadas por JS) */
#tablaAsistenciasGrupoModal .inp-tot-asist,
#tablaAsistenciasGrupoModal .inp-asist {
    width: 84px; height: 32px; margin: 0 auto; display: block; text-align: center; font-weight: 700;
    border: 1px solid #E2E8F0; border-radius: 0; font-size: 0.875rem; background: #FFFFFF;
}
#tablaAsistenciasGrupoModal .inp-tot-asist:focus,
#tablaAsistenciasGrupoModal .inp-asist:focus { border-color: #26687B; outline: none; box-shadow: 0 0 0 3px rgba(38, 104, 123, 0.15); }
#tablaAsistenciasGrupoModal input[aria-invalid="true"] { border-color: #DC2626; background: #FEF2F2; box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12); }
#tablaAsistenciasGrupoModal .asist-aviso { display: block; margin-top: 4px; font-size: 0.6875rem; line-height: 1.2; color: #DC2626; text-align: center; }
#tablaAsistenciasGrupoModal .asist-aviso:empty { display: none; }
</style>

@php
    $card = ' tw:border tw:border-slate-200 tw:bg-white tw:shadow-card';
    $label = 'tw:mb-1.5 tw:block tw:text-[0.8125rem] tw:font-medium tw:text-slate-700';
    $labelUp = 'tw:mb-1 tw:block tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-400';
    $input = 'tw:h-10 tw:w-full tw:border tw:border-slate-200 tw:bg-white tw:px-3 tw:text-sm tw:text-slate-900 tw:shadow-xs tw:outline-none tw:transition tw:placeholder:text-slate-400 tw:hover:border-slate-300 tw:focus:border-brand-500 tw:focus:ring-4 tw:focus:ring-brand-500/10 tw:disabled:cursor-not-allowed tw:disabled:bg-slate-50 tw:disabled:text-slate-500';
    $btnBase = 'tw:inline-flex tw:cursor-pointer tw:items-center tw:justify-center tw:gap-2 tw:text-sm tw:font-semibold tw:transition-all tw:duration-150 tw:focus-visible:outline-2 tw:focus-visible:outline-offset-2 tw:focus-visible:outline-brand-500 tw:active:scale-[0.98] tw:disabled:cursor-not-allowed tw:disabled:opacity-60';
    $btnPrimary = $btnBase . ' tw:h-10 tw:border-0 tw:bg-brand-700 tw:px-4 tw:text-white tw:shadow-brand tw:hover:bg-brand-800';
    $btnSecondary = $btnBase . ' tw:h-10 tw:border tw:border-slate-200 tw:bg-white tw:px-4 tw:text-slate-700 tw:shadow-xs tw:hover:bg-slate-50';
    $th = 'tw:px-4 tw:py-2.5 tw:text-left tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-500';
    $spinner = '<span class="tw:inline-block tw:size-6 tw:animate-spin tw:rounded-full tw:border-2 tw:border-slate-200 tw:border-t-brand-700"></span>';
    $stat = 'tw:inline-flex tw:items-center tw:gap-1.5 tw:bg-white tw:px-2.5 tw:py-1 tw:text-xs tw:text-slate-600 tw:ring-1 tw:ring-slate-200';
@endphp

<div class="tw:mx-auto tw:flex tw:max-w-[1600px] tw:flex-col tw:gap-6">

    {{-- ===================== ENCABEZADO ===================== --}}
    <header class="tw:flex tw:items-start tw:gap-4">
        <a href="{{ route('grupos') }}" aria-label="Regresar a grupos" title="Regresar a grupos"
            class="tw:mt-0.5 tw:inline-flex tw:size-10 tw:shrink-0 tw:items-center tw:justify-center tw:border tw:border-slate-200 tw:bg-white tw:text-slate-500 tw:no-underline tw:shadow-xs tw:transition-colors tw:hover:bg-slate-50 tw:hover:text-slate-900">
            <i data-lucide="arrow-left" class="tw:size-[18px]"></i>
        </a>
        <div>
            <h1 class="tw:m-0 tw:text-[1.5rem] tw:font-bold tw:tracking-tight tw:text-slate-900">Captura de calificaciones</h1>
            <p class="tw:m-0 tw:mt-1 tw:text-sm tw:text-slate-500">Elige un grupo para capturar calificaciones por asignatura o registrar asistencias del periodo.</p>
        </div>
    </header>

    <section class="{{ $card }} tw:overflow-hidden">
        {{-- Filtros --}}
        <div class="tw:flex tw:flex-col tw:gap-3 tw:border-b tw:border-slate-100 tw:px-5 tw:py-4 tw:lg:flex-row tw:lg:items-center tw:lg:justify-between">
            <div class="tw:flex tw:flex-wrap tw:items-center tw:gap-3">
                {{-- Plantel: chips que sincronizan el select oculto que usa el JS --}}
                <div role="group" aria-label="Plantel" id="chipsFiltroCct" class="tw:inline-flex tw:border tw:border-slate-200 tw:bg-slate-100/80 tw:p-0.5">
                    @foreach (['' => 'Todos', '2' => 'BTI', '3' => 'BGNE', '1' => 'INF'] as $val => $txt)
                    <button type="button" data-cct="{{ $val }}" onclick="setFiltroCct('{{ $val }}')" aria-pressed="{{ $val === '' ? 'true' : 'false' }}"
                        class="tw:cursor-pointer tw:border-0 tw:bg-transparent tw:px-3 tw:py-1.5 tw:text-xs tw:font-semibold tw:text-slate-500 tw:transition-all tw:hover:text-slate-900 tw:aria-pressed:bg-white tw:aria-pressed:text-brand-700 tw:aria-pressed:shadow-xs">{{ $txt }}</button>
                    @endforeach
                </div>
                <select id="selectFiltroCct" class="tw:sr-only" tabindex="-1" aria-hidden="true" onchange="setFiltroCct(this.value)">
                    <option value="" selected>Todos</option>
                    <option value="3">BGNE</option>
                    <option value="2">BTI</option>
                    <option value="1">INF</option>
                </select>

                <label for="selectFiltroEstatus" class="tw:sr-only">Estatus</label>
                <select id="selectFiltroEstatus" onchange="aplicarFiltros()"
                    class="tw:h-9 tw:border tw:border-slate-200 tw:bg-white tw:px-3 tw:text-[0.8125rem] tw:text-slate-700 tw:shadow-xs tw:outline-none tw:focus:border-brand-500 tw:focus:ring-4 tw:focus:ring-brand-500/10">
                    <option value="ACTIVO" selected>Grupos activos</option>
                    <option value="INACTIVO">Grupos inactivos</option>
                    <option value="">Todos los estatus</option>
                </select>
            </div>
            <div class="tw:relative tw:w-full tw:lg:w-80">
                <label for="buscadorGrupoCaptura" class="tw:sr-only">Buscar grupo</label>
                <i data-lucide="search" class="tw:pointer-events-none tw:absolute tw:left-3 tw:top-1/2 tw:size-4 tw:-translate-y-1/2 tw:text-slate-400"></i>
                <input type="text" id="buscadorGrupoCaptura" placeholder="Clave, plantel o nivel…" oninput="aplicarFiltros()"
                    class="tw:h-9 tw:w-full tw:border tw:border-slate-200 tw:bg-white tw:pr-3 tw:pl-9 tw:text-[0.8125rem] tw:text-slate-900 tw:shadow-xs tw:outline-none tw:transition tw:placeholder:text-slate-400 tw:focus:border-brand-500 tw:focus:ring-4 tw:focus:ring-brand-500/10">
            </div>
        </div>

        <div id="loadingGrupos" style="display:none;">
            <div class="tw:flex tw:flex-col tw:items-center tw:gap-3 tw:py-16 tw:text-[0.8125rem] tw:text-slate-500">{!! $spinner !!} Cargando grupos…</div>
        </div>

        <div id="contenedorTablaGruposWrapper" style="display:none;">
            <div class="tw:overflow-x-auto">
                <table class="tw:w-full tw:min-w-[900px] tw:border-collapse" id="tablaGruposCaptura">
                    <thead class="tw:bg-slate-50">
                        <tr class="tw:border-b tw:border-slate-200">
                            <th class="{{ $th }} tw:pl-5">Grupo</th>
                            <th class="{{ $th }}">Nivel</th>
                            <th class="{{ $th }}">Vigencia</th>
                            <th class="{{ $th }} tw:w-[240px]">Avance del periodo</th>
                            <th class="{{ $th }}">Estatus</th>
                            <th class="{{ $th }} tw:pr-5 tw:text-right"><span class="tw:sr-only">Acciones</span></th>
                        </tr>
                    </thead>
                    <tbody id="tbodyGruposCaptura" class="tw:divide-y tw:divide-slate-100"></tbody>
                </table>
            </div>
            <div class="tw:border-t tw:border-slate-100 tw:px-5 tw:py-3 tw:text-xs tw:text-slate-500" id="lblConteoGrupos"></div>
        </div>

        <div id="emptyStateGrupos" style="display:none;">
            <div class="tw:flex tw:flex-col tw:items-center tw:px-6 tw:py-14 tw:text-center">
                <span class="tw:inline-flex tw:size-12 tw:items-center tw:justify-center tw:bg-slate-100 tw:text-slate-400"><i data-lucide="search-x" class="tw:size-5"></i></span>
                <p class="tw:m-0 tw:mt-3 tw:text-sm tw:font-semibold tw:text-slate-900">No se encontraron grupos</p>
                <p class="tw:m-0 tw:mt-1 tw:text-[0.8125rem] tw:text-slate-500">Ajusta el plantel, el estatus o la búsqueda.</p>
            </div>
        </div>
    </section>
</div>

{{-- ===================== MODAL: CAPTURA DE CALIFICACIONES ===================== --}}
<div class="modal fade" id="modalCapturaMateriaGrupo" tabindex="-1" aria-labelledby="modalHeaderTitulo" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="max-width: min(1440px, 96vw);">
        <div class="modal-content">

            <div class="modal-header">
                <div class="tw:flex tw:min-w-0 tw:items-center tw:gap-3">
                    <span id="badgeClaveGrupoOficial" class="tw:inline-flex tw:h-9 tw:shrink-0 tw:items-center tw:bg-brand-700 tw:px-3 tw:font-mono tw:text-sm tw:font-bold tw:tracking-wide tw:text-white">GRUPO</span>
                    <div class="tw:min-w-0">
                        <h5 class="modal-title tw:truncate" id="modalHeaderTitulo">Control de calificaciones finales</h5>
                        <p class="tw:m-0 tw:truncate tw:text-xs tw:text-slate-500" id="boxNombreInstitucion">Bachillerato Interamericano</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body tw:bg-slate-50/70 tw:p-5">
                <div id="loadingMateriaGrupo" style="display: none;">
                    <div class="tw:flex tw:flex-col tw:items-center tw:gap-3 tw:py-16 tw:text-[0.8125rem] tw:text-slate-500">{!! $spinner !!} Cargando asignaturas y alumnos…</div>
                </div>

                <div id="contentCapturaMateriaGrupo">
                    {{-- Solo lectura: el JS alterna su display --}}
                    <div id="bannerSoloLectura" class="tw:mb-4" style="display: none;">
                        <div class="tw:flex tw:items-start tw:gap-3 tw:border tw:border-warning-600/30 tw:bg-warning-50 tw:px-4 tw:py-3">
                            <i data-lucide="lock" class="tw:mt-0.5 tw:size-4 tw:shrink-0 tw:text-warning-600"></i>
                            <div>
                                <p class="tw:m-0 tw:text-sm tw:font-semibold tw:text-slate-900">Captura bloqueada: solo lectura</p>
                                <p class="tw:m-0 tw:mt-0.5 tw:text-[0.8125rem] tw:text-slate-600" id="mensajeBannerSoloLectura">—</p>
                            </div>
                        </div>
                    </div>

                    {{-- Selección y datos de la asignatura --}}
                    <div class=" tw:border tw:border-slate-200 tw:bg-white tw:p-4 tw:shadow-xs">
                        <div class="tw:grid tw:grid-cols-1 tw:gap-4 tw:md:grid-cols-2 tw:xl:grid-cols-[minmax(0,2fr)_minmax(0,4fr)_minmax(0,4fr)]">
                            <div>
                                <label for="selectPeriodoCaptura" class="{{ $labelUp }}" id="labelPeriodoCaptura">Periodo</label>
                                <select id="selectPeriodoCaptura" class="{{ $input }}" onchange="cambiarPeriodoSeleccionado(this.value)" {{ session('rol') === 'DOCENTE' ? 'disabled' : '' }}></select>
                            </div>
                            <div>
                                <label for="selectMateriaGrupo" class="{{ $labelUp }}">Asignatura</label>
                                <select id="selectMateriaGrupo" class="{{ $input }} tw:font-semibold" onchange="cambiarMateriaSeleccionada(this.value)"></select>
                            </div>
                            <div class="tw:md:col-span-2 tw:xl:col-span-1">
                                <span class="{{ $labelUp }}">Docente</span>
                                <div class="tw:flex tw:h-10 tw:items-center tw:gap-2 tw:bg-slate-50 tw:px-3 tw:ring-1 tw:ring-slate-200">
                                    <i data-lucide="presentation" class="tw:size-4 tw:shrink-0 tw:text-slate-400"></i>
                                    <span id="boxNombreDocente" class="tw:truncate tw:text-sm tw:font-semibold tw:text-slate-800">—</span>
                                </div>
                            </div>
                        </div>
                        <div class="tw:mt-4 tw:flex tw:flex-wrap tw:items-center tw:gap-x-5 tw:gap-y-2 tw:border-t tw:border-slate-100 tw:pt-3 tw:text-xs tw:text-slate-500">
                            <span class="tw:inline-flex tw:items-center tw:gap-1.5"><i data-lucide="calendar" class="tw:size-3.5"></i>Inicio <strong id="boxFechaInicioGrupo" class="tw:font-mono tw:font-semibold tw:text-slate-700">—</strong></span>
                            <span class="tw:inline-flex tw:items-center tw:gap-1.5"><i data-lucide="calendar-check" class="tw:size-3.5"></i>Fin <strong id="boxFechaFinGrupo" class="tw:font-mono tw:font-semibold tw:text-slate-700">—</strong></span>
                            <span id="boxModalidadGrupo" class="tw:inline-flex tw:items-center tw:rounded-none tw:bg-brand-50 tw:px-2.5 tw:py-0.5 tw:text-[0.6875rem] tw:font-semibold tw:text-brand-700 tw:ring-1 tw:ring-brand-200">—</span>
                        </div>
                    </div>

                    {{-- Calendario de captura (el JS alterna su display) --}}
                    <div id="containerCalendarioPeriodos" class="tw:mt-4" style="display: none;">
                        <p class="tw:m-0 tw:mb-2 tw:flex tw:items-center tw:gap-1.5 tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-400">
                            <i data-lucide="calendar-range" class="tw:size-3.5"></i> Ventanas de captura del grupo
                        </p>
                        <div id="gridCalendarioPeriodos" class="tw:grid tw:grid-cols-2 tw:gap-2 tw:md:grid-cols-3 tw:xl:grid-cols-5"></div>
                    </div>

                    {{-- Tabla --}}
                    <div class="tw:mt-4 tw:overflow-hidden tw:border tw:border-slate-200 tw:bg-white tw:shadow-xs">
                        <div class="tw:max-h-[56vh] tw:overflow-auto">
                            <table class="table-calif-oficial">
                                <thead id="theadCalificacionesOficial"></thead>
                                <tbody id="tbodyAlumnosCalificacionesMateria"></tbody>
                                <tfoot id="tfootCalificacionesOficial"></tfoot>
                            </table>
                        </div>
                    </div>
                    <p class="tw:m-0 tw:mt-2 tw:text-[0.6875rem] tw:text-slate-400">Calificaciones enteras de 0 a 10, presiona <kbd class=" tw:border tw:border-slate-200 tw:bg-white tw:px-1 tw:font-mono tw:text-[0.6875rem] tw:font-semibold tw:text-slate-700 tw:shadow-none">E</kbd> en una celda para marcar equivalencia, las calificaciones menores a 6 se muestran en rojo.</p>
                </div>
            </div>

            <div class="modal-footer tw:justify-between tw:flex-wrap">
                <div class="tw:flex tw:flex-wrap tw:items-center tw:gap-1.5">
                    <span class="{{ $stat }}">Alumnos <strong id="statTotalAlumnos" class="tw:text-slate-900">0</strong></span>
                    <span class="{{ $stat }}"><span class="tw:size-1.5 tw:rounded-full tw:bg-success-500"></span>Aprobados <strong id="statAprobados" class="tw:text-success-700">0</strong></span>
                    <span class="{{ $stat }}"><span class="tw:size-1.5 tw:rounded-full tw:bg-danger-500"></span>Reprobados <strong id="statReprobados" class="tw:text-danger-600">0</strong></span>
                    <span class="{{ $stat }}"><span class="tw:size-1.5 tw:rounded-full tw:bg-warning-600"></span>Equivalencias <strong id="statEquivalencias" class="tw:text-warning-600">0</strong></span>
                    <span id="statPromedioMateria" class="tw:inline-flex tw:items-center tw:rounded-none tw:bg-brand-700 tw:px-3 tw:py-1 tw:text-xs tw:font-semibold tw:text-white">Promedio 0.0</span>
                </div>
                <div class="tw:flex tw:items-center tw:gap-2">
                    <button type="button" class="{{ $btnSecondary }}" data-bs-dismiss="modal">Cerrar</button>
                    <span id="wrapGuardarCalifs">{{-- el JS alterna su display --}}<button type="button" class="{{ $btnPrimary }}" id="btnGuardarCalifsMateria" onclick="guardarCalificacionesMateriaSeleccionada()">
                        <i class="ti ti-device-floppy"></i> Guardar calificaciones
                    </button></span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===================== MODAL: ASISTENCIAS DEL PERIODO ===================== --}}
<div class="modal fade" id="modalCapturaAsistenciasGrupo" tabindex="-1" aria-labelledby="modalCapturaAsistenciasLabel" aria-hidden="true" style="z-index: 1250;">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div class="tw:min-w-0">
                    <h5 class="modal-title" id="modalCapturaAsistenciasLabel"><i data-lucide="calendar-check" class="tw:size-5"></i> Asistencias del periodo</h5>
                    <p class="tw:m-0 tw:text-xs tw:text-slate-500">Grupo <strong id="lblAsistenciasGrupoClave" class="tw:font-mono tw:text-slate-800">---</strong></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body tw:bg-slate-50/70 tw:p-5">
                <div class="tw:grid tw:grid-cols-1 tw:items-end tw:gap-3 tw:border tw:border-slate-200 tw:bg-white tw:p-4 tw:shadow-xs tw:sm:grid-cols-[minmax(0,1fr)_160px_auto]">
                    <div>
                        <label for="selSemestreAsistenciasGrupo" class="{{ $label }}">Periodo</label>
                        <select id="selSemestreAsistenciasGrupo" class="{{ $input }}" onchange="recargarAlumnosAsistenciasModal()"></select>
                    </div>
                    <div>
                        <label for="inpTotalClasesGlobal" class="{{ $label }}">Clases impartidas</label>
                        <input type="number" id="inpTotalClasesGlobal" class="{{ $input }}" placeholder="Ej. 50" min="0" max="999" step="1" inputmode="numeric" data-entero>
                    </div>
                    <button type="button" class="{{ $btnSecondary }}" onclick="aplicarTotalClasesATodos()" title="Copia el total de clases a todos los alumnos">
                        <i data-lucide="copy-check" class="tw:size-4"></i> Aplicar a todos
                    </button>
                </div>

                <div class="tw:mt-4 tw:overflow-hidden tw:border tw:border-slate-200 tw:bg-white tw:shadow-xs">
                    <div class="tw:max-h-[420px] tw:overflow-auto">
                        <table class="tw:w-full tw:border-collapse" id="tablaAsistenciasGrupoModal">
                            <thead class="tw:sticky tw:top-0 tw:z-10 tw:bg-slate-50">
                                <tr class="tw:border-b tw:border-slate-200">
                                    <th class="{{ $th }} tw:w-12 tw:text-center">#</th>
                                    <th class="{{ $th }}">Alumno</th>
                                    <th class="{{ $th }} tw:text-center">Clases</th>
                                    <th class="{{ $th }} tw:text-center">Asistencias</th>
                                    <th class="{{ $th }} tw:text-center">%</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyAsistenciasGrupoModal" class="tw:divide-y tw:divide-slate-100">
                                <tr><td colspan="5" class="tw:py-8 tw:text-center tw:text-[0.8125rem] tw:text-slate-500">Cargando alumnos…</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="{{ $btnSecondary }}" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="{{ $btnPrimary }}" id="btnGuardarAsistenciasModal" onclick="guardarAsistenciasGrupoModal()">
                    <i class="ti ti-device-floppy"></i> Guardar asistencias
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let listaTodosGrupos = [];
let filtroCctActual = '';
let grupoCapturaActualId = null;
let datosGrupoMateriaActual = null;


// ==========================================
// CAPTURA DE ASISTENCIAS DEL PERIODO POR GRUPO
// ==========================================
let grupoAsistenciasActual = null;

function abrirModalAsistenciasGrupo(idGrupo, claveGrupo, idCct, idNivelActivo) {
    grupoAsistenciasActual = {
        id: idGrupo,
        clave: claveGrupo,
        idCct: idCct,
        idNivelActivo: idNivelActivo
    };

    const lbl = document.getElementById('lblAsistenciasGrupoClave');
    if (lbl) lbl.innerText = claveGrupo;

    const select = document.getElementById('selSemestreAsistenciasGrupo');
    if (select) {
        select.innerHTML = '';
        const isSemestral = (idCct === 2 || (claveGrupo && (claveGrupo.includes('BTI') || claveGrupo.includes('LV'))));

        if (isSemestral) {
            const semestres = [
                { id: 7, nombre: '1er Semestre' },
                { id: 8, nombre: '2° Semestre' },
                { id: 9, nombre: '3er Semestre' },
                { id: 10, nombre: '4° Semestre' },
                { id: 11, nombre: '5to Semestre' },
                { id: 12, nombre: '6to Semestre' }
            ];
            semestres.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = s.nombre;
                if (s.id === idNivelActivo) opt.selected = true;
                select.appendChild(opt);
            });
        } else {
            const trimestres = [
                { id: 1, nombre: '1er Trimestre' },
                { id: 2, nombre: '2° Trimestre' },
                { id: 3, nombre: '3er Trimestre' },
                { id: 4, nombre: '4to Trimestre' },
                { id: 5, nombre: '5to Trimestre' },
                { id: 6, nombre: '6to Trimestre' }
            ];
            trimestres.forEach(t => {
                const opt = document.createElement('option');
                opt.value = t.id;
                opt.textContent = t.nombre;
                if (t.id === idNivelActivo) opt.selected = true;
                select.appendChild(opt);
            });
        }
    }

    let modalEl = document.getElementById('modalCapturaAsistenciasGrupo');
    if (modalEl) {
        let modal = bootstrap.Modal.getInstance(modalEl);
        if (!modal) modal = new bootstrap.Modal(modalEl);
        modal.show();
    }

    recargarAlumnosAsistenciasModal();
}

function recargarAlumnosAsistenciasModal() {
    if (!grupoAsistenciasActual) return;
    const tbody = document.getElementById('tbodyAsistenciasGrupoModal');
    if (!tbody) return;
    tbody.innerHTML = filaMensajeAsist('Cargando lista de alumnos…');

    const idNivel = document.getElementById('selSemestreAsistenciasGrupo') ? document.getElementById('selSemestreAsistenciasGrupo').value : 7;

    fetch(`/grupos/${encodeURIComponent(grupoAsistenciasActual.id)}/asistencias-semestre?id_nivel_academico=${encodeURIComponent(idNivel)}`)
        .then(r => r.json())
        .then(resp => {
            const alumnos = resp.alumnos || [];
            if (!alumnos.length) {
                tbody.innerHTML = filaMensajeAsist('No hay alumnos inscritos en este grupo.');
                return;
            }

            tbody.innerHTML = alumnos.map((al, idx) => {
                const totVal = al.total_asistencias ?? '';
                const asistVal = al.asistencias ?? '';
                const pct = (al.porcentaje !== null && al.porcentaje !== undefined) ? Number(al.porcentaje) : null;
                return `
                    <tr data-alumno-id="${Number(al.idAlumno)}" class="tw:transition-colors tw:hover:bg-slate-50">
                        <td class="tw:px-4 tw:py-2.5 tw:text-center tw:text-xs tw:text-slate-400 tw:tabular-nums">${idx + 1}</td>
                        <td class="tw:px-4 tw:py-2.5">
                            <div class="tw:text-[0.8125rem] tw:font-semibold tw:text-slate-900">${escHtml(`${al.apPaterno || ''} ${al.apMaterno || ''} ${al.nombre || ''}`.trim())}</div>
                            <div class="tw:font-mono tw:text-[0.6875rem] tw:text-slate-400">${escHtml(al.numeroControl || al.idAlumno)}</div>
                        </td>
                        <td class="tw:px-4 tw:py-2.5"><input type="number" min="0" max="${ASIST_MAX_CLASES}" step="1" inputmode="numeric" data-entero class="inp-tot-asist" value="${escHtml(totVal)}" placeholder="—" aria-label="Clases impartidas" oninput="validarFilaAsistencia(this)"></td>
                        <td class="tw:px-4 tw:py-2.5"><input type="number" min="0" ${totVal !== '' ? `max="${escHtml(totVal)}"` : ''} step="1" inputmode="numeric" data-entero class="inp-asist" value="${escHtml(asistVal)}" placeholder="—" aria-label="Asistencias" oninput="validarFilaAsistencia(this)"><span class="asist-aviso" role="alert"></span></td>
                        <td class="tw:px-4 tw:py-2.5 tw:text-center td-pct">${chipPorcentaje(pct)}</td>
                    </tr>`;
            }).join('');
        })
        .catch(err => {
            console.error(err);
            tbody.innerHTML = filaMensajeAsist('Error al cargar las asistencias del grupo.', true);
        });
}

function filaMensajeAsist(texto, error = false) {
    return `<tr><td colspan="5" class="tw:py-8 tw:text-center tw:text-[0.8125rem] ${error ? 'tw:text-danger-600' : 'tw:text-slate-500'}">${escHtml(texto)}</td></tr>`;
}

function chipPorcentaje(pct) {
    if (pct === null || isNaN(pct)) {
        return '<span class="tw:inline-flex tw:rounded-none tw:bg-slate-100 tw:px-2.5 tw:py-0.5 tw:text-[0.6875rem] tw:font-semibold tw:text-slate-400">—</span>';
    }
    const tono = pct >= 80 ? 'tw:bg-success-100 tw:text-success-700' : (pct >= 60 ? 'tw:bg-warning-100 tw:text-warning-600' : 'tw:bg-danger-100 tw:text-danger-600');
    return `<span class="tw:inline-flex tw:rounded-none tw:px-2.5 tw:py-0.5 tw:text-[0.6875rem] tw:font-semibold tw:tabular-nums ${tono}">${pct}%</span>`;
}

function escHtml(v) {
    return String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
}

// ---------------------------------------------------------------------------
// Validación de asistencias: enteros ≥ 0 y asistencias ≤ clases impartidas
// ---------------------------------------------------------------------------
const ASIST_MAX_CLASES = 999;

// En campos numéricos enteros no se permite escribir "-", "+", "e" ni decimales
document.addEventListener('keydown', (e) => {
    if (e.target.matches?.('input[data-entero]') && ['-', '+', 'e', 'E', '.', ','].includes(e.key)) e.preventDefault();
});
// Pegar texto: se conservan solo los dígitos
document.addEventListener('paste', (e) => {
    if (!e.target.matches?.('input[data-entero]')) return;
    e.preventDefault();
    e.target.value = (e.clipboardData?.getData('text') || '').replace(/\D/g, '');
    e.target.dispatchEvent(new Event('input', { bubbles: true }));
});

/** Normaliza un input a entero >= 0 (o vacío) sin pasar de `max`. Devuelve el número o null. */
function normalizarEntero(inp, max) {
    const txt = String(inp.value ?? '').replace(/\D/g, '');
    if (txt === '') { inp.value = ''; return null; }
    let n = parseInt(txt, 10);
    if (max !== undefined && max !== null && n > max) n = max;
    if (String(n) !== inp.value) inp.value = n;
    return n;
}

/** Valida la fila al escribir: si las asistencias superan las clases se ajustan al máximo y se avisa. */
function validarFilaAsistencia(inputEl) {
    const tr = inputEl.closest('tr');
    if (!tr) return;
    const inpTot = tr.querySelector('.inp-tot-asist');
    const inpAsist = tr.querySelector('.inp-asist');
    const aviso = tr.querySelector('.asist-aviso');

    const tot = normalizarEntero(inpTot, ASIST_MAX_CLASES);
    if (tot === null) inpAsist.removeAttribute('max'); else inpAsist.max = tot;

    // Solo se recorta al escribir en Asistencias. Si se cambian las Clases (p. ej. al teclear "10"
    // pasa por "1"), las asistencias NO se modifican: se marcan en rojo y se bloquea el guardado.
    const escribiendoAsistencias = inputEl === inpAsist;
    const escrito = parseInt(String(inpAsist.value).replace(/\D/g, ''), 10);
    const asist = normalizarEntero(inpAsist, escribiendoAsistencias ? tot : null);
    let mensaje = '';
    if (tot !== null && !isNaN(escrito) && escrito > tot) {
        mensaje = escribiendoAsistencias ? `Máximo ${tot} (clases impartidas)` : `Supera las ${tot} clases`;
    } else if (tot === null && asist !== null) {
        mensaje = 'Primero captura las clases';
    }

    if (mensaje) inpAsist.setAttribute('aria-invalid', 'true'); else inpAsist.removeAttribute('aria-invalid');
    if (aviso) aviso.textContent = mensaje;
    actualizarPorcentajeFila(inputEl);
}

function actualizarPorcentajeFila(inputEl) {
    const tr = inputEl.closest('tr');
    if (!tr) return;
    const tot = parseFloat(tr.querySelector('.inp-tot-asist')?.value);
    const asist = parseFloat(tr.querySelector('.inp-asist')?.value);
    const tdPct = tr.querySelector('.td-pct');
    if (!tdPct) return;
    tdPct.innerHTML = chipPorcentaje((!isNaN(tot) && !isNaN(asist) && tot > 0) ? Math.round((asist / tot) * 100) : null);
}

function aplicarTotalClasesATodos() {
    const inp = document.getElementById('inpTotalClasesGlobal');
    const val = inp ? inp.value.trim() : '';
    if (!val) {
        Swal.fire({
            icon: 'info',
            title: 'Ingrese un valor',
            text: 'Por favor, escriba el total de clases impartidas en el campo superior.',
            confirmButtonColor: '#26687B'
        });
        return;
    }
    const total = normalizarEntero(inp, ASIST_MAX_CLASES);
    if (total === null) return;
    const inputs = document.querySelectorAll('#tbodyAsistenciasGrupoModal .inp-tot-asist');
    let ajustadas = 0;
    inputs.forEach(i => {
        const asist = parseInt(i.closest('tr')?.querySelector('.inp-asist')?.value, 10);
        if (!isNaN(asist) && asist > total) ajustadas++;
        i.value = total;
        validarFilaAsistencia(i);
    });
    if (ajustadas) {
        Swal.fire({
            icon: 'info',
            title: 'Asistencias ajustadas',
            text: `${ajustadas} ${ajustadas === 1 ? 'alumno tenía' : 'alumnos tenían'} más de ${total} asistencias; se ajustaron a ${total}. Revísalas antes de guardar.`,
            confirmButtonColor: '#26687B'
        });
    }
}

function guardarAsistenciasGrupoModal() {
    if (!grupoAsistenciasActual) return;
    const sel = document.getElementById('selSemestreAsistenciasGrupo');
    const idNivel = sel ? parseInt(sel.value) : 7;
    const rows = document.querySelectorAll('#tbodyAsistenciasGrupoModal tr[data-alumno-id]');

    if (!rows.length) {
        Swal.fire({ icon: 'warning', title: 'Sin alumnos', text: 'No hay alumnos para guardar.' });
        return;
    }

    // Validación final: enteros >= 0, asistencias <= clases y sin asistencias sin clases
    const invalidas = [];
    rows.forEach(tr => {
        const tot = tr.querySelector('.inp-tot-asist').value.trim();
        const asist = tr.querySelector('.inp-asist').value.trim();
        const nombre = tr.querySelector('td:nth-child(2) div')?.textContent.trim() || 'Alumno';
        if (asist !== '' && tot === '') {
            tr.querySelector('.inp-asist').setAttribute('aria-invalid', 'true');
            invalidas.push(`${nombre}: tiene asistencias pero no clases impartidas`);
        } else if (asist !== '' && parseInt(asist, 10) > parseInt(tot, 10)) {
            tr.querySelector('.inp-asist').setAttribute('aria-invalid', 'true');
            invalidas.push(`${nombre}: ${asist} asistencias de ${tot} clases`);
        }
    });
    if (invalidas.length) {
        Swal.fire({
            icon: 'warning',
            title: 'Revisa las asistencias',
            html: invalidas.slice(0, 6).map(escHtml).join('<br>') + (invalidas.length > 6 ? `<br>… y ${invalidas.length - 6} más` : ''),
            confirmButtonColor: '#26687B'
        });
        document.querySelector('#tbodyAsistenciasGrupoModal input[aria-invalid="true"]')?.focus();
        return;
    }

    const payloadList = [];
    rows.forEach(tr => {
        const idAlumno = parseInt(tr.getAttribute('data-alumno-id'));
        const totInp = tr.querySelector('.inp-tot-asist');
        const asistInp = tr.querySelector('.inp-asist');
        const totVal = totInp ? totInp.value.trim() : '';
        const asistVal = asistInp ? asistInp.value.trim() : '';

        payloadList.push({
            idAlumno: idAlumno,
            total_asistencias: totVal !== '' ? parseInt(totVal, 10) : null,
            asistencias: asistVal !== '' ? parseInt(asistVal, 10) : null
        });
    });

    const btn = document.getElementById('btnGuardarAsistenciasModal');
    const originalText = btn ? btn.innerHTML : 'Guardar Asistencias';
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = `<i class="ti ti-loader-2 ti-spin me-1"></i> Guardando...`;
    }

    fetch(`/grupos/${grupoAsistenciasActual.id}/asistencias-semestre`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : ''
        },
        body: JSON.stringify({
            id_nivel_academico: idNivel,
            asistencias: payloadList
        })
    })
    .then(r => r.json())
    .then(resp => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }

        if (resp.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Asistencias Guardadas!',
                text: 'Las asistencias del periodo fueron actualizadas exitosamente y se reflejarán en las boletas.',
                confirmButtonColor: '#26687B'
            });
            recargarAlumnosAsistenciasModal();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error al guardar',
                text: (resp.errors ? Object.values(resp.errors).flat().join(' ') : '') || resp.message || resp.error || 'Ocurrió un error inesperado.'
            });
        }
    })
    .catch(err => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo comunicar con el servidor.'
        });
    });
}


// ==========================================
// CÁLCULO DE PROGRESO DE PERIODO
// ==========================================
function calcularProgresoPeriodo(g) {
    if (!g.fechaInicio) return { percent: 0, nivelText: '', inicioPeriodo: '—', finPeriodo: '—' };

    const parseToUTCDate = (dateVal) => {
        if (!dateVal) return null;
        if (dateVal instanceof Date) {
            return new Date(Date.UTC(dateVal.getFullYear(), dateVal.getMonth(), dateVal.getDate()));
        }
        const dateStr = String(dateVal).trim();
        if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr) || /^\d{4}-\d{2}-\d{2}\s/.test(dateStr)) {
            const parts = dateStr.substring(0, 10).split('-');
            return new Date(Date.UTC(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2])));
        }
        const d = new Date(dateStr);
        if (isNaN(d.getTime())) return null;
        if (dateStr.includes('GMT') || dateStr.endsWith('Z')) {
            return new Date(Date.UTC(d.getUTCFullYear(), d.getUTCMonth(), d.getUTCDate()));
        } else {
            return new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()));
        }
    };

    let periodStartDate = parseToUTCDate(g.fechaInicio);
    if (!periodStartDate) return { percent: 0, nivelText: '', inicioPeriodo: '—', finPeriodo: '—' };

    let idTipoPeriodo = g.id_tipoPeriodo;
    let idNivelActualDb = g.id_nivel_academico;

    if (idTipoPeriodo === null || idTipoPeriodo === undefined) {
        if (idNivelActualDb !== null && idNivelActualDb >= 7) {
            idTipoPeriodo = 1; // SEMESTRAL
        } else {
            idTipoPeriodo = 2; // TRIMESTRAL (default)
        }
    }

    const isTrimestral = idTipoPeriodo === 2;
    const periodLabel = isTrimestral ? "Trim." : "Sem.";

    const now = new Date();
    const todayUTC = new Date(Date.UTC(now.getFullYear(), now.getMonth(), now.getDate()));

    const toDMY = (d) => {
        if (!d || isNaN(d.getTime())) return '—';
        const dd = String(d.getUTCDate()).padStart(2, '0');
        const mm = String(d.getUTCMonth() + 1).padStart(2, '0');
        const yyyy = d.getUTCFullYear();
        return `${dd}/${mm}/${yyyy}`;
    };

    let periodEndDate;

    if (!isTrimestral) {
        // LÓGICA SEMESTRAL (Escolarizado / BTI)
        let currentSemester = 1;
        if (idNivelActualDb !== null && idNivelActualDb !== undefined && idNivelActualDb >= 7) {
            currentSemester = idNivelActualDb - 6;
        }

        periodEndDate = parseToUTCDate(g.fechaFin);
        
        if (!periodEndDate) {
            // Fallback si no tiene fechaFin
            const startYear = periodStartDate.getUTCFullYear();
            const isFall = (currentSemester % 2 !== 0);
            let periodYear = startYear;
            if (isFall) {
                periodYear = startYear + Math.floor((currentSemester - 1) / 2);
            } else {
                periodYear = startYear + Math.floor(currentSemester / 2);
            }
            if (isFall) {
                periodStartDate = new Date(Date.UTC(periodYear, 7, 1)); // 1 de Agosto
                periodEndDate = new Date(Date.UTC(periodYear, 11, 31)); // 31 de Diciembre
            } else {
                periodStartDate = new Date(Date.UTC(periodYear, 1, 1)); // 1 de Febrero
                periodEndDate = new Date(Date.UTC(periodYear, 6, 31)); // 31 de Julio
            }
        }

        // Porcentaje de progreso
        let percent = 0;
        if (todayUTC.getTime() >= periodEndDate.getTime()) {
            percent = 100;
        } else if (todayUTC.getTime() <= periodStartDate.getTime()) {
            percent = 0;
        } else {
            const total = periodEndDate.getTime() - periodStartDate.getTime();
            const elapsed = todayUTC.getTime() - periodStartDate.getTime();
            percent = Math.round((elapsed / total) * 100);
            percent = Math.max(0, Math.min(100, percent));
        }

        return {
            percent: percent,
            nivelText: `${currentSemester}° ${periodLabel}`,
            inicioPeriodo: toDMY(periodStartDate),
            finPeriodo: toDMY(periodEndDate)
        };
    }

    // LÓGICA TRIMESTRAL (BGNE)
    let currentTrimestre = (idNivelActualDb !== null && idNivelActualDb !== undefined && idNivelActualDb >= 1 && idNivelActualDb <= 6) ? idNivelActualDb : 1;

    if (g.fechaInicioNivel && g.fechaFinNivel) {
        const parsedStart = parseToUTCDate(g.fechaInicioNivel);
        const parsedEnd = parseToUTCDate(g.fechaFinNivel);
        if (parsedStart && parsedEnd) {
            periodStartDate = parsedStart;
            periodEndDate = parsedEnd;
        }
    } else {
        let fInicio = new Date(periodStartDate.getTime());
        let fFin = new Date(fInicio.getTime() + (12 * 7 * 24 * 60 * 60 * 1000));
        let lvlCalculado = 1;

        while (true) {
            fFin = new Date(fInicio.getTime() + (12 * 7 * 24 * 60 * 60 * 1000));
            if (todayUTC.getTime() <= fFin.getTime() || lvlCalculado >= 6) {
                break;
            }
            fInicio = new Date(fFin.getTime() + (7 * 24 * 60 * 60 * 1000));
            lvlCalculado++;
        }

        if (lvlCalculado > currentTrimestre) {
            currentTrimestre = lvlCalculado;
            periodStartDate = fInicio;
            periodEndDate = fFin;
        } else {
            const weeksOffset = (currentTrimestre - 1) * 13;
            periodStartDate = new Date(periodStartDate.getTime() + (weeksOffset * 7 * 24 * 60 * 60 * 1000));
            periodEndDate = new Date(periodStartDate.getTime() + (12 * 7 * 24 * 60 * 60 * 1000));
        }
    }

    // Limitar a fechaFin oficial si existe
    const groupEndDate = parseToUTCDate(g.fechaFin);
    if (groupEndDate && periodEndDate.getTime() > groupEndDate.getTime()) {
        periodEndDate = groupEndDate;
    }

    // Porcentaje de progreso
    let percent = 0;
    if (todayUTC.getTime() >= periodEndDate.getTime()) {
        percent = 100;
    } else if (todayUTC.getTime() <= periodStartDate.getTime()) {
        percent = 0;
    } else {
        const total = periodEndDate.getTime() - periodStartDate.getTime();
        const elapsed = todayUTC.getTime() - periodStartDate.getTime();
        percent = Math.round((elapsed / total) * 100);
        percent = Math.max(0, Math.min(100, percent));
    }

    return {
        percent: percent,
        currentNivel: currentTrimestre,
        nivelText: `${currentTrimestre}° ${periodLabel}`,
        inicioPeriodo: toDMY(periodStartDate),
        finPeriodo: toDMY(periodEndDate)
    };
}

function formatearFecha(fecha) {
    if (!fecha) return '—';
    try {
        const d = new Date(fecha);
        if (isNaN(d.getTime())) return fecha;
        const dd = String(d.getUTCDate()).padStart(2, '0');
        const mm = String(d.getUTCMonth() + 1).padStart(2, '0');
        const yyyy = d.getUTCFullYear();
        return `${dd}/${mm}/${yyyy}`;
    } catch(e) {
        return fecha;
    }
}

// Convertidor de número a letra para calificaciones escolares (Enteros del 0 al 10 o EQUIVALENCIA)
function numeroALetrasCalificacion(num) {
    if (num === 'EQUIV' || num === 'EQUIVALENCIA') return 'EQUIVALENCIA';
    if (num === null || num === undefined || num === '' || isNaN(num)) return '—';
    const n = parseInt(num, 10);
    if (isNaN(n) || n < 0 || n > 10) return '—';

    const nombres = {
        0: 'CERO', 1: 'UNO', 2: 'DOS', 3: 'TRES', 4: 'CUATRO',
        5: 'CINCO', 6: 'SEIS', 7: 'SIETE', 8: 'OCHO', 9: 'NUEVE', 10: 'DIEZ'
    };

    return nombres[n] || String(n);
}

// Prevención estricta de decimales en teclas (solo enteros 0 al 10 o conmutación por tecla 'E')
function prevenirDecimales(event) {
    const allowedKeys = ['Backspace', 'Tab', 'Enter', 'Escape', 'ArrowLeft', 'ArrowRight', 'Delete'];
    if (allowedKeys.includes(event.key)) return;
    if (event.ctrlKey || event.metaKey) return;

    // Si presiona 'e' o 'E', permitir conmutar a modo EQUIV
    if (event.key === 'e' || event.key === 'E') {
        event.preventDefault();
        const tr = event.target.closest('tr');
        if (tr) {
            conmutarEquivalenciaFila(tr, true);
        }
        return;
    }

    // Bloquear punto '.', coma ',', '-', '+', etc.
    if (['.', ',', '-', '+', 'e', 'E'].includes(event.key)) {
        event.preventDefault();
        return;
    }

    // Solo permitir dígitos 0 al 9
    if (!/^\d$/.test(event.key)) {
        event.preventDefault();
        return;
    }
}

// ==========================================
// CARGAR GRUPOS DESDE EL BACKEND
// ==========================================
function cargarGruposCaptura() {
    const loading = document.getElementById('loadingGrupos');
    if (loading) loading.style.display = 'block';

    fetch('/grupos/lista?limit=200')
        .then(async res => {
            const data = await res.json().catch(() => null);
            if (!res.ok) {
                throw new Error((data && (data.error || data.message)) || `Error HTTP ${res.status}`);
            }
            return data;
        })
        .then(res => {
            listaTodosGrupos = (res && Array.isArray(res.data)) ? res.data : [];
            aplicarFiltros();
        })
        .catch(err => {
            console.error('Error al cargar grupos:', err);
            listaTodosGrupos = [];
            aplicarFiltros();
            Swal.fire({
                icon: 'error',
                title: 'Error al cargar grupos',
                text: 'No se pudieron cargar los grupos desde el servidor backend. Verifique que el servicio esté iniciado.',
                confirmButtonColor: '#26687B'
            });
        })
        .finally(() => {
            if (loading) loading.style.display = 'none';
        });
}

function setFiltroCct(cctVal) {
    filtroCctActual = cctVal;
    const select = document.getElementById('selectFiltroCct');
    if (select && select.value !== cctVal) {
        select.value = cctVal;
    }
    document.querySelectorAll('#chipsFiltroCct [data-cct]').forEach(b => b.setAttribute('aria-pressed', b.dataset.cct === String(cctVal) ? 'true' : 'false'));
    aplicarFiltros();
}

function aplicarFiltros() {
    const selectCct = document.getElementById('selectFiltroCct');
    const cctFiltro = selectCct ? selectCct.value : filtroCctActual;
    filtroCctActual = cctFiltro;

    const selectEstatus = document.getElementById('selectFiltroEstatus');
    const estatusFiltro = selectEstatus ? selectEstatus.value : '';

    const inputBusqueda = document.getElementById('buscadorGrupoCaptura');
    const busqueda = (inputBusqueda ? inputBusqueda.value : '').toLowerCase().trim();

    const filtrados = listaTodosGrupos.filter(g => {
        if (cctFiltro !== '') {
            const idCct = g.id_centroTrabajo ?? g.id_centro_trabajo;
            if (String(idCct) !== String(cctFiltro)) {
                return false;
            }
        }
        if (estatusFiltro !== '') {
            const statusG = String(g.statusGrupo || g.status_grupo || 'ACTIVO').toUpperCase();
            if (statusG !== estatusFiltro) {
                return false;
            }
        }
        if (busqueda !== '') {
            const clave = (g.clave || '').toLowerCase();
            const cct = (g.nombreCentroTrabajo || g.nombre_centro_trabajo || '').toLowerCase();
            const nivel = (g.nombre_nivel || g.nombreNivel || '').toLowerCase();
            if (!clave.includes(busqueda) && !cct.includes(busqueda) && !nivel.includes(busqueda)) {
                return false;
            }
        }
        return true;
    });

    renderTablaGrupos(filtrados);
}

function renderTablaGrupos(grupos) {
    const tbody = document.getElementById('tbodyGruposCaptura');
    const tableWrapper = document.getElementById('contenedorTablaGruposWrapper');
    const emptyState = document.getElementById('emptyStateGrupos');

    if (!grupos || !grupos.length) {
        if (tableWrapper) tableWrapper.style.display = 'none';
        if (emptyState) emptyState.style.display = 'block';
        return;
    }

    if (tableWrapper) tableWrapper.style.display = 'block';
    if (emptyState) emptyState.style.display = 'none';
    const CCT = {
        2: ['BTI', 'tw:bg-brand-50 tw:text-brand-700 tw:ring-brand-200'],
        3: ['BGNE', 'tw:bg-accent-100 tw:text-accent-600 tw:ring-accent-400/40'],
        1: ['INF', 'tw:bg-slate-100 tw:text-slate-600 tw:ring-slate-200'],
    };

    const html = grupos.map(g => {
        const activo = String(g.statusGrupo || g.status_grupo || 'ACTIVO').toUpperCase() === 'ACTIVO';
        const idCct = parseInt(g.id_centroTrabajo ?? g.id_centro_trabajo, 10);
        const [cctTxt, cctTono] = CCT[idCct] || CCT[1];
        const nivelNombre = g.nombre_nivel || g.nombreNivel || (g.id_nivel_academico ? (g.id_nivel_academico <= 6 ? `${g.id_nivel_academico}° Trimestre` : `${g.id_nivel_academico - 6}° Semestre`) : '—');

        let progreso = { percent: 0, nivelText: 'Periodo', inicioPeriodo: '—', finPeriodo: '—' };
        try {
            progreso = calcularProgresoPeriodo(g) || progreso;
        } catch (eProg) {
            console.warn('Error calculando progreso para grupo:', g, eProg);
        }

        // Argumentos seguros para los onclick (la clave puede contener comillas)
        const id = Number(g.id);
        const argsAsist = [id, g.clave || '', Number(g.id_centroTrabajo ?? idCct), Number(g.id_nivel_academico || 7)]
            .map(v => escHtml(JSON.stringify(v))).join(', ');

        return `
        <tr class="tw:transition-colors tw:hover:bg-slate-50">
            <td class="tw:py-3 tw:pr-4 tw:pl-5 tw:align-middle">
                <div class="tw:flex tw:items-center tw:gap-2">
                    <span class="tw:font-mono tw:text-[0.8125rem] tw:font-bold tw:text-slate-900">${escHtml(g.clave || '—')}</span>
                    <span class="tw:rounded-none tw:px-2 tw:py-0.5 tw:text-[0.625rem] tw:font-bold tw:ring-1 ${cctTono}">${cctTxt}</span>
                </div>
            </td>
            <td class="tw:px-4 tw:py-3 tw:align-middle tw:text-[0.8125rem] tw:text-slate-700">${escHtml(nivelNombre)}</td>
            <td class="tw:px-4 tw:py-3 tw:align-middle tw:font-mono tw:text-xs tw:whitespace-nowrap tw:text-slate-500">${formatearFecha(g.fechaInicio)} – ${formatearFecha(g.fechaFin)}</td>
            <td class="tw:px-4 tw:py-3 tw:align-middle">
                <div class="tw:mb-1 tw:flex tw:justify-between tw:text-[0.6875rem]"><span class="tw:font-semibold tw:text-slate-700">${escHtml(progreso.nivelText || 'Periodo')}</span><span class="tw:text-slate-500 tw:tabular-nums">${progreso.percent}%</span></div>
                <div class="tw:h-1.5 tw:overflow-hidden tw:bg-slate-100"><div class="tw:h-full tw:bg-brand-700" style="width:${progreso.percent}%"></div></div>
                <div class="tw:mt-1 tw:flex tw:justify-between tw:font-mono tw:text-[0.625rem] tw:text-slate-400"><span>${progreso.inicioPeriodo}</span><span>${progreso.finPeriodo}</span></div>
            </td>
            <td class="tw:px-4 tw:py-3 tw:align-middle">
                <span class="tw:inline-flex tw:items-center tw:gap-1.5 tw:rounded-none tw:px-2.5 tw:py-0.5 tw:text-[0.6875rem] tw:font-semibold ${activo ? 'tw:bg-success-100 tw:text-success-700' : 'tw:bg-slate-100 tw:text-slate-500'}">
                    <span class="tw:size-1.5 tw:rounded-full ${activo ? 'tw:bg-success-700' : 'tw:bg-slate-400'}"></span>${activo ? 'Activo' : 'Inactivo'}
                </span>
            </td>
            <td class="tw:py-3 tw:pr-5 tw:pl-4 tw:text-right tw:align-middle tw:whitespace-nowrap">
                <button type="button" onclick="abrirModalAsistenciasGrupo(${argsAsist})" title="Capturar asistencias del periodo"
                    class="tw:mr-1.5 tw:inline-flex tw:h-8 tw:cursor-pointer tw:items-center tw:gap-1.5 tw:border tw:border-slate-200 tw:bg-white tw:px-3 tw:text-xs tw:font-semibold tw:text-slate-600 tw:transition-colors tw:hover:bg-slate-50 tw:hover:text-slate-900">
                    <i class="ti ti-calendar-check"></i> Asistencias
                </button>
                <button type="button" onclick="abrirCapturaGrupoMateria(${id})" title="Capturar calificaciones por asignatura"
                    class="tw:inline-flex tw:h-8 tw:cursor-pointer tw:items-center tw:gap-1.5 tw:border-0 tw:bg-brand-700 tw:px-3 tw:text-xs tw:font-semibold tw:text-white tw:shadow-xs tw:transition-colors tw:hover:bg-brand-800">
                    <i class="ti ti-pencil"></i> Calificaciones
                </button>
            </td>
        </tr>`;
    }).join('');

    if (tbody) tbody.innerHTML = html;
    const conteo = document.getElementById('lblConteoGrupos');
    if (conteo) conteo.textContent = `${grupos.length} ${grupos.length === 1 ? 'grupo' : 'grupos'} de ${listaTodosGrupos.length}`;
}

// ==========================================
// MODAL DE CAPTURA POR MATERIA Y GRUPO
// ==========================================
function abrirCapturaGrupoMateria(idGrupo, idMateria = null) {
    grupoCapturaActualId = idGrupo;

    const modalEl = document.getElementById('modalCapturaMateriaGrupo');
    const loading = document.getElementById('loadingMateriaGrupo');
    const content = document.getElementById('contentCapturaMateriaGrupo');
    // Obtener o inicializar la instancia única del Modal
    let modalInstance = bootstrap.Modal.getInstance(modalEl);
    if (!modalInstance) {
        modalInstance = new bootstrap.Modal(modalEl, {
            backdrop: 'static',
            keyboard: true
        });
    }

    const yaAbierto = modalEl.classList.contains('show');

    if (!yaAbierto) {
        // Limpiar backdrops residuales antes de abrir
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');

        if (loading) loading.style.display = 'block';
        if (content) content.style.display = 'none';
        modalInstance.show();
    } else {
        // Si el modal ya está visible, no colapsamos el diálogo
        // Mostramos el spinner en el tbody de la tabla para una transición limpia
        const tbody = document.getElementById('tbodyAlumnosCalificacionesMateria');
        if (tbody) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="13" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <div class="text-muted mt-2 fw-semibold">Cargando datos de la asignatura...</div>
                    </td>
                </tr>
            `;
        }
    }

    const urlParams = new URLSearchParams(window.location.search);
    const idDocente = urlParams.get('id_docente');

    let url = `/grupos/${idGrupo}/calificaciones-materia`;
    const params = [];
    if (idMateria) params.push(`id_materia=${idMateria}`);
    if (idDocente) params.push(`id_docente=${idDocente}`);
    if (params.length > 0) {
        url += `?${params.join('&')}`;
    }

    fetch(url)
        .then(res => res.json())
        .then(res => {
            if (res.success && res.data) {
                datosGrupoMateriaActual = res.data;
                try {
                    renderDatosControlOficial(res.data);
                } catch (renderErr) {
                    console.error('Error renderizando datos del grupo/materia:', renderErr);
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: res.error || 'No se pudieron cargar los datos del grupo.',
                    confirmButtonColor: '#26687B'
                });
            }
        })
        .catch(err => {
            console.error('Error de red/servidor al obtener grupo:', err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de conexión al obtener información del grupo.',
                confirmButtonColor: '#26687B'
            });
        })
        .finally(() => {
            if (loading) loading.style.display = 'none';
            if (content) content.style.display = 'block';
        });
}

function cambiarMateriaSeleccionada(idMateria) {
    if (!grupoCapturaActualId) return;
    abrirCapturaGrupoMateria(grupoCapturaActualId, idMateria);
}

function cambiarPeriodoSeleccionado(idPeriodo) {
    if (!grupoCapturaActualId || !datosGrupoMateriaActual) return;
    const materias = datosGrupoMateriaActual.materias || [];
    const filtered = materias.filter(m => String(m.id_nivel_academico) === String(idPeriodo));
    if (filtered.length > 0) {
        // Seleccionar la primera materia del periodo elegido
        abrirCapturaGrupoMateria(grupoCapturaActualId, filtered[0].idMateria);
    } else {
        Swal.fire({
            icon: 'info',
            title: 'Sin asignaturas',
            text: 'No hay asignaturas registradas para este periodo en el grupo.',
            confirmButtonColor: '#26687B'
        });
    }
}

function renderDatosControlOficial(data) {
    const grupo = data.grupo || {};
    const materias = data.materias || [];
    const matSel = data.materiaSeleccionada || {};
    const rawAlumnos = data.alumnos || [];
    const alumnos = rawAlumnos.filter(a => !a.statusAlumno || a.statusAlumno.toUpperCase() === 'ACTIVO');

    const isSoloLectura = data.solo_lectura === true;
    const disAttr = isSoloLectura ? 'disabled' : '';

    const cctConfig = data.cct_config || {
        captura_p1: true,
        captura_p2: true,
        captura_p3: true,
        captura_semestral: true,
        captura_extraordinario: true
    };
    
    const isDocente = '{{ session("rol") }}' === 'DOCENTE';
    const disP1 = (isSoloLectura || (isDocente && !cctConfig.captura_p1)) ? 'disabled' : '';
    const disP2 = (isSoloLectura || (isDocente && !cctConfig.captura_p2)) ? 'disabled' : '';
    const disP3 = (isSoloLectura || (isDocente && !cctConfig.captura_p3)) ? 'disabled' : '';
    const disSem = (isSoloLectura || (isDocente && !cctConfig.captura_semestral)) ? 'disabled' : '';
    const disExt = (isSoloLectura || (isDocente && !cctConfig.captura_extraordinario)) ? 'disabled' : '';


    // Handle Solo Lectura Banner and Save Button
    const banner = document.getElementById('bannerSoloLectura');
    const mensajeBanner = document.getElementById('mensajeBannerSoloLectura');
    const btnGuardar = document.getElementById('btnGuardarCalifsMateria');

    if (isSoloLectura) {
        if (banner) {
            banner.style.display = 'block';
            mensajeBanner.textContent = data.mensaje_restriccion || 'Captura de calificaciones deshabilitada.';
        }
        if (btnGuardar) {
            btnGuardar.disabled = true;
            document.getElementById('wrapGuardarCalifs').style.display = 'none';
        }
    } else {
        if (banner) {
            banner.style.display = 'none';
        }
        if (btnGuardar) {
            btnGuardar.disabled = false;
            document.getElementById('wrapGuardarCalifs').style.display = '';
        }
    }

    // Renderizado del Calendario de Captura por Periodos
    const containerCal = document.getElementById('containerCalendarioPeriodos');
    const gridCal = document.getElementById('gridCalendarioPeriodos');
    
    if (containerCal && gridCal) {
        const fechas = data.grupo_fechas_limite;
        if (fechas) {
            // Estado de cada ventana respecto a hoy: abierta / próxima / cerrada / sin límite
            const hoyISO = new Date().toLocaleDateString('en-CA');
            const fmt = (iso) => new Date(iso + 'T00:00:00').toLocaleDateString('es-MX', { day: 'numeric', month: 'short' });
            const ventana = (nombre, inicio, fin) => {
                let estado, tono, rango;
                if (!inicio && !fin) {
                    estado = 'Sin límite'; tono = 'tw:bg-success-100 tw:text-success-700'; rango = 'Captura abierta';
                } else {
                    rango = `${inicio ? fmt(inicio) : '…'} – ${fin ? fmt(fin) : '…'}`;
                    if (inicio && hoyISO < inicio) { estado = 'Próxima'; tono = 'tw:bg-slate-100 tw:text-slate-600'; }
                    else if (fin && hoyISO > fin) { estado = 'Cerrada'; tono = 'tw:bg-danger-100 tw:text-danger-600'; }
                    else { estado = 'Abierta'; tono = 'tw:bg-success-100 tw:text-success-700'; }
                }
                return `
                    <div class=" tw:border tw:border-slate-200 tw:bg-white tw:px-3 tw:py-2">
                        <div class="tw:flex tw:items-center tw:justify-between tw:gap-2">
                            <span class="tw:truncate tw:text-xs tw:font-semibold tw:text-slate-800">${nombre}</span>
                            <span class="tw:shrink-0 tw:rounded-none tw:px-2 tw:py-0.5 tw:text-[0.625rem] tw:font-semibold ${tono}">${estado}</span>
                        </div>
                        <div class="tw:mt-0.5 tw:text-[0.6875rem] tw:text-slate-500">${rango}</div>
                    </div>`;
            };

            const isBgne = parseInt(grupo.id_centroTrabajo) === 3;
            const isSemestralCal = matSel.tipoEvaluacion && matSel.tipoEvaluacion.toUpperCase() === 'SEMESTRAL';
            let ventanas;
            if (isBgne) {
                ventanas = [['Calificación final', 'p1'], ['Extraordinario', 'extraordinario']];
            } else if (isSemestralCal) {
                ventanas = [['1er parcial', 'p1'], ['2do parcial', 'p2'], ['3er parcial', 'p3'], ['Semestral / final', 'semestral'], ['Extraordinario', 'extraordinario']];
            } else {
                ventanas = [['1er parcial', 'p1'], ['2do parcial', 'p2'], ['3er parcial', 'p3']];
            }
            gridCal.innerHTML = ventanas.map(([n, k]) => ventana(n, fechas[`${k}_inicio`], fechas[`${k}_fin`])).join('');
            containerCal.style.display = 'block';
        } else {
            containerCal.style.display = 'none';
        }
    }

    // Membrete
    document.getElementById('boxNombreInstitucion').textContent = grupo.nombreCentroTrabajo || 'BACHILLERATO INTERAMERICANO';
    document.getElementById('badgeClaveGrupoOficial').textContent = grupo.clave || 'GRUPO';

    // 1. Obtener periodos únicos de las materias
    const isBti = (parseInt(grupo.id_centroTrabajo) === 2 || (grupo.nombreCentroTrabajo || '').toUpperCase().includes('BTI'));
    const labelPeriodo = document.getElementById('labelPeriodoCaptura');
    if (labelPeriodo) {
        labelPeriodo.textContent = isBti ? 'Semestre' : 'Módulo';
    }

    const uniqueLevels = [];
    const levelIds = new Set();

    materias.forEach(m => {
        const lvlId = m.id_nivel_academico || (isBti ? null : grupo.id_nivel_academico);
        if (lvlId && !levelIds.has(lvlId)) {
            levelIds.add(lvlId);
            
            let lvlNombre = m.nombreNivel;
            let lvlNumero = m.numeroNivel;
            if (!m.id_nivel_academico) {
                lvlNombre = grupo.nombreNivel || `Nivel ${lvlId}`;
                lvlNumero = grupo.id_nivel_academico ? (grupo.id_nivel_academico <= 6 ? grupo.id_nivel_academico : grupo.id_nivel_academico - 6) : 1;
            }
            
            uniqueLevels.push({
                id: lvlId,
                nombre: lvlNombre || `Nivel ${lvlId}`,
                numero: lvlNumero || 1
            });
        }
    });

    // Ordenar periodos por su número secuencial
    uniqueLevels.sort((a, b) => a.numero - b.numero);

    // Poblar Selector de Periodos
    const selectPeriodo = document.getElementById('selectPeriodoCaptura');
    selectPeriodo.innerHTML = '';
    
    if (uniqueLevels.length === 0) {
        const opt = document.createElement('option');
        opt.value = grupo.id_nivel_academico || '';
        opt.textContent = grupo.nombreNivel || '—';
        selectPeriodo.appendChild(opt);
    } else {
        uniqueLevels.forEach(lvl => {
            const opt = document.createElement('option');
            opt.value = lvl.id;
            opt.textContent = lvl.nombre;
            selectPeriodo.appendChild(opt);
        });
    }

    if ('{{ session("rol") }}' === 'DOCENTE') {
        selectPeriodo.disabled = true;
    } else {
        selectPeriodo.disabled = false;
    }

    // Seleccionar el periodo correspondiente a la materia seleccionada o nivel del grupo
    const currentPeriodId = matSel.id_nivel_academico || grupo.id_nivel_academico;
    if (currentPeriodId) {
        selectPeriodo.value = currentPeriodId;
    }

    // 2. Filtrar materias pertenecientes al periodo seleccionado
    const activePeriodId = selectPeriodo.value;
    const filteredMaterias = materias.filter(m => {
        const lvlId = m.id_nivel_academico || (isBti ? null : grupo.id_nivel_academico);
        return String(lvlId) === String(activePeriodId);
    });

    // Si la materia seleccionada no está dentro de este periodo filtrado, auto-seleccionar la primera y recargar
    if (filteredMaterias.length > 0) {
        const idMatActual = data.idMateriaSeleccionada || (matSel ? matSel.idMateria : null);
        const isCurrentMatInPeriod = filteredMaterias.some(m => String(m.idMateria) === String(idMatActual));
        if (!isCurrentMatInPeriod && filteredMaterias[0]) {
            setTimeout(() => {
                abrirCapturaGrupoMateria(grupoCapturaActualId, filteredMaterias[0].idMateria);
            }, 0);
            return;
        }
    }

    // Poblar Selector de Materias
    const selectMat = document.getElementById('selectMateriaGrupo');
    selectMat.innerHTML = '';

    if (filteredMaterias.length === 0) {
        selectMat.innerHTML = '<option value="">No hay materias registradas</option>';
    } else {
        filteredMaterias.forEach(m => {
            const opt = document.createElement('option');
            opt.value = m.idMateria;
            opt.textContent = m.nombreMateria;
            if (String(m.idMateria) === String(data.idMateriaSeleccionada)) {
                opt.selected = true;
            }
            selectMat.appendChild(opt);
        });
    }

    // Docente de la materia
    const docenteNombre = matSel.nombreDocente || 'Sin docente asignado';
    const nivelDoc = matSel.nivelEstudios ? `${matSel.nivelEstudios}. ` : '';
    document.getElementById('boxNombreDocente').textContent = docenteNombre !== 'Sin docente asignado' ? `${nivelDoc}${docenteNombre}` : docenteNombre;

    // Fechas y modalidad
    document.getElementById('boxFechaInicioGrupo').textContent = formatearFecha(grupo.fechaInicio);
    document.getElementById('boxFechaFinGrupo').textContent = formatearFecha(grupo.fechaFin);

    let modalidad = (grupo.modalidadHorario || '').toUpperCase();
    if (!modalidad) {
        if ((grupo.clave || '').toUpperCase().endsWith('S')) modalidad = 'SISTEMA SABATINO';
        else if ((grupo.clave || '').toUpperCase().endsWith('D')) modalidad = 'SISTEMA DOMINGO';
        else modalidad = 'SISTEMA ESCOLARIZADO';
    }
    document.getElementById('boxModalidadGrupo').textContent = modalidad;

    // Detectar si el grupo es Semestral (BTI o Informatica)
    const isSemestral = (grupo.id_tipoPeriodo === 1 || grupo.id_centroTrabajo === 2 || grupo.id_centroTrabajo === 1);
    const mostrarAsistencias = false;

    // Ajustar thead dinámicamente
    const thead = document.getElementById('theadCalificacionesOficial');
    if (isSemestral) {
        thead.innerHTML = `
            <tr>
                <th style="width: 44px;">#</th>
                <th style="width: 140px;">Ap. paterno</th>
                <th style="width: 140px;">Ap. materno</th>
                <th style="width: 170px;">Nombre(s)</th>
                <th style="width: 78px;">Parcial 1</th>
                <th style="width: 78px;">Parcial 2</th>
                <th style="width: 78px;">Parcial 3</th>
                <th style="width: 82px;">Semestral</th>
                <th style="width: 82px; background: #FFFBEB; color: #B45309;">Extraord.</th>
                <th style="width: 120px; background: #EFF6F7; color: #26687B;">Final</th>
                <th style="width: 120px;">Con letra</th>
                ${mostrarAsistencias ? '<th style="width: 120px;">Asistencias</th>' : ''}
                <th style="min-width: 160px; text-align: left;">Observaciones</th>
            </tr>
        `;
    } else {
        thead.innerHTML = `
            <tr>
                <th style="width: 44px;">#</th>
                <th style="width: 150px;">Ap. paterno</th>
                <th style="width: 150px;">Ap. materno</th>
                <th style="width: 180px;">Nombre(s)</th>
                <th style="width: 130px; background: #EFF6F7; color: #26687B;">Calificación final</th>
                <th style="width: 100px; background: #FFFBEB; color: #B45309;">Extraord.</th>
                <th style="width: 130px;">Con letra</th>
                <th style="min-width: 180px; text-align: left;">Observaciones</th>
            </tr>
        `;
    }

    // Poblar Tabla de Alumnos
    const tbody = document.getElementById('tbodyAlumnosCalificacionesMateria');
    if (!alumnos.length) {
        tbody.innerHTML = `<tr><td colspan="${isSemestral ? (mostrarAsistencias ? 13 : 12) : 8}" class="text-center py-4 text-muted">No hay alumnos inscritos en este grupo.</td></tr>`;
        document.getElementById('statTotalAlumnos').textContent = '0';
        document.getElementById('statAprobados').textContent = '0';
        document.getElementById('statReprobados').textContent = '0';
        document.getElementById('statPromedioMateria').textContent = 'Promedio 0.0';
        const tfoot = document.getElementById('tfootCalificacionesOficial');
        if (tfoot) tfoot.innerHTML = '';
        return;
    }

    let html = '';
    alumnos.forEach((a, idx) => {
        const calif = a.calificacion !== null && a.calificacion !== undefined ? a.calificacion : '';
        const obs = a.observaciones || '';
        const isEquiv = a.es_equivalencia === true;
        const califLetra = isEquiv ? 'EQUIVALENCIA' : numeroALetrasCalificacion(calif);

        let p1Input = '';
        let p2Input = '';
        let p3Input = '';
        let semInput = '';
        let extInput = '';
        let finalInput = '';
        let asistInput = '';

        if (isEquiv) {
            p1Input = `<span class="chip-equiv">EQUIV.</span>`;
            p2Input = `<span class="chip-equiv">EQUIV.</span>`;
            p3Input = `<span class="chip-equiv">EQUIV.</span>`;
            semInput = `<span class="chip-equiv">EQUIV.</span>`;
            extInput = `<span class="chip-equiv">EQUIV.</span>`;
            asistInput = `<span class="chip-equiv">EQUIV.</span>`;
            finalInput = `
                <div class="d-flex align-items-center justify-content-center gap-1">
                    <span class="chip-equiv">EQUIV.</span>
                    <button type="button" class="btn-equiv-undo" title="Cambiar a calificación numérica" onclick="conmutarEquivalenciaFila(this.closest('tr'), false)" ${disAttr}>
                        <i class="ti ti-pencil"></i>
                    </button>
                    <input type="hidden" class="inp-calif-final" value="EQUIV">
                </div>
            `;
        } else {
            if (isSemestral) {
                const intP1 = (a.parcial1 !== null && a.parcial1 !== undefined && a.parcial1 !== '') ? parseInt(a.parcial1) : '';
                const intP2 = (a.parcial2 !== null && a.parcial2 !== undefined && a.parcial2 !== '') ? parseInt(a.parcial2) : '';
                const intP3 = (a.parcial3 !== null && a.parcial3 !== undefined && a.parcial3 !== '') ? parseInt(a.parcial3) : '';
                const intSem = (a.semestral !== null && a.semestral !== undefined && a.semestral !== '') ? parseInt(a.semestral) : '';
                const intExtSem = (a.extraordinario !== null && a.extraordinario !== undefined && a.extraordinario !== '') ? parseInt(a.extraordinario) : '';
                const intFinalSem = (calif !== '' && !isNaN(calif)) ? parseInt(calif) : '';

                p1Input = `<input type="number" step="1" min="0" max="10" class="input-calif-celda inp-p1" value="${intP1}" onkeydown="prevenirDecimales(event)" oninput="recalcularFilaSemestral(this)" placeholder="–" ${disP1}>`;
                p2Input = `<input type="number" step="1" min="0" max="10" class="input-calif-celda inp-p2" value="${intP2}" onkeydown="prevenirDecimales(event)" oninput="recalcularFilaSemestral(this)" placeholder="–" ${disP2}>`;
                p3Input = `<input type="number" step="1" min="0" max="10" class="input-calif-celda inp-p3" value="${intP3}" onkeydown="prevenirDecimales(event)" oninput="recalcularFilaSemestral(this)" placeholder="–" ${disP3}>`;
                semInput = `<input type="number" step="1" min="0" max="10" class="input-calif-celda inp-semestral" value="${intSem}" onkeydown="prevenirDecimales(event)" oninput="recalcularFilaSemestral(this)" placeholder="–" ${disSem}>`;
                extInput = `<input type="number" step="1" min="0" max="10" class="input-calif-celda inp-extraordinario" value="${intExtSem}" onkeydown="prevenirDecimales(event)" oninput="recalcularFilaSemestral(this)" placeholder="–" ${disExt}>`;
                finalInput = `
                    <div class="d-flex align-items-center justify-content-center gap-1">
                        <input type="number" step="1" min="0" max="10" class="input-calif-celda inp-calif-final fw-bold" value="${intFinalSem}" readonly ${disAttr}>
                        <button type="button" class="btn-equiv" title="Marcar como Equivalencia (alumno transferido)" onclick="conmutarEquivalenciaFila(this.closest('tr'), true)" ${disAttr}>
                            EQUIV
                        </button>
                    </div>
                `;
                
                const aVal = a.asistencias !== null && a.asistencias !== undefined ? a.asistencias : '';
                const tVal = a.total_asistencias !== null && a.total_asistencias !== undefined ? a.total_asistencias : '';
                asistInput = `
                    <div class="d-flex align-items-center justify-content-center gap-1">
                        <input type="number" min="0" class="input-calif-celda inp-asistencias" value="${aVal}" style="width: 45px;" placeholder="0" oninput="recalcularEstadisticasMateria()" ${disAttr}>
                        <span class="text-muted">/</span>
                        <input type="number" min="0" class="input-calif-celda inp-total-asistencias" value="${tVal}" style="width: 45px;" placeholder="0" oninput="recalcularEstadisticasMateria()" ${disAttr}>
                    </div>
                `;
            } else {
                // Para BGNE: Solo Final y Extraordinario (enteros del 0 al 10)
                const intCalif = (calif !== '' && !isNaN(calif)) ? parseInt(calif) : '';
                const intExt = (a.extraordinario !== null && a.extraordinario !== undefined && a.extraordinario !== '' && !isNaN(a.extraordinario)) ? parseInt(a.extraordinario) : '';
                finalInput = `
                    <div class="d-flex align-items-center justify-content-center gap-1">
                        <input type="number" step="1" min="0" max="10" class="input-calif-celda inp-calif-final fw-bold" value="${intCalif}" onkeydown="prevenirDecimales(event)" oninput="recalcularFilaOficial(this)" placeholder="–" ${disP1}>
                        <button type="button" class="btn-equiv" title="Marcar alumno con EQUIVALENCIA" onclick="conmutarEquivalenciaFila(this.closest('tr'), true)" ${disP1}>
                            EQUIV
                        </button>
                    </div>
                `;
                extInput = `<input type="number" step="1" min="0" max="10" class="input-calif-celda inp-extraordinario" value="${intExt}" onkeydown="prevenirDecimales(event)" oninput="recalcularFilaOficial(this)" placeholder="–" ${disExt}>`;
            }
        }

        if (isSemestral) {
            html += `
            <tr data-alumno-id="${a.idAlumno}" data-is-equivalencia="${isEquiv}">
                <td class="text-center fw-bold">${idx + 1}</td>
                <td class="fw-semibold">${escHtml(a.apPaterno)}</td>
                <td class="fw-semibold">${escHtml(a.apMaterno)}</td>
                <td class="fw-bold text-dark">${escHtml(a.nombre)}</td>
                
                <td style="text-align: center;">${p1Input}</td>
                <td style="text-align: center;">${p2Input}</td>
                <td style="text-align: center;">${p3Input}</td>
                <td style="text-align: center;">${semInput}</td>
                <td style="background-color: #FFFBEB; text-align: center;">${extInput}</td>
                <td style="background-color: #EFF6F7; text-align: center;">${finalInput}</td>
                <td class="text-center fw-bold td-calif-letra" style="font-size: 0.78rem;">${califLetra}</td>
                ${mostrarAsistencias ? `<td class="td-asistencias" style="background-color: #f8fafc; text-align: center;">${asistInput}</td>` : ''}
                <td><input type="text" class="input-observaciones-celda inp-obs" value="${escHtml(obs)}" placeholder="Opcional..." ${isEquiv ? 'disabled' : ''} ${disAttr}></td>
            </tr>
            `;
        } else {
            html += `
            <tr data-alumno-id="${a.idAlumno}" data-is-equivalencia="${isEquiv}">
                <td class="text-center fw-bold">${idx + 1}</td>
                <td class="fw-semibold">${escHtml(a.apPaterno)}</td>
                <td class="fw-semibold">${escHtml(a.apMaterno)}</td>
                <td class="fw-bold text-dark">${escHtml(a.nombre)}</td>
                
                <td style="background-color: #EFF6F7; text-align: center;">${finalInput}</td>
                <td style="background-color: #FFFBEB; text-align: center;">${extInput}</td>
                <td class="text-center fw-bold td-calif-letra" style="font-size: 0.78rem;">${califLetra}</td>
                <td><input type="text" class="input-observaciones-celda inp-obs" value="${escHtml(obs)}" placeholder="Opcional..." ${isEquiv ? 'disabled' : ''} ${disAttr}></td>
            </tr>
            `;
        }
    });

    tbody.innerHTML = html;

    // Recalcular estilos y estadísticas iniciales
    tbody.querySelectorAll('tr').forEach(tr => {
        const isEquiv = tr.getAttribute('data-is-equivalencia') === 'true';
        if (!isEquiv) {
            if (isSemestral) {
                const inpP1 = tr.querySelector('.inp-p1');
                if (inpP1) recalcularFilaSemestral(inpP1);
            } else {
                const inpFinal = tr.querySelector('.inp-calif-final');
                if (inpFinal) recalcularFilaOficial(inpFinal);
            }
        } else {
            const tdLetra = tr.querySelector('.td-calif-letra');
            if (tdLetra) {
                tdLetra.className = 'text-center fw-bold td-calif-letra tw:text-warning-600';
            }
        }
    });

    recalcularEstadisticasMateria();
}

// ==========================================
// CONMUTACIÓN MANUAL DE EQUIVALENCIA
// ==========================================
function conmutarEquivalenciaFila(tr, aEquiv) {
    if (!tr) return;
    const isSoloLectura = datosGrupoMateriaActual && datosGrupoMateriaActual.solo_lectura === true;
    if (isSoloLectura) return;

    const isSemestral = datosGrupoMateriaActual && datosGrupoMateriaActual.grupo && (
        datosGrupoMateriaActual.grupo.id_tipoPeriodo === 1 ||
        datosGrupoMateriaActual.grupo.id_centroTrabajo === 2 ||
        datosGrupoMateriaActual.grupo.id_centroTrabajo === 1
    );

    const cctConfig = (datosGrupoMateriaActual && datosGrupoMateriaActual.cct_config) ? datosGrupoMateriaActual.cct_config : {
        captura_p1: true, captura_p2: true, captura_p3: true, captura_semestral: true, captura_extraordinario: true
    };
    const isDocente = '{{ session("rol") }}' === 'DOCENTE';
    const disP1 = (isSoloLectura || (isDocente && !cctConfig.captura_p1)) ? 'disabled' : '';
    const disP2 = (isSoloLectura || (isDocente && !cctConfig.captura_p2)) ? 'disabled' : '';
    const disP3 = (isSoloLectura || (isDocente && !cctConfig.captura_p3)) ? 'disabled' : '';
    const disSem = (isSoloLectura || (isDocente && !cctConfig.captura_semestral)) ? 'disabled' : '';
    const disExt = (isSoloLectura || (isDocente && !cctConfig.captura_extraordinario)) ? 'disabled' : '';
    const disAttr = isSoloLectura ? 'disabled' : '';

    const tdLetra = tr.querySelector('.td-calif-letra');
    const tdAsist = tr.querySelector('.td-asistencias');
    const inpObs = tr.querySelector('.inp-obs');

    if (aEquiv) {
        tr.setAttribute('data-is-equivalencia', 'true');
        
        if (isSemestral) {
            tr.children[4].innerHTML = `<span class="chip-equiv">EQUIV.</span>`;
            tr.children[5].innerHTML = `<span class="chip-equiv">EQUIV.</span>`;
            tr.children[6].innerHTML = `<span class="chip-equiv">EQUIV.</span>`;
            tr.children[7].innerHTML = `<span class="chip-equiv">EQUIV.</span>`;
            tr.children[8].innerHTML = `<span class="chip-equiv">EQUIV.</span>`;
            tr.children[9].innerHTML = `
                <div class="d-flex align-items-center justify-content-center gap-1">
                    <span class="chip-equiv">EQUIV.</span>
                    <button type="button" class="btn-equiv-undo" title="Cambiar a calificación numérica" onclick="conmutarEquivalenciaFila(this.closest('tr'), false)" ${disAttr}>
                        <i class="ti ti-pencil"></i>
                    </button>
                    <input type="hidden" class="inp-calif-final" value="EQUIV">
                </div>
            `;
            if (tdAsist) {
                tdAsist.innerHTML = `<span class="chip-equiv">EQUIV.</span>`;
            }
        } else {
            // BGNE (children[4] es Final, children[5] es Extraordinario)
            tr.children[4].innerHTML = `
                <div class="d-flex align-items-center justify-content-center gap-1">
                    <span class="chip-equiv">EQUIV.</span>
                    <button type="button" class="btn-equiv-undo" title="Cambiar a calificación numérica" onclick="conmutarEquivalenciaFila(this.closest('tr'), false)" ${disAttr}>
                        <i class="ti ti-pencil"></i>
                    </button>
                    <input type="hidden" class="inp-calif-final" value="EQUIV">
                </div>
            `;
            tr.children[5].innerHTML = `<span class="text-muted fw-bold">—</span><input type="hidden" class="inp-extraordinario" value="">`;
        }

        if (tdLetra) {
            tdLetra.textContent = 'EQUIVALENCIA';
            tdLetra.className = 'text-center fw-bold td-calif-letra tw:text-warning-600';
        }
    } else {
        tr.setAttribute('data-is-equivalencia', 'false');

        if (isSemestral) {
            tr.children[4].innerHTML = `<input type="number" step="1" min="0" max="10" class="input-calif-celda inp-p1" value="" onkeydown="prevenirDecimales(event)" oninput="recalcularFilaSemestral(this)" placeholder="–" ${disP1}>`;
            tr.children[5].innerHTML = `<input type="number" step="1" min="0" max="10" class="input-calif-celda inp-p2" value="" onkeydown="prevenirDecimales(event)" oninput="recalcularFilaSemestral(this)" placeholder="–" ${disP2}>`;
            tr.children[6].innerHTML = `<input type="number" step="1" min="0" max="10" class="input-calif-celda inp-p3" value="" onkeydown="prevenirDecimales(event)" oninput="recalcularFilaSemestral(this)" placeholder="–" ${disP3}>`;
            tr.children[7].innerHTML = `<input type="number" step="1" min="0" max="10" class="input-calif-celda inp-semestral" value="" onkeydown="prevenirDecimales(event)" oninput="recalcularFilaSemestral(this)" placeholder="–" ${disSem}>`;
            tr.children[8].innerHTML = `<input type="number" step="1" min="0" max="10" class="input-calif-celda inp-extraordinario" value="" onkeydown="prevenirDecimales(event)" oninput="recalcularFilaSemestral(this)" placeholder="–" ${disExt}>`;
            tr.children[9].innerHTML = `
                <div class="d-flex align-items-center justify-content-center gap-1">
                    <input type="number" step="1" min="0" max="10" class="input-calif-celda inp-calif-final fw-bold" value="" readonly ${disAttr}>
                    <button type="button" class="btn-equiv" title="Marcar como Equivalencia (alumno transferido)" onclick="conmutarEquivalenciaFila(this.closest('tr'), true)" ${disAttr}>
                        EQUIV
                    </button>
                </div>
            `;
            if (tdAsist) {
                tdAsist.innerHTML = `
                    <div class="d-flex align-items-center justify-content-center gap-1">
                        <input type="number" min="0" class="input-calif-celda inp-asistencias" value="" style="width: 45px;" placeholder="0" oninput="recalcularEstadisticasMateria()" ${disAttr}>
                        <span class="text-muted">/</span>
                        <input type="number" min="0" class="input-calif-celda inp-total-asistencias" value="" style="width: 45px;" placeholder="0" oninput="recalcularEstadisticasMateria()" ${disAttr}>
                    </div>
                `;
            }
        } else {
            tr.children[4].innerHTML = `
                <div class="d-flex align-items-center justify-content-center gap-1">
                    <input type="number" step="1" min="0" max="10" class="input-calif-celda inp-calif-final fw-bold" value="" onkeydown="prevenirDecimales(event)" oninput="recalcularFilaOficial(this)" placeholder="–" ${disP1}>
                    <button type="button" class="btn-equiv" title="Marcar alumno con EQUIVALENCIA" onclick="conmutarEquivalenciaFila(this.closest('tr'), true)" ${disP1}>
                        EQUIV
                    </button>
                </div>
            `;
            tr.children[5].innerHTML = `<input type="number" step="1" min="0" max="10" class="input-calif-celda inp-extraordinario" value="" onkeydown="prevenirDecimales(event)" oninput="recalcularFilaOficial(this)" placeholder="–" ${disExt}>`;
        }

        if (tdLetra) {
            tdLetra.textContent = '—';
            tdLetra.className = 'text-center fw-bold td-calif-letra tw:text-slate-500';
        }
    }

    if (inpObs) inpObs.disabled = isSoloLectura;
    recalcularEstadisticasMateria();
}

function validarRangoInput(inputEl) {
    if (!inputEl) return true;

    // Si es un campo de asistencias, no validar rango 0-10 de calificaciones
    if (inputEl.classList.contains('inp-asistencias') || inputEl.classList.contains('inp-total-asistencias')) {
        inputEl.style.borderColor = '';
        inputEl.style.backgroundColor = '';
        return true;
    }

    const cctId = datosGrupoMateriaActual && datosGrupoMateriaActual.grupo ? datosGrupoMateriaActual.grupo.id_centroTrabajo : null;
    const enforceRange = (cctId === 2 || cctId === 3);
    if (!enforceRange) return true;

    if (inputEl.value === "") {
        inputEl.style.borderColor = '';
        inputEl.style.backgroundColor = '';
        return true;
    }

    const val = parseFloat(inputEl.value);
    // Solo enteros del 0 al 10
    if (isNaN(val) || val < 0.0 || val > 10.0 || !Number.isInteger(val)) {
        inputEl.style.borderColor = '#dc2626';
        inputEl.style.backgroundColor = '#fef2f2';
        return false;
    } else {
        inputEl.style.borderColor = '';
        inputEl.style.backgroundColor = '';
        return true;
    }
}

function recalcularFilaSemestral(inputEl) {
    validarRangoInput(inputEl);
    const tr = inputEl.closest('tr');
    if (!tr) return;

    const p1Inp = tr.querySelector('.inp-p1');
    const p2Inp = tr.querySelector('.inp-p2');
    const p3Inp = tr.querySelector('.inp-p3');
    const semInp = tr.querySelector('.inp-semestral');
    const extInp = tr.querySelector('.inp-extraordinario');
    const pFinalInp = tr.querySelector('.inp-calif-final');
    const tdLetra = tr.querySelector('.td-calif-letra');

    const v1 = parseFloat(p1Inp ? p1Inp.value : NaN);
    const v2 = parseFloat(p2Inp ? p2Inp.value : NaN);
    const v3 = parseFloat(p3Inp ? p3Inp.value : NaN);

    const config = (datosGrupoMateriaActual && datosGrupoMateriaActual.cct_config) ? datosGrupoMateriaActual.cct_config : {
        captura_p1: true,
        captura_p2: true,
        captura_p3: true,
        captura_semestral: true,
        captura_extraordinario: true
    };
    const isSoloLectura = datosGrupoMateriaActual && datosGrupoMateriaActual.solo_lectura === true;
    const isDocente = '{{ session("rol") }}' === 'DOCENTE';

    // Determinar habilitación de parciales para docente
    if (p1Inp && isDocente) {
        p1Inp.disabled = isSoloLectura || !config.captura_p1;
    }
    if (p2Inp && isDocente) {
        p2Inp.disabled = isSoloLectura || !config.captura_p2;
    }
    if (p3Inp && isDocente) {
        p3Inp.disabled = isSoloLectura || !config.captura_p3;
    }

    // Determinar habilitación de semestral y extraordinario
    if (semInp) {
        if (!isDocente) {
            // Como administrador siempre se puede capturar semestral sin bloqueos
            semInp.disabled = isSoloLectura;
        } else {
            // Para docente depende del calendario oficial
            semInp.disabled = isSoloLectura || !config.captura_semestral;
        }
        semInp.placeholder = "–";
    }

    if (extInp) {
        if (!isDocente) {
            // Como administrador siempre se puede capturar extraordinario
            extInp.disabled = isSoloLectura;
        } else {
            // Para docente depende del calendario oficial
            extInp.disabled = isSoloLectura || !config.captura_extraordinario;
        }
        extInp.placeholder = "–";
    }

    const semVal = semInp && semInp.value !== '' ? parseFloat(semInp.value) : NaN;
    const extVal = extInp && extInp.value !== '' ? parseFloat(extInp.value) : NaN;

    // Cálculo del promedio final
    if (!isNaN(extVal) && pFinalInp) {
        // 1. Si capturó examen extraordinario, promedio final es extraordinario (tope 7)
        pFinalInp.value = Math.min(Math.round(extVal), 7);
    } else if (!isNaN(semVal) && pFinalInp) {
        // 2. Si capturó examen semestral, promedio de parciales capturados + semestral
        const partials = [v1, v2, v3].filter(v => !isNaN(v));
        if (partials.length > 0) {
            const count = partials.length === 3 ? 4 : (partials.length + 1);
            pFinalInp.value = Math.round((partials.reduce((a, b) => a + b, 0) + semVal) / count);
        } else {
            pFinalInp.value = Math.round(semVal);
        }
    } else if (pFinalInp) {
        // 3. Si no hay semestral ni extraordinario, promedio provisional de los parciales capturados
        const partials = [v1, v2, v3].filter(v => !isNaN(v));
        if (partials.length > 0) {
            pFinalInp.value = Math.round(partials.reduce((a, b) => a + b, 0) / partials.length);
        } else {
            pFinalInp.value = "";
        }
    }

    // Color rojo en notas menores a 6
    const checkRed = (inp) => {
        if (!inp) return;
        const v = parseFloat(inp.value);
        if (!isNaN(v) && v < 6.0) {
            inp.style.color = '#dc2626';
            inp.style.fontWeight = 'bold';
        } else {
            inp.style.color = '#0f172a';
            inp.style.fontWeight = '700';
        }
    };

    checkRed(p1Inp);
    checkRed(p2Inp);
    checkRed(p3Inp);
    checkRed(semInp);
    checkRed(extInp);
    checkRed(pFinalInp);

    // Actualizar Calificación con Letra
    const finalVal = pFinalInp ? pFinalInp.value : '';
    if (tdLetra) {
        tdLetra.textContent = numeroALetrasCalificacion(finalVal);
        if (parseFloat(finalVal) < 6.0) {
            tdLetra.className = 'text-center fw-bold td-calif-letra tw:text-danger-600';
        } else {
            tdLetra.className = 'text-center fw-bold td-calif-letra tw:text-slate-500';
        }
    }

    recalcularEstadisticasMateria();
}

function recalcularFilaOficial(inputEl) {
    validarRangoInput(inputEl);
    const tr = inputEl.closest('tr');
    const isEquiv = tr.getAttribute('data-is-equivalencia') === 'true';
    if (isEquiv) return;

    const p1Inp = tr.querySelector('.inp-p1');
    const p2Inp = tr.querySelector('.inp-p2');
    const p3Inp = tr.querySelector('.inp-p3');
    const extInp = tr.querySelector('.inp-extraordinario');
    const pFinalInp = tr.querySelector('.inp-calif-final');
    const tdLetra = tr.querySelector('.td-calif-letra');

    // Si se escriben parciales y no final manual, calcular promedio de parciales en final
    if (inputEl !== pFinalInp) {
        const v1 = p1Inp ? parseFloat(p1Inp.value) : NaN;
        const v2 = p2Inp ? parseFloat(p2Inp.value) : NaN;
        const v3 = p3Inp ? parseFloat(p3Inp.value) : NaN;
        let vals = [];
        if (!isNaN(v1)) vals.push(v1);
        if (!isNaN(v2)) vals.push(v2);
        if (!isNaN(v3)) vals.push(v3);

        if (vals.length > 0 && pFinalInp) {
            const prom = Math.round(vals.reduce((a, b) => a + b, 0) / vals.length);
            pFinalInp.value = prom;
        } else if (pFinalInp) {
            pFinalInp.value = "";
        }
    }

    // Color rojo en notas menores a 6.0
    const checkRed = (inp) => {
        if (!inp) return;
        const v = parseFloat(inp.value);
        if (!isNaN(v) && v < 6.0) {
            inp.style.color = '#dc2626';
            inp.style.fontWeight = 'bold';
        } else {
            inp.style.color = '#0f172a';
            inp.style.fontWeight = '700';
        }
    };

    checkRed(p1Inp);
    checkRed(p2Inp);
    checkRed(p3Inp);
    checkRed(extInp);
    checkRed(pFinalInp);

    // Actualizar Calificación con Letra
    let finalVal = pFinalInp ? pFinalInp.value : '';
    if (extInp && extInp.value !== "") {
        finalVal = extInp.value;
    }
    if (tdLetra) {
        tdLetra.textContent = numeroALetrasCalificacion(finalVal);
        if (parseFloat(finalVal) < 6.0) {
            tdLetra.className = 'text-center fw-bold td-calif-letra tw:text-danger-600';
        } else {
            tdLetra.className = 'text-center fw-bold td-calif-letra tw:text-slate-500';
        }
    }

    recalcularEstadisticasMateria();
}

function recalcularEstadisticasMateria() {
    const rows = document.querySelectorAll('#tbodyAlumnosCalificacionesMateria tr');
    const isSemestral = datosGrupoMateriaActual && datosGrupoMateriaActual.grupo && (
        datosGrupoMateriaActual.grupo.id_tipoPeriodo === 1 ||
        datosGrupoMateriaActual.grupo.id_centroTrabajo === 2 ||
        datosGrupoMateriaActual.grupo.id_centroTrabajo === 1
    );

    let total = 0;
    let aprobados = 0;
    let reprobados = 0;
    
    // Arrays to collect column grades
    let p1Vals = [];
    let p2Vals = [];
    let p3Vals = [];
    let semVals = [];
    let extVals = [];
    let finalVals = [];

    rows.forEach(tr => {
        const isEquiv = tr.getAttribute('data-is-equivalencia') === 'true';
        if (isEquiv) return;

        const p1Inp = tr.querySelector('.inp-p1');
        const p2Inp = tr.querySelector('.inp-p2');
        const p3Inp = tr.querySelector('.inp-p3');
        const semInp = tr.querySelector('.inp-semestral');
        const extInp = tr.querySelector('.inp-extraordinario');
        const inpFinal = tr.querySelector('.inp-calif-final');

        if (!inpFinal) return;
        total++;

        // Collect values
        if (p1Inp) { const v = parseFloat(p1Inp.value); if (!isNaN(v)) p1Vals.push(v); }
        if (p2Inp) { const v = parseFloat(p2Inp.value); if (!isNaN(v)) p2Vals.push(v); }
        if (p3Inp) { const v = parseFloat(p3Inp.value); if (!isNaN(v)) p3Vals.push(v); }
        if (semInp) { const v = parseFloat(semInp.value); if (!isNaN(v)) semVals.push(v); }
        if (extInp) { const v = parseFloat(extInp.value); if (!isNaN(v)) extVals.push(v); }
        
        // Calificación efectiva: en BGNE, si hay extraordinario capturado es la que cuenta
        // (coincide con la "calificación con letra" que muestra la fila)
        let val = parseFloat(inpFinal.value);
        if (!isSemestral && extInp && extInp.value !== '') val = parseFloat(extInp.value);
        if (!isNaN(val)) {
            finalVals.push(val);
            if (val >= 6.0) aprobados++;
            else reprobados++;
        }
    });

    const totalEquiv = document.querySelectorAll('#tbodyAlumnosCalificacionesMateria tr[data-is-equivalencia="true"]').length;

    document.getElementById('statTotalAlumnos').textContent = total + totalEquiv;
    document.getElementById('statAprobados').textContent = aprobados;
    document.getElementById('statReprobados').textContent = reprobados;
    const elEq = document.getElementById('statEquivalencias');
    if (elEq) elEq.textContent = totalEquiv;

    const prom = finalVals.length > 0 ? (finalVals.reduce((a, b) => a + b, 0) / finalVals.length).toFixed(1) : '—';
    document.getElementById('statPromedioMateria').textContent = `Promedio ${prom}`;

    // Fila de promedios por columna (alineada con el encabezado de cada tipo de tabla)
    const tfoot = document.getElementById('tfootCalificacionesOficial');
    if (tfoot) {
        const avg = (arr) => arr.length > 0 ? (arr.reduce((a, b) => a + b, 0) / arr.length).toFixed(1) : '—';
        const celda = (valor, fondo = '') => {
            const rojo = valor !== '—' && parseFloat(valor) < 6.0;
            return `<td class="text-center" style="color: ${rojo ? '#DC2626' : (valor === '—' ? '#94A3B8' : '#0F172A')};${fondo ? ` background: ${fondo} !important;` : ''}">${valor}</td>`;
        };
        const etiqueta = `<td colspan="4" style="text-align: right; color: #64748B; font-size: 0.6875rem; letter-spacing: .06em; text-transform: uppercase;">Promedio del grupo</td>`;

        if (isSemestral) {
            tfoot.innerHTML = `<tr>${etiqueta}
                ${celda(avg(p1Vals))}${celda(avg(p2Vals))}${celda(avg(p3Vals))}${celda(avg(semVals))}
                ${celda(avg(extVals), '#FFFBEB')}${celda(prom, '#EFF6F7')}
                <td colspan="2"></td></tr>`;
        } else {
            tfoot.innerHTML = `<tr>${etiqueta}
                ${celda(prom, '#EFF6F7')}${celda(avg(extVals), '#FFFBEB')}
                <td colspan="2"></td></tr>`;
        }
    }
}

// ==========================================
// GUARDAR CALIFICACIONES DE LA MATERIA
// ==========================================
function guardarCalificacionesMateriaSeleccionada() {
    if (!grupoCapturaActualId || !datosGrupoMateriaActual) return;
    const idMateria = datosGrupoMateriaActual.idMateriaSeleccionada;
    if (!idMateria) {
        Swal.fire({ icon: 'warning', title: 'Atención', text: 'No hay materia seleccionada.' });
        return;
    }

    // 1. Validar rango de calificaciones antes de continuar
    const rows = document.querySelectorAll('#tbodyAlumnosCalificacionesMateria tr');
    let valid = true;
    
    rows.forEach(tr => {
        const isEquiv = tr.getAttribute('data-is-equivalencia') === 'true';
        if (isEquiv) return;

        const inputs = tr.querySelectorAll('.input-calif-celda:not(.inp-asistencias):not(.inp-total-asistencias)');
        inputs.forEach(inp => {
            if (!validarRangoInput(inp)) {
                valid = false;
            }
        });
    });

    if (!valid) {
        Swal.fire({
            icon: 'error',
            title: 'Valores Inválidos',
            text: 'Existen calificaciones fuera del rango permitido (números enteros del 0 al 10). Por favor corríjalas antes de guardar.',
            confirmButtonColor: '#26687B'
        });
        return;
    }

    const isDocente = '{{ session("rol") }}' === 'DOCENTE';

    if (isDocente) {
        // Mostrar SweetAlert pidiendo confirmación definitiva
        Swal.fire({
            title: '¿Confirmar y enviar calificaciones?',
            text: "Una vez enviadas, las calificaciones quedarán registradas de manera definitiva.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#26687B',
            cancelButtonColor: '#64748B',
            confirmButtonText: '<i class="ti ti-send me-1"></i> Sí, confirmar y enviar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                enviarPeticionGuardar(true);
            }
        });
    } else {
        // Es Administrador: Guardar con confirmación simple sin bloqueo
        Swal.fire({
            title: '¿Guardar calificaciones?',
            text: "Se guardarán las calificaciones modificadas en la asignatura.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#26687B',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                enviarPeticionGuardar(false);
            }
        });
    }
}

function enviarPeticionGuardar(finalizar) {
    const idMateria = datosGrupoMateriaActual.idMateriaSeleccionada;
    const btn = document.getElementById('btnGuardarCalifsMateria');
    btn.disabled = true;
    btn.innerHTML = '<i class="ti ti-loader-2 ti-spin"></i> Guardando…';

    const rows = document.querySelectorAll('#tbodyAlumnosCalificacionesMateria tr');
    const calificaciones = [];

    rows.forEach(tr => {
        const idAlumno = tr.getAttribute('data-alumno-id');
        const isEquiv = tr.getAttribute('data-is-equivalencia') === 'true';
        const obs = tr.querySelector('.inp-obs') ? tr.querySelector('.inp-obs').value : '';
        
        if (isEquiv) {
            if (idAlumno) {
                calificaciones.push({
                    idAlumno: parseInt(idAlumno),
                    calificacion: 0.0,
                    tipoAcreditacion: 'EQUIVALENCIA',
                    observaciones: obs
                });
            }
            return;
        }

        const p1Inp = tr.querySelector('.inp-p1');
        const p2Inp = tr.querySelector('.inp-p2');
        const p3Inp = tr.querySelector('.inp-p3');
        const semInp = tr.querySelector('.inp-semestral');
        const extInp = tr.querySelector('.inp-extraordinario');
        const asistInp = tr.querySelector('.inp-asistencias');
        const totAsistInp = tr.querySelector('.inp-total-asistencias');

        const califFinalInp = tr.querySelector('.inp-calif-final');
        let califFinal = califFinalInp ? califFinalInp.value : '';

        // Si se capturó algún parcial pero por alguna razón la celda final quedó vacía, calcular su promedio
        if (califFinal === '' && (p1Inp || p2Inp || p3Inp)) {
            const vals = [];
            if (p1Inp && p1Inp.value !== '') vals.push(parseFloat(p1Inp.value));
            if (p2Inp && p2Inp.value !== '') vals.push(parseFloat(p2Inp.value));
            if (p3Inp && p3Inp.value !== '') vals.push(parseFloat(p3Inp.value));
            if (vals.length > 0) {
                califFinal = String(Math.round(vals.reduce((a, b) => a + b, 0) / vals.length));
                if (califFinalInp) califFinalInp.value = califFinal;
            }
        }

        if (idAlumno && califFinal !== 'EQUIV') {
            const esExtra = extInp && extInp.value !== '' && !extInp.disabled;
            const dataObj = {
                idAlumno: parseInt(idAlumno),
                calificacion: (califFinal !== '' && califFinal !== null) ? parseInt(califFinal, 10) : null,
                tipoAcreditacion: esExtra ? 'EXTRAORDINARIO' : 'ORDINARIO',
                observaciones: obs
            };
            
            if (p1Inp) dataObj.parcial1 = p1Inp.value !== "" ? parseInt(p1Inp.value, 10) : null;
            if (p2Inp) dataObj.parcial2 = p2Inp.value !== "" ? parseInt(p2Inp.value, 10) : null;
            if (p3Inp) dataObj.parcial3 = p3Inp.value !== "" ? parseInt(p3Inp.value, 10) : null;
            if (semInp) dataObj.semestral = semInp.value !== "" ? parseInt(semInp.value, 10) : null;
            if (extInp) dataObj.extraordinario = extInp.value !== "" ? parseInt(extInp.value, 10) : null;
            if (asistInp) dataObj.asistencias = asistInp.value !== "" ? parseInt(asistInp.value, 10) : null;
            if (totAsistInp) dataObj.total_asistencias = totAsistInp.value !== "" ? parseInt(totAsistInp.value, 10) : null;

            calificaciones.push(dataObj);
        }
    });

    function obtenerCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
            || '{{ csrf_token() }}';
    }

    async function ejecutarPeticionGuardar(token, esReintento = false) {
        const res = await fetch(`/grupos/${grupoCapturaActualId}/calificaciones-materia/${idMateria}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ 
                calificaciones: calificaciones,
                finalizar: finalizar
            })
        });

        const resp = await res.json().catch(() => null);

        if (!res.ok) {
            // Manejo de Error 419 (Token CSRF expirado o sesión inactiva)
            if (res.status === 419 && !esReintento) {
                console.warn('Token CSRF expirado (419), renovando token automáticamente...');
                try {
                    const csrfRes = await fetch('/refresh-csrf', {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (csrfRes.ok) {
                        const csrfData = await csrfRes.json();
                        if (csrfData && csrfData.csrf_token) {
                            const meta = document.querySelector('meta[name="csrf-token"]');
                            if (meta) meta.setAttribute('content', csrfData.csrf_token);
                            // Reintentar de forma transparente con el token nuevo sin perder datos capturados
                            return await ejecutarPeticionGuardar(csrfData.csrf_token, true);
                        }
                    }
                } catch (retryErr) {
                    console.error('Error al renovar token CSRF:', retryErr);
                }
                throw new Error('La sesión de seguridad ha expirado (Error 419). Tus calificaciones siguen seguras en la pantalla: no cierres esta ventana. Puedes abrir otra pestaña, verificar tu sesión y volver a presionar Guardar.');
            }

            if (res.status === 401) {
                throw new Error('Tu sesión ha finalizado (Error 401). Abre una nueva pestaña para iniciar sesión sin cerrar esta ventana, y luego presiona Guardar nuevamente.');
            }

            const errorDetalle = (resp && (resp.error || resp.message)) || `Error en el servidor (${res.status})`;
            throw new Error(errorDetalle);
        }

        return resp;
    }

    ejecutarPeticionGuardar(obtenerCsrfToken())
        .then(resp => {
            if (resp && resp.success) {
                Swal.fire({
                    icon: 'success',
                    title: finalizar ? '¡Enviado!' : '¡Guardado!',
                    text: finalizar ? 'Calificaciones enviadas y guardadas exitosamente.' : 'Calificaciones de la materia registradas en borrador.',
                    confirmButtonColor: '#26687B'
                });
                // Recargar modal para actualizar estado de solo lectura y ver los cambios reflejados
                abrirCapturaGrupoMateria(grupoCapturaActualId, idMateria);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al Guardar',
                    text: (resp && (resp.error || resp.message)) || 'No se pudieron guardar las calificaciones.',
                    confirmButtonColor: '#26687B'
                });
            }
        })
        .catch(err => {
            console.error('Error al guardar:', err);
            const msg = err.message || 'Ocurrió un error inesperado al enviar las calificaciones.';
            Swal.fire({
                icon: 'error',
                title: 'Error al Guardar',
                html: `<div class="text-start">
                        <p class="mb-2"><strong>Detalle del error:</strong></p>
                        <div class="alert alert-danger p-2 mb-2" style="font-size: 0.88rem; word-break: break-word;">
                            ${msg}
                        </div>
                        <p class="text-muted small mb-0"><i class="ti ti-circle-check text-success me-1"></i>Tus notas ingresadas siguen en pantalla; no se han borrado.</p>
                       </div>`,
                confirmButtonColor: '#26687B',
                confirmButtonText: 'Entendido'
            });
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="ti ti-device-floppy"></i> Guardar calificaciones';
        });
}

// Carga Inicial
function inicializarModuloCaptura() {
    // Mantener activa la sesión y el token CSRF periódicamente cada 4 minutos mientras el usuario captura
    setInterval(function() {
        fetch('/refresh-csrf', {
            headers: { 'Accept': 'application/json', 'Cache-Control': 'no-cache', 'Pragma': 'no-cache' }
        })
        .then(r => r.json())
        .then(data => {
            if (data && data.csrf_token) {
                const meta = document.querySelector('meta[name="csrf-token"]');
                if (meta) meta.setAttribute('content', data.csrf_token);
            }
        })
        .catch(() => {});
    }, 4 * 60 * 1000);

    // Mover modales a document.body para evitar stacking context con navbar y sidebar fijos
    const modal = document.getElementById('modalCapturaMateriaGrupo');
    const modalAsist = document.getElementById('modalCapturaAsistenciasGrupo');
    const modalContainer = document.getElementById('contenedorModal') || document.body;
    if (modal && modal.parentElement !== modalContainer) {
        modalContainer.appendChild(modal);
    }
    if (modalAsist && modalAsist.parentElement !== modalContainer) {
        modalContainer.appendChild(modalAsist);
    }

    if (modalAsist) {
        modalAsist.addEventListener('hidden.bs.modal', function () {
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        });
    }

    if (modal) {
        modal.addEventListener('hidden.bs.modal', function () {
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        });
    }

    cargarGruposCaptura();

    // Detectar params para abrir automáticamente modal
    const urlParams = new URLSearchParams(window.location.search);
    const paramGrupo = urlParams.get('id_grupo');
    const paramMateria = urlParams.get('id_materia');
    if (paramGrupo) {
        setTimeout(() => {
            abrirCapturaGrupoMateria(parseInt(paramGrupo), paramMateria ? parseInt(paramMateria) : null);
        }, 600);
    }
}

if (document.readyState === 'loading') {
    document.addEventListener("DOMContentLoaded", inicializarModuloCaptura);
} else {
    inicializarModuloCaptura();
}
</script>

@endsection
