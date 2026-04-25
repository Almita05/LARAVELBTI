<div class="modal fade" id="modalAlumno" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form id="formAlumno" class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Alta Alumno</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row">

                    <!-- DATOS PERSONALES -->
                    <div class="col-12">
                        <h6 class="text-primary">Datos personales</h6>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>Nombre<span style="color:red;">*</span></label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>Apellido Paterno<span style="color:red;">*</span></label>
                        <input type="text" name="apPaterno" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>Apellido Materno<span style="color:red;">*</span></label>
                        <input type="text" name="apMaterno" class="form-control">
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>Fecha de nacimiento<span style="color:red;">*</span></label>
                        <input type="date" name="fechaNacimiento" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>Celular alumno<span style="color:red;">*</span></label>
                        <input type="number" name="celularAlumno" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>Correo</label>
                        <input type="email" name="correoAlumno" class="form-control">
                    </div>

                    <!-- TUTOR -->
                    <div class="col-12 mt-3">
                        <h6 class="text-primary">Datos del tutor</h6>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>Nombre tutor<span style="color:red;">*</span></label>
                        <input type="text" name="tutor" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>Parentesco<span style="color:red;">*</span></label>
                        <input type="text" name="parentesco" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>Teléfono tutor<span style="color:red;">*</span></label>
                        <input type="number" name="telefonoTutor" class="form-control" required>
                    </div>

                    <!-- DIRECCIÓN -->
                    <div class="col-12 mt-3">
                        <h6 class="text-primary">Dirección</h6>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>Calle</label>
                        <input type="text" name="calle" class="form-control">
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>Colonia</label>
                        <input type="text" name="colonia" class="form-control">
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>Localidad</label>
                        <input type="text" name="localidad" class="form-control">
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>Municipio</label>
                        <input type="text" name="municipio" class="form-control">
                    </div>

                    <!-- ESCUELA -->
                    <div class="col-12 mt-3">
                        <h6 class="text-primary">Datos académicos</h6>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>Escuela procedencia</label>
                        <input type="text" name="escuelaProcedencia" class="form-control">
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>Grupo<span style="color:red;">*</span></label>
                        <select name="id_Grupo" class="form-control" required>
                            <option value="">Seleccione un grupo</option>

                            @foreach($grupos as $grupo)
                            <option value="{{ $grupo['id'] }}">
                                {{ $grupo['clave'] }}
                            </option>
                            @endforeach

                        </select>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input class="form-check-input" type="checkbox" id="chkEquivalencia" name="equivalencia">
                            <label>Equivalencia de estudios</label>
                        </div>

                        <div class="col-md-6 mb-3">
                            <input class="form-check-input" type="checkbox" name="martinez">
                            <label>Viene de Martínez</label>
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <h6 class="text-primary">Cursos extracurriculares</h6>
                    </div>
                    <div id="cursos">
                        <div class="col-md-12 mb-3">
                            <input class="form-check-input" type="checkbox" name="martinez">
                            <label>Curso de computación</label>
                        </div>
                        <div class="col-md-12 mb-3">
                            <input class="form-check-input" type="checkbox" name="martinez">
                            <label>Curso de reparación de sistemas digitales</label>
                        </div>
                        <div class="col-md-12 mb-3">
                            <input class="form-check-input" type="checkbox" name="martinez">
                            <label>Curso de diseño Gráfico</label>
                        </div>
                        <div class="col-md-12 mb-3">
                            <input class="form-check-input" type="checkbox" name="martinez">
                            <label>Curso de inglés</label>
                        </div>
                    </div>
                    <div class="col-md-12 mb-2">
                        <label>Observaciones</label>
                        <textarea name="observaciones" class="form-control"></textarea>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-success" type="submit">Guardar</button>
            </div>

        </form>
    </div>
</div>

<script>
document.addEventListener('submit', function(e) {

    if (e.target.id === 'formAlumno') {

        e.preventDefault();

        let formData = new FormData(e.target);

        let data = Object.fromEntries(formData.entries());

        fetch('/alumnos', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                alert('Alumno guardado');

                let modal = bootstrap.Modal.getInstance(document.getElementById('modalAlumno'));
                modal.hide();

                location.reload();
            });

    }

});
</script>