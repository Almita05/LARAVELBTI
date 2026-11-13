<style>
/* ==========================================================================
   MODAL ALTA / DETALLE / EDICIÓN DE ALUMNO (ESTILO SHADCN/UI & RADIX DIALOG)
   ========================================================================== */
#modalAlumno .modal-content,
#modalAlumno.glass-modal,
#modalAlumno .glass-modal {
    background: #FFFFFF !important;
    border: 1px solid #E2E8F0 !important;
    border-radius: 0!important;
    box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.12), 0 8px 10px -6px rgba(15, 23, 42, 0.08) !important;
    overflow: hidden;
}

/* Modal Header Blanco Ejecutivo */
#modalAlumno .modal-header {
    background: #FFFFFF !important;
    padding: 18px 24px !important;
    border-bottom: 1px solid #E2E8F0 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
}

#modalAlumno .modal-header-icon {
    width: 36px;
    height: 36px;
    border-radius: 0;
    background: #EFF6F7;
    border: 1px solid #BBDBE1;
    color: #26687B;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

#modalAlumno .modal-title {
    font-size: 1.05rem !important;
    font-weight: 600 !important;
    color: #0F172A !important;
    margin: 0 !important;
    display: inline-flex;
    align-items: center;
    letter-spacing: -0.01em;
}

#modalAlumno .btn-close-modal {
    width: 32px;
    height: 32px;
    border-radius: 0;
    background: transparent;
    border: none;
    color: #64748B;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s ease;
}

#modalAlumno .btn-close-modal:hover {
    background: #F1F5F9;
    color: #0F172A;
}

/* Modal Body */
#modalAlumno .modal-body {
    background: #F8FAFC !important;
    padding: 20px 24px !important;
    max-height: calc(85vh - 130px) !important;
    overflow-y: auto !important;
    scrollbar-width: thin;
}

/* Acordeones / Secciones */
#modalAlumno .accordion-item {
    background: #FFFFFF !important;
    border: 1px solid #E2E8F0 !important;
    border-radius: 0!important;
    margin-bottom: 14px !important;
    overflow: hidden !important;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.02) !important;
    transition: all 0.2s ease;
}

#modalAlumno .accordion-header {
    background: #FFFFFF !important;
    margin: 0 !important;
    border: none !important;
}

#modalAlumno .accordion-button {
    padding: 14px 18px !important;
    background: #FFFFFF !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    cursor: pointer !important;
    user-select: none !important;
    border: none !important;
    border-bottom: 1px solid transparent !important;
    box-shadow: none !important;
    transition: background 0.15s ease, border-color 0.15s ease !important;
    font-size: 0.9rem !important;
    font-weight: 600 !important;
    color: #0F172A !important;
}

#modalAlumno .accordion-button:not(.collapsed) {
    background: #F8FAFC !important;
    border-bottom-color: #E2E8F0 !important;
    color: #0F172A !important;
    box-shadow: none !important;
}

#modalAlumno .accordion-button:focus {
    box-shadow: none !important;
    outline: none !important;
}

/* Chevron indicador derecho */
#modalAlumno .accordion-button::after {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23475569'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e") !important;
    width: 16px !important;
    height: 16px !important;
    background-size: 16px !important;
    transition: transform 0.2s ease-in-out !important;
}

#modalAlumno .accordion-button:not(.collapsed)::after {
    transform: rotate(-180deg) !important;
}

#modalAlumno .accordion-body {
    background: #FFFFFF !important;
    padding: 18px 20px !important;
}

/* Form Labels */
#modalAlumno .form-label {
    display: block !important;
    font-size: 0.75rem !important;
    font-weight: 600 !important;
    color: #334155 !important;
    margin-bottom: 6px !important;
}

#modalAlumno .form-label .text-danger,
#modalAlumno .requerido-normal {
    color: #DC2626 !important;
    margin-left: 2px !important;
    font-weight: 700 !important;
}

/* Inputs, Selects & Textareas */
#modalAlumno .form-control,
#modalAlumno .form-select,
#modalAlumno .form-control-premium,
#modalAlumno .form-select-premium,
#modalAlumno textarea.form-control-premium {
    width: 100% !important;
    height: 38px !important;
    min-height: 38px !important;
    padding: 0 12px !important;
    border: 1px solid #E2E8F0 !important;
    border-radius: 0!important;
    font-size: 0.875rem !important;
    color: #0F172A !important;
    background-color: #FFFFFF !important;
    transition: all 0.15s ease !important;
}

#modalAlumno textarea.form-control-premium {
    height: auto !important;
    min-height: 64px !important;
    padding: 8px 12px !important;
}

#modalAlumno .form-control:focus,
#modalAlumno .form-select:focus,
#modalAlumno .form-control-premium:focus,
#modalAlumno .form-select-premium:focus,
#modalAlumno textarea.form-control-premium:focus {
    border-color: #26687B !important;
    outline: none !important;
    box-shadow: 0 0 0 3px rgba(38, 104, 123, 0.12) !important;
}

#modalAlumno input[name="curp"] {
    font-family: 'JetBrains Mono', monospace !important;
    text-transform: uppercase !important;
}

/* Custom Select Dropdown Arrow */
#modalAlumno .form-select,
#modalAlumno .form-select-premium {
    appearance: none !important;
    -webkit-appearance: none !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%2364748B'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat !important;
    background-position: right 10px center !important;
    background-size: 14px 10px !important;
    padding-right: 32px !important;
}

/* Callout Neutro Institucional (Aviso CCT) */
#modalAlumno #avisoSeleccionarCCT {
    background: #F8FAFC !important;
    border: 1px solid #E2E8F0 !important;
    border-left: 3px solid #26687B !important;
    border-radius: 0!important;
    padding: 10px 14px !important;
    font-size: 0.8125rem !important;
    color: #475569 !important;
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    margin: 14px 0 !important;
}

/* Sub-contenedor Modalidad Moodle */
#modalAlumno .moodle-subcontainer {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 0;
    padding: 14px 16px;
    margin-top: 12px;
}

#modalAlumno .badge-moodle-soft {
    background: #EFF6F7;
    color: #26687B;
    border: 1px solid #BBDBE1;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 0;
}

/* Footer & Buttons */
#modalAlumno .modal-footer {
    background: #FFFFFF !important;
    padding: 14px 24px !important;
    border-top: 1px solid #E2E8F0 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: flex-end !important;
    gap: 10px !important;
}

#modalAlumno .btn-premium-cancel {
    height: 38px !important;
    padding: 0 16px !important;
    border-radius: 0!important;
    background: #FFFFFF !important;
    border: 1px solid #E2E8F0 !important;
    color: #475569 !important;
    font-size: 0.875rem !important;
    font-weight: 500 !important;
    cursor: pointer !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all 0.15s ease !important;
}

#modalAlumno .btn-premium-cancel:hover {
    background: #F8FAFC !important;
    color: #0F172A !important;
    border-color: #CBD5E1 !important;
}

#modalAlumno .btn-premium-save {
    height: 38px !important;
    padding: 0 18px !important;
    border-radius: 0!important;
    background: #26687B !important;
    border: none !important;
    color: #FFFFFF !important;
    font-size: 0.875rem !important;
    font-weight: 500 !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
    cursor: pointer !important;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05) !important;
    transition: all 0.15s ease !important;
}

#modalAlumno .btn-premium-save:hover {
    background: #1F5565 !important;
    color: #FFFFFF !important;
}

/* Grupo Recomendado Box */
#modalAlumno .group-recommended {
    border: 1px solid #BBF7D0 !important;
    border-radius: 0!important;
    padding: 14px 16px !important;
    background: #F0FDF4 !important;
}

#modalAlumno .group-name {
    font-size: 1.15rem;
    font-weight: 700;
    color: #166534;
}

#modalAlumno .group-meta {
    font-size: 0.8125rem;
    color: #374151;
    line-height: 1.5;
}
</style>

<div class="modal fade" id="modalAlumno" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <form id="formAlumno" class="modal-content glass-modal" novalidate>

            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="modal-header-icon">
                        <i class="ti ti-user-plus"></i>
                    </div>
                    <h5 class="modal-title">
                        Alta de Alumno
                    </h5>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <!-- Botón Acceso Rápido Online en Modo Edición (Desactivado: se gestiona en Control Escolar Online) -->
                    <button type="button" class="btn btn-sm text-white shadow-sm" id="btnModalOnlineHeader" style="display: none !important; background: #26687B; border: none; border-radius: 0; font-weight: 500; padding: 0.38rem 0.9rem;" onclick="abrirModalOnlineDesdeEditar()" title="Configuración de Moodle y Pagos">
                        <i class="ti ti-device-laptop me-1"></i> <span id="txtBtnModalOnlineHeader">Modalidad Online</span>
                    </button>
                    <button type="button" class="btn-close-modal" data-bs-dismiss="modal" title="Cerrar">
                        <i class="ti ti-x" style="font-size: 18px;"></i>
                    </button>
                </div>
            </div>

            <div class="modal-body">
                <!-- Opción de Registro Histórico -->
                <div class="card p-3 border mb-3" style="background: #FFFFFF; border-color: #E2E8F0 !important; border-radius: 0!important;">
                    <div class="form-check form-switch mb-0 d-flex align-items-start gap-2">
                        <input class="form-check-input mt-1" type="checkbox" id="chkRegistroHistorico" name="registro_historico" value="1">
                        <div>
                            <label class="form-check-label fw-bold text-dark mb-0" for="chkRegistroHistorico" style="font-size: 0.875rem; cursor: pointer;">
                                <i class="ti ti-history me-1" style="color: #D97706;"></i> Registro de Alumno Histórico
                            </label>
                            <small class="text-muted d-block mt-0.5" style="font-size: 0.8125rem;">
                                Active esta opción si está registrando un alumno de ciclos anteriores con información incompleta. Se relajarán las validaciones y se permitirá seleccionar cualquier grupo (incluso inactivos o terminados).
                            </small>
                        </div>
                    </div>
                </div>

                <div class="accordion" id="accordionAlumno">

                    <!-- 1. DATOS PERSONALES -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#datosPersonales">
                                <span class="d-inline-flex align-items-center gap-2">
                                    <i class="ti ti-id" style="color: #26687B; font-size: 18px;"></i>
                                    <span>1. Datos personales</span>
                                </span>
                            </button>
                        </h2>
                        <div id="datosPersonales" class="accordion-collapse collapse show" data-bs-parent="#accordionAlumno">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Nombre(s) <span class="text-danger">*</span></label>
                                        <input type="text" name="nombre" class="form-control form-control-premium" placeholder="Ej. Juan" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Apellido Paterno <span class="text-danger requerido-normal">*</span></label>
                                        <input type="text" name="apPaterno" id="inputApPaterno" class="form-control form-control-premium" placeholder="Ej. Pérez" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Apellido Materno</label>
                                        <input type="text" name="apMaterno" class="form-control form-control-premium" placeholder="Ej. López">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Fecha de nacimiento <span class="text-danger requerido-normal">*</span></label>
                                        <input type="date" name="fechaNacimiento" id="inputFechaNacimiento" class="form-control form-control-premium" required>
                                        <small id="txtEdadCalculada" class="text-muted d-block mt-1" style="font-size:0.78rem;"></small>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">CURP</label>
                                        <input type="text" name="curp" class="form-control form-control-premium" placeholder="18 caracteres" maxlength="18">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Número de Control</label>
                                        <input type="text" name="numeroControl" class="form-control form-control-premium" placeholder="Opcional">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. CONTACTO Y TUTOR -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#datosContacto">
                                <span class="d-inline-flex align-items-center gap-2">
                                    <i class="ti ti-address-book" style="color: #26687B; font-size: 18px;"></i>
                                    <span>2. Contacto, tutor y domicilio</span>
                                </span>
                            </button>
                        </h2>
                        <div id="datosContacto" class="accordion-collapse collapse" data-bs-parent="#accordionAlumno">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Celular del Alumno <span class="text-danger requerido-normal">*</span></label>
                                        <input type="tel" name="celularAlumno" id="inputCelularAlumno" class="form-control form-control-premium" placeholder="10 dígitos" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Correo Electrónico</label>
                                        <input type="email" name="correoAlumno" class="form-control form-control-premium" placeholder="correo@ejemplo.com">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Nombre del Tutor</label>
                                        <input type="text" name="tutor" class="form-control form-control-premium">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Parentesco</label>
                                        <input type="text" name="parentesco" class="form-control form-control-premium" placeholder="Padre, Madre, etc.">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Teléfono del Tutor</label>
                                        <input type="tel" name="telefonoTutor" class="form-control form-control-premium">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Calle y Número</label>
                                        <input type="text" name="calle" class="form-control form-control-premium">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Colonia</label>
                                        <input type="text" name="colonia" class="form-control form-control-premium">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Localidad / Municipio</label>
                                        <input type="text" name="localidad" class="form-control form-control-premium">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. DATOS ACADÉMICOS Y GRUPOS (EL FLUJO DINÁMICO) -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#academicos">
                                <span class="d-inline-flex align-items-center gap-2">
                                    <i class="ti ti-school" style="color: #26687B; font-size: 18px;"></i>
                                    <span>3. Contexto académico y selección de grupo</span>
                                </span>
                            </button>
                        </h2>
                        <div id="academicos" class="accordion-collapse collapse show" data-bs-parent="#accordionAlumno">
                            <div class="accordion-body">
                                
                                <div class="row mb-3">
                                    <!-- CCT -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">CCT (Centro de Trabajo) <span class="text-danger requerido-normal">*</span></label>
                                        <select name="id_centroTrabajo" id="selectCCT" class="form-select form-select-premium" required>
                                            <option value="">Seleccionar CCT...</option>
                                            @if(isset($centrosTrabajo) && count($centrosTrabajo) > 0)
                                                @foreach($centrosTrabajo as $cct)
                                                    <option value="{{ $cct['id'] }}" 
                                                        data-programa="{{ $cct['nombrePrograma'] ?? '' }}" 
                                                        data-periodo="{{ $cct['nombrePeriodo'] ?? '' }}">
                                                        {{ $cct['nombre'] }} ({{ $cct['nombrePeriodo'] ?? 'General' }}) - {{ $cct['clave'] }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <!-- Periodo / Nivel -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Periodo de incorporación <span class="text-danger requerido-normal">*</span></label>
                                        <select name="id_nivel_academico" id="selectNivelAcademico" class="form-select form-select-premium" required>
                                            <option value="">Seleccionar periodo...</option>
                                        </select>
                                    </div>

                                    <!-- Estado Alumno -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Estado del alumno <span class="text-danger">*</span></label>
                                        <select name="statusAlumno" id="selectStatusAlumno" class="form-select form-select-premium" required>
                                            <option value="ACTIVO" selected>ACTIVO</option>
                                            <option value="PENDIENTE">PENDIENTE</option>
                                            <option value="INACTIVO">INACTIVO</option>
                                            <option value="BAJA_TEMPORAL">BAJA TEMPORAL</option>
                                            <option value="CERTIFICADO">CERTIFICADO</option>
                                            <option value="REINSCRIPCION">REINSCRIPCIÓN</option>
                                        </select>
                                    </div>

                                    <!-- BLOQUE DINÁMICO DE CERTIFICACIÓN -->
                                    <div id="boxDatosCertificado" class="col-12 mb-3 p-3 bg-light rounded-3 border" style="display: none; border-color: #E2E8F0 !important;">
                                        <div class="fw-bold text-dark mb-2 d-flex align-items-center gap-1" style="font-size: 0.85rem;">
                                            <i class="ti ti-certificate" style="color: #26687B;"></i> Datos de Certificación y Entrega de Documento
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <label class="form-label">Folio del Certificado</label>
                                                <input type="text" name="folioCertificado" id="inputFolioCertificado" class="form-control form-control-premium" placeholder="Ej. A12345678">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">¿Ya recogió el certificado?</label>
                                                <select name="recogioCertificado" id="selectRecogioCertificado" class="form-select form-select-premium">
                                                    <option value="0" selected>NO (En resguardo escolar)</option>
                                                    <option value="1">SÍ (Entregado al alumno/tutor)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4" id="boxFechaRecogioCertificado" style="display: none;">
                                                <label class="form-label">Fecha de entrega / recogida</label>
                                                <input type="date" name="fechaRecogioCertificado" id="inputFechaRecogioCertificado" class="form-control form-control-premium">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- CONTENEDOR DINÁMICO: FILTROS DE GRUPO Y RECOMENDACIÓN -->
                                <div id="seccionAcademicaDinamica" style="display: none;">
                                    
                                    <div class="alert alert-info py-2 px-3 mb-3 d-flex align-items-center gap-2" style="font-size:0.85rem; border-radius:0; background: #EFF6F7; border-color: #BBDBE1; color: #26687B;">
                                        <i class="ti ti-info-circle"></i>
                                        <div>Indique el día y jornada de interés para que el sistema proponga el <strong>grupo compatible más próximo a iniciar</strong>.</div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Día de asistencia <span class="text-danger">*</span></label>
                                            <select name="diaAsistencia" id="selectDiaAsistencia" class="form-select form-select-premium">
                                                <option value="">Seleccionar día de clase...</option>
                                                <option value="SABADO">Sábado</option>
                                                <option value="DOMINGO">Domingo</option>
                                                <option value="LUNES-VIERNES">Lunes a Viernes (Escolarizado)</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Jornada <span class="text-danger">*</span></label>
                                            <select name="jornadaHorario" id="selectJornada" class="form-select form-select-premium">
                                                <option value="">Seleccionar jornada...</option>
                                                <option value="MATUTINO">Matutino</option>
                                                <option value="VESPERTINO">Vespertino</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Generación</label>
                                            <select name="id_Generacion" id="selectGeneracion" class="form-select form-select-premium">
                                                <option value="">Pendiente de registro SEP</option>
                                            </select>
                                            <small class="text-muted d-block mt-1" style="font-size:0.75rem;">Se asigna oficialmente al certificar o registrar ante SEP.</small>
                                        </div>
                                    </div>

                                    <!-- TARJETA: GRUPOS COMPATIBLES Y RECOMENDACIÓN -->
                                    <div class="card p-3 border-0 bg-light rounded-3 mb-3" style="background: #F8FAFC !important; border: 1px solid #E2E8F0 !important;">
                                        <!-- SELECCIÓN DIRECTA DE GRUPO (SÓLO PARA REGISTRO HISTÓRICO) -->
                                        <div id="boxGrupoDirectoHistorico" style="display: none;" class="mb-3">
                                            <label class="form-label fw-bold text-dark">Grupo Histórico / Directo</label>
                                            <select id="selectGrupoHistorico" class="form-select form-select-premium">
                                                <option value="">Seleccionar grupo histórico...</option>
                                            </select>
                                            <small class="text-muted d-block mt-1" style="font-size: 0.78rem;">
                                                Aquí se muestran todos los grupos del CCT seleccionado (incluidos inactivos y terminados).
                                            </small>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-2 box-compatibles-cabecera">
                                            <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-1" style="font-size: 0.875rem;">
                                                <i class="ti ti-users-group" style="color: #26687B;"></i> Grupos compatibles
                                            </h6>
                                            <input type="hidden" name="id_Grupo" id="inputGrupoSeleccionado" value="">
                                        </div>

                                        <div id="alertaBuscandoGrupo" class="alert alert-secondary py-2" style="font-size:0.85rem; border-radius: 0;">
                                            Selecciona periodo, día o jornada para consultar los grupos disponibles.
                                        </div>

                                        <!-- GRUPO RECOMENDADO -->
                                        <div id="boxGrupoRecomendado" class="group-recommended mb-3" style="display: none;">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <div style="font-size:0.75rem; color:#15803d; font-weight:700; text-transform:uppercase;">
                                                        ✓ GRUPO RECOMENDADO (Próximo a iniciar)
                                                    </div>
                                                    <div class="group-name" id="txtRecomendadoClave">BGNE-02</div>
                                                    <div class="group-meta mt-1" id="txtRecomendadoMeta">
                                                        <strong>Modalidad:</strong> Matutino | <strong>Inicio:</strong> 22 de agosto
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge" style="background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; font-size: 0.75rem;" id="badgeRecomendadoCupo">Cupo disponible</span>
                                                    <button type="button" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1" id="btnElegirRecomendado">
                                                        <i class="ti ti-check"></i> Seleccionar este grupo
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- TABLA DE OTROS GRUPOS COMPATIBLES -->
                                        <div id="boxTablaOtrosGrupos" style="display: none;">
                                            <label class="form-label mb-1">Todos los grupos compatibles:</label>
                                            <div class="table-responsive bg-white rounded border" style="border-color: #E2E8F0 !important;">
                                                <table class="table table-sm table-hover align-middle mb-0" style="font-size:0.83rem;">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Grupo</th>
                                                            <th>Jornada</th>
                                                            <th>Nivel</th>
                                                            <th>Fecha Inicio</th>
                                                            <th class="text-end">Acción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbodyGruposCompatibles"></tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div id="alertaSinGrupo" class="alert alert-warning py-2 mt-2" style="display: none; font-size:0.85rem; border-radius: 0;">
                                            <strong>Sin grupo compatible asignado.</strong> Se guardará el alumno sin grupo (válido para alumnos en trámite).
                                        </div>

                                        <div id="badgeGrupoElegidoFinal" class="alert alert-primary py-2 mt-2" style="display: none; font-size:0.88rem; border-radius: 0; background: #EFF6F7; border-color: #BBDBE1; color: #26687B;">
                                            Grupo seleccionado actualmente: <strong id="txtNombreGrupoElegido">Ninguno</strong>
                                            <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2" id="btnQuitarGrupo">(Quitar)</button>
                                        </div>
                                    </div>

                                </div>

                                <div id="avisoSeleccionarCCT">
                                    <i class="ti ti-info-circle" style="color: #26687B; font-size: 16px;"></i>
                                    <span>Seleccione primero un <strong>CCT</strong> para cargar los periodos y grupos compatibles.</span>
                                </div>

                                <!-- ======================================================== -->
                                <!-- BLOQUE MODALIDAD DE ESTUDIO (PRESENCIAL / ONLINE)        -->
                                <!-- ======================================================== -->
                                <div class="moodle-subcontainer mb-2">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ti ti-device-laptop" style="color: #26687B; font-size: 17px;"></i>
                                            <span style="font-size: 0.8125rem; font-weight: 600; color: #0F172A;">Modalidad de Estudio y Aula Moodle</span>
                                        </div>
                                        <span id="badgeModalidadEstado" class="badge-moodle-soft">PRESENCIAL</span>
                                    </div>

                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-6">
                                            <label class="form-label">Modalidad del Alumno:</label>
                                            <select name="modalidad_estudio" id="selectModalidadEstudioForm" class="form-select form-select-premium" onchange="toggleModalidadForm(this.value)">
                                                <option value="PRESENCIAL">Modalidad Presencial</option>
                                                <option value="ONLINE">Modalidad En Línea (Moodle)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6" id="boxDiaPagoForm" style="display: none;">
                                            <label class="form-label">
                                                <i class="ti ti-calendar me-1" style="color: #26687B;"></i> Día de Cobro Semanal:
                                            </label>
                                            <select name="dia_pago" id="selectDiaPagoForm" class="form-select form-select-premium">
                                                <option value="SABADO">Cada Sábado (Fin de semana)</option>
                                                <option value="DOMINGO">Cada Domingo (Fin de semana)</option>
                                                <option value="LUNES">Cada Lunes (Semanal)</option>
                                                <option value="VIERNES">Cada Viernes</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- DETALLES DE MOODLE Y ACCIONES RÁPIDAS (VISIBLE SI ESTÁ EN LÍNEA) -->
                                    <div id="panelDetallesOnlineForm" class="mt-3 p-3 bg-white rounded-3 border" style="display: none; border-color: #E2E8F0 !important; box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);">
                                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                            <div>
                                                <div class="small fw-bold text-dark mb-1 d-flex align-items-center gap-1">
                                                    <i class="ti ti-user-circle" style="color: #26687B; font-size: 16px;"></i> Usuario Moodle: 
                                                    <span class="font-monospace fw-bold" style="color: #26687B;" id="txtMoodleUsernameDisplay">Sin asignar</span>
                                                </div>
                                                <div class="text-muted" style="font-size: 0.8rem;">
                                                    Semana actual pagada: <strong class="text-success" id="txtSemanaPagadaDisplay">Semana 0</strong> 
                                                    <span class="me-1">,</span> próximo cobro: <span class="fw-semibold text-secondary" id="txtProximoPagoDisplay">-</span>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-success text-white shadow-sm d-inline-flex align-items-center gap-1" onclick="abrirModalPagoDesdeEditar()" style="border-radius: 0; font-weight: 500; padding: 0.45rem 0.9rem;" title="Registrar Pago y Folio de Ticket">
                                                    <i class="ti ti-receipt"></i> Registrar Pago / Tickets
                                                </button>
                                                <button type="button" class="btn btn-sm text-white shadow-sm d-inline-flex align-items-center gap-1" onclick="abrirModalOnlineDesdeEditar()" style="background-color: #26687B; border: none; border-radius: 0; font-weight: 500; padding: 0.45rem 0.9rem;" title="Configurar Acceso en Moodle">
                                                    <i class="ti ti-device-laptop"></i> Configurar Moodle
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ALERTA INFORMATIVA SI NO TIENE GRUPO ASIGNADO -->
                                    <div id="alertaGrupoOnlineForm" class="alert alert-danger py-2 px-3 mt-2 mb-0 d-flex align-items-center gap-2" style="display: none; font-size: 0.83rem; border-radius: 0;">
                                        <i class="ti ti-alert-triangle"></i>
                                        <div><strong>Grupo Obligatorio:</strong> Para que el alumno pueda estar en modalidad en línea debe tener un grupo asignado arriba.</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- 4. PROCEDENCIA ACADÉMICA -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#procedenciaSection">
                                <span class="d-inline-flex align-items-center gap-2">
                                    <i class="ti ti-building" style="color: #26687B; font-size: 18px;"></i>
                                    <span>4. Procedencia académica y registro SEP</span>
                                </span>
                            </button>
                        </h2>
                        <div id="procedenciaSection" class="accordion-collapse collapse" data-bs-parent="#accordionAlumno">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Procedencia *</label>
                                        <select name="tipoProcedencia" id="selectTipoProcedencia" class="form-select form-select-premium">
                                            <option value="sin" selected>Sin estudios previos de bachillerato</option>
                                            <option value="secundaria">Secundaria BTI</option>
                                            <option value="externa">Otra institución de bachillerato</option>
                                            <option value="bti">BTI Escolarizado</option>
                                            <option value="bgne">BGNE</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8 mb-3" id="boxInstitucionProcedencia">
                                        <label class="form-label">Institución de procedencia</label>
                                        <input type="text" name="escuelaProcedencia" id="inputEscuelaProcedencia" class="form-control form-control-premium" placeholder="Nombre de la escuela anterior">
                                    </div>
                                </div>

                                <div id="boxAlertaSecundaria" class="alert alert-info py-2" style="display: none; font-size:0.85rem; border-radius: 0; background: #EFF6F7; border-color: #BBDBE1; color: #26687B;">
                                    <strong>Transición Secundaria BTI → Bachillerato:</strong> El sistema dará seguimiento a la continuidad académica del alumno.
                                </div>

                                <hr class="my-3" style="border-color: #E2E8F0;">

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Estado del registro SEP</label>
                                        <select name="estadoRegistroSEP" id="selectEstadoSEP" class="form-select form-select-premium">
                                            <option value="PENDIENTE" selected>Pendiente</option>
                                            <option value="EN_PROCESO">En proceso</option>
                                            <option value="REGISTRADO">Registrado</option>
                                            <option value="RECHAZADO">Rechazado</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="alertaEdadBGNE" class="alert alert-warning py-2 mb-0 d-flex align-items-center gap-2" style="display: none; font-size:0.85rem; border-radius:0;">
                                    <i class="ti ti-alert-triangle"></i>
                                    <div>
                                        <strong>Aviso de Validación de Edad (BGNE):</strong> El alumno tiene <strong id="txtDetalleEdadBGNE">menos de 16 años y medio</strong>. Puede inscribirse y cursar en BGNE, pero su alta oficial ante SEP permanecerá como <strong>PENDIENTE</strong> hasta cumplir la edad reglamentaria (16 años y 6 meses).
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 5. DOCUMENTACIÓN Y EQUIVALENCIA (REACTIVO PARA PERIODOS >= 2) -->
                    <div class="accordion-item" id="accordionItemEquivalencia" style="display: none;">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#equivalenciaSection">
                                <span class="d-inline-flex align-items-center gap-2">
                                    <i class="ti ti-file-check" style="color: #26687B; font-size: 18px;"></i>
                                    <span>5. Documentación y equivalencia</span>
                                </span>
                            </button>
                        </h2>
                        <div id="equivalenciaSection" class="accordion-collapse collapse show" data-bs-parent="#accordionAlumno">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">¿Cuenta con boleta?</label>
                                        <select name="traeBoleta" id="selectTraeBoleta" class="form-select form-select-premium">
                                            <option value="NO" selected>NO</option>
                                            <option value="SI">SÍ</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">¿Cuenta con certificado parcial?</label>
                                        <select name="certificadoIncompleto" id="selectCertIncompleto" class="form-select form-select-premium">
                                            <option value="NO" selected>NO</option>
                                            <option value="SI">SÍ</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Fecha de entrega</label>
                                        <input type="date" name="fechaEntregaCertificado" id="inputFechaEntregaCert" class="form-control form-control-premium">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Requiere equivalencia?</label>
                                        <select name="equivalencia" id="selectRequiereEquiv" class="form-select form-select-premium">
                                            <option value="NO" selected>NO</option>
                                            <option value="SI">SÍ</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Estado del pago de equivalencia</label>
                                        <select name="estadoPagoEquivalencia" id="selectEstadoPagoEquiv" class="form-select form-select-premium">
                                            <option value="PENDIENTE" selected>Pendiente</option>
                                            <option value="PAGADO">Pagado</option>
                                            <option value="EXENTO">Exento</option>
                                        </select>
                                    </div>
                                </div>

                                <div id="alertaAccionEquivalencia" class="alert alert-warning py-2" style="display: none; font-size:0.85rem; border-radius: 0;">
                                    <strong>Acción requerida:</strong> El certificado fue entregado y la equivalencia pagada. Control Escolar debe ingresar el trámite ante la SEP.
                                </div>

                                <!-- MATERIAS PENDIENTES -->
                                <div class="card p-3 border bg-light mt-3" style="background: #F8FAFC !important; border-color: #E2E8F0 !important; border-radius: 0!important;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label fw-bold mb-0">Materias pendientes de acreditar:</label>
                                        <button type="button" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" id="btnAgregarMateriaPendiente" style="border-radius: 0;">
                                            <i class="ti ti-plus"></i> Agregar materia pendiente
                                        </button>
                                    </div>
                                    <div class="table-responsive bg-white rounded border" style="border-color: #E2E8F0 !important;">
                                        <table class="table table-sm align-middle mb-0" id="tablaMateriasPendientes" style="font-size:0.82rem;">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Materia</th>
                                                    <th style="width:120px;">Periodo</th>
                                                    <th style="width:120px;">Calificación</th>
                                                    <th style="width:50px;"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbodyMateriasPendientes">
                                                <tr class="fila-vacia-materias">
                                                    <td colspan="4" class="text-center text-muted py-2">Sin materias pendientes registradas.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 6. ATENCIÓN PRESENCIAL / TRAYECTORIAS CRUZADAS -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#trayectoriaCruzadaSection">
                                <span class="d-inline-flex align-items-center gap-2">
                                    <i class="ti ti-arrows-left-right" style="color: #26687B; font-size: 18px;"></i>
                                    <span>6. Atención presencial y trayectorias cruzadas</span>
                                </span>
                            </button>
                        </h2>
                        <div id="trayectoriaCruzadaSection" class="accordion-collapse collapse" data-bs-parent="#accordionAlumno">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">¿Asistirá a otro CCT presencialmente?</label>
                                        <select name="asistePresencialBTI" id="selectAsistePresencial" class="form-select form-select-premium">
                                            <option value="NO" selected>NO (Asiste a su CCT administrativo)</option>
                                            <option value="SI">SÍ (Asiste a clases en otro CCT)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3" id="boxCCTPresencial" style="display: none;">
                                        <label class="form-label">CCT Presencial de Atención <span class="text-danger">*</span></label>
                                        <select name="id_centroTrabajo_presencial" id="selectCCTPresencial" class="form-select form-select-premium">
                                            <option value="">Seleccionar CCT presencial...</option>
                                            @if(isset($centrosTrabajo) && count($centrosTrabajo) > 0)
                                                @foreach($centrosTrabajo as $cct)
                                                    <option value="{{ $cct['id'] }}" data-nombre="{{ $cct['nombre'] }}">
                                                        {{ $cct['nombre'] }} - {{ $cct['clave'] }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3" id="boxGrupoPresencialBTI" style="display: none;">
                                        <label class="form-label">Grupo presencial de atención <span class="text-danger">*</span></label>
                                        <select name="grupoPresencialAtencion" id="inputGrupoPresencial" class="form-select form-select-premium">
                                            <option value="">Seleccionar un grupo...</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3" id="boxGeneracionPresencial" style="display: none;">
                                        <label class="form-label">Generación CCT Presencial</label>
                                        <select name="id_generacion_presencial" id="selectGeneracionPresencial" class="form-select form-select-premium">
                                            <option value="">Sin asignación específica</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="alertaTrayectoriaEspecial" class="alert alert-info py-2 mb-0 d-flex align-items-center gap-2" style="display: none; font-size:0.85rem; border-radius:0; background: #EFF6F7; border-color: #BBDBE1; color: #26687B;">
                                    <i class="ti ti-arrows-left-right"></i>
                                    <div><strong>Trayectoria especial activa:</strong> CCT Administrativo / SEP: <span id="txtCCTAdmin" class="fw-bold">BTI</span> ➔ Atención presencial: <span id="txtCCTPresencialNombre" class="fw-bold" style="color: #26687B;">BGNE</span>.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 7. FICHA RESUMEN DE INSCRIPCIÓN (FORMATO TIPO DOCUMENTO) -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#resumenSection">
                                <span class="d-inline-flex align-items-center gap-2">
                                    <i class="ti ti-file-text" style="color: #26687B; font-size: 18px;"></i>
                                    <span>7. Ficha resumen de inscripción y situación escolar</span>
                                </span>
                            </button>
                        </h2>
                        <div id="resumenSection" class="accordion-collapse collapse show" data-bs-parent="#accordionAlumno">
                            <div class="accordion-body p-3">
                                
                                <!-- FICHA TIPO DOCUMENTO OFICIAL -->
                                <div class="card border rounded-3 shadow-none bg-white mb-0" style="border-color: #E2E8F0 !important;">
                                    
                                    <!-- Cabecera de Ficha -->
                                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="background: #F8FAFC; border-color: #E2E8F0 !important;">
                                        <div>
                                            <div class="fw-bold text-dark text-uppercase d-flex align-items-center gap-1" style="font-size: 0.86rem; letter-spacing: 0.5px;">
                                                <i class="ti ti-file-spreadsheet" style="color: #26687B;"></i> Ficha de Inscripción y Control Escolar
                                            </div>
                                            <div class="text-muted" style="font-size: 0.78rem;">
                                                Resumen administrativo del expediente
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary fw-semibold px-3 py-1 d-inline-flex align-items-center gap-1" onclick="imprimirFichaInscripcion()" style="border-radius: 0;">
                                                <i class="ti ti-printer"></i> Imprimir Ficha
                                            </button>
                                            <span id="badgeEstadoGeneral" class="badge" style="background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0; font-size: 0.75rem; font-weight: 600; padding: 4px 10px; border-radius: 0;">EN REGLA</span>
                                        </div>
                                    </div>

                                    <!-- Tabla de datos estructurados tipo documento -->
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm mb-0" style="font-size: 0.84rem; border-color: #e2e8f0;">
                                            <tbody>
                                                <!-- Datos del Alumno -->
                                                <tr class="table-light">
                                                    <th colspan="4" class="py-1 px-3 text-secondary text-uppercase" style="font-size: 0.74rem; letter-spacing: 0.4px;">
                                                        Datos del Alumno
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted w-25 px-3 py-2">Nombre Completo:</td>
                                                    <td colspan="3" class="px-3 py-2"><strong id="sumNombreAlumno" class="text-dark text-uppercase">—</strong></td>
                                                </tr>

                                                <!-- Sección 1: Datos Académicos y SEP -->
                                                <tr class="table-light">
                                                    <th colspan="4" class="py-1 px-3 text-secondary text-uppercase" style="font-size: 0.74rem; letter-spacing: 0.4px;">
                                                        1. Programa Académico y Registro SEP
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted w-25 px-3 py-2">CCT Inscripción:</td>
                                                    <td class="w-25 px-3 py-2"><strong id="sumCCT" class="text-dark">—</strong></td>
                                                    <td class="text-muted w-25 px-3 py-2">Grupo Oficial SEP:</td>
                                                    <td class="w-25 px-3 py-2"><strong id="sumGrupo" class="text-dark">Sin grupo</strong></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted px-3 py-2">Periodo / Nivel:</td>
                                                    <td class="px-3 py-2"><strong id="sumPeriodo" class="text-dark">—</strong></td>
                                                    <td class="text-muted px-3 py-2">Generación SEP:</td>
                                                    <td class="px-3 py-2"><strong id="sumGeneracion" class="text-dark">Pendiente SEP</strong></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted px-3 py-2">Turno / Asistencia:</td>
                                                    <td class="px-3 py-2"><strong id="sumDiaJornada" class="text-dark">—</strong></td>
                                                    <td class="text-muted px-3 py-2">Estatus Registro SEP:</td>
                                                    <td class="px-3 py-2"><strong id="sumEstadoSEP" class="text-dark">PENDIENTE</strong></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted px-3 py-2">Inicio del Grupo:</td>
                                                    <td class="px-3 py-2"><strong id="sumFechaInicioGrupo" class="text-dark">—</strong></td>
                                                    <td class="text-muted px-3 py-2">Término Tentativo:</td>
                                                    <td class="px-3 py-2"><strong id="sumFechaFinTentativa" class="text-dark">—</strong></td>
                                                </tr>

                                                <!-- Sección 2: Trayectoria y Atención Presencial -->
                                                <tr class="table-light">
                                                    <th colspan="4" class="py-1 px-3 text-secondary text-uppercase" style="font-size: 0.74rem; letter-spacing: 0.4px;">
                                                        2. Modalidad de Atención y Plantel de Clases
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted px-3 py-2">Modalidad de Asistencia:</td>
                                                    <td class="px-3 py-2"><strong id="sumAsistePresencialTexto" class="text-dark">Presencial</strong></td>
                                                    <td class="text-muted px-3 py-2">Plantel y Grupo Presencial:</td>
                                                    <td class="px-3 py-2"><strong id="sumDetalleTrayectoria" class="text-dark">Mismo CCT Administrativo</strong></td>
                                                </tr>

                                                <!-- Sección 3: Regularidad y Trámites -->
                                                <tr class="table-light">
                                                    <th colspan="4" class="py-1 px-3 text-secondary text-uppercase" style="font-size: 0.74rem; letter-spacing: 0.4px;">
                                                        3. Trámites y Regularidad Académica
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted px-3 py-2">Trámite de Equivalencia:</td>
                                                    <td class="px-3 py-2"><strong id="sumEquivalencia" class="text-dark">No requiere</strong></td>
                                                    <td class="text-muted px-3 py-2">Materias Pendientes:</td>
                                                    <td class="px-3 py-2"><strong id="sumMateriasPend" class="text-dark">0</strong></td>
                                                </tr>

                                                <!-- Sección 4: Observaciones y Pendientes de Control Escolar -->
                                                <tr class="table-light">
                                                    <th colspan="4" class="py-1 px-3 text-secondary text-uppercase" style="font-size: 0.74rem; letter-spacing: 0.4px;">
                                                        4. Observaciones y Pendientes de Control Escolar
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" class="p-3 bg-white">
                                                        <div id="listaPendientesControlEscolar" class="d-flex flex-column gap-1" style="font-size: 0.84rem;">
                                                            <div class="text-secondary">• Sin observaciones ni pendientes administrativos registrados.</div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- 8. CURSOS EXTRA Y OBSERVACIONES -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cursosExtra">
                                <span class="d-inline-flex align-items-center gap-2">
                                    <i class="ti ti-certificate" style="color: #26687B; font-size: 18px;"></i>
                                    <span>8. Cursos extracurriculares y observaciones</span>
                                </span>
                            </button>
                        </h2>
                        <div id="cursosExtra" class="accordion-collapse collapse" data-bs-parent="#accordionAlumno">
                            <div class="accordion-body">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="cursos[]" value="1" id="cursoComp">
                                    <label class="form-check-label" for="cursoComp">Curso de Computación</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="cursos[]" value="2" id="cursoRep">
                                    <label class="form-check-label" for="cursoRep">Curso de Reparación Digital</label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="cursos[]" value="3" id="cursoDis">
                                    <label class="form-check-label" for="cursoDis">Curso de Diseño Gráfico</label>
                                </div>
                                <div class="mt-3">
                                    <label class="form-label">Observaciones adicionales</label>
                                    <textarea name="observaciones" class="form-control form-control-premium" rows="2" placeholder="Comentarios sobre la inscripción..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-premium-cancel" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn-premium-save" type="submit" id="btnGuardarAlumno">
                    <i class="ti ti-check" style="font-size: 17px;"></i>
                    <span>Guardar Alumno</span>
                </button>
            </div>

        </form>
    </div>
</div>
