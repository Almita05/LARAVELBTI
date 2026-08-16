@extends('layouts.app')

@section('content')

<head>
    <link rel="stylesheet" href="{{ asset('css/estilosDocentes.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .modulo-badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 4px;
            margin-right: 4px;
            margin-bottom: 4px;
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
            <i class="fa-solid fa-user-gear me-2"></i>
            Gestión de Personal
        </h3>

        <button class="btn btn-azul" data-bs-toggle="modal" data-bs-target="#modalAltaPersonal">
            <i class="fa-solid fa-plus me-2"></i>
            Alta Personal
        </button>
    </div>

    <div class="glass-header p-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 textoDocentes">
            Lista de Cuentas de Personal
        </h5>
        <input type="text" id="buscadorPersonal" class="form-control glass-input w-25" placeholder="Buscar personal...">
    </div>

    <br>

    <div class="glass-card">
        <div class="table-responsive">
            <table class="table glass-table align-middle mb-0">
                <thead class="table-head">
                    <tr>
                        <th>ID</th>
                        <th>NOMBRE</th>
                        <th>USUARIO</th>
                        <th>ROL</th>
                        <th>MÓDULOS PERMITIDOS</th>
                        <th>ESTATUS</th>
                        <th class="text-center" style="width: 150px; min-width: 150px;">ACCIONES</th>
                    </tr>
                </thead>
                <tbody id="tablaPersonal">
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL ALTA PERSONAL --}}
<div class="modal fade" id="modalAltaPersonal" tabindex="-1" aria-labelledby="modalAltaPersonalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header bg-dark text-white" style="border-bottom: none;">
                <h5 class="modal-title fw-bold" id="modalAltaPersonalLabel">
                    <i class="fa-solid fa-user-plus me-2 text-primary"></i> Registrar Nueva Cuenta de Personal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAltaPersonal">
                @csrf
                <div class="modal-body text-dark bg-white">
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold">Nombre Completo *</label>
                        <input type="text" class="form-control form-control-premium text-dark" name="nombre" required placeholder="Ej. Lic. Laura Elena Martínez" style="color: #000 !important; font-weight: 600;">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-bold">Nombre de Usuario *</label>
                            <input type="text" class="form-control form-control-premium text-dark" name="usuario" required placeholder="Ej. laura.martinez" style="color: #000 !important; font-weight: 600;">
                            <small class="text-muted">Se sugiere usar minúsculas sin espacios.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-bold">Contraseña *</label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-premium text-dark" id="altaPassword" name="password" required placeholder="Mínimo 4 caracteres" style="color: #000 !important; font-weight: 600;">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('altaPassword')">
                                    <i class="fa-solid fa-eye" id="toggleIcon_altaPassword"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold">Rol de Personal *</label>
                        <select class="form-select form-control-premium text-dark" name="rol" required style="color: #000 !important; font-weight: 600;">
                            <option value="" disabled selected>Selecciona un rol...</option>
                            <option value="Control Escolar">Control Escolar</option>
                            <option value="Administrativo">Administrativo</option>
                            <option value="Subdirector">Subdirector</option>
                            <option value="Director">Director</option>
                            <option value="Prefecto">Prefecto</option>
                        </select>
                    </div>

                    <div class="mb-3 border-top pt-3">
                        <label class="form-label text-dark fw-bold">Módulos Habilitados (Permisos CRUD):</label>
                        <div class="table-responsive border rounded bg-white" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-sm table-hover align-middle mb-0 text-dark" style="font-size: 0.85rem; color: #000 !important;">
                                <thead class="table-dark sticky-top">
                                    <tr>
                                        <th class="ps-3 text-white">Módulo / Submódulo</th>
                                        <th class="text-center" style="width: 100px; color: white;">
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input col-check-all" type="checkbox" data-col="ver" id="allVer-crear" checked>
                                                <label class="form-check-label text-white fw-bold fs-7" for="allVer-crear" style="cursor: pointer;">Ver</label>
                                            </div>
                                        </th>
                                        <th class="text-center" style="width: 100px; color: white;">
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input col-check-all" type="checkbox" data-col="crear" id="allCrear-crear" checked>
                                                <label class="form-check-label text-white fw-bold fs-7" for="allCrear-crear" style="cursor: pointer;">Crear/Editar</label>
                                            </div>
                                        </th>
                                        <th class="text-center" style="width: 100px; color: white;">
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input col-check-all" type="checkbox" data-col="eliminar" id="allEliminar-crear" checked>
                                                <label class="form-check-label text-white fw-bold fs-7" for="allEliminar-crear" style="cursor: pointer;">Eliminar</label>
                                            </div>
                                        </th>
                                        <th class="text-center" style="width: 100px; color: white;">
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input col-check-all" type="checkbox" data-col="consultar" id="allConsultar-crear" checked>
                                                <label class="form-check-label text-white fw-bold fs-7" for="allConsultar-crear" style="cursor: pointer;">Consultar</label>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Categoría: General -->
                                    <tr class="table-secondary fw-bold text-dark">
                                        <td colspan="5" class="ps-2 py-1"><i class="fa-solid fa-gear me-1"></i> GENERAL</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Inicio (Dashboard)</td>
                                        <td class="text-center"><input class="form-check-input check-crear-ver" type="checkbox" name="permisos_modulos[]" value="inicio:ver" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-crear" type="checkbox" name="permisos_modulos[]" value="inicio:crear" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-eliminar" type="checkbox" name="permisos_modulos[]" value="inicio:eliminar" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-consultar" type="checkbox" name="permisos_modulos[]" value="inicio:consultar" checked></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Avisos y Pendientes</td>
                                        <td class="text-center"><input class="form-check-input check-crear-ver" type="checkbox" name="permisos_modulos[]" value="notificaciones:ver" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-crear" type="checkbox" name="permisos_modulos[]" value="notificaciones:crear" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-eliminar" type="checkbox" name="permisos_modulos[]" value="notificaciones:eliminar" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-consultar" type="checkbox" name="permisos_modulos[]" value="notificaciones:consultar" checked></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Búsqueda de Grupo</td>
                                        <td class="text-center"><input class="form-check-input check-crear-ver" type="checkbox" name="permisos_modulos[]" value="busqueda_grupos:ver" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-crear" type="checkbox" name="permisos_modulos[]" value="busqueda_grupos:crear" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-eliminar" type="checkbox" name="permisos_modulos[]" value="busqueda_grupos:eliminar" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-consultar" type="checkbox" name="permisos_modulos[]" value="busqueda_grupos:consultar" checked></td>
                                    </tr>

                                    <!-- Categoría: Alumnos -->
                                    <tr class="table-secondary fw-bold text-dark">
                                        <td colspan="5" class="ps-2 py-1"><i class="fa-solid fa-graduation-cap me-1 text-success"></i> ALUMNOS</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Listado de Alumnos</td>
                                        <td class="text-center"><input class="form-check-input check-crear-ver" type="checkbox" name="permisos_modulos[]" value="alumnos_list:ver" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-crear" type="checkbox" name="permisos_modulos[]" value="alumnos_list:crear" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-eliminar" type="checkbox" name="permisos_modulos[]" value="alumnos_list:eliminar" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-consultar" type="checkbox" name="permisos_modulos[]" value="alumnos_list:consultar" checked></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Captura de Calificaciones</td>
                                        <td class="text-center"><input class="form-check-input check-crear-ver" type="checkbox" name="permisos_modulos[]" value="alumnos_calificaciones:ver" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-crear" type="checkbox" name="permisos_modulos[]" value="alumnos_calificaciones:crear" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-eliminar" type="checkbox" name="permisos_modulos[]" value="alumnos_calificaciones:eliminar" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-consultar" type="checkbox" name="permisos_modulos[]" value="alumnos_calificaciones:consultar" checked></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Asistencia Alumnos</td>
                                        <td class="text-center"><input class="form-check-input check-crear-ver" type="checkbox" name="permisos_modulos[]" value="alumnos_asistencias:ver" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-crear" type="checkbox" name="permisos_modulos[]" value="alumnos_asistencias:crear" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-eliminar" type="checkbox" name="permisos_modulos[]" value="alumnos_asistencias:eliminar" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-consultar" type="checkbox" name="permisos_modulos[]" value="alumnos_asistencias:consultar" checked></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Reporte de Asistencias</td>
                                        <td class="text-center"><input class="form-check-input check-crear-ver" type="checkbox" name="permisos_modulos[]" value="alumnos_reporte_asistencias:ver" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-crear" type="checkbox" name="permisos_modulos[]" value="alumnos_reporte_asistencias:crear" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-eliminar" type="checkbox" name="permisos_modulos[]" value="alumnos_reporte_asistencias:eliminar" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-consultar" type="checkbox" name="permisos_modulos[]" value="alumnos_reporte_asistencias:consultar" checked></td>
                                    </tr>

                                    <!-- Categoría: Docentes -->
                                    <tr class="table-secondary fw-bold text-dark">
                                        <td colspan="5" class="ps-2 py-1"><i class="fa-solid fa-person-chalkboard me-1 text-info"></i> DOCENTES</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Listado de Docentes</td>
                                        <td class="text-center"><input class="form-check-input check-crear-ver" type="checkbox" name="permisos_modulos[]" value="docentes_list:ver" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-crear" type="checkbox" name="permisos_modulos[]" value="docentes_list:crear" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-eliminar" type="checkbox" name="permisos_modulos[]" value="docentes_list:eliminar" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-consultar" type="checkbox" name="permisos_modulos[]" value="docentes_list:consultar" checked></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Asistencia Docente</td>
                                        <td class="text-center"><input class="form-check-input check-crear-ver" type="checkbox" name="permisos_modulos[]" value="docentes_asistencias:ver" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-crear" type="checkbox" name="permisos_modulos[]" value="docentes_asistencias:crear" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-eliminar" type="checkbox" name="permisos_modulos[]" value="docentes_asistencias:eliminar" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-consultar" type="checkbox" name="permisos_modulos[]" value="docentes_asistencias:consultar" checked></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Horario Docente</td>
                                        <td class="text-center"><input class="form-check-input check-crear-ver" type="checkbox" name="permisos_modulos[]" value="docentes_horarios:ver" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-crear" type="checkbox" name="permisos_modulos[]" value="docentes_horarios:crear" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-eliminar" type="checkbox" name="permisos_modulos[]" value="docentes_horarios:eliminar" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-consultar" type="checkbox" name="permisos_modulos[]" value="docentes_horarios:consultar" checked></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Permisos de Captura</td>
                                        <td class="text-center"><input class="form-check-input check-crear-ver" type="checkbox" name="permisos_modulos[]" value="docentes_permisos_captura:ver" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-crear" type="checkbox" name="permisos_modulos[]" value="docentes_permisos_captura:crear" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-eliminar" type="checkbox" name="permisos_modulos[]" value="docentes_permisos_captura:eliminar" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-consultar" type="checkbox" name="permisos_modulos[]" value="docentes_permisos_captura:consultar" checked></td>
                                    </tr>

                                    <!-- Categoría: Grupos -->
                                    <tr class="table-secondary fw-bold text-dark">
                                        <td colspan="5" class="ps-2 py-1"><i class="fa-solid fa-users me-1 text-secondary"></i> GRUPOS</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Inicio Grupos</td>
                                        <td class="text-center"><input class="form-check-input check-crear-ver" type="checkbox" name="permisos_modulos[]" value="grupos_list:ver" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-crear" type="checkbox" name="permisos_modulos[]" value="grupos_list:crear" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-eliminar" type="checkbox" name="permisos_modulos[]" value="grupos_list:eliminar" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-consultar" type="checkbox" name="grupos_list:consultar" checked></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Captura Calificaciones</td>
                                        <td class="text-center"><input class="form-check-input check-crear-ver" type="checkbox" name="permisos_modulos[]" value="grupos_calificaciones:ver" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-crear" type="checkbox" name="permisos_modulos[]" value="grupos_calificaciones:crear" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-eliminar" type="checkbox" name="permisos_modulos[]" value="grupos_calificaciones:eliminar" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-consultar" type="checkbox" name="permisos_modulos[]" value="grupos_calificaciones:consultar" checked></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Horarios</td>
                                        <td class="text-center"><input class="form-check-input check-crear-ver" type="checkbox" name="permisos_modulos[]" value="grupos_horarios:ver" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-crear" type="checkbox" name="permisos_modulos[]" value="grupos_horarios:crear" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-eliminar" type="checkbox" name="permisos_modulos[]" value="grupos_horarios:eliminar" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-consultar" type="checkbox" name="permisos_modulos[]" value="grupos_horarios:consultar" checked></td>
                                    </tr>

                                    <!-- Categoría: Materias -->
                                    <tr class="table-secondary fw-bold text-dark">
                                        <td colspan="5" class="ps-2 py-1"><i class="fa-solid fa-book-bookmark me-1 text-danger"></i> MATERIAS</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Materias</td>
                                        <td class="text-center"><input class="form-check-input check-crear-ver" type="checkbox" name="permisos_modulos[]" value="materias:ver" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-crear" type="checkbox" name="permisos_modulos[]" value="materias:crear" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-eliminar" type="checkbox" name="permisos_modulos[]" value="materias:eliminar" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-consultar" type="checkbox" name="permisos_modulos[]" value="materias:consultar" checked></td>
                                    </tr>

                                    <!-- Categoría: Planes -->
                                    <tr class="table-secondary fw-bold text-dark">
                                        <td colspan="5" class="ps-2 py-1"><i class="fa-solid fa-briefcase me-1" style="color: #6f42c1;"></i> PLANES DE ESTUDIO</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Planes BTI</td>
                                        <td class="text-center"><input class="form-check-input check-crear-ver" type="checkbox" name="permisos_modulos[]" value="planes_bti:ver" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-crear" type="checkbox" name="permisos_modulos[]" value="planes_bti:crear" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-eliminar" type="checkbox" name="permisos_modulos[]" value="planes_bti:eliminar" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-consultar" type="checkbox" name="permisos_modulos[]" value="planes_bti:consultar" checked></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Planes BGNE</td>
                                        <td class="text-center"><input class="form-check-input check-crear-ver" type="checkbox" name="permisos_modulos[]" value="planes_bgne:ver" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-crear" type="checkbox" name="permisos_modulos[]" value="planes_bgne:crear" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-eliminar" type="checkbox" name="permisos_modulos[]" value="planes_bgne:eliminar" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-consultar" type="checkbox" name="permisos_modulos[]" value="planes_bgne:consultar" checked></td>
                                    </tr>

                                    <!-- Categoría: Formatos -->
                                    <tr class="table-secondary fw-bold text-dark">
                                        <td colspan="5" class="ps-2 py-1"><i class="fa-solid fa-print me-1 text-danger"></i> IMPRIMIR FORMATOS</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Boletas BTI</td>
                                        <td class="text-center"><input class="form-check-input check-crear-ver" type="checkbox" name="permisos_modulos[]" value="formatos_boletas_bti:ver" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-crear" type="checkbox" name="permisos_modulos[]" value="formatos_boletas_bti:crear" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-eliminar" type="checkbox" name="permisos_modulos[]" value="formatos_boletas_bti:eliminar" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-consultar" type="checkbox" name="permisos_modulos[]" value="formatos_boletas_bti:consultar" checked></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Kardex BGNE</td>
                                        <td class="text-center"><input class="form-check-input check-crear-ver" type="checkbox" name="permisos_modulos[]" value="formatos_kardex_bgne:ver" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-crear" type="checkbox" name="permisos_modulos[]" value="formatos_kardex_bgne:crear" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-eliminar" type="checkbox" name="permisos_modulos[]" value="formatos_kardex_bgne:eliminar" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-consultar" type="checkbox" name="permisos_modulos[]" value="formatos_kardex_bgne:consultar" checked></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Actas Extraordinarios</td>
                                        <td class="text-center"><input class="form-check-input check-crear-ver" type="checkbox" name="permisos_modulos[]" value="formatos_actas_extraordinarios:ver" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-crear" type="checkbox" name="permisos_modulos[]" value="formatos_actas_extraordinarios:crear" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-eliminar" type="checkbox" name="permisos_modulos[]" value="formatos_actas_extraordinarios:eliminar" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-consultar" type="checkbox" name="permisos_modulos[]" value="formatos_actas_extraordinarios:consultar" checked></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Listas de Asistencia</td>
                                        <td class="text-center"><input class="form-check-input check-crear-ver" type="checkbox" name="permisos_modulos[]" value="formatos_listas_asistencia:ver" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-crear" type="checkbox" name="permisos_modulos[]" value="formatos_listas_asistencia:crear" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-eliminar" type="checkbox" name="permisos_modulos[]" value="formatos_listas_asistencia:eliminar" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-consultar" type="checkbox" name="permisos_modulos[]" value="formatos_listas_asistencia:consultar" checked></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Actas Calificaciones O.</td>
                                        <td class="text-center"><input class="form-check-input check-crear-ver" type="checkbox" name="permisos_modulos[]" value="formatos_actas_ordinarios:ver" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-crear" type="checkbox" name="permisos_modulos[]" value="formatos_actas_ordinarios:crear" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-eliminar" type="checkbox" name="permisos_modulos[]" value="formatos_actas_ordinarios:eliminar" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-consultar" type="checkbox" name="permisos_modulos[]" value="formatos_actas_ordinarios:consultar" checked></td>
                                    </tr>

                                    <!-- Categoría: Generaciones -->
                                    <tr class="table-secondary fw-bold text-dark">
                                        <td colspan="5" class="ps-2 py-1"><i class="fa-solid fa-graduation-cap me-1 text-dark"></i> GENERACIONES</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Generaciones</td>
                                        <td class="text-center"><input class="form-check-input check-crear-ver" type="checkbox" name="permisos_modulos[]" value="generaciones:ver" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-crear" type="checkbox" name="permisos_modulos[]" value="generaciones:crear" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-eliminar" type="checkbox" name="permisos_modulos[]" value="generaciones:eliminar" checked></td>
                                        <td class="text-center"><input class="form-check-input check-crear-consultar" type="checkbox" name="permisos_modulos[]" value="generaciones:consultar" checked></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light" style="border-top: none;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary bg-dark border-0 fw-bold">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Guardar Personal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDITAR PERSONAL --}}
<div class="modal fade" id="modalEditarPersonal" tabindex="-1" aria-labelledby="modalEditarPersonalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header bg-dark text-white" style="border-bottom: none;">
                <h5 class="modal-title fw-bold" id="modalEditarPersonalLabel">
                    <i class="fa-solid fa-user-pen me-2 text-warning"></i> Editar Cuenta de Personal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditarPersonal">
                @csrf
                @method('PUT')
                <input type="hidden" id="editPersonalId">
                <div class="modal-body text-dark bg-white">
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold">Nombre Completo *</label>
                        <input type="text" class="form-control form-control-premium text-dark" id="editNombre" name="nombre" required style="color: #000 !important; font-weight: 600;">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-bold">Nombre de Usuario *</label>
                            <input type="text" class="form-control form-control-premium text-dark" id="editUsuario" name="usuario" required style="color: #000 !important; font-weight: 600;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-bold">Contraseña (Nueva)</label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-premium text-dark" id="editPassword" name="password" placeholder="Dejar en blanco para conservar" style="color: #000 !important; font-weight: 600;">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('editPassword')">
                                    <i class="fa-solid fa-eye" id="toggleIcon_editPassword"></i>
                                </button>
                            </div>
                            <small class="text-muted">Ingresa solo si deseas actualizarla.</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-bold">Rol de Personal *</label>
                            <select class="form-select form-control-premium text-dark" id="editRol" name="rol" required style="color: #000 !important; font-weight: 600;">
                                <option value="Control Escolar">Control Escolar</option>
                                <option value="Administrativo">Administrativo</option>
                                <option value="Subdirector">Subdirector</option>
                                <option value="Director">Director</option>
                                <option value="Prefecto">Prefecto</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-bold">Estatus *</label>
                            <select class="form-select form-control-premium text-dark" id="editStatus" name="status" required style="color: #000 !important; font-weight: 600;">
                                <option value="ACTIVO">ACTIVO</option>
                                <option value="INACTIVO">INACTIVO</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 border-top pt-3">
                        <label class="form-label text-dark fw-bold">Módulos Habilitados (Permisos CRUD):</label>
                        <div class="table-responsive border rounded bg-white" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-sm table-hover align-middle mb-0 text-dark" style="font-size: 0.85rem; color: #000 !important;">
                                <thead class="table-dark sticky-top">
                                    <tr>
                                        <th class="ps-3 text-white">Módulo / Submódulo</th>
                                        <th class="text-center" style="width: 100px; color: white;">
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input col-check-all" type="checkbox" data-col="ver" id="allVer-edit">
                                                <label class="form-check-label text-white fw-bold fs-7" for="allVer-edit" style="cursor: pointer;">Ver</label>
                                            </div>
                                        </th>
                                        <th class="text-center" style="width: 100px; color: white;">
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input col-check-all" type="checkbox" data-col="crear" id="allCrear-edit">
                                                <label class="form-check-label text-white fw-bold fs-7" for="allCrear-edit" style="cursor: pointer;">Crear/Editar</label>
                                            </div>
                                        </th>
                                        <th class="text-center" style="width: 100px; color: white;">
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input col-check-all" type="checkbox" data-col="eliminar" id="allEliminar-edit">
                                                <label class="form-check-label text-white fw-bold fs-7" for="allEliminar-edit" style="cursor: pointer;">Eliminar</label>
                                            </div>
                                        </th>
                                        <th class="text-center" style="width: 100px; color: white;">
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input col-check-all" type="checkbox" data-col="consultar" id="allConsultar-edit">
                                                <label class="form-check-label text-white fw-bold fs-7" for="allConsultar-edit" style="cursor: pointer;">Consultar</label>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Categoría: General -->
                                    <tr class="table-secondary fw-bold text-dark">
                                        <td colspan="5" class="ps-2 py-1"><i class="fa-solid fa-gear me-1"></i> GENERAL</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Inicio (Dashboard)</td>
                                        <td class="text-center"><input class="form-check-input check-edit-ver" type="checkbox" name="permisos_modulos[]" value="inicio:ver"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-crear" type="checkbox" name="permisos_modulos[]" value="inicio:crear"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-eliminar" type="checkbox" name="permisos_modulos[]" value="inicio:eliminar"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-consultar" type="checkbox" name="permisos_modulos[]" value="inicio:consultar"></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Avisos y Pendientes</td>
                                        <td class="text-center"><input class="form-check-input check-edit-ver" type="checkbox" name="permisos_modulos[]" value="notificaciones:ver"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-crear" type="checkbox" name="permisos_modulos[]" value="notificaciones:crear"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-eliminar" type="checkbox" name="permisos_modulos[]" value="notificaciones:eliminar"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-consultar" type="checkbox" name="permisos_modulos[]" value="notificaciones:consultar"></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Búsqueda de Grupo</td>
                                        <td class="text-center"><input class="form-check-input check-edit-ver" type="checkbox" name="permisos_modulos[]" value="busqueda_grupos:ver"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-crear" type="checkbox" name="permisos_modulos[]" value="busqueda_grupos:crear"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-eliminar" type="checkbox" name="permisos_modulos[]" value="busqueda_grupos:eliminar"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-consultar" type="checkbox" name="permisos_modulos[]" value="busqueda_grupos:consultar"></td>
                                    </tr>

                                    <!-- Categoría: Alumnos -->
                                    <tr class="table-secondary fw-bold text-dark">
                                        <td colspan="5" class="ps-2 py-1"><i class="fa-solid fa-graduation-cap me-1 text-success"></i> ALUMNOS</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Listado de Alumnos</td>
                                        <td class="text-center"><input class="form-check-input check-edit-ver" type="checkbox" name="permisos_modulos[]" value="alumnos_list:ver"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-crear" type="checkbox" name="permisos_modulos[]" value="alumnos_list:crear"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-eliminar" type="checkbox" name="permisos_modulos[]" value="alumnos_list:eliminar"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-consultar" type="checkbox" name="permisos_modulos[]" value="alumnos_list:consultar"></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Captura de Calificaciones</td>
                                        <td class="text-center"><input class="form-check-input check-edit-ver" type="checkbox" name="permisos_modulos[]" value="alumnos_calificaciones:ver"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-crear" type="checkbox" name="permisos_modulos[]" value="alumnos_calificaciones:crear"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-eliminar" type="checkbox" name="permisos_modulos[]" value="alumnos_calificaciones:eliminar"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-consultar" type="checkbox" name="permisos_modulos[]" value="alumnos_calificaciones:consultar"></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Asistencia Alumnos</td>
                                        <td class="text-center"><input class="form-check-input check-edit-ver" type="checkbox" name="permisos_modulos[]" value="alumnos_asistencias:ver"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-crear" type="checkbox" name="permisos_modulos[]" value="alumnos_asistencias:crear"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-eliminar" type="checkbox" name="permisos_modulos[]" value="alumnos_asistencias:eliminar"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-consultar" type="checkbox" name="permisos_modulos[]" value="alumnos_asistencias:consultar"></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Reporte de Asistencias</td>
                                        <td class="text-center"><input class="form-check-input check-edit-ver" type="checkbox" name="permisos_modulos[]" value="alumnos_reporte_asistencias:ver"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-crear" type="checkbox" name="permisos_modulos[]" value="alumnos_reporte_asistencias:crear"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-eliminar" type="checkbox" name="permisos_modulos[]" value="alumnos_reporte_asistencias:eliminar"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-consultar" type="checkbox" name="permisos_modulos[]" value="alumnos_reporte_asistencias:consultar"></td>
                                    </tr>

                                    <!-- Categoría: Docentes -->
                                    <tr class="table-secondary fw-bold text-dark">
                                        <td colspan="5" class="ps-2 py-1"><i class="fa-solid fa-person-chalkboard me-1 text-info"></i> DOCENTES</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Listado de Docentes</td>
                                        <td class="text-center"><input class="form-check-input check-edit-ver" type="checkbox" name="permisos_modulos[]" value="docentes_list:ver"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-crear" type="checkbox" name="permisos_modulos[]" value="docentes_list:crear"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-eliminar" type="checkbox" name="permisos_modulos[]" value="docentes_list:eliminar"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-consultar" type="checkbox" name="permisos_modulos[]" value="docentes_list:consultar"></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Asistencia Docente</td>
                                        <td class="text-center"><input class="form-check-input check-edit-ver" type="checkbox" name="permisos_modulos[]" value="docentes_asistencias:ver"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-crear" type="checkbox" name="permisos_modulos[]" value="docentes_asistencias:crear"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-eliminar" type="checkbox" name="permisos_modulos[]" value="docentes_asistencias:eliminar"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-consultar" type="checkbox" name="permisos_modulos[]" value="docentes_asistencias:consultar"></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Horario Docente</td>
                                        <td class="text-center"><input class="form-check-input check-edit-ver" type="checkbox" name="permisos_modulos[]" value="docentes_horarios:ver"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-crear" type="checkbox" name="permisos_modulos[]" value="docentes_horarios:crear"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-eliminar" type="checkbox" name="permisos_modulos[]" value="docentes_horarios:eliminar"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-consultar" type="checkbox" name="permisos_modulos[]" value="docentes_horarios:consultar"></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Permisos de Captura</td>
                                        <td class="text-center"><input class="form-check-input check-edit-ver" type="checkbox" name="permisos_modulos[]" value="docentes_permisos_captura:ver"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-crear" type="checkbox" name="permisos_modulos[]" value="docentes_permisos_captura:crear"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-eliminar" type="checkbox" name="permisos_modulos[]" value="docentes_permisos_captura:eliminar"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-consultar" type="checkbox" name="permisos_modulos[]" value="docentes_permisos_captura:consultar"></td>
                                    </tr>

                                    <!-- Categoría: Grupos -->
                                    <tr class="table-secondary fw-bold text-dark">
                                        <td colspan="5" class="ps-2 py-1"><i class="fa-solid fa-users me-1 text-secondary"></i> GRUPOS</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Inicio Grupos</td>
                                        <td class="text-center"><input class="form-check-input check-edit-ver" type="checkbox" name="permisos_modulos[]" value="grupos_list:ver"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-crear" type="checkbox" name="permisos_modulos[]" value="grupos_list:crear"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-eliminar" type="checkbox" name="permisos_modulos[]" value="grupos_list:eliminar"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-consultar" type="checkbox" name="permisos_modulos[]" value="grupos_list:consultar"></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Captura Calificaciones</td>
                                        <td class="text-center"><input class="form-check-input check-edit-ver" type="checkbox" name="permisos_modulos[]" value="grupos_calificaciones:ver"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-crear" type="checkbox" name="permisos_modulos[]" value="grupos_calificaciones:crear"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-eliminar" type="checkbox" name="permisos_modulos[]" value="grupos_calificaciones:eliminar"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-consultar" type="checkbox" name="permisos_modulos[]" value="grupos_calificaciones:consultar"></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Horarios</td>
                                        <td class="text-center"><input class="form-check-input check-edit-ver" type="checkbox" name="permisos_modulos[]" value="grupos_horarios:ver"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-crear" type="checkbox" name="permisos_modulos[]" value="grupos_horarios:crear"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-eliminar" type="checkbox" name="permisos_modulos[]" value="grupos_horarios:eliminar"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-consultar" type="checkbox" name="permisos_modulos[]" value="grupos_horarios:consultar"></td>
                                    </tr>

                                    <!-- Categoría: Materias -->
                                    <tr class="table-secondary fw-bold text-dark">
                                        <td colspan="5" class="ps-2 py-1"><i class="fa-solid fa-book-bookmark me-1 text-danger"></i> MATERIAS</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Materias</td>
                                        <td class="text-center"><input class="form-check-input check-edit-ver" type="checkbox" name="permisos_modulos[]" value="materias:ver"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-crear" type="checkbox" name="permisos_modulos[]" value="materias:crear"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-eliminar" type="checkbox" name="permisos_modulos[]" value="materias:eliminar"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-consultar" type="checkbox" name="permisos_modulos[]" value="materias:consultar"></td>
                                    </tr>

                                    <!-- Categoría: Planes -->
                                    <tr class="table-secondary fw-bold text-dark">
                                        <td colspan="5" class="ps-2 py-1"><i class="fa-solid fa-briefcase me-1" style="color: #6f42c1;"></i> PLANES DE ESTUDIO</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Planes BTI</td>
                                        <td class="text-center"><input class="form-check-input check-edit-ver" type="checkbox" name="permisos_modulos[]" value="planes_bti:ver"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-crear" type="checkbox" name="permisos_modulos[]" value="planes_bti:crear"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-eliminar" type="checkbox" name="permisos_modulos[]" value="planes_bti:eliminar"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-consultar" type="checkbox" name="permisos_modulos[]" value="planes_bti:consultar"></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Planes BGNE</td>
                                        <td class="text-center"><input class="form-check-input check-edit-ver" type="checkbox" name="permisos_modulos[]" value="planes_bgne:ver"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-crear" type="checkbox" name="permisos_modulos[]" value="planes_bgne:crear"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-eliminar" type="checkbox" name="permisos_modulos[]" value="planes_bgne:eliminar"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-consultar" type="checkbox" name="permisos_modulos[]" value="planes_bgne:consultar"></td>
                                    </tr>

                                    <!-- Categoría: Formatos -->
                                    <tr class="table-secondary fw-bold text-dark">
                                        <td colspan="5" class="ps-2 py-1"><i class="fa-solid fa-print me-1 text-danger"></i> IMPRIMIR FORMATOS</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Boletas BTI</td>
                                        <td class="text-center"><input class="form-check-input check-edit-ver" type="checkbox" name="permisos_modulos[]" value="formatos_boletas_bti:ver"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-crear" type="checkbox" name="permisos_modulos[]" value="formatos_boletas_bti:crear"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-eliminar" type="checkbox" name="permisos_modulos[]" value="formatos_boletas_bti:eliminar"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-consultar" type="checkbox" name="permisos_modulos[]" value="formatos_boletas_bti:consultar"></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Kardex BGNE</td>
                                        <td class="text-center"><input class="form-check-input check-edit-ver" type="checkbox" name="permisos_modulos[]" value="formatos_kardex_bgne:ver"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-crear" type="checkbox" name="permisos_modulos[]" value="formatos_kardex_bgne:crear"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-eliminar" type="checkbox" name="permisos_modulos[]" value="formatos_kardex_bgne:eliminar"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-consultar" type="checkbox" name="permisos_modulos[]" value="formatos_kardex_bgne:consultar"></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Actas Extraordinarios</td>
                                        <td class="text-center"><input class="form-check-input check-edit-ver" type="checkbox" name="permisos_modulos[]" value="formatos_actas_extraordinarios:ver"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-crear" type="checkbox" name="permisos_modulos[]" value="formatos_actas_extraordinarios:crear"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-eliminar" type="checkbox" name="permisos_modulos[]" value="formatos_actas_extraordinarios:eliminar"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-consultar" type="checkbox" name="permisos_modulos[]" value="formatos_actas_extraordinarios:consultar"></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Listas de Asistencia</td>
                                        <td class="text-center"><input class="form-check-input check-edit-ver" type="checkbox" name="permisos_modulos[]" value="formatos_listas_asistencia:ver"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-crear" type="checkbox" name="permisos_modulos[]" value="formatos_listas_asistencia:crear"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-eliminar" type="checkbox" name="permisos_modulos[]" value="formatos_listas_asistencia:eliminar"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-consultar" type="checkbox" name="permisos_modulos[]" value="formatos_listas_asistencia:consultar"></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">- Actas Calificaciones O.</td>
                                        <td class="text-center"><input class="form-check-input check-edit-ver" type="checkbox" name="permisos_modulos[]" value="formatos_actas_ordinarios:ver"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-crear" type="checkbox" name="permisos_modulos[]" value="formatos_actas_ordinarios:crear"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-eliminar" type="checkbox" name="permisos_modulos[]" value="formatos_actas_ordinarios:eliminar"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-consultar" type="checkbox" name="permisos_modulos[]" value="formatos_actas_ordinarios:consultar"></td>
                                    </tr>

                                    <!-- Categoría: Generaciones -->
                                    <tr class="table-secondary fw-bold text-dark">
                                        <td colspan="5" class="ps-2 py-1"><i class="fa-solid fa-graduation-cap me-1 text-dark"></i> GENERACIONES</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Generaciones</td>
                                        <td class="text-center"><input class="form-check-input check-edit-ver" type="checkbox" name="permisos_modulos[]" value="generaciones:ver"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-crear" type="checkbox" name="permisos_modulos[]" value="generaciones:crear"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-eliminar" type="checkbox" name="permisos_modulos[]" value="generaciones:eliminar"></td>
                                        <td class="text-center"><input class="form-check-input check-edit-consultar" type="checkbox" name="permisos_modulos[]" value="generaciones:consultar"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light" style="border-top: none;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning text-dark fw-bold">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let personalCompleto = [];

    // Cargar Lista de Personal
    window.cargarPersonal = function() {
        fetch('/personal/lista')
            .then(res => res.json())
            .then(resp => {
                personalCompleto = resp.data || [];
                renderPersonal(personalCompleto);
            })
            .catch(err => {
                console.error(err);
                document.getElementById('tablaPersonal').innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-danger py-4 fw-bold">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> Error al cargar datos.
                        </td>
                    </tr>
                `;
            });
    };

    function renderPersonal(personal) {
        const tbody = document.getElementById('tablaPersonal');
        tbody.innerHTML = '';

        if (personal.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        No se encontraron cuentas de personal registradas.
                    </td>
                </tr>
            `;
            return;
        }

        const moduloColores = {
            inicio: 'bg-primary text-white',
            notificaciones: 'bg-warning text-dark',
            alumnos: 'bg-success text-white',
            docentes: 'bg-info text-dark',
            grupos: 'bg-secondary text-white',
            materias: 'bg-danger text-white',
            planes: 'bg-dark text-white',
            formatos: 'bg-danger text-white',
            generaciones: 'bg-dark text-white'
        };

        personal.forEach(item => {
            const statusClass = item.status === 'ACTIVO' ? 'badge-activo' : 'badge-inactivo';
            
            // Render de módulos permitidos
            let modulosBadges = '';
            if (item.permisos_modulos) {
                const modulos = item.permisos_modulos.split(',');
                modulos.forEach(m => {
                    const badgeClass = moduloColores[m.trim()] || 'bg-light text-dark';
                    modulosBadges += `<span class="modulo-badge ${badgeClass}">${m.toUpperCase()}</span>`;
                });
            } else {
                modulosBadges = '<span class="text-muted fs-7">Ninguno</span>';
            }

            tbody.innerHTML += `
                <tr>
                    <td>${item.idPersonal}</td>
                    <td class="fw-bold">${item.nombre}</td>
                    <td><code class="text-dark fw-bold">${item.usuario}</code></td>
                    <td><span class="badge bg-light text-dark border fw-bold fs-7">${item.rol}</span></td>
                    <td style="max-width: 300px; overflow-wrap: break-word;">${modulosBadges}</td>
                    <td><span class="badge ${statusClass}">${item.status}</span></td>
                    <td class="text-center" style="white-space: nowrap;">
                        <button class="btn btn-warning btn-sm btn-action me-1" onclick="editarPersonal(${item.idPersonal})" title="Editar datos y permisos">
                            <i class="fa-solid fa-user-pen"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btn-action" onclick="eliminarPersonal(${item.idPersonal})" title="Eliminar cuenta">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    }

    // Buscador en tiempo real
    document.getElementById('buscadorPersonal').addEventListener('input', function(e) {
        const text = e.target.value.toLowerCase().trim();
        const filtrados = personalCompleto.filter(p => 
            p.nombre.toLowerCase().includes(text) || 
            p.usuario.toLowerCase().includes(text) || 
            p.rol.toLowerCase().includes(text)
        );
        renderPersonal(filtrados);
    });

    // Alta de Personal
    document.getElementById('formAltaPersonal').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        const data = {};
        
        formData.forEach((value, key) => {
            if (key.endsWith('[]')) {
                const cleanKey = key.slice(0, -2);
                if (!data[cleanKey]) data[cleanKey] = [];
                data[cleanKey].push(value);
            } else {
                data[key] = value;
            }
        });

        fetch('/personal', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Registrado!',
                    text: 'La cuenta del personal se ha creado correctamente.',
                    confirmButtonColor: '#0f172a'
                });
                form.reset();
                bootstrap.Modal.getInstance(document.getElementById('modalAltaPersonal')).hide();
                cargarPersonal();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: resp.message || 'No se pudo crear la cuenta de personal.',
                    confirmButtonColor: '#0f172a'
                });
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de red al guardar el personal.',
                confirmButtonColor: '#0f172a'
            });
        });
    });

    // Cargar datos en Modal de Edición
    window.editarPersonal = function(id) {
        fetch(`/personal/${id}`)
            .then(res => res.json())
            .then(resp => {
                if (resp.success) {
                    const data = resp.data;
                    document.getElementById('editPersonalId').value = data.idPersonal;
                    document.getElementById('editNombre').value = data.nombre;
                    document.getElementById('editUsuario').value = data.usuario;
                    document.getElementById('editRol').value = data.rol;
                    document.getElementById('editStatus').value = data.status;
                    document.getElementById('editPassword').value = '';

                    // Desmarcar todos los checkboxes de permisos
                    const checkboxes = document.querySelectorAll('#formEditarPersonal input[type="checkbox"]');
                    checkboxes.forEach(chk => chk.checked = false);

                    // Marcar según permisos guardados
                    if (data.permisos_modulos) {
                        const modulos = data.permisos_modulos.split(',').map(m => m.trim());
                        modulos.forEach(m => {
                            const chk = document.querySelector(`#formEditarPersonal input[type="checkbox"][value="${m}"]`);
                            if (chk) chk.checked = true;
                        });
                    }

                    const modal = new bootstrap.Modal(document.getElementById('modalEditarPersonal'));
                    modal.show();
                }
            })
            .catch(err => console.error(err));
    };

    // Actualizar Personal
    document.getElementById('formEditarPersonal').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('editPersonalId').value;
        const form = e.target;
        const formData = new FormData(form);
        const data = {};

        formData.forEach((value, key) => {
            if (key.endsWith('[]')) {
                const cleanKey = key.slice(0, -2);
                if (!data[cleanKey]) data[cleanKey] = [];
                data[cleanKey].push(value);
            } else {
                data[key] = value;
            }
        });

        // Asegurarnos de que enviamos permisos_modulos como array, incluso si está vacío
        if (!data['permisos_modulos']) {
            data['permisos_modulos'] = [];
        }

        fetch(`/personal/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Actualizado!',
                    text: 'La cuenta de personal se actualizó correctamente.',
                    confirmButtonColor: '#0f172a'
                });
                bootstrap.Modal.getInstance(document.getElementById('modalEditarPersonal')).hide();
                cargarPersonal();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: resp.message || 'No se pudo actualizar la cuenta.',
                    confirmButtonColor: '#0f172a'
                });
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de red al actualizar el personal.',
                confirmButtonColor: '#0f172a'
            });
        });
    });

    // Eliminar Personal
    window.eliminarPersonal = function(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer y revocará el acceso permanentemente.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar cuenta',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/personal/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(resp => {
                    if (resp.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: 'El registro de personal ha sido borrado.',
                            confirmButtonColor: '#0f172a'
                        });
                        cargarPersonal();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: resp.message || 'No se pudo eliminar la cuenta.',
                            confirmButtonColor: '#0f172a'
                        });
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error de red al intentar eliminar.',
                        confirmButtonColor: '#0f172a'
                    });
                });
            }
        });
    };

    window.togglePasswordVisibility = function(id) {
        const inp = document.getElementById(id);
        const icon = document.getElementById('toggleIcon_' + id);
        if (inp.type === 'password') {
            inp.type = 'text';
            icon.className = 'fa-solid fa-eye-slash';
        } else {
            inp.type = 'password';
            icon.className = 'fa-solid fa-eye';
        }
    };

    // Select all checkboxes in a column
    document.querySelectorAll('.col-check-all').forEach(header => {
        header.addEventListener('change', function() {
            const col = this.getAttribute('data-col');
            const prefix = this.id.includes('edit') ? 'edit' : 'crear';
            document.querySelectorAll(`.check-${prefix}-${col}`).forEach(chk => {
                chk.checked = this.checked;
            });
        });
    });

    // Carga inicial
    cargarPersonal();
});
</script>

@endsection
