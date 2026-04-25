<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<title>BTI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

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
}

/* NAVBAR */
.navbar {
    background: #1A338F;
    padding: 10px 20px;
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
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
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

     <img src="{{ asset('img/logo.png') }}" alt="logo" width="40px" height="40px">
        <a class="navbar-brand text-white fw-bold" href="/">Bachillerato Tecnológico Interamericano</a>

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
                        <li><a class="dropdown-item" href="/alumnos">Buscador</a></li>
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
                        <li><a class="dropdown-item" href="/grupos/alta">Alta</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-person-gear"></i> Perfil  
                    </a>
                    
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/">Editar perfil</a></li>
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