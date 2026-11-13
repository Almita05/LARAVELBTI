@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@php
    $totales = $notificaciones['totales'] ?? [];
    $resueltasLista = $notificaciones['resueltas'] ?? [];

    // Genera "fn(arg1, arg2, ..., this)" con argumentos JSON (seguros dentro de onclick con {{ }})
    $call = fn (string $fn, ...$args) => $fn . '(' . implode(', ', array_map(
        fn ($a) => json_encode($a, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), $args
    )) . ', this)';

    /*
     * Normalización de todas las alertas a un mismo formato de fila:
     *  nivel  → critico | advertencia | info | archivo   (borde izquierdo)
     *  tono   → color del círculo del icono
     */
    $items = [];

    foreach ($notificaciones['documentos'] ?? [] as $doc) {
        $items[] = [
            'categoria' => 'documentos', 'nivel' => 'critico', 'tono' => 'danger',
            'icono' => 'file-warning', 'tipo' => 'Documentación faltante',
            'titulo' => $doc['nombre'] ?? '', 'mono' => false,
            'detalle' => $doc['detalle'] ?? '', 'detalle2' => null,
            'meta' => array_filter([$doc['cct'] ?? null, $doc['grupo'] ?? null]),
            'acciones' => [
                ['icon' => 'check', 'label' => 'Quitar alerta', 'tone' => 'success',
                 'onclick' => $call('abrirModalResolver', 'documentos', $doc['idAlumno'] ?? 0, $doc['nombre'] ?? '', $doc['detalle'] ?? '', $doc['subtipo'] ?? 'general')],
            ],
        ];
    }

    foreach ($notificaciones['equivalencias'] ?? [] as $eq) {
        $esPago = ($eq['tipo'] ?? '') === 'pago_pendiente';
        $items[] = [
            'categoria' => 'equivalencias', 'nivel' => $esPago ? 'advertencia' : 'info', 'tono' => $esPago ? 'warning' : 'brand',
            'icono' => $esPago ? 'coins' : 'arrow-left-right', 'tipo' => $esPago ? 'Pago de equivalencia pendiente' : 'Equivalencia',
            'titulo' => $eq['nombre'] ?? '', 'mono' => false,
            'detalle' => $eq['detalle'] ?? '', 'detalle2' => null,
            'meta' => array_filter([$eq['cct'] ?? null, $eq['grupo'] ?? null]),
            'acciones' => [
                ['icon' => 'check', 'label' => 'Quitar alerta', 'tone' => 'success',
                 'onclick' => $call('abrirModalResolver', 'equivalencias', $eq['idAlumno'] ?? 0, $eq['nombre'] ?? '', $eq['detalle'] ?? '', $eq['subtipo'] ?? 'equivalencia')],
            ],
        ];
    }

    foreach ($notificaciones['grupos'] ?? [] as $gp) {
        $tipoAviso = $gp['badge_tipo'] ?? 'Término de ciclo';
        $detalleTexto = $gp['detalle'] ?? '';
        $esAtencion = str_contains($detalleTexto, 'ATENCIÓN:');
        $esHorario = stripos($tipoAviso, 'horario') !== false || stripos($tipoAviso, 'incompleto') !== false;
        $esCritico = !empty($gp['esCritico']);
        $accion = $gp['accion_tipo'] ?? null;
        $idGrupo = $gp['idGrupo'] ?? 0;

        if ($accion === 'armar_prehorario') {
            $acciones = [['icon' => 'calendar-plus', 'label' => 'Armar pre-horario', 'tone' => 'brand', 'href' => "/horarios?grupo_id={$idGrupo}&es_prehorario=1"]];
        } elseif (in_array($accion, ['armar_horario', 'completar_horario'], true)) {
            $acciones = [['icon' => 'calendar-check', 'label' => $accion === 'completar_horario' ? 'Completar horario' : 'Armar horario', 'tone' => 'brand', 'href' => "/horarios?grupo_id={$idGrupo}"]];
        } else {
            $acciones = [['icon' => 'notebook-pen', 'label' => 'Capturar notas', 'tone' => 'brand', 'href' => '/grupos/captura_calificaciones']];
            $acciones[] = ($gp['id_centroTrabajo'] ?? null) == 3
                ? ['icon' => 'calendar-plus', 'label' => 'Armar pre-horario', 'tone' => 'brand', 'href' => "/horarios?grupo_id={$idGrupo}&es_prehorario=1"]
                : ['icon' => 'calendar', 'label' => 'Ver horario', 'tone' => 'neutral', 'href' => "/horarios?grupo_id={$idGrupo}"];
        }
        $acciones[] = ['icon' => 'eye-off', 'label' => 'Omitir aviso', 'tone' => 'neutral',
            'onclick' => $call('abrirModalResolver', 'grupos', $idGrupo, 'Grupo: ' . ($gp['clave'] ?? ''), $detalleTexto, $gp['subtipo'] ?? 'general')];

        $items[] = [
            'categoria' => 'grupos',
            'nivel' => $esCritico ? 'critico' : (($esHorario || $esAtencion) ? 'advertencia' : 'info'),
            'tono' => $esCritico ? 'danger' : (($esHorario || $esAtencion) ? 'warning' : 'brand'),
            'icono' => $esHorario ? 'clock' : (stripos($tipoAviso, 'equivalencia') !== false ? 'arrow-left-right' : 'calendar-range'),
            'tipo' => $tipoAviso,
            'titulo' => $gp['clave'] ?? '', 'mono' => true, 'urgente' => $esCritico,
            'detalle' => trim(preg_replace('/\[?ATENCIÓN:\s*([^\]]*)\]?/u', '$1', $detalleTexto)), 'detalle2' => null,
            'meta' => array_filter([$gp['cct'] ?? null]),
            'acciones' => $acciones,
        ];
    }

    foreach ($notificaciones['nuevos_alumnos'] ?? [] as $na) {
        $matricula = (!empty($na['matricula']) && $na['matricula'] !== 'Sin Matrícula') ? $na['matricula'] : null;
        $items[] = [
            'categoria' => 'nuevos_alumnos', 'nivel' => 'info', 'tono' => 'brand',
            'icono' => 'user-plus', 'tipo' => 'Nuevo alumno',
            'titulo' => $na['nombre'] ?? '', 'mono' => false,
            'detalle' => 'Registrado por ' . ($na['creador'] ?? 'Docente/Admin'),
            'detalle2' => trim(($na['fecha_creacion'] ?? '') . (!empty($na['hora_creacion']) ? ' a las ' . $na['hora_creacion'] : '')),
            'meta' => array_filter([$na['cct'] ?? null, $na['grupo'] ?? null, $matricula]),
            'acciones' => [
                ['icon' => 'eye', 'label' => 'Ver kárdex', 'tone' => 'neutral', 'href' => '/alumnos/' . ($na['idAlumno'] ?? 0) . '/kardex'],
                ['icon' => 'check', 'label' => 'Marcar como enterado', 'tone' => 'success',
                 'onclick' => $call('abrirModalResolver', 'nuevos_alumnos', $na['idAlumno'] ?? 0, $na['nombre'] ?? '', 'Nuevo alumno en grupo ' . ($na['grupo'] ?? ''), 'nuevo_registro')],
            ],
        ];
    }

    // Prioridad visual en el feed: críticas → advertencias → informativas (orden estable)
    $peso = ['critico' => 0, 'advertencia' => 1, 'info' => 2];
    usort($items, fn ($a, $b) => $peso[$a['nivel']] <=> $peso[$b['nivel']]);

    foreach ($resueltasLista as $res) {
        $resuelto = ($res['accion'] ?? '') === 'resuelto';
        $items[] = [
            'categoria' => 'resueltas', 'nivel' => 'archivo', 'tono' => $resuelto ? 'success' : 'slate',
            'icono' => $resuelto ? 'circle-check' : 'eye-off', 'tipo' => $resuelto ? 'Resuelta' : 'Omitida (no es alerta)',
            'titulo' => $res['nombre'] ?? '', 'mono' => false,
            'detalle' => $res['motivo'] ?? '',
            'detalle2' => trim(($res['usuario'] ?? '') . ', ' . ($res['fecha'] ?? ''), ' ,'),
            'meta' => array_filter([$res['cct'] ?? null, $res['grupo'] ?? null]),
            'acciones' => [
                ['icon' => 'rotate-ccw', 'label' => 'Reactivar alerta', 'tone' => 'brand',
                 'onclick' => $call('reactivarAlerta', $res['tipo'] ?? '', $res['id_referencia'] ?? 0, $res['subtipo'] ?? '', $res['nombre'] ?? '')],
            ],
        ];
    }

    $bordes = [
        'critico'     => 'tw:border-l-danger-500',
        'advertencia' => 'tw:border-l-warning-600',
        'info'        => 'tw:border-l-brand-300',
        'archivo'     => 'tw:border-l-slate-200',
    ];
    $tonos = [
        'danger'  => 'tw:bg-danger-50 tw:text-danger-600',
        'warning' => 'tw:bg-warning-50 tw:text-warning-600',
        'brand'   => 'tw:bg-brand-50 tw:text-brand-700',
        'success' => 'tw:bg-success-50 tw:text-success-700',
        'slate'   => 'tw:bg-slate-100 tw:text-slate-500',
    ];

    $tabs = [
        ['id' => 'todos',          'label' => 'Todos',              'count' => $totales['total'] ?? 0],
        ['id' => 'documentos',     'label' => 'Documentación',      'count' => $totales['documentos'] ?? 0],
        ['id' => 'equivalencias',  'label' => 'Equivalencias',      'count' => $totales['equivalencias'] ?? 0],
        ['id' => 'grupos',         'label' => 'Grupos y horarios',  'count' => $totales['grupos'] ?? 0],
        ['id' => 'nuevos_alumnos', 'label' => 'Nuevos alumnos',     'count' => $totales['nuevos_alumnos'] ?? 0],
        ['id' => 'resueltas',      'label' => 'Resueltas',          'count' => $totales['resueltas'] ?? count($resueltasLista)],
    ];
    $totalActivas = (int) ($totales['total'] ?? 0);
@endphp

<div class="tw:mx-auto tw:flex tw:max-w-[1100px] tw:flex-col tw:gap-6">

    {{-- ===================== ENCABEZADO ===================== --}}
    <header class="tw:flex tw:items-start tw:gap-4">
        <a href="{{ url()->previous() }}" aria-label="Regresar"
            class="tw:mt-0.5 tw:inline-flex tw:size-10 tw:shrink-0 tw:items-center tw:justify-center tw:border tw:border-slate-200 tw:bg-white tw:text-slate-500 tw:no-underline tw:shadow-xs tw:transition-colors tw:hover:bg-slate-50 tw:hover:text-slate-900">
            <i data-lucide="arrow-left" class="tw:size-[18px]"></i>
        </a>
        <div class="tw:min-w-0">
            <h1 class="tw:m-0 tw:text-[1.5rem] tw:font-bold tw:tracking-tight tw:text-slate-900">Avisos y pendientes</h1>
            <p class="tw:m-0 tw:mt-1 tw:text-sm tw:text-slate-500">
                Tienes <strong id="badge-header-total" class="tw:font-semibold tw:text-slate-900">{{ $totalActivas }} alertas</strong> activas por atender.
            </p>
        </div>
    </header>

    @if(isset($notificaciones['error']))
        <div role="alert" class="tw:flex tw:items-start tw:gap-3 tw:border tw:border-danger-200 tw:border-l-4 tw:border-l-danger-500 tw:bg-white tw:p-5 tw:shadow-card">
            <span class="tw:inline-flex tw:size-10 tw:shrink-0 tw:items-center tw:justify-center tw:bg-danger-50 tw:text-danger-600">
                <i data-lucide="wifi-off" class="tw:size-5"></i>
            </span>
            <div>
                <p class="tw:m-0 tw:text-sm tw:font-semibold tw:text-slate-900">Error de comunicación</p>
                <p class="tw:m-0 tw:mt-0.5 tw:text-[0.8125rem] tw:text-slate-500">{{ $notificaciones['error'] }}</p>
            </div>
        </div>
    @else

    {{-- ===================== PESTAÑAS ===================== --}}
    <div class="tw:flex tw:flex-wrap tw:items-end tw:justify-between tw:gap-3 tw:border-b tw:border-slate-200">
        <nav role="tablist" aria-label="Filtrar avisos" class="tw:-mb-px tw:flex tw:gap-1 tw:overflow-x-auto tw:[scrollbar-width:none]">
            @foreach ($tabs as $tab)
            <button type="button" role="tab" data-filtro="{{ $tab['id'] }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                onclick="filtrarAlertas('{{ $tab['id'] }}', this)"
                class="tw:group tw:inline-flex tw:shrink-0 tw:cursor-pointer tw:items-center tw:gap-2 tw:border-0 tw:border-b-2 tw:border-transparent tw:bg-transparent tw:px-3 tw:pt-2 tw:pb-3 tw:text-[0.8125rem] tw:font-medium tw:whitespace-nowrap tw:text-slate-500 tw:transition-colors tw:hover:text-slate-900 tw:aria-selected:border-brand-700 tw:aria-selected:font-semibold tw:aria-selected:text-brand-700">
                {{ $tab['label'] }}
                <span id="badge-pill-{{ $tab['id'] }}"
                    class="tw:inline-flex tw:h-5 tw:min-w-5 tw:items-center tw:justify-center tw:rounded-none tw:bg-slate-100 tw:px-1.5 tw:text-[0.6875rem] tw:font-semibold tw:text-slate-500 tw:tabular-nums tw:transition-colors tw:group-aria-selected:bg-brand-50 tw:group-aria-selected:text-brand-700">{{ $tab['count'] }}</span>
            </button>
            @endforeach
        </nav>

        {{-- Visible solo en "Resueltas" con registros (se controla con el atributo hidden) --}}
        <div id="contenedor-btn-limpiar" class="tw:pb-2" hidden>
            <button type="button" id="btn-limpiar-resueltas" onclick="limpiarResueltasOmitidas()"
                class="tw:inline-flex tw:cursor-pointer tw:items-center tw:gap-1.5 tw:border-0 tw:bg-transparent tw:px-2.5 tw:py-1.5 tw:text-[0.8125rem] tw:font-medium tw:text-danger-600 tw:transition-colors tw:hover:bg-danger-50">
                <i data-lucide="trash-2" class="tw:size-4"></i> Limpiar historial
            </button>
        </div>
    </div>

    {{-- ===================== FEED ===================== --}}
    {{-- Cada <li> se muestra/oculta con el atributo hidden: no ponerle utilidades de display --}}
    <ul id="tablaNotificaciones" class="tw:m-0 tw:flex tw:list-none tw:flex-col tw:gap-3 tw:p-0">
        @foreach ($items as $it)
        <li class="fila-alerta" data-categoria="{{ $it['categoria'] }}" @if($it['categoria'] === 'resueltas') hidden @endif>
            <div @class([
                'tw:group tw:flex tw:items-center tw:gap-4 tw:border tw:border-l-[3px] tw:border-slate-200 tw:bg-white tw:px-5 tw:py-4 tw:shadow-xs tw:transition-all tw:duration-200 tw:hover:border-slate-300 tw:hover:shadow-raised',
                $bordes[$it['nivel']],
                'tw:bg-slate-50/60' => $it['nivel'] === 'archivo',
            ])>
                {{-- Icono del tipo --}}
                <span class="tw:inline-flex tw:size-10 tw:shrink-0 tw:items-center tw:justify-center {{ $tonos[$it['tono']] }}" title="{{ $it['tipo'] }}">
                    <i data-lucide="{{ $it['icono'] }}" class="tw:size-[18px]"></i>
                    <span class="tw:sr-only">{{ $it['tipo'] }}</span>
                </span>

                {{-- Contenido --}}
                <div class="tw:min-w-0 tw:flex-1">
                    <div class="tw:flex tw:items-center tw:gap-2">
                        <p @class(['tw:m-0 tw:truncate tw:text-sm tw:font-semibold tw:text-slate-900', 'tw:font-mono' => $it['mono']])>{{ $it['titulo'] }}</p>
                        @if (!empty($it['urgente']))
                            <span class="tw:inline-flex tw:shrink-0 tw:items-center tw:gap-1 tw:text-[0.6875rem] tw:font-semibold tw:text-danger-600">
                                <span class="tw:size-1.5 tw:rounded-full tw:bg-danger-500"></span>Urgente
                            </span>
                        @endif
                    </div>
                    <p class="tw:m-0 tw:mt-1 tw:line-clamp-2 tw:text-[0.8125rem] tw:leading-relaxed tw:text-slate-500" title="{{ $it['detalle'] }}">{{ $it['detalle'] }}</p>
                    @if ($it['detalle2'])
                        <p class="tw:m-0 tw:mt-0.5 tw:text-xs tw:text-slate-400">{{ $it['detalle2'] }}</p>
                    @endif
                </div>

                {{-- Metadatos + acciones --}}
                <div class="tw:flex tw:shrink-0 tw:items-center tw:gap-5">
                    <div class="tw:hidden tw:w-24 tw:flex-col tw:items-end tw:gap-0.5 tw:text-right tw:md:flex">
                        @foreach (array_values($it['meta']) as $i => $m)
                            <span @class(['tw:font-mono tw:text-[0.6875rem] tw:leading-tight', 'tw:font-semibold tw:text-slate-600' => $i === 0, 'tw:text-slate-400' => $i > 0])>{{ $m }}</span>
                        @endforeach
                    </div>
                    <div class="tw:flex tw:w-[116px] tw:items-center tw:justify-end tw:gap-1">
                        @foreach ($it['acciones'] as $ac)
                            @if (!empty($ac['href']))
                                <x-notificaciones.accion :href="$ac['href']" :icon="$ac['icon']" :label="$ac['label']" :tone="$ac['tone']" />
                            @else
                                <x-notificaciones.accion :icon="$ac['icon']" :label="$ac['label']" :tone="$ac['tone']" onclick="{{ $ac['onclick'] }}" />
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </li>
        @endforeach

        {{-- Estado vacío --}}
        <li id="fila-vacia" @if($totalActivas > 0) hidden @endif>
            <div class="tw:flex tw:flex-col tw:items-center tw:border tw:border-dashed tw:border-slate-200 tw:bg-white tw:px-6 tw:py-14 tw:text-center">
                <span class="tw:inline-flex tw:size-12 tw:items-center tw:justify-center tw:bg-success-50 tw:text-success-700">
                    <i data-lucide="circle-check" class="tw:size-6"></i>
                </span>
                <p id="texto-vacio-titulo" class="tw:m-0 tw:mt-3 tw:text-[0.9375rem] tw:font-semibold tw:text-slate-900">Todo al corriente</p>
                <p id="texto-vacio-desc" class="tw:m-0 tw:mt-1 tw:text-[0.8125rem] tw:text-slate-500">No se encontraron avisos ni pendientes en esta sección.</p>
            </div>
        </li>
    </ul>
    @endif
</div>

<script>
let categoriaActual = 'todos';
const CSRF = '{{ csrf_token() }}';
const esc = (v) => String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
const contador = (id) => parseInt(document.getElementById(id)?.textContent, 10) || 0;

function filtrarAlertas(categoria, tab) {
    categoriaActual = categoria;
    document.querySelectorAll('[role="tab"][data-filtro]').forEach(t => t.setAttribute('aria-selected', t === tab ? 'true' : 'false'));

    const contLimpiar = document.getElementById('contenedor-btn-limpiar');
    if (contLimpiar) contLimpiar.hidden = !(categoria === 'resueltas' && contador('badge-pill-resueltas') > 0);

    let visibles = 0;
    document.querySelectorAll('.fila-alerta').forEach(fila => {
        const cat = fila.dataset.categoria;
        const mostrar = categoria === 'todos' ? cat !== 'resueltas' : cat === categoria;
        fila.hidden = !mostrar;
        if (mostrar) visibles++;
    });

    actualizarVacio(visibles);
}

function actualizarVacio(visibles) {
    const filaVacia = document.getElementById('fila-vacia');
    if (!filaVacia) return;
    filaVacia.hidden = visibles > 0;
    if (visibles === 0) {
        const historial = categoriaActual === 'resueltas';
        document.getElementById('texto-vacio-titulo').textContent = historial ? 'Sin registros históricos' : 'Todo al corriente';
        document.getElementById('texto-vacio-desc').textContent = historial
            ? 'No hay advertencias marcadas como resueltas u omitidas.'
            : 'No se encontraron avisos ni pendientes en esta sección.';
    }
}

function abrirModalResolver(tipo, idReferencia, nombre, detalle, subtipo, btnElement) {
    Swal.fire({
        title: '<span style="font-size: 1.15rem; font-weight: 700; color: #0F172A;">Gestionar advertencia</span>',
        html: `
            <div class="text-start p-2" style="font-size: 0.875rem;">
                <div class="mb-1" style="color:#0F172A;font-weight:600;">${esc(nombre)}</div>
                <div class="mb-3" style="color:#64748B;">${esc(detalle)}</div>
                <p class="mb-2 fw-semibold" style="color:#334155;">¿Cómo deseas clasificar esta advertencia?</p>
                <div class="form-check mb-2 p-2 rounded" style="background:#F0FDF4;border:1px solid #BBF7D0;">
                    <input class="form-check-input ms-1" type="radio" name="swal_accion" id="opt_resuelto" value="resuelto" checked>
                    <label class="form-check-label fw-semibold ms-2" for="opt_resuelto" style="cursor:pointer;color:#15803D;">
                        Ya se resolvió / Entregado
                        <div class="small fw-normal mt-1" style="color:#64748B;">El alumno ya entregó el documento o se completó el trámite pendiente.</div>
                    </label>
                </div>
                <div class="form-check mb-3 p-2 rounded" style="background:#F8FAFC;border:1px solid #E2E8F0;">
                    <input class="form-check-input ms-1" type="radio" name="swal_accion" id="opt_ignorar" value="ignorar">
                    <label class="form-check-label fw-semibold ms-2" for="opt_ignorar" style="cursor:pointer;color:#475569;">
                        No es alerta / Omitir
                        <div class="small fw-normal mt-1" style="color:#64748B;">No aplica para este caso o se autoriza como excepción permanente.</div>
                    </label>
                </div>
                <label class="form-label small mb-1" style="color:#64748B;" for="swal_motivo">Nota o motivo (opcional)</label>
                <input type="text" id="swal_motivo" class="form-control form-control-sm" placeholder="Ej. Entregó constancia física / Validado por control escolar">
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Aplicar y quitar alerta',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#26687B',
        cancelButtonColor: '#64748B',
        reverseButtons: true,
        focusConfirm: false,
        preConfirm: () => ({
            accion: document.querySelector('input[name="swal_accion"]:checked')?.value || 'resuelto',
            motivo: document.getElementById('swal_motivo')?.value?.trim() || ''
        })
    }).then((result) => {
        if (!result.isConfirmed) return;
        const { accion, motivo } = result.value;
        Swal.fire({ title: 'Actualizando…', text: 'Guardando estado de la advertencia', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        fetch('/notificaciones/resolver', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ tipo, id_referencia: idReferencia, accion, subtipo, motivo })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                Swal.fire({ icon: 'error', title: 'Error', text: data.error || 'No se pudo actualizar la advertencia.' });
                return;
            }
            Swal.fire({
                icon: 'success',
                title: '¡Advertencia actualizada!',
                text: accion === 'resuelto' ? 'Marcada como resuelta exitosamente.' : 'Descartada del panel de pendientes.',
                timer: 1500,
                showConfirmButton: false
            });

            const fila = btnElement.closest('.fila-alerta');
            const tarjeta = fila?.firstElementChild;
            if (tarjeta) {
                tarjeta.style.transition = 'opacity .3s ease, transform .3s ease';
                tarjeta.style.opacity = '0';
                tarjeta.style.transform = 'translateX(24px)';
            }
            setTimeout(() => { fila?.remove(); actualizarContadores(tipo); }, 300);
        })
        .catch(err => Swal.fire({ icon: 'error', title: 'Error de red', text: err.message }));
    });
}

function reactivarAlerta(tipo, idReferencia, subtipo, nombre) {
    Swal.fire({
        title: '¿Reactivar advertencia?',
        text: `¿Deseas volver a mostrar la advertencia para "${nombre}" en el panel de pendientes activos?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, reactivar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#26687B',
        cancelButtonColor: '#64748B'
    }).then((result) => {
        if (!result.isConfirmed) return;
        Swal.fire({ title: 'Reactivando…', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        fetch('/notificaciones/reactivar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ tipo, id_referencia: idReferencia, subtipo })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ icon: 'success', title: 'Reactivada', text: 'La advertencia volverá a mostrarse en los pendientes.', timer: 1200, showConfirmButton: false })
                    .then(() => window.location.reload());
            } else {
                Swal.fire('Error', data.error || 'No se pudo reactivar la advertencia.', 'error');
            }
        })
        .catch(err => Swal.fire('Error', err.message, 'error'));
    });
}

function limpiarResueltasOmitidas() {
    const count = contador('badge-pill-resueltas');
    if (count === 0) {
        Swal.fire({ icon: 'info', title: 'Sección vacía', text: 'No hay alertas resueltas u omitidas para limpiar.' });
        return;
    }

    Swal.fire({
        title: '¿Limpiar historial?',
        text: `Se vaciará el historial de resueltas y omitidas (${count} registros) sin reactivar las advertencias.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, limpiar todo',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#64748B'
    }).then((result) => {
        if (!result.isConfirmed) return;
        Swal.fire({ title: 'Limpiando…', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        fetch('/notificaciones/limpiar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ icon: 'success', title: '¡Historial limpio!', timer: 1500, showConfirmButton: false })
                    .then(() => window.location.reload());
            } else {
                Swal.fire('Error', data.error || 'No se pudo limpiar la sección.', 'error');
            }
        })
        .catch(err => Swal.fire('Error de red', err.message, 'error'));
    });
}

function actualizarContadores(tipo) {
    const setCount = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = v; };

    setCount(`badge-pill-${tipo}`, Math.max(0, contador(`badge-pill-${tipo}`) - 1));
    const total = Math.max(0, contador('badge-pill-todos') - 1);
    setCount('badge-pill-todos', total);
    setCount('badge-header-total', `${total} alertas`);
    setCount('badge-pill-resueltas', contador('badge-pill-resueltas') + 1);

    window.actualizarBadgeSidebarNotificaciones?.(total);

    const visibles = [...document.querySelectorAll('.fila-alerta')].filter(f => !f.hidden).length;
    actualizarVacio(visibles);
}

// Sincronizar el badge del sidebar con el conteo de la página
document.addEventListener('DOMContentLoaded', function () {
    window.actualizarBadgeSidebarNotificaciones?.({{ $totalActivas }});
});
</script>
@endsection
