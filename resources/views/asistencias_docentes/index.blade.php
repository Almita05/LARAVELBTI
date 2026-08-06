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
    border-radius: 12px !important;
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
    border-radius: 8px;
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
    border-radius: 8px;
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
    border-radius: 8px;
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
    border-radius: 20px !important;
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
    border-radius: 3px;
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
    border-radius: 8px;
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

.asistencias-table th.sub-col-header {
    font-size: 0.7rem;
    background: #f8fafc;
    padding: 6px 4px;
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
    border-radius: 50%;
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

.hour-cell.tot-val.clickable {
    cursor: pointer;
    color: rgb(38, 104, 123);
    text-decoration: underline dotted;
    transition: all 0.2s ease;
    display: inline-block;
}

.hour-cell.tot-val.clickable:hover {
    color: rgb(28, 79, 94);
    transform: scale(1.08);
}

.hour-cell.real-val {
    padding: 4px;
    border-radius: 6px;
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
    border-radius: 16px;
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
    border-radius: 12px;
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
    border-radius: 4px;
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
    border-radius: 8px;
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
        <div id="processingAlert" class="alert alert-info d-flex align-items-center mb-3 justify-content-between" style="display: none !important; border-radius: 8px; border-left: 4px solid #0ea5e9; background: #f0f9ff; color: #0369a1; padding: 12px 20px;">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-info me-2 text-info fs-5" style="color: #0ea5e9 !important;"></i>
                <div>
                    El archivo <strong id="lblUploadedFilename"></strong> se ha recibido y guardado. Se encuentra en proceso de carga en el servidor.
                </div>
            </div>
            <button id="btnRefreshStatus" class="btn btn-sm btn-info text-white d-flex align-items-center" style="background: #0ea5e9; border: none; border-radius: 4px; padding: 5px 10px; font-size: 0.8rem; font-weight: 600;">
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
                <i class="fa-solid fa-file-excel me-1"></i>
                Importar Registro Biométrico
            </h5>
            <button class="btn-close-modal" id="btnCloseModal">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body-custom">
            <div class="upload-drag-area" id="dragArea">
                <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
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
                <i class="fa-solid fa-play"></i>
                Procesar Archivo
            </button>
        </div>
    </div>
</div>

<!-- Detailed Schedule Modal -->
<div class="modal-overlay" id="detailModal">
    <div class="modal-content-card" style="max-width: 650px;">
        <div class="modal-header-custom">
            <h5 class="modal-title">
                <i class="fa-solid fa-calendar-day me-1"></i>
                Detalle de Horario
            </h5>
            <button class="btn-close-modal" id="btnCloseDetailModal">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body-custom" style="padding: 20px;">
            <!-- Teacher name and Date header info -->
            <div class="d-flex align-items-center mb-3" style="gap: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                <div class="teacher-avatar" id="detailTeacherAvatar">MV</div>
                <div>
                    <h6 class="mb-0 font-weight-bold" id="detailTeacherName" style="color: #1e293b; font-size: 1rem;">Margarita Vazquez Patricio</h6>
                    <span class="text-secondary" id="detailDateLabel" style="font-size: 0.8rem;">Sábado, 15 Ago 2026</span>
                </div>
            </div>

            <!-- Table of classes -->
            <div class="table-responsive-custom" style="border-radius: 8px; border: 1px solid #e2e8f0; max-height: 300px; overflow-y: auto;">
                <table class="asistencias-table mb-0" style="width: 100%;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                            <th class="p-2" style="font-size: 0.8rem; font-weight: 700; color: #475569;">Grupo</th>
                            <th class="p-2" style="font-size: 0.8rem; font-weight: 700; color: #475569;">Materia</th>
                            <th class="p-2" style="font-size: 0.8rem; font-weight: 700; color: #475569;">Aula</th>
                            <th class="p-2" style="font-size: 0.8rem; font-weight: 700; color: #475569; text-align: center;">Horario</th>
                            <th class="p-2" style="font-size: 0.8rem; font-weight: 700; color: #475569; text-align: center;">Horas</th>
                        </tr>
                    </thead>
                    <tbody id="detailTableBody">
                        <!-- Dynamic list of classes -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer-custom" style="background: #f8fafc; padding: 15px 20px; border-top: 1px solid #e2e8f0; justify-content: space-between; align-items: center; display: flex;">
            <div style="font-size: 0.85rem; color: #64748b;">
                Suma de horas del día: <strong class="text-dark" id="detailSumLabel">7 hrs</strong>
            </div>
            <button class="btn-action-teal" id="btnCloseDetailModalBtn">Aceptar</button>
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

    // Default mock teachers fallback if database call is empty
    const fallbackTeachers = [
        { id: 1, name: "Marco Antonio Rosas", area: "Matemáticas" },
        { id: 2, name: "Juan Pérez Domínguez", area: "Física" },
        { id: 3, name: "Margarita Vázquez Patricio", area: "Química & Biología" },
        { id: 4, name: "Brenda Denisse Martínez", area: "Lectura & Redacción" },
        { id: 5, name: "Cristian Enrique Rodríguez", area: "Computación" },
        { id: 6, name: "Daniel Hernández Mora", area: "Idiomas" }
    ];

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

    // Initialize module
    initAttendanceModule();

    async function initAttendanceModule() {
        renderPeriod();
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
                    fetch(urlDatos).then(r => r.ok ? r.json() : []),
                    fetch(urlBiometrico).then(r => r.ok ? r.json() : [])
                ]);

                attendanceData = resDatos;
                biometricLogs = resBiometrico;

                // Si ya hay registros en la base de datos, quitamos el estado de procesando
                if (biometricLogs && biometricLogs.length > 0) {
                    biometricFileProcessing = false;
                    const processingAlert = document.getElementById("processingAlert");
                    if (processingAlert) {
                        processingAlert.style.setProperty("display", "none", "important");
                    }
                }
                
                // Si el backend no regresa registros de docentes o viene vacío, usamos simulación para propósitos de diseño/demostración
                if (!attendanceData || attendanceData.length === 0) {
                    attendanceData = getSimulatedData(startDay, endDay);
                    biometricLogs = [];
                }
            } catch(e) {
                console.error("Error al obtener datos reales, usando simulación:", e);
                attendanceData = getSimulatedData(startDay, endDay);
                biometricLogs = [];
            }
        }

        // 2. Generate header row
        let headerRow1 = '<tr><th rowspan="2" class="docente-header">Docente</th>';
        let headerRow2 = '<tr>';

        for (let d = startDay; d <= endDay; d++) {
            const currentDayDate = new Date(currentYear, currentMonthIdx, d);
            const dayOfWeekIdx = currentDayDate.getDay();
            const dayOfWeekStr = daysWeekShort[dayOfWeekIdx];
            
            headerRow1 += `<th colspan="2" class="day-header">${d} ${dayOfWeekStr}</th>`;
            headerRow2 += `
                <th class="sub-col-header">TOT</th>
                <th class="sub-col-header">REAL</th>
            `;
        }

        headerRow1 += '</tr>';
        headerRow2 += '</tr>';

        gridHeader.innerHTML = headerRow1 + headerRow2;

        // 3. Generate body rows
        // 3. Generate body rows
        let bodyHtml = '';
        const searchQuery = document.getElementById("txtSearchTeacher") ? document.getElementById("txtSearchTeacher").value.toLowerCase().trim() : "";
        const filteredData = attendanceData.filter(teacher => {
            return (teacher.docente || "").toLowerCase().includes(searchQuery);
        });

        if (filteredData.length === 0) {
            bodyHtml = `
                <tr>
                    <td colspan="${(endDay - startDay + 1) * 2 + 1}" class="text-center py-5 text-secondary" style="font-size: 0.9rem; background: white;">
                        <i class="fa-solid fa-user-slash d-block fs-4 mb-2 text-muted"></i>
                        No se encontraron docentes con el nombre "${searchQuery}".
                    </td>
                </tr>
            `;
        } else {
            filteredData.forEach(teacher => {
            const teacherName = teacher.docente || "Docente";
            const initials = teacherName.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
            
            // Generar departamento sutil según su nombre
            let area = "Docencia";
            if (teacherName.toLowerCase().includes("matematicas") || teacherName.toLowerCase().includes("rodriguez") || (teacher.id_docente && teacher.id_docente % 2 === 0)) area = "Matemáticas";
            else if (teacherName.toLowerCase().includes("fisica") || teacherName.toLowerCase().includes("perez") || (teacher.id_docente && teacher.id_docente % 3 === 0)) area = "Física";
            else if (teacherName.toLowerCase().includes("quimica") || teacherName.toLowerCase().includes("vazquez")) area = "Química";
            else if (teacherName.toLowerCase().includes("ingles") || teacherName.toLowerCase().includes("mora") || teacherName.toLowerCase().includes("hernandez")) area = "Idiomas";

            bodyHtml += `
                <tr>
                    <td>
                        <div class="teacher-cell">
                            <div class="teacher-avatar">${initials}</div>
                            <div class="teacher-info">
                                <span class="teacher-name">${teacherName}</span>
                                <span class="teacher-sub">${area}</span>
                            </div>
                        </div>
                    </td>
            `;

            for (let d = startDay; d <= endDay; d++) {
                const dateString = `${currentYear}-${monthNumStr}-${String(d).padStart(2, '0')}`;
                
                // Buscar el registro correspondiente a esta fecha
                const dayRecord = teacher.dias ? teacher.dias.find(dr => dr.fecha === dateString) : null;
                
                let totHours = 0;
                let realHours = 0;

                if (dayRecord) {
                    totHours = parseFloat(dayRecord.total || 0);
                    realHours = parseFloat(dayRecord.real || 0);
                }

                const teacherId = teacher.id_docente || teacher.id;
                const bioRecord = biometricLogs ? biometricLogs.find(log => {
                    return log.id_docente === teacherId && log.fecha === dateString;
                }) : null;

                let realHoursText = '-';
                let realClass = '';
                let observations = '';

                if (totHours > 0) {
                    const hasBiometricData = biometricLogs && biometricLogs.length > 0;

                    if (hasBiometricData) {
                        if (bioRecord) {
                            const hrsWorked = parseFloat(bioRecord.horas_trabajadas || 0);
                            realHoursText = hrsWorked.toFixed(2);
                            observations = bioRecord.observaciones || '';

                            const estado = bioRecord.estado ? bioRecord.estado.toLowerCase() : '';
                            if (estado.includes('completo')) {
                                realClass = 'status-completo';
                            } else if (estado.includes('parcial') || estado.includes('retardo') || estado.includes('advertencia')) {
                                realClass = 'status-parcial';
                            } else {
                                realClass = 'status-falta';
                            }
                        } else {
                            // Si ya se importó y no hay registro, es falta
                            realHoursText = '0.00';
                            realClass = 'status-falta';
                            observations = 'Sin registros de entrada ni salida (Falta)';
                        }
                    } else if (biometricFileProcessing) {
                        // Mostramos un loader indicador de que está cargando en la base de datos
                        realHoursText = `<span class="spinner-border spinner-border-sm text-secondary" style="width: 12px; height: 12px; margin-right: 2px;" role="status"></span><span style="font-size: 0.72rem; color: #64748b;">Proc...</span>`;
                        realClass = '';
                    } else {
                        realHoursText = '-';
                        realClass = '';
                    }
                }

                let totCellHtml = '';
                if (totHours > 0) {
                    totCellHtml = `<span class="hour-cell tot-val clickable" data-docente-id="${teacherId}" data-docente-name="${teacherName}" data-fecha="${dateString}" data-total="${totHours}">${totHours.toFixed(1)}</span>`;
                } else {
                    totCellHtml = `<span class="empty-placeholder">-</span>`;
                }
                
                const realText = realHoursText !== '-' ? realHoursText : '<span class="empty-placeholder">-</span>';
                const tooltipAttr = observations ? `title="${observations}" style="cursor: help;"` : '';

                bodyHtml += `
                    <td>${totCellHtml}</td>
                    <td><span class="hour-cell real-val ${realClass}" ${tooltipAttr}>${realText}</span></td>
                `;
            }

            bodyHtml += '</tr>';
        });
        }

        gridBody.innerHTML = bodyHtml;
    }

    // Helper para generar simulación realista si el backend no cuenta con datos para este rango
    function getSimulatedData(startDay, endDay) {
        const monthNumStr = String(currentMonthIdx + 1).padStart(2, '0');
        
        return fallbackTeachers.map(ft => {
            const dias = [];
            for (let d = startDay; d <= endDay; d++) {
                const currentDayDate = new Date(currentYear, currentMonthIdx, d);
                const dayOfWeekIdx = currentDayDate.getDay();
                
                let tot = 0;
                let real = 0;
                
                if (dayOfWeekIdx === 6) { // Sábado
                    if (ft.id % 2 === 0) tot = 4.0;
                    else if (ft.id % 3 === 0) tot = 3.0;
                } else if (dayOfWeekIdx === 0) { // Domingo
                    if (ft.id === 3 || ft.id === 5) tot = 4.0;
                } else { // Lunes a Viernes
                    if (ft.id % 3 === 0 && (dayOfWeekIdx === 1 || dayOfWeekIdx === 3)) tot = 4.5;
                    else if (ft.id % 2 === 0 && (dayOfWeekIdx === 2 || dayOfWeekIdx === 4)) tot = 5.0;
                    else if (ft.id % 5 === 0 && dayOfWeekIdx === 5) tot = 3.5;
                }

                if (tot > 0) {
                    const randomFactor = (ft.id * 7 + d * 13) % 100;
                    if (randomFactor > 90) real = 0.0;
                    else if (randomFactor > 75) real = Math.max(1.0, tot - 0.5 - (randomFactor % 3) * 0.5);
                    else real = tot;
                }

                dias.push({
                    fecha: `${currentYear}-${monthNumStr}-${String(d).padStart(2, '0')}`,
                    total: tot,
                    real: real
                });
            }
            
            return {
                id_docente: ft.id,
                docente: ft.name,
                dias: dias
            };
        });
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

    // Search input listener
    const txtSearchTeacher = document.getElementById("txtSearchTeacher");
    if (txtSearchTeacher) {
        txtSearchTeacher.addEventListener("input", function() {
            renderPeriod(true); // Redraw list without network fetch
        });
    }
 
    // Detail Modal Element bindings
    const detailModal = document.getElementById("detailModal");
    const btnCloseDetailModal = document.getElementById("btnCloseDetailModal");
    const btnCloseDetailModalBtn = document.getElementById("btnCloseDetailModalBtn");
    const detailTeacherAvatar = document.getElementById("detailTeacherAvatar");
    const detailTeacherName = document.getElementById("detailTeacherName");
    const detailDateLabel = document.getElementById("detailDateLabel");
    const detailTableBody = document.getElementById("detailTableBody");
    const detailSumLabel = document.getElementById("detailSumLabel");

    // Click handler for grid cells via delegation
    gridBody.addEventListener("click", function(e) {
        const clickableCell = e.target.closest(".hour-cell.tot-val.clickable");
        if (clickableCell) {
            const docenteId = clickableCell.getAttribute("data-docente-id");
            const docenteName = clickableCell.getAttribute("data-docente-name");
            const fecha = clickableCell.getAttribute("data-fecha");
            const totalHours = clickableCell.getAttribute("data-total");
            openDetailModal(docenteId, docenteName, fecha, totalHours);
        }
    });

    async function openDetailModal(docenteId, name, fechaStr, totalHours) {
        // Initials for avatar
        const initials = name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
        detailTeacherAvatar.textContent = initials;
        detailTeacherName.textContent = name;

        // Human readable date string formatting
        try {
            const dateObj = new Date(fechaStr + 'T00:00:00');
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            let formattedDate = dateObj.toLocaleDateString('es-MX', options);
            formattedDate = formattedDate.charAt(0).toUpperCase() + formattedDate.slice(1);
            detailDateLabel.textContent = formattedDate;
        } catch(e) {
            detailDateLabel.textContent = fechaStr;
        }

        detailSumLabel.textContent = `${parseFloat(totalHours).toFixed(1)} hrs`;

        // Render Loading inside modal table body
        detailTableBody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-4" style="background: white;">
                    <div class="spinner-border text-secondary spinner-border-sm me-2" role="status"></div>
                    <span class="text-secondary" style="font-size: 0.85rem;">Cargando desglose de horario...</span>
                </td>
            </tr>
        `;

        detailModal.classList.add("active");

        try {
            const url = `/asistencias_docentes/detalle?fecha=${fechaStr}&id_docente=${docenteId}`;
            const response = await fetch(url);
            if (!response.ok) throw new Error("Error en la respuesta del servidor");
            const details = await response.json();

            if (!details || details.length === 0) {
                detailTableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-4 text-secondary" style="font-size: 0.85rem; background: white;">
                            No se encontraron detalles de clases programadas para esta fecha.
                        </td>
                    </tr>
                `;
                return;
            }

            // Group by group + time slot + classroom to detect joint classes
            const groupedDetails = {};
            details.forEach(item => {
                const key = `${item.grupo}_${item.hora_inicio}_${item.hora_fin}_${item.aula || 'N/A'}`;
                if (!groupedDetails[key]) {
                    groupedDetails[key] = {
                        grupo: item.grupo,
                        aula: item.aula || "N/A",
                        hora_inicio: item.hora_inicio,
                        hora_fin: item.hora_fin,
                        duracion: item.duracion,
                        materias: []
                    };
                }
                groupedDetails[key].materias.push(item.materia);
            });

            let bodyHtml = '';
            Object.values(groupedDetails).forEach(item => {
                const materiasHtml = item.materias.map(m => `
                    <div style="font-size: 0.82rem; color: #334155; font-weight: 500; padding: 2px 0;">
                        ${m}
                    </div>
                `).join('<div style="border-top: 1px solid #f1f5f9; margin: 2px 0;"></div>');

                const grupo = item.grupo || "Grupo";
                const aula = item.aula || "N/A";
                const formatTime = (t) => t ? t.substring(0, 5) : "";
                const horaStr = `${formatTime(item.hora_inicio)} - ${formatTime(item.hora_fin)}`;
                const duration = parseFloat(item.duracion || 0).toFixed(1);

                bodyHtml += `
                    <tr style="border-bottom: 1px solid #f1f5f9; background: white;">
                        <td class="p-2 align-middle text-start" style="font-size: 0.82rem; font-weight: 600; color: rgb(38, 104, 123);">${grupo}</td>
                        <td class="p-2 align-middle text-start" style="padding-top: 8px !important; padding-bottom: 8px !important;">
                            ${materiasHtml}
                        </td>
                        <td class="p-2 align-middle text-start" style="font-size: 0.82rem; color: #64748b;">${aula}</td>
                        <td class="p-2 align-middle text-center" style="font-size: 0.82rem; color: #475569; font-family: monospace;">${horaStr}</td>
                        <td class="p-2 align-middle text-center font-weight-bold" style="font-size: 0.82rem; color: #1e293b;">${duration} hrs</td>
                    </tr>
                `;
            });
            detailTableBody.innerHTML = bodyHtml;

        } catch(e) {
            console.error("Error fetching detailed schedules:", e);
            detailTableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-4 text-danger" style="font-size: 0.85rem; background: white;">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i>
                        Error al cargar la información. Inténtalo de nuevo.
                    </td>
                </tr>
            `;
        }
    }

    function closeDetailModal() {
        detailModal.classList.remove("active");
    }

    btnCloseDetailModal.addEventListener("click", closeDetailModal);
    btnCloseDetailModalBtn.addEventListener("click", closeDetailModal);
    detailModal.addEventListener("click", function(e) {
        if (e.target === detailModal) {
            closeDetailModal();
        }
    });
});
</script>
@endsection
