@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@php
    $diasEsp = [
        'Monday' => 'lunes',
        'Tuesday' => 'martes',
        'Wednesday' => 'miércoles',
        'Thursday' => 'jueves',
        'Friday' => 'viernes',
        'Saturday' => 'sábado',
        'Sunday' => 'domingo'
    ];
    $nombreDiaHoy = $diasEsp[date('l')] ?? date('l');
    $semanaAnio = date('W');

    // Mapear días de clase del grupo
    $diasGrupo = $grupo['diasClase'] ?? [];
    $diasLabel = '';
    if (in_array('LUNES-VIERNES', $diasGrupo)) {
        $diasLabel = 'LUNES A VIERNES';
    } elseif (in_array('SABADO', $diasGrupo)) {
        $diasLabel = 'SABADOS';
    } elseif (in_array('DOMINGO', $diasGrupo)) {
        $diasLabel = 'DOMINGOS';
    } else {
        $diasLabel = implode(', ', $diasGrupo);
    }
@endphp

<div class="page-container">
    <!-- Encabezado con Botón de Regresar -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('asistencias_alumnos') }}" class="btn rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 42px; height: 42px; background: rgba(255,255,255,0.4); border: 1px solid rgba(49, 125, 146, 0.2); color: rgb(38, 104, 123); transition: 0.2s;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <div class="d-flex align-items-center gap-2">
                <h3 class="page-title mb-0" style="font-size: 1.6rem; letter-spacing: -0.5px;">{{ $grupo['clave'] }}</h3>
                @if($selected_materia_id === 'general')
                    <span class="badge bg-primary text-white px-2.5 py-1 rounded-pill shadow-sm" style="font-size: 0.72rem; letter-spacing: 0.3px;">
                        Pase General
                    </span>
                @endif
            </div>
            <p class="text-muted mb-0" style="font-size: 0.8rem;">
                {{ $nombreDiaHoy }} • Semana {{ $semanaAnio }} • {{ $diasLabel }} {{ $grupo['horario'] ?? '' }}
            </p>
        </div>
        <div class="ms-auto d-flex gap-2">
            <!-- Selector de Vista: Listado o Matriz -->
            <div class="btn-group bg-light p-1" style="border-radius: 12px; border: 1px solid rgba(49, 125, 146, 0.15);">
                <button type="button" class="btn btn-sm btn-premium-view active rounded-3 px-3 py-1.5" id="btn-vista-listado" onclick="cambiarVista('LISTADO')" style="font-size: 0.78rem; font-weight: 500;">
                    <i class="fa-solid fa-list me-1"></i>Listado
                </button>
                <button type="button" class="btn btn-sm btn-premium-view rounded-3 px-3 py-1.5" id="btn-vista-matriz" onclick="cambiarVista('MATRIZ')" style="font-size: 0.78rem; font-weight: 500;">
                    <i class="fa-solid fa-table-cells me-1"></i>Matriz Excel
                </button>
            </div>
        </div>
    </div>

    <!-- Barra de Selector de Fechas y Materia -->
    <div class="card border-0 mb-4 shadow-sm" id="selector-fechas-card" style="border-radius: 16px; background: rgba(255, 255, 255, 0.25); border: 1px solid rgba(49, 125, 146, 0.12) !important;">
        <div class="card-body p-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <!-- Selector de Materia o Pase General -->
                <div class="d-flex align-items-center gap-2">
                    <label class="text-muted fw-semibold mb-0" style="font-size: 0.82rem; min-width: 85px;">
                        @if(session('rol') !== 'DOCENTE') Modalidad: @else Asignatura: @endif
                    </label>
                    <select id="select-materia" class="form-select border-0 text-dark" onchange="seleccionarMateria(this.value)" style="background: rgba(255, 255, 255, 0.65); border-radius: 10px; min-width: 270px; font-size: 0.85rem; height: 38px; border: 1px solid rgba(49, 125, 146, 0.2) !important; font-weight: 600;">
                        @if(session('rol') !== 'DOCENTE')
                            <option value="general" {{ $selected_materia_id === 'general' ? 'selected' : '' }} style="font-weight: 700; color: #0284c7;">
                                Pase de Lista General (Todo el grupo)
                            </option>
                            @if(count($materias) > 0)
                                <optgroup label="── Por Asignatura Individual ──">
                                    @foreach($materias as $m)
                                        <option value="{{ $m['idMateria'] }}" {{ $m['idMateria'] == $selected_materia_id ? 'selected' : '' }}>
                                            {{ $m['nombreMateria'] }} ({{ $m['nombreDocente'] }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        @else
                            @foreach($materias as $m)
                                <option value="{{ $m['idMateria'] }}" {{ $m['idMateria'] == $selected_materia_id ? 'selected' : '' }}>
                                    {{ $m['nombreMateria'] }} ({{ $m['nombreDocente'] }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Selector de Fecha (sólo visible para vista Listado) -->
                <div class="d-flex align-items-center gap-2" id="div-select-fecha">
                    <label class="text-muted fw-semibold mb-0" style="font-size: 0.82rem; min-width: 110px;">Fecha de clase:</label>
                    <select id="select-fecha" class="form-select border-0 text-dark" onchange="seleccionarFecha(this.value)" style="background: rgba(255, 255, 255, 0.5); border-radius: 10px; min-width: 250px; font-size: 0.85rem; height: 38px; border: 1px solid rgba(49, 125, 146, 0.2) !important;">
                        @foreach($fechas as $fIdx => $f)
                            @php
                                $isBgne = ($grupo['id_centroTrabajo'] == 3);
                                $totalWeeks = count($fechas);
                                $evalText = '';
                                if ($isBgne) {
                                    if ($fIdx == 5 || $fIdx == 6) {
                                        $evalText = ' [Evaluación P.1]';
                                    } elseif ($fIdx == $totalWeeks - 2 || $fIdx == $totalWeeks - 1) {
                                        $evalText = ' [Evaluación P.2]';
                                    }
                                }
                            @endphp
                            <option value="{{ $f['fecha'] }}">
                                {{ \Carbon\Carbon::parse($f['fecha'])->format('d-m-Y') }} ({{ $f['nombreNivel'] }}){{ $evalText }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="d-flex gap-2" id="div-nav-fecha">
                <button class="btn btn-sm btn-secondary d-flex align-items-center justify-content-center" onclick="navegarFecha(-1)" style="border-radius: 8px; width: 36px; height: 36px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff;">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class="btn btn-sm btn-secondary d-flex align-items-center justify-content-center" onclick="navegarFecha(1)" style="border-radius: 8px; width: 36px; height: 36px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff;">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Progreso de Registro (Para vista Listado) -->
    <div class="card border-0 mb-4 shadow-sm" id="progreso-registro-card" style="border-radius: 16px; background: rgba(255, 255, 255, 0.25); border: 1px solid rgba(49, 125, 146, 0.12) !important;">
        <div class="card-body p-4">
            <span class="text-muted d-block uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 1px;">PROGRESO DE REGISTRO</span>
            <div class="d-flex justify-content-between align-items-baseline mt-1 mb-2">
                <h2 class="fw-bold mb-0" id="progreso-texto" style="font-size: 1.5rem; color: rgb(49, 125, 146) !important;">0 / 0 Alumnos</h2>
                <span class="fw-semibold text-dark" id="progreso-status-label" style="font-size: 0.78rem;"></span>
            </div>
            <div class="progress bg-dark" style="height: 8px; border-radius: 10px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" id="progreso-bar" role="progressbar" style="width: 0%; border-radius: 10px; background-color: rgb(49, 125, 146) !important;"></div>
            </div>
        </div>
    </div>

    <!-- Buscador y Botón Agregar -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-4">
        <!-- Buscador -->
        <div class="position-relative w-100 w-md-25" style="min-width: 250px;">
            <span class="position-absolute start-0 top-50 translate-middle-y ms-3 text-muted">
                <i class="fa-solid fa-magnifying-glass" style="font-size: 0.85rem;"></i>
            </span>
            <input type="text" id="buscadorAlumno" class="form-control ps-5 border-0 text-dark" oninput="filtrarAlumnos()" placeholder="Buscar alumno..." style="background: rgba(255, 255, 255, 0.5); border-radius: 12px; height: 42px; font-size: 0.85rem; border: 1px solid rgba(49, 125, 146, 0.2) !important;">
        </div>

        <div class="d-flex gap-2 w-100 w-md-auto justify-content-end align-items-center">
            <!-- Botón Agregar Alumno -->
            <button type="button" class="btn btn-premium-secondary py-2 px-4 fw-semibold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalAgregarAlumno" style="border-radius: 12px; font-size: 0.82rem;">
                <i class="fa-solid fa-user-plus"></i>Agregar Alumno
            </button>

            <!-- Botón Imprimir (PDF) -->
            <button id="btn-imprimir-asistencias" type="button" class="btn btn-outline-primary py-2 px-4 fw-semibold d-flex align-items-center gap-2 d-none" onclick="imprimirReporte()" style="border-radius: 12px; font-size: 0.82rem; border-color: rgba(49, 125, 146, 0.35); color: rgb(38, 104, 123); background-color: rgba(255,255,255,0.3);">
                <i class="fa-solid fa-print"></i>Imprimir Reporte
            </button>

            <!-- Botón Descargar Excel -->
            <button id="btn-excel-asistencias" type="button" class="btn btn-outline-success py-2 px-4 fw-semibold d-flex align-items-center gap-2 d-none" onclick="descargarExcelAsistencias()" style="border-radius: 12px; font-size: 0.82rem; border-color: rgba(34, 197, 94, 0.35); color: rgb(21, 128, 61); background-color: rgba(255,255,255,0.3);">
                <i class="fa-solid fa-file-excel"></i>Descargar Excel
            </button>

            <!-- Botón Guardar Cambios -->
            <button id="btn-guardar-asistencias" type="button" class="btn btn-success py-2 px-4 fw-semibold d-flex align-items-center gap-2" onclick="guardarAsistencias()" style="border-radius: 12px; font-size: 0.82rem; box-shadow: 0 4px 12px rgba(34, 197, 94, 0.25);">
                <i class="fa-solid fa-floppy-disk"></i>Guardar Cambios
            </button>
        </div>
    </div>

    <!-- Contenido Principal: VISTA LISTADO -->
    <div id="contenedor-vista-listado">
        <!-- Barra de herramientas superior del listado -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 px-1 gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-white text-dark border shadow-sm px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.8rem; border-color: rgba(49, 125, 146, 0.2) !important;">
                    <i class="fa-solid fa-calendar-day me-1" style="color: rgb(49, 125, 146);"></i>
                    <span id="label-fecha-seleccionada">Fecha</span>
                </span>
                <span class="text-muted small fw-medium" id="label-total-alumnos"></span>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-success fw-semibold d-flex align-items-center gap-1 shadow-sm" onclick="marcarTodos('A')" style="border-radius: 10px; font-size: 0.78rem; background: #fff;">
                    <i class="fa-solid fa-check-double text-success"></i> Todos Asistencia
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary fw-semibold d-flex align-items-center gap-1 shadow-sm" onclick="marcarTodos(null)" style="border-radius: 10px; font-size: 0.78rem; background: #fff;">
                    <i class="fa-solid fa-eraser text-secondary"></i> Limpiar
                </button>
            </div>
        </div>

        <!-- Contenedor de Tarjetas de Alumnos -->
        <div id="lista-alumnos-cards" class="d-flex flex-column gap-3">
            <!-- Renderizado dinámicamente por renderListado() -->
        </div>

        <div class="text-center py-5 d-none bg-white rounded-4 shadow-sm border mt-3" id="mensaje-vacio">
            <i class="fa-solid fa-users-slash text-muted mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
            <h5 class="text-dark fw-bold">No se encontraron alumnos</h5>
            <p class="text-muted small mb-0">Prueba escribiendo otro nombre o agrega un nuevo alumno al grupo.</p>
        </div>
    </div>

    <!-- Contenido Principal: VISTA MATRIZ EXCEL -->
    <div id="contenedor-vista-matriz" class="d-none">
        <div class="card border-0 shadow-sm" style="border-radius: 16px; background: rgba(255, 255, 255, 0.25); border: 1px solid rgba(49, 125, 146, 0.15) !important;">
            <div class="card-body p-0">
                <!-- Contenedor scrollable horizontal -->
                <div class="table-responsive" style="max-height: 60vh; overflow-y: auto; border-radius: 16px;">
                    <table class="table table-hover mb-0 align-middle text-center table-bordered" style="border-color: rgba(49, 125, 146, 0.12); font-size: 0.78rem; color: #1e293b;">
                        <thead>
                            <!-- Fila 1: Trimestre / Nivel Académico -->
                            <tr style="background-color: rgba(255,255,255,0.7); border-bottom: 2px solid rgba(49, 125, 146, 0.15);">
                                <th class="text-start sticky-col px-3 py-3" style="min-width: 250px; background-color: rgba(255,255,255,0.9); font-weight: 700; color: #1e293b;">Alumno</th>
                                @php
                                    // Agrupar fechas por su nivel académico para hacer colspan
                                    $nivelesAgrupados = [];
                                    foreach($fechas as $f) {
                                        $nId = $f['id_nivel_academico'];
                                        if(!isset($nivelesAgrupados[$nId])) {
                                            $nivelesAgrupados[$nId] = [
                                                'nombre' => $f['nombreNivel'],
                                                'count' => 0
                                            ];
                                        }
                                        $nivelesAgrupados[$nId]['count']++;
                                    }
                                @endphp
                                @foreach($nivelesAgrupados as $n)
                                    <th colspan="{{ $n['count'] }}" style="background-color: rgba(49, 125, 146, 0.25); color: rgb(38, 104, 123); font-weight: bold; border-left: 1px solid rgba(49, 125, 146, 0.15);">
                                        {{ $n['nombre'] }}
                                    </th>
                                @endforeach
                            </tr>
                            <!-- Fila 2: Fechas -->
                            <tr style="background-color: rgba(255,255,255,0.5);">
                                <th class="text-start sticky-col px-3 py-2" style="background-color: rgba(255,255,255,0.85); font-weight: 600; color: #475569; border-bottom: 1px solid rgba(49, 125, 146, 0.15);">Clave / Matrícula</th>
                                @foreach($fechas as $fIdx => $f)
                                    @php
                                         $isBgne = ($grupo['id_centroTrabajo'] == 3);
                                         $totalWeeks = count($fechas);
                                         $isEvaluation = false;
                                         $evalLabel = '';
                                         if ($isBgne) {
                                             if ($fIdx == 5 || $fIdx == 6) {
                                                 $isEvaluation = true;
                                                 $evalLabel = 'P.1';
                                             } elseif ($fIdx == $totalWeeks - 2 || $fIdx == $totalWeeks - 1) {
                                                 $isEvaluation = true;
                                                 $evalLabel = 'P.2';
                                             }
                                         }
                                    @endphp
                                    <th class="py-2 px-1" style="min-width: 75px; font-size: 0.72rem; font-weight: 600; color: #475569; border-left: 1px solid rgba(49, 125, 146, 0.15); border-bottom: 1px solid rgba(49, 125, 146, 0.15); @if($isEvaluation) background-color: rgba(226, 232, 240, 0.85); @endif">
                                         @if($isEvaluation)
                                             <div class="text-primary fw-bold" style="font-size: 0.62rem; line-height: 1; margin-bottom: 2px;">{{ $evalLabel }}</div>
                                         @endif
                                         {{ \Carbon\Carbon::parse($f['fecha'])->format('d-m') }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody id="tabla-matriz-body">
                            <!-- Renderizado dinámicamente en JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Agregar Alumno -->
<div class="modal fade" id="modalAgregarAlumno" tabindex="-1" aria-labelledby="modalAgregarAlumnoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; background-color: #ffffff; border: 1px solid rgba(49, 125, 146, 0.15) !important;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-dark fw-bold" id="modalAgregarAlumnoLabel">Agregar Nuevo Alumno</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-agregar-alumno" onsubmit="crearAlumno(event)">
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label for="input-nombre" class="form-label text-muted uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.5px;">NOMBRE(S)</label>
                        <input type="text" class="form-control text-dark" id="input-nombre" required placeholder="EJ. JUAN CARLOS" style="background: rgba(255, 255, 255, 0.5); border-radius: 10px; border: 1px solid rgba(49, 125, 146, 0.2); height: 42px;">
                    </div>
                    <div class="mb-3">
                        <label for="input-paterno" class="form-label text-muted uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.5px;">APELLIDO PATERNO</label>
                        <input type="text" class="form-control text-dark" id="input-paterno" required placeholder="EJ. PÉREZ" style="background: rgba(255, 255, 255, 0.5); border-radius: 10px; border: 1px solid rgba(49, 125, 146, 0.2); height: 42px;">
                    </div>
                    <div class="mb-3">
                        <label for="input-materno" class="form-label text-muted uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.5px;">APELLIDO MATERNO</label>
                        <input type="text" class="form-control text-dark" id="input-materno" placeholder="EJ. GÓMEZ" style="background: rgba(255, 255, 255, 0.5); border-radius: 10px; border: 1px solid rgba(49, 125, 146, 0.2); height: 42px;">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal" style="border-radius: 10px; font-size: 0.8rem; background-color: rgba(0,0,0,0.05); border: none; color: #1e293b;">Cancelar</button>
                    <button type="submit" class="btn btn-azul px-4 py-2" style="border-radius: 10px; font-size: 0.8rem; border: none;">Guardar Alumno</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Estilo del botón toggle */
    .btn-premium-view {
        color: #94a3b8;
        border: none !important;
        background: transparent;
        transition: 0.2s;
    }
    .btn-premium-view.active {
        background-color: rgb(49, 125, 146) !important;
        color: #fff !important;
        box-shadow: 0 4px 8px rgba(49, 125, 146, 0.25);
    }
    .btn-premium-view:hover:not(.active) {
        color: #cbd5e1;
        background-color: rgba(255,255,255,0.04);
    }

    /* Botón premium secundario (Agregar Alumno) */
    .btn-premium-secondary {
        background: rgba(49, 125, 146, 0.1);
        border: 1px solid rgba(49, 125, 146, 0.25) !important;
        color: rgb(38, 104, 123) !important;
        transition: all 0.25s;
    }
    .btn-premium-secondary:hover {
        background: rgba(49, 125, 146, 0.2);
        border-color: rgba(49, 125, 146, 0.5) !important;
        color: rgb(38, 104, 123) !important;
    }

    /* Forzar border-collapse: separate para habilitar columnas sticky */
    .table-responsive table {
        border-collapse: separate !important;
        border-spacing: 0 !important;
    }

    /* Columna congelada (Sticky) en la matriz */
    .sticky-col {
        position: sticky !important;
        left: 0 !important;
        z-index: 100 !important;
        background-color: #ffffff !important;
        box-shadow: 4px 0 8px -4px rgba(0,0,0,0.15) !important;
    }
    tr:hover .sticky-col {
        background-color: #f0f7f9 !important;
    }

    /* Botones circulares de la matriz */
    .btn-circle-status {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 1px solid rgba(255,255,255,0.15);
        background: transparent;
        color: #94a3b8;
        font-size: 0.72rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-circle-status.status-A {
        background-color: #22c55e !important;
        border-color: #22c55e !important;
        color: #fff !important;
        box-shadow: 0 0 6px rgba(34,197,94,0.4);
    }
    .btn-circle-status.status-F {
        background-color: #ef4444 !important;
        border-color: #ef4444 !important;
        color: #fff !important;
        box-shadow: 0 0 6px rgba(239,68,68,0.4);
    }
    .btn-circle-status.status-R {
        background-color: #f97316 !important;
        border-color: #f97316 !important;
        color: #fff !important;
        box-shadow: 0 0 6px rgba(249,115,22,0.4);
    }
    .btn-circle-status.status-J {
        background-color: rgb(49, 125, 146) !important;
        border-color: rgb(49, 125, 146) !important;
        color: #fff !important;
        box-shadow: 0 0 6px rgba(49, 125, 146, 0.4);
    }

    /* ========================================================
       NUEVO DISEÑO: Tarjetas de Asistencia (Inspirado en App Móvil)
       ======================================================== */
    .attendance-card {
        background: #ffffff;
        border: 1.5px solid #edf2f7;
        border-radius: 18px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .attendance-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
        border-color: rgba(2, 132, 199, 0.35);
    }

    /* Píldora redondeada con número de lista (azul estilo imagen 2) */
    .badge-lista {
        min-width: 58px;
        height: 34px;
        padding: 0 12px;
        border-radius: 20px;
        border: 2px solid #0284c7;
        color: #0284c7;
        background-color: #f0f9ff;
        font-weight: 800;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        letter-spacing: -0.01em;
        flex-shrink: 0;
        box-shadow: 0 2px 5px rgba(2, 132, 199, 0.1);
    }

    /* Nombre del Alumno */
    .alumno-nombre-card {
        font-size: 1.02rem;
        font-weight: 800;
        color: #0f172a;
        text-transform: uppercase;
        letter-spacing: 0.2px;
        line-height: 1.25;
    }

    /* Subtítulo del Alumno */
    .alumno-subtitulo-card {
        font-size: 0.82rem;
        color: #64748b;
        font-weight: 500;
        margin-top: 3px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
    }

    /* Grupo de botones de asistencia */
    .attendance-buttons-group {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    /* Botón individual de asistencia */
    .btn-attendance-opt {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 7px 16px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.4px;
        border: 1.5px solid #cbd5e1;
        background-color: #ffffff;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
        user-select: none;
        min-height: 38px;
    }
    .btn-attendance-opt:hover:not(:disabled) {
        background-color: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
        transform: translateY(-1px);
    }
    .btn-attendance-opt:active:not(:disabled) {
        transform: translateY(0);
    }
    .btn-attendance-opt:disabled {
        opacity: 0.55;
        cursor: not-allowed;
    }

    /* Estados Activos según la referencia */
    /* ✓ ASIS. (Verde sólido) */
    .btn-attendance-opt.btn-opt-asis.active {
        background-color: #15803d !important;
        border-color: #15803d !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(21, 128, 61, 0.35);
    }

    /* ✕ FALT. (Rojo sólido) */
    .btn-attendance-opt.btn-opt-falt.active {
        background-color: #dc2626 !important;
        border-color: #dc2626 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.35);
    }

    /* ⏱ RETA. (Ámbar sólido) */
    .btn-attendance-opt.btn-opt-reta.active {
        background-color: #d97706 !important;
        border-color: #d97706 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(217, 119, 6, 0.35);
    }

    /* 📄 JUST. (Azul sólido) */
    .btn-attendance-opt.btn-opt-just.active {
        background-color: #2563eb !important;
        border-color: #2563eb !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
    }

    /* Campo integrado de observaciones */
    .attendance-note-input {
        background-color: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.82rem;
        height: 38px;
        padding: 6px 14px;
        color: #1e293b;
        transition: all 0.2s ease;
    }
    .attendance-note-input:focus {
        background-color: #ffffff;
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        outline: none;
    }
</style>

<script>
    // 🔍 Interceptores globales para reportar cualquier error visualmente
    window.addEventListener('error', function(e) {
        const div = document.createElement('div');
        div.className = 'alert alert-danger m-0 border-0 rounded-0';
        div.style.position = 'fixed';
        div.style.top = '0';
        div.style.left = '0';
        div.style.width = '100%';
        div.style.zIndex = '999999';
        div.style.fontFamily = 'monospace';
        div.style.fontSize = '0.85rem';
        div.innerHTML = '<strong>⚠️ ERROR DE JAVASCRIPT:</strong> ' + e.message + ' en ' + e.filename + ':' + e.lineno;
        document.body.appendChild(div);
    });

    window.addEventListener('unhandledrejection', function(e) {
        const div = document.createElement('div');
        div.className = 'alert alert-warning m-0 border-0 rounded-0';
        div.style.position = 'fixed';
        div.style.top = '0';
        div.style.left = '0';
        div.style.width = '100%';
        div.style.zIndex = '999999';
        div.style.fontFamily = 'monospace';
        div.style.fontSize = '0.85rem';
        div.innerHTML = '<strong>⚠️ EXCEPCIÓN NO CAPTURADA (PROMISE):</strong> ' + (e.reason ? (e.reason.message || e.reason) : 'Desconocido');
        document.body.appendChild(div);
    });

    function mostrarErrorPantalla(e, origen) {
        const div = document.createElement('div');
        div.className = 'alert alert-danger m-0 border-0 rounded-0';
        div.style.position = 'fixed';
        div.style.top = '0';
        div.style.left = '0';
        div.style.width = '100%';
        div.style.zIndex = '999999';
        div.style.fontFamily = 'monospace';
        div.style.fontSize = '0.85rem';
        div.innerHTML = '<strong>⚠️ ERROR EN ' + origen + ':</strong> ' + e.message + (e.stack ? '<br><small style="font-size:0.75rem;">' + e.stack.split('\\n').slice(0, 3).join('<br>') + '</small>' : '');
        document.body.appendChild(div);
    }

    const normalizeStr = str => (str || '').normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();

    // Variables de datos inyectadas desde PHP
    const alumnosRaw = @json($alumnos);
    const alumnos = Array.isArray(alumnosRaw) ? alumnosRaw : Object.values(alumnosRaw || {});

    const fechasRaw = @json($fechas);
    const fechas = Array.isArray(fechasRaw) ? fechasRaw : Object.values(fechasRaw || {});

    const asistenciasRaw = @json($asistencias);
    const asistenciasGuardadas = Array.isArray(asistenciasRaw) ? asistenciasRaw : Object.values(asistenciasRaw || {});

    const grupo = @json($grupo);

    let vistaActual = 'LISTADO'; // 'LISTADO' o 'MATRIZ'
    let fechaSeleccionada = '';

    // Estructura de estado local: { [fecha]: { [idAlumno]: { estatus, observaciones } } }
    const localState = {};
    // Registro de modificaciones para guardar únicamente lo cambiado
    const modifiedState = {};

    try {
        if (fechas.length > 0) {
            fechaSeleccionada = fechas[0].fecha;
        }

        // Inicializar el estado local
        fechas.forEach(f => {
            localState[f.fecha] = {};
            alumnos.forEach(al => {
                localState[f.fecha][al.idAlumno] = {
                    estatus: null,
                    observaciones: '',
                    justificado_admin: false
                };
            });
        });

        // Cargar asistencias existentes de la base de datos
        asistenciasGuardadas.forEach(as => {
            if (localState[as.fecha] && localState[as.fecha][as.id_alumno]) {
                localState[as.fecha][as.id_alumno].estatus = as.estatus;
                localState[as.fecha][as.id_alumno].observaciones = as.observaciones;
                localState[as.fecha][as.id_alumno].justificado_admin = as.justificado_admin || false;
            }
        });
    } catch (e) {
        console.error("Error al inicializar el estado de asistencias:", e);
    }

    function init() {
        try {
            // Mover modal al body para evitar el bug del fondo gris de Bootstrap
            const modalEl = document.getElementById('modalAgregarAlumno');
            if (modalEl) {
                document.body.appendChild(modalEl);
            }

            // Seleccionar fecha de hoy si está programada, o la más cercana
            const hoyStr = new Date().toISOString().split('T')[0];
            const tieneHoy = fechas.some(f => f.fecha === hoyStr);
            if (tieneHoy) {
                fechaSeleccionada = hoyStr;
            } else if (fechas.length > 0) {
                let closest = fechas[0];
                let minDiff = Math.abs(new Date(closest.fecha + 'T00:00:00') - new Date(hoyStr + 'T00:00:00'));
                
                for (let i = 1; i < fechas.length; i++) {
                    const diff = Math.abs(new Date(fechas[i].fecha + 'T00:00:00') - new Date(hoyStr + 'T00:00:00'));
                    if (diff < minDiff) {
                        minDiff = diff;
                        closest = fechas[i];
                    }
                }
                fechaSeleccionada = closest.fecha;
            }
            
            const selectFecha = document.getElementById('select-fecha');
            if (selectFecha) {
                selectFecha.value = fechaSeleccionada;
            }
            
            renderizar();
        } catch (e) {
            console.error("Error en la inicialización (init):", e);
            mostrarErrorPantalla(e, 'init');
        }
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }

    // Recarga la página para alternar materias
    function seleccionarMateria(idMateria) {
        const url = new URL(window.location.href);
        url.searchParams.set('id_materia', idMateria);
        window.location.href = url.toString();
    }

    // Renderiza la vista actual
    function renderizar() {
        const now = new Date();
        const todayStr = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-' + String(now.getDate()).padStart(2, '0');
        const userRole = @json(session('rol'));
        const esHoy = (fechaSeleccionada === todayStr);
        const edicionBloqueada = (userRole === 'DOCENTE' && !esHoy);

        const btnGuardar = document.getElementById('btn-guardar-asistencias');
        if (btnGuardar) {
            if (edicionBloqueada) {
                btnGuardar.disabled = true;
                btnGuardar.style.opacity = '0.65';
                btnGuardar.style.cursor = 'not-allowed';
                btnGuardar.title = 'No se pueden guardar asistencias de otras fechas.';
            } else {
                btnGuardar.disabled = false;
                btnGuardar.style.opacity = '1';
                btnGuardar.style.cursor = 'pointer';
                btnGuardar.title = '';
            }
        }

        if (vistaActual === 'LISTADO') {
            renderListado();
            actualizarProgreso();
        } else {
            renderMatriz();
        }
    }

    function cambiarVista(vista) {
        vistaActual = vista;
        
        // Cambiar active buttons
        document.getElementById('btn-vista-listado').classList.remove('active');
        document.getElementById('btn-vista-matriz').classList.remove('active');
        
        const fechasCard = document.getElementById('selector-fechas-card');
        const progresoCard = document.getElementById('progreso-registro-card');
        const viewListado = document.getElementById('contenedor-vista-listado');
        const viewMatriz = document.getElementById('contenedor-vista-matriz');
        
        const btnPrint = document.getElementById('btn-imprimir-asistencias');
        const btnExcel = document.getElementById('btn-excel-asistencias');

        if (vista === 'LISTADO') {
            document.getElementById('btn-vista-listado').classList.add('active');
            fechasCard.classList.remove('d-none');
            document.getElementById('div-select-fecha').classList.remove('d-none');
            document.getElementById('div-nav-fecha').classList.remove('d-none');
            progresoCard.classList.remove('d-none');
            viewListado.classList.remove('d-none');
            viewMatriz.classList.add('d-none');
            
            if (btnPrint) btnPrint.classList.add('d-none');
            if (btnExcel) btnExcel.classList.add('d-none');
        } else {
            document.getElementById('btn-vista-matriz').classList.add('active');
            fechasCard.classList.remove('d-none');
            document.getElementById('div-select-fecha').classList.add('d-none');
            document.getElementById('div-nav-fecha').classList.add('d-none');
            progresoCard.classList.add('d-none');
            viewListado.classList.add('d-none');
            viewMatriz.classList.remove('d-none');
            
            if (btnPrint) btnPrint.classList.remove('d-none');
            if (btnExcel) btnExcel.classList.remove('d-none');
        }

        renderizar();
    }

    function seleccionarFecha(fecha) {
        fechaSeleccionada = fecha;
        renderizar();
    }

    function navegarFecha(direccion) {
        const idx = fechas.findIndex(f => f.fecha === fechaSeleccionada);
        if (idx === -1) return;
        const newIdx = idx + direccion;
        if (newIdx >= 0 && newIdx < fechas.length) {
            fechaSeleccionada = fechas[newIdx].fecha;
            document.getElementById('select-fecha').value = fechaSeleccionada;
            renderizar();
        }
    }

    // 🔍 BUSCADOR DE ALUMNOS (diacritic-insensitive)
    function filtrarAlumnos() {
        const query = normalizeStr(document.getElementById('buscadorAlumno').value.trim());

        if (vistaActual === 'LISTADO') {
            let visibles = 0;
            document.querySelectorAll('.attendance-card').forEach(card => {
                const nombre = card.getAttribute('data-nombre') || '';
                if (nombre.includes(query)) {
                    card.classList.remove('d-none');
                    visibles++;
                } else {
                    card.classList.add('d-none');
                }
            });
            const mensajeVacio = document.getElementById('mensaje-vacio');
            if (visibles === 0 && alumnos.length > 0) {
                mensajeVacio.classList.remove('d-none');
            } else {
                mensajeVacio.classList.add('d-none');
            }

            const labelTotal = document.getElementById('label-total-alumnos');
            if (labelTotal) {
                labelTotal.innerText = `${visibles} de ${alumnos.length} alumnos`;
            }
        } else {
            // Filtrar tabla matriz
            document.querySelectorAll('.tabla-matriz-fila').forEach(row => {
                const nombre = row.getAttribute('data-nombre') || '';
                if (nombre.includes(query)) {
                    row.classList.remove('d-none');
                } else {
                    row.classList.add('d-none');
                }
            });
        }
    }

    // Renderizar Listado de Alumnos en Tarjetas Modernas para la fecha seleccionada
    function renderListado() {
        try {
            const container = document.getElementById('lista-alumnos-cards');
            if (!container) return;
            container.innerHTML = '';

            // Actualizar etiqueta de fecha seleccionada
            const labelFecha = document.getElementById('label-fecha-seleccionada');
            if (labelFecha) {
                const dateParts = fechaSeleccionada.split('-');
                if (dateParts.length === 3) {
                    labelFecha.innerText = `Fecha: ${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;
                } else {
                    labelFecha.innerText = `Fecha: ${fechaSeleccionada}`;
                }
            }

            if (alumnos.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5 bg-white rounded-4 shadow-sm border">
                        <i class="fa-solid fa-users-slash text-muted mb-3" style="font-size: 2.5rem; opacity: 0.5;"></i>
                        <h6 class="text-dark fw-bold">No hay alumnos registrados en este grupo</h6>
                    </div>
                `;
                document.getElementById('mensaje-vacio').classList.add('d-none');
                return;
            } else {
                document.getElementById('mensaje-vacio').classList.add('d-none');
            }

            const query = normalizeStr(document.getElementById('buscadorAlumno').value.trim());
            let visibles = 0;

            const now = new Date();
            const todayStr = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-' + String(now.getDate()).padStart(2, '0');
            const esHoy = (fechaSeleccionada === todayStr);
            const userRole = @json(session('rol'));
            const esDocente = (userRole === 'DOCENTE');
            const edicionBloqueada = esDocente && !esHoy;

            alumnos.forEach((al, index) => {
                const nombreCompleto = `${al.apPaternoAlumno} ${al.apMaternoAlumno || ''} ${al.nombreAlumno}`.trim();
                const nombreNormalizado = normalizeStr(nombreCompleto);
                const isVisible = query.length === 0 || nombreNormalizado.includes(query);
                if (isVisible) visibles++;

                const record = (localState[fechaSeleccionada] || {})[al.idAlumno] || { estatus: null, observaciones: '', justificado_admin: false };
                const estatus = record.estatus;
                const justificadoAdmin = record.justificado_admin || false;
                const observaciones = record.observaciones || '';

                const isDisabled = justificadoAdmin || edicionBloqueada;
                const disableAttr = isDisabled ? 'disabled' : '';

                const numLista = al.numero_lista || al.num_lista || (index + 1);

                const card = document.createElement('div');
                card.className = `attendance-card student-row ${isVisible ? '' : 'd-none'}`;
                card.setAttribute('data-nombre', nombreNormalizado);
                card.setAttribute('data-id', al.idAlumno);

                card.innerHTML = `
                    <div class="p-3 p-md-4">
                        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                            <!-- Datos del Alumno -->
                            <div class="d-flex align-items-center gap-3">
                                <div class="badge-lista" title="Número de lista">
                                    ${numLista}
                                </div>
                                <div>
                                    <div class="alumno-nombre-card">${nombreCompleto}</div>
                                    <div class="alumno-subtitulo-card">
                                        <span>Número de lista: ${numLista}</span>
                                        ${al.matricula ? `<span class="opacity-50">•</span><span>Matrícula: ${al.matricula}</span>` : ''}
                                    </div>
                                </div>
                            </div>

                            <!-- Opciones de Asistencia y Observaciones -->
                            <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2 justify-content-lg-end flex-wrap">
                                <!-- Botones de Asistencia directos -->
                                <div class="attendance-buttons-group">
                                    <button type="button" 
                                            class="btn-attendance-opt btn-opt-asis ${estatus === 'A' ? 'active' : ''}" 
                                            data-status="A"
                                            ${disableAttr}
                                            title="Asistencia"
                                            onclick="seleccionarEstatusListado(${al.idAlumno}, 'A')">
                                        <i class="fa-solid fa-check"></i> ASIS.
                                    </button>
                                    <button type="button" 
                                            class="btn-attendance-opt btn-opt-falt ${estatus === 'F' ? 'active' : ''}" 
                                            data-status="F"
                                            ${disableAttr}
                                            title="Falta"
                                            onclick="seleccionarEstatusListado(${al.idAlumno}, 'F')">
                                        <i class="fa-solid fa-xmark"></i> FALT.
                                    </button>
                                    <button type="button" 
                                            class="btn-attendance-opt btn-opt-reta ${estatus === 'R' ? 'active' : ''}" 
                                            data-status="R"
                                            ${disableAttr}
                                            title="Retardo"
                                            onclick="seleccionarEstatusListado(${al.idAlumno}, 'R')">
                                        <i class="fa-regular fa-clock"></i> RETA.
                                    </button>
                                    <button type="button" 
                                            class="btn-attendance-opt btn-opt-just ${estatus === 'J' ? 'active' : ''}" 
                                            data-status="J"
                                            ${disableAttr}
                                            title="Justificado"
                                            onclick="seleccionarEstatusListado(${al.idAlumno}, 'J')">
                                        <i class="fa-regular fa-file-lines"></i> JUST.
                                    </button>
                                </div>

                                <!-- Input de Observaciones / Nota -->
                                <div class="attendance-note-container" style="min-width: 170px; max-width: 240px;">
                                    <input type="text" 
                                           class="form-control form-control-sm attendance-note-input" 
                                           placeholder="Agregar nota..." 
                                           value="${(observaciones || '').replace(/"/g, '&quot;')}" 
                                           ${disableAttr}
                                           title="Observaciones del alumno"
                                           oninput="actualizarObservacionesListado(${al.idAlumno}, this.value)">
                                </div>
                            </div>
                        </div>

                        <!-- Avisos de restricción / Justificación -->
                        ${justificadoAdmin ? `
                            <div class="mt-2 pt-2 border-top border-light-subtle d-flex align-items-center gap-2 text-primary fw-semibold" style="font-size: 0.76rem;">
                                <i class="fa-solid fa-lock"></i>
                                <span>Asistencia justificada por Dirección / Administración (Edición bloqueada)</span>
                            </div>
                        ` : ''}
                        ${(!justificadoAdmin && edicionBloqueada) ? `
                            <div class="mt-2 pt-2 border-top border-light-subtle d-flex align-items-center gap-2 text-danger fw-semibold" style="font-size: 0.76rem;">
                                <i class="fa-solid fa-ban"></i>
                                <span>Sólo lectura: Los docentes sólo pueden registrar asistencia para la fecha de hoy.</span>
                            </div>
                        ` : ''}
                    </div>
                `;
                container.appendChild(card);
            });

            const mensajeVacio = document.getElementById('mensaje-vacio');
            if (visibles === 0 && alumnos.length > 0) {
                mensajeVacio.classList.remove('d-none');
            } else {
                mensajeVacio.classList.add('d-none');
            }

            const labelTotal = document.getElementById('label-total-alumnos');
            if (labelTotal) {
                labelTotal.innerText = `${visibles} de ${alumnos.length} alumnos`;
            }
        } catch (e) {
            console.error("Error en renderListado:", e);
            mostrarErrorPantalla(e, 'renderListado');
        }
    }

    // Selecciona o deselecciona directamente un estado para un alumno
    function seleccionarEstatusListado(idAlumno, estatusDeseado) {
        if (!localState[fechaSeleccionada]) {
            localState[fechaSeleccionada] = {};
        }
        if (!localState[fechaSeleccionada][idAlumno]) {
            localState[fechaSeleccionada][idAlumno] = { estatus: null, observaciones: '', justificado_admin: false };
        }

        const record = localState[fechaSeleccionada][idAlumno];

        // Bloquear si justificado por admin o si es docente y no es hoy
        if (record.justificado_admin) return;
        const now = new Date();
        const todayStr = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-' + String(now.getDate()).padStart(2, '0');
        const userRole = @json(session('rol'));
        if (userRole === 'DOCENTE' && fechaSeleccionada !== todayStr) return;

        // Si ya está activo ese estado, se deselecciona (null)
        if (record.estatus === estatusDeseado) {
            record.estatus = null;
        } else {
            record.estatus = estatusDeseado;
        }

        // Registrar modificación
        if (!modifiedState[fechaSeleccionada]) modifiedState[fechaSeleccionada] = {};
        modifiedState[fechaSeleccionada][idAlumno] = record;

        // Actualizar visualmente la tarjeta
        actualizarBotonesTarjeta(idAlumno, record.estatus);

        // Actualizar barra de progreso
        actualizarProgreso();
    }

    // Actualiza la clase activa en los 4 botones de una tarjeta
    function actualizarBotonesTarjeta(idAlumno, estatus) {
        const card = document.querySelector(`.attendance-card[data-id="${idAlumno}"]`);
        if (!card) return;

        card.querySelectorAll('.btn-attendance-opt').forEach(btn => {
            const opt = btn.getAttribute('data-status');
            if (opt === estatus) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
    }

    // Marcar todos los alumnos con un estado ('A' o null para limpiar)
    function marcarTodos(estatusDeseado) {
        const now = new Date();
        const todayStr = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-' + String(now.getDate()).padStart(2, '0');
        const userRole = @json(session('rol'));
        if (userRole === 'DOCENTE' && fechaSeleccionada !== todayStr) {
            Swal.fire({
                icon: 'info',
                title: 'Edición restringida',
                text: 'Como docente sólo puedes registrar asistencias correspondientes al día de hoy.',
                confirmButtonColor: '#0284c7'
            });
            return;
        }

        if (!localState[fechaSeleccionada]) localState[fechaSeleccionada] = {};
        if (!modifiedState[fechaSeleccionada]) modifiedState[fechaSeleccionada] = {};

        alumnos.forEach(al => {
            if (!localState[fechaSeleccionada][al.idAlumno]) {
                localState[fechaSeleccionada][al.idAlumno] = { estatus: null, observaciones: '', justificado_admin: false };
            }
            const rec = localState[fechaSeleccionada][al.idAlumno];
            if (rec.justificado_admin) return;

            rec.estatus = estatusDeseado;
            modifiedState[fechaSeleccionada][al.idAlumno] = rec;
            actualizarBotonesTarjeta(al.idAlumno, estatusDeseado);
        });

        actualizarProgreso();
    }

    // Compatibilidad para cualquier llamada residual
    function ciclarEstatusListado(btn, idAlumno) {
        const record = (localState[fechaSeleccionada] || {})[idAlumno] || { estatus: null };
        let nuevo = 'A';
        if (record.estatus === 'A') nuevo = 'F';
        else if (record.estatus === 'F') nuevo = 'R';
        else if (record.estatus === 'R') nuevo = 'J';
        else if (record.estatus === 'J') nuevo = null;
        seleccionarEstatusListado(idAlumno, nuevo);
    }

    // Actualiza las observaciones en tiempo real
    function actualizarObservacionesListado(idAlumno, valor) {
        if (!localState[fechaSeleccionada]) {
            localState[fechaSeleccionada] = {};
        }
        if (!localState[fechaSeleccionada][idAlumno]) {
            localState[fechaSeleccionada][idAlumno] = { estatus: null, observaciones: '', justificado_admin: false };
        }

        const record = localState[fechaSeleccionada][idAlumno];
        record.observaciones = valor;

        // Registrar modificación
        if (!modifiedState[fechaSeleccionada]) modifiedState[fechaSeleccionada] = {};
        modifiedState[fechaSeleccionada][idAlumno] = record;
    }

    // Actualiza la barra de progreso para la fecha seleccionada
    function actualizarProgreso() {
        const total = alumnos.length;
        if (total === 0) return;

        let completados = 0;
        const dateRecord = localState[fechaSeleccionada] || {};
        alumnos.forEach(al => {
            const record = dateRecord[al.idAlumno] || { estatus: null };
            if (record.estatus !== null) {
                completados++;
            }
        });

        const porcentaje = Math.round((completados / total) * 100);
        
        const bar = document.getElementById('progreso-bar');
        const text = document.getElementById('progreso-texto');
        const label = document.getElementById('progreso-status-label');

        bar.style.width = `${porcentaje}%`;
        text.innerText = `${completados} / ${total} Alumnos`;

        if (completados === total) {
            label.innerText = '¡Todos los alumnos completados!';
            label.className = 'text-success fw-semibold';
            bar.className = 'progress-bar progress-bar-striped bg-success';
        } else {
            const faltantes = total - completados;
            label.innerText = `Faltan ${faltantes} registros por completar`;
            label.className = 'text-info fw-semibold';
            bar.className = 'progress-bar progress-bar-striped progress-bar-animated';
            bar.style.backgroundColor = 'rgb(49, 125, 146)';
        }
    }

    // Renderizar Matriz Completa
    function renderMatriz() {
        try {
            const tbody = document.getElementById('tabla-matriz-body');
            tbody.innerHTML = '';

            if (alumnos.length === 0) {
                tbody.innerHTML = `<tr><td colspan="${fechas.length + 1}" class="text-center py-4 text-muted">No hay alumnos registrados.</td></tr>`;
                return;
            }

            const query = normalizeStr(document.getElementById('buscadorAlumno').value.trim());

            alumnos.forEach((al, index) => {
                const nombreCompleto = `${al.apPaternoAlumno} ${al.apMaternoAlumno || ''} ${al.nombreAlumno}`;
                const nombreNormalizado = normalizeStr(nombreCompleto);
                const isVisible = query.length === 0 || nombreNormalizado.includes(query);

                const tr = document.createElement('tr');
                tr.className = `tabla-matriz-fila ${isVisible ? '' : 'd-none'}`;
                tr.setAttribute('data-nombre', nombreNormalizado);
                tr.innerHTML = `
                    <td class="text-start sticky-col px-3 py-2 fw-semibold" style="background-color: rgba(255, 255, 255, 0.95); color: #1e293b; border-right: 1px solid rgba(49, 125, 146, 0.15); border-bottom: 1px solid rgba(49, 125, 146, 0.08);">
                        <div style="font-size: 0.82rem; text-transform: uppercase;">
                            ${index + 1}. ${al.apPaternoAlumno} ${al.apMaternoAlumno || ''} ${al.nombreAlumno}
                        </div>
                        <small class="text-muted" style="font-size: 0.65rem;">LISTA: ${al.idAlumno}</small>
                    </td>
                `;

                fechas.forEach((f, fIdx) => {
                    const record = localState[f.fecha][al.idAlumno];
                    const estatus = record.estatus || '';
                    const justificadoAdmin = record.justificado_admin || false;

                    const now = new Date();
                    const todayStr = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-' + String(now.getDate()).padStart(2, '0');
                    const esHoy = (f.fecha === todayStr);
                    const userRole = @json(session('rol'));
                    const esDocente = (userRole === 'DOCENTE');
                    const edicionBloqueada = esDocente && !esHoy;

                    const isDisabled = justificadoAdmin || edicionBloqueada;

                    // Sombrear columnas de evaluación si es BGNE
                    const isBgne = (grupo.id_centroTrabajo === 3);
                    const totalWeeks = fechas.length;
                    let isEvaluation = false;
                    if (isBgne) {
                        if (fIdx === 5 || fIdx === 6 || fIdx === totalWeeks - 2 || fIdx === totalWeeks - 1) {
                            isEvaluation = true;
                        }
                    }

                    const td = document.createElement('td');
                    td.className = 'p-1';
                    if (isEvaluation) {
                        td.style.backgroundColor = 'rgba(226, 232, 240, 0.6)';
                    }

                    let btnTitle = '';
                    if (justificadoAdmin) btnTitle = 'Justificado por Administración (Bloqueado)';
                    else if (edicionBloqueada) btnTitle = 'Sólo lectura (No es hoy)';

                    let simbolo = '-';
                    if (estatus === 'A') simbolo = ':.';
                    else if (estatus === 'F') simbolo = '\\';
                    else if (estatus === 'R') simbolo = '.';
                    else if (estatus === 'J') simbolo = 'J';

                    td.innerHTML = `
                        <button type="button" 
                                class="btn-circle-status ${estatus ? 'status-' + estatus : ''}" 
                                ${isDisabled ? 'disabled style="opacity: 0.7; cursor: not-allowed;"' : ''} 
                                title="${btnTitle}"
                                onclick="ciclarEstatusMatriz(this, ${al.idAlumno}, '${f.fecha}')">
                            ${simbolo}
                        </button>
                    `;
                    tr.appendChild(td);
                });

                tbody.appendChild(tr);
            });
        } catch (e) {
            console.error("Error en renderMatriz:", e);
            mostrarErrorPantalla(e, 'renderMatriz');
        }
    }

    // Cicla a través de los estados: '-' -> 'A' -> 'F' -> 'R' -> 'J' -> '-'
    function ciclarEstatusMatriz(btn, idAlumno, fecha) {
        const record = localState[fecha][idAlumno];
        
        // Bloquear si justificado por admin o si es docente y no es hoy
        if (record.justificado_admin) return;
        const now = new Date();
        const todayStr = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-' + String(now.getDate()).padStart(2, '0');
        const userRole = @json(session('rol'));
        if (userRole === 'DOCENTE' && fecha !== todayStr) return;

        let nuevo = null;

        if (record.estatus === null) nuevo = 'A';
        else if (record.estatus === 'A') nuevo = 'F';
        else if (record.estatus === 'F') nuevo = 'R';
        else if (record.estatus === 'R') nuevo = 'J';
        else if (record.estatus === 'J') nuevo = null;

        record.estatus = nuevo;

        // Registrar modificación
        if (!modifiedState[fecha]) modifiedState[fecha] = {};
        modifiedState[fecha][idAlumno] = record;

        // Actualizar visualmente el botón
        let simbolo = '-';
        if (nuevo === 'A') simbolo = ':.';
        else if (nuevo === 'F') simbolo = '\\';
        else if (nuevo === 'R') simbolo = '.';
        else if (nuevo === 'J') simbolo = 'J';

        btn.className = `btn-circle-status ${nuevo ? 'status-' + nuevo : ''}`;
        btn.innerText = simbolo;
    }

    // GUARDAR CAMBIOS MASIVOS POR AJAX
    function guardarAsistencias() {
        const asistenciasToSend = [];

        // Recopilar todos los registros modificados del modifiedState
        for (const fecha in modifiedState) {
            for (const idAlumno in modifiedState[fecha]) {
                const rec = modifiedState[fecha][idAlumno];
                const fObj = fechas.find(fe => fe.fecha === fecha);
                asistenciasToSend.push({
                    id_alumno: parseInt(idAlumno),
                    fecha: fecha,
                    id_nivel_academico: fObj ? fObj.id_nivel_academico : null,
                    estatus: rec.estatus,
                    observaciones: rec.observaciones
                });
            }
        }

        // Si no hay cambios, avisar al usuario
        if (asistenciasToSend.length === 0) {
            Swal.fire({
                icon: 'info',
                title: 'Sin cambios',
                text: 'No has realizado ninguna modificación en las asistencias.',
                confirmButtonColor: 'rgb(49, 125, 146)'
            });
            return;
        }

        // Mostrar indicador de carga
        Swal.fire({
            title: 'Guardando asistencias...',
            html: 'Por favor, espera un momento.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Enviar petición POST a Laravel
        fetch("{{ route('asistencias_alumnos.guardar') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                id_grupo: grupo.id,
                id_materia: @json($selected_materia_id),
                id_docente: @json(session('rol') === 'DOCENTE' ? session('id_docente') : null),
                asistencias: asistenciasToSend
            })
        })
        .then(res => res.json())
        .then(data => {
            Swal.close();
            if (data.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al guardar',
                    text: data.error,
                    confirmButtonColor: '#ef4444'
                });
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'Guardado',
                    text: 'Las asistencias se han actualizado correctamente en el sistema.',
                    confirmButtonColor: '#22c55e'
                }).then(() => {
                    // Limpiar historial de modificaciones locales
                    for (const prop in modifiedState) { delete modifiedState[prop]; }
                    // Recargar datos y refrescar la vista
                    window.location.reload();
                });
            }
        })
        .catch(err => {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error de Red',
                text: 'No se pudo establecer comunicación con el servidor. Inténtalo de nuevo.',
                confirmButtonColor: '#ef4444'
            });
            console.error(err);
        });
    }

    // CREAR Y MATRICULAR NUEVO ALUMNO EN EL GRUPO
    function crearAlumno(e) {
        e.preventDefault();

        const nombre = document.getElementById('input-nombre').value.trim();
        const paterno = document.getElementById('input-paterno').value.trim();
        const materno = document.getElementById('input-materno').value.trim();

        if (!nombre || !paterno) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'El nombre y el apellido paterno son obligatorios.',
                confirmButtonColor: '#f97316'
            });
            return;
        }

        // Ocultar modal
        const modalEl = document.getElementById('modalAgregarAlumno');
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();

        Swal.fire({
            title: 'Registrando alumno...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Payload requerido por el backend Laravel/Flask
        const payload = {
            alumno: {
                nombre: nombre,
                apPaterno: paterno,
                apMaterno: materno,
                statusAlumno: 'ACTIVO'
            },
            academico: {
                id_centroTrabajo: grupo.id_centroTrabajo,
                id_nivel_academico: grupo.id_nivel_academico_actual,
                id_generacion: grupo.idGeneracion,
                id_grupo: grupo.id
            }
        };

        fetch("/alumnos", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            Swal.close();
            if (data.success === false) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Error al guardar alumno.',
                    confirmButtonColor: '#ef4444'
                }).then(() => {
                    modal.show();
                });
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'Alumno Agregado',
                    text: 'El alumno se ha matriculado en este grupo correctamente.',
                    confirmButtonColor: '#22c55e'
                }).then(() => {
                    // Recargar la pantalla para actualizar la lista de alumnos
                    window.location.reload();
                });
            }
        })
        .catch(err => {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error de Red',
                text: 'Hubo un error al conectar con el servidor para registrar el alumno.',
                confirmButtonColor: '#ef4444'
            }).then(() => {
                modal.show();
            });
            console.error(err);
        });
    }

    function imprimirReporte() {
        const printable = document.getElementById('printable-report');
        if (!printable) return;

        const selectMateria = document.getElementById('select-materia');
        const selectedMateriaText = selectMateria ? selectMateria.options[selectMateria.selectedIndex].text : '';
        
        let subjectName = selectedMateriaText;
        let teacherName = '';
        const matchMat = selectedMateriaText.match(/^(.*?)\s*\((.*?)\)$/);
        if (matchMat) {
            subjectName = matchMat[1].trim();
            teacherName = matchMat[2].trim();
        }
        
        const groupClave = @json($grupo['clave']);
        const levelName = @json($grupo['nombre_nivel'] ?? '');
        
        const meses = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
        const groupMeses = [];
        fechas.forEach((f, idx) => {
            const dateObj = new Date(f.fecha + 'T00:00:00');
            const monthName = meses[dateObj.getMonth()];
            if (groupMeses.length === 0 || groupMeses[groupMeses.length - 1].month !== monthName) {
                groupMeses.push({ month: monthName, colspan: 1 });
            } else {
                groupMeses[groupMeses.length - 1].colspan++;
            }
        });
        
        let monthColsHtml = '';
        groupMeses.forEach(m => {
            monthColsHtml += `<th colspan="${m.colspan}" style="border: 1px solid black; padding: 4px; font-weight: bold; background-color: #f1f5f9; text-align: center;">${m.month}</th>`;
        });
        
        let dateColsHtml = '';
        fechas.forEach((f, fIdx) => {
            const dateObj = new Date(f.fecha + 'T00:00:00');
            const dayNum = dateObj.getDate();
            const isBgne = (@json($grupo['id_centroTrabajo']) == 3);
            const totalWeeks = fechas.length;
            let evalLabel = '';
            if (isBgne) {
                if (fIdx === 5 || fIdx === 6) evalLabel = 'P.1';
                else if (fIdx === totalWeeks - 2 || fIdx === totalWeeks - 1) evalLabel = 'P.2';
            }
            
            dateColsHtml += `
                <th style="border: 1px solid black; padding: 4px; font-size: 0.72rem; min-width: 25px; text-align: center;">
                    ${evalLabel ? `<div style="font-weight: bold; font-size: 0.58rem; color: #3b82f6;">${evalLabel}</div>` : ''}
                    ${dayNum}
                </th>`;
        });
        
        let dayColsHtml = '';
        const dayMap = ['D', 'L', 'M', 'M', 'J', 'V', 'S'];
        fechas.forEach(f => {
            const dateObj = new Date(f.fecha + 'T00:00:00');
            const initial = dayMap[dateObj.getDay()];
            dayColsHtml += `<th style="border: 1px solid black; padding: 4px; font-size: 0.72rem; text-align: center; background-color: #fafafa;">${initial}</th>`;
        });
        
        let rowsHtml = '';
        alumnos.forEach((al, index) => {
            let cellsHtml = '';
            fechas.forEach(f => {
                const record = localState[f.fecha][al.idAlumno];
                const estatus = record ? record.estatus : '';
                
                let simbolo = '';
                if (estatus === 'A') simbolo = ':.';
                else if (estatus === 'F') simbolo = '\\';
                else if (estatus === 'R') simbolo = '.';
                else if (estatus === 'J') simbolo = 'J';
                
                let cellColor = '';
                if (estatus === 'F') cellColor = 'color: red; font-weight: bold;';
                else if (estatus === 'J') cellColor = 'color: #0d9488; font-weight: bold;';
                
                cellsHtml += `<td style="border: 1px solid black; padding: 4px; text-align: center; ${cellColor}">${simbolo}</td>`;
            });
            
            rowsHtml += `
                <tr>
                    <td style="border: 1px solid black; padding: 6px 12px; text-align: left; text-transform: uppercase; white-space: nowrap; font-size: 0.8rem;">
                        ${index + 1}. ${al.apPaternoAlumno} ${al.apMaternoAlumno || ''} ${al.nombreAlumno}
                    </td>
                    ${cellsHtml}
                </tr>
            `;
        });
        
        printable.innerHTML = `
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-family: Arial, sans-serif;">
                <tr>
                    <td style="width: 10%; text-align: left; vertical-align: middle;">
                        <img src="${window.location.origin}/img/logo.png" alt="Logo" style="width: 75px; height: 75px; border-radius: 50%; padding: 2px; background: white; border: 1px solid #ddd;">
                    </td>
                    <td style="width: 80%; text-align: center; vertical-align: middle;">
                        <h2 style="margin: 0; font-size: 1.4rem; font-weight: bold; letter-spacing: 0.5px;">BACHILLERATO INTERAMERICANO</h2>
                        <h3 style="margin: 5px 0 0 0; font-size: 1.1rem; font-weight: bold; text-decoration: underline; letter-spacing: 0.5px;">LISTA DE ASISTENCIA</h3>
                    </td>
                    <td style="width: 10%;"></td>
                </tr>
            </table>
            
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-family: Arial, sans-serif; font-size: 0.88rem; line-height: 1.8;">
                <tr>
                    <td style="width: 12%; font-weight: bold; text-align: left;">DOCENTE:</td>
                    <td style="width: 50%; border-bottom: 1px solid black; text-align: left; text-transform: uppercase;">${teacherName}</td>
                    <td style="width: 10%;"></td>
                    <td style="width: 10%; font-weight: bold; text-align: left;">GRUPO:</td>
                    <td style="width: 18%; border-bottom: 1px solid black; text-align: left; text-transform: uppercase; font-weight: bold;">${groupClave}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; text-align: left;">ASIGNATURA:</td>
                    <td style="border-bottom: 1px solid black; text-align: left; text-transform: uppercase;">${subjectName}</td>
                    <td></td>
                    <td style="font-weight: bold; text-align: left;">TRIMESTRE:</td>
                    <td style="border-bottom: 1px solid black; text-align: left; text-transform: uppercase; font-weight: bold;">${levelName}</td>
                </tr>
            </table>
            
            <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; border: 1px solid black;">
                <thead>
                    <tr>
                        <th rowspan="3" style="border: 1px solid black; padding: 10px; background-color: #f1f5f9; min-width: 250px; text-align: left; font-size: 0.88rem;">NOMBRE DEL ALUMNO</th>
                        ${monthColsHtml}
                    </tr>
                    <tr>
                        ${dateColsHtml}
                    </tr>
                    <tr>
                        ${dayColsHtml}
                    </tr>
                </thead>
                <tbody>
                    ${rowsHtml}
                </tbody>
            </table>
            
            <table style="width: 100%; margin-top: 50px; font-family: Arial, sans-serif; font-size: 0.85rem;">
                <tr>
                    <td style="width: 40%; text-align: center;">
                        <br><br>
                        <div style="border-top: 1px solid black; width: 80%; margin: 0 auto; padding-top: 5px; text-transform: uppercase;">
                            ${teacherName}<br>
                            <strong>Firma del Docente</strong>
                        </div>
                    </td>
                    <td style="width: 20%;"></td>
                    <td style="width: 40%; text-align: center;">
                        <br><br>
                        <div style="border-top: 1px solid black; width: 80%; margin: 0 auto; padding-top: 5px;">
                            <strong>Control Escolar / Administración</strong><br>
                            Sello y Firma de Recibido
                        </div>
                    </td>
                </tr>
            </table>
        `;
        
        window.print();
    }

    function descargarExcelAsistencias() {
        const selectMateria = document.getElementById('select-materia');
        const selectedMateriaText = selectMateria ? selectMateria.options[selectMateria.selectedIndex].text : '';
        
        let subjectName = selectedMateriaText;
        let teacherName = '';
        const matchMat = selectedMateriaText.match(/^(.*?)\s*\((.*?)\)$/);
        if (matchMat) {
            subjectName = matchMat[1].trim();
            teacherName = matchMat[2].trim();
        }
        const groupClave = @json($grupo['clave']);
        const levelName = @json($grupo['nombre_nivel'] ?? '');

        const meses = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
        const groupMeses = [];
        fechas.forEach((f, idx) => {
            const dateObj = new Date(f.fecha + 'T00:00:00');
            const monthName = meses[dateObj.getMonth()];
            if (groupMeses.length === 0 || groupMeses[groupMeses.length - 1].month !== monthName) {
                groupMeses.push({ month: monthName, colspan: 1 });
            } else {
                groupMeses[groupMeses.length - 1].colspan++;
            }
        });

        let monthColsHtml = '';
        groupMeses.forEach(m => {
            monthColsHtml += `<th colspan="${m.colspan}" style="border: 1px solid #000000; font-weight: bold; background-color: #d9e1f2; text-align: center;">${m.month}</th>`;
        });

        let dateColsHtml = '';
        fechas.forEach((f, fIdx) => {
            const dateObj = new Date(f.fecha + 'T00:00:00');
            const dayNum = dateObj.getDate();
            const isBgne = (@json($grupo['id_centroTrabajo']) == 3);
            const totalWeeks = fechas.length;
            let evalLabel = '';
            if (isBgne) {
                if (fIdx === 5 || fIdx === 6) evalLabel = 'P.1';
                else if (fIdx === totalWeeks - 2 || fIdx === totalWeeks - 1) evalLabel = 'P.2';
            }
            
            dateColsHtml += `
                <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #f2f2f2; font-size: 9pt;">
                    ${evalLabel ? `<span style="font-size: 7pt; color: #4472c4;">${evalLabel}</span><br>` : ''}
                    ${dayNum}
                </th>`;
        });

        let dayColsHtml = '';
        const dayMap = ['D', 'L', 'M', 'M', 'J', 'V', 'S'];
        fechas.forEach(f => {
            const dateObj = new Date(f.fecha + 'T00:00:00');
            const initial = dayMap[dateObj.getDay()];
            dayColsHtml += `<th style="border: 1px solid #000000; text-align: center; background-color: #f2f2f2; font-size: 9pt;">${initial}</th>`;
        });

        let rowsHtml = '';
        alumnos.forEach((al, index) => {
            let cellsHtml = '';
            fechas.forEach(f => {
                const record = localState[f.fecha][al.idAlumno];
                const estatus = record ? record.estatus : '';
                
                let simbolo = '';
                if (estatus === 'A') simbolo = ':.';
                else if (estatus === 'F') simbolo = '\\';
                else if (estatus === 'R') simbolo = '.';
                else if (estatus === 'J') simbolo = 'J';
                
                let cellStyle = 'border: 1px solid #000000; text-align: center;';
                if (estatus === 'F') cellStyle += ' color: #ff0000; font-weight: bold;';
                else if (estatus === 'J') cellStyle += ' color: #008080; font-weight: bold;';
                
                cellsHtml += `<td style="${cellStyle}">${simbolo}</td>`;
            });
            
            rowsHtml += `
                <tr>
                    <td style="border: 1px solid #000000; padding: 4px; text-transform: uppercase;">
                        ${index + 1}. ${al.apPaternoAlumno} ${al.apMaternoAlumno || ''} ${al.nombreAlumno}
                    </td>
                    ${cellsHtml}
                </tr>
            `;
        });

        const ns = 'x';
        const htmlContent = `
            <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
            <head>
                <!--[if gte mso 9]>
                <xml>
                    <${ns}:ExcelWorkbook>
                        <${ns}:ExcelWorksheets>
                            <${ns}:ExcelWorksheet>
                                <${ns}:Name>Asistencias</${ns}:Name>
                                <${ns}:WorksheetOptions>
                                    <${ns}:DisplayGridlines/>
                                </${ns}:WorksheetOptions>
                            </${ns}:ExcelWorksheet>
                        </${ns}:ExcelWorksheets>
                    </${ns}:ExcelWorkbook>
                </xml>
                <![endif]-->
                <meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">
                <style>
                    body { font-family: Arial, sans-serif; }
                    table { border-collapse: collapse; }
                </style>
            </head>
            <body>
                <table style="width: 100%;">
                    <tr>
                        <td colspan="${fechas.length + 1}" style="text-align: center; font-size: 16pt; font-weight: bold;">
                            BACHILLERATO INTERAMERICANO
                        </td>
                    </tr>
                    <tr>
                        <td colspan="${fechas.length + 1}" style="text-align: center; font-size: 12pt; font-weight: bold; text-decoration: underline;">
                            LISTA DE ASISTENCIA
                        </td>
                    </tr>
                    <tr><td colspan="${fechas.length + 1}"></td></tr>
                    <tr>
                        <td style="font-weight: bold;">DOCENTE:</td>
                        <td colspan="${Math.floor(fechas.length / 2)}" style="border-bottom: 1px solid #000000; text-transform: uppercase;">${teacherName}</td>
                        <td style="font-weight: bold; text-align: right;">GRUPO:</td>
                        <td colspan="${Math.ceil(fechas.length / 2)}" style="border-bottom: 1px solid #000000; text-transform: uppercase; font-weight: bold;">${groupClave}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">ASIGNATURA:</td>
                        <td colspan="${Math.floor(fechas.length / 2)}" style="border-bottom: 1px solid #000000; text-transform: uppercase;">${subjectName}</td>
                        <td style="font-weight: bold; text-align: right;">TRIMESTRE:</td>
                        <td colspan="${Math.ceil(fechas.length / 2)}" style="border-bottom: 1px solid #000000; text-transform: uppercase; font-weight: bold;">${levelName}</td>
                    </tr>
                    <tr><td colspan="${fechas.length + 1}"></td></tr>
                    <tr style="height: 25px;">
                        <th rowspan="3" style="border: 1px solid #000000; font-weight: bold; background-color: #d9e1f2; text-align: left; padding: 4px;">NOMBRE DEL ALUMNO</th>
                        ${monthColsHtml}
                    </tr>
                    <tr>
                        ${dateColsHtml}
                    </tr>
                    <tr>
                        ${dayColsHtml}
                    </tr>
                    ${rowsHtml}
                </table>
            </body>
            </html>
        `;

        const blob = new Blob([htmlContent], { type: 'application/vnd.ms-excel' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `Asistencias_${groupClave}_${subjectName.replace(/[^a-zA-Z0-9]/g, '_')}.xls`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }
</script>

<div id="printable-report" class="d-none d-print-block" style="font-family: Arial, sans-serif; color: black; padding: 15px; background: white;">
</div>

<style>
@media print {
    body * {
        visibility: hidden !important;
    }
    #printable-report, #printable-report * {
        visibility: visible !important;
    }
    #printable-report {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        display: block !important;
        background-color: white !important;
        color: black !important;
    }
    @page {
        size: landscape;
        margin: 5mm;
    }
}
</style>
@endsection
