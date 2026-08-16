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


    <div class="glass-header p-3 d-flex justify-content-between align-items-center">

        <h5 class="mb-0 textoDocentes">
            Lista de docentes
        </h5>

        <input type="text" id="buscador" class="form-control glass-input w-25" placeholder="Buscar docente...">

    </div>

    <br>



    <div class="glass-card">
        <div class="table-responsive">

            <table class="table glass-table align-middle mb-0">

                <thead class="table-head">
                    <tr>
                        <th>ID</th>
                        <th>NOMBRE</th>
                        <th>CORREO</th>
                        <th>TELÉFONO</th>
                        <th>ESTATUS</th>
                        <th>NIVEL</th>
                        <th>FECHA</th>
                        <th class="text-center">ACCIONES</th>
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

    @endsection
    <!-- MODAL -->
    <div class="modal fade" id="modalDocente" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
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

                        <div class="col-md-6">
                            <label class="form-label">Nombre de Usuario</label>
                            <input type="text" class="form-control form-control-premium" name="usuario" placeholder="Ej. juan.perez">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" id="lblFormDocentePassword">Contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-premium" name="password" id="formDocentePassword" placeholder="Dejar en blanco para no cambiar">
                                <button class="btn btn-outline-secondary" type="button" id="btnToggleFormDocentePassword" onclick="togglePasswordVisibility('formDocentePassword')">
                                    <i class="fa-solid fa-eye" id="toggleIcon_formDocentePassword"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control form-control-premium" name="observacionesDocente"
                                rows="4"></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-azul" data-bs-dismiss="modal">

                        Cancelar

                    </button>

                    <button class="btn btn-azul" type="submit">

                        <i class="bi bi-floppy-fill"></i>
                        Guardar Alumno

                    </button>

                </div>

            </form>
        </div>

    </div>
</div>

{{-- MODAL CREDENCIALES DOCENTE --}}
<div class="modal fade" id="modalCredencialesDocente" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formCredencialesDocente" class="modal-content glass-modal">
            <div class="modal-header">
                <h5 class="modal-title text-white">
                    <i class="fa-solid fa-user-lock me-2"></i>Credenciales de Acceso
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-dark bg-white" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
                <input type="hidden" id="credDocenteId" name="idDocente">
                
                <div class="mb-3">
                    <label class="form-label text-dark fw-bold">Docente:</label>
                    <div id="credDocenteNombre" class="fw-bold fs-6 text-primary bg-light p-2 rounded border">—</div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-dark fw-bold">Nombre de Usuario *</label>
                    <input type="text" class="form-control form-control-premium text-dark" id="credUsuario" name="usuario" required placeholder="Ej. juan.perez" style="color: #000000 !important; font-weight: 600;">
                    <small class="text-muted">Se sugiere usar minúsculas separadas por punto.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label text-dark fw-bold" id="lblPasswordCred">Contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control form-control-premium text-dark" id="credPassword" name="password" placeholder="Mínimo 4 caracteres" style="color: #000000 !important; font-weight: 600;">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('credPassword')">
                            <i class="fa-solid fa-eye" id="toggleIcon_credPassword"></i>
                        </button>
                    </div>
                    <small class="text-muted" id="helpPasswordCred">Deja en blanco para conservar la contraseña actual.</small>
                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="generarPasswordAleatoria()">
                        <i class="fa-solid fa-arrows-rotate me-1"></i> Generar Contraseña Segura
                    </button>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" style="background-color: #0f172a; border: none; font-weight: 600;">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Guardar Credenciales
                </button>
            </div>
        </form>
    </div>
</div>




{{-- ========================= JS ========================= --}}
<script>
document.addEventListener("DOMContentLoaded", function() {

    cargarDocentes();

    // =========================
    // LISTAR DOCENTES
    // =========================
    let listaDocentes = [];
    let paginaDocente = 1;
    const filasDocente = 13;

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
        const normalizeStr = str => (str || '').normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
        const filtro = normalizeStr(document.getElementById('buscador').value);

        const filtradas = listaDocentes.filter(d =>
            normalizeStr(d.nombreDocente).includes(filtro) ||
            normalizeStr(d.apPaternoDocente).includes(filtro) ||
            normalizeStr(d.apMaternoDocente).includes(filtro) ||
            normalizeStr(d.correoDocente).includes(filtro) ||
            normalizeStr(d.telefonoDocente).includes(filtro) ||
            normalizeStr(d.nivelEstudios).includes(filtro)
        );

        const inicio = (paginaDocente - 1) * filasDocente;
        const fin = inicio + filasDocente;
        const datos = filtradas.slice(inicio, fin);

        let html = '';

        datos.forEach(docente => {
            const nombreCompleto =
                `${docente.nombreDocente} ${docente.apPaternoDocente ?? ''} ${docente.apMaternoDocente ?? ''}`;

            let userBadge = '';
            if (docente.usuario) {
                userBadge = `<small class="text-primary d-block mt-1" style="font-size: 0.76rem;"><i class="fa-solid fa-user-shield me-1"></i>${docente.usuario}</small>`;
            } else {
                userBadge = `<small class="text-muted d-block mt-1" style="font-size: 0.74rem;"><i class="fa-solid fa-user-slash me-1"></i>Sin usuario</small>`;
            }

            html += `
                <tr>
                    <td>${docente.idDocente}</td>
                    <td>
                        <strong>${docente.nombreDocente} ${docente.apPaternoDocente ?? ''} ${docente.apMaternoDocente ?? ''}</strong>
                        ${userBadge}
                    </td>
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
                        <button class="btn btn-secondary btn-sm btn-action me-1" onclick="verDocente(${docente.idDocente})" title="Ver detalles">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button class="btn btn-warning btn-sm btn-action me-1" onclick="editarDocente(${docente.idDocente})" title="Editar">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="btn btn-primary btn-sm btn-action me-1" onclick="abrirModalCredenciales(${docente.idDocente}, '${docente.nombreDocente.replace(/'/g, "\\'")}', '${docente.apPaternoDocente ? docente.apPaternoDocente.replace(/'/g, "\\'") : ''}', '${docente.usuario ? docente.usuario.replace(/'/g, "\\'") : ''}', ${docente.tiene_password ?? 0})" title="Credenciales de acceso" style="background-color: #0f172a; border-color: #0f172a;">
                            <i class="fa-solid fa-key"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btn-action" onclick="eliminarDocente(${docente.idDocente})" title="Eliminar">
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
        form.idBiometrico.disabled = disabled;
        form.usuario.disabled = disabled;
        form.password.disabled = disabled;
        const btnTogglePass = document.getElementById('btnToggleFormDocentePassword');
        if (btnTogglePass) btnTogglePass.disabled = disabled;
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
            document.getElementById('lblFormDocentePassword').textContent = 'Contraseña';
            form.password.placeholder = 'Ingrese contraseña';
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
                    form.idBiometrico.value = d.idBiometrico || '';
                    form.usuario.value = d.usuario || '';
                    form.password.value = '';
                    document.getElementById('lblFormDocentePassword').textContent = 'Contraseña';
                    form.password.placeholder = 'No asignada';

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
                    form.idBiometrico.value = d.idBiometrico || '';
                    form.usuario.value = d.usuario || '';
                    form.password.value = '';
                    document.getElementById('lblFormDocentePassword').textContent = 'Nueva Contraseña';
                    form.password.placeholder = 'Dejar en blanco para conservar actual';

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
            fechaNacimiento: this.fechaNacimiento.value,
            idBiometrico: this.idBiometrico.value,
            usuario: this.usuario.value,
            password: this.password.value
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

    // ==========================================
    // GESTIÓN DE CREDENCIALES DE DOCENTES
    // ==========================================
    window.abrirModalCredenciales = function(id, nombre, paterno, usuario, tienePassword) {
        document.getElementById('credDocenteId').value = id;
        document.getElementById('credDocenteNombre').textContent = `${nombre} ${paterno}`;
        document.getElementById('credUsuario').value = usuario || (nombre.trim() + '.' + (paterno ? paterno.trim() : '')).toLowerCase().replace(/\s+/g, '').normalize("NFD").replace(/[\u0300-\u036f]/g, "");
        document.getElementById('credPassword').value = '';

        if (tienePassword) {
            document.getElementById('lblPasswordCred').textContent = 'Nueva Contraseña';
            document.getElementById('helpPasswordCred').textContent = 'Deja en blanco para conservar la contraseña actual.';
            document.getElementById('credPassword').required = false;
        } else {
            document.getElementById('lblPasswordCred').textContent = 'Contraseña *';
            document.getElementById('helpPasswordCred').textContent = 'Ingresa una contraseña para activar la cuenta de acceso.';
            document.getElementById('credPassword').required = true;
        }

        const modal = new bootstrap.Modal(document.getElementById('modalCredencialesDocente'));
        modal.show();
    };

    window.togglePasswordVisibility = function(id) {
        const inp = document.getElementById(id);
        const icon = document.getElementById('toggleIcon_' + id);
        if (inp.type === 'password') {
            inp.type = 'text';
            icon.className = 'fa-solid fa-eye-slash';
        } else {
            inp.type = 'password';
            icon.className = 'fa-solid fa-eye';
        }
    };

    window.generarPasswordAleatoria = function() {
        const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$";
        let pass = "";
        for (let i = 0; i < 8; i++) {
            pass += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        const inp = document.getElementById('credPassword');
        inp.value = pass;
        inp.type = 'text';
        document.getElementById('toggleIcon_credPassword').className = 'fa-solid fa-eye-slash';
    };

    document.getElementById('formCredencialesDocente').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('credDocenteId').value;
        const data = {
            usuario: document.getElementById('credUsuario').value.trim(),
            password: document.getElementById('credPassword').value
        };

        fetch(`/docentes/${id}/credenciales`, {
            method: 'POST',
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
                    text: 'Credenciales del docente actualizadas correctamente.',
                    confirmButtonColor: 'rgb(38, 104, 123)'
                });
                bootstrap.Modal.getInstance(document.getElementById('modalCredencialesDocente')).hide();
                cargarDocentes();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: resp.message || 'No se pudieron actualizar las credenciales.',
                    confirmButtonColor: 'rgb(38, 104, 123)'
                });
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de conexión al guardar credenciales.',
                confirmButtonColor: 'rgb(38, 104, 123)'
            });
        });
    });

});
</script>