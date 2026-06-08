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
    background: linear-gradient(135deg,
            #0F2E6D 0%,
            #1E6FA8 50%,
            #6BC7E8 100%);
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
    text-shadow: 0 2px 8px rgba(0, 0, 0, .2);
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

/* TARJETAS */
.portal-card {
    background: rgba(255, 255, 255, .08);
    backdrop-filter: blur(12px);

    border: 1px solid rgba(255, 255, 255, .2);
    border-radius: 20px;

    padding: 30px 20px;

    text-align: center;
    color: white;

    min-height: 260px;

    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;

    transition: all .3s ease;
    box-shadow: 0 5px 15px rgba(0, 0, 0, .15);
}

.portal-card svg {
    width: 70px;
    height: 70px;
    fill: #6BC7E8;
    margin-bottom: 20px;
    transition: .3s;
}

.portal-card h5 {
    font-weight: 700;
    margin-bottom: 10px;
}

.portal-card h6 {
    color: rgba(255, 255, 255, .8);
    font-size: .9rem;
    margin: 0;
}

/* HOVER */
.portal-card:hover {
    background: rgba(255, 255, 255, .12);
    transform: translateY(-8px) scale(1.03);
    box-shadow: 0 12px 25px rgba(0, 0, 0, .25);
}

.portal-card:hover svg {
    transform: scale(1.1);
}

/* LINKS */
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
            <i class="fa-solid fa-file-excel me-2"></i>
            BTI - ESCOLARIZADO
        </h1>

        <div class="row justify-content-center g-3">

            <div class="col-md-4">
                    <a href="" style="text-decoration:none;" target="_blank">
                        <div class="portal-card"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                <path
                                    d="M129.5 464L179.5 304L558.9 304L508.9 464L129.5 464zM320.2 512L509 512C530 512 548.6 498.4 554.8 478.3L604.8 318.3C614.5 287.4 591.4 256 559 256L179.6 256C158.6 256 140 269.6 133.8 289.7L112.2 358.4L112.2 160C112.2 151.2 119.4 144 128.2 144L266.9 144C270.4 144 273.7 145.1 276.5 147.2L314.9 176C328.7 186.4 345.6 192 362.9 192L480.2 192C489 192 496.2 199.2 496.2 208L544.2 208C544.2 172.7 515.5 144 480.2 144L362.9 144C356 144 349.2 141.8 343.7 137.6L305.3 108.8C294.2 100.5 280.8 96 266.9 96L128.2 96C92.9 96 64.2 124.7 64.2 160L64.2 448C64.2 483.3 92.9 512 128.2 512L320.2 512z" />
                            </svg>
                            <h5>
                                <i class="fa-solid fa-graduation-cap me-2"></i>
                                1ER SEMESTRE
                            </h5>
                            <h6>Referencia: </h6>
                        </div>
                </a>
            </div>

            <div class="col-md-4">
                    <a href="https://docs.google.com/spreadsheets/d/1VpAYUo4Cyi37RHMG0-_eSJl2IN_7mqzgbyjB_rdI9HY/edit?gid=616267140#gid=616267140"
                        style="text-decoration:none;" target="_blank">
                        <div class="portal-card"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                <path
                                    d="M129.5 464L179.5 304L558.9 304L508.9 464L129.5 464zM320.2 512L509 512C530 512 548.6 498.4 554.8 478.3L604.8 318.3C614.5 287.4 591.4 256 559 256L179.6 256C158.6 256 140 269.6 133.8 289.7L112.2 358.4L112.2 160C112.2 151.2 119.4 144 128.2 144L266.9 144C270.4 144 273.7 145.1 276.5 147.2L314.9 176C328.7 186.4 345.6 192 362.9 192L480.2 192C489 192 496.2 199.2 496.2 208L544.2 208C544.2 172.7 515.5 144 480.2 144L362.9 144C356 144 349.2 141.8 343.7 137.6L305.3 108.8C294.2 100.5 280.8 96 266.9 96L128.2 96C92.9 96 64.2 124.7 64.2 160L64.2 448C64.2 483.3 92.9 512 128.2 512L320.2 512z" />
                            </svg>
                            <h5>2DO SEMESTRE</h5>
                            <h6>Referencia: Mora Herrera Victoria Kiran </h6>
                        </div>
                </a>
            </div>
            <div class="col-md-4">
                    <a href="" style="text-decoration:none;" target="_blank">
                        <div class="portal-card"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                <path
                                    d="M129.5 464L179.5 304L558.9 304L508.9 464L129.5 464zM320.2 512L509 512C530 512 548.6 498.4 554.8 478.3L604.8 318.3C614.5 287.4 591.4 256 559 256L179.6 256C158.6 256 140 269.6 133.8 289.7L112.2 358.4L112.2 160C112.2 151.2 119.4 144 128.2 144L266.9 144C270.4 144 273.7 145.1 276.5 147.2L314.9 176C328.7 186.4 345.6 192 362.9 192L480.2 192C489 192 496.2 199.2 496.2 208L544.2 208C544.2 172.7 515.5 144 480.2 144L362.9 144C356 144 349.2 141.8 343.7 137.6L305.3 108.8C294.2 100.5 280.8 96 266.9 96L128.2 96C92.9 96 64.2 124.7 64.2 160L64.2 448C64.2 483.3 92.9 512 128.2 512L320.2 512z" />
                            </svg>
                            <h5>3ER SEMESTRE</h5>
                            <h6>Referencia: </h6>
                        </div>
                    </a>
            </div>
            <div class="col-md-4">
                    <a href="https://docs.google.com/spreadsheets/d/1VpAYUo4Cyi37RHMG0-_eSJl2IN_7mqzgbyjB_rdI9HY/edit?gid=616267140#gid=616267140"
                        style="text-decoration:none;" target="_blank">
                        <div class="portal-card"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                <path
                                    d="M129.5 464L179.5 304L558.9 304L508.9 464L129.5 464zM320.2 512L509 512C530 512 548.6 498.4 554.8 478.3L604.8 318.3C614.5 287.4 591.4 256 559 256L179.6 256C158.6 256 140 269.6 133.8 289.7L112.2 358.4L112.2 160C112.2 151.2 119.4 144 128.2 144L266.9 144C270.4 144 273.7 145.1 276.5 147.2L314.9 176C328.7 186.4 345.6 192 362.9 192L480.2 192C489 192 496.2 199.2 496.2 208L544.2 208C544.2 172.7 515.5 144 480.2 144L362.9 144C356 144 349.2 141.8 343.7 137.6L305.3 108.8C294.2 100.5 280.8 96 266.9 96L128.2 96C92.9 96 64.2 124.7 64.2 160L64.2 448C64.2 483.3 92.9 512 128.2 512L320.2 512z" />
                            </svg>
                            <h5>4TO SEMESTRE</h5>
                            <h6>Referencia: Leal Hernandez Yael</h6>
                        </div>
                    </a>
            </div>
            <div class="col-md-4">
                    <a href="" style="text-decoration:none;" target="_blank">
                        <div class="portal-card"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                <path
                                    d="M129.5 464L179.5 304L558.9 304L508.9 464L129.5 464zM320.2 512L509 512C530 512 548.6 498.4 554.8 478.3L604.8 318.3C614.5 287.4 591.4 256 559 256L179.6 256C158.6 256 140 269.6 133.8 289.7L112.2 358.4L112.2 160C112.2 151.2 119.4 144 128.2 144L266.9 144C270.4 144 273.7 145.1 276.5 147.2L314.9 176C328.7 186.4 345.6 192 362.9 192L480.2 192C489 192 496.2 199.2 496.2 208L544.2 208C544.2 172.7 515.5 144 480.2 144L362.9 144C356 144 349.2 141.8 343.7 137.6L305.3 108.8C294.2 100.5 280.8 96 266.9 96L128.2 96C92.9 96 64.2 124.7 64.2 160L64.2 448C64.2 483.3 92.9 512 128.2 512L320.2 512z" />
                            </svg>
                            <h5>5TO SEMESTRE</h5>
                            <h6>Referencia: </h6>
                        </div>
                    </a>
            </div>
            <div class="col-md-4">
                    <a href="https://docs.google.com/spreadsheets/d/1VpAYUo4Cyi37RHMG0-_eSJl2IN_7mqzgbyjB_rdI9HY/edit?gid=616267140#gid=616267140"
                        style="text-decoration:none;" target="_blank">
                        <div class="portal-card"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                <path
                                    d="M129.5 464L179.5 304L558.9 304L508.9 464L129.5 464zM320.2 512L509 512C530 512 548.6 498.4 554.8 478.3L604.8 318.3C614.5 287.4 591.4 256 559 256L179.6 256C158.6 256 140 269.6 133.8 289.7L112.2 358.4L112.2 160C112.2 151.2 119.4 144 128.2 144L266.9 144C270.4 144 273.7 145.1 276.5 147.2L314.9 176C328.7 186.4 345.6 192 362.9 192L480.2 192C489 192 496.2 199.2 496.2 208L544.2 208C544.2 172.7 515.5 144 480.2 144L362.9 144C356 144 349.2 141.8 343.7 137.6L305.3 108.8C294.2 100.5 280.8 96 266.9 96L128.2 96C92.9 96 64.2 124.7 64.2 160L64.2 448C64.2 483.3 92.9 512 128.2 512L320.2 512z" />
                            </svg>
                            <h5>6TO SEMESTRE</h5>
                            <h6>Referencia: Gomez Trujillo Zaratustra Netzahualcóyotl</h6>
                        </div>
                    </a>
            </div>





        </div>

    </div>

</div>

@endsection