@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>Grupos</h3>

   <button class="btn btn-azul" onclick="abrirModalGrupo()">
        Alta grupo
    </button>
</div>

<div class="card shadow-sm">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Lista de Grupos</h5>

        <input type="text" id="buscadorGrupos" class="form-control w-25" placeholder="Buscar grupos...">
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
                        <button class="btn btn-azul btn-sm">Ver</button>
                        <button class="btn btn-warning btn-sm">Editar</button>
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