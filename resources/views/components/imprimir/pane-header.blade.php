{{-- Encabezado de cada formato del Módulo de Impresión: título y para qué sirve. --}}
@props(['icon' => null, 'title'])

<header class="tw:mb-6 tw:flex tw:flex-wrap tw:items-start tw:justify-between tw:gap-4 tw:border-b tw:border-slate-200 tw:pb-4">
    <div class="tw:min-w-0">
        <h2 class="tw:m-0 tw:text-[1.25rem] tw:font-semibold tw:leading-tight tw:text-slate-900">{{ $title }}</h2>
        <p {{ $attributes->merge(['class' => 'tw:m-0 tw:mt-1 tw:max-w-[62ch] tw:text-[0.9375rem] tw:text-slate-600']) }}>{{ $slot }}</p>
    </div>
    {{ $aside ?? '' }}
</header>
