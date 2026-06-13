@extends('layouts.app')

@section('content')

<style>
.page-container {
    padding: 30px;
    min-height: calc(100vh - 80px);
}

.page-title {
    color: white;
    font-size: 2rem;
    font-weight: 700;
    text-shadow: 0 2px 8px rgba(0, 0, 0, .2);
}

.glass-card {
    background: rgba(255, 255, 255, .08);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, .15);
    border-radius: 20px;
    overflow: hidden;
}

.glass-header {
    background: rgba(255, 255, 255, .05);
    color: white;
}

.glass-footer {
    background: rgba(255, 255, 255, .05);
    color: white;
}

.glass-table {
    color: white;
}

.glass-table thead tr {
    background: rgba(255, 255, 255, .08);
}

.glass-table tbody tr:hover {
    background: rgba(255, 255, 255, .08);
}

.glass-table td,
.glass-table th {
    border-color: rgba(255, 255, 255, .1);
}

.glass-input {
    background: rgba(255, 255, 255, .08);
    border: 1px solid rgba(255, 255, 255, .15);
    color: white;
}

.glass-input::placeholder {
    color: rgba(255, 255, 255, .6);
}

.glass-input:focus {
    background: rgba(255, 255, 255, .12);
    color: white;
    border-color: #6BC7E8;
    box-shadow: 0 0 15px rgba(107, 199, 232, .3);
}

.btn-action {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    margin: 0 2px;
}

.btn-light.btn-action {
    background: rgba(255, 255, 255, .15);
    border: none;
    color: white;
}

.btn-light.btn-action:hover {
    background: rgba(255, 255, 255, .25);
}

.btn-warning.btn-action {
    color: white;
}

/* Fondo transparente para integrarse al layout */
.page-container {
    padding: 20px;
}

/* Card principal */
.glass-card {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, .15);
}

/* Header */
.glass-header {
    background: rgba(255, 255, 255, 0.05);
    border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    color: white;
}

/* Tabla */
.glass-table {
    color: white;
    margin-bottom: 0;
}

.glass-table thead {
    background: rgba(255, 255, 255, 0.1);
}

.glass-table thead th {
    color: white;
    border-bottom: 1px solid rgba(255, 255, 255, 0.15);
}

.glass-table tbody tr {
    transition: all .25s ease;
}

.glass-table tbody tr:hover {
    background: rgba(255, 255, 255, 0.08);
}

.glass-table td,
.glass-table th {
    border-color: rgba(255, 255, 255, 0.08);
}

/* Buscador */
.glass-input {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
}

.glass-input::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.glass-input:focus {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    border-color: #6BC7E8;
    box-shadow: 0 0 10px rgba(107, 199, 232, .5);
}

/* Footer */
.glass-footer {
    background: rgba(255, 255, 255, 0.05);
    border-top: 1px solid rgba(255, 255, 255, 0.15);
    color: white;
}

/* Botón principal */
.btn-azul {
    background: linear-gradient(135deg, #1E6FA8, #6BC7E8);
    border: none;
    color: white;
    font-weight: 500;
    transition: .3s;
}

.btn-azul:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(107, 199, 232, .4);
    color: white;
}

/* Acciones */
.btn-action {
    width: 36px;
    height: 36px;
    border-radius: 10px;
}

/* Título */
.page-title {
    color: white;
    font-weight: 600;
}

.btn-back {
    display: inline-block;
    background: white;
    color: #1A338F;
    padding: 10px 20px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: bold;
    margin-bottom: 30px;
    transition: all 0.3s ease;

    position: absolute;
    top: 20px;
    left: 20px;
}

.btn-back:hover {
    background: #f0f0f0;
    color: #1A338F;
}

#filtroBGNE {
    background: rgba(255, 255, 255, 0.10) !important;
    color: white !important;
    border: 1px solid rgba(255, 255, 255, 0.15);
}

#filtroBGNE:focus {
    background: rgba(255, 255, 255, 0.15) !important;
    color: white !important;
    border-color: #6BC7E8;
    box-shadow: 0 0 10px rgba(107, 199, 232, .5);
}

#filtroBGNE option {
    background: #6BC7E8;
    color: white;
}

.input-group-text.glass-input {
    background: rgba(255, 255, 255, 0.10) !important;
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.15);
}
</style>

<div class="page-container">



    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ url()->previous() }}" class="btn btn-light">
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

            <h5 class="mb-0">
                Lista de alumnos
            </h5>


            <div class="input-group w-25">

                <select id="filtroBGNE" class="form-select glass-input">
                    <option value="">Todas las generaciones</option>

                    @foreach($generaciones as $generacion)
                    <option value="{{ $generacion['generacion'] }}">
                        Generación {{ $generacion['generacion'] }} - {{ $generacion['nombreGeneracion'] }}
                    </option>
                    @endforeach
                </select>
                <span class="input-group-text glass-input">
                    <i class="fa-solid fa-filter"></i>
                </span>
            </div>


            <!-- Búsqueda por texto -->
            <input type="text" id="buscadorAlumnos" class="form-control glass-input w-25"
                placeholder="Buscar alumnos...">

        </div>

        <div class="table-responsive">

            <div id="loading" class="text-center py-4" style="display:none;">
                <div class="spinner-border text-light"></div>
            </div>

            <table class="table glass-table align-middle mb-0">

                <thead>
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

        <div class="glass-footer p-3 d-flex justify-content-between align-items-center">
            <small id="infoPaginacionMaterias"></small>
            <div id="paginacionMaterias"></div>
        </div>

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
    let generacion = '';

    function cargarAlumnos() {

        document.getElementById('loading').style.display = 'block';

        fetch(`/alumnos/lista?page=${pagina}&limit=10&search=${search}&generacion=${generacion}`)
            .then(res => res.json())
            .then(data => {

                let alumnos = data.data;

                if (generacion) {
                    alumnos = alumnos.filter(
                        alumno => alumno.nombreGeneracionTexto == generacion
                    );
                }

                renderTabla(alumnos);
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

            html += `
        <tr>
            <td>${alumno.idAlumno}</td>
            <td>${alumno.nombre} ${alumno.apPaterno} ${alumno.apMaterno}</td>
            <td>${alumno.apPaterno}</td>
            <td>${alumno.apMaterno}</td>
            <td>Generación ${alumno.nombreGeneracionTexto}</td>
               <td class="text-center">
    <button class="btn btn-secondary btn-sm btn-action">
        <i class="fa-solid fa-eye"></i>
    </button>

    <button class="btn btn-warning btn-sm btn-action">
        <i class="fa-solid fa-pen"></i>
    </button>
    <button class="btn btn-danger btn-sm btn-action btnEliminar"
        data-id="${alumno.idAlumno}">
    <i class="fa-solid fa-trash"></i>
</button>
</td>
        </tr>
        `;
        });

        document.getElementById('tablaAlumnos').innerHTML = html;
    }

    document.addEventListener('click', function(e) {

        const boton = e.target.closest('.btnEliminar');

        if (!boton) return;

        const id = boton.dataset.id;

        if (!confirm('¿Deseas eliminar este alumno?')) {
            return;
        }

        fetch(`/alumnos/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {

                if (data.success) {

                    alert(data.message);

                    cargarAlumnos();
                } else {
                    alert(data.message || 'Error al eliminar');
                }

            })
            .catch(() => {
                alert('Error al eliminar alumno');
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

});
</script>