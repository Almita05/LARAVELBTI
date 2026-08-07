@php
$rol = strtoupper(session('rol'));
@endphp

 <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

<div class="sidebar">

    <div class="logo">
        <i class="fa-solid fa-school"></i>
        <span>Sistema Escolar</span>
    </div>

    <ul>

        <li>
            <a href="{{ route('home') }}">
                <i class="fa-solid fa-house"></i>
                Inicio
            </a>
        </li>

        @if($rol=='ADMIN')

        <div class="menu-title">Alumnos</div>

        <li>
            <a href="{{ route('alumnos') }}">
                <i class="fa-solid fa-magnifying-glass"></i>
                Alumnos
            </a>
        </li>
        <li>
            <a href="{{ route('boletas_bti') }}">
                <i class="fa-solid fa-pen"></i>
                Capturar Calificaciones
            </a>
        </li>
        <div class="menu-title">Docentes</div>
        <li>
            <a href="{{ route('docentes') }}">
                <i class="fa-solid fa-chalkboard-user"></i>
                Inicio Docentes
            </a>
        </li>
        <div class="menu-title">Grupos</div>
        <li>
            <a href="{{ route('grupos') }}">
                <i class="fa-solid fa-users"></i>
                Inicio Grupos
            </a>
        </li>
        <li>
            <a href="{{ route('horarios') }}">
                <i class="fa-solid fa-calendar-days"></i>
                Horarios
            </a>
        </li>

        <div class="menu-title">Materias</div>
        <li>
            <a href="{{ route('materias') }}">
                <i class="fa-solid fa-book"></i>
                Materias
            </a>
        </li>
        <div class="menu-title">Trámites</div>
        <li>
            <a href="{{ route('equivalencias') }}">
                <i class="fa-solid fa-repeat"></i>
                Equivalencias
            </a>
        </li>

        @endif

        <div class="menu-title">Planes de estudio</div>

        <li>
            <a href="{{ route('planesBTI') }}">
                <i class="fa-solid fa-book-open"></i>
                Planes BTI
            </a>
        </li>

        <li>
            <a href="{{ route('planesBGNE') }}">
                <i class="fa-solid fa-book-open"></i>
                Planes BGNE
            </a>
        </li>



        @if($rol=='ADMIN')

        <div class="menu-title">Imprimir formatos</div>

        <li>
            <a href="{{ route('boleta_calificaciones_bti') }}">
                <i class="fa-solid fa-file"></i>
                Boletas BTI
            </a>
        </li>

        <li>
            <a href="{{ route('kardex_no_escolarizado') }}">
                <i class="fa-solid fa-file-lines"></i>
                Kardex BGNE
            </a>
        </li>

        <li>
            <a href="{{ route('boleta_calificaciones_extraordinarios') }}">
                <i class="fa-solid fa-file-circle-check"></i>
                Actas C. Extraordinarios
            </a>
        </li>

        <li>
            <a href="{{ route('listas_asistencias') }}">
                <i class="fa-solid fa-list-check"></i>
                Listas de Asistencia
            </a>
        </li>

        <li>
            <a href="{{ route('actas_calificaciones') }}">
                <i class="fa-solid fa-book-bookmark"></i>
                Actas de Calificaciones O.
            </a>
        </li>

        @endif

        <div class="menu-title">Otros</div>


    </ul>

</div>