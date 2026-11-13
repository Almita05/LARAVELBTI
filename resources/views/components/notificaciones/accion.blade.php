{{-- Botón de acción con solo icono + tooltip (CSS) al pasar el ratón o enfocar --}}
@props(['icon', 'label', 'href' => null, 'tone' => 'neutral'])

@php
    $tones = [
        'brand'   => 'tw:text-brand-700 tw:hover:bg-brand-50 tw:hover:text-brand-800 tw:hover:ring-brand-200',
        'success' => 'tw:text-success-700 tw:hover:bg-success-50 tw:hover:ring-success-200',
        'neutral' => 'tw:text-slate-500 tw:hover:bg-slate-100 tw:hover:text-slate-800 tw:hover:ring-slate-200',
    ];
    $classes = 'tw:group/act tw:relative tw:inline-flex tw:size-9 tw:cursor-pointer tw:items-center tw:justify-center tw:border-0 tw:bg-transparent tw:no-underline tw:ring-1 tw:ring-transparent tw:transition-all tw:duration-150 tw:focus-visible:outline-2 tw:focus-visible:outline-offset-2 tw:focus-visible:outline-brand-500 '
        . ($tones[$tone] ?? $tones['neutral']);
@endphp

@if ($href)
<a href="{{ $href }}" aria-label="{{ $label }}" {{ $attributes->merge(['class' => $classes]) }}>
@else
<button type="button" aria-label="{{ $label }}" {{ $attributes->merge(['class' => $classes]) }}>
@endif
    <i data-lucide="{{ $icon }}" class="tw:size-[18px]"></i>
    <span role="tooltip"
        class="tw:pointer-events-none tw:absolute tw:bottom-full tw:left-1/2 tw:z-20 tw:mb-2 tw:-translate-x-1/2 tw:translate-y-1 tw:whitespace-nowrap tw:bg-slate-900 tw:px-2 tw:py-1 tw:text-[0.6875rem] tw:font-medium tw:text-white tw:opacity-0 tw:shadow-raised tw:transition-all tw:duration-150 tw:group-hover/act:translate-y-0 tw:group-hover/act:opacity-100 tw:group-focus-visible/act:translate-y-0 tw:group-focus-visible/act:opacity-100">
        {{ $label }}
    </span>
@if ($href)
</a>
@else
</button>
@endif
