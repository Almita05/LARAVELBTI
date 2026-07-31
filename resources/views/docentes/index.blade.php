@extends('layouts.app')

@section('content')

<head>
    <link rel="stylesheet" href="{{ asset('css/estilosDocentes.css') }}">
</head>

<div class="page-container">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <a href="{{ url()->previous() }}" class="btn btn-regresar">
            <i class="fa-solid fa-arrow-left me-2"></i>
            Regresar
        </a>

        <h3 class="page-title mb-0">
            <i class="fa-solid fa-chalkboard-user me-2"></i>
            Listado de docentes
        </h3>

        <button class="btn btn-azul" data-bs-toggle="modal" data-bs-target="#modalDocente">
            <i class="fa-solid fa-plus me-2"></i>
            Alta docente
        </button>

    </div>

    <div class="glass-card">

        <div class="glass-header p-3 d-flex justify-content-between align-items-center">

            <h5 class="mb-0 textoDocentes">
                Lista de docentes
            </h5>

            <input type="text" id="buscador" class="form-control glass-input w-25" placeholder="Buscar docente...">

        </div>


        <div class="table-responsive">

            <table class="table table-borderless glass-table align-middle mb-0">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Status</th>
                        <th>Nivel</th>
                        <th>Fecha</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody id="tablaDocentes"></tbody>

            </table>

        </div>


        <div class="glass-footer p-3 d-flex justify-content-between align-items-center">
            <small id="infoPaginacion"></small>
            <div id="paginacion"></div>
        </div>

    </div>

    <!-- MODAL -->
    <div class="modal fade" id="modalDocente" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form id="formDocente" class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Alta Docente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6 mb-2">
                            <label>Nombre</label>
                            <input type="text" class="form-control" name="nombreDocente" required>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Apellido Paterno</label>
                            <input type="text" class="form-control" name="apPaternoDocente" required>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Apellido Materno</label>
                            <input type="text" class="form-control" name="apMaternoDocente">
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Correo</label>
                            <input type="email" class="form-control" name="correoDocente" required>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Teléfono</label>
                            <input type="text" class="form-control" name="telefonoDocente">
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Status</label>
                            <select class="form-control" name="statusDocente">
                                <option value="ACTIVO">ACTIVO</option>
                                <option value="INACTIVO">INACTIVO</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Nivel de Estudios</label>
                            <input type="text" class="form-control" name="nivelEstudios">
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Fecha de Nacimiento</label>
                            <input type="date" class="form-control" name="fechaNacimiento">
                        </div>

                        <div class="col-12 mb-2">
                            <label>Observaciones</label>
                            <textarea class="form-control" name="observacionesDocente"></textarea>
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


    {{-- ========================= JS ========================= --}}
    <script>
    document.addEventListener("DOMContentLoaded", function() {

        cargarDocentes();

        // =========================
        // LISTAR DOCENTES
        // =========================
        function cargarDocentes() {

            fetch('/docentes/lista')
                .then(res => res.json())
                .then(data => {

                    let html = '';

                    data.data.forEach(docente => {

                        const nombreCompleto =
                            `${docente.nombreDocente} ${docente.apPaternoDocente ?? ''} ${docente.apMaternoDocente ?? ''}`;

                        html += `
                        <tr>
                            <td>${docente.idDocente}</td>
                            <td>${nombreCompleto}</td>
                            <td>${docente.correoDocente}</td>
                            <td>${docente.telefonoDocente ?? ''}</td>
                            <td>
                                <span class="badge ${docente.statusDocente === 'ACTIVO' ? 'bg-success' : 'bg-danger'}">
                                    ${docente.statusDocente}
                                </span>
                            </td>
                            <td>${docente.nivelEstudios ?? 'N/A'}</td>
                            <td>${docente.fechaNacimiento ?? 'N/A'}</td>

                            <td>
                               <button class="btn btn-secondary btn-sm btn-action
            onclick="verDocente(${docente.idDocente})">
        <i class="fa-solid fa-eye"></i>
    </button>

    <button class="btn btn-secondary btn-sm btn-action
            onclick="editarDocente(${docente.idDocente})">
        <i class="fa-solid fa-pen"></i>
    </button>

    <button class="btn btn-secondary btn-sm btn-action
            onclick="eliminarDocente(${docente.idDocente})">
        <i class="fa-solid fa-trash"></i>
    </button>
                            </td>
                        </tr>
                    `;
                    });

                    document.getElementById('tablaDocentes').innerHTML = html;
                })
                .catch(err => console.log(err));
        }


        // =========================
        // INSERTAR DOCENTE
        // =========================
        document.getElementById('formDocente').addEventListener('submit', function(e) {
            e.preventDefault();

            const data = {
                nombreDocente: this.nombreDocente.value,
                apPaternoDocente: this.apPaternoDocente.value,
                apMaternoDocente: this.apMaternoDocente.value,
                correoDocente: this.correoDocente.value,
                telefonoDocente: this.telefonoDocente.value,
                statusDocente: this.statusDocente.value,
                observacionesDocente: this.observacionesDocente.value,
                nivelEstudios: this.nivelEstudios.value,
                fechaNacimiento: this.fechaNacimiento.value
            };

            fetch('/docentes', {
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

                        alert("Docente guardado correctamente");

                        // cerrar modal
                        bootstrap.Modal.getInstance(document.getElementById('modalDocente')).hide();

                        // limpiar form
                        this.reset();

                        // recargar tabla
                        cargarDocentes();

                    } else {
                        alert("Error al guardar docente");
                    }
                })
                .catch(err => console.log(err));
        });

    });


    // =========================
    // ACCIONES (BASE)
    // =========================
    function verDocente(id) {
        alert("Ver docente: " + id);
    }

    function editarDocente(id) {
        alert("Editar docente: " + id);
    }

    function eliminarDocente(id) {
        if (confirm("¿Eliminar docente?")) {
            alert("Eliminar docente: " + id);
        }
    }
    </script>