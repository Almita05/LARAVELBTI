{{--
    Campo de formulario con su etiqueta (en minúsculas normales, sin mayúsculas espaciadas).
    El control va en el slot; usa \App\View\Ui::INPUT para el estilo estándar.
    Uso:
      <x-ui.field label="Fecha de expedición" for="constanciaFecha">
          <input type="date" id="constanciaFecha" class="{{ \App\View\Ui::INPUT }}">
      </x-ui.field>
--}}
@props(['label', 'for' => null, 'hint' => null])

<div {{ $attributes }}>
    <label @if($for) for="{{ $for }}" @endif class="tw:mb-1 tw:block tw:text-[0.875rem] tw:font-medium tw:text-slate-700">{{ $label }}</label>
    {{ $slot }}
    @if($hint)
        <p class="tw:m-0 tw:mt-1 tw:text-[0.8125rem] tw:text-slate-500">{{ $hint }}</p>
    @endif
</div>
