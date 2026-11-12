<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>BTI</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">

    <!-- Google Fonts: Inter & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

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
        /* Paleta Institucional BTI (Azul Institucional Original) */
        --brand-primary: #317D92;
        --brand-primary-hover: #26687B;
        --brand-primary-subtle: #EBF5F8;
        --brand-accent: #6BC7E8;
        
        /* Superficies (Escala Slate) */
        --bg-canvas: #F8FAFC;
        --bg-surface: #FFFFFF;
        --bg-muted: #F1F5F9;
        
        /* Bordes y Divisores */
        --border-subtle: #E2E8F0;
        --border-focus: #317D92;
        
        /* Tipografía */
        --text-primary: #0F172A;
        --text-secondary: #475569;
        --text-muted: #94A3B8;

        /* Estados Semánticos (Soft Badges) */
        --success-bg: #DCFCE7; --success-text: #15803D; --success-border: #BBF7D0;
        --info-bg: #E0F2FE;    --info-text: #0369A1;    --info-border: #BAE6FD;
        --neutral-bg: #F1F5F9; --neutral-text: #334155; --neutral-border: #E2E8F0;
        --danger-hover-bg: #FEF2F2; --danger-text: #DC2626;

        /* Geometría y Elevación */
        --radius-sm: 6px;
        --radius-md: 8px;
        --radius-lg: 12px;
        --shadow-xs: 0 1px 2px 0 rgba(15, 23, 42, 0.03);
        --shadow-card: 0 1px 3px 0 rgba(15, 23, 42, 0.04), 0 1px 2px -1px rgba(15, 23, 42, 0.02);
    }

    /* Reset Crítico para Submenús del Sidebar (Evita contaminación de fondos blancos de Bootstrap y vistas) */
    .sidebar .accordion,
    .sidebar .accordion-item,
    .sidebar .accordion-header,
    .sidebar .accordion-collapse,
    .sidebar .accordion-body {
        background: transparent !important;
        background-color: transparent !important;
        border: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
    }

    html, body {
        margin: 0;
        padding: 0;
        min-height: 100%;
        background-color: var(--bg-canvas);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: var(--text-primary);
        -webkit-font-smoothing: antialiased;
    }

    /* Navbar Superior Corporativo - Azul Institucional BTI (#26687B) */
    .navbar {
        background: #26687B !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12) !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12) !important;
        padding: 0 24px !important;
        height: 60px;
        z-index: 1040;
        position: fixed;
        top: 0;
        left: 0;
        width: 100% !important;
        margin-left: 0 !important;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .navbar-brand {
        font-size: 0.95rem;
        font-weight: 700;
        letter-spacing: -0.01em;
        color: #FFFFFF !important;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        margin: 0;
    }
    .navbar-brand img {
        border-radius: 50%;
        width: 38px;
        height: 38px;
        object-fit: contain;
    }
    .navbar .nav-link {
        color: rgba(255, 255, 255, 0.9) !important;
        font-size: 0.875rem;
        font-weight: 500;
        transition: color 0.15s ease, background-color 0.15s ease;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        padding: 6px 10px;
        border-radius: 6px;
    }
    .navbar .nav-link:hover,
    .navbar .nav-link:focus {
        color: #FFFFFF !important;
        background: rgba(255, 255, 255, 0.12);
    }
    .navbar .user-welcome-text {
        color: #FFFFFF;
        font-weight: 600;
        font-size: 0.875rem;
    }
    .dropdown-menu {
        border: 1px solid var(--border-subtle, #E2E8F0);
        border-radius: var(--radius-md, 8px);
        box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.08);
        padding: 6px;
    }
    .dropdown-item {
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: var(--radius-sm, 6px);
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

    /* Contenedor Principal (Main Viewport) */
    .main-content {
        margin-left: 270px;
        margin-top: 60px;
        background-color: var(--bg-canvas);
        min-height: calc(100vh - 60px);
        padding: 24px 32px;
        overflow-x: hidden;
        width: calc(100% - 270px);
        transition: margin-left 0.3s ease, width 0.3s ease;
    }
    body.sidebar-hidden .main-content {
        margin-left: 75px;
        width: calc(100% - 75px);
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
        border-radius: var(--radius-sm) !important;
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
        border-radius: var(--radius-sm) !important;
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
        border-radius: var(--radius-sm);
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
        border-radius: var(--radius-sm) !important;
        padding: 0 12px;
        font-size: 0.875rem;
        color: var(--text-primary);
        background: var(--bg-surface);
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .form-control:focus, .form-select:focus, .form-control-premium:focus, .form-select-premium:focus {
        outline: none;
        border-color: var(--brand-primary) !important;
        box-shadow: 0 0 0 3px rgba(30, 91, 102, 0.12) !important;
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
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-card);
        overflow: hidden;
    }

    /* Soft Badges */
    .badge-soft {
        display: inline-flex;
        align-items: center;
        height: 22px;
        padding: 0 8px;
        border-radius: 9999px;
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
        border-color: #CBE4E8;
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
        border-radius: 14px !important;
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
        color: #1E5B66 !important;
    }
    .modal-header .btn-close,
    .btn-close-white {
        width: 32px;
        height: 32px;
        border-radius: 6px;
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
        border-radius: 6px;
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
        border-color: #1E5B66 !important;
        box-shadow: 0 0 0 3px rgba(30, 91, 102, 0.12) !important;
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
        border-radius: 6px !important;
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
        background: #1E5B66 !important;
        border: 1px solid #1E5B66 !important;
        color: #FFFFFF !important;
        border-radius: 6px !important;
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
        background: #16464F !important;
        border-color: #16464F !important;
        color: #FFFFFF !important;
    }
    </style>

</head>

<body>

    <nav class="navbar fixed-top">
        <div class="w-100 d-flex align-items-center justify-content-between">

            <!-- Sección Izquierda: Identidad Institucional Limpia -->
            <!-- Sección Izquierda: Identidad Institucional -->
            <div class="d-flex align-items-center">
                <img src="{{ asset('img/logo.png') }}" alt="logo" width="38" height="38" class="me-3" style="border-radius: 50%; object-fit: contain;">
                <a class="navbar-brand text-white fw-bold mb-0" href="/home">
                    Bachillerato Tecnológico Interamericano
                </a>
            </div>

            <!-- Botón colapsable para responsive (compatibilidad) -->
            <button class="navbar-toggler d-none" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Sección Derecha: Usuario y Menú de Perfil -->
            <div class="d-flex align-items-center gap-3" id="menuNav">
                <span class="user-welcome-text d-none d-sm-inline">
                    ¡Bienvenido, {{ session('nombre') }}!
                </span>

                <div class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ti ti-user-circle me-1" style="font-size: 17px;"></i> Perfil
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width: 180px;">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="/editarPerfil">
                                <i class="ti ti-edit" style="font-size: 16px;"></i>
                                <span>Editar perfil</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider my-1" style="border-color: #F1F5F9;">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                    <i class="ti ti-logout" style="font-size: 16px;"></i>
                                    <span>Cerrar sesión</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </nav>

    @include('components.sidebar')

    <div class="main-content">
        @yield('content')
    </div>

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
            border-radius: 14px !important;
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
            color: #1E5B66 !important;
        }
        html body .modal .btn-close,
        html body .modal-header .btn-close,
        html body .btn-close-white,
        html body .btn-close-modal {
            width: 32px !important;
            height: 32px !important;
            border-radius: 6px !important;
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