@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3></h3>

    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalMateria">
        Alta alumno
    </button>
</div>

<div class="card shadow-sm">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Lista de Alumnos</h5>

        <!-- 🔍 BUSCADOR -->
        <input type="text" id="buscadorAlumnos" class="form-control w-25" placeholder="Buscar alumnos...">
    </div>

    <div class="table-responsive">
        <div id="loading" class="text-center py-4" style="display:none;">
    <div class="spinner-border text-primary"></div>
</div>
        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Generación</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>

            <tbody id="tablaAlumnos"></tbody>

        </table>
    </div>

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
                <h5 class="modal-title">Alta Alumno</h5>
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

                        <div id="contenedorDocentes"></div>

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
document.addEventListener("DOMContentLoaded", function() {

    let pagina = 1;
    let search = '';
    function cargarAlumnos() {

    document.getElementById('loading').style.display = 'block';

    fetch(`/alumnos/lista?page=${pagina}&limit=10&search=${search}`)
        .then(res => res.json())
        .then(data => {

            renderTabla(data.data);
            renderPaginacion(data);
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
    function renderTabla(alumnos) {

    if (!alumnos.length) {
        document.getElementById('tablaAlumnos').innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-muted">
                    No se encontraron alumnos 😕
                </td>
            </tr>
        `;
        return;
    }

    let html = '';

    alumnos.forEach(alumno => {

        html += `
        <tr>
            <td>${alumno.idAlumno}</td>
            <td>${alumno.nombre} ${alumno.apPaterno} ${alumno.apMaterno}</td>
            <td>${alumno.apPaterno}</td>
            <td>${alumno.apMaterno}</td>
            <td>Generación ${alumno.idGeneracion}</td>
            <td class="text-center">
                <button class="btn btn-info btn-sm">👁</button>
                <button class="btn btn-warning btn-sm">✏</button>
                <button class="btn btn-danger btn-sm">🗑</button>
            </td>
        </tr>
        `;
    });

    document.getElementById('tablaAlumnos').innerHTML = html;
}
    function renderPaginacion(data) {

    let html = '';

    html += `
        <button class="btn btn-sm btn-outline-primary me-2"
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
        <button class="btn btn-sm btn-outline-primary ms-2"
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

});
</script>