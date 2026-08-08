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
    form.clave.disabled = disabled;
    form.fechaCreacion.disabled = disabled;
    form.fechaInicio.disabled = disabled;
    form.fechaFin.disabled = disabled;
    form.id_centroTrabajo.disabled = disabled;
    form.id_planEstudios.disabled = disabled;
    form.id_tipoPeriodo.disabled = disabled;
    form.id_nivel_academico.disabled = disabled;
    form.modalidadHorario.disabled = disabled;
    if (form.statusGrupo) form.statusGrupo.disabled = disabled;
}

function initializeModalEvents() {
    const form = document.getElementById('formGrupo');
    if (!form) return;

    const selectCt = form.id_centroTrabajo;
    const chkDiv = document.getElementById('divCalcularSemanas');
    const chk = document.getElementById('chkCalcularSemanas');
    const inputInicio = form.fechaInicio;
    const inputFin = form.fechaFin;
    const selectPeriodo = form.id_tipoPeriodo;

    if (!selectCt || !chkDiv || !chk || !inputInicio || !inputFin || !selectPeriodo) return;

    // Función para validar si el CT seleccionado es "BGNE"
    function checkCt(showAlert = false) {
        const selectedOption = selectCt.options[selectCt.selectedIndex];
        const isBgne = selectedOption && selectedOption.textContent.toUpperCase().includes('BGNE');

        if (isBgne) {
            chkDiv.style.display = 'block';

            // Buscar la opción TRIMESTRAL en el select de periodo
            const optionTrimestral = Array.from(selectPeriodo.options).find(opt => opt.textContent.toUpperCase()
                .includes('TRIMESTRAL'));
            if (optionTrimestral) {
                if (selectPeriodo.value !== optionTrimestral.value) {
                    selectPeriodo.value = optionTrimestral.value;

                    if (showAlert) {
                        Swal.fire({
                            title: 'Ajuste Automático',
                            text: 'Al ser un Centro de Trabajo BGNE, el tipo de periodo se ha configurado forzosamente como TRIMESTRAL.',
                            icon: 'info',
                            confirmButtonColor: 'rgb(38, 104, 123)'
                        });
                    }
                }
                selectPeriodo.disabled = true;
            }
        } else {
            chkDiv.style.display = 'none';
            chk.checked = false;
            inputFin.readOnly = false;
            selectPeriodo.disabled = false;
        }
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
        checkCt(true);
        calculateEndDate();
    });

    chk.addEventListener('change', calculateEndDate);
    inputInicio.addEventListener('change', calculateEndDate);
    inputInicio.addEventListener('input', calculateEndDate);

    // Inicializar para cuando los valores ya estén pre-cargados (sin alerta)
    checkCt(false);

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
        return new Date(fecha).toLocaleDateString();
    }

    function renderTabla() {

        if (!grupos.length) {
            document.getElementById('tablaGrupos').innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-muted">
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

            html += `
                <tr>
                    <td>${grupo.clave}</td>
                    <td>${formatearFecha(grupo.fechaInicio)}</td>
                    <td>${formatearFecha(grupo.fechaFin)}</td>
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
                            form.id_nivel_academico.value = g.id_nivel_academico || '';
                            form.modalidadHorario.value = g.modalidadHorario || '';
                            if (form.statusGrupo) form.statusGrupo.value = g.statusGrupo || 'ACTIVO';

                            initializeModalEvents();

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

                            setFormDisabled(true);

                            form.clave.value = g.clave || '';
                            form.fechaInicio.value = formatDateForInput(g.fechaInicio);
                            form.fechaFin.value = formatDateForInput(g.fechaFin);
                            form.id_centroTrabajo.value = g.id_centroTrabajo || '';
                            form.id_planEstudios.value = g.id_planEstudios || '';
                            form.id_tipoPeriodo.value = g.id_tipoPeriodo || '';
                            form.id_nivel_academico.value = g.id_nivel_academico || '';
                            form.modalidadHorario.value = g.modalidadHorario || '';
                            if (form.statusGrupo) form.statusGrupo.value = g.statusGrupo || 'ACTIVO';

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

