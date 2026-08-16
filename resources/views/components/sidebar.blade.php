@php
$rol = strtoupper(session('rol'));
$modulosValidos = session('modulos', 'horarios,pendientes,grupos') ?: 'horarios,pendientes,grupos';
if (is_string($modulosValidos)) {
    $modulosValidos = explode(',', $modulosValidos);
}
$modulosValidos = array_map('trim', $modulosValidos);
@endphp

<link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">


<div class="sidebar" id="sidebar">

    <div class="logo">
        <i class="fa-solid fa-school"></i>
        <span>Sistema Escolar</span>
    </div>

    <div class="usuario-sidebar">

        <div class="usuario-info">

            <i class="fa-solid fa-circle-user usuario-icono"></i>

            <div class="usuario-texto">
                <span class="usuario-nombre">
                    {{ session('nombre') }}
                </span>
            </div>

            <button type="button" id="toggleSidebar" class="toggle-sidebar">
                <i class="fa-solid fa-chevron-left"></i>
            </button>

        </div>

    </div>


    @if($rol=='ADMIN' || $rol=='PERSONAL' || in_array('inicio', $modulosValidos))
    <div class="accordion-item menu-title">

        <h2 class="accordion-header">

            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#menu-Inicio"
                aria-expanded="false" aria-controls="menu-Inicio">
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
    @endif
    @if($rol=='ADMIN' || $rol=='PERSONAL')
        @if(has_perm('notificaciones', 'ver'))
        <div class="accordion-item menu-title">
            <h2 class="accordion-header">
                <a href="{{ route('notificaciones') }}" class="accordion-button no-chevron d-flex align-items-center text-white" style="text-decoration: none;">
                    <div class="position-relative d-inline-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                        <i class="fa-solid fa-bell" style="font-size: 18px; margin: 0;"></i>
                        <span class="badge rounded-pill bg-danger position-absolute d-none" id="sidebar-notificaciones-badge" style="font-size: 0.58rem; padding: 0.25em 0.45em; top: -5px; right: -5px; line-height: 1;">0</span>
                    </div>
                    <span class="menu-text ms-2">Avisos y Pendientes</span>
                </a>
            </h2>
        </div>
        @endif

        @if(has_perm('alumnos_list', 'ver') || has_perm('alumnos_calificaciones', 'ver') || has_perm('alumnos_asistencias', 'ver') || has_perm('alumnos_reporte_asistencias', 'ver'))
        <div class="accordion-item menu-title">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#menu-Alumnos"
                    aria-expanded="false" aria-controls="menu-Alumnos">
                    <i class="fa-solid fa-users"></i>
                    Alumnos
                </button>
            </h2>
            <div id="menu-Alumnos" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
                @if(has_perm('alumnos_list', 'ver'))
                <div class="accordion-body">
                    <a href="{{ route('alumnos') }}">
                        -Listado Alumnos
                    </a>
                </div>
                @endif
                @if(has_perm('alumnos_calificaciones', 'ver'))
                <div class="accordion-body">
                    <a href="{{ route('boletas_bti') }}">
                        -Captura de calificaciones
                    </a>
                </div>
                @endif
                @if(has_perm('alumnos_asistencias', 'ver'))
                <div class="accordion-body">
                    <a href="{{ route('asistencias_alumnos') }}">
                        -Asistencia Alumnos
                    </a>
                </div>
                @endif
                @if(has_perm('alumnos_reporte_asistencias', 'ver'))
                <div class="accordion-body">
                    <a href="{{ route('reportes.asistencias') }}">
                        -Reporte de Asistencias
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endif

        @if(has_perm('docentes_list', 'ver') || has_perm('docentes_asistencias', 'ver') || has_perm('docentes_horarios', 'ver') || has_perm('docentes_permisos_captura', 'ver'))
        <div class="accordion-item menu-title">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#menu-Docentes"
                    aria-expanded="false" aria-controls="menu-Docentes">
                    <i class="fa-solid fa-person-chalkboard"></i>
                    Docentes
                </button>
            </h2>
            <div id="menu-Docentes" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
                @if(has_perm('docentes_list', 'ver'))
                <div class="accordion-body">
                    <a href="{{ route('docentes') }}">
                        -Listado de Docentes
                    </a>
                </div>
                @endif
                @if(has_perm('docentes_asistencias', 'ver'))
                <div class="accordion-body">
                    <a href="{{ route('asistencias_docentes') }}">
                        -Asistencia Docente
                    </a>
                </div>
                @endif
                @if(has_perm('docentes_list', 'ver') || has_perm('docentes_permisos_captura', 'ver'))
                
                @endif
                @if(has_perm('docentes_horarios', 'ver'))
                <div class="accordion-body">
                    <a href="{{ route('horarios_docentes') }}">
                        -Horario Docente
                    </a>
                </div>
                @endif
                @if(has_perm('docentes_permisos_captura', 'ver'))
                <div class="accordion-body">
                    <a href="{{ route('permisos_captura') }}">
                        -Permisos de Captura
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endif

        @if(has_perm('grupos_list', 'ver') || has_perm('grupos_calificaciones', 'ver') || has_perm('grupos_horarios', 'ver'))
        <div class="accordion-item menu-title">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#menu-Grupos"
                    aria-expanded="false" aria-controls="menu-Grupos">
                    <i class="fa-solid fa-user-group"></i>
                    <span>Grupos</span>
                </button>
            </h2>
            <div id="menu-Grupos" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
                <div class="accordion-body">
                    @if(has_perm('grupos_list', 'ver'))
                    <a href="{{ route('grupos') }}">
                        -Listado Grupos
                    </a>
                    @endif
                    @if(has_perm('grupos_calificaciones', 'ver'))
                    <a href="{{ route('grupos.captura_calificaciones') }}">
                        -Captura Calificaciones
                    </a>
                    @endif
                    @if(has_perm('grupos_horarios', 'ver'))
                    <a href="{{ route('horarios') }}">
                        -Armado Horarios
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if(has_perm('materias', 'ver'))
        <div class="accordion-item menu-title">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#menu-Materias"
                    aria-expanded="false" aria-controls="menu-Materias">
                    <i class="fa-solid fa-book-bookmark"></i>
                    Materias
                </button>
            </h2>
            <div id="menu-Materias" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
                <div class="accordion-body">
                    <a href="{{ route('materias') }}">
                        -Listado Materias
                    </a>
                </div>
            </div>
        </div>
        @endif
    @endif

    @if($rol=='DOCENTE')
        @if(in_array('horarios', $modulosValidos))
        <div class="accordion-item menu-title">
            <h2 class="accordion-header">
                <a href="{{ route('horarios_docentes') }}" class="accordion-button no-chevron d-flex align-items-center text-white" style="text-decoration: none;">
                    <i class="fa-solid fa-calendar-days me-2"></i>
                    <span>Mi Horario</span>
                </a>
            </h2>
        </div>
        @endif

        @if(in_array('pendientes', $modulosValidos))
        <div class="accordion-item menu-title">
            <h2 class="accordion-header">
                <a href="{{ route('docentes.pendientes') }}" class="accordion-button no-chevron d-flex align-items-center text-white" style="text-decoration: none;">
                    <i class="fa-solid fa-list-check me-2"></i>
                    <span>Pendientes</span>
                </a>
            </h2>
        </div>
        @endif

        @if(in_array('grupos', $modulosValidos))
        <div class="accordion-item menu-title">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                    data-bs-target="#menu-GruposDocente" aria-expanded="false" aria-controls="menu-GruposDocente">
                    <i class="fa-solid fa-user-group"></i>
                    <span>Mis Grupos</span>
                </button>
            </h2>
            <div id="menu-GruposDocente" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
                <div class="accordion-body">
                    <a href="{{ route('grupos') }}">
                        -Listado de Grupos
                    </a>
                    <a href="{{ route('grupos.captura_calificaciones') }}">
                        -Capturar Calificaciones
                    </a>
                    <a href="{{ route('asistencias_alumnos') }}">
                        -Asistencia
                    </a>
                </div>
            </div>
        </div>
        @endif
    @endif










    @if($rol=='ADMIN' || $rol=='PERSONAL')
        @if($rol=='ADMIN' || has_perm('formatos_boletas_bti', 'ver') || has_perm('formatos_kardex_bgne', 'ver') || has_perm('formatos_actas_extraordinarios', 'ver') || has_perm('formatos_listas_asistencia', 'ver') || has_perm('formatos_actas_ordinarios', 'ver'))
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
                    @if(has_perm('formatos_boletas_bti', 'ver'))
                    <a href="{{ route('boleta_calificaciones_bti') }}">
                        -Boletas BTI
                    </a>
                    @endif
                    @if(has_perm('formatos_kardex_bgne', 'ver'))
                    <a href="{{ route('kardex_no_escolarizado') }}">
                        -Kardex BGNE
                    </a>
                    @endif
                    @if(has_perm('formatos_actas_extraordinarios', 'ver'))
                    <a href="{{ route('boleta_calificaciones_extraordinarios') }}">
                        -Actas de calificaciones Extraordinarios
                    </a>
                    @endif
                    @if(has_perm('formatos_listas_asistencia', 'ver'))
                    <a href="{{ route('listas_asistencias') }}">
                        -Listas de Asistencia
                    </a>
                    @endif
                    @if(has_perm('formatos_actas_ordinarios', 'ver'))
                    <a href="{{ route('actas_calificaciones') }}">
                        -Actas de Calificaciones O.
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @endif

    {{-- Menú Unificado: Otros --}}
    @if($rol=='ADMIN' || $rol=='DOCENTE' || has_perm('planes_bti', 'ver') || has_perm('planes_bgne', 'ver') || has_perm('generaciones', 'ver') || has_perm('busqueda_grupos', 'ver'))
    <div class="accordion-item menu-title">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#menu-Otros" aria-expanded="false" aria-controls="menu-Otros">
                <i class="fa-solid fa-folder-plus"></i>
                Otros
            </button>
        </h2>
        <div id="menu-Otros" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
            <div class="accordion-body">

                {{-- Sub-Módulo: Planes de Estudio --}}
                @if($rol=='ADMIN' || $rol=='DOCENTE' || has_perm('planes_bti', 'ver') || has_perm('planes_bgne', 'ver'))
                <div class="mb-3 border-bottom pb-2">
                    <span class="text-white opacity-75 fw-bold" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                        <i class="fa-regular fa-newspaper me-1"></i> Planes de Estudio
                    </span>
                    <div class="ms-2 mt-1 d-flex flex-column gap-1">
                        @if($rol=='ADMIN' || $rol=='DOCENTE' || has_perm('planes_bti', 'ver'))
                        <a href="{{ route('planesBTI') }}" class="text-white opacity-75 text-decoration-none" style="font-size: 0.8rem;">
                            - Planes BTI
                        </a>
                        @endif
                        @if($rol=='ADMIN' || $rol=='DOCENTE' || has_perm('planes_bgne', 'ver'))
                        <a href="{{ route('planesBGNE') }}" class="text-white opacity-75 text-decoration-none" style="font-size: 0.8rem;">
                            - Planes BGNE
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Sub-Módulo: Generaciones --}}
                @if($rol=='ADMIN' || has_perm('generaciones', 'ver'))
                <a href="{{ route('generaciones') }}" class="d-block py-2 text-white text-decoration-none" style="font-size: 0.85rem;">
                    <i class="fa-solid fa-graduation-cap me-1"></i> Generaciones
                </a>
                @endif

                {{-- Sub-Módulo: Búsqueda de Grupo --}}
                @if($rol=='ADMIN' || has_perm('busqueda_grupos', 'ver'))
                <a href="{{ route('busqueda_grupos') }}" class="d-block py-2 text-white text-decoration-none" style="font-size: 0.85rem;">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Búsqueda de Grupo
                </a>
                @endif

                {{-- Sub-Módulo: Personal (Solo Admin) --}}
                @if($rol=='ADMIN')
                <a href="{{ route('personal') }}" class="d-block py-2 text-white text-decoration-none" style="font-size: 0.85rem;">
                    <i class="fa-solid fa-user-gear me-1"></i> Personal
                </a>
                @endif

            </div>
        </div>
    </div>
    @endif
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
// Restaurar estado de la barra lateral de forma inmediata para evitar parpadeos (FOUC)
(function() {
    const sidebarState = localStorage.getItem('sidebar-state');
    if (sidebarState === 'collapsed') {
        const sidebar = document.getElementById('sidebar');
        if (sidebar) {
            sidebar.classList.add('sidebar-hidden');
            document.body.classList.add('sidebar-hidden');
            const toggleBtn = document.getElementById('toggleSidebar');
            if (toggleBtn) {
                const icon = toggleBtn.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-chevron-left');
                    icon.classList.add('fa-chevron-right');
                }
            }
        }
    }
})();

document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleButton = document.getElementById('toggleSidebar');

    function setSidebarState(state) {
        localStorage.setItem('sidebar-state', state);
    }

    function expandSidebar() {
        if (sidebar && sidebar.classList.contains('sidebar-hidden')) {
            sidebar.classList.remove('sidebar-hidden');
            document.body.classList.remove('sidebar-hidden');
            if (toggleButton) {
                const icon = toggleButton.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-chevron-right');
                    icon.classList.add('fa-chevron-left');
                }
            }
            setSidebarState('expanded');
        }
    }

    function collapseSidebar() {
        if (sidebar && !sidebar.classList.contains('sidebar-hidden')) {
            sidebar.classList.add('sidebar-hidden');
            document.body.classList.add('sidebar-hidden');
            if (toggleButton) {
                const icon = toggleButton.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-chevron-left');
                    icon.classList.add('fa-chevron-right');
                }
            }
            setSidebarState('collapsed');
        }
    }

    // Toggle button click listener
    if (toggleButton && sidebar) {
        toggleButton.addEventListener('click', function() {
            if (sidebar.classList.contains('sidebar-hidden')) {
                expandSidebar();
            } else {
                collapseSidebar();
            }
        });
    }

    // Expandir sidebar al hacer clic en cualquier botón de menú (acordeón) estando colapsado
    if (sidebar) {
        const menuButtons = sidebar.querySelectorAll('.accordion-button');
        menuButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (sidebar.classList.contains('sidebar-hidden')) {
                    expandSidebar();
                }
            });
        });
    }

    // Obtener cantidad de notificaciones
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