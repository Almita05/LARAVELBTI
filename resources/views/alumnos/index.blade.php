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
        </h3>

        <button class="btn btn-azul" onclick="abrirModalAlumno()">
            <i class="fa-solid fa-plus me-2"></i>
            Alta alumno
        </button>
    </div>

    <div class="glass-card">

        <div class="glass-header p-3 d-flex justify-content-between align-items-center">

            <h5 class="mb-0" id="tituloListaAlumnos">
                @if(isset($grupoId))
                    Alumnos del Grupo: <strong>{{ $grupoClave }}</strong>
                @else
                    Lista de alumnos
                @endif
            </h5>

            <div class="input-group w-25" id="contenedorFiltroGeneracion">
                <select id="filtroBGNE" class="form-select glass-input">
                    <option value="" class="valueGeneraciones">Todas las generaciones</option>
                    @foreach($generaciones as $generacion)
                    <option value="{{ $generacion['generacion'] }}" class="valueGeneraciones">
                        Generación {{ $generacion['generacion'] }} - {{ $generacion['nombreGeneracion'] }}
                    </option>
                    @endforeach
                </select>
                <button class="btn filtro-btn" type="button">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </div>

            <!-- Búsqueda por texto -->
            <input type="text" id="buscadorAlumnos" class="form-control glass-input w-25"
                placeholder="Buscar alumnos...">

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

    const ctNombre = (selectCCT && selectCCT.selectedIndex >= 0 ? selectCCT.options[selectCCT.selectedIndex].textContent : '').toUpperCase();
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
    const diaTexto = selectDia && selectDia.value ? selectDia.options[selectDia.selectedIndex].text.split('(')[0].trim() : '—';
    const jornadaTexto = selectJornada && selectJornada.value ? selectJornada.options[selectJornada.selectedIndex].text : '—';
    if (sumDiaJornada) {
        sumDiaJornada.textContent = (diaTexto !== '—' || jornadaTexto !== '—') ? `${diaTexto} (${jornadaTexto})` : '—';
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
        sumAsistePresencialTexto.textContent = esPresencial ? 'SÍ (Trayectoria Cruzada)' : 'Regular (Asistencia directa)';
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
        
        if (requiereEquiv && pagoEquiv === 'PAGADO') {
            pendientes.push('<div>• <strong>Trámite de Equivalencia:</strong> Pago recibido. Pendiente gestionar dictamen ante la autoridad educativa.</div>');
        } else if (requiereEquiv && pagoEquiv === 'PENDIENTE') {
            pendientes.push('<div>• <strong>Trámite de Equivalencia:</strong> Pendiente de pago y recepción de documentación de procedencia.</div>');
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
                pendientes.push(`<div>• <strong>Alta oficial ante SEP (BGNE):</strong> Alumno menor a 16.5 años (${edad.anos} años, ${edad.meses} meses). Cursa en plantel; el personal administrativo debe tramitar su alta oficial ante SEP una vez cumplida la edad reglamentaria.</div>`);
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

            // Grupo recomendado: el primero con fecha de inicio futura
            const recomendado = grupos[0];
            if (boxRecomendado && recomendado) {
                boxRecomendado.style.display = 'block';
                form.querySelector('#txtRecomendadoClave').textContent = recomendado.clave;
                form.querySelector('#txtRecomendadoMeta').innerHTML = `
                    <strong>Modalidad:</strong> ${recomendado.modalidadHorario || 'General'} | 
                    <strong>Nivel:</strong> ${recomendado.nombre_nivel || 'Nivel ' + nivelId} | 
                    <strong>Fecha Inicio:</strong> ${formatearFechaDisplay(recomendado.fechaInicio)}
                `;
                const btnRec = form.querySelector('#btnElegirRecomendado');
                if (btnRec) {
                    btnRec.onclick = function() {
                        elegirGrupoFinal(recomendado.id, recomendado.clave);
                    };
                }
            }

            // Tabla de todos los grupos
            if (boxTabla && tbody) {
                boxTabla.style.display = 'block';
                let htmlRows = '';
                grupos.forEach(g => {
                    htmlRows += `
                        <tr>
                            <td class="fw-bold text-primary">${g.clave}</td>
                            <td>${g.modalidadHorario || 'Regular'}</td>
                            <td>${g.nombre_nivel || 'N/A'}</td>
                            <td>${formatearFechaDisplay(g.fechaInicio)}</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-xs btn-outline-primary py-1 px-2 btn-elegir-grupo-tabla" 
                                    data-id="${g.id}" data-clave="${g.clave}">
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

function elegirGrupoFinal(idGrupo, claveGrupo) {
    const form = document.getElementById('formAlumno');
    if (!form) return;
    const inputGrupo = form.querySelector('#inputGrupoSeleccionado');
    const badgeElegido = form.querySelector('#badgeGrupoElegidoFinal');
    const txtElegido = form.querySelector('#txtNombreGrupoElegido');

    if (inputGrupo) inputGrupo.value = idGrupo;
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
                if (inputGrupo) inputGrupo.value = grupoIdAl;
                if (txtElegido) txtElegido.textContent = al.claveGrupo || al.nombreGrupo || `Grupo #${grupoIdAl}`;
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

// Listener de input para actualizar edad en tiempo real al escribir
document.addEventListener('input', function(e) {
    if (e.target.name === 'fechaNacimiento' || e.target.id === 'inputFechaNacimiento') {
        verificarEdadBGNE();
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
        elegirGrupoFinal(btn.dataset.id, btn.dataset.clave);
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
                <button type="button" class="btn btn-sm btn-link text-danger p-0 btn-eliminar-materia-pend"><i class="bi bi-trash"></i></button>
            </td>
        `;
        tbody.appendChild(tr);
        actualizarResumenYSemaforo();
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
                const groupSelect = document.querySelector('#formAlumno select[name="id_Grupo"]');
                if (groupSelect) {
                    groupSelect.value = grupoId;
                }
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
            : `/alumnos/lista?page=${pagina}&limit=10&search=${search}&generacion=${generacion}`;

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
    document.getElementById('buscadorAlumnos').addEventListener('input', (e) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            search = e.target.value;
            pagina = 1;
            cargarAlumnos();
        }, 400);
    });

    document.getElementById('filtroBGNE').addEventListener('change', (e) => {
        generacion = e.target.value;
        pagina = 1;
        cargarAlumnos();
    });

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
                <button class="btn btn-info text-white btn-sm btn-action" onclick="abrirKardexAlumno(${alumno.idAlumno})" title="Ver Kárdex / Calificaciones">
                    <i class="fa-solid fa-graduation-cap"></i>
                </button>
                <button class="btn btn-secondary btn-sm btn-action" onclick="verAlumno(${alumno.idAlumno})" title="Ver detalles">
                    <i class="fa-solid fa-eye"></i>
                </button>
                <button class="btn btn-warning btn-sm btn-action" onclick="editarAlumno(${alumno.idAlumno})" title="Editar">
                    <i class="fa-solid fa-pen"></i>
                </button>
                <button class="btn btn-danger btn-sm btn-action btnEliminar" data-id="${alumno.idAlumno}" title="Eliminar">
                    <i class="fa-solid fa-trash"></i>
                </button>
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

        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());

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
                observaciones: data.observaciones || null,
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
    const modalEl = document.getElementById('modalKardexAlumno');
    if (modalEl && modalEl.parentElement !== document.body) {
        document.body.appendChild(modalEl);
    }
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();

    const contenedor = document.getElementById('contenedorPeriodosKardex');
    contenedor.innerHTML = `
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary"></div>
            <div class="text-muted mt-2">Cargando kárdex de calificaciones...</div>
        </div>
    `;

    fetch(`/alumnos/${idAlumno}/kardex`)
        .then(r => r.json())
        .then(resp => {
            if (!resp.success || !resp.data) {
                contenedor.innerHTML = '<div class="col-12 alert alert-danger text-center">Error al cargar el kárdex.</div>';
                return;
            }

            const data = resp.data;
            const al = data.alumno;
            const periodos = data.periodos || [];

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
                    const califVal = m.calificacion !== null ? m.calificacion : '';
                    htmlMaterias += `
                        <tr>
                            <td class="px-2 py-1 align-middle text-uppercase fw-semibold" style="font-size: 0.78rem; border-color: #cbd5e1;">
                                ${m.nombreMateria}
                            </td>
                            <td class="px-1 py-1 text-center align-middle" style="width: 85px; border-color: #cbd5e1;">
                                <input type="number" step="0.1" min="0" max="10" 
                                    class="form-control form-control-sm text-center fw-bold input-calif-kardex" 
                                    data-materia="${m.idMateria}" 
                                    data-nivel="${p.idNivel}" 
                                    data-periodo-idx="${idx}"
                                    value="${califVal}" 
                                    style="height: 28px; font-size: 0.85rem; padding: 2px 4px;"
                                    placeholder="—"
                                    oninput="calcularPromediosKardex()">
                            </td>
                        </tr>
                    `;
                });

                const promInicial = p.promedio !== null ? p.promedio : '—';

                let footerHtml = `
                    <tfoot>
                        <tr class="bg-light fw-bold" style="border-color: #333; font-size: 0.78rem;">
                            <td class="text-end px-2 py-1 text-uppercase">PROMEDIO</td>
                            <td class="text-center px-1 py-1 text-primary fw-bold prom-periodo-val" id="promPeriodo_${idx}">${promInicial}</td>
                        </tr>
                `;

                if (idx === 4) { // 5to periodo
                    footerHtml += `
                        <tr class="fw-bold" style="border-color: #333; font-size: 0.8rem; background: #e2e8f0;">
                            <td class="text-end px-2 py-1 text-uppercase">PROMEDIO FINAL</td>
                            <td class="text-center px-1 py-1 fw-bold text-primary" id="kardexPromedioFinal">0.0</td>
                        </tr>
                    `;
                } else if (idx === 5) { // 6to periodo (balance visual)
                    footerHtml += `
                        <tr style="border-color: transparent; height: 26px;">
                            <td colspan="2" style="border: none !important; background: transparent;"></td>
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
                                            <th class="px-2 py-1 text-uppercase text-dark" style="border-color: #000 !important;">MATERIA</th>
                                            <th class="px-1 py-1 text-center text-uppercase text-dark" style="width: 80px; border-color: #000 !important;">EVALUACIÓN OBTENIDA</th>
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
        })
        .catch(err => {
            console.error('Error cargando kardex:', err);
            contenedor.innerHTML = '<div class="col-12 alert alert-danger text-center">Error de conexión al cargar kárdex.</div>';
        });
};

window.calcularPromediosKardex = function() {
    const inputs = document.querySelectorAll('.input-calif-kardex');
    const periodosSums = {};
    const periodosCounts = {};
    let sumGlobal = 0;
    let countGlobal = 0;

    inputs.forEach(inp => {
        const pIdx = inp.dataset.periodoIdx;
        const valStr = inp.value.trim();

        if (!periodosSums[pIdx]) {
            periodosSums[pIdx] = 0;
            periodosCounts[pIdx] = 0;
        }

        if (valStr !== '' && !isNaN(valStr)) {
            const num = parseFloat(valStr);
            periodosSums[pIdx] += num;
            periodosCounts[pIdx] += 1;
            sumGlobal += num;
            countGlobal += 1;

            if (num < 6.0) {
                inp.style.setProperty('color', '#dc2626', 'important');
                inp.style.setProperty('font-weight', '700', 'important');
            } else {
                inp.style.setProperty('color', '#1e293b', 'important');
                inp.style.setProperty('font-weight', '700', 'important');
            }
        } else {
            inp.style.setProperty('color', '#1e293b', 'important');
        }
    });

    // Actualizar promedios por periodo
    Object.keys(periodosSums).forEach(pIdx => {
        const el = document.getElementById(`promPeriodo_${pIdx}`);
        if (el) {
            const count = periodosCounts[pIdx];
            if (count > 0) {
                const prom = (periodosSums[pIdx] / count).toFixed(1);
                el.textContent = prom;
                if (parseFloat(prom) < 6.0) {
                    el.style.setProperty('color', '#dc2626', 'important');
                } else {
                    el.style.setProperty('color', '#1e6fa8', 'important');
                }
            } else {
                el.textContent = '—';
                el.style.setProperty('color', '#64748b', 'important');
            }
        }
    });

    // Actualizar promedio final
    const finalEl = document.getElementById('kardexPromedioFinal');
    if (finalEl) {
        if (countGlobal > 0) {
            const promFinal = (sumGlobal / countGlobal).toFixed(1);
            finalEl.textContent = promFinal;
            if (parseFloat(promFinal) < 6.0) {
                finalEl.style.setProperty('color', '#dc2626', 'important');
            } else {
                finalEl.style.setProperty('color', '#1e6fa8', 'important');
            }
        } else {
            finalEl.textContent = '0.0';
            finalEl.style.setProperty('color', '#1e6fa8', 'important');
        }
    }
};

window.guardarCalificacionesKardex = function() {
    if (!idAlumnoKardexActual) return;
    const btn = document.getElementById('btnGuardarKardex');
    const inputs = document.querySelectorAll('.input-calif-kardex');
    const calificaciones = [];

    inputs.forEach(inp => {
        const valStr = inp.value.trim();
        if (valStr !== '' && !isNaN(valStr)) {
            calificaciones.push({
                idMateria: parseInt(inp.dataset.materia),
                id_nivel_academico: parseInt(inp.dataset.nivel),
                calificacion: parseFloat(valStr),
                tipoAcreditacion: 'ORDINARIO'
            });
        }
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
            const inp = tr.querySelector('input.input-calif-kardex');
            const calif = inp ? inp.value.trim() : '';
            rows.push({
                materia: matName,
                calificacion: calif !== '' ? calif : '—',
                esReprobatoria: calif !== '' && !isNaN(calif) && parseFloat(calif) < 6.0
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

    const colIzqHtml = `
        <div style="width: 350px; flex-shrink: 0;">
            ${renderTablaPeriodo(periodosData[0], false, false)}
            ${renderTablaPeriodo(periodosData[2], false, false)}
            ${renderTablaPeriodo(periodosData[4], true, false)}
        </div>
    `;

    const colDerHtml = `
        <div style="width: 350px; flex-shrink: 0;">
            ${renderTablaPeriodo(periodosData[1], false, false)}
            ${renderTablaPeriodo(periodosData[3], false, false)}
            ${renderTablaPeriodo(periodosData[5], false, true)}
        </div>
    `;

    const win = window.open('', '', 'height=850,width=1100');
    win.document.write(`
        <html>
            <head>
                <title>Kárdex de Calificaciones</title>
                <style>
                    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; box-sizing: border-box; }
                    @page {
                        size: letter portrait;
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
                        width: 720px;
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
                        <div style="width: 630px; border: 1.5px solid #10599a; overflow: hidden; border-radius: 3px;">
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
                    <div style="display: flex; justify-content: space-between; width: 720px; margin: 0 auto; text-align: left;">
                        ${colIzqHtml}
                        ${colDerHtml}
                    </div>

                    <!-- LEMA INFERIOR -->
                    <div style="width: 720px; margin: 6px auto 0 auto; background: #5b9bd5; color: #ffffff; font-weight: bold; font-size: 8.2pt; padding: 3px 0; text-align: center; border-radius: 2px;">
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
</script>