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
            @php
                $modRawGrid = strtoupper(trim($grupo['modalidadHorario'] ?? ''));
                $esTardeGrid = str_contains($modRawGrid, 'TARDE') || str_contains($modRawGrid, 'VESPERTINO');
                $esMananaGrid = str_contains($modRawGrid, 'MAÑANA') || str_contains($modRawGrid, 'MANANA') || str_contains($modRawGrid, 'MATUTINO') || str_contains($modRawGrid, 'MAANA');
                $turnoLabelGrid = $esTardeGrid ? 'Turno Tarde' : ($esMananaGrid ? 'Turno Mañana' : (!empty($modRawGrid) ? ucwords(strtolower($modRawGrid)) : ''));
            @endphp
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <h3 class="page-title mb-0" style="font-size: 1.6rem; letter-spacing: -0.5px;">{{ $grupo['clave'] }}</h3>
                @if($selected_materia_id === 'general')
                    <span class="badge bg-primary text-white px-2.5 py-1 rounded-pill shadow-sm" style="font-size: 0.72rem; letter-spacing: 0.3px;">
                        Pase General
                    </span>
                @endif
                @if($turnoLabelGrid)
                    <span class="badge" style="font-size: 0.72rem; padding: 4px 8px; border-radius: 6px; {{ $esTardeGrid ? 'background-color: rgba(249, 115, 22, 0.16); color: rgb(194, 65, 12); border: 1px solid rgba(249, 115, 22, 0.35); font-weight: 600;' : 'background-color: rgba(234, 179, 8, 0.16); color: rgb(161, 98, 7); border: 1px solid rgba(234, 179, 8, 0.35); font-weight: 600;' }}">
                        {{ $turnoLabelGrid }}
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
                            @php
                                $esReabierta = in_array($f['fecha'], $reaperturasFechas ?? []);
                            @endphp
                            <option value="{{ $f['fecha'] }}">
                                {{ \Carbon\Carbon::parse($f['fecha'])->format('d-m-Y') }} ({{ $f['nombreNivel'] }}){{ $evalText }}{{ $esReabierta ? ' [REAPERTURA ACTIVA]' : '' }}
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
            <button id="btn-abrir-modal-alumno" type="button" class="btn btn-premium-secondary py-2 px-4 fw-semibold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalAgregarAlumno" style="border-radius: 12px; font-size: 0.82rem;">
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
            <div id="botones-acciones-listado" class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-success fw-semibold d-flex align-items-center gap-1 shadow-sm" onclick="marcarTodos('A')" style="border-radius: 10px; font-size: 0.78rem; background: #fff;">
                    <i class="fa-solid fa-check-double text-success"></i> Todos Asistencia
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary fw-semibold d-flex align-items-center gap-1 shadow-sm" onclick="marcarTodos(null)" style="border-radius: 10px; font-size: 0.78rem; background: #fff;">
                    <i class="fa-solid fa-eraser text-secondary"></i> Limpiar
                </button>
                @if(session('rol') !== 'DOCENTE')
                    <button type="button" id="btn-reabrir-pase" class="btn btn-sm fw-semibold d-flex align-items-center gap-1 shadow-sm" onclick="toggleReaperturaDocente()" style="border-radius: 10px; font-size: 0.78rem; background: #fff; border: 1px solid #f59e0b; color: #b45309;" title="Permitir que el docente pueda volver a modificar y reenviar el pase de lista">
                        <i class="fa-solid fa-lock-open" id="icon-reabrir-pase"></i>
                        <span id="label-reabrir-pase">Permitir Edición a Docente</span>
                    </button>
                @endif
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

        <!-- Botón de Confirmación del Pase de Lista (Hasta abajo de la lista de alumnos) -->
        <div id="panel-guardar-asistencias-bottom" class="mt-4 pt-3 pb-5 text-center">
            <button id="btn-guardar-asistencias" type="button" class="btn btn-success py-3 px-5 fw-bold d-inline-flex align-items-center justify-content-center gap-2 shadow" onclick="guardarAsistencias()" style="border-radius: 14px; font-size: 1.05rem; min-width: 280px; box-shadow: 0 4px 16px rgba(34, 197, 94, 0.35);">
                <i class="fa-solid fa-check-circle" style="font-size: 1.2rem;"></i>
                <span id="btn-guardar-texto">Confirmar Pase de Lista</span>
            </button>
            <div id="msg-pase-bloqueado" class="mt-2 text-center text-muted small" style="max-width: 520px; margin: 0 auto;">
                <i class="fa-solid fa-circle-info me-1"></i>Revisa las asistencias antes de confirmar. Al guardar, tu pase de lista quedará registrado.
            </div>
        </div>
    </div>

    <!-- Contenido Principal: VISTA MATRIZ EXCEL -->
    <div id="contenedor-vista-matriz" class="d-none">
        <div class="card border-0 shadow-sm" style="border-radius: 16px; background: rgba(255, 255, 255, 0.25); border: 1px solid rgba(49, 125, 146, 0.15) !important;">
            <div class="card-body p-0">
                <!-- Contenedor scrollable horizontal y vertical -->
                <div class="table-responsive matriz-scroll-container" style="max-height: 72vh; overflow: auto; border-radius: 16px; background: #ffffff;">
                    <table id="tabla-matriz" class="table table-hover mb-0 align-middle text-center table-bordered" style="border-color: rgba(49, 125, 146, 0.15); font-size: 0.78rem; color: #1e293b;">
                        <thead>
                            <!-- Fila 1: Trimestre / Nivel Académico -->
                            <tr class="fila-nivel" style="border-bottom: 1px solid rgba(49, 125, 146, 0.25);">
                                <th class="text-start sticky-col px-3 py-2" style="min-width: 260px; max-width: 260px; width: 260px; background-color: #f8fafc; font-weight: 700; color: #1e293b;">Alumno</th>
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
                                    <th colspan="{{ $n['count'] }}" class="header-nivel-th" style="background-color: #93b5be; color: #0b343e; font-weight: 700; border-left: 1px solid rgba(49, 125, 146, 0.25);">
                                        {{ $n['nombre'] }}
                                    </th>
                                @endforeach
                            </tr>
                            <!-- Fila 2: Fechas -->
                            <tr class="fila-fechas" style="border-bottom: 2px solid #94a3b8;">
                                <th class="text-start sticky-col px-3 py-2" style="min-width: 260px; max-width: 260px; width: 260px; background-color: #f8fafc; font-weight: 600; color: #475569; border-bottom: 2px solid #94a3b8;">Clave / Matrícula</th>
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
                                    <th class="py-2 px-1 header-fecha-th @if($isEvaluation) col-evaluacion @endif" style="min-width: 75px; font-size: 0.72rem; font-weight: 600; color: #334155; border-left: 1px solid rgba(49, 125, 146, 0.15); @if($isEvaluation) background-color: #e2e8f0; @else background-color: #f8fafc; @endif">
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
        <!-- Botón Guardar Cambios Matriz -->
        <div id="panel-guardar-matriz-bottom" class="mt-3 pt-2 pb-3 d-flex justify-content-end px-3">
            <button id="btn-guardar-matriz" type="button" class="btn btn-success py-2 px-4 fw-semibold d-flex align-items-center gap-2" onclick="guardarAsistencias()" style="border-radius: 12px; font-size: 0.85rem; box-shadow: 0 4px 12px rgba(34, 197, 94, 0.25);">
                <i class="fa-solid fa-floppy-disk"></i>Guardar Cambios Matriz
            </button>
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
                        <input type="text" class="form-control text-dark" id="input-nombre" required placeholder="EJ. JUAN CARLOS" style="background: rgba(255, 255, 255, 0.5); border-radius: 10px; border: 1px solid rgba(49, 125, 146, 0.2); height: 42px; text-transform: uppercase;" oninput="this.value = this.value.toUpperCase()">
                    </div>
                    <div class="mb-3">
                        <label for="input-paterno" class="form-label text-muted uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.5px;">APELLIDO PATERNO</label>
                        <input type="text" class="form-control text-dark" id="input-paterno" required placeholder="EJ. PÉREZ" style="background: rgba(255, 255, 255, 0.5); border-radius: 10px; border: 1px solid rgba(49, 125, 146, 0.2); height: 42px; text-transform: uppercase;" oninput="this.value = this.value.toUpperCase()">
                    </div>
                    <div class="mb-3">
                        <label for="input-materno" class="form-label text-muted uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.5px;">APELLIDO MATERNO</label>
                        <input type="text" class="form-control text-dark" id="input-materno" placeholder="EJ. GÓMEZ" style="background: rgba(255, 255, 255, 0.5); border-radius: 10px; border: 1px solid rgba(49, 125, 146, 0.2); height: 42px; text-transform: uppercase;" oninput="this.value = this.value.toUpperCase()">
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

    /* ========================================================
       CONGELAR PANELES MATRIZ (Freeze Panes: Encabezados y Alumno)
       ======================================================== */
    .matriz-scroll-container {
        position: relative;
        max-height: 72vh !important;
        overflow: auto !important;
        border-radius: 16px;
        background-color: #ffffff !important;
        border: 1px solid rgba(49, 125, 146, 0.2) !important;
    }

    /* Anular overflow:hidden heredado de layouts/app.blade.php .table */
    .matriz-scroll-container table,
    #tabla-matriz {
        border-collapse: separate !important;
        border-spacing: 0 !important;
        overflow: visible !important;
        width: max-content !important;
        min-width: 100% !important;
    }

    /* Columna congelada (Sticky horizontal) */
    #tabla-matriz .sticky-col {
        position: sticky !important;
        left: 0 !important;
        min-width: 260px !important;
        max-width: 260px !important;
        width: 260px !important;
        box-sizing: border-box !important;
        background-color: #ffffff !important;
        border-right: 2px solid #cbd5e1 !important;
        box-shadow: 4px 0 8px -3px rgba(0, 0, 0, 0.12) !important;
    }

    /* En tbody (filas de alumnos) */
    #tabla-matriz tbody td.sticky-col {
        z-index: 20 !important;
        background-color: #ffffff !important;
    }
    #tabla-matriz tbody tr:hover td.sticky-col {
        background-color: #f1f5f9 !important;
    }

    /* Fila 1 de encabezado (Niveles/Trimestres) - fija arriba */
    #tabla-matriz thead tr.fila-nivel th {
        position: sticky !important;
        top: 0 !important;
        z-index: 30 !important;
        background-color: #93b5be !important;
        color: #0b343e !important;
        height: 40px !important;
        vertical-align: middle !important;
        box-sizing: border-box !important;
        border-bottom: 1px solid rgba(49, 125, 146, 0.25) !important;
    }

    /* Esquina superior izquierda (Fila 1, Alumno) - fija arriba y a la izquierda */
    #tabla-matriz thead tr.fila-nivel th.sticky-col {
        top: 0 !important;
        left: 0 !important;
        z-index: 50 !important;
        background-color: #f8fafc !important;
        color: #1e293b !important;
        border-right: 2px solid #cbd5e1 !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }

    /* Fila 2 de encabezado (Fechas) - fija debajo de la Fila 1 */
    #tabla-matriz thead tr.fila-fechas th {
        position: sticky !important;
        top: 40px !important;
        z-index: 30 !important;
        background-color: #f8fafc !important;
        color: #334155 !important;
        height: 48px !important;
        vertical-align: middle !important;
        box-sizing: border-box !important;
        border-bottom: 2px solid #94a3b8 !important;
        box-shadow: 0 4px 6px -2px rgba(0, 0, 0, 0.08) !important;
    }

    #tabla-matriz thead tr.fila-fechas th.col-evaluacion {
        background-color: #e2e8f0 !important;
    }

    /* Esquina intermedia izquierda (Fila 2, Clave / Matrícula) - fija arriba y a la izquierda */
    #tabla-matriz thead tr.fila-fechas th.sticky-col {
        top: 40px !important;
        left: 0 !important;
        z-index: 50 !important;
        background-color: #f8fafc !important;
        color: #475569 !important;
        border-right: 2px solid #cbd5e1 !important;
        border-bottom: 2px solid #94a3b8 !important;
        box-shadow: 4px 4px 8px -2px rgba(0, 0, 0, 0.15) !important;
    }

    /* Botones circulares de la matriz */
    .btn-circle-status {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 1px solid rgba(255,255,255,0.15);
        background: transparent;
        color: #94a3b8;
        font-size: 0.78rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s;
        text-transform: uppercase;
        line-height: 1;
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

    /* Panel de opciones de asistencia y notas */
    .attendance-action-panel {
        display: flex;
        flex-direction: column;
        gap: 8px;
        width: 100%;
    }

    /* Grupo de 4 botones de asistencia en móviles: 4 columnas exactas e iguales */
    .attendance-buttons-group {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 6px;
        width: 100%;
    }

    /* Botón individual de asistencia */
    .btn-attendance-opt {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 8px 3px;
        border-radius: 12px;
        font-size: 0.77rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        border: 1.5px solid #cbd5e1;
        background-color: #ffffff;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
        user-select: none;
        min-height: 42px;
        width: 100%;
        text-align: center;
        white-space: nowrap;
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

    /* Contenedor de notas responsivo */
    .attendance-note-container {
        width: 100%;
    }

    /* Campo integrado de observaciones */
    .attendance-note-input {
        width: 100%;
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

    /* Optimizaciones responsivas para Desktop (>= 992px) */
    @media (min-width: 992px) {
        .attendance-action-panel {
            flex-direction: row;
            align-items: center;
            justify-content: flex-end;
            width: auto;
            gap: 10px;
        }
        .attendance-buttons-group {
            display: flex;
            flex-wrap: nowrap;
            width: auto;
            gap: 8px;
        }
        .btn-attendance-opt {
            width: auto;
            min-width: 82px;
            padding: 7px 14px;
            min-height: 38px;
            font-size: 0.8rem;
        }
        .attendance-note-container {
            width: 200px;
            min-width: 170px;
            max-width: 240px;
        }
    }

    /* Optimizaciones responsivas para Móviles (< 768px) */
    @media (max-width: 767.98px) {
        #contenedor-vista-listado {
            padding-bottom: 75px;
        }
        #select-materia,
        #select-fecha {
            min-width: 0 !important;
            width: 100% !important;
        }
        #div-select-fecha {
            width: 100% !important;
            flex-direction: column !important;
            align-items: stretch !important;
        }
        #div-select-fecha label {
            min-width: 0 !important;
            margin-bottom: 2px;
        }
        #div-nav-fecha {
            width: 100% !important;
            justify-content: center !important;
            margin-top: 6px;
        }
    }

    /* Móviles con pantallas estrechas (< 390px) */
    @media (max-width: 389.98px) {
        .btn-attendance-opt {
            padding: 7px 2px;
            font-size: 0.69rem;
            gap: 3px;
        }
        .btn-attendance-opt i {
            font-size: 0.72rem;
        }
        .badge-lista {
            min-width: 32px;
            height: 32px;
            font-size: 0.8rem;
        }
        .alumno-nombre-card {
            font-size: 0.88rem;
        }
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
    let alumnos = Array.isArray(alumnosRaw) ? [...alumnosRaw] : Object.values(alumnosRaw || {});

    const fechasRaw = @json($fechas);
    const fechas = Array.isArray(fechasRaw) ? fechasRaw : Object.values(fechasRaw || {});

    const asistenciasRaw = @json($asistencias);
    const asistenciasGuardadas = Array.isArray(asistenciasRaw) ? asistenciasRaw : Object.values(asistenciasRaw || {});

    const reaperturasRaw = @json($reaperturasFechas ?? []);
    let reaperturasActivas = Array.isArray(reaperturasRaw) ? [...reaperturasRaw] : Object.values(reaperturasRaw || {});

    const grupo = @json($grupo);

    let vistaActual = 'LISTADO'; // 'LISTADO' o 'MATRIZ'
    let fechaSeleccionada = '';

    // Estructura de estado local: { [fecha]: { [idAlumno]: { estatus, observaciones } } }
    const localState = {};
    // Registro de modificaciones para guardar únicamente lo cambiado
    const modifiedState = {};

    // Persistencia local (sessionStorage) para no perder el pase de lista si se recarga la página o apaga la pantalla
    function guardarBorradorLocal() {
        try {
            const key = `draft_asistencias_${grupo.id}_${@json($selected_materia_id)}`;
            sessionStorage.setItem(key, JSON.stringify({
                modifiedState: modifiedState,
                localState: localState,
                timestamp: Date.now()
            }));
        } catch (e) {
            console.warn("No se pudo guardar borrador en sessionStorage", e);
        }
    }

    function recuperarBorradorLocal() {
        try {
            const key = `draft_asistencias_${grupo.id}_${@json($selected_materia_id)}`;
            const raw = sessionStorage.getItem(key);
            if (!raw) return;
            const draft = JSON.parse(raw);
            // Recuperar si el borrador es reciente (menos de 24 horas)
            if (Date.now() - (draft.timestamp || 0) < 24 * 3600 * 1000) {
                if (draft.modifiedState && Object.keys(draft.modifiedState).length > 0) {
                    for (const fecha in draft.modifiedState) {
                        if (!localState[fecha]) localState[fecha] = {};
                        if (!modifiedState[fecha]) modifiedState[fecha] = {};
                        for (const idAlumno in draft.modifiedState[fecha]) {
                            const rec = draft.modifiedState[fecha][idAlumno];
                            localState[fecha][idAlumno] = rec;
                            modifiedState[fecha][idAlumno] = rec;
                        }
                    }
                }
            }
        } catch (e) {
            console.warn("Error al recuperar borrador de sessionStorage", e);
        }
    }

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

        // Restaurar borrador pendiente si existe para esta sesión
        recuperarBorradorLocal();
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

                        // Seleccionar fecha de hoy si está programada, o fecha de reapertura activa, o la más cercana
            const now = new Date();
            const hoyStr = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-' + String(now.getDate()).padStart(2, '0');
            const tieneHoy = fechas.some(f => f.fecha === hoyStr);
            if (tieneHoy) {
                fechaSeleccionada = hoyStr;
            } else if (reaperturasActivas.length > 0 && fechas.some(f => f.fecha === reaperturasActivas[0])) {
                fechaSeleccionada = reaperturasActivas[0];
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
    // Determina si el pase de lista de la fecha indicada (o fechaSeleccionada) está cerrado/bloqueado para docentes
    function estaPaseBloqueado(fecha = null) {
        const fechaTarget = fecha || fechaSeleccionada;
        const userRole = @json(session('rol'));
        const esDocente = (userRole === 'DOCENTE');
        if (!esDocente) return false; // Administradores y directivos tienen edición completa permitida

        // Si la administración otorgó permiso de reapertura para esta fecha, NO está bloqueado
        if (reaperturasActivas.includes(fechaTarget)) {
            return false;
        }

        const now = new Date();
        const todayStr = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-' + String(now.getDate()).padStart(2, '0');
        const esHoy = (fechaTarget === todayStr);
        if (!esHoy) return true; // Docente no puede modificar fechas pasadas o futuras sin reapertura

        // Si ya existen asistencias guardadas con estatus no nulo para esta fecha en la base de datos (excluyendo justificaciones de admin)
        const yaEnviado = asistenciasGuardadas.some(as => as.fecha === fechaTarget && as.estatus !== null && as.estatus !== '' && !as.justificado_admin);
        return yaEnviado;
    }

    function renderizar() {
        const userRole = @json(session('rol'));
        const esDocente = (userRole === 'DOCENTE');
        const edicionBloqueada = estaPaseBloqueado();

        const btnGuardar = document.getElementById('btn-guardar-asistencias');
        const msgBloqueado = document.getElementById('msg-pase-bloqueado');

        // Actualizar botón de reapertura para Administradores
        const btnReabrir = document.getElementById('btn-reabrir-pase');
        if (btnReabrir) {
            const estaHabilitado = reaperturasActivas.includes(fechaSeleccionada);
            const iconReabrir = document.getElementById('icon-reabrir-pase');
            const labelReabrir = document.getElementById('label-reabrir-pase');

            if (estaHabilitado) {
                btnReabrir.style.borderColor = '#ef4444';
                btnReabrir.style.color = '#dc2626';
                btnReabrir.style.backgroundColor = '#fef2f2';
                btnReabrir.title = 'El docente actualmente tiene permiso para modificar este pase. Haz clic para bloquearlo nuevamente.';
                if (iconReabrir) iconReabrir.className = 'fa-solid fa-lock';
                if (labelReabrir) labelReabrir.innerText = 'Bloquear Edición a Docente';
            } else {
                btnReabrir.style.borderColor = '#f59e0b';
                btnReabrir.style.color = '#b45309';
                btnReabrir.style.backgroundColor = '#fff';
                btnReabrir.title = 'Permitir que el docente pueda volver a realizar o modificar el pase de lista de esta fecha';
                if (iconReabrir) iconReabrir.className = 'fa-solid fa-lock-open';
                if (labelReabrir) labelReabrir.innerText = 'Permitir Edición a Docente';
            }
        }

        if (btnGuardar) {
            if (edicionBloqueada) {
                btnGuardar.disabled = true;
                btnGuardar.className = 'btn btn-secondary py-3 px-5 fw-bold d-inline-flex align-items-center justify-content-center gap-2 shadow-sm';
                btnGuardar.style.opacity = '0.7';
                btnGuardar.style.cursor = 'not-allowed';
                btnGuardar.innerHTML = '<i class="fa-solid fa-lock me-1"></i> <span>Pase de Lista Enviado (Cerrado)</span>';
                if (msgBloqueado) {
                    msgBloqueado.innerHTML = '<span class="badge bg-secondary-subtle text-secondary border px-3 py-2 rounded-pill"><i class="fa-solid fa-lock me-1"></i>El pase de lista de esta fecha ya fue enviado y se encuentra bloqueado para docentes. Para modificaciones, solicita apoyo a la administración.</span>';
                }
            } else {
                btnGuardar.disabled = false;
                btnGuardar.className = 'btn btn-success py-3 px-5 fw-bold d-inline-flex align-items-center justify-content-center gap-2 shadow';
                btnGuardar.style.opacity = '1';
                btnGuardar.style.cursor = 'pointer';
                btnGuardar.innerHTML = '<i class="fa-solid fa-check-circle me-1"></i> <span>Confirmar Pase de Lista</span>';
                if (msgBloqueado) {
                    if (esDocente && reaperturasActivas.includes(fechaSeleccionada)) {
                          msgBloqueado.innerHTML = '<span class="badge bg-warning-subtle text-warning-emphasis border border-warning px-3 py-2 rounded-pill"><i class="fa-solid fa-unlock-keyhole me-1"></i> Administración te ha habilitado el permiso para modificar y reenviar tu pase de lista.</span>';
                      } else {
                          msgBloqueado.innerHTML = '<i class="fa-solid fa-circle-info me-1"></i>Revisa las asistencias antes de confirmar. Al guardar, tu pase de lista quedará registrado.';
                      }
                }
            }
        }

        const btnGuardarMatriz = document.getElementById('btn-guardar-matriz');
        if (btnGuardarMatriz) {
            if (edicionBloqueada) {
                btnGuardarMatriz.disabled = true;
                btnGuardarMatriz.style.opacity = '0.6';
                btnGuardarMatriz.style.cursor = 'not-allowed';
            } else {
                btnGuardarMatriz.disabled = false;
                btnGuardarMatriz.style.opacity = '1';
                btnGuardarMatriz.style.cursor = 'pointer';
            }
        }

        // Acciones rápidas (Todos Asistencia, Limpiar)
        const toolbarAcciones = document.getElementById('botones-acciones-listado');
        if (toolbarAcciones) {
            toolbarAcciones.style.display = edicionBloqueada ? 'none' : 'flex';
        }

        // Botón agregar alumno
        const btnAgregarAlumno = document.getElementById('btn-abrir-modal-alumno');
        if (btnAgregarAlumno && esDocente) {
            if (edicionBloqueada) {
                btnAgregarAlumno.disabled = true;
                btnAgregarAlumno.style.opacity = '0.5';
                btnAgregarAlumno.style.cursor = 'not-allowed';
                btnAgregarAlumno.title = 'El pase de lista ya fue enviado. No se pueden agregar alumnos.';
            } else {
                btnAgregarAlumno.disabled = false;
                btnAgregarAlumno.style.opacity = '1';
                btnAgregarAlumno.style.cursor = 'pointer';
                btnAgregarAlumno.title = '';
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
            setTimeout(ajustarAlturasSticky, 50);
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

            const edicionBloqueada = estaPaseBloqueado();

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

                            <!-- Opciones de Asistencia y Observaciones Responsivo -->
                            <div class="attendance-action-panel">
                                <!-- Botones de Asistencia directos (4 columnas exactas en móvil) -->
                                <div class="attendance-buttons-group">
                                    <button type="button" 
                                            class="btn-attendance-opt btn-opt-asis ${estatus === 'A' ? 'active' : ''}" 
                                            data-status="A"
                                            ${disableAttr}
                                            title="Asistencia"
                                            onclick="seleccionarEstatusListado(${al.idAlumno}, 'A')">
                                        <i class="fa-solid fa-check"></i> <span>ASIS.</span>
                                    </button>
                                    <button type="button" 
                                            class="btn-attendance-opt btn-opt-falt ${estatus === 'F' ? 'active' : ''}" 
                                            data-status="F"
                                            ${disableAttr}
                                            title="Falta"
                                            onclick="seleccionarEstatusListado(${al.idAlumno}, 'F')">
                                        <i class="fa-solid fa-xmark"></i> <span>FALT.</span>
                                    </button>
                                    <button type="button" 
                                            class="btn-attendance-opt btn-opt-reta ${estatus === 'R' ? 'active' : ''}" 
                                            data-status="R"
                                            ${disableAttr}
                                            title="Retardo"
                                            onclick="seleccionarEstatusListado(${al.idAlumno}, 'R')">
                                        <i class="fa-regular fa-clock"></i> <span>RETA.</span>
                                    </button>
                                    <button type="button" 
                                            class="btn-attendance-opt btn-opt-just ${estatus === 'J' ? 'active' : ''}" 
                                            data-status="J"
                                            ${disableAttr}
                                            title="Justificado"
                                            onclick="seleccionarEstatusListado(${al.idAlumno}, 'J')">
                                        <i class="fa-regular fa-file-lines"></i> <span>JUST.</span>
                                    </button>
                                </div>

                                <!-- Input de Observaciones / Nota (Ancho completo debajo en móviles) -->
                                <div class="attendance-note-container">
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
                            <div class="mt-2 pt-2 border-top border-light-subtle d-flex align-items-center gap-2 text-secondary fw-medium" style="font-size: 0.76rem;">
                                <i class="fa-solid fa-lock text-secondary"></i>
                                <span>Pase de lista enviado y cerrado. No editable para docentes.</span>
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
        if (estaPaseBloqueado()) {
            Swal.fire({
                icon: 'info',
                title: 'Pase de lista cerrado',
                text: 'El pase de lista ya fue enviado y no puede modificarse. Consulta con el administrador si requieres un cambio.',
                confirmButtonColor: 'rgb(49, 125, 146)'
            });
            return;
        }

        if (!localState[fechaSeleccionada]) {
            localState[fechaSeleccionada] = {};
        }
        if (!localState[fechaSeleccionada][idAlumno]) {
            localState[fechaSeleccionada][idAlumno] = { estatus: null, observaciones: '', justificado_admin: false };
        }

        const record = localState[fechaSeleccionada][idAlumno];
        if (record.justificado_admin) return;

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

        // Guardar borrador local
        guardarBorradorLocal();
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
        if (estaPaseBloqueado()) {
            Swal.fire({
                icon: 'info',
                title: 'Pase de lista cerrado',
                text: 'El pase de lista ya fue enviado y no puede modificarse.',
                confirmButtonColor: 'rgb(49, 125, 146)'
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
        guardarBorradorLocal();
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
        if (estaPaseBloqueado()) return;
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

        // Guardar borrador local
        guardarBorradorLocal();
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

        // Actualizar contador en la barra flotante para móviles
        const textoMovil = document.getElementById('texto-movil-progreso');
        if (textoMovil) {
            textoMovil.innerText = `${completados} / ${total}`;
        }

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
                    <td class="text-start sticky-col px-3 py-2 fw-semibold" style="min-width: 260px; max-width: 260px; width: 260px; background-color: #ffffff; color: #1e293b; border-right: 2px solid #cbd5e1; border-bottom: 1px solid rgba(49, 125, 146, 0.1);">
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

                    const edicionBloqueada = estaPaseBloqueado(f.fecha);

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
                    if (estatus === 'A') simbolo = 'A';
                    else if (estatus === 'F') simbolo = 'F';
                    else if (estatus === 'R') simbolo = 'R';
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

            // Sincronizar altura de fila de fechas sticky
            setTimeout(ajustarAlturasSticky, 30);
        } catch (e) {
            console.error("Error en renderMatriz:", e);
            mostrarErrorPantalla(e, 'renderMatriz');
        }
    }

    // Función para asegurar que la fila de fechas esté perfectamente pegada debajo de la fila de niveles
    function ajustarAlturasSticky() {
        const filaNivel = document.querySelector('#tabla-matriz thead tr.fila-nivel');
        if (filaNivel) {
            const h = filaNivel.offsetHeight;
            if (h && h > 0) {
                document.querySelectorAll('#tabla-matriz thead tr.fila-fechas th').forEach(th => {
                    th.style.top = h + 'px';
                });
            }
        }
    }
    window.addEventListener('resize', ajustarAlturasSticky);

    // Cicla a través de los estados: '-' -> 'A' -> 'F' -> 'R' -> 'J' -> '-'
    function ciclarEstatusMatriz(btn, idAlumno, fecha) {
        const record = localState[fecha][idAlumno];
        if (record.justificado_admin) return;
        if (estaPaseBloqueado(fecha)) return;

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
        if (nuevo === 'A') simbolo = 'A';
        else if (nuevo === 'F') simbolo = 'F';
        else if (nuevo === 'R') simbolo = 'R';
        else if (nuevo === 'J') simbolo = 'J';

        btn.className = `btn-circle-status ${nuevo ? 'status-' + nuevo : ''}`;
        btn.innerText = simbolo;
    }

    // GUARDAR CAMBIOS MASIVOS POR AJAX
    function guardarAsistencias() {
        const userRole = @json(session('rol'));
        const esDocente = (userRole === 'DOCENTE');

        if (estaPaseBloqueado()) {
            Swal.fire({
                icon: 'warning',
                title: 'Pase de lista cerrado',
                text: 'El pase de lista ya fue enviado previamente y se encuentra cerrado.',
                confirmButtonColor: '#ef4444'
            });
            return;
        }

        const asistenciasToSend = [];

        if (vistaActual === 'LISTADO' && esDocente) {
            // En vista listado como docente, confirmamos estrictamente el pase de lista de la fecha seleccionada
            const fObj = fechas.find(fe => fe.fecha === fechaSeleccionada);
            const nivelId = fObj ? fObj.id_nivel_academico : null;
            const dateRecord = localState[fechaSeleccionada] || {};

            for (const idAlumno in dateRecord) {
                const rec = dateRecord[idAlumno];
                if (rec && rec.estatus !== null && rec.estatus !== '') {
                    asistenciasToSend.push({
                        id_alumno: parseInt(idAlumno),
                        fecha: fechaSeleccionada,
                        id_nivel_academico: nivelId,
                        estatus: rec.estatus,
                        observaciones: rec.observaciones || ''
                    });
                }
            }
        } else {
            // Recopilar todos los registros modificados del modifiedState
            for (const fecha in modifiedState) {
                if (esDocente && estaPaseBloqueado(fecha)) continue; // Omitir fechas no autorizadas

                for (const idAlumno in modifiedState[fecha]) {
                    const rec = modifiedState[fecha][idAlumno];
                    const fObj = fechas.find(fe => fe.fecha === fecha);
                    asistenciasToSend.push({
                        id_alumno: parseInt(idAlumno),
                        fecha: fecha,
                        id_nivel_academico: fObj ? fObj.id_nivel_academico : null,
                        estatus: rec.estatus,
                        observaciones: rec.observaciones || ''
                    });
                }
            }

            // Si modifiedState está vacío pero hay asistencias marcadas en localState para la fecha actual, incluirlas
            if (asistenciasToSend.length === 0 && localState[fechaSeleccionada]) {
                const fObj = fechas.find(fe => fe.fecha === fechaSeleccionada);
                const nivelId = fObj ? fObj.id_nivel_academico : null;
                for (const idAlumno in localState[fechaSeleccionada]) {
                    const rec = localState[fechaSeleccionada][idAlumno];
                    if (rec && rec.estatus !== null && rec.estatus !== '') {
                        asistenciasToSend.push({
                            id_alumno: parseInt(idAlumno),
                            fecha: fechaSeleccionada,
                            id_nivel_academico: nivelId,
                            estatus: rec.estatus,
                            observaciones: rec.observaciones || ''
                        });
                    }
                }
            }
        }

        // Si no hay datos que enviar, avisar al usuario
        if (asistenciasToSend.length === 0) {
            Swal.fire({
                icon: 'info',
                title: 'Sin datos asignados',
                text: 'Por favor, asigna al menos una asistencia antes de confirmar el pase de lista.',
                confirmButtonColor: 'rgb(49, 125, 146)'
            });
            return;
        }

        // Si faltan alumnos por marcar en la fecha seleccionada en vista Listado, advertir amablemente al docente
        if (esDocente && vistaActual === 'LISTADO') {
            const totalAlumnos = alumnos.length;
            const marcados = asistenciasToSend.length;
            if (marcados < totalAlumnos) {
                const faltantes = totalAlumnos - marcados;
                Swal.fire({
                    icon: 'question',
                    title: '¿Confirmar pase de lista?',
                    html: `Hay <b>${faltantes} alumno(s)</b> sin estatus asignado.<br>Al confirmar, tu pase de lista quedará registrado y cerrado.<br>¿Deseas continuar?`,
                    showCancelButton: true,
                    confirmButtonText: 'Sí, confirmar',
                    cancelButtonText: 'Revisar lista',
                    confirmButtonColor: '#22c55e',
                    cancelButtonColor: '#6b7280'
                }).then((resAlert) => {
                    if (resAlert.isConfirmed) {
                        ejecutarEnvioAsistencias(asistenciasToSend);
                    }
                });
                return;
            }
        }

        ejecutarEnvioAsistencias(asistenciasToSend);
    }

    function ejecutarEnvioAsistencias(asistenciasToSend) {
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
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                id_grupo: grupo.id,
                id_materia: @json($selected_materia_id),
                id_docente: @json(session('rol') === 'DOCENTE' ? session('id_docente') : null),
                asistencias: asistenciasToSend
            })
        })
        .then(async res => {
            const data = await res.json().catch(() => null);
            if (!res.ok) {
                const errorMsg = (data && (data.error || data.message))
                    || (res.status === 419 ? 'La sesión de seguridad ha expirado por inactividad. Abre otra pestaña para ingresar al sistema y vuelve a presionar Confirmar.' : `Error del servidor (${res.status})`);
                throw new Error(errorMsg);
            }
            return data;
        })
        .then(data => {
            Swal.close();
            if (data && data.error) {
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
                    text: (data && data.mensaje) || 'Las asistencias se han actualizado correctamente en el sistema.',
                    confirmButtonColor: '#22c55e'
                }).then(() => {
                    // Limpiar historial de modificaciones locales
                    for (const prop in modifiedState) { delete modifiedState[prop]; }
                    try {
                        sessionStorage.removeItem(`draft_asistencias_${grupo.id}_${@json($selected_materia_id)}`);
                    } catch(e) {}
                    // Recargar datos y refrescar la vista
                    window.location.reload();
                });
            }
        })
        .catch(err => {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error al guardar',
                text: err.message || 'No se pudo establecer comunicación con el servidor. Inténtalo de nuevo.',
                confirmButtonColor: '#ef4444'
            });
            console.error(err);
        });
    }

    // CREAR Y MATRICULAR NUEVO ALUMNO EN EL GRUPO
    function crearAlumno(e) {
        e.preventDefault();

        if (estaPaseBloqueado()) {
            Swal.fire({
                icon: 'warning',
                title: 'Pase de lista cerrado',
                text: 'No es posible agregar alumnos porque el pase de lista de hoy ya fue enviado y cerrado.',
                confirmButtonColor: '#ef4444'
            });
            return;
        }

        const nombre = document.getElementById('input-nombre').value.trim().toUpperCase();
        const paterno = document.getElementById('input-paterno').value.trim().toUpperCase();
        const materno = document.getElementById('input-materno').value.trim().toUpperCase();

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
        if (modal) modal.hide();

        Swal.fire({
            title: 'Registrando alumno...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const creatorName = @json(session('nombre') ?: session('usuario') ?: 'Docente');

        // Payload requerido por el backend Laravel/Flask con createBy para notificaciones
        const payload = {
            alumno: {
                nombre: nombre,
                apPaterno: paterno,
                apMaterno: materno,
                statusAlumno: 'ACTIVO',
                createBy: creatorName
            },
            academico: {
                id_centroTrabajo: grupo.id_centroTrabajo,
                id_nivel_academico: grupo.id_nivel_academico_actual,
                id_generacion: grupo.idGeneracion,
                id_grupo: grupo.id
            },
            createBy: creatorName
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
                    if (modal) modal.show();
                });
            } else {
                // Obtener ID del nuevo alumno asignado por la base de datos
                const nuevoId = (data.data && data.data.idAlumno) ? data.data.idAlumno : (data.idAlumno || Date.now());

                // Crear objeto del nuevo alumno para agregar a la colección local
                const nuevoAlumno = {
                    idAlumno: nuevoId,
                    nombreAlumno: nombre,
                    apPaternoAlumno: paterno,
                    apMaternoAlumno: materno || '',
                    matricula: (data.data && data.data.numeroControl) ? data.data.numeroControl : '',
                    numero_lista: 0
                };

                // Agregar el alumno al array local
                alumnos.push(nuevoAlumno);

                // Reordenar alfabéticamente (Paterno, Materno, Nombre)
                alumnos.sort((a, b) => {
                    const nomA = `${a.apPaternoAlumno || ''} ${a.apMaternoAlumno || ''} ${a.nombreAlumno || ''}`.trim().toLowerCase();
                    const nomB = `${b.apPaternoAlumno || ''} ${b.apMaternoAlumno || ''} ${b.nombreAlumno || ''}`.trim().toLowerCase();
                    return nomA.localeCompare(nomB, 'es', { sensitivity: 'base' });
                });

                // Recalcular correlativo de número de lista
                alumnos.forEach((al, idx) => {
                    al.numero_lista = idx + 1;
                    al.num_lista = idx + 1;
                });

                // Inicializar estado para el nuevo alumno en todas las fechas disponibles
                fechas.forEach(f => {
                    if (!localState[f.fecha]) localState[f.fecha] = {};
                    if (!localState[f.fecha][nuevoId]) {
                        localState[f.fecha][nuevoId] = {
                            estatus: null,
                            observaciones: '',
                            justificado_admin: false
                        };
                    }
                });

                // Limpiar formulario modal para próximas inserciones
                const formEl = document.getElementById('form-agregar-alumno');
                if (formEl) formEl.reset();

                // Re-renderizar la vista CONSERVANDO el pase de lista de todos los demás alumnos
                renderizar();
                guardarBorradorLocal();

                // Notificar al usuario con confirmación clara
                Swal.fire({
                    icon: 'success',
                    title: '¡Alumno Agregado!',
                    text: `${paterno} ${materno} ${nombre} se incorporó a la lista. Tu pase de lista anterior se ha conservado; ya puedes marcar la asistencia del nuevo alumno.`,
                    confirmButtonColor: 'rgb(49, 125, 146)',
                    timer: 3500,
                    timerProgressBar: true
                });

                // Desplazar suavemente hasta la tarjeta del nuevo alumno y resaltarla temporalmente
                setTimeout(() => {
                    const tarjeta = document.querySelector(`.attendance-card[data-id="${nuevoId}"]`);
                    if (tarjeta) {
                        tarjeta.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        tarjeta.style.transition = 'all 0.4s ease';
                        tarjeta.style.boxShadow = '0 0 0 3px rgba(49, 125, 146, 0.75), 0 8px 24px rgba(49, 125, 146, 0.25)';
                        tarjeta.style.transform = 'scale(1.02)';
                        setTimeout(() => {
                            tarjeta.style.boxShadow = '';
                            tarjeta.style.transform = '';
                        }, 2600);
                    }
                }, 350);
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
                if (modal) modal.show();
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
                if (estatus === 'A') simbolo = 'A';
                else if (estatus === 'F') simbolo = 'F';
                else if (estatus === 'R') simbolo = 'R';
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
                if (estatus === 'A') simbolo = 'A';
                else if (estatus === 'F') simbolo = 'F';
                else if (estatus === 'R') simbolo = 'R';
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

    // Habilitar o revocar permiso al docente para modificar el pase de lista
    function toggleReaperturaDocente() {
        const fecha = fechaSeleccionada;
        const estaHabilitado = reaperturasActivas.includes(fecha);
        const titulo = estaHabilitado 
            ? '¿Bloquear edición al docente?' 
            : '¿Permitir edición al docente?';
        const texto = estaHabilitado 
            ? `El pase de lista del ${fecha} volverá a quedar cerrado para el docente.` 
            : `El docente podrá volver a modificar y reenviar el pase de lista de la fecha ${fecha}.`;
        const confirmBtnText = estaHabilitado ? 'Sí, bloquear edición' : 'Sí, habilitar edición';
        const confirmBtnColor = estaHabilitado ? '#ef4444' : '#f59e0b';

        Swal.fire({
            icon: 'question',
            title: titulo,
            text: texto,
            showCancelButton: true,
            confirmButtonColor: confirmBtnColor,
            cancelButtonColor: '#6b7280',
            confirmButtonText: confirmBtnText,
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.showLoading();
                fetch('{{ route("asistencias_alumnos.reabrir") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id_grupo: grupo.id,
                        fecha: fecha,
                        id_materia: document.getElementById('select-materia').value,
                        habilitar: !estaHabilitado
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire({ icon: 'error', title: 'Error', text: data.error });
                    } else {
                        if (data.habilitado) {
                            if (!reaperturasActivas.includes(fecha)) {
                                reaperturasActivas.push(fecha);
                            }
                        } else {
                            reaperturasActivas = reaperturasActivas.filter(f => f !== fecha);
                        }
                        Swal.fire({
                            icon: 'success',
                            title: data.habilitado ? 'Edición Habilitada' : 'Edición Bloqueada',
                            text: data.mensaje,
                            timer: 2200,
                            showConfirmButton: false
                        });
                        renderizar();
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire({ icon: 'error', title: 'Error de conexión', text: 'No se pudo actualizar el permiso de edición.' });
                });
            }
        });
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
