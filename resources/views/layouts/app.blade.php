<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="d-flex">

    <!-- SIDEBAR -->
    <div class="bg-dark text-white p-3 vh-100" style="width: 250px;">
        <h4 class="text-center">BTI</h4>
        <hr>

        <ul class="nav flex-column">

            <!-- ALUMNOS -->
            <li class="nav-item mb-2">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#alumnosMenu" role="button">
                    Alumnos
                </a>
                <div class="collapse ps-3" id="alumnosMenu">
                    <a href="/alumnos/buscador" class="nav-link text-white">Buscador de alumnos</a>
                </div>
            </li>

            <!-- DOCENTES -->
            <li class="nav-item mb-2">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#docentesMenu" role="button">
                    Docentes
                </a>
                <div class="collapse ps-3" id="docentesMenu">
                    <a href="/docentes" class="nav-link text-white">Docentes</a>
                </div>
            </li>

            <!-- EQUIVALENCIAS -->
            <li class="nav-item mb-2">
                <a href="/equivalencias" class="nav-link text-white">
                    Trámite de equivalencias
                </a>
            </li>

            <!-- GRUPOS -->
            <li class="nav-item mb-2">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#gruposMenu" role="button">
                    Grupos
                </a>
                <div class="collapse ps-3" id="gruposMenu">
                    <a href="/grupos/alta" class="nav-link text-white">Alta grupos</a>
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