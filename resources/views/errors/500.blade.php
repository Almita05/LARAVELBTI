<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dificultades Técnicas - BTI</title>
    <!-- Cargamos fuentes para que se vea premium -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2d3748;
            padding: 20px;
        }
        .error-container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            max-width: 550px;
            width: 100%;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }
        .icon-container {
            width: 80px;
            height: 80px;
            background-color: #ebf8ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px auto;
        }
        .icon-container svg {
            width: 45px;
            height: 45px;
            color: #3182ce; /* Azul BTI */
        }
        h1 {
            font-size: 24px;
            color: #1a365d;
            margin-bottom: 15px;
            font-weight: 700;
        }
        p {
            font-size: 16px;
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .btn-home {
            display: inline-block;
            background-color: #3182ce;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(49, 130, 206, 0.2);
        }
        .btn-home:hover {
            background-color: #2b6cb0;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(49, 130, 206, 0.3);
        }
        .footer-text {
            margin-top: 35px;
            font-size: 12px;
            color: #a0aec0;
        }
    </style>
</head>
<body>

    <div class="error-container">
        <!-- Icono de soporte/herramientas o servidor -->
        <div class="icon-container">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A1.5 1.5 0 1019.5 18.75l-5.83-5.83M11.42 15.17l2.49-2.49M11.42 15.17L4.83 8.58a3 3 0 114.24-4.24l6.58 6.58m0 0l-2.49 2.49m2.49-2.49L21 14.25a1.5 1.5 0 01-2.12 2.12l-4.63-4.63z" />
            </svg>
        </div>

        <h1>Servicio Temporalmente Fuera de Servicio</h1>
        
        <p>
            Lo sentimos, estamos experimentando algunas dificultades técnicas de conexión con nuestros servidores. 
            El equipo técnico ya está trabajando para solucionarlo lo antes posible.
        </p>

        <!-- Botón para regresar al Home del sistema -->
        <a href="/home" class="btn-home">
            Regresar al Inicio
        </a>

        <div class="footer-text">
            Bachillerato Tecnológico Interamericano &copy; {{ date('Y') }}
        </div>
    </div>

</body>
</html>
