@extends('layouts.app')

@section('content')

<style>

/* HERO */
.hero {
    background: transparent;
    min-height: 100vh;
    padding: 60px 20px;
    color: white;
    position: relative;
}



/* BOTÓN REGRESAR */
.btn-back {
    background: rgba(255, 255, 255, .12);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, .2);
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
    background: rgba(255, 255, 255, .2);
    color: white;
    transform: translateY(-2px);
}


/* CONTENEDOR */
.container {
    min-height: 100vh;
    padding-top: 90px;
    padding-bottom: 40px;
}
.title {
    text-align: center;
    font-weight: bold;
    margin-bottom: 50px;
}



/* TARJETAS */
.portal-card {
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);

    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 18px;

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
    width: 100%;
    max-width: 110px;
    height: auto;
    margin-bottom: 20px;
    transition: .3s;
}

.portal-card h5 {
    font-weight: 600;
    margin: 0;
}

/* HOVER */
.portal-card:hover {
    background: rgba(255,255,255,0.12);
    transform: translateY(-8px) scale(1.03);
    box-shadow: 0 12px 25px rgba(0,0,0,.25);
}

.portal-card:hover img {
    transform: scale(1.08);
}

a {
    text-decoration: none !important;
}


/* BOTÓN REGRESAR */
.btn-back {
    background: rgba(255, 255, 255, .12);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, .2);
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
    background: rgba(255, 255, 255, .2);
    color: white;
    transform: translateY(-2px);
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
          Captura de calificaciones-Sistema No Escolarizado
        </h1>

        <div class="row justify-content-center g-4">

            <!-- SÁBADO -->
            <div class="col-md-4">
                <a href="{{ route('captura_bgneS') }}">
                    <div class="portal-card">
                        <img src="{{ asset('img/SAB.png') }}" alt="Kardex Sábados">
                        <h5>Captura - Sábados</h5>
                    </div>
                </a>
            </div>

            <!-- DOMINGO -->
            <div class="col-md-4">
                <a href="{{ route('captura_bgneD') }}">
                    <div class="portal-card">
                        <img src="{{ asset('img/DOM.png') }}" alt="Kardex Domingos">
                        <h5>Captura - Domingos</h5>
                    </div>
                </a>
            </div>

        </div>

    </div>

</div>

@endsection