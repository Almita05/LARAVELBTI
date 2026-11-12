@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/estilosDocentes.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="page-container">
    <div class="page-header-standard">
        <div class="page-header-left">
            <a href="{{ url()->previous() }}" class="btn btn-regresar">
                <i class="ti ti-arrow-left"></i>
                <span>Regresar</span>
            </a>
            <h1 class="page-heading">Listado de Docentes</h1>
        </div>

        <button type="button" class="btn btn-azul btn-primary-saas" data-bs-toggle="modal" data-bs-target="#modalDocente">
            <i class="ti ti-plus"></i>
            <span>Alta docente</span>
        </button>
    </div>

    <div class="glass-card">
        <div class="docentes-header-bar">
            <div class="docentes-search-wrapper">
                <i class="ti ti-search search-icon"></i>
                <input type="text" id="buscador" class="form-control form-control-search" placeholder="Buscar docente...">
            </div>
            <div id="infoDocentesBadge" class="docentes-info-text"></div>
        </div>

        <div class="table-responsive">
            <table class="table glass-table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 75px;">ID</th>
                        <th>NOMBRE</th>
                        <th>CORREO</th>
                        <th>TELÉFONO</th>
                        <th>ESTATUS</th>
                        <th>NIVEL</th>
                        <th>FECHA</th>
                        <th class="text-end" style="width: 175px; min-width: 175px; padding-right: 20px;">ACCIONES</th>
                    </tr>
                </thead>
                <tbody id="tablaDocentes"></tbody>
            </table>
        </div>

        <div class="glass-footer">
            <small id="infoPaginacion" class="docentes-info-text"></small>
            <div id="paginacion" class="docentes-pagination"></div>
        </div>
    </div>
</div>
@endsection
    <!-- MODAL -->
    <div class="modal fade" id="modalDocente" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form id="formDocente" class="modal-content glass-modal">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ti ti-chalkboard me-2" style="color: #1E5B66;"></i>Alta Docente
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                                    <i class="ti ti-eye" id="toggleIcon_formDocentePassword"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Color Exclusivo (Pastel)</label>
                            <div class="d-flex align-items-center gap-2">
                                <div id="pastelColorPalette" class="d-flex flex-wrap gap-1 align-items-center" style="max-width: 280px;"></div>
                                <div class="position-relative" style="width: 38px; height: 38px;">
                                    <input type="color" class="form-control form-control-color" name="colorDocente" id="formDocenteColor" value="#FFFFFF" style="width: 100%; height: 100%; padding: 0; border-radius: 50%; cursor: pointer; border: 2px solid #cbd5e1; background: none;" title="Color personalizado">
                                </div>
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

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button class="btn btn-azul btn-primary-saas" type="submit">
                        <i class="ti ti-device-floppy me-1"></i>
                        Guardar
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
                <h5 class="modal-title">
                    <i class="ti ti-key me-2" style="color: #1E5B66;"></i>Credenciales de Acceso
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                            <i class="ti ti-eye" id="toggleIcon_credPassword"></i>
                        </button>
                    </div>
                    <small class="text-muted" id="helpPasswordCred">Deja en blanco para conservar la contraseña actual.</small>
                </div>

                <div class="text-end mb-3">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="generarPasswordAleatoria()">
                        <i class="ti ti-refresh me-1"></i> Generar Contraseña Segura
                    </button>
                </div>

                <div class="mb-3 border-top pt-3">
                    <label class="form-label text-dark fw-bold">Módulos Habilitados (Rol Docente):</label>
                    <div class="p-3 rounded bg-light border">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="permisos_modulos[]" value="horarios" id="chkModuloHorarios" checked>
                            <label class="form-check-label text-dark fw-semibold" for="chkModuloHorarios" style="cursor: pointer;">
                                <i class="ti ti-calendar-stats me-1 text-primary"></i> Mi Horario
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="permisos_modulos[]" value="pendientes" id="chkModuloPendientes" checked>
                            <label class="form-check-label text-dark fw-semibold" for="chkModuloPendientes" style="cursor: pointer;">
                                <i class="ti ti-checkbox me-1 text-warning"></i> Pendientes
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permisos_modulos[]" value="grupos" id="chkModuloGrupos" checked>
                            <label class="form-check-label text-dark fw-semibold" for="chkModuloGrupos" style="cursor: pointer;">
                                <i class="ti ti-users-group me-1 text-success"></i> Mis Grupos (Calificaciones, Asistencias)
                            </label>
                        </div>
                    </div>
                    <small class="text-muted">Marca los módulos que el docente podrá visualizar en su barra lateral al iniciar sesión.</small>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" style="background-color: var(--brand-primary); border: none; font-weight: 600;">
                    <i class="ti ti-device-floppy me-1"></i> Guardar Credenciales
                </button>
            </div>
        </form>
    </div>
</div>




{{-- ========================= JS ========================= --}}
<script>
document.addEventListener("DOMContentLoaded", function() {

    cargarDocentes();
    if (typeof initPastelPalette === 'function') {
        initPastelPalette();
    }

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

    function formatearFechaDocente(dateStr) {
        if (!dateStr || dateStr === 'N/A' || dateStr === 'null' || dateStr === 'undefined') {
            return '<span style="color: #94A3B8; font-size: 0.8125rem;">N/A</span>';
        }
        try {
            const d = new Date(dateStr);
            if (isNaN(d.getTime())) {
                if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
                    const p = dateStr.split('-');
                    return `<span class="fecha-mono">${p[2]}/${p[1]}/${p[0]}</span>`;
                }
                return `<span class="fecha-mono">${dateStr}</span>`;
            }
            const isUtc = typeof dateStr === 'string' && (dateStr.includes('GMT') || dateStr.endsWith('Z') || /^\d{4}-\d{2}-\d{2}$/.test(dateStr));
            const day = String(isUtc ? d.getUTCDate() : d.getDate()).padStart(2, '0');
            const month = String((isUtc ? d.getUTCMonth() : d.getMonth()) + 1).padStart(2, '0');
            const year = isUtc ? d.getUTCFullYear() : d.getFullYear();
            return `<span class="fecha-mono">${day}/${month}/${year}</span>`;
        } catch (e) {
            return `<span class="fecha-mono">${dateStr}</span>`;
        }
    }

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
                `${docente.nombreDocente || ''} ${docente.apPaternoDocente ?? ''} ${docente.apMaternoDocente ?? ''}`.trim();

            let userBadge = '';
            if (docente.usuario) {
                userBadge = `<div class="docente-user-badge"><i class="ti ti-user-check"></i><span>${docente.usuario}</span></div>`;
            } else {
                userBadge = `<div class="docente-no-user"><i class="ti ti-user-off"></i><span>Sin usuario</span></div>`;
            }

            const colorCircle = `<span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background-color: ${docente.colorDocente || '#94A3B8'}; border: 1px solid rgba(0,0,0,0.12); margin-right: 6px; vertical-align: middle;" title="Color del Docente"></span>`;

            const isActivo = (docente.statusDocente || 'ACTIVO').toUpperCase() === 'ACTIVO';
            const statusBadge = isActivo
                ? `<span class="badge-soft-success"><span class="badge-dot"></span>Activo</span>`
                : `<span class="badge-soft-danger"><span class="badge-dot"></span>Inactivo</span>`;

            const nivelTexto = (docente.nivelEstudios && docente.nivelEstudios !== 'N/A')
                ? `<span class="docente-nivel">${docente.nivelEstudios}</span>`
                : `<span style="color: #94A3B8; font-size: 0.8125rem;">N/A</span>`;

            html += `
                <tr>
                    <td><span class="id-mono">${docente.idDocente}</span></td>
                    <td>
                        <div class="d-flex align-items-center">
                            ${colorCircle}
                            <span class="docente-nombre">${nombreCompleto}</span>
                        </div>
                        ${userBadge}
                    </td>
                    <td><span class="docente-correo">${docente.correoDocente || '—'}</span></td>
                    <td><span class="docente-telefono">${docente.telefonoDocente || '—'}</span></td>
                    <td>${statusBadge}</td>
                    <td>${nivelTexto}</td>
                    <td>${formatearFechaDocente(docente.fechaNacimiento)}</td>
                    <td class="text-end" style="padding-right: 20px;">
                        <div class="actions-cell" style="justify-content: flex-end;">
                            <button type="button" class="btn-ghost-icon" onclick="verDocente(${docente.idDocente})" title="Ver detalles">
                                <i class="ti ti-eye"></i>
                            </button>
                            <button type="button" class="btn-ghost-icon" onclick="editarDocente(${docente.idDocente})" title="Editar docente">
                                <i class="ti ti-edit"></i>
                            </button>
                            <button type="button" class="btn-ghost-icon btn-ghost-key" onclick="abrirModalCredenciales(${docente.idDocente}, '${(docente.nombreDocente || '').replace(/'/g, "\\'")}', '${docente.apPaternoDocente ? docente.apPaternoDocente.replace(/'/g, "\\'") : ''}', '${docente.usuario ? docente.usuario.replace(/'/g, "\\'") : ''}', ${docente.tiene_password ?? 0}, '${docente.permisos_modulos || ''}')" title="Credenciales de acceso">
                                <i class="ti ti-key"></i>
                            </button>
                            <button type="button" class="btn-ghost-icon btn-ghost-danger" onclick="eliminarDocente(${docente.idDocente})" title="Eliminar docente">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        document.getElementById('tablaDocentes').innerHTML = html;
        renderPaginacionDocentes(filtradas.length, inicio, fin);
    }

    function renderPaginacionDocentes(total, inicio, fin) {
        const totalPaginas = Math.ceil(total / filasDocente);
        let html = '';

        for (let i = 1; i <= totalPaginas; i++) {
            html += `
                <button type="button" class="btn-page ${i === paginaDocente ? 'active' : ''}"
                    onclick="cambiarPaginaDocente(${i})">
                    ${i}
                </button>`;
        }

        document.getElementById('paginacion').innerHTML = html;
        const textoInfo = total > 0 
            ? `Mostrando ${inicio + 1} a ${Math.min(fin, total)} de ${total} docentes`
            : 'Sin registros';
        document.getElementById('infoPaginacion').innerText = textoInfo;

        const infoBadge = document.getElementById('infoDocentesBadge');
        if (infoBadge) {
            infoBadge.innerText = total > 0 ? `${total} docente${total === 1 ? '' : 's'}` : '';
        }
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
        if (form.colorDocente) form.colorDocente.disabled = disabled;
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
            if (typeof selectPastelColor === 'function') {
                const randomPastel = pastelColors[Math.floor(Math.random() * pastelColors.length)];
                selectPastelColor(randomPastel);
            }
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
                    form.colorDocente.value = d.colorDocente || '#FFFFFF';
                    if (typeof selectPastelColor === 'function') {
                        selectPastelColor(d.colorDocente || '#FFFFFF');
                    }

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
                    form.colorDocente.value = d.colorDocente || '#FFFFFF';
                    if (typeof selectPastelColor === 'function') {
                        selectPastelColor(d.colorDocente || '#FFFFFF');
                    }

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
            password: this.password.value,
            colorDocente: this.colorDocente.value
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
    // GESTIÓN DE COLORES PASTEL DE DOCENTES
    // ==========================================
    const pastelColors = [
        '#FFD6D6', '#D6E4FF', '#D4EDDA', '#F3E5F5', '#FFF3CD', 
        '#FFE8D6', '#D1ECF1', '#FAD2E1', '#E8F5E9', '#ECEFF1', 
        '#C5D3E8', '#E8DFF5'
    ];

    window.initPastelPalette = function() {
        const palette = document.getElementById('pastelColorPalette');
        if (!palette) return;
        palette.innerHTML = '';
        
        pastelColors.forEach(color => {
            const bubble = document.createElement('div');
            bubble.className = 'color-bubble';
            bubble.style.width = '24px';
            bubble.style.height = '24px';
            bubble.style.borderRadius = '50%';
            bubble.style.backgroundColor = color;
            bubble.style.cursor = 'pointer';
            bubble.style.border = '1px solid #cbd5e1';
            bubble.style.transition = 'all 0.2s';
            bubble.title = color;
            
            bubble.addEventListener('click', () => {
                const form = document.getElementById('formDocente');
                if (form.nombreDocente.disabled) return; // View mode, disabled
                selectPastelColor(color);
            });
            
            palette.appendChild(bubble);
        });
    }

    window.selectPastelColor = function(color) {
        const input = document.getElementById('formDocenteColor');
        if (input) {
            input.value = color;
        }
        
        const bubbles = document.querySelectorAll('#pastelColorPalette .color-bubble');
        bubbles.forEach(b => {
            if (b.title.toUpperCase() === color.toUpperCase()) {
                b.style.border = '2.5px solid #26687b';
                b.style.transform = 'scale(1.15)';
            } else {
                b.style.border = '1px solid #cbd5e1';
                b.style.transform = 'scale(1)';
            }
        });
    }

    document.getElementById('formDocenteColor')?.addEventListener('input', function() {
        const color = this.value;
        const bubbles = document.querySelectorAll('#pastelColorPalette .color-bubble');
        bubbles.forEach(b => {
            if (b.title.toUpperCase() === color.toUpperCase()) {
                b.style.border = '2.5px solid #26687b';
                b.style.transform = 'scale(1.15)';
            } else {
                b.style.border = '1px solid #cbd5e1';
                b.style.transform = 'scale(1)';
            }
        });
    });

    // ==========================================
    // GESTIÓN DE CREDENCIALES DE DOCENTES
    // ==========================================
    window.abrirModalCredenciales = function(id, nombre, paterno, usuario, tienePassword, permisosModulos) {
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

        // Marcar checkboxes según permisos guardados
        const modulos = (permisosModulos || 'horarios,pendientes,grupos').split(',');
        document.getElementById('chkModuloHorarios').checked = modulos.includes('horarios');
        document.getElementById('chkModuloPendientes').checked = modulos.includes('pendientes');
        document.getElementById('chkModuloGrupos').checked = modulos.includes('grupos');

        const modal = new bootstrap.Modal(document.getElementById('modalCredencialesDocente'));
        modal.show();
    };

    window.togglePasswordVisibility = function(id) {
        const inp = document.getElementById(id);
        const icon = document.getElementById('toggleIcon_' + id);
        if (inp.type === 'password') {
            inp.type = 'text';
            icon.className = 'ti ti-eye-off';
        } else {
            inp.type = 'password';
            icon.className = 'ti ti-eye';
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
        const toggleIcon = document.getElementById('toggleIcon_credPassword');
        if (toggleIcon) toggleIcon.className = 'ti ti-eye-off';
    };

    document.getElementById('formCredencialesDocente').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('credDocenteId').value;

        const permisos_modulos = [];
        if (document.getElementById('chkModuloHorarios').checked) permisos_modulos.push('horarios');
        if (document.getElementById('chkModuloPendientes').checked) permisos_modulos.push('pendientes');
        if (document.getElementById('chkModuloGrupos').checked) permisos_modulos.push('grupos');

        const data = {
            usuario: document.getElementById('credUsuario').value.trim(),
            password: document.getElementById('credPassword').value,
            permisos_modulos: permisos_modulos
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