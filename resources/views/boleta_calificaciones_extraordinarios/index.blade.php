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
}

.title {
    text-align: center;
    font-weight: bold;
    margin-bottom: 50px;
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

    <div class="container">

        <h1 class="title">
            BGNE-EXTRAORDINARIOS
        </h1>

        <div class="row justify-content-center g-4">

            <div class="col-md-3">
                <a href="">
                        <a href="https://docs.google.com/spreadsheets/d/19-FiT5OlnWnfH-iXjTmkZK4OdV1KHCRPMbAWh5_Myio/edit?gid=39241542#gid=39241542" style="text-decoration:none;" target="_blank">
                            <div class="portal-card"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                    <path
                                        d="M129.5 464L179.5 304L558.9 304L508.9 464L129.5 464zM320.2 512L509 512C530 512 548.6 498.4 554.8 478.3L604.8 318.3C614.5 287.4 591.4 256 559 256L179.6 256C158.6 256 140 269.6 133.8 289.7L112.2 358.4L112.2 160C112.2 151.2 119.4 144 128.2 144L266.9 144C270.4 144 273.7 145.1 276.5 147.2L314.9 176C328.7 186.4 345.6 192 362.9 192L480.2 192C489 192 496.2 199.2 496.2 208L544.2 208C544.2 172.7 515.5 144 480.2 144L362.9 144C356 144 349.2 141.8 343.7 137.6L305.3 108.8C294.2 100.5 280.8 96 266.9 96L128.2 96C92.9 96 64.2 124.7 64.2 160L64.2 448C64.2 483.3 92.9 512 128.2 512L320.2 512z" />
                                </svg>
                                <h5>Imprimir actas de calificaciones de BGNE extraordinarias</h5>
                            </div>
                        </a>
                </a>
            </div>

        </div>

    </div>

</div>

@endsection