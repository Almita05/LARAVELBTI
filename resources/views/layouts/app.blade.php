<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>BTI</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">

    <!-- Google Fonts: Public Sans (interfaz institucional) y JetBrains Mono (heredada, en retiro) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    {{-- Tailwind CSS v4 (prefijo tw:) — ver resources/css/app.css --}}
    @vite('resources/css/app.css')

    <meta name="csrf-token" content="{{ csrf_token() }}">

        <style>
    /* Iconografía Global Institucional (Tabler Icons) */
    i[class^="ti-"], i[class*=" ti-"], i.ti {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        color: currentColor;
        vertical-align: middle;
    }
    :root {
        /* Paleta institucional: azul del membrete BTI (ver resources/css/app.css) */
        --brand-primary: #26687B;
        --brand-primary-hover: #1F5565;
        --brand-primary-subtle: #DDEDF0;
        --brand-accent: #6BC7E8;
        
        /* Superficies (Escala Slate) */
        --bg-canvas: #E6ECEE; /* fondo general un tono más oscuro para que tablas y paneles blancos se distingan */
        --bg-surface: #FFFFFF;
        --bg-muted: #F1F5F9;
        
        /* Bordes y Divisores */
        --border-subtle: #E2E8F0;
        --border-focus: #3D8494;
        
        /* Tipografía */
        --text-primary: #0F172A;
        --text-secondary: #475569;
        --text-muted: #94A3B8;

        /* Estados Semánticos (Soft Badges) */
        --success-bg: #DCFCE7; --success-text: #0E6B33; --success-border: #BBF7D0;
        --info-bg: #E0F2FE;    --info-text: #0369A1;    --info-border: #BAE6FD;
        --neutral-bg: #F1F5F9; --neutral-text: #334155; --neutral-border: #E2E8F0;
        --danger-hover-bg: #FEF2F2; --danger-text: #C8102E;

        /* Geometría y Elevación */
        --radius-sm: 0;
        --radius-md: 0;
        --radius-lg: 0;
        --shadow-xs: 0 1px 2px 0 rgba(15, 23, 42, 0.03);
        --shadow-card: 0 1px 3px 0 rgba(15, 23, 42, 0.04), 0 1px 2px -1px rgba(15, 23, 42, 0.02);
    }

    html, body {
        margin: 0;
        padding: 0;
        min-height: 100%;
        background-color: var(--bg-canvas);
        font-family: 'Public Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: var(--text-primary);
        -webkit-font-smoothing: antialiased;
    }

    .dropdown-menu {
        border: 1px solid var(--border-subtle, #E2E8F0);
        border-radius: 0;
        box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.08);
        padding: 6px;
    }
    .dropdown-item {
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 0;
        padding: 8px 12px;
        color: var(--text-primary, #0F172A);
        transition: all 0.15s ease;
    }
    .dropdown-item:hover {
        background: var(--bg-muted, #F8FAFC);
        color: var(--text-primary, #0F172A);
    }
    .dropdown-item.text-danger:hover {
        background: var(--danger-hover-bg, #FEF2F2);
        color: var(--danger-text, #DC2626) !important;
    }

    .page-container {
        padding: 0;
        min-height: auto;
    }

    /* Encabezado Estandarizado de Página */
    .page-header-standard {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }
    .page-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .page-heading {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        letter-spacing: -0.01em;
    }

    /* Botones UI SaaS */
    .btn-regresar, .btn-secondary-outline {
        background: var(--bg-surface) !important;
        border: 1px solid var(--border-subtle) !important;
        color: var(--text-secondary) !important;
        height: 36px;
        padding: 0 12px;
        border-radius: 0!important;
        font-size: 0.875rem;
        font-weight: 500;
        box-shadow: var(--shadow-xs);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.15s ease;
    }
    .btn-regresar:hover, .btn-secondary-outline:hover {
        background: var(--bg-muted) !important;
        color: var(--text-primary) !important;
        border-color: var(--border-subtle) !important;
        transform: none !important;
        box-shadow: var(--shadow-xs) !important;
    }

    .btn-azul, .btn-primary-saas, .btn-primary {
        background: var(--brand-primary) !important;
        border: none !important;
        color: #FFFFFF !important;
        height: 36px;
        padding: 0 16px;
        border-radius: 0!important;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: background 0.15s ease;
        box-shadow: var(--shadow-xs);
    }
    .btn-azul:hover, .btn-primary-saas:hover, .btn-primary:hover {
        background: var(--brand-primary-hover) !important;
        color: #FFFFFF !important;
        transform: none !important;
        box-shadow: var(--shadow-xs) !important;
    }

    /* Ghost Icon Buttons */
    .btn-ghost-icon {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0;
        background: transparent;
        border: none;
        color: #64748B;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
        font-size: 0.95rem;
    }
    .btn-ghost-icon:hover {
        background: var(--bg-muted);
        color: var(--text-primary);
    }
    .btn-ghost-icon.btn-ghost-danger:hover {
        background: var(--danger-hover-bg);
        color: var(--danger-text);
    }

    /* Formularios */
    .form-control, .form-select, .form-control-premium, .form-select-premium {
        height: 38px;
        border: 1px solid var(--border-subtle) !important;
        border-radius: 0!important;
        padding: 0 12px;
        font-size: 0.875rem;
        color: var(--text-primary);
        background: var(--bg-surface);
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .form-control:focus, .form-select:focus, .form-control-premium:focus, .form-select-premium:focus {
        outline: none;
        border-color: var(--brand-primary) !important;
        box-shadow: 0 0 0 3px rgba(38, 104, 123, 0.12) !important;
    }
    .form-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 6px;
        display: block;
    }

    /* Superficie Limpia (Sustituye glass-card) */
    .glass-card, .saas-surface {
        background: var(--bg-surface);
        border: 1px solid var(--border-subtle);
        border-radius: 0;
        box-shadow: var(--shadow-card);
        overflow: hidden;
    }

    /* Soft Badges */
    .badge-soft {
        display: inline-flex;
        align-items: center;
        height: 22px;
        padding: 0 8px;
        border-radius: 0;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid transparent;
        white-space: nowrap;
    }
    .badge-soft-bti {
        background: var(--info-bg);
        color: var(--info-text);
        border-color: var(--info-border);
    }
    .badge-soft-bgne {
        background: var(--brand-primary-subtle);
        color: var(--brand-primary);
        border-color: #BBDBE1;
    }
    .badge-soft-inf {
        background: var(--neutral-bg);
        color: var(--neutral-text);
        border-color: var(--neutral-border);
    }
    .badge-soft-success {
        background: var(--success-bg);
        color: var(--success-text);
        border-color: var(--success-border);
    }
    .badge-soft-danger {
        background: #FEE2E2;
        color: #991B1B;
        border-color: #FECACA;
    }

    /* Modales Estilo shadcn/ui & Radix UI Dialog */
    .modal-content,
    .modal-content.glass-modal,
    .glass-modal {
        background: #FFFFFF !important;
        border: 1px solid #E2E8F0 !important;
        border-radius: 0!important;
        overflow: hidden;
        box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.12), 0 8px 10px -6px rgba(15, 23, 42, 0.08) !important;
    }
    .modal-header,
    .glass-modal .modal-header,
    .modal-header-system {
        background: #FFFFFF !important;
        color: #0F172A !important;
        border-bottom: 1px solid #E2E8F0 !important;
        padding: 16px 24px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
    }
    .modal-header .modal-title,
    .glass-modal .modal-header .modal-title,
    .modal-header-system .modal-title {
        font-size: 1.05rem !important;
        font-weight: 600 !important;
        color: #0F172A !important;
        margin: 0 !important;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        letter-spacing: -0.01em;
    }
    .modal-header .modal-title i,
    .modal-header .modal-title svg {
        color: #26687B !important;
    }
    .modal-header .btn-close,
    .btn-close-white {
        width: 32px;
        height: 32px;
        border-radius: 0;
        background-color: transparent !important;
        filter: none !important;
        opacity: 0.6;
        transition: all 0.15s ease;
    }
    .modal-header .btn-close:hover,
    .btn-close-white:hover {
        background-color: #F1F5F9 !important;
        opacity: 1;
    }
    .modal-body {
        background: #FFFFFF !important;
        padding: 20px 24px !important;
        color: #0F172A;
    }
    .modal-footer,
    .glass-modal .modal-footer {
        background: #F8FAFC !important;
        border-top: 1px solid #E2E8F0 !important;
        padding: 14px 24px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        gap: 10px !important;
    }
    /* Modal Form Controls Standard */
    .modal .form-control,
    .modal .form-select,
    .modal .form-control-premium,
    .modal .form-select-premium {
        height: 38px;
        min-height: 38px;
        border-radius: 0;
        border: 1px solid #CBD5E1;
        font-size: 0.875rem;
        color: #0F172A;
        background-color: #FFFFFF;
        transition: all 0.15s ease;
    }
    .modal textarea.form-control,
    .modal textarea.form-control-premium {
        height: auto;
        min-height: 80px;
    }
    .modal .form-control:focus,
    .modal .form-select:focus,
    .modal .form-control-premium:focus,
    .modal .form-select-premium:focus {
        border-color: #26687B !important;
        box-shadow: 0 0 0 3px rgba(38, 104, 123, 0.12) !important;
        outline: none;
    }
    .modal .form-label {
        font-size: 0.8125rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
    }
    .modal .btn-secondary,
    .modal .btn-regresar,
    .modal .btn-premium-cancel {
        background: #FFFFFF !important;
        border: 1px solid #E2E8F0 !important;
        color: #475569 !important;
        border-radius: 0!important;
        height: 38px !important;
        padding: 0 16px !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        transition: all 0.15s ease;
    }
    .modal .btn-secondary:hover,
    .modal .btn-regresar:hover,
    .modal .btn-premium-cancel:hover {
        background: #F8FAFC !important;
        color: #0F172A !important;
        border-color: #CBD5E1 !important;
    }
    .modal .btn-primary,
    .modal .btn-azul,
    .modal .btn-primary-saas,
    .modal .btn-premium-save {
        background: #26687B !important;
        border: 1px solid #26687B !important;
        color: #FFFFFF !important;
        border-radius: 0!important;
        height: 38px !important;
        padding: 0 18px !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
        transition: all 0.15s ease;
    }
    .modal .btn-primary:hover,
    .modal .btn-azul:hover,
    .modal .btn-primary-saas:hover,
    .modal .btn-premium-save:hover {
        background: #1F5565 !important;
        border-color: #1F5565 !important;
        color: #FFFFFF !important;
    }
    </style>

</head>

<body>

    @php
        $nombreUsuario = trim((string) session('nombre')) ?: 'Usuario';
        $iniciales = collect(preg_split('/\s+/u', $nombreUsuario))
            ->filter()->take(2)
            ->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))
            ->implode('');
        $rolUsuario = ucfirst(mb_strtolower((string) session('rol')));

        // Ciclo escolar vigente (agosto–julio): nov 2026 → 2026-2027, mar 2027 → 2026-2027
        $hoy = now();
        $inicioCiclo = $hoy->month >= 8 ? $hoy->year : $hoy->year - 1;
        $cicloEscolar = $inicioCiclo . '-' . ($inicioCiclo + 1);
    @endphp

    @include('components.sidebar')

    {{-- Barra superior --}}
    <header class="tw:fixed tw:top-0 tw:right-0 tw:left-[270px] tw:z-[1040] tw:flex tw:h-[60px] tw:items-center tw:justify-between tw:gap-4 tw:border-b tw:border-slate-200 tw:bg-white/90 tw:px-8 tw:backdrop-blur-md tw:transition-[left] tw:duration-300 tw:ease-out tw:sb-collapsed:left-[75px]">
        <div class="tw:flex tw:min-w-0 tw:flex-col tw:leading-tight">
            <span class="tw:truncate tw:text-[0.95rem] tw:font-semibold tw:tracking-tight tw:text-slate-900">Bachillerato Tecnológico Interamericano</span>
            <span class="tw:truncate tw:text-xs tw:font-medium tw:text-slate-500">Ciclo Escolar {{ $cicloEscolar }}</span>
        </div>

        <div class="dropdown tw:ml-auto tw:shrink-0">
            {{-- Perfil como "pill" interactivo --}}
            <button type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menú de {{ $nombreUsuario }}"
                class="tw:inline-flex tw:cursor-pointer tw:items-center tw:gap-2.5 tw:border tw:border-slate-200 tw:bg-white tw:py-1 tw:pr-3 tw:pl-1 tw:transition-colors tw:duration-150 tw:hover:bg-slate-50 tw:aria-expanded:bg-slate-50 tw:focus-visible:outline-2 tw:focus-visible:outline-offset-2 tw:focus-visible:outline-brand-500">
                <span class="tw:flex tw:size-8 tw:shrink-0 tw:items-center tw:justify-center tw:bg-brand-700 tw:text-[0.8125rem] tw:font-semibold tw:text-white">{{ $iniciales }}</span>
                <span class="tw:hidden tw:flex-col tw:text-left tw:leading-tight tw:sm:flex">
                    <span class="tw:max-w-[200px] tw:truncate tw:text-[0.8125rem] tw:font-semibold tw:text-slate-900">{{ $nombreUsuario }}</span>
                    @if($rolUsuario)
                        <span class="tw:text-[0.6875rem] tw:font-medium tw:text-slate-500">{{ $rolUsuario }}</span>
                    @endif
                </span>
                <i data-lucide="chevron-down" class="tw:size-4 tw:text-slate-400"></i>
            </button>

            {{-- .dropdown-menu: Bootstrap controla display; no añadir utilidades de display --}}
            <ul class="dropdown-menu dropdown-menu-end tw:mt-2 tw:min-w-[220px] tw:border tw:border-slate-200 tw:p-1.5 tw:shadow-raised">
                <li class="tw:px-3 tw:pt-2 tw:pb-2.5">
                    <p class="tw:m-0 tw:truncate tw:text-[0.8125rem] tw:font-semibold tw:text-slate-900">{{ $nombreUsuario }}</p>
                    @if($rolUsuario)
                        <p class="tw:m-0 tw:text-xs tw:text-slate-500">{{ $rolUsuario }}</p>
                    @endif
                </li>
                <li><hr class="tw:my-1 tw:border-slate-100 tw:opacity-100"></li>
                <li>
                    <a href="/editarPerfil" class="dropdown-item tw:flex tw:items-center tw:gap-2.5 tw:px-3 tw:py-2 tw:text-[0.8125rem] tw:font-medium tw:text-slate-700 tw:hover:bg-slate-100 tw:hover:text-slate-900">
                        <i data-lucide="user-round" class="tw:size-4 tw:text-slate-500"></i>
                        Editar perfil
                    </a>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" class="tw:m-0">
                        @csrf
                        <button type="submit" class="dropdown-item tw:flex tw:w-full tw:items-center tw:gap-2.5 tw:px-3 tw:py-2 tw:text-[0.8125rem] tw:font-medium tw:text-danger-600 tw:hover:bg-danger-50 tw:hover:text-danger-600">
                            <i data-lucide="log-out" class="tw:size-4"></i>
                            Cerrar sesión
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </header>

    {{-- .main-content se conserva como hook para hojas legacy --}}
    <main class="main-content tw:mt-[60px] tw:ml-[270px] tw:min-h-[calc(100vh-60px)] tw:overflow-x-hidden tw:bg-canvas tw:px-8 tw:py-6 tw:transition-[margin] tw:duration-300 tw:ease-out tw:sb-collapsed:ml-[75px]">
        @yield('content')
    </main>

    <!-- Contenedor global para Modales (evita problemas de z-index y stacking context con backdrop) -->
    <div id="contenedorModal"></div>

    <!-- Script Global para Limpieza de Modales y Backdrops residuales -->
    <script>
        document.addEventListener('hidden.bs.modal', function () {
            setTimeout(() => {
                if (!document.querySelector('.modal.show')) {
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                }
            }, 50);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide && typeof lucide.createIcons === 'function') {
                lucide.createIcons();
            }
        });
    </script>

    <!-- Reglas globales obligatorias para Modales (inmunes a hojas de estilo secundarias) -->
    <style>
        /* Backdrop Oscuro con Desenfoque de Fondo (shadcn/ui & Radix UI Dialog) */
        html body .modal-backdrop {
            --bs-backdrop-zindex: 1150 !important;
            --bs-backdrop-bg: #0F172A !important;
            --bs-backdrop-opacity: 0.65 !important;
            z-index: 1150 !important;
            background-color: rgba(15, 23, 42, 0.65) !important;
            backdrop-filter: blur(4px) !important;
            -webkit-backdrop-filter: blur(4px) !important;
            transition: opacity 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        html body .modal-backdrop.fade {
            opacity: 0 !important;
        }
        html body .modal-backdrop.show {
            opacity: 1 !important;
        }

        html body .modal {
            z-index: 1200 !important;
        }
        html body .modal-dialog {
            z-index: 1201 !important;
        }

        html body .modal .modal-content,
        html body .modal-content,
        html body .glass-modal,
        html body .modal-content.glass-modal {
            background: #FFFFFF !important;
            border: 1px solid #CBD5E1 !important;
            border-radius: 0!important;
            overflow: hidden !important;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.35), 0 0 0 1px rgba(226, 232, 240, 0.8) !important;
        }
        html body .modal .modal-header,
        html body .modal-header,
        html body .glass-modal .modal-header,
        html body .modal-header-system,
        html body .modal-header-custom {
            background: #FFFFFF !important;
            color: #0F172A !important;
            border-bottom: 1px solid #E2E8F0 !important;
            padding: 16px 24px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }
        html body .modal .modal-title,
        html body .modal-header .modal-title,
        html body .glass-modal .modal-header .modal-title,
        html body .modal-header-system .modal-title,
        html body .modal-header-custom .modal-title {
            font-size: 1.05rem !important;
            font-weight: 600 !important;
            color: #0F172A !important;
            margin: 0 !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 8px !important;
            letter-spacing: -0.01em !important;
        }
        html body .modal-header .modal-title i,
        html body .modal-header .modal-title svg,
        html body .glass-modal .modal-header .modal-title i {
            color: #26687B !important;
        }
        html body .modal .btn-close,
        html body .modal-header .btn-close,
        html body .btn-close-white,
        html body .btn-close-modal {
            width: 32px !important;
            height: 32px !important;
            border-radius: 0!important;
            background-color: transparent !important;
            filter: none !important;
            opacity: 0.6 !important;
            color: #64748B !important;
            transition: all 0.15s ease !important;
        }
        html body .modal .btn-close:hover,
        html body .modal-header .btn-close:hover,
        html body .btn-close-white:hover,
        html body .btn-close-modal:hover {
            background-color: #F1F5F9 !important;
            color: #0F172A !important;
            opacity: 1 !important;
        }
        html body .modal .modal-body,
        html body .modal-body,
        html body .glass-modal .modal-body {
            background: #FFFFFF !important;
            padding: 20px 24px !important;
            color: #0F172A !important;
        }
        html body .modal .modal-footer,
        html body .modal-footer,
        html body .glass-modal .modal-footer,
        html body .modal-footer-custom {
            background: #F8FAFC !important;
            border-top: 1px solid #E2E8F0 !important;
            padding: 14px 24px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: flex-end !important;
            gap: 10px !important;
        }
    </style>
</body>

</html>