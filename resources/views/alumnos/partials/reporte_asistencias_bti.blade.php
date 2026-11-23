{{--
    Reporte de asistencias BTI (unidad = día de clase; pase de lista diario de Administración).
    Reglas: retardo = asistencia (se reporta aparte); justificante = falta justificada (no es asistencia).
    Consume: GET /reportes/asistencias/bti/grupo/{id} y /reportes/asistencias/bti/grupo/{id}/alumno/{id}
--}}
<div id="seccion-reporte-bti" class="d-none">

    {{-- Filtro de periodo --}}
    <div class="bti-panel bti-filtros">
        <div class="bti-filtro-campo">
            <label for="bti-desde">Desde</label>
            <input type="date" id="bti-desde" class="bti-input">
        </div>
        <div class="bti-filtro-campo">
            <label for="bti-hasta">Hasta</label>
            <input type="date" id="bti-hasta" class="bti-input">
        </div>
        <div class="bti-filtro-acciones">
            <button type="button" class="bti-btn bti-btn-primario" onclick="btiAplicarFiltro()">
                <i class="ti ti-filter"></i>Aplicar
            </button>
            <button type="button" class="bti-btn" onclick="btiSemestreCompleto()">
                <i class="ti ti-calendar"></i>Todo el semestre
            </button>
        </div>
        <div class="bti-periodo" id="bti-periodo-label"></div>
    </div>

    {{-- ===================== VISTA GRUPO ===================== --}}
    <div id="bti-vista-grupo">
        <div class="bti-kpis" id="bti-kpis"></div>
        <div id="bti-aviso-cobertura"></div>

        <div class="bti-tabs" role="tablist">
            <button type="button" class="bti-tab active" id="bti-tab-alumnos" onclick="btiTab('ALUMNOS')">
                <i class="ti ti-users"></i>Alumnos
            </button>
            <button type="button" class="bti-tab" id="bti-tab-dias" onclick="btiTab('DIAS')">
                <i class="ti ti-calendar-stats"></i>Por día
            </button>
        </div>

        <div id="bti-panel-alumnos" class="bti-panel p-0">
            <div class="bti-tabla-toolbar">
                <div class="bti-buscador">
                    <i class="ti ti-search"></i>
                    <input type="text" id="bti-buscar-alumno" class="bti-input" placeholder="Buscar alumno o matrícula..." oninput="btiRenderAlumnos()">
                </div>
                <span class="bti-nota">Selecciona un alumno para ver su detalle. Haz clic en un encabezado para ordenar.</span>
            </div>
            <div class="table-responsive">
                <table class="bti-tabla" id="bti-tabla-alumnos">
                    <thead>
                        <tr>
                            <th data-orden="nombreCompleto" class="text-start">Alumno</th>
                            <th data-orden="dias_registrados">Días</th>
                            <th data-orden="asistencias">Asistencias</th>
                            <th data-orden="retardos">Retardos</th>
                            <th data-orden="faltas">Faltas</th>
                            <th data-orden="faltas_justificadas">Justificadas</th>
                            <th data-orden="faltas_sin_justificar">Sin justificar</th>
                            <th data-orden="porcentaje_asistencia" class="bti-col-pct">% Asistencia</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div id="bti-panel-dias" class="bti-panel p-0 d-none">
            <div class="table-responsive">
                <table class="bti-tabla" id="bti-tabla-dias">
                    <thead>
                        <tr>
                            <th class="text-start">Fecha</th>
                            <th>Asistencias</th>
                            <th>Retardos</th>
                            <th>Faltas</th>
                            <th>Justificadas</th>
                            <th>Sin marca</th>
                            <th class="bti-col-pct">% Asistencia</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===================== VISTA ALUMNO ===================== --}}
    <div id="bti-vista-alumno" class="d-none">
        <button type="button" class="bti-btn mb-3" onclick="btiVolverAlGrupo()">
            <i class="ti ti-arrow-left"></i>Volver al grupo
        </button>
        <div id="bti-alumno-contenido"></div>
    </div>
</div>

<style>
    #seccion-reporte-bti {
        --bti-brand: #26687B;
        --bti-brand-hover: #1F5565;
        --bti-brand-subtle: #EFF6F7;
        --bti-borde: #E2E8F0;
        --bti-texto: #0F172A;
        --bti-texto-2: #475569;
        --bti-texto-3: #64748B;
        --bti-ok: #15803D;      --bti-ok-bg: #DCFCE7;   --bti-ok-borde: #BBF7D0;
        --bti-ret: #B45309;     --bti-ret-bg: #FEF3C7;  --bti-ret-borde: #FDE68A;
        --bti-falta: #B91C1C;   --bti-falta-bg: #FEE2E2; --bti-falta-borde: #FECACA;
        --bti-just: #1D4ED8;    --bti-just-bg: #DBEAFE; --bti-just-borde: #BFDBFE;
        --bti-neutro: #64748B;  --bti-neutro-bg: #F1F5F9; --bti-neutro-borde: #E2E8F0;
    }
    #seccion-reporte-bti * { border-radius: 0; }

    .bti-panel {
        background: #FFFFFF;
        border: 1px solid var(--bti-borde);
        padding: 16px;
        margin-bottom: 16px;
    }
    .bti-filtros {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        gap: 12px;
    }
    .bti-filtro-campo { display: flex; flex-direction: column; gap: 4px; }
    .bti-filtro-campo label,
    .bti-kpi-label,
    .bti-seccion-titulo {
        font-size: 0.6875rem;
        font-weight: 600;
        color: var(--bti-texto-3);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .bti-filtro-acciones { display: flex; gap: 8px; flex-wrap: wrap; }
    .bti-periodo { margin-left: auto; font-size: 0.8125rem; color: var(--bti-texto-2); align-self: center; }

    .bti-input {
        height: 38px;
        padding: 0 10px;
        border: 1px solid var(--bti-borde);
        background: #FFFFFF;
        color: var(--bti-texto);
        font-size: 0.875rem;
    }
    .bti-input:focus { outline: none; border-color: var(--bti-brand); box-shadow: 0 0 0 3px rgba(38, 104, 123, 0.12); }

    .bti-btn {
        height: 38px;
        padding: 0 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid var(--bti-borde);
        background: #FFFFFF;
        color: var(--bti-texto-2);
        font-size: 0.8125rem;
        font-weight: 600;
        cursor: pointer;
    }
    .bti-btn:hover { border-color: var(--bti-brand); color: var(--bti-brand); }
    .bti-btn-primario { background: var(--bti-brand); border-color: var(--bti-brand); color: #FFFFFF; }
    .bti-btn-primario:hover { background: var(--bti-brand-hover); color: #FFFFFF; }

    /* KPIs */
    .bti-kpis {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 16px;
    }
    .bti-kpi {
        background: #FFFFFF;
        border: 1px solid var(--bti-borde);
        border-top: 3px solid var(--bti-brand);
        padding: 14px 16px;
    }
    .bti-kpi-valor {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--bti-texto);
        line-height: 1.2;
        margin-top: 4px;
        font-variant-numeric: tabular-nums;
    }
    .bti-kpi-sub { font-size: 0.75rem; color: var(--bti-texto-3); margin-top: 2px; }
    .bti-kpi.ret   { border-top-color: var(--bti-ret); }
    .bti-kpi.falta { border-top-color: var(--bti-falta); }
    .bti-kpi.just  { border-top-color: var(--bti-just); }
    .bti-kpi.neutro { border-top-color: var(--bti-neutro); }

    .bti-aviso {
        border: 1px solid var(--bti-ret-borde);
        background: var(--bti-ret-bg);
        color: var(--bti-ret);
        padding: 10px 14px;
        font-size: 0.8125rem;
        margin-bottom: 16px;
    }
    .bti-aviso summary { cursor: pointer; font-weight: 600; }
    .bti-aviso-fechas { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
    .bti-aviso-fechas span { background: #FFFFFF; border: 1px solid var(--bti-ret-borde); padding: 2px 8px; font-size: 0.75rem; }

    /* Tabs */
    .bti-tabs { display: inline-flex; background: #F1F5F9; padding: 4px; gap: 2px; margin-bottom: 12px; }
    .bti-tab {
        height: 34px; padding: 0 14px; border: none; background: transparent;
        color: var(--bti-texto-2); font-size: 0.8125rem; font-weight: 500;
        display: inline-flex; align-items: center; gap: 6px; cursor: pointer;
    }
    .bti-tab.active { background: var(--bti-brand); color: #FFFFFF; font-weight: 600; }

    /* Tablas */
    .bti-tabla-toolbar {
        display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between;
        gap: 12px; padding: 12px 16px; border-bottom: 1px solid var(--bti-borde);
    }
    .bti-buscador { position: relative; flex: 1; max-width: 340px; min-width: 220px; }
    .bti-buscador i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #94A3B8; }
    .bti-buscador .bti-input { width: 100%; padding-left: 32px; }
    .bti-nota { font-size: 0.75rem; color: var(--bti-texto-3); }

    .bti-tabla { width: 100%; border-collapse: collapse; font-size: 0.8125rem; color: var(--bti-texto); }
    .bti-tabla th {
        background: #F8FAFC; color: var(--bti-texto-2); font-weight: 600; font-size: 0.75rem;
        text-align: center; padding: 10px 12px; border-bottom: 1px solid var(--bti-borde);
        white-space: nowrap; user-select: none;
    }
    .bti-tabla th[data-orden] { cursor: pointer; }
    .bti-tabla th[data-orden]:hover { color: var(--bti-brand); }
    .bti-tabla th .ti { font-size: 12px; margin-left: 2px; }
    .bti-tabla td {
        padding: 10px 12px; border-bottom: 1px solid #F1F5F9; text-align: center;
        font-variant-numeric: tabular-nums; white-space: nowrap;
    }
    .bti-tabla td.text-start { white-space: normal; }
    .bti-tabla tbody tr.bti-fila-click { cursor: pointer; }
    .bti-tabla tbody tr.bti-fila-click:hover td { background: var(--bti-brand-subtle); }
    .bti-alumno-nombre { font-weight: 600; text-transform: uppercase; }
    .bti-alumno-matricula { font-size: 0.75rem; color: var(--bti-texto-3); }
    .bti-col-pct { min-width: 160px; }
    .bti-cero { color: #CBD5E1; }

    .bti-barra { display: flex; align-items: center; gap: 8px; }
    .bti-barra-pista { flex: 1; height: 8px; background: #F1F5F9; min-width: 60px; }
    .bti-barra-relleno { height: 100%; background: var(--bti-brand); }
    .bti-barra-texto { min-width: 46px; text-align: right; font-weight: 600; }

    /* Estatus */
    .bti-estatus {
        display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px;
        font-size: 0.6875rem; font-weight: 700; border: 1px solid; white-space: nowrap;
    }
    .bti-estatus-A { color: var(--bti-ok); background: var(--bti-ok-bg); border-color: var(--bti-ok-borde); }
    .bti-estatus-R { color: var(--bti-ret); background: var(--bti-ret-bg); border-color: var(--bti-ret-borde); }
    .bti-estatus-F { color: var(--bti-falta); background: var(--bti-falta-bg); border-color: var(--bti-falta-borde); }
    .bti-estatus-J { color: var(--bti-just); background: var(--bti-just-bg); border-color: var(--bti-just-borde); }
    .bti-estatus-SIN_REGISTRO,
    .bti-estatus-SIN_PASE { color: var(--bti-neutro); background: var(--bti-neutro-bg); border-color: var(--bti-neutro-borde); }

    /* Detalle alumno */
    .bti-alumno-header { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 12px; }
    .bti-alumno-header h4 { font-size: 1.125rem; font-weight: 700; margin: 0; text-transform: uppercase; color: var(--bti-texto); }
    .bti-boleta {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        border: 1px solid var(--bti-borde); margin-top: 14px;
    }
    .bti-boleta > div { padding: 12px 14px; border-right: 1px solid var(--bti-borde); }
    .bti-boleta > div:last-child { border-right: none; }
    .bti-boleta-valor { font-size: 1.25rem; font-weight: 700; color: var(--bti-texto); font-variant-numeric: tabular-nums; }
    .bti-boleta-sub { font-size: 0.75rem; color: var(--bti-texto-3); }
    .bti-seccion-titulo { margin: 0 0 10px; }

    .bti-calendario { display: grid; grid-template-columns: repeat(auto-fill, minmax(118px, 1fr)); gap: 6px; }
    .bti-dia { border: 1px solid var(--bti-borde); padding: 8px; font-size: 0.75rem; background: #FFFFFF; }
    .bti-dia-fecha { font-weight: 600; color: var(--bti-texto); }
    .bti-dia-nombre { color: var(--bti-texto-3); margin-bottom: 6px; }
    .bti-dia-obs { margin-top: 6px; color: var(--bti-texto-2); white-space: normal; }
    .bti-leyenda { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 10px; }

    .bti-vacio { text-align: center; padding: 32px 16px; color: var(--bti-texto-3); font-size: 0.875rem; }

    @media (max-width: 767.98px) {
        .bti-periodo { margin-left: 0; width: 100%; }
        .bti-filtro-campo { flex: 1 1 140px; }
        .bti-filtro-campo .bti-input { width: 100%; font-size: 16px; }
        .bti-boleta > div { border-right: none; border-bottom: 1px solid var(--bti-borde); }
    }
</style>

<script>
    const btiEstado = {
        idGrupo: null,
        datos: null,
        orden: { campo: 'nombreCompleto', asc: true },
        desde: '',
        hasta: '',
    };

    const BTI_ESTATUS = {
        A: { label: 'Asistencia', icon: 'ti-circle-check' },
        R: { label: 'Retardo', icon: 'ti-clock-exclamation' },
        F: { label: 'Falta', icon: 'ti-circle-x' },
        J: { label: 'Justificada', icon: 'ti-file-certificate' },
        SIN_REGISTRO: { label: 'Sin marca', icon: 'ti-minus' },
        SIN_PASE: { label: 'Sin pase de lista', icon: 'ti-calendar-off' },
    };

    function btiEsc(v) {
        return String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    }
    function btiFecha(iso) {
        if (!iso) return '';
        const [y, m, d] = String(iso).slice(0, 10).split('-');
        return `${d}/${m}/${y}`;
    }
    function btiFechaHora(iso) {
        if (!iso) return '';
        const hora = String(iso).length > 10 ? String(iso).slice(11, 16) : '';
        return `${btiFecha(iso)}${hora ? ' ' + hora : ''}`;
    }
    function btiNum(v) {
        return v ? `${v}` : `<span class="bti-cero">0</span>`;
    }
    function btiBarra(pct) {
        if (pct === null || pct === undefined) return '<span class="bti-cero">Sin datos</span>';
        return `<div class="bti-barra">
                    <div class="bti-barra-pista"><div class="bti-barra-relleno" style="width:${Math.min(100, pct)}%"></div></div>
                    <span class="bti-barra-texto">${pct}%</span>
                </div>`;
    }
    function btiBadge(estatus) {
        const e = BTI_ESTATUS[estatus] || BTI_ESTATUS.SIN_REGISTRO;
        return `<span class="bti-estatus bti-estatus-${btiEsc(estatus)}"><i class="ti ${e.icon}"></i>${e.label}</span>`;
    }

    async function btiFetch(url) {
        const res = await fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
        const data = await res.json().catch(() => null);
        if (!res.ok) {
            if (res.status === 401 || res.status === 419) {
                throw new Error('Tu sesión ha expirado. Por favor recarga la página (F5) o inicia sesión de nuevo.');
            }
            const msg = data && (data.error || data.message || (data.errors && Object.values(data.errors)[0]));
            throw new Error(msg || `Error del servidor (${res.status})`);
        }
        if (!data) throw new Error('Respuesta inválida del servidor');
        if (data.error) throw new Error(data.error);
        return data;
    }

    function btiQuery() {
        const p = new URLSearchParams();
        if (btiEstado.desde) p.set('desde', btiEstado.desde);
        if (btiEstado.hasta) p.set('hasta', btiEstado.hasta);
        const s = p.toString();
        return s ? `?${s}` : '';
    }

    // Punto de entrada: lo llama la vista principal al elegir un grupo BTI
    function cargarReporteBti(idGrupo) {
        if (btiEstado.idGrupo !== idGrupo) {
            btiEstado.desde = '';
            btiEstado.hasta = '';
            document.getElementById('bti-desde').value = '';
            document.getElementById('bti-hasta').value = '';
            document.getElementById('bti-buscar-alumno').value = '';
        }
        btiEstado.idGrupo = idGrupo;
        btiVolverAlGrupo();

        Swal.fire({ title: 'Cargando reporte...', text: 'Calculando asistencias del grupo', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        btiFetch(`/reportes/asistencias/bti/grupo/${encodeURIComponent(idGrupo)}${btiQuery()}`)
            .then(data => {
                Swal.close();
                btiEstado.datos = data;
                document.getElementById('bti-desde').value = data.rango.desde;
                document.getElementById('bti-hasta').value = data.rango.hasta;
                document.getElementById('bti-desde').min = data.grupo.fechaInicio;
                document.getElementById('bti-desde').max = data.grupo.fechaFin;
                document.getElementById('bti-hasta').min = data.grupo.fechaInicio;
                document.getElementById('bti-hasta').max = data.grupo.fechaFin;
                btiRenderGrupo();
            })
            .catch(err => {
                Swal.close();
                Swal.fire({ icon: 'error', title: 'Error de consulta', text: err.message || 'No se pudo cargar el reporte del grupo.' });
            });
    }

    function btiAplicarFiltro() {
        const desde = document.getElementById('bti-desde').value;
        const hasta = document.getElementById('bti-hasta').value;
        if (desde && hasta && desde > hasta) {
            Swal.fire({ icon: 'warning', title: 'Rango inválido', text: 'La fecha "Desde" no puede ser posterior a "Hasta".' });
            return;
        }
        btiEstado.desde = desde;
        btiEstado.hasta = hasta;
        cargarReporteBti(btiEstado.idGrupo);
    }

    function btiSemestreCompleto() {
        btiEstado.desde = '';
        btiEstado.hasta = '';
        cargarReporteBti(btiEstado.idGrupo);
    }

    function btiTab(tab) {
        document.getElementById('bti-tab-alumnos').classList.toggle('active', tab === 'ALUMNOS');
        document.getElementById('bti-tab-dias').classList.toggle('active', tab === 'DIAS');
        document.getElementById('bti-panel-alumnos').classList.toggle('d-none', tab !== 'ALUMNOS');
        document.getElementById('bti-panel-dias').classList.toggle('d-none', tab !== 'DIAS');
    }

    function btiRenderGrupo() {
        const d = btiEstado.datos;
        const r = d.resumen;
        const cob = d.cobertura;

        document.getElementById('bti-periodo-label').innerHTML =
            `Periodo: <strong>${btiFecha(d.rango.desde)}</strong> al <strong>${btiFecha(d.rango.hasta)}</strong>`;

        document.getElementById('bti-kpis').innerHTML = `
            <div class="bti-kpi">
                <div class="bti-kpi-label">Asistencia del grupo</div>
                <div class="bti-kpi-valor">${r.porcentaje_asistencia ?? 'Sin datos'}${r.porcentaje_asistencia !== null ? '%' : ''}</div>
                <div class="bti-kpi-sub">${r.asistencias} asistencias de ${r.dias_registrados} registros</div>
            </div>
            <div class="bti-kpi neutro">
                <div class="bti-kpi-label">Días con pase de lista</div>
                <div class="bti-kpi-valor">${cob.dias_con_pase} de ${cob.dias_programados}</div>
                <div class="bti-kpi-sub">${cob.porcentaje ?? 0}% de los días de clase</div>
            </div>
            <div class="bti-kpi ret">
                <div class="bti-kpi-label">Retardos</div>
                <div class="bti-kpi-valor">${r.retardos}</div>
                <div class="bti-kpi-sub">Incluidos en asistencias</div>
            </div>
            <div class="bti-kpi falta">
                <div class="bti-kpi-label">Faltas</div>
                <div class="bti-kpi-valor">${r.faltas}</div>
                <div class="bti-kpi-sub">${r.faltas_sin_justificar} sin justificar</div>
            </div>
            <div class="bti-kpi just">
                <div class="bti-kpi-label">Faltas justificadas</div>
                <div class="bti-kpi-valor">${r.faltas_justificadas}</div>
                <div class="bti-kpi-sub">${r.total_alumnos} alumnos en el grupo</div>
            </div>`;

        const aviso = document.getElementById('bti-aviso-cobertura');
        if (cob.dias_sin_pase > 0) {
            aviso.innerHTML = `
                <details class="bti-aviso">
                    <summary><i class="ti ti-alert-triangle"></i> Hay ${cob.dias_sin_pase} día(s) de clase sin pase de lista en este periodo. No cuentan para el porcentaje.</summary>
                    <div class="bti-aviso-fechas">${cob.fechas_sin_pase.map(f => `<span>${btiFecha(f)}</span>`).join('')}</div>
                </details>`;
        } else {
            aviso.innerHTML = '';
        }

        btiRenderAlumnos();
        btiRenderDias();
    }

    function btiRenderAlumnos() {
        const d = btiEstado.datos;
        if (!d) return;
        const tbody = document.querySelector('#bti-tabla-alumnos tbody');
        const q = normalizarBti(document.getElementById('bti-buscar-alumno').value);
        const { campo, asc } = btiEstado.orden;

        const lista = d.alumnos
            .filter(a => !q || normalizarBti(`${a.nombreCompleto} ${a.numeroControl}`).includes(q))
            .sort((a, b) => {
                let va = a[campo], vb = b[campo];
                if (va === null || va === undefined) return 1;   // sin datos siempre al final
                if (vb === null || vb === undefined) return -1;
                if (typeof va === 'string') return asc ? va.localeCompare(vb) : vb.localeCompare(va);
                return asc ? va - vb : vb - va;
            });

        document.querySelectorAll('#bti-tabla-alumnos th[data-orden]').forEach(th => {
            th.querySelector('.ti')?.remove();
            if (th.dataset.orden === campo) {
                th.insertAdjacentHTML('beforeend', `<i class="ti ${asc ? 'ti-arrow-up' : 'ti-arrow-down'}"></i>`);
            }
        });

        if (d.alumnos.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" class="bti-vacio">Este grupo no tiene alumnos activos.</td></tr>`;
            return;
        }
        if (lista.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" class="bti-vacio">Ningún alumno coincide con la búsqueda.</td></tr>`;
            return;
        }

        tbody.innerHTML = lista.map(a => `
            <tr class="bti-fila-click" tabindex="0" onclick="btiVerAlumno(${Number(a.idAlumno)})"
                onkeydown="if(event.key==='Enter'){btiVerAlumno(${Number(a.idAlumno)})}">
                <td class="text-start">
                    <div class="bti-alumno-nombre">${btiEsc(a.nombreCompleto)}</div>
                    <div class="bti-alumno-matricula">${btiEsc(a.numeroControl || 'Sin matrícula')}</div>
                </td>
                <td>${btiNum(a.dias_registrados)}</td>
                <td>${btiNum(a.asistencias)}</td>
                <td>${btiNum(a.retardos)}</td>
                <td>${btiNum(a.faltas)}</td>
                <td>${btiNum(a.faltas_justificadas)}</td>
                <td>${btiNum(a.faltas_sin_justificar)}</td>
                <td>${btiBarra(a.porcentaje_asistencia)}</td>
            </tr>`).join('');
    }

    function btiRenderDias() {
        const dias = btiEstado.datos.por_dia || [];
        const tbody = document.querySelector('#bti-tabla-dias tbody');
        if (dias.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="bti-vacio">No hay pases de lista registrados en este periodo.</td></tr>`;
            return;
        }
        tbody.innerHTML = dias.slice().reverse().map(d => `
            <tr>
                <td class="text-start"><strong>${btiFecha(d.fecha)}</strong> <span class="bti-alumno-matricula">${btiEsc(d.dia_nombre)}</span></td>
                <td>${btiNum(d.asistencias)}</td>
                <td>${btiNum(d.retardos)}</td>
                <td>${btiNum(d.faltas)}</td>
                <td>${btiNum(d.faltas_justificadas)}</td>
                <td>${btiNum(d.sin_registro)}</td>
                <td>${btiBarra(d.porcentaje_asistencia)}</td>
            </tr>`).join('');
    }

    function normalizarBti(s) {
        return String(s || '').normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase().trim();
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('#bti-tabla-alumnos th[data-orden]').forEach(th => {
            th.addEventListener('click', () => {
                const campo = th.dataset.orden;
                btiEstado.orden = {
                    campo,
                    asc: btiEstado.orden.campo === campo ? !btiEstado.orden.asc : campo === 'nombreCompleto',
                };
                btiRenderAlumnos();
            });
        });
    });

    function btiVolverAlGrupo() {
        document.getElementById('bti-vista-alumno').classList.add('d-none');
        document.getElementById('bti-vista-grupo').classList.remove('d-none');
    }

    function btiVerAlumno(idAlumno) {
        Swal.fire({ title: 'Cargando alumno...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        btiFetch(`/reportes/asistencias/bti/grupo/${encodeURIComponent(btiEstado.idGrupo)}/alumno/${encodeURIComponent(idAlumno)}${btiQuery()}`)
            .then(data => {
                Swal.close();
                btiRenderAlumno(data);
                document.getElementById('bti-vista-grupo').classList.add('d-none');
                document.getElementById('bti-vista-alumno').classList.remove('d-none');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            })
            .catch(err => {
                Swal.close();
                Swal.fire({ icon: 'error', title: 'Error de consulta', text: err.message || 'No se pudo cargar el alumno.' });
            });
    }

    function btiRenderAlumno(data) {
        const al = data.alumno;
        const r = data.resumen;

        const faltasHtml = data.faltas.length === 0
            ? `<div class="bti-vacio">Sin faltas en este periodo.</div>`
            : `<div class="table-responsive"><table class="bti-tabla">
                    <thead><tr>
                        <th class="text-start">Fecha de la falta</th>
                        <th>Estado</th>
                        <th>Justificó</th>
                        <th class="text-start">Motivo</th>
                        <th>Justificada el</th>
                    </tr></thead>
                    <tbody>${data.faltas.map(f => `
                        <tr>
                            <td class="text-start"><strong>${btiFecha(f.fecha)}</strong> <span class="bti-alumno-matricula">${btiEsc(f.dia_nombre)}</span></td>
                            <td>${f.justificada ? btiBadge('J') : btiBadge('F')}</td>
                            <td>${f.justificada ? (f.origen === 'ADMINISTRACION' ? 'Administración (justificante)' : 'Pase de lista') : '<span class="bti-cero">Pendiente</span>'}</td>
                            <td class="text-start">${btiEsc(f.motivo) || '<span class="bti-cero">Sin motivo</span>'}</td>
                            <td>${f.justificada ? btiEsc(btiFechaHora(f.fecha_justificacion)) : ''}</td>
                        </tr>`).join('')}
                    </tbody></table></div>`;

        const historialHtml = data.historial.length === 0
            ? `<div class="bti-vacio">No hay días de clase en este periodo.</div>`
            : `<div class="bti-leyenda">${['A', 'R', 'F', 'J', 'SIN_REGISTRO', 'SIN_PASE'].map(btiBadge).join('')}</div>
               <div class="bti-calendario">${data.historial.map(h => `
                    <div class="bti-dia">
                        <div class="bti-dia-fecha">${btiFecha(h.fecha)}</div>
                        <div class="bti-dia-nombre">${btiEsc(h.dia_nombre)}</div>
                        ${btiBadge(h.estatus)}
                        ${h.observaciones ? `<div class="bti-dia-obs">${btiEsc(h.observaciones)}</div>` : ''}
                    </div>`).join('')}
               </div>`;

        document.getElementById('bti-alumno-contenido').innerHTML = `
            <div class="bti-panel">
                <div class="bti-alumno-header">
                    <div>
                        <h4>${btiEsc(al.nombreCompleto)}</h4>
                        <div class="bti-alumno-matricula">Matrícula ${btiEsc(al.numeroControl || 'sin registrar')}, grupo ${btiEsc(data.grupo.clave)}</div>
                    </div>
                    <div class="bti-periodo">Periodo: <strong>${btiFecha(data.rango.desde)}</strong> al <strong>${btiFecha(data.rango.hasta)}</strong></div>
                </div>

                <div class="bti-boleta">
                    <div>
                        <div class="bti-kpi-label">Asistencias</div>
                        <div class="bti-boleta-valor">${r.asistencias} de ${r.dias_registrados}</div>
                        <div class="bti-boleta-sub">${r.porcentaje_asistencia !== null ? r.porcentaje_asistencia + '% de asistencia' : 'Sin registros'}</div>
                    </div>
                    <div>
                        <div class="bti-kpi-label">Retardos</div>
                        <div class="bti-boleta-valor">${r.retardos}</div>
                        <div class="bti-boleta-sub">Contados como asistencia</div>
                    </div>
                    <div>
                        <div class="bti-kpi-label">Faltas</div>
                        <div class="bti-boleta-valor">${r.faltas}</div>
                        <div class="bti-boleta-sub">${r.faltas_sin_justificar} sin justificar</div>
                    </div>
                    <div>
                        <div class="bti-kpi-label">Faltas justificadas</div>
                        <div class="bti-boleta-valor">${r.faltas_justificadas}</div>
                        <div class="bti-boleta-sub">No cuentan como asistencia</div>
                    </div>
                    ${r.sin_registro ? `
                    <div>
                        <div class="bti-kpi-label">Sin marca</div>
                        <div class="bti-boleta-valor">${r.sin_registro}</div>
                        <div class="bti-boleta-sub">Días con pase sin marcar al alumno</div>
                    </div>` : ''}
                </div>
            </div>

            <div class="bti-panel">
                <h6 class="bti-seccion-titulo">Faltas y justificantes</h6>
                ${faltasHtml}
            </div>

            <div class="bti-panel">
                <h6 class="bti-seccion-titulo">Historial por día</h6>
                ${historialHtml}
            </div>`;
    }
</script>
