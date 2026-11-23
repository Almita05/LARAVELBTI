{{--
    Boleta de calificaciones BTI (formato institucional, una hoja). Fuente única: la usan reportes/imprimir y alumnos/index.

    La boleta se entrega POR PARCIAL con el mismo formato; solo cambian los datos que se llenan:
      - Calificaciones: la tabla completa (1er, 2do, 3er parcial, semestral, extraordinario y final).
        En la boleta del parcial N se llenan los parciales 1..N; semestral, extraordinario y final solo en la FINAL.
      - Asistencia por parcial, calculada del pase de lista diario dentro de las fechas de cada parcial
        (Horarios > Fechas de Periodos de Evaluación): clases impartidas (días con pase), asistencias (A + R),
        retardos, faltas (F + J) y faltas justificadas, de cada parcial hasta el N.
        Si un parcial no tiene pase de lista se usa la captura provisional de Captura de Calificaciones.
      - Fechas de faltas, justificantes y retardos del parcial que se entrega.
      - Observaciones: descripción de los reportes de indisciplina del parcial que se entrega
        (fecha, folio e incidente). En la FINAL se listan los de los tres parciales.
    Lo capturado de cada parcial se conserva, así que cualquier parcial se puede reimprimir.

    window.imprimirBoletaBTISemestre(data, idNivelSemestre, reportesCounts, opciones)
      data            → respuesta del kárdex (alumno, periodos con materias, asistenciasParciales, periodosEvaluacion)
      idNivelSemestre → 7..12 (1er a 6to semestre)
      reportesCounts  → { 1: n, 2: n, 3: n } reportes de conducta por parcial
      opciones        → { parcial: '1'|'2'|'3'|'FINAL', incluirAsistencias, incluirReportes }
      El detalle de los reportes (observaciones) lo consulta la propia función en /alumnos/{id}/reportes-detalle.

    window.imprimirBoletasBTIGrupo(alumnos, grupo, idNivelSemestre, opciones, onProgreso)
      Boletas de todos los alumnos de un grupo en un solo documento, una hoja carta por alumno.

    window.parcialActualBTI(data) → parcial sugerido según las fechas del grupo ('1', '2' o '3').
    Nunca se imprimen datos de ejemplo.
--}}
<script>
(function () {
    const DIRECTOR = 'Ing. Fausto Leyva Flores';
    const ESCUELA = 'Bachillerato Tecnológico Interamericano';
    const CCT_DEFAULT = '21PCT0073R';
    const DOMICILIO = 'Avenida Benito Juárez 901, Colonia Centro, Teziutlán, Puebla. Tel. 231 312 3979';
    const SEMESTRES = { 7: 'Primer', 8: 'Segundo', 9: 'Tercer', 10: 'Cuarto', 11: 'Quinto', 12: 'Sexto' };
    const PARCIALES = { 1: 'Primer parcial', 2: 'Segundo parcial', 3: 'Tercer parcial' };
    const MESES = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    const APROBATORIA = 6;

    const esc = v => String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const num = v => (v === null || v === undefined || v === '' || isNaN(parseFloat(v))) ? null : parseFloat(v);
    const fechaCorta = iso => { const [y, m, d] = String(iso).slice(0, 10).split('-'); return `${d}/${m}/${y}`; };
    const fechaLarga = (d = new Date()) => `${d.getDate()} de ${MESES[d.getMonth()]} de ${d.getFullYear()}`;
    const cicloDe = d => { const y = d.getFullYear(); const ini = d.getMonth() >= 7 ? y : y - 1; return `${ini}-${ini + 1}`; };
    const promedio = arr => arr.length ? arr.reduce((a, b) => a + b, 0) / arr.length : null;
    const pctTxt = (a, t) => (t > 0 && a !== null && a !== undefined) ? `${Math.round(a / t * 1000) / 10}%` : '';
    // "31/08/2026" → Date
    const deDMY = s => { if (!s) return null; const [d, m, y] = String(s).split('/'); return (d && m && y) ? new Date(`${y}-${m}-${d}T00:00:00`) : null; };

    function celdaNota(v, extra = '') {
        if (v === null) return `<td class="n${extra}"></td>`;
        return `<td class="n${extra}${v < APROBATORIA ? ' rep' : ''}">${v.toFixed(1)}</td>`;
    }

    /** Parcial en curso según las fechas del grupo: el último que ya inició. */
    window.parcialActualBTI = function (data) {
        const pe = (data && data.periodosEvaluacion) || {};
        const hoy = new Date();
        let actual = '1';
        [1, 2, 3].forEach(n => {
            const ini = deDMY(pe[`p${n}_inicio`]);
            if (ini && ini <= hoy) actual = String(n);
        });
        return actual;
    };

    // ---------------------------------------------------------------
    // Pase de lista diario dentro de las fechas de un parcial
    // ---------------------------------------------------------------
    async function obtenerPaseParcial(al, idNivelSemestre, parcial) {
        if (!al.idGrupo) return null;
        try {
            const res = await fetch(`/boletas/bti/asistencia/grupo/${encodeURIComponent(al.idGrupo)}/alumno/${encodeURIComponent(al.idAlumno)}?parcial=${encodeURIComponent(parcial)}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const d = await res.json().catch(() => null);
            if (!res.ok || !d || d.error) return null;
            if (!d.grupo || Number(d.grupo.id_nivel) !== Number(idNivelSemestre)) return null;
            return d;
        } catch (e) {
            return null;
        }
    }

    // ---------------------------------------------------------------
    // Reportes de indisciplina del alumno (detalle para las observaciones)
    // ---------------------------------------------------------------
    async function obtenerReportes(idAlumno) {
        if (!idAlumno) return [];
        try {
            const res = await fetch(`/alumnos/${encodeURIComponent(idAlumno)}/reportes-detalle`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, cache: 'no-store'
            });
            const d = await res.json().catch(() => null);
            if (!res.ok || !d || !Array.isArray(d.data)) return [];
            return d.data.filter(r => r.id_alumno === undefined || r.id_alumno === null || String(r.id_alumno) === String(idAlumno));
        } catch (e) {
            return [];
        }
    }

    // La fecha del reporte puede venir como "2026-09-14" o como fecha HTTP ("Mon, 14 Sep 2026 00:00:00 GMT")
    function fechaReporte(v) {
        if (!v) return '';
        if (/^\d{4}-\d{2}-\d{2}/.test(String(v))) return fechaCorta(v);
        const d = new Date(v);
        if (isNaN(d)) return String(v);
        const p = x => String(x).padStart(2, '0');
        return `${p(d.getUTCDate())}/${p(d.getUTCMonth() + 1)}/${d.getUTCFullYear()}`;
    }
    const ordenFecha = v => { const d = new Date(v); return isNaN(d) ? 0 : d.getTime(); };

    // ---------------------------------------------------------------
    // Estilos del documento (formato institucional, impresión en carta)
    // ---------------------------------------------------------------
    const CSS = `
        @page { size: letter portrait; margin: 10mm 14mm 10mm; }
        * { box-sizing: border-box; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        html, body { margin: 0; padding: 0; background: #fff; }
        body { font-family: 'Public Sans', Arial, Helvetica, sans-serif; font-size: 8.6pt; color: #111; line-height: 1.3; }
        table { border-collapse: collapse; width: 100%; }

        /* Membrete */
        .membrete { display: grid; grid-template-columns: 22mm 1fr 34mm; align-items: center; gap: 4mm; }
        .membrete img { width: 20mm; height: 20mm; object-fit: contain; }
        .membrete-centro { text-align: center; }
        .membrete-dep { font-size: 7.4pt; text-transform: uppercase; color: #333; }
        .membrete-esc { font-size: 13pt; font-weight: 700; text-transform: uppercase; margin: .6mm 0; color: #000; }
        .membrete-dom { font-size: 7pt; color: #444; }
        .membrete-ciclo { border: .75pt solid #000; text-align: center; }
        .membrete-ciclo div:first-child { font-size: 6.6pt; text-transform: uppercase; border-bottom: .75pt solid #000; padding: .8mm 0; background: #ececec; }
        .membrete-ciclo div:last-child { font-size: 9.5pt; font-weight: 700; padding: 1.2mm 0; }
        .filete { border-top: 1.6pt solid #26687B; border-bottom: .5pt solid #26687B; height: 1.4mm; margin: 2.5mm 0 3.5mm; }

        .titulo { text-align: center; margin-bottom: 3.5mm; }
        .titulo h1 { font-size: 11.5pt; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: .3pt; }
        .titulo p { margin: .8mm 0 0; font-size: 8.4pt; }

        /* Formulario de datos */
        .datos td { border: .75pt solid #000; padding: 1.1mm 2mm; vertical-align: top; }
        .datos .et { display: block; font-size: 6.4pt; text-transform: uppercase; color: #333; }
        .datos .va { display: block; font-size: 8.8pt; font-weight: 700; text-transform: uppercase; margin-top: .3mm; min-height: 3.6mm; }

        /* Tablas de contenido */
        .tabla { margin-top: 3.5mm; }
        .tabla th, .tabla td { border: .75pt solid #000; padding: .75mm 1.6mm; }
        .tabla th { background: #ececec; font-size: 7pt; font-weight: 700; text-transform: uppercase; text-align: center; line-height: 1.15; }
        .tabla td { font-size: 8.4pt; }
        .tabla td.no { text-align: center; width: 7mm; color: #333; }
        .tabla td.mat { text-transform: uppercase; }
        .tabla td.n { text-align: center; width: 15mm; font-variant-numeric: tabular-nums; }
        .tabla td.fin { font-weight: 700; }
        .tabla td.rep { font-weight: 700; color: #a11; }
        .tabla tr.total td { font-weight: 700; background: #f4f4f4; }
        .tabla tr.total td.et { text-align: right; text-transform: uppercase; font-size: 7.4pt; }
        .tabla td.vacio { text-align: center; color: #555; font-style: italic; padding: 3mm; }
        .equiv { text-align: center; font-size: 7.4pt; font-weight: 700; text-transform: uppercase; }
        .nota { font-size: 7pt; color: #333; margin-top: 1.2mm; }

        .dos-col { display: grid; grid-template-columns: 1.7fr 1fr; gap: 4mm; }
        .seccion { font-size: 7.6pt; font-weight: 700; text-transform: uppercase; margin: 4mm 0 0; }
        .seccion + .tabla { margin-top: 1.2mm; }

        /* Fechas de faltas, justificantes y retardos del parcial */
        .eventos { border: .75pt solid #000; margin-top: 1.2mm; font-size: 7.8pt; }
        .eventos div { padding: .8mm 2mm; border-bottom: .5pt solid #999; }
        .eventos div:last-child { border-bottom: none; }
        .eventos b { display: block; text-transform: uppercase; font-size: 6.6pt; color: #333; }

        .obs { border: .75pt solid #000; margin-top: 1.2mm; }
        .obs div { height: 6mm; border-bottom: .5pt solid #999; }
        .obs div:last-child { border-bottom: none; }
        .obs div.rep-ind { height: auto; min-height: 6mm; padding: .9mm 2mm; font-size: 7.8pt; overflow-wrap: anywhere; }
        .obs div.rep-ind b { font-size: 6.6pt; text-transform: uppercase; color: #333; margin-right: 1.5mm; }

        /* Firmas */
        .firmas { display: grid; grid-template-columns: 70mm 30mm; justify-content: center; gap: 8mm; align-items: end; margin-top: 6mm; }
        .firma { text-align: center; font-size: 7.6pt; }
        .firma .linea { border-top: .75pt solid #000; padding-top: 1mm; }
        .firma .nom { font-weight: 700; text-transform: uppercase; }
        .sello { height: 20mm; border: .5pt solid #888; display: flex; align-items: center; justify-content: center; font-size: 6.6pt; color: #777; text-transform: uppercase; }

        .pie { display: flex; justify-content: space-between; border-top: .5pt solid #000; margin-top: 4mm; padding-top: 1.2mm; font-size: 7pt; color: #333; }
    `;

    function membrete(al, ciclo) {
        return `
            <div class="membrete">
                <img src="${location.origin}/img/logo.png" alt="">
                <div class="membrete-centro">
                    <div class="membrete-dep">Dirección General de Educación Tecnológica Industrial y de Servicios</div>
                    <div class="membrete-esc">${esc(al.nombreCentroTrabajo || ESCUELA)}</div>
                    <div class="membrete-dom">C.C.T. ${esc(al.claveCentroTrabajo || CCT_DEFAULT)}</div>
                    <div class="membrete-dom">${DOMICILIO}</div>
                </div>
                <div class="membrete-ciclo"><div>Ciclo escolar</div><div>${esc(ciclo)}</div></div>
            </div>
            <div class="filete"></div>`;
    }

    function datosAlumno(al, grupo, turno, semestre) {
        const nombre = `${al.apPaterno || ''} ${al.apMaterno || ''} ${al.nombre || ''}`.replace(/\s+/g, ' ').trim();
        return `
            <table class="datos">
                <tr>
                    <td colspan="3"><span class="et">Nombre del alumno (apellido paterno, materno y nombre)</span><span class="va">${esc(nombre)}</span></td>
                    <td><span class="et">Matrícula</span><span class="va">${esc(al.numeroControl || '')}</span></td>
                </tr>
                <tr>
                    <td style="width: 34%;"><span class="et">CURP</span><span class="va">${esc(al.curp || '')}</span></td>
                    <td style="width: 22%;"><span class="et">Semestre</span><span class="va">${esc(semestre)}</span></td>
                    <td style="width: 22%;"><span class="et">Grupo</span><span class="va">${esc(grupo)}</span></td>
                    <td style="width: 22%;"><span class="et">Turno</span><span class="va">${esc(turno)}</span></td>
                </tr>
            </table>`;
    }

    // Semestres capturados con el formato anterior (un solo total por semestre en tb_calificaciones).
    // Solo se muestra en la boleta final; nunca se reparte entre parciales porque no se sabe a cuál corresponde.
    function asistenciaAnterior(materias, esFinal) {
        const tots = materias.map(m => num(m.total_asistencias)).filter(v => v !== null);
        if (!esFinal || !tots.length) return `<tr><td colspan="5" class="vacio">Sin asistencia capturada.</td></tr>`;
        const tot = Math.max(...tots);
        const asis = materias.map(m => num(m.asistencias)).filter(v => v !== null);
        const asi = asis.length ? Math.max(...asis) : 0;
        const c = x => `<td class="n">${x}</td>`;
        return `
            <tr><td>Clases impartidas</td><td></td><td></td><td></td>${c(tot)}</tr>
            <tr><td>Asistencias</td><td></td><td></td><td></td>${c(asi)}</tr>
            <tr><td>Faltas</td><td></td><td></td><td></td>${c(Math.max(0, tot - asi))}</tr>
            <tr class="total"><td class="et">Asistencia</td><td></td><td></td><td></td><td class="n fin">${pctTxt(asi, tot)}</td></tr>
            <tr><td colspan="5" class="vacio" style="padding:1mm;">Total del semestre capturado con el formato anterior.</td></tr>`;
    }

    // Asistencia de un parcial. Fuente: pase de lista diario dentro de las fechas del parcial
    // (días con pase, asistencias = A + R, retardos, faltas = F + J y justificadas).
    // Solo si el parcial no tiene pase de lista se usa la captura provisional de Captura de Calificaciones,
    // que no trae retardos ni justificantes (se dejan vacíos, no en cero).
    function asistenciaDeParcial(data, idNivel, k, pase) {
        const r = (pase && pase.resumen && pase.resumen.dias_registrados > 0) ? pase.resumen : null;
        if (r) {
            return {
                tot: r.dias_registrados,
                asi: r.asistencias,
                fal: r.faltas,
                ret: r.retardos,
                jus: r.faltas_justificadas,
                origen: 'pase',
            };
        }
        const d = (((data.asistenciasParciales || {})[String(idNivel)]) || {})[String(k)] || {};
        const tot = num(d.total_asistencias), asi = num(d.asistencias);
        return {
            tot, asi,
            fal: (tot !== null && asi !== null) ? Math.max(0, tot - asi) : null,
            ret: null,
            jus: null,
            origen: (tot !== null || asi !== null) ? 'manual' : null,
        };
    }

    // ---------------------------------------------------------------
    // Construcción del documento
    // ---------------------------------------------------------------
    function construir(data, idNivelSemestre, reportesCounts, opciones, pases, reportes = []) {
        const al = data.alumno || {};
        const periodo = (data.periodos || []).find(x => Number(x.idNivel) === Number(idNivelSemestre));
        const materias = periodo ? (periodo.materias || []) : [];
        const semestre = `${SEMESTRES[idNivelSemestre] || ''} semestre`;
        const esFinal = !['1', '2', '3'].includes(String(opciones.parcial));
        const n = esFinal ? 3 : parseInt(opciones.parcial, 10);   // último parcial que se llena
        const incAsist = opciones.incluirAsistencias !== false;
        const incRep = opciones.incluirReportes !== false;
        const rc = reportesCounts || {};
        const pase = pases[n] || null;   // pase del parcial que se entrega

        const paseRef = pases[1] || pases[2] || pases[3] || null;
        const inicioGrupo = paseRef && paseRef.grupo && paseRef.grupo.fechaInicio ? new Date(paseRef.grupo.fechaInicio + 'T00:00:00') : new Date();
        const ciclo = cicloDe(inicioGrupo);
        const grupo = (paseRef && paseRef.grupo && paseRef.grupo.clave) || al.nombreGrupoTexto || al.claveGrupo || '';
        const turno = (paseRef && paseRef.grupo && paseRef.grupo.modalidad) || al.modalidadHorario || '';
        const hoy = new Date();

        // ---- Calificaciones (se llenan hasta el parcial que se entrega) ----
        const cols = { p1: [], p2: [], p3: [], sem: [], fin: [] };
        let filas = '';
        materias.forEach((m, i) => {
            if (m.es_equivalencia === true) {
                filas += `<tr><td class="no">${i + 1}</td><td class="mat">${esc(m.nombreMateria)}</td>
                          <td colspan="6" class="equiv">Acreditada por equivalencia</td></tr>`;
                return;
            }
            const v = {
                p1: n >= 1 ? num(m.parcial1) : null,
                p2: n >= 2 ? num(m.parcial2) : null,
                p3: n >= 3 ? num(m.parcial3) : null,
                sem: esFinal ? num(m.semestral) : null,
                ext: esFinal ? num(m.extraordinario) : null,
                fin: esFinal ? num(m.calificacion) : null,
            };
            ['p1', 'p2', 'p3', 'sem', 'fin'].forEach(k => { if (v[k] !== null) cols[k].push(v[k]); });
            filas += `<tr>
                <td class="no">${i + 1}</td>
                <td class="mat">${esc(m.nombreMateria)}</td>
                ${celdaNota(v.p1)}${celdaNota(v.p2)}${celdaNota(v.p3)}${celdaNota(v.sem)}${celdaNota(v.ext)}${celdaNota(v.fin, ' fin')}
            </tr>`;
        });
        if (!materias.length) {
            filas = `<tr><td colspan="8" class="vacio">No hay asignaturas registradas para este semestre.</td></tr>`;
        }
        const prom = (k, extra = '') => { const p = promedio(cols[k]); return p === null ? `<td class="n${extra}"></td>` : celdaNota(Math.round(p * 10) / 10, extra); };

        // ---- Asistencia por parcial (hasta el que se entrega) ----
        let asistenciaHtml = '';
        if (incAsist) {
            const P = [1, 2, 3].map(k => k <= n ? asistenciaDeParcial(data, idNivelSemestre, k, pases[k]) : { tot: null, asi: null, fal: null, ret: null, jus: null });
            const suma = campo => { const vals = P.map(x => x[campo]).filter(x => x !== null && x !== undefined); return vals.length ? vals.reduce((a, b) => a + b, 0) : null; };
            const S = { tot: suma('tot'), asi: suma('asi'), fal: suma('fal'), ret: suma('ret'), jus: suma('jus') };
            const hay = P.some(x => x.tot !== null || x.asi !== null);
            const c = x => `<td class="n">${x === null || x === undefined ? '' : x}</td>`;
            const fila = (et, campo) => `<tr><td>${et}</td>${P.map(x => c(x[campo])).join('')}${c(S[campo])}</tr>`;
            const ORD = { 1: '1er', 2: '2do', 3: '3er' };
            const manuales = [1, 2, 3].filter(k => P[k - 1].origen === 'manual');
            const notaManual = manuales.length
                ? `<div class="nota">${manuales.map(k => ORD[k]).join(' y ')} parcial: sin pase de lista diario, se muestra la asistencia capturada manualmente.</div>`
                : '';
            asistenciaHtml = `
                <div class="seccion">Asistencia</div>
                <table class="tabla">
                    <tr><th style="text-align:left;">Concepto</th><th>1er parcial</th><th>2do parcial</th><th>3er parcial</th><th>${esFinal ? 'Semestre' : 'Acumulado'}</th></tr>
                    ${hay ? `
                    ${fila('Clases impartidas', 'tot')}
                    ${fila('Asistencias', 'asi')}
                    ${fila('Retardos', 'ret')}
                    ${fila('Faltas', 'fal')}
                    ${fila('Faltas justificadas', 'jus')}
                    <tr class="total"><td class="et">Asistencia</td>${P.map(x => `<td class="n fin">${pctTxt(x.asi, x.tot)}</td>`).join('')}<td class="n fin">${pctTxt(S.asi, S.tot)}</td></tr>
                    ` : asistenciaAnterior(materias, esFinal)}
                </table>
                ${hay ? notaManual : ''}`;
        }

        // ---- Conducta ----
        let conductaHtml = '';
        if (incRep) {
            const r = k => k <= n ? (rc[k] || 0) : '';
            const total = [1, 2, 3].filter(k => k <= n).reduce((a, k) => a + (rc[k] || 0), 0);
            conductaHtml = `
                <div class="seccion">Reportes de conducta</div>
                <table class="tabla">
                    <tr><th>1er parcial</th><th>2do parcial</th><th>3er parcial</th><th>Total</th></tr>
                    <tr><td class="n">${r(1)}</td><td class="n">${r(2)}</td><td class="n">${r(3)}</td><td class="n fin">${total}</td></tr>
                </table>`;
        }

        // ---- Fechas de faltas, justificantes y retardos del parcial que se entrega ----
        let eventosHtml = '';
        if (incAsist && !esFinal) {
            let cuerpo;
            if (!pase || !(pase.resumen && pase.resumen.dias_registrados > 0)) {
                cuerpo = `<div>Sin pase de lista diario registrado en este parcial.</div>`;
            } else {
                const hist = (pase.historial || []).slice().sort((a, b) => a.fecha.localeCompare(b.fecha));
                const justif = {};
                (pase.faltas || []).forEach(f => { justif[f.fecha] = f; });
                const de = e => hist.filter(h => h.estatus === e);
                const lista = arr => arr.length ? arr.map(h => fechaCorta(h.fecha)).join(', ') : 'Ninguna';
                const listaJ = arr => arr.length ? arr.map(h => {
                    const j = justif[h.fecha];
                    const det = [h.observaciones, j && j.fecha_justificacion ? `justificada el ${fechaCorta(j.fecha_justificacion)}` : ''].filter(Boolean).join(', ');
                    return `${fechaCorta(h.fecha)}${det ? ` (${esc(det)})` : ''}`;
                }).join('; ') : 'Ninguna';
                cuerpo = `
                    <div><b>Faltas</b>${lista(de('F'))}</div>
                    <div><b>Faltas justificadas</b>${listaJ(de('J'))}</div>
                    <div><b>Retardos</b>${lista(de('R')).replace('Ninguna', 'Ninguno')}</div>
                    ${hist.some(h => h.en_linea) ? `<div><b>Clases en línea</b>${lista(hist.filter(h => h.en_linea))}</div>` : ''}`;
            }
            eventosHtml = `
                <div class="seccion">Fechas del ${PARCIALES[n].toLowerCase()}</div>
                <div class="eventos">${cuerpo}</div>`;
        }

        // ---- Observaciones: reportes de indisciplina del parcial que se entrega (los tres en la final) ----
        let obsHtml = `<div></div><div></div>`;
        if (incRep) {
            const delParcial = (reportes || [])
                .filter(r => esFinal ? [1, 2, 3].includes(Number(r.parcial)) : Number(r.parcial) === n)
                .sort((a, b) => (Number(a.parcial) - Number(b.parcial)) || (ordenFecha(a.fecha) - ordenFecha(b.fecha)));
            if (delParcial.length) {
                const ORD = { 1: '1er', 2: '2do', 3: '3er' };
                obsHtml = delParcial.map(r => {
                    const et = [
                        esFinal ? `${ORD[Number(r.parcial)]} parcial` : '',
                        fechaReporte(r.fecha),
                        r.folio ? `Folio ${r.folio}` : '',
                    ].filter(Boolean).join(', ');
                    return `<div class="rep-ind"><b>Reporte de indisciplina${et ? `, ${esc(et)}` : ''}:</b>${esc(String(r.incidente || '').replace(/\s+/g, ' ').trim())}</div>`;
                }).join('') + `<div></div>`;
            }
        }

        // Periodo del parcial (Horarios > Fechas de Periodos de Evaluación)
        const pe = data.periodosEvaluacion || {};
        const periodoTxt = esFinal ? '' : ((pase && pase.rango && pase.rango.parcial)
            ? `del ${fechaCorta(pase.rango.parcial.inicio)} al ${fechaCorta(pase.rango.parcial.fin)}`
            : (pe[`p${n}_rango`] || ''));
        const subtitulo = esFinal
            ? `Final del ${semestre.toLowerCase()}`
            : `${PARCIALES[n]}, ${semestre.toLowerCase()}${periodoTxt ? `. Periodo ${periodoTxt}` : ''}`;

        const hoja = `
            ${membrete(al, ciclo)}
            <div class="titulo">
                <h1>Boleta de calificaciones</h1>
                <p>${esc(subtitulo)}</p>
            </div>
            ${datosAlumno(al, grupo, turno, semestre)}

            <table class="tabla">
                <thead>
                    <tr>
                        <th rowspan="2" style="width:7mm;">No.</th>
                        <th rowspan="2" style="text-align:left;">Asignatura</th>
                        <th colspan="4">Evaluación ordinaria</th>
                        <th rowspan="2">Extra&shy;ordinario</th>
                        <th rowspan="2">Calificación final</th>
                    </tr>
                    <tr>
                        <th>1er parcial</th><th>2do parcial</th><th>3er parcial</th><th>Semestral</th>
                    </tr>
                </thead>
                <tbody>
                    ${filas}
                    <tr class="total">
                        <td colspan="2" class="et">Promedio</td>
                        ${prom('p1')}${prom('p2')}${prom('p3')}${prom('sem')}<td class="n"></td>${prom('fin', ' fin')}
                    </tr>
                </tbody>
            </table>
            <div class="nota">Escala de calificación de 5 a 10. Calificación mínima aprobatoria: ${APROBATORIA}.</div>

            ${(asistenciaHtml || conductaHtml || eventosHtml) ? `
            <div class="dos-col">
                <div>${asistenciaHtml}</div>
                <div>${conductaHtml}${eventosHtml}</div>
            </div>` : ''}

            <div class="seccion">Observaciones</div>
            <div class="obs">${obsHtml}</div>

            <div class="firmas">
                <div class="firma"><div class="linea"><div class="nom">${esc(DIRECTOR)}</div>Director</div></div>
                <div class="sello">Sello</div>
            </div>

            <div class="pie">
                <span>Teziutlán, Puebla, a ${fechaLarga(hoy)}.</span>
                <span></span>
            </div>`;

        const nombreArchivo = `Boleta ${esFinal ? 'final' : PARCIALES[n].toLowerCase()} ${semestre} ${al.apPaterno || ''} ${al.nombre || ''}`.trim();
        return { hoja, titulo: nombreArchivo };
    }

    // Documento imprimible con una o varias boletas (una por hoja carta)
    function documento(hojas, titulo) {
        return `<!DOCTYPE html>
            <html lang="es"><head>
                <meta charset="UTF-8">
                <title>${esc(titulo)}</title>
                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;700&display=swap" rel="stylesheet">
                <style>${CSS}
                    .hoja { break-after: page; page-break-after: always; }
                    .hoja:last-child { break-after: auto; page-break-after: auto; }
                </style>
            </head><body>${hojas.map(h => `<div class="hoja">${h}</div>`).join('')}</body></html>`;
    }

    // Kárdex vigente del alumno (la boleta sale con lo último guardado)
    async function obtenerKardex(idAlumno) {
        const res = await fetch(`/alumnos/${encodeURIComponent(idAlumno)}/kardex`, { headers: { 'Accept': 'application/json' }, cache: 'no-store' });
        const fresco = await res.json().catch(() => null);
        const d = fresco && (fresco.data || fresco);
        return (res.ok && d && d.alumno && Array.isArray(d.periodos)) ? d : null;
    }

    async function obtenerConteoReportes(idAlumno) {
        try {
            const r = await fetch(`/alumnos/${encodeURIComponent(idAlumno)}/reportes-conteo`, { headers: { 'Accept': 'application/json' }, cache: 'no-store' });
            const d = await r.json();
            return d.success && d.counts ? d.counts : { 1: 0, 2: 0, 3: 0 };
        } catch (e) {
            return { 1: 0, 2: 0, 3: 0 };
        }
    }

    // Datos complementarios de la boleta (pase de lista de cada parcial y reportes) y hoja construida
    async function hojaDeAlumno(data, idNivelSemestre, reportesCounts, opciones) {
        const al = (data && data.alumno) || {};
        const parcial = String(opciones.parcial || 'FINAL').toUpperCase();
        const hasta = ['1', '2', '3'].includes(parcial) ? parseInt(parcial, 10) : 3;
        const pases = {};
        const reportesProm = opciones.incluirReportes !== false ? obtenerReportes(al.idAlumno) : Promise.resolve([]);
        if (opciones.incluirAsistencias !== false) {
            const ks = [1, 2, 3].filter(k => k <= hasta);
            const res = await Promise.all(ks.map(k => obtenerPaseParcial(al, idNivelSemestre, k)));
            ks.forEach((k, i) => { pases[k] = res[i]; });
        }
        const reportes = await reportesProm;
        return construir(data || {}, idNivelSemestre, reportesCounts, { ...opciones, parcial }, pases, reportes);
    }

    function lanzarImpresion(win) {
        win.focus();
        const imprimir = () => setTimeout(() => win.print(), 150);
        if (win.document.fonts && win.document.fonts.ready) {
            win.document.fonts.ready.then(imprimir).catch(imprimir);
        } else {
            setTimeout(imprimir, 500);
        }
    }

    window.imprimirBoletaBTISemestre = async function (data, idNivelSemestre, reportesCounts, opciones = {}) {
        // Abrir la ventana de inmediato (dentro del clic del usuario) para que no la bloquee el navegador
        const win = window.open('', '', 'height=900,width=900');
        if (!win) {
            if (window.Swal) Swal.fire({ icon: 'warning', title: 'Ventana bloqueada', text: 'Permite las ventanas emergentes para imprimir la boleta.' });
            return;
        }
        win.document.write('<p style="font-family: Arial, sans-serif; padding: 24px;">Generando boleta…</p>');

        // Volver a consultar el kárdex al imprimir: la pantalla pudo cargarlo antes de que se capturaran
        // asistencias o calificaciones, y la boleta debe salir con lo último guardado.
        const idAlumno = data && data.alumno && data.alumno.idAlumno;
        if (idAlumno) {
            try {
                const d = await obtenerKardex(idAlumno);
                // Conservar datos que la pantalla agrega al alumno (p. ej. texto del grupo)
                if (d) data = { ...data, ...d, alumno: { ...data.alumno, ...d.alumno } };
            } catch (e) { /* se imprime con los datos que ya tenía la pantalla */ }
        }

        // Pase de lista diario de cada parcial hasta el que se entrega (retardos, justificadas y fechas)
        const { hoja, titulo } = await hojaDeAlumno(data, idNivelSemestre, reportesCounts, opciones);

        win.document.open();
        win.document.write(documento([hoja], titulo));
        win.document.close();
        lanzarImpresion(win);
    };

    /**
     * Boletas de todo un grupo en un solo documento (una hoja por alumno, en el orden recibido).
     *   alumnos     → [{ idAlumno, ... }] del grupo
     *   grupo       → { clave } para el título del documento y el dato "Grupo" si el pase no lo trae
     *   onProgreso  → (hechos, total) opcional
     * Devuelve { generadas, fallidas: [nombres] }.
     */
    window.imprimirBoletasBTIGrupo = async function (alumnos, grupo, idNivelSemestre, opciones = {}, onProgreso = () => {}) {
        const win = window.open('', '', 'height=900,width=900');
        if (!win) {
            if (window.Swal) Swal.fire({ icon: 'warning', title: 'Ventana bloqueada', text: 'Permite las ventanas emergentes para imprimir las boletas.' });
            return null;
        }
        const total = alumnos.length;
        const aviso = (t) => { try { win.document.body.innerHTML = `<p style="font-family: Arial, sans-serif; padding: 24px;">${esc(t)}</p>`; } catch (e) {} };
        win.document.write('<body></body>');
        aviso(`Generando boletas del grupo ${grupo.clave || ''}: 0 de ${total}…`);

        const hojas = new Array(total).fill(null);
        const fallidas = [];
        let hechos = 0, siguiente = 0;
        const nombreDe = a => `${a.apPaterno || ''} ${a.apMaterno || ''} ${a.nombre || ''}`.replace(/\s+/g, ' ').trim() || `Alumno ${a.idAlumno}`;

        // Pocas consultas en paralelo para no saturar la API
        async function trabajador() {
            while (siguiente < total) {
                const i = siguiente++;
                const a = alumnos[i];
                try {
                    const [kardex, counts] = await Promise.all([
                        obtenerKardex(a.idAlumno),
                        opciones.incluirReportes !== false ? obtenerConteoReportes(a.idAlumno) : Promise.resolve({ 1: 0, 2: 0, 3: 0 }),
                    ]);
                    if (!kardex) throw new Error('sin kárdex');
                    if (!kardex.alumno.nombreGrupoTexto) kardex.alumno.nombreGrupoTexto = grupo.clave || '';
                    if (!kardex.alumno.idGrupo && grupo.id) kardex.alumno.idGrupo = grupo.id;
                    hojas[i] = (await hojaDeAlumno(kardex, idNivelSemestre, counts, opciones)).hoja;
                } catch (e) {
                    fallidas.push(nombreDe(a));
                }
                hechos++;
                onProgreso(hechos, total);
                aviso(`Generando boletas del grupo ${grupo.clave || ''}: ${hechos} de ${total}…`);
            }
        }
        await Promise.all(Array.from({ length: Math.min(4, total) }, trabajador));

        const listas = hojas.filter(Boolean);
        if (!listas.length) {
            win.close();
            return { generadas: 0, fallidas };
        }
        const parcial = String(opciones.parcial || 'FINAL').toUpperCase();
        const etiqueta = ['1', '2', '3'].includes(parcial) ? PARCIALES[parcial].toLowerCase() : 'final';
        const titulo = `Boletas ${etiqueta} ${SEMESTRES[idNivelSemestre] || ''} semestre grupo ${grupo.clave || ''}`.replace(/\s+/g, ' ').trim();

        win.document.open();
        win.document.write(documento(listas, titulo));
        win.document.close();
        lanzarImpresion(win);
        return { generadas: listas.length, fallidas };
    };

    // Para pruebas y vista previa sin abrir ventana
    window.__boletaBTI = { construir };
})();
</script>
