@extends('layouts.app')

@section('content')

<style>
.page-container {
    padding: 30px;
    min-height: 100vh;
    color: white;
}

.page-title {
    color: #fff;
    font-weight: 700;
    text-shadow: 0 2px 8px rgba(0,0,0,.2);
}

.portal-card {
    background: rgba(255, 255, 255, .08);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, .15);
    border-radius: 20px;
    color: #fff;
    transition: all 0.3s ease;
    min-height: 220px;
    padding: 30px 20px;

    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
}

.portal-card:hover {
    background: rgba(255, 255, 255, .12);
    transform: translateY(-6px);
    box-shadow: 0 12px 25px rgba(0,0,0,.2);
}

.portal-card i.main-icon {
    font-size: 55px;
    color: #6BC7E8;
    margin-bottom: 20px;
    transition: 0.3s;
}

.portal-card:hover i.main-icon {
    transform: scale(1.1);
}

.portal-card h5 {
    font-weight: 700;
    margin-bottom: 10px;
}

.portal-card h6 {
    color: rgba(255, 255, 255, .8);
    font-size: 0.9rem;
    margin-bottom: 5px;
}

.portal-card .ref-text {
    font-style: italic;
    color: rgba(255, 255, 255, .6);
    font-size: 0.85rem;
    margin-bottom: 15px;
}

a {
    text-decoration: none;
}

.btn-regresar {
    background: rgba(255, 255, 255, .12);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, .2);
    color: #fff;
    font-weight: 600;
    padding: .375rem .75rem;
    border-radius: .375rem;
    transition: .2s;
}

.btn-regresar:hover {
    background: rgba(255, 255, 255, .2);
    color: #fff;
}
</style>

<div class="page-container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('horarios') }}" class="btn btn-regresar">
            <i class="fa-solid fa-arrow-left me-2"></i>
            Regresar
        </a>

        <h3 class="page-title mb-0">
            <i class="fa-solid fa-calendar-days me-2"></i>
            Horarios - Domingos
        </h3>

        <div></div>
    </div>

    <div class="row justify-content-center g-4">

        <!-- BGNE271024 -->
        <div class="col-md-4">
            <div class="portal-card">
                <i class="fa-solid fa-clock main-icon"></i>
                <h5>BGNE271024</h5>
                <h6>Domingo 9:00 am a 3:00 pm</h6>
                <div class="ref-text">Referencia: Pablo Casiano Andrea Yolanda</div>
                <a href="#" class="btn btn-primary">
                    <i class="fa-solid fa-eye me-2"></i>Ver Horario
                </a>
            </div>
        </div>

        <!-- BGNE160325 -->
        <div class="col-md-4">
            <div class="portal-card">
                <i class="fa-solid fa-clock main-icon"></i>
                <h5>BGNE160325</h5>
                <h6>Domingo 9:00 am a 3:00 pm</h6>
                <div class="ref-text">Referencia: Amaro Rosas Magher</div>
                <a href="#" class="btn btn-primary">
                    <i class="fa-solid fa-eye me-2"></i>Ver Horario
                </a>
            </div>
        </div>

        <!-- BGNE200725D -->
        <div class="col-md-4">
            <div class="portal-card">
                <i class="fa-solid fa-clock main-icon"></i>
                <h5>BGNE200725D</h5>
                <h6>Domingo 9:00 am a 3:00 pm</h6>
                <div class="ref-text">Referencia: Garcia Garcilazo Daniel Alberto</div>
                <a href="#" class="btn btn-primary">
                    <i class="fa-solid fa-eye me-2"></i>Ver Horario
                </a>
            </div>
        </div>

        <!-- BGNE080226D -->
        <div class="col-md-4">
            <div class="portal-card">
                <i class="fa-solid fa-clock main-icon"></i>
                <h5>BGNE080226D</h5>
                <h6>Domingo 9:00 am a 3:00 pm</h6>
                <div class="ref-text">Referencia: Chavez Luna Alejandra</div>
                <a href="#" class="btn btn-primary">
                    <i class="fa-solid fa-eye me-2"></i>Ver Horario
                </a>
            </div>
        </div>

        <!-- BGNE190726D -->
        <div class="col-md-4">
            <div class="portal-card">
                <i class="fa-solid fa-clock main-icon"></i>
                <h5>BGNE190726D</h5>
                <h6>Domingo 09:00 am a 3:00 pm</h6>
                <div class="ref-text">Referencia: Perez Hernández Israel</div>
                <a href="#" class="btn btn-primary">
                    <i class="fa-solid fa-eye me-2"></i>Ver Horario
                </a>
            </div>
        </div>

    </div>

</div>

@endsection
