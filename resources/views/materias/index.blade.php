@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>LISTA DE MATERIAS</h3>

    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalMateria">
        Alta materia
    </button>
</div>

<div class="card shadow-sm">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Lista de Materias</h5>

        <!-- 🔍 BUSCADOR -->
        <input type="text" id="buscadorMateria" class="form-control w-25" placeholder="Buscar materia...">
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">
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

    <!-- PAGINACIÓN -->
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small id="infoPaginacionMaterias"></small>
        <div id="paginacionMaterias"></div>
    </div>

</div>

<!-- MODAL -->
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

                        <!-- 🔥 CHECKBOXES -->
                        <div id="contenedorDocentes"></div>

                        <!-- 👇 seleccionados -->
                        <div id="docentesSeleccionados" class="mt-2"></div>
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

            listaMaterias = Array.isArray(data) ? data : [];
            renderMaterias();
        });
}

// 🔍 BUSCADOR
document.getElementById('buscadorMateria')?.addEventListener('input', () => {
    paginaMateria = 1;
    renderMaterias();
});

function renderMaterias() {

    const filtro = document.getElementById('buscadorMateria').value.toLowerCase();

    const filtradas = listaMaterias.filter(m =>
        m.nombreMateria.toLowerCase().includes(filtro) ||
        m.descripcionMateria.toLowerCase().includes(filtro)
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
                <button class="btn btn-info btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
  <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
  <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
</svg></button>
                <button class="btn btn-warning btn-sm"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
  <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
</svg></button>
                <button class="btn btn-danger btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
  <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
</svg></button>
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

function cambiarPaginaMateria(p) {
    paginaMateria = p;
    renderMaterias();
}

    // =========================
    // FORMULARIO
    // =========================
    document.getElementById('formMateria').addEventListener('submit', function(e) {
        e.preventDefault();

        const docentes = Array.from(document.querySelectorAll('.docenteCheck:checked'))
    .map(check => ({
        idDocente: Number(check.value)
    }));
        const data = {
            nombreMateria: this.nombreMateria.value,
            descripcionMateria: this.descripcionMateria.value,
            estatusMateria: this.estatusMateria.value,
            docentes: docentes
        };

        fetch('/materias', {
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
                alert("Materia guardada correctamente");

                bootstrap.Modal.getInstance(document.getElementById('modalMateria')).hide();

                this.reset();
                contenedorSeleccionados.innerHTML = '';

                cargarMaterias();
            } else {
                alert("Error al guardar materia");
            }
        });

    });

    // 🔥 INIT
    cargarDocentes();

});
</script>