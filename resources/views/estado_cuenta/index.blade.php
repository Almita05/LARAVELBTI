@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- ENCABEZADO --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Analizador de Estado de Cuenta
            </h2>

            <p class="text-muted mb-0">
                Analiza archivos QRP y consulta los movimientos detectados.
            </p>

        </div>

    </div>


    {{-- CARGAR ARCHIVO --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <h5 class="fw-bold mb-3">

                <i class="fa-solid fa-folder-open me-2"></i>

                Seleccionar estado de cuenta

            </h5>

            @if(session('success'))

    <div class="alert alert-success alert-dismissible fade show">

        <i class="fa-solid fa-circle-check me-2"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

@endif

@if($errors->any())

    <div class="alert alert-danger">

        <strong>
            No se pudo procesar el archivo.
        </strong>

        <ul class="mb-0 mt-2">

            @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


            <form action="{{ route('estado_cuenta.analizar') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">

                    <label for="archivo" class="form-label">
                        Seleccionar archivo QRP
                    </label>

                    <input type="file" name="archivo" id="archivo" class="form-control" accept=".qrp" required>

                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-chart-column me-2"></i>
                    Analizar estado de cuenta
                </button>

            </form>

        </div>

    </div>


    {{-- TARJETAS --}}
    <div class="row g-3 mb-4">

        {{-- MOVIMIENTOS --}}
        <div class="col-md-6 col-xl">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        MOVIMIENTOS
                    </div>

                    <div class="fs-3 fw-bold mt-2">
                        0
                    </div>

                </div>

            </div>

        </div>


        {{-- COLEGIATURAS --}}
        <div class="col-md-6 col-xl">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        COLEGIATURAS
                    </div>

                    <div class="fs-3 fw-bold mt-2">
                        0
                    </div>

                    <div class="text-muted">
                        $0.00
                    </div>

                </div>

            </div>

        </div>


        {{-- EXÁMENES --}}
        <div class="col-md-6 col-xl">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        EXÁMENES
                    </div>

                    <div class="fs-3 fw-bold mt-2">
                        0
                    </div>

                    <div class="text-muted">
                        $0.00
                    </div>

                </div>

            </div>

        </div>


        {{-- OTROS --}}
        <div class="col-md-6 col-xl">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        OTROS
                    </div>

                    <div class="fs-3 fw-bold mt-2">
                        0
                    </div>

                    <div class="text-muted">
                        $0.00
                    </div>

                </div>

            </div>

        </div>


        {{-- TOTAL --}}
        <div class="col-md-6 col-xl">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        TOTAL PAGADO
                    </div>

                    <div class="fs-3 fw-bold mt-2">
                        $0.00
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- TABLA --}}
    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="fw-bold mb-0">

                    <i class="fa-solid fa-table me-2"></i>

                    Movimientos

                </h5>


                <button type="button" class="btn btn-success btn-sm" disabled>

                    <i class="fa-solid fa-file-csv me-1"></i>

                    Exportar CSV

                </button>

            </div>


            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>Recibo</th>

                            <th>Fecha</th>

                            <th>Concepto</th>

                            <th>Categoría</th>

                            <th>Cantidad</th>

                            <th>Importe</th>

                            <th>Recargo</th>

                            <th>Total</th>

                            <th>Usuario</th>

                            <th>Hora</th>

                        </tr>

                    </thead>

                    <tbody>

                        <tr>

                            <td colspan="10" class="text-center text-muted py-5">

                                <i class="fa-solid fa-file-circle-question fs-2 mb-3"></i>

                                <div>
                                    Selecciona un archivo QRP para comenzar.
                                </div>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection