{{-- Enlace de primer nivel del sidebar (sin submenú) --}}
@props(['href', 'icon', 'label', 'active' => false])

<a href="{{ $href }}" title="{{ $label }}" data-sb-item
    @class([
        'tw:group tw:relative tw:flex tw:items-center tw:gap-3 tw:px-3 tw:py-2.5 tw:text-[0.875rem] tw:font-medium tw:no-underline tw:transition-all tw:duration-150',
        'tw:sb-collapsed:justify-center tw:sb-collapsed:px-0',
        'tw:bg-white/15 tw:text-white tw:shadow-xs' => $active,
        'tw:text-white/80 tw:hover:bg-white/10 tw:hover:text-white' => !$active,
    ])>
    @if ($active)
        <span class="tw:absolute tw:-left-3 tw:top-1/2 tw:h-5 tw:w-1 tw:-translate-y-1/2 tw:rounded-r-full tw:bg-accent-400"></span>
    @endif
    <span class="tw:relative tw:inline-flex tw:size-5 tw:shrink-0 tw:items-center tw:justify-center">
        <i data-lucide="{{ $icon }}" class="tw:size-[18px]"></i>
        {{ $badge ?? '' }}
    </span>
    <span class="tw:truncate tw:sb-collapsed:hidden">{{ $label }}</span>
</a>
