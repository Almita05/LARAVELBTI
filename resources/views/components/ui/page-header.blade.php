{{--
    Encabezado de página con título sobrio (Public Sans semibold).
    Slots: default (descripción, opcional), actions (controles a la derecha, opcional).
    Uso: <x-ui.page-header title="Módulo de impresión" :back="url()->previous()">Descripción…</x-ui.page-header>
--}}
@props(['title', 'back' => null])

<header {{ $attributes->merge(['class' => 'tw:flex tw:flex-col tw:gap-4 tw:lg:flex-row tw:lg:items-end tw:lg:justify-between']) }}>
    <div class="tw:flex tw:items-start tw:gap-3">
        @if($back)
            <a href="{{ $back }}" aria-label="Regresar"
               class="tw:mt-1 tw:inline-flex tw:size-9 tw:shrink-0 tw:items-center tw:justify-center tw:border tw:border-slate-300 tw:bg-white tw:text-slate-600 tw:no-underline tw:hover:bg-slate-50 tw:hover:text-slate-900 tw:focus-visible:outline-2 tw:focus-visible:outline-offset-2 tw:focus-visible:outline-brand-700">
                <i data-lucide="arrow-left" class="tw:size-[18px]" aria-hidden="true"></i>
            </a>
        @endif
        <div>
            <h1 class="tw:m-0 tw:text-[1.625rem] tw:font-semibold tw:leading-tight tw:tracking-[-0.01em] tw:text-slate-900">{{ $title }}</h1>
            @if(trim($slot) !== '')
                <p class="tw:m-0 tw:mt-1 tw:max-w-[62ch] tw:text-[0.9375rem] tw:text-slate-600">{{ $slot }}</p>
            @endif
        </div>
    </div>
    {{ $actions ?? '' }}
</header>
