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
            <i class="fa-solid fa-users me-2"></i>
            Grupos
        </h3>

        <button class="btn btn-azul" onclick="abrirModalGrupo()">
            <i class="fa-solid fa-plus me-2"></i>
            Alta grupo
        </button>

    </div>

    <div class="glass-card">

        <div class="glass-header p-3 d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

            </h5>

            <input type="text" id="buscadorGrupos" class="form-control glass-input w-25" placeholder="Buscar grupos...">

        </div>

        <div class="table-responsive">
            <div id="loading" class="text-center py-4" style="display:none;">
                <div class="spinner-border text-light"></div>
            </div>

            <table class="table table-borderless glass-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>CCT</th>
                        <th>Semestre / Trimestre</th>
                        <th>Progreso Periodo</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Estatus</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody id="tablaGrupos"></tbody>
            </table>
        </div>
        <div class="glass-footer p-3 d-flex justify-content-between align-items-center">
            <small id="infoGrupos"></small>
            <div id="paginacionGrupos"></div>
        </div>

    </div>
</div>
@endsection

<script>
let modoGrupo = 'crear';
let idGrupoActual = null;

function formatDateForInput(dateStr) {
    if (!dateStr) return '';

    // Si ya está en formato YYYY-MM-DD
    if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
        return dateStr;
    }

    // Si viene con hora "YYYY-MM-DD HH:MM:SS..."
    if (/^\d{4}-\d{2}-\d{2}\s/.test(dateStr)) {
        return dateStr.substring(0, 10);
    }

    try {
        const d = new Date(dateStr);
        if (isNaN(d.getTime())) return '';

        // Si tiene formato GMT/UTC, obtenemos los valores UTC para evitar desfases de zona horaria
        if (dateStr.includes('GMT') || dateStr.endsWith('Z')) {
            const yyyy = d.getUTCFullYear();
            const mm = String(d.getUTCMonth() + 1).padStart(2, '0');
            const dd = String(d.getUTCDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        } else {
            const yyyy = d.getFullYear();
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const dd = String(d.getDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        }
    } catch (e) {
        return '';
    }
}

function setFormDisabled(disabled) {
    const form = document.getElementById('formGrupo');
    if (!form) return;
    if (form.clave) form.clave.disabled = disabled;
    if (form.fechaCreacion) form.fechaCreacion.disabled = disabled;
    if (form.fechaInicio) form.fechaInicio.disabled = disabled;
    if (form.fechaFin) form.fechaFin.disabled = disabled;
    if (form.id_centroTrabajo) form.id_centroTrabajo.disabled = disabled;
    if (form.id_planEstudios) form.id_planEstudios.disabled = disabled;
    if (form.id_tipoPeriodo) form.id_tipoPeriodo.disabled = disabled;
    if (form.id_nivel_academico) form.id_nivel_academico.disabled = disabled;
    if (form.modalidadHorario) form.modalidadHorario.disabled = disabled;
    if (form.statusGrupo) form.statusGrupo.disabled = disabled;
    const chk = document.getElementById('chkCalcularSemanas');
    if (chk) chk.disabled = disabled;
}

function cargarNivelesAcademicos(idCentroTrabajo, selectedNivelId = null) {
    const form = document.getElementById('formGrupo');
    if (!form || !form.id_nivel_academico) return Promise.resolve();
    const selectNivel = form.id_nivel_academico;

    if (!idCentroTrabajo) {
        selectNivel.innerHTML = '<option value="">Seleccione un centro de trabajo primero</option>';
        return Promise.resolve();
    }

    selectNivel.innerHTML = '<option value="">Cargando niveles académicos...</option>';

    return fetch(`/catalogos/niveles-academicos?idCentroTrabajo=${idCentroTrabajo}`)
        .then(res => res.json())
        .then(niveles => {
            selectNivel.innerHTML = '<option value="">Seleccione un nivel</option>';
            if (Array.isArray(niveles)) {
                niveles.forEach(n => {
                    const opt = document.createElement('option');
                    opt.value = n.id;
                    opt.textContent = `${n.nombre}`;
                    if (selectedNivelId && String(n.id) === String(selectedNivelId)) {
                        opt.selected = true;
                    }
                    selectNivel.appendChild(opt);
                });
                if (selectedNivelId) {
                    selectNivel.value = selectedNivelId;
                }
            }
        })
        .catch(err => {
            console.error('Error cargando niveles académicos:', err);
            selectNivel.innerHTML = '<option value="">Error al cargar niveles</option>';
        });
}

function initializeModalEvents(initialNivelId = null) {
    const form = document.getElementById('formGrupo');
    if (!form) return;

    const selectCt = form.id_centroTrabajo;
    const chkDiv = document.getElementById('divCalcularSemanas');
    const chk = document.getElementById('chkCalcularSemanas');
    const inputInicio = form.fechaInicio;
    const inputFin = form.fechaFin;
    const selectPeriodo = form.id_tipoPeriodo;
    const selectNivel = form.id_nivel_academico;

    if (!selectCt || !chkDiv || !chk || !inputInicio || !inputFin || !selectPeriodo || !selectNivel) return;

    // Función para validar y ajustar según el CT seleccionado
    function checkCt(showAlert = false, nivelIdToSelect = null) {
        const selectedOption = selectCt.options[selectCt.selectedIndex];
        if (!selectedOption || !selectCt.value) {
            selectNivel.innerHTML = '<option value="">Seleccione un centro de trabajo primero</option>';
            chkDiv.style.display = 'none';
            chk.checked = false;
            inputFin.readOnly = false;
            selectPeriodo.disabled = false;
            return;
        }

        const ctNombre = (selectedOption.dataset.nombre || selectedOption.textContent || '').toUpperCase();
        const idPeriodo = selectedOption.dataset.idPeriodo;
        const isBgne = ctNombre.includes('BGNE');

        // Auto-seleccionar Tipo de Periodo según el Centro de Trabajo
        if (idPeriodo) {
            selectPeriodo.value = idPeriodo;
        } else if (isBgne) {
            const optionTrimestral = Array.from(selectPeriodo.options).find(opt => 
                opt.textContent.toUpperCase().includes('TRIMESTRAL')
            );
            if (optionTrimestral) selectPeriodo.value = optionTrimestral.value;
        }

        if (isBgne) {
            chkDiv.style.display = 'block';
            selectPeriodo.disabled = true;

            if (showAlert) {
                Swal.fire({
                    title: 'Ajuste Automático',
                    text: 'Al ser un Centro de Trabajo BGNE, el tipo de periodo se ha configurado forzosamente como TRIMESTRAL.',
                    icon: 'info',
                    confirmButtonColor: 'rgb(38, 104, 123)'
                });
            }
        } else {
            chkDiv.style.display = 'none';
            chk.checked = false;
            inputFin.readOnly = false;
            selectPeriodo.disabled = false;
        }

        // Cargar dinámicamente los niveles académicos del CCT (Semestre para BTI / Trimestre para BGNE)
        cargarNivelesAcademicos(selectCt.value, nivelIdToSelect);
    }

    // Función para calcular y autocompletar la fecha de finalización (78 semanas)
    function calculateEndDate() {
        if (chk.checked && inputInicio.value) {
            const startParts = inputInicio.value.split('-');
            if (startParts.length === 3) {
                // Crear fecha local de forma segura
                const start = new Date(startParts[0], startParts[1] - 1, startParts[2]);
                // 77 semanas * 7 días = 539 días desde la fecha de inicio
                const end = new Date(start.getTime() + (77 * 7 * 24 * 60 * 60 * 1000));

                const yyyy = end.getFullYear();
                const mm = String(end.getMonth() + 1).padStart(2, '0');
                const dd = String(end.getDate()).padStart(2, '0');

                inputFin.value = `${yyyy}-${mm}-${dd}`;
                inputFin.readOnly = true;
            }
        } else {
            inputFin.readOnly = false;
        }
    }

    selectCt.addEventListener('change', () => {
        checkCt(true, null);
        calculateEndDate();
    });

    selectPeriodo.addEventListener('change', () => {
        if (selectPeriodo.value && !selectCt.value) {
            fetch(`/catalogos/niveles-academicos?idTipoPeriodo=${selectPeriodo.value}`)
                .then(res => res.json())
                .then(niveles => {
                    selectNivel.innerHTML = '<option value="">Seleccione un nivel</option>';
                    if (Array.isArray(niveles)) {
                        niveles.forEach(n => {
                            const opt = document.createElement('option');
                            opt.value = n.id;
                            opt.textContent = `${n.nombre}`;
                            selectNivel.appendChild(opt);
                        });
                    }
                });
        }
    });

    chk.addEventListener('change', calculateEndDate);
    inputInicio.addEventListener('change', calculateEndDate);
    inputInicio.addEventListener('input', calculateEndDate);

    // Inicializar para cuando los valores ya estén pre-cargados (sin alerta)
    checkCt(false, initialNivelId);

    // Auto-detectar si ya tiene 78 semanas asignadas para marcar el checkbox
    if (inputInicio.value && inputFin.value) {
        const startParts = inputInicio.value.split('-');
        const endParts = inputFin.value.split('-');
        if (startParts.length === 3 && endParts.length === 3) {
            const start = new Date(startParts[0], startParts[1] - 1, startParts[2]);
            const end = new Date(endParts[0], endParts[1] - 1, endParts[2]);
            const diffTime = Math.abs(end.getTime() - start.getTime());
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            if (diffDays === 539) {
                chk.checked = true;
                inputFin.readOnly = true;
            }
        }
    }
}

function abrirModalGrupo() {
    modoGrupo = 'crear';
    idGrupoActual = null;

    fetch('/grupos/modalAlta')
        .then(res => res.text())
        .then(html => {
            document.getElementById('contenedorModal').innerHTML = html;
            setFormDisabled(false);

            // Auto-fill today's local date in fechaCreacion
            const form = document.getElementById('formGrupo');
            if (form) {
                const today = new Date();
                const yyyy = today.getFullYear();
                const mm = String(today.getMonth() + 1).padStart(2, '0');
                const dd = String(today.getDate()).padStart(2, '0');
                form.fechaCreacion.value = `${yyyy}-${mm}-${dd}`;
            }

            initializeModalEvents();

            let modal = new bootstrap.Modal(document.getElementById('modalGrupo'));
            modal.show();
        });
}
document.addEventListener("DOMContentLoaded", function() {

    let grupos = [];
    let search = '';
    let pagina = 1;

    function cargarGrupos() {

        document.getElementById('loading').style.display = 'block';

        fetch(`/grupos/lista?page=${pagina}&search=${search}`)
            .then(res => res.json())
            .then(res => {
                grupos = res.data;
                renderTabla();
                renderPaginacion(res);
            })
            .finally(() => {
                document.getElementById('loading').style.display = 'none';
            });
    }

    document.getElementById('buscadorGrupos').addEventListener('input', (e) => {
        search = e.target.value.toLowerCase();
        pagina = 1;
        cargarGrupos();
    });

    function formatearFecha(fecha) {
        if (!fecha) return '—';
        const str = formatDateForInput(fecha);
        const parts = str.split('-');
        if (parts.length === 3) {
            return `${parts[2]}/${parts[1]}/${parts[0]}`;
        }
        return new Date(fecha).toLocaleDateString('es-MX');
    }

    function calcularProgresoPeriodo(g) {
        if (!g.fechaInicio) return { percent: 0, nivelText: '', inicioPeriodo: '—', finPeriodo: '—' };

        let fechaInicioAbs = new Date(g.fechaInicio);
        if (isNaN(fechaInicioAbs.getTime())) return { percent: 0, nivelText: '', inicioPeriodo: '—', finPeriodo: '—' };

        let currentDate = new Date(Date.UTC(
            fechaInicioAbs.getUTCFullYear(),
            fechaInicioAbs.getUTCMonth(),
            fechaInicioAbs.getUTCDate()
        ));

        let groupEndDate = null;
        if (g.fechaFin) {
            let fechaFinAbs = new Date(g.fechaFin);
            if (!isNaN(fechaFinAbs.getTime())) {
                groupEndDate = new Date(Date.UTC(
                    fechaFinAbs.getUTCFullYear(),
                    fechaFinAbs.getUTCMonth(),
                    fechaFinAbs.getUTCDate()
                ));
            }
        }

        let idTipoPeriodo = g.id_tipoPeriodo;
        let idNivelActualDb = g.id_nivel_academico;

        if (idTipoPeriodo === null || idTipoPeriodo === undefined) {
            if (idNivelActualDb !== null && idNivelActualDb >= 7) {
                idTipoPeriodo = 1; // SEMESTRAL
            } else {
                idTipoPeriodo = 2; // TRIMESTRAL (default)
            }
        }

        const isTrimestral = idTipoPeriodo === 2;
        const weeksPerPeriod = isTrimestral ? 13 : 26;
        const periodLabel = isTrimestral ? "Trim." : "Sem.";

        const now = new Date();
        const todayUTC = new Date(Date.UTC(now.getFullYear(), now.getMonth(), now.getDate()));

        let periodNumber = 1;
        let periodStartDate = new Date(currentDate.getTime());
        let periodEndDate = new Date(currentDate.getTime() + ((weeksPerPeriod - 1) * 7 * 24 * 60 * 60 * 1000));

        while (true) {
            periodEndDate = new Date(periodStartDate.getTime() + ((weeksPerPeriod - 1) * 7 * 24 * 60 * 60 * 1000));

            if (todayUTC.getTime() <= periodEndDate.getTime()) {
                break;
            }

            if (groupEndDate && periodStartDate.getTime() > groupEndDate.getTime()) {
                break;
            }
            if (isTrimestral && periodNumber >= 6) break;
            if (!isTrimestral && periodNumber >= 6) break;

            if (groupEndDate && periodEndDate.getTime() >= groupEndDate.getTime()) {
                break;
            }

            periodStartDate = new Date(periodEndDate.getTime() + (7 * 24 * 60 * 60 * 1000));
            periodNumber++;
        }

        const total = periodEndDate.getTime() - periodStartDate.getTime();
        const elapsed = todayUTC.getTime() - periodStartDate.getTime();

        let percent = 0;
        if (total > 0) {
            percent = Math.round((elapsed / total) * 100);
            percent = Math.max(0, Math.min(100, percent));
        }

        const toDMY = (d) => {
            const dd = String(d.getUTCDate()).padStart(2, '0');
            const mm = String(d.getUTCMonth() + 1).padStart(2, '0');
            const yyyy = d.getUTCFullYear();
            return `${dd}/${mm}/${yyyy}`;
        };

        return {
            percent: percent,
            nivelText: `${periodNumber}° ${periodLabel}`,
            inicioPeriodo: toDMY(periodStartDate),
            finPeriodo: toDMY(periodEndDate)
        };
    }

    function renderTabla() {

        if (!grupos.length) {
            document.getElementById('tablaGrupos').innerHTML = `
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        No se encontraron grupos
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';

        grupos.forEach(grupo => {
            const status = (grupo.statusGrupo || 'ACTIVO').toUpperCase();
            const badgeClass = status === 'ACTIVO' ? 'bg-success' : 'bg-danger';
            const statusBadge = `<span class="badge ${badgeClass}" style="font-size: 0.75rem; padding: 5px 10px; border-radius: 12px;">${status}</span>`;

            const cctNombre = grupo.nombreCentroTrabajo || (grupo.id_centroTrabajo === 3 ? 'BGNE' : (grupo.id_centroTrabajo === 2 ? 'BTI' : (grupo.id_centroTrabajo === 1 ? 'INF. Y COMP.' : '—')));
            const cctBadgeClass = grupo.id_centroTrabajo === 3 ? 'bg-primary' : (grupo.id_centroTrabajo === 2 ? 'bg-info text-dark' : 'bg-secondary');

            const nivelNombre = grupo.nombre_nivel || (grupo.id_nivel_academico ? (grupo.id_nivel_academico <= 6 ? `${grupo.id_nivel_academico}° Trimestre` : `${grupo.id_nivel_academico - 6}° Semestre`) : '—');

            const progreso = calcularProgresoPeriodo(grupo);

            html += `
                <tr>
                    <td><strong>${grupo.clave}</strong></td>
                    <td>
                        <span class="badge ${cctBadgeClass}">${cctNombre}</span>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">${nivelNombre}</span>
                    </td>
                    <td style="min-width: 170px;">
                        <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.76rem;">
                            <span class="fw-bold text-dark">${progreso.nivelText || 'Periodo'}</span>
                            <span class="fw-bold" style="color: rgb(38, 104, 123);">${progreso.percent}%</span>
                        </div>
                        <div class="progress" style="height: 6px; background-color: #e2e8f0; border-radius: 4px; overflow: hidden;" title="Periodo: ${progreso.inicioPeriodo} - ${progreso.finPeriodo}">
                            <div class="progress-bar" role="progressbar" style="width: ${progreso.percent}%; background-color: rgb(38, 104, 123);" aria-valuenow="${progreso.percent}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between text-muted mt-1" style="font-size: 0.68rem;">
                            <span>${progreso.inicioPeriodo}</span>
                            <span>${progreso.finPeriodo}</span>
                        </div>
                    </td>
                    <td><span class="fw-semibold text-secondary">${formatearFecha(grupo.fechaInicio)}</span></td>
                    <td><span class="fw-semibold text-secondary">${formatearFecha(grupo.fechaFin)}</span></td>
                    <td>${statusBadge}</td>
                    <td class="text-center">
                        <button class="btn btn-ver btn-sm" onclick="verGrupo(${grupo.id})" title="Detalles del grupo">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button class="btn btn-editar btn-sm" onclick="editarGrupo(${grupo.id})" title="Editar grupo">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <a href="/grupos/${grupo.id}/alumnos" class="btn btn-sm text-white" style="background: #0ea5e9; border: none; border-radius: 6px; padding: 4px 8px;" title="Ver alumnos del grupo">
                            <i class="fa-solid fa-users"></i>
                        </a>
                    </td>
                </tr>
            `;
        });

        document.getElementById('tablaGrupos').innerHTML = html;
    }

    window.editarGrupo = function(id) {
        modoGrupo = 'editar';
        idGrupoActual = id;

        fetch('/grupos/modalAlta')
            .then(res => res.text())
            .then(html => {
                document.getElementById('contenedorModal').innerHTML = html;

                fetch(`/grupos/${id}`)
                    .then(res => res.json())
                    .then(resp => {
                        if (resp.success && resp.data) {
                            const g = resp.data;
                            const form = document.getElementById('formGrupo');

                            setFormDisabled(false);

                            form.clave.value = g.clave || '';
                            form.fechaCreacion.value = formatDateForInput(g.fechaCreacion);
                            form.fechaInicio.value = formatDateForInput(g.fechaInicio);
                            form.fechaFin.value = formatDateForInput(g.fechaFin);
                            form.id_centroTrabajo.value = g.id_centroTrabajo || '';
                            form.id_planEstudios.value = g.id_planEstudios || '';
                            form.id_tipoPeriodo.value = g.id_tipoPeriodo || '';
                            form.modalidadHorario.value = g.modalidadHorario || '';
                            if (form.statusGrupo) form.statusGrupo.value = g.statusGrupo || 'ACTIVO';

                            initializeModalEvents(g.id_nivel_academico);

                            document.querySelector('.modal-title').textContent = 'Editar Grupo';
                            const submitBtn = document.querySelector(
                                '#formGrupo button[type="submit"]');
                            if (submitBtn) {
                                submitBtn.textContent = 'Actualizar';
                                submitBtn.className = 'btn btn-primary';
                            }

                            let modal = new bootstrap.Modal(document.getElementById('modalGrupo'));
                            modal.show();
                        } else {
                            alert('Error al cargar los detalles del grupo.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Error al cargar los detalles del grupo.');
                    });
            });
    };

    window.verGrupo = function(id) {
        modoGrupo = 'ver';
        idGrupoActual = id;

        fetch('/grupos/modalAlta')
            .then(res => res.text())
            .then(html => {

                document.getElementById('contenedorModal').innerHTML = html;

                fetch(`/grupos/${id}`)
                    .then(res => res.json())
                    .then(resp => {

                        if (resp.success && resp.data) {

                            const g = resp.data;
                            const form = document.getElementById('formGrupo');

                            form.clave.value = g.clave || '';
                            form.fechaInicio.value = formatDateForInput(g.fechaInicio);
                            form.fechaFin.value = formatDateForInput(g.fechaFin);
                            form.id_centroTrabajo.value = g.id_centroTrabajo || '';
                            form.id_planEstudios.value = g.id_planEstudios || '';
                            form.id_tipoPeriodo.value = g.id_tipoPeriodo || '';
                            form.modalidadHorario.value = g.modalidadHorario || '';
                            if (form.statusGrupo) form.statusGrupo.value = g.statusGrupo || 'ACTIVO';

                            initializeModalEvents(g.id_nivel_academico);
                            setFormDisabled(true);

                            document.querySelector('.modal-title').textContent =
                                'Detalles del Grupo';

                            const submitBtn = document.querySelector(
                                '#formGrupo button[type="submit"]'
                            );

                            if (submitBtn) {
                                submitBtn.style.display = 'none';
                            }

                            let modal = new bootstrap.Modal(
                                document.getElementById('modalGrupo')
                            );

                            modal.show();

                        } else {

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al cargar los detalles del grupo.',
                                confirmButtonColor: '#317D92'
                            });

                        }

                    })
                    .catch(err => {

                        console.error(err);

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al cargar los detalles del grupo.',
                            confirmButtonColor: '#317D92'
                        });

                    });

            });
    };

    function renderPaginacion(data) {
        let html = '';

        html += `
            <button class="btn btn-sm btn-azul me-2"
                ${data.page === 1 ? 'disabled' : ''}
                onclick="cambiarPagina(${data.page - 1})">
                ⬅ Anterior
            </button>
        `;

        html += `
            <span class="mx-2 text-white">
                Página ${data.page} de ${data.total_pages}
            </span>
        `;

        html += `
            <button class="btn btn-sm btn-azul ms-2"
                ${data.page === data.total_pages ? 'disabled' : ''}
                onclick="cambiarPagina(${data.page + 1})">
                Siguiente ➡
            </button>
        `;

        document.getElementById('paginacionGrupos').innerHTML = html;
        document.getElementById('infoGrupos').innerText = `Total grupos: ${data.total}`;
    }

    window.cambiarPagina = function(p) {
        pagina = p;
        cargarGrupos();
    }

    // Form submission for creating/editing group (delegated to index page since AJAX HTML script doesn't auto-evaluate)

    document.addEventListener('submit', function(e) {
        if (e.target.id === 'formGrupo') {
            e.preventDefault();

            let formData = new FormData(e.target);
            let data = Object.fromEntries(formData.entries());

            // Si id_tipoPeriodo está deshabilitado en el form (por ser BGNE),
            // FormData no lo captura. Lo recuperamos manualmente.
            if (e.target.id_tipoPeriodo && e.target.id_tipoPeriodo.disabled) {
                data.id_tipoPeriodo = e.target.id_tipoPeriodo.value;
            }

            const url = modoGrupo === 'editar' ?
                `/grupos/${idGrupoActual}` :
                '/grupos';

            const method = modoGrupo === 'editar' ?
                'PUT' :
                'POST';

            fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                })
                .then(async res => {
                    const isJson = res.headers.get('content-type')?.includes(
                        'application/json');
                    const resData = isJson ? await res.json() : null;

                    if (!res.ok) {
                        throw new Error(
                            resData?.message ||
                            'Error al guardar el grupo en el servidor backend'
                        );
                    }

                    return resData;
                })
                .then(res => {

                    // SWEET ALERT DE ÉXITO
                    Swal.fire({
                        icon: 'success',
                        title: modoGrupo === 'editar' ?
                            '¡Grupo actualizado!' : '¡Grupo guardado!',
                        text: modoGrupo === 'editar' ?
                            'El grupo se actualizó correctamente.' :
                            'El grupo se guardó correctamente.',
                        confirmButtonColor: '#317D92',
                        confirmButtonText: 'Aceptar'
                    });

                    let modalEl = document.getElementById('modalGrupo');
                    let modal = bootstrap.Modal.getInstance(modalEl);

                    if (modal) {
                        modal.hide();
                    }

                    // Remove modal backdrop if any remains
                    const backdrop = document.querySelector('.modal-backdrop');

                    if (backdrop) {
                        backdrop.remove();
                    }

                    cargarGrupos();
                })
                .catch(err => {

                    // SWEET ALERT DE ERROR
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al guardar',
                        text: err.message,
                        confirmButtonColor: '#317D92',
                        confirmButtonText: 'Aceptar'
                    });

                });
        }
    });


    // INIT
    cargarGrupos();

});
</script>

