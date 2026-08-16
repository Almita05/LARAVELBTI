@extends('layouts.app')

@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<div class="page-container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ url()->previous() }}" class="btn btn-azul">
                <i class="fa-solid fa-arrow-left me-2"></i>
                Regresar
            </a>


            
        </div>
        <h3 class="page-title">
             <i class="fa-solid fa-chalkboard-user me-2"></i>
            Listado de alumnos
        </h3>

        @if(has_perm('alumnos_list', 'crear'))
        <button class="btn btn-azul" onclick="abrirModalAlumno()">
            <i class="fa-solid fa-plus me-2"></i>
            Alta alumno
        </button>
        @endif
    </div>

    <div class="glass-card">

        <div class="glass-header p-3">
            @if(isset($grupoId))
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" id="tituloListaAlumnos">
                        Alumnos del Grupo: <strong>{{ $grupoClave }}</strong>
                    </h5>
                </div>
            @else
                <div class="row g-3 align-items-end">
                    <!-- Título -->
                    <div class="col-md-2">
                        <h5 class="mb-0" id="tituloListaAlumnos" style="font-weight:600; color:#334155; font-size: 1.1rem;">Lista de alumnos</h5>
                    </div>
                    <!-- Buscador -->
                    <div class="col-md-3">
                        <label class="form-label mb-1" style="font-size:0.75rem; font-weight:600; color:#334155;">Buscar Alumno</label>
                        <input type="text" id="buscadorAlumnos" class="form-control glass-input w-100" placeholder="Buscar alumnos..." style="min-height:38px; font-size:0.85rem;">
                    </div>
                    <!-- CCT -->
                    <div class="col-md-2">
                        <label class="form-label mb-1" style="font-size:0.75rem; font-weight:600; color:#334155;">CCT</label>
                        <select id="filtroCCT" class="form-select glass-input w-100" style="min-height:38px; font-size:0.85rem; padding: 0.35rem 0.75rem;">
                            <option value="">-- Todos --</option>
                            <option value="3">BGNE</option>
                            <option value="2">BTI</option>
                            <option value="1">INF. Y COMP.</option>
                        </select>
                    </div>
                    <!-- Generación -->
                    <div class="col-md-2" id="contenedorFiltroGeneracion">
                        <label class="form-label mb-1" style="font-size:0.75rem; font-weight:600; color:#334155;">Generación</label>
                        <select id="filtroBGNE" class="form-select glass-input w-100" style="min-height:38px; font-size:0.85rem; padding: 0.35rem 0.75rem;">
                            <option value="" class="valueGeneraciones">Todas</option>
                            @foreach($generaciones as $generacion)
                            <option value="{{ $generacion['generacion'] }}" class="valueGeneraciones">
                                Gen {{ $generacion['generacion'] }} - {{ $generacion['nombreGeneracion'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Estado -->
                    <div class="col-md-2">
                        <label class="form-label mb-1" style="font-size:0.75rem; font-weight:600; color:#334155;">Estado</label>
                        <select id="filtroStatus" class="form-select glass-input w-100" style="min-height:38px; font-size:0.85rem; padding: 0.35rem 0.75rem;">
                            <option value="">-- Todos --</option>
                            <option value="ACTIVO">ACTIVO</option>
                            <option value="INACTIVO">INACTIVO</option>
                            <option value="CERTIFICADO">CERTIFICADO</option>
                            <option value="BAJA">BAJA</option>
                        </select>
                    </div>
                    <!-- Ordenación -->
                    <div class="col-md-1">
                        <label class="form-label mb-1" style="font-size:0.75rem; font-weight:600; color:#334155;">Orden ID</label>
                        <select id="filtroOrden" class="form-select glass-input w-100" style="min-height:38px; font-size:0.85rem; padding: 0.35rem 0.75rem;">
                            <option value="ASC">Menor-Mayor</option>
                            <option value="DESC">Mayor-Menor</option>
                        </select>
                    </div>
                </div>
            @endif
        </div>

        <div class="table-responsive">

            <div id="loading" class="text-center py-4" style="display:none;">
                <div class="spinner-border text-light"></div>
            </div>

            <table class="table table-borderless glass-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Grupo</th>
                        <th>Generación</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaAlumnos"></tbody>
            </table>

        </div>

        <div class="glass-footer p-3 d-flex justify-content-between align-items-center">
            <small id="infoPaginacionMaterias"></small>
            <div id="paginacionMaterias"></div>
        </div>

    </div>

    <!-- ESTILOS DE MODALES (Z-INDEX Y MARGEN PARA NAVBAR FIJA) -->
    <style>
        .modal {
            z-index: 1200 !important;
        }
        .modal-backdrop {
            z-index: 1150 !important;
        }
        .modal .modal-dialog {
            margin-top: 85px !important;
            margin-bottom: 50px !important;
        }
    </style>

    <!-- MODAL DE KÁRDEX OFICIAL / CALIFICACIONES -->
    <div class="modal fade" id="modalKardexAlumno" tabindex="-1" aria-labelledby="modalKardexLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="background: #ffffff;">
                <div class="modal-header py-3 px-4 d-flex justify-content-between align-items-center" style="background: #1e6fa8 !important; color: #ffffff !important;">
                    <h5 class="modal-title fw-bold text-white mb-0" id="modalKardexLabel">
                        <i class="fa-solid fa-graduation-cap me-2"></i> Kárdex Oficial y Calificaciones
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-light btn-sm fw-semibold shadow-sm text-dark px-3" onclick="imprimirKardex()">
                            <i class="fa-solid fa-print me-1 text-primary"></i> Imprimir Kárdex
                        </button>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body p-4 bg-white" id="kardexPrintArea" style="max-height: calc(85vh - 130px); overflow-y: auto;">
                    
                    <!-- ENCABEZADO OFICIAL CON MEMBRETE INSTITUCIONAL -->
                    <div class="mb-3 pt-1">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <!-- Logo Institucional -->
                            <div style="flex-shrink: 0;">
                                <img id="kardexLogoImg" src="{{ asset('img/logo.png') }}" alt="Logo Institucional" style="height: 80px; width: auto; object-fit: contain;">
                            </div>

                            <!-- Banner Bicolor Oficial -->
                            <div class="flex-grow-1 border" style="border-color: #10599a !important; overflow: hidden; border-radius: 4px;">
                                <div class="py-1 px-3 text-center text-white fw-bold text-uppercase" style="background: #10599a; font-size: 1.15rem; letter-spacing: 1px;">
                                    BACHILLERATO INTERAMERICANO
                                </div>
                                <div class="py-1 px-2 text-center" style="background: #d4ebf9; color: #0f172a; font-size: 0.78rem; line-height: 1.35;">
                                    <div>Avenida Benito Juárez 901, Colonia Centro Teziutlán Puebla. Tel: 231-3123979</div>
                                    <div class="fw-bold" id="kardexCCTClave">CLAVE CT: 21PBH0353G</div>
                                </div>
                            </div>
                        </div>

                        <!-- Texto institucional y Alumno -->
                        <div class="text-center mt-2">
                            <div class="text-dark" style="font-size: 0.85rem;">
                                La Dirección de la escuela <strong id="kardexCCTNombre">BACHILLERATO GENERAL NO ESCOLARIZADO</strong>
                            </div>
                            <div class="text-secondary fst-italic" style="font-size: 0.80rem;">
                                Reporta las siguientes calificaciones obtenidas hasta el momento del alumno(a):
                            </div>
                            <div class="my-2 py-1 px-4 bg-light border rounded-pill d-inline-block shadow-sm">
                                <span class="fw-bold text-dark text-uppercase fs-5" id="kardexNombreAlumno" style="letter-spacing: 0.5px; text-decoration: underline;">
                                    —
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- CONTENEDOR DE PERIODOS (GRID 2 COLUMNAS X 3 FILAS) -->
                    <div class="row g-2" id="contenedorPeriodosKardex">
                        <div class="col-12 text-center py-5">
                            <div class="spinner-border text-primary"></div>
                            <div class="text-muted mt-2">Cargando kárdex de calificaciones...</div>
                        </div>
                    </div>

                    <!-- LEMA INFERIOR OFICIAL -->
                    <div class="text-center mt-2 p-1 text-white fw-semibold rounded-1" style="background: #4a90e2; font-size: 0.82rem; letter-spacing: 0.5px;">
                        ¡ Excelencia educativa a su servicio !
                    </div>

                </div>
                <div class="modal-footer bg-light border-top py-2 px-4 d-flex justify-content-between">
                    <span class="text-muted" style="font-size: 0.82rem;">
                        <i class="fa-solid fa-circle-info me-1 text-info"></i> Puedes capturar o ajustar calificaciones directamente en cada casilla.
                    </span>
                    <div>
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-success px-4 fw-bold shadow-sm" onclick="guardarCalificacionesKardex()" id="btnGuardarKardex">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Guardar Calificaciones
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

<script>
let modoAlumno = 'crear';
let idAlumnoActual = null;
let grupoId = @json($grupoId ?? null);
const canCreateAlumno = @json(has_perm('alumnos_list', 'crear'));
const canEditAlumno = @json(has_perm('alumnos_list', 'crear'));
const canDeleteAlumno = @json(has_perm('alumnos_list', 'eliminar'));
const canConsultAlumno = @json(has_perm('alumnos_list', 'consultar'));

// Lógica para mostrar/ocultar campos del Certificado según el Estado del Alumno
window.actualizarVistaCertificado = function() {
    const form = document.getElementById('formAlumno');
    if (!form) return;
    const statusSelect = form.querySelector('[name="statusAlumno"]');
    const seccionCertificado = document.getElementById('seccionCertificado');
    if (statusSelect && seccionCertificado) {
        if (statusSelect.value === 'CERTIFICADO') {
            seccionCertificado.style.setProperty('display', 'flex', 'important');
        } else {
            seccionCertificado.style.setProperty('display', 'none', 'important');
        }
    }
};

// ==========================================
// REACTIVIDAD DINÁMICA DE ALTA DE ALUMNOS
// ==========================================

function calcularEdadExacta(fechaNacStr) {
    if (!fechaNacStr) return null;
    const parts = fechaNacStr.split('-');
    if (parts.length !== 3) return null;
    const nac = new Date(parts[0], parts[1] - 1, parts[2]);
    const hoy = new Date();
    
    let anos = hoy.getFullYear() - nac.getFullYear();
    let meses = hoy.getMonth() - nac.getMonth();
    let dias = hoy.getDate() - nac.getDate();
    
    if (dias < 0) {
        meses--;
    }
    if (meses < 0) {
        anos--;
        meses += 12;
    }
    
    const totalMeses = (anos * 12) + meses;
    
    return {
        anos: anos,
        meses: meses,
        totalMeses: totalMeses,
        esMenor16Medio: totalMeses < 198 // 16 años * 12 + 6 meses = 198 meses
    };
}

function verificarEdadBGNE() {
    const form = document.getElementById('formAlumno');
    if (!form) return;

    const inputFechaNac = form.querySelector('#inputFechaNacimiento') || form.querySelector('input[name="fechaNacimiento"]');
    const selectCCT = form.querySelector('#selectCCT');
    const txtEdad = form.querySelector('#txtEdadCalculada');
    const alertaBGNE = form.querySelector('#alertaEdadBGNE');
    const txtDetalleEdad = form.querySelector('#txtDetalleEdadBGNE');
    const selectEstadoSEP = form.querySelector('#selectEstadoSEP');

    if (!inputFechaNac || !inputFechaNac.value) {
        if (txtEdad) txtEdad.textContent = '';
        if (alertaBGNE) alertaBGNE.style.display = 'none';
        return;
    }

    const edad = calcularEdadExacta(inputFechaNac.value);
    if (!edad) return;

    const selectedCCTVal = selectCCT ? selectCCT.value : '';

    if (!selectedCCTVal) {
        // CCT no seleccionado: mostrar solo la edad calculada de manera neutral
        if (txtEdad) {
            txtEdad.innerHTML = `<span class="text-secondary"><i class="bi bi-calendar3 me-1"></i>Edad calculada: ${edad.anos} años, ${edad.meses} meses</span>`;
        }
        if (alertaBGNE) alertaBGNE.style.display = 'none';
        return;
    }

    const selectedOpt = selectCCT.options[selectCCT.selectedIndex];
    const ctNombre = selectedOpt ? selectedOpt.textContent.toUpperCase() : '';
    const isBgne = ctNombre.includes('BGNE');

    if (txtEdad) {
        if (isBgne && edad.esMenor16Medio) {
            txtEdad.innerHTML = `<span class="text-warning fw-bold"><i class="bi bi-exclamation-triangle me-1"></i>${edad.anos} años, ${edad.meses} meses (Menor a 16.5 años - Pendiente SEP en BGNE)</span>`;
        } else if (isBgne) {
            txtEdad.innerHTML = `<span class="text-success"><i class="bi bi-check-circle me-1"></i>${edad.anos} años, ${edad.meses} meses (Edad reglamentaria BGNE)</span>`;
        } else {
            txtEdad.innerHTML = `<span class="text-success"><i class="bi bi-check-circle me-1"></i>${edad.anos} años, ${edad.meses} meses (Apto para inscripción en BTI)</span>`;
        }
    }

    if (isBgne && edad.esMenor16Medio) {
        if (alertaBGNE) {
            alertaBGNE.style.display = 'block';
            if (txtDetalleEdad) {
                txtDetalleEdad.textContent = `${edad.anos} años y ${edad.meses} meses`;
            }
        }
        if (selectEstadoSEP && selectEstadoSEP.value === 'REGISTRADO') {
            selectEstadoSEP.value = 'PENDIENTE';
        }
    } else {
        if (alertaBGNE) alertaBGNE.style.display = 'none';
    }
}

function actualizarResumenYSemaforo() {
    const form = document.getElementById('formAlumno');
    if (!form) return;

    // 0. Nombre del Alumno
    const inputNombre = form.querySelector('[name="nombre"]');
    const inputApPaterno = form.querySelector('[name="apPaterno"]');
    const inputApMaterno = form.querySelector('[name="apMaterno"]');
    const sumNombreAlumno = form.querySelector('#sumNombreAlumno');
    if (sumNombreAlumno) {
        let nom = inputNombre ? inputNombre.value.trim() : '';
        let pat = inputApPaterno ? inputApPaterno.value.trim() : '';
        let mat = inputApMaterno ? inputApMaterno.value.trim() : '';
        let completo = `${nom} ${pat} ${mat}`.trim().toUpperCase();
        sumNombreAlumno.textContent = completo || '—';
    }

    const selectCCT = form.querySelector('#selectCCT');
    const selectNivel = form.querySelector('#selectNivelAcademico');
    const selectDia = form.querySelector('#selectDiaAsistencia');
    const selectJornada = form.querySelector('#selectJornada');
    const selectGen = form.querySelector('#selectGeneracion');
    const selectRequiereEquiv = form.querySelector('#selectRequiereEquiv');
    const selectCertIncompleto = form.querySelector('#selectCertIncompleto');
    const selectPagoEquiv = form.querySelector('#selectEstadoPagoEquiv');
    const selectEstadoSEP = form.querySelector('#selectEstadoSEP');
    const selectAsistePresencial = form.querySelector('#selectAsistePresencial');
    const selectGrupoPresencial = form.querySelector('#inputGrupoPresencial');

    // Elementos de resumen
    const sumCCT = form.querySelector('#sumCCT');
    const sumPeriodo = form.querySelector('#sumPeriodo');
    const sumDiaJornada = form.querySelector('#sumDiaJornada');
    const sumGrupo = form.querySelector('#sumGrupo');
    const sumGeneracion = form.querySelector('#sumGeneracion');
    const sumAtencionPresencial = form.querySelector('#sumAtencionPresencial');
    const sumEstadoSEP = form.querySelector('#sumEstadoSEP');
    const sumEquivalencia = form.querySelector('#sumEquivalencia');
    const sumMateriasPend = form.querySelector('#sumMateriasPend');
    const bannerTrayectoria = form.querySelector('#bannerResumenTrayectoriaCruzada');
    const txtResumenGrupoPresencial = form.querySelector('#txtResumenGrupoPresencialBTI');
    const badgeEstadoGeneral = form.querySelector('#badgeEstadoGeneral');
    const listaPendientes = form.querySelector('#listaPendientesControlEscolar');

    // 1. CCT
    const cctTexto = selectCCT && selectCCT.value ? (selectCCT.options[selectCCT.selectedIndex].dataset.programa || selectCCT.options[selectCCT.selectedIndex].text.split('(')[0].trim()) : '—';
    if (sumCCT) sumCCT.textContent = cctTexto;

    // 2. Periodo
    const periodoTexto = selectNivel && selectNivel.value ? selectNivel.options[selectNivel.selectedIndex].text.split('(')[0].trim() : '—';
    if (sumPeriodo) sumPeriodo.textContent = periodoTexto;

    // 3. Día y Jornada
    let turnoAsistencia = '—';
    const inputGrupoVal = form.querySelector('#inputGrupoSeleccionado');
    const grupoModalidad = inputGrupoVal ? inputGrupoVal.dataset.modalidad : '';
    
    if (grupoModalidad) {
        // Si hay un grupo seleccionado, usar su modalidad de horario formateada
        const modUpper = grupoModalidad.toUpperCase();
        if (modUpper.includes('SÁBADO') || modUpper.includes('SABADO')) {
            if (modUpper.includes('MAÑANA') || modUpper.includes('MANANA')) {
                turnoAsistencia = 'Sábado (Matutino)';
            } else if (modUpper.includes('TARDE')) {
                turnoAsistencia = 'Sábado (Vespertino)';
            } else {
                turnoAsistencia = `Sábado (${grupoModalidad})`;
            }
        } else if (modUpper.includes('DOMINGO')) {
            if (modUpper.includes('MAÑANA') || modUpper.includes('MANANA')) {
                turnoAsistencia = 'Domingo (Matutino)';
            } else {
                turnoAsistencia = `Domingo (${grupoModalidad})`;
            }
        } else if (modUpper.includes('MATUTINO')) {
            turnoAsistencia = 'Lunes a Viernes (Matutino)';
        } else if (modUpper.includes('VESPERTINO')) {
            turnoAsistencia = 'Lunes a Viernes (Vespertino)';
        } else {
            turnoAsistencia = grupoModalidad;
        }
    } else {
        // Si no hay grupo seleccionado, usar los dropdowns del formulario
        const diaTexto = selectDia && selectDia.value ? selectDia.options[selectDia.selectedIndex].text.split('(')[0].trim() : '—';
        const jornadaTexto = selectJornada && selectJornada.value ? selectJornada.options[selectJornada.selectedIndex].text : '—';
        if (diaTexto !== '—' || jornadaTexto !== '—') {
            if (diaTexto !== '—' && jornadaTexto !== '—') {
                turnoAsistencia = `${diaTexto} (${jornadaTexto})`;
            } else {
                turnoAsistencia = diaTexto !== '—' ? diaTexto : jornadaTexto;
            }
        }
    }
    
    if (sumDiaJornada) {
        sumDiaJornada.textContent = turnoAsistencia;
    }

    // 3.1 Fecha Inicio y Fin Tentativa del Grupo
    const sumFechaInicioGrupo = form.querySelector('#sumFechaInicioGrupo');
    const sumFechaFinTentativa = form.querySelector('#sumFechaFinTentativa');
    if (sumFechaInicioGrupo && sumFechaFinTentativa) {
        const inputGrupo = form.querySelector('#inputGrupoSeleccionado');
        const fechaInicioStr = inputGrupo ? inputGrupo.dataset.fechaInicio : '';
        
        if (fechaInicioStr) {
            sumFechaInicioGrupo.textContent = formatearFechaDisplay(fechaInicioStr);
            
            // Calcular término tentativo
            const parts = fechaInicioStr.split('-');
            const baseDate = new Date(parts[0], parts[1] - 1, parts[2]);
            baseDate.setHours(0, 0, 0, 0);
            
            const optNivel = selectNivel && selectNivel.selectedIndex >= 0 ? selectNivel.options[selectNivel.selectedIndex] : null;
            const tipoNivel = optNivel ? (optNivel.dataset.tipo || 'TRIMESTRE') : 'TRIMESTRE';
            const semanasPorPeriodo = (tipoNivel === 'SEMESTRE') ? 28 : 13;
            const totalSemanas = 6 * semanasPorPeriodo;
            
            const endDate = new Date(baseDate);
            endDate.setDate(baseDate.getDate() + (totalSemanas * 7));
            
            const yyyy = endDate.getFullYear();
            const mm = String(endDate.getMonth() + 1).padStart(2, '0');
            const dd = String(endDate.getDate()).padStart(2, '0');
            sumFechaFinTentativa.textContent = `${dd}/${mm}/${yyyy}`;
        } else {
            sumFechaInicioGrupo.textContent = '—';
            sumFechaFinTentativa.textContent = '—';
        }
    }

    // 4. Grupo Asignado SEP
    if (sumGrupo) {
        const nombreG = form.querySelector('#txtNombreGrupoElegido')?.textContent;
        sumGrupo.textContent = (nombreG && nombreG !== 'Ninguno') ? nombreG : 'Sin grupo';
    }

    // 5. Generación
    if (sumGeneracion && selectGen) {
        sumGeneracion.textContent = selectGen.value ? selectGen.options[selectGen.selectedIndex].text : 'Pendiente SEP';
    }

    // 6. Trayectoria y Atención Presencial en otro CCT
    const selectCCTPresencial = form.querySelector('#selectCCTPresencial');
    const selectGenPresencial = form.querySelector('#selectGeneracionPresencial');
    const esPresencial = selectAsistePresencial && selectAsistePresencial.value === 'SI';
    const cctPresencialTexto = (selectCCTPresencial && selectCCTPresencial.selectedIndex >= 0 && selectCCTPresencial.value) ? (selectCCTPresencial.options[selectCCTPresencial.selectedIndex].dataset.nombre || selectCCTPresencial.options[selectCCTPresencial.selectedIndex].text.split('-')[0].trim()) : 'Otro CCT';

    let grupoPresencialVal = 'Sin asignar';
    if (selectGrupoPresencial && selectGrupoPresencial.value) {
        const optSelected = selectGrupoPresencial.options[selectGrupoPresencial.selectedIndex];
        grupoPresencialVal = optSelected ? optSelected.text.split('-')[0].trim() : selectGrupoPresencial.value;
    }

    let genPresencialTexto = '';
    if (selectGenPresencial && selectGenPresencial.value) {
        const optGen = selectGenPresencial.options[selectGenPresencial.selectedIndex];
        genPresencialTexto = optGen ? optGen.text : '';
    }
    
    const sumAsistePresencialTexto = form.querySelector('#sumAsistePresencialTexto');
    const sumDetalleTrayectoria = form.querySelector('#sumDetalleTrayectoria');
    
    if (sumAsistePresencialTexto) {
        sumAsistePresencialTexto.textContent = esPresencial ? 'SÍ (Trayectoria Cruzada)' : 'Presencial';
    }
    if (sumDetalleTrayectoria) {
        let detalle = esPresencial ? `Plantel ${cctPresencialTexto} (Grupo: ${grupoPresencialVal}` : 'Mismo CCT Administrativo';
        if (esPresencial && genPresencialTexto) {
            detalle += ` | ${genPresencialTexto}`;
        }
        if (esPresencial) detalle += ')';
        sumDetalleTrayectoria.textContent = detalle;
    }

    // 7. Estado SEP
    if (sumEstadoSEP && selectEstadoSEP) {
        const estadoSepVal = selectEstadoSEP.value || 'PENDIENTE';
        sumEstadoSEP.textContent = estadoSepVal;
    }

    // 8. Equivalencia
    const requiereEquiv = selectRequiereEquiv && selectRequiereEquiv.value === 'SI';
    const pagoEquiv = selectPagoEquiv ? selectPagoEquiv.value : 'PENDIENTE';
    if (sumEquivalencia) {
        if (requiereEquiv) {
            sumEquivalencia.textContent = `Requiere trámite (${pagoEquiv})`;
        } else {
            sumEquivalencia.textContent = 'No requiere';
        }
    }

    // 9. Materias pendientes
    const filasMaterias = form.querySelectorAll('#tbodyMateriasPendientes tr:not(.fila-vacia-materias)');
    if (sumMateriasPend) {
        sumMateriasPend.textContent = `${filasMaterias.length} materia(s)`;
    }

    // 10. Observaciones y Pendientes de Control Escolar (Formato Documento)
    if (listaPendientes) {
        const pendientes = [];
        
        const selectTraeBoleta = form.querySelector('#selectTraeBoleta');
        const traeBoleta = selectTraeBoleta && selectTraeBoleta.value === 'SI';

        if (traeBoleta) {
            pendientes.push('<div>• <strong>Trámite de Parcial:</strong> Alumno cuenta con boleta parcial. Dar seguimiento al trámite y pago de su parcial ante la autoridad educativa.</div>');
        }

        if (requiereEquiv && pagoEquiv === 'PAGADO') {
            pendientes.push('<div>• <strong>Trámite de Equivalencia:</strong> Pago recibido. Pendiente gestionar dictamen ante la autoridad educativa.</div>');
        } else if (requiereEquiv && pagoEquiv === 'PENDIENTE') {
            pendientes.push('<div>• <strong>Trámite de Equivalencia:</strong> Pendiente de pago de equivalencia, entrega de certificado parcial, pendiente de trámite de equivalencia.</div>');
        }

        if (filasMaterias.length > 0) {
            pendientes.push(`<div>• <strong>Regularidad Académica:</strong> El alumno tiene ${filasMaterias.length} materia(s) pendiente(s) por acreditar.</div>`);
        }

        const inputFechaNac = form.querySelector('#inputFechaNacimiento') || form.querySelector('input[name="fechaNacimiento"]');
        const edad = inputFechaNac && inputFechaNac.value ? calcularEdadExacta(inputFechaNac.value) : null;
        const isBgne = cctTexto.toUpperCase().includes('BGNE');
        const estadoSep = selectEstadoSEP ? selectEstadoSEP.value : 'PENDIENTE';

        if (estadoSep === 'PENDIENTE') {
            if (isBgne && edad && edad.esMenor16Medio) {
                if (traeBoleta) {
                    pendientes.push(`<div>• <strong>Alta oficial ante SEP (BGNE):</strong> Alumno menor a 16.5 años (${edad.anos} años, ${edad.meses} meses) pero cuenta con boleta parcial. Dar seguimiento al trámite de validación y reingreso de su parcial ante la SEP.</div>`);
                } else {
                    pendientes.push(`<div>• <strong>Alta oficial ante SEP (BGNE):</strong> Alumno menor a 16.5 años (${edad.anos} años, ${edad.meses} meses). Cursa en plantel; el personal administrativo debe tramitar su alta oficial ante SEP una vez cumplida la edad reglamentaria.</div>`);
                }
            } else {
                pendientes.push(`<div>• <strong>Alta oficial ante SEP (${cctTexto !== '—' ? cctTexto : 'Plantel'}):</strong> Trámite de alta y registro oficial del alumno ante la SEP pendiente de realizar por el personal administrativo.</div>`);
            }
        } else if (estadoSep === 'EN_PROCESO') {
            pendientes.push(`<div>• <strong>Alta oficial ante SEP:</strong> Trámite en proceso de cotejo y validación ante la autoridad educativa.</div>`);
        } else if (estadoSep === 'RECHAZADO') {
            pendientes.push(`<div>• <strong>Alta oficial ante SEP:</strong> Registro rechazado ante la SEP. Requiere revisión de documentación por control escolar.</div>`);
        }

        if (esPresencial) {
            let textoTray = `<div>• <strong>Trayectoria Cruzada:</strong> Inscripción administrativa en <strong>${cctTexto}</strong> con clases presenciales en plantel <strong>${cctPresencialTexto}</strong> (Grupo: <strong>${grupoPresencialVal}</strong>`;
            if (genPresencialTexto) {
                textoTray += ` | ${genPresencialTexto}`;
            }
            textoTray += ').</div>';
            pendientes.push(textoTray);
        }

        if (pendientes.length > 0) {
            listaPendientes.innerHTML = pendientes.join('');
            if (badgeEstadoGeneral) {
                badgeEstadoGeneral.className = 'badge bg-secondary';
                badgeEstadoGeneral.textContent = `${pendientes.length} PENDIENTE(S)`;
            }
        } else {
            listaPendientes.innerHTML = '<div class="text-secondary">• Expediente en orden. Sin observaciones ni trámites pendientes.</div>';
            if (badgeEstadoGeneral) {
                badgeEstadoGeneral.className = 'badge bg-dark';
                badgeEstadoGeneral.textContent = 'EN REGLA';
            }
        }
    }
}

function cargarGruposPresenciales(idCctPresencial = null, valorSeleccionado = null, valGenSeleccionado = null) {
    const form = document.getElementById('formAlumno');
    if (!form) return Promise.resolve();
    const selectGrupoPresencial = form.querySelector('#inputGrupoPresencial');
    const selectCCTPresencial = form.querySelector('#selectCCTPresencial');
    const selectGenPresencial = form.querySelector('#selectGeneracionPresencial');
    const selectCCTAdmin = form.querySelector('#selectCCT');
    if (!selectGrupoPresencial) return Promise.resolve();

    let cctTarget = idCctPresencial || (selectCCTPresencial ? selectCCTPresencial.value : null);

    // Si aún no está seleccionado en el select, auto-seleccionar el CCT complementario
    if (!cctTarget && selectCCTAdmin && selectCCTAdmin.value && selectCCTPresencial) {
        const adminId = String(selectCCTAdmin.value);
        for (let opt of selectCCTPresencial.options) {
            if (opt.value && String(opt.value) !== adminId) {
                selectCCTPresencial.value = opt.value;
                cctTarget = opt.value;
                break;
            }
        }
    }

    if (!cctTarget) {
        selectGrupoPresencial.innerHTML = '<option value="">-- Primero seleccione CCT Presencial --</option>';
        if (selectGenPresencial) selectGenPresencial.innerHTML = '<option value="">-- Sin asignación específica --</option>';
        actualizarResumenYSemaforo();
        return Promise.resolve();
    }

    const cctNombre = (selectCCTPresencial && selectCCTPresencial.selectedIndex >= 0) ? selectCCTPresencial.options[selectCCTPresencial.selectedIndex].text.split('-')[0].trim() : 'Presencial';
    selectGrupoPresencial.innerHTML = `<option value="">Cargando grupos de ${cctNombre}...</option>`;

    // Cargar generaciones de ese CCT presencial
    if (selectGenPresencial) {
        selectGenPresencial.innerHTML = '<option value="">Cargando generaciones...</option>';
        fetch(`/catalogos/generaciones?idCentroTrabajo=${cctTarget}`)
            .then(r => r.json())
            .then(gens => {
                selectGenPresencial.innerHTML = `<option value="">-- Generación en ${cctNombre} (Opcional) --</option>`;
                if (Array.isArray(gens)) {
                    gens.forEach(g => {
                        const opt = document.createElement('option');
                        opt.value = g.id;
                        opt.textContent = `Gen. ${g.generacion || ''} - ${g.nombreGeneracion}`;
                        if (valGenSeleccionado && String(g.id) === String(valGenSeleccionado)) {
                            opt.selected = true;
                        }
                        selectGenPresencial.appendChild(opt);
                    });
                }
                actualizarResumenYSemaforo();
            });
    }

    return fetch(`/catalogos/grupos?idCentroTrabajo=${cctTarget}&statusGrupo=ACTIVO`)
        .then(r => r.json())
        .then(resp => {
            const grupos = resp.data || (Array.isArray(resp) ? resp : []);
            selectGrupoPresencial.innerHTML = `<option value="">-- Seleccione un grupo de ${cctNombre} --</option>`;
            if (Array.isArray(grupos)) {
                grupos.forEach(g => {
                    const opt = document.createElement('option');
                    opt.value = g.clave || g.id;
                    opt.textContent = `${g.clave} - ${g.nombre_nivel || ''} (${g.modalidadHorario || 'General'})`;
                    if (valorSeleccionado && (String(g.clave) === String(valorSeleccionado) || String(g.id) === String(valorSeleccionado))) {
                        opt.selected = true;
                    }
                    selectGrupoPresencial.appendChild(opt);
                });
                if (valorSeleccionado) {
                    selectGrupoPresencial.value = valorSeleccionado;
                }
            }
            actualizarResumenYSemaforo();
        })
        .catch(err => {
            console.error('Error al cargar grupos presenciales:', err);
            selectGrupoPresencial.innerHTML = '<option value="">Error al cargar grupos</option>';
            actualizarResumenYSemaforo();
        });
}

function buscarYRecomendarGrupos() {
    const form = document.getElementById('formAlumno');
    if (!form) return;

    const selectCCT = form.querySelector('#selectCCT');
    const selectNivel = form.querySelector('#selectNivelAcademico');
    const selectDia = form.querySelector('#selectDiaAsistencia');
    const selectJornada = form.querySelector('#selectJornada');

    const cctId = selectCCT ? selectCCT.value : null;
    const nivelId = selectNivel ? selectNivel.value : null;
    const dia = selectDia ? selectDia.value : '';
    const jornada = selectJornada ? selectJornada.value : '';

    const alertaBuscando = form.querySelector('#alertaBuscandoGrupo');
    const boxRecomendado = form.querySelector('#boxGrupoRecomendado');
    const boxTabla = form.querySelector('#boxTablaOtrosGrupos');
    const alertaSinGrupo = form.querySelector('#alertaSinGrupo');
    const tbody = form.querySelector('#tbodyGruposCompatibles');

    if (!cctId || !nivelId) {
        if (alertaBuscando) alertaBuscando.style.display = 'block';
        if (boxRecomendado) boxRecomendado.style.display = 'none';
        if (boxTabla) boxTabla.style.display = 'none';
        if (alertaSinGrupo) alertaSinGrupo.style.display = 'none';
        return;
    }

    if (alertaBuscando) alertaBuscando.innerHTML = '<i class="spinner-border spinner-border-sm me-1"></i> Consultando grupos compatibles...';

    let url = `/catalogos/grupos?idCentroTrabajo=${cctId}&idNivelAcademico=${nivelId}&statusGrupo=ACTIVO`;
    if (jornada) url += `&modalidadHorario=${encodeURIComponent(jornada)}`;
    if (dia) url += `&dia=${encodeURIComponent(dia)}`;

    fetch(url)
        .then(r => r.json())
        .then(resp => {
            const grupos = resp.data || (Array.isArray(resp) ? resp : []);

            if (alertaBuscando) alertaBuscando.style.display = 'none';

            if (!grupos.length) {
                if (boxRecomendado) boxRecomendado.style.display = 'none';
                if (boxTabla) boxTabla.style.display = 'none';
                if (alertaSinGrupo) alertaSinGrupo.style.display = 'block';
                actualizarResumenYSemaforo();
                return;
            }

            if (alertaSinGrupo) alertaSinGrupo.style.display = 'none';

            // 0. Obtener información del nivel seleccionado
            const selectedNivelOpt = selectNivel && selectNivel.selectedIndex >= 0 ? selectNivel.options[selectNivel.selectedIndex] : null;
            const numeroNivel = selectedNivelOpt ? parseInt(selectedNivelOpt.dataset.numero) || 1 : 1;
            const tipoNivel = selectedNivelOpt ? (selectedNivelOpt.dataset.tipo || 'TRIMESTRE') : 'TRIMESTRE';

            const hoy = new Date();
            hoy.setHours(0, 0, 0, 0);

            const optCCT = selectCCT && selectCCT.selectedIndex >= 0 ? selectCCT.options[selectCCT.selectedIndex] : null;
            const ctNombre = optCCT ? optCCT.textContent.toUpperCase() : '';
            const isBgne = ctNombre.includes('BGNE');
            const maxDaysPast = isBgne ? 14 : 30; // 2 semanas (14 días) para BGNE, 1 mes (30 días) para BTI

            // Calcular fecha de inicio del nivel para cada grupo
            const gruposConFechaNivel = grupos.map(g => {
                let fInicioNivel = null;
                if (g.fechaInicio) {
                    const parts = g.fechaInicio.split('-');
                    if (parts.length === 3) {
                        const baseDate = new Date(parts[0], parts[1] - 1, parts[2]);
                        baseDate.setHours(0, 0, 0, 0);
                        
                        const semanasPorPeriodo = (tipoNivel === 'SEMESTRE') ? 28 : 13;
                        const semanasAdicionales = (numeroNivel - 1) * semanasPorPeriodo;
                        
                        fInicioNivel = new Date(baseDate);
                        fInicioNivel.setDate(baseDate.getDate() + (semanasAdicionales * 7));
                    }
                }
                return {
                    ...g,
                    fechaInicioNivelObj: fInicioNivel,
                    fechaInicioNivelStr: fInicioNivel ? fInicioNivel.toISOString().split('T')[0] : g.fechaInicio
                };
            });

            // Encontrar el grupo recomendado usando la fecha del nivel
            const gruposRecomendables = gruposConFechaNivel.filter(g => {
                const fInicio = g.fechaInicioNivelObj;
                if (!fInicio) return false;

                if (fInicio >= hoy) {
                    return true; // Próximos a empezar
                } else {
                    // Ya empezaron
                    const diffTime = hoy - fInicio;
                    const diffDays = diffTime / (1000 * 60 * 60 * 24);
                    return diffDays <= maxDaysPast; // máximo 14 días para BGNE, 30 días para BTI
                }
            });

            let recomendado = null;
            if (gruposRecomendables.length > 0) {
                // Ordenar por cercanía a hoy
                gruposRecomendables.sort((a, b) => {
                    return Math.abs(a.fechaInicioNivelObj - hoy) - Math.abs(b.fechaInicioNivelObj - hoy);
                });
                recomendado = gruposRecomendables[0];
            }

            if (boxRecomendado && recomendado) {
                boxRecomendado.style.display = 'block';
                form.querySelector('#txtRecomendadoClave').textContent = recomendado.clave;
                form.querySelector('#txtRecomendadoMeta').innerHTML = `
                    <strong>Modalidad:</strong> ${recomendado.modalidadHorario || 'General'} | 
                    <strong>Nivel:</strong> ${recomendado.nombre_nivel || 'Nivel ' + nivelId} | 
                    <strong>Fecha Inicio Nivel:</strong> ${formatearFechaDisplay(recomendado.fechaInicioNivelStr)}
                `;
                const btnRec = form.querySelector('#btnElegirRecomendado');
                if (btnRec) {
                    btnRec.onclick = function() {
                        elegirGrupoFinal(recomendado.id, recomendado.clave, recomendado.modalidadHorario, recomendado.fechaInicio);
                    };
                }
            } else if (boxRecomendado) {
                boxRecomendado.style.display = 'none';
            }

            // Tabla de todos los grupos (mostrando la fecha de inicio del nivel en la columna Fecha Inicio)
            if (boxTabla && tbody) {
                boxTabla.style.display = 'block';
                let htmlRows = '';
                gruposConFechaNivel.forEach(g => {
                    htmlRows += `
                        <tr>
                            <td class="fw-bold text-primary">${g.clave}</td>
                            <td>${g.modalidadHorario || 'Regular'}</td>
                            <td>${g.nombre_nivel || 'N/A'}</td>
                            <td>${formatearFechaDisplay(g.fechaInicioNivelStr)}</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-xs btn-outline-primary py-1 px-2 btn-elegir-grupo-tabla" 
                                    data-id="${g.id}" data-clave="${g.clave}" data-modalidad="${g.modalidadHorario || ''}" data-fecha-inicio="${g.fechaInicio || ''}">
                                    Seleccionar
                                </button>
                            </td>
                        </tr>
                    `;
                });
                tbody.innerHTML = htmlRows;
            }

            actualizarResumenYSemaforo();
        })
        .catch(err => {
            console.error('Error buscando grupos:', err);
            if (alertaBuscando) alertaBuscando.style.display = 'none';
            if (alertaSinGrupo) alertaSinGrupo.style.display = 'block';
        });
}

function elegirGrupoFinal(idGrupo, claveGrupo, modalidad, fechaInicio) {
    const form = document.getElementById('formAlumno');
    if (!form) return;
    const inputGrupo = form.querySelector('#inputGrupoSeleccionado');
    const badgeElegido = form.querySelector('#badgeGrupoElegidoFinal');
    const txtElegido = form.querySelector('#txtNombreGrupoElegido');

    if (inputGrupo) {
        inputGrupo.value = idGrupo;
        inputGrupo.dataset.modalidad = modalidad || '';
        inputGrupo.dataset.fechaInicio = fechaInicio || '';
    }
    if (txtElegido) txtElegido.textContent = claveGrupo;
    if (badgeElegido) badgeElegido.style.display = 'block';

    actualizarResumenYSemaforo();
}

// Delegación global de eventos para el modal dinámico
document.addEventListener('change', function(e) {
    if (!e.target) return;
    const form = e.target.closest('form') || document.getElementById('formAlumno');
    if (!form) return;

    // 1. Cambio de CCT
    if (e.target.id === 'selectCCT' || e.target.name === 'id_centroTrabajo') {
        const cctId = e.target.value;
        const seccionAcademica = form.querySelector('#seccionAcademicaDinamica');
        const avisoCCT = form.querySelector('#avisoSeleccionarCCT');
        const selectNivel = form.querySelector('#selectNivelAcademico');
        const selectGen = form.querySelector('#selectGeneracion');
        const accordionEquiv = form.querySelector('#accordionItemEquivalencia');

        if (!cctId) {
            if (seccionAcademica) seccionAcademica.style.display = 'none';
            if (avisoCCT) avisoCCT.style.display = 'block';
            if (selectNivel) selectNivel.innerHTML = '<option value="">-- Seleccione Periodo --</option>';
            if (selectGen) selectGen.innerHTML = '<option value="">Pendiente de registro SEP</option>';
            if (accordionEquiv) accordionEquiv.style.display = 'none';
            actualizarResumenYSemaforo();
            return;
        }

        if (avisoCCT) avisoCCT.style.display = 'none';
        if (seccionAcademica) seccionAcademica.style.display = 'block';

        // Configurar opciones de Día de asistencia según Centro de Trabajo
        const selectDia = form.querySelector('#selectDiaAsistencia');
        if (selectDia) {
            const selectedOpt = e.target.options[e.target.selectedIndex];
            const ctNombre = (selectedOpt ? selectedOpt.textContent : '').toUpperCase();
            const isBgne = ctNombre.includes('BGNE');

            if (isBgne) {
                selectDia.innerHTML = `
                    <option value="">-- Todos los días / Seleccione --</option>
                    <option value="SABADO">Sábado</option>
                    <option value="DOMINGO">Domingo</option>
                `;
            } else {
                selectDia.innerHTML = `
                    <option value="LUNES-VIERNES" selected>Lunes a Viernes (Escolarizado)</option>
                    <option value="">-- Todos los días --</option>
                `;
            }
        }

        // Cargar Periodos
        if (selectNivel) {
            selectNivel.innerHTML = '<option value="">Cargando periodos...</option>';
            fetch(`/catalogos/niveles-academicos?idCentroTrabajo=${cctId}`)
                .then(r => r.json())
                .then(niveles => {
                    selectNivel.innerHTML = '<option value="">-- Seleccione Periodo --</option>';
                    if (Array.isArray(niveles)) {
                        niveles.forEach(n => {
                            const opt = document.createElement('option');
                            opt.value = n.id;
                            opt.dataset.numero = n.numero;
                            opt.dataset.tipo = n.tipo;
                            opt.textContent = `${n.nombre} (${n.nombrePeriodo || n.tipo})`;
                            selectNivel.appendChild(opt);
                        });
                    }
                    actualizarResumenYSemaforo();
                });
        }

        // Cargar Generaciones
        if (selectGen) {
            selectGen.innerHTML = '<option value="">Cargando generaciones...</option>';
            fetch(`/catalogos/generaciones?idCentroTrabajo=${cctId}`)
                .then(r => r.json())
                .then(gens => {
                    selectGen.innerHTML = '<option value="">Pendiente de registro SEP</option>';
                    if (Array.isArray(gens)) {
                        gens.forEach(g => {
                            const opt = document.createElement('option');
                            opt.value = g.id;
                            opt.textContent = `Gen. ${g.generacion || ''} - ${g.nombreGeneracion}`;
                            selectGen.appendChild(opt);
                        });
                    }
                    actualizarResumenYSemaforo();
                });
        }

        const selectAsistePresencial = form.querySelector('#selectAsistePresencial');
        const selectCCTPresencial = form.querySelector('#selectCCTPresencial');
        const txtCCTAdmin = form.querySelector('#txtCCTAdmin');
        const txtCCTPresencialNombre = form.querySelector('#txtCCTPresencialNombre');
        
        if (txtCCTAdmin && e.target.selectedIndex >= 0) {
            txtCCTAdmin.textContent = e.target.options[e.target.selectedIndex]?.text?.split('(')[0]?.trim() || 'CCT Principal';
        }

        if (selectAsistePresencial && selectAsistePresencial.value === 'SI' && selectCCTPresencial) {
            Array.from(selectCCTPresencial.options).forEach(opt => {
                if (opt.value) {
                    opt.style.display = (String(opt.value) === String(cctId)) ? 'none' : 'block';
                }
            });
            if (!selectCCTPresencial.value || String(selectCCTPresencial.value) === String(cctId)) {
                for (let opt of selectCCTPresencial.options) {
                    if (opt.value && String(opt.value) !== String(cctId)) {
                        selectCCTPresencial.value = opt.value;
                        break;
                    }
                }
            }
            if (txtCCTPresencialNombre && selectCCTPresencial.selectedIndex >= 0) {
                txtCCTPresencialNombre.textContent = selectCCTPresencial.options[selectCCTPresencial.selectedIndex]?.text?.split('-')[0]?.trim() || '';
            }
            cargarGruposPresenciales();
        }

        verificarEdadBGNE();
        buscarYRecomendarGrupos();
    }

    // 2. Cambio de Periodo / Nivel Académico
    if (e.target.id === 'selectNivelAcademico' || e.target.name === 'id_nivel_academico') {
        const nivelId = e.target.value;
        const accordionEquiv = form.querySelector('#accordionItemEquivalencia');

        if (nivelId) {
            const selectedOpt = e.target.options[e.target.selectedIndex];
            const numPeriodo = parseInt(selectedOpt.dataset.numero || 1);

            // Regla: 1er Periodo -> Oculto. 2do en adelante -> Visible
            if (accordionEquiv) {
                if (numPeriodo >= 2) {
                    accordionEquiv.style.display = 'block';
                } else {
                    accordionEquiv.style.display = 'none';
                }
            }
        } else {
            if (accordionEquiv) accordionEquiv.style.display = 'none';
        }

        buscarYRecomendarGrupos();
    }

    // 3. Cambio de Día o Jornada
    if (e.target.id === 'selectDiaAsistencia' || e.target.id === 'selectJornada') {
        buscarYRecomendarGrupos();
    }

    // 4. Cambio de Procedencia
    if (e.target.id === 'selectTipoProcedencia') {
        const alertaSecundaria = form.querySelector('#boxAlertaSecundaria');
        if (alertaSecundaria) {
            alertaSecundaria.style.display = (e.target.value === 'secundaria') ? 'block' : 'none';
        }
    }

    // 5. Cambio de Atención Presencial
    if (e.target.id === 'selectAsistePresencial') {
        const boxCCTPresencial = form.querySelector('#boxCCTPresencial');
        const boxPresencial = form.querySelector('#boxGrupoPresencialBTI');
        const alertaPresencial = form.querySelector('#alertaTrayectoriaEspecial');
        const txtCCTAdmin = form.querySelector('#txtCCTAdmin');
        const txtCCTPresencialNombre = form.querySelector('#txtCCTPresencialNombre');
        const selectCCT = form.querySelector('#selectCCT');
        const selectCCTPresencial = form.querySelector('#selectCCTPresencial');
        const boxGeneracionPresencial = form.querySelector('#boxGeneracionPresencial');
        const activo = e.target.value === 'SI';

        if (boxCCTPresencial) boxCCTPresencial.style.display = activo ? 'block' : 'none';
        if (boxPresencial) boxPresencial.style.display = activo ? 'block' : 'none';
        if (boxGeneracionPresencial) boxGeneracionPresencial.style.display = activo ? 'block' : 'none';
        if (alertaPresencial) alertaPresencial.style.display = activo ? 'block' : 'none';

        if (txtCCTAdmin && selectCCT && selectCCT.selectedIndex >= 0) {
            txtCCTAdmin.textContent = selectCCT.options[selectCCT.selectedIndex]?.text?.split('(')[0]?.trim() || 'CCT Principal';
        }

        if (activo) {
            // Filtrar CCT Presencial para que no se elija a sí mismo
            if (selectCCTPresencial && selectCCT && selectCCT.value) {
                const adminId = String(selectCCT.value);
                Array.from(selectCCTPresencial.options).forEach(opt => {
                    if (opt.value) {
                        opt.style.display = (String(opt.value) === adminId) ? 'none' : 'block';
                    }
                });
                // Auto-seleccionar CCT complementario
                if (!selectCCTPresencial.value || String(selectCCTPresencial.value) === adminId) {
                    for (let opt of selectCCTPresencial.options) {
                        if (opt.value && String(opt.value) !== adminId) {
                            selectCCTPresencial.value = opt.value;
                            break;
                        }
                    }
                }
            }
            if (txtCCTPresencialNombre && selectCCTPresencial && selectCCTPresencial.selectedIndex >= 0) {
                txtCCTPresencialNombre.textContent = selectCCTPresencial.options[selectCCTPresencial.selectedIndex]?.text?.split('-')[0]?.trim() || '';
            }
            cargarGruposPresenciales();
        } else {
            const selectGrupoPresencial = form.querySelector('#inputGrupoPresencial');
            const selectGenPresencial = form.querySelector('#selectGeneracionPresencial');
            if (selectGrupoPresencial) selectGrupoPresencial.value = '';
            if (selectGenPresencial) selectGenPresencial.value = '';
        }
        actualizarResumenYSemaforo();
    }

    // 5.1 Cambio de CCT Presencial
    if (e.target.id === 'selectCCTPresencial' || e.target.name === 'id_centroTrabajo_presencial') {
        const txtCCTPresencialNombre = form.querySelector('#txtCCTPresencialNombre');
        if (txtCCTPresencialNombre && e.target.selectedIndex >= 0) {
            txtCCTPresencialNombre.textContent = e.target.options[e.target.selectedIndex]?.text?.split('-')[0]?.trim() || '';
        }
        cargarGruposPresenciales(e.target.value);
    }

    // 5.2 Cambio en Grupo Presencial o Generación Presencial Seleccionada
    if (e.target.id === 'inputGrupoPresencial' || e.target.name === 'grupoPresencialAtencion' || e.target.id === 'selectGeneracionPresencial' || e.target.name === 'id_generacion_presencial') {
        actualizarResumenYSemaforo();
    }

    // 6. Cambio de Fecha de Nacimiento
    if (e.target.id === 'inputFechaNacimiento' || e.target.name === 'fechaNacimiento') {
        verificarEdadBGNE();
        actualizarResumenYSemaforo();
    }

    // 7. Cambio de Estado de Registro SEP (Restricción por edad en BGNE)
    if (e.target.id === 'selectEstadoSEP') {
        const inputFechaNac = form.querySelector('#inputFechaNacimiento') || form.querySelector('input[name="fechaNacimiento"]');
        const selectCCT = form.querySelector('#selectCCT');
        const edad = inputFechaNac && inputFechaNac.value ? calcularEdadExacta(inputFechaNac.value) : null;
        const ctNombre = (selectCCT && selectCCT.selectedIndex >= 0 ? selectCCT.options[selectCCT.selectedIndex].textContent : '').toUpperCase();
        const isBgne = ctNombre.includes('BGNE');

        if (isBgne && edad && edad.esMenor16Medio && e.target.value === 'REGISTRADO') {
            Swal.fire({
                icon: 'warning',
                title: 'Restricción de Edad SEP (BGNE)',
                text: 'El alumno tiene menos de 16 años y medio. Puede cursar y asistir a BGNE, pero su estatus oficial ante SEP debe permanecer como PENDIENTE hasta cumplir la edad reglamentaria (16.5 años).',
                confirmButtonColor: '#317D92'
            });
            e.target.value = 'PENDIENTE';
        }
        actualizarResumenYSemaforo();
    }

    // 8. Cambio en Estado del Alumno (CERTIFICADO / ACTIVO / etc)
    if (e.target.id === 'selectStatusAlumno' || e.target.name === 'statusAlumno') {
        const boxCert = form.querySelector('#boxDatosCertificado');
        if (boxCert) {
            boxCert.style.display = (e.target.value === 'CERTIFICADO') ? 'block' : 'none';
        }
        actualizarResumenYSemaforo();
    }

    // 8.1 Cambio en ¿Recogió Certificado?
    if (e.target.id === 'selectRecogioCertificado' || e.target.name === 'recogioCertificado') {
        const boxFecha = form.querySelector('#boxFechaRecogioCertificado');
        if (boxFecha) {
            boxFecha.style.display = (e.target.value === '1' || e.target.value === 'SI') ? 'block' : 'none';
        }
    }

    // 9. Alerta de Acción de Equivalencia
    if (e.target.id === 'selectCertIncompleto' || e.target.id === 'selectRequiereEquiv' || e.target.id === 'selectEstadoPagoEquiv') {
        const certIncompleto = form.querySelector('#selectCertIncompleto')?.value === 'SI';
        const requiereEquiv = form.querySelector('#selectRequiereEquiv')?.value === 'SI';
        const pagoPagado = form.querySelector('#selectEstadoPagoEquiv')?.value === 'PAGADO';
        const alertaAccion = form.querySelector('#alertaAccionEquivalencia');

        if (alertaAccion) {
            if (certIncompleto && requiereEquiv && pagoPagado) {
                alertaAccion.style.display = 'block';
            } else {
                alertaAccion.style.display = 'none';
            }
        }
    }

    actualizarResumenYSemaforo();
});

// Función central para inicializar el modal dinámico en Crear, Editar o Ver
window.initModalAlumnoDinamico = function(al) {
    const form = document.getElementById('formAlumno');
    if (!form) return;

    if (!al) {
        // Modo Crear: ocultar sección académica hasta elegir CCT
        const seccionDinamica = form.querySelector('#seccionAcademicaDinamica');
        if (seccionDinamica) seccionDinamica.style.display = 'none';
        actualizarResumenYSemaforo();
        return;
    }

    // Modo Editar o Ver Alumno
    const cctId = al.id_centroTrabajo || al.idCentroTrabajo;
    const nivelId = al.id_nivel_academico || al.idNivelAcademico;
    const genId = al.id_Generacion || al.idGeneracion;
    const grupoIdAl = al.id_Grupo || al.idGrupo;
    const statusAl = (al.statusAlumno || 'ACTIVO').toUpperCase();

    // 1. Manejo de Certificación
    const selectStatus = form.querySelector('#selectStatusAlumno');
    const boxCert = form.querySelector('#boxDatosCertificado');
    const selectRecogio = form.querySelector('#selectRecogioCertificado');
    const boxFechaRecogio = form.querySelector('#boxFechaRecogioCertificado');

    if (selectStatus) selectStatus.value = statusAl;
    if (boxCert) boxCert.style.display = (statusAl === 'CERTIFICADO') ? 'block' : 'none';
    if (selectRecogio && al.recogioCertificado !== undefined && al.recogioCertificado !== null) {
        selectRecogio.value = (al.recogioCertificado == 1 || al.recogioCertificado === 'SI') ? '1' : '0';
    }
    if (boxFechaRecogio) {
        boxFechaRecogio.style.display = (selectRecogio && (selectRecogio.value === '1' || selectRecogio.value === 'SI')) ? 'block' : 'none';
    }

    // 2. Cargar contexto académico del CCT principal
    const selectCCT = form.querySelector('#selectCCT');
    const selectNivel = form.querySelector('#selectNivelAcademico');
    const selectGen = form.querySelector('#selectGeneracion');
    const selectDia = form.querySelector('#selectDiaAsistencia');
    const selectJornada = form.querySelector('#selectJornada');
    const seccionDinamica = form.querySelector('#seccionAcademicaDinamica');

    const setupCCT = function(resolvedCctId) {
        if (!resolvedCctId || !selectCCT) return;
        selectCCT.value = resolvedCctId;
        if (seccionDinamica) seccionDinamica.style.display = 'block';

        const optCCT = selectCCT.options[selectCCT.selectedIndex];
        const isBgne = (optCCT ? optCCT.textContent : '').toUpperCase().includes('BGNE');

        if (selectDia) {
            if (isBgne) {
                selectDia.innerHTML = `
                    <option value="">-- Todos los días / Seleccione --</option>
                    <option value="SABADO">Sábado</option>
                    <option value="DOMINGO">Domingo</option>
                `;
            } else {
                selectDia.innerHTML = `
                    <option value="LUNES-VIERNES">Lunes a Viernes (Escolarizado)</option>
                    <option value="">-- Todos los días --</option>
                `;
            }
            if (al.diaAsistencia) selectDia.value = al.diaAsistencia;
        }
        if (selectJornada && al.jornadaHorario) {
            selectJornada.value = al.jornadaHorario;
        }

        // Cargar Niveles y Generaciones en paralelo para el CCT principal
        Promise.all([
            fetch(`/catalogos/niveles-academicos?idCentroTrabajo=${resolvedCctId}`).then(r => r.json()),
            fetch(`/catalogos/generaciones?idCentroTrabajo=${resolvedCctId}`).then(r => r.json())
        ]).then(([niveles, gens]) => {
            if (selectNivel) {
                selectNivel.innerHTML = '<option value="">-- Seleccione Periodo --</option>';
                if (Array.isArray(niveles)) {
                    niveles.forEach(n => {
                        const opt = document.createElement('option');
                        opt.value = n.id;
                        opt.dataset.numero = n.numero;
                        opt.dataset.tipo = n.tipo;
                        opt.textContent = `${n.nombre} (${n.nombrePeriodo || n.tipo})`;
                        if (nivelId && String(n.id) === String(nivelId)) {
                            opt.selected = true;
                        }
                        selectNivel.appendChild(opt);
                    });
                }
            }

            if (selectGen) {
                selectGen.innerHTML = '<option value="">Pendiente de registro SEP</option>';
                if (Array.isArray(gens)) {
                    gens.forEach(g => {
                        const opt = document.createElement('option');
                        opt.value = g.id;
                        opt.textContent = `Gen. ${g.generacion || ''} - ${g.nombreGeneracion}`;
                        if (genId && String(g.id) === String(genId)) {
                            opt.selected = true;
                        }
                        selectGen.appendChild(opt);
                    });
                }
            }

            // Si hay grupo asignado
            if (grupoIdAl) {
                const inputGrupo = form.querySelector('#inputGrupoSeleccionado');
                const badgeElegido = form.querySelector('#badgeGrupoElegidoFinal');
                const txtElegido = form.querySelector('#txtNombreGrupoElegido');
                if (inputGrupo) {
                    inputGrupo.value = grupoIdAl;
                    inputGrupo.dataset.modalidad = al.jornadaHorario || al.modalidadHorario || '';
                    inputGrupo.dataset.fechaInicio = al.fechaInicioGrupo || al.fechaInicio || '';
                }
                if (txtElegido) txtElegido.textContent = al.nombreGrupoTexto || al.claveGrupo || al.nombreGrupo || `Grupo #${grupoIdAl}`;
                if (badgeElegido) badgeElegido.style.display = 'block';
            }

            actualizarResumenYSemaforo();
        });
    };

    if (cctId) {
        setupCCT(cctId);
    } else if (genId) {
        fetch('/catalogos/generaciones')
            .then(r => r.json())
            .then(allGens => {
                const found = Array.isArray(allGens) ? allGens.find(g => String(g.id) === String(genId)) : null;
                const cctFromGen = found ? (found.id_centroTrabajo || found.idCentroTrabajo) : null;
                if (cctFromGen) {
                    setupCCT(cctFromGen);
                }
            });
    }

    // 3. Trayectoria cruzada y atención presencial en segundo CCT
    const selectAsistePresencial = form.querySelector('#selectAsistePresencial');
    const selectCCTPresencial = form.querySelector('#selectCCTPresencial');
    const boxCCTPresencial = form.querySelector('#boxCCTPresencial');
    const boxPresencial = form.querySelector('#boxGrupoPresencialBTI');
    const boxGeneracionPresencial = form.querySelector('#boxGeneracionPresencial');
    const alertaPresencial = form.querySelector('#alertaTrayectoriaEspecial');

    const esPresencial = (al.asistePresencialBTI === 'SI' || al.asistePresencialBTI == 1);
    if (selectAsistePresencial) selectAsistePresencial.value = esPresencial ? 'SI' : 'NO';

    if (boxCCTPresencial) boxCCTPresencial.style.display = esPresencial ? 'block' : 'none';
    if (boxPresencial) boxPresencial.style.display = esPresencial ? 'block' : 'none';
    if (boxGeneracionPresencial) boxGeneracionPresencial.style.display = esPresencial ? 'block' : 'none';
    if (alertaPresencial) alertaPresencial.style.display = esPresencial ? 'block' : 'none';

    if (esPresencial) {
        const cctPresencialId = al.id_centroTrabajo_presencial || al.idCentroTrabajoPresencial;
        const grupoPresencialVal = al.grupoPresencialAtencion;
        const genPresencialVal = al.id_generacion_presencial || al.idGeneracionPresencial;

        if (selectCCTPresencial && cctPresencialId) {
            selectCCTPresencial.value = cctPresencialId;
        }
        cargarGruposPresenciales(cctPresencialId, grupoPresencialVal, genPresencialVal);
    }

    verificarEdadBGNE();
    actualizarResumenYSemaforo();
};

// Listener de input para actualizar edad y resumen en tiempo real al escribir
document.addEventListener('input', function(e) {
    const form = document.getElementById('formAlumno');
    if (form && form.contains(e.target)) {
        if (e.target.name === 'fechaNacimiento' || e.target.id === 'inputFechaNacimiento') {
            verificarEdadBGNE();
        }
        actualizarResumenYSemaforo();
    }
});

// Clicks interactivos dentro del modal
document.addEventListener('click', function(e) {
    const form = document.getElementById('formAlumno');
    if (!form) return;

    // Seleccionar grupo de la tabla
    if (e.target.classList.contains('btn-elegir-grupo-tabla') || e.target.closest('.btn-elegir-grupo-tabla')) {
        const btn = e.target.classList.contains('btn-elegir-grupo-tabla') ? e.target : e.target.closest('.btn-elegir-grupo-tabla');
        elegirGrupoFinal(btn.dataset.id, btn.dataset.clave, btn.dataset.modalidad, btn.dataset.fechaInicio);
    }

    // Quitar grupo seleccionado
    if (e.target.id === 'btnQuitarGrupo') {
        const inputGrupo = form.querySelector('#inputGrupoSeleccionado');
        const badgeElegido = form.querySelector('#badgeGrupoElegidoFinal');
        const txtElegido = form.querySelector('#txtNombreGrupoElegido');
        if (inputGrupo) inputGrupo.value = '';
        if (txtElegido) txtElegido.textContent = 'Ninguno';
        if (badgeElegido) badgeElegido.style.display = 'none';
        actualizarResumenYSemaforo();
    }

    // Agregar materia pendiente
    if (e.target.id === 'btnAgregarMateriaPendiente' || e.target.closest('#btnAgregarMateriaPendiente')) {
        const tbody = form.querySelector('#tbodyMateriasPendientes');
        if (!tbody) return;
        const filaVacia = tbody.querySelector('.fila-vacia-materias');
        if (filaVacia) filaVacia.remove();

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" name="materiasPendientesNombre[]" class="form-control form-control-sm" placeholder="Nombre de la materia" required></td>
            <td><input type="number" name="materiasPendientesPeriodo[]" class="form-control form-control-sm" placeholder="Periodo" min="1" max="6"></td>
            <td><input type="number" step="0.1" name="materiasPendientesCalif[]" class="form-control form-control-sm" placeholder="Calificación"></td>
            <td class="text-center">
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-link text-success p-0 btn-confirmar-materia-pend" title="Confirmar materia"><i class="bi bi-check-lg" style="font-size: 1.25rem;"></i></button>
                    <button type="button" class="btn btn-sm btn-link text-danger p-0 btn-eliminar-materia-pend" title="Eliminar"><i class="bi bi-trash"></i></button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
        actualizarResumenYSemaforo();
    }

    // Confirmar materia pendiente
    if (e.target.classList.contains('btn-confirmar-materia-pend') || e.target.closest('.btn-confirmar-materia-pend')) {
        const btn = e.target.classList.contains('btn-confirmar-materia-pend') ? e.target : e.target.closest('.btn-confirmar-materia-pend');
        const tr = btn.closest('tr');
        if (tr) {
            const inputNombre = tr.querySelector('input[name="materiasPendientesNombre[]"]');
            const inputPeriodo = tr.querySelector('input[name="materiasPendientesPeriodo[]"]');
            const inputCalif = tr.querySelector('input[name="materiasPendientesCalif[]"]');
            
            if (inputNombre) {
                const nombreVal = inputNombre.value.trim();
                const periodoVal = inputPeriodo ? inputPeriodo.value.trim() : '';
                const califVal = inputCalif ? inputCalif.value.trim() : '';
                
                if (!nombreVal) {
                    inputNombre.focus();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campo Requerido',
                        text: 'Por favor, introduce el nombre de la materia.',
                        confirmButtonColor: '#317D92'
                    });
                    return;
                }
                
                tr.innerHTML = `
                    <td>
                        <span class="text-dark fw-semibold">${nombreVal}</span>
                        <input type="hidden" name="materiasPendientesNombre[]" value="${nombreVal}">
                    </td>
                    <td>
                        <span>${periodoVal ? (periodoVal + '°') : '—'}</span>
                        <input type="hidden" name="materiasPendientesPeriodo[]" value="${periodoVal}">
                    </td>
                    <td>
                        <span class="badge bg-secondary" style="font-size: 0.8rem; padding: 4px 8px; border-radius: 6px;">${califVal || '—'}</span>
                        <input type="hidden" name="materiasPendientesCalif[]" value="${califVal}">
                    </td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <button type="button" class="btn btn-sm btn-link text-primary p-0 btn-editar-materia-pend" title="Editar"><i class="bi bi-pencil"></i></button>
                            <button type="button" class="btn btn-sm btn-link text-danger p-0 btn-eliminar-materia-pend" title="Eliminar"><i class="bi bi-trash"></i></button>
                        </div>
                    </td>
                `;
                actualizarResumenYSemaforo();
            }
        }
    }

    // Editar materia pendiente
    if (e.target.classList.contains('btn-editar-materia-pend') || e.target.closest('.btn-editar-materia-pend')) {
        const btn = e.target.classList.contains('btn-editar-materia-pend') ? e.target : e.target.closest('.btn-editar-materia-pend');
        const tr = btn.closest('tr');
        if (tr) {
            const nombreVal = tr.querySelector('input[name="materiasPendientesNombre[]"]')?.value || '';
            const periodoVal = tr.querySelector('input[name="materiasPendientesPeriodo[]"]')?.value || '';
            const califVal = tr.querySelector('input[name="materiasPendientesCalif[]"]')?.value || '';
            
            tr.innerHTML = `
                <td><input type="text" name="materiasPendientesNombre[]" class="form-control form-control-sm" placeholder="Nombre de la materia" value="${nombreVal}" required></td>
                <td><input type="number" name="materiasPendientesPeriodo[]" class="form-control form-control-sm" placeholder="Periodo" min="1" max="6" value="${periodoVal}"></td>
                <td><input type="number" step="0.1" name="materiasPendientesCalif[]" class="form-control form-control-sm" placeholder="Calificación" value="${califVal}"></td>
                <td class="text-center">
                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <button type="button" class="btn btn-sm btn-link text-success p-0 btn-confirmar-materia-pend" title="Confirmar materia"><i class="bi bi-check-lg" style="font-size: 1.25rem;"></i></button>
                        <button type="button" class="btn btn-sm btn-link text-danger p-0 btn-eliminar-materia-pend" title="Eliminar"><i class="bi bi-trash"></i></button>
                    </div>
                </td>
            `;
        }
    }

    // Eliminar fila de materia pendiente
    if (e.target.classList.contains('btn-eliminar-materia-pend') || e.target.closest('.btn-eliminar-materia-pend')) {
        const tr = e.target.closest('tr');
        if (tr) {
            tr.remove();
            const tbody = form.querySelector('#tbodyMateriasPendientes');
            if (tbody && tbody.children.length === 0) {
                tbody.innerHTML = '<tr class="fila-vacia-materias"><td colspan="4" class="text-center text-muted py-2">Sin materias pendientes registradas.</td></tr>';
            }
            actualizarResumenYSemaforo();
        }
    }
});

// Evitar que la tecla Enter envíe el formulario principal y simular confirmación de materia
document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        const form = document.getElementById('formAlumno');
        if (form && form.contains(e.target) && e.target.tagName === 'INPUT') {
            e.preventDefault();
            
            // Si están escribiendo en una fila de materias pendientes, simular clic en confirmar
            if (e.target.name && e.target.name.startsWith('materiasPendientes')) {
                const tr = e.target.closest('tr');
                const btnConfirmar = tr ? tr.querySelector('.btn-confirmar-materia-pend') : null;
                if (btnConfirmar) {
                    btnConfirmar.click();
                }
            }
            return false;
        }
    }
});

function setFormDisabled(disabled) {
    const form = document.getElementById('formAlumno');
    if (!form) return;
    Array.from(form.querySelectorAll('input, select, textarea')).forEach(el => {
        el.disabled = disabled;
    });
}

function formatDateForInput(dateStr) {
    if (!dateStr) return '';
    try {
        const d = new Date(dateStr);
        if (isNaN(d.getTime())) return '';
        const yyyy = d.getFullYear();
        const mm = String(d.getMonth() + 1).padStart(2, '0');
        const dd = String(d.getDate()).padStart(2, '0');
        return `${yyyy}-${mm}-${dd}`;
    } catch(e) {
        return '';
    }
}

function formatearFechaDisplay(dateStr) {
    if (!dateStr) return '—';
    if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
        const parts = dateStr.split('-');
        return `${parts[2]}/${parts[1]}/${parts[0]}`;
    }
    if (/^\d{4}-\d{2}-\d{2}\s/.test(dateStr)) {
        const parts = dateStr.substring(0, 10).split('-');
        return `${parts[2]}/${parts[1]}/${parts[0]}`;
    }
    try {
        const d = new Date(dateStr);
        if (isNaN(d.getTime())) return dateStr;
        if (dateStr.includes('GMT') || dateStr.endsWith('Z')) {
            const dd = String(d.getUTCDate()).padStart(2, '0');
            const mm = String(d.getUTCMonth() + 1).padStart(2, '0');
            const yyyy = d.getUTCFullYear();
            return `${dd}/${mm}/${yyyy}`;
        } else {
            const dd = String(d.getDate()).padStart(2, '0');
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const yyyy = d.getFullYear();
            return `${dd}/${mm}/${yyyy}`;
        }
    } catch(e) {
        return dateStr;
    }
}

function getStatusBadge(status) {
    status = (status || 'ACTIVO').toUpperCase();
    switch (status) {
        case 'ACTIVO':
            return '<span class="badge bg-success" style="font-size: 0.75rem; padding: 5px 10px; border-radius: 12px;">ACTIVO</span>';
        case 'INACTIVO':
            return '<span class="badge bg-secondary" style="font-size: 0.75rem; padding: 5px 10px; border-radius: 12px;">INACTIVO</span>';
        case 'BAJA_TEMPORAL':
            return '<span class="badge bg-warning text-dark" style="font-size: 0.75rem; padding: 5px 10px; border-radius: 12px;">BAJA TEMPORAL</span>';
        case 'CERTIFICADO':
            return '<span class="badge bg-info text-dark" style="font-size: 0.75rem; padding: 5px 10px; border-radius: 12px;">CERTIFICADO</span>';
        case 'REINSCRIPCION':
            return '<span class="badge bg-primary" style="font-size: 0.75rem; padding: 5px 10px; border-radius: 12px;">REINSCRIPCIÓN</span>';
        default:
            return `<span class="badge bg-secondary" style="font-size: 0.75rem; padding: 5px 10px; border-radius: 12px;">${status}</span>`;
    }
}

window.verAlumno = function(id) {
    modoAlumno = 'ver';
    idAlumnoActual = id;

    fetch('/alumnos/modalAlta?_t=' + Date.now())
        .then(res => res.text())
        .then(html => {
            document.getElementById('contenedorModal').innerHTML = html;
            setFormDisabled(true);

            fetch(`/alumnos/${id}`)
                .then(res => res.json())
                .then(resp => {
                    if (resp.success && resp.data) {
                        const al = resp.data;
                        const form = document.getElementById('formAlumno');
                        
                        const fields = [
                            'nombre', 'apPaterno', 'apMaterno', 'fechaNacimiento', 'celularAlumno',
                            'correoAlumno', 'tutor', 'parentesco', 'telefonoTutor', 'calle',
                            'colonia', 'localidad', 'municipio', 'escuelaProcedencia', 'observaciones',
                            'equivalencia', 'numeroControl', 'statusAlumno',
                            'curp', 'folioCertificado', 'fechaRecogioCertificado', 'recogioCertificado',
                            'asistePresencialBTI', 'id_centroTrabajo_presencial', 'grupoPresencialAtencion', 'id_generacion_presencial', 'estadoRegistroSEP'
                        ];
                        fields.forEach(field => {
                            const input = form.querySelector(`[name="${field}"]`);
                            if (input) {
                                if (field === 'fechaNacimiento' || field === 'fechaRecogioCertificado') {
                                    input.value = formatDateForInput(al[field]);
                                } else {
                                    input.value = al[field] || '';
                                }
                            }
                        });

                        // Procesar boleta parcial
                        let obs = al.observaciones || '';
                        let traeBoleta = 'NO';
                        if (obs.includes('[BOLETA_PARCIAL]')) {
                            traeBoleta = 'SI';
                            obs = obs.replace('[BOLETA_PARCIAL]', '').trim();
                        }
                        const selectTraeBoleta = form.querySelector('#selectTraeBoleta');
                        if (selectTraeBoleta) selectTraeBoleta.value = traeBoleta;
                        const textareaObs = form.querySelector('[name="observaciones"]');
                        if (textareaObs) textareaObs.value = obs;

                        if (typeof window.initModalAlumnoDinamico === 'function') {
                            window.initModalAlumnoDinamico(al);
                        }
                        
                        const modalTitle = document.querySelector('#modalAlumno .modal-title');
                        if (modalTitle) {
                            modalTitle.innerHTML = '<i class="bi bi-person-fill me-2"></i> Detalles del Alumno';
                        }
                        const submitBtn = document.querySelector('#formAlumno button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.style.display = 'none';
                        }

                        let modal = new bootstrap.Modal(document.getElementById('modalAlumno'));
                        modal.show();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al cargar datos del alumno.',
                            confirmButtonColor: 'rgb(38, 104, 123)'
                        });
                    }
                });
        });
}

window.editarAlumno = function(id) {
    modoAlumno = 'editar';
    idAlumnoActual = id;

    fetch('/alumnos/modalAlta?_t=' + Date.now())
        .then(res => res.text())
        .then(html => {
            document.getElementById('contenedorModal').innerHTML = html;
            setFormDisabled(false);

            fetch(`/alumnos/${id}`)
                .then(res => res.json())
                .then(resp => {
                    if (resp.success && resp.data) {
                        const al = resp.data;
                        const form = document.getElementById('formAlumno');
                        
                        const fields = [
                            'nombre', 'apPaterno', 'apMaterno', 'fechaNacimiento', 'celularAlumno',
                            'correoAlumno', 'tutor', 'parentesco', 'telefonoTutor', 'calle',
                            'colonia', 'localidad', 'municipio', 'escuelaProcedencia', 'observaciones',
                            'equivalencia', 'numeroControl', 'statusAlumno',
                            'curp', 'folioCertificado', 'fechaRecogioCertificado', 'recogioCertificado',
                            'asistePresencialBTI', 'id_centroTrabajo_presencial', 'grupoPresencialAtencion', 'id_generacion_presencial', 'estadoRegistroSEP'
                        ];
                        fields.forEach(field => {
                            const input = form.querySelector(`[name="${field}"]`);
                            if (input) {
                                if (field === 'fechaNacimiento' || field === 'fechaRecogioCertificado') {
                                    input.value = formatDateForInput(al[field]);
                                } else {
                                    input.value = al[field] || '';
                                }
                            }
                        });

                        // Procesar boleta parcial
                        let obs = al.observaciones || '';
                        let traeBoleta = 'NO';
                        if (obs.includes('[BOLETA_PARCIAL]')) {
                            traeBoleta = 'SI';
                            obs = obs.replace('[BOLETA_PARCIAL]', '').trim();
                        }
                        const selectTraeBoleta = form.querySelector('#selectTraeBoleta');
                        if (selectTraeBoleta) selectTraeBoleta.value = traeBoleta;
                        const textareaObs = form.querySelector('[name="observaciones"]');
                        if (textareaObs) textareaObs.value = obs;

                        if (typeof window.initModalAlumnoDinamico === 'function') {
                            window.initModalAlumnoDinamico(al);
                        }
                        
                        const modalTitle = document.querySelector('#modalAlumno .modal-title');
                        if (modalTitle) {
                            modalTitle.innerHTML = '<i class="bi bi-pencil-square me-2"></i> Editar Alumno';
                        }
                        const submitBtn = document.querySelector('#formAlumno button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.innerHTML = '<i class="bi bi-floppy-fill"></i> Actualizar Alumno';
                        }

                        let modal = new bootstrap.Modal(document.getElementById('modalAlumno'));
                        modal.show();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al cargar datos del alumno.',
                            confirmButtonColor: 'rgb(38, 104, 123)'
                        });
                    }
                });
        });
}

function cargarContextoGrupo(grupoId) {
    if (!grupoId) return;
    
    const form = document.getElementById('formAlumno');
    if (!form) return;
    
    fetch(`/grupos/${grupoId}`)
        .then(res => res.json())
        .then(resp => {
            if (resp.success && resp.data) {
                const gr = resp.data.data || resp.data;
                
                // 1. Establecer idGrupo en el input oculto
                const inputGrupo = form.querySelector('#inputGrupoSeleccionado') || form.querySelector('input[name="id_Grupo"]');
                if (inputGrupo) {
                    inputGrupo.value = grupoId;
                    inputGrupo.dataset.modalidad = gr.modalidadHorario || '';
                    inputGrupo.dataset.fechaInicio = gr.fechaInicio || '';
                }

                // Mostrar banner de grupo elegido
                const txtElegido = form.querySelector('#txtNombreGrupoElegido');
                const badgeElegido = form.querySelector('#badgeGrupoElegidoFinal');
                if (txtElegido) txtElegido.textContent = gr.clave;
                if (badgeElegido) badgeElegido.style.display = 'block';

                // 2. Seleccionar CCT
                const selectCCT = form.querySelector('#selectCCT');
                if (selectCCT && gr.id_centroTrabajo) {
                    selectCCT.value = gr.id_centroTrabajo;

                    // Mostrar sección académica
                    const seccionAcademica = form.querySelector('#seccionAcademicaDinamica');
                    const avisoCCT = form.querySelector('#avisoSeleccionarCCT');
                    const selectDia = form.querySelector('#selectDiaAsistencia');

                    if (avisoCCT) avisoCCT.style.display = 'none';
                    if (seccionAcademica) seccionAcademica.style.display = 'block';

                    // Configurar opciones de Día de asistencia según Centro de Trabajo
                    const optCCT = selectCCT.options[selectCCT.selectedIndex];
                    const ctNombre = (optCCT ? optCCT.textContent : '').toUpperCase();
                    const isBgne = ctNombre.includes('BGNE');
                    if (selectDia) {
                        if (isBgne) {
                            selectDia.innerHTML = `
                                <option value="">-- Todos los días / Seleccione --</option>
                                <option value="SABADO">Sábado</option>
                                <option value="DOMINGO">Domingo</option>
                            `;
                        } else {
                            selectDia.innerHTML = `
                                <option value="LUNES-VIERNES" selected>Lunes a Viernes (Escolarizado)</option>
                                <option value="">-- Todos los días --</option>
                            `;
                        }
                    }

                    // Cargar y auto-seleccionar Nivel
                    const selectNivel = form.querySelector('#selectNivelAcademico');
                    let p1 = Promise.resolve();
                    if (selectNivel && gr.id_nivel_academico) {
                        selectNivel.innerHTML = '<option value="">Cargando periodos...</option>';
                        p1 = fetch(`/catalogos/niveles-academicos?idCentroTrabajo=${gr.id_centroTrabajo}`)
                            .then(r => r.json())
                            .then(niveles => {
                                selectNivel.innerHTML = '<option value="">-- Seleccione Periodo --</option>';
                                if (Array.isArray(niveles)) {
                                    niveles.forEach(n => {
                                        const opt = document.createElement('option');
                                        opt.value = n.id;
                                        opt.dataset.numero = n.numero;
                                        opt.dataset.tipo = n.tipo;
                                        opt.textContent = `${n.nombre} (${n.nombrePeriodo || n.tipo})`;
                                        if (String(n.id) === String(gr.id_nivel_academico)) {
                                            opt.selected = true;
                                        }
                                        selectNivel.appendChild(opt);
                                    });
                                }
                                
                                // Regla de Equivalencia: 1er Periodo -> Oculto. 2do en adelante -> Visible
                                const accordionEquiv = form.querySelector('#accordionItemEquivalencia');
                                if (accordionEquiv) {
                                    const selectedOpt = selectNivel.options[selectNivel.selectedIndex];
                                    const numPeriodo = selectedOpt ? parseInt(selectedOpt.dataset.numero || 1) : 1;
                                    if (numPeriodo >= 2) {
                                        accordionEquiv.style.display = 'block';
                                    } else {
                                        accordionEquiv.style.display = 'none';
                                    }
                                }
                            });
                    }

                    // Cargar y auto-seleccionar Generación
                    const selectGen = form.querySelector('#selectGeneracion');
                    let p2 = Promise.resolve();
                    if (selectGen) {
                        selectGen.innerHTML = '<option value="">Cargando generaciones...</option>';
                        p2 = fetch(`/catalogos/generaciones?idCentroTrabajo=${gr.id_centroTrabajo}`)
                            .then(r => r.json())
                            .then(gens => {
                                selectGen.innerHTML = '<option value="">Pendiente de registro SEP</option>';
                                if (Array.isArray(gens)) {
                                    gens.forEach(g => {
                                        const opt = document.createElement('option');
                                        opt.value = g.id;
                                        opt.textContent = `Gen. ${g.generacion || ''} - ${g.nombreGeneracion}`;
                                        if (gr.idGeneracion && String(g.id) === String(gr.idGeneracion)) {
                                            opt.selected = true;
                                        }
                                        selectGen.appendChild(opt);
                                    });
                                }
                            });
                    }

                    Promise.all([p1, p2]).then(() => {
                        verificarEdadBGNE();
                        actualizarResumenYSemaforo();
                    });
                }
            }
        })
        .catch(err => {
            console.error('Error al cargar contexto de grupo:', err);
        });
}

function abrirModalAlumno() {
    modoAlumno = 'crear';
    idAlumnoActual = null;

    fetch('/alumnos/modalAlta?_t=' + Date.now())
        .then(res => res.text())
        .then(html => {
            document.getElementById('contenedorModal').innerHTML = html;
            setFormDisabled(false);

            if (typeof window.initModalAlumnoDinamico === 'function') {
                window.initModalAlumnoDinamico();
            }

            if (grupoId) {
                cargarContextoGrupo(grupoId);
            }

            let modal = new bootstrap.Modal(document.getElementById('modalAlumno'));
            modal.show();
        });
}

document.addEventListener("DOMContentLoaded", function() {

    let pagina = 1;
    let search = '';
    let generacion = '';

    if (grupoId) {
        const filterGen = document.getElementById('contenedorFiltroGeneracion');
        if (filterGen) filterGen.style.display = 'none';
        const searchInput = document.getElementById('buscadorAlumnos');
        if (searchInput) searchInput.style.display = 'none';
    }

    function cargarAlumnos() {
        document.getElementById('loading').style.display = 'block';

        let fetchUrl = grupoId 
            ? `/alumnos/grupo/${grupoId}`
            : `/alumnos/lista?page=${pagina}&limit=10&search=${encodeURIComponent(search)}&generacion=${generacion}`;

        if (!grupoId) {
            const cct = document.getElementById('filtroCCT') ? document.getElementById('filtroCCT').value : '';
            const status = document.getElementById('filtroStatus') ? document.getElementById('filtroStatus').value : '';
            const order = document.getElementById('filtroOrden') ? document.getElementById('filtroOrden').value : 'ASC';

            if (cct) fetchUrl += `&id_centro_trabajo=${cct}`;
            if (status) fetchUrl += `&status_alumno=${status}`;
            if (order) fetchUrl += `&order=${order}`;
        }

        fetch(fetchUrl)
            .then(res => res.json())
            .then(data => {
                let alumnos = data.data || [];

                if (!grupoId && generacion) {
                    alumnos = alumnos.filter(
                        alumno => alumno.nombreGeneracionTexto == generacion
                    );
                }

                renderTabla(alumnos);
                
                if (grupoId) {
                    document.getElementById('paginacionMaterias').innerHTML = '';
                    document.getElementById('infoPaginacionMaterias').innerText =
                        `Total alumnos en grupo: ${alumnos.length}`;
                } else {
                    renderPaginacion(data);
                }
            })
            .finally(() => {
                document.getElementById('loading').style.display = 'none';
            });
    }

    let timeout = null;
    if (document.getElementById('buscadorAlumnos')) {
        document.getElementById('buscadorAlumnos').addEventListener('input', (e) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                search = e.target.value;
                pagina = 1;
                cargarAlumnos();
            }, 400);
        });
    }

    if (document.getElementById('filtroBGNE')) {
        document.getElementById('filtroBGNE').addEventListener('change', (e) => {
            generacion = e.target.value;
            pagina = 1;
            cargarAlumnos();
        });
    }

    if (!grupoId) {
        if (document.getElementById('filtroCCT')) {
            document.getElementById('filtroCCT').addEventListener('change', resetAndCargarAlumnos);
        }
        if (document.getElementById('filtroStatus')) {
            document.getElementById('filtroStatus').addEventListener('change', resetAndCargarAlumnos);
        }
        if (document.getElementById('filtroOrden')) {
            document.getElementById('filtroOrden').addEventListener('change', resetAndCargarAlumnos);
        }
    }

    function resetAndCargarAlumnos() {
        pagina = 1;
        cargarAlumnos();
    }

    function renderTabla(alumnos) {
        if (!alumnos.length) {
            document.getElementById('tablaAlumnos').innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-muted">
                    No se encontraron alumnos
                </td>
            </tr>
        `;
            return;
        }

        let html = '';
        alumnos.forEach(alumno => {
            const fullName = `${alumno.nombre} ${alumno.apPaterno} ${alumno.apMaterno || ''}`;
            
            const groupBadge = alumno.nombreGrupoTexto 
                ? `<span class="badge" style="font-size: 0.75rem; padding: 5px 10px; border-radius: 12px; background-color: #0ea5e9; color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.15); font-weight: 700; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">${alumno.nombreGrupoTexto}</span>` 
                : '<span class="text-muted" style="font-size: 0.85rem;">—</span>';

            html += `
        <tr>
            <td>${alumno.idAlumno}</td>
            <td>${fullName}</td>
            <td>${groupBadge}</td>
            <td>Generación ${alumno.nombreGeneracionTexto || 'N/A'}</td>
            <td>${getStatusBadge(alumno.statusAlumno)}</td>
            <td class="text-center">
                ${canConsultAlumno ? `
                <button class="btn btn-info text-white btn-sm btn-action" onclick="abrirKardexAlumno(${alumno.idAlumno})" title="Ver Kárdex / Calificaciones">
                    <i class="fa-solid fa-graduation-cap"></i>
                </button>
                <button class="btn btn-secondary btn-sm btn-action" onclick="verAlumno(${alumno.idAlumno})" title="Ver detalles">
                    <i class="fa-solid fa-eye"></i>
                </button>
                ` : ''}
                ${canEditAlumno ? `
                <button class="btn btn-warning btn-sm btn-action" onclick="editarAlumno(${alumno.idAlumno})" title="Editar">
                    <i class="fa-solid fa-pen"></i>
                </button>
                ` : ''}
                ${canDeleteAlumno ? `
                <button class="btn btn-danger btn-sm btn-action btnEliminar" data-id="${alumno.idAlumno}" title="Eliminar">
                    <i class="fa-solid fa-trash"></i>
                </button>
                ` : ''}
            </td>
        </tr>
        `;
        });

        document.getElementById('tablaAlumnos').innerHTML = html;
    }

    document.addEventListener('click', async function(e) {
        const boton = e.target.closest('.btnEliminar');
        if (!boton) return;
        const id = boton.dataset.id;

        const confirmResult = await Swal.fire({
            title: '¿Deseas eliminar este alumno?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#cbd5e1',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (!confirmResult.isConfirmed) return;

        fetch(`/alumnos/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminado',
                        text: data.message,
                        confirmButtonColor: 'rgb(38, 104, 123)'
                    });
                    cargarAlumnos();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al eliminar',
                        confirmButtonColor: 'rgb(38, 104, 123)'
                    });
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al eliminar alumno',
                    confirmButtonColor: 'rgb(38, 104, 123)'
                });
            });
    });

    function renderPaginacion(data) {
        let html = '';
        html += `
        <button class="btn btn-sm btn-azul me-2"
            ${data.page === 1 ? 'disabled' : ''}
            onclick="cambiarPagina(${data.page - 1})">
            ⬅ Anterior
        </button>
    `;

        html += `
        <span class="mx-2">
            Página ${data.page} de ${data.total_pages}
        </span>
    `;

        html += `
        <button class="btn btn-sm btn-azul ms-2"
            ${data.page === data.total_pages ? 'disabled' : ''}
            onclick="cambiarPagina(${data.page + 1})">
            Siguiente ➡
        </button>
    `;

        document.getElementById('paginacionMaterias').innerHTML = html;
        document.getElementById('infoPaginacionMaterias').innerText =
            `Total alumnos: ${data.total}`;
    }

    window.cambiarPagina = function(p) {
        pagina = p;
        cargarAlumnos();
    }

    // INIT
    cargarAlumnos();

    document.addEventListener('submit', function(e) {
        if (e.target.id !== 'formAlumno') return;
        e.preventDefault();

        // VALIDACIÓN FRONTEND: Verificar campos requeridos nativos de HTML5
        if (!e.target.checkValidity()) {
            e.stopPropagation();
            e.target.classList.add('was-validated');

            // Encontrar el primer control inválido para enfocarlo
            const primerInvalido = e.target.querySelector(':invalid');
            if (primerInvalido) {
                // Si el control está dentro de un panel colapsado del acordeón, expandirlo
                const collapseParent = primerInvalido.closest('.accordion-collapse');
                if (collapseParent && !collapseParent.classList.contains('show')) {
                    const toggleBtn = document.querySelector(`[data-bs-target="#${collapseParent.id}"]`);
                    if (toggleBtn) {
                        toggleBtn.click();
                    }
                }
                setTimeout(() => {
                    primerInvalido.focus();
                }, 200);
            }

            Swal.fire({
                icon: 'warning',
                title: 'Campos requeridos faltantes',
                text: 'Por favor complete todos los campos obligatorios marcados con asterisco (*).',
                confirmButtonColor: 'rgb(38, 104, 123)'
            });
            return;
        }

        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());

        // Procesar flag de boleta parcial en observaciones
        let obsValue = data.observaciones || '';
        if (data.traeBoleta === 'SI') {
            obsValue = `[BOLETA_PARCIAL] ${obsValue}`.trim();
        }

        // Recoger cursos seleccionados
        const cursos = [];
        e.target.querySelectorAll('input[name="cursos[]"]:checked').forEach(cb => {
            cursos.push(parseInt(cb.value));
        });

        // Construir payload estructurado
        const payload = {
            ...data,
            alumno: {
                nombre: data.nombre || null,
                apPaterno: data.apPaterno || null,
                apMaterno: data.apMaterno || null,
                fechaNacimiento: data.fechaNacimiento || null,
                celularAlumno: data.celularAlumno || null,
                correoAlumno: data.correoAlumno || null,
                tutor: data.tutor || null,
                parentesco: data.parentesco || null,
                telefonoTutor: data.telefonoTutor || null,
                calle: data.calle || null,
                colonia: data.colonia || null,
                localidad: data.localidad || null,
                municipio: data.municipio || null,
                escuelaProcedencia: data.escuelaProcedencia || null,
                observaciones: obsValue || null,
                numeroControl: data.numeroControl || null,
                statusAlumno: data.statusAlumno || 'ACTIVO',
                curp: data.curp || null,
                folioCertificado: data.folioCertificado || null,
                fechaRecogioCertificado: data.fechaRecogioCertificado || null,
                recogioCertificado: data.recogioCertificado || 'NO'
            },
            academico: {
                idCentroTrabajo: data.id_centroTrabajo ? parseInt(data.id_centroTrabajo) : null,
                idNivelAcademico: data.id_nivel_academico ? parseInt(data.id_nivel_academico) : null,
                idGeneracion: data.id_Generacion ? parseInt(data.id_Generacion) : null,
                idGrupo: data.id_Grupo ? parseInt(data.id_Grupo) : null
            },
            equivalencia: {
                requiereEquivalencia: data.equivalencia === 'SI',
                cuentaConCertificadoIncompleto: data.certificadoIncompleto === 'SI',
                fechaEntrega: data.fechaEntregaCertificado || data.fechaEntregaDocumentos || null,
                estadoPago: data.estadoPagoEquivalencia || 'PENDIENTE'
            },
            cursos: cursos,
            materiasPendientes: []
        };

        // Recoger materias pendientes si existen
        const nombresMat = e.target.querySelectorAll('input[name="materiasPendientesNombre[]"]');
        const periodosMat = e.target.querySelectorAll('input[name="materiasPendientesPeriodo[]"]');
        const califsMat = e.target.querySelectorAll('input[name="materiasPendientesCalif[]"]');
        nombresMat.forEach((inputNom, idx) => {
            if (inputNom.value && inputNom.value.trim()) {
                payload.materiasPendientes.push({
                    materia: inputNom.value.trim(),
                    periodo: periodosMat[idx]?.value || null,
                    calificacion: califsMat[idx]?.value || null
                });
            }
        });

        const url = modoAlumno === 'editar' ? `/alumnos/${idAlumnoActual}` : '/alumnos';
        const method = modoAlumno === 'editar' ? 'PUT' : 'POST';

        fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Completado',
                        text: data.message,
                        confirmButtonColor: 'rgb(38, 104, 123)'
                    });

                    bootstrap.Modal.getInstance(document.getElementById('modalAlumno')).hide();
                    cargarAlumnos();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al guardar',
                        confirmButtonColor: 'rgb(38, 104, 123)'
                    });
                }
            })
            .catch(error => {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al guardar alumno',
                    confirmButtonColor: 'rgb(38, 104, 123)'
                });
            });
    });

});

let idAlumnoKardexActual = null;

window.abrirKardexAlumno = function(idAlumno) {
    idAlumnoKardexActual = idAlumno;
    
    // Primero, mostramos un loading rápido para consultar la información general del alumno y su CCT
    Swal.fire({
        title: 'Cargando información...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(`/alumnos/${idAlumno}/kardex`)
        .then(r => r.json())
        .then(resp => {
            Swal.close();
            if (!resp.success || !resp.data) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo cargar la información del alumno.'
                });
                return;
            }

            const data = resp.data;
            const al = data.alumno;
            const isBti = (al.id_centroTrabajo === 2 || al.claveCentroTrabajo === '21PCT0073R' || (al.nombreCentroTrabajo && al.nombreCentroTrabajo.toUpperCase().includes('BTI')));

            if (isBti) {
                // Preguntar si quiere Kárdex o Boleta
                Swal.fire({
                    title: 'Seleccione Tipo de Documento',
                    text: '¿Desea consultar el Kárdex general o imprimir una Boleta de calificaciones de un semestre específico?',
                    icon: 'question',
                    showCancelButton: true,
                    showDenyButton: true,
                    confirmButtonColor: 'rgb(38, 104, 123)',
                    denyButtonColor: '#0ea5e9',
                    cancelButtonColor: '#cbd5e1',
                    confirmButtonText: 'Kárdex General',
                    denyButtonText: 'Boleta de Semestre',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Cargar Kárdex normal
                        mostrarKardexConDatos(data);
                    } else if (result.isDenied) {
                        // Preguntar qué semestre (1 al 6)
                        Swal.fire({
                            title: 'Seleccionar Semestre',
                            text: 'Seleccione el semestre (del 1 al 6) para generar la boleta:',
                            input: 'select',
                            inputOptions: {
                                '7': '1° Semestre',
                                '8': '2° Semestre',
                                '9': '3° Semestre',
                                '10': '4° Semestre',
                                '11': '5° Semestre',
                                '12': '6° Semestre'
                            },
                            inputPlaceholder: 'Seleccione semestre...',
                            showCancelButton: true,
                            confirmButtonColor: 'rgb(38, 104, 123)',
                            cancelButtonColor: '#cbd5e1',
                            confirmButtonText: 'Generar Boleta',
                            cancelButtonText: 'Cancelar',
                            inputValidator: (value) => {
                                if (!value) {
                                     return 'Debe seleccionar un semestre';
                                }
                            }
                        }).then((semResult) => {
                            if (semResult.isConfirmed) {
                                const idNivelSemestre = parseInt(semResult.value); // 7 a 12
                                imprimirBoletaBTISemestre(data, idNivelSemestre);
                            }
                        });
                    }
                });
            } else {
                // No es BTI (es BGNE u otro), cargar Kárdex normal directamente
                mostrarKardexConDatos(data);
            }
        })
        .catch(err => {
            Swal.close();
            console.error('Error al cargar datos del alumno:', err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un error al comunicarse con el servidor.'
            });
        });
};

function mostrarKardexConDatos(data) {
    const modalEl = document.getElementById('modalKardexAlumno');
    if (modalEl && modalEl.parentElement !== document.body) {
        document.body.appendChild(modalEl);
    }
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();

    const contenedor = document.getElementById('contenedorPeriodosKardex');
    const al = data.alumno;
    const periodos = data.periodos || [];

    const isBti = (al.id_centroTrabajo === 2 || al.claveCentroTrabajo === '21PCT0073R' || (al.nombreCentroTrabajo && al.nombreCentroTrabajo.toUpperCase().includes('BTI')));

    // Datos de encabezado
    document.getElementById('kardexNombreAlumno').textContent = `${al.apPaterno || ''} ${al.apMaterno || ''} ${al.nombre || ''}`.trim().toUpperCase();
    if (al.claveCentroTrabajo) {
        document.getElementById('kardexCCTClave').textContent = `CLAVE CT: ${al.claveCentroTrabajo}`;
    }
    if (al.nombreCentroTrabajo) {
        document.getElementById('kardexCCTNombre').textContent = al.nombreCentroTrabajo.toUpperCase();
    }

    // Renderizar las 6 cajas de periodos
    let htmlPeriodos = '';
    periodos.forEach((p, idx) => {
        let htmlMaterias = '';
        
        (p.materias || []).forEach(m => {
            const isEquiv = m.es_equivalencia === true;
            if (isEquiv) {
                if (isBti) {
                    htmlMaterias += `
                        <tr data-materia-id="${m.idMateria}" data-is-equivalencia="true">
                            <td class="px-2 py-1 align-middle text-uppercase fw-semibold" style="font-size: 0.78rem; border-color: #cbd5e1;">
                                ${m.nombreMateria}
                            </td>
                            <td colspan="7" class="text-center px-1 py-1 text-warning fw-bold align-middle" style="font-size: 0.85rem; border-color: #cbd5e1;">
                                EQUIVALENCIA
                            </td>
                        </tr>
                    `;
                } else {
                    htmlMaterias += `
                        <tr data-materia-id="${m.idMateria}" data-nivel="${p.idNivel}" data-is-equivalencia="true">
                            <td class="px-2 py-1 align-middle text-uppercase fw-semibold" style="font-size: 0.78rem; border-color: #cbd5e1;">
                                ${m.nombreMateria}
                            </td>
                            <td class="px-1 py-1 text-center align-middle" style="width: 85px; border-color: #cbd5e1;">
                                <span class="badge bg-warning text-dark px-2 py-1">EQUIV.</span>
                            </td>
                        </tr>
                    `;
                }
            } else {
                const califVal = m.calificacion !== null ? m.calificacion : '';
                if (isBti) {
                    const p1 = m.parcial1 !== null ? m.parcial1 : '';
                    const p2 = m.parcial2 !== null ? m.parcial2 : '';
                    const p3 = m.parcial3 !== null ? m.parcial3 : '';
                    const sem = m.semestral !== null ? m.semestral : '';
                    const ext = m.extraordinario !== null ? m.extraordinario : '';
                    const asist = m.asistencias !== null ? m.asistencias : '';
                    const totAsist = m.total_asistencias !== null ? m.total_asistencias : '';

                    htmlMaterias += `
                        <tr data-materia-id="${m.idMateria}" data-nivel="${p.idNivel}">
                            <td class="px-2 py-1 align-middle text-uppercase fw-semibold" style="font-size: 0.78rem; border-color: #000;">
                                ${m.nombreMateria}
                            </td>
                            <td class="px-1 py-1 align-middle text-center" style="border-color: #000 !important;">
                                <input type="number" step="0.1" class="form-control form-control-sm text-center fw-bold input-calif-kardex inp-p1" data-materia="${m.idMateria}" data-nivel="${p.idNivel}" data-periodo-idx="${idx}" data-field="parcial1" value="${p1}" style="height: 28px; font-size: 0.82rem; padding: 2px;" oninput="recalcularFilaKardexSemestral(this)">
                            </td>
                            <td class="px-1 py-1 align-middle text-center" style="border-color: #000 !important;">
                                <input type="number" step="0.1" class="form-control form-control-sm text-center fw-bold input-calif-kardex inp-p2" data-materia="${m.idMateria}" data-nivel="${p.idNivel}" data-periodo-idx="${idx}" data-field="parcial2" value="${p2}" style="height: 28px; font-size: 0.82rem; padding: 2px;" oninput="recalcularFilaKardexSemestral(this)">
                            </td>
                            <td class="px-1 py-1 align-middle text-center" style="border-color: #000 !important;">
                                <input type="number" step="0.1" class="form-control form-control-sm text-center fw-bold input-calif-kardex inp-p3" data-materia="${m.idMateria}" data-nivel="${p.idNivel}" data-periodo-idx="${idx}" data-field="parcial3" value="${p3}" style="height: 28px; font-size: 0.82rem; padding: 2px;" oninput="recalcularFilaKardexSemestral(this)">
                            </td>
                            <td class="px-1 py-1 align-middle text-center" style="border-color: #000 !important;">
                                <input type="number" step="0.1" class="form-control form-control-sm text-center fw-bold input-calif-kardex inp-semestral" data-materia="${m.idMateria}" data-nivel="${p.idNivel}" data-periodo-idx="${idx}" data-field="semestral" value="${sem}" style="height: 28px; font-size: 0.82rem; padding: 2px;" oninput="recalcularFilaKardexSemestral(this)">
                            </td>
                            <td class="px-1 py-1 align-middle text-center" style="border-color: #000 !important;">
                                <input type="number" step="0.1" class="form-control form-control-sm text-center fw-bold input-calif-kardex inp-extraordinario" data-materia="${m.idMateria}" data-nivel="${p.idNivel}" data-periodo-idx="${idx}" data-field="extraordinario" value="${ext}" style="height: 28px; font-size: 0.82rem; padding: 2px;" oninput="recalcularFilaKardexSemestral(this)">
                            </td>
                            <td class="px-1 py-1 align-middle text-center" style="border-color: #000 !important;">
                                <input type="number" step="0.1" class="form-control form-control-sm text-center fw-bold input-calif-kardex inp-calif-final" data-materia="${m.idMateria}" data-nivel="${p.idNivel}" data-periodo-idx="${idx}" data-field="calificacion" value="${califVal}" readonly style="height: 28px; font-size: 0.82rem; padding: 2px; background-color: #f1f5f9;">
                            </td>
                            <td class="px-1 py-1 align-middle text-center" style="border-color: #000 !important;">
                                <div class="d-flex align-items-center gap-1 justify-content-center">
                                    <input type="number" class="form-control form-control-sm text-center fw-bold input-calif-kardex inp-asistencias" data-materia="${m.idMateria}" data-nivel="${p.idNivel}" data-periodo-idx="${idx}" data-field="asistencias" value="${asist}" style="height: 28px; font-size: 0.80rem; padding: 2px; width: 28px;" oninput="calcularPromediosKardex()">
                                    <span style="font-size: 0.7rem;">/</span>
                                    <input type="number" class="form-control form-control-sm text-center fw-bold input-calif-kardex inp-total-asistencias" data-materia="${m.idMateria}" data-nivel="${p.idNivel}" data-periodo-idx="${idx}" data-field="total_asistencias" value="${totAsist}" style="height: 28px; font-size: 0.80rem; padding: 2px; width: 28px;" oninput="calcularPromediosKardex()">
                                </div>
                            </td>
                        </tr>
                    `;
                } else {
                    htmlMaterias += `
                        <tr data-materia-id="${m.idMateria}" data-nivel="${p.idNivel}">
                            <td class="px-2 py-1 align-middle text-uppercase fw-semibold" style="font-size: 0.78rem; border-color: #cbd5e1;">
                                ${m.nombreMateria}
                            </td>
                            <td class="px-1 py-1 text-center align-middle" style="width: 85px; border-color: #cbd5e1;">
                                <input type="text" maxlength="4" 
                                    class="form-control form-control-sm text-center fw-bold input-calif-kardex" 
                                    data-materia="${m.idMateria}" 
                                    data-nivel="${p.idNivel}" 
                                    data-periodo-idx="${idx}" 
                                    data-field="calificacion" 
                                    value="${califVal}" 
                                    style="height: 28px; font-size: 0.85rem; padding: 2px; background: transparent; border: 1px solid #cbd5e1;"
                                    oninput="this.value = this.value.toUpperCase(); calcularPromediosKardex()">
                            </td>
                        </tr>
                    `;
                }
            }
        });

        const promInicial = p.promedio !== null ? p.promedio : '—';

        let footerHtml = '';
        if (isBti) {
            footerHtml = `
                <tfoot>
                    <tr class="bg-light fw-bold" style="border-color: #333; font-size: 0.70rem;">
                        <td class="text-end px-2 py-1 text-uppercase">PROMEDIO</td>
                        <td class="text-center px-1 py-1 prom-p1-val" id="promP1_${idx}">—</td>
                        <td class="text-center px-1 py-1 prom-p2-val" id="promP2_${idx}">—</td>
                        <td class="text-center px-1 py-1 prom-p3-val" id="promP3_${idx}">—</td>
                        <td class="text-center px-1 py-1 prom-sem-val" id="promSem_${idx}">—</td>
                        <td class="text-center px-1 py-1 prom-ext-val" id="promExt_${idx}">—</td>
                        <td class="text-center px-1 py-1 text-primary fw-bold prom-periodo-val" id="promPeriodo_${idx}">${promInicial}</td>
                        <td class="text-center px-1 py-1" style="font-size: 0.65rem;" id="totalAsistPeriodo_${idx}">—</td>
                    </tr>
            `;
        } else {
            footerHtml = `
                <tfoot>
                    <tr class="bg-light fw-bold" style="border-color: #333; font-size: 0.78rem;">
                        <td class="text-end px-2 py-1 text-uppercase">PROMEDIO</td>
                        <td class="text-center px-1 py-1 text-primary fw-bold prom-periodo-val" id="promPeriodo_${idx}">${promInicial}</td>
                    </tr>
            `;
        }

        if (idx === 4) { // 5to periodo
            footerHtml += `
                <tr class="fw-bold" style="border-color: #333; font-size: 0.8rem; background: #e2e8f0;">
                    <td class="${isBti ? 'text-end' : 'text-end'} px-2 py-1 text-uppercase" colspan="${isBti ? '6' : '1'}">PROMEDIO FINAL</td>
                    <td class="text-center px-1 py-1 fw-bold text-primary" id="kardexPromedioFinal">0.0</td>
                    ${isBti ? '<td style="border: none !important;"></td>' : ''}
                </tr>
            `;
        } else if (idx === 5) { // 6to periodo (balance visual)
            footerHtml += `
                <tr style="border-color: transparent; height: 26px;">
                    <td colspan="${isBti ? '8' : '2'}" style="border: none !important; background: transparent;"></td>
                </tr>
            `;
        }
        footerHtml += `</tfoot>`;

        htmlPeriodos += `
            <div class="col-6" style="width: 50%;">
                <div class="border rounded-1 shadow-none overflow-hidden bg-white h-100" style="border-color: #000 !important;">
                    <div class="py-1 px-2 fw-bold text-dark text-uppercase bg-light border-bottom" style="font-size: 0.78rem; letter-spacing: 0.5px; border-color: #000 !important;">
                        ${p.nombrePeriodo}
                    </div>
                    <div class="table-responsive mb-0">
                        <table class="table table-bordered table-sm mb-0" style="border-color: #000 !important;">
                            <thead class="table-light">
                                <tr style="font-size: 0.70rem; border-color: #000;">
                                    ${isBti ? `
                                    <th class="px-2 py-1 text-uppercase text-dark" style="border-color: #000 !important;">MATERIA</th>
                                    <th class="px-1 py-1 text-center text-uppercase text-dark" style="width: 42px; border-color: #000 !important;">P1</th>
                                    <th class="px-1 py-1 text-center text-uppercase text-dark" style="width: 42px; border-color: #000 !important;">P2</th>
                                    <th class="px-1 py-1 text-center text-uppercase text-dark" style="width: 42px; border-color: #000 !important;">P3</th>
                                    <th class="px-1 py-1 text-center text-uppercase text-dark" style="width: 42px; border-color: #000 !important;">SEM</th>
                                    <th class="px-1 py-1 text-center text-uppercase text-dark" style="width: 42px; border-color: #000 !important;">EXT</th>
                                    <th class="px-1 py-1 text-center text-uppercase text-dark" style="width: 48px; border-color: #000 !important;">FINAL</th>
                                    <th class="px-1 py-1 text-center text-uppercase text-dark" style="width: 66px; border-color: #000 !important;">ASIST.</th>
                                    ` : `
                                    <th class="px-2 py-1 text-uppercase text-dark" style="border-color: #000 !important;">MATERIA</th>
                                    <th class="px-1 py-1 text-center text-uppercase text-dark" style="width: 80px; border-color: #000 !important;">EVALUACIÓN OBTENIDA</th>
                                    `}
                                </tr>
                            </thead>
                            <tbody>
                                ${htmlMaterias}
                            </tbody>
                            ${footerHtml}
                        </table>
                    </div>
                </div>
            </div>
        `;
    });

    contenedor.innerHTML = htmlPeriodos;
    calcularPromediosKardex();
}

function imprimirBoletaBTISemestre(data, idNivelSemestre) {
    const al = data.alumno;
    const periodos = data.periodos || [];
    const p = periodos.find(x => x.idNivel === idNivelSemestre);
    const materias = p ? p.materias || [] : [];

    const semestresNombres = {
        7: 'PRIMER',
        8: 'SEGUNDO',
        9: 'TERCER',
        10: 'CUARTO',
        11: 'QUINTO',
        12: 'SEXTO'
    };
    const semestreNombreLargo = semestresNombres[idNivelSemestre] || 'SEMESTRE';

    let filasMateriasHtml = '';
    let p1Vals = []; let p2Vals = []; let p3Vals = []; let semVals = []; let extVals = []; let finalVals = [];
    let totalAsist = 0; let totalTotAsist = 0;

    materias.forEach(m => {
        const isEquiv = m.es_equivalencia === true;
        if (isEquiv) {
            filasMateriasHtml += `
                <tr style="text-align: center; height: 32px;">
                    <td style="border: 1px solid #000; padding: 6px; text-align: left; text-transform: uppercase; font-weight: bold; font-size: 8.5pt;">${m.nombreMateria}</td>
                    <td colspan="5" style="border: 1px solid #000; padding: 6px; font-weight: bold; color: #d97706; font-size: 8.5pt;">EQUIVALENCIA</td>
                    <td style="border: 1px solid #000; padding: 6px; font-weight: bold; font-size: 8.5pt;">—</td>
                </tr>
            `;
            return;
        }

        const p1 = m.parcial1 !== null ? parseFloat(m.parcial1) : null;
        const p2 = m.parcial2 !== null ? parseFloat(m.parcial2) : null;
        const p3 = m.parcial3 !== null ? parseFloat(m.parcial3) : null;
        const sem = m.semestral !== null ? parseFloat(m.semestral) : null;
        const ext = m.extraordinario !== null ? parseFloat(m.extraordinario) : null;
        const finalVal = m.calificacion !== null ? parseFloat(m.calificacion) : null;

        const parseVal = (v, arr) => { if (v !== null) arr.push(v); };
        parseVal(p1, p1Vals);
        parseVal(p2, p2Vals);
        parseVal(p3, p3Vals);
        parseVal(sem, semVals);
        parseVal(ext, extVals);
        parseVal(finalVal, finalVals);

        if (m.asistencias !== null) totalAsist += parseInt(m.asistencias) || 0;
        if (m.total_asistencias !== null) totalTotAsist += parseInt(m.total_asistencias) || 0;

        const p1Text = p1 !== null ? p1.toFixed(1) : '—';
        const p2Text = p2 !== null ? p2.toFixed(1) : '—';
        const p3Text = p3 !== null ? p3.toFixed(1) : '—';
        const semText = sem !== null ? sem.toFixed(1) : '—';
        const extText = ext !== null ? ext.toFixed(1) : '0.0';
        
        const extStyle = ext !== null && ext > 0 ? 'color: #dc2626; font-weight: bold;' : 'color: #000;';
        const finalStyle = finalVal !== null && finalVal < 6.0 ? 'color: #dc2626; font-weight: bold;' : 'color: #000;';
        const finalText = finalVal !== null ? finalVal.toFixed(1) : '—';

        filasMateriasHtml += `
            <tr style="text-align: center; height: 32px;">
                <td style="border: 1px solid #000; padding: 6px 8px; text-align: left; text-transform: uppercase; font-weight: 700; font-size: 8.5pt;">${m.nombreMateria}</td>
                <td style="border: 1px solid #000; padding: 6px; font-size: 9pt;">${p1Text}</td>
                <td style="border: 1px solid #000; padding: 6px; font-size: 9pt;">${p2Text}</td>
                <td style="border: 1px solid #000; padding: 6px; font-size: 9pt;">${p3Text}</td>
                <td style="border: 1px solid #000; padding: 6px; font-size: 9pt;">${semText}</td>
                <td style="border: 1px solid #000; padding: 6px; font-size: 9pt; ${extStyle}">${extText}</td>
                <td style="border: 1px solid #000; padding: 6px; font-size: 9pt; background: #f8fafc; ${finalStyle}">${finalText}</td>
            </tr>
        `;
    });

    const getAvg = (arr) => arr.length > 0 ? (arr.reduce((a, b) => a + b, 0) / arr.length).toFixed(1) : '—';

    const p1Avg = getAvg(p1Vals);
    const p2Avg = getAvg(p2Vals);
    const p3Avg = getAvg(p3Vals);
    const semAvg = getAvg(semVals);
    const extAvg = getAvg(extVals);
    const finalAvg = getAvg(finalVals);

    const totalAsistVal = totalAsist;
    const totalTotAsistVal = totalTotAsist;

    const win = window.open('', '', 'height=850,width=1100');
    win.document.write(`
        <html>
            <head>
                <title>Boleta de Calificaciones - ${semestreNombreLargo} Semestre</title>
                <style>
                    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; box-sizing: border-box; }
                    @page {
                        size: letter portrait;
                        margin: 10mm 12mm;
                    }
                    html, body {
                        font-family: Arial, Helvetica, sans-serif;
                        background: #fff;
                        color: #000;
                        padding: 0;
                        margin: 0;
                    }
                    .boleta-container {
                        width: 100%;
                        max-width: 800px;
                        margin: 0 auto;
                    }
                </style>
            </head>
            <body>
                <div class="boleta-container">
                    
                    <!-- Header -->
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #0f172a; padding-bottom: 12px; margin-bottom: 20px;">
                        <div style="width: 100px; text-align: left;">
                            <img src="/img/BTI_ICON.png" style="height: 60px; width: auto; object-fit: contain;">
                        </div>
                        <div style="text-align: center; flex: 1;">
                            <div style="font-size: 10pt; font-weight: bold; color: #000; letter-spacing: 0.5px; text-transform: uppercase;">Dirección General de Educación Tecnológica Industrial y de Servicios</div>
                            <div style="font-size: 9pt; font-weight: 700; color: #334155; margin-top: 3px; text-transform: uppercase;">Educación Media Superior</div>
                            <div style="font-size: 11pt; font-weight: 800; color: #0f172a; margin-top: 6px; text-transform: uppercase;">BOLETA DE CALIFICACIONES DEL ${semestreNombreLargo} SEMESTRE</div>
                            <div style="font-size: 8.5pt; font-weight: bold; color: #475569; margin-top: 3px; text-transform: uppercase;">Ciclo Escolar 2025-2026</div>
                        </div>
                        <div style="width: 100px; text-align: right;">
                            <img src="/img/logo.png" style="height: 60px; width: auto; object-fit: contain;">
                        </div>
                    </div>

                    <!-- Details Row 1 -->
                    <div style="display: flex; gap: 30px; font-size: 8.5pt; margin-bottom: 12px;">
                        <div style="flex: 2; display: flex; flex-direction: column;">
                            <div style="display: flex; align-items: flex-end; margin-bottom: 2px;">
                                <span style="font-weight: bold; color: #475569; width: 150px; text-transform: uppercase;">DATOS DEL ALUMNO (A):</span>
                                <span style="flex: 1; border-bottom: 1.2px solid #000; font-weight: 700; font-size: 9.5pt; text-align: center; padding-bottom: 2px; text-transform: uppercase; letter-spacing: 0.5px;">
                                    ${al.apPaterno || ''} ${al.apMaterno || ''} ${al.nombre || ''}
                                </span>
                            </div>
                            <div style="display: flex; justify-content: space-around; font-size: 7.2pt; color: #64748b; padding-left: 150px; margin-top: 1px;">
                                <span>Apellido Paterno</span>
                                <span>Apellido Materno</span>
                                <span>Nombre</span>
                            </div>
                        </div>
                    </div>

                    <!-- Details Row 2 -->
                    <div style="display: flex; gap: 20px; font-size: 8.5pt; margin-bottom: 20px; align-items: flex-end;">
                        <div style="flex: 2; display: flex; align-items: flex-end;">
                            <span style="font-weight: bold; color: #475569; width: 150px; text-transform: uppercase;">DATOS DE LA ESCUELA:</span>
                            <span style="flex: 1; border-bottom: 1.2px solid #000; font-weight: 700; text-align: center; padding-bottom: 2px; text-transform: uppercase; font-size: 9pt;">
                                ${al.nombreCentroTrabajo || 'BACHILLERATO TECNOLÓGICO INTERAMERICANO'}
                            </span>
                        </div>
                        <div style="display: flex; gap: 15px; font-size: 8pt; text-align: center;">
                            <div style="display: flex; flex-direction: column; width: 60px;">
                                <span style="font-weight: 700; border-bottom: 1px solid #000; padding-bottom: 2px; text-transform: uppercase;">${al.nombreGrupoTexto || '—'}</span>
                                <span style="font-size: 7pt; color: #475569; font-weight: bold; margin-top: 2px;">GRUPO</span>
                            </div>
                            <div style="display: flex; flex-direction: column; width: 90px;">
                                <span style="font-weight: 700; border-bottom: 1px solid #000; padding-bottom: 2px; text-transform: uppercase;">${al.modalidadHorario || 'MATUTINO'}</span>
                                <span style="font-size: 7pt; color: #475569; font-weight: bold; margin-top: 2px;">TURNO</span>
                            </div>
                            <div style="display: flex; flex-direction: column; width: 90px;">
                                <span style="font-weight: 700; border-bottom: 1px solid #000; padding-bottom: 2px; text-transform: uppercase;">${al.claveCentroTrabajo || '21PCT0073R'}</span>
                                <span style="font-size: 7pt; color: #475569; font-weight: bold; margin-top: 2px;">CCT</span>
                            </div>
                        </div>
                    </div>

                    <!-- Grades Table and Info blocks -->
                    <div style="display: flex; gap: 20px; align-items: flex-start; justify-content: space-between; margin-bottom: 25px;">
                        
                        <!-- Main Grades Table -->
                        <div style="flex: 1;">
                            <table style="width: 100%; border-collapse: collapse; border: 1.5px solid #000; font-size: 8pt;">
                                <thead>
                                    <tr style="background: #e2e8f0; color: #000; text-align: center; border-bottom: 1.5px solid #000;">
                                        <th rowspan="2" style="border: 1px solid #000; padding: 6px; text-align: left; width: 220px; font-size: 7.8pt; text-transform: uppercase; font-weight: 800;">ASIGNATURAS / ÁREAS</th>
                                        <th colspan="4" style="border: 1px solid #000; padding: 4px; font-size: 7.8pt; font-weight: 800;">PERIODOS DE EVALUACIÓN ORDINARIA</th>
                                        <th rowspan="2" style="border: 1px solid #000; padding: 6px; width: 50px; font-size: 7.2pt; font-weight: 800; border-left: 1.5px solid #000;">EXTRAORDINARIO</th>
                                        <th rowspan="2" style="border: 1px solid #000; padding: 6px; width: 65px; font-size: 7.2pt; font-weight: 800; border-left: 1.5px solid #000;">PROMEDIO FINAL/<br>MATERIA</th>
                                    </tr>
                                    <tr style="background: #f8fafc; color: #000; text-align: center; font-size: 7.2pt; border-bottom: 1.5px solid #000;">
                                        <th style="border: 1px solid #000; padding: 4px; width: 45px;">1ER. PARCIAL</th>
                                        <th style="border: 1px solid #000; padding: 4px; width: 45px;">2DO. PARCIAL</th>
                                        <th style="border: 1px solid #000; padding: 4px; width: 45px;">3ER. PARCIAL</th>
                                        <th style="border: 1px solid #000; padding: 4px; width: 55px;">SEMESTRAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${filasMateriasHtml}
                                    <tr style="font-weight: bold; background: #e2e8f0; text-align: center; font-size: 8pt; border-top: 1.5px solid #000; height: 32px;">
                                        <td style="border: 1px solid #000; padding: 6px; text-align: right; text-transform: uppercase; font-weight: 800;">PROMEDIO</td>
                                        <td style="border: 1px solid #000; padding: 5px;">${p1Avg}</td>
                                        <td style="border: 1px solid #000; padding: 5px;">${p2Avg}</td>
                                        <td style="border: 1px solid #000; padding: 5px;">${p3Avg}</td>
                                        <td style="border: 1px solid #000; padding: 5px;">${semAvg}</td>
                                        <td style="border: 1px solid #000; padding: 5px; color: #dc2626; border-left: 1.5px solid #000;">${extAvg}</td>
                                        <td style="border: 1px solid #000; padding: 5px; background: #cbd5e1; color: #1e293b; border-left: 1.5px solid #000;">${finalAvg}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Sidebar details -->
                        <div style="width: 250px; display: flex; flex-direction: column; gap: 25px; align-items: center;">
                            
                            <!-- Badges -->
                            <div style="display: flex; gap: 15px; width: 100%; justify-content: center;">
                                <div style="border: 1.5px solid #000; background: #ffffff; width: 110px; border-radius: 4px; overflow: hidden; display: flex; flex-direction: column; align-items: center; text-align: center;">
                                    <span style="font-size: 6.8pt; font-weight: 800; background: #2e596b; color: #ffffff; width: 100%; padding: 4px 0; text-transform: uppercase; display: block;">ASISTENCIAS</span>
                                    <span style="font-size: 11pt; font-weight: 800; padding: 12px 5px; color: #0f172a; display: block;">${totalAsistVal} / ${totalTotAsistVal}</span>
                                </div>
                                <div style="border: 1.5px solid #000; background: #ffffff; width: 110px; border-radius: 4px; overflow: hidden; display: flex; flex-direction: column; align-items: center; text-align: center;">
                                    <span style="font-size: 6.8pt; font-weight: 800; background: #2e596b; color: #ffffff; width: 100%; padding: 4px 0; text-transform: uppercase; display: block; line-height: 1.1;">PROMEDIO FINAL<br>DEL SEMESTRE</span>
                                    <span style="font-size: 13pt; font-weight: 900; padding: 10px 5px; color: #1e3a8a; display: block;">${finalAvg}</span>
                                </div>
                            </div>

                            <!-- Director Signature -->
                            <div style="margin-top: 15px; width: 100%; text-align: center;">
                                <div style="border-bottom: 1px solid #000; width: 160px; margin: 0 auto 5px auto; height: 45px;"></div>
                                <div style="font-size: 7.8pt; font-weight: 800; text-transform: uppercase; color: #0f172a;">ING. FAUSTO LEYVA FLORES</div>
                                <div style="font-size: 7.2pt; font-weight: 700; color: #475569; text-transform: uppercase; margin-top: 1px;">DIRECTOR</div>
                            </div>

                            <!-- Date info -->
                            <div style="font-size: 7.5pt; font-weight: bold; color: #1e293b; margin-top: 10px; text-transform: uppercase; text-align: center; border: 1px dashed #cbd5e1; padding: 4px 8px; border-radius: 4px;">
                                TEZIUTLÁN PUEBLA A 14 DE AGOSTO DE 2026
                            </div>

                        </div>
                    </div>

                    <!-- Recommendations Table -->
                    <div style="margin-top: 25px;">
                        <table style="width: 100%; border-collapse: collapse; border: 1.5px solid #000; font-size: 8pt;">
                            <thead>
                                <tr style="background: #2e596b; color: #ffffff; font-weight: 800; text-transform: uppercase; text-align: center;">
                                    <th style="border: 1px solid #000; padding: 6px; font-size: 7.8pt; letter-spacing: 0.5px;">OBSERVACIONES O RECOMENDACIONES DE LA DOCENTE O DEL DOCENTE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="border: 1px solid #000; padding: 0;">
                                        <div style="display: flex; align-items: center; border-bottom: 1px solid #cbd5e1; min-height: 28px;">
                                            <span style="font-size: 6.8pt; font-weight: bold; background: #e2e8f0; color: #334155; width: 110px; text-align: center; padding: 6px 0; border-right: 1px solid #cbd5e1; text-transform: uppercase; flex-shrink: 0;">PRIMER PARCIAL</span>
                                            <div style="flex: 1; padding: 0 10px;"></div>
                                        </div>
                                        <div style="display: flex; align-items: center; border-bottom: 1px solid #cbd5e1; min-height: 28px;">
                                            <span style="font-size: 6.8pt; font-weight: bold; background: #e2e8f0; color: #334155; width: 110px; text-align: center; padding: 6px 0; border-right: 1px solid #cbd5e1; text-transform: uppercase; flex-shrink: 0;">SEGUNDO PARCIAL</span>
                                            <div style="flex: 1; padding: 0 10px;"></div>
                                        </div>
                                        <div style="display: flex; align-items: center; border-bottom: 1px solid #cbd5e1; min-height: 28px;">
                                            <span style="font-size: 6.8pt; font-weight: bold; background: #e2e8f0; color: #334155; width: 110px; text-align: center; padding: 6px 0; border-right: 1px solid #cbd5e1; text-transform: uppercase; flex-shrink: 0;">TERCER PARCIAL</span>
                                            <div style="flex: 1; padding: 0 10px;"></div>
                                        </div>
                                        <div style="display: flex; align-items: center; min-height: 28px;">
                                            <span style="font-size: 6.8pt; font-weight: bold; background: #e2e8f0; color: #334155; width: 110px; text-align: center; padding: 6px 0; border-right: 1px solid #cbd5e1; text-transform: uppercase; flex-shrink: 0;">SEMESTRAL</span>
                                            <div style="flex: 1; padding: 0 10px;"></div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(function() { window.close(); }, 500);
                    };
                <\/script>
            </body>
        </html>
    `);
}

window.recalcularFilaKardexSemestral = function(inputEl) {
    const tr = inputEl.closest('tr');
    if (!tr) return;

    const p1Inp = tr.querySelector('.inp-p1');
    const p2Inp = tr.querySelector('.inp-p2');
    const p3Inp = tr.querySelector('.inp-p3');
    const semInp = tr.querySelector('.inp-semestral');
    const extInp = tr.querySelector('.inp-extraordinario');
    const pFinalInp = tr.querySelector('.inp-calif-final');

    const v1 = parseFloat(p1Inp.value);
    const v2 = parseFloat(p2Inp.value);
    const v3 = parseFloat(p3Inp.value);

    // 1. Verificar si los 3 parciales están llenos
    const partialsFilled = !isNaN(v1) && !isNaN(v2) && !isNaN(v3);

    if (partialsFilled) {
        const sumPartials = v1 + v2 + v3;
        if (sumPartials < 18) {
            // No tiene derecho a semestral. Va directamente a extraordinario
            semInp.value = "";
            semInp.disabled = true;
            semInp.placeholder = "N/A";
            
            extInp.disabled = false;
            extInp.placeholder = "0.0";
            
            const extVal = parseFloat(extInp.value);
            if (!isNaN(extVal)) {
                pFinalInp.value = Math.min(extVal, 7.0).toFixed(1);
            } else {
                pFinalInp.value = "";
            }
        } else {
            semInp.disabled = false;
            semInp.placeholder = "0.0";
            
            extInp.value = "";
            extInp.disabled = true;
            extInp.placeholder = "N/A";

            const semVal = parseFloat(semInp.value);
            if (!isNaN(semVal)) {
                pFinalInp.value = ((v1 + v2 + v3 + semVal) / 4).toFixed(1);
            } else {
                pFinalInp.value = "";
            }
        }
    } else {
        semInp.disabled = false;
        extInp.disabled = false;
        
        let vals = [];
        if (!isNaN(v1)) vals.push(v1);
        if (!isNaN(v2)) vals.push(v2);
        if (!isNaN(v3)) vals.push(v3);
        
        if (vals.length > 0) {
            pFinalInp.value = (vals.reduce((a, b) => a + b, 0) / vals.length).toFixed(1);
        } else {
            pFinalInp.value = "";
        }
    }

    // Color rojo
    const checkRed = (inp) => {
        if (!inp) return;
        const v = parseFloat(inp.value);
        if (!isNaN(v) && v < 6.0) {
            inp.style.color = '#dc2626';
        } else {
            inp.style.color = '#1e293b';
        }
    };
    checkRed(p1Inp);
    checkRed(p2Inp);
    checkRed(p3Inp);
    checkRed(semInp);
    checkRed(extInp);
    checkRed(pFinalInp);

    calcularPromediosKardex();
};

window.calcularPromediosKardex = function() {
    const inputs = document.querySelectorAll('.input-calif-kardex');
    const isBti = document.getElementById('kardexCCTNombre')?.textContent.includes('BTI') || document.getElementById('kardexCCTClave')?.textContent.includes('21PCT0073R') || (document.querySelector('.prom-p1-val') !== null);

    const p1Sums = {}; const p1Counts = {};
    const p2Sums = {}; const p2Counts = {};
    const p3Sums = {}; const p3Counts = {};
    const semSums = {}; const semCounts = {};
    const extSums = {}; const extCounts = {};
    const periodosSums = {}; const periodosCounts = {};
    const asistSums = {}; const totAsistSums = {};

    let sumGlobal = 0;
    let countGlobal = 0;

    inputs.forEach(inp => {
        const pIdx = inp.dataset.periodoIdx;
        const field = inp.dataset.field || 'calificacion';
        const valStr = inp.value.trim();

        if (!periodosSums[pIdx]) {
            p1Sums[pIdx] = 0; p1Counts[pIdx] = 0;
            p2Sums[pIdx] = 0; p2Counts[pIdx] = 0;
            p3Sums[pIdx] = 0; p3Counts[pIdx] = 0;
            semSums[pIdx] = 0; semCounts[pIdx] = 0;
            extSums[pIdx] = 0; extCounts[pIdx] = 0;
            periodosSums[pIdx] = 0; periodosCounts[pIdx] = 0;
            asistSums[pIdx] = 0; totAsistSums[pIdx] = 0;
        }

        if (valStr !== '' && !isNaN(valStr)) {
            const num = parseFloat(valStr);
            if (field === 'parcial1') { p1Sums[pIdx] += num; p1Counts[pIdx]++; }
            else if (field === 'parcial2') { p2Sums[pIdx] += num; p2Counts[pIdx]++; }
            else if (field === 'parcial3') { p3Sums[pIdx] += num; p3Counts[pIdx]++; }
            else if (field === 'semestral') { semSums[pIdx] += num; semCounts[pIdx]++; }
            else if (field === 'extraordinario') { extSums[pIdx] += num; extCounts[pIdx]++; }
            else if (field === 'calificacion') { 
                periodosSums[pIdx] += num; 
                periodosCounts[pIdx]++; 
                sumGlobal += num;
                countGlobal++;
            }
            else if (field === 'asistencias') { asistSums[pIdx] += num; }
            else if (field === 'total_asistencias') { totAsistSums[pIdx] += num; }

            if (field !== 'asistencias' && field !== 'total_asistencias') {
                if (num < 6.0) {
                    inp.style.setProperty('color', '#dc2626', 'important');
                    inp.style.setProperty('font-weight', '700', 'important');
                } else {
                    inp.style.setProperty('color', '#1e293b', 'important');
                    inp.style.setProperty('font-weight', '700', 'important');
                }
            }
        } else if (valStr.toUpperCase() === 'EQUIV.' || valStr.toUpperCase() === 'EQUIVALENCIA') {
            inp.style.setProperty('color', '#d97706', 'important');
            inp.style.setProperty('font-weight', '700', 'important');
        } else {
            inp.style.setProperty('color', '#1e293b', 'important');
            inp.style.setProperty('font-weight', 'normal', 'important');
        }
    });

    // Actualizar promedios por periodo
    Object.keys(periodosSums).forEach(pIdx => {
        const updateAvgEl = (id, sum, count) => {
            const el = document.getElementById(id);
            if (el) {
                if (count > 0) {
                    const avg = (sum / count).toFixed(1);
                    el.textContent = avg;
                    el.style.setProperty('color', parseFloat(avg) < 6.0 ? '#dc2626' : '#1e6fa8', 'important');
                } else {
                    el.textContent = '—';
                    el.style.setProperty('color', '#64748b', 'important');
                }
            }
        };

        if (isBti) {
            updateAvgEl(`promP1_${pIdx}`, p1Sums[pIdx], p1Counts[pIdx]);
            updateAvgEl(`promP2_${pIdx}`, p2Sums[pIdx], p2Counts[pIdx]);
            updateAvgEl(`promP3_${pIdx}`, p3Sums[pIdx], p3Counts[pIdx]);
            updateAvgEl(`promSem_${pIdx}`, semSums[pIdx], semCounts[pIdx]);
            updateAvgEl(`promExt_${pIdx}`, extSums[pIdx], extCounts[pIdx]);
            
            const asistEl = document.getElementById(`totalAsistPeriodo_${pIdx}`);
            if (asistEl) {
                asistEl.textContent = `${asistSums[pIdx]} / ${totAsistSums[pIdx]}`;
            }
        }
        
        updateAvgEl(`promPeriodo_${pIdx}`, periodosSums[pIdx], periodosCounts[pIdx]);
    });

    // Actualizar promedio final
    const finalEl = document.getElementById('kardexPromedioFinal');
    if (finalEl) {
        if (countGlobal > 0) {
            const promFinal = (sumGlobal / countGlobal).toFixed(1);
            finalEl.textContent = promFinal;
            finalEl.style.setProperty('color', parseFloat(promFinal) < 6.0 ? '#dc2626' : '#1e6fa8', 'important');
        } else {
            finalEl.textContent = '0.0';
            finalEl.style.setProperty('color', '#1e6fa8', 'important');
        }
    }
};

window.guardarCalificacionesKardex = function() {
    if (!idAlumnoKardexActual) return;
    const btn = document.getElementById('btnGuardarKardex');
    const rows = document.querySelectorAll('#contenedorPeriodosKardex tbody tr');
    const calificaciones = [];

    rows.forEach(tr => {
        const isEquiv = tr.getAttribute('data-is-equivalencia') === 'true';
        if (isEquiv) return;

        const idMateria = tr.getAttribute('data-materia-id');
        const idNivel = tr.getAttribute('data-nivel');
        if (!idMateria) return;

        const p1Inp = tr.querySelector('.inp-p1');
        const p2Inp = tr.querySelector('.inp-p2');
        const p3Inp = tr.querySelector('.inp-p3');
        const semInp = tr.querySelector('.inp-semestral');
        const extInp = tr.querySelector('.inp-extraordinario');
        const finalInp = tr.querySelector('.inp-calif-final') || tr.querySelector('.input-calif-kardex');

        if (!finalInp) return;

        const valStr = finalInp.value.trim();
        let calif = null;
        if (valStr !== '' && !isNaN(valStr)) {
            calif = parseFloat(valStr);
        }

        const dataObj = {
            idMateria: parseInt(idMateria),
            id_nivel_academico: parseInt(idNivel || finalInp.dataset.nivel),
            calificacion: calif,
            tipoAcreditacion: 'ORDINARIO'
        };

        if (p1Inp) dataObj.parcial1 = p1Inp.value !== "" ? parseFloat(p1Inp.value) : null;
        if (p2Inp) dataObj.parcial2 = p2Inp.value !== "" ? parseFloat(p2Inp.value) : null;
        if (p3Inp) dataObj.parcial3 = p3Inp.value !== "" ? parseFloat(p3Inp.value) : null;
        if (semInp) dataObj.semestral = semInp.value !== "" ? parseFloat(semInp.value) : null;
        if (extInp) dataObj.extraordinario = extInp.value !== "" ? parseFloat(extInp.value) : null;
        
        const asistInp = tr.querySelector('.inp-asistencias');
        const totAsistInp = tr.querySelector('.inp-total-asistencias');
        if (asistInp) dataObj.asistencias = asistInp.value !== "" ? parseInt(asistInp.value) : null;
        if (totAsistInp) dataObj.total_asistencias = totAsistInp.value !== "" ? parseInt(totAsistInp.value) : null;

        calificaciones.push(dataObj);
    });

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Guardando...';

    fetch(`/alumnos/${idAlumnoKardexActual}/calificaciones`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ calificaciones: calificaciones })
    })
    .then(r => r.json())
    .then(resp => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> Guardar Calificaciones';

        if (resp.success) {
            Swal.fire({
                icon: 'success',
                title: 'Kárdex Actualizado',
                text: 'Las calificaciones se han guardado exitosamente.',
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: resp.error || resp.message || 'Error al guardar calificaciones'
            });
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> Guardar Calificaciones';
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de comunicación al guardar calificaciones'
        });
    });
};

window.imprimirKardex = function() {
    const isBti = document.getElementById('kardexCCTNombre')?.textContent.includes('BTI') || document.getElementById('kardexCCTClave')?.textContent.includes('21PCT0073R') || (document.querySelector('.prom-p1-val') !== null);

    const alumnoNombre = document.getElementById('kardexNombreAlumno')?.textContent || '—';
    const cctClave = document.getElementById('kardexCCTClave')?.textContent || 'CLAVE CT: 21PBH0353G';
    const cctNombre = document.getElementById('kardexCCTNombre')?.textContent || 'BACHILLERATO GENERAL NO ESCOLARIZADO';
    const promFinal = document.getElementById('kardexPromedioFinal')?.textContent || '—';

    // Extraer datos de los 6 periodos
    const periodosData = [];
    const periodosDom = document.querySelectorAll('#contenedorPeriodosKardex > div');

    periodosDom.forEach((pDom, idx) => {
        const title = pDom.querySelector('.fw-bold.text-dark')?.textContent.trim() || `PERIODO ${idx + 1}`;
        const promVal = document.getElementById(`promPeriodo_${idx}`)?.textContent.trim() || '—';
        const rows = [];
        const trs = pDom.querySelectorAll('tbody tr');

        trs.forEach(tr => {
            const matName = tr.querySelector('td:first-child')?.textContent.trim() || '';
            const isEquiv = tr.getAttribute('data-is-equivalencia') === 'true';
            
            const p1Inp = tr.querySelector('.inp-p1');
            const p2Inp = tr.querySelector('.inp-p2');
            const p3Inp = tr.querySelector('.inp-p3');
            const semInp = tr.querySelector('.inp-semestral');
            const extInp = tr.querySelector('.inp-extraordinario');
            const finalInp = tr.querySelector('.inp-calif-final') || tr.querySelector('input.input-calif-kardex');
            const asistInp = tr.querySelector('.inp-asistencias');
            const totAsistInp = tr.querySelector('.inp-total-asistencias');

            const p1 = p1Inp ? p1Inp.value.trim() : '';
            const p2 = p2Inp ? p2Inp.value.trim() : '';
            const p3 = p3Inp ? p3Inp.value.trim() : '';
            const sem = semInp ? semInp.value.trim() : '';
            const ext = extInp ? extInp.value.trim() : '';
            const finalVal = finalInp ? finalInp.value.trim() : '';
            const asist = asistInp ? asistInp.value.trim() : '';
            const totAsist = totAsistInp ? totAsistInp.value.trim() : '';

            rows.push({
                materia: matName,
                isEquiv: isEquiv,
                p1: p1 !== '' ? p1 : '—',
                p2: p2 !== '' ? p2 : '—',
                p3: p3 !== '' ? p3 : '—',
                semestral: sem !== '' ? sem : '—',
                extraordinario: ext !== '' ? ext : '—',
                calificacion: finalVal !== '' ? finalVal : '—',
                asistencias: asist !== '' ? asist : '0',
                total_asistencias: totAsist !== '' ? totAsist : '0',
                esReprobatoria: finalVal !== '' && !isNaN(finalVal) && parseFloat(finalVal) < 6.0
            });
        });

        periodosData.push({
            titulo: title,
            promedio: promVal,
            promReprobatorio: promVal !== '—' && !isNaN(promVal) && parseFloat(promVal) < 6.0,
            materias: rows
        });
    });

    function renderTablaPeriodo(p, is5to, is6to) {
        if (!p) return '';
        let filasHtml = '';
        
        if (isBti) {
            p.materias.forEach(m => {
                if (m.isEquiv) {
                    filasHtml += `
                        <tr>
                            <td style="border: 1px solid #000; padding: 2.2px 5px; font-size: 7.5pt; text-align: left; text-transform: uppercase;">
                                ${m.materia}
                            </td>
                            <td colspan="7" style="border: 1px solid #000; padding: 2.2px 5px; font-size: 8pt; text-align: center; font-weight: bold; color: #d97706;">
                                EQUIVALENCIA
                            </td>
                        </tr>
                    `;
                } else {
                    const styleColor = m.esReprobatoria ? 'color: #dc2626 !important; font-weight: bold;' : 'color: #000;';
                    filasHtml += `
                        <tr>
                            <td style="border: 1px solid #000; padding: 2.2px 5px; font-size: 7.5pt; text-align: left; text-transform: uppercase;">
                                ${m.materia}
                            </td>
                            <td style="border: 1px solid #000; padding: 2.2px 5px; font-size: 8pt; text-align: center; color: #000;">${m.p1}</td>
                            <td style="border: 1px solid #000; padding: 2.2px 5px; font-size: 8pt; text-align: center; color: #000;">${m.p2}</td>
                            <td style="border: 1px solid #000; padding: 2.2px 5px; font-size: 8pt; text-align: center; color: #000;">${m.p3}</td>
                            <td style="border: 1px solid #000; padding: 2.2px 5px; font-size: 8pt; text-align: center; color: #000;">${m.semestral}</td>
                            <td style="border: 1px solid #000; padding: 2.2px 5px; font-size: 8pt; text-align: center; color: #000;">${m.extraordinario}</td>
                            <td style="border: 1px solid #000; padding: 2.2px 5px; font-size: 8pt; text-align: center; background-color: #f1f5f9; ${styleColor}">${m.calificacion}</td>
                            <td style="border: 1px solid #000; padding: 2.2px 5px; font-size: 7.5pt; text-align: center; color: #000;">${m.asistencias} / ${m.total_asistencias}</td>
                        </tr>
                    `;
                }
            });

            // Calculate columns averages
            let p1Vals = []; let p2Vals = []; let p3Vals = []; let semVals = []; let extVals = []; let finalVals = [];
            let totalAsist = 0; let totalTotAsist = 0;

            p.materias.forEach(m => {
                if (m.isEquiv) return;
                const parseVal = (v, arr) => { if (v !== '—' && !isNaN(v)) arr.push(parseFloat(v)); };
                parseVal(m.p1, p1Vals);
                parseVal(m.p2, p2Vals);
                parseVal(m.p3, p3Vals);
                parseVal(m.semestral, semVals);
                parseVal(m.extraordinario, extVals);
                parseVal(m.calificacion, finalVals);
                
                if (m.asistencias !== '—') totalAsist += parseInt(m.asistencias) || 0;
                if (m.total_asistencias !== '—') totalTotAsist += parseInt(m.total_asistencias) || 0;
            });

            const getAvg = (arr) => arr.length > 0 ? (arr.reduce((a, b) => a + b, 0) / arr.length).toFixed(1) : '—';
            const avgFinal = getAvg(finalVals);

            let footerExtra = '';
            if (is5to) {
                footerExtra = `
                    <tr style="font-weight: bold; background: #e8ecf2;">
                        <td style="border: 1.5px solid #000; padding: 3px 5px; font-size: 8.2pt; text-align: right;">PROMEDIO FINAL DEL SEMESTRE</td>
                        <td colspan="7" style="border: 1.5px solid #000; padding: 3px 5px; font-size: 9pt; text-align: center; color: #1e6fa8;">${avgFinal}</td>
                    </tr>
                `;
            } else if (is6to) {
                footerExtra = `
                    <tr>
                        <td colspan="8" style="border: 1px solid #000; height: 21px; background: #f8fafc;"></td>
                    </tr>
                `;
            }

            return `
                <div style="margin-bottom: 8px;">
                    <div style="font-size: 8.5pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; color: #000;">
                        ${p.titulo}
                    </div>
                    <table style="width: 100%; border-collapse: collapse; border: 1.2px solid #000; font-family: Arial, sans-serif;">
                        <thead>
                            <tr style="background: #e2e8f0; font-size: 6.8pt; font-weight: bold; text-align: center; color: #000;">
                                <th style="border: 1px solid #000; padding: 3px; text-align: left; width: 180px;">ASIGNATURAS / ÁREAS</th>
                                <th style="border: 1px solid #000; padding: 3px; width: 32px;">1ER. PAR.</th>
                                <th style="border: 1px solid #000; padding: 3px; width: 32px;">2DO. PAR.</th>
                                <th style="border: 1px solid #000; padding: 3px; width: 32px;">3ER. PAR.</th>
                                <th style="border: 1px solid #000; padding: 3px; width: 32px;">SEM.</th>
                                <th style="border: 1px solid #000; padding: 3px; width: 32px;">EXT.</th>
                                <th style="border: 1px solid #000; padding: 3px; width: 45px;">FINAL</th>
                                <th style="border: 1px solid #000; padding: 3px; width: 60px;">ASIST.</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${filasHtml}
                            <tr style="font-weight: bold; background: #f8fafc;">
                                <td style="border: 1px solid #000; padding: 3.5px 5px; font-size: 8pt; text-align: right;">PROMEDIO:</td>
                                <td style="border: 1px solid #000; padding: 3px; text-align: center;">${getAvg(p1Vals)}</td>
                                <td style="border: 1px solid #000; padding: 3px; text-align: center;">${getAvg(p2Vals)}</td>
                                <td style="border: 1px solid #000; padding: 3px; text-align: center;">${getAvg(p3Vals)}</td>
                                <td style="border: 1px solid #000; padding: 3px; text-align: center;">${getAvg(semVals)}</td>
                                <td style="border: 1px solid #000; padding: 3px; text-align: center;">${getAvg(extVals)}</td>
                                <td style="border: 1px solid #000; padding: 3.5px 5px; font-size: 8pt; text-align: center; color: #1e6fa8; background: #e2e8f0;">${avgFinal}</td>
                                <td style="border: 1px solid #000; padding: 3px; text-align: center; font-size: 7.2pt;">${totalAsist} / ${totalTotAsist}</td>
                            </tr>
                            ${footerExtra}
                        </tbody>
                    </table>
                </div>
            `;
        } else {
            p.materias.forEach(m => {
                const styleColor = m.esReprobatoria ? 'color: #dc2626 !important; font-weight: bold;' : 'color: #000;';
                filasHtml += `
                    <tr>
                        <td style="border: 1px solid #000; padding: 2.2px 5px; font-size: 8pt; text-align: left; text-transform: uppercase;">
                            ${m.materia}
                        </td>
                        <td style="border: 1px solid #000; padding: 2.2px 5px; font-size: 8.5pt; text-align: center; font-weight: bold; width: 75px; ${styleColor}">
                            ${m.calificacion}
                        </td>
                    </tr>
                `;
            });

            let footerExtra = '';
            if (is5to) {
                const finalColor = promFinal !== '—' && !isNaN(promFinal) && parseFloat(promFinal) < 6.0 ? 'color: #dc2626 !important;' : 'color: #000;';
                footerExtra = `
                    <tr>
                        <td style="border: 1.5px solid #000; padding: 2.5px 5px; font-size: 8.2pt; font-weight: bold; text-align: right; background: #e8ecf2;">
                            PROMEDIO FINAL
                        </td>
                        <td style="border: 1.5px solid #000; padding: 2.5px 5px; font-size: 8.8pt; font-weight: bold; text-align: center; background: #e8ecf2; ${finalColor}">
                            ${promFinal}
                        </td>
                    </tr>
                `;
            } else if (is6to) {
                footerExtra = `
                    <tr>
                        <td colspan="2" style="border: 1px solid #000; height: 21px; background: #f8fafc;"></td>
                    </tr>
                `;
            }

            const promColor = p.promReprobatorio ? 'color: #dc2626 !important;' : 'color: #000;';

            return `
                <div style="margin-bottom: 8px;">
                    <div style="font-size: 8.2pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; color: #000;">
                        ${p.titulo}
                    </div>
                    <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; font-family: Arial, sans-serif;">
                        <thead>
                            <tr style="background: #ffffff;">
                                <th style="border: 1px solid #000; padding: 2.5px 5px; font-size: 7.8pt; text-align: left; font-weight: bold; width: 275px;">MATERIA</th>
                                <th style="border: 1px solid #000; padding: 2.5px 2px; font-size: 7.2pt; text-align: center; font-weight: bold; width: 75px; line-height: 1.1;">EVALUACIÓN<br>OBTENIDA</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${filasHtml}
                            <tr>
                                <td style="border: 1px solid #000; padding: 2.2px 5px; font-size: 8pt; font-weight: bold; text-align: right;">
                                    PROMEDIO
                                </td>
                                <td style="border: 1px solid #000; padding: 2.2px 5px; font-size: 8.5pt; font-weight: bold; text-align: center; ${promColor}">
                                    ${p.promedio}
                                </td>
                            </tr>
                            ${footerExtra}
                        </tbody>
                    </table>
                </div>
            `;
        }
    }

    const pageContainerWidth = isBti ? '920px' : '720px';
    const pageColWidth = isBti ? '450px' : '350px';
    const pageSize = isBti ? 'letter landscape' : 'letter portrait';

    const colIzqHtml = `
        <div style="width: ${pageColWidth}; flex-shrink: 0;">
            ${renderTablaPeriodo(periodosData[0], false, false)}
            ${renderTablaPeriodo(periodosData[2], false, false)}
            ${renderTablaPeriodo(periodosData[4], true, false)}
        </div>
    `;

    const colDerHtml = `
        <div style="width: ${pageColWidth}; flex-shrink: 0;">
            ${renderTablaPeriodo(periodosData[1], false, false)}
            ${renderTablaPeriodo(periodosData[3], false, false)}
            ${renderTablaPeriodo(periodosData[5], false, true)}
        </div>
    `;

    let signaturesHtml = '';
    if (isBti) {
        signaturesHtml = `
            <div style="margin-top: 25px; display: flex; justify-content: space-between; align-items: flex-end; width: 920px; font-family: Arial, sans-serif;">
                <div style="width: 380px; text-align: center;">
                    <div style="border-bottom: 1px solid #000; width: 240px; margin: 0 auto 5px auto; height: 45px;"></div>
                    <div style="font-size: 8pt; font-weight: bold;">ING. FAUSTO LEYVA FLORES</div>
                    <div style="font-size: 7.5pt; color: #444; text-transform: uppercase;">DIRECTOR</div>
                </div>
                <div style="width: 380px; text-align: center; font-size: 8.5pt; font-weight: bold; padding-bottom: 15px;">
                    TEZIUTLÁN PUEBLA A 14 DE AGOSTO DE 2026
                </div>
            </div>
        `;
    }

    const win = window.open('', '', 'height=850,width=1100');
    win.document.write(`
        <html>
            <head>
                <title>Kárdex de Calificaciones</title>
                <style>
                    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; box-sizing: border-box; }
                    @page {
                        size: ${pageSize};
                        margin: 6mm 8mm 4mm 8mm;
                    }
                    html, body {
                        font-family: Arial, Helvetica, sans-serif;
                        background: #fff;
                        color: #000;
                        padding: 0;
                        margin: 0;
                        height: 100%;
                    }
                    .kardex-hoja {
                        width: ${pageContainerWidth};
                        margin: 0 auto;
                        text-align: center;
                    }
                </style>
            </head>
            <body>
                <div class="kardex-hoja">
                    <!-- MEMBRETE OFICIAL -->
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <div style="width: 80px; text-align: left;">
                            <img src="/img/logo.png" alt="Logo" style="height: 60px; object-fit: contain;">
                        </div>
                        <div style="width: ${isBti ? '820px' : '630px'}; border: 1.5px solid #10599a; overflow: hidden; border-radius: 3px;">
                            <div style="background: #10599a; color: #ffffff; font-weight: bold; font-size: 11.5pt; padding: 3px 6px; text-align: center; letter-spacing: 0.8px;">
                                BACHILLERATO INTERAMERICANO
                            </div>
                            <div style="background: #d4ebf9; color: #000000; font-size: 7.2pt; padding: 2px 6px; text-align: center; line-height: 1.3;">
                                <div>Avenida Benito Juárez 901, Colonia Centro Teziutlán Puebla. Tel: 231-3123979</div>
                                <div style="font-weight: bold;">${cctClave}</div>
                            </div>
                        </div>
                    </div>

                    <!-- TEXTO DIRECCIÓN Y NOMBRE ALUMNO -->
                    <div style="margin-bottom: 6px;">
                        <div style="font-size: 8.5pt; color: #000;">
                            La Dirección de la escuela <strong>${cctNombre}</strong>
                        </div>
                        <div style="font-size: 7.8pt; color: #333; font-style: italic;">
                            Reporta las siguientes calificaciones obtenidas hasta el momento del alumno(a):
                        </div>
                        <div style="display: inline-block; background: #e8ecf2; border: 1.5px solid #1e293b; border-radius: 20px; padding: 2.5px 30px; margin: 4px 0 8px 0; font-size: 11.5pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">
                            ${alumnoNombre}
                        </div>
                    </div>

                    <!-- TABLAS DE LOS 6 PERIODOS (2 COLUMNAS) -->
                    <div style="display: flex; justify-content: space-between; width: ${pageContainerWidth}; margin: 0 auto; text-align: left;">
                        ${colIzqHtml}
                        ${colDerHtml}
                    </div>

                    ${signaturesHtml}

                    <!-- LEMA INFERIOR -->
                    <div style="width: ${pageContainerWidth}; margin: 6px auto 0 auto; background: #5b9bd5; color: #ffffff; font-weight: bold; font-size: 8.2pt; padding: 3px 0; text-align: center; border-radius: 2px;">
                        ¡ Excelencia educativa a su servicio !
                    </div>
                </div>
            </body>
        </html>
    `);
    win.document.close();
    win.focus();
    setTimeout(() => {
        win.print();
        win.close();
    }, 400);
};

window.imprimirFichaInscripcion = function() {
    const form = document.getElementById('formAlumno');
    if (!form) return;

    const nom = form.querySelector('[name="nombre"]')?.value.trim() || '';
    const pat = form.querySelector('[name="apPaterno"]')?.value.trim() || '';
    const mat = form.querySelector('[name="apMaterno"]')?.value.trim() || '';
    const alumnoNombre = `${nom} ${pat} ${mat}`.trim().toUpperCase() || '—';

    const sumCCT = form.querySelector('#sumCCT')?.textContent || '—';
    const sumGrupo = form.querySelector('#sumGrupo')?.textContent || 'Sin grupo';
    const sumPeriodo = form.querySelector('#sumPeriodo')?.textContent || '—';
    const sumGeneracion = form.querySelector('#sumGeneracion')?.textContent || 'Pendiente SEP';
    const sumDiaJornada = form.querySelector('#sumDiaJornada')?.textContent || '—';
    const sumEstadoSEP = form.querySelector('#sumEstadoSEP')?.textContent || 'PENDIENTE';
    const sumAsistePresencialTexto = form.querySelector('#sumAsistePresencialTexto')?.textContent || 'Regular';
    const sumDetalleTrayectoria = form.querySelector('#sumDetalleTrayectoria')?.textContent || 'Mismo CCT Administrativo';
    const sumEquivalencia = form.querySelector('#sumEquivalencia')?.textContent || 'No requiere';
    const sumMateriasPend = form.querySelector('#sumMateriasPend')?.textContent || '0';
    const listaPendientes = form.querySelector('#listaPendientesControlEscolar')?.innerHTML || 'Sin observaciones.';

    const win = window.open('', '', 'height=850,width=850');
    win.document.write(`
        <html>
            <head>
                <title>Ficha de Inscripción y Control Escolar</title>
                <style>
                    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; box-sizing: border-box; }
                    @page {
                        size: letter portrait;
                        margin: 15mm;
                    }
                    body {
                        font-family: Arial, Helvetica, sans-serif;
                        color: #000;
                        background: #fff;
                        padding: 0;
                        margin: 0;
                    }
                    .header-container {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        margin-bottom: 15px;
                        border-bottom: 2px solid #1e6fa8;
                        padding-bottom: 10px;
                    }
                    .title-doc {
                        font-size: 14pt;
                        font-weight: bold;
                        color: #1e6fa8;
                        text-transform: uppercase;
                        margin: 0;
                    }
                    .subtitle-doc {
                        font-size: 9pt;
                        color: #666;
                        margin: 2px 0 0 0;
                    }
                    .table-ficha {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 15px;
                    }
                    .table-ficha th, .table-ficha td {
                        border: 1px solid #cbd5e1;
                        padding: 8px 12px;
                        font-size: 9pt;
                    }
                    .table-ficha th {
                        background-color: #f1f5f9;
                        color: #334155;
                        text-align: left;
                        font-weight: bold;
                        text-transform: uppercase;
                    }
                    .section-header {
                        background-color: #e2e8f0 !important;
                        font-weight: bold;
                        color: #000 !important;
                    }
                    .label-col {
                        color: #475569;
                        width: 25%;
                        font-weight: bold;
                    }
                    .val-col {
                        color: #0f172a;
                        width: 25%;
                    }
                    .obs-box {
                        border: 1px solid #cbd5e1;
                        padding: 12px;
                        background-color: #fafafb;
                        font-size: 9pt;
                        border-radius: 4px;
                        line-height: 1.5;
                    }
                    .footer-doc {
                        margin-top: 30px;
                        font-size: 8pt;
                        color: #666;
                        text-align: center;
                        border-top: 1px dashed #cbd5e1;
                        padding-top: 10px;
                    }
                </style>
            </head>
            <body>
                <div class="header-container">
                    <div>
                        <h1 class="title-doc">Ficha de Inscripción y Control Escolar</h1>
                        <p class="subtitle-doc">Resumen administrativo del expediente del alumno</p>
                    </div>
                    <img src="/img/logo.png" alt="Logo" style="height: 50px; object-fit: contain;">
                </div>

                <table class="table-ficha">
                    <tbody>
                        <tr class="section-header">
                            <th colspan="4">Datos del Alumno</th>
                        </tr>
                        <tr>
                            <td class="label-col">Nombre Completo:</td>
                            <td colspan="3" class="val-col" style="font-size: 11pt; font-weight: bold; text-transform: uppercase;">
                                \${alumnoNombre}
                            </td>
                        </tr>
                        
                        <tr class="section-header">
                            <th colspan="4">1. Programa Académico y Registro SEP</th>
                        </tr>
                        <tr>
                            <td class="label-col">CCT Administrativo (SEP):</td>
                            <td class="val-col">\${sumCCT}</td>
                            <td class="label-col">Grupo Oficial SEP:</td>
                            <td class="val-col">\${sumGrupo}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Periodo / Nivel:</td>
                            <td class="val-col">\${sumPeriodo}</td>
                            <td class="label-col">Generación SEP:</td>
                            <td class="val-col">\${sumGeneracion}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Turno / Asistencia:</td>
                            <td class="val-col">\${sumDiaJornada}</td>
                            <td class="label-col">Estatus Registro SEP:</td>
                            <td class="val-col">\${sumEstadoSEP}</td>
                        </tr>

                        <tr class="section-header">
                            <th colspan="4">2. Modalidad de Atención y Plantel de Clases</th>
                        </tr>
                        <tr>
                            <td class="label-col">Modalidad de Asistencia:</td>
                            <td class="val-col">\${sumAsistePresencialTexto}</td>
                            <td class="label-col">Plantel y Grupo Presencial:</td>
                            <td class="val-col">\${sumDetalleTrayectoria}</td>
                        </tr>

                        <tr class="section-header">
                            <th colspan="4">3. Trámites y Regularidad Académica</th>
                        </tr>
                        <tr>
                            <td class="label-col">Trámite de Equivalencia:</td>
                            <td class="val-col">\${sumEquivalencia}</td>
                            <td class="label-col">Materias Pendientes:</td>
                            <td class="val-col">\${sumMateriasPend}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="font-weight: bold; font-size: 9.5pt; color: #334155; margin-bottom: 6px; text-transform: uppercase;">
                    4. Observaciones y Pendientes de Control Escolar
                </div>
                <div class="obs-box">
                    \${listaPendientes}
                </div>

                <div class="footer-doc">
                    Documento informativo generado el \${new Date().toLocaleDateString('es-MX')} a las \${new Date().toLocaleTimeString('es-MX')}.<br>
                    Bachillerato Interamericano - Excelencia educativa a su servicio.
                </div>
            </body>
        </html>
    `);
    win.document.close();
    win.focus();
    setTimeout(() => {
        win.print();
        win.close();
    }, 400);
};
</script>