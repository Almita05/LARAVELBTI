<div class="modal fade" id="modalCalificaciones" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: #F0F7F8; border: 1px solid #CBE4E8; color: #1E5B66; display: inline-flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                        <i class="ti ti-checklist"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold" style="font-size: 1.05rem; color: #0F172A; margin: 0;">
                            Captura de Calificaciones
                        </h5>
                        <small style="font-size: 0.75rem; color: #64748B;">Sábana de calificaciones parciales y semestrales</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body p-4" style="background: #F8FAFC;">
                <div class="materia-info mb-4 p-3 bg-white border rounded-3 shadow-xs" style="border-color: #E2E8F0 !important;">

                    @if(strtolower(session('rol')) === 'admin')
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <label class="form-label mb-0 fw-semibold text-slate-700 fs-8"><i class="ti ti-book me-1"></i> Materia Seleccionada:</label>
                        </div>
                        <div class="col-md-9">
                            <select id="selectorMateria" class="form-select form-select-sm" style="height: 38px; border-color: #CBD5E1;"></select>
                        </div>
                    </div>
                    @else
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h5 id="tituloMateria" class="mb-0 fw-bold text-slate-800 fs-6"></h5>
                        <span class="badge" id="contadorMateria" style="background: #F0F7F8; color: #1E5B66; border: 1px solid #CBE4E8; padding: 4px 10px; border-radius: 999px; font-size: 0.75rem;"></span>
                    </div>
                    @endif

                </div>
                <div class="card border shadow-xs" style="border-radius: 8px; overflow: hidden; border-color: #E2E8F0 !important;">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-center tabla-calificaciones mb-0">
                            <thead style="background: #F8FAFC;">
                                <tr style="font-size: 0.75rem; text-transform: uppercase; color: #475569; letter-spacing: 0.04em;">
                                    <th style="width: 50px;">#</th>
                                    <th class="text-start">Alumno</th>
                                    <th style="width: 100px;">P1</th>
                                    <th style="width: 100px;">P2</th>
                                    <th style="width: 100px;">P3</th>
                                    <th style="width: 110px;">Semestral</th>
                                    <th style="width: 120px;">Extraordinario</th>
                                </tr>
                            </thead>
                            <tbody id="tablaAlumnos" class="bg-white">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background: #FFFFFF; border-top: 1px solid #E2E8F0; padding: 14px 24px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
                <button type="button" class="btn btn-primary" id="guardarCalificaciones" style="background: #1E5B66; border-color: #1E5B66;">
                    <i class="ti ti-device-floppy me-1"></i> Guardar
                </button>
            </div>
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
    fetch(`/calificaciones/materias/${grado}`)
        .then(res => res.json())
        .then(materias => {
            if (!materias || materias.length === 0) {
                Swal.fire(
                    "Sin materias",
                    "No se encontraron materias para este semestre",
                    "warning"
                );
                return;
            }
            materiasActuales = materias;
            if (esAdmin) {

                let select = document.getElementById("selectorMateria");

                select.innerHTML = "";

                materias.forEach((m, i) => {

                    select.innerHTML += `
            <option value="${i}">
                ${m.MATERIA}
            </option>
        `;

                });

            }
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
            const modal = bootstrap.Modal.getInstance(
                document.getElementById('modalCalificaciones')
            );
            if (!modal) {
                new bootstrap.Modal(
                    document.getElementById('modalCalificaciones')
                ).show();
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


</script>