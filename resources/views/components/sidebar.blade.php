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


        <div class="accordion-item menu-title">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#menu-Alumnos" aria-expanded="false" aria-controls="flush-collapseOne">
                    Alumno
                </button>
            </h2>
            <div id="menu-Alumnos" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <a href="{{ route('alumnos') }}">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Alumnos
                </a>
            </div>
            <div id="menu-Alumnos" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <a href="{{ route('boletas_bti') }}">
                    <i class="fa-solid fa-pen"></i>
                    Capturar Calificaciones
                </a>
            </div>
        </div>



        <div class="accordion-item menu-title">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#menu-Docentes" aria-expanded="false" aria-controls="flush-collapseOne">
                    Docentes
                </button>
            </h2>
            <div id="menu-Docentes" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <a href="{{ route('docentes') }}">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    Inicio Docentes
                </a>
            </div>
            <div id="menu-Docentes" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <a href="{{ route('asistencias_docentes') }}">
                    <i class="fa-solid fa-user-check"></i>
                    Asistencias Docentes
                </a>
            </div>
        </div>






        <div class="accordion-item menu-title">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#menu-Grupos" aria-expanded="false" aria-controls="flush-collapseOne">
                    Grupos
                </button>
            </h2>
            <div id="menu-Grupos" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <a href="{{ route('grupos') }}">
                    <i class="fa-solid fa-users"></i>
                    Inicio Grupos
                </a>
            </div>
            <div id="menu-Grupos" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <a href="{{ route('asistencias_docentes') }}">
                    <i class="fa-solid fa-user-check"></i>
                    Asistencias Docentes
                </a>
            </div>
            <div id="menu-Grupos" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <a href="{{ route('horarios') }}">
                    <i class="fa-solid fa-calendar-days"></i>
                    Horarios
                </a>
            </div>
        </div>




        <div class="accordion-item menu-title">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#menu-Materias" aria-expanded="false" aria-controls="flush-collapseOne">
                    Materias
                </button>
            </h2>
            <div id="menu-Materias" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <a href="{{ route('materias') }}">
                    <i class="fa-solid fa-book"></i>
                    Materias
                </a>
            </div>
        </div>


        <div class="accordion-item menu-title">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#menu-Tramites" aria-expanded="false" aria-controls="flush-collapseOne">
                    Trámites
                </button>
            </h2>
            <div id="menu-Tramites" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <a href="{{ route('equivalencias') }}">
                    <i class="fa-solid fa-repeat"></i>
                    Equivalencias
                </a>
            </div>
        </div>

        @endif





        <div class="accordion-item menu-title">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#menu-PlanesDeEstudio" aria-expanded="false" aria-controls="flush-collapseOne">
                    Planes de estudio
                </button>
            </h2>
            <div id="menu-PlanesDeEstudio" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <a href="{{ route('planesBTI') }}">
                    <i class="fa-solid fa-book-open"></i>
                    Planes BTI
                </a>
            </div>
            <div id="menu-PlanesDeEstudio" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <a href="{{ route('planesBGNE') }}">
                    <i class="fa-solid fa-book-open"></i>
                    Planes BGNE
                </a>
            </div>
        </div>






        @if($rol=='ADMIN')

        <div class="accordion-item menu-title">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#menu-ImprimirFormatos" aria-expanded="false" aria-controls="flush-collapseOne">
                    Imprimir formatos
                </button>
            </h2>
            <div id="menu-ImprimirFormatos" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <a href="{{ route('boleta_calificaciones_bti') }}">
                    <i class="fa-solid fa-file"></i>
                    Boletas BTI
                </a>
            </div>
            <div id="menu-ImprimirFormatos" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <a href="{{ route('kardex_no_escolarizado') }}">
                    <i class="fa-solid fa-file-lines"></i>
                    Kardex BGNE
                </a>
            </div>
            <div id="menu-ImprimirFormatos" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">

                <a href="{{ route('boleta_calificaciones_extraordinarios') }}">
                    <i class="fa-solid fa-file-circle-check"></i>
                    Actas C. Extraordinarios
                </a>
            </div>
            <div id="menu-ImprimirFormatos" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">

                <a href="{{ route('listas_asistencias') }}">
                    <i class="fa-solid fa-list-check"></i>
                    Listas de Asistencia
                </a>
            </div>
            <div id="menu-ImprimirFormatos" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">


                <a href="{{ route('actas_calificaciones') }}">
                    <i class="fa-solid fa-book-bookmark"></i>
                    Actas de Calificaciones O.
                </a>
            </div>
        </div>

        @endif


           <div class="accordion-item menu-title">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#menu-Otros" aria-expanded="false" aria-controls="flush-collapseOne">
                    Otros
                </button>
            </h2>
            <div id="menu-Otros" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <a href="{{ route('planesBTI') }}">
                    <i class="fa-solid fa-book-open"></i>
                    Otros
                </a>
            </div>
        </div>

</div>