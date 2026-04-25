@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3></h3>

    <button class="btn btn-azul" onclick="abrirModalAlumno()">
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

<div id="contenedorModal"></div>
@endsection

<script>
function abrirModalAlumno() {

    fetch('/alumnos/modalAlta')
        .then(res => res.text())
        .then(html => {

            document.getElementById('contenedorModal').innerHTML = html;

            let modal = new bootstrap.Modal(document.getElementById('modalAlumno'));
            modal.show();
        });
}
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
                    No se encontraron alumnos
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
            <td>Generación ${alumno.nombreGeneracionTexto}</td>
            <td class="text-center">
                <button class="btn btn-azul btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                </svg></button>
                <button class="btn btn-warning btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                </svg></button>
                
            </td>
        </tr>
        `;
        });

        document.getElementById('tablaAlumnos').innerHTML = html;
    }

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

});
</script>