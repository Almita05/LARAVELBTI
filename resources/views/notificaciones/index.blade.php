@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="page-container">
    <!-- Encabezado Estandarizado de Página -->
    <div class="page-header-standard">
        <div class="page-header-left">
            <a href="{{ url()->previous() }}" class="btn-regresar">
                <i class="ti ti-arrow-left"></i>
                Regresar
            </a>
            <h1 class="page-heading">Avisos y Pendientes</h1>
            <span class="badge-soft badge-soft-danger font-monospace fw-bold" id="badge-header-total" style="font-size: 0.78rem;">
                {{ $notificaciones['totales']['total'] ?? 0 }} alertas
            </span>
        </div>
    </div>

    @if(isset($notificaciones['error']))
        <div class="alert alert-danger border-0 rounded-3 shadow-xs p-3 d-flex align-items-center mb-4" role="alert">
            <i class="ti ti-alert-triangle fs-3 me-3 text-danger"></i>
            <div>
                <h6 class="alert-heading fw-bold mb-0.5">Error de Comunicación</h6>
                <p class="mb-0 fs-8">{{ $notificaciones['error'] }}</p>
            </div>
        </div>
    @else
        <!-- Barra de Filtros (Tabs con Contadores) -->
        <div class="d-flex flex-wrap gap-2 mb-4 justify-content-between align-items-center">
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn-tab-filter btn-premium-pill active" onclick="filtrarAlertas('todos', this)">
                    <i class="ti ti-layout-grid"></i>
                    <span>Todos</span>
                    <span class="tab-counter" id="badge-pill-todos">{{ $notificaciones['totales']['total'] ?? 0 }}</span>
                </button>
                <button type="button" class="btn-tab-filter btn-premium-pill" onclick="filtrarAlertas('documentos', this)">
                    <i class="ti ti-file-text"></i>
                    <span>Documentación</span>
                    <span class="tab-counter" id="badge-pill-documentos">{{ $notificaciones['totales']['documentos'] ?? 0 }}</span>
                </button>
                <button type="button" class="btn-tab-filter btn-premium-pill" onclick="filtrarAlertas('equivalencias', this)">
                    <i class="ti ti-arrows-exchange"></i>
                    <span>Equivalencias</span>
                    <span class="tab-counter" id="badge-pill-equivalencias">{{ $notificaciones['totales']['equivalencias'] ?? 0 }}</span>
                </button>
                <button type="button" class="btn-tab-filter btn-premium-pill" onclick="filtrarAlertas('grupos', this)">
                    <i class="ti ti-calendar-time"></i>
                    <span>Grupos y Horarios</span>
                    <span class="tab-counter" id="badge-pill-grupos">{{ $notificaciones['totales']['grupos'] ?? 0 }}</span>
                </button>
                <button type="button" class="btn-tab-filter btn-premium-pill" onclick="filtrarAlertas('nuevos_alumnos', this)">
                    <i class="ti ti-user-plus"></i>
                    <span>Nuevos Alumnos</span>
                    <span class="tab-counter" id="badge-pill-nuevos_alumnos">{{ $notificaciones['totales']['nuevos_alumnos'] ?? 0 }}</span>
                </button>
                <button type="button" class="btn-tab-filter btn-premium-pill" onclick="filtrarAlertas('resueltas', this)" id="btn-pill-resueltas">
                    <i class="ti ti-checks"></i>
                    <span>Resueltas / Omitidas</span>
                    <span class="tab-counter" id="badge-pill-resueltas">{{ $notificaciones['totales']['resueltas'] ?? count($notificaciones['resueltas'] ?? []) }}</span>
                </button>
            </div>
            <div id="contenedor-btn-limpiar" style="display: none;">
                <button type="button" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-2 fw-semibold shadow-xs" onclick="limpiarResueltasOmitidas()" id="btn-limpiar-resueltas" title="Limpiar y dejar en cero la sección de resueltas y omitidas">
                    <i class="ti ti-trash"></i> Limpiar Resueltas / Omitidas
                </button>
            </div>
        </div>

        <!-- Contenedor de Tabla Unificado SaaS -->
        <div class="saas-surface shadow-card border-0 bg-white" style="border: 1px solid var(--border-subtle) !important; border-radius: var(--radius-lg); overflow: hidden;">
            <div class="table-responsive">
                <table class="table align-middle mb-0" style="width: 100%; border-collapse: separate; border-spacing: 0;">
                    <thead style="background: var(--bg-canvas); border-bottom: 1px solid var(--border-subtle);">
                        <tr>
                            <th style="font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; padding: 12px 16px; border: none; width: 150px;">TIPO</th>
                            <th style="font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; padding: 12px 16px; border: none; width: 230px;">ALUMNO / GRUPO</th>
                            <th style="font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; padding: 12px 16px; border: none;">DETALLE DEL PENDIENTE</th>
                            <th style="font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; padding: 12px 16px; border: none; width: 140px;">CCT / GRUPO</th>
                            <th class="text-end" style="font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; padding: 12px 16px; border: none; width: 220px;">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody id="tablaNotificaciones">
                        
                        <!-- SECCIÓN: DOCUMENTACIÓN PENDIENTE -->
                        @foreach($notificaciones['documentos'] as $doc)
                            <tr class="fila-alerta" data-categoria="documentos">
                                <td style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                    <span class="badge-soft-tipo badge-tipo-falta">Falta Doc.</span>
                                </td>
                                <td style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                    <span class="fw-semibold text-slate-800" style="font-size: 0.875rem;">{{ $doc['nombre'] }}</span>
                                </td>
                                <td style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                    <div class="d-flex align-items-start gap-2" style="font-size: 0.8125rem; color: var(--text-secondary); line-height: 1.5;">
                                        <i class="ti ti-alert-circle text-danger mt-0.5" style="font-size: 16px; flex-shrink: 0;"></i>
                                        <span>{{ $doc['detalle'] }}</span>
                                    </div>
                                </td>
                                <td style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge-cct-pill">{{ $doc['cct'] }}</span>
                                        <span class="font-monospace text-slate-500" style="font-size: 0.75rem;">{{ $doc['grupo'] }}</span>
                                    </div>
                                </td>
                                <td class="text-end" style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                    <div class="d-flex align-items-center gap-1.5 justify-content-end">
                                        <button type="button" class="btn-alerta-success btn-resolver-alerta" 
                                                onclick="abrirModalResolver('documentos', {{ $doc['idAlumno'] }}, '{{ addslashes($doc['nombre']) }}', '{{ addslashes($doc['detalle']) }}', '{{ $doc['subtipo'] ?? 'general' }}', this)"
                                                title="Marcar como resuelta o descartar advertencia">
                                            <i class="ti ti-check"></i> Quitar Alerta
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        <!-- SECCIÓN: EQUIVALENCIAS PENDIENTES -->
                        @foreach($notificaciones['equivalencias'] as $eq)
                            <tr class="fila-alerta" data-categoria="equivalencias">
                                <td style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                    <span class="badge-soft-tipo badge-tipo-equivalencia">Equivalencia</span>
                                </td>
                                <td style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                    <span class="fw-semibold text-slate-800" style="font-size: 0.875rem;">{{ $eq['nombre'] }}</span>
                                </td>
                                <td style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                    <div class="d-flex align-items-start gap-2" style="font-size: 0.8125rem; color: var(--text-secondary); line-height: 1.5;">
                                        @if($eq['tipo'] == 'pago_pendiente')
                                            <i class="ti ti-coin text-warning mt-0.5" style="font-size: 16px; flex-shrink: 0;"></i>
                                        @else
                                            <i class="ti ti-file-certificate text-success mt-0.5" style="font-size: 16px; flex-shrink: 0;"></i>
                                        @endif
                                        <span>{{ $eq['detalle'] }}</span>
                                    </div>
                                </td>
                                <td style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge-cct-pill">{{ $eq['cct'] }}</span>
                                        <span class="font-monospace text-slate-500" style="font-size: 0.75rem;">{{ $eq['grupo'] }}</span>
                                    </div>
                                </td>
                                <td class="text-end" style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                    <div class="d-flex align-items-center gap-1.5 justify-content-end">
                                        <button type="button" class="btn-alerta-success btn-resolver-alerta" 
                                                onclick="abrirModalResolver('equivalencias', {{ $eq['idAlumno'] }}, '{{ addslashes($eq['nombre']) }}', '{{ addslashes($eq['detalle']) }}', '{{ $eq['subtipo'] ?? 'equivalencia' }}', this)"
                                                title="Marcar como resuelta o descartar advertencia">
                                            <i class="ti ti-check"></i> Quitar Alerta
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        <!-- SECCIÓN: GRUPOS Y HORARIOS -->
                        @foreach($notificaciones['grupos'] as $gp)
                            @php
                                $badgeTipo = $gp['badge_tipo'] ?? 'Término Ciclo';
                                $badgeClass = 'badge-tipo-termino';
                                if (stripos($badgeTipo, 'horario') !== false || stripos($badgeTipo, 'incompleto') !== false) {
                                    $badgeClass = 'badge-tipo-horario';
                                } elseif (stripos($badgeTipo, 'equivalencia') !== false) {
                                    $badgeClass = 'badge-tipo-equivalencia';
                                }

                                $detalleTexto = $gp['detalle'] ?? '';
                                $esAtencion = str_contains($detalleTexto, '[ATENCIÓN:') || str_contains($detalleTexto, 'ATENCIÓN:');
                                $detalleLimpio = preg_replace('/\[?ATENCIÓN:\s*(.*?)\]?/i', '$1', $detalleTexto);
                            @endphp
                            <tr class="fila-alerta" data-categoria="grupos">
                                <td style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                    <span class="badge-soft-tipo {{ $badgeClass }}">
                                        {{ $badgeTipo }}
                                    </span>
                                </td>
                                <td style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                    <span class="font-monospace fw-semibold text-slate-800" style="font-size: 0.85rem;">{{ $gp['clave'] }}</span>
                                    @if(isset($gp['esCritico']) && $gp['esCritico'])
                                        <span class="badge-urgente ms-1">
                                            <i class="ti ti-alert-circle me-0.5"></i>Urgente
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                    <div class="d-flex align-items-start gap-2" style="font-size: 0.8125rem; color: var(--text-secondary); line-height: 1.5;">
                                        @if($esAtencion)
                                            <i class="ti ti-info-circle mt-0.5" style="color: #B45309; font-size: 16px; flex-shrink: 0;"></i>
                                        @else
                                            <i class="ti ti-clock mt-0.5" style="color: var(--text-muted); font-size: 16px; flex-shrink: 0;"></i>
                                        @endif
                                        <span>{{ $detalleLimpio }}</span>
                                    </div>
                                </td>
                                <td style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge-cct-pill">{{ $gp['cct'] }}</span>
                                    </div>
                                </td>
                                <td class="text-end" style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                    <div class="d-flex align-items-center gap-1.5 justify-content-end flex-wrap">
                                        @if(isset($gp['accion_tipo']) && $gp['accion_tipo'] == 'armar_prehorario')
                                            <a href="/horarios?grupo_id={{ $gp['idGrupo'] }}&es_prehorario=1" class="btn-alerta-cta">
                                                <i class="ti ti-calendar-plus"></i> Armar Pre-Horario
                                            </a>
                                        @elseif(isset($gp['accion_tipo']) && in_array($gp['accion_tipo'], ['armar_horario', 'completar_horario']))
                                            <a href="/horarios?grupo_id={{ $gp['idGrupo'] }}" class="btn-alerta-cta">
                                                <i class="ti ti-calendar-check"></i> {{ $gp['accion_tipo'] == 'completar_horario' ? 'Completar Horario' : 'Armar Horario' }}
                                            </a>
                                        @else
                                            <a href="/grupos/captura_calificaciones" class="btn-alerta-cta">
                                                <i class="ti ti-edit"></i> Capturar Notas
                                            </a>
                                            @if(isset($gp['id_centroTrabajo']) && $gp['id_centroTrabajo'] == 3)
                                                <a href="/horarios?grupo_id={{ $gp['idGrupo'] }}&es_prehorario=1" class="btn-alerta-cta">
                                                    <i class="ti ti-calendar-plus"></i> Armar Pre-Horario
                                                </a>
                                            @else
                                                <a href="/horarios?grupo_id={{ $gp['idGrupo'] }}" class="btn-alerta-secondary">
                                                    <i class="ti ti-calendar"></i> Ver Horario
                                                </a>
                                            @endif
                                        @endif
                                        <button type="button" class="btn-ghost-omitir"
                                                onclick="abrirModalResolver('grupos', {{ $gp['idGrupo'] }}, 'Grupo: {{ addslashes($gp['clave']) }}', '{{ addslashes($gp['detalle']) }}', '{{ $gp['subtipo'] ?? 'general' }}', this)"
                                                title="Omitir Aviso">
                                            <i class="ti ti-eye-off"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        <!-- SECCIÓN: NUEVOS ALUMNOS REGISTRADOS -->
                        @if(!empty($notificaciones['nuevos_alumnos']))
                            @foreach($notificaciones['nuevos_alumnos'] as $na)
                                <tr class="fila-alerta" data-categoria="nuevos_alumnos">
                                    <td style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                        <span class="badge-soft-tipo badge-tipo-nuevo">
                                            <i class="ti ti-user-plus me-1"></i>Nuevo Alumno
                                        </span>
                                    </td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                        <span class="fw-semibold text-slate-800 d-block" style="font-size: 0.875rem;">{{ $na['nombre'] }}</span>
                                        @if(!empty($na['matricula']) && $na['matricula'] !== 'Sin Matrícula')
                                            <small class="text-muted font-monospace"><i class="ti ti-id-badge-2 me-0.5"></i>{{ $na['matricula'] }}</small>
                                        @endif
                                    </td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                        <div style="font-size: 0.8125rem; color: var(--text-secondary); line-height: 1.5;">
                                            <div>
                                                <i class="ti ti-user-check me-1 text-primary"></i>
                                                Registrado por: <strong class="text-slate-800">{{ $na['creador'] ?? 'Docente/Admin' }}</strong>
                                            </div>
                                            <div class="text-muted fs-8 mt-0.5">
                                                <i class="ti ti-clock me-1"></i>{{ $na['fecha_creacion'] }} @if(!empty($na['hora_creacion'])) a las {{ $na['hora_creacion'] }} @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                        <div class="d-flex flex-column gap-1">
                                            <span class="badge-cct-pill">{{ $na['cct'] }}</span>
                                            <span class="font-monospace text-slate-500" style="font-size: 0.75rem;">{{ $na['grupo'] }}</span>
                                        </div>
                                    </td>
                                    <td class="text-end" style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                        <div class="d-flex align-items-center gap-1.5 justify-content-end flex-wrap">
                                            <a href="/alumnos/{{ $na['idAlumno'] }}/kardex" class="btn-alerta-cta" title="Ver Kárdex">
                                                <i class="ti ti-file-text"></i> Ver Kárdex
                                            </a>
                                            <button type="button" class="btn-alerta-success btn-resolver-alerta"
                                                    onclick="abrirModalResolver('nuevos_alumnos', {{ $na['idAlumno'] }}, '{{ addslashes($na['nombre']) }}', 'Nuevo alumno en grupo {{ addslashes($na['grupo']) }}', 'nuevo_registro', this)"
                                                    title="Marcar como enterado / visto">
                                                <i class="ti ti-check"></i> Enterado
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                        <!-- SECCIÓN: ALERTAS RESUELTAS / OMITIDAS -->
                        @if(!empty($notificaciones['resueltas']))
                            @foreach($notificaciones['resueltas'] as $res)
                                <tr class="fila-alerta" data-categoria="resueltas" style="display: none;">
                                    <td style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                        @if($res['accion'] == 'resuelto')
                                            <span class="badge-soft-tipo badge-tipo-resuelto">
                                                <i class="ti ti-check me-1"></i>Resuelto
                                            </span>
                                        @else
                                            <span class="badge-soft-tipo badge-tipo-ignorado">
                                                <i class="ti ti-eye-off me-1"></i>No es Alerta
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                        <span class="fw-semibold text-slate-800" style="font-size: 0.875rem;">{{ $res['nombre'] }}</span>
                                    </td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                        <div style="font-size: 0.8125rem; color: var(--text-secondary); line-height: 1.5;">
                                            <span class="text-slate-800 fw-medium">{{ $res['motivo'] }}</span>
                                            <div class="text-muted fs-8 mt-0.5"><i class="ti ti-user me-1"></i>{{ $res['usuario'] }} • {{ $res['fecha'] }}</div>
                                        </div>
                                    </td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                        <div class="d-flex flex-column gap-1">
                                            <span class="badge-cct-pill">{{ $res['cct'] }}</span>
                                            <span class="font-monospace text-slate-500" style="font-size: 0.75rem;">{{ $res['grupo'] }}</span>
                                        </div>
                                    </td>
                                    <td class="text-end" style="padding: 12px 16px; border-bottom: 1px solid var(--border-subtle);">
                                        <button type="button" class="btn-alerta-secondary"
                                                onclick="reactivarAlerta('{{ $res['tipo'] }}', {{ $res['id_referencia'] }}, '{{ $res['subtipo'] }}', '{{ addslashes($res['nombre']) }}', this)">
                                            <i class="ti ti-rotate-clockwise me-1"></i> Reactivar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                        <tr id="fila-vacia" style="{{ ($notificaciones['totales']['total'] ?? 0) == 0 ? '' : 'display: none !important;' }}">
                            <td colspan="5" class="text-center py-5 text-secondary">
                                <div class="mb-2">
                                    <i class="ti ti-circle-check text-success" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="fw-bold text-slate-800" id="texto-vacio-titulo">Todo al corriente</h5>
                                <p class="mb-0 text-muted fs-8" id="texto-vacio-desc">No se encontraron avisos ni pendientes en esta sección.</p>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<!-- Estilos CSS SaaS Integrados con Design Tokens -->
<style>
    /* Tabs Horizontales de Filtros */
    .btn-tab-filter {
        background: var(--bg-surface);
        border: 1px solid var(--border-subtle);
        color: var(--text-secondary);
        height: 36px;
        padding: 0 12px;
        border-radius: var(--radius-md);
        font-size: 0.8125rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: var(--shadow-xs);
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-tab-filter:hover {
        background: var(--bg-muted);
        color: var(--text-primary);
    }
    .btn-tab-filter.active {
        background: var(--brand-primary) !important;
        color: #FFFFFF !important;
        border-color: var(--brand-primary) !important;
    }
    .btn-tab-filter .tab-counter {
        padding: 1px 6px;
        border-radius: 999px;
        font-size: 0.75rem;
        font-family: 'JetBrains Mono', monospace;
        font-weight: 600;
        background: var(--bg-muted);
        color: var(--text-secondary);
        line-height: 1.2;
    }
    .btn-tab-filter.active .tab-counter {
        background: rgba(255, 255, 255, 0.2);
        color: #FFFFFF;
    }

    /* Soft Badges Semánticos */
    .badge-soft-tipo {
        height: 24px;
        padding: 0 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .badge-tipo-falta {
        background: #FEF2F2;
        color: #DC2626;
        border: 1px solid #FECACA;
    }
    .badge-tipo-equivalencia {
        background: #FEF3C7;
        color: #B45309;
        border: 1px solid #FDE68A;
    }
    .badge-tipo-horario {
        background: #FFEDD5;
        color: #C2410C;
        border: 1px solid #FED7AA;
    }
    .badge-tipo-termino {
        background: #E0F2FE;
        color: #0369A1;
        border: 1px solid #BAE6FD;
    }
    .badge-tipo-nuevo {
        background: #F0F7F8;
        color: #1E5B66;
        border: 1px solid #CBE4E8;
    }
    .badge-tipo-resuelto {
        background: #DCFCE7;
        color: #15803D;
        border: 1px solid #BBF7D0;
    }
    .badge-tipo-ignorado {
        background: #F1F5F9;
        color: #475569;
        border: 1px solid #E2E8F0;
    }

    .badge-urgente {
        background: #FEF2F2;
        color: #DC2626;
        border: 1px solid #FECACA;
        font-size: 0.7rem;
        padding: 1px 7px;
        border-radius: 999px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        vertical-align: middle;
    }

    .badge-cct-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: fit-content;
        padding: 1px 7px;
        border-radius: 4px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        background: #EBF5F8;
        color: #1E5B66;
        border: 1px solid #CBE4E8;
    }

    /* Botones de Acción en Tabla */
    .btn-alerta-cta {
        background: var(--brand-primary) !important;
        color: #FFFFFF !important;
        height: 32px;
        padding: 0 12px;
        border-radius: var(--radius-sm);
        font-size: 0.8125rem;
        font-weight: 500;
        white-space: nowrap;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        box-shadow: var(--shadow-xs);
        transition: background 0.15s ease;
    }
    .btn-alerta-cta:hover {
        background: var(--brand-primary-hover) !important;
        color: #FFFFFF !important;
    }

    .btn-alerta-success {
        background: #F0FDF4 !important;
        color: #15803D !important;
        border: 1px solid #BBF7D0 !important;
        height: 32px;
        padding: 0 12px;
        border-radius: var(--radius-sm);
        font-size: 0.8125rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.15s ease;
    }
    .btn-alerta-success:hover {
        background: #DCFCE7 !important;
        border-color: #86EFAC !important;
        color: #15803D !important;
    }

    .btn-ghost-omitir {
        width: 32px;
        height: 32px;
        color: var(--text-muted);
        border: 1px solid var(--border-subtle);
        background: transparent;
        border-radius: var(--radius-sm);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-ghost-omitir:hover {
        background: var(--bg-muted);
        color: var(--text-primary);
        border-color: #CBD5E1;
    }

    .btn-alerta-secondary {
        background: var(--bg-surface);
        color: var(--text-secondary);
        border: 1px solid var(--border-subtle);
        height: 32px;
        padding: 0 10px;
        border-radius: var(--radius-sm);
        font-size: 0.8125rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-alerta-secondary:hover {
        background: var(--bg-muted);
        color: var(--text-primary);
    }
</style>

<script>
let categoriaActual = 'todos';

function filtrarAlertas(categoria, botonElement) {
    categoriaActual = categoria;
    const botones = document.querySelectorAll('.btn-premium-pill, .btn-tab-filter');
    botones.forEach(btn => btn.classList.remove('active'));
    botonElement.classList.add('active');

    // Mostrar/ocultar botón de limpiar según pestaña seleccionada y conteo
    const contLimpiar = document.getElementById('contenedor-btn-limpiar');
    const badgeResueltas = document.getElementById('badge-pill-resueltas');
    const countResueltas = parseInt(badgeResueltas?.innerText) || 0;
    if (contLimpiar) {
        if (categoria === 'resueltas' && countResueltas > 0) {
            contLimpiar.style.display = 'block';
        } else {
            contLimpiar.style.display = 'none';
        }
    }

    const filas = document.querySelectorAll('.fila-alerta');
    let visibles = 0;

    filas.forEach(fila => {
        const cat = fila.dataset.categoria;
        let mostrar = false;

        if (categoria === 'todos') {
            mostrar = (cat !== 'resueltas');
        } else {
            mostrar = (cat === categoria);
        }

        if (mostrar) {
            fila.style.setProperty('display', 'table-row', 'important');
            visibles++;
        } else {
            fila.style.setProperty('display', 'none', 'important');
        }
    });

    const filaVacia = document.getElementById('fila-vacia');
    if (filaVacia) {
        if (visibles === 0) {
            filaVacia.style.setProperty('display', 'table-row', 'important');
            const titulo = document.getElementById('texto-vacio-titulo');
            const desc = document.getElementById('texto-vacio-desc');
            if (categoria === 'resueltas') {
                if (titulo) titulo.innerText = 'Sin registros históricos';
                if (desc) desc.innerText = 'No hay advertencias marcadas como resueltas u omitidas.';
            } else {
                if (titulo) titulo.innerText = 'Todo al corriente';
                if (desc) desc.innerText = 'No se encontraron avisos ni pendientes en esta sección.';
            }
        } else {
            filaVacia.style.setProperty('display', 'none', 'important');
        }
    }
}

function abrirModalResolver(tipo, idReferencia, nombre, detalle, subtipo, btnElement) {
    Swal.fire({
        title: '<span style="font-size: 1.25rem; font-weight: 700; color: #0f172a;">Gestionar Advertencia</span>',
        html: `
            <div class="text-start p-2" style="font-size: 0.9rem;">
                <div class="mb-2"><strong>Alumno / Grupo:</strong> <span class="text-primary fw-bold">${nombre}</span></div>
                <div class="mb-3 text-muted"><strong>Pendiente:</strong> ${detalle}</div>
                <hr class="my-2" style="border-color: #e2e8f0;">
                <p class="mb-2 fw-semibold text-dark">¿Cómo deseas clasificar esta advertencia?</p>
                <div class="form-check mb-2 p-2 rounded" style="background: rgba(25, 135, 84, 0.08); border: 1px solid rgba(25, 135, 84, 0.2);">
                    <input class="form-check-input ms-1" type="radio" name="swal_accion" id="opt_resuelto" value="resuelto" checked>
                    <label class="form-check-label fw-bold text-success ms-2" for="opt_resuelto" style="cursor: pointer;">
                        <i class="ti ti-circle-check me-1"></i> Ya se resolvió / Entregado
                        <div class="text-muted small fw-normal mt-0.5">El alumno ya entregó el documento o se completó el trámite pendiente.</div>
                    </label>
                </div>
                <div class="form-check mb-3 p-2 rounded" style="background: rgba(108, 117, 125, 0.08); border: 1px solid rgba(108, 117, 125, 0.2);">
                    <input class="form-check-input ms-1" type="radio" name="swal_accion" id="opt_ignorar" value="ignorar">
                    <label class="form-check-label fw-bold text-secondary ms-2" for="opt_ignorar" style="cursor: pointer;">
                        <i class="ti ti-eye-off me-1"></i> No es alerta / Omitir
                        <div class="text-muted small fw-normal mt-0.5">No aplica para este caso o se autoriza como excepción permanente.</div>
                    </label>
                </div>
                <div class="mb-1">
                    <label class="form-label small text-muted mb-1">Nota o motivo (opcional):</label>
                    <input type="text" id="swal_motivo" class="form-control form-control-sm" placeholder="Ej. Entregó constancia física / Validado por control escolar" style="border-radius: 6px;">
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="ti ti-check me-1"></i> Aplicar y Quitar Alerta',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#317d92',
        cancelButtonColor: '#64748b',
        reverseButtons: true,
        focusConfirm: false,
        preConfirm: () => {
            const accion = document.querySelector('input[name="swal_accion"]:checked')?.value || 'resuelto';
            const motivo = document.getElementById('swal_motivo')?.value?.trim() || '';
            return { accion, motivo };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { accion, motivo } = result.value;
            Swal.fire({
                title: 'Actualizando...',
                text: 'Guardando estado de la advertencia',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('/notificaciones/resolver', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    tipo: tipo,
                    id_referencia: idReferencia,
                    accion: accion,
                    subtipo: subtipo,
                    motivo: motivo
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Advertencia actualizada!',
                        text: accion === 'resuelto' ? 'Marcada como resuelta exitosamente.' : 'Descartada del panel de pendientes.',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    // Transición y remoción de fila
                    const tr = btnElement.closest('tr');
                    if (tr) {
                        tr.style.transition = 'all 0.35s ease';
                        tr.style.opacity = '0';
                        tr.style.transform = 'translateX(25px)';
                        setTimeout(() => {
                            tr.remove();
                            actualizarContadores(tipo);
                        }, 350);
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error || 'No se pudo actualizar la advertencia.'
                    });
                }
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de red',
                    text: err.message
                });
            });
        }
    });
}

function reactivarAlerta(tipo, idReferencia, subtipo, nombre, btnElement) {
    Swal.fire({
        title: '¿Reactivar advertencia?',
        text: `¿Deseas volver a mostrar la advertencia para "${nombre}" en el panel de pendientes activos?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="ti ti-rotate-clockwise me-1"></i> Sí, reactivar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#317d92',
        cancelButtonColor: '#64748b'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Reactivando...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('/notificaciones/reactivar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    tipo: tipo,
                    id_referencia: idReferencia,
                    subtipo: subtipo
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Reactivada',
                        text: 'La advertencia volverá a mostrarse en los pendientes.',
                        timer: 1200,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Error', data.error || 'No se pudo reactivar la advertencia.', 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error', err.message, 'error');
            });
        }
    });
}

function limpiarResueltasOmitidas() {
    const badgeResueltas = document.getElementById('badge-pill-resueltas');
    const count = parseInt(badgeResueltas?.innerText) || 0;

    if (count === 0) {
        Swal.fire({
            icon: 'info',
            title: 'Sección vacía',
            text: 'No hay alertas resueltas u omitidas para limpiar.'
        });
        return;
    }

    Swal.fire({
        title: '¿Limpiar sección?',
        text: `¿Deseas dejar en cero la sección de resueltas y omitidas (${count} registros)? Esta acción vacía el historial de resueltas sin reactivar las advertencias.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="ti ti-trash me-1"></i> Sí, limpiar todo',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Limpiando...',
                text: 'Dejando en cero la sección de resueltas y omitidas',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('/notificaciones/limpiar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Sección limpia!',
                        text: 'La sección de resueltas y omitidas ha quedado en cero.',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Error', data.error || 'No se pudo limpiar la sección.', 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error de red', err.message, 'error');
            });
        }
    });
}

function actualizarContadores(tipo) {
    // Actualizar pill de la categoría
    const badgeCat = document.getElementById(`badge-pill-${tipo}`);
    if (badgeCat) {
        let val = parseInt(badgeCat.innerText) || 0;
        if (val > 0) badgeCat.innerText = val - 1;
    }

    // Actualizar pill todos y badge principal
    const badgeTodos = document.getElementById('badge-pill-todos');
    const badgeHeader = document.getElementById('badge-header-total');
    let total = 0;
    if (badgeTodos) {
        let val = parseInt(badgeTodos.innerText) || 0;
        total = Math.max(0, val - 1);
        badgeTodos.innerText = total;
    }
    if (badgeHeader) {
        badgeHeader.innerText = `${total} alertas`;
    }

    // Actualizar badge del sidebar en tiempo real
    if (typeof window.actualizarBadgeSidebarNotificaciones === 'function') {
        window.actualizarBadgeSidebarNotificaciones(total);
    } else {
        const sidebarBadge = document.getElementById('sidebar-notificaciones-badge');
        if (sidebarBadge) {
            if (total > 0) {
                sidebarBadge.textContent = total > 99 ? '99+' : total;
                sidebarBadge.classList.remove('d-none');
            } else {
                sidebarBadge.textContent = '0';
                sidebarBadge.classList.add('d-none');
            }
        }
    }

    // Incrementar pill resueltas
    const badgeResueltas = document.getElementById('badge-pill-resueltas');
    if (badgeResueltas) {
        let val = parseInt(badgeResueltas.innerText) || 0;
        badgeResueltas.innerText = val + 1;
    }

    // Verificar si quedan filas visibles
    const filasRestantes = document.querySelectorAll('.fila-alerta');
    let visibles = 0;
    filasRestantes.forEach(fila => {
        const cat = fila.dataset.categoria;
        if (categoriaActual === 'todos' && cat !== 'resueltas') visibles++;
        else if (categoriaActual === cat) visibles++;
    });

    const filaVacia = document.getElementById('fila-vacia');
    if (filaVacia) {
        filaVacia.style.setProperty('display', visibles === 0 ? 'table-row' : 'none', 'important');
    }
}

// Sincronizar el badge del sidebar inmediatamente con el conteo de la página
document.addEventListener('DOMContentLoaded', function() {
    const totalInicial = {{ (int)($notificaciones['totales']['total'] ?? 0) }};
    if (typeof window.actualizarBadgeSidebarNotificaciones === 'function') {
        window.actualizarBadgeSidebarNotificaciones(totalInicial);
    }
});
</script>
@endsection
