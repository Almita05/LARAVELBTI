<style>

.glass-input {
    background: #fff;
    border: 2px solid #9BDFFF !important;
    border-radius: 15px !important;
    color: #212529;
    min-height: 48px;
    padding: .75rem 1rem;
    transition: .25s;
}

.glass-input:hover {
    border-color: #7FD3FF !important;
}

.glass-input:focus {
    border-color: #66C9FF !important;
    box-shadow:
        0 0 0 3px rgba(102, 201, 255, .20),
        inset 0 1px 2px rgba(0, 0, 0, .04);
}

.glass-modal {
    background: rgb(73, 164, 190) !important;
    border: 1px solid rgba(255, 255, 255, .15);
    border-radius: 20px;
    overflow: hidden;
    color: white;
    backdrop-filter: blur(12px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, .35);
}

.glass-modal .modal-header {
    background: linear-gradient(135deg, rgb(73, 164, 190), #1E6FA8);
    color: #fff;
}

.glass-modal .modal-body {
    background: white;
}

.glass-modal .modal-footer {
    border-top: 1px solid rgba(255, 255, 255, .08);
    background: rgba(255, 255, 255, .03);
}

.accordion-item {
    background: rgba(255, 255, 255, .06);
    border: none;
    border-radius: 14px;
    overflow: hidden;
}

.accordion-button,
.accordion-button.collapsed,
.accordion-button:not(.collapsed) {
    background: linear-gradient(135deg, rgb(73, 164, 190), #1E6FA8) !important;
    color: #fff !important;
    font-weight: 600;
    box-shadow: none !important;
    border: none;
}

.accordion-button:focus {
    box-shadow: none !important;
}

.accordion-button::after {
    filter: brightness(0) invert(1);
}

.accordion-body {
    background: white;
}

.form-label {
    font-weight: 600;
    color: black;
}

.form-control-premium,
.form-select-premium {
    background: #fff;
    border: 2px solid #9BDFFF;
    border-radius: 15px;
    color: #212529;
    min-height: 48px;
}

.form-control-premium:hover,
.form-select-premium:hover {
    border-color: #7FD3FF;
}

.form-control-premium:focus,
.form-select-premium:focus {
    border-color: #66C9FF;
    box-shadow:
        0 0 0 3px rgba(102, 201, 255, .20),
        inset 0 1px 2px rgba(0, 0, 0, .04);
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

                    <div class="col-md-6">
                        <label class="form-label">
                            Clave del grupo <span class="text-warning">*</span>
                        </label>
                        <input type="text" name="clave" class="form-control glass-input" placeholder="Ej. 1A-2026"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Fecha creación <span class="text-warning">*</span>
                        </label>
                        <input type="date" name="fechaCreacion" class="form-control glass-input" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Fecha inicio <span class="text-warning">*</span>
                        </label>
                        <input type="date" name="fechaInicio" class="form-control glass-input" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Fecha fin <span class="text-warning">*</span>
                        </label>
                        <input type="date" name="fechaFin" class="form-control glass-input" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Centro de trabajo <span class="text-warning">*</span>
                        </label>

                        <select name="id_centroTrabajo" class="form-select glass-input" required>

                            <option value="">Seleccione un centro de trabajo</option>

                            @foreach($centros as $ct)
                            <option value="{{ $ct['id'] }}">
                                {{ $ct['nombre'] }}
                            </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Plan de estudios <span class="text-warning">*</span>
                        </label>

                        <select name="id_planEstudios" class="form-select glass-input" required>

                            <option value="">Seleccione un plan</option>

                            @foreach($planes as $pe)
                            <option value="{{ $pe['id'] }}">
                                {{ $pe['nombrePlan'] }}
                            </option>
                            @endforeach

<<<<<<< HEAD
                        <label>Tipo de periodo<span style="color:red;">*</span></label>
                        <select name="id_tipoPeriodo" class="form-control" required>
                            <option value="">Seleccione un periodo</option>
                            @foreach($periodos as $periodo)
                            <option value="{{ $periodo['id'] }}">
                                {{ $periodo['nombrePeriodo'] }}
                            </option>
                            @endforeach
=======
>>>>>>> bde174c (vista de alumno)
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">
                            Tipo de periodo <span class="text-warning">*</span>
                        </label>

                        <select name="id_tipoPeriodo" class="form-select glass-input" required>

                            <option value="">Seleccione un periodo</option>
                            <option value="Semestral">Semestral</option>
                            <option value="Trimestral">Trimestral</option>

                        </select>
                    </div>

                </div>

            </div>

            <div class="modal-footer glass-footer">

                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button class="btn btn-azul px-4" type="submit">
                    <i class="fas fa-save me-2"></i>
                    Guardar
                </button>

            </div>

        </form>
    </div>
</div>