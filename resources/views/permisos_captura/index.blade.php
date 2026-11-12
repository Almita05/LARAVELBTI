@extends('layouts.app')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* ==========================================================================
   PORTAL DE PERMISOS DE CAPTURA - DESIGN SYSTEM (Linear / shadcn/ui)
   ========================================================================== */

:root {
    --brand-primary: #1E5B66;
    --brand-primary-hover: #184A54;
    --brand-primary-subtle: #F0F7F8;
    --border-subtle: #E2E8F0;
    --bg-canvas: #F8FAFC;
    --text-primary: #0F172A;
    --text-secondary: #475569;
    --text-muted: #64748B;
}

/* Page Header */
.permisos-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 16px;
}
.permisos-page-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #0F172A;
    margin: 0 0 4px 0;
    letter-spacing: -0.01em;
}
.permisos-page-subtitle {
    font-size: 0.875rem;
    color: #64748B;
    margin: 0;
}

/* Primary Button SaaS */
.btn-primary-saas {
    background: #1E5B66 !important;
    color: #FFFFFF !important;
    height: 36px;
    padding: 0 16px;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: none !important;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.15s ease, box-shadow 0.15s ease;
}
.btn-primary-saas:hover {
    background: #184A54 !important;
    color: #FFFFFF !important;
    box-shadow: 0 2px 6px rgba(30, 91, 102, 0.25);
}
.btn-primary-saas i {
    font-size: 16px;
    line-height: 1;
}

/* Segmented Tabs Control */
.segmented-tabs-wrapper {
    display: inline-flex;
    background: #F1F5F9;
    padding: 4px;
    border-radius: 8px;
    border: 1px solid #E2E8F0;
    margin-bottom: 20px;
    gap: 4px;
    max-width: 100%;
    overflow-x: auto;
}
.segmented-tab {
    height: 34px;
    padding: 0 14px;
    border-radius: 6px;
    font-size: 0.8125rem;
    font-weight: 500;
    color: #475569;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: none;
    background: transparent;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.15s ease;
}
.segmented-tab:hover:not(.active) {
    background: rgba(255, 255, 255, 0.6);
    color: #0F172A;
}
.segmented-tab.active {
    background: #FFFFFF !important;
    color: #1E5B66 !important;
    font-weight: 600 !important;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06) !important;
}
.segmented-tab i {
    font-size: 16px;
    line-height: 1;
}

/* Unified Card Container */
.card-unified {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    overflow: hidden;
}

/* Filters Header Bar */
.filters-header-bar {
    display: grid;
    grid-template-columns: 1fr 260px;
    gap: 12px;
    padding: 16px 20px;
    border-bottom: 1px solid #E2E8F0;
    background: #FFFFFF;
    align-items: center;
}
@media (max-width: 768px) {
    .filters-header-bar {
        grid-template-columns: 1fr;
    }
}
.filter-search-wrapper {
    position: relative;
    width: 100%;
}
.filter-search-wrapper .search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94A3B8;
    font-size: 16px;
    pointer-events: none;
    line-height: 1;
}
.filter-input-search {
    height: 38px;
    padding: 0 12px 0 36px;
    border: 1px solid #E2E8F0;
    border-radius: 6px;
    font-size: 0.875rem;
    background: #F8FAFC;
    width: 100%;
    color: #0F172A;
    transition: all 0.15s ease;
}
.filter-input-search:focus {
    background: #FFFFFF;
    border-color: #1E5B66;
    outline: none;
    box-shadow: 0 0 0 3px rgba(30, 91, 102, 0.12);
}
.filter-select {
    height: 38px;
    padding: 0 32px 0 12px;
    border: 1px solid #E2E8F0;
    border-radius: 6px;
    font-size: 0.875rem;
    color: #0F172A;
    background-color: #FFFFFF;
    width: 100%;
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23475569' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 14px 10px;
    cursor: pointer;
    transition: all 0.15s ease;
}
.filter-select:focus {
    border-color: #1E5B66;
    outline: none;
    box-shadow: 0 0 0 3px rgba(30, 91, 102, 0.12);
}

/* Glass Table Base */
.glass-table {
    width: 100%;
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
}
.glass-table th {
    background: #F8FAFC !important;
    color: #475569 !important;
    font-size: 0.75rem !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.04em !important;
    padding: 12px 16px !important;
    border-bottom: 1px solid #E2E8F0 !important;
    border-top: none !important;
    white-space: nowrap !important;
}
.glass-table td {
    padding: 12px 16px !important;
    border-bottom: 1px solid #F1F5F9 !important;
    border-top: none !important;
    vertical-align: middle !important;
    font-size: 0.8125rem !important;
    color: #334155;
    background: #FFFFFF;
}
.glass-table tr:hover td {
    background-color: #F8FAFC;
}
.glass-table tr:last-child td {
    border-bottom: none !important;
}
.table-danger-light td {
    background-color: #FEF2F2 !important;
}
.table-danger-light:hover td {
    background-color: #FEE2E2 !important;
}

/* Column Typography Tokens */
.id-mono {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.75rem;
    color: #64748B;
    font-weight: 500;
}
.docente-nombre-matriz {
    font-weight: 600;
    color: #0F172A;
    text-transform: uppercase;
    font-size: 0.8125rem;
    letter-spacing: 0.01em;
    display: block;
}
.badge-grupo-mono {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.75rem;
    font-weight: 600;
    background: #F1F5F9;
    color: #334155;
    border: 1px solid #E2E8F0;
    padding: 2px 8px;
    border-radius: 6px;
    white-space: nowrap;
    display: inline-block;
}
.materia-nombre-matriz {
    font-weight: 600;
    color: #0F172A;
    font-size: 0.8125rem;
    line-height: 1.3;
}
.materia-clave-mono {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.75rem;
    color: #64748B;
    margin-top: 2px;
}
.progress-text-row {
    font-size: 0.75rem;
    color: #475569;
    margin-bottom: 6px;
    display: flex;
    justify-content: space-between;
    font-family: 'JetBrains Mono', monospace;
    font-weight: 500;
}
.progress-track-saas {
    height: 6px;
    width: 100%;
    background: #F1F5F9;
    border-radius: 999px;
    overflow: hidden;
}
.progress-fill-saas {
    height: 100%;
    border-radius: 999px;
    transition: width 0.4s ease;
}
.fecha-limite-cell {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.8125rem;
    color: #334155;
    font-weight: 500;
}
.fecha-limite-cell i {
    color: #64748B;
    font-size: 15px;
    line-height: 1;
}

/* Soft Badges */
.badge-matriz-soft {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    height: 24px;
    padding: 0 10px;
    border-radius: 9999px;
    font-size: 0.72rem;
    font-weight: 600;
    white-space: nowrap;
    border: 1px solid transparent;
    line-height: 1;
}
.badge-matriz-soft i {
    font-size: 13px;
    line-height: 1;
}
.badge-soft-warning {
    background: #FFFBEB !important;
    color: #B45309 !important;
    border-color: #FDE68A !important;
}
.badge-soft-success {
    background: #DCFCE7 !important;
    color: #15803D !important;
    border-color: #BBF7D0 !important;
}
.badge-soft-info {
    background: #E0F2FE !important;
    color: #0369A1 !important;
    border-color: #BAE6FD !important;
}
.badge-soft-danger {
    background: #FEE2E2 !important;
    color: #B91C1C !important;
    border-color: #FECACA !important;
}
.badge-soft-neutral {
    background: #F1F5F9 !important;
    color: #475569 !important;
    border-color: #E2E8F0 !important;
}

/* Direct Action Buttons */
.actions-cell-matriz {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
    white-space: nowrap;
}
.btn-action-prorroga {
    height: 32px;
    padding: 0 10px;
    border-radius: 6px;
    background: #F0F7F8;
    border: 1px solid #CBE4E8;
    color: #1E5B66;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    white-space: nowrap;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.15s ease;
}
.btn-action-prorroga i {
    font-size: 15px;
    line-height: 1;
}
.btn-action-prorroga:hover {
    background: #1E5B66;
    border-color: #1E5B66;
    color: #FFFFFF;
}
.btn-action-notas {
    height: 32px;
    padding: 0 10px;
    border-radius: 6px;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    color: #334155;
    font-size: 0.75rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    white-space: nowrap;
    cursor: pointer;
    text-decoration: none;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
    transition: all 0.15s ease;
}
.btn-action-notas i {
    font-size: 15px;
    line-height: 1;
    color: #64748B;
}
.btn-action-notas:hover {
    background: #F8FAFC;
    border-color: #CBD5E1;
    color: #0F172A;
}

/* Ghost Buttons (Historial de Prórrogas) */
.btn-ghost-icon {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    background: transparent !important;
    border: none !important;
    color: #64748B !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s ease;
    padding: 0;
    text-decoration: none;
}
.btn-ghost-icon:hover {
    background: #F1F5F9 !important;
    color: #0F172A !important;
}
.btn-ghost-danger:hover {
    background: #FEF2F2 !important;
    color: #DC2626 !important;
}
.btn-ghost-icon i {
    font-size: 16px !important;
    line-height: 1 !important;
    color: currentColor !important;
}

/* Modals & Switch Forms */
.form-select-premium, .form-control-premium {
    border-radius: 6px;
    border: 1px solid #E2E8F0;
    padding: 8px 12px;
    font-size: 0.875rem;
    transition: all 0.15s ease;
}
.form-select-premium:focus, .form-control-premium:focus {
    border-color: #1E5B66;
    box-shadow: 0 0 0 3px rgba(30, 91, 102, 0.12);
    outline: none;
}
.switch-premium {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 22px;
}
.switch-premium input {
    opacity: 0;
    width: 0;
    height: 0;
}
.slider-premium {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #CBD5E1;
    transition: .25s ease;
    border-radius: 22px;
}
.slider-premium:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .25s ease;
    border-radius: 50%;
}
input:checked + .slider-premium {
    background-color: #1E5B66;
}
input:checked + .slider-premium:before {
    transform: translateX(22px);
}
</style>

<div class="page-container">

    {{-- Encabezado Principal --}}
    <div class="permisos-page-header">
        <div>
            <h1 class="permisos-page-title">Portal de Permisos de Captura</h1>
            <p class="permisos-page-subtitle">Administra los tiempos de captura, monitorea el avance de calificaciones y asigna prórrogas especiales.</p>
        </div>

        <button type="button" class="btn btn-primary-saas" onclick="abrirModalAlta()">
            <i class="ti ti-plus"></i>
            <span>Asignar Prórroga Especial</span>
        </button>
    </div>

    {{-- Sistema de Pestañas (Segmented Tabs) --}}
    <div class="segmented-tabs-wrapper" id="pills-tab" role="tablist">
        <button class="segmented-tab active" id="tab-matriz-btn" data-bs-toggle="pill" data-bs-target="#tab-matriz" type="button" role="tab" aria-controls="tab-matriz" aria-selected="true" onclick="cargarMatriz()">
            <i class="ti ti-chart-bar"></i>
            <span>Matriz de Avance (Monitoreo)</span>
        </button>
        <button class="segmented-tab" id="tab-prorrogas-btn" data-bs-toggle="pill" data-bs-target="#tab-prorrogas" type="button" role="tab" aria-controls="tab-prorrogas" aria-selected="false" onclick="cargarPermisos()">
            <i class="ti ti-history"></i>
            <span>Historial de Prórrogas</span>
        </button>
        <button class="segmented-tab" id="tab-cct-btn" data-bs-toggle="pill" data-bs-target="#tab-cct" type="button" role="tab" aria-controls="tab-cct" aria-selected="false" onclick="cargarCctConfigList()">
            <i class="ti ti-building-community"></i>
            <span>Ajustes de Cierre por Plantel (CCT)</span>
        </button>
    </div>

    {{-- Contenido de las Pestañas --}}
    <div class="tab-content" id="pills-tabContent">
        
        {{-- PESTAÑA 1: MATRIZ DE AVANCE --}}
        <div class="tab-pane fade show active" id="tab-matriz" role="tabpanel" aria-labelledby="tab-matriz-btn">
            
            <div class="card-unified">
                {{-- Header de Filtros (Buscador + Select de Estado) --}}
                <div class="filters-header-bar">
                    <div class="filter-search-wrapper">
                        <i class="ti ti-search search-icon"></i>
                        <input type="text" id="buscadorMatriz" class="filter-input-search" placeholder="Buscar en la matriz por docente, materia o grupo..." oninput="filtrarTablaMatriz()">
                    </div>
                    <div class="filter-select-wrapper">
                        <select id="filtroEstadoMatriz" class="filter-select" onchange="filtrarTablaMatriz()">
                            <option value="">Todos los Estados</option>
                            <option value="completo">Completo</option>
                            <option value="pendiente">Pendiente (En fecha)</option>
                            <option value="prorroga">Con Prórroga Activa</option>
                            <option value="expirado">Expirado / Atrasado</option>
                            <option value="bloqueado_pasado">Bloqueado (Periodo pasado)</option>
                        </select>
                    </div>
                </div>

                {{-- Spinner de carga matriz --}}
                <div id="spinnerMatriz" class="text-center py-5">
                    <div class="spinner-border" style="width: 2.5rem; height: 2.5rem; color: #1E5B66;" role="status"></div>
                    <p class="text-muted mt-2 fw-semibold mb-0" style="font-size: 0.875rem;">Calculando matriz de avance de calificaciones...</p>
                </div>

                {{-- Tabla Matriz --}}
                <div id="wrapperMatriz" style="display: none;">
                    <div class="table-responsive">
                        <table class="table glass-table align-middle mb-0" id="tablaMatriz">
                            <thead>
                                <tr>
                                    <th style="width: 45px;">#</th>
                                    <th>Docente</th>
                                    <th>Grupo</th>
                                    <th>Materia Impartida</th>
                                    <th style="min-width: 150px;">Avance de Captura</th>
                                    <th>Fecha Límite</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-end" style="width: 260px; padding-right: 20px;">Acción Directa</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyMatriz">
                                {{-- Llenado por JS --}}
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Vacío Matriz --}}
                <div id="emptyMatriz" class="text-center p-5" style="display: none;">
                    <i class="ti ti-users-minus text-muted" style="font-size: 3rem; display: inline-block; margin-bottom: 12px; color: #94A3B8;"></i>
                    <h5 class="mt-2 mb-1" style="font-size: 1rem; font-weight: 600; color: #334155;">No se encontraron registros en la carga académica</h5>
                    <p class="text-muted small mb-0">Verifica que existan horarios asignados en el sistema.</p>
                </div>
            </div>

        </div>

        {{-- PESTAÑA 2: HISTORIAL DE PRÓRROGAS --}}
        <div class="tab-pane fade" id="tab-prorrogas" role="tabpanel" aria-labelledby="tab-prorrogas-btn">
            
            <div class="card-unified">
                {{-- Header de Filtros (Buscador + Select de Estado) --}}
                <div class="filters-header-bar">
                    <div class="filter-search-wrapper">
                        <i class="ti ti-search search-icon"></i>
                        <input type="text" id="buscadorPermisos" class="filter-input-search" placeholder="Buscar prórrogas por docente, materia o grupo..." oninput="filtrarTablaPermisos()">
                    </div>
                    <div class="filter-select-wrapper">
                        <select id="filtroEstado" class="filter-select" onchange="filtrarTablaPermisos()">
                            <option value="">Todos los Estados</option>
                            <option value="activo">Solo Activos</option>
                            <option value="inactivo">Solo Inactivos</option>
                        </select>
                    </div>
                </div>

                {{-- Spinner de carga permisos --}}
                <div id="spinnerCarga" class="text-center py-5" style="display: none;">
                    <div class="spinner-border" style="width: 2.5rem; height: 2.5rem; color: #1E5B66;" role="status"></div>
                    <p class="text-muted mt-2 fw-semibold mb-0" style="font-size: 0.875rem;">Cargando listado de prórrogas...</p>
                </div>

                {{-- Tabla Prórrogas --}}
                <div id="tablaWrapper" style="display: none;">
                    <div class="table-responsive">
                        <table class="table glass-table align-middle mb-0" id="tablaPermisos">
                            <thead>
                                <tr>
                                    <th style="width: 45px;">#</th>
                                    <th>Docente</th>
                                    <th>Grupo</th>
                                    <th>Asignatura</th>
                                    <th>Fecha Límite</th>
                                    <th class="text-center">Modificar Historial</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-end" style="width: 140px; padding-right: 20px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyPermisos">
                                {{-- Llenado por JS --}}
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Vacío Prórrogas --}}
                <div id="emptyState" class="text-center p-5" style="display: none;">
                    <i class="ti ti-lock-open text-muted" style="font-size: 3rem; display: inline-block; margin-bottom: 12px; color: #94A3B8;"></i>
                    <h5 class="mt-2 mb-1" style="font-size: 1rem; font-weight: 600; color: #334155;">No se encontraron prórrogas especiales</h5>
                    <p class="text-muted small mb-0">Todos los docentes operan bajo los tiempos ordinarios de captura.</p>
                </div>
            </div>

        </div>

        {{-- PESTAÑA 3: CONFIGURACIÓN POR GRUPO --}}
        <div class="tab-pane fade" id="tab-cct" role="tabpanel" aria-labelledby="tab-cct-btn">
            <div class="glass-card p-4 mb-4 shadow-sm bg-white border">
                <h5 class="fw-bold text-dark mb-3"><i class="ti ti-building-community me-2" style="color: #1E5B66;"></i> Seleccionar Plantel y Grupo</h5>
                <div class="row g-3">
                    <div class="col-12 col-md-6 col-lg-4">
                        <label class="form-label small fw-bold text-muted">1. Plantel (CCT):</label>
                        <select id="selectCctConfig" class="form-select form-select-premium" onchange="cargarGruposConfig(this.value)">
                            <option value="">Seleccione un plantel...</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label class="form-label small fw-bold text-muted">2. Grupo:</label>
                        <select id="selectGrupoConfig" class="form-select form-select-premium" onchange="cargarConfiguracionGrupo(this.value)" disabled>
                            <option value="">(Primero selecciona un plantel)</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Card de Ajustes --}}
            <div id="cardAjustesGrupo" class="glass-card p-4 shadow-sm bg-white border" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <div>
                        <h4 class="fw-bold text-dark mb-1" id="lblGrupoNombreTitle">Ajustes de Captura - Grupo</h4>
                        <p class="text-muted mb-0 small">Establece la habilitación y fechas límite de captura de calificaciones para los docentes de este grupo.</p>
                    </div>
                </div>

                <form id="formGrupoConfig">
                    {{-- HABILITAR GENERAL --}}
                    <div class="mb-4 p-3 rounded border bg-light d-flex align-items-center justify-content-between" style="max-width: 700px;">
                        <div>
                            <label class="fw-bold text-dark mb-0 d-block" style="font-size: 1.02rem;"><i class="ti ti-power me-2" style="color: #1E5B66;"></i> Habilitar Captura de Calificaciones en este Grupo</label>
                            <small class="text-muted">Si se desactiva, los docentes no podrán capturar calificaciones en ninguna asignatura de este grupo.</small>
                        </div>
                        <label class="switch-premium mb-0">
                            <input type="checkbox" id="checkGrupoHabilitada" onchange="toggleInputsGrupo(this.checked)">
                            <span class="slider-premium"></span>
                        </label>
                    </div>

                    {{-- DROPDOWN SEMESTRE HABILITADO --}}
                    <div class="mb-4 p-3 rounded border bg-light" style="max-width: 700px;" id="divGrupoSemestreHabilitado">
                        <label class="form-label small fw-bold text-muted d-block"><i class="ti ti-school me-2" style="color: #1E5B66;"></i> Semestre Habilitado para Captura:</label>
                        <select id="selectGrupoNivelCaptura" class="form-select form-select-premium" required>
                            <option value="">Seleccione un semestre...</option>
                        </select>
                        <small class="text-muted d-block mt-1">Los docentes solo podrán capturar calificaciones para asignaturas correspondientes a este semestre/nivel académico.</small>
                    </div>

                    <h5 class="fw-bold text-dark mt-4 mb-3" id="lblPeriodosEvaluacionHeader"><i class="ti ti-calendar-event me-2" style="color: #475569;"></i> Rango de Fechas por Periodo de Evaluación</h5>
                    <div class="row g-4" style="max-width: 700px;">
                        
                        {{-- Parcial 1 --}}
                        <div class="col-12 p-3 rounded border bg-light card-periodo-ajuste" id="cardAjusteP1">
                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                <span class="fw-bold text-dark fs-6" id="lblGrupoP1Title"><i class="ti ti-checkbox text-muted me-2"></i> 1er. Parcial</span>
                                <label class="switch-premium mb-0">
                                    <input type="checkbox" id="checkGrupoP1" class="switch-grupo-sub" onchange="toggleFilaFecha('p1', this.checked)">
                                    <span class="slider-premium"></span>
                                </label>
                            </div>
                            <div class="row g-2 div-fechas-periodo" id="divFechasP1">
                                <div class="col-6">
                                    <label class="form-label small text-muted">Fecha de Inicio:</label>
                                    <input type="date" id="inputGrupoP1Inicio" class="form-control form-control-sm">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small text-muted">Fecha de Fin (Límite):</label>
                                    <input type="date" id="inputGrupoP1Fin" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>

                        {{-- Parcial 2 --}}
                        <div class="col-12 p-3 rounded border bg-light card-periodo-ajuste" id="cardAjusteP2">
                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                <span class="fw-bold text-dark fs-6"><i class="ti ti-checkbox text-muted me-2"></i> 2do. Parcial</span>
                                <label class="switch-premium mb-0">
                                    <input type="checkbox" id="checkGrupoP2" class="switch-grupo-sub" onchange="toggleFilaFecha('p2', this.checked)">
                                    <span class="slider-premium"></span>
                                </label>
                            </div>
                            <div class="row g-2 div-fechas-periodo" id="divFechasP2">
                                <div class="col-6">
                                    <label class="form-label small text-muted">Fecha de Inicio:</label>
                                    <input type="date" id="inputGrupoP2Inicio" class="form-control form-control-sm">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small text-muted">Fecha de Fin (Límite):</label>
                                    <input type="date" id="inputGrupoP2Fin" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>

                        {{-- Parcial 3 --}}
                        <div class="col-12 p-3 rounded border bg-light card-periodo-ajuste" id="cardAjusteP3">
                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                <span class="fw-bold text-dark fs-6"><i class="ti ti-checkbox text-muted me-2"></i> 3er. Parcial</span>
                                <label class="switch-premium mb-0">
                                    <input type="checkbox" id="checkGrupoP3" class="switch-grupo-sub" onchange="toggleFilaFecha('p3', this.checked)">
                                    <span class="slider-premium"></span>
                                </label>
                            </div>
                            <div class="row g-2 div-fechas-periodo" id="divFechasP3">
                                <div class="col-6">
                                    <label class="form-label small text-muted">Fecha de Inicio:</label>
                                    <input type="date" id="inputGrupoP3Inicio" class="form-control form-control-sm">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small text-muted">Fecha de Fin (Límite):</label>
                                    <input type="date" id="inputGrupoP3Fin" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>

                        {{-- Semestral --}}
                        <div class="col-12 p-3 rounded border bg-light card-periodo-ajuste" id="cardAjusteSemestral">
                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                <span class="fw-bold text-dark fs-6"><i class="ti ti-file-certificate text-muted me-2"></i> Examen Semestral / Final</span>
                                <label class="switch-premium mb-0">
                                    <input type="checkbox" id="checkGrupoSemestral" class="switch-grupo-sub" onchange="toggleFilaFecha('semestral', this.checked)">
                                    <span class="slider-premium"></span>
                                </label>
                            </div>
                            <div class="row g-2 div-fechas-periodo" id="divFechasSemestral">
                                <div class="col-6">
                                    <label class="form-label small text-muted">Fecha de Inicio:</label>
                                    <input type="date" id="inputGrupoSemestralInicio" class="form-control form-control-sm">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small text-muted">Fecha de Fin (Límite):</label>
                                    <input type="date" id="inputGrupoSemestralFin" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>

                        {{-- Extraordinario --}}
                        <div class="col-12 p-3 rounded border bg-light card-periodo-ajuste">
                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                <span class="fw-bold text-dark fs-6"><i class="ti ti-bolt text-muted me-2"></i> Examen Extraordinario</span>
                                <label class="switch-premium mb-0">
                                    <input type="checkbox" id="checkGrupoExtraordinario" class="switch-grupo-sub" onchange="toggleFilaFecha('extraordinario', this.checked)">
                                    <span class="slider-premium"></span>
                                </label>
                            </div>
                            <div class="row g-2 div-fechas-periodo" id="divFechasExtraordinario">
                                <div class="col-6">
                                    <label class="form-label small text-muted">Fecha de Inicio:</label>
                                    <input type="date" id="inputGrupoExtraordinarioInicio" class="form-control form-control-sm">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small text-muted">Fecha de Fin (Límite):</label>
                                    <input type="date" id="inputGrupoExtraordinarioFin" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="mt-4 pt-3 border-top" style="max-width: 700px;">
                        <button type="button" class="btn btn-primary-saas px-4" onclick="guardarConfiguracionGrupo()">
                            <i class="ti ti-device-floppy me-1"></i> Guardar Ajustes de Captura del Grupo
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>

{{-- MODAL PARA ASIGNAR NUEVO PERMISO / PRÓRROGA --}}
<div class="modal fade" id="modalAltaPermiso" tabindex="-1" aria-hidden="true" style="z-index: 1200;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-modal border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header px-4 py-3" style="border-bottom: 1px solid #E2E8F0;">
                <div class="d-flex align-items-center">
                    <i class="ti ti-key me-2 fs-5" style="color: #1E5B66;"></i>
                    <h5 class="modal-title fw-bold mb-0">Asignar Prórroga / Permiso Especial</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-white text-dark">
                <form id="formAltaPermiso">
                    
                    {{-- DOCENTE --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark"><i class="ti ti-chalkboard me-1" style="color: var(--brand-primary);"></i> Docente:</label>
                        <select id="selectAltaDocente" class="form-select form-select-premium w-100" onchange="filtrarMaterias()" required>
                            <option value="">Selecciona un docente...</option>
                        </select>
                    </div>

                    {{-- GRUPO --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark"><i class="ti ti-users me-1" style="color: var(--brand-primary);"></i> Grupo:</label>
                        <select id="selectAltaGrupo" class="form-select form-select-premium w-100" onchange="cargarMateriasPorGrupo(this.value)" required>
                            <option value="">Selecciona un grupo...</option>
                        </select>
                    </div>

                    {{-- MATERIA --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark"><i class="ti ti-books me-1" style="color: var(--brand-primary);"></i> Asignatura:</label>
                        <select id="selectAltaMateria" class="form-select form-select-premium w-100" required disabled>
                            <option value="">(Primero selecciona un grupo)</option>
                        </select>
                        <div id="loaderMaterias" class="spinner-border spinner-border-sm text-primary ms-2" role="status" style="display: none;"></div>
                        
                        {{-- Mostrar materias pasadas del grupo --}}
                        <div class="form-check mt-2" id="divCheckMateriaPasada" style="display: none;">
                            <input class="form-check-input" type="checkbox" id="checkMateriaPasada" onchange="filtrarMaterias()" style="cursor: pointer;">
                            <label class="form-check-label text-dark fw-semibold" for="checkMateriaPasada" style="font-size: 0.85rem; cursor: pointer;">
                                <i class="ti ti-history text-warning me-1"></i> Mostrar materias pasadas o de otros niveles
                            </label>
                        </div>
                    </div>

                    {{-- FECHA LÍMITE (EXTENSION) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark"><i class="ti ti-calendar-event me-1" style="color: var(--brand-primary);"></i> Prórroga / Fecha Límite de Captura:</label>
                        <input type="date" id="inputAltaFechaLimite" class="form-control form-control-premium" required>
                        <small class="text-muted d-block mt-1">El docente podrá capturar calificaciones en esta materia hasta las 23:59 hrs de la fecha indicada.</small>
                    </div>

                    {{-- PRIVILEGIOS DE PERIODOS PASADOS --}}
                    <div class="mb-3 p-3 rounded border bg-light d-flex align-items-center justify-content-between">
                        <div>
                            <label class="fw-bold text-dark mb-0 d-block" style="font-size: 0.9rem;">Modificar Semestres/Trimestres Pasados</label>
                            <small class="text-muted">Permitir editar calificaciones de periodos anteriores o grupos cerrados.</small>
                        </div>
                        <label class="switch-premium mb-0">
                            <input type="checkbox" id="checkAltaPasados" value="1">
                            <span class="slider-premium"></span>
                        </label>
                    </div>

                </form>
            </div>
            <div class="modal-footer bg-light border-0 px-4 py-3 d-flex justify-content-between">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary-saas px-4" onclick="guardarNuevoPermiso()">
                    <i class="ti ti-circle-check me-1"></i> Asignar Prórroga
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL PARA EDITAR PERMISO EXISTENTE --}}
<div class="modal fade" id="modalEditarPermiso" tabindex="-1" aria-hidden="true" style="z-index: 1200;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-modal border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header px-4 py-3" style="border-bottom: 1px solid #E2E8F0;">
                <div class="d-flex align-items-center">
                    <i class="ti ti-edit me-2 fs-5" style="color: #1E5B66;"></i>
                    <h5 class="modal-title fw-bold mb-0">Modificar Permiso Especial</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-white text-dark">
                <form id="formEditarPermiso">
                    <input type="hidden" id="inputEditId">
                    
                    {{-- DETALLES INFORMATIVOS --}}
                    <div class="mb-4 p-3 rounded bg-light border">
                        <div class="mb-1 text-muted" style="font-size: 0.8rem;">DOCENTE:</div>
                        <div id="lblEditDocente" class="fw-bold text-dark mb-2">—</div>
                        <div class="row">
                            <div class="col-6">
                                <div class="text-muted" style="font-size: 0.8rem;">GRUPO:</div>
                                <div id="lblEditGrupo" class="fw-bold text-dark">—</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted" style="font-size: 0.8rem;">ASIGNATURA:</div>
                                <div id="lblEditMateria" class="fw-bold text-dark">—</div>
                            </div>
                        </div>
                    </div>

                    {{-- FECHA LÍMITE (EXTENSION) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark"><i class="ti ti-calendar-event me-1" style="color: var(--brand-primary);"></i> Prórroga / Fecha Límite de Captura:</label>
                        <input type="date" id="inputEditFechaLimite" class="form-control form-control-premium" required>
                    </div>

                    {{-- PRIVILEGIOS DE PERIODOS PASADOS --}}
                    <div class="mb-3 p-3 rounded border bg-light d-flex align-items-center justify-content-between">
                        <div>
                            <label class="fw-bold text-dark mb-0 d-block" style="font-size: 0.9rem;">Modificar Semestres/Trimestres Pasados</label>
                            <small class="text-muted">Permitir editar calificaciones de periodos anteriores.</small>
                        </div>
                        <label class="switch-premium mb-0">
                            <input type="checkbox" id="checkEditPasados" value="1">
                            <span class="slider-premium"></span>
                        </label>
                    </div>

                    {{-- ESTADO HABILITADO --}}
                    <div class="mb-3 p-3 rounded border bg-light d-flex align-items-center justify-content-between">
                        <div>
                            <label class="fw-bold text-dark mb-0 d-block" style="font-size: 0.9rem;">Permiso Habilitado</label>
                            <small class="text-muted">Si se desactiva, el docente tendrá bloqueada la captura de inmediato.</small>
                        </div>
                        <label class="switch-premium mb-0">
                            <input type="checkbox" id="checkEditHabilitado" value="1">
                            <span class="slider-premium"></span>
                        </label>
                    </div>

                </form>
            </div>
            <div class="modal-footer bg-light border-0 px-4 py-3 d-flex justify-content-between">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary-saas px-4" onclick="guardarEdicionPermiso()">
                    <i class="ti ti-device-floppy me-1"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let listadoPermisos = [];
let listadoMatriz = [];

// ==========================================
// CARGAR MATRIZ DE AVANCE (TAB 1)
// ==========================================
function cargarMatriz() {
    const spinner = document.getElementById('spinnerMatriz');
    const wrapper = document.getElementById('wrapperMatriz');
    const empty = document.getElementById('emptyMatriz');

    spinner.style.display = 'block';
    wrapper.style.display = 'none';
    empty.style.display = 'none';

    fetch('/permisos-captura/matriz')
        .then(res => res.json())
        .then(data => {
            listadoMatriz = data;
            filtrarTablaMatriz();
        })
        .catch(err => {
            console.error('Error al cargar la matriz de avance:', err);
            Swal.fire({
                icon: 'error',
                title: 'Error de carga',
                text: 'No se pudo generar la matriz de avance.'
            });
        })
        .finally(() => {
            spinner.style.display = 'none';
        });
}

// ==========================================
// RENDERIZAR TABLA DE MATRIZ CON FILTROS
// ==========================================
function filtrarTablaMatriz() {
    const busqueda = document.getElementById('buscadorMatriz').value.toLowerCase().trim();
    const filtroEstado = document.getElementById('filtroEstadoMatriz').value;

    const filtrados = listadoMatriz.filter(h => {
        const docente = (h.nombre_docente || '').toLowerCase();
        const grupo = (h.clave_grupo || '').toLowerCase();
        const materia = (h.nombre_materia || '').toLowerCase();
        const clMateria = (h.clave_materia || '').toLowerCase();

        const matchBusqueda = !busqueda || 
            docente.includes(busqueda) || 
            grupo.includes(busqueda) || 
            materia.includes(busqueda) || 
            clMateria.includes(busqueda);

        let matchEstado = true;
        if (filtroEstado !== '') {
            matchEstado = h.estado === filtroEstado;
        }

        return matchBusqueda && matchEstado;
    });

    const tbody = document.getElementById('tbodyMatriz');
    const wrapper = document.getElementById('wrapperMatriz');
    const empty = document.getElementById('emptyMatriz');

    if (filtrados.length === 0) {
        wrapper.style.display = 'none';
        empty.style.display = 'block';
        return;
    }

    wrapper.style.display = 'block';
    empty.style.display = 'none';

    let html = '';
    filtrados.forEach((h, index) => {
        // Estatus Badge
        let statusBadge = '';
        let rowColorClass = '';
        switch (h.estado) {
            case 'completo':
                statusBadge = '<span class="badge-matriz-soft badge-soft-success"><i class="ti ti-circle-check"></i> Completo</span>';
                break;
            case 'pendiente':
                statusBadge = '<span class="badge-matriz-soft badge-soft-warning"><i class="ti ti-clock"></i> Ordinario</span>';
                break;
            case 'prorroga':
                statusBadge = '<span class="badge-matriz-soft badge-soft-info"><i class="ti ti-calendar-check"></i> Prórroga</span>';
                break;
            case 'expirado':
                statusBadge = '<span class="badge-matriz-soft badge-soft-danger"><i class="ti ti-alert-triangle"></i> Expirado</span>';
                rowColorClass = 'table-danger-light';
                break;
            case 'bloqueado_pasado':
                statusBadge = '<span class="badge-matriz-soft badge-soft-neutral"><i class="ti ti-lock"></i> Periodo Pasado</span>';
                break;
            case 'deshabilitado':
                statusBadge = '<span class="badge-matriz-soft badge-soft-neutral"><i class="ti ti-ban"></i> Desactivado</span>';
                break;
            case 'sin_alumnos':
                statusBadge = '<span class="badge-matriz-soft badge-soft-neutral"><i class="ti ti-users-minus"></i> Sin Alumnos</span>';
                break;
        }

        // Avance de Captura
        const pct = h.alumnos_totales > 0 ? Math.round((h.alumnos_calificados / h.alumnos_totales) * 100) : 0;
        let progressBg = '#1E5B66';
        if (h.estado === 'completo') progressBg = '#10B981';
        else if (h.estado === 'expirado') progressBg = '#EF4444';

        const progressHtml = `
            <div style="min-width: 150px;">
                <div class="progress-text-row">
                    <span>${h.alumnos_calificados} / ${h.alumnos_totales} alumnos</span>
                    <span>${pct}%</span>
                </div>
                <div class="progress-track-saas">
                    <div class="progress-fill-saas" style="width: ${pct}%; background-color: ${progressBg};"></div>
                </div>
            </div>
        `;

        // Botones de acción directa
        let actionBtn = '';
        if (h.estado !== 'completo' && h.estado !== 'sin_alumnos') {
            actionBtn += `
                <button type="button" class="btn-action-prorroga" onclick="otorgarProrrogaRapida(${h.id_docente}, ${h.id_grupo}, ${h.id_materia})" title="Asignar prórroga especial">
                    <i class="ti ti-calendar-plus"></i> <span>Prórroga</span>
                </button>
            `;
        }
        
        // Botón Administrador de capturar/cambiar notas
        actionBtn += `
            <a href="/grupos/captura_calificaciones?id_grupo=${h.id_grupo}&id_materia=${h.id_materia}" class="btn-action-notas" title="Capturar o modificar notas directamente como administrador">
                <i class="ti ti-edit"></i> <span>Calificaciones</span>
            </a>
        `;

        html += `
        <tr class="${rowColorClass}">
            <td><span class="id-mono">${index + 1}</span></td>
            <td>
                <span class="docente-nombre-matriz">${h.nombre_docente}</span>
            </td>
            <td>
                <span class="badge-grupo-mono">${h.clave_grupo}</span>
            </td>
            <td>
                <div class="materia-nombre-matriz">${h.nombre_materia}</div>
                <div class="materia-clave-mono">Clave: ${h.clave_materia}</div>
            </td>
            <td>
                ${progressHtml}
            </td>
            <td>
                <div class="fecha-limite-cell">
                    <i class="ti ti-calendar-due"></i>
                    <span>${formatearFechaEspanol(h.fecha_limite)}</span>
                </div>
            </td>
            <td class="text-center">
                ${statusBadge}
            </td>
            <td class="text-end" style="padding-right: 20px;">
                <div class="actions-cell-matriz">
                    ${actionBtn}
                </div>
            </td>
        </tr>
        `;
    });

    tbody.innerHTML = html;
}

// ==========================================
// OTORGAR PRÓRROGA RÁPIDA DESDE LA MATRIZ
// ==========================================
function otorgarProrrogaRapida(idDocente, idGrupo, idMateria) {
    // Resetear formulario
    document.getElementById('formAltaPermiso').reset();

    // Cargar catálogos
    Promise.all([
        fetch('/permisos-captura/docentes').then(r => r.json()),
        fetch('/permisos-captura/grupos').then(r => r.json())
    ]).then(([docentes, grupos]) => {
        // Cargar Docentes
        const selectDocente = document.getElementById('selectAltaDocente');
        selectDocente.innerHTML = '<option value="">Selecciona un docente...</option>';
        docentes.forEach(d => {
            const opt = document.createElement('option');
            opt.value = d.id;
            opt.textContent = d.nombre;
            if (d.id == idDocente) opt.selected = true;
            selectDocente.appendChild(opt);
        });

        // Cargar Grupos
        const selectGrupo = document.getElementById('selectAltaGrupo');
        selectGrupo.innerHTML = '<option value="">Selecciona un grupo...</option>';
        grupos.forEach(g => {
            const opt = document.createElement('option');
            opt.value = g.id;
            opt.textContent = g.clave;
            if (g.id == idGrupo) opt.selected = true;
            selectGrupo.appendChild(opt);
        });

        // Cargar materias del grupo y pre-seleccionar
        const selectMateria = document.getElementById('selectAltaMateria');
        selectMateria.disabled = true;

        fetch(`/grupos/${idGrupo}/calificaciones-materia`)
            .then(res => res.json())
            .then(resp => {
                selectMateria.innerHTML = '';
                if (resp.success && resp.data && Array.isArray(resp.data.materias)) {
                    resp.data.materias.forEach(m => {
                        const opt = document.createElement('option');
                        opt.value = m.idMateria;
                        opt.textContent = `${m.nombreMateria} (${m.claveMateria})`;
                        if (m.idMateria == idMateria) opt.selected = true;
                        selectMateria.appendChild(opt);
                    });
                    selectMateria.disabled = false;
                }
            })
            .finally(() => {
                // Abrir modal
                const modal = new bootstrap.Modal(document.getElementById('modalAltaPermiso'));
                modal.show();
            });
    });
}


// ==========================================
// CARGAR HISTORIAL DE PRÓRROGAS (TAB 2)
// ==========================================
function cargarPermisos() {
    const spinner = document.getElementById('spinnerCarga');
    const wrapper = document.getElementById('tablaWrapper');
    const empty = document.getElementById('emptyState');

    spinner.style.display = 'block';
    wrapper.style.display = 'none';
    empty.style.display = 'none';

    fetch('/permisos-captura/lista')
        .then(res => res.json())
        .then(data => {
            listadoPermisos = data;
            filtrarTablaPermisos();
        })
        .catch(err => {
            console.error('Error al cargar permisos:', err);
            Swal.fire({
                icon: 'error',
                title: 'Error de carga',
                text: 'No se pudieron recuperar las prórrogas registradas.'
            });
        })
        .finally(() => {
            spinner.style.display = 'none';
        });
}

// ==========================================
// RENDERIZAR TABLA DE HISTORIAL DE PRÓRROGAS
// ==========================================
function filtrarTablaPermisos() {
    const busqueda = document.getElementById('buscadorPermisos').value.toLowerCase().trim();
    const filtroEstado = document.getElementById('filtroEstado').value;

    const filtrados = listadoPermisos.filter(p => {
        const docente = (p.nombre_docente || '').toLowerCase();
        const grupo = (p.clave_grupo || '').toLowerCase();
        const materia = (p.nombre_materia || '').toLowerCase();
        const clMateria = (p.clave_materia || '').toLowerCase();

        const matchBusqueda = !busqueda || 
            docente.includes(busqueda) || 
            grupo.includes(busqueda) || 
            materia.includes(busqueda) || 
            clMateria.includes(busqueda);

        let matchEstado = true;
        if (filtroEstado === 'activo') {
            matchEstado = p.habilitado == 1;
        } else if (filtroEstado === 'inactivo') {
            matchEstado = p.habilitado == 0;
        }

        return matchBusqueda && matchEstado;
    });

    const tbody = document.getElementById('tbodyPermisos');
    const wrapper = document.getElementById('tablaWrapper');
    const empty = document.getElementById('emptyState');

    if (filtrados.length === 0) {
        wrapper.style.display = 'none';
        empty.style.display = 'block';
        return;
    }

    wrapper.style.display = 'block';
    empty.style.display = 'none';

    let html = '';
    filtrados.forEach((p, index) => {
        let estadoBadge = '';
        if (p.finalizado == 1 || p.finalizado === true) {
            estadoBadge = '<span class="badge-matriz-soft badge-soft-neutral"><i class="ti ti-lock"></i> Entregado</span>';
        } else if (p.habilitado == 1) {
            estadoBadge = '<span class="badge-matriz-soft badge-soft-success"><i class="ti ti-circle-check"></i> Activo</span>';
        } else {
            estadoBadge = '<span class="badge-matriz-soft badge-soft-danger"><i class="ti ti-circle-x"></i> Inactivo</span>';
        }

        const pasadosBadge = p.permitir_modificar_pasados == 1
            ? '<span class="badge-matriz-soft badge-soft-warning"><i class="ti ti-history"></i> Autorizado</span>'
            : '<span class="badge-matriz-soft badge-soft-neutral">No</span>';

        html += `
        <tr>
            <td><span class="id-mono">${index + 1}</span></td>
            <td>
                <span class="docente-nombre-matriz">${p.nombre_docente}</span>
            </td>
            <td>
                <span class="badge-grupo-mono">${p.clave_grupo}</span>
            </td>
            <td>
                <div class="materia-nombre-matriz">${p.nombre_materia}</div>
                <div class="materia-clave-mono">Clave: ${p.clave_materia}</div>
            </td>
            <td>
                <div class="fecha-limite-cell">
                    <i class="ti ti-calendar-due"></i>
                    <span>${formatearFechaEspanol(p.fecha_limite)}</span>
                </div>
            </td>
            <td class="text-center">
                ${pasadosBadge}
            </td>
            <td class="text-center">
                ${estadoBadge}
            </td>
            <td class="text-end" style="padding-right: 20px;">
                <div class="actions-cell-matriz">
                    <button type="button" class="btn-ghost-icon" title="Editar Permiso" onclick="abrirModalEdicion(${p.id})">
                        <i class="ti ti-edit"></i>
                    </button>
                    <button type="button" class="btn-ghost-icon btn-ghost-danger" title="Eliminar Permiso" onclick="eliminarPermiso(${p.id})">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
        `;
    });

    tbody.innerHTML = html;
}

function formatearFechaEspanol(fechaStr) {
    if (!fechaStr) return '—';
    const partes = fechaStr.split('-');
    if (partes.length === 3) {
        return `${partes[2]}/${partes[1]}/${partes[0]}`;
    }
    return fechaStr;
}

// ==========================================
// ABRIR ALTA PERMISO (GENERAL)
// ==========================================
function abrirModalAlta() {
    document.getElementById('formAltaPermiso').reset();
    document.getElementById('selectAltaMateria').innerHTML = '<option value="">(Primero selecciona un grupo)</option>';
    document.getElementById('selectAltaMateria').disabled = true;

    Promise.all([
        fetch('/permisos-captura/docentes').then(r => r.json()),
        fetch('/permisos-captura/grupos').then(r => r.json())
    ]).then(([docentes, grupos]) => {
        const selectDocente = document.getElementById('selectAltaDocente');
        selectDocente.innerHTML = '<option value="">Selecciona un docente...</option>';
        docentes.forEach(d => {
            const opt = document.createElement('option');
            opt.value = d.id;
            opt.textContent = d.nombre;
            selectDocente.appendChild(opt);
        });

        const selectGrupo = document.getElementById('selectAltaGrupo');
        selectGrupo.innerHTML = '<option value="">Selecciona un grupo...</option>';
        grupos.forEach(g => {
            const opt = document.createElement('option');
            opt.value = g.id;
            opt.textContent = g.clave;
            selectGrupo.appendChild(opt);
        });

        const modal = new bootstrap.Modal(document.getElementById('modalAltaPermiso'));
        modal.show();
    }).catch(err => {
        console.error('Error al cargar datos auxiliares:', err);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudieron recuperar los catálogos de docentes o grupos.'
        });
    });
}

// Variables para almacenar las materias y la información del grupo cargados
let materiasCargadas = [];
let grupoCargado = null;

function cargarMateriasPorGrupo(idGrupo) {
    const selectMateria = document.getElementById('selectAltaMateria');
    const loader = document.getElementById('loaderMaterias');
    const divCheck = document.getElementById('divCheckMateriaPasada');
    const checkPasada = document.getElementById('checkMateriaPasada');

    if (!idGrupo) {
        selectMateria.innerHTML = '<option value="">(Primero selecciona un grupo)</option>';
        selectMateria.disabled = true;
        if (divCheck) divCheck.style.display = 'none';
        if (checkPasada) checkPasada.checked = false;
        materiasCargadas = [];
        grupoCargado = null;
        return;
    }

    loader.style.display = 'inline-block';
    selectMateria.disabled = true;

    fetch(`/grupos/${idGrupo}/calificaciones-materia`)
        .then(res => res.json())
        .then(resp => {
            if (resp.success && resp.data) {
                materiasCargadas = resp.data.materias || [];
                grupoCargado = resp.data.grupo || null;
                if (divCheck) divCheck.style.display = 'block';
                filtrarMaterias();
            } else {
                selectMateria.innerHTML = '<option value="">No hay asignaturas en este grupo</option>';
                if (divCheck) divCheck.style.display = 'none';
                if (checkPasada) checkPasada.checked = false;
                materiasCargadas = [];
                grupoCargado = null;
            }
        })
        .catch(err => {
            console.error('Error al cargar materias:', err);
            selectMateria.innerHTML = '<option value="">Error al cargar asignaturas</option>';
            if (divCheck) divCheck.style.display = 'none';
            if (checkPasada) checkPasada.checked = false;
            materiasCargadas = [];
            grupoCargado = null;
        })
        .finally(() => {
            loader.style.display = 'none';
        });
}

function filtrarMaterias() {
    const selectMateria = document.getElementById('selectAltaMateria');
    const docenteId = document.getElementById('selectAltaDocente').value;
    const checkPasada = document.getElementById('checkMateriaPasada');
    const mostrarPasadas = checkPasada ? checkPasada.checked : false;

    selectMateria.innerHTML = '';

    if (materiasCargadas.length === 0) {
        selectMateria.innerHTML = '<option value="">No hay asignaturas en este grupo</option>';
        selectMateria.disabled = true;
        return;
    }

    // 1. Filtrar materias por el docente seleccionado (si hay uno elegido)
    let filtered = materiasCargadas;
    if (docenteId) {
        filtered = materiasCargadas.filter(m => {
            return m.id_docente && parseInt(m.id_docente) === parseInt(docenteId);
        });
    }

    // 2. Si no mostramos las pasadas, filtrar por el nivel actual del grupo
    if (!mostrarPasadas && grupoCargado && grupoCargado.id_nivel_academico) {
        const currentLevel = parseInt(grupoCargado.id_nivel_academico);
        filtered = filtered.filter(m => {
            return !m.id_nivel_academico || parseInt(m.id_nivel_academico) === currentLevel;
        });
    }

    if (filtered.length === 0) {
        const optNone = document.createElement('option');
        optNone.value = '';
        optNone.textContent = docenteId 
            ? (mostrarPasadas ? 'El docente no imparte materias en este grupo' : 'El docente no imparte materias en el nivel actual del grupo')
            : 'No hay materias que coincidan';
        selectMateria.appendChild(optNone);
        selectMateria.disabled = true;
        return;
    }

    const optDef = document.createElement('option');
    optDef.value = '';
    optDef.textContent = 'Selecciona una asignatura...';
    selectMateria.appendChild(optDef);

    filtered.forEach(m => {
        const opt = document.createElement('option');
        opt.value = m.idMateria;
        
        let labelNivel = '';
        if (m.nombreNivel) {
            labelNivel = ` - ${m.nombreNivel}`;
        }
        
        opt.textContent = `${m.nombreMateria} (${m.claveMateria})${labelNivel}`;
        selectMateria.appendChild(opt);
    });

    selectMateria.disabled = false;
}

// ==========================================
// GUARDAR NUEVO PERMISO
// ==========================================
function guardarNuevoPermiso() {
    const idDocente = document.getElementById('selectAltaDocente').value;
    const idGrupo = document.getElementById('selectAltaGrupo').value;
    const idMateria = document.getElementById('selectAltaMateria').value;
    const fechaLimite = document.getElementById('inputAltaFechaLimite').value;
    const checkPasados = document.getElementById('checkAltaPasados').checked ? 1 : 0;

    if (!idDocente || !idGrupo || !idMateria || !fechaLimite) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos incompletos',
            text: 'Por favor, llena todos los campos obligatorios del formulario.'
        });
        return;
    }

    fetch('/permisos-captura', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            id_docente: parseInt(idDocente),
            id_grupo: parseInt(idGrupo),
            id_materia: parseInt(idMateria),
            fecha_limite: fechaLimite,
            permitir_modificar_pasados: checkPasados
        })
    })
    .then(res => res.json())
    .then(resp => {
        if (resp.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Asignado!',
                text: resp.message,
                timer: 2000,
                showConfirmButton: false
            });
            const modalEl = document.getElementById('modalAltaPermiso');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
            
            // Recargar datos en la pestaña activa
            if (document.getElementById('tab-matriz-btn').classList.contains('active')) {
                cargarMatriz();
            } else {
                cargarPermisos();
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'No se pudo asignar',
                text: resp.message || 'Error desconocido'
            });
        }
    })
    .catch(err => {
        console.error('Error al guardar:', err);
        Swal.fire({
            icon: 'error',
            title: 'Error de servidor',
            text: 'Ocurrió un error al intentar crear el permiso.'
        });
    });
}

// ==========================================
// ABRIR EDICION
// ==========================================
function abrirModalEdicion(idPermiso) {
    const permiso = listadoPermisos.find(p => p.id === idPermiso);
    if (!permiso) return;

    document.getElementById('inputEditId').value = permiso.id;
    document.getElementById('lblEditDocente').textContent = permiso.nombre_docente;
    document.getElementById('lblEditGrupo').textContent = permiso.clave_grupo;
    document.getElementById('lblEditMateria').textContent = `${permiso.nombre_materia} (${permiso.clave_materia})`;
    document.getElementById('inputEditFechaLimite').value = permiso.fecha_limite || '';
    document.getElementById('checkEditPasados').checked = permiso.permitir_modificar_pasados == 1;
    document.getElementById('checkEditHabilitado').checked = permiso.habilitado == 1;

    const modal = new bootstrap.Modal(document.getElementById('modalEditarPermiso'));
    modal.show();
}

// ==========================================
// GUARDAR EDICION
// ==========================================
function guardarEdicionPermiso() {
    const id = document.getElementById('inputEditId').value;
    const fechaLimite = document.getElementById('inputEditFechaLimite').value;
    const checkPasados = document.getElementById('checkEditPasados').checked ? 1 : 0;
    const checkHabilitado = document.getElementById('checkEditHabilitado').checked ? 1 : 0;

    if (!id || !fechaLimite) {
        Swal.fire({
            icon: 'warning',
            title: 'Fecha Requerida',
            text: 'Por favor especifique la fecha límite del permiso.'
        });
        return;
    }

    fetch(`/permisos-captura/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            fecha_limite: fechaLimite,
            permitir_modificar_pasados: checkPasados,
            habilitado: checkHabilitado
        })
    })
    .then(res => res.json())
    .then(resp => {
        if (resp.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Actualizado!',
                text: resp.message,
                timer: 1500,
                showConfirmButton: false
            });
            const modalEl = document.getElementById('modalEditarPermiso');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
            
            cargarPermisos();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: resp.message || 'No se pudo guardar la actualización.'
            });
        }
    })
    .catch(err => {
        console.error('Error al actualizar:', err);
        Swal.fire({
            icon: 'error',
            title: 'Error de servidor',
            text: 'Ocurrió un error al intentar modificar el permiso.'
        });
    });
}

// ==========================================
// ELIMINAR PERMISO
// ==========================================
function eliminarPermiso(idPermiso) {
    const permiso = listadoPermisos.find(p => p.id === idPermiso);
    if (!permiso) return;

    Swal.fire({
        title: '¿Eliminar Permiso Especial?',
        text: `Se retirará el permiso del docente "${permiso.nombre_docente}" para la materia "${permiso.nombre_materia}". Su acceso volverá a los límites de tiempo regulares.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#E53935',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/permisos-captura/${idPermiso}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(resp => {
                if (resp.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: resp.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    cargarPermisos();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: resp.message || 'No se pudo eliminar el permiso.'
                    });
                }
            })
            .catch(err => {
                console.error('Error al eliminar:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de servidor',
                    text: 'Ocurrió un error al intentar eliminar el permiso.'
                });
            });
        }
    });
}

// ==========================================
// SECCIÓN DE CONFIGURACIÓN CCT / GRUPO (TAB 3)
// ==========================================
let listadoCcts = [];
let listadoGruposCct = [];
let grupoConfiguracionActualId = null;

function cargarCctConfigList() {
    const selectCct = document.getElementById('selectCctConfig');
    const selectGrupo = document.getElementById('selectGrupoConfig');
    document.getElementById('cardAjustesGrupo').style.display = 'none';
    
    selectGrupo.innerHTML = '<option value="">(Primero selecciona un plantel)</option>';
    selectGrupo.disabled = true;

    fetch('/permisos-captura/ccts')
        .then(res => res.json())
        .then(data => {
            listadoCcts = data;
            selectCct.innerHTML = '<option value="">Seleccione un plantel...</option>';
            data.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.id;
                opt.textContent = `${c.nombre} (${c.clave})`;
                selectCct.appendChild(opt);
            });
        })
        .catch(err => {
            console.error('Error al cargar planteles CCT:', err);
            Swal.fire({
                icon: 'error',
                title: 'Error de carga',
                text: 'No se pudo cargar el listado de planteles.'
            });
        });
}

function cargarGruposConfig(idCct) {
    const selectGrupo = document.getElementById('selectGrupoConfig');
    document.getElementById('cardAjustesGrupo').style.display = 'none';
    grupoConfiguracionActualId = null;

    if (!idCct) {
        selectGrupo.innerHTML = '<option value="">(Primero selecciona un plantel)</option>';
        selectGrupo.disabled = true;
        return;
    }

    selectGrupo.innerHTML = '<option value="">Cargando grupos...</option>';
    selectGrupo.disabled = true;

    fetch(`/permisos-captura/cct/${idCct}/grupos`)
        .then(res => res.json())
        .then(data => {
            listadoGruposCct = data;
            selectGrupo.innerHTML = '<option value="">Seleccione un grupo...</option>';
            if (data.length > 0) {
                data.forEach(g => {
                    const opt = document.createElement('option');
                    opt.value = g.id;
                    opt.textContent = g.clave;
                    selectGrupo.appendChild(opt);
                });
                selectGrupo.disabled = false;
            } else {
                selectGrupo.innerHTML = '<option value="">No hay grupos activos en este plantel</option>';
            }
        })
        .catch(err => {
            console.error('Error al cargar grupos del CCT:', err);
            selectGrupo.innerHTML = '<option value="">Error al cargar grupos</option>';
        });
}

function cargarConfiguracionGrupo(idGrupo) {
    const card = document.getElementById('cardAjustesGrupo');
    if (!idGrupo) {
        card.style.display = 'none';
        grupoConfiguracionActualId = null;
        return;
    }

    grupoConfiguracionActualId = parseInt(idGrupo);
    const selectedGrupo = listadoGruposCct.find(g => g.id == idGrupo);
    document.getElementById('lblGrupoNombreTitle').textContent = `Ajustes de Captura - Grupo: ${selectedGrupo ? selectedGrupo.clave : '—'}`;

    fetch(`/permisos-captura/grupo-config/${idGrupo}`)
        .then(res => res.json())
        .then(resp => {
            if (resp.success && resp.config) {
                const c = resp.config;
                
                // Populate level dropdown
                const selectNivel = document.getElementById('selectGrupoNivelCaptura');
                selectNivel.innerHTML = '<option value="">Seleccione un semestre...</option>';
                if (resp.levels && resp.levels.length > 0) {
                    resp.levels.forEach(lvl => {
                        const opt = document.createElement('option');
                        opt.value = lvl.id;
                        opt.textContent = lvl.nombre;
                        selectNivel.appendChild(opt);
                    });
                }
                selectNivel.value = c.id_nivel_academico || '';

                // Handle CCT BGNE visibility adaptations
                const isBgne = resp.isBgne === true;
                const cardP2 = document.getElementById('cardAjusteP2');
                const cardP3 = document.getElementById('cardAjusteP3');
                const cardSem = document.getElementById('cardAjusteSemestral');
                const lblP1Title = document.getElementById('lblGrupoP1Title');

                if (isBgne) {
                    if (cardP2) cardP2.style.display = 'none';
                    if (cardP3) cardP3.style.display = 'none';
                    if (cardSem) cardSem.style.display = 'none';
                    if (lblP1Title) {
                        lblP1Title.innerHTML = '<i class="ti ti-checkbox text-muted me-2"></i> Calificación Final de la Asignatura';
                    }
                } else {
                    if (cardP2) cardP2.style.display = 'block';
                    if (cardP3) cardP3.style.display = 'block';
                    if (cardSem) cardSem.style.display = 'block';
                    if (lblP1Title) {
                        lblP1Title.innerHTML = '<i class="ti ti-checkbox text-muted me-2"></i> 1er. Parcial';
                    }
                }

                document.getElementById('checkGrupoHabilitada').checked = c.captura_habilitada == 1;
                
                // Parcial 1
                document.getElementById('checkGrupoP1').checked = c.p1_habilitado == 1;
                document.getElementById('inputGrupoP1Inicio').value = c.p1_fecha_inicio || '';
                document.getElementById('inputGrupoP1Fin').value = c.p1_fecha_fin || '';
                toggleFilaFecha('p1', c.p1_habilitado == 1);

                // Parcial 2
                document.getElementById('checkGrupoP2').checked = c.p2_habilitado == 1;
                document.getElementById('inputGrupoP2Inicio').value = c.p2_fecha_inicio || '';
                document.getElementById('inputGrupoP2Fin').value = c.p2_fecha_fin || '';
                toggleFilaFecha('p2', c.p2_habilitado == 1);

                // Parcial 3
                document.getElementById('checkGrupoP3').checked = c.p3_habilitado == 1;
                document.getElementById('inputGrupoP3Inicio').value = c.p3_fecha_inicio || '';
                document.getElementById('inputGrupoP3Fin').value = c.p3_fecha_fin || '';
                toggleFilaFecha('p3', c.p3_habilitado == 1);

                // Semestral
                document.getElementById('checkGrupoSemestral').checked = c.semestral_habilitado == 1;
                document.getElementById('inputGrupoSemestralInicio').value = c.semestral_fecha_inicio || '';
                document.getElementById('inputGrupoSemestralFin').value = c.semestral_fecha_fin || '';
                toggleFilaFecha('semestral', c.semestral_habilitado == 1);

                // Extraordinario
                document.getElementById('checkGrupoExtraordinario').checked = c.extraordinario_habilitado == 1;
                document.getElementById('inputGrupoExtraordinarioInicio').value = c.extraordinario_fecha_inicio || '';
                document.getElementById('inputGrupoExtraordinarioFin').value = c.extraordinario_fecha_fin || '';
                toggleFilaFecha('extraordinario', c.extraordinario_habilitado == 1);

                toggleInputsGrupo(c.captura_habilitada == 1);
                card.style.display = 'block';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: resp.message || 'No se pudo obtener la configuración.'
                });
            }
        })
        .catch(err => {
            console.error('Error al cargar config grupo:', err);
            Swal.fire({
                icon: 'error',
                title: 'Error de carga',
                text: 'No se pudo cargar la configuración de este grupo.'
            });
        });
}

function toggleInputsGrupo(enabled) {
    const switches = document.querySelectorAll('.switch-grupo-sub');
    switches.forEach(s => {
        s.disabled = !enabled;
    });

    const cardAjustes = document.querySelectorAll('.card-periodo-ajuste');
    cardAjustes.forEach(c => {
        if (!enabled) {
            c.style.opacity = '0.5';
            c.querySelectorAll('input').forEach(i => i.disabled = true);
        } else {
            c.style.opacity = '1';
            const sw = c.querySelector('.switch-grupo-sub');
            c.querySelectorAll('input[type="date"]').forEach(i => i.disabled = !sw.checked);
        }
    });
}

function toggleFilaFecha(periodKey, enabled) {
    const capitalized = periodKey.charAt(0).toUpperCase() + periodKey.slice(1);
    const inpInicio = document.getElementById(`inputGrupo${capitalized}Inicio`);
    const inpFin = document.getElementById(`inputGrupo${capitalized}Fin`);
    const masterEnabled = document.getElementById('checkGrupoHabilitada').checked;

    if (inpInicio && inpFin) {
        const shouldDisable = !enabled || !masterEnabled;
        inpInicio.disabled = shouldDisable;
        inpFin.disabled = shouldDisable;
    }
}

function guardarConfiguracionGrupo() {
    if (!grupoConfiguracionActualId) return;

    const idNivelAcademico = document.getElementById('selectGrupoNivelCaptura').value;
    const capturaHabilitada = document.getElementById('checkGrupoHabilitada').checked ? 1 : 0;
    
    const p1Habilitado = document.getElementById('checkGrupoP1').checked ? 1 : 0;
    const p1Inicio = document.getElementById('inputGrupoP1Inicio').value;
    const p1Fin = document.getElementById('inputGrupoP1Fin').value;

    const p2Habilitado = document.getElementById('checkGrupoP2').checked ? 1 : 0;
    const p2Inicio = document.getElementById('inputGrupoP2Inicio').value;
    const p2Fin = document.getElementById('inputGrupoP2Fin').value;

    const p3Habilitado = document.getElementById('checkGrupoP3').checked ? 1 : 0;
    const p3Inicio = document.getElementById('inputGrupoP3Inicio').value;
    const p3Fin = document.getElementById('inputGrupoP3Fin').value;

    const semestralHabilitado = document.getElementById('checkGrupoSemestral').checked ? 1 : 0;
    const semestralInicio = document.getElementById('inputGrupoSemestralInicio').value;
    const semestralFin = document.getElementById('inputGrupoSemestralFin').value;

    const extraordinarioHabilitado = document.getElementById('checkGrupoExtraordinario').checked ? 1 : 0;
    const extraordinarioInicio = document.getElementById('inputGrupoExtraordinarioInicio').value;
    const extraordinarioFin = document.getElementById('inputGrupoExtraordinarioFin').value;

    fetch(`/permisos-captura/grupo-config/${grupoConfiguracionActualId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            id_nivel_academico: idNivelAcademico,
            captura_habilitada: capturaHabilitada,
            
            p1_habilitado: p1Habilitado,
            p1_fecha_inicio: p1Inicio,
            p1_fecha_fin: p1Fin,

            p2_habilitado: p2Habilitado,
            p2_fecha_inicio: p2Inicio,
            p2_fecha_fin: p2Fin,

            p3_habilitado: p3Habilitado,
            p3_fecha_inicio: p3Inicio,
            p3_fecha_fin: p3Fin,

            semestral_habilitado: semestralHabilitado,
            semestral_fecha_inicio: semestralInicio,
            semestral_fecha_fin: semestralFin,

            extraordinario_habilitado: extraordinarioHabilitado,
            extraordinario_fecha_inicio: extraordinarioInicio,
            extraordinario_fecha_fin: extraordinarioFin
        })
    })
    .then(res => res.json())
    .then(resp => {
        if (resp.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Guardado!',
                text: resp.message,
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error al guardar',
                text: resp.message || 'No se pudo guardar la configuración.'
            });
        }
    })
    .catch(err => {
        console.error('Error al guardar config grupo:', err);
        Swal.fire({
            icon: 'error',
            title: 'Error de servidor',
            text: 'Ocurrió un error al guardar los ajustes de este grupo.'
        });
    });
}

// Carga Inicial
document.addEventListener("DOMContentLoaded", function() {
    const modalAlta = document.getElementById('modalAltaPermiso');
    const modalEdit = document.getElementById('modalEditarPermiso');
    const modalContainer = document.getElementById('contenedorModal') || document.body;

    if (modalAlta && modalAlta.parentElement !== modalContainer) {
        modalContainer.appendChild(modalAlta);
    }
    if (modalEdit && modalEdit.parentElement !== modalContainer) {
        modalContainer.appendChild(modalEdit);
    }

    cargarMatriz();
});
</script>

@endsection
