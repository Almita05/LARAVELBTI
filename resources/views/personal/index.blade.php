@extends('layouts.app')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@php
    /*
     * NOTA: los elementos que el JS muestra/oculta usan el atributo `hidden`
     * (las utilidades de display de Tailwind son !important).
     */
    $card      = ' tw:border tw:border-slate-200 tw:bg-white tw:shadow-card';
    $label     = 'tw:mb-1.5 tw:block tw:text-[0.8125rem] tw:font-medium tw:text-slate-700';
    $hint      = 'tw:m-0 tw:mt-1 tw:text-xs tw:text-slate-500';
    $input     = 'tw:h-10 tw:w-full tw:border tw:border-slate-200 tw:bg-white tw:px-3 tw:text-sm tw:text-slate-900 tw:shadow-xs tw:outline-none tw:transition tw:placeholder:text-slate-400 tw:hover:border-slate-300 tw:focus:border-brand-500 tw:focus:ring-4 tw:focus:ring-brand-500/10 tw:aria-[invalid=true]:border-danger-500 tw:aria-[invalid=true]:ring-4 tw:aria-[invalid=true]:ring-danger-500/10';
    $btnBase   = 'tw:inline-flex tw:cursor-pointer tw:items-center tw:justify-center tw:gap-2 tw:text-sm tw:font-semibold tw:transition-all tw:duration-150 tw:focus-visible:outline-2 tw:focus-visible:outline-offset-2 tw:focus-visible:outline-brand-500 tw:active:scale-[0.98] tw:disabled:cursor-not-allowed tw:disabled:opacity-60';
    $btnPrimary   = $btnBase . ' tw:h-10 tw:border-0 tw:bg-brand-700 tw:px-4 tw:text-white tw:shadow-brand tw:hover:bg-brand-800';
    $btnSecondary = $btnBase . ' tw:h-10 tw:border tw:border-slate-200 tw:bg-white tw:px-4 tw:text-slate-700 tw:shadow-xs tw:hover:bg-slate-50';
    $btnChip   = 'tw:inline-flex tw:h-7 tw:cursor-pointer tw:items-center tw:border tw:border-slate-200 tw:bg-white tw:px-2.5 tw:text-xs tw:font-medium tw:text-slate-600 tw:transition tw:hover:border-brand-200 tw:hover:bg-brand-50 tw:hover:text-brand-700';
    $th   = 'tw:px-4 tw:py-2.5 tw:text-left tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.06em] tw:text-slate-500';
    $tab  = 'tw:inline-flex tw:cursor-pointer tw:items-center tw:gap-1.5 tw:border-0 tw:bg-transparent tw:px-3 tw:py-1.5 tw:text-[0.8125rem] tw:font-medium tw:text-slate-600 tw:transition tw:hover:text-slate-900 tw:aria-pressed:bg-white tw:aria-pressed:font-semibold tw:aria-pressed:text-slate-900 tw:aria-pressed:shadow-xs';
    $sectionTitle = 'tw:m-0 tw:mb-4 tw:text-[0.6875rem] tw:font-semibold tw:uppercase tw:tracking-[0.08em] tw:text-slate-400';

    $roles = ['Control Escolar', 'Administrativo', 'Subdirector', 'Director', 'Prefecto', 'Servicio Social'];

    // Etiquetas legibles de cada módulo (para el resumen de permisos en la tabla)
    $modulosCatalogo = collect(config('permisos.grupos'))->flatMap(fn ($g) => $g['modulos'])->all();
@endphp

<div class="tw:mx-auto tw:flex tw:max-w-[1600px] tw:flex-col tw:gap-6">

    {{-- ===================== ENCABEZADO ===================== --}}
    <header class="tw:flex tw:flex-col tw:gap-4 tw:lg:flex-row tw:lg:items-end tw:lg:justify-between">
        <div>
            <h1 class="tw:m-0 tw:text-[1.5rem] tw:font-bold tw:tracking-tight tw:text-slate-900">Gestión de personal</h1>
            <p class="tw:m-0 tw:mt-1 tw:text-sm tw:text-slate-500">Cuentas de acceso del personal administrativo: usuario, rol, checador y permisos por módulo.</p>
        </div>
        <button type="button" class="{{ $btnPrimary }}" onclick="abrirModalPersonal()">
            <i data-lucide="user-plus" class="tw:size-4"></i> Nueva cuenta
        </button>
    </header>

    {{-- ===================== LISTA ===================== --}}
    <section class="{{ $card }} tw:overflow-hidden">
        <div class="tw:flex tw:flex-col tw:gap-3 tw:border-b tw:border-slate-100 tw:px-5 tw:py-4 tw:lg:flex-row tw:lg:items-center tw:lg:justify-between">
            <div role="group" aria-label="Filtrar por estatus" class="tw:inline-flex tw:w-fit tw:gap-1 tw:bg-slate-100 tw:p-1">
                <button type="button" class="{{ $tab }}" data-filtro="ACTIVO" aria-pressed="true">Activas <span class="js-conteo tw:tabular-nums tw:text-slate-400" data-conteo="ACTIVO">0</span></button>
                <button type="button" class="{{ $tab }}" data-filtro="INACTIVO" aria-pressed="false">Inactivas <span class="js-conteo tw:tabular-nums tw:text-slate-400" data-conteo="INACTIVO">0</span></button>
                <button type="button" class="{{ $tab }}" data-filtro="" aria-pressed="false">Todas <span class="js-conteo tw:tabular-nums tw:text-slate-400" data-conteo="">0</span></button>
            </div>
            <div class="tw:relative tw:w-full tw:sm:w-80">
                <i data-lucide="search" class="tw:pointer-events-none tw:absolute tw:left-3 tw:top-1/2 tw:size-4 tw:-translate-y-1/2 tw:text-slate-400"></i>
                <input type="search" id="buscadorPersonal" aria-label="Buscar personal"
                    class="tw:h-9 tw:w-full tw:border tw:border-slate-200 tw:bg-white tw:pr-3 tw:pl-9 tw:text-[0.8125rem] tw:text-slate-900 tw:shadow-xs tw:outline-none tw:transition tw:placeholder:text-slate-400 tw:focus:border-brand-500 tw:focus:ring-4 tw:focus:ring-brand-500/10"
                    placeholder="Nombre, usuario, rol o checador…">
            </div>
        </div>

        <div class="tw:overflow-x-auto">
            <table class="tw:w-full tw:min-w-[900px] tw:border-collapse">
                <thead class="tw:bg-slate-50">
                    <tr class="tw:border-b tw:border-slate-200">
                        <th class="{{ $th }} tw:pl-5">Persona</th>
                        <th class="{{ $th }}">Usuario</th>
                        <th class="{{ $th }}">Checador</th>
                        <th class="{{ $th }}">Permisos</th>
                        <th class="{{ $th }}">Estatus</th>
                        <th class="{{ $th }} tw:pr-5 tw:text-right"><span class="tw:sr-only">Acciones</span></th>
                    </tr>
                </thead>
                <tbody id="tablaPersonal" class="tw:divide-y tw:divide-slate-100"></tbody>
            </table>
        </div>
    </section>
</div>

{{-- ===================== MODAL: ALTA / EDICIÓN ===================== --}}
<div class="modal fade" id="modalPersonal" tabindex="-1" aria-labelledby="modalPersonalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPersonalLabel"><span id="modalPersonalIcono" class="tw:inline-flex"><i data-lucide="user-plus" class="tw:size-5"></i></span> <span id="modalPersonalTitulo">Nueva cuenta de personal</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formPersonal" novalidate class="tw:grid tw:grid-cols-1 tw:gap-8 tw:lg:grid-cols-[minmax(0,5fr)_minmax(0,7fr)]">
                    <input type="hidden" id="personalId">

                    {{-- Datos de la cuenta --}}
                    <div class="tw:flex tw:flex-col tw:gap-4">
                        <h3 class="{{ $sectionTitle }} tw:mb-0">Datos de la cuenta</h3>

                        <div>
                            <label for="campoNombre" class="{{ $label }}">Nombre completo</label>
                            <input type="text" id="campoNombre" name="nombre" class="{{ $input }}" required maxlength="255" autocomplete="off" placeholder="Ej. Lic. Laura Elena Martínez">
                        </div>

                        <div class="tw:grid tw:grid-cols-1 tw:gap-4 tw:sm:grid-cols-2">
                            <div>
                                <label for="campoUsuario" class="{{ $label }}">Usuario</label>
                                <input type="text" id="campoUsuario" name="usuario" class="{{ $input }} tw:font-mono" required maxlength="100" autocomplete="off" autocapitalize="none" spellcheck="false" placeholder="laura.martinez"
                                    pattern="[A-Za-z0-9._@ñÑ\-]+">
                                <p class="{{ $hint }}">Sin espacios. Debe ser único en todo el sistema.</p>
                            </div>
                            <div>
                                <label for="campoPassword" class="{{ $label }}" id="lblPassword">Contraseña</label>
                                <div class="tw:relative">
                                    <input type="password" id="campoPassword" name="password" class="{{ $input }} tw:pr-10" minlength="8" autocomplete="new-password" aria-describedby="hintPassword">
                                    <button type="button" class="tw:absolute tw:right-1.5 tw:top-1/2 tw:inline-flex tw:size-7 tw:-translate-y-1/2 tw:cursor-pointer tw:items-center tw:justify-center tw:border-0 tw:bg-transparent tw:text-slate-400 tw:hover:bg-slate-100 tw:hover:text-slate-700"
                                        onclick="alternarPassword()" aria-label="Mostrar contraseña" id="btnVerPassword">
                                        <i data-lucide="eye" class="tw:size-4"></i>
                                    </button>
                                </div>
                                <p class="{{ $hint }}" id="hintPassword">Mínimo 8 caracteres, con letras y números.</p>
                            </div>
                        </div>

                        <div class="tw:grid tw:grid-cols-1 tw:gap-4 tw:sm:grid-cols-2">
                            <div>
                                <label for="campoRol" class="{{ $label }}">Rol</label>
                                <select id="campoRol" name="rol" class="{{ $input }}" required>
                                    <option value="" disabled selected>Selecciona…</option>
                                    @foreach ($roles as $rol)
                                        <option value="{{ $rol }}">{{ $rol }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="campoBiometrico" class="{{ $label }}">ID en reloj checador <span class="tw:font-normal tw:text-slate-400">(opcional)</span></label>
                                <input type="text" id="campoBiometrico" name="idBiometrico" class="{{ $input }} tw:font-mono" maxlength="50" inputmode="numeric" placeholder="Ej. 45">
                            </div>
                        </div>

                        <div id="bloqueEstatus" hidden>
                            <span class="{{ $label }}" id="lblEstatus">Estatus</span>
                            <div role="radiogroup" aria-labelledby="lblEstatus" class="tw:grid tw:grid-cols-2 tw:gap-1 tw:bg-slate-100 tw:p-1">
                                @foreach (['ACTIVO' => 'Activa', 'INACTIVO' => 'Inactiva'] as $valor => $texto)
                                    <label class="tw:cursor-pointer">
                                        <input type="radio" name="status" value="{{ $valor }}" class="tw:peer tw:sr-only" @checked($valor === 'ACTIVO')>
                                        <span class="tw:flex tw:h-8 tw:items-center tw:justify-center tw:text-[0.8125rem] tw:font-medium tw:text-slate-600 tw:transition tw:peer-checked:bg-white tw:peer-checked:font-semibold tw:peer-checked:shadow-xs tw:peer-focus-visible:ring-2 tw:peer-focus-visible:ring-brand-500 {{ $valor === 'ACTIVO' ? 'tw:peer-checked:text-success-700' : 'tw:peer-checked:text-danger-600' }}">{{ $texto }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <p class="{{ $hint }}">Una cuenta inactiva no puede iniciar sesión; si tiene una sesión abierta se cierra en menos de un minuto.</p>
                        </div>

                        <div class="tw:flex tw:items-start tw:justify-between tw:gap-4 tw:border tw:border-slate-200 tw:bg-slate-50/60 tw:p-3.5">
                            <span>
                                <span class="tw:block tw:text-[0.8125rem] tw:font-semibold tw:text-slate-800" id="lblServicioSocial">Realiza servicio social</span>
                                <span class="tw:block tw:text-xs tw:text-slate-500">Sus horas se calculan por tiempo efectivo (primer a último marcaje).</span>
                            </span>
                            <x-ui.switch id="campoServicioSocial" name="es_servicio_social" value="1" aria-labelledby="lblServicioSocial" />
                        </div>
                    </div>

                    {{-- Permisos --}}
                    <div class="tw:flex tw:min-w-0 tw:flex-col">
                        <div class="tw:mb-3 tw:flex tw:flex-wrap tw:items-center tw:justify-between tw:gap-2">
                            <h3 class="{{ $sectionTitle }} tw:mb-0">Permisos por módulo</h3>
                            <span id="conteoPermisos" class="tw:rounded-none tw:bg-slate-100 tw:px-2.5 tw:py-0.5 tw:text-[0.6875rem] tw:font-semibold tw:tabular-nums tw:text-slate-600">0 permisos</span>
                        </div>
                        <div class="tw:mb-3 tw:flex tw:flex-wrap tw:items-center tw:gap-1.5">
                            <span class="tw:text-xs tw:text-slate-500">Atajos:</span>
                            <button type="button" class="{{ $btnChip }}" data-preset="ninguno">Sin permisos</button>
                            <button type="button" class="{{ $btnChip }}" data-preset="lectura">Solo consulta</button>
                            <button type="button" class="{{ $btnChip }}" data-preset="todo">Acceso completo</button>
                        </div>
                        <div id="matrizPermisos" class="tw:max-h-[52vh] tw:overflow-auto tw:border tw:border-slate-200">
                            @include('personal.partials.filas-permisos')
                        </div>
                        <p class="{{ $hint }} tw:mt-2">
                            <strong class="tw:font-semibold tw:text-slate-600">Ver</strong> abre la pantalla, <strong class="tw:font-semibold tw:text-slate-600">Crear/Editar</strong> guarda cambios ·
                            <strong class="tw:font-semibold tw:text-slate-600">Eliminar</strong> borra registros, <strong class="tw:font-semibold tw:text-slate-600">Consultar</strong> muestra detalles.
                            "Listado de alumnos" incluye reportes de indisciplina y alumnos online; cualquier permiso de "Imprimir formatos" da acceso al módulo de impresión.
                        </p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="{{ $btnSecondary }}" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formPersonal" class="{{ $btnPrimary }}" id="btnGuardarPersonal">
                    <i data-lucide="check" class="tw:size-4"></i> <span id="btnGuardarTexto">Crear cuenta</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const BRAND = '#26687B';
    const MODULOS = @json($modulosCatalogo);
    const HEADERS = { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF };

    const esc = (v) => String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const normalizar = (s) => String(s ?? '').normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase();
    const iniciales = (n) => String(n || '').replace(/^(lic|ing|mtro|mtra|dr|dra|prof)\.?\s+/i, '').trim().split(/\s+/).filter(Boolean).slice(0, 2).map(p => p[0].toUpperCase()).join('') || '—';
    const refrescarIconos = () => { if (window.lucide?.createIcons) lucide.createIcons(); };

    /** Errores de validación de Laravel (422) o mensaje de la API. */
    const mensajeError = (resp, fallback) => {
        if (resp && resp.errors) return Object.values(resp.errors).flat().join(' ');
        return (resp && resp.message) || fallback;
    };

    async function fetchJson(url, options = {}) {
        const res = await fetch(url, { ...options, headers: { ...HEADERS, ...(options.headers || {}) } });
        let data = null;
        try { data = await res.json(); } catch (e) { /* respuesta no JSON */ }
        if (!res.ok) {
            const err = new Error(mensajeError(data, `HTTP ${res.status}`));
            err.data = data;
            throw err;
        }
        return data;
    }

    // =====================================================================
    // LISTA
    // =====================================================================
    let personal = [];
    let filtroEstatus = 'ACTIVO';
    const tbody = document.getElementById('tablaPersonal');

    const fila = (contenido) => `<tr><td colspan="6">${contenido}</td></tr>`;
    const estado = (icon, texto, tono = 'tw:text-slate-400', extra = '') => fila(`
        <div class="tw:flex tw:flex-col tw:items-center tw:px-6 tw:py-14 tw:text-center">
            <span class="tw:inline-flex tw:size-11 tw:items-center tw:justify-center tw:bg-slate-100 ${tono}"><i data-lucide="${icon}" class="tw:size-5"></i></span>
            <p class="tw:m-0 tw:mt-3 tw:text-[0.8125rem] tw:text-slate-500">${texto}</p>${extra}
        </div>`);

    window.cargarPersonal = async function () {
        tbody.innerHTML = fila(`<div class="tw:flex tw:flex-col tw:items-center tw:gap-3 tw:py-14 tw:text-[0.8125rem] tw:text-slate-500">
            <span class="tw:inline-block tw:size-6 tw:animate-spin tw:rounded-full tw:border-2 tw:border-slate-200 tw:border-t-brand-700"></span>Cargando cuentas…</div>`);
        try {
            const resp = await fetchJson('/personal/lista');
            personal = resp.data || [];
            render();
        } catch (e) {
            tbody.innerHTML = estado('circle-alert', 'No se pudieron cargar las cuentas de personal.', 'tw:text-danger-600',
                `<button type="button" class="tw:mt-3 tw:cursor-pointer tw:border-0 tw:bg-transparent tw:text-[0.8125rem] tw:font-semibold tw:text-brand-700 tw:hover:underline" onclick="cargarPersonal()">Reintentar</button>`);
            refrescarIconos();
        }
    };

    /** Resumen legible: "Listado de alumnos, Materias +5" a partir de "mod:accion,...". */
    function resumenPermisos(texto) {
        const pares = String(texto || '').split(',').map(s => s.trim()).filter(Boolean);
        if (!pares.length) return `<span class="tw:text-xs tw:text-slate-400">Sin permisos</span>`;
        const modulos = [...new Set(pares.map(p => p.split(':')[0]))];
        const nombres = modulos.map(m => MODULOS[m] || m);
        const visibles = nombres.slice(0, 2).map(n => `<span class="tw:inline-flex tw:max-w-[150px] tw:truncate tw:bg-slate-100 tw:px-2 tw:py-0.5 tw:text-[0.6875rem] tw:font-medium tw:text-slate-600">${esc(n)}</span>`).join('');
        const resto = nombres.length > 2 ? `<span class="tw:text-[0.6875rem] tw:font-semibold tw:text-slate-500">+${nombres.length - 2}</span>` : '';
        return `<div class="tw:flex tw:flex-wrap tw:items-center tw:gap-1" title="${esc(nombres.join(', '))}">${visibles}${resto}</div>`;
    }

    const accion = (icon, label, tono, onclick) => `
        <button type="button" onclick="${onclick}" aria-label="${label}" title="${label}"
            class="tw:inline-flex tw:size-8 tw:cursor-pointer tw:items-center tw:justify-center tw:border-0 tw:bg-transparent tw:transition ${tono}">
            <i data-lucide="${icon}" class="tw:size-4"></i>
        </button>`;

    function render() {
        // Conteos por estatus
        const conteo = { ACTIVO: 0, INACTIVO: 0, '': personal.length };
        personal.forEach(p => { if (p.status in conteo) conteo[p.status]++; });
        document.querySelectorAll('[data-conteo]').forEach(el => el.textContent = conteo[el.dataset.conteo] ?? 0);

        const q = normalizar(document.getElementById('buscadorPersonal').value.trim());
        const lista = personal.filter(p =>
            (!filtroEstatus || p.status === filtroEstatus) &&
            (!q || normalizar(`${p.nombre} ${p.usuario} ${p.rol} ${p.idBiometrico ?? ''}`).includes(q)));

        if (!personal.length) {
            tbody.innerHTML = estado('users-round', 'Aún no hay cuentas de personal.', 'tw:text-slate-400',
                `<button type="button" class="tw:mt-3 tw:cursor-pointer tw:border-0 tw:bg-transparent tw:text-[0.8125rem] tw:font-semibold tw:text-brand-700 tw:hover:underline" onclick="abrirModalPersonal()">Crear la primera cuenta</button>`);
        } else if (!lista.length) {
            tbody.innerHTML = estado('search-x', 'No hay cuentas que coincidan con el filtro.');
        } else {
            tbody.innerHTML = lista.map(p => {
                const id = Number(p.idPersonal);
                const activo = p.status === 'ACTIVO';
                const ss = p.es_servicio_social == 1 || p.rol === 'Servicio Social';
                return `
                <tr class="tw:transition-colors tw:hover:bg-slate-50 ${activo ? '' : 'tw:bg-slate-50/50'}">
                    <td class="tw:px-4 tw:py-3 tw:pl-5">
                        <div class="tw:flex tw:items-center tw:gap-3">
                            <span class="tw:inline-flex tw:size-9 tw:shrink-0 tw:items-center tw:justify-center tw:text-xs tw:font-semibold ${activo ? 'tw:bg-brand-50 tw:text-brand-700 tw:ring-1 tw:ring-brand-200' : 'tw:bg-slate-100 tw:text-slate-400'}">${esc(iniciales(p.nombre))}</span>
                            <div class="tw:min-w-0">
                                <p class="tw:m-0 tw:truncate tw:text-sm tw:font-semibold ${activo ? 'tw:text-slate-900' : 'tw:text-slate-500'}">${esc(p.nombre)}</p>
                                <p class="tw:m-0 tw:flex tw:items-center tw:gap-1.5 tw:text-xs tw:text-slate-500">${esc(p.rol)}${ss ? '<span class="tw:rounded-none tw:bg-info-100 tw:px-1.5 tw:text-[0.625rem] tw:font-semibold tw:text-info-700">Servicio social</span>' : ''}</p>
                            </div>
                        </div>
                    </td>
                    <td class="tw:px-4 tw:py-3 tw:font-mono tw:text-[0.8125rem] tw:text-slate-700">${esc(p.usuario)}</td>
                    <td class="tw:px-4 tw:py-3 tw:text-[0.8125rem] tw:text-slate-600">${p.idBiometrico ? `<span class="tw:inline-flex tw:items-center tw:gap-1 tw:font-mono"><i data-lucide="fingerprint" class="tw:size-3.5 tw:text-slate-400"></i>${esc(p.idBiometrico)}</span>` : '<span class="tw:text-slate-400">—</span>'}</td>
                    <td class="tw:px-4 tw:py-3">${resumenPermisos(p.permisos_modulos)}</td>
                    <td class="tw:px-4 tw:py-3">
                        <span class="tw:inline-flex tw:items-center tw:gap-1.5 tw:rounded-none tw:px-2.5 tw:py-0.5 tw:text-[0.6875rem] tw:font-semibold ${activo ? 'tw:bg-success-100 tw:text-success-700' : 'tw:bg-slate-100 tw:text-slate-500'}">
                            <span class="tw:size-1.5 tw:rounded-full ${activo ? 'tw:bg-success-500' : 'tw:bg-slate-400'}"></span>${activo ? 'Activa' : 'Inactiva'}
                        </span>
                    </td>
                    <td class="tw:px-4 tw:py-3 tw:pr-5 tw:text-right tw:whitespace-nowrap">
                        ${accion('pencil', 'Editar cuenta y permisos', 'tw:text-slate-500 tw:hover:bg-slate-100 tw:hover:text-slate-900', `abrirModalPersonal(${id})`)}
                        ${activo
                            ? accion('user-x', 'Desactivar cuenta', 'tw:text-slate-500 tw:hover:bg-danger-50 tw:hover:text-danger-600', `desactivarPersonal(${id})`)
                            : accion('user-check', 'Reactivar cuenta', 'tw:text-success-700 tw:hover:bg-success-50', `reactivarPersonal(${id})`)}
                    </td>
                </tr>`;
            }).join('');
        }
        refrescarIconos();
    }

    document.getElementById('buscadorPersonal').addEventListener('input', render);
    document.querySelectorAll('[data-filtro]').forEach(btn => btn.addEventListener('click', () => {
        filtroEstatus = btn.dataset.filtro;
        document.querySelectorAll('[data-filtro]').forEach(b => b.setAttribute('aria-pressed', String(b === btn)));
        render();
    }));

    // =====================================================================
    // MODAL ALTA / EDICIÓN
    // =====================================================================
    const modalEl = document.getElementById('modalPersonal');
    const form = document.getElementById('formPersonal');
    const permisos = () => [...form.querySelectorAll('.js-permiso')];
    const campo = (id) => document.getElementById(id);
    let cuentaEditada = null; // datos originales (conserva campos que el formulario no muestra, p. ej. horas objetivo)

    /** Mantiene coherentes los "seleccionar todo" (columna y grupo) y el contador. */
    function sincronizarMatriz() {
        const marcados = permisos().filter(c => c.checked).length;
        campo('conteoPermisos').textContent = `${marcados} ${marcados === 1 ? 'permiso' : 'permisos'}`;

        const estadoDe = (casillas, toggle) => {
            const n = casillas.filter(c => c.checked).length;
            toggle.checked = n > 0 && n === casillas.length;
            toggle.indeterminate = n > 0 && n < casillas.length;
        };
        form.querySelectorAll('[data-col-toggle]').forEach(t => estadoDe(permisos().filter(c => c.dataset.accion === t.dataset.colToggle), t));
        form.querySelectorAll('[data-grupo-toggle]').forEach(t => estadoDe([...form.querySelectorAll(`tbody[data-grupo="${t.dataset.grupoToggle}"] .js-permiso`)], t));
    }

    form.addEventListener('change', (e) => {
        const t = e.target;
        if (t.dataset.colToggle) permisos().filter(c => c.dataset.accion === t.dataset.colToggle).forEach(c => c.checked = t.checked);
        if (t.dataset.grupoToggle) form.querySelectorAll(`tbody[data-grupo="${t.dataset.grupoToggle}"] .js-permiso`).forEach(c => c.checked = t.checked);
        if (t.id === 'campoRol') campo('campoServicioSocial').checked = t.value === 'Servicio Social';
        if (t.matches('.js-permiso, [data-col-toggle], [data-grupo-toggle]')) sincronizarMatriz();
    });

    document.querySelectorAll('[data-preset]').forEach(btn => btn.addEventListener('click', () => {
        const p = btn.dataset.preset;
        permisos().forEach(c => c.checked = p === 'todo' || (p === 'lectura' && ['ver', 'consultar'].includes(c.dataset.accion)));
        sincronizarMatriz();
    }));

    window.alternarPassword = function () {
        const inp = campo('campoPassword');
        const ver = inp.type === 'password';
        inp.type = ver ? 'text' : 'password';
        campo('btnVerPassword').setAttribute('aria-label', ver ? 'Ocultar contraseña' : 'Mostrar contraseña');
        campo('btnVerPassword').innerHTML = `<i data-lucide="${ver ? 'eye-off' : 'eye'}" class="tw:size-4"></i>`;
        refrescarIconos();
    };

    function prepararModal(cuenta) {
        const editar = !!cuenta;
        form.reset();
        form.querySelectorAll('[aria-invalid]').forEach(el => el.removeAttribute('aria-invalid'));
        campo('campoPassword').type = 'password';
        campo('personalId').value = editar ? cuenta.idPersonal : '';
        campo('modalPersonalTitulo').textContent = editar ? `Editar cuenta de ${cuenta.nombre}` : 'Nueva cuenta de personal';
        campo('modalPersonalIcono').innerHTML = `<i data-lucide="${editar ? 'user-pen' : 'user-plus'}" class="tw:size-5"></i>`;
        cuentaEditada = cuenta;
        campo('btnGuardarTexto').textContent = editar ? 'Guardar cambios' : 'Crear cuenta';
        campo('lblPassword').innerHTML = editar ? 'Nueva contraseña <span class="tw:font-normal tw:text-slate-400">(opcional)</span>' : 'Contraseña';
        campo('campoPassword').placeholder = editar ? 'Vacía = sin cambios' : '';
        campo('campoPassword').required = !editar;
        campo('bloqueEstatus').hidden = !editar;

        if (editar) {
            campo('campoNombre').value = cuenta.nombre || '';
            campo('campoUsuario').value = cuenta.usuario || '';
            campo('campoRol').value = cuenta.rol || '';
            campo('campoBiometrico').value = cuenta.idBiometrico || '';
            campo('campoServicioSocial').checked = cuenta.es_servicio_social == 1 || cuenta.rol === 'Servicio Social';
            form.querySelector(`input[name="status"][value="${cuenta.status === 'INACTIVO' ? 'INACTIVO' : 'ACTIVO'}"]`).checked = true;
            const actuales = new Set(String(cuenta.permisos_modulos || '').split(',').map(s => s.trim()).filter(Boolean));
            permisos().forEach(c => c.checked = actuales.has(c.value));
        }
        sincronizarMatriz();
        refrescarIconos();
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    }

    window.abrirModalPersonal = async function (id) {
        if (!id) return prepararModal(null);
        try {
            const resp = await fetchJson(`/personal/${id}`);
            prepararModal(resp.data);
        } catch (e) {
            Swal.fire({ icon: 'error', title: 'No se pudo abrir la cuenta', text: e.message, confirmButtonColor: BRAND });
        }
    };

    modalEl.addEventListener('shown.bs.modal', () => campo('campoNombre').focus());

    /** Validación en el navegador (la definitiva la hacen Laravel y la API). */
    function validar(editar) {
        const errores = [];
        const marcar = (id, msj) => { campo(id).setAttribute('aria-invalid', 'true'); errores.push(msj); };
        form.querySelectorAll('[aria-invalid]').forEach(el => el.removeAttribute('aria-invalid'));

        if (!campo('campoNombre').value.trim()) marcar('campoNombre', 'Escribe el nombre completo.');
        const usuario = campo('campoUsuario').value.trim();
        if (!usuario) marcar('campoUsuario', 'Escribe el usuario.');
        else if (!/^[A-Za-z0-9._@ñÑ-]+$/.test(usuario)) marcar('campoUsuario', 'El usuario no puede tener espacios ni caracteres especiales.');
        const pw = campo('campoPassword').value;
        if ((!editar || pw) && (pw.length < 8 || !/[A-Za-zñÑ]/.test(pw) || !/\d/.test(pw))) marcar('campoPassword', 'La contraseña debe tener al menos 8 caracteres, con letras y números.');
        if (!campo('campoRol').value) marcar('campoRol', 'Selecciona un rol.');
        return errores;
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = campo('personalId').value;
        const editar = !!id;
        const errores = validar(editar);
        if (errores.length) {
            Swal.fire({ icon: 'warning', title: 'Revisa los datos', html: errores.map(esc).join('<br>'), confirmButtonColor: BRAND });
            form.querySelector('[aria-invalid="true"]')?.focus();
            return;
        }

        const seleccion = permisos().filter(c => c.checked).map(c => c.value);
        if (!seleccion.length) {
            const { isConfirmed } = await Swal.fire({
                icon: 'question', title: '¿Guardar sin permisos?',
                text: 'La cuenta podrá iniciar sesión, pero no verá ningún módulo hasta que le asignes permisos.',
                showCancelButton: true, confirmButtonText: 'Sí, guardar', cancelButtonText: 'Asignar permisos',
                confirmButtonColor: BRAND, reverseButtons: true,
            });
            if (!isConfirmed) return;
        }

        const payload = {
            nombre: campo('campoNombre').value.trim(),
            usuario: campo('campoUsuario').value.trim(),
            rol: campo('campoRol').value,
            idBiometrico: campo('campoBiometrico').value.trim(),
            es_servicio_social: campo('campoServicioSocial').checked ? 1 : 0,
            permisos_modulos: seleccion,
        };
        if (campo('campoPassword').value) payload.password = campo('campoPassword').value;
        if (editar) {
            payload.status = form.querySelector('input[name="status"]:checked')?.value || 'ACTIVO';
            payload.horas_objetivo = cuentaEditada?.horas_objetivo ?? '';
        }

        const btn = campo('btnGuardarPersonal');
        btn.disabled = true;
        try {
            await fetchJson(editar ? `/personal/${id}` : '/personal', { method: editar ? 'PUT' : 'POST', body: JSON.stringify(payload) });
            bootstrap.Modal.getInstance(modalEl)?.hide();
            Swal.fire({ icon: 'success', title: editar ? 'Cambios guardados' : 'Cuenta creada', timer: 1600, showConfirmButton: false });
            cargarPersonal();
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'No se pudo guardar', text: err.message, confirmButtonColor: BRAND });
        } finally {
            btn.disabled = false;
        }
    });

    // =====================================================================
    // DESACTIVAR / REACTIVAR (baja lógica)
    // =====================================================================
    window.desactivarPersonal = async function (id) {
        const p = personal.find(x => Number(x.idPersonal) === id);
        const { isConfirmed } = await Swal.fire({
            icon: 'warning',
            title: '¿Desactivar esta cuenta?',
            html: `<strong>${esc(p?.nombre || '')}</strong> perderá el acceso de inmediato. Su historial se conserva y puedes reactivarla cuando quieras.`,
            showCancelButton: true, confirmButtonText: 'Sí, desactivar', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#DC2626', cancelButtonColor: '#64748B', reverseButtons: true,
        });
        if (!isConfirmed) return;
        try {
            await fetchJson(`/personal/${id}`, { method: 'DELETE' });
            Swal.fire({ icon: 'success', title: 'Cuenta desactivada', timer: 1500, showConfirmButton: false });
            cargarPersonal();
        } catch (e) {
            Swal.fire({ icon: 'error', title: 'No se pudo desactivar', text: e.message, confirmButtonColor: BRAND });
        }
    };

    window.reactivarPersonal = async function (id) {
        try {
            const c = (await fetchJson(`/personal/${id}`)).data;
            await fetchJson(`/personal/${id}`, {
                method: 'PUT',
                body: JSON.stringify({
                    nombre: c.nombre, usuario: c.usuario, rol: c.rol, status: 'ACTIVO',
                    idBiometrico: c.idBiometrico || '', es_servicio_social: c.es_servicio_social ? 1 : 0,
                    horas_objetivo: c.horas_objetivo ?? '',
                    permisos_modulos: String(c.permisos_modulos || '').split(',').map(s => s.trim()).filter(Boolean),
                }),
            });
            Swal.fire({ icon: 'success', title: 'Cuenta reactivada', text: 'Conserva los permisos que tenía.', timer: 1800, showConfirmButton: false });
            cargarPersonal();
        } catch (e) {
            Swal.fire({ icon: 'error', title: 'No se pudo reactivar', text: e.message, confirmButtonColor: BRAND });
        }
    };

    cargarPersonal();
});
</script>

@endsection
