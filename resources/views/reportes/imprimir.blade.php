@extends('layouts.app')

@section('content')

<!-- SweetAlert2 para diálogos estéticos -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@php
    /*
     * Clases reutilizadas en la interfaz del módulo (las comunes viven en App\View\Ui).
     * IMPORTANTE: los elementos que el JS muestra/oculta con style.display NO deben llevar
     * utilidades de display de Tailwind (tw:flex, tw:block, tw:grid...), ya que son !important.
     */
    $card      = \App\View\Ui::PANEL;
    $subcard   = ' tw:border tw:border-slate-200 tw:bg-white';
    $label     = \App\View\Ui::LABEL;
    $labelSm   = \App\View\Ui::LABEL;
    $input     = \App\View\Ui::INPUT;
    $inputSm   = \App\View\Ui::INPUT;
    $inputIcon = \App\View\Ui::INPUT_ICON;
    $fieldIcon = 'tw:pointer-events-none tw:absolute tw:left-3 tw:top-1/2 tw:size-[18px] tw:-translate-y-1/2 tw:text-slate-400';
    $textarea  = 'tw:w-full tw:border tw:border-slate-300 tw:bg-white tw:px-3 tw:py-2.5 tw:text-[0.9375rem] tw:text-slate-900 tw:outline-none tw:placeholder:text-slate-400 tw:focus:border-brand-700 tw:focus:ring-2 tw:focus:ring-brand-700/20';
    $btnBase   = 'tw:inline-flex tw:cursor-pointer tw:items-center tw:justify-center tw:gap-2 tw:text-sm tw:font-semibold tw:no-underline tw:transition-colors tw:duration-100 tw:focus-visible:outline-2 tw:focus-visible:outline-offset-2 tw:focus-visible:outline-brand-700';
    $btnPrimary   = $btnBase . ' tw:h-10 tw:border-0 tw:bg-brand-700 tw:px-4 tw:text-white tw:hover:bg-brand-800';
    $btnSecondary = $btnBase . ' tw:h-10 tw:border tw:border-slate-300 tw:bg-white tw:px-4 tw:text-slate-800 tw:hover:border-slate-400 tw:hover:bg-slate-50';
    $iconBtn   = 'tw:absolute tw:right-1.5 tw:top-1/2 tw:size-7 tw:-translate-y-1/2 tw:cursor-pointer tw:items-center tw:justify-center tw:border-0 tw:bg-transparent tw:p-0 tw:text-slate-400 tw:hover:bg-slate-100 tw:hover:text-slate-700';
    // Contenedor de sugerencias (Bootstrap .list-group, el JS controla su display)
    $sugerencias = 'list-group tw:absolute tw:inset-x-0 tw:top-full tw:z-30 tw:mt-1 tw:max-h-64 tw:overflow-y-auto tw:border tw:border-slate-300 tw:bg-white tw:p-1 tw:shadow-raised';
    $chip      = 'tw:inline-flex tw:items-center tw:gap-1 tw:px-2.5 tw:py-0.5 tw:text-[0.75rem] tw:font-semibold';
    $sectionTitle = \App\View\Ui::SECTION_TITLE . ' tw:flex tw:items-center tw:gap-2';
@endphp

<div class="tw:mx-auto tw:flex tw:max-w-[1600px] tw:flex-col tw:gap-6">

    {{-- ===================== ENCABEZADO + PLANTEL ===================== --}}
    <x-ui.page-header title="Módulo de impresión" :back="url()->previous()">
        Elige el plantel y el formato; los datos del alumno o grupo se llenan solos.
        <x-slot:actions>
            {{-- Selector de plantel (CCT). El JS alterna la clase .active --}}
            <div role="tablist" aria-label="Plantel" class="tw:inline-flex tw:gap-1 tw:self-start tw:border tw:border-slate-200 tw:bg-white tw:p-1 tw:lg:self-auto">
                @foreach ([['cct-bti', 'BTI', 'BTI', 'Tecnológico'], ['cct-bgne', 'BGNE', 'BGNE', 'No escolarizado'], ['cct-inf', 'INF', 'Informática', 'Técnico']] as [$id, $val, $nombre, $sub])
                <button type="button" id="{{ $id }}" onclick="selectCCT('{{ $val }}')"
                    @class([
                        'cct-tab tw:flex tw:cursor-pointer tw:flex-col tw:items-start tw:border-0 tw:bg-transparent tw:px-4 tw:py-1.5 tw:text-left tw:leading-tight tw:text-slate-500 tw:hover:bg-slate-100 tw:hover:text-slate-900 tw:focus-visible:outline-2 tw:focus-visible:outline-brand-700',
                        'tw:[&.active]:bg-brand-700 tw:[&.active]:text-white tw:[&.active]:hover:bg-brand-700',
                        'active' => $loop->first,
                    ])>
                    <span class="tw:text-[0.9375rem] tw:font-semibold">{{ $nombre }}</span>
                    <span class="tw:text-[0.8125rem]">{{ $sub }}</span>
                </button>
                @endforeach
            </div>
        </x-slot:actions>
    </x-ui.page-header>

    <!-- Elemento auxiliar para mantener compatibilidad con scripts existentes -->
    <div id="placeholder-select-cct" style="display: none;"></div>

    {{-- ===================== MASTER / DETAIL ===================== --}}
    {{-- El JS asigna style.display = 'grid' a este contenedor --}}
    <div id="formatos-container" style="display: grid;"
        class="tw:grid-cols-1 tw:items-start tw:gap-6 tw:lg:grid-cols-[264px_minmax(0,1fr)]">

        {{-- Menú de formatos (generado por JS) --}}
        <aside class="{{ $card }} tw:p-2 tw:lg:sticky tw:lg:top-[84px]">
            <p class="tw:m-0 tw:mb-1 tw:px-3 tw:pt-1 tw:text-[0.875rem] tw:text-slate-500">Formatos de <span id="lbl-cct-activo" class="tw:font-semibold tw:text-slate-800">BTI</span></p>
            <nav id="submodulos-nav" role="tablist" aria-orientation="vertical"
                class="tw:flex tw:flex-col tw:gap-0.5">
                <!-- Los enlaces de pestañas se generarán dinámicamente vía JS -->
            </nav>
        </aside>

        {{-- Panel del formato seleccionado --}}
        <section class="{{ $card }} tw:min-w-0 tw:p-6 tw:lg:p-7">
            <div class="tab-content" id="submodulos-tab-content">

                {{-- ============ CONSTANCIA DE ESTUDIOS ============ --}}
                <div class="tab-pane fade" id="pane-constancia" role="tabpanel">
                    <x-imprimir.pane-header title="Constancia de estudios">
                        Oficio que acredita que el alumno estudia en el plantel o, en BGNE, que ya concluyó su bachillerato.
                    </x-imprimir.pane-header>

                    <div class="tw:grid tw:grid-cols-1 tw:gap-4 tw:md:grid-cols-3">
                        <div class="tw:relative tw:md:col-span-2">
                            <label for="constanciaAlumnoSearch" class="{{ $label }}">Alumno</label>
                            <div class="tw:relative">
                                <i data-lucide="search" class="{{ $fieldIcon }}" aria-hidden="true"></i>
                                <input type="text" id="constanciaAlumnoSearch" class="{{ $inputIcon }}" autocomplete="off" placeholder="Matrícula o nombre del alumno" onkeyup="simularBusquedaAlumnoConstancia(this.value)">
                            </div>
                            <div id="constancia-alumno-sugerencia" class="{{ $sugerencias }}" style="display: none;"></div>
                        </div>
                        <div id="constancia-tipo-wrapper">
                            <label for="constanciaTipoSelect" class="{{ $label }}">Tipo de constancia</label>
                            <select id="constanciaTipoSelect" class="{{ $input }}">
                                <option value="simple">Constancia simple</option>
                                <option value="calif">Con calificaciones</option>
                                <option value="conducta">De buena conducta</option>
                            </select>
                        </div>
                    </div>

                    <p id="constancia-preview-placeholder" class="tw:m-0 tw:mt-6 tw:bg-brand-50 tw:px-4 tw:py-3 tw:text-[0.9375rem] tw:text-slate-700">
                        Escribe al menos dos letras del nombre o dígitos de la matrícula y elige al alumno de la lista.
                    </p>

                    <div id="constancia-info-alumno" class="tw:mt-6" style="display: none;">
                        <div class="tw:flex tw:flex-wrap tw:items-center tw:justify-between tw:gap-4 tw:border-b tw:border-slate-200 tw:pb-4">
                            <div class="tw:min-w-0">
                                <p class="tw:m-0 tw:truncate tw:text-[1.125rem] tw:font-semibold tw:leading-tight tw:text-slate-900" id="lbl-constancia-nombre"></p>
                                <p class="tw:m-0 tw:mt-0.5 tw:text-[0.875rem] tw:text-slate-600" id="lbl-constancia-carrera"></p>
                            </div>
                            <x-ui.button icon="printer" onclick="generarConstancia()">Generar constancia</x-ui.button>
                        </div>

                        {{-- Datos del oficio (solo BTI) a la izquierda; la hoja tal como saldrá impresa a la derecha --}}
                        <div id="constancia-bti-campos" class="tw:mt-5" style="display: none;">
                            <div class="tw:grid tw:grid-cols-1 tw:items-start tw:gap-6 tw:xl:grid-cols-[minmax(0,300px)_minmax(0,1fr)]">
                                <div class="tw:flex tw:flex-col tw:gap-4">
                                    <x-ui.field label="Número de oficio" for="constanciaOficio">
                                        <input type="text" id="constanciaOficio" class="{{ $input }}" placeholder="2026/09/BTI/001" oninput="actualizarPreviewConstancia()">
                                    </x-ui.field>
                                    <x-ui.field label="Semestre que cursa" for="constanciaSemestre">
                                        <select id="constanciaSemestre" class="{{ $input }}" onchange="actualizarPreviewConstancia()">
                                            @foreach (['1ER', '2DO', '3ER', '4TO', '5TO', '6TO'] as $sem)
                                                <option value="{{ $sem }} SEMESTRE">{{ $sem }} SEMESTRE</option>
                                            @endforeach
                                        </select>
                                    </x-ui.field>
                                    <div class="tw:grid tw:grid-cols-2 tw:gap-3">
                                        <x-ui.field label="Inicio del periodo" for="constanciaFechaInicio">
                                            <input type="date" id="constanciaFechaInicio" class="{{ $input }}" oninput="actualizarPreviewConstancia()">
                                        </x-ui.field>
                                        <x-ui.field label="Fin del periodo" for="constanciaFechaFin">
                                            <input type="date" id="constanciaFechaFin" class="{{ $input }}" oninput="actualizarPreviewConstancia()">
                                        </x-ui.field>
                                    </div>
                                    <x-ui.field label="Fecha de expedición" for="constanciaFechaExpedicion">
                                        <input type="date" id="constanciaFechaExpedicion" class="{{ $input }}" oninput="actualizarPreviewConstancia()">
                                    </x-ui.field>
                                    <p id="constancia-aviso-matricula" class="tw:m-0 tw:border tw:border-warning-100 tw:bg-warning-50 tw:px-3 tw:py-2 tw:text-[0.875rem] tw:text-slate-800" style="display: none;">
                                        Este alumno no tiene número de control, así que la constancia dirá S/N. Regístralo en su expediente si debe aparecer.
                                    </p>
                                </div>

                                {{-- Hoja carta a escala: es el elemento protagonista del formato --}}
                                <figure class="tw:m-0 tw:bg-slate-100 tw:p-4 tw:sm:p-6">
                                    <div id="constancia-preview-doc" role="img" aria-label="Vista previa de la constancia"
                                         class="tw:mx-auto tw:aspect-[8.5/11] tw:w-full tw:max-w-[540px] tw:overflow-hidden tw:bg-white tw:px-[7%] tw:py-[5%] tw:font-[Helvetica,Arial,sans-serif] tw:leading-[1.75] tw:text-black tw:shadow-raised tw:@container">
                                        {{-- Tamaños en cqw: el texto escala con el ancho de la hoja, como un PDF reducido --}}
                                        <div class="tw:text-[2.7cqw]">
                                        <div class="tw:flex tw:items-center tw:gap-[3%]">
                                            <img src="{{ asset('img/logo.png') }}" alt="" class="tw:w-[15%] tw:shrink-0">
                                            <div class="tw:flex-1">
                                                <div class="tw:bg-[#1A56DB] tw:py-[1.5%] tw:whitespace-nowrap tw:text-center tw:text-[3cqw] tw:font-bold tw:leading-tight tw:text-white">BACHILLERATO TECNOLOGICO INTERAMERICANO</div>
                                                <div class="tw:bg-[#6F9EEA] tw:py-[0.8%] tw:text-center tw:text-[1.9cqw] tw:leading-snug tw:text-white">{{ config('plantel.bti.direccion') }}<br>Clave SEP {{ config('plantel.bti.clave_sep') }} &nbsp;&nbsp;&nbsp; {{ config('plantel.bti.rvoe') }}</div>
                                            </div>
                                        </div>
                                        <p class="tw:m-0 tw:mt-[5%] tw:text-right">OFICIO NO. <span id="cp-oficio"></span><br>ASUNTO: <b>CONSTANCIA DE ESTUDIO</b></p>
                                        <p class="tw:m-0 tw:mt-[5%] tw:font-bold">A QUIEN CORRESPONDA.<br>PRESENTE.</p>
                                        <p class="tw:m-0 tw:ml-[8%] tw:mt-[4%] tw:text-justify">EL QUE SUSCRIBE {{ config('plantel.bti.director') }}, {{ config('plantel.bti.director_cargo') }} DENOMINADO <b>{{ config('plantel.bti.nombre') }}</b>, CON CLAVE OPERATIVA <b>{{ config('plantel.bti.clave_operativa') }}</b> Y CLAVE DE CENTRO DE TRABAJO <b>{{ config('plantel.bti.cct') }}</b>, UBICADO EN {{ config('plantel.bti.direccion_texto') }}. HACE CONSTAR QUE EL C.</p>
                                        <p class="tw:m-0 tw:my-[3%] tw:text-center tw:font-bold" id="cp-nombre"></p>
                                        <p class="tw:m-0 tw:text-justify">CON NÚMERO DE CONTROL <b id="cp-matricula"></b> SE ENCUENTRA CURSANDO ACTUALMENTE EL <b id="cp-semestre"></b><span id="cp-periodo"></span>, CORRESPONDIENTE AL PLAN DE ESTUDIOS DEL {{ config('plantel.bti.sistema') }}, EN ESTA INSTITUCIÓN.</p>
                                        <p class="tw:m-0 tw:mt-[3%] tw:text-center" id="cp-fecha"></p>
                                        <div class="tw:mx-auto tw:mt-[7%] tw:w-[62%] tw:border-t tw:border-black tw:pt-[1%] tw:text-center tw:font-bold">{{ config('plantel.bti.director') }}<br>{{ config('plantel.bti.director_cargo') }}</div>
                                        <div class="tw:mt-[4%] tw:bg-[#6F9EEA] tw:py-[1%] tw:text-center tw:text-[2.1cqw] tw:text-white">{{ config('plantel.bti.lema') }}</div>
                                        </div>
                                    </div>
                                    <figcaption class="tw:mt-3 tw:text-center tw:text-[0.8125rem] tw:text-slate-600">Así saldrá la hoja. Al cambiar los datos de la izquierda se actualiza aquí.</figcaption>
                                </figure>
                            </div>
                        </div>

                        {{-- Datos de la constancia BGNE. La vista previa es el PDF real generado con estos datos. --}}
                        <div id="constancia-bgne-campos" class="tw:mt-5" style="display: none;">
                            <div class="tw:grid tw:grid-cols-1 tw:items-start tw:gap-6 tw:xl:grid-cols-[minmax(0,320px)_minmax(0,1fr)]">
                                <div class="tw:flex tw:flex-col tw:gap-4">
                                    <x-ui.field label="Tipo de constancia" for="constanciaBgneTipo">
                                        <select id="constanciaBgneTipo" class="{{ $input }}" onchange="onTipoConstanciaBgne()">
                                            <option value="cursando">Está cursando</option>
                                            <option value="concluyo">Concluyó el bachillerato</option>
                                        </select>
                                    </x-ui.field>
                                    <div class="tw:grid tw:grid-cols-[110px_minmax(0,1fr)] tw:gap-3">
                                        <x-ui.field label="Tratamiento" for="constanciaBgneTratamiento">
                                            <select id="constanciaBgneTratamiento" class="{{ $input }}" onchange="programarPreviewBgne()">
                                                <option value="EL">El C.</option>
                                                <option value="LA">La C.</option>
                                            </select>
                                        </x-ui.field>
                                        <x-ui.field label="Número de oficio" for="constanciaBgneOficio">
                                            <input type="text" id="constanciaBgneOficio" class="{{ $input }}" placeholder="Vacío si no lleva" oninput="programarPreviewBgne()">
                                        </x-ui.field>
                                    </div>

                                    {{-- Alumno que está cursando --}}
                                    <div id="constancia-bgne-cursando" class="tw:flex tw:flex-col tw:gap-4">
                                        <x-ui.field label="Trimestre que cursa" for="constanciaBgneTrimestre">
                                            <select id="constanciaBgneTrimestre" class="{{ $input }}" onchange="programarPreviewBgne()">
                                                @foreach (['Primer', 'Segundo', 'Tercer', 'Cuarto', 'Quinto', 'Sexto'] as $i => $t)
                                                    <option value="{{ $i + 1 }}">{{ $t }} trimestre</option>
                                                @endforeach
                                            </select>
                                        </x-ui.field>
                                        <div class="tw:grid tw:grid-cols-2 tw:gap-3">
                                            <x-ui.field label="Inicio del periodo" for="constanciaBgneInicio">
                                                <input type="date" id="constanciaBgneInicio" class="{{ $input }}" oninput="programarPreviewBgne()">
                                            </x-ui.field>
                                            <x-ui.field label="Fin del periodo" for="constanciaBgneFin">
                                                <input type="date" id="constanciaBgneFin" class="{{ $input }}" oninput="programarPreviewBgne()">
                                            </x-ui.field>
                                        </div>
                                        <label class="tw:flex tw:cursor-pointer tw:items-center tw:gap-2 tw:text-[0.875rem] tw:font-medium tw:text-slate-700">
                                            <input type="checkbox" id="constanciaBgneExtra" class="tw:size-4 tw:accent-brand-700" onchange="onExtraConstanciaBgne()">
                                            Incluir curso extracurricular
                                        </label>
                                        <div id="constancia-bgne-extra-campos" class="tw:flex-col tw:gap-3 tw:border tw:border-slate-200 tw:bg-slate-50 tw:p-3" style="display: none;">
                                            <x-ui.field label="Curso" for="constanciaBgneCurso">
                                                <input type="text" id="constanciaBgneCurso" class="{{ $input }}" value="COMPUTACIÓN" oninput="programarPreviewBgne()">
                                            </x-ui.field>
                                            <div class="tw:grid tw:grid-cols-1 tw:gap-3">
                                                <x-ui.field label="Días" for="constanciaBgneDias">
                                                    <input type="text" id="constanciaBgneDias" class="{{ $input }}" placeholder="SÁBADOS" oninput="programarPreviewBgne()">
                                                </x-ui.field>
                                                <x-ui.field label="Horario" for="constanciaBgneHorario">
                                                    <input type="text" id="constanciaBgneHorario" class="{{ $input }}" value="JORNADA COMPLETA" oninput="programarPreviewBgne()">
                                                </x-ui.field>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Alumno que ya concluyó --}}
                                    <div id="constancia-bgne-concluyo" class="tw:flex-col tw:gap-4" style="display: none;">
                                        <x-ui.field label="Fecha de la última materia" for="constanciaBgneUltimaMateria">
                                            <input type="date" id="constanciaBgneUltimaMateria" class="{{ $input }}" oninput="programarPreviewBgne()">
                                        </x-ui.field>
                                        <div class="tw:grid tw:grid-cols-2 tw:gap-3">
                                            <x-ui.field label="Expedición del certificado" for="constanciaBgneFechaCert">
                                                <input type="date" id="constanciaBgneFechaCert" class="{{ $input }}" oninput="programarPreviewBgne()">
                                            </x-ui.field>
                                            <x-ui.field label="Folio del certificado" for="constanciaBgneFolio">
                                                <input type="text" id="constanciaBgneFolio" class="{{ $input }}" oninput="programarPreviewBgne()">
                                            </x-ui.field>
                                        </div>
                                    </div>

                                    <x-ui.field label="Fecha de expedición" for="constanciaBgneExpedicion">
                                        <input type="date" id="constanciaBgneExpedicion" class="{{ $input }}" oninput="programarPreviewBgne()">
                                    </x-ui.field>
                                    <p id="constancia-bgne-aviso" class="tw:m-0 tw:border tw:border-warning-100 tw:bg-warning-50 tw:px-3 tw:py-2 tw:text-[0.875rem] tw:text-slate-800" style="display: none;">
                                        Este alumno no tiene matrícula registrada, así que la constancia dirá S/N. Regístrala en su expediente si debe aparecer.
                                    </p>
                                </div>

                                <figure class="tw:m-0 tw:bg-slate-100 tw:p-4 tw:sm:p-6">
                                    <iframe id="constancia-bgne-preview" title="Vista previa de la constancia BGNE"
                                            class="tw:block tw:h-[760px] tw:w-full tw:border tw:border-slate-300 tw:bg-white"></iframe>
                                    <figcaption class="tw:mt-3 tw:text-center tw:text-[0.8125rem] tw:text-slate-600" id="constancia-bgne-preview-estado">Es el PDF tal como se imprimirá. Se actualiza al cambiar los datos.</figcaption>
                                </figure>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ KÁRDEX ============ --}}
                <div class="tab-pane fade" id="pane-kardex" role="tabpanel">
                    <x-imprimir.pane-header icon="scroll-text" title="Kárdex académico">
                        Historial completo de asignaturas, calificaciones oficiales y promedios por periodo.
                    </x-imprimir.pane-header>

                    <div class="tw:relative">
                        <label for="kardexAlumnoSearch" class="{{ $label }}">Alumno</label>
                        <div class="tw:relative">
                            <i data-lucide="search" class="{{ $fieldIcon }}"></i>
                            <input type="text" id="kardexAlumnoSearch" class="{{ $inputIcon }} tw:pr-10" autocomplete="off" placeholder="Matrícula o nombre del alumno…" onkeyup="simularBusquedaAlumnoKardex(this.value)">
                            <button type="button" class="{{ $iconBtn }} tw:inline-flex" onclick="limpiarBusquedaKardex()" aria-label="Limpiar búsqueda" title="Limpiar búsqueda">
                                <i data-lucide="x" class="tw:size-4"></i>
                            </button>
                        </div>
                        <div id="kardex-alumno-sugerencia" class="{{ $sugerencias }}" style="display: none;"></div>
                    </div>

                    <div id="kardex-info-alumno" class="tw:mt-6" style="display: none;">
                        {{-- Resumen del alumno --}}
                        <div class="{{ $subcard }} tw:flex tw:flex-wrap tw:items-center tw:justify-between tw:gap-5 tw:bg-slate-50/60 tw:p-5">
                            <div class="tw:min-w-0">
                                <div class="tw:flex tw:flex-wrap tw:items-center tw:gap-2">
                                    <p class="tw:m-0 tw:text-base tw:font-semibold tw:text-slate-900" id="lbl-kardex-nombre"></p>
                                    <span class="{{ $chip }} tw:bg-brand-50 tw:text-brand-700 tw:ring-1 tw:ring-brand-200" id="lbl-kardex-badge-cct">BTI</span>
                                    <span class="{{ $chip }} tw:bg-success-100 tw:text-success-700" id="lbl-kardex-status">ACTIVO</span>
                                </div>
                                <p class="tw:m-0 tw:mt-1 tw:text-xs tw:text-slate-500">
                                    <span id="lbl-kardex-matricula"></span>
                                    <span class="tw:-ml-1 tw:text-slate-500">, </span>
                                    <span id="lbl-kardex-generacion-grupo"></span>
                                </p>
                            </div>
                            <div class="tw:flex tw:flex-wrap tw:items-center tw:gap-3">
                                <div class=" tw:border tw:border-slate-200 tw:bg-white tw:px-4 tw:py-2 tw:text-center">
                                    <span class="tw:block tw:text-[0.625rem] tw:font-semibold tw:uppercase tw:tracking-[0.08em] tw:text-slate-400">Promedio</span>
                                    <span class="tw:text-xl tw:font-bold tw:tabular-nums tw:text-brand-700" id="lbl-kardex-promedio">—</span>
                                </div>
                                <button type="button" class="{{ $btnSecondary }}" onclick="mostrarModalKardexManual()">
                                    <i data-lucide="sliders-horizontal" class="tw:size-4"></i> Ajustar
                                </button>
                                <button type="button" class="{{ $btnPrimary }}" onclick="imprimirKardex()">
                                    <i data-lucide="printer" class="tw:size-4"></i> Imprimir kárdex
                                </button>
                            </div>
                        </div>

                        {{-- Vista previa oficial --}}
                        <div class="tw:mt-5 tw:border tw:border-slate-200 tw:bg-slate-100/70 tw:p-4 tw:sm:p-6" id="kardex-preview-wrapper">
                            <div class="tw:mb-4 tw:flex tw:flex-wrap tw:items-center tw:justify-between tw:gap-2">
                                <p class="{{ $sectionTitle }} tw:mb-0"><i data-lucide="eye" class="tw:size-3.5"></i> Vista previa del formato oficial</p>
                                <span class="tw:inline-flex tw:items-center tw:gap-1 tw:text-xs tw:text-success-700"><i data-lucide="circle-check" class="tw:size-3.5"></i> Listo para imprimir</span>
                            </div>
                            <div id="kardex-live-preview" class="tw:overflow-x-auto tw:bg-white tw:p-4 tw:shadow-card">
                                <!-- Se renderiza dinámicamente con la estructura oficial del Kárdex -->
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ BOLETA (BTI) ============ --}}
                <div class="tab-pane fade" id="pane-boleta" role="tabpanel">
                    <x-imprimir.pane-header icon="file-spreadsheet" title="Boleta de calificaciones">
                        Boleta por alumno y semestre (BTI).
                    </x-imprimir.pane-header>

                    <div class="tw:grid tw:grid-cols-1 tw:gap-4 tw:md:grid-cols-3">
                        <div class="tw:relative tw:md:col-span-2">
                            <label for="boletaAlumnoSearch" class="{{ $label }}">Alumno</label>
                            <div class="tw:relative">
                                <i data-lucide="search" class="{{ $fieldIcon }}"></i>
                                <input type="text" id="boletaAlumnoSearch" class="{{ $inputIcon }}" autocomplete="off" placeholder="Matrícula o nombre del alumno…" onkeyup="simularBusquedaAlumnoBoleta(this.value)">
                            </div>
                            <div id="boleta-alumno-sugerencia" class="{{ $sugerencias }}" style="display: none;"></div>
                        </div>
                        <div>
                            <label for="boletaSemestreSelect" class="{{ $label }}">Semestre</label>
                            <select id="boletaSemestreSelect" class="{{ $input }}" onchange="document.getElementById('lbl-boleta-semestre-val').innerText = this.value">
                                <option value="1er Semestre">1er semestre</option>
                                <option value="2° Semestre">2° semestre</option>
                                <option value="3er Semestre" selected>3er semestre</option>
                                <option value="4° Semestre">4° semestre</option>
                                <option value="5° Semestre">5° semestre</option>
                                <option value="6° Semestre">6° semestre</option>
                            </select>
                        </div>
                    </div>

                    <div id="boleta-tabla-resultados" class="tw:mt-6" style="display: none;">
                        <div class="{{ $subcard }} tw:bg-slate-50/60 tw:p-5">
                            <div class="tw:flex tw:flex-wrap tw:items-center tw:justify-between tw:gap-4">
                                <div class="tw:flex tw:min-w-0 tw:items-center tw:gap-3">
                                    <span class="tw:inline-flex tw:size-11 tw:shrink-0 tw:items-center tw:justify-center tw:bg-brand-700 tw:text-white"><i data-lucide="graduation-cap" class="tw:size-5"></i></span>
                                    <div class="tw:min-w-0">
                                        <p class="tw:m-0 tw:truncate tw:text-[0.9375rem] tw:font-semibold tw:text-slate-900" id="lbl-boleta-alumno-nombre"></p>
                                        <p class="tw:m-0 tw:mt-0.5 tw:text-xs tw:text-slate-500">
                                            Matrícula <strong class="tw:font-mono tw:font-semibold tw:text-slate-700" id="lbl-boleta-alumno-matricula"></strong>
                                            <span class="tw:-ml-1 tw:text-slate-500">, </span>
                                            <strong class="tw:font-semibold tw:text-slate-700" id="lbl-boleta-semestre-val"></strong>
                                        </p>
                                    </div>
                                </div>
                                <button type="button" class="{{ $btnPrimary }}" onclick="generarBoletaBTIDesdeSelect()">
                                    <i data-lucide="printer" class="tw:size-4"></i> Imprimir boleta
                                </button>
                            </div>
                            <div class="tw:mt-4 tw:flex tw:flex-wrap tw:gap-x-6 tw:gap-y-2 tw:border-t tw:border-slate-200 tw:pt-4">
                                <label class="tw:inline-flex tw:cursor-pointer tw:items-center tw:gap-2 tw:text-[0.8125rem] tw:text-slate-700">
                                    <input type="checkbox" id="chkBoletaIncluirAsistencias" class="tw:size-4 tw:cursor-pointer tw:accent-brand-700" checked>
                                    Incluir asistencias
                                </label>
                                <label class="tw:inline-flex tw:cursor-pointer tw:items-center tw:gap-2 tw:text-[0.8125rem] tw:text-slate-700">
                                    <input type="checkbox" id="chkBoletaIncluirReportes" class="tw:size-4 tw:cursor-pointer tw:accent-brand-700" checked>
                                    Incluir reportes de conducta
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ REPORTES DE INDISCIPLINA ============ --}}
                <div class="tab-pane fade" id="pane-reporte_indisciplina" role="tabpanel">
                    <x-imprimir.pane-header icon="shield-alert" title="Reportes de indisciplina">
                        Registra incidencias y genera el formato oficial a doble talón (BTI).
                    </x-imprimir.pane-header>

                    <div class="tw:grid tw:grid-cols-1 tw:gap-6 tw:2xl:grid-cols-[minmax(0,5fr)_minmax(0,7fr)]">
                        {{-- Registro --}}
                        <div>
                            <p class="{{ $sectionTitle }}"><i data-lucide="plus" class="tw:size-3.5"></i> Nuevo reporte</p>
                            <form id="formCrearReporte" onsubmit="registrarReporteIndisciplina(event)" class="tw:flex tw:flex-col tw:gap-4">
                                <div class="tw:relative">
                                    <label for="reporteAlumnoSearch" class="{{ $label }}">Alumno</label>
                                    <div class="tw:relative">
                                        <i data-lucide="search" class="{{ $fieldIcon }}"></i>
                                        <input type="text" id="reporteAlumnoSearch" class="{{ $inputIcon }}" autocomplete="off" placeholder="Nombre del alumno…" onkeyup="buscarAlumnoReporte(this.value)">
                                    </div>
                                    <div id="reporte-alumno-sugerencia" class="{{ $sugerencias }}" style="display: none;"></div>
                                </div>

                                <div id="reporte-alumno-info-box" style="display: none;">
                                    <input type="hidden" id="reporte_id_alumno">
                                    <input type="hidden" id="reporte_alumno_nombre">
                                    <div class="tw:flex tw:items-center tw:justify-between tw:gap-3 tw:border tw:border-brand-200 tw:bg-brand-50 tw:px-4 tw:py-3">
                                        <div class="tw:min-w-0">
                                            <span class="tw:block tw:truncate tw:text-sm tw:font-semibold tw:text-slate-900" id="lbl-reporte-alumno-nom"></span>
                                            <small class="tw:text-xs tw:text-slate-500" id="lbl-reporte-alumno-mat"></small>
                                        </div>
                                        <button type="button" onclick="deseleccionarAlumnoReporte()" aria-label="Quitar alumno" title="Quitar alumno"
                                            class="tw:inline-flex tw:size-8 tw:shrink-0 tw:cursor-pointer tw:items-center tw:justify-center tw:border-0 tw:bg-transparent tw:text-slate-400 tw:transition-colors tw:hover:bg-white tw:hover:text-danger-600">
                                            <i data-lucide="x" class="tw:size-4"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="tw:grid tw:grid-cols-1 tw:gap-4 tw:sm:grid-cols-[minmax(0,1fr)_140px]">
                                    <div>
                                        <label for="reporte_tutor_nombre" class="{{ $label }}">Tutor</label>
                                        <input type="text" id="reporte_tutor_nombre" class="{{ $input }}" placeholder="Nombre completo del tutor" required>
                                    </div>
                                    <div>
                                        <label for="reporte_parcial" class="{{ $label }}">Parcial</label>
                                        <select id="reporte_parcial" class="{{ $input }}" required>
                                            <option value="1">Parcial 1</option>
                                            <option value="2">Parcial 2</option>
                                            <option value="3">Parcial 3</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label for="reporte_incidente" class="{{ $label }}">Descripción del incidente</label>
                                    <textarea id="reporte_incidente" class="{{ $textarea }}" rows="4" placeholder="Describe la indisciplina con detalle…" required></textarea>
                                </div>

                                <button type="submit" class="{{ $btnPrimary }} tw:w-full">
                                    <i data-lucide="save" class="tw:size-4"></i> Registrar y generar reporte
                                </button>
                            </form>
                        </div>

                        {{-- Historial --}}
                        <div class="tw:min-w-0">
                            <div class="tw:mb-3 tw:flex tw:flex-wrap tw:items-center tw:justify-between tw:gap-3">
                                <p class="{{ $sectionTitle }} tw:mb-0"><i data-lucide="history" class="tw:size-3.5"></i> Historial</p>
                                <div class="tw:relative tw:w-full tw:sm:w-64">
                                    <i data-lucide="search" class="{{ $fieldIcon }} tw:size-4"></i>
                                    <input type="text" id="reportesHistorialSearch" class="{{ $inputSm }} tw:pl-9" placeholder="Alumno o folio…" onkeyup="cargarHistorialReportes(this.value)">
                                </div>
                            </div>
                            <div class="tw:max-h-[520px] tw:overflow-auto tw:border tw:border-slate-200">
                                <table class="tw:w-full tw:border-collapse tw:text-left">
                                    <thead class="tw:sticky tw:top-0 tw:z-10 tw:bg-slate-50">
                                        <tr class="tw:border-b tw:border-slate-200">
                                            @foreach (['Folio', 'Alumno', 'Tutor', 'Parcial', 'Fecha'] as $th)
                                            <th class="tw:px-4 tw:py-2.5 tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-500">{{ $th }}</th>
                                            @endforeach
                                            <th class="tw:px-4 tw:py-2.5 tw:text-right tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-500"><span class="tw:sr-only">Acciones</span></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabla-reportes-historial" class="tw:divide-y tw:divide-slate-100">
                                        <tr><td colspan="6" class="tw:px-4 tw:py-8 tw:text-center tw:text-[0.8125rem] tw:text-slate-500">Cargando historial…</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ CREDENCIAL ============ --}}
                <div class="tab-pane fade" id="pane-credencial" role="tabpanel">
                    <x-imprimir.pane-header icon="id-card" title="Credencial escolar" id="cred-descripcion-subtitulo">
                        Emisión de credenciales oficiales institucionales en formato estándar PVC / Opalina (Sistema Escolarizado).
                        <x-slot:aside>
                            <span class="{{ $chip }} tw:bg-brand-700 tw:px-3 tw:py-1 tw:text-white" id="cred-tipo-formato-badge">
                                <i class="ti ti-award me-1"></i> Formato Oficial Escolarizado
                            </span>
                        </x-slot:aside>
                    </x-imprimir.pane-header>

                    <div class="tw:relative">
                        <label for="credencialAlumnoSearch" class="{{ $label }}">Alumno</label>
                        <div class="tw:relative">
                            <i data-lucide="search" class="{{ $fieldIcon }}"></i>
                            <input type="text" id="credencialAlumnoSearch" class="{{ $inputIcon }}" autocomplete="off" placeholder="Matrícula o nombre del alumno para llenar los datos…" onkeyup="simularBusquedaAlumnoCredencial(this.value)">
                        </div>
                        <div id="credencial-alumno-sugerencia" class="{{ $sugerencias }}" style="display: none;"></div>
                    </div>

                    <div id="credencial-info-alumno" class="tw:mt-6" style="display: block;">
                        <div class="tw:grid tw:grid-cols-1 tw:items-start tw:gap-6 tw:2xl:grid-cols-[minmax(0,7fr)_minmax(0,5fr)]">

                            {{-- Vista previa (diseño oficial, no modificar clases bti-*) --}}
                            <div class=" tw:border tw:border-slate-200 tw:bg-slate-100/70 tw:p-5">
                                <div class="tw:mb-4 tw:flex tw:flex-wrap tw:items-center tw:justify-between tw:gap-2">
                                    <p class="{{ $sectionTitle }} tw:mb-0"><i data-lucide="eye" class="tw:size-3.5"></i> Vista previa en tiempo real</p>
                                    <span class=" tw:bg-white tw:px-2 tw:py-0.5 tw:font-mono tw:text-[0.6875rem] tw:text-slate-500 tw:ring-1 tw:ring-slate-200">CR80, 54 × 85.6 mm</span>
                                </div>

                                <div class="tw:overflow-x-auto tw:pb-1">
                                    <div class="tw:mx-auto tw:w-[500px]">
                                        <div class="tw:mb-2 tw:grid tw:grid-cols-2 tw:text-center tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.08em] tw:text-slate-400">
                                            <span>Anverso</span><span>Reverso</span>
                                        </div>

                                        <!-- Contenedor Unificado Dúplex (Ambas Caras Juntas) -->
                                        <div class="bti-card-spread shadow-lg position-relative">
                                            <!-- ANVERSO -->
                                            <div class="bti-card bti-card-front bti-card-preview-left position-relative" id="cred-card-front-preview">
                                                <div class="bti-front-stripes"></div>
                                                <div class="bti-map-bg"></div>
                                                <div class="bti-header-banner" id="cred-header-banner">
                                                    <div class="bti-header-title" id="cred-header-title">BACHILLERATO TECNOLOGICO</div>
                                                    <div class="bti-header-subtitle" id="cred-header-subtitle">INTERAMERICANO</div>
                                                </div>
                                                <div class="bti-sub-header">
                                                    <div class="bti-cct-text" id="cred-cct-val">C.C.T. 21PCT0073R</div>
                                                    <div class="bti-official-logos">
                                                        <div class="bti-logos-row">
                                                            <img src="/img/credencial/logo_puebla.png" class="bti-logo-puebla" alt="Puebla Gobierno">
                                                            <img src="/img/credencial/logo_sep.png" class="bti-logo-sep" alt="Secretaría de Educación">
                                                        </div>
                                                        <div class="bti-sep-text">EDUCACIÓN PÚBLICA</div>
                                                    </div>
                                                </div>
                                                <div class="bti-front-body">
                                                    <img src="/img/credencial/logo_bti.png" class="bti-corner-logo" alt="Logo BTI">
                                                    <div class="bti-field-group">
                                                        <span class="bti-field-label">NOMBRE</span>
                                                        <div class="bti-capsule bti-capsule-full" id="cred-nombre-val">DANIEL IGNACIO FERNÁNDEZ PARRA</div>
                                                    </div>
                                                    <div class="bti-field-group">
                                                        <span class="bti-field-label">MODALIDAD</span>
                                                        <div class="bti-capsule bti-capsule-sm" id="cred-modalidad-val">ESCOLARIZADO</div>
                                                    </div>
                                                    <div class="bti-field-group">
                                                        <span class="bti-field-label">MATRICULA</span>
                                                        <div class="bti-capsule bti-capsule-sm" id="cred-matricula-val">419012603</div>
                                                    </div>
                                                    <div class="bti-field-group">
                                                        <span class="bti-field-label">VIGENCIA</span>
                                                        <div class="bti-capsule bti-capsule-full" id="cred-vigencia-val">JULIO 2026 - SEPTIEMBRE 2026</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- REVERSO -->
                                            <div class="bti-card bti-card-back bti-card-preview-right position-relative" id="cred-card-back-preview">
                                                <div class="bti-back-stripes"></div>
                                                <img src="/img/credencial/logo_bti.png" class="bti-back-watermark" alt="Watermark BTI">
                                                <div class="bti-back-content">
                                                    <div>
                                                        <div class="bti-important-title">IMPORTANTE:</div>
                                                        <div class="bti-important-text" id="cred-importante-institucion-texto">
                                                            Esta credencial es personal y debe portarse en lugar visible mientras usted permanezca dentro de la institución.<br>
                                                            Debe mostrarse al personal de seguridad, a la autoridad del BTI para fines de identificación. no es transferible y deberá devolverse al causar baja el usuario.
                                                        </div>
                                                        <div class="bti-accident-title">EN CASO DE ACCIDENTE LLAMAR A:</div>
                                                        <div class="bti-accident-capsule">
                                                            <span class="bti-accident-name" id="cred-accidente-nombre-val">Ignacio Fernández Neville</span>
                                                            <span class="bti-accident-phone" id="cred-accidente-tel-val">231 108 9100</span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="bti-signature-section">
                                                            <div class="bti-signature-line"></div>
                                                            <div class="bti-director-title">DIRECTOR</div>
                                                            <div class="bti-director-name" id="cred-director-val">ING. FAUSTO MAURO LEYVA FLORES</div>
                                                        </div>
                                                        <div class="bti-address-footer">
                                                            AV. JUÁREZ 901 ESQ. LEÓN GUZMÁN<br>
                                                            COL. CENTRO TEZIUTLÁN, PUEBLA.
                                                        </div>
                                                        <div class="bti-phone-footer">
                                                            TEL: 01 231 31 2 39 79
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Ajustes + impresión --}}
                            <div class="tw:flex tw:flex-col tw:gap-5">
                                <div>
                                    <p class="{{ $sectionTitle }}"><i data-lucide="user-round" class="tw:size-3.5"></i> Datos del alumno</p>
                                    <div class="tw:grid tw:grid-cols-1 tw:gap-3 tw:sm:grid-cols-3">
                                        <div class="tw:sm:col-span-3">
                                            <label for="inputCredNombre" class="{{ $labelSm }}">Nombre completo</label>
                                            <input type="text" id="inputCredNombre" class="{{ $inputSm }}" value="DANIEL IGNACIO FERNÁNDEZ PARRA" oninput="actualizarCamposCredencialDinamicos()" placeholder="Nombre completo del alumno">
                                        </div>
                                        <div>
                                            <label for="inputCredMatricula" class="{{ $labelSm }}">Matrícula</label>
                                            <input type="text" id="inputCredMatricula" class="{{ $inputSm }} tw:font-mono" value="419012603" oninput="actualizarCamposCredencialDinamicos()" placeholder="419012603">
                                        </div>
                                        <div>
                                            <label for="inputCredCCT" class="{{ $labelSm }}">C.C.T.</label>
                                            <input type="text" id="inputCredCCT" class="{{ $inputSm }} tw:font-mono" value="21PCT0073R" oninput="actualizarCamposCredencialDinamicos()" placeholder="C.C.T.">
                                        </div>
                                        <div>
                                            <label for="inputCredModalidad" class="{{ $labelSm }}">Modalidad</label>
                                            <input type="text" id="inputCredModalidad" class="{{ $inputSm }}" value="ESCOLARIZADO" oninput="actualizarCamposCredencialDinamicos()">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="inputCredVigencia" class="{{ $sectionTitle }}"><i data-lucide="calendar-range" class="tw:size-3.5"></i> Vigencia</label>
                                    <input type="text" id="inputCredVigencia" class="{{ $inputSm }}" value="JULIO 2026 - SEPTIEMBRE 2026" oninput="actualizarCamposCredencialDinamicos()">
                                    <div class="tw:mt-2 tw:flex tw:flex-wrap tw:gap-1.5">
                                        @foreach (['JULIO 2026 - SEPTIEMBRE 2026' => 'Jul – Sep 2026', 'AGOSTO 2026 - ENERO 2027' => 'Ago 2026 – Ene 2027', 'FEBRERO 2027 - JULIO 2027' => 'Feb – Jul 2027'] as $valor => $texto)
                                        <button type="button" onclick="setCredVigencia('{{ $valor }}')"
                                            class="tw:cursor-pointer tw:border tw:border-slate-200 tw:bg-white tw:px-2.5 tw:py-1 tw:text-[0.6875rem] tw:font-medium tw:text-slate-600 tw:transition-colors tw:hover:border-brand-200 tw:hover:bg-brand-50 tw:hover:text-brand-700">{{ $texto }}</button>
                                        @endforeach
                                    </div>
                                </div>

                                <div>
                                    <p class="{{ $sectionTitle }}"><i data-lucide="phone" class="tw:size-3.5"></i> Contacto de emergencia</p>
                                    <div class="tw:grid tw:grid-cols-1 tw:gap-3 tw:sm:grid-cols-[minmax(0,3fr)_minmax(0,2fr)]">
                                        <div>
                                            <label for="inputCredAccidenteNombre" class="{{ $labelSm }}">Nombre</label>
                                            <input type="text" id="inputCredAccidenteNombre" class="{{ $inputSm }}" value="Ignacio Fernández Neville" oninput="actualizarCamposCredencialDinamicos()">
                                        </div>
                                        <div>
                                            <label for="inputCredAccidenteTel" class="{{ $labelSm }}">Teléfono</label>
                                            <input type="text" id="inputCredAccidenteTel" class="{{ $inputSm }} tw:font-mono" value="231 108 9100" oninput="actualizarCamposCredencialDinamicos()">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="inputCredDirector" class="{{ $sectionTitle }}"><i data-lucide="pen-line" class="tw:size-3.5"></i> Director del plantel</label>
                                    <input type="text" id="inputCredDirector" class="{{ $inputSm }}" value="ING. FAUSTO MAURO LEYVA FLORES" oninput="actualizarCamposCredencialDinamicos()">
                                </div>

                                <div class=" tw:border tw:border-slate-200 tw:bg-slate-50/60 tw:p-4">
                                    <p class="tw:m-0 tw:mb-3 tw:text-xs tw:text-slate-500">Impresión en alta definición para tarjeta PVC o papel opalina con marcas de corte.</p>
                                    <button type="button" class="{{ $btnPrimary }} tw:w-full" onclick="imprimirCredencialOficialBTI('ambas')">
                                        <i data-lucide="printer" class="tw:size-4"></i> Imprimir ambas caras
                                    </button>
                                    <div class="tw:mt-2 tw:grid tw:grid-cols-2 tw:gap-2">
                                        <button type="button" class="{{ $btnSecondary }} tw:h-9 tw:px-3 tw:text-[0.8125rem]" onclick="imprimirCredencialOficialBTI('frente')">Solo anverso</button>
                                        <button type="button" class="{{ $btnSecondary }} tw:h-9 tw:px-3 tw:text-[0.8125rem]" onclick="imprimirCredencialOficialBTI('reverso')">Solo reverso</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ EXAMEN EXTRAORDINARIO ============ --}}
                <div class="tab-pane fade" id="pane-extraordinario" role="tabpanel">
                    <x-imprimir.pane-header icon="file-pen" title="Examen extraordinario">
                        Acta de examen y recibo de derecho a examen extraordinario.
                    </x-imprimir.pane-header>

                    <div class="tw:grid tw:grid-cols-1 tw:gap-4 tw:md:grid-cols-6">
                        <div class="tw:relative tw:md:col-span-3">
                            <label for="extraordinarioAlumnoSearch" class="{{ $label }}">Alumno</label>
                            <div class="tw:relative">
                                <i data-lucide="graduation-cap" class="{{ $fieldIcon }}"></i>
                                <input type="text" id="extraordinarioAlumnoSearch" class="{{ $inputIcon }}" placeholder="Matrícula o nombre del alumno…" autocomplete="off" onkeyup="simularBusquedaAlumnoExtraordinario(this.value)">
                            </div>
                            <div id="extraordinario-alumno-sugerencia" class="{{ $sugerencias }}" style="display: none;"></div>
                        </div>

                        <div class="tw:relative tw:md:col-span-3">
                            <label for="extraordinarioGrupoInput" class="{{ $label }}">Grupo</label>
                            <div class="tw:relative">
                                <i data-lucide="users" class="{{ $fieldIcon }}"></i>
                                <input type="text" id="extraordinarioGrupoInput" class="{{ $inputIcon }}" placeholder="Busca un grupo (ej. 101, BTI…)" autocomplete="off" oninput="buscarGruposExtraordinario(this.value)" onfocus="buscarGruposExtraordinario(this.value)">
                            </div>
                            <div id="extraordinario-grupo-sugerencia" class="{{ $sugerencias }}" style="display: none;"></div>
                        </div>

                        <div class="tw:md:col-span-2">
                            <label for="extraordinarioSemestreSelect" class="{{ $label }}" id="lbl-extraordinario-semestre-label">Semestre</label>
                            {{-- Opciones según plantel (BTI: semestres, BGNE: trimestres) — ver configurarPeriodoExtraordinario() --}}
                            <select id="extraordinarioSemestreSelect" class="{{ $input }}" onchange="onExtraordinarioSemestreChange()">
                                <option value="">Selecciona…</option>
                            </select>
                        </div>

                        <div class="tw:relative tw:md:col-span-2">
                            <label for="extraordinarioMateriaInput" class="{{ $label }}">Materia</label>
                            <div class="tw:relative">
                                <i data-lucide="book-open" class="{{ $fieldIcon }}"></i>
                                <input type="text" id="extraordinarioMateriaInput" class="{{ $inputIcon }}" placeholder="Busca la materia…" autocomplete="off" oninput="buscarMateriasExtraordinario(this.value)" onfocus="buscarMateriasExtraordinario(this.value)">
                            </div>
                            <div id="extraordinario-materia-sugerencia" class="{{ $sugerencias }}" style="display: none;"></div>
                        </div>

                        <div class="tw:relative tw:md:col-span-2">
                            <label for="extraordinarioDocenteInput" class="{{ $label }}">Docente</label>
                            <div class="tw:relative">
                                <i data-lucide="presentation" class="{{ $fieldIcon }}"></i>
                                <input type="text" id="extraordinarioDocenteInput" class="{{ $inputIcon }}" placeholder="Busca al docente…" autocomplete="off" oninput="buscarDocentesExtraordinario(this.value)" onfocus="buscarDocentesExtraordinario(this.value)">
                            </div>
                            <div id="extraordinario-docente-sugerencia" class="{{ $sugerencias }}" style="display: none;"></div>
                        </div>
                    </div>

                    <div id="extraordinario-info-alumno" class="tw:mt-6" style="display: none;">
                        <div class="{{ $subcard }} tw:bg-slate-50/60 tw:p-5">
                            <p class="tw:m-0 tw:text-[0.9375rem] tw:font-semibold tw:text-slate-900" id="lbl-extraordinario-nombre"></p>
                            <dl class="tw:m-0 tw:mt-3 tw:grid tw:grid-cols-1 tw:gap-x-6 tw:gap-y-2 tw:text-[0.8125rem] tw:sm:grid-cols-2">
                                <div class="tw:flex tw:gap-2"><dt class="tw:w-16 tw:shrink-0 tw:text-slate-500">Grupo</dt><dd class="tw:m-0 tw:font-semibold tw:text-slate-800" id="lbl-extraordinario-grupo"></dd></div>
                                <div class="tw:flex tw:gap-2"><dt class="tw:w-16 tw:shrink-0 tw:text-slate-500">Periodo</dt><dd class="tw:m-0 tw:font-semibold tw:text-slate-800" id="lbl-extraordinario-semestre-val"></dd></div>
                                <div class="tw:flex tw:gap-2"><dt class="tw:w-16 tw:shrink-0 tw:text-slate-500">Materia</dt><dd class="tw:m-0 tw:font-semibold tw:text-brand-700" id="lbl-extraordinario-materia"></dd></div>
                                <div class="tw:flex tw:gap-2"><dt class="tw:w-16 tw:shrink-0 tw:text-slate-500">Docente</dt><dd class="tw:m-0 tw:font-semibold tw:text-slate-800" id="lbl-extraordinario-docente"></dd></div>
                            </dl>
                            <div class="tw:mt-4 tw:flex tw:justify-end tw:border-t tw:border-slate-200 tw:pt-4">
                                <button type="button" class="{{ $btnPrimary }}" onclick="abrirPrevisualizacionExtraordinario()">
                                    <i data-lucide="eye" class="tw:size-4"></i> Previsualizar e imprimir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ ACTAS ORDINARIO (BTI) ============ --}}
                <div class="tab-pane fade" id="pane-actas_ordinario" role="tabpanel">
                    <x-imprimir.pane-header icon="clipboard-list" title="Actas ordinario">
                        Genera el acta de calificaciones del grupo con la lista de sus alumnos. Las fechas son opcionales: si no las capturas, quedan en blanco para llenarlas a mano.
                    </x-imprimir.pane-header>

                    <div class="tw:grid tw:grid-cols-1 tw:gap-4 tw:md:grid-cols-3">
                        @foreach ([
                            ['id' => 'actaGrupo',   'label' => 'Grupo',      'icon' => 'users-round',  'ph' => 'Clave del grupo o nombre del alumno…'],
                            ['id' => 'actaDocente', 'label' => 'Docente',    'icon' => 'presentation', 'ph' => 'Busca al docente…'],
                            ['id' => 'actaMateria', 'label' => 'Asignatura', 'icon' => 'book-open',    'ph' => 'Busca o escribe la asignatura…'],
                        ] as $c)
                            <div class="tw:relative">
                                <label for="{{ $c['id'] }}Input" class="{{ $label }}">{{ $c['label'] }}</label>
                                <div class="tw:relative">
                                    <i data-lucide="{{ $c['icon'] }}" class="{{ $fieldIcon }}"></i>
                                    <input type="text" id="{{ $c['id'] }}Input" class="{{ $inputIcon }} tw:pr-10 {{ !empty($c['mono']) ? 'tw:font-mono' : '' }}" placeholder="{{ $c['ph'] }}" autocomplete="off"
                                        role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="{{ $c['id'] }}Lista">
                                    <button type="button" id="{{ $c['id'] }}Limpiar" class="{{ $iconBtn }}" style="display: none;" aria-label="Limpiar {{ mb_strtolower($c['label']) }}" title="Limpiar">
                                        <i data-lucide="x" class="tw:mx-auto tw:size-4"></i>
                                    </button>
                                </div>
                                <div id="{{ $c['id'] }}Lista" role="listbox" class="{{ $sugerencias }}" style="display: none;"></div>
                            </div>
                        @endforeach
                    </div>

                    <div class="tw:mt-4 tw:grid tw:grid-cols-1 tw:gap-4 tw:md:grid-cols-3">
                        <div>
                            <label for="actaExamenInput" class="{{ $label }}">Examen <span class="tw:font-normal tw:text-slate-400">(opcional)</span></label>
                            <input type="text" id="actaExamenInput" class="{{ $input }}" placeholder="Ej. Ordinario" autocomplete="off" maxlength="60">
                        </div>
                        <div>
                            <label for="actaFechaExamenInput" class="{{ $label }}">Fecha de examen <span class="tw:font-normal tw:text-slate-400">(opcional)</span></label>
                            <input type="date" id="actaFechaExamenInput" class="{{ $input }}">
                        </div>
                        <div>
                            <label for="actaFechaEntregaInput" class="{{ $label }}">Fecha de entrega <span class="tw:font-normal tw:text-slate-400">(opcional)</span></label>
                            <input type="date" id="actaFechaEntregaInput" class="{{ $input }}">
                        </div>
                    </div>

                    <div class="tw:mt-6 tw:flex tw:flex-wrap tw:items-center tw:justify-between tw:gap-4 tw:border-t tw:border-slate-100 tw:pt-5">
                        <p id="actaResumen" class="tw:m-0 tw:flex tw:items-center tw:gap-2 tw:text-[0.8125rem] tw:text-slate-500">
                            <i data-lucide="info" class="tw:size-4 tw:shrink-0"></i> Elige un grupo para ver cuántos alumnos aparecerán en el acta.
                        </p>
                        <button type="button" id="btnGenerarActa" class="{{ $btnPrimary }}" onclick="generarActaOrdinarioPdf()">
                            <i data-lucide="printer" class="tw:size-4"></i> Generar acta (PDF)
                        </button>
                    </div>
                </div>

                {{-- ============ ACTAS ORDINARIO (BGNE) ============ --}}
                <div class="tab-pane fade" id="pane-actas_bgne" role="tabpanel">
                    <x-imprimir.pane-header icon="clipboard-list" title="Actas ordinario">
                        Control de calificaciones finales por trimestre (módulo). Las fechas de inicio y fin se calculan con el inicio del grupo y puedes corregirlas; la fecha de entrega es opcional.
                    </x-imprimir.pane-header>

                    <div class="tw:grid tw:grid-cols-1 tw:gap-4 tw:md:grid-cols-[minmax(0,3fr)_minmax(0,2fr)]">
                        <div class="tw:relative">
                            <label for="actaBgneGrupoInput" class="{{ $label }}">Grupo</label>
                            <div class="tw:relative">
                                <i data-lucide="users-round" class="{{ $fieldIcon }}"></i>
                                <input type="text" id="actaBgneGrupoInput" class="{{ $inputIcon }} tw:pr-10" placeholder="Clave del grupo o nombre del alumno…" autocomplete="off"
                                    role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="actaBgneGrupoLista">
                                <button type="button" id="actaBgneGrupoLimpiar" class="{{ $iconBtn }}" style="display: none;" aria-label="Limpiar grupo" title="Limpiar">
                                    <i data-lucide="x" class="tw:mx-auto tw:size-4"></i>
                                </button>
                            </div>
                            <div id="actaBgneGrupoLista" role="listbox" class="{{ $sugerencias }}" style="display: none;"></div>
                        </div>
                        <div>
                            <label for="actaBgneTrimestreSelect" class="{{ $label }}">Trimestre (módulo)</label>
                            <select id="actaBgneTrimestreSelect" class="{{ $input }}" onchange="onActaBgneTrimestreChange()">
                                <option value="">Selecciona…</option>
                                @foreach (['1er', '2do', '3er', '4to', '5to', '6to'] as $i => $ord)
                                    <option value="{{ $i + 1 }}">{{ $ord }} trimestre</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="tw:mt-4 tw:grid tw:grid-cols-1 tw:gap-4 tw:md:grid-cols-2">
                        @foreach ([
                            ['id' => 'actaBgneMateria', 'label' => 'Asignatura', 'icon' => 'book-open',    'ph' => 'Busca o escribe la asignatura…'],
                            ['id' => 'actaBgneDocente', 'label' => 'Docente',    'icon' => 'presentation', 'ph' => 'Busca o escribe al docente…'],
                        ] as $c)
                            <div class="tw:relative">
                                <label for="{{ $c['id'] }}Input" class="{{ $label }}">{{ $c['label'] }}</label>
                                <div class="tw:relative">
                                    <i data-lucide="{{ $c['icon'] }}" class="{{ $fieldIcon }}"></i>
                                    <input type="text" id="{{ $c['id'] }}Input" class="{{ $inputIcon }} tw:pr-10" placeholder="{{ $c['ph'] }}" autocomplete="off"
                                        role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="{{ $c['id'] }}Lista">
                                    <button type="button" id="{{ $c['id'] }}Limpiar" class="{{ $iconBtn }}" style="display: none;" aria-label="Limpiar {{ mb_strtolower($c['label']) }}" title="Limpiar">
                                        <i data-lucide="x" class="tw:mx-auto tw:size-4"></i>
                                    </button>
                                </div>
                                <div id="{{ $c['id'] }}Lista" role="listbox" class="{{ $sugerencias }}" style="display: none;"></div>
                            </div>
                        @endforeach
                    </div>

                    <div class="tw:mt-4 tw:grid tw:grid-cols-1 tw:gap-4 tw:md:grid-cols-3">
                        <div>
                            <label for="actaBgneFechaInicioInput" class="{{ $label }}">Fecha de inicio</label>
                            <input type="date" id="actaBgneFechaInicioInput" class="{{ $input }}">
                        </div>
                        <div>
                            <label for="actaBgneFechaFinInput" class="{{ $label }}">Fecha de fin</label>
                            <input type="date" id="actaBgneFechaFinInput" class="{{ $input }}">
                        </div>
                        <div>
                            <label for="actaBgneFechaEntregaInput" class="{{ $label }}">Fecha de entrega <span class="tw:font-normal tw:text-slate-400">(opcional)</span></label>
                            <input type="date" id="actaBgneFechaEntregaInput" class="{{ $input }}">
                        </div>
                    </div>

                    <div class="tw:mt-6 tw:flex tw:flex-wrap tw:items-center tw:justify-between tw:gap-4 tw:border-t tw:border-slate-100 tw:pt-5">
                        <p id="actaBgneResumen" class="tw:m-0 tw:flex tw:items-center tw:gap-2 tw:text-[0.8125rem] tw:text-slate-500">
                            <i data-lucide="info" class="tw:size-4 tw:shrink-0"></i> Elige un grupo para ver cuántos alumnos aparecerán en el acta.
                        </p>
                        <button type="button" class="{{ $btnPrimary }}" onclick="generarActaBgnePdf()">
                            <i data-lucide="printer" class="tw:size-4"></i> Generar acta (PDF)
                        </button>
                    </div>
                </div>

                {{-- ============ LISTA DE ASISTENCIAS ============ --}}
                <div class="tab-pane fade" id="pane-asistencias" role="tabpanel">
                    <x-imprimir.pane-header icon="calendar-check" title="Lista de asistencias">
                        Genera la lista de asistencia del grupo para el periodo correspondiente.
                    </x-imprimir.pane-header>

                    <div class="tw:grid tw:grid-cols-1 tw:gap-4 tw:md:grid-cols-2 tw:xl:grid-cols-[minmax(0,3fr)_minmax(0,3fr)_minmax(0,3fr)_minmax(0,2fr)]">
                        <div class="tw:relative">
                            <label for="asistenciaGrupoInput" class="{{ $label }}">Grupo</label>
                            <div class="tw:relative">
                                <i data-lucide="users-round" class="{{ $fieldIcon }}"></i>
                                <input type="text" id="asistenciaGrupoInput" class="{{ $inputIcon }} tw:pr-10 tw:font-mono" placeholder="Escribe la clave o elige…" autocomplete="off"
                                    role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="asistencia-grupo-sugerencia"
                                    oninput="onGrupoAsistenciaInput(this.value)" onfocus="buscarGruposAsistencia(this.value)" onkeydown="onGrupoAsistenciaKeydown(event)" onblur="onGrupoAsistenciaBlur()">
                                <button type="button" id="btn-limpiar-asistencia-grupo" class="{{ $iconBtn }}" onclick="limpiarGrupoAsistencia()" style="display: none;" aria-label="Limpiar grupo" title="Limpiar grupo">
                                    <i data-lucide="x" class="tw:mx-auto tw:size-4"></i>
                                </button>
                            </div>
                            <div id="asistencia-grupo-sugerencia" role="listbox" class="{{ $sugerencias }}" style="display: none;"></div>
                            {{-- Fuente de verdad del grupo elegido (el resto del módulo lee su .value) --}}
                            <select id="asistenciaGrupoSelect" style="display: none;" onchange="onAsistenciaGrupoChange()" aria-hidden="true" tabindex="-1">
                                <option value="">Selecciona…</option>
                                <!-- Se puebla según el CCT seleccionado -->
                            </select>
                        </div>

                        <div class="tw:relative">
                            <label for="asistenciaDocenteInput" class="{{ $label }}">Docente <span class="tw:font-normal tw:text-slate-400">(opcional)</span></label>
                            <div class="tw:relative">
                                <i data-lucide="presentation" class="{{ $fieldIcon }}"></i>
                                <input type="text" id="asistenciaDocenteInput" class="{{ $inputIcon }} tw:pr-10" placeholder="Busca al docente…" autocomplete="off" oninput="buscarDocentesAsistencia(this.value)" onfocus="buscarDocentesAsistencia(this.value)">
                                <button type="button" id="btn-limpiar-asistencia-docente" class="{{ $iconBtn }}" onclick="limpiarDocenteAsistencia()" style="display: none;" aria-label="Limpiar docente" title="Limpiar docente">
                                    <i data-lucide="x" class="tw:mx-auto tw:size-4"></i>
                                </button>
                            </div>
                            <div id="asistencia-docente-sugerencia" class="{{ $sugerencias }}" style="display: none;"></div>
                            <select id="asistenciaDocenteSelect" style="display: none;" onchange="onAsistenciaDocenteChange()">
                                <option value="">Seleccione Docente</option>
                                @if(isset($docentes))
                                    @foreach($docentes as $d)
                                        @php
                                            $dId = is_array($d) ? ($d['idDocente'] ?? $d['id'] ?? '') : ($d->idDocente ?? $d->id ?? '');
                                            $dNom = is_array($d) ? ($d['nombreDocente'] ?? $d['nombre'] ?? '') : ($d->nombreDocente ?? $d->nombre ?? '');
                                            $dPat = is_array($d) ? ($d['apPaternoDocente'] ?? $d['apPaterno'] ?? '') : ($d->apPaternoDocente ?? $d->apPaterno ?? '');
                                            $dMat = is_array($d) ? ($d['apMaternoDocente'] ?? $d['apMaterno'] ?? '') : ($d->apMaternoDocente ?? $d->apMaterno ?? '');
                                        @endphp
                                        <option value="{{ $dId }}">{{ trim("$dNom $dPat $dMat") }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="tw:relative">
                            <label for="asistenciaMateriaInput" class="{{ $label }}">Asignatura</label>
                            <div class="tw:relative">
                                <i data-lucide="book-open" class="{{ $fieldIcon }}"></i>
                                <input type="text" id="asistenciaMateriaInput" class="{{ $inputIcon }} tw:pr-10" placeholder="Busca la materia…" autocomplete="off" oninput="buscarMateriasAsistencia(this.value)" onfocus="buscarMateriasAsistencia(this.value)">
                                <button type="button" id="btn-limpiar-asistencia-materia" class="{{ $iconBtn }}" onclick="limpiarMateriaAsistencia()" style="display: none;" aria-label="Limpiar materia" title="Limpiar materia">
                                    <i data-lucide="x" class="tw:mx-auto tw:size-4"></i>
                                </button>
                            </div>
                            <div id="asistencia-materia-sugerencia" class="{{ $sugerencias }}" style="display: none;"></div>
                        </div>

                        <div>
                            <label for="asistenciaCicloSelect" class="{{ $label }}" id="lbl-asistencia-ciclo-label">Trimestre</label>
                            <select id="asistenciaCicloSelect" class="{{ $input }}" onchange="onAsistenciaCicloChange()">
                                <!-- Cargado dinámicamente por JS (Semestres para BTI, Trimestres para BGNE) -->
                            </select>
                        </div>
                    </div>

                    {{-- Periodo (solo BTI escolarizado) --}}
                    <div id="asistencia-periodo-escolarizado-row" class="tw:mt-5" style="display: none;">
                        <div class="{{ $subcard }} tw:bg-slate-50/60 tw:p-4">
                            <div class="tw:grid tw:grid-cols-1 tw:items-end tw:gap-4 tw:lg:grid-cols-[minmax(0,5fr)_minmax(0,7fr)]">
                                <div>
                                    <label for="asistenciaPeriodoSelect" class="{{ $label }}">Periodo de asistencias</label>
                                    <select id="asistenciaPeriodoSelect" class="{{ $input }}" onchange="onAsistenciaPeriodoSelectChange()">
                                        <option value="1">1° Periodo: 31 Ago – 30 Oct (45 días hábiles)</option>
                                        <option value="2">2° Periodo: 02 Nov – 18 Dic (35 días hábiles)</option>
                                        <option value="custom">Rango personalizado…</option>
                                    </select>
                                </div>
                                <div id="asistencia-custom-fechas-container" style="display: none;">
                                    <div class="tw:grid tw:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto] tw:items-end tw:gap-3">
                                        <div>
                                            <label for="asistenciaFechaInicioInput" class="{{ $labelSm }}">Desde</label>
                                            <input type="date" id="asistenciaFechaInicioInput" class="{{ $input }}" value="2026-08-31" onchange="onAsistenciaCustomFechasChange()">
                                        </div>
                                        <div>
                                            <label for="asistenciaFechaFinInput" class="{{ $labelSm }}">Hasta</label>
                                            <input type="date" id="asistenciaFechaFinInput" class="{{ $input }}" value="2026-10-30" onchange="onAsistenciaCustomFechasChange()">
                                        </div>
                                        <span id="asistencia-dias-badge" title="Días hábiles de lunes a viernes"
                                            class="{{ $chip }} tw:h-10 tw:bg-success-100 tw:px-3 tw:text-success-700">45 días</span>
                                    </div>
                                </div>
                            </div>
                            {{-- El JS alterna su display: no usar utilidades de display aquí --}}
                            <div id="asistencia-exceso-alerta" class="tw:mt-3 tw:border tw:border-warning-600/30 tw:bg-warning-50 tw:px-3 tw:py-2.5 tw:text-[0.8125rem] tw:text-slate-700" style="display: none;">
                                <i data-lucide="triangle-alert" class="tw:mr-1.5 tw:inline tw:size-4 tw:align-[-3px] tw:text-warning-600"></i>
                                <strong id="asistencia-exceso-titulo" class="tw:font-semibold">Rango excede el límite de 1 hoja.</strong>
                                <span id="asistencia-exceso-mensaje">Se abarcarán un máximo de 45 días hábiles para garantizar que quepa en 1 sola página.</span>
                            </div>
                        </div>
                    </div>

                    <div id="asistencia-preview-card" class="tw:mt-6" style="display: none;">
                        <div class="{{ $subcard }} tw:flex tw:flex-wrap tw:items-center tw:justify-between tw:gap-5 tw:bg-slate-50/60 tw:p-5">
                            <div class="tw:min-w-0">
                                <p class="tw:m-0 tw:text-[0.9375rem] tw:font-semibold tw:text-slate-900" id="lbl-asistencia-preview-docente"></p>
                                <dl class="tw:m-0 tw:mt-3 tw:grid tw:grid-cols-1 tw:gap-x-6 tw:gap-y-1.5 tw:text-[0.8125rem] tw:sm:grid-cols-2">
                                    <div class="tw:flex tw:gap-2"><dt class="tw:w-20 tw:shrink-0 tw:text-slate-500">Asignatura</dt><dd class="tw:m-0 tw:font-semibold tw:text-brand-700" id="lbl-asistencia-preview-materia"></dd></div>
                                    <div class="tw:flex tw:gap-2"><dt class="tw:w-20 tw:shrink-0 tw:text-slate-500">Grupo</dt><dd class="tw:m-0 tw:font-semibold tw:text-slate-800" id="lbl-asistencia-preview-grupo"></dd></div>
                                    <div id="lbl-asistencia-preview-ciclo-texto" class="tw:text-slate-500">Periodo: <strong class="tw:font-semibold tw:text-slate-800" id="lbl-asistencia-preview-term"></strong></div>
                                    <div id="lbl-asistencia-preview-fechas-line" class="tw:text-slate-500">Fechas: <strong class="tw:font-semibold tw:text-slate-800" id="lbl-asistencia-preview-fechas-val"></strong></div>
                                </dl>
                                <span class="{{ $chip }} tw:mt-3 tw:bg-success-100 tw:text-success-700" id="lbl-asistencia-preview-alumnos">0 alumnos inscritos</span>
                            </div>
                            <button type="button" class="{{ $btnPrimary }}" onclick="generarListaAsistenciaOficialPdf()">
                                <i data-lucide="printer" class="tw:size-4"></i> Generar lista (PDF)
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
</div>


    <!-- MODAL DE PREVISUALIZACIÓN Y IMPRESIÓN DE EXAMEN EXTRAORDINARIO -->
    <div class="modal fade" id="modalExtraordinarioPreview" tabindex="-1" aria-labelledby="modalExtraordinarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalExtraordinarioLabel">
                        <i data-lucide="file-pen" class="tw:size-5"></i> Vista previa del acta y recibo de examen extraordinario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body tw:flex tw:justify-center tw:bg-slate-100 tw:p-6" style="max-height: calc(85vh - 120px); overflow-y: auto;">
                    <div id="extraordinarioHojaImpresion" style="width: 100%; max-width: 760px; background: #ffffff; padding: 28px 32px; border-radius: 6px; box-shadow: 0 4px 15px rgba(0,0,0,0.06); border: 1px solid #cbd5e1; font-family: Arial, Helvetica, sans-serif; color: #000000;">
                        <!-- Se puebla dinámicamente con renderFormatoExtraordinario() -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="{{ $btnSecondary }}" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="{{ $btnPrimary }}" onclick="ejecutarImpresionExtraordinario()">
                        <i data-lucide="printer" class="tw:size-4"></i> Imprimir formato
                    </button>
                </div>
            </div>
        </div>
    </div>

<!-- DATOS COMPARTIDOS (JS) -->
<script>
    window.gruposDb = @json($grupos ?? []);
    window.docentesDb = @json($docentes ?? []);
    window.horariosDb = @json($horarios ?? []);
    window.materiasDb = @json($materias ?? []);

    const mockAlumnos = [
        { matricula: '20260001', nombre: 'Pérez López Juan', plan: 'BTI - Tecnologías de la Información' },
        { matricula: '20260002', nombre: 'Gómez García María', plan: 'BGNE - Tronco Común' },
        { matricula: '20260003', nombre: 'Hernández Ruiz Carlos', plan: 'BTI - Electrónica y Sistemas' },
        { matricula: '20260004', nombre: 'Martínez Díaz Sofía', plan: 'BGNE - Administrativo' },
        { matricula: '20260005', nombre: 'Rodríguez Solís Ana', plan: 'INF - Informática y Computación' }
    ];
</script>

<!-- ESTILOS SAAS MODERNO PARA MÓDULO IMPRIMIR -->
<style>
    /* Ítems de sugerencias generados por JS (buscadores de alumno, grupo, materia, docente) */
    #submodulos-tab-content .list-group > .list-group-item {
        border: 0;
        border-radius: 8px;
        margin: 0;
        padding: 9px 12px;
        font-size: 0.8125rem;
        color: #0F172A;
        background: transparent;
        transition: background-color .15s ease;
    }
    #submodulos-tab-content .list-group > .list-group-item + .list-group-item { border-top: 0; }
    #submodulos-tab-content .list-group > .list-group-item:hover,
    #submodulos-tab-content .list-group > .list-group-item:focus-visible {
        background: #EFF6F7;
        color: #0F172A;
        outline: none;
    }
    #submodulos-tab-content .list-group > .list-group-item .badge {
        background: #F1F5F9 !important;
        color: #475569 !important;
        font-weight: 600;
        border-radius: 999px;
    }

    /* DISEÑO OFICIAL DE CREDENCIAL ESCOLARIZADO BTI */
    .bti-card-spread {
        display: flex;
        flex-direction: row;
        width: 500px;
        height: 396px;
        background: #ffffff;
        border-radius: 14px;
        border: 1.5px solid #64748b;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.08);
        user-select: none;
        max-width: 100%;
        margin: 0 auto;
    }
    .bti-card-spread .bti-card {
        width: 250px;
        height: 396px;
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        flex-shrink: 0;
    }
    .bti-card-spread .bti-card-preview-left {
        border-right: 1.5px solid #64748b !important;
    }
    @media (max-width: 576px) {
        .bti-card-spread {
            transform: scale(0.65);
            transform-origin: top center;
            margin-bottom: -130px;
        }
    }
    .bti-card {
        width: 250px;
        height: 396px;
        background: #ffffff;
        border-radius: 12px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #cbd5e1;
        display: flex;
        flex-direction: column;
        user-select: none;
    }

    /* Frente (Anverso) */
    .bti-card-front {
        background: linear-gradient(135deg, #f0f7fd 0%, #e6f2fb 50%, #ffffff 100%);
    }

    .bti-front-stripes {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 14px;
        background: #15285f;
        display: flex;
        border-right: 1.5px solid #60a5fa;
        box-shadow: 1px 0 3px rgba(0,0,0,0.15);
        z-index: 5;
    }
    .bti-front-stripes::after {
        content: '';
        position: absolute;
        right: -4px;
        top: 0;
        bottom: 0;
        width: 1.5px;
        background: #15285f;
    }

    .bti-map-bg {
        position: absolute;
        left: 12px;
        top: 35px;
        right: 0;
        bottom: 0;
        background-image: url('/img/credencial/mapa_america.svg');
        background-size: 260% auto;
        background-position: 70% 32%;
        background-repeat: no-repeat;
        opacity: 0.35;
        z-index: 1;
        pointer-events: none;
    }

    .bti-header-banner {
        background: #15285f;
        color: #ffffff;
        text-align: center;
        padding: 6px 8px 5px 18px;
        position: relative;
        z-index: 6;
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }
    .bti-header-title {
        font-size: 9.5px;
        font-weight: 900;
        letter-spacing: 0.3px;
        line-height: 1.15;
        text-transform: uppercase;
        font-family: 'Montserrat', Arial, sans-serif;
    }
    .bti-header-subtitle {
        font-size: 9.5px;
        font-weight: 900;
        letter-spacing: 0.7px;
        line-height: 1.15;
        text-transform: uppercase;
        font-family: 'Montserrat', Arial, sans-serif;
    }

    .bti-sub-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 4px 8px 3px 22px;
        position: relative;
        z-index: 6;
    }
    .bti-cct-text {
        font-size: 8.5px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: 0.2px;
        font-family: 'Montserrat', Arial, sans-serif;
    }
    .bti-official-logos {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }
    .bti-logos-row {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .bti-logo-puebla {
        height: 16px;
        width: auto;
        object-fit: contain;
    }
    .bti-logo-sep {
        height: 14px;
        width: auto;
        object-fit: contain;
    }
    .bti-sep-text {
        font-size: 5px;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        margin-top: 1px;
    }

    .bti-front-body {
        flex: 1;
        padding: 8px 8px 10px 24px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        position: relative;
        z-index: 6;
    }
    .bti-corner-logo {
        position: absolute;
        right: 4px;
        bottom: 20px;
        width: 82px;
        height: 82px;
        z-index: 2;
        pointer-events: none;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.12));
    }
    .bti-field-group {
        margin-bottom: 6px;
        position: relative;
        z-index: 6;
    }
    .bti-field-group:last-child {
        margin-bottom: 0;
    }
    .bti-field-label {
        font-size: 7.8px;
        font-weight: 800;
        color: #15285f;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        margin-bottom: 2px;
        display: block;
        font-family: 'Montserrat', Arial, sans-serif;
    }
    .bti-capsule {
        background: #ffffff;
        border: 1.5px solid #38bdf8;
        border-radius: 7px;
        padding: 3.5px 6px;
        font-size: 7.8px;
        font-weight: 800;
        color: #0f172a;
        box-shadow: 0 1.5px 3px rgba(56, 189, 248, 0.18);
        text-align: center;
        text-transform: uppercase;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
        font-family: 'Montserrat', Arial, sans-serif;
    }
    .bti-capsule-sm { width: 72%; }
    .bti-capsule-md { width: 82%; }
    .bti-capsule-full { width: 92%; }

    /* Reverso (Atrás) */
    .bti-card-back {
        background: #ffffff;
        padding: 10px 22px 10px 12px;
        justify-content: space-between;
    }
    .bti-back-stripes {
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        width: 14px;
        background: #15285f;
        border-left: 1.5px solid #60a5fa;
        box-shadow: -1px 0 3px rgba(0,0,0,0.15);
        z-index: 5;
    }
    .bti-back-stripes::before {
        content: '';
        position: absolute;
        left: -4px;
        top: 0;
        bottom: 0;
        width: 1.5px;
        background: #15285f;
    }
    .bti-back-watermark {
        position: absolute;
        left: 45%;
        top: 50%;
        transform: translate(-50%, -50%);
        width: 135px;
        height: 135px;
        opacity: 0.18;
        z-index: 1;
        pointer-events: none;
    }
    .bti-back-content {
        position: relative;
        z-index: 6;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        text-align: center;
    }
    .bti-important-title {
        font-size: 11px;
        font-weight: 800;
        color: #1e293b;
        letter-spacing: 0.6px;
        margin-bottom: 5px;
        font-family: 'Montserrat', Arial, sans-serif;
    }
    .bti-important-text {
        font-size: 6.2px;
        line-height: 1.32;
        color: #334155;
        font-weight: 600;
        margin-bottom: 10px;
        text-align: justify;
        padding: 0 4px;
        font-family: 'Montserrat', Arial, sans-serif;
    }
    .bti-accident-title {
        font-size: 6.8px;
        font-weight: 800;
        color: #1e293b;
        letter-spacing: 0.3px;
        margin-bottom: 3px;
        font-family: 'Montserrat', Arial, sans-serif;
    }
    .bti-accident-capsule {
        background: #ffffff;
        border: 1.5px solid #38bdf8;
        border-radius: 7px;
        padding: 4px 6px;
        margin: 0 auto 10px auto;
        width: 90%;
        box-shadow: 0 1.5px 4px rgba(56, 189, 248, 0.2);
    }
    .bti-accident-name {
        font-size: 7.5px;
        font-weight: 800;
        color: #0f172a;
        display: block;
        line-height: 1.2;
        font-family: 'Montserrat', Arial, sans-serif;
    }
    .bti-accident-phone {
        font-size: 8px;
        font-weight: 800;
        color: #0284c7;
        display: block;
        margin-top: 1px;
        font-family: 'Montserrat', Arial, sans-serif;
    }
    .bti-signature-section {
        margin-top: auto;
        margin-bottom: 8px;
    }
    .bti-signature-line {
        width: 120px;
        height: 1px;
        background: #64748b;
        margin: 0 auto 3px auto;
    }
    .bti-director-title {
        font-size: 7px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: 0.3px;
        font-family: 'Montserrat', Arial, sans-serif;
    }
    .bti-director-name {
        font-size: 7px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
        font-family: 'Montserrat', Arial, sans-serif;
    }
    .bti-address-footer {
        font-size: 5.8px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.25;
        letter-spacing: 0.2px;
        font-family: 'Montserrat', Arial, sans-serif;
    }
    .bti-phone-footer {
        font-size: 6.2px;
        font-weight: 800;
        color: #0f172a;
        margin-top: 1px;
        font-family: 'Montserrat', Arial, sans-serif;
    }

    /* Utilidades */
    .fs-8 { font-size: 0.8rem; }
    .fs-9 { font-size: 0.72rem; }
    .text-slate-800 { color: #1e293b; }
    .text-slate-700 { color: #334155; }
    .text-slate-600 { color: #475569; }
</style>

    <!-- MODAL DE KÁRDEX OFICIAL / CALIFICACIONES -->
    <div class="modal fade" id="modalKardexAlumno" tabindex="-1" aria-labelledby="modalKardexLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="background: #ffffff;">
                <div class="modal-header py-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bold mb-0" id="modalKardexLabel">
                        <i class="ti ti-school me-2" style="color: #26687B;"></i> Kárdex Oficial y Calificaciones
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-secondary btn-sm fw-semibold shadow-sm px-3" onclick="imprimirKardex()">
                            <i class="ti ti-printer me-1" style="color: #26687B;"></i> Imprimir Kárdex
                        </button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body p-4 bg-white" id="kardexPrintArea" style="max-height: calc(85vh - 130px); overflow-y: auto;">
                    
                    <!-- ENCABEZADO OFICIAL CON MEMBRETE INSTITUCIONAL -->
                    <div class="mb-3 pt-1">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <!-- Logo Institucional -->
                            <div style="flex-shrink: 0;">
                                <img id="kardexLogoImg" src="{{ asset('img/logo.png') }}" alt="Logo Institucional" style="height: 80px; width: auto; object-fit: contain;">
                            </div>

                            <!-- Banner Bicolor Oficial -->
                            <div class="flex-grow-1 border" style="border-color: #10599a !important; overflow: hidden; border-radius: 4px;">
                                <div class="py-1 px-3 text-center text-white fw-bold text-uppercase" style="background: #10599a; font-size: 1.15rem; letter-spacing: 1px;">
                                    BACHILLERATO INTERAMERICANO
                                </div>
                                <div class="py-1 px-2 text-center" style="background: #d4ebf9; color: #0f172a; font-size: 0.78rem; line-height: 1.35;">
                                    <div>Avenida Benito Juárez 901, Colonia Centro Teziutlán Puebla. Tel: 231-3123979</div>
                                    <div class="fw-bold" id="kardexCCTClave">CLAVE CT: 21PBH0353G</div>
                                </div>
                            </div>
                        </div>

                        <!-- Texto institucional y Alumno -->
                        <div class="text-center mt-2">
                            <div class="text-dark" style="font-size: 0.85rem;">
                                La Dirección de la escuela <strong id="kardexCCTNombre">BACHILLERATO GENERAL NO ESCOLARIZADO</strong>
                            </div>
                            <div class="text-secondary fst-italic" style="font-size: 0.80rem;">
                                Reporta las siguientes calificaciones obtenidas hasta el momento del alumno(a):
                            </div>
                            <div class="my-2 py-1 px-4 bg-light border rounded-pill d-inline-block shadow-sm">
                                <span class="fw-bold text-dark text-uppercase fs-5" id="kardexNombreAlumno" style="letter-spacing: 0.5px; text-decoration: underline;">
                                    —
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- CONTENEDOR DE PERIODOS (GRID 2 COLUMNAS X 3 FILAS) -->
                    <div class="row g-2" id="contenedorPeriodosKardex">
                        <div class="col-12 text-center py-5">
                            <div class="spinner-border text-primary"></div>
                            <div class="text-muted mt-2">Cargando kárdex de calificaciones...</div>
                        </div>
                    </div>

                    <!-- LEMA INFERIOR OFICIAL -->
                    <div class="text-center mt-2 p-1 text-white fw-semibold rounded-1" style="background: #4a90e2; font-size: 0.82rem; letter-spacing: 0.5px;">
                        ¡ Excelencia educativa a su servicio !
                    </div>

                </div>
                <div class="modal-footer bg-light border-top py-2 px-4 d-flex justify-content-between">
                    <span class="text-muted" style="font-size: 0.82rem;">
                        <i class="ti ti-info-circle me-1"></i> Puedes ajustar calificaciones directamente en cada casilla.
                    </span>
                    <div>
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-success px-4 fw-bold shadow-sm" onclick="guardarCalificacionesKardex()" id="btnGuardarKardex">
                            <i class="ti ti-device-floppy me-1"></i> Guardar Calificaciones
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- ESTILO COMPARTIDO DE DOCUMENTOS IMPRESOS (mismo lenguaje visual que la lista de asistencia PDF) -->
<script>
    window.RP = (function () {
        const css = `
            .rp, .rp * { box-sizing: border-box; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            .rp { font-family: Helvetica, Arial, sans-serif; color: #1f2a2c; font-size: 8pt; line-height: 1.3; text-align: left; }
            .rp table { border-collapse: collapse; }

            /* Encabezado */
            .rp-head { width: 100%; display: flex; align-items: center; gap: 4mm; }
            .rp-logo { width: 16mm; height: 16mm; object-fit: contain; flex-shrink: 0; }
            .rp-head-main { flex: 1; min-width: 0; }
            .rp-inst { font-size: 12.5pt; font-weight: bold; letter-spacing: .5px; text-transform: uppercase; color: #12393f; margin: 0; line-height: 1.15; }
            .rp-title { font-size: 9pt; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; color: #26687B; margin-top: 1.2mm; }
            .rp-sub { font-size: 6.8pt; color: #5b6b6e; margin-top: .8mm; }
            .rp-head-side { text-align: right; flex-shrink: 0; }
            .rp-tag { display: inline-block; background: #26687B; color: #fff; font-size: 6.8pt; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; padding: 1.2mm 3mm; border-radius: 2px; white-space: nowrap; }
            .rp-tag-sub { margin-top: 1.2mm; font-size: 6.6pt; color: #5b6b6e; text-transform: uppercase; letter-spacing: .5px; }
            .rp-folio { font-size: 6.2pt; font-weight: bold; letter-spacing: 1.2px; color: #5b6b6e; text-transform: uppercase; }
            .rp-folio-val { font-size: 10pt; font-weight: bold; color: #9a2a1f; letter-spacing: .4px; }
            .rp-rule { height: .9mm; background: #26687B; margin-top: 2.2mm; }
            .rp-rule-thin { height: .3mm; background: #c9a24b; margin-top: .5mm; margin-bottom: 3mm; }

            /* Tarjetas de datos */
            .rp-meta { display: flex; gap: 2.5mm; margin-bottom: 3mm; }
            .rp-card { flex: 1; min-width: 0; border: .6pt solid #b9c9cc; border-left: 1.4mm solid #26687B; background: #f4f8f8; padding: 1.3mm 2.5mm 1.5mm; }
            .rp-label { display: block; font-size: 5.8pt; font-weight: bold; letter-spacing: 1.1px; text-transform: uppercase; color: #5b6b6e; }
            .rp-value { display: block; margin-top: .6mm; font-size: 8.4pt; font-weight: bold; text-transform: uppercase; color: #12393f; }
            .rp-value.lg { font-size: 10pt; }

            /* Títulos de sección */
            .rp-section { display: flex; align-items: center; gap: 2mm; font-size: 7pt; font-weight: bold; letter-spacing: 1.4px; text-transform: uppercase; color: #12393f; margin: 0 0 1.4mm; }
            .rp-section::before { content: ""; width: 1.2mm; height: 3.2mm; background: #c9a24b; display: inline-block; }

            /* Tablas */
            .rp-table { width: 100%; border: 1pt solid #26687B; }
            .rp-table th, .rp-table td { border: .5pt solid #a9bcc0; padding: 1.2mm 1.8mm; vertical-align: middle; }
            .rp-table thead th { background: #26687B; color: #fff; font-size: 6.6pt; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; text-align: center; border-color: #26687B; line-height: 1.2; }
            .rp-table thead tr.sub th { background: #dceaec; color: #12393f; border-color: #a9bcc0; font-size: 6.3pt; }
            .rp-table thead th.l { text-align: left; }
            .rp-table tbody tr:nth-child(even) td { background: #f5f9f9; }
            .rp-table td.c { text-align: center; }
            .rp-table td.r { text-align: right; }
            .rp-table td.mat { text-transform: uppercase; font-size: 7.2pt; }
            .rp-table td.n { text-align: center; font-weight: bold; font-size: 8pt; }
            .rp-table td.fin { background: #fbf3df !important; }
            .rp-table tr.tot td { background: #dceaec !important; font-weight: bold; color: #12393f; border-top: 1pt solid #26687B; }
            .rp-table tr.tot td.lbl { text-align: right; font-size: 6.6pt; letter-spacing: 1.1px; text-transform: uppercase; }
            .rp-table tr.gran td { background: #26687B !important; color: #fff; font-weight: bold; border-color: #26687B; }
            .rp-table tr.gran td.lbl { text-align: right; font-size: 6.8pt; letter-spacing: 1.2px; text-transform: uppercase; }
            .rp-fail { color: #b42318 !important; font-weight: bold; }
            .rp-equiv { color: #8a6514 !important; font-weight: bold; font-size: 6.6pt; letter-spacing: .8px; }
            .rp-gran .rp-fail { color: #ffd2cc !important; }

            /* Paneles e indicadores */
            .rp-box { border: .6pt solid #b9c9cc; background: #fff; }
            .rp-box-h { background: #26687B; color: #fff; font-size: 6.5pt; font-weight: bold; letter-spacing: 1.1px; text-transform: uppercase; text-align: center; padding: 1.2mm 2mm; }
            .rp-box-b { padding: 2mm 2.5mm; }
            .rp-stat { text-align: center; }
            .rp-stat .v { font-size: 15pt; font-weight: bold; color: #12393f; line-height: 1.1; }
            .rp-stat .v.sm { font-size: 11pt; }
            .rp-stat .s { font-size: 6.4pt; font-weight: bold; color: #26687B; letter-spacing: .6px; text-transform: uppercase; margin-top: .6mm; }
            .rp-stat .h { font-size: 6pt; color: #7a8a8d; margin-top: .4mm; }
            .rp-chip { display: inline-block; background: #dceaec; color: #12393f; font-weight: bold; padding: .3mm 2mm; border-radius: 2px; font-size: 7pt; }
            .rp-chip.alert { background: #fbe3df; color: #9a2a1f; }

            .rp-note { font-size: 7pt; color: #4c5d60; text-align: justify; line-height: 1.45; }
            .rp-fill { border-bottom: .8pt solid #12393f; padding: 0 2mm; font-weight: bold; text-transform: uppercase; color: #12393f; }

            /* Firmas */
            .rp-firmas { display: flex; justify-content: space-between; align-items: flex-end; gap: 6mm; }
            .rp-firma { flex: 1; text-align: center; }
            .rp-firma .ln { border-top: .8pt solid #12393f; margin: 0 6% 1.2mm; padding-top: 0; }
            .rp-firma .nm { font-size: 7.2pt; font-weight: bold; text-transform: uppercase; color: #12393f; margin-bottom: .8mm; }
            .rp-firma .rl { font-size: 6.6pt; font-weight: bold; letter-spacing: .9px; text-transform: uppercase; color: #26687B; }
            .rp-firma .dt { font-size: 6.4pt; color: #5b6b6e; margin-top: .3mm; }
            .rp-sello { width: 26mm; height: 20mm; flex: 0 0 26mm; border: .8pt dashed #9fb3b7; border-radius: 2mm; display: flex; align-items: center; justify-content: center; font-size: 6.2pt; font-weight: bold; letter-spacing: 1.2px; color: #9fb3b7; text-transform: uppercase; }

            /* Pie */
            .rp-foot { margin-top: 4mm; border-top: .3mm solid #c9a24b; padding-top: 1.4mm; display: flex; justify-content: space-between; font-size: 6.4pt; color: #5b6b6e; letter-spacing: .6px; text-transform: uppercase; }
            .rp-foot b { color: #26687B; }
            .rp-cut { display: flex; align-items: center; gap: 3mm; margin: 4mm 0; font-size: 6.2pt; font-weight: bold; letter-spacing: 1.2px; color: #7a8a8d; text-transform: uppercase; }
            .rp-cut::before, .rp-cut::after { content: ""; flex: 1; border-top: .8pt dashed #9fb3b7; }
            .rp-page-break { page-break-before: always; break-before: page; height: 0; }
        `;

        const esc = (v) => String(v ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

        /** Encabezado: { titulo, subtitulo?, lineas?: [], tag?, tagSub?, folio? } */
        function head(o) {
            const lineas = (o.lineas || []).filter(Boolean).map(l => `<div class="rp-sub">${l}</div>`).join('');
            let side = '';
            if (o.folio) {
                side = `<div class="rp-folio">Folio</div><div class="rp-folio-val">${o.folio}</div>${o.tagSub ? `<div class="rp-tag-sub">${o.tagSub}</div>` : ''}`;
            } else if (o.tag) {
                side = `<span class="rp-tag">${o.tag}</span>${o.tagSub ? `<div class="rp-tag-sub">${o.tagSub}</div>` : ''}`;
            }
            return `
                <div class="rp-head">
                    <img class="rp-logo" src="/img/logo.png" alt="Logo">
                    <div class="rp-head-main">
                        <div class="rp-inst">${o.institucion || 'Bachillerato Tecnológico Interamericano'}</div>
                        ${o.titulo ? `<div class="rp-title">${o.titulo}</div>` : ''}
                        ${lineas}
                    </div>
                    ${side ? `<div class="rp-head-side">${side}</div>` : ''}
                </div>
                <div class="rp-rule"></div><div class="rp-rule-thin"></div>`;
        }

        /** Tarjetas: [{ label, value, flex?, lg? }] */
        function meta(items) {
            return `<div class="rp-meta">${items.map(i => `
                <div class="rp-card" style="flex: ${i.flex || 1};">
                    <span class="rp-label">${i.label}</span>
                    <span class="rp-value${i.lg ? ' lg' : ''}">${i.value ?? '—'}</span>
                </div>`).join('')}</div>`;
        }

        /** Firmas: [{ nombre?, rol, detalle? } | 'sello'] */
        function firmas(items, alto = '12mm') {
            return `<div class="rp-firmas">${items.map(i => (typeof i === 'string' && i.startsWith('sello'))
                ? `<div class="rp-sello">${i.split(':')[1] || 'Sello'}</div>`
                : `<div class="rp-firma">
                        <div style="height: ${alto};"></div>
                        ${i.nombre ? `<div class="nm">${i.nombre}</div>` : ''}
                        <div class="ln"></div>
                        <div class="rl">${i.rol}</div>
                        ${i.detalle ? `<div class="dt">${i.detalle}</div>` : ''}
                   </div>`).join('')}</div>`;
        }

        function foot(izq, der) {
            if (!der) return `<div class="rp-foot" style="justify-content: center;"><b>${izq || ''}</b></div>`;
            return `<div class="rp-foot"><span>${izq || ''}</span><span>${der}</span></div>`;
        }

        /** Documento completo para window.open */
        function documento(titulo, cuerpo, opts = {}) {
            return `<!DOCTYPE html>
                <html lang="es">
                <head>
                    <meta charset="UTF-8">
                    <title>${titulo}</title>
                    <style>
                        @page { size: ${opts.size || 'letter portrait'}; margin: ${opts.margin || '10mm 11mm'}; }
                        html, body { margin: 0; padding: 0; background: #fff; }
                        ${css}
                    </style>
                </head>
                <body><div class="rp">${cuerpo}</div></body>
                </html>`;
        }

        return { css, esc, head, meta, firmas, foot, documento };
    })();
</script>

<!-- LÓGICA DE NEGOCIO PARA SELECCIÓN DE CCT Y FORMATOS CONDICIONALES -->
<script>
    // Configuración de Submódulos autorizados por CCT
    const submodulosPorCCT = {
        'BTI': [
            { id: 'constancia', nombre: 'Constancia de estudios', icon: 'file-badge' },
            { id: 'kardex', nombre: 'Kárdex académico', icon: 'scroll-text' },
            { id: 'boleta', nombre: 'Boleta de calificaciones', icon: 'file-spreadsheet' },
            { id: 'reporte_indisciplina', nombre: 'Reportes de indisciplina', icon: 'shield-alert' },
            { id: 'credencial', nombre: 'Credencial escolar', icon: 'id-card' },
            { id: 'extraordinario', nombre: 'Examen extraordinario', icon: 'file-pen' },
            { id: 'asistencias', nombre: 'Lista de asistencias', icon: 'calendar-check' },
            { id: 'actas_ordinario', nombre: 'Actas ordinario', icon: 'clipboard-list' }
        ],
        'BGNE': [
            { id: 'constancia', nombre: 'Constancia de estudios', icon: 'file-badge' },
            { id: 'kardex', nombre: 'Kárdex académico', icon: 'scroll-text' },
            { id: 'credencial', nombre: 'Credencial escolar', icon: 'id-card' },
            { id: 'extraordinario', nombre: 'Examen extraordinario', icon: 'file-pen' },
            { id: 'asistencias', nombre: 'Lista de asistencias', icon: 'calendar-check' },
            { id: 'actas_bgne', nombre: 'Actas ordinario', icon: 'clipboard-list' }
        ],
        'INF': [
            { id: 'credencial', nombre: 'Credencial escolar', icon: 'id-card' },
            { id: 'constancia', nombre: 'Constancia de estudios', icon: 'file-badge' }
        ]
    };

    let cctSeleccionado = '';
    let alumnoConstanciaId = null; // alumno elegido en "Constancia de estudios"

    // Función principal para la selección del CCT
    function selectCCT(cct) {
        cctSeleccionado = cct;
        if (typeof limpiarBusquedaKardex === 'function') { limpiarBusquedaKardex(); }
        
        // Actualizar UI del selector
        document.querySelectorAll('.cct-tab').forEach(card => card.classList.remove('selected-cct', 'active'));
        
        const activeCardId = cct === 'BTI' ? 'cct-bti' : (cct === 'BGNE' ? 'cct-bgne' : 'cct-inf');
        const activeCard = document.getElementById(activeCardId);
        if (activeCard) {
            activeCard.classList.add('selected-cct', 'active');
        }

        // Ocultar placeholder si existiera y mostrar contenedor principal en grid
        const placeholder = document.getElementById('placeholder-select-cct');
        if (placeholder) placeholder.style.display = 'none';
        const container = document.getElementById('formatos-container');
        if (container) container.style.display = 'grid';

        // Actualizar textos en la UI
        document.getElementById('lbl-cct-activo').innerText = cct;
        const credBadge = document.getElementById('cred-cct-badge'); if (credBadge) credBadge.innerText = `PLAN ${cct}`;

        // Sincronizar plantilla de credenciales escolares según CCT
        actualizarCredencialPorCCT(cct);

        // Semestre (BTI) / Trimestre (BGNE) en Examen extraordinario
        configurarPeriodoExtraordinario(cct);

        // Actualizar etiqueta de ciclo en Asistencia
        const label = document.getElementById('lbl-asistencia-ciclo-label');
        const select = document.getElementById('asistenciaCicloSelect');
        if (label && select) {
            select.innerHTML = '';
            if (cct === 'BTI') {
                label.innerText = 'Semestre';
                for (let i = 1; i <= 6; i++) {
                    select.innerHTML += `<option value="${i}° Semestre">${i}° Semestre</option>`;
                }
            } else {
                label.innerText = 'Trimestre';
                for (let i = 1; i <= 6; i++) {
                    select.innerHTML += `<option value="${i}° Trimestre">${i}° Trimestre</option>`;
                }
            }
        }

        // Mostrar o esconder selector de periodos de escolarizado según CCT
        const escolarizadoRow = document.getElementById('asistencia-periodo-escolarizado-row');
        if (escolarizadoRow) {
            escolarizadoRow.style.display = (cct === 'BTI') ? 'block' : 'none';
        }

        // Cargar los grupos del CCT seleccionado
        actualizarGruposPorCCT(cct);

        // Resetear vistas previas de sugerencias y búsquedas
        resetSubmodulosState();

        // Cargar los submódulos (tabs) condicionalmente en la barra lateral
        cargarSubmodulosNav(cct);
    }

    function actualizarGruposPorCCT(cct) {
        const select = document.getElementById('asistenciaGrupoSelect');
        if (!select) return;
        select.innerHTML = '<option value="">Seleccione Grupo</option>';

        const targetCentroId = cct === 'BTI' ? 2 : (cct === 'BGNE' ? 3 : 1);
        const filtered = (window.gruposDb || []).filter(g => {
            const cid = g.id_centroTrabajo ?? g.idCentroTrabajo ?? g.id_centro_trabajo;
            return cid == targetCentroId;
        });

        filtered.forEach(g => {
            const opt = document.createElement('option');
            opt.value = g.id;
            opt.textContent = `${g.clave} (${g.modalidadHorario || 'General'})`;
            select.appendChild(opt);
        });

        const previewCard = document.getElementById('asistencia-preview-card');
        if (previewCard) previewCard.style.display = 'none';
        sincronizarInputGrupoAsistencia();

        asegurarCatalogosAsistencia();
    }

    let isFetchingCatalogos = false;
    async function asegurarCatalogosAsistencia() {
        if (isFetchingCatalogos) return;
        if (!window.gruposDb || window.gruposDb.length === 0 || !window.docentesDb || window.docentesDb.length === 0) {
            isFetchingCatalogos = true;
            try {
                const [gRes, dRes] = await Promise.all([
                    fetch('/grupos/lista?limit=1000').then(r => r.json()).catch(() => ({ data: [] })),
                    fetch('/docentes/lista').then(r => r.json()).catch(() => ({ data: [] }))
                ]);
                if ((!window.gruposDb || window.gruposDb.length === 0) && gRes.data) {
                    window.gruposDb = gRes.data;
                    if (cctSeleccionado) {
                        const select = document.getElementById('asistenciaGrupoSelect');
                        if (select && select.options.length <= 1) {
                            const targetCentroId = cctSeleccionado === 'BTI' ? 2 : (cctSeleccionado === 'BGNE' ? 3 : 1);
                            const filtered = (window.gruposDb || []).filter(g => {
                                const cid = g.id_centroTrabajo ?? g.idCentroTrabajo ?? g.id_centro_trabajo;
                                return cid == targetCentroId;
                            });
                            filtered.forEach(g => {
                                const opt = document.createElement('option');
                                opt.value = g.id;
                                opt.textContent = `${g.clave} (${g.modalidadHorario || 'General'})`;
                                select.appendChild(opt);
                            });
                        }
                    }
                }
                if (!window.docentesDb || window.docentesDb.length === 0) {
                    const rawDoc = dRes.data || (Array.isArray(dRes) ? dRes : []);
                    window.docentesDb = rawDoc;
                    poblarSelectDocentes(rawDoc);
                }
            } catch (err) {
                console.warn('Carga diferida catálogos:', err);
            } finally {
                isFetchingCatalogos = false;
            }
        }
    }

    function poblarSelectDocentes(lista) {
        if (lista && Array.isArray(lista) && lista.length > 0) {
            window.docentesDb = lista;
        }
        const sel = document.getElementById('asistenciaDocenteSelect');
        if (!sel || sel.options.length > 1) return;
        lista.forEach(d => {
            const id = d.idDocente || d.id;
            const nom = d.nombreDocente || d.nombre || '';
            const pat = d.apPaternoDocente || d.apPaterno || '';
            const mat = d.apMaternoDocente || d.apMaterno || '';
            const opt = document.createElement('option');
            opt.value = id;
            opt.textContent = `${nom} ${pat} ${mat}`.trim();
            sel.appendChild(opt);
        });
    }

    function resetSubmodulosState() {
        // Ocultar e inhabilitar búsquedas anteriores
        document.getElementById('constancia-info-alumno').style.display = 'none';
        const constPl = document.getElementById('constancia-preview-placeholder'); if (constPl) constPl.style.display = 'block';
        document.getElementById('constancia-bti-campos').style.display = 'none';
        document.getElementById('constancia-bgne-campos').style.display = 'none';
        document.getElementById('constanciaAlumnoSearch').value = '';
        alumnoConstanciaId = null;
        // BTI y BGNE tienen sus propios campos de constancia; el selector de tipo genérico solo aplica a INF
        document.getElementById('constancia-tipo-wrapper').style.display = ['BTI', 'BGNE'].includes(cctSeleccionado) ? 'none' : 'block';
        document.getElementById('kardex-info-alumno').style.display = 'none';
        document.getElementById('boleta-tabla-resultados').style.display = 'none';
        document.getElementById('credencial-info-alumno').style.display = 'none';
        document.getElementById('extraordinario-info-alumno').style.display = 'none';
        
        const asistCard = document.getElementById('asistencia-preview-card');
        if (asistCard) asistCard.style.display = 'none';
        
        // Limpiar controles de Asistencia
        const docenteSelect = document.getElementById('asistenciaDocenteSelect');
        const docenteInput = document.getElementById('asistenciaDocenteInput');
        const grupoSelect = document.getElementById('asistenciaGrupoSelect');
        const materiaInput = document.getElementById('asistenciaMateriaInput');
        if (docenteSelect) docenteSelect.value = '';
        if (docenteInput) docenteInput.value = '';
        if (grupoSelect) grupoSelect.value = '';
        if (materiaInput) materiaInput.value = '';
        sincronizarInputGrupoAsistencia();

        const btnClearDoc = document.getElementById('btn-limpiar-asistencia-docente');
        if (btnClearDoc) btnClearDoc.style.display = 'none';
        const sugDoc = document.getElementById('asistencia-docente-sugerencia');
        if (sugDoc) { sugDoc.innerHTML = ''; sugDoc.style.display = 'none'; }

        const btnClearMat = document.getElementById('btn-limpiar-asistencia-materia');
        if (btnClearMat) btnClearMat.style.display = 'none';
        const sugMat = document.getElementById('asistencia-materia-sugerencia');
        if (sugMat) { sugMat.innerHTML = ''; sugMat.style.display = 'none'; }
    }

    // Carga dinámica de la barra de navegación lateral según el CCT seleccionado
    function cargarSubmodulosNav(cct) {
        const nav = document.getElementById('submodulos-nav');
        nav.innerHTML = ''; // Limpiar anteriores

        const submodulos = submodulosPorCCT[cct];
        
        submodulos.forEach((sub, index) => {
            const button = document.createElement('button');
            // El formato activo se marca con fondo verde azulado claro
            button.className = `tw:flex tw:w-full tw:cursor-pointer tw:items-center tw:gap-2.5 tw:border-0 tw:bg-transparent tw:px-3 tw:py-2 tw:hover:bg-slate-100 tw:text-left tw:text-[0.9375rem] tw:text-slate-600 tw:hover:text-slate-900 tw:focus-visible:outline-2 tw:focus-visible:outline-brand-700 tw:[&.active]:bg-brand-50 tw:[&.active]:font-semibold tw:[&.active]:text-brand-800 ${index === 0 ? 'active' : ''}`;
            button.id = `tab-${sub.id}`;
            button.setAttribute('data-bs-toggle', 'pill');
            button.setAttribute('data-bs-target', `#pane-${sub.id}`);
            button.setAttribute('type', 'button');
            button.setAttribute('role', 'tab');
            button.innerHTML = `<i data-lucide="${sub.icon}" class="tw:size-[18px] tw:shrink-0"></i><span class="tw:truncate">${sub.nombre}</span>`;
            
            button.addEventListener('click', (e) => {
                if (window.bootstrap && bootstrap.Tab) {
                    bootstrap.Tab.getOrCreateInstance(button).show();
                }
                // Si cambiamos de pestaña, resetear inputs de búsqueda (sin borrar configuraciones de credencial)
                document.querySelectorAll('#submodulos-tab-content input:not([type="hidden"]):not([id^="inputCred"])').forEach(input => input.value = '');
                document.querySelectorAll('.list-group').forEach(list => list.style.display = 'none');
                
                if (sub.id === 'reporte_indisciplina') {
                    cargarHistorialReportes();
                }
                if (sub.id === 'credencial') {
                    const credInfo = document.getElementById('credencial-info-alumno');
                    if (credInfo) credInfo.style.display = 'block';
                    actualizarCredencialPorCCT(cctSeleccionado);
                }
            });

            nav.appendChild(button);
        });
        if (window.lucide?.createIcons) lucide.createIcons();

        // Activar el primer panel y desactivar TODOS los demás (incluidos los de otro CCT).
        // Antes se usaba una lista fija de ids y los apartados nuevos quedaban visibles al cambiar de plantel.
        document.querySelectorAll('[id^="pane-"].tab-pane').forEach(el => el.classList.remove('show', 'active'));

        // Activar el primero que pertenezca al CCT seleccionado
        const primerSubmoduloId = submodulos[0].id;
        const primerPane = document.getElementById(`pane-${primerSubmoduloId}`);
        primerPane.classList.add('show', 'active');

        // Si se carga la pestaña de asistencia, resetear vista previa
        actualizarAsistenciaPreview();
    }

    // ==========================================
    // SIMULACIÓN DE BÚSQUEDAS EN TIEMPO REAL
    // ==========================================

    let idAlumnoKardexActual = null;
    let datosKardexActual = null;
    let alumnoSeleccionadoActual = null;
    const canEditAlumno = @json(has_perm('alumnos_list', 'crear'));

    let ultimaBusquedaQuery = {};

    let debounceTimeouts = {};

    function buscarAlumnosReal(query, sugerenciaDivId, callbackSeleccion) {
        const div = document.getElementById(sugerenciaDivId);
        if (!div) return;
        
        if (query.trim().length < 2) {
            div.innerHTML = '';
            div.style.display = 'none';
            return;
        }

        clearTimeout(debounceTimeouts[sugerenciaDivId]);
        debounceTimeouts[sugerenciaDivId] = setTimeout(() => {
            ultimaBusquedaQuery[sugerenciaDivId] = query;

            fetch(`/alumnos/lista?search=${encodeURIComponent(query)}&limit=8`)
                .then(r => r.json())
                .then(resp => {
                    if (ultimaBusquedaQuery[sugerenciaDivId] !== query) {
                        return;
                    }

                    div.innerHTML = '';
                    const alumnos = resp.data || [];
                    if (alumnos.length === 0) {
                        div.style.display = 'none';
                        return;
                    }

                    div.style.display = 'block';
                    alumnos.forEach(al => {
                        const fullName = `${al.nombre} ${al.apPaterno} ${al.apMaterno || ''}`.trim();
                        const matricula = al.numeroControl || al.idAlumno || 'S/N';
                        
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'list-group-item list-group-item-action py-2 text-start';
                        btn.innerHTML = `
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold d-block text-slate-800 fs-8">${fullName}</span>
                                    <small class="text-muted fs-9">Matrícula: ${matricula} | CCT: ${al.claveCentroTrabajo || 'N/A'}</small>
                                </div>
                                <span class="badge bg-secondary-subtle text-secondary fs-9" style="font-size: 0.68rem !important;">${al.statusAlumno || 'ACTIVO'}</span>
                            </div>
                        `;
                        btn.onclick = (e) => {
                            e.preventDefault();
                            callbackSeleccion(al);
                            div.style.display = 'none';
                        };
                        div.appendChild(btn);
                    });
                })
                .catch(err => {
                    console.error('Error al buscar alumnos:', err);
                });
        }, 300);
    }

    function cargarKardexAlumnoReal(idAlumno, callbackSuccess) {
        idAlumnoKardexActual = idAlumno;
        Swal.fire({
            title: 'Cargando información...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(`/alumnos/${idAlumno}/kardex`)
            .then(r => r.json())
            .then(resp => {
                Swal.close();
                if (!resp.success || !resp.data) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo cargar la información del alumno.'
                    });
                    return;
                }

                datosKardexActual = resp.data;
                alumnoSeleccionadoActual = resp.data.alumno;
                if (callbackSuccess) {
                    callbackSuccess(resp.data);
                }
            })
            .catch(err => {
                Swal.close();
                console.error('Error al cargar kárdex:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un error al comunicarse con el servidor.'
                });
            });
    }

    function simularBusquedaAlumnoConstancia(query) {
        buscarAlumnosReal(query, 'constancia-alumno-sugerencia', (alumno) => {
            const fullName = `${alumno.nombre} ${alumno.apPaterno} ${alumno.apMaterno || ''}`.trim();
            document.getElementById('constanciaAlumnoSearch').value = fullName;
            document.getElementById('lbl-constancia-nombre').innerText = fullName;
            const detalle = [
                alumno.numeroControl ? `Matrícula ${alumno.numeroControl}` : 'Sin matrícula',
                alumno.nombreGrupoTexto ? `grupo ${alumno.nombreGrupoTexto}` : null,
                `plantel ${alumno.nombreCentroTrabajo || cctSeleccionado}`,
            ].filter(Boolean).join(', ');
            document.getElementById('lbl-constancia-carrera').innerText = detalle;
            document.getElementById('constancia-info-alumno').style.display = 'block';
            const constPl = document.getElementById('constancia-preview-placeholder'); if (constPl) constPl.style.display = 'none';

            alumnoConstanciaId = alumno.idAlumno;
            constanciaPreviewBase = { nombre: fullName.toUpperCase(), matricula: alumno.numeroControl || '' };
            document.getElementById('constancia-bti-campos').style.display = 'none';
            document.getElementById('constancia-bgne-campos').style.display = 'none';
            if (cctSeleccionado === 'BTI') cargarDatosConstanciaBti(alumno.idAlumno);
            if (cctSeleccionado === 'BGNE') cargarDatosConstanciaBgne(alumno.idAlumno);
        });
    }

    // ---------- Constancia de estudios BTI (PDF generado en el servidor) ----------
    function cargarDatosConstanciaBti(idAlumno) {
        fetch(`/reportes/constancia-estudios-bti/${idAlumno}/datos`, { headers: { Accept: 'application/json' } })
            .then(r => r.json())
            .then(resp => {
                if (alumnoConstanciaId !== idAlumno) return; // se eligió otro alumno mientras cargaba
                if (!resp.success) throw new Error(resp.message);
                const d = resp.data;
                const set = (id, v) => { const el = document.getElementById(id); if (el) el.value = v || ''; };
                set('constanciaOficio', d.oficio);
                set('constanciaFechaExpedicion', d.fecha_expedicion);
                set('constanciaFechaInicio', d.fecha_inicio);
                set('constanciaFechaFin', d.fecha_fin);

                // "1er Semestre" → "1ER SEMESTRE"; si no coincide con el catálogo se agrega como opción
                const sel = document.getElementById('constanciaSemestre');
                const sem = (d.semestre || '').toUpperCase().trim();
                if (sem && ![...sel.options].some(o => o.value === sem)) sel.add(new Option(sem, sem));
                if (sem) sel.value = sem;

                constanciaPreviewBase = { nombre: d.nombre_completo, matricula: d.numero_control };
                document.getElementById('constancia-aviso-matricula').style.display = d.numero_control ? 'none' : 'block';
                document.getElementById('constancia-bti-campos').style.display = 'block';
                actualizarPreviewConstancia();
            })
            .catch(err => {
                console.error('Error al cargar datos de la constancia:', err);
                document.getElementById('constancia-bti-campos').style.display = 'block';
                actualizarPreviewConstancia();
                Swal.fire({ icon: 'warning', title: 'Falta el grupo del alumno', text: 'No se encontró su grupo, así que el semestre y el periodo no se llenaron. Captúralos a mano antes de generar la constancia.', confirmButtonColor: '#26687B' });
            });
    }

    // Vista previa de la hoja: mismos textos que el PDF (ConstanciaEstudiosController@pdfBti)
    let constanciaPreviewBase = { nombre: '', matricula: '' };
    const MESES_CONSTANCIA = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];

    function fechaConstancia(iso) {
        const m = /^(\d{4})-(\d{2})-(\d{2})$/.exec(iso || '');
        return m ? { dia: +m[3], mes: MESES_CONSTANCIA[+m[2] - 1], anio: m[1] } : null;
    }

    function actualizarPreviewConstancia() {
        const v = id => (document.getElementById(id)?.value || '').trim();
        const txt = (id, t) => { const el = document.getElementById(id); if (el) el.textContent = t; };
        const ini = fechaConstancia(v('constanciaFechaInicio'));
        const fin = fechaConstancia(v('constanciaFechaFin'));
        const exp = fechaConstancia(v('constanciaFechaExpedicion'));

        txt('cp-oficio', v('constanciaOficio').toUpperCase());
        txt('cp-nombre', constanciaPreviewBase.nombre);
        txt('cp-matricula', constanciaPreviewBase.matricula || 'S/N');
        txt('cp-semestre', v('constanciaSemestre'));
        txt('cp-periodo', ini && fin ? ` EN UN PERIODO COMPRENDIDO DEL ${ini.dia} DE ${ini.mes} DEL ${ini.anio} AL ${fin.dia} DE ${fin.mes} DE ${fin.anio}` : '');
        txt('cp-fecha', exp ? `TEZIUTLÁN, PUEBLA A ${exp.dia} DE ${exp.mes} DE ${exp.anio}` : '');
    }

    function generarConstancia() {
        if (cctSeleccionado === 'BGNE') { generarConstanciaBgne(); return; }
        if (cctSeleccionado !== 'BTI') {
            printDoc('Constancia de Estudios', document.getElementById('lbl-constancia-nombre').innerText);
            return;
        }
        if (!alumnoConstanciaId) {
            Swal.fire({ icon: 'info', title: 'Selecciona un alumno', confirmButtonColor: '#26687B' });
            return;
        }
        const v = id => (document.getElementById(id)?.value || '').trim();
        if (!v('constanciaFechaInicio') || !v('constanciaFechaFin')) {
            Swal.fire({ icon: 'warning', title: 'Periodo incompleto', text: 'Indica las fechas de inicio y fin del periodo.', confirmButtonColor: '#26687B' });
            return;
        }
        if (v('constanciaFechaInicio') > v('constanciaFechaFin')) {
            Swal.fire({ icon: 'warning', title: 'Periodo inválido', text: 'La fecha de inicio no puede ser posterior a la de fin.', confirmButtonColor: '#26687B' });
            return;
        }
        const params = new URLSearchParams({
            id_alumno: alumnoConstanciaId,
            oficio: v('constanciaOficio'),
            semestre: v('constanciaSemestre'),
            fecha_inicio: v('constanciaFechaInicio'),
            fecha_fin: v('constanciaFechaFin'),
            fecha_expedicion: v('constanciaFechaExpedicion'),
        });
        window.open(`/reportes/constancia-estudios-bti-pdf?${params}`, '_blank');
    }

    // ---------- Constancia de estudios BGNE (cursando / concluyó) ----------
    function cargarDatosConstanciaBgne(idAlumno) {
        const set = (id, v) => { const el = document.getElementById(id); if (el) el.value = v ?? ''; };
        fetch(`/reportes/constancia-estudios-bgne/${idAlumno}/datos`, { headers: { Accept: 'application/json' } })
            .then(r => r.json())
            .then(resp => {
                if (alumnoConstanciaId !== idAlumno) return; // se eligió otro alumno mientras cargaba
                if (!resp.success) throw new Error(resp.message);
                const d = resp.data;
                set('constanciaBgneTipo', d.tipo);
                set('constanciaBgneOficio', d.oficio);
                if (d.trimestre) set('constanciaBgneTrimestre', String(d.trimestre));
                set('constanciaBgneInicio', d.fecha_inicio);
                set('constanciaBgneFin', d.fecha_fin);
                set('constanciaBgneDias', d.dias);
                set('constanciaBgneFechaCert', d.fecha_certificado);
                set('constanciaBgneFolio', d.folio_certificado);
                set('constanciaBgneUltimaMateria', '');
                set('constanciaBgneExpedicion', d.fecha_expedicion);
                document.getElementById('constanciaBgneExtra').checked = false;
                document.getElementById('constancia-bgne-aviso').style.display = d.numero_control ? 'none' : 'block';
            })
            .catch(err => {
                console.error('Error al cargar datos de la constancia BGNE:', err);
                Swal.fire({ icon: 'warning', title: 'Falta el grupo del alumno', text: 'No se encontró su grupo, así que el trimestre y el periodo no se llenaron. Captúralos a mano.', confirmButtonColor: '#26687B' });
            })
            .finally(() => {
                if (alumnoConstanciaId !== idAlumno) return;
                document.getElementById('constancia-bgne-campos').style.display = 'block';
                onTipoConstanciaBgne();
            });
    }

    function onTipoConstanciaBgne() {
        const concluyo = document.getElementById('constanciaBgneTipo').value === 'concluyo';
        document.getElementById('constancia-bgne-cursando').style.display = concluyo ? 'none' : 'flex';
        document.getElementById('constancia-bgne-concluyo').style.display = concluyo ? 'flex' : 'none';
        programarPreviewBgne(0);
    }

    function onExtraConstanciaBgne() {
        document.getElementById('constancia-bgne-extra-campos').style.display =
            document.getElementById('constanciaBgneExtra').checked ? 'flex' : 'none';
        programarPreviewBgne(0);
    }

    function urlConstanciaBgne() {
        const v = id => (document.getElementById(id)?.value || '').trim();
        const concluyo = v('constanciaBgneTipo') === 'concluyo';
        const p = new URLSearchParams({
            id_alumno: alumnoConstanciaId,
            tipo: concluyo ? 'concluyo' : 'cursando',
            tratamiento: v('constanciaBgneTratamiento') || 'EL',
            oficio: v('constanciaBgneOficio'),
            fecha_expedicion: v('constanciaBgneExpedicion'),
        });
        if (concluyo) {
            p.set('fecha_ultima_materia', v('constanciaBgneUltimaMateria'));
            p.set('fecha_certificado', v('constanciaBgneFechaCert'));
            p.set('folio_certificado', v('constanciaBgneFolio'));
        } else {
            p.set('trimestre', v('constanciaBgneTrimestre'));
            p.set('fecha_inicio', v('constanciaBgneInicio'));
            p.set('fecha_fin', v('constanciaBgneFin'));
            if (document.getElementById('constanciaBgneExtra').checked) {
                p.set('extracurricular', '1');
                p.set('curso', v('constanciaBgneCurso'));
                p.set('dias', v('constanciaBgneDias'));
                p.set('horario', v('constanciaBgneHorario'));
            }
        }
        // Las fechas vacías no se envían (el servidor las valida como fecha)
        [...p.keys()].forEach(k => { if (p.get(k) === '') p.delete(k); });
        return `/reportes/constancia-estudios-bgne-pdf?${p}`;
    }

    // La vista previa recarga el PDF real; se espera a que el usuario deje de escribir
    let timerPreviewBgne = null;
    function programarPreviewBgne(espera = 700) {
        clearTimeout(timerPreviewBgne);
        timerPreviewBgne = setTimeout(() => {
            const frame = document.getElementById('constancia-bgne-preview');
            if (!frame || !alumnoConstanciaId || document.getElementById('constancia-bgne-campos').style.display === 'none') return;
            frame.src = `${urlConstanciaBgne()}#toolbar=0&navpanes=0&view=FitH`;
        }, espera);
    }

    function generarConstanciaBgne() {
        if (!alumnoConstanciaId) {
            Swal.fire({ icon: 'info', title: 'Selecciona un alumno', confirmButtonColor: '#26687B' });
            return;
        }
        const v = id => (document.getElementById(id)?.value || '').trim();
        if (v('constanciaBgneTipo') === 'cursando') {
            if (!v('constanciaBgneInicio') || !v('constanciaBgneFin')) {
                Swal.fire({ icon: 'warning', title: 'Periodo incompleto', text: 'Indica las fechas de inicio y fin del trimestre.', confirmButtonColor: '#26687B' });
                return;
            }
            if (v('constanciaBgneInicio') > v('constanciaBgneFin')) {
                Swal.fire({ icon: 'warning', title: 'Periodo inválido', text: 'La fecha de inicio no puede ser posterior a la de fin.', confirmButtonColor: '#26687B' });
                return;
            }
        } else if (!v('constanciaBgneUltimaMateria') || !v('constanciaBgneFechaCert') || !v('constanciaBgneFolio')) {
            Swal.fire({ icon: 'warning', title: 'Faltan datos del certificado', text: 'Captura la fecha de la última materia, la fecha de expedición del certificado y su folio.', confirmButtonColor: '#26687B' });
            return;
        }
        window.open(urlConstanciaBgne(), '_blank');
    }

    function limpiarBusquedaKardex() {
        const input = document.getElementById('kardexAlumnoSearch');
        if (input) input.value = '';
        const sugerencias = document.getElementById('kardex-alumno-sugerencia');
        if (sugerencias) {
            sugerencias.innerHTML = '';
            sugerencias.style.display = 'none';
        }
        const infoBox = document.getElementById('kardex-info-alumno');
        if (infoBox) infoBox.style.display = 'none';
        datosKardexActual = null;
        idAlumnoKardexActual = null;
    }

    function simularBusquedaAlumnoKardex(query) {
        buscarAlumnosReal(query, 'kardex-alumno-sugerencia', (alumno) => {
            const fullName = `${alumno.nombre} ${alumno.apPaterno} ${alumno.apMaterno || ''}`.trim();
            document.getElementById('kardexAlumnoSearch').value = fullName;
            
            cargarKardexAlumnoReal(alumno.idAlumno, (data) => {
                datosKardexActual = data;
                idAlumnoKardexActual = alumno.idAlumno;
                const al = data.alumno || alumno;
                
                document.getElementById('lbl-kardex-nombre').innerText = fullName;
                const cctClave = al.claveCentroTrabajo || (cctSeleccionado === 'BTI' ? '21PCT0073R' : '21PBH0353G');
                document.getElementById('lbl-kardex-matricula').innerText = `Matrícula: ${al.numeroControl || al.idAlumno} | CCT: ${cctClave}`;
                
                const badgeCct = document.getElementById('lbl-kardex-badge-cct');
                if (badgeCct) badgeCct.innerText = al.nombreCentroTrabajo || cctSeleccionado || 'BTI';

                const badgeStatus = document.getElementById('lbl-kardex-status');
                if (badgeStatus) {
                    badgeStatus.innerText = al.statusAlumno || 'ACTIVO';
                    if ((al.statusAlumno || '').toUpperCase() === 'INACTIVO' || (al.statusAlumno || '').toUpperCase() === 'BAJA') {
                        badgeStatus.className = 'tw:inline-flex tw:items-center tw:bg-danger-100 tw:px-2.5 tw:py-0.5 tw:text-[0.6875rem] tw:font-semibold tw:text-danger-600';
                    } else {
                        badgeStatus.className = 'tw:inline-flex tw:items-center tw:bg-success-100 tw:px-2.5 tw:py-0.5 tw:text-[0.6875rem] tw:font-semibold tw:text-success-700';
                    }
                }

                const genGrupo = document.getElementById('lbl-kardex-generacion-grupo');
                if (genGrupo) {
                    genGrupo.innerText = `Gen: ${al.nombreGeneracion || 'N/A'} | Grupo: ${al.claveGrupo || 'N/A'}`;
                }

                const prom = (data.promedioGeneral !== null && data.promedioGeneral !== undefined) ? data.promedioGeneral : '—';
                document.getElementById('lbl-kardex-promedio').innerText = prom;

                // Renderizar vista previa integrada para que se pueda ver en pantalla antes de imprimir
                renderizarKardexPreview(data);

                // Poblar los elementos del modal en segundo plano (sin abrir modal)
                mostrarKardexConDatos(data, false);
                
                document.getElementById('kardex-info-alumno').style.display = 'block';
            });
        });
    }

    function renderizarKardexPreview(data) {
        const previewEl = document.getElementById('kardex-live-preview');
        if (!previewEl || !data || !data.alumno) return;

        const al = data.alumno;
        const isBti = (cctSeleccionado === 'BTI') || (al.id_centroTrabajo === 2 || al.claveCentroTrabajo === '21PCT0073R' || (al.nombreCentroTrabajo && al.nombreCentroTrabajo.toUpperCase().includes('BTI')));

        const alumnoNombre = `${al.apPaterno || ''} ${al.apMaterno || ''} ${al.nombre || ''}`.trim().toUpperCase() || '—';
        const cctClave = isBti 
            ? `CLAVE CT: ${al.claveCentroTrabajo || '21PCT0073R'}` 
            : `CLAVE CT: ${al.claveCentroTrabajo || '21PBH0353G'}`;
        const cctNombre = isBti 
            ? 'BACHILLERATO TECNOLÓGICO INTERAMERICANO' 
            : 'BACHILLERATO GENERAL NO ESCOLARIZADO';

        const periodos = data.periodos || [];
        const promFinal = (data.promedioGeneral !== null && data.promedioGeneral !== undefined) ? data.promedioGeneral : '—';

        function renderTablaPreviewPeriodo(p, is5to, is6to) {
            if (!p) return '';
            let filasHtml = '';
            (p.materias || []).forEach(m => {
                if (m.es_equivalencia) {
                    filasHtml += `
                        <tr>
                            <td class="text-uppercase" style="border: 1px solid #000; padding: 2px 4px; font-size: 7.5pt; text-align: left;">${m.nombreMateria}</td>
                            <td style="border: 1px solid #000; padding: 2px 4px; font-size: 7.5pt; text-align: center; font-weight: bold; color: #d97706;">EQUIV.</td>
                        </tr>
                    `;
                } else {
                    const cVal = (m.calificacion !== null && m.calificacion !== undefined) ? m.calificacion : '—';
                    const esRep = cVal !== '—' && !isNaN(cVal) && parseFloat(cVal) < 6.0;
                    const styleColor = esRep ? 'color: #dc2626 !important; font-weight: bold;' : 'color: #000;';
                    filasHtml += `
                        <tr>
                            <td class="text-uppercase" style="border: 1px solid #000; padding: 2px 4px; font-size: 7.5pt; text-align: left;">${m.nombreMateria}</td>
                            <td style="border: 1px solid #000; padding: 2px 4px; font-size: 8pt; text-align: center; ${styleColor}">${cVal}</td>
                        </tr>
                    `;
                }
            });

            let footerExtra = '';
            if (is5to) {
                const finalColor = promFinal !== '—' && !isNaN(promFinal) && parseFloat(promFinal) < 6.0 ? 'color: #dc2626 !important;' : 'color: #1e6fa8;';
                footerExtra = `
                    <tr style="font-weight: bold; background: #e8ecf2;">
                        <td style="border: 1.5px solid #000; padding: 2px 4px; font-size: 7.8pt; text-align: right;">PROMEDIO FINAL</td>
                        <td style="border: 1.5px solid #000; padding: 2px 4px; font-size: 8.5pt; font-weight: bold; text-align: center; background: #e8ecf2; ${finalColor}">${promFinal}</td>
                    </tr>
                `;
            } else if (is6to) {
                footerExtra = `
                    <tr>
                        <td colspan="2" style="border: 1px solid #000; height: 21px; background: #f8fafc;"></td>
                    </tr>
                `;
            }

            const pAvg = (p.promedio !== null && p.promedio !== undefined) ? p.promedio : '—';
            const promColor = pAvg !== '—' && !isNaN(pAvg) && parseFloat(pAvg) < 6.0 ? 'color: #dc2626 !important;' : 'color: #000;';

            return `
                <div style="margin-bottom: 8px;">
                    <div style="font-size: 8pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; color: #000;">
                        ${p.nombrePeriodo}
                    </div>
                    <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; font-family: Arial, sans-serif; background: #ffffff;">
                        <thead>
                            <tr style="background: #ffffff;">
                                <th style="border: 1px solid #000; padding: 2px 4px; font-size: 7.5pt; text-align: left; font-weight: bold;">MATERIA</th>
                                <th style="border: 1px solid #000; padding: 2px 2px; font-size: 7pt; text-align: center; font-weight: bold; width: 75px; line-height: 1.1;">${isBti ? 'CALIFICACIÓN<br>FINAL' : 'EVALUACIÓN<br>OBTENIDA'}</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${filasHtml}
                            <tr>
                                <td style="border: 1px solid #000; padding: 2px 4px; font-size: 7.8pt; font-weight: bold; text-align: right;">PROMEDIO</td>
                                <td style="border: 1px solid #000; padding: 2px 4px; font-size: 8.2pt; font-weight: bold; text-align: center; ${promColor}">${pAvg}</td>
                            </tr>
                            ${footerExtra}
                        </tbody>
                    </table>
                </div>
            `;
        }

        const fechaHoy = new Date();
        const meses = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
        const fechaTexto = `TEZIUTLÁN PUEBLA A ${fechaHoy.getDate()} DE ${meses[fechaHoy.getMonth()]} DE ${fechaHoy.getFullYear()}`;

        let signaturesHtml = '';
        if (isBti) {
            signaturesHtml = `
                <div style="margin-top: 15px; display: flex; justify-content: space-between; align-items: flex-end; width: 100%; font-family: Arial, sans-serif;">
                    <div style="width: 250px; text-align: center;">
                        <div style="border-bottom: 1px solid #000; width: 180px; margin: 0 auto 4px auto; height: 30px;"></div>
                        <div style="font-size: 7.8pt; font-weight: bold;">ING. FAUSTO LEYVA FLORES</div>
                        <div style="font-size: 7.2pt; color: #444; text-transform: uppercase;">DIRECTOR</div>
                    </div>
                    <div style="width: 250px; text-align: center; font-size: 8pt; font-weight: bold; padding-bottom: 10px;">
                        ${fechaTexto}
                    </div>
                </div>
            `;
        }

        previewEl.innerHTML = `
            <div style="max-width: 740px; margin: 0 auto; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 15px 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                <!-- MEMBRETE INSTITUCIONAL -->
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; gap: 10px;">
                    <div style="width: 70px; flex-shrink: 0; text-align: left;">
                        <img src="/img/logo.png" alt="Logo" style="height: 60px; object-fit: contain;">
                    </div>
                    <div style="flex-grow: 1; border: 1.5px solid #10599a; overflow: hidden; border-radius: 4px;">
                        <div style="background: #10599a; color: #ffffff; font-weight: bold; font-size: 11pt; padding: 3px 6px; text-align: center; letter-spacing: 0.8px;">
                            BACHILLERATO INTERAMERICANO
                        </div>
                        <div style="background: #d4ebf9; color: #000000; font-size: 7.2pt; padding: 2px 6px; text-align: center; line-height: 1.35;">
                            <div>Avenida Benito Juárez 901, Colonia Centro Teziutlán Puebla. Tel: 231-3123979</div>
                            <div style="font-weight: bold;">${cctClave}</div>
                        </div>
                    </div>
                </div>

                <!-- TEXTO DIRECCIÓN Y NOMBRE ALUMNO -->
                <div style="text-align: center; margin-bottom: 10px;">
                    <div style="font-size: 8.5pt; color: #000;">
                        La Dirección de la escuela <strong>${cctNombre}</strong>
                    </div>
                    <div style="font-size: 7.8pt; color: #333; font-style: italic;">
                        Reporta las siguientes calificaciones obtenidas hasta el momento del alumno(a):
                    </div>
                    <div style="display: inline-block; background: #e8ecf2; border: 1.5px solid #1e293b; border-radius: 20px; padding: 3px 25px; margin: 4px 0 6px 0; font-size: 11pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">
                        ${alumnoNombre}
                    </div>
                </div>

                <!-- GRID 2 COLUMNAS (1,3,5 a la izq y 2,4,6 a la der) -->
                <div style="display: flex; justify-content: space-between; gap: 15px; width: 100%;">
                    <div style="width: 50%;">
                        ${renderTablaPreviewPeriodo(periodos[0], false, false)}
                        ${renderTablaPreviewPeriodo(periodos[2], false, false)}
                        ${renderTablaPreviewPeriodo(periodos[4], true, false)}
                    </div>
                    <div style="width: 50%;">
                        ${renderTablaPreviewPeriodo(periodos[1], false, false)}
                        ${renderTablaPreviewPeriodo(periodos[3], false, false)}
                        ${renderTablaPreviewPeriodo(periodos[5], false, true)}
                    </div>
                </div>

                ${signaturesHtml}

                <!-- LEMA INFERIOR -->
                <div style="background: #4a90e2; color: #ffffff; font-weight: bold; font-size: 7.5pt; padding: 3px; margin-top: 10px; border-radius: 2px; text-align: center; letter-spacing: 0.5px;">
                    ¡ Excelencia educativa a su servicio !
                </div>
            </div>
        `;
    }

    function simularBusquedaAlumnoBoleta(query) {
        buscarAlumnosReal(query, 'boleta-alumno-sugerencia', (alumno) => {
            const fullName = `${alumno.nombre} ${alumno.apPaterno} ${alumno.apMaterno || ''}`.trim();
            document.getElementById('boletaAlumnoSearch').value = fullName;
            
            cargarKardexAlumnoReal(alumno.idAlumno, (data) => {
                const al = data.alumno;
                document.getElementById('lbl-boleta-alumno-nombre').innerText = fullName;
                document.getElementById('lbl-boleta-alumno-matricula').innerText = al.numeroControl || al.idAlumno;
                
                const semestre = document.getElementById('boletaSemestreSelect').value;
                document.getElementById('lbl-boleta-semestre-val').innerText = semestre;
                document.getElementById('boleta-tabla-resultados').style.display = 'block';
            });
        });
    }

    let alumnoCredencialSeleccionado = null;

    function actualizarCredencialPorCCT(cct) {
        const isBgne = (cct === 'BGNE');
        
        // Header de la vista / tarjeta
        const tipoBadgeEl = document.getElementById('cred-tipo-formato-badge');
        if (tipoBadgeEl) {
            tipoBadgeEl.innerHTML = isBgne
                ? '<i class="ti ti-award me-1"></i> Formato Oficial No Escolarizado'
                : '<i class="ti ti-award me-1"></i> Formato Oficial Escolarizado';
        }
        const descSubtituloEl = document.getElementById('cred-descripcion-subtitulo');
        if (descSubtituloEl) {
            descSubtituloEl.innerText = isBgne
                ? 'Emisión de credenciales oficiales institucionales en formato estándar PVC / Opalina (Modalidad No Escolarizada).'
                : 'Emisión de credenciales oficiales institucionales en formato estándar PVC / Opalina (Sistema Escolarizado).';
        }

        // Anverso de la credencial: Escuela y CCT
        const headerTitleEl = document.getElementById('cred-header-title');
        if (headerTitleEl) {
            headerTitleEl.innerText = isBgne ? 'BACHILLERATO' : 'BACHILLERATO TECNOLOGICO';
        }
        const headerSubtitleEl = document.getElementById('cred-header-subtitle');
        if (headerSubtitleEl) {
            headerSubtitleEl.innerText = 'INTERAMERICANO';
        }
        const cctValEl = document.getElementById('cred-cct-val');
        if (cctValEl) {
            cctValEl.innerText = isBgne ? 'C.C.T. 21PBH0353G' : 'C.C.T. 21PCT0073R';
        }

        // Reverso: Texto de institución
        const impTextoEl = document.getElementById('cred-importante-institucion-texto');
        if (impTextoEl) {
            impTextoEl.innerHTML = isBgne
                ? 'Esta credencial es personal y debe portarse en lugar visible mientras usted permanezca dentro de la institución.<br>Debe mostrarse al personal de seguridad, a la autoridad del Bachillerato Interamericano para fines de identificación. no es transferible y deberá devolverse al causar baja el usuario.'
                : 'Esta credencial es personal y debe portarse en lugar visible mientras usted permanezca dentro de la institución.<br>Debe mostrarse al personal de seguridad, a la autoridad del BTI para fines de identificación. no es transferible y deberá devolverse al causar baja el usuario.';
        }

        // Inputs de personalización
        const inputCctEl = document.getElementById('inputCredCCT');
        if (inputCctEl) {
            inputCctEl.value = isBgne ? '21PBH0353G' : '21PCT0073R';
        }

        const defaultMod = isBgne ? 'NO ESCOLARIZADA' : 'ESCOLARIZADO';
        const modValEl = document.getElementById('cred-modalidad-val');
        const inputModEl = document.getElementById('inputCredModalidad');
        if (!alumnoCredencialSeleccionado) {
            if (modValEl) modValEl.innerText = defaultMod;
            if (inputModEl) inputModEl.value = defaultMod;
        } else {
            const isAlumnoBgne = isBgne || (alumnoCredencialSeleccionado.id_centroTrabajo === 3) || (alumnoCredencialSeleccionado.claveCentroTrabajo === '21PBH0353G');
            const mod = isAlumnoBgne ? 'NO ESCOLARIZADA' : ((alumnoCredencialSeleccionado.modalidadHorario || alumnoCredencialSeleccionado.modalidad_estudio || '').toUpperCase().includes('SAB') ? 'SABATINO' : 'ESCOLARIZADO');
            if (modValEl) modValEl.innerText = mod;
            if (inputModEl) inputModEl.value = mod;
        }
    }

    function simularBusquedaAlumnoCredencial(query) {
        buscarAlumnosReal(query, 'credencial-alumno-sugerencia', (alumno) => {
            alumnoCredencialSeleccionado = alumno;
            const fullName = `${alumno.nombre} ${alumno.apPaterno} ${alumno.apMaterno || ''}`.trim().toUpperCase();
            document.getElementById('credencialAlumnoSearch').value = fullName;

            // Datos del frente
            document.getElementById('cred-nombre-val').innerText = fullName;
            document.getElementById('cred-matricula-val').innerText = alumno.numeroControl || alumno.idAlumno;
            const inputNom = document.getElementById('inputCredNombre');
            if (inputNom) inputNom.value = fullName;
            const inputMat = document.getElementById('inputCredMatricula');
            if (inputMat) inputMat.value = alumno.numeroControl || alumno.idAlumno;
            
            // Modalidad y CCT según selección actual o del alumno
            const isBgne = (cctSeleccionado === 'BGNE') || (alumno.id_centroTrabajo === 3) || (alumno.claveCentroTrabajo === '21PBH0353G');
            
            // Modalidad
            let modalidadTexto = 'ESCOLARIZADO';
            if (isBgne) {
                modalidadTexto = 'NO ESCOLARIZADA';
            } else {
                const modVal = (alumno.modalidadHorario || alumno.modalidad_estudio || 'ESCOLARIZADO').toUpperCase();
                modalidadTexto = modVal.includes('SAB') ? 'SABATINO' : 'ESCOLARIZADO';
            }
            document.getElementById('cred-modalidad-val').innerText = modalidadTexto;
            const inputMod = document.getElementById('inputCredModalidad');
            if (inputMod) inputMod.value = modalidadTexto;

            // CCT y Nombre de Escuela en credencial
            const cctClave = isBgne ? (alumno.claveCentroTrabajo || '21PBH0353G') : (alumno.claveCentroTrabajo || '21PCT0073R');
            const headerTitleEl = document.getElementById('cred-header-title');
            if (headerTitleEl) headerTitleEl.innerText = isBgne ? 'BACHILLERATO' : 'BACHILLERATO TECNOLOGICO';
            const headerSubtitleEl = document.getElementById('cred-header-subtitle');
            if (headerSubtitleEl) headerSubtitleEl.innerText = 'INTERAMERICANO';
            const cctValEl = document.getElementById('cred-cct-val');
            if (cctValEl) cctValEl.innerText = `C.C.T. ${cctClave}`;
            const inputCct = document.getElementById('inputCredCCT');
            if (inputCct) inputCct.value = cctClave;

            // Contacto de emergencia (tutor o default)
            const tutorNombre = (alumno.tutor || 'Ignacio Fernández Neville').trim();
            const tutorTel = (alumno.telefonoTutor || alumno.celularAlumno || '231 108 9100').trim();
            document.getElementById('cred-accidente-nombre-val').innerText = tutorNombre;
            document.getElementById('cred-accidente-tel-val').innerText = tutorTel;
            const inputAccNombre = document.getElementById('inputCredAccidenteNombre');
            const inputAccTel = document.getElementById('inputCredAccidenteTel');
            if (inputAccNombre) inputAccNombre.value = tutorNombre;
            if (inputAccTel) inputAccTel.value = tutorTel;

            document.getElementById('credencial-info-alumno').style.display = 'block';
        });
    }

    function actualizarCamposCredencialDinamicos() {
        const nom = document.getElementById('inputCredNombre')?.value || '';
        const mat = document.getElementById('inputCredMatricula')?.value || '';
        const cctRaw = document.getElementById('inputCredCCT')?.value || '';
        const mod = document.getElementById('inputCredModalidad')?.value || 'ESCOLARIZADO';
        const vig = document.getElementById('inputCredVigencia')?.value || '';
        const accNom = document.getElementById('inputCredAccidenteNombre')?.value || '';
        const accTel = document.getElementById('inputCredAccidenteTel')?.value || '';
        const dir = document.getElementById('inputCredDirector')?.value || '';

        if (document.getElementById('cred-nombre-val')) document.getElementById('cred-nombre-val').innerText = nom.toUpperCase();
        if (document.getElementById('cred-matricula-val')) document.getElementById('cred-matricula-val').innerText = mat;
        if (document.getElementById('cred-cct-val') && cctRaw) {
            const cleanCct = cctRaw.replace(/^C\.C\.T\.\s*/i, '').trim().toUpperCase();
            document.getElementById('cred-cct-val').innerText = `C.C.T. ${cleanCct}`;
            
            // Si el CCT introducido es el de BGNE, ajustar nombre de la escuela
            const isBgneCct = cleanCct === '21PBH0353G' || cctSeleccionado === 'BGNE';
            if (document.getElementById('cred-header-title')) {
                document.getElementById('cred-header-title').innerText = isBgneCct ? 'BACHILLERATO' : 'BACHILLERATO TECNOLOGICO';
            }
        }
        if (document.getElementById('cred-modalidad-val')) document.getElementById('cred-modalidad-val').innerText = mod.toUpperCase();
        if (document.getElementById('cred-vigencia-val')) document.getElementById('cred-vigencia-val').innerText = vig.toUpperCase();
        if (document.getElementById('cred-accidente-nombre-val')) document.getElementById('cred-accidente-nombre-val').innerText = accNom;
        if (document.getElementById('cred-accidente-tel-val')) document.getElementById('cred-accidente-tel-val').innerText = accTel;
        if (document.getElementById('cred-director-val')) document.getElementById('cred-director-val').innerText = dir.toUpperCase();
    }

    function setCredVigencia(texto) {
        const input = document.getElementById('inputCredVigencia');
        if (input) {
            input.value = texto;
            actualizarCamposCredencialDinamicos();
        }
    }

    function imprimirCredencialOficialBTI(modo = 'ambas') {
        const nombre = document.getElementById('cred-nombre-val')?.innerText || 'DANIEL IGNACIO FERNÁNDEZ PARRA';
        const modalidad = document.getElementById('inputCredModalidad')?.value || document.getElementById('cred-modalidad-val')?.innerText || (cctSeleccionado === 'BGNE' ? 'NO ESCOLARIZADA' : 'ESCOLARIZADO');
        const matricula = document.getElementById('cred-matricula-val')?.innerText || '419012603';
        const vigencia = document.getElementById('cred-vigencia-val')?.innerText || 'JULIO 2026 - SEPTIEMBRE 2026';
        const accNombre = document.getElementById('cred-accidente-nombre-val')?.innerText || 'Ignacio Fernández Neville';
        const accTel = document.getElementById('cred-accidente-tel-val')?.innerText || '231 108 9100';
        const director = document.getElementById('cred-director-val')?.innerText || 'ING. FAUSTO MAURO LEYVA FLORES';

        const headerTitle = document.getElementById('cred-header-title')?.innerText || (cctSeleccionado === 'BGNE' ? 'BACHILLERATO' : 'BACHILLERATO TECNOLOGICO');
        const headerSubtitle = document.getElementById('cred-header-subtitle')?.innerText || 'INTERAMERICANO';
        const cctTexto = document.getElementById('cred-cct-val')?.innerText || (cctSeleccionado === 'BGNE' ? 'C.C.T. 21PBH0353G' : 'C.C.T. 21PCT0073R');

        const isBgne = (cctSeleccionado === 'BGNE') || headerTitle.trim() === 'BACHILLERATO' || cctTexto.includes('21PBH0353G');

        const textoInstitucion = isBgne
            ? 'Debe mostrarse al personal de seguridad, a la autoridad del Bachillerato Interamericano para fines de identificación. no es transferible y deberá devolverse al causar baja el usuario.'
            : 'Debe mostrarse al personal de seguridad, a la autoridad del BTI para fines de identificación. no es transferible y deberá devolverse al causar baja el usuario.';

        const origin = window.location.origin;

        const win = window.open('', '_blank');
        if (!win) {
            Swal.fire({
                icon: 'warning',
                title: 'Ventana emergente bloqueada',
                text: 'Por favor, permite ventanas emergentes en tu navegador para ver la impresión.',
                confirmButtonColor: '#26687B'
            });
            return;
        }

        const cardFrenteHtml = `
            <div class="bti-print-card bti-card-front">
                <div class="bti-front-stripes"></div>
                <div class="bti-map-bg"></div>
                <div class="bti-header-banner">
                    <div class="bti-header-title">${headerTitle}</div>
                    <div class="bti-header-subtitle">${headerSubtitle}</div>
                </div>
                <div class="bti-sub-header">
                    <div class="bti-cct-text">${cctTexto}</div>
                    <div class="bti-official-logos">
                        <div class="bti-logos-row">
                            <img src="${origin}/img/credencial/logo_puebla.png" class="bti-logo-puebla" alt="Puebla">
                            <img src="${origin}/img/credencial/logo_sep.png" class="bti-logo-sep" alt="SEP">
                        </div>
                        <div class="bti-sep-text">EDUCACIÓN PÚBLICA</div>
                    </div>
                </div>
                <div class="bti-front-body">
                    <img src="${origin}/img/credencial/logo_bti.png" class="bti-corner-logo" alt="BTI">
                    <div class="bti-field-group">
                        <span class="bti-field-label">NOMBRE</span>
                        <div class="bti-capsule bti-capsule-full">${nombre}</div>
                    </div>
                    <div class="bti-field-group">
                        <span class="bti-field-label">MODALIDAD</span>
                        <div class="bti-capsule bti-capsule-sm">${modalidad}</div>
                    </div>
                    <div class="bti-field-group">
                        <span class="bti-field-label">MATRICULA</span>
                        <div class="bti-capsule bti-capsule-sm">${matricula}</div>
                    </div>
                    <div class="bti-field-group">
                        <span class="bti-field-label">VIGENCIA</span>
                        <div class="bti-capsule bti-capsule-full">${vigencia}</div>
                    </div>
                </div>
            </div>
        `;

        const cardReversoHtml = `
            <div class="bti-print-card bti-card-back">
                <div class="bti-back-stripes"></div>
                <img src="${origin}/img/credencial/logo_bti.png" class="bti-back-watermark" alt="BTI Watermark">
                <div class="bti-back-content">
                    <div>
                        <div class="bti-important-title">IMPORTANTE:</div>
                        <div class="bti-important-text">
                            Esta credencial es personal y debe portarse en lugar visible mientras usted permanezca dentro de la institución.<br>
                            ${textoInstitucion}
                        </div>
                        <div class="bti-accident-title">EN CASO DE ACCIDENTE LLAMAR A:</div>
                        <div class="bti-accident-capsule">
                            <span class="bti-accident-name">${accNombre}</span>
                            <span class="bti-accident-phone">${accTel}</span>
                        </div>
                    </div>
                    <div>
                        <div class="bti-signature-section">
                            <div class="bti-signature-line"></div>
                            <div class="bti-director-title">DIRECTOR</div>
                            <div class="bti-director-name">${director}</div>
                        </div>
                        <div class="bti-address-footer">
                            AV. JUÁREZ 901 ESQ. LEÓN GUZMÁN<br>
                            COL. CENTRO TEZIUTLÁN, PUEBLA.
                        </div>
                        <div class="bti-phone-footer">
                            TEL: 01 231 31 2 39 79
                        </div>
                    </div>
                </div>
            </div>
        `;

        let cardsHtml = '';
        if (modo === 'ambas') {
            cardsHtml = `
                <div class="cards-print-container">
                    <div class="bti-credencial-spread">
                        ${cardFrenteHtml}
                        ${cardReversoHtml}
                    </div>
                </div>
            `;
        } else if (modo === 'frente') {
            cardsHtml = `
                <div class="cards-print-container">
                    <div class="bti-single-card-spread">
                        ${cardFrenteHtml}
                    </div>
                </div>
            `;
        } else {
            cardsHtml = `
                <div class="cards-print-container">
                    <div class="bti-single-card-spread">
                        ${cardReversoHtml}
                    </div>
                </div>
            `;
        }

        win.document.write(`
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="utf-8">
                <base href="${origin}">
                <title>Credencial Escolar - ${nombre}</title>
                <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&display=swap" rel="stylesheet">
                <style>
                    * {
                        box-sizing: border-box;
                        margin: 0;
                        padding: 0;
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                    }
                    body {
                        font-family: 'Montserrat', Arial, sans-serif;
                        background: #ffffff;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        min-height: 100vh;
                        padding: 10mm;
                    }
                    @page {
                        size: auto;
                        margin: 8mm;
                    }
                    @media print {
                        body {
                            min-height: auto;
                            padding: 0;
                            margin: 0;
                        }
                        .cards-print-container {
                            padding-top: 10mm;
                        }
                    }
                    .cards-print-container {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        width: 100%;
                    }
                    /* AMBAS CARAS JUNTAS (CONTINUAS DÚPLEX) */
                    .bti-credencial-spread {
                        display: flex;
                        flex-direction: row;
                        flex-wrap: nowrap;
                        width: 108mm;
                        height: 85.6mm;
                        border: 0.35mm solid #64748b;
                        border-radius: 3.5mm;
                        overflow: hidden;
                        background: #ffffff;
                        box-sizing: content-box;
                        position: relative;
                        page-break-inside: avoid;
                        break-inside: avoid;
                    }
                    .bti-credencial-spread .bti-card-front {
                        border-right: 0.35mm solid #64748b;
                    }
                    /* FORMATO INDIVIDUAL */
                    .bti-single-card-spread {
                        display: flex;
                        width: 54mm;
                        height: 85.6mm;
                        border: 0.35mm solid #64748b;
                        border-radius: 3.5mm;
                        overflow: hidden;
                        background: #ffffff;
                        box-sizing: content-box;
                        position: relative;
                        page-break-inside: avoid;
                        break-inside: avoid;
                    }
                    .bti-print-card {
                        width: 54mm;
                        height: 85.6mm;
                        position: relative;
                        overflow: hidden;
                        display: flex;
                        flex-direction: column;
                        background: #ffffff;
                        box-sizing: border-box;
                        flex-shrink: 0;
                    }
                    .bti-card-front {
                        background: linear-gradient(135deg, #f0f7fd 0%, #e6f2fb 50%, #ffffff 100%);
                    }
                    .bti-front-stripes {
                        position: absolute;
                        left: 0;
                        top: 0;
                        bottom: 0;
                        width: 3.2mm;
                        background: #15285f;
                        border-right: 0.3mm solid #60a5fa;
                        z-index: 5;
                    }
                    .bti-front-stripes::after {
                        content: '';
                        position: absolute;
                        right: -0.9mm;
                        top: 0;
                        bottom: 0;
                        width: 0.4mm;
                        background: #15285f;
                    }
                    .bti-map-bg {
                        position: absolute;
                        left: 3mm;
                        top: 8mm;
                        right: 0;
                        bottom: 0;
                        background-image: url('${origin}/img/credencial/mapa_america.svg');
                        background-size: 260% auto;
                        background-position: 70% 32%;
                        background-repeat: no-repeat;
                        opacity: 0.35;
                        z-index: 1;
                    }
                    .bti-header-banner {
                        background: #15285f;
                        color: #ffffff;
                        text-align: center;
                        padding: 1.4mm 1mm 1.2mm 4mm;
                        position: relative;
                        z-index: 6;
                    }
                    .bti-header-title, .bti-header-subtitle {
                        font-size: 2.1mm;
                        font-weight: 900;
                        letter-spacing: 0.08mm;
                        line-height: 1.15;
                        text-transform: uppercase;
                    }
                    .bti-sub-header {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        padding: 0.9mm 1.5mm 0.8mm 5mm;
                        position: relative;
                        z-index: 6;
                    }
                    .bti-cct-text {
                        font-size: 1.9mm;
                        font-weight: 800;
                        color: #0f172a;
                    }
                    .bti-official-logos {
                        display: flex;
                        flex-direction: column;
                        align-items: flex-end;
                    }
                    .bti-logos-row {
                        display: flex;
                        align-items: center;
                        gap: 1.2mm;
                    }
                    .bti-logo-puebla {
                        height: 3.6mm;
                        width: auto;
                        object-fit: contain;
                    }
                    .bti-logo-sep {
                        height: 3.2mm;
                        width: auto;
                        object-fit: contain;
                    }
                    .bti-sep-text {
                        font-size: 1.1mm;
                        font-weight: 700;
                        color: #64748b;
                        letter-spacing: 0.05mm;
                    }
                    .bti-front-body {
                        flex: 1;
                        padding: 1.5mm 1.5mm 2.2mm 5.2mm;
                        display: flex;
                        flex-direction: column;
                        justify-content: flex-end;
                        position: relative;
                        z-index: 6;
                    }
                    .bti-corner-logo {
                        position: absolute;
                        right: 1mm;
                        bottom: 4.5mm;
                        width: 17.5mm;
                        height: 17.5mm;
                        z-index: 2;
                    }
                    .bti-field-group {
                        margin-bottom: 1.3mm;
                        position: relative;
                        z-index: 6;
                    }
                    .bti-field-group:last-child {
                        margin-bottom: 0;
                    }
                    .bti-field-label {
                        font-size: 1.7mm;
                        font-weight: 800;
                        color: #15285f;
                        text-transform: uppercase;
                        margin-bottom: 0.4mm;
                        display: block;
                    }
                    .bti-capsule {
                        background: #ffffff;
                        border: 0.35mm solid #38bdf8;
                        border-radius: 1.4mm;
                        padding: 0.7mm 1.2mm;
                        font-size: 1.7mm;
                        font-weight: 800;
                        color: #0f172a;
                        text-align: center;
                        text-transform: uppercase;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        display: block;
                    }
                    .bti-capsule-sm { width: 72%; }
                    .bti-capsule-full { width: 92%; }

                    /* REVERSO */
                    .bti-card-back {
                        background: #ffffff;
                        padding: 2.2mm 4.8mm 2.2mm 2.6mm;
                        justify-content: space-between;
                    }
                    .bti-back-stripes {
                        position: absolute;
                        right: 0;
                        top: 0;
                        bottom: 0;
                        width: 3.2mm;
                        background: #15285f;
                        border-left: 0.3mm solid #60a5fa;
                        z-index: 5;
                    }
                    .bti-back-stripes::before {
                        content: '';
                        position: absolute;
                        left: -0.9mm;
                        top: 0;
                        bottom: 0;
                        width: 0.4mm;
                        background: #15285f;
                    }
                    .bti-back-watermark {
                        position: absolute;
                        left: 45%;
                        top: 50%;
                        transform: translate(-50%, -50%);
                        width: 29mm;
                        height: 29mm;
                        opacity: 0.18;
                        z-index: 1;
                    }
                    .bti-back-content {
                        position: relative;
                        z-index: 6;
                        height: 100%;
                        display: flex;
                        flex-direction: column;
                        justify-content: space-between;
                        text-align: center;
                    }
                    .bti-important-title {
                        font-size: 2.4mm;
                        font-weight: 800;
                        color: #1e293b;
                        margin-bottom: 1.1mm;
                    }
                    .bti-important-text {
                        font-size: 1.35mm;
                        line-height: 1.32;
                        color: #334155;
                        font-weight: 600;
                        margin-bottom: 2mm;
                        text-align: justify;
                        padding: 0 0.8mm;
                    }
                    .bti-accident-title {
                        font-size: 1.5mm;
                        font-weight: 800;
                        color: #1e293b;
                        margin-bottom: 0.6mm;
                    }
                    .bti-accident-capsule {
                        background: #ffffff;
                        border: 0.35mm solid #38bdf8;
                        border-radius: 1.4mm;
                        padding: 0.8mm 1.2mm;
                        margin: 0 auto 2mm auto;
                        width: 90%;
                    }
                    .bti-accident-name {
                        font-size: 1.65mm;
                        font-weight: 800;
                        color: #0f172a;
                        display: block;
                        line-height: 1.2;
                    }
                    .bti-accident-phone {
                        font-size: 1.75mm;
                        font-weight: 800;
                        color: #0284c7;
                        display: block;
                        margin-top: 0.2mm;
                    }
                    .bti-signature-section {
                        margin-top: auto;
                        margin-bottom: 1.6mm;
                    }
                    .bti-signature-line {
                        width: 26mm;
                        height: 0.2mm;
                        background: #64748b;
                        margin: 0 auto 0.6mm auto;
                    }
                    .bti-director-title, .bti-director-name {
                        font-size: 1.5mm;
                        font-weight: 800;
                        color: #0f172a;
                        line-height: 1.2;
                    }
                    .bti-address-footer {
                        font-size: 1.25mm;
                        font-weight: 700;
                        color: #1e293b;
                        line-height: 1.25;
                    }
                    .bti-phone-footer {
                        font-size: 1.35mm;
                        font-weight: 800;
                        color: #0f172a;
                        margin-top: 0.2mm;
                    }
                </style>
            </head>
            <body>
                ${cardsHtml}
            </body>
            </html>
        `);
        win.document.close();
        win.focus();
        setTimeout(() => {
            win.print();
        }, 500);
    }

    function simularBusquedaAlumnoExtraordinario(query) {
        buscarAlumnosReal(query, 'extraordinario-alumno-sugerencia', (alumno) => {
            alumnoExtraordinarioActual = alumno;
            const fullName = `${alumno.nombre} ${alumno.apPaterno} ${alumno.apMaterno || ''}`.trim();
            const elSearch = document.getElementById('extraordinarioAlumnoSearch');
            if (elSearch) elSearch.value = fullName;
            const elLbl = document.getElementById('lbl-extraordinario-nombre');
            if (elLbl) elLbl.innerText = fullName;

            // El alumno ya está ligado a un grupo: se llena solo (y reemplaza el del alumno anterior)
            const grpInput = document.getElementById('extraordinarioGrupoInput');
            if (grpInput) grpInput.value = '';
            resolverGrupoDeAlumno(alumno).then(grupo => {
                if (alumnoExtraordinarioActual !== alumno) return; // se eligió otro alumno mientras cargaba
                if (grupo?.id) {
                    seleccionarGrupoExtraordinario(grupo);
                    return;
                }
                // Grupo fuera del catálogo (o sin grupo): se muestra su clave si la hay y el periodo
                // queda por elegir, para no arrastrar el del alumno anterior
                if (grpInput) grpInput.value = grupo?.clave || '';
                const semSelect = document.getElementById('extraordinarioSemestreSelect');
                if (semSelect) semSelect.value = '';
                actualizarExtraordinarioPreview();
            });

            actualizarExtraordinarioPreview();
            const infoCard = document.getElementById('extraordinario-info-alumno');
            if (infoCard) infoCard.style.display = 'block';
        });
    }

    /*
     * Grupo al que pertenece un alumno. La API de alumnos trae idGrupo y nombreGrupoTexto
     * (no claveGrupo); se busca en el catálogo de grupos por id y, si no, por clave.
     */
    async function resolverGrupoDeAlumno(alumno) {
        const id = alumno.idGrupo ?? alumno.id_grupo ?? null;
        const clave = String(alumno.nombreGrupoTexto || alumno.claveGrupo || alumno.nombreGrupo || alumno.grupo || '').trim();
        if (id == null && !clave) return null;

        const buscar = () => (window.gruposDb || []).find(g =>
            (id != null && (g.id ?? g.idGrupo) == id) ||
            (clave && (g.clave || '').toLowerCase() === clave.toLowerCase()));

        let grupo = buscar();
        if (!grupo) {
            await asegurarCatalogosAsistencia(); // carga el catálogo si aún no estaba
            grupo = buscar();
        }
        return grupo || (clave ? { clave } : null);
    }

    function simularFiltrarBoletas() {
        const nombre = document.getElementById('boletaAlumnoSearch').value.trim();
        if (!nombre) {
            Swal.fire({
                icon: 'warning',
                title: 'Campo incompleto',
                text: 'Por favor, busque y seleccione un alumno.',
                confirmButtonColor: '#26687B'
            });
            return;
        }
        const semestre = document.getElementById('boletaSemestreSelect').value;
        document.getElementById('lbl-boleta-semestre-val').innerText = semestre;
        document.getElementById('boleta-tabla-resultados').style.display = 'block';
    }

    // Modal, impresión y guardado de Kárdex y Boleta oficial
    function mostrarKardexConDatos(data, openModal = true) {
        if (!data || !data.alumno) return;
        const al = data.alumno;
        const periodos = data.periodos || [];

        const isBti = (cctSeleccionado === 'BTI') || (al.id_centroTrabajo === 2 || al.claveCentroTrabajo === '21PCT0073R' || (al.nombreCentroTrabajo && al.nombreCentroTrabajo.toUpperCase().includes('BTI')));

        // Datos de encabezado del Modal
        const nombreCompleto = `${al.apPaterno || ''} ${al.apMaterno || ''} ${al.nombre || ''}`.trim().toUpperCase();
        const elNombre = document.getElementById('kardexNombreAlumno');
        if (elNombre) elNombre.textContent = nombreCompleto || '—';

        const claveCctVal = isBti ? (al.claveCentroTrabajo || '21PCT0073R') : (al.claveCentroTrabajo || '21PBH0353G');
        const elClave = document.getElementById('kardexCCTClave');
        if (elClave) elClave.textContent = `CLAVE CT: ${claveCctVal}`;

        const nombreCctVal = isBti ? 'BACHILLERATO TECNOLÓGICO INTERAMERICANO' : 'BACHILLERATO GENERAL NO ESCOLARIZADO';
        const elCctNombre = document.getElementById('kardexCCTNombre');
        if (elCctNombre) elCctNombre.textContent = nombreCctVal;

        // Renderizar las 6 cajas de periodos (3 por columna: 1,3,5 a la izq y 2,4,6 a la der)
        let htmlPeriodos = '';
        periodos.forEach((p, idx) => {
            let htmlMaterias = '';
            
            (p.materias || []).forEach(m => {
                const isEquiv = m.es_equivalencia === true;
                if (isEquiv) {
                    htmlMaterias += `
                        <tr data-materia-id="${m.idMateria}" data-nivel="${p.idNivel}" data-is-equivalencia="true">
                            <td class="px-2 py-1 align-middle text-uppercase fw-semibold" style="font-size: 0.78rem; border-color: #cbd5e1;">
                                ${m.nombreMateria}
                            </td>
                            <td class="px-1 py-1 text-center align-middle" style="width: 85px; border-color: #cbd5e1;">
                                <span class="badge bg-warning text-dark px-2 py-1">EQUIV.</span>
                            </td>
                        </tr>
                    `;
                } else {
                    const califVal = (m.calificacion !== null && m.calificacion !== undefined) ? m.calificacion : '';
                    htmlMaterias += `
                        <tr data-materia-id="${m.idMateria}" data-nivel="${p.idNivel}">
                            <td class="px-2 py-1 align-middle text-uppercase fw-semibold" style="font-size: 0.78rem; border-color: #cbd5e1;">
                                ${m.nombreMateria}
                            </td>
                            <td class="px-1 py-1 text-center align-middle" style="width: 85px; border-color: #cbd5e1;">
                                <input type="text" maxlength="4" 
                                    class="form-control form-control-sm text-center fw-bold input-calif-kardex" 
                                    data-materia="${m.idMateria}" 
                                    data-nivel="${p.idNivel}" 
                                    data-periodo-idx="${idx}" 
                                    data-field="calificacion" 
                                    value="${califVal}" 
                                    style="height: 28px; font-size: 0.85rem; padding: 2px; background: transparent; border: 1px solid #cbd5e1;"
                                    oninput="this.value = this.value.toUpperCase(); calcularPromediosKardex()">
                            </td>
                        </tr>
                    `;
                }
            });

            const promInicial = (p.promedio !== null && p.promedio !== undefined) ? p.promedio : '—';

            let footerHtml = `
                <tfoot>
                    <tr class="bg-light fw-bold" style="border-color: #333; font-size: 0.78rem;">
                        <td class="text-end px-2 py-1 text-uppercase">PROMEDIO</td>
                        <td class="text-center px-1 py-1 text-primary fw-bold prom-periodo-val" id="promPeriodo_${idx}">${promInicial}</td>
                    </tr>
            `;

            if (idx === 4) { // 5to periodo
                const promGenVal = (data.promedioGeneral !== null && data.promedioGeneral !== undefined) ? data.promedioGeneral : '0.0';
                footerHtml += `
                    <tr class="fw-bold" style="border-color: #333; font-size: 0.8rem; background: #e2e8f0;">
                        <td class="text-end px-2 py-1 text-uppercase">PROMEDIO FINAL</td>
                        <td class="text-center px-1 py-1 fw-bold text-primary" id="kardexPromedioFinal">${promGenVal}</td>
                    </tr>
                `;
            } else if (idx === 5) { // 6to periodo (balance visual)
                footerHtml += `
                    <tr style="border-color: transparent; height: 26px;">
                        <td colspan="2" style="border: none !important; background: transparent;"></td>
                    </tr>
                `;
            }
            footerHtml += `</tfoot>`;

            htmlPeriodos += `
                <div class="col-6" style="width: 50%;">
                    <div class="border rounded-1 shadow-none overflow-hidden bg-white h-100" style="border-color: #000 !important;">
                        <div class="py-1 px-2 fw-bold text-dark text-uppercase bg-light border-bottom" style="font-size: 0.78rem; letter-spacing: 0.5px; border-color: #000 !important;">
                            ${p.nombrePeriodo}
                        </div>
                        <div class="table-responsive mb-0">
                            <table class="table table-bordered table-sm mb-0" style="border-color: #000 !important;">
                                <thead class="table-light">
                                    <tr style="font-size: 0.70rem; border-color: #000;">
                                        <th class="px-2 py-1 text-uppercase text-dark" style="border-color: #000 !important;">MATERIA</th>
                                        <th class="px-1 py-1 text-center text-uppercase text-dark" style="width: 85px; border-color: #000 !important;">${isBti ? 'FINAL' : 'EVALUACIÓN OBTENIDA'}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${htmlMaterias}
                                </tbody>
                                ${footerHtml}
                            </table>
                        </div>
                    </div>
                </div>
            `;
        });

        const contenedor = document.getElementById('contenedorPeriodosKardex');
        if (contenedor) {
            contenedor.innerHTML = htmlPeriodos;
        }
        calcularPromediosKardex();

        if (openModal) {
            const modalEl = document.getElementById('modalKardexAlumno');
            if (modalEl && modalEl.parentElement !== document.body) {
                document.body.appendChild(modalEl);
            }
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
    }

    window.recalcularFilaKardexSemestral = function(inputEl) {
    calcularPromediosKardex();
};

window.calcularPromediosKardex = function() {
    const inputs = document.querySelectorAll('.input-calif-kardex');
    const periodosSums = {};
    const periodosCounts = {};
    let sumGlobal = 0;
    let countGlobal = 0;

    inputs.forEach(inp => {
        const pIdx = inp.dataset.periodoIdx;
        const valStr = inp.value.trim();

        if (!periodosSums[pIdx]) {
            periodosSums[pIdx] = 0;
            periodosCounts[pIdx] = 0;
        }

        if (valStr !== '' && !isNaN(valStr)) {
            const num = parseFloat(valStr);
            periodosSums[pIdx] += num;
            periodosCounts[pIdx]++;
            sumGlobal += num;
            countGlobal++;

            if (num < 6.0) {
                inp.style.setProperty('color', '#dc2626', 'important');
                inp.style.setProperty('font-weight', '700', 'important');
            } else {
                inp.style.setProperty('color', '#1e293b', 'important');
                inp.style.setProperty('font-weight', '700', 'important');
            }
        } else if (valStr.toUpperCase() === 'EQUIV.' || valStr.toUpperCase() === 'EQUIVALENCIA') {
            inp.style.setProperty('color', '#d97706', 'important');
            inp.style.setProperty('font-weight', '700', 'important');
        } else {
            inp.style.setProperty('color', '#1e293b', 'important');
            inp.style.setProperty('font-weight', 'normal', 'important');
        }
    });

    // Actualizar promedios por periodo
    Object.keys(periodosSums).forEach(pIdx => {
        const el = document.getElementById(`promPeriodo_${pIdx}`);
        if (el) {
            if (periodosCounts[pIdx] > 0) {
                const avg = (periodosSums[pIdx] / periodosCounts[pIdx]).toFixed(1);
                el.textContent = avg;
                el.style.setProperty('color', parseFloat(avg) < 6.0 ? '#dc2626' : '#1e6fa8', 'important');
            } else {
                el.textContent = '—';
                el.style.setProperty('color', '#64748b', 'important');
            }
        }
    });

    // Actualizar promedio final (ubicado en el footer del 5to periodo)
    const finalEl = document.getElementById('kardexPromedioFinal');
    if (finalEl) {
        if (countGlobal > 0) {
            const promFinal = (sumGlobal / countGlobal).toFixed(1);
            finalEl.textContent = promFinal;
            finalEl.style.setProperty('color', parseFloat(promFinal) < 6.0 ? '#dc2626' : '#1e6fa8', 'important');
        } else {
            finalEl.textContent = '0.0';
            finalEl.style.setProperty('color', '#1e6fa8', 'important');
        }
    }
};

window.guardarCalificacionesKardex = function() {
    if (!idAlumnoKardexActual) return;
    const btn = document.getElementById('btnGuardarKardex');
    const rows = document.querySelectorAll('#contenedorPeriodosKardex tbody tr');
    const calificaciones = [];

    rows.forEach(tr => {
        const isEquiv = tr.getAttribute('data-is-equivalencia') === 'true';
        if (isEquiv) return;

        const idMateria = tr.getAttribute('data-materia-id');
        const idNivel = tr.getAttribute('data-nivel');
        if (!idMateria) return;

        const finalInp = tr.querySelector('.input-calif-kardex');
        if (!finalInp) return;

        const valStr = finalInp.value.trim();
        let calif = null;
        if (valStr !== '' && !isNaN(valStr)) {
            calif = parseFloat(valStr);
        }

        calificaciones.push({
            idMateria: parseInt(idMateria),
            id_nivel_academico: parseInt(idNivel || finalInp.dataset.nivel),
            calificacion: calif,
            tipoAcreditacion: 'ORDINARIO'
        });
    });

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Guardando...';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    fetch(`/alumnos/${idAlumnoKardexActual}/calificaciones`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ calificaciones: calificaciones })
    })
    .then(async r => {
        const data = await r.json().catch(() => null);
        if (!r.ok) {
            if (r.status === 419) {
                throw new Error('La sesión ha expirado (Error 419). Por favor recarga la página con F5.');
            }
            if (r.status === 401) {
                throw new Error('Tu sesión ha expirado. Por favor inicia sesión nuevamente.');
            }
            throw new Error((data && (data.error || data.message)) || `Error en el servidor (${r.status})`);
        }
        return data;
    })
    .then(resp => {
        btn.disabled = false;
        btn.innerHTML = '<i class="ti ti-device-floppy me-1"></i> Guardar Calificaciones';

        if (resp && resp.success) {
            Swal.fire({
                icon: 'success',
                title: 'Kárdex Actualizado',
                text: 'Las calificaciones se han guardado exitosamente.',
                timer: 2000,
                showConfirmButton: false
            });
            if (idAlumnoKardexActual) {
                cargarKardexAlumnoReal(idAlumnoKardexActual, (freshData) => {
                    datosKardexActual = freshData;
                    renderizarKardexPreview(freshData);
                });
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error al Guardar',
                text: (resp && (resp.error || resp.message)) || 'Error al guardar calificaciones'
            });
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="ti ti-device-floppy me-1"></i> Guardar Calificaciones';
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'Error al Guardar',
            text: err.message || 'Error de comunicación al guardar calificaciones'
        });
    });
};

window.imprimirKardex = function() {
    if (!datosKardexActual || !datosKardexActual.alumno) {
        Swal.fire({
            icon: 'warning',
            title: 'Sin datos de alumno',
            text: 'Por favor, busque y seleccione un alumno primero para generar el kárdex.',
            confirmButtonColor: '#26687B'
        });
        return;
    }

    const al = datosKardexActual.alumno;
    const isBti = (cctSeleccionado === 'BTI') || (al.id_centroTrabajo === 2 || al.claveCentroTrabajo === '21PCT0073R' || (al.nombreCentroTrabajo && al.nombreCentroTrabajo.toUpperCase().includes('BTI')));

    const alumnoNombre = `${al.apPaterno || ''} ${al.apMaterno || ''} ${al.nombre || ''}`.trim().toUpperCase() || '—';
    const cctClave = isBti 
        ? `CLAVE CT: ${al.claveCentroTrabajo || '21PCT0073R'}` 
        : `CLAVE CT: ${al.claveCentroTrabajo || '21PBH0353G'}`;
    const cctNombre = isBti 
        ? 'BACHILLERATO TECNOLÓGICO INTERAMERICANO' 
        : 'BACHILLERATO GENERAL NO ESCOLARIZADO';

    // Mapear valores editados en inputs si existen en el modal
    const inputsMap = {};
    document.querySelectorAll('.input-calif-kardex').forEach(inp => {
        const matId = inp.dataset.materia;
        const nivId = inp.dataset.nivel;
        if (matId && nivId) {
            inputsMap[`${matId}_${nivId}`] = inp.value.trim();
        }
    });

    const periodos = datosKardexActual.periodos || [];
    const periodosData = [];
    let sumGlobal = 0, countGlobal = 0;

    periodos.forEach((p, idx) => {
        const rows = [];
        let sumPeriodo = 0, countPeriodo = 0;

        (p.materias || []).forEach(m => {
            const isEquiv = m.es_equivalencia === true;
            let califVal = (m.calificacion !== null && m.calificacion !== undefined) ? String(m.calificacion) : '';

            const key = `${m.idMateria}_${p.idNivel}`;
            if (inputsMap[key] !== undefined) {
                califVal = inputsMap[key];
            }

            if (!isEquiv && califVal !== '' && !isNaN(califVal)) {
                const num = parseFloat(califVal);
                sumPeriodo += num;
                countPeriodo++;
                sumGlobal += num;
                countGlobal++;
            }

            rows.push({
                materia: m.nombreMateria,
                isEquiv: isEquiv,
                calificacion: califVal !== '' ? califVal : '—',
                esReprobatoria: !isEquiv && califVal !== '' && !isNaN(califVal) && parseFloat(califVal) < 6.0
            });
        });

        const promPeriodo = countPeriodo > 0 
            ? (sumPeriodo / countPeriodo).toFixed(1) 
            : (p.promedio !== null && p.promedio !== undefined ? String(p.promedio) : '—');

        periodosData.push({
            titulo: p.nombrePeriodo || `PERIODO ${idx + 1}`,
            promedio: promPeriodo,
            promReprobatorio: promPeriodo !== '—' && !isNaN(promPeriodo) && parseFloat(promPeriodo) < 6.0,
            materias: rows
        });
    });

    const promFinal = countGlobal > 0 
        ? (sumGlobal / countGlobal).toFixed(1) 
        : (datosKardexActual.promedioGeneral !== null && datosKardexActual.promedioGeneral !== undefined ? String(datosKardexActual.promedioGeneral) : '—');

    function renderTablaPeriodo(p, is5to, is6to) {
        if (!p) return '';
        let filasHtml = '';

        p.materias.forEach(m => {
            if (m.isEquiv) {
                filasHtml += `
                    <tr>
                        <td class="mat">${m.materia}</td>
                        <td class="c rp-equiv">EQUIV.</td>
                    </tr>`;
            } else {
                filasHtml += `
                    <tr>
                        <td class="mat">${m.materia}</td>
                        <td class="n ${m.esReprobatoria ? 'rp-fail' : ''}">${m.calificacion}</td>
                    </tr>`;
            }
        });

        let footerExtra = '';
        if (is5to) {
            const finalFail = promFinal !== '—' && !isNaN(promFinal) && parseFloat(promFinal) < 6.0;
            footerExtra = `
                <tr class="gran rp-gran">
                    <td class="lbl">Promedio final</td>
                    <td class="n ${finalFail ? 'rp-fail' : ''}" style="font-size: 9pt;">${promFinal}</td>
                </tr>`;
        } else if (is6to) {
            // Fila vacía para equilibrar visualmente con la columna izquierda (promedio final)
            footerExtra = `
                <tr class="kx-spacer">
                    <td colspan="2"></td>
                </tr>`;
        }

        return `
            <div class="kx-periodo">
                <div class="rp-section">${p.titulo}</div>
                <table class="rp-table kx-table">
                    <thead>
                        <tr>
                            <th class="l">Materia</th>
                            <th style="width: 19mm;">${isBti ? 'Calificación final' : 'Evaluación obtenida'}</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${filasHtml}
                        <tr class="tot">
                            <td class="lbl">Promedio</td>
                            <td class="n ${p.promReprobatorio ? 'rp-fail' : ''}">${p.promedio}</td>
                        </tr>
                        ${footerExtra}
                    </tbody>
                </table>
            </div>`;
    }

    const colIzqHtml = `
        <div class="kx-col">
            ${renderTablaPeriodo(periodosData[0], false, false)}
            ${renderTablaPeriodo(periodosData[2], false, false)}
            ${renderTablaPeriodo(periodosData[4], true, false)}
        </div>`;

    const colDerHtml = `
        <div class="kx-col">
            ${renderTablaPeriodo(periodosData[1], false, false)}
            ${renderTablaPeriodo(periodosData[3], false, false)}
            ${renderTablaPeriodo(periodosData[5], false, true)}
        </div>`;

    const fechaHoy = new Date();
    const meses = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
    const fechaTexto = `TEZIUTLÁN PUEBLA A ${fechaHoy.getDate()} DE ${meses[fechaHoy.getMonth()]} DE ${fechaHoy.getFullYear()}`;

    let signaturesHtml = '';
    if (isBti) {
        signaturesHtml = `
            <div class="kx-firmas">
                ${RP.firmas([{ nombre: 'Ing. Fausto Leyva Flores', rol: 'Director' }], '9mm')}
                <div class="kx-fecha">${fechaTexto}</div>
            </div>`;
    }

    const win = window.open('', '', 'height=850,width=850');
    if (!win) {
        Swal.fire({
            icon: 'warning',
            title: 'Ventana emergente bloqueada',
            text: 'Por favor, habilite las ventanas emergentes en su navegador para imprimir el kárdex.',
            confirmButtonColor: '#26687B'
        });
        return;
    }

    const cuerpoKardex = `
        <style>
            .kx-intro { font-size: 7.8pt; color: #4c5d60; margin-bottom: 2mm; }
            .kx-intro strong { color: #12393f; }
            .kx-cols { display: flex; gap: 5mm; align-items: flex-start; }
            .kx-col { flex: 1; min-width: 0; }
            .kx-periodo { margin-bottom: 2.4mm; }
            .kx-table th, .kx-table td { padding: .55mm 1.8mm; }
            .kx-table thead th { padding: .9mm 1.8mm; }
            .kx-table td.mat { font-size: 6.9pt; }
            .kx-spacer td { border: none !important; background: transparent !important; height: 5mm; }
            .kx-firmas { display: flex; align-items: flex-end; justify-content: space-between; gap: 10mm; margin-top: 1mm; }
            .kx-firmas .rp-firmas { width: 75mm; }
            .kx-fecha { font-size: 7.4pt; font-weight: bold; letter-spacing: .6px; color: #12393f; border: .6pt solid #b9c9cc; border-left: 1.4mm solid #c9a24b; background: #fbf8ef; padding: 1.6mm 3mm; }
        </style>

        ${RP.head({
            institucion: 'Bachillerato Interamericano',
            titulo: 'Kárdex de calificaciones',
            lineas: ['Avenida Benito Juárez 901, Colonia Centro, Teziutlán, Puebla · Tel. 231-312-3979'],
            tag: cctClave.replace('CLAVE CT: ', 'CCT '),
            tagSub: isBti ? 'Bachillerato Tecnológico' : 'Bachillerato General'
        })}

        <div class="kx-intro">
            La Dirección de la escuela <strong>${cctNombre}</strong> reporta las siguientes calificaciones obtenidas hasta el momento por el alumno(a):
        </div>
        ${RP.meta([{ label: 'Alumno(a)', value: alumnoNombre, lg: true }])}

        <div class="kx-cols">
            ${colIzqHtml}
            ${colDerHtml}
        </div>

        ${signaturesHtml}

        ${RP.foot('¡ Excelencia educativa a su servicio !')}
    `;

    win.document.write(RP.documento(`Kárdex de Calificaciones - ${alumnoNombre}`, cuerpoKardex, { margin: '8mm 10mm 7mm 10mm' }));
    win.document.close();
    win.focus();
    setTimeout(() => {
        win.print();
    }, 450);
};

        window.imprimirBoletaBTISemestre = function(data, idNivelSemestre, reportesCounts, opciones = {}) {
        const al = data.alumno;
        const repCounts = reportesCounts || { 1: 0, 2: 0, 3: 0 };
        const totalReportes = (repCounts[1] || 0) + (repCounts[2] || 0) + (repCounts[3] || 0);

        const incAsist = opciones.incluirAsistencias !== false;
        const incRep = opciones.incluirReportes !== false;

        let boxReportesHtml = '';
        if (incRep) {
            if (totalReportes > 0) {
                boxReportesHtml = `
                    <div class="rp-box bo-side-box">
                        <div class="rp-box-h">Conducta y disciplina</div>
                        <div class="rp-box-b rp-stat">
                            <div class="v sm rp-fail">${totalReportes} reporte${totalReportes > 1 ? 's' : ''}</div>
                            <div class="s" style="color: #9a2a1f;">Acumulado${totalReportes > 1 ? 's' : ''} en el semestre</div>
                            <div class="bo-rep-parciales">
                                <span class="rp-chip ${(repCounts[1] || 0) > 0 ? 'alert' : ''}">P1: ${repCounts[1] || 0}</span>
                                <span class="rp-chip ${(repCounts[2] || 0) > 0 ? 'alert' : ''}">P2: ${repCounts[2] || 0}</span>
                                <span class="rp-chip ${(repCounts[3] || 0) > 0 ? 'alert' : ''}">P3: ${repCounts[3] || 0}</span>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                boxReportesHtml = `
                    <div class="rp-box bo-side-box">
                        <div class="rp-box-h">Conducta y disciplina</div>
                        <div class="rp-box-b rp-stat">
                            <div class="v sm">0</div>
                            <div class="s">Reportes de indisciplina</div>
                        </div>
                    </div>
                `;
            }
        }

        const periodos = data.periodos || [];
        const p = periodos.find(x => x.idNivel === idNivelSemestre);
        const materias = p ? p.materias || [] : [];

        const semestresNombres = {
            7: 'PRIMER',
            8: 'SEGUNDO',
            9: 'TERCER',
            10: 'CUARTO',
            11: 'QUINTO',
            12: 'SEXTO'
        };
        const semestreNombreLargo = semestresNombres[idNivelSemestre] || 'SEMESTRE';

        let filasMateriasHtml = '';
        let p1Vals = []; let p2Vals = []; let p3Vals = []; let semVals = []; let extVals = []; let finalVals = [];
        let totalAsist = 0; let totalTotAsist = 0;
        let allAsistVals = [];
        let allTotVals = [];

        const formatNota = (val, isExtra = false) => {
            if (val === null || val === undefined || isNaN(val)) {
                return {
                    text: isExtra ? '0.0' : '—',
                    style: ''
                };
            }
            const num = parseFloat(val);
            const text = num.toFixed(1);
            const style = num <= 5.0 ? 'color: #b42318; font-weight: bold;' : '';
            return { text, style, num };
        };

        materias.forEach(m => {
            const isEquiv = m.es_equivalencia === true;
            if (isEquiv) {
                filasMateriasHtml += `
                    <tr>
                        <td class="mat">${m.nombreMateria}</td>
                        <td colspan="5" class="c rp-equiv">EQUIVALENCIA</td>
                        <td class="n fin">—</td>
                    </tr>
                `;
                return;
            }

            const p1 = (m.parcial1 !== null && m.parcial1 !== undefined && m.parcial1 !== '') ? parseFloat(m.parcial1) : null;
            const p2 = (m.parcial2 !== null && m.parcial2 !== undefined && m.parcial2 !== '') ? parseFloat(m.parcial2) : null;
            const p3 = (m.parcial3 !== null && m.parcial3 !== undefined && m.parcial3 !== '') ? parseFloat(m.parcial3) : null;
            const sem = (m.semestral !== null && m.semestral !== undefined && m.semestral !== '') ? parseFloat(m.semestral) : null;
            const ext = (m.extraordinario !== null && m.extraordinario !== undefined && m.extraordinario !== '') ? parseFloat(m.extraordinario) : null;
            const finalVal = (m.calificacion !== null && m.calificacion !== undefined && m.calificacion !== '') ? parseFloat(m.calificacion) : null;

            const parseVal = (v, arr) => { if (v !== null && !isNaN(v)) arr.push(v); };
            parseVal(p1, p1Vals);
            parseVal(p2, p2Vals);
            parseVal(p3, p3Vals);
            parseVal(sem, semVals);
            parseVal(ext, extVals);
            parseVal(finalVal, finalVals);

            if (m.asistencias !== null && m.asistencias !== undefined) {
                const a = parseInt(m.asistencias) || 0;
                totalAsist += a;
                allAsistVals.push(a);
            }
            if (m.total_asistencias !== null && m.total_asistencias !== undefined) {
                const t = parseInt(m.total_asistencias) || 0;
                totalTotAsist += t;
                allTotVals.push(t);
            }

            const c1 = formatNota(p1);
            const c2 = formatNota(p2);
            const c3 = formatNota(p3);
            const cSem = formatNota(sem);
            const cExt = formatNota(ext, true);
            const cFinal = formatNota(finalVal);

            filasMateriasHtml += `
                <tr>
                    <td class="mat">${m.nombreMateria}</td>
                    <td class="n" style="${c1.style}">${c1.text}</td>
                    <td class="n" style="${c2.style}">${c2.text}</td>
                    <td class="n" style="${c3.style}">${c3.text}</td>
                    <td class="n" style="${cSem.style}">${cSem.text}</td>
                    <td class="n bo-sep" style="${cExt.style}">${cExt.text}</td>
                    <td class="n fin bo-sep" style="${cFinal.style}">${cFinal.text}</td>
                </tr>
            `;
        });

        const getAvg = (arr) => arr.length > 0 ? (arr.reduce((a, b) => a + b, 0) / arr.length).toFixed(1) : '—';

        const p1Avg = getAvg(p1Vals);
        const p2Avg = getAvg(p2Vals);
        const p3Avg = getAvg(p3Vals);
        const semAvg = getAvg(semVals);
        const extAvg = getAvg(extVals);
        const finalAvg = getAvg(finalVals);

        const formatAvgStyle = (avgStr, defaultColor = '#12393f') => {
            if (avgStr === '—' || isNaN(avgStr)) return `color: ${defaultColor};`;
            const n = parseFloat(avgStr);
            return n <= 5.0 ? 'color: #b42318; font-weight: bold;' : `color: ${defaultColor};`;
        };

        const p1AvgStyle = formatAvgStyle(p1Avg);
        const p2AvgStyle = formatAvgStyle(p2Avg);
        const p3AvgStyle = formatAvgStyle(p3Avg);
        const semAvgStyle = formatAvgStyle(semAvg);
        const extAvgStyle = formatAvgStyle(extAvg);
        const finalAvgStyle = formatAvgStyle(finalAvg, '#12393f');

        const badgeFinalColor = (finalAvg !== '—' && !isNaN(finalAvg) && parseFloat(finalAvg) <= 5.0) ? '#b42318' : '#12393f';

        const allSameTot = allTotVals.length > 0 && allTotVals.every(v => v === allTotVals[0]);
        const allSameAsist = allAsistVals.length > 0 && allAsistVals.every(v => v === allAsistVals[0]);

        const displayAsist = (allSameTot && allSameAsist && allAsistVals.length > 0) ? allAsistVals[0] : totalAsist;
        const displayTotAsist = (allSameTot && allTotVals.length > 0) ? allTotVals[0] : totalTotAsist;
        const pctAsist = displayTotAsist > 0 ? Math.round((displayAsist / displayTotAsist) * 100) : 0;

        let badgesHtml = '';
        if (incAsist) {
            badgesHtml = `
                <div class="bo-stats">
                    <div class="rp-box">
                        <div class="rp-box-h">Asistencias</div>
                        <div class="rp-box-b rp-stat">
                            <div class="v sm">${displayAsist} / ${displayTotAsist}</div>
                            <div class="s">${pctAsist}% asistencia</div>
                            <div class="h">(Detalle en hoja 2)</div>
                        </div>
                    </div>
                    <div class="rp-box">
                        <div class="rp-box-h">Promedio final</div>
                        <div class="rp-box-b rp-stat">
                            <div class="v" style="color: ${badgeFinalColor};">${finalAvg}</div>
                            <div class="s">Del semestre</div>
                        </div>
                    </div>
                </div>
            `;
        } else {
            badgesHtml = `
                <div class="rp-box bo-side-box">
                    <div class="rp-box-h">Promedio final del semestre</div>
                    <div class="rp-box-b rp-stat">
                        <div class="v" style="font-size: 18pt; color: ${badgeFinalColor};">${finalAvg}</div>
                    </div>
                </div>
            `;
        }

        // Datos de fechas de periodos de evaluación configuradas para el grupo
        const pe = data.periodosEvaluacion || opciones.periodosEvaluacion || {};
        const p1RangoText = pe.p1_rango || (pe.p1_inicio && pe.p1_fin ? `${pe.p1_inicio} al ${pe.p1_fin}` : '01/09/2025 al 10/10/2025');
        const p2RangoText = pe.p2_rango || (pe.p2_inicio && pe.p2_fin ? `${pe.p2_inicio} al ${pe.p2_fin}` : '13/10/2025 al 21/11/2025');
        const p3RangoText = pe.p3_rango || (pe.p3_inicio && pe.p3_fin ? `${pe.p3_inicio} al ${pe.p3_fin}` : '24/11/2025 al 16/01/2026');

        const incidenciasAsistencia = opciones.incidenciasAsistencia || {
            1: {
                inicio: pe.p1_inicio || '01/09/2025',
                fin: pe.p1_fin || '10/10/2025',
                asistencias: 48,
                total: 50,
                pct: 96,
                faltas: ['12 de Septiembre, 2025', '25 de Septiembre, 2025'],
                retardos: ['18 de Septiembre, 2025'],
                justificantes: [{ fecha: '29 de Septiembre, 2025', motivo: 'Cita Médica' }]
            },
            2: {
                inicio: pe.p2_inicio || '13/10/2025',
                fin: pe.p2_fin || '21/11/2025',
                asistencias: 50,
                total: 50,
                pct: 100,
                faltas: [],
                retardos: [],
                justificantes: []
            },
            3: {
                inicio: pe.p3_inicio || '24/11/2025',
                fin: pe.p3_fin || '16/01/2026',
                asistencias: 47,
                total: 50,
                pct: 94,
                faltas: ['05 de Diciembre, 2025'],
                retardos: ['02 de Diciembre, 2025', '10 de Diciembre, 2025'],
                justificantes: []
            }
        };

        const renderListaFechas = (items, tipo) => {
            if (!items || items.length === 0) {
                const txt = (tipo === 'retardos' || tipo === 'justificantes') ? `Sin ${tipo} registrados` : `Sin ${tipo} registradas`;
                return `<div class="bo-vacio">${txt}</div>`;
            }
            return items.map(item => {
                if (typeof item === 'object') {
                    return `<div class="bo-fecha">${item.fecha} <span>(${item.motivo})</span></div>`;
                }
                return `<div class="bo-fecha">${item}</div>`;
            }).join('');
        };

        const totFaltas = (incidenciasAsistencia[1]?.faltas?.length || 0) + (incidenciasAsistencia[2]?.faltas?.length || 0) + (incidenciasAsistencia[3]?.faltas?.length || 0);
        const totRetardos = (incidenciasAsistencia[1]?.retardos?.length || 0) + (incidenciasAsistencia[2]?.retardos?.length || 0) + (incidenciasAsistencia[3]?.retardos?.length || 0);
        const totJustif = (incidenciasAsistencia[1]?.justificantes?.length || 0) + (incidenciasAsistencia[2]?.justificantes?.length || 0) + (incidenciasAsistencia[3]?.justificantes?.length || 0);
        const totAsist = (incidenciasAsistencia[1]?.asistencias || 0) + (incidenciasAsistencia[2]?.asistencias || 0) + (incidenciasAsistencia[3]?.asistencias || 0);
        const totTotalAsist = (incidenciasAsistencia[1]?.total || 0) + (incidenciasAsistencia[2]?.total || 0) + (incidenciasAsistencia[3]?.total || 0);
        const pctGlobal = totTotalAsist > 0 ? ((totAsist / totTotalAsist) * 100).toFixed(1) : '100.0';

        const nombreAlumno = `${al.apPaterno || ''} ${al.apMaterno || ''} ${al.nombre || ''}`;
        const fechaLarga = new Date().toLocaleDateString('es-MX', {day: 'numeric', month: 'long', year: 'numeric'}).toUpperCase();

        // Columna de un parcial en el anexo de asistencia (hoja 2)
        const renderParcialAnexo = (n, titulo, iniDef, finDef) => {
            const inc = incidenciasAsistencia[n] || {};
            const bloque = (label, lista, tipo) => `
                <div class="bo-inc">
                    <div class="bo-inc-h">
                        <span>${label}</span>
                        <span class="rp-chip ${(lista?.length || 0) > 0 && tipo !== 'justificantes' ? 'alert' : ''}">${lista?.length || 0}</span>
                    </div>
                    ${renderListaFechas(lista, tipo)}
                </div>`;
            return `
                <div class="bo-parcial">
                    <div class="bo-parcial-h">
                        ${titulo}
                        <div>${inc.inicio || iniDef} al ${inc.fin || finDef}</div>
                    </div>
                    <div class="bo-parcial-asist">
                        <span class="rp-label">Asistencia del periodo</span>
                        <strong>${inc.asistencias || 0} / ${inc.total || 0}</strong>
                        <span class="bo-pct">${inc.pct || 0}%</span>
                    </div>
                    ${bloque('Faltas', inc.faltas, 'faltas')}
                    ${bloque('Retardos', inc.retardos, 'retardos')}
                    ${bloque('Justificantes', inc.justificantes, 'justificantes')}
                </div>`;
        };

        const cuerpoBoleta = `
            <style>
                .bo-periodos { display: flex; border: .6pt solid #b9c9cc; background: #f4f8f8; margin-bottom: 3.5mm; }
                .bo-periodos > div { flex: 1; padding: 1.4mm 2.5mm; border-left: .6pt solid #b9c9cc; }
                .bo-periodos > div:first-child { border-left: 1.4mm solid #c9a24b; }
                .bo-periodos .v { display: block; margin-top: .5mm; font-size: 7.8pt; font-weight: bold; color: #12393f; }
                .bo-main { display: flex; gap: 4mm; align-items: flex-start; }
                .bo-main > .bo-grades { flex: 1; min-width: 0; }
                .bo-main > .bo-side { width: 56mm; display: flex; flex-direction: column; gap: 3mm; }
                .bo-table td { padding: 1.6mm 1.6mm; }
                .bo-table td.mat { font-weight: bold; font-size: 7pt; }
                .bo-table td.n { font-size: 8.4pt; }
                .bo-table .bo-sep { border-left: 1pt solid #26687B !important; }
                .bo-stats { display: flex; gap: 2mm; }
                .bo-stats .rp-box { flex: 1; }
                .bo-rep-parciales { display: flex; justify-content: center; gap: 1.2mm; margin-top: 1.6mm; }
                .bo-fecha-doc { font-size: 6.8pt; font-weight: bold; letter-spacing: .5px; color: #12393f; text-align: center; border: .6pt solid #b9c9cc; border-left: 1.4mm solid #c9a24b; background: #fbf8ef; padding: 1.4mm 2mm; }
                .bo-obs { margin-top: 5mm; }
                .bo-obs-area { border: 1pt solid #26687B; border-top: none; height: 16mm; background: repeating-linear-gradient(to bottom, transparent 0, transparent 5.2mm, #dfe8ea 5.2mm, #dfe8ea 5.4mm); }
                .bo-anexo-grid { display: flex; border: 1pt solid #26687B; }
                .bo-parcial { flex: 1; border-left: .6pt solid #a9bcc0; display: flex; flex-direction: column; }
                .bo-parcial:first-child { border-left: none; }
                .bo-parcial-h { background: #dceaec; color: #12393f; text-align: center; font-size: 8.4pt; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; padding: 1.8mm 1mm; border-bottom: .6pt solid #a9bcc0; }
                .bo-parcial-h div { font-size: 6.4pt; font-weight: bold; letter-spacing: .4px; color: #4c6266; margin-top: .4mm; }
                .bo-parcial-asist { text-align: center; padding: 2mm 1mm; border-bottom: .6pt solid #a9bcc0; background: #f4f8f8; }
                .bo-parcial-asist strong { display: block; font-size: 12pt; color: #12393f; margin-top: .5mm; }
                .bo-pct { font-size: 6.6pt; font-weight: bold; color: #26687B; }
                .bo-inc { padding: 2mm 3mm; border-bottom: .5pt solid #e1eaec; }
                .bo-inc:last-child { border-bottom: none; flex: 1; }
                .bo-inc-h { display: flex; justify-content: space-between; align-items: center; font-size: 6.8pt; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; color: #12393f; margin-bottom: 1mm; }
                .bo-fecha { font-size: 7.2pt; padding-left: 2.6mm; position: relative; line-height: 1.5; }
                .bo-fecha::before { content: ""; position: absolute; left: 0; top: 1.5mm; width: 1.1mm; height: 1.1mm; background: #c9a24b; }
                .bo-fecha span { font-size: 6.6pt; color: #5b6b6e; }
                .bo-vacio { font-size: 6.8pt; color: #7a8a8d; font-style: italic; }
            </style>

            ${RP.head({
                institucion: 'Dirección General de Educación Tecnológica Industrial y de Servicios',
                titulo: `Boleta de calificaciones del ${semestreNombreLargo} semestre`,
                lineas: ['Educación Media Superior · Ciclo escolar 2025-2026'],
                tag: al.claveCentroTrabajo || '21PCT0073R',
                tagSub: 'Ciclo 2025-2026'
            })}

            ${RP.meta([
                { label: 'Datos del alumno(a), Apellido paterno, materno y nombre', value: nombreAlumno, flex: 1, lg: true }
            ])}
            ${RP.meta([
                { label: 'Datos de la escuela', value: al.nombreCentroTrabajo || 'BACHILLERATO TECNOLÓGICO INTERAMERICANO', flex: 3 },
                { label: 'Grupo', value: al.nombreGrupoTexto || '—', flex: .8 },
                { label: 'Turno', value: al.modalidadHorario || 'MATUTINO', flex: 1.1 },
                { label: 'CCT', value: al.claveCentroTrabajo || '21PCT0073R', flex: 1.1 }
            ])}

            <div class="bo-periodos">
                <div><span class="rp-label">Periodos de evaluación parcial</span><span class="v">Fechas</span></div>
                <div><span class="rp-label">1er. parcial</span><span class="v">${p1RangoText}</span></div>
                <div><span class="rp-label">2do. parcial</span><span class="v">${p2RangoText}</span></div>
                <div><span class="rp-label">3er. parcial</span><span class="v">${p3RangoText}</span></div>
            </div>

            <div class="bo-main">
                <div class="bo-grades">
                    <table class="rp-table bo-table">
                        <thead>
                            <tr>
                                <th rowspan="2" class="l">Asignaturas / áreas</th>
                                <th colspan="4">Periodos de evaluación ordinaria</th>
                                <th rowspan="2" class="bo-sep" style="width: 15mm;">Extra&shy;ordinario</th>
                                <th rowspan="2" class="bo-sep" style="width: 16mm;">Promedio final / materia</th>
                            </tr>
                            <tr class="sub">
                                <th style="width: 11mm;">1er. parcial</th>
                                <th style="width: 11mm;">2do. parcial</th>
                                <th style="width: 11mm;">3er. parcial</th>
                                <th style="width: 14mm;">Semestral</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${filasMateriasHtml}
                            <tr class="tot">
                                <td class="lbl">Promedio</td>
                                <td class="n" style="${p1AvgStyle}">${p1Avg}</td>
                                <td class="n" style="${p2AvgStyle}">${p2Avg}</td>
                                <td class="n" style="${p3AvgStyle}">${p3Avg}</td>
                                <td class="n" style="${semAvgStyle}">${semAvg}</td>
                                <td class="n bo-sep" style="${extAvgStyle}">${extAvg}</td>
                                <td class="n bo-sep" style="background: #f3e4bb !important; ${finalAvgStyle}">${finalAvg}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="bo-side">
                    ${badgesHtml}
                    ${boxReportesHtml}
                    <div style="margin-top: 2mm;">
                        ${RP.firmas([{ nombre: 'Ing. Fausto Leyva Flores', rol: 'Director' }], '12mm')}
                    </div>
                    <div class="bo-fecha-doc">TEZIUTLÁN PUEBLA A ${fechaLarga}</div>
                </div>
            </div>

            <div class="bo-obs">
                <table class="rp-table" style="border-bottom: none;">
                    <thead><tr><th>Observaciones o recomendaciones de la docente o del docente</th></tr></thead>
                </table>
                <div class="bo-obs-area"></div>
            </div>

            ${incAsist ? `
                <div class="rp-page-break"></div>

                ${RP.head({
                    titulo: 'Reporte detallado de asistencia, puntualidad e incidencias',
                    lineas: [`Anexo a la boleta de calificaciones — ${semestreNombreLargo} semestre`],
                    tag: 'Hoja 2 · Anexo',
                    tagSub: 'Ciclo 2025-2026'
                })}

                ${RP.meta([
                    { label: 'Alumno', value: nombreAlumno, flex: 2.6 },
                    { label: 'Matrícula', value: al.numeroControl || al.idAlumno || '—', flex: 1 },
                    { label: 'Grupo', value: `${al.nombreGrupoTexto || '—'} (${al.modalidadHorario || 'MATUTINO'})`, flex: 1.3 },
                    { label: 'Ciclo', value: '2025-2026', flex: .9 }
                ])}

                <table class="rp-table" style="border-bottom: none;">
                    <thead><tr><th>Desglose de incidencias por periodo parcial</th></tr></thead>
                </table>
                <div class="bo-anexo-grid">
                    ${renderParcialAnexo(1, '1er. parcial', '01/09/2025', '10/10/2025')}
                    ${renderParcialAnexo(2, '2do. parcial', '13/10/2025', '21/11/2025')}
                    ${renderParcialAnexo(3, '3er. parcial', '24/11/2025', '16/01/2026')}
                </div>

                <div style="margin-top: 18mm; padding: 0 6mm;">
                    ${RP.firmas([
                        { rol: 'Prefectura / Control escolar', detalle: 'Responsable de asistencia' },
                        'sello:Sello escolar',
                        { rol: 'Padre de familia o tutor', detalle: 'Firma de enterado' }
                    ], '14mm')}
                </div>

                ${RP.foot('Documento oficial anexo', `Teziutlán, Puebla a ${fechaLarga}`)}
            ` : ''}
        `;

        const win = window.open('', '', 'height=850,width=1100');
        win.document.write(RP.documento(`Boleta de Calificaciones - ${semestreNombreLargo} Semestre - ${al.nombre}`, cuerpoBoleta, { margin: '10mm 11mm' }));
        win.document.close();
        win.focus();
        setTimeout(() => {
            win.print();
            win.close();
        }, 400);
    };

    function promptBoletaSemestreDespuesKardex() {
        if (!datosKardexActual) {
            Swal.fire({
                icon: 'warning',
                title: 'No hay datos cargados',
                text: 'Por favor, busque un alumno primero.',
                confirmButtonColor: '#26687B'
            });
            return;
        }
        
        Swal.fire({
            title: 'Seleccionar Semestre',
            text: 'Seleccione el semestre (del 1 al 6) para generar la boleta:',
            input: 'select',
            inputOptions: {
                '7': '1° Semestre',
                '8': '2° Semestre',
                '9': '3° Semestre',
                '10': '4° Semestre',
                '11': '5° Semestre',
                '12': '6° Semestre'
            },
            inputPlaceholder: 'Seleccione semestre...',
            showCancelButton: true,
            confirmButtonColor: 'rgb(38, 104, 123)',
            cancelButtonColor: '#cbd5e1',
            confirmButtonText: 'Generar Boleta',
            cancelButtonText: 'Cancelar',
            inputValidator: (value) => {
                if (!value) {
                     return 'Debe seleccionar un semestre';
                }
            }
        }).then((semResult) => {
            if (semResult.isConfirmed) {
                const idNivelSemestre = parseInt(semResult.value);
                const alId = datosKardexActual.alumno.idAlumno;
                Swal.fire({
                    title: 'Preparando boleta...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/alumnos/${alId}/reportes-conteo`)
                    .then(r => r.json())
                    .then(resp => {
                        Swal.close();
                        const counts = resp.success && resp.counts ? resp.counts : { 1: 0, 2: 0, 3: 0 };
                        imprimirBoletaBTISemestre(datosKardexActual, idNivelSemestre, counts);
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.close();
                        imprimirBoletaBTISemestre(datosKardexActual, idNivelSemestre, { 1: 0, 2: 0, 3: 0 });
                    });
            }
        });
    }

    function generarBoletaBTIDesdeSelect() {
        if (!datosKardexActual) {
            Swal.fire({
                icon: 'warning',
                title: 'No hay datos cargados',
                text: 'Por favor, busque un alumno primero.',
                confirmButtonColor: '#26687B'
            });
            return;
        }
        const select = document.getElementById('boletaSemestreSelect');
        const semVal = select.value;
        
        const mapping = {
            "1er Semestre": 7,
            "2° Semestre": 8,
            "3er Semestre": 9,
            "4° Semestre": 10,
            "5° Semestre": 11,
            "6° Semestre": 12
        };
        const idNivel = mapping[semVal] || 9;

        const alId = datosKardexActual.alumno.idAlumno;
        Swal.fire({
            title: 'Preparando boleta...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const chkAsist = document.getElementById('chkBoletaIncluirAsistencias');
        const chkRep = document.getElementById('chkBoletaIncluirReportes');
        const opciones = {
            incluirAsistencias: chkAsist ? chkAsist.checked : true,
            incluirReportes: chkRep ? chkRep.checked : true
        };

        fetch(`/alumnos/${alId}/reportes-conteo`)
            .then(r => r.json())
            .then(resp => {
                Swal.close();
                const counts = resp.success && resp.counts ? resp.counts : { 1: 0, 2: 0, 3: 0 };
                imprimirBoletaBTISemestre(datosKardexActual, idNivel, counts, opciones);
            })
            .catch(err => {
                console.error(err);
                Swal.close();
                imprimirBoletaBTISemestre(datosKardexActual, idNivel, { 1: 0, 2: 0, 3: 0 }, opciones);
            });
    }

    function mostrarModalKardexManual() {
        if (!datosKardexActual) {
            Swal.fire({
                icon: 'warning',
                title: 'No hay datos cargados',
                text: 'Por favor, busque un alumno primero.',
                confirmButtonColor: '#26687B'
            });
            return;
        }
        mostrarKardexConDatos(datosKardexActual);
    }

    // ==========================================
    // AUTOCOMPLETE Y FILTRADO - FORMATO EXTRAORDINARIO
    // ==========================================

    function buscarGruposExtraordinario(query = '') {
        const div = document.getElementById('extraordinario-grupo-sugerencia');
        if (!div) return;

        const grupos = window.gruposDb || [];
        const normQ = (query || '').normalize("NFD").replace(/[̀-ͯ]/g, "").toLowerCase().trim();
        const targetCentroId = cctSeleccionado === 'BTI' ? 2 : (cctSeleccionado === 'BGNE' ? 3 : null);

        let filtered = grupos.filter(g => {
            if (targetCentroId) {
                const cid = g.id_centroTrabajo ?? g.idCentroTrabajo ?? g.id_centro_trabajo;
                if (cid && cid != targetCentroId) return false;
            }
            if (!normQ) return true;
            const claveNorm = (g.clave || '').normalize("NFD").replace(/[̀-ͯ]/g, "").toLowerCase();
            const modNorm = (g.modalidadHorario || '').normalize("NFD").replace(/[̀-ͯ]/g, "").toLowerCase();
            return claveNorm.includes(normQ) || modNorm.includes(normQ);
        });

        div.innerHTML = '';
        div.style.display = 'block';

        if (filtered.length === 0) {
            div.innerHTML = '<div class="p-2 text-muted fs-8 text-center">No se encontraron grupos</div>';
            return;
        }

        filtered.slice(0, 15).forEach(g => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'list-group-item list-group-item-action py-2 text-start d-flex justify-content-between align-items-center';

            const cctName = (g.id_centroTrabajo == 2) ? 'BTI' : ((g.id_centroTrabajo == 3) ? 'BGNE' : '');
            btn.innerHTML = `
                <div>
                    <span class="fw-bold d-block text-slate-800 fs-8">${g.clave}</span>
                    <small class="text-muted fs-9">${g.modalidadHorario || 'General'}</small>
                </div>
                ${cctName ? `<span class="badge bg-primary-subtle text-primary fs-9">${cctName}</span>` : ''}
            `;

            btn.onclick = (e) => {
                e.preventDefault();
                seleccionarGrupoExtraordinario(g);
                div.style.display = 'none';
            };
            div.appendChild(btn);
        });
    }

    function seleccionarGrupoExtraordinario(grupo) {
        const input = document.getElementById('extraordinarioGrupoInput');
        if (input) input.value = grupo.clave;

        // Auto-seleccionar semestre/trimestre en base al nivel académico del grupo
        if (grupo.id_nivel_academico) {
            const semNum = periodoDeNivel(grupo.id_nivel_academico);
            if (semNum) {
                const semSelect = document.getElementById('extraordinarioSemestreSelect');
                if (semSelect) {
                    semSelect.value = String(semNum);
                    onExtraordinarioSemestreChange();
                }
            }
        }

        // Sugerir docente si hay asignaciones en tb_horarios
        if (window.horariosDb && window.horariosDb.length > 0) {
            const asignacion = window.horariosDb.find(h => h.id_grupo == grupo.id);
            if (asignacion && asignacion.id_docente) {
                const docInput = document.getElementById('extraordinarioDocenteInput');
                if (docInput && !docInput.value.trim()) {
                    const doc = (window.docentesDb || []).find(d => (d.idDocente || d.id) == asignacion.id_docente);
                    if (doc) {
                        const nom = `${doc.nombreDocente || doc.nombre || ''} ${doc.apPaternoDocente || doc.apPaterno || ''} ${doc.apMaternoDocente || doc.apMaterno || ''}`.trim();
                        docInput.value = nom;
                    }
                }
            }
        }

        actualizarExtraordinarioPreview();
    }

    /*
     * Periodos por plantel. En tb_nivel_academico:
     *   BGNE → trimestres, id_nivel_academico 1–6
     *   BTI  → semestres,  id_nivel_academico 7–12
     * El select guarda el número de periodo (1–6); nivelDePeriodo() lo traduce al id del catálogo.
     */
    const ORDINALES_PERIODO = ['1er', '2do', '3er', '4to', '5to', '6to'];
    const periodoExtraordinario = () => (cctSeleccionado === 'BGNE')
        ? { nombre: 'Trimestre', offset: 0 }
        : { nombre: 'Semestre', offset: 6 };
    const nivelDePeriodo = (num) => parseInt(num) + periodoExtraordinario().offset;
    const periodoDeNivel = (nivel) => {
        const n = parseInt(nivel) - periodoExtraordinario().offset;
        return n >= 1 && n <= 6 ? n : null;
    };

    function configurarPeriodoExtraordinario(cct) {
        const select = document.getElementById('extraordinarioSemestreSelect');
        const label = document.getElementById('lbl-extraordinario-semestre-label');
        if (!select) return;
        const { nombre } = periodoExtraordinario();
        if (label) label.textContent = nombre;
        select.innerHTML = '<option value="">Selecciona…</option>' +
            ORDINALES_PERIODO.map((o, i) => `<option value="${i + 1}">${o} ${nombre.toLowerCase()}</option>`).join('');

        // La materia elegida pertenecía al plantel anterior
        const mat = document.getElementById('extraordinarioMateriaInput');
        if (mat) mat.value = '';
    }

    function onExtraordinarioSemestreChange() {
        const semSelect = document.getElementById('extraordinarioSemestreSelect');
        const semVal = semSelect ? semSelect.value : '';

        // Si la materia actual no pertenece al nuevo semestre seleccionado, limpiarla
        const matInput = document.getElementById('extraordinarioMateriaInput');
        if (matInput && matInput.value.trim() && semVal) {
            const materias = obtenerMateriasFiltradasExtraordinario();
            const coincide = materias.some(m => (m.nombreMateria || '').toLowerCase().trim() === matInput.value.toLowerCase().trim());
            if (!coincide) {
                matInput.value = '';
            }
        }

        actualizarExtraordinarioPreview();
    }

    function obtenerMateriasFiltradasExtraordinario(query = '') {
        const materias = window.materiasDb || [];
        const semSelect = document.getElementById('extraordinarioSemestreSelect');
        const semVal = semSelect ? semSelect.value : '';
        const normQ = (query || '').normalize("NFD").replace(/[̀-ͯ]/g, "").toLowerCase().trim();

        const targetCentroId = cctSeleccionado === 'BTI' ? 2 : (cctSeleccionado === 'BGNE' ? 3 : null);

        return materias.filter(m => {
            if (targetCentroId) {
                const cid = m.idCentroTrabajo ?? m.id_centro_trabajo ?? m.id_centroTrabajo;
                if (cid && cid != targetCentroId) return false;
            }

            // Solo materias del semestre/trimestre elegido (nivel exacto del plantel)
            if (semVal && parseInt(m.id_nivel_academico) !== nivelDePeriodo(semVal)) return false;

            if (!normQ) return true;
            const nomNorm = (m.nombreMateria || '').normalize("NFD").replace(/[̀-ͯ]/g, "").toLowerCase();
            const claveNorm = (m.clave || '').normalize("NFD").replace(/[̀-ͯ]/g, "").toLowerCase();
            return nomNorm.includes(normQ) || claveNorm.includes(normQ);
        });
    }

    function buscarMateriasExtraordinario(query = '') {
        const div = document.getElementById('extraordinario-materia-sugerencia');
        if (!div) return;

        // Si materiasDb está vacío, intentar cargar diferido
        if (!window.materiasDb || window.materiasDb.length === 0) {
            fetch('/materias/lista?limit=1000')
                .then(r => r.json())
                .then(res => {
                    if (res && res.data) {
                        window.materiasDb = res.data;
                        buscarMateriasExtraordinario(query);
                    }
                }).catch(() => {});
        }

        const semSelect = document.getElementById('extraordinarioSemestreSelect');
        const semVal = semSelect ? semSelect.value : '';
        const filtered = obtenerMateriasFiltradasExtraordinario(query);

        div.innerHTML = '';
        div.style.display = 'block';

        if (!semVal && filtered.length > 0) {
            const notice = document.createElement('div');
            notice.className = 'p-2 bg-light text-muted fs-9 border-bottom text-center';
            notice.innerHTML = `<i class="ti ti-info-circle me-1"></i> Elige un ${periodoExtraordinario().nombre.toLowerCase()} para ver solo sus materias`;
            div.appendChild(notice);
        }

        if (filtered.length === 0) {
            div.innerHTML = `<div class="p-3 text-muted fs-8 text-center">${semVal ? `No hay materias para este ${periodoExtraordinario().nombre.toLowerCase()}` : 'No se encontraron materias'}</div>`;
            return;
        }

        filtered.slice(0, 20).forEach(m => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'list-group-item list-group-item-action py-2 text-start d-flex justify-content-between align-items-center';

            const numPeriodo = periodoDeNivel(m.id_nivel_academico);
            const semBadge = numPeriodo ? `${ORDINALES_PERIODO[numPeriodo - 1]} ${periodoExtraordinario().nombre.slice(0, 4).toLowerCase()}.` : '';

            btn.innerHTML = `
                <div>
                    <span class="fw-semibold d-block text-slate-800 fs-8">${m.nombreMateria}</span>
                    ${m.clave ? `<small class="text-muted fs-9">Clave: ${m.clave}</small>` : ''}
                </div>
                ${semBadge ? `<span class="badge bg-light text-secondary border fs-9">${semBadge}</span>` : ''}
            `;

            btn.onclick = (e) => {
                e.preventDefault();
                seleccionarMateriaExtraordinario(m);
                div.style.display = 'none';
            };
            div.appendChild(btn);
        });
    }

    function seleccionarMateriaExtraordinario(materia) {
        const input = document.getElementById('extraordinarioMateriaInput');
        if (input) input.value = materia.nombreMateria;

        // Auto-seleccionar semestre/trimestre si no estaba seleccionado
        if (materia.id_nivel_academico) {
            const semSelect = document.getElementById('extraordinarioSemestreSelect');
            if (semSelect && !semSelect.value) {
                const num = periodoDeNivel(materia.id_nivel_academico);
                if (num) {
                    semSelect.value = String(num);
                    onExtraordinarioSemestreChange();
                }
            }
        }

        // Sugerir docente de tb_horarios si coincide
        if (window.horariosDb && window.horariosDb.length > 0) {
            const asignacion = window.horariosDb.find(h => h.id_materia == materia.id);
            if (asignacion && asignacion.id_docente) {
                const docInput = document.getElementById('extraordinarioDocenteInput');
                if (docInput && !docInput.value.trim()) {
                    const doc = (window.docentesDb || []).find(d => (d.idDocente || d.id) == asignacion.id_docente);
                    if (doc) {
                        const nom = `${doc.nombreDocente || doc.nombre || ''} ${doc.apPaternoDocente || doc.apPaterno || ''} ${doc.apMaternoDocente || doc.apMaterno || ''}`.trim();
                        docInput.value = nom;
                    }
                }
            }
        }

        actualizarExtraordinarioPreview();
    }

    function buscarDocentesExtraordinario(query = '') {
        const div = document.getElementById('extraordinario-docente-sugerencia');
        if (!div) return;

        const docentes = window.docentesDb || [];
        const normQ = (query || '').normalize("NFD").replace(/[̀-ͯ]/g, "").toLowerCase().trim();

        let filtered = docentes.filter(d => {
            if (!normQ) return true;
            const nom = `${d.nombreDocente || d.nombre || ''} ${d.apPaternoDocente || d.apPaterno || ''} ${d.apMaternoDocente || d.apMaterno || ''}`;
            const nomNorm = nom.normalize("NFD").replace(/[̀-ͯ]/g, "").toLowerCase();
            return nomNorm.includes(normQ);
        });

        div.innerHTML = '';
        div.style.display = 'block';

        if (filtered.length === 0) {
            div.innerHTML = '<div class="p-2 text-muted fs-8 text-center">No se encontraron docentes</div>';
            return;
        }

        filtered.slice(0, 15).forEach(d => {
            const fullName = `${d.nombreDocente || d.nombre || ''} ${d.apPaternoDocente || d.apPaterno || ''} ${d.apMaternoDocente || d.apMaterno || ''}`.trim();
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'list-group-item list-group-item-action py-2 text-start';
            btn.innerHTML = `<span class="fw-semibold text-slate-800 fs-8">${fullName}</span>`;

            btn.onclick = (e) => {
                e.preventDefault();
                const input = document.getElementById('extraordinarioDocenteInput');
                if (input) input.value = fullName;
                div.style.display = 'none';
                actualizarExtraordinarioPreview();
            };
            div.appendChild(btn);
        });
    }

    function actualizarExtraordinarioPreview() {
        const alumno = document.getElementById('extraordinarioAlumnoSearch')?.value.trim() || '';
        const grupo = document.getElementById('extraordinarioGrupoInput')?.value.trim() || '';
        const docente = document.getElementById('extraordinarioDocenteInput')?.value.trim() || '';
        const materia = document.getElementById('extraordinarioMateriaInput')?.value.trim() || '';
        const semSelect = document.getElementById('extraordinarioSemestreSelect');
        const semestreText = semSelect && semSelect.selectedIndex > 0 ? semSelect.options[semSelect.selectedIndex].text : '';

        const lblA = document.getElementById('lbl-extraordinario-nombre');
        const lblG = document.getElementById('lbl-extraordinario-grupo');
        const lblD = document.getElementById('lbl-extraordinario-docente');
        const lblM = document.getElementById('lbl-extraordinario-materia');
        const lblS = document.getElementById('lbl-extraordinario-semestre-val');

        if (lblA && alumno) lblA.innerText = alumno;
        if (lblG) lblG.innerText = grupo || 'Sin asignar';
        if (lblD) lblD.innerText = docente || 'Sin asignar';
        if (lblM) lblM.innerText = materia || 'Sin asignar';
        if (lblS) lblS.innerText = semestreText || 'Sin especificar';

        const infoCard = document.getElementById('extraordinario-info-alumno');
        if (infoCard && (alumno || grupo || materia)) {
            infoCard.style.display = 'block';
        }
    }

    let alumnoExtraordinarioActual = null;

    function abrirPrevisualizacionExtraordinario() {
        const alumnoNombre = document.getElementById('extraordinarioAlumnoSearch')?.value.trim() || 
                             document.getElementById('lbl-extraordinario-nombre')?.innerText.trim() || '';
        const grupo = document.getElementById('extraordinarioGrupoInput')?.value.trim() || 
                      document.getElementById('lbl-extraordinario-grupo')?.innerText.trim() || 'Sin asignar';
        const materia = document.getElementById('extraordinarioMateriaInput')?.value.trim() || 
                        document.getElementById('lbl-extraordinario-materia')?.innerText.trim() || '';
        const docente = document.getElementById('extraordinarioDocenteInput')?.value.trim() || 
                        document.getElementById('lbl-extraordinario-docente')?.innerText.trim() || 'Sin asignar';
        const semSelect = document.getElementById('extraordinarioSemestreSelect');
        const semestre = semSelect && semSelect.selectedIndex > 0 ? semSelect.options[semSelect.selectedIndex].text : 
                         (document.getElementById('lbl-extraordinario-semestre-val')?.innerText.trim() || 'General');

        if (!alumnoNombre || alumnoNombre === 'Alumno') {
            Swal.fire({
                icon: 'warning',
                title: 'Campo Requerido',
                text: 'Por favor, busque y seleccione o escriba el nombre del alumno.',
                confirmButtonColor: '#26687B'
            });
            return;
        }

        if (!materia || materia === 'Materia') {
            Swal.fire({
                icon: 'warning',
                title: 'Campo Requerido',
                text: 'Por favor, seleccione o escriba la materia para el examen extraordinario.',
                confirmButtonColor: '#26687B'
            });
            return;
        }

        const matricula = alumnoExtraordinarioActual 
            ? (alumnoExtraordinarioActual.numeroControl || alumnoExtraordinarioActual.idAlumno || 'S/N') 
            : 'S/N';

        renderFormatoExtraordinario(alumnoNombre, matricula, grupo, semestre, materia, docente);

        const modalEl = document.getElementById('modalExtraordinarioPreview');
        if (modalEl && window.bootstrap && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        }
    }

    function renderFormatoExtraordinario(alumno, matricula, grupo, semestre, materia, docente) {
        const isBti = (cctSeleccionado === 'BTI') || (!cctSeleccionado && true);
        const cctNombre = isBti ? 'BACHILLERATO TECNOLÓGICO INTERAMERICANO' : 'BACHILLERATO GENERAL NO ESCOLARIZADO';
        const cctClave = isBti ? '21PCT0073R' : '21PBH0353G';

        const fechaObj = new Date();
        const fechaFormateada = fechaObj.toLocaleDateString('es-MX', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        }).toUpperCase();

        const randFolio = String(Math.floor(1000 + Math.random() * 9000));
        const folio = `EXT-${fechaObj.getFullYear()}-${randFolio}`;

        // Los estilos viajan dentro del HTML: se usa tanto en la vista previa como en la impresión
        const html = `
            <style>
                ${RP.css}
                .ex-acta { border: 1pt solid #26687B; padding: 5mm 5.5mm 4.5mm; background: #fff; }
                .ex-titulo { text-align: center; margin: 0 0 3mm; }
                .ex-titulo span { display: inline-block; font-size: 10.5pt; font-weight: bold; letter-spacing: 2.4px; text-transform: uppercase; color: #12393f; border-top: .6pt solid #c9a24b; border-bottom: .6pt solid #c9a24b; padding: 1.2mm 6mm; }
                .ex-dictamen td { height: 12mm; }
                .ex-check { display: inline-block; width: 3mm; height: 3mm; border: .8pt solid #12393f; vertical-align: -0.5mm; margin-right: 1.4mm; }
                .ex-talon { border: .8pt dashed #9fb3b7; padding: 4mm 5mm; background: #fff; }
                .ex-talon-h { display: flex; justify-content: space-between; align-items: baseline; border-bottom: .6pt solid #26687B; padding-bottom: 1.6mm; margin-bottom: 2.4mm; }
                .ex-talon-h .t { font-size: 8.6pt; font-weight: bold; text-transform: uppercase; color: #12393f; letter-spacing: .4px; }
                .ex-talon-h .t span { font-size: 7pt; color: #26687B; letter-spacing: 1.2px; }
                .ex-talon-h .f { font-size: 7.6pt; font-weight: bold; color: #9a2a1f; }
                .ex-talon .rp-card { padding: 1mm 2mm 1.1mm; }
                .ex-talon .rp-value { font-size: 7.6pt; }
            </style>
            <div class="rp">
                <!-- ==================== SECCIÓN 1: ACTA DE EXAMEN EXTRAORDINARIO ==================== -->
                <div class="ex-acta">
                    ${RP.head({
                        institucion: cctNombre,
                        lineas: ['Avenida Benito Juárez 901, Colonia Centro, Teziutlán, Puebla · Tel. 231-312-3979', `Clave C.C.T. ${cctClave}`],
                        folio: folio,
                        tagSub: fechaFormateada
                    })}

                    <div class="ex-titulo"><span>Acta de examen extraordinario</span></div>

                    ${RP.meta([
                        { label: 'Nombre del alumno(a)', value: alumno, flex: 2.1, lg: true },
                        { label: 'Matrícula / No. control', value: matricula, flex: 1 }
                    ])}
                    ${RP.meta([
                        { label: 'Asignatura / materia', value: materia, flex: 2.1 },
                        { label: `${isBti ? 'Semestre' : 'Trimestre'} y grupo`, value: `${semestre} | Grupo: ${grupo}`, flex: 1 }
                    ])}
                    ${RP.meta([
                        { label: 'Docente titular / evaluador', value: docente }
                    ])}

                    <table class="rp-table ex-dictamen" style="margin: 1mm 0 3mm;">
                        <thead>
                            <tr>
                                <th style="width: 25%;">Fecha de aplicación</th>
                                <th style="width: 22%;">Calificación (número)</th>
                                <th style="width: 33%;">Calificación (con letra)</th>
                                <th style="width: 20%;">Dictamen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="c" style="font-weight: bold; color: #12393f;">${fechaFormateada}</td>
                                <td class="n" style="font-size: 12pt;"></td>
                                <td></td>
                                <td style="font-size: 7pt; font-weight: bold; color: #12393f; line-height: 1.9; padding-left: 3mm;">
                                    <span class="ex-check"></span>APROBADO<br><span class="ex-check"></span>REPROBADO
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <p class="rp-note" style="margin: 0 0 4mm;">
                        El docente y las autoridades escolares que suscriben hacen constar que el(la) alumno(a) acreditó el proceso de examen extraordinario conforme al reglamento escolar vigente. Las calificaciones aquí asentadas son de carácter oficial y definitivo.
                    </p>

                    ${RP.firmas([
                        { rol: 'Firma del alumno', detalle: 'Aceptación de calificación' },
                        'sello:Sello escolar',
                        { rol: 'Docente evaluador', detalle: 'Firma de conformidad' },
                        { nombre: 'Ing. Fausto Mauro Leyva Flores', rol: 'Director', detalle: 'Autorización institucional' }
                    ], '11mm')}
                </div>

                <!-- Línea de Corte -->
                <div class="rp-cut">&#9986; Corte aquí — talón de derecho a examen extraordinario</div>

                <!-- ==================== SECCIÓN 2: RECIBO Y DERECHO A EXAMEN ==================== -->
                <div class="ex-talon">
                    <div class="ex-talon-h">
                        <div class="t">${cctNombre} &mdash; <span>Comprobante de derecho a examen</span></div>
                        <div class="f">FOLIO: ${folio}</div>
                    </div>

                    ${RP.meta([
                        { label: 'Alumno', value: alumno, flex: 2 },
                        { label: 'Matrícula', value: matricula, flex: 1 }
                    ])}
                    ${RP.meta([
                        { label: 'Asignatura', value: materia, flex: 1.4 },
                        { label: 'Periodo / grupo', value: `${semestre} (${grupo})`, flex: 1 },
                        { label: 'Docente asignado', value: docente, flex: 1.4 }
                    ])}

                    <p class="rp-note" style="margin: 0 0 3mm; background: #f4f8f8; border: .6pt solid #dfe8ea; padding: 1.6mm 2.4mm;">
                        El presente recibo certifica que el alumno(a) ha tramitado en tiempo y forma su solicitud para presentar la prueba extraordinaria de la materia indicada. Es obligatorio presentar este talón con sello al momento de realizar la evaluación.
                    </p>

                    <div style="padding: 0 8mm;">
                        ${RP.firmas([
                            { rol: 'Firma del alumno' },
                            'sello',
                            { nombre: 'Control Escolar', rol: 'Firma de validación' }
                        ], '8mm')}
                    </div>
                </div>
            </div>
        `;

        const container = document.getElementById('extraordinarioHojaImpresion');
        if (container) {
            container.innerHTML = html;
        }
    }

    function ejecutarImpresionExtraordinario() {
        const container = document.getElementById('extraordinarioHojaImpresion');
        if (!container) return;

        const contenidoHtml = container.innerHTML;
        const win = window.open('', '', 'height=900,width=850');
        win.document.write(RP.documento('Acta de Examen Extraordinario', contenidoHtml, { margin: '10mm' }));
        win.document.close();
        win.focus();
        setTimeout(() => {
            win.print();
        }, 350);
    }

    function imprimirActaExtraordinario() {
        abrirPrevisualizacionExtraordinario();
    }

    let alumnosGrupoActual = [];

    function onAsistenciaGrupoChange() {
        const grupoSelect = document.getElementById('asistenciaGrupoSelect');
        const grupoId = grupoSelect ? grupoSelect.value : '';
        const card = document.getElementById('asistencia-preview-card');

        if (!grupoId) {
            if (card) card.style.display = 'none';
            return;
        }

        const grupo = (window.gruposDb || []).find(g => g.id == grupoId);
        if (!grupo) return;

        // Auto-seleccionar trimestre/semestre según id_nivel_academico del grupo
        const cicloSelect = document.getElementById('asistenciaCicloSelect');
        if (cicloSelect && grupo.id_nivel_academico) {
            const isBti = (grupo.id_centroTrabajo == 2) || (cctSeleccionado === 'BTI');
            const num = isBti ? (grupo.id_nivel_academico - 6) : grupo.id_nivel_academico;
            const targetVal = isBti ? `${num}° Semestre` : `${num}° Trimestre`;
            for (let opt of cicloSelect.options) {
                if (opt.value === targetVal) {
                    cicloSelect.value = targetVal;
                    break;
                }
            }
        }

        // Ajustar visibilidad de periodos escolarizados según modalidad del grupo
        const modUpper = (grupo.modalidadHorario || '').toUpperCase();
        const isSabatino = modUpper.includes('SABADO') || modUpper.includes('SÁBADO');
        const isDominical = modUpper.includes('DOMINGO');
        const isEscolarizado = (grupo.id_centroTrabajo == 2) || (cctSeleccionado === 'BTI') || (!isSabatino && !isDominical);
        const escolarizadoRow = document.getElementById('asistencia-periodo-escolarizado-row');
        if (escolarizadoRow) {
            escolarizadoRow.style.display = isEscolarizado ? 'block' : 'none';
        }

        // Auto-seleccionar docente y materia si existen en tb_horarios
        const asignaciones = (window.horariosDb || []).filter(h => h.id_grupo == grupoId);
        const docenteSelect = document.getElementById('asistenciaDocenteSelect');
        const docenteInput = document.getElementById('asistenciaDocenteInput');
        const materiaInput = document.getElementById('asistenciaMateriaInput');

        if (asignaciones.length > 0) {
            if (docenteSelect && !docenteSelect.value) {
                docenteSelect.value = asignaciones[0].id_docente;
            }
            if (docenteInput && !docenteInput.value.trim()) {
                const docObj = (window.docentesDb || []).find(d => (d.idDocente || d.id) == asignaciones[0].id_docente);
                if (docObj) {
                    const fullNom = `${docObj.nombreDocente || docObj.nombre || ''} ${docObj.apPaternoDocente || docObj.apPaterno || ''} ${docObj.apMaternoDocente || docObj.apMaterno || ''}`.replace(/\s+/g, ' ').trim();
                    docenteInput.value = fullNom;
                    const btnClearDoc = document.getElementById('btn-limpiar-asistencia-docente');
                    if (btnClearDoc) btnClearDoc.style.display = 'block';
                }
            }
            if (materiaInput && !materiaInput.value.trim()) {
                const match = asignaciones.find(a => a.id_docente == (docenteSelect ? docenteSelect.value : null));
                materiaInput.value = match ? match.nombreMateria : asignaciones[0].nombreMateria;
                const btnClearMat = document.getElementById('btn-limpiar-asistencia-materia');
                if (btnClearMat) btnClearMat.style.display = 'block';
            }
        }

        // Consultar alumnos reales asignados al grupo
        fetch(`/reportes/grupo/${grupoId}/alumnos`)
            .then(r => r.json())
            .then(resp => {
                if (resp.success) {
                    alumnosGrupoActual = resp.data || [];
                    const badge = document.getElementById('lbl-asistencia-preview-alumnos');
                    if (badge) {
                        badge.innerText = `${resp.total} alumnos inscritos en este grupo`;
                    }
                }
                actualizarAsistenciaPreview();
            })
            .catch(err => {
                console.error('Error al consultar alumnos:', err);
                actualizarAsistenciaPreview();
            });
    }

    function onAsistenciaDocenteChange() {
        const grupoId = document.getElementById('asistenciaGrupoSelect')?.value;
        const docenteSelect = document.getElementById('asistenciaDocenteSelect');
        const docenteId = docenteSelect ? docenteSelect.value : '';
        const materiaInput = document.getElementById('asistenciaMateriaInput');

        if (grupoId && docenteId && materiaInput && !materiaInput.value.trim()) {
            const match = (window.horariosDb || []).find(h => h.id_grupo == grupoId && h.id_docente == docenteId);
            if (match) {
                materiaInput.value = match.nombreMateria;
                const btnClearMat = document.getElementById('btn-limpiar-asistencia-materia');
                if (btnClearMat) btnClearMat.style.display = 'block';
            }
        }
        actualizarAsistenciaPreview();
    }

    function onAsistenciaCicloChange() {
        const matInput = document.getElementById('asistenciaMateriaInput');
        const cicloSelect = document.getElementById('asistenciaCicloSelect');
        const semVal = cicloSelect ? cicloSelect.value : '';

        // Si la materia actual no pertenece al nuevo semestre seleccionado, limpiarla
        if (matInput && matInput.value.trim() && semVal) {
            const materias = obtenerMateriasFiltradasAsistencia();
            const coincide = materias.some(m => (m.nombreMateria || '').toLowerCase().trim() === matInput.value.toLowerCase().trim());
            if (!coincide) {
                matInput.value = '';
                const btnClearMat = document.getElementById('btn-limpiar-asistencia-materia');
                if (btnClearMat) btnClearMat.style.display = 'none';
            }
        }

        // Desplegar de inmediato las materias de ese semestre bajo el campo de Asignatura
        buscarMateriasAsistencia('', true);

        actualizarAsistenciaPreview();
    }

    function obtenerMateriasFiltradasAsistencia(query = '') {
        const materias = window.materiasDb || [];
        const cicloSelect = document.getElementById('asistenciaCicloSelect');
        const cicloVal = cicloSelect ? cicloSelect.value : '';
        const match = cicloVal.match(/\d+/);
        const semNum = match ? parseInt(match[0]) : null;

        const normQ = (query || '').normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().trim();
        const targetCentroId = cctSeleccionado === 'BTI' ? 2 : (cctSeleccionado === 'BGNE' ? 3 : null);

        return materias.filter(m => {
            if (targetCentroId) {
                const cid = m.idCentroTrabajo ?? m.id_centro_trabajo ?? m.id_centroTrabajo;
                if (cid && cid != targetCentroId) return false;
            }

            // Solo materias del semestre/trimestre elegido (nivel exacto del plantel)
            if (semNum !== null && parseInt(m.id_nivel_academico) !== nivelDePeriodo(semNum)) return false;

            if (!normQ) return true;
            const nomNorm = (m.nombreMateria || '').normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
            const claveNorm = (m.clave || '').normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
            return nomNorm.includes(normQ) || claveNorm.includes(normQ);
        });
    }

    function buscarMateriasAsistencia(query = '', forceOpen = false) {
        const div = document.getElementById('asistencia-materia-sugerencia');
        const btnClear = document.getElementById('btn-limpiar-asistencia-materia');
        if (btnClear) {
            btnClear.style.display = query ? 'block' : 'none';
        }
        if (!div) return;

        // Si materiasDb está vacío, cargar diferido
        if (!window.materiasDb || window.materiasDb.length === 0) {
            fetch('/materias/lista?limit=1000')
                .then(r => r.json())
                .then(res => {
                    if (res && res.data) {
                        window.materiasDb = res.data;
                        buscarMateriasAsistencia(query, forceOpen);
                    }
                }).catch(() => {});
        }

        const cicloSelect = document.getElementById('asistenciaCicloSelect');
        const cicloVal = cicloSelect ? cicloSelect.value : '';
        const match = cicloVal.match(/\d+/);
        const semNum = match ? parseInt(match[0]) : null;

        const filtered = obtenerMateriasFiltradasAsistencia(query);

        div.innerHTML = '';
        div.style.display = 'block';

        const cicloLabel = document.getElementById('lbl-asistencia-ciclo-label')?.innerText || 'Periodo';
        const headerNotice = document.createElement('div');
        headerNotice.className = 'p-2 bg-light text-primary fw-bold fs-9 border-bottom text-center d-flex justify-content-between align-items-center px-3';
        
        if (semNum !== null) {
            headerNotice.innerHTML = `
                <span><i class="ti ti-stack me-1"></i> ${cicloVal} (${filtered.length} materias)</span>
                <span class="badge bg-primary-subtle text-primary">${cctSeleccionado || 'Plan'}</span>
            `;
        } else {
            headerNotice.innerHTML = `
                <span><i class="ti ti-info-circle me-1"></i> Seleccione un ${cicloLabel.toLowerCase()} para filtrar materias</span>
                <span class="badge bg-secondary-subtle text-secondary">${filtered.length} totales</span>
            `;
        }
        div.appendChild(headerNotice);

        if (filtered.length === 0) {
            const emptyDiv = document.createElement('div');
            emptyDiv.className = 'p-3 text-muted fs-8 text-center';
            emptyDiv.innerText = semNum !== null ? `No hay materias registradas para ${cicloVal}` : 'No se encontraron materias';
            div.appendChild(emptyDiv);
            return;
        }

        filtered.slice(0, 30).forEach(m => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'list-group-item list-group-item-action py-2 text-start d-flex justify-content-between align-items-center';

            const numPeriodo = periodoDeNivel(m.id_nivel_academico);
            const semBadge = numPeriodo ? `${ORDINALES_PERIODO[numPeriodo - 1]} ${periodoExtraordinario().nombre.slice(0, 4).toLowerCase()}.` : '';

            btn.innerHTML = `
                <div>
                    <span class="fw-semibold text-slate-800 fs-8 d-block">${m.nombreMateria}</span>
                    ${m.clave ? `<small class="text-muted fs-9">Clave: ${m.clave}</small>` : ''}
                </div>
                <div>
                    ${semBadge ? `<span class="badge bg-light text-secondary border fs-9">${semBadge}</span>` : ''}
                </div>
            `;

            btn.onclick = (e) => {
                e.preventDefault();
                e.stopPropagation();
                seleccionarMateriaAsistencia(m);
            };
            div.appendChild(btn);
        });
    }

    function seleccionarMateriaAsistencia(materia) {
        const input = document.getElementById('asistenciaMateriaInput');
        const div = document.getElementById('asistencia-materia-sugerencia');
        const btnClear = document.getElementById('btn-limpiar-asistencia-materia');

        if (input) input.value = materia.nombreMateria;
        if (btnClear) btnClear.style.display = 'block';
        if (div) div.style.display = 'none';

        // Si hay grupo seleccionado, intentar auto-asignar docente de tb_horarios
        const grupoId = document.getElementById('asistenciaGrupoSelect')?.value;
        if (grupoId && window.horariosDb) {
            const matId = materia.id || materia.idMateria;
            const matchH = window.horariosDb.find(h => h.id_grupo == grupoId && (h.id_materia == matId || (h.nombreMateria && h.nombreMateria.toLowerCase() === materia.nombreMateria.toLowerCase())));
            if (matchH && matchH.id_docente) {
                const docObj = (window.docentesDb || []).find(d => (d.idDocente || d.id) == matchH.id_docente);
                if (docObj) {
                    const fullNom = `${docObj.nombreDocente || docObj.nombre || ''} ${docObj.apPaternoDocente || docObj.apPaterno || ''} ${docObj.apMaternoDocente || docObj.apMaterno || ''}`.replace(/\s+/g, ' ').trim();
                    seleccionarDocenteAsistencia(matchH.id_docente, fullNom);
                }
            }
        }

        actualizarAsistenciaPreview();
    }

    function limpiarMateriaAsistencia() {
        const input = document.getElementById('asistenciaMateriaInput');
        const div = document.getElementById('asistencia-materia-sugerencia');
        const btnClear = document.getElementById('btn-limpiar-asistencia-materia');

        if (input) input.value = '';
        if (btnClear) btnClear.style.display = 'none';
        if (div) { div.innerHTML = ''; div.style.display = 'none'; }

        actualizarAsistenciaPreview();
    }

    function buscarDocentesAsistencia(query = '') {
        const div = document.getElementById('asistencia-docente-sugerencia');
        const btnClear = document.getElementById('btn-limpiar-asistencia-docente');
        if (btnClear) {
            btnClear.style.display = query ? 'block' : 'none';
        }
        if (!div) return;

        let docentes = window.docentesDb || [];
        if (docentes.length === 0) {
            const sel = document.getElementById('asistenciaDocenteSelect');
            if (sel && sel.options.length > 1) {
                docentes = Array.from(sel.options).slice(1).map(opt => ({
                    id: opt.value,
                    idDocente: opt.value,
                    nombreDocente: opt.text
                }));
                window.docentesDb = docentes;
            }
        }

        const normQ = (query || '').normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().trim();

        let filtered = docentes.filter(d => {
            if (!normQ) return true;
            const nom = `${d.nombreDocente || d.nombre || ''} ${d.apPaternoDocente || d.apPaterno || ''} ${d.apMaternoDocente || d.apMaterno || ''}`;
            const nomNorm = nom.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
            return nomNorm.includes(normQ);
        });

        div.innerHTML = '';
        div.style.display = 'block';

        if (filtered.length === 0) {
            div.innerHTML = '<div class="p-2 text-muted fs-8 text-center">No se encontraron docentes</div>';
            return;
        }

        filtered.slice(0, 25).forEach(d => {
            const dId = d.idDocente || d.id;
            const fullName = `${d.nombreDocente || d.nombre || ''} ${d.apPaternoDocente || d.apPaterno || ''} ${d.apMaternoDocente || d.apMaterno || ''}`.replace(/\s+/g, ' ').trim();
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'list-group-item list-group-item-action py-2 text-start d-flex justify-content-between align-items-center';
            btn.innerHTML = `
                <div>
                    <span class="fw-semibold text-slate-800 fs-8 d-block">${fullName}</span>
                </div>
                <i class="ti ti-check text-muted fs-9 opacity-50"></i>
            `;

            btn.onclick = (e) => {
                e.preventDefault();
                e.stopPropagation();
                seleccionarDocenteAsistencia(dId, fullName);
            };
            div.appendChild(btn);
        });
    }

    function seleccionarDocenteAsistencia(dId, fullName) {
        const input = document.getElementById('asistenciaDocenteInput');
        const sel = document.getElementById('asistenciaDocenteSelect');
        const div = document.getElementById('asistencia-docente-sugerencia');
        const btnClear = document.getElementById('btn-limpiar-asistencia-docente');

        if (input) input.value = fullName;
        if (sel) {
            sel.value = dId;
            if (sel.value != dId) {
                const opt = document.createElement('option');
                opt.value = dId;
                opt.text = fullName;
                sel.appendChild(opt);
                sel.value = dId;
            }
        }
        if (btnClear) btnClear.style.display = 'block';
        if (div) div.style.display = 'none';

        onAsistenciaDocenteChange();
    }

    function limpiarDocenteAsistencia() {
        const input = document.getElementById('asistenciaDocenteInput');
        const sel = document.getElementById('asistenciaDocenteSelect');
        const div = document.getElementById('asistencia-docente-sugerencia');
        const btnClear = document.getElementById('btn-limpiar-asistencia-docente');

        if (input) input.value = '';
        if (sel) sel.value = '';
        if (btnClear) btnClear.style.display = 'none';
        if (div) { div.innerHTML = ''; div.style.display = 'none'; }

        actualizarAsistenciaPreview();
    }

    function contarDiasHabiles(startDateStr, endDateStr) {
        if (!startDateStr || !endDateStr) return { dias: 0, fechaCorte: null };
        const p1 = startDateStr.split('-');
        const p2 = endDateStr.split('-');
        if (p1.length < 3 || p2.length < 3) return { dias: 0, fechaCorte: null };

        let cur = new Date(Date.UTC(parseInt(p1[0]), parseInt(p1[1]) - 1, parseInt(p1[2])));
        const end = new Date(Date.UTC(parseInt(p2[0]), parseInt(p2[1]) - 1, parseInt(p2[2])));

        if (cur > end) return { dias: 0, fechaCorte: null };

        let count = 0;
        let fechaCorte = null;

        while (cur <= end) {
            const dayOfWeek = cur.getUTCDay(); // 0: Dom, 6: Sab
            if (dayOfWeek !== 0 && dayOfWeek !== 6) {
                count++;
                if (count === 45) {
                    fechaCorte = new Date(cur.getTime());
                }
            }
            cur.setUTCDate(cur.getUTCDate() + 1);
        }

        return { dias: count, fechaCorte: fechaCorte };
    }

    function onAsistenciaPeriodoSelectChange() {
        const sel = document.getElementById('asistenciaPeriodoSelect');
        const val = sel ? sel.value : '1';
        const customContainer = document.getElementById('asistencia-custom-fechas-container');
        const excesoAlerta = document.getElementById('asistencia-exceso-alerta');
        const fIniInput = document.getElementById('asistenciaFechaInicioInput');
        const fFinInput = document.getElementById('asistenciaFechaFinInput');

        if (val === 'custom') {
            if (customContainer) customContainer.style.display = 'block';
            onAsistenciaCustomFechasChange();
        } else {
            if (customContainer) customContainer.style.display = 'none';
            if (excesoAlerta) excesoAlerta.style.display = 'none';

            const anioActual = new Date().getFullYear();
            if (val === '1') {
                if (fIniInput) fIniInput.value = `${anioActual}-08-31`;
                if (fFinInput) fFinInput.value = `${anioActual}-10-30`;
            } else if (val === '2') {
                if (fIniInput) fIniInput.value = `${anioActual}-11-02`;
                if (fFinInput) fFinInput.value = `${anioActual}-12-18`;
            }
            actualizarAsistenciaPreview();
        }
    }

    function onAsistenciaCustomFechasChange() {
        const fIniInput = document.getElementById('asistenciaFechaInicioInput');
        const fFinInput = document.getElementById('asistenciaFechaFinInput');
        const badge = document.getElementById('asistencia-dias-badge');
        const excesoAlerta = document.getElementById('asistencia-exceso-alerta');
        const excesoMsg = document.getElementById('asistencia-exceso-mensaje');

        if (!fIniInput || !fFinInput) return;

        const info = contarDiasHabiles(fIniInput.value, fFinInput.value);
        const dias = info.dias;

        if (badge) {
            if (dias > 45) {
                badge.className = 'tw:inline-flex tw:h-10 tw:items-center tw:bg-warning-100 tw:px-3 tw:text-[0.6875rem] tw:font-semibold tw:text-warning-600';
                badge.innerText = `${dias} días`;
            } else {
                badge.className = 'tw:inline-flex tw:h-10 tw:items-center tw:bg-success-100 tw:px-3 tw:text-[0.6875rem] tw:font-semibold tw:text-success-700';
                badge.innerText = `${dias} días (1 hoja)`;
            }
        }

        if (excesoAlerta) {
            if (dias > 45) {
                const pad = (n) => String(n).padStart(2, '0');
                const corteStr = info.fechaCorte 
                    ? `${pad(info.fechaCorte.getUTCDate())}/${pad(info.fechaCorte.getUTCMonth() + 1)}/${info.fechaCorte.getUTCFullYear()}`
                    : '';
                if (excesoMsg) {
                    excesoMsg.innerText = `Ha seleccionado ${dias} días hábiles. Por límites de impresión (1 página), el reporte abarcará los primeros 45 días hábiles ${corteStr ? `(hasta el ${corteStr})` : ''}.`;
                }
                excesoAlerta.style.display = 'flex';
            } else {
                excesoAlerta.style.display = 'none';
            }
        }

        actualizarAsistenciaPreview();
    }

    function calcularFechasTrimestrePreview(grupo, trimestreNum) {
        if (!grupo) return 'Fechas por definir';

        const modalidad = (grupo.modalidadHorario || '').toUpperCase();
        const isSabatino = modalidad.includes('SABADO') || modalidad.includes('SÁBADO');
        const isDominical = modalidad.includes('DOMINGO');
        const isEscolarizado = (grupo.id_centroTrabajo == 2) || (cctSeleccionado === 'BTI') || (!isSabatino && !isDominical);

        const pad = (n) => String(n).padStart(2, '0');
        const fmt = (d) => `${pad(d.getUTCDate())}/${pad(d.getUTCMonth() + 1)}/${d.getUTCFullYear()}`;

        if (isEscolarizado) {
            const periodoSel = document.getElementById('asistenciaPeriodoSelect')?.value || '1';
            let anio = new Date().getFullYear();
            if (grupo.fechaInicio) {
                const p = grupo.fechaInicio.split('-');
                if (p.length >= 1 && parseInt(p[0]) > 2000) anio = parseInt(p[0]);
            }

            if (periodoSel === '1') {
                return `31/08/${anio} al 30/10/${anio} (1° Periodo: 45 días hábiles)`;
            } else if (periodoSel === '2') {
                return `02/11/${anio} al 18/12/${anio} (2° Periodo: 35 días hábiles)`;
            } else {
                const fIni = document.getElementById('asistenciaFechaInicioInput')?.value;
                const fFin = document.getElementById('asistenciaFechaFinInput')?.value;
                if (fIni && fFin) {
                    const info = contarDiasHabiles(fIni, fFin);
                    const p1 = fIni.split('-');
                    const p2 = fFin.split('-');
                    const d1 = new Date(Date.UTC(parseInt(p1[0]), parseInt(p1[1]) - 1, parseInt(p1[2])));
                    const d2 = new Date(Date.UTC(parseInt(p2[0]), parseInt(p2[1]) - 1, parseInt(p2[2])));
                    return `${fmt(d1)} al ${fmt(d2)} (Personalizado: ${info.dias} días hábiles${info.dias > 45 ? ' / tope 45' : ''})`;
                }
                return `31/08/${anio} al 30/10/${anio} (45 días hábiles)`;
            }
        }

        if (!grupo.fechaInicio) return 'Fechas por definir';
        const parts = grupo.fechaInicio.split('-');
        if (parts.length < 3) return 'Fechas por definir';
        const startDate = new Date(Date.UTC(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2])));

        // Cada trimestre dura exactamente 13 semanas (Sabatino / Dominical)
        const weeksOffset = (trimestreNum - 1) * 13;
        const periodStart = new Date(startDate.getTime() + (weeksOffset * 7 * 24 * 60 * 60 * 1000));
        const periodEnd = new Date(periodStart.getTime() + (12 * 7 * 24 * 60 * 60 * 1000));

        return `${fmt(periodStart)} al ${fmt(periodEnd)} (13 semanas)`;
    }

    function actualizarAsistenciaPreview() {
        const grupoSelect = document.getElementById('asistenciaGrupoSelect');
        const docenteSelect = document.getElementById('asistenciaDocenteSelect');
        const docenteInput = document.getElementById('asistenciaDocenteInput');
        const materiaInput = document.getElementById('asistenciaMateriaInput');
        const cicloSelect = document.getElementById('asistenciaCicloSelect');
        const card = document.getElementById('asistencia-preview-card');

        if (!grupoSelect || !grupoSelect.value) {
            if (card) card.style.display = 'none';
            return;
        }

        const grupoId = grupoSelect.value;
        const grupo = (window.gruposDb || []).find(g => g.id == grupoId);
        if (!grupo) {
            if (card) card.style.display = 'none';
            return;
        }

        let docenteNombre = 'Docente no seleccionado (general)';
        if (docenteInput && docenteInput.value.trim()) {
            docenteNombre = docenteInput.value.trim();
        } else if (docenteSelect && docenteSelect.selectedIndex > 0) {
            docenteNombre = docenteSelect.options[docenteSelect.selectedIndex].text.trim();
        }
            
        const materiaNombre = (materiaInput && materiaInput.value.trim()) ? materiaInput.value.trim() : 'Materia General';
        const ciclo = cicloSelect ? cicloSelect.value : '1° Periodo';

        const matchTrim = ciclo.match(/\d+/);
        const trimNum = matchTrim ? parseInt(matchTrim[0]) : 1;

        const lblD = document.getElementById('lbl-asistencia-preview-docente');
        const lblM = document.getElementById('lbl-asistencia-preview-materia');
        const lblG = document.getElementById('lbl-asistencia-preview-grupo');
        const lblT = document.getElementById('lbl-asistencia-preview-term');
        const lblFechas = document.getElementById('lbl-asistencia-preview-fechas-val');

        if (lblD) lblD.innerText = docenteNombre;
        if (lblM) lblM.innerText = materiaNombre;
        if (lblG) lblG.innerText = `${grupo.clave} (${grupo.modalidadHorario || ''})`;
        if (lblT) lblT.innerText = ciclo;
        if (lblFechas) lblFechas.innerText = calcularFechasTrimestrePreview(grupo, trimNum);

        if (card) card.style.display = 'block';
    }

    function generarListaAsistenciaOficialPdf() {
        const grupoSelect = document.getElementById('asistenciaGrupoSelect');
        const grupoId = grupoSelect ? grupoSelect.value : '';

        if (!grupoId) {
            Swal.fire({
                icon: 'warning',
                title: 'Seleccione un grupo',
                text: 'Por favor, elija un grupo para generar la lista de asistencia.',
                confirmButtonColor: '#26687B'
            });
            return;
        }

        const grupo = (window.gruposDb || []).find(g => g.id == grupoId) || {};
        const clave = grupo.clave || '';
        const cctId = grupo.id_centroTrabajo ?? grupo.idCentroTrabajo ?? '';
        const modalidad = grupo.modalidadHorario || '';
        const fechaIni = grupo.fechaInicio || '';

        const docenteSelect = document.getElementById('asistenciaDocenteSelect');
        const docenteInput = document.getElementById('asistenciaDocenteInput');
        let docenteId = docenteSelect ? docenteSelect.value : '';
        let docenteNombre = docenteInput && docenteInput.value.trim() 
            ? docenteInput.value.trim() 
            : (docenteSelect && docenteSelect.selectedIndex > 0 ? docenteSelect.options[docenteSelect.selectedIndex].text.trim() : '');

        if (!docenteId && docenteNombre && window.docentesDb) {
            const normDN = docenteNombre.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().trim();
            const foundDoc = window.docentesDb.find(d => {
                const nom = `${d.nombreDocente || d.nombre || ''} ${d.apPaternoDocente || d.apPaterno || ''} ${d.apMaternoDocente || d.apMaterno || ''}`.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().trim();
                return nom === normDN;
            });
            if (foundDoc) {
                docenteId = foundDoc.idDocente || foundDoc.id || '';
            }
        }

        const materia = (document.getElementById('asistenciaMateriaInput') ? document.getElementById('asistenciaMateriaInput').value.trim() : '');
        const ciclo = document.getElementById('asistenciaCicloSelect') ? document.getElementById('asistenciaCicloSelect').value : '';
        const matchTrim = ciclo.match(/\d+/);
        const trimestreNum = matchTrim ? matchTrim[0] : '1';

        const modUpper = (modalidad || '').toUpperCase();
        const isSabatino = modUpper.includes('SABADO') || modUpper.includes('SÁBADO');
        const isDominical = modUpper.includes('DOMINGO');
        const isEscolarizado = (grupo.id_centroTrabajo == 2) || (cctSeleccionado === 'BTI') || (!isSabatino && !isDominical);

        let extraParams = '';
        if (isEscolarizado) {
            const periodoSel = document.getElementById('asistenciaPeriodoSelect')?.value || '1';
            const fIni = document.getElementById('asistenciaFechaInicioInput')?.value || '';
            const fFin = document.getElementById('asistenciaFechaFinInput')?.value || '';
            extraParams = `&periodo_escolarizado=${encodeURIComponent(periodoSel)}&fecha_inicio=${encodeURIComponent(fIni)}&fecha_fin=${encodeURIComponent(fFin)}`;
        } else {
            extraParams = `&fecha_inicio=${encodeURIComponent(fechaIni)}`;
        }

        const url = `/reportes/asistencia-pdf?id_grupo=${encodeURIComponent(grupoId)}&clave_grupo=${encodeURIComponent(clave)}&id_centro_trabajo=${encodeURIComponent(cctId)}&modalidad=${encodeURIComponent(modalidad)}${extraParams}&id_docente=${encodeURIComponent(docenteId)}&docente_nombre=${encodeURIComponent(docenteNombre)}&materia=${encodeURIComponent(materia)}&trimestre=${encodeURIComponent(trimestreNum)}`;
        
        window.open(url, '_blank');
    }

    // ==========================================
    // LOGICA DE FILTRADO PARA LISTA DE ASISTENCIA POR GRUPO
    // ==========================================
    function filtrarDocentesAsistencias() {
        const searchVal = document.getElementById('asistenciasSearch').value.toLowerCase().trim();
        const diaFilter = document.getElementById('filtroDiaAsistencias').value;
        const trimestreFilter = document.getElementById('filtroTrimestreAsistencias').value;

        const rows = document.querySelectorAll('.docente-row');
        let visibleRows = 0;

        rows.forEach(row => {
            const docente = row.getAttribute('data-docente');
            const materia = row.getAttribute('data-materia');
            const dia = row.getAttribute('data-dia');
            const trimestre = row.getAttribute('data-trimestre');
            const cct = row.getAttribute('data-cct');

            // Filtrar también para que coincida con el CCT actualmente seleccionado
            const matchCCT = cct === cctSeleccionado;
            const matchSearch = docente.includes(searchVal) || materia.includes(searchVal);
            const matchDia = diaFilter === '' || dia === diaFilter;
            const matchTrimestre = trimestreFilter === '' || trimestre === trimestreFilter;

            if (matchCCT && matchSearch && matchDia && matchTrimestre) {
                row.style.display = 'table-row';
                visibleRows++;
            } else {
                row.style.display = 'none';
            }
        });

        const errorDiv = document.getElementById('noAsistenciasResultados');
        const tabla = document.getElementById('tablaAsistenciasDocentes');

        if (visibleRows === 0) {
            errorDiv.style.display = 'block';
            tabla.style.display = 'none';
        } else {
            errorDiv.style.display = 'none';
            tabla.style.display = 'table';
        }
    }

    // ==========================================
    // SIMULACIÓN DE DESCARGAS E IMPRESIÓN (SWEETALERT2)
    // ==========================================

    function previewDoc(tipo, alumno) {
        Swal.fire({
            title: `Vista Previa: ${tipo}`,
            html: `<div class="p-3 border rounded text-start bg-light" style="font-family: monospace; font-size: 0.85rem;">
                     <strong>SISTEMA DE CONTROL ESCOLAR</strong><br>
                     CCT: ${cctSeleccionado}<br>
                     Documento: ${tipo} Certificado<br>
                     Alumno: ${alumno}<br>
                     Fecha de Emisión: ${new Date().toLocaleDateString()}<br>
                     -----------------------------------------<br>
                     * Estatus de acreditación de materias.<br>
                     * Código de verificación QR.<br>
                     * Firma digital del Director General.<br>
                     -----------------------------------------<br>
                     <span class="text-muted">(Vista previa simulada con éxito)</span>
                   </div>`,
            width: '600px',
            confirmButtonText: 'Cerrar',
            confirmButtonColor: '#26687B'
        });
    }

    // Formatos que todavía no generan documento: se dice tal cual, sin simular una descarga.
    function printDoc(tipo) {
        Swal.fire({
            title: `${tipo}: aún no disponible para ${cctSeleccionado}`,
            text: `Por ahora este formato solo se genera para el BTI. Para ${cctSeleccionado}, elabóralo en control escolar con la plantilla en papel.`,
            icon: 'info',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#26687B'
        });
    }

    // ==========================================
    // SECCIÓN: REPORTES DE INDISCIPLINA
    // ==========================================

    let reporteAlumnoSeleccionado = null;

    window.buscarAlumnoReporte = function(query) {
        buscarAlumnosReal(query, 'reporte-alumno-sugerencia', (alumno) => {
            reporteAlumnoSeleccionado = alumno;
            document.getElementById('reporte_id_alumno').value = alumno.idAlumno;
            document.getElementById('reporte_alumno_nombre').value = `${alumno.nombre} ${alumno.apPaterno} ${alumno.apMaterno || ''}`.trim();
            
            document.getElementById('lbl-reporte-alumno-nom').innerText = `${alumno.nombre} ${alumno.apPaterno} ${alumno.apMaterno || ''}`.trim().toUpperCase();
            document.getElementById('lbl-reporte-alumno-mat').innerText = `Matrícula: ${alumno.numeroControl || alumno.idAlumno || 'S/N'}`;
            
            // Cargar tutor automáticamente
            document.getElementById('reporte_tutor_nombre').value = alumno.tutor || '';
            
            document.getElementById('reporte-alumno-info-box').style.display = 'block';
            document.getElementById('reporteAlumnoSearch').value = '';
        });
    };

    window.deseleccionarAlumnoReporte = function() {
        reporteAlumnoSeleccionado = null;
        document.getElementById('reporte_id_alumno').value = '';
        document.getElementById('reporte_alumno_nombre').value = '';
        document.getElementById('reporte_tutor_nombre').value = '';
        document.getElementById('reporte-alumno-info-box').style.display = 'none';
    };

    window.registrarReporteIndisciplina = function(event) {
        event.preventDefault();
        
        const idAlumno = document.getElementById('reporte_id_alumno').value;
        const alumnoNombre = document.getElementById('reporte_alumno_nombre').value;
        const tutorNombre = document.getElementById('reporte_tutor_nombre').value;
        const incidente = document.getElementById('reporte_incidente').value;
        const parcial = document.getElementById('reporte_parcial').value;

        if (!idAlumno) {
            Swal.fire({
                icon: 'warning',
                title: 'Seleccione un alumno',
                text: 'Por favor, busque y seleccione un alumno de la lista.',
                confirmButtonColor: '#26687B'
            });
            return;
        }

        Swal.fire({
            title: 'Registrando reporte...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('/reportes-indisciplina', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                id_alumno: idAlumno,
                alumno_nombre: alumnoNombre,
                tutor_nombre: tutorNombre,
                incidente: incidente,
                parcial: parcial
            })
        })
        .then(r => r.json())
        .then(resp => {
            Swal.close();
            if (resp.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Reporte registrado',
                    text: 'El reporte de indisciplina se guardó correctamente.',
                    confirmButtonColor: '#26687B'
                }).then(() => {
                    deseleccionarAlumnoReporte();
                    document.getElementById('reporte_incidente').value = '';
                    cargarHistorialReportes();
                    imprimirFormatoReporte(resp.data);
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: resp.message || 'No se pudo registrar el reporte.',
                    confirmButtonColor: '#26687B'
                });
            }
        })
        .catch(err => {
            Swal.close();
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error de red',
                text: 'Ocurrió un problema de comunicación con el servidor.',
                confirmButtonColor: '#26687B'
            });
        });
    };

    window.cargarHistorialReportes = function(search = '') {
        const tbody = document.getElementById('tabla-reportes-historial');
        if (!tbody) return;

        fetch(`/reportes-indisciplina?search=${encodeURIComponent(search)}`)
            .then(r => r.json())
            .then(resp => {
                if (resp.success) {
                    tbody.innerHTML = '';
                    const list = resp.data || [];
                    if (list.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="6" class="tw:px-4 tw:py-8 tw:text-center tw:text-[0.8125rem] tw:text-slate-500">No se encontraron reportes.</td></tr>`;
                        return;
                    }

                    const esc = (v) => String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
                    const accion = 'tw:inline-flex tw:size-8 tw:cursor-pointer tw:items-center tw:justify-center tw:border-0 tw:bg-transparent tw:transition-colors';

                    window.reportesCache = window.reportesCache || {};
                    list.forEach(rep => {
                        window.reportesCache[rep.id] = rep;
                        const tr = document.createElement('tr');
                        tr.className = 'tw:text-[0.8125rem] tw:transition-colors tw:hover:bg-slate-50';
                        tr.innerHTML = `
                            <td class="tw:px-4 tw:py-3 tw:align-middle"><span class=" tw:bg-slate-100 tw:px-2 tw:py-0.5 tw:font-mono tw:text-[0.6875rem] tw:font-semibold tw:text-slate-700">${esc(rep.folio)}</span></td>
                            <td class="tw:px-4 tw:py-3 tw:align-middle">
                                <span class="tw:block tw:font-semibold tw:text-slate-900">${esc(rep.alumno_nombre)}</span>
                                <span class="tw:text-[0.6875rem] tw:text-slate-400">ID ${esc(rep.id_alumno)}</span>
                            </td>
                            <td class="tw:px-4 tw:py-3 tw:align-middle tw:text-slate-600">${esc(rep.tutor_nombre)}</td>
                            <td class="tw:px-4 tw:py-3 tw:align-middle"><span class="tw:inline-flex tw:bg-brand-50 tw:px-2.5 tw:py-0.5 tw:text-[0.6875rem] tw:font-bold tw:text-brand-700 tw:ring-1 tw:ring-brand-200">P${esc(rep.parcial)}</span></td>
                            <td class="tw:whitespace-nowrap tw:px-4 tw:py-3 tw:align-middle tw:font-mono tw:text-xs tw:text-slate-600">${new Date(rep.fecha + 'T00:00:00').toLocaleDateString('es-MX')}</td>
                            <td class="tw:px-4 tw:py-3 tw:text-right tw:align-middle tw:whitespace-nowrap">
                                <button type="button" class="${accion} tw:text-brand-700 tw:hover:bg-brand-50" onclick="imprimirFormatoReporte(window.reportesCache[${Number(rep.id)}])" title="Imprimir formato" aria-label="Imprimir formato">
                                    <i data-lucide="printer" class="tw:size-4"></i>
                                </button>
                                <button type="button" class="${accion} tw:text-slate-400 tw:hover:bg-danger-50 tw:hover:text-danger-600" onclick="eliminarReporte(${Number(rep.id)})" title="Eliminar" aria-label="Eliminar">
                                    <i data-lucide="trash-2" class="tw:size-4"></i>
                                </button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                    if (window.lucide?.createIcons) lucide.createIcons();
                }
            })
            .catch(err => {
                console.error('Error al cargar historial:', err);
                tbody.innerHTML = `<tr><td colspan="6" class="tw:px-4 tw:py-8 tw:text-center tw:text-[0.8125rem] tw:text-danger-600">Error al cargar el historial.</td></tr>`;
            });
    };

    window.eliminarReporte = function(id) {
        Swal.fire({
            title: '¿Eliminar reporte?',
            text: "Esta acción no se puede deshacer y el reporte se borrará del historial.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#64748b'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Eliminando...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/reportes-indisciplina/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(resp => {
                    Swal.close();
                    if (resp.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: 'El reporte de indisciplina fue eliminado.',
                            confirmButtonColor: '#26687B'
                        });
                        cargarHistorialReportes();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: resp.message || 'No se pudo eliminar el reporte.',
                            confirmButtonColor: '#26687B'
                        });
                    }
                })
                .catch(err => {
                    Swal.close();
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de red',
                        text: 'No se pudo comunicar con el servidor.',
                        confirmButtonColor: '#26687B'
                    });
                });
            }
        });
    };

    window.imprimirFormatoReporte = function(rep) {
        const fechaFormateada = new Date(rep.fecha + 'T00:00:00').toLocaleDateString('es-MX', {day: 'numeric', month: 'long', year: 'numeric'}).toUpperCase();

        function renderBloque(repVal) {
            return `
                <style>
                    .in-hoja { border: 1pt solid #26687B; padding: 7mm 8mm 6mm; }
                    .in-titulo { text-align: center; margin: 2mm 0 6mm; }
                    .in-titulo span { display: inline-block; font-size: 12pt; font-weight: bold; letter-spacing: 3px; text-transform: uppercase; color: #12393f; border-top: .6pt solid #c9a24b; border-bottom: .6pt solid #c9a24b; padding: 1.6mm 8mm; }
                    .in-texto { font-size: 9.4pt; line-height: 1.75; color: #1f2a2c; }
                    .in-texto p { margin: 0 0 3.5mm; }
                    .in-suscribe { font-size: 8pt; font-weight: bold; letter-spacing: .5px; text-transform: uppercase; color: #4c5d60; border-left: 1.2mm solid #c9a24b; background: #fbf8ef; padding: 1.8mm 3mm; }
                    .in-incidente { border: .6pt solid #b9c9cc; border-left: 1.4mm solid #26687B; background: #f4f8f8; padding: 3mm 4mm; min-height: 32mm; white-space: pre-wrap; font-size: 9.2pt; line-height: 1.55; color: #12393f; margin-bottom: 5mm; }
                    .in-cierre { font-size: 8.2pt; font-style: italic; font-weight: bold; color: #26687B; text-align: center; line-height: 1.55; margin: 0 6mm 10mm; }
                </style>
                <div class="in-hoja">
                    ${RP.head({
                        lineas: ['Avenida Benito Juárez 901, Colonia Centro, Teziutlán, Puebla · Tel. 231-312-3979'],
                        folio: repVal.folio,
                        tagSub: `Fecha: ${fechaFormateada}`
                    })}

                    <div class="in-titulo"><span>Reporte por indisciplina</span></div>

                    <div class="in-texto">
                        <p><strong>Sr. (a):</strong> <span class="rp-fill">${repVal.tutor_nombre || 'S/N'}</span></p>
                        <p class="in-suscribe">El que suscribe Ing. Fausto Mauro Leyva Flores, Director del Bachillerato Tecnológico Interamericano.</p>
                        <p>POR ESTE CONDUCTO LE INFORMO QUE EL ALUMNO(A): <span class="rp-fill">${repVal.alumno_nombre}</span></p>
                        <p style="margin-bottom: 1.6mm;">INCURRIÓ A LA FALTA DE INDISCIPLINA YA QUE:</p>
                        <div class="in-incidente">${repVal.incidente}</div>
                        <p class="in-cierre">
                            ESPERAMOS SU VALIOSA CONTRIBUCIÓN PARA QUE SU HIJO (A) MEJORE EN SU COMPORTAMIENTO Y NOS AYUDE A SACARLO ADELANTE EN SU EDUCACIÓN, FORMÁNDOLO EN UN BUEN CIUDADADANO.
                        </p>
                    </div>

                    ${RP.firmas([
                        { rol: 'Nombre y firma del alumno' },
                        'sello',
                        { nombre: 'Ing. Fausto Leyva Flores', rol: 'Director' }
                    ], '14mm')}
                </div>
            `;
        }

        const win = window.open('', '', 'height=850,width=800');
        win.document.write(RP.documento(`Reporte de Indisciplina - ${rep.folio}`, renderBloque(rep), { margin: '15mm' }));
        win.document.close();
        win.focus();
        setTimeout(() => {
            win.print();
            win.close();
        }, 400);
    };

    // ==========================================
    // ACTAS ORDINARIO (BTI)
    // ==========================================

    /**
     * Combobox escribible reutilizable.
     *  - getItems(): [{ value, label, sub?, tag? }]
     *  - libre: si true, se acepta el texto escrito aunque no esté en la lista (asignatura).
     *  - onChange(item|null): se llama al elegir o al limpiar.
     */
    function crearCombo({ id, getItems, onChange = () => {}, libre = false, mono = false, remoto = null }) {
        const input = document.getElementById(`${id}Input`);
        const lista = document.getElementById(`${id}Lista`);
        const limpiar = document.getElementById(`${id}Limpiar`);
        const norm = (s) => String(s ?? '').normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase().replace(/\s+/g, ' ').trim();
        const esc = (v) => String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
        let seleccionado = null, visibles = [], activo = -1;
        // Búsqueda remota opcional (p. ej. alumnos → su grupo): se muestra como 2ª sección
        let remotos = [], remotoEstado = '', remotoTimer = null, remotoReq = 0;

        const abrir = (abierto) => { lista.style.display = abierto ? 'block' : 'none'; input.setAttribute('aria-expanded', String(abierto)); if (!abierto) activo = -1; };
        const syncLimpiar = () => { limpiar.style.display = input.value ? 'block' : 'none'; };

        function filtrar() {
            const q = norm(input.value);
            const filtro = (seleccionado && q === norm(seleccionado.label)) ? '' : q;
            const rank = (it) => {
                if (!filtro) return 0;
                const l = norm(it.label);
                if (l === filtro) return 0;
                if (l.startsWith(filtro)) return 1;
                if (l.split(' ').some(p => p.startsWith(filtro))) return 2;
                if (l.includes(filtro) || norm(it.sub).includes(filtro)) return 3;
                return -1;
            };
            const locales = getItems().map(it => ({ ...it, r: rank(it) })).filter(it => it.r >= 0)
                .sort((a, b) => a.r - b.r || (b.tag ? 1 : 0) - (a.tag ? 1 : 0) || a.label.localeCompare(b.label, 'es', { numeric: true }))
                .slice(0, 60);
            programarRemoto(filtro);
            visibles = [...locales, ...remotos];
            activo = filtro && visibles.length ? 0 : -1;
            pintar(filtro);
        }

        function programarRemoto(filtro) {
            if (!remoto) return;
            clearTimeout(remotoTimer);
            const req = ++remotoReq;
            if (filtro.length < 3) { remotos = []; remotoEstado = ''; return; }
            remotoEstado = 'buscando';
            remotoTimer = setTimeout(async () => {
                let res = [];
                try { res = await remoto(input.value.trim()); } catch (e) { res = null; }
                if (req !== remotoReq) return; // llegó una búsqueda más nueva
                remotos = (res || []).map(it => ({ ...it, seccion: 'remoto' }));
                remotoEstado = res === null ? 'error' : '';
                const locales = visibles.filter(it => it.seccion !== 'remoto');
                visibles = [...locales, ...remotos];
                if (activo < 0 && visibles.length) activo = 0;
                if (document.activeElement === input) pintar(norm(input.value));
            }, 280);
        }

        const opcionHtml = (it, i) => `
            <button type="button" role="option" data-i="${i}" aria-selected="${i === activo}" ${it.deshabilitado ? 'aria-disabled="true"' : ''}
                class="tw:flex tw:w-full tw:items-center tw:justify-between tw:gap-3 tw:border-0 tw:px-3 tw:py-2 tw:text-left tw:transition-colors ${it.deshabilitado ? 'tw:cursor-not-allowed tw:opacity-55' : 'tw:cursor-pointer'} ${i === activo ? 'tw:bg-brand-50' : 'tw:bg-transparent tw:hover:bg-slate-50'}">
                <span class="tw:min-w-0">
                    <span class="tw:block tw:truncate tw:text-[0.8125rem] tw:font-semibold tw:text-slate-900 ${mono && it.seccion !== 'remoto' ? 'tw:font-mono' : ''}">${esc(it.titulo ?? it.label)}</span>
                    ${it.sub ? `<span class="tw:block tw:truncate tw:text-[0.75rem] tw:text-slate-500">${it.subHtml ?? esc(it.sub)}</span>` : ''}
                </span>
                <span class="tw:flex tw:shrink-0 tw:items-center tw:gap-2">
                    ${it.tag ? `<span class=" ${it.tagTono || 'tw:bg-brand-50 tw:text-brand-700'} tw:px-2 tw:py-0.5 tw:text-[0.6875rem] tw:font-semibold">${esc(it.tag)}</span>` : ''}
                    ${seleccionado && seleccionado.value == it.value && it.seccion !== 'remoto' ? '<i data-lucide="check" class="tw:size-4 tw:text-brand-700"></i>' : ''}
                </span>
            </button>`;

        function pintar(filtro = '') {
            abrir(true);
            const locales = visibles.filter(it => it.seccion !== 'remoto');
            const seccionTitulo = (t) => `<div class="tw:px-3 tw:pt-2 tw:pb-1 tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-400">${t}</div>`;
            const aviso = (t) => `<div class="tw:px-3 tw:py-2.5 tw:text-center tw:text-[0.8125rem] tw:text-slate-500">${t}</div>`;
            let html = '';

            if (locales.length) {
                if (remoto && filtro) html += seccionTitulo(remoto.tituloLocal || 'Coincidencias');
                html += locales.map((it) => opcionHtml(it, visibles.indexOf(it))).join('');
            }
            if (remoto && filtro) {
                if (remotos.length) {
                    html += seccionTitulo(remoto.tituloRemoto || 'Más resultados');
                    html += remotos.map((it) => opcionHtml(it, visibles.indexOf(it))).join('');
                } else if (remotoEstado === 'buscando') {
                    html += aviso(`<span class="tw:mr-1.5 tw:inline-block tw:size-3 tw:animate-spin tw:rounded-full tw:border-2 tw:border-slate-200 tw:border-t-brand-700 tw:align-[-1px]"></span> ${remoto.textoBuscando || 'Buscando…'}`);
                } else if (remotoEstado === 'error') {
                    html += aviso('No se pudo completar la búsqueda.');
                } else if (!locales.length && filtro.length < 3) {
                    html += aviso(remoto.textoMinimo || 'Escribe al menos 3 letras para buscar.');
                }
            }
            if (!html) {
                html = aviso(filtro ? (libre ? 'Sin coincidencias: se usará el texto escrito.' : `Nada coincide con “${esc(input.value.trim())}”`) : 'No hay opciones disponibles');
            }
            lista.innerHTML = html;
            lista.querySelectorAll('[data-i]').forEach(b => b.addEventListener('mousedown', (e) => {
                e.preventDefault();
                const it = visibles[+b.dataset.i];
                if (!it?.deshabilitado) elegir(it);
            }));
            lista.querySelector('[aria-selected="true"]')?.scrollIntoView({ block: 'nearest' });
            if (window.lucide?.createIcons) lucide.createIcons();
        }

        function elegir(item, notificar = true) {
            // Un resultado remoto (alumno) se traduce a la opción real (su grupo)
            if (item && item.seccion === 'remoto') item = item.resolver ? item.resolver() : null;
            const cambio = (seleccionado?.value ?? null) !== (item?.value ?? null);
            seleccionado = item || null;
            input.value = item ? item.label : '';
            syncLimpiar();
            abrir(false);
            if (notificar && cambio) onChange(seleccionado);
        }

        input.addEventListener('focus', filtrar);
        input.addEventListener('input', () => {
            syncLimpiar();
            if (seleccionado && norm(input.value) !== norm(seleccionado.label)) { seleccionado = null; onChange(null); }
            filtrar();
        });
        input.addEventListener('keydown', (e) => {
            const abierto = lista.style.display !== 'none';
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
                if (!abierto) return filtrar();
                if (!visibles.length) return;
                activo = e.key === 'ArrowDown' ? (activo + 1) % visibles.length : (activo <= 0 ? visibles.length - 1 : activo - 1);
                pintar(norm(input.value));
            } else if (e.key === 'Enter' && abierto) {
                e.preventDefault();
                const it = visibles[activo >= 0 ? activo : 0];
                if (it && !it.deshabilitado) elegir(it);
                else if (!it) abrir(false);
            } else if (e.key === 'Escape') {
                abrir(false);
            }
        });
        input.addEventListener('blur', () => {
            if (!seleccionado && input.value.trim()) {
                const q = norm(input.value);
                const exacto = getItems().find(it => norm(it.label) === q);
                if (exacto) return elegir(exacto);
            }
            abrir(false);
        });
        limpiar.addEventListener('click', () => { elegir(null); input.focus(); });

        return {
            get valor() { return seleccionado; },
            /** Texto final: lo elegido, o lo escrito si el combo acepta texto libre. */
            get texto() { return seleccionado ? seleccionado.label : (libre ? input.value.trim() : ''); },
            elegir,
            input,
        };
    }

    /**
     * Búsqueda remota de alumnos (nombre o matrícula) para los campos "Grupo" de las actas:
     * al elegir un alumno se selecciona su grupo. idCentro: 2 = BTI, 3 = BGNE.
     */
    function buscarAlumnosParaGrupo(idCentro) {
        const fn = async (texto) => {
            const params = new URLSearchParams({ search: texto, limit: 8, page: 1, id_centro_trabajo: idCentro });
            const resp = await fetch(`/alumnos/lista?${params}`, { headers: { Accept: 'application/json' } }).then(r => r.json());
            const porId = new Map((window.gruposDb || []).map(g => [String(g.id), g]));
            return (resp?.data || []).map(al => {
                const nombre = `${al.apPaterno || ''} ${al.apMaterno || ''} ${al.nombre || ''}`.replace(/\s+/g, ' ').trim().toUpperCase();
                const idGrupo = al.idGrupo ?? al.id_grupo;
                const g = idGrupo != null ? porId.get(String(idGrupo)) : null;
                const clave = g?.clave || al.nombreGrupoTexto || '';
                const matricula = al.numeroControl ? `, ${al.numeroControl}` : '';
                const activo = String(al.statusAlumno || 'ACTIVO').toUpperCase() === 'ACTIVO';
                return {
                    value: `al-${al.idAlumno}`,
                    titulo: nombre,
                    sub: idGrupo != null && clave ? `Grupo ${clave}${matricula}` : `Sin grupo asignado${matricula}`,
                    subHtml: idGrupo != null && clave
                        ? `Grupo <span class="tw:font-mono tw:font-semibold tw:text-slate-700">${escGrupo(clave)}</span>${escGrupo(matricula)}`
                        : null,
                    tag: activo ? '' : al.statusAlumno,
                    tagTono: 'tw:bg-slate-100 tw:text-slate-600',
                    deshabilitado: idGrupo == null || !clave,
                    resolver: () => ({ value: String(idGrupo), label: clave, sub: g?.modalidadHorario || '', alumno: nombre }),
                };
            });
        };
        fn.tituloLocal = 'Grupos';
        fn.tituloRemoto = 'Alumnos';
        fn.textoBuscando = 'Buscando alumnos…';
        fn.textoMinimo = 'Escribe al menos 3 letras para buscar por nombre de alumno.';
        return fn;
    }

    const actas = {};

    function nombreDocente(d) {
        return `${d.nombreDocente || d.nombre || ''} ${d.apPaternoDocente || d.apPaterno || ''} ${d.apMaternoDocente || d.apMaterno || ''}`.replace(/\s+/g, ' ').trim();
    }
    const horariosDeGrupo = (idGrupo) => (window.horariosDb || []).filter(h => h.id_grupo == idGrupo);

    function initActasOrdinario() {
        if (!document.getElementById('actaGrupoInput')) return;

        actas.grupo = crearCombo({
            id: 'actaGrupo',
            mono: true,
            remoto: buscarAlumnosParaGrupo(2),
            getItems: () => (window.gruposDb || [])
                .filter(g => (g.id_centroTrabajo ?? g.idCentroTrabajo ?? g.id_centro_trabajo) == 2)
                .map(g => ({ value: String(g.id), label: g.clave, sub: g.modalidadHorario || '' })),
            onChange: onActaGrupoChange,
        });

        actas.docente = crearCombo({
            id: 'actaDocente',
            getItems: () => {
                const g = actas.grupo?.valor;
                const enGrupo = new Set(g ? horariosDeGrupo(g.value).map(h => String(h.id_docente)) : []);
                return (window.docentesDb || []).map(d => {
                    const id = String(d.idDocente || d.id);
                    return { value: id, label: nombreDocente(d), tag: enGrupo.has(id) ? 'En este grupo' : '' };
                }).filter(it => it.label);
            },
            onChange: (doc) => {
                // Si el docente imparte una sola materia en el grupo, se propone
                const g = actas.grupo.valor;
                if (!doc || !g || actas.materia.texto) return;
                const suyas = horariosDeGrupo(g.value).filter(h => h.id_docente == doc.value);
                const unicas = [...new Set(suyas.map(h => h.nombreMateria))];
                if (unicas.length === 1) actas.materia.elegir({ value: `h-${unicas[0]}`, label: unicas[0] }, false);
            },
        });

        actas.materia = crearCombo({
            id: 'actaMateria',
            libre: true,
            getItems: () => {
                const g = actas.grupo?.valor;
                const docentesPorId = new Map((window.docentesDb || []).map(d => [String(d.idDocente || d.id), nombreDocente(d)]));
                if (g) {
                    const vistos = new Map();
                    horariosDeGrupo(g.value).forEach(h => {
                        if (!vistos.has(h.nombreMateria)) vistos.set(h.nombreMateria, { value: `h-${h.nombreMateria}`, label: h.nombreMateria, sub: docentesPorId.get(String(h.id_docente)) || '', idDocente: h.id_docente });
                    });
                    if (vistos.size) return [...vistos.values()];
                }
                // Sin horarios: catálogo de materias del BTI
                const vistos = new Set();
                return (window.materiasDb || [])
                    .filter(m => (m.idCentroTrabajo ?? m.id_centroTrabajo) == 2 || (m.idCentroTrabajo ?? m.id_centroTrabajo) == null)
                    .map(m => ({ value: `m-${m.id}`, label: m.nombreMateria }))
                    .filter(it => it.label && !vistos.has(it.label) && vistos.add(it.label));
            },
            onChange: (mat) => {
                // Elegir la materia del horario propone a su docente si aún no hay uno
                if (mat?.idDocente && !actas.docente.valor) {
                    const d = (window.docentesDb || []).find(x => (x.idDocente || x.id) == mat.idDocente);
                    if (d) actas.docente.elegir({ value: String(d.idDocente || d.id), label: nombreDocente(d) }, false);
                }
            },
        });
    }

    let actaAlumnosReq = 0;
    function onActaGrupoChange(grupo) {
        const resumen = document.getElementById('actaResumen');
        const pintar = (icon, html, tono = 'tw:text-slate-500') => {
            resumen.className = `tw:m-0 tw:flex tw:items-center tw:gap-2 tw:text-[0.8125rem] ${tono}`;
            resumen.innerHTML = `<i data-lucide="${icon}" class="tw:size-4 tw:shrink-0"></i> ${html}`;
            if (window.lucide?.createIcons) lucide.createIcons();
        };
        if (!grupo) return pintar('info', 'Elige un grupo para ver cuántos alumnos aparecerán en el acta.');

        // Con un solo docente/materia en el horario del grupo, se proponen solos
        const hs = horariosDeGrupo(grupo.value);
        const materias = [...new Set(hs.map(h => h.nombreMateria))];
        if (materias.length === 1 && !actas.materia.texto) actas.materia.elegir({ value: `h-${materias[0]}`, label: materias[0] }, false);
        const docentes = [...new Set(hs.map(h => String(h.id_docente)))];
        if (docentes.length === 1 && !actas.docente.valor) {
            const d = (window.docentesDb || []).find(x => String(x.idDocente || x.id) === docentes[0]);
            if (d) actas.docente.elegir({ value: docentes[0], label: nombreDocente(d) }, false);
        }

        const req = ++actaAlumnosReq;
        pintar('loader', 'Consultando alumnos del grupo…');
        fetch(`/reportes/grupo/${encodeURIComponent(grupo.value)}/alumnos`, { headers: { Accept: 'application/json' } })
            .then(r => r.json())
            .then(resp => {
                if (req !== actaAlumnosReq) return; // llegó tarde: el usuario ya cambió de grupo
                const total = resp?.total ?? (resp?.data || []).length;
                total > 0
                    ? pintar('users-round', `<span><strong class="tw:font-semibold tw:text-slate-900">${total} alumnos activos</strong> en ${escGrupo(grupo.label)}${grupo.alumno ? ` (grupo de ${escGrupo(grupo.alumno)})` : ''} aparecerán en el acta.</span>`, 'tw:text-slate-600')
                    : pintar('triangle-alert', `El grupo ${grupo.label} no tiene alumnos activos; el acta saldrá sin nombres.`, 'tw:text-warning-600');
            })
            .catch(() => { if (req === actaAlumnosReq) pintar('circle-alert', 'No se pudo consultar el número de alumnos; el acta se generará igual.', 'tw:text-slate-500'); });
    }

    function generarActaOrdinarioPdf() {
        const grupo = actas.grupo?.valor;
        if (!grupo) {
            Swal.fire({ icon: 'warning', title: 'Elige un grupo', text: 'El acta se genera con los alumnos del grupo seleccionado.', confirmButtonColor: '#26687B' })
                .then(() => actas.grupo?.input.focus());
            return;
        }
        const fExamen = document.getElementById('actaFechaExamenInput').value;
        const fEntrega = document.getElementById('actaFechaEntregaInput').value;

        const params = new URLSearchParams({
            id_grupo: grupo.value,
            clave_grupo: grupo.label,
            docente_nombre: actas.docente.texto,
            materia: actas.materia.texto,
            examen: document.getElementById('actaExamenInput').value.trim(),
            fecha_examen: fExamen,
            fecha_entrega: fEntrega,
        });
        window.open(`/reportes/acta-ordinario-pdf?${params}`, '_blank');
    }

    document.addEventListener('DOMContentLoaded', initActasOrdinario);

    // ==========================================
    // ACTAS ORDINARIO (BGNE): por trimestre / módulo
    // ==========================================
    const actasBgne = {};
    const SEMANAS_POR_TRIMESTRE = 13; // igual que la lista de asistencia sabatina/dominical
    const SEMANAS_DE_CLASE = 12;      // fin = inicio + 12 semanas (último sábado/domingo del trimestre)

    /** Fecha de inicio del grupo en UTC (acepta "2025-11-29", "2025-11-29 00:00:00" o "Sat, 29 Nov 2025 00:00:00 GMT"). */
    function fechaInicioGrupo(g) {
        const v = g?.fechaInicio || g?.fecha_inicio || '';
        const m = String(v).match(/^(\d{4})-(\d{2})-(\d{2})/);
        if (m) return new Date(Date.UTC(+m[1], +m[2] - 1, +m[3]));
        const d = new Date(v);
        return isNaN(d) ? null : new Date(Date.UTC(d.getUTCFullYear(), d.getUTCMonth(), d.getUTCDate()));
    }
    const isoUTC = (d) => d.toISOString().slice(0, 10);
    const sumarSemanas = (d, s) => new Date(d.getTime() + s * 7 * 864e5);

    /** Precarga inicio/fin del trimestre (el usuario puede corregirlas). */
    function calcularFechasActaBgne() {
        const g = actasBgne.grupoObj;
        const t = parseInt(document.getElementById('actaBgneTrimestreSelect').value);
        const ini = document.getElementById('actaBgneFechaInicioInput');
        const fin = document.getElementById('actaBgneFechaFinInput');
        const base = fechaInicioGrupo(g);
        if (!base || !t) return;
        const inicio = sumarSemanas(base, (t - 1) * SEMANAS_POR_TRIMESTRE);
        ini.value = isoUTC(inicio);
        fin.value = isoUTC(sumarSemanas(inicio, SEMANAS_DE_CLASE));
    }

    function initActasBgne() {
        if (!document.getElementById('actaBgneGrupoInput')) return;
        const grupoPorId = (id) => (window.gruposDb || []).find(g => String(g.id) === String(id));
        const docentesPorId = () => new Map((window.docentesDb || []).map(d => [String(d.idDocente || d.id), nombreDocente(d)]));

        actasBgne.grupo = crearCombo({
            id: 'actaBgneGrupo',
            mono: true,
            remoto: buscarAlumnosParaGrupo(3),
            getItems: () => (window.gruposDb || [])
                .filter(g => (g.id_centroTrabajo ?? g.idCentroTrabajo ?? g.id_centro_trabajo) == 3)
                .map(g => ({ value: String(g.id), label: g.clave, sub: g.modalidadHorario || '' })),
            onChange: (item) => {
                actasBgne.grupoObj = item ? grupoPorId(item.value) : null;
                // El trimestre del grupo (BGNE: id_nivel_academico 1–6)
                const nivel = parseInt(actasBgne.grupoObj?.id_nivel_academico);
                if (nivel >= 1 && nivel <= 6) document.getElementById('actaBgneTrimestreSelect').value = String(nivel);
                calcularFechasActaBgne();
                resumenActaBgne(item);
            },
        });

        actasBgne.materia = crearCombo({
            id: 'actaBgneMateria',
            libre: true,
            getItems: () => {
                const t = parseInt(document.getElementById('actaBgneTrimestreSelect').value);
                const g = actasBgne.grupo?.valor;
                const docs = docentesPorId();
                const hs = g ? horariosDeGrupo(g.value) : [];
                const vistos = new Set();
                return (window.materiasDb || [])
                    .filter(m => (m.idCentroTrabajo ?? m.id_centroTrabajo) == 3)
                    .filter(m => !t || parseInt(m.id_nivel_academico) === t)
                    .map(m => {
                        const h = hs.find(x => x.id_materia == m.id);
                        return { value: `m-${m.id}`, label: m.nombreMateria, sub: h ? (docs.get(String(h.id_docente)) || '') : '', tag: h ? 'En este grupo' : '', idDocente: h?.id_docente };
                    })
                    .filter(it => it.label && !vistos.has(it.label) && vistos.add(it.label));
            },
            onChange: (mat) => {
                // Si la materia está en el horario del grupo, se propone su docente
                if (mat?.idDocente && !actasBgne.docente.texto) {
                    const nombre = docentesPorId().get(String(mat.idDocente));
                    if (nombre) actasBgne.docente.elegir({ value: String(mat.idDocente), label: nombre }, false);
                }
            },
        });

        actasBgne.docente = crearCombo({
            id: 'actaBgneDocente',
            libre: true,
            getItems: () => {
                const g = actasBgne.grupo?.valor;
                const enGrupo = new Set(g ? horariosDeGrupo(g.value).map(h => String(h.id_docente)) : []);
                return (window.docentesDb || []).map(d => {
                    const id = String(d.idDocente || d.id);
                    return { value: id, label: nombreDocente(d), tag: enGrupo.has(id) ? 'En este grupo' : '' };
                }).filter(it => it.label);
            },
        });
    }

    function onActaBgneTrimestreChange() {
        calcularFechasActaBgne();
        // La asignatura elegida puede no pertenecer al nuevo trimestre
        const mat = actasBgne.materia?.valor;
        const t = parseInt(document.getElementById('actaBgneTrimestreSelect').value);
        if (mat && t) {
            const m = (window.materiasDb || []).find(x => `m-${x.id}` === mat.value);
            if (m && parseInt(m.id_nivel_academico) !== t) actasBgne.materia.elegir(null);
        }
    }

    let actaBgneReq = 0;
    function resumenActaBgne(grupo) {
        const resumen = document.getElementById('actaBgneResumen');
        const pintar = (icon, html, tono = 'tw:text-slate-500') => {
            resumen.className = `tw:m-0 tw:flex tw:items-center tw:gap-2 tw:text-[0.8125rem] ${tono}`;
            resumen.innerHTML = `<i data-lucide="${icon}" class="tw:size-4 tw:shrink-0"></i> ${html}`;
            if (window.lucide?.createIcons) lucide.createIcons();
        };
        if (!grupo) return pintar('info', 'Elige un grupo para ver cuántos alumnos aparecerán en el acta.');
        if (!fechaInicioGrupo(actasBgne.grupoObj)) {
            // Sin fecha de inicio no se pueden calcular las del trimestre
            pintar('triangle-alert', `El grupo ${escGrupo(grupo.label)} no tiene fecha de inicio: captura las fechas del trimestre a mano.`, 'tw:text-warning-600');
        }
        const req = ++actaBgneReq;
        fetch(`/reportes/grupo/${encodeURIComponent(grupo.value)}/alumnos`, { headers: { Accept: 'application/json' } })
            .then(r => r.json())
            .then(resp => {
                if (req !== actaBgneReq || !fechaInicioGrupo(actasBgne.grupoObj)) return;
                const total = resp?.total ?? (resp?.data || []).length;
                total > 0
                    ? pintar('users-round', `<span><strong class="tw:font-semibold tw:text-slate-900">${total} alumnos activos</strong> en ${escGrupo(grupo.label)}${grupo.alumno ? ` (grupo de ${escGrupo(grupo.alumno)})` : ''} aparecerán en el acta.</span>`, 'tw:text-slate-600')
                    : pintar('triangle-alert', `El grupo ${escGrupo(grupo.label)} no tiene alumnos activos; el acta saldrá sin nombres.`, 'tw:text-warning-600');
            })
            .catch(() => {});
    }

    function generarActaBgnePdf() {
        const grupo = actasBgne.grupo?.valor;
        const trimestre = document.getElementById('actaBgneTrimestreSelect').value;
        const faltan = [!grupo && 'el grupo', !trimestre && 'el trimestre'].filter(Boolean);
        if (faltan.length) {
            Swal.fire({ icon: 'warning', title: 'Faltan datos', text: `Elige ${faltan.join(' y ')} para generar el acta.`, confirmButtonColor: '#26687B' });
            return;
        }
        const ini = document.getElementById('actaBgneFechaInicioInput').value;
        const fin = document.getElementById('actaBgneFechaFinInput').value;
        if (ini && fin && fin < ini) {
            Swal.fire({ icon: 'warning', title: 'Fechas inválidas', text: 'La fecha de fin no puede ser anterior a la de inicio.', confirmButtonColor: '#26687B' });
            return;
        }
        const params = new URLSearchParams({
            id_grupo: grupo.value,
            clave_grupo: grupo.label,
            trimestre,
            materia: actasBgne.materia.texto,
            docente_nombre: actasBgne.docente.texto,
            fecha_inicio: ini,
            fecha_fin: fin,
            fecha_entrega: document.getElementById('actaBgneFechaEntregaInput').value,
        });
        window.open(`/reportes/acta-ordinario-bgne-pdf?${params}`, '_blank');
    }

    document.addEventListener('DOMContentLoaded', initActasBgne);

    // ==========================================
    // GRUPO (Lista de asistencias): combobox escribible
    // El <select> oculto sigue siendo la fuente de verdad; este input solo lo controla.
    // ==========================================
    let grupoAsistenciaActivo = -1;   // índice resaltado con el teclado
    let grupoAsistenciaOpciones = []; // [{ id, clave, modalidad, texto }]

    const normGrupo = (s) => String(s ?? '').normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase().replace(/\s+/g, ' ').trim();
    const escGrupo = (v) => String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

    function opcionesGrupoAsistencia() {
        const sel = document.getElementById('asistenciaGrupoSelect');
        if (!sel) return [];
        const porId = new Map((window.gruposDb || []).map(g => [String(g.id), g]));
        return Array.from(sel.options).filter(o => o.value).map(o => {
            const g = porId.get(String(o.value)) || {};
            return { id: o.value, clave: g.clave || o.text, modalidad: g.modalidadHorario || '', texto: o.text };
        });
    }

    /** Muestra en el input el grupo que tenga el select (o lo vacía). */
    function sincronizarInputGrupoAsistencia() {
        const sel = document.getElementById('asistenciaGrupoSelect');
        const input = document.getElementById('asistenciaGrupoInput');
        const btn = document.getElementById('btn-limpiar-asistencia-grupo');
        if (!sel || !input) return;
        const opt = sel.value ? sel.options[sel.selectedIndex] : null;
        input.value = opt ? opt.text : '';
        if (btn) btn.style.display = input.value ? 'block' : 'none';
        cerrarSugerenciasGrupoAsistencia();
    }

    function cerrarSugerenciasGrupoAsistencia() {
        const div = document.getElementById('asistencia-grupo-sugerencia');
        const input = document.getElementById('asistenciaGrupoInput');
        if (div) div.style.display = 'none';
        if (input) input.setAttribute('aria-expanded', 'false');
        grupoAsistenciaActivo = -1;
    }

    function buscarGruposAsistencia(query = '') {
        const div = document.getElementById('asistencia-grupo-sugerencia');
        const input = document.getElementById('asistenciaGrupoInput');
        if (!div) return;

        const sel = document.getElementById('asistenciaGrupoSelect');
        const q = normGrupo(query);
        // Si el texto es el del grupo ya elegido, mostrar la lista completa
        const textoElegido = sel && sel.value ? normGrupo(sel.options[sel.selectedIndex].text) : null;
        const filtro = (q && q !== textoElegido) ? q : '';

        // Coincidencias por clave primero (empieza con > contiene), luego por modalidad
        const rank = (o) => {
            const c = normGrupo(o.clave);
            if (!filtro) return 0;
            if (c === filtro) return 0;
            if (c.startsWith(filtro)) return 1;
            if (c.includes(filtro)) return 2;
            if (normGrupo(o.texto).includes(filtro)) return 3;
            return -1;
        };
        grupoAsistenciaOpciones = opcionesGrupoAsistencia()
            .map(o => ({ ...o, r: rank(o) }))
            .filter(o => o.r >= 0)
            .sort((a, b) => a.r - b.r || a.clave.localeCompare(b.clave, 'es', { numeric: true }));

        div.style.display = 'block';
        input?.setAttribute('aria-expanded', 'true');

        if (grupoAsistenciaOpciones.length === 0) {
            grupoAsistenciaActivo = -1;
            div.innerHTML = `<div class="tw:px-3 tw:py-3 tw:text-center tw:text-[0.8125rem] tw:text-slate-500">${
                opcionesGrupoAsistencia().length ? `Ningún grupo coincide con “${escGrupo(query.trim())}”` : 'No hay grupos para este plantel'}</div>`;
            return;
        }

        grupoAsistenciaActivo = filtro ? 0 : -1;
        renderSugerenciasGrupoAsistencia();
    }

    function renderSugerenciasGrupoAsistencia() {
        const div = document.getElementById('asistencia-grupo-sugerencia');
        const sel = document.getElementById('asistenciaGrupoSelect');
        div.innerHTML = grupoAsistenciaOpciones.slice(0, 60).map((o, i) => `
            <button type="button" role="option" data-id="${escGrupo(o.id)}" id="grupo-asist-opt-${i}" aria-selected="${i === grupoAsistenciaActivo}"
                class="tw:flex tw:w-full tw:cursor-pointer tw:items-center tw:justify-between tw:gap-3 tw:border-0 tw:px-3 tw:py-2 tw:text-left tw:transition-colors ${i === grupoAsistenciaActivo ? 'tw:bg-brand-50' : 'tw:bg-transparent tw:hover:bg-slate-50'}">
                <span class="tw:font-mono tw:text-[0.8125rem] tw:font-semibold tw:text-slate-900">${escGrupo(o.clave)}</span>
                <span class="tw:flex tw:items-center tw:gap-2">
                    ${o.modalidad ? `<span class="tw:truncate tw:text-[0.75rem] tw:text-slate-500">${escGrupo(o.modalidad)}</span>` : ''}
                    ${sel && sel.value == o.id ? '<i data-lucide="check" class="tw:size-4 tw:text-brand-700"></i>' : ''}
                </span>
            </button>`).join('');

        // mousedown evita que el blur del input cierre la lista antes del click
        div.querySelectorAll('button[data-id]').forEach(b => {
            b.addEventListener('mousedown', (e) => { e.preventDefault(); seleccionarGrupoAsistencia(b.dataset.id); });
        });
        div.querySelector('[aria-selected="true"]')?.scrollIntoView({ block: 'nearest' });
        if (window.lucide?.createIcons) lucide.createIcons();
    }

    function seleccionarGrupoAsistencia(id) {
        const sel = document.getElementById('asistenciaGrupoSelect');
        if (!sel) return;
        const cambio = sel.value != id;
        sel.value = id;
        sincronizarInputGrupoAsistencia();
        if (cambio) onAsistenciaGrupoChange();
    }

    function onGrupoAsistenciaInput(valor) {
        const sel = document.getElementById('asistenciaGrupoSelect');
        const btn = document.getElementById('btn-limpiar-asistencia-grupo');
        if (btn) btn.style.display = valor ? 'block' : 'none';

        // Si el texto ya no corresponde al grupo elegido, se deselecciona
        if (sel && sel.value && normGrupo(valor) !== normGrupo(sel.options[sel.selectedIndex].text)) {
            sel.value = '';
            onAsistenciaGrupoChange();
        }
        buscarGruposAsistencia(valor);
    }

    function onGrupoAsistenciaKeydown(e) {
        const div = document.getElementById('asistencia-grupo-sugerencia');
        const abierta = div && div.style.display !== 'none';
        const n = Math.min(grupoAsistenciaOpciones.length, 60);

        if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
            e.preventDefault();
            if (!abierta) return buscarGruposAsistencia(e.target.value);
            if (!n) return;
            grupoAsistenciaActivo = e.key === 'ArrowDown'
                ? (grupoAsistenciaActivo + 1) % n
                : (grupoAsistenciaActivo <= 0 ? n - 1 : grupoAsistenciaActivo - 1);
            renderSugerenciasGrupoAsistencia();
        } else if (e.key === 'Enter') {
            if (!abierta) return;
            e.preventDefault();
            const o = grupoAsistenciaOpciones[grupoAsistenciaActivo >= 0 ? grupoAsistenciaActivo : 0];
            if (o) seleccionarGrupoAsistencia(o.id);
        } else if (e.key === 'Escape') {
            cerrarSugerenciasGrupoAsistencia();
        } else if (e.key === 'Tab' && abierta && grupoAsistenciaActivo >= 0) {
            const o = grupoAsistenciaOpciones[grupoAsistenciaActivo];
            if (o) seleccionarGrupoAsistencia(o.id);
        }
    }

    /** Al salir del campo: si lo escrito es exactamente una clave, se elige; si no, se restaura. */
    function onGrupoAsistenciaBlur() {
        const sel = document.getElementById('asistenciaGrupoSelect');
        const input = document.getElementById('asistenciaGrupoInput');
        if (!sel || !input) return;
        const q = normGrupo(input.value);
        if (!sel.value && q) {
            const exactas = opcionesGrupoAsistencia().filter(o => normGrupo(o.clave) === q || normGrupo(o.texto) === q);
            if (exactas.length === 1) return seleccionarGrupoAsistencia(exactas[0].id);
        }
        cerrarSugerenciasGrupoAsistencia();
    }

    function limpiarGrupoAsistencia() {
        const sel = document.getElementById('asistenciaGrupoSelect');
        const input = document.getElementById('asistenciaGrupoInput');
        const teniaGrupo = sel && sel.value;
        if (sel) sel.value = '';
        sincronizarInputGrupoAsistencia();
        if (teniaGrupo) onAsistenciaGrupoChange();
        input?.focus();
    }

    // Cerrar sugerencias al hacer click fuera
    document.addEventListener('click', function(e) {
        const sugDoc = document.getElementById('asistencia-docente-sugerencia');
        const inputDoc = document.getElementById('asistenciaDocenteInput');
        const btnClearDoc = document.getElementById('btn-limpiar-asistencia-docente');
        if (sugDoc && inputDoc && !sugDoc.contains(e.target) && !inputDoc.contains(e.target) && (!btnClearDoc || !btnClearDoc.contains(e.target))) {
            sugDoc.style.display = 'none';
        }

        const sugMat = document.getElementById('asistencia-materia-sugerencia');
        const inputMat = document.getElementById('asistenciaMateriaInput');
        const cicloSel = document.getElementById('asistenciaCicloSelect');
        const btnClearMat = document.getElementById('btn-limpiar-asistencia-materia');
        if (sugMat && inputMat && !sugMat.contains(e.target) && !inputMat.contains(e.target) && (!cicloSel || !cicloSel.contains(e.target)) && (!btnClearMat || !btnClearMat.contains(e.target))) {
            sugMat.style.display = 'none';
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        if (typeof asegurarCatalogosAsistencia === 'function') {
            asegurarCatalogosAsistencia();
        }
        // Pre-seleccionar BTI por defecto para mostrar directamente el área de trabajo
        selectCCT('BTI');
    });
</script>

@endsection
