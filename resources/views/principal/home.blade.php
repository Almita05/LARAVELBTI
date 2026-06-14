@extends('layouts.app')

@section('content')

<style>
    
html, body {
    margin: 0;
    padding: 0;
    height: 100%;
}



.hero {
    background: transparent;
    padding: 60px 20px;
    text-align: center;
    color: white;
}
.cards-container {
    margin-top: 20px;
}

.portal-card {
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 18px;
    padding: 30px 20px;
    text-align: center;
    color: white;
    min-height: 180px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    transition: all .3s ease;
    box-shadow: 0 5px 15px rgba(0,0,0,.15);
}

.portal-card i {
    font-size: 45px;
    color: #6BC7E8;
    margin-bottom: 15px;
    transition: .3s;
}

.portal-card h5 {
    font-weight: 600;
    margin-top: 10px;
}

/* HOVER */
.portal-card:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-8px) scale(1.03);
    box-shadow: 0 10px 25px rgba(0,0,0,.2);
}
.footer {
    margin-top: 50px;
    padding: 20px;
    text-align: center;
    color: white;
    background: rgba(0,0,0,0.15);
    backdrop-filter: blur(10px);
    border-top: 1px solid rgba(255,255,255,0.2);
}

.footer .social-icons a {
    color: white;
    font-size: 1.5rem;
    margin: 0 12px;
    transition: all .3s ease;
}


</style>

<div class="hero">

    <div class="container cards-container">

        <div class="row justify-content-center g-4">

            <div class="col-md-3">
                <a href="{{ route('alumnos') }}" style="text-decoration:none;">
                    <div class="portal-card">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <h5>Buscador de alumnos</h5>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('docentes') }}" style="text-decoration:none;">
                    <div class="portal-card">
                        <i class="fa-solid fa-chalkboard-user"></i>
                        <h5>Docentes</h5>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('planesBTI') }}" style="text-decoration:none;">
                    <div class="portal-card">
                        <i class="fa-solid fa-book-open-reader"></i>
                        <h5>Planes de estudio BTI</h5>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('planesBGNE') }}" style="text-decoration:none;">
                    <div class="portal-card">
                        <i class="fa-solid fa-book-open-reader"></i>
                        <h5>Planes de estudio BGNE</h5>
                    </div>
                </a>
            </div>

        </div>

        <div class="row justify-content-center g-4 mt-2">

            <div class="col-md-3">
                <a href="{{ route('grupos') }}" style="text-decoration:none;">
                    <div class="portal-card">
                        <i class="fa-solid fa-users-line"></i>
                        <h5>Grupos</h5>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('equivalencias') }}" style="text-decoration:none;">
                    <div class="portal-card">
                        <i class="fa-solid fa-spinner"></i>
                        <h5>Equivalencias</h5>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('listas_asistencias') }}" style="text-decoration:none;">
                    <div class="portal-card">
                       <i class="fa-solid fa-user-clock"></i>
                        <h5>Imprimir listas de asistencias</h5>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('actas_calificaciones') }}" style="text-decoration:none;">
                    <div class="portal-card">
                        <i class="bi bi-journal-bookmark-fill"></i>
                        <h5>Imprimir acta de calificaciones</h5>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('boleta_calificaciones_bti') }}" style="text-decoration:none;">
                    <div class="portal-card">
                       <i class="bi bi-file-earmark-text"></i>
                        <h5>Boleta de calificaciones-BTI</h5>
                    </div>
                </a>
            </div>
             <div class="col-md-3">
                <a href="{{ route('kardex_no_escolarizado') }}" style="text-decoration:none;">
                    <div class="portal-card">
                        <i class="bi bi-file-earmark-text"></i>
                        <h5>Kardex-BGNE</h5>
                    </div>
                </a>
            </div>

        </div>

    </div>

</div>


@endsection