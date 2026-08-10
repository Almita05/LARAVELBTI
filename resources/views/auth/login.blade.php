<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BTI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
 <style>

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;

            display: flex;
            align-items: center;
            justify-content: center;

            background:
                radial-gradient(
                    circle at 10% 90%,
                    rgba(107, 199, 232, .25),
                    transparent 30%
                ),
                radial-gradient(
                    circle at 95% 10%,
                    rgba(107, 199, 232, .30),
                    transparent 30%
                ),
                #f4f8fb;

            padding: 30px;
        }
        .login-container {
            width: 100%;
            max-width: 1150px;
            min-height: 650px;
            display: flex;
            background: #fff;
            border-radius: 22px;
            overflow: hidden;
            box-shadow:
                0 25px 60px rgba(15, 46, 109, .20);

            animation: fadeUp .7s ease;
        }


        .login-left {
            width: 50%;

            background:
                linear-gradient(
                    135deg,
                    #ffffff 0%,
                    #f7fbfd 100%
                );

            display: flex;
            flex-direction: column;

            align-items: center;
            justify-content: center;

            text-align: center;

            padding: 50px;

            position: relative;
        }


        .login-left::before {
            content: "";
            position: absolute;

            width: 280px;
            height: 280px;

            border-radius: 50%;

            background: rgba(107, 199, 232, .12);

            top: -120px;
            left: -120px;
        }


        .login-left::after {
            content: "";
            position: absolute;

            width: 250px;
            height: 250px;

            border-radius: 50%;

            background: rgba(49, 125, 146, .08);

            bottom: -120px;
            right: -100px;
        }


        .logo-container {
            position: relative;
            z-index: 2;

            width: 260px;
            height: 260px;

            display: flex;
            align-items: center;
            justify-content: center;

            margin-bottom: 25px;
        }


        .logo-container img {
            width: 220px;
            height: 220px;

            object-fit: contain;

            filter:
                drop-shadow(
                    0 10px 20px
                    rgba(0, 0, 0, .15)
                );
        }


        .left-title {
            position: relative;
            z-index: 2;

            color: rgb(15, 46, 109);

            font-size: 24px;
            font-weight: 700;

            margin-bottom: 10px;
        }


        .left-subtitle {
            position: relative;
            z-index: 2;

            color: #6c757d;

            font-size: 15px;

            max-width: 400px;

            line-height: 1.6;
        }


        .login-right {
            width: 50%;
            background:
                linear-gradient(
                    135deg,
                   rgb(38, 104, 123)
                );
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 55px 75px;
        }
        .login-header {
            text-align: center;

            margin-bottom: 35px;
        }
        .login-header h2 {
            font-size: 42px;

            font-weight: 700;

            margin-bottom: 10px;

            letter-spacing: .3px;
        }


        .login-header p {
            margin: 0;
            font-size: 15px;
        }

        .alert {
            border: none;
            border-radius: 12px;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .form-label {
            color: #fff;
            font-weight: 500;
            margin-bottom: 8px;
        }


        .input-group {
            margin-bottom: 22px;
        }


        .input-group-text {
            background: rgba(255,255,255,.95);
            border: none;
            color: rgb(49, 125, 146);
            border-radius: 14px 0 0 14px;
            padding-left: 17px;
            padding-right: 10px;
        }


        .form-control {
            background: rgba(255,255,255,.95);
            border: none;
            color: #333;
            border-radius: 0 14px 14px 0;
            padding: 14px 15px;
            height: 52px;
            transition: .25s;
        }


        .form-control::placeholder {
            color: #9ba7b4;
        }


        .form-control:focus {
            background: #fff;

            color: #333;

            border: none;

            box-shadow:
                0 0 0 3px
                rgba(255,255,255,.20);
        }

        .btn-login {
            width: 100%;
            background:white;
            border: none;
            border-radius: 14px;
            height: 52px;
            font-size: 16px;
            font-weight: 700;
            color:black;

        }


        .btn-login:hover {
            background:white;
            color:black;
            transform: translateY(-2px);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .footer-text {
            text-align: center;

            color: rgba(255,255,255,.65);

            margin-top: 30px;

            font-size: .85rem;
        }


        @keyframes fadeUp {

            from {
                opacity: 0;

                transform:
                    translateY(25px);
            }

            to {
                opacity: 1;

                transform:
                    translateY(0);
            }

        }

        @media (max-width: 900px) {

            body {
                padding: 20px;
            }

            .login-container {
                max-width: 550px;

                min-height: auto;

                flex-direction: column;
            }

            .login-left {
                width: 100%;

                padding: 35px 25px;

                min-height: 300px;
            }

            .logo-container {
                width: 170px;
                height: 170px;

                margin-bottom: 10px;
            }

            .logo-container img {
                width: 150px;
                height: 150px;
            }

            .left-title {
                font-size: 20px;
            }

            .login-right {
                width: 100%;
                padding: 45px 30px;
            }

            .login-header h2 {
                font-size: 34px;
            }
        }


        @media (max-width: 480px) {

            body {
                padding: 10px;
                background: rgb(38, 104, 123);
            }

            .login-left {
                min-height: 270px;

                padding: 25px 15px;
            }

            .login-right {
                padding: 35px 22px;
            }

            .login-header h2 {
                font-size: 30px;
            }

            .left-subtitle {
                font-size: 13px;
            }
        }

    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-left">
            <div class="logo-container">
                <img src="{{ asset('img/logo.png') }}" alt="Logo BTI">
            </div>
            <div class="left-title">
                BACHILLERATO
                TECNOLÓGICO
                INTERAMERICANO
            </div>
            <div class="left-subtitle">¡Formando líderes desde 1999!
            </div>
        </div>
        <div class="login-right">
            <div class="login-header">
                <h2>
                    ¡Bienvenido!
                </h2>
                <p>
                    Ingresa a tu cuenta para continuar
                </p>
            </div>
            @if(session('error'))
            <div class="alert alert-danger text-center">
                <i class="fa-solid fa-circle-exclamation me-2"></i>
                {{ session('error') }}
            </div>
            @endif
            <form method="POST" action="/login">
                @csrf
                <!-- USUARIO -->
                <label class="form-label">
                    Usuario
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fa-solid fa-user"></i>
                    </span>
                    <input type="text" name="usuario" class="form-control" placeholder="Ingrese usuario"
                        autocomplete="username" required>
                </div>
                <label class="form-label">
                    Contraseña
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" name="password" class="form-control" placeholder="Ingrese contraseña"
                        autocomplete="current-password" required>
                </div>
                <button type="submit" class="btn btn-login">
                    <i class="fa-solid fa-right-to-bracket me-2"></i>
                    Ingresar
                </button>
            </form>
            <div class="footer-text">
                © {{ date('Y') }}
                Bachillerato Tecnológico Interamericano
            </div>
        </div>
    </div>


    <!-- <div class="card login-card">

        <div class="login-header">

            <img src="{{ asset('img/logo.png') }}" alt="Logo">
            <br>
            <br>

            <h5>BACHILLERATO TECNOLÓGICO INTERAMERICANO</h5>

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
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            <path fill-rule="evenodd"
                                d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                        </svg>
                        Usuario
                    </label>

                    <input type="text" name="usuario" class="form-control" placeholder="Ingrese usuario" required>

                </div>



                <div class="mb-4">

                    <label class="form-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-lock-fill" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3" />
                        </svg>
                        Contraseña
                    </label>

                    <input type="password" name="password" class="form-control" placeholder="Ingrese contraseña"
                        required>

                </div>



                <button type="submit" class="btn btn-login w-100">

                    <i class="fa-solid fa-right-to-bracket me-2"></i>
                    Ingresar

                </button>
            </form>


            <div class="footer-text">

                © {{ date('Y') }} Bachillerato Tecnológico Interamericano

            </div>


        </div>


    </div> -->


</body>

</html>