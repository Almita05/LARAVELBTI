@extends('layouts.app')

@section('content')

{{--
    Pendientes del docente: una tarjeta por grupo con
      - el periodo en curso (trimestre BGNE / semestre BTI) y su barra de avance en el tiempo,
      - el estado de la captura (abierta con días restantes, próxima, cerrada),
      - el avance de captura por asignatura con acceso directo a capturar.
    Datos: /docentes/pendientes/datos → API /docentes/{id}/pendientes (clave "grupos").
--}}
<style>
.pd { --pd-brand: #26687B; --pd-brand-2: #1d5261; --pd-brand-soft: #e8f1f3; --pd-ink: #0f172a; --pd-text: #334155; --pd-muted: #64748b;
      --pd-line: #e2e8f0; --pd-bg: #f8fafc; --pd-ok: #15803d; --pd-ok-soft: #dcfce7; --pd-warn: #b45309; --pd-warn-soft: #fef3c7;
      --pd-bad: #b91c1c; --pd-bad-soft: #fee2e2; font-family: 'Public Sans', system-ui, sans-serif; color: var(--pd-text); }
.pd * { border-radius: 0 !important; }
.pd-head h1 { font-size: 1.375rem; font-weight: 700; color: var(--pd-ink); margin: 0; letter-spacing: -.01em; }
.pd-head p { margin: .25rem 0 0; font-size: .875rem; color: var(--pd-muted); }

/* Resumen */
.pd-kpis { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; margin: 20px 0; }
.pd-kpi { background: #fff; border: 1px solid var(--pd-line); padding: 14px 16px; }
.pd-kpi-label { font-size: .6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: .07em; color: var(--pd-muted); }
.pd-kpi-valor { font-size: 1.625rem; font-weight: 700; color: var(--pd-ink); line-height: 1.2; margin-top: 4px; font-variant-numeric: tabular-nums; }
.pd-kpi-nota { font-size: .75rem; color: var(--pd-muted); margin-top: 2px; }
.pd-kpi.urgente { border-color: #f59e0b; background: #fffbeb; }
.pd-kpi.urgente .pd-kpi-valor { color: var(--pd-warn); }

/* Prórrogas */
.pd-prorrogas { background: var(--pd-brand-soft); border: 1px solid #c9dde2; padding: 12px 16px; margin-bottom: 20px; }
.pd-prorrogas b { color: var(--pd-brand-2); font-size: .8125rem; }
.pd-prorrogas ul { margin: 6px 0 0; padding-left: 18px; font-size: .8125rem; }

/* Filtros */
.pd-filtros { display: flex; flex-wrap: wrap; gap: 0; border: 1px solid var(--pd-line); background: #fff; width: fit-content; max-width: 100%; margin-bottom: 16px; }
.pd-filtros button { border: 0; background: transparent; padding: 8px 14px; font-size: .8125rem; font-weight: 600; color: var(--pd-muted); cursor: pointer; }
.pd-filtros button + button { border-left: 1px solid var(--pd-line); }
.pd-filtros button[aria-pressed="true"] { background: var(--pd-brand); color: #fff; }
.pd-filtros .n { font-variant-numeric: tabular-nums; opacity: .8; margin-left: 4px; }

/* Tarjetas de grupo */
.pd-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; align-items: start; }
.pd-card { background: #fff; border: 1px solid var(--pd-line); }
.pd-card-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; padding: 16px 18px 12px; }
.pd-card-head h2 { margin: 0; font-size: 1.0625rem; font-weight: 700; color: var(--pd-ink); }
.pd-card-head .sub { font-size: .75rem; color: var(--pd-muted); margin-top: 2px; }
.pd-chip { display: inline-block; font-size: .625rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; padding: 2px 6px; border: 1px solid var(--pd-line); color: var(--pd-text); vertical-align: middle; margin-left: 6px; }
.pd-periodo-nombre { font-size: .8125rem; font-weight: 700; color: var(--pd-brand); white-space: nowrap; text-align: right; }
.pd-periodo-nombre small { display: block; font-weight: 500; color: var(--pd-muted); font-size: .6875rem; }

.pd-sec { padding: 12px 18px; border-top: 1px solid var(--pd-line); }
.pd-sec-titulo { font-size: .6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: .07em; color: var(--pd-muted); margin-bottom: 6px; display: flex; justify-content: space-between; gap: 8px; }
.pd-sec-titulo span:last-child { text-transform: none; letter-spacing: 0; font-weight: 600; color: var(--pd-text); font-variant-numeric: tabular-nums; }
.pd-barra { height: 8px; background: #eef2f6; position: relative; overflow: hidden; }
.pd-barra > i { position: absolute; inset: 0 auto 0 0; background: var(--pd-brand); }
.pd-barra.ok > i { background: var(--pd-ok); }
.pd-barra.fin > i { background: #94a3b8; }
.pd-nota { font-size: .75rem; color: var(--pd-muted); margin-top: 6px; }

/* Aviso de captura */
.pd-captura { display: flex; gap: 14px; align-items: center; padding: 12px 18px; border-top: 1px solid var(--pd-line); }
.pd-captura .cifra { min-width: 64px; text-align: center; font-weight: 700; line-height: 1; }
.pd-captura .cifra b { display: block; font-size: 1.75rem; font-variant-numeric: tabular-nums; }
.pd-captura .cifra span { font-size: .6875rem; text-transform: uppercase; letter-spacing: .05em; }
.pd-captura .txt { font-size: .8125rem; line-height: 1.4; }
.pd-captura .txt strong { display: block; font-size: .875rem; color: var(--pd-ink); }
.pd-captura .txt .extra { display: block; margin-top: 2px; font-size: .75rem; color: var(--pd-muted); }
.pd-captura .icono { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex: none; }
.pd-captura.abierta { background: var(--pd-ok-soft); }
.pd-captura.abierta .cifra { color: var(--pd-ok); }
.pd-captura.urgente { background: var(--pd-warn-soft); }
.pd-captura.urgente .cifra { color: var(--pd-warn); }
.pd-captura.proxima { background: var(--pd-bg); }
.pd-captura.proxima .icono { background: var(--pd-brand-soft); color: var(--pd-brand); }
.pd-captura.cerrada, .pd-captura.deshabilitada { background: var(--pd-bg); }
.pd-captura.cerrada .icono, .pd-captura.deshabilitada .icono { background: #eef2f6; color: var(--pd-muted); }
.pd-captura.sin_fechas { background: var(--pd-bg); }
.pd-captura.sin_fechas .icono { background: var(--pd-ok-soft); color: var(--pd-ok); }

/* Asignaturas */
.pd-materias { list-style: none; margin: 0; padding: 0; }
.pd-materia { display: grid; grid-template-columns: minmax(0, 1fr) 120px auto; gap: 12px; align-items: center; padding: 10px 0; }
.pd-materia + .pd-materia { border-top: 1px dashed var(--pd-line); }
.pd-materia .nom { font-size: .8125rem; font-weight: 600; color: var(--pd-ink); overflow-wrap: anywhere; }
.pd-materia .nom small { display: block; font-weight: 500; color: var(--pd-muted); font-size: .6875rem; }
.pd-materia .cuenta { font-size: .6875rem; color: var(--pd-muted); margin-top: 3px; font-variant-numeric: tabular-nums; text-align: right; }
.pd-materia .motivo { grid-column: 1 / -1; font-size: .75rem; color: var(--pd-muted); background: var(--pd-bg); border-left: 3px solid #cbd5e1; padding: 6px 10px; margin-top: -2px; }
.pd-btn { display: inline-flex; align-items: center; gap: 6px; font-size: .8125rem; font-weight: 600; padding: 7px 12px; border: 1px solid var(--pd-brand); background: var(--pd-brand); color: #fff; text-decoration: none; white-space: nowrap; }
.pd-btn:hover { background: var(--pd-brand-2); border-color: var(--pd-brand-2); color: #fff; }
.pd-btn.sec { background: #fff; color: var(--pd-brand); }
.pd-btn.sec:hover { background: var(--pd-brand-soft); color: var(--pd-brand-2); }
.pd-estado { display: inline-flex; align-items: center; gap: 6px; font-size: .75rem; font-weight: 600; color: var(--pd-muted); white-space: nowrap; }
.pd-estado.ok { color: var(--pd-ok); }

.pd-vacio { background: #fff; border: 1px solid var(--pd-line); padding: 48px 24px; text-align: center; color: var(--pd-muted); font-size: .875rem; }
.pd-vacio i { font-size: 1.75rem; color: var(--pd-brand); display: block; margin-bottom: 8px; }
.pd-cargando { height: 220px; background: linear-gradient(90deg, #f1f5f9 25%, #e9eef4 37%, #f1f5f9 63%); background-size: 400% 100%; animation: pd-brillo 1.4s ease infinite; border: 1px solid var(--pd-line); }
@keyframes pd-brillo { 0% { background-position: 100% 50%; } 100% { background-position: 0 50%; } }
@media (prefers-reduced-motion: reduce) { .pd-cargando { animation: none; } }

@media (max-width: 991px) { .pd-grid { grid-template-columns: minmax(0, 1fr); } }
@media (max-width: 575px) {
    .pd-kpis { grid-template-columns: minmax(0, 1fr); }
    .pd-materia { grid-template-columns: minmax(0, 1fr) auto; }
    .pd-materia .avance-col { grid-column: 1 / -1; grid-row: 2; }
}
</style>

<div class="page-container pd">

    <div class="pd-head">
        <h1>Mis grupos</h1>
        <p>El avance de cada grupo, cuánto tiempo te queda para capturar y qué calificaciones te faltan.</p>
    </div>

    <div class="pd-kpis" id="pdKpis" aria-live="polite">
        <div class="pd-kpi"><div class="pd-kpi-label">Grupos</div><div class="pd-kpi-valor">—</div></div>
        <div class="pd-kpi"><div class="pd-kpi-label">Captura abierta</div><div class="pd-kpi-valor">—</div></div>
        <div class="pd-kpi"><div class="pd-kpi-label">Asignaturas por completar</div><div class="pd-kpi-valor">—</div></div>
    </div>

    <div id="pdProrrogas"></div>

    <div class="pd-filtros" id="pdFiltros" role="group" aria-label="Filtrar grupos" style="display: none;"></div>

    <div class="pd-grid" id="pdGrupos">
        <div class="pd-cargando"></div>
        <div class="pd-cargando"></div>
    </div>
</div>

<script>
(function () {
    const DIAS = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
    const esc = v => String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const aFecha = iso => { if (!iso) return null; const [y, m, d] = String(iso).slice(0, 10).split('-').map(Number); return new Date(y, m - 1, d); };
    const dmy = iso => { const d = aFecha(iso); return d ? `${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth() + 1).padStart(2, '0')}/${d.getFullYear()}` : '—'; };
    const conDia = iso => { const d = aFecha(iso); return d ? `${DIAS[d.getDay()]} ${dmy(iso)}` : '—'; };
    const plural = (n, uno, varios) => `${n} ${n === 1 ? uno : varios}`;

    let grupos = [];
    let filtro = 'todos';

    const incompletas = g => g.materias.filter(m => m.total > 0 && m.calificados < m.total);
    const FILTROS = [
        { id: 'todos', txt: 'Todos', fn: () => true },
        { id: 'abierta', txt: 'Captura abierta', fn: g => g.captura.estado === 'abierta' || g.captura.estado === 'sin_fechas' },
        { id: 'pendientes', txt: 'Con pendientes', fn: g => incompletas(g).length > 0 },
        { id: 'aldia', txt: 'Al día', fn: g => incompletas(g).length === 0 },
    ];

    // Orden: primero la captura abierta que cierra antes, luego la que está por abrir, después el resto
    const PESO = { abierta: 0, sin_fechas: 1, proxima: 2, cerrada: 3, deshabilitada: 4 };
    const ordenar = arr => arr.slice().sort((a, b) =>
        (PESO[a.captura.estado] ?? 5) - (PESO[b.captura.estado] ?? 5) ||
        (a.captura.dias_restantes ?? a.captura.dias_para_abrir ?? 999) - (b.captura.dias_restantes ?? b.captura.dias_para_abrir ?? 999) ||
        String(a.clave).localeCompare(String(b.clave)));

    // ---------------------------------------------------------------
    function pintarKpis() {
        const abiertas = grupos.filter(g => g.captura.estado === 'abierta');
        const minDias = abiertas.length ? Math.min(...abiertas.map(g => g.captura.dias_restantes)) : null;
        const porCompletar = grupos.reduce((n, g) => n + incompletas(g).filter(m => !m.bloqueado).length, 0);
        const urgente = minDias !== null && minDias <= 2;
        document.getElementById('pdKpis').innerHTML = `
            <div class="pd-kpi">
                <div class="pd-kpi-label">Grupos</div>
                <div class="pd-kpi-valor">${grupos.length}</div>
                <div class="pd-kpi-nota">${plural(grupos.reduce((n, g) => n + g.materias.length, 0), 'asignatura', 'asignaturas')} en total</div>
            </div>
            <div class="pd-kpi ${urgente ? 'urgente' : ''}">
                <div class="pd-kpi-label">Captura abierta</div>
                <div class="pd-kpi-valor">${abiertas.length}</div>
                <div class="pd-kpi-nota">${minDias === null ? 'Ningún grupo en periodo de captura' : (minDias === 0 ? 'Una cierra hoy' : `La primera cierra en ${plural(minDias, 'día', 'días')}`)}</div>
            </div>
            <div class="pd-kpi">
                <div class="pd-kpi-label">Asignaturas por completar</div>
                <div class="pd-kpi-valor">${porCompletar}</div>
                <div class="pd-kpi-nota">${porCompletar ? 'Que hoy puedes capturar' : 'Nada pendiente que puedas capturar hoy'}</div>
            </div>`;
    }

    function pintarProrrogas(prorrogas) {
        const cont = document.getElementById('pdProrrogas');
        if (!prorrogas || !prorrogas.length) { cont.innerHTML = ''; return; }
        cont.innerHTML = `
            <div class="pd-prorrogas">
                <b><i class="fa-solid fa-calendar-plus me-1"></i> Tienes ${plural(prorrogas.length, 'prórroga', 'prórrogas')} de captura</b>
                <ul>${prorrogas.map(p => `<li><strong>${esc(p.nombre_materia)}</strong>, grupo ${esc(p.clave_grupo)}: hasta el ${esc(conDia(p.fecha_limite))} (${plural(p.dias_restantes, 'día', 'días')})</li>`).join('')}</ul>
            </div>`;
    }

    function pintarFiltros() {
        const cont = document.getElementById('pdFiltros');
        if (grupos.length < 2) { cont.style.display = 'none'; return; }
        cont.style.display = 'flex';
        cont.innerHTML = FILTROS.map(f => `<button type="button" data-f="${f.id}" aria-pressed="${filtro === f.id}">${f.txt}<span class="n">${grupos.filter(f.fn).length}</span></button>`).join('');
        cont.querySelectorAll('button').forEach(b => b.addEventListener('click', () => { filtro = b.dataset.f; pintarFiltros(); pintarGrupos(); }));
    }

    // ---------------------------------------------------------------
    function bloquePeriodo(p) {
        if (!p) return '';
        let nota;
        if (p.estado === 'por_iniciar') nota = `Inicia el ${conDia(p.inicio)}.`;
        else if (p.estado === 'terminado') nota = `Terminó el ${conDia(p.fin)}.`;
        else nota = `Semana ${p.semana} de ${p.semanas}. ${p.dias_restantes === 0 ? 'Termina hoy.' : `Faltan ${plural(p.dias_restantes, 'día', 'días')} para que termine.`}`;
        return `
            <div class="pd-sec">
                <div class="pd-sec-titulo"><span>Avance del ${esc(p.nombre.split(' ').pop())}</span><span>${p.porcentaje}%</span></div>
                <div class="pd-barra ${p.estado === 'terminado' ? 'fin' : ''}" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="${p.porcentaje}" aria-label="Avance del periodo"><i style="width:${p.porcentaje}%"></i></div>
                <div class="pd-nota">${dmy(p.inicio)} al ${dmy(p.fin)}. ${nota}</div>
            </div>`;
    }

    function bloqueCaptura(c) {
        const anterior = c.anterior ? `<span class="extra">La captura del ${esc(c.anterior.etapa.toLowerCase())} cerró el ${dmy(c.anterior.fin)}.</span>` : '';
        if (c.estado === 'abierta') {
            const urg = c.dias_restantes <= 2;
            const cifra = c.dias_restantes === 0 ? `<b>Hoy</b><span>último día</span>` : `<b>${c.dias_restantes}</b><span>${c.dias_restantes === 1 ? 'día' : 'días'}</span>`;
            return `
                <div class="pd-captura ${urg ? 'urgente' : 'abierta'}">
                    <div class="cifra">${cifra}</div>
                    <div class="txt">
                        <strong>Captura abierta: ${esc(c.etapa.toLowerCase())}</strong>
                        Tienes hasta el ${esc(conDia(c.fin))} para capturar tus calificaciones.
                        ${anterior}
                    </div>
                </div>`;
        }
        const ICON = { proxima: 'fa-hourglass-start', cerrada: 'fa-lock', deshabilitada: 'fa-ban', sin_fechas: 'fa-lock-open' };
        let titulo, texto;
        if (c.estado === 'proxima') {
            titulo = `La captura abre ${c.dias_para_abrir === 1 ? 'mañana' : `en ${plural(c.dias_para_abrir, 'día', 'días')}`}`;
            texto = `${esc(c.etapa)}: del ${esc(conDia(c.inicio))} al ${esc(conDia(c.fin))}${c.automatica ? ', la semana siguiente al fin del trimestre' : ''}.`;
        } else if (c.estado === 'cerrada') {
            titulo = 'Captura cerrada';
            texto = `La captura del ${esc(c.etapa.toLowerCase())} cerró el ${esc(conDia(c.fin))}. Si te falta capturar, solicita una prórroga a la administración.`;
        } else if (c.estado === 'deshabilitada') {
            titulo = 'Captura desactivada';
            texto = 'La administración desactivó la captura de calificaciones en este grupo.';
        } else {
            titulo = 'Captura sin fecha límite';
            texto = 'Este grupo no tiene fechas de captura definidas.';
        }
        return `
            <div class="pd-captura ${c.estado}">
                <div class="icono"><i class="fa-solid ${ICON[c.estado] || 'fa-circle-info'}"></i></div>
                <div class="txt"><strong>${titulo}</strong>${texto}${anterior}</div>
            </div>`;
    }

    function filaMateria(g, m) {
        const completa = m.total > 0 && m.calificados >= m.total;
        const url = `/grupos/captura_calificaciones?id_grupo=${encodeURIComponent(g.id)}&id_materia=${encodeURIComponent(m.id)}`;
        let accion;
        if (m.bloqueado) accion = `<span class="pd-estado"><i class="fa-solid fa-lock"></i> Bloqueada</span>`;
        else if (completa) accion = `<a class="pd-btn sec" href="${url}" title="Revisar calificaciones">Revisar</a>`;
        else accion = `<a class="pd-btn" href="${url}"><i class="fa-solid fa-pen-to-square"></i> Capturar</a>`;
        return `
            <li class="pd-materia">
                <div class="nom">${esc(m.nombre)}<small>${esc(m.nivel || '')}</small></div>
                <div class="avance-col">
                    <div class="pd-barra ${completa ? 'ok' : ''}" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="${m.porcentaje}" aria-label="Avance de ${esc(m.nombre)}"><i style="width:${m.porcentaje}%"></i></div>
                    <div class="cuenta">${m.total ? `${m.calificados} de ${m.total} alumnos` : 'Sin alumnos'}</div>
                </div>
                <div>${completa && !m.bloqueado ? `<span class="pd-estado ok me-2"><i class="fa-solid fa-circle-check"></i></span>` : ''}${accion}</div>
                ${m.bloqueado && m.motivo ? `<div class="motivo">${esc(m.motivo)}</div>` : ''}
            </li>`;
    }

    function tarjeta(g) {
        const p = g.periodo;
        const a = g.avance;
        return `
            <article class="pd-card" aria-label="Grupo ${esc(g.clave)}">
                <div class="pd-card-head">
                    <div>
                        <h2>${esc(g.clave)}${g.plantel ? `<span class="pd-chip">${esc(g.plantel)}</span>` : ''}</h2>
                        <div class="sub">${esc(g.modalidad || '')}${g.fecha_inicio ? `${g.modalidad ? ', ' : ''}del ${dmy(g.fecha_inicio)} al ${dmy(g.fecha_fin)}` : ''}</div>
                    </div>
                    ${p ? `<div class="pd-periodo-nombre">${esc(p.nombre.charAt(0).toUpperCase() + p.nombre.slice(1))}<small>${p.estado === 'en_curso' ? 'En curso' : (p.estado === 'terminado' ? 'Terminado' : 'Por iniciar')}</small></div>` : ''}
                </div>
                ${bloqueCaptura(g.captura)}
                ${bloquePeriodo(p)}
                <div class="pd-sec">
                    <div class="pd-sec-titulo"><span>Captura: ${esc(g.etapa_avance.toLowerCase())}</span><span>${a.calificados} de ${a.total}</span></div>
                    <div class="pd-barra ${a.total && a.calificados >= a.total ? 'ok' : ''}" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="${a.porcentaje}" aria-label="Avance de captura del grupo"><i style="width:${a.porcentaje}%"></i></div>
                </div>
                <div class="pd-sec">
                    <div class="pd-sec-titulo"><span>Mis asignaturas</span><span>${g.materias.length}</span></div>
                    ${g.materias.length ? `<ul class="pd-materias">${g.materias.map(m => filaMateria(g, m)).join('')}</ul>` : '<div class="pd-nota">Sin asignaturas en tu horario.</div>'}
                </div>
            </article>`;
    }

    function pintarGrupos() {
        const cont = document.getElementById('pdGrupos');
        if (!grupos.length) {
            cont.innerHTML = `<div class="pd-vacio" style="grid-column: 1 / -1;"><i class="fa-solid fa-chalkboard-user"></i>No tienes grupos asignados en tu horario.</div>`;
            return;
        }
        const f = FILTROS.find(x => x.id === filtro) || FILTROS[0];
        const lista = ordenar(grupos.filter(f.fn));
        cont.innerHTML = lista.length
            ? lista.map(tarjeta).join('')
            : `<div class="pd-vacio" style="grid-column: 1 / -1;"><i class="fa-solid fa-circle-check"></i>No hay grupos en este filtro.</div>`;
    }

    // ---------------------------------------------------------------
    fetch('/docentes/pendientes/datos', { headers: { 'Accept': 'application/json' }, cache: 'no-store' })
        .then(async r => {
            const data = await r.json().catch(() => ({}));
            // Sin la clave "grupos" es un error (API caída, desactualizada o sin docente en sesión), no "sin grupos"
            if (!r.ok || !Array.isArray(data.grupos)) throw new Error(data.error || `HTTP ${r.status}`);
            return data;
        })
        .then(data => {
            grupos = data.grupos;
            pintarKpis();
            pintarProrrogas(data.prorrogas || []);
            pintarFiltros();
            pintarGrupos();
        })
        .catch(err => {
            console.error('Error al cargar pendientes:', err);
            document.getElementById('pdGrupos').innerHTML = `<div class="pd-vacio" style="grid-column: 1 / -1;"><i class="fa-solid fa-triangle-exclamation"></i>No se pudo cargar la información. Recarga la página para intentarlo de nuevo.<br><small style="opacity:.7">${String(err && err.message || '').replace(/[<>&"]/g, '')}</small></div>`;
        });
})();
</script>

@endsection
