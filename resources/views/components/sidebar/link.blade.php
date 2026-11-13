{{-- Enlace de submenú (nivel 2) --}}
@props(['href', 'active' => false])

<a href="{{ $href }}"
    @class([
        'tw:relative tw:flex tw:items-center tw:gap-2 tw:px-3 tw:py-1.5 tw:text-[0.8125rem] tw:no-underline tw:transition-colors tw:duration-150',
        'tw:bg-white/15 tw:font-semibold tw:text-white' => $active,
        'tw:font-medium tw:text-white/70 tw:hover:bg-white/10 tw:hover:text-white' => !$active,
    ])>
    @if ($active)
        <span class="tw:absolute tw:-left-[13px] tw:top-1/2 tw:h-4 tw:w-px tw:-translate-y-1/2 tw:bg-accent-400"></span>
    @endif
    {{ $slot }}
</a>
