{{-- Interruptor accesible (checkbox nativo). Pasa id, onchange, class, etc. al <input>. --}}
@props(['label' => null])

<label class="tw:relative tw:inline-flex tw:shrink-0 tw:cursor-pointer tw:items-center">
    <input type="checkbox" {{ $attributes->merge(['class' => 'tw:peer tw:sr-only']) }}>
    <span class="tw:h-6 tw:w-11 tw:bg-slate-300 tw:transition-colors tw:duration-200 tw:peer-checked:bg-brand-700 tw:peer-focus-visible:ring-4 tw:peer-focus-visible:ring-brand-500/20 tw:peer-disabled:cursor-not-allowed tw:peer-disabled:opacity-50"></span>
    <span class="tw:pointer-events-none tw:absolute tw:top-0.5 tw:left-0.5 tw:size-5 tw:bg-white tw:shadow-xs tw:transition-transform tw:duration-200 tw:peer-checked:translate-x-5"></span>
    @if ($label)<span class="tw:sr-only">{{ $label }}</span>@endif
</label>
