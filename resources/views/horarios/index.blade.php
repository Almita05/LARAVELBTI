@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Tom Select (Autocomplete en Selects) -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<style>
/* Wrapper to overlay the layout's teal background and make it clean light-gray */
.horarios-page-wrapper {
    background: #f4f6f9;
    margin: -25px;
    padding: 30px;
    min-height: calc(100vh - 85px);
    color: #1e293b;
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
}

/* Dashboard Cards (White theme with subtle shadow) */
.glass-card {
    background: #ffffff !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 12px !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    color: #1e293b !important;
}

.dashboard-header {
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 15px;
    margin-bottom: 25px;
}

.dashboard-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: rgb(38, 104, 123); /* Color del sistema */
}

.dashboard-subtitle {
    font-size: 0.9rem;
    color: #64748b;
}

.badge-nivel {
    background-color: rgba(38, 104, 123, 0.1);
    color: rgb(38, 104, 123);
    border: 1px solid rgba(38, 104, 123, 0.2);
    font-size: 0.85rem;
    font-weight: 600;
    padding: 4px 10.5px;
    border-radius: 20px;
    display: inline-block;
    vertical-align: middle;
}

/* User Badge */
.user-badge {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(38, 104, 123, 0.1);
    color: rgb(38, 104, 123);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    border: 1px solid rgba(38, 104, 123, 0.2);
}

.user-info-text {
    line-height: 1.2;
}

.user-name {
    font-size: 0.9rem;
    font-weight: 600;
    color: #1e293b;
}

.user-role {
    font-size: 0.75rem;
    color: #64748b;
}

/* Return Button */
.btn-regresar-light {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: rgb(38, 104, 123);
    font-weight: 600;
    padding: .375rem .75rem;
    border-radius: .375rem;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.btn-regresar-light:hover {
    background: #f8fafc;
    color: rgb(38, 104, 123);
    border-color: #94a3b8;
}

/* Sidebar - Seleccionar Grupo */
.sidebar-panel,
.calendar-panel,
.edit-panel {
    height: 68vh;
    display: flex;
    flex-direction: column;
}

.sidebar-title,
.edit-panel-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 15px;
}

/* Search Box */
.search-container {
    position: relative;
    margin-bottom: 15px;
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 0.85rem;
}

.search-input-custom {
    background: #f1f5f9 !important;
    border: 1px solid #cbd5e1 !important;
    color: #1e293b !important;
    border-radius: 8px;
    padding-left: 35px !important;
}

.search-input-custom:focus {
    background: #ffffff !important;
    border-color: rgb(38, 104, 123) !important;
    box-shadow: 0 0 0 2px rgba(38, 104, 123, 0.15) !important;
}

/* Groups List */
.group-list {
    overflow-y: auto;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding-right: 5px;
}

/* Scrollbar styling */
.group-list::-webkit-scrollbar,
.calendar-container::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
.group-list::-webkit-scrollbar-thumb,
.calendar-container::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.1);
    border-radius: 3px;
}

.group-item {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #334155;
    padding: 10px 14px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 500;
    text-align: left;
    cursor: pointer;
    transition: all 0.2s ease;
}

.group-item:hover {
    background: #f8fafc;
    color: #1e293b;
    border-color: #cbd5e1;
}

.group-item.active {
    background: #f1f5f9 !important;
    border: 1px solid #e2e8f0 !important;
    border-left: 4px solid rgb(38, 104, 123) !important;
    color: rgb(38, 104, 123) !important;
    font-weight: 700;
}

/* Calendar Header */
.calendar-header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.calendar-group-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

.calendar-group-title span {
    color: rgb(38, 104, 123);
}

.btn-action-system {
    background: rgb(38, 104, 123) !important;
    border: none;
    color: white !important;
    font-size: 0.85rem;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.btn-action-system:hover {
    background: rgb(28, 79, 94) !important;
    transform: translateY(-1px);
}

/* Calendar Table */
.calendar-container {
    overflow-x: auto;
    overflow-y: auto;
    flex-grow: 1;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
}

.calendar-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 750px;
    background: #ffffff;
}

.calendar-table th {
    background: #f8fafc !important;
    border: 1px solid #e2e8f0;
    color: #475569 !important;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    text-align: center;
    padding: 12px 10px;
}

.calendar-table td {
    border: 1px solid #e2e8f0 !important;
    padding: 0;
    height: 80px;
    vertical-align: middle;
    position: relative;
    background: #ffffff !important;
}

.time-col {
    background: #f8fafc !important;
    color: #475569 !important;
    font-size: 0.75rem;
    font-weight: 700;
    text-align: center;
    width: 90px;
    padding: 10px !important;
    border-right: 1px solid #e2e8f0 !important;
}

/* Calendar Cell Slots */
.cell-slot {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #cbd5e1;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
}

.cell-slot:hover {
    background: #f8fafc;
}

.cell-slot.selected {
    background: rgba(38, 104, 123, 0.05) !important;
    border: 2px dashed rgb(38, 104, 123);
}

.cell-slot .add-btn-icon {
    display: none;
    color: rgb(38, 104, 123);
    font-size: 1.1rem;
}

.cell-slot:hover .add-btn-icon {
    display: block;
}

.cell-slot:hover .placeholder-dash {
    display: none;
}

/* Assigned Class Cards (Light theme style matching the mock colors) */
.class-card {
    position: relative;
    width: calc(100% - 8px);
    min-height: 72px;
    height: auto;
    margin: 4px;
    border-radius: 6px;
    padding: 6px 10px;
    text-align: left;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
    background: #f1f5f9; /* Light gray-blue */
    border: 1px solid #cbd5e1;
    border-left: 4px solid rgb(38, 104, 123); /* System Color indicator */
    box-sizing: border-box;
    align-self: center;
}

.class-subject, .class-detail, .class-card hr {
    flex-shrink: 0;
}

.class-subject {
    font-size: 0.78rem;
    font-weight: 700;
    color: rgb(38, 104, 123); /* System color text */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.class-detail {
    font-size: 0.68rem;
    color: #475569;
    display: flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-top: 1px;
}

.class-detail i {
    color: #64748b;
    font-size: 0.65rem;
}

/* Drag and Drop visual feedback */
.class-card {
    cursor: grab;
    user-select: none;
}
.class-card:active {
    cursor: grabbing;
}
.class-card.dragging {
    opacity: 0.45;
    transform: scale(0.96);
    border: 2px dashed rgb(38, 104, 123) !important;
}

.cell-slot.drag-over {
    background: rgba(38, 104, 123, 0.12) !important;
    border: 2px dashed rgb(38, 104, 123) !important;
    box-shadow: inset 0 0 8px rgba(38, 104, 123, 0.15);
}

/* Form Fields */
.edit-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 10px;
}

.btn-close-panel {
    background: transparent;
    border: none;
    color: #64748b;
    font-size: 0.9rem;
    cursor: pointer;
    transition: color 0.2s;
}

.btn-close-panel:hover {
    color: #1e293b;
}

.edit-form {
    flex-grow: 1;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding-right: 5px;
}

.form-group-custom {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.form-group-custom label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #475569;
}

.form-input-custom {
    background: #ffffff !important;
    border: 1px solid #cbd5e1 !important;
    color: #1e293b !important;
    border-radius: 8px;
    padding: 8px 12px !important;
    font-size: 0.85rem !important;
    width: 100%;
}

.form-input-custom:focus {
    border-color: rgb(38, 104, 123) !important;
    box-shadow: 0 0 0 2px rgba(38, 104, 123, 0.15) !important;
    outline: none;
}

.form-input-custom[readonly] {
    background: #f1f5f9 !important;
    color: #64748b !important;
    border-color: #e2e8f0 !important;
    cursor: not-allowed;
}



.form-actions-custom {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.btn-form-cancel {
    flex: 1;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: #475569;
    font-size: 0.8rem;
    font-weight: 600;
    padding: 8px;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.btn-form-cancel:hover {
    background: #f8fafc;
    border-color: #94a3b8;
}

.btn-form-delete {
    background: #ef4444;
    border: none;
    color: #ffffff;
    font-size: 0.8rem;
    font-weight: 600;
    padding: 8px;
    border-radius: 8px;
    transition: all 0.2s ease;
    margin-top: 5px;
}

.btn-form-delete:hover {
    background: #dc2626;
}

/* Empty States */
.empty-state-message {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #94a3b8;
    text-align: center;
    gap: 15px;
}

.empty-state-message i {
    font-size: 3rem;
    color: #cbd5e1;
}
</style>

<div class="horarios-page-wrapper">

    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('home') }}" class="btn-regresar-light">
            <i class="fa-solid fa-arrow-left me-2"></i>
            Regresar
        </a>
    </div>

    <!-- Main Dashboard Container -->
    <div class="horarios-dashboard">

        <!-- Header -->
        <div class="dashboard-header d-flex justify-content-between align-items-center">
            <div>
                <h1 class="dashboard-title">Horarios del Grupo</h1>
                <p class="dashboard-subtitle mb-0">Administra el horario de clases del grupo seleccionado.</p>
            </div>
            
            <!-- User Profile Badge -->
            <div class="user-badge">
                <div class="user-info-text text-end d-none d-sm-block">
                    <div class="user-name">Coordinador Académico</div>
                    <div class="user-role">Coordinación</div>
                </div>
                <div class="user-avatar">
                    <i class="fa-solid fa-user"></i>
                </div>
            </div>
        </div>

        <!-- Dashboard Panels Grid -->
        <div class="row g-3">
            
            <!-- Left Panel: Select Group Sidebar -->
            <div class="col-lg-3 col-md-4">
                <div class="glass-card sidebar-panel p-3">
                    <h4 class="sidebar-title">Seleccionar Grupo</h4>
                    
                    <!-- Search Input -->
                    <div class="search-container">
                        <i class="fa-solid fa-magnifying-glass search-icon"></i>
                        <input type="text" id="groupSearchInput" class="form-control search-input-custom" placeholder="Buscar grupo o alumno...">
                    </div>

                    <!-- Groups List -->
                    <div id="groupListContainer" class="group-list">
                        <div class="text-center py-4" id="groupListSpinner">
                            <div class="spinner-border text-secondary spinner-border-sm"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Center Panel: Calendar Grid -->
            <div class="col-lg-6 col-md-8">
                <div class="glass-card calendar-panel p-3">
                    <div class="calendar-header-actions">
                        <h3 class="calendar-group-title" id="calendarGroupTitle">
                            Horario del Grupo: <span id="selectedGroupName">Ninguno</span>
                            <span id="selectedGroupNivel" class="badge-nivel ms-2" style="display:none;"></span>
                        </h3>
                        <div class="d-flex gap-2">
                            <button class="btn-regresar-light py-1" style="font-size: 0.85rem;">
                                <i class="fa-solid fa-user-tie me-2"></i>Vista del Docente
                            </button>
                            <button class="btn btn-action-system py-1" id="btnSaveChanges">
                                <i class="fa-solid fa-arrows-rotate me-2"></i>Refrescar
                            </button>
                        </div>
                    </div>

                    <!-- Progress Bar for Academic Level -->
                    <div id="progressContainer" style="display:none; margin-top: 5px; margin-bottom: 20px; padding: 0 10px;">
                        <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.75rem; color: #64748b;">
                            <span>Inicio Periodo: <strong id="progressStartDate">—</strong></span>
                            <span id="progressPercent" style="font-weight: 600; color: rgb(38, 104, 123);">0%</span>
                            <span>Fin Periodo: <strong id="progressEndDate">—</strong></span>
                        </div>
                        <div class="progress" style="height: 6px; background-color: #e2e8f0; border-radius: 3px; overflow: hidden;">
                            <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%; background-color: rgb(38, 104, 123); transition: width 0.4s ease;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <!-- Calendar Table wrapper -->
                    <div class="calendar-container">
                        <div id="calendarEmptyState" class="empty-state-message">
                            <i class="fa-solid fa-calendar-days"></i>
                            <div>Selecciona un grupo del panel izquierdo para ver y gestionar su horario.</div>
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
            </div>

            <!-- Right Panel: Edit Class Form -->
            <div class="col-lg-3 col-md-12 mt-md-3 mt-lg-0">
                <div class="glass-card edit-panel p-3">
                    <div class="edit-panel-header">
                        <h4 class="edit-panel-title">Editar Clase</h4>
                        <button class="btn-close-panel" id="btnCloseEditPanel" title="Cerrar panel">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div id="editEmptyState" class="empty-state-message w-100" style="display: flex; flex-direction: column; justify-content: flex-start; align-items: stretch; text-align: left; height: 100%; overflow-y: auto; padding: 10px 0;">
                        <!-- Default placeholder, shown when no group is selected -->
                        <div id="noGroupSelectedMessage" class="text-center py-5">
                            <i class="fa-solid fa-calendar-days" style="font-size: 2.5rem; color: #cbd5e1; margin-bottom: 15px;"></i>
                            <div class="text-secondary" style="font-size: 0.9rem;">Selecciona un grupo para ver el calendario de periodos.</div>
                        </div>

                        <!-- Group Calendar periods list, shown when a group is active -->
                        <div id="groupCalendarInfo" style="display: none; width: 100%;">
                            <h5 style="font-size: 0.95rem; font-weight: 700; color: rgb(38, 104, 123); margin-bottom: 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px; display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-calendar-days"></i> Calendario de Periodos
                            </h5>
                            <div id="groupPeriodsList" style="display: flex; flex-direction: column; gap: 10px; width: 100%;"></div>
                            
                            <hr style="margin: 15px 0; opacity: 0.15;">
                            <div class="text-center text-secondary" style="font-size: 0.8rem; padding: 10px 15px; background: rgba(38,104,123,0.03); border-radius: 8px; border: 1px dashed rgba(38,104,123,0.2);">
                                <i class="fa-solid fa-circle-info me-1"></i> Selecciona una celda de horario para agregar o editar una clase.
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
                            <select id="formMateriaSelect" class="form-select form-input-custom form-select-custom-style" required>
                                <option value="">Seleccione Materia</option>
                            </select>
                        </div>

                        <!-- Materia Multiple Selection Container -->
                        <div id="multipleMateriaContainer" class="form-group-custom" style="display: none;">
                            <label>Materias *</label>
                            <select id="formMateriaSelectMultiple" class="form-select form-input-custom form-select-custom-style" multiple>
                                <!-- options populated dynamically -->
                            </select>
                        </div>

                        <!-- Checkbox for multiple classes -->
                        <div class="form-check mb-3 mt-3">
                            <input class="form-check-input" type="checkbox" id="chkMultipleClasses">
                            <label class="form-check-label" for="chkMultipleClasses" style="font-size: 0.85rem; font-weight: 600; color: rgb(38, 104, 123);">
                                Impartir más de una materia en esta hora
                            </label>
                        </div>

                        <!-- Docente Selection (Always Single) -->
                        <div class="form-group-custom">
                            <label>Docente *</label>
                            <select id="formDocenteSelect" class="form-select form-input-custom form-select-custom-style" required>
                                <option value="">Seleccione Docente</option>
                            </select>
                        </div>

                        <div class="form-group-custom">
                            <label>Aula</label>
                            <select id="formAulaSelect" class="form-select form-input-custom form-select-custom-style">
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
                            <button type="button" class="btn-action-system btn-form-save" id="btnSaveClass">Guardar</button>
                        </div>

                        <button type="button" class="btn-form-delete" id="btnDeleteClass" style="display: none;">
                            <i class="fa-solid fa-trash me-2"></i>Eliminar Clase
                        </button>

                    </form>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- Scripts Section for Interaction -->
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
        "07:00 - 08:00",
        "08:00 - 09:00",
        "09:00 - 10:00",
        "10:00 - 11:00",
        "11:00 - 12:00",
        "12:00 - 13:00",
        "13:00 - 14:00"
    ];

    // Default days
    let dayNames = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];

    // DOM Elements
    const groupListContainer = document.getElementById("groupListContainer");
    const groupListSpinner = document.getElementById("groupListSpinner");
    const groupSearchInput = document.getElementById("groupSearchInput");
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
            const [groupsRes, docentesRes, materiasRes] = await Promise.all([
                fetch('/grupos/lista?limit=1000').then(r => r.json()).catch(() => ({ data: [] })),
                fetch('/docentes/lista').then(r => r.json()).catch(() => ({ data: [] })),
                fetch('/materias/lista').then(r => r.json()).catch(() => ({ data: [] }))
            ]);

            groups = groupsRes.data || [];
            docentes = docentesRes.data || [];
            materias = materiasRes.data || [];

            populateDropdowns();
            renderGroupsList(groups);

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
        materias.forEach(mat => {
            const opt1 = document.createElement("option");
            opt1.value = mat.id_materia || mat.id || mat.nombreMateria;
            opt1.textContent = mat.nombreMateria;
            formMateriaSelect.appendChild(opt1);

            const opt2 = document.createElement("option");
            opt2.value = mat.id_materia || mat.id || mat.nombreMateria;
            opt2.textContent = mat.nombreMateria;
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
        } else {
            singleMateriaContainer.style.display = "block";
            multipleMateriaContainer.style.display = "none";
            formMateriaSelect.setAttribute("required", "required");
            formMateriaSelectMultiple.removeAttribute("required");
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
            if (idNivelActualDb !== null && idNivelActualDb >= 11) {
                idTipoPeriodo = 1; // SEMESTRAL
            } else {
                idTipoPeriodo = 2; // TRIMESTRAL (default)
            }
        }

        const isTrimestral = idTipoPeriodo === 2;
        const weeksPerPeriod = isTrimestral ? 13 : 26;
        const periodLabel = isTrimestral ? "Trim." : "Sem.";

        const today = new Date();
        const todayUTC = new Date(Date.UTC(today.getFullYear(), today.getMonth(), today.getDate()));

        if (todayUTC.getTime() < currentDate.getTime()) {
            return `1° ${periodLabel}`;
        }

        let periodNumber = 1;

        while (true) {
            let periodEndDate = new Date(currentDate.getTime() + (weeksPerPeriod * 7 * 24 * 60 * 60 * 1000) - (24 * 60 * 60 * 1000));

            if (todayUTC.getTime() >= currentDate.getTime() && todayUTC.getTime() <= periodEndDate.getTime()) {
                return `${periodNumber}° ${periodLabel}`;
            }

            if (groupEndDate && currentDate.getTime() > groupEndDate.getTime()) {
                break;
            }
            if (isTrimestral && periodNumber > 10) break;
            if (!isTrimestral && periodNumber > 8) break;

            if (groupEndDate && periodEndDate.getTime() >= groupEndDate.getTime()) {
                if (todayUTC.getTime() > groupEndDate.getTime()) {
                    return `${periodNumber}° ${periodLabel} (Fin)`;
                }
                break;
            }

            currentDate = new Date(periodEndDate.getTime() + (24 * 60 * 60 * 1000));
            periodNumber++;
        }

        if (groupEndDate && todayUTC.getTime() > groupEndDate.getTime()) {
            return `Fin`;
        }

        return '';
    }

    function renderGroupsList(list) {
        groupListSpinner.style.display = 'none';
        
        if (list.length === 0) {
            groupListContainer.innerHTML = '<div class="text-secondary text-center">No hay grupos.</div>';
            return;
        }

        groupListContainer.innerHTML = '';
        list.forEach(group => {
            const btn = document.createElement("button");
            btn.className = "group-item";
            btn.style.display = "flex";
            btn.style.justifyContent = "space-between";
            btn.style.alignItems = "center";
            
            let periodText = "";
            if (group.nombre_nivel) {
                periodText = group.nombre_nivel
                    .replace("er", "°")
                    .replace("o", "°")
                    .replace("Trimestre", "Trim.")
                    .replace("Semestre", "Sem.");
            } else {
                periodText = getCurrentPeriodText(group);
            }
            const badgeHtml = periodText 
                ? `<span class="group-period-badge" style="font-size: 0.68rem; font-weight: 700; background: rgba(38, 104, 123, 0.08); color: rgb(38, 104, 123); padding: 2px 7px; border-radius: 8px; border: 1.2px solid rgba(38, 104, 123, 0.15);">${periodText}</span>` 
                : '';
                
            btn.innerHTML = `<span>${group.clave}</span>${badgeHtml}`;
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
        divider.style.fontWeight = "800";
        divider.style.color = "#64748b";
        divider.style.textTransform = "uppercase";
        divider.style.borderTop = "1px dashed rgba(0,0,0,0.08)";
        divider.style.marginTop = "10px";
        divider.style.letterSpacing = "0.5px";
        divider.innerHTML = `<i class="fa-solid fa-graduation-cap me-1" style="color: #10b981;"></i> Alumnos Encontrados`;
        groupListContainer.appendChild(divider);

        students.forEach(student => {
            const studentGroupId = student.idGrupo || student.id_Grupo;
            const matchedGroup = groups.find(g => g.id === studentGroupId);
            if (!matchedGroup) return;

            const btn = document.createElement("button");
            btn.className = "group-item";
            btn.style.display = "flex";
            btn.style.justifyContent = "space-between";
            btn.style.alignItems = "center";
            btn.style.borderLeft = "3.5px solid #10b981";
            btn.style.padding = "8px 12px";
            btn.style.marginTop = "5px";
            btn.style.background = "rgba(16, 185, 129, 0.02)";

            btn.innerHTML = `
                <div style="text-align: left;">
                    <div style="font-weight: 700; font-size: 0.8rem; color: #334155; display: flex; align-items: center; gap: 4px;">
                        <i class="fa-solid fa-user" style="color: #10b981; font-size: 0.72rem;"></i>
                        ${student.nombre} ${student.apPaterno}
                    </div>
                    <div style="font-size: 0.65rem; color: #64748b; margin-top: 2px;">Grupo: <strong>${matchedGroup.clave}</strong></div>
                </div>
                <span class="badge" style="font-size: 0.65rem; background: rgba(16, 185, 129, 0.1); color: #0f766e; padding: 2px 6px; border-radius: 8px; font-weight: 600;">Ir al grupo</span>
            `;

            btn.addEventListener("click", () => {
                selectGroup(matchedGroup);
            });

            groupListContainer.appendChild(btn);
        });
    }

    groupSearchInput.addEventListener("input", function(e) {
        const query = e.target.value.toLowerCase().trim();
        clearTimeout(searchDebounceTimeout);

        // 1. Filtrar grupos locales de inmediato
        const filteredGroups = groups.filter(g => g.clave.toLowerCase().includes(query));
        renderGroupsList(filteredGroups);

        // 2. Si tiene 3 o más caracteres, buscar alumnos con debounce
        if (query.length >= 3) {
            searchDebounceTimeout = setTimeout(() => {
                fetch(`/alumnos/lista?limit=5&search=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(resp => {
                        const students = resp.data || [];
                        if (students.length > 0) {
                            renderGroupsAndStudentsList(filteredGroups, students);
                        }
                    })
                    .catch(err => console.error("Error al buscar alumnos en horarios:", err));
            }, 300);
        }
    });

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
        
        // Ocultar elementos temporalmente hasta que se carguen los detalles extendidos
        document.getElementById("progressContainer").style.display = "none";
        selectedGroupNivel.style.display = "none";
        
        const items = groupListContainer.getElementsByClassName("group-item");
        Array.from(items).forEach(item => {
            if (item.textContent === group.clave) {
                item.classList.add("active");
            } else {
                item.classList.remove("active");
            }
        });

        // Determinar días basados en la clave del grupo (BGNE -> Sábado, Domingo; Cualquier otro -> Lunes a Viernes)
        const isBgne = group.clave.toUpperCase().startsWith("BGNE");
        dayNames = isBgne ? ["Sábado", "Domingo"] : ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];

        // Configurar bloques de horario basados en si es BGNE o no
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
            timeBlocks = [
                "07:00 - 08:00",
                "08:00 - 09:00",
                "09:00 - 10:00",
                "10:00 - 11:00",
                "11:00 - 12:00",
                "12:00 - 13:00",
                "13:00 - 14:00"
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
                    
                    // Mostrar nivel académico
                    if (g.nombre_nivel) {
                        selectedGroupNivel.textContent = g.nombre_nivel;
                        selectedGroupNivel.style.display = "inline-block";
                    }
                    
                    // Calcular y mostrar barra de progreso
                    let fechaInicioNivel = g.fechaInicioNivel;
                    let fechaFinNivel = g.fechaFinNivel;
                    
                    if (!fechaInicioNivel || !fechaFinNivel) {
                        const calculated = calcularPeriodoNivel(g);
                        if (calculated) {
                            fechaInicioNivel = calculated.fechaInicioNivel;
                            fechaFinNivel = calculated.fechaFinNivel;
                        }
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
                }
            })
            .catch(err => {
                console.error("Error al obtener progreso de nivel:", err);
            });
    }

    function calcularPeriodoNivel(g) {
        if (!g.fechaInicio) return null;
        
        // Parsear fechaInicio de forma segura
        let fechaInicioAbs = new Date(g.fechaInicio);
        if (isNaN(fechaInicioAbs.getTime())) return null;
        
        // Convertir a fecha UTC pura para evitar desfases de zona horaria del cliente
        let fechaInicioNivel = new Date(Date.UTC(
            fechaInicioAbs.getUTCFullYear(),
            fechaInicioAbs.getUTCMonth(),
            fechaInicioAbs.getUTCDate()
        ));
        
        let idTipoPeriodo = g.id_tipoPeriodo;
        let idNivelActualDb = g.id_nivel_academico;
        
        if (idTipoPeriodo === null || idTipoPeriodo === undefined) {
            if (idNivelActualDb !== null && idNivelActualDb >= 11) {
                idTipoPeriodo = 1; // SEMESTRAL
            } else {
                idTipoPeriodo = 2; // TRIMESTRAL (default)
            }
        }
        
        let idNivel = 1;
        if (idTipoPeriodo === 2) {
            idNivel = 1; // 1er Trimestre
        } else if (idTipoPeriodo === 1) {
            idNivel = 11; // 1er Semestre
        }
        
        const now = new Date();
        const today = new Date(Date.UTC(now.getFullYear(), now.getMonth(), now.getDate()));
        
        let fechaFinNivel = new Date(fechaInicioNivel.getTime());
        
        const getDuracionSemanas = (levelId) => {
            if (levelId >= 1 && levelId <= 10) return 13;
            if (levelId >= 11 && levelId <= 18) return 26;
            return 13;
        };
        
        const hasNextNivel = (levelId) => {
            if (idTipoPeriodo === 2) return levelId < 10;
            if (idTipoPeriodo === 1) return levelId < 18;
            return false;
        };
        
        while (true) {
            const duracionSemanas = getDuracionSemanas(idNivel);
            
            // Todos los periodos duran exactamente weeks * 7 días (menos 1 día)
            fechaFinNivel = new Date(fechaInicioNivel.getTime() + (duracionSemanas * 7 * 24 * 60 * 60 * 1000) - (24 * 60 * 60 * 1000));
            
            if (today.getTime() <= fechaFinNivel.getTime()) {
                break;
            }
            
            if (!hasNextNivel(idNivel)) {
                break;
            }
            
            // El siguiente periodo empieza exactamente 1 día después del fin del actual
            fechaInicioNivel = new Date(fechaFinNivel.getTime() + (24 * 60 * 60 * 1000));
            idNivel = idNivel + 1;
        }
        
        const toISODate = (d) => {
            const yyyy = d.getUTCFullYear();
            const mm = String(d.getUTCMonth() + 1).padStart(2, '0');
            const dd = String(d.getUTCDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        };
        
        return {
            fechaInicioNivel: toISODate(fechaInicioNivel),
            fechaFinNivel: toISODate(fechaFinNivel)
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
            if (idNivelActualDb !== null && idNivelActualDb >= 11) {
                idTipoPeriodo = 1; // SEMESTRAL
            } else {
                idTipoPeriodo = 2; // TRIMESTRAL (default)
            }
        }

        const isTrimestral = idTipoPeriodo === 2;
        const weeksPerPeriod = isTrimestral ? 13 : 26;
        const periodNameSingular = isTrimestral ? "Trimestre" : "Semestre";
        const startLevel = isTrimestral ? 1 : 11;

        let periodNumber = 1;
        let idNivel = startLevel;

        const formatDateDMY = (dateObj) => {
            const dd = String(dateObj.getUTCDate()).padStart(2, '0');
            const mm = String(dateObj.getUTCMonth() + 1).padStart(2, '0');
            const yyyy = dateObj.getUTCFullYear();
            return `${dd}/${mm}/${yyyy}`;
        };

        const today = new Date();
        const todayUTC = new Date(Date.UTC(today.getFullYear(), today.getMonth(), today.getDate()));

        while (true) {
            // El periodo termina weeksPerPeriod * 7 días después de su fecha de inicio (menos 1 día)
            let periodEndDate = new Date(currentDate.getTime() + (weeksPerPeriod * 7 * 24 * 60 * 60 * 1000) - (24 * 60 * 60 * 1000));

            // Si definimos un fin de grupo y el inicio de este periodo ya excede la fecha fin, paramos
            if (groupEndDate && currentDate.getTime() > groupEndDate.getTime()) {
                break;
            }

            // Si el periodo excede el límite académico
            if (isTrimestral && periodNumber > 10) break;
            if (!isTrimestral && periodNumber > 8) break;

            const isCurrent = todayUTC.getTime() >= currentDate.getTime() && todayUTC.getTime() <= periodEndDate.getTime();
            const isPast = todayUTC.getTime() > periodEndDate.getTime();

            const periodItem = document.createElement("div");
            periodItem.style.padding = "10px 12px";
            periodItem.style.borderRadius = "10px";
            periodItem.style.transition = "all 0.2s ease";

            if (isCurrent) {
                // Periodo activo: borde institucional azul marino y fondo sutil
                periodItem.style.border = "1.5px solid rgb(38, 104, 123)";
                periodItem.style.background = "rgba(38, 104, 123, 0.08)";
                periodItem.style.color = "#1e293b";
                periodItem.style.opacity = "1";
            } else if (isPast) {
                // Periodo finalizado: borde discontinuo y ligeramente opaco
                periodItem.style.border = "1px dashed #cbd5e1";
                periodItem.style.background = "rgba(241, 245, 249, 0.4)";
                periodItem.style.color = "#64748b";
                periodItem.style.opacity = "0.65";
            } else {
                // Periodo futuro: borde estándar
                periodItem.style.border = "1px solid #e2e8f0";
                periodItem.style.background = "#ffffff";
                periodItem.style.color = "#334155";
                periodItem.style.opacity = "1";
            }

            let badgeHtml = "";
            if (isCurrent) {
                badgeHtml = '<span style="font-size: 0.65rem; font-weight: 700; color: white; background: rgb(38, 104, 123); padding: 2px 6px; border-radius: 8px; text-transform: uppercase;">Activo</span>';
            } else if (isPast) {
                badgeHtml = '<span style="font-size: 0.65rem; font-weight: 600; color: #475569; background: #e2e8f0; padding: 2px 6px; border-radius: 8px;">Finalizado</span>';
            }

            const titleColor = isCurrent ? "rgb(38, 104, 123)" : (isPast ? "#64748b" : "#1e293b");
            const dateColor = isCurrent ? "#334155" : (isPast ? "#8c9ba5" : "#475569");
            const iconColor = isCurrent ? "rgb(38, 104, 123)" : (isPast ? "#94a3b8" : "rgb(56, 189, 248)");

            periodItem.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <span style="font-weight: 700; font-size: 0.85rem; color: ${titleColor};">
                        ${periodNumber}° ${periodNameSingular} (Nivel ${idNivel})
                    </span>
                    ${badgeHtml}
                </div>
                <div style="font-size: 0.75rem; color: ${dateColor}; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-regular fa-calendar-check" style="color: ${iconColor};"></i>
                    <span>${formatDateDMY(currentDate)}</span>
                    <span style="color: #cbd5e1;">&rarr;</span>
                    <span>${formatDateDMY(periodEndDate)}</span>
                </div>
            `;

            periodsList.appendChild(periodItem);

            // Si el periodo sobrepasa la fechaFin del grupo (o coincide con ella), paramos
            if (groupEndDate && periodEndDate.getTime() >= groupEndDate.getTime()) {
                break;
            }

            // Siguiente periodo empieza exactamente 1 día después de que finaliza el actual
            currentDate = new Date(periodEndDate.getTime() + (24 * 60 * 60 * 1000));
            periodNumber++;
            idNivel++;
        }
    }

    function generateCalendarGridHTML(days) {
        calendarBody.innerHTML = '';
        timeBlocks.forEach((time, index) => {
            const tr = document.createElement("tr");
            
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
                    <i class="fa-solid fa-plus add-btn-icon"></i>
                `;

                slotDiv.addEventListener("click", () => handleCellClick(day, index, slotDiv));

                td.appendChild(slotDiv);
                tr.appendChild(td);
            });

            calendarBody.appendChild(tr);
        });
    }

    function handleCellClick(day, timeIdx, element) {
        if (!activeGroup) return;

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
        const existingClass = groupData[cellKey];

        if (existingClass) {
            // Normalizar a formato agrupado si no lo tiene
            if (!existingClass.clases) {
                existingClass.clases = [{
                    id_horario: existingClass.id,
                    id_materia: existingClass.materiaId,
                    materia_nombre: existingClass.subjectName,
                    id_docente: existingClass.docenteId,
                    docente_nombre: existingClass.teacherName
                }];
            }

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
            const firstClassDocente = existingClass.id_docente || (existingClass.clases[0] ? existingClass.clases[0].id_docente : "");
            if (docenteSelectInstance) docenteSelectInstance.setValue(firstClassDocente);
            formAulaSelect.value = existingClass.aula || "";
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
    }

    btnSaveClass.addEventListener("click", async function() {
        if (!selectedCell || !activeGroup) return;

        const aula = formAulaSelect.value;
        const timeSlot = timeBlocks[selectedCell.timeIdx];
        const [startStr, endStr] = timeSlot.split(" - ");
        const horaInicio = startStr + ":00";
        const horaFin = endStr + ":00";

        let payload = {
            id_grupo: activeGroup.id || activeGroup.id_grupo,
            diaSemana: dayNumbers[selectedCell.day],
            horaInicio: horaInicio,
            horaFin: horaFin,
            aula: aula
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
                        horaFin: horaFin
                    })
                });

                if (valRes.ok) {
                    const valData = await valRes.json();
                    if (valData.success === false) {
                        const matObj = materias.find(m => (m.id_materia || m.id) == matId);
                        const docObj = docentes.find(d => (d.idDocente || d.id_docente || d.id) == parseInt(docenteId));
                        const matName = matObj ? matObj.nombreMateria : "Materia";
                        const docName = docObj ? getTeacherFullName(docObj) : "Docente";

                        const confirmResult = await Swal.fire({
                            icon: 'warning',
                            title: 'Conflicto de Horario',
                            html: `El docente <strong>${docName}</strong> ya tiene clases en este horario en otro grupo para la materia <strong>${matName}</strong>.<br><br>¿Deseas asignarlo de todas formas?`,
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

            const groupData = schedulesData[activeGroup.clave] || {};
            const cellKey = `${selectedCell.day}-${selectedCell.timeIdx}`;
            const existingClass = groupData[cellKey];

            // If it exists in backend, delete all previous classes first
            if (existingClass && existingClass.clases && existingClass.clases.length > 0) {
                for (let clase of existingClass.clases) {
                    const delRes = await fetch(`/horarios/${clase.id_horario}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    if (!delRes.ok) throw new Error("Error al remover horario previo.");
                }
            } else if (existingClass && existingClass.id) {
                // Fallback for single class delete
                const delRes = await fetch(`/horarios/${existingClass.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                if (!delRes.ok) throw new Error("Error al remover horario previo.");
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
                        const listToDelete = existingClass.clases || [{ id_horario: existingClass.id }];
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

                        delete schedulesData[activeGroup.clave][cellKey];
                        
                        selectedCell.element.innerHTML = `
                            <span class="placeholder-dash">—</span>
                            <i class="fa-solid fa-plus add-btn-icon"></i>
                        `;

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
                <i class="fa-solid fa-plus add-btn-icon"></i>
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

    async function renderActiveGroupClasses() {
        // Clear cells first
        const cells = calendarBody.getElementsByClassName("cell-slot");
        Array.from(cells).forEach(cell => {
            cell.innerHTML = `
                <span class="placeholder-dash">—</span>
                <i class="fa-solid fa-plus add-btn-icon"></i>
            `;
            cell.classList.remove("selected");
        });

        if (!activeGroup) return;

        const groupId = activeGroup.id || activeGroup.id_grupo;
        if (!groupId) return;

        try {
            const response = await fetch(`/horarios/grupo/${groupId}?agrupado=true`);
            if (!response.ok) throw new Error("Error al obtener los horarios");
            const data = await response.json();

            // Store in schedulesData in memory
            schedulesData[activeGroup.clave] = {};

            data.forEach(item => {
                const dayName = daysMap[item.diaSemana];
                const timeIdx = getTimeIdx(item.horaInicio);

                if (dayName && timeIdx !== -1) {
                    const cellKey = `${dayName}-${timeIdx}`;
                    
                    schedulesData[activeGroup.clave][cellKey] = {
                        diaSemana: item.diaSemana,
                        horaInicio: item.horaInicio,
                        horaFin: item.horaFin,
                        aula: item.aula || "",
                        clases: item.clases || []
                    };

                    const cellElement = Array.from(cells).find(c => c.dataset.day === dayName && c.dataset.timeIdx == timeIdx);
                    if (cellElement) {
                        renderClassCardInCell(cellElement, schedulesData[activeGroup.clave][cellKey]);
                    }
                }
            });
        } catch (error) {
            console.error("Error al cargar horarios del grupo:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al cargar los horarios desde el servidor.',
                confirmButtonColor: 'rgb(38, 104, 123)'
            });
        }
    }

    function renderClassCardInCell(cellElement, data) {
        const aulaText = data.aula ? `<div style="font-size: 0.7rem; font-weight: 500; margin-top: 4px; color: #475569; display: flex; align-items: center; gap: 4px;"><i class="fa-solid fa-door-open"></i> ${data.aula}</div>` : '';
        let html = '<div class="class-card" draggable="true">';
        data.clases.forEach((clase, idx) => {
            if (idx > 0) {
                html += '<hr style="margin: 4px 0; opacity: 0.15; border-color: rgb(38, 104, 123);">';
            }
            html += `
                <div class="class-subject" style="font-size: 0.85rem; font-weight: 700; line-height: 1.2;">${clase.materia_nombre}</div>
                <div class="class-detail" style="font-size: 0.72rem; color: #475569; line-height: 1.2;"><i class="fa-solid fa-user-tie"></i> ${clase.docente_nombre}</div>
            `;
        });
        html += aulaText;
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
                        horaFin: horaFin
                    })
                });

                if (valRes.ok) {
                    const valData = await valRes.json();
                    if (valData.success === false) {
                        Swal.close();
                        
                        const matObj = materias.find(m => (m.id_materia || m.id) == matId);
                        const docObj = docentes.find(d => (d.idDocente || d.id_docente || d.id) == parseInt(docenteId));
                        const matName = matObj ? matObj.nombreMateria : "Materia";
                        const docName = docObj ? getTeacherFullName(docObj) : "Docente";

                        const confirmResult = await Swal.fire({
                            icon: 'warning',
                            title: 'Conflicto de Horario',
                            html: `El docente <strong>${docName}</strong> ya tiene clases en este horario en otro grupo para la materia <strong>${matName}</strong>.<br><br>¿Deseas asignarlo de todas formas?`,
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
                id_docente: parseInt(docenteId)
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
    }
});
</script>

@endsection
