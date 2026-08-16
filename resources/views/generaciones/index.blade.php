@extends('layouts.app')

@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('css/estilosDocentes.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .suggestion-badge {
            background: rgba(49, 125, 146, 0.15);
            border: 1px solid rgba(49, 125, 146, 0.3);
            color: rgb(49, 125, 146);
            font-size: 0.8rem;
            padding: 6px 12px;
            border-radius: 8px;
            display: inline-block;
            margin-top: 8px;
            font-weight: 500;
        }
        .form-label {
            color: #333333 !important;
            font-weight: 600;
        }
        /* Ajustar espaciado de modal para que sea idéntico al de docentes */
        .modal-body {
            padding: 24px !important;
        }
        .page-title {
            color: rgb(7, 101, 136) !important;
        }
    </style>
</head>

<div class="page-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-regresar">
            <i class="fa-solid fa-arrow-left me-2"></i>
            Regresar
        </a>

        <h3 class="page-title mb-0">
            <i class="fa-solid fa-graduation-cap me-2"></i>
            Listado de Generaciones
        </h3>

        <button class="btn btn-azul" onclick="abrirModalCrear()">
            <i class="fa-solid fa-plus me-2"></i>
            Nueva Generación
        </button>
    </div>

    <div class="glass-card">
        <div class="glass-header p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0 text-white font-weight-bold">
                Lista de Generaciones
            </h5>
            <input type="text" id="buscador" class="form-control form-control-premium w-25" placeholder="Buscar generación..." style="min-width: 200px; min-height: 38px;">
        </div>

        <div class="table-responsive">
            <table class="table glass-table align-middle mb-0">
                <thead class="table-head">
                    <tr>
                        <th>CCT (Centro de Trabajo)</th>
                        <th>Consecutivo</th>
                        <th>Nombre Generación</th>
                        <th>Mes de Inicio</th>
                        <th>Año de Inicio</th>
                        <th>Mes de Término</th>
                        <th>Año de Término</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaGeneraciones" class="text-white">
                    <tr>
                        <td colspan="8" class="text-center py-4 text-white">
                            <i class="spinner-border spinner-border-sm me-2"></i> Cargando generaciones...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="glass-footer p-3 d-flex justify-content-between align-items-center">
            <small id="infoPaginacion" class="text-white"></small>
            <div id="paginacion"></div>
        </div>
    </div>
</div>

<!-- MODAL ALTA / EDICIÓN -->
<div class="modal fade" id="modalGeneracion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form id="formGeneracion" class="modal-content glass-modal">
            @csrf
            <input type="hidden" id="inputGeneracionId">
            
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitulo">
                    <i class="fa-solid fa-graduation-cap me-2"></i>Alta de Generación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body text-dark">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">CCT (Centro de Trabajo) *</label>
                        <select name="idCentroTrabajo" id="selectCCT" class="form-select form-select-premium" required>
                            <option value="">-- Seleccione --</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Consecutivo (Número o Rango) *</label>
                        <input type="text" name="generacion" id="inputConsecutivo" class="form-control form-control-premium" placeholder="Ej. 45 o 2024-2027" required>
                        <div id="boxSugerenciaConsecutivo" style="display: none;">
                            <span class="suggestion-badge" id="txtSugerenciaConsecutivo"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Nombre de la Generación *</label>
                        <input type="text" name="nombreGeneracion" id="inputNombreGeneracion" class="form-control form-control-premium" placeholder="Ej. Cuadragesima quinta" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Mes de Inicio *</label>
                        <select name="mesInicio" id="selectMesInicio" class="form-select form-select-premium" required>
                            <option value="FEBRERO" selected>Febrero</option>
                            <option value="AGOSTO">Agosto</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Año de Inicio *</label>
                        <input type="number" name="anioInicio" id="inputAnioInicio" class="form-control form-control-premium" value="{{ date('Y') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Mes de Término *</label>
                        <select name="mesFin" id="selectMesFin" class="form-select form-select-premium" required>
                            <option value="JULIO" selected>Julio</option>
                            <option value="ENERO">Enero</option>
                            <option value="FEBRERO">Febrero</option>
                            <option value="AGOSTO">Agosto</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Año de Término *</label>
                        <input type="number" name="anioFin" id="inputAnioFin" class="form-control form-control-premium" required>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-regresar" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-azul text-white" id="btnGuardar">Guardar Generación</button>
            </div>
        </form>
    </div>
</div>

<script>
    let listadoGeneraciones = [];
    let modalBootstrap = null;
    let modoGeneracion = 'crear';

    document.addEventListener('DOMContentLoaded', function() {
        // Mover el modal al contenedor global para evitar que quede atrapado detrás del backdrop
        const modalEl = document.getElementById('modalGeneracion');
        if (modalEl) {
            document.getElementById('contenedorModal').appendChild(modalEl);
        }
        
        modalBootstrap = new bootstrap.Modal(document.getElementById('modalGeneracion'));
        
        cargarCentrosTrabajo();
        cargarGeneraciones();

        // Listeners para cálculos automáticos en tiempo real
        document.getElementById('selectCCT').addEventListener('change', manejarCambioCCT);
        document.getElementById('selectMesInicio').addEventListener('change', calcularFechasBGNE);
        document.getElementById('inputAnioInicio').addEventListener('input', calcularFechasBGNE);
        
        // Buscador reactivo
        document.getElementById('buscador').addEventListener('input', filtrarTabla);

        // Submit del formulario
        document.getElementById('formGeneracion').addEventListener('submit', guardarGeneracion);
    });

    function cargarCentrosTrabajo() {
        fetch('/catalogos/centros-trabajo')
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('selectCCT');
                select.innerHTML = '<option value="">-- Seleccione --</option>';
                data.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.id;
                    opt.textContent = c.nombre;
                    select.appendChild(opt);
                });
            });
    }

    function cargarGeneraciones() {
        fetch('/generaciones/lista')
            .then(res => res.json())
            .then(data => {
                listadoGeneraciones = data;
                renderTabla(data);
            })
            .catch(err => {
                console.error(err);
                document.getElementById('tablaGeneraciones').innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-4 text-white">
                            <i class="fa-solid fa-circle-exclamation me-2"></i> Error al cargar el listado.
                        </td>
                    </tr>
                `;
            });
    }

    function renderTabla(datos) {
        const tbody = document.getElementById('tablaGeneraciones');
        if (!datos.length) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-4 text-white">
                        No se encontraron registros.
                    </td>
                </tr>
            `;
            document.getElementById('infoPaginacion').textContent = 'Total: 0';
            return;
        }

        let html = '';
        datos.forEach(g => {
            html += `
                <tr>
                    <td class="fw-bold text-white">${g.nombreCentroTrabajo || '—'}</td>
                    <td class="fw-bold text-info">${g.generacion || '—'}</td>
                    <td>${g.nombreGeneracion || '—'}</td>
                    <td>${g.mesInicio || '—'}</td>
                    <td>${g.anioInicio || '—'}</td>
                    <td>${g.mesFin || '—'}</td>
                    <td>${g.aniofin || g.anioFin || '—'}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-editar me-1 py-1 px-2" onclick="abrirModalEditar(${g.id})">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-eliminar py-1 px-2" onclick="confirmarEliminar(${g.id})">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
        document.getElementById('infoPaginacion').textContent = `Total: ${datos.length} generaciones`;
    }

    function filtrarTabla() {
        const busqueda = document.getElementById('buscador').value.toLowerCase().trim();
        if (!busqueda) {
            renderTabla(listadoGeneraciones);
            return;
        }

        const filtrados = listadoGeneraciones.filter(g => {
            return (g.nombreCentroTrabajo && g.nombreCentroTrabajo.toLowerCase().includes(busqueda)) ||
                   (g.nombreGeneracion && g.nombreGeneracion.toLowerCase().includes(busqueda)) ||
                   (g.generacion && String(g.generacion).toLowerCase().includes(busqueda)) ||
                   (g.mesInicio && g.mesInicio.toLowerCase().includes(busqueda)) ||
                   (g.mesFin && g.mesFin.toLowerCase().includes(busqueda));
        });
        renderTabla(filtrados);
    }

    function manejarCambioCCT() {
        const selectCCT = document.getElementById('selectCCT');
        const textCCT = selectCCT.selectedIndex >= 0 ? selectCCT.options[selectCCT.selectedIndex].text.toUpperCase() : '';
        const boxSugerencia = document.getElementById('boxSugerenciaConsecutivo');
        const isBgne = textCCT.includes('BGNE') || String(selectCCT.value) === '3';
        
        const selectMesInicio = document.getElementById('selectMesInicio');
        const inputAnioInicio = document.getElementById('inputAnioInicio');

        if (isBgne) {
            // Consultar última generación registrada en BGNE
            fetch('/generaciones/ultima?idCentroTrabajo=' + selectCCT.value)
                .then(res => res.json())
                .then(gen => {
                    if (gen && gen.generacion) {
                        const ultimoNum = parseInt(gen.generacion);
                        if (!isNaN(ultimoNum)) {
                            const siguiente = ultimoNum + 1;
                            
                            // Mostrar sugerencia
                            boxSugerencia.style.display = 'block';
                            document.getElementById('txtSugerenciaConsecutivo').innerHTML = 
                                `<i class="fa-solid fa-circle-info me-1"></i> Sugerencia: <strong>${siguiente}</strong> (Última registrada: ${ultimoNum})`;

                            if (modoGeneracion === 'crear') {
                                document.getElementById('inputConsecutivo').value = siguiente;
                                document.getElementById('inputNombreGeneracion').value = `Generación ${siguiente}`;
                                
                                // Auto-calcular mes y año de inicio basados en la última generación
                                const ultimoMesInicio = (gen.mesInicio || 'FEBRERO').toUpperCase();
                                const ultimoAnioInicio = parseInt(gen.anioInicio) || new Date().getFullYear();
                                
                                if (ultimoMesInicio === 'FEBRERO') {
                                    selectMesInicio.value = 'AGOSTO';
                                    inputAnioInicio.value = ultimoAnioInicio;
                                } else {
                                    selectMesInicio.value = 'FEBRERO';
                                    inputAnioInicio.value = ultimoAnioInicio + 1;
                                }
                                
                                // Bloquear campos de inicio para evitar cambios manuales en el flujo automático
                                selectMesInicio.style.pointerEvents = 'none';
                                selectMesInicio.setAttribute('tabindex', '-1');
                                selectMesInicio.style.backgroundColor = '#e9ecef';
                                
                                inputAnioInicio.readOnly = true;
                                inputAnioInicio.style.backgroundColor = '#e9ecef';
                            }
                        }
                    } else {
                        // Si no hay ninguna
                        boxSugerencia.style.display = 'block';
                        document.getElementById('txtSugerenciaConsecutivo').innerHTML = 
                            `<i class="fa-solid fa-circle-info me-1"></i> Primera generación: se sugiere <strong>1</strong>`;
                        if (modoGeneracion === 'crear') {
                            document.getElementById('inputConsecutivo').value = '1';
                            document.getElementById('inputNombreGeneracion').value = 'Primera';
                            
                            // Si no hay ninguna anterior, dejamos que elijan el inicio
                            selectMesInicio.style.pointerEvents = 'auto';
                            selectMesInicio.removeAttribute('tabindex');
                            selectMesInicio.style.backgroundColor = '';
                            
                            inputAnioInicio.readOnly = false;
                            inputAnioInicio.style.backgroundColor = '';
                        }
                    }
                    // Ejecutar el cálculo del término
                    calcularFechasBGNE();
                });
        } else {
            boxSugerencia.style.display = 'none';
            
            // Restablecer campos de inicio a editables
            selectMesInicio.style.pointerEvents = 'auto';
            selectMesInicio.removeAttribute('tabindex');
            selectMesInicio.style.backgroundColor = '';
            
            inputAnioInicio.readOnly = false;
            inputAnioInicio.style.backgroundColor = '';
            
            calcularFechasBGNE();
        }
    }

    function calcularFechasBGNE() {
        const selectCCT = document.getElementById('selectCCT');
        const textCCT = selectCCT.selectedIndex >= 0 ? selectCCT.options[selectCCT.selectedIndex].text.toUpperCase() : '';
        const isBgne = textCCT.includes('BGNE') || String(selectCCT.value) === '3';
        
        const selectMesFin = document.getElementById('selectMesFin');
        const inputAnioFin = document.getElementById('inputAnioFin');

        // Sólo aplicar cálculo automático si es BGNE
        if (!isBgne) {
            selectMesFin.style.pointerEvents = 'auto';
            selectMesFin.removeAttribute('tabindex');
            selectMesFin.style.backgroundColor = '';

            inputAnioFin.readOnly = false;
            inputAnioFin.style.backgroundColor = '';
            return;
        }

        // Bloquear campos para evitar cambios manuales del usuario
        selectMesFin.style.pointerEvents = 'none';
        selectMesFin.setAttribute('tabindex', '-1');
        selectMesFin.style.backgroundColor = '#e9ecef';

        inputAnioFin.readOnly = true;
        inputAnioFin.style.backgroundColor = '#e9ecef';

        const mesInicio = document.getElementById('selectMesInicio').value;
        const anioInicio = parseInt(document.getElementById('inputAnioInicio').value) || new Date().getFullYear();

        if (mesInicio === 'FEBRERO') {
            selectMesFin.value = 'JULIO';
            inputAnioFin.value = anioInicio + 1;
        } else if (mesInicio === 'AGOSTO') {
            selectMesFin.value = 'ENERO';
            inputAnioFin.value = anioInicio + 2;
        }
    }

    function abrirModalCrear() {
        modoGeneracion = 'crear';
        document.getElementById('formGeneracion').reset();
        document.getElementById('inputGeneracionId').value = '';
        document.getElementById('modalTitulo').innerHTML = '<i class="fa-solid fa-graduation-cap me-2"></i> Alta de Generación';
        document.getElementById('boxSugerenciaConsecutivo').style.display = 'none';
        document.getElementById('inputAnioInicio').value = new Date().getFullYear();
        
        // Desbloquear campos de inicio por defecto
        const selectMesInicio = document.getElementById('selectMesInicio');
        selectMesInicio.style.pointerEvents = 'auto';
        selectMesInicio.removeAttribute('tabindex');
        selectMesInicio.style.backgroundColor = '';

        const inputAnioInicio = document.getElementById('inputAnioInicio');
        inputAnioInicio.readOnly = false;
        inputAnioInicio.style.backgroundColor = '';

        // Ejecutar cálculo para asegurar que los campos inicien desbloqueados y limpios
        calcularFechasBGNE();

        modalBootstrap.show();
    }

    function abrirModalEditar(id) {
        modoGeneracion = 'editar';
        const gen = listadoGeneraciones.find(g => g.id === id);
        if (!gen) return;

        document.getElementById('inputGeneracionId').value = gen.id;
        document.getElementById('selectCCT').value = gen.id_centroTrabajo;
        document.getElementById('inputConsecutivo').value = gen.generacion || '';
        document.getElementById('inputNombreGeneracion').value = gen.nombreGeneracion || '';
        document.getElementById('selectMesInicio').value = gen.mesInicio || 'FEBRERO';
        document.getElementById('inputAnioInicio').value = gen.anioInicio || new Date().getFullYear();
        document.getElementById('selectMesFin').value = gen.mesFin || 'JULIO';
        document.getElementById('inputAnioFin').value = gen.aniofin || gen.anioFin || '';

        document.getElementById('modalTitulo').innerHTML = '<i class="fa-solid fa-pencil me-2"></i> Editar Generación';
        document.getElementById('boxSugerenciaConsecutivo').style.display = 'none';

        // Asegurar que al editar se puedan corregir los campos de inicio
        const selectMesInicio = document.getElementById('selectMesInicio');
        selectMesInicio.style.pointerEvents = 'auto';
        selectMesInicio.removeAttribute('tabindex');
        selectMesInicio.style.backgroundColor = '';

        const inputAnioInicio = document.getElementById('inputAnioInicio');
        inputAnioInicio.readOnly = false;
        inputAnioInicio.style.backgroundColor = '';

        // Recalcular para corregir posibles datos erróneos de la base de datos
        calcularFechasBGNE();

        modalBootstrap.show();
    }

    function guardarGeneracion(e) {
        e.preventDefault();

        const id = document.getElementById('inputGeneracionId').value;
        const formData = new FormData(document.getElementById('formGeneracion'));
        const payload = Object.fromEntries(formData.entries());

        const url = modoGeneracion === 'crear' ? '/generaciones' : `/generaciones/${id}`;
        const method = modoGeneracion === 'crear' ? 'POST' : 'PUT';

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: data.message,
                    confirmButtonColor: 'rgb(49, 125, 146)'
                });
                modalBootstrap.hide();
                cargarGeneraciones();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Error al procesar la solicitud.',
                    confirmButtonColor: 'rgb(49, 125, 146)'
                });
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de red al intentar guardar.',
                confirmButtonColor: 'rgb(49, 125, 146)'
            });
        });
    }

    function confirmarEliminar(id) {
        Swal.fire({
            title: '¿Deseas eliminar esta generación?',
            text: 'Esta acción no se puede deshacer y puede afectar grupos asociados.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#cbd5e1',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/generaciones/${id}`, {
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
                            confirmButtonColor: 'rgb(49, 125, 146)'
                        });
                        cargarGeneraciones();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Error al eliminar.',
                            confirmButtonColor: 'rgb(49, 125, 146)'
                        });
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error de red al eliminar.',
                        confirmButtonColor: 'rgb(49, 125, 146)'
                    });
                });
            }
        });
    }
</script>
@endsection
