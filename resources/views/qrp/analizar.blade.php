@extends('layouts.app')

@section('content')

@php
    use Illuminate\Support\Str;

    $procesado = isset($procesado) && $procesado;
    $movs = collect($movimientos ?? []);
    $dinero = fn ($v) => '$' . number_format((float) $v, 2);

    // Etiqueta y tono de cada categoría que devuelve el analizador
    $categorias = [
        'Colegiatura'  => ['plural' => 'Colegiaturas', 'tono' => 'tw:bg-brand-50 tw:text-brand-800', 'marca' => 'tw:bg-brand-700'],
        'Examen'       => ['plural' => 'Exámenes',     'tono' => 'tw:bg-amber-50 tw:text-amber-800', 'marca' => 'tw:bg-amber-500'],
        'Otros'        => ['plural' => 'Otros',        'tono' => 'tw:bg-slate-100 tw:text-slate-700', 'marca' => 'tw:bg-slate-400'],
        'Sin concepto' => ['plural' => 'Sin concepto', 'tono' => 'tw:bg-danger-50 tw:text-danger-600', 'marca' => 'tw:bg-danger-500'],
    ];
    $catDe = fn ($m) => array_key_exists($m['categoria'] ?? '', $categorias) ? $m['categoria'] : 'Sin concepto';

    $totalCobrado = (float) $movs->sum('total');
    $totalRecargos = (float) $movs->sum('recargo');

    // Desglose por concepto (solo las categorías con movimientos, en el orden de $categorias)
    $desglose = collect($categorias)->map(function ($cfg, $cat) use ($movs, $catDe, $totalCobrado) {
        $grupo = $movs->filter(fn ($m) => $catDe($m) === $cat);
        $total = (float) $grupo->sum('total');
        return $cfg + [
            'clave'    => $cat,
            'cantidad' => $grupo->count(),
            'importe'  => (float) $grupo->sum('importe'),
            'recargo'  => (float) $grupo->sum('recargo'),
            'total'    => $total,
            'pct'      => $totalCobrado > 0 ? round($total * 100 / $totalCobrado, 1) : 0,
        ];
    })->filter(fn ($d) => $d['cantidad'] > 0)->values();

    $th = 'tw:whitespace-nowrap tw:border-b tw:border-slate-300 tw:bg-slate-50 tw:px-3 tw:py-2.5 tw:text-left tw:text-[0.8125rem] tw:font-semibold tw:text-slate-700';
    $td = 'tw:border-b tw:border-slate-200 tw:px-3 tw:py-2 tw:align-middle';
    $num = 'tw:text-right tw:tabular-nums tw:whitespace-nowrap';
@endphp

<div class="tw:mx-auto tw:flex tw:max-w-[1600px] tw:flex-col tw:gap-6">

    <x-ui.page-header title="Analizar estado de cuenta">
        Sube el reporte de caja en formato .QRP para revisar lo cobrado por concepto y cada movimiento.
    </x-ui.page-header>

    {{-- ===================== ERRORES ===================== --}}
    @if ($errors->any())
        <div role="alert" class="tw:border tw:border-danger-200 tw:bg-danger-50 tw:px-5 tw:py-4">
            <p class="tw:m-0 tw:font-semibold tw:text-danger-800">No se pudo analizar el archivo</p>
            <ul class="tw:m-0 tw:mt-1 tw:pl-5 tw:text-[0.9375rem] tw:text-slate-800">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <p class="tw:m-0 tw:mt-2 tw:text-[0.875rem] tw:text-slate-600">Revisa que sea el reporte .QRP exportado de caja y vuelve a subirlo.</p>
        </div>
    @endif

    {{-- ===================== CARGA DEL ARCHIVO ===================== --}}
    <section class="{{ \App\View\Ui::PANEL }} tw:p-5">
        <form action="{{ route('estado_cuenta.analizar') }}" method="POST" enctype="multipart/form-data" id="formQrp"
              class="tw:grid tw:grid-cols-1 tw:items-end tw:gap-4 tw:md:grid-cols-[minmax(0,1fr)_auto]">
            @csrf
            <x-ui.field label="Reporte de caja (.QRP)" for="archivo_qrp">
                <input type="file" name="archivo_qrp" id="archivo_qrp" accept=".qrp" required
                       class="tw:block tw:h-10 tw:w-full tw:cursor-pointer tw:border tw:border-slate-300 tw:bg-white tw:text-[0.9375rem] tw:text-slate-700 tw:file:mr-4 tw:file:h-full tw:file:cursor-pointer tw:file:border-0 tw:file:border-r tw:file:border-slate-300 tw:file:bg-slate-100 tw:file:px-4 tw:file:font-semibold tw:file:text-slate-800 tw:hover:file:bg-slate-200 tw:focus:outline-none tw:focus-visible:ring-2 tw:focus-visible:ring-brand-700/30">
            </x-ui.field>
            <x-ui.button type="submit" id="btnAnalizar" icon="file-search">Analizar archivo</x-ui.button>
        </form>
        @if ($procesado)
            <p class="tw:m-0 tw:mt-3 tw:text-[0.875rem] tw:text-slate-600">
                Archivo analizado: <span class="tw:font-semibold tw:text-slate-900">{{ $archivoProcesado }}</span>, con {{ $totalMovimientos }} {{ Str::plural('movimiento', $totalMovimientos) }}.
            </p>
        @else
            <p class="tw:m-0 tw:mt-3 tw:text-[0.875rem] tw:text-slate-600">Solo se aceptan archivos .QRP exportados del sistema de caja.</p>
        @endif
    </section>

    @if ($procesado)

    {{-- ===================== RESUMEN: total cobrado + desglose por concepto ===================== --}}
    <section class="{{ \App\View\Ui::PANEL }} tw:grid tw:grid-cols-1 tw:lg:grid-cols-[280px_minmax(0,1fr)]">
        <div class="tw:border-b tw:border-slate-200 tw:bg-brand-700 tw:p-6 tw:text-white tw:lg:border-b-0">
            <p class="tw:m-0 tw:text-[0.9375rem] tw:text-white/80">Total cobrado</p>
            <p class="tw:m-0 tw:mt-1 tw:text-[2.25rem] tw:font-semibold tw:leading-tight tw:tabular-nums">{{ $dinero($totalCobrado) }}</p>
            <p class="tw:m-0 tw:mt-2 tw:text-[0.875rem] tw:text-white/80">
                En {{ $totalMovimientos }} {{ Str::plural('movimiento', $totalMovimientos) }}{{ $totalRecargos > 0 ? ', incluye ' . $dinero($totalRecargos) . ' de recargos' : '' }}.
            </p>
        </div>

        <div class="tw:min-w-0 tw:overflow-x-auto">
            <table class="tw:w-full tw:border-collapse tw:text-[0.9375rem]">
                <caption class="tw:sr-only">Desglose de lo cobrado por concepto. Elige un concepto para ver solo sus movimientos.</caption>
                <thead>
                    <tr>
                        <th scope="col" class="{{ $th }}">Concepto</th>
                        <th scope="col" class="{{ $th }} tw:text-right">Movimientos</th>
                        <th scope="col" class="{{ $th }} tw:text-right">Importe</th>
                        <th scope="col" class="{{ $th }} tw:text-right">Recargos</th>
                        <th scope="col" class="{{ $th }} tw:text-right">Total</th>
                        <th scope="col" class="{{ $th }} tw:w-[22%]">Parte del total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($desglose as $d)
                        <tr class="fila-desglose tw:cursor-pointer tw:hover:bg-slate-50 tw:[&.activa]:bg-brand-50" data-filtro="{{ $d['clave'] }}"
                            tabindex="0" role="button" aria-label="Ver solo los movimientos de {{ $d['plural'] }}">
                            <td class="{{ $td }}">
                                <span class="tw:inline-flex tw:items-center tw:gap-2 tw:font-semibold tw:text-slate-900">
                                    <span class="tw:size-2.5 {{ $d['marca'] }}" aria-hidden="true"></span>
                                    {{ $d['plural'] }}
                                </span>
                            </td>
                            <td class="{{ $td }} {{ $num }}">{{ $d['cantidad'] }}</td>
                            <td class="{{ $td }} {{ $num }}">{{ $dinero($d['importe']) }}</td>
                            <td class="{{ $td }} {{ $num }} {{ $d['recargo'] > 0 ? 'tw:text-danger-600' : 'tw:text-slate-400' }}">{{ $dinero($d['recargo']) }}</td>
                            <td class="{{ $td }} {{ $num }} tw:font-semibold tw:text-slate-900">{{ $dinero($d['total']) }}</td>
                            <td class="{{ $td }}">
                                <div class="tw:flex tw:items-center tw:gap-3">
                                    <div class="tw:h-2 tw:flex-1 tw:bg-slate-100" aria-hidden="true">
                                        <div class="tw:h-full tw:bg-brand-700" style="width: {{ $d['pct'] }}%"></div>
                                    </div>
                                    <span class="tw:w-12 tw:text-right tw:text-[0.875rem] tw:tabular-nums tw:text-slate-600">{{ number_format($d['pct'], 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    {{-- ===================== MOVIMIENTOS ===================== --}}
    <section class="{{ \App\View\Ui::PANEL }}">
        <div class="tw:flex tw:flex-col tw:gap-4 tw:border-b tw:border-slate-200 tw:p-5 tw:xl:flex-row tw:xl:items-end tw:xl:justify-between">
            <div>
                <h2 class="tw:m-0 tw:text-[1.25rem] tw:font-semibold tw:text-slate-900">Movimientos</h2>
                <p class="tw:m-0 tw:mt-1 tw:text-[0.875rem] tw:text-slate-600" id="lblConteo" aria-live="polite">
                    Mostrando {{ $movs->count() }} de {{ $movs->count() }}
                </p>
            </div>

            <div class="tw:flex tw:flex-col tw:gap-3 tw:md:flex-row tw:md:items-center">
                {{-- Filtro por concepto --}}
                <div role="group" aria-label="Filtrar por concepto" class="tw:inline-flex tw:border tw:border-slate-300 tw:bg-white">
                    <button type="button" class="btn-filtro activa tw:h-9 tw:cursor-pointer tw:border-0 tw:bg-transparent tw:px-3.5 tw:text-[0.875rem] tw:font-medium tw:text-slate-700 tw:hover:bg-slate-100 tw:focus-visible:outline-2 tw:focus-visible:outline-brand-700 tw:[&.activa]:bg-brand-700 tw:[&.activa]:text-white" data-filtro="" aria-pressed="true">Todos</button>
                    @foreach ($desglose as $d)
                        <button type="button" class="btn-filtro tw:h-9 tw:cursor-pointer tw:border-0 tw:border-l tw:border-slate-300 tw:bg-transparent tw:px-3.5 tw:text-[0.875rem] tw:font-medium tw:text-slate-700 tw:hover:bg-slate-100 tw:focus-visible:outline-2 tw:focus-visible:outline-brand-700 tw:[&.activa]:bg-brand-700 tw:[&.activa]:text-white" data-filtro="{{ $d['clave'] }}" aria-pressed="false">{{ $d['plural'] }}</button>
                    @endforeach
                </div>

                <div class="tw:relative tw:md:w-72">
                    <label for="buscarMovimiento" class="tw:sr-only">Buscar por recibo, concepto o usuario</label>
                    <i data-lucide="search" class="tw:pointer-events-none tw:absolute tw:left-3 tw:top-1/2 tw:size-4 tw:-translate-y-1/2 tw:text-slate-400" aria-hidden="true"></i>
                    <input type="search" id="buscarMovimiento" placeholder="Recibo, concepto o usuario" autocomplete="off"
                           class="{{ \App\View\Ui::INPUT }} tw:h-9 tw:pl-9 tw:text-[0.875rem]">
                </div>
            </div>
        </div>

        <div class="tw:max-h-[70vh] tw:overflow-auto">
            <table class="tw:w-full tw:border-collapse tw:text-[0.875rem]" id="tablaMovimientos">
                <thead class="tw:sticky tw:top-0 tw:z-10">
                    <tr>
                        <th scope="col" class="{{ $th }}">Recibo</th>
                        <th scope="col" class="{{ $th }}">Fecha</th>
                        <th scope="col" class="{{ $th }}">Hora</th>
                        <th scope="col" class="{{ $th }}">Concepto</th>
                        <th scope="col" class="{{ $th }}">Categoría</th>
                        <th scope="col" class="{{ $th }} tw:text-right">Cant.</th>
                        <th scope="col" class="{{ $th }} tw:text-right">Importe</th>
                        <th scope="col" class="{{ $th }} tw:text-right">Recargo</th>
                        <th scope="col" class="{{ $th }} tw:text-right">Total</th>
                        <th scope="col" class="{{ $th }}">Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($movs as $m)
                        @php $cat = $catDe($m); @endphp
                        <tr class="fila-mov tw:hover:bg-slate-50"
                            data-categoria="{{ $cat }}"
                            data-importe="{{ (float) ($m['importe'] ?? 0) }}"
                            data-recargo="{{ (float) ($m['recargo'] ?? 0) }}"
                            data-total="{{ (float) ($m['total'] ?? 0) }}"
                            data-busqueda="{{ Str::lower(Str::ascii(($m['recibo'] ?? '') . ' ' . ($m['concepto'] ?? '') . ' ' . ($m['usuario'] ?? ''))) }}">
                            <td class="{{ $td }} tw:whitespace-nowrap tw:font-semibold tw:tabular-nums tw:text-slate-900">{{ $m['recibo'] ?? '' }}</td>
                            <td class="{{ $td }} tw:whitespace-nowrap tw:tabular-nums">{{ $m['fecha'] ?? '' }}</td>
                            <td class="{{ $td }} tw:whitespace-nowrap tw:tabular-nums tw:text-slate-600">{{ $m['hora'] ?? '' }}</td>
                            <td class="{{ $td }} tw:min-w-[13rem] tw:text-slate-800">{{ $m['concepto'] ?? '' }}</td>
                            <td class="{{ $td }}">
                                <span class="tw:inline-block tw:whitespace-nowrap tw:px-2 tw:py-0.5 tw:text-[0.8125rem] tw:font-semibold {{ $categorias[$cat]['tono'] }}">{{ $cat }}</span>
                            </td>
                            <td class="{{ $td }} {{ $num }}">{{ $m['cantidad_colegiaturas'] ?? 0 }}</td>
                            <td class="{{ $td }} {{ $num }}">{{ $dinero($m['importe'] ?? 0) }}</td>
                            <td class="{{ $td }} {{ $num }} {{ ($m['recargo'] ?? 0) > 0 ? 'tw:text-danger-600' : 'tw:text-slate-400' }}">{{ $dinero($m['recargo'] ?? 0) }}</td>
                            <td class="{{ $td }} {{ $num }} tw:font-semibold tw:text-slate-900">{{ $dinero($m['total'] ?? 0) }}</td>
                            <td class="{{ $td }} tw:whitespace-nowrap tw:text-slate-700">{{ $m['usuario'] ?? '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="tw:px-4 tw:py-10 tw:text-center tw:text-slate-600">
                                El archivo no contiene movimientos. Verifica que el reporte de caja abarque el periodo correcto y vuelve a exportarlo.
                            </td>
                        </tr>
                    @endforelse
                    <tr id="filaSinResultados" hidden>
                        <td colspan="10" class="tw:px-4 tw:py-10 tw:text-center tw:text-slate-600">
                            Ningún movimiento coincide. Borra la búsqueda o elige «Todos».
                        </td>
                    </tr>
                </tbody>
                @if ($movs->isNotEmpty())
                    <tfoot class="tw:sticky tw:bottom-0">
                        <tr class="tw:bg-slate-100 tw:font-semibold tw:text-slate-900">
                            <td colspan="6" class="tw:border-t-2 tw:border-slate-300 tw:px-4 tw:py-3">Total de lo mostrado</td>
                            <td class="tw:border-t-2 tw:border-slate-300 tw:px-4 tw:py-3 {{ $num }}" id="sumImporte">{{ $dinero($movs->sum('importe')) }}</td>
                            <td class="tw:border-t-2 tw:border-slate-300 tw:px-4 tw:py-3 {{ $num }}" id="sumRecargo">{{ $dinero($movs->sum('recargo')) }}</td>
                            <td class="tw:border-t-2 tw:border-slate-300 tw:px-4 tw:py-3 {{ $num }}" id="sumTotal">{{ $dinero($movs->sum('total')) }}</td>
                            <td class="tw:border-t-2 tw:border-slate-300"></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </section>

    @else

    {{-- ===================== ANTES DE SUBIR UN ARCHIVO ===================== --}}
    <section class="{{ \App\View\Ui::PANEL }} tw:p-6">
        <h2 class="tw:m-0 tw:text-[1.125rem] tw:font-semibold tw:text-slate-900">Qué obtendrás</h2>
        <ul class="tw:m-0 tw:mt-3 tw:grid tw:list-none tw:grid-cols-1 tw:gap-4 tw:p-0 tw:text-[0.9375rem] tw:text-slate-700 tw:md:grid-cols-3">
            <li class="tw:border-t-2 tw:border-brand-700 tw:pt-3"><span class="tw:block tw:font-semibold tw:text-slate-900">Total cobrado</span>La suma de todos los recibos del reporte, con los recargos incluidos.</li>
            <li class="tw:border-t-2 tw:border-brand-700 tw:pt-3"><span class="tw:block tw:font-semibold tw:text-slate-900">Desglose por concepto</span>Cuánto corresponde a colegiaturas, exámenes y otros cobros.</li>
            <li class="tw:border-t-2 tw:border-brand-700 tw:pt-3"><span class="tw:block tw:font-semibold tw:text-slate-900">Cada movimiento</span>Recibo, fecha, concepto, importe y quién lo cobró, con búsqueda.</li>
        </ul>
    </section>

    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Al enviar: evitar doble envío y avisar que se está procesando
    const form = document.getElementById('formQrp');
    const boton = document.getElementById('btnAnalizar');
    form?.addEventListener('submit', () => {
        if (!document.getElementById('archivo_qrp')?.files.length) return;
        boton.disabled = true;
        boton.innerHTML = '<span class="tw:inline-block tw:size-4 tw:animate-spin tw:rounded-full tw:border-2 tw:border-white/40 tw:border-t-white" aria-hidden="true"></span> Analizando…';
    });

    const filas = [...document.querySelectorAll('.fila-mov')];
    if (!filas.length) return;

    const moneda = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' });
    const sinAcentos = t => t.normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase();
    const buscar = document.getElementById('buscarMovimiento');
    let filtro = '';

    function aplicar() {
        const q = sinAcentos(buscar.value.trim());
        let n = 0, importe = 0, recargo = 0, total = 0;
        filas.forEach(f => {
            const ok = (!filtro || f.dataset.categoria === filtro) && (!q || f.dataset.busqueda.includes(q));
            f.hidden = !ok;
            if (ok) {
                n++;
                importe += +f.dataset.importe;
                recargo += +f.dataset.recargo;
                total += +f.dataset.total;
            }
        });
        document.getElementById('lblConteo').textContent = `Mostrando ${n} de ${filas.length}`;
        document.getElementById('sumImporte').textContent = moneda.format(importe);
        document.getElementById('sumRecargo').textContent = moneda.format(recargo);
        document.getElementById('sumTotal').textContent = moneda.format(total);
        document.getElementById('filaSinResultados').hidden = n > 0;
    }

    function elegir(valor) {
        filtro = valor;
        document.querySelectorAll('.btn-filtro').forEach(b => {
            const activo = b.dataset.filtro === valor;
            b.classList.toggle('activa', activo);
            b.setAttribute('aria-pressed', activo);
        });
        document.querySelectorAll('.fila-desglose').forEach(r => r.classList.toggle('activa', !!valor && r.dataset.filtro === valor));
        aplicar();
    }

    document.querySelectorAll('.btn-filtro').forEach(b => b.addEventListener('click', () => elegir(b.dataset.filtro)));

    // Las filas del desglose también filtran; un segundo clic regresa a "Todos"
    document.querySelectorAll('.fila-desglose').forEach(r => {
        const accion = () => {
            elegir(filtro === r.dataset.filtro ? '' : r.dataset.filtro);
            document.getElementById('tablaMovimientos').scrollIntoView({ behavior: 'smooth', block: 'start' });
        };
        r.addEventListener('click', accion);
        r.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); accion(); } });
    });

    buscar.addEventListener('input', aplicar);
});
</script>

@endsection
