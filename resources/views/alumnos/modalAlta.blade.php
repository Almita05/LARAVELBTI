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
    margin-bottom: 14px;
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

#modalAlumno .group-recommended {
    border: 2px solid #86efac;
    border-radius: 14px;
    padding: 18px;
    background: #f0fdf4;
    position: relative;
}

#modalAlumno .group-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: #166534;
}

#modalAlumno .group-meta {
    font-size: 0.88rem;
    color: #374151;
    line-height: 1.6;
}

#modalAlumno .pill {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 700;
}

#modalAlumno .pill-green {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

#modalAlumno .summary-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 16px;
}

#modalAlumno .summary-item {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px dashed #cbd5e1;
    padding: 8px 0;
    font-size: 0.88rem;
}

#modalAlumno .summary-item span {
    color: #64748b;
}

#modalAlumno .summary-item strong {
    color: #1e293b;
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
}

#modalAlumno .btn-premium-save {
    background: linear-gradient(135deg, rgb(73, 164, 190) 0%, #1E6FA8 100%);
    color: white !important;
    border: none;
    border-radius: 12px;
    padding: 0.6rem 1.8rem;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(30, 111, 168, 0.25);
    transition: all 0.25s ease;
}

#modalAlumno .btn-premium-save:hover {
    background: linear-gradient(135deg, #1E6FA8 0%, #154c75 100%);
    box-shadow: 0 6px 16px rgba(30, 111, 168, 0.35);
}
</style>

<div class="modal fade" id="modalAlumno" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <form id="formAlumno" class="modal-content glass-modal" novalidate>

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-person-plus-fill me-2"></i>
                    Alta de Alumno
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <!-- Opción de Registro Histórico -->
                <div class="card p-3 border rounded-3 mb-3 bg-light" style="border-color: #cbd5e1 !important; border-radius: 16px !important;">
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" id="chkRegistroHistorico" name="registro_historico" value="1">
                        <label class="form-check-label fw-bold text-dark" for="chkRegistroHistorico" style="font-size: 0.95rem; cursor: pointer;">
                            <i class="bi bi-clock-history me-1 text-warning"></i> Registro de Alumno Histórico
                        </label>
                        <small class="text-muted d-block mt-1" style="font-size: 0.82rem;">
                            Active esta opción si está registrando un alumno de ciclos anteriores con información incompleta. Se relajarán las validaciones y se permitirá seleccionar cualquier grupo (incluso inactivos o terminados).
                        </small>
                    </div>
                </div>

                <div class="accordion" id="accordionAlumno">

                    <!-- 1. DATOS PERSONALES -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button rounded" type="button" data-bs-toggle="collapse" data-bs-target="#datosPersonales">
                                <i class="bi bi-person-badge-fill me-2 text-info"></i> 1. Datos personales
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
                            <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#datosContacto">
                                <i class="bi bi-telephone-fill me-2 text-primary"></i> 2. Contacto, tutor y domicilio
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
                            <button class="accordion-button rounded" type="button" data-bs-toggle="collapse" data-bs-target="#academicos">
                                <i class="bi bi-mortarboard-fill me-2 text-primary"></i> 3. Contexto académico y selección de grupo
                            </button>
                        </h2>
                        <div id="academicos" class="accordion-collapse collapse show" data-bs-parent="#accordionAlumno">
                            <div class="accordion-body">
                                
                                <div class="row mb-3">
                                    <!-- CCT -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">CCT (Centro de Trabajo) <span class="text-danger requerido-normal">*</span></label>
                                        <select name="id_centroTrabajo" id="selectCCT" class="form-select form-select-premium" required>
                                            <option value="">-- Seleccione CCT --</option>
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
                                            <option value="">-- Seleccione Periodo --</option>
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
                                    <div id="boxDatosCertificado" class="col-12 mb-3 p-3 bg-light rounded-3 border" style="display: none; border-color: #cbd5e1 !important;">
                                        <div class="fw-bold text-dark mb-2" style="font-size: 0.85rem;">
                                            <i class="bi bi-award-fill me-1 text-secondary"></i> Datos de Certificación y Entrega de Documento
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
                                    
                                    <div class="alert alert-info py-2 px-3 mb-3" style="font-size:0.85rem; border-radius:10px;">
                                        <i class="bi bi-info-circle-fill me-1"></i>
                                        Indique el día y jornada de interés para que el sistema proponga el <strong>grupo compatible más próximo a iniciar</strong>.
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Día de asistencia <span class="text-danger">*</span></label>
                                            <select name="diaAsistencia" id="selectDiaAsistencia" class="form-select form-select-premium">
                                                <option value="">-- Todos los días / Seleccione --</option>
                                                <option value="SABADO">Sábado</option>
                                                <option value="DOMINGO">Domingo</option>
                                                <option value="LUNES-VIERNES">Lunes a Viernes (Escolarizado)</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Jornada <span class="text-danger">*</span></label>
                                            <select name="jornadaHorario" id="selectJornada" class="form-select form-select-premium">
                                                <option value="">-- Todas las jornadas --</option>
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
                                    <div class="card p-3 border-0 bg-light rounded-3 mb-3">
                                        <!-- SELECCIÓN DIRECTA DE GRUPO (SÓLO PARA REGISTRO HISTÓRICO) -->
                                        <div id="boxGrupoDirectoHistorico" style="display: none;" class="mb-3">
                                            <label class="form-label fw-bold text-dark">Grupo Histórico / Directo</label>
                                            <select id="selectGrupoHistorico" class="form-select form-select-premium">
                                                <option value="">-- Seleccione cualquier grupo (activo/inactivo) --</option>
                                            </select>
                                            <small class="text-muted d-block mt-1" style="font-size: 0.78rem;">
                                                Aquí se muestran todos los grupos del CCT seleccionado (incluidos inactivos y terminados).
                                            </small>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-2 box-compatibles-cabecera">
                                            <h6 class="fw-bold mb-0 text-dark">
                                                <i class="bi bi-diagram-3-fill me-1 text-primary"></i> Grupos compatibles
                                            </h6>
                                            <input type="hidden" name="id_Grupo" id="inputGrupoSeleccionado" value="">
                                        </div>

                                        <div id="alertaBuscandoGrupo" class="alert alert-secondary py-2" style="font-size:0.85rem;">
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
                                                <div>
                                                    <span class="pill pill-green me-1" id="badgeRecomendadoCupo">Cupo disponible</span>
                                                    <button type="button" class="btn btn-sm btn-success" id="btnElegirRecomendado">
                                                        <i class="bi bi-check2-circle me-1"></i> Seleccionar este grupo
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- TABLA DE OTROS GRUPOS COMPATIBLES -->
                                        <div id="boxTablaOtrosGrupos" style="display: none;">
                                            <label class="form-label mb-1">Todos los grupos compatibles:</label>
                                            <div class="table-responsive bg-white rounded border">
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

                                        <div id="alertaSinGrupo" class="alert alert-warning py-2 mt-2" style="display: none; font-size:0.85rem;">
                                            <strong>Sin grupo compatible asignado.</strong> Se guardará el alumno sin grupo (válido para alumnos en trámite).
                                        </div>

                                        <div id="badgeGrupoElegidoFinal" class="alert alert-primary py-2 mt-2" style="display: none; font-size:0.88rem;">
                                            Grupo seleccionado actualmente: <strong id="txtNombreGrupoElegido">Ninguno</strong>
                                            <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2" id="btnQuitarGrupo">(Quitar)</button>
                                        </div>
                                    </div>

                                </div>

                                <div id="avisoSeleccionarCCT" class="alert alert-light border text-muted py-2 px-3 mb-0" style="font-size:0.85rem;">
                                    <i class="bi bi-arrow-up-circle me-1 text-primary"></i>
                                    Seleccione primero un <strong>CCT</strong> para cargar los periodos y grupos compatibles.
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- 4. PROCEDENCIA ACADÉMICA -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#procedenciaSection">
                                <i class="bi bi-building me-2 text-primary"></i> 4. Procedencia académica y registro SEP
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

                                <div id="boxAlertaSecundaria" class="alert alert-info py-2" style="display: none; font-size:0.85rem;">
                                    <strong>Transición Secundaria BTI → Bachillerato:</strong> El sistema dará seguimiento a la continuidad académica del alumno.
                                </div>

                                <hr class="my-3">

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
                                <div id="alertaEdadBGNE" class="alert alert-warning py-2 mb-0" style="display: none; font-size:0.85rem; border-radius:10px;">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                    <strong>Aviso de Validación de Edad (BGNE):</strong> El alumno tiene <strong id="txtDetalleEdadBGNE">menos de 16 años y medio</strong>. Puede inscribirse y cursar en BGNE, pero su alta oficial ante SEP permanecerá como <strong>PENDIENTE</strong> hasta cumplir la edad reglamentaria (16 años y 6 meses).
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 5. DOCUMENTACIÓN Y EQUIVALENCIA (REACTIVO PARA PERIODOS >= 2) -->
                    <div class="accordion-item" id="accordionItemEquivalencia" style="display: none;">
                        <h2 class="accordion-header">
                            <button class="accordion-button rounded" type="button" data-bs-toggle="collapse" data-bs-target="#equivalenciaSection">
                                <i class="bi bi-file-earmark-check-fill me-2 text-warning"></i> 5. Documentación y equivalencia
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

                                <div id="alertaAccionEquivalencia" class="alert alert-warning py-2" style="display: none; font-size:0.85rem;">
                                    <strong>Acción requerida:</strong> El certificado fue entregado y la equivalencia pagada. Control Escolar debe ingresar el trámite ante la SEP.
                                </div>

                                <!-- MATERIAS PENDIENTES -->
                                <div class="card p-3 border bg-light mt-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label fw-bold mb-0">Materias pendientes de acreditar:</label>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnAgregarMateriaPendiente">
                                            <i class="bi bi-plus-circle me-1"></i> + Agregar materia pendiente
                                        </button>
                                    </div>
                                    <div class="table-responsive bg-white rounded border">
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
                            <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#trayectoriaCruzadaSection">
                                <i class="bi bi-arrow-left-right me-2 text-primary"></i> 6. Atención presencial y trayectorias cruzadas
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
                                            <option value="">-- Seleccione CCT Presencial --</option>
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
                                            <option value="">-- Seleccione un grupo --</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3" id="boxGeneracionPresencial" style="display: none;">
                                        <label class="form-label">Generación CCT Presencial</label>
                                        <select name="id_generacion_presencial" id="selectGeneracionPresencial" class="form-select form-select-premium">
                                            <option value="">-- Sin asignación específica --</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="alertaTrayectoriaEspecial" class="alert alert-info py-2 mb-0" style="display: none; font-size:0.85rem; border-radius:10px;">
                                    <i class="bi bi-arrow-left-right me-1"></i>
                                    <strong>Trayectoria especial activa:</strong> CCT Administrativo / SEP: <span id="txtCCTAdmin" class="fw-bold">BTI</span> ➔ Atención presencial: <span id="txtCCTPresencialNombre" class="fw-bold text-primary">BGNE</span>.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 7. FICHA RESUMEN DE INSCRIPCIÓN (FORMATO TIPO DOCUMENTO) -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button rounded" type="button" data-bs-toggle="collapse" data-bs-target="#resumenSection">
                                <i class="bi bi-file-earmark-text me-2 text-secondary"></i> 7. Ficha resumen de inscripción y situación escolar
                            </button>
                        </h2>
                        <div id="resumenSection" class="accordion-collapse collapse show" data-bs-parent="#accordionAlumno">
                            <div class="accordion-body p-3">
                                
                                <!-- FICHA TIPO DOCUMENTO OFICIAL -->
                                <div class="card border rounded-3 shadow-none bg-white mb-0" style="border-color: #cbd5e1 !important;">
                                    
                                    <!-- Cabecera de Ficha -->
                                    <div class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold text-dark text-uppercase" style="font-size: 0.86rem; letter-spacing: 0.5px;">
                                                <i class="bi bi-file-earmark-spreadsheet me-1 text-secondary"></i> Ficha de Inscripción y Control Escolar
                                            </div>
                                            <div class="text-muted" style="font-size: 0.78rem;">
                                                Resumen administrativo del expediente
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary fw-semibold px-3 py-1" onclick="imprimirFichaInscripcion()">
                                                <i class="fa-solid fa-print me-1"></i> Imprimir Ficha
                                            </button>
                                            <span id="badgeEstadoGeneral" class="badge bg-secondary" style="font-size: 0.75rem; font-weight: 600; padding: 5px 10px;">EN REGLA</span>
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
                            <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#cursosExtra">
                                <i class="bi bi-journal-bookmark me-2 text-primary"></i> 8. Cursos extracurriculares y observaciones
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
                    <i class="bi bi-floppy-fill me-2"></i> Guardar Alumno
                </button>
            </div>

        </form>
    </div>
</div>