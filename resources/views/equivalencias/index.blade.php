@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>Equivalencias</h3>

</div>

<div class="card shadow-sm">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Lista de equivalencias</h5>

        <input type="text" id="buscadorGrupos" class="form-control w-25" placeholder="Buscar equivalencia...">
    </div>

    <div class="p-3">
        <div id="loading" class="text-center py-4" style="display:none;">
            <div class="spinner-border text-primary"></div>
        </div>

        <div class="row" id="contenedorCards"></div>
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

        fetch(`/equivalencias/lista`)
            .then(res => res.json())
            .then(res => {
                grupos = res.data;
                renderCards();
            })
            .finally(() => {
                document.getElementById('loading').style.display = 'none';
            });
    }

    document.getElementById('buscadorGrupos').addEventListener('input', (e) => {
        search = e.target.value.toLowerCase();
        renderCards();
    });

    function formatearFecha(fecha) {
        return new Date(fecha).toLocaleDateString();
    }

    function renderCards() {

        let filtrados = grupos.filter(g =>
            (g.nombre + ' ' + g.apPaterno + ' ' + g.apMaterno)
            .toLowerCase()
            .includes(search)
        );

        if (!filtrados.length) {
            document.getElementById('contenedorCards').innerHTML = `
            <div class="col-12 text-center text-muted">
                No se encontraron alumnos
            </div>
        `;
            return;
        }

        let html = '';

       filtrados.forEach(alumno => {

    let nombreCompleto = `${alumno.nombre} ${alumno.apPaterno} ${alumno.apMaterno}`;

    let estadoTexto = '';
    let estadoClase = '';

    if (alumno.equivalencia === 'SI') {
        estadoTexto = 'Finalizada';
        estadoClase = 'bg-success';
    } else if (alumno.equivalencia === 'EN_PROCESO') {
        estadoTexto = 'En trámite';
        estadoClase = 'bg-warning text-dark';
    } else {
        estadoTexto = 'No tramitada';
        estadoClase = 'bg-secondary';
    }

    html += `
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100 text-center">

                <div class="card-body">
                    <h5 class="card-title mb-2">${nombreCompleto}</h5>

                    <p class="mb-2">
                        <strong>Generación:</strong> ${alumno.nombreGeneracionTexto}
                    </p>

                    <span class="badge ${estadoClase}">
                        ${estadoTexto}
                    </span>
                </div>

            </div>
        </div>
    `;
});

        document.getElementById('contenedorCards').innerHTML = html;
    }

    // INIT
    cargarGrupos();

});
</script>