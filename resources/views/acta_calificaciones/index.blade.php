@extends('layouts.app')

@section('content')

<style>

html,
body {
    margin: 0;
    padding: 0;
    min-height: 100%;
}

/* FONDO PRINCIPAL */
body {
    background: linear-gradient(
        135deg,
        #0F2E6D 0%,
        #1E6FA8 50%,
        #6BC7E8 100%
    );
}

/* CONTENEDOR */
.hero {
    background: transparent;
    min-height: 100vh;
    padding: 60px 20px;
    color: white;
    position: relative;
}

/* TÍTULO */
.title {
    text-align: center;
    font-weight: 700;
    margin-bottom: 50px;
    text-shadow: 0 2px 8px rgba(0,0,0,.2);
}

/* BOTÓN REGRESAR */
.btn-back {
    background: rgba(255,255,255,.12);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,.2);
    color: white;

    padding: 10px 20px;
    border-radius: 12px;

    text-decoration: none;
    font-weight: 600;

    transition: all .3s ease;

    position: absolute;
    top: 20px;
    left: 20px;
}

.btn-back:hover {
    background: rgba(255,255,255,.2);
    color: white;
    transform: translateY(-2px);
}

/* TARJETAS */
.portal-card {
    background: rgba(255,255,255,.08);
    backdrop-filter: blur(12px);

    border: 1px solid rgba(255,255,255,.2);
    border-radius: 20px;

    padding: 30px 20px;

    text-align: center;
    color: white;

    min-height: 230px;

    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;

    transition: all .3s ease;
    box-shadow: 0 5px 15px rgba(0,0,0,.15);
}

.portal-card img {
    max-width: 100px;
    margin-bottom: 20px;
    transition: .3s;
}

.portal-card h5 {
    font-weight: 600;
    line-height: 1.4;
    margin: 0;
}

/* EFECTO HOVER */
.portal-card:hover {
    background: rgba(255,255,255,.12);
    transform: translateY(-8px) scale(1.03);
    box-shadow: 0 12px 25px rgba(0,0,0,.25);
}

.portal-card:hover img {
    transform: scale(1.08);
}

a {
    text-decoration: none;
}

</style>

<div class="hero">
       <!-- BOTÓN REGRESAR -->
        <a href="{{ url()->previous() }}" class="btn-back">
    <i class="fa-solid fa-arrow-left me-2"></i>
    Regresar
</a>


    <div class="container">

     
        <h1 class="title">
    <i class="fa-solid fa-file-signature me-2"></i>
    Actas de Calificaciones
</h1>

        <div class="row justify-content-center g-4">

            <!-- SÁBADO -->
            <div class="col-md-3">
                <a href="{{ route('actas_calificacionesBGNES') }}">
                    <div class="portal-card">
                        <img src="{{ asset('img/SAB.png') }}"
                             alt="Actas de calificaciones-Sábados"
                             style="width: 100%; max-width: 100px; height: auto;">
                        <br>
                        <h5>Actas de calificaciones-Sábados</h5>
                    </div>
                </a>
            </div>

            <!-- DOMINGO -->
            <div class="col-md-3">
                <a href="{{ route('actas_calificacionesBGNED') }}">
                    <div class="portal-card">
                        <img src="{{ asset('img/DOM.png') }}"
                             alt="Actas de calificaciones-Domingos"
                             style="width: 100%; max-width: 100px; height: auto;">
                        <br>
                        <h5>Actas de calificaciones-Domingos</h5>
                    </div>
                </a>
            </div>

            <!-- ESCOLARIZADO -->
            <div class="col-md-3">
                <a href="{{ route('actas_calificacionesBTI') }}">
                    <div class="portal-card">
                        <img src="{{ asset('img/L_V.png') }}"
                             alt="Actas de calificaciones-Escolarizado"
                             style="width: 100%; max-width: 100px; height: auto;">
                        <br>
                        <h5>Actas de calificaciones-Escolarizado</h5>
                    </div>
                </a>
            </div>

        </div>

    </div>

</div>

@endsection