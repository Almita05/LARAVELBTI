<style>
.glass-modal {
    background: #ffffff !important;
    border: none;
    border-radius: 20px;
    overflow: hidden;
    color: #1e293b;
    box-shadow: 0 15px 40px rgba(0, 0, 0, .15);
}

.glass-modal .modal-header {
    background: linear-gradient(135deg, rgb(73, 164, 190), #1E6FA8) !important;
    color: #fff;
    border-bottom: none;
    padding: 1.2rem 1.5rem;
}

.glass-modal .modal-body {
    background: #f8fafc;
    padding: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: #334155;
    font-size: 0.85rem;
    margin-bottom: 6px;
    display: inline-block;
}

.form-control-premium,
.form-select-premium {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    color: #1e293b;
    font-size: 0.9rem;
    padding: 0.65rem 1rem;
    min-height: 44px;
    transition: all 0.2s ease-in-out;
}

.form-control-premium:hover,
.form-select-premium:hover {
    border-color: #94a3b8;
}

.form-control-premium:focus,
.form-select-premium:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
    outline: none;
}

.glass-modal .modal-footer {
    background: #f8fafc !important;
    border-top: 1px solid #e2e8f0;
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

.btn-premium-cancel {
    background: #f1f5f9;
    color: #475569 !important;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    padding: 0.6rem 1.5rem;
    font-weight: 600;
    transition: all 0.25s ease;
}

.btn-premium-cancel:hover {
    background: #e2e8f0;
    color: #1e293b !important;
    transform: translateY(-1px);
}

.btn-premium-save {
    background: linear-gradient(135deg, rgb(73, 164, 190) 0%, #1E6FA8 100%);
    color: white !important;
    border: none;
    border-radius: 12px;
    padding: 0.6rem 1.5rem;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(30, 111, 168, 0.25);
    transition: all 0.25s ease;
}

.btn-premium-save:hover {
    background: linear-gradient(135deg, #1E6FA8 0%, #154c75 100%);
    box-shadow: 0 6px 16px rgba(30, 111, 168, 0.35);
    transform: translateY(-1px);
}
</style>
<div class="modal fade" id="modalGrupo" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="formGrupo" class="modal-content glass-modal">

            <div class="modal-header glass-header">
                <h5 class="modal-title">
                    <i class="fas fa-users me-2"></i>
                    Nuevo Grupo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <!-- Clave -->
                    <div class="col-md-6">
                        <label class="form-label">Clave del grupo <span class="text-danger">*</span></label>
                        <input type="text" name="clave" class="form-control form-control-premium"
                            placeholder="Ej. 1A-2026" required>
                    </div>




                    <input type="hidden" name="fechaCreacion" id="fechaCreacion">
                    <!-- Fecha Inicio -->
                    <div class="col-md-6">
                        <label class="form-label">Fecha inicio <span class="text-danger">*</span></label>
                        <input type="date" name="fechaInicio" class="form-control form-control-premium" required>
                    </div>

                    <!-- Centro de Trabajo -->
                    <div class="col-md-6">
                        <label class="form-label">Centro de trabajo <span class="text-danger">*</span></label>
                        <select name="id_centroTrabajo" class="form-select form-select-premium" required>
                            <option value="">Seleccione un centro de trabajo</option>
                            @foreach($centros as $ct)
                            <option value="{{ $ct['id'] }}" 
                                    data-id-periodo="{{ $ct['idTipoPeriodo'] ?? '' }}"
                                    data-nombre-periodo="{{ $ct['nombrePeriodo'] ?? '' }}"
                                    data-nombre="{{ $ct['nombre'] }}">{{ $ct['nombre'] }}</option>
                            @endforeach
                        </select>
                    </div>


                    <!-- Fecha Fin -->
                    <div class="col-md-6">
                        <label class="form-label">Fecha fin <span class="text-danger">*</span></label>
                        <input type="date" name="fechaFin" class="form-control form-control-premium" required>
                        <div id="divCalcularSemanas" style="display: none; margin-top: 8px;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkCalcularSemanas">
                                <label class="form-check-label" for="chkCalcularSemanas"
                                    style="font-weight: 600; font-size: 0.85rem; color: #475569;">
                                    Calcular 78 semanas automáticamente (BGNE)
                                </label>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="id_planEstudios" id="id_planEstudios">


                    <!-- Tipo de Periodo -->
                    <div class="col-md-6">
                        <label class="form-label">Tipo de periodo <span class="text-danger">*</span></label>
                        <select name="id_tipoPeriodo" class="form-select form-select-premium" required>
                            <option value="">Seleccione un periodo</option>
                            @foreach($periodos as $periodo)
                            <option value="{{ $periodo['id'] }}" data-nombre="{{ $periodo['nombrePeriodo'] }}">{{ $periodo['nombrePeriodo'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nivel Académico -->
                    <div class="col-md-6">
                        <label class="form-label">Nivel académico <span class="text-danger">*</span></label>
                        <select name="id_nivel_academico" class="form-select form-select-premium" required>
                            <option value="">Seleccione primero un centro de trabajo</option>
                        </select>
                    </div>

                    <!-- Modalidad de Horario -->
                    <div class="col-md-6">
                        <label class="form-label">Modalidad de Horario <span class="text-danger">*</span></label>
                        <select name="modalidadHorario" class="form-select form-select-premium" required>
                            <option value="">Seleccione una modalidad</option>
                            <option value="MATUTINO">MATUTINO</option>
                            <option value="VESPERTINO">VESPERTINO</option>
                            <option value="LIBRE">LIBRE</option>
                        </select>
                    </div>

                    <!-- Estatus del Grupo -->
                    <div class="col-md-6">
                        <label class="form-label">Estatus del grupo <span class="text-danger">*</span></label>
                        <select name="statusGrupo" class="form-select form-select-premium" required>
                            <option value="ACTIVO">ACTIVO</option>
                            <option value="INACTIVO">INACTIVO</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-premium-cancel" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button class="btn-premium-save" type="submit">
                    <i class="fas fa-save me-2"></i>
                    Guardar
                </button>
            </div>

        </form>
    </div>
</div>