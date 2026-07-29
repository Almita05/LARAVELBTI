<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BTI</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
       body{
    min-height:100vh;
    margin:0;
    display:flex;
    align-items:center;
    justify-content:center;
    font-family:'Segoe UI',sans-serif;
         background: rgb(49, 125, 146);
}

.login-card{
    width:100%;
    max-width:420px;
    background:rgba(98, 191, 214, 0.8);
    backdrop-filter:blur(16px);
    -webkit-backdrop-filter:blur(16px);
    border:1px solid rgba(111, 199, 220,.12);
    border-radius:22px;
    box-shadow:0 20px 45px rgba(0,0,0,.35);
    animation:fadeUp .7s ease;
}

.login-header{
    text-align:center;
    padding:35px 30px 20px;
    color:#fff;
}

.login-header img{
    width:110px;
    height:110px;
    object-fit:contain;
    margin-bottom:15px;
    filter:drop-shadow(0 4px 12px rgba(0,0,0,.35));
}

.login-header h3{
    font-weight:700;
    letter-spacing:.5px;
    margin-bottom:5px;
}

.login-header p{
    color:rgba(255,255,255,.70);
}

.login-body{
    padding:30px;
}

.form-label{
    color:#F5F5F5;
    font-weight:500;
}

.form-control{
    background:rgba(255,255,255,.08);
    border:1px solid rgba(255,255,255,.15);
    color:#fff;
    border-radius:14px;
    padding:13px 15px;
    transition:.25s;
}

.form-control::placeholder{
    color:rgba(255,255,255,.45);
}

.form-control:focus{
    background:rgba(255,255,255,.12);
    color:#fff;
    border-color:rgba(255,255,255,.35);
    box-shadow:none;
}

.btn-login{
    background: rgb(49, 125, 146);
    color:#fff;
    border:none;
    border-radius:14px;
    padding:13px;
    font-weight:600;
    transition:.3s;
}

.btn-login:hover{
    background: rgb(38, 104, 123);
}

.alert{
    border:none;
    border-radius:12px;
}

.footer-text{
    text-align:center;
    color:rgba(255,255,255,.60);
    margin-top:20px;
    font-size:.9rem;
}

@keyframes fadeUp{
    from{
        opacity:0;
        transform:translateY(25px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}
    </style>

</head>

<body>


<div class="card login-card">

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
                   <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
  <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
  <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
</svg>
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
</svg>
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
    class="btn btn-login w-100">

    <i class="fa-solid fa-right-to-bracket me-2"></i>
    Ingresar

</button>
        </form>


        <div class="footer-text">

            © {{ date('Y') }} Bachillerato Tecnológico Interamericano

        </div>


    </div>


</div>


</body>
</html>