<style>
#modalAlumno.glass-modal,
#modalAlumno .glass-modal {
    background: #ffffff !important;
    border: none;
    border-radius: 20px;
    overflow: hidden;
    color: #1e293b;
    box-shadow: 0 15px 40px rgba(0, 0, 0, .15);
}

#modalAlumno .modal-header {
    background: linear-gradient(135deg, rgb(73, 164, 190), #1E6FA8) !important;
    color: #fff;
    border-bottom: none;
    padding: 1.2rem 1.5rem;
}

#modalAlumno .modal-body {
    background: #f8fafc;
    padding: 1.5rem;
}

#modalAlumno .accordion-item {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 16px !important;
    margin-bottom: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    overflow: hidden;
    transition: all 0.3s ease;
}

#modalAlumno .accordion-item:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    border-color: #cbd5e1;
}

#modalAlumno .accordion-button {
    background: #f8fafc !important;
    color: #1e293b !important;
    font-weight: 600;
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid transparent;
}

#modalAlumno .accordion-button:not(.collapsed) {
    background: linear-gradient(135deg, rgba(73, 164, 190, 0.08) 0%, rgba(30, 111, 168, 0.08) 100%) !important;
    color: #1E6FA8 !important;
    border-bottom: 1px solid #e2e8f0;
    box-shadow: none !important;
}

#modalAlumno .accordion-button:focus {
    box-shadow: none !important;
}

#modalAlumno .accordion-body {
    background: white;
    padding: 1.5rem;
}

#modalAlumno .form-label {
    font-weight: 600;
    color: #334155;
    font-size: 0.85rem;
    margin-bottom: 6px;
    display: inline-block;
}

#modalAlumno .form-control-premium,
#modalAlumno .form-select-premium,
#modalAlumno textarea.form-control-premium {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    color: #1e293b;
    font-size: 0.9rem;
    padding: 0.65rem 1rem;
    min-height: 44px;
    transition: all 0.2s ease-in-out;
}

#modalAlumno .form-control-premium:hover,
#modalAlumno .form-select-premium:hover {
    border-color: #94a3b8;
}

#modalAlumno .form-control-premium:focus,
#modalAlumno .form-select-premium:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
    outline: none;
}

#modalAlumno .modal-footer {
    background: #f8fafc !important;
    border-top: 1px solid #e2e8f0;
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

#modalAlumno .btn-premium-cancel {
    background: #f1f5f9;
    color: #475569 !important;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    padding: 0.6rem 1.5rem;
    font-weight: 600;
    transition: all 0.25s ease;
}

#modalAlumno .btn-premium-cancel:hover {
    background: #e2e8f0;
    color: #1e293b !important;
    transform: translateY(-1px);
}

#modalAlumno .btn-premium-save {
    background: linear-gradient(135deg, rgb(73, 164, 190) 0%, #1E6FA8 100%);
    color: white !important;
    border: none;
    border-radius: 12px;
    padding: 0.6rem 1.5rem;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(30, 111, 168, 0.25);
    transition: all 0.25s ease;
}

#modalAlumno .btn-premium-save:hover {
    background: linear-gradient(135deg, #1E6FA8 0%, #154c75 100%);
    box-shadow: 0 6px 16px rgba(30, 111, 168, 0.35);
    transform: translateY(-1px);
}
</style>
<div class="modal fade" id="modalAlumno" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <form id="formAlumno" class="modal-content glass-modal" novalidate>

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-person-plus-fill me-2"></i>
                    Alta de Alumno
                </h5>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body p-4">

                <div class="accordion" id="accordionAlumno">

                    <!-- DATOS PERSONALES -->
                    <div class="accordion-item mb-3 border-0 shadow-sm rounded">
                        <h2 class="accordion-header">
                            <button class="accordion-button rounded" type="button" data-bs-toggle="collapse"
                                data-bs-target="#datosPersonales">
                                <i class="bi bi-person-badge-fill me-2 text-info"></i> Datos personales
                            </button>
                        </h2>
                        
                        <div id="datosPersonales" class="accordion-collapse collapse show"
                            data-bs-parent="#accordionAlumno">

                            <div class="accordion-body">
                                <div class="row">

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                                        <input type="text" name="nombre" class="form-control form-control-premium"
                                            required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Apellido Paterno <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="apPaterno" class="form-control form-control-premium"
                                            required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Apellido Materno</label>
                                        <input type="text" name="apMaterno" class="form-control form-control-premium">
                                    </div>

                                     <div class="col-md-4 mb-3">
                                        <label class="form-label">Fecha nacimiento <span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="fechaNacimiento"
                                            class="form-control form-control-premium" required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Celular <span class="text-danger">*</span></label>
                                        <input type="number" name="celularAlumno"
                                            class="form-control form-control-premium" required>
                                    </div>

                                     <div class="col-md-4 mb-3">
                                         <label class="form-label">Correo</label>
                                         <input type="email" name="correoAlumno"
                                             class="form-control form-control-premium">
                                     </div>

                                     <div class="col-md-4 mb-3">
                                         <label class="form-label">CURP</label>
                                         <input type="text" name="curp"
                                             class="form-control form-control-premium" maxlength="18">
                                     </div>
 
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TUTOR -->
                     <div class="accordion-item mb-3 border-0 shadow-sm rounded">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse"
                                data-bs-target="#datosTutor">
                                <i class="bi bi-people me-2 text-primary"></i> Datos del tutor
                            </button>
                        </h2>

                        <div id="datosTutor" class="accordion-collapse collapse" data-bs-parent="#accordionAlumno">

                            <div class="accordion-body">
                                <div class="row">

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Nombre tutor <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="tutor" class="form-control form-control-premium">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Parentesco <span class="text-danger">*</span></label>
                                        <input type="text" name="parentesco" class="form-control form-control-premium">
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

                    <div class="accordion-item mb-3 border-0 shadow-sm rounded">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse"
                                data-bs-target="#direccion">
                                <i class="bi bi-geo-alt me-2 text-primary"></i> Dirección
                            </button>
                        </h2>

                        <div id="direccion" class="accordion-collapse collapse" data-bs-parent="#accordionAlumno">

                            <div class="accordion-body">
                                <div class="row">

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Calle</label>
                                        <input type="text" name="calle" class="form-control form-control-premium">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Colonia</label>
                                        <input type="text" name="colonia" class="form-control form-control-premium">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Localidad</label>
                                        <input type="text" name="localidad" class="form-control form-control-premium">
                                    </div>

                                </div>
                            </div>
                        </div>
</div>

                    <div class="accordion-item mb-3 border-0 shadow-sm rounded">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse"
                                data-bs-target="#academicos">

                                <i class="bi bi-mortarboard me-2 text-primary"></i> Datos académicos
                            </button>

                        </h2>

                        <div id="academicos" class="accordion-collapse collapse" data-bs-parent="#accordionAlumno">

                            <div class="accordion-body">
                                <div class="row">

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Escuela procedencia</label>
                                        <input type="text" name="escuelaProcedencia"
                                            class="form-control form-control-premium">
                                    </div> 

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Grupo <span class="text-danger">*</span></label>
                                        <select name="id_Grupo" class="form-select form-select-premium">
                                            <option value="">Seleccione</option>
                                            @foreach($grupos as $grupo)
                                            <option value="{{ $grupo['id'] }}">
                                                {{ $grupo['clave'] }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div> 

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Generación <span class="text-danger">*</span></label>
                                        <select name="id_Generacion" id="id_Generacion"
                                            class="form-select form-select-premium" required>
                                            <option value="">Seleccione una generación</option>
                                            @foreach($generaciones as $generacion)
                                            <option value="{{ $generacion['id'] }}">
                                                Generación {{ $generacion['generacion'] }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Estado del Alumno <span class="text-danger">*</span></label>
                                        <select name="statusAlumno" class="form-select form-select-premium" required>
                                            <option value="ACTIVO">ACTIVO</option>
                                            <option value="INACTIVO">INACTIVO</option>
                                            <option value="BAJA_TEMPORAL">BAJA TEMPORAL</option>
                                            <option value="CERTIFICADO">CERTIFICADO</option>
                                            <option value="REINSCRIPCION">REINSCRIPCIÓN</option>
                                        </select>
                                    </div>

                                     <!-- Campos de Certificado (Se muestran solo si el estado es CERTIFICADO) -->
                                     <div id="seccionCertificado" class="row mt-2" style="display: none; padding-right: 0; margin-left: 0; margin-right: 0; width: 100%;">
                                         <div class="col-md-4 mb-3 ps-0">
                                             <label class="form-label">Folio del Certificado</label>
                                             <input type="text" name="folioCertificado" class="form-control form-control-premium">
                                         </div>
                                         <div class="col-md-4 mb-3">
                                             <label class="form-label">¿Recogió Certificado?</label>
                                             <select name="recogioCertificado" class="form-select form-select-premium">
                                                 <option value="NO">NO</option>
                                                 <option value="SI">SI</option>
                                             </select>
                                         </div>
                                         <div class="col-md-4 mb-3 pe-0">
                                             <label class="form-label">Fecha de Entrega de Certificado</label>
                                             <input type="date" name="fechaRecogioCertificado" class="form-control form-control-premium">
                                         </div>
                                     </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3 border-0 shadow-sm rounded">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse"
                                data-bs-target="#cursosExtra">
                                <i class="bi bi-journal-bookmark me-2 text-primary"></i> Cursos extracurriculares
                            </button>
                        </h2>

                        <div id="cursosExtra" class="accordion-collapse collapse" data-bs-parent="#accordionAlumno">

                            <div class="accordion-body">

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="computacion">
                                    <label class="form-check-label">Curso de computación</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="reparacion">
                                    <label class="form-check-label">Curso reparación digital</label>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="diseno">
                                    <label class="form-check-label">Curso diseño gráfico</label>
                                </div>

                                <div class="mt-3">
                                    <label class="form-label">Observaciones</label>
                                    <textarea name="observaciones" class="form-control form-control-premium"></textarea>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn-premium-cancel" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button class="btn-premium-save" type="submit">
                    <i class="bi bi-floppy-fill me-2"></i>
                    Guardar Alumno
                </button>
            </div>

        </form>
    </div>
</div>