@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            <span class="badge bg-danger rounded-pill px-3 py-2 fs-6 fw-bold" id="badge-header-total">
                {{ $notificaciones['totales']['total'] ?? 0 }} alertas
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
                <span class="badge bg-light text-dark ms-2" id="badge-pill-todos">{{ $notificaciones['totales']['total'] ?? 0 }}</span>
            </button>
            <button class="btn btn-premium-pill" onclick="filtrarAlertas('documentos', this)">
                <i class="bi bi-file-earmark-text-fill me-1"></i> Documentación
                <span class="badge bg-light text-dark ms-2" id="badge-pill-documentos">{{ $notificaciones['totales']['documentos'] ?? 0 }}</span>
            </button>
            <button class="btn btn-premium-pill" onclick="filtrarAlertas('equivalencias', this)">
                <i class="bi bi-folder-symlink-fill me-1"></i> Equivalencias
                <span class="badge bg-light text-dark ms-2" id="badge-pill-equivalencias">{{ $notificaciones['totales']['equivalencias'] ?? 0 }}</span>
            </button>
            <button class="btn btn-premium-pill" onclick="filtrarAlertas('grupos', this)">
                <i class="bi bi-calendar-event-fill me-1"></i> Término de Grupos
                <span class="badge bg-light text-dark ms-2" id="badge-pill-grupos">{{ $notificaciones['totales']['grupos'] ?? 0 }}</span>
            </button>
            <button class="btn btn-premium-pill" onclick="filtrarAlertas('resueltas', this)">
                <i class="bi bi-check2-all me-1"></i> Resueltas / Omitidas
                <span class="badge bg-light text-dark ms-2" id="badge-pill-resueltas">{{ $notificaciones['totales']['resueltas'] ?? count($notificaciones['resueltas'] ?? []) }}</span>
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
                                    <button type="button" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1 px-3 py-1.5 shadow-sm btn-resolver-alerta" 
                                            onclick="abrirModalResolver('documentos', {{ $doc['idAlumno'] }}, '{{ addslashes($doc['nombre']) }}', '{{ addslashes($doc['detalle']) }}', '{{ $doc['subtipo'] ?? 'general' }}', this)"
                                            title="Marcar como resuelta o descartar advertencia"
                                            style="border-radius: 10px; font-size: 0.8rem; font-weight: 600; transition: all 0.2s;">
                                        <i class="bi bi-check-circle-fill"></i> Quitar Alerta
                                    </button>
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
                                    <button type="button" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1 px-3 py-1.5 shadow-sm btn-resolver-alerta" 
                                            onclick="abrirModalResolver('equivalencias', {{ $eq['idAlumno'] }}, '{{ addslashes($eq['nombre']) }}', '{{ addslashes($eq['detalle']) }}', '{{ $eq['subtipo'] ?? 'equivalencia' }}', this)"
                                            title="Marcar como resuelta o descartar advertencia"
                                            style="border-radius: 10px; font-size: 0.8rem; font-weight: 600; transition: all 0.2s;">
                                        <i class="bi bi-check-circle-fill"></i> Quitar Alerta
                                    </button>
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
                                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 mt-1 d-inline-flex align-items-center justify-content-center gap-1"
                                                onclick="abrirModalResolver('grupos', {{ $gp['idGrupo'] }}, 'Grupo: {{ addslashes($gp['clave']) }}', '{{ addslashes($gp['detalle']) }}', 'termino_ciclo', this)"
                                                style="border-radius: 8px; font-size: 0.75rem;">
                                            <i class="bi bi-eye-slash"></i> Omitir Aviso
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        <!-- SECCIÓN: ALERTAS RESUELTAS / OMITIDAS -->
                        @if(!empty($notificaciones['resueltas']))
                            @foreach($notificaciones['resueltas'] as $res)
                                <tr class="fila-alerta" data-categoria="resueltas" style="display: none;">
                                    <td>
                                        @if($res['accion'] == 'resuelto')
                                            <span class="badge bg-success px-2 py-1 fw-bold text-uppercase" style="font-size: 0.73rem;">
                                                <i class="bi bi-check2 me-1"></i>Resuelto
                                            </span>
                                        @else
                                            <span class="badge bg-secondary px-2 py-1 fw-bold text-uppercase" style="font-size: 0.73rem;">
                                                <i class="bi bi-eye-slash me-1"></i>No es Alerta
                                            </span>
                                        @endif
                                    </td>
                                    <td class="fw-bold text-dark">{{ $res['nombre'] }}</td>
                                    <td class="text-secondary">
                                        <span class="text-dark fw-medium">{{ $res['motivo'] }}</span><br>
                                        <small class="text-muted"><i class="bi bi-person me-1"></i>{{ $res['usuario'] }} • {{ $res['fecha'] }}</small>
                                    </td>
                                    <td class="text-secondary">
                                        <strong>CCT:</strong> {{ $res['cct'] }} <br>
                                        <strong>Grupo:</strong> {{ $res['grupo'] }}
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1 px-2.5 py-1"
                                                onclick="reactivarAlerta('{{ $res['tipo'] }}', {{ $res['id_referencia'] }}, '{{ $res['subtipo'] }}', '{{ addslashes($res['nombre']) }}', this)"
                                                style="border-radius: 8px; font-size: 0.78rem; font-weight: 500;">
                                            <i class="bi bi-arrow-counterclockwise"></i> Reactivar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                        <tr id="fila-vacia" style="{{ ($notificaciones['totales']['total'] ?? 0) == 0 ? '' : 'display: none !important;' }}">
                            <td colspan="5" class="text-center py-5 text-secondary">
                                <div class="mb-2">
                                    <i class="bi bi-shield-check text-success" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="fw-bold text-dark" id="texto-vacio-titulo">Todo al corriente</h5>
                                <p class="mb-0 text-muted" id="texto-vacio-desc">No se encontraron avisos ni pendientes en esta sección.</p>
                            </td>
                        </tr>

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

    .btn-resolver-alerta:hover {
        background-color: #198754 !important;
        color: white !important;
        transform: scale(1.03);
    }
</style>

<script>
let categoriaActual = 'todos';

function filtrarAlertas(categoria, botonElement) {
    categoriaActual = categoria;
    const botones = document.querySelectorAll('.btn-premium-pill');
    botones.forEach(btn => btn.classList.remove('active'));
    botonElement.classList.add('active');

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
        title: '<span style="font-size: 1.25rem; font-weight: 700; color: #1e293b;">Gestionar Advertencia</span>',
        html: `
            <div class="text-start p-2" style="font-size: 0.9rem;">
                <div class="mb-2"><strong>Alumno / Grupo:</strong> <span class="text-primary fw-bold">${nombre}</span></div>
                <div class="mb-3 text-muted"><strong>Pendiente:</strong> ${detalle}</div>
                <hr class="my-2">
                <p class="mb-2 fw-semibold text-dark">¿Cómo deseas clasificar esta advertencia?</p>
                <div class="form-check mb-2 p-2 rounded" style="background: rgba(25, 135, 84, 0.08); border: 1px solid rgba(25, 135, 84, 0.2);">
                    <input class="form-check-input ms-1" type="radio" name="swal_accion" id="opt_resuelto" value="resuelto" checked>
                    <label class="form-check-label fw-bold text-success ms-2" for="opt_resuelto" style="cursor: pointer;">
                        <i class="bi bi-check-circle-fill me-1"></i> Ya se resolvió / Entregado
                        <div class="text-muted small fw-normal mt-0.5">El alumno ya entregó el documento o se completó el trámite pendiente.</div>
                    </label>
                </div>
                <div class="form-check mb-3 p-2 rounded" style="background: rgba(108, 117, 125, 0.08); border: 1px solid rgba(108, 117, 125, 0.2);">
                    <input class="form-check-input ms-1" type="radio" name="swal_accion" id="opt_ignorar" value="ignorar">
                    <label class="form-check-label fw-bold text-secondary ms-2" for="opt_ignorar" style="cursor: pointer;">
                        <i class="bi bi-eye-slash-fill me-1"></i> No es alerta / Omitir
                        <div class="text-muted small fw-normal mt-0.5">No aplica para este caso o se autoriza como excepción permanente.</div>
                    </label>
                </div>
                <div class="mb-1">
                    <label class="form-label small text-muted mb-1">Nota o motivo (opcional):</label>
                    <input type="text" id="swal_motivo" class="form-control form-control-sm" placeholder="Ej. Entregó constancia física / Validado por control escolar" style="border-radius: 8px;">
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-check-lg me-1"></i> Aplicar y Quitar Alerta',
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
        confirmButtonText: '<i class="bi bi-arrow-counterclockwise me-1"></i> Sí, reactivar',
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
</script>
@endsection
