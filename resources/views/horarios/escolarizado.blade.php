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
            Horarios - Escolarizado
        </h3>

        <div></div>
    </div>

    <div class="row justify-content-center g-4">

        <!-- 1ER SEMESTRE -->
        <div class="col-md-4">
            <div class="portal-card">
                <i class="fa-solid fa-calendar-week main-icon"></i>
                <h5>1ER SEMESTRE</h5>
                <h6>Horario oficial para primer semestre</h6>
                <a href="#" class="btn btn-primary mt-2">
                    <i class="fa-solid fa-eye me-2"></i>Ver Horario
                </a>
            </div>
        </div>

        <!-- 2DO SEMESTRE -->
        <div class="col-md-4">
            <div class="portal-card">
                <i class="fa-solid fa-calendar-week main-icon"></i>
                <h5>2DO SEMESTRE</h5>
                <h6>Horario oficial para segundo semestre</h6>
                <a href="#" class="btn btn-primary mt-2">
                    <i class="fa-solid fa-eye me-2"></i>Ver Horario
                </a>
            </div>
        </div>

        <!-- 3ER SEMESTRE -->
        <div class="col-md-4">
            <div class="portal-card">
                <i class="fa-solid fa-calendar-week main-icon"></i>
                <h5>3ER SEMESTRE</h5>
                <h6>Horario oficial para tercer semestre</h6>
                <a href="#" class="btn btn-primary mt-2">
                    <i class="fa-solid fa-eye me-2"></i>Ver Horario
                </a>
            </div>
        </div>

        <!-- 4TO SEMESTRE -->
        <div class="col-md-4">
            <div class="portal-card">
                <i class="fa-solid fa-calendar-week main-icon"></i>
                <h5>4TO SEMESTRE</h5>
                <h6>Horario oficial para cuarto semestre</h6>
                <a href="#" class="btn btn-primary mt-2">
                    <i class="fa-solid fa-eye me-2"></i>Ver Horario
                </a>
            </div>
        </div>

        <!-- 5TO SEMESTRE -->
        <div class="col-md-4">
            <div class="portal-card">
                <i class="fa-solid fa-calendar-week main-icon"></i>
                <h5>5TO SEMESTRE</h5>
                <h6>Horario oficial para quinto semestre</h6>
                <a href="#" class="btn btn-primary mt-2">
                    <i class="fa-solid fa-eye me-2"></i>Ver Horario
                </a>
            </div>
        </div>

        <!-- 6TO SEMESTRE -->
        <div class="col-md-4">
            <div class="portal-card">
                <i class="fa-solid fa-calendar-week main-icon"></i>
                <h5>6TO SEMESTRE</h5>
                <h6>Horario oficial para sexto semestre</h6>
                <a href="#" class="btn btn-primary mt-2">
                    <i class="fa-solid fa-eye me-2"></i>Ver Horario
                </a>
            </div>
        </div>

    </div>

</div>

@endsection
