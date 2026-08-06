@extends('layouts.app')

@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<div class="page-container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ url()->previous() }}" class="btn btn-azul">
                <i class="fa-solid fa-arrow-left me-2"></i>
                Regresar
            </a>
        </div>
        <h3 class="page-title">
        </h3>

        <button class="btn btn-azul" onclick="abrirModalAlumno()">
            <i class="fa-solid fa-plus me-2"></i>
            Alta alumno
        </button>
    </div>

    <div class="glass-card">

        <div class="glass-header p-3 d-flex justify-content-between align-items-center">

            <h5 class="mb-0" id="tituloListaAlumnos">
                @if(isset($grupoId))
                    Alumnos del Grupo: <strong>{{ $grupoClave }}</strong>
                @else
                    Lista de alumnos
                @endif
            </h5>

            <div class="input-group w-25" id="contenedorFiltroGeneracion">
                <select id="filtroBGNE" class="form-select glass-input">
                    <option value="" class="valueGeneraciones">Todas las generaciones</option>
                    @foreach($generaciones as $generacion)
                    <option value="{{ $generacion['generacion'] }}" class="valueGeneraciones">
                        Generación {{ $generacion['generacion'] }} - {{ $generacion['nombreGeneracion'] }}
                    </option>
                    @endforeach
                </select>
                <button class="btn filtro-btn" type="button">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </div>

            <!-- Búsqueda por texto -->
            <input type="text" id="buscadorAlumnos" class="form-control glass-input w-25"
                placeholder="Buscar alumnos...">

        </div>

        <div class="table-responsive">

            <div id="loading" class="text-center py-4" style="display:none;">
                <div class="spinner-border text-light"></div>
            </div>

            <table class="table table-borderless glass-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Grupo</th>
                        <th>Generación</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaAlumnos"></tbody>
            </table>

        </div>

        <div class="glass-footer p-3 d-flex justify-content-between align-items-center">
            <small id="infoPaginacionMaterias"></small>
            <div id="paginacionMaterias"></div>
        </div>

    </div>

</div>

<div id="contenedorModal"></div>
@endsection

<script>
let modoAlumno = 'crear';
let idAlumnoActual = null;
let grupoId = @json($grupoId ?? null);

function setFormDisabled(disabled) {
    const form = document.getElementById('formAlumno');
    if (!form) return;
    Array.from(form.querySelectorAll('input, select, textarea')).forEach(el => {
        el.disabled = disabled;
    });
}

function formatDateForInput(dateStr) {
    if (!dateStr) return '';
    try {
        const d = new Date(dateStr);
        if (isNaN(d.getTime())) return '';
        const yyyy = d.getFullYear();
        const mm = String(d.getMonth() + 1).padStart(2, '0');
        const dd = String(d.getDate()).padStart(2, '0');
        return `${yyyy}-${mm}-${dd}`;
    } catch(e) {
        return '';
    }
}

function getStatusBadge(status) {
    status = (status || 'ACTIVO').toUpperCase();
    switch (status) {
        case 'ACTIVO':
            return '<span class="badge bg-success" style="font-size: 0.75rem; padding: 5px 10px; border-radius: 12px;">ACTIVO</span>';
        case 'INACTIVO':
            return '<span class="badge bg-secondary" style="font-size: 0.75rem; padding: 5px 10px; border-radius: 12px;">INACTIVO</span>';
        case 'BAJA_TEMPORAL':
            return '<span class="badge bg-warning text-dark" style="font-size: 0.75rem; padding: 5px 10px; border-radius: 12px;">BAJA TEMPORAL</span>';
        case 'CERTIFICADO':
            return '<span class="badge bg-info text-dark" style="font-size: 0.75rem; padding: 5px 10px; border-radius: 12px;">CERTIFICADO</span>';
        case 'REINSCRIPCION':
            return '<span class="badge bg-primary" style="font-size: 0.75rem; padding: 5px 10px; border-radius: 12px;">REINSCRIPCIÓN</span>';
        default:
            return `<span class="badge bg-secondary" style="font-size: 0.75rem; padding: 5px 10px; border-radius: 12px;">${status}</span>`;
    }
}

window.verAlumno = function(id) {
    modoAlumno = 'ver';
    idAlumnoActual = id;

    fetch('/alumnos/modalAlta?_t=' + Date.now())
        .then(res => res.text())
        .then(html => {
            document.getElementById('contenedorModal').innerHTML = html;
            setFormDisabled(true);

            fetch(`/alumnos/${id}`)
                .then(res => res.json())
                .then(resp => {
                    if (resp.success && resp.data) {
                        const al = resp.data;
                        const form = document.getElementById('formAlumno');
                        
                        const fields = [
                            'nombre', 'apPaterno', 'apMaterno', 'fechaNacimiento', 'celularAlumno',
                            'correoAlumno', 'tutor', 'parentesco', 'telefonoTutor', 'calle',
                            'colonia', 'localidad', 'municipio', 'escuelaProcedencia', 'observaciones',
                            'equivalencia', 'numeroControl', 'statusAlumno',
                            'curp', 'folioCertificado', 'fechaRecogioCertificado', 'recogioCertificado'
                        ];
                        fields.forEach(field => {
                            const input = form.querySelector(`[name="${field}"]`);
                            if (input) {
                                if (field === 'fechaNacimiento' || field === 'fechaRecogioCertificado') {
                                    input.value = formatDateForInput(al[field]);
                                } else {
                                    input.value = al[field] || '';
                                }
                            }
                        });

                        const idGrupoInput = form.querySelector('[name="id_Grupo"]');
                        if (idGrupoInput) idGrupoInput.value = al.idGrupo || '';

                        const idGenInput = form.querySelector('[name="id_Generacion"]');
                        if (idGenInput) idGenInput.value = al.idGeneracion || '';

                        if (typeof window.actualizarVistaCertificado === 'function') {
                            window.actualizarVistaCertificado();
                        }
                        
                        document.querySelector('.modal-title').innerHTML = '<i class="bi bi-person-fill me-2"></i> Detalles del Alumno';
                        const submitBtn = document.querySelector('#formAlumno button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.style.display = 'none';
                        }

                        let modal = new bootstrap.Modal(document.getElementById('modalAlumno'));
                        modal.show();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al cargar datos del alumno.',
                            confirmButtonColor: 'rgb(38, 104, 123)'
                        });
                    }
                });
        });
}

window.editarAlumno = function(id) {
    modoAlumno = 'editar';
    idAlumnoActual = id;

    fetch('/alumnos/modalAlta?_t=' + Date.now())
        .then(res => res.text())
        .then(html => {
            document.getElementById('contenedorModal').innerHTML = html;
            setFormDisabled(false);

            fetch(`/alumnos/${id}`)
                .then(res => res.json())
                .then(resp => {
                    if (resp.success && resp.data) {
                        const al = resp.data;
                        const form = document.getElementById('formAlumno');
                        
                        const fields = [
                            'nombre', 'apPaterno', 'apMaterno', 'fechaNacimiento', 'celularAlumno',
                            'correoAlumno', 'tutor', 'parentesco', 'telefonoTutor', 'calle',
                            'colonia', 'localidad', 'municipio', 'escuelaProcedencia', 'observaciones',
                            'equivalencia', 'numeroControl', 'statusAlumno',
                            'curp', 'folioCertificado', 'fechaRecogioCertificado', 'recogioCertificado'
                        ];
                        fields.forEach(field => {
                            const input = form.querySelector(`[name="${field}"]`);
                            if (input) {
                                if (field === 'fechaNacimiento' || field === 'fechaRecogioCertificado') {
                                    input.value = formatDateForInput(al[field]);
                                } else {
                                    input.value = al[field] || '';
                                }
                            }
                        });

                        const idGrupoInput = form.querySelector('[name="id_Grupo"]');
                        if (idGrupoInput) idGrupoInput.value = al.idGrupo || '';

                        const idGenInput = form.querySelector('[name="id_Generacion"]');
                        if (idGenInput) idGenInput.value = al.idGeneracion || '';

                        if (typeof window.actualizarVistaCertificado === 'function') {
                            window.actualizarVistaCertificado();
                        }
                        
                        document.querySelector('.modal-title').innerHTML = '<i class="bi bi-pencil-square me-2"></i> Editar Alumno';
                        const submitBtn = document.querySelector('#formAlumno button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.innerHTML = '<i class="bi bi-floppy-fill"></i> Actualizar Alumno';
                        }

                        let modal = new bootstrap.Modal(document.getElementById('modalAlumno'));
                        modal.show();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al cargar datos del alumno.',
                            confirmButtonColor: 'rgb(38, 104, 123)'
                        });
                    }
                });
        });
}

function abrirModalAlumno() {
    modoAlumno = 'crear';
    idAlumnoActual = null;

    fetch('/alumnos/modalAlta?_t=' + Date.now())
        .then(res => res.text())
        .then(html => {
            document.getElementById('contenedorModal').innerHTML = html;
            setFormDisabled(false);

            if (grupoId) {
                const groupSelect = document.querySelector('#formAlumno select[name="id_Grupo"]');
                if (groupSelect) {
                    groupSelect.value = grupoId;
                }
            }

            let modal = new bootstrap.Modal(document.getElementById('modalAlumno'));
            modal.show();
        });
}

document.addEventListener("DOMContentLoaded", function() {

    let pagina = 1;
    let search = '';
    let generacion = '';

    if (grupoId) {
        const filterGen = document.getElementById('contenedorFiltroGeneracion');
        if (filterGen) filterGen.style.display = 'none';
        const searchInput = document.getElementById('buscadorAlumnos');
        if (searchInput) searchInput.style.display = 'none';
    }

    function cargarAlumnos() {
        document.getElementById('loading').style.display = 'block';

        let fetchUrl = grupoId 
            ? `/alumnos/grupo/${grupoId}`
            : `/alumnos/lista?page=${pagina}&limit=10&search=${search}&generacion=${generacion}`;

        fetch(fetchUrl)
            .then(res => res.json())
            .then(data => {
                let alumnos = data.data || [];

                if (!grupoId && generacion) {
                    alumnos = alumnos.filter(
                        alumno => alumno.nombreGeneracionTexto == generacion
                    );
                }

                renderTabla(alumnos);
                
                if (grupoId) {
                    document.getElementById('paginacionMaterias').innerHTML = '';
                    document.getElementById('infoPaginacionMaterias').innerText =
                        `Total alumnos en grupo: ${alumnos.length}`;
                } else {
                    renderPaginacion(data);
                }
            })
            .finally(() => {
                document.getElementById('loading').style.display = 'none';
            });
    }

    let timeout = null;
    document.getElementById('buscadorAlumnos').addEventListener('input', (e) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            search = e.target.value;
            pagina = 1;
            cargarAlumnos();
        }, 400);
    });

    document.getElementById('filtroBGNE').addEventListener('change', (e) => {
        generacion = e.target.value;
        pagina = 1;
        cargarAlumnos();
    });

    function renderTabla(alumnos) {
        if (!alumnos.length) {
            document.getElementById('tablaAlumnos').innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-muted">
                    No se encontraron alumnos
                </td>
            </tr>
        `;
            return;
        }

        let html = '';
        alumnos.forEach(alumno => {
            const fullName = `${alumno.nombre} ${alumno.apPaterno} ${alumno.apMaterno || ''}`;
            
            const groupBadge = alumno.nombreGrupoTexto 
                ? `<span class="badge" style="font-size: 0.75rem; padding: 5px 10px; border-radius: 12px; background-color: #0ea5e9; color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.15); font-weight: 700; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">${alumno.nombreGrupoTexto}</span>` 
                : '<span class="text-muted" style="font-size: 0.85rem;">—</span>';

            html += `
        <tr>
            <td>${alumno.idAlumno}</td>
            <td>${fullName}</td>
            <td>${groupBadge}</td>
            <td>Generación ${alumno.nombreGeneracionTexto || 'N/A'}</td>
            <td>${getStatusBadge(alumno.statusAlumno)}</td>
            <td class="text-center">
                <button class="btn btn-secondary btn-sm btn-action" onclick="verAlumno(${alumno.idAlumno})" title="Ver detalles">
                    <i class="fa-solid fa-eye"></i>
                </button>
                <button class="btn btn-warning btn-sm btn-action" onclick="editarAlumno(${alumno.idAlumno})" title="Editar">
                    <i class="fa-solid fa-pen"></i>
                </button>
                <button class="btn btn-danger btn-sm btn-action btnEliminar" data-id="${alumno.idAlumno}" title="Eliminar">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </td>
        </tr>
        `;
        });

        document.getElementById('tablaAlumnos').innerHTML = html;
    }

    document.addEventListener('click', async function(e) {
        const boton = e.target.closest('.btnEliminar');
        if (!boton) return;
        const id = boton.dataset.id;

        const confirmResult = await Swal.fire({
            title: '¿Deseas eliminar este alumno?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#cbd5e1',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (!confirmResult.isConfirmed) return;

        fetch(`/alumnos/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminado',
                        text: data.message,
                        confirmButtonColor: 'rgb(38, 104, 123)'
                    });
                    cargarAlumnos();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al eliminar',
                        confirmButtonColor: 'rgb(38, 104, 123)'
                    });
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al eliminar alumno',
                    confirmButtonColor: 'rgb(38, 104, 123)'
                });
            });
    });

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
        <span class="mx-2">
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

        document.getElementById('paginacionMaterias').innerHTML = html;
        document.getElementById('infoPaginacionMaterias').innerText =
            `Total alumnos: ${data.total}`;
    }

    window.cambiarPagina = function(p) {
        pagina = p;
        cargarAlumnos();
    }

    // INIT
    cargarAlumnos();

    document.addEventListener('submit', function(e) {
        if (e.target.id !== 'formAlumno') return;
        e.preventDefault();

        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());

        const url = modoAlumno === 'editar' ? `/alumnos/${idAlumnoActual}` : '/alumnos';
        const method = modoAlumno === 'editar' ? 'PUT' : 'POST';

        fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Completado',
                        text: data.message,
                        confirmButtonColor: 'rgb(38, 104, 123)'
                    });

                    bootstrap.Modal.getInstance(document.getElementById('modalAlumno')).hide();
                    cargarAlumnos();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al guardar',
                        confirmButtonColor: 'rgb(38, 104, 123)'
                    });
                }
            })
            .catch(error => {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al guardar alumno',
                    confirmButtonColor: 'rgb(38, 104, 123)'
                });
            });
    });

});
</script>