@extends('layouts.app')

@section('content')
<div class="page-container">
    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('home') }}" class="btn btn-regresar">
            <i class="fa-solid fa-arrow-left me-2"></i>
            Regresar
        </a>
        <h3 class="page-title mb-0">
            <i class="fa-solid fa-triangle-exclamation text-warning me-2"></i>REPORTES DE INDISCIPLINA (BTI)
        </h3>
    </div>

    {{-- SECCIÓN 1: VISTA DE GRUPOS BTI --}}
    <div class="glass-card" id="seccion-grupos">
        <div class="glass-header p-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-slate-800 fw-bold">Grupos del Bachillerato Tecnológico Interamericano</h5>
            <div style="width: 300px;">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-filter text-muted"></i></span>
                    <input type="text" id="grupoSearch" class="form-control border-start-0" placeholder="Buscar grupo..." onkeyup="filtrarTablas(this.value, 'gruposTablaBody')">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-borderless glass-table align-middle mb-0 text-center">
                <thead>
                    <tr class="table-head">
                        <th>Clave del Grupo</th>
                        <th>Semestre / Nivel</th>
                        <th>Modalidad / Horario</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="gruposTablaBody">
                    <tr>
                        <td colspan="4" class="py-5 text-muted">
                            <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                            Cargando grupos de BTI...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- SECCIÓN 2: VISTA DE ALUMNOS DEL GRUPO SELECCIONADO --}}
    <div class="glass-card" id="seccion-alumnos" style="display: none;">
        <div class="glass-header p-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-outline-secondary px-3" onclick="regresarAGrupos()">
                    <i class="fa-solid fa-arrow-left me-1"></i> Regresar a Grupos
                </button>
                <h5 class="mb-0 text-slate-800 fw-bold">
                    Alumnos del Grupo: <strong id="lbl-grupo-seleccionado" class="text-primary"></strong>
                </h5>
            </div>
            <div style="width: 300px;">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-search text-muted"></i></span>
                    <input type="text" id="alumnoSearch" class="form-control border-start-0" placeholder="Buscar alumno por nombre..." onkeyup="filtrarTablas(this.value, 'alumnosTablaBody')">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-borderless glass-table align-middle mb-0 text-center">
                <thead>
                    <tr class="table-head">
                        <th>Matrícula</th>
                        <th>Nombre Completo</th>
                        <th>Estatus</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="alumnosTablaBody">
                    <!-- Cargado dinámicamente -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL DE REPORTES DE INDISCIPLINA (BTI) -->
<div class="modal fade" id="modalReportesIndisciplina" tabindex="-1" aria-labelledby="modalReportesLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="background: #ffffff;">
            <div class="modal-header py-3 px-4 d-flex justify-content-between align-items-center" style="background: #c2410c !important; color: #ffffff !important;">
                <h5 class="modal-title fw-bold text-white mb-0" id="modalReportesLabel">
                    <i class="fa-solid fa-triangle-exclamation text-white me-2"></i> Reportes de Indisciplina - Detalle
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-white" style="max-height: calc(85vh - 100px); overflow-y: auto;">
                <div class="row g-4">
                    <!-- Registrar Reporte -->
                    <div class="col-12 col-lg-5 border-end">
                        <h6 class="fw-bold text-slate-800 mb-3 border-bottom pb-2">
                            <i class="fa-solid fa-circle-plus text-danger me-2"></i>Registrar Nuevo Reporte
                        </h6>
                        <form id="formAlumnoReporte" onsubmit="guardarReporteAlumno(event)">
                            <input type="hidden" id="rep_id_alumno">
                            <input type="hidden" id="rep_alumno_nombre">
                            
                            <div class="mb-3 bg-light p-3 rounded-3">
                                <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.65rem;">ALUMNO SELECCIONADO</small>
                                <span class="fw-bold text-slate-900 fs-6" id="lbl-rep-alumno-nom">—</span>
                                <small class="text-muted d-block mt-0.5" id="lbl-rep-alumno-mat">Matrícula: —</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-slate-700 fs-7">Nombre del Tutor</label>
                                <input type="text" id="rep_tutor_nombre" class="form-control" placeholder="Nombre completo del tutor o tutor legal" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-slate-700 fs-7">Parcial correspondiente</label>
                                <select id="rep_parcial" class="form-select" required>
                                    <option value="1">Parcial 1</option>
                                    <option value="2">Parcial 2</option>
                                    <option value="3">Parcial 3</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-slate-700 fs-7">Descripción del incidente</label>
                                <textarea id="rep_incidente" class="form-control" rows="4" placeholder="Describa la indisciplina cometida detalladamente..." required></textarea>
                            </div>

                            <button type="submit" class="btn btn-danger w-100 fw-bold py-2 shadow-sm">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Registrar y Generar Formato
                            </button>
                        </form>
                    </div>

                    <!-- Historial del Alumno -->
                    <div class="col-12 col-lg-7">
                        <h6 class="fw-bold text-slate-800 mb-3 border-bottom pb-2">
                            <i class="fa-solid fa-clock-rotate-left text-secondary me-2"></i>Historial de Reportes del Alumno
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr class="fs-8">
                                        <th>Folio</th>
                                        <th>Tutor</th>
                                        <th>Parcial</th>
                                        <th>Fecha</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla-rep-alumno-historial">
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No hay reportes registrados.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let btiGrupos = [];

    document.addEventListener('DOMContentLoaded', () => {
        cargarGruposBTI();
    });

    // Cargar grupos de BTI (idCentroTrabajo = 2)
    function cargarGruposBTI() {
        const tbody = document.getElementById('gruposTablaBody');
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="py-5 text-muted">
                    <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                    Cargando grupos del BTI...
                </td>
            </tr>
        `;

        fetch('/catalogos/grupos?idCentroTrabajo=2&statusGrupo=ACTIVO')
            .then(r => r.json())
            .then(resp => {
                const list = resp.data || (Array.isArray(resp) ? resp : []);
                tbody.innerHTML = '';
                btiGrupos = list;

                if (list.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="4" class="py-4 text-muted">No se encontraron grupos activos para BTI.</td></tr>`;
                    return;
                }

                list.forEach(g => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td><strong>${g.clave}</strong></td>
                        <td>${g.nombre_nivel || '—'}</td>
                        <td><span class="badge bg-info-subtle text-info-emphasis px-3 py-1.5">${g.modalidadHorario || 'General'}</span></td>
                        <td>
                            <button class="btn btn-primary btn-sm px-3 shadow-sm d-inline-flex align-items-center gap-2" onclick="cargarAlumnosDelGrupo('${g.id}', '${g.clave}')">
                                <i class="fa-solid fa-users"></i> Ver Alumnos
                            </button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            })
            .catch(err => {
                console.error(err);
                tbody.innerHTML = `<tr><td colspan="4" class="py-4 text-danger">Error al cargar los grupos de BTI.</td></tr>`;
            });
    }

    // Cargar alumnos de un grupo específico
    function cargarAlumnosDelGrupo(idGrupo, claveGrupo) {
        const tbody = document.getElementById('alumnosTablaBody');
        document.getElementById('lbl-grupo-seleccionado').innerText = claveGrupo;
        document.getElementById('alumnoSearch').value = '';

        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="py-5 text-muted">
                    <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                    Cargando alumnos del grupo...
                </td>
            </tr>
        `;

        // Ocultar sección de grupos y mostrar la de alumnos
        document.getElementById('seccion-grupos').style.display = 'none';
        document.getElementById('seccion-alumnos').style.display = 'block';

        fetch(`/alumnos/grupo/${encodeURIComponent(idGrupo)}`)
            .then(r => r.json())
            .then(resp => {
                const list = resp.data || [];
                tbody.innerHTML = '';

                if (list.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="4" class="py-4 text-muted">No hay alumnos asignados a este grupo.</td></tr>`;
                    return;
                }

                list.forEach(alumno => {
                    const fullName = `${alumno.nombre} ${alumno.apPaterno} ${alumno.apMaterno || ''}`.trim();
                    const tr = document.createElement('tr');
                    tr.className = 'alumno-row-item';
                    tr.innerHTML = `
                        <td><strong>${alumno.numeroControl || alumno.idAlumno}</strong></td>
                        <td class="text-start font-semibold">${fullName.toUpperCase()}</td>
                        <td><span class="badge ${alumno.statusAlumno === 'ACTIVO' ? 'bg-success' : 'bg-secondary'}">${alumno.statusAlumno}</span></td>
                        <td>
                            <button class="btn btn-danger text-white btn-sm px-3 shadow-sm d-inline-flex align-items-center gap-2" onclick="abrirReportesIndisciplinaAlumno(${alumno.idAlumno}, '${fullName.replace(/'/g, "\\'")}', '${(alumno.tutor || '').replace(/'/g, "\\'")}', '${alumno.numeroControl || alumno.idAlumno}')" style="background-color: #c2410c; border-color: #c2410c;">
                                <i class="fa-solid fa-triangle-exclamation"></i> Gestionar Reportes
                            </button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            })
            .catch(err => {
                console.error(err);
                tbody.innerHTML = `<tr><td colspan="4" class="py-4 text-danger">Error al cargar el listado de alumnos.</td></tr>`;
            });
    }

    // Regresar a la vista de grupos
    function regresarAGrupos() {
        document.getElementById('seccion-alumnos').style.display = 'none';
        document.getElementById('seccion-grupos').style.display = 'block';
    }

    // Filtrado de búsquedas cliente-side super rápido
    function filtrarTablas(searchVal, tbodyId) {
        const query = searchVal.toLowerCase().trim();
        const rows = document.querySelectorAll(`#${tbodyId} tr`);

        rows.forEach(row => {
            if (row.cells.length <= 1 && row.innerText.includes('Cargando') || row.innerText.includes('No se encontraron') || row.innerText.includes('No hay')) return;
            const content = row.innerText.toLowerCase();
            if (content.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // ==========================================
    // SECCIÓN: MODAL Y REPORTES DE INDISCIPLINA
    // ==========================================

    window.abrirReportesIndisciplinaAlumno = function(idAlumno, alumnoNombre, tutorNombre, matricula) {
        document.getElementById('rep_id_alumno').value = idAlumno;
        document.getElementById('rep_alumno_nombre').value = alumnoNombre;
        document.getElementById('lbl-rep-alumno-nom').innerText = alumnoNombre.toUpperCase();
        document.getElementById('lbl-rep-alumno-mat').innerText = `Matrícula: ${matricula}`;
        
        document.getElementById('rep_tutor_nombre').value = tutorNombre || '';
        document.getElementById('rep_incidente').value = '';
        document.getElementById('rep_parcial').value = '1';

        cargarHistorialReportesAlumno(idAlumno);

        const modal = new bootstrap.Modal(document.getElementById('modalReportesIndisciplina'));
        modal.show();
    };

    window.cargarHistorialReportesAlumno = function(idAlumno) {
        const tbody = document.getElementById('tabla-rep-alumno-historial');
        if (!tbody) return;

        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4"><div class="spinner-border spinner-border-sm text-danger"></div> Cargando historial...</td></tr>`;

        fetch(`/reportes-indisciplina?id_alumno=${idAlumno}`)
            .then(r => r.json())
            .then(resp => {
                if (resp.success) {
                    tbody.innerHTML = '';
                    const list = resp.data || [];
                    if (list.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-muted">No hay reportes registrados para este alumno.</td></tr>`;
                        return;
                    }

                    list.forEach(rep => {
                        const tr = document.createElement('tr');
                        tr.className = 'fs-8';
                        tr.innerHTML = `
                            <td><strong class="text-slate-800">${rep.folio}</strong></td>
                            <td>${rep.tutor_nombre}</td>
                            <td><span class="badge bg-warning-subtle text-warning-emphasis fw-bold">${rep.parcial}° Parcial</span></td>
                            <td>${new Date(rep.fecha + 'T00:00:00').toLocaleDateString('es-MX')}</td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <button class="btn btn-sm btn-light text-primary border" onclick="imprimirFormatoReporteAlumno(${JSON.stringify(rep).replace(/"/g, '&quot;')})" title="Imprimir Formato">
                                        <i class="fa-solid fa-print"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light text-danger border" onclick="eliminarReporteAlumno(${rep.id}, ${rep.id_alumno})" title="Eliminar">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                } else {
                    tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-danger">Error al cargar historial.</td></tr>`;
                }
            })
            .catch(err => {
                console.error(err);
                tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-danger">Error de red.</td></tr>`;
            });
    };

    window.guardarReporteAlumno = function(event) {
        event.preventDefault();

        const idAlumno = document.getElementById('rep_id_alumno').value;
        const alumnoNombre = document.getElementById('rep_alumno_nombre').value;
        const tutorNombre = document.getElementById('rep_tutor_nombre').value;
        const incidente = document.getElementById('rep_incidente').value;
        const parcial = document.getElementById('rep_parcial').value;

        Swal.fire({
            title: 'Registrando reporte...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('/reportes-indisciplina', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                id_alumno: idAlumno,
                alumno_nombre: alumnoNombre,
                tutor_nombre: tutorNombre,
                incidente: incidente,
                parcial: parcial
            })
        })
        .then(r => r.json())
        .then(resp => {
            Swal.close();
            if (resp.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Reporte registrado',
                    text: 'El reporte de indisciplina se guardó correctamente.',
                    confirmButtonColor: '#c2410c'
                }).then(() => {
                    document.getElementById('rep_incidente').value = '';
                    cargarHistorialReportesAlumno(idAlumno);
                    imprimirFormatoReporteAlumno(resp.data);
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: resp.message || 'No se pudo guardar el reporte.',
                    confirmButtonColor: '#0284c7'
                });
            }
        })
        .catch(err => {
            Swal.close();
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error de red',
                text: 'Ocurrió un problema de comunicación.',
                confirmButtonColor: '#0284c7'
            });
        });
    };

    window.eliminarReporteAlumno = function(idReporte, idAlumno) {
        Swal.fire({
            title: '¿Eliminar reporte?',
            text: "Esta acción no se puede deshacer y borrará el registro del historial.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#64748b'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Eliminando...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/reportes-indisciplina/${idReporte}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(resp => {
                    Swal.close();
                    if (resp.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: 'El reporte de indisciplina fue eliminado.',
                            confirmButtonColor: '#0284c7'
                        });
                        cargarHistorialReportesAlumno(idAlumno);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: resp.message || 'No se pudo eliminar el reporte.',
                            confirmButtonColor: '#0284c7'
                        });
                    }
                })
                .catch(err => {
                    Swal.close();
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de red',
                        text: 'Ocurrió un problema al comunicarse con el servidor.',
                        confirmButtonColor: '#0284c7'
                    });
                });
            }
        });
    };

    window.imprimirFormatoReporteAlumno = function(rep) {
        const fechaFormateada = new Date(rep.fecha + 'T00:00:00').toLocaleDateString('es-MX', {day: 'numeric', month: 'long', year: 'numeric'}).toUpperCase();
        
        function renderBloque(repVal) {
            return `
                <div style="border: 2px solid #000; padding: 25px; font-family: Arial, Helvetica, sans-serif; position: relative; border-radius: 6px; background: #fff; margin-bottom: 25px;">
                    <!-- Membrete -->
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px; border-bottom: 2px solid #10599a; padding-bottom: 10px;">
                        <img src="/img/logo.png" alt="Logo" style="height: 50px; object-fit: contain;">
                        <div style="text-align: center; flex-grow: 1;">
                            <div style="font-weight: bold; font-size: 11pt; color: #10599a; letter-spacing: 0.5px; text-transform: uppercase;">
                                Bachillerato Tecnológico Interamericano
                            </div>
                            <div style="font-size: 6.8pt; color: #444; margin-top: 2px;">
                                Avenida Benito Juárez 901, Colonia Centro Teziutlán Puebla. Tel: 231-3123979
                            </div>
                        </div>
                        <div style="text-align: right; font-size: 8.5pt; font-weight: bold;">
                            Folio: <span style="color: #dc2626;">${repVal.folio}</span><br>
                            Fecha: ${fechaFormateada}
                        </div>
                    </div>

                    <!-- Titulo -->
                    <div style="text-align: center; margin-bottom: 20px;">
                        <h4 style="margin: 0; font-size: 12.5pt; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; text-decoration: underline;">
                            Reporte por indisciplina
                        </h4>
                    </div>

                    <!-- Contenido -->
                    <div style="font-size: 9.5pt; line-height: 1.7; color: #000; text-align: justify;">
                        <p style="margin: 0 0 12px 0;">
                            <strong>Sr. (a):</strong> <span style="border-bottom: 1px solid #000; padding: 0 10px; font-weight: bold; text-transform: uppercase;">${repVal.tutor_nombre || 'S/N'}</span>
                        </p>
                        <p style="margin: 0 0 15px 0; text-transform: uppercase; font-weight: bold; font-size: 8.5pt; color: #333;">
                            EL QUE SUSCRIBE ING. FAUSTO MAURO LEYVA FLORES, DIRECTOR DEL BACHILLERATO TECNOLÓGICO INTERAMERICANO.
                        </p>
                        <p style="margin: 0 0 12px 0;">
                            POR ESTE CONDUCTO LE INFORMO QUE EL ALUMNO(A): <span style="border-bottom: 1px solid #000; padding: 0 10px; font-weight: bold; text-transform: uppercase;">${repVal.alumno_nombre}</span>
                        </p>
                        <p style="margin: 0 0 8px 0;">
                            INCURRIÓ A LA FALTA DE INDISCIPLINA YA QUE:
                        </p>
                        <div style="border: 1px solid #94a3b8; background: #f8fafc; padding: 12px 15px; border-radius: 4px; font-family: monospace; font-size: 9.5pt; margin-bottom: 15px; min-height: 90px; white-space: pre-wrap; line-height: 1.4;">${repVal.incidente}</div>
                        
                        <p style="margin: 0 0 25px 0; font-size: 8.5pt; font-style: italic; color: #334155; line-height: 1.5; font-weight: bold; text-align: center;">
                            ESPERAMOS SU VALIOSA CONTRIBUCIÓN PARA QUE SU HIJO (A) MEJORE EN SU COMPORTAMIENTO Y NOS AYUDE A SACARLO ADELANTE EN SU EDUCACIÓN, FORMÁNDOLO EN UN BUEN CIUDADADANO.
                        </p>
                    </div>

                    <!-- Firmas -->
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 30px;">
                        <div style="width: 250px; text-align: center;">
                            <div style="border-bottom: 1px solid #000; margin-bottom: 5px; height: 35px;"></div>
                            <span style="font-size: 7.5pt; font-weight: bold; text-transform: uppercase; color: #475569;">Nombre y Firma del Alumno</span>
                        </div>
                        <div style="width: 140px; text-align: center; border: 1px dashed #cbd5e1; height: 75px; display: flex; align-items: center; justify-content: center; border-radius: 4px; background: #fafafa;">
                            <span style="font-size: 7.5pt; font-weight: bold; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Sello</span>
                        </div>
                        <div style="width: 250px; text-align: center;">
                            <div style="border-bottom: 1px solid #000; margin-bottom: 5px; height: 35px; text-align: center; font-size: 8pt; font-weight: bold; display: flex; align-items: flex-end; justify-content: center; text-transform: uppercase; color: #000;">
                                Ing. Fausto Leyva Flores
                            </div>
                            <span style="font-size: 7.5pt; font-weight: bold; text-transform: uppercase; color: #475569;">Director</span>
                        </div>
                    </div>
                </div>
            `;
        }

        const win = window.open('', '', 'height=850,width=800');
        win.document.write(`
            <html>
                <head>
                    <title>Reporte de Indisciplina - ${rep.folio}</title>
                    <style>
                        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; box-sizing: border-box; }
                        @page {
                            size: letter portrait;
                            margin: 6mm 10mm 6mm 10mm;
                        }
                        html, body {
                            font-family: Arial, Helvetica, sans-serif;
                            background: #fff;
                            color: #000;
                            padding: 0;
                            margin: 0;
                        }
                        .container {
                            width: 100%;
                            margin: 0 auto;
                        }
                        .separator {
                            text-align: center;
                            font-size: 9pt;
                            font-weight: bold;
                            color: #64748b;
                            margin: 15px 0;
                            border-top: 1px dashed #64748b;
                            padding-top: 15px;
                            position: relative;
                        }
                        .separator-icon {
                            position: absolute;
                            top: -10px;
                            left: 50%;
                            transform: translateX(-50%);
                            background: #fff;
                            padding: 0 10px;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <!-- TALÓN 1 (Escuela) -->
                        <div style="font-size: 7.5pt; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px;">
                            Talón 1 - Expediente Escolar
                        </div>
                        ${renderBloque(rep)}

                        <!-- Divisor de Corte -->
                        <div class="separator">
                            <span class="separator-icon">✂ Cortar aquí</span>
                        </div>

                        <!-- TALÓN 2 (Tutor) -->
                        <div style="font-size: 7.5pt; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px; margin-top: 5px;">
                            Talón 2 - Para el Padre / Tutor
                        </div>
                        ${renderBloque(rep)}
                    </div>
                </body>
            </html>
        `);
        win.document.close();
        win.focus();
        setTimeout(() => {
            win.print();
            win.close();
        }, 400);
    };
</script>
@endsection
