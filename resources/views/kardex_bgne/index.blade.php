@extends('layouts.app')

@section('content')

<style>
html,
body {
    margin: 0;
    padding: 0;
    min-height: 100%;
}

/* FONDO */
body {
    background: linear-gradient(
        135deg,
        #0F2E6D 0%,
        #1E6FA8 50%,
        #6BC7E8 100%
    );
}

/* HERO */
.hero {
    min-height: 100vh;
    position: relative;
}

/* CONTENEDOR */
.container {
    min-height: 100vh;
    padding-top: 90px;
    padding-bottom: 40px;
}
/* TÍTULO */
.title {
    text-align: center;
    color: white;
    font-weight: 700;
    margin-bottom: 50px;
    text-shadow: 0 3px 10px rgba(0,0,0,.2);
}

/* BOTÓN REGRESAR */
.btn-back {
    position: fixed;
    top: 85px;
    left: 20px;
    z-index: 99999;

    background: rgba(255,255,255,.95);
    color: #0F2E6D;
    padding: 12px 22px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    box-shadow: 0 5px 15px rgba(0,0,0,.15);
    transition: all .3s ease;
}

.btn-back:hover {
    transform: translateY(-3px);
    background: white;
    color: #0F2E6D;
    box-shadow: 0 8px 20px rgba(0,0,0,.2);
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

/* QUITAR SUBRAYADOS */
a {
    text-decoration: none !important;
}
</style>
<div class="hero">

    <a href="{{ url()->previous() }}" class="btn-back">
    <i class="fa-solid fa-arrow-left me-2"></i>Regresar
</a>

    <div class="container">

        <h1 class="title">
            Kardex Sistema No Escolarizado
        </h1>

        <div class="row justify-content-center g-4">

            <!-- SÁBADO -->
            <div class="col-md-4">
                <a href="{{ route('kardex_bgneS') }}">
                    <div class="portal-card">
                        <img src="{{ asset('img/SAB.png') }}" alt="Kardex Sábados">
                        <h5>Kardex - Sábados</h5>
                    </div>
                </a>
            </div>

            <!-- DOMINGO -->
            <div class="col-md-4">
                <a href="{{ route('kardex_bgneD') }}">
                    <div class="portal-card">
                        <img src="{{ asset('img/DOM.png') }}" alt="Kardex Domingos">
                        <h5>Kardex - Domingos</h5>
                    </div>
                </a>
            </div>

        </div>

    </div>

</div>

@endsection