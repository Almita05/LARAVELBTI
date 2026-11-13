@extends('layouts.app')

@section('content')

@php
    /*
     * NOTA: los elementos que el JS muestra/oculta usan el atributo `hidden`
     * y NO llevan utilidades de display de Tailwind (son !important).
     */
    $card   = ' tw:border tw:border-slate-200 tw:bg-white tw:shadow-card';
    $label  = 'tw:mb-1.5 tw:block tw:text-[0.8125rem] tw:font-medium tw:text-slate-700';
    $select = 'tw:h-10 tw:w-full tw:border tw:border-slate-200 tw:bg-white tw:px-3 tw:text-sm tw:text-slate-900 tw:shadow-xs tw:outline-none tw:transition tw:hover:border-slate-300 tw:focus:border-brand-500 tw:focus:ring-4 tw:focus:ring-brand-500/10';
    $th     = 'tw:px-4 tw:py-2.5 tw:text-left tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-500';

    // Niveles separados por tipo para que el JS arme el select según la escuela
    $niveles = collect($niveles ?? [])->map(fn ($n) => ['id' => (int) $n->id, 'nombre' => $n->nombre])->sortBy('id')->values();
@endphp

<div class="tw:mx-auto tw:flex tw:max-w-[1600px] tw:flex-col tw:gap-6">

    {{-- ===================== ENCABEZADO ===================== --}}
    <header>
        <h1 class="tw:m-0 tw:text-[1.5rem] tw:font-bold tw:tracking-tight tw:text-slate-900">Búsqueda de grupo</h1>
        <p class="tw:m-0 tw:mt-1 tw:text-sm tw:text-slate-500">Encuentra el grupo que mejor se acomoda al horario y nivel de un prospecto.</p>
    </header>

    {{-- ===================== FILTROS ===================== --}}
    <section class="{{ $card }} tw:p-5">
        <div class="tw:mb-4 tw:flex tw:flex-wrap tw:items-center tw:justify-between tw:gap-2">
            <h2 class="tw:m-0 tw:flex tw:items-center tw:gap-2 tw:text-[0.9375rem] tw:font-semibold tw:text-slate-900">
                <i data-lucide="sliders-horizontal" class="tw:size-4 tw:text-brand-700"></i> Preferencias del prospecto
            </h2>
            <button type="button" id="btnLimpiar" disabled
                class="tw:inline-flex tw:cursor-pointer tw:items-center tw:gap-1.5 tw:border-0 tw:bg-transparent tw:px-2.5 tw:py-1.5 tw:text-[0.8125rem] tw:font-medium tw:text-slate-500 tw:transition tw:hover:bg-slate-100 tw:hover:text-slate-900 tw:disabled:cursor-default tw:disabled:opacity-40 tw:disabled:hover:bg-transparent">
                <i data-lucide="rotate-ccw" class="tw:size-4"></i> Limpiar filtros
            </button>
        </div>

        <div class="tw:grid tw:grid-cols-1 tw:gap-4 tw:sm:grid-cols-2 tw:xl:grid-cols-4">
            <div>
                <label for="filtroCCT" class="{{ $label }}">Escuela</label>
                <select id="filtroCCT" class="{{ $select }}">
                    <option value="">Todas</option>
                    <option value="2">BTI, Tecnológico Interamericano</option>
                    <option value="3">BGNE, General No Escolarizado</option>
                    <option value="1">Informática y Computación</option>
                </select>
            </div>
            <div>
                <label for="filtroDia" class="{{ $label }}">Día de preferencia</label>
                <select id="filtroDia" class="{{ $select }}"></select>
            </div>
            <div>
                <label for="filtroTurno" class="{{ $label }}">Turno</label>
                <select id="filtroTurno" class="{{ $select }}"></select>
            </div>
            <div>
                <label for="filtroNivel" class="{{ $label }}" id="lblNivel">Semestre o trimestre de interés</label>
                <select id="filtroNivel" class="{{ $select }}"></select>
            </div>
        </div>
    </section>

    {{-- ===================== RESULTADOS ===================== --}}
    <section class="{{ $card }} tw:overflow-hidden" aria-live="polite">
        <div class="tw:flex tw:flex-wrap tw:items-center tw:justify-between tw:gap-2 tw:border-b tw:border-slate-100 tw:px-5 tw:py-3.5">
            <h2 class="tw:m-0 tw:text-[0.9375rem] tw:font-semibold tw:text-slate-900">Resultados</h2>
            <span id="conteoResultados" class="tw:text-[0.8125rem] tw:text-slate-500"></span>
        </div>

        {{-- Aviso: se muestran grupos del nivel anterior como próximos ingresos --}}
        <div id="avisoSugerencia" hidden>
            <div class="tw:mx-5 tw:mt-4 tw:flex tw:items-start tw:gap-3 tw:border tw:border-warning-600/30 tw:bg-warning-50 tw:px-4 tw:py-3 tw:text-[0.8125rem] tw:text-slate-700">
                <i data-lucide="forward" class="tw:mt-0.5 tw:size-4 tw:shrink-0 tw:text-warning-600"></i>
                <p class="tw:m-0" id="avisoSugerenciaTexto"></p>
            </div>
        </div>

        <div id="estadoResultados"></div>

        <div id="tablaWrapper" hidden>
            <div class="tw:overflow-x-auto">
                <table class="tw:w-full tw:min-w-[920px] tw:border-collapse">
                    <thead class="tw:bg-slate-50">
                        <tr class="tw:border-b tw:border-slate-200">
                            <th class="{{ $th }} tw:pl-5">Grupo</th>
                            <th class="{{ $th }}">Nivel</th>
                            <th class="{{ $th }}">Horario</th>
                            <th class="{{ $th }}">Periodo del nivel</th>
                            <th class="{{ $th }} tw:text-center">Alumnos</th>
                            <th class="{{ $th }} tw:pr-5">Estatus</th>
                        </tr>
                    </thead>
                    <tbody id="resultadosBody" class="tw:divide-y tw:divide-slate-100"></tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const NIVELES = @json($niveles);
    const filtros = {
        cct: document.getElementById('filtroCCT'),
        dia: document.getElementById('filtroDia'),
        turno: document.getElementById('filtroTurno'),
        nivel: document.getElementById('filtroNivel'),
    };
    const estado = document.getElementById('estadoResultados');
    const tablaWrapper = document.getElementById('tablaWrapper');
    const tbody = document.getElementById('resultadosBody');
    const conteo = document.getElementById('conteoResultados');
    const aviso = document.getElementById('avisoSugerencia');

    const esc = (v) => String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const refrescarIconos = () => { if (window.lucide?.createIcons) lucide.createIcons(); };
    const CCT_NOMBRE = { 1: 'Informática', 2: 'BTI', 3: 'BGNE' };

    // ------------------------------------------------------------- fechas
    /** Acepta "2026-10-25", ISO o "Sun, 27 Jul 2025 00:00:00 GMT" sin desfase de zona horaria. */
    function parseFecha(v) {
        if (!v) return null;
        const m = String(v).match(/^(\d{4})-(\d{2})-(\d{2})/);
        if (m) return new Date(Date.UTC(+m[1], +m[2] - 1, +m[3]));
        const d = new Date(v);
        return isNaN(d) ? null : new Date(Date.UTC(d.getUTCFullYear(), d.getUTCMonth(), d.getUTCDate()));
    }
    const fmt = (d) => d ? d.toLocaleDateString('es-MX', { day: '2-digit', month: 'short', year: 'numeric', timeZone: 'UTC' }) : '—';
    const sumarDias = (d, n) => new Date(d.getTime() + n * 864e5);

    // ------------------------------------------------------------- opciones dependientes
    /** Reconstruye un <select> conservando el valor si sigue siendo válido (ocultar <option> no funciona en Safari). */
    function llenar(select, opciones, textoTodos) {
        const previo = select.value;
        select.innerHTML = `<option value="">${textoTodos}</option>` +
            opciones.map(([v, t]) => `<option value="${esc(v)}">${esc(t)}</option>`).join('');
        select.value = opciones.some(([v]) => String(v) === previo) ? previo : '';
    }

    function actualizarOpciones() {
        const cct = filtros.cct.value;
        const dia = filtros.dia.value;

        // Días: BTI solo entre semana, BGNE solo fines de semana
        const dias = { '2': [['LUNES-VIERNES', 'Lunes a viernes']], '3': [['SABADO', 'Sábados'], ['DOMINGO', 'Domingos']] }[cct]
            ?? [['LUNES-VIERNES', 'Lunes a viernes'], ['SABADO', 'Sábados'], ['DOMINGO', 'Domingos']];
        llenar(filtros.dia, dias, 'Cualquier día');
        if (cct === '2' && !filtros.dia.value && dia) filtros.dia.value = 'LUNES-VIERNES';

        // Turnos según escuela y día
        let turnos = [['MATUTINO', 'Matutino'], ['VESPERTINO', 'Vespertino'], ['SABATINO', 'Sabatino (sábado completo)'], ['DOMINICAL', 'Dominical (domingo completo)']];
        if (cct === '2' || filtros.dia.value === 'LUNES-VIERNES') turnos = turnos.slice(0, 2);
        if (filtros.dia.value === 'SABADO') turnos = turnos.filter(([v]) => v !== 'DOMINICAL');
        if (filtros.dia.value === 'DOMINGO') turnos = turnos.filter(([v]) => v !== 'SABATINO');
        llenar(filtros.turno, turnos, 'Cualquier turno');

        // Niveles: BTI semestres (7–12), BGNE trimestres (1–6)
        const niveles = NIVELES.filter(n => cct === '2' ? n.id >= 7 : cct === '3' ? n.id <= 6 : true);
        llenar(filtros.nivel, niveles.map(n => [n.id, n.nombre]), 'Cualquier nivel');
        document.getElementById('lblNivel').textContent = cct === '2' ? 'Semestre de interés' : cct === '3' ? 'Trimestre de interés' : 'Semestre o trimestre de interés';

        document.getElementById('btnLimpiar').disabled = !Object.values(filtros).some(f => f.value);
    }

    // ------------------------------------------------------------- estados
    function mostrarEstado(icon, titulo, texto, tono = 'tw:text-slate-400') {
        tablaWrapper.hidden = true;
        aviso.hidden = true;
        conteo.textContent = '';
        estado.innerHTML = `
            <div class="tw:flex tw:flex-col tw:items-center tw:px-6 tw:py-16 tw:text-center">
                <span class="tw:inline-flex tw:size-12 tw:items-center tw:justify-center tw:bg-slate-100 ${tono}"><i data-lucide="${icon}" class="tw:size-5"></i></span>
                <p class="tw:m-0 tw:mt-3 tw:text-sm tw:font-semibold tw:text-slate-900">${titulo}</p>
                <p class="tw:m-0 tw:mt-1 tw:max-w-md tw:text-[0.8125rem] tw:text-slate-500">${texto}</p>
            </div>`;
        refrescarIconos();
    }

    const inicial = () => mostrarEstado('search', 'Elige al menos un filtro', 'Selecciona escuela, día, turno o nivel para ver los grupos disponibles.');

    // ------------------------------------------------------------- búsqueda
    let peticion = 0; // descarta respuestas de búsquedas anteriores

    async function consultar(params) {
        const res = await fetch(`/grupos/lista?${params}`, { headers: { Accept: 'application/json' } });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return (await res.json()).data || [];
    }

    async function buscar() {
        const { cct, dia, turno, nivel } = Object.fromEntries(Object.entries(filtros).map(([k, f]) => [k, f.value]));
        if (!cct && !dia && !turno && !nivel) return inicial();

        const params = new URLSearchParams({ limit: 100 });
        if (cct) params.set('id_centro_trabajo', cct);
        if (dia) params.set('dia', dia);
        if (turno) params.set('modalidad_horario', turno);
        if (nivel) params.set('id_nivel_academico', nivel);

        const req = ++peticion;
        tablaWrapper.hidden = true;
        aviso.hidden = true;
        conteo.textContent = '';
        estado.innerHTML = `<div class="tw:flex tw:flex-col tw:items-center tw:gap-3 tw:py-16 tw:text-[0.8125rem] tw:text-slate-500">
            <span class="tw:inline-block tw:size-6 tw:animate-spin tw:rounded-full tw:border-2 tw:border-slate-200 tw:border-t-brand-700"></span>Buscando grupos…</div>`;

        try {
            let grupos = await consultar(params);
            let sugerencia = null;

            // Sin grupos en ese nivel: mostrar los del nivel anterior, que avanzarán a él
            const nivelId = parseInt(nivel);
            const anterior = (nivelId >= 2 && nivelId <= 6) || (nivelId >= 8 && nivelId <= 12) ? nivelId - 1 : null;
            if (!grupos.length && anterior) {
                const p2 = new URLSearchParams(params);
                p2.set('id_nivel_academico', anterior);
                grupos = await consultar(p2);
                if (grupos.length) sugerencia = nivelId;
            }
            if (req !== peticion) return;

            if (!grupos.length) {
                return mostrarEstado('calendar-x', 'Sin opciones disponibles', 'No hay grupos activos ni próximos ingresos con esos criterios. Prueba quitando algún filtro.', 'tw:text-warning-600');
            }
            render(grupos, sugerencia);
        } catch (e) {
            if (req !== peticion) return;
            console.error(e);
            mostrarEstado('circle-alert', 'No se pudo consultar', 'Ocurrió un error al obtener los grupos. Intenta de nuevo.', 'tw:text-danger-600');
        }
    }

    function render(grupos, nivelSugerido) {
        estado.innerHTML = '';
        conteo.textContent = `${grupos.length} ${grupos.length === 1 ? 'grupo' : 'grupos'}`;

        if (nivelSugerido) {
            const nombre = NIVELES.find(n => n.id === nivelSugerido)?.nombre || 'ese nivel';
            document.getElementById('avisoSugerenciaTexto').innerHTML =
                `<strong class="tw:font-semibold">Próximo ingreso:</strong> ningún grupo cursa <strong class="tw:font-semibold">${esc(nombre)}</strong> en este momento. ` +
                `Estos grupos del nivel anterior avanzarán a él; las fechas mostradas son estimadas.`;
            aviso.hidden = false;
        }

        tbody.innerHTML = grupos.map(g => {
            const esTrimestre = parseInt(g.id_nivel_academico) <= 6;
            let inicio = parseFecha(g.fechaInicioNivel || g.fechaInicio);
            let fin = parseFecha(g.fechaFinNivel || g.fechaFin);
            let periodo;

            if (nivelSugerido && fin) {
                if (esTrimestre) {
                    // Trimestre: el siguiente inicia 7 días después y dura 13 semanas
                    inicio = sumarDias(fin, 7);
                    fin = sumarDias(inicio, 12 * 7);
                    periodo = `${fmt(inicio)} – ${fmt(fin)}`;
                } else {
                    // Semestre: no hay regla de duración fija; solo se sabe cuándo termina el actual
                    periodo = `Después del ${fmt(fin)}`;
                }
            } else {
                periodo = `${fmt(inicio)} – ${fmt(fin)}`;
            }

            const dias = Array.isArray(g.diasClase) ? g.diasClase.join(', ') : (g.diasClase || '');
            const activo = g.statusGrupo === 'ACTIVO';
            const cct = CCT_NOMBRE[g.id_centroTrabajo] || g.nombreCentroTrabajo || '';

            return `
            <tr class="tw:transition-colors tw:hover:bg-slate-50">
                <td class="tw:px-4 tw:py-3 tw:pl-5">
                    <p class="tw:m-0 tw:font-mono tw:text-sm tw:font-semibold tw:text-slate-900">${esc(g.clave)}</p>
                    <p class="tw:m-0 tw:mt-0.5 tw:flex tw:items-center tw:gap-1.5 tw:text-xs tw:text-slate-500">
                        <span class=" tw:bg-brand-50 tw:px-1.5 tw:font-semibold tw:text-brand-700">${esc(cct)}</span>
                        ${g.aula ? `<span>Aula ${esc(g.aula)}</span>` : ''}
                    </p>
                </td>
                <td class="tw:px-4 tw:py-3">
                    <span class="tw:text-[0.8125rem] tw:font-medium tw:text-slate-800">${esc(g.nombre_nivel || '—')}</span>
                    ${nivelSugerido ? `<span class="tw:mt-1 tw:flex tw:w-fit tw:items-center tw:gap-1 tw:rounded-none tw:bg-warning-100 tw:px-2 tw:py-0.5 tw:text-[0.6875rem] tw:font-semibold tw:text-warning-600"><i data-lucide="forward" class="tw:size-3"></i> Pasa al siguiente</span>` : ''}
                </td>
                <td class="tw:px-4 tw:py-3">
                    <p class="tw:m-0 tw:flex tw:items-center tw:gap-1.5 tw:text-[0.8125rem] tw:text-slate-700"><i data-lucide="calendar-days" class="tw:size-3.5 tw:text-slate-400"></i>${esc(dias || '—')}</p>
                    <p class="tw:m-0 tw:mt-0.5 tw:flex tw:items-center tw:gap-1.5 tw:text-xs tw:text-slate-500"><i data-lucide="clock" class="tw:size-3.5 tw:text-slate-400"></i>${esc(g.modalidadHorario || '—')}</p>
                </td>
                <td class="tw:px-4 tw:py-3 tw:text-[0.8125rem] tw:whitespace-nowrap tw:text-slate-700">${periodo}</td>
                <td class="tw:px-4 tw:py-3 tw:text-center">
                    <span class="tw:inline-flex tw:items-center tw:gap-1 tw:text-sm tw:font-semibold tw:tabular-nums tw:text-slate-900"><i data-lucide="users" class="tw:size-3.5 tw:text-slate-400"></i>${Number(g.alumnos_count) || 0}</span>
                </td>
                <td class="tw:px-4 tw:py-3 tw:pr-5">
                    <span class="tw:inline-flex tw:items-center tw:gap-1.5 tw:rounded-none tw:px-2.5 tw:py-0.5 tw:text-[0.6875rem] tw:font-semibold ${activo ? 'tw:bg-success-100 tw:text-success-700' : 'tw:bg-slate-100 tw:text-slate-500'}">
                        <span class="tw:size-1.5 tw:rounded-full ${activo ? 'tw:bg-success-500' : 'tw:bg-slate-400'}"></span>${activo ? 'Activo' : esc(g.statusGrupo || 'Inactivo')}
                    </span>
                </td>
            </tr>`;
        }).join('');

        tablaWrapper.hidden = false;
        refrescarIconos();
    }

    // ------------------------------------------------------------- eventos
    Object.values(filtros).forEach(f => f.addEventListener('change', () => {
        if (f === filtros.cct || f === filtros.dia) actualizarOpciones();
        document.getElementById('btnLimpiar').disabled = !Object.values(filtros).some(x => x.value);
        buscar();
    }));
    document.getElementById('btnLimpiar').addEventListener('click', () => {
        Object.values(filtros).forEach(f => f.value = '');
        actualizarOpciones();
        buscar();
    });

    actualizarOpciones();
    inicial();
});
</script>

@endsection
