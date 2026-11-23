@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/estilosAlumnosOnline.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .swal2-container { z-index: 99999 !important; }
    .online-main-card { overflow: visible !important; }
    .table-responsive-online { overflow: visible !important; min-height: 280px; }
    .dropdown-menu { z-index: 1060 !important; }

    /* Semáforo de atraso */
    .sem { display: inline-flex; align-items: center; gap: 6px; padding: 3px 8px; font-size: 0.75rem; font-weight: 600; border: 1px solid; white-space: nowrap; }
    .sem::before { content: ""; width: 8px; height: 8px; background: currentColor; }
    .sem-VERDE { color: #15803D; background: #F0FDF4; border-color: #BBF7D0; }
    .sem-AMARILLO { color: #A16207; background: #FEFCE8; border-color: #FDE68A; }
    .sem-NARANJA { color: #C2410C; background: #FFF7ED; border-color: #FED7AA; }
    .sem-ROJO { color: #B91C1C; background: #FEF2F2; border-color: #FECACA; }
    .sem-GRIS { color: #475569; background: #F8FAFC; border-color: #E2E8F0; }

    .filtros-sem { display: flex; flex-wrap: wrap; gap: 6px; }
    .filtro-sem { border: 1px solid #E2E8F0; background: #FFFFFF; color: #334155; font-size: 0.78rem; font-weight: 600; padding: 6px 10px; cursor: pointer; }
    .filtro-sem b { margin-left: 4px; font-variant-numeric: tabular-nums; }
    .filtro-sem.activo { border-color: #26687B; background: #EFF6F7; color: #26687B; }

    .celda-sub { display: block; font-size: 0.72rem; color: #64748B; margin-top: 2px; }

    /* Bloques de datos */
    .datos-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); border: 1px solid #E2E8F0; background: #F8FAFC; }
    .datos-grid > div { padding: 8px 12px; border-left: 1px solid #E2E8F0; border-top: 1px solid #E2E8F0; margin: -1px 0 0 -1px; }
    .datos-grid span { display: block; font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; color: #64748B; }
    .datos-grid b { font-size: 0.875rem; color: #0F172A; }

    .campo-label { font-size: 0.75rem; font-weight: 600; color: #334155; margin-bottom: 4px; display: block; }
    .campo { width: 100%; height: 38px; border: 1px solid #CBD5E1; border-radius: 0; padding: 0 10px; font-size: 0.875rem; color: #0F172A; background: #FFFFFF; }
    .campo:focus { outline: 2px solid #26687B; outline-offset: -1px; border-color: #26687B; }
    .ayuda { font-size: 0.72rem; color: #64748B; margin-top: 4px; }
    .aviso { border: 1px solid #FCD34D; background: #FFFBEB; color: #92400E; font-size: 0.8rem; padding: 8px 12px; }
    .aviso-error { border-color: #FECACA; background: #FEF2F2; color: #991B1B; }
    .aviso-info { border-color: #BBDBE1; border-left: 3px solid #26687B; background: #EFF6F7; color: #1F5565; }

    .tabla-mini { width: 100%; border-collapse: collapse; font-size: 0.8rem; }
    .tabla-mini th { background: #F8FAFC; color: #475569; font-size: 0.6875rem; text-transform: uppercase; font-weight: 700; text-align: left; padding: 6px 8px; border-bottom: 1px solid #E2E8F0; }
    .tabla-mini td { padding: 6px 8px; border-bottom: 1px solid #F1F5F9; vertical-align: top; }
    .tabla-mini .num { text-align: center; font-variant-numeric: tabular-nums; }

    /* Mapa de 78 semanas */
    .mapa-trim { margin-bottom: 14px; }
    .mapa-trim-tit { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #334155; margin-bottom: 4px; }
    .mapa-bloque { display: grid; grid-template-columns: 200px 1fr; gap: 8px; align-items: center; padding: 4px 0; border-top: 1px solid #F1F5F9; }
    .mapa-bloque-nom { font-size: 0.72rem; color: #475569; line-height: 1.25; }
    .mapa-bloque-nom b { display: block; color: #0F172A; }
    .mapa-celdas { display: flex; flex-wrap: wrap; gap: 4px; }
    .celda-semana { width: 38px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 700; border: 1px solid; font-variant-numeric: tabular-nums; }
    .st-HABILITADA { background: #26687B; border-color: #26687B; color: #FFFFFF; }
    .st-EN_ESPERA { background: #EFF6F7; border-color: #26687B; color: #26687B; }
    .st-ADEUDO { background: #FEF2F2; border-color: #FCA5A5; color: #B91C1C; }
    .st-PENDIENTE { background: #FFFFFF; border-color: #CBD5E1; color: #64748B; }
    .st-NO_APLICA { background: #F1F5F9; border-color: #E2E8F0; color: #CBD5E1; }
    .celda-semana.hoy { outline: 2px solid #0F172A; outline-offset: 1px; }
    .celda-semana.err::after { content: "!"; margin-left: 1px; color: #FDE047; }
    .leyenda { display: flex; flex-wrap: wrap; gap: 12px; font-size: 0.72rem; color: #475569; margin-bottom: 12px; }
    .leyenda span { display: inline-flex; align-items: center; gap: 6px; }
    .leyenda i { width: 14px; height: 14px; border: 1px solid; display: inline-block; }

    .tabs-plano { display: flex; gap: 0; border-bottom: 1px solid #E2E8F0; margin-bottom: 12px; flex-wrap: wrap; }
    .tabs-plano button { border: none; background: none; padding: 8px 14px; font-size: 0.8125rem; font-weight: 600; color: #64748B; border-bottom: 2px solid transparent; margin-bottom: -1px; }
    .tabs-plano button.activo { color: #26687B; border-bottom-color: #26687B; }

    .bloque-conf { border: 1px solid #E2E8F0; margin-bottom: 10px; }
    .bloque-conf-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 8px 12px; background: #F8FAFC; border-bottom: 1px solid #E2E8F0; }
    .curso-conf { display: grid; grid-template-columns: 1fr 120px auto; gap: 8px; align-items: center; padding: 8px 12px; border-top: 1px solid #F1F5F9; }
    .curso-conf:first-of-type { border-top: none; }
    .btn-lite { height: 32px; padding: 0 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #334155; font-size: 0.78rem; font-weight: 600; border-radius: 0; white-space: nowrap; }
    .btn-lite:hover { border-color: #26687B; color: #26687B; }
    .btn-prim { height: 36px; padding: 0 14px; border: none; background: #26687B; color: #FFFFFF; font-size: 0.8125rem; font-weight: 600; border-radius: 0; display: inline-flex; align-items: center; gap: 6px; }
    .btn-prim:hover { background: #1F5565; }
    .btn-prim:disabled { opacity: .6; }
    .btn-sec { height: 36px; padding: 0 14px; border: 1px solid #E2E8F0; background: #FFFFFF; color: #475569; font-size: 0.8125rem; font-weight: 500; border-radius: 0; }

    @media (max-width: 640px) {
        .mapa-bloque { grid-template-columns: 1fr; }
        .curso-conf { grid-template-columns: 1fr; }
    }
</style>

<div class="online-page-container">

    <div class="online-header">
        <div class="online-header-left">
            <div class="online-header-icon"><i class="ti ti-device-desktop"></i></div>
            <div>
                <h1 class="online-header-title">Alumnos online</h1>
                <p class="online-header-subtitle">BGNE: 78 semanas según las fechas de su grupo. Cada pago habilita la semana pendiente más antigua.</p>
            </div>
        </div>
        <div class="online-header-actions">
            <button type="button" class="btn-header-secondary" onclick="abrirConfiguracion()">
                <i class="ti ti-settings" style="color: #26687B;"></i><span>Grupos y bloques</span>
            </button>
            <button type="button" class="btn-header-primary" onclick="abrirModalIntegrarAlumno()">
                <i class="ti ti-user-plus"></i><span>Integrar alumno</span>
            </button>
        </div>
    </div>

    <div class="online-main-card">
        <div class="online-card-header">
            <div class="search-box-wrapper">
                <i class="ti ti-search"></i>
                <input type="text" id="inputBuscarOnline" class="search-input" placeholder="Buscar por nombre o matrícula..." oninput="renderTabla()">
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="filtros-sem" id="filtrosSemaforo"></div>
                <button type="button" class="btn-filter-refresh" onclick="cargarAlumnos()" title="Actualizar lista"><i class="ti ti-refresh"></i></button>
            </div>
        </div>

        <div class="table-responsive table-responsive-online">
            <table class="table-alumnos-online">
                <thead>
                    <tr>
                        <th style="width: 150px;">Matrícula</th>
                        <th>Estudiante</th>
                        <th style="width: 150px;">Grupo</th>
                        <th style="width: 170px; text-align: center;">Semana del calendario</th>
                        <th style="width: 150px; text-align: center;">Pagado hasta</th>
                        <th style="width: 170px; text-align: center;">Atraso</th>
                        <th style="width: 70px; text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbodyAlumnosOnline">
                    <tr><td colspan="7" class="text-center py-4 text-muted">Cargando alumnos online...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ================= MODAL: REGISTRAR PAGO ================= --}}
<div class="modal fade" id="modalPago" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 0; border: 1px solid #E2E8F0;">
            <div class="modal-header-executive">
                <div class="modal-header-executive-left">
                    <div class="modal-icon-box"><i class="ti ti-receipt"></i></div>
                    <div>
                        <h5 class="modal-title-text">Registrar pago</h5>
                        <p class="modal-subtitle-text" id="pagoNombre">-</p>
                    </div>
                </div>
                <button type="button" class="btn-modal-close" data-bs-dismiss="modal" aria-label="Cerrar"><i class="ti ti-x"></i></button>
            </div>
            <form id="formPago" onsubmit="guardarPago(event)" novalidate>
                <div class="modal-body-executive">
                    <div class="datos-grid mb-3" id="pagoEstado"></div>

                    <div class="row g-2 mb-2">
                        <div class="col-sm-4">
                            <label class="campo-label" for="pagoSemanas">Semanas que paga <span class="text-danger">*</span></label>
                            <input type="number" class="campo font-monospace fw-bold" id="pagoSemanas" min="1" value="1" oninput="programarVistaPrevia()">
                            <div class="ayuda" id="pagoSemanasAyuda"></div>
                        </div>
                        <div class="col-sm-4">
                            <label class="campo-label" for="pagoMonto">Monto total <span class="text-danger">*</span></label>
                            <input type="number" class="campo font-monospace fw-bold" id="pagoMonto" min="0.01" step="0.01" placeholder="0.00">
                        </div>
                        <div class="col-sm-4">
                            <label class="campo-label" for="pagoFecha">Fecha del pago</label>
                            <input type="date" class="campo" id="pagoFecha" onchange="programarVistaPrevia()">
                        </div>
                        <div class="col-sm-4">
                            <label class="campo-label" for="pagoMetodo">Método</label>
                            <select class="campo" id="pagoMetodo">
                                <option value="EFECTIVO">Efectivo</option>
                                <option value="TRANSFERENCIA">Transferencia</option>
                                <option value="DEPOSITO">Depósito</option>
                                <option value="TARJETA">Tarjeta</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="campo-label" for="pagoFolio">Folio del recibo</label>
                            <input type="text" class="campo font-monospace" id="pagoFolio" placeholder="Se genera si lo dejas vacío">
                        </div>
                        <div class="col-sm-4">
                            <label class="campo-label" for="pagoObs">Observaciones</label>
                            <input type="text" class="campo" id="pagoObs" placeholder="Opcional">
                        </div>
                    </div>

                    <div class="campo-label mt-3">Semanas que cubre este pago</div>
                    <div id="pagoVistaPrevia" class="ayuda">Calculando…</div>
                </div>
                <div class="modal-footer-executive">
                    <button type="button" class="btn-sec" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-prim" id="btnGuardarPago"><i class="ti ti-check"></i> Registrar pago</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ================= MODAL: DETALLE (SEMANAS Y PAGOS) ================= --}}
<div class="modal fade" id="modalDetalle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 0; border: 1px solid #E2E8F0;">
            <div class="modal-header-executive">
                <div class="modal-header-executive-left">
                    <div class="modal-icon-box"><i class="ti ti-calendar-week"></i></div>
                    <div>
                        <h5 class="modal-title-text" id="detNombre">-</h5>
                        <p class="modal-subtitle-text" id="detSub">-</p>
                    </div>
                </div>
                <button type="button" class="btn-modal-close" data-bs-dismiss="modal" aria-label="Cerrar"><i class="ti ti-x"></i></button>
            </div>
            <div class="modal-body-executive">
                <div class="datos-grid mb-3" id="detEstado"></div>
                <div class="tabs-plano" role="tablist">
                    <button type="button" class="activo" data-tab="detTabSemanas" onclick="cambiarTab(this)">Semanas</button>
                    <button type="button" data-tab="detTabEntregas" onclick="cambiarTab(this)">Semanas habilitadas</button>
                    <button type="button" data-tab="detTabPagos" onclick="cambiarTab(this)">Pagos</button>
                </div>
                <div id="detTabSemanas"></div>
                <div id="detTabEntregas" hidden></div>
                <div id="detTabPagos" hidden></div>
            </div>
            <div class="modal-footer-executive">
                <button type="button" class="btn-sec" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

{{-- ================= MODAL: INTEGRAR ALUMNO ================= --}}
<div class="modal fade" id="modalIntegrar" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content" style="border-radius: 0; border: 1px solid #E2E8F0;">
            <div class="modal-header-executive">
                <div class="modal-header-executive-left">
                    <div class="modal-icon-box"><i class="ti ti-device-laptop"></i></div>
                    <div>
                        <h5 class="modal-title-text">Integrar alumno a online</h5>
                        <p class="modal-subtitle-text">Se queda en su mismo grupo BGNE e inicia el siguiente trimestre desde la semana 1</p>
                    </div>
                </div>
                <button type="button" class="btn-modal-close" data-bs-dismiss="modal" aria-label="Cerrar"><i class="ti ti-x"></i></button>
            </div>
            <form id="formIntegrar" onsubmit="guardarIntegracion(event)" novalidate>
                <div class="modal-body-executive">
                    <div class="mb-3" id="intBuscarBox">
                        <label class="campo-label" for="intBuscar">Alumno <span class="text-danger">*</span></label>
                        <input type="text" class="campo" id="intBuscar" placeholder="Escribe nombre o matrícula..." oninput="buscarAlumnos(this.value)" autocomplete="off">
                        <div id="intResultados" class="list-group mt-1" style="display: none; max-height: 200px; overflow-y: auto; border-radius: 0;"></div>
                    </div>
                    <div id="intSeleccionado" class="mb-3" style="display: none; border: 1px solid #E2E8F0; background: #F8FAFC; padding: 10px 12px;">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <b id="intNombre" style="font-size: 0.875rem;">-</b>
                                <span class="celda-sub" id="intInfo">-</span>
                            </div>
                            <button type="button" class="btn-lite" onclick="cambiarAlumno()">Cambiar</button>
                        </div>
                    </div>
                    <input type="hidden" id="intIdAlumno">

                    <div id="intVentana" class="mb-3"></div>

                    <div class="row g-2 mb-3" id="intCampos">
                        <div class="col-6">
                            <label class="campo-label" for="intDiaPago">Día de cobro</label>
                            <select class="campo" id="intDiaPago">
                                <option value="SABADO">Sábado</option>
                                <option value="DOMINGO">Domingo</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="campo-label" for="intPassword">Contraseña de Moodle</label>
                            <input type="text" class="campo font-monospace" id="intPassword" placeholder="Bti.matrícula*">
                        </div>
                    </div>
                    <div class="aviso aviso-info">
                        Solo grupos BGNE. El cambio se permite cuando el trimestre en curso del grupo ya concluyó (o en la
                        primera semana del siguiente). El usuario de Moodle es la matrícula en minúsculas
                        (o <code>alu</code> + ID si aún no tiene matrícula).
                    </div>
                </div>
                <div class="modal-footer-executive">
                    <button type="button" class="btn-sec" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-prim" id="btnIntegrar"><i class="ti ti-check"></i> Integrar a online</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ================= MODAL: CONFIGURACIÓN ================= --}}
<div class="modal fade" id="modalConfig" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 0; border: 1px solid #E2E8F0;">
            <div class="modal-header-executive">
                <div class="modal-header-executive-left">
                    <div class="modal-icon-box"><i class="ti ti-settings"></i></div>
                    <div>
                        <h5 class="modal-title-text">Grupos y bloques</h5>
                        <p class="modal-subtitle-text">Calendario de los grupos BGNE, duración de los bloques y cursos de Moodle</p>
                    </div>
                </div>
                <button type="button" class="btn-modal-close" data-bs-dismiss="modal" aria-label="Cerrar"><i class="ti ti-x"></i></button>
            </div>
            <div class="modal-body-executive">
                <div class="tabs-plano">
                    <button type="button" class="activo" data-tab="cfgTabGrupos" onclick="cambiarTab(this)">Grupos BGNE</button>
                    <button type="button" data-tab="cfgTabBloques" onclick="cambiarTab(this)">Bloques y cursos de Moodle</button>
                    <button type="button" data-tab="cfgTabProceso" onclick="cambiarTab(this)">Habilitación semanal</button>
                </div>

                <div id="cfgTabGrupos">
                    <div class="aviso aviso-info mb-3">
                        Las fechas salen de la fecha de inicio de cada grupo: el trimestre N inicia 13 semanas después del anterior.
                        Un grupo puede tener alumnos presenciales y online al mismo tiempo.
                    </div>
                    <div id="cfgGruposLista" style="overflow-x: auto;"></div>
                </div>

                <div id="cfgTabBloques" hidden>
                    <div class="aviso aviso-info mb-3">
                        Los 3 bloques de cada trimestre deben sumar 13 semanas. En cada curso de Moodle, cada sección semanal
                        se restringe al grupo <code>Semana_N</code>; con <b>Crear grupos</b> se crean los grupos que falten.
                    </div>
                    <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                        <select class="campo" id="cfgPlan" style="width: auto;" onchange="renderBloques()"></select>
                        <div class="tabs-plano mb-0" id="cfgTrimTabs" style="border-bottom: none;"></div>
                    </div>
                    <div id="cfgBloques"></div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="ayuda" id="cfgSumas"></span>
                        <button type="button" class="btn-prim" onclick="guardarBloques()"><i class="ti ti-device-floppy"></i> Guardar duración de bloques</button>
                    </div>
                </div>

                <div id="cfgTabProceso" hidden>
                    <p style="font-size: 0.8125rem; color: #334155;">
                        Cada sábado a las 07:00 la API habilita las semanas que se pagaron por adelantado y reintenta las que
                        no se pudieron aplicar en Moodle. También corre una revisión diaria y al iniciar la API.
                        Si necesitas forzarlo ahora, usa el botón.
                    </p>
                    <button type="button" class="btn-prim" id="btnHabilitar" onclick="habilitarAhora()"><i class="ti ti-player-play"></i> Ejecutar ahora</button>
                    <div id="cfgProcesoRes" class="mt-3"></div>
                </div>
            </div>
            <div class="modal-footer-executive">
                <button type="button" class="btn-sec" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const CSRF = '{{ csrf_token() }}';
    const SEMAFOROS = [
        { clave: 'TODOS', texto: 'Todos' },
        { clave: 'VERDE', texto: 'Al corriente' },
        { clave: 'AMARILLO', texto: 'Alerta baja' },
        { clave: 'NARANJA', texto: 'Preventiva' },
        { clave: 'ROJO', texto: 'Máxima' },
        { clave: 'GRIS', texto: 'Sin calendario' },
    ];
    const ESTADOS = {
        HABILITADA: 'Pagada y habilitada', EN_ESPERA: 'Pagada, se habilita en su fecha',
        ADEUDO: 'Vencida sin pago', PENDIENTE: 'Por pagar', NO_APLICA: 'Antes de su ingreso',
    };

    let alumnos = [];
    let filtroSem = 'TODOS';
    let config = null;
    let pagoActual = null;
    let trimCfg = 1;

    // ---------------------------------------------------------------- utilidades
    const $ = id => document.getElementById(id);
    const esc = v => String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const dmy = iso => { if (!iso) return ''; const [y, m, d] = String(iso).slice(0, 10).split('-'); return `${d}/${m}/${y}`; };
    const dmyh = iso => iso ? `${dmy(iso)} ${String(iso).slice(11, 16)}` : '';
    const hoyISO = () => { const d = new Date(); return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`; };
    const colorSem = e => (e && e.configurado) ? e.semaforo.color : 'GRIS';
    const modal = id => bootstrap.Modal.getOrCreateInstance($(id));

    async function api(url, opciones = {}) {
        const op = { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }, ...opciones };
        if (op.body && typeof op.body !== 'string') {
            op.headers['Content-Type'] = 'application/json';
            op.body = JSON.stringify(op.body);
        }
        try {
            const res = await fetch(url, op);
            const data = await res.json().catch(() => ({}));
            return { ok: res.ok && data.success !== false, data, status: res.status };
        } catch (e) {
            return { ok: false, data: { message: 'No hubo conexión con el servidor.' }, status: 0 };
        }
    }
    const msgError = (r, def) => (r.data && (r.data.message || r.data.error)) || def;

    window.cambiarTab = function (btn) {
        const cont = btn.parentElement;
        cont.querySelectorAll('button').forEach(b => {
            b.classList.toggle('activo', b === btn);
            if (b.dataset.tab) $(b.dataset.tab).hidden = b !== btn;
        });
    };

    function semaforoHtml(e) {
        if (!e || !e.configurado) return `<span class="sem sem-GRIS" style="white-space: normal; text-align: left;">${esc(e && e.aviso || 'Sin calendario')}</span>`;
        const n = e.atraso;
        return `<span class="sem sem-${e.semaforo.color}">${n === 0 ? 'Al corriente' : `${n} sem. de atraso`}</span>`;
    }
    const ubicacionTxt = u => u ? `T${u.trimestre}, bloque ${u.bloque}, sem. ${u.semana_bloque}` : '';

    // ---------------------------------------------------------------- listado
    window.cargarAlumnos = async function () {
        const tbody = $('tbodyAlumnosOnline');
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-muted">Cargando alumnos online...</td></tr>`;
        const r = await api('/alumnos/online/data');
        if (!r.ok) {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-danger">${esc(msgError(r, 'No se pudieron cargar los alumnos.'))}</td></tr>`;
            return;
        }
        alumnos = r.data.alumnos || [];
        renderFiltros();
        renderTabla();
    };

    function renderFiltros() {
        $('filtrosSemaforo').innerHTML = SEMAFOROS.map(s => {
            const n = s.clave === 'TODOS' ? alumnos.length : alumnos.filter(a => colorSem(a.estado) === s.clave).length;
            if (s.clave === 'GRIS' && !n) return '';
            return `<button type="button" class="filtro-sem ${filtroSem === s.clave ? 'activo' : ''}" onclick="filtrarSemaforo('${s.clave}')">${s.texto}<b>${n}</b></button>`;
        }).join('');
    }
    window.filtrarSemaforo = function (c) { filtroSem = c; renderFiltros(); renderTabla(); };

    window.renderTabla = function () {
        const q = ($('inputBuscarOnline').value || '').toLowerCase().trim();
        const lista = alumnos.filter(a =>
            (filtroSem === 'TODOS' || colorSem(a.estado) === filtroSem) &&
            (!q || (a.nombreCompleto || '').toLowerCase().includes(q) || (a.numeroControl || '').toLowerCase().includes(q)));
        const tbody = $('tbodyAlumnosOnline');
        if (!lista.length) {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center py-5 text-muted">No hay alumnos online con estos filtros.</td></tr>`;
            return;
        }
        tbody.innerHTML = lista.map(a => {
            const e = a.estado || {};
            const semCal = e.configurado
                ? (e.semana_calendario < 1 ? `<span class="text-muted">Inicia ${esc(dmy(a.grupo_inicio))}</span>`
                    : `<b>${Math.min(78, e.semana_calendario)}</b> de 78<span class="celda-sub">${esc(ubicacionTxt(e.ubicacion))}</span>`)
                : '<span class="text-muted">-</span>';
            const pagado = e.ultima_semana_pagada
                ? `Semana <b>${e.ultima_semana_pagada}</b><span class="celda-sub">${e.semanas_pagadas} pagadas${e.en_espera ? `, ${e.en_espera} en espera` : ''}</span>`
                : `<span class="text-muted">Sin pagos</span><span class="celda-sub">Empieza en sem. ${e.semana_inicio || '-'}</span>`;
            const alertaMoodle = e.sin_sincronizar ? `<span class="celda-sub text-danger"><i class="ti ti-alert-triangle"></i> ${e.sin_sincronizar} sem. sin aplicar en Moodle</span>` : '';
            let whatsapp = '';
            const tel = String(a.celularAlumno || '').replace(/\D/g, '');
            if (tel.length >= 10 && e.atraso > 0) {
                const msg = encodeURIComponent(`Hola ${(a.nombre || '').split(' ')[0]}, te contactamos de Control Escolar del Bachillerato Tecnológico Interamericano. Tu colegiatura online tiene ${e.atraso} semana(s) pendiente(s) de pago. Al pagar se habilitan tus actividades en Moodle. ¿Te podemos apoyar?`);
                whatsapp = `<li><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="https://wa.me/${tel.length === 10 ? '52' + tel : tel}?text=${msg}" target="_blank" rel="noopener"><i class="ti ti-brand-whatsapp text-success"></i> Recordatorio por WhatsApp</a></li>`;
            }
            return `<tr>
                <td><span class="font-monospace fw-semibold text-muted" style="font-size: 0.75rem;">${esc(a.numeroControl || 'ID-' + a.idAlumno)}</span></td>
                <td><span style="text-transform: uppercase; font-weight: 600; color: #0F172A; font-size: 0.8125rem;">${esc(a.nombreCompleto)}</span>
                    <span class="celda-sub"><i class="ti ti-brand-moodle"></i> ${esc(a.moodle_username || 'Sin cuenta de Moodle')}</span>${alertaMoodle}</td>
                <td style="font-size: 0.8125rem;"><b>${esc(a.grupo || 'Sin grupo')}</b><span class="celda-sub">Cobro: ${e.dia_pago === 'DOMINGO' ? 'domingo' : 'sábado'}</span></td>
                <td class="text-center" style="font-size: 0.8125rem;">${semCal}</td>
                <td class="text-center" style="font-size: 0.8125rem;">${pagado}</td>
                <td class="text-center">${semaforoHtml(e)}</td>
                <td class="text-center">
                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn-ghost-action" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false" aria-label="Acciones"><i class="ti ti-dots-vertical"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border" style="border-radius: 0; font-size: 0.8125rem; min-width: 240px;">
                            <li><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:void(0)" onclick="abrirPago(${a.idAlumno})"><i class="ti ti-currency-dollar text-success"></i> <b>Registrar pago</b></a></li>
                            <li><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:void(0)" onclick="abrirDetalle(${a.idAlumno})"><i class="ti ti-calendar-week"></i> Ver semanas y pagos</a></li>
                            ${whatsapp}
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:void(0)" onclick="sincronizarMoodle(${a.idAlumno})"><i class="ti ti-refresh"></i> Sincronizar con Moodle</a></li>
                            <li><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:void(0)" onclick="suspensionMoodle(${a.idAlumno}, 'suspender')"><i class="ti ti-lock"></i> Suspender cuenta de Moodle</a></li>
                            <li><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:void(0)" onclick="suspensionMoodle(${a.idAlumno}, 'reactivar')"><i class="ti ti-lock-open"></i> Reactivar cuenta de Moodle</a></li>
                        </ul>
                    </div>
                </td>
            </tr>`;
        }).join('');
    };

    // ---------------------------------------------------------------- pago
    window.abrirPago = function (id) {
        const a = alumnos.find(x => x.idAlumno == id);
        if (!a) return;
        const e = a.estado || {};
        if (!e.configurado) {
            Swal.fire('Sin calendario', (e.aviso || 'El alumno no tiene calendario online') + '.', 'warning');
            return;
        }
        if (!e.siguiente_semana) { Swal.fire('Sin semanas por pagar', 'El alumno ya pagó sus 78 semanas.', 'info'); return; }
        pagoActual = a;
        const restantes = 78 - e.siguiente_semana + 1;
        $('pagoNombre').textContent = `${a.nombreCompleto} (${a.numeroControl || 'ID ' + a.idAlumno})`;
        $('pagoEstado').innerHTML = `
            <div><span>Pagado hasta</span><b>${e.ultima_semana_pagada ? 'Semana ' + e.ultima_semana_pagada : 'Sin pagos'}</b></div>
            <div><span>Semana del calendario</span><b>${e.semana_calendario >= 1 ? Math.min(78, e.semana_calendario) : 'No inicia'}</b></div>
            <div><span>Semanas que debe</span><b>${e.atraso}</b></div>
            <div><span>Atraso</span><b>${semaforoHtml(e)}</b></div>`;
        $('pagoSemanas').value = Math.max(1, Math.min(restantes, e.atraso || 1));
        $('pagoSemanas').max = restantes;
        $('pagoSemanasAyuda').textContent = `Le quedan ${restantes} por pagar.`;
        $('pagoMonto').value = '';
        $('pagoFecha').value = hoyISO();
        $('pagoFecha').max = hoyISO();
        $('pagoMetodo').value = 'EFECTIVO';
        $('pagoFolio').value = '';
        $('pagoObs').value = '';
        vistaPrevia();
        modal('modalPago').show();
    };

    let tVista = null;
    window.programarVistaPrevia = function () { clearTimeout(tVista); tVista = setTimeout(vistaPrevia, 250); };

    async function vistaPrevia() {
        if (!pagoActual) return;
        const n = parseInt($('pagoSemanas').value);
        const cont = $('pagoVistaPrevia');
        if (!(n >= 1)) { cont.innerHTML = '<div class="aviso">Escribe cuántas semanas paga.</div>'; return; }
        cont.innerHTML = 'Calculando…';
        const r = await api(`/alumnos/${pagoActual.idAlumno}/pago-online/vista-previa?semanas=${n}&fecha_pago=${encodeURIComponent($('pagoFecha').value)}`);
        if (!r.ok) { cont.innerHTML = `<div class="aviso aviso-error">${esc(msgError(r, 'No se pudo calcular.'))}</div>`; return; }
        const filas = r.data.semanas.map(s => `<tr>
            <td class="num"><b>${s.semana}</b></td>
            <td>T${s.trimestre}, bloque ${s.bloque}, semana ${s.semana_bloque}</td>
            <td>${s.inmediata ? '<b style="color:#15803D;">Hoy</b>' : `Sábado ${esc(dmy(s.se_habilita))}`}</td>
            <td>${esc(dmyh(s.fecha_limite))}</td>
        </tr>`).join('');
        const espera = r.data.semanas.filter(s => !s.inmediata).length;
        cont.innerHTML = `<table class="tabla-mini"><thead><tr><th class="num">Semana</th><th>Ubicación</th><th>Se habilita</th><th>Entrega hasta</th></tr></thead><tbody>${filas}</tbody></table>
            ${espera ? `<div class="ayuda mt-1">${espera} semana(s) pagada(s) por adelantado: se habilitan solas el sábado de su semana a las 07:00.</div>` : ''}`;
    }

    window.guardarPago = async function (ev) {
        ev.preventDefault();
        const n = parseInt($('pagoSemanas').value), monto = parseFloat($('pagoMonto').value);
        if (!(n >= 1)) return Swal.fire('Faltan datos', 'Indica cuántas semanas paga.', 'warning');
        if (!(monto > 0)) return Swal.fire('Faltan datos', 'Escribe el monto recibido.', 'warning');
        const btn = $('btnGuardarPago'), txt = btn.innerHTML;
        btn.disabled = true; btn.innerHTML = '<i class="ti ti-loader"></i> Registrando y aplicando en Moodle…';
        const r = await api(`/alumnos/${pagoActual.idAlumno}/registrar-pago-online`, { method: 'POST', body: {
            semanas: n, monto, metodo_pago: $('pagoMetodo').value, fecha_pago: $('pagoFecha').value,
            folio_ticket: $('pagoFolio').value.trim(), observaciones: $('pagoObs').value.trim(),
        } });
        btn.disabled = false; btn.innerHTML = txt;
        if (!r.ok) return Swal.fire('No se registró el pago', msgError(r, 'Intenta de nuevo.'), 'error');
        modal('modalPago').hide();
        const d = r.data, p = d.pago;
        const errores = (d.errores_moodle || []).map(e => `<li>${esc(e)}</li>`).join('');
        Swal.fire({
            icon: errores ? 'warning' : 'success',
            title: 'Pago registrado',
            confirmButtonColor: '#26687B',
            html: `<div class="text-start" style="font-size: 0.875rem;">
                <div><b>Folio:</b> ${esc(p.folio_ticket)}</div>
                <div><b>Cubre:</b> semana ${p.semana_desde}${p.semana_hasta !== p.semana_desde ? ' a la ' + p.semana_hasta : ''}</div>
                <div><b>Habilitadas hoy:</b> ${d.habilitadas_hoy.length ? d.habilitadas_hoy.join(', ') : 'ninguna (se habilitan en su fecha)'}</div>
                <div><b>Atraso ahora:</b> ${d.estado.atraso} semana(s)</div>
                ${errores ? `<div class="aviso aviso-error mt-2">El pago quedó guardado, pero Moodle reportó:<ul class="mb-0">${errores}</ul>Se reintentará automáticamente; también puedes usar "Sincronizar con Moodle".</div>` : ''}
            </div>`,
        });
        cargarAlumnos();
    };

    // ---------------------------------------------------------------- detalle
    window.abrirDetalle = async function (id) {
        $('detNombre').textContent = 'Cargando…';
        $('detSub').textContent = '';
        $('detEstado').innerHTML = '';
        $('detTabSemanas').innerHTML = $('detTabEntregas').innerHTML = $('detTabPagos').innerHTML = '';
        cambiarTab(document.querySelector('#modalDetalle .tabs-plano button'));
        modal('modalDetalle').show();
        const r = await api(`/alumnos/${id}/detalle-online`);
        if (!r.ok) { $('detNombre').textContent = 'Error'; $('detTabSemanas').innerHTML = `<div class="aviso aviso-error">${esc(msgError(r, 'No se pudo cargar.'))}</div>`; return; }
        const d = r.data, e = d.estado, al = d.alumno;
        $('detNombre').textContent = al.nombreCompleto;
        $('detSub').textContent = `${al.numeroControl || 'ID ' + al.idAlumno}, grupo ${d.grupo ? d.grupo.clave : 'sin asignar'}, Moodle: ${al.moodle_username || 'sin cuenta'}`;
        $('detEstado').innerHTML = `
            <div><span>Ingresó en</span><b>Semana ${e.semana_inicio} (T${e.trimestre_inicio})</b></div>
            <div><span>Semana del calendario</span><b>${e.semana_calendario >= 1 ? Math.min(78, e.semana_calendario) : '-'}</b></div>
            <div><span>Pagado hasta</span><b>${e.ultima_semana_pagada ? 'Semana ' + e.ultima_semana_pagada : 'Sin pagos'}</b></div>
            <div><span>Próximo cobro</span><b>${e.fecha_proximo_pago ? dmy(e.fecha_proximo_pago) : '-'}</b></div>
            <div><span>Atraso</span><b>${semaforoHtml(e)}</b></div>`;

        if (!d.semanas.length) {
            $('detTabSemanas').innerHTML = `<div class="aviso">${esc(e.aviso || 'Sin calendario')}</div>`;
        } else {
            const leyenda = Object.entries(ESTADOS).map(([k, t]) => `<span><i class="st-${k}"></i>${t}</span>`).join('');
            let html = `<div class="leyenda">${leyenda}<span><i style="outline: 2px solid #0F172A; border-color: #CBD5E1;"></i>Semana actual</span></div>`;
            for (let t = 1; t <= 6; t++) {
                html += `<div class="mapa-trim"><div class="mapa-trim-tit">${t}° trimestre</div>`;
                (d.plan_bloques || []).filter(b => b.trimestre == t).forEach(b => {
                    const celdas = d.semanas.filter(s => s.trimestre == t && s.bloque == b.bloque).map(s => {
                        const tip = [`Semana ${s.semana} (inicia ${dmy(s.inicio)})`, ESTADOS[s.estado],
                            s.fecha_limite ? `Entrega hasta ${dmyh(s.fecha_limite)}` : '', s.moodle_error ? `Moodle: ${s.moodle_error}` : ''].filter(Boolean).join('\n');
                        return `<span class="celda-semana st-${s.estado} ${s.semana === e.semana_calendario ? 'hoy' : ''} ${s.moodle_error ? 'err' : ''}" title="${esc(tip)}">${s.semana}</span>`;
                    }).join('');
                    html += `<div class="mapa-bloque"><div class="mapa-bloque-nom"><b>Bloque ${b.bloque} (${b.semanas} sem.)</b>${esc(b.cursos.join(' y '))}</div><div class="mapa-celdas">${celdas}</div></div>`;
                });
                html += '</div>';
            }
            $('detTabSemanas').innerHTML = html;
        }

        const hab = d.semanas.filter(s => s.estado === 'HABILITADA' || s.estado === 'EN_ESPERA').reverse();
        $('detTabEntregas').innerHTML = hab.length ? `<table class="tabla-mini"><thead><tr><th class="num">Semana</th><th>Ubicación</th><th>Pagada</th><th>Habilitada</th><th>Entrega hasta</th><th>Moodle</th></tr></thead><tbody>
            ${hab.map(s => `<tr><td class="num"><b>${s.semana}</b></td><td>T${s.trimestre}, bloque ${s.bloque}, sem. ${s.semana_bloque}</td>
                <td>${esc(dmy(s.fecha_pago))}</td><td>${s.fecha_habilitada ? esc(dmyh(s.fecha_habilitada)) : `En espera (${esc(dmy(s.inicio))})`}</td>
                <td>${esc(dmyh(s.fecha_limite))}</td>
                <td>${s.estado !== 'HABILITADA' ? '-' : s.moodle_sincronizado ? '<span style="color:#15803D;">Aplicada</span>' : `<span class="text-danger" title="${esc(s.moodle_error || '')}">Pendiente</span>`}</td></tr>`).join('')}
            </tbody></table>` : '<div class="ayuda">Sin semanas pagadas.</div>';

        $('detTabPagos').innerHTML = d.pagos.length ? `<table class="tabla-mini"><thead><tr><th>Folio</th><th>Fecha</th><th>Semanas</th><th class="num">Monto</th><th>Método</th><th>Registró</th></tr></thead><tbody>
            ${d.pagos.map(p => `<tr><td class="font-monospace">${esc(p.folio_ticket)}</td><td>${esc(dmy(p.fecha_pago))}</td>
                <td>${p.semana_desde ? `${p.semana_desde}${p.semana_hasta !== p.semana_desde ? ' a ' + p.semana_hasta : ''}` : `<span class="text-muted">Formato anterior</span>`}</td>
                <td class="num">$${Number(p.monto || 0).toFixed(2)}</td><td>${esc(p.metodo_pago)}</td><td>${esc(p.registrado_por || '')}</td></tr>`).join('')}
            </tbody></table>` : '<div class="ayuda">Sin pagos registrados.</div>';
    };

    // ---------------------------------------------------------------- Moodle manual
    window.sincronizarMoodle = async function (id) {
        Swal.fire({ title: 'Sincronizando con Moodle…', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        const r = await api(`/alumnos/${id}/sincronizar-moodle`, { method: 'POST' });
        Swal.fire(r.ok ? 'Listo' : 'No se completó', r.ok ? `Cuenta ${r.data.moodle_username || ''} y semanas habilitadas aplicadas en Moodle.` : msgError(r, 'Error en Moodle.'), r.ok ? 'success' : 'error');
        cargarAlumnos();
    };

    window.suspensionMoodle = async function (id, accion) {
        const a = alumnos.find(x => x.idAlumno == id);
        const c = await Swal.fire({
            title: accion === 'suspender' ? '¿Suspender la cuenta de Moodle?' : '¿Reactivar la cuenta de Moodle?',
            text: accion === 'suspender' ? `${a ? a.nombreCompleto : ''} no podrá entrar a Moodle hasta que la reactives. No es automático por adeudo.` : '',
            icon: 'question', showCancelButton: true, confirmButtonText: accion === 'suspender' ? 'Suspender' : 'Reactivar',
            cancelButtonText: 'Cancelar', confirmButtonColor: '#26687B',
        });
        if (!c.isConfirmed) return;
        const r = await api(`/alumnos/${id}/toggle-suspension-moodle`, { method: 'POST', body: { accion } });
        Swal.fire(r.ok ? 'Listo' : 'No se completó', r.ok ? r.data.message : msgError(r, 'Error en Moodle.'), r.ok ? 'success' : 'error');
    };

    // ---------------------------------------------------------------- configuración
    async function cargarConfig() {
        const r = await api('/online/configuracion');
        if (!r.ok) throw new Error(msgError(r, 'No se pudo cargar la configuración.'));
        config = r.data;
        return config;
    }

    window.abrirConfiguracion = async function () {
        modal('modalConfig').show();
        $('cfgGruposLista').innerHTML = 'Cargando…';
        try { await cargarConfig(); } catch (e) { $('cfgGruposLista').innerHTML = `<div class="aviso aviso-error">${esc(e.message)}</div>`; return; }
        $('cfgPlan').innerHTML = config.planes.map(p => `<option value="${p.id}">${esc(p.nombre)}</option>`).join('');
        renderGrupos();
        renderBloques();
    };

    function renderGrupos() {
        const g = config.grupos || [];
        const variosPlanes = config.planes.length > 1;
        const planTxt = id => (config.planes.find(p => p.id == id) || {}).nombre || '-';
        $('cfgGruposLista').innerHTML = g.length ? `<table class="tabla-mini"><thead><tr>
                <th>Grupo</th><th>Inicio</th><th>Día</th><th class="num">Semana</th><th class="num">Trimestre</th>
                <th class="num">Online</th><th class="num">Presencial</th><th>Cambio a online</th><th>Plan de bloques</th></tr></thead><tbody>
            ${g.map(x => `<tr>
                <td><b>${esc(x.clave)}</b></td>
                <td>${esc(dmy(x.fecha_inicio))}</td>
                <td>${x.dia_clase === 'DOMINGO' ? 'Domingo' : 'Sábado'}</td>
                <td class="num">${x.semana_actual == null ? '-' : x.semana_actual < 1 ? 'No inicia' : x.semana_actual > 78 ? 'Concluido' : x.semana_actual}</td>
                <td class="num">${x.trimestre_actual ? x.trimestre_actual + '°' : '-'}</td>
                <td class="num">${x.alumnos_online}</td><td class="num">${x.alumnos_presencial}</td>
                <td style="max-width: 280px;">${x.ventana.permitido ? `<span style="color:#15803D; font-weight:600;">Abierto</span>: ${esc(x.ventana.mensaje)}` : `<span class="text-muted">${esc(x.ventana.mensaje)}</span>`}</td>
                <td>${variosPlanes
                    ? `<select class="campo" style="height:32px; min-width:150px;" onchange="cambiarPlanGrupo(${x.id}, this)">
                        <option value="">Plan base (${esc(planTxt(config.planes[0].id))})</option>
                        ${config.planes.map(p => `<option value="${p.id}" ${x.id_plan_online == p.id ? 'selected' : ''}>${esc(p.nombre)}</option>`).join('')}</select>`
                    : esc(planTxt(x.id_plan))}</td>
            </tr>`).join('')}
            </tbody></table>` : '<div class="aviso">No hay grupos BGNE activos.</div>';
    }

    window.cambiarPlanGrupo = async function (id, sel) {
        const r = await api(`/online/grupos/${id}/plan`, { method: 'PUT', body: { id_plan: sel.value } });
        if (!r.ok) { Swal.fire('No se cambió', msgError(r, 'Error.'), 'error'); await cargarConfig(); renderGrupos(); return; }
        Swal.fire({ icon: 'success', title: 'Plan del grupo actualizado', timer: 1300, showConfirmButton: false });
    };

    window.renderBloques = function () {
        const plan = config.planes.find(p => p.id == $('cfgPlan').value) || config.planes[0];
        if (!plan) { $('cfgBloques').innerHTML = '<div class="aviso">No hay planes.</div>'; return; }
        $('cfgTrimTabs').innerHTML = [1, 2, 3, 4, 5, 6].map(t => `<button type="button" class="${t === trimCfg ? 'activo' : ''}" onclick="verTrimestreCfg(${t})">${t}° trim.</button>`).join('');
        const bloques = plan.bloques.filter(b => b.trimestre == trimCfg);
        $('cfgBloques').innerHTML = bloques.map(b => `
            <div class="bloque-conf">
                <div class="bloque-conf-head">
                    <b style="font-size: 0.8125rem;">Bloque ${b.bloque}</b>
                    <label class="d-flex align-items-center gap-2 mb-0" style="font-size: 0.78rem;">Semanas
                        <input type="number" min="1" max="13" class="campo font-monospace" style="width: 70px; height: 32px;" data-bloque="${b.id}" value="${b.semanas}" oninput="actualizarSumas()">
                    </label>
                </div>
                ${b.cursos.map(c => `
                    <div class="curso-conf">
                        <div style="font-size: 0.8rem;"><b>${esc(c.nombre)}</b>
                            <span class="celda-sub">${c.materias.length > 1 ? 'Curso fusionado: ' : 'Materia: '}${esc(c.materias.map(m => m.nombre).join(', '))}</span></div>
                        <input type="number" min="2" class="campo font-monospace" style="height: 32px;" id="curso${c.id}" value="${c.moodle_course_id || ''}" placeholder="ID Moodle" aria-label="ID del curso en Moodle">
                        <div class="d-flex gap-1">
                            <button type="button" class="btn-lite" onclick="guardarCurso(${c.id})">Guardar</button>
                            <button type="button" class="btn-lite" onclick="prepararGrupos(${c.id})" title="Crea Semana_1 a Semana_${b.semanas} en el curso">Crear grupos</button>
                        </div>
                    </div>`).join('') || '<div class="curso-conf"><span class="ayuda">Este bloque no tiene cursos.</span></div>'}
            </div>`).join('');
        actualizarSumas();
    };
    window.verTrimestreCfg = function (t) { guardarValoresLocales(); trimCfg = t; renderBloques(); };

    function guardarValoresLocales() {
        const plan = config.planes.find(p => p.id == $('cfgPlan').value);
        if (!plan) return;
        document.querySelectorAll('#cfgBloques [data-bloque]').forEach(inp => {
            const b = plan.bloques.find(x => x.id == inp.dataset.bloque);
            if (b) b.semanas = parseInt(inp.value) || 0;
        });
    }

    window.actualizarSumas = function () {
        guardarValoresLocales();
        const plan = config.planes.find(p => p.id == $('cfgPlan').value);
        const sumas = [1, 2, 3, 4, 5, 6].map(t => plan.bloques.filter(b => b.trimestre == t).reduce((a, b) => a + (parseInt(b.semanas) || 0), 0));
        $('cfgSumas').innerHTML = sumas.map((s, i) => `<span style="margin-right: 10px; ${s !== 13 ? 'color:#B91C1C; font-weight:700;' : ''}">T${i + 1}: ${s}/13</span>`).join('');
    };

    window.guardarBloques = async function () {
        guardarValoresLocales();
        const plan = config.planes.find(p => p.id == $('cfgPlan').value);
        const r = await api(`/online/planes/${plan.id}/bloques`, { method: 'PUT', body: { bloques: plan.bloques.map(b => ({ id: b.id, semanas: b.semanas })) } });
        if (!r.ok) return Swal.fire('No se guardó', msgError(r, 'Revisa las semanas.'), 'error');
        Swal.fire({ icon: 'success', title: 'Bloques guardados', timer: 1400, showConfirmButton: false });
    };

    window.guardarCurso = async function (id) {
        const r = await api(`/online/cursos/${id}`, { method: 'PUT', body: { moodle_course_id: $('curso' + id).value.trim() } });
        if (!r.ok) return Swal.fire('No se guardó', msgError(r, 'Revisa el ID.'), 'error');
        await cargarConfig();
        Swal.fire({ icon: 'success', title: 'Curso guardado', timer: 1200, showConfirmButton: false });
    };

    window.prepararGrupos = async function (id) {
        Swal.fire({ title: 'Creando grupos en Moodle…', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        const r = await api(`/online/cursos/${id}/preparar-grupos`, { method: 'POST' });
        Swal.fire(r.ok ? 'Listo' : 'No se completó', r.ok ? r.data.message : msgError(r, 'Error en Moodle.'), r.ok ? 'success' : 'error');
    };

    window.habilitarAhora = async function () {
        const btn = $('btnHabilitar');
        btn.disabled = true;
        $('cfgProcesoRes').innerHTML = 'Ejecutando…';
        const r = await api('/online/habilitar-semanas', { method: 'POST' });
        btn.disabled = false;
        if (!r.ok) { $('cfgProcesoRes').innerHTML = `<div class="aviso aviso-error">${esc(msgError(r, 'Error.'))}</div>`; return; }
        const d = r.data;
        $('cfgProcesoRes').innerHTML = `<div class="aviso aviso-info">Alumnos revisados: <b>${d.alumnos}</b>. Semanas habilitadas: <b>${d.semanas_habilitadas}</b>.
            ${d.con_error.length ? `<br>Con error en Moodle: ${d.con_error.map(x => `ID ${x.idAlumno} (${esc(x.errores.join(' '))})`).join('; ')}` : ''}</div>`;
        cargarAlumnos();
    };

    // ---------------------------------------------------------------- integrar alumno
    let integracionPermitida = false;

    window.abrirModalIntegrarAlumno = async function () {
        $('formIntegrar').reset();
        cambiarAlumno();
        modal('modalIntegrar').show();
    };

    // Consulta a la API si el grupo del alumno permite hoy el cambio y en qué trimestre iniciaría
    async function revisarIntegracion(id) {
        integracionPermitida = false;
        $('btnIntegrar').disabled = true;
        $('intVentana').innerHTML = '<div class="ayuda">Revisando las fechas de su grupo…</div>';
        const r = await api(`/alumnos/${encodeURIComponent(id)}/integracion-online`);
        if (!r.ok) { $('intVentana').innerHTML = `<div class="aviso aviso-error">${esc(msgError(r, 'No se pudo revisar.'))}</div>`; return; }
        const d = r.data, v = d.ventana;
        if (d.grupo) $('intDiaPago').value = d.grupo.dia_clase === 'DOMINGO' ? 'DOMINGO' : 'SABADO';
        $('intInfo').textContent = `${d.alumno.numeroControl || 'Sin matrícula'}, grupo ${d.grupo ? d.grupo.clave : 'sin asignar'}. Usuario de Moodle: ${d.alumno.moodle_username}`;
        integracionPermitida = !!v.permitido;
        $('btnIntegrar').disabled = !integracionPermitida;
        $('intVentana').innerHTML = v.permitido
            ? `<div class="aviso aviso-info"><b>Inicia en online el ${v.trimestre}° trimestre (semana ${v.semana_inicio} de 78) el ${esc(dmy(v.inicio))}.</b><br>${esc(v.mensaje)}</div>`
            : `<div class="aviso aviso-error"><b>Todavía no se puede pasar a online.</b><br>${esc(v.mensaje)}</div>`;
    }

    let tBusca = null;
    window.buscarAlumnos = function (val) {
        clearTimeout(tBusca);
        const lista = $('intResultados');
        if ((val || '').trim().length < 2) { lista.style.display = 'none'; return; }
        tBusca = setTimeout(async () => {
            const r = await api(`/online/buscar-presenciales?search=${encodeURIComponent(val.trim())}`);
            const al = (r.data && r.data.alumnos) || [];
            lista.style.display = 'block';
            lista.innerHTML = al.length ? al.map((a, i) => `<button type="button" class="list-group-item list-group-item-action" style="border-radius:0; font-size:0.8125rem;" data-i="${i}">
                <b>${esc([a.nombre, a.apPaterno, a.apMaterno].join(' '))}</b>
                <span class="celda-sub">${esc(a.numeroControl || 'Sin matrícula')}, ${esc(a.grupo || 'sin grupo')}, ${a.modalidad_estudio === 'ONLINE' ? 'ya está en online' : 'presencial'}</span></button>`).join('')
                : '<div class="list-group-item text-muted" style="font-size:0.8125rem;">Sin resultados</div>';
            lista.querySelectorAll('button[data-i]').forEach(b => b.onclick = () => seleccionarAlumno(al[b.dataset.i]));
        }, 300);
    };

    function seleccionarAlumno(a) {
        $('intIdAlumno').value = a.idAlumno;
        $('intNombre').textContent = [a.nombre, a.apPaterno, a.apMaterno].join(' ');
        $('intInfo').textContent = `${a.numeroControl || 'Sin matrícula'}, grupo ${a.grupo || '-'}`;
        $('intBuscarBox').style.display = 'none';
        $('intSeleccionado').style.display = 'block';
        $('intResultados').style.display = 'none';
        revisarIntegracion(a.idAlumno);
    }
    window.cambiarAlumno = function () {
        $('intIdAlumno').value = '';
        $('intBuscar').value = '';
        $('intBuscarBox').style.display = 'block';
        $('intSeleccionado').style.display = 'none';
        $('intResultados').style.display = 'none';
        $('intVentana').innerHTML = '';
        integracionPermitida = false;
        $('btnIntegrar').disabled = true;
    };

    window.guardarIntegracion = async function (ev) {
        ev.preventDefault();
        const id = $('intIdAlumno').value;
        if (!id) return Swal.fire('Falta el alumno', 'Busca y selecciona al alumno.', 'warning');
        if (!integracionPermitida) return;
        const btn = $('btnIntegrar'), txt = btn.innerHTML;
        btn.disabled = true; btn.innerHTML = '<i class="ti ti-loader"></i> Integrando y creando cuenta de Moodle…';
        const r = await api(`/alumnos/${id}/activar-online`, { method: 'POST', body: {
            dia_pago: $('intDiaPago').value, moodle_password: $('intPassword').value.trim(),
        } });
        btn.disabled = false; btn.innerHTML = txt;
        if (!r.ok) return Swal.fire('No se integró', msgError(r, 'Revisa los datos.'), 'error');
        modal('modalIntegrar').hide();
        const m = r.data.moodle || {};
        Swal.fire({
            icon: 'success', title: 'Alumno integrado a online exitosamente', confirmButtonColor: '#26687B',
            html: `<div class="text-start" style="font-size:0.875rem;">
                <div><b>Grupo:</b> ${esc(r.data.grupo)}</div>
                <div><b>Inicia:</b> ${r.data.trimestre}° trimestre el ${esc(dmy(r.data.inicio_trimestre))} (semana ${r.data.estado.semana_inicio})</div>
                <div><b>Usuario de Moodle:</b> <code>${esc(m.username || '-')}</code></div>
                ${m.password ? `<div><b>Contraseña:</b> <code>${esc(m.password)}</code></div>` : ''}
            </div>`,
        });
        cargarAlumnos();
    };

    // Llegada desde la pantalla de alumnos: ?integrar=ID abre la integración, ?pagar=ID abre el pago
    async function abrirDesdeUrl() {
        const qs = new URLSearchParams(location.search);
        const integrar = qs.get('integrar'), pagar = qs.get('pagar');
        if (integrar || pagar) history.replaceState(null, '', location.pathname);
        if (pagar) {
            if (alumnos.some(a => a.idAlumno == pagar)) abrirPago(pagar);
            else Swal.fire('No está en online', 'Primero integra al alumno a modalidad online.', 'info');
        } else if (integrar) {
            await abrirModalIntegrarAlumno();
            const r = await api(`/alumnos/${encodeURIComponent(integrar)}/integracion-online`);
            const d = r.data || {};
            if (d.alumno) seleccionarAlumno({ idAlumno: d.alumno.idAlumno, nombre: d.alumno.nombreCompleto, apPaterno: '', apMaterno: '',
                numeroControl: d.alumno.numeroControl || '', grupo: d.grupo ? d.grupo.clave : '' });
        }
    }

    document.addEventListener('DOMContentLoaded', async () => { await cargarAlumnos(); abrirDesdeUrl(); });
})();
</script>
@endsection
