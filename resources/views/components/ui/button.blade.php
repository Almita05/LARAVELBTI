{{--
    Botón estándar.
    variant: primary (acción principal, una por vista) | secondary | ghost
    icon: nombre de ícono Lucide (opcional)
    Uso: <x-ui.button icon="printer" onclick="...">Generar constancia</x-ui.button>
--}}
@props(['variant' => 'primary', 'icon' => null, 'type' => 'button'])

@php
    $base = 'tw:inline-flex tw:h-10 tw:shrink-0 tw:cursor-pointer tw:items-center tw:justify-center tw:gap-2 tw:px-4 tw:text-sm tw:font-semibold tw:no-underline tw:transition-colors tw:duration-100 tw:focus-visible:outline-2 tw:focus-visible:outline-offset-2 tw:focus-visible:outline-brand-700 tw:disabled:cursor-not-allowed tw:disabled:opacity-60';
    $variantes = [
        'primary'   => 'tw:border-0 tw:bg-brand-700 tw:text-white tw:hover:bg-brand-800',
        'secondary' => 'tw:border tw:border-slate-300 tw:bg-white tw:text-slate-800 tw:hover:border-slate-400 tw:hover:bg-slate-50',
        'ghost'     => 'tw:border-0 tw:bg-transparent tw:text-brand-700 tw:hover:bg-brand-50',
    ];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $base . ' ' . ($variantes[$variant] ?? $variantes['primary'])]) }}>
    @if($icon)<i data-lucide="{{ $icon }}" class="tw:size-4" aria-hidden="true"></i>@endif
    {{ $slot }}
</button>
