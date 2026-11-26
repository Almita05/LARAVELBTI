@extends('layouts.app')

@section('content')

@php
    $card  = 'tw:border tw:border-slate-200 tw:bg-white tw:shadow-card';
    $label = 'tw:mb-1.5 tw:block tw:text-[0.8125rem] tw:font-medium tw:text-slate-700';
    $input = 'tw:h-10 tw:w-full tw:border tw:border-slate-200 tw:bg-white tw:px-3 tw:text-sm tw:text-slate-900 tw:shadow-xs tw:outline-none tw:transition tw:focus:border-brand-500 tw:focus:ring-4 tw:focus:ring-brand-500/10';
    $th    = 'tw:px-4 tw:py-2.5 tw:text-left tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-500';
    $seg   = 'tw:h-10 tw:cursor-pointer tw:border-0 tw:bg-transparent tw:px-5 tw:text-sm tw:font-medium tw:text-slate-600 tw:transition tw:aria-pressed:bg-brand-700 tw:aria-pressed:text-white tw:aria-pressed:font-semibold';
@endphp

<div class="tw:mx-auto tw:max-w-[1400px] tw:px-4 tw:py-6 tw:sm:px-6">
    <header class="tw:mb-5">
        <h1 class="tw:m-0 tw:text-xl tw:font-bold tw:text-slate-900">Métricas</h1>
        <p class="tw:m-0 tw:mt-1 tw:text-sm tw:text-slate-500">Asistencia del pase de lista general y alumnos en riesgo por plantel.</p>
    </header>

    {{-- Filtros --}}
    <section class="{{ $card }} tw:mb-5 tw:p-4" aria-label="Filtros">
        <div class="tw:flex tw:flex-wrap tw:items-end tw:gap-4">
            <div>
                <span class="{{ $label }}">Plantel</span>
                <div class="tw:inline-flex tw:border tw:border-slate-200" role="group" aria-label="Plantel">
                    <button type="button" class="{{ $seg }}" data-plantel="2" aria-pressed="true">BTI</button>
                    <button type="button" class="{{ $seg }}" data-plantel="3" aria-pressed="false">BGNE</button>
                </div>
            </div>
            <div class="tw:min-w-[200px]">
                <label for="fGrupo" class="{{ $label }}">Grupo</label>
                <select id="fGrupo" class="{{ $input }}"><option value="">Todos los grupos activos</option></select>
            </div>
            <div class="tw:min-w-[220px]">
                <label for="fPeriodo" class="{{ $label }}">Periodo</label>
                <select id="fPeriodo" class="{{ $input }}">
                    <option value="actual" id="optPeriodoActual">Semestre en curso</option>
                    <option value="4s">Últimas 4 semanas</option>
                    <option value="12s">Últimas 12 semanas</option>
                    <option value="personalizado">Personalizado…</option>
                </select>
            </div>
            {{-- Sin utilidades de display en el contenedor: las de Tailwind son !important y anularían hidden --}}
            <div id="fFechas" hidden>
                <div class="tw:flex tw:gap-3">
                    <div><label for="fDesde" class="{{ $label }}">Desde</label><input type="date" id="fDesde" class="{{ $input }}"></div>
                    <div><label for="fHasta" class="{{ $label }}">Hasta</label><input type="date" id="fHasta" class="{{ $input }}"></div>
                </div>
            </div>
            <p id="rangoTexto" class="tw:m-0 tw:ml-auto tw:self-center tw:text-xs tw:text-slate-500" aria-live="polite"></p>
        </div>
    </section>

    <div id="estadoCarga" class="tw:py-16 tw:text-center tw:text-sm tw:text-slate-500" role="status">Cargando métricas…</div>

    <div id="contenido" hidden>
        {{-- Resumen --}}
        <section aria-label="Resumen" class="tw:mb-5 tw:grid tw:grid-cols-2 tw:gap-3 tw:md:grid-cols-3 tw:xl:grid-cols-6" id="kpis"></section>

        {{-- Tendencia --}}
        <section class="{{ $card }} tw:mb-5 tw:p-5">
            <h2 class="tw:m-0 tw:text-base tw:font-semibold tw:text-slate-900">Asistencia por semana</h2>
            <p class="tw:m-0 tw:mt-0.5 tw:text-xs tw:text-slate-500">Porcentaje de asistencias (incluye retardos) sobre los días registrados de cada semana.</p>
            <div class="tw:relative tw:mt-4 tw:h-[260px]"><canvas id="graficaTendencia" role="img" aria-label="Porcentaje de asistencia por semana"></canvas></div>
            <p id="tendenciaVacia" class="tw:m-0 tw:py-10 tw:text-center tw:text-sm tw:text-slate-500" hidden>Sin pases de lista en el periodo.</p>
        </section>

        {{-- Por grupo --}}
        <section class="{{ $card }} tw:mb-5">
            <div class="tw:px-5 tw:pt-5 tw:pb-3">
                <h2 class="tw:m-0 tw:text-base tw:font-semibold tw:text-slate-900">Asistencia por grupo</h2>
                <p class="tw:m-0 tw:mt-0.5 tw:text-xs tw:text-slate-500">De menor a mayor asistencia. Toca un grupo para ver solo sus métricas.</p>
            </div>
            <div class="tw:overflow-x-auto">
                <table class="tw:w-full tw:border-collapse tw:text-sm">
                    <thead class="tw:bg-slate-50">
                        <tr>
                            <th class="{{ $th }}">Grupo</th>
                            <th class="{{ $th }}">Periodo</th>
                            <th class="{{ $th }} tw:text-right">Alumnos</th>
                            <th class="{{ $th }} tw:min-w-[180px]">Asistencia</th>
                            <th class="{{ $th }} tw:text-right">Retardos</th>
                            <th class="{{ $th }} tw:text-right">Faltas sin justificar</th>
                            <th class="{{ $th }} tw:text-right">Justificadas</th>
                            <th class="{{ $th }} tw:text-right" title="Días con pase de lista general / días de clase">Días con pase</th>
                        </tr>
                    </thead>
                    <tbody id="tablaGrupos"></tbody>
                </table>
            </div>
        </section>

        <div class="tw:grid tw:grid-cols-1 tw:gap-5 tw:xl:grid-cols-2">
            {{-- Riesgo --}}
            <section class="{{ $card }}">
                <div class="tw:px-5 tw:pt-5 tw:pb-3">
                    <h2 class="tw:m-0 tw:text-base tw:font-semibold tw:text-slate-900">Alumnos en riesgo por asistencia</h2>
                    <p class="tw:m-0 tw:mt-0.5 tw:text-xs tw:text-slate-500" id="riesgoNota"></p>
                </div>
                <div class="tw:max-h-[420px] tw:overflow-auto">
                    <table class="tw:w-full tw:border-collapse tw:text-sm">
                        <thead class="tw:sticky tw:top-0 tw:bg-slate-50">
                            <tr>
                                <th class="{{ $th }}">Alumno</th>
                                <th class="{{ $th }}">Grupo</th>
                                <th class="{{ $th }} tw:text-right">Asistencia</th>
                                <th class="{{ $th }} tw:text-right">Faltas</th>
                                <th class="{{ $th }} tw:text-right">Retardos</th>
                            </tr>
                        </thead>
                        <tbody id="tablaRiesgo"></tbody>
                    </table>
                </div>
            </section>

            {{-- Retardos --}}
            <section class="{{ $card }}">
                <div class="tw:px-5 tw:pt-5 tw:pb-3">
                    <h2 class="tw:m-0 tw:text-base tw:font-semibold tw:text-slate-900">Alumnos con más retardos</h2>
                    <p class="tw:m-0 tw:mt-0.5 tw:text-xs tw:text-slate-500" id="retardosNota"></p>
                </div>
                <div class="tw:max-h-[420px] tw:overflow-auto">
                    <table class="tw:w-full tw:border-collapse tw:text-sm">
                        <thead class="tw:sticky tw:top-0 tw:bg-slate-50">
                            <tr>
                                <th class="{{ $th }}">Alumno</th>
                                <th class="{{ $th }}">Grupo</th>
                                <th class="{{ $th }} tw:text-right">Retardos</th>
                                <th class="{{ $th }} tw:text-right">Asistencia</th>
                            </tr>
                        </thead>
                        <tbody id="tablaRetardos"></tbody>
                    </table>
                </div>
            </section>
        </div>

        <p id="omitidos" class="tw:m-0 tw:mt-4 tw:text-xs tw:text-slate-500" hidden></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    const MARCA = '#26687B';
    const estado = { plantel: 2, grupo: '', periodo: 'actual', desde: '', hasta: '' };
    let grafica = null;

    const $ = id => document.getElementById(id);
    const esc = v => String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const num = v => (v === null || v === undefined) ? '—' : Number(v).toLocaleString('es-MX');
    const pct = v => (v === null || v === undefined) ? '—' : `${v}%`;
    const fecha = iso => { if (!iso) return ''; const [y, m, d] = iso.split('-'); return `${d}/${m}/${y}`; };
    const fechaCorta = iso => { const [, m, d] = iso.split('-'); return `${d}/${m}`; };

    function kpi(titulo, valor, detalle = '') {
        return `<div class="tw:border tw:border-slate-200 tw:bg-white tw:p-4 tw:shadow-card">
            <p class="tw:m-0 tw:text-xs tw:font-medium tw:text-slate-500">${titulo}</p>
            <p class="tw:m-0 tw:mt-1 tw:text-2xl tw:font-bold tw:tabular-nums tw:text-slate-900">${valor}</p>
            ${detalle ? `<p class="tw:m-0 tw:mt-0.5 tw:text-xs tw:text-slate-500">${detalle}</p>` : ''}
        </div>`;
    }

    // Barra de magnitud de un solo tono (asistencia por grupo)
    function barra(valor) {
        if (valor === null || valor === undefined) return '<span class="tw:text-slate-400">Sin pase</span>';
        return `<div class="tw:flex tw:items-center tw:gap-2">
            <div class="tw:h-2 tw:flex-1 tw:bg-slate-100" aria-hidden="true"><div class="tw:h-2" style="width:${Math.max(0, Math.min(100, valor))}%;background:${MARCA}"></div></div>
            <span class="tw:w-12 tw:text-right tw:tabular-nums tw:font-semibold tw:text-slate-900">${valor}%</span>
        </div>`;
    }

    function pintar(d) {
        const k = d.kpis;
        $('rangoTexto').textContent = d.periodo.desde ? `Del ${fecha(d.periodo.desde)} al ${fecha(d.periodo.hasta)}` : '';
        $('kpis').innerHTML = [
            kpi('Asistencia', pct(k.porcentaje_asistencia), `${num(k.asistencias)} asistencias`),
            kpi('Retardos', num(k.retardos), 'Cuentan como asistencia'),
            kpi('Faltas sin justificar', num(k.faltas_sin_justificar)),
            kpi('Faltas justificadas', num(k.faltas_justificadas)),
            kpi('Alumnos en riesgo', num(k.alumnos_en_riesgo), `de ${num(k.alumnos)} alumnos`),
            kpi('Días con pase general', pct(k.cobertura_pase), `${num(k.grupos)} grupos`),
        ].join('');

        // Tendencia semanal (una serie)
        const t = d.tendencia.filter(s => s.porcentaje_asistencia !== null);
        $('tendenciaVacia').hidden = t.length > 0;
        $('graficaTendencia').parentElement.hidden = t.length === 0;
        if (grafica) { grafica.destroy(); grafica = null; }
        if (t.length) {
            grafica = new Chart($('graficaTendencia'), {
                type: 'line',
                data: {
                    labels: t.map(s => `Sem. ${fechaCorta(s.semana)}`),
                    datasets: [{
                        label: 'Asistencia', data: t.map(s => s.porcentaje_asistencia),
                        borderColor: MARCA, backgroundColor: MARCA, borderWidth: 2,
                        pointRadius: 4, pointHoverRadius: 6, pointBorderColor: '#ffffff', pointBorderWidth: 2, tension: 0,
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: {
                            title: items => `Semana del ${fecha(t[items[0].dataIndex].semana)}`,
                            label: item => {
                                const s = t[item.dataIndex];
                                return [`Asistencia: ${s.porcentaje_asistencia}%`, `Retardos: ${s.retardos}`, `Faltas: ${s.faltas}`];
                            },
                        } },
                    },
                    scales: {
                        y: { min: 0, max: 100, ticks: { callback: v => `${v}%`, color: '#64748b' }, grid: { color: '#f1f5f9' }, border: { display: false } },
                        x: { ticks: { color: '#64748b', maxRotation: 0, autoSkip: true }, grid: { display: false } },
                    },
                },
            });
        }

        // Por grupo
        $('tablaGrupos').innerHTML = d.grupos.length ? d.grupos.map(g => `
            <tr class="tw:cursor-pointer tw:border-t tw:border-slate-100 tw:hover:bg-slate-50" data-grupo="${g.id}" tabindex="0" aria-label="Ver métricas de ${esc(g.clave)}">
                <td class="tw:px-4 tw:py-2.5"><span class="tw:font-mono tw:font-semibold tw:text-slate-900">${esc(g.clave)}</span>${g.nivel ? `<span class="tw:block tw:text-xs tw:text-slate-500">${esc(g.nivel)}</span>` : ''}</td>
                <td class="tw:px-4 tw:py-2.5 tw:text-xs tw:text-slate-500 tw:whitespace-nowrap">${fecha(g.desde)} – ${fecha(g.hasta)}</td>
                <td class="tw:px-4 tw:py-2.5 tw:text-right tw:tabular-nums">${num(g.alumnos)}</td>
                <td class="tw:px-4 tw:py-2.5">${barra(g.porcentaje_asistencia)}</td>
                <td class="tw:px-4 tw:py-2.5 tw:text-right tw:tabular-nums">${num(g.retardos)}</td>
                <td class="tw:px-4 tw:py-2.5 tw:text-right tw:tabular-nums">${num(g.faltas_sin_justificar)}</td>
                <td class="tw:px-4 tw:py-2.5 tw:text-right tw:tabular-nums">${num(g.faltas_justificadas)}</td>
                <td class="tw:px-4 tw:py-2.5 tw:text-right tw:tabular-nums" title="${g.dias_sin_pase} días de clase sin pase general">${pct(g.cobertura_pase)}</td>
            </tr>`).join('')
            : `<tr><td colspan="8" class="tw:px-4 tw:py-8 tw:text-center tw:text-slate-500">No hay grupos activos con datos en el periodo.</td></tr>`;

        // Riesgo y retardos
        const u = d.umbrales;
        $('riesgoNota').textContent = `Asistencia menor a ${u.asistencia}% con al menos ${u.min_dias} días registrados. De menor a mayor.`;
        $('retardosNota').textContent = `${u.retardos} o más retardos en el periodo.`;
        const fila = (a, cols) => `<tr class="tw:border-t tw:border-slate-100">
            <td class="tw:px-4 tw:py-2"><span class="tw:font-medium tw:text-slate-900">${esc(a.nombre)}</span>${a.numeroControl ? `<span class="tw:block tw:text-xs tw:text-slate-500">${esc(a.numeroControl)}</span>` : ''}</td>
            <td class="tw:px-4 tw:py-2 tw:font-mono tw:text-xs">${esc(a.grupo)}</td>${cols}</tr>`;
        const vacio = (n, txt) => `<tr><td colspan="${n}" class="tw:px-4 tw:py-8 tw:text-center tw:text-slate-500">${txt}</td></tr>`;
        $('tablaRiesgo').innerHTML = d.riesgo.length ? d.riesgo.map(a => fila(a, `
            <td class="tw:px-4 tw:py-2 tw:text-right tw:tabular-nums tw:font-semibold tw:text-slate-900">${pct(a.porcentaje_asistencia)}</td>
            <td class="tw:px-4 tw:py-2 tw:text-right tw:tabular-nums">${num(a.faltas_sin_justificar)}${a.faltas_justificadas ? `<span class="tw:text-xs tw:text-slate-500"> (+${a.faltas_justificadas} just.)</span>` : ''}</td>
            <td class="tw:px-4 tw:py-2 tw:text-right tw:tabular-nums">${num(a.retardos)}</td>`)).join('') : vacio(5, 'Ningún alumno en riesgo por asistencia.');
        $('tablaRetardos').innerHTML = d.retardos_top.length ? d.retardos_top.map(a => fila(a, `
            <td class="tw:px-4 tw:py-2 tw:text-right tw:tabular-nums tw:font-semibold tw:text-slate-900">${num(a.retardos)}</td>
            <td class="tw:px-4 tw:py-2 tw:text-right tw:tabular-nums">${pct(a.porcentaje_asistencia)}</td>`)).join('') : vacio(4, 'Ningún alumno con retardos frecuentes.');

        $('omitidos').hidden = !d.omitidos.length;
        $('omitidos').textContent = d.omitidos.length ? `Sin datos en el periodo: ${d.omitidos.map(o => `${o.clave} (${o.motivo})`).join('; ')}` : '';

        // Opciones del filtro de grupo (se conservan al filtrar un grupo)
        const sel = $('fGrupo');
        sel.innerHTML = '<option value="">Todos los grupos activos</option>' + d.grupos_lista.map(g => `<option value="${g.id}">${esc(g.clave)}</option>`).join('');
        sel.value = estado.grupo;
    }

    let peticion = 0;
    async function cargar() {
        if (estado.periodo === 'personalizado' && !(estado.desde && estado.hasta)) return;
        const id = ++peticion;
        $('estadoCarga').hidden = false;
        $('estadoCarga').textContent = 'Cargando métricas…';
        $('contenido').setAttribute('aria-busy', 'true');
        const q = new URLSearchParams({ id_centro_trabajo: estado.plantel, periodo: estado.periodo });
        if (estado.grupo) q.set('id_grupo', estado.grupo);
        if (estado.periodo === 'personalizado') { q.set('desde', estado.desde); q.set('hasta', estado.hasta); }
        try {
            const res = await fetch(`{{ route('metricas.datos') }}?${q}`, { headers: { Accept: 'application/json' } });
            const d = await res.json().catch(() => null);
            if (id !== peticion) return;
            if (!res.ok || !d || !d.success) throw new Error((d && (d.error || d.message)) || `Error del servidor (${res.status})`);
            pintar(d);
            $('estadoCarga').hidden = true;
            $('contenido').hidden = false;
        } catch (e) {
            if (id !== peticion) return;
            $('estadoCarga').textContent = `No se pudieron cargar las métricas: ${e.message}`;
            $('contenido').hidden = true;
        } finally {
            if (id === peticion) $('contenido').removeAttribute('aria-busy');
        }
    }

    document.querySelectorAll('[data-plantel]').forEach(btn => btn.addEventListener('click', () => {
        if (Number(btn.dataset.plantel) === estado.plantel) return;
        estado.plantel = Number(btn.dataset.plantel);
        estado.grupo = '';
        document.querySelectorAll('[data-plantel]').forEach(b => b.setAttribute('aria-pressed', String(b === btn)));
        $('optPeriodoActual').textContent = estado.plantel === 3 ? 'Trimestre en curso' : 'Semestre en curso';
        cargar();
    }));
    $('fGrupo').addEventListener('change', e => { estado.grupo = e.target.value; cargar(); });
    $('fPeriodo').addEventListener('change', e => {
        estado.periodo = e.target.value;
        $('fFechas').hidden = estado.periodo !== 'personalizado';
        cargar();
    });
    ['fDesde', 'fHasta'].forEach(id => $(id).addEventListener('change', () => {
        estado.desde = $('fDesde').value; estado.hasta = $('fHasta').value; cargar();
    }));
    $('tablaGrupos').addEventListener('click', e => {
        const tr = e.target.closest('tr[data-grupo]');
        if (tr) { estado.grupo = tr.dataset.grupo; cargar(); window.scrollTo({ top: 0, behavior: 'smooth' }); }
    });
    $('tablaGrupos').addEventListener('keydown', e => {
        if ((e.key === 'Enter' || e.key === ' ') && e.target.matches('tr[data-grupo]')) { e.preventDefault(); e.target.click(); }
    });

    cargar();
})();
</script>
@endsection
