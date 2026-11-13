{{--
    Matriz de permisos generada desde config/permisos.php (catálogo único).
    Ninguna casilla viene marcada: una cuenta nueva NO tiene permisos hasta que se le asignan.
    El JS de la vista usa: .js-permiso (casillas), [data-col-toggle] (columna), [data-grupo-toggle] (grupo).
--}}
@php $acciones = config('permisos.acciones'); @endphp
<table class="tw:w-full tw:border-collapse tw:text-[0.8125rem]">
    <thead class="tw:sticky tw:top-0 tw:z-10 tw:bg-slate-50">
        <tr class="tw:border-b tw:border-slate-200">
            <th scope="col" class="tw:px-3 tw:py-2.5 tw:text-left tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-500">Módulo</th>
            @foreach ($acciones as $accion => $etiqueta)
                <th scope="col" class="tw:w-[92px] tw:px-1 tw:py-2 tw:text-center">
                    <label class="tw:inline-flex tw:cursor-pointer tw:flex-col tw:items-center tw:gap-1 tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.04em] tw:text-slate-500">
                        {{ $etiqueta }}
                        <input type="checkbox" data-col-toggle="{{ $accion }}" class="tw:size-4 tw:cursor-pointer tw:accent-brand-700" aria-label="Seleccionar toda la columna {{ $etiqueta }}">
                    </label>
                </th>
            @endforeach
        </tr>
    </thead>
    @foreach (config('permisos.grupos') as $i => $grupo)
        <tbody data-grupo="{{ $i }}">
            <tr class="tw:border-b tw:border-slate-100 tw:bg-white">
                <th scope="rowgroup" colspan="{{ count($acciones) + 1 }}" class="tw:px-3 tw:pt-3 tw:pb-1.5 tw:text-left">
                    <label class="tw:inline-flex tw:cursor-pointer tw:items-center tw:gap-2 tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-brand-700">
                        <input type="checkbox" data-grupo-toggle="{{ $i }}" class="tw:size-4 tw:cursor-pointer tw:accent-brand-700" aria-label="Seleccionar todo el grupo {{ $grupo['titulo'] }}">
                        {{ $grupo['titulo'] }}
                    </label>
                </th>
            </tr>
            @foreach ($grupo['modulos'] as $modulo => $etiqueta)
                <tr class="tw:border-b tw:border-slate-100 tw:transition-colors tw:hover:bg-slate-50">
                    <td class="tw:py-2 tw:pr-2 tw:pl-9 tw:text-slate-700">{{ $etiqueta }}</td>
                    @foreach ($acciones as $accion => $etiquetaAccion)
                        <td class="tw:py-2 tw:text-center">
                            <input type="checkbox" name="permisos_modulos[]" value="{{ $modulo }}:{{ $accion }}" data-accion="{{ $accion }}"
                                class="js-permiso tw:size-4 tw:cursor-pointer tw:accent-brand-700" aria-label="{{ $etiqueta }}: {{ $etiquetaAccion }}">
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    @endforeach
</table>
