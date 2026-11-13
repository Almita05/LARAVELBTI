@php
$rol = strtoupper(session('rol'));
$modulosValidos = session('modulos', 'horarios,pendientes,grupos') ?: 'horarios,pendientes,grupos';
if (is_string($modulosValidos)) {
$modulosValidos = explode(',', $modulosValidos);
}
$modulosValidos = array_map('trim', $modulosValidos);
$is = fn (...$routes) => request()->routeIs(...$routes);
@endphp

<aside id="sidebar"
    class="tw:fixed tw:inset-y-0 tw:left-0 tw:z-[1045] tw:flex tw:w-[270px] tw:flex-col tw:bg-brand-700 tw:shadow-[4px_0_24px_-12px_rgb(15_23_42/0.45)] tw:transition-[width] tw:duration-300 tw:ease-out tw:sb-collapsed:w-[75px]">

    {{-- Identidad institucional --}}
    <div class="tw:flex tw:h-[60px] tw:shrink-0 tw:items-center tw:gap-3 tw:border-b tw:border-white/10 tw:px-4 tw:sb-collapsed:justify-center tw:sb-collapsed:px-0" data-sb-header>
        <a href="{{ route('home') }}" class="tw:flex tw:min-w-0 tw:items-center tw:gap-3 tw:no-underline">
            {{-- Logo sin caja: PNG transparente alineado con el texto --}}
            <span class="tw:inline-flex tw:size-10 tw:shrink-0 tw:items-center tw:justify-center tw:bg-transparent">
                <img src="{{ asset('img/logo.png') }}" alt="BTI" class="tw:size-full tw:bg-transparent tw:object-contain">
            </span>
            <span class="tw:text-[1.1rem] tw:leading-none tw:font-bold tw:tracking-[0.02em] tw:text-white tw:sb-collapsed:hidden">BTI</span>
        </a>
    </div>

    {{-- Navegación --}}
    <nav id="sidebarAccordion" class="tw:flex tw:flex-1 tw:flex-col tw:gap-1 tw:overflow-y-auto tw:overflow-x-hidden tw:px-3 tw:py-4 tw:[scrollbar-width:none]">

    @if($rol=='ADMIN' || $rol=='PERSONAL' || in_array('inicio', $modulosValidos))
        <x-sidebar.item :href="route('home')" icon="layout-dashboard" label="Dashboard" :active="$is('home')" />
    @endif

    @if($rol=='ADMIN' || $rol=='PERSONAL')
        @if(has_perm('notificaciones', 'ver'))
        <x-sidebar.item :href="route('notificaciones')" icon="bell" label="Avisos y Pendientes" :active="$is('notificaciones')">
            <x-slot:badge>
                <span id="sidebar-notificaciones-badge"
                    class="d-none tw:absolute tw:-top-1.5 tw:-right-2 tw:min-w-[18px] tw:rounded-none tw:bg-danger-500 tw:px-1 tw:text-center tw:text-[0.625rem] tw:font-bold tw:leading-[18px] tw:text-white tw:ring-2 tw:ring-brand-700">0</span>
            </x-slot:badge>
        </x-sidebar.item>
        @endif

        <p class="tw:mx-3 tw:mt-4 tw:mb-1 tw:text-[0.8125rem] tw:font-medium tw:text-white/60 tw:sb-collapsed:hidden">Académico</p>

        @if(has_perm('alumnos_list', 'ver') || has_perm('alumnos_asistencias', 'ver') || has_perm('alumnos_reporte_asistencias', 'ver'))
        <x-sidebar.group id="Alumnos" icon="graduation-cap" label="Alumnos"
            :active="$is('alumnos', 'alumnos.online*', 'asistencias_alumnos', 'reportes.asistencias', 'reportes_indisciplina.index_view')">
            @if(has_perm('alumnos_list', 'ver'))
            <x-sidebar.link :href="route('alumnos')" :active="$is('alumnos')">Listado de Alumnos</x-sidebar.link>
            @endif
            @if(has_perm('alumnos_list', 'ver') || (isset($rol) && $rol=='ADMIN'))
            <x-sidebar.link :href="route('alumnos.online')" :active="$is('alumnos.online*')">Alumnos Online</x-sidebar.link>
            @endif
            @if(has_perm('alumnos_asistencias', 'ver'))
            <x-sidebar.link :href="route('asistencias_alumnos')" :active="$is('asistencias_alumnos')">Asistencia de Alumnos</x-sidebar.link>
            @endif
            @if(has_perm('alumnos_reporte_asistencias', 'ver'))
            <x-sidebar.link :href="route('reportes.asistencias')" :active="$is('reportes.asistencias')">Reporte de Asistencias</x-sidebar.link>
            @endif
            @if(has_perm('alumnos_list', 'ver'))
            <x-sidebar.link :href="route('reportes_indisciplina.index_view')" :active="$is('reportes_indisciplina.index_view')">Reportes de Indisciplina</x-sidebar.link>
            @endif
        </x-sidebar.group>
        @endif

        @if(has_perm('docentes_list', 'ver') || has_perm('docentes_asistencias', 'ver') || has_perm('docentes_horarios', 'ver') || has_perm('docentes_permisos_captura', 'ver'))
        <x-sidebar.group id="Docentes" icon="presentation" label="Docentes"
            :active="$is('docentes', 'asistencias_docentes', 'horarios_docentes', 'permisos_captura')">
            @if(has_perm('docentes_list', 'ver'))
            <x-sidebar.link :href="route('docentes')" :active="$is('docentes')">Listado de Docentes</x-sidebar.link>
            @endif
            @if(has_perm('docentes_asistencias', 'ver'))
            <x-sidebar.link :href="route('asistencias_docentes')" :active="$is('asistencias_docentes')">Asistencia Docente</x-sidebar.link>
            @endif
            @if(has_perm('docentes_horarios', 'ver'))
            <x-sidebar.link :href="route('horarios_docentes')" :active="$is('horarios_docentes')">Horario Docente</x-sidebar.link>
            @endif
            @if(has_perm('docentes_permisos_captura', 'ver'))
            <x-sidebar.link :href="route('permisos_captura')" :active="$is('permisos_captura')">Permisos de Captura</x-sidebar.link>
            @endif
        </x-sidebar.group>
        @endif

        @if(has_perm('grupos_list', 'ver') || has_perm('grupos_calificaciones', 'ver') || has_perm('grupos_horarios', 'ver'))
        <x-sidebar.group id="Grupos" icon="users" label="Grupos"
            :active="$is('grupos', 'grupos.captura_calificaciones', 'horarios')">
            @if(has_perm('grupos_list', 'ver'))
            <x-sidebar.link :href="route('grupos')" :active="$is('grupos')">Listado de Grupos</x-sidebar.link>
            @endif
            @if(has_perm('grupos_calificaciones', 'ver'))
            <x-sidebar.link :href="route('grupos.captura_calificaciones')" :active="$is('grupos.captura_calificaciones')">Captura de Calificaciones</x-sidebar.link>
            @endif
            @if(has_perm('grupos_horarios', 'ver'))
            <x-sidebar.link :href="route('horarios')" :active="$is('horarios')">Armado de Horarios</x-sidebar.link>
            @endif
        </x-sidebar.group>
        @endif

        @if(has_perm('materias', 'ver'))
        <x-sidebar.item :href="route('materias')" icon="book-open" label="Materias" :active="$is('materias')" />
        @endif
    @endif

    @if($rol=='DOCENTE')
        <p class="tw:mx-3 tw:mt-2 tw:mb-1 tw:text-[0.8125rem] tw:font-medium tw:text-white/60 tw:sb-collapsed:hidden">Mi espacio</p>

        @if(in_array('horarios', $modulosValidos))
        <x-sidebar.item :href="route('horarios_docentes')" icon="calendar-clock" label="Mi Horario" :active="$is('horarios_docentes')" />
        @endif

        @if(in_array('pendientes', $modulosValidos))
        <x-sidebar.item :href="route('docentes.pendientes')" icon="list-checks" label="Pendientes" :active="$is('docentes.pendientes')" />
        @endif

        @if(in_array('grupos', $modulosValidos))
        <x-sidebar.group id="GruposDocente" icon="users" label="Mis Grupos"
            :active="$is('grupos', 'grupos.captura_calificaciones', 'asistencias_alumnos')">
            <x-sidebar.link :href="route('grupos')" :active="$is('grupos')">Listado de Grupos</x-sidebar.link>
            <x-sidebar.link :href="route('grupos.captura_calificaciones')" :active="$is('grupos.captura_calificaciones')">Capturar Calificaciones</x-sidebar.link>
            <x-sidebar.link :href="route('asistencias_alumnos')" :active="$is('asistencias_alumnos')">Asistencia</x-sidebar.link>
        </x-sidebar.group>
        @endif
    @endif

    @if($rol=='ADMIN' || $rol=='PERSONAL')
        <p class="tw:mx-3 tw:mt-4 tw:mb-1 tw:text-[0.8125rem] tw:font-medium tw:text-white/60 tw:sb-collapsed:hidden">Documentos</p>

        @if($rol=='ADMIN' || has_perm('formatos_boletas_bti', 'ver') || has_perm('formatos_kardex_bgne', 'ver') ||
            has_perm('formatos_actas_extraordinarios', 'ver') || has_perm('formatos_listas_asistencia', 'ver') || has_perm('formatos_actas_ordinarios', 'ver'))
        <x-sidebar.item :href="route('reportes.imprimir')" icon="printer" label="Imprimir" :active="$is('reportes.imprimir')" />
        @endif

        @if($rol=='ADMIN' || has_perm('formatos_boletas_bti', 'ver') || has_perm('formatos_kardex_bgne', 'ver') ||
        has_perm('formatos_actas_extraordinarios', 'ver') || has_perm('formatos_listas_asistencia', 'ver') ||
        has_perm('formatos_actas_ordinarios', 'ver'))
        <x-sidebar.group id="ImprimirFormatos" icon="files" label="Imprimir Formatos"
            :active="$is('boleta_calificaciones_bti', 'kardex_no_escolarizado', 'boleta_calificaciones_extraordinarios', 'listas_asistencias', 'actas_calificaciones')">
            @if(has_perm('formatos_boletas_bti', 'ver'))
            <x-sidebar.link :href="route('boleta_calificaciones_bti')" :active="$is('boleta_calificaciones_bti')">Boletas BTI</x-sidebar.link>
            @endif
            @if(has_perm('formatos_kardex_bgne', 'ver'))
            <x-sidebar.link :href="route('kardex_no_escolarizado')" :active="$is('kardex_no_escolarizado')">Kardex BGNE</x-sidebar.link>
            @endif
            @if(has_perm('formatos_actas_extraordinarios', 'ver'))
            <x-sidebar.link :href="route('boleta_calificaciones_extraordinarios')" :active="$is('boleta_calificaciones_extraordinarios')">Actas de Extraordinarios</x-sidebar.link>
            @endif
            @if(has_perm('formatos_listas_asistencia', 'ver'))
            <x-sidebar.link :href="route('listas_asistencias')" :active="$is('listas_asistencias')">Listas de Asistencia</x-sidebar.link>
            @endif
            @if(has_perm('formatos_actas_ordinarios', 'ver'))
            <x-sidebar.link :href="route('actas_calificaciones')" :active="$is('actas_calificaciones')">Actas de Ordinarios</x-sidebar.link>
            @endif
        </x-sidebar.group>
        @endif
    @endif

    {{-- Menú Unificado: Otros --}}
    @if($rol=='ADMIN' || has_perm('planes_bti', 'ver') || has_perm('planes_bgne', 'ver') ||
    has_perm('generaciones', 'ver') || has_perm('busqueda_grupos', 'ver') || has_perm('alumnos_calificaciones', 'ver'))
        <p class="tw:mx-3 tw:mt-4 tw:mb-1 tw:text-[0.8125rem] tw:font-medium tw:text-white/60 tw:sb-collapsed:hidden">Administración</p>

        <x-sidebar.group id="Otros" icon="layers" label="Otros"
            :active="$is('planesBTI', 'planesBGNE', 'generaciones', 'busqueda_grupos', 'boletas_bti', 'personal')">
            @if($rol=='ADMIN' || has_perm('planes_bti', 'ver') || has_perm('planes_bgne', 'ver'))
            <span class="tw:px-3 tw:pt-1 tw:pb-0.5 tw:text-[0.8125rem] tw:font-medium tw:text-white/60">Planes de Estudio</span>
            @if($rol=='ADMIN' || has_perm('planes_bti', 'ver'))
            <x-sidebar.link :href="route('planesBTI')" :active="$is('planesBTI')">Planes BTI</x-sidebar.link>
            @endif
            @if($rol=='ADMIN' || has_perm('planes_bgne', 'ver'))
            <x-sidebar.link :href="route('planesBGNE')" :active="$is('planesBGNE')">Planes BGNE</x-sidebar.link>
            @endif
            <span class="tw:mx-3 tw:my-1.5 tw:block tw:h-px tw:bg-white/10"></span>
            @endif

            @if($rol=='ADMIN' || has_perm('generaciones', 'ver'))
            <x-sidebar.link :href="route('generaciones')" :active="$is('generaciones')">Generaciones</x-sidebar.link>
            @endif
            @if($rol=='ADMIN' || has_perm('busqueda_grupos', 'ver'))
            <x-sidebar.link :href="route('busqueda_grupos')" :active="$is('busqueda_grupos')">Búsqueda de Grupo</x-sidebar.link>
            @endif
            @if(has_perm('alumnos_calificaciones', 'ver'))
            <x-sidebar.link :href="route('boletas_bti')" :active="$is('boletas_bti')">Captura de Calificaciones</x-sidebar.link>
            @endif
            @if($rol=='ADMIN')
            <x-sidebar.link :href="route('personal')" :active="$is('personal')">Personal</x-sidebar.link>
            @endif
        </x-sidebar.group>

        <x-sidebar.group id="EstadoCuenta" icon="receipt" label="Estados de Cuenta" :active="$is('analizar-estado-cuenta')">
            @if($rol=='ADMIN' || has_perm('planes_bti', 'ver'))
            <x-sidebar.link :href="route('analizar-estado-cuenta')" :active="$is('analizar-estado-cuenta')">Verificar Estados de Cuenta</x-sidebar.link>
            @endif
        </x-sidebar.group>
    @endif
    </nav>

    {{-- Pie: colapsar --}}
    <div class="tw:shrink-0 tw:border-t tw:border-white/10 tw:p-3">
        <button type="button" id="toggleSidebar" title="Colapsar / expandir menú"
            class="tw:flex tw:w-full tw:cursor-pointer tw:items-center tw:gap-3 tw:border-0 tw:bg-white/5 tw:px-3 tw:py-2 tw:text-[0.8125rem] tw:font-medium tw:text-white/70 tw:transition-colors tw:hover:bg-white/15 tw:hover:text-white tw:sb-collapsed:justify-center tw:sb-collapsed:px-0">
            <span class="tw:inline-flex tw:transition-transform tw:duration-300 tw:sb-collapsed:rotate-180">
                <i data-lucide="chevrons-left" class="tw:size-[18px]"></i>
            </span>
            <span class="tw:sb-collapsed:hidden">Contraer menú</span>
        </button>
    </div>
</aside>

<script>
// Restaurar estado del sidebar de inmediato para evitar parpadeo (FOUC)
(function () {
    try {
        if (localStorage.getItem('sidebar-state') === 'collapsed') {
            document.getElementById('sidebar')?.classList.add('sidebar-hidden');
            document.body.classList.add('sidebar-hidden');
        }
    } catch (e) {}
})();

document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const toggleButton = document.getElementById('toggleSidebar');
    if (!sidebar) return;

    function setCollapsed(collapsed) {
        sidebar.classList.toggle('sidebar-hidden', collapsed);
        document.body.classList.toggle('sidebar-hidden', collapsed);
        try { localStorage.setItem('sidebar-state', collapsed ? 'collapsed' : 'expanded'); } catch (e) {}
    }

    toggleButton?.addEventListener('click', () => setCollapsed(!sidebar.classList.contains('sidebar-hidden')));

    // Estando colapsado, cualquier clic en un elemento del menú lo expande
    sidebar.querySelectorAll('[data-sb-item], [data-sb-header]').forEach(el => {
        el.addEventListener('click', () => {
            if (sidebar.classList.contains('sidebar-hidden')) setCollapsed(false);
        });
    });

    // Actualiza el badge de notificaciones en tiempo real (API global usada por otras vistas)
    window.actualizarBadgeSidebarNotificaciones = function (total) {
        const badge = document.getElementById('sidebar-notificaciones-badge');
        if (!badge) return;
        const count = parseInt(total, 10) || 0;
        badge.textContent = count > 99 ? '99+' : count;
        badge.classList.toggle('d-none', count <= 0);
    };

    window.recargarConteoNotificacionesSidebar = function () {
        fetch('/notificaciones/count?_t=' + Date.now(), {
            headers: { 'Cache-Control': 'no-cache, no-store, must-revalidate', 'Pragma': 'no-cache' }
        })
            .then(r => { if (!r.ok) throw new Error('Status ' + r.status); return r.json(); })
            .then(data => {
                if (data && typeof data.total !== 'undefined') window.actualizarBadgeSidebarNotificaciones(data.total);
            })
            .catch(err => console.debug('Error fetching notification count:', err));
    };

    window.recargarConteoNotificacionesSidebar();
});
</script>
