<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BTI</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0d6efd, #0b2e59);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,.25);
        }

        .login-header {
            background: white;
            text-align: center;
            padding: 35px 20px 20px;
        }

        .login-header img {
            width: 120px;
            height: 120px;
            object-fit: contain;
            margin-bottom: 15px;
        }

        .login-body {
            background: white;
            padding: 30px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px;
        }

        .btn-login {
            border-radius: 12px;
            padding: 12px;
            font-weight: bold;
            background: #0d6efd;
            border: none;
        }

        .btn-login:hover {
            background: #084298;
        }

        .footer-text {
            font-size: 13px;
            color: #777;
            text-align:center;
            margin-top:20px;
        }
    </style>

</head>

<body>


<div class="card login-card">

    <div class="login-header">

        <img src="{{ asset('img/logo.png') }}" alt="Logo">

        <h3 class="fw-bold">
            Sistema Escolar
        </h3>

        <p class="text-muted mb-0">
            Inicia sesión para continuar
        </p>

    </div>


    <div class="login-body">


        @if(session('error'))

            <div class="alert alert-danger text-center">
                {{ session('error') }}
            </div>

        @endif



        <form method="POST" action="/login">

            @csrf


            <div class="mb-3">

                <label class="form-label">
                    Usuario
                </label>

                <input 
                    type="text"
                    name="usuario"
                    class="form-control"
                    placeholder="Ingrese usuario"
                    required>

            </div>



            <div class="mb-4">

                <label class="form-label">
                    Contraseña
                </label>

                <input 
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Ingrese contraseña"
                    required>

            </div>



            <button 
                type="submit"
                class="btn btn-primary w-100 btn-login">

                Ingresar

            </button>


        </form>


        <div class="footer-text">

            © {{ date('Y') }} Sistema Escolar

        </div>


    </div>


</div>


</body>
</html>