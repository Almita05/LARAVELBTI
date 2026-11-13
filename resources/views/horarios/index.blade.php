@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Tom Select (Autocomplete en Selects) -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<style>
:root {
    --brand-primary: #26687B;
    --brand-dark: #184A54;
    --brand-light: #EFF6F7;
    --brand-border: #BBDBE1;
    --bg-page: #F8FAFC;
    --bg-surface: #FFFFFF;
    --border-subtle: #E2E8F0;
    --text-primary: #0F172A;
    --text-secondary: #475569;
    --text-muted: #64748B;
    --success-bg: #DCFCE7;
    --success-text: #15803D;
    --success-border: #BBF7D0;
    --danger-bg: #FEE2E2;
    --danger-text: #B91C1C;
    --danger-border: #FECACA;
}

/* Wrapper to overlay the layout's teal background and make it clean light-gray */
.horarios-page-wrapper {
    background: var(--bg-page);
    margin: -25px;
    padding: 24px 32px;
    min-height: calc(100vh - 85px);
    color: var(--text-primary);
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

/* Page Header (Unified horizontal row) */
.horarios-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    gap: 16px;
    flex-wrap: wrap;
}

.horarios-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.page-heading {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
    line-height: 1.2;
    letter-spacing: -0.01em;
}

/* Return Button */
.btn-regresar {
    background: #FFFFFF;
    border: 1px solid var(--border-subtle);
    color: var(--text-secondary);
    height: 36px;
    padding: 0 12px;
    border-radius: 0;
    font-size: 0.875rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
    transition: all 0.15s ease;
}

.btn-regresar:hover {
    background: #F1F5F9;
    color: var(--text-primary);
    border-color: #CBD5E1;
}

/* Institutional Soft Badge for role */
.badge-rol-coordinacion {
    background: var(--brand-light);
    color: var(--brand-primary);
    border: 1px solid var(--brand-border);
    padding: 4px 10px;
    border-radius: 0;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

/* 3-Panel Proportional Grid Layout */
.horarios-grid-layout {
    display: grid;
    grid-template-columns: 250px minmax(0, 1fr) 300px;
    gap: 20px;
    align-items: start;
    margin-bottom: 24px;
}

@media (max-width: 1200px) {
    .horarios-grid-layout {
        grid-template-columns: 250px minmax(0, 1fr);
    }
    .edit-panel-wrapper {
        grid-column: 1 / -1;
    }
}

@media (max-width: 820px) {
    .horarios-grid-layout {
        grid-template-columns: 1fr;
    }
}

/* Uniform Panel Surfaces */
.sidebar-panel,
.calendar-panel,
.edit-panel {
    background: #FFFFFF;
    border: 1px solid var(--border-subtle);
    border-radius: 0;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    display: flex;
    flex-direction: column;
}

.sidebar-panel {
    padding: 16px;
}

.calendar-panel {
    padding: 16px;
}

.edit-panel {
    padding: 16px;
    overflow-y: auto;
    max-height: calc(100vh - 140px);
    scrollbar-width: thin;
}

.sidebar-title,
.edit-panel-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 12px 0;
}

/* Search Box */
.search-container {
    position: relative;
    margin-bottom: 12px;
}

.search-icon {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #94A3B8;
    font-size: 1rem;
    pointer-events: none;
}

.search-input-custom {
    background: #FFFFFF !important;
    border: 1px solid var(--border-subtle) !important;
    color: var(--text-primary) !important;
    border-radius: 0!important;
    padding-left: 34px !important;
    height: 36px !important;
    font-size: 0.8125rem !important;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.search-input-custom:focus {
    border-color: var(--brand-primary) !important;
    box-shadow: 0 0 0 2px rgba(38, 104, 123, 0.12) !important;
    outline: none !important;
}

.filter-select-custom {
    background-color: #FFFFFF !important;
    border: 1px solid var(--border-subtle) !important;
    color: var(--text-secondary) !important;
    border-radius: 0!important;
    font-size: 0.8125rem !important;
    height: 34px !important;
    padding: 0 24px 0 8px !important;
    cursor: pointer;
    font-weight: 500;
}

.filter-select-custom:focus {
    border-color: var(--brand-primary) !important;
    box-shadow: 0 0 0 2px rgba(38, 104, 123, 0.12) !important;
    outline: none !important;
}

/* Groups List */
.group-list {
    overflow-y: auto;
    max-height: calc(100vh - 280px);
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding-right: 4px;
    scrollbar-width: thin;
}

.group-list::-webkit-scrollbar,
.calendar-container::-webkit-scrollbar,
.edit-panel::-webkit-scrollbar {
    width: 5px;
    height: 5px;
}
.group-list::-webkit-scrollbar-thumb,
.calendar-container::-webkit-scrollbar-thumb,
.edit-panel::-webkit-scrollbar-thumb {
    background: #CBD5E1;
    border-radius: 0;
}

.group-item {
    background: #FFFFFF;
    border: 1px solid var(--border-subtle);
    border-radius: 0;
    padding: 10px 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    transition: all 0.15s ease;
    margin-bottom: 0;
    width: 100%;
    text-align: left;
}

.group-item span.group-clave-text {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.8125rem;
    font-weight: 600;
    color: #334155;
}

.group-period-badge {
    font-size: 0.6875rem;
    font-weight: 600;
    background: #F1F5F9;
    color: #475569;
    padding: 2px 7px;
    border-radius: 0;
    border: 1px solid var(--border-subtle);
    font-family: 'Inter', sans-serif;
}

.group-item:hover {
    background: #F8FAFC;
    border-color: #CBD5E1;
}

/* Active Selected Group Item */
.group-item.active {
    background: var(--brand-light) !important;
    border-color: var(--brand-primary) !important;
    box-shadow: inset 3px 0 0 0 var(--brand-primary) !important;
}

.group-item.active span.group-clave-text {
    color: var(--brand-primary) !important;
    font-weight: 700;
}

.group-item.active .group-period-badge {
    background: #E0EFF2 !important;
    color: var(--brand-primary) !important;
    border-color: var(--brand-border) !important;
}

/* Calendar Header & Actions */
.calendar-header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
    gap: 12px;
    flex-wrap: wrap;
}

.calendar-group-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.calendar-group-title span.group-clave-highlight {
    font-family: 'JetBrains Mono', monospace;
    color: var(--brand-primary);
    font-weight: 700;
}

.badge-nivel {
    background-color: var(--brand-light);
    color: var(--brand-primary);
    border: 1px solid var(--brand-border);
    font-size: 0.75rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 0;
    display: inline-block;
}

.btn-header-secondary {
    background: #FFFFFF;
    border: 1px solid var(--border-subtle);
    color: var(--text-secondary);
    height: 32px;
    padding: 0 12px;
    border-radius: 0;
    font-size: 0.8125rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.15s ease;
    text-decoration: none;
}

.btn-header-secondary:hover {
    background: #F1F5F9;
    color: var(--text-primary);
    border-color: #CBD5E1;
}

.btn-header-primary {
    background: var(--brand-primary);
    border: none;
    color: #FFFFFF;
    height: 32px;
    padding: 0 12px;
    border-radius: 0;
    font-size: 0.8125rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.15s ease;
    cursor: pointer;
}

.btn-header-primary:hover {
    background: var(--brand-dark);
}

/* Calendar Container & Table */
.calendar-container {
    overflow-x: auto;
    overflow-y: auto;
    max-height: calc(100vh - 250px);
    border: 1px solid var(--border-subtle);
    border-radius: 0;
    scrollbar-width: thin;
}

.calendar-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 680px;
    background: #FFFFFF;
}

.calendar-table th {
    background: #F8FAFC !important;
    border: 1px solid var(--border-subtle);
    color: var(--text-secondary) !important;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    text-align: center;
    padding: 10px 8px;
    letter-spacing: 0.02em;
}

.calendar-table td {
    border: 1px solid var(--border-subtle) !important;
    padding: 3px !important;
    vertical-align: middle;
    position: relative;
    background: #FFFFFF !important;
}

.time-col {
    background: #F8FAFC !important;
    color: var(--text-muted) !important;
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.75rem;
    font-weight: 600;
    text-align: center;
    width: 105px;
    padding: 8px !important;
    border-right: 1px solid var(--border-subtle) !important;
    white-space: nowrap;
}

/* Receso Row */
.receso-row {
    background: #F8FAFC !important;
}

.receso-time-col {
    background: #F1F5F9 !important;
    color: var(--text-secondary) !important;
    font-family: 'JetBrains Mono', monospace;
    font-weight: 700 !important;
    font-size: 0.75rem !important;
}

.receso-cell {
    background: #F8FAFC !important;
    padding: 4px 8px !important;
    text-align: center;
    border: 1px solid var(--border-subtle) !important;
}

.receso-banner {
    background: #F8FAFC;
    border: 1px dashed #CBD5E1;
    color: var(--text-secondary);
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.05em;
    border-radius: 0;
    padding: 6px 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    user-select: none;
}

/* Calendar Cell Slots */
.cell-slot {
    width: 100%;
    min-height: 72px;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #CBD5E1;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.15s ease;
    position: relative;
    border-radius: 0;
}

.cell-slot:hover {
    background: #F8FAFC;
}

.cell-slot.selected {
    background: rgba(38, 104, 123, 0.06) !important;
    border: 2px dashed var(--brand-primary);
    border-radius: 0;
}

.cell-slot .add-btn-icon {
    display: none;
    color: #94A3B8;
    font-size: 1rem;
}

.cell-slot:hover .add-btn-icon {
    display: block;
}

.cell-slot:hover .placeholder-dash {
    display: none;
}

/* Assigned Class Card */
.class-card {
    position: relative;
    width: calc(100% - 6px);
    min-height: 64px;
    height: auto;
    margin: 3px;
    border-radius: 0;
    padding: 8px 10px;
    text-align: left;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 3px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
    background: var(--brand-light);
    border: 1px solid var(--brand-border);
    border-left: 3px solid var(--brand-primary);
    box-sizing: border-box;
    align-self: center;
    cursor: grab;
    user-select: none;
    transition: all 0.15s ease;
}

.class-card:active {
    cursor: grabbing;
}

.class-card.dragging {
    opacity: 0.45;
    transform: scale(0.96);
    border: 2px dashed var(--brand-primary) !important;
}

.cell-slot.drag-over {
    background: rgba(38, 104, 123, 0.12) !important;
    border: 2px dashed var(--brand-primary) !important;
}

.class-subject {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.25;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 4px;
}

.aula-badge {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.6875rem;
    background: #FFFFFF;
    color: var(--brand-primary);
    border: 1px solid var(--brand-border);
    padding: 1px 5px;
    border-radius: 0;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 2px;
}

.class-detail {
    font-size: 0.7rem;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: 3px;
    line-height: 1.2;
}

/* Right Panel: Edit Form & Evaluation Periods */
.edit-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
    border-bottom: 1px solid var(--border-subtle);
    padding-bottom: 10px;
}

.btn-close-panel {
    background: transparent;
    border: none;
    color: var(--text-muted);
    font-size: 1.1rem;
    cursor: pointer;
    transition: color 0.15s ease;
    padding: 2px 6px;
    border-radius: 0;
}

.btn-close-panel:hover {
    color: var(--text-primary);
    background: #F1F5F9;
}

.edit-form {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.form-group-custom {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.form-group-custom label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
}

.form-input-custom {
    background: #FFFFFF !important;
    border: 1px solid var(--border-subtle) !important;
    color: var(--text-primary) !important;
    border-radius: 0!important;
    padding: 6px 10px !important;
    font-size: 0.8125rem !important;
    height: 36px !important;
    width: 100%;
}

.form-input-custom:focus {
    border-color: var(--brand-primary) !important;
    box-shadow: 0 0 0 2px rgba(38, 104, 123, 0.12) !important;
    outline: none !important;
}

.form-input-custom[readonly] {
    background: #F8FAFC !important;
    color: var(--text-muted) !important;
    border-color: var(--border-subtle) !important;
    cursor: not-allowed;
}

.form-actions-custom {
    display: flex;
    gap: 8px;
    margin-top: 10px;
}

.btn-form-cancel {
    flex: 1;
    background: #FFFFFF;
    border: 1px solid var(--border-subtle);
    color: var(--text-secondary);
    font-size: 0.8125rem;
    font-weight: 500;
    height: 36px;
    border-radius: 0;
    transition: all 0.15s ease;
    cursor: pointer;
}

.btn-form-cancel:hover {
    background: #F8FAFC;
    color: var(--text-primary);
    border-color: #CBD5E1;
}

.btn-form-save {
    flex: 1;
    background: var(--brand-primary) !important;
    border: none !important;
    color: #FFFFFF !important;
    font-size: 0.8125rem !important;
    font-weight: 500 !important;
    height: 36px !important;
    border-radius: 0!important;
    transition: all 0.15s ease;
    cursor: pointer;
}

.btn-form-save:hover {
    background: var(--brand-dark) !important;
}

.btn-form-delete {
    background: #EF4444;
    border: none;
    color: #FFFFFF;
    font-size: 0.8125rem;
    font-weight: 500;
    height: 36px;
    border-radius: 0;
    transition: all 0.15s ease;
    margin-top: 6px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    width: 100%;
}

.btn-form-delete:hover {
    background: #DC2626;
}

/* Evaluation Period Cards & Notice */
.eval-card {
    padding: 10px 12px;
    border: 1px solid var(--border-subtle);
    border-radius: 0;
    background: #FFFFFF;
    margin-bottom: 8px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.02);
}

.eval-card-title {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-primary);
}

.eval-dates-mono {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.72rem;
    color: #334155;
    margin-top: 3px;
}

.badge-soft-success {
    background: var(--success-bg);
    color: var(--success-text);
    border: 1px solid var(--success-border);
    font-size: 0.6875rem;
    padding: 1px 6px;
    border-radius: 0;
    font-weight: 600;
}

.badge-soft-muted {
    background: #F1F5F9;
    color: var(--text-muted);
    border: 1px solid var(--border-subtle);
    font-size: 0.6875rem;
    padding: 1px 6px;
    border-radius: 0;
    font-weight: 500;
}

.btn-eval-periodos {
    height: 36px;
    width: 100%;
    background: var(--brand-primary);
    color: #FFFFFF;
    border-radius: 0;
    font-size: 0.8125rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: none;
    margin-top: 8px;
    cursor: pointer;
    transition: all 0.15s ease;
}

.btn-eval-periodos:hover {
    background: var(--brand-dark);
}

.eval-info-notice {
    margin-top: 12px;
    padding: 12px;
    background: #F8FAFC;
    border: 1px dashed #CBD5E1;
    border-radius: 0;
    display: flex;
    align-items: flex-start;
    gap: 8px;
    font-size: 0.75rem;
    color: var(--text-muted);
    line-height: 1.4;
}

/* Empty State Message */
.empty-state-message {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    text-align: center;
    gap: 12px;
    padding: 24px 12px;
}

.empty-state-message i {
    font-size: 2rem;
    color: #CBD5E1;
}

/* Spin animation for Tabler icons */
.ti-spin {
    animation: ti-spin 1s infinite linear;
    display: inline-block;
}
@keyframes ti-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(359deg); }
}

/* Fix TomSelect width inside initially hidden containers */
.ts-wrapper, .ts-control {
    width: 100% !important;
}
</style>

<div class="horarios-page-wrapper">

    <!-- Page Header (Unified horizontal row, role badge, avatar removed) -->
    <div class="horarios-page-header">
        <div class="horarios-header-left">
            <a href="{{ route('home') }}" class="btn-regresar">
                <i class="ti ti-arrow-left"></i>
                Regresar
            </a>
            <h1 class="page-heading">Horarios del Grupo</h1>
        </div>
        
        <!-- Institutional Soft Badge Role (Avatar removed) -->
        <span class="badge-rol-coordinacion">
            <i class="ti ti-shield-check"></i>
            Coordinación Académica
        </span>
    </div>

    <!-- Main 3-Panel Proportional Grid Layout -->
    <div class="horarios-grid-layout">
        
        <!-- Left Panel: Select Group Sidebar -->
        <div class="sidebar-panel">
            <h4 class="sidebar-title">Seleccionar Grupo</h4>
            
            <!-- Search Input -->
            <div class="search-container">
                <i class="ti ti-search search-icon"></i>
                <input type="text" id="groupSearchInput" class="form-control search-input-custom" placeholder="Buscar grupo o alumno...">
            </div>

            <!-- Filters Row -->
            <div class="d-flex gap-2 mb-3">
                <select id="filterStatus" class="form-select form-select-sm filter-select-custom w-50">
                    <option value="ACTIVO" selected>Activos</option>
                    <option value="INACTIVO">Inactivos</option>
                    <option value="ALL">Todos</option>
                </select>
                <select id="filterCct" class="form-select form-select-sm filter-select-custom w-50">
                    <option value="ALL" selected>Todos CCT</option>
                </select>
            </div>

            <!-- Groups List -->
            <div id="groupListContainer" class="group-list">
                <div class="text-center py-4" id="groupListSpinner">
                    <div class="spinner-border text-secondary spinner-border-sm"></div>
                </div>
            </div>
        </div>

        <!-- Center Panel: Calendar Grid -->
        <div class="calendar-panel">
            <div class="calendar-header-actions">
                <h3 class="calendar-group-title" id="calendarGroupTitle">
                    Horario del Grupo: <span id="selectedGroupName" class="group-clave-highlight">Ninguno</span>
                    <span id="selectedGroupNivel" class="badge-nivel ms-2" style="display:none;"></span>
                </h3>
                <div class="d-flex gap-2">
                    <button class="btn-header-secondary" type="button">
                        <i class="ti ti-user-check"></i>Vista del Docente
                    </button>
                    <button class="btn-header-primary" id="btnSaveChanges" type="button">
                        <i class="ti ti-refresh"></i>Refrescar
                    </button>
                </div>
            </div>

            <!-- Progress Bar for Academic Level -->
            <div id="progressContainer" style="display:none; margin-top: 5px; margin-bottom: 16px; padding: 0 4px;">
                <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.75rem; color: #64748B;">
                    <span>Inicio Periodo: <strong id="progressStartDate" style="font-family: 'JetBrains Mono', monospace;">—</strong></span>
                    <span id="progressPercent" style="font-weight: 600; color: #26687B; font-family: 'JetBrains Mono', monospace;">0%</span>
                    <span>Fin Periodo: <strong id="progressEndDate" style="font-family: 'JetBrains Mono', monospace;">—</strong></span>
                </div>
                <div class="progress" style="height: 6px; background-color: #E2E8F0; border-radius: 0; overflow: hidden;">
                    <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%; background-color: #26687B; transition: width 0.4s ease;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>

            <!-- Calendar Table wrapper -->
            <div class="calendar-container">
                <div id="calendarEmptyState" class="empty-state-message">
                    <i class="ti ti-calendar" style="font-size: 36px; color: #CBD5E1;"></i>
                    <div style="font-size: 0.875rem; color: #64748B;">Selecciona un grupo del panel izquierdo para ver y gestionar su horario.</div>
                </div>

                <table class="calendar-table" id="calendarTable" style="display: none;">
                    <thead id="calendarHeader">
                        <!-- Generado dinámicamente -->
                    </thead>
                    <tbody id="calendarBody">
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Panel: Edit Class Form / Evaluation Dates -->
        <div class="edit-panel-wrapper">
            <div class="edit-panel">
                <div class="edit-panel-header">
                    <h4 class="edit-panel-title">Editar Clase</h4>
                    <button class="btn-close-panel" id="btnCloseEditPanel" title="Cerrar panel">
                        <i class="ti ti-x"></i>
                    </button>
                </div>

                <div id="editEmptyState" class="w-100" style="display: flex; flex-direction: column; justify-content: flex-start; align-items: stretch; text-align: left; padding: 0;">
                    <!-- Default placeholder, shown when no group is selected -->
                    <div id="noGroupSelectedMessage" class="text-center py-5">
                        <i class="ti ti-calendar" style="font-size: 32px; color: #CBD5E1; margin-bottom: 12px; display: inline-block;"></i>
                        <div style="font-size: 0.8125rem; color: #64748B;">Selecciona un grupo para ver el calendario de periodos.</div>
                    </div>

                    <!-- Group Calendar periods list, shown when a group is active -->
                    <div id="groupCalendarInfo" style="display: none; width: 100%;">
                        <h5 style="font-size: 0.875rem; font-weight: 600; color: #26687B; margin-bottom: 12px; border-bottom: 1px solid #E2E8F0; padding-bottom: 6px; display: flex; align-items: center; gap: 8px;">
                            <i class="ti ti-calendar" style="font-size: 16px;"></i> Calendario de Periodos
                        </h5>
                        <div id="groupPeriodsList" style="display: flex; flex-direction: column; gap: 8px; width: 100%;"></div>
                        
                        <!-- Fechas de Periodos de Evaluación (Solo grupos BTI) -->
                        <div class="mt-3 pt-3" id="seccionFechasEvaluacion" style="border-top: 1px dashed #CBD5E1; display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 style="font-size: 0.8125rem; font-weight: 600; color: #0F172A; margin: 0; display: flex; align-items: center; gap: 6px;">
                                    <i class="ti ti-calendar-check" style="font-size: 18px; color: #26687B;"></i> Fechas Evaluaciones
                                </h6>
                                <span id="badgeFechasEvaluacionStatus" class="badge-soft-muted">Cargando...</span>
                            </div>
                            
                            <div id="resumenFechasEvaluacionList" style="display: flex; flex-direction: column; gap: 6px; margin-bottom: 10px;">
                                <div class="text-center py-2" style="font-size: 0.75rem; color: #64748B;">
                                    <i class="ti ti-loader-2 ti-spin me-1"></i> Consultando fechas...
                                </div>
                            </div>

                            <button type="button" class="btn-eval-periodos" id="btnConfigurarFechasEvaluacion" onclick="abrirModalFechasEvaluacion()">
                                <i class="ti ti-calendar-plus" style="font-size: 16px;"></i> Fechas Periodos Evaluaciones
                            </button>
                        </div>
                        
                        <div class="eval-info-notice">
                            <i class="ti ti-info-circle" style="font-size: 16px; color: #64748B; flex-shrink: 0; margin-top: 1px;"></i>
                            <span>Selecciona una celda de horario para agregar o editar una clase.</span>
                        </div>
                    </div>
                </div>

                <!-- Edit Form -->
                <form id="editClassForm" class="edit-form" style="display: none;" onsubmit="return false;">
                    
                    <div class="form-group-custom">
                        <label>Grupo</label>
                        <input type="text" id="formGroupClave" class="form-control form-input-custom" readonly>
                    </div>

                    <div class="form-group-custom">
                        <label>Día</label>
                        <input type="text" id="formDayName" class="form-control form-input-custom" readonly>
                    </div>

                    <div class="form-group-custom">
                        <label>Horario</label>
                        <input type="text" id="formTimeSlot" class="form-control form-input-custom" readonly>
                    </div>

                    <!-- Materia Single Selection Container -->
                    <div id="singleMateriaContainer" class="form-group-custom">
                        <label>Materia *</label>
                        <select id="formMateriaSelect" class="form-select form-input-custom" required>
                            <option value="">Seleccione Materia</option>
                        </select>
                    </div>

                    <!-- Materia Multiple Selection Container -->
                    <div id="multipleMateriaContainer" class="form-group-custom" style="display: none;">
                        <label>Materias *</label>
                        <select id="formMateriaSelectMultiple" class="form-select form-input-custom" multiple>
                            <!-- options populated dynamically -->
                        </select>
                    </div>

                    <!-- Checkbox for multiple classes -->
                    <div class="form-check mb-2 mt-2">
                        <input class="form-check-input" type="checkbox" id="chkMultipleClasses">
                        <label class="form-check-label" for="chkMultipleClasses" style="font-size: 0.8125rem; font-weight: 500; color: #26687B;">
                            Impartir más de una materia en esta hora
                        </label>
                    </div>

                    <!-- Docente Selection (Always Single) -->
                    <div class="form-group-custom">
                        <label>Docente *</label>
                        <select id="formDocenteSelect" class="form-select form-input-custom" required>
                            <option value="">Seleccione Docente</option>
                        </select>
                    </div>

                    <div class="form-group-custom">
                        <label>Aula</label>
                        <select id="formAulaSelect" class="form-select form-input-custom">
                            <option value="">Seleccione Aula</option>
                            <option value="A-2">Aula A-2</option>
                            <option value="A-3">Aula A-3</option>
                            <option value="A-4">Aula A-4</option>
                            <option value="A-5">Aula A-5</option>
                            <option value="Lab. 1">Laboratorio Quimica</option>
                            <option value="Lab. 2">Audiovisual</option>
                            <option value="CentroComputo">Centro de Computo</option>
                        </select>
                    </div>

                    <div class="form-actions-custom">
                        <button type="button" class="btn-form-cancel" id="btnCancelEdit">Cancelar</button>
                        <button type="button" class="btn-form-save" id="btnSaveClass">Guardar</button>
                    </div>

                    <button type="button" class="btn-form-delete" id="btnDeleteClass" style="display: none;">
                        <i class="ti ti-trash"></i> Eliminar Clase
                    </button>

                </form>
            </div>
        </div>

    </div>

</div>

<!-- Modal Configuración Fechas Periodos Evaluaciones -->
<div class="modal fade" id="modalFechasPeriodosEvaluacion" tabindex="-1" aria-labelledby="modalFechasPeriodosEvaluacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 0; border: 1px solid var(--border-subtle); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); overflow: hidden;">
            <div class="modal-header px-4 py-3" style="border-bottom: 1px solid #E2E8F0;">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="modalFechasPeriodosEvaluacionLabel" style="font-size: 1rem; color: #0F172A;">
                    <i class="ti ti-calendar" style="font-size: 18px; color: #26687B;"></i>
                    <span>Fechas de Periodos de Evaluación — Grupo: <span id="modalEvalGrupoClave" class="badge px-2 py-1 ms-1" style="background: #EFF6F7; color: #26687B; border: 1px solid #BBDBE1; font-family: 'JetBrains Mono', monospace;"></span></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            
            <form id="formFechasPeriodosEvaluacion" onsubmit="guardarFechasPeriodosEvaluacion(event)">
                <div class="modal-body p-4" style="background-color: #F8FAFC; max-height: 75vh; overflow-y: auto;">
                    <div class="alert alert-info py-2 px-3 mb-3 d-flex align-items-center gap-2" style="font-size: 0.8rem; border-radius: 0; border-left: 4px solid #26687B; background: #EFF6F7; color: #184A54; border-color: #BBDBE1;">
                        <i class="ti ti-info-circle" style="font-size: 1.1rem; color: #26687B; flex-shrink: 0;"></i>
                        <div>
                            Define los periodos de evaluación para este grupo. Las fechas se sincronizan con las ventanas de captura y se reflejarán automáticamente en las <strong>Boletas Oficiales de Calificaciones</strong>.
                        </div>
                    </div>

                    <!-- Fila 1er Parcial -->
                    <div class="card mb-3 border-0 shadow-sm" style="border-radius: 0; overflow: hidden; border: 1px solid #E2E8F0 !important;">
                        <div class="card-header bg-white py-2 px-3 d-flex justify-content-between align-items-center border-bottom" style="border-left: 4px solid #26687B;">
                            <span class="fw-bold" style="font-size: 0.85rem; color: #0F172A;">
                                <i class="ti ti-number-1 me-1" style="color: #26687B;"></i> Primer Parcial (1ER. PARCIAL)
                            </span>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" id="evalP1Habilitado" checked>
                                <label class="form-check-label text-secondary small" for="evalP1Habilitado">Habilitar captura</label>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-secondary mb-1">Fecha de Inicio:</label>
                                    <input type="date" class="form-control form-control-sm" id="evalP1FechaInicio" style="border-radius: 0; font-family: 'JetBrains Mono', monospace;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-secondary mb-1">Fecha de Fin / Límite:</label>
                                    <input type="date" class="form-control form-control-sm" id="evalP1FechaFin" style="border-radius: 0; font-family: 'JetBrains Mono', monospace;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fila 2do Parcial -->
                    <div class="card mb-3 border-0 shadow-sm" style="border-radius: 0; overflow: hidden; border: 1px solid #E2E8F0 !important;">
                        <div class="card-header bg-white py-2 px-3 d-flex justify-content-between align-items-center border-bottom" style="border-left: 4px solid #26687B;">
                            <span class="fw-bold" style="font-size: 0.85rem; color: #0F172A;">
                                <i class="ti ti-number-2 me-1" style="color: #26687B;"></i> Segundo Parcial (2DO. PARCIAL)
                            </span>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" id="evalP2Habilitado" checked>
                                <label class="form-check-label text-secondary small" for="evalP2Habilitado">Habilitar captura</label>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-secondary mb-1">Fecha de Inicio:</label>
                                    <input type="date" class="form-control form-control-sm" id="evalP2FechaInicio" style="border-radius: 0; font-family: 'JetBrains Mono', monospace;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-secondary mb-1">Fecha de Fin / Límite:</label>
                                    <input type="date" class="form-control form-control-sm" id="evalP2FechaFin" style="border-radius: 0; font-family: 'JetBrains Mono', monospace;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fila 3er Parcial -->
                    <div class="card mb-3 border-0 shadow-sm" style="border-radius: 0; overflow: hidden; border: 1px solid #E2E8F0 !important;">
                        <div class="card-header bg-white py-2 px-3 d-flex justify-content-between align-items-center border-bottom" style="border-left: 4px solid #26687B;">
                            <span class="fw-bold" style="font-size: 0.85rem; color: #0F172A;">
                                <i class="ti ti-number-3 me-1" style="color: #26687B;"></i> Tercer Parcial (3ER. PARCIAL)
                            </span>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" id="evalP3Habilitado" checked>
                                <label class="form-check-label text-secondary small" for="evalP3Habilitado">Habilitar captura</label>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-secondary mb-1">Fecha de Inicio:</label>
                                    <input type="date" class="form-control form-control-sm" id="evalP3FechaInicio" style="border-radius: 0; font-family: 'JetBrains Mono', monospace;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-secondary mb-1">Fecha de Fin / Límite:</label>
                                    <input type="date" class="form-control form-control-sm" id="evalP3FechaFin" style="border-radius: 0; font-family: 'JetBrains Mono', monospace;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fila Semestral y Extraordinario (Grid de 2 columnas) -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm" style="border-radius: 0; overflow: hidden; border: 1px solid #E2E8F0 !important;">
                                <div class="card-header bg-white py-2 px-3 d-flex justify-content-between align-items-center border-bottom" style="border-left: 4px solid #0284c7;">
                                    <span class="fw-bold" style="font-size: 0.85rem; color: #0F172A;">
                                        <i class="ti ti-school me-1" style="color: #0284c7;"></i> Examen Semestral
                                    </span>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" id="evalSemestralHabilitado" checked>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <div class="mb-2">
                                        <label class="form-label small fw-semibold text-secondary mb-1">Fecha Inicio:</label>
                                        <input type="date" class="form-control form-control-sm" id="evalSemestralFechaInicio" style="border-radius: 0; font-family: 'JetBrains Mono', monospace;">
                                    </div>
                                    <div>
                                        <label class="form-label small fw-semibold text-secondary mb-1">Fecha Fin:</label>
                                        <input type="date" class="form-control form-control-sm" id="evalSemestralFechaFin" style="border-radius: 0; font-family: 'JetBrains Mono', monospace;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm" style="border-radius: 0; overflow: hidden; border: 1px solid #E2E8F0 !important;">
                                <div class="card-header bg-white py-2 px-3 d-flex justify-content-between align-items-center border-bottom" style="border-left: 4px solid #d97706;">
                                    <span class="fw-bold" style="font-size: 0.85rem; color: #0F172A;">
                                        <i class="ti ti-file-text me-1" style="color: #d97706;"></i> Extraordinario
                                    </span>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" id="evalExtraordinarioHabilitado" checked>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <div class="mb-2">
                                        <label class="form-label small fw-semibold text-secondary mb-1">Fecha Inicio:</label>
                                        <input type="date" class="form-control form-control-sm" id="evalExtraordinarioFechaInicio" style="border-radius: 0; font-family: 'JetBrains Mono', monospace;">
                                    </div>
                                    <div>
                                        <label class="form-label small fw-semibold text-secondary mb-1">Fecha Fin:</label>
                                        <input type="date" class="form-control form-control-sm" id="evalExtraordinarioFechaFin" style="border-radius: 0; font-family: 'JetBrains Mono', monospace;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Opción de Replicación a otros grupos del mismo semestre -->
                    <div class="p-3 bg-white rounded-3 border d-flex align-items-start gap-2 shadow-sm" style="border-color: #CBD5E1 !important;">
                        <input class="form-check-input mt-1" type="checkbox" id="evalReplicarMismoSemestre" checked style="cursor: pointer;">
                        <div>
                            <label class="form-check-label fw-bold mb-0" for="evalReplicarMismoSemestre" style="cursor: pointer; font-size: 0.85rem; color: #0F172A;">
                                <i class="ti ti-copy me-1" style="color: #26687B;"></i> Replicar estas fechas a todos los grupos de este mismo semestre / CCT
                            </label>
                            <div class="text-secondary small mt-1" style="font-size: 0.75rem;">
                                Si está marcado, se aplicará automáticamente este mismo calendario de evaluaciones a los grupos paralelos activos del mismo nivel académico.
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-white px-4 py-3 border-top d-flex justify-content-between">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal" style="border-radius: 0; height: 36px;">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-sm px-4 fw-medium text-white" id="btnGuardarFechasEvaluacionModal" style="background-color: #26687B; border-radius: 0; height: 36px; border: none;">
                        <i class="ti ti-device-floppy me-1"></i> Guardar Fechas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    let groups = [];
    let docentes = [];
    let materias = [];
    
    // schedules state stored by group key: groupClave -> { "Day-Time": { id, materiaId, docenteId, aula, subjectName, teacherName } }
    let schedulesData = {};
    
    let activeGroup = null;
    let selectedCell = null; // { day, timeIdx, element }
    let materiaSelectInstance = null;
    let materiaSelectMultipleInstance = null;
    let docenteSelectInstance = null;
    let selectedPeriodNivel = null;
    let currentPrehorarioMode = 0;

    window.debugState = function() {
        console.log("selectedPeriodNivel:", selectedPeriodNivel);
        console.log("activeGroup:", activeGroup);
        console.log("schedulesData for activeGroup:", activeGroup ? schedulesData[activeGroup.clave] : null);
        console.log("all schedulesData:", schedulesData);
    };

    function setPrehorarioMode(val) {
        currentPrehorarioMode = val;
        if (activeGroup) {
            if (val === 1) {
                document.getElementById("selectedGroupName").textContent = activeGroup.clave + " (Pre-Horario)";
            } else {
                document.getElementById("selectedGroupName").textContent = activeGroup.clave;
            }
        }
    }

    const dayNumbers = {
        "Lunes": 1,
        "Martes": 2,
        "Miércoles": 3,
        "Jueves": 4,
        "Viernes": 5,
        "Sábado": 6,
        "Domingo": 7
    };
    const daysMap = {
        1: "Lunes",
        2: "Martes",
        3: "Miércoles",
        4: "Jueves",
        5: "Viernes",
        6: "Sábado",
        7: "Domingo"
    };

    function getTimeIdx(horaInicio) {
        const prefix = horaInicio.substring(0, 5); // "HH:MM"
        return timeBlocks.findIndex(block => block.startsWith(prefix));
    }

    function getTeacherFullName(doc) {
        if (!doc) return 'Docente';
        return `${doc.nombreDocente} ${doc.apPaternoDocente || ''} ${doc.apMaternoDocente || ''}`.trim();
    }

    let timeBlocks = [
        "07:30 - 08:20",
        "08:20 - 09:10",
        "09:10 - 10:00",
        "10:00 - 10:30",
        "10:30 - 11:20",
        "11:20 - 12:10",
        "12:10 - 13:00",
        "13:00 - 13:50"
    ];

    // Default days
    let dayNames = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];

    // DOM Elements
    const groupListContainer = document.getElementById("groupListContainer");
    const groupListSpinner = document.getElementById("groupListSpinner");
    const groupSearchInput = document.getElementById("groupSearchInput");
    const filterStatus = document.getElementById("filterStatus");
    const filterCct = document.getElementById("filterCct");
    const selectedGroupName = document.getElementById("selectedGroupName");
    const selectedGroupNivel = document.getElementById("selectedGroupNivel");
    const calendarEmptyState = document.getElementById("calendarEmptyState");
    const calendarTable = document.getElementById("calendarTable");
    const calendarHeader = document.getElementById("calendarHeader");
    const calendarBody = document.getElementById("calendarBody");
    const editEmptyState = document.getElementById("editEmptyState");
    const editClassForm = document.getElementById("editClassForm");
    
    // Form fields
    const formGroupClave = document.getElementById("formGroupClave");
    const formDayName = document.getElementById("formDayName");
    const formTimeSlot = document.getElementById("formTimeSlot");
    const formMateriaSelect = document.getElementById("formMateriaSelect");
    const formMateriaSelectMultiple = document.getElementById("formMateriaSelectMultiple");
    const formDocenteSelect = document.getElementById("formDocenteSelect");
    const chkMultipleClasses = document.getElementById("chkMultipleClasses");
    const singleMateriaContainer = document.getElementById("singleMateriaContainer");
    const multipleMateriaContainer = document.getElementById("multipleMateriaContainer");

    // Action buttons
    const btnSaveClass = document.getElementById("btnSaveClass");
    const btnCancelEdit = document.getElementById("btnCancelEdit");
    const btnCloseEditPanel = document.getElementById("btnCloseEditPanel");
    const btnDeleteClass = document.getElementById("btnDeleteClass");
    const btnSaveChanges = document.getElementById("btnSaveChanges");

    // Fetch initial data
    initDashboard();

    async function initDashboard() {
        try {
            const [groupsRes, docentesRes, materiasRes, centrosRes] = await Promise.all([
                fetch('/grupos/lista?limit=1000').then(r => r.json()).catch(() => ({ data: [] })),
                fetch('/docentes/lista').then(r => r.json()).catch(() => ({ data: [] })),
                fetch('/materias/lista').then(r => r.json()).catch(() => ({ data: [] })),
                fetch('/catalogos/centros-trabajo').then(r => r.json()).catch(() => [])
            ]);

            groups = groupsRes.data || [];
            docentes = docentesRes.data || [];
            materias = materiasRes.data || [];
            const centros = centrosRes || [];

            // Populate CCT select
            if (filterCct) {
                filterCct.innerHTML = '<option value="ALL">Todos CCT</option>';
                centros.forEach(c => {
                    const opt = document.createElement("option");
                    opt.value = c.id;
                    opt.textContent = c.nombreCentroTrabajo || c.claveCentroTrabajo || c.nombre || `CCT ${c.id}`;
                    filterCct.appendChild(opt);
                });
            }

            populateDropdowns();
            applyFilters();

        } catch (error) {
            console.error("Error loading dashboard data:", error);
            groupListContainer.innerHTML = '<div class="text-danger text-center">Error al cargar datos.</div>';
        }
    }

    function populateDropdowns() {
        if (materiaSelectInstance) {
            materiaSelectInstance.destroy();
        }
        if (materiaSelectMultipleInstance) {
            materiaSelectMultipleInstance.destroy();
        }
        if (docenteSelectInstance) {
            docenteSelectInstance.destroy();
        }

        formMateriaSelect.innerHTML = '<option value="">Seleccione Materia</option>';
        formMateriaSelectMultiple.innerHTML = '';
        const filteredMaterias = materias.filter(mat => {
            // Filtrar por el Centro de Trabajo (CCT) del grupo activo
            if (activeGroup && activeGroup.id_centroTrabajo) {
                if (mat.idCentroTrabajo !== undefined && mat.idCentroTrabajo !== null && String(mat.idCentroTrabajo) !== String(activeGroup.id_centroTrabajo)) {
                    return false;
                }
            }
            if (!selectedPeriodNivel) return true;
            const matLvl = mat.id_nivel_academico;
            return matLvl === null || matLvl === undefined || String(matLvl) === String(selectedPeriodNivel);
        });

        filteredMaterias.forEach(mat => {
            const rawCct = mat.nombreCentroTrabajo || (mat.idCentroTrabajo === 3 ? 'BGNE' : (mat.idCentroTrabajo === 2 ? 'BTI' : (mat.idCentroTrabajo === 1 ? 'INF. Y COMP.' : '')));
            let cctNombre = rawCct;
            if (rawCct === 'INFORMATICA Y COMPUTACION') {
                cctNombre = 'INF. Y COMP.';
            }
            const cctSuffix = cctNombre ? ` (${cctNombre})` : '';

            const opt1 = document.createElement("option");
            opt1.value = mat.id_materia || mat.id || mat.nombreMateria;
            opt1.textContent = `${mat.nombreMateria}${cctSuffix}`;
            formMateriaSelect.appendChild(opt1);

            const opt2 = document.createElement("option");
            opt2.value = mat.id_materia || mat.id || mat.nombreMateria;
            opt2.textContent = `${mat.nombreMateria}${cctSuffix}`;
            formMateriaSelectMultiple.appendChild(opt2);
        });

        formDocenteSelect.innerHTML = '<option value="">Seleccione Docente</option>';
        docentes.forEach(doc => {
            const opt = document.createElement("option");
            const fullName = `${doc.nombreDocente} ${doc.apPaternoDocente || ''} ${doc.apMaternoDocente || ''}`.trim();
            opt.value = doc.idDocente || doc.id_docente || doc.id || fullName;
            opt.textContent = fullName;
            formDocenteSelect.appendChild(opt);
        });

        materiaSelectInstance = new TomSelect("#formMateriaSelect", {
            create: false,
            placeholder: "Buscar materia...",
            allowEmptyOption: true
        });

        materiaSelectMultipleInstance = new TomSelect("#formMateriaSelectMultiple", {
            create: false,
            placeholder: "Buscar materias...",
            plugins: ['remove_button']
        });

        docenteSelectInstance = new TomSelect("#formDocenteSelect", {
            create: false,
            placeholder: "Buscar docente...",
            allowEmptyOption: true
        });
    }

    chkMultipleClasses.addEventListener("change", function() {
        if (chkMultipleClasses.checked) {
            singleMateriaContainer.style.display = "none";
            multipleMateriaContainer.style.display = "block";
            formMateriaSelect.removeAttribute("required");
            formMateriaSelectMultiple.setAttribute("required", "required");
            
            if (materiaSelectMultipleInstance) {
                materiaSelectMultipleInstance.sync();
                materiaSelectMultipleInstance.refreshOptions(false);
            }
        } else {
            singleMateriaContainer.style.display = "block";
            multipleMateriaContainer.style.display = "none";
            formMateriaSelect.setAttribute("required", "required");
            formMateriaSelectMultiple.removeAttribute("required");
            
            if (materiaSelectInstance) {
                materiaSelectInstance.sync();
                materiaSelectInstance.refreshOptions(false);
            }
        }
    });

    function getCurrentPeriodText(g) {
        if (!g.fechaInicio) return '';

        let fechaInicioAbs = new Date(g.fechaInicio);
        if (isNaN(fechaInicioAbs.getTime())) return '';

        let currentDate = new Date(Date.UTC(
            fechaInicioAbs.getUTCFullYear(),
            fechaInicioAbs.getUTCMonth(),
            fechaInicioAbs.getUTCDate()
        ));

        let groupEndDate = null;
        if (g.fechaFin) {
            let fechaFinAbs = new Date(g.fechaFin);
            if (!isNaN(fechaFinAbs.getTime())) {
                groupEndDate = new Date(Date.UTC(
                    fechaFinAbs.getUTCFullYear(),
                    fechaFinAbs.getUTCMonth(),
                    fechaFinAbs.getUTCDate()
                ));
            }
        }

        let idTipoPeriodo = g.id_tipoPeriodo;
        let idNivelActualDb = g.id_nivel_academico;

        if (idTipoPeriodo === null || idTipoPeriodo === undefined) {
            if (idNivelActualDb !== null && idNivelActualDb >= 7) {
                idTipoPeriodo = 1; // SEMESTRAL
            } else {
                idTipoPeriodo = 2; // TRIMESTRAL (default)
            }
        }

        const isTrimestral = idTipoPeriodo === 2;
        if (!isTrimestral) {
            const semNum = idNivelActualDb ? (idNivelActualDb - 6) : 1;
            const today = new Date();
            const todayUTC = new Date(Date.UTC(today.getFullYear(), today.getMonth(), today.getDate()));
            if (g.fechaFin) {
                let fechaFinAbs = new Date(g.fechaFin);
                let groupEndDate = new Date(Date.UTC(
                    fechaFinAbs.getUTCFullYear(),
                    fechaFinAbs.getUTCMonth(),
                    fechaFinAbs.getUTCDate()
                ));
                if (todayUTC.getTime() > groupEndDate.getTime()) {
                    return `${semNum}° Sem. (Fin)`;
                }
            }
            return `${semNum}° Sem.`;
        }
        const weeksPerPeriod = isTrimestral ? 13 : 26;
        const periodLabel = isTrimestral ? "Trim." : "Sem.";

        const startLevel = isTrimestral ? 1 : 7;
        let idNivel = startLevel;

        const today = new Date();
        const todayUTC = new Date(Date.UTC(today.getFullYear(), today.getMonth(), today.getDate()));

        if (todayUTC.getTime() < currentDate.getTime()) {
            const periodNum = isTrimestral ? idNivel : (idNivel - 6);
            return `${periodNum}° ${periodLabel}`;
        }

        while (true) {
            // Todos los periodos duran exactamente (weeks - 1) * 7 días inclusive
            let periodEndDate = new Date(currentDate.getTime() + ((weeksPerPeriod - 1) * 7 * 24 * 60 * 60 * 1000));

            if (todayUTC.getTime() <= periodEndDate.getTime()) {
                const periodNum = isTrimestral ? idNivel : (idNivel - 6);
                return `${periodNum}° ${periodLabel}`;
            }

            if (groupEndDate && currentDate.getTime() > groupEndDate.getTime()) {
                break;
            }
            if (isTrimestral && idNivel > 6) break;
            if (!isTrimestral && idNivel > 12) break;

            if (groupEndDate && periodEndDate.getTime() >= groupEndDate.getTime()) {
                if (todayUTC.getTime() > groupEndDate.getTime()) {
                    const periodNum = isTrimestral ? idNivel : (idNivel - 6);
                    return `${periodNum}° ${periodLabel} (Fin)`;
                }
                break;
            }

            currentDate = new Date(periodEndDate.getTime() + (7 * 24 * 60 * 60 * 1000));
            idNivel++;
        }

        if (groupEndDate && todayUTC.getTime() > groupEndDate.getTime()) {
            return `Fin`;
        }

        return '';
    }

    function renderGroupsList(list) {
        groupListSpinner.style.display = 'none';
        
        if (list.length === 0) {
            groupListContainer.innerHTML = '<div class="text-secondary text-center py-3" style="font-size: 0.8125rem;">No hay grupos.</div>';
            return;
        }

        groupListContainer.innerHTML = '';
        list.forEach(group => {
            const btn = document.createElement("button");
            btn.className = "group-item";
            btn.setAttribute("data-group-id", group.id);
            btn.setAttribute("data-group-clave", group.clave);
            if (activeGroup && activeGroup.id === group.id) {
                btn.classList.add("active");
            }
            
            let periodText = getCurrentPeriodText(group)
                .replace("Trimestre", "Trim.")
                .replace("Semestre", "Sem.");
            const badgeHtml = periodText 
                ? `<span class="group-period-badge">${periodText}</span>` 
                : '';
                
            btn.innerHTML = `<span class="group-clave-text">${group.clave}</span>${badgeHtml}`;
            btn.addEventListener("click", () => selectGroup(group));
            groupListContainer.appendChild(btn);
        });
    }

    let searchDebounceTimeout = null;

    function renderGroupsAndStudentsList(filteredGroups, students) {
        renderGroupsList(filteredGroups);

        if (students.length === 0) return;

        const divider = document.createElement("div");
        divider.style.padding = "8px 12px 4px 12px";
        divider.style.fontSize = "0.7rem";
        divider.style.fontWeight = "700";
        divider.style.color = "#64748B";
        divider.style.textTransform = "uppercase";
        divider.style.borderTop = "1px dashed #CBD5E1";
        divider.style.marginTop = "10px";
        divider.style.letterSpacing = "0.5px";
        divider.innerHTML = `<i class="ti ti-school me-1" style="color: #10B981;"></i> Alumnos Encontrados`;
        groupListContainer.appendChild(divider);

        students.forEach(student => {
            const studentGroupId = student.idGrupo || student.id_Grupo;
            const matchedGroup = groups.find(g => g.id === studentGroupId);
            if (!matchedGroup) return;

            const btn = document.createElement("button");
            btn.className = "group-item";
            btn.setAttribute("data-group-id", matchedGroup.id);
            btn.setAttribute("data-group-clave", matchedGroup.clave);
            if (activeGroup && activeGroup.id === matchedGroup.id) {
                btn.classList.add("active");
            }
            btn.style.borderLeft = "3.5px solid #10B981";
            btn.style.padding = "8px 12px";
            btn.style.marginTop = "5px";
            btn.style.background = "rgba(16, 185, 129, 0.04)";

            btn.innerHTML = `
                <div style="text-align: left;">
                    <div style="font-weight: 600; font-size: 0.8rem; color: #0F172A; display: flex; align-items: center; gap: 4px;">
                        <i class="ti ti-user" style="color: #10B981; font-size: 0.85rem;"></i>
                        ${student.nombre} ${student.apPaterno}
                    </div>
                    <div style="font-size: 0.6875rem; color: #64748B; margin-top: 2px;">Grupo: <strong style="font-family: 'JetBrains Mono', monospace;">${matchedGroup.clave}</strong></div>
                </div>
                <span style="font-size: 0.65rem; background: rgba(16, 185, 129, 0.12); color: #047857; padding: 2px 7px; border-radius: 0; font-weight: 600;">Ir al grupo</span>
            `;

            btn.addEventListener("click", () => {
                selectGroup(matchedGroup);
            });

            groupListContainer.appendChild(btn);
        });
    }

    function applyFilters() {
        const query = groupSearchInput.value.toLowerCase().trim();
        const statusFilter = filterStatus ? filterStatus.value : 'ACTIVO';
        const cctFilter = filterCct ? filterCct.value : 'ALL';

        // Filter groups locally
        const filteredGroups = groups.filter(g => {
            // 1. Search query filter
            const clave = g.clave ? String(g.clave).toLowerCase() : '';
            if (query && !clave.includes(query)) {
                return false;
            }

            // 2. Status filter (ACTIVO, INACTIVO)
            if (statusFilter !== 'ALL') {
                const groupStatus = g.statusGrupo ? String(g.statusGrupo).toUpperCase() : 'ACTIVO';
                if (groupStatus !== statusFilter) {
                    return false;
                }
            }

            // 3. CCT filter (id_centroTrabajo)
            if (cctFilter !== 'ALL') {
                const gCct = g.id_centroTrabajo || g.idCentroTrabajo;
                if (String(gCct) !== String(cctFilter)) {
                    return false;
                }
            }

            return true;
        });

        renderGroupsList(filteredGroups);

        // If query length >= 3, search students with debounce
        if (query.length >= 3) {
            clearTimeout(searchDebounceTimeout);
            searchDebounceTimeout = setTimeout(() => {
                fetch(`/alumnos/lista?limit=5&search=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(resp => {
                        const students = resp.data || [];
                        if (students.length > 0) {
                            const studentGroupIds = students.map(s => s.idGrupo || s.id_Grupo).filter(Boolean);
                            
                            // Find groups matching the students and applying the active filters
                            const matchedGroupsByStudents = groups.filter(g => {
                                if (statusFilter !== 'ALL') {
                                    const groupStatus = g.statusGrupo ? String(g.statusGrupo).toUpperCase() : 'ACTIVO';
                                    if (groupStatus !== statusFilter) return false;
                                }
                                if (cctFilter !== 'ALL') {
                                    const gCct = g.id_centroTrabajo || g.idCentroTrabajo;
                                    if (String(gCct) !== String(cctFilter)) return false;
                                }
                                return studentGroupIds.includes(g.id);
                            });
                            
                            // Combine with filteredGroups (avoiding duplicates)
                            const combinedGroups = [...filteredGroups];
                            matchedGroupsByStudents.forEach(mg => {
                                if (!combinedGroups.some(cg => cg.id === mg.id)) {
                                    combinedGroups.push(mg);
                                }
                            });

                            renderGroupsAndStudentsList(combinedGroups, students);
                        }
                    })
                    .catch(err => console.error("Error al buscar alumnos en horarios:", err));
            }, 300);
        }
    }

    if (groupSearchInput) groupSearchInput.addEventListener("input", applyFilters);
    if (filterStatus) filterStatus.addEventListener("change", applyFilters);
    if (filterCct) filterCct.addEventListener("change", applyFilters);

    function generateTableHeader(days) {
        const headerRow = document.createElement("tr");
        
        // Columna "Hora"
        const thTime = document.createElement("th");
        thTime.textContent = "Hora";
        headerRow.appendChild(thTime);

        // Columnas para días activos
        days.forEach(day => {
            const th = document.createElement("th");
            th.textContent = day;
            headerRow.appendChild(th);
        });

        calendarHeader.innerHTML = "";
        calendarHeader.appendChild(headerRow);
    }

    function selectGroup(group) {
        activeGroup = group;
        selectedGroupName.textContent = group.clave;
        
        setPrehorarioMode(0);

        // Pre-calculate selectedPeriodNivel synchronously to prevent race conditions during async fetching
        const calculatedSync = calcularPeriodoNivel(group);
        if (calculatedSync) {
            selectedPeriodNivel = calculatedSync.idNivel;
        } else {
            selectedPeriodNivel = null;
        }
        
        // Ocultar elementos temporalmente hasta que se carguen los detalles extendidos
        document.getElementById("progressContainer").style.display = "none";
        selectedGroupNivel.style.display = "none";
        const seccionEvalInit = document.getElementById("seccionFechasEvaluacion");
        if (seccionEvalInit) seccionEvalInit.style.display = "none";
        
        const items = groupListContainer.getElementsByClassName("group-item");
        Array.from(items).forEach(item => {
            if (item.getAttribute("data-group-id") == group.id) {
                item.classList.add("active");
            } else {
                item.classList.remove("active");
            }
        });

        // Determinar días basados en la clave del grupo (BGNE -> Sábado, Domingo; BTI y escolarizado -> Lunes a Viernes)
        const isBgne = group.clave.toUpperCase().startsWith("BGNE");
        dayNames = isBgne ? ["Sábado", "Domingo"] : ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];

        // Configurar bloques de horario basados en si es BGNE o BTI/Escolarizado
        if (isBgne) {
            timeBlocks = [
                "09:00 - 10:00",
                "10:00 - 11:00",
                "11:00 - 12:00",
                "12:00 - 13:00",
                "13:00 - 14:00",
                "14:00 - 15:00",
                "15:00 - 16:00",
                "16:00 - 17:00",
                "17:00 - 18:00",
                "18:00 - 19:00"
            ];
        } else {
            // Grupos BTI y escolarizados: 07:30 a 13:50 con Receso de 10:00 a 10:30
            timeBlocks = [
                "07:30 - 08:20",
                "08:20 - 09:10",
                "09:10 - 10:00",
                "10:00 - 10:30",
                "10:30 - 11:20",
                "11:20 - 12:10",
                "12:10 - 13:00",
                "13:00 - 13:50"
            ];
        }

        // Generar cabecera y grilla dinámicamente
        generateTableHeader(dayNames);
        generateCalendarGridHTML(dayNames);

        calendarEmptyState.style.display = "none";
        calendarTable.style.display = "table";

        clearForm();
        renderActiveGroupClasses();

        // Cargar detalles extendidos para el Trimestre y Barra de Progreso
        fetch(`/grupos/${group.id}`)
            .then(res => res.json())
            .then(resp => {
                if (resp.success && resp.data) {
                    const g = resp.data;
                    
                    // Mostrar nivel académico dinámico según calendario de fechas
                    const activePeriodText = getCurrentPeriodText(g);
                    if (activePeriodText) {
                        selectedGroupNivel.textContent = activePeriodText;
                        selectedGroupNivel.style.display = "inline-block";
                    } else {
                        selectedGroupNivel.style.display = "none";
                    }
                    
                    // Calcular y mostrar barra de progreso
                    let fechaInicioNivel = g.fechaInicioNivel;
                    let fechaFinNivel = g.fechaFinNivel;
                    
                    const calculated = calcularPeriodoNivel(g);
                    if (calculated) {
                        selectedPeriodNivel = calculated.idNivel;
                        if (!fechaInicioNivel || !fechaFinNivel) {
                            fechaInicioNivel = calculated.fechaInicioNivel;
                            fechaFinNivel = calculated.fechaFinNivel;
                        }
                    } else {
                        selectedPeriodNivel = null;
                    }
                    
                    if (fechaInicioNivel && fechaFinNivel) {
                        const progressContainer = document.getElementById("progressContainer");
                        const progressStartDate = document.getElementById("progressStartDate");
                        const progressEndDate = document.getElementById("progressEndDate");
                        const progressPercent = document.getElementById("progressPercent");
                        const progressBar = document.getElementById("progressBar");
                        
                        const formatDateDMY = (dateStr) => {
                            if (!dateStr) return '—';
                            const parts = dateStr.split('-');
                            return parts.length === 3 ? `${parts[2]}/${parts[1]}/${parts[0]}` : dateStr;
                        };
                        
                        progressStartDate.textContent = formatDateDMY(fechaInicioNivel);
                        progressEndDate.textContent = formatDateDMY(fechaFinNivel);
                        
                        const parseDateUTC = (dateStr) => {
                            const parts = dateStr.split('-');
                            return new Date(Date.UTC(parts[0], parts[1] - 1, parts[2]));
                        };
                        
                        const start = parseDateUTC(fechaInicioNivel);
                        const end = parseDateUTC(fechaFinNivel);
                        const now = new Date();
                        const today = new Date(Date.UTC(now.getFullYear(), now.getMonth(), now.getDate()));
                        
                        const total = end.getTime() - start.getTime();
                        const elapsed = today.getTime() - start.getTime();
                        
                        let percent = 0;
                        if (total > 0) {
                            percent = Math.round((elapsed / total) * 100);
                            percent = Math.max(0, Math.min(100, percent));
                        }
                        
                        progressPercent.textContent = `${percent}%`;
                        progressBar.style.width = `${percent}%`;
                        progressBar.setAttribute("aria-valuenow", percent);
                        progressContainer.style.display = "block";
                    }
                    // Render dynamically calculated group calendar in right panel empty state
                    renderGroupCalendar(g);
                    redrawCalendarGrid();

                    // La sección de fechas de evaluación solo aplica para grupos del CCT BTI
                    const esBti = esGrupoBti(group, g);
                    const seccionEval = document.getElementById("seccionFechasEvaluacion");
                    if (seccionEval) {
                        seccionEval.style.display = esBti ? "block" : "none";
                    }
                    if (esBti) {
                        window.cargarConfiguracionFechasEvaluacion(group.id);
                    }
                }
            })
            .catch(err => {
                console.error("Error al obtener progreso de nivel:", err);
            });
    }

    function esGrupoBti(group, gDetails) {
        if (!group && !gDetails) return false;
        const cctId = parseInt((gDetails && gDetails.id_centroTrabajo) || (group && group.id_centroTrabajo) || 0);
        if (cctId === 2) return true;
        if (cctId === 3 || cctId === 1) return false;

        const cctNombre = String((gDetails && gDetails.nombreCentroTrabajo) || (group && group.nombreCentroTrabajo) || '').toUpperCase();
        if (cctNombre.includes('BTI')) return true;
        if (cctNombre.includes('BGNE')) return false;

        const clave = String((group && group.clave) || (gDetails && gDetails.clave) || '').toUpperCase();
        if (clave.startsWith('BTI')) return true;
        if (clave.startsWith('BGNE')) return false;

        return false;
    }

    window.currentGrupoConfig = null;

    window.cargarConfiguracionFechasEvaluacion = function(grupoId) {
        const seccionEval = document.getElementById("seccionFechasEvaluacion");
        if (!activeGroup || !esGrupoBti(activeGroup)) {
            if (seccionEval) seccionEval.style.display = "none";
            return;
        }
        if (seccionEval) seccionEval.style.display = "block";

        const badge = document.getElementById("badgeFechasEvaluacionStatus");
        const container = document.getElementById("resumenFechasEvaluacionList");
        if (!container || !badge) return;

        badge.className = "badge-soft-muted";
        badge.textContent = "Cargando...";
        container.innerHTML = `
            <div class="text-secondary text-center py-2" style="font-size: 0.75rem;">
                <i class="ti ti-loader-2 ti-spin me-1"></i> Consultando fechas...
            </div>
        `;

        fetch(`/permisos-captura/grupo-config/${grupoId}`)
            .then(res => res.json())
            .then(resp => {
                if (resp && resp.success && resp.config) {
                    window.currentGrupoConfig = resp.config;
                    renderResumenFechasEvaluacion(resp.config);
                } else {
                    window.currentGrupoConfig = null;
                    badge.className = "badge-soft-muted";
                    badge.textContent = "Sin fechas";
                    container.innerHTML = `
                        <div class="text-muted text-center py-2" style="font-size: 0.75rem;">
                            No se encontraron periodos configurados.
                        </div>
                    `;
                }
            })
            .catch(err => {
                console.error("Error al cargar configuración de captura:", err);
                badge.className = "badge-soft-muted";
                badge.textContent = "Error";
                container.innerHTML = `
                    <div class="text-danger text-center py-2" style="font-size: 0.75rem;">
                        No fue posible consultar las fechas.
                    </div>
                `;
            });
    };

    function formatDMY(dateStr) {
        if (!dateStr) return null;
        const s = String(dateStr).trim();
        const parts = s.substring(0, 10).split('-');
        if (parts.length === 3) {
            return `${parts[2]}/${parts[1]}/${parts[0]}`;
        }
        return s;
    }

    function renderResumenFechasEvaluacion(cfg) {
        const badge = document.getElementById("badgeFechasEvaluacionStatus");
        const container = document.getElementById("resumenFechasEvaluacionList");
        if (!container || !badge) return;

        const fmtRango = (ini, fin) => {
            const dIni = formatDMY(ini);
            const dFin = formatDMY(fin);
            if (dIni && dFin) return `${dIni} al ${dFin}`;
            if (dIni) return `Desde ${dIni}`;
            if (dFin) return `Hasta ${dFin}`;
            return `<span style="color: #94A3B8; font-style: italic;">Sin definir</span>`;
        };

        const hasAnyDate = cfg.p1_fecha_inicio || cfg.p2_fecha_inicio || cfg.p3_fecha_inicio || cfg.semestral_fecha_inicio || cfg.extraordinario_fecha_inicio;
        if (hasAnyDate) {
            badge.className = "badge-soft-success";
            badge.textContent = "Configurado";
        } else {
            badge.className = "badge-soft-muted";
            badge.textContent = "Sin fechas";
        }

        container.innerHTML = `
            <div class="eval-card">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <strong class="eval-card-title">1ER. PARCIAL</strong>
                    <span class="${cfg.p1_habilitado ? 'badge-soft-success' : 'badge-soft-muted'}">${cfg.p1_habilitado ? 'Activo' : 'Cerrado'}</span>
                </div>
                <div class="eval-dates-mono">${fmtRango(cfg.p1_fecha_inicio, cfg.p1_fecha_fin)}</div>
            </div>

            <div class="eval-card">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <strong class="eval-card-title">2DO. PARCIAL</strong>
                    <span class="${cfg.p2_habilitado ? 'badge-soft-success' : 'badge-soft-muted'}">${cfg.p2_habilitado ? 'Activo' : 'Cerrado'}</span>
                </div>
                <div class="eval-dates-mono">${fmtRango(cfg.p2_fecha_inicio, cfg.p2_fecha_fin)}</div>
            </div>

            <div class="eval-card">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <strong class="eval-card-title">3ER. PARCIAL</strong>
                    <span class="${cfg.p3_habilitado ? 'badge-soft-success' : 'badge-soft-muted'}">${cfg.p3_habilitado ? 'Activo' : 'Cerrado'}</span>
                </div>
                <div class="eval-dates-mono">${fmtRango(cfg.p3_fecha_inicio, cfg.p3_fecha_fin)}</div>
            </div>

            <div class="eval-card">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <strong class="eval-card-title">SEMESTRAL / EXTRA</strong>
                </div>
                <div class="eval-dates-mono">
                    Sem: ${fmtRango(cfg.semestral_fecha_inicio, cfg.semestral_fecha_fin)}
                </div>
                <div class="eval-dates-mono">
                    Ext: ${fmtRango(cfg.extraordinario_fecha_inicio, cfg.extraordinario_fecha_fin)}
                </div>
            </div>
        `;
    }

    window.abrirModalFechasEvaluacion = function() {
        if (!activeGroup) {
            Swal.fire({
                icon: 'info',
                title: 'Selecciona un Grupo',
                text: 'Por favor selecciona un grupo del panel izquierdo para configurar sus periodos de evaluación.',
                confirmButtonColor: '#2e596b'
            });
            return;
        }

        if (!esGrupoBti(activeGroup)) {
            Swal.fire({
                icon: 'warning',
                title: 'No Aplica',
                text: 'La sección de fechas de evaluación solo aplica para grupos pertenecientes al CCT BTI.',
                confirmButtonColor: '#2e596b'
            });
            return;
        }

        document.getElementById("modalEvalGrupoClave").textContent = activeGroup.clave;

        const cfg = window.currentGrupoConfig || {};
        document.getElementById("evalP1FechaInicio").value = cfg.p1_fecha_inicio ? cfg.p1_fecha_inicio.substring(0, 10) : '';
        document.getElementById("evalP1FechaFin").value = cfg.p1_fecha_fin ? cfg.p1_fecha_fin.substring(0, 10) : '';
        document.getElementById("evalP1Habilitado").checked = cfg.p1_habilitado === undefined ? true : (cfg.p1_habilitado == 1);

        document.getElementById("evalP2FechaInicio").value = cfg.p2_fecha_inicio ? cfg.p2_fecha_inicio.substring(0, 10) : '';
        document.getElementById("evalP2FechaFin").value = cfg.p2_fecha_fin ? cfg.p2_fecha_fin.substring(0, 10) : '';
        document.getElementById("evalP2Habilitado").checked = cfg.p2_habilitado === undefined ? true : (cfg.p2_habilitado == 1);

        document.getElementById("evalP3FechaInicio").value = cfg.p3_fecha_inicio ? cfg.p3_fecha_inicio.substring(0, 10) : '';
        document.getElementById("evalP3FechaFin").value = cfg.p3_fecha_fin ? cfg.p3_fecha_fin.substring(0, 10) : '';
        document.getElementById("evalP3Habilitado").checked = cfg.p3_habilitado === undefined ? true : (cfg.p3_habilitado == 1);

        document.getElementById("evalSemestralFechaInicio").value = cfg.semestral_fecha_inicio ? cfg.semestral_fecha_inicio.substring(0, 10) : '';
        document.getElementById("evalSemestralFechaFin").value = cfg.semestral_fecha_fin ? cfg.semestral_fecha_fin.substring(0, 10) : '';
        document.getElementById("evalSemestralHabilitado").checked = cfg.semestral_habilitado === undefined ? true : (cfg.semestral_habilitado == 1);

        document.getElementById("evalExtraordinarioFechaInicio").value = cfg.extraordinario_fecha_inicio ? cfg.extraordinario_fecha_inicio.substring(0, 10) : '';
        document.getElementById("evalExtraordinarioFechaFin").value = cfg.extraordinario_fecha_fin ? cfg.extraordinario_fecha_fin.substring(0, 10) : '';
        document.getElementById("evalExtraordinarioHabilitado").checked = cfg.extraordinario_habilitado === undefined ? true : (cfg.extraordinario_habilitado == 1);

        const modalEl = document.getElementById("modalFechasPeriodosEvaluacion");
        let modalInstance = bootstrap.Modal.getInstance(modalEl);
        if (!modalInstance) {
            modalInstance = new bootstrap.Modal(modalEl);
        }
        modalInstance.show();
    };

    window.guardarFechasPeriodosEvaluacion = function(event) {
        event.preventDefault();
        if (!activeGroup) return;

        const btn = document.getElementById("btnGuardarFechasEvaluacionModal");
        const originalBtnHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `<i class="ti ti-loader-2 ti-spin me-1"></i> Guardando...`;

        const payload = {
            id_nivel_academico: selectedPeriodNivel || activeGroup.id_nivel_academico,
            captura_habilitada: 1,
            p1_habilitado: document.getElementById("evalP1Habilitado").checked ? 1 : 0,
            p1_fecha_inicio: document.getElementById("evalP1FechaInicio").value || null,
            p1_fecha_fin: document.getElementById("evalP1FechaFin").value || null,
            p2_habilitado: document.getElementById("evalP2Habilitado").checked ? 1 : 0,
            p2_fecha_inicio: document.getElementById("evalP2FechaInicio").value || null,
            p2_fecha_fin: document.getElementById("evalP2FechaFin").value || null,
            p3_habilitado: document.getElementById("evalP3Habilitado").checked ? 1 : 0,
            p3_fecha_inicio: document.getElementById("evalP3FechaInicio").value || null,
            p3_fecha_fin: document.getElementById("evalP3FechaFin").value || null,
            semestral_habilitado: document.getElementById("evalSemestralHabilitado").checked ? 1 : 0,
            semestral_fecha_inicio: document.getElementById("evalSemestralFechaInicio").value || null,
            semestral_fecha_fin: document.getElementById("evalSemestralFechaFin").value || null,
            extraordinario_habilitado: document.getElementById("evalExtraordinarioHabilitado").checked ? 1 : 0,
            extraordinario_fecha_inicio: document.getElementById("evalExtraordinarioFechaInicio").value || null,
            extraordinario_fecha_fin: document.getElementById("evalExtraordinarioFechaFin").value || null,
            replicar_mismo_semestre: document.getElementById("evalReplicarMismoSemestre").checked ? 1 : 0
        };

        const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '';

        fetch(`/permisos-captura/grupo-config/${activeGroup.id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(resp => {
            btn.disabled = false;
            btn.innerHTML = originalBtnHtml;

            if (resp && resp.success) {
                const modalEl = document.getElementById("modalFechasPeriodosEvaluacion");
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) {
                    modalInstance.hide();
                }

                Swal.fire({
                    icon: 'success',
                    title: '¡Fechas Guardadas!',
                    text: resp.message || 'Periodos de evaluación actualizados exitosamente.',
                    timer: 2500,
                    showConfirmButton: false
                });

                window.cargarConfiguracionFechasEvaluacion(activeGroup.id);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: (resp && resp.message) ? resp.message : (resp && resp.error ? resp.error : 'Ocurrió un error al guardar las fechas.'),
                    confirmButtonColor: '#2e596b'
                });
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = originalBtnHtml;
            console.error("Error al guardar fechas de evaluación:", err);
            Swal.fire({
                icon: 'error',
                title: 'Error de Red',
                text: 'No se pudo comunicar con el servidor para guardar las fechas.',
                confirmButtonColor: '#2e596b'
            });
        });
    };

    function calcularPeriodoNivel(g) {
        if (!g.fechaInicio) return null;
        
        const parseToUTCDate = (dateVal) => {
            if (!dateVal) return null;
            if (dateVal instanceof Date) {
                return new Date(Date.UTC(dateVal.getFullYear(), dateVal.getMonth(), dateVal.getDate()));
            }
            const dateStr = String(dateVal).trim();
            if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr) || /^\d{4}-\d{2}-\d{2}\s/.test(dateStr)) {
                const parts = dateStr.substring(0, 10).split('-');
                return new Date(Date.UTC(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2])));
            }
            const d = new Date(dateStr);
            if (isNaN(d.getTime())) return null;
            if (dateStr.includes('GMT') || dateStr.endsWith('Z')) {
                return new Date(Date.UTC(d.getUTCFullYear(), d.getUTCMonth(), d.getUTCDate()));
            } else {
                return new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()));
            }
        };

        let fechaInicioNivel = parseToUTCDate(g.fechaInicio);
        if (!fechaInicioNivel) return null;
        
        let idTipoPeriodo = g.id_tipoPeriodo;
        let idNivelActualDb = g.id_nivel_academico;
        
        if (idTipoPeriodo === null || idTipoPeriodo === undefined) {
            if (idNivelActualDb !== null && idNivelActualDb >= 7) {
                idTipoPeriodo = 1; // SEMESTRAL
            } else {
                idTipoPeriodo = 2; // TRIMESTRAL (default)
            }
        }
        
        let idNivel = idTipoPeriodo === 2 ? 1 : 7;
        const isTrimestral = idTipoPeriodo === 2;
        if (!isTrimestral) {
            const formatISO = (d) => {
                const yyyy = d.getUTCFullYear();
                const mm = String(d.getUTCMonth() + 1).padStart(2, '0');
                const dd = String(d.getUTCDate()).padStart(2, '0');
                return `${yyyy}-${mm}-${dd}`;
            };
            let startD = parseToUTCDate(g.fechaInicio);
            let endD = parseToUTCDate(g.fechaFin) || startD;
            return {
                fechaInicioNivel: formatISO(startD),
                fechaFinNivel: formatISO(endD),
                idNivel: idNivelActualDb || 7
            };
        }
        
        const now = new Date();
        const today = new Date(Date.UTC(now.getFullYear(), now.getMonth(), now.getDate()));
        
        let fechaFinNivel = new Date(fechaInicioNivel.getTime());
        
        const getDuracionSemanas = (levelId) => {
            if (levelId >= 1 && levelId <= 6) return 13; // Trimestral
            if (levelId >= 7 && levelId <= 12) return 28; // Semestral (duración 28 semanas)
            return 13;
        };
        
        const hasNextNivel = (levelId) => {
            if (idTipoPeriodo === 2) return levelId < 6;
            if (idTipoPeriodo === 1) return levelId < 12;
            return false;
        };
        
        while (true) {
            const duracionSemanas = getDuracionSemanas(idNivel);
            
            // Todos los periodos duran exactamente (weeks - 1) * 7 días inclusive
            fechaFinNivel = new Date(fechaInicioNivel.getTime() + ((duracionSemanas - 1) * 7 * 24 * 60 * 60 * 1000));
            
            if (today.getTime() <= fechaFinNivel.getTime()) {
                break;
            }
            
            if (!hasNextNivel(idNivel)) {
                break;
            }
            
            // El siguiente periodo empieza una semana después del fin del actual
            fechaInicioNivel = new Date(fechaFinNivel.getTime() + (7 * 24 * 60 * 60 * 1000));
            idNivel = idNivel + 1;
        }
        
        // Capping at group's official fechaFin
        let groupEndDate = parseToUTCDate(g.fechaFin);
        if (groupEndDate && fechaFinNivel > groupEndDate) {
            fechaFinNivel = groupEndDate;
        }
        if (groupEndDate && fechaInicioNivel > groupEndDate) {
            fechaInicioNivel = groupEndDate;
        }

        const toISODate = (d) => {
            const yyyy = d.getUTCFullYear();
            const mm = String(d.getUTCMonth() + 1).padStart(2, '0');
            const dd = String(d.getUTCDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        };
        
        return {
            fechaInicioNivel: toISODate(fechaInicioNivel),
            fechaFinNivel: toISODate(fechaFinNivel),
            idNivel: idNivel
        };
    }

    function renderGroupCalendar(g) {
        const calendarInfo = document.getElementById("groupCalendarInfo");
        const noGroupMsg = document.getElementById("noGroupSelectedMessage");
        const periodsList = document.getElementById("groupPeriodsList");

        if (!g || !g.fechaInicio) {
            if (calendarInfo) calendarInfo.style.display = "none";
            if (noGroupMsg) noGroupMsg.style.display = "block";
            return;
        }

        if (noGroupMsg) noGroupMsg.style.display = "none";
        if (calendarInfo) calendarInfo.style.display = "block";
        if (!periodsList) return;
        
        periodsList.innerHTML = "";

        // Parse start and end dates
        let fechaInicioAbs = new Date(g.fechaInicio);
        if (isNaN(fechaInicioAbs.getTime())) {
            periodsList.innerHTML = '<div class="text-danger">Fecha de inicio inválida.</div>';
            return;
        }

        // Pure UTC date
        let currentDate = new Date(Date.UTC(
            fechaInicioAbs.getUTCFullYear(),
            fechaInicioAbs.getUTCMonth(),
            fechaInicioAbs.getUTCDate()
        ));

        let groupEndDate = null;
        if (g.fechaFin) {
            let fechaFinAbs = new Date(g.fechaFin);
            if (!isNaN(fechaFinAbs.getTime())) {
                groupEndDate = new Date(Date.UTC(
                    fechaFinAbs.getUTCFullYear(),
                    fechaFinAbs.getUTCMonth(),
                    fechaFinAbs.getUTCDate()
                ));
            }
        }

        let idTipoPeriodo = g.id_tipoPeriodo;
        let idNivelActualDb = g.id_nivel_academico;

        // If not defined, fallback based on academic level
        if (idTipoPeriodo === null || idTipoPeriodo === undefined) {
            if (idNivelActualDb !== null && idNivelActualDb >= 7) {
                idTipoPeriodo = 1; // SEMESTRAL
            } else {
                idTipoPeriodo = 2; // TRIMESTRAL (default)
            }
        }

        const isTrimestral = idTipoPeriodo === 2;
        if (!isTrimestral) {
            const semNum = idNivelActualDb ? (idNivelActualDb - 6) : 1;
            const periodItem = document.createElement("div");
            periodItem.style.padding = "10px 12px";
            periodItem.style.borderRadius = "10px";
            periodItem.style.transition = "all 0.2s ease";
            periodItem.style.cursor = "pointer";
            
            periodItem.style.border = "2px solid rgb(38, 104, 123)";
            periodItem.style.background = "rgba(38, 104, 123, 0.12)";
            periodItem.style.color = "#0f172a";
            periodItem.style.opacity = "1";
            periodItem.style.boxShadow = "0 2px 8px rgba(38,104,123,0.15)";

            const titleColor = "rgb(38, 104, 123)";
            const dateColor = "#1e293b";
            const iconColor = "rgb(38, 104, 123)";

            const formatDateDMY = (dateObj) => {
                const dd = String(dateObj.getUTCDate()).padStart(2, '0');
                const mm = String(dateObj.getUTCMonth() + 1).padStart(2, '0');
                const yyyy = dateObj.getUTCFullYear();
                return `${dd}/${mm}/${yyyy}`;
            };

            periodItem.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <span style="font-weight: 700; font-size: 0.85rem; color: ${titleColor};">
                        ${semNum}° Semestre (Nivel ${idNivelActualDb || 7})
                    </span>
                    <span class="badge-soft-success">Activo</span>
                </div>
                <div style="font-size: 0.75rem; color: ${dateColor}; display: flex; align-items: center; gap: 6px;">
                    <i class="ti ti-calendar-event" style="color: ${iconColor}; font-size: 14px;"></i>
                    <span style="font-family: 'JetBrains Mono', monospace;">${formatDateDMY(currentDate)}</span>
                    <span style="color: #cbd5e1;">&rarr;</span>
                    <span style="font-family: 'JetBrains Mono', monospace;">${formatDateDMY(groupEndDate || currentDate)}</span>
                </div>
            `;

            periodsList.appendChild(periodItem);
            return;
        }
        const weeksPerPeriod = isTrimestral ? 13 : 26;
        const periodNameSingular = isTrimestral ? "Trimestre" : "Semestre";
        const startLevel = isTrimestral ? 1 : 7;

        let idNivel = startLevel;

        const formatDateDMY = (dateObj) => {
            const dd = String(dateObj.getUTCDate()).padStart(2, '0');
            const mm = String(dateObj.getUTCMonth() + 1).padStart(2, '0');
            const yyyy = dateObj.getUTCFullYear();
            return `${dd}/${mm}/${yyyy}`;
        };

        const today = new Date();
        const todayUTC = new Date(Date.UTC(today.getFullYear(), today.getMonth(), today.getDate()));

        // Calcular el nivel activo usando el helper
        const calculated = calcularPeriodoNivel(g);
        const activeNivel = calculated ? calculated.idNivel : null;

        while (true) {
            const periodNumber = isTrimestral ? idNivel : (idNivel - 6);
            // El periodo termina (weeksPerPeriod - 1) * 7 días después de su fecha de inicio
            let periodEndDate = new Date(currentDate.getTime() + ((weeksPerPeriod - 1) * 7 * 24 * 60 * 60 * 1000));

            // Si definimos un fin de grupo y el inicio de este periodo ya excede la fecha fin, paramos
            if (groupEndDate && currentDate.getTime() > groupEndDate.getTime()) {
                break;
            }

            // Si el periodo excede el límite académico
            if (isTrimestral && idNivel > 6) break;
            if (!isTrimestral && idNivel > 12) break;

            const isSelected = idNivel === selectedPeriodNivel;
            const isCurrent = idNivel === activeNivel;
            const isPast = activeNivel !== null && idNivel < activeNivel;

            const periodItem = document.createElement("div");
            periodItem.style.padding = "10px 12px";
            periodItem.style.borderRadius = "10px";
            periodItem.style.transition = "all 0.2s ease";
            periodItem.style.cursor = "pointer";

            if (isSelected) {
                // Seleccionado actualmente para ver: borde institucional grueso y fondo destacado
                periodItem.style.border = "2px solid rgb(38, 104, 123)";
                periodItem.style.background = "rgba(38, 104, 123, 0.12)";
                periodItem.style.color = "#0f172a";
                periodItem.style.opacity = "1";
                periodItem.style.boxShadow = "0 2px 8px rgba(38,104,123,0.15)";
            } else if (isCurrent) {
                // Periodo activo pero no seleccionado: borde azul sutil
                periodItem.style.border = "1.5px solid rgba(38, 104, 123, 0.4)";
                periodItem.style.background = "rgba(38, 104, 123, 0.03)";
                periodItem.style.color = "#334155";
                periodItem.style.opacity = "1";
            } else if (isPast) {
                // Periodo finalizado: borde discontinuo y ligeramente opaco
                periodItem.style.border = "1px dashed #cbd5e1";
                periodItem.style.background = "rgba(241, 245, 249, 0.4)";
                periodItem.style.color = "#64748b";
                periodItem.style.opacity = "0.75";
            } else {
                // Periodo futuro: borde estándar
                periodItem.style.border = "1px solid #e2e8f0";
                periodItem.style.background = "#ffffff";
                periodItem.style.color = "#334155";
                periodItem.style.opacity = "1";
            }

            // Hover effects
            periodItem.addEventListener("mouseenter", () => {
                if (!isSelected) {
                    periodItem.style.background = "rgba(38, 104, 123, 0.05)";
                    periodItem.style.borderColor = "rgba(38, 104, 123, 0.5)";
                }
            });
            periodItem.addEventListener("mouseleave", () => {
                if (!isSelected) {
                    if (isCurrent) {
                        periodItem.style.border = "1.5px solid rgba(38, 104, 123, 0.4)";
                        periodItem.style.background = "rgba(38, 104, 123, 0.03)";
                    } else if (isPast) {
                        periodItem.style.border = "1px dashed #cbd5e1";
                        periodItem.style.background = "rgba(241, 245, 249, 0.4)";
                    } else {
                        periodItem.style.border = "1px solid #e2e8f0";
                        periodItem.style.background = "#ffffff";
                    }
                }
            });

            // Click action
            const targetNivel = idNivel;
            const isFuture = !isCurrent && !isPast;
            const isBgne = g.clave.toUpperCase().startsWith("BGNE");
            periodItem.addEventListener("click", () => {
                selectedPeriodNivel = targetNivel;
                setPrehorarioMode(isFuture && isBgne ? 1 : 0);
                renderGroupCalendar(g);
                redrawCalendarGrid();
                renderActiveGroupClasses();
            });

            let badgeHtml = "";
            if (isCurrent) {
                badgeHtml = '<span class="badge-soft-success">Activo</span>';
            } else if (isPast) {
                badgeHtml = '<span class="badge-soft-muted">Finalizado</span>';
            } else {
                // Periodo futuro: colocar botón "Anticipar Horario" si es BGNE
                if (isBgne) {
                    badgeHtml = '<button type="button" class="btn btn-warning btn-xs py-0 px-2 fw-bold text-dark btn-anticipar-horario" style="font-size: 0.65rem; border-radius: 0; border: none; height: 18px; line-height: 18px; display: inline-flex; align-items: center; justify-content: center;">Anticipar Horario</button>';
                }
            }

            const titleColor = isSelected ? "var(--brand-primary)" : (isCurrent ? "var(--brand-primary)" : (isPast ? "#64748b" : "#1e293b"));
            const dateColor = isSelected ? "#1e293b" : (isCurrent ? "#334155" : (isPast ? "#8c9ba5" : "#475569"));
            const iconColor = isSelected ? "var(--brand-primary)" : (isCurrent ? "var(--brand-primary)" : (isPast ? "#94a3b8" : "rgb(56, 189, 248)"));

            periodItem.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <span style="font-weight: 700; font-size: 0.85rem; color: ${titleColor};">
                        ${periodNumber}° ${periodNameSingular} (Nivel ${idNivel})
                    </span>
                    ${badgeHtml}
                </div>
                <div style="font-size: 0.75rem; color: ${dateColor}; display: flex; align-items: center; gap: 6px;">
                    <i class="ti ti-calendar-event" style="color: ${iconColor}; font-size: 14px;"></i>
                    <span style="font-family: 'JetBrains Mono', monospace;">${formatDateDMY(currentDate)}</span>
                    <span style="color: #cbd5e1;">&rarr;</span>
                    <span style="font-family: 'JetBrains Mono', monospace;">${formatDateDMY(periodEndDate)}</span>
                </div>
            `;

            periodsList.appendChild(periodItem);

            // Si el periodo sobrepasa la fechaFin del grupo (o coincide con ella), paramos
            if (groupEndDate && periodEndDate.getTime() >= groupEndDate.getTime()) {
                break;
            }

            // Siguiente periodo empieza exactamente 1 semana después de que finaliza el actual
            currentDate = new Date(periodEndDate.getTime() + (7 * 24 * 60 * 60 * 1000));
            idNivel++;
        }
    }

    function generateCalendarGridHTML(days) {
        calendarBody.innerHTML = '';
        timeBlocks.forEach((time, index) => {
            const tr = document.createElement("tr");
            const isReceso = time.includes("10:00 - 10:30") || time.toLowerCase().includes("receso");

            if (isReceso) {
                tr.className = "receso-row";
                const tdTime = document.createElement("td");
                tdTime.className = "time-col receso-time-col";
                tdTime.textContent = time;
                tr.appendChild(tdTime);

                const tdReceso = document.createElement("td");
                tdReceso.colSpan = days.length;
                tdReceso.className = "receso-cell";
                tdReceso.innerHTML = `
                    <div class="receso-banner">
                        <i class="ti ti-coffee me-1"></i>
                        <span>RECESO</span>
                    </div>
                `;
                tr.appendChild(tdReceso);
            } else {
                const tdTime = document.createElement("td");
                tdTime.className = "time-col";
                tdTime.textContent = time;
                tr.appendChild(tdTime);

                days.forEach(day => {
                    const td = document.createElement("td");
                    
                    const slotDiv = document.createElement("div");
                    slotDiv.className = "cell-slot";
                    slotDiv.dataset.day = day;
                    slotDiv.dataset.time = time;
                    slotDiv.dataset.timeIdx = index;
                    
                    slotDiv.innerHTML = `
                        <span class="placeholder-dash">—</span>
                        <i class="ti ti-plus add-btn-icon"></i>
                    `;

                    slotDiv.addEventListener("click", () => handleCellClick(day, index, slotDiv));

                    td.appendChild(slotDiv);
                    tr.appendChild(td);
                });
            }

            calendarBody.appendChild(tr);
        });
    }

    function handleCellClick(day, timeIdx, element) {
        if (!activeGroup) return;

        // Re-populate dropdowns dynamically to apply level-based filters
        populateDropdowns();

        if (selectedCell) {
            selectedCell.element.classList.remove("selected");
        }

        selectedCell = { day, timeIdx, element };
        element.classList.add("selected");

        editEmptyState.style.display = "none";
        editClassForm.style.display = "flex";

        formGroupClave.value = activeGroup.clave;
        formDayName.value = day;
        formTimeSlot.value = timeBlocks[timeIdx];

        const groupData = schedulesData[activeGroup.clave] || {};
        const cellKey = `${day}-${timeIdx}`;
        let existingClass = groupData[cellKey];

        if (existingClass) {
            // Normalizar a formato agrupado si no lo tiene
            if (!existingClass.clases) {
                existingClass.clases = [{
                    id_horario: existingClass.id,
                    id_materia: existingClass.materiaId,
                    materia_nombre: existingClass.subjectName,
                    id_docente: existingClass.docenteId,
                    docente_nombre: existingClass.teacherName,
                    id_nivel_materia: existingClass.id_nivel_materia
                }];
            }

            // Filtrar clases que corresponden al nivel seleccionado
            const levelClases = existingClass.clases.filter(c => {
                return c.id_nivel_materia === undefined || c.id_nivel_materia === null || c.id_nivel_materia === selectedPeriodNivel;
            });

            if (levelClases.length > 0) {
                existingClass = {
                    ...existingClass,
                    clases: levelClases
                };

                if (existingClass.clases.length > 1) {
                    chkMultipleClasses.checked = true;
                    singleMateriaContainer.style.display = "none";
                    multipleMateriaContainer.style.display = "block";
                    formMateriaSelect.removeAttribute("required");
                    formMateriaSelectMultiple.setAttribute("required", "required");

                    // Set multiple materia values
                    const matIds = existingClass.clases.map(c => String(c.id_materia));
                    if (materiaSelectMultipleInstance) materiaSelectMultipleInstance.setValue(matIds);
                    if (materiaSelectInstance) materiaSelectInstance.setValue("");
                } else {
                    chkMultipleClasses.checked = false;
                    singleMateriaContainer.style.display = "block";
                    multipleMateriaContainer.style.display = "none";
                    formMateriaSelect.setAttribute("required", "required");
                    formMateriaSelectMultiple.removeAttribute("required");

                    const singleClase = existingClass.clases[0];
                    if (singleClase) {
                        if (materiaSelectInstance) materiaSelectInstance.setValue(singleClase.id_materia);
                    } else {
                        if (materiaSelectInstance) materiaSelectInstance.setValue("");
                    }
                    if (materiaSelectMultipleInstance) materiaSelectMultipleInstance.setValue([]);
                }
                
                // Popolar docente (compartido) y aula
                const firstClase = existingClass.clases[0];
                const firstClassDocente = firstClase ? firstClase.id_docente : (existingClass.id_docente || "");
                const firstClassAula = firstClase ? firstClase.aula : (existingClass.aula || "");
                if (docenteSelectInstance) docenteSelectInstance.setValue(firstClassDocente);
                formAulaSelect.value = firstClassAula || "";
                btnDeleteClass.style.display = "block";
            } else {
                chkMultipleClasses.checked = false;
                singleMateriaContainer.style.display = "block";
                multipleMateriaContainer.style.display = "none";
                formMateriaSelect.setAttribute("required", "required");
                formMateriaSelectMultiple.removeAttribute("required");
                
                if (materiaSelectInstance) materiaSelectInstance.setValue("");
                if (materiaSelectMultipleInstance) materiaSelectMultipleInstance.setValue([]);
                if (docenteSelectInstance) docenteSelectInstance.setValue("");
                formAulaSelect.value = "";
                btnDeleteClass.style.display = "none";
            }
        } else {
            chkMultipleClasses.checked = false;
            singleMateriaContainer.style.display = "block";
            multipleMateriaContainer.style.display = "none";
            formMateriaSelect.setAttribute("required", "required");
            formMateriaSelectMultiple.removeAttribute("required");
            
            if (materiaSelectInstance) materiaSelectInstance.setValue("");
            if (materiaSelectMultipleInstance) materiaSelectMultipleInstance.setValue([]);
            if (docenteSelectInstance) docenteSelectInstance.setValue("");
            formAulaSelect.value = "";
            btnDeleteClass.style.display = "none";
        }
    }

    btnSaveClass.addEventListener("click", async function() {
        if (!selectedCell || !activeGroup) return;

        const aula = formAulaSelect.value;
        if (!aula) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos requeridos',
                text: 'El aula es obligatoria.',
                confirmButtonColor: 'rgb(38, 104, 123)'
            });
            return;
        }

        const timeSlot = timeBlocks[selectedCell.timeIdx];
        const [startStr, endStr] = timeSlot.split(" - ");
        const horaInicio = startStr + ":00";
        const horaFin = endStr + ":00";

        let payload = {
            id_grupo: activeGroup.id || activeGroup.id_grupo,
            diaSemana: dayNumbers[selectedCell.day],
            horaInicio: horaInicio,
            horaFin: horaFin,
            aula: aula,
            es_prehorario: currentPrehorarioMode
        };

        const docenteId = formDocenteSelect.value;
        if (!docenteId) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos requeridos',
                text: 'El docente es obligatorio.',
                confirmButtonColor: 'rgb(38, 104, 123)'
            });
            return;
        }
        payload.id_docente = parseInt(docenteId);

        let materiasList = [];

        if (chkMultipleClasses.checked) {
            const selectedValues = materiaSelectMultipleInstance ? materiaSelectMultipleInstance.getValue() : [];
            if (!selectedValues || selectedValues.length === 0 || (selectedValues.length === 1 && selectedValues[0] === "")) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Faltan Materias',
                    text: 'Debes seleccionar al menos una materia.',
                    confirmButtonColor: 'rgb(38, 104, 123)'
                });
                return;
            }
            materiasList = selectedValues.filter(v => v !== "").map(v => parseInt(v));
            payload.materias = materiasList;
        } else {
            const materiaId = formMateriaSelect.value;
            if (!materiaId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos requeridos',
                    text: 'La materia es obligatoria.',
                    confirmButtonColor: 'rgb(38, 104, 123)'
                });
                return;
            }
            payload.id_materia = parseInt(materiaId);
            materiasList = [parseInt(materiaId)];
        }

        btnSaveClass.disabled = true;

        const groupData = schedulesData[activeGroup.clave] || {};
        const cellKey = `${selectedCell.day}-${selectedCell.timeIdx}`;
        const existingClass = groupData[cellKey];
        const excludeIds = (existingClass && existingClass.clases)
            ? existingClass.clases.map(c => c.id_horario).filter(Boolean)
            : [];

        try {
            // Validar disponibilidad del docente para cada materia en el backend
            for (let matId of materiasList) {
                const valRes = await fetch('/horarios/validar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        id_grupo: activeGroup.id || activeGroup.id_grupo,
                        id_materia: matId,
                        id_docente: parseInt(docenteId),
                        diaSemana: dayNumbers[selectedCell.day],
                        horaInicio: horaInicio,
                        horaFin: horaFin,
                        es_prehorario: currentPrehorarioMode,
                        aula: aula,
                        exclude_ids: excludeIds
                    })
                });

                if (valRes.ok) {
                    const valData = await valRes.json();
                    if (valData.success === false) {
                        const confirmResult = await Swal.fire({
                            icon: 'warning',
                            title: 'Conflicto de Horario',
                            html: `${valData.mensaje}<br><br>¿Deseas asignarlo de todas formas?`,
                            showCancelButton: true,
                            confirmButtonColor: 'rgb(38, 104, 123)',
                            cancelButtonColor: '#cbd5e1',
                            confirmButtonText: 'Sí, asignar',
                            cancelButtonText: 'Cancelar'
                        });

                        if (!confirmResult.isConfirmed) {
                            btnSaveClass.disabled = false;
                            return;
                        }
                    }
                }
            }

            // If it exists in backend, delete all previous classes first
            if (existingClass && existingClass.clases && existingClass.clases.length > 0) {
                for (let clase of existingClass.clases) {
                    if (clase.id_nivel_materia === undefined || clase.id_nivel_materia === null || clase.id_nivel_materia === selectedPeriodNivel) {
                        const delRes = await fetch(`/horarios/${clase.id_horario}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        if (!delRes.ok) throw new Error("Error al remover horario previo.");
                    }
                }
            } else if (existingClass && existingClass.id) {
                // Fallback for single class delete
                if (existingClass.id_nivel_materia === undefined || existingClass.id_nivel_materia === null || existingClass.id_nivel_materia === selectedPeriodNivel) {
                    const delRes = await fetch(`/horarios/${existingClass.id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    if (!delRes.ok) throw new Error("Error al remover horario previo.");
                }
            }

            const saveRes = await fetch('/horarios', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(payload)
            });

            if (!saveRes.ok) {
                let errMsg = "Error al guardar el horario en el backend.";
                try {
                    const errData = await saveRes.json();
                    if (errData.error) errMsg += " Detalles: " + (typeof errData.error === 'object' ? JSON.stringify(errData.error) : errData.error);
                    else if (errData.message) errMsg += " Detalles: " + (typeof errData.message === 'object' ? JSON.stringify(errData.message) : errData.message);
                } catch(e) {}
                errMsg += " \n\nPayload enviado: " + JSON.stringify(payload);
                throw new Error(errMsg);
            }

            // Reload all group schedules to sync correctly
            await renderActiveGroupClasses();
            clearForm();
            Swal.fire({
                icon: 'success',
                title: '¡Guardado!',
                text: 'La clase ha sido guardada correctamente.',
                confirmButtonColor: 'rgb(38, 104, 123)'
            });

        } catch (error) {
            console.error("Error al guardar clase:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || "Error al guardar la clase.",
                confirmButtonColor: 'rgb(38, 104, 123)'
            });
        } finally {
            btnSaveClass.disabled = false;
        }
    });

    btnDeleteClass.addEventListener("click", function() {
        if (!selectedCell || !activeGroup) return;

        const cellKey = `${selectedCell.day}-${selectedCell.timeIdx}`;
        const existingClass = schedulesData[activeGroup.clave] ? schedulesData[activeGroup.clave][cellKey] : null;

        if (existingClass) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta clase y todas sus materias asociadas se eliminarán del horario permanentemente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'rgb(38, 104, 123)',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    btnDeleteClass.disabled = true;
                    try {
                        // Filter the list of classes to delete based on the selected level
                        const listToDelete = (existingClass.clases || []).filter(c => {
                            return c.id_nivel_materia === undefined || c.id_nivel_materia === null || c.id_nivel_materia === selectedPeriodNivel;
                        });
                        
                        if (listToDelete.length === 0 && existingClass.id) {
                            if (existingClass.id_nivel_materia === undefined || existingClass.id_nivel_materia === null || existingClass.id_nivel_materia === selectedPeriodNivel) {
                                listToDelete.push({ id_horario: existingClass.id });
                            }
                        }

                        for (let clase of listToDelete) {
                            if (clase.id_horario) {
                                const response = await fetch(`/horarios/${clase.id_horario}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                    }
                                });

                                if (!response.ok) {
                                    let errMsg = "Error al eliminar la clase del servidor backend.";
                                    try {
                                        const errData = await response.json();
                                        if (errData.error) errMsg += " Detalles: " + errData.error;
                                        else if (errData.message) errMsg += " Detalles: " + errData.message;
                                    } catch(e) {}
                                    throw new Error(errMsg);
                                }
                            }
                        }

                        // Remove deleted classes from in-memory cache
                        if (schedulesData[activeGroup.clave] && schedulesData[activeGroup.clave][cellKey]) {
                            const remainingClases = (schedulesData[activeGroup.clave][cellKey].clases || []).filter(c => {
                                return c.id_nivel_materia !== undefined && c.id_nivel_materia !== null && c.id_nivel_materia !== selectedPeriodNivel;
                            });
                            if (remainingClases.length > 0) {
                                schedulesData[activeGroup.clave][cellKey].clases = remainingClases;
                            } else {
                                delete schedulesData[activeGroup.clave][cellKey];
                            }
                        }
                        
                        redrawCalendarGrid();
                        clearForm();
                        
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: 'La clase ha sido eliminada del horario.',
                            confirmButtonColor: 'rgb(38, 104, 123)'
                        });
                    } catch (error) {
                        console.error("Error al eliminar clase:", error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message || "Error al eliminar la clase.",
                            confirmButtonColor: 'rgb(38, 104, 123)'
                        });
                    } finally {
                        btnDeleteClass.disabled = false;
                    }
                }
            });
        } else {
            // Just added locally, not persisted
            selectedCell.element.innerHTML = `
                <span class="placeholder-dash">—</span>
                <i class="ti ti-plus add-btn-icon"></i>
            `;
            clearForm();
        }
    });

    btnCancelEdit.addEventListener("click", clearForm);
    btnCloseEditPanel.addEventListener("click", clearForm);

    btnSaveChanges.addEventListener("click", function() {
        if (activeGroup) {
            renderActiveGroupClasses();
        }
    });

    function redrawCalendarGrid() {
        const cells = calendarBody.getElementsByClassName("cell-slot");
        Array.from(cells).forEach(cell => {
            cell.innerHTML = `
                <span class="placeholder-dash">—</span>
                <i class="ti ti-plus add-btn-icon"></i>
            `;
            cell.classList.remove("selected");
        });

        if (!activeGroup || !schedulesData[activeGroup.clave]) return;

        const cellsArray = Array.from(cells);
        console.log("--- REDRAW GRID ---");
        console.log("DOM Cell Keys:", cellsArray.map(c => `${c.dataset.day}-${c.dataset.timeIdx}`));
        
        Object.entries(schedulesData[activeGroup.clave]).forEach(([cellKey, classData]) => {
            const [dayName, timeIdxStr] = cellKey.split('-');
            const timeIdx = parseInt(timeIdxStr);

            console.log(`Raw classes for ${cellKey}:`, (classData.clases || []).map(c => ({ nombre: c.materia_nombre, level: c.id_nivel_materia, levelType: typeof c.id_nivel_materia })));
            console.log(`selectedPeriodNivel:`, selectedPeriodNivel, "type:", typeof selectedPeriodNivel);

            // Filter classes by selected level
            const filteredClases = (classData.clases || []).filter(c => {
                return c.id_nivel_materia === undefined || c.id_nivel_materia === null || c.id_nivel_materia === selectedPeriodNivel;
            });

            console.log(`Data Key ${cellKey}: filteredClases:`, filteredClases.map(c => c.materia_nombre));

            if (filteredClases.length > 0) {
                const cellElement = cellsArray.find(c => c.dataset.day === dayName && c.dataset.timeIdx == timeIdx);
                console.log(`Match for ${dayName}-${timeIdx}:`, cellElement ? "FOUND" : "NOT FOUND");
                if (cellElement) {
                    const filteredClassData = {
                        ...classData,
                        clases: filteredClases
                    };
                    renderClassCardInCell(cellElement, filteredClassData);
                }
            }
        });
    }

    async function renderActiveGroupClasses() {
        if (!activeGroup) {
            // Clear cells if no active group
            const cells = calendarBody.getElementsByClassName("cell-slot");
            Array.from(cells).forEach(cell => {
                cell.innerHTML = `
                    <span class="placeholder-dash">—</span>
                    <i class="ti ti-plus add-btn-icon"></i>
                `;
                cell.classList.remove("selected");
            });
            return;
        }

        const groupId = activeGroup.id || activeGroup.id_grupo;
        if (!groupId) return;

        try {
            const response = await fetch(`/horarios/grupo/${groupId}?agrupado=true&es_prehorario=${currentPrehorarioMode}`);
            if (!response.ok) throw new Error("Error al obtener los horarios");
            const data = await response.json();

            // Store in schedulesData in memory
            schedulesData[activeGroup.clave] = {};

            data.forEach(item => {
                const dayName = daysMap[item.diaSemana];
                const timeIdx = getTimeIdx(item.horaInicio);

                if (dayName && timeIdx !== -1) {
                    const cellKey = `${dayName}-${timeIdx}`;
                    
                    if (!schedulesData[activeGroup.clave][cellKey]) {
                        schedulesData[activeGroup.clave][cellKey] = {
                            diaSemana: item.diaSemana,
                            horaInicio: item.horaInicio,
                            horaFin: item.horaFin,
                            aula: item.aula || "",
                            id_docente: item.id_docente,
                            docente_nombre: item.docente_nombre || "",
                            clases: []
                        };
                    }
                    
                    if (item.clases && item.clases.length > 0) {
                        item.clases.forEach(c => {
                            if (!schedulesData[activeGroup.clave][cellKey].clases.some(existing => existing.id_horario === c.id_horario)) {
                                schedulesData[activeGroup.clave][cellKey].clases.push(c);
                            }
                        });
                    }
                    
                    if (item.id_docente && !schedulesData[activeGroup.clave][cellKey].id_docente) {
                        schedulesData[activeGroup.clave][cellKey].id_docente = item.id_docente;
                        schedulesData[activeGroup.clave][cellKey].docente_nombre = item.docente_nombre || "";
                    }
                    if (item.aula && !schedulesData[activeGroup.clave][cellKey].aula) {
                        schedulesData[activeGroup.clave][cellKey].aula = item.aula;
                    }
                }
            });
            
            redrawCalendarGrid();
        } catch (error) {
            console.error("Error al cargar horarios del grupo:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al cargar los horarios desde el servidor.',
                confirmButtonColor: '#26687B'
            });
        }
    }

    function darkenColor(hex, percent) {
        if (!hex || hex === '#FFFFFF') return '#cbd5e1';
        hex = hex.replace(/^\s*#|\s*$/g, '');
        if (hex.length === 3) {
            hex = hex.replace(/(.)/g, '$1$1');
        }
        let r = parseInt(hex.substr(0, 2), 16),
            g = parseInt(hex.substr(2, 2), 16),
            b = parseInt(hex.substr(4, 2), 16);

        r = Math.max(0, Math.min(255, r - (r * (percent / 100))));
        g = Math.max(0, Math.min(255, g - (g * (percent / 100))));
        b = Math.max(0, Math.min(255, b - (b * (percent / 100))));

        return '#' + 
            ('0' + Math.round(r).toString(16)).slice(-2) + 
            ('0' + Math.round(g).toString(16)).slice(-2) + 
            ('0' + Math.round(b).toString(16)).slice(-2);
    }

    function renderClassCardInCell(cellElement, data) {
        const firstClaseColor = (data.clases[0] && data.clases[0].docente_color) || data.docente_color || '';
        
        let cardStyle = '';
        if (firstClaseColor && firstClaseColor !== '#FFFFFF') {
            const darkBorder = darkenColor(firstClaseColor, 12);
            const leftBorder = darkenColor(firstClaseColor, 35);
            cardStyle = `style="background-color: ${firstClaseColor} !important; border-color: ${darkBorder} !important; border-left-color: ${leftBorder} !important;"`;
        }
        
        let html = `<div class="class-card" draggable="true" ${cardStyle}>`;
        data.clases.forEach((clase, idx) => {
            if (idx > 0) {
                html += '<hr style="margin: 4px 0; opacity: 0.15; border-color: var(--brand-primary);">';
            }
            const teacherName = clase.docente_nombre || data.docente_nombre || '';
            const aula = clase.aula || data.aula || '';
            const aulaBadge = aula ? ` <span class="aula-badge">${aula}</span>` : '';
            
            // If the card has a custom color, force readable dark colors for text
            const subjectStyle = firstClaseColor && firstClaseColor !== '#FFFFFF' ? 'style="font-size: 0.8125rem; font-weight: 700; line-height: 1.25; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 4px; color: #0F172A !important;"' : 'style="font-size: 0.8125rem; font-weight: 700; line-height: 1.25; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 4px;"';
            const detailStyle = firstClaseColor && firstClaseColor !== '#FFFFFF' ? 'style="font-size: 0.72rem; color: #334155 !important; line-height: 1.2; margin-top: 2px;"' : 'style="font-size: 0.72rem; color: #475569; line-height: 1.2; margin-top: 2px;"';
            const iconStyle = firstClaseColor && firstClaseColor !== '#FFFFFF' ? 'style="color: #475569 !important;"' : '';
            
            html += `
                <div class="class-subject" ${subjectStyle}>
                    <span>${clase.materia_nombre}</span>
                    ${aulaBadge}
                </div>
                <div class="class-detail" ${detailStyle}><i class="ti ti-user" ${iconStyle}></i> ${teacherName}</div>
            `;
        });
        html += '</div>';
        cellElement.innerHTML = html;
    }

    function clearForm() {
        if (selectedCell) {
            selectedCell.element.classList.remove("selected");
            selectedCell = null;
        }

        editClassForm.style.display = "none";
        editEmptyState.style.display = "flex";
        renderGroupCalendar(activeGroup);
        
        if (materiaSelectInstance) materiaSelectInstance.setValue("");
        if (docenteSelectInstance) docenteSelectInstance.setValue("");
        formAulaSelect.value = "";
        btnDeleteClass.style.display = "none";

        // Limpiar controles y estados de múltiples clases
        chkMultipleClasses.checked = false;
        singleMateriaContainer.style.display = "block";
        multipleMateriaContainer.style.display = "none";
        formMateriaSelect.setAttribute("required", "required");
        formMateriaSelectMultiple.removeAttribute("required");
        if (materiaSelectMultipleInstance) materiaSelectMultipleInstance.setValue([]);
    }

    // ==========================================
    // Drag & Drop Event Handlers (Event Delegation)
    // ==========================================
    calendarTable.addEventListener("dragstart", function(e) {
        const card = e.target.closest(".class-card");
        if (!card) return;
        
        const cell = card.closest(".cell-slot");
        if (!cell) return;
        
        card.classList.add("dragging");
        
        e.dataTransfer.setData("text/plain", JSON.stringify({
            day: cell.dataset.day,
            timeIdx: parseInt(cell.dataset.timeIdx)
        }));
        e.dataTransfer.effectAllowed = "move";
    });

    calendarTable.addEventListener("dragend", function(e) {
        const card = e.target.closest(".class-card");
        if (card) {
            card.classList.remove("dragging");
        }
        const cells = calendarBody.querySelectorAll(".cell-slot.drag-over");
        cells.forEach(c => c.classList.remove("drag-over"));
    });

    calendarTable.addEventListener("dragover", function(e) {
        const cell = e.target.closest(".cell-slot");
        if (!cell) return;
        
        e.preventDefault();
        e.dataTransfer.dropEffect = "move";
    });

    calendarTable.addEventListener("dragenter", function(e) {
        const cell = e.target.closest(".cell-slot");
        if (!cell) return;
        
        cell.classList.add("drag-over");
    });

    calendarTable.addEventListener("dragleave", function(e) {
        const cell = e.target.closest(".cell-slot");
        if (cell && !cell.contains(e.relatedTarget)) {
            cell.classList.remove("drag-over");
        }
    });

    calendarTable.addEventListener("drop", async function(e) {
        const cell = e.target.closest(".cell-slot");
        if (!cell) return;
        
        cell.classList.remove("drag-over");
        
        try {
            const dragDataStr = e.dataTransfer.getData("text/plain");
            if (!dragDataStr) return;
            const dragData = JSON.parse(dragDataStr);
            
            const sourceDay = dragData.day;
            const sourceTimeIdx = dragData.timeIdx;
            
            const targetDay = cell.dataset.day;
            const targetTimeIdx = parseInt(cell.dataset.timeIdx);
            
            if (sourceDay === targetDay && sourceTimeIdx === targetTimeIdx) {
                return;
            }
            
            await moveClass(sourceDay, sourceTimeIdx, targetDay, targetTimeIdx);
        } catch(err) {
            console.error("Error al procesar el drop:", err);
        }
    });

    async function moveClass(sourceDay, sourceTimeIdx, targetDay, targetTimeIdx) {
        if (!activeGroup) return;

        const groupData = schedulesData[activeGroup.clave] || {};
        const sourceCellKey = `${sourceDay}-${sourceTimeIdx}`;
        const sourceData = groupData[sourceCellKey];
        
        if (!sourceData || !sourceData.clases || sourceData.clases.length === 0) {
            return;
        }

        const timeSlot = timeBlocks[targetTimeIdx];
        const [startStr, endStr] = timeSlot.split(" - ");
        const horaInicio = startStr + ":00";
        const horaFin = endStr + ":00";
        
        const targetCellKey = `${targetDay}-${targetTimeIdx}`;
        const targetExistingClass = groupData[targetCellKey];
        
        // 1. Si la celda de destino ya tiene clases, confirmar reemplazo
        if (targetExistingClass && targetExistingClass.clases && targetExistingClass.clases.length > 0) {
            const replaceConfirm = await Swal.fire({
                title: 'Horario ocupado',
                text: 'La celda de destino ya tiene clases asignadas. ¿Deseas reemplazarlas?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: 'rgb(38, 104, 123)',
                cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Sí, reemplazar',
                cancelButtonText: 'Cancelar'
            });
            if (!replaceConfirm.isConfirmed) {
                return;
            }
        }
        
        // 2. Validar disponibilidad del docente en el nuevo horario
        const materiasList = sourceData.clases.map(c => parseInt(c.id_materia));
        const docenteId = sourceData.id_docente || (sourceData.clases[0] ? sourceData.clases[0].id_docente : null);
        const excludeIds = (sourceData && sourceData.clases)
            ? sourceData.clases.map(c => c.id_horario).filter(Boolean)
            : [];
        
        if (!docenteId) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo identificar el docente de la clase origen.',
                confirmButtonColor: 'rgb(38, 104, 123)'
            });
            return;
        }

        Swal.fire({
            title: 'Moviendo clase...',
            html: 'Validando disponibilidad y actualizando horario.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            // Validar cada materia asignada
            for (let matId of materiasList) {
                const valRes = await fetch('/horarios/validar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        id_grupo: activeGroup.id || activeGroup.id_grupo,
                        id_materia: matId,
                        id_docente: parseInt(docenteId),
                        diaSemana: dayNumbers[targetDay],
                        horaInicio: horaInicio,
                        horaFin: horaFin,
                        es_prehorario: currentPrehorarioMode,
                        aula: sourceData.aula || "",
                        exclude_ids: excludeIds
                    })
                });

                if (valRes.ok) {
                    const valData = await valRes.json();
                    if (valData.success === false) {
                        Swal.close();
                        
                        const confirmResult = await Swal.fire({
                            icon: 'warning',
                            title: 'Conflicto de Horario',
                            html: `${valData.mensaje}<br><br>¿Deseas asignarlo de todas formas?`,
                            showCancelButton: true,
                            confirmButtonColor: 'rgb(38, 104, 123)',
                            cancelButtonColor: '#cbd5e1',
                            confirmButtonText: 'Sí, asignar',
                            cancelButtonText: 'Cancelar'
                        });

                        if (!confirmResult.isConfirmed) {
                            return;
                        }
                        
                        Swal.fire({
                            title: 'Moviendo clase...',
                            html: 'Actualizando horario.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    }
                }
            }

            // 3. Eliminar clases existentes en destino si corresponde
            if (targetExistingClass && targetExistingClass.clases && targetExistingClass.clases.length > 0) {
                for (let clase of targetExistingClass.clases) {
                    if (clase.id_horario) {
                        await fetch(`/horarios/${clase.id_horario}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                    }
                }
            }

            // 4. Eliminar clases en el origen
            for (let clase of sourceData.clases) {
                if (clase.id_horario) {
                    await fetch(`/horarios/${clase.id_horario}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                }
            }

            // 5. Crear clase en celda de destino
            let payload = {
                id_grupo: activeGroup.id || activeGroup.id_grupo,
                diaSemana: dayNumbers[targetDay],
                horaInicio: horaInicio,
                horaFin: horaFin,
                aula: sourceData.aula || "",
                id_docente: parseInt(docenteId),
                es_prehorario: currentPrehorarioMode
            };

            if (sourceData.clases.length > 1) {
                payload.materias = materiasList;
            } else {
                payload.id_materia = materiasList[0];
            }

            const saveRes = await fetch('/horarios', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(payload)
            });

            if (!saveRes.ok) {
                throw new Error("No se pudo guardar la clase en el nuevo horario.");
            }

            await renderActiveGroupClasses();
            clearForm();
            
            Swal.fire({
                icon: 'success',
                title: '¡Movido!',
                text: 'La clase ha sido movida correctamente.',
                confirmButtonColor: 'rgb(38, 104, 123)',
                timer: 1500
            });

        } catch (error) {
            console.error("Error al mover clase:", error);
            await renderActiveGroupClasses();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || "Error al mover la clase.",
                confirmButtonColor: 'rgb(38, 104, 123)'
            });
        }
        // Parse URL params for pre-selected group or pre-horario mode
        const urlParams = new URLSearchParams(window.location.search);
        const urlGroupId = urlParams.get('grupo_id');
        const urlEsPrehorario = urlParams.get('es_prehorario');

        if (urlGroupId) {
            const checkAndSelect = setInterval(() => {
                if (groups && groups.length > 0) {
                    clearInterval(checkAndSelect);
                    const matchedGroup = groups.find(g => String(g.id) === String(urlGroupId));
                    if (matchedGroup) {
                        selectGroup(matchedGroup);
                        if (urlEsPrehorario === '1' && matchedGroup.clave.toUpperCase().startsWith("BGNE")) {
                            setPrehorarioMode(1);
                            renderActiveGroupClasses();
                        }
                    }
                }
            }, 100);
        }
    }
});
</script>

@endsection
