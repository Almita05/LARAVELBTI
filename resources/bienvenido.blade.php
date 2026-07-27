<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login correcto</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">


<div class="container vh-100 d-flex justify-content-center align-items-center">


    <div class="card shadow p-5 text-center" style="max-width:450px;">


        <div class="mb-3">
            <img src="{{ asset('img/logo.png') }}" 
                 width="100">
        </div>


        <h2 class="text-success">
            ¡Login correcto!
        </h2>


        <p class="mt-3">
            Bienvenido:
        </p>


        <h4>
            {{ session('nombre') }}
        </h4>


        <p class="text-muted">
            Rol: {{ session('rol') }}
        </p>


        <a href="/logout" class="btn btn-danger mt-3">
            Cerrar sesión
        </a>


    </div>


</div>


</body>
</html>