@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
/* ==========================================================================
   SISTEMA DE DISEÑO SAAS - ASISTENCIAS DE ALUMNOS (SHADCN / RADIX STYLE)
   ========================================================================== */
.page-container {
    padding: 24px 32px;
    min-height: 100vh;
}

.page-header-standard {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    gap: 16px;
}

.page-heading {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary, #0F172A);
    margin: 0 0 4px 0;
    letter-spacing: -0.01em;
}

.page-subheading {
    font-size: 0.875rem;
    color: var(--text-secondary, #64748B);
    margin: 0;
}

/* KPI Cards */
.kpi-grid-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 16px;
    margin-bottom: 20px;
}

.kpi-card {
    background: #FFFFFF;
    border: 1px solid var(--border-subtle, #E2E8F0);
    border-radius: 0;
    padding: 16px 20px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
    display: flex;
    align-items: center;
    gap: 16px;
}

.kpi-icon-box {
    width: 44px;
    height: 44px;
    border-radius: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.kpi-icon-warning {
    background: #FFFBEB;
    border: 1px solid #FDE68A;
    color: #D97706;
}

.kpi-icon-success {
    background: #F0FDF4;
    border: 1px solid #BBF7D0;
    color: #15803D;
}

.kpi-content {
    display: flex;
    flex-direction: column;
}

.kpi-label {
    font-size: 0.6875rem;
    font-weight: 600;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 2px;
}

.kpi-value {
    font-family: 'JetBrains Mono', monospace;
    font-size: 1.5rem;
    font-weight: 700;
    color: #0F172A;
    line-height: 1.2;
    margin: 0;
}

/* Barra de Filtros y Búsqueda */
.asistencias-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    background: #FFFFFF;
    border: 1px solid var(--border-subtle, #E2E8F0);
    border-radius: 0;
    padding: 10px 14px;
    margin-bottom: 20px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
}

@media (max-width: 768px) {
    .asistencias-toolbar {
        flex-direction: column;
        align-items: stretch;
    }
}

.asistencias-segmented-tabs {
    display: inline-flex;
    align-items: center;
    background: #F1F5F9;
    padding: 4px;
    border-radius: 0;
    gap: 2px;
    flex-shrink: 0;
    white-space: nowrap;
    overflow-x: auto;
}

.asistencias-tab-btn {
    height: 32px;
    padding: 0 14px;
    border-radius: 0;
    font-size: 0.8125rem;
    font-weight: 500;
    color: #475569;
    border: none;
    background: transparent;
    white-space: nowrap;
    cursor: pointer;
    transition: all 0.15s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.asistencias-tab-btn:hover:not(.active) {
    color: #0F172A;
    background: rgba(255, 255, 255, 0.6);
}

.asistencias-tab-btn.active {
    background: var(--brand-primary, #26687B);
    color: #FFFFFF;
    font-weight: 600;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.1);
}

.asistencias-search-wrap {
    flex: 1;
    position: relative;
    display: flex;
    align-items: center;
    max-width: 320px;
}

@media (max-width: 768px) {
    .asistencias-search-wrap {
        max-width: 100%;
    }
}

.asistencias-search-icon {
    position: absolute;
    left: 12px;
    color: #94A3B8;
    font-size: 16px;
    pointer-events: none;
}

.asistencias-search-input {
    width: 100%;
    height: 38px;
    padding: 0 12px 0 36px;
    border: 1px solid var(--border-subtle, #E2E8F0);
    border-radius: 0;
    font-size: 0.875rem;
    color: #0F172A;
    background: #F8FAFC;
    transition: all 0.15s ease;
}

.asistencias-search-input:focus {
    background: #FFFFFF;
    border-color: var(--brand-primary, #26687B);
    outline: none;
    box-shadow: 0 0 0 3px rgba(38, 104, 123, 0.1);
}

/* Tarjeta de Grupo SaaS */
.grupo-card-surface {
    background: #FFFFFF !important;
    border: 1px solid var(--border-subtle, #E2E8F0) !important;
    border-radius: 0!important;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03) !important;
    transition: border-color 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease !important;
    overflow: hidden;
}

.grupo-card-surface:hover {
    border-color: #CBD5E1 !important;
    box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.05) !important;
    transform: translateY(-2px) !important;
}

.grupo-clave {
    font-family: 'JetBrains Mono', monospace;
    font-size: 1rem;
    font-weight: 700;
    color: #0F172A;
    margin: 0;
    letter-spacing: -0.01em;
}

.status-badge-noprog {
    background: #F1F5F9;
    color: #64748B;
    border: 1px solid #E2E8F0;
    font-size: 0.6875rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 0;
    letter-spacing: 0.02em;
    white-space: nowrap;
}

.status-badge-pend {
    background: #FFFBEB;
    color: #D97706;
    border: 1px solid #FDE68A;
    font-size: 0.6875rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 0;
    letter-spacing: 0.02em;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.status-badge-fin {
    background: #DCFCE7;
    color: #15803D;
    border: 1px solid #BBF7D0;
    font-size: 0.6875rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 0;
    letter-spacing: 0.02em;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.metadata-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    font-size: 0.8125rem;
    color: #334155;
    margin-top: 14px;
}

.metadata-item {
    display: flex;
    align-items: center;
    gap: 8px;
    line-height: 1.4;
}

.metadata-icon {
    font-size: 16px;
    color: #64748B;
    width: 20px;
    text-align: center;
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-matriz {
    width: 100%;
    height: 36px;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 0;
    color: var(--brand-primary, #26687B);
    font-size: 0.8125rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    cursor: pointer;
    transition: all 0.15s ease;
    text-decoration: none;
}

.btn-matriz:hover {
    background: #EFF6F7;
    border-color: var(--brand-primary, #26687B);
    color: var(--brand-primary-hover, #1F5565);
}

.btn-pasar-lista {
    width: 100%;
    height: 36px;
    background: var(--brand-primary, #26687B);
    border: 1px solid var(--brand-primary, #26687B);
    border-radius: 0;
    color: #FFFFFF;
    font-size: 0.8125rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    cursor: pointer;
    transition: all 0.15s ease;
    text-decoration: none;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
}

.btn-pasar-lista:hover {
    background: var(--brand-primary-hover, #1F5565);
    border-color: var(--brand-primary-hover, #1F5565);
    color: #FFFFFF;
}
</style>

<div class="page-container">
    <!-- Encabezado de Página -->
    <div class="page-header-standard">
        <div>
            <h1 class="page-heading">Asistencias de Alumnos</h1>
            <p class="page-subheading">Control y registro de asistencias por grupos y días de clase.</p>
        </div>
        @if(session('rol') === 'ADMIN')
            <button type="button" class="btn-primary-saas" onclick="abrirModalJustificacion()">
                <i class="ti ti-shield-check"></i>
                <span>Justificar Faltas</span>
            </button>
        @endif
    </div>

    <!-- Alertas -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 0; background-color: #FEF2F2; color: #991B1B; border: 1px solid #FECACA !important;">
            <i class="ti ti-alert-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
        $dayOfWeek = date('N');
        $diaClaseHoy = '';
        if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
            $diaClaseHoy = 'LUNES-VIERNES';
        } elseif ($dayOfWeek == 6) {
            $diaClaseHoy = 'SABADO';
        } elseif ($dayOfWeek == 7) {
            $diaClaseHoy = 'DOMINGO';
        }

        $pendientesCount = 0;
        $finalizadosCount = 0;

        foreach($grupos as $grupo) {
            $dias = $grupo['diasClase'] ?? [];
            $esHoyClase = in_array($diaClaseHoy, $dias);
            if ($esHoyClase) {
                if (in_array($grupo['id'], $gruposConAsistenciaHoy)) {
                    $finalizadosCount++;
                } else {
                    $pendientesCount++;
                }
            }
        }
    @endphp

    <!-- Estadísticas Rápidas (KPIs) -->
    <div class="kpi-grid-container">
        <!-- Pendientes Hoy -->
        <div class="kpi-card">
            <div class="kpi-icon-box kpi-icon-warning">
                <i class="ti ti-clock-hour-4"></i>
            </div>
            <div class="kpi-content">
                <span class="kpi-label">Pendientes Hoy</span>
                <h3 class="kpi-value" id="stat-pendientes">{{ $pendientesCount }}</h3>
            </div>
        </div>
        <!-- Finalizados Hoy -->
        <div class="kpi-card">
            <div class="kpi-icon-box kpi-icon-success">
                <i class="ti ti-circle-check"></i>
            </div>
            <div class="kpi-content">
                <span class="kpi-label">Finalizados Hoy</span>
                <h3 class="kpi-value" id="stat-finalizados">{{ $finalizadosCount }}</h3>
            </div>
        </div>
    </div>

    <!-- Barra de Filtros y Búsqueda -->
    <div class="asistencias-toolbar">
        <!-- Tabs de Filtro de Días (Segmented Control) -->
        <div class="asistencias-segmented-tabs">
            <button type="button" class="asistencias-tab-btn active" id="tab-todos" onclick="filtrarDia('TODOS')">
                Todos
            </button>
            <button type="button" class="asistencias-tab-btn" id="tab-semana" onclick="filtrarDia('LUNES-VIERNES')">
                Lunes a Viernes
            </button>
            <button type="button" class="asistencias-tab-btn" id="tab-sabados" onclick="filtrarDia('SABADO')">
                Sábados
            </button>
            <button type="button" class="asistencias-tab-btn" id="tab-domingos" onclick="filtrarDia('DOMINGO')">
                Domingos
            </button>
        </div>

        <!-- Buscador por Texto -->
        <div class="asistencias-search-wrap">
            <i class="ti ti-search asistencias-search-icon"></i>
            <input type="text" id="buscadorGrupo" class="asistencias-search-input" placeholder="Buscar grupo por clave o modalidad...">
        </div>
    </div>

    <!-- Listado de Grupos -->
    <div class="row g-4" id="contenedor-grupos">
        @foreach($grupos as $grupo)
            @php
                $dias = $grupo['diasClase'] ?? [];
                $esHoyClase = in_array($diaClaseHoy, $dias);
                $tieneAsistenciaHoy = in_array($grupo['id'], $gruposConAsistenciaHoy);

                $status = 'NO_PROGRAMADO';
                if ($esHoyClase) {
                    $status = $tieneAsistenciaHoy ? 'FINALIZADO' : 'PENDIENTE';
                }

                $diasLabel = '';
                if (in_array('LUNES-VIERNES', $dias)) {
                    $diasLabel = 'Lunes a Viernes';
                } elseif (in_array('SABADO', $dias)) {
                    $diasLabel = 'Sábados';
                } elseif (in_array('DOMINGO', $dias)) {
                    $diasLabel = 'Domingos';
                } else {
                    $diasLabel = implode(', ', $dias);
                }

                // Detección de Turno / Modalidad (Mañana o Tarde)
                $modRaw = strtoupper(trim($grupo['modalidadHorario'] ?? ''));
                $esTarde = str_contains($modRaw, 'TARDE') || str_contains($modRaw, 'VESPERTINO');
                $esManana = str_contains($modRaw, 'MAÑANA') || str_contains($modRaw, 'MANANA') || str_contains($modRaw, 'MATUTINO') || str_contains($modRaw, 'MAANA');

                if ($esTarde) {
                    $turnoLabel = 'Tarde';
                    $turnoBadgeStyle = 'background-color: #FFEDD5; color: #C2410C; border: 1px solid #FED7AA; font-weight: 600;';
                } elseif ($esManana) {
                    $turnoLabel = 'Mañana';
                    $turnoBadgeStyle = 'background-color: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; font-weight: 600;';
                } else {
                    $turnoLabel = !empty($modRaw) ? ucwords(strtolower($modRaw)) : '';
                    $turnoBadgeStyle = 'background-color: #F1F5F9; color: #475569; border: 1px solid #E2E8F0; font-weight: 600;';
                }
            @endphp
            <div class="col-md-6 col-lg-4 elemento-grupo" 
                 data-clave="{{ strtolower($grupo['clave']) }}" 
                 data-dias="{{ implode(',', $dias) }}"
                 data-modalidad="{{ strtolower($turnoLabel . ' ' . $modRaw) }}">
                <div class="card h-100 grupo-card-surface position-relative">
                    <div class="card-body p-4 d-flex flex-column">
                        <!-- Clave del Grupo y Estado -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h4 class="grupo-clave mb-1.5">{{ $grupo['clave'] }}</h4>
                                <div class="d-flex align-items-center gap-1 flex-wrap">
                                    <span class="badge" style="font-size: 0.6875rem; padding: 2px 7px; border-radius: 0; {{ $grupo['id_centroTrabajo'] == 2 ? 'background-color: #E0F2FE; color: #0369A1; border: 1px solid #BAE6FD;' : 'background-color: #EFF6F7; color: #26687B; border: 1px solid #BBDBE1;' }}">
                                        {{ $grupo['nombreCentroTrabajo'] ?? ($grupo['id_centroTrabajo'] == 2 ? 'BTI' : 'BGNE') }}
                                    </span>
                                    @if($turnoLabel)
                                        <span class="badge" style="font-size: 0.6875rem; padding: 2px 7px; border-radius: 0; {{ $turnoBadgeStyle }}">
                                            {{ $turnoLabel }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if($status == 'FINALIZADO')
                                <span class="status-badge-fin">
                                    <i class="ti ti-circle-check" style="font-size: 13px;"></i>FINALIZADO
                                </span>
                            @elseif($status == 'PENDIENTE')
                                <span class="status-badge-pend">
                                    <i class="ti ti-clock" style="font-size: 13px;"></i>PENDIENTE HOY
                                </span>
                            @else
                                <span class="status-badge-noprog">
                                    NO PROGRAMADO HOY
                                </span>
                            @endif
                        </div>

                        <!-- Detalles del Grupo -->
                        <div class="metadata-list flex-grow-1">
                            <div class="metadata-item">
                                <i class="ti ti-calendar-event metadata-icon"></i>
                                <span style="font-weight: 500;">{{ $diasLabel }}</span>
                            </div>
                            <div class="metadata-item">
                                <i class="ti ti-school metadata-icon"></i>
                                <span style="font-weight: 500;">{{ $grupo['nombre_nivel'] ?? 'Sin nivel académico' }}</span>
                            </div>
                            <div class="metadata-item">
                                <i class="ti ti-users metadata-icon"></i>
                                <span style="font-weight: 500;">{{ $grupo['alumnos_count'] ?? 0 }} Alumnos Inscritos</span>
                            </div>
                            <div class="metadata-item">
                                <i class="ti ti-door metadata-icon"></i>
                                <span style="font-weight: 500;">Aula: {{ $grupo['aula'] ?? 'Sin asignar' }}</span>
                            </div>
                        </div>

                        <!-- Divisor Sutil -->
                        <div style="border-top: 1px solid #F1F5F9; margin: 16px 0 14px 0;"></div>

                        <!-- Botón de Acción -->
                        <div>
                            @if($status == 'PENDIENTE')
                                <a href="{{ route('asistencias_alumnos.grupo', $grupo['id']) }}" class="btn-pasar-lista">
                                    <i class="ti ti-checklist" style="font-size: 16px;"></i>Pasar Lista de Hoy
                                </a>
                            @else
                                <a href="{{ route('asistencias_alumnos.grupo', $grupo['id']) }}" class="btn-matriz">
                                    <i class="ti ti-table-options" style="font-size: 16px;"></i>Ver Historial y Matriz
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Mensaje No Encontrado -->
    <div class="text-center py-5 d-none" id="mensaje-vacio">
        <i class="ti ti-folder-x text-muted mb-2" style="font-size: 2.75rem; display: block; color: #94A3B8;"></i>
        <h5 style="color: #0F172A; font-weight: 600; font-size: 1rem; margin-bottom: 4px;">No se encontraron grupos</h5>
        <p class="text-muted small mb-0" style="font-size: 0.8125rem;">Intenta cambiando el filtro de día o el término de búsqueda.</p>
    </div>
</div>

<script>
    let filtroDiaActual = 'TODOS';

    function filtrarDia(dia) {
        filtroDiaActual = dia;
        
        // Actualizar pestañas activas
        document.querySelectorAll('.asistencias-tab-btn, .nav-pills .nav-link').forEach(el => el.classList.remove('active'));
        if (dia === 'TODOS') document.getElementById('tab-todos')?.classList.add('active');
        if (dia === 'LUNES-VIERNES') document.getElementById('tab-semana')?.classList.add('active');
        if (dia === 'SABADO') document.getElementById('tab-sabados')?.classList.add('active');
        if (dia === 'DOMINGO') document.getElementById('tab-domingos')?.classList.add('active');

        aplicarFiltros();
    }

    // Buscador en tiempo real
    const normalizeStr = str => (str || '').normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
    document.getElementById('buscadorGrupo').addEventListener('input', aplicarFiltros);

    function aplicarFiltros() {
        const query = normalizeStr(document.getElementById('buscadorGrupo').value.trim());
        const elementos = document.querySelectorAll('.elemento-grupo');
        let visibles = 0;

        elementos.forEach(el => {
            const clave = el.getAttribute('data-clave') || '';
            const dias = el.getAttribute('data-dias') || '';
            const modalidad = el.getAttribute('data-modalidad') || '';

            const coincideDia = (filtroDiaActual === 'TODOS') || dias.includes(filtroDiaActual);
            const coincideTexto = query.length === 0 || 
                normalizeStr(clave).includes(query) || 
                normalizeStr(modalidad).includes(query);

            if (coincideDia && coincideTexto) {
                el.classList.remove('d-none');
                visibles++;
            } else {
                el.classList.add('d-none');
            }
        });

        const mensajeVacio = document.getElementById('mensaje-vacio');
        if (visibles === 0) {
            mensajeVacio.classList.remove('d-none');
        } else {
            mensajeVacio.classList.add('d-none');
        }
    }

    @if(session('rol') === 'ADMIN')
    let modalJustificacion = null;
    let justTimer = null;

    function abrirModalJustificacion() {
        if(!modalJustificacion) {
            modalJustificacion = new bootstrap.Modal(document.getElementById('modalJustificacion'));
        }
        document.getElementById('formJustificacion').reset();
        document.getElementById('just-seleccionado-container').classList.add('d-none');
        document.getElementById('just-id-alumno').value = '';
        document.getElementById('just-resultados-busqueda').classList.add('d-none');
        document.getElementById('just-resultados-busqueda').innerHTML = '';
        document.getElementById('btn-guardar-justificacion').disabled = true;

        const hoy = new Date().toISOString().split('T')[0];
        document.getElementById('just-fecha-inicio').value = hoy;
        document.getElementById('just-fecha-fin').value = hoy;

        modalJustificacion.show();
    }

    function buscarAlumnoJustificacion(query) {
        clearTimeout(justTimer);
        const resultsDiv = document.getElementById('just-resultados-busqueda');
        if (query.trim().length < 2) {
            resultsDiv.classList.add('d-none');
            resultsDiv.innerHTML = '';
            return;
        }

        justTimer = setTimeout(() => {
            fetch(`/alumnos/lista?search=${encodeURIComponent(query)}&limit=10`)
                .then(res => res.json())
                .then(resp => {
                    const alumnosList = resp.data || [];
                    resultsDiv.innerHTML = '';
                    if (alumnosList.length === 0) {
                        resultsDiv.innerHTML = '<div class="list-group-item text-muted small bg-white">No se encontraron alumnos</div>';
                    } else {
                        alumnosList.forEach(al => {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'list-group-item list-group-item-action text-start bg-white';
                            btn.style.fontSize = '0.8rem';
                            btn.innerHTML = `
                                <strong>${al.apPaterno} ${al.apMaterno || ''} ${al.nombre}</strong><br>
                                <span class="text-muted" style="font-size:0.7rem;">Matrícula: ${al.numeroControl}</span>
                            `;
                            btn.onclick = () => seleccionarAlumnoJustificacion(al);
                            resultsDiv.appendChild(btn);
                        });
                    }
                    resultsDiv.classList.remove('d-none');
                })
                .catch(err => {
                    console.error('Error al buscar alumno:', err);
                    resultsDiv.innerHTML = '<div class="list-group-item text-danger small bg-white">Error al buscar alumno</div>';
                    resultsDiv.classList.remove('d-none');
                });
        }, 300);
    }

    function seleccionarAlumnoJustificacion(al) {
        document.getElementById('just-id-alumno').value = al.idAlumno;
        document.getElementById('just-alumno-nombre').textContent = `${al.apPaterno} ${al.apMaterno || ''} ${al.nombre}`;
        document.getElementById('just-alumno-matricula').textContent = `Matrícula: ${al.numeroControl}`;
        document.getElementById('just-seleccionado-container').classList.remove('d-none');
        document.getElementById('just-buscar-alumno').value = '';
        document.getElementById('just-resultados-busqueda').classList.add('d-none');
        document.getElementById('just-resultados-busqueda').innerHTML = '';
        document.getElementById('btn-guardar-justificacion').disabled = false;
    }

    function guardarJustificacion(e) {
        e.preventDefault();
        const id_alumno = document.getElementById('just-id-alumno').value;
        const fecha_inicio = document.getElementById('just-fecha-inicio').value;
        const fecha_fin = document.getElementById('just-fecha-fin').value;
        const motivo = document.getElementById('just-motivo').value;

        if (!id_alumno || !fecha_inicio || !fecha_fin) {
            Swal.fire({
                icon: 'error',
                title: 'Campos incompletos',
                text: 'Por favor, selecciona un alumno y especifica el rango de fechas.'
            });
            return;
        }

        Swal.fire({
            title: 'Registrando Justificación...',
            text: 'Por favor espera',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        fetch('/asistencias_alumnos/justificar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ id_alumno, fecha_inicio, fecha_fin, motivo })
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.error) {
                Swal.fire({ icon: 'error', title: 'Error', text: resp.error });
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'La justificación ha sido registrada correctamente.',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    modalJustificacion.hide();
                });
            }
        })
        .catch(err => {
            console.error('Error al registrar justificación:', err);
            Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un problema al guardar la justificación.' });
        });
    }
    @endif
</script>

@if(session('rol') === 'ADMIN')
<!-- Modal de Justificación de Faltas -->
<div class="modal fade" id="modalJustificacion" tabindex="-1" aria-labelledby="modalJustificacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title fw-bold mb-0" id="modalJustificacionLabel">
                    <i class="ti ti-shield-check me-2" style="font-size: 1.2rem; vertical-align: -2px; color: #26687B;"></i>Justificar Faltas de Alumno
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formJustificacion" onsubmit="guardarJustificacion(event)">
                <div class="modal-body" style="padding: 20px;">
                    <!-- Búsqueda de Alumno -->
                    <div class="mb-3 position-relative">
                        <label class="form-label" style="font-size: 0.75rem; font-weight: 600; color: #475569; margin-bottom: 6px; text-transform: uppercase;">Buscar Alumno</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 0; border-color: #E2E8F0;">
                                <i class="ti ti-search" style="font-size: 15px; color: #94A3B8;"></i>
                            </span>
                            <input type="text" id="just-buscar-alumno" class="form-control border-start-0 text-dark bg-white" placeholder="Escribe nombre o matrícula..." oninput="buscarAlumnoJustificacion(this.value)" style="border-radius: 0; border-color: #E2E8F0; height: 38px; font-size: 0.875rem;">
                        </div>
                        <!-- Resultados de búsqueda -->
                        <div id="just-resultados-busqueda" class="list-group position-absolute w-100 shadow-sm d-none" style="z-index: 1050; max-height: 200px; overflow-y: auto; border-radius: 0; border: 1px solid #E2E8F0; margin-top: 4px;"></div>
                    </div>

                    <!-- Alumno Seleccionado -->
                    <div class="mb-3 d-none" id="just-seleccionado-container">
                        <span class="text-muted small d-block mb-1" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Alumno Seleccionado:</span>
                        <div class="p-3 bg-light border" style="border-radius: 0; border-color: #E2E8F0 !important;">
                            <strong class="text-dark d-block" id="just-alumno-nombre" style="font-size: 0.875rem;"></strong>
                            <small class="text-muted" id="just-alumno-matricula" style="font-family: 'JetBrains Mono', monospace; font-size: 0.75rem;"></small>
                            <input type="hidden" name="id_alumno" id="just-id-alumno">
                        </div>
                    </div>

                    <!-- Fechas -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label" style="font-size: 0.75rem; font-weight: 600; color: #475569; margin-bottom: 6px; text-transform: uppercase;">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" id="just-fecha-inicio" class="form-control text-dark bg-white" required style="border-radius: 0; border-color: #E2E8F0; height: 38px; font-size: 0.875rem;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size: 0.75rem; font-weight: 600; color: #475569; margin-bottom: 6px; text-transform: uppercase;">Fecha Fin</label>
                            <input type="date" name="fecha_fin" id="just-fecha-fin" class="form-control text-dark bg-white" required style="border-radius: 0; border-color: #E2E8F0; height: 38px; font-size: 0.875rem;">
                        </div>
                    </div>

                    <!-- Motivo -->
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 0.75rem; font-weight: 600; color: #475569; margin-bottom: 6px; text-transform: uppercase;">Motivo / Justificante</label>
                        <textarea name="motivo" id="just-motivo" class="form-control text-dark bg-white" rows="3" placeholder="Ej. Presenta justificante médico..." style="border-radius: 0; border-color: #E2E8F0; font-size: 0.875rem; resize: none;"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 12px 20px;">
                    <button type="button" class="btn-regresar" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-primary-saas" id="btn-guardar-justificacion" disabled>
                        <i class="ti ti-check"></i>
                        <span>Registrar Justificación</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection
