<div class="modal fade" id="modalAlumno" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form id="formAlumno" class="modal-content" novalidate>

            <div class="modal-header">
                <h5 class="modal-title">Alta Alumno</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">

                <div class="accordion" id="accordionAlumno">

                    <!-- DATOS PERSONALES -->
                    <div class="accordion-item mb-3 border-0 shadow-sm rounded">
                        <h2 class="accordion-header">
                            <button class="accordion-button rounded" type="button" data-bs-toggle="collapse"
                                data-bs-target="#datosPersonales">
                                <i class="bi bi-person-badge me-2 text-primary"></i> Datos personales
                            </button>
                        </h2>

                        <div id="datosPersonales" class="accordion-collapse collapse show"
                            data-bs-parent="#accordionAlumno">

                            <div class="accordion-body">
                                <div class="row">

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                                        <input type="text" name="nombre"
                                            class="form-control form-control-premium" required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Apellido Paterno <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="apPaterno"
                                            class="form-control form-control-premium" required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Apellido Materno</label>
                                        <input type="text" name="apMaterno"
                                            class="form-control form-control-premium">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Fecha nacimiento <span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="fechaNacimiento"
                                            class="form-control form-control-premium" required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Celular <span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="celularAlumno"
                                            class="form-control form-control-premium" required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Correo</label>
                                        <input type="email" name="correoAlumno"
                                            class="form-control form-control-premium">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TUTOR -->
                    <div class="accordion-item mb-3 border-0 shadow-sm rounded">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed rounded" type="button"
                                data-bs-toggle="collapse" data-bs-target="#datosTutor">
                                <i class="bi bi-people me-2 text-primary"></i> Datos del tutor
                            </button>
                        </h2>

                        <div id="datosTutor" class="accordion-collapse collapse"
                            data-bs-parent="#accordionAlumno">

                            <div class="accordion-body">
                                <div class="row">

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Nombre tutor <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="tutor"
                                            class="form-control form-control-premium">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Parentesco <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="parentesco"
                                            class="form-control form-control-premium">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Teléfono tutor <span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="telefonoTutor"
                                            class="form-control form-control-premium">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DIRECCIÓN -->
                    <div class="accordion-item mb-3 border-0 shadow-sm rounded">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed rounded" type="button"
                                data-bs-toggle="collapse" data-bs-target="#direccion">
                                <i class="bi bi-geo-alt me-2 text-primary"></i> Dirección
                            </button>
                        </h2>

                        <div id="direccion" class="accordion-collapse collapse"
                            data-bs-parent="#accordionAlumno">

                            <div class="accordion-body">
                                <div class="row">

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Calle</label>
                                        <input type="text" name="calle"
                                            class="form-control form-control-premium">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Colonia</label>
                                        <input type="text" name="colonia"
                                            class="form-control form-control-premium">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Localidad</label>
                                        <input type="text" name="localidad"
                                            class="form-control form-control-premium">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DATOS ACADÉMICOS -->
                    <div class="accordion-item mb-3 border-0 shadow-sm rounded">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed rounded" type="button"
                                data-bs-toggle="collapse" data-bs-target="#academicos">
                                <i class="bi bi-mortarboard me-2 text-primary"></i> Datos académicos
                            </button>
                        </h2>

                        <div id="academicos" class="accordion-collapse collapse"
                            data-bs-parent="#accordionAlumno">

                            <div class="accordion-body">
                                <div class="row">

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Escuela procedencia</label>
                                        <input type="text" name="escuelaProcedencia"
                                            class="form-control form-control-premium">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Grupo <span
                                                class="text-danger">*</span></label>
                                        <select name="id_Grupo"
                                            class="form-select form-select-premium">
                                            <option value="">Seleccione</option>
                                            @foreach($grupos as $grupo)
                                            <option value="{{ $grupo['id'] }}">
                                                {{ $grupo['clave'] }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CURSOS -->
                    <div class="accordion-item mb-3 border-0 shadow-sm rounded">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed rounded" type="button"
                                data-bs-toggle="collapse" data-bs-target="#cursosExtra">
                                <i class="bi bi-journal-bookmark me-2 text-primary"></i> Cursos extracurriculares
                            </button>
                        </h2>

                        <div id="cursosExtra" class="accordion-collapse collapse"
                            data-bs-parent="#accordionAlumno">

                            <div class="accordion-body">

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox"
                                        name="computacion">
                                    <label class="form-check-label">Curso de computación</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox"
                                        name="reparacion">
                                    <label class="form-check-label">Curso reparación digital</label>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox"
                                        name="diseno">
                                    <label class="form-check-label">Curso diseño gráfico</label>
                                </div>

                                <div class="mt-3">
                                    <label class="form-label">Observaciones</label>
                                    <textarea name="observaciones"
                                        class="form-control form-control-premium"></textarea>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Cancelar</button>

                <button class="btn btn-premium btn-primary"
                    type="submit"> Guardar
                </button>
            </div>

        </form>
    </div>
</div>