<div class="modal fade" id="modalGrupo" tabindex="-1">
    <div class="modal-dialog">
        <form id="formGrupo" class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Nuevo Grupo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row">

                    <div class="col-md-12 mb-3">
                        <label>Clave del grupo<span style="color:red;">*</span></label>
                        <input type="text" name="clave" class="form-control" required>
                        <br>

                        <label>Fecha creación</label>
                        <input type="date" name="fechaCreacion" class="form-control" readonly required>

                        <br> 
                        <label>Plan de estudios<span style="color:red;">*</span></label>
                        <select name="id_planEstudios" class="form-control" required>
                            <option value="">Seleccione un CT</option>

                            @foreach($planes as $pe)
                            <option value="{{ $pe['id'] }}">
                                {{ $pe['nombrePlan'] }}
                            </option>
                            @endforeach
                        </select>

                        <br>    
                        
                        <label>Centro de trabajo<span style="color:red;">*</span></label>
                        <select name="id_centroTrabajo" class="form-control" required>
                            <option value="">Seleccione un CT</option>

                            @foreach($centros as $ct)
                            <option value="{{ $ct['id'] }}">
                                {{ $ct['nombre'] }}
                            </option>
                            @endforeach
                        </select>
                        <br>
                        

                        <label>Fecha inicio<span style="color:red;">*</span></label>
                        <input type="date" name="fechaInicio" class="form-control" required>

                        <br>

                        <label>Fecha fin<span style="color:red;">*</span></label>
                        <input type="date" name="fechaFin" class="form-control" required>

                        <div id="divCalcularSemanas" style="display: none; margin-top: 10px; margin-bottom: 10px;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkCalcularSemanas">
                                <label class="form-check-label" for="chkCalcularSemanas" style="font-weight: 500; font-size: 0.9rem;">
                                    Calcular 78 semanas automáticamente (BGNE)
                                </label>
                            </div>
                        </div>

                        <br>

                        

                        <label>Tipo de periodo<span style="color:red;">*</span></label>
                        <select name="id_tipoPeriodo" class="form-control" required>
                            <option value="">Seleccione un periodo</option>
                            @foreach($periodos as $periodo)
                            <option value="{{ $periodo['id'] }}">
                                {{ $periodo['nombrePeriodo'] }}
                            </option>
                            @endforeach
                        </select>

                        <br>

                        <label>Nivel académico<span style="color:red;">*</span></label>
                        <select name="id_nivel_academico" class="form-control" required>
                            <option value="">Seleccione un nivel</option>
                            @foreach($niveles as $nivel)
                            <option value="{{ $nivel['id'] }}">
                                {{ $nivel['nombre'] }}
                            </option>
                            @endforeach
                        </select>

                        <br>

                        <label>Modalidad de Horario<span style="color:red;">*</span></label>
                        <select name="modalidadHorario" class="form-control" required>
                            <option value="">Seleccione una modalidad</option>
                            <option value="MATUTINO">MATUTINO</option>
                            <option value="VESPERTINO">VESPERTINO</option>
                            <option value="LIBRE">LIBRE</option>
                        </select>
                    </div>


                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-success" type="submit">Guardar</button>
            </div>

        </form>
    </div>
</div>