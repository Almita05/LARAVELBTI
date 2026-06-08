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

/* Cards de alumnos */
.equivalencia-card {
    background: rgba(255, 255, 255, .08);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, .15);
    border-radius: 18px;
    color: white;
    transition: .3s;
    overflow: hidden;
    height: 100%;
}

.equivalencia-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, .2);
    background: rgba(255, 255, 255, .12);
}

.equivalencia-card .card-body {
    padding: 25px;
}

.equivalencia-card .card-title {
    font-weight: 700;
    margin-bottom: 15px;
}

.badge-equivalencia {
    font-size: .85rem;
    padding: 8px 12px;
    border-radius: 20px;
}

.estado-finalizada {
    background: #28a745;
}

.estado-tramite {
    background: #ffc107;
    color: #000;
}

.estado-no {
    background: #6c757d;
}
</style>

<div class="page-container">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <a href="{{ url()->previous() }}" class="btn btn-regresar">
            <i class="fa-solid fa-arrow-left me-2"></i>
            Regresar
        </a>

        <h3 class="page-title mb-0">
            <i class="fa-solid fa-arrows-rotate me-2"></i>
            Equivalencias
        </h3>

        <div style="width:130px;"></div>

    </div>

    <div class="glass-card">

        <div class="glass-header p-3 d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                
            </h5>

            <input type="text" id="buscadorGrupos" class="form-control glass-input w-25"
                placeholder="Buscar equivalencia...">

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
            <div class="equivalencia-card text-center">

                <div class="card-body">
                    <h5 class="card-title mb-2">${nombreCompleto}</h5>

                    <p class="mb-2">
                        <strong>Generación:</strong> ${alumno.nombreGeneracionTexto}
                    </p>

                    <span class="badge-equivalencia ${
    alumno.equivalencia === 'SI'
        ? 'estado-finalizada'
        : alumno.equivalencia === 'EN_PROCESO'
        ? 'estado-tramite'
        : 'estado-no'
}">
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