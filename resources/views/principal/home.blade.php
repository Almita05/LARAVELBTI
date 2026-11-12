@extends('layouts.app')

@section('content')
<div class="dashboard-wrapper">

    {{-- 1. REEMPLAZAR EL BANNER GIGANTE POR UN "EXECUTIVE HEADER" COMPACTO --}}
    <div class="executive-header">
        <div class="header-left">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <h1 class="page-title">Panel de Control Escolar</h1>
                <span class="system-status-pill">
                    <span class="status-dot"></span>
                    Sistema en línea
                </span>
            </div>
            <p class="page-subtitle">
                <span id="greeting-title" class="greeting-highlight">¡Bienvenido, {{ session('nombre') }}!</span> &bull; Monitoreo en tiempo real de indicadores académicos y matrícula
            </p>
        </div>
        
        <div class="header-right">
            <div class="date-chip">
                <i class="ti ti-calendar"></i>
                <span id="current-date-text">Cargando fecha...</span>
            </div>
            <button type="button" class="btn-refresh-stats" id="btnRefreshStats" onclick="cargarMetricasDashboard()">
                <i class="ti ti-refresh" id="refreshIcon"></i>
                <span>Actualizar Indicadores</span>
            </button>
        </div>
    </div>

    {{-- 2. REFACTORIZACIÓN DE LAS 4 TARJETAS DE KPIS (ESTILO SHADCN/UI) --}}
    <div class="kpis-grid">
        {{-- KPI 1: Matrícula Activa --}}
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-label">Matrícula Activa</span>
                <div class="kpi-icon-box">
                    <i class="ti ti-users"></i>
                </div>
            </div>
            <h3 class="kpi-value" id="kpi-activos"><span class="kpi-skeleton"></span></h3>
            <div class="kpi-footer">
                <span class="badge-kpi-sub">
                    <span id="kpi-certificados">0</span> Certificados
                </span>
                <span class="kpi-secondary-text" title="Total histórico registrado">
                    Total: <strong id="kpi-total-alumnos">--</strong>
                </span>
            </div>
        </div>

        {{-- KPI 2: Plantilla Docente --}}
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-label">Plantilla Docente</span>
                <div class="kpi-icon-box">
                    <i class="ti ti-chalkboard"></i>
                </div>
            </div>
            <h3 class="kpi-value" id="kpi-docentes"><span class="kpi-skeleton"></span></h3>
            <div class="kpi-footer">
                <span class="badge-kpi-sub">
                    Docentes Activos
                </span>
                <span class="kpi-secondary-text">
                    <strong id="kpi-materias">--</strong> materias
                </span>
            </div>
        </div>

        {{-- KPI 3: Grupos Escolares --}}
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-label">Grupos Escolares</span>
                <div class="kpi-icon-box">
                    <i class="ti ti-users-group"></i>
                </div>
            </div>
            <h3 class="kpi-value" id="kpi-grupos"><span class="kpi-skeleton"></span></h3>
            <div class="kpi-footer">
                <span class="badge-kpi-sub">
                    BTI & BGNE
                </span>
                <span class="kpi-secondary-text">
                    Semana / Sáb / Dom
                </span>
            </div>
        </div>

        {{-- KPI 4: Tasa de Asistencia --}}
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-label">Tasa de Asistencia</span>
                <div class="kpi-icon-box">
                    <i class="ti ti-user-check"></i>
                </div>
            </div>
            <h3 class="kpi-value" id="kpi-asistencia"><span class="kpi-skeleton"></span></h3>
            <div class="kpi-footer">
                <span class="badge-kpi-sub" id="badge-asistencia-status">
                    Alta presencia
                </span>
                <span class="kpi-secondary-text">
                    <strong id="kpi-presentes">0</strong> ptes / <strong id="kpi-faltas">0</strong> fts
                </span>
            </div>
        </div>
    </div>

    {{-- 3. SECCIÓN DE GRÁFICAS ("ALUMNOS POR GRUPO" Y "ESTATUS") --}}
    <div class="charts-grid">
        {{-- Gráfico 1: Alumnos por Grupo Activo --}}
        <div class="chart-glass-panel">
            <div class="panel-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="panel-title mb-1">Alumnos por Grupo</h5>
                    <p class="panel-subtitle mb-0">Distribución de estudiantes inscritos en los grupos activos</p>
                </div>
                <span class="badge-ciclo-actual">
                    Ciclo Actual
                </span>
            </div>
            <div class="panel-body mt-3">
                <div class="chart-container position-relative" style="height: 300px;">
                    <canvas id="chartGrupos"></canvas>
                    <div id="chartGruposEmpty" class="chart-empty-state d-none">
                        <i class="ti ti-inbox" style="font-size: 32px; color: #94A3B8;"></i>
                        <p class="mt-2 text-muted" style="font-size: 0.8125rem;">No hay datos de grupos disponibles</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gráfico 2: Estatus del Alumnado --}}
        <div class="chart-glass-panel">
            <div class="panel-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="panel-title mb-1">Estatus del Alumnado</h5>
                    <p class="panel-subtitle mb-0">Composición histórica y activa</p>
                </div>
            </div>
            <div class="panel-body mt-3">
                <div class="chart-container position-relative" style="height: 220px;">
                    <canvas id="chartEstatus"></canvas>
                    {{-- Centro absoluto del anillo con métrica activa --}}
                    <div class="donut-center-metric">
                        <span class="donut-center-value" id="donut-center-total">--</span>
                        <span class="donut-center-label">Activos</span>
                    </div>
                </div>
                {{-- Resumen leyenda debajo de la dona --}}
                <div class="donut-legend-grid mt-3 pt-3 border-top">
                    <div class="donut-stat-item">
                        <span class="donut-dot dot-active"></span>
                        <span class="donut-label">Activos</span>
                        <strong class="donut-val" id="legend-activos">0</strong>
                    </div>
                    <div class="donut-stat-item">
                        <span class="donut-dot dot-certified"></span>
                        <span class="donut-label">Certificados</span>
                        <strong class="donut-val" id="legend-certificados">0</strong>
                    </div>
                    <div class="donut-stat-item">
                        <span class="donut-dot dot-inactive"></span>
                        <span class="donut-label">Histórico</span>
                        <strong class="donut-val" id="legend-inactivos">0</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. ACCESOS RÁPIDOS DEL SISTEMA (ACTION TILES EJECUTIVOS) --}}
    <div class="quick-launchpad mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="section-heading mb-0">Accesos Rápidos del Sistema</h5>
            <span class="section-subtitle">Módulos más utilizados</span>
        </div>
        <div class="quick-actions-grid">
            <a href="{{ route('alumnos') }}" class="quick-action-card">
                <div class="quick-icon-box">
                    <i class="ti ti-user-search"></i>
                </div>
                <div class="quick-action-info">
                    <span class="quick-action-title">Buscador Alumnos</span>
                    <span class="quick-action-hint">Expedientes & CURP</span>
                </div>
            </a>

            <a href="{{ route('grupos.captura_calificaciones') }}" class="quick-action-card">
                <div class="quick-icon-box">
                    <i class="ti ti-notes"></i>
                </div>
                <div class="quick-action-info">
                    <span class="quick-action-title">Capturar Notas</span>
                    <span class="quick-action-hint">Parciales & Semestral</span>
                </div>
            </a>

            <a href="{{ route('asistencias_alumnos') }}" class="quick-action-card">
                <div class="quick-icon-box">
                    <i class="ti ti-checkbox"></i>
                </div>
                <div class="quick-action-info">
                    <span class="quick-action-title">Pase de Lista</span>
                    <span class="quick-action-hint">Control de Asistencias</span>
                </div>
            </a>

            <a href="{{ route('reportes.imprimir') }}" class="quick-action-card">
                <div class="quick-icon-box">
                    <i class="ti ti-printer"></i>
                </div>
                <div class="quick-action-info">
                    <span class="quick-action-title">Módulo Impresión</span>
                    <span class="quick-action-hint">Kardex, Boletas, Cred.</span>
                </div>
            </a>

            <a href="{{ route('horarios') }}" class="quick-action-card">
                <div class="quick-icon-box">
                    <i class="ti ti-calendar-time"></i>
                </div>
                <div class="quick-action-info">
                    <span class="quick-action-title">Horarios</span>
                    <span class="quick-action-hint">Aulas & Profesores</span>
                </div>
            </a>

            <a href="{{ route('reportes_indisciplina.index_view') }}" class="quick-action-card quick-action-danger">
                <div class="quick-icon-box icon-box-danger">
                    <i class="ti ti-alert-octagon"></i>
                </div>
                <div class="quick-action-info">
                    <span class="quick-action-title">Indisciplina</span>
                    <span class="quick-action-hint">Reportes & Citatorios</span>
                </div>
            </a>
        </div>
    </div>

    {{-- 5. SECCIÓN INFERIOR: "ÚLTIMAS INCIDENCIAS" Y "OFERTA EDUCATIVA" --}}
    <div class="bottom-grid">
        {{-- Columna 1: Reportes Recientes de Indisciplina --}}
        <div class="chart-glass-panel">
            <div class="panel-header d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="panel-title mb-1">Últimas Incidencias de Indisciplina</h5>
                    <p class="panel-subtitle mb-0">Seguimiento de conducta de los alumnos</p>
                </div>
                <a href="{{ route('reportes_indisciplina.index_view') }}" class="btn-ver-todas">
                    Ver todas <i class="ti ti-chevron-right ms-1"></i>
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
                                    <div class="spinner-border spinner-border-sm text-secondary me-2" role="status"></div>
                                    <span style="font-size: 0.8125rem;">Cargando incidentes...</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Columna 2: Centros de Trabajo / Planteles Oficiales --}}
        <div class="chart-glass-panel">
            <div class="panel-header d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="panel-title mb-1">Oferta Educativa y CCTs</h5>
                    <p class="panel-subtitle mb-0">Centros de Trabajo incorporados</p>
                </div>
            </div>
            <div class="panel-body p-0">
                <div class="cct-cards-list">
                    {{-- CCT 1: BTI --}}
                    <div class="cct-item-card">
                        <div class="cct-icon-box">
                            <i class="ti ti-school"></i>
                        </div>
                        <div class="cct-info flex-grow-1">
                            <h6 class="cct-name mb-0">Bachillerato Tecnológico Interamericano</h6>
                            <span class="cct-clave"><i class="ti ti-fingerprint me-1"></i>CCT: <strong>21PCT0073R</strong></span>
                        </div>
                        <span class="badge-modalidad badge-modalidad-green">
                            Escolarizado
                        </span>
                    </div>

                    {{-- CCT 2: BGNE --}}
                    <div class="cct-item-card">
                        <div class="cct-icon-box">
                            <i class="ti ti-book"></i>
                        </div>
                        <div class="cct-info flex-grow-1">
                            <h6 class="cct-name mb-0">Bachillerato General No Escolarizado</h6>
                            <span class="cct-clave"><i class="ti ti-fingerprint me-1"></i>CCT: <strong>21PBH0353G</strong></span>
                        </div>
                        <span class="badge-modalidad badge-modalidad-teal">
                            Fines de Semana
                        </span>
                    </div>

                    {{-- CCT 3: Computación --}}
                    <div class="cct-item-card">
                        <div class="cct-icon-box">
                            <i class="ti ti-cpu"></i>
                        </div>
                        <div class="cct-info flex-grow-1">
                            <h6 class="cct-name mb-0">Informática y Computación</h6>
                            <span class="cct-clave"><i class="ti ti-fingerprint me-1"></i>CCT: <strong>21PBT0322I</strong></span>
                        </div>
                        <span class="badge-modalidad badge-modalidad-slate">
                            Técnico
                        </span>
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
   DASHBOARD ESTILOS PROFESIONALES BTI (SHADCN/UI & LINEAR STYLE)
   ========================================================= */
:root {
    --brand-primary: #1E5B66;
    --brand-dark: #16464F;
    --brand-light: #F0F7F8;
    --brand-border: #CBE4E8;
    --bg-page: #F8FAFC;
    --bg-surface: #FFFFFF;
    --border-subtle: #E2E8F0;
    --text-primary: #0F172A;
    --text-secondary: #475569;
    --text-muted: #64748B;
    --success-bg: #DCFCE7;
    --success-text: #15803D;
    --success-border: #BBF7D0;
    --danger-bg: #FEF2F2;
    --danger-text: #DC2626;
    --danger-border: #FECACA;
}

.dashboard-wrapper {
    width: 100%;
    color: var(--text-primary);
    padding-bottom: 30px;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

/* 1. EXECUTIVE HEADER */
.executive-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border-subtle);
    gap: 16px;
    flex-wrap: wrap;
}

.header-left {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.page-title {
    font-size: 1.35rem;
    font-weight: 700;
    color: var(--text-primary);
    letter-spacing: -0.02em;
    margin: 0;
    line-height: 1.2;
}

.system-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 2px 8px;
    background: var(--success-bg);
    color: var(--success-text);
    font-size: 0.7rem;
    font-weight: 600;
    border-radius: 999px;
    border: 1px solid var(--success-border);
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background-color: var(--success-text);
    display: inline-block;
    animation: pulseDot 2s infinite ease-in-out;
}

@keyframes pulseDot {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.3); opacity: 0.5; }
}

.page-subtitle {
    font-size: 0.85rem;
    color: var(--text-muted);
    margin: 0;
}

.greeting-highlight {
    font-weight: 600;
    color: var(--text-secondary);
}

.header-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.date-chip {
    background: #FFFFFF;
    border: 1px solid var(--border-subtle);
    color: var(--text-secondary);
    height: 36px;
    padding: 0 12px;
    border-radius: 6px;
    font-size: 0.8125rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
}

.date-chip i {
    color: var(--brand-primary);
    font-size: 1rem;
}

.btn-refresh-stats {
    background: var(--brand-primary);
    color: #FFFFFF;
    height: 36px;
    padding: 0 14px;
    border-radius: 6px;
    font-size: 0.8125rem;
    font-weight: 500;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: background 0.15s ease;
}

.btn-refresh-stats:hover {
    background: var(--brand-dark);
}

.rotating-icon {
    animation: spin 0.8s linear infinite;
    display: inline-block;
}

@keyframes spin {
    100% { transform: rotate(360deg); }
}

/* 2. KPIS GRID */
.kpis-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}

@media (max-width: 1100px) {
    .kpis-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 600px) {
    .kpis-grid {
        grid-template-columns: 1fr;
    }
}

.kpi-card {
    background: #FFFFFF;
    border: 1px solid var(--border-subtle);
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.15s ease;
}

.kpi-card:hover {
    transform: translateY(-2px);
    border-color: #CBD5E1;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
}

.kpi-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.kpi-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.kpi-icon-box {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: #F8FAFC;
    border: 1px solid var(--border-subtle);
    color: var(--brand-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.kpi-value {
    font-family: 'JetBrains Mono', monospace;
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 8px 0 12px 0;
    line-height: 1;
}

.kpi-footer {
    border-top: 1px solid #F1F5F9;
    padding-top: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.75rem;
    color: var(--text-muted);
    gap: 6px;
    flex-wrap: wrap;
}

.badge-kpi-sub {
    background: var(--brand-light);
    color: var(--brand-primary);
    font-weight: 600;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.72rem;
}

.kpi-secondary-text {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.kpi-skeleton {
    display: inline-block;
    width: 60px;
    height: 28px;
    background: #E2E8F0;
    border-radius: 6px;
    animation: skeletonPulse 1.5s infinite ease-in-out;
}

@keyframes skeletonPulse {
    0%, 100% { opacity: 0.6; }
    50% { opacity: 1; }
}

/* 3. CHARTS GRID */
.charts-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
    margin-bottom: 24px;
}

@media (max-width: 992px) {
    .charts-grid {
        grid-template-columns: 1fr;
    }
}

.chart-glass-panel {
    background: #FFFFFF;
    border-radius: 12px;
    border: 1px solid var(--border-subtle);
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    padding: 20px;
}

.panel-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--text-primary);
    letter-spacing: -0.01em;
}

.panel-subtitle {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.badge-ciclo-actual {
    background: var(--brand-light);
    color: var(--brand-primary);
    border: 1px solid var(--brand-border);
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 2px 10px;
}

.chart-empty-state {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

/* Donut Center Metric */
.donut-center-metric {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    pointer-events: none;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.donut-center-value {
    font-family: 'JetBrains Mono', monospace;
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1;
}

.donut-center-label {
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-top: 2px;
}

/* Donut Legend */
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
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
    margin-bottom: 2px;
}

.dot-active { background-color: var(--brand-primary); }
.dot-certified { background-color: #10B981; }
.dot-inactive { background-color: #CBD5E1; }

.donut-label {
    font-size: 0.72rem;
    color: var(--text-muted);
    font-weight: 500;
}

.donut-val {
    font-family: 'JetBrains Mono', monospace;
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-primary);
}

/* 4. ACTION TILES (ACCESOS RÁPIDOS) */
.section-heading {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--text-primary);
}

.section-subtitle {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 12px;
    margin-bottom: 24px;
}

@media (max-width: 1200px) {
    .quick-actions-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 640px) {
    .quick-actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.quick-action-card {
    background: #FFFFFF;
    border: 1px solid var(--border-subtle);
    border-radius: 10px;
    padding: 14px;
    display: flex;
    align-items: center;
    gap: 12px;
    text-align: left;
    transition: all 0.15s ease;
    text-decoration: none;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.02);
}

.quick-action-card:hover {
    border-color: var(--brand-primary);
    background: #F8FAFC;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.05);
}

.quick-icon-box {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    background: var(--brand-light);
    color: var(--brand-primary);
    border: 1px solid var(--brand-border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
    transition: all 0.15s ease;
}

.icon-box-danger {
    background: var(--danger-bg);
    color: var(--danger-text);
    border-color: var(--danger-border);
}

.quick-action-card:hover .quick-icon-box {
    transform: scale(1.05);
}

.quick-action-info {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.quick-action-title {
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--text-primary);
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.quick-action-hint {
    font-size: 0.7rem;
    color: var(--text-muted);
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* 5. BOTTOM GRID: INCIDENCIAS & CCTs */
.bottom-grid {
    display: grid;
    grid-template-columns: 1.4fr 1fr;
    gap: 20px;
    margin-bottom: 24px;
}

@media (max-width: 992px) {
    .bottom-grid {
        grid-template-columns: 1fr;
    }
}

.btn-ver-todas {
    background: #FFFFFF;
    border: 1px solid var(--border-subtle);
    color: var(--text-secondary);
    height: 30px;
    padding: 0 10px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    text-decoration: none;
    transition: all 0.15s ease;
}

.btn-ver-todas:hover {
    background: #F1F5F9;
    color: var(--text-primary);
    border-color: #CBD5E1;
}

.table-incidencias {
    font-size: 0.8125rem;
}

.table-incidencias thead th {
    background: #F8FAFC;
    color: var(--text-secondary);
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    border-bottom: 1px solid var(--border-subtle);
    padding: 10px 14px;
}

.table-incidencias tbody td {
    padding: 10px 14px;
    vertical-align: middle;
    border-bottom: 1px solid #F1F5F9;
}

.badge-folio {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.7rem;
    font-weight: 600;
    background: #F1F5F9;
    color: #334155;
    border: 1px solid var(--border-subtle);
    padding: 2px 6px;
    border-radius: 4px;
}

.badge-parcial {
    background: #FFFBEB;
    color: #B45309;
    border: 1px solid #FDE68A;
    font-weight: 600;
    font-size: 0.7rem;
    padding: 2px 8px;
    border-radius: 999px;
}

/* CCT ITEMS */
.cct-cards-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.cct-item-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    border-radius: 10px;
    background: #FFFFFF;
    border: 1px solid var(--border-subtle);
    transition: all 0.15s ease;
}

.cct-item-card:hover {
    border-color: #CBD5E1;
    background: #F8FAFC;
}

.cct-icon-box {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    background: #F8FAFC;
    border: 1px solid var(--border-subtle);
    color: var(--brand-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.cct-name {
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--text-primary);
}

.cct-clave {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.72rem;
    color: var(--text-muted);
}

.badge-modalidad {
    font-size: 0.6875rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 999px;
    white-space: nowrap;
}

.badge-modalidad-green {
    background: var(--success-bg);
    color: var(--success-text);
    border: 1px solid var(--success-border);
}

.badge-modalidad-teal {
    background: var(--brand-light);
    color: var(--brand-primary);
    border: 1px solid var(--brand-border);
}

.badge-modalidad-slate {
    background: #F1F5F9;
    color: var(--text-secondary);
    border: 1px solid var(--border-subtle);
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

    chartGruposInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Alumnos Asignados',
                data: dataVals,
                backgroundColor: '#1E5B66',
                hoverBackgroundColor: '#16464F',
                borderWidth: 0,
                borderRadius: 6,
                borderSkipped: false,
                barPercentage: 0.55,
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
                    backgroundColor: 'rgba(15, 23, 42, 0.95)',
                    titleFont: { family: "'JetBrains Mono', monospace", size: 12, weight: 'bold' },
                    bodyFont: { family: "'Inter', sans-serif", size: 12 },
                    padding: 10,
                    cornerRadius: 6,
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
                        color: '#64748B',
                        font: { family: "'JetBrains Mono', monospace", size: 11 }
                    },
                    grid: {
                        color: '#F1F5F9'
                    },
                    border: {
                        dash: [3, 3]
                    }
                },
                x: {
                    ticks: {
                        color: '#475569',
                        font: { family: "'JetBrains Mono', monospace", size: 11, weight: '600' }
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

    // Actualizar métrica central de la dona
    const centerTotalEl = document.getElementById('donut-center-total');
    if (centerTotalEl) {
        centerTotalEl.textContent = (estatus.activos || 0).toLocaleString('es-MX');
    }

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
                    '#1E5B66', // Brand Primary
                    '#10B981', // Emerald Success
                    '#CBD5E1'  // Slate Muted
                ],
                hoverBackgroundColor: [
                    '#16464F',
                    '#059669',
                    '#94A3B8'
                ],
                borderWidth: 2,
                borderColor: '#FFFFFF',
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
                    backgroundColor: 'rgba(15, 23, 42, 0.95)',
                    titleFont: { family: "'Inter', sans-serif", size: 12, weight: 'bold' },
                    bodyFont: { family: "'JetBrains Mono', monospace", size: 12 },
                    padding: 10,
                    cornerRadius: 6,
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
                    <i class="ti ti-circle-check text-success" style="font-size: 24px; display: block; margin-bottom: 4px;"></i>
                    <span style="font-size: 0.8125rem;">No se registran incidencias recientes de indisciplina.</span>
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
                    <div style="text-transform: uppercase; font-weight: 600; font-size: 0.75rem; color: #0F172A;">${r.alumno_nombre || 'Alumno'}</div>
                    <small style="font-size: 0.7rem; color: #64748B;">${r.tutor_nombre ? 'Tutor: ' + r.tutor_nombre : 'Sin tutor reg.'}</small>
                </td>
                <td style="max-width: 200px; font-size: 0.75rem; color: #334155; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${r.incidente}">
                    ${r.incidente}
                </td>
                <td style="font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; color: #475569; white-space: nowrap;">${r.fecha || '--'}</td>
                <td class="text-center">
                    <span class="badge-parcial">
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
