@extends('layouts.app')

@section('content')

<div class="page-container" style="padding: 0;">

    {{-- Encabezado Estandarizado --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ url()->previous() }}" class="btn-regresar" style="background: #FFFFFF; border: 1px solid #E2E8F0; color: #475569; height: 36px; padding: 0 12px; border-radius: 6px; font-size: 0.875rem; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03); text-decoration: none; transition: all 0.15s ease;">
                <i class="ti ti-arrow-left"></i>
                <span>Regresar</span>
            </a>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h1 style="font-size: 1.25rem; font-weight: 600; color: #0F172A; margin: 0; letter-spacing: -0.01em;">
                        Captura de Calificaciones
                    </h1>
                    <span style="background: #F0F7F8; color: #1E5B66; border: 1px solid #CBE4E8; padding: 2px 8px; border-radius: 999px; font-size: 0.72rem; font-weight: 600;">
                        Planes BTI
                    </span>
                </div>
                <p style="font-size: 0.8125rem; color: #64748B; margin: 2px 0 0 0;">
                    Sábana de calificaciones por semestre y periodo de evaluación (Plan Escolarizado)
                </p>
            </div>
        </div>

        <div>
            <span style="background: #FFFFFF; border: 1px solid #E2E8F0; color: #64748B; height: 36px; padding: 0 12px; border-radius: 6px; font-size: 0.8125rem; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);">
                <i class="ti ti-calendar text-secondary"></i>
                <span>6 Semestres Activos</span>
            </span>
        </div>
    </div>


    {{-- Contenedor Principal Unificado --}}
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04); overflow: hidden;">

        {{-- Cabecera del Bloque --}}
        <div style="padding: 16px 20px; border-bottom: 1px solid #E2E8F0; background: #FFFFFF; display: flex; align-items: center; justify-content: space-between; flex-wrap: gap-2;">
            <div class="d-flex align-items-center gap-2">
                <div style="width: 32px; height: 32px; border-radius: 6px; background: #F0F7F8; color: #1E5B66; border: 1px solid #CBE4E8; display: inline-flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                    <i class="ti ti-layout-grid"></i>
                </div>
                <div>
                    <h2 style="font-size: 0.95rem; font-weight: 600; color: #0F172A; margin: 0;">
                        Selecciona el semestre
                    </h2>
                    <span style="font-size: 0.75rem; color: #64748B;">Haz clic en capturar para acceder a la sábana de materias y captura rápida</span>
                </div>
            </div>

            <span style="font-size: 0.75rem; color: #64748B; font-weight: 500;">
                <i class="ti ti-building me-1"></i> CCT: <strong>21PCT0073R</strong>
            </span>
        </div>


        {{-- Grid de Semestres (1 al 6) --}}
        <div class="row g-3 p-4">

            {{-- 1ER SEMESTRE --}}
            <div class="col-12 col-md-6 col-lg-4">
                <div class="semestre-card" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; padding: 18px 20px; display: flex; flex-direction: column; justify-content: space-between; gap: 16px; min-height: 190px; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03); transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: #F0F7F8; border: 1px solid #CBE4E8; color: #1E5B66; display: inline-flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                                    <i class="ti ti-school"></i>
                                </div>
                                <h3 style="font-size: 0.95rem; font-weight: 700; color: #0F172A; margin: 0;">
                                    1ER SEMESTRE
                                </h3>
                            </div>
                            <span style="background: #F8FAFC; color: #64748B; border: 1px solid #E2E8F0; padding: 2px 8px; border-radius: 999px; font-size: 0.7rem; font-weight: 600;">
                                Semestre 1
                            </span>
                        </div>

                        <div style="background: #F8FAFC; border: 1px solid #F1F5F9; border-radius: 6px; padding: 8px 12px;">
                            <div style="font-size: 0.7rem; font-weight: 600; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.04em; margin-bottom: 2px;">
                                <i class="ti ti-user me-1"></i> Referencia
                            </div>
                            <div style="font-size: 0.8125rem; font-weight: 500; color: #94A3B8; font-style: italic;">
                                Sin referencia asignada
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn w-100 btn-capturar-sem" onclick="abrirSemestre(1)" style="background: #1E5B66; color: #FFFFFF; height: 36px; border-radius: 6px; font-size: 0.8125rem; font-weight: 500; border: none; display: inline-flex; align-items: center; justify-content: center; gap: 6px; transition: background 0.15s ease;">
                        <i class="ti ti-edit"></i>
                        <span>Capturar calificaciones</span>
                    </button>
                </div>
            </div>


            {{-- 2DO SEMESTRE --}}
            <div class="col-12 col-md-6 col-lg-4">
                <div class="semestre-card" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; padding: 18px 20px; display: flex; flex-direction: column; justify-content: space-between; gap: 16px; min-height: 190px; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03); transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: #F0F7F8; border: 1px solid #CBE4E8; color: #1E5B66; display: inline-flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                                    <i class="ti ti-school"></i>
                                </div>
                                <h3 style="font-size: 0.95rem; font-weight: 700; color: #0F172A; margin: 0;">
                                    2DO SEMESTRE
                                </h3>
                            </div>
                            <span style="background: #F8FAFC; color: #64748B; border: 1px solid #E2E8F0; padding: 2px 8px; border-radius: 999px; font-size: 0.7rem; font-weight: 600;">
                                Semestre 2
                            </span>
                        </div>

                        <div style="background: #F8FAFC; border: 1px solid #F1F5F9; border-radius: 6px; padding: 8px 12px;">
                            <div style="font-size: 0.7rem; font-weight: 600; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.04em; margin-bottom: 2px;">
                                <i class="ti ti-user me-1"></i> Referencia
                            </div>
                            <div style="font-size: 0.8125rem; font-weight: 500; color: #1E293B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="Mora Herrera Victoria Kiran">
                                Mora Herrera Victoria Kiran
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn w-100 btn-capturar-sem" onclick="abrirSemestre(2)" style="background: #1E5B66; color: #FFFFFF; height: 36px; border-radius: 6px; font-size: 0.8125rem; font-weight: 500; border: none; display: inline-flex; align-items: center; justify-content: center; gap: 6px; transition: background 0.15s ease;">
                        <i class="ti ti-edit"></i>
                        <span>Capturar calificaciones</span>
                    </button>
                </div>
            </div>


            {{-- 3ER SEMESTRE --}}
            <div class="col-12 col-md-6 col-lg-4">
                <div class="semestre-card" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; padding: 18px 20px; display: flex; flex-direction: column; justify-content: space-between; gap: 16px; min-height: 190px; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03); transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: #F0F7F8; border: 1px solid #CBE4E8; color: #1E5B66; display: inline-flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                                    <i class="ti ti-school"></i>
                                </div>
                                <h3 style="font-size: 0.95rem; font-weight: 700; color: #0F172A; margin: 0;">
                                    3ER SEMESTRE
                                </h3>
                            </div>
                            <span style="background: #F8FAFC; color: #64748B; border: 1px solid #E2E8F0; padding: 2px 8px; border-radius: 999px; font-size: 0.7rem; font-weight: 600;">
                                Semestre 3
                            </span>
                        </div>

                        <div style="background: #F8FAFC; border: 1px solid #F1F5F9; border-radius: 6px; padding: 8px 12px;">
                            <div style="font-size: 0.7rem; font-weight: 600; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.04em; margin-bottom: 2px;">
                                <i class="ti ti-user me-1"></i> Referencia
                            </div>
                            <div style="font-size: 0.8125rem; font-weight: 500; color: #94A3B8; font-style: italic;">
                                Sin referencia asignada
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn w-100 btn-capturar-sem" onclick="abrirSemestre(3)" style="background: #1E5B66; color: #FFFFFF; height: 36px; border-radius: 6px; font-size: 0.8125rem; font-weight: 500; border: none; display: inline-flex; align-items: center; justify-content: center; gap: 6px; transition: background 0.15s ease;">
                        <i class="ti ti-edit"></i>
                        <span>Capturar calificaciones</span>
                    </button>
                </div>
            </div>


            {{-- 4TO SEMESTRE --}}
            <div class="col-12 col-md-6 col-lg-4">
                <div class="semestre-card" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; padding: 18px 20px; display: flex; flex-direction: column; justify-content: space-between; gap: 16px; min-height: 190px; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03); transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: #F0F7F8; border: 1px solid #CBE4E8; color: #1E5B66; display: inline-flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                                    <i class="ti ti-school"></i>
                                </div>
                                <h3 style="font-size: 0.95rem; font-weight: 700; color: #0F172A; margin: 0;">
                                    4TO SEMESTRE
                                </h3>
                            </div>
                            <span style="background: #F8FAFC; color: #64748B; border: 1px solid #E2E8F0; padding: 2px 8px; border-radius: 999px; font-size: 0.7rem; font-weight: 600;">
                                Semestre 4
                            </span>
                        </div>

                        <div style="background: #F8FAFC; border: 1px solid #F1F5F9; border-radius: 6px; padding: 8px 12px;">
                            <div style="font-size: 0.7rem; font-weight: 600; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.04em; margin-bottom: 2px;">
                                <i class="ti ti-user me-1"></i> Referencia
                            </div>
                            <div style="font-size: 0.8125rem; font-weight: 500; color: #1E293B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="Leal Hernandez Yael">
                                Leal Hernandez Yael
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn w-100 btn-capturar-sem" onclick="abrirSemestre(4)" style="background: #1E5B66; color: #FFFFFF; height: 36px; border-radius: 6px; font-size: 0.8125rem; font-weight: 500; border: none; display: inline-flex; align-items: center; justify-content: center; gap: 6px; transition: background 0.15s ease;">
                        <i class="ti ti-edit"></i>
                        <span>Capturar calificaciones</span>
                    </button>
                </div>
            </div>


            {{-- 5TO SEMESTRE --}}
            <div class="col-12 col-md-6 col-lg-4">
                <div class="semestre-card" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; padding: 18px 20px; display: flex; flex-direction: column; justify-content: space-between; gap: 16px; min-height: 190px; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03); transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: #F0F7F8; border: 1px solid #CBE4E8; color: #1E5B66; display: inline-flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                                    <i class="ti ti-school"></i>
                                </div>
                                <h3 style="font-size: 0.95rem; font-weight: 700; color: #0F172A; margin: 0;">
                                    5TO SEMESTRE
                                </h3>
                            </div>
                            <span style="background: #F8FAFC; color: #64748B; border: 1px solid #E2E8F0; padding: 2px 8px; border-radius: 999px; font-size: 0.7rem; font-weight: 600;">
                                Semestre 5
                            </span>
                        </div>

                        <div style="background: #F8FAFC; border: 1px solid #F1F5F9; border-radius: 6px; padding: 8px 12px;">
                            <div style="font-size: 0.7rem; font-weight: 600; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.04em; margin-bottom: 2px;">
                                <i class="ti ti-user me-1"></i> Referencia
                            </div>
                            <div style="font-size: 0.8125rem; font-weight: 500; color: #94A3B8; font-style: italic;">
                                Sin referencia asignada
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn w-100 btn-capturar-sem" onclick="abrirSemestre(5)" style="background: #1E5B66; color: #FFFFFF; height: 36px; border-radius: 6px; font-size: 0.8125rem; font-weight: 500; border: none; display: inline-flex; align-items: center; justify-content: center; gap: 6px; transition: background 0.15s ease;">
                        <i class="ti ti-edit"></i>
                        <span>Capturar calificaciones</span>
                    </button>
                </div>
            </div>


            {{-- 6TO SEMESTRE --}}
            <div class="col-12 col-md-6 col-lg-4">
                <div class="semestre-card" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; padding: 18px 20px; display: flex; flex-direction: column; justify-content: space-between; gap: 16px; min-height: 190px; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03); transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: #F0F7F8; border: 1px solid #CBE4E8; color: #1E5B66; display: inline-flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                                    <i class="ti ti-school"></i>
                                </div>
                                <h3 style="font-size: 0.95rem; font-weight: 700; color: #0F172A; margin: 0;">
                                    6TO SEMESTRE
                                </h3>
                            </div>
                            <span style="background: #F8FAFC; color: #64748B; border: 1px solid #E2E8F0; padding: 2px 8px; border-radius: 999px; font-size: 0.7rem; font-weight: 600;">
                                Semestre 6
                            </span>
                        </div>

                        <div style="background: #F8FAFC; border: 1px solid #F1F5F9; border-radius: 6px; padding: 8px 12px;">
                            <div style="font-size: 0.7rem; font-weight: 600; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.04em; margin-bottom: 2px;">
                                <i class="ti ti-user me-1"></i> Referencia
                            </div>
                            <div style="font-size: 0.8125rem; font-weight: 500; color: #1E293B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="Gomez Trujillo Zaratustra Netzahualcóyotl">
                                Gomez Trujillo Zaratustra Netzahualcóyotl
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn w-100 btn-capturar-sem" onclick="abrirSemestre(6)" style="background: #1E5B66; color: #FFFFFF; height: 36px; border-radius: 6px; font-size: 0.8125rem; font-weight: 500; border: none; display: inline-flex; align-items: center; justify-content: center; gap: 6px; transition: background 0.15s ease;">
                        <i class="ti ti-edit"></i>
                        <span>Capturar calificaciones</span>
                    </button>
                </div>
            </div>

        </div>


        {{-- Footer --}}
        <div style="padding: 14px 20px; border-top: 1px solid #E2E8F0; background: #F8FAFC; display: flex; align-items: center; justify-content: space-between;">
            <small id="infoPaginacion" class="text-muted" style="font-size: 0.75rem;"></small>
            <div id="paginacion"></div>
        </div>

    </div>
</div>

<style>
.semestre-card:hover {
    border-color: #CBD5E1 !important;
    box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.06), 0 2px 4px -2px rgba(15, 23, 42, 0.04) !important;
}
.btn-capturar-sem:hover {
    background: #184A54 !important;
}
</style>

@endsection

@include('boletasBTI.modalAlta')
