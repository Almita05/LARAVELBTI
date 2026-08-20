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
                                <i class="fa-solid fa-graduation-cap text-info me-2"></i>Kardex Académico
                            </h5>
                            <p class="text-muted mb-0">Historial completo de asignaturas cursadas, calificaciones y promedios.</p>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Buscar Alumno para Kardex</label>
                                <input type="text" id="kardexAlumnoSearch" class="form-control" placeholder="Escriba matrícula o nombre del alumno..." onkeyup="simularBusquedaAlumnoKardex(this.value)">
                                <div id="kardex-alumno-sugerencia" class="list-group mt-2 shadow-sm" style="display: none;">
                                    <!-- Cargado por JS -->
                                </div>
                            </div>
                            <div class="col-12 mt-4" id="kardex-info-alumno" style="display: none;">
                                <div class="card p-3 border-0 bg-light mb-3" style="border-radius: 12px;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="fw-bold mb-0 text-slate-800" id="lbl-kardex-nombre"></h6>
                                            <small class="text-muted" id="lbl-kardex-matricula"></small>
                                        </div>
                                        <div class="text-end">
                                            <span class="d-block fw-bold text-info" id="lbl-kardex-promedio">Promedio General: —</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="button" class="btn btn-primary fw-bold" onclick="imprimirKardex()">
                                        <i class="fa-solid fa-print me-2"></i> Imprimir Kárdex General
                                    </button>
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
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nombre del Alumno</label>
                                <input type="text" id="extraordinarioAlumnoSearch" class="form-control" placeholder="Escriba matrícula o nombre del alumno..." onkeyup="simularBusquedaAlumnoExtraordinario(this.value)">
                                <div id="extraordinario-alumno-sugerencia" class="list-group mt-2 shadow-sm" style="display: none;">
                                    <!-- Cargado por JS -->
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Grupo</label>
                                <select id="extraordinarioGrupoSelect" class="form-select" onchange="actualizarExtraordinarioPreview()">
                                    <option value="Grupo A">Grupo A</option>
                                    <option value="Grupo B">Grupo B</option>
                                    <option value="Grupo C">Grupo C</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Docente</label>
                                <select id="extraordinarioDocenteSelect" class="form-select" onchange="actualizarExtraordinarioPreview()">
                                    <option value="Ing. Juan Carlos Pérez Gómez">Ing. Juan Carlos Pérez Gómez</option>
                                    <option value="Lic. María Elena Rojas Ortiz">Lic. María Elena Rojas Ortiz</option>
                                    <option value="Dr. Alejandro Silva Montes">Dr. Alejandro Silva Montes</option>
                                    <option value="Mtra. Laura Patricia Jiménez">Mtra. Laura Patricia Jiménez</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Materia</label>
                                <select id="extraordinarioMateriaSelect" class="form-select" onchange="actualizarExtraordinarioPreview()">
                                    <option value="Matemáticas Aplicadas">Matemáticas Aplicadas</option>
                                    <option value="Programación Orientada a Objetos">Programación Orientada a Objetos</option>
                                    <option value="Administración General">Administración General</option>
                                    <option value="Inglés Técnico I">Inglés Técnico I</option>
                                </select>
                            </div>
                            <div class="col-12 mt-4" id="extraordinario-info-alumno" style="display: none;">
                                <div class="alert alert-warning border-0 p-3 shadow-sm" style="border-radius: 12px; background: rgba(245, 158, 11, 0.1);">
                                    <h6 class="fw-bold mb-1 text-slate-800" id="lbl-extraordinario-nombre"></h6>
                                    <div class="fs-8 mt-2 text-slate-700">
                                        <span class="d-block">Grupo: <strong id="lbl-extraordinario-grupo"></strong></span>
                                        <span class="d-block">Docente: <strong id="lbl-extraordinario-docente"></strong></span>
                                        <span class="d-block">Materia: <strong id="lbl-extraordinario-materia"></strong></span>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary fw-bold" onclick="printDoc('Acta de Examen Extraordinario', document.getElementById('lbl-extraordinario-nombre').innerText, `${document.getElementById('lbl-extraordinario-materia').innerText} (${document.getElementById('lbl-extraordinario-grupo').innerText})`)">
                                    <i class="fa-solid fa-file-pdf me-2"></i> Generar Formato Extraordinario
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

                        <!-- Selección de Docente, Grupo y Trimestre/Semestre -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Docente</label>
                                <select id="asistenciaDocenteSelect" class="form-select" onchange="actualizarAsistenciaPreview()">
                                    <option value="">Seleccione Docente</option>
                                    <option value="Ing. Juan Carlos Pérez Gómez">Ing. Juan Carlos Pérez Gómez</option>
                                    <option value="Lic. María Elena Rojas Ortiz">Lic. María Elena Rojas Ortiz</option>
                                    <option value="Dr. Alejandro Silva Montes">Dr. Alejandro Silva Montes</option>
                                    <option value="Mtra. Laura Patricia Jiménez">Mtra. Laura Patricia Jiménez</option>
                                    <option value="Ing. Roberto Torres Medina">Ing. Roberto Torres Medina</option>
                                    <option value="Lic. Silvia Elena Castro">Lic. Silvia Elena Castro</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Grupo</label>
                                <select id="asistenciaGrupoSelect" class="form-select" onchange="actualizarAsistenciaPreview()">
                                    <option value="Grupo A">Grupo A</option>
                                    <option value="Grupo B">Grupo B</option>
                                    <option value="Grupo C">Grupo C</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold" id="lbl-asistencia-ciclo-label">Trimestre o Semestre</label>
                                <select id="asistenciaCicloSelect" class="form-select" onchange="actualizarAsistenciaPreview()">
                                    <!-- Cargado dinámicamente por JS (Semestres para BTI, Trimestres para BGNE) -->
                                </select>
                            </div>
                        </div>

                        <div class="col-12 mt-4" id="asistencia-preview-card" style="display: none;">
                            <div class="card p-4 border-0 bg-light shadow-sm" style="border-radius: 12px;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="fw-bold mb-1 text-slate-800" id="lbl-asistencia-preview-docente"></h5>
                                        <div class="fs-8 mt-2 text-slate-700">
                                            <span class="d-block">Materia Asignada: <strong id="lbl-asistencia-preview-materia"></strong></span>
                                            <span class="d-block">Grupo: <strong id="lbl-asistencia-preview-grupo"></strong></span>
                                            <span class="d-block" id="lbl-asistencia-preview-ciclo-texto">Semestre/Trimestre: <strong id="lbl-asistencia-preview-term"></strong></span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <button class="btn btn-primary fw-bold py-2.5 px-4 shadow-sm" onclick="printDoc('Lista de Asistencia', document.getElementById('lbl-asistencia-preview-docente').innerText, `${document.getElementById('lbl-asistencia-preview-materia').innerText} (${document.getElementById('lbl-asistencia-preview-grupo').innerText})`)">
                                            <i class="fa-solid fa-print me-2"></i> Generar Lista (PDF)
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

<!-- MOCK DATA COMPARTIDA (JS) -->
<script>
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

        // Resetear vistas previas de sugerencias y búsquedas
        resetSubmodulosState();

        // Cargar los submódulos (tabs) condicionalmente en la barra lateral
        cargarSubmodulosNav(cct);
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
        
        // Limpiar selects de Asistencia
        const docenteSelect = document.getElementById('asistenciaDocenteSelect');
        const grupoSelect = document.getElementById('asistenciaGrupoSelect');
        if (docenteSelect) docenteSelect.value = '';
        if (grupoSelect) grupoSelect.value = 'Grupo A';
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
            
            button.addEventListener('click', () => {
                // Si cambiamos de pestaña, resetear inputs
                document.querySelectorAll('input').forEach(input => input.value = '');
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

    function simularBusquedaAlumnoKardex(query) {
        buscarAlumnosReal(query, 'kardex-alumno-sugerencia', (alumno) => {
            const fullName = `${alumno.nombre} ${alumno.apPaterno} ${alumno.apMaterno || ''}`.trim();
            document.getElementById('kardexAlumnoSearch').value = fullName;
            
            cargarKardexAlumnoReal(alumno.idAlumno, (data) => {
                const al = data.alumno;
                document.getElementById('lbl-kardex-nombre').innerText = fullName;
                document.getElementById('lbl-kardex-matricula').innerText = `Matrícula: ${al.numeroControl || al.idAlumno} | Plan: ${al.claveCentroTrabajo || cctSeleccionado}`;
                
                // Calculate average
                let sum = 0, count = 0;
                (data.periodos || []).forEach(p => {
                    (p.materias || []).forEach(m => {
                        if (m.calificacion !== null && !isNaN(m.calificacion) && m.es_equivalencia !== true) {
                            sum += parseFloat(m.calificacion);
                            count++;
                        }
                    });
                });
                const prom = count > 0 ? (sum / count).toFixed(1) : '—';
                document.getElementById('lbl-kardex-promedio').innerText = `Promedio General: ${prom}`;
                
                document.getElementById('kardex-info-alumno').style.display = 'block';
            });
        });
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
            const fullName = `${alumno.nombre} ${alumno.apPaterno} ${alumno.apMaterno || ''}`.trim();
            document.getElementById('extraordinarioAlumnoSearch').value = fullName;
            document.getElementById('lbl-extraordinario-nombre').innerText = fullName;
            actualizarExtraordinarioPreview();
            document.getElementById('extraordinario-info-alumno').style.display = 'block';
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
    function mostrarKardexConDatos(data) {
        const modalEl = document.getElementById('modalKardexAlumno');
        if (modalEl && modalEl.parentElement !== document.body) {
            document.body.appendChild(modalEl);
        }
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();

        const contenedor = document.getElementById('contenedorPeriodosKardex');
        const al = data.alumno;
        const periodos = data.periodos || [];

        const isBti = (al.id_centroTrabajo === 2 || al.claveCentroTrabajo === '21PCT0073R' || (al.nombreCentroTrabajo && al.nombreCentroTrabajo.toUpperCase().includes('BTI')));

        document.getElementById('kardexNombreAlumno').textContent = `${al.apPaterno || ''} ${al.apMaterno || ''} ${al.nombre || ''}`.trim().toUpperCase();
        if (al.claveCentroTrabajo) {
            document.getElementById('kardexCCTClave').textContent = `CLAVE CT: ${al.claveCentroTrabajo}`;
        }
        if (al.nombreCentroTrabajo) {
            document.getElementById('kardexCCTNombre').textContent = al.nombreCentroTrabajo.toUpperCase();
        }

        let htmlPeriodos = '';
        periodos.forEach((p, idx) => {
            let htmlMaterias = '';
            (p.materias || []).forEach(m => {
                const isEquiv = m.es_equivalencia === true;
                if (isEquiv) {
                    if (isBti) {
                        htmlMaterias += `
                            <tr data-materia-id="${m.idMateria}" data-is-equivalencia="true">
                                <td class="px-2 py-1 align-middle text-uppercase fw-semibold" style="font-size: 0.78rem; border-color: #cbd5e1;">
                                    ${m.nombreMateria}
                                </td>
                                <td colspan="7" class="text-center px-1 py-1 text-warning fw-bold align-middle" style="font-size: 0.85rem; border-color: #cbd5e1;">
                                    EQUIVALENCIA
                                </td>
                            </tr>
                        `;
                    } else {
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
                    }
                } else {
                    const califVal = m.calificacion !== null ? m.calificacion : '';
                    if (isBti) {
                        const p1 = m.parcial1 !== null ? m.parcial1 : '';
                        const p2 = m.parcial2 !== null ? m.parcial2 : '';
                        const p3 = m.parcial3 !== null ? m.parcial3 : '';
                        const sem = m.semestral !== null ? m.semestral : '';
                        const ext = m.extraordinario !== null ? m.extraordinario : '';
                        const asist = m.asistencias !== null ? m.asistencias : '';
                        const totAsist = m.total_asistencias !== null ? m.total_asistencias : '';

                        htmlMaterias += `
                            <tr data-materia-id="${m.idMateria}" data-nivel="${p.idNivel}">
                                <td class="px-2 py-1 align-middle text-uppercase fw-semibold" style="font-size: 0.78rem; border-color: #000;">
                                    ${m.nombreMateria}
                                </td>
                                <td class="px-1 py-1 align-middle text-center" style="border-color: #000 !important;">
                                    <input type="number" step="0.1" class="form-control form-control-sm text-center fw-bold input-calif-kardex inp-p1" data-materia="${m.idMateria}" data-nivel="${p.idNivel}" data-periodo-idx="${idx}" data-field="parcial1" value="${p1}" style="height: 28px; font-size: 0.82rem; padding: 2px;" oninput="recalcularFilaKardexSemestral(this)">
                                </td>
                                <td class="px-1 py-1 align-middle text-center" style="border-color: #000 !important;">
                                    <input type="number" step="0.1" class="form-control form-control-sm text-center fw-bold input-calif-kardex inp-p2" data-materia="${m.idMateria}" data-nivel="${p.idNivel}" data-periodo-idx="${idx}" data-field="parcial2" value="${p2}" style="height: 28px; font-size: 0.82rem; padding: 2px;" oninput="recalcularFilaKardexSemestral(this)">
                                </td>
                                <td class="px-1 py-1 align-middle text-center" style="border-color: #000 !important;">
                                    <input type="number" step="0.1" class="form-control form-control-sm text-center fw-bold input-calif-kardex inp-p3" data-materia="${m.idMateria}" data-nivel="${p.idNivel}" data-periodo-idx="${idx}" data-field="parcial3" value="${p3}" style="height: 28px; font-size: 0.82rem; padding: 2px;" oninput="recalcularFilaKardexSemestral(this)">
                                </td>
                                <td class="px-1 py-1 align-middle text-center" style="border-color: #000 !important;">
                                    <input type="number" step="0.1" class="form-control form-control-sm text-center fw-bold input-calif-kardex inp-semestral" data-materia="${m.idMateria}" data-nivel="${p.idNivel}" data-periodo-idx="${idx}" data-field="semestral" value="${sem}" style="height: 28px; font-size: 0.82rem; padding: 2px;" oninput="recalcularFilaKardexSemestral(this)">
                                </td>
                                <td class="px-1 py-1 align-middle text-center" style="border-color: #000 !important;">
                                    <input type="number" step="0.1" class="form-control form-control-sm text-center fw-bold input-calif-kardex inp-extraordinario" data-materia="${m.idMateria}" data-nivel="${p.idNivel}" data-periodo-idx="${idx}" data-field="extraordinario" value="${ext}" style="height: 28px; font-size: 0.82rem; padding: 2px;" oninput="recalcularFilaKardexSemestral(this)">
                                </td>
                                <td class="px-1 py-1 align-middle text-center" style="border-color: #000 !important;">
                                    <input type="number" step="0.1" class="form-control form-control-sm text-center fw-bold input-calif-kardex inp-calif-final" data-materia="${m.idMateria}" data-nivel="${p.idNivel}" data-periodo-idx="${idx}" data-field="calificacion" value="${califVal}" readonly style="height: 28px; font-size: 0.82rem; padding: 2px; background-color: #f1f5f9;">
                                </td>
                                <td class="px-1 py-1 align-middle text-center" style="border-color: #000 !important;">
                                    <div class="d-flex align-items-center gap-1 justify-content-center">
                                        <input type="number" class="form-control form-control-sm text-center fw-bold input-calif-kardex inp-asistencias" data-materia="${m.idMateria}" data-nivel="${p.idNivel}" data-periodo-idx="${idx}" data-field="asistencias" value="${asist}" style="height: 28px; font-size: 0.80rem; padding: 2px; width: 28px;" oninput="calcularPromediosKardex()">
                                        <span style="font-size: 0.7rem;">/</span>
                                        <input type="number" class="form-control form-control-sm text-center fw-bold input-calif-kardex inp-total-asistencias" data-materia="${m.idMateria}" data-nivel="${p.idNivel}" data-periodo-idx="${idx}" data-field="total_asistencias" value="${totAsist}" style="height: 28px; font-size: 0.80rem; padding: 2px; width: 28px;" oninput="calcularPromediosKardex()">
                                    </div>
                                </td>
                            </tr>
                        `;
                    } else {
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
                }
            });

            const promInicial = p.promedio !== null ? p.promedio : '—';

            let footerHtml = '';
            if (isBti) {
                footerHtml = `
                    <tfoot>
                        <tr class="bg-light fw-bold" style="border-color: #333; font-size: 0.70rem;">
                            <td class="text-end px-2 py-1 text-uppercase">PROMEDIO</td>
                            <td class="text-center px-1 py-1 prom-p1-val" id="promP1_${idx}">—</td>
                            <td class="text-center px-1 py-1 prom-p2-val" id="promP2_${idx}">—</td>
                            <td class="text-center px-1 py-1 prom-p3-val" id="promP3_${idx}">—</td>
                            <td class="text-center px-1 py-1 prom-sem-val" id="promSem_${idx}">—</td>
                            <td class="text-center px-1 py-1 prom-ext-val" id="promExt_${idx}">—</td>
                            <td class="text-center px-1 py-1 text-primary fw-bold prom-periodo-val" id="promPeriodo_${idx}">${promInicial}</td>
                            <td class="text-center px-1 py-1" style="font-size: 0.65rem;" id="totalAsistPeriodo_${idx}">—</td>
                        </tr>
                `;
            } else {
                footerHtml = `
                    <tfoot>
                        <tr class="bg-light fw-bold" style="border-color: #333; font-size: 0.78rem;">
                            <td class="text-end px-2 py-1 text-uppercase">PROMEDIO</td>
                            <td class="text-center px-1 py-1 text-primary fw-bold prom-periodo-val" id="promPeriodo_${idx}">${promInicial}</td>
                        </tr>
                `;
            }

            if (idx === 4) { // 5to periodo
                footerHtml += `
                    <tr class="fw-bold" style="border-color: #333; font-size: 0.8rem; background: #e2e8f0;">
                        <td class="${isBti ? 'text-end' : 'text-end'} px-2 py-1 text-uppercase" colspan="${isBti ? '6' : '1'}">PROMEDIO FINAL</td>
                        <td class="text-center px-1 py-1 fw-bold text-primary" id="kardexPromedioFinal">0.0</td>
                        ${isBti ? '<td style="border: none !important;"></td>' : ''}
                    </tr>
                `;
            } else if (idx === 5) { // 6to periodo (balance visual)
                footerHtml += `
                    <tr style="border-color: transparent; height: 26px;">
                        <td colspan="${isBti ? '8' : '2'}" style="border: none !important; background: transparent;"></td>
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
                                        ${isBti ? `
                                        <th class="px-2 py-1 text-uppercase text-dark" style="border-color: #000 !important;">MATERIA</th>
                                        <th class="px-1 py-1 text-center text-uppercase text-dark" style="width: 42px; border-color: #000 !important;">P1</th>
                                        <th class="px-1 py-1 text-center text-uppercase text-dark" style="width: 42px; border-color: #000 !important;">P2</th>
                                        <th class="px-1 py-1 text-center text-uppercase text-dark" style="width: 42px; border-color: #000 !important;">P3</th>
                                        <th class="px-1 py-1 text-center text-uppercase text-dark" style="width: 42px; border-color: #000 !important;">SEM</th>
                                        <th class="px-1 py-1 text-center text-uppercase text-dark" style="width: 42px; border-color: #000 !important;">EXT</th>
                                        <th class="px-1 py-1 text-center text-uppercase text-dark" style="width: 48px; border-color: #000 !important;">FINAL</th>
                                        <th class="px-1 py-1 text-center text-uppercase text-dark" style="width: 66px; border-color: #000 !important;">ASIST.</th>
                                        ` : `
                                        <th class="px-2 py-1 text-uppercase text-dark" style="border-color: #000 !important;">MATERIA</th>
                                        <th class="px-1 py-1 text-center text-uppercase text-dark" style="width: 80px; border-color: #000 !important;">EVALUACIÓN OBTENIDA</th>
                                        `}
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

        contenedor.innerHTML = htmlPeriodos;
        calcularPromediosKardex();
    }

    window.calcularPromediosKardex = function() {
        const inputs = document.querySelectorAll('.input-calif-kardex');
        const isBti = document.getElementById('kardexCCTNombre')?.textContent.includes('BTI') || document.getElementById('kardexCCTClave')?.textContent.includes('21PCT0073R') || (document.querySelector('.prom-p1-val') !== null);

        const p1Sums = {}; const p1Counts = {};
        const p2Sums = {}; const p2Counts = {};
        const p3Sums = {}; const p3Counts = {};
        const semSums = {}; const semCounts = {};
        const extSums = {}; const extCounts = {};
        const periodosSums = {}; const periodosCounts = {};
        const asistSums = {}; const totAsistSums = {};

        let sumGlobal = 0;
        let countGlobal = 0;

        inputs.forEach(inp => {
            const pIdx = inp.dataset.periodoIdx;
            const field = inp.dataset.field || 'calificacion';
            const valStr = inp.value.trim();

            if (!periodosSums[pIdx]) {
                p1Sums[pIdx] = 0; p1Counts[pIdx] = 0;
                p2Sums[pIdx] = 0; p2Counts[pIdx] = 0;
                p3Sums[pIdx] = 0; p3Counts[pIdx] = 0;
                semSums[pIdx] = 0; semCounts[pIdx] = 0;
                extSums[pIdx] = 0; extCounts[pIdx] = 0;
                periodosSums[pIdx] = 0; periodosCounts[pIdx] = 0;
                asistSums[pIdx] = 0; totAsistSums[pIdx] = 0;
            }

            if (valStr !== '' && !isNaN(valStr)) {
                const num = parseFloat(valStr);
                if (field === 'parcial1') { p1Sums[pIdx] += num; p1Counts[pIdx]++; }
                else if (field === 'parcial2') { p2Sums[pIdx] += num; p2Counts[pIdx]++; }
                else if (field === 'parcial3') { p3Sums[pIdx] += num; p3Counts[pIdx]++; }
                else if (field === 'semestral') { semSums[pIdx] += num; semCounts[pIdx]++; }
                else if (field === 'extraordinario') { extSums[pIdx] += num; extCounts[pIdx]++; }
                else if (field === 'calificacion') { 
                    periodosSums[pIdx] += num; 
                    periodosCounts[pIdx]++; 
                    sumGlobal += num;
                    countGlobal++;
                }
                else if (field === 'asistencias') { asistSums[pIdx] += num; }
                else if (field === 'total_asistencias') { totAsistSums[pIdx] += num; }

                if (field !== 'asistencias' && field !== 'total_asistencias') {
                    if (num < 6.0) {
                        inp.style.setProperty('color', '#dc2626', 'important');
                        inp.style.setProperty('font-weight', '700', 'important');
                    } else {
                        inp.style.setProperty('color', '#1e293b', 'important');
                        inp.style.setProperty('font-weight', '700', 'important');
                    }
                }
            } else if (valStr.toUpperCase() === 'EQUIV.' || valStr.toUpperCase() === 'EQUIVALENCIA') {
                inp.style.setProperty('color', '#d97706', 'important');
                inp.style.setProperty('font-weight', '700', 'important');
            } else {
                inp.style.setProperty('color', '#1e293b', 'important');
                inp.style.setProperty('font-weight', 'normal', 'important');
            }
        });

        Object.keys(periodosSums).forEach(pIdx => {
            const updateAvgEl = (id, sum, count) => {
                const el = document.getElementById(id);
                if (el) {
                    if (count > 0) {
                        const avg = (sum / count).toFixed(1);
                        el.textContent = avg;
                        el.style.setProperty('color', parseFloat(avg) < 6.0 ? '#dc2626' : '#1e6fa8', 'important');
                    } else {
                        el.textContent = '—';
                        el.style.setProperty('color', '#64748b', 'important');
                    }
                }
            };

            if (isBti) {
                updateAvgEl(`promP1_${pIdx}`, p1Sums[pIdx], p1Counts[pIdx]);
                updateAvgEl(`promP2_${pIdx}`, p2Sums[pIdx], p2Counts[pIdx]);
                updateAvgEl(`promP3_${pIdx}`, p3Sums[pIdx], p3Counts[pIdx]);
                updateAvgEl(`promSem_${pIdx}`, semSums[pIdx], semCounts[pIdx]);
                updateAvgEl(`promExt_${pIdx}`, extSums[pIdx], extCounts[pIdx]);
                
                const asistEl = document.getElementById(`totalAsistPeriodo_${pIdx}`);
                if (asistEl) {
                    asistEl.textContent = `${asistSums[pIdx]} / ${totAsistSums[pIdx]}`;
                }
            }
            
            updateAvgEl(`promPeriodo_${pIdx}`, periodosSums[pIdx], periodosCounts[pIdx]);
        });

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

    window.recalcularFilaKardexSemestral = function(inputEl) {
        const tr = inputEl.closest('tr');
        if (!tr) return;

        const p1Inp = tr.querySelector('.inp-p1');
        const p2Inp = tr.querySelector('.inp-p2');
        const p3Inp = tr.querySelector('.inp-p3');
        const semInp = tr.querySelector('.inp-semestral');
        const extInp = tr.querySelector('.inp-extraordinario');
        const pFinalInp = tr.querySelector('.inp-calif-final');

        const v1 = parseFloat(p1Inp.value);
        const v2 = parseFloat(p2Inp.value);
        const v3 = parseFloat(p3Inp.value);

        const partialsFilled = !isNaN(v1) && !isNaN(v2) && !isNaN(v3);

        if (partialsFilled) {
            const sumPartials = v1 + v2 + v3;
            if (sumPartials < 18) {
                semInp.value = "";
                semInp.disabled = true;
                semInp.placeholder = "N/A";
                
                extInp.disabled = false;
                extInp.placeholder = "0.0";
                
                const extVal = parseFloat(extInp.value);
                if (!isNaN(extVal)) {
                    pFinalInp.value = Math.min(extVal, 7.0).toFixed(1);
                } else {
                    pFinalInp.value = "";
                }
            } else {
                semInp.disabled = false;
                semInp.placeholder = "0.0";
                
                extInp.value = "";
                extInp.disabled = true;
                extInp.placeholder = "N/A";

                const semVal = parseFloat(semInp.value);
                if (!isNaN(semVal)) {
                    pFinalInp.value = ((v1 + v2 + v3 + semVal) / 4).toFixed(1);
                } else {
                    pFinalInp.value = "";
                }
            }
        } else {
            semInp.disabled = false;
            extInp.disabled = false;
            
            let vals = [];
            if (!isNaN(v1)) vals.push(v1);
            if (!isNaN(v2)) vals.push(v2);
            if (!isNaN(v3)) vals.push(v3);
            
            if (vals.length > 0) {
                pFinalInp.value = (vals.reduce((a, b) => a + b, 0) / vals.length).toFixed(1);
            } else {
                pFinalInp.value = "";
            }
        }

        const checkRed = (inp) => {
            if (!inp) return;
            const v = parseFloat(inp.value);
            if (!isNaN(v) && v < 6.0) {
                inp.style.color = '#dc2626';
            } else {
                inp.style.color = '#1e293b';
            }
        };
        checkRed(p1Inp);
        checkRed(p2Inp);
        checkRed(p3Inp);
        checkRed(semInp);
        checkRed(extInp);
        checkRed(pFinalInp);

        calcularPromediosKardex();
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

            const p1Inp = tr.querySelector('.inp-p1');
            const p2Inp = tr.querySelector('.inp-p2');
            const p3Inp = tr.querySelector('.inp-p3');
            const semInp = tr.querySelector('.inp-semestral');
            const extInp = tr.querySelector('.inp-extraordinario');
            const finalInp = tr.querySelector('.inp-calif-final') || tr.querySelector('.input-calif-kardex');

            if (!finalInp) return;

            const valStr = finalInp.value.trim();
            let calif = null;
            if (valStr !== '' && !isNaN(valStr)) {
                calif = parseFloat(valStr);
            }

            const dataObj = {
                idMateria: parseInt(idMateria),
                id_nivel_academico: parseInt(idNivel || finalInp.dataset.nivel),
                calificacion: calif,
                tipoAcreditacion: 'ORDINARIO'
            };

            if (p1Inp) dataObj.parcial1 = p1Inp.value !== "" ? parseFloat(p1Inp.value) : null;
            if (p2Inp) dataObj.parcial2 = p2Inp.value !== "" ? parseFloat(p2Inp.value) : null;
            if (p3Inp) dataObj.parcial3 = p3Inp.value !== "" ? parseFloat(p3Inp.value) : null;
            if (semInp) dataObj.semestral = semInp.value !== "" ? parseFloat(semInp.value) : null;
            if (extInp) dataObj.extraordinario = extInp.value !== "" ? parseFloat(extInp.value) : null;
            
            const asistInp = tr.querySelector('.inp-asistencias');
            const totAsistInp = tr.querySelector('.inp-total-asistencias');
            if (asistInp) dataObj.asistencias = asistInp.value !== "" ? parseInt(asistInp.value) : null;
            if (totAsistInp) dataObj.total_asistencias = totAsistInp.value !== "" ? parseInt(totAsistInp.value) : null;

            calificaciones.push(dataObj);
        });

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Guardando...';

        fetch(`/alumnos/${idAlumnoKardexActual}/calificaciones`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ calificaciones: calificaciones })
        })
        .then(r => r.json())
        .then(resp => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> Guardar Calificaciones';

            if (resp.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Kárdex Actualizado',
                    text: 'Las calificaciones se han guardado exitosamente.',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                if (datosKardexActual) {
                    datosKardexActual.periodos.forEach((p, pIdx) => {
                        p.materias.forEach(m => {
                            const califFound = calificaciones.find(c => c.idMateria === m.idMateria && c.id_nivel_academico === p.idNivel);
                            if (califFound) {
                                m.parcial1 = califFound.parcial1;
                                m.parcial2 = califFound.parcial2;
                                m.parcial3 = califFound.parcial3;
                                m.semestral = califFound.semestral;
                                m.extraordinario = califFound.extraordinario;
                                m.asistencias = califFound.asistencias;
                                m.total_asistencias = califFound.total_asistencias;
                                
                                let finalVal = m.calificacion;
                                const v1 = parseFloat(m.parcial1);
                                const v2 = parseFloat(m.parcial2);
                                const v3 = parseFloat(m.parcial3);
                                if (!isNaN(v1) && !isNaN(v2) && !isNaN(v3)) {
                                    if ((v1+v2+v3) < 18) {
                                        const ext = parseFloat(m.extraordinario);
                                        finalVal = !isNaN(ext) ? Math.min(ext, 7.0) : null;
                                    } else {
                                        const sem = parseFloat(m.semestral);
                                        finalVal = !isNaN(sem) ? (v1+v2+v3+sem)/4 : null;
                                    }
                                }
                                m.calificacion = finalVal;
                            }
                        });
                    });
                    
                    let sum = 0, count = 0;
                    datosKardexActual.periodos.forEach(p => {
                        p.materias.forEach(m => {
                            if (m.calificacion !== null && !isNaN(m.calificacion) && m.es_equivalencia !== true) {
                                sum += parseFloat(m.calificacion);
                                count++;
                            }
                        });
                    });
                    const prom = count > 0 ? (sum / count).toFixed(1) : '—';
                    const lblKProm = document.getElementById('lbl-kardex-promedio');
                    if (lblKProm) lblKProm.innerText = `Promedio General: ${prom}`;
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: resp.error || resp.message || 'Error al guardar calificaciones'
                });
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> Guardar Calificaciones';
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de comunicación al guardar calificaciones'
            });
        });
    };

    window.imprimirKardex = function() {
        if (!datosKardexActual) {
            Swal.fire({
                icon: 'warning',
                title: 'No hay datos cargados',
                text: 'Por favor, busque un alumno primero.',
                confirmButtonColor: '#0284c7'
            });
            return;
        }
        const al = datosKardexActual.alumno;
        const cctClave = al.claveCentroTrabajo || '21PCT0073R';
        const cctNombre = al.nombreCentroTrabajo || 'BACHILLERATO TECNOLÓGICO INTERAMERICANO';
        const alumnoNombre = `${al.apPaterno || ''} ${al.apMaterno || ''} ${al.nombre || ''}`.trim().toUpperCase();
        
        let sumGlobal = 0;
        let countGlobal = 0;

        const periodosData = [];
        (datosKardexActual.periodos || []).forEach((p, idx) => {
            const rows = [];
            let sumPeriodo = 0;
            let countPeriodo = 0;
            
            (p.materias || []).forEach(m => {
                const isEquiv = m.es_equivalencia === true;
                const p1 = m.parcial1 !== null ? m.parcial1 : '';
                const p2 = m.parcial2 !== null ? m.parcial2 : '';
                const p3 = m.parcial3 !== null ? m.parcial3 : '';
                const sem = m.semestral !== null ? m.semestral : '';
                const ext = m.extraordinario !== null ? m.extraordinario : '';
                const calif = m.calificacion !== null ? m.calificacion : '';

                if (m.calificacion !== null && !isEquiv) {
                    sumPeriodo += parseFloat(m.calificacion);
                    countPeriodo++;
                    sumGlobal += parseFloat(m.calificacion);
                    countGlobal++;
                }

                rows.push({
                    materia: m.nombreMateria,
                    isEquiv: isEquiv,
                    p1: p1 !== '' ? parseFloat(p1).toFixed(1) : '—',
                    p2: p2 !== '' ? parseFloat(p2).toFixed(1) : '—',
                    p3: p3 !== '' ? parseFloat(p3).toFixed(1) : '—',
                    semestral: sem !== '' ? parseFloat(sem).toFixed(1) : '—',
                    extraordinario: ext !== '' ? parseFloat(ext).toFixed(1) : '—',
                    calificacion: calif !== '' ? parseFloat(calif).toFixed(1) : '—',
                    esReprobatoria: calif !== '' && parseFloat(calif) < 6.0
                });
            });

            periodosData.push({
                titulo: p.nombrePeriodo,
                promedio: countPeriodo > 0 ? (sumPeriodo / countPeriodo).toFixed(1) : '—',
                promReprobatorio: countPeriodo > 0 && (sumPeriodo / countPeriodo) < 6.0,
                materias: rows
            });
        });

        const promFinal = countGlobal > 0 ? (sumGlobal / countGlobal).toFixed(1) : '—';
        const isBti = (al.id_centroTrabajo === 2 || al.claveCentroTrabajo === '21PCT0073R' || (al.nombreCentroTrabajo && al.nombreCentroTrabajo.toUpperCase().includes('BTI')));

        function renderTablaPeriodo(p, is5to, is6to) {
            if (!p) return '';
            let filasHtml = '';
            
            if (isBti) {
                p.materias.forEach(m => {
                    if (m.isEquiv) {
                        filasHtml += `
                            <tr>
                                <td style="border: 1px solid #000; padding: 1.2px 4px; font-size: 6.8pt; text-align: left; text-transform: uppercase;">
                                    ${m.materia}
                                </td>
                                <td colspan="6" style="border: 1px solid #000; padding: 1.2px 4px; font-size: 7.2pt; text-align: center; font-weight: bold; color: #d97706;">
                                    EQUIVALENCIA
                                </td>
                            </tr>
                        `;
                    } else {
                        const styleColor = m.esReprobatoria ? 'color: #dc2626 !important; font-weight: bold;' : 'color: #000;';
                        filasHtml += `
                            <tr>
                                <td style="border: 1px solid #000; padding: 1.2px 4px; font-size: 6.8pt; text-align: left; text-transform: uppercase;">
                                    ${m.materia}
                                </td>
                                <td style="border: 1px solid #000; padding: 1.2px 4px; font-size: 7.2pt; text-align: center; color: #000;">${m.p1}</td>
                                <td style="border: 1px solid #000; padding: 1.2px 4px; font-size: 7.2pt; text-align: center; color: #000;">${m.p2}</td>
                                <td style="border: 1px solid #000; padding: 1.2px 4px; font-size: 7.2pt; text-align: center; color: #000;">${m.p3}</td>
                                <td style="border: 1px solid #000; padding: 1.2px 4px; font-size: 7.2pt; text-align: center; color: #000;">${m.semestral}</td>
                                <td style="border: 1px solid #000; padding: 1.2px 4px; font-size: 7.2pt; text-align: center; color: #000;">${m.extraordinario}</td>
                                <td style="border: 1px solid #000; padding: 1.2px 4px; font-size: 7.2pt; text-align: center; background-color: #f1f5f9; ${styleColor}">${m.calificacion}</td>
                            </tr>
                        `;
                    }
                });

                let p1Vals = []; let p2Vals = []; let p3Vals = []; let semVals = []; let extVals = []; let finalVals = [];

                p.materias.forEach(m => {
                    if (m.isEquiv) return;
                    const parseVal = (v, arr) => { if (v !== '—' && !isNaN(v)) arr.push(parseFloat(v)); };
                    parseVal(m.p1, p1Vals);
                    parseVal(m.p2, p2Vals);
                    parseVal(m.p3, p3Vals);
                    parseVal(m.semestral, semVals);
                    parseVal(m.extraordinario, extVals);
                    parseVal(m.calificacion, finalVals);
                });

                const getAvg = (arr) => arr.length > 0 ? (arr.reduce((a, b) => a + b, 0) / arr.length).toFixed(1) : '—';
                const avgFinal = getAvg(finalVals);

                let footerExtra = '';
                if (is5to) {
                    footerExtra = `
                        <tr style="font-weight: bold; background: #e8ecf2;">
                            <td style="border: 1.5px solid #000; padding: 2px 4px; font-size: 7.5pt; text-align: right;">PROMEDIO FINAL DEL SEMESTRE</td>
                            <td colspan="6" style="border: 1.5px solid #000; padding: 2px 4px; font-size: 8pt; text-align: center; color: #1e6fa8;">${avgFinal}</td>
                        </tr>
                    `;
                } else if (is6to) {
                    footerExtra = `
                        <tr>
                            <td colspan="7" style="border: 1px solid #000; height: 18px; background: #f8fafc;"></td>
                        </tr>
                    `;
                }

                return `
                    <div style="margin-bottom: 5px;">
                        <div style="font-size: 7.8pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; color: #000;">
                            ${p.titulo}
                        </div>
                        <table style="width: 100%; border-collapse: collapse; border: 1.2px solid #000; font-family: Arial, sans-serif;">
                            <thead>
                                <tr style="background: #e2e8f0; font-size: 6.2pt; font-weight: bold; text-align: center; color: #000;">
                                    <th style="border: 1px solid #000; padding: 2px; text-align: left; width: 180px;">ASIGNATURAS / ÁREAS</th>
                                    <th style="border: 1px solid #000; padding: 2px; width: 32px;">1ER. PAR.</th>
                                    <th style="border: 1px solid #000; padding: 2px; width: 32px;">2DO. PAR.</th>
                                    <th style="border: 1px solid #000; padding: 2px; width: 32px;">3ER. PAR.</th>
                                    <th style="border: 1px solid #000; padding: 2px; width: 32px;">SEM.</th>
                                    <th style="border: 1px solid #000; padding: 2px; width: 32px;">EXT.</th>
                                    <th style="border: 1px solid #000; padding: 2px; width: 45px;">FINAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${filasHtml}
                                <tr style="font-weight: bold; background: #f8fafc;">
                                    <td style="border: 1px solid #000; padding: 2px 4px; font-size: 7.2pt; text-align: right;">PROMEDIO:</td>
                                    <td style="border: 1px solid #000; padding: 2px; text-align: center;">${getAvg(p1Vals)}</td>
                                    <td style="border: 1px solid #000; padding: 2px; text-align: center;">${getAvg(p2Vals)}</td>
                                    <td style="border: 1px solid #000; padding: 2px; text-align: center;">${getAvg(p3Vals)}</td>
                                    <td style="border: 1px solid #000; padding: 2px; text-align: center;">${getAvg(semVals)}</td>
                                    <td style="border: 1px solid #000; padding: 2px; text-align: center;">${getAvg(extVals)}</td>
                                    <td style="border: 1px solid #000; padding: 2px 4px; font-size: 7.2pt; text-align: center; color: #1e6fa8; background: #e2e8f0;">${avgFinal}</td>
                                </tr>
                                ${footerExtra}
                            </tbody>
                        </table>
                    </div>
                `;
            } else {
                p.materias.forEach(m => {
                    const styleColor = m.esReprobatoria ? 'color: #dc2626 !important; font-weight: bold;' : 'color: #000;';
                    filasHtml += `
                        <tr>
                            <td style="border: 1px solid #000; padding: 2.2px 5px; font-size: 8pt; text-align: left; text-transform: uppercase;">
                                ${m.materia}
                            </td>
                            <td style="border: 1px solid #000; padding: 2.2px 5px; font-size: 8.5pt; text-align: center; font-weight: bold; width: 75px; ${styleColor}">
                                ${m.calificacion}
                            </td>
                        </tr>
                    `;
                });

                let footerExtra = '';
                if (is5to) {
                    const finalColor = promFinal !== '—' && !isNaN(promFinal) && parseFloat(promFinal) < 6.0 ? 'color: #dc2626 !important;' : 'color: #000;';
                    footerExtra = `
                        <tr>
                            <td style="border: 1.5px solid #000; padding: 2.5px 5px; font-size: 8.2pt; font-weight: bold; text-align: right; background: #e8ecf2;">
                                PROMEDIO FINAL
                            </td>
                            <td style="border: 1.5px solid #000; padding: 2.5px 5px; font-size: 8.8pt; font-weight: bold; text-align: center; background: #e8ecf2; ${finalColor}">
                                ${promFinal}
                            </td>
                        </tr>
                    `;
                } else if (is6to) {
                    footerExtra = `
                        <tr>
                            <td colspan="2" style="border: 1px solid #000; height: 21px; background: #f8fafc;"></td>
                        </tr>
                    `;
                }

                const promColor = p.promReprobatorio ? 'color: #dc2626 !important;' : 'color: #000;';

                return `
                    <div style="margin-bottom: 8px;">
                        <div style="font-size: 8.2pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; color: #000;">
                            ${p.titulo}
                        </div>
                        <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; font-family: Arial, sans-serif;">
                            <thead>
                                <tr style="background: #ffffff;">
                                    <th style="border: 1px solid #000; padding: 2.5px 5px; font-size: 7.8pt; text-align: left; font-weight: bold; width: 275px;">MATERIA</th>
                                    <th style="border: 1px solid #000; padding: 2.5px 2px; font-size: 7.2pt; text-align: center; font-weight: bold; width: 75px; line-height: 1.1;">EVALUACIÓN<br>OBTENIDA</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${filasHtml}
                                <tr>
                                    <td style="border: 1px solid #000; padding: 2.2px 5px; font-size: 8pt; font-weight: bold; text-align: right;">
                                        PROMEDIO
                                    </td>
                                    <td style="border: 1px solid #000; padding: 2.2px 5px; font-size: 8.5pt; font-weight: bold; text-align: center; ${promColor}">
                                        ${p.promedio}
                                    </td>
                                </tr>
                                ${footerExtra}
                            </tbody>
                        </table>
                    </div>
                `;
            }
        }

        const pageContainerWidth = isBti ? '920px' : '720px';
        const pageColWidth = isBti ? '450px' : '350px';
        const pageSize = isBti ? 'letter landscape' : 'letter portrait';

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

        let signaturesHtml = '';
        if (isBti) {
            signaturesHtml = `
                <div style="margin-top: 15px; display: flex; justify-content: space-between; align-items: flex-end; width: 920px; font-family: Arial, sans-serif;">
                    <div style="width: 380px; text-align: center;">
                        <div style="border-bottom: 1px solid #000; width: 220px; margin: 0 auto 3px auto; height: 30px;"></div>
                        <div style="font-size: 7.5pt; font-weight: bold;">ING. FAUSTO LEYVA FLORES</div>
                        <div style="font-size: 7.0pt; color: #444; text-transform: uppercase;">DIRECTOR</div>
                    </div>
                    <div style="width: 380px; text-align: center; font-size: 8pt; font-weight: bold; padding-bottom: 10px;">
                        TEZIUTLÁN PUEBLA A ${new Date().toLocaleDateString('es-MX', {day: 'numeric', month: 'long', year: 'numeric'}).toUpperCase()}
                    </div>
                </div>
            `;
        }

        const win = window.open('', '', 'height=850,width=1100');
        win.document.write(`
            <html>
                <head>
                    <title>Kárdex de Calificaciones - ${alumnoNombre}</title>
                    <style>
                        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; box-sizing: border-box; }
                        @page {
                            size: ${pageSize};
                            margin: 3mm 4mm 2mm 4mm;
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
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px;">
                            <div style="width: 80px; text-align: left;">
                                <img src="/img/logo.png" alt="Logo" style="height: 45px; object-fit: contain;">
                            </div>
                            <div style="width: ${isBti ? '820px' : '630px'}; border: 1.5px solid #10599a; overflow: hidden; border-radius: 3px;">
                                <div style="background: #10599a; color: #ffffff; font-weight: bold; font-size: 10pt; padding: 2.5px 5px; text-align: center; letter-spacing: 0.8px;">
                                    BACHILLERATO INTERAMERICANO
                                </div>
                                <div style="background: #d4ebf9; color: #000000; font-size: 6.8pt; padding: 1.5px 5px; text-align: center; line-height: 1.2;">
                                    <div>Avenida Benito Juárez 901, Colonia Centro Teziutlán Puebla. Tel: 231-3123979</div>
                                    <div style="font-weight: bold;">CLAVE CT: ${cctClave}</div>
                                </div>
                            </div>
                        </div>

                        <!-- TEXTO DIRECCIÓN Y NOMBRE ALUMNO -->
                        <div style="margin-bottom: 4px;">
                            <div style="font-size: 8pt; color: #000;">
                                La Dirección de la escuela <strong>${cctNombre}</strong>
                            </div>
                            <div style="font-size: 7.2pt; color: #333; font-style: italic;">
                                Reporta las siguientes calificaciones obtenidas hasta el momento del alumno(a):
                            </div>
                            <div style="display: inline-block; background: #e8ecf2; border: 1.5px solid #1e293b; border-radius: 20px; padding: 1.5px 22px; margin: 2px 0 5px 0; font-size: 10pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">
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
                        <div style="width: ${pageContainerWidth}; margin: 4px auto 0 auto; background: #5b9bd5; color: #ffffff; font-weight: bold; font-size: 7.5pt; padding: 2px 0; text-align: center; border-radius: 2px;">
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
            win.close();
        }, 400);
    };

    window.imprimirBoletaBTISemestre = function(data, idNivelSemestre, reportesCounts) {
        const al = data.alumno;
        const repCounts = reportesCounts || { 1: 0, 2: 0, 3: 0 };
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

                                <!-- Box Reportes de Indisciplina -->
                                <div style="border: 1.5px solid #000; background: #ffffff; width: 235px; border-radius: 4px; overflow: hidden; display: flex; flex-direction: column; align-items: center; text-align: center; margin-top: -10px;">
                                    <span style="font-size: 6.8pt; font-weight: 800; background: #c2410c; color: #ffffff; width: 100%; padding: 4px 0; text-transform: uppercase; display: block; letter-spacing: 0.5px;">REPORTES POR DISCIPLINA</span>
                                    <span style="font-size: 8pt; font-weight: 800; padding: 8px 5px; color: #0f172a; display: block; word-spacing: 3px;">
                                        PARCIAL 1: <strong style="color: #c2410c; font-size: 9pt;">${repCounts[1] || 0}</strong> &nbsp;|&nbsp; 
                                        PARCIAL 2: <strong style="color: #c2410c; font-size: 9pt;">${repCounts[2] || 0}</strong> &nbsp;|&nbsp; 
                                        PARCIAL 3: <strong style="color: #c2410c; font-size: 9pt;">${repCounts[3] || 0}</strong>
                                    </span>
                                </div>

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
                imprimirBoletaBTISemestre(datosKardexActual, idNivelSemestre);
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

    function actualizarExtraordinarioPreview() {
        const grupo = document.getElementById('extraordinarioGrupoSelect').value;
        const docente = document.getElementById('extraordinarioDocenteSelect').value;
        const materia = document.getElementById('extraordinarioMateriaSelect').value;

        const lblG = document.getElementById('lbl-extraordinario-grupo');
        const lblD = document.getElementById('lbl-extraordinario-docente');
        const lblM = document.getElementById('lbl-extraordinario-materia');

        if (lblG) lblG.innerText = grupo;
        if (lblD) lblD.innerText = docente;
        if (lblM) lblM.innerText = materia;
    }

    const mockMateriaDocente = {
        "Ing. Juan Carlos Pérez Gómez": "Programación Orientada a Objetos",
        "Lic. María Elena Rojas Ortiz": "Administración I",
        "Dr. Alejandro Silva Montes": "Cálculo Diferencial",
        "Mtra. Laura Patricia Jiménez": "Inglés Técnico I",
        "Ing. Roberto Torres Medina": "Base de Datos I",
        "Lic. Silvia Elena Castro": "Derecho Mercantil"
    };

    function actualizarAsistenciaPreview() {
        const docente = document.getElementById('asistenciaDocenteSelect').value;
        const grupo = document.getElementById('asistenciaGrupoSelect').value;
        const ciclo = document.getElementById('asistenciaCicloSelect').value;

        const card = document.getElementById('asistencia-preview-card');

        if (!docente) {
            if (card) card.style.display = 'none';
            return;
        }

        const materia = mockMateriaDocente[docente] || "Materia General";

        const lblD = document.getElementById('lbl-asistencia-preview-docente');
        const lblM = document.getElementById('lbl-asistencia-preview-materia');
        const lblG = document.getElementById('lbl-asistencia-preview-grupo');
        const lblT = document.getElementById('lbl-asistencia-preview-term');
        const lblC = document.getElementById('lbl-asistencia-preview-ciclo-texto');

        if (lblD) lblD.innerText = docente;
        if (lblM) lblM.innerText = materia;
        if (lblG) lblG.innerText = grupo;
        if (lblT) lblT.innerText = ciclo;
        
        if (lblC) {
            lblC.innerHTML = `${cctSeleccionado === 'BTI' ? 'Semestre' : 'Trimestre'}: <strong>${ciclo}</strong>`;
        }

        if (card) card.style.display = 'block';
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
                    
                    list.forEach(rep => {
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
                                    <button class="btn btn-sm btn-light text-primary border" onclick="imprimirFormatoReporte(${JSON.stringify(rep).replace(/"/g, '&quot;')})" title="Imprimir Formato">
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
                            margin: 6mm 10mm 6mm 10mm;
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
                        .separator {
                            text-align: center;
                            font-size: 9pt;
                            font-weight: bold;
                            color: #64748b;
                            margin: 15px 0;
                            border-top: 1px dashed #64748b;
                            padding-top: 15px;
                            position: relative;
                        }
                        .separator-icon {
                            position: absolute;
                            top: -10px;
                            left: 50%;
                            transform: translateX(-50%);
                            background: #fff;
                            padding: 0 10px;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <!-- TALÓN 1 (Escuela) -->
                        <div style="font-size: 7.5pt; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px;">
                            Talón 1 - Expediente Escolar
                        </div>
                        ${renderBloque(rep)}

                        <!-- Divisor de Corte -->
                        <div class="separator">
                            <span class="separator-icon">✂ Cortar aquí</span>
                        </div>

                        <!-- TALÓN 2 (Tutor) -->
                        <div style="font-size: 7.5pt; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px; margin-top: 5px;">
                            Talón 2 - Para el Padre / Tutor
                        </div>
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
    }
</script>

@endsection
