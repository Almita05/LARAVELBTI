@extends('layouts.app')

@section('content')

<style>
/* Estilos premium para el panel de pendientes */
.timeline-card {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-left: 5px solid #cbd5e1;
    border-radius: 14px;
    padding: 20px;
    margin-bottom: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    transition: all 0.25s ease;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.timeline-card:hover {
    transform: translateX(4px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
}

.card-prorrroga { border-left-color: rgb(49, 125, 146); }
.card-finalizar { border-left-color: #f59e0b; }
.card-pendiente { border-left-color: #ef4444; }
.card-pendiente.bloqueado { border-left-color: #64748b; }

.stat-icon-wrapper {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
}
.stat-prorrogas { background-color: rgba(49, 125, 146, 0.12); color: rgb(49, 125, 146); }
.stat-finalizar { background-color: rgba(245, 158, 11, 0.12); color: #d97706; }
.stat-pendientes { background-color: rgba(239, 68, 68, 0.12); color: #dc2626; }

.btn-capturar-rapido {
    background: rgb(49, 125, 146);
    color: white;
    font-weight: 600;
    border-radius: 8px;
    padding: 8px 16px;
    border: none;
    font-size: 0.86rem;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-capturar-rapido:hover {
    background: rgb(38, 104, 123);
    color: white;
    transform: translateY(-1px);
}
.btn-bloqueado-info {
    background: #e2e8f0;
    color: #475569;
    font-weight: 600;
    border-radius: 8px;
    padding: 8px 16px;
    border: none;
    font-size: 0.86rem;
    cursor: default;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
</style>

<div class="page-container">

    {{-- Encabezado --}}
    <div class="mb-4">
        <h3 class="page-title mb-1">
            <i class="fa-solid fa-list-check me-2"></i>
            Avisos y Tareas Pendientes
        </h3>
        <p class="text-muted mb-0 small">Consulta las prórrogas otorgadas, el estatus de tus asignaturas asignadas y los avisos de cierre de periodos.</p>
    </div>

    {{-- Tarjetas de Estadísticas --}}
    <div class="row g-3 mb-4">
        {{-- Prórrogas --}}
        <div class="col-12 col-md-4">
            <div class="glass-card p-4 d-flex align-items-center gap-3 bg-white border">
                <div class="stat-icon-wrapper stat-prorrogas">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0" id="statCountProrrogas">0</h3>
                    <small class="text-muted fw-bold">Prórrogas Habilitadas</small>
                </div>
            </div>
        </div>
        {{-- Por Finalizar --}}
        <div class="col-12 col-md-4">
            <div class="glass-card p-4 d-flex align-items-center gap-3 bg-white border">
                <div class="stat-icon-wrapper stat-finalizar">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0" id="statCountFinalizar">0</h3>
                    <small class="text-muted fw-bold">Grupos por Terminar</small>
                </div>
            </div>
        </div>
        {{-- Pendientes --}}
        <div class="col-12 col-md-4">
            <div class="glass-card p-4 d-flex align-items-center gap-3 bg-white border">
                <div class="stat-icon-wrapper stat-pendientes">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0" id="statCountPendientes">0</h3>
                    <small class="text-muted fw-bold">Asignaturas Incompletas</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Contenedor de Timeline/Avisos --}}
    <div class="row">
        
        {{-- Sección de Prórrogas y Cierres --}}
        <div class="col-12 col-lg-6 mb-4">
            <div class="glass-card p-4 bg-white border h-100">
                <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-bullhorn text-primary me-2"></i> Prórrogas y Fechas Límite</h5>
                <hr class="mt-0">
                <div id="contenedorAlertasFechas" style="max-height: 480px; overflow-y: auto; padding-right: 4px;">
                    {{-- Llenado por JS --}}
                </div>
            </div>
        </div>

        {{-- Sección de Calificaciones Incompletas --}}
        <div class="col-12 col-lg-6 mb-4">
            <div class="glass-card p-4 bg-white border h-100">
                <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-pen-to-square text-danger me-2"></i> Capturas Incompletas</h5>
                <hr class="mt-0">
                <div id="contenedorCapturasIncompletas" style="max-height: 480px; overflow-y: auto; padding-right: 4px;">
                    {{-- Llenado por JS --}}
                </div>
            </div>
        </div>

    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    cargarPendientes();
});

function cargarPendientes() {
    const loaderAlertas = document.getElementById('contenedorAlertasFechas');
    const loaderCapturas = document.getElementById('contenedorCapturasIncompletas');
    
    loaderAlertas.innerHTML = '<div class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary"></div></div>';
    loaderCapturas.innerHTML = '<div class="text-center py-4"><div class="spinner-border spinner-border-sm text-danger"></div></div>';

    fetch('/docentes/pendientes/datos')
        .then(res => res.json())
        .then(data => {
            // Actualizar contadores
            document.getElementById('statCountProrrogas').textContent = data.prorrogas.length;
            document.getElementById('statCountFinalizar').textContent = data.por_finalizar.length;
            document.getElementById('statCountPendientes').textContent = data.calificaciones_pendientes.length;

            renderSeccionFechas(data.prorrogas, data.por_finalizar);
            renderSeccionCapturas(data.calificaciones_pendientes);
        })
        .catch(err => {
            console.error('Error al cargar pendientes:', err);
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'No se pudieron recuperar tus tareas pendientes.'
            });
        });
}

function formatearFechaEspanol(fechaStr) {
    if (!fechaStr) return '—';
    const partes = fechaStr.split('-');
    if (partes.length === 3) {
        return `${partes[2]}/${partes[1]}/${partes[0]}`;
    }
    return fechaStr;
}

function renderSeccionFechas(prorrogas, porFinalizar) {
    const contenedor = document.getElementById('contenedorAlertasFechas');
    contenedor.innerHTML = '';

    if (prorrogas.length === 0 && porFinalizar.length === 0) {
        contenedor.innerHTML = `
            <div class="text-center py-5 text-muted">
                <i class="fa-solid fa-circle-check fs-2 text-success mb-2"></i>
                <p class="mb-0 small fw-bold">Sin alertas de fechas vigentes.</p>
            </div>
        `;
        return;
    }

    let html = '';

    // Renderizar prórrogas
    prorrogas.forEach(p => {
        html += `
            <div class="timeline-card card-prorrroga">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon-wrapper stat-prorrogas">
                        <i class="fa-solid fa-calendar-plus"></i>
                    </div>
                    <div>
                        <strong class="text-dark d-block">Prórroga de Captura Asignada</strong>
                        <span class="text-muted small d-block">Asignatura: <strong>${p.nombre_materia}</strong></span>
                        <span class="text-muted small d-block">Grupo: <strong>${p.clave_grupo}</strong></span>
                    </div>
                </div>
                <div class="text-end">
                    <span class="badge bg-info text-dark px-2.5 py-1.5 fw-bold d-block mb-1" style="font-size: 0.8rem;">Hasta: ${formatearFechaEspanol(p.fecha_limite)}</span>
                    <small class="text-primary fw-bold" style="font-size: 0.72rem;">Quedan ${p.dias_restantes} día(s)</small>
                </div>
            </div>
        `;
    });

    // Renderizar grupos por finalizar
    porFinalizar.forEach(g => {
        html += `
            <div class="timeline-card card-finalizar">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon-wrapper stat-finalizar">
                        <i class="fa-solid fa-clock-four"></i>
                    </div>
                    <div>
                        <strong class="text-dark d-block">Cierre del Grupo Próximo</strong>
                        <span class="text-muted small d-block">Grupo: <strong>${g.clave_grupo}</strong></span>
                        <span class="text-muted small d-block">Fecha de Fin: <strong>${formatearFechaEspanol(g.fecha_fin)}</strong></span>
                    </div>
                </div>
                <div class="text-end">
                    <span class="badge bg-warning text-dark px-2.5 py-1.5 fw-bold d-block mb-1" style="font-size: 0.8rem;">Cierra en ${g.dias_restantes} día(s)</span>
                </div>
            </div>
        `;
    });

    contenedor.innerHTML = html;
}

function renderSeccionCapturas(pendientes) {
    const contenedor = document.getElementById('contenedorCapturasIncompletas');
    contenedor.innerHTML = '';

    if (pendientes.length === 0) {
        contenedor.innerHTML = `
            <div class="text-center py-5 text-muted">
                <i class="fa-solid fa-circle-check fs-2 text-success mb-2"></i>
                <p class="mb-0 small fw-bold">¡Excelente! Tienes todas tus calificaciones capturadas.</p>
            </div>
        `;
        return;
    }

    let html = '';

    pendientes.forEach(p => {
        const bloqueado = p.bloqueado;
        const cardClass = bloqueado ? 'timeline-card card-pendiente bloqueado' : 'timeline-card card-pendiente';
        
        let controlBtn = '';
        if (bloqueado) {
            controlBtn = `
                <div class="text-end">
                    <button class="btn btn-bloqueado-info" title="${p.motivo_bloqueo}">
                        <i class="fa-solid fa-lock me-1"></i> Bloqueado
                    </button>
                    <small class="text-danger d-block mt-1 fw-bold" style="font-size: 0.68rem; max-width: 140px; text-align: right;">${p.motivo_bloqueo}</small>
                </div>
            `;
        } else {
            controlBtn = `
                <a href="/grupos/captura_calificaciones?id_grupo=${p.id_grupo}&id_materia=${p.id_materia}" class="btn btn-capturar-rapido">
                    <i class="fa-solid fa-pen-to-square"></i> Capturar
                </a>
            `;
        }

        html += `
            <div class="${cardClass}">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon-wrapper stat-pendientes" style="${bloqueado ? 'background: #f1f5f9; color: #64748b;' : ''}">
                        <i class="fa-solid fa-clipboard-question"></i>
                    </div>
                    <div>
                        <strong class="text-dark d-block">${p.nombre_materia}</strong>
                        <span class="text-muted small d-block">Grupo: <strong>${p.clave_grupo}</strong></span>
                        <span class="text-danger small d-block fw-bold"><i class="fa-solid fa-triangle-exclamation me-1"></i> Faltan ${p.alumnos_faltantes} de ${p.alumnos_totales} alumnos</span>
                    </div>
                </div>
                ${controlBtn}
            </div>
        `;
    });

    contenedor.innerHTML = html;
}
</script>

@endsection
