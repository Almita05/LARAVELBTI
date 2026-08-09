<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>BTI</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

<link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

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

    html,
    body {
        margin: 0;
        padding: 0;
        min-height: 100%;
    }

    body {
        font-family: 'Segoe UI', sans-serif;
       
        background-attachment: fixed;
    }

    .navbar {
        background: rgba(38, 104, 123, .95);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, .25);
        padding: 12px 25px;
        z-index: 1100;
    }

    .navbar-brand img {
        border-radius: 50%;
        padding: 2px;
        background: #fff;
    }

    .navbar .nav-link {
        color: #fff !important;
        font-weight: 500;
        margin-right: 10px;
        transition: .2s;
    }

    .navbar .nav-link:hover {
        color: #d9f4fb !important;
    }

    .dropdown-menu {
        border: none;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, .20);
    }

    .dropdown-item:hover {
        background: rgb(49, 125, 146);
        color: #fff;
    }

  
    .card {
        background: rgba(98, 191, 214, .80);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: none;
        border-radius: 22px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, .25);
    }

    .btn-primary {
        background: rgb(49, 125, 146);
        border-color: rgb(49, 125, 146);
    }

    .btn-primary:hover {
        background: rgb(38, 104, 123);
        border-color: rgb(38, 104, 123);
    }

    .table {
        border-radius: 15px;
        overflow: hidden;
         background: rgb(49, 125, 146);
    }

   .main-content {
    margin-left: 270px;
    margin-top: 85px;
    padding: 30px;
    min-height: calc(100vh - 85px);
    width: calc(100% - 270px);
    transition: all .3s ease;
}




.page-container {
    padding: 30px;
    min-height: 100vh;
}


.page-title {
    color:rgb(7, 101, 136);
    font-weight: 700;
    text-shadow: 0 2px 8px rgba(0, 0, 0, .2);
}

.glass-card {
    background: rgba(255, 255, 255, .08);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, .15);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, .15);
    padding: .5%;
}


.glass-footer {
    border-top: 1px solid rgba(255, 255, 255, .15);
}

.table-head{
    background: rgb(49, 125, 146) !important;
    color: rgb(8, 0, 0) !important;
}



.btn-regresar {
     background: rgb(49, 125, 146) !important;
    border: none;
    font-weight: 600;
    color: white;
}


.btn-regresar:hover {
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(107, 199, 232, .4);
}

.btn-azul {
     background: rgb(49, 125, 146) !important;
    border: none;
    font-weight: 600;
}

.btn-azul:hover {
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(107, 199, 232, .4);
}

.textoDocentes{
    color:white;
}


.card {
    background: transparent !important;
}

.btn-ver {
    background: #00BCD4;
    color: white;
    border: none;
}

.btn-ver:hover {
    background: #0097A7;
    color: white;
}

.btn-editar {
    background: #FF9800;
    color: white;
    border: none;
}

.btn-editar:hover {
    background: #F57C00;
    color: white;
}

.btn-eliminar {
    background: #E53935;
    color: white;
    border: none;
}

.btn-eliminar:hover {
    background: #C62828;
    color: white;
}


/* Modal */
.modal-content {
    border: none;
}

.modal-header {
     background: rgb(49, 125, 146) !important;
    color: white;
}

.glass-modal {
    border: 1px solid rgba(255, 255, 255, .15);
    border-radius: 20px;
    overflow: hidden;
    color: white;
    backdrop-filter: blur(12px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, .35);
}

.glass-modal .modal-header {
    background: linear-gradient(135deg, rgb(73, 164, 190), #1E6FA8);
    color: #fff;
}

.glass-modal .modal-body {
    background: white;
}


.accordion-item {
    background: rgba(255, 255, 255, .06);
    border: none;
    border-radius: 14px;
    overflow: hidden;
}

.accordion-button,
.accordion-button.collapsed,
.accordion-button:not(.collapsed) {
    background: linear-gradient(135deg, rgb(73, 164, 190), #1E6FA8) !important;
    color: #fff !important;
    font-weight: 600;
    box-shadow: none !important;
    border: none;
}

.accordion-button:focus {
    box-shadow: none !important;
}

.accordion-button::after {
    filter: brightness(0) invert(1);
}

.accordion-body {
    background: white;
}

.form-label {
    font-weight: 500;
    color: black;
}

.form-control-premium,
.form-select-premium {
    background: #fff;
    color: #212529;
    min-height: 48px;
}

.form-control-premium:hover,
.form-select-premium:hover {
    border-color: rgb(49, 125, 146) !important;
}

.form-control-premium:focus,
.form-select-premium:focus {
    border-color: rgb(49, 125, 146) !important;
    box-shadow:
        0 0 0 3px rgba(102, 201, 255, .20),
        inset 0 1px 2px rgba(0, 0, 0, .04);
}

.container-principal{
    color:rgb(49, 125, 146);
}



    </style>

</head>

<body>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">

            <div class="d-flex align-items-center">
                <img src="{{ asset('img/logo.png') }}" alt="logo" width="45" height="45" class="me-3">
                <a class="navbar-brand text-white fw-bold mb-0" href="/home">
                    Bachillerato Tecnológico Interamericano
                </a>
            </div>

            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menuNav">

                <ul class="navbar-nav ms-auto">


                    <li class="nav-item d-flex align-items-center me-3">
                        <span class="text-white fw-semibold">
                            ¡Bienvenido, {{ session('nombre') }}!
                        </span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/home">
                            <i class="bi bi-house"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-person-gear"></i> Perfil
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="/editarPerfil">
                                    <i class="bi bi-pencil-square"></i> Editar perfil
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

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

    @include('components.sidebar')

    <div class="main-content">
        @yield('content')
    </div>

    <!-- Contenedor global para Modales (evita problemas de z-index y stacking context con backdrop) -->
    <div id="contenedorModal"></div>

    

</body>

</html>