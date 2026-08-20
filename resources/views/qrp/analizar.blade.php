@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/estilosQrp.css') }}">

<div class="container-fluid py-4">

    {{-- ============================================
         ENCABEZADO
    ============================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Analizar Estado de Cuenta
            </h2>

            <p class="text-muted mb-0">
                Carga un archivo QRP para analizar sus movimientos.
            </p>
        </div>

    </div>


    {{-- ============================================
         MENSAJES DE ERROR
    ============================================= --}}

    @if ($errors->any())

    <div class="alert alert-danger alert-dismissible fade show">

        <div class="fw-bold mb-2">

            <i class="fa-solid fa-triangle-exclamation me-2"></i>

            No se pudo procesar el archivo

        </div>

        <ul class="mb-0">

            @foreach ($errors->all() as $error)

            <li>{{ $error }}</li>

            @endforeach

        </ul>

        <button type="button" class="btn-close" data-bs-dismiss="alert">
        </button>

    </div>

    @endif


    {{-- ============================================
         FORMULARIO
    ============================================= --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body p-4">

            <form action="{{ route('analizar-estado-cuenta') }}" method="POST" enctype="multipart/form-data"
                id="formQrp">

                @csrf

                <div class="row align-items-end">

                    <div class="col-md-8">

                        <label for="archivo_qrp" class="form-label fw-semibold">
                            Archivo de Estado de Cuenta
                        </label>

                        <input type="file" name="archivo_qrp" id="archivo_qrp" class="form-control" accept=".qrp"
                            required>

                        <div class="form-text">

                            Selecciona un archivo con extensión
                            <strong>.QRP</strong>.

                        </div>

                    </div>


                    <div class="col-md-4 mt-3 mt-md-0">

                        <button type="submit" class="btn btn-primary w-100" id="btnAnalizar">

                            <i class="fa-solid fa-magnifying-glass me-2"></i>

                            Analizar Estado de Cuenta

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>
    



    {{-- ============================================
         RESULTADOS
    ============================================= --}}

    @if(isset($procesado) && $procesado)

    {{-- ARCHIVO PROCESADO --}}

    <div class="alert alert-success shadow-sm">

        <i class="fa-solid fa-circle-check me-2"></i>

        Archivo procesado correctamente:

        <strong>
            {{ $archivoProcesado }}
        </strong>

    </div>


    {{-- ========================================
             TARJETAS RESUMEN
        ========================================= --}}

    <div class="row g-3 mb-4">


        {{-- =====================================
                 MOVIMIENTOS
            ====================================== --}}

        <div class="col-xl col-md-6">

            <div class="card resumen-card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Movimientos
                            </div>

                            <div class="fs-3 fw-bold">
                                {{ $totalMovimientos }}
                            </div>

                        </div>

                        <div class="resumen-icon bg-primary-subtle text-primary">

                            <i class="fa-solid fa-list"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================
                 COLEGIATURAS
            ====================================== --}}

        <div class="col-xl col-md-6">

            <div class="card resumen-card resumen-clickable shadow-sm border-0 h-100"
                onclick="mostrarDetalle('Colegiatura')" role="button" tabindex="0">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Colegiaturas
                            </div>

                            <div class="fs-3 fw-bold">

                                {{ $resumen['Colegiatura']['cantidad'] ?? 0 }}

                            </div>

                            <div class="text-muted">

                                ${{ number_format(
                                        $resumen['Colegiatura']['importe'] ?? 0,
                                        2
                                    ) }}

                            </div>

                            <div class="detalle-link mt-2">

                                <i class="fa-solid fa-eye me-1"></i>

                                Ver detalles

                            </div>

                        </div>


                        <div class="resumen-icon bg-success-subtle text-success">

                            <i class="fa-solid fa-graduation-cap"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================
                 EXÁMENES
            ====================================== --}}

        <div class="col-xl col-md-6">

            <div class="card resumen-card resumen-clickable shadow-sm border-0 h-100" onclick="mostrarDetalle('Examen')"
                role="button" tabindex="0">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Exámenes
                            </div>

                            <div class="fs-3 fw-bold">

                                {{ $resumen['Examen']['cantidad'] ?? 0 }}

                            </div>

                            <div class="text-muted">

                                ${{ number_format(
                                        $resumen['Examen']['importe'] ?? 0,
                                        2
                                    ) }}

                            </div>

                            <div class="detalle-link mt-2">

                                <i class="fa-solid fa-eye me-1"></i>

                                Ver detalles

                            </div>

                        </div>


                        <div class="resumen-icon bg-warning-subtle text-warning">

                            <i class="fa-solid fa-file-pen"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================
                 OTROS
            ====================================== --}}

        <div class="col-xl col-md-6">

            <div class="card resumen-card resumen-clickable shadow-sm border-0 h-100" onclick="mostrarDetalle('Otros')"
                role="button" tabindex="0">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Otros
                            </div>

                            <div class="fs-3 fw-bold">

                                {{ $resumen['Otros']['cantidad'] ?? 0 }}

                            </div>

                            <div class="text-muted">

                                ${{ number_format(
                                        $resumen['Otros']['importe'] ?? 0,
                                        2
                                    ) }}

                            </div>

                            <div class="detalle-link mt-2">

                                <i class="fa-solid fa-eye me-1"></i>

                                Ver detalles

                            </div>

                        </div>


                        <div class="resumen-icon bg-secondary-subtle text-secondary">

                            <i class="fa-solid fa-folder"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================
                 TOTAL PAGADO
            ====================================== --}}

        @php

        $totalPagado = collect($movimientos ?? [])
        ->sum('total');

        @endphp


        <div class="col-xl col-md-6">

            <div class="card resumen-card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Total pagado
                            </div>

                            <div class="fs-3 fw-bold text-primary">

                                ${{ number_format(
                                        $totalPagado,
                                        2
                                    ) }}

                            </div>

                        </div>


                        <div class="resumen-icon bg-primary-subtle text-primary">

                            <i class="fa-solid fa-dollar-sign"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================
             TABLA GENERAL
        ========================================= --}}

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">

                        <i class="fa-solid fa-table me-2"></i>

                        Movimientos del Estado de Cuenta

                    </h5>

                    <small class="text-muted">

                        {{ $totalMovimientos }} movimientos encontrados

                    </small>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>Recibo</th>

                            <th>Fecha</th>

                            <th>Concepto</th>

                            <th>Categoría</th>

                            <th class="text-center">
                                Cantidad
                            </th>

                            <th class="text-end">
                                Importe
                            </th>

                            <th class="text-end">
                                Recargo
                            </th>

                            <th class="text-end">
                                Total
                            </th>

                            <th>Usuario</th>

                            <th>Hora</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($movimientos as $movimiento)

                        <tr>

                            <td>

                                <span class="fw-semibold">

                                    {{ $movimiento['recibo'] }}

                                </span>

                            </td>


                            <td>

                                {{ $movimiento['fecha'] }}

                            </td>


                            <td>

                                {{ $movimiento['concepto'] }}

                            </td>


                            <td>

                                @if($movimiento['categoria'] === 'Colegiatura')

                                <span class="badge bg-success-subtle text-success">

                                    <i class="fa-solid fa-graduation-cap me-1"></i>

                                    Colegiatura

                                </span>

                                @elseif($movimiento['categoria'] === 'Examen')

                                <span class="badge bg-warning-subtle text-warning">

                                    <i class="fa-solid fa-file-pen me-1"></i>

                                    Examen

                                </span>

                                @elseif($movimiento['categoria'] === 'Otros')

                                <span class="badge bg-secondary-subtle text-secondary">

                                    <i class="fa-solid fa-folder me-1"></i>

                                    Otros

                                </span>

                                @else

                                <span class="badge bg-light text-dark">

                                    Sin concepto

                                </span>

                                @endif

                            </td>


                            <td class="text-center">

                                {{ $movimiento['cantidad_colegiaturas'] ?? 0 }}

                            </td>


                            <td class="text-end">

                                ${{ number_format(
                                            $movimiento['importe'] ?? 0,
                                            2
                                        ) }}

                            </td>


                            <td class="text-end">

                                ${{ number_format(
                                            $movimiento['recargo'] ?? 0,
                                            2
                                        ) }}

                            </td>


                            <td class="text-end fw-semibold">

                                ${{ number_format(
                                            $movimiento['total'] ?? 0,
                                            2
                                        ) }}

                            </td>


                            <td>

                                {{ $movimiento['usuario'] ?? '' }}

                            </td>


                            <td>

                                {{ $movimiento['hora'] ?? '' }}

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="10" class="text-center py-5 text-muted">

                                <i class="fa-solid fa-inbox fa-2x mb-3"></i>

                                <div>
                                    No se encontraron movimientos.
                                </div>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    @endif

</div>


{{-- =========================================================
     MODAL DETALLE
========================================================= --}}

@if(isset($procesado) && $procesado)

<div class="modal fade" id="modalDetalle" tabindex="-1" aria-labelledby="modalDetalleTitulo" aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content border-0 shadow-lg">


            {{-- HEADER --}}

            <div class="modal-header">

                <div>

                    <h5 class="modal-title fw-bold" id="modalDetalleTitulo">

                        <i class="fa-solid fa-list me-2"></i>

                        Detalle

                    </h5>

                    <small class="text-muted" id="modalDetalleSubtitulo">

                        Movimientos encontrados

                    </small>

                </div>


                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>

            </div>


            {{-- BODY --}}

            <div class="modal-body">


                {{-- =====================================
                     RESUMEN
                ====================================== --}}

                <div class="row g-3 mb-4">


                    {{-- CANTIDAD --}}

                    <div class="col-md-4">

                        <div class="detalle-resumen">

                            <div class="text-muted small">

                                Movimientos

                            </div>

                            <div class="fs-4 fw-bold" id="detalleCantidad">
                                0
                            </div>

                        </div>

                    </div>


                    {{-- IMPORTE --}}

                    <div class="col-md-4">

                        <div class="detalle-resumen">

                            <div class="text-muted small">

                                Importe

                            </div>

                            <div class="fs-4 fw-bold" id="detalleImporte">
                                $0.00
                            </div>

                        </div>

                    </div>


                    {{-- TOTAL --}}

                    <div class="col-md-4">

                        <div class="detalle-resumen">

                            <div class="text-muted small">

                                Total

                            </div>

                            <div class="fs-4 fw-bold text-primary" id="detalleTotal">
                                $0.00
                            </div>

                        </div>

                    </div>

                </div>


                {{-- =====================================
                     TABLA DETALLE
                ====================================== --}}

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>Recibo</th>

                                <th>Fecha</th>

                                <th>Concepto</th>

                                <th class="text-center">
                                    Cantidad
                                </th>

                                <th class="text-end">
                                    Importe
                                </th>

                                <th class="text-end">
                                    Recargo
                                </th>

                                <th class="text-end">
                                    Total
                                </th>

                                <th>Usuario</th>

                                <th>Hora</th>

                            </tr>

                        </thead>


                        <tbody id="detalleTabla">

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- FOOTER --}}

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                    <i class="fa-solid fa-xmark me-1"></i>

                    Cerrar

                </button>

            </div>

        </div>

    </div>

</div>

@endif


{{-- =========================================================
     ESTILOS
========================================================= --}}

<style>
/* ============================================
   TARJETAS RESUMEN
============================================ */

.resumen-card {

    transition:
        transform .2s ease,
        box-shadow .2s ease;

}


.resumen-card:hover {

    transform: translateY(-3px);

    box-shadow:
        0 .5rem 1rem rgba(0, 0, 0, .12) !important;

}


/* ============================================
   TARJETAS CLICKEABLES
============================================ */

.resumen-clickable {

    cursor: pointer;

    user-select: none;

}


.resumen-clickable:hover {

    transform: translateY(-5px);

    box-shadow:
        0 .75rem 1.5rem rgba(0, 0, 0, .16) !important;

}


.resumen-clickable:active {

    transform: translateY(-2px);

}


/* ============================================
   ENLACE VER DETALLES
============================================ */

.detalle-link {

    font-size: .85rem;

    font-weight: 600;

    color: #0d6efd;

    opacity: .85;

    transition:
        opacity .2s ease;

}


.resumen-clickable:hover .detalle-link {

    opacity: 1;

}


/* ============================================
   ICONOS
============================================ */

.resumen-icon {

    width: 48px;

    height: 48px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 12px;

    font-size: 20px;

}


/* ============================================
   TABLA PRINCIPAL
============================================ */

.table th {

    white-space: nowrap;

    font-size: .85rem;

}


.table td {

    font-size: .9rem;

}


/* ============================================
   BOTÓN ANALIZAR
============================================ */

#btnAnalizar {

    height: 42px;

}


/* ============================================
   MODAL
============================================ */

#modalDetalle .modal-content {

    border-radius: 16px;

    overflow: hidden;

}


#modalDetalle .modal-header {

    padding: 1.25rem 1.5rem;

    background: #f8f9fa;

    border-bottom: 1px solid #e9ecef;

}


#modalDetalle .modal-body {

    padding: 1.5rem;

}


/* ============================================
   RESUMEN DENTRO DEL MODAL
============================================ */

.detalle-resumen {

    background: #f8f9fa;

    border-radius: 12px;

    padding: 15px 18px;

    border: 1px solid #e9ecef;

}


/* ============================================
   TABLA DEL MODAL
============================================ */

#modalDetalle .table th {

    white-space: nowrap;

    font-size: .82rem;

}


#modalDetalle .table td {

    font-size: .88rem;

}


#modalDetalle .table tbody tr {

    transition:
        background-color .15s ease;

}


/* ============================================
   RESPONSIVE
============================================ */

@media (max-width: 768px) {

    #modalDetalle .modal-body {

        padding: 1rem;

    }

    #modalDetalle .table {

        min-width: 900px;

    }

}
</style>


{{-- =========================================================
     JAVASCRIPT
========================================================= --}}

<script>
document.addEventListener('DOMContentLoaded', function() {


    /* ============================================
       FORMULARIO QRP
    ============================================ */

    const formulario =
        document.getElementById('formQrp');


    const archivo =
        document.getElementById('archivo_qrp');


    const boton =
        document.getElementById('btnAnalizar');


    if (formulario) {

        formulario.addEventListener('submit', function() {

            if (!archivo || !archivo.files.length) {

                return;

            }


            boton.disabled = true;


            boton.innerHTML = `

                <span
                    class="spinner-border spinner-border-sm me-2"
                    role="status"
                    aria-hidden="true"
                ></span>

                Analizando QRP...

            `;

        });

    }


    /* ============================================
       ACCESIBILIDAD DE LAS TARJETAS
    ============================================ */

    document
        .querySelectorAll('.resumen-clickable')
        .forEach(function(tarjeta) {

            tarjeta.addEventListener('keydown', function(event) {

                if (
                    event.key === 'Enter' ||
                    event.key === ' '
                ) {

                    event.preventDefault();

                    tarjeta.click();

                }

            });

        });

});


/* =========================================================
   DATOS DE LOS MOVIMIENTOS
========================================================= */

const movimientosQrp = @json($movimientos ?? []);


/* =========================================================
   MOSTRAR DETALLE
========================================================= */

function mostrarDetalle(categoria) {


    /* ============================================
       FILTRAR MOVIMIENTOS
    ============================================ */

    const movimientos = movimientosQrp.filter(function(movimiento) {

        return movimiento.categoria === categoria;

    });


    /* ============================================
       CONFIGURACIÓN DEL TÍTULO
    ============================================ */

    let titulo = 'Detalle';

    let icono = 'fa-list';


    if (categoria === 'Colegiatura') {

        titulo = 'Detalle de Colegiaturas';

        icono = 'fa-graduation-cap';

    } else if (categoria === 'Examen') {

        titulo = 'Detalle de Exámenes';

        icono = 'fa-file-pen';

    } else if (categoria === 'Otros') {

        titulo = 'Detalle de Otros conceptos';

        icono = 'fa-folder';

    }


    /* ============================================
       ACTUALIZAR TÍTULO
    ============================================ */

    const tituloElemento =
        document.getElementById('modalDetalleTitulo');


    tituloElemento.innerHTML = `

        <i class="fa-solid ${icono} me-2"></i>

        ${escapeHtml(titulo)}

    `;


    /* ============================================
       SUBTÍTULO
    ============================================ */

    document
        .getElementById('modalDetalleSubtitulo')
        .textContent =
        `${movimientos.length} movimiento(s) encontrado(s)`;


    /* ============================================
       CALCULAR TOTALES
    ============================================ */

    let importe = 0;

    let total = 0;


    movimientos.forEach(function(movimiento) {

        importe += parseFloat(
            movimiento.importe || 0
        );


        total += parseFloat(
            movimiento.total || 0
        );

    });


    /* ============================================
       MOSTRAR RESUMEN
    ============================================ */

    document
        .getElementById('detalleCantidad')
        .textContent =
        movimientos.length;


    document
        .getElementById('detalleImporte')
        .textContent =
        formatoMoneda(importe);


    document
        .getElementById('detalleTotal')
        .textContent =
        formatoMoneda(total);


    /* ============================================
       TABLA
    ============================================ */

    const tabla =
        document.getElementById('detalleTabla');


    tabla.innerHTML = '';


    /* ============================================
       SIN MOVIMIENTOS
    ============================================ */

    if (!movimientos.length) {

        tabla.innerHTML = `

            <tr>

                <td
                    colspan="9"
                    class="text-center text-muted py-5"
                >

                    <i
                        class="fa-solid fa-inbox fa-2x mb-3"
                    ></i>

                    <div>

                        No existen movimientos
                        para esta categoría.

                    </div>

                </td>

            </tr>

        `;

    }


    /* ============================================
       MOSTRAR MOVIMIENTOS
    ============================================ */
    else {

        movimientos.forEach(function(movimiento) {


            const fila =
                document.createElement('tr');


            fila.innerHTML = `

                <td>

                    <span class="fw-semibold">

                        ${escapeHtml(
                            movimiento.recibo ?? ''
                        )}

                    </span>

                </td>


                <td>

                    ${escapeHtml(
                        movimiento.fecha ?? ''
                    )}

                </td>


                <td>

                    ${escapeHtml(
                        movimiento.concepto ?? ''
                    )}

                </td>


                <td class="text-center">

                    ${escapeHtml(
                        String(
                            movimiento.cantidad_colegiaturas ?? 0
                        )
                    )}

                </td>


                <td class="text-end">

                    ${formatoMoneda(
                        movimiento.importe
                    )}

                </td>


                <td class="text-end">

                    ${formatoMoneda(
                        movimiento.recargo
                    )}

                </td>


                <td class="text-end fw-semibold">

                    ${formatoMoneda(
                        movimiento.total
                    )}

                </td>


                <td>

                    ${escapeHtml(
                        movimiento.usuario ?? ''
                    )}

                </td>


                <td>

                    ${escapeHtml(
                        movimiento.hora ?? ''
                    )}

                </td>

            `;


            tabla.appendChild(fila);

        });

    }


    /* ============================================
       ABRIR MODAL
    ============================================ */

    const modalElemento =
        document.getElementById('modalDetalle');


    if (!modalElemento) {

        return;

    }


    const modal =
        bootstrap.Modal.getOrCreateInstance(
            modalElemento
        );


    modal.show();

}


/* =========================================================
   FORMATO DE MONEDA
========================================================= */

function formatoMoneda(valor) {

    const numero =
        parseFloat(valor || 0);


    return new Intl.NumberFormat(
        'es-MX', {
            style: 'currency',
            currency: 'MXN',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }
    ).format(numero);

}


/* =========================================================
   ESCAPAR HTML
========================================================= */

function escapeHtml(valor) {

    const div =
        document.createElement('div');


    div.textContent =
        String(valor ?? '');


    return div.innerHTML;

}
</script>

@endsection