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

            {{-- Filtros CCT --}}
            <div class="col-12 col-xl-7">
                <label class="form-label text-dark fw-bold mb-2">
                    <i class="fa-solid fa-school me-1 text-primary"></i> Filtrar por Centro de Trabajo (CCT):
                </label>
                <div class="d-flex flex-wrap gap-2" id="filtroCctContainer">
                    <button type="button" class="filter-pill-btn active" data-cct="" onclick="setFiltroCct('', this)">
                        <i class="fa-solid fa-layer-group me-1"></i> Todos
                    </button>
                    <button type="button" class="filter-pill-btn" data-cct="3" onclick="setFiltroCct('3', this)">
                        <span class="badge cct-badge-bgne me-1">BGNE</span> Bachillerato No Escolarizado
                    </button>
                    <button type="button" class="filter-pill-btn" data-cct="2" onclick="setFiltroCct('2', this)">
                        <span class="badge cct-badge-bti me-1">BTI</span> Bachillerato Tecnológico
                    </button>
                    <button type="button" class="filter-pill-btn" data-cct="1" onclick="setFiltroCct('1', this)">
                        <span class="badge cct-badge-ic me-1">INF</span> Informática y Computación
                    </button>
                </div>
            </div>

            {{-- Filtro Estatus --}}
            <div class="col-12 col-md-6 col-xl-2">
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
            <div class="col-12 col-md-6 col-xl-3">
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
                                    <span id="boxModuloPeriodo" class="badge bg-secondary text-white px-2 py-1 fs-6">
                                        —
                                    </span>
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
                            <thead style="position: sticky; top: 0; z-index: 2;">
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

    let fechaInicioAbs = new Date(g.fechaInicio);
    if (isNaN(fechaInicioAbs.getTime())) return { percent: 0, nivelText: '', inicioPeriodo: '—', finPeriodo: '—' };

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
    const weeksPerPeriod = isTrimestral ? 13 : 26;
    const periodLabel = isTrimestral ? "Trim." : "Sem.";

    const now = new Date();
    const todayUTC = new Date(Date.UTC(now.getFullYear(), now.getMonth(), now.getDate()));

    let periodNumber = 1;
    let periodStartDate = new Date(currentDate.getTime());
    let periodEndDate = new Date(currentDate.getTime() + ((weeksPerPeriod - 1) * 7 * 24 * 60 * 60 * 1000));

    while (true) {
        periodEndDate = new Date(periodStartDate.getTime() + ((weeksPerPeriod - 1) * 7 * 24 * 60 * 60 * 1000));

        if (todayUTC.getTime() <= periodEndDate.getTime()) {
            break;
        }

        if (groupEndDate && periodStartDate.getTime() > groupEndDate.getTime()) {
            break;
        }
        if (isTrimestral && periodNumber >= 6) break;
        if (!isTrimestral && periodNumber >= 6) break;

        if (groupEndDate && periodEndDate.getTime() >= groupEndDate.getTime()) {
            break;
        }

        periodStartDate = new Date(periodEndDate.getTime() + (7 * 24 * 60 * 60 * 1000));
        periodNumber++;
    }

    const total = periodEndDate.getTime() - periodStartDate.getTime();
    const elapsed = todayUTC.getTime() - periodStartDate.getTime();

    let percent = 0;
    if (total > 0) {
        percent = Math.round((elapsed / total) * 100);
        percent = Math.max(0, Math.min(100, percent));
    }

    const toDMY = (d) => {
        const dd = String(d.getUTCDate()).padStart(2, '0');
        const mm = String(d.getUTCMonth() + 1).padStart(2, '0');
        const yyyy = d.getUTCFullYear();
        return `${dd}/${mm}/${yyyy}`;
    };

    return {
        percent: percent,
        nivelText: `${periodNumber}° ${periodLabel}`,
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

function setFiltroCct(cctVal, btn) {
    filtroCctActual = cctVal;
    document.querySelectorAll('#filtroCctContainer .filter-pill-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
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

    let url = `/grupos/${idGrupo}/calificaciones-materia`;
    if (idMateria) {
        url += `?id_materia=${idMateria}`;
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

function renderDatosControlOficial(data) {
    const grupo = data.grupo || {};
    const materias = data.materias || [];
    const matSel = data.materiaSeleccionada || {};
    const alumnos = data.alumnos || [];

    // Membrete
    document.getElementById('boxNombreInstitucion').textContent = grupo.nombreCentroTrabajo || 'BACHILLERATO INTERAMERICANO';
    document.getElementById('badgeClaveGrupoOficial').textContent = grupo.clave || 'GRUPO';

    // Poblar Selector de Materias
    const selectMat = document.getElementById('selectMateriaGrupo');
    selectMat.innerHTML = '';

    if (materias.length === 0) {
        selectMat.innerHTML = '<option value="">No hay materias registradas</option>';
    } else {
        materias.forEach(m => {
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

    // Módulo / Nivel
    document.getElementById('boxModuloPeriodo').textContent = matSel.nombreNivel || grupo.nombreNivel || '—';

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

    // Poblar Tabla de Alumnos
    const tbody = document.getElementById('tbodyAlumnosCalificacionesMateria');
    if (!alumnos.length) {
        tbody.innerHTML = '<tr><td colspan="10" class="text-center py-4 text-muted">No hay alumnos inscritos en este grupo.</td></tr>';
        document.getElementById('statTotalAlumnos').textContent = '0';
        document.getElementById('statAprobados').textContent = '0';
        document.getElementById('statReprobados').textContent = '0';
        document.getElementById('statPromedioMateria').textContent = 'Promedio Materia: 0.0';
        return;
    }

    let html = '';
    alumnos.forEach((a, idx) => {
        const calif = a.calificacion !== null && a.calificacion !== undefined ? a.calificacion : '';
        const obs = a.observaciones || '';
        const califLetra = numeroALetrasCalificacion(calif);

        html += `
        <tr data-alumno-id="${a.idAlumno}">
            <td class="text-center fw-bold">${idx + 1}</td>
            <td class="fw-semibold">${a.apPaterno || ''}</td>
            <td class="fw-semibold">${a.apMaterno || ''}</td>
            <td class="fw-bold text-dark">${a.nombre || ''}</td>
            
            {{-- P1, P2, P3 --}}
            <td style="background-color: #fafafa;">
                <input type="number" step="0.1" min="0" max="10" class="input-calif-celda inp-p1" value="" oninput="recalcularFilaOficial(this)">
            </td>
            <td style="background-color: #fafafa;">
                <input type="number" step="0.1" min="0" max="10" class="input-calif-celda inp-p2" value="" oninput="recalcularFilaOficial(this)">
            </td>
            <td style="background-color: #fafafa;">
                <input type="number" step="0.1" min="0" max="10" class="input-calif-celda inp-p3" value="" oninput="recalcularFilaOficial(this)">
            </td>

            {{-- Calificación Final --}}
            <td style="background-color: #f1f5f9;">
                <input type="number" step="0.1" min="0" max="10" class="input-calif-celda inp-calif-final fw-bold" value="${calif}" oninput="recalcularFilaOficial(this)" placeholder="0.0">
            </td>

            {{-- Calificación con Letra --}}
            <td class="text-center fw-bold text-secondary td-calif-letra" style="font-size: 0.78rem;">
                ${califLetra}
            </td>

            {{-- Observaciones --}}
            <td>
                <input type="text" class="input-observaciones-celda inp-obs" value="${obs}" placeholder="Opcional...">
            </td>
        </tr>
        `;
    });

    tbody.innerHTML = html;

    // Recalcular estilos y estadísticas iniciales
    tbody.querySelectorAll('tr').forEach(tr => {
        const inpFinal = tr.querySelector('.inp-calif-final');
        if (inpFinal) recalcularFilaOficial(inpFinal);
    });
}

function recalcularFilaOficial(inputEl) {
    const tr = inputEl.closest('tr');
    const p1Inp = tr.querySelector('.inp-p1');
    const p2Inp = tr.querySelector('.inp-p2');
    const p3Inp = tr.querySelector('.inp-p3');
    const pFinalInp = tr.querySelector('.inp-calif-final');
    const tdLetra = tr.querySelector('.td-calif-letra');

    // Si se escriben parciales y no final manual, calcular promedio de parciales en final
    if (inputEl !== pFinalInp) {
        const v1 = parseFloat(p1Inp.value);
        const v2 = parseFloat(p2Inp.value);
        const v3 = parseFloat(p3Inp.value);
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

function recalcularEstadisticasMateria() {
    const rows = document.querySelectorAll('#tbodyAlumnosCalificacionesMateria tr');
    let total = 0;
    let aprobados = 0;
    let reprobados = 0;
    let sumaNotas = 0;
    let evaluados = 0;

    rows.forEach(tr => {
        const inpFinal = tr.querySelector('.inp-calif-final');
        if (!inpFinal) return;
        total++;
        const val = parseFloat(inpFinal.value);
        if (!isNaN(val)) {
            evaluados++;
            sumaNotas += val;
            if (val >= 6.0) aprobados++;
            else reprobados++;
        }
    });

    document.getElementById('statTotalAlumnos').textContent = total;
    document.getElementById('statAprobados').textContent = aprobados;
    document.getElementById('statReprobados').textContent = reprobados;

    const prom = evaluados > 0 ? (sumaNotas / evaluados).toFixed(1) : '0.0';
    document.getElementById('statPromedioMateria').textContent = `Promedio Materia: ${prom}`;
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
        const califFinal = tr.querySelector('.inp-calif-final').value;
        const obs = tr.querySelector('.inp-obs').value;

        if (idAlumno && califFinal !== '') {
            calificaciones.push({
                idAlumno: parseInt(idAlumno),
                calificacion: parseFloat(califFinal),
                observaciones: obs
            });
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
});
</script>

@endsection
