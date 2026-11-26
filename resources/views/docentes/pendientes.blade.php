@extends('layouts.app')

@section('content')

{{--
    Pendientes del docente: una tarjeta compacta por grupo con
      - el periodo en curso (trimestre BGNE / semestre BTI) en una línea,
      - una etiqueta con el estado de la captura (cierra hoy / en N días, abre en N días, cerrada),
      - sus asignaturas con alumnos calificados y acceso directo a capturar.
    Datos: /docentes/pendientes/datos → API /docentes/{id}/pendientes (clave "grupos").
--}}
<style>
.pd { --pd-brand: #26687B; --pd-brand-2: #1d5261; --pd-brand-soft: #e8f1f3; --pd-ink: #0f172a; --pd-text: #334155; --pd-muted: #64748b;
      --pd-line: #e2e8f0; --pd-bg: #f8fafc; --pd-ok: #15803d; --pd-ok-soft: #dcfce7; --pd-warn: #b45309; --pd-warn-soft: #fef3c7;
      font-family: 'Public Sans', system-ui, sans-serif; color: var(--pd-text); }
.pd * { border-radius: 0 !important; }
.pd-head h1 { font-size: 1.375rem; font-weight: 700; color: var(--pd-ink); margin: 0; letter-spacing: -.01em; }
.pd-head p { margin: .25rem 0 0; font-size: .875rem; color: var(--pd-muted); font-variant-numeric: tabular-nums; }

/* Aviso superior (solo cuando hay algo urgente o prórrogas) */
.pd-aviso { display: flex; gap: 10px; align-items: flex-start; padding: 10px 14px; margin-top: 16px; font-size: .8125rem; border: 1px solid; }
.pd-aviso i { margin-top: 2px; }
.pd-aviso.urgente { background: var(--pd-warn-soft); border-color: #fcd34d; color: #78350f; }
.pd-aviso.info { background: var(--pd-brand-soft); border-color: #c9dde2; color: var(--pd-brand-2); }
.pd-aviso ul { margin: 4px 0 0; padding-left: 18px; }

/* Tarjetas */
.pd-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; align-items: start; margin-top: 18px; }
.pd-card { background: #fff; border: 1px solid var(--pd-line); border-left: 3px solid var(--pd-line); }
.pd-card.abierta { border-left-color: var(--pd-ok); }
.pd-card.urgente { border-left-color: #f59e0b; }
.pd-card-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; padding: 14px 16px 10px; }
.pd-card-head h2 { margin: 0; font-size: 1rem; font-weight: 700; color: var(--pd-ink); }
.pd-card-head .sub { font-size: .75rem; color: var(--pd-muted); margin-top: 2px; }
.pd-chip { font-size: .625rem; font-weight: 700; letter-spacing: .06em; padding: 1px 5px; border: 1px solid var(--pd-line); color: var(--pd-muted); vertical-align: middle; margin-left: 6px; }

.pd-fechas { display: flex; gap: 8px; align-items: baseline; margin: 0 16px 10px; padding: 8px 10px; background: var(--pd-bg); font-size: .75rem; color: var(--pd-text); font-variant-numeric: tabular-nums; }
.pd-fechas i { color: var(--pd-muted); }
.pd-fechas b { color: var(--pd-ink); font-weight: 600; }
.pd-materia .nom small { display: block; font-weight: 500; font-size: .6875rem; color: var(--pd-ok); margin-top: 1px; }
.pd-pill { flex: none; font-size: .75rem; font-weight: 600; padding: 4px 8px; white-space: nowrap; background: var(--pd-bg); color: var(--pd-muted); text-align: right; line-height: 1.25; }
.pd-pill small { display: block; font-weight: 500; font-size: .6875rem; opacity: .85; }
.pd-pill.abierta { background: var(--pd-ok-soft); color: var(--pd-ok); }
.pd-pill.urgente { background: var(--pd-warn-soft); color: var(--pd-warn); }

/* Asignaturas */
.pd-materias { list-style: none; margin: 0; padding: 0 16px 6px; }
.pd-materia { display: grid; grid-template-columns: minmax(0, 1fr) auto auto; gap: 12px; align-items: center; padding: 9px 0; border-top: 1px solid var(--pd-line); }
.pd-materia .nom { font-size: .8125rem; font-weight: 600; color: var(--pd-ink); overflow-wrap: anywhere; }
.pd-materia .cuenta { font-size: .75rem; color: var(--pd-muted); font-variant-numeric: tabular-nums; white-space: nowrap; }
.pd-materia .cuenta.ok { color: var(--pd-ok); font-weight: 600; }
.pd-materia .motivo { grid-column: 1 / -1; font-size: .75rem; color: var(--pd-muted); background: var(--pd-bg); padding: 6px 10px; }
.pd-btn { display: inline-flex; align-items: center; gap: 6px; font-size: .75rem; font-weight: 600; padding: 5px 10px; border: 1px solid var(--pd-brand); background: var(--pd-brand); color: #fff; text-decoration: none; white-space: nowrap; }
.pd-btn:hover { background: var(--pd-brand-2); border-color: var(--pd-brand-2); color: #fff; }
.pd-btn.sec { background: #fff; color: var(--pd-brand); }
.pd-btn.sec:hover { background: var(--pd-brand-soft); color: var(--pd-brand-2); }
.pd-bloq { border: 0; background: none; padding: 5px 0; font-size: .75rem; font-weight: 600; color: var(--pd-muted); cursor: pointer; white-space: nowrap; }
.pd-bloq:hover { color: var(--pd-text); }
.pd-sin { padding: 0 16px 14px; font-size: .75rem; color: var(--pd-muted); }

.pd-vacio { grid-column: 1 / -1; background: #fff; border: 1px solid var(--pd-line); padding: 48px 24px; text-align: center; color: var(--pd-muted); font-size: .875rem; }
.pd-vacio i { font-size: 1.75rem; color: var(--pd-brand); display: block; margin-bottom: 8px; }
.pd-cargando { height: 180px; background: linear-gradient(90deg, #f1f5f9 25%, #e9eef4 37%, #f1f5f9 63%); background-size: 400% 100%; animation: pd-brillo 1.4s ease infinite; border: 1px solid var(--pd-line); }
@keyframes pd-brillo { 0% { background-position: 100% 50%; } 100% { background-position: 0 50%; } }
@media (prefers-reduced-motion: reduce) { .pd-cargando { animation: none; } }

@media (max-width: 991px) { .pd-grid { grid-template-columns: minmax(0, 1fr); } }
</style>

<div class="page-container pd">
    <div class="pd-head">
        <h1>Mis grupos</h1>
        <p id="pdResumen">Cargando…</p>
    </div>

    <div id="pdAvisos" aria-live="polite"></div>

    <div class="pd-grid" id="pdGrupos">
        <div class="pd-cargando"></div>
        <div class="pd-cargando"></div>
    </div>
</div>

<script>
(function () {
    const DIAS = ['dom', 'lun', 'mar', 'mié', 'jue', 'vie', 'sáb'];
    const esc = v => String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const aFecha = iso => { if (!iso) return null; const [y, m, d] = String(iso).slice(0, 10).split('-').map(Number); return new Date(y, m - 1, d); };
    const corta = iso => { const d = aFecha(iso); return d ? `${DIAS[d.getDay()]} ${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth() + 1).padStart(2, '0')}` : '—'; };
    const larga = iso => { const d = aFecha(iso); return d ? `${corta(iso)}/${d.getFullYear()}` : '—'; };
    const plural = (n, uno, varios) => `${n} ${n === 1 ? uno : varios}`;
    let prorrogas = {};   // "idGrupo-idMateria" → fecha límite
    const enDias = (n, hoy, manana) => n === 0 ? hoy : (n === 1 ? manana : `en ${plural(n, 'día', 'días')}`);

    let grupos = [];
    const porCapturar = g => g.materias.filter(m => !m.bloqueado && m.total > 0 && m.calificados < m.total);
    // Lo que manda es si el docente puede capturar alguna asignatura, no solo las fechas del grupo
    const puede = g => g.materias.some(m => !m.bloqueado);
    const abierta = g => g.captura.estado === 'abierta' && puede(g);
    const urgente = g => abierta(g) && g.captura.dias_restantes <= 2;

    // Orden: primero la captura abierta que cierra antes, luego la que está por abrir, después el resto
    const PESO = { abierta: 0, sin_fechas: 1, proxima: 2, cerrada: 3, deshabilitada: 4 };
    const peso = g => abierta(g) ? 0 : (puede(g) ? 1 : (g.captura.estado === 'proxima' ? 2 : 3 + (PESO[g.captura.estado] ?? 2)));
    const ordenar = arr => arr.slice().sort((a, b) =>
        peso(a) - peso(b) ||
        (a.captura.dias_restantes ?? a.captura.dias_para_abrir ?? 999) - (b.captura.dias_restantes ?? b.captura.dias_para_abrir ?? 999) ||
        String(a.clave).localeCompare(String(b.clave)));

    // ---------------------------------------------------------------
    function pintarEncabezado(prorrogas) {
        const pendientes = grupos.reduce((n, g) => n + porCapturar(g).length, 0);
        document.getElementById('pdResumen').textContent = grupos.length
            ? `${plural(grupos.length, 'grupo', 'grupos')} · ${pendientes ? `${plural(pendientes, 'asignatura', 'asignaturas')} por capturar` : 'nada pendiente por capturar hoy'}`
            : '';

        let html = '';
        const urg = grupos.filter(urgente);
        if (urg.length) {
            const hoy = urg.filter(g => g.captura.dias_restantes === 0).length;
            html += `<div class="pd-aviso urgente"><i class="fa-solid fa-clock"></i><div><strong>${hoy
                ? `La captura de ${plural(hoy, 'grupo cierra', 'grupos cierra')} hoy.`
                : `La captura de ${plural(urg.length, 'grupo cierra', 'grupos cierra')} en menos de 3 días.`}</strong> ${urg.map(g => esc(g.clave)).join(', ')}</div></div>`;
        }
        if (prorrogas && prorrogas.length) {
            html += `<div class="pd-aviso info"><i class="fa-solid fa-calendar-plus"></i><div><strong>Tienes ${plural(prorrogas.length, 'prórroga', 'prórrogas')} de captura</strong>
                <ul>${prorrogas.map(p => `<li>${esc(p.nombre_materia)}, ${esc(p.clave_grupo)}: hasta el ${esc(corta(p.fecha_limite))}</li>`).join('')}</ul></div></div>`;
        }
        document.getElementById('pdAvisos').innerHTML = html;
    }

    // Etiqueta con el estado de la captura del grupo
    function pill(g) {
        const c = g.captura;
        if (abierta(g)) {
            return `<span class="pd-pill ${urgente(g) ? 'urgente' : 'abierta'}">${c.dias_restantes === 0 ? 'Último día' : (c.dias_restantes === 1 ? 'Cierra mañana' : `Quedan ${c.dias_restantes} días`)}</span>`;
        }
        // Fuera de las fechas del grupo pero con alguna asignatura habilitada (p. ej. por prórroga)
        if (puede(g) && g.materias.length) return `<span class="pd-pill abierta">Puedes capturar</span>`;
        // Ventana abierta pero todas sus asignaturas bloqueadas (ya enviadas, desactivadas…): no hay nada que hacer
        if (c.estado === 'abierta' || c.estado === 'sin_fechas') return `<span class="pd-pill" title="Toca «Bloqueada» en la asignatura para ver el motivo.">Captura bloqueada</span>`;
        if (c.estado === 'proxima') {
            return `<span class="pd-pill">Captura abre ${enDias(c.dias_para_abrir, 'hoy', 'mañana')}</span>`;
        }
        if (c.estado === 'cerrada') return `<span class="pd-pill" title="Si te falta capturar, solicita una prórroga.">Captura cerrada</span>`;
        if (c.estado === 'deshabilitada') return `<span class="pd-pill">Captura desactivada</span>`;
        return `<span class="pd-pill abierta">Captura abierta</span>`;
    }

    function subtitulo(g) {
        const p = g.periodo;
        if (!p) return esc(g.modalidad || '');
        const nombre = p.nombre.charAt(0).toUpperCase() + p.nombre.slice(1);
        if (p.estado === 'por_iniciar') return `${esc(nombre)} · inicia el ${esc(corta(p.inicio))}`;
        if (p.estado === 'terminado') return `${esc(nombre)} · terminado`;
        return `${esc(nombre)} · semana ${p.semana} de ${p.semanas}`;
    }

    // Ventana de captura del grupo: cuándo se habilitó y cuándo cierra
    function fechasCaptura(c, g) {
        const etapa = c.etapa ? ` (${esc(c.etapa.toLowerCase())})` : '';
        const del = () => `del <b>${esc(larga(c.inicio))}</b> al <b>${esc(larga(c.fin))}</b>`;
        let txt;
        const le = c.limite_estimado;
        if (le) {
            const periodo = g.periodo && g.periodo.nombre ? `El ${esc(g.periodo.nombre)}` : 'El grupo';
            txt = `${periodo} termina el <b>${esc(larga(le.fin_periodo))}</b>; la fecha límite para capturar es el <b>${esc(larga(le.limite))}</b> (6 días después).`;
        } else if (c.estado === 'sin_fechas') {
            txt = 'La administración aún no habilita las fechas de captura de este grupo.';
        } else if (c.estado === 'deshabilitada') {
            txt = c.inicio && c.fin
                ? `Captura${etapa}: se habilita el <b>${esc(larga(c.inicio))}</b> y cierra el <b>${esc(larga(c.fin))}</b>`
                : 'La administración tiene desactivada la captura de este grupo y aún no hay fechas programadas.';
        } else if (!c.inicio || !c.fin) {
            return '';
        } else if (c.estado === 'abierta') {
            txt = `Captura${etapa}: se habilitó el <b>${esc(larga(c.inicio))}</b> y cierra el <b>${esc(larga(c.fin))}</b>`;
        } else if (c.estado === 'proxima') {
            txt = `Captura${etapa}: se habilita el <b>${esc(larga(c.inicio))}</b> y cierra el <b>${esc(larga(c.fin))}</b>`;
        } else {
            txt = `Captura${etapa}: estuvo habilitada ${del()}. Aún no hay fechas para la siguiente captura.`;
        }
        return `<div class="pd-fechas"><i class="fa-regular fa-calendar"></i><span>${txt}</span></div>`;
    }

    function filaMateria(g, m, i) {
        const completa = m.total > 0 && m.calificados >= m.total;
        const url = `/grupos/captura_calificaciones?id_grupo=${encodeURIComponent(g.id)}&id_materia=${encodeURIComponent(m.id)}`;
        const idMotivo = `pd-mot-${g.id}-${i}`;
        let accion;
        if (m.bloqueado) accion = `<button type="button" class="pd-bloq" aria-expanded="false" aria-controls="${idMotivo}"><i class="fa-solid fa-lock"></i> Bloqueada</button>`;
        else if (completa) accion = `<a class="pd-btn sec" href="${url}">Revisar</a>`;
        else accion = `<a class="pd-btn" href="${url}">Capturar</a>`;
        const cuenta = !m.total ? 'Sin alumnos'
            : (completa ? `<i class="fa-solid fa-check"></i> ${m.calificados}/${m.total}` : `${m.calificados}/${m.total}`);
        return `
            <li class="pd-materia">
                <div class="nom">${esc(m.nombre)}${prorrogas[`${g.id}-${m.id}`] && !m.bloqueado ? `<small>Prórroga hasta el ${esc(larga(prorrogas[`${g.id}-${m.id}`]))}</small>` : ''}</div>
                <div class="cuenta ${completa ? 'ok' : ''}" title="Alumnos calificados">${cuenta}</div>
                <div>${accion}</div>
                ${m.bloqueado && m.motivo ? `<div class="motivo" id="${idMotivo}" hidden>${esc(m.motivo)}</div>` : ''}
            </li>`;
    }

    function tarjeta(g) {
        const clase = urgente(g) ? 'urgente' : (puede(g) ? 'abierta' : '');
        return `
            <article class="pd-card ${clase}" aria-label="Grupo ${esc(g.clave)}">
                <div class="pd-card-head">
                    <div>
                        <h2>${esc(g.clave)}${g.plantel ? `<span class="pd-chip">${esc(g.plantel)}</span>` : ''}</h2>
                        <div class="sub">${subtitulo(g)}</div>
                    </div>
                    ${pill(g)}
                </div>
                ${fechasCaptura(g.captura, g)}
                ${g.materias.length
                    ? `<ul class="pd-materias">${g.materias.map((m, i) => filaMateria(g, m, i)).join('')}</ul>`
                    : '<div class="pd-sin">Sin asignaturas en tu horario.</div>'}
            </article>`;
    }

    function pintarGrupos() {
        const cont = document.getElementById('pdGrupos');
        cont.innerHTML = grupos.length
            ? ordenar(grupos).map(tarjeta).join('')
            : `<div class="pd-vacio"><i class="fa-solid fa-chalkboard-user"></i>No tienes grupos asignados en tu horario.</div>`;
    }

    // Mostrar / ocultar el motivo de una asignatura bloqueada
    document.getElementById('pdGrupos').addEventListener('click', e => {
        const b = e.target.closest('.pd-bloq');
        if (!b) return;
        const mot = document.getElementById(b.getAttribute('aria-controls'));
        if (!mot) return;
        mot.hidden = !mot.hidden;
        b.setAttribute('aria-expanded', String(!mot.hidden));
    });

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
            (data.prorrogas || []).forEach(p => { prorrogas[`${p.id_grupo}-${p.id_materia}`] = p.fecha_limite; });
            pintarEncabezado(data.prorrogas || []);
            pintarGrupos();
        })
        .catch(err => {
            console.error('Error al cargar pendientes:', err);
            document.getElementById('pdResumen').textContent = '';
            document.getElementById('pdGrupos').innerHTML = `<div class="pd-vacio"><i class="fa-solid fa-triangle-exclamation"></i>No se pudo cargar la información. Recarga la página para intentarlo de nuevo.<br><small style="opacity:.7">${esc(err && err.message)}</small></div>`;
        });
})();
</script>

@endsection
