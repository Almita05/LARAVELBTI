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

.glass-footer {
    background: rgba(255, 255, 255, .05);
    border-top: 1px solid rgba(255, 255, 255, .15);
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

.btn-eliminar {
    background: #E53935;
    color: white;
    border: none;
}

.btn-eliminar:hover {
    background: #C62828;
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

/* Modal */
.modal-content {
    border-radius: 20px;
    border: none;
}

.modal-header {
    background: linear-gradient(135deg, #1E6FA8, #6BC7E8);
    color: white;
}

.modal-footer {
    background: #f8f9fa;
}
</style>

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

    <div class="card shadow-sm">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"></h5>


            <input type="text" id="buscador" class="form-control w-25" placeholder="Buscar docente...">
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
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

        <div class="card-footer d-flex justify-content-between align-items-center">
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
                               <button class="btn btn-ver btn-sm"
            onclick="verDocente(${docente.idDocente})">
        <i class="fa-solid fa-eye"></i>
    </button>

    <button class="btn btn-editar btn-sm"
            onclick="editarDocente(${docente.idDocente})">
        <i class="fa-solid fa-pen"></i>
    </button>

    <button class="btn btn-eliminar btn-sm"
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