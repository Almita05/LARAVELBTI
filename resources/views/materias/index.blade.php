@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>LISTA DE MATERIAS</h3>

    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalMateria">
        Alta materia
    </button>
</div>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Estatus</th>
            <th>Docentes</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody id="tablaMaterias"></tbody>
</table>

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
    function cargarMaterias() {

        fetch('/materias/lista')
            .then(res => res.json())
            .then(data => {

                if (!Array.isArray(data)) return;

                let html = '';

                data.forEach(materia => {

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
                            ${materia.docentes && materia.docentes.length > 0
                                ? materia.docentes.map(doc => doc.nombreDocente).join(', ')
                                : 'Sin docentes'
                            }
                        </td>

                        <td>
                            <button class="btn btn-info btn-sm">Ver</button>
                            <button class="btn btn-warning btn-sm">Editar</button>
                            <button class="btn btn-danger btn-sm">Eliminar</button>
                        </td>
                    </tr>
                    `;
                });

                document.getElementById('tablaMaterias').innerHTML = html;
            });
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