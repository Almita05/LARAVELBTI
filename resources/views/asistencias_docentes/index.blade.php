@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* Page Wrapper to override layouts background */
.asistencias-page-wrapper {
    background: #f8fafc;
    margin: -25px;
    padding: 30px;
    min-height: calc(100vh - 85px);
    color: #1e293b;
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
}

/* Glass Card styling */
.glass-card {
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 0!important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03) !important;
    color: #1e293b !important;
}

/* Header section styling */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 20px;
    margin-bottom: 25px;
}

.dashboard-title-group {
    display: flex;
    flex-direction: column;
}

.dashboard-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: rgb(38, 104, 123);
    margin: 0;
}

.dashboard-subtitle {
    font-size: 0.9rem;
    color: #64748b;
    margin-top: 4px;
}

/* Action Controls */
.header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

/* Date Range Selector */
.period-selector-container {
    display: flex;
    align-items: center;
    background: #f1f5f9;
    border: 1px solid #cbd5e1;
    border-radius: 0;
    overflow: hidden;
}

.btn-period-arrow {
    background: transparent;
    border: none;
    color: #475569;
    padding: 8px 12px;
    cursor: pointer;
    transition: background 0.2s;
    font-size: 0.85rem;
}

.btn-period-arrow:hover {
    background: #e2e8f0;
}

.period-dates {
    display: flex;
    align-items: center;
    padding: 8px 16px;
    font-size: 0.88rem;
    font-weight: 600;
    color: #334155;
    border-left: 1px solid #cbd5e1;
    border-right: 1px solid #cbd5e1;
    background: #ffffff;
    min-width: 200px;
    justify-content: center;
}

/* Buttons */
.btn-action-teal {
    background: rgb(38, 104, 123) !important;
    border: none !important;
    color: white !important;
    font-size: 0.88rem;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 0;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-action-teal:hover {
    background: rgb(28, 79, 94) !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(38, 104, 123, 0.15);
}

.btn-action-light {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: #334155;
    font-size: 0.88rem;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 0;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-action-light:hover {
    background: #f8fafc;
    border-color: #94a3b8;
}

.btn-icon-only {
    width: 38px;
    height: 38px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
}

/* Panel Body */
.panel-title-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.panel-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: rgb(38, 104, 123);
    margin: 0;
}

/* Search Box styling */
.search-box-container {
    position: relative;
    width: 250px;
    margin-left: 20px;
}

.search-input {
    padding-left: 36px !important;
    border-radius: 0!important;
    font-size: 0.85rem !important;
    border: 1px solid #cbd5e1 !important;
    background: #ffffff !important;
    transition: all 0.2s ease !important;
    height: 36px !important;
    width: 100%;
}

.search-input:focus {
    border-color: rgb(38, 104, 123) !important;
    box-shadow: 0 0 0 3px rgba(38, 104, 123, 0.15) !important;
    outline: none !important;
}

.search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 0.85rem;
    pointer-events: none;
}

/* Legends indicator */
.legends-container {
    display: flex;
    gap: 16px;
    font-size: 0.8rem;
    font-weight: 600;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #475569;
}

.legend-dot {
    width: 14px;
    height: 14px;
    border-radius: 0;
}

.legend-dot.completo {
    background-color: #d1fae5;
    border: 1.2px solid #10b981;
}

.legend-dot.parcial {
    background-color: #fef3c7;
    border: 1.2px solid #f59e0b;
}

.legend-dot.falta {
    background-color: #fee2e2;
    border: 1.2px solid #ef4444;
}

/* Table styling */
.table-responsive-custom {
    width: 100%;
    overflow-x: auto;
    border: 1px solid #e2e8f0;
    border-radius: 0;
    background: #ffffff;
}

.asistencias-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
    text-align: center;
}

/* Header Cells styling */
.asistencias-table th {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #475569;
    font-weight: 700;
    padding: 10px;
}

.asistencias-table th.docente-header {
    text-align: left;
    padding-left: 15px;
    min-width: 220px;
}

.asistencias-table th.day-header {
    font-size: 0.78rem;
    background: #f1f5f9;
    border-bottom: 1.5px solid #cbd5e1;
    min-width: 75px;
}


/* Table body rows */
.asistencias-table td {
    border: 1px solid #e2e8f0;
    padding: 10px 8px;
    vertical-align: middle;
}

.asistencias-table tr:hover td {
    background-color: rgba(248, 250, 252, 0.5);
}

/* Teacher identity Cell */
.teacher-cell {
    display: flex;
    align-items: center;
    gap: 10px;
    text-align: left;
    padding-left: 8px;
}

.teacher-avatar {
    width: 32px;
    height: 32px;
    border-radius:0;
    background: rgba(38, 104, 123, 0.1);
    color: rgb(38, 104, 123);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.78rem;
    border: 1.2px solid rgba(38, 104, 123, 0.2);
}

.teacher-info {
    display: flex;
    flex-direction: column;
    line-height: 1.25;
}

.teacher-name {
    font-weight: 700;
    color: #1e293b;
    font-size: 0.85rem;
}

.teacher-sub {
    font-size: 0.72rem;
    color: #64748b;
}

/* Cell Value Badge styles */
.hour-cell {
    font-weight: 600;
    font-size: 0.8rem;
    color: #64748b;
}

.hour-cell.tot-val {
    color: #334155;
}

.hour-cell.real-val {
    padding: 4px;
    border-radius: 0;
    transition: all 0.25s ease;
}

.hour-cell.real-val.status-completo {
    background-color: rgba(16, 185, 129, 0.08);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.15);
}

.hour-cell.real-val.status-parcial {
    background-color: rgba(245, 158, 11, 0.08);
    color: #d97706;
    border: 1px solid rgba(245, 158, 11, 0.15);
}

.hour-cell.real-val.status-falta {
    background-color: rgba(239, 68, 68, 0.08);
    color: #dc2626;
    border: 1px solid rgba(239, 68, 68, 0.15);
}

/* ---- Tabla quincenal: una celda por día (reales de programadas) ---- */
.asistencias-table th.day-header { min-width: 58px; padding: 6px 4px; line-height: 1.15; }
.asistencias-table th.day-header span { display: block; font-size: 0.65rem; font-weight: 600; color: #94a3b8; letter-spacing: .04em; }
.asistencias-table th.quincena-header { min-width: 132px; font-size: 0.78rem; }
.asistencias-table th.fin-semana,
.asistencias-table td.fin-semana { background-color: #f8fafc; }

/* Docente fijo al desplazarse a la derecha */
.asistencias-table th.docente-header,
.asistencias-table td.docente-cell { position: sticky; left: 0; z-index: 2; background: #ffffff; }
.asistencias-table th.docente-header { background: #f8fafc; z-index: 3; }

.asistencias-table td.dia-cell { padding: 5px 3px; line-height: 1.2; }
.asistencias-table td.dia-cell .hour-cell { display: block; }
.dia-cell .real-val { font-size: 0.82rem; font-weight: 700; color: #334155; padding: 1px 2px; }
.dia-cell .tot-val { font-size: 0.68rem; font-weight: 500; color: #64748b; margin-top: 1px; }
.asistencias-table td.dia-cell.clickable { cursor: pointer; }
.asistencias-table td.dia-cell.clickable:hover { outline: 2px solid rgba(38, 104, 123, 0.45); outline-offset: -2px; }
.asistencias-table td.dia-cell.clickable:focus-visible { outline: 2px solid rgb(38, 104, 123); outline-offset: -2px; }
.dia-cell .real-pendiente { color: #cbd5e1; font-weight: 400; }
.asistencias-table td.dia-cell.status-completo { background-color: rgba(16, 185, 129, 0.08); box-shadow: inset 0 -2px 0 #10b981; }
.asistencias-table td.dia-cell.status-completo .real-val { color: #047857; }
.asistencias-table td.dia-cell.status-parcial { background-color: rgba(245, 158, 11, 0.09); box-shadow: inset 0 -2px 0 #f59e0b; }
.asistencias-table td.dia-cell.status-parcial .real-val { color: #b45309; }
.asistencias-table td.dia-cell.status-falta { background-color: rgba(239, 68, 68, 0.08); box-shadow: inset 0 -2px 0 #ef4444; }
.asistencias-table td.dia-cell.status-falta .real-val { color: #b91c1c; }

/* Totales de la quincena */
.asistencias-table td.quincena-cell { text-align: left; padding: 6px 10px; line-height: 1.3; background: #fcfdfe; }
.quincena-cell .q-horas { font-size: 0.8rem; color: #64748b; font-variant-numeric: tabular-nums; }
.quincena-cell .q-horas strong { font-size: 0.9rem; color: #1e293b; }
.quincena-cell .q-pct { font-size: 0.72rem; font-weight: 700; }
.quincena-cell .q-pct.ok { color: #047857; }
.quincena-cell .q-pct.warn { color: #b45309; }
.quincena-cell .q-pct.mal { color: #b91c1c; }
.quincena-cell .q-inc { font-size: 0.7rem; color: #94a3b8; }
.quincena-cell .q-inc.con { color: #b45309; font-weight: 600; }


/* Detalle de asistencia del día */
.asis-marcajes { display: flex; flex-wrap: wrap; gap: 6px; align-items: center; margin-bottom: 12px; font-size: 0.78rem; color: #64748b; }
.asis-marcajes .chip { font-family: monospace; font-size: 0.78rem; padding: 2px 6px; background: #f1f5f9; border: 1px solid #e2e8f0; color: #334155; }
.asis-estado { font-size: 0.75rem; font-weight: 600; padding: 2px 6px; display: inline-block; }
.asis-estado.ok { background: rgba(16, 185, 129, 0.08); color: #059669; }
.asis-estado.warn { background: rgba(245, 158, 11, 0.1); color: #b45309; }
.asis-estado.falta { background: rgba(239, 68, 68, 0.08); color: #dc2626; }
.asis-aviso { font-size: 0.8rem; color: #78350f; background: #fef3c7; border: 1px solid #fcd34d; padding: 8px 10px; margin-bottom: 12px; }

.empty-placeholder {
    color: #cbd5e1;
    font-weight: normal;
}

/* Modal Overlay Styling */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(15, 23, 42, 0.4);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
}

.modal-overlay.active {
    opacity: 1;
    pointer-events: auto;
}

.modal-content-card {
    background: #ffffff;
    border-radius: 0;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    border: 1px solid #e2e8f0;
    overflow: hidden;
    transform: translateY(20px);
    transition: transform 0.3s ease;
}

.modal-overlay.active .modal-content-card {
    transform: translateY(0);
}

.modal-header-custom {
    padding: 20px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: rgb(38, 104, 123);
    margin: 0;
}

.btn-close-modal {
    background: transparent;
    border: none;
    color: #64748b;
    font-size: 1.2rem;
    cursor: pointer;
    transition: color 0.2s;
}

.btn-close-modal:hover {
    color: #0f172a;
}

.modal-body-custom {
    padding: 24px;
}

/* File Upload drag area */
.upload-drag-area {
    border: 2px dashed #cbd5e1;
    border-radius: 0;
    padding: 30px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.25s ease;
    background: #f8fafc;
}

.upload-drag-area:hover,
.upload-drag-area.drag-over {
    border-color: rgb(38, 104, 123);
    background: rgba(38, 104, 123, 0.02);
}

.upload-icon {
    font-size: 2.2rem;
    color: rgb(38, 104, 123);
    margin-bottom: 15px;
}

.upload-text-main {
    font-weight: 700;
    font-size: 0.9rem;
    color: #334155;
    margin-bottom: 6px;
}

.upload-text-sub {
    font-size: 0.78rem;
    color: #64748b;
}

/* Progress bar style */
.upload-progress-container {
    display: none;
    margin-top: 20px;
}

.progress-bar-outer {
    height: 8px;
    background: #e2e8f0;
    border-radius: 0;
    overflow: hidden;
    margin-bottom: 8px;
}

.progress-bar-inner {
    height: 100%;
    width: 0%;
    background: rgb(38, 104, 123);
    transition: width 0.1s linear;
}

.progress-status {
    display: flex;
    justify-content: space-between;
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
}

/* Footer of modal */
.modal-footer-custom {
    padding: 16px 24px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    background: #f8fafc;
}

.btn-form-cancel {
    background: transparent;
    border: 1px solid #cbd5e1;
    color: #475569;
    font-size: 0.88rem;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 0;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-form-cancel:hover {
    background: #f1f5f9;
}
</style>

<div class="asistencias-page-wrapper">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('home') }}" class="btn-regresar-light">
            <i class="fa-solid fa-arrow-left me-2"></i>
            Regresar
        </a>
    </div>

    <!-- Main Dashboard Card -->
    <div class="glass-card p-4">
        
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="dashboard-title-group">
                <h1 class="dashboard-title">Análisis de Horas Laboradas</h1>
                <p class="dashboard-subtitle mb-0">Control quincenal de asistencia y cumplimiento docente.</p>
            </div>
            
            <div class="header-actions">
                <!-- Date Period Selector -->
                <div class="period-selector-container">
                    <button class="btn-period-arrow" id="btnPrevPeriod" title="Quincena Anterior">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <div class="period-dates">
                        <i class="fa-regular fa-calendar-days me-2" style="color: rgb(38, 104, 123);"></i>
                        <span id="lblPeriodDates">01 Ago 2026 - 15 Ago 2026</span>
                    </div>
                    <button class="btn-period-arrow" id="btnNextPeriod" title="Siguiente Quincena">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>

                <!-- Action buttons -->
                <button class="btn-action-teal" id="btnOpenImportModal">
                    <i class="fa-solid fa-file-excel"></i>
                    Importar Biométrico
                </button>

                <button class="btn-action-light" id="btnClearBiometric" title="Limpiar datos del Excel (Biométrico)">
                    <i class="fa-solid fa-trash-can"></i>
                    Limpiar Asistencias
                </button>
                
                <button class="btn-action-light" id="btnGenerateReport">
                    <i class="fa-solid fa-file-lines"></i>
                    Generar Reporte
                </button>

                <button class="btn-action-light btn-icon-only" id="btnDownloadReport" title="Exportar Reporte">
                    <i class="fa-solid fa-download"></i>
                </button>

                <button class="btn-action-light btn-icon-only" id="btnRefresh" title="Actualizar Datos">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </button>
            </div>
        </div>

        <!-- Panel Body Title & Legends -->
        <div class="panel-title-bar">
            <div class="d-flex align-items-center flex-grow-1">
                <h4 class="panel-title mb-0" style="margin-right: 10px;">Registro Quincenal Detallado</h4>
                <div class="search-box-container">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" id="txtSearchTeacher" class="form-control search-input" placeholder="Buscar docente...">
                </div>
            </div>
            
            <!-- Legends indicator -->
            <div class="legends-container">
                <div class="legend-item">
                    <div class="legend-dot completo"></div>
                    <span>Completo</span>
                </div>
                <div class="legend-item">
                    <div class="legend-dot parcial"></div>
                    <span>Parcial/Retardo</span>
                </div>
                <div class="legend-item">
                    <div class="legend-dot falta"></div>
                    <span>Falta/Incompleto</span>
                </div>
            </div>
        </div>

        <!-- Processing Alert Banner -->
        <div id="processingAlert" class="alert alert-info d-flex align-items-center mb-3 justify-content-between" style="display: none !important; border-radius: 0; border-left: 4px solid #0ea5e9; background: #f0f9ff; color: #0369a1; padding: 12px 20px;">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-info me-2 text-info fs-5" style="color: #0ea5e9 !important;"></i>
                <div>
                    El archivo <strong id="lblUploadedFilename"></strong> se ha recibido y guardado. Se encuentra en proceso de carga en el servidor.
                </div>
            </div>
            <button id="btnRefreshStatus" class="btn btn-sm btn-info text-white d-flex align-items-center" style="background: #0ea5e9; border: none; border-radius: 0; padding: 5px 10px; font-size: 0.8rem; font-weight: 600;">
                <i class="fa-solid fa-arrows-rotate me-1"></i>Actualizar estado
            </button>
        </div>

        <!-- Dynamic Grid Table Container -->
        <div class="table-responsive-custom">
            <table class="asistencias-table" id="attendanceGridTable">
                <thead id="gridHeader">
                    <!-- Dinámico por JS -->
                </thead>
                <tbody id="gridBody">
                    <!-- Dinámico por JS -->
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Excel Import Modal -->
<div class="modal-overlay" id="importModal">
    <div class="modal-content-card">
        <div class="modal-header-custom">
            <h5 class="modal-title">
                <i class="ti ti-file-spreadsheet me-2" style="color: #26687B;"></i>
                Importar Registro Biométrico
            </h5>
            <button class="btn-close-modal" id="btnCloseModal">
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="modal-body-custom">
            <div class="upload-drag-area" id="dragArea">
                <i class="ti ti-cloud-upload upload-icon"></i>
                <div class="upload-text-main" id="uploadStatusText">Arrastra tu archivo Excel aquí</div>
                <div class="upload-text-sub">o haz clic para buscar en tu equipo (.xlsx, .xls)</div>
                <input type="file" id="fileInput" accept=".xlsx, .xls" style="display: none;">
            </div>

            <!-- Upload progress bar -->
            <div class="upload-progress-container" id="progressContainer">
                <div class="progress-bar-outer">
                    <div class="progress-bar-inner" id="progressBarInner"></div>
                </div>
                <div class="progress-status">
                    <span id="progressFilename">registros_biometricos.xlsx</span>
                    <span id="progressPercentage">0%</span>
                </div>
            </div>
        </div>
        <div class="modal-footer-custom">
            <button class="btn-form-cancel" id="btnCancelModal">Cancelar</button>
            <button class="btn-action-teal" id="btnProcessImport" disabled>
                <i class="ti ti-player-play me-1"></i>
                Procesar Archivo
            </button>
        </div>
    </div>
</div>

<!-- Detalle del día: horario y llegada/salida en cada clase -->
<div class="modal-overlay" id="asisModal" role="dialog" aria-modal="true" aria-labelledby="asisModalTitle">
    <div class="modal-content-card" style="max-width: 820px;">
        <div class="modal-header-custom">
            <h5 class="modal-title" id="asisModalTitle">
                <i class="ti ti-fingerprint me-2" style="color: #26687B;"></i>
                Detalle del día
            </h5>
            <button class="btn-close-modal" id="btnCloseAsisModal" aria-label="Cerrar">
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="modal-body-custom" style="padding: 20px;">
            <div class="d-flex align-items-center mb-3" style="gap: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                <div class="teacher-avatar" id="asisAvatar"></div>
                <div>
                    <h6 class="mb-0 font-weight-bold" id="asisNombre" style="color: #1e293b; font-size: 1rem;"></h6>
                    <span class="text-secondary" id="asisFecha" style="font-size: 0.8rem;"></span>
                </div>
            </div>
            <div id="asisContenido"></div>
        </div>
        <div class="modal-footer-custom" style="background: #f8fafc; padding: 15px 20px; border-top: 1px solid #e2e8f0; justify-content: space-between; align-items: center; display: flex;">
            <div style="font-size: 0.85rem; color: #64748b;" id="asisResumen"></div>
            <button class="btn-action-teal" id="btnAceptarAsisModal">Aceptar</button>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Current date state: starts in August 1, 2026
    let currentYear = 2026;
    let currentMonthIdx = 7; // August
    let currentPeriod = 1; // 1: 1st-15th, 2: 16th-End

    const monthsNames = [
        "Ene", "Feb", "Mar", "Abr", "May", "Jun", 
        "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"
    ];
    const daysWeekShort = ["DOM", "LUN", "MAR", "MIÉ", "JUE", "VIE", "SÁB"];

    // Base mock attendance DB
    let teachersList = [];
    let attendanceData = [];
    let biometricLogs = [];
    let biometricDataImported = false;
    let biometricFileProcessing = false;
    let uploadedFilename = "";

    // Mensaje del último error al cargar las horas (null si cargó bien)
    let errorCarga = null;

    // DOM selectors
    const lblPeriodDates = document.getElementById("lblPeriodDates");
    const btnPrevPeriod = document.getElementById("btnPrevPeriod");
    const btnNextPeriod = document.getElementById("btnNextPeriod");
    
    // Modal Selectors
    const importModal = document.getElementById("importModal");
    const btnOpenImportModal = document.getElementById("btnOpenImportModal");
    const btnCloseModal = document.getElementById("btnCloseModal");
    const btnCancelModal = document.getElementById("btnCancelModal");
    const btnProcessImport = document.getElementById("btnProcessImport");
    const dragArea = document.getElementById("dragArea");
    const fileInput = document.getElementById("fileInput");
    const uploadStatusText = document.getElementById("uploadStatusText");
    const progressContainer = document.getElementById("progressContainer");
    const progressBarInner = document.getElementById("progressBarInner");
    const progressPercentage = document.getElementById("progressPercentage");
    const progressFilename = document.getElementById("progressFilename");

    const gridHeader = document.getElementById("gridHeader");
    const gridBody = document.getElementById("gridBody");

    const btnGenerateReport = document.getElementById("btnGenerateReport");
    const btnDownloadReport = document.getElementById("btnDownloadReport");
    const btnRefresh = document.getElementById("btnRefresh");
    const btnClearBiometric = document.getElementById("btnClearBiometric");

    // Initialize module
    initAttendanceModule();

    async function initAttendanceModule() {
        renderPeriod();
    }

    function formatDecimalHours(decimalHours) {
        if (!decimalHours || isNaN(decimalHours)) return '0:00';
        const totalMinutes = Math.round(decimalHours * 60);
        const hrs = Math.floor(totalMinutes / 60);
        const mins = totalMinutes % 60;
        return hrs + ":" + String(mins).padStart(2, '0');
    }

    // Function to calculate and render headers and cells using real API
    async function renderPeriod(skipFetch = false) {
        // 1. Calculate dates
        let startDay = 1;
        let endDay = 15;
        if (currentPeriod === 2) {
            startDay = 16;
            // Get last day of month
            endDay = new Date(currentYear, currentMonthIdx + 1, 0).getDate();
        }

        const monthNumStr = String(currentMonthIdx + 1).padStart(2, '0');
        const startDayStr = String(startDay).padStart(2, '0');
        const endDayStr = String(endDay).padStart(2, '0');

        const fechaInicio = `${currentYear}-${monthNumStr}-${startDayStr}`;
        const fechaFin = `${currentYear}-${monthNumStr}-${endDayStr}`;

        // Update Label "01 Ago 2026 - 15 Ago 2026"
        const monthLabel = monthsNames[currentMonthIdx];
        lblPeriodDates.textContent = `${startDayStr} ${monthLabel} ${currentYear} - ${endDayStr} ${monthLabel} ${currentYear}`;

        if (!skipFetch) {
            // Show loading spinner
            gridBody.innerHTML = `
                <tr>
                    <td colspan="${(endDay - startDay + 1) * 2 + 1}" class="text-center py-5">
                        <div class="spinner-border text-secondary spinner-border-sm me-2" role="status"></div>
                        <span class="text-secondary" style="font-size: 0.9rem;">Cargando información de asistencia...</span>
                    </td>
                </tr>
            `;

            try {
                const urlDatos = `/asistencias_docentes/datos?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
                const urlBiometrico = `/asistencias?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
                
                const [resDatos, resBiometrico] = await Promise.all([
                    // Si fallan las horas de los docentes es un error, no "sin docentes"
                    fetch(urlDatos).then(async r => {
                        const data = await r.json().catch(() => null);
                        if (!r.ok || !Array.isArray(data)) throw new Error((data && data.error) || `HTTP ${r.status}`);
                        return data;
                    }),
                    fetch(urlBiometrico).then(r => r.ok ? r.json() : [])
                ]);

                attendanceData = resDatos;
                biometricLogs = Array.isArray(resBiometrico) ? resBiometrico : [];
                errorCarga = null;

                // Si ya hay registros en la base de datos, quitamos el estado de procesando
                if (biometricLogs && biometricLogs.length > 0) {
                    biometricFileProcessing = false;
                    const processingAlert = document.getElementById("processingAlert");
                    if (processingAlert) {
                        processingAlert.style.setProperty("display", "none", "important");
                    }
                }
            } catch(e) {
                // Sin datos inventados: se avisa del error en la tabla
                console.error("Error al obtener las horas de los docentes:", e);
                attendanceData = [];
                biometricLogs = [];
                errorCarga = (e && e.message) || 'Error desconocido';
            }
        }

        // 2. Encabezado: Docente · Quincena (totales) · un día por columna
        const totalDias = endDay - startDay + 1;
        let headerRow = '<tr><th class="docente-header">Docente</th><th class="quincena-header">Quincena</th>';
        for (let d = startDay; d <= endDay; d++) {
            const dow = new Date(currentYear, currentMonthIdx, d).getDay();
            headerRow += `<th class="day-header ${dow === 0 || dow === 6 ? 'fin-semana' : ''}">${d}<span>${daysWeekShort[dow]}</span></th>`;
        }
        gridHeader.innerHTML = headerRow + '</tr>';

        // 3. Filas
        let bodyHtml = '';
        const searchQuery = document.getElementById("txtSearchTeacher") ? document.getElementById("txtSearchTeacher").value.toLowerCase().trim() : "";
        const filteredData = attendanceData.filter(teacher => {
            return (teacher.docente || "").toLowerCase().includes(searchQuery);
        });

        const escTxt = v => String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
        const filaAviso = (icono, texto) => `
                <tr>
                    <td colspan="${totalDias + 2}" class="text-center py-5 text-secondary" style="font-size: 0.9rem; background: white;">
                        <i class="fa-solid ${icono} d-block fs-4 mb-2 text-muted"></i>
                        ${texto}
                    </td>
                </tr>
            `;
        const hayBiometrico = biometricLogs && biometricLogs.length > 0;

        if (errorCarga) {
            bodyHtml = filaAviso('fa-triangle-exclamation',
                `No se pudieron cargar las horas de los docentes. Recarga la página para intentarlo de nuevo.<br><small style="opacity:.7">${escTxt(errorCarga)}</small>`);
        } else if (attendanceData.length === 0) {
            bodyHtml = filaAviso('fa-user-slash', 'No hay docentes activos para mostrar.');
        } else if (filteredData.length === 0) {
            bodyHtml = filaAviso('fa-user-slash', `No se encontraron docentes con el nombre "${escTxt(searchQuery)}".`);
        } else {
            filteredData.forEach(teacher => {
                const teacherName = teacher.docente || "Docente";
                const nombreAttr = escTxt(teacherName);
                const initials = teacherName.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
                const teacherId = teacher.id_docente || teacher.id;

                // Totales de la quincena (solo días con clases programadas)
                let sumProg = 0, sumReal = 0, retardos = 0, faltas = 0;
                let celdasDias = '';

                for (let d = startDay; d <= endDay; d++) {
                    const dateString = `${currentYear}-${monthNumStr}-${String(d).padStart(2, '0')}`;
                    const dow = new Date(currentYear, currentMonthIdx, d).getDay();
                    const finSemana = (dow === 0 || dow === 6) ? 'fin-semana' : '';
                    const dayRecord = teacher.dias ? teacher.dias.find(dr => dr.fecha === dateString) : null;
                    const totHours = dayRecord ? parseFloat(dayRecord.total || 0) : 0;

                    if (!(totHours > 0)) {
                        celdasDias += `<td class="dia-cell ${finSemana}"></td>`;
                        continue;
                    }
                    sumProg += totHours;

                    const bioRecord = hayBiometrico ? biometricLogs.find(log => log.id_docente === teacherId && log.fecha === dateString) : null;

                    // Horas reales y estado del día
                    let realHtml = '<span class="real-pendiente">—</span>';
                    let estadoClass = '';
                    let observations = '';
                    if (hayBiometrico) {
                        if (bioRecord) {
                            const hrs = parseFloat(bioRecord.horas_trabajadas || 0);
                            sumReal += hrs;
                            realHtml = formatDecimalHours(hrs);
                            observations = bioRecord.observaciones || '';
                            const estado = (bioRecord.estado || '').toLowerCase();
                            if (estado.includes('completo')) {
                                estadoClass = 'status-completo';
                            } else if (estado.includes('parcial') || estado.includes('retardo') || estado.includes('advertencia')) {
                                estadoClass = 'status-parcial';
                                retardos++;
                            } else {
                                estadoClass = 'status-falta';
                                faltas++;
                            }
                        } else {
                            // Ya se importó la quincena y no hay registro: falta
                            realHtml = '0:00';
                            estadoClass = 'status-falta';
                            observations = 'Sin registros de entrada ni salida (Falta)';
                            faltas++;
                        }
                    } else if (biometricFileProcessing) {
                        realHtml = `<span class="spinner-border spinner-border-sm text-secondary" style="width: 11px; height: 11px;" role="status"></span>`;
                    }

                    // Toda la celda abre el detalle del día (horario + llegada y salida en cada clase)
                    const titulo = escTxt(observations ? `${observations} · Clic para ver el detalle` : 'Clic para ver el detalle del día');
                    celdasDias += `<td class="dia-cell clickable ${finSemana} ${estadoClass}" role="button" tabindex="0" title="${titulo}"
                        data-docente-id="${teacherId}" data-docente-name="${nombreAttr}" data-fecha="${dateString}" data-importado="${hayBiometrico ? 1 : 0}">
                        <span class="hour-cell real-val">${realHtml}</span><span class="hour-cell tot-val">de ${formatDecimalHours(totHours)}</span></td>`;
                }

                // Celda de la quincena
                let quincenaHtml = `<div class="q-horas"><strong>${hayBiometrico ? formatDecimalHours(sumReal) : '—'}</strong> de ${formatDecimalHours(sumProg)}</div>`;
                if (sumProg > 0 && hayBiometrico) {
                    const pct = Math.round(sumReal * 100 / sumProg);
                    const tono = pct >= 95 ? 'ok' : (pct >= 80 ? 'warn' : 'mal');
                    quincenaHtml += `<div class="q-pct ${tono}">${pct}% cumplido</div>`;
                    const inc = [];
                    if (retardos) inc.push(`${retardos} ${retardos === 1 ? 'retardo' : 'retardos'}`);
                    if (faltas) inc.push(`${faltas} ${faltas === 1 ? 'falta' : 'faltas'}`);
                    quincenaHtml += `<div class="q-inc ${inc.length ? 'con' : ''}">${inc.length ? inc.join(' · ') : 'Sin incidencias'}</div>`;
                } else if (sumProg === 0) {
                    quincenaHtml = '<div class="q-inc">Sin clases en la quincena</div>';
                } else {
                    quincenaHtml += '<div class="q-inc">Biométrico sin importar</div>';
                }

                bodyHtml += `
                    <tr>
                        <td class="docente-cell">
                            <div class="teacher-cell">
                                <div class="teacher-avatar">${escTxt(initials)}</div>
                                <div class="teacher-info"><span class="teacher-name">${escTxt(teacherName)}</span></div>
                            </div>
                        </td>
                        <td class="quincena-cell">${quincenaHtml}</td>
                        ${celdasDias}
                    </tr>`;
            });
        }

        gridBody.innerHTML = bodyHtml;
    }

    // Navigation arrow handlers (Quincena switching)
    btnPrevPeriod.addEventListener("click", function() {
        if (currentPeriod === 2) {
            currentPeriod = 1;
        } else {
            currentPeriod = 2;
            currentMonthIdx--;
            if (currentMonthIdx < 0) {
                currentMonthIdx = 11;
                currentYear--;
            }
        }
        renderPeriod();
    });

    btnNextPeriod.addEventListener("click", function() {
        if (currentPeriod === 1) {
            currentPeriod = 2;
        } else {
            currentPeriod = 1;
            currentMonthIdx++;
            if (currentMonthIdx > 11) {
                currentMonthIdx = 0;
                currentYear++;
            }
        }
        renderPeriod();
    });

    // ==========================================
    // Biometric Modal Logic
    // ==========================================
    btnOpenImportModal.addEventListener("click", function() {
        importModal.classList.add("active");
        resetUploadState();
    });

    function closeModal() {
        importModal.classList.remove("active");
    }

    btnCloseModal.addEventListener("click", closeModal);
    btnCancelModal.addEventListener("click", closeModal);
    
    // Close modal clicking outside
    importModal.addEventListener("click", function(e) {
        if (e.target === importModal) {
            closeModal();
        }
    });

    // Drag and drop handlers
    dragArea.addEventListener("click", () => fileInput.click());
    
    fileInput.addEventListener("change", function() {
        if (fileInput.files.length > 0) {
            handleFileSelected(fileInput.files[0]);
        }
    });

    dragArea.addEventListener("dragover", function(e) {
        e.preventDefault();
        dragArea.classList.add("drag-over");
    });

    dragArea.addEventListener("dragleave", function() {
        dragArea.classList.remove("drag-over");
    });

    dragArea.addEventListener("drop", function(e) {
        e.preventDefault();
        dragArea.classList.remove("drag-over");
        if (e.dataTransfer.files.length > 0) {
            handleFileSelected(e.dataTransfer.files[0]);
        }
    });

    let selectedFile = null;

    function handleFileSelected(file) {
        // Validate extension
        const extension = file.name.split('.').pop().toLowerCase();
        if (extension !== 'xlsx' && extension !== 'xls') {
            Swal.fire({
                icon: 'error',
                title: 'Formato no soportado',
                text: 'Por favor, selecciona un archivo Excel (.xlsx o .xls).',
                confirmButtonColor: 'rgb(38, 104, 123)'
            });
            return;
        }

        selectedFile = file;
        uploadStatusText.innerHTML = `<strong>${file.name}</strong> seleccionado`;
        dragArea.style.borderColor = "rgb(38, 104, 123)";
        btnProcessImport.removeAttribute("disabled");
    }

    function resetUploadState() {
        selectedFile = null;
        fileInput.value = "";
        uploadStatusText.innerHTML = "Arrastra tu archivo Excel aquí";
        dragArea.style.borderColor = "#cbd5e1";
        progressContainer.style.display = "none";
        btnProcessImport.setAttribute("disabled", "disabled");
        progressBarInner.style.width = "0%";
        progressPercentage.textContent = "0%";
    }

    btnProcessImport.addEventListener("click", function() {
        if (!selectedFile) return;

        // Set up real progress indicators
        btnProcessImport.setAttribute("disabled", "disabled");
        progressContainer.style.display = "block";
        progressFilename.textContent = selectedFile.name;
        progressBarInner.style.width = "0%";
        progressPercentage.textContent = "0%";

        const formData = new FormData();
        formData.append("file", selectedFile);

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "/asistencias/upload", true);

        // Attach CSRF Token for Laravel validation
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken.getAttribute("content"));
        }

        // Listen for upload progress
        xhr.upload.addEventListener("progress", function(e) {
            if (e.lengthComputable) {
                const pct = Math.round((e.loaded / e.total) * 100);
                progressBarInner.style.width = `${pct}%`;
                progressPercentage.textContent = `${pct}%`;
            }
        });

        // Request loaded handler
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    const res = JSON.parse(xhr.responseText);
                    const filename = res.filename || selectedFile.name;

                    setTimeout(() => {
                        closeModal();

                        // Mark as processing and re-render grid
                        biometricFileProcessing = true;
                        uploadedFilename = filename;

                        // Show banner
                        const processingAlert = document.getElementById("processingAlert");
                        const lblUploadedFilename = document.getElementById("lblUploadedFilename");
                        if (processingAlert && lblUploadedFilename) {
                            lblUploadedFilename.textContent = filename;
                            processingAlert.style.setProperty("display", "flex", "important");
                        }

                        renderPeriod();

                        Swal.fire({
                            icon: 'success',
                            title: '¡Archivo recibido!',
                            html: `El archivo se ha subido correctamente y se encuentra en proceso de carga.<br><small class="text-secondary">Guardado como: <strong>${filename}</strong></small>`,
                            confirmButtonColor: 'rgb(38, 104, 123)'
                        });
                    }, 400);
                } catch(e) {
                    console.error("Error al procesar respuesta del servidor:", e);
                    showUploadError("La respuesta del servidor no tiene el formato esperado.");
                }
            } else {
                let errorMsg = "Ocurrió un error al subir el archivo.";
                try {
                    const res = JSON.parse(xhr.responseText);
                    errorMsg = res.error || res.mensaje || errorMsg;
                } catch(e) {}
                showUploadError(errorMsg);
            }
        };

        xhr.onerror = function() {
            showUploadError("Error de conexión al subir el archivo.");
        };

        xhr.send(formData);
    });

    function showUploadError(msg) {
        btnProcessImport.removeAttribute("disabled");
        progressContainer.style.display = "none";
        Swal.fire({
            icon: 'error',
            title: 'Error de Importación',
            text: msg,
            confirmButtonColor: 'rgb(38, 104, 123)'
        });
    }

    // Status refresh button listener
    const btnRefreshStatus = document.getElementById("btnRefreshStatus");
    if (btnRefreshStatus) {
        btnRefreshStatus.addEventListener("click", function(e) {
            e.preventDefault();
            renderPeriod(false); // force a real backend refresh
        });
    }

    // Action button triggers
    btnGenerateReport.addEventListener("click", function() {
        Swal.fire({
            title: 'Generando Reporte...',
            html: 'Compilando estadísticas de asistencia de la quincena.',
            timer: 1500,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
            }
        }).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Reporte Generado',
                text: 'El reporte de horas docentes ha sido compilado.',
                confirmButtonColor: 'rgb(38, 104, 123)'
            });
        });
    });

    btnDownloadReport.addEventListener("click", function() {
        Swal.fire({
            icon: 'info',
            title: 'Exportar Reporte',
            text: '¿Deseas descargar este análisis de asistencias en formato PDF?',
            showCancelButton: true,
            confirmButtonColor: 'rgb(38, 104, 123)',
            cancelButtonColor: '#cbd5e1',
            confirmButtonText: 'Descargar PDF',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Descarga iniciada',
                    text: 'El archivo PDF se está descargando en tu equipo.',
                    confirmButtonColor: 'rgb(38, 104, 123)',
                    timer: 1500
                });
            }
        });
    });

    btnRefresh.addEventListener("click", function() {
        renderPeriod();
        Swal.fire({
            icon: 'success',
            title: 'Datos actualizados',
            text: 'La cuadrícula de asistencia docente ha sido actualizada.',
            confirmButtonColor: 'rgb(38, 104, 123)',
            timer: 1200,
            showConfirmButton: false
        });
    });

    if (btnClearBiometric) {
        btnClearBiometric.addEventListener("click", function() {
            clearBiometricData(true);
        });
    }

    // Limpieza automática al salir de la pestaña / cerrar navegador / cambiar de vista
    window.addEventListener("unload", function() {
        fetch('/asistencias/clear', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            keepalive: true
        });
    });

    async function clearBiometricData(showConfirm = true) {
        if (showConfirm) {
            const confirmResult = await Swal.fire({
                title: '¿Deseas limpiar las asistencias?',
                text: 'Esta acción borrará todas las horas reales importadas del Excel y restablecerá la grilla.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Sí, limpiar',
                cancelButtonText: 'Cancelar'
            });
            if (!confirmResult.isConfirmed) return;
        }

        try {
            const response = await fetch('/asistencias/clear', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            if (response.ok && data.success) {
                if (showConfirm) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Limpieza Completada',
                        text: data.message || 'Se han limpiado los registros correctamente.',
                        confirmButtonColor: 'rgb(38, 104, 123)'
                    });
                }
                renderPeriod();
            } else {
                if (showConfirm) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error || 'Error al limpiar asistencias.',
                        confirmButtonColor: 'rgb(38, 104, 123)'
                    });
                }
            }
        } catch (e) {
            console.error("Error clearing biometric:", e);
            if (showConfirm) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error inesperado al conectar con el servidor.',
                    confirmButtonColor: 'rgb(38, 104, 123)'
                });
            }
        }
    }

    // Search input listener
    const txtSearchTeacher = document.getElementById("txtSearchTeacher");
    if (txtSearchTeacher) {
        txtSearchTeacher.addEventListener("input", function() {
            renderPeriod(true); // Redraw list without network fetch
        });
    }
 
    // ---------------------------------------------------------------
    // Detalle del día: clic en la celda del día → clases con su horario y, si ya se importó el
    // biométrico, a qué hora llegó y salió de cada una
    // ---------------------------------------------------------------
    const asisModal = document.getElementById("asisModal");
    const asisContenido = document.getElementById("asisContenido");
    const asisResumen = document.getElementById("asisResumen");
    const escA = v => String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

    function abrirDia(celda) {
        const nombre = celda.getAttribute("data-docente-name") || '';
        const fecha = celda.getAttribute("data-fecha");
        const importado = celda.getAttribute("data-importado") === "1";
        document.getElementById("asisAvatar").textContent = nombre.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
        document.getElementById("asisNombre").textContent = nombre;
        const f = new Date(fecha + 'T00:00:00').toLocaleDateString('es-MX', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        document.getElementById("asisFecha").textContent = f.charAt(0).toUpperCase() + f.slice(1);
        asisResumen.textContent = '';
        asisContenido.innerHTML = `<div class="text-center py-4"><div class="spinner-border text-secondary spinner-border-sm me-2" role="status"></div><span class="text-secondary" style="font-size: 0.85rem;">Cargando…</span></div>`;
        asisModal.classList.add("active");

        fetch(`/asistencias_docentes/asistencia_dia?fecha=${encodeURIComponent(fecha)}&id_docente=${encodeURIComponent(celda.getAttribute("data-docente-id"))}`,
              { headers: { 'Accept': 'application/json' } })
            .then(async r => {
                const d = await r.json().catch(() => null);
                if (!r.ok || !d || !Array.isArray(d.clases)) throw new Error((d && d.error) || `HTTP ${r.status}`);
                return d;
            })
            .then(d => pintarDia(d, importado))
            .catch(err => {
                asisContenido.innerHTML = `<div class="text-center py-4 text-secondary" style="font-size: 0.85rem;">No se pudo cargar el detalle del día.<br><small style="opacity:.7">${escA(err.message)}</small></div>`;
            });
    }

    function pintarDia(d, importado) {
        const reg = d.registro;
        // Llegada y salida solo cuando la quincena ya tiene biométrico importado
        const conAsistencia = importado && d.tiene_marcajes;
        let html = '';
        if (!importado) {
            html += `<div class="asis-aviso"><i class="ti ti-info-circle me-1"></i>El biométrico de esta quincena aún no se importa; solo se muestra el horario.</div>`;
        } else if (d.sin_registro) {
            html += `<div class="asis-aviso"><i class="ti ti-info-circle me-1"></i>No tiene marcajes en el biométrico este día.</div>`;
        } else if (!d.tiene_marcajes) {
            html += `<div class="asis-aviso"><i class="ti ti-info-circle me-1"></i>Este día se importó antes de guardar los marcajes por hora. Vuelve a importar el biométrico de esta quincena para ver a qué hora llegó a cada clase.</div>`;
        } else {
            html += `<div class="asis-marcajes"><span>Marcajes del día:</span>${d.marcajes.length
                ? d.marcajes.map(m => `<span class="chip">${escA(m)}</span>`).join('')
                : '<span class="empty-placeholder">ninguno</span>'}</div>`;
        }

        const tono = e => !e ? '' : (e === 'A tiempo' ? 'ok' : (e === 'Sin marcaje' ? 'falta' : 'warn'));
        const vacio = '<span class="empty-placeholder">—</span>';
        const td = (contenido, extra = '') => `<td class="p-2 align-middle ${extra}" style="font-size: 0.82rem;">${contenido}</td>`;
        let programadas = 0;
        const filas = d.clases.map(c => {
            programadas += parseFloat(c.horas || 0);
            return `<tr style="border-bottom: 1px solid #f1f5f9; background: white;">
                ${td(`<div style="font-weight: 600; color: rgb(38, 104, 123);">${escA(c.grupo)}</div><div style="color: #334155;">${c.materias.map(escA).join('<br>')}</div>`, 'text-start')}
                ${td(escA(c.aula || '—'), 'text-start text-secondary')}
                ${td(`<span style="font-family: monospace; white-space: nowrap; color: #475569;">${escA(c.hora_inicio)} - ${escA(c.hora_fin)}</span>`, 'text-center')}
                ${td(`<span style="font-weight: 600;">${escA(formatDecimalHours(parseFloat(c.horas || 0)))}</span>`, 'text-center')}
                ${conAsistencia ? td(`<span style="font-family: monospace; font-weight: 600;">${c.llegada ? escA(c.llegada) : vacio}</span>`, 'text-center') : ''}
                ${conAsistencia ? td(`<span style="font-family: monospace; font-weight: 600;">${c.salida ? escA(c.salida) : vacio}</span>`, 'text-center') : ''}
                ${conAsistencia ? td(c.estado ? `<span class="asis-estado ${tono(c.estado)}">${escA(c.estado)}</span>` : vacio, 'text-start') : ''}
            </tr>`;
        }).join('');

        const th = (t, centro) => `<th class="p-2" style="font-size: 0.78rem; font-weight: 700; color: #475569; ${centro ? 'text-align:center' : 'text-align:left'}">${t}</th>`;
        html += d.clases.length
            ? `<div class="table-responsive-custom" style="border: 1px solid #e2e8f0; max-height: 360px; overflow-y: auto;">
                <table class="asistencias-table mb-0" style="width: 100%;">
                    <thead><tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        ${th('Grupo y materia')}${th('Aula')}${th('Horario', true)}${th('Horas', true)}
                        ${conAsistencia ? th('Llegó', true) + th('Salió', true) + th('Estado') : ''}
                    </tr></thead>
                    <tbody>${filas}</tbody>
                </table></div>`
            : `<div class="text-center py-4 text-secondary" style="font-size: 0.85rem;">No tiene clases programadas este día.</div>`;

        if (importado && reg && reg.observaciones) {
            html += `<div style="font-size: 0.8rem; color: #64748b; margin-top: 10px;"><strong>Observación:</strong> ${escA(reg.observaciones)}</div>`;
        }
        asisContenido.innerHTML = html;

        let resumen = `Programadas: <strong class="text-dark">${escA(formatDecimalHours(programadas))}</strong>`;
        if (importado) {
            resumen += ` · Reales: <strong class="text-dark">${escA(formatDecimalHours(parseFloat((reg && reg.horas_trabajadas) || 0)))}</strong>`;
            resumen += ` · ${escA(reg ? (reg.estado || '') : 'Falta')}`;
        }
        asisResumen.innerHTML = resumen;
    }

    function cerrarDia() { asisModal.classList.remove("active"); }
    document.getElementById("btnCloseAsisModal").addEventListener("click", cerrarDia);
    document.getElementById("btnAceptarAsisModal").addEventListener("click", cerrarDia);
    asisModal.addEventListener("click", e => { if (e.target === asisModal) cerrarDia(); });
    document.addEventListener("keydown", e => { if (e.key === "Escape" && asisModal.classList.contains("active")) cerrarDia(); });

    gridBody.addEventListener("click", function(e) {
        const celda = e.target.closest("td.dia-cell.clickable");
        if (celda) abrirDia(celda);
    });
    gridBody.addEventListener("keydown", function(e) {
        if (e.key !== "Enter" && e.key !== " ") return;
        const celda = e.target.closest("td.dia-cell.clickable");
        if (celda) { e.preventDefault(); abrirDia(celda); }
    });
});
</script>

@endsection
