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

                            <tr data-alumno="${alumno.id}">
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

                <button class="btn" id="guardarCalificaciones">
                    Guardar
                </button>

                <button class="btn" data-bs-dismiss="modal">
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
let materiaSeleccionada = null;


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
    materiaSeleccionada = materia.ID;


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

            <tr data-alumno="${alumno.id}">

                <td>${index+1}</td>

                <td>${alumno.nombre}</td>


<td>
<input 
class="form-control calificacion parcial1"
value="${alumno.p1 ?? ''}"
${alumno.p1 ? 'readonly' : ''}
>
</td>

<td>
<input 
class="form-control calificacion parcial2"
value="${alumno.p2 ?? ''}"
${alumno.p2 ? 'readonly' : ''}
disabled
>
</td>

<td>
<input 
class="form-control calificacion parcial3"
value="${alumno.p3 ?? ''}"
${alumno.p3 ? 'readonly' : ''}
disabled
>
</td>

<td>
<input 
class="form-control calificacion semestral"
value="${alumno.semestral ?? ''}"
${alumno.semestral ? 'readonly' : ''}
disabled
>
</td>

<td>
<input 
class="form-control calificacion extra"
value="${alumno.extra ?? ''}"
${alumno.extra ? 'readonly' : ''}
disabled
>
</td>

            </tr>

            `;

            });


            controlarParciales();

            new bootstrap.Modal(
                document.getElementById('modalCalificaciones')
            ).show();


        });

}


document.addEventListener('input', function(e) {

    if (!e.target.classList.contains('calificacion')) {
        return;
    }

    let input = e.target;

    if (input.value === '') {
        input.classList.remove('nota-roja', 'nota-verde');
        return;
    }


    let valor = parseFloat(input.value);


    if (valor > 10) {
        valor = 10;
    }


    if (valor < 0) {
        valor = 0;
    }


    valor = Math.round(valor);


    input.value = valor;


    input.classList.remove(
        'nota-roja',
        'nota-verde'
    );


    if (valor <= 5) {

        input.classList.add('nota-roja');

    } else {

        input.classList.add('nota-verde');

    }

});



document
    .getElementById('guardarCalificaciones')
    .addEventListener('click', function() {


        let parcial = obtenerParcialActivo();


        let datos = [];


        document.querySelectorAll('#tablaAlumnos tr')
            .forEach(fila => {


                let alumno = fila.dataset.alumno;


                let claseInput = {

                    P1: 'parcial1',
                    P2: 'parcial2',
                    P3: 'parcial3',
                    SEMESTRAL: 'semestral',
                    EXTRA: 'extra'

                } [parcial];


                let input = fila.querySelector(
                    '.' + claseInput
                );



                if (input) {

                    datos.push({

                        alumno_id: alumno,

                        materiaId: materiaSeleccionada,

                        parcial: parcial,

                        calificacion: input.value

                    });
                }

            });



        if (datos.length === 0) {

            Swal.fire(
                "Sin datos",
                "No hay calificaciones para guardar",
                "warning"
            );

            return;

        }



        fetch('/calificaciones/guardar', {

                method: 'POST',

                headers: {

                    'Content-Type': 'application/json',

                    'X-CSRF-TOKEN': document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content

                },

                body: JSON.stringify({

                    datos: datos

                })

            })

            .then(res => res.json())

            .then(resp => {


                Swal.fire(
                    "Guardado",
                    "Calificaciones guardadas correctamente",
                    "success"
                );


                cargarMateria();


            })

            .catch(error => {


                console.error(error);


                Swal.fire(
                    "Error",
                    "No se pudieron guardar las calificaciones",
                    "error"
                );


            });


    });

function controlarParciales() {


    let p1 = document.querySelectorAll('.parcial1');
    let p2 = document.querySelectorAll('.parcial2');
    let p3 = document.querySelectorAll('.parcial3');
    let sem = document.querySelectorAll('.semestral');
    let extra = document.querySelectorAll('.extra');



    function terminado(lista) {

        return [...lista].every(input =>
            input.value.trim() !== ''
        );

    }



    // Siempre inicia P1 habilitado

    p1.forEach(input => {
        input.disabled = false;
    });



    // Si P1 está completo habilita P2

    if (terminado(p1)) {

        p1.forEach(i => i.readOnly = true);

        p2.forEach(i => i.disabled = false);

    }



    // Si P2 está completo habilita P3

    if (terminado(p2)) {

        p2.forEach(i => i.readOnly = true);

        p3.forEach(i => i.disabled = false);

    }



    // Si P3 está completo habilita Semestral

    if (terminado(p3)) {

        p3.forEach(i => i.readOnly = true);

        sem.forEach(i => i.disabled = false);

    }



    // Si Semestral está completo habilita Extra

    if (terminado(sem)) {

        sem.forEach(i => i.readOnly = true);

        extra.forEach(i => i.disabled = false);

    }


}

function obtenerParcialActivo() {

    if (document.querySelector('.parcial1:not([readonly])')) {
        return 'P1';
    }

    if (document.querySelector('.parcial2:not([readonly])')) {
        return 'P2';
    }

    if (document.querySelector('.parcial3:not([readonly])')) {
        return 'P3';
    }

    if (document.querySelector('.semestral:not([readonly])')) {
        return 'SEMESTRAL';
    }

    if (document.querySelector('.extra:not([readonly])')) {
        return 'EXTRA';
    }

}
</script>