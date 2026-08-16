@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- ============================================
         ENCABEZADO
    ============================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="fa-solid fa-file-invoice-dollar me-2"></i>
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

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ============================================
         FORMULARIO
    ============================================= --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body p-4">

            <form
                action="{{ route('analizar-estado-cuenta') }}"
                method="POST"
                enctype="multipart/form-data"
                id="formQrp"
            >

                @csrf

                <div class="row align-items-end">

                    <div class="col-md-8">

                        <label
                            for="archivo_qrp"
                            class="form-label fw-semibold"
                        >
                            Archivo de Estado de Cuenta
                        </label>

                        <input
                            type="file"
                            name="archivo_qrp"
                            id="archivo_qrp"
                            class="form-control"
                            accept=".qrp"
                            required
                        >

                        <div class="form-text">
                            Selecciona un archivo con extensión
                            <strong>.QRP</strong>.
                        </div>

                    </div>


                    <div class="col-md-4 mt-3 mt-md-0">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                            id="btnAnalizar"
                        >

                            <i class="fa-solid fa-magnifying-glass me-2"></i>

                            Analizar Estado de Cuenta

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    @if(isset($procesado) && $procesado)

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


            {{-- MOVIMIENTOS --}}

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


            {{-- COLEGIATURAS --}}

            <div class="col-xl col-md-6">

                <div class="card resumen-card shadow-sm border-0 h-100">

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

                            </div>

                            <div class="resumen-icon bg-success-subtle text-success">

                                <i class="fa-solid fa-graduation-cap"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- EXÁMENES --}}

            <div class="col-xl col-md-6">

                <div class="card resumen-card shadow-sm border-0 h-100">

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

                            </div>

                            <div class="resumen-icon bg-warning-subtle text-warning">

                                <i class="fa-solid fa-file-pen"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- OTROS --}}

            <div class="col-xl col-md-6">

                <div class="card resumen-card shadow-sm border-0 h-100">

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

                            </div>

                            <div class="resumen-icon bg-secondary-subtle text-secondary">

                                <i class="fa-solid fa-folder"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- TOTAL --}}

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
             TABLA
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

                                                Colegiatura

                                            </span>

                                        @elseif($movimiento['categoria'] === 'Examen')

                                            <span class="badge bg-warning-subtle text-warning">

                                                Examen

                                            </span>

                                        @elseif($movimiento['categoria'] === 'Otros')

                                            <span class="badge bg-secondary-subtle text-secondary">

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
                                            $movimiento['importe'],
                                            2
                                        ) }}

                                    </td>


                                    <td class="text-end">

                                        ${{ number_format(
                                            $movimiento['recargo'],
                                            2
                                        ) }}

                                    </td>


                                    <td class="text-end fw-semibold">

                                        ${{ number_format(
                                            $movimiento['total'],
                                            2
                                        ) }}

                                    </td>


                                    <td>

                                        {{ $movimiento['usuario'] }}

                                    </td>


                                    <td>

                                        {{ $movimiento['hora'] }}

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="10"
                                        class="text-center py-5 text-muted"
                                    >

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


{{-- ================================================
     ESTILOS
================================================ --}}

<style>

.resumen-card {

    transition:
        transform .2s ease,
        box-shadow .2s ease;

}

.resumen-card:hover {

    transform: translateY(-3px);

    box-shadow:
        0 .5rem 1rem rgba(0,0,0,.12) !important;

}


.resumen-icon {

    width: 48px;
    height: 48px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 12px;

    font-size: 20px;

}


.table th {

    white-space: nowrap;

    font-size: .85rem;

}


.table td {

    font-size: .9rem;

}


#btnAnalizar {

    height: 42px;

}

</style>


{{-- ================================================
     JAVASCRIPT
================================================ --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const formulario =
        document.getElementById('formQrp');

    const archivo =
        document.getElementById('archivo_qrp');

    const boton =
        document.getElementById('btnAnalizar');


    formulario.addEventListener('submit', function () {

        if (!archivo.files.length) {

            return;

        }


        boton.disabled = true;


        boton.innerHTML = `

            <span
                class="spinner-border spinner-border-sm me-2"
                role="status"
            ></span>

            Analizando QRP...

        `;

    });

});

</script>

@endsection