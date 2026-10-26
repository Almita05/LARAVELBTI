@extends('layouts.app')

@section('content')
<div class="dashboard-wrapper">

    {{-- HERO / HEADER DE BIENVENIDA --}}
    <div class="welcome-hero mb-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-welcome-badge">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <span class="badge-role">
                                <i class="bi bi-shield-check me-1"></i>{{ session('rol') ?? 'ADMINISTRADOR' }}
                            </span>
                            <span class="system-status-pill">
                                <span class="status-dot"></span> Sistema en línea
                            </span>
                        </div>
                        <h2 class="welcome-title mb-1" id="greeting-title">
                            ¡Bienvenido, {{ session('nombre') }}!
                        </h2>
                        <p class="welcome-subtitle mb-0">
                            Panel de Control Escolar BTI &bull; Monitoreo en tiempo real de indicadores académicos y matrícula
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="d-inline-flex flex-column align-items-lg-end gap-2">
                    <div class="date-chip">
                        <i class="bi bi-calendar3 me-2 text-info"></i>
                        <span id="current-date-text">Cargando fecha...</span>
                    </div>
                    <button type="button" class="btn-refresh-stats" id="btnRefreshStats" onclick="cargarMetricasDashboard()">
                        <i class="bi bi-arrow-clockwise me-1" id="refreshIcon"></i> Actualizar indicadores
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- TARJETAS KPI (INDICADORES CLAVE) --}}
    <div class="row g-3 mb-4">
        {{-- KPI 1: Alumnos Activos --}}
        <div class="col-xl-3 col-md-6">
            <div class="kpi-card kpi-card-cyan">
                <div class="kpi-icon-box">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="kpi-content">
                    <span class="kpi-label">Matrícula Activa</span>
                    <h3 class="kpi-value" id="kpi-activos"><span class="kpi-skeleton"></span></h3>
                    <div class="kpi-footer">
                        <span class="badge-kpi-sub bg-cyan-sub">
                            <i class="bi bi-mortarboard me-1"></i><span id="kpi-certificados">0</span> Certificados
                        </span>
                        <span class="kpi-secondary-text" title="Total histórico registrado">
                            Total: <strong id="kpi-total-alumnos">--</strong>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- KPI 2: Plantilla Docente --}}
        <div class="col-xl-3 col-md-6">
            <div class="kpi-card kpi-card-indigo">
                <div class="kpi-icon-box">
                    <i class="bi bi-person-video3"></i>
                </div>
                <div class="kpi-content">
                    <span class="kpi-label">Plantilla Docente</span>
                    <h3 class="kpi-value" id="kpi-docentes"><span class="kpi-skeleton"></span></h3>
                    <div class="kpi-footer">
                        <span class="badge-kpi-sub bg-indigo-sub">
                            <i class="bi bi-check-circle me-1"></i>Docentes Activos
                        </span>
                        <span class="kpi-secondary-text">
                            <strong id="kpi-materias">--</strong> materias
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- KPI 3: Grupos en Operación --}}
        <div class="col-xl-3 col-md-6">
            <div class="kpi-card kpi-card-teal">
                <div class="kpi-icon-box">
                    <i class="bi bi-building-check"></i>
                </div>
                <div class="kpi-content">
                    <span class="kpi-label">Grupos Escolares</span>
                    <h3 class="kpi-value" id="kpi-grupos"><span class="kpi-skeleton"></span></h3>
                    <div class="kpi-footer">
                        <span class="badge-kpi-sub bg-teal-sub">
                            <i class="bi bi-calendar-week me-1"></i>BTI & BGNE
                        </span>
                        <span class="kpi-secondary-text">
                            Semana / Sáb / Dom
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- KPI 4: Asistencia Global --}}
        <div class="col-xl-3 col-md-6">
            <div class="kpi-card kpi-card-emerald">
                <div class="kpi-icon-box">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>
                <div class="kpi-content">
                    <span class="kpi-label">Tasa de Asistencia</span>
                    <h3 class="kpi-value" id="kpi-asistencia"><span class="kpi-skeleton"></span></h3>
                    <div class="kpi-footer">
                        <span class="badge-kpi-sub bg-emerald-sub" id="badge-asistencia-status">
                            <i class="bi bi-arrow-up-right me-1"></i>Alta presencia
                        </span>
                        <span class="kpi-secondary-text">
                            <strong id="kpi-presentes">0</strong> ptes / <strong id="kpi-faltas">0</strong> fts
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SECCIÓN DE GRÁFICOS INTERACTIVOS --}}
    <div class="row g-4 mb-4">
        {{-- Gráfico 1: Alumnos por Grupo Activo --}}
        <div class="col-lg-8">
            <div class="chart-glass-panel">
                <div class="panel-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="panel-title mb-1">
                            <i class="bi bi-bar-chart-fill me-2 text-info"></i>Alumnos por Grupo
                        </h5>
                        <p class="panel-subtitle mb-0">Distribución de estudiantes inscritos en los grupos activos</p>
                    </div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">
                        <i class="bi bi-layers me-1"></i>Ciclo Actual
                    </span>
                </div>
                <div class="panel-body">
                    <div class="chart-container position-relative" style="height: 310px;">
                        <canvas id="chartGrupos"></canvas>
                        <div id="chartGruposEmpty" class="chart-empty-state d-none">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="mt-2 text-muted">No hay datos de grupos disponibles</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gráfico 2: Estatus del Alumnado --}}
        <div class="col-lg-4">
            <div class="chart-glass-panel">
                <div class="panel-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="panel-title mb-1">
                            <i class="bi bi-pie-chart-fill me-2 text-info"></i>Estatus del Alumnado
                        </h5>
                        <p class="panel-subtitle mb-0">Composición histórica y activa</p>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="chart-container position-relative" style="height: 230px;">
                        <canvas id="chartEstatus"></canvas>
                    </div>
                    {{-- Mini resumen debajo de la dona --}}
                    <div class="donut-legend-grid mt-3 pt-2 border-top border-secondary-subtle">
                        <div class="donut-stat-item">
                            <span class="donut-dot dot-active"></span>
                            <span class="donut-label">Activos</span>
                            <strong class="donut-val text-info" id="legend-activos">0</strong>
                        </div>
                        <div class="donut-stat-item">
                            <span class="donut-dot dot-certified"></span>
                            <span class="donut-label">Certificados</span>
                            <strong class="donut-val text-success" id="legend-certificados">0</strong>
                        </div>
                        <div class="donut-stat-item">
                            <span class="donut-dot dot-inactive"></span>
                            <span class="donut-label">Histórico</span>
                            <strong class="donut-val text-muted" id="legend-inactivos">0</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ACCESOS RÁPIDOS (QUICK ACTIONS) --}}
    <div class="quick-launchpad mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="section-heading mb-0">
                <i class="bi bi-lightning-charge-fill me-2 text-warning"></i>Accesos Rápidos del Sistema
            </h5>
            <small class="text-muted">Módulos más utilizados</small>
        </div>
        <div class="row g-3">
            <div class="col-xl-2 col-md-4 col-6">
                <a href="{{ route('alumnos') }}" class="quick-action-card">
                    <div class="quick-icon-wrapper icon-bg-blue">
                        <i class="bi bi-search"></i>
                    </div>
                    <span class="quick-action-title">Buscador Alumnos</span>
                    <span class="quick-action-hint">Expedientes & CURP</span>
                </a>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <a href="{{ route('grupos.captura_calificaciones') }}" class="quick-action-card">
                    <div class="quick-icon-wrapper icon-bg-green">
                        <i class="bi bi-card-checklist"></i>
                    </div>
                    <span class="quick-action-title">Capturar Notas</span>
                    <span class="quick-action-hint">Parciales & Semestral</span>
                </a>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <a href="{{ route('asistencias_alumnos') }}" class="quick-action-card">
                    <div class="quick-icon-wrapper icon-bg-teal">
                        <i class="bi bi-calendar2-check"></i>
                    </div>
                    <span class="quick-action-title">Pase de Lista</span>
                    <span class="quick-action-hint">Control de Asistencias</span>
                </a>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <a href="{{ route('reportes.imprimir') }}" class="quick-action-card">
                    <div class="quick-icon-wrapper icon-bg-purple">
                        <i class="bi bi-printer-fill"></i>
                    </div>
                    <span class="quick-action-title">Módulo Impresión</span>
                    <span class="quick-action-hint">Kardex, Boletas, Cred.</span>
                </a>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <a href="{{ route('horarios') }}" class="quick-action-card">
                    <div class="quick-icon-wrapper icon-bg-amber">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <span class="quick-action-title">Horarios</span>
                    <span class="quick-action-hint">Aulas & Profesores</span>
                </a>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <a href="{{ route('reportes_indisciplina.index_view') }}" class="quick-action-card">
                    <div class="quick-icon-wrapper icon-bg-rose">
                        <i class="bi bi-exclamation-octagon-fill"></i>
                    </div>
                    <span class="quick-action-title">Indisciplina</span>
                    <span class="quick-action-hint">Reportes & Citatorios</span>
                </a>
            </div>
        </div>
    </div>

    {{-- SECCIÓN INFERIOR: REPORTES RECIENTES & OFERTA EDUCATIVA --}}
    <div class="row g-4 mb-4">
        {{-- Columna 1: Reportes Recientes de Indisciplina --}}
        <div class="col-lg-7">
            <div class="chart-glass-panel h-100">
                <div class="panel-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="panel-title mb-1">
                            <i class="bi bi-shield-exclamation me-2 text-warning"></i>Últimas Incidencias de Indisciplina
                        </h5>
                        <p class="panel-subtitle mb-0">Seguimiento de conducta de los alumnos</p>
                    </div>
                    <a href="{{ route('reportes_indisciplina.index_view') }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                        Ver todos <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </div>
                <div class="panel-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-incidencias mb-0">
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Alumno</th>
                                    <th>Incidente</th>
                                    <th>Fecha</th>
                                    <th class="text-center">Parcial</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-reportes-body">
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <div class="spinner-border spinner-border-sm text-info me-2" role="status"></div>
                                        Cargando incidentes...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Columna 2: Centros de Trabajo / Planteles Oficiales --}}
        <div class="col-lg-5">
            <div class="chart-glass-panel h-100">
                <div class="panel-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="panel-title mb-1">
                            <i class="bi bi-buildings-fill me-2 text-info"></i>Oferta Educativa y CCTs
                        </h5>
                        <p class="panel-subtitle mb-0">Centros de Trabajo incorporados</p>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="cct-cards-list">
                        {{-- CCT 1: BTI --}}
                        <div class="cct-item-card">
                            <div class="cct-icon-chip bg-primary-subtle text-primary">
                                <i class="bi bi-award"></i>
                            </div>
                            <div class="cct-info flex-grow-1">
                                <h6 class="cct-name mb-0">Bachillerato Tecnológico Interamericano</h6>
                                <span class="cct-clave"><i class="bi bi-fingerprint me-1"></i>CCT: <strong>21PCT0073R</strong></span>
                            </div>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                                Escolarizado
                            </span>
                        </div>

                        {{-- CCT 2: BGNE --}}
                        <div class="cct-item-card">
                            <div class="cct-icon-chip bg-info-subtle text-info">
                                <i class="bi bi-journal-bookmark"></i>
                            </div>
                            <div class="cct-info flex-grow-1">
                                <h6 class="cct-name mb-0">Bachillerato General No Escolarizado</h6>
                                <span class="cct-clave"><i class="bi bi-fingerprint me-1"></i>CCT: <strong>21PBH0353G</strong></span>
                            </div>
                            <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2 py-1">
                                Fines de Semana
                            </span>
                        </div>

                        {{-- CCT 3: Computación --}}
                        <div class="cct-item-card">
                            <div class="cct-icon-chip bg-warning-subtle text-warning">
                                <i class="bi bi-cpu"></i>
                            </div>
                            <div class="cct-info flex-grow-1">
                                <h6 class="cct-name mb-0">Informática y Computación</h6>
                                <span class="cct-clave"><i class="bi bi-fingerprint me-1"></i>CCT: <strong>21PBT0322I</strong></span>
                            </div>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1">
                                Técnico
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- SCRIPT CHART.JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<style>
/* =========================================================
   DASHBOARD ESTILOS PREMIUM BTI
   ========================================================= */
.dashboard-wrapper {
    width: 100%;
    color: #1e293b;
    padding-bottom: 30px;
}

/* HERO BIENVENIDA */
.welcome-hero {
    background: linear-gradient(135deg, rgba(38, 104, 123, 0.95), rgba(20, 58, 69, 0.95));
    border-radius: 20px;
    padding: 24px 28px;
    color: #ffffff;
    box-shadow: 0 10px 30px rgba(18, 54, 65, 0.25);
    border: 1px solid rgba(255, 255, 255, 0.12);
    position: relative;
    overflow: hidden;
}

.welcome-hero::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 320px;
    height: 320px;
    background: radial-gradient(circle, rgba(107, 199, 232, 0.25) 0%, transparent 70%);
    pointer-events: none;
}

.avatar-welcome-badge {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    color: #6bc7e8;
    border: 1px solid rgba(255, 255, 255, 0.25);
    flex-shrink: 0;
}

.badge-role {
    display: inline-block;
    padding: 4px 10px;
    background: rgba(107, 199, 232, 0.2);
    color: #6bc7e8;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    border-radius: 20px;
    border: 1px solid rgba(107, 199, 232, 0.4);
    text-transform: uppercase;
}

.system-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    background: rgba(16, 185, 129, 0.15);
    color: #6ee7b7;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 20px;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background-color: #10b981;
    display: inline-block;
    box-shadow: 0 0 8px #10b981;
    animation: pulseDot 2s infinite ease-in-out;
}

@keyframes pulseDot {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.3); opacity: 0.6; }
}

.welcome-title {
    font-size: 1.65rem;
    font-weight: 700;
    color: #ffffff;
    letter-spacing: -0.3px;
}

.welcome-subtitle {
    font-size: 0.92rem;
    color: #d2ecf5;
    opacity: 0.95;
}

.date-chip {
    display: inline-flex;
    align-items: center;
    padding: 7px 14px;
    background: rgba(0, 0, 0, 0.25);
    border-radius: 12px;
    font-size: 0.85rem;
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.btn-refresh-stats {
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.25);
    color: #ffffff;
    font-size: 0.82rem;
    font-weight: 600;
    padding: 7px 14px;
    border-radius: 12px;
    transition: all 0.25s ease;
    cursor: pointer;
}

.btn-refresh-stats:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* ROTATE ANIMATION FOR REFRESH */
.rotating-icon {
    animation: spin 0.8s linear infinite;
    display: inline-block;
}

@keyframes spin {
    100% { transform: rotate(360deg); }
}

/* KPI CARDS */
.kpi-card {
    background: #ffffff;
    border-radius: 18px;
    padding: 20px 22px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
    display: flex;
    align-items: flex-start;
    gap: 16px;
    position: relative;
    overflow: hidden;
    transition: all 0.25s ease;
}

.kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 25px rgba(38, 104, 123, 0.12);
}

.kpi-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 5px;
    height: 100%;
}

.kpi-card-cyan::before { background: linear-gradient(180deg, #00BCD4, #0288D1); }
.kpi-card-indigo::before { background: linear-gradient(180deg, #6366F1, #4338CA); }
.kpi-card-teal::before { background: linear-gradient(180deg, #14B8A6, #0D9488); }
.kpi-card-emerald::before { background: linear-gradient(180deg, #10B981, #059669); }

.kpi-icon-box {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    flex-shrink: 0;
}

.kpi-card-cyan .kpi-icon-box { background: rgba(0, 188, 212, 0.12); color: #0097A7; }
.kpi-card-indigo .kpi-icon-box { background: rgba(99, 102, 241, 0.12); color: #4F46E5; }
.kpi-card-teal .kpi-icon-box { background: rgba(20, 184, 166, 0.12); color: #0D9488; }
.kpi-card-emerald .kpi-icon-box { background: rgba(16, 185, 129, 0.12); color: #059669; }

.kpi-content {
    flex-grow: 1;
}

.kpi-label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.kpi-value {
    font-size: 1.85rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
    margin-bottom: 8px;
    letter-spacing: -0.5px;
}

.kpi-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.78rem;
    gap: 6px;
    flex-wrap: wrap;
}

.badge-kpi-sub {
    padding: 3px 8px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.74rem;
}

.bg-cyan-sub { background: #e0f7fa; color: #006064; }
.bg-indigo-sub { background: #eef2ff; color: #3730a3; }
.bg-teal-sub { background: #ccfbf1; color: #115e59; }
.bg-emerald-sub { background: #d1fae5; color: #065f46; }

.kpi-secondary-text {
    color: #64748b;
    font-size: 0.76rem;
}

.kpi-skeleton {
    display: inline-block;
    width: 60px;
    height: 28px;
    background: #e2e8f0;
    border-radius: 6px;
    animation: skeletonPulse 1.5s infinite ease-in-out;
}

@keyframes skeletonPulse {
    0%, 100% { opacity: 0.6; }
    50% { opacity: 1; }
}

/* PANELES DE GRÁFICOS Y CONTENIDO */
.chart-glass-panel {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
    padding: 22px 24px;
    transition: all 0.25s ease;
}

.chart-glass-panel:hover {
    box-shadow: 0 8px 25px rgba(38, 104, 123, 0.08);
}

.panel-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e293b;
    letter-spacing: -0.2px;
}

.panel-subtitle {
    font-size: 0.82rem;
    color: #64748b;
}

.chart-empty-state {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

/* LEYENDA DEL DONUT */
.donut-legend-grid {
    display: flex;
    justify-content: space-around;
    gap: 8px;
    text-align: center;
}

.donut-stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
}

.donut-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    display: inline-block;
}

.dot-active { background-color: #00BCD4; box-shadow: 0 0 6px rgba(0, 188, 212, 0.5); }
.dot-certified { background-color: #10B981; box-shadow: 0 0 6px rgba(16, 185, 129, 0.5); }
.dot-inactive { background-color: #94A3B8; }

.donut-label {
    font-size: 0.73rem;
    color: #64748b;
    font-weight: 600;
}

.donut-val {
    font-size: 1rem;
    font-weight: 700;
}

/* QUICK LAUNCHPAD */
.section-heading {
    font-size: 1.15rem;
    font-weight: 700;
    color: #1e293b;
}

.quick-action-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 18px 12px;
    text-decoration: none;
    color: #1e293b;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
    transition: all 0.25s ease;
}

.quick-action-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 22px rgba(38, 104, 123, 0.12);
    border-color: #317d92;
    color: #317d92;
}

.quick-icon-wrapper {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-bottom: 10px;
    transition: all 0.25s ease;
}

.quick-action-card:hover .quick-icon-wrapper {
    transform: scale(1.08);
}

.icon-bg-blue { background: #e0f2fe; color: #0284c7; }
.icon-bg-green { background: #dcfce7; color: #16a34a; }
.icon-bg-teal { background: #ccfbf1; color: #0d9488; }
.icon-bg-purple { background: #f3e8ff; color: #9333ea; }
.icon-bg-amber { background: #fef3c7; color: #d97706; }
.icon-bg-rose { background: #ffe4e6; color: #e11d48; }

.quick-action-title {
    font-size: 0.84rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 3px;
}

.quick-action-hint {
    font-size: 0.72rem;
    color: #94a3b8;
}

/* TABLA INCIDENCIAS */
.table-incidencias {
    font-size: 0.86rem;
}

.table-incidencias thead th {
    background: #f8fafc;
    color: #475569;
    font-weight: 600;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #e2e8f0;
    padding: 12px 16px;
}

.table-incidencias tbody td {
    padding: 12px 16px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}

.badge-folio {
    font-family: monospace;
    font-size: 0.76rem;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 6px;
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #cbd5e1;
}

/* CCT ITEMS */
.cct-cards-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.cct-item-card {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 16px;
    border-radius: 14px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}

.cct-item-card:hover {
    background: #ffffff;
    border-color: #cbd5e1;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.cct-icon-chip {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.cct-name {
    font-size: 0.88rem;
    font-weight: 700;
    color: #1e293b;
}

.cct-clave {
    font-size: 0.78rem;
    color: #64748b;
}
</style>

<script>
// =========================================================
// LOGICA DINÁMICA DEL DASHBOARD (AJAX + CHART.JS)
// =========================================================
let chartGruposInstance = null;
let chartEstatusInstance = null;

document.addEventListener('DOMContentLoaded', function () {
    actualizarSaludoYFecha();
    cargarMetricasDashboard();
});

function actualizarSaludoYFecha() {
    const ahora = new Date();
    const hora = ahora.getHours();
    let saludo = "¡Buen día";

    if (hora >= 5 && hora < 12) {
        saludo = "¡Buenos días";
    } else if (hora >= 12 && hora < 19) {
        saludo = "¡Buenas tardes";
    } else {
        saludo = "¡Buenas noches";
    }

    const titleEl = document.getElementById('greeting-title');
    if (titleEl) {
        titleEl.textContent = `${saludo}, {{ session('nombre') }}!`;
    }

    // Fecha en español
    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const fechaFormateada = ahora.toLocaleDateString('es-MX', opciones);
    const dateTextEl = document.getElementById('current-date-text');
    if (dateTextEl) {
        // Capitalizar primera letra
        dateTextEl.textContent = fechaFormateada.charAt(0).toUpperCase() + fechaFormateada.slice(1);
    }
}

async function cargarMetricasDashboard() {
    const icon = document.getElementById('refreshIcon');
    if (icon) icon.classList.add('rotating-icon');

    try {
        const response = await fetch("{{ route('dashboard.stats') }}", {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.status === 401) {
            console.warn('Sesión expirada');
            window.location.href = "{{ route('login') }}";
            return;
        }

        if (!response.ok) {
            throw new Error(`HTTP error ${response.status}`);
        }

        const data = await response.json();

        if (data.success && data.kpis) {
            actualizarKPIs(data.kpis);
            renderizarGraficoGrupos(data.charts ? data.charts.grupos : []);
            renderizarGraficoEstatus(data.charts ? data.charts.estatus_alumnos : null);
            renderizarReportesRecientes(data.reportes_recientes || []);
        }
    } catch (err) {
        console.error('Error al cargar métricas del dashboard:', err);
        ['kpi-activos', 'kpi-docentes', 'kpi-grupos', 'kpi-asistencia'].forEach(id => {
            const el = document.getElementById(id);
            if (el && el.querySelector('.kpi-skeleton')) {
                el.innerHTML = '<span class="text-muted fs-5">0</span>';
            }
        });
    } finally {
        if (icon) {
            setTimeout(() => icon.classList.remove('rotating-icon'), 500);
        }
    }
}

function animarNumero(idElemento, valorFinal, esPorcentaje = false) {
    const el = document.getElementById(idElemento);
    if (!el) return;

    const duracion = 800;
    const inicio = 0;
    const pasos = 25;
    const incremento = valorFinal / pasos;
    let actual = 0;
    let contador = 0;

    const timer = setInterval(() => {
        contador++;
        actual += incremento;
        if (contador >= pasos) {
            actual = valorFinal;
            clearInterval(timer);
        }
        if (esPorcentaje) {
            el.textContent = `${actual.toFixed(1)}%`;
        } else if (Number.isInteger(valorFinal)) {
            el.textContent = Math.round(actual).toLocaleString('es-MX');
        } else {
            el.textContent = actual.toFixed(2);
        }
    }, duracion / pasos);
}

function actualizarKPIs(kpis) {
    animarNumero('kpi-activos', kpis.alumnos_activos || 0);
    animarNumero('kpi-docentes', kpis.total_docentes || 0);
    animarNumero('kpi-grupos', kpis.total_grupos || 0);
    animarNumero('kpi-asistencia', kpis.tasa_asistencia || 0, true);

    document.getElementById('kpi-certificados').textContent = (kpis.alumnos_certificados || 0).toLocaleString('es-MX');
    document.getElementById('kpi-total-alumnos').textContent = (kpis.total_alumnos || 0).toLocaleString('es-MX');
    document.getElementById('kpi-materias').textContent = (kpis.total_materias || 0);
    document.getElementById('kpi-presentes').textContent = (kpis.presentes || 0);
    document.getElementById('kpi-faltas').textContent = (kpis.faltas || 0);

    // Valores para leyenda del donut
    document.getElementById('legend-activos').textContent = (kpis.alumnos_activos || 0);
    document.getElementById('legend-certificados').textContent = (kpis.alumnos_certificados || 0);
    document.getElementById('legend-inactivos').textContent = (kpis.alumnos_inactivos || 0);
}

function renderizarGraficoGrupos(grupos) {
    const canvas = document.getElementById('chartGrupos');
    if (!canvas) return;

    const emptyState = document.getElementById('chartGruposEmpty');

    if (!grupos || grupos.length === 0) {
        if (emptyState) emptyState.classList.remove('d-none');
        return;
    }
    if (emptyState) emptyState.classList.add('d-none');

    // Filtrar grupos relevantes (hasta 8 grupos con mayor alumnado)
    const topGrupos = grupos.slice(0, 8);
    const labels = topGrupos.map(g => g.clave);
    const dataVals = topGrupos.map(g => g.total);

    if (chartGruposInstance) {
        chartGruposInstance.destroy();
    }

    const ctx = canvas.getContext('2d');

    // Gradiente sutil
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(49, 125, 146, 0.95)');
    gradient.addColorStop(1, 'rgba(107, 199, 232, 0.55)');

    chartGruposInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Alumnos Asignados',
                data: dataVals,
                backgroundColor: gradient,
                borderColor: '#26687b',
                borderWidth: 1.5,
                borderRadius: 8,
                borderSkipped: false,
                barPercentage: 0.6,
                categoryPercentage: 0.7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12 },
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function (context) {
                            return ` ${context.parsed.y} alumno(s) inscritos`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        color: '#64748b',
                        font: { size: 11 }
                    },
                    grid: {
                        color: '#f1f5f9'
                    }
                },
                x: {
                    ticks: {
                        color: '#475569',
                        font: { size: 11, weight: '600' }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

function renderizarGraficoEstatus(estatus) {
    const canvas = document.getElementById('chartEstatus');
    if (!canvas || !estatus) return;

    if (chartEstatusInstance) {
        chartEstatusInstance.destroy();
    }

    const ctx = canvas.getContext('2d');

    const total = (estatus.activos || 0) + (estatus.certificados || 0) + (estatus.inactivos || 0);

    chartEstatusInstance = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Activos', 'Certificados', 'Histórico'],
            datasets: [{
                data: [
                    estatus.activos || 0,
                    estatus.certificados || 0,
                    estatus.inactivos || 0
                ],
                backgroundColor: [
                    '#00BCD4', // Cyan
                    '#10B981', // Emerald
                    '#94A3B8'  // Slate
                ],
                hoverBackgroundColor: [
                    '#0097A7',
                    '#059669',
                    '#64748B'
                ],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleFont: { size: 12, weight: 'bold' },
                    bodyFont: { size: 12 },
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function (context) {
                            const val = context.parsed;
                            const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                            return ` ${context.label}: ${val.toLocaleString('es-MX')} (${pct}%)`;
                        }
                    }
                }
            }
        }
    });
}

function renderizarReportesRecientes(reportes) {
    const tbody = document.getElementById('tabla-reportes-body');
    if (!tbody) return;

    if (!reportes || reportes.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-4 text-muted">
                    <i class="bi bi-check-circle-fill text-success fs-4 d-block mb-1"></i>
                    No se registran incidencias recientes de indisciplina.
                </td>
            </tr>
        `;
        return;
    }

    let html = '';
    reportes.forEach(r => {
        html += `
            <tr>
                <td><span class="badge-folio">${r.folio}</span></td>
                <td>
                    <div class="fw-semibold text-dark">${r.alumno_nombre || 'Alumno'}</div>
                    <small class="text-muted">${r.tutor_nombre ? 'Tutor: ' + r.tutor_nombre : 'Sin tutor reg.'}</small>
                </td>
                <td class="text-truncate" style="max-width: 220px;" title="${r.incidente}">
                    ${r.incidente}
                </td>
                <td class="text-nowrap text-secondary">${r.fecha || '--'}</td>
                <td class="text-center">
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill">
                        P${r.parcial || 1}
                    </span>
                </td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
}
</script>
@endsection
