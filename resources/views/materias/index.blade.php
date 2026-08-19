@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/estilosMaterias.css') }}?v={{ time() }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<div class="page-container">

    <div class="d-flex justify-content-between align-items-center mb-4">


        <a href="{{ url()->previous() }}" class="btn btn-azul">
            <i class="fa-solid fa-arrow-left me-2"></i>
            Regresar
        </a>

        <h3 class="page-title">
            Lista de Materias
        </h3>

        <button class="btn btn-azul" data-bs-toggle="modal" data-bs-target="#modalMateria">
            <i class="fa-solid fa-plus me-2"></i>
            Alta materia
        </button>

    </div>

    <div class="glass-card">



        <div class="glass-header p-3 d-flex justify-content-between align-items-center">


            <input type="text" id="buscadorMateria" class="form-control glass-input w-25"
                placeholder="Buscar materia...">




        </div>

        <div class="table-responsive">

            <table class="table table-borderless glass-table align-middle mb-0">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Clave</th>
                        <th>Nombre</th>
                        <th>Centro de Trabajo (CCT)</th>
                        <th>Semestre / Periodo</th>
                        <th>Estatus</th>
                        <th>Docentes</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody id="tablaMaterias"></tbody>

            </table>

        </div>

        <div class="glass-footer p-3 d-flex justify-content-between align-items-center">

            <small id="infoPaginacionMaterias"></small>

            <div id="paginacionMaterias"></div>

        </div>

    </div>

</div>

<!-- Modal Materia -->
<div class="modal fade" id="modalMateria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content glass-modal">

            <form id="formMateria">

                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-white">
                        <i class="fa-solid fa-book me-2"></i>
                        Alta Materia
                    </h5>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar">
                    </button>
                </div>

                <div class="modal-body p-4">

                    <div class="container-fluid p-0">

                        <div class="row g-3">

                            <!-- Nombre -->
                            <div class="col-md-6">
                                <label class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-premium" name="nombreMateria"
                                    placeholder="Ej. Matemáticas I" required>
                            </div>

                            <!-- Descripción -->
                            <div class="col-md-6">
                                <label class="form-label">Descripción <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-premium" name="descripcionMateria"
                                    placeholder="Ej. Materia de tronco común" required>
                            </div>

                            <!-- Clave -->
                            <div class="col-md-6">
                                <label class="form-label">Clave <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-premium" name="clave"
                                    placeholder="Ej. MAT-201" required>
                            </div>

                            <!-- Estatus -->
                            <div class="col-md-6">
                                <label class="form-label">Estatus <span class="text-danger">*</span></label>
                                <select class="form-select form-select-premium" name="estatusMateria">
                                    <option value="ACTIVA">ACTIVA</option>
                                    <option value="INACTIVA">INACTIVA</option>
                                </select>
                            </div>

                            <!-- Centro de Trabajo (CCT) -->
                            <div class="col-md-6">
                                <label class="form-label">Centro de Trabajo (CCT) <span class="text-danger">*</span></label>
                                <select
                                    class="form-select form-select-premium"
                                    name="idCentroTrabajo"
                                    id="selectCctMateria"
                                    onchange="filtrarPeriodosPorCct(this.value)"
                                    required>
                                    <option value="">-- Seleccionar CCT --</option>
                                    <option value="2">BTI (Bachillerato Tecnológico Interamericano)</option>
                                    <option value="3">BGNE (Bachillerato General No Escolarizado)</option>
                                    <option value="1">INFORMÁTICA Y COMPUTACIÓN</option>
                                </select>
                            </div>

                            <!-- Nivel Académico (Semestre / Trimestre) -->
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label mb-0" id="labelNivelMateria">Semestre / Periodo <span class="text-danger">*</span></label>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" id="chkMultiplesPeriodos" name="multiples_periodos">
                                        <label class="form-check-label small text-muted" for="chkMultiplesPeriodos">Múltiples</label>
                                    </div>
                                </div>
                                <select
                                    class="form-select form-select-premium"
                                    name="id_nivel_academico"
                                    id="selectNivelMateria"
                                    required>
                                    <option value="">-- Primero seleccione un Centro de Trabajo --</option>
                                </select>
                                <div id="contenedorNivelesCheckboxes" class="border rounded p-2 text-black bg-white" style="display: none; max-height: 120px; overflow-y: auto;">
                                    <!-- Populated dynamically -->
                                </div>
                            </div>

                            <!-- Docentes -->
                            <div class="col-12">
                                <label class="form-label d-flex justify-content-between align-items-center">
                                    <span>Docentes asignados</span>
                                    <small class="text-muted fw-normal">Selecciona los docentes</small>
                                </label>

                                <div id="contenedorDocentes" class="contenedor-docentes text-black">
                                </div>

                                <div id="docentesSeleccionados" class="mt-2 d-flex flex-wrap gap-1">
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer border-0">

                    <button type="button" class="btn btn-azul" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button class="btn btn-azul" type="submit">
                        <i class="fa-solid fa-floppy-disk me-2"></i>
                        Guardar
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>
@endsection

<script>
let listaDocentes = [];

document.addEventListener("DOMContentLoaded", function() {

    // Mover el modal a #contenedorModal en la raíz para evitar stacking context con backdrop
    const modalEl = document.getElementById('modalMateria');
    const modalContainer = document.getElementById('contenedorModal') || document.body;
    if (modalEl && modalEl.parentElement !== modalContainer) {
        modalContainer.appendChild(modalEl);
    }

    const contenedorDocentes = document.getElementById('contenedorDocentes');
    const contenedorSeleccionados = document.getElementById('docentesSeleccionados');

    // =========================
    // CARGAR DOCENTES (CHECKBOX)
    // =========================
    function cargarDocentes() {

        fetch('/docentes/lista')
            .then(res => res.json())
            .then(data => {

                listaDocentes = Array.isArray(data.data) ? data.data : [];

                contenedorDocentes.innerHTML = '';

                listaDocentes.forEach(docente => {

                    const id = docente.idDocente;
                    const nombre =
                        `${docente.nombreDocente} ${docente.apPaternoDocente} ${docente.apMaternoDocente}`;

                    const div = document.createElement('div');
                    div.className = 'form-check mb-1';

                    div.innerHTML = `
                        <input class="form-check-input docenteCheck" type="checkbox" value="${id}" id="doc_${id}">
                        <label class="form-check-label text-dark fw-normal ms-1" for="doc_${id}">
                            ${nombre}
                        </label>
                    `;

                    contenedorDocentes.appendChild(div);
                });

                agregarEventosCheckbox();
                cargarMaterias();
            });
    }

    // =========================
    // EVENTO CHECKBOX
    // =========================
    function agregarEventosCheckbox() {

        const checks = document.querySelectorAll('.docenteCheck');

        checks.forEach(check => {
            check.addEventListener('change', mostrarSeleccionados);
        });
    }

    function mostrarSeleccionados() {

        const checks = document.querySelectorAll('.docenteCheck:checked');

        contenedorSeleccionados.innerHTML = '';

        checks.forEach(check => {

            const label = document.querySelector(`label[for="${check.id}"]`);

            let badge = document.createElement('span');
            badge.className = 'badge bg-primary me-1';
            badge.textContent = label.textContent;

            contenedorSeleccionados.appendChild(badge);
        });
    }

    // =========================
    // CARGAR MATERIAS
    // =========================
    let listaMaterias = [];
    let paginaMateria = 1;
    const filasMateria = 15;

    function cargarMaterias() {

        fetch('/materias/lista')
            .then(res => res.json())
            .then(data => {

                listaMaterias = Array.isArray(data.data) ? data.data : [];
                renderMaterias();
            });
    }

    // 🔍 BUSCADOR
    document.getElementById('buscadorMateria')?.addEventListener('input', () => {
        paginaMateria = 1;
        renderMaterias();
    });

    let modoMateria = 'crear'; // 'crear', 'editar', 'ver'
    let idMateriaActual = null;

    window.filtrarPeriodosPorCct = function(idCentroTrabajo, selectedNivelId = null) {
        const selectNivel = document.getElementById('selectNivelMateria');
        if (!selectNivel) return Promise.resolve();

        if (!idCentroTrabajo) {
            selectNivel.innerHTML = '<option value="">-- Primero seleccione un Centro de Trabajo --</option>';
            return Promise.resolve();
        }

        selectNivel.innerHTML = '<option value="">Cargando semestres / periodos...</option>';

        return fetch(`/catalogos/niveles-academicos?idCentroTrabajo=${idCentroTrabajo}`)
            .then(res => res.json())
            .then(niveles => {
                selectNivel.innerHTML = '<option value="">-- Seleccionar Nivel --</option>';
                const containerCheckboxes = document.getElementById('contenedorNivelesCheckboxes');
                if (containerCheckboxes) {
                    containerCheckboxes.innerHTML = '';
                }

                if (Array.isArray(niveles)) {
                    niveles.forEach(n => {
                        const opt = document.createElement('option');
                        opt.value = n.id;
                        opt.textContent = n.nombre;
                        if (selectedNivelId && String(n.id) === String(selectedNivelId)) {
                            opt.selected = true;
                        }
                        selectNivel.appendChild(opt);

                        if (containerCheckboxes) {
                            const div = document.createElement('div');
                            div.className = 'form-check';
                            div.innerHTML = `
                                <input class="form-check-input nivelCheck" type="checkbox" value="${n.id}" id="nivel_${n.id}">
                                <label class="form-check-label text-dark fw-normal ms-1" for="nivel_${n.id}">
                                    ${n.nombre}
                                </label>
                            `;
                            containerCheckboxes.appendChild(div);
                        }
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

    function setFormDisabled(disabled) {
        const form = document.getElementById('formMateria');
        form.nombreMateria.disabled = disabled;
        form.descripcionMateria.disabled = disabled;
        if (form.clave) form.clave.disabled = disabled;
        form.estatusMateria.disabled = disabled;
        if (form.idCentroTrabajo) form.idCentroTrabajo.disabled = disabled;
        
        const chkMulti = document.getElementById('chkMultiplesPeriodos');
        if (chkMulti) chkMulti.disabled = disabled;
        
        if (form.id_nivel_academico) {
            form.id_nivel_academico.disabled = disabled || (chkMulti && chkMulti.checked);
        }
        document.querySelectorAll('.docenteCheck').forEach(cb => cb.disabled = disabled);
        document.querySelectorAll('.nivelCheck').forEach(cb => cb.disabled = disabled);
    }

    // Reset modal to create mode when Alta button is clicked
    const btnAlta = document.querySelector('[data-bs-target="#modalMateria"]');
    if (btnAlta) {
        btnAlta.addEventListener('click', function() {
            modoMateria = 'crear';
            idMateriaActual = null;
            const form = document.getElementById('formMateria');
            form.reset();
            const selectNivel = document.getElementById('selectNivelMateria');
            if (selectNivel) {
                selectNivel.innerHTML = '<option value="">-- Primero seleccione un Centro de Trabajo --</option>';
            }
            document.getElementById('docentesSeleccionados').innerHTML = '';
            
            const chkMulti = document.getElementById('chkMultiplesPeriodos');
            if (chkMulti) {
                chkMulti.checked = false;
                if (selectNivel) {
                    selectNivel.disabled = false;
                    selectNivel.style.display = 'block';
                    selectNivel.setAttribute('required', 'required');
                }
                const containerCheckboxes = document.getElementById('contenedorNivelesCheckboxes');
                if (containerCheckboxes) {
                    containerCheckboxes.style.display = 'none';
                    containerCheckboxes.innerHTML = '';
                }
                const labelSpan = document.querySelector('#labelNivelMateria .text-danger');
                if (labelSpan) labelSpan.style.display = 'inline';
            }
            
            setFormDisabled(false);
            document.querySelectorAll('.docenteCheck').forEach(cb => cb.checked = false);
            document.querySelector('#modalMateria .modal-title').innerHTML =
                '<i class="fa-solid fa-book me-2"></i> Alta Materia';
            const submitBtn = document.querySelector('#formMateria button[type="submit"]');
            submitBtn.style.display = 'inline-block';
            submitBtn.innerHTML = '<i class="fa-solid fa-floppy-disk me-2"></i> Guardar';
        });
    }

    window.verMateria = function(id) {
        modoMateria = 'ver';
        idMateriaActual = id;

        fetch(`/materias/${id}`)
            .then(res => res.json())
            .then(resp => {
                if (resp.success && resp.data) {
                    const m = resp.data;
                    const form = document.getElementById('formMateria');
                    form.nombreMateria.value = m.nombreMateria || '';
                    form.descripcionMateria.value = m.descripcionMateria || '';
                    if (form.clave) form.clave.value = m.clave || '';
                    form.estatusMateria.value = m.estatusMateria || 'ACTIVA';
                    if (form.idCentroTrabajo) form.idCentroTrabajo.value = m.idCentroTrabajo || '';

                    // Cargar niveles académicos correspondientes y auto-seleccionar
                    filtrarPeriodosPorCct(m.idCentroTrabajo, m.id_nivel_academico);

                    // Configurar checkbox de múltiples periodos
                    const isMulti = !m.id_nivel_academico;
                    const chkMulti = document.getElementById('chkMultiplesPeriodos');
                    if (chkMulti) {
                        chkMulti.checked = isMulti;
                        const selectNivel = document.getElementById('selectNivelMateria');
                        const containerCheckboxes = document.getElementById('contenedorNivelesCheckboxes');
                        const labelSpan = document.querySelector('#labelNivelMateria .text-danger');
                        if (isMulti) {
                            if (selectNivel) {
                                selectNivel.style.display = 'none';
                                selectNivel.removeAttribute('required');
                            }
                            if (containerCheckboxes) {
                                containerCheckboxes.style.display = 'block';
                            }
                            const labelSpan = document.querySelector('#labelNivelMateria .text-danger');
                            if (labelSpan) labelSpan.style.display = 'none';
                        } else {
                            if (selectNivel) {
                                selectNivel.style.display = 'block';
                                selectNivel.setAttribute('required', 'required');
                            }
                            if (containerCheckboxes) {
                                containerCheckboxes.style.display = 'none';
                            }
                            const labelSpan = document.querySelector('#labelNivelMateria .text-danger');
                            if (labelSpan) labelSpan.style.display = 'inline';
                        }
                    }

                    document.querySelectorAll('.docenteCheck').forEach(cb => cb.checked = false);
                    const checkIds = m.docentes ? m.docentes.map(d => d.idDocente) : [];
                    checkIds.forEach(docId => {
                        const cb = document.getElementById(`doc_${docId}`);
                        if (cb) cb.checked = true;
                    });
                    mostrarSeleccionados();
                    setFormDisabled(true);

                    document.querySelector('#modalMateria .modal-title').innerHTML =
                        '<i class="fa-solid fa-eye me-2"></i> Detalles de Materia';
                    const submitBtn = document.querySelector('#formMateria button[type="submit"]');
                    submitBtn.style.display = 'none';

                    const modal = new bootstrap.Modal(document.getElementById('modalMateria'));
                    modal.show();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al cargar detalles de la materia.',
                        confirmButtonColor: 'rgb(38, 104, 123)'
                    });
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar detalles de la materia.',
                    confirmButtonColor: 'rgb(38, 104, 123)'
                });
            });
    }

    window.editarMateria = function(id) {
        modoMateria = 'editar';
        idMateriaActual = id;

        fetch(`/materias/${id}`)
            .then(res => res.json())
            .then(resp => {
                if (resp.success && resp.data) {
                    const m = resp.data;
                    const form = document.getElementById('formMateria');
                    form.nombreMateria.value = m.nombreMateria || '';
                    form.descripcionMateria.value = m.descripcionMateria || '';
                    if (form.clave) form.clave.value = m.clave || '';
                    form.estatusMateria.value = m.estatusMateria || 'ACTIVA';
                    if (form.idCentroTrabajo) form.idCentroTrabajo.value = m.idCentroTrabajo || '';

                    // Cargar niveles académicos correspondientes y auto-seleccionar
                    filtrarPeriodosPorCct(m.idCentroTrabajo, m.id_nivel_academico);

                    // Configurar checkbox de múltiples periodos
                    const isMulti = !m.id_nivel_academico;
                    const chkMulti = document.getElementById('chkMultiplesPeriodos');
                    if (chkMulti) {
                        chkMulti.checked = isMulti;
                        const selectNivel = document.getElementById('selectNivelMateria');
                        const containerCheckboxes = document.getElementById('contenedorNivelesCheckboxes');
                        const labelSpan = document.querySelector('#labelNivelMateria .text-danger');
                        if (isMulti) {
                            if (selectNivel) {
                                selectNivel.style.display = 'none';
                                selectNivel.removeAttribute('required');
                            }
                            if (containerCheckboxes) {
                                containerCheckboxes.style.display = 'block';
                            }
                            const labelSpan = document.querySelector('#labelNivelMateria .text-danger');
                            if (labelSpan) labelSpan.style.display = 'none';
                        } else {
                            if (selectNivel) {
                                selectNivel.style.display = 'block';
                                selectNivel.setAttribute('required', 'required');
                            }
                            if (containerCheckboxes) {
                                containerCheckboxes.style.display = 'none';
                            }
                            const labelSpan = document.querySelector('#labelNivelMateria .text-danger');
                            if (labelSpan) labelSpan.style.display = 'inline';
                        }
                    }

                    document.querySelectorAll('.docenteCheck').forEach(cb => cb.checked = false);
                    const checkIds = m.docentes ? m.docentes.map(d => d.idDocente) : [];
                    checkIds.forEach(docId => {
                        const cb = document.getElementById(`doc_${docId}`);
                        if (cb) cb.checked = true;
                    });
                    mostrarSeleccionados();
                    setFormDisabled(false);

                    document.querySelector('#modalMateria .modal-title').innerHTML =
                        '<i class="fa-solid fa-pen-to-square me-2"></i> Editar Materia';
                    const submitBtn = document.querySelector('#formMateria button[type="submit"]');
                    submitBtn.style.display = 'inline-block';
                    submitBtn.innerHTML = '<i class="fa-solid fa-floppy-disk me-2"></i> Actualizar';

                    const modal = new bootstrap.Modal(document.getElementById('modalMateria'));
                    modal.show();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al cargar detalles de la materia.',
                        confirmButtonColor: 'rgb(38, 104, 123)'
                    });
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar detalles de la materia.',
                    confirmButtonColor: 'rgb(38, 104, 123)'
                });
            });
    }

    window.eliminarMateria = function(id) {
        Swal.fire({
            title: '¿Deseas eliminar esta materia?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: 'rgb(38, 104, 123)',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/materias/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Eliminado!',
                                text: data.message ||
                                    'Materia eliminada correctamente.',
                                confirmButtonColor: 'rgb(38, 104, 123)'
                            });
                            cargarMaterias();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Error al eliminar.',
                                confirmButtonColor: 'rgb(38, 104, 123)'
                            });
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al eliminar materia.',
                            confirmButtonColor: 'rgb(38, 104, 123)'
                        });
                    });
            }
        });
    }

    function renderMaterias() {
        const normalizeStr = str => (str || '').normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
        const filtro = normalizeStr(document.getElementById('buscadorMateria').value);

        const filtradas = listaMaterias.filter(m =>
            normalizeStr(m.nombreMateria).includes(filtro) ||
            normalizeStr(m.descripcionMateria).includes(filtro) ||
            normalizeStr(m.clave).includes(filtro)
        );

        const inicio = (paginaMateria - 1) * filasMateria;
        const fin = inicio + filasMateria;
        const datos = filtradas.slice(inicio, fin);

        let html = '';

        datos.forEach(materia => {
            const cctNombre = materia.nombreCentroTrabajo || 'N/A';
            const nivelNombre = materia.nombreNivelAcademico || 'N/A';
            
            let cctBadgeClass = 'bg-secondary';
            if (materia.idCentroTrabajo === 2) {
                cctBadgeClass = 'bg-primary'; // BTI
            } else if (materia.idCentroTrabajo === 3) {
                cctBadgeClass = 'bg-info'; // BGNE
            } else if (materia.idCentroTrabajo === 1) {
                cctBadgeClass = 'bg-success'; // Computación
            }

            html += `
        <tr>
            <td><strong>${materia.id}</strong></td>
            <td><code class="fw-bold">${materia.clave || '—'}</code></td>
            <td><strong>${materia.nombreMateria}</strong></td>
            <td>
                <span class="badge ${cctBadgeClass}">${cctNombre}</span>
            </td>
            <td>
                <span class="badge bg-light text-dark border">${nivelNombre}</span>
            </td>

            <td>
                <span class="badge ${materia.estatusMateria === 'ACTIVA' ? 'bg-success' : 'bg-danger'}">
                    ${materia.estatusMateria}
                </span>
            </td>

            <td>
                ${materia.docentes?.length
                    ? materia.docentes.map(d => d.nombreDocente).join(', ')
                    : '<span class="text-muted fst-italic">Sin docentes</span>'}
            </td>

            <td class="text-center">
                <button class="btn btn-secondary btn-sm me-1 shadow-sm" onclick="verMateria(${materia.id})" title="Ver detalles">
                    <i class="fa-solid fa-eye"></i>
                </button>
                <button class="btn btn-warning btn-sm me-1 shadow-sm" onclick="editarMateria(${materia.id})" title="Editar">
                    <i class="fa-solid fa-pen"></i>
                </button>
                <button class="btn btn-danger btn-sm shadow-sm" onclick="eliminarMateria(${materia.id})" title="Eliminar">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </td>
        </tr>
        `;
        });

        document.getElementById('tablaMaterias').innerHTML = html;

        renderPaginacionMaterias(filtradas.length);
    }

    function renderPaginacionMaterias(total) {

        const totalPaginas = Math.ceil(total / filasMateria);
        let html = '';

        for (let i = 1; i <= totalPaginas; i++) {
            html += `
        <button class="btn btn-sm ${i === paginaMateria ? 'btn-primary' : 'btn-outline-primary'} me-1"
            onclick="cambiarPaginaMateria(${i})">
            ${i}
        </button>`;
        }

        document.getElementById('paginacionMaterias').innerHTML = html;

        document.getElementById('infoPaginacionMaterias').innerText =
            `Mostrando ${total} registros`;
    }

    window.cambiarPaginaMateria = function(p) {
        paginaMateria = p;
        renderMaterias();
    }

    // =========================
    // FORMULARIO
    // =========================
    document.getElementById('formMateria').addEventListener('submit', function(e) {
        e.preventDefault();

        const docentes = Array.from(document.querySelectorAll('.docenteCheck:checked'))
            .map(check => Number(check.value));
        const chkMulti = document.getElementById('chkMultiplesPeriodos');
        const checkedNiveles = Array.from(document.querySelectorAll('.nivelCheck:checked'))
            .map(check => Number(check.value));

        if (chkMulti && chkMulti.checked && checkedNiveles.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Faltan Semestres / Periodos',
                text: 'Debes seleccionar al menos un semestre o periodo.',
                confirmButtonColor: 'rgb(38, 104, 123)'
            });
            return;
        }

        const data = {
            nombreMateria: this.nombreMateria.value,
            descripcionMateria: this.descripcionMateria.value,
            estatusMateria: this.estatusMateria.value,
            clave: this.clave.value,
            idCentroTrabajo: this.idCentroTrabajo.value ? parseInt(this.idCentroTrabajo.value) : null,
            id_nivel_academico: this.id_nivel_academico.value ? parseInt(this.id_nivel_academico.value) : null,
            docentes: docentes
        };

        // Alta Múltiple
        if (modoMateria === 'crear' && chkMulti && chkMulti.checked) {
            const promises = checkedNiveles.map(nivelId => {
                const payload = { ...data, id_nivel_academico: nivelId };
                return fetch('/materias', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                }).then(res => res.json());
            });

            Promise.all(promises)
                .then(responses => {
                    const allSuccess = responses.every(r => r.success);
                    if (allSuccess) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: "Materias guardadas correctamente para todos los periodos seleccionados.",
                            confirmButtonColor: 'rgb(38, 104, 123)'
                        });
                        bootstrap.Modal.getInstance(document.getElementById('modalMateria')).hide();
                        this.reset();
                        contenedorSeleccionados.innerHTML = '';
                        cargarMaterias();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al guardar algunas materias.',
                            confirmButtonColor: 'rgb(38, 104, 123)'
                        });
                    }
                })
                .catch(err => {
                    console.error("Error al guardar múltiples materias:", err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error inesperado al guardar.',
                        confirmButtonColor: 'rgb(38, 104, 123)'
                    });
                });
            return;
        }

        // Editar Múltiple (Actualizar la actual con el primer periodo y crear nuevas para los demás)
        if (modoMateria === 'editar' && chkMulti && chkMulti.checked) {
            const firstNivelId = checkedNiveles[0];
            const firstPayload = { ...data, id_nivel_academico: firstNivelId };
            
            const promises = [];
            promises.push(
                fetch(`/materias/${idMateriaActual}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(firstPayload)
                }).then(res => res.json())
            );

            for (let i = 1; i < checkedNiveles.length; i++) {
                const nextNivelId = checkedNiveles[i];
                const nextPayload = { ...data, id_nivel_academico: nextNivelId };
                promises.push(
                    fetch('/materias', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(nextPayload)
                    }).then(res => res.json())
                );
            }

            Promise.all(promises)
                .then(responses => {
                    const allSuccess = responses.every(r => r.success);
                    if (allSuccess) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: "Materia actualizada y nuevos periodos agregados correctamente.",
                            confirmButtonColor: 'rgb(38, 104, 123)'
                        });
                        bootstrap.Modal.getInstance(document.getElementById('modalMateria')).hide();
                        this.reset();
                        contenedorSeleccionados.innerHTML = '';
                        cargarMaterias();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al actualizar la materia o agregar nuevos periodos.',
                            confirmButtonColor: 'rgb(38, 104, 123)'
                        });
                    }
                })
                .catch(err => {
                    console.error("Error al editar múltiples materias:", err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error inesperado al guardar.',
                        confirmButtonColor: 'rgb(38, 104, 123)'
                    });
                });
            return;
        }

        // Caso normal (Único periodo - Crear o Editar)
        const url = modoMateria === 'editar' ? `/materias/${idMateriaActual}` : '/materias';
        const method = modoMateria === 'editar' ? 'PUT' : 'POST';

        fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(resp => {
                if (resp.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: modoMateria === 'editar' ?
                            "Materia actualizada correctamente." :
                            "Materia guardada correctamente.",
                        confirmButtonColor: 'rgb(38, 104, 123)'
                    });

                    bootstrap.Modal.getInstance(document.getElementById('modalMateria')).hide();
                    this.reset();
                    contenedorSeleccionados.innerHTML = '';
                    cargarMaterias();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al guardar materia.',
                        confirmButtonColor: 'rgb(38, 104, 123)'
                    });
                }
            });
    });

    cargarDocentes();

    // Listener para checkbox de múltiples periodos
    const chkMultiplesPeriodos = document.getElementById('chkMultiplesPeriodos');
    if (chkMultiplesPeriodos) {
        chkMultiplesPeriodos.addEventListener('change', function() {
            const selectNivel = document.getElementById('selectNivelMateria');
            const containerCheckboxes = document.getElementById('contenedorNivelesCheckboxes');
            const labelSpan = document.querySelector('#labelNivelMateria .text-danger');
            
            if (this.checked) {
                const currentVal = selectNivel ? selectNivel.value : "";
                if (selectNivel) {
                    selectNivel.style.display = 'none';
                    selectNivel.removeAttribute('required');
                }
                if (containerCheckboxes) {
                    containerCheckboxes.style.display = 'block';
                    if (currentVal) {
                        const cb = containerCheckboxes.querySelector(`#nivel_${currentVal}`);
                        if (cb) cb.checked = true;
                    }
                }
                if (labelSpan) labelSpan.style.display = 'none';
            } else {
                if (selectNivel) {
                    selectNivel.style.display = 'block';
                    selectNivel.setAttribute('required', 'required');
                }
                if (containerCheckboxes) {
                    containerCheckboxes.style.display = 'none';
                    containerCheckboxes.querySelectorAll('.nivelCheck').forEach(cb => cb.checked = false);
                }
                if (labelSpan) labelSpan.style.display = 'inline';
            }
        });
    }

});
</script>