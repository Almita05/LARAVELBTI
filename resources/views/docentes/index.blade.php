@extends('layouts.app')

@section('content')

<style>
.glass-modal {
    background: rgb(73, 164, 190) !important;
    border: 1px solid rgba(255, 255, 255, .15);
    border-radius: 20px;
    overflow: hidden;
    color: white;
    backdrop-filter: blur(12px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, .35);
}

.glass-modal .modal-header {
    background: linear-gradient(135deg, rgb(73, 164, 190), #1E6FA8);
    color: #fff;
}

.glass-modal .modal-body {
    background: white;
}

.glass-modal .modal-footer {
    border-top: 1px solid rgba(255, 255, 255, .08);
    background: rgba(255, 255, 255, .03);
}

.accordion-item {
    background: rgba(255, 255, 255, .06);
    border: none;
    border-radius: 14px;
    overflow: hidden;
}

.accordion-button,
.accordion-button.collapsed,
.accordion-button:not(.collapsed) {
    background: linear-gradient(135deg, rgb(73, 164, 190), #1E6FA8) !important;
    color: #fff !important;
    font-weight: 600;
    box-shadow: none !important;
    border: none;
}

.accordion-button:focus {
    box-shadow: none !important;
}

.accordion-button::after {
    filter: brightness(0) invert(1);
}

.accordion-body {
    background: white;
}

.form-label {
    font-weight: 600;
    color: black;
}

.form-control-premium,
.form-select-premium {
    background: #fff;
    border: 2px solid #9BDFFF;
    border-radius: 15px;
    color: #212529;
    min-height: 48px;
}

.form-control-premium:hover,
.form-select-premium:hover {
    border-color: #7FD3FF;
}

.form-control-premium:focus,
.form-select-premium:focus {
    border-color: #66C9FF;
    box-shadow:
        0 0 0 3px rgba(102, 201, 255, .20),
        inset 0 1px 2px rgba(0, 0, 0, .04);
}
</style>

<head>
    <link rel="stylesheet" href="{{ asset('css/estilosDocentes.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<div class="page-container">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <a href="{{ url()->previous() }}" class="btn btn-regresar">
            <i class="fa-solid fa-arrow-left me-2"></i>
            Regresar
        </a>

        <h3 class="page-title mb-0">
            <i class="fa-solid fa-chalkboard-user me-2"></i>
            Listado de docentes
        </h3>

        <button class="btn btn-azul" data-bs-toggle="modal" data-bs-target="#modalDocente">
            <i class="fa-solid fa-plus me-2"></i>
            Alta docente
        </button>

    </div>

    <div class="glass-card">

        <div class="glass-header p-3 d-flex justify-content-between align-items-center">

            <h5 class="mb-0 textoDocentes">
                Lista de docentes
            </h5>

            <input type="text" id="buscador" class="form-control glass-input w-25" placeholder="Buscar docente...">

        </div>


        <div class="table-responsive">

            <table class="table table-borderless glass-table align-middle mb-0">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Status</th>
                        <th>Nivel</th>
                        <th>Fecha</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody id="tablaDocentes"></tbody>

            </table>

        </div>


        <div class="glass-footer p-3 d-flex justify-content-between align-items-center">
            <small id="infoPaginacion"></small>
            <div id="paginacion"></div>
        </div>

    </div>

    <!-- MODAL -->
    <div class="modal fade" id="modalDocente" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form id="formDocente" class="modal-content glass-modal">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-chalkboard-teacher me-2"></i>Alta Docente
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control form-control-premium" name="nombreDocente" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Apellido Paterno</label>
                            <input type="text" class="form-control form-control-premium" name="apPaternoDocente"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Apellido Materno</label>
                            <input type="text" class="form-control form-control-premium" name="apMaternoDocente">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control form-control-premium" name="correoDocente" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" class="form-control form-control-premium" name="telefonoDocente">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select form-select-premium" name="statusDocente">
                                <option value="ACTIVO">ACTIVO</option>
                                <option value="INACTIVO">INACTIVO</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nivel de Estudios</label>
                            <input type="text" class="form-control form-control-premium" name="nivelEstudios">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control form-control-premium" name="fechaNacimiento">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">ID Biométrico</label>
                            <input type="text" class="form-control form-control-premium" name="idBiometrico">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control form-control-premium" name="observacionesDocente"
                                rows="4"></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">

                        Cancelar

                    </button>

                    <button class="btn btn-outline-light" type="submit">

                        <i class="bi bi-floppy-fill"></i>
                        Guardar Alumno

                    </button>

                </div>

            </form>
        </div>
    </div>
</div>

@endsection


{{-- ========================= JS ========================= --}}
<script>
document.addEventListener("DOMContentLoaded", function() {

    cargarDocentes();

    // =========================
    // LISTAR DOCENTES
    // =========================
    let listaDocentes = [];
    let paginaDocente = 1;
    const filasDocente = 5;

    function cargarDocentes() {
        fetch('/docentes/lista')
            .then(res => res.json())
            .then(data => {
                listaDocentes = Array.isArray(data.data) ? data.data : [];
                renderDocentes();
            })
            .catch(err => console.log(err));
    }

    // 🔍 BUSCADOR
    document.getElementById('buscador')?.addEventListener('input', () => {
        paginaDocente = 1;
        renderDocentes();
    });

    function renderDocentes() {
        const filtro = document.getElementById('buscador').value.toLowerCase();

        const filtradas = listaDocentes.filter(d =>
            d.nombreDocente.toLowerCase().includes(filtro) ||
            (d.apPaternoDocente && d.apPaternoDocente.toLowerCase().includes(filtro)) ||
            (d.apMaternoDocente && d.apMaternoDocente.toLowerCase().includes(filtro)) ||
            (d.correoDocente && d.correoDocente.toLowerCase().includes(filtro)) ||
            (d.telefonoDocente && d.telefonoDocente.toLowerCase().includes(filtro)) ||
            (d.nivelEstudios && d.nivelEstudios.toLowerCase().includes(filtro))
        );

        const inicio = (paginaDocente - 1) * filasDocente;
        const fin = inicio + filasDocente;
        const datos = filtradas.slice(inicio, fin);

        let html = '';

        datos.forEach(docente => {
            const nombreCompleto =
                `${docente.nombreDocente} ${docente.apPaternoDocente ?? ''} ${docente.apMaternoDocente ?? ''}`;

            html += `
                <tr>
                    <td>${docente.idDocente}</td>
                    <td>${nombreCompleto}</td>
                    <td>${docente.correoDocente}</td>
                    <td>${docente.telefonoDocente ?? ''}</td>
                    <td>
                        <span class="badge ${docente.statusDocente === 'ACTIVO' ? 'bg-success' : 'bg-danger'}">
                            ${docente.statusDocente}
                        </span>
                    </td>
                    <td>${docente.nivelEstudios ?? 'N/A'}</td>
                    <td>${docente.fechaNacimiento ?? 'N/A'}</td>

                    <td>
                        <button class="btn btn-secondary btn-sm btn-action me-1" onclick="verDocente(${docente.idDocente})">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button class="btn btn-warning btn-sm btn-action me-1" onclick="editarDocente(${docente.idDocente})">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btn-action" onclick="eliminarDocente(${docente.idDocente})">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
                `;
        });

        document.getElementById('tablaDocentes').innerHTML = html;
        renderPaginacionDocentes(filtradas.length);
    }

    function renderPaginacionDocentes(total) {
        const totalPaginas = Math.ceil(total / filasDocente);
        let html = '';

        for (let i = 1; i <= totalPaginas; i++) {
            html += `
                <button class="btn btn-sm ${i === paginaDocente ? 'btn-primary' : 'btn-outline-primary'} me-1"
                    onclick="cambiarPaginaDocente(${i})">
                    ${i}
                </button>`;
        }

        document.getElementById('paginacion').innerHTML = html;
        document.getElementById('infoPaginacion').innerText = `Mostrando ${total} registros`;
    }

    window.cambiarPaginaDocente = function(p) {
        paginaDocente = p;
        renderDocentes();
    }

    let modoDocente = 'crear'; // 'crear', 'editar', 'ver'
    let idDocenteActual = null;

    function setFormDisabled(disabled) {
        const form = document.getElementById('formDocente');
        form.nombreDocente.disabled = disabled;
        form.apPaternoDocente.disabled = disabled;
        form.apMaternoDocente.disabled = disabled;
        form.correoDocente.disabled = disabled;
        form.telefonoDocente.disabled = disabled;
        form.statusDocente.disabled = disabled;
        form.observacionesDocente.disabled = disabled;
        form.nivelEstudios.disabled = disabled;
        form.fechaNacimiento.disabled = disabled;
    }

    // Reset modal to create mode when Alta button is clicked
    const btnAlta = document.querySelector('[data-bs-target="#modalDocente"]');
    if (btnAlta) {
        btnAlta.addEventListener('click', function() {
            modoDocente = 'crear';
            idDocenteActual = null;
            const form = document.getElementById('formDocente');
            form.reset();
            setFormDisabled(false);
            document.querySelector('.modal-title').textContent = 'Alta Docente';
            const submitBtn = document.querySelector('#formDocente button[type="submit"]');
            submitBtn.style.display = 'block';
            submitBtn.textContent = 'Guardar';
        });
    }

    window.verDocente = function(id) {
        modoDocente = 'ver';
        idDocenteActual = id;

        fetch(`/docentes/${id}`)
            .then(res => res.json())
            .then(resp => {
                if (resp.success && resp.data) {
                    const d = resp.data;
                    const form = document.getElementById('formDocente');
                    form.nombreDocente.value = d.nombreDocente || '';
                    form.apPaternoDocente.value = d.apPaternoDocente || '';
                    form.apMaternoDocente.value = d.apMaternoDocente || '';
                    form.correoDocente.value = d.correoDocente || '';
                    form.telefonoDocente.value = d.telefonoDocente || '';
                    form.statusDocente.value = d.statusDocente || 'ACTIVO';
                    form.observacionesDocente.value = d.observacionesDocente || '';
                    form.nivelEstudios.value = d.nivelEstudios || '';
                    form.fechaNacimiento.value = d.fechaNacimiento || '';

                    setFormDisabled(true);

                    document.querySelector('.modal-title').textContent = 'Detalles de Docente';
                    const submitBtn = document.querySelector('#formDocente button[type="submit"]');
                    submitBtn.style.display = 'none';

                    const modal = new bootstrap.Modal(document.getElementById('modalDocente'));
                    modal.show();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al cargar detalles del docente.',
                        confirmButtonColor: 'rgb(38, 104, 123)'
                    });
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar detalles del docente.',
                    confirmButtonColor: 'rgb(38, 104, 123)'
                });
            });
    }

    window.editarDocente = function(id) {
        modoDocente = 'editar';
        idDocenteActual = id;

        fetch(`/docentes/${id}`)
            .then(res => res.json())
            .then(resp => {
                if (resp.success && resp.data) {
                    const d = resp.data;
                    const form = document.getElementById('formDocente');
                    form.nombreDocente.value = d.nombreDocente || '';
                    form.apPaternoDocente.value = d.apPaternoDocente || '';
                    form.apMaternoDocente.value = d.apMaternoDocente || '';
                    form.correoDocente.value = d.correoDocente || '';
                    form.telefonoDocente.value = d.telefonoDocente || '';
                    form.statusDocente.value = d.statusDocente || 'ACTIVO';
                    form.observacionesDocente.value = d.observacionesDocente || '';
                    form.nivelEstudios.value = d.nivelEstudios || '';
                    form.fechaNacimiento.value = d.fechaNacimiento || '';

                    setFormDisabled(false);

                    document.querySelector('.modal-title').textContent = 'Editar Docente';
                    const submitBtn = document.querySelector('#formDocente button[type="submit"]');
                    submitBtn.style.display = 'block';
                    submitBtn.textContent = 'Actualizar';

                    const modal = new bootstrap.Modal(document.getElementById('modalDocente'));
                    modal.show();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al cargar detalles del docente.',
                        confirmButtonColor: 'rgb(38, 104, 123)'
                    });
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar detalles del docente.',
                    confirmButtonColor: 'rgb(38, 104, 123)'
                });
            });
    }

    window.eliminarDocente = function(id) {
        Swal.fire({
            title: '¿Deseas eliminar este docente?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: 'rgb(38, 104, 123)',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/docentes/${id}`, {
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
                                    'Docente eliminado correctamente.',
                                confirmButtonColor: 'rgb(38, 104, 123)'
                            });
                            cargarDocentes();
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
                            text: 'Error al eliminar docente.',
                            confirmButtonColor: 'rgb(38, 104, 123)'
                        });
                    });
            }
        });
    }

    // =========================
    // INSERTAR / ACTUALIZAR DOCENTE
    // =========================
    document.getElementById('formDocente').addEventListener('submit', function(e) {
        e.preventDefault();

        const data = {
            nombreDocente: this.nombreDocente.value,
            apPaternoDocente: this.apPaternoDocente.value,
            apMaternoDocente: this.apMaternoDocente.value,
            correoDocente: this.correoDocente.value,
            telefonoDocente: this.telefonoDocente.value,
            statusDocente: this.statusDocente.value,
            observacionesDocente: this.observacionesDocente.value,
            nivelEstudios: this.nivelEstudios.value,
            fechaNacimiento: this.fechaNacimiento.value
        };

        const url = modoDocente === 'editar' ? `/docentes/${idDocenteActual}` : '/docentes';
        const method = modoDocente === 'editar' ? 'PUT' : 'POST';

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
                        text: modoDocente === 'editar' ?
                            "Docente actualizado correctamente." :
                            "Docente guardado correctamente.",
                        confirmButtonColor: 'rgb(38, 104, 123)'
                    });

                    // cerrar modal
                    bootstrap.Modal.getInstance(document.getElementById('modalDocente')).hide();

                    // limpiar form
                    this.reset();

                    // recargar tabla
                    cargarDocentes();

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al guardar docente.',
                        confirmButtonColor: 'rgb(38, 104, 123)'
                    });
                }
            })
            .catch(err => {
                console.log(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error inesperado al procesar la solicitud.',
                    confirmButtonColor: 'rgb(38, 104, 123)'
                });
            });
    });

});
</script>