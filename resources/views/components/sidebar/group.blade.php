{{-- Grupo colapsable del sidebar (usa Bootstrap Collapse para abrir/cerrar) --}}
@props(['id', 'icon', 'label', 'active' => false])

<div>
    <button type="button" title="{{ $label }}" data-sb-item
        data-bs-toggle="collapse" data-bs-target="#sb-{{ $id }}"
        aria-expanded="{{ $active ? 'true' : 'false' }}" aria-controls="sb-{{ $id }}"
        @class([
            'tw:group tw:relative tw:flex tw:w-full tw:cursor-pointer tw:items-center tw:gap-3 tw:border-0 tw:px-3 tw:py-2.5 tw:text-left tw:text-[0.875rem] tw:font-medium tw:transition-all tw:duration-150',
            'tw:sb-collapsed:justify-center tw:sb-collapsed:px-0',
            'tw:bg-black/10 tw:text-white' => $active,
            'tw:bg-transparent tw:text-white/80 tw:hover:bg-white/10 tw:hover:text-white tw:aria-expanded:bg-black/10 tw:aria-expanded:text-white' => !$active,
        ])>
        @if ($active)
            <span class="tw:absolute tw:-left-3 tw:top-1/2 tw:h-5 tw:w-1 tw:-translate-y-1/2 tw:rounded-r-full tw:bg-accent-400"></span>
        @endif
        <span class="tw:inline-flex tw:size-5 tw:shrink-0 tw:items-center tw:justify-center">
            <i data-lucide="{{ $icon }}" class="tw:size-[18px]"></i>
        </span>
        <span class="tw:flex-1 tw:truncate tw:sb-collapsed:hidden">{{ $label }}</span>
        <span class="tw:inline-flex tw:text-white/60 tw:transition-transform tw:duration-200 tw:group-aria-expanded:rotate-180 tw:sb-collapsed:hidden">
            <i data-lucide="chevron-down" class="tw:size-4"></i>
        </span>
    </button>

    {{-- No usar utilidades de display aquí: Bootstrap controla .collapse/.show --}}
    <div id="sb-{{ $id }}" data-bs-parent="#sidebarAccordion"
        @class(['collapse tw:sb-collapsed:hidden', 'show' => $active])>
        <div class="tw:ml-[22px] tw:mt-1 tw:mb-2 tw:flex tw:flex-col tw:gap-0.5 tw:border-l tw:border-white/15 tw:pl-3">
            {{ $slot }}
        </div>
    </div>
</div>
