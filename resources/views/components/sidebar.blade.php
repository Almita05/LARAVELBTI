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

    <div class="sidebar-header">
        <button type="button" id="toggleSidebar" class="toggle-sidebar" title="Colapsar menú">
            <i class="ti ti-chevron-left"></i>
        </button>
    </div>

    <div class="sidebar-nav-container">


    @if($rol=='ADMIN' || $rol=='PERSONAL' || in_array('inicio', $modulosValidos))
    <div class="accordion-item menu-title">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menu-Inicio"
                aria-expanded="false" aria-controls="menu-Inicio">
                <i class="ti ti-home"></i>
                <span class="menu-text">Inicio</span>
            </button>
        </h2>
        <div id="menu-Inicio" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
            <div class="accordion-body">
                <div class="submenu-list">
                    <a href="{{ route('home') }}">
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if($rol=='ADMIN' || $rol=='PERSONAL')
    @if(has_perm('notificaciones', 'ver'))
    <div class="accordion-item menu-title">
        <h2 class="accordion-header">
            <a href="{{ route('notificaciones') }}"
                class="accordion-button no-chevron d-flex align-items-center text-white {{ request()->routeIs('notificaciones') ? 'active' : '' }}" style="text-decoration: none;">
                <div class="position-relative d-inline-flex align-items-center justify-content-center"
                    style="width: 22px; height: 22px; flex-shrink: 0;">
                    <i class="ti ti-bell" style="font-size: 18px; margin: 0;"></i>
                    <span class="badge rounded-pill bg-danger position-absolute d-none"
                        id="sidebar-notificaciones-badge"
                        style="font-size: 0.58rem; padding: 0.25em 0.45em; top: -5px; right: -5px; line-height: 1;">0</span>
                </div>
                <span class="menu-text">Avisos y Pendientes</span>
            </a>
        </h2>
    </div>
    @endif

    @if(has_perm('alumnos_list', 'ver') || has_perm('alumnos_asistencias',
    'ver') || has_perm('alumnos_reporte_asistencias', 'ver'))
    <div class="accordion-item menu-title">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menu-Alumnos"
                aria-expanded="false" aria-controls="menu-Alumnos">
                <i class="ti ti-users"></i>
                <span class="menu-text">Alumnos</span>
            </button>
        </h2>
        <div id="menu-Alumnos" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
            <div class="accordion-body">
                <div class="submenu-list">
                    @if(has_perm('alumnos_list', 'ver'))
                    <a href="{{ route('alumnos') }}">
                        Listado Alumnos
                    </a>
                    @endif
                    @if(has_perm('alumnos_list', 'ver') || (isset($rol) && $rol=='ADMIN'))
                    <a href="{{ route('alumnos.online') }}" class="{{ request()->routeIs('alumnos.online*') ? 'active' : '' }}">
                        <i class="ti ti-device-laptop me-1"></i> Alumnos Online
                    </a>
                    @endif
                    @if(has_perm('alumnos_asistencias', 'ver'))
                    <a href="{{ route('asistencias_alumnos') }}">
                        Asistencia Alumnos
                    </a>
                    @endif
                    @if(has_perm('alumnos_reporte_asistencias', 'ver'))
                    <a href="{{ route('reportes.asistencias') }}">
                        Reporte de Asistencias
                    </a>
                    @endif
                    @if(has_perm('alumnos_list', 'ver'))
                    <a href="{{ route('reportes_indisciplina.index_view') }}">
                        Reportes de Indisciplina
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(has_perm('docentes_list', 'ver') || has_perm('docentes_asistencias', 'ver') || has_perm('docentes_horarios',
    'ver') || has_perm('docentes_permisos_captura', 'ver'))
    <div class="accordion-item menu-title">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menu-Docentes"
                aria-expanded="false" aria-controls="menu-Docentes">
                <i class="ti ti-chalkboard"></i>
                <span class="menu-text">Docentes</span>
            </button>
        </h2>
        <div id="menu-Docentes" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
            <div class="accordion-body">
                <div class="submenu-list">
                    @if(has_perm('docentes_list', 'ver'))
                    <a href="{{ route('docentes') }}">
                        Listado de Docentes
                    </a>
                    @endif
                    @if(has_perm('docentes_asistencias', 'ver'))
                    <a href="{{ route('asistencias_docentes') }}">
                        Asistencia Docente
                    </a>
                    @endif
                    @if(has_perm('docentes_horarios', 'ver'))
                    <a href="{{ route('horarios_docentes') }}">
                        Horario Docente
                    </a>
                    @endif
                    @if(has_perm('docentes_permisos_captura', 'ver'))
                    <a href="{{ route('permisos_captura') }}">
                        Permisos de Captura
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(has_perm('grupos_list', 'ver') || has_perm('grupos_calificaciones', 'ver') || has_perm('grupos_horarios',
    'ver'))
    <div class="accordion-item menu-title">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menu-Grupos"
                aria-expanded="false" aria-controls="menu-Grupos">
                <i class="ti ti-users-group"></i>
                <span class="menu-text">Grupos</span>
            </button>
        </h2>
        <div id="menu-Grupos" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
            <div class="accordion-body">
                <div class="submenu-list">
                    @if(has_perm('grupos_list', 'ver'))
                    <a href="{{ route('grupos') }}">
                        Listado Grupos
                    </a>
                    @endif
                    @if(has_perm('grupos_calificaciones', 'ver'))
                    <a href="{{ route('grupos.captura_calificaciones') }}">
                        Captura Calificaciones
                    </a>
                    @endif
                    @if(has_perm('grupos_horarios', 'ver'))
                    <a href="{{ route('horarios') }}">
                        Armado Horarios
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(has_perm('materias', 'ver'))
    <div class="accordion-item menu-title">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menu-Materias"
                aria-expanded="false" aria-controls="menu-Materias">
                <i class="ti ti-books"></i>
                <span class="menu-text">Materias</span>
            </button>
        </h2>
        <div id="menu-Materias" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
            <div class="accordion-body">
                <div class="submenu-list">
                    <a href="{{ route('materias') }}">
                        Listado Materias
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif

    @if($rol=='DOCENTE')
    @if(in_array('horarios', $modulosValidos))
    <div class="accordion-item menu-title">
        <h2 class="accordion-header">
            <a href="{{ route('horarios_docentes') }}"
                class="accordion-button no-chevron d-flex align-items-center text-white {{ request()->routeIs('horarios_docentes') ? 'active' : '' }}" style="text-decoration: none;">
                <i class="ti ti-calendar-event me-2"></i>
                <span class="menu-text">Mi Horario</span>
            </a>
        </h2>
    </div>
    @endif

    @if(in_array('pendientes', $modulosValidos))
    <div class="accordion-item menu-title">
        <h2 class="accordion-header">
            <a href="{{ route('docentes.pendientes') }}"
                class="accordion-button no-chevron d-flex align-items-center text-white {{ request()->routeIs('docentes.pendientes') ? 'active' : '' }}" style="text-decoration: none;">
                <i class="ti ti-checklist me-2"></i>
                <span class="menu-text">Pendientes</span>
            </a>
        </h2>
    </div>
    @endif

    @if(in_array('grupos', $modulosValidos))
    <div class="accordion-item menu-title">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#menu-GruposDocente" aria-expanded="false" aria-controls="menu-GruposDocente">
                <i class="ti ti-users-group"></i>
                <span class="menu-text">Mis Grupos</span>
            </button>
        </h2>
        <div id="menu-GruposDocente" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
            <div class="accordion-body">
                <div class="submenu-list">
                    <a href="{{ route('grupos') }}">
                        Listado de Grupos
                    </a>
                    <a href="{{ route('grupos.captura_calificaciones') }}">
                        Capturar Calificaciones
                    </a>
                    <a href="{{ route('asistencias_alumnos') }}">
                        Asistencia
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif

    @if($rol=='ADMIN' || $rol=='PERSONAL')
    {{-- Nuevo Módulo: Imprimir --}}
    <div class="accordion-item menu-title">
        <h2 class="accordion-header">
            <a href="{{ route('reportes.imprimir') }}"
                class="accordion-button no-chevron d-flex align-items-center text-white {{ request()->routeIs('reportes.imprimir') ? 'active' : '' }}" style="text-decoration: none;">
                <i class="ti ti-printer"></i>
                <span class="menu-text ms-2">Imprimir</span>
            </a>
        </h2>
    </div>

    @if($rol=='ADMIN' || has_perm('formatos_boletas_bti', 'ver') || has_perm('formatos_kardex_bgne', 'ver') ||
    has_perm('formatos_actas_extraordinarios', 'ver') || has_perm('formatos_listas_asistencia', 'ver') ||
    has_perm('formatos_actas_ordinarios', 'ver'))
    <div class="accordion-item menu-title">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#menu-ImprimirFormatos" aria-expanded="false" aria-controls="menu-ImprimirFormatos">
                <i class="ti ti-files"></i>
                <span class="menu-text">Imprimir Formatos</span>
            </button>
        </h2>
        <div id="menu-ImprimirFormatos" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
            <div class="accordion-body">
                <div class="submenu-list">
                    @if(has_perm('formatos_boletas_bti', 'ver'))
                    <a href="{{ route('boleta_calificaciones_bti') }}">
                        Boletas BTI
                    </a>
                    @endif
                    @if(has_perm('formatos_kardex_bgne', 'ver'))
                    <a href="{{ route('kardex_no_escolarizado') }}">
                        Kardex BGNE
                    </a>
                    @endif
                    @if(has_perm('formatos_actas_extraordinarios', 'ver'))
                    <a href="{{ route('boleta_calificaciones_extraordinarios') }}">
                        Actas de calificaciones Extraordinarios
                    </a>
                    @endif
                    @if(has_perm('formatos_listas_asistencia', 'ver'))
                    <a href="{{ route('listas_asistencias') }}">
                        Listas de Asistencia
                    </a>
                    @endif
                    @if(has_perm('formatos_actas_ordinarios', 'ver'))
                    <a href="{{ route('actas_calificaciones') }}">
                        Actas de Calificaciones O.
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif

    {{-- Menú Unificado: Otros --}}
    @if($rol=='ADMIN' || has_perm('planes_bti', 'ver') || has_perm('planes_bgne', 'ver') ||
    has_perm('generaciones', 'ver') || has_perm('busqueda_grupos', 'ver') || has_perm('alumnos_calificaciones', 'ver'))
    <div class="accordion-item menu-title">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#menu-Otros" aria-expanded="false" aria-controls="menu-Otros">
                <i class="ti ti-folder-plus"></i>
                <span class="menu-text">Otros</span>
            </button>
        </h2>
        <div id="menu-Otros" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
            <div class="accordion-body">
                <div class="submenu-list">
                    {{-- Sub-Módulo: Planes de Estudio --}}
                    @if($rol=='ADMIN' || has_perm('planes_bti', 'ver') || has_perm('planes_bgne', 'ver'))
                    <div class="mb-1">
                        <span class="text-white opacity-75 fw-semibold px-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                            <i class="ti ti-books me-1"></i> Planes de Estudio
                        </span>
                        <div class="d-flex flex-column gap-1 mt-1 ps-2">
                            @if($rol=='ADMIN' || has_perm('planes_bti', 'ver'))
                            <a href="{{ route('planesBTI') }}">
                                Planes BTI
                            </a>
                            @endif
                            @if($rol=='ADMIN' || has_perm('planes_bgne', 'ver'))
                            <a href="{{ route('planesBGNE') }}">
                                Planes BGNE
                            </a>
                            @endif
                        </div>
                    </div>
                    <div style="border-top: 1px solid rgba(255, 255, 255, 0.12); margin: 6px 8px;"></div>
                    @endif

                    {{-- Sub-Módulo: Generaciones --}}
                    @if($rol=='ADMIN' || has_perm('generaciones', 'ver'))
                    <a href="{{ route('generaciones') }}">
                        Generaciones
                    </a>
                    @endif

                    {{-- Sub-Módulo: Búsqueda de Grupo --}}
                    @if($rol=='ADMIN' || has_perm('busqueda_grupos', 'ver'))
                    <a href="{{ route('busqueda_grupos') }}">
                        Búsqueda de Grupo
                    </a>
                    @endif

                    {{-- Sub-Módulo: Captura de calificaciones --}}
                    @if(has_perm('alumnos_calificaciones', 'ver'))
                    <a href="{{ route('boletas_bti') }}">
                        Captura de calificaciones
                    </a>
                    @endif

                    {{-- Sub-Módulo: Personal (Solo Admin) --}}
                    @if($rol=='ADMIN')
                    <a href="{{ route('personal') }}">
                        Personal
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

   <div class="accordion-item menu-title">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#menu-EstadoCuenta" aria-expanded="false" aria-controls="menu-EstadoCuenta">
                <i class="ti ti-receipt"></i>
                <span class="menu-text">Estados de cuenta</span>
            </button>
        </h2>
        <div id="menu-EstadoCuenta" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
            <div class="accordion-body">
                <div class="submenu-list">
                    @if($rol=='ADMIN' || has_perm('planes_bti', 'ver') || has_perm('planes_bgne', 'ver'))
                    @if($rol=='ADMIN' || has_perm('planes_bti', 'ver'))
                    <a href="{{ route('analizar-estado-cuenta') }}">
                        Verificar estados de cuenta
                    </a>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
    </div><!-- /.sidebar-nav-container -->
</div><!-- /#sidebar -->

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
                    icon.classList.remove('ti-chevron-left');
                    icon.classList.add('ti-chevron-right');
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
                    icon.classList.remove('ti-chevron-right');
                    icon.classList.add('ti-chevron-left');
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
                    icon.classList.remove('ti-chevron-left');
                    icon.classList.add('ti-chevron-right');
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

    // Expandir sidebar al hacer clic en cualquier botón de menú (acordeón) o cabecera estando colapsado
    if (sidebar) {
        const topHeader = sidebar.querySelector('.sidebar-header');
        if (topHeader) {
            topHeader.addEventListener('click', function(e) {
                if (e.target.closest('#toggleSidebar')) return;
                if (sidebar.classList.contains('sidebar-hidden')) {
                    expandSidebar();
                }
            });
        }

        const menuButtons = sidebar.querySelectorAll('.accordion-button');
        menuButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (sidebar.classList.contains('sidebar-hidden')) {
                    expandSidebar();
                }
            });
        });
    }

    // Función global para actualizar el badge del sidebar en tiempo real
    window.actualizarBadgeSidebarNotificaciones = function(total) {
        const badge = document.getElementById('sidebar-notificaciones-badge');
        if (!badge) return;
        const count = parseInt(total, 10) || 0;
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.classList.remove('d-none');
        } else {
            badge.textContent = '0';
            badge.classList.add('d-none');
        }
    };

    // Consultar cantidad de notificaciones con bypass de caché
    window.recargarConteoNotificacionesSidebar = function() {
        fetch('/notificaciones/count?_t=' + Date.now(), {
            headers: {
                'Cache-Control': 'no-cache, no-store, must-revalidate',
                'Pragma': 'no-cache'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Status ' + response.status);
            return response.json();
        })
        .then(data => {
            if (data && typeof data.total !== 'undefined') {
                window.actualizarBadgeSidebarNotificaciones(data.total);
            }
        })
        .catch(err => console.debug('Error fetching notification count:', err));
    };

    window.recargarConteoNotificacionesSidebar();
});
</script>