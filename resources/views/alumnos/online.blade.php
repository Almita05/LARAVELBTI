@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/estilosAlumnosOnline.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    /* Forzar SweetAlert2 por encima de modales de Bootstrap */
    .swal2-container {
        z-index: 99999 !important;
    }

    /* Permitir que los menús dropdown floten fuera de la tabla sin ser recortados */
    .online-main-card {
        overflow: visible !important;
    }
    .table-responsive-online,
    .online-main-card .table-responsive {
        overflow: visible !important;
        min-height: 280px;
    }
    .dropdown-menu {
        z-index: 1060 !important;
    }

    /* Estilos ejecutivos para modales */
    .btn-cancelar-modal {
        height: 38px;
        padding: 0 16px;
        border-radius: 6px;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        color: #475569;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-cancelar-modal:hover {
        background: #F8FAFC;
        color: #0F172A;
        border-color: #CBD5E1;
    }
    .btn-guardar-modal {
        height: 38px;
        padding: 0 16px;
        border-radius: 6px;
        background: #1E5B66;
        border: none;
        color: #FFFFFF;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-guardar-modal:hover {
        background: #15454E;
        color: #FFFFFF;
    }
    #inputBuscarAlumnoPresencial:focus,
    #inputPassMoodleNuevo:focus {
        border-color: #1E5B66 !important;
        box-shadow: 0 0 0 3px rgba(30, 91, 102, 0.12) !important;
        outline: none !important;
        background-color: #FFFFFF !important;
    }
</style>

<div class="online-page-container">

    {{-- 1. ENCABEZADO EJECUTIVO --}}
    <div class="online-header">
        <div class="online-header-left">
            <div class="online-header-icon">
                <i class="ti ti-device-desktop"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h1 class="online-header-title">
                        Control Escolar • Modalidad Online
                    </h1>
                    <span class="badge-moodle-header">
                        Moodle LMS Activo
                    </span>
                </div>
                <p class="online-header-subtitle">
                    Seguimiento modular por semanas, registro de colegiaturas y control de inicio trimestral
                </p>
            </div>
        </div>

        <div class="online-header-actions">
            {{-- Botón para consultar la distribución oficial de materias en trimestres --}}
            <button type="button" class="btn-header-secondary" onclick="abrirModalDistribucion()">
                <i class="ti ti-layout-grid" style="color: #1E5B66;"></i>
                <span>Distribución de Materias</span>
            </button>

            {{-- Botón para incorporar nuevo alumno a modalidad online --}}
            <button type="button" class="btn-header-primary" onclick="abrirModalIntegrarAlumno()">
                <i class="ti ti-user-plus"></i>
                <span>+ Integrar Alumno</span>
            </button>
        </div>
    </div>

    {{-- 2. TARJETA PRINCIPAL CON TABLA Y FILTROS --}}
    <div class="online-main-card">
        <div class="online-card-header">
            {{-- Buscador en tiempo real --}}
            <div class="search-box-wrapper">
                <i class="ti ti-search"></i>
                <input type="text" id="inputBuscarOnline" class="search-input" placeholder="Buscar por nombre o matrícula..." oninput="filtrarAlumnosOnline()">
            </div>

            {{-- Filtros Rápidos --}}
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <select id="filtroTrimestreInicio" class="filter-select" onchange="cargarAlumnosOnline()">
                    <option value="">Trimestre Inicio</option>
                    <option value="1">1° Trimestre</option>
                    <option value="2">2° Trimestre</option>
                    <option value="3">3° Trimestre</option>
                    <option value="4">4° Trimestre</option>
                    <option value="5">5° Trimestre</option>
                    <option value="6">6° Trimestre</option>
                </select>

                <select id="filtroEstatusPago" class="filter-select" onchange="filtrarAlumnosOnline()">
                    <option value="">Estatus Pago</option>
                    <option value="AL_CORRIENTE">Al Corriente</option>
                    <option value="POR_VENCER">Por Vencer</option>
                    <option value="VENCIDO">Atrasado / Vencido</option>
                </select>

                <button type="button" class="btn-filter-refresh" onclick="cargarAlumnosOnline()" title="Actualizar lista">
                    <i class="ti ti-refresh"></i>
                </button>
            </div>
        </div>

        {{-- Tabla de Alumnos Online --}}
        <div class="table-responsive table-responsive-online">
            <table class="table-alumnos-online">
                <thead>
                    <tr>
                        <th style="width: 100px;">ID</th>
                        <th>Estudiante</th>
                        <th style="width: 110px; text-align: center;">Estatus</th>
                        <th style="width: 130px; text-align: center;">Trimestre Inicio</th>
                        <th style="width: 130px; text-align: center;">Trimestre Actual</th>
                        <th style="width: 130px; text-align: center;">Semanas Pagadas</th>
                        <th style="width: 130px; text-align: center;">Estatus Pago</th>
                        <th style="width: 70px; text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbodyAlumnosOnline">
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="ti ti-loader animate-spin me-2"></i> Cargando alumnos en modalidad online...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ========================================================
     MODAL 1: DISTRIBUCIÓN OFICIAL DE MATERIAS EN TRIMESTRES
======================================================== --}}
<div class="modal fade" id="modalDistribucionMaterias" data-bs-focus="false" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden; border: 1px solid #E2E8F0;">
            <div class="modal-header-executive">
                <div class="modal-header-executive-left">
                    <div class="modal-icon-box">
                        <i class="ti ti-layout-grid"></i>
                    </div>
                    <div>
                        <h5 class="modal-title-text">Distribución Oficial de Materias por Trimestre</h5>
                        <p class="modal-subtitle-text">Esquema modular oficial BGNE • 3 Bloques Mensuales (4 Semanas c/u)</p>
                    </div>
                </div>
                <button type="button" class="btn-modal-close" data-bs-dismiss="modal">
                    <i class="ti ti-x" style="font-size: 1.1rem;"></i>
                </button>
            </div>

            <div class="modal-body-executive">
                {{-- Banner informativo de mapeo con Moodle --}}
                <div class="alert alert-light border d-flex align-items-center justify-content-between p-2 px-3 mb-3" style="border-radius: 8px; border-color: #CBE4E8 !important; background: #F0F7F8 !important; font-size: 0.8125rem; color: #16464F;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ti ti-brand-moodle fs-4" style="color: #1E5B66;"></i>
                        <span><strong>Vinculación con Moodle LMS:</strong> Haz clic en el botón de cada materia (ej. <code>+ Asignar Moodle</code> o <code>Moodle: #ID</code>) para registrar o cambiar su ID de curso. El sistema enrolará automáticamente a los estudiantes a estos cursos según sus pagos.</span>
                    </div>
                </div>

                {{-- Selector de Trimestres (1° al 6°) --}}
                <div class="d-flex justify-content-center gap-2 mb-4 flex-wrap" id="tabsTrimestreMatriz">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" onclick="mostrarMatrizTrimestre(1)" id="btnTabTrim1">1° Trimestre</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" onclick="mostrarMatrizTrimestre(2)" id="btnTabTrim2">2° Trimestre</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" onclick="mostrarMatrizTrimestre(3)" id="btnTabTrim3">3° Trimestre</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" onclick="mostrarMatrizTrimestre(4)" id="btnTabTrim4">4° Trimestre</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" onclick="mostrarMatrizTrimestre(5)" id="btnTabTrim5">5° Trimestre</button>
                    <button type="button" class="btn btn-sm btn-primary px-3" onclick="mostrarMatrizTrimestre(6)" id="btnTabTrim6">6° Trimestre </button>
                </div>

                {{-- Contenedor dinámico de la matriz del trimestre seleccionado --}}
                <div id="contenedorMatrizTrimestre">
                    <!-- Cargado dinámicamente por JS -->
                </div>
            </div>

            <div class="modal-footer-executive">
                <button type="button" class="btn btn-sm btn-secondary px-4" data-bs-dismiss="modal" style="border-radius: 6px;">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ========================================================
     MODAL 2: REGISTRAR PAGO DE COLEGIATURA ONLINE
======================================================== --}}
<div class="modal fade" id="modalRegistrarPagoOnline" data-bs-focus="false" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden; border: 1px solid #E2E8F0;">
            <div class="modal-header-executive">
                <div class="modal-header-executive-left">
                    <div class="modal-icon-box">
                        <i class="ti ti-receipt"></i>
                    </div>
                    <div>
                        <h5 class="modal-title-text">Registrar Pago de Colegiatura</h5>
                        <p class="modal-subtitle-text" id="lblPagoAlumnoNombre">Alumno: -</p>
                    </div>
                </div>
                <button type="button" class="btn-modal-close" data-bs-dismiss="modal">
                    <i class="ti ti-x" style="font-size: 1.1rem;"></i>
                </button>
            </div>

            <form id="formRegistroPagoOnline" onsubmit="guardarPagoOnline(event)">
                <input type="hidden" id="pagoAlumnoId">
                <div class="modal-body-executive">
                    {{-- 1. Resumen Ejecutivo del Alumno --}}
                    <div class="p-3 rounded-3 mb-3" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge" style="background: #E2E8F0; color: #0F172A; font-weight: 600; font-size: 0.75rem;" id="lblPagoTrimestreActual">2° Trimestre</span>
                                <span class="text-muted small">• Cobro: <strong class="text-dark" id="lblPagoDiaCobro">SÁBADO</strong></span>
                            </div>
                            <div class="small">
                                <span class="text-muted">Vence:</span>
                                <strong class="font-monospace text-dark ms-1" id="lblPagoFechaProximo">2026-11-25</strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between pt-2 border-top" style="border-color: #E2E8F0 !important; font-size: 0.78rem;">
                            <span class="text-muted">Avance actual: <strong class="text-dark" id="lblPagoAvanceMesActual">Mes 1 • Semana 2 de 4</strong></span>
                            <span class="text-truncate text-secondary ms-2" style="max-width: 220px;" id="lblPagoMateriasActuales">-</span>
                        </div>
                    </div>

                    {{-- 2. Monto del Pago y Semanas que Cubre (Directamente relacionados en la misma fila) --}}
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark mb-1">
                                Monto Pagado ($) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted" style="border-color: #E2E8F0; font-size: 0.85rem;">$</span>
                                <input type="number" step="0.01" class="form-control font-monospace fw-bold" id="inputPagoMonto" placeholder="350.00" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark mb-1">
                                Semanas que cubre <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control font-monospace fw-bold" id="inputPagoSemanas" min="1" max="52" value="1" required oninput="actualizarCalculoPagoOnline(this.value)" onchange="actualizarCalculoPagoOnline(this.value)" style="height: 38px;">
                        </div>
                    </div>

                    {{-- 3. Resumen de Cobertura con este Pago --}}
                    <div class="p-3 rounded-3 mb-3" style="background: #F0FDF4; border: 1px solid #BBF7D0;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 32px; height: 32px; border-radius: 6px; background: #DCFCE7; color: #15803D; display: flex; align-items: center; justify-content: center;">
                                    <i class="ti ti-check" style="font-size: 1.1rem;"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 0.84rem;">
                                        Cubre: <span id="lblProySemanasCubiertas" class="text-success">Semana 3</span>
                                    </div>
                                    <div class="text-muted" style="font-size: 0.72rem;" id="lblProyNivelResultante">
                                        Mes 1 (Semana 3 de 4 cubiertas)
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="text-muted d-block" style="font-size: 0.68rem; text-transform: uppercase; font-weight: 600;">Próximo Pago</span>
                                <span class="fw-bold font-monospace text-dark" style="font-size: 0.875rem;" id="lblProyNuevaFechaPago">02/12/2026</span>
                            </div>
                        </div>
                        <div class="mt-2 pt-2 border-top text-muted d-flex align-items-center gap-1" style="border-color: #DCFCE7 !important; font-size: 0.72rem;">
                            <i class="ti ti-book text-success"></i>
                            <span>Materias activas:</span>
                            <span class="text-dark fw-medium text-truncate" id="lblProyMateriasAsignadas">-</span>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Método de Pago</label>
                            <select class="form-select" id="inputPagoMetodo">
                                <option value="TRANSFERENCIA" selected>Transferencia</option>
                                <option value="EFECTIVO">Efectivo</option>
                                <option value="DEPOSITO">Depósito</option>
                                <option value="TARJETA">Tarjeta</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Fecha de Pago</label>
                            <input type="date" class="form-control" id="inputPagoFecha">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Folio / Ticket</label>
                            <input type="text" class="form-control font-monospace" id="inputPagoFolio" placeholder="Auto-generado">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Observaciones</label>
                            <input type="text" class="form-control" id="inputPagoObservaciones" placeholder="Opcional (ej. Colegiatura puntual)">
                        </div>
                    </div>
                </div>

                <div class="modal-footer-executive">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4" id="btnGuardarPagoOnline" style="background: #1E5B66; border-color: #1E5B66;">
                        <i class="ti ti-check me-1"></i> Confirmar Pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ========================================================
     MODAL 3: FICHA DE MÁS DETALLES / AVANCE ACADÉMICO
======================================================== --}}
<div class="modal fade" id="modalDetallesAlumnoOnline" data-bs-focus="false" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden; border: 1px solid #E2E8F0;">
            <div class="modal-header-executive">
                <div class="modal-header-executive-left">
                    <div class="modal-icon-box">
                        <i class="ti ti-id-badge-2"></i>
                    </div>
                    <div>
                        <h5 class="modal-title-text" id="lblDetalleNombre">Ficha del Estudiante Online</h5>
                        <p class="modal-subtitle-text" id="lblDetalleSubtitulo">Matrícula: - • Grupo: -</p>
                    </div>
                </div>
                <button type="button" class="btn-modal-close" data-bs-dismiss="modal">
                    <i class="ti ti-x" style="font-size: 1.1rem;"></i>
                </button>
            </div>

            <div class="modal-body-executive">
                {{-- Banner Resumen del Alumno --}}
                <div class="bg-white p-3 rounded-3 border mb-4 shadow-sm" style="border-color: #E2E8F0 !important;">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-3">
                            <span class="text-muted small d-block">Trimestre de Inicio:</span>
                            <span class="badge-trimestre fs-6" id="badgeDetalleTrimInicio">1° Trimestre</span>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted small d-block">Trimestre Actual Calculado:</span>
                            <span class="badge-trimestre fs-6" style="background: #F0FDF4; color: #166534; border-color: #BBF7D0;" id="badgeDetalleTrimActual">1° Trimestre</span>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted small d-block">Total Semanas Pagadas:</span>
                            <span class="badge-semanas fs-6" id="badgeDetalleSemanas">0 Semanas</span>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted small d-block">Usuario Moodle:</span>
                            <span class="font-monospace fw-bold text-dark" id="lblDetalleMoodleUser">-</span>
                        </div>
                    </div>
                </div>

                {{-- Navegación de Pestañas: 1. Avance por Materias | 2. Historial de Pagos --}}
                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabAvanceMaterias" type="button">
                            <i class="ti ti-books me-1"></i> Avance por Materias y Semanas
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabHistorialPagos" type="button">
                            <i class="ti ti-history me-1"></i> Historial de Recibos y Pagos
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    {{-- Pestaña 1: Avance por Materias --}}
                    <div class="tab-pane fade show active" id="tabAvanceMaterias">
                        <div id="contenedorProgresoMaterias">
                            <!-- Cargado por JS -->
                        </div>
                    </div>

                    {{-- Pestaña 2: Historial de Pagos --}}
                    <div class="tab-pane fade" id="tabHistorialPagos">
                        <div class="bg-white rounded-3 border overflow-hidden" style="border-color: #E2E8F0 !important;">
                            <table class="table table-hover mb-0" style="font-size: 0.8125rem;">
                                <thead style="background: #F8FAFC;">
                                    <tr>
                                        <th>Folio / Ticket</th>
                                        <th>Fecha Pago</th>
                                        <th>Semanas Cubiertas</th>
                                        <th>Trimestre / Mes</th>
                                        <th>Monto</th>
                                        <th>Método</th>
                                        <th>Registrado Por</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyHistorialPagosAlumno">
                                    <tr>
                                        <td colspan="7" class="text-center py-3 text-muted">Sin pagos registrados.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer-executive">
                <button type="button" class="btn btn-sm btn-secondary px-4" data-bs-dismiss="modal" style="border-radius: 6px;">
                    Cerrar Ficha
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ========================================================
     MODAL 4: CONFIGURAR MODALIDAD DE ESTUDIO / INTEGRAR ALUMNO ONLINE
======================================================== --}}
<div class="modal fade" id="modalIntegrarAlumnoOnline" data-bs-focus="false" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden; border: 1px solid #E2E8F0; background: #FFFFFF;">
            <div class="modal-header-executive" style="background: #FFFFFF; border-bottom: 1px solid #E2E8F0; padding: 16px 20px;">
                <div class="modal-header-executive-left">
                    <div class="modal-icon-box">
                        <i class="ti ti-device-laptop"></i>
                    </div>
                    <div>
                        <h5 class="modal-title-text">Configurar Modalidad de Estudio</h5>
                        <p class="modal-subtitle-text" style="color: #64748B; font-size: 0.8125rem; margin-top: 2px;">Integrar estudiante a modalidad en línea y habilitar Moodle</p>
                    </div>
                </div>
                <button type="button" class="btn-modal-close" data-bs-dismiss="modal" style="color: #64748B;">
                    <i class="ti ti-x" style="font-size: 1.1rem;"></i>
                </button>
            </div>

            <form id="formIntegrarAlumno" onsubmit="guardarIntegracionOnline(event)">
                <div class="modal-body-executive" style="padding: 20px;">
                    {{-- 1. Buscador del Alumno Presencial --}}
                    <div class="mb-3" id="bloqueBuscarAlumnoPresencial">
                        <label for="inputBuscarAlumnoPresencial" style="font-size: 0.75rem; font-weight: 600; color: #334155; display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                            Buscar Estudiante Presencial <span class="text-danger">*</span>
                        </label>
                        <div style="position: relative; display: flex; align-items: center; width: 100%;">
                            <i class="ti ti-search" style="position: absolute; left: 12px; color: #94A3B8; font-size: 16px; pointer-events: none;"></i>
                            <input type="text" class="form-control" id="inputBuscarAlumnoPresencial" placeholder="Escribe nombre o matrícula..." onkeyup="buscarPresencialesEnVivo(this.value)" style="width: 100%; height: 38px; padding: 0 12px 0 36px; border: 1px solid #E2E8F0; border-radius: 6px; font-size: 0.875rem; color: #0F172A; transition: all 0.15s;">
                        </div>
                        <div id="listaResultadosPresenciales" class="list-group mt-2 border rounded-3 overflow-hidden shadow-sm" style="display: none; max-height: 180px; overflow-y: auto;">
                        </div>
                    </div>

                    {{-- 2. Card del Alumno Seleccionado --}}
                    <input type="hidden" id="integrarAlumnoId" required>
                    <div id="boxAlumnoSeleccionado" class="mb-3" style="display: none; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 12px 16px;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span style="font-size: 0.7rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 2px;">ALUMNO:</span>
                                <span class="fw-bold text-dark fs-6" id="lblAlumnoSeleccionadoNombre">-</span>
                                <div style="font-size: 0.75rem; color: #64748B; margin-top: 2px;" id="lblAlumnoSeleccionadoInfo">Grupo asignado: -</div>
                            </div>
                            <button type="button" class="btn btn-sm btn-link text-decoration-none p-0" onclick="cambiarAlumnoSeleccionado()" title="Cambiar alumno" style="font-size: 0.75rem; color: #1E5B66; font-weight: 500;">
                                <i class="ti ti-pencil me-1"></i>Cambiar
                            </button>
                        </div>
                    </div>

                    {{-- 3. Modalidad de Asistencia --}}
                    <div class="mb-3">
                        <label style="font-size: 0.75rem; font-weight: 600; color: #334155; display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                            <i class="ti ti-device-laptop" style="color: #64748B; font-size: 15px;"></i> Modalidad de Asistencia
                        </label>
                        <input type="text" class="form-control" value="Modalidad en Línea (Online)" readonly style="height: 38px; border: 1px solid #E2E8F0; border-radius: 6px; padding: 0 12px; font-size: 0.875rem; color: #475569; background-color: #F8FAFC; width: 100%;">
                    </div>

                    {{-- 4. Trimestre en que Comienza --}}
                    <div class="mb-3">
                        <label for="selectTrimestreInicioNuevo" style="font-size: 0.75rem; font-weight: 600; color: #334155; display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                            Trimestre en que Comienza <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="selectTrimestreInicioNuevo" required style="height: 38px; border: 1px solid #E2E8F0; border-radius: 6px; padding: 0 32px 0 12px; font-size: 0.875rem; color: #0F172A; background-color: #FFFFFF; width: 100%; appearance: auto;">
                            <option value="1" selected>1° Trimestre (Inicio Regular)</option>
                            <option value="2">2° Trimestre</option>
                            <option value="3">3° Trimestre</option>
                            <option value="4">4° Trimestre</option>
                            <option value="5">5° Trimestre</option>
                            <option value="6">6° Trimestre (Egreso / Módulo Final)</option>
                        </select>
                        <div style="font-size: 0.7rem; color: #64748B; margin-top: 4px;">
                            Indica desde qué nivel curricular iniciará el seguimiento modular de materias.
                        </div>
                    </div>

                    {{-- 5. Día Asignado de Cobro / Vencimiento --}}
                    <div class="mb-3">
                        <label for="selectDiaPagoNuevo" style="font-size: 0.75rem; font-weight: 600; color: #334155; display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                            <i class="ti ti-calendar" style="color: #64748B; font-size: 15px;"></i> Día de Cobro / Vencimiento de Pagos <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="selectDiaPagoNuevo" required style="height: 38px; border: 1px solid #E2E8F0; border-radius: 6px; padding: 0 32px 0 12px; font-size: 0.875rem; color: #0F172A; background-color: #FFFFFF; width: 100%; appearance: auto;">
                            <option value="SABADO" selected>Cada Sábado (Fin de Semana)</option>
                            <option value="DOMINGO">Cada Domingo (Fin de Semana)</option>
                            <option value="LUNES">Cada Lunes (Semanal)</option>
                            <option value="VIERNES">Cada Viernes</option>
                            <option value="MARTES">Cada Martes</option>
                            <option value="MIERCOLES">Cada Miércoles</option>
                            <option value="JUEVES">Cada Jueves</option>
                        </select>
                        <div style="font-size: 0.7rem; color: #64748B; margin-top: 4px;">
                            Define la fecha para el cálculo de sus pagos y recordatorios en su tablero.
                        </div>
                    </div>

                    {{-- 6. Caja de Moodle (Limpia, sin efecto dropzone ni morado) --}}
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-left: 3px solid #1E5B66; border-radius: 6px; padding: 16px; margin-top: 8px;">
                        <div style="font-weight: 600; color: #1E5B66; font-size: 0.875rem; margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
                            <i class="ti ti-school" style="color: #1E5B66; font-size: 18px;"></i>
                            <span>Credenciales Moodle Automáticas</span>
                        </div>
                        <p style="font-size: 0.75rem; color: #475569; margin-bottom: 12px; line-height: 1.4;">
                            Al guardar, el sistema creará o actualizará automáticamente al alumno en <strong>Moodle LMS</strong> y lo enrolará en el curso de su materia.
                        </p>
                        <div class="row g-2">
                            <div class="col-6">
                                <label for="inputMoodleUsernameNuevo" style="font-size: 0.72rem; font-weight: 600; color: #64748B; margin-bottom: 4px; display: block;">Usuario Moodle:</label>
                                <input type="text" id="inputMoodleUsernameNuevo" readonly placeholder="alu..." style="background: #F1F5F9; border: 1px solid #E2E8F0; color: #475569; font-family: 'JetBrains Mono', monospace; font-size: 0.8125rem; height: 36px; padding: 0 12px; border-radius: 6px; width: 100%;">
                            </div>
                            <div class="col-6">
                                <label for="inputPassMoodleNuevo" style="font-size: 0.72rem; font-weight: 600; color: #64748B; margin-bottom: 4px; display: block;">Contraseña:</label>
                                <input type="text" id="inputPassMoodleNuevo" placeholder="Autogenerada (Bti.xxx*)" style="background: #F1F5F9; border: 1px solid #E2E8F0; color: #475569; font-family: 'JetBrains Mono', monospace; font-size: 0.8125rem; height: 36px; padding: 0 12px; border-radius: 6px; width: 100%;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer-executive" style="background: #FFFFFF; border-top: 1px solid #E2E8F0; padding: 14px 20px; display: flex; align-items: center; justify-content: flex-end; gap: 8px;">
                    <button type="button" class="btn-cancelar-modal" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-guardar-modal" id="btnConfirmarIntegracion">
                        <i class="ti ti-check" style="font-size: 16px;"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT JAVASCRIPT DEL MÓDULO --}}
<script>
    let alumnosOnlineGlobal = [];
    let distribucionMateriasGlobal = null;

    document.addEventListener('DOMContentLoaded', function () {
        // Desactivar el focus trap de los modales para permitir escribir libremente en SweetAlert2
        document.querySelectorAll('.modal').forEach(m => {
            m.removeAttribute('tabindex');
            m.setAttribute('data-bs-focus', 'false');
        });

        cargarAlumnosOnline();
        precargarDistribucion();
        document.getElementById('inputPagoFecha').value = new Date().toISOString().split('T')[0];
    });

    // 1. Cargar Alumnos Online desde el servidor
    async function cargarAlumnosOnline() {
        const tbody = document.getElementById('tbodyAlumnosOnline');
        tbody.innerHTML = `<tr><td colspan="8" class="text-center py-4 text-muted"><i class="ti ti-loader animate-spin me-2"></i> Cargando alumnos...</td></tr>`;

        const trimFiltro = document.getElementById('filtroTrimestreInicio').value;
        let url = `/alumnos/online/data`;
        if (trimFiltro) url += `?trimestre=${trimFiltro}`;

        try {
            const res = await fetch(url);
            const data = await res.json();

            if (data.success) {
                alumnosOnlineGlobal = data.alumnos || [];
                actualizarKPIs(alumnosOnlineGlobal);
                renderizarTablaAlumnos(alumnosOnlineGlobal);
            } else {
                tbody.innerHTML = `<tr><td colspan="8" class="text-center py-4 text-danger">Error: ${data.message || 'No se pudieron cargar los datos'}</td></tr>`;
            }
        } catch (e) {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center py-4 text-danger">Error de conexión al cargar alumnos.</td></tr>`;
        }
    }

    // 2. Renderizar Tabla
    function renderizarTablaAlumnos(lista) {
        const tbody = document.getElementById('tbodyAlumnosOnline');
        if (!lista || lista.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="ti ti-inbox fs-2 d-block mb-2 text-secondary opacity-50"></i>
                        No se encontraron estudiantes en Modalidad Online con los filtros aplicados.
                    </td>
                </tr>`;
            return;
        }

        tbody.innerHTML = lista.map(al => {
            // Estatus del alumno
            const statusRaw = (al.statusAlumno || 'ACTIVO').toUpperCase();
            let badgeStatus = `<span class="badge-soft-success"><i class="ti ti-check"></i> Activo</span>`;
            if (statusRaw === 'INACTIVO' || statusRaw === 'BAJA') {
                badgeStatus = `<span class="badge-soft-danger"><i class="ti ti-x"></i> ${statusRaw}</span>`;
            } else if (statusRaw !== 'ACTIVO') {
                badgeStatus = `<span class="badge-soft-neutral">${statusRaw}</span>`;
            }

            // Estatus de pago
            let badgePago = `<span class="badge-soft-success"><i class="ti ti-check"></i> Al corriente</span>`;
            if (al.estatus_pago === 'VENCIDO') {
                badgePago = `<span class="badge-soft-danger"><i class="ti ti-alert-triangle"></i> Vencido</span>`;
            } else if (al.estatus_pago === 'POR_VENCER') {
                badgePago = `<span class="badge-soft-warning"><i class="ti ti-clock"></i> Por vencer</span>`;
            }

            const suspenderToggleVal = (al.estatus_pago === 'VENCIDO') ? 0 : 1;
            const toggleIcon = (al.estatus_pago === 'VENCIDO') ? 'ti-lock-open' : 'ti-lock';
            const toggleTitle = (al.estatus_pago === 'VENCIDO') ? 'Reactivar acceso a Moodle' : 'Suspender acceso a Moodle manualmente';
            const nombreEscapado = (al.nombreCompleto || '').replace(/'/g, "\\'");

            // Item de WhatsApp para el menú de 3 puntos
            let itemWhatsapp = '';
            if (al.celularAlumno) {
                const cleanPhone = al.celularAlumno.replace(/[^0-9]/g, '');
                if (cleanPhone.length >= 10) {
                    const phoneFormatted = (cleanPhone.length === 10) ? `52${cleanPhone}` : cleanPhone;
                    const primerNombre = (al.nombre || '').split(' ')[0] || al.nombreCompleto;
                    const msg = encodeURIComponent(`Hola ${primerNombre}, te contactamos de Control Escolar del Bachillerato Tecnológico Interamericano. Notamos que vas al corriente con tu colegiatura en Modalidad Online, pero tienes ${al.actividades_pendientes} actividades pendientes por subir en Moodle. ¿Te podemos apoyar con alguna duda para que no te rezagues en tus materias?`);
                    itemWhatsapp = `
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="https://wa.me/${phoneFormatted}?text=${msg}" target="_blank">
                                <i class="ti ti-brand-whatsapp text-success" style="font-size: 1.1rem;"></i>
                                <span>Contactar por WhatsApp</span>
                            </a>
                        </li>
                    `;
                }
            }

            return `
                <tr>
                    <td>
                        <span class="font-monospace fw-semibold text-muted" style="font-size: 0.75rem;">${al.numeroControl || ('ID-' + al.idAlumno)}</span>
                    </td>
                    <td>
                        <span style="text-transform: uppercase; font-weight: 600; color: #0F172A; font-size: 0.8125rem;">
                            ${al.nombreCompleto}
                        </span>
                    </td>
                    <td class="text-center">
                        ${badgeStatus}
                    </td>
                    <td class="text-center">
                        <span style="font-weight: 500; color: #0F172A; font-size: 0.8125rem;">${al.trimestre_inicio}° Trimestre</span>
                    </td>
                    <td class="text-center">
                        <span class="badge-soft-neutral">
                            ${al.trimestre_actual}° Trimestre
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge-soft-neutral font-monospace">
                            ${al.semanas_totales_pagadas || 0} sem.
                        </span>
                    </td>
                    <td class="text-center">
                        ${badgePago}
                    </td>
                    <td class="text-center">
                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn-ghost-action" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false" title="Más opciones e información">
                                <i class="ti ti-dots-vertical" style="font-size: 1.15rem;"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border" style="border-radius: 10px; font-size: 0.8125rem; min-width: 230px; z-index: 1060; padding: 6px 0;">
                                <li class="px-3 py-2 bg-light border-bottom mb-1" style="margin-top: -6px; border-radius: 9px 9px 0 0;">
                                    <div class="fw-bold text-dark text-truncate" style="font-size: 0.8125rem;">${al.nombreCompleto}</div>
                                    <div class="text-muted" style="font-size: 0.72rem;">
                                        ${al.numeroControl ? `Matrícula: <strong>${al.numeroControl}</strong><br>` : ''}
                                        ${al.clave_grupo ? `Grupo: ${al.clave_grupo}<br>` : ''}
                                        <i class="ti ti-brand-moodle me-1 text-primary"></i>${al.moodle_username || 'Sin Moodle'}
                                    </div>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:void(0)" onclick="abrirModalRegistrarPago(${al.idAlumno})">
                                        <i class="ti ti-currency-dollar text-success" style="font-size: 1.1rem;"></i>
                                        <span class="fw-medium">Registrar Pago</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:void(0)" onclick="abrirModalDetalle(${al.idAlumno})">
                                        <i class="ti ti-chart-bar text-primary" style="font-size: 1.1rem;"></i>
                                        <span class="fw-medium">Ver Avance Curricular</span>
                                    </a>
                                </li>
                                ${itemWhatsapp}
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:void(0)" onclick="toggleMoodleAccess(${al.idAlumno}, '${nombreEscapado}', ${suspenderToggleVal})">
                                        <i class="ti ${toggleIcon} text-secondary" style="font-size: 1.1rem;"></i>
                                        <span>${toggleTitle}</span>
                                    </a>
                                </li>
                                <li class="border-top mt-1 pt-2 px-3 text-muted" style="font-size: 0.72rem; background: #FAFBFD; margin-bottom: -6px; border-radius: 0 0 9px 9px; padding-bottom: 8px;">
                                    ${al.en_riesgo_academico ? `<div class="text-danger fw-semibold mb-1"><i class="ti ti-alert-triangle me-1"></i>Debe ${al.actividades_pendientes} tareas</div>` : ''}
                                    ${al.celularAlumno ? `<div><i class="ti ti-phone me-1"></i>${al.celularAlumno}</div>` : ''}
                                    ${al.correoAlumno ? `<div><i class="ti ti-mail me-1"></i>${al.correoAlumno}</div>` : ''}
                                    ${al.fecha_proximo_pago ? `<div><i class="ti ti-calendar me-1"></i>Próx: ${al.fecha_proximo_pago} (${al.dia_pago || 'SÁBADO'})</div>` : ''}
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }

    // 3. Filtrar Alumnos Localmente (Búsqueda y Estatus de Pago)
    function filtrarAlumnosOnline() {
        const query = document.getElementById('inputBuscarOnline').value.toLowerCase().trim();
        const estatus = document.getElementById('filtroEstatusPago').value;

        const filtrados = alumnosOnlineGlobal.filter(al => {
            const matchQuery = !query || 
                al.nombreCompleto.toLowerCase().includes(query) ||
                (al.numeroControl && al.numeroControl.toLowerCase().includes(query)) ||
                (al.correoAlumno && al.correoAlumno.toLowerCase().includes(query));

            const matchEstatus = !estatus || (al.estatus_pago === estatus);

            return matchQuery && matchEstatus;
        });

        renderizarTablaAlumnos(filtrados);
    }

    // 4. Actualizar KPIs (si existen en el DOM)
    function actualizarKPIs(lista) {
        const kpiTotal = document.getElementById('kpiTotalOnline');
        if (kpiTotal) kpiTotal.textContent = lista.length;
        const kpiCorr = document.getElementById('kpiAlCorriente');
        if (kpiCorr) kpiCorr.textContent = lista.filter(a => a.estatus_pago !== 'VENCIDO').length;
        const kpiPend = document.getElementById('kpiPendientes');
        if (kpiPend) {
            const pend = lista.filter(a => a.estatus_pago === 'VENCIDO').length;
            kpiPend.textContent = pend;
            kpiPend.style.color = (pend > 0) ? '#DC2626' : '#64748B';
        }
        const kpiRez = document.getElementById('kpiRezago');
        if (kpiRez) {
            const rez = lista.filter(a => a.en_riesgo_academico).length;
            kpiRez.textContent = rez;
            kpiRez.style.color = (rez > 0) ? '#DC2626' : '#64748B';
        }
        const kpiSem = document.getElementById('kpiSemanas');
        if (kpiSem) kpiSem.textContent = lista.reduce((acc, a) => acc + (parseInt(a.semanas_totales_pagadas) || 0), 0);
    }

    // Acceso rápido desde la tarjeta KPI
    function activarFiltroRezago() {
        const select = document.getElementById('filtroRiesgoTareas');
        if (select) {
            select.value = (select.value === 'CON_REZAGO') ? '' : 'CON_REZAGO';
            filtrarAlumnosOnline();
        }
    }

    // 5. Cambiar Trimestre de Inicio
    async function cambiarTrimestreInicio(idAlumno, nuevoTrimestre) {
        try {
            const res = await fetch(`/alumnos/${idAlumno}/trimestre-inicio`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ trimestre_inicio: nuevoTrimestre })
            });

            const data = await res.json();
            if (data.success) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 2500
                });
                cargarAlumnosOnline();
            } else {
                Swal.fire('Error', data.message || 'No se pudo actualizar.', 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'Fallo de conexión.', 'error');
        }
    }

    // 6. Abrir Modal de Registro de Pago con Proyección y Asignación en Moodle
    let alumnoPagoActual = null;

    function abrirModalRegistrarPago(idAlumno) {
        const al = alumnosOnlineGlobal.find(a => a.idAlumno == idAlumno);
        if (!al) return;
        alumnoPagoActual = al;

        document.getElementById('pagoAlumnoId').value = al.idAlumno;
        document.getElementById('lblPagoAlumnoNombre').textContent = `Alumno: ${al.nombreCompleto} (${al.numeroControl || 'ID ' + al.idAlumno})`;
        document.getElementById('lblPagoTrimestreActual').textContent = `${al.trimestre_actual || 1}° Trimestre`;
        document.getElementById('lblPagoDiaCobro').textContent = al.dia_pago || 'SÁBADO';
        document.getElementById('lblPagoFechaProximo').textContent = al.fecha_proximo_pago || 'No registrada';

        const semPagadas = parseInt(al.semanas_totales_pagadas) || 0;
        const semMes = (semPagadas % 4);
        const semMesTexto = (semMes === 0) ? '0 de 4 cubiertas' : `Semana ${semMes} de 4 cubiertas`;
        document.getElementById('lblPagoAvanceMesActual').textContent = `Mes ${al.mes_actual || 1} • ${semMesTexto}`;

        // Materias en curso
        const trimActual = al.trimestre_actual || 1;
        const mesActual = al.mes_actual || 1;
        let nombresMats = 'Materias del bloque';
        if (distribucionMateriasGlobal && distribucionMateriasGlobal[trimActual]) {
            const matsMes = distribucionMateriasGlobal[trimActual]['mes_' + mesActual] || [];
            if (matsMes.length > 0) {
                nombresMats = matsMes.map(m => m.nombreMateria).join(' • ');
            }
        }
        document.getElementById('lblPagoMateriasActuales').textContent = nombresMats;

        document.getElementById('inputPagoMonto').value = '350.00';
        document.getElementById('inputPagoSemanas').value = 1;
        document.getElementById('inputPagoFolio').value = 'BOL-' + Math.floor(100000 + Math.random() * 900000);
        document.getElementById('inputPagoObservaciones').value = '';
        
        // Asignar fecha actual por defecto
        const hoy = new Date().toISOString().split('T')[0];
        document.getElementById('inputPagoFecha').value = hoy;
        
        // Actualizar proyección y cálculo en vivo
        actualizarCalculoPagoOnline(1);

        new bootstrap.Modal(document.getElementById('modalRegistrarPagoOnline')).show();
    }

    function actualizarCalculoPagoOnline(semanas) {
        semanas = parseInt(semanas) || 1;
        if (semanas < 1) semanas = 1;

        if (!alumnoPagoActual) return;

        const al = alumnoPagoActual;
        const semPrevias = parseInt(al.semanas_totales_pagadas) || 0;
        const semResultantes = semPrevias + semanas;
        const semDesde = semPrevias + 1;
        const semHasta = semResultantes;

        // Texto limpio y humano para las semanas que cubre
        if (semanas === 1) {
            document.getElementById('lblProySemanasCubiertas').textContent = `Semana ${semDesde}`;
        } else if (semanas === 2 && semDesde + 1 === semHasta) {
            document.getElementById('lblProySemanasCubiertas').textContent = `Semanas ${semDesde} y ${semHasta}`;
        } else {
            document.getElementById('lblProySemanasCubiertas').textContent = `Semanas ${semDesde} a ${semHasta} (${semanas} sem.)`;
        }

        // Calcular nivel resultante
        const trimInicio = parseInt(al.trimestre_inicio) || 1;
        const trimResultante = Math.min(6, trimInicio + Math.floor((semResultantes - 1) / 12));
        const mesResultante = Math.min(3, Math.floor(((semResultantes - 1) % 12) / 4) + 1);
        const semMesResultante = ((semResultantes - 1) % 4) + 1;

        document.getElementById('lblProyNivelResultante').textContent = `${trimResultante}° Trimestre • Mes ${mesResultante} (Semana ${semMesResultante} de 4 cubiertas)`;

        // Materias asignadas en Moodle
        let matsAsignadas = [];
        if (distribucionMateriasGlobal) {
            const trimActual = al.trimestre_actual || 1;
            const mesActual = al.mes_actual || 1;
            const mats1 = distribucionMateriasGlobal[trimActual] ? (distribucionMateriasGlobal[trimActual]['mes_' + mesActual] || []) : [];
            matsAsignadas.push(...mats1.map(m => m.nombreMateria));

            if (mesResultante !== mesActual || trimResultante !== trimActual) {
                const mats2 = distribucionMateriasGlobal[trimResultante] ? (distribucionMateriasGlobal[trimResultante]['mes_' + mesResultante] || []) : [];
                for (const m of mats2) {
                    if (!matsAsignadas.includes(m.nombreMateria)) matsAsignadas.push(m.nombreMateria);
                }
            }
        }
        document.getElementById('lblProyMateriasAsignadas').textContent = matsAsignadas.length > 0 ? matsAsignadas.join(' • ') : 'Materias correspondientes al bloque';

        // Nueva fecha de próximo pago
        let fechaBase = new Date();
        if (al.fecha_proximo_pago) {
            const parts = al.fecha_proximo_pago.split('-');
            if (parts.length === 3) {
                const f = new Date(parts[0], parts[1] - 1, parts[2]);
                if (f > fechaBase) fechaBase = f;
            }
        }
        const nuevaFecha = new Date(fechaBase);
        nuevaFecha.setDate(nuevaFecha.getDate() + (semanas * 7));
        const yyyy = nuevaFecha.getFullYear();
        const mm = String(nuevaFecha.getMonth() + 1).padStart(2, '0');
        const dd = String(nuevaFecha.getDate()).padStart(2, '0');
        document.getElementById('lblProyNuevaFechaPago').textContent = `${dd}/${mm}/${yyyy}`;
    }

    // 7. Guardar Pago Online y Asignar Materias en Moodle de forma totalmente automática
    async function guardarPagoOnline(e) {
        e.preventDefault();
        const id = document.getElementById('pagoAlumnoId').value;
        const btn = document.getElementById('btnGuardarPagoOnline');
        btn.disabled = true;
        btn.innerHTML = `<i class="ti ti-loader animate-spin me-1"></i> Registrando pago y asignando en Moodle...`;

        const payload = {
            semanas_pagadas: document.getElementById('inputPagoSemanas').value,
            monto: document.getElementById('inputPagoMonto').value,
            metodo_pago: document.getElementById('inputPagoMetodo').value,
            folio_ticket: document.getElementById('inputPagoFolio').value,
            fecha_pago: document.getElementById('inputPagoFecha').value,
            observaciones: document.getElementById('inputPagoObservaciones').value
        };

        try {
            const res = await fetch(`/alumnos/${id}/registrar-pago-online`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            });

            const data = await res.json();
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('modalRegistrarPagoOnline')).hide();
                Swal.fire({
                    icon: 'success',
                    title: '¡Pago y Asignación de Materias Completado!',
                    html: `
                        <div class="text-start small">
                            <p class="mb-2">${data.message}</p>
                            <div class="p-2 bg-light rounded border text-muted">
                                <strong>Semanas totales acumuladas:</strong> ${data.nuevo_total_semanas} sem.<br>
                                <strong>Nueva fecha de vencimiento:</strong> ${data.fecha_proximo_pago}<br>
                                <span class="text-success fw-bold"><i class="ti ti-circle-check"></i> Acceso y cursos de Moodle activados automáticamente.</span>
                            </div>
                        </div>
                    `,
                    confirmButtonColor: '#1E5B66'
                });
                cargarAlumnosOnline();
            } else {
                Swal.fire('Error', data.message || 'No se pudo registrar el pago.', 'error');
            }
        } catch (err) {
            Swal.fire('Error', 'Fallo de conexión al servidor.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = `<i class="ti ti-check me-1"></i> Confirmar Pago y Asignar Materias en Moodle`;
        }
    }

    // 8. Abrir Modal de Más Detalles / Avance
    let alumnoDetalleActual = null;

    async function abrirModalDetalle(idAlumno) {
        const modal = new bootstrap.Modal(document.getElementById('modalDetallesAlumnoOnline'));
        modal.show();

        const contProgreso = document.getElementById('contenedorProgresoMaterias');
        contProgreso.innerHTML = `<div class="text-center py-5 text-muted"><i class="ti ti-loader animate-spin fs-3 me-2"></i> Cargando avance curricular del alumno...</div>`;

        try {
            const res = await fetch(`/alumnos/${idAlumno}/detalle-online`);
            const data = await res.json();

            if (data.success) {
                const al = data.alumno;
                alumnoDetalleActual = al;
                document.getElementById('lblDetalleNombre').textContent = `${al.nombre} ${al.apPaterno} ${al.apMaterno}`;
                document.getElementById('lblDetalleSubtitulo').textContent = `Matrícula: ${al.numeroControl || 'ID-' + al.idAlumno} • Grupo: ${al.clave_grupo || 'BGNE ONLINE'}`;
                document.getElementById('badgeDetalleTrimInicio').textContent = `${data.trimestre_inicio}° Trimestre`;
                document.getElementById('badgeDetalleTrimActual').textContent = `${data.trimestre_actual}° Trimestre (Mes ${data.mes_actual})`;
                document.getElementById('badgeDetalleSemanas').textContent = `${data.semanas_totales} Semanas Cubiertas`;
                document.getElementById('lblDetalleMoodleUser').textContent = al.moodle_username || 'Sin cuenta Moodle';

                // Renderizar Progreso de Materias
                renderizarProgresoMaterias(data.progreso);

                // Renderizar Historial de Pagos
                renderizarHistorialPagos(data.pagos);
            }
        } catch (e) {
            contProgreso.innerHTML = `<div class="alert alert-danger">Error al cargar datos del estudiante.</div>`;
        }
    }

    function renderizarProgresoMaterias(trimestres) {
        const cont = document.getElementById('contenedorProgresoMaterias');
        if (!trimestres || trimestres.length === 0) {
            cont.innerHTML = `<div class="text-muted text-center py-4">No hay materias asignadas aún.</div>`;
            return;
        }

        // Leyenda explicativa de estados de actividades vs pagos
        const leyendaHtml = `
            <div class="d-flex align-items-center gap-2 mb-3 p-2 px-3 rounded-3 border bg-white flex-wrap shadow-sm" style="font-size: 0.75rem; border-color: #E2E8F0 !important;">
                <span class="text-muted fw-bold me-1">Simbología:</span>
                <span class="semana-chip semana-liberada py-1 px-2"><i class="ti ti-clock"></i> Pagada (Pendiente entrega)</span>
                <span class="semana-chip semana-entregada py-1 px-2"><i class="ti ti-file-check"></i> Actividad Entregada</span>
                <span class="semana-chip semana-calificada py-1 px-2"><i class="ti ti-award"></i> Calificada</span>
                <span class="semana-chip semana-bloqueada py-1 px-2"><i class="ti ti-lock"></i> Bloqueada (Sin pago)</span>
                <span class="text-muted ms-auto small"><i class="ti ti-pointer"></i> Haz clic en una semana liberada para cambiar su estatus o calificar.</span>
            </div>
        `;

        const cardsHtml = trimestres.map(trim => {
            const mesesHtml = trim.meses.map(mes => {
                const materiasHtml = mes.materias.map(mat => {
                    const semanasHtml = mat.semanas.map(sem => {
                        let claseEstatus = 'semana-bloqueada';
                        let icono = 'ti-lock';
                        
                        const esGeoICS = (mat.nombreMateria || '').includes('GEOGRAFIA') && (mat.nombreMateria || '').includes('CIENCIA');
                        const subtituloModulo = esGeoICS ? (sem.semana <= 2 ? 'Geografía' : 'Intro. CS') : null;

                        let texto = `Sem ${sem.semana}${subtituloModulo ? ' (' + subtituloModulo + ')' : ''}`;
                        let tooltipTitle = `${subtituloModulo ? subtituloModulo + ' • ' : ''}Bloqueada (Semana no pagada)`;
                        let onclickAttr = `onclick="Swal.fire('Semana Bloqueada', 'Esta semana no ha sido liberada porque el estudiante aún no registra el pago correspondiente.', 'info')"`;

                        const nombreEscapado = mat.nombreMateria.replace(/'/g, "\\'");

                        if (sem.estatus === 'LIBERADA') {
                            claseEstatus = 'semana-liberada';
                            icono = 'ti-clock';
                            texto = `Sem ${sem.semana}${subtituloModulo ? ' (' + subtituloModulo + ')' : ''} (Por entregar)`;
                            tooltipTitle = `${subtituloModulo ? subtituloModulo + ' • ' : ''}Semana Pagada • Tarea pendiente de entrega`;
                            onclickAttr = `onclick="gestionarEntregaActividad(${alumnoDetalleActual.idAlumno}, ${mat.id}, '${nombreEscapado}', ${sem.semana}, 'LIBERADA', null, '${sem.fecha_liberacion || ''}')"`;
                        } else if (sem.estatus === 'ENTREGADA') {
                            claseEstatus = 'semana-entregada';
                            icono = 'ti-file-check';
                            texto = `Sem ${sem.semana}${subtituloModulo ? ' (' + subtituloModulo + ')' : ''} (Entregada)`;
                            tooltipTitle = `${subtituloModulo ? subtituloModulo + ' • ' : ''}Actividad entregada ${sem.fecha_entrega ? 'el ' + sem.fecha_entrega : ''}`;
                            onclickAttr = `onclick="gestionarEntregaActividad(${alumnoDetalleActual.idAlumno}, ${mat.id}, '${nombreEscapado}', ${sem.semana}, 'ENTREGADA', null, '${sem.fecha_entrega || ''}')"`;
                        } else if (sem.estatus === 'CALIFICADA') {
                            claseEstatus = 'semana-calificada';
                            icono = 'ti-award';
                            texto = `Sem ${sem.semana}${subtituloModulo ? ' (' + subtituloModulo + ')' : ''}: ${sem.calificacion}`;
                            tooltipTitle = `${subtituloModulo ? subtituloModulo + ' • ' : ''}Calificada con ${sem.calificacion}`;
                            onclickAttr = `onclick="gestionarEntregaActividad(${alumnoDetalleActual.idAlumno}, ${mat.id}, '${nombreEscapado}', ${sem.semana}, 'CALIFICADA', ${sem.calificacion}, '${sem.fecha_entrega || ''}')"`;
                        }

                        return `<span class="semana-chip ${claseEstatus}" ${onclickAttr} title="${tooltipTitle}">
                            <i class="ti ${icono}"></i> ${texto}
                        </span>`;
                    }).join('');

                    return `
                        <div class="col-md-6 mb-2">
                            <div class="bg-white p-3 rounded-3 border h-100 shadow-sm" style="border-color: #E2E8F0 !important;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <strong class="text-dark" style="font-size: 0.85rem;">${mat.nombreMateria}</strong>
                                    <span class="badge" style="background: #F0F7F8; color: #1E5B66; font-size: 0.68rem;">${mat.clave || 'BGNE'}</span>
                                </div>
                                <div class="d-flex gap-2 flex-wrap">
                                    ${semanasHtml}
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');

                return `
                    <div class="mb-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge" style="background: #1E5B66; color: #FFFFFF; font-size: 0.72rem; padding: 4px 8px;">
                                MES ${mes.mes_bloque} • Semanas ${(mes.mes_bloque - 1) * 4 + 1} a ${mes.mes_bloque * 4}
                            </span>
                            <div style="flex: 1; height: 1px; background: #E2E8F0;"></div>
                        </div>
                        <div class="row g-2">
                            ${materiasHtml}
                        </div>
                    </div>
                `;
            }).join('');

            return `
                <div class="card mb-4 border-0 shadow-sm" style="background: #F8FAFC; border-radius: 12px; border: 1px solid #E2E8F0 !important;">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between" style="border-radius: 12px 12px 0 0;">
                        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="ti ti-bookmark" style="color: #1E5B66;"></i> ${trim.trimestre}° Trimestre
                            ${trim.es_actual ? '<span class="badge bg-success" style="font-size: 0.65rem;">EN CURSO</span>' : ''}
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        ${mesesHtml}
                    </div>
                </div>
            `;
        }).join('');

        cont.innerHTML = leyendaHtml + cardsHtml;
    }

    // Gestión interactiva del estado de una actividad semanal
    async function gestionarEntregaActividad(idAlumno, idMateria, nombreMateria, semana, estatusActual, califActual, fechaActual) {
        const esGeoICS = (nombreMateria || '').includes('GEOGRAFIA') && (nombreMateria || '').includes('CIENCIA');
        const moduloDetalle = esGeoICS ? (semana <= 2 ? ' • Módulo 1: Geografía' : ' • Módulo 2: Intro. a Ciencias Sociales') : '';

        const htmlModal = `
            <div class="text-start" style="font-size: 0.85rem;">
                <div class="mb-1 text-muted small">Materia y Semana:</div>
                <div class="p-2 bg-light rounded border fw-bold text-dark font-monospace mb-3">${nombreMateria} • Semana ${semana}${moduloDetalle}</div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Estatus de la Actividad:</label>
                    <select id="swalSelectEstatus" class="form-select" onchange="document.getElementById('swalBoxCalif').style.display = (this.value === 'CALIFICADA') ? 'block' : 'none'">
                        <option value="LIBERADA" ${estatusActual === 'LIBERADA' ? 'selected' : ''}>🟡 Pendiente de Entrega (Semana pagada, sin tarea subida)</option>
                        <option value="ENTREGADA" ${estatusActual === 'ENTREGADA' ? 'selected' : ''}>🔵 Actividad Entregada (Tarea subida por el alumno)</option>
                        <option value="CALIFICADA" ${estatusActual === 'CALIFICADA' ? 'selected' : ''}>🟢 Calificada (Evaluada por el docente con nota)</option>
                    </select>
                </div>

                <div class="mb-3" id="swalBoxCalif" style="display: ${estatusActual === 'CALIFICADA' ? 'block' : 'none'};">
                    <label class="form-label fw-bold small text-dark">Calificación (0 a 100):</label>
                    <input type="number" step="0.1" min="0" max="100" class="form-control" id="swalInputCalif" value="${califActual !== null ? califActual : ''}" placeholder="Ej. 90.0">
                </div>
                
                <p class="text-muted small mt-2 mb-0">
                    <i class="ti ti-info-circle me-1 text-primary"></i> 
                    Permite diferenciar claramente cuando el alumno ya pagó la semana vs cuando efectivamente subió la tarea.
                </p>
            </div>
        `;

        const result = await Swal.fire({
            title: 'Control de Actividad • Sem ' + semana,
            html: htmlModal,
            showCancelButton: true,
            confirmButtonText: '<i class="ti ti-check me-1"></i> Guardar Estatus',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#1E5B66'
        });

        if (result.isConfirmed) {
            const nuevoEstatus = document.getElementById('swalSelectEstatus').value;
            const califVal = document.getElementById('swalInputCalif').value;

            try {
                const res = await fetch(`/alumnos/${idAlumno}/actividades/actualizar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id_materia: idMateria,
                        semana: semana,
                        estatus: nuevoEstatus,
                        calificacion: (nuevoEstatus === 'CALIFICADA' && califVal !== '') ? parseFloat(califVal) : null
                    })
                });

                const data = await res.json();
                if (data.success) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    abrirModalDetalle(idAlumno);
                    cargarAlumnosOnline();
                } else {
                    Swal.fire('Error', data.message || 'No se pudo actualizar la actividad.', 'error');
                }
            } catch (e) {
                Swal.fire('Error', 'Fallo de conexión al actualizar actividad.', 'error');
            }
        }
    }

    function renderizarHistorialPagos(pagos) {
        const tbody = document.getElementById('tbodyHistorialPagosAlumno');
        if (!pagos || pagos.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-muted">Sin historial de pagos registrados.</td></tr>`;
            return;
        }

        tbody.innerHTML = pagos.map(p => `
            <tr>
                <td class="font-monospace fw-bold text-dark">${p.folio_ticket}</td>
                <td>${p.fecha_pago}</td>
                <td><span class="badge-semanas">${p.semanas_pagadas} semana(s)</span></td>
                <td>Trim. ${p.id_nivel_academico || '-'} (Mes ${p.mes_bloque || '-'})</td>
                <td class="fw-bold text-success">$${parseFloat(p.monto).toFixed(2)}</td>
                <td>${p.metodo_pago}</td>
                <td class="text-muted small">${p.registrado_por || 'Sistema'}</td>
            </tr>
        `).join('');
    }

    // 9. Precargar y Abrir Modal de Distribución de Materias
    let trimestreMatrizActivo = 6;

    async function precargarDistribucion() {
        try {
            const res = await fetch(`/online/distribucion-materias`);
            const data = await res.json();
            if (data.success) {
                distribucionMateriasGlobal = data.trimestres;
            }
        } catch (e) {
            console.warn('Error precargando matriz de materias');
        }
    }

    async function abrirModalDistribucion() {
        const modalEl = document.getElementById('modalDistribucionMaterias');
        modalEl.removeAttribute('tabindex');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl, { focus: false });
        modal.show();
        if (!distribucionMateriasGlobal) {
            await precargarDistribucion();
        }
        mostrarMatrizTrimestre(6); // Por defecto mostramos el 6° que coincide con la imagen enviada
    }

    function renderBadgeCursoMoodle(mat) {
        if (!mat || !mat.id) return '';
        const cid = mat.id_curso_moodle;
        const label = cid ? `Moodle: #${cid}` : `+ Asignar Moodle`;
        const cssClass = cid ? 'badge-moodle-course-id' : 'badge-moodle-course-id sin-asignar';
        const nombreEscapado = (mat.nombreMateria || '').replace(/'/g, "\\'");
        return `<div class="mt-1"><button type="button" class="${cssClass}" onclick="editarCursoMoodle(${mat.id}, '${nombreEscapado}', ${cid || 'null'})" title="Asignar o editar ID de curso en Moodle"><i class="ti ti-brand-moodle"></i> ${label}</button></div>`;
    }

    async function editarCursoMoodle(idMateria, nombreMateria, idActual) {
        const { value: nuevoId, isConfirmed } = await Swal.fire({
            title: 'Vincular con Curso Moodle',
            html: `
                <div class="text-start mb-2" style="font-size: 0.85rem;">
                    <div class="text-muted small mb-1">Materia en Plan de Estudios:</div>
                    <div class="p-2 bg-light rounded border fw-bold text-dark font-monospace mb-3">${nombreMateria}</div>
                    <p class="text-muted small mb-2">
                        <i class="ti ti-info-circle me-1 text-primary"></i> 
                        Para obtener el ID, abre la materia en <strong>Moodle</strong> y copia el número al final de la URL:<br>
                        <code class="text-primary font-monospace">.../course/view.php?id=<b>15</b></code>
                    </p>
                    <span class="text-muted" style="font-size: 0.75rem;">(Deja el campo vacío si deseas desvincular esta materia de Moodle).</span>
                </div>
            `,
            input: 'number',
            inputValue: idActual || '',
            inputPlaceholder: 'Ej. 3, 6, 7, 15...',
            inputAttributes: {
                min: 1,
                step: 1
            },
            showCancelButton: true,
            confirmButtonText: '<i class="ti ti-check me-1"></i> Guardar Curso',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#1E5B66',
            cancelButtonColor: '#64748B',
            didOpen: () => {
                const input = Swal.getInput();
                if (input) {
                    input.focus();
                    input.select();
                }
            }
        });

        if (isConfirmed) {
            try {
                const res = await fetch(`/online/materias/${idMateria}/moodle-curso`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id_curso_moodle: nuevoId ? parseInt(nuevoId) : null })
                });
                const data = await res.json();
                if (data.success) {
                    const nuevoCursoId = data.id_curso_moodle !== undefined ? data.id_curso_moodle : (nuevoId ? parseInt(nuevoId) : null);
                    if (distribucionMateriasGlobal && distribucionMateriasGlobal[trimestreMatrizActivo]) {
                        ['mes_1', 'mes_2', 'mes_3'].forEach(mesKey => {
                            const lista = distribucionMateriasGlobal[trimestreMatrizActivo][mesKey] || [];
                            lista.forEach(m => {
                                if (m && m.id == idMateria) {
                                    m.id_curso_moodle = nuevoCursoId;
                                }
                            });
                        });
                    }

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.message || 'Curso Moodle vinculado correctamente.',
                        showConfirmButton: false,
                        timer: 2200
                    });

                    mostrarMatrizTrimestre(trimestreMatrizActivo);
                    precargarDistribucion();
                } else {
                    Swal.fire('Error', data.message || 'No se pudo guardar el ID.', 'error');
                }
            } catch (e) {
                Swal.fire('Error', 'Fallo de conexión al guardar ID del curso.', 'error');
            }
        }
    }

    function mostrarMatrizTrimestre(num) {
        trimestreMatrizActivo = num;

        // Actualizar estado activo de los botones
        for (let i = 1; i <= 6; i++) {
            const btn = document.getElementById(`btnTabTrim${i}`);
            if (btn) {
                if (i === num) {
                    btn.className = 'btn btn-sm btn-primary px-3';
                    btn.style.backgroundColor = '#1E5B66';
                    btn.style.borderColor = '#1E5B66';
                } else {
                    btn.className = 'btn btn-sm btn-outline-secondary px-3';
                    btn.style.backgroundColor = '';
                    btn.style.borderColor = '';
                }
            }
        }

        const cont = document.getElementById('contenedorMatrizTrimestre');

        // Si es el 6° Trimestre: Renderizamos exactamente la matriz idéntica al Excel de la imagen
        if (num === 6) {
            const t6Data = (distribucionMateriasGlobal && distribucionMateriasGlobal[6]) ? distribucionMateriasGlobal[6] : null;
            const m1_6 = t6Data ? (t6Data.mes_1 || []) : [];
            const m2_6 = t6Data ? (t6Data.mes_2 || []) : [];
            const m3_6 = t6Data ? (t6Data.mes_3 || []) : [];

            cont.innerHTML = `
                <div class="table-responsive rounded-3 border overflow-hidden shadow-sm" style="border-color: #000000 !important;">
                    <table class="matriz-excel-table">
                        <thead>
                            <tr>
                                <th colspan="24" class="tr-header-trimestre-modal">SEXTO TRIMESTRE</th>
                            </tr>
                            <tr>
                                <th colspan="24" class="tr-header-materias-modal">MATERIAS CURSADAS</th>
                            </tr>
                            <tr>
                                <td class="cell-x-indicator-modal">x</td>
                                <td class="cell-x-indicator-modal">x</td>
                                <td class="cell-x-indicator-modal">x</td>
                                <td class="cell-x-indicator-modal">x</td>
                                <td colspan="20" style="background: #FFFFFF; border: none; border-bottom: 1px solid #000000;"></td>
                            </tr>
                            <tr>
                                <th colspan="8" style="background-color: #2563EB !important; color: #FFF; font-weight: 800;">MES I</th>
                                <th colspan="8" style="background-color: #7E22CE !important; color: #FFF; font-weight: 800;">MES II</th>
                                <th colspan="8" style="background-color: #65A30D !important; color: #FFF; font-weight: 800;">MES III</th>
                            </tr>
                            <tr>
                                <th colspan="4" style="background-color: #FFA0A0 !important; color: #000; font-weight: 700;">
                                    ${m1_6[0] ? m1_6[0].nombreMateria : 'INFORMATICA IV'}
                                    ${renderBadgeCursoMoodle(m1_6[0])}
                                </th>
                                <th colspan="4" style="background-color: #FFC000 !important; color: #000; font-weight: 700;">
                                    ${m1_6[1] ? m1_6[1].nombreMateria : 'FILOSOFIA'}
                                    ${renderBadgeCursoMoodle(m1_6[1])}
                                </th>
                                <th colspan="4" style="background-color: #6B98D8 !important; color: #000; font-weight: 700;">
                                    ${m2_6[0] ? m2_6[0].nombreMateria : 'CALCULO INTEGRAL'}
                                    ${renderBadgeCursoMoodle(m2_6[0])}
                                </th>
                                <th colspan="4" style="background-color: #9BC595 !important; color: #000; font-weight: 700;">
                                    ${m2_6[1] ? m2_6[1].nombreMateria : 'TEMAS SELC.DE BIO II'}
                                    ${renderBadgeCursoMoodle(m2_6[1])}
                                </th>
                                <th colspan="4" style="background-color: #FF0000 !important; color: #000; font-weight: 700;">
                                    ${m3_6[0] ? m3_6[0].nombreMateria : 'CONTABILIDAD II'}
                                    ${renderBadgeCursoMoodle(m3_6[0])}
                                </th>
                                <th colspan="4" style="background-color: #B5739D !important; color: #000; font-weight: 700;">
                                    ${m3_6[1] ? m3_6[1].nombreMateria : 'INGLES VI'}
                                    ${renderBadgeCursoMoodle(m3_6[1])}
                                </th>
                            </tr>
                            <tr>
                                <td style="background-color: #FFA0A0; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 1</td>
                                <td style="background-color: #FFA0A0; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 2</td>
                                <td style="background-color: #FFA0A0; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 3</td>
                                <td style="background-color: #FFA0A0; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 4</td>
                                <td style="background-color: #FFC000; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 1</td>
                                <td style="background-color: #FFC000; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 2</td>
                                <td style="background-color: #FFC000; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 3</td>
                                <td style="background-color: #FFC000; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 4</td>

                                <td style="background-color: #6B98D8; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 1</td>
                                <td style="background-color: #6B98D8; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 2</td>
                                <td style="background-color: #6B98D8; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 3</td>
                                <td style="background-color: #6B98D8; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 4</td>
                                <td style="background-color: #9BC595; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 1</td>
                                <td style="background-color: #9BC595; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 2</td>
                                <td style="background-color: #9BC595; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 3</td>
                                <td style="background-color: #9BC595; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 4</td>

                                <td style="background-color: #FF0000; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 1</td>
                                <td style="background-color: #FF0000; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 2</td>
                                <td style="background-color: #FF0000; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 3</td>
                                <td style="background-color: #FF0000; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 4</td>
                                <td style="background-color: #B5739D; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 1</td>
                                <td style="background-color: #B5739D; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 2</td>
                                <td style="background-color: #B5739D; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 3</td>
                                <td style="background-color: #B5739D; font-family: monospace; font-weight: 700; font-size: 0.75rem;">SEM 4</td>
                            </tr>
                        </thead>
                    </table>
                </div>
            `;
            return;
        }

        // Para trimestres 1 a 5: Mostramos desde los datos oficiales de la BD
        if (!distribucionMateriasGlobal || !distribucionMateriasGlobal[num]) {
            cont.innerHTML = `<div class="text-center py-4 text-muted"><i class="ti ti-loader animate-spin me-2"></i> Cargando materias del ${num}° Trimestre...</div>`;
            return;
        }

        const tData = distribucionMateriasGlobal[num];
        const m1 = tData.mes_1 || [];
        const m2 = tData.mes_2 || [];
        const m3 = tData.mes_3 || [];

        const nombresTrimestres = ['', 'PRIMER', 'SEGUNDO', 'TERCER', 'CUARTO', 'QUINTO', 'SEXTO'];

        cont.innerHTML = `
            <div class="table-responsive rounded-3 border overflow-hidden shadow-sm" style="border-color: #000000 !important;">
                <table class="matriz-excel-table">
                    <thead>
                        <tr>
                            <th colspan="24" class="tr-header-trimestre-modal">${nombresTrimestres[num]} TRIMESTRE</th>
                        </tr>
                        <tr>
                            <th colspan="24" class="tr-header-materias-modal">MATERIAS CURSADAS</th>
                        </tr>
                        <tr>
                            <th colspan="8" style="background-color: #2563EB !important; color: #FFF; font-weight: 800;">MES I</th>
                            <th colspan="8" style="background-color: #7E22CE !important; color: #FFF; font-weight: 800;">MES II</th>
                            <th colspan="8" style="background-color: #65A30D !important; color: #FFF; font-weight: 800;">MES III</th>
                        </tr>
                        <tr>
                            <th colspan="4" style="background-color: #FFA0A0 !important; color: #000; font-weight: 700;">
                                ${m1[0] ? m1[0].nombreMateria : 'MATERIA 1'}
                                ${renderBadgeCursoMoodle(m1[0])}
                            </th>
                            <th colspan="4" style="background-color: #FFC000 !important; color: #000; font-weight: 700;">
                                ${m1[1] ? m1[1].nombreMateria : 'MATERIA 2'}
                                ${renderBadgeCursoMoodle(m1[1])}
                            </th>
                            <th colspan="4" style="background-color: #6B98D8 !important; color: #000; font-weight: 700;">
                                ${m2[0] ? m2[0].nombreMateria : 'MATERIA 3'}
                                ${renderBadgeCursoMoodle(m2[0])}
                            </th>
                            <th colspan="4" style="background-color: #9BC595 !important; color: #000; font-weight: 700;">
                                ${m2[1] ? m2[1].nombreMateria : 'MATERIA 4'}
                                ${renderBadgeCursoMoodle(m2[1])}
                            </th>
                            <th colspan="4" style="background-color: #FF0000 !important; color: #000; font-weight: 700;">
                                ${m3[0] ? m3[0].nombreMateria : 'MATERIA 5'}
                                ${renderBadgeCursoMoodle(m3[0])}
                            </th>
                            <th colspan="4" style="background-color: #B5739D !important; color: #000; font-weight: 700;">
                                ${m3[1] ? m3[1].nombreMateria : 'MATERIA 6'}
                                ${renderBadgeCursoMoodle(m3[1])}
                            </th>
                        </tr>
                        <tr>
                            <td style="background-color: #FFA0A0; font-family: monospace; font-weight: 700; font-size: 0.72rem;">SEM 1</td>
                            <td style="background-color: #FFA0A0; font-family: monospace; font-weight: 700; font-size: 0.72rem;">SEM 2</td>
                            <td style="background-color: #FFA0A0; font-family: monospace; font-weight: 700; font-size: 0.72rem;">SEM 3</td>
                            <td style="background-color: #FFA0A0; font-family: monospace; font-weight: 700; font-size: 0.72rem;">SEM 4</td>

                            <td style="background-color: #FFC000; font-family: monospace; font-weight: 700; font-size: 0.72rem;" title="Semana 1: Geografía">SEM 1</td>
                            <td style="background-color: #FFC000; font-family: monospace; font-weight: 700; font-size: 0.72rem;" title="Semana 2: Geografía">SEM 2</td>
                            <td style="background-color: #FFC000; font-family: monospace; font-weight: 700; font-size: 0.72rem;" title="Semana 3: Intro. a Ciencias Sociales">SEM 3</td>
                            <td style="background-color: #FFC000; font-family: monospace; font-weight: 700; font-size: 0.72rem;" title="Semana 4: Intro. a Ciencias Sociales">SEM 4</td>

                            <td style="background-color: #6B98D8; font-family: monospace; font-weight: 700; font-size: 0.72rem;">SEM 1</td>
                            <td style="background-color: #6B98D8; font-family: monospace; font-weight: 700; font-size: 0.72rem;">SEM 2</td>
                            <td style="background-color: #6B98D8; font-family: monospace; font-weight: 700; font-size: 0.72rem;">SEM 3</td>
                            <td style="background-color: #6B98D8; font-family: monospace; font-weight: 700; font-size: 0.72rem;">SEM 4</td>

                            <td style="background-color: #9BC595; font-family: monospace; font-weight: 700; font-size: 0.72rem;">SEM 1</td>
                            <td style="background-color: #9BC595; font-family: monospace; font-weight: 700; font-size: 0.72rem;">SEM 2</td>
                            <td style="background-color: #9BC595; font-family: monospace; font-weight: 700; font-size: 0.72rem;">SEM 3</td>
                            <td style="background-color: #9BC595; font-family: monospace; font-weight: 700; font-size: 0.72rem;">SEM 4</td>

                            <td style="background-color: #FF0000; font-family: monospace; font-weight: 700; font-size: 0.72rem;">SEM 1</td>
                            <td style="background-color: #FF0000; font-family: monospace; font-weight: 700; font-size: 0.72rem;">SEM 2</td>
                            <td style="background-color: #FF0000; font-family: monospace; font-weight: 700; font-size: 0.72rem;">SEM 3</td>
                            <td style="background-color: #FF0000; font-family: monospace; font-weight: 700; font-size: 0.72rem;">SEM 4</td>

                            <td style="background-color: #B5739D; font-family: monospace; font-weight: 700; font-size: 0.72rem;">SEM 1</td>
                            <td style="background-color: #B5739D; font-family: monospace; font-weight: 700; font-size: 0.72rem;">SEM 2</td>
                            <td style="background-color: #B5739D; font-family: monospace; font-weight: 700; font-size: 0.72rem;">SEM 3</td>
                            <td style="background-color: #B5739D; font-family: monospace; font-weight: 700; font-size: 0.72rem;">SEM 4</td>
                        </tr>
                    </thead>
                </table>
            </div>
        `;
    }

    // 10. Sincronización y Control de Acceso a Moodle LMS
    async function sincronizarMoodle(idAlumno, nombre) {
        Swal.fire({
            title: 'Sincronizando con Moodle...',
            html: `Verificando cuenta, enrolamientos y pagos para <b>${nombre}</b>.`,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const res = await fetch(`/alumnos/${idAlumno}/sincronizar-moodle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const data = await res.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Moodle Sincronizado!',
                    html: data.message,
                    confirmButtonColor: '#1E5B66'
                });
                cargarAlumnosOnline();
            } else {
                Swal.fire('Error en Moodle', data.message || 'No se pudo sincronizar.', 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'Fallo de conexión al sincronizar con Moodle.', 'error');
        }
    }

    async function toggleMoodleAccess(idAlumno, nombre, suspender) {
        const accionTexto = suspender ? 'suspender temporalmente el acceso a Moodle' : 'reactivar el acceso a Moodle';
        const confirmBtnColor = suspender ? '#DC2626' : '#1E5B66';

        const result = await Swal.fire({
            title: '¿Confirmar acción en Moodle?',
            html: `¿Estás seguro de que deseas <b>${accionTexto}</b> para el alumno <b>${nombre}</b>?`,
            icon: suspender ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonText: suspender ? 'Sí, Suspender' : 'Sí, Reactivar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: confirmBtnColor
        });

        if (result.isConfirmed) {
            try {
                const res = await fetch(`/alumnos/${idAlumno}/toggle-suspension-moodle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ suspender: suspender })
                });
                const data = await res.json();

                if (data.success) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 2500
                    });
                    cargarAlumnosOnline();
                } else {
                    Swal.fire('Error', data.message || 'No se pudo actualizar el acceso en Moodle.', 'error');
                }
            } catch (e) {
                Swal.fire('Error', 'Fallo de conexión al actualizar estado de Moodle.', 'error');
            }
        }
    }

    // 11. Integrar Alumno Presencial a Online
    function abrirModalIntegrarAlumno() {
        document.getElementById('formIntegrarAlumno').reset();
        document.getElementById('integrarAlumnoId').value = '';
        const inputUser = document.getElementById('inputMoodleUsernameNuevo');
        if (inputUser) inputUser.value = '';
        document.getElementById('boxAlumnoSeleccionado').style.display = 'none';
        const blqBusq = document.getElementById('bloqueBuscarAlumnoPresencial');
        if (blqBusq) blqBusq.style.display = 'block';
        document.getElementById('listaResultadosPresenciales').style.display = 'none';
        new bootstrap.Modal(document.getElementById('modalIntegrarAlumnoOnline')).show();
    }

    let timerBusqueda = null;
    function buscarPresencialesEnVivo(val) {
        clearTimeout(timerBusqueda);
        if (val.trim().length < 2) {
            document.getElementById('listaResultadosPresenciales').style.display = 'none';
            return;
        }

        timerBusqueda = setTimeout(async () => {
            try {
                const res = await fetch(`/online/buscar-presenciales?search=${encodeURIComponent(val)}`);
                const data = await res.json();
                const cont = document.getElementById('listaResultadosPresenciales');

                if (data.success && data.alumnos.length > 0) {
                    window.resultadosBusquedaPresenciales = data.alumnos;
                    cont.style.display = 'block';
                    cont.innerHTML = data.alumnos.map((a, idx) => {
                        const nombreCompleto = `${a.nombre} ${a.apPaterno || ''} ${a.apMaterno || ''}`.trim();
                        const modBadge = a.modalidad_estudio === 'ONLINE'
                            ? `<span class="badge" style="background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; font-size: 0.7rem;">Online</span>`
                            : `<span class="badge bg-light text-dark border" style="font-size: 0.7rem;">${a.grupo || 'Presencial'}</span>`;

                        return `
                            <button type="button" class="list-group-item list-group-item-action py-2 d-flex justify-content-between align-items-center" onclick="seleccionarAlumnoPresencialPorIndice(${idx})">
                                <div>
                                    <strong class="text-dark small d-block">${nombreCompleto}</strong>
                                    <span class="text-muted" style="font-size: 0.72rem;">Matrícula / ID: ${a.numeroControl || 'ID-' + a.idAlumno}</span>
                                </div>
                                <div>${modBadge}</div>
                            </button>
                        `;
                    }).join('');
                } else {
                    cont.style.display = 'block';
                    cont.innerHTML = `<div class="p-2 text-center text-muted small">No se encontró ningún estudiante con ese nombre o matrícula.</div>`;
                }
            } catch (e) {
                console.warn(e);
            }
        }, 250);
    }

    function seleccionarAlumnoPresencialPorIndice(idx) {
        const a = (window.resultadosBusquedaPresenciales || [])[idx];
        if (!a) return;
        const nombreCompleto = `${a.nombre} ${a.apPaterno || ''} ${a.apMaterno || ''}`.trim();
        document.getElementById('integrarAlumnoId').value = a.idAlumno;
        document.getElementById('lblAlumnoSeleccionadoNombre').textContent = nombreCompleto;
        document.getElementById('lblAlumnoSeleccionadoInfo').textContent = `Grupo asignado: ${a.grupo || 'Sin grupo'} (ID #${a.idAlumno})`;
        const inputUser = document.getElementById('inputMoodleUsernameNuevo');
        if (inputUser) inputUser.value = 'alu' + a.idAlumno;
        document.getElementById('boxAlumnoSeleccionado').style.display = 'block';
        const blqBusq = document.getElementById('bloqueBuscarAlumnoPresencial');
        if (blqBusq) blqBusq.style.display = 'none';
        document.getElementById('listaResultadosPresenciales').style.display = 'none';
        document.getElementById('inputBuscarAlumnoPresencial').value = '';
    }

    function seleccionarAlumnoPresencial(id, nombre, matricula, grupo) {
        document.getElementById('integrarAlumnoId').value = id;
        document.getElementById('lblAlumnoSeleccionadoNombre').textContent = nombre;
        document.getElementById('lblAlumnoSeleccionadoInfo').textContent = `Grupo asignado: ${grupo || 'Sin grupo'} (ID #${id})`;
        const inputUser = document.getElementById('inputMoodleUsernameNuevo');
        if (inputUser) inputUser.value = 'alu' + id;
        document.getElementById('boxAlumnoSeleccionado').style.display = 'block';
        const blqBusq = document.getElementById('bloqueBuscarAlumnoPresencial');
        if (blqBusq) blqBusq.style.display = 'none';
        document.getElementById('listaResultadosPresenciales').style.display = 'none';
        document.getElementById('inputBuscarAlumnoPresencial').value = '';
    }

    function cambiarAlumnoSeleccionado() {
        document.getElementById('integrarAlumnoId').value = '';
        const inputUser = document.getElementById('inputMoodleUsernameNuevo');
        if (inputUser) inputUser.value = '';
        document.getElementById('boxAlumnoSeleccionado').style.display = 'none';
        const blqBusq = document.getElementById('bloqueBuscarAlumnoPresencial');
        if (blqBusq) blqBusq.style.display = 'block';
        document.getElementById('inputBuscarAlumnoPresencial').focus();
    }

    async function guardarIntegracionOnline(e) {
        e.preventDefault();
        const id = document.getElementById('integrarAlumnoId').value;
        if (!id) {
            Swal.fire('Atención', 'Primero selecciona a un estudiante presencial.', 'warning');
            return;
        }

        const btn = document.getElementById('btnConfirmarIntegracion');
        btn.disabled = true;
        btn.innerHTML = `<i class="ti ti-loader animate-spin me-1"></i> Activando...`;

        const payload = {
            dia_pago: document.getElementById('selectDiaPagoNuevo').value,
            trimestre_inicio: document.getElementById('selectTrimestreInicioNuevo').value,
            moodle_password: document.getElementById('inputPassMoodleNuevo').value
        };

        try {
            const res = await fetch(`/alumnos/${id}/activar-online`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            });

            const data = await res.json();
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('modalIntegrarAlumnoOnline')).hide();
                Swal.fire({
                    icon: 'success',
                    title: '¡Estudiante Integrado a Online!',
                    text: data.message,
                    confirmButtonColor: '#1E5B66'
                });
                cargarAlumnosOnline();
            } else {
                Swal.fire('Error', data.message || 'No se pudo integrar.', 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'Fallo de conexión.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = `<i class="ti ti-check me-1"></i> Activar Modalidad Online`;
        }
    }
</script>

@endsection

