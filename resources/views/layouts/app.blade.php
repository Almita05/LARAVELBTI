<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>BTI</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
    .btn-azul {
        background-color: #1A338F;
        border-color: #1A338F;
        color: white;
    }

    .btn-azul:hover {
        background-color: #162a73;
        border-color: #162a73;
        color: white;
    }

    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        min-height: 100vh;

        background: linear-gradient(
        135deg,
        #0F2E6D 0%,
        #1E6FA8 50%,
        #6BC7E8 100%
    );

        background-attachment: fixed;
    }

    .navbar {
        background: rgba(15, 46, 109, .95);
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, .15);
        padding: 12px 25px;
        transition: .3s;
    }

    .navbar-brand img {
        border-radius: 50%;
        padding: 2px;
        background: white;
    }

    /* TEXTO NAV */
    .navbar .nav-link {
        color: #ffffff !important;
        font-weight: 500;
        margin-right: 10px;
    }

    .navbar .nav-link:hover {
        color: #8ACFDE !important;
    }

    /* DROPDOWN */
    .dropdown-menu {
        border-radius: 10px;
        border: none;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .dropdown-item:hover {
        background: #1A338F;
        color: #fff;
    }

    /* CONTENIDO */
    .content {
        padding: 20px;
        
    }
    </style>

</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">

            <div class="d-flex align-items-center">
                <img src="{{ asset('img/logo.png') }}" alt="logo" width="45" height="45" class="me-3">
                <a class="navbar-brand text-white fw-bold mb-0" href="/">
                    Bachillerato Tecnológico Interamericano
                </a>
            </div>

            <!-- BOTÓN RESPONSIVE -->
            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- MENÚ -->
            <div class="collapse navbar-collapse" id="menuNav">

                <ul class="navbar-nav ms-auto">

                    <!-- INICIO -->
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <i class="bi bi-house"></i> Inicio
                        </a>
                    </li>

                    <!-- ALUMNOS -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person"></i> Alumnos
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/alumnos"><i class="fa-solid fa-magnifying-glass"></i> Buscador</a></li>
                        </ul>
                    </li>

                    <!-- DOCENTES -->
                    <li class="nav-item">
                        <a class="nav-link" href="/docentes">
                            <i class="bi bi-people"></i> Docentes
                        </a>
                    </li>

                    <!-- MATERIAS -->
                    <li class="nav-item">
                        <a class="nav-link" href="/materias">
                            <i class="bi bi-book"></i> Materias
                        </a>
                    </li>

                    <!-- EQUIVALENCIAS -->
                    <li class="nav-item">
                        <a class="nav-link" href="/equivalencias">
                            <i class="bi bi-file-earmark-text"></i> Equivalencias
                        </a>
                    </li>

                    <!-- GRUPOS -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="/grupos" data-bs-toggle="dropdown">
                            <i class="bi bi-diagram-3"></i> Grupos
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/grupos">Dashboard grupos</a></li>
                            <li><a class="dropdown-item" href="/grupos/alta">Dar de alta grupo</a></li>
                        </ul>
                    </li>


                    <!-- imprimir diferentes cosas -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="/grupos" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-print"></i> Imprimir
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/grupos"> <i class="bi bi-journal-bookmark-fill"></i> Actas de calificaciones para docentes</a></li>
                            <li><a class="dropdown-item" href="/grupos/alta"> <i class="fa-solid fa-user-clock"></i> Listas de asistencias para docentes</a>
                            </li>
                            <li><a class="dropdown-item" href="/grupos/alta"><i class="bi bi-file-earmark-text"></i> Kardex BGNE</a></li>
                            <li><a class="dropdown-item" href="/grupos/alta"><i class="bi bi-file-earmark-text"></i> Boleta de calificaciones BTI </a></li>
                        </ul>
                    </li>





                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-gear"></i> Perfil
                        </a>

                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/editarPerfil">Editar perfil</a></li>
                        </ul>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/grupos/alta">Cerrar sesión</a></li>
                        </ul>
                    </li>

                </ul>

            </div>
        </div>
    </nav>

    <!-- CONTENIDO -->
    <div class="content" style="margin-top:70px;">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>