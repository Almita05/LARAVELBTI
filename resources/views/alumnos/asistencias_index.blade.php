@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="page-container">
    <!-- Encabezado de Página -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="page-title mb-1">
                <i class="fa-solid fa-clipboard-user me-2"></i>Asistencias de Alumnos
            </h3>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Control y registro de asistencias por grupos y días de clase.</p>
        </div>
        @if(session('rol') === 'ADMIN')
            <button class="btn btn-azul d-flex align-items-center gap-2" onclick="abrirModalJustificacion()" style="border-radius: 12px; font-weight: 500; font-size: 0.82rem; height: 40px; border: none; box-shadow: 0 4px 12px rgba(49, 125, 146, 0.25);">
                <i class="fa-solid fa-user-shield" style="font-size: 0.95rem;"></i>Justificar Faltas
            </button>
        @endif
    </div>

    <!-- Alertas -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background-color: rgba(220, 38, 38, 0.2); color: #fecaca;">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
        $dayOfWeek = date('N');
        $diaClaseHoy = '';
        if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
            $diaClaseHoy = 'LUNES-VIERNES';
        } elseif ($dayOfWeek == 6) {
            $diaClaseHoy = 'SABADO';
        } elseif ($dayOfWeek == 7) {
            $diaClaseHoy = 'DOMINGO';
        }

        $pendientesCount = 0;
        $finalizadosCount = 0;

        foreach($grupos as $grupo) {
            $dias = $grupo['diasClase'] ?? [];
            $esHoyClase = in_array($diaClaseHoy, $dias);
            if ($esHoyClase) {
                if (in_array($grupo['id'], $gruposConAsistenciaHoy)) {
                    $finalizadosCount++;
                } else {
                    $pendientesCount++;
                }
            }
        }
    @endphp

    <!-- Estadísticas Rápidas -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; background: rgba(255, 255, 255, 0.25); backdrop-filter: blur(12px); border: 1px solid rgba(49, 125, 146, 0.15) !important;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 52px; height: 52px; background: rgba(249, 115, 22, 0.15);">
                        <i class="fa-solid fa-clock-rotate-left text-warning" style="font-size: 1.3rem;"></i>
                    </div>
                    <div>
                        <span class="text-muted d-block uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 1px;">PENDIENTES HOY</span>
                        <h3 class="text-dark fw-bold mb-0 mt-1" id="stat-pendientes">{{ $pendientesCount }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; background: rgba(255, 255, 255, 0.25); backdrop-filter: blur(12px); border: 1px solid rgba(49, 125, 146, 0.15) !important;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 52px; height: 52px; background: rgba(34, 197, 94, 0.15);">
                        <i class="fa-solid fa-circle-check text-success" style="font-size: 1.3rem;"></i>
                    </div>
                    <div>
                        <span class="text-muted d-block uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 1px;">FINALIZADOS HOY</span>
                        <h3 class="text-dark fw-bold mb-0 mt-1" id="stat-finalizados">{{ $finalizadosCount }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de Filtros y Búsqueda -->
    <div class="card border-0 mb-4 shadow-sm" style="border-radius: 16px; background: rgba(255, 255, 255, 0.25); border: 1px solid rgba(49, 125, 146, 0.12) !important;">
        <div class="card-body p-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <!-- Tabs de Filtro de Días -->
            <ul class="nav nav-pills border-0 p-1" style="border-radius: 12px; gap: 4px; background: rgba(0, 0, 0, 0.06); border: 1px solid rgba(49, 125, 146, 0.1) !important;">
                <li class="nav-item">
                    <button class="nav-link active px-4 py-2 border-0 rounded-3 fw-medium" id="tab-todos" onclick="filtrarDia('TODOS')" style="font-size: 0.8rem; transition: 0.2s;">
                        Todos
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link px-4 py-2 border-0 rounded-3 fw-medium" id="tab-semana" onclick="filtrarDia('LUNES-VIERNES')" style="font-size: 0.8rem; transition: 0.2s;">
                        Lunes a Viernes
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link px-4 py-2 border-0 rounded-3 fw-medium" id="tab-sabados" onclick="filtrarDia('SABADO')" style="font-size: 0.8rem; transition: 0.2s;">
                        Sábados
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link px-4 py-2 border-0 rounded-3 fw-medium" id="tab-domingos" onclick="filtrarDia('DOMINGO')" style="font-size: 0.8rem; transition: 0.2s;">
                        Domingos
                    </button>
                </li>
            </ul>

            <!-- Buscador por Texto -->
            <div class="position-relative w-100 w-md-25" style="min-width: 250px;">
                <span class="position-absolute start-0 top-50 translate-middle-y ms-3 text-muted">
                    <i class="fa-solid fa-magnifying-glass" style="font-size: 0.85rem;"></i>
                </span>
                <input type="text" id="buscadorGrupo" class="form-control ps-5 border-0 text-dark" placeholder="Buscar grupo por clave..." style="background: rgba(255, 255, 255, 0.5); border-radius: 12px; height: 42px; font-size: 0.85rem; border: 1px solid rgba(49, 125, 146, 0.2) !important;">
            </div>
        </div>
    </div>

    <!-- Listado de Grupos -->
    <div class="row g-4" id="contenedor-grupos">
        @foreach($grupos as $grupo)
            @php
                $dias = $grupo['diasClase'] ?? [];
                $esHoyClase = in_array($diaClaseHoy, $dias);
                $tieneAsistenciaHoy = in_array($grupo['id'], $gruposConAsistenciaHoy);

                $status = 'NO_PROGRAMADO';
                if ($esHoyClase) {
                    $status = $tieneAsistenciaHoy ? 'FINALIZADO' : 'PENDIENTE';
                }

                $diasLabel = '';
                if (in_array('LUNES-VIERNES', $dias)) {
                    $diasLabel = 'Lunes a Viernes';
                } elseif (in_array('SABADO', $dias)) {
                    $diasLabel = 'Sábados';
                } elseif (in_array('DOMINGO', $dias)) {
                    $diasLabel = 'Domingos';
                } else {
                    $diasLabel = implode(', ', $dias);
                }
            @endphp
            <div class="col-md-6 col-lg-4 elemento-grupo" data-clave="{{ strtolower($grupo['clave']) }}" data-dias="{{ implode(',', $dias) }}">
                <div class="card h-100 border-0 shadow-premium card-premium position-relative" style="border-radius: 20px; background: rgba(255, 255, 255, 0.55); border: 1px solid rgba(49, 125, 146, 0.18) !important; backdrop-filter: blur(12px); transition: transform 0.25s, box-shadow 0.25s;">
                    <div class="card-body p-4 d-flex flex-column">
                        <!-- Clave del Grupo y Estado -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h4 class="text-dark fw-bold mb-1" style="font-size: 1.25rem; letter-spacing: -0.3px;">{{ $grupo['clave'] }}</h4>
                                <span class="badge" style="font-size: 0.68rem; padding: 4px 8px; border-radius: 6px; background-color: {{ $grupo['id_centroTrabajo'] == 2 ? 'rgba(49, 125, 146, 0.15)' : 'rgba(139, 92, 246, 0.12)' }}; color: {{ $grupo['id_centroTrabajo'] == 2 ? 'rgb(38, 104, 123)' : 'rgb(109, 40, 217)' }};">
                                    {{ $grupo['nombreCentroTrabajo'] ?? ($grupo['id_centroTrabajo'] == 2 ? 'BTI' : 'BGNE') }}
                                </span>
                            </div>
                            @if($status == 'FINALIZADO')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1" style="border-radius: 20px; font-size: 0.68rem; font-weight: 600;">
                                    <i class="fa-solid fa-check-double me-1"></i>FINALIZADO
                                </span>
                            @elseif($status == 'PENDIENTE')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1" style="border-radius: 20px; font-size: 0.68rem; font-weight: 600; animation: pulse-warning 2s infinite;">
                                    <i class="fa-solid fa-clock me-1"></i>PENDIENTE HOY
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-1" style="border-radius: 20px; font-size: 0.68rem; font-weight: 600;">
                                    NO PROGRAMADO HOY
                                </span>
                            @endif
                        </div>

                        <!-- Detalles del Grupo -->
                        <div class="mt-2 text-dark flex-grow-1" style="font-size: 0.8rem; line-height: 1.8;">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fa-solid fa-calendar-days me-2" style="width: 16px; color: rgb(49, 125, 146);"></i>
                                <span class="text-dark" style="font-weight: 500;">{{ $diasLabel }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fa-solid fa-graduation-cap me-2" style="width: 16px; color: rgb(49, 125, 146);"></i>
                                <span class="text-dark" style="font-weight: 500;">{{ $grupo['nombre_nivel'] ?? 'Sin nivel académico' }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fa-solid fa-users me-2" style="width: 16px; color: rgb(49, 125, 146);"></i>
                                <span class="text-dark" style="font-weight: 500;">{{ $grupo['alumnos_count'] ?? 0 }} Alumnos Inscritos</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-door-open me-2" style="width: 16px; color: rgb(49, 125, 146);"></i>
                                <span class="text-dark" style="font-weight: 500;">Aula: {{ $grupo['aula'] ?? 'Sin asignar' }}</span>
                            </div>
                        </div>

                        <!-- Botón de Acción -->
                        <div class="mt-4">
                            @if($status == 'PENDIENTE')
                                <a href="{{ route('asistencias_alumnos.grupo', $grupo['id']) }}" class="btn btn-azul w-100 py-2 fw-semibold d-flex align-items-center justify-content-center gap-2" style="border-radius: 12px; font-size: 0.82rem; border: none; box-shadow: 0 4px 12px rgba(49, 125, 146, 0.25);">
                                    <i class="fa-solid fa-signature"></i>Pasar Lista de Hoy
                                </a>
                            @else
                                <a href="{{ route('asistencias_alumnos.grupo', $grupo['id']) }}" class="btn btn-outline-secondary w-100 py-2 fw-semibold d-flex align-items-center justify-content-center gap-2" style="border-radius: 12px; font-size: 0.82rem; color: rgb(38, 104, 123); border-color: rgba(49, 125, 146, 0.35); background-color: rgba(255, 255, 255, 0.3);">
                                    <i class="fa-solid fa-eye"></i>Ver Historial y Matriz
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Mensaje No Encontrado -->
    <div class="text-center py-5 d-none" id="mensaje-vacio">
        <i class="fa-solid fa-inbox text-muted mb-3" style="font-size: 3rem;"></i>
        <h5 class="text-dark">No se encontraron grupos</h5>
        <p class="text-muted small">Intenta cambiando el filtro de día o el término de búsqueda.</p>
    </div>
</div>

<style>
    /* Efecto hover en tarjetas */
    .card-premium:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px -10px rgba(49, 125, 146, 0.2), 0 0 0 1px rgba(49, 125, 146, 0.25) !important;
        background: rgba(255, 255, 255, 0.75) !important;
    }

    /* Animación de pulso para pendientes */
    @keyframes pulse-warning {
        0% {
            box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.4);
        }
        70% {
            box-shadow: 0 0 0 6px rgba(249, 115, 22, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(249, 115, 22, 0);
        }
    }

    .nav-pills .nav-link:not(.active) {
        color: #475569 !important;
    }
    .nav-pills .nav-link:not(.active):hover {
        background-color: rgba(49, 125, 146, 0.08);
        color: rgb(38, 104, 123) !important;
    }
    .nav-pills .nav-link.active {
        background-color: rgb(49, 125, 146) !important;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(49, 125, 146, 0.25);
    }
</style>

<script>
    let filtroDiaActual = 'TODOS';

    function filtrarDia(dia) {
        filtroDiaActual = dia;
        
        // Actualizar pestañas activas
        document.querySelectorAll('.nav-pills .nav-link').forEach(el => el.classList.remove('active'));
        if (dia === 'TODOS') document.getElementById('tab-todos').classList.add('active');
        if (dia === 'LUNES-VIERNES') document.getElementById('tab-semana').classList.add('active');
        if (dia === 'SABADO') document.getElementById('tab-sabados').classList.add('active');
        if (dia === 'DOMINGO') document.getElementById('tab-domingos').classList.add('active');

        aplicarFiltros();
    }

    // Buscador en tiempo real
    const normalizeStr = str => (str || '').normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
    document.getElementById('buscadorGrupo').addEventListener('input', aplicarFiltros);

    function aplicarFiltros() {
        const query = normalizeStr(document.getElementById('buscadorGrupo').value.trim());
        const elementos = document.querySelectorAll('.elemento-grupo');
        let visibles = 0;

        elementos.forEach(el => {
            const clave = el.getAttribute('data-clave') || '';
            const dias = el.getAttribute('data-dias') || '';

            const coincideDia = (filtroDiaActual === 'TODOS') || dias.includes(filtroDiaActual);
            const coincideTexto = query.length === 0 || normalizeStr(clave).includes(query);

            if (coincideDia && coincideTexto) {
                el.classList.remove('d-none');
                visibles++;
            } else {
                el.classList.add('d-none');
            }
        });

        const mensajeVacio = document.getElementById('mensaje-vacio');
        if (visibles === 0) {
            mensajeVacio.classList.remove('d-none');
        } else {
            mensajeVacio.classList.add('d-none');
        }
    }

    @if(session('rol') === 'ADMIN')
    let modalJustificacion = null;
    let justTimer = null;

    function abrirModalJustificacion() {
        if(!modalJustificacion) {
            modalJustificacion = new bootstrap.Modal(document.getElementById('modalJustificacion'));
        }
        document.getElementById('formJustificacion').reset();
        document.getElementById('just-seleccionado-container').classList.add('d-none');
        document.getElementById('just-id-alumno').value = '';
        document.getElementById('just-resultados-busqueda').classList.add('d-none');
        document.getElementById('just-resultados-busqueda').innerHTML = '';
        document.getElementById('btn-guardar-justificacion').disabled = true;

        const hoy = new Date().toISOString().split('T')[0];
        document.getElementById('just-fecha-inicio').value = hoy;
        document.getElementById('just-fecha-fin').value = hoy;

        modalJustificacion.show();
    }

    function buscarAlumnoJustificacion(query) {
        clearTimeout(justTimer);
        const resultsDiv = document.getElementById('just-resultados-busqueda');
        if (query.trim().length < 2) {
            resultsDiv.classList.add('d-none');
            resultsDiv.innerHTML = '';
            return;
        }

        justTimer = setTimeout(() => {
            fetch(`/alumnos/lista?search=${encodeURIComponent(query)}&limit=10`)
                .then(res => res.json())
                .then(resp => {
                    const alumnosList = resp.data || [];
                    resultsDiv.innerHTML = '';
                    if (alumnosList.length === 0) {
                        resultsDiv.innerHTML = '<div class="list-group-item text-muted small bg-white">No se encontraron alumnos</div>';
                    } else {
                        alumnosList.forEach(al => {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'list-group-item list-group-item-action text-start bg-white';
                            btn.style.fontSize = '0.8rem';
                            btn.innerHTML = `
                                <strong>${al.apPaterno} ${al.apMaterno || ''} ${al.nombre}</strong><br>
                                <span class="text-muted" style="font-size:0.7rem;">Matrícula: ${al.numeroControl}</span>
                            `;
                            btn.onclick = () => seleccionarAlumnoJustificacion(al);
                            resultsDiv.appendChild(btn);
                        });
                    }
                    resultsDiv.classList.remove('d-none');
                })
                .catch(err => {
                    console.error('Error al buscar alumno:', err);
                    resultsDiv.innerHTML = '<div class="list-group-item text-danger small bg-white">Error al buscar alumno</div>';
                    resultsDiv.classList.remove('d-none');
                });
        }, 300);
    }

    function seleccionarAlumnoJustificacion(al) {
        document.getElementById('just-id-alumno').value = al.idAlumno;
        document.getElementById('just-alumno-nombre').textContent = `${al.apPaterno} ${al.apMaterno || ''} ${al.nombre}`;
        document.getElementById('just-alumno-matricula').textContent = `Matrícula: ${al.numeroControl}`;
        document.getElementById('just-seleccionado-container').classList.remove('d-none');
        document.getElementById('just-buscar-alumno').value = '';
        document.getElementById('just-resultados-busqueda').classList.add('d-none');
        document.getElementById('just-resultados-busqueda').innerHTML = '';
        document.getElementById('btn-guardar-justificacion').disabled = false;
    }

    function guardarJustificacion(e) {
        e.preventDefault();
        const id_alumno = document.getElementById('just-id-alumno').value;
        const fecha_inicio = document.getElementById('just-fecha-inicio').value;
        const fecha_fin = document.getElementById('just-fecha-fin').value;
        const motivo = document.getElementById('just-motivo').value;

        if (!id_alumno || !fecha_inicio || !fecha_fin) {
            Swal.fire({
                icon: 'error',
                title: 'Campos incompletos',
                text: 'Por favor, selecciona un alumno y especifica el rango de fechas.'
            });
            return;
        }

        Swal.fire({
            title: 'Registrando Justificación...',
            text: 'Por favor espera',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        fetch('/asistencias_alumnos/justificar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ id_alumno, fecha_inicio, fecha_fin, motivo })
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.error) {
                Swal.fire({ icon: 'error', title: 'Error', text: resp.error });
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'La justificación ha sido registrada correctamente.',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    modalJustificacion.hide();
                });
            }
        })
        .catch(err => {
            console.error('Error al registrar justificación:', err);
            Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un problema al guardar la justificación.' });
        });
    }
    @endif
</script>

@if(session('rol') === 'ADMIN')
<!-- Modal de Justificación de Faltas -->
<div class="modal fade" id="modalJustificacion" tabindex="-1" aria-labelledby="modalJustificacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-premium" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(16px); border: 1px solid rgba(49, 125, 146, 0.15); border-radius: 20px;">
            <div class="modal-header border-0 pb-0" style="padding: 1.5rem 1.5rem 0.5rem 1.5rem;">
                <h5 class="modal-title fw-bold text-dark" id="modalJustificacionLabel">
                    <i class="fa-solid fa-user-shield text-primary me-2"></i>Justificar Faltas de Alumno
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formJustificacion" onsubmit="guardarJustificacion(event)">
                <div class="modal-body" style="padding: 1.5rem;">
                    <!-- Búsqueda de Alumno -->
                    <div class="mb-3 position-relative">
                        <label class="form-label fw-bold text-dark small" style="margin-bottom: 6px;">Buscar Alumno</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0" style="border-radius: 10px 0 0 10px; border-color: rgba(49, 125, 146, 0.2);"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" id="just-buscar-alumno" class="form-control border-start-0 text-dark bg-white" placeholder="Escribe nombre o matrícula..." oninput="buscarAlumnoJustificacion(this.value)" style="border-radius: 0 10px 10px 0; border-color: rgba(49, 125, 146, 0.2); height: 40px; font-size: 0.85rem;">
                        </div>
                        <!-- Resultados de búsqueda -->
                        <div id="just-resultados-busqueda" class="list-group position-absolute w-100 shadow-premium d-none" style="z-index: 1050; max-height: 200px; overflow-y: auto; border-radius: 10px; border: 1px solid rgba(49, 125, 146, 0.15); margin-top: 4px;"></div>
                    </div>

                    <!-- Alumno Seleccionado -->
                    <div class="mb-3 d-none" id="just-seleccionado-container">
                        <span class="text-muted small d-block mb-1">Alumno Seleccionado:</span>
                        <div class="p-3 bg-light border" style="border-radius: 12px; border-color: rgba(49, 125, 146, 0.12) !important;">
                            <strong class="text-dark d-block" id="just-alumno-nombre" style="font-size: 0.88rem;"></strong>
                            <small class="text-muted" id="just-alumno-matricula" style="font-size: 0.72rem;"></small>
                            <input type="hidden" name="id_alumno" id="just-id-alumno">
                        </div>
                    </div>

                    <!-- Fechas -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark small" style="margin-bottom: 6px;">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" id="just-fecha-inicio" class="form-control text-dark bg-white" required style="border-radius: 10px; border-color: rgba(49, 125, 146, 0.2); height: 40px; font-size: 0.85rem;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark small" style="margin-bottom: 6px;">Fecha Fin</label>
                            <input type="date" name="fecha_fin" id="just-fecha-fin" class="form-control text-dark bg-white" required style="border-radius: 10px; border-color: rgba(49, 125, 146, 0.2); height: 40px; font-size: 0.85rem;">
                        </div>
                    </div>

                    <!-- Motivo -->
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small" style="margin-bottom: 6px;">Motivo / Justificante</label>
                        <textarea name="motivo" id="just-motivo" class="form-control text-dark bg-white" rows="3" placeholder="Ej. Presenta justificante médico..." style="border-radius: 10px; border-color: rgba(49, 125, 146, 0.2); font-size: 0.85rem; resize: none;"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0" style="padding: 1.5rem;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius: 10px; font-size: 0.82rem;">Cancelar</button>
                    <button type="submit" class="btn btn-azul" id="btn-guardar-justificacion" disabled style="border-radius: 10px; font-size: 0.82rem;">Registrar Justificación</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection
