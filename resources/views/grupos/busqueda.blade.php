@extends('layouts.app')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="{{ asset('css/estilosGrupos.css') }}">

<div class="page-container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-regresar">
            <i class="fa-solid fa-arrow-left me-2"></i>
            Regresar
        </a>

        <h3 class="page-title mb-0">
            <i class="fa-solid fa-magnifying-glass me-2"></i>
            Búsqueda de Grupo (Filtro por Horario)
        </h3>

        <div style="width: 100px;"></div> {{-- Spacer --}}
    </div>

    <div class="glass-card mb-4">
        <div class="glass-header p-4" style="border-bottom: 1px solid rgba(0,0,0,0.05);">
            <h5 class="mb-3 fw-bold" style="color: #334155; font-size: 1.1rem;">
                <i class="fa-solid fa-sliders me-2 text-info"></i> Indique las preferencias del prospecto
            </h5>
            <div class="row g-3">
                <!-- CCT -->
                <div class="col-md-3">
                    <label class="form-label mb-1" style="font-size:0.75rem; font-weight:600; color:#334155;">CCT / Bachillerato</label>
                    <select id="filtroCCT" class="form-select form-select-premium w-100" style="min-height:38px; font-size:0.85rem; padding: 0.35rem 0.75rem;">
                        <option value="">Cualquier escuela (Todos)</option>
                        <option value="3">BGNE (General No Escolarizado)</option>
                        <option value="2">BTI (Tecnológico Interamericano)</option>
                        <option value="1">INF. Y COMP. (Informática y Computación)</option>
                    </select>
                </div>
                <!-- Día de Clase -->
                <div class="col-md-3">
                    <label class="form-label mb-1" style="font-size:0.75rem; font-weight:600; color:#334155;">Día de Preferencia</label>
                    <select id="filtroDia" class="form-select form-select-premium w-100" style="min-height:38px; font-size:0.85rem; padding: 0.35rem 0.75rem;">
                        <option value="">Cualquier día (Todos)</option>
                        <option value="SABADO">Sábados</option>
                        <option value="DOMINGO">Domingos</option>
                        <option value="LUNES-VIERNES">Lunes a Viernes</option>
                    </select>
                </div>
                <!-- Turno / Horario -->
                <div class="col-md-3">
                    <label class="form-label mb-1" style="font-size:0.75rem; font-weight:600; color:#334155;">Turno / Horario</label>
                    <select id="filtroTurno" class="form-select form-select-premium w-100" style="min-height:38px; font-size:0.85rem; padding: 0.35rem 0.75rem;">
                        <option value="">Cualquier turno (Todos)</option>
                        <option value="MATUTINO">Matutino (Mañana)</option>
                        <option value="VESPERTINO">Vespertino (Tarde)</option>
                        <option value="SABATINO">Sabatino (Sábados completo)</option>
                        <option value="DOMINICAL">Dominical (Domingos completo)</option>
                    </select>
                </div>
                <!-- Nivel / Trimestre / Semestre -->
                <div class="col-md-3">
                    <label class="form-label mb-1" style="font-size:0.75rem; font-weight:600; color:#334155;">Trimestre o Semestre de Interés</label>
                    <select id="filtroNivel" class="form-select form-select-premium w-100" style="min-height:38px; font-size:0.85rem; padding: 0.35rem 0.75rem;">
                        <option value="">Cualquier nivel (Todos)</option>
                        @foreach($niveles as $nivel)
                            <option value="{{ $nivel->id }}">{{ $nivel->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="p-4 pt-0">
            <div id="alertSugerencia" style="display:none;"></div>

            <div id="loading" class="text-center py-5" style="display:none;">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
                <div class="text-muted mt-2 fw-semibold">Buscando opciones disponibles...</div>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless glass-table align-middle mb-0" id="tablaResultados" style="display:none;">
                    <thead>
                        <tr>
                            <th class="ps-4">Clave</th>
                            <th>Bachillerato / Carrera</th>
                            <th>Nivel Actual</th>
                            <th>Días</th>
                            <th>Turno</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th class="text-center">Alumnos Activos</th>
                            <th class="text-center">Estatus</th>
                        </tr>
                    </thead>
                    <tbody id="resultadosBody">
                        <!-- Resultados cargados vía JS -->
                    </tbody>
                </table>
            </div>

            <div id="sinResultados" class="text-center py-5 text-muted">
                <i class="fa-solid fa-circle-info fa-3x mb-3 text-info"></i>
                <h5 class="fw-bold" style="color: #334155;">Realiza una búsqueda</h5>
                <p class="text-muted px-3">Modifica los filtros arriba para mostrar las opciones de grupos y periodos que mejor se acomoden al alumno.</p>
            </div>
        </div>
    </div>
</div>

<script>
// Helper para formatear fechas de manera segura y evitar desfasamientos horarios
function formatFriendlyDate(dateStr) {
    if (!dateStr) return 'N/A';
    try {
        const date = new Date(dateStr);
        if (isNaN(date.getTime())) {
            return dateStr;
        }
        const isGMT = dateStr.includes('GMT') || dateStr.includes('UTC') || dateStr.includes('Z');
        const yyyy = isGMT ? date.getUTCFullYear() : date.getFullYear();
        const mm = String((isGMT ? date.getUTCMonth() : date.getMonth()) + 1).padStart(2, '0');
        const dd = String(isGMT ? date.getUTCDate() : date.getDate()).padStart(2, '0');
        return `${dd}/${mm}/${yyyy}`; // Formato: día/mes/año
    } catch (e) {
        return dateStr;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const filtroCCT = document.getElementById('filtroCCT');
    const filtroDia = document.getElementById('filtroDia');
    const filtroTurno = document.getElementById('filtroTurno');
    const filtroNivel = document.getElementById('filtroNivel');
    
    const loading = document.getElementById('loading');
    const tablaResultados = document.getElementById('tablaResultados');
    const resultadosBody = document.getElementById('resultadosBody');
    const sinResultados = document.getElementById('sinResultados');

    // Lógica para condicionar dinámicamente las opciones de los filtros
    function updateFilterOptions() {
        const cct = filtroCCT.value;
        const dia = filtroDia.value;

        // 1. Mostrar/Ocultar "Lunes a Viernes", Sábado y Domingo en días según CCT
        const optLV = filtroDia.querySelector('option[value="LUNES-VIERNES"]');
        const optSab = filtroDia.querySelector('option[value="SABADO"]');
        const optDom = filtroDia.querySelector('option[value="DOMINGO"]');

        if (cct === '3') { // BGNE (Solo Fin de Semana)
            if (optLV) optLV.style.display = 'none';
            if (optSab) optSab.style.display = 'block';
            if (optDom) optDom.style.display = 'block';
            
            if (filtroDia.value === 'LUNES-VIERNES') {
                filtroDia.value = '';
            }
        } else if (cct === '2') { // BTI (Solo entre semana)
            if (optLV) optLV.style.display = 'block';
            if (optSab) optSab.style.display = 'none';
            if (optDom) optDom.style.display = 'none';
            
            if (filtroDia.value === 'SABADO' || filtroDia.value === 'DOMINGO') {
                filtroDia.value = 'LUNES-VIERNES';
            }
        } else { // Todos
            if (optLV) optLV.style.display = 'block';
            if (optSab) optSab.style.display = 'block';
            if (optDom) optDom.style.display = 'block';
        }

        // 2. Mostrar/Ocultar Turnos según CCT y Día
        const optSabatino = filtroTurno.querySelector('option[value="SABATINO"]');
        const optDominical = filtroTurno.querySelector('option[value="DOMINICAL"]');
        const optMatutino = filtroTurno.querySelector('option[value="MATUTINO"]');
        const optVespertino = filtroTurno.querySelector('option[value="VESPERTINO"]');

        if (optSabatino) optSabatino.style.display = 'block';
        if (optDominical) optDominical.style.display = 'block';
        if (optMatutino) optMatutino.style.display = 'block';
        if (optVespertino) optVespertino.style.display = 'block';

        if (cct === '3') { // BGNE
            if (dia === 'SABADO') {
                if (optDominical) optDominical.style.display = 'none';
                if (filtroTurno.value === 'DOMINICAL') filtroTurno.value = '';
            } else if (dia === 'DOMINGO') {
                if (optSabatino) optSabatino.style.display = 'none';
                if (filtroTurno.value === 'SABATINO') filtroTurno.value = '';
            }
        } else if (cct === '2') { // BTI (Solo entre semana)
            if (optSabatino) optSabatino.style.display = 'none';
            if (optDominical) optDominical.style.display = 'none';
            if (filtroTurno.value === 'SABATINO' || filtroTurno.value === 'DOMINICAL') {
                filtroTurno.value = '';
            }
        }

        // 3. Mostrar/Ocultar Trimestres vs Semestres según CCT
        const opcionesNivel = filtroNivel.querySelectorAll('option');
        opcionesNivel.forEach(opt => {
            const val = opt.value;
            if (!val) return;

            const texto = opt.text.toLowerCase();
            if (cct === '3') { // BGNE -> Solo Trimestres
                if (texto.includes('semestre')) {
                    opt.style.display = 'none';
                    if (filtroNivel.value === val) filtroNivel.value = '';
                } else {
                    opt.style.display = 'block';
                }
            } else if (cct === '2') { // BTI -> Solo Semestres
                if (texto.includes('trimestre')) {
                    opt.style.display = 'none';
                    if (filtroNivel.value === val) filtroNivel.value = '';
                } else {
                    opt.style.display = 'block';
                }
            } else {
                opt.style.display = 'block';
            }
        });
    }

    // Escuchar cambios para actualización de dependencias y búsquedas en tiempo real
    filtroCCT.addEventListener('change', function() {
        updateFilterOptions();
        buscarGrupos();
    });
    filtroDia.addEventListener('change', function() {
        updateFilterOptions();
        buscarGrupos();
    });
    filtroTurno.addEventListener('change', buscarGrupos);
    filtroNivel.addEventListener('change', buscarGrupos);

    // Inicializar lógica de visibilidad de filtros al cargar
    updateFilterOptions();

    function buscarGrupos() {
        const cct = filtroCCT.value;
        const dia = filtroDia.value;
        const turno = filtroTurno.value;
        const nivel = filtroNivel.value;

        // Si todos están vacíos, mostrar mensaje inicial
        if (!cct && !dia && !turno && !nivel) {
            document.getElementById('alertSugerencia').style.display = 'none';
            tablaResultados.style.display = 'none';
            sinResultados.style.display = 'block';
            sinResultados.innerHTML = `
                <i class="fa-solid fa-circle-info fa-3x mb-3 text-info"></i>
                <h5 class="fw-bold" style="color: #334155;">Realiza una búsqueda</h5>
                <p class="text-muted px-3">Modifica los filtros arriba para mostrar las opciones de grupos y periodos que mejor se acomoden al alumno.</p>
            `;
            resultadosBody.innerHTML = '';
            return;
        }

        loading.style.display = 'block';
        tablaResultados.style.display = 'none';
        sinResultados.style.display = 'none';
        document.getElementById('alertSugerencia').style.display = 'none';

        // Armar query params
        const params = new URLSearchParams();
        if (cct) params.append('id_centro_trabajo', cct);
        if (dia) params.append('dia', dia);
        if (turno) params.append('modalidad_horario', turno);
        if (nivel) params.append('id_nivel_academico', nivel);
        params.append('limit', 100);

        fetch(`/grupos/lista?${params.toString()}`)
            .then(res => res.json())
            .then(resp => {
                const grupos = resp.data || [];

                if (grupos.length > 0) {
                    loading.style.display = 'none';
                    renderTablaResultados(grupos, false);
                } else {
                    // Si no hay grupos en el nivel seleccionado, intentar buscar el nivel anterior (progresión)
                    const nivelId = parseInt(nivel);
                    let nivelAnteriorId = null;

                    if (nivelId >= 2 && nivelId <= 6) {
                        nivelAnteriorId = nivelId - 1;
                    } else if (nivelId >= 8 && nivelId <= 12) {
                        nivelAnteriorId = nivelId - 1;
                    }

                    if (nivelAnteriorId) {
                        const prevParams = new URLSearchParams(params);
                        prevParams.set('id_nivel_academico', nivelAnteriorId);

                        fetch(`/grupos/lista?${prevParams.toString()}`)
                            .then(r => r.json())
                            .then(prevResp => {
                                loading.style.display = 'none';
                                const prevGrupos = prevResp.data || [];
                                
                                if (prevGrupos.length > 0) {
                                    renderTablaResultados(prevGrupos, true, nivelId);
                                } else {
                                    mostrarSinResultados();
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                loading.style.display = 'none';
                                mostrarError();
                            });
                    } else {
                        loading.style.display = 'none';
                        mostrarSinResultados();
                    }
                }
            })
            .catch(err => {
                console.error(err);
                loading.style.display = 'none';
                mostrarError();
            });
    }

    function renderTablaResultados(grupos, esSugerencia = false, nivelBuscadoId = null) {
        resultadosBody.innerHTML = '';
        const alertSugerencia = document.getElementById('alertSugerencia');

        if (esSugerencia) {
            const optNivelBuscado = filtroNivel.querySelector(`option[value="${nivelBuscadoId}"]`);
            const nombreNivelBuscado = optNivelBuscado ? optNivelBuscado.text : 'el nivel solicitado';
            
            alertSugerencia.innerHTML = `
                <div class="alert alert-warning mb-3 d-flex align-items-center shadow-sm" role="alert" style="border-radius: 10px; border-left: 5px solid #ffc107; background: #fffdf5; padding: 12px 20px;">
                    <i class="fa-solid fa-circle-exclamation me-3 fa-lg text-warning"></i>
                    <div class="text-dark" style="font-size: 0.9rem;">
                        <strong>Sugerencia de Próximo Ingreso:</strong> No hay grupos activos cursando actualmente <strong>${nombreNivelBuscado}</strong>.
                        Se muestran a continuación los grupos del nivel anterior que avanzarán a este nivel y las fechas estimadas para su inicio.
                    </div>
                </div>
            `;
            alertSugerencia.style.display = 'block';
        } else {
            alertSugerencia.style.display = 'none';
        }

        grupos.forEach(g => {
            const diasStr = Array.isArray(g.diasClase) ? g.diasClase.join(', ') : (g.diasClase || 'No especificado');
            const statusBadge = g.statusGrupo === 'ACTIVO' 
                ? '<span class="badge bg-success text-white px-2.5 py-1">ACTIVO</span>' 
                : `<span class="badge bg-secondary text-white px-2.5 py-1">${g.statusGrupo || 'INACTIVO'}</span>`;

            let fechaInicioStr = g.fechaInicioNivel || g.fechaInicio;
            let fechaFinStr = g.fechaFinNivel || g.fechaFin;
            let badgeNivel = `<span class="badge bg-light text-dark fw-bold border" style="font-size: 0.8rem;">${g.nombre_nivel || 'N/A'}</span>`;

            if (esSugerencia) {
                // Calcular las fechas estimadas del siguiente nivel
                if (fechaFinStr) {
                    const currentEnd = new Date(fechaFinStr);
                    // El siguiente nivel inicia 7 días después
                    const nextStart = new Date(currentEnd.getTime() + 7 * 24 * 60 * 60 * 1000);
                    // Dura 13 semanas (12 * 7 dias inclusive)
                    const nextEnd = new Date(nextStart.getTime() + 12 * 7 * 24 * 60 * 60 * 1000);

                    fechaInicioStr = nextStart.toISOString();
                    fechaFinStr = nextEnd.toISOString();
                }

                badgeNivel = `
                    <div class="d-flex flex-column gap-1 align-items-start">
                        <span class="badge bg-light text-dark fw-bold border" style="font-size: 0.75rem;">${g.nombre_nivel || 'N/A'}</span>
                        <span class="badge bg-warning text-dark fw-bold" style="font-size: 0.7rem;"><i class="fa-solid fa-forward me-1"></i> Siguiente nivel</span>
                    </div>
                `;
            }

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="ps-4 fw-bold" style="color: rgb(7, 101, 136);">${g.clave}</td>
                <td class="fw-semibold text-dark">${g.nombreCentroTrabajo || 'N/A'}</td>
                <td>${badgeNivel}</td>
                <td class="text-secondary"><i class="fa-regular fa-calendar me-1"></i> ${diasStr}</td>
                <td class="text-secondary"><i class="fa-regular fa-clock me-1"></i> ${g.modalidadHorario || 'N/A'}</td>
                <td class="text-secondary fw-semibold">${formatFriendlyDate(fechaInicioStr)}</td>
                <td class="text-secondary fw-semibold">${formatFriendlyDate(fechaFinStr)}</td>
                <td class="text-center fw-bold text-dark"><i class="fa-solid fa-users me-1 text-primary"></i> ${g.alumnos_count || 0}</td>
                <td class="text-center">${statusBadge}</td>
            `;
            resultadosBody.appendChild(tr);
        });

        tablaResultados.style.display = 'table';
    }

    function mostrarSinResultados() {
        tablaResultados.style.display = 'none';
        sinResultados.style.display = 'block';
        sinResultados.innerHTML = `
            <i class="fa-solid fa-triangle-exclamation fa-3x mb-3 text-warning"></i>
            <h5 class="fw-bold" style="color: #334155;">No hay opciones disponibles</h5>
            <p class="text-muted">No se encontraron grupos activos ni próximos ingresos que coincidan con estos criterios de horario y nivel.</p>
        `;
    }

    function mostrarError() {
        tablaResultados.style.display = 'none';
        sinResultados.style.display = 'block';
        sinResultados.innerHTML = `
            <i class="fa-solid fa-circle-exclamation fa-3x mb-3 text-danger"></i>
            <h5 class="fw-bold text-danger">Error de carga</h5>
            <p class="text-muted">Ocurrió un error al consultar el listado de grupos.</p>
        `;
    }
});
</script>

@endsection
