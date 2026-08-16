@extends('layouts.app')

@section('content')
<div class="page-container">
    <!-- Encabezado del Sistema -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ url()->previous() }}" class="btn btn-regresar">
                <i class="fa-solid fa-arrow-left me-2"></i>
                Regresar
            </a>
        </div>
        <h3 class="page-title mb-0">
            <i class="fa-solid fa-bell me-2"></i>Centro de Avisos y Pendientes
        </h3>
        <div>
            <span class="badge bg-danger rounded-pill px-3 py-2 fs-6 fw-bold">
                {{ $notificaciones['totales']['total'] }} alertas
            </span>
        </div>
    </div>

    @if(isset($notificaciones['error']))
        <div class="alert alert-danger border-0 rounded-4 shadow-lg p-4 d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-2 me-3"></i>
            <div>
                <h5 class="alert-heading fw-bold mb-1">Error de Comunicación</h5>
                <p class="mb-0">{{ $notificaciones['error'] }}</p>
            </div>
        </div>
    @else
        <!-- Filtros Rápidos (Pills usando el color del tema) -->
        <div class="d-flex flex-wrap gap-2 mb-4">
            <button class="btn btn-premium-pill active" onclick="filtrarAlertas('todos', this)">
                <i class="bi bi-grid-fill me-1"></i> Todos
                <span class="badge bg-light text-dark ms-2">{{ $notificaciones['totales']['total'] }}</span>
            </button>
            <button class="btn btn-premium-pill" onclick="filtrarAlertas('documentos', this)">
                <i class="bi bi-file-earmark-text-fill me-1"></i> Documentación
                <span class="badge bg-light text-dark ms-2">{{ $notificaciones['totales']['documentos'] }}</span>
            </button>
            <button class="btn btn-premium-pill" onclick="filtrarAlertas('equivalencias', this)">
                <i class="bi bi-folder-symlink-fill me-1"></i> Equivalencias
                <span class="badge bg-light text-dark ms-2">{{ $notificaciones['totales']['equivalencias'] }}</span>
            </button>
            <button class="btn btn-premium-pill" onclick="filtrarAlertas('grupos', this)">
                <i class="bi bi-calendar-event-fill me-1"></i> Término de Grupos
                <span class="badge bg-light text-dark ms-2">{{ $notificaciones['totales']['grupos'] }}</span>
            </button>
        </div>

        <div class="glass-card shadow-lg bg-white rounded-4 p-3 border">
            <div class="table-responsive">
                <table class="table glass-table align-middle mb-0">
                    <thead class="table-head">
                        <tr>
                            <th style="font-weight:700;">TIPO</th>
                            <th style="font-weight:700;">ALUMNO / GRUPO</th>
                            <th style="font-weight:700;">DETALLE DEL PENDIENTE</th>
                            <th style="font-weight:700;">CCT / GRUPO</th>
                            <th class="text-center" style="font-weight:700; width: 220px;">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody id="tablaNotificaciones">
                        
                        <!-- SECCIÓN: DOCUMENTACIÓN PENDIENTE -->
                        @foreach($notificaciones['documentos'] as $doc)
                            <tr class="fila-alerta" data-categoria="documentos">
                                <td>
                                    <span class="badge bg-danger px-2 py-1 fw-bold text-uppercase" style="font-size: 0.73rem;">Falta Doc.</span>
                                </td>
                                <td class="fw-bold text-dark">{{ $doc['nombre'] }}</td>
                                <td class="text-secondary">
                                    <i class="bi bi-exclamation-circle-fill text-danger me-1"></i>{{ $doc['detalle'] }}
                                </td>
                                <td class="text-secondary">
                                    <strong>CCT:</strong> {{ $doc['cct'] }} <br>
                                    <strong>Grupo:</strong> {{ $doc['grupo'] }}
                                </td>
                                <td class="text-center">
                                    <span class="text-muted">—</span>
                                </td>
                            </tr>
                        @endforeach

                        <!-- SECCIÓN: EQUIVALENCIAS PENDIENTES -->
                        @foreach($notificaciones['equivalencias'] as $eq)
                            <tr class="fila-alerta" data-categoria="equivalencias">
                                <td>
                                    <span class="badge bg-warning text-dark px-2 py-1 fw-bold text-uppercase" style="font-size: 0.73rem;">Equivalencia</span>
                                </td>
                                <td class="fw-bold text-dark">{{ $eq['nombre'] }}</td>
                                <td class="text-secondary">
                                    @if($eq['tipo'] == 'pago_pendiente')
                                        <i class="bi bi-cash-stack text-warning me-1"></i>
                                    @else
                                        <i class="bi bi-send-check-fill text-success me-1"></i>
                                    @endif
                                    {{ $eq['detalle'] }}
                                </td>
                                <td class="text-secondary">
                                    <strong>CCT:</strong> {{ $eq['cct'] }} <br>
                                    <strong>Grupo:</strong> {{ $eq['grupo'] }}
                                </td>
                                <td class="text-center">
                                    <span class="text-muted">—</span>
                                </td>
                            </tr>
                        @endforeach

                        <!-- SECCIÓN: TÉRMINO DE GRUPOS -->
                        @foreach($notificaciones['grupos'] as $gp)
                            <tr class="fila-alerta" data-categoria="grupos">
                                <td>
                                    <span class="badge bg-info text-dark px-2 py-1 fw-bold text-uppercase" style="font-size: 0.73rem;">Término Ciclo</span>
                                </td>
                                <td class="fw-bold text-dark">Grupo: {{ $gp['clave'] }}</td>
                                <td class="text-secondary">
                                    <i class="bi bi-clock-history text-info me-1"></i>{{ $gp['detalle'] }}
                                </td>
                                <td class="text-secondary">
                                    <strong>CCT:</strong> {{ $gp['cct'] }}
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column gap-1 align-items-center">
                                        <a href="/grupos/captura_calificaciones" class="btn btn-ver btn-sm text-white w-100">
                                            <i class="bi bi-journal-check me-1"></i> Capturar Notas
                                        </a>
                                        @if(isset($gp['id_centroTrabajo']) && $gp['id_centroTrabajo'] == 3)
                                            <a href="/horarios?grupo_id={{ $gp['idGrupo'] }}&es_prehorario=1" class="btn btn-warning btn-sm text-dark w-100">
                                                <i class="bi bi-calendar-plus me-1"></i> Armar Pre-Horario
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @if($notificaciones['totales']['total'] == 0)
                            <tr>
                                <td colspan="5" class="text-center py-5 text-secondary">
                                    <div class="mb-2">
                                        <i class="bi bi-shield-check text-success" style="font-size: 3rem;"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark">Todo al corriente</h5>
                                    <p class="mb-0">No se encontraron avisos ni pendientes activos en el sistema.</p>
                                </td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<!-- Estilos CSS Personalizados Integrados con el Tema -->
<style>
    .btn-premium-pill {
        background: rgba(49, 125, 146, 0.08);
        color: rgb(49, 125, 146);
        border: 1px solid rgba(49, 125, 146, 0.25);
        padding: 0.55rem 1.1rem;
        border-radius: 12px;
        font-size: 0.88rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-premium-pill:hover {
        background: rgba(49, 125, 146, 0.15);
        color: rgb(38, 104, 123);
        transform: translateY(-1px);
    }
    .btn-premium-pill.active {
        background: rgb(49, 125, 146) !important;
        border-color: rgb(49, 125, 146) !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(49, 125, 146, 0.3);
    }
    
    .glass-table {
        background: transparent !important;
    }
    
    .glass-table tr {
        border-bottom: 1px solid rgba(0, 0, 0, 0.08) !important;
    }
    
    .glass-table td {
        padding: 1rem 0.75rem !important;
        color: #212529 !important;
    }
    
    .glass-table td strong {
        color: #334155 !important;
    }
</style>

<script>
function filtrarAlertas(categoria, botonElement) {
    const botones = document.querySelectorAll('.btn-premium-pill');
    botones.forEach(btn => btn.classList.remove('active'));
    botonElement.classList.add('active');

    const filas = document.querySelectorAll('.fila-alerta');
    filas.forEach(fila => {
        const cat = fila.dataset.categoria;
        if (categoria === 'todos' || cat === categoria) {
            fila.style.setProperty('display', 'table-row', 'important');
        } else {
            fila.style.setProperty('display', 'none', 'important');
        }
    });
}
</script>
@endsection
