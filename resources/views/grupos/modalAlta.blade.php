<style>
.glass-modal {
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 0!important;
    overflow: hidden;
    color: #0f172a;
    box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.12), 0 8px 10px -6px rgba(15, 23, 42, 0.08) !important;
}

.glass-modal .modal-header {
    background: #ffffff !important;
    color: #0f172a !important;
    border-bottom: 1px solid #e2e8f0 !important;
    padding: 16px 24px !important;
}

.glass-modal .modal-header .modal-title {
    font-size: 1.05rem !important;
    font-weight: 600 !important;
    color: #0f172a !important;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.glass-modal .modal-body {
    background: #ffffff;
    padding: 24px;
}

.form-label {
    font-weight: 600;
    color: #334155;
    font-size: 0.8125rem;
    margin-bottom: 6px;
    display: inline-block;
}

.form-control-premium,
.form-select-premium {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 0;
    color: #0f172a;
    font-size: 0.875rem;
    padding: 0.5rem 0.875rem;
    min-height: 38px;
    transition: all 0.15s ease-in-out;
}

.form-control-premium:hover,
.form-select-premium:hover {
    border-color: #94a3b8;
}

.form-control-premium:focus,
.form-select-premium:focus {
    border-color: #26687B !important;
    box-shadow: 0 0 0 3px rgba(38, 104, 123, 0.12) !important;
    outline: none;
}

.glass-modal .modal-footer {
    background: #f8fafc !important;
    border-top: 1px solid #e2e8f0;
    padding: 14px 24px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.btn-premium-cancel {
    background: #ffffff;
    color: #475569 !important;
    border: 1px solid #cbd5e1;
    border-radius: 0;
    padding: 0 16px;
    height: 38px;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.15s ease;
}

.btn-premium-cancel:hover {
    background: #f8fafc;
    color: #0f172a !important;
}

.btn-premium-save {
    background: #26687B !important;
    color: white !important;
    border: none;
    border-radius: 0;
    padding: 0 18px;
    height: 38px;
    font-weight: 500;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
    transition: all 0.15s ease;
}

.btn-premium-save:hover {
    background: #1F5565 !important;
}
</style>
<div class="modal fade" id="modalGrupo" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="formGrupo" class="modal-content glass-modal">

            <div class="modal-header glass-header">
                <h5 class="modal-title">
                    <i class="ti ti-users" style="color: #26687B; font-size: 20px;"></i>
                    Nuevo Grupo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                        <small class="text-muted d-block mt-1" style="font-size: 0.76rem; line-height: 1.2;">
                            * Para grupos históricos o ya finalizados, seleccione el periodo de inicio habitual (ej. 1er Trimestre).
                        </small>
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
                    <i class="ti ti-check me-1"></i>
                    Guardar
                </button>
            </div>

        </form>
    </div>
</div>