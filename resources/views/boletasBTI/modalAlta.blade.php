@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/estilosModal.css') }}">
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

                    @if(strtolower(session('rol')) === 'admin')
                    <select id="selectorMateria" class="form-select w-50 mx-auto"></select>
                    @else
                    <span class="badge px-3 py-2 mb-2" id="contadorMateria"></span>
                    <h4 id="tituloMateria" class="mt-2 fw-bold"></h4>
                    @endif

                </div>
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
const esAdmin = @json(strtolower(session('rol')) === 'admin');
let materiasActuales = [];
let materiaActual = 0;
let materiaSeleccionada = null;

function abrirSemestre(grado) {

    const modalElement = document.getElementById('modalCalificaciones');

    // Limpiar cualquier backdrop que haya quedado anteriormente
    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
        backdrop.remove();
    });

    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('padding-right');

    const tbody = document.getElementById('tablaAlumnos');

    tbody.innerHTML = `
        <tr>
            <td colspan="7" class="text-center py-4">
                <div class="spinner-border text-primary"></div>
                <div class="mt-2">
                    Cargando materias...
                </div>
            </td>
        </tr>
    `;

    const modal = bootstrap.Modal.getOrCreateInstance(modalElement);

    modal.show();

    fetch(`/calificaciones/materias/${grado}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(async response => {

        const texto = await response.text();

        console.log('Respuesta materias:', texto);

        if (!response.ok) {
            throw new Error(texto);
        }

        return JSON.parse(texto);
    })
    .then(materias => {

        if (!materias || materias.length === 0) {

            Swal.fire(
                'Sin materias',
                'No se encontraron materias para este semestre.',
                'warning'
            );

            return;
        }

        materiasActuales = materias;
        materiaActual = 0;

        if (esAdmin) {

            const select = document.getElementById('selectorMateria');

            select.innerHTML = '';

            materias.forEach((m, i) => {

                select.innerHTML += `
                    <option value="${i}">
                        ${m.MATERIA}
                    </option>
                `;

            });
        }

        cargarMateria();

    })
    .catch(error => {

        console.error('ERROR:', error);

        Swal.fire(
            'Error',
            'No se pudieron cargar las materias.',
            'error'
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

    if (!esAdmin) {
        document.getElementById('contadorMateria').innerText =
            `Materia ${materiaActual + 1} de ${materiasActuales.length}`;

        document.getElementById('tituloMateria').innerText =
            materia.MATERIA;
    } else {
        document.getElementById('selectorMateria').value = materiaActual;
    }

    fetch(`/calificaciones/alumnos/${materia.GRADO}/${materia.GRUPO}/${materia.ID}`)
        .then(res => res.json())
        .then(alumnos => {
            let tbody = document.getElementById('tablaAlumnos');
            let html = "";
            alumnos.forEach((alumno, index) => {
                html += `
    <tr data-alumno="${alumno.id}">
        <td>${index+1}</td>
        <td>${alumno.nombre}</td>
        <td>
            <input
            type="number"
            class="form-control calificacion parcial1"
            value="${alumno.p1 ?? ''}"
            ${!esAdmin && alumno.p1 ? 'readonly' : ''}
        </td>
        <td>
            <input
            type="number"
            class="form-control calificacion parcial2"
            value="${alumno.p2 ?? ''}"
            ${!esAdmin && alumno.p2 ? 'readonly' : ''}
            ${!esAdmin ? 'disabled' : ''}>
        </td>
        <td>
            <input
            type="number"
            class="form-control calificacion parcial3"
            value="${alumno.p3 ?? ''}"
            ${!esAdmin && alumno.p3 ? 'readonly' : ''}
           ${!esAdmin ? 'disabled' : ''}>
        </td>
        <td>
            <input
            type="number"
            class="form-control calificacion semestral"
            value="${alumno.semestral ?? ''}"
            ${!esAdmin && alumno.semestral ? 'readonly' : ''}
            ${!esAdmin ? 'disabled' : ''}>
        </td>
        <td>
            <input
            type="number"
            class="form-control calificacion extra"
            value="${alumno.extra ?? ''}"
           ${!esAdmin && alumno.extra ? 'readonly' : ''}
            ${!esAdmin ? 'disabled' : ''}>
        </td>
    </tr>
    `;
            });
            tbody.innerHTML = html;
            if (!esAdmin) {
                controlarParciales();
            }
            
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
        let hayVacios = false;

        if (esAdmin) {

            document.querySelectorAll('#tablaAlumnos tr').forEach(fila => {

                datos.push({
                    alumno_id: fila.dataset.alumno,
                    materiaId: materiaSeleccionada,
                    p1: fila.querySelector('.parcial1').value,
                    p2: fila.querySelector('.parcial2').value,
                    p3: fila.querySelector('.parcial3').value,
                    semestral: fila.querySelector('.semestral').value,
                    extra: fila.querySelector('.extra').value
                });

            });

        } else {
            document.querySelectorAll('#tablaAlumnos tr').forEach(fila => {
                let alumno = fila.dataset.alumno;
                let claseInput = {
                    P1: 'parcial1',
                    P2: 'parcial2',
                    P3: 'parcial3',
                    SEMESTRAL: 'semestral',
                    EXTRA: 'extra'
                } [parcial];
                let input = fila.querySelector('.' + claseInput);
                if (!input) return;
                if (!input.disabled && !input.readOnly && input.value.trim() === '') {
                    hayVacios = true;
                    input.focus();
                    input.classList.add('is-invalid');
                    return;
                }
                input.classList.remove('is-invalid');
                datos.push({
                    alumno_id: alumno,
                    materiaId: materiaSeleccionada,
                    parcial: parcial,
                    calificacion: input.value
                });
            });
            if (datos.length === 0) {
                Swal.fire(
                    "Sin datos",
                    "No hay calificaciones para guardar",
                    "warning"
                );
                return;
            }

            if (hayVacios) {
                Swal.fire(
                    "Campos incompletos",
                    "Debe capturar la calificación de todos los alumnos antes de guardar.",
                    "warning"
                );
                return;
            }

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
                Swal.fire({
                    icon: "success",
                    title: "Guardado",
                    text: "Calificaciones guardadas correctamente",
                    timer: 1000,
                    showConfirmButton: false
                });
                setTimeout(() => {
                    if (!esAdmin) {

                        if (materiaActual < materiasActuales.length - 1) {
                            materiaActual++;
                            cargarMateria();
                        } else {
                            bootstrap.Modal.getInstance(
                                document.getElementById('modalCalificaciones')
                            ).hide();

                            Swal.fire(
                                "Proceso terminado",
                                "Se capturaron las calificaciones de todas las materias.",
                                "success"
                            );
                        }

                    } else {

                        Swal.fire(
                            "Guardado",
                            "Las calificaciones fueron actualizadas.",
                            "success"
                        );

                    }
                }, 1100);
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
    p1.forEach(input => {
        input.disabled = false;
    });
    if (terminado(p1)) {
        p1.forEach(i => i.readOnly = true);
        p2.forEach(i => i.disabled = false);
    }
    if (terminado(p2)) {
        p2.forEach(i => i.readOnly = true);
        p3.forEach(i => i.disabled = false);
    }
    if (terminado(p3)) {
        p3.forEach(i => i.readOnly = true);
        sem.forEach(i => i.disabled = false);
    }
    if (terminado(sem)) {
        sem.forEach(i => i.readOnly = true);
        extra.forEach(i => i.disabled = false);
    }
}

function obtenerParcialActivo() {

    if (esAdmin) {
        return null;
    }

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

if (esAdmin) {

    document.getElementById('selectorMateria').addEventListener('change', function () {

        materiaActual = parseInt(this.value);

        cargarMateria();

    });

}

document.getElementById('modalCalificaciones')
    .addEventListener('hidden.bs.modal', function () {

        document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
            backdrop.remove();
        });

        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('padding-right');
        document.body.style.removeProperty('overflow');
    });


</script>