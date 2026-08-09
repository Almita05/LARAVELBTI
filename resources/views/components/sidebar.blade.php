@php
$rol = strtoupper(session('rol'));
@endphp

<link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

<div class="sidebar">

    <div class="logo">
        <i class="fa-solid fa-school"></i>
        <span>Sistema Escolar</span>
    </div>


    <div class="accordion-item menu-title">

        <h2 class="accordion-header">

            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#menu-Inicio" aria-expanded="false" aria-controls="menu-Inicio">
                <i class="fa-solid fa-house"></i>
                Inicio
            </button>
        </h2>
        <div id="menu-Inicio" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">

            <div class="accordion-body">

                <a href="{{ route('home') }}">
                    -Dashboard
                </a>

            </div>

        </div>

    </div>
    @if($rol=='ADMIN')


    <div class="accordion-item menu-title">

        <h2 class="accordion-header">

            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#menu-Alumnos" aria-expanded="false" aria-controls="menu-Alumnos">
                <i class="fa-solid fa-users"></i>
                Alumnos
            </button>
        </h2>
        <div id="menu-Alumnos" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">

            <div class="accordion-body">

                <a href="{{ route('alumnos') }}">
                    -Alumnos
                </a>

            </div>
            <div class="accordion-body">

                <a href="{{ route('boletas_bti') }}">
                    -Captura de calificaciones
                </a>

            </div>

        </div>

    </div>



    <div class="accordion-item menu-title">

        <h2 class="accordion-header">

            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#menu-Docentes" aria-expanded="false" aria-controls="menu-Docentes">
                <i class="fa-solid fa-person-chalkboard"></i>
                Docentes
            </button>
        </h2>
        <div id="menu-Docentes" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">

            <div class="accordion-body">

                <a href="{{ route('docentes') }}">
                    -Docentes
                </a>

            </div>
            <div class="accordion-body">

                <a href="{{ route('asistencias_docentes') }}">
                    -Asistencia Docentes
                </a>

            </div>

        </div>

    </div>






    <div class="accordion-item menu-title">

        <h2 class="accordion-header">

            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#menu-Grupos" aria-expanded="false" aria-controls="menu-Grupos">

               <i class="fa-solid fa-user-group"></i>
                <span>Grupos</span>

            </button>

        </h2>


        <div id="menu-Grupos" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">

            <div class="accordion-body">

                <a href="{{ route('grupos') }}">
                    -Inicio Grupos
                </a>

                <a href="{{ route('asistencias_docentes') }}">
                    -Asistencias Docentes
                </a>

                <a href="{{ route('horarios') }}">
                    -Horarios
                </a>

            </div>

        </div>

    </div>



    <div class="accordion-item menu-title">

        <h2 class="accordion-header">

            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#menu-Materias" aria-expanded="false" aria-controls="menu-Materias">
                <i class="fa-solid fa-book-bookmark"></i>
                Materias
            </button>
        </h2>
        <div id="menu-Materias" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">

            <div class="accordion-body">

                <a href="{{ route('materias') }}">
                    -Materias
                </a>

            </div>

        </div>

    </div>


    <div class="accordion-item menu-title">

        <h2 class="accordion-header">

            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#menu-Tramites" aria-expanded="false" aria-controls="menu-Tramites">
                <i class="fa-solid fa-folder-open"></i>
                Trámites
            </button>
        </h2>
        <div id="menu-Tramites" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">

            <div class="accordion-body">

                <a href="{{ route('equivalencias') }}">
                    -Equivalencias
                </a>

            </div>

        </div>

    </div>

    @endif




    <div class="accordion-item menu-title">

        <h2 class="accordion-header">

            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#menu-PlanesDeEstudio" aria-expanded="false" aria-controls="menu-PlanesDeEstudio">
                <i class="fa-regular fa-newspaper"></i>
                Planes de Estudio
            </button>
        </h2>
        <div id="menu-PlanesDeEstudio" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">

            <div class="accordion-body">

                <a href="{{ route('planesBTI') }}">
                    -Planes BTI
                </a>
                <a href="{{ route('planesBGNE') }}">
                    -Planes BGNE
                </a>


            </div>

        </div>

    </div>





    @if($rol=='ADMIN')

<div class="accordion-item menu-title">

        <h2 class="accordion-header">

            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#menu-ImprimirFormatos" aria-expanded="false" aria-controls="menu-ImprimirFormatos">
                <i class="fa-solid fa-print"></i>
                Imprimir Formatos
            </button>
        </h2>
        <div id="menu-ImprimirFormatos" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">

            <div class="accordion-body">

                <a href="{{ route('boleta_calificaciones_bti') }}">
                    -Boletas BTI
                </a>
                <a href="{{ route('kardex_no_escolarizado') }}">
                    -Kardex BGNE
                </a>
                <a href="{{ route('boleta_calificaciones_extraordinarios') }}">
                    -Actas de calificaciones Extraordinarios
                </a>
                <a href="{{ route('listas_asistencias') }}">
                    -Listas de Asistencia
                </a>
                <a href="{{ route('actas_calificaciones') }}">
                    -Actas de Calificaciones O.
                </a>
            </div>
        </div>
    </div>


    @endif


    <div class="accordion-item menu-title">
        <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#menu-Otros" aria-expanded="false" aria-controls="flush-collapseOne">
                Otros
            </button>
        </h2>
        <div id="menu-Otros" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <a href="{{ route('planesBTI') }}">
                <i class="fa-solid fa-book-open"></i>
                -Otros
            </a>
        </div>
    </div>
    <div class="accordion-item menu-title">

    </div>
</div>