<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>BTI</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
   .btn-azul {
    background: rgb(49, 125, 146);
    border-color: rgb(49, 125, 146);
    color: #fff;
}

.btn-azul:hover {
    background: rgb(38, 104, 123);
    border-color: rgb(38, 104, 123);
    color: #fff;
}

/* =========================
   FONDO GENERAL
========================= */

html,
body{
    margin:0;
    padding:0;
    min-height:100%;
}

body{
    font-family:'Segoe UI',sans-serif;
    background: rgb(49, 125, 146);
    background-attachment: fixed;
}

/* =========================
   NAVBAR
========================= */

.navbar{
    background: rgba(38,104,123,.95);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    box-shadow:0 4px 15px rgba(0,0,0,.25);
    padding:12px 25px;
}

.navbar-brand img{
    border-radius:50%;
    padding:2px;
    background:#fff;
}

.navbar .nav-link{
    color:#fff !important;
    font-weight:500;
    margin-right:10px;
    transition:.2s;
}

.navbar .nav-link:hover{
    color:#d9f4fb !important;
}

/* =========================
   DROPDOWN
========================= */

.dropdown-menu{
    border:none;
    border-radius:14px;
    box-shadow:0 10px 25px rgba(0,0,0,.20);
}

.dropdown-item:hover{
    background:rgb(49,125,146);
    color:#fff;
}

/* =========================
   CONTENIDO
========================= */

.content{
    margin-top:85px;
    padding:25px;
}

/* Tarjetas generales */

.card{
    background:rgba(98,191,214,.80);
    backdrop-filter:blur(16px);
    -webkit-backdrop-filter:blur(16px);
    border:none;
    border-radius:22px;
    box-shadow:0 15px 35px rgba(0,0,0,.25);
}

/* Botones Bootstrap */

.btn-primary{
    background:rgb(49,125,146);
    border-color:rgb(49,125,146);
}

.btn-primary:hover{
    background:rgb(38,104,123);
    border-color:rgb(38,104,123);
}

/* Tablas */

.table{
    background:white;
    border-radius:15px;
    overflow:hidden;
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
        <li>
            <a class="dropdown-item" href="/editarPerfil">
                <i class="bi bi-pencil-square"></i> Editar perfil
            </a>
        </li>

        <li><hr class="dropdown-divider"></li>

        <li>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item text-danger">
                    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                </button>
            </form>
        </li>
    </ul>
</li>
                </ul>

            </div>
        </div>
    </nav>

    <div class="container-fluid content">
    @yield('content')
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>