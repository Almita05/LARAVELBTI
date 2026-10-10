@extends('layouts.app')

@section('content')

<!-- SweetAlert2 para diálogos estéticos -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="page-container py-4">
    <!-- Encabezado de Página -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-regresar">
            <i class="fa-solid fa-arrow-left me-2"></i>
            Regresar
        </a>

        <h3 class="page-title mb-0">
            <i class="fa-solid fa-print me-2 text-info"></i>
            Módulo "Imprimir"
        </h3>

        <div style="width: 100px;"></div> {{-- Spacer --}}
    </div>

    <!-- SECCIÓN 1: SELECCIONAR CCT -->
    <div class="glass-card p-4 mb-4">
        <div class="glass-header text-center mb-4">
            <h4 class="fw-bold text-slate-800 mb-2">
                <i class="fa-solid fa-school text-info me-2"></i>
                Selecciona el CCT para formatos
            </h4>
            <p class="text-muted mb-0">Elige la modalidad / Bachillerato para cargar los formatos de impresión autorizados.</p>
        </div>

        <div class="row g-3 justify-content-center">
            <!-- Card BTI -->
            <div class="col-md-4">
                <div class="cct-card text-center p-4" id="cct-bti" onclick="selectCCT('BTI')">
                    <div class="cct-icon-wrapper bg-primary-subtle text-primary mb-3">
                        <i class="fa-solid fa-laptop-code fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-1 text-slate-800">BTI</h5>
                    <span class="text-muted fs-8">Bachillerato Tecnológico Interamericano</span>
                </div>
            </div>

            <!-- Card BGNE -->
            <div class="col-md-4">
                <div class="cct-card text-center p-4" id="cct-bgne" onclick="selectCCT('BGNE')">
                    <div class="cct-icon-wrapper bg-success-subtle text-success mb-3">
                        <i class="fa-solid fa-book-reader fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-1 text-slate-800">BGNE</h5>
                    <span class="text-muted fs-8">Bachillerato General No Escolarizado</span>
                </div>
            </div>

            <!-- Card INF. Y COMP. -->
            <div class="col-md-4">
                <div class="cct-card text-center p-4" id="cct-inf" onclick="selectCCT('INF')">
                    <div class="cct-icon-wrapper bg-warning-subtle text-warning mb-3">
                        <i class="fa-solid fa-microchip fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-1 text-slate-800">Informática y Computación</h5>
                    <span class="text-muted fs-8">Especialidad Técnica</span>
                </div>
            </div>
        </div>
    </div>

    <!-- PLACEHOLDER INICIAL (OCULTO CUANDO SE SELECCIONA UN CCT) -->
    <div class="glass-card p-5 text-center text-muted" id="placeholder-select-cct">
        <i class="fa-solid fa-circle-info fa-3x mb-3 text-info"></i>
        <h5 class="fw-bold text-slate-700">Esperando Selección de CCT</h5>
        <p class="mb-0">Por favor, haz clic en una de las modalidades superiores para visualizar sus formatos disponibles.</p>
    </div>

    <!-- SECCIÓN 2: SUBMÓDULOS DE IMPRESIÓN (DINÁMICOS) -->
    <div class="row g-4" id="formatos-container" style="display: none;">
        <!-- Menú Lateral de Submódulos -->
        <div class="col-md-3">
            <div class="glass-card p-3 h-100">
                <h6 class="fw-bold text-secondary border-bottom pb-2 mb-3">
                    <i class="fa-solid fa-folder-open me-2 text-info"></i>Formatos <span id="lbl-cct-activo" class="badge bg-info text-white"></span>
                </h6>
                <div class="nav flex-column nav-pills" id="submodulos-nav" role="tablist" aria-orientation="vertical">
                    <!-- Los enlaces de pestañas se generarán dinámicamente vía JS -->
                </div>
            </div>
        </div>

        <!-- Contenido de los Submódulos -->
        <div class="col-md-9">
            <div class="glass-card p-4 h-100">
                <div class="tab-content" id="submodulos-tab-content">
                    
                    <!-- PANE: CONSTANCIA DE ESTUDIOS -->
                    <div class="tab-pane fade" id="pane-constancia" role="tabpanel">
                        <div class="glass-header mb-4">
                            <h5 class="fw-bold mb-1 text-slate-800">
                                <i class="fa-solid fa-file-invoice text-info me-2"></i>Constancia de Estudios
                            </h5>
                            <p class="text-muted mb-0">Emisión de constancia escolar con o sin historial de calificaciones.</p>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Buscar Alumno</label>
                                <input type="text" id="constanciaAlumnoSearch" class="form-control" placeholder="Escriba matrícula o nombre del alumno..." onkeyup="simularBusquedaAlumnoConstancia(this.value)">
                                <div id="constancia-alumno-sugerencia" class="list-group mt-2 shadow-sm" style="display: none;">
                                    <!-- Cargado por JS -->
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Tipo de Constancia</label>
                                <select class="form-select">
                                    <option value="simple">Constancia Simple</option>
                                    <option value="calif">Constancia con Calificaciones</option>
                                    <option value="conducta">Constancia de Buena Conducta</option>
                                </select>
                            </div>
                            <div class="col-12 mt-4" id="constancia-info-alumno" style="display: none;">
                                <div class="alert alert-secondary border-0 p-3" style="border-radius: 12px; background: rgba(0,0,0,0.02);">
                                    <h6 class="fw-bold mb-1 text-slate-800" id="lbl-constancia-nombre"></h6>
                                    <span class="text-muted d-block fs-8" id="lbl-constancia-carrera"></span>
                                    <span class="badge bg-success-subtle text-success fs-9 mt-2">Estatus: Alumno Regular</span>
                                </div>
                                <button type="button" class="btn btn-primary fw-bold" onclick="printDoc('Constancia de Estudios', document.getElementById('lbl-constancia-nombre').innerText)">
                                    <i class="fa-solid fa-file-pdf me-2"></i> Generar Constancia (PDF)
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- PANE: KARDEX -->
                    <div class="tab-pane fade" id="pane-kardex" role="tabpanel">
                        <div class="glass-header mb-4">
                            <h5 class="fw-bold mb-1 text-slate-800">
                                <i class="fa-solid fa-graduation-cap text-info me-2"></i>Kárdex Académico
                            </h5>
                            <p class="text-muted mb-0">Historial completo de asignaturas cursadas, calificaciones oficiales y promedios por periodo.</p>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12 position-relative">
                                <label class="form-label fw-semibold">Buscar Alumno para Kárdex</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                    <input type="text" id="kardexAlumnoSearch" class="form-control border-start-0" placeholder="Escriba matrícula o nombre del alumno..." onkeyup="simularBusquedaAlumnoKardex(this.value)">
                                    <button class="btn btn-outline-secondary" type="button" onclick="limpiarBusquedaKardex()"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                <div id="kardex-alumno-sugerencia" class="list-group mt-2 shadow-sm position-absolute w-100" style="display: none; z-index: 1050; max-height: 250px; overflow-y: auto;">
                                    <!-- Cargado por JS -->
                                </div>
                            </div>

                            <div class="col-12 mt-3" id="kardex-info-alumno" style="display: none;">
                                <!-- Tarjeta de Resumen y Acciones -->
                                <div class="card p-3 border-0 shadow-sm mb-3" style="border-radius: 14px; background: linear-gradient(135deg, #f8fafc 0%, #eef2f6 100%); border-left: 5px solid #1e6fa8 !important;">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                        <div>
                                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                                <h5 class="fw-bold mb-0 text-slate-800" id="lbl-kardex-nombre"></h5>
                                                <span class="badge bg-primary-subtle text-primary px-2 py-1 fs-9" id="lbl-kardex-badge-cct">BTI</span>
                                                <span class="badge bg-success-subtle text-success px-2 py-1 fs-9" id="lbl-kardex-status">ACTIVO</span>
                                            </div>
                                            <div class="text-muted fs-8">
                                                <span id="lbl-kardex-matricula"></span>
                                                <span class="mx-2">•</span>
                                                <span id="lbl-kardex-generacion-grupo"></span>
                                            </div>
                                        </div>
                                        <div class="text-end d-flex align-items-center gap-3">
                                            <div class="bg-white px-3 py-2 rounded-3 border shadow-xs text-center">
                                                <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Promedio General</small>
                                                <span class="fs-5 fw-bold text-primary" id="lbl-kardex-promedio">—</span>
                                            </div>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-outline-primary fw-bold shadow-xs px-3" onclick="mostrarModalKardexManual()">
                                                    <i class="fa-solid fa-pen-to-square me-1"></i> Ver / Ajustar en Modal
                                                </button>
                                                <button type="button" class="btn btn-primary fw-bold shadow-sm px-3" onclick="imprimirKardex()">
                                                    <i class="fa-solid fa-print me-1"></i> Imprimir Kárdex
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Vista previa oficial en pantalla del Kárdex (para poder verse antes de imprimir) -->
                                <div class="card border shadow-sm p-4 mb-4 bg-white" style="border-radius: 12px;" id="kardex-preview-wrapper">
                                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom flex-wrap gap-2">
                                        <h6 class="fw-bold text-dark mb-0">
                                            <i class="fa-solid fa-file-invoice text-info me-2"></i>Vista Previa del Formato Oficial de Kárdex
                                        </h6>
                                        <div class="d-flex gap-2 align-items-center">
                                            <span class="text-muted fs-8"><i class="fa-solid fa-circle-check text-success me-1"></i>Documento completo listo para imprimir</span>
                                            <button type="button" class="btn btn-sm btn-primary fw-bold" onclick="imprimirKardex()">
                                                <i class="fa-solid fa-print me-1"></i> Imprimir
                                            </button>
                                        </div>
                                    </div>

                                    <div id="kardex-live-preview">
                                        <!-- Se renderiza dinámicamente con la estructura oficial del Kárdex -->
                                    </div>

                                    <div class="text-end mt-3 pt-3 border-top">
                                        <button type="button" class="btn btn-primary fw-bold shadow-sm px-4" onclick="imprimirKardex()">
                                            <i class="fa-solid fa-print me-2"></i> Imprimir Kárdex Oficial
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PANE: BOLETA (BTI) -->
                    <div class="tab-pane fade" id="pane-boleta" role="tabpanel">
                        <div class="glass-header mb-4">
                            <h5 class="fw-bold mb-1 text-slate-800">
                                <i class="fa-solid fa-file-signature text-info me-2"></i>Boletas de Calificaciones (BTI)
                            </h5>
                            <p class="text-muted mb-0">Impresión de boleta de calificaciones por alumno y semestre seleccionado.</p>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-7">
                                <label class="form-label fw-semibold">Nombre del Alumno</label>
                                <input type="text" id="boletaAlumnoSearch" class="form-control" placeholder="Escriba matrícula o nombre del alumno..." onkeyup="simularBusquedaAlumnoBoleta(this.value)">
                                <div id="boleta-alumno-sugerencia" class="list-group mt-2 shadow-sm" style="display: none;">
                                    <!-- Cargado por JS -->
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Semestre</label>
                                <select id="boletaSemestreSelect" class="form-select">
                                    <option value="1er Semestre">1er Semestre</option>
                                    <option value="2° Semestre">2° Semestre</option>
                                    <option value="3er Semestre" selected>3er Semestre</option>
                                    <option value="4° Semestre">4° Semestre</option>
                                    <option value="5° Semestre">5° Semestre</option>
                                    <option value="6° Semestre">6° Semestre</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button class="btn btn-info w-100 text-white fw-bold py-2 shadow-sm" onclick="simularFiltrarBoletas()"><i class="fa-solid fa-magnifying-glass me-1"></i> Buscar</button>
                            </div>
                        </div>
                        <div id="boleta-tabla-resultados" style="display: none;">
                            <div class="card p-3 border-0 bg-light shadow-sm" style="border-radius: 12px;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold mb-1 text-slate-800" id="lbl-boleta-alumno-nombre">Pérez López Juan</h6>
                                        <small class="text-muted">Matrícula: <strong id="lbl-boleta-alumno-matricula">20260001</strong> | Semestre Seleccionado: <strong id="lbl-boleta-semestre-val">3er Semestre</strong></small>
                                    </div>
                                    <div class="text-end d-flex gap-2 flex-wrap">
                                        <button class="btn btn-primary btn-sm fw-bold" onclick="generarBoletaBTIDesdeSelect()"><i class="fa-solid fa-print me-1"></i> Imprimir Boleta</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PANE: REPORTE INDISCIPLINA -->
                    <div class="tab-pane fade" id="pane-reporte_indisciplina" role="tabpanel">
                        <div class="glass-header mb-4">
                            <h5 class="fw-bold mb-1 text-slate-800">
                                <i class="fa-solid fa-triangle-exclamation text-warning me-2"></i>Reportes de Indisciplina (BTI)
                            </h5>
                            <p class="text-muted fs-7 mb-0">Registre indisciplinas y genere el formato oficial a doble talón.</p>
                        </div>

                        <div class="row g-4">
                            <!-- FORMULARIO DE CREACIÓN -->
                            <div class="col-12 col-xl-5">
                                <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; background: #ffffff;">
                                    <h6 class="fw-bold text-slate-800 mb-3 border-bottom pb-2">
                                        <i class="fa-solid fa-circle-plus text-primary me-2"></i>Registrar Nuevo Reporte
                                    </h6>
                                    <form id="formCrearReporte" onsubmit="registrarReporteIndisciplina(event)">
                                        <!-- Alumno Search -->
                                        <div class="mb-3 position-relative">
                                            <label class="form-label fw-bold text-slate-700 fs-7">Buscar Alumno</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-search text-muted"></i></span>
                                                <input type="text" id="reporteAlumnoSearch" class="form-control border-start-0" placeholder="Escriba el nombre del alumno..." onkeyup="buscarAlumnoReporte(this.value)">
                                            </div>
                                            <div id="reporte-alumno-sugerencia" class="list-group mt-2 shadow-sm position-absolute w-100" style="display: none; z-index: 1050; max-height: 200px; overflow-y: auto;">
                                                <!-- Cargado por JS -->
                                            </div>
                                        </div>

                                        <!-- Datos cargados del Alumno -->
                                        <div class="mb-3 bg-light p-3 rounded-3" id="reporte-alumno-info-box" style="display: none;">
                                            <input type="hidden" id="reporte_id_alumno">
                                            <input type="hidden" id="reporte_alumno_nombre">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="fw-bold d-block text-slate-800" id="lbl-reporte-alumno-nom"></span>
                                                    <small class="text-muted" id="lbl-reporte-alumno-mat"></small>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deseleccionarAlumnoReporte()"><i class="fa-solid fa-xmark"></i></button>
                                            </div>
                                        </div>

                                        <!-- Tutor -->
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-slate-700 fs-7">Nombre del Tutor</label>
                                            <input type="text" id="reporte_tutor_nombre" class="form-control" placeholder="Nombre completo del tutor o tutor legal" required>
                                        </div>

                                        <!-- Parcial -->
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-slate-700 fs-7">Parcial correspondiente</label>
                                            <select id="reporte_parcial" class="form-select" required>
                                                <option value="1">Parcial 1</option>
                                                <option value="2">Parcial 2</option>
                                                <option value="3">Parcial 3</option>
                                            </select>
                                        </div>

                                        <!-- Descripción de la indisciplina -->
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-slate-700 fs-7">Descripción del incidente</label>
                                            <textarea id="reporte_incidente" class="form-control" rows="4" placeholder="Describa la indisciplina cometida detalladamente..." required></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                                            <i class="fa-solid fa-floppy-disk me-2"></i>Registrar y Generar Reporte
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- HISTORIAL DE REPORTES -->
                            <div class="col-12 col-xl-7">
                                <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; background: #ffffff;">
                                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                                        <h6 class="fw-bold text-slate-800 mb-0">
                                            <i class="fa-solid fa-clock-rotate-left text-secondary me-2"></i>Historial de Reportes
                                        </h6>
                                        <div style="width: 250px;">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-filter text-muted"></i></span>
                                                <input type="text" id="reportesHistorialSearch" class="form-control border-start-0" placeholder="Buscar por alumno o folio..." onkeyup="cargarHistorialReportes(this.value)">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive" style="max-height: 500px;">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr class="fs-8">
                                                    <th>Folio</th>
                                                    <th>Alumno</th>
                                                    <th>Tutor</th>
                                                    <th>Parcial</th>
                                                    <th>Fecha</th>
                                                    <th class="text-center">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tabla-reportes-historial">
                                                <!-- Cargado por JS -->
                                                <tr>
                                                    <td colspan="6" class="text-center py-4 text-muted">Cargando historial...</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PANE: CREDENCIAL -->
                    <div class="tab-pane fade" id="pane-credencial" role="tabpanel">
                        <div class="glass-header mb-4">
                            <h5 class="fw-bold mb-1 text-slate-800">
                                <i class="fa-solid fa-id-card text-info me-2"></i>Credenciales Escolares
                            </h5>
                            <p class="text-muted mb-0">Buscador y visor de credenciales oficiales para impresión física.</p>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Buscar Alumno para Credencial</label>
                                <input type="text" id="credencialAlumnoSearch" class="form-control" placeholder="Escriba matrícula o nombre del alumno..." onkeyup="simularBusquedaAlumnoCredencial(this.value)">
                                <div id="credencial-alumno-sugerencia" class="list-group mt-2 shadow-sm" style="display: none;">
                                    <!-- Cargado por JS -->
                                </div>
                            </div>
                            <div class="col-12 mt-4" id="credencial-info-alumno" style="display: none;">
                                <div class="row">
                                    <!-- Vista Previa de la Credencial (Diseño CSS Premium) -->
                                    <div class="col-md-7 d-flex justify-content-center align-items-center mb-3">
                                        <div class="credential-card shadow-lg position-relative" id="mock-credencial">
                                            <!-- Encabezado de la Credencial -->
                                            <div class="cred-header d-flex align-items-center p-2 text-white">
                                                <i class="fa-solid fa-school me-2 text-warning fs-6"></i>
                                                <div>
                                                    <span class="d-block fw-bold" style="font-size: 0.65rem; line-height: 1.1;">COLEGIO INTERAMERICANO</span>
                                                    <span class="d-block text-warning fw-semibold" style="font-size: 0.52rem; letter-spacing:0.5px;" id="cred-cct-badge">PLAN BTI</span>
                                                </div>
                                            </div>
                                            <!-- Cuerpo -->
                                            <div class="cred-body p-3 d-flex gap-3 align-items-center">
                                                <!-- Foto del Alumno -->
                                                <div class="cred-photo-container">
                                                    <i class="fa-solid fa-user-tie text-secondary fa-3x mt-2"></i>
                                                </div>
                                                <!-- Datos -->
                                                <div class="cred-details flex-grow-1">
                                                    <span class="cred-label">NOMBRE</span>
                                                    <span class="cred-value fw-bold text-slate-800" id="cred-nombre-val">Pérez López Juan</span>
                                                    
                                                    <span class="cred-label mt-1">MATRÍCULA</span>
                                                    <span class="cred-value text-slate-700 fw-semibold" id="cred-matricula-val">20260001</span>
                                                    
                                                    <span class="cred-label mt-1">PROGRAMA</span>
                                                    <span class="cred-value text-slate-600" id="cred-plan-val">Informática e Interfaces</span>
                                                </div>
                                            </div>
                                            <!-- Footer -->
                                            <div class="cred-footer d-flex justify-content-between align-items-center px-3 py-2 text-white" style="background: #0f172a;">
                                                <span class="fs-9 font-monospace" style="opacity:0.85;">Vigencia: 2026-2027</span>
                                                <div class="cred-barcode d-flex align-items-center">
                                                    <i class="fa-solid fa-barcode fa-lg"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Acciones de Credencial -->
                                    <div class="col-md-5 d-flex flex-column justify-content-center gap-2">
                                        <h6 class="fw-bold text-slate-800">Acciones de Impresión</h6>
                                        <p class="text-muted fs-8">Genera un PDF con formato de credencial listo para imprimir en PVC o papel opalina.</p>
                                        <button type="button" class="btn btn-success fw-bold py-2 w-100" onclick="printDoc('Credencial Escolar', document.getElementById('cred-nombre-val').innerText)">
                                            <i class="fa-solid fa-print me-2"></i> Imprimir Credencial
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PANE: FORMATO EXTRAORDINARIO -->
                    <div class="tab-pane fade" id="pane-extraordinario" role="tabpanel">
                        <div class="glass-header mb-4">
                            <h5 class="fw-bold mb-1 text-slate-800">
                                <i class="fa-solid fa-circle-exclamation text-info me-2"></i>Formato Examen Extraordinario
                            </h5>
                            <p class="text-muted mb-0">Emisión de actas y recibos de derecho a exámenes extraordinarios.</p>
                        </div>
                        <div class="row g-3">
                            <!-- Alumno con búsqueda predictiva -->
                            <div class="col-md-6 position-relative">
                                <label class="form-label fw-semibold text-slate-700">Nombre del Alumno</label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-user-graduate"></i></span>
                                    <input type="text" id="extraordinarioAlumnoSearch" class="form-control border-start-0" placeholder="Escriba matrícula o nombre del alumno..." autocomplete="off" onkeyup="simularBusquedaAlumnoExtraordinario(this.value)">
                                </div>
                                <div id="extraordinario-alumno-sugerencia" class="list-group mt-1 shadow position-absolute w-100" style="display: none; z-index: 1050; max-height: 220px; overflow-y: auto;">
                                    <!-- Cargado por JS -->
                                </div>
                            </div>

                            <!-- Grupo: escritura y sugerencias dinámicas -->
                            <div class="col-md-6 position-relative">
                                <label class="form-label fw-semibold text-slate-700">Grupo</label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-users"></i></span>
                                    <input type="text" id="extraordinarioGrupoInput" class="form-control border-start-0" placeholder="Escriba o busque grupo (ej. 101, BTI...)" autocomplete="off" oninput="buscarGruposExtraordinario(this.value)" onfocus="buscarGruposExtraordinario(this.value)">
                                </div>
                                <div id="extraordinario-grupo-sugerencia" class="list-group mt-1 shadow position-absolute w-100" style="display: none; z-index: 1050; max-height: 220px; overflow-y: auto;">
                                    <!-- Cargado por JS -->
                                </div>
                            </div>

                            <!-- Semestre Select para desglosar materias -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-slate-700" id="lbl-extraordinario-semestre-label">Semestre</label>
                                <select id="extraordinarioSemestreSelect" class="form-select shadow-sm" onchange="onExtraordinarioSemestreChange()">
                                    <option value="">Seleccione Semestre</option>
                                    <option value="1">1º Semestre</option>
                                    <option value="2">2º Semestre</option>
                                    <option value="3">3º Semestre</option>
                                    <option value="4">4º Semestre</option>
                                    <option value="5">5º Semestre</option>
                                    <option value="6">6º Semestre</option>
                                </select>
                            </div>

                            <!-- Materia: escritura y sugerencias filtradas por el semestre seleccionado -->
                            <div class="col-md-4 position-relative">
                                <label class="form-label fw-semibold text-slate-700">Materia</label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-book-open"></i></span>
                                    <input type="text" id="extraordinarioMateriaInput" class="form-control border-start-0" placeholder="Escriba o seleccione materia..." autocomplete="off" oninput="buscarMateriasExtraordinario(this.value)" onfocus="buscarMateriasExtraordinario(this.value)">
                                </div>
                                <div id="extraordinario-materia-sugerencia" class="list-group mt-1 shadow position-absolute w-100" style="display: none; z-index: 1050; max-height: 220px; overflow-y: auto;">
                                    <!-- Cargado por JS -->
                                </div>
                            </div>

                            <!-- Docente: escritura y sugerencias predictivas -->
                            <div class="col-md-4 position-relative">
                                <label class="form-label fw-semibold text-slate-700">Docente</label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-chalkboard-user"></i></span>
                                    <input type="text" id="extraordinarioDocenteInput" class="form-control border-start-0" placeholder="Escriba o seleccione docente..." autocomplete="off" oninput="buscarDocentesExtraordinario(this.value)" onfocus="buscarDocentesExtraordinario(this.value)">
                                </div>
                                <div id="extraordinario-docente-sugerencia" class="list-group mt-1 shadow position-absolute w-100" style="display: none; z-index: 1050; max-height: 220px; overflow-y: auto;">
                                    <!-- Cargado por JS -->
                                </div>
                            </div>

                            <!-- Resumen y botón para generar acta -->
                            <div class="col-12 mt-4" id="extraordinario-info-alumno" style="display: none;">
                                <div class="alert alert-warning border-0 p-3 shadow-sm" style="border-radius: 12px; background: rgba(245, 158, 11, 0.1);">
                                    <h6 class="fw-bold mb-1 text-slate-800" id="lbl-extraordinario-nombre"></h6>
                                    <div class="fs-8 mt-2 text-slate-700">
                                        <span class="d-block">Grupo: <strong id="lbl-extraordinario-grupo"></strong></span>
                                        <span class="d-block">Periodo: <strong id="lbl-extraordinario-semestre-val"></strong></span>
                                        <span class="d-block">Materia: <strong id="lbl-extraordinario-materia" class="text-primary"></strong></span>
                                        <span class="d-block">Docente: <strong id="lbl-extraordinario-docente"></strong></span>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary fw-bold py-2.5 px-4 shadow-sm" onclick="abrirPrevisualizacionExtraordinario()">
                                    <i class="fa-solid fa-eye me-2"></i> Previsualizar e Imprimir Formato
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- PANE: FORMATO ASISTENCIAS -->
                    <div class="tab-pane fade" id="pane-asistencias" role="tabpanel">
                        <div class="glass-header mb-4">
                            <h5 class="fw-bold mb-1" style="color: #334155;">
                                <i class="fa-solid fa-user-clock text-info me-2"></i> Formato de Asistencias por Grupo
                            </h5>
                            <p class="text-muted mb-0">Seleccione los criterios para generar la lista de asistencia del trimestre/semestre correspondiente.</p>
                        </div>

                        <!-- Selección de Grupo, Docente, Asignatura y Trimestre/Semestre -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-slate-700">Grupo</label>
                                <select id="asistenciaGrupoSelect" class="form-select shadow-sm" onchange="onAsistenciaGrupoChange()">
                                    <option value="">Seleccione Grupo</option>
                                    <!-- Se puebla según el CCT seleccionado -->
                                </select>
                            </div>
                            <div class="col-md-4 position-relative">
                                <label class="form-label fw-semibold text-slate-700">Docente</label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-chalkboard-user"></i></span>
                                    <input type="text" id="asistenciaDocenteInput" class="form-control border-start-0" placeholder="Escriba o busque docente..." autocomplete="off" oninput="buscarDocentesAsistencia(this.value)" onfocus="buscarDocentesAsistencia(this.value)">
                                    <button class="btn btn-outline-secondary border-start-0 bg-white text-muted" type="button" id="btn-limpiar-asistencia-docente" onclick="limpiarDocenteAsistencia()" style="display: none;" title="Limpiar docente">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                                <div id="asistencia-docente-sugerencia" class="list-group mt-1 shadow position-absolute w-100" style="display: none; z-index: 1050; max-height: 240px; overflow-y: auto;">
                                    <!-- Cargado por JS -->
                                </div>
                                <select id="asistenciaDocenteSelect" style="display: none;" onchange="onAsistenciaDocenteChange()">
                                    <option value="">Seleccione Docente</option>
                                    @if(isset($docentes))
                                        @foreach($docentes as $d)
                                            @php
                                                $dId = is_array($d) ? ($d['idDocente'] ?? $d['id'] ?? '') : ($d->idDocente ?? $d->id ?? '');
                                                $dNom = is_array($d) ? ($d['nombreDocente'] ?? $d['nombre'] ?? '') : ($d->nombreDocente ?? $d->nombre ?? '');
                                                $dPat = is_array($d) ? ($d['apPaternoDocente'] ?? $d['apPaterno'] ?? '') : ($d->apPaternoDocente ?? $d->apPaterno ?? '');
                                                $dMat = is_array($d) ? ($d['apMaternoDocente'] ?? $d['apMaterno'] ?? '') : ($d->apMaternoDocente ?? $d->apMaterno ?? '');
                                            @endphp
                                            <option value="{{ $dId }}">
                                                {{ trim("$dNom $dPat $dMat") }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3 position-relative">
                                <label class="form-label fw-semibold text-slate-700">Asignatura</label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-book-open"></i></span>
                                    <input type="text" id="asistenciaMateriaInput" class="form-control border-start-0" placeholder="Escriba o seleccione materia..." autocomplete="off" oninput="buscarMateriasAsistencia(this.value)" onfocus="buscarMateriasAsistencia(this.value)">
                                    <button class="btn btn-outline-secondary border-start-0 bg-white text-muted" type="button" id="btn-limpiar-asistencia-materia" onclick="limpiarMateriaAsistencia()" style="display: none;" title="Limpiar materia">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                                <div id="asistencia-materia-sugerencia" class="list-group mt-1 shadow position-absolute w-100" style="display: none; z-index: 1050; max-height: 240px; overflow-y: auto;">
                                    <!-- Cargado por JS -->
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold text-slate-700" id="lbl-asistencia-ciclo-label">Trimestre</label>
                                <select id="asistenciaCicloSelect" class="form-select shadow-sm" onchange="onAsistenciaCicloChange()">
                                    <!-- Cargado dinámicamente por JS (Semestres para BTI, Trimestres para BGNE) -->
                                </select>
                            </div>
                        </div>

                        <div class="col-12 mt-4" id="asistencia-preview-card" style="display: none;">
                            <div class="card p-4 border-0 bg-light shadow-sm" style="border-radius: 16px; border-left: 5px solid #0284c7 !important;">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                    <div>
                                        <h5 class="fw-bold mb-1 text-slate-800" id="lbl-asistencia-preview-docente"></h5>
                                        <div class="fs-8 mt-2 text-slate-700">
                                            <span class="d-block">Asignatura: <strong id="lbl-asistencia-preview-materia" class="text-primary"></strong></span>
                                            <span class="d-block">Grupo: <strong id="lbl-asistencia-preview-grupo"></strong></span>
                                            <span class="d-block" id="lbl-asistencia-preview-ciclo-texto">Periodo: <strong id="lbl-asistencia-preview-term"></strong></span>
                                            <span class="d-block text-muted" id="lbl-asistencia-preview-fechas-line">Fechas estimadas: <strong id="lbl-asistencia-preview-fechas-val" class="text-dark"></strong></span>
                                            <div class="mt-2">
                                                <span class="badge bg-success-subtle text-success fw-bold px-2 py-1" id="lbl-asistencia-preview-alumnos">0 alumnos inscritos</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <button type="button" class="btn btn-primary fw-bold py-2.5 px-4 shadow-sm" onclick="generarListaAsistenciaOficialPdf()">
                                            <i class="fa-solid fa-file-pdf me-2"></i> Generar Lista (PDF)
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


    <!-- MODAL DE PREVISUALIZACIÓN Y IMPRESIÓN DE EXAMEN EXTRAORDINARIO -->
    <div class="modal fade" id="modalExtraordinarioPreview" tabindex="-1" aria-labelledby="modalExtraordinarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="background: #f1f5f9;">
                <div class="modal-header py-3 px-4 d-flex justify-content-between align-items-center" style="background: #1e6fa8 !important; color: #ffffff !important;">
                    <h5 class="modal-title fw-bold text-white mb-0" id="modalExtraordinarioLabel">
                        <i class="fa-solid fa-file-circle-check me-2"></i> Vista Previa - Acta y Recibo de Examen Extraordinario
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-light btn-sm fw-semibold shadow-sm text-dark px-3" onclick="ejecutarImpresionExtraordinario()">
                            <i class="fa-solid fa-print me-1 text-primary"></i> Imprimir Documento
                        </button>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body p-4 d-flex justify-content-center" style="max-height: calc(85vh - 120px); overflow-y: auto;">
                    <div id="extraordinarioHojaImpresion" style="width: 100%; max-width: 760px; background: #ffffff; padding: 28px 32px; border-radius: 6px; box-shadow: 0 4px 15px rgba(0,0,0,0.06); border: 1px solid #cbd5e1; font-family: Arial, Helvetica, sans-serif; color: #000000;">
                        <!-- Se puebla dinámicamente con renderFormatoExtraordinario() -->
                    </div>
                </div>
                <div class="modal-footer bg-white py-2.5 px-4 d-flex justify-content-between border-top">
                    <button type="button" class="btn btn-outline-secondary px-3" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark me-1"></i> Cerrar
                    </button>
                    <button type="button" class="btn btn-primary fw-bold px-4 shadow-sm" onclick="ejecutarImpresionExtraordinario()">
                        <i class="fa-solid fa-print me-2"></i> Imprimir Formato
                    </button>
                </div>
            </div>
        </div>
    </div>

<!-- DATOS COMPARTIDOS (JS) -->
<script>
    window.gruposDb = @json($grupos ?? []);
    window.docentesDb = @json($docentes ?? []);
    window.horariosDb = @json($horarios ?? []);
    window.materiasDb = @json($materias ?? []);

    const mockAlumnos = [
        { matricula: '20260001', nombre: 'Pérez López Juan', plan: 'BTI - Tecnologías de la Información' },
        { matricula: '20260002', nombre: 'Gómez García María', plan: 'BGNE - Tronco Común' },
        { matricula: '20260003', nombre: 'Hernández Ruiz Carlos', plan: 'BTI - Electrónica y Sistemas' },
        { matricula: '20260004', nombre: 'Martínez Díaz Sofía', plan: 'BGNE - Administrativo' },
        { matricula: '20260005', nombre: 'Rodríguez Solís Ana', plan: 'INF - Informática y Computación' }
    ];
</script>

<!-- ESTILOS CSS PREMIUM INTEGRADOS (GLASSMORPHISM Y CREDENCIAL) -->
<style>
    /* Tarjetas de CCT */
    .cct-card {
        background: rgba(255, 255, 255, 0.6);
        border: 2px solid rgba(255, 255, 255, 0.4);
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.02);
    }

    .cct-card:hover {
        transform: translateY(-5px);
        background: #ffffff;
        border-color: #38bdf8;
        box-shadow: 0 10px 25px rgba(56, 189, 248, 0.12);
    }

    .cct-card.selected-cct {
        background: #ffffff;
        border-color: #0284c7;
        box-shadow: 0 10px 30px rgba(2, 132, 199, 0.18);
        transform: scale(1.02);
    }

    .cct-icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    /* Contenedor General */
    .glass-card {
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    .page-title {
        font-weight: 750;
        color: #1e293b;
        letter-spacing: -0.5px;
    }

    .btn-regresar {
        background: #f1f5f9;
        border: 1.5px solid #cbd5e1;
        color: #475569;
        font-weight: 600;
        padding: 8px 18px;
        border-radius: 12px;
        transition: all 0.25s ease;
    }

    .btn-regresar:hover {
        background: #e2e8f0;
        color: #1e293b;
        transform: translateY(-2px);
    }

    /* Pestañas de Navegación */
    .nav-pills .nav-link {
        color: #475569;
        background: transparent;
        border-radius: 10px;
        transition: all 0.25s ease;
        border: 1.5px solid transparent;
        text-align: left;
        margin-bottom: 5px;
    }

    .nav-pills .nav-link:hover {
        color: #0f172a;
        background: rgba(0, 0, 0, 0.02);
    }

    .nav-pills .nav-link.active {
        color: #ffffff;
        background: linear-gradient(135deg, #0284c7, #0369a1);
        box-shadow: 0 4px 12px rgba(3, 105, 161, 0.2);
    }

    /* Tabla Estilizada */
    .glass-table {
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .glass-table thead th {
        border: none;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        padding: 12px 16px;
    }

    .glass-table tbody tr {
        background: rgba(255, 255, 255, 0.45);
        border-radius: 12px;
        transition: all 0.2s ease;
    }

    .glass-table tbody tr:hover {
        transform: translateY(-2px);
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    .glass-table tbody td {
        border: none;
        padding: 12px 16px;
    }

    .glass-table tbody tr td:first-child {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }

    .glass-table tbody tr td:last-child {
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    /* Avatar */
    .avatar-sm {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        background: linear-gradient(135deg, #38bdf8, #0284c7) !important;
    }

    /* DISEÑO DE CREDENCIAL ESCOLAR EN CSS */
    .credential-card {
        width: 320px;
        height: 200px;
        background: #ffffff;
        border-radius: 16px;
        overflow: hidden;
        border: 2px solid #0f172a;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    }

    .cred-header {
        background: linear-gradient(135deg, #0f172a, #1e293b);
        height: 48px;
        border-bottom: 2.5px solid #e2e8f0;
    }

    .cred-photo-container {
        width: 80px;
        height: 100px;
        border: 1.5px solid #cbd5e1;
        background: #f8fafc;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .cred-label {
        font-size: 0.52rem;
        color: #94a3b8;
        font-weight: 700;
        letter-spacing: 0.3px;
        display: block;
    }

    .cred-value {
        font-size: 0.72rem;
        display: block;
        line-height: 1.2;
    }

    /* Utilidades */
    .fs-8 { font-size: 0.8rem; }
    .fs-9 { font-size: 0.72rem; }
    .text-slate-800 { color: #1e293b; }
    .text-slate-700 { color: #334155; }
    .text-slate-600 { color: #475569; }
</style>

    <!-- MODAL DE KÁRDEX OFICIAL / CALIFICACIONES -->
    <div class="modal fade" id="modalKardexAlumno" tabindex="-1" aria-labelledby="modalKardexLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="background: #ffffff;">
                <div class="modal-header py-3 px-4 d-flex justify-content-between align-items-center" style="background: #1e6fa8 !important; color: #ffffff !important;">
                    <h5 class="modal-title fw-bold text-white mb-0" id="modalKardexLabel">
                        <i class="fa-solid fa-graduation-cap me-2"></i> Kárdex Oficial y Calificaciones
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-light btn-sm fw-semibold shadow-sm text-dark px-3" onclick="imprimirKardex()">
                            <i class="fa-solid fa-print me-1 text-primary"></i> Imprimir Kárdex
                        </button>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body p-4 bg-white" id="kardexPrintArea" style="max-height: calc(85vh - 130px); overflow-y: auto;">
                    
                    <!-- ENCABEZADO OFICIAL CON MEMBRETE INSTITUCIONAL -->
                    <div class="mb-3 pt-1">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <!-- Logo Institucional -->
                            <div style="flex-shrink: 0;">
                                <img id="kardexLogoImg" src="{{ asset('img/logo.png') }}" alt="Logo Institucional" style="height: 80px; width: auto; object-fit: contain;">
                            </div>

                            <!-- Banner Bicolor Oficial -->
                            <div class="flex-grow-1 border" style="border-color: #10599a !important; overflow: hidden; border-radius: 4px;">
                                <div class="py-1 px-3 text-center text-white fw-bold text-uppercase" style="background: #10599a; font-size: 1.15rem; letter-spacing: 1px;">
                                    BACHILLERATO INTERAMERICANO
                                </div>
                                <div class="py-1 px-2 text-center" style="background: #d4ebf9; color: #0f172a; font-size: 0.78rem; line-height: 1.35;">
                                    <div>Avenida Benito Juárez 901, Colonia Centro Teziutlán Puebla. Tel: 231-3123979</div>
                                    <div class="fw-bold" id="kardexCCTClave">CLAVE CT: 21PBH0353G</div>
                                </div>
                            </div>
                        </div>

                        <!-- Texto institucional y Alumno -->
                        <div class="text-center mt-2">
                            <div class="text-dark" style="font-size: 0.85rem;">
                                La Dirección de la escuela <strong id="kardexCCTNombre">BACHILLERATO GENERAL NO ESCOLARIZADO</strong>
                            </div>
                            <div class="text-secondary fst-italic" style="font-size: 0.80rem;">
                                Reporta las siguientes calificaciones obtenidas hasta el momento del alumno(a):
                            </div>
                            <div class="my-2 py-1 px-4 bg-light border rounded-pill d-inline-block shadow-sm">
                                <span class="fw-bold text-dark text-uppercase fs-5" id="kardexNombreAlumno" style="letter-spacing: 0.5px; text-decoration: underline;">
                                    —
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- CONTENEDOR DE PERIODOS (GRID 2 COLUMNAS X 3 FILAS) -->
                    <div class="row g-2" id="contenedorPeriodosKardex">
                        <div class="col-12 text-center py-5">
                            <div class="spinner-border text-primary"></div>
                            <div class="text-muted mt-2">Cargando kárdex de calificaciones...</div>
                        </div>
                    </div>

                    <!-- LEMA INFERIOR OFICIAL -->
                    <div class="text-center mt-2 p-1 text-white fw-semibold rounded-1" style="background: #4a90e2; font-size: 0.82rem; letter-spacing: 0.5px;">
                        ¡ Excelencia educativa a su servicio !
                    </div>

                </div>
                <div class="modal-footer bg-light border-top py-2 px-4 d-flex justify-content-between">
                    <span class="text-muted" style="font-size: 0.82rem;">
                        <i class="fa-solid fa-circle-info me-1 text-info"></i> Puedes ajustar calificaciones directamente en cada casilla.
                    </span>
                    <div>
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-success px-4 fw-bold shadow-sm" onclick="guardarCalificacionesKardex()" id="btnGuardarKardex">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Guardar Calificaciones
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- LÓGICA DE NEGOCIO PARA SELECCIÓN DE CCT Y FORMATOS CONDICIONALES -->
<script>
    // Configuración de Submódulos autorizados por CCT
    const submodulosPorCCT = {
        'BTI': [
            { id: 'constancia', nombre: 'Constancia de Estudios', icon: 'fa-file-invoice' },
            { id: 'kardex', nombre: 'Kardex Académico', icon: 'fa-graduation-cap' },
            { id: 'boleta', nombre: 'Boleta de Calificaciones', icon: 'fa-file-signature' },
            { id: 'reporte_indisciplina', nombre: 'Reportes de Indisciplina', icon: 'fa-triangle-exclamation' },
            { id: 'credencial', nombre: 'Credencial Escolar', icon: 'fa-id-card' },
            { id: 'extraordinario', nombre: 'Formato Extraordinario', icon: 'fa-circle-exclamation' },
            { id: 'asistencias', nombre: 'Formato de Asistencias', icon: 'fa-user-clock' }
        ],
        'BGNE': [
            { id: 'constancia', nombre: 'Constancia de Estudios', icon: 'fa-file-invoice' },
            { id: 'kardex', nombre: 'Kardex Académico', icon: 'fa-graduation-cap' },
            { id: 'credencial', nombre: 'Credencial Escolar', icon: 'fa-id-card' },
            { id: 'extraordinario', nombre: 'Formato Extraordinario', icon: 'fa-circle-exclamation' },
            { id: 'asistencias', nombre: 'Formato de Asistencias', icon: 'fa-user-clock' }
        ],
        'INF': [
            { id: 'credencial', nombre: 'Credencial Escolar', icon: 'fa-id-card' },
            { id: 'constancia', nombre: 'Constancia de Estudios', icon: 'fa-file-invoice' }
        ]
    };

    let cctSeleccionado = '';

    // Función principal para la selección del CCT
    function selectCCT(cct) {
        cctSeleccionado = cct;
        if (typeof limpiarBusquedaKardex === 'function') { limpiarBusquedaKardex(); }
        
        // Actualizar UI del selector
        document.querySelectorAll('.cct-card').forEach(card => card.classList.remove('selected-cct'));
        
        const activeCardId = cct === 'BTI' ? 'cct-bti' : (cct === 'BGNE' ? 'cct-bgne' : 'cct-inf');
        document.getElementById(activeCardId).classList.add('selected-cct');

        // Ocultar placeholder y mostrar contenedor principal
        document.getElementById('placeholder-select-cct').style.display = 'none';
        document.getElementById('formatos-container').style.display = 'flex';

        // Actualizar textos en la UI
        document.getElementById('lbl-cct-activo').innerText = cct;
        document.getElementById('cred-cct-badge').innerText = `PLAN ${cct}`;

        // Actualizar etiqueta de ciclo en Asistencia
        const label = document.getElementById('lbl-asistencia-ciclo-label');
        const select = document.getElementById('asistenciaCicloSelect');
        if (label && select) {
            select.innerHTML = '';
            if (cct === 'BTI') {
                label.innerText = 'Semestre';
                for (let i = 1; i <= 6; i++) {
                    select.innerHTML += `<option value="${i}° Semestre">${i}° Semestre</option>`;
                }
            } else {
                label.innerText = 'Trimestre';
                for (let i = 1; i <= 6; i++) {
                    select.innerHTML += `<option value="${i}° Trimestre">${i}° Trimestre</option>`;
                }
            }
        }

        // Cargar los grupos del CCT seleccionado
        actualizarGruposPorCCT(cct);

        // Resetear vistas previas de sugerencias y búsquedas
        resetSubmodulosState();

        // Cargar los submódulos (tabs) condicionalmente en la barra lateral
        cargarSubmodulosNav(cct);
    }

    function actualizarGruposPorCCT(cct) {
        const select = document.getElementById('asistenciaGrupoSelect');
        if (!select) return;
        select.innerHTML = '<option value="">Seleccione Grupo</option>';

        const targetCentroId = cct === 'BTI' ? 2 : (cct === 'BGNE' ? 3 : 1);
        const filtered = (window.gruposDb || []).filter(g => {
            const cid = g.id_centroTrabajo ?? g.idCentroTrabajo ?? g.id_centro_trabajo;
            return cid == targetCentroId;
        });

        filtered.forEach(g => {
            const opt = document.createElement('option');
            opt.value = g.id;
            opt.textContent = `${g.clave} (${g.modalidadHorario || 'General'})`;
            select.appendChild(opt);
        });

        const previewCard = document.getElementById('asistencia-preview-card');
        if (previewCard) previewCard.style.display = 'none';

        asegurarCatalogosAsistencia();
    }

    let isFetchingCatalogos = false;
    async function asegurarCatalogosAsistencia() {
        if (isFetchingCatalogos) return;
        if (!window.gruposDb || window.gruposDb.length === 0 || !window.docentesDb || window.docentesDb.length === 0) {
            isFetchingCatalogos = true;
            try {
                const [gRes, dRes] = await Promise.all([
                    fetch('/grupos/lista?limit=1000').then(r => r.json()).catch(() => ({ data: [] })),
                    fetch('/docentes/lista').then(r => r.json()).catch(() => ({ data: [] }))
                ]);
                if ((!window.gruposDb || window.gruposDb.length === 0) && gRes.data) {
                    window.gruposDb = gRes.data;
                    if (cctSeleccionado) {
                        const select = document.getElementById('asistenciaGrupoSelect');
                        if (select && select.options.length <= 1) {
                            const targetCentroId = cctSeleccionado === 'BTI' ? 2 : (cctSeleccionado === 'BGNE' ? 3 : 1);
                            const filtered = (window.gruposDb || []).filter(g => {
                                const cid = g.id_centroTrabajo ?? g.idCentroTrabajo ?? g.id_centro_trabajo;
                                return cid == targetCentroId;
                            });
                            filtered.forEach(g => {
                                const opt = document.createElement('option');
                                opt.value = g.id;
                                opt.textContent = `${g.clave} (${g.modalidadHorario || 'General'})`;
                                select.appendChild(opt);
                            });
                        }
                    }
                }
                if (!window.docentesDb || window.docentesDb.length === 0) {
                    const rawDoc = dRes.data || (Array.isArray(dRes) ? dRes : []);
                    window.docentesDb = rawDoc;
                    poblarSelectDocentes(rawDoc);
                }
            } catch (err) {
                console.warn('Carga diferida catálogos:', err);
            } finally {
                isFetchingCatalogos = false;
            }
        }
    }

    function poblarSelectDocentes(lista) {
        if (lista && Array.isArray(lista) && lista.length > 0) {
            window.docentesDb = lista;
        }
        const sel = document.getElementById('asistenciaDocenteSelect');
        if (!sel || sel.options.length > 1) return;
        lista.forEach(d => {
            const id = d.idDocente || d.id;
            const nom = d.nombreDocente || d.nombre || '';
            const pat = d.apPaternoDocente || d.apPaterno || '';
            const mat = d.apMaternoDocente || d.apMaterno || '';
            const opt = document.createElement('option');
            opt.value = id;
            opt.textContent = `${nom} ${pat} ${mat}`.trim();
            sel.appendChild(opt);
        });
    }

    function resetSubmodulosState() {
        // Ocultar e inhabilitar búsquedas anteriores
        document.getElementById('constancia-info-alumno').style.display = 'none';
        document.getElementById('kardex-info-alumno').style.display = 'none';
        document.getElementById('boleta-tabla-resultados').style.display = 'none';
        document.getElementById('credencial-info-alumno').style.display = 'none';
        document.getElementById('extraordinario-info-alumno').style.display = 'none';
        
        const asistCard = document.getElementById('asistencia-preview-card');
        if (asistCard) asistCard.style.display = 'none';
        
        // Limpiar controles de Asistencia
        const docenteSelect = document.getElementById('asistenciaDocenteSelect');
        const docenteInput = document.getElementById('asistenciaDocenteInput');
        const grupoSelect = document.getElementById('asistenciaGrupoSelect');
        const materiaInput = document.getElementById('asistenciaMateriaInput');
        if (docenteSelect) docenteSelect.value = '';
        if (docenteInput) docenteInput.value = '';
        if (grupoSelect) grupoSelect.value = '';
        if (materiaInput) materiaInput.value = '';

        const btnClearDoc = document.getElementById('btn-limpiar-asistencia-docente');
        if (btnClearDoc) btnClearDoc.style.display = 'none';
        const sugDoc = document.getElementById('asistencia-docente-sugerencia');
        if (sugDoc) { sugDoc.innerHTML = ''; sugDoc.style.display = 'none'; }

        const btnClearMat = document.getElementById('btn-limpiar-asistencia-materia');
        if (btnClearMat) btnClearMat.style.display = 'none';
        const sugMat = document.getElementById('asistencia-materia-sugerencia');
        if (sugMat) { sugMat.innerHTML = ''; sugMat.style.display = 'none'; }
    }

    // Carga dinámica de la barra de navegación lateral según el CCT seleccionado
    function cargarSubmodulosNav(cct) {
        const nav = document.getElementById('submodulos-nav');
        nav.innerHTML = ''; // Limpiar anteriores

        const submodulos = submodulosPorCCT[cct];
        
        submodulos.forEach((sub, index) => {
            const button = document.createElement('button');
            button.className = `nav-link py-2.5 px-3 fw-bold d-flex align-items-center gap-2 w-100 ${index === 0 ? 'active' : ''}`;
            button.id = `tab-${sub.id}`;
            button.setAttribute('data-bs-toggle', 'pill');
            button.setAttribute('data-bs-target', `#pane-${sub.id}`);
            button.setAttribute('type', 'button');
            button.setAttribute('role', 'tab');
            button.innerHTML = `<i class="fa-solid ${sub.icon}"></i> ${sub.nombre}`;
            
            button.addEventListener('click', (e) => {
                if (window.bootstrap && bootstrap.Tab) {
                    bootstrap.Tab.getOrCreateInstance(button).show();
                }
                // Si cambiamos de pestaña, resetear inputs visibles
                document.querySelectorAll('#submodulos-tab-content input:not([type="hidden"])').forEach(input => input.value = '');
                document.querySelectorAll('.list-group').forEach(list => list.style.display = 'none');
                
                if (sub.id === 'reporte_indisciplina') {
                    cargarHistorialReportes();
                }
            });

            nav.appendChild(button);
        });

        // Activar el primer panel y desactivar los demás
        const contentPanes = ['constancia', 'kardex', 'boleta', 'reporte_indisciplina', 'credencial', 'extraordinario', 'asistencias'];
        contentPanes.forEach(pane => {
            const el = document.getElementById(`pane-${pane}`);
            if (el) {
                el.classList.remove('show', 'active');
            }
        });

        // Activar el primero que pertenezca al CCT seleccionado
        const primerSubmoduloId = submodulos[0].id;
        const primerPane = document.getElementById(`pane-${primerSubmoduloId}`);
        primerPane.classList.add('show', 'active');

        // Si se carga la pestaña de asistencia, resetear vista previa
        actualizarAsistenciaPreview();
    }

    // ==========================================
    // SIMULACIÓN DE BÚSQUEDAS EN TIEMPO REAL
    // ==========================================

    let idAlumnoKardexActual = null;
    let datosKardexActual = null;
    let alumnoSeleccionadoActual = null;
    const canEditAlumno = @json(has_perm('alumnos_list', 'crear'));

    let ultimaBusquedaQuery = {};

    let debounceTimeouts = {};

    function buscarAlumnosReal(query, sugerenciaDivId, callbackSeleccion) {
        const div = document.getElementById(sugerenciaDivId);
        if (!div) return;
        
        if (query.trim().length < 2) {
            div.innerHTML = '';
            div.style.display = 'none';
            return;
        }

        clearTimeout(debounceTimeouts[sugerenciaDivId]);
        debounceTimeouts[sugerenciaDivId] = setTimeout(() => {
            ultimaBusquedaQuery[sugerenciaDivId] = query;

            fetch(`/alumnos/lista?search=${encodeURIComponent(query)}&limit=8`)
                .then(r => r.json())
                .then(resp => {
                    if (ultimaBusquedaQuery[sugerenciaDivId] !== query) {
                        return;
                    }

                    div.innerHTML = '';
                    const alumnos = resp.data || [];
                    if (alumnos.length === 0) {
                        div.style.display = 'none';
                        return;
                    }

                    div.style.display = 'block';
                    alumnos.forEach(al => {
                        const fullName = `${al.nombre} ${al.apPaterno} ${al.apMaterno || ''}`.trim();
                        const matricula = al.numeroControl || al.idAlumno || 'S/N';
                        
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'list-group-item list-group-item-action py-2 text-start';
                        btn.style.borderLeft = '3px solid #0284c7';
                        btn.innerHTML = `
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold d-block text-slate-800 fs-8">${fullName}</span>
                                    <small class="text-muted fs-9">Matrícula: ${matricula} | CCT: ${al.claveCentroTrabajo || 'N/A'}</small>
                                </div>
                                <span class="badge bg-secondary-subtle text-secondary fs-9" style="font-size: 0.68rem !important;">${al.statusAlumno || 'ACTIVO'}</span>
                            </div>
                        `;
                        btn.onclick = (e) => {
                            e.preventDefault();
                            callbackSeleccion(al);
                            div.style.display = 'none';
                        };
                        div.appendChild(btn);
                    });
                })
                .catch(err => {
                    console.error('Error al buscar alumnos:', err);
                });
        }, 300);
    }

    function cargarKardexAlumnoReal(idAlumno, callbackSuccess) {
        idAlumnoKardexActual = idAlumno;
        Swal.fire({
            title: 'Cargando información...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(`/alumnos/${idAlumno}/kardex`)
            .then(r => r.json())
            .then(resp => {
                Swal.close();
                if (!resp.success || !resp.data) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo cargar la información del alumno.'
                    });
                    return;
                }

                datosKardexActual = resp.data;
                alumnoSeleccionadoActual = resp.data.alumno;
                if (callbackSuccess) {
                    callbackSuccess(resp.data);
                }
            })
            .catch(err => {
                Swal.close();
                console.error('Error al cargar kárdex:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un error al comunicarse con el servidor.'
                });
            });
    }

    function simularBusquedaAlumnoConstancia(query) {
        buscarAlumnosReal(query, 'constancia-alumno-sugerencia', (alumno) => {
            const fullName = `${alumno.nombre} ${alumno.apPaterno} ${alumno.apMaterno || ''}`.trim();
            document.getElementById('constanciaAlumnoSearch').value = fullName;
            document.getElementById('lbl-constancia-nombre').innerText = fullName;
            document.getElementById('lbl-constancia-carrera').innerText = `${alumno.nombreNivelIngreso || 'Carrera general'} | CCT: ${alumno.claveCentroTrabajo || cctSeleccionado}`;
            document.getElementById('constancia-info-alumno').style.display = 'block';
        });
    }

    function limpiarBusquedaKardex() {
        const input = document.getElementById('kardexAlumnoSearch');
        if (input) input.value = '';
        const sugerencias = document.getElementById('kardex-alumno-sugerencia');
        if (sugerencias) {
            sugerencias.innerHTML = '';
            sugerencias.style.display = 'none';
        }
        const infoBox = document.getElementById('kardex-info-alumno');
        if (infoBox) infoBox.style.display = 'none';
        datosKardexActual = null;
        idAlumnoKardexActual = null;
    }

    function simularBusquedaAlumnoKardex(query) {
        buscarAlumnosReal(query, 'kardex-alumno-sugerencia', (alumno) => {
            const fullName = `${alumno.nombre} ${alumno.apPaterno} ${alumno.apMaterno || ''}`.trim();
            document.getElementById('kardexAlumnoSearch').value = fullName;
            
            cargarKardexAlumnoReal(alumno.idAlumno, (data) => {
                datosKardexActual = data;
                idAlumnoKardexActual = alumno.idAlumno;
                const al = data.alumno || alumno;
                
                document.getElementById('lbl-kardex-nombre').innerText = fullName;
                const cctClave = al.claveCentroTrabajo || (cctSeleccionado === 'BTI' ? '21PCT0073R' : '21PBH0353G');
                document.getElementById('lbl-kardex-matricula').innerText = `Matrícula: ${al.numeroControl || al.idAlumno} | CCT: ${cctClave}`;
                
                const badgeCct = document.getElementById('lbl-kardex-badge-cct');
                if (badgeCct) badgeCct.innerText = al.nombreCentroTrabajo || cctSeleccionado || 'BTI';

                const badgeStatus = document.getElementById('lbl-kardex-status');
                if (badgeStatus) {
                    badgeStatus.innerText = al.statusAlumno || 'ACTIVO';
                    if ((al.statusAlumno || '').toUpperCase() === 'INACTIVO' || (al.statusAlumno || '').toUpperCase() === 'BAJA') {
                        badgeStatus.className = 'badge bg-danger-subtle text-danger px-2 py-1 fs-9';
                    } else {
                        badgeStatus.className = 'badge bg-success-subtle text-success px-2 py-1 fs-9';
                    }
                }

                const genGrupo = document.getElementById('lbl-kardex-generacion-grupo');
                if (genGrupo) {
                    genGrupo.innerText = `Gen: ${al.nombreGeneracion || 'N/A'} | Grupo: ${al.claveGrupo || 'N/A'}`;
                }

                const prom = (data.promedioGeneral !== null && data.promedioGeneral !== undefined) ? data.promedioGeneral : '—';
                document.getElementById('lbl-kardex-promedio').innerText = prom;

                // Renderizar vista previa integrada para que se pueda ver en pantalla antes de imprimir
                renderizarKardexPreview(data);

                // Poblar los elementos del modal en segundo plano (sin abrir modal)
                mostrarKardexConDatos(data, false);
                
                document.getElementById('kardex-info-alumno').style.display = 'block';
            });
        });
    }

    function renderizarKardexPreview(data) {
        const previewEl = document.getElementById('kardex-live-preview');
        if (!previewEl || !data || !data.alumno) return;

        const al = data.alumno;
        const isBti = (cctSeleccionado === 'BTI') || (al.id_centroTrabajo === 2 || al.claveCentroTrabajo === '21PCT0073R' || (al.nombreCentroTrabajo && al.nombreCentroTrabajo.toUpperCase().includes('BTI')));

        const alumnoNombre = `${al.apPaterno || ''} ${al.apMaterno || ''} ${al.nombre || ''}`.trim().toUpperCase() || '—';
        const cctClave = isBti 
            ? `CLAVE CT: ${al.claveCentroTrabajo || '21PCT0073R'}` 
            : `CLAVE CT: ${al.claveCentroTrabajo || '21PBH0353G'}`;
        const cctNombre = isBti 
            ? 'BACHILLERATO TECNOLÓGICO INTERAMERICANO' 
            : 'BACHILLERATO GENERAL NO ESCOLARIZADO';

        const periodos = data.periodos || [];
        const promFinal = (data.promedioGeneral !== null && data.promedioGeneral !== undefined) ? data.promedioGeneral : '—';

        function renderTablaPreviewPeriodo(p, is5to, is6to) {
            if (!p) return '';
            let filasHtml = '';
            (p.materias || []).forEach(m => {
                if (m.es_equivalencia) {
                    filasHtml += `
                        <tr>
                            <td class="text-uppercase" style="border: 1px solid #000; padding: 2px 4px; font-size: 7.5pt; text-align: left;">${m.nombreMateria}</td>
                            <td style="border: 1px solid #000; padding: 2px 4px; font-size: 7.5pt; text-align: center; font-weight: bold; color: #d97706;">EQUIV.</td>
                        </tr>
                    `;
                } else {
                    const cVal = (m.calificacion !== null && m.calificacion !== undefined) ? m.calificacion : '—';
                    const esRep = cVal !== '—' && !isNaN(cVal) && parseFloat(cVal) < 6.0;
                    const styleColor = esRep ? 'color: #dc2626 !important; font-weight: bold;' : 'color: #000;';
                    filasHtml += `
                        <tr>
                            <td class="text-uppercase" style="border: 1px solid #000; padding: 2px 4px; font-size: 7.5pt; text-align: left;">${m.nombreMateria}</td>
                            <td style="border: 1px solid #000; padding: 2px 4px; font-size: 8pt; text-align: center; ${styleColor}">${cVal}</td>
                        </tr>
                    `;
                }
            });

            let footerExtra = '';
            if (is5to) {
                const finalColor = promFinal !== '—' && !isNaN(promFinal) && parseFloat(promFinal) < 6.0 ? 'color: #dc2626 !important;' : 'color: #1e6fa8;';
                footerExtra = `
                    <tr style="font-weight: bold; background: #e8ecf2;">
                        <td style="border: 1.5px solid #000; padding: 2px 4px; font-size: 7.8pt; text-align: right;">PROMEDIO FINAL</td>
                        <td style="border: 1.5px solid #000; padding: 2px 4px; font-size: 8.5pt; font-weight: bold; text-align: center; background: #e8ecf2; ${finalColor}">${promFinal}</td>
                    </tr>
                `;
            } else if (is6to) {
                footerExtra = `
                    <tr>
                        <td colspan="2" style="border: 1px solid #000; height: 21px; background: #f8fafc;"></td>
                    </tr>
                `;
            }

            const pAvg = (p.promedio !== null && p.promedio !== undefined) ? p.promedio : '—';
            const promColor = pAvg !== '—' && !isNaN(pAvg) && parseFloat(pAvg) < 6.0 ? 'color: #dc2626 !important;' : 'color: #000;';

            return `
                <div style="margin-bottom: 8px;">
                    <div style="font-size: 8pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; color: #000;">
                        ${p.nombrePeriodo}
                    </div>
                    <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; font-family: Arial, sans-serif; background: #ffffff;">
                        <thead>
                            <tr style="background: #ffffff;">
                                <th style="border: 1px solid #000; padding: 2px 4px; font-size: 7.5pt; text-align: left; font-weight: bold;">MATERIA</th>
                                <th style="border: 1px solid #000; padding: 2px 2px; font-size: 7pt; text-align: center; font-weight: bold; width: 75px; line-height: 1.1;">${isBti ? 'CALIFICACIÓN<br>FINAL' : 'EVALUACIÓN<br>OBTENIDA'}</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${filasHtml}
                            <tr>
                                <td style="border: 1px solid #000; padding: 2px 4px; font-size: 7.8pt; font-weight: bold; text-align: right;">PROMEDIO</td>
                                <td style="border: 1px solid #000; padding: 2px 4px; font-size: 8.2pt; font-weight: bold; text-align: center; ${promColor}">${pAvg}</td>
                            </tr>
                            ${footerExtra}
                        </tbody>
                    </table>
                </div>
            `;
        }

        const fechaHoy = new Date();
        const meses = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
        const fechaTexto = `TEZIUTLÁN PUEBLA A ${fechaHoy.getDate()} DE ${meses[fechaHoy.getMonth()]} DE ${fechaHoy.getFullYear()}`;

        let signaturesHtml = '';
        if (isBti) {
            signaturesHtml = `
                <div style="margin-top: 15px; display: flex; justify-content: space-between; align-items: flex-end; width: 100%; font-family: Arial, sans-serif;">
                    <div style="width: 250px; text-align: center;">
                        <div style="border-bottom: 1px solid #000; width: 180px; margin: 0 auto 4px auto; height: 30px;"></div>
                        <div style="font-size: 7.8pt; font-weight: bold;">ING. FAUSTO LEYVA FLORES</div>
                        <div style="font-size: 7.2pt; color: #444; text-transform: uppercase;">DIRECTOR</div>
                    </div>
                    <div style="width: 250px; text-align: center; font-size: 8pt; font-weight: bold; padding-bottom: 10px;">
                        ${fechaTexto}
                    </div>
                </div>
            `;
        }

        previewEl.innerHTML = `
            <div style="max-width: 740px; margin: 0 auto; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 15px 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                <!-- MEMBRETE INSTITUCIONAL -->
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; gap: 10px;">
                    <div style="width: 70px; flex-shrink: 0; text-align: left;">
                        <img src="/img/logo.png" alt="Logo" style="height: 60px; object-fit: contain;">
                    </div>
                    <div style="flex-grow: 1; border: 1.5px solid #10599a; overflow: hidden; border-radius: 4px;">
                        <div style="background: #10599a; color: #ffffff; font-weight: bold; font-size: 11pt; padding: 3px 6px; text-align: center; letter-spacing: 0.8px;">
                            BACHILLERATO INTERAMERICANO
                        </div>
                        <div style="background: #d4ebf9; color: #000000; font-size: 7.2pt; padding: 2px 6px; text-align: center; line-height: 1.35;">
                            <div>Avenida Benito Juárez 901, Colonia Centro Teziutlán Puebla. Tel: 231-3123979</div>
                            <div style="font-weight: bold;">${cctClave}</div>
                        </div>
                    </div>
                </div>

                <!-- TEXTO DIRECCIÓN Y NOMBRE ALUMNO -->
                <div style="text-align: center; margin-bottom: 10px;">
                    <div style="font-size: 8.5pt; color: #000;">
                        La Dirección de la escuela <strong>${cctNombre}</strong>
                    </div>
                    <div style="font-size: 7.8pt; color: #333; font-style: italic;">
                        Reporta las siguientes calificaciones obtenidas hasta el momento del alumno(a):
                    </div>
                    <div style="display: inline-block; background: #e8ecf2; border: 1.5px solid #1e293b; border-radius: 20px; padding: 3px 25px; margin: 4px 0 6px 0; font-size: 11pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">
                        ${alumnoNombre}
                    </div>
                </div>

                <!-- GRID 2 COLUMNAS (1,3,5 a la izq y 2,4,6 a la der) -->
                <div style="display: flex; justify-content: space-between; gap: 15px; width: 100%;">
                    <div style="width: 50%;">
                        ${renderTablaPreviewPeriodo(periodos[0], false, false)}
                        ${renderTablaPreviewPeriodo(periodos[2], false, false)}
                        ${renderTablaPreviewPeriodo(periodos[4], true, false)}
                    </div>
                    <div style="width: 50%;">
                        ${renderTablaPreviewPeriodo(periodos[1], false, false)}
                        ${renderTablaPreviewPeriodo(periodos[3], false, false)}
                        ${renderTablaPreviewPeriodo(periodos[5], false, true)}
                    </div>
                </div>

                ${signaturesHtml}

                <!-- LEMA INFERIOR -->
                <div style="background: #4a90e2; color: #ffffff; font-weight: bold; font-size: 7.5pt; padding: 3px; margin-top: 10px; border-radius: 2px; text-align: center; letter-spacing: 0.5px;">
                    ¡ Excelencia educativa a su servicio !
                </div>
            </div>
        `;
    }

    function simularBusquedaAlumnoBoleta(query) {
        buscarAlumnosReal(query, 'boleta-alumno-sugerencia', (alumno) => {
            const fullName = `${alumno.nombre} ${alumno.apPaterno} ${alumno.apMaterno || ''}`.trim();
            document.getElementById('boletaAlumnoSearch').value = fullName;
            
            cargarKardexAlumnoReal(alumno.idAlumno, (data) => {
                const al = data.alumno;
                document.getElementById('lbl-boleta-alumno-nombre').innerText = fullName;
                document.getElementById('lbl-boleta-alumno-matricula').innerText = al.numeroControl || al.idAlumno;
                
                const semestre = document.getElementById('boletaSemestreSelect').value;
                document.getElementById('lbl-boleta-semestre-val').innerText = semestre;
                document.getElementById('boleta-tabla-resultados').style.display = 'block';
            });
        });
    }

    function simularBusquedaAlumnoCredencial(query) {
        buscarAlumnosReal(query, 'credencial-alumno-sugerencia', (alumno) => {
            const fullName = `${alumno.nombre} ${alumno.apPaterno} ${alumno.apMaterno || ''}`.trim();
            document.getElementById('credencialAlumnoSearch').value = fullName;
            
            document.getElementById('cred-nombre-val').innerText = fullName.toUpperCase();
            document.getElementById('cred-matricula-val').innerText = alumno.numeroControl || alumno.idAlumno;
            document.getElementById('cred-plan-val').innerText = (alumno.nombreNivelIngreso || alumno.nombreGrupoTexto || 'PLAN GENERAL').toUpperCase();
            document.getElementById('credencial-info-alumno').style.display = 'block';
        });
    }

    function simularBusquedaAlumnoExtraordinario(query) {
        buscarAlumnosReal(query, 'extraordinario-alumno-sugerencia', (alumno) => {
            alumnoExtraordinarioActual = alumno;
            const fullName = `${alumno.nombre} ${alumno.apPaterno} ${alumno.apMaterno || ''}`.trim();
            const elSearch = document.getElementById('extraordinarioAlumnoSearch');
            if (elSearch) elSearch.value = fullName;
            const elLbl = document.getElementById('lbl-extraordinario-nombre');
            if (elLbl) elLbl.innerText = fullName;

            // Si el alumno cuenta con grupo asignado en la BD, auto-seleccionarlo
            const grpVal = alumno.claveGrupo || alumno.grupo || alumno.nombreGrupo || '';
            if (grpVal) {
                const grpInput = document.getElementById('extraordinarioGrupoInput');
                if (grpInput && !grpInput.value) {
                    grpInput.value = grpVal;
                    const matchG = (window.gruposDb || []).find(g => (g.clave || '').toLowerCase() === grpVal.toLowerCase());
                    if (matchG) seleccionarGrupoExtraordinario(matchG);
                }
            }

            actualizarExtraordinarioPreview();
            const infoCard = document.getElementById('extraordinario-info-alumno');
            if (infoCard) infoCard.style.display = 'block';
        });
    }

    function simularFiltrarBoletas() {
        const nombre = document.getElementById('boletaAlumnoSearch').value.trim();
        if (!nombre) {
            Swal.fire({
                icon: 'warning',
                title: 'Campo incompleto',
                text: 'Por favor, busque y seleccione un alumno.',
                confirmButtonColor: '#0284c7'
            });
            return;
        }
        const semestre = document.getElementById('boletaSemestreSelect').value;
        document.getElementById('lbl-boleta-semestre-val').innerText = semestre;
        document.getElementById('boleta-tabla-resultados').style.display = 'block';
    }

    // Modal, impresión y guardado de Kárdex y Boleta oficial
    function mostrarKardexConDatos(data, openModal = true) {
        if (!data || !data.alumno) return;
        const al = data.alumno;
        const periodos = data.periodos || [];

        const isBti = (cctSeleccionado === 'BTI') || (al.id_centroTrabajo === 2 || al.claveCentroTrabajo === '21PCT0073R' || (al.nombreCentroTrabajo && al.nombreCentroTrabajo.toUpperCase().includes('BTI')));

        // Datos de encabezado del Modal
        const nombreCompleto = `${al.apPaterno || ''} ${al.apMaterno || ''} ${al.nombre || ''}`.trim().toUpperCase();
        const elNombre = document.getElementById('kardexNombreAlumno');
        if (elNombre) elNombre.textContent = nombreCompleto || '—';

        const claveCctVal = isBti ? (al.claveCentroTrabajo || '21PCT0073R') : (al.claveCentroTrabajo || '21PBH0353G');
        const elClave = document.getElementById('kardexCCTClave');
        if (elClave) elClave.textContent = `CLAVE CT: ${claveCctVal}`;

        const nombreCctVal = isBti ? 'BACHILLERATO TECNOLÓGICO INTERAMERICANO' : 'BACHILLERATO GENERAL NO ESCOLARIZADO';
        const elCctNombre = document.getElementById('kardexCCTNombre');
        if (elCctNombre) elCctNombre.textContent = nombreCctVal;

        // Renderizar las 6 cajas de periodos (3 por columna: 1,3,5 a la izq y 2,4,6 a la der)
        let htmlPeriodos = '';
        periodos.forEach((p, idx) => {
            let htmlMaterias = '';
            
            (p.materias || []).forEach(m => {
                const isEquiv = m.es_equivalencia === true;
                if (isEquiv) {
                    htmlMaterias += `
                        <tr data-materia-id="${m.idMateria}" data-nivel="${p.idNivel}" data-is-equivalencia="true">
                            <td class="px-2 py-1 align-middle text-uppercase fw-semibold" style="font-size: 0.78rem; border-color: #cbd5e1;">
                                ${m.nombreMateria}
                            </td>
                            <td class="px-1 py-1 text-center align-middle" style="width: 85px; border-color: #cbd5e1;">
                                <span class="badge bg-warning text-dark px-2 py-1">EQUIV.</span>
                            </td>
                        </tr>
                    `;
                } else {
                    const califVal = (m.calificacion !== null && m.calificacion !== undefined) ? m.calificacion : '';
                    htmlMaterias += `
                        <tr data-materia-id="${m.idMateria}" data-nivel="${p.idNivel}">
                            <td class="px-2 py-1 align-middle text-uppercase fw-semibold" style="font-size: 0.78rem; border-color: #cbd5e1;">
                                ${m.nombreMateria}
                            </td>
                            <td class="px-1 py-1 text-center align-middle" style="width: 85px; border-color: #cbd5e1;">
                                <input type="text" maxlength="4" 
                                    class="form-control form-control-sm text-center fw-bold input-calif-kardex" 
                                    data-materia="${m.idMateria}" 
                                    data-nivel="${p.idNivel}" 
                                    data-periodo-idx="${idx}" 
                                    data-field="calificacion" 
                                    value="${califVal}" 
                                    style="height: 28px; font-size: 0.85rem; padding: 2px; background: transparent; border: 1px solid #cbd5e1;"
                                    oninput="this.value = this.value.toUpperCase(); calcularPromediosKardex()">
                            </td>
                        </tr>
                    `;
                }
            });

            const promInicial = (p.promedio !== null && p.promedio !== undefined) ? p.promedio : '—';

            let footerHtml = `
                <tfoot>
                    <tr class="bg-light fw-bold" style="border-color: #333; font-size: 0.78rem;">
                        <td class="text-end px-2 py-1 text-uppercase">PROMEDIO</td>
                        <td class="text-center px-1 py-1 text-primary fw-bold prom-periodo-val" id="promPeriodo_${idx}">${promInicial}</td>
                    </tr>
            `;

            if (idx === 4) { // 5to periodo
                const promGenVal = (data.promedioGeneral !== null && data.promedioGeneral !== undefined) ? data.promedioGeneral : '0.0';
                footerHtml += `
                    <tr class="fw-bold" style="border-color: #333; font-size: 0.8rem; background: #e2e8f0;">
                        <td class="text-end px-2 py-1 text-uppercase">PROMEDIO FINAL</td>
                        <td class="text-center px-1 py-1 fw-bold text-primary" id="kardexPromedioFinal">${promGenVal}</td>
                    </tr>
                `;
            } else if (idx === 5) { // 6to periodo (balance visual)
                footerHtml += `
                    <tr style="border-color: transparent; height: 26px;">
                        <td colspan="2" style="border: none !important; background: transparent;"></td>
                    </tr>
                `;
            }
            footerHtml += `</tfoot>`;

            htmlPeriodos += `
                <div class="col-6" style="width: 50%;">
                    <div class="border rounded-1 shadow-none overflow-hidden bg-white h-100" style="border-color: #000 !important;">
                        <div class="py-1 px-2 fw-bold text-dark text-uppercase bg-light border-bottom" style="font-size: 0.78rem; letter-spacing: 0.5px; border-color: #000 !important;">
                            ${p.nombrePeriodo}
                        </div>
                        <div class="table-responsive mb-0">
                            <table class="table table-bordered table-sm mb-0" style="border-color: #000 !important;">
                                <thead class="table-light">
                                    <tr style="font-size: 0.70rem; border-color: #000;">
                                        <th class="px-2 py-1 text-uppercase text-dark" style="border-color: #000 !important;">MATERIA</th>
                                        <th class="px-1 py-1 text-center text-uppercase text-dark" style="width: 85px; border-color: #000 !important;">${isBti ? 'FINAL' : 'EVALUACIÓN OBTENIDA'}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${htmlMaterias}
                                </tbody>
                                ${footerHtml}
                            </table>
                        </div>
                    </div>
                </div>
            `;
        });

        const contenedor = document.getElementById('contenedorPeriodosKardex');
        if (contenedor) {
            contenedor.innerHTML = htmlPeriodos;
        }
        calcularPromediosKardex();

        if (openModal) {
            const modalEl = document.getElementById('modalKardexAlumno');
            if (modalEl && modalEl.parentElement !== document.body) {
                document.body.appendChild(modalEl);
            }
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
    }

    window.recalcularFilaKardexSemestral = function(inputEl) {
    calcularPromediosKardex();
};

window.calcularPromediosKardex = function() {
    const inputs = document.querySelectorAll('.input-calif-kardex');
    const periodosSums = {};
    const periodosCounts = {};
    let sumGlobal = 0;
    let countGlobal = 0;

    inputs.forEach(inp => {
        const pIdx = inp.dataset.periodoIdx;
        const valStr = inp.value.trim();

        if (!periodosSums[pIdx]) {
            periodosSums[pIdx] = 0;
            periodosCounts[pIdx] = 0;
        }

        if (valStr !== '' && !isNaN(valStr)) {
            const num = parseFloat(valStr);
            periodosSums[pIdx] += num;
            periodosCounts[pIdx]++;
            sumGlobal += num;
            countGlobal++;

            if (num < 6.0) {
                inp.style.setProperty('color', '#dc2626', 'important');
                inp.style.setProperty('font-weight', '700', 'important');
            } else {
                inp.style.setProperty('color', '#1e293b', 'important');
                inp.style.setProperty('font-weight', '700', 'important');
            }
        } else if (valStr.toUpperCase() === 'EQUIV.' || valStr.toUpperCase() === 'EQUIVALENCIA') {
            inp.style.setProperty('color', '#d97706', 'important');
            inp.style.setProperty('font-weight', '700', 'important');
        } else {
            inp.style.setProperty('color', '#1e293b', 'important');
            inp.style.setProperty('font-weight', 'normal', 'important');
        }
    });

    // Actualizar promedios por periodo
    Object.keys(periodosSums).forEach(pIdx => {
        const el = document.getElementById(`promPeriodo_${pIdx}`);
        if (el) {
            if (periodosCounts[pIdx] > 0) {
                const avg = (periodosSums[pIdx] / periodosCounts[pIdx]).toFixed(1);
                el.textContent = avg;
                el.style.setProperty('color', parseFloat(avg) < 6.0 ? '#dc2626' : '#1e6fa8', 'important');
            } else {
                el.textContent = '—';
                el.style.setProperty('color', '#64748b', 'important');
            }
        }
    });

    // Actualizar promedio final (ubicado en el footer del 5to periodo)
    const finalEl = document.getElementById('kardexPromedioFinal');
    if (finalEl) {
        if (countGlobal > 0) {
            const promFinal = (sumGlobal / countGlobal).toFixed(1);
            finalEl.textContent = promFinal;
            finalEl.style.setProperty('color', parseFloat(promFinal) < 6.0 ? '#dc2626' : '#1e6fa8', 'important');
        } else {
            finalEl.textContent = '0.0';
            finalEl.style.setProperty('color', '#1e6fa8', 'important');
        }
    }
};

window.guardarCalificacionesKardex = function() {
    if (!idAlumnoKardexActual) return;
    const btn = document.getElementById('btnGuardarKardex');
    const rows = document.querySelectorAll('#contenedorPeriodosKardex tbody tr');
    const calificaciones = [];

    rows.forEach(tr => {
        const isEquiv = tr.getAttribute('data-is-equivalencia') === 'true';
        if (isEquiv) return;

        const idMateria = tr.getAttribute('data-materia-id');
        const idNivel = tr.getAttribute('data-nivel');
        if (!idMateria) return;

        const finalInp = tr.querySelector('.input-calif-kardex');
        if (!finalInp) return;

        const valStr = finalInp.value.trim();
        let calif = null;
        if (valStr !== '' && !isNaN(valStr)) {
            calif = parseFloat(valStr);
        }

        calificaciones.push({
            idMateria: parseInt(idMateria),
            id_nivel_academico: parseInt(idNivel || finalInp.dataset.nivel),
            calificacion: calif,
            tipoAcreditacion: 'ORDINARIO'
        });
    });

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Guardando...';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    fetch(`/alumnos/${idAlumnoKardexActual}/calificaciones`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ calificaciones: calificaciones })
    })
    .then(async r => {
        const data = await r.json().catch(() => null);
        if (!r.ok) {
            if (r.status === 419) {
                throw new Error('La sesión ha expirado (Error 419). Por favor recarga la página con F5.');
            }
            if (r.status === 401) {
                throw new Error('Tu sesión ha expirado. Por favor inicia sesión nuevamente.');
            }
            throw new Error((data && (data.error || data.message)) || `Error en el servidor (${r.status})`);
        }
        return data;
    })
    .then(resp => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> Guardar Calificaciones';

        if (resp && resp.success) {
            Swal.fire({
                icon: 'success',
                title: 'Kárdex Actualizado',
                text: 'Las calificaciones se han guardado exitosamente.',
                timer: 2000,
                showConfirmButton: false
            });
            if (idAlumnoKardexActual) {
                cargarKardexAlumnoReal(idAlumnoKardexActual, (freshData) => {
                    datosKardexActual = freshData;
                    renderizarKardexPreview(freshData);
                });
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error al Guardar',
                text: (resp && (resp.error || resp.message)) || 'Error al guardar calificaciones'
            });
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> Guardar Calificaciones';
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'Error al Guardar',
            text: err.message || 'Error de comunicación al guardar calificaciones'
        });
    });
};

window.imprimirKardex = function() {
    if (!datosKardexActual || !datosKardexActual.alumno) {
        Swal.fire({
            icon: 'warning',
            title: 'Sin datos de alumno',
            text: 'Por favor, busque y seleccione un alumno primero para generar el kárdex.',
            confirmButtonColor: '#0284c7'
        });
        return;
    }

    const al = datosKardexActual.alumno;
    const isBti = (cctSeleccionado === 'BTI') || (al.id_centroTrabajo === 2 || al.claveCentroTrabajo === '21PCT0073R' || (al.nombreCentroTrabajo && al.nombreCentroTrabajo.toUpperCase().includes('BTI')));

    const alumnoNombre = `${al.apPaterno || ''} ${al.apMaterno || ''} ${al.nombre || ''}`.trim().toUpperCase() || '—';
    const cctClave = isBti 
        ? `CLAVE CT: ${al.claveCentroTrabajo || '21PCT0073R'}` 
        : `CLAVE CT: ${al.claveCentroTrabajo || '21PBH0353G'}`;
    const cctNombre = isBti 
        ? 'BACHILLERATO TECNOLÓGICO INTERAMERICANO' 
        : 'BACHILLERATO GENERAL NO ESCOLARIZADO';

    // Mapear valores editados en inputs si existen en el modal
    const inputsMap = {};
    document.querySelectorAll('.input-calif-kardex').forEach(inp => {
        const matId = inp.dataset.materia;
        const nivId = inp.dataset.nivel;
        if (matId && nivId) {
            inputsMap[`${matId}_${nivId}`] = inp.value.trim();
        }
    });

    const periodos = datosKardexActual.periodos || [];
    const periodosData = [];
    let sumGlobal = 0, countGlobal = 0;

    periodos.forEach((p, idx) => {
        const rows = [];
        let sumPeriodo = 0, countPeriodo = 0;

        (p.materias || []).forEach(m => {
            const isEquiv = m.es_equivalencia === true;
            let califVal = (m.calificacion !== null && m.calificacion !== undefined) ? String(m.calificacion) : '';

            const key = `${m.idMateria}_${p.idNivel}`;
            if (inputsMap[key] !== undefined) {
                califVal = inputsMap[key];
            }

            if (!isEquiv && califVal !== '' && !isNaN(califVal)) {
                const num = parseFloat(califVal);
                sumPeriodo += num;
                countPeriodo++;
                sumGlobal += num;
                countGlobal++;
            }

            rows.push({
                materia: m.nombreMateria,
                isEquiv: isEquiv,
                calificacion: califVal !== '' ? califVal : '—',
                esReprobatoria: !isEquiv && califVal !== '' && !isNaN(califVal) && parseFloat(califVal) < 6.0
            });
        });

        const promPeriodo = countPeriodo > 0 
            ? (sumPeriodo / countPeriodo).toFixed(1) 
            : (p.promedio !== null && p.promedio !== undefined ? String(p.promedio) : '—');

        periodosData.push({
            titulo: p.nombrePeriodo || `PERIODO ${idx + 1}`,
            promedio: promPeriodo,
            promReprobatorio: promPeriodo !== '—' && !isNaN(promPeriodo) && parseFloat(promPeriodo) < 6.0,
            materias: rows
        });
    });

    const promFinal = countGlobal > 0 
        ? (sumGlobal / countGlobal).toFixed(1) 
        : (datosKardexActual.promedioGeneral !== null && datosKardexActual.promedioGeneral !== undefined ? String(datosKardexActual.promedioGeneral) : '—');

    function renderTablaPeriodo(p, is5to, is6to) {
        if (!p) return '';
        let filasHtml = '';
        
        p.materias.forEach(m => {
            if (m.isEquiv) {
                filasHtml += `
                    <tr>
                        <td style="border: 1px solid #000; padding: 2px 4px; font-size: 7.2pt; text-align: left; text-transform: uppercase;">
                            ${m.materia}
                        </td>
                        <td style="border: 1px solid #000; padding: 2px 4px; font-size: 7.2pt; text-align: center; font-weight: bold; color: #d97706;">
                            EQUIV.
                        </td>
                    </tr>
                `;
            } else {
                const styleColor = m.esReprobatoria ? 'color: #dc2626 !important; font-weight: bold;' : 'color: #000;';
                filasHtml += `
                    <tr>
                        <td style="border: 1px solid #000; padding: 2px 4px; font-size: 7.2pt; text-align: left; text-transform: uppercase;">
                            ${m.materia}
                        </td>
                        <td style="border: 1px solid #000; padding: 2px 4px; font-size: 7.8pt; text-align: center; ${styleColor}">
                            ${m.calificacion}
                        </td>
                    </tr>
                `;
            }
        });

        let footerExtra = '';
        if (is5to) {
            const finalColor = promFinal !== '—' && !isNaN(promFinal) && parseFloat(promFinal) < 6.0 ? 'color: #dc2626 !important;' : 'color: #1e6fa8;';
            footerExtra = `
                <tr style="font-weight: bold; background: #e8ecf2;">
                    <td style="border: 1.5px solid #000; padding: 2.2px 4px; font-size: 7.6pt; text-align: right;">
                        PROMEDIO FINAL
                    </td>
                    <td style="border: 1.5px solid #000; padding: 2.2px 4px; font-size: 8.2pt; font-weight: bold; text-align: center; background: #e8ecf2; ${finalColor}">
                        ${promFinal}
                    </td>
                </tr>
            `;
        } else if (is6to) {
            footerExtra = `
                <tr>
                    <td colspan="2" style="border: 1px solid #000; height: 19px; background: #f8fafc;"></td>
                </tr>
            `;
        }

        const promColor = p.promReprobatorio ? 'color: #dc2626 !important;' : 'color: #000;';

        return `
            <div style="margin-bottom: 6px;">
                <div style="font-size: 8pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; color: #000;">
                    ${p.titulo}
                </div>
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; font-family: Arial, sans-serif;">
                    <thead>
                        <tr style="background: #ffffff;">
                            <th style="border: 1px solid #000; padding: 2.2px 4px; font-size: 7.4pt; text-align: left; font-weight: bold; width: 280px;">MATERIA</th>
                            <th style="border: 1px solid #000; padding: 2.2px 2px; font-size: 7pt; text-align: center; font-weight: bold; width: 70px; line-height: 1.1;">${isBti ? 'CALIFICACIÓN<br>FINAL' : 'EVALUACIÓN<br>OBTENIDA'}</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${filasHtml}
                        <tr>
                            <td style="border: 1px solid #000; padding: 2px 4px; font-size: 7.6pt; font-weight: bold; text-align: right;">
                                PROMEDIO
                            </td>
                            <td style="border: 1px solid #000; padding: 2px 4px; font-size: 8pt; font-weight: bold; text-align: center; ${promColor}">
                                ${p.promedio}
                            </td>
                        </tr>
                        ${footerExtra}
                    </tbody>
                </table>
            </div>
        `;
    }

    const pageContainerWidth = '720px';
    const pageColWidth = '350px';
    const pageSize = 'letter portrait';

    const colIzqHtml = `
        <div style="width: ${pageColWidth}; flex-shrink: 0;">
            ${renderTablaPeriodo(periodosData[0], false, false)}
            ${renderTablaPeriodo(periodosData[2], false, false)}
            ${renderTablaPeriodo(periodosData[4], true, false)}
        </div>
    `;

    const colDerHtml = `
        <div style="width: ${pageColWidth}; flex-shrink: 0;">
            ${renderTablaPeriodo(periodosData[1], false, false)}
            ${renderTablaPeriodo(periodosData[3], false, false)}
            ${renderTablaPeriodo(periodosData[5], false, true)}
        </div>
    `;

    const fechaHoy = new Date();
    const meses = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
    const fechaTexto = `TEZIUTLÁN PUEBLA A ${fechaHoy.getDate()} DE ${meses[fechaHoy.getMonth()]} DE ${fechaHoy.getFullYear()}`;

    let signaturesHtml = '';
    if (isBti) {
        signaturesHtml = `
            <div style="margin-top: 15px; display: flex; justify-content: space-between; align-items: flex-end; width: ${pageContainerWidth}; font-family: Arial, sans-serif;">
                <div style="width: 280px; text-align: center;">
                    <div style="border-bottom: 1px solid #000; width: 200px; margin: 0 auto 5px auto; height: 30px;"></div>
                    <div style="font-size: 7.8pt; font-weight: bold;">ING. FAUSTO LEYVA FLORES</div>
                    <div style="font-size: 7.2pt; color: #444; text-transform: uppercase;">DIRECTOR</div>
                </div>
                <div style="width: 280px; text-align: center; font-size: 8pt; font-weight: bold; padding-bottom: 10px;">
                    ${fechaTexto}
                </div>
            </div>
        `;
    }

    const win = window.open('', '', 'height=850,width=850');
    if (!win) {
        Swal.fire({
            icon: 'warning',
            title: 'Ventana emergente bloqueada',
            text: 'Por favor, habilite las ventanas emergentes en su navegador para imprimir el kárdex.',
            confirmButtonColor: '#0284c7'
        });
        return;
    }

    win.document.write(`
        <html>
            <head>
                <title>Kárdex de Calificaciones - ${alumnoNombre}</title>
                <style>
                    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; box-sizing: border-box; }
                    @page {
                        size: ${pageSize};
                        margin: 5mm 8mm 4mm 8mm;
                    }
                    html, body {
                        font-family: Arial, Helvetica, sans-serif;
                        background: #fff;
                        color: #000;
                        padding: 0;
                        margin: 0;
                        height: 100%;
                    }
                    .kardex-hoja {
                        width: ${pageContainerWidth};
                        margin: 0 auto;
                        text-align: center;
                    }
                </style>
            </head>
            <body>
                <div class="kardex-hoja">
                    <!-- MEMBRETE OFICIAL -->
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <div style="width: 75px; text-align: left;">
                            <img src="/img/logo.png" alt="Logo" style="height: 58px; object-fit: contain;">
                        </div>
                        <div style="width: 635px; border: 1.5px solid #10599a; overflow: hidden; border-radius: 3px;">
                            <div style="background: #10599a; color: #ffffff; font-weight: bold; font-size: 11.5pt; padding: 2.5px 6px; text-align: center; letter-spacing: 0.8px;">
                                BACHILLERATO INTERAMERICANO
                            </div>
                            <div style="background: #d4ebf9; color: #000000; font-size: 7.2pt; padding: 2px 6px; text-align: center; line-height: 1.35;">
                                <div>Avenida Benito Juárez 901, Colonia Centro Teziutlán Puebla. Tel: 231-3123979</div>
                                <div style="font-weight: bold;">${cctClave}</div>
                            </div>
                        </div>
                    </div>

                    <!-- TEXTO DIRECCIÓN Y NOMBRE ALUMNO -->
                    <div style="margin-bottom: 6px;">
                        <div style="font-size: 8.5pt; color: #000;">
                            La Dirección de la escuela <strong>${cctNombre}</strong>
                        </div>
                        <div style="font-size: 7.8pt; color: #333; font-style: italic;">
                            Reporta las siguientes calificaciones obtenidas hasta el momento del alumno(a):
                        </div>
                        <div style="display: inline-block; background: #e8ecf2; border: 1.5px solid #1e293b; border-radius: 20px; padding: 2px 25px; margin: 3px 0 6px 0; font-size: 11pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">
                            ${alumnoNombre}
                        </div>
                    </div>

                    <!-- TABLAS DE LOS 6 PERIODOS (2 COLUMNAS) -->
                    <div style="display: flex; justify-content: space-between; width: ${pageContainerWidth}; margin: 0 auto; text-align: left;">
                        ${colIzqHtml}
                        ${colDerHtml}
                    </div>

                    ${signaturesHtml}

                    <!-- LEMA INFERIOR -->
                    <div style="width: ${pageContainerWidth}; margin: 6px auto 0 auto; background: #4a90e2; color: #ffffff; font-weight: bold; font-size: 7.8pt; padding: 2.5px 0; text-align: center; border-radius: 2px; letter-spacing: 0.5px;">
                        ¡ Excelencia educativa a su servicio !
                    </div>
                </div>
            </body>
        </html>
    `);
    win.document.close();
    win.focus();
    setTimeout(() => {
        win.print();
    }, 450);
};

    window.imprimirBoletaBTISemestre = function(data, idNivelSemestre, reportesCounts) {
        const al = data.alumno;
        const repCounts = reportesCounts || { 1: 0, 2: 0, 3: 0 };
        const totalReportes = (repCounts[1] || 0) + (repCounts[2] || 0) + (repCounts[3] || 0);
        let boxReportesHtml = '';
        if (totalReportes > 0) {
            boxReportesHtml = `
                <!-- Box Reportes de Indisciplina -->
                <div style="border: 1.5px solid #000; background: #ffffff; width: 235px; border-radius: 4px; overflow: hidden; display: flex; flex-direction: column; align-items: center; text-align: center; margin-top: -10px;">
                    <span style="font-size: 6.8pt; font-weight: 800; background: #2e596b; color: #ffffff; width: 100%; padding: 4px 0; text-transform: uppercase; display: block; letter-spacing: 0.5px;">REPORTES POR CONDUCTA</span>
                    <span style="font-size: 8pt; font-weight: 800; padding: 8px 5px; color: #0f172a; display: block; word-spacing: 3px;">
                        P1: <strong style="color: #1e6fa8; font-size: 9pt;">${repCounts[1] || 0}</strong> &nbsp;|&nbsp; 
                        P2: <strong style="color: #1e6fa8; font-size: 9pt;">${repCounts[2] || 0}</strong> &nbsp;|&nbsp; 
                        P3: <strong style="color: #1e6fa8; font-size: 9pt;">${repCounts[3] || 0}</strong>
                    </span>
                </div>
            `;
        }
        const periodos = data.periodos || [];
        const p = periodos.find(x => x.idNivel === idNivelSemestre);
        const materias = p ? p.materias || [] : [];

        const semestresNombres = {
            7: 'PRIMER',
            8: 'SEGUNDO',
            9: 'TERCER',
            10: 'CUARTO',
            11: 'QUINTO',
            12: 'SEXTO'
        };
        const semestreNombreLargo = semestresNombres[idNivelSemestre] || 'SEMESTRE';

        let filasMateriasHtml = '';
        let p1Vals = []; let p2Vals = []; let p3Vals = []; let semVals = []; let extVals = []; let finalVals = [];
        let totalAsist = 0; let totalTotAsist = 0;

        materias.forEach(m => {
            const isEquiv = m.es_equivalencia === true;
            if (isEquiv) {
                filasMateriasHtml += `
                    <tr style="text-align: center; height: 32px;">
                        <td style="border: 1px solid #000; padding: 6px; text-align: left; text-transform: uppercase; font-weight: bold; font-size: 8.5pt;">${m.nombreMateria}</td>
                        <td colspan="5" style="border: 1px solid #000; padding: 6px; font-weight: bold; color: #d97706; font-size: 8.5pt;">EQUIVALENCIA</td>
                        <td style="border: 1px solid #000; padding: 6px; font-weight: bold; font-size: 8.5pt;">—</td>
                    </tr>
                `;
                return;
            }

            const p1 = m.parcial1 !== null ? parseFloat(m.parcial1) : null;
            const p2 = m.parcial2 !== null ? parseFloat(m.parcial2) : null;
            const p3 = m.parcial3 !== null ? parseFloat(m.parcial3) : null;
            const sem = m.semestral !== null ? parseFloat(m.semestral) : null;
            const ext = m.extraordinario !== null ? parseFloat(m.extraordinario) : null;
            const finalVal = m.calificacion !== null ? parseFloat(m.calificacion) : null;

            const parseVal = (v, arr) => { if (v !== null) arr.push(v); };
            parseVal(p1, p1Vals);
            parseVal(p2, p2Vals);
            parseVal(p3, p3Vals);
            parseVal(sem, semVals);
            parseVal(ext, extVals);
            parseVal(finalVal, finalVals);

            if (m.asistencias !== null) totalAsist += parseInt(m.asistencias) || 0;
            if (m.total_asistencias !== null) totalTotAsist += parseInt(m.total_asistencias) || 0;

            const p1Text = p1 !== null ? p1.toFixed(1) : '—';
            const p2Text = p2 !== null ? p2.toFixed(1) : '—';
            const p3Text = p3 !== null ? p3.toFixed(1) : '—';
            const semText = sem !== null ? sem.toFixed(1) : '—';
            const extText = ext !== null ? ext.toFixed(1) : '0.0';
            
            const extStyle = ext !== null && ext > 0 ? 'color: #dc2626; font-weight: bold;' : 'color: #000;';
            const finalStyle = finalVal !== null && finalVal < 6.0 ? 'color: #dc2626; font-weight: bold;' : 'color: #000;';
            const finalText = finalVal !== null ? finalVal.toFixed(1) : '—';

            filasMateriasHtml += `
                <tr style="text-align: center; height: 32px;">
                    <td style="border: 1px solid #000; padding: 6px 8px; text-align: left; text-transform: uppercase; font-weight: 700; font-size: 8.5pt;">${m.nombreMateria}</td>
                    <td style="border: 1px solid #000; padding: 6px; font-size: 9pt;">${p1Text}</td>
                    <td style="border: 1px solid #000; padding: 6px; font-size: 9pt;">${p2Text}</td>
                    <td style="border: 1px solid #000; padding: 6px; font-size: 9pt;">${p3Text}</td>
                    <td style="border: 1px solid #000; padding: 6px; font-size: 9pt;">${semText}</td>
                    <td style="border: 1px solid #000; padding: 6px; font-size: 9pt; ${extStyle}">${extText}</td>
                    <td style="border: 1px solid #000; padding: 6px; font-size: 9pt; background: #f8fafc; ${finalStyle}">${finalText}</td>
                </tr>
            `;
        });

        const getAvg = (arr) => arr.length > 0 ? (arr.reduce((a, b) => a + b, 0) / arr.length).toFixed(1) : '—';

        const p1Avg = getAvg(p1Vals);
        const p2Avg = getAvg(p2Vals);
        const p3Avg = getAvg(p3Vals);
        const semAvg = getAvg(semVals);
        const extAvg = getAvg(extVals);
        const finalAvg = getAvg(finalVals);

        const totalAsistVal = totalAsist;
        const totalTotAsistVal = totalTotAsist;

        const win = window.open('', '', 'height=850,width=1100');
        win.document.write(`
            <html>
                <head>
                    <title>Boleta de Calificaciones - ${semestreNombreLargo} Semestre - ${al.nombre}</title>
                    <style>
                        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; box-sizing: border-box; }
                        @page {
                            size: letter portrait;
                            margin: 10mm 12mm;
                        }
                        html, body {
                            font-family: Arial, Helvetica, sans-serif;
                            background: #fff;
                            color: #000;
                            padding: 0;
                            margin: 0;
                        }
                        .boleta-container {
                            width: 100%;
                            max-width: 800px;
                            margin: 0 auto;
                        }
                    </style>
                </head>
                <body>
                    <div class="boleta-container">
                        
                        <!-- Header -->
                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #0f172a; padding-bottom: 12px; margin-bottom: 20px;">
                            <div style="width: 100px; text-align: left;">
                                <img src="/img/logo.png" style="height: 60px; width: auto; object-fit: contain;">
                            </div>
                            <div style="text-align: center; flex: 1;">
                                <div style="font-size: 10pt; font-weight: bold; color: #000; letter-spacing: 0.5px; text-transform: uppercase;">Dirección General de Educación Tecnológica Industrial y de Servicios</div>
                                <div style="font-size: 9pt; font-weight: 700; color: #334155; margin-top: 3px; text-transform: uppercase;">Educación Media Superior</div>
                                <div style="font-size: 11pt; font-weight: 800; color: #0f172a; margin-top: 6px; text-transform: uppercase;">BOLETA DE CALIFICACIONES DEL ${semestreNombreLargo} SEMESTRE</div>
                                <div style="font-size: 8.5pt; font-weight: bold; color: #475569; margin-top: 3px; text-transform: uppercase;">Ciclo Escolar 2025-2026</div>
                            </div>
                            <div style="width: 100px; text-align: right;">
                                <img src="/img/logo.png" style="height: 60px; width: auto; object-fit: contain;">
                            </div>
                        </div>

                        <!-- Details Row 1 -->
                        <div style="display: flex; gap: 30px; font-size: 8.5pt; margin-bottom: 12px;">
                            <div style="flex: 2; display: flex; flex-direction: column;">
                                <div style="display: flex; align-items: flex-end; margin-bottom: 2px;">
                                    <span style="font-weight: bold; color: #475569; width: 150px; text-transform: uppercase;">DATOS DEL ALUMNO (A):</span>
                                    <span style="flex: 1; border-bottom: 1.2px solid #000; font-weight: 700; font-size: 9.5pt; text-align: center; padding-bottom: 2px; text-transform: uppercase; letter-spacing: 0.5px;">
                                        ${al.apPaterno || ''} ${al.apMaterno || ''} ${al.nombre || ''}
                                    </span>
                                </div>
                                <div style="display: flex; justify-content: space-around; font-size: 7.2pt; color: #64748b; padding-left: 150px; margin-top: 1px;">
                                    <span>Apellido Paterno</span>
                                    <span>Apellido Materno</span>
                                    <span>Nombre</span>
                                </div>
                            </div>
                        </div>

                        <!-- Details Row 2 -->
                        <div style="display: flex; gap: 20px; font-size: 8.5pt; margin-bottom: 20px; align-items: flex-end;">
                            <div style="flex: 2; display: flex; align-items: flex-end;">
                                <span style="font-weight: bold; color: #475569; width: 150px; text-transform: uppercase;">DATOS DE LA ESCUELA:</span>
                                <span style="flex: 1; border-bottom: 1.2px solid #000; font-weight: 700; text-align: center; padding-bottom: 2px; text-transform: uppercase; font-size: 9pt;">
                                    ${al.nombreCentroTrabajo || 'BACHILLERATO TECNOLÓGICO INTERAMERICANO'}
                                </span>
                            </div>
                            <div style="display: flex; gap: 15px; font-size: 8pt; text-align: center;">
                                <div style="display: flex; flex-direction: column; width: 60px;">
                                    <span style="font-weight: 700; border-bottom: 1px solid #000; padding-bottom: 2px; text-transform: uppercase;">${al.nombreGrupoTexto || '—'}</span>
                                    <span style="font-size: 7pt; color: #475569; font-weight: bold; margin-top: 2px;">GRUPO</span>
                                </div>
                                <div style="display: flex; flex-direction: column; width: 90px;">
                                    <span style="font-weight: 700; border-bottom: 1px solid #000; padding-bottom: 2px; text-transform: uppercase;">${al.modalidadHorario || 'MATUTINO'}</span>
                                    <span style="font-size: 7pt; color: #475569; font-weight: bold; margin-top: 2px;">TURNO</span>
                                </div>
                                <div style="display: flex; flex-direction: column; width: 90px;">
                                    <span style="font-weight: 700; border-bottom: 1px solid #000; padding-bottom: 2px; text-transform: uppercase;">${al.claveCentroTrabajo || '21PCT0073R'}</span>
                                    <span style="font-size: 7pt; color: #475569; font-weight: bold; margin-top: 2px;">CCT</span>
                                </div>
                            </div>
                        </div>

                        <!-- Grades Table and Info blocks -->
                        <div style="display: flex; gap: 20px; align-items: flex-start; justify-content: space-between; margin-bottom: 25px;">
                            
                            <!-- Main Grades Table -->
                            <div style="flex: 1;">
                                <table style="width: 100%; border-collapse: collapse; border: 1.5px solid #000; font-size: 8pt;">
                                    <thead>
                                        <tr style="background: #e2e8f0; color: #000; text-align: center; border-bottom: 1.5px solid #000;">
                                            <th rowspan="2" style="border: 1px solid #000; padding: 6px; text-align: left; width: 220px; font-size: 7.8pt; text-transform: uppercase; font-weight: 800;">ASIGNATURAS / ÁREAS</th>
                                            <th colspan="4" style="border: 1px solid #000; padding: 4px; font-size: 7.8pt; font-weight: 800;">PERIODOS DE EVALUACIÓN ORDINARIA</th>
                                            <th rowspan="2" style="border: 1px solid #000; padding: 6px; width: 50px; font-size: 7.2pt; font-weight: 800; border-left: 1.5px solid #000;">EXTRAORDINARIO</th>
                                            <th rowspan="2" style="border: 1px solid #000; padding: 6px; width: 65px; font-size: 7.2pt; font-weight: 800; border-left: 1.5px solid #000;">PROMEDIO FINAL/<br>MATERIA</th>
                                        </tr>
                                        <tr style="background: #f8fafc; color: #000; text-align: center; font-size: 7.2pt; border-bottom: 1.5px solid #000;">
                                            <th style="border: 1px solid #000; padding: 4px; width: 45px;">1ER. PARCIAL</th>
                                            <th style="border: 1px solid #000; padding: 4px; width: 45px;">2DO. PARCIAL</th>
                                            <th style="border: 1px solid #000; padding: 4px; width: 45px;">3ER. PARCIAL</th>
                                            <th style="border: 1px solid #000; padding: 4px; width: 55px;">SEMESTRAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${filasMateriasHtml}
                                        <tr style="font-weight: bold; background: #e2e8f0; text-align: center; font-size: 8pt; border-top: 1.5px solid #000; height: 32px;">
                                            <td style="border: 1px solid #000; padding: 6px; text-align: right; text-transform: uppercase; font-weight: 800;">PROMEDIO</td>
                                            <td style="border: 1px solid #000; padding: 5px;">${p1Avg}</td>
                                            <td style="border: 1px solid #000; padding: 5px;">${p2Avg}</td>
                                            <td style="border: 1px solid #000; padding: 5px;">${p3Avg}</td>
                                            <td style="border: 1px solid #000; padding: 5px;">${semAvg}</td>
                                            <td style="border: 1px solid #000; padding: 5px; color: #dc2626; border-left: 1.5px solid #000;">${extAvg}</td>
                                            <td style="border: 1px solid #000; padding: 5px; background: #cbd5e1; color: #1e293b; border-left: 1.5px solid #000;">${finalAvg}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Sidebar details -->
                            <div style="width: 250px; display: flex; flex-direction: column; gap: 25px; align-items: center;">
                                
                                <!-- Badges -->
                                <div style="display: flex; gap: 15px; width: 100%; justify-content: center;">
                                    <div style="border: 1.5px solid #000; background: #ffffff; width: 110px; border-radius: 4px; overflow: hidden; display: flex; flex-direction: column; align-items: center; text-align: center;">
                                        <span style="font-size: 6.8pt; font-weight: 800; background: #2e596b; color: #ffffff; width: 100%; padding: 4px 0; text-transform: uppercase; display: block;">ASISTENCIAS</span>
                                        <span style="font-size: 11pt; font-weight: 800; padding: 12px 5px; color: #0f172a; display: block;">${totalAsistVal} / ${totalTotAsistVal}</span>
                                    </div>
                                    <div style="border: 1.5px solid #000; background: #ffffff; width: 110px; border-radius: 4px; overflow: hidden; display: flex; flex-direction: column; align-items: center; text-align: center;">
                                        <span style="font-size: 6.8pt; font-weight: 800; background: #2e596b; color: #ffffff; width: 100%; padding: 4px 0; text-transform: uppercase; display: block; line-height: 1.1;">PROMEDIO FINAL<br>DEL SEMESTRE</span>
                                        <span style="font-size: 13pt; font-weight: 900; padding: 10px 5px; color: #1e3a8a; display: block;">${finalAvg}</span>
                                    </div>
                                </div>

                                ${boxReportesHtml}

                                <!-- Director Signature -->
                                <div style="margin-top: 15px; width: 100%; text-align: center;">
                                    <div style="border-bottom: 1px solid #000; width: 160px; margin: 0 auto 5px auto; height: 45px;"></div>
                                    <div style="font-size: 7.8pt; font-weight: 800; text-transform: uppercase; color: #0f172a;">ING. FAUSTO LEYVA FLORES</div>
                                    <div style="font-size: 7.2pt; font-weight: 700; color: #475569; text-transform: uppercase; margin-top: 1px;">DIRECTOR</div>
                                </div>

                                <!-- Date info -->
                                <div style="font-size: 7.5pt; font-weight: bold; color: #1e293b; margin-top: 10px; text-transform: uppercase; text-align: center; border: 1px dashed #cbd5e1; padding: 4px 8px; border-radius: 4px;">
                                    TEZIUTLÁN PUEBLA A ${new Date().toLocaleDateString('es-MX', {day: 'numeric', month: 'long', year: 'numeric'}).toUpperCase()}
                                </div>

                            </div>
                        </div>

                        <!-- Recommendations Table -->
                        <div style="margin-top: 25px;">
                            <table style="width: 100%; border-collapse: collapse; border: 1.5px solid #000; font-size: 8pt;">
                                <thead>
                                    <tr style="background: #2e596b; color: #ffffff; font-weight: 800; text-transform: uppercase; text-align: center;">
                                        <th style="border: 1px solid #000; padding: 6px; font-size: 7.8pt; letter-spacing: 0.5px;">OBSERVACIONES O RECOMENDACIONES DE LA DOCENTE O DEL DOCENTE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="border: 1px solid #000; height: 40px;"></td>
                                    </tr>
                                </tbody>
                            </table>
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
        }, 400);
    };

    function promptBoletaSemestreDespuesKardex() {
        if (!datosKardexActual) {
            Swal.fire({
                icon: 'warning',
                title: 'No hay datos cargados',
                text: 'Por favor, busque un alumno primero.',
                confirmButtonColor: '#0284c7'
            });
            return;
        }
        
        Swal.fire({
            title: 'Seleccionar Semestre',
            text: 'Seleccione el semestre (del 1 al 6) para generar la boleta:',
            input: 'select',
            inputOptions: {
                '7': '1° Semestre',
                '8': '2° Semestre',
                '9': '3° Semestre',
                '10': '4° Semestre',
                '11': '5° Semestre',
                '12': '6° Semestre'
            },
            inputPlaceholder: 'Seleccione semestre...',
            showCancelButton: true,
            confirmButtonColor: 'rgb(38, 104, 123)',
            cancelButtonColor: '#cbd5e1',
            confirmButtonText: 'Generar Boleta',
            cancelButtonText: 'Cancelar',
            inputValidator: (value) => {
                if (!value) {
                     return 'Debe seleccionar un semestre';
                }
            }
        }).then((semResult) => {
            if (semResult.isConfirmed) {
                const idNivelSemestre = parseInt(semResult.value);
                const alId = datosKardexActual.alumno.idAlumno;
                Swal.fire({
                    title: 'Preparando boleta...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/alumnos/${alId}/reportes-conteo`)
                    .then(r => r.json())
                    .then(resp => {
                        Swal.close();
                        const counts = resp.success && resp.counts ? resp.counts : { 1: 0, 2: 0, 3: 0 };
                        imprimirBoletaBTISemestre(datosKardexActual, idNivelSemestre, counts);
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.close();
                        imprimirBoletaBTISemestre(datosKardexActual, idNivelSemestre, { 1: 0, 2: 0, 3: 0 });
                    });
            }
        });
    }

    function generarBoletaBTIDesdeSelect() {
        if (!datosKardexActual) {
            Swal.fire({
                icon: 'warning',
                title: 'No hay datos cargados',
                text: 'Por favor, busque un alumno primero.',
                confirmButtonColor: '#0284c7'
            });
            return;
        }
        const select = document.getElementById('boletaSemestreSelect');
        const semVal = select.value;
        
        const mapping = {
            "1er Semestre": 7,
            "2° Semestre": 8,
            "3er Semestre": 9,
            "4° Semestre": 10,
            "5° Semestre": 11,
            "6° Semestre": 12
        };
        const idNivel = mapping[semVal] || 9;

        const alId = datosKardexActual.alumno.idAlumno;
        Swal.fire({
            title: 'Preparando boleta...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(`/alumnos/${alId}/reportes-conteo`)
            .then(r => r.json())
            .then(resp => {
                Swal.close();
                const counts = resp.success && resp.counts ? resp.counts : { 1: 0, 2: 0, 3: 0 };
                imprimirBoletaBTISemestre(datosKardexActual, idNivel, counts);
            })
            .catch(err => {
                console.error(err);
                Swal.close();
                imprimirBoletaBTISemestre(datosKardexActual, idNivel, { 1: 0, 2: 0, 3: 0 });
            });
    }

    function mostrarModalKardexManual() {
        if (!datosKardexActual) {
            Swal.fire({
                icon: 'warning',
                title: 'No hay datos cargados',
                text: 'Por favor, busque un alumno primero.',
                confirmButtonColor: '#0284c7'
            });
            return;
        }
        mostrarKardexConDatos(datosKardexActual);
    }

    // ==========================================
    // AUTOCOMPLETE Y FILTRADO - FORMATO EXTRAORDINARIO
    // ==========================================

    function buscarGruposExtraordinario(query = '') {
        const div = document.getElementById('extraordinario-grupo-sugerencia');
        if (!div) return;

        const grupos = window.gruposDb || [];
        const normQ = (query || '').normalize("NFD").replace(/[̀-ͯ]/g, "").toLowerCase().trim();
        const targetCentroId = cctSeleccionado === 'BTI' ? 2 : (cctSeleccionado === 'BGNE' ? 3 : null);

        let filtered = grupos.filter(g => {
            if (targetCentroId) {
                const cid = g.id_centroTrabajo ?? g.idCentroTrabajo ?? g.id_centro_trabajo;
                if (cid && cid != targetCentroId) return false;
            }
            if (!normQ) return true;
            const claveNorm = (g.clave || '').normalize("NFD").replace(/[̀-ͯ]/g, "").toLowerCase();
            const modNorm = (g.modalidadHorario || '').normalize("NFD").replace(/[̀-ͯ]/g, "").toLowerCase();
            return claveNorm.includes(normQ) || modNorm.includes(normQ);
        });

        div.innerHTML = '';
        div.style.display = 'block';

        if (filtered.length === 0) {
            div.innerHTML = '<div class="p-2 text-muted fs-8 text-center">No se encontraron grupos</div>';
            return;
        }

        filtered.slice(0, 15).forEach(g => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'list-group-item list-group-item-action py-2 text-start d-flex justify-content-between align-items-center';
            btn.style.borderLeft = '3px solid #0284c7';

            const cctName = (g.id_centroTrabajo == 2) ? 'BTI' : ((g.id_centroTrabajo == 3) ? 'BGNE' : '');
            btn.innerHTML = `
                <div>
                    <span class="fw-bold d-block text-slate-800 fs-8">${g.clave}</span>
                    <small class="text-muted fs-9">${g.modalidadHorario || 'General'}</small>
                </div>
                ${cctName ? `<span class="badge bg-primary-subtle text-primary fs-9">${cctName}</span>` : ''}
            `;

            btn.onclick = (e) => {
                e.preventDefault();
                seleccionarGrupoExtraordinario(g);
                div.style.display = 'none';
            };
            div.appendChild(btn);
        });
    }

    function seleccionarGrupoExtraordinario(grupo) {
        const input = document.getElementById('extraordinarioGrupoInput');
        if (input) input.value = grupo.clave;

        // Auto-seleccionar semestre en base al nivel académico del grupo
        if (grupo.id_nivel_academico) {
            const isBti = (grupo.id_centroTrabajo == 2) || (cctSeleccionado === 'BTI');
            let semNum = isBti ? (grupo.id_nivel_academico - 6) : grupo.id_nivel_academico;
            if (semNum >= 1 && semNum <= 6) {
                const semSelect = document.getElementById('extraordinarioSemestreSelect');
                if (semSelect) {
                    semSelect.value = String(semNum);
                    onExtraordinarioSemestreChange();
                }
            }
        }

        // Sugerir docente si hay asignaciones en tb_horarios
        if (window.horariosDb && window.horariosDb.length > 0) {
            const asignacion = window.horariosDb.find(h => h.id_grupo == grupo.id);
            if (asignacion && asignacion.id_docente) {
                const docInput = document.getElementById('extraordinarioDocenteInput');
                if (docInput && !docInput.value.trim()) {
                    const doc = (window.docentesDb || []).find(d => (d.idDocente || d.id) == asignacion.id_docente);
                    if (doc) {
                        const nom = `${doc.nombreDocente || doc.nombre || ''} ${doc.apPaternoDocente || doc.apPaterno || ''} ${doc.apMaternoDocente || doc.apMaterno || ''}`.trim();
                        docInput.value = nom;
                    }
                }
            }
        }

        actualizarExtraordinarioPreview();
    }

    function onExtraordinarioSemestreChange() {
        const semSelect = document.getElementById('extraordinarioSemestreSelect');
        const semVal = semSelect ? semSelect.value : '';

        // Si la materia actual no pertenece al nuevo semestre seleccionado, limpiarla
        const matInput = document.getElementById('extraordinarioMateriaInput');
        if (matInput && matInput.value.trim() && semVal) {
            const materias = obtenerMateriasFiltradasExtraordinario();
            const coincide = materias.some(m => (m.nombreMateria || '').toLowerCase().trim() === matInput.value.toLowerCase().trim());
            if (!coincide) {
                matInput.value = '';
            }
        }

        actualizarExtraordinarioPreview();
    }

    function obtenerMateriasFiltradasExtraordinario(query = '') {
        const materias = window.materiasDb || [];
        const semSelect = document.getElementById('extraordinarioSemestreSelect');
        const semVal = semSelect ? semSelect.value : '';
        const normQ = (query || '').normalize("NFD").replace(/[̀-ͯ]/g, "").toLowerCase().trim();

        const targetCentroId = cctSeleccionado === 'BTI' ? 2 : (cctSeleccionado === 'BGNE' ? 3 : null);

        return materias.filter(m => {
            if (targetCentroId) {
                const cid = m.idCentroTrabajo ?? m.id_centro_trabajo ?? m.id_centroTrabajo;
                if (cid && cid != targetCentroId) return false;
            }

            if (semVal) {
                const sNum = parseInt(semVal);
                const nivel = parseInt(m.id_nivel_academico);
                if (cctSeleccionado === 'BTI') {
                    if (nivel !== (sNum + 6) && nivel !== sNum) return false;
                } else if (cctSeleccionado === 'BGNE') {
                    if (nivel !== sNum) return false;
                } else {
                    if (nivel !== sNum && nivel !== (sNum + 6)) return false;
                }
            }

            if (!normQ) return true;
            const nomNorm = (m.nombreMateria || '').normalize("NFD").replace(/[̀-ͯ]/g, "").toLowerCase();
            const claveNorm = (m.clave || '').normalize("NFD").replace(/[̀-ͯ]/g, "").toLowerCase();
            return nomNorm.includes(normQ) || claveNorm.includes(normQ);
        });
    }

    function buscarMateriasExtraordinario(query = '') {
        const div = document.getElementById('extraordinario-materia-sugerencia');
        if (!div) return;

        // Si materiasDb está vacío, intentar cargar diferido
        if (!window.materiasDb || window.materiasDb.length === 0) {
            fetch('/materias/lista?limit=1000')
                .then(r => r.json())
                .then(res => {
                    if (res && res.data) {
                        window.materiasDb = res.data;
                        buscarMateriasExtraordinario(query);
                    }
                }).catch(() => {});
        }

        const semSelect = document.getElementById('extraordinarioSemestreSelect');
        const semVal = semSelect ? semSelect.value : '';
        const filtered = obtenerMateriasFiltradasExtraordinario(query);

        div.innerHTML = '';
        div.style.display = 'block';

        if (!semVal && filtered.length > 0) {
            const notice = document.createElement('div');
            notice.className = 'p-2 bg-light text-muted fs-9 border-bottom text-center';
            notice.innerHTML = '<i class="fa-solid fa-info-circle me-1 text-info"></i> Selecciona un semestre arriba para desglosar materias de ese periodo';
            div.appendChild(notice);
        }

        if (filtered.length === 0) {
            div.innerHTML = `<div class="p-3 text-muted fs-8 text-center">${semVal ? 'No hay materias para este semestre' : 'No se encontraron materias'}</div>`;
            return;
        }

        filtered.slice(0, 20).forEach(m => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'list-group-item list-group-item-action py-2 text-start d-flex justify-content-between align-items-center';
            btn.style.borderLeft = '3px solid #0284c7';

            let semBadge = '';
            if (m.id_nivel_academico) {
                const num = m.id_nivel_academico >= 7 ? (m.id_nivel_academico - 6) : m.id_nivel_academico;
                semBadge = `${num}º Sem/Trim`;
            }

            btn.innerHTML = `
                <div>
                    <span class="fw-semibold d-block text-slate-800 fs-8">${m.nombreMateria}</span>
                    ${m.clave ? `<small class="text-muted fs-9">Clave: ${m.clave}</small>` : ''}
                </div>
                ${semBadge ? `<span class="badge bg-light text-secondary border fs-9">${semBadge}</span>` : ''}
            `;

            btn.onclick = (e) => {
                e.preventDefault();
                seleccionarMateriaExtraordinario(m);
                div.style.display = 'none';
            };
            div.appendChild(btn);
        });
    }

    function seleccionarMateriaExtraordinario(materia) {
        const input = document.getElementById('extraordinarioMateriaInput');
        if (input) input.value = materia.nombreMateria;

        // Auto-seleccionar semestre si no estaba seleccionado
        if (materia.id_nivel_academico) {
            const semSelect = document.getElementById('extraordinarioSemestreSelect');
            if (semSelect && !semSelect.value) {
                const num = materia.id_nivel_academico >= 7 ? (materia.id_nivel_academico - 6) : materia.id_nivel_academico;
                if (num >= 1 && num <= 6) {
                    semSelect.value = String(num);
                    onExtraordinarioSemestreChange();
                }
            }
        }

        // Sugerir docente de tb_horarios si coincide
        if (window.horariosDb && window.horariosDb.length > 0) {
            const asignacion = window.horariosDb.find(h => h.id_materia == materia.id);
            if (asignacion && asignacion.id_docente) {
                const docInput = document.getElementById('extraordinarioDocenteInput');
                if (docInput && !docInput.value.trim()) {
                    const doc = (window.docentesDb || []).find(d => (d.idDocente || d.id) == asignacion.id_docente);
                    if (doc) {
                        const nom = `${doc.nombreDocente || doc.nombre || ''} ${doc.apPaternoDocente || doc.apPaterno || ''} ${doc.apMaternoDocente || doc.apMaterno || ''}`.trim();
                        docInput.value = nom;
                    }
                }
            }
        }

        actualizarExtraordinarioPreview();
    }

    function buscarDocentesExtraordinario(query = '') {
        const div = document.getElementById('extraordinario-docente-sugerencia');
        if (!div) return;

        const docentes = window.docentesDb || [];
        const normQ = (query || '').normalize("NFD").replace(/[̀-ͯ]/g, "").toLowerCase().trim();

        let filtered = docentes.filter(d => {
            if (!normQ) return true;
            const nom = `${d.nombreDocente || d.nombre || ''} ${d.apPaternoDocente || d.apPaterno || ''} ${d.apMaternoDocente || d.apMaterno || ''}`;
            const nomNorm = nom.normalize("NFD").replace(/[̀-ͯ]/g, "").toLowerCase();
            return nomNorm.includes(normQ);
        });

        div.innerHTML = '';
        div.style.display = 'block';

        if (filtered.length === 0) {
            div.innerHTML = '<div class="p-2 text-muted fs-8 text-center">No se encontraron docentes</div>';
            return;
        }

        filtered.slice(0, 15).forEach(d => {
            const fullName = `${d.nombreDocente || d.nombre || ''} ${d.apPaternoDocente || d.apPaterno || ''} ${d.apMaternoDocente || d.apMaterno || ''}`.trim();
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'list-group-item list-group-item-action py-2 text-start';
            btn.style.borderLeft = '3px solid #0284c7';
            btn.innerHTML = `<span class="fw-semibold text-slate-800 fs-8">${fullName}</span>`;

            btn.onclick = (e) => {
                e.preventDefault();
                const input = document.getElementById('extraordinarioDocenteInput');
                if (input) input.value = fullName;
                div.style.display = 'none';
                actualizarExtraordinarioPreview();
            };
            div.appendChild(btn);
        });
    }

    function actualizarExtraordinarioPreview() {
        const alumno = document.getElementById('extraordinarioAlumnoSearch')?.value.trim() || '';
        const grupo = document.getElementById('extraordinarioGrupoInput')?.value.trim() || '';
        const docente = document.getElementById('extraordinarioDocenteInput')?.value.trim() || '';
        const materia = document.getElementById('extraordinarioMateriaInput')?.value.trim() || '';
        const semSelect = document.getElementById('extraordinarioSemestreSelect');
        const semestreText = semSelect && semSelect.selectedIndex > 0 ? semSelect.options[semSelect.selectedIndex].text : '';

        const lblA = document.getElementById('lbl-extraordinario-nombre');
        const lblG = document.getElementById('lbl-extraordinario-grupo');
        const lblD = document.getElementById('lbl-extraordinario-docente');
        const lblM = document.getElementById('lbl-extraordinario-materia');
        const lblS = document.getElementById('lbl-extraordinario-semestre-val');

        if (lblA && alumno) lblA.innerText = alumno;
        if (lblG) lblG.innerText = grupo || 'Sin asignar';
        if (lblD) lblD.innerText = docente || 'Sin asignar';
        if (lblM) lblM.innerText = materia || 'Sin asignar';
        if (lblS) lblS.innerText = semestreText || 'Sin especificar';

        const infoCard = document.getElementById('extraordinario-info-alumno');
        if (infoCard && (alumno || grupo || materia)) {
            infoCard.style.display = 'block';
        }
    }

    let alumnoExtraordinarioActual = null;

    function abrirPrevisualizacionExtraordinario() {
        const alumnoNombre = document.getElementById('extraordinarioAlumnoSearch')?.value.trim() || 
                             document.getElementById('lbl-extraordinario-nombre')?.innerText.trim() || '';
        const grupo = document.getElementById('extraordinarioGrupoInput')?.value.trim() || 
                      document.getElementById('lbl-extraordinario-grupo')?.innerText.trim() || 'Sin asignar';
        const materia = document.getElementById('extraordinarioMateriaInput')?.value.trim() || 
                        document.getElementById('lbl-extraordinario-materia')?.innerText.trim() || '';
        const docente = document.getElementById('extraordinarioDocenteInput')?.value.trim() || 
                        document.getElementById('lbl-extraordinario-docente')?.innerText.trim() || 'Sin asignar';
        const semSelect = document.getElementById('extraordinarioSemestreSelect');
        const semestre = semSelect && semSelect.selectedIndex > 0 ? semSelect.options[semSelect.selectedIndex].text : 
                         (document.getElementById('lbl-extraordinario-semestre-val')?.innerText.trim() || 'General');

        if (!alumnoNombre || alumnoNombre === 'Alumno') {
            Swal.fire({
                icon: 'warning',
                title: 'Campo Requerido',
                text: 'Por favor, busque y seleccione o escriba el nombre del alumno.',
                confirmButtonColor: '#0284c7'
            });
            return;
        }

        if (!materia || materia === 'Materia') {
            Swal.fire({
                icon: 'warning',
                title: 'Campo Requerido',
                text: 'Por favor, seleccione o escriba la materia para el examen extraordinario.',
                confirmButtonColor: '#0284c7'
            });
            return;
        }

        const matricula = alumnoExtraordinarioActual 
            ? (alumnoExtraordinarioActual.numeroControl || alumnoExtraordinarioActual.idAlumno || 'S/N') 
            : 'S/N';

        renderFormatoExtraordinario(alumnoNombre, matricula, grupo, semestre, materia, docente);

        const modalEl = document.getElementById('modalExtraordinarioPreview');
        if (modalEl && window.bootstrap && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        }
    }

    function renderFormatoExtraordinario(alumno, matricula, grupo, semestre, materia, docente) {
        const isBti = (cctSeleccionado === 'BTI') || (!cctSeleccionado && true);
        const cctNombre = isBti ? 'BACHILLERATO TECNOLÓGICO INTERAMERICANO' : 'BACHILLERATO GENERAL NO ESCOLARIZADO';
        const cctClave = isBti ? '21PCT0073R' : '21PBH0353G';
        
        const fechaObj = new Date();
        const fechaFormateada = fechaObj.toLocaleDateString('es-MX', {
            day: '2-digit', 
            month: 'long', 
            year: 'numeric'
        }).toUpperCase();

        const randFolio = String(Math.floor(1000 + Math.random() * 9000));
        const folio = `EXT-${fechaObj.getFullYear()}-${randFolio}`;

        const html = `
            <div style="font-family: Arial, Helvetica, sans-serif; color: #000; font-size: 8.2pt; line-height: 1.35;">
                <!-- ==================== SECCIÓN 1: ACTA DE EXAMEN EXTRAORDINARIO ==================== -->
                <div style="border: 2px solid #000; padding: 18px 20px; border-radius: 4px; background: #fff; margin-bottom: 12px; position: relative;">
                    <!-- Membrete Oficial -->
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #10599a; padding-bottom: 8px; margin-bottom: 10px;">
                        <div style="width: 70px; text-align: left;">
                            <img src="/img/logo.png" alt="Logo" style="height: 52px; width: auto; object-fit: contain;">
                        </div>
                        <div style="text-align: center; flex-grow: 1; padding: 0 10px;">
                            <div style="font-weight: 900; font-size: 11pt; color: #10599a; text-transform: uppercase; letter-spacing: 0.5px;">
                                ${cctNombre}
                            </div>
                            <div style="font-size: 7.2pt; color: #334155; margin-top: 2px;">
                                Avenida Benito Juárez 901, Colonia Centro Teziutlán, Puebla. Tel: 231-3123979
                            </div>
                            <div style="font-size: 7.5pt; font-weight: bold; color: #0f172a; margin-top: 1px;">
                                CLAVE C.C.T. ${cctClave}
                            </div>
                        </div>
                        <div style="width: 140px; text-align: right; border-left: 1px solid #cbd5e1; padding-left: 8px;">
                            <div style="font-size: 7pt; color: #64748b; font-weight: bold; text-transform: uppercase;">FOLIO OFICIAL</div>
                            <div style="font-size: 9.5pt; font-weight: 900; color: #dc2626;">${folio}</div>
                            <div style="font-size: 7pt; color: #334155; margin-top: 2px;">${fechaFormateada}</div>
                        </div>
                    </div>

                    <!-- Título del Documento -->
                    <div style="text-align: center; margin-bottom: 10px;">
                        <span style="font-size: 10.5pt; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; background: #f8fafc; padding: 3px 20px; border-radius: 4px; border: 1.5px solid #000; display: inline-block;">
                            Acta de Examen Extraordinario
                        </span>
                    </div>

                    <!-- Datos del Alumno y Asignatura -->
                    <table style="width: 100%; border-collapse: collapse; border: 1.5px solid #000; margin-bottom: 10px; font-size: 8pt;">
                        <tbody>
                            <tr>
                                <td style="border: 1px solid #000; padding: 4px 8px; width: 68%; background: #ffffff;">
                                    <span style="font-size: 6.8pt; color: #64748b; display: block; font-weight: bold; text-transform: uppercase;">Nombre del Alumno(a)</span>
                                    <strong style="font-size: 9.2pt; text-transform: uppercase; color: #000;">${alumno}</strong>
                                </td>
                                <td style="border: 1px solid #000; padding: 4px 8px; width: 32%; background: #ffffff;">
                                    <span style="font-size: 6.8pt; color: #64748b; display: block; font-weight: bold; text-transform: uppercase;">Matrícula / No. Control</span>
                                    <strong style="font-size: 8.5pt; color: #000;">${matricula}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #000; padding: 4px 8px; background: #ffffff;">
                                    <span style="font-size: 6.8pt; color: #64748b; display: block; font-weight: bold; text-transform: uppercase;">Asignatura / Materia</span>
                                    <strong style="font-size: 8.8pt; text-transform: uppercase; color: #1e40af;">${materia}</strong>
                                </td>
                                <td style="border: 1px solid #000; padding: 4px 8px; background: #ffffff;">
                                    <span style="font-size: 6.8pt; color: #64748b; display: block; font-weight: bold; text-transform: uppercase;">Semestre / Periodo y Grupo</span>
                                    <strong style="font-size: 8.5pt; text-transform: uppercase; color: #000;">${semestre} | Grupo: ${grupo}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #000; padding: 4px 8px; background: #ffffff;">
                                    <span style="font-size: 6.8pt; color: #64748b; display: block; font-weight: bold; text-transform: uppercase;">Docente Titular / Evaluador</span>
                                    <strong style="font-size: 8.5pt; text-transform: uppercase; color: #000;">${docente}</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Dictamen y Calificación -->
                    <table style="width: 100%; border-collapse: collapse; border: 1.5px solid #000; margin-bottom: 12px; font-size: 8pt;">
                        <thead>
                            <tr style="background: #f8fafc; text-align: center; font-size: 7.2pt;">
                                <th style="border: 1px solid #000; padding: 4px; width: 25%;">FECHA DE APLICACIÓN</th>
                                <th style="border: 1px solid #000; padding: 4px; width: 25%;">CALIFICACIÓN (NÚMERO)</th>
                                <th style="border: 1px solid #000; padding: 4px; width: 30%;">CALIFICACIÓN (CON LETRA)</th>
                                <th style="border: 1px solid #000; padding: 4px; width: 20%;">DICTAMEN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="text-align: center; height: 38px;">
                                <td style="border: 1px solid #000; padding: 4px; font-weight: bold;">${fechaFormateada}</td>
                                <td style="border: 1px solid #000; padding: 4px; font-size: 11pt; font-weight: bold;"></td>
                                <td style="border: 1px solid #000; padding: 4px;"></td>
                                <td style="border: 1px solid #000; padding: 4px; font-size: 7.5pt; line-height: 1.2;">
                                    [ &nbsp; ] APROBADO<br>[ &nbsp; ] REPROBADO
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Leyenda Legal -->
                    <p style="font-size: 7.2pt; text-align: justify; margin: 0 0 16px 0; color: #334155; line-height: 1.35;">
                        El docente y las autoridades escolares que suscriben hacen constar que el(la) alumno(a) acreditó el proceso de examen extraordinario conforme al reglamento escolar vigente. Las calificaciones aquí asentadas son de carácter oficial y definitivo.
                    </p>

                    <!-- Firmas Oficiales -->
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 15px; padding: 0 5px;">
                        <div style="width: 175px; text-align: center;">
                            <div style="border-bottom: 1.5px solid #000; height: 35px; margin-bottom: 4px;"></div>
                            <span style="font-size: 7.2pt; font-weight: bold; text-transform: uppercase; color: #000; display: block;">Firma del Alumno</span>
                            <span style="font-size: 6.8pt; color: #64748b;">Aceptación de Calificación</span>
                        </div>

                        <div style="width: 110px; text-align: center; border: 1.5px dashed #94a3b8; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 4px; background: #fafafa;">
                            <span style="font-size: 7pt; font-weight: bold; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Sello Escolar</span>
                        </div>

                        <div style="width: 175px; text-align: center;">
                            <div style="border-bottom: 1.5px solid #000; height: 35px; margin-bottom: 4px;"></div>
                            <span style="font-size: 7.2pt; font-weight: bold; text-transform: uppercase; color: #000; display: block;">Docente Evaluador</span>
                            <span style="font-size: 6.8pt; color: #64748b;">Firma de Conformidad</span>
                        </div>

                        <div style="width: 175px; text-align: center;">
                            <div style="border-bottom: 1.5px solid #000; height: 35px; margin-bottom: 4px; display: flex; align-items: flex-end; justify-content: center; font-size: 7.2pt; font-weight: bold;">
                                Ing. Fausto Mauro Leyva Flores
                            </div>
                            <span style="font-size: 7.2pt; font-weight: bold; text-transform: uppercase; color: #000; display: block;">Director</span>
                            <span style="font-size: 6.8pt; color: #64748b;">Autorización Institucional</span>
                        </div>
                    </div>
                </div>

                <!-- Línea de Corte -->
                <div style="display: flex; align-items: center; justify-content: center; margin: 10px 0; color: #64748b; font-size: 7pt; font-weight: bold; letter-spacing: 1px; text-transform: uppercase;">
                    <span style="border-top: 1.5px dashed #94a3b8; flex-grow: 1;"></span>
                    <span style="padding: 0 12px;"><i class="fa-solid fa-scissors me-1"></i> CORTE AQUÍ &mdash; TALÓN DE DERECHO A EXAMEN EXTRAORDINARIO</span>
                    <span style="border-top: 1.5px dashed #94a3b8; flex-grow: 1;"></span>
                </div>

                <!-- ==================== SECCIÓN 2: RECIBO Y DERECHO A EXAMEN ==================== -->
                <div style="border: 1.5px solid #000; padding: 14px 18px; border-radius: 4px; background: #fff;">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1.5px solid #10599a; padding-bottom: 6px; margin-bottom: 8px;">
                        <div style="font-weight: 800; font-size: 9.5pt; color: #10599a; text-transform: uppercase;">
                            ${cctNombre} &mdash; <span style="font-size: 8.5pt; color: #334155;">Comprobante de Derecho a Examen</span>
                        </div>
                        <div style="font-size: 8pt; font-weight: bold; color: #dc2626;">
                            FOLIO: ${folio}
                        </div>
                    </div>

                    <table style="width: 100%; border-collapse: collapse; font-size: 7.8pt; margin-bottom: 8px;">
                        <tbody>
                            <tr>
                                <td style="padding: 2px 4px; width: 65%;"><strong>ALUMNO:</strong> ${alumno}</td>
                                <td style="padding: 2px 4px; width: 35%;"><strong>MATRÍCULA:</strong> ${matricula}</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 4px;"><strong>ASIGNATURA:</strong> ${materia}</td>
                                <td style="padding: 2px 4px;"><strong>PERIODO / GRUPO:</strong> ${semestre} (${grupo})</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 2px 4px;"><strong>DOCENTE ASIGNADO:</strong> ${docente}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 5px 8px; font-size: 7.2pt; color: #334155; line-height: 1.3; margin-bottom: 10px;">
                        El presente recibo certifica que el alumno(a) ha tramitado en tiempo y forma su solicitud para presentar la prueba extraordinaria de la materia indicada. Es obligatorio presentar este talón con sello al momento de realizar la evaluación.
                    </div>

                    <div style="display: flex; justify-content: space-around; align-items: flex-end; padding: 0 20px;">
                        <div style="width: 190px; text-align: center;">
                            <div style="border-bottom: 1px solid #000; height: 26px; margin-bottom: 3px;"></div>
                            <span style="font-size: 7pt; font-weight: bold; text-transform: uppercase; color: #475569;">Firma del Alumno</span>
                        </div>
                        <div style="width: 90px; text-align: center; border: 1px dashed #94a3b8; height: 42px; display: flex; align-items: center; justify-content: center; border-radius: 3px;">
                            <span style="font-size: 6.5pt; font-weight: bold; color: #94a3b8; text-transform: uppercase;">Sello</span>
                        </div>
                        <div style="width: 190px; text-align: center;">
                            <div style="border-bottom: 1px solid #000; height: 26px; margin-bottom: 3px; display: flex; align-items: flex-end; justify-content: center; font-size: 7pt; font-weight: bold;">
                                Control Escolar
                            </div>
                            <span style="font-size: 7pt; font-weight: bold; text-transform: uppercase; color: #475569;">Firma de Validación</span>
                        </div>
                    </div>
                </div>
            </div>
        `;

        const container = document.getElementById('extraordinarioHojaImpresion');
        if (container) {
            container.innerHTML = html;
        }
    }

    function ejecutarImpresionExtraordinario() {
        const container = document.getElementById('extraordinarioHojaImpresion');
        if (!container) return;

        const contenidoHtml = container.innerHTML;
        const win = window.open('', '', 'height=900,width=850');
        win.document.write(`
            <!DOCTYPE html>
            <html>
                <head>
                    <title>Acta de Examen Extraordinario</title>
                    <style>
                        * {
                            -webkit-print-color-adjust: exact !important;
                            print-color-adjust: exact !important;
                            box-sizing: border-box;
                        }
                        @page {
                            size: letter portrait;
                            margin: 10mm;
                        }
                        body {
                            font-family: Arial, Helvetica, sans-serif;
                            background: #fff;
                            color: #000;
                            margin: 0;
                            padding: 0;
                        }
                        .hoja-print {
                            width: 100%;
                            max-width: 730px;
                            margin: 0 auto;
                        }
                    </style>
                </head>
                <body>
                    <div class="hoja-print">
                        ${contenidoHtml}
                    </div>
                </body>
            </html>
        `);
        win.document.close();
        win.focus();
        setTimeout(() => {
            win.print();
        }, 350);
    }

    function imprimirActaExtraordinario() {
        abrirPrevisualizacionExtraordinario();
    }

    let alumnosGrupoActual = [];

    function onAsistenciaGrupoChange() {
        const grupoSelect = document.getElementById('asistenciaGrupoSelect');
        const grupoId = grupoSelect ? grupoSelect.value : '';
        const card = document.getElementById('asistencia-preview-card');

        if (!grupoId) {
            if (card) card.style.display = 'none';
            return;
        }

        const grupo = (window.gruposDb || []).find(g => g.id == grupoId);
        if (!grupo) return;

        // Auto-seleccionar trimestre/semestre según id_nivel_academico del grupo
        const cicloSelect = document.getElementById('asistenciaCicloSelect');
        if (cicloSelect && grupo.id_nivel_academico) {
            const isBti = (grupo.id_centroTrabajo == 2) || (cctSeleccionado === 'BTI');
            const num = isBti ? (grupo.id_nivel_academico - 6) : grupo.id_nivel_academico;
            const targetVal = isBti ? `${num}° Semestre` : `${num}° Trimestre`;
            for (let opt of cicloSelect.options) {
                if (opt.value === targetVal) {
                    cicloSelect.value = targetVal;
                    break;
                }
            }
        }

        // Auto-seleccionar docente y materia si existen en tb_horarios
        const asignaciones = (window.horariosDb || []).filter(h => h.id_grupo == grupoId);
        const docenteSelect = document.getElementById('asistenciaDocenteSelect');
        const docenteInput = document.getElementById('asistenciaDocenteInput');
        const materiaInput = document.getElementById('asistenciaMateriaInput');

        if (asignaciones.length > 0) {
            if (docenteSelect && !docenteSelect.value) {
                docenteSelect.value = asignaciones[0].id_docente;
            }
            if (docenteInput && !docenteInput.value.trim()) {
                const docObj = (window.docentesDb || []).find(d => (d.idDocente || d.id) == asignaciones[0].id_docente);
                if (docObj) {
                    const fullNom = `${docObj.nombreDocente || docObj.nombre || ''} ${docObj.apPaternoDocente || docObj.apPaterno || ''} ${docObj.apMaternoDocente || docObj.apMaterno || ''}`.replace(/\s+/g, ' ').trim();
                    docenteInput.value = fullNom;
                    const btnClearDoc = document.getElementById('btn-limpiar-asistencia-docente');
                    if (btnClearDoc) btnClearDoc.style.display = 'block';
                }
            }
            if (materiaInput && !materiaInput.value.trim()) {
                const match = asignaciones.find(a => a.id_docente == (docenteSelect ? docenteSelect.value : null));
                materiaInput.value = match ? match.nombreMateria : asignaciones[0].nombreMateria;
                const btnClearMat = document.getElementById('btn-limpiar-asistencia-materia');
                if (btnClearMat) btnClearMat.style.display = 'block';
            }
        }

        // Consultar alumnos reales asignados al grupo
        fetch(`/reportes/grupo/${grupoId}/alumnos`)
            .then(r => r.json())
            .then(resp => {
                if (resp.success) {
                    alumnosGrupoActual = resp.data || [];
                    const badge = document.getElementById('lbl-asistencia-preview-alumnos');
                    if (badge) {
                        badge.innerText = `${resp.total} alumnos inscritos en este grupo`;
                    }
                }
                actualizarAsistenciaPreview();
            })
            .catch(err => {
                console.error('Error al consultar alumnos:', err);
                actualizarAsistenciaPreview();
            });
    }

    function onAsistenciaDocenteChange() {
        const grupoId = document.getElementById('asistenciaGrupoSelect')?.value;
        const docenteSelect = document.getElementById('asistenciaDocenteSelect');
        const docenteId = docenteSelect ? docenteSelect.value : '';
        const materiaInput = document.getElementById('asistenciaMateriaInput');

        if (grupoId && docenteId && materiaInput && !materiaInput.value.trim()) {
            const match = (window.horariosDb || []).find(h => h.id_grupo == grupoId && h.id_docente == docenteId);
            if (match) {
                materiaInput.value = match.nombreMateria;
                const btnClearMat = document.getElementById('btn-limpiar-asistencia-materia');
                if (btnClearMat) btnClearMat.style.display = 'block';
            }
        }
        actualizarAsistenciaPreview();
    }

    function onAsistenciaCicloChange() {
        const matInput = document.getElementById('asistenciaMateriaInput');
        const cicloSelect = document.getElementById('asistenciaCicloSelect');
        const semVal = cicloSelect ? cicloSelect.value : '';

        // Si la materia actual no pertenece al nuevo semestre seleccionado, limpiarla
        if (matInput && matInput.value.trim() && semVal) {
            const materias = obtenerMateriasFiltradasAsistencia();
            const coincide = materias.some(m => (m.nombreMateria || '').toLowerCase().trim() === matInput.value.toLowerCase().trim());
            if (!coincide) {
                matInput.value = '';
                const btnClearMat = document.getElementById('btn-limpiar-asistencia-materia');
                if (btnClearMat) btnClearMat.style.display = 'none';
            }
        }

        // Desplegar de inmediato las materias de ese semestre bajo el campo de Asignatura
        buscarMateriasAsistencia('', true);

        actualizarAsistenciaPreview();
    }

    function obtenerMateriasFiltradasAsistencia(query = '') {
        const materias = window.materiasDb || [];
        const cicloSelect = document.getElementById('asistenciaCicloSelect');
        const cicloVal = cicloSelect ? cicloSelect.value : '';
        const match = cicloVal.match(/\d+/);
        const semNum = match ? parseInt(match[0]) : null;

        const normQ = (query || '').normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().trim();
        const targetCentroId = cctSeleccionado === 'BTI' ? 2 : (cctSeleccionado === 'BGNE' ? 3 : null);

        return materias.filter(m => {
            if (targetCentroId) {
                const cid = m.idCentroTrabajo ?? m.id_centro_trabajo ?? m.id_centroTrabajo;
                if (cid && cid != targetCentroId) return false;
            }

            if (semNum !== null) {
                const nivel = parseInt(m.id_nivel_academico);
                if (cctSeleccionado === 'BTI') {
                    if (nivel !== (semNum + 6) && nivel !== semNum) return false;
                } else if (cctSeleccionado === 'BGNE') {
                    if (nivel !== semNum) return false;
                } else {
                    if (nivel !== semNum && nivel !== (semNum + 6)) return false;
                }
            }

            if (!normQ) return true;
            const nomNorm = (m.nombreMateria || '').normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
            const claveNorm = (m.clave || '').normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
            return nomNorm.includes(normQ) || claveNorm.includes(normQ);
        });
    }

    function buscarMateriasAsistencia(query = '', forceOpen = false) {
        const div = document.getElementById('asistencia-materia-sugerencia');
        const btnClear = document.getElementById('btn-limpiar-asistencia-materia');
        if (btnClear) {
            btnClear.style.display = query ? 'block' : 'none';
        }
        if (!div) return;

        // Si materiasDb está vacío, cargar diferido
        if (!window.materiasDb || window.materiasDb.length === 0) {
            fetch('/materias/lista?limit=1000')
                .then(r => r.json())
                .then(res => {
                    if (res && res.data) {
                        window.materiasDb = res.data;
                        buscarMateriasAsistencia(query, forceOpen);
                    }
                }).catch(() => {});
        }

        const cicloSelect = document.getElementById('asistenciaCicloSelect');
        const cicloVal = cicloSelect ? cicloSelect.value : '';
        const match = cicloVal.match(/\d+/);
        const semNum = match ? parseInt(match[0]) : null;

        const filtered = obtenerMateriasFiltradasAsistencia(query);

        div.innerHTML = '';
        div.style.display = 'block';

        const cicloLabel = document.getElementById('lbl-asistencia-ciclo-label')?.innerText || 'Periodo';
        const headerNotice = document.createElement('div');
        headerNotice.className = 'p-2 bg-light text-primary fw-bold fs-9 border-bottom text-center d-flex justify-content-between align-items-center px-3';
        
        if (semNum !== null) {
            headerNotice.innerHTML = `
                <span><i class="fa-solid fa-layer-group me-1 text-info"></i> ${cicloVal} (${filtered.length} materias)</span>
                <span class="badge bg-primary-subtle text-primary">${cctSeleccionado || 'Plan'}</span>
            `;
        } else {
            headerNotice.innerHTML = `
                <span><i class="fa-solid fa-info-circle me-1 text-info"></i> Seleccione un ${cicloLabel.toLowerCase()} para filtrar materias</span>
                <span class="badge bg-secondary-subtle text-secondary">${filtered.length} totales</span>
            `;
        }
        div.appendChild(headerNotice);

        if (filtered.length === 0) {
            const emptyDiv = document.createElement('div');
            emptyDiv.className = 'p-3 text-muted fs-8 text-center';
            emptyDiv.innerText = semNum !== null ? `No hay materias registradas para ${cicloVal}` : 'No se encontraron materias';
            div.appendChild(emptyDiv);
            return;
        }

        filtered.slice(0, 30).forEach(m => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'list-group-item list-group-item-action py-2 text-start d-flex justify-content-between align-items-center';
            btn.style.borderLeft = '3px solid #0284c7';
            
            let semBadge = '';
            if (m.id_nivel_academico) {
                const num = m.id_nivel_academico >= 7 ? (m.id_nivel_academico - 6) : m.id_nivel_academico;
                semBadge = `${num}º Sem/Trim`;
            }

            btn.innerHTML = `
                <div>
                    <span class="fw-semibold text-slate-800 fs-8 d-block">${m.nombreMateria}</span>
                    ${m.clave ? `<small class="text-muted fs-9">Clave: ${m.clave}</small>` : ''}
                </div>
                <div>
                    ${semBadge ? `<span class="badge bg-light text-secondary border fs-9">${semBadge}</span>` : ''}
                </div>
            `;

            btn.onclick = (e) => {
                e.preventDefault();
                e.stopPropagation();
                seleccionarMateriaAsistencia(m);
            };
            div.appendChild(btn);
        });
    }

    function seleccionarMateriaAsistencia(materia) {
        const input = document.getElementById('asistenciaMateriaInput');
        const div = document.getElementById('asistencia-materia-sugerencia');
        const btnClear = document.getElementById('btn-limpiar-asistencia-materia');

        if (input) input.value = materia.nombreMateria;
        if (btnClear) btnClear.style.display = 'block';
        if (div) div.style.display = 'none';

        // Si hay grupo seleccionado, intentar auto-asignar docente de tb_horarios
        const grupoId = document.getElementById('asistenciaGrupoSelect')?.value;
        if (grupoId && window.horariosDb) {
            const matId = materia.id || materia.idMateria;
            const matchH = window.horariosDb.find(h => h.id_grupo == grupoId && (h.id_materia == matId || (h.nombreMateria && h.nombreMateria.toLowerCase() === materia.nombreMateria.toLowerCase())));
            if (matchH && matchH.id_docente) {
                const docObj = (window.docentesDb || []).find(d => (d.idDocente || d.id) == matchH.id_docente);
                if (docObj) {
                    const fullNom = `${docObj.nombreDocente || docObj.nombre || ''} ${docObj.apPaternoDocente || docObj.apPaterno || ''} ${docObj.apMaternoDocente || docObj.apMaterno || ''}`.replace(/\s+/g, ' ').trim();
                    seleccionarDocenteAsistencia(matchH.id_docente, fullNom);
                }
            }
        }

        actualizarAsistenciaPreview();
    }

    function limpiarMateriaAsistencia() {
        const input = document.getElementById('asistenciaMateriaInput');
        const div = document.getElementById('asistencia-materia-sugerencia');
        const btnClear = document.getElementById('btn-limpiar-asistencia-materia');

        if (input) input.value = '';
        if (btnClear) btnClear.style.display = 'none';
        if (div) { div.innerHTML = ''; div.style.display = 'none'; }

        actualizarAsistenciaPreview();
    }

    function buscarDocentesAsistencia(query = '') {
        const div = document.getElementById('asistencia-docente-sugerencia');
        const btnClear = document.getElementById('btn-limpiar-asistencia-docente');
        if (btnClear) {
            btnClear.style.display = query ? 'block' : 'none';
        }
        if (!div) return;

        let docentes = window.docentesDb || [];
        if (docentes.length === 0) {
            const sel = document.getElementById('asistenciaDocenteSelect');
            if (sel && sel.options.length > 1) {
                docentes = Array.from(sel.options).slice(1).map(opt => ({
                    id: opt.value,
                    idDocente: opt.value,
                    nombreDocente: opt.text
                }));
                window.docentesDb = docentes;
            }
        }

        const normQ = (query || '').normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().trim();

        let filtered = docentes.filter(d => {
            if (!normQ) return true;
            const nom = `${d.nombreDocente || d.nombre || ''} ${d.apPaternoDocente || d.apPaterno || ''} ${d.apMaternoDocente || d.apMaterno || ''}`;
            const nomNorm = nom.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
            return nomNorm.includes(normQ);
        });

        div.innerHTML = '';
        div.style.display = 'block';

        if (filtered.length === 0) {
            div.innerHTML = '<div class="p-2 text-muted fs-8 text-center">No se encontraron docentes</div>';
            return;
        }

        filtered.slice(0, 25).forEach(d => {
            const dId = d.idDocente || d.id;
            const fullName = `${d.nombreDocente || d.nombre || ''} ${d.apPaternoDocente || d.apPaterno || ''} ${d.apMaternoDocente || d.apMaterno || ''}`.replace(/\s+/g, ' ').trim();
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'list-group-item list-group-item-action py-2 text-start d-flex justify-content-between align-items-center';
            btn.style.borderLeft = '3px solid #0284c7';
            btn.innerHTML = `
                <div>
                    <span class="fw-semibold text-slate-800 fs-8 d-block">${fullName}</span>
                </div>
                <i class="fa-solid fa-check text-muted fs-9 opacity-50"></i>
            `;

            btn.onclick = (e) => {
                e.preventDefault();
                e.stopPropagation();
                seleccionarDocenteAsistencia(dId, fullName);
            };
            div.appendChild(btn);
        });
    }

    function seleccionarDocenteAsistencia(dId, fullName) {
        const input = document.getElementById('asistenciaDocenteInput');
        const sel = document.getElementById('asistenciaDocenteSelect');
        const div = document.getElementById('asistencia-docente-sugerencia');
        const btnClear = document.getElementById('btn-limpiar-asistencia-docente');

        if (input) input.value = fullName;
        if (sel) {
            sel.value = dId;
            if (sel.value != dId) {
                const opt = document.createElement('option');
                opt.value = dId;
                opt.text = fullName;
                sel.appendChild(opt);
                sel.value = dId;
            }
        }
        if (btnClear) btnClear.style.display = 'block';
        if (div) div.style.display = 'none';

        onAsistenciaDocenteChange();
    }

    function limpiarDocenteAsistencia() {
        const input = document.getElementById('asistenciaDocenteInput');
        const sel = document.getElementById('asistenciaDocenteSelect');
        const div = document.getElementById('asistencia-docente-sugerencia');
        const btnClear = document.getElementById('btn-limpiar-asistencia-docente');

        if (input) input.value = '';
        if (sel) sel.value = '';
        if (btnClear) btnClear.style.display = 'none';
        if (div) { div.innerHTML = ''; div.style.display = 'none'; }

        actualizarAsistenciaPreview();
    }

    function calcularFechasTrimestrePreview(grupo, trimestreNum) {
        if (!grupo || !grupo.fechaInicio) return 'Fechas por definir';

        // Parse UTC de fechaInicio (ej. 2026-02-08)
        const parts = grupo.fechaInicio.split('-');
        if (parts.length < 3) return 'Fechas por definir';
        const startDate = new Date(Date.UTC(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2])));

        // Cada trimestre dura exactamente 13 semanas (mismo algoritmo oficial de horarios)
        const weeksOffset = (trimestreNum - 1) * 13;
        const periodStart = new Date(startDate.getTime() + (weeksOffset * 7 * 24 * 60 * 60 * 1000));
        const periodEnd = new Date(periodStart.getTime() + (12 * 7 * 24 * 60 * 60 * 1000));

        const pad = (n) => String(n).padStart(2, '0');
        const fmt = (d) => `${pad(d.getUTCDate())}/${pad(d.getUTCMonth() + 1)}/${d.getUTCFullYear()}`;

        return `${fmt(periodStart)} al ${fmt(periodEnd)} (13 semanas)`;
    }

    function actualizarAsistenciaPreview() {
        const grupoSelect = document.getElementById('asistenciaGrupoSelect');
        const docenteSelect = document.getElementById('asistenciaDocenteSelect');
        const docenteInput = document.getElementById('asistenciaDocenteInput');
        const materiaInput = document.getElementById('asistenciaMateriaInput');
        const cicloSelect = document.getElementById('asistenciaCicloSelect');
        const card = document.getElementById('asistencia-preview-card');

        if (!grupoSelect || !grupoSelect.value) {
            if (card) card.style.display = 'none';
            return;
        }

        const grupoId = grupoSelect.value;
        const grupo = (window.gruposDb || []).find(g => g.id == grupoId);
        if (!grupo) {
            if (card) card.style.display = 'none';
            return;
        }

        let docenteNombre = 'Docente no seleccionado (general)';
        if (docenteInput && docenteInput.value.trim()) {
            docenteNombre = docenteInput.value.trim();
        } else if (docenteSelect && docenteSelect.selectedIndex > 0) {
            docenteNombre = docenteSelect.options[docenteSelect.selectedIndex].text.trim();
        }
            
        const materiaNombre = (materiaInput && materiaInput.value.trim()) ? materiaInput.value.trim() : 'Materia General';
        const ciclo = cicloSelect ? cicloSelect.value : '1° Periodo';

        const matchTrim = ciclo.match(/\d+/);
        const trimNum = matchTrim ? parseInt(matchTrim[0]) : 1;

        const lblD = document.getElementById('lbl-asistencia-preview-docente');
        const lblM = document.getElementById('lbl-asistencia-preview-materia');
        const lblG = document.getElementById('lbl-asistencia-preview-grupo');
        const lblT = document.getElementById('lbl-asistencia-preview-term');
        const lblFechas = document.getElementById('lbl-asistencia-preview-fechas-val');

        if (lblD) lblD.innerText = docenteNombre;
        if (lblM) lblM.innerText = materiaNombre;
        if (lblG) lblG.innerText = `${grupo.clave} (${grupo.modalidadHorario || ''})`;
        if (lblT) lblT.innerText = ciclo;
        if (lblFechas) lblFechas.innerText = calcularFechasTrimestrePreview(grupo, trimNum);

        if (card) card.style.display = 'block';
    }

    function generarListaAsistenciaOficialPdf() {
        const grupoSelect = document.getElementById('asistenciaGrupoSelect');
        const grupoId = grupoSelect ? grupoSelect.value : '';

        if (!grupoId) {
            Swal.fire({
                icon: 'warning',
                title: 'Seleccione un grupo',
                text: 'Por favor, elija un grupo para generar la lista de asistencia.',
                confirmButtonColor: '#0284c7'
            });
            return;
        }

        const grupo = (window.gruposDb || []).find(g => g.id == grupoId) || {};
        const clave = grupo.clave || '';
        const cctId = grupo.id_centroTrabajo ?? grupo.idCentroTrabajo ?? '';
        const modalidad = grupo.modalidadHorario || '';
        const fechaIni = grupo.fechaInicio || '';

        const docenteSelect = document.getElementById('asistenciaDocenteSelect');
        const docenteInput = document.getElementById('asistenciaDocenteInput');
        let docenteId = docenteSelect ? docenteSelect.value : '';
        let docenteNombre = docenteInput && docenteInput.value.trim() 
            ? docenteInput.value.trim() 
            : (docenteSelect && docenteSelect.selectedIndex > 0 ? docenteSelect.options[docenteSelect.selectedIndex].text.trim() : '');

        if (!docenteId && docenteNombre && window.docentesDb) {
            const normDN = docenteNombre.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().trim();
            const foundDoc = window.docentesDb.find(d => {
                const nom = `${d.nombreDocente || d.nombre || ''} ${d.apPaternoDocente || d.apPaterno || ''} ${d.apMaternoDocente || d.apMaterno || ''}`.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().trim();
                return nom === normDN;
            });
            if (foundDoc) {
                docenteId = foundDoc.idDocente || foundDoc.id || '';
            }
        }

        const materia = (document.getElementById('asistenciaMateriaInput') ? document.getElementById('asistenciaMateriaInput').value.trim() : '');
        const ciclo = document.getElementById('asistenciaCicloSelect') ? document.getElementById('asistenciaCicloSelect').value : '';
        const matchTrim = ciclo.match(/\d+/);
        const trimestreNum = matchTrim ? matchTrim[0] : '1';

        const url = `/reportes/asistencia-pdf?id_grupo=${encodeURIComponent(grupoId)}&clave_grupo=${encodeURIComponent(clave)}&id_centro_trabajo=${encodeURIComponent(cctId)}&modalidad=${encodeURIComponent(modalidad)}&fecha_inicio=${encodeURIComponent(fechaIni)}&id_docente=${encodeURIComponent(docenteId)}&docente_nombre=${encodeURIComponent(docenteNombre)}&materia=${encodeURIComponent(materia)}&trimestre=${encodeURIComponent(trimestreNum)}`;
        
        window.open(url, '_blank');
    }

    // ==========================================
    // LOGICA DE FILTRADO PARA LISTA DE ASISTENCIA POR GRUPO
    // ==========================================
    function filtrarDocentesAsistencias() {
        const searchVal = document.getElementById('asistenciasSearch').value.toLowerCase().trim();
        const diaFilter = document.getElementById('filtroDiaAsistencias').value;
        const trimestreFilter = document.getElementById('filtroTrimestreAsistencias').value;

        const rows = document.querySelectorAll('.docente-row');
        let visibleRows = 0;

        rows.forEach(row => {
            const docente = row.getAttribute('data-docente');
            const materia = row.getAttribute('data-materia');
            const dia = row.getAttribute('data-dia');
            const trimestre = row.getAttribute('data-trimestre');
            const cct = row.getAttribute('data-cct');

            // Filtrar también para que coincida con el CCT actualmente seleccionado
            const matchCCT = cct === cctSeleccionado;
            const matchSearch = docente.includes(searchVal) || materia.includes(searchVal);
            const matchDia = diaFilter === '' || dia === diaFilter;
            const matchTrimestre = trimestreFilter === '' || trimestre === trimestreFilter;

            if (matchCCT && matchSearch && matchDia && matchTrimestre) {
                row.style.display = 'table-row';
                visibleRows++;
            } else {
                row.style.display = 'none';
            }
        });

        const errorDiv = document.getElementById('noAsistenciasResultados');
        const tabla = document.getElementById('tablaAsistenciasDocentes');

        if (visibleRows === 0) {
            errorDiv.style.display = 'block';
            tabla.style.display = 'none';
        } else {
            errorDiv.style.display = 'none';
            tabla.style.display = 'table';
        }
    }

    // ==========================================
    // SIMULACIÓN DE DESCARGAS E IMPRESIÓN (SWEETALERT2)
    // ==========================================

    function previewDoc(tipo, alumno) {
        Swal.fire({
            title: `Vista Previa: ${tipo}`,
            html: `<div class="p-3 border rounded text-start bg-light" style="font-family: monospace; font-size: 0.85rem;">
                     <strong>SISTEMA DE CONTROL ESCOLAR</strong><br>
                     CCT: ${cctSeleccionado}<br>
                     Documento: ${tipo} Certificado<br>
                     Alumno: ${alumno}<br>
                     Fecha de Emisión: ${new Date().toLocaleDateString()}<br>
                     -----------------------------------------<br>
                     * Estatus de acreditación de materias.<br>
                     * Código de verificación QR.<br>
                     * Firma digital del Director General.<br>
                     -----------------------------------------<br>
                     <span class="text-muted">(Vista previa simulada con éxito)</span>
                   </div>`,
            width: '600px',
            confirmButtonText: 'Cerrar',
            confirmButtonColor: '#0284c7'
        });
    }

    function printDoc(tipo, nombre, detalle = '') {
        let message = `Generando el archivo PDF para <strong>${nombre}</strong>.`;
        if (detalle) {
            message += `<br>Asignación: <i>${detalle}</i>`;
        }

        Swal.fire({
            title: `Imprimir ${tipo}`,
            html: message,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: '<i class="fa-solid fa-print me-1"></i> Descargar PDF',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: '¡Generado!',
                    text: 'El documento se descargó correctamente en tu equipo.',
                    icon: 'success',
                    confirmButtonColor: '#0284c7'
                });
            }
        });
    }

    function simularImpresionGrupo(grupo) {
        Swal.fire({
            title: 'Imprimir Grupo Completo',
            html: `¿Deseas descargar el paquete PDF con todas las boletas del grupo <strong>${grupo}</strong>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, descargar paquete',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Generando PDF consolidado...',
                    html: 'Esto puede demorar unos segundos.',
                    timer: 1500,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                }).then(() => {
                    Swal.fire({
                        title: '¡Listo!',
                        text: `Se descargó el paquete del grupo ${grupo} (3 alumnos).`,
                        icon: 'success',
                        confirmButtonColor: '#0284c7'
                    });
                });
            }
        });
    }

    // ==========================================
    // SECCIÓN: REPORTES DE INDISCIPLINA
    // ==========================================

    let reporteAlumnoSeleccionado = null;

    window.buscarAlumnoReporte = function(query) {
        buscarAlumnosReal(query, 'reporte-alumno-sugerencia', (alumno) => {
            reporteAlumnoSeleccionado = alumno;
            document.getElementById('reporte_id_alumno').value = alumno.idAlumno;
            document.getElementById('reporte_alumno_nombre').value = `${alumno.nombre} ${alumno.apPaterno} ${alumno.apMaterno || ''}`.trim();
            
            document.getElementById('lbl-reporte-alumno-nom').innerText = `${alumno.nombre} ${alumno.apPaterno} ${alumno.apMaterno || ''}`.trim().toUpperCase();
            document.getElementById('lbl-reporte-alumno-mat').innerText = `Matrícula: ${alumno.numeroControl || alumno.idAlumno || 'S/N'}`;
            
            // Cargar tutor automáticamente
            document.getElementById('reporte_tutor_nombre').value = alumno.tutor || '';
            
            document.getElementById('reporte-alumno-info-box').style.display = 'block';
            document.getElementById('reporteAlumnoSearch').value = '';
        });
    };

    window.deseleccionarAlumnoReporte = function() {
        reporteAlumnoSeleccionado = null;
        document.getElementById('reporte_id_alumno').value = '';
        document.getElementById('reporte_alumno_nombre').value = '';
        document.getElementById('reporte_tutor_nombre').value = '';
        document.getElementById('reporte-alumno-info-box').style.display = 'none';
    };

    window.registrarReporteIndisciplina = function(event) {
        event.preventDefault();
        
        const idAlumno = document.getElementById('reporte_id_alumno').value;
        const alumnoNombre = document.getElementById('reporte_alumno_nombre').value;
        const tutorNombre = document.getElementById('reporte_tutor_nombre').value;
        const incidente = document.getElementById('reporte_incidente').value;
        const parcial = document.getElementById('reporte_parcial').value;

        if (!idAlumno) {
            Swal.fire({
                icon: 'warning',
                title: 'Seleccione un alumno',
                text: 'Por favor, busque y seleccione un alumno de la lista.',
                confirmButtonColor: '#0284c7'
            });
            return;
        }

        Swal.fire({
            title: 'Registrando reporte...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('/reportes-indisciplina', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                id_alumno: idAlumno,
                alumno_nombre: alumnoNombre,
                tutor_nombre: tutorNombre,
                incidente: incidente,
                parcial: parcial
            })
        })
        .then(r => r.json())
        .then(resp => {
            Swal.close();
            if (resp.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Reporte registrado',
                    text: 'El reporte de indisciplina se guardó correctamente.',
                    confirmButtonColor: '#0284c7'
                }).then(() => {
                    deseleccionarAlumnoReporte();
                    document.getElementById('reporte_incidente').value = '';
                    cargarHistorialReportes();
                    imprimirFormatoReporte(resp.data);
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: resp.message || 'No se pudo registrar el reporte.',
                    confirmButtonColor: '#0284c7'
                });
            }
        })
        .catch(err => {
            Swal.close();
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error de red',
                text: 'Ocurrió un problema de comunicación con el servidor.',
                confirmButtonColor: '#0284c7'
            });
        });
    };

    window.cargarHistorialReportes = function(search = '') {
        const tbody = document.getElementById('tabla-reportes-historial');
        if (!tbody) return;

        fetch(`/reportes-indisciplina?search=${encodeURIComponent(search)}`)
            .then(r => r.json())
            .then(resp => {
                if (resp.success) {
                    tbody.innerHTML = '';
                    const list = resp.data || [];
                    if (list.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-muted">No se encontraron reportes.</td></tr>`;
                        return;
                    }
                    
                    window.reportesCache = window.reportesCache || {};
                    list.forEach(rep => {
                        window.reportesCache[rep.id] = rep;
                        const tr = document.createElement('tr');
                        tr.className = 'fs-8';
                        tr.innerHTML = `
                            <td><strong class="text-slate-800">${rep.folio}</strong></td>
                            <td>
                                <span class="d-block fw-semibold">${rep.alumno_nombre}</span>
                                <small class="text-muted">ID: ${rep.id_alumno}</small>
                            </td>
                            <td>${rep.tutor_nombre}</td>
                            <td><span class="badge bg-warning-subtle text-warning-emphasis fw-bold">${rep.parcial}° Parcial</span></td>
                            <td>${new Date(rep.fecha + 'T00:00:00').toLocaleDateString('es-MX')}</td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <button class="btn btn-sm btn-light text-primary border" onclick="imprimirFormatoReporte(window.reportesCache[${rep.id}])" title="Imprimir Formato">
                                        <i class="fa-solid fa-print"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light text-danger border" onclick="eliminarReporte(${rep.id})" title="Eliminar">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                }
            })
            .catch(err => {
                console.error('Error al cargar historial:', err);
                tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-danger">Error al cargar el historial.</td></tr>`;
            });
    };

    window.eliminarReporte = function(id) {
        Swal.fire({
            title: '¿Eliminar reporte?',
            text: "Esta acción no se puede deshacer y el reporte se borrará del historial.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#64748b'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Eliminando...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/reportes-indisciplina/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(resp => {
                    Swal.close();
                    if (resp.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: 'El reporte de indisciplina fue eliminado.',
                            confirmButtonColor: '#0284c7'
                        });
                        cargarHistorialReportes();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: resp.message || 'No se pudo eliminar el reporte.',
                            confirmButtonColor: '#0284c7'
                        });
                    }
                })
                .catch(err => {
                    Swal.close();
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de red',
                        text: 'No se pudo comunicar con el servidor.',
                        confirmButtonColor: '#0284c7'
                    });
                });
            }
        });
    };

    window.imprimirFormatoReporte = function(rep) {
        const fechaFormateada = new Date(rep.fecha + 'T00:00:00').toLocaleDateString('es-MX', {day: 'numeric', month: 'long', year: 'numeric'}).toUpperCase();
        
        function renderBloque(repVal) {
            return `
                <div style="border: 2px solid #000; padding: 25px; font-family: Arial, Helvetica, sans-serif; position: relative; border-radius: 6px; background: #fff; margin-bottom: 25px;">
                    <!-- Membrete -->
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px; border-bottom: 2px solid #10599a; padding-bottom: 10px;">
                        <img src="/img/logo.png" alt="Logo" style="height: 50px; object-fit: contain;">
                        <div style="text-align: center; flex-grow: 1;">
                            <div style="font-weight: bold; font-size: 11pt; color: #10599a; letter-spacing: 0.5px; text-transform: uppercase;">
                                Bachillerato Tecnológico Interamericano
                            </div>
                            <div style="font-size: 6.8pt; color: #444; margin-top: 2px;">
                                Avenida Benito Juárez 901, Colonia Centro Teziutlán Puebla. Tel: 231-3123979
                            </div>
                        </div>
                        <div style="text-align: right; font-size: 8.5pt; font-weight: bold;">
                            Folio: <span style="color: #dc2626;">${repVal.folio}</span><br>
                            Fecha: ${fechaFormateada}
                        </div>
                    </div>

                    <!-- Titulo -->
                    <div style="text-align: center; margin-bottom: 20px;">
                        <h4 style="margin: 0; font-size: 12.5pt; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; text-decoration: underline;">
                            Reporte por indisciplina
                        </h4>
                    </div>

                    <!-- Contenido -->
                    <div style="font-size: 9.5pt; line-height: 1.7; color: #000; text-align: justify;">
                        <p style="margin: 0 0 12px 0;">
                            <strong>Sr. (a):</strong> <span style="border-bottom: 1px solid #000; padding: 0 10px; font-weight: bold; text-transform: uppercase;">${repVal.tutor_nombre || 'S/N'}</span>
                        </p>
                        <p style="margin: 0 0 15px 0; text-transform: uppercase; font-weight: bold; font-size: 8.5pt; color: #333;">
                            EL QUE SUSCRIBE ING. FAUSTO MAURO LEYVA FLORES, DIRECTOR DEL BACHILLERATO TECNOLÓGICO INTERAMERICANO.
                        </p>
                        <p style="margin: 0 0 12px 0;">
                            POR ESTE CONDUCTO LE INFORMO QUE EL ALUMNO(A): <span style="border-bottom: 1px solid #000; padding: 0 10px; font-weight: bold; text-transform: uppercase;">${repVal.alumno_nombre}</span>
                        </p>
                        <p style="margin: 0 0 8px 0;">
                            INCURRIÓ A LA FALTA DE INDISCIPLINA YA QUE:
                        </p>
                        <div style="border: 1px solid #94a3b8; background: #f8fafc; padding: 12px 15px; border-radius: 4px; font-family: monospace; font-size: 9.5pt; margin-bottom: 15px; min-height: 90px; white-space: pre-wrap; line-height: 1.4;">${repVal.incidente}</div>
                        
                        <p style="margin: 0 0 25px 0; font-size: 8.5pt; font-style: italic; color: #334155; line-height: 1.5; font-weight: bold; text-align: center;">
                            ESPERAMOS SU VALIOSA CONTRIBUCIÓN PARA QUE SU HIJO (A) MEJORE EN SU COMPORTAMIENTO Y NOS AYUDE A SACARLO ADELANTE EN SU EDUCACIÓN, FORMÁNDOLO EN UN BUEN CIUDADADANO.
                        </p>
                    </div>

                    <!-- Firmas -->
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 30px;">
                        <div style="width: 250px; text-align: center;">
                            <div style="border-bottom: 1px solid #000; margin-bottom: 5px; height: 35px;"></div>
                            <span style="font-size: 7.5pt; font-weight: bold; text-transform: uppercase; color: #475569;">Nombre y Firma del Alumno</span>
                        </div>
                        <div style="width: 140px; text-align: center; border: 1px dashed #cbd5e1; height: 75px; display: flex; align-items: center; justify-content: center; border-radius: 4px; background: #fafafa;">
                            <span style="font-size: 7.5pt; font-weight: bold; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Sello</span>
                        </div>
                        <div style="width: 250px; text-align: center;">
                            <div style="border-bottom: 1px solid #000; margin-bottom: 5px; height: 35px; text-align: center; font-size: 8pt; font-weight: bold; display: flex; align-items: flex-end; justify-content: center; text-transform: uppercase; color: #000;">
                                Ing. Fausto Leyva Flores
                            </div>
                            <span style="font-size: 7.5pt; font-weight: bold; text-transform: uppercase; color: #475569;">Director</span>
                        </div>
                    </div>
                </div>
            `;
        }

        const win = window.open('', '', 'height=850,width=800');
        win.document.write(`
            <html>
                <head>
                    <title>Reporte de Indisciplina - ${rep.folio}</title>
                    <style>
                        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; box-sizing: border-box; }
                        @page {
                            size: letter portrait;
                            margin: 15mm 15mm 15mm 15mm;
                        }
                        html, body {
                            font-family: Arial, Helvetica, sans-serif;
                            background: #fff;
                            color: #000;
                            padding: 0;
                            margin: 0;
                        }
                        .container {
                            width: 100%;
                            margin: 0 auto;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        ${renderBloque(rep)}
                    </div>
                </body>
            </html>
        `);
        win.document.close();
        win.focus();
        setTimeout(() => {
            win.print();
            win.close();
        }, 400);
    };

    // Cerrar sugerencias al hacer click fuera
    document.addEventListener('click', function(e) {
        const sugDoc = document.getElementById('asistencia-docente-sugerencia');
        const inputDoc = document.getElementById('asistenciaDocenteInput');
        const btnClearDoc = document.getElementById('btn-limpiar-asistencia-docente');
        if (sugDoc && inputDoc && !sugDoc.contains(e.target) && !inputDoc.contains(e.target) && (!btnClearDoc || !btnClearDoc.contains(e.target))) {
            sugDoc.style.display = 'none';
        }

        const sugMat = document.getElementById('asistencia-materia-sugerencia');
        const inputMat = document.getElementById('asistenciaMateriaInput');
        const cicloSel = document.getElementById('asistenciaCicloSelect');
        const btnClearMat = document.getElementById('btn-limpiar-asistencia-materia');
        if (sugMat && inputMat && !sugMat.contains(e.target) && !inputMat.contains(e.target) && (!cicloSel || !cicloSel.contains(e.target)) && (!btnClearMat || !btnClearMat.contains(e.target))) {
            sugMat.style.display = 'none';
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        if (typeof asegurarCatalogosAsistencia === 'function') {
            asegurarCatalogosAsistencia();
        }
    });
</script>

@endsection
