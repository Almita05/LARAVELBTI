@extends('layouts.app')

@section('content')


    <link rel="stylesheet" href="{{ asset('css/estilosGrupos.css') }}">


<div class="page-container">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <a href="{{ url()->previous() }}" class="btn btn-regresar">
            <i class="fa-solid fa-arrow-left me-2"></i>
            Regresar
        </a>

        <h3 class="page-title mb-0">
            <i class="fa-solid fa-users me-2"></i>
            Grupos
        </h3>

        <button class="btn btn-azul" onclick="abrirModalGrupo()">
            <i class="fa-solid fa-plus me-2"></i>
            Alta grupo
        </button>

    </div>

    <div class="glass-card">

        <div class="glass-header p-3 d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

            </h5>

            <input type="text" id="buscadorGrupos" class="form-control glass-input w-25" placeholder="Buscar grupos...">

        </div>

        <div class="table-responsive">
            <div id="loading" class="text-center py-4" style="display:none;">
                <div class="spinner-border text-light"></div>
            </div>

            <table class="table table-borderless glass-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody id="tablaGrupos"></tbody>
            </table>
        </div>
        <div class="glass-footer p-3 d-flex justify-content-between align-items-center">
    <small id="infoGrupos"></small>
    <div id="paginacionGrupos"></div>
</div>

    </div>
</div>


<div id="contenedorModal"></div>
@endsection

<script>
function abrirModalGrupo() {

    fetch('/grupos/modalAlta')
        .then(res => res.text())
        .then(html => {

            document.getElementById('contenedorModal').innerHTML = html;

            let modal = new bootstrap.Modal(document.getElementById('modalGrupo'));
            modal.show();
        });
}
document.addEventListener("DOMContentLoaded", function() {

    let grupos = [];
    let search = '';
    let pagina = 1;

    function cargarGrupos() {

        document.getElementById('loading').style.display = 'block';

        fetch(`/grupos/lista?page=${pagina}&search=${search}`)
            .then(res => res.json())
            .then(res => {
                grupos = res.data;
                renderTabla();
                renderPaginacion(res);
            })
            .finally(() => {
                document.getElementById('loading').style.display = 'none';
            });
    }

    document.getElementById('buscadorGrupos').addEventListener('input', (e) => {
        search = e.target.value.toLowerCase();
        pagina = 1;
        cargarGrupos();
    });

    function formatearFecha(fecha) {
        return new Date(fecha).toLocaleDateString();
    }

    function renderTabla() {

        if (!grupos.length) {
            document.getElementById('tablaGrupos').innerHTML = `
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        No se encontraron grupos
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';

        grupos.forEach(grupo => {
            html += `
                <tr>
                    <td>${grupo.clave}</td>
                    <td>${formatearFecha(grupo.fechaInicio)}</td>
                    <td>${formatearFecha(grupo.fechaFin)}</td>
                    <td class="text-center">
                        <button class="btn btn-ver btn-sm">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button class="btn btn-editar btn-sm">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

        document.getElementById('tablaGrupos').innerHTML = html;
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
            <span class="mx-2 text-white">
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

        document.getElementById('paginacionGrupos').innerHTML = html;
        document.getElementById('infoGrupos').innerText = `Total grupos: ${data.total}`;
    }

    window.cambiarPagina = function(p) {
        pagina = p;
        cargarGrupos();
    }

    // Form submission for creating new group (delegated to index page since AJAX HTML script doesn't auto-evaluate)
    document.addEventListener('submit', function(e) {
        if (e.target.id === 'formGrupo') {
            e.preventDefault();

            let formData = new FormData(e.target);
            let data = Object.fromEntries(formData.entries());

            fetch('/grupos', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(async res => {
                const isJson = res.headers.get('content-type')?.includes('application/json');
                const resData = isJson ? await res.json() : null;

                if (!res.ok) {
                    throw new Error(resData?.message || 'Error al guardar el grupo en el servidor backend');
                }
                return resData;
            })
            .then(res => {
                alert('¡Éxito: Grupo guardado correctamente!');
                
                let modalEl = document.getElementById('modalGrupo');
                let modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) {
                    modal.hide();
                }

                // Remove modal backdrop if any remains
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }

                cargarGrupos();
            })
            .catch(err => {
                alert('Advertencia / Error: ' + err.message);
            });
        }
    });

    // INIT
    cargarGrupos();

});
</script>