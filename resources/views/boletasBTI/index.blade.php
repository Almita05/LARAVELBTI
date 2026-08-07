@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/estilosBTI.css') }}">

<div class="page-container">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <a href="{{ url()->previous() }}" class="btn btn-regresar">
            <i class="fa-solid fa-arrow-left me-2"></i>
            Regresar
        </a>

        <h3 class="page-title mb-0">
            CAPTURA DE CALIFICACIONES
        </h3>

    </div>


    {{-- Contenedor principal --}}
    <div class="glass-card">

        {{-- Encabezado de la tarjeta --}}
        <div class="glass-header p-3">

            <h5 class="mb-0 textoDocentes">
                Selecciona el semestre
            </h5>

        </div>


        {{-- Semestres --}}
        <div class="row g-4 p-4">

            {{-- 1ER SEMESTRE --}}
            <div class="col-12 col-md-6 col-lg-4">
                <div class="portal-bti">

                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wallet2" viewBox="0 0 16 16">
  <path d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
</svg>

                    <h5>
                        <i class="fa-solid fa-graduation-cap me-2"></i>
                        1ER SEMESTRE
                    </h5>

                    <h6>Referencia:</h6>

                    <button type="button" class="btn btn-primary" onclick="abrirSemestre(1)">
                        Capturar calificaciones
                    </button>

                </div>
            </div>


            {{-- 2DO SEMESTRE --}}
            <div class="col-12 col-md-6 col-lg-4">
                <div class="portal-bti">

                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wallet2" viewBox="0 0 16 16">
  <path d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
</svg>

                    <h5>2DO SEMESTRE</h5>

                    <h6>
                        Referencia: Mora Herrera Victoria Kiran
                    </h6>

                    <button type="button" class="btn btn-primary" onclick="abrirSemestre(2)">
                        Capturar calificaciones
                    </button>

                </div>
            </div>


            {{-- 3ER SEMESTRE --}}
            <div class="col-12 col-md-6 col-lg-4">
                <div class="portal-bti">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wallet2" viewBox="0 0 16 16">
  <path d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
</svg>

                    <h5>3ER SEMESTRE</h5>

                    <h6>Referencia:</h6>

                    <button type="button" class="btn btn-primary" onclick="abrirSemestre(3)">
                        Capturar calificaciones
                    </button>

                </div>
            </div>


            {{-- 4TO SEMESTRE --}}
            <div class="col-12 col-md-6 col-lg-4">
                <div class="portal-bti">

                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wallet2" viewBox="0 0 16 16">
  <path d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
</svg>

                    <h5>4TO SEMESTRE</h5>

                    <h6>
                        Referencia: Leal Hernandez Yael
                    </h6>

                    <button type="button" class="btn btn-primary" onclick="abrirSemestre(4)">
                        Capturar calificaciones
                    </button>

                </div>
            </div>


            {{-- 5TO SEMESTRE --}}
            <div class="col-12 col-md-6 col-lg-4">
                <div class="portal-bti">

                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wallet2" viewBox="0 0 16 16">
  <path d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
</svg>

                    <h5>5TO SEMESTRE</h5>

                    <h6>Referencia:</h6>

                    <button type="button" class="btn btn-primary" onclick="abrirSemestre(5)">
                        Capturar calificaciones
                    </button>

                </div>
            </div>


            {{-- 6TO SEMESTRE --}}
            <div class="col-12 col-md-6 col-lg-4">
                <div class="portal-bti">

                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wallet2" viewBox="0 0 16 16">
  <path d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
</svg>

                    <h5>6TO SEMESTRE</h5>

                    <h6>
                        Referencia: Gomez Trujillo Zaratustra Netzahualcóyotl
                    </h6>

                    <button type="button" class="btn btn-primary" onclick="abrirSemestre(6)">
                        Capturar calificaciones
                    </button>

                </div>
            </div>

        </div>


        {{-- Footer --}}
        <div class="glass-footer p-3 d-flex justify-content-between align-items-center">

            <small id="infoPaginacion"></small>

            <div id="paginacion"></div>

        </div>

    </div>
</div>



@endsection

@include('boletasBTI.modalAlta')