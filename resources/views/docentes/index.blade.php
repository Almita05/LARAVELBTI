@extends('layouts.app')

@section('content')

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
            <form id="formDocente" class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Alta Docente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6 mb-2">
                            <label>Nombre</label>
                            <input type="text" class="form-control" name="nombreDocente" required>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Apellido Paterno</label>
                            <input type="text" class="form-control" name="apPaternoDocente" required>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Apellido Materno</label>
                            <input type="text" class="form-control" name="apMaternoDocente">
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Correo</label>
                            <input type="email" class="form-control" name="correoDocente" required>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Teléfono</label>
                            <input type="text" class="form-control" name="telefonoDocente">
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Status</label>
                            <select class="form-control" name="statusDocente">
                                <option value="ACTIVO">ACTIVO</option>
                                <option value="INACTIVO">INACTIVO</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Nivel de Estudios</label>
                            <input type="text" class="form-control" name="nivelEstudios">
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Fecha de Nacimiento</label>
                            <input type="date" class="form-control" name="fechaNacimiento">
                        </div>

                        <div class="col-12 mb-2">
                            <label>Observaciones</label>
                            <textarea class="form-control" name="observacionesDocente"></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Guardar</button>
                </div>

            </form>
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
        function cargarDocentes() {

            fetch('/docentes/lista')
                .then(res => res.json())
                .then(data => {

                    let html = '';

                    data.data.forEach(docente => {

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
                })
                .catch(err => console.log(err));
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
                            confirmButtonColor: 'rgb(38, 104, 123)',
                            background: '#111e25',
                            color: '#fff'
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al cargar detalles del docente.',
                        confirmButtonColor: 'rgb(38, 104, 123)',
                        background: '#111e25',
                        color: '#fff'
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
                            confirmButtonColor: 'rgb(38, 104, 123)',
                            background: '#111e25',
                            color: '#fff'
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al cargar detalles del docente.',
                        confirmButtonColor: 'rgb(38, 104, 123)',
                        background: '#111e25',
                        color: '#fff'
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
                cancelButtonText: 'Cancelar',
                background: '#111e25',
                color: '#fff'
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
                                text: data.message || 'Docente eliminado correctamente.',
                                confirmButtonColor: 'rgb(38, 104, 123)',
                                background: '#111e25',
                                color: '#fff'
                            });
                            cargarDocentes();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Error al eliminar.',
                                confirmButtonColor: 'rgb(38, 104, 123)',
                                background: '#111e25',
                                color: '#fff'
                            });
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al eliminar docente.',
                            confirmButtonColor: 'rgb(38, 104, 123)',
                            background: '#111e25',
                            color: '#fff'
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
                            text: modoDocente === 'editar' ? "Docente actualizado correctamente." : "Docente guardado correctamente.",
                            confirmButtonColor: 'rgb(38, 104, 123)',
                            background: '#111e25',
                            color: '#fff'
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
                            confirmButtonColor: 'rgb(38, 104, 123)',
                            background: '#111e25',
                            color: '#fff'
                        });
                    }
                })
                .catch(err => {
                    console.log(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error inesperado al procesar la solicitud.',
                        confirmButtonColor: 'rgb(38, 104, 123)',
                        background: '#111e25',
                        color: '#fff'
                    });
                });
        });

    });
    </script>