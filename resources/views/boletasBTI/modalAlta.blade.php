@extends('layouts.app')

@section('content')


<head>
    <link rel="stylesheet" href="{{ asset('css/estilosModal.css') }}">
</head>



<div class="modal fade" id="modalCalificaciones" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content glass-modal">

            <div class="modal-header modal-header-system">
                <h5 class="modal-title">
                    Captura de Calificaciones
                </h5>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="materia-info text-center mb-4">

                    <span class="badge px-3 py-2 mb-2" id="contadorMateria"></span>

                    <h4 id="tituloMateria" class="mt-2 fw-bold"></h4>

                </div>

                <!-- Tabla -->
                <div class="table-responsive">

                    <table class="table table-hover align-middle text-center tabla-calificaciones">
                       <thead>

                            <tr>
                                <th>#</th>
                                <th>Alumno</th>
                                <th>P1</th>
                                <th>P2</th>
                                <th>P3</th>
                                <th>Semestral</th>
                                <th>Extraordinario</th>
                            </tr>

                        </thead>

                        <tbody id="tablaAlumnos">

                        </tbody>

                    </table>

                </div>

            </div>

            <div class="modal-footer">

                <button class="btn">
                    Guardar
                </button>

                <button class="btn data-bs-dismiss="modal">
                    Cerrar
                </button>

            </div>

        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let materiasActuales = [];
let materiaActual = 0;


function abrirSemestre(grado) {

    fetch(`/calificaciones/materias/${grado}`)

        .then(res => res.json())

        .then(materias => {

            console.log("Materias recibidas:", materias);

            if (!materias || materias.length === 0) {

                Swal.fire(
                    "Sin materias",
                    "No se encontraron materias para este semestre",
                    "warning"
                );

                return;
            }


            materiasActuales = materias;
            materiaActual = 0;

            cargarMateria();

        })

        .catch(error => {

            console.error(error);

            Swal.fire(
                "Error",
                "No se pudieron cargar las materias",
                "error"
            );

        });

}


function cargarMateria() {

    if (!materiasActuales[materiaActual]) {
        console.error("No existe materia", materiasActuales);
        return;
    }


    let materia = materiasActuales[materiaActual];


    document.getElementById('contadorMateria').innerText =
        `Materia ${materiaActual+1} de ${materiasActuales.length}`;


    document.getElementById('tituloMateria').innerText =
        `${materia.MATERIA} `;



    fetch(`/calificaciones/alumnos/${materia.GRADO}/${materia.GRUPO}/${materia.ID}`)

        .then(res => res.json())

        .then(alumnos => {


            let tbody = document.getElementById('tablaAlumnos');

            tbody.innerHTML = "";


            alumnos.forEach((alumno, index) => {


                tbody.innerHTML += `

            <tr>

                <td>${index+1}</td>

                <td>${alumno.nombre}</td>


                <td>
                    <input class="form-control" 
                    value="${alumno.p1 ?? ''}">
                </td>

                <td>
                    <input class="form-control" 
                    value="${alumno.p2 ?? ''}">
                </td>

                <td>
                    <input class="form-control" 
                    value="${alumno.p3 ?? ''}">
                </td>

                <td>
                    <input class="form-control" 
                    value="${alumno.semestral ?? ''}">
                </td>

                <td>
                    <input class="form-control" 
                    value="${alumno.extra ?? ''}">
                </td>

            </tr>

            `;

            });


            new bootstrap.Modal(
                document.getElementById('modalCalificaciones')
            ).show();


        });

}
</script>