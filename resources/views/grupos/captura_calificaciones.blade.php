@extends('layouts.app')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="{{ asset('css/estilosGrupos.css') }}">

<style>
.filter-pill-btn {
    border: 1.5px solid rgba(49, 125, 146, 0.25);
    background: #ffffff;
    color: rgb(49, 125, 146);
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 20px;
    transition: all 0.25s ease;
    cursor: pointer;
    font-size: 0.88rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
}
.filter-pill-btn:hover {
    background: rgba(49, 125, 146, 0.08);
    border-color: rgb(49, 125, 146);
    color: rgb(38, 104, 123);
    transform: translateY(-1px);
}
.filter-pill-btn.active {
    background: rgb(49, 125, 146) !important;
    color: #ffffff !important;
    border-color: rgb(49, 125, 146) !important;
    box-shadow: 0 4px 12px rgba(49, 125, 146, 0.3);
}

.grupo-card {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
}
.grupo-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(38, 104, 123, 0.15);
    border-color: rgba(49, 125, 146, 0.4);
}

.modal {
    z-index: 1200 !important;
}
.modal-backdrop {
    z-index: 1150 !important;
}

.cct-badge-bgne { background-color: #2563eb; color: #fff; }
.cct-badge-bti { background-color: #0284c7; color: #fff; }
.cct-badge-ic { background-color: #475569; color: #fff; }

/* Estilos de la tabla tipo Excel Oficial */
.control-header-box {
    background: #ffffff;
    border: 1.5px solid #0f172a;
    border-radius: 8px;
    padding: 12px 16px;
}

.table-calif-oficial {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.86rem;
}
.table-calif-oficial th {
    background-color: #e2e8f0;
    color: #0f172a;
    font-weight: 800;
    text-transform: uppercase;
    font-size: 0.76rem;
    border: 1.2px solid #0f172a;
    padding: 6px 8px;
    text-align: center;
    vertical-align: middle;
}
.table-calif-oficial td {
    border: 1px solid #94a3b8;
    padding: 4px 6px;
    vertical-align: middle;
    background-color: #ffffff;
}
.table-calif-oficial tr:hover td {
    background-color: #f8fafc;
}

.input-calif-celda {
    width: 100%;
    text-align: center;
    font-weight: 700;
    border: 1px solid transparent;
    background: transparent;
    padding: 4px 2px;
    font-size: 0.88rem;
    border-radius: 4px;
    transition: all 0.2s;
}
.input-calif-celda:hover {
    border-color: #cbd5e1;
    background: #ffffff;
}
.input-calif-celda:focus {
    border-color: #0284c7;
    background: #ffffff;
    outline: none;
    box-shadow: 0 0 0 2px rgba(2, 132, 199, 0.25);
}

.input-observaciones-celda {
    width: 100%;
    border: 1px solid transparent;
    background: transparent;
    padding: 4px 6px;
    font-size: 0.82rem;
    border-radius: 4px;
}
.input-observaciones-celda:hover, .input-observaciones-celda:focus {
    border-color: #cbd5e1;
    background: #ffffff;
    outline: none;
}
</style>

<div class="page-container">

    {{-- Encabezado Principal --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('grupos') }}" class="btn btn-regresar">
            <i class="fa-solid fa-arrow-left me-2"></i>
            Regresar a Grupos
        </a>

        <h3 class="page-title mb-0">
            <i class="fa-solid fa-file-pen me-2"></i>
            Captura de Calificaciones por Grupo
        </h3>

        <div style="width: 120px;"></div>
    </div>

    {{-- Contenedor de Filtros y Búsqueda --}}
    <div class="glass-card mb-4 p-4">
        <div class="row g-3 align-items-center">

            {{-- Filtro CCT --}}
            <div class="col-12 col-md-4 col-xl-4">
                <label class="form-label text-dark fw-bold mb-2">
                    <i class="fa-solid fa-school me-1 text-primary"></i> Centro de Trabajo (CCT):
                </label>
                <select id="selectFiltroCct" class="form-select shadow-sm" style="border-radius: 12px; font-weight: 500;" onchange="setFiltroCct(this.value)">
                    <option value="" selected>Todos los CCT</option>
                    <option value="3">BGNE (Bachillerato No Escolarizado)</option>
                    <option value="2">BTI (Bachillerato Tecnológico)</option>
                    <option value="1">INF (Informática y Computación)</option>
                </select>
            </div>

            {{-- Filtro Estatus --}}
            <div class="col-12 col-md-3 col-xl-3">
                <label class="form-label text-dark fw-bold mb-2">
                    <i class="fa-solid fa-toggle-on me-1 text-primary"></i> Estatus:
                </label>
                <select id="selectFiltroEstatus" class="form-select shadow-sm" style="border-radius: 12px; font-weight: 500;" onchange="aplicarFiltros()">
                    <option value="ACTIVO" selected>Solo Activos</option>
                    <option value="INACTIVO">Solo Inactivos</option>
                    <option value="">Todos los Estatus</option>
                </select>
            </div>

            {{-- Buscador en tiempo real --}}
            <div class="col-12 col-md-5 col-xl-5">
                <label class="form-label text-dark fw-bold mb-2">
                    <i class="fa-solid fa-magnifying-glass me-1 text-primary"></i> Buscar Grupo:
                </label>
                <input type="text" id="buscadorGrupoCaptura" class="form-control shadow-sm" style="border-radius: 12px;" placeholder="Ej. BGNE291125..." oninput="aplicarFiltros()">
            </div>

        </div>
    </div>

    {{-- Spinner de Carga --}}
    <div id="loadingGrupos" class="text-center py-5" style="display:none;">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
        <div class="text-muted mt-2 fw-semibold">Cargando grupos para captura...</div>
    </div>

    {{-- Tabla de Grupos para Captura --}}
    <div id="contenedorTablaGruposWrapper" class="glass-card p-4 mb-4 shadow-sm" style="display:none;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaGruposCaptura">
                <thead style="background: rgba(49, 125, 146, 0.12); color: #0f172a; font-weight: 700; border-bottom: 2px solid rgba(49, 125, 146, 0.3);">
                    <tr>
                        <th style="width: 45px;">#</th>
                        <th>Clave del Grupo</th>
                        <th>CCT</th>
                        <th>Nivel / Periodo</th>
                        <th>Fechas</th>
                        <th style="min-width: 220px;">Progreso Periodo</th>
                        <th>Estatus</th>
                        <th class="text-center" style="width: 200px;">Acción</th>
                    </tr>
                </thead>
                <tbody id="tbodyGruposCaptura">
                    {{-- Poblado por JS --}}
                </tbody>
            </table>
        </div>
    </div>

    {{-- Estado Vacío --}}
    <div id="emptyStateGrupos" class="glass-card text-center p-5" style="display:none;">
        <i class="fa-solid fa-users-slash text-muted" style="font-size: 3.5rem;"></i>
        <h5 class="text-muted mt-3 mb-1">No se encontraron grupos</h5>
        <p class="text-muted small">Intenta ajustar los filtros de CCT o el estatus seleccionado.</p>
    </div>

</div>

{{-- MODAL OFICIAL DE CONTROL DE CALIFICACIONES POR MATERIA Y GRUPO --}}
<div class="modal fade" id="modalCapturaMateriaGrupo" tabindex="-1" aria-hidden="true" style="z-index: 1200;">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="max-width: 95%;">
        <div class="modal-content glass-modal shadow-lg border-0" style="border-radius: 18px;">
            
            {{-- Encabezado Modal --}}
            <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, rgb(38, 104, 123), #1e3a8a); border-top-left-radius: 18px; border-top-right-radius: 18px;">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-clipboard-check me-2 fs-4"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalHeaderTitulo">CONTROL DE CALIFICACIONES FINALES</h5>
                        <small id="modalHeaderSubtitulo" class="opacity-75">Captura por Asignatura y Docente Asignado</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-white text-dark">
                
                {{-- Spinner de Carga de Materia --}}
                <div id="loadingMateriaGrupo" class="text-center py-4" style="display: none;">
                    <div class="spinner-border text-primary" role="status"></div>
                    <div class="text-muted mt-2">Cargando materias y alumnos del grupo...</div>
                </div>

                {{-- Contenido de Captura --}}
                <div id="contentCapturaMateriaGrupo">
                    
                    {{-- Banner de Restricción de Solo Lectura --}}
                    <div id="bannerSoloLectura" class="alert alert-warning border-warning shadow-sm align-items-center gap-2 mb-3" style="display: none !important; border-radius: 12px; padding: 12px 16px;">
                        <i class="fa-solid fa-circle-exclamation fs-5 text-warning"></i>
                        <div>
                            <strong class="d-block" style="font-size: 0.92rem; color: #856404;">Captura Bloqueada (Solo Lectura)</strong>
                            <span id="mensajeBannerSoloLectura" class="small text-dark">—</span>
                        </div>
                    </div>

                    {{-- Calendario de Captura de Calificaciones --}}
                    <div id="containerCalendarioPeriodos" class="alert alert-info border-info shadow-sm p-3 mb-3" style="display: none; border-radius: 12px;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fa-solid fa-calendar-days text-primary fs-5"></i>
                            <strong style="font-size: 0.95rem; color: #0c5460;">Calendario de Captura (Límites de Fecha por Grupo)</strong>
                        </div>
                        <div class="row g-2 text-center" id="gridCalendarioPeriodos" style="font-size: 0.84rem;">
                            {{-- Poblado por JS --}}
                        </div>
                    </div>
                    
                    {{-- Formato Membrete Oficial --}}
                    <div class="control-header-box mb-3">
                        <div class="text-center mb-3">
                            <h4 class="fw-bold text-dark mb-1" id="boxNombreInstitucion">BACHILLERATO INTERAMERICANO</h4>
                            <h6 class="fw-bold text-secondary mb-2">CONTROL DE CALIFICACIONES FINALES</h6>
                            <span class="badge px-4 py-2 fs-5 text-dark shadow-sm" id="badgeClaveGrupoOficial" style="background-color: #ffeb3b; border: 1.5px solid #0f172a; font-weight: 800; letter-spacing: 1px;">
                                GRUPO
                            </span>
                        </div>

                        <div class="row g-2 border-top pt-3 align-items-center" style="font-size: 0.88rem;">
                            {{-- ASIGNATURA --}}
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="d-flex align-items-center gap-2">
                                    <strong class="text-nowrap" style="min-width: 105px;">ASIGNATURA:</strong>
                                    <select id="selectMateriaGrupo" class="form-select form-select-sm fw-bold border-dark shadow-sm" onchange="cambiarMateriaSeleccionada(this.value)">
                                        {{-- Poblado por JS --}}
                                    </select>
                                </div>
                            </div>

                            {{-- DOCENTE --}}
                            <div class="col-12 col-md-6 col-lg-5">
                                <div class="d-flex align-items-center gap-2">
                                    <strong class="text-nowrap" style="min-width: 80px;">DOCENTE:</strong>
                                    <div id="boxNombreDocente" class="fw-bold text-primary text-truncate p-1 bg-light rounded border w-100">
                                        —
                                    </div>
                                </div>
                            </div>

                            {{-- MÓDULO / PERIODO --}}
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="d-flex align-items-center gap-2">
                                    <strong class="text-nowrap">MÓDULO:</strong>
                                    <select id="selectPeriodoCaptura" class="form-select form-select-sm fw-bold border-dark shadow-sm" onchange="cambiarPeriodoSeleccionado(this.value)" {{ session('rol') === 'DOCENTE' ? 'disabled' : '' }}>
                                        {{-- Poblado por JS --}}
                                    </select>
                                </div>
                            </div>

                            {{-- FECHAS Y MODALIDAD --}}
                            <div class="col-12 col-md-4">
                                <small class="text-muted fw-bold">FECHA DE INICIO:</small>
                                <span id="boxFechaInicioGrupo" class="fw-semibold ms-1 text-dark">—</span>
                            </div>

                            <div class="col-12 col-md-4">
                                <small class="text-muted fw-bold">FECHA DE FIN:</small>
                                <span id="boxFechaFinGrupo" class="fw-semibold ms-1 text-dark">—</span>
                            </div>

                            <div class="col-12 col-md-4">
                                <small class="text-muted fw-bold">SISTEMA / MODALIDAD:</small>
                                <span id="boxModalidadGrupo" class="badge bg-info text-dark ms-1">—</span>
                            </div>
                        </div>
                    </div>

                    {{-- Tabla de Calificaciones de Alumnos --}}
                    <div class="table-responsive" style="max-height: 480px;">
                        <table class="table-calif-oficial">
                            <thead style="position: sticky; top: 0; z-index: 2;" id="theadCalificacionesOficial">
                                <tr>
                                    <th style="width: 40px;">N°</th>
                                    <th style="width: 130px;">Apellido Paterno</th>
                                    <th style="width: 130px;">Apellido Materno</th>
                                    <th style="width: 160px;">Nombre(s)</th>
                                    <th style="width: 80px;">% EXA (P1)</th>
                                    <th style="width: 80px;">% (P2)</th>
                                    <th style="width: 80px;">% (P3)</th>
                                    <th style="width: 95px;">Calificación Final</th>
                                    <th style="width: 160px;">Calificación con Letra</th>
                                    <th style="min-width: 140px;">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyAlumnosCalificacionesMateria">
                                {{-- Poblado por JS --}}
                            </tbody>
                            <tfoot id="tfootCalificacionesOficial" style="position: sticky; bottom: 0; background-color: #f8fafc; font-weight: bold; border-top: 2.5px solid #0f172a;">
                                {{-- Poblado por JS --}}
                            </tfoot>
                        </table>
                    </div>

                    {{-- Estadísticas Rápidas al pie --}}
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                        <small class="text-muted">
                            Total Alumnos: <strong id="statTotalAlumnos">0</strong> | 
                            Aprobados: <strong class="text-success" id="statAprobados">0</strong> | 
                            Reprobados: <strong class="text-danger" id="statReprobados">0</strong>
                        </small>
                        <span class="badge bg-primary px-3 py-2" id="statPromedioMateria">Promedio Materia: 0.0</span>
                    </div>

                </div>

            </div>

            {{-- Footer con Botón Guardar --}}
            <div class="modal-footer bg-light border-0 px-4 py-3 d-flex justify-content-between" style="border-bottom-left-radius: 18px; border-bottom-right-radius: 18px;">
                <button type="button" class="btn btn-secondary px-4 fw-semibold" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success px-4 py-2 fw-bold shadow-sm" id="btnGuardarCalifsMateria" onclick="guardarCalificacionesMateriaSeleccionada()">
                    <i class="fa-solid fa-floppy-disk me-2"></i> Guardar Calificaciones de la Asignatura
                </button>
            </div>

        </div>
    </div>
</div>

<script>
let listaTodosGrupos = [];
let filtroCctActual = '';
let grupoCapturaActualId = null;
let datosGrupoMateriaActual = null;

// ==========================================
// CÁLCULO DE PROGRESO DE PERIODO
// ==========================================
function calcularProgresoPeriodo(g) {
    if (!g.fechaInicio) return { percent: 0, nivelText: '', inicioPeriodo: '—', finPeriodo: '—' };

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

    let periodStartDate = parseToUTCDate(g.fechaInicio);
    if (!periodStartDate) return { percent: 0, nivelText: '', inicioPeriodo: '—', finPeriodo: '—' };

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
    const periodLabel = isTrimestral ? "Trim." : "Sem.";

    const now = new Date();
    const todayUTC = new Date(Date.UTC(now.getFullYear(), now.getMonth(), now.getDate()));

    const toDMY = (d) => {
        if (!d || isNaN(d.getTime())) return '—';
        const dd = String(d.getUTCDate()).padStart(2, '0');
        const mm = String(d.getUTCMonth() + 1).padStart(2, '0');
        const yyyy = d.getUTCFullYear();
        return `${dd}/${mm}/${yyyy}`;
    };

    let periodEndDate;

    if (!isTrimestral) {
        // LÓGICA SEMESTRAL (Escolarizado / BTI)
        let currentSemester = 1;
        if (idNivelActualDb !== null && idNivelActualDb !== undefined && idNivelActualDb >= 7) {
            currentSemester = idNivelActualDb - 6;
        }

        periodEndDate = parseToUTCDate(g.fechaFin);
        
        if (!periodEndDate) {
            // Fallback si no tiene fechaFin
            const startYear = periodStartDate.getUTCFullYear();
            const isFall = (currentSemester % 2 !== 0);
            let periodYear = startYear;
            if (isFall) {
                periodYear = startYear + Math.floor((currentSemester - 1) / 2);
            } else {
                periodYear = startYear + Math.floor(currentSemester / 2);
            }
            if (isFall) {
                periodStartDate = new Date(Date.UTC(periodYear, 7, 1)); // 1 de Agosto
                periodEndDate = new Date(Date.UTC(periodYear, 11, 31)); // 31 de Diciembre
            } else {
                periodStartDate = new Date(Date.UTC(periodYear, 1, 1)); // 1 de Febrero
                periodEndDate = new Date(Date.UTC(periodYear, 6, 31)); // 31 de Julio
            }
        }

        // Porcentaje de progreso
        let percent = 0;
        if (todayUTC.getTime() >= periodEndDate.getTime()) {
            percent = 100;
        } else if (todayUTC.getTime() <= periodStartDate.getTime()) {
            percent = 0;
        } else {
            const total = periodEndDate.getTime() - periodStartDate.getTime();
            const elapsed = todayUTC.getTime() - periodStartDate.getTime();
            percent = Math.round((elapsed / total) * 100);
            percent = Math.max(0, Math.min(100, percent));
        }

        return {
            percent: percent,
            nivelText: `${currentSemester}° ${periodLabel}`,
            inicioPeriodo: toDMY(periodStartDate),
            finPeriodo: toDMY(periodEndDate)
        };
    }

    // LÓGICA TRIMESTRAL (BGNE)
    let currentTrimestre = 1;
    if (idNivelActualDb !== null && idNivelActualDb !== undefined && idNivelActualDb >= 1 && idNivelActualDb <= 6) {
        currentTrimestre = idNivelActualDb;
    }

    // Cada trimestre dura 13 semanas (91 días)
    const weeksOffset = (currentTrimestre - 1) * 13;
    
    // periodStartDate es la fechaInicio original del primer trimestre
    periodStartDate = new Date(periodStartDate.getTime() + (weeksOffset * 7 * 24 * 60 * 60 * 1000));
    periodEndDate = new Date(periodStartDate.getTime() + (12 * 7 * 24 * 60 * 60 * 1000));

    // Porcentaje de progreso
    let percent = 0;
    if (todayUTC.getTime() >= periodEndDate.getTime()) {
        percent = 100;
    } else if (todayUTC.getTime() <= periodStartDate.getTime()) {
        percent = 0;
    } else {
        const total = periodEndDate.getTime() - periodStartDate.getTime();
        const elapsed = todayUTC.getTime() - periodStartDate.getTime();
        percent = Math.round((elapsed / total) * 100);
        percent = Math.max(0, Math.min(100, percent));
    }

    return {
        percent: percent,
        nivelText: `${currentTrimestre}° ${periodLabel}`,
        inicioPeriodo: toDMY(periodStartDate),
        finPeriodo: toDMY(periodEndDate)
    };
}

function formatearFecha(fecha) {
    if (!fecha) return '—';
    try {
        const d = new Date(fecha);
        if (isNaN(d.getTime())) return fecha;
        const dd = String(d.getUTCDate()).padStart(2, '0');
        const mm = String(d.getUTCMonth() + 1).padStart(2, '0');
        const yyyy = d.getUTCFullYear();
        return `${dd}/${mm}/${yyyy}`;
    } catch(e) {
        return fecha;
    }
}

// Convertidor de número a letra para calificaciones escolares
function numeroALetrasCalificacion(num) {
    if (num === null || num === undefined || isNaN(num) || num === '') return '—';
    const n = parseFloat(num);
    if (isNaN(n)) return '—';

    const entero = Math.floor(n);
    const decimal = Math.round((n - entero) * 10);

    const nombres = {
        0: 'CERO', 1: 'UNO', 2: 'DOS', 3: 'TRES', 4: 'CUATRO',
        5: 'CINCO', 6: 'SEIS', 7: 'SIETE', 8: 'OCHO', 9: 'NUEVE', 10: 'DIEZ'
    };

    let texto = nombres[entero] || String(entero);
    if (decimal > 0 && decimal <= 9) {
        texto += ` PUNTO ${nombres[decimal] || decimal}`;
    }
    return texto;
}

// ==========================================
// CARGAR GRUPOS DESDE EL BACKEND
// ==========================================
function cargarGruposCaptura() {
    const loading = document.getElementById('loadingGrupos');
    loading.style.display = 'block';

    fetch('/grupos/lista?limit=200')
        .then(res => res.json())
        .then(res => {
            listaTodosGrupos = Array.isArray(res.data) ? res.data : [];
            aplicarFiltros();
        })
        .catch(err => {
            console.error('Error al cargar grupos:', err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar los grupos.',
                confirmButtonColor: 'rgb(49, 125, 146)'
            });
        })
        .finally(() => {
            loading.style.display = 'none';
        });
}

function setFiltroCct(cctVal) {
    filtroCctActual = cctVal;
    aplicarFiltros();
}

function aplicarFiltros() {
    const estatusFiltro = document.getElementById('selectFiltroEstatus').value;
    const busqueda = (document.getElementById('buscadorGrupoCaptura').value || '').toLowerCase().trim();

    const filtrados = listaTodosGrupos.filter(g => {
        if (filtroCctActual !== '') {
            if (String(g.id_centroTrabajo) !== String(filtroCctActual)) {
                return false;
            }
        }
        if (estatusFiltro !== '') {
            const statusG = (g.statusGrupo || 'ACTIVO').toUpperCase();
            if (statusG !== estatusFiltro) {
                return false;
            }
        }
        if (busqueda !== '') {
            const clave = (g.clave || '').toLowerCase();
            const cct = (g.nombreCentroTrabajo || '').toLowerCase();
            const nivel = (g.nombre_nivel || '').toLowerCase();
            if (!clave.includes(busqueda) && !cct.includes(busqueda) && !nivel.includes(busqueda)) {
                return false;
            }
        }
        return true;
    });

    renderTablaGrupos(filtrados);
}

function renderTablaGrupos(grupos) {
    const tbody = document.getElementById('tbodyGruposCaptura');
    const tableWrapper = document.getElementById('contenedorTablaGruposWrapper');
    const emptyState = document.getElementById('emptyStateGrupos');

    if (!grupos.length) {
        tableWrapper.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }

    tableWrapper.style.display = 'block';
    emptyState.style.display = 'none';
    let html = '';

    grupos.forEach((g, idx) => {
        const status = (g.statusGrupo || 'ACTIVO').toUpperCase();
        const statusBadgeClass = status === 'ACTIVO' ? 'bg-success' : 'bg-danger';

        const cctNombre = g.nombreCentroTrabajo || (g.id_centroTrabajo === 3 ? 'BGNE' : (g.id_centroTrabajo === 2 ? 'BTI' : (g.id_centroTrabajo === 1 ? 'INF. Y COMP.' : '—')));
        const cctBadgeClass = g.id_centroTrabajo === 3 ? 'cct-badge-bgne' : (g.id_centroTrabajo === 2 ? 'cct-badge-bti' : 'cct-badge-ic');

        const nivelNombre = g.nombre_nivel || (g.id_nivel_academico ? (g.id_nivel_academico <= 6 ? `${g.id_nivel_academico}° Trimestre` : `${g.id_nivel_academico - 6}° Semestre`) : '—');
        const progreso = calcularProgresoPeriodo(g);

        const fechaIniStr = formatearFecha(g.fechaInicio);
        const fechaFinStr = formatearFecha(g.fechaFin);

        html += `
        <tr>
            <td class="text-muted fw-bold">${idx + 1}</td>
            <td>
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-users me-2" style="color: rgb(49, 125, 146);"></i>
                    <strong class="text-dark fs-6">${g.clave}</strong>
                </div>
            </td>
            <td>
                <span class="badge ${cctBadgeClass} px-2 py-1">${cctNombre}</span>
            </td>
            <td>
                <span class="badge bg-light text-dark border px-2 py-1">${nivelNombre}</span>
            </td>
            <td>
                <div style="font-size: 0.82rem;">
                    <div><span class="text-muted">Inicio:</span> <strong>${fechaIniStr}</strong></div>
                    <div><span class="text-muted">Fin:</span> <strong>${fechaFinStr}</strong></div>
                </div>
            </td>
            <td>
                <div class="p-2 bg-light rounded-3 border">
                    <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.75rem;">
                        <span class="fw-bold text-secondary">${progreso.nivelText || 'Periodo'}</span>
                        <span class="fw-bold" style="color: rgb(38, 104, 123);">${progreso.percent}%</span>
                    </div>
                    <div class="progress" style="height: 6px; background-color: #cbd5e1; border-radius: 4px; overflow: hidden;">
                        <div class="progress-bar" role="progressbar" style="width: ${progreso.percent}%; background-color: rgb(38, 104, 123);" aria-valuenow="${progreso.percent}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between text-muted mt-1" style="font-size: 0.68rem;">
                        <span>${progreso.inicioPeriodo}</span>
                        <span>${progreso.finPeriodo}</span>
                    </div>
                </div>
            </td>
            <td>
                <span class="badge ${statusBadgeClass}">${status}</span>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-primary fw-bold px-3 py-2 shadow-sm w-100" onclick="abrirCapturaGrupoMateria(${g.id})" style="background: rgb(49, 125, 146); border: none; border-radius: 8px;">
                    <i class="fa-solid fa-pen-to-square me-1"></i> Capturar Calificaciones
                </button>
            </td>
        </tr>
        `;
    });

    tbody.innerHTML = html;
}

// ==========================================
// MODAL DE CAPTURA POR MATERIA Y GRUPO
// ==========================================
function abrirCapturaGrupoMateria(idGrupo, idMateria = null) {
    grupoCapturaActualId = idGrupo;

    const modalEl = document.getElementById('modalCapturaMateriaGrupo');
    const loading = document.getElementById('loadingMateriaGrupo');
    const content = document.getElementById('contentCapturaMateriaGrupo');

    loading.style.display = 'block';
    content.style.display = 'none';

    const modal = new bootstrap.Modal(modalEl);
    modal.show();

    const urlParams = new URLSearchParams(window.location.search);
    const idDocente = urlParams.get('id_docente');

    let url = `/grupos/${idGrupo}/calificaciones-materia`;
    const params = [];
    if (idMateria) params.push(`id_materia=${idMateria}`);
    if (idDocente) params.push(`id_docente=${idDocente}`);
    if (params.length > 0) {
        url += `?${params.join('&')}`;
    }

    fetch(url)
        .then(res => res.json())
        .then(res => {
            if (res.success && res.data) {
                datosGrupoMateriaActual = res.data;
                renderDatosControlOficial(res.data);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: res.error || 'No se pudieron cargar los datos del grupo.',
                    confirmButtonColor: 'rgb(49, 125, 146)'
                });
            }
        })
        .catch(err => {
            console.error('Error:', err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de conexión al obtener información del grupo.',
                confirmButtonColor: 'rgb(49, 125, 146)'
            });
        })
        .finally(() => {
            loading.style.display = 'none';
            content.style.display = 'block';
        });
}

function cambiarMateriaSeleccionada(idMateria) {
    if (!grupoCapturaActualId) return;
    abrirCapturaGrupoMateria(grupoCapturaActualId, idMateria);
}

function cambiarPeriodoSeleccionado(idPeriodo) {
    if (!grupoCapturaActualId || !datosGrupoMateriaActual) return;
    const materias = datosGrupoMateriaActual.materias || [];
    const filtered = materias.filter(m => String(m.id_nivel_academico) === String(idPeriodo));
    if (filtered.length > 0) {
        // Seleccionar la primera materia del periodo elegido
        abrirCapturaGrupoMateria(grupoCapturaActualId, filtered[0].idMateria);
    } else {
        Swal.fire({
            icon: 'info',
            title: 'Sin asignaturas',
            text: 'No hay asignaturas registradas para este periodo en el grupo.',
            confirmButtonColor: 'rgb(49, 125, 146)'
        });
    }
}

function renderDatosControlOficial(data) {
    const grupo = data.grupo || {};
    const materias = data.materias || [];
    const matSel = data.materiaSeleccionada || {};
    const alumnos = data.alumnos || [];

    const isSoloLectura = data.solo_lectura === true;
    const disAttr = isSoloLectura ? 'disabled' : '';

    const cctConfig = data.cct_config || {
        captura_p1: true,
        captura_p2: true,
        captura_p3: true,
        captura_semestral: true,
        captura_extraordinario: true
    };
    
    const isDocente = '{{ session("rol") }}' === 'DOCENTE';
    const disP1 = (isSoloLectura || (isDocente && !cctConfig.captura_p1)) ? 'disabled' : '';
    const disP2 = (isSoloLectura || (isDocente && !cctConfig.captura_p2)) ? 'disabled' : '';
    const disP3 = (isSoloLectura || (isDocente && !cctConfig.captura_p3)) ? 'disabled' : '';
    const disSem = (isSoloLectura || (isDocente && !cctConfig.captura_semestral)) ? 'disabled' : '';
    const disExt = (isSoloLectura || (isDocente && !cctConfig.captura_extraordinario)) ? 'disabled' : '';

    console.log("--- CAPTURA CALIFS DEBUG ---");
    console.log("cctConfig:", cctConfig);
    console.log("isSoloLectura:", isSoloLectura);
    console.log("disSem:", disSem, "disExt:", disExt);

    // Handle Solo Lectura Banner and Save Button
    const banner = document.getElementById('bannerSoloLectura');
    const mensajeBanner = document.getElementById('mensajeBannerSoloLectura');
    const btnGuardar = document.getElementById('btnGuardarCalifsMateria');

    if (isSoloLectura) {
        if (banner) {
            banner.style.setProperty('display', 'flex', 'important');
            mensajeBanner.textContent = data.mensaje_restriccion || 'Captura de calificaciones deshabilitada.';
        }
        if (btnGuardar) {
            btnGuardar.disabled = true;
            btnGuardar.style.display = 'none';
        }
    } else {
        if (banner) {
            banner.style.setProperty('display', 'none', 'important');
        }
        if (btnGuardar) {
            btnGuardar.disabled = false;
            btnGuardar.style.display = 'block';
        }
    }

    // Renderizado del Calendario de Captura por Periodos
    const containerCal = document.getElementById('containerCalendarioPeriodos');
    const gridCal = document.getElementById('gridCalendarioPeriodos');
    
    if (containerCal && gridCal) {
        const fechas = data.grupo_fechas_limite;
        if (fechas) {
            let calHtml = '';
            
            const formatRango = (inicio, fin) => {
                if (!inicio && !fin) return '<span class="text-success fw-bold">Abierto (Sin límite)</span>';
                let txt = '';
                if (inicio) {
                    const dIni = new Date(inicio + 'T00:00:00');
                    txt += `Desde ${dIni.toLocaleDateString('es-MX', {day: 'numeric', month: 'short'})}`;
                }
                if (fin) {
                    const dFin = new Date(fin + 'T00:00:00');
                    txt += ` hasta ${dFin.toLocaleDateString('es-MX', {day: 'numeric', month: 'short'})}`;
                }
                return `<span class="fw-bold">${txt}</span>`;
            };

            // Validar si el periodo es semestral o si es BGNE
            const isBgne = parseInt(grupo.id_centroTrabajo) === 3;
            const isSemestral = matSel.tipoEvaluacion && matSel.tipoEvaluacion.toUpperCase() === 'SEMESTRAL';

            if (isBgne) {
                calHtml += `
                    <div class="col-12 col-md-6">
                        <div class="p-2 border rounded bg-light">
                            <div class="fw-bold text-dark" style="font-size: 0.82rem;">Calificación Final</div>
                            <small class="text-muted" style="font-size: 0.72rem;">${formatRango(fechas.p1_inicio, fechas.p1_fin)}</small>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="p-2 border rounded bg-light">
                            <div class="fw-bold text-dark" style="font-size: 0.82rem;">Examen Extraordinario</div>
                            <small class="text-muted" style="font-size: 0.72rem;">${formatRango(fechas.extraordinario_inicio, fechas.extraordinario_fin)}</small>
                        </div>
                    </div>
                `;
            } else if (isSemestral) {
                calHtml += `
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="p-2 border rounded bg-light">
                            <div class="fw-bold text-dark" style="font-size: 0.82rem;">1er. Parcial</div>
                            <small class="text-muted" style="font-size: 0.72rem;">${formatRango(fechas.p1_inicio, fechas.p1_fin)}</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="p-2 border rounded bg-light">
                            <div class="fw-bold text-dark" style="font-size: 0.82rem;">2do. Parcial</div>
                            <small class="text-muted" style="font-size: 0.72rem;">${formatRango(fechas.p2_inicio, fechas.p2_fin)}</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="p-2 border rounded bg-light">
                            <div class="fw-bold text-dark" style="font-size: 0.82rem;">3er. Parcial</div>
                            <small class="text-muted" style="font-size: 0.72rem;">${formatRango(fechas.p3_inicio, fechas.p3_fin)}</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="p-2 border rounded bg-light">
                            <div class="fw-bold text-dark" style="font-size: 0.82rem;">Semestral/Final</div>
                            <small class="text-muted" style="font-size: 0.72rem;">${formatRango(fechas.semestral_inicio, fechas.semestral_fin)}</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="p-2 border rounded bg-light">
                            <div class="fw-bold text-dark" style="font-size: 0.82rem;">Extraordinario</div>
                            <small class="text-muted" style="font-size: 0.72rem;">${formatRango(fechas.extraordinario_inicio, fechas.extraordinario_fin)}</small>
                        </div>
                    </div>
                `;
            } else {
                calHtml += `
                    <div class="col-12 col-md-4">
                        <div class="p-2 border rounded bg-light">
                            <div class="fw-bold text-dark" style="font-size: 0.82rem;">1er. Parcial</div>
                            <small class="text-muted" style="font-size: 0.72rem;">${formatRango(fechas.p1_inicio, fechas.p1_fin)}</small>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="p-2 border rounded bg-light">
                            <div class="fw-bold text-dark" style="font-size: 0.82rem;">2do. Parcial</div>
                            <small class="text-muted" style="font-size: 0.72rem;">${formatRango(fechas.p2_inicio, fechas.p2_fin)}</small>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="p-2 border rounded bg-light">
                            <div class="fw-bold text-dark" style="font-size: 0.82rem;">3er. Parcial</div>
                            <small class="text-muted" style="font-size: 0.72rem;">${formatRango(fechas.p3_inicio, fechas.p3_fin)}</small>
                        </div>
                    </div>
                `;
            }
            
            gridCal.innerHTML = calHtml;
            containerCal.style.display = 'block';
        } else {
            containerCal.style.display = 'none';
        }
    }

    // Membrete
    document.getElementById('boxNombreInstitucion').textContent = grupo.nombreCentroTrabajo || 'BACHILLERATO INTERAMERICANO';
    document.getElementById('badgeClaveGrupoOficial').textContent = grupo.clave || 'GRUPO';

    // 1. Obtener periodos únicos de las materias
    const uniqueLevels = [];
    const levelIds = new Set();
    materias.forEach(m => {
        const lvlId = m.id_nivel_academico || grupo.id_nivel_academico;
        if (lvlId && !levelIds.has(lvlId)) {
            levelIds.add(lvlId);
            
            let lvlNombre = m.nombreNivel;
            let lvlNumero = m.numeroNivel;
            if (!m.id_nivel_academico) {
                lvlNombre = grupo.nombreNivel || `Nivel ${lvlId}`;
                lvlNumero = grupo.id_nivel_academico ? (grupo.id_nivel_academico <= 6 ? grupo.id_nivel_academico : grupo.id_nivel_academico - 6) : 1;
            }
            
            uniqueLevels.push({
                id: lvlId,
                nombre: lvlNombre || `Nivel ${lvlId}`,
                numero: lvlNumero || 1
            });
        }
    });

    // Ordenar periodos por su número secuencial
    uniqueLevels.sort((a, b) => a.numero - b.numero);

    // Poblar Selector de Periodos
    const selectPeriodo = document.getElementById('selectPeriodoCaptura');
    selectPeriodo.innerHTML = '';
    
    if (uniqueLevels.length === 0) {
        const opt = document.createElement('option');
        opt.value = grupo.id_nivel_academico || '';
        opt.textContent = grupo.nombreNivel || '—';
        selectPeriodo.appendChild(opt);
    } else {
        uniqueLevels.forEach(lvl => {
            const opt = document.createElement('option');
            opt.value = lvl.id;
            opt.textContent = lvl.nombre;
            selectPeriodo.appendChild(opt);
        });
    }

    // Seleccionar el periodo correspondiente a la materia seleccionada
    const currentPeriodId = matSel.id_nivel_academico || grupo.id_nivel_academico;
    if (currentPeriodId) {
        selectPeriodo.value = currentPeriodId;
    }

    // 2. Filtrar materias pertenecientes al periodo seleccionado
    const activePeriodId = selectPeriodo.value;
    const filteredMaterias = materias.filter(m => {
        const lvlId = m.id_nivel_academico || grupo.id_nivel_academico;
        return String(lvlId) === String(activePeriodId);
    });

    // Si la materia seleccionada no está dentro de este periodo filtrado, auto-seleccionar la primera y recargar
    if (filteredMaterias.length > 0) {
        const isCurrentMatInPeriod = filteredMaterias.some(m => String(m.idMateria) === String(data.idMateriaSeleccionada));
        if (!isCurrentMatInPeriod) {
            setTimeout(() => {
                abrirCapturaGrupoMateria(grupoCapturaActualId, filteredMaterias[0].idMateria);
            }, 0);
            return;
        }
    }

    // Poblar Selector de Materias
    const selectMat = document.getElementById('selectMateriaGrupo');
    selectMat.innerHTML = '';

    if (filteredMaterias.length === 0) {
        selectMat.innerHTML = '<option value="">No hay materias registradas</option>';
    } else {
        filteredMaterias.forEach(m => {
            const opt = document.createElement('option');
            opt.value = m.idMateria;
            opt.textContent = m.nombreMateria;
            if (String(m.idMateria) === String(data.idMateriaSeleccionada)) {
                opt.selected = true;
            }
            selectMat.appendChild(opt);
        });
    }

    // Docente de la materia
    const docenteNombre = matSel.nombreDocente || 'Sin docente asignado';
    const nivelDoc = matSel.nivelEstudios ? `${matSel.nivelEstudios}. ` : '';
    document.getElementById('boxNombreDocente').textContent = docenteNombre !== 'Sin docente asignado' ? `${nivelDoc}${docenteNombre}` : docenteNombre;

    // Fechas y modalidad
    document.getElementById('boxFechaInicioGrupo').textContent = formatearFecha(grupo.fechaInicio);
    document.getElementById('boxFechaFinGrupo').textContent = formatearFecha(grupo.fechaFin);

    let modalidad = (grupo.modalidadHorario || '').toUpperCase();
    if (!modalidad) {
        if ((grupo.clave || '').toUpperCase().endsWith('S')) modalidad = 'SISTEMA SABATINO';
        else if ((grupo.clave || '').toUpperCase().endsWith('D')) modalidad = 'SISTEMA DOMINGO';
        else modalidad = 'SISTEMA ESCOLARIZADO';
    }
    document.getElementById('boxModalidadGrupo').textContent = modalidad;

    // Detectar si el grupo es Semestral (BTI o Informatica)
    const isSemestral = (grupo.id_tipoPeriodo === 1 || grupo.id_centroTrabajo === 2 || grupo.id_centroTrabajo === 1);

    // Ajustar thead dinámicamente
    const thead = document.getElementById('theadCalificacionesOficial');
    if (isSemestral) {
        thead.innerHTML = `
            <tr>
                <th style="width: 40px;">N°</th>
                <th style="width: 130px;">Apellido Paterno</th>
                <th style="width: 130px;">Apellido Materno</th>
                <th style="width: 160px;">Nombre(s)</th>
                <th style="width: 80px;">1ER. PARCIAL</th>
                <th style="width: 80px;">2DO. PARCIAL</th>
                <th style="width: 80px;">3ER. PARCIAL</th>
                <th style="width: 80px;">SEMESTRAL</th>
                <th style="width: 80px;">EXTRAORDINARIO</th>
                <th style="width: 95px;">PROMEDIO FINAL</th>
                <th style="width: 140px;">Calificación con Letra</th>
                <th style="width: 120px;">Asistencias</th>
                <th style="min-width: 120px;">Observaciones</th>
            </tr>
        `;
    } else {
        thead.innerHTML = `
            <tr>
                <th style="width: 40px;">N°</th>
                <th style="width: 130px;">Apellido Paterno</th>
                <th style="width: 130px;">Apellido Materno</th>
                <th style="width: 160px;">Nombre(s)</th>
                <th style="width: 120px;">Calificación Final</th>
                <th style="width: 120px;">Extraordinario</th>
                <th style="width: 160px;">Calificación con Letra</th>
                <th style="min-width: 140px;">Observaciones</th>
            </tr>
        `;
    }

    // Poblar Tabla de Alumnos
    const tbody = document.getElementById('tbodyAlumnosCalificacionesMateria');
    if (!alumnos.length) {
        tbody.innerHTML = `<tr><td colspan="${isSemestral ? '13' : '8'}" class="text-center py-4 text-muted">No hay alumnos inscritos en este grupo.</td></tr>`;
        document.getElementById('statTotalAlumnos').textContent = '0';
        document.getElementById('statAprobados').textContent = '0';
        document.getElementById('statReprobados').textContent = '0';
        document.getElementById('statPromedioMateria').textContent = 'Promedio Materia: 0.0';
        const tfoot = document.getElementById('tfootCalificacionesOficial');
        if (tfoot) tfoot.innerHTML = '';
        return;
    }

    let html = '';
    alumnos.forEach((a, idx) => {
        const calif = a.calificacion !== null && a.calificacion !== undefined ? a.calificacion : '';
        const obs = a.observaciones || '';
        const isEquiv = a.es_equivalencia === true;
        const califLetra = isEquiv ? 'EQUIVALENCIA' : numeroALetrasCalificacion(calif);

        let p1Input = '';
        let p2Input = '';
        let p3Input = '';
        let semInput = '';
        let extInput = '';
        let finalInput = '';
        let asistInput = '';

        if (isEquiv) {
            p1Input = `<span class="badge bg-warning text-dark px-2 py-1">EQUIV.</span>`;
            p2Input = `<span class="badge bg-warning text-dark px-2 py-1">EQUIV.</span>`;
            p3Input = `<span class="badge bg-warning text-dark px-2 py-1">EQUIV.</span>`;
            semInput = `<span class="badge bg-warning text-dark px-2 py-1">EQUIV.</span>`;
            extInput = `<span class="badge bg-warning text-dark px-2 py-1">EQUIV.</span>`;
            asistInput = `<span class="badge bg-warning text-dark px-2 py-1">EQUIV.</span>`;
            finalInput = `<span class="badge bg-warning text-dark px-3 py-2 fw-bold d-inline-block">EQUIV.</span>
                          <input type="hidden" class="inp-calif-final" value="0.0">`;
        } else {
            if (isSemestral) {
                p1Input = `<input type="number" step="0.1" min="0" max="10" class="input-calif-celda inp-p1" value="${a.parcial1 !== null && a.parcial1 !== undefined ? a.parcial1 : ''}" oninput="recalcularFilaSemestral(this)" ${disP1}>`;
                p2Input = `<input type="number" step="0.1" min="0" max="10" class="input-calif-celda inp-p2" value="${a.parcial2 !== null && a.parcial2 !== undefined ? a.parcial2 : ''}" oninput="recalcularFilaSemestral(this)" ${disP2}>`;
                p3Input = `<input type="number" step="0.1" min="0" max="10" class="input-calif-celda inp-p3" value="${a.parcial3 !== null && a.parcial3 !== undefined ? a.parcial3 : ''}" oninput="recalcularFilaSemestral(this)" ${disP3}>`;
                semInput = `<input type="number" step="0.1" min="0" max="10" class="input-calif-celda inp-semestral" value="${a.semestral !== null && a.semestral !== undefined ? a.semestral : ''}" oninput="recalcularFilaSemestral(this)" ${disSem}>`;
                extInput = `<input type="number" step="0.1" min="0" max="10" class="input-calif-celda inp-extraordinario" value="${a.extraordinario !== null && a.extraordinario !== undefined ? a.extraordinario : ''}" oninput="recalcularFilaSemestral(this)" ${disExt}>`;
                finalInput = `<input type="number" step="0.1" min="0" max="10" class="input-calif-celda inp-calif-final fw-bold" value="${calif}" readonly style="background-color: #f1f5f9;" ${disAttr}>`;
                
                const aVal = a.asistencias !== null && a.asistencias !== undefined ? a.asistencias : '';
                const tVal = a.total_asistencias !== null && a.total_asistencias !== undefined ? a.total_asistencias : '';
                asistInput = `
                    <div class="d-flex align-items-center justify-content-center gap-1">
                        <input type="number" min="0" class="input-calif-celda inp-asistencias" value="${aVal}" style="width: 45px;" placeholder="0" oninput="recalcularEstadisticasMateria()" ${disAttr}>
                        <span class="text-muted">/</span>
                        <input type="number" min="0" class="input-calif-celda inp-total-asistencias" value="${tVal}" style="width: 45px;" placeholder="0" oninput="recalcularEstadisticasMateria()" ${disAttr}>
                    </div>
                `;
            } else {
                // For BGNE, we only have final grade (which uses disP1) and extraordinary grade
                finalInput = `<input type="number" step="0.1" min="0" max="10" class="input-calif-celda inp-calif-final fw-bold" value="${calif}" oninput="recalcularFilaOficial(this)" placeholder="0.0" ${disP1}>`;
                extInput = `<input type="number" step="0.1" min="0" max="10" class="input-calif-celda inp-extraordinario" value="${a.extraordinario !== null && a.extraordinario !== undefined ? a.extraordinario : ''}" oninput="recalcularFilaOficial(this)" placeholder="0.0" ${disExt}>`;
            }
        }

        if (isSemestral) {
            html += `
            <tr data-alumno-id="${a.idAlumno}" data-is-equivalencia="${isEquiv}">
                <td class="text-center fw-bold">${idx + 1}</td>
                <td class="fw-semibold">${a.apPaterno || ''}</td>
                <td class="fw-semibold">${a.apMaterno || ''}</td>
                <td class="fw-bold text-dark">${a.nombre || ''}</td>
                
                <td style="background-color: #fafafa; text-align: center;">${p1Input}</td>
                <td style="background-color: #fafafa; text-align: center;">${p2Input}</td>
                <td style="background-color: #fafafa; text-align: center;">${p3Input}</td>
                <td style="background-color: #fafafa; text-align: center;">${semInput}</td>
                <td style="background-color: #fffbeb; text-align: center;">${extInput}</td>
                <td style="background-color: #f1f5f9; text-align: center;">${finalInput}</td>
                <td class="text-center fw-bold td-calif-letra" style="font-size: 0.78rem;">${califLetra}</td>
                <td style="background-color: #f8fafc; text-align: center;">${asistInput}</td>
                <td><input type="text" class="input-observaciones-celda inp-obs" value="${obs}" placeholder="Opcional..." ${isEquiv ? 'disabled' : ''} ${disAttr}></td>
            </tr>
            `;
        } else {
            html += `
            <tr data-alumno-id="${a.idAlumno}" data-is-equivalencia="${isEquiv}">
                <td class="text-center fw-bold">${idx + 1}</td>
                <td class="fw-semibold">${a.apPaterno || ''}</td>
                <td class="fw-semibold">${a.apMaterno || ''}</td>
                <td class="fw-bold text-dark">${a.nombre || ''}</td>
                
                <td style="background-color: #f1f5f9; text-align: center;">${finalInput}</td>
                <td style="background-color: #fffbeb; text-align: center;">${extInput}</td>
                <td class="text-center fw-bold td-calif-letra" style="font-size: 0.78rem;">${califLetra}</td>
                <td><input type="text" class="input-observaciones-celda inp-obs" value="${obs}" placeholder="Opcional..." ${isEquiv ? 'disabled' : ''} ${disAttr}></td>
            </tr>
            `;
        }
    });

    tbody.innerHTML = html;

    // Recalcular estilos y estadísticas iniciales
    tbody.querySelectorAll('tr').forEach(tr => {
        const isEquiv = tr.getAttribute('data-is-equivalencia') === 'true';
        if (!isEquiv) {
            if (isSemestral) {
                const inpP1 = tr.querySelector('.inp-p1');
                if (inpP1) recalcularFilaSemestral(inpP1);
            } else {
                const inpFinal = tr.querySelector('.inp-calif-final');
                if (inpFinal) recalcularFilaOficial(inpFinal);
            }
        } else {
            const tdLetra = tr.querySelector('.td-calif-letra');
            if (tdLetra) {
                tdLetra.className = 'text-center fw-bold text-warning td-calif-letra';
            }
        }
    });

    recalcularEstadisticasMateria();
}

function recalcularFilaSemestral(inputEl) {
    const tr = inputEl.closest('tr');
    if (!tr) return;

    const p1Inp = tr.querySelector('.inp-p1');
    const p2Inp = tr.querySelector('.inp-p2');
    const p3Inp = tr.querySelector('.inp-p3');
    const semInp = tr.querySelector('.inp-semestral');
    const extInp = tr.querySelector('.inp-extraordinario');
    const pFinalInp = tr.querySelector('.inp-calif-final');
    const tdLetra = tr.querySelector('.td-calif-letra');

    const v1 = parseFloat(p1Inp.value);
    const v2 = parseFloat(p2Inp.value);
    const v3 = parseFloat(p3Inp.value);

    const config = (datosGrupoMateriaActual && datosGrupoMateriaActual.cct_config) ? datosGrupoMateriaActual.cct_config : {
        captura_p1: true,
        captura_p2: true,
        captura_p3: true,
        captura_semestral: true,
        captura_extraordinario: true
    };
    const isSoloLectura = datosGrupoMateriaActual && datosGrupoMateriaActual.solo_lectura === true;
    const isDocente = '{{ session("rol") }}' === 'DOCENTE';

    // 1. Verificar si los 3 parciales están llenos
    const partialsFilled = !isNaN(v1) && !isNaN(v2) && !isNaN(v3);

    if (partialsFilled) {
        const sumPartials = v1 + v2 + v3;
        if (sumPartials < 18) {
            // No tiene derecho a semestral. Va directamente a extraordinario
            semInp.value = "";
            semInp.disabled = true;
            semInp.placeholder = "N/A";
            
            extInp.disabled = isSoloLectura || (isDocente && !config.captura_extraordinario);
            extInp.placeholder = "0.0";
            
            const extVal = parseFloat(extInp.value);
            if (!isNaN(extVal)) {
                // Promedio final = extraordinario capped at 7.0
                pFinalInp.value = Math.min(extVal, 7.0).toFixed(1);
            } else {
                pFinalInp.value = "";
            }
        } else {
            // Habilitar semestral, deshabilitar extraordinario
            semInp.disabled = isSoloLectura || (isDocente && !config.captura_semestral);
            semInp.placeholder = "0.0";
            
            extInp.value = "";
            extInp.disabled = true;
            extInp.placeholder = "N/A";

            const semVal = parseFloat(semInp.value);
            if (!isNaN(semVal)) {
                // Promedio final = (P1 + P2 + P3 + Semestral) / 4
                pFinalInp.value = ((v1 + v2 + v3 + semVal) / 4).toFixed(1);
            } else {
                pFinalInp.value = "";
            }
        }
    } else {
        // Parciales incompletos, habilitar según calendario y calcular promedio provisional de parciales
        semInp.disabled = isSoloLectura || (isDocente && !config.captura_semestral);
        extInp.disabled = isSoloLectura || (isDocente && !config.captura_extraordinario);
        
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

    // Color rojo en notas menores a 6.0
    const checkRed = (inp) => {
        if (!inp) return;
        const v = parseFloat(inp.value);
        if (!isNaN(v) && v < 6.0) {
            inp.style.color = '#dc2626';
            inp.style.fontWeight = 'bold';
        } else {
            inp.style.color = '#0f172a';
            inp.style.fontWeight = '700';
        }
    };

    checkRed(p1Inp);
    checkRed(p2Inp);
    checkRed(p3Inp);
    checkRed(semInp);
    checkRed(extInp);
    checkRed(pFinalInp);

    // Actualizar Calificación con Letra
    const finalVal = pFinalInp.value;
    tdLetra.textContent = numeroALetrasCalificacion(finalVal);
    if (parseFloat(finalVal) < 6.0) {
        tdLetra.className = 'text-center fw-bold text-danger td-calif-letra';
    } else {
        tdLetra.className = 'text-center fw-bold text-secondary td-calif-letra';
    }

    recalcularEstadisticasMateria();
}

function recalcularFilaOficial(inputEl) {
    const tr = inputEl.closest('tr');
    const isEquiv = tr.getAttribute('data-is-equivalencia') === 'true';
    if (isEquiv) return;

    const p1Inp = tr.querySelector('.inp-p1');
    const p2Inp = tr.querySelector('.inp-p2');
    const p3Inp = tr.querySelector('.inp-p3');
    const extInp = tr.querySelector('.inp-extraordinario');
    const pFinalInp = tr.querySelector('.inp-calif-final');
    const tdLetra = tr.querySelector('.td-calif-letra');

    // Si se escriben parciales y no final manual, calcular promedio de parciales en final
    if (inputEl !== pFinalInp) {
        const v1 = p1Inp ? parseFloat(p1Inp.value) : NaN;
        const v2 = p2Inp ? parseFloat(p2Inp.value) : NaN;
        const v3 = p3Inp ? parseFloat(p3Inp.value) : NaN;
        let vals = [];
        if (!isNaN(v1)) vals.push(v1);
        if (!isNaN(v2)) vals.push(v2);
        if (!isNaN(v3)) vals.push(v3);

        if (vals.length > 0) {
            const prom = (vals.reduce((a, b) => a + b, 0) / vals.length).toFixed(1);
            pFinalInp.value = prom;
        }
    }

    // Color rojo en notas menores a 6.0
    const checkRed = (inp) => {
        if (!inp) return;
        const v = parseFloat(inp.value);
        if (!isNaN(v) && v < 6.0) {
            inp.style.color = '#dc2626';
            inp.style.fontWeight = 'bold';
        } else {
            inp.style.color = '#0f172a';
            inp.style.fontWeight = '700';
        }
    };

    checkRed(p1Inp);
    checkRed(p2Inp);
    checkRed(p3Inp);
    checkRed(extInp);
    checkRed(pFinalInp);

    // Actualizar Calificación con Letra
    let finalVal = pFinalInp.value;
    if (extInp && extInp.value !== "") {
        finalVal = extInp.value;
    }
    tdLetra.textContent = numeroALetrasCalificacion(finalVal);
    if (parseFloat(finalVal) < 6.0) {
        tdLetra.className = 'text-center fw-bold text-danger td-calif-letra';
    } else {
        tdLetra.className = 'text-center fw-bold text-secondary td-calif-letra';
    }

    recalcularEstadisticasMateria();
}

function recalcularEstadisticasMateria() {
    const rows = document.querySelectorAll('#tbodyAlumnosCalificacionesMateria tr');
    const isSemestral = datosGrupoMateriaActual && datosGrupoMateriaActual.grupo && (
        datosGrupoMateriaActual.grupo.id_tipoPeriodo === 1 ||
        datosGrupoMateriaActual.grupo.id_centroTrabajo === 2 ||
        datosGrupoMateriaActual.grupo.id_centroTrabajo === 1
    );

    let total = 0;
    let aprobados = 0;
    let reprobados = 0;
    
    // Arrays to collect column grades
    let p1Vals = [];
    let p2Vals = [];
    let p3Vals = [];
    let semVals = [];
    let extVals = [];
    let finalVals = [];

    rows.forEach(tr => {
        const isEquiv = tr.getAttribute('data-is-equivalencia') === 'true';
        if (isEquiv) return;

        const p1Inp = tr.querySelector('.inp-p1');
        const p2Inp = tr.querySelector('.inp-p2');
        const p3Inp = tr.querySelector('.inp-p3');
        const semInp = tr.querySelector('.inp-semestral');
        const extInp = tr.querySelector('.inp-extraordinario');
        const inpFinal = tr.querySelector('.inp-calif-final');

        if (!inpFinal) return;
        total++;

        // Collect values
        if (p1Inp) { const v = parseFloat(p1Inp.value); if (!isNaN(v)) p1Vals.push(v); }
        if (p2Inp) { const v = parseFloat(p2Inp.value); if (!isNaN(v)) p2Vals.push(v); }
        if (p3Inp) { const v = parseFloat(p3Inp.value); if (!isNaN(v)) p3Vals.push(v); }
        if (semInp) { const v = parseFloat(semInp.value); if (!isNaN(v)) semVals.push(v); }
        if (extInp) { const v = parseFloat(extInp.value); if (!isNaN(v)) extVals.push(v); }
        
        const val = parseFloat(inpFinal.value);
        if (!isNaN(val)) {
            finalVals.push(val);
            if (val >= 6.0) aprobados++;
            else reprobados++;
        }
    });

    const totalEquiv = document.querySelectorAll('#tbodyAlumnosCalificacionesMateria tr[data-is-equivalencia="true"]').length;

    document.getElementById('statTotalAlumnos').textContent = total + totalEquiv;
    document.getElementById('statAprobados').textContent = aprobados;
    document.getElementById('statReprobados').textContent = reprobados;

    const prom = finalVals.length > 0 ? (finalVals.reduce((a, b) => a + b, 0) / finalVals.length).toFixed(1) : '0.0';
    document.getElementById('statPromedioMateria').textContent = `Promedio Materia: ${prom}`;

    // Render averages row at tfoot
    const tfoot = document.getElementById('tfootCalificacionesOficial');
    if (tfoot) {
        const getAvg = (arr) => arr.length > 0 ? (arr.reduce((a, b) => a + b, 0) / arr.length).toFixed(1) : '0.0';
        
        if (isSemestral) {
            tfoot.innerHTML = `
                <tr>
                    <td colspan="4" class="text-end pe-3 fw-bold" style="border: 1.2px solid #0f172a; padding: 6px 8px;">PROMEDIO:</td>
                    <td class="text-center" style="border: 1.2px solid #0f172a; padding: 6px 8px; color: ${parseFloat(getAvg(p1Vals)) < 6.0 ? '#dc2626' : '#0f172a'};">${getAvg(p1Vals)}</td>
                    <td class="text-center" style="border: 1.2px solid #0f172a; padding: 6px 8px; color: ${parseFloat(getAvg(p2Vals)) < 6.0 ? '#dc2626' : '#0f172a'};">${getAvg(p2Vals)}</td>
                    <td class="text-center" style="border: 1.2px solid #0f172a; padding: 6px 8px; color: ${parseFloat(getAvg(p3Vals)) < 6.0 ? '#dc2626' : '#0f172a'};">${getAvg(p3Vals)}</td>
                    <td class="text-center" style="border: 1.2px solid #0f172a; padding: 6px 8px; color: ${parseFloat(getAvg(semVals)) < 6.0 ? '#dc2626' : '#0f172a'};">${getAvg(semVals)}</td>
                    <td class="text-center" style="border: 1.2px solid #0f172a; padding: 6px 8px; color: ${parseFloat(getAvg(extVals)) < 6.0 ? '#dc2626' : '#0f172a'};">${getAvg(extVals)}</td>
                    <td class="text-center" style="border: 1.2px solid #0f172a; padding: 6px 8px; background-color: #e2e8f0; color: ${parseFloat(prom) < 6.0 ? '#dc2626' : '#0f172a'};">${prom}</td>
                    <td colspan="3" style="border: 1.2px solid #0f172a;"></td>
                </tr>
            `;
        } else {
            tfoot.innerHTML = `
                <tr>
                    <td colspan="4" class="text-end pe-3 fw-bold" style="border: 1.2px solid #0f172a; padding: 6px 8px;">PROMEDIO:</td>
                    <td class="text-center" style="border: 1.2px solid #0f172a; padding: 6px 8px; color: ${parseFloat(getAvg(p1Vals)) < 6.0 ? '#dc2626' : '#0f172a'};">${getAvg(p1Vals)}</td>
                    <td class="text-center" style="border: 1.2px solid #0f172a; padding: 6px 8px; color: ${parseFloat(getAvg(p2Vals)) < 6.0 ? '#dc2626' : '#0f172a'};">${getAvg(p2Vals)}</td>
                    <td class="text-center" style="border: 1.2px solid #0f172a; padding: 6px 8px; color: ${parseFloat(getAvg(p3Vals)) < 6.0 ? '#dc2626' : '#0f172a'};">${getAvg(p3Vals)}</td>
                    <td class="text-center" style="border: 1.2px solid #0f172a; padding: 6px 8px; background-color: #e2e8f0; color: ${parseFloat(prom) < 6.0 ? '#dc2626' : '#0f172a'};">${prom}</td>
                    <td colspan="2" style="border: 1.2px solid #0f172a;"></td>
                </tr>
            `;
        }
    }
}

// ==========================================
// GUARDAR CALIFICACIONES DE LA MATERIA
// ==========================================
function guardarCalificacionesMateriaSeleccionada() {
    if (!grupoCapturaActualId || !datosGrupoMateriaActual) return;
    const idMateria = datosGrupoMateriaActual.idMateriaSeleccionada;
    if (!idMateria) {
        Swal.fire({ icon: 'warning', title: 'Atención', text: 'No hay materia seleccionada.' });
        return;
    }

    const btn = document.getElementById('btnGuardarCalifsMateria');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';

    const rows = document.querySelectorAll('#tbodyAlumnosCalificacionesMateria tr');
    const calificaciones = [];

    rows.forEach(tr => {
        const idAlumno = tr.getAttribute('data-alumno-id');
        const isEquiv = tr.getAttribute('data-is-equivalencia') === 'true';
        
        // Omitir el guardado para alumnos que tienen equivalencia en este periodo
        if (isEquiv) return;

        const p1Inp = tr.querySelector('.inp-p1');
        const p2Inp = tr.querySelector('.inp-p2');
        const p3Inp = tr.querySelector('.inp-p3');
        const semInp = tr.querySelector('.inp-semestral');
        const extInp = tr.querySelector('.inp-extraordinario');
        const asistInp = tr.querySelector('.inp-asistencias');
        const totAsistInp = tr.querySelector('.inp-total-asistencias');

        const califFinal = tr.querySelector('.inp-calif-final').value;
        const obs = tr.querySelector('.inp-obs').value;

        if (idAlumno && califFinal !== '') {
            const dataObj = {
                idAlumno: parseInt(idAlumno),
                calificacion: parseFloat(califFinal),
                observaciones: obs
            };
            
            if (p1Inp) dataObj.parcial1 = p1Inp.value !== "" ? parseFloat(p1Inp.value) : null;
            if (p2Inp) dataObj.parcial2 = p2Inp.value !== "" ? parseFloat(p2Inp.value) : null;
            if (p3Inp) dataObj.parcial3 = p3Inp.value !== "" ? parseFloat(p3Inp.value) : null;
            if (semInp) dataObj.semestral = semInp.value !== "" ? parseFloat(semInp.value) : null;
            if (extInp) dataObj.extraordinario = extInp.value !== "" ? parseFloat(extInp.value) : null;
            if (asistInp) dataObj.asistencias = asistInp.value !== "" ? parseInt(asistInp.value) : null;
            if (totAsistInp) dataObj.total_asistencias = totAsistInp.value !== "" ? parseInt(totAsistInp.value) : null;

            calificaciones.push(dataObj);
        }
    });

    fetch(`/grupos/${grupoCapturaActualId}/calificaciones-materia/${idMateria}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ calificaciones: calificaciones })
    })
    .then(res => res.json())
    .then(resp => {
        if (resp.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Guardado!',
                text: 'Calificaciones de la materia registradas exitosamente.',
                confirmButtonColor: 'rgb(49, 125, 146)'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: resp.message || 'No se pudieron guardar las calificaciones.',
                confirmButtonColor: 'rgb(49, 125, 146)'
            });
        }
    })
    .catch(err => {
        console.error('Error al guardar:', err);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al enviar las calificaciones.',
            confirmButtonColor: 'rgb(49, 125, 146)'
        });
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-floppy-disk me-2"></i> Guardar Calificaciones de la Asignatura';
    });
}

// Carga Inicial
document.addEventListener("DOMContentLoaded", function() {
    // Mover modales a document.body para evitar stacking context con navbar y sidebar fijos
    const modal = document.getElementById('modalCapturaMateriaGrupo');
    const modalContainer = document.getElementById('contenedorModal') || document.body;
    if (modal && modal.parentElement !== modalContainer) {
        modalContainer.appendChild(modal);
    }

    cargarGruposCaptura();

    // Detectar params para abrir automáticamente modal
    const urlParams = new URLSearchParams(window.location.search);
    const paramGrupo = urlParams.get('id_grupo');
    const paramMateria = urlParams.get('id_materia');
    if (paramGrupo) {
        setTimeout(() => {
            abrirCapturaGrupoMateria(parseInt(paramGrupo), paramMateria ? parseInt(paramMateria) : null);
        }, 600);
    }
});
</script>

@endsection
