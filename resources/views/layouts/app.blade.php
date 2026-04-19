<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>BTI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
    /* SIDEBAR */
    .sidebar {
        width: 250px;
        height: 100vh;
        background: #142855;
        /* azul muy ligero */
        color: #1e293b;
        padding: 15px;
        border-right: 1px solid #dbeafe;
        overflow-y: auto;
    }

    /* HEADER CON LOGO */
    .sidebar-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    /* LOGO */
    .sidebar-header img {
        width: 40px;
        height: 40px;
        object-fit: contain;
    }

    /* TITULO */
    .sidebar-header h4 {
        margin: 0;
        font-weight: bold;
        color: #e0f2fe;
    }

    /* LINKS */
    .sidebar .nav-link {
        color: #93c5fd;
        border-radius: 10px;
        padding: 10px 12px;
        transition: all 0.25s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* HOVER */
    .sidebar .nav-link:hover {
        background: #1e3a8a;
        color: #bfdbfe;
        transform: translateX(5px);
    }

    /* ACTIVO */
    .sidebar .nav-link.active {
        background: #0ea5e9;
        color: #fff;
        box-shadow: 0 4px 10px rgba(14, 165, 233, 0.3);
    }

    /* SUBMENU */
    .sidebar .collapse .nav-link {
        font-size: 14px;
        padding-left: 20px;
        color: #93c5fd;
    }

    /* HOVER SUBMENU */
    .sidebar .collapse .nav-link:hover {
        color: #0284c7;
    }

    /* FLECHA */
    .menu-toggle::after {
        content: "▼";
        font-size: 12px;
        transition: transform 0.3s ease;
    }

    /* ROTAR */
    .menu-toggle[aria-expanded="true"]::after {
        transform: rotate(180deg);
    }

    /* SCROLL */
    .sidebar::-webkit-scrollbar {
        width: 5px;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: #93c5fd;
        border-radius: 10px;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f5f9;
        transition: 0.2s;
    }

    .badge {
        font-size: 12px;
        padding: 6px 10px;
    }

    .btn-sm {
        border-radius: 8px;
    }
    </style>
</head>

<body>

    <div class="d-flex">

        <div class="sidebar">

            <!-- HEADER -->
            <div class="sidebar-header">
                <img src="{{ asset('img/logo.png') }}" alt="logo">
                <h4>BTI</h4>
            </div>

            <!-- MENÚ -->
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a class="nav-link" href="/">
                        <i class="bi bi-house"></i>Inicio</a>
                </li>


                <li class="nav-item mb-2">
                    <a class="nav-link menu-toggle" data-bs-toggle="collapse" href="#alumnosMenu">
                        <span><i class="bi bi-person"></i> Alumnos</span>
                    </a>
                    <div class="collapse ps-2" id="alumnosMenu">
                        <a href="/alumnos" class="nav-link">Buscador de alumnos</a>
                    </div>
                </li>

                <li class="nav-item mb-2">
                    <a class="nav-link menu-toggle" data-bs-toggle="collapse" href="#docentesMenu">
                        <span><i class="bi bi-people"></i> Docentes</span>
                    </a>
                    <div class="collapse ps-2" id="docentesMenu">
                        <a href="/docentes" class="nav-link">Docentes</a>
                    </div>
                </li>

                <li class="nav-item mb-2">
                    <a class="nav-link menu-toggle" data-bs-toggle="collapse" href="#materiasMenu">
                        <span><i class="bi bi-book"></i> Materias</span>
                    </a>
                    <div class="collapse ps-2" id="materiasMenu">
                        <a href="/materias" class="nav-link">Materias</a>
                    </div>
                </li>

                <li class="nav-item mb-2">
                    <a href="/equivalencias" class="nav-link">
                        <span><i class="bi bi-file-earmark-text"></i> Equivalencias</span>
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a class="nav-link menu-toggle" data-bs-toggle="collapse" href="#gruposMenu">
                        <span><i class="bi bi-diagram-3"></i> Grupos</span>
                    </a>
                    <div class="collapse ps-2" id="gruposMenu">
                        <a href="/grupos/alta" class="nav-link">Alta grupos</a>
                    </div>
                </li>

            </ul>
        </div>

        <!-- CONTENIDO -->
        <div class="p-4 w-100">
            @yield('content')
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>