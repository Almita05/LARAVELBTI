@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/estilosMaterias.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<div class="page-container">

    <div class="d-flex justify-content-between align-items-center mb-4">

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

            <h5 class="mb-0">
                Lista de Materias
            </h5>

            <input
                type="text"
                id="buscadorMateria"
                class="form-control glass-input w-25"
                placeholder="Buscar materia..."
            >

        </div>

        <div class="table-responsive">

            <table class="table table-borderless glass-table align-middle mb-0">

                <thead>
                    <tr>
                        <th>ID</th>
                     
                        <th>Nombre</th>
                        <th>Descripción</th>
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

<!-- Modal -->
<div class="modal fade" id="modalMateria" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="formMateria" class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Alta Materia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row">

                    <div class="col-md-6 mb-2">
                        <label>Nombre</label>
                        <input type="text" class="form-control" name="nombreMateria" required>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label>Descripción</label>
                        <input type="text" class="form-control" name="descripcionMateria" required>
                    </div>

                    <div class="col-md-12 mb-2">
                        <label>Docentes</label>

                        <div id="contenedorDocentes" style="max-height: 200px; overflow-y: auto; padding: 10px; border: 1px solid rgba(255,255,255,0.15); border-radius: 8px; background: rgba(255,255,255,0.05);"></div>

                        <div id="docentesSeleccionados" class="mt-2"></div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label>Clave</label>
                        <input type="text" class="form-control" name="clave" placeholder="Ej. MAT-201" required>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label>Estatus</label>
                        <select class="form-control" name="estatusMateria">
                            <option value="ACTIVA">ACTIVA</option>
                            <option value="INACTIVA">INACTIVA</option>
                        </select>
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


<script>
let listaDocentes = [];

document.addEventListener("DOMContentLoaded", function() {

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
                    const nombre = `${docente.nombreDocente} ${docente.apPaternoDocente} ${docente.apMaternoDocente}`;

                    const div = document.createElement('div');
                    div.className = 'form-check';

                    div.innerHTML = `
                        <input class="form-check-input docenteCheck" type="checkbox" value="${id}" id="doc_${id}">
                        <label class="form-check-label" for="doc_${id}">
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
const filasMateria = 5;

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

function setFormDisabled(disabled) {
    const form = document.getElementById('formMateria');
    form.nombreMateria.disabled = disabled;
    form.descripcionMateria.disabled = disabled;
    if (form.clave) form.clave.disabled = disabled;
    form.estatusMateria.disabled = disabled;
    document.querySelectorAll('.docenteCheck').forEach(cb => cb.disabled = disabled);
}

// Reset modal to create mode when Alta button is clicked
const btnAlta = document.querySelector('[data-bs-target="#modalMateria"]');
if (btnAlta) {
    btnAlta.addEventListener('click', function() {
        modoMateria = 'crear';
        idMateriaActual = null;
        const form = document.getElementById('formMateria');
        form.reset();
        document.getElementById('contenedorSeleccionados').innerHTML = '';
        setFormDisabled(false);
        document.querySelectorAll('.docenteCheck').forEach(cb => cb.checked = false);
        document.querySelector('.modal-title').textContent = 'Alta Materia';
        const submitBtn = document.querySelector('#formMateria button[type="submit"]');
        submitBtn.style.display = 'block';
        submitBtn.textContent = 'Guardar';
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

                document.querySelectorAll('.docenteCheck').forEach(cb => cb.checked = false);
                const checkIds = m.docentes ? m.docentes.map(d => d.idDocente) : [];
                checkIds.forEach(docId => {
                    const cb = document.getElementById(`doc_${docId}`);
                    if (cb) cb.checked = true;
                });
                mostrarSeleccionados();
                setFormDisabled(true);

                document.querySelector('.modal-title').textContent = 'Detalles de Materia';
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

                document.querySelectorAll('.docenteCheck').forEach(cb => cb.checked = false);
                const checkIds = m.docentes ? m.docentes.map(d => d.idDocente) : [];
                checkIds.forEach(docId => {
                    const cb = document.getElementById(`doc_${docId}`);
                    if (cb) cb.checked = true;
                });
                mostrarSeleccionados();
                setFormDisabled(false);

                document.querySelector('.modal-title').textContent = 'Editar Materia';
                const submitBtn = document.querySelector('#formMateria button[type="submit"]');
                submitBtn.style.display = 'block';
                submitBtn.textContent = 'Actualizar';

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
                        text: data.message || 'Materia eliminada correctamente.',
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

    const filtro = document.getElementById('buscadorMateria').value.toLowerCase();

    const filtradas = listaMaterias.filter(m =>
        m.nombreMateria.toLowerCase().includes(filtro) ||
        m.descripcionMateria.toLowerCase().includes(filtro) ||
        (m.clave && m.clave.toLowerCase().includes(filtro))
    );

    const inicio = (paginaMateria - 1) * filasMateria;
    const fin = inicio + filasMateria;
    const datos = filtradas.slice(inicio, fin);

    let html = '';

    datos.forEach(materia => {

        html += `
        <tr>
            <td>${materia.id}</td>
          
            <td>${materia.nombreMateria}</td>
            <td>${materia.descripcionMateria}</td>

            <td>
                <span class="badge ${materia.estatusMateria === 'ACTIVA' ? 'bg-success' : 'bg-danger'}">
                    ${materia.estatusMateria}
                </span>
            </td>

            <td>
                ${materia.docentes?.length
                    ? materia.docentes.map(d => d.nombreDocente).join(', ')
                    : 'Sin docentes'}
            </td>

            <td class="text-center">
                <button class="btn btn-secondary btn-sm me-1" onclick="verMateria(${materia.id})">
                    <i class="fa-solid fa-eye"></i>
                </button>
                <button class="btn btn-warning btn-sm me-1" onclick="editarMateria(${materia.id})">
                    <i class="fa-solid fa-pen"></i>
                </button>
                <button class="btn btn-danger btn-sm" onclick="eliminarMateria(${materia.id})">
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
        const data = {
            nombreMateria: this.nombreMateria.value,
            descripcionMateria: this.descripcionMateria.value,
            estatusMateria: this.estatusMateria.value,
            clave: this.clave.value,
            docentes: docentes
        };

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
                    text: modoMateria === 'editar' ? "Materia actualizada correctamente." : "Materia guardada correctamente.",
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

});
</script>