@extends('layouts.app')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@php
    /*
     * NOTA: los elementos que el JS muestra/oculta con style.display (spinners, wrappers de tabla,
     * estados vacíos, tarjetas de periodo) no llevan utilidades de display de Tailwind (son !important).
     */
    $card      = ' tw:border tw:border-slate-200 tw:bg-white tw:shadow-card';
    $label     = 'tw:mb-1.5 tw:block tw:text-[0.8125rem] tw:font-medium tw:text-slate-700';
    $input     = 'tw:h-10 tw:w-full tw:border tw:border-slate-200 tw:bg-white tw:px-3 tw:text-sm tw:text-slate-900 tw:shadow-xs tw:outline-none tw:transition tw:placeholder:text-slate-400 tw:hover:border-slate-300 tw:focus:border-brand-500 tw:focus:ring-4 tw:focus:ring-brand-500/10 tw:disabled:cursor-not-allowed tw:disabled:bg-slate-50 tw:disabled:text-slate-400';
    $inputSm   = 'tw:h-9 tw:w-full tw:border tw:border-slate-200 tw:bg-white tw:px-2.5 tw:text-[0.8125rem] tw:text-slate-900 tw:shadow-xs tw:outline-none tw:transition tw:focus:border-brand-500 tw:focus:ring-4 tw:focus:ring-brand-500/10 tw:disabled:cursor-not-allowed tw:disabled:bg-slate-50 tw:disabled:text-slate-400 tw:disabled:shadow-none';
    $btnBase   = 'tw:inline-flex tw:cursor-pointer tw:items-center tw:justify-center tw:gap-2 tw:text-sm tw:font-semibold tw:transition-all tw:duration-150 tw:focus-visible:outline-2 tw:focus-visible:outline-offset-2 tw:focus-visible:outline-brand-500 tw:active:scale-[0.98]';
    $btnPrimary   = $btnBase . ' tw:h-10 tw:border-0 tw:bg-brand-700 tw:px-4 tw:text-white tw:shadow-brand tw:hover:bg-brand-800';
    $btnSecondary = $btnBase . ' tw:h-10 tw:border tw:border-slate-200 tw:bg-white tw:px-4 tw:text-slate-700 tw:shadow-xs tw:hover:bg-slate-50';
    $tab  = 'tw:group tw:inline-flex tw:shrink-0 tw:cursor-pointer tw:items-center tw:gap-2 tw:border-0 tw:border-b-2 tw:border-transparent tw:bg-transparent tw:px-3 tw:pt-2 tw:pb-3 tw:text-[0.8125rem] tw:font-medium tw:whitespace-nowrap tw:text-slate-500 tw:transition-colors tw:hover:text-slate-900 tw:aria-selected:border-brand-700 tw:aria-selected:font-semibold tw:aria-selected:text-brand-700';
    $th   = 'tw:px-4 tw:py-2.5 tw:text-left tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-500';
    $searchWrap = 'tw:relative tw:w-full tw:sm:w-80';
    $search = 'tw:h-9 tw:w-full tw:border tw:border-slate-200 tw:bg-white tw:pr-3 tw:pl-9 tw:text-[0.8125rem] tw:text-slate-900 tw:shadow-xs tw:outline-none tw:transition tw:placeholder:text-slate-400 tw:focus:border-brand-500 tw:focus:ring-4 tw:focus:ring-brand-500/10';
    $spinner = '<span class="tw:inline-block tw:size-6 tw:animate-spin tw:rounded-full tw:border-2 tw:border-slate-200 tw:border-t-brand-700"></span>';

    $periodos = [
        ['key' => 'p1',             'cap' => 'P1',             'card' => 'cardAjusteP1',        'titulo' => '1er parcial',             'icon' => 'circle-dot',   'tituloId' => 'lblGrupoP1Title'],
        ['key' => 'p2',             'cap' => 'P2',             'card' => 'cardAjusteP2',        'titulo' => '2do parcial',             'icon' => 'circle-dot'],
        ['key' => 'p3',             'cap' => 'P3',             'card' => 'cardAjusteP3',        'titulo' => '3er parcial',             'icon' => 'circle-dot'],
        ['key' => 'semestral',      'cap' => 'Semestral',      'card' => 'cardAjusteSemestral', 'titulo' => 'Examen semestral / final', 'icon' => 'file-check'],
        ['key' => 'extraordinario', 'cap' => 'Extraordinario', 'card' => 'cardAjusteExtraordinario', 'titulo' => 'Examen extraordinario', 'icon' => 'zap'],
    ];
@endphp

<div class="tw:mx-auto tw:flex tw:max-w-[1600px] tw:flex-col tw:gap-6">

    {{-- ===================== ENCABEZADO ===================== --}}
    <header class="tw:flex tw:flex-col tw:gap-4 tw:lg:flex-row tw:lg:items-end tw:lg:justify-between">
        <div>
            <h1 class="tw:m-0 tw:text-[1.5rem] tw:font-bold tw:tracking-tight tw:text-slate-900">Permisos de captura</h1>
            <p class="tw:m-0 tw:mt-1 tw:text-sm tw:text-slate-500">Monitorea el avance de calificaciones, asigna prórrogas y define los periodos de captura por grupo.</p>
        </div>
        <button type="button" class="{{ $btnPrimary }}" onclick="abrirModalAlta()">
            <i data-lucide="calendar-plus" class="tw:size-4"></i> Asignar prórroga
        </button>
    </header>

    {{-- ===================== PESTAÑAS (Bootstrap pills) ===================== --}}
    <nav id="pills-tab" role="tablist" class="tw:flex tw:gap-1 tw:overflow-x-auto tw:border-b tw:border-slate-200 tw:[scrollbar-width:none]">
        <button class="{{ $tab }} active" id="tab-matriz-btn" data-bs-toggle="pill" data-bs-target="#tab-matriz" type="button" role="tab" aria-controls="tab-matriz" aria-selected="true" onclick="cargarMatriz()">
            <i data-lucide="chart-no-axes-column" class="tw:size-4"></i> Avance de captura
        </button>
        <button class="{{ $tab }}" id="tab-prorrogas-btn" data-bs-toggle="pill" data-bs-target="#tab-prorrogas" type="button" role="tab" aria-controls="tab-prorrogas" aria-selected="false" onclick="cargarPermisos()">
            <i data-lucide="calendar-clock" class="tw:size-4"></i> Prórrogas
        </button>
        <button class="{{ $tab }}" id="tab-cct-btn" data-bs-toggle="pill" data-bs-target="#tab-cct" type="button" role="tab" aria-controls="tab-cct" aria-selected="false" onclick="cargarCctConfigList()">
            <i data-lucide="sliders-horizontal" class="tw:size-4"></i> Periodos por grupo
        </button>
    </nav>

    <div class="tab-content" id="pills-tabContent">

        {{-- ===================== 1. AVANCE DE CAPTURA ===================== --}}
        <div class="tab-pane fade show active" id="tab-matriz" role="tabpanel" aria-labelledby="tab-matriz-btn">
            <section class="{{ $card }} tw:overflow-hidden">
                <div class="tw:flex tw:flex-col tw:gap-3 tw:border-b tw:border-slate-100 tw:px-5 tw:py-4 tw:lg:flex-row tw:lg:items-center tw:lg:justify-between">
                    {{-- Filtros por estado (con conteo) generados por JS --}}
                    <div id="resumenMatriz" role="group" aria-label="Filtrar por estado" class="tw:flex tw:flex-wrap tw:gap-1.5"></div>
                    <div class="{{ $searchWrap }}">
                        <i data-lucide="search" class="tw:pointer-events-none tw:absolute tw:left-3 tw:top-1/2 tw:size-4 tw:-translate-y-1/2 tw:text-slate-400"></i>
                        <input type="text" id="buscadorMatriz" class="{{ $search }}" placeholder="Docente, materia o grupo…" oninput="filtrarTablaMatrizDebounced()">
                    </div>
                </div>

                <div id="spinnerMatriz">
                    <div class="tw:flex tw:flex-col tw:items-center tw:gap-3 tw:py-16 tw:text-[0.8125rem] tw:text-slate-500">{!! $spinner !!} Calculando avance de calificaciones…</div>
                </div>

                <div id="wrapperMatriz" style="display: none;">
                    <div class="tw:overflow-x-auto">
                        <table class="tw:w-full tw:min-w-[980px] tw:border-collapse" id="tablaMatriz">
                            <thead class="tw:bg-slate-50">
                                <tr class="tw:border-b tw:border-slate-200">
                                    <th class="{{ $th }} tw:pl-5">Docente</th>
                                    <th class="{{ $th }}">Grupo</th>
                                    <th class="{{ $th }}">Materia</th>
                                    <th class="{{ $th }} tw:w-[190px]">Avance</th>
                                    <th class="{{ $th }}">Fecha límite</th>
                                    <th class="{{ $th }}">Estado</th>
                                    <th class="{{ $th }} tw:pr-5 tw:text-right"><span class="tw:sr-only">Acciones</span></th>
                                </tr>
                            </thead>
                            <tbody id="tbodyMatriz" class="tw:divide-y tw:divide-slate-100"></tbody>
                        </table>
                    </div>
                </div>

                <div id="emptyMatriz" style="display: none;">
                    <div class="tw:flex tw:flex-col tw:items-center tw:px-6 tw:py-14 tw:text-center">
                        <span class="tw:inline-flex tw:size-12 tw:items-center tw:justify-center tw:bg-slate-100 tw:text-slate-400"><i data-lucide="search-x" class="tw:size-5"></i></span>
                        <p class="tw:m-0 tw:mt-3 tw:text-sm tw:font-semibold tw:text-slate-900">Sin resultados</p>
                        <p class="tw:m-0 tw:mt-1 tw:text-[0.8125rem] tw:text-slate-500">No hay asignaciones que coincidan con el filtro, o no existen horarios cargados.</p>
                    </div>
                </div>
            </section>
        </div>

        {{-- ===================== 2. PRÓRROGAS ===================== --}}
        <div class="tab-pane fade" id="tab-prorrogas" role="tabpanel" aria-labelledby="tab-prorrogas-btn">
            <section class="{{ $card }} tw:overflow-hidden">
                <div class="tw:flex tw:flex-col tw:gap-3 tw:border-b tw:border-slate-100 tw:px-5 tw:py-4 tw:lg:flex-row tw:lg:items-center tw:lg:justify-between">
                    <div id="resumenPermisos" role="group" aria-label="Filtrar por estado" class="tw:flex tw:flex-wrap tw:gap-1.5"></div>
                    <div class="{{ $searchWrap }}">
                        <i data-lucide="search" class="tw:pointer-events-none tw:absolute tw:left-3 tw:top-1/2 tw:size-4 tw:-translate-y-1/2 tw:text-slate-400"></i>
                        <input type="text" id="buscadorPermisos" class="{{ $search }}" placeholder="Docente, materia o grupo…" oninput="filtrarTablaPermisosDebounced()">
                    </div>
                </div>

                <div id="spinnerCarga" style="display: none;">
                    <div class="tw:flex tw:flex-col tw:items-center tw:gap-3 tw:py-16 tw:text-[0.8125rem] tw:text-slate-500">{!! $spinner !!} Cargando prórrogas…</div>
                </div>

                <div id="tablaWrapper" style="display: none;">
                    <div class="tw:overflow-x-auto">
                        <table class="tw:w-full tw:min-w-[900px] tw:border-collapse" id="tablaPermisos">
                            <thead class="tw:bg-slate-50">
                                <tr class="tw:border-b tw:border-slate-200">
                                    <th class="{{ $th }} tw:pl-5">Docente</th>
                                    <th class="{{ $th }}">Grupo</th>
                                    <th class="{{ $th }}">Asignatura</th>
                                    <th class="{{ $th }}">Fecha límite</th>
                                    <th class="{{ $th }}">Periodos pasados</th>
                                    <th class="{{ $th }}">Estado</th>
                                    <th class="{{ $th }} tw:pr-5 tw:text-right"><span class="tw:sr-only">Acciones</span></th>
                                </tr>
                            </thead>
                            <tbody id="tbodyPermisos" class="tw:divide-y tw:divide-slate-100"></tbody>
                        </table>
                    </div>
                </div>

                <div id="emptyState" style="display: none;">
                    <div class="tw:flex tw:flex-col tw:items-center tw:px-6 tw:py-14 tw:text-center">
                        <span class="tw:inline-flex tw:size-12 tw:items-center tw:justify-center tw:bg-brand-50 tw:text-brand-700"><i data-lucide="calendar-check" class="tw:size-5"></i></span>
                        <p class="tw:m-0 tw:mt-3 tw:text-sm tw:font-semibold tw:text-slate-900">Sin prórrogas</p>
                        <p class="tw:m-0 tw:mt-1 tw:text-[0.8125rem] tw:text-slate-500">Todos los docentes operan con los tiempos ordinarios de captura.</p>
                    </div>
                </div>
            </section>
        </div>

        {{-- ===================== 3. PERIODOS POR GRUPO ===================== --}}
        <div class="tab-pane fade" id="tab-cct" role="tabpanel" aria-labelledby="tab-cct-btn">
            <div class="tw:grid tw:grid-cols-1 tw:items-start tw:gap-6 tw:lg:grid-cols-[300px_minmax(0,1fr)]">

                {{-- Selección --}}
                <aside class="{{ $card }} tw:p-5 tw:lg:sticky tw:lg:top-[84px]">
                    <p class="tw:m-0 tw:mb-4 tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.08em] tw:text-slate-400">Grupo a configurar</p>
                    <div class="tw:flex tw:flex-col tw:gap-4">
                        <div>
                            <label for="selectCctConfig" class="{{ $label }}">Plantel</label>
                            <select id="selectCctConfig" class="{{ $input }}" onchange="cargarGruposConfig(this.value)">
                                <option value="">Selecciona un plantel…</option>
                            </select>
                        </div>
                        <div>
                            <label for="selectGrupoConfig" class="{{ $label }}">Grupo</label>
                            <select id="selectGrupoConfig" class="{{ $input }}" onchange="cargarConfiguracionGrupo(this.value)" disabled>
                                <option value="">Primero elige un plantel</option>
                            </select>
                        </div>
                    </div>
                </aside>

                <div class="tw:min-w-0">
                    {{-- Vacío --}}
                    <div id="vacioAjustesGrupo">
                        <div class="{{ $card }} tw:flex tw:flex-col tw:items-center tw:px-6 tw:py-16 tw:text-center">
                            <span class="tw:inline-flex tw:size-12 tw:items-center tw:justify-center tw:bg-brand-50 tw:text-brand-700 tw:ring-1 tw:ring-brand-200"><i data-lucide="calendar-range" class="tw:size-5"></i></span>
                            <p class="tw:m-0 tw:mt-3 tw:text-sm tw:font-semibold tw:text-slate-900">Selecciona un plantel y un grupo</p>
                            <p class="tw:m-0 tw:mt-1 tw:max-w-sm tw:text-[0.8125rem] tw:text-slate-500">Podrás habilitar la captura y definir el rango de fechas de cada periodo de evaluación.</p>
                        </div>
                    </div>

                    {{-- Ajustes --}}
                    <section id="cardAjustesGrupo" style="display: none;">
                        <form id="formGrupoConfig" class="{{ $card }} tw:overflow-hidden" onsubmit="event.preventDefault(); guardarConfiguracionGrupo();">
                            <header class="tw:border-b tw:border-slate-100 tw:px-6 tw:py-5">
                                <h2 id="lblGrupoNombreTitle" class="tw:m-0 tw:text-[1.0625rem] tw:font-semibold tw:tracking-tight tw:text-slate-900">Grupo</h2>
                                <p class="tw:m-0 tw:mt-0.5 tw:text-[0.8125rem] tw:text-slate-500">Habilitación y fechas límite de captura para los docentes de este grupo.</p>
                            </header>

                            {{-- Interruptor general --}}
                            <div class="tw:flex tw:items-center tw:justify-between tw:gap-4 tw:border-b tw:border-slate-100 tw:bg-slate-50/60 tw:px-6 tw:py-4">
                                <div>
                                    <p class="tw:m-0 tw:text-sm tw:font-semibold tw:text-slate-900">Captura habilitada</p>
                                    <p class="tw:m-0 tw:mt-0.5 tw:text-xs tw:text-slate-500">Si se desactiva, nadie podrá capturar calificaciones en este grupo.</p>
                                </div>
                                <x-ui.switch id="checkGrupoHabilitada" label="Captura habilitada" onchange="toggleInputsGrupo(this.checked)" />
                            </div>

                            <div class="tw:px-6 tw:py-5" id="divGrupoSemestreHabilitado">
                                <label for="selectGrupoNivelCaptura" class="{{ $label }}">Semestre habilitado para captura</label>
                                <select id="selectGrupoNivelCaptura" class="{{ $input }} tw:sm:max-w-sm">
                                    <option value="">Selecciona un semestre…</option>
                                </select>
                                <p class="tw:m-0 tw:mt-1.5 tw:text-xs tw:text-slate-500">Solo se podrán capturar asignaturas de este semestre o nivel.</p>
                            </div>

                            {{-- Periodos --}}
                            <div class="tw:px-6 tw:pb-2">
                                <div class="tw:mb-1 tw:hidden tw:grid-cols-[44px_minmax(0,1fr)_150px_150px] tw:gap-4 tw:px-1 tw:sm:grid">
                                    <span></span>
                                    <span id="lblPeriodosEvaluacionHeader" class="tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-400">Periodo de evaluación</span>
                                    <span class="tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-400">Inicio</span>
                                    <span class="tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-400">Fin (límite)</span>
                                </div>
                                <div class="tw:overflow-hidden tw:border tw:border-slate-200">
                                    @foreach ($periodos as $p)
                                    {{-- .card-periodo-ajuste: el JS alterna display y opacidad --}}
                                    <div class="card-periodo-ajuste tw:border-slate-100 tw:transition-opacity tw:not-first:border-t" id="{{ $p['card'] }}">
                                        <div class="tw:grid tw:grid-cols-[44px_minmax(0,1fr)] tw:items-center tw:gap-x-4 tw:gap-y-2 tw:px-4 tw:py-3 tw:sm:grid-cols-[44px_minmax(0,1fr)_150px_150px]">
                                            <x-ui.switch id="checkGrupo{{ $p['cap'] }}" class="switch-grupo-sub" :label="$p['titulo']" onchange="toggleFilaFecha('{{ $p['key'] }}', this.checked)" />
                                            <span class="tw:flex tw:min-w-0 tw:items-center tw:gap-2 tw:text-sm tw:font-medium tw:text-slate-800" @isset($p['tituloId']) id="{{ $p['tituloId'] }}" @endisset>
                                                {{ $p['titulo'] }}
                                            </span>
                                            <div class="tw:col-start-2 tw:sm:col-start-auto">
                                                <label for="inputGrupo{{ $p['cap'] }}Inicio" class="tw:mb-1 tw:block tw:text-[0.6875rem] tw:text-slate-500 tw:sm:sr-only">Inicio</label>
                                                <input type="date" id="inputGrupo{{ $p['cap'] }}Inicio" class="{{ $inputSm }}">
                                            </div>
                                            <div class="tw:col-start-2 tw:sm:col-start-auto">
                                                <label for="inputGrupo{{ $p['cap'] }}Fin" class="tw:mb-1 tw:block tw:text-[0.6875rem] tw:text-slate-500 tw:sm:sr-only">Fin (límite)</label>
                                                <input type="date" id="inputGrupo{{ $p['cap'] }}Fin" class="{{ $inputSm }}">
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <footer class="tw:mt-4 tw:flex tw:items-center tw:justify-end tw:gap-3 tw:border-t tw:border-slate-100 tw:bg-slate-50/60 tw:px-6 tw:py-4">
                                <button type="submit" class="{{ $btnPrimary }}">
                                    <i data-lucide="save" class="tw:size-4"></i> Guardar ajustes
                                </button>
                            </footer>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===================== MODAL: ASIGNAR PRÓRROGA ===================== --}}
<div class="modal fade" id="modalAltaPermiso" tabindex="-1" aria-labelledby="modalAltaPermisoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAltaPermisoLabel"><i data-lucide="calendar-plus" class="tw:size-5"></i> Asignar prórroga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formAltaPermiso" class="tw:flex tw:flex-col tw:gap-4" onsubmit="event.preventDefault(); guardarNuevoPermiso();">
                    <div>
                        <label for="selectAltaDocente" class="{{ $label }}">Docente</label>
                        <select id="selectAltaDocente" class="{{ $input }}" onchange="filtrarMaterias()" required>
                            <option value="">Selecciona un docente…</option>
                        </select>
                    </div>
                    <div>
                        <label for="selectAltaGrupo" class="{{ $label }}">Grupo</label>
                        <select id="selectAltaGrupo" class="{{ $input }}" onchange="cargarMateriasPorGrupo(this.value)" required>
                            <option value="">Selecciona un grupo…</option>
                        </select>
                    </div>
                    <div>
                        <label for="selectAltaMateria" class="{{ $label }} tw:flex tw:items-center tw:gap-2">
                            Asignatura
                            <span id="loaderMaterias" class="tw:size-3.5 tw:animate-spin tw:rounded-full tw:border-2 tw:border-slate-200 tw:border-t-brand-700" style="display: none;" role="status" aria-label="Cargando asignaturas"></span>
                        </label>
                        <select id="selectAltaMateria" class="{{ $input }}" required disabled>
                            <option value="">Primero elige un grupo</option>
                        </select>
                        <div id="divCheckMateriaPasada" class="tw:mt-2" style="display: none;">
                            <label class="tw:inline-flex tw:cursor-pointer tw:items-center tw:gap-2 tw:text-[0.8125rem] tw:text-slate-600">
                                <input type="checkbox" id="checkMateriaPasada" class="tw:size-4 tw:cursor-pointer tw:accent-brand-700" onchange="filtrarMaterias()">
                                Incluir materias de semestres pasados u otros niveles
                            </label>
                        </div>
                    </div>
                    <div>
                        <label for="inputAltaFechaLimite" class="{{ $label }}">Fecha límite de captura</label>
                        <input type="date" id="inputAltaFechaLimite" class="{{ $input }}" required>
                        <p class="tw:m-0 tw:mt-1.5 tw:text-xs tw:text-slate-500">El docente podrá capturar hasta las 23:59 h de esta fecha.</p>
                    </div>
                    <div class="tw:flex tw:items-center tw:justify-between tw:gap-4 tw:border tw:border-slate-200 tw:bg-slate-50/60 tw:px-4 tw:py-3">
                        <div>
                            <p class="tw:m-0 tw:text-sm tw:font-medium tw:text-slate-900">Modificar periodos pasados</p>
                            <p class="tw:m-0 tw:mt-0.5 tw:text-xs tw:text-slate-500">Permite editar calificaciones de periodos anteriores o grupos cerrados.</p>
                        </div>
                        <x-ui.switch id="checkAltaPasados" value="1" label="Modificar periodos pasados" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="{{ $btnSecondary }}" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formAltaPermiso" class="{{ $btnPrimary }}" id="btnGuardarAlta">
                    <i data-lucide="check" class="tw:size-4"></i> Asignar prórroga
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===================== MODAL: EDITAR PRÓRROGA ===================== --}}
<div class="modal fade" id="modalEditarPermiso" tabindex="-1" aria-labelledby="modalEditarPermisoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarPermisoLabel"><i data-lucide="pencil" class="tw:size-5"></i> Editar prórroga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarPermiso" class="tw:flex tw:flex-col tw:gap-4" onsubmit="event.preventDefault(); guardarEdicionPermiso();">
                    <input type="hidden" id="inputEditId">
                    <dl class="tw:m-0 tw:grid tw:grid-cols-2 tw:gap-x-4 tw:gap-y-3 tw:border tw:border-slate-200 tw:bg-slate-50/60 tw:p-4">
                        <div class="tw:col-span-2">
                            <dt class="tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-400">Docente</dt>
                            <dd id="lblEditDocente" class="tw:m-0 tw:mt-0.5 tw:text-sm tw:font-semibold tw:text-slate-900">—</dd>
                        </div>
                        <div>
                            <dt class="tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-400">Grupo</dt>
                            <dd id="lblEditGrupo" class="tw:m-0 tw:mt-0.5 tw:font-mono tw:text-sm tw:font-semibold tw:text-slate-900">—</dd>
                        </div>
                        <div>
                            <dt class="tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-400">Asignatura</dt>
                            <dd id="lblEditMateria" class="tw:m-0 tw:mt-0.5 tw:text-sm tw:font-semibold tw:text-slate-900">—</dd>
                        </div>
                    </dl>
                    <div>
                        <label for="inputEditFechaLimite" class="{{ $label }}">Fecha límite de captura</label>
                        <input type="date" id="inputEditFechaLimite" class="{{ $input }}" required>
                    </div>
                    <div class="tw:overflow-hidden tw:border tw:border-slate-200">
                        <div class="tw:flex tw:items-center tw:justify-between tw:gap-4 tw:px-4 tw:py-3">
                            <div>
                                <p class="tw:m-0 tw:text-sm tw:font-medium tw:text-slate-900">Prórroga habilitada</p>
                                <p class="tw:m-0 tw:mt-0.5 tw:text-xs tw:text-slate-500">Al desactivarla, la captura se bloquea de inmediato.</p>
                            </div>
                            <x-ui.switch id="checkEditHabilitado" value="1" label="Prórroga habilitada" />
                        </div>
                        <div class="tw:flex tw:items-center tw:justify-between tw:gap-4 tw:border-t tw:border-slate-100 tw:px-4 tw:py-3">
                            <div>
                                <p class="tw:m-0 tw:text-sm tw:font-medium tw:text-slate-900">Modificar periodos pasados</p>
                                <p class="tw:m-0 tw:mt-0.5 tw:text-xs tw:text-slate-500">Permite editar calificaciones de periodos anteriores.</p>
                            </div>
                            <x-ui.switch id="checkEditPasados" value="1" label="Modificar periodos pasados" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="{{ $btnSecondary }}" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formEditarPermiso" class="{{ $btnPrimary }}">
                    <i data-lucide="save" class="tw:size-4"></i> Guardar cambios
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// =====================================================================
// UTILIDADES
// =====================================================================
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const BRAND = '#26687B';
const esc = (v) => String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
const refrescarIconos = () => { if (window.lucide?.createIcons) lucide.createIcons(); };
const mensajeError = (resp, fallback) => (resp && (resp.message || resp.error)) || fallback;
const debounce = (fn, ms = 180) => { let t; return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), ms); }; };
const normalizar = (s) => String(s ?? '').normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase();
const iniciales = (n) => String(n || '').trim().split(/\s+/).filter(Boolean).slice(0, 2).map(p => p[0].toUpperCase()).join('') || '—';

async function fetchJson(url, options = {}) {
    const res = await fetch(url, options);
    let data = null;
    try { data = await res.json(); } catch (e) { /* respuesta no JSON */ }
    if (!res.ok && data && typeof data === 'object' && data.success === undefined) data.success = false;
    if (!res.ok && !data) throw new Error(`HTTP ${res.status}`);
    return data;
}

const TONO = {
    success: 'tw:bg-success-100 tw:text-success-700',
    warning: 'tw:bg-warning-100 tw:text-warning-600',
    brand:   'tw:bg-brand-50 tw:text-brand-700 tw:ring-1 tw:ring-brand-200',
    danger:  'tw:bg-danger-100 tw:text-danger-600',
    slate:   'tw:bg-slate-100 tw:text-slate-600',
};
const ESTADOS_MATRIZ = {
    completo:         { label: 'Completo',       icon: 'circle-check',   tono: 'success', barra: '#10B981' },
    pendiente:        { label: 'En tiempo',      icon: 'clock',          tono: 'warning', barra: BRAND },
    prorroga:         { label: 'Con prórroga',   icon: 'calendar-check', tono: 'brand',   barra: BRAND },
    expirado:         { label: 'Expirado',       icon: 'triangle-alert', tono: 'danger',  barra: '#EF4444' },
    bloqueado_pasado: { label: 'Periodo pasado', icon: 'lock',           tono: 'slate',   barra: '#94A3B8' },
    deshabilitado:    { label: 'Desactivado',    icon: 'ban',            tono: 'slate',   barra: '#94A3B8' },
    sin_alumnos:      { label: 'Sin alumnos',    icon: 'users',          tono: 'slate',   barra: '#CBD5E1' },
};

function badge(tono, icon, texto) {
    return `<span class="tw:inline-flex tw:items-center tw:gap-1 tw:whitespace-nowrap tw:rounded-none tw:px-2.5 tw:py-0.5 tw:text-[0.6875rem] tw:font-semibold ${TONO[tono]}">
        <i data-lucide="${icon}" class="tw:size-3"></i>${esc(texto)}</span>`;
}

function accion({ icon, label, tono = 'neutral', onclick = '', href = '' }) {
    const tonos = {
        brand: 'tw:text-brand-700 tw:hover:bg-brand-50',
        danger: 'tw:text-slate-400 tw:hover:bg-danger-50 tw:hover:text-danger-600',
        neutral: 'tw:text-slate-500 tw:hover:bg-slate-100 tw:hover:text-slate-800',
    };
    const cls = `tw:group/act tw:relative tw:inline-flex tw:size-8 tw:cursor-pointer tw:items-center tw:justify-center tw:border-0 tw:bg-transparent tw:no-underline tw:transition-colors ${tonos[tono]}`;
    const tip = `<span role="tooltip" class="tw:pointer-events-none tw:absolute tw:bottom-full tw:left-1/2 tw:z-20 tw:mb-2 tw:-translate-x-1/2 tw:whitespace-nowrap tw:bg-slate-900 tw:px-2 tw:py-1 tw:text-[0.6875rem] tw:font-medium tw:text-white tw:opacity-0 tw:shadow-raised tw:transition-opacity tw:group-hover/act:opacity-100 tw:group-focus-visible/act:opacity-100">${esc(label)}</span>`;
    const inner = `<i data-lucide="${icon}" class="tw:size-4"></i>${tip}`;
    return href
        ? `<a href="${href}" class="${cls}" aria-label="${esc(label)}">${inner}</a>`
        : `<button type="button" class="${cls}" aria-label="${esc(label)}" onclick="${onclick}">${inner}</button>`;
}

function chipFiltro({ valor, texto, total, activo, onclick, punto }) {
    const cls = activo
        ? 'tw:border-brand-700 tw:bg-brand-700 tw:text-white'
        : 'tw:border-slate-200 tw:bg-white tw:text-slate-600 tw:hover:border-slate-300 tw:hover:text-slate-900';
    const contador = activo ? 'tw:bg-white/20 tw:text-white' : 'tw:bg-slate-100 tw:text-slate-500';
    const dot = punto ? `<span class="tw:size-1.5 tw:rounded-full" style="background:${punto}"></span>` : '';
    return `<button type="button" aria-pressed="${activo}" onclick="${onclick}('${valor}')"
        class="tw:inline-flex tw:cursor-pointer tw:items-center tw:gap-1.5 tw:border tw:py-1 tw:pr-1.5 tw:pl-3 tw:text-xs tw:font-medium tw:transition-colors ${cls}">
        ${dot}${esc(texto)}<span class="tw:inline-flex tw:h-5 tw:min-w-5 tw:items-center tw:justify-center tw:rounded-none tw:px-1.5 tw:text-[0.6875rem] tw:font-semibold tw:tabular-nums ${contador}">${total}</span></button>`;
}

function celdaDocente(nombre) {
    return `<div class="tw:flex tw:items-center tw:gap-2.5">
        <span class="tw:inline-flex tw:size-8 tw:shrink-0 tw:items-center tw:justify-center tw:bg-slate-100 tw:text-[0.6875rem] tw:font-semibold tw:text-slate-600">${esc(iniciales(nombre))}</span>
        <span class="tw:text-[0.8125rem] tw:font-semibold tw:text-slate-900">${esc(nombre)}</span></div>`;
}
const celdaGrupo = (g) => `<span class=" tw:bg-slate-100 tw:px-2 tw:py-0.5 tw:font-mono tw:text-xs tw:font-semibold tw:text-slate-700">${esc(g)}</span>`;
const celdaMateria = (nombre, clave) => `<div class="tw:text-[0.8125rem] tw:font-medium tw:text-slate-800">${esc(nombre)}</div><div class="tw:font-mono tw:text-[0.6875rem] tw:text-slate-400">${esc(clave)}</div>`;

function formatearFechaEspanol(fechaStr) {
    if (!fechaStr) return '—';
    const partes = fechaStr.split('-');
    return partes.length === 3 ? `${partes[2]}/${partes[1]}/${partes[0]}` : fechaStr;
}

function cerrarModal(id) {
    const modal = bootstrap.Modal.getInstance(document.getElementById(id));
    if (modal) modal.hide();
}

// =====================================================================
// 1. AVANCE DE CAPTURA
// =====================================================================
let listadoMatriz = [];
let estadoMatriz = '';

function cargarMatriz() {
    const spinner = document.getElementById('spinnerMatriz');
    spinner.style.display = 'block';
    document.getElementById('wrapperMatriz').style.display = 'none';
    document.getElementById('emptyMatriz').style.display = 'none';

    fetchJson('/permisos-captura/matriz')
        .then(data => {
            listadoMatriz = Array.isArray(data) ? data : [];
            filtrarTablaMatriz();
        })
        .catch(err => {
            console.error('Error al cargar la matriz de avance:', err);
            Swal.fire({ icon: 'error', title: 'Error de carga', text: 'No se pudo generar la matriz de avance.', confirmButtonColor: BRAND });
        })
        .finally(() => { spinner.style.display = 'none'; });
}

function filtrarEstadoMatriz(valor) {
    estadoMatriz = estadoMatriz === valor ? '' : valor;
    filtrarTablaMatriz();
}

function renderResumenMatriz() {
    const conteo = listadoMatriz.reduce((acc, h) => (acc[h.estado] = (acc[h.estado] || 0) + 1, acc), {});
    let html = chipFiltro({ valor: '', texto: 'Todos', total: listadoMatriz.length, activo: estadoMatriz === '', onclick: 'filtrarEstadoMatriz' });
    Object.entries(ESTADOS_MATRIZ).forEach(([valor, cfg]) => {
        if (!conteo[valor]) return;
        html += chipFiltro({ valor, texto: cfg.label, total: conteo[valor], activo: estadoMatriz === valor, onclick: 'filtrarEstadoMatriz', punto: cfg.barra });
    });
    document.getElementById('resumenMatriz').innerHTML = html;
}

function filtrarTablaMatriz() {
    const busqueda = normalizar(document.getElementById('buscadorMatriz').value.trim());
    const filtrados = listadoMatriz.filter(h => {
        const texto = normalizar(`${h.nombre_docente} ${h.clave_grupo} ${h.nombre_materia} ${h.clave_materia}`);
        return (!busqueda || texto.includes(busqueda)) && (!estadoMatriz || h.estado === estadoMatriz);
    });

    renderResumenMatriz();

    const wrapper = document.getElementById('wrapperMatriz');
    const empty = document.getElementById('emptyMatriz');
    if (filtrados.length === 0) {
        wrapper.style.display = 'none';
        empty.style.display = 'block';
        refrescarIconos();
        return;
    }
    wrapper.style.display = 'block';
    empty.style.display = 'none';

    document.getElementById('tbodyMatriz').innerHTML = filtrados.map(h => {
        const cfg = ESTADOS_MATRIZ[h.estado] || { label: h.estado || '—', icon: 'circle', tono: 'slate', barra: '#94A3B8' };
        const total = Number(h.alumnos_totales) || 0;
        const hechos = Number(h.alumnos_calificados) || 0;
        const pct = total > 0 ? Math.round((hechos / total) * 100) : 0;
        const ids = [h.id_docente, h.id_grupo, h.id_materia].map(Number);

        const acciones = [];
        if (h.estado !== 'completo' && h.estado !== 'sin_alumnos') {
            acciones.push(accion({ icon: 'calendar-plus', label: 'Asignar prórroga', tono: 'brand', onclick: `otorgarProrrogaRapida(${ids.join(', ')})` }));
        }
        acciones.push(accion({ icon: 'square-pen', label: 'Capturar calificaciones', href: `/grupos/captura_calificaciones?id_grupo=${ids[1]}&id_materia=${ids[2]}` }));

        return `<tr class="tw:transition-colors ${h.estado === 'expirado' ? 'tw:bg-danger-50/50 tw:hover:bg-danger-50' : 'tw:hover:bg-slate-50'}">
            <td class="tw:py-3 tw:pr-4 tw:pl-5 tw:align-middle">${celdaDocente(h.nombre_docente)}</td>
            <td class="tw:px-4 tw:py-3 tw:align-middle">${celdaGrupo(h.clave_grupo)}</td>
            <td class="tw:px-4 tw:py-3 tw:align-middle">${celdaMateria(h.nombre_materia, h.clave_materia)}</td>
            <td class="tw:px-4 tw:py-3 tw:align-middle">
                <div class="tw:mb-1 tw:flex tw:justify-between tw:text-[0.6875rem] tw:text-slate-500"><span>${hechos}/${total} alumnos</span><span class="tw:font-semibold tw:text-slate-700">${pct}%</span></div>
                <div class="tw:h-1.5 tw:overflow-hidden tw:bg-slate-100"><div class="tw:h-full" style="width:${pct}%;background:${cfg.barra}"></div></div>
            </td>
            <td class="tw:px-4 tw:py-3 tw:align-middle tw:font-mono tw:text-xs tw:whitespace-nowrap tw:text-slate-600">${formatearFechaEspanol(h.fecha_limite)}</td>
            <td class="tw:px-4 tw:py-3 tw:align-middle">${badge(cfg.tono, cfg.icon, cfg.label)}</td>
            <td class="tw:py-3 tw:pr-5 tw:pl-4 tw:text-right tw:align-middle tw:whitespace-nowrap">${acciones.join('')}</td>
        </tr>`;
    }).join('');
    refrescarIconos();
}
const filtrarTablaMatrizDebounced = debounce(filtrarTablaMatriz);

// =====================================================================
// 2. PRÓRROGAS
// =====================================================================
let listadoPermisos = [];
let estadoPermisos = '';

function estadoPermiso(p) {
    if (p.finalizado == 1 || p.finalizado === true) return 'entregado';
    return p.habilitado == 1 ? 'activo' : 'inactivo';
}
const ESTADOS_PERMISO = {
    activo:    { label: 'Activa',    icon: 'circle-check', tono: 'success', punto: '#15803D' },
    inactivo:  { label: 'Inactiva',  icon: 'circle-x',     tono: 'danger',  punto: '#DC2626' },
    entregado: { label: 'Entregada', icon: 'lock',         tono: 'slate',   punto: '#94A3B8' },
};

function cargarPermisos() {
    const spinner = document.getElementById('spinnerCarga');
    spinner.style.display = 'block';
    document.getElementById('tablaWrapper').style.display = 'none';
    document.getElementById('emptyState').style.display = 'none';

    fetchJson('/permisos-captura/lista')
        .then(data => {
            listadoPermisos = Array.isArray(data) ? data : [];
            filtrarTablaPermisos();
        })
        .catch(err => {
            console.error('Error al cargar permisos:', err);
            Swal.fire({ icon: 'error', title: 'Error de carga', text: 'No se pudieron recuperar las prórrogas registradas.', confirmButtonColor: BRAND });
        })
        .finally(() => { spinner.style.display = 'none'; });
}

function filtrarEstadoPermisos(valor) {
    estadoPermisos = estadoPermisos === valor ? '' : valor;
    filtrarTablaPermisos();
}

function filtrarTablaPermisos() {
    const busqueda = normalizar(document.getElementById('buscadorPermisos').value.trim());
    const filtrados = listadoPermisos.filter(p => {
        const texto = normalizar(`${p.nombre_docente} ${p.clave_grupo} ${p.nombre_materia} ${p.clave_materia}`);
        return (!busqueda || texto.includes(busqueda)) && (!estadoPermisos || estadoPermiso(p) === estadoPermisos);
    });

    const conteo = listadoPermisos.reduce((acc, p) => (acc[estadoPermiso(p)] = (acc[estadoPermiso(p)] || 0) + 1, acc), {});
    document.getElementById('resumenPermisos').innerHTML =
        chipFiltro({ valor: '', texto: 'Todas', total: listadoPermisos.length, activo: estadoPermisos === '', onclick: 'filtrarEstadoPermisos' }) +
        Object.entries(ESTADOS_PERMISO).filter(([v]) => conteo[v]).map(([valor, cfg]) =>
            chipFiltro({ valor, texto: cfg.label, total: conteo[valor], activo: estadoPermisos === valor, onclick: 'filtrarEstadoPermisos', punto: cfg.punto })
        ).join('');

    const wrapper = document.getElementById('tablaWrapper');
    const empty = document.getElementById('emptyState');
    if (filtrados.length === 0) {
        wrapper.style.display = 'none';
        empty.style.display = 'block';
        refrescarIconos();
        return;
    }
    wrapper.style.display = 'block';
    empty.style.display = 'none';

    document.getElementById('tbodyPermisos').innerHTML = filtrados.map(p => {
        const cfg = ESTADOS_PERMISO[estadoPermiso(p)];
        const id = Number(p.id);
        const pasados = p.permitir_modificar_pasados == 1
            ? badge('warning', 'history', 'Autorizado')
            : '<span class="tw:text-xs tw:text-slate-400">No</span>';
        return `<tr class="tw:transition-colors tw:hover:bg-slate-50">
            <td class="tw:py-3 tw:pr-4 tw:pl-5 tw:align-middle">${celdaDocente(p.nombre_docente)}</td>
            <td class="tw:px-4 tw:py-3 tw:align-middle">${celdaGrupo(p.clave_grupo)}</td>
            <td class="tw:px-4 tw:py-3 tw:align-middle">${celdaMateria(p.nombre_materia, p.clave_materia)}</td>
            <td class="tw:px-4 tw:py-3 tw:align-middle tw:font-mono tw:text-xs tw:whitespace-nowrap tw:text-slate-600">${formatearFechaEspanol(p.fecha_limite)}</td>
            <td class="tw:px-4 tw:py-3 tw:align-middle">${pasados}</td>
            <td class="tw:px-4 tw:py-3 tw:align-middle">${badge(cfg.tono, cfg.icon, cfg.label)}</td>
            <td class="tw:py-3 tw:pr-5 tw:pl-4 tw:text-right tw:align-middle tw:whitespace-nowrap">
                ${accion({ icon: 'pencil', label: 'Editar prórroga', tono: 'brand', onclick: `abrirModalEdicion(${id})` })}
                ${accion({ icon: 'trash-2', label: 'Eliminar prórroga', tono: 'danger', onclick: `eliminarPermiso(${id})` })}
            </td>
        </tr>`;
    }).join('');
    refrescarIconos();
}
const filtrarTablaPermisosDebounced = debounce(filtrarTablaPermisos);

// =====================================================================
// MODAL: ASIGNAR PRÓRROGA (general o desde la matriz)
// =====================================================================
let catalogosCache = null;
let materiasCargadas = [];
let grupoCargado = null;

async function obtenerCatalogos() {
    if (!catalogosCache) {
        catalogosCache = Promise.all([
            fetchJson('/permisos-captura/docentes'),
            fetchJson('/permisos-captura/grupos'),
        ]).then(([docentes, grupos]) => ({
            docentes: Array.isArray(docentes) ? docentes : [],
            grupos: Array.isArray(grupos) ? grupos : [],
        })).catch(err => { catalogosCache = null; throw err; });
    }
    return catalogosCache;
}

function llenarSelect(select, items, placeholder, valueKey, textKey, seleccionado) {
    select.innerHTML = `<option value="">${placeholder}</option>` + items.map(it =>
        `<option value="${esc(it[valueKey])}" ${it[valueKey] == seleccionado ? 'selected' : ''}>${esc(it[textKey])}</option>`
    ).join('');
}

async function abrirModalAlta(preset = {}) {
    document.getElementById('formAltaPermiso').reset();
    const selectMateria = document.getElementById('selectAltaMateria');
    selectMateria.innerHTML = '<option value="">Primero elige un grupo</option>';
    selectMateria.disabled = true;
    document.getElementById('divCheckMateriaPasada').style.display = 'none';
    materiasCargadas = [];
    grupoCargado = null;

    try {
        const { docentes, grupos } = await obtenerCatalogos();
        llenarSelect(document.getElementById('selectAltaDocente'), docentes, 'Selecciona un docente…', 'id', 'nombre', preset.idDocente);
        llenarSelect(document.getElementById('selectAltaGrupo'), grupos, 'Selecciona un grupo…', 'id', 'clave', preset.idGrupo);

        if (preset.idGrupo) {
            await cargarMateriasPorGrupo(preset.idGrupo);
            if (preset.idMateria) {
                // Si la materia no aparece con el filtro por nivel, incluir las de otros niveles
                if (![...selectMateria.options].some(o => o.value == preset.idMateria)) {
                    document.getElementById('checkMateriaPasada').checked = true;
                    filtrarMaterias();
                }
                selectMateria.value = String(preset.idMateria);
            }
        }

        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalAltaPermiso')).show();
        setTimeout(() => document.getElementById('inputAltaFechaLimite').focus(), 350);
    } catch (err) {
        console.error('Error al cargar datos auxiliares:', err);
        Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudieron recuperar los catálogos de docentes o grupos.', confirmButtonColor: BRAND });
    }
}

function otorgarProrrogaRapida(idDocente, idGrupo, idMateria) {
    abrirModalAlta({ idDocente, idGrupo, idMateria });
}

function cargarMateriasPorGrupo(idGrupo) {
    const selectMateria = document.getElementById('selectAltaMateria');
    const loader = document.getElementById('loaderMaterias');
    const divCheck = document.getElementById('divCheckMateriaPasada');
    const checkPasada = document.getElementById('checkMateriaPasada');

    const limpiar = (texto) => {
        selectMateria.innerHTML = `<option value="">${texto}</option>`;
        selectMateria.disabled = true;
        divCheck.style.display = 'none';
        checkPasada.checked = false;
        materiasCargadas = [];
        grupoCargado = null;
    };

    if (!idGrupo) { limpiar('Primero elige un grupo'); return Promise.resolve(); }

    loader.style.display = 'inline-block';
    selectMateria.disabled = true;

    return fetchJson(`/grupos/${encodeURIComponent(idGrupo)}/calificaciones-materia`)
        .then(resp => {
            if (resp && resp.success && resp.data) {
                materiasCargadas = resp.data.materias || [];
                grupoCargado = resp.data.grupo || null;
                divCheck.style.display = 'block';
                filtrarMaterias();
            } else {
                limpiar('No hay asignaturas en este grupo');
            }
        })
        .catch(err => {
            console.error('Error al cargar materias:', err);
            limpiar('Error al cargar asignaturas');
        })
        .finally(() => { loader.style.display = 'none'; });
}

function filtrarMaterias() {
    const selectMateria = document.getElementById('selectAltaMateria');
    const docenteId = document.getElementById('selectAltaDocente').value;
    const mostrarPasadas = document.getElementById('checkMateriaPasada').checked;

    if (materiasCargadas.length === 0) {
        selectMateria.innerHTML = '<option value="">' + (document.getElementById('selectAltaGrupo').value ? 'No hay asignaturas en este grupo' : 'Primero elige un grupo') + '</option>';
        selectMateria.disabled = true;
        return;
    }

    let filtered = materiasCargadas;
    if (docenteId) {
        filtered = filtered.filter(m => m.id_docente && parseInt(m.id_docente) === parseInt(docenteId));
    }
    if (!mostrarPasadas && grupoCargado && grupoCargado.id_nivel_academico) {
        const nivel = parseInt(grupoCargado.id_nivel_academico);
        filtered = filtered.filter(m => !m.id_nivel_academico || parseInt(m.id_nivel_academico) === nivel);
    }

    if (filtered.length === 0) {
        const texto = docenteId
            ? (mostrarPasadas ? 'El docente no imparte materias en este grupo' : 'El docente no imparte materias del nivel actual')
            : 'No hay materias que coincidan';
        selectMateria.innerHTML = `<option value="">${texto}</option>`;
        selectMateria.disabled = true;
        return;
    }

    selectMateria.innerHTML = '<option value="">Selecciona una asignatura…</option>' + filtered.map(m =>
        `<option value="${esc(m.idMateria)}">${esc(m.nombreMateria)} (${esc(m.claveMateria)})${m.nombreNivel ? ', ' + esc(m.nombreNivel) : ''}</option>`
    ).join('');
    selectMateria.disabled = false;
}

function guardarNuevoPermiso() {
    const idDocente = document.getElementById('selectAltaDocente').value;
    const idGrupo = document.getElementById('selectAltaGrupo').value;
    const idMateria = document.getElementById('selectAltaMateria').value;
    const fechaLimite = document.getElementById('inputAltaFechaLimite').value;
    const checkPasados = document.getElementById('checkAltaPasados').checked ? 1 : 0;

    if (!idDocente || !idGrupo || !idMateria || !fechaLimite) {
        Swal.fire({ icon: 'warning', title: 'Campos incompletos', text: 'Selecciona docente, grupo, asignatura y fecha límite.', confirmButtonColor: BRAND });
        return;
    }

    const btn = document.getElementById('btnGuardarAlta');
    btn.disabled = true;

    fetchJson('/permisos-captura', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({
            id_docente: parseInt(idDocente),
            id_grupo: parseInt(idGrupo),
            id_materia: parseInt(idMateria),
            fecha_limite: fechaLimite,
            permitir_modificar_pasados: checkPasados
        })
    })
    .then(resp => {
        if (resp && resp.success) {
            Swal.fire({ icon: 'success', title: '¡Prórroga asignada!', text: resp.message, timer: 2000, showConfirmButton: false });
            cerrarModal('modalAltaPermiso');
            if (document.getElementById('tab-matriz-btn').classList.contains('active')) cargarMatriz();
            else cargarPermisos();
        } else {
            Swal.fire({ icon: 'error', title: 'No se pudo asignar', text: mensajeError(resp, 'Error desconocido.'), confirmButtonColor: BRAND });
        }
    })
    .catch(err => {
        console.error('Error al guardar:', err);
        Swal.fire({ icon: 'error', title: 'Error de servidor', text: 'Ocurrió un error al intentar crear la prórroga.', confirmButtonColor: BRAND });
    })
    .finally(() => { btn.disabled = false; });
}

// =====================================================================
// MODAL: EDITAR / ELIMINAR PRÓRROGA
// =====================================================================
function abrirModalEdicion(idPermiso) {
    const permiso = listadoPermisos.find(p => Number(p.id) === Number(idPermiso));
    if (!permiso) return;

    document.getElementById('inputEditId').value = permiso.id;
    document.getElementById('lblEditDocente').textContent = permiso.nombre_docente;
    document.getElementById('lblEditGrupo').textContent = permiso.clave_grupo;
    document.getElementById('lblEditMateria').textContent = `${permiso.nombre_materia} (${permiso.clave_materia})`;
    document.getElementById('inputEditFechaLimite').value = permiso.fecha_limite || '';
    document.getElementById('checkEditPasados').checked = permiso.permitir_modificar_pasados == 1;
    document.getElementById('checkEditHabilitado').checked = permiso.habilitado == 1;

    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditarPermiso')).show();
}

function guardarEdicionPermiso() {
    const id = document.getElementById('inputEditId').value;
    const fechaLimite = document.getElementById('inputEditFechaLimite').value;

    if (!id || !fechaLimite) {
        Swal.fire({ icon: 'warning', title: 'Fecha requerida', text: 'Indica la fecha límite de la prórroga.', confirmButtonColor: BRAND });
        return;
    }

    fetchJson(`/permisos-captura/${encodeURIComponent(id)}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({
            fecha_limite: fechaLimite,
            permitir_modificar_pasados: document.getElementById('checkEditPasados').checked ? 1 : 0,
            habilitado: document.getElementById('checkEditHabilitado').checked ? 1 : 0
        })
    })
    .then(resp => {
        if (resp && resp.success) {
            Swal.fire({ icon: 'success', title: '¡Actualizada!', text: resp.message, timer: 1500, showConfirmButton: false });
            cerrarModal('modalEditarPermiso');
            cargarPermisos();
        } else {
            Swal.fire({ icon: 'error', title: 'Error', text: mensajeError(resp, 'No se pudo guardar la actualización.'), confirmButtonColor: BRAND });
        }
    })
    .catch(err => {
        console.error('Error al actualizar:', err);
        Swal.fire({ icon: 'error', title: 'Error de servidor', text: 'Ocurrió un error al intentar modificar la prórroga.', confirmButtonColor: BRAND });
    });
}

function eliminarPermiso(idPermiso) {
    const permiso = listadoPermisos.find(p => Number(p.id) === Number(idPermiso));
    if (!permiso) return;

    Swal.fire({
        title: '¿Eliminar prórroga?',
        text: `${permiso.nombre_docente} perderá la prórroga en "${permiso.nombre_materia}" y volverá a los tiempos ordinarios de captura.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#64748B',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (!result.isConfirmed) return;
        fetchJson(`/permisos-captura/${encodeURIComponent(idPermiso)}`, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
        })
        .then(resp => {
            if (resp && resp.success) {
                Swal.fire({ icon: 'success', title: '¡Eliminada!', text: resp.message, timer: 1500, showConfirmButton: false });
                cargarPermisos();
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: mensajeError(resp, 'No se pudo eliminar la prórroga.'), confirmButtonColor: BRAND });
            }
        })
        .catch(err => {
            console.error('Error al eliminar:', err);
            Swal.fire({ icon: 'error', title: 'Error de servidor', text: 'Ocurrió un error al intentar eliminar la prórroga.', confirmButtonColor: BRAND });
        });
    });
}

// =====================================================================
// 3. PERIODOS POR GRUPO
// =====================================================================
const PERIODOS = [
    { key: 'p1', cap: 'P1', nombre: '1er parcial' },
    { key: 'p2', cap: 'P2', nombre: '2do parcial' },
    { key: 'p3', cap: 'P3', nombre: '3er parcial' },
    { key: 'semestral', cap: 'Semestral', nombre: 'Examen semestral' },
    { key: 'extraordinario', cap: 'Extraordinario', nombre: 'Examen extraordinario' },
];
let listadoCcts = [];
let listadoGruposCct = [];
let grupoConfiguracionActualId = null;

function mostrarAjustes(visible) {
    document.getElementById('cardAjustesGrupo').style.display = visible ? 'block' : 'none';
    document.getElementById('vacioAjustesGrupo').style.display = visible ? 'none' : 'block';
}

function cargarCctConfigList() {
    const selectCct = document.getElementById('selectCctConfig');
    const selectGrupo = document.getElementById('selectGrupoConfig');
    mostrarAjustes(false);
    selectGrupo.innerHTML = '<option value="">Primero elige un plantel</option>';
    selectGrupo.disabled = true;

    fetchJson('/permisos-captura/ccts')
        .then(data => {
            listadoCcts = Array.isArray(data) ? data : [];
            selectCct.innerHTML = '<option value="">Selecciona un plantel…</option>' +
                listadoCcts.map(c => `<option value="${esc(c.id)}">${esc(c.nombre)} (${esc(c.clave)})</option>`).join('');
        })
        .catch(err => {
            console.error('Error al cargar planteles CCT:', err);
            Swal.fire({ icon: 'error', title: 'Error de carga', text: 'No se pudo cargar el listado de planteles.', confirmButtonColor: BRAND });
        });
}

function cargarGruposConfig(idCct) {
    const selectGrupo = document.getElementById('selectGrupoConfig');
    mostrarAjustes(false);
    grupoConfiguracionActualId = null;

    if (!idCct) {
        selectGrupo.innerHTML = '<option value="">Primero elige un plantel</option>';
        selectGrupo.disabled = true;
        return;
    }

    selectGrupo.innerHTML = '<option value="">Cargando grupos…</option>';
    selectGrupo.disabled = true;

    fetchJson(`/permisos-captura/cct/${encodeURIComponent(idCct)}/grupos`)
        .then(data => {
            listadoGruposCct = Array.isArray(data) ? data : [];
            if (listadoGruposCct.length > 0) {
                selectGrupo.innerHTML = '<option value="">Selecciona un grupo…</option>' +
                    listadoGruposCct.map(g => `<option value="${esc(g.id)}">${esc(g.clave)}</option>`).join('');
                selectGrupo.disabled = false;
            } else {
                selectGrupo.innerHTML = '<option value="">No hay grupos activos en este plantel</option>';
            }
        })
        .catch(err => {
            console.error('Error al cargar grupos del CCT:', err);
            selectGrupo.innerHTML = '<option value="">Error al cargar grupos</option>';
        });
}

function cargarConfiguracionGrupo(idGrupo) {
    if (!idGrupo) {
        mostrarAjustes(false);
        grupoConfiguracionActualId = null;
        return;
    }

    grupoConfiguracionActualId = parseInt(idGrupo);
    const selectedGrupo = listadoGruposCct.find(g => g.id == idGrupo);
    document.getElementById('lblGrupoNombreTitle').textContent = `Grupo ${selectedGrupo ? selectedGrupo.clave : '—'}`;

    fetchJson(`/permisos-captura/grupo-config/${encodeURIComponent(idGrupo)}`)
        .then(resp => {
            if (!(resp && resp.success && resp.config)) {
                Swal.fire({ icon: 'error', title: 'Error', text: mensajeError(resp, 'No se pudo obtener la configuración.'), confirmButtonColor: BRAND });
                return;
            }
            const c = resp.config;

            const selectNivel = document.getElementById('selectGrupoNivelCaptura');
            selectNivel.innerHTML = '<option value="">Selecciona un semestre…</option>' +
                (resp.levels || []).map(l => `<option value="${esc(l.id)}">${esc(l.nombre)}</option>`).join('');
            selectNivel.value = c.id_nivel_academico || '';

            // BGNE solo maneja una calificación final (sin P2, P3 ni semestral)
            const isBgne = resp.isBgne === true;
            ['cardAjusteP2', 'cardAjusteP3', 'cardAjusteSemestral'].forEach(id => {
                document.getElementById(id).style.display = isBgne ? 'none' : 'block';
            });
            document.getElementById('lblGrupoP1Title').textContent = isBgne ? 'Calificación final de la asignatura' : '1er parcial';

            document.getElementById('checkGrupoHabilitada').checked = c.captura_habilitada == 1;
            PERIODOS.forEach(({ key, cap }) => {
                document.getElementById(`checkGrupo${cap}`).checked = c[`${key}_habilitado`] == 1;
                document.getElementById(`inputGrupo${cap}Inicio`).value = c[`${key}_fecha_inicio`] || '';
                document.getElementById(`inputGrupo${cap}Fin`).value = c[`${key}_fecha_fin`] || '';
            });

            toggleInputsGrupo(c.captura_habilitada == 1);
            mostrarAjustes(true);
        })
        .catch(err => {
            console.error('Error al cargar config grupo:', err);
            Swal.fire({ icon: 'error', title: 'Error de carga', text: 'No se pudo cargar la configuración de este grupo.', confirmButtonColor: BRAND });
        });
}

function toggleInputsGrupo(enabled) {
    document.getElementById('selectGrupoNivelCaptura').disabled = !enabled;
    document.querySelectorAll('.card-periodo-ajuste').forEach(card => {
        const sw = card.querySelector('.switch-grupo-sub');
        sw.disabled = !enabled;
        card.style.opacity = enabled ? '1' : '0.55';
        card.querySelectorAll('input[type="date"]').forEach(i => i.disabled = !enabled || !sw.checked);
    });
}

function toggleFilaFecha(periodKey, enabled) {
    const cap = periodKey.charAt(0).toUpperCase() + periodKey.slice(1);
    const masterEnabled = document.getElementById('checkGrupoHabilitada').checked;
    ['Inicio', 'Fin'].forEach(s => {
        const inp = document.getElementById(`inputGrupo${cap}${s}`);
        if (inp) inp.disabled = !enabled || !masterEnabled;
    });
}

function validarConfiguracionGrupo() {
    const errores = [];
    if (!document.getElementById('checkGrupoHabilitada').checked) return errores;

    if (!document.getElementById('selectGrupoNivelCaptura').value) {
        errores.push('Selecciona el semestre habilitado para captura.');
    }
    PERIODOS.forEach(({ cap, nombre }) => {
        const card = document.getElementById(`checkGrupo${cap}`).closest('.card-periodo-ajuste');
        if (card.style.display === 'none' || !document.getElementById(`checkGrupo${cap}`).checked) return;
        const ini = document.getElementById(`inputGrupo${cap}Inicio`).value;
        const fin = document.getElementById(`inputGrupo${cap}Fin`).value;
        const titulo = cap === 'P1' ? document.getElementById('lblGrupoP1Title').textContent.trim() : nombre;
        if (!ini || !fin) errores.push(`${titulo}: indica fecha de inicio y de fin.`);
        else if (ini > fin) errores.push(`${titulo}: la fecha de fin es anterior a la de inicio.`);
    });
    return errores;
}

function guardarConfiguracionGrupo() {
    if (!grupoConfiguracionActualId) return;

    const errores = validarConfiguracionGrupo();
    if (errores.length) {
        Swal.fire({
            icon: 'warning',
            title: 'Revisa los ajustes',
            html: `<ul style="text-align:left;margin:0;padding-left:1.2em;font-size:.875rem;color:#475569">${errores.map(e => `<li>${esc(e)}</li>`).join('')}</ul>`,
            confirmButtonColor: BRAND
        });
        return;
    }

    const payload = {
        id_nivel_academico: document.getElementById('selectGrupoNivelCaptura').value,
        captura_habilitada: document.getElementById('checkGrupoHabilitada').checked ? 1 : 0,
    };
    PERIODOS.forEach(({ key, cap }) => {
        payload[`${key}_habilitado`] = document.getElementById(`checkGrupo${cap}`).checked ? 1 : 0;
        payload[`${key}_fecha_inicio`] = document.getElementById(`inputGrupo${cap}Inicio`).value;
        payload[`${key}_fecha_fin`] = document.getElementById(`inputGrupo${cap}Fin`).value;
    });

    fetchJson(`/permisos-captura/grupo-config/${grupoConfiguracionActualId}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify(payload)
    })
    .then(resp => {
        if (resp && resp.success) {
            Swal.fire({ icon: 'success', title: '¡Guardado!', text: resp.message, timer: 2000, showConfirmButton: false });
        } else {
            Swal.fire({ icon: 'error', title: 'Error al guardar', text: mensajeError(resp, 'No se pudo guardar la configuración.'), confirmButtonColor: BRAND });
        }
    })
    .catch(err => {
        console.error('Error al guardar config grupo:', err);
        Swal.fire({ icon: 'error', title: 'Error de servidor', text: 'Ocurrió un error al guardar los ajustes de este grupo.', confirmButtonColor: BRAND });
    });
}

// =====================================================================
// CARGA INICIAL
// =====================================================================
document.addEventListener('DOMContentLoaded', function () {
    const modalContainer = document.getElementById('contenedorModal') || document.body;
    ['modalAltaPermiso', 'modalEditarPermiso'].forEach(id => {
        const el = document.getElementById(id);
        if (el && el.parentElement !== modalContainer) modalContainer.appendChild(el);
    });
    cargarMatriz();
});
</script>
@endsection
