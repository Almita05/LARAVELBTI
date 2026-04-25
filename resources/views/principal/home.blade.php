@extends('layouts.app')

@section('content')

<style>
.hero {
    background: #1A338F;
    padding: 60px 20px;
    text-align: center;
    color: white;
    min-height: calc(100vh - 60px);
}

.cards-container {
    margin-top: -40px;
}

.portal-card {
    background: transparent;
    border: 2px solid #ffffff;
    border-radius: 15px;
    padding: 30px 20px;
    text-align: center;
    color: white;
    transition: all 0.3s ease;
}

.portal-card i {
    font-size: 40px;
    margin-bottom: 15px;
}

.portal-card h5 {
    font-weight: 600;
    margin-top: 10px;
}

/* HOVER */
.portal-card:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-8px) scale(1.03);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}
</style>

<!-- SECCIÓN AZUL -->
<div class="hero">
    <!--<h2 class="fw-bold mb-4">Bienvenido al Sistema</h2>-->

    <div class="container cards-container">
        <div class="row justify-content-center g-4">

            <div class="col-md-3">
                <a href="{{ route('alumnos') }}" style="text-decoration:none;">
                    <div class="portal-card">
                        <i class="bi bi-person-check-fill"></i>
                        <h5>Buscador de alumnos</h5>
                    </div>
                </a>
            </div>


            <div class="col-md-3">
                <a href="{{ route('docentes') }}" style="text-decoration:none;">
                    <div class="portal-card">
                        <i class="bi bi-person-vcard-fill"></i>
                        <h5>Docentes</h5>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('planesBTI') }}" style="text-decoration:none;">
                    <div class="portal-card">
                        <i class="bi bi-file-earmark-text"></i></i>
                        <h5>Planes de estudio BTI</h5>
                    </div>
                </a>
            </div>


            <div class="col-md-3">
                <a href="{{ route('planesBGNE') }}" style="text-decoration:none;">
                    <div class="portal-card">
                        <i class="bi bi-file-earmark-text"></i></i>
                        <h5>Planes de estudio BGNE</h5>
                    </div>
                </a>
            </div>
        </div>
        <br>
        <div class="row justify-content-center g-4">
            <div class="col-md-3">
                <a href="{{ route('equivalencias') }}" style="text-decoration:none;">
                    <div class="portal-card">
                        <i class="bi bi-journal-bookmark-fill"></i></i></i>
                        <h5>Grupos</h5>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('equivalencias') }}" style="text-decoration:none;">
                    <div class="portal-card">
                        <i class="bi bi-file-earmark-text"></i></i>
                        <h5>Equivalencias</h5>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('equivalencias') }}" style="text-decoration:none;">
                    <div class="portal-card">
                        <i class="bi bi-list-check"></i></i>
                        <h5>Listas de asistencias</h5>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('equivalencias') }}" style="text-decoration:none;">
                    <div class="portal-card">
                        <i class="bi bi-journal-bookmark-fill"></i></i></i>
                        <h5>Actas de calificaciones</h5>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection