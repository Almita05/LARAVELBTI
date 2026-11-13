@extends('layouts.app')

@section('content')

@php
    /*
     * Planes de estudio BTI: material por semestre alojado en Google Drive.
     * Para agregar o cambiar una materia basta con editar esta lista.
     * El ID del archivo se toma del enlace de Drive para ofrecer "Abrir" y "Descargar".
     */
    $semestres = [
        1 => [
            ['Álgebra',                    'https://drive.google.com/file/d/1_P48AFfUycZ1XxjEZkc6-WsfwcdJQSgl/view?usp=sharing'],
            ['Química I',                  'https://drive.google.com/file/d/1sj8UxdlvtttIBhE5b58yX129Vx-qOUTZ/view?usp=drive_link'],
            ['Lógica',                     'https://drive.google.com/file/d/1sTNbGfGfgD28TvN2NuiGDSEv04K6dgXy/view?usp=drive_link'],
            ['Lectura y Expresión I',      'https://drive.google.com/file/d/1CNyn7VTVRvPGWGF5sns0EtvgDGxoo7lp/view?usp=drive_link'],
            ['Informática I',              'https://drive.google.com/file/d/1cnglulpBivDMMvngRPkQ42gYZTGew2v_/view?usp=drive_link'],
            ['Inglés I',                   'https://drive.google.com/file/d/1OS4yF6v-BwqoYooPjKAb5h-zvkOthLIT/view?usp=drive_link'],
        ],
        2 => [
            ['Geometría y Trigonometría',  'https://drive.google.com/file/d/1dFb3PRwQY5wfeXKvWOlNuMb2IBs_wDeo/view?usp=drive_link'],
            ['Ética',                      'https://drive.google.com/file/d/1o6ZKUfNTd3efaOonATtcmQo8o3H59HO6/view?usp=drive_link'],
            ['Inglés II',                  'https://drive.google.com/file/d/1JE9YqD7DFtfMkVDV1k_ru0XCC0OrpF35/view?usp=sharing'],
            ['Lectura y Expresión II',     'https://drive.google.com/file/d/1JQMFZZ2upT_1U14Ah0CV6nkSLTDnffuq/view?usp=drive_link'],
        ],
        3 => [
            ['Geometría Analítica',        'https://drive.google.com/file/d/133T5c_iK_Kx63TtZfnj42Y3bGvjWS3uF/view?usp=drive_link'],
            ['Biología',                   'https://drive.google.com/file/d/17tUp_LZrl8ftqtyFmElZOvZxuKAyxsD_/view?usp=drive_link'],
            ['Inglés III',                 'https://drive.google.com/file/d/1kUJ_1RR0KX9GrkLpbWNb9h3PGQESQeyQ/view?usp=drive_link'],
            ['Ética',                      'https://drive.google.com/file/d/1oSyAzGErV-iWUrebv9YjvZUoaGwvp9vx/view?usp=drive_link'],
        ],
        4 => [
            ['Cálculo Diferencial',        'https://drive.google.com/file/d/1C_7yhlPNQ163Zo_lQyj-ShdqQD1IlGbh/view?usp=drive_link'],
            ['Inglés',                     'https://drive.google.com/file/d/1h4K_sgWpesi1xHRiPGpn-bo8mtkY7w0F/view?usp=drive_link'],
            ['Física',                     'https://drive.google.com/file/d/1SfQsHRKT3fOMP8k1sOqbsIt5a0G0puAG/view?usp=drive_link'],
            ['Ecología',                   'https://drive.google.com/file/d/1zaUyLjmlsU_sZJqPw19YyNFZu1LhNhCN/view?usp=drive_link'],
        ],
        5 => [
            ['Cálculo Integral',           'https://drive.google.com/file/d/188ZIGgqDWO_V6pcb5EhK-1l-ZE6kiNQD/view?usp=drive_link'],
            ['CTSyV',                      'https://drive.google.com/file/d/1-wUxXGcRQs_EXXogsrcifZtJev2W_7WO/view?usp=drive_link'],
            ['Física II',                  'https://drive.google.com/file/d/1pCucIgw7vR8luqhr3UFj_1kuCvemEbW1/view?usp=drive_link'],
        ],
        6 => [
            ['Probabilidad y Estadística', 'https://drive.google.com/file/d/1kIFUju6pof0LEW1lOej4WWJAlwx0MnBM/view?usp=drive_link'],
            ['Filosofía',                  'https://drive.google.com/file/d/16nZubuScda1uPkkyxZGN11bKLKy8KQbd/view?usp=drive_link'],
        ],
    ];
    $ordinales = [1 => '1er', 2 => '2do', 3 => '3er', 4 => '4to', 5 => '5to', 6 => '6to'];
    $idDrive = fn ($url) => preg_match('#/file/d/([^/]+)#', $url, $m) ? $m[1] : null;
    $totalMaterias = array_sum(array_map('count', $semestres));

    $card = ' tw:border tw:border-slate-200 tw:bg-white tw:shadow-card';
    $tab  = 'tw:inline-flex tw:shrink-0 tw:cursor-pointer tw:items-center tw:gap-2 tw:border-0 tw:bg-transparent tw:px-3.5 tw:py-2 tw:text-[0.8125rem] tw:font-medium tw:whitespace-nowrap tw:text-slate-600 tw:transition tw:hover:bg-slate-100 tw:hover:text-slate-900 tw:aria-selected:bg-brand-700 tw:aria-selected:font-semibold tw:aria-selected:text-white tw:aria-selected:shadow-brand tw:focus-visible:outline-2 tw:focus-visible:outline-offset-2 tw:focus-visible:outline-brand-500';
    $btn  = 'tw:inline-flex tw:h-9 tw:flex-1 tw:items-center tw:justify-center tw:gap-1.5 tw:text-[0.8125rem] tw:font-semibold tw:no-underline tw:transition tw:focus-visible:outline-2 tw:focus-visible:outline-offset-2 tw:focus-visible:outline-brand-500';
@endphp

<div class="tw:mx-auto tw:flex tw:max-w-[1600px] tw:flex-col tw:gap-6">

    {{-- ===================== ENCABEZADO ===================== --}}
    <header class="tw:flex tw:flex-col tw:gap-4 tw:lg:flex-row tw:lg:items-end tw:lg:justify-between">
        <div>
            <h1 class="tw:m-0 tw:text-[1.5rem] tw:font-bold tw:tracking-tight tw:text-slate-900">Planes de estudio BTI</h1>
            <p class="tw:m-0 tw:mt-1 tw:text-sm tw:text-slate-500">Material de estudio por semestre, {{ $totalMaterias }} asignaturas en 6 semestres.</p>
        </div>
        <div class="tw:relative tw:w-full tw:sm:w-80">
            <i data-lucide="search" class="tw:pointer-events-none tw:absolute tw:left-3 tw:top-1/2 tw:size-4 tw:-translate-y-1/2 tw:text-slate-400"></i>
            <input type="search" id="buscadorPlanes" aria-label="Buscar asignatura en todos los semestres"
                class="tw:h-10 tw:w-full tw:border tw:border-slate-200 tw:bg-white tw:pr-3 tw:pl-9 tw:text-sm tw:text-slate-900 tw:shadow-xs tw:outline-none tw:transition tw:placeholder:text-slate-400 tw:focus:border-brand-500 tw:focus:ring-4 tw:focus:ring-brand-500/10"
                placeholder="Buscar asignatura…">
        </div>
    </header>

    <section class="{{ $card }} tw:overflow-hidden">
        {{-- Semestres --}}
        <nav id="tabsSemestres" role="tablist" aria-label="Semestres"
            class="tw:flex tw:gap-1 tw:overflow-x-auto tw:border-b tw:border-slate-100 tw:px-4 tw:py-3 tw:[scrollbar-width:none]">
            @foreach ($semestres as $num => $materias)
                <button type="button" role="tab" class="{{ $tab }}" id="tab-sem-{{ $num }}" data-semestre="{{ $num }}"
                    aria-selected="{{ $num === 1 ? 'true' : 'false' }}" aria-controls="panel-semestres" tabindex="{{ $num === 1 ? '0' : '-1' }}">
                    {{ $ordinales[$num] }} semestre
                    <span class="tw:rounded-none tw:bg-current/15 tw:px-1.5 tw:text-[0.6875rem] tw:font-semibold tw:tabular-nums">{{ count($materias) }}</span>
                </button>
            @endforeach
        </nav>

        <div id="panel-semestres" role="tabpanel" aria-labelledby="tab-sem-1" class="tw:p-5">
            <p id="avisoBusqueda" class="tw:m-0 tw:mb-4 tw:text-[0.8125rem] tw:text-slate-500" hidden></p>

            <ul id="gridMaterias" class="tw:m-0 tw:grid tw:list-none tw:grid-cols-1 tw:gap-4 tw:p-0 tw:sm:grid-cols-2 tw:xl:grid-cols-3">
                @foreach ($semestres as $num => $materias)
                    @foreach ($materias as [$nombre, $url])
                        @php $id = $idDrive($url); @endphp
                        {{-- El <li> no lleva utilidades de display: el JS lo oculta con `hidden` (Tailwind es !important) --}}
                        <li data-semestre="{{ $num }}" data-nombre="{{ $nombre }}" @if($num !== 1) hidden @endif>
                          <article class="tw:flex tw:h-full tw:flex-col tw:gap-4 tw:border tw:border-slate-200 tw:bg-white tw:p-4 tw:transition tw:hover:border-brand-200 tw:hover:shadow-card">
                            <div class="tw:flex tw:items-start tw:gap-3">
                                <span class="tw:inline-flex tw:size-10 tw:shrink-0 tw:items-center tw:justify-center tw:bg-brand-50 tw:text-brand-700 tw:ring-1 tw:ring-brand-200/70">
                                    <i data-lucide="book-open-text" class="tw:size-5"></i>
                                </span>
                                <div class="tw:min-w-0">
                                    <h2 class="tw:m-0 tw:text-[0.9375rem] tw:font-semibold tw:leading-snug tw:text-slate-900">{{ $nombre }}</h2>
                                    <p class="tw:m-0 tw:mt-0.5 tw:text-xs tw:text-slate-500">{{ $ordinales[$num] }} semestre, Google Drive</p>
                                </div>
                            </div>
                            <div class="tw:mt-auto tw:flex tw:gap-2">
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                                    class="{{ $btn }} tw:border tw:border-slate-200 tw:bg-white tw:text-slate-700 tw:hover:bg-slate-50 tw:hover:text-slate-900"
                                    aria-label="Abrir material de {{ $nombre }} ({{ $ordinales[$num] }} semestre) en una pestaña nueva">
                                    <i data-lucide="external-link" class="tw:size-4"></i> Abrir
                                </a>
                                @if ($id)
                                    <a href="https://drive.google.com/uc?export=download&id={{ $id }}" target="_blank" rel="noopener noreferrer"
                                        class="{{ $btn }} tw:border-0 tw:bg-brand-700 tw:text-white tw:hover:bg-brand-800 tw:hover:text-white"
                                        aria-label="Descargar material de {{ $nombre }} ({{ $ordinales[$num] }} semestre)">
                                        <i data-lucide="download" class="tw:size-4"></i> Descargar
                                    </a>
                                @endif
                            </div>
                          </article>
                        </li>
                    @endforeach
                @endforeach
            </ul>

            <div id="sinResultados" hidden>
              <div class="tw:flex tw:flex-col tw:items-center tw:px-6 tw:py-14 tw:text-center">
                <span class="tw:inline-flex tw:size-11 tw:items-center tw:justify-center tw:bg-slate-100 tw:text-slate-400"><i data-lucide="search-x" class="tw:size-5"></i></span>
                <p class="tw:m-0 tw:mt-3 tw:text-[0.8125rem] tw:text-slate-500">Ninguna asignatura coincide con la búsqueda.</p>
              </div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tabs = [...document.querySelectorAll('#tabsSemestres [role="tab"]')];
    const tarjetas = [...document.querySelectorAll('#gridMaterias > li')];
    const buscador = document.getElementById('buscadorPlanes');
    const aviso = document.getElementById('avisoBusqueda');
    const panel = document.getElementById('panel-semestres');
    const normalizar = (s) => String(s ?? '').normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase().trim();
    let semestre = '1';

    function render() {
        const q = normalizar(buscador.value);
        let visibles = 0;
        tarjetas.forEach(li => {
            // Con búsqueda se buscan en todos los semestres; sin ella, solo el semestre elegido
            const ok = q ? normalizar(li.dataset.nombre).includes(q) : li.dataset.semestre === semestre;
            li.hidden = !ok;
            if (ok) visibles++;
        });
        aviso.hidden = !q;
        aviso.textContent = q ? `${visibles} ${visibles === 1 ? 'resultado' : 'resultados'} en todos los semestres` : '';
        document.getElementById('sinResultados').hidden = visibles > 0;
        tabs.forEach(t => t.classList.toggle('tw:opacity-50', !!q));
    }

    function seleccionar(tab, foco = false) {
        semestre = tab.dataset.semestre;
        tabs.forEach(t => {
            const activo = t === tab;
            t.setAttribute('aria-selected', String(activo));
            t.tabIndex = activo ? 0 : -1;
        });
        panel.setAttribute('aria-labelledby', tab.id);
        buscador.value = '';
        if (foco) tab.focus();
        render();
    }

    tabs.forEach(tab => tab.addEventListener('click', () => seleccionar(tab)));
    // Teclado: ← → entre semestres (patrón de pestañas accesibles)
    document.getElementById('tabsSemestres').addEventListener('keydown', (e) => {
        const i = tabs.indexOf(document.activeElement);
        if (i < 0) return;
        if (e.key === 'ArrowRight') { e.preventDefault(); seleccionar(tabs[(i + 1) % tabs.length], true); }
        if (e.key === 'ArrowLeft')  { e.preventDefault(); seleccionar(tabs[(i - 1 + tabs.length) % tabs.length], true); }
    });
    buscador.addEventListener('input', render);
});
</script>

@endsection
