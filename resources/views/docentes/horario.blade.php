@extends('layouts.app')

@section('content')

<style>
    /* Wrapper styling */
    .horarios-page-wrapper {
        background: #f4f6f9;
        margin: -25px;
        padding: 30px;
        min-height: calc(100vh - 85px);
        color: #1e293b;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }

    .dashboard-header {
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 15px;
        margin-bottom: 25px;
    }

    .dashboard-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: rgb(38, 104, 123);
    }

    .dashboard-subtitle {
        font-size: 0.9rem;
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

    .glass-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        color: #1e293b !important;
        padding: 20px;
        margin-bottom: 20px;
    }

    .teacher-details-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: rgb(38, 104, 123);
        border-bottom: 2px solid rgba(38, 104, 123, 0.15);
        padding-bottom: 8px;
        margin-bottom: 15px;
    }

    .info-label {
        font-size: 0.78rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 2px;
    }

    .info-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
    }

    .status-badge {
        font-size: 0.8rem;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 700;
    }

    /* Hours summary */
    .summary-card {
        background: rgb(244, 246, 249);
        border-radius: 8px;
        padding: 15px;
        border-left: 5px solid rgb(38, 104, 123);
    }

    .summary-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
    }

    .summary-value {
        font-size: 1.6rem;
        font-weight: 800;
        color: rgb(38, 104, 123);
        margin: 5px 0 0 0;
    }

    /* Calendars styles */
    .calendar-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .calendar-table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
        background: #ffffff;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .calendar-table th {
        background: rgb(38, 104, 123) !important;
        color: #ffffff !important;
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        text-align: center;
        padding: 12px 10px;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .calendar-table td {
        border: 1px solid #e2e8f0 !important;
        padding: 8px !important;
        vertical-align: middle;
        text-align: center;
        background: #ffffff !important;
        height: 70px;
    }

    .time-col {
        background: #f8fafc !important;
        color: #475569 !important;
        font-size: 0.78rem;
        font-weight: 700;
        text-align: center;
        width: 110px !important;
        padding: 10px !important;
        border-right: 1px solid #e2e8f0 !important;
        height: auto;
    }

    .receso-row {
        background: #f1f5f9 !important;
        height: 35px !important;
        text-align: center;
        font-weight: 800;
        font-size: 0.78rem;
        color: #64748b;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    /* Class cell style */
    .class-block {
        background: rgba(38, 104, 123, 0.08);
        border: 1px solid rgba(38, 104, 123, 0.25);
        border-left: 4px solid rgb(38, 104, 123);
        border-radius: 6px;
        padding: 6px;
        text-align: left;
        height: auto;
        min-height: 52px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        transition: all 0.2s ease;
        margin-bottom: 4px;
    }

    .class-block:last-child {
        margin-bottom: 0;
    }

    .class-block:hover {
        background: rgba(38, 104, 123, 0.12);
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }

    .class-materia {
        font-weight: 700;
        font-size: 0.78rem;
        color: #0f172a;
        line-height: 1.25;
        margin-bottom: 2px;
        text-transform: uppercase;
    }

    .class-grupo {
        font-size: 0.7rem;
        font-weight: 700;
        color: rgb(38, 104, 123);
        display: inline-block;
        margin-right: 5px;
    }

    .class-aula {
        font-size: 0.7rem;
        font-weight: 600;
        color: #64748b;
    }

    /* Custom autocomplete search style */
    #search-results {
        background: #ffffff;
        border-radius: 8px;
        overflow-y: auto;
    }
    #search-results .list-group-item {
        border: none;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
        transition: background 0.2s ease;
    }
    #search-results .list-group-item:last-child {
        border-bottom: none;
    }
    #search-results .list-group-item:hover {
        background: #f8fafc;
    }

    .btn-action-system {
        background: rgb(38, 104, 123) !important;
        border: none;
        color: white !important;
        font-size: 0.9rem;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .btn-action-system:hover {
        background: rgb(28, 79, 94) !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(38, 104, 123, 0.2);
    }

    .no-schedule-box {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        color: #64748b;
        font-weight: 600;
        font-size: 0.9rem;
    }
</style>

<div class="horarios-page-wrapper">

    <!-- CABECERA DE LA PÁGINA -->
    <div class="dashboard-header d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('docentes') }}" class="btn-regresar-light mb-2">
                <i class="fa-solid fa-arrow-left me-2"></i>
                Listado de Docentes
            </a>
            <h3 class="dashboard-title mb-0">
                <i class="fa-solid fa-calendar-days me-2"></i>
                Horario Docente
            </h3>
            <span class="dashboard-subtitle">Reporte consolidado de carga horaria (BTI y BGNE)</span>
        </div>

        <!-- SELECTOR DE DOCENTE AUTOCOMPLETAR -->
        <div class="position-relative" style="width: 320px;">
            <label class="form-label fw-bold text-dark mb-1">Buscar Docente:</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0" style="border-radius: 8px 0 0 8px; height: 45px;"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" id="docente-search" class="form-control border-start-0 ps-0" placeholder="Escribe el nombre del docente..." autocomplete="off" style="border-radius: 0 8px 8px 0; height: 45px; font-weight: 500;">
            </div>
            <div id="search-results" class="list-group position-absolute w-100 d-none" style="z-index: 1100; max-height: 250px; overflow-y: auto; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-radius: 8px; border: 1px solid #e2e8f0;"></div>
        </div>
    </div>

    <!-- PANTALLA INICIAL (SIN SELECCIÓN) -->
    <div id="pantalla-vacia" class="glass-card text-center py-5">
        <i class="fa-solid fa-chalkboard-user text-muted mb-3" style="font-size: 4rem; opacity: 0.4;"></i>
        <h4 class="fw-bold text-slate-700">Consulta de Horario</h4>
        <p class="text-muted mx-auto" style="max-width: 450px;">
            Selecciona un docente de la lista en la esquina superior derecha para visualizar su carga horaria asignada y descargar su reporte.
        </p>
    </div>

    <!-- PANEL PRINCIPAL DE HORARIO (SE MUESTRA AL SELECCIONAR) -->
    <div id="panel-horario" style="display: none;">
        
        <!-- FICHA DE DETALLES DEL DOCENTE -->
        <div class="glass-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="teacher-details-title">
                        <i class="fa-solid fa-address-card me-2"></i>Datos del Docente
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6 col-lg-3">
                            <div class="info-label">Nombre Completo</div>
                            <div class="info-value" id="docente-nombre">—</div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="info-label">ID Biométrico</div>
                            <div class="info-value" id="docente-biometrico">—</div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="info-label">Correo Electrónico</div>
                            <div class="info-value" id="docente-correo">—</div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="info-label">Nivel de Estudios</div>
                            <div class="info-value" id="docente-estudios">—</div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="info-label">Teléfono</div>
                            <div class="info-value" id="docente-telefono">—</div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="info-label">Estado</div>
                            <div>
                                <span class="badge bg-success status-badge" id="docente-estatus">ACTIVO</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RESUMEN DE CARGA ACADÉMICA -->
                <div class="col-md-4 mt-3 mt-md-0 border-start ps-md-4">
                    <h5 class="teacher-details-title">
                        <i class="fa-solid fa-business-time me-2"></i>Carga Asignada
                    </h5>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="summary-card">
                                <div class="summary-title">Horas BTI</div>
                                <div class="summary-value" id="resumen-bti">0h</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="summary-card">
                                <div class="summary-title">Horas BGNE</div>
                                <div class="summary-value" id="resumen-bgne">0h</div>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="summary-card" style="background: rgb(38, 104, 123); color: white; border-left: none;">
                                <div class="summary-title text-white opacity-75">Carga Horaria Semanal</div>
                                <div class="summary-value text-white" id="resumen-total">0h</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-end mt-4 pt-3 border-top">
                <button class="btn btn-action-system" onclick="imprimirReporteHorario()">
                    <i class="fa-solid fa-print me-2"></i>Imprimir Reporte Oficial
                </button>
            </div>
        </div>

        <!-- SECCIÓN DE CALENDARIOS -->
        <div class="row g-4">

            <!-- CALENDARIO BTI -->
            <div class="col-12">
                <div class="glass-card">
                    <h5 class="calendar-card-title">
                        <i class="fa-solid fa-school text-primary" style="color: rgb(38, 104, 123) !important;"></i>
                        Horario BTI (CCT: 21PCT0073R - Escolarizado)
                    </h5>
                    
                    <div class="table-responsive">
                        <table class="calendar-table">
                            <thead>
                                <tr>
                                    <th class="time-col">Hora</th>
                                    <th>Lunes</th>
                                    <th>Martes</th>
                                    <th>Miércoles</th>
                                    <th>Jueves</th>
                                    <th>Viernes</th>
                                </tr>
                            </thead>
                            <tbody id="bti-tbody">
                                <!-- Filas de horarios BTI cargadas dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                    <div id="bti-no-schedule" class="no-schedule-box mt-3" style="display: none;">
                        El docente no tiene materias asignadas en el CCT de BTI.
                    </div>
                </div>
            </div>

            <!-- CALENDARIO BGNE -->
            <div class="col-12">
                <div class="glass-card">
                    <h5 class="calendar-card-title">
                        <i class="fa-solid fa-building-columns text-primary" style="color: rgb(38, 104, 123) !important;"></i>
                        Horario BGNE (CCT: 21PBH0353G - Fin de Semana)
                    </h5>
                    
                    <div class="table-responsive">
                        <table class="calendar-table" id="bgne-table">
                            <thead>
                                <tr>
                                    <th class="time-col">Hora</th>
                                    <th>Sábado</th>
                                    <th>Domingo</th>
                                </tr>
                            </thead>
                            <tbody id="bgne-tbody">
                                <!-- Filas de horarios BGNE cargadas dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                    <div id="bgne-no-schedule" class="no-schedule-box mt-3" style="display: none;">
                        El docente no tiene materias asignadas en el CCT de BGNE.
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<script>
const loggedInDocenteId = @json(session('id_docente'));
const isDocenteRole = @json(session('rol') === 'DOCENTE');
let selectDocente = null;
let docentesMap = {};
let horarioActual = [];
let docenteActual = null;

// Estructura fija de horas BTI
const BTI_HOURS = [
    { start: "07:30", end: "08:20", type: "class" },
    { start: "08:20", end: "09:10", type: "class" },
    { start: "09:10", end: "10:00", type: "class" },
    { start: "10:00", end: "10:30", type: "receso" },
    { start: "10:30", end: "11:20", type: "class" },
    { start: "11:20", end: "12:10", type: "class" },
    { start: "12:10", end: "13:00", type: "class" }
];

document.addEventListener("DOMContentLoaded", function() {
    if (isDocenteRole && loggedInDocenteId) {
        // Ocultar buscador e inputs innecesarios para el rol docente
        const searchContainer = document.getElementById("docente-search")?.closest(".position-relative");
        if (searchContainer) searchContainer.style.display = "none";
        
        const returnBtn = document.querySelector(".btn-regresar-light");
        if (returnBtn) returnBtn.style.display = "none";

        // Cargar los datos del docente directamente
        fetch(`/docentes/${loggedInDocenteId}`)
            .then(res => res.json())
            .then(resp => {
                if (resp.success && resp.data) {
                    const d = resp.data;
                    docentesMap[d.idDocente] = {
                        ...d,
                        nombreCompleto: `${d.nombreDocente} ${d.apPaternoDocente ?? ''} ${d.apMaternoDocente ?? ''}`.trim()
                    };
                    seleccionarDocente(d.idDocente);
                }
            })
            .catch(err => {
                console.error("Error al cargar datos del docente:", err);
            });
    } else {
        cargarListaDocentes();
    }
});

function cargarListaDocentes() {
    fetch('/docentes/lista')
        .then(res => res.json())
        .then(data => {
            const list = Array.isArray(data.data) ? data.data : [];
            
            list.forEach(d => {
                const nombreCompleto = `${d.nombreDocente} ${d.apPaternoDocente ?? ''} ${d.apMaternoDocente ?? ''}`.trim();
                docentesMap[d.idDocente] = {
                    ...d,
                    nombreCompleto: nombreCompleto
                };
            });

            // Configurar buscador Autocomplete
            const searchInput = document.getElementById("docente-search");
            const searchResults = document.getElementById("search-results");

            searchInput.addEventListener("input", function() {
                const normalizeStr = str => (str || '').normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
                const query = normalizeStr(this.value).trim();
                if (query.length === 0) {
                    resetPantalla();
                    searchResults.classList.add("d-none");
                    return;
                }
                if (query.length < 2) {
                    searchResults.classList.add("d-none");
                    return;
                }
                
                // Filtrar docentes
                const matches = Object.values(docentesMap).filter(d => 
                    normalizeStr(d.nombreCompleto).includes(query) ||
                    normalizeStr(d.correoDocente).includes(query) ||
                    d.idDocente.toString().includes(query)
                );
                
                if (matches.length === 0) {
                    searchResults.innerHTML = '<div class="list-group-item text-muted text-center py-3">No se encontraron docentes</div>';
                } else {
                    searchResults.innerHTML = matches.map(d => `
                        <a href="javascript:void(0)" class="list-group-item list-group-item-action py-2 text-start" onclick="seleccionarDocenteAutocompletar(${d.idDocente})">
                            <strong style="color: #0f172a;">${d.nombreCompleto}</strong> <br>
                            <small class="text-muted" style="font-size: 0.72rem;">ID: ${d.idDocente} | ${d.correoDocente || 'Sin correo'}</small>
                        </a>
                    `).join("");
                }
                searchResults.classList.remove("d-none");
            });

            // Cerrar resultados al hacer clic fuera
            document.addEventListener("click", function(e) {
                if (e.target !== searchInput && e.target !== searchResults) {
                    searchResults.classList.add("d-none");
                }
            });

            window.seleccionarDocenteAutocompletar = function(id) {
                const d = docentesMap[id];
                if (d) {
                    searchInput.value = d.nombreCompleto;
                    searchResults.classList.add("d-none");
                    seleccionarDocente(id);
                }
            };
        })
        .catch(err => {
            console.error("Error al cargar listado de docentes:", err);
        });
}

function seleccionarDocente(id) {
    docenteActual = docentesMap[id];
    
    // Rellenar ficha del docente
    document.getElementById("docente-nombre").textContent = docenteActual.nombreCompleto;
    document.getElementById("docente-biometrico").textContent = docenteActual.idBiometrico || "Sin ID";
    document.getElementById("docente-correo").textContent = docenteActual.correoDocente || "N/A";
    document.getElementById("docente-estudios").textContent = docenteActual.nivelEstudios || "N/A";
    document.getElementById("docente-telefono").textContent = docenteActual.telefonoDocente || "N/A";
    
    const est = document.getElementById("docente-estatus");
    est.textContent = docenteActual.statusDocente;
    if (docenteActual.statusDocente === "ACTIVO") {
        est.className = "badge bg-success status-badge";
    } else {
        est.className = "badge bg-danger status-badge";
    }

    // Cargar Horario
    cargarHorariosServicio(id);
}

function resetPantalla() {
    docenteActual = null;
    horarioActual = [];
    document.getElementById("panel-horario").style.display = "none";
    document.getElementById("pantalla-vacia").style.display = "block";
}

function formatTimeHM(timeStr) {
    if (!timeStr) return "";
    const parts = timeStr.split(":");
    return `${parts[0].padStart(2, "0")}:${parts[1].padStart(2, "0")}`;
}

function cargarHorariosServicio(idDocente) {
    fetch(`/docentes/${idDocente}/horario`)
        .then(res => res.json())
        .then(horarios => {
            horarioActual = Array.isArray(horarios) ? horarios : [];
            renderCalendars();
            
            document.getElementById("pantalla-vacia").style.display = "none";
            document.getElementById("panel-horario").style.display = "block";
        })
        .catch(err => {
            console.error("Error al cargar el horario del docente:", err);
        });
}

function renderCalendars() {
    // Dividir las clases por CCT (BTI y BGNE)
    const btiClasses = [];
    const bgneClasses = [];
    
    let hoursBtiCount = 0;
    let hoursBgneCount = 0;

    horarioActual.forEach(c => {
        // Normalizar tiempos
        c.horaInicioFormatted = formatTimeHM(c.horaInicio);
        c.horaFinFormatted = formatTimeHM(c.horaFin);
        
        if (c.id_centroTrabajo === 2 || c.diaSemana <= 5) {
            btiClasses.push(c);
            hoursBtiCount++;
        } else if (c.id_centroTrabajo === 3 || c.diaSemana >= 6) {
            bgneClasses.push(c);
            hoursBgneCount++; // En BGNE las sesiones suelen ser de 1 hora
        }
    });

    // Actualizar resúmenes
    document.getElementById("resumen-bti").textContent = `${hoursBtiCount}h`;
    document.getElementById("resumen-bgne").textContent = `${hoursBgneCount}h`;
    document.getElementById("resumen-total").textContent = `${hoursBtiCount + hoursBgneCount}h`;

    // 1. RENDER BTI
    if (btiClasses.length > 0) {
        document.getElementById("bti-tbody").innerHTML = "";
        document.getElementById("bti-no-schedule").style.display = "none";
        document.querySelector("#bti-tbody").closest("table").style.display = "table";
        
        let htmlBti = "";
        BTI_HOURS.forEach(block => {
            if (block.type === "receso") {
                htmlBti += `
                    <tr class="receso-row">
                        <td class="time-col">${block.start} - ${block.end}</td>
                        <td colspan="5">RECESO</td>
                    </tr>
                `;
            } else {
                htmlBti += `<tr>
                    <td class="time-col">${block.start} - ${block.end}</td>`;
                
                // Columnas de días: Lunes(1) a Viernes(5)
                for (let day = 1; day <= 5; day++) {
                    // Buscar clase
                    const matchingClasses = btiClasses.filter(c => 
                        parseInt(c.diaSemana) === day && 
                        c.horaInicioFormatted === block.start
                    );
                    
                    if (matchingClasses.length > 0) {
                        let cellHtml = "";
                        matchingClasses.forEach(mc => {
                            cellHtml += `
                                <div class="class-block">
                                    <div class="class-materia" title="${mc.materia_nombre}">${mc.materia_nombre}</div>
                                    <div>
                                        <span class="class-grupo">${mc.grupo_clave}</span>
                                        <span class="class-aula"><i class="fa-solid fa-location-dot me-1"></i>${mc.aula}</span>
                                    </div>
                                </div>
                            `;
                        });
                        htmlBti += `<td>${cellHtml}</td>`;
                    } else {
                        htmlBti += `<td></td>`;
                    }
                }
                htmlBti += `</tr>`;
            }
        });
        document.getElementById("bti-tbody").innerHTML = htmlBti;
    } else {
        document.getElementById("bti-tbody").innerHTML = "";
        document.querySelector("#bti-tbody").closest("table").style.display = "none";
        document.getElementById("bti-no-schedule").style.display = "block";
    }

    // 2. RENDER BGNE (Sábado(6) y Domingo(7))
    if (bgneClasses.length > 0) {
        document.getElementById("bgne-tbody").innerHTML = "";
        document.getElementById("bgne-no-schedule").style.display = "none";
        document.querySelector("#bgne-tbody").closest("table").style.display = "table";
        
        // Obtener rangos de horas de BGNE de forma dinámica
        const bgneIntervals = [];
        bgneClasses.forEach(c => {
            const intv = `${c.horaInicioFormatted} - ${c.horaFinFormatted}`;
            if (!bgneIntervals.includes(intv)) {
                bgneIntervals.push(intv);
            }
        });
        
        // Ordenar rangos de horas
        bgneIntervals.sort();
        
        let htmlBgne = "";
        bgneIntervals.forEach(interval => {
            const [start, end] = interval.split(" - ");
            htmlBgne += `<tr>
                <td class="time-col">${interval}</td>`;
            
            // Sábado(6) y Domingo(7)
            for (let day = 6; day <= 7; day++) {
                const matchingClasses = bgneClasses.filter(c => 
                    parseInt(c.diaSemana) === day && 
                    c.horaInicioFormatted === start
                );
                
                if (matchingClasses.length > 0) {
                    let cellHtml = "";
                    matchingClasses.forEach(mc => {
                        cellHtml += `
                            <div class="class-block" style="background: rgba(13, 148, 136, 0.08); border-left-color: rgb(13, 148, 136); border-color: rgba(13, 148, 136, 0.25);">
                                <div class="class-materia" title="${mc.materia_nombre}">${mc.materia_nombre}</div>
                                <div>
                                    <span class="class-grupo" style="color: rgb(13, 148, 136);">${mc.grupo_clave}</span>
                                    <span class="class-aula"><i class="fa-solid fa-location-dot me-1"></i>${mc.aula}</span>
                                </div>
                            </div>
                        `;
                    });
                    htmlBgne += `<td>${cellHtml}</td>`;
                } else {
                    htmlBgne += `<td></td>`;
                }
            }
            htmlBgne += `</tr>`;
        });
        document.getElementById("bgne-tbody").innerHTML = htmlBgne;
    } else {
        document.getElementById("bgne-tbody").innerHTML = "";
        document.querySelector("#bgne-tbody").closest("table").style.display = "none";
        document.getElementById("bgne-no-schedule").style.display = "block";
    }
}

// FUNCIÓN DE IMPRESIÓN OFICIAL DEL HORARIO
function imprimirReporteHorario() {
    if (!docenteActual) return;
    
    // Obtener los HTMLs de las tablas actualmente renderizadas
    const btiClasses = horarioActual.filter(c => c.id_centroTrabajo === 2 || c.diaSemana <= 5);
    const bgneClasses = horarioActual.filter(c => c.id_centroTrabajo === 3 || c.diaSemana >= 6);

    let btiTableHtml = "";
    if (btiClasses.length > 0) {
        btiTableHtml = `
            <h3 class="seccion-titulo">I. Horario de Bachillerato Tecnológico Industrial (BTI) - Escolarizado</h3>
            <table class="report-table">
                <thead>
                    <tr>
                        <th class="time-header">Hora</th>
                        <th>Lunes</th>
                        <th>Martes</th>
                        <th>Miércoles</th>
                        <th>Jueves</th>
                        <th>Viernes</th>
                    </tr>
                </thead>
                <tbody>
                    ${document.getElementById("bti-tbody").innerHTML}
                </tbody>
            </table>
        `;
    } else {
        btiTableHtml = `
            <h3 class="seccion-titulo">I. Horario de Bachillerato Tecnológico Industrial (BTI) - Escolarizado</h3>
            <p class="no-classes">El docente no cuenta con carga horaria en el plantel BTI.</p>
        `;
    }

    let bgneTableHtml = "";
    if (bgneClasses.length > 0) {
        bgneTableHtml = `
            <h3 class="seccion-titulo">II. Horario de Bachillerato General No Escolarizado (BGNE) - Fin de Semana</h3>
            <table class="report-table">
                <thead>
                    <tr>
                        <th class="time-header">Hora</th>
                        <th>Sábado</th>
                        <th>Domingo</th>
                    </tr>
                </thead>
                <tbody>
                    ${document.getElementById("bgne-tbody").innerHTML}
                </tbody>
            </table>
        `;
    } else {
        bgneTableHtml = `
            <h3 class="seccion-titulo">II. Horario de Bachillerato General No Escolarizado (BGNE) - Fin de Semana</h3>
            <p class="no-classes">El docente no cuenta con carga horaria en el plantel BGNE.</p>
        `;
    }

    const totalHours = btiClasses.length + bgneClasses.length;
    
    // Crear la ventana de impresión
    const win = window.open("", "_blank");
    win.document.write(`
        <html>
            <head>
                <title>Reporte de Carga Horaria - ${docenteActual.nombreCompleto}</title>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
                <style>
                    body {
                        font-family: 'Segoe UI', Arial, sans-serif;
                        color: #1e293b;
                        padding: 10px 20px;
                        margin: 0;
                        background: #ffffff;
                    }
                    .report-header {
                        display: flex;
                        align-items: center;
                        border-bottom: 2px double rgb(38, 104, 123);
                        padding-bottom: 8px;
                        margin-bottom: 12px;
                    }
                    .logo-placeholder {
                        width: 50px;
                        height: 50px;
                        margin-right: 15px;
                        border-radius: 50%;
                        background: #f1f5f9;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: rgb(38, 104, 123);
                        font-size: 1.5rem;
                        border: 2px solid rgb(38, 104, 123);
                    }
                    .header-info h1 {
                        font-size: 1.15rem;
                        font-weight: 800;
                        margin: 0 0 3px 0;
                        color: rgb(38, 104, 123);
                        text-transform: uppercase;
                    }
                    .header-info h2 {
                        font-size: 0.8rem;
                        font-weight: 700;
                        margin: 0;
                        color: #475569;
                    }
                    .info-card {
                        background: #f8fafc;
                        border: 1px solid #e2e8f0;
                        border-radius: 8px;
                        padding: 8px 12px;
                        margin-bottom: 12px;
                        display: grid;
                        grid-template-columns: 2fr 1fr 1fr 1fr;
                        gap: 8px 12px;
                    }
                    .info-group {
                        display: flex;
                        flex-direction: column;
                    }
                    .info-lbl {
                        font-size: 0.65rem;
                        font-weight: 700;
                        color: #64748b;
                        text-transform: uppercase;
                    }
                    .info-val {
                        font-size: 0.78rem;
                        font-weight: 600;
                        color: #0f172a;
                    }
                    .seccion-titulo {
                        font-size: 0.85rem;
                        font-weight: 700;
                        color: rgb(38, 104, 123);
                        margin: 10px 0 6px 0;
                        padding-bottom: 3px;
                        border-bottom: 1px solid #cbd5e1;
                    }
                    .report-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 12px;
                    }
                    .report-table th {
                        background: rgb(38, 104, 123) !important;
                        color: white !important;
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                        font-size: 0.68rem;
                        font-weight: 700;
                        text-transform: uppercase;
                        padding: 4px 3px;
                        border: 1px solid #cbd5e1;
                        text-align: center;
                    }
                    .report-table td {
                        border: 1px solid #cbd5e1;
                        padding: 3px 4px;
                        font-size: 0.65rem;
                        text-align: center;
                        vertical-align: middle;
                        height: auto;
                        background: #ffffff;
                    }
                    .time-header {
                        width: 100px;
                    }
                    .report-table td.time-col {
                        background: #f8fafc !important;
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                        font-weight: 700;
                        color: #475569;
                        height: auto;
                    }
                    .receso-row {
                        background: #f1f5f9 !important;
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                        height: 18px !important;
                        font-weight: 700;
                        color: #64748b;
                        letter-spacing: 2px;
                    }
                    .class-block {
                        border-left: 2px solid rgb(38, 104, 123);
                        padding-left: 4px;
                        text-align: left;
                        margin-bottom: 4px;
                    }
                    .class-block:last-child {
                        margin-bottom: 0;
                    }
                    .class-materia {
                        font-weight: 700;
                        font-size: 0.65rem;
                        color: #0f172a;
                        line-height: 1.1;
                    }
                    .class-grupo {
                        font-size: 0.6rem;
                        font-weight: 700;
                        color: rgb(38, 104, 123);
                    }
                    .class-aula {
                        font-size: 0.6rem;
                        color: #64748b;
                    }
                    .no-classes {
                        font-size: 0.72rem;
                        color: #64748b;
                        font-style: italic;
                        padding: 8px;
                        background: #f8fafc;
                        border: 1px dashed #cbd5e1;
                        border-radius: 6px;
                        text-align: center;
                    }
                    /* Signatures Section */
                    .signatures-container {
                        margin-top: 20px;
                        display: flex;
                        justify-content: space-between;
                        padding: 0 20px;
                    }
                    .sig-box {
                        width: 200px;
                        text-align: center;
                    }
                    .sig-line {
                        border-top: 1px solid #1e293b;
                        margin-top: 25px;
                        padding-top: 3px;
                        font-size: 0.72rem;
                        font-weight: 700;
                    }
                    .sig-title {
                        font-size: 0.65rem;
                        color: #64748b;
                        text-transform: uppercase;
                    }
                    @media print {
                        body {
                            padding: 0;
                            margin: 1cm;
                        }
                        .no-print {
                            display: none;
                        }
                    }
                </style>
            </head>
            <body>
                <!-- ENCABEZADO -->
                <div class="report-header" style="justify-content: center; text-align: center;">
                    <div class="header-info">
                        <h1>Bachillerato Tecnológico Interamericano</h1>
                        <h2>Carga Horaria y Distribución del Personal Docente</h2>
                    </div>
                </div>

                <!-- DATOS DEL DOCENTE -->
                <div class="info-card">
                    <div class="info-group">
                        <span class="info-lbl">Nombre del Docente</span>
                        <span class="info-val">${docenteActual.nombreCompleto}</span>
                    </div>
                    <div class="info-group">
                        <span class="info-lbl">Horas BTI (CCT: 21PCT0073R)</span>
                        <span class="info-val">${btiClasses.length} horas</span>
                    </div>
                    <div class="info-group">
                        <span class="info-lbl">Horas BGNE (CCT: 21PBH0353G)</span>
                        <span class="info-val">${bgneClasses.length} horas</span>
                    </div>
                    <div class="info-group">
                        <span class="info-lbl">Carga Horaria Semanal</span>
                        <span class="info-val" style="color: rgb(38, 104, 123); font-weight: 800;">${totalHours} HORAS</span>
                    </div>
                </div>

                <!-- TABLAS DE HORARIOS -->
                ${btiTableHtml}
                ${bgneTableHtml}

                <!-- FIRMAS -->
                <div class="signatures-container">
                    <div class="sig-box">
                        <div class="sig-line">Firma del Docente</div>
                        <div class="sig-title">${docenteActual.nombreCompleto}</div>
                    </div>
                    <div class="sig-box">
                        <div class="sig-line">Firma de Dirección</div>
                        <div class="sig-title">Director del Plantel</div>
                    </div>
                </div>
            </body>
        </html>
    `);
    win.document.close();
    win.focus();
    setTimeout(() => {
        win.print();
        win.close();
    }, 450);
}
</script>
@endsection
