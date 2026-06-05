@extends('layouts.app')

@section('content')

<style>
html,
body {
    margin: 0;
    padding: 0;
    height: 100%;
}

/* FONDO AZUL */
body {
    background: #1A338F;
}

/* CONTENEDOR PRINCIPAL */
.hero {
    background: #1A338F;
    min-height: 100vh;
    padding: 60px 20px;
    color: white;
    position: relative;
}

.title {
    text-align: center;
    font-weight: bold;
    margin-bottom: 50px;
}

/* BOTÓN REGRESAR */
.btn-back {
    display: inline-block;
    background: white;
    color: #1A338F;
    padding: 10px 20px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: bold;
    margin-bottom: 30px;
    transition: all 0.3s ease;

    position: absolute;
    top: 20px;
    left: 20px;
}

.btn-back:hover {
    background: #f0f0f0;
    color: #1A338F;
}

/* CARDS */
.portal-card {
    background: transparent;
    border: 2px solid #ffffff;
    border-radius: 15px;
    padding: 30px 20px;
    text-align: center;
    color: white;
    transition: all 0.3s ease;
    min-height: 220px;

    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.portal-card svg {
    width: 60px;
    height: 60px;
    fill: white;
    margin-bottom: 20px;
}

.portal-card h5 {
    font-weight: 600;
    margin: 0;
}

/* HOVER */
.portal-card:hover {
    background: rgba(255, 255, 255, .1);
    transform: translateY(-8px) scale(1.03);
    box-shadow: 0 10px 25px rgba(0, 0, 0, .2);
}

a {
    text-decoration: none;
}
</style>

<div class="hero">
<!-- BOTÓN REGRESAR -->
        <a href="{{ url()->previous() }}" class="btn-back">
            ← Regresar
        </a>

    <div class="container">

        <h1 class="title">
            Kardex Sistema No escolarizado
        </h1>

        <div class="row justify-content-center g-4">

            <!-- SÁBADO -->
            <div class="col-md-3">
                <a href="">
                        <a href="{{ route('kardex_bgneS') }}" style="text-decoration:none;">
                            <div class="portal-card"><img src="{{ asset('img/SAB.png') }}" alt="Listas de asistencias-Escolarizado"
                            style="width: 100%; max-width: 100px; height: auto; color: white"> <br>
                                <h5>Kardex-Sábados</h5>
                            </div>
                        </a>
                </a>
            </div>

            <!-- DOMINGO -->
            <div class="col-md-3">
                <a href="">
                        <a href="{{ route('kardex_bgneD') }}" style="text-decoration:none;">
                            <div class="portal-card"><img src="{{ asset('img/DOM.png') }}" alt="Listas de asistencias-Escolarizado"
                            style="width: 100%; max-width: 100px; height: auto; color: white"> <br>
                                <h5>Kardex-Domingos</h5>
                            </div>
                        </a>
                </a>
            </div>




        </div>

    </div>

</div>

@endsection