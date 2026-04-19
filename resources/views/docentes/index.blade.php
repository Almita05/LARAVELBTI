@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3></h3>

    <!-- BOTÓN MODAL -->
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDocente">
        Alta docente
    </button>
</div>

<div class="card shadow-sm">
    
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Lista de Docentes</h5>

      
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
                                <button class="btn btn-info btn-sm" onclick="verDocente(${docente.idDocente})">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
  <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
  <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
</svg>
                                </button>

                                <button class="btn btn-warning btn-sm" onclick="editarDocente(${docente.idDocente})">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
  <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
</svg>
                                </button>

                                <button class="btn btn-danger btn-sm" onclick="eliminarDocente(${docente.idDocente})">
                                   <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
  <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
</svg>
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
    document.getElementById('formDocente').addEventListener('submit', function(e){
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

            if(resp.success){

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
function verDocente(id){
    alert("Ver docente: " + id);
}

function editarDocente(id){
    alert("Editar docente: " + id);
}

function eliminarDocente(id){
    if(confirm("¿Eliminar docente?")){
        alert("Eliminar docente: " + id);
    }
}
</script>