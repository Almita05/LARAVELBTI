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
            <a href="{{ route('notificaciones') }}" class="accordion-button no-chevron d-flex align-items-center text-white" style="text-decoration: none;">
                <i class="fa-solid fa-bell me-2"></i>
                <span>Avisos y Pendientes</span>
                <span class="badge rounded-pill bg-danger ms-auto d-none" id="sidebar-notificaciones-badge" style="font-size: 0.72rem; padding: 0.35em 0.65em;">0</span>
            </a>
        </h2>
    </div>

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
                    -Listado de Docentes
                </a>

            </div>
            <div class="accordion-body">

                <a href="{{ route('asistencias_docentes') }}">
                    -Asistencia Docente
                </a>

            </div>
            <div class="accordion-body">

                <a href="javascript:void(0)" onclick="alert('Este módulo se encuentra en proceso de diseño.')" style="opacity: 0.65; cursor: not-allowed;">
                    -Captura Calificaciones <span class="badge bg-warning text-dark ms-1" style="font-size: 0.63rem;">Proceso</span>
                </a>

            </div>
            <div class="accordion-body">

                <a href="{{ route('horarios_docentes') }}">
                    -Horario Docente
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

                <a href="{{ route('grupos.captura_calificaciones') }}">
                    -Captura Calificaciones
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

    <div class="accordion-item menu-title">
        <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#menu-Generaciones" aria-expanded="false" aria-controls="menu-Generaciones">
                <i class="fa-solid fa-graduation-cap"></i>
                Generaciones
            </button>
        </h2>
        <div id="menu-Generaciones" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
            <div class="accordion-body">
                <a href="{{ route('generaciones') }}">
                    -Generaciones
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

<style>
.accordion-button.no-chevron::after {
    display: none !important;
}
.sidebar a.accordion-button.no-chevron {
    color: white !important;
}
.sidebar a.accordion-button.no-chevron:hover {
    background: rgba(255, 255, 255, 0.1) !important;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('/notificaciones/count')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('sidebar-notificaciones-badge');
            if (badge && data.total > 0) {
                badge.textContent = data.total;
                badge.classList.remove('d-none');
            }
        })
        .catch(err => console.error('Error fetching notification count:', err));
});
</script>