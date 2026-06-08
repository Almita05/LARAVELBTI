@extends('layouts.app')

@section('content')
<style>
.page-container {
    padding: 30px;
    min-height: 100vh;
}

body {
    background: linear-gradient(135deg,
            #0F2E6D 0%,
            #1E6FA8 50%,
            #6BC7E8 100%);
}

.page-title {
    color: white;
    font-weight: 700;
    text-shadow: 0 2px 8px rgba(0, 0, 0, .2);
}

.glass-card {
    background: rgba(255, 255, 255, .08);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, .15);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, .15);
}

.glass-header {
    background: rgba(255, 255, 255, .05);
    border-bottom: 1px solid rgba(255, 255, 255, .15);
    color: white;
}

.glass-table {
    color: white;
    margin-bottom: 0;
}

.glass-table thead {
    background: #0D47A1;
}

.glass-table thead th {
    color: white;
    border: none;
    font-weight: 600;
}

.glass-table tbody {
    background: rgba(13, 71, 161, .12);
}

.glass-table tbody tr {
    transition: .3s;
}

.glass-table tbody tr:hover {
    background: rgba(255, 255, 255, .08);
}

.glass-table td,
.glass-table th {
    border-color: rgba(255, 255, 255, .08);
}

.glass-input {
    background: rgba(255, 255, 255, .08);
    border: 1px solid rgba(255, 255, 255, .15);
    color: white;
}

.glass-input::placeholder {
    color: rgba(255, 255, 255, .7);
}

.glass-input:focus {
    background: rgba(255, 255, 255, .12);
    color: white;
    border-color: #6BC7E8;
    box-shadow: 0 0 12px rgba(107, 199, 232, .4);
}

.btn-azul {
    background: linear-gradient(135deg, #1E6FA8, #6BC7E8);
    border: none;
    color: white;
    font-weight: 600;
}

.btn-azul:hover {
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(107, 199, 232, .4);
}

.btn-ver {
    background: #00BCD4;
    color: white;
    border: none;
}

.btn-ver:hover {
    background: #0097A7;
    color: white;
}

.btn-editar {
    background: #FF9800;
    color: white;
    border: none;
}

.btn-editar:hover {
    background: #F57C00;
    color: white;
}

.btn-regresar {
    background: rgba(255, 255, 255, .12);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, .2);
    color: white;
    font-weight: 600;
}

.btn-regresar:hover {
    background: rgba(255, 255, 255, .2);
    color: white;
}
</style>
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
                <div class="spinner-border text-primary"></div>
            </div>

            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
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

    function cargarGrupos() {

        document.getElementById('loading').style.display = 'block';

        fetch(`/grupos/lista`)
            .then(res => res.json())
            .then(res => {
                grupos = res.data;
                renderTabla();
            })
            .finally(() => {
                document.getElementById('loading').style.display = 'none';
            });
    }

    document.getElementById('buscadorGrupos').addEventListener('input', (e) => {
        search = e.target.value.toLowerCase();
        renderTabla();
    });

    function formatearFecha(fecha) {
        return new Date(fecha).toLocaleDateString();
    }

    function renderTabla() {

        let filtrados = grupos.filter(g =>
            g.clave.toLowerCase().includes(search)
        );

        if (!filtrados.length) {
            document.getElementById('tablaGrupos').innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        No se encontraron grupos
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';

        filtrados.forEach(grupo => {
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

    // INIT
    cargarGrupos();

});
</script>