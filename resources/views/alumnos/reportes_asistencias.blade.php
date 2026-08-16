@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- TomSelect -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<div class="page-container" style="min-height: 80vh;">
    <!-- Encabezado de Página -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="page-title mb-1">
                <i class="fa-solid fa-chart-pie me-2"></i>Reportes de Asistencia
            </h3>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Consulta porcentajes de asistencia por asignatura e historial detallado por alumno.</p>
        </div>
    </div>

    <!-- Barra de Selección de Grupo -->
    <div class="card border-0 mb-4 shadow-sm" style="border-radius: 16px; background: rgba(255, 255, 255, 0.25); border: 1px solid rgba(49, 125, 146, 0.12) !important;">
        <div class="card-body p-4 d-flex flex-column flex-md-row align-items-center gap-3">
            <div class="d-flex align-items-center gap-2 flex-grow-1 w-100">
                <label class="text-muted fw-semibold mb-0" style="font-size: 0.88rem; min-width: 140px;">Seleccionar Grupo:</label>
                <div class="flex-grow-1">
                    <select id="select-grupo" class="form-select border-0" onchange="cargarReportesGrupo(this.value)">
                        <option value="">-- Seleccione un grupo activo --</option>
                        @foreach($grupos as $g)
                            <option value="{{ $g['id'] }}">
                                {{ $g['clave'] }} - {{ $g['nombreCentroTrabajo'] ?? ($g['id_centroTrabajo'] == 2 ? 'BTI' : 'BGNE') }} ({{ $g['modalidadHorario'] ?? '' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido del Reporte (Se muestra al seleccionar un grupo) -->
    <div id="seccion-reporte" class="d-none">
        
        <!-- Pestañas del Reporte -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <ul class="nav nav-pills border-0 p-1" style="border-radius: 12px; gap: 4px; background: rgba(0, 0, 0, 0.06); border: 1px solid rgba(49, 125, 146, 0.1) !important;">
                <li class="nav-item">
                    <button class="nav-link active px-4 py-2 border-0 rounded-3 fw-medium" id="tab-materias" onclick="alternarTab('MATERIAS')" style="font-size: 0.82rem; transition: 0.2s;">
                        <i class="fa-solid fa-book me-1"></i>Porcentaje por Asignatura
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link px-4 py-2 border-0 rounded-3 fw-medium" id="tab-alumnos" onclick="alternarTab('ALUMNOS')" style="font-size: 0.82rem; transition: 0.2s;">
                        <i class="fa-solid fa-user-graduate me-1"></i>Historial por Alumno
                    </button>
                </li>
            </ul>
        </div>

        <!-- VISTA 1: PORCENTAJE POR ASIGNATURA -->
        <div id="vista-materias">
            <div class="row g-4" id="grid-materias">
                <!-- Renderizado dinámicamente con JS -->
            </div>
        </div>

        <!-- VISTA 2: HISTORIAL POR ALUMNO -->
        <div id="vista-alumnos" class="d-none">
            <!-- Barra de Selección de Alumno -->
            <div class="card border-0 mb-4 shadow-sm" style="border-radius: 16px; background: rgba(255, 255, 255, 0.25); border: 1px solid rgba(49, 125, 146, 0.12) !important;">
                <div class="card-body p-4 d-flex flex-column flex-md-row align-items-center gap-3">
                    <div class="d-flex align-items-center gap-2 flex-grow-1 w-100">
                        <label class="text-muted fw-semibold mb-0" style="font-size: 0.88rem; min-width: 140px;">Seleccionar Alumno:</label>
                        <div class="flex-grow-1">
                            <select id="select-alumno" class="form-select border-0" onchange="cargarHistorialAlumno(this.value)">
                                <option value="">-- Seleccione un alumno --</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline de Historial -->
            <div id="historial-timeline-container">
                <!-- Renderizado dinámicamente con JS -->
                <div class="text-center py-5 text-muted" id="mensaje-vacio-alumno">
                    <i class="fa-solid fa-arrow-pointer mb-3" style="font-size: 2.5rem; color: rgba(49, 125, 146, 0.4);"></i>
                    <h5>Selecciona un alumno para consultar su historial de asistencia</h5>
                </div>
            </div>
        </div>

    </div>

    <!-- Estado Inicial Vacío -->
    <div class="text-center py-5 text-muted" id="mensaje-vacio-grupo">
        <i class="fa-solid fa-chart-line mb-3" style="font-size: 3.5rem; color: rgba(49, 125, 146, 0.3);"></i>
        <h4 class="text-dark">Consulta de Asistencias</h4>
        <p class="text-muted small">Selecciona un grupo del dropdown superior para comenzar a visualizar los reportes estadísticos.</p>
    </div>
</div>

<style>
    /* Estilo Premium de pestañas */
    .nav-pills .nav-link {
        color: #64748b;
        background: transparent;
    }
    .nav-pills .nav-link.active {
        background-color: rgb(49, 125, 146) !important;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(49, 125, 146, 0.25);
    }
    
    /* Card Premium */
    .card-materia-reporte {
        border-radius: 20px; 
        background: rgba(255, 255, 255, 0.65); 
        border: 1px solid rgba(49, 125, 146, 0.18) !important; 
        backdrop-filter: blur(12px); 
        transition: transform 0.25s, box-shadow 0.25s;
    }
    .card-materia-reporte:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(49, 125, 146, 0.15) !important;
    }

    /* Anillo de Progreso SVG */
    .progress-ring {
        transform: rotate(-90deg);
        transform-origin: 50% 50%;
    }
    .progress-ring__circle {
        stroke-dasharray: 157;
        stroke-dashoffset: 157;
        transition: stroke-dashoffset 0.35s;
    }

    /* Badges de Asistencia */
    .badge-asist-status {
        font-size: 0.68rem;
        font-weight: 700;
        padding: 5px 10px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        letter-spacing: 0.2px;
        border: 1px solid transparent;
    }
    .badge-status-A {
        background-color: #dcfce7;
        color: #166534;
        border-color: #bbf7d0;
    }
    .badge-status-F {
        background-color: #fee2e2;
        color: #991b1b;
        border-color: #fecaca;
    }
    .badge-status-R {
        background-color: #fef9c3;
        color: #854d0e;
        border-color: #fef08a;
    }
    .badge-status-J {
        background-color: #ccfbf1;
        color: #0f766e;
        border-color: #99f6e4;
    }
    .badge-status-SIN_REGISTRO {
        background-color: #f1f5f9;
        color: #64748b;
        border-color: #cbd5e1;
    }

    /* Timeline Styles */
    .timeline-day {
        position: relative;
        padding-left: 24px;
        border-left: 2px solid rgba(49, 125, 146, 0.2);
        margin-bottom: 25px;
    }
    .timeline-day::before {
        content: '';
        position: absolute;
        left: -7px;
        top: 6px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: rgb(49, 125, 146);
        border: 2px solid white;
    }
</style>

<script>
    let activeGroupId = null;
    let selectedTab = 'MATERIAS'; // 'MATERIAS' o 'ALUMNOS'
    let tomSelectGrupo = null;
    let tomSelectAlumno = null;

    document.addEventListener("DOMContentLoaded", function() {
        tomSelectGrupo = new TomSelect("#select-grupo", {
            create: false,
            placeholder: "Buscar y seleccionar grupo..."
        });
        
        tomSelectAlumno = new TomSelect("#select-alumno", {
            create: false,
            placeholder: "Seleccione un alumno del grupo..."
        });
    });

    function alternarTab(tab) {
        selectedTab = tab;
        
        document.getElementById('tab-materias').classList.remove('active');
        document.getElementById('tab-alumnos').classList.remove('active');
        
        document.getElementById('vista-materias').classList.add('d-none');
        document.getElementById('vista-alumnos').classList.add('d-none');
        
        if (tab === 'MATERIAS') {
            document.getElementById('tab-materias').classList.add('active');
            document.getElementById('vista-materias').classList.remove('d-none');
        } else {
            document.getElementById('tab-alumnos').classList.add('active');
            document.getElementById('vista-alumnos').classList.remove('d-none');
        }
    }

    function cargarReportesGrupo(idGrupo) {
        if (!idGrupo) {
            activeGroupId = null;
            document.getElementById('seccion-reporte').classList.add('d-none');
            document.getElementById('mensaje-vacio-grupo').classList.remove('d-none');
            return;
        }

        activeGroupId = idGrupo;
        Swal.fire({
            title: 'Cargando reportes...',
            text: 'Obteniendo estadísticas de asistencia del grupo',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(`/reportes/asistencias/grupo/${idGrupo}`)
            .then(res => res.json())
            .then(data => {
                Swal.close();
                if (data.error) {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.error });
                    return;
                }

                document.getElementById('mensaje-vacio-grupo').classList.add('d-none');
                document.getElementById('seccion-reporte').classList.remove('d-none');

                // Renderizar materias
                renderizarMateriasReporte(data.materias_reporte);

                // Poblar select de alumnos
                poblarSelectAlumnos(data.alumnos);
            })
            .catch(err => {
                Swal.close();
                console.error(err);
                Swal.fire({ icon: 'error', title: 'Error de Red', text: 'No se pudieron cargar las estadísticas del grupo.' });
            });
    }

    function renderizarMateriasReporte(materias) {
        const grid = document.getElementById('grid-materias');
        grid.innerHTML = '';

        if (!materias || materias.length === 0) {
            grid.innerHTML = '<div class="col-12 text-center text-muted py-5">No hay asignaturas registradas en el horario de este grupo.</div>';
            return;
        }

        materias.forEach(mat => {
            const pct = mat.porcentaje;
            const pctText = pct !== null ? `${pct}%` : 'N/A';
            const progressColor = pct === null ? '#cbd5e1' : (pct < 70 ? '#dc2626' : (pct < 85 ? '#eab308' : 'rgb(49, 125, 146)'));
            
            // Círculo de progreso SVG
            const strokeDash = pct !== null ? Math.round(157 - (157 * pct) / 100) : 157;

            // Tarjeta de materia
            const col = document.createElement('div');
            col.className = 'col-md-6 col-lg-4';
            col.innerHTML = `
                <div class="card card-materia-reporte border-0 shadow-sm h-100">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="text-dark fw-bold mb-0 flex-grow-1" style="font-size: 1.1rem; line-height: 1.4;">${mat.nombreMateria}</h5>
                                <div class="position-relative ms-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <svg class="progress-ring" width="60" height="60">
                                        <circle class="progress-ring__circle" stroke="#e2e8f0" stroke-width="5" fill="transparent" r="25" cx="30" cy="30" />
                                        <circle class="progress-ring__circle" stroke="${progressColor}" stroke-dasharray="157" stroke-dashoffset="${strokeDash}" stroke-width="5" fill="transparent" r="25" cx="30" cy="30" />
                                    </svg>
                                    <span class="position-absolute fw-bold text-dark" style="font-size: 0.78rem;">${pctText}</span>
                                </div>
                            </div>
                            <small class="text-muted d-block" style="font-size: 0.72rem; margin-top: -8px;">Clave: ${mat.clave}</small>
                            
                            <div class="d-flex align-items-center mt-3 text-dark">
                                <i class="fa-solid fa-user-tie me-2" style="width: 14px; color: rgb(49, 125, 146);"></i>
                                <span class="fw-semibold" style="font-size: 0.78rem; text-transform: uppercase;">${mat.docente_nombre}</span>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top" style="border-color: rgba(49,125,146,0.1) !important;">
                            <span class="text-muted d-block uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">ESTADÍSTICAS</span>
                            <div class="row g-2 mt-1 text-center" style="font-size: 0.75rem;">
                                <div class="col-3">
                                    <div class="bg-light p-1 rounded">
                                        <div class="text-success fw-bold">${mat.asistencias}</div>
                                        <small class="text-muted" style="font-size: 0.58rem;">Asist</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="bg-light p-1 rounded">
                                        <div class="text-danger fw-bold">${mat.faltas}</div>
                                        <small class="text-muted" style="font-size: 0.58rem;">Faltas</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="bg-light p-1 rounded">
                                        <div class="text-warning fw-bold">${mat.retardos}</div>
                                        <small class="text-muted" style="font-size: 0.58rem;">Retardos</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="bg-light p-1 rounded">
                                        <div class="text-info fw-bold">${mat.justificadas}</div>
                                        <small class="text-muted" style="font-size: 0.58rem;">Justif</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            grid.appendChild(col);
        });
    }

    function poblarSelectAlumnos(alumnos) {
        if (tomSelectAlumno) {
            tomSelectAlumno.clearOptions();
            tomSelectAlumno.addOption({ value: '', text: '-- Seleccione un alumno --' });
            alumnos.forEach(al => {
                const fullName = `${al.apPaterno} ${al.apMaterno || ''} ${al.nombre} (${al.numeroControl || 'S/M'})`.toUpperCase();
                tomSelectAlumno.addOption({ value: al.idAlumno, text: fullName });
            });
            tomSelectAlumno.setValue('');
        }
        
        // Limpiar el historial anterior al cambiar de grupo
        document.getElementById('historial-timeline-container').innerHTML = `
            <div class="text-center py-5 text-muted" id="mensaje-vacio-alumno">
                <i class="fa-solid fa-arrow-pointer mb-3" style="font-size: 2.5rem; color: rgba(49, 125, 146, 0.4);"></i>
                <h5>Selecciona un alumno para consultar su historial de asistencia</h5>
            </div>
        `;
    }

    function cargarHistorialAlumno(idAlumno) {
        if (!idAlumno || !activeGroupId) {
            document.getElementById('historial-timeline-container').innerHTML = `
                <div class="text-center py-5 text-muted" id="mensaje-vacio-alumno">
                    <i class="fa-solid fa-arrow-pointer mb-3" style="font-size: 2.5rem; color: rgba(49, 125, 146, 0.4);"></i>
                    <h5>Selecciona un alumno para consultar su historial de asistencia</h5>
                </div>
            `;
            return;
        }

        Swal.fire({
            title: 'Cargando historial...',
            text: 'Obteniendo registros detallados del alumno',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(`/reportes/asistencias/grupo/${activeGroupId}/alumno/${idAlumno}`)
            .then(res => res.json())
            .then(data => {
                Swal.close();
                if (data.error) {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.error });
                    return;
                }

                renderizarHistorialTimeline(data.historial, data.alumno);
            })
            .catch(err => {
                Swal.close();
                console.error(err);
                Swal.fire({ icon: 'error', title: 'Error de Red', text: 'No se pudo cargar el historial del alumno.' });
            });
    }

    function renderizarHistorialTimeline(historial, alumno) {
        const container = document.getElementById('historial-timeline-container');
        container.innerHTML = '';

        if (!historial || historial.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-calendar-xmark mb-3" style="font-size: 2.5rem; color: rgba(49, 125, 146, 0.4);"></i>
                    <h5>Sin registros de asistencia</h5>
                    <p class="small text-muted">No se han registrado pases de lista para este alumno en el grupo seleccionado.</p>
                </div>
            `;
            return;
        }

        // Header del alumno
        const headerCard = document.createElement('div');
        headerCard.className = 'card border-0 mb-4 shadow-sm';
        headerCard.style = 'border-radius: 16px; background-color: rgba(49, 125, 146, 0.05);';
        headerCard.innerHTML = `
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="text-dark fw-bold mb-1 uppercase" style="letter-spacing: -0.3px;">${alumno.nombreCompleto}</h5>
                    <small class="text-muted fw-semibold">MATRÍCULA: ${alumno.numeroControl || 'N/A'}</small>
                </div>
            </div>
        `;
        container.appendChild(headerCard);

        // Crear la estructura de la línea de tiempo
        historial.forEach(day => {
            const dayDiv = document.createElement('div');
            dayDiv.className = 'timeline-day';
            
            // Encabezado del día
            dayDiv.innerHTML = `
                <div class="d-flex align-items-baseline mb-3">
                    <h5 class="fw-bold text-dark mb-0 me-2" style="font-size: 1rem;">${day.fecha}</h5>
                    <span class="text-muted fw-semibold" style="font-size: 0.8rem;">• ${day.dia_nombre}</span>
                </div>
            `;

            // Listado de clases programadas
            const card = document.createElement('div');
            card.className = 'card border-0 shadow-sm overflow-hidden';
            card.style = 'border-radius: 14px;';
            
            let classesListHtml = '';
            day.clases.forEach((clase, idx) => {
                let badgeLabel = 'Sin registro';
                let iconClass = 'fa-solid fa-minus';
                
                if (clase.estatus === 'A') {
                    badgeLabel = 'Asistencia';
                    iconClass = 'fa-solid fa-circle-check';
                } else if (clase.estatus === 'F') {
                    badgeLabel = 'Falta';
                    iconClass = 'fa-solid fa-circle-xmark';
                } else if (clase.estatus === 'R') {
                    badgeLabel = 'Retardo';
                    iconClass = 'fa-solid fa-clock';
                } else if (clase.estatus === 'J') {
                    badgeLabel = 'Justificada';
                    iconClass = 'fa-solid fa-file-medical';
                }

                const borderTop = idx > 0 ? 'border-top: 1px solid #f1f5f9;' : '';
                classesListHtml += `
                    <div class="p-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2" style="${borderTop}">
                        <div class="d-flex align-items-start gap-3">
                            <div class="text-center bg-light p-2 rounded" style="min-width: 90px; border: 1px solid #e2e8f0;">
                                <div class="fw-bold text-dark" style="font-size: 0.78rem;">${clase.horaInicio}</div>
                                <div class="text-muted" style="font-size: 0.65rem;">${clase.horaFin}</div>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.85rem; line-height: 1.4;">${clase.nombreMateria}</h6>
                                <div class="text-muted d-flex align-items-center gap-3" style="font-size: 0.72rem;">
                                    <span><i class="fa-solid fa-user-tie me-1"></i>${clase.docente_nombre}</span>
                                    <span><i class="fa-solid fa-door-open me-1"></i>${clase.aula}</span>
                                </div>
                                ${clase.observaciones ? `<small class="text-danger fw-semibold d-block mt-1" style="font-size: 0.7rem;"><i class="fa-regular fa-comment me-1"></i>Obs: ${clase.observaciones}</small>` : ''}
                            </div>
                        </div>
                        <div class="text-md-end">
                            <span class="badge-asist-status badge-status-${clase.estatus}">
                                <i class="${iconClass}"></i>${badgeLabel}
                            </span>
                        </div>
                    </div>
                `;
            });

            card.innerHTML = classesListHtml;
            dayDiv.appendChild(card);
            container.appendChild(dayDiv);
        });
    }
</script>
@endsection
