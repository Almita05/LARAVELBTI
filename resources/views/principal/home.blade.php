@extends('layouts.app')

@section('content')

<style>
.card-dashboard {
    border: none;
    border-radius: 15px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.card-dashboard:hover {
    transform: translateY(-10px) scale(1.03);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
}

.bg-gradient-primary { background: linear-gradient(45deg, #4e73df, #224abe); color:white; }
.bg-gradient-success { background: linear-gradient(45deg, #1cc88a, #13855c); color:white; }
.bg-gradient-warning { background: linear-gradient(45deg, #f6c23e, #dda20a); color:white; }
.bg-gradient-info { background: linear-gradient(45deg, #36b9cc, #258391); color:white; }
.bg-gradient-dark { background: linear-gradient(45deg, #5a5c69, #2e2f37); color:white; }
.bg-gradient-danger { background: linear-gradient(45deg, #e74a3b, #be2617); color:white; }
</style>

<div class="container">
    <h2 class="mb-4">¡Bienvenido(a) !</h2>

    @php
    $cards = [
        ['titulo'=>'Buscador de Alumnos','icon'=>'fa-user-graduate','color'=>'primary','ruta'=>'alumnos'],
        ['titulo'=>'Alta Docentes','icon'=>'fa-chalkboard-teacher','color'=>'success','ruta'=>'docentes'],
        ['titulo'=>'Planes de Estudios','icon'=>'fa-book','color'=>'warning','ruta'=>'planes'],
        ['titulo'=>'Equivalencias','icon'=>'fa-exchange-alt','color'=>'info','ruta'=>'equivalencias'],
        ['titulo'=>'Alta Grupos','icon'=>'fa-users','color'=>'dark','ruta'=>'grupos'],
        ['titulo'=>'Alta Materias','icon'=>'fa-file-alt','color'=>'danger','ruta'=>'materias'],
    ];
    @endphp

    <div class="row">
        @foreach($cards as $card)
            <div class="col-md-4 mb-4">

                <!-- 🔥 AQUÍ VA TU CÓDIGO -->
                <a href="{{ route($card['ruta']) }}" style="text-decoration:none;">
                    <div class="card card-dashboard bg-gradient-{{ $card['color'] }} text-center p-4">
                        <i class="fas {{ $card['icon'] }}"></i>
                        <h5 class="fw-bold">{{ $card['titulo'] }}</h5>
                        <p class="small">Haz clic para acceder</p>
                    </div>
                </a>

            </div>
        @endforeach
    </div>
</div>

@endsection