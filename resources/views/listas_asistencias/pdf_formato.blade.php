<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Asistencia - {{ $materia }}</title>
    <style>
        /* Configuración de la página (márgenes del papel) */
        @page {
            margin: 1.5cm 1.2cm;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.3;
        }

        /* Encabezado */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .header-logo {
            width: 80px;
            text-align: left;
            vertical-align: middle;
        }

        .header-title {
            text-align: center;
            vertical-align: middle;
        }

        .header-title h1 {
            font-size: 16px;
            margin: 0 0 5px 0;
            color: #1a365d; /* Azul oscuro elegante */
            text-transform: uppercase;
        }

        .header-title h2 {
            font-size: 12px;
            margin: 0;
            color: #4a5568;
            font-weight: normal;
        }

        /* Tabla de metadatos (Asignatura, Docente, Grupo) */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
        }

        .info-table td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
        }

        .label {
            font-weight: bold;
            color: #2d3748;
            width: 15%;
        }

        .value {
            color: #4a5568;
        }

        /* Tabla de asistencia */
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .attendance-table th {
            background-color: #1a365d;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            padding: 6px;
            border: 1px solid #2d3748;
            font-size: 10px;
            text-transform: uppercase;
        }

        .attendance-table td {
            padding: 6px;
            border: 1px solid #cbd5e0;
            vertical-align: middle;
        }

        /* Alineaciones */
        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        /* Columnas de firmas/asistencias vacías para el profesor */
        .col-check {
            width: 25px; /* Columnas angostas para marcar asistencia */
        }

        /* Filas intercaladas para mejor lectura */
        .attendance-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
    </style>
</head>
<body>

    <!-- Encabezado principal -->
    <table class="header-table">
        <tr>
            <!-- Si tienes un logo, puedes ponerlo aquí, por ahora dejamos un placeholder de texto -->
            <td class="header-logo">
                <div style="font-weight: bold; font-size: 14px; color: #1a365d;">CONTROL<br>ESCOLAR</div>
            </td>
            <td class="header-title">
                <h1>Lista de Asistencia para Docentes</h1>
                <h2>Ciclo Escolar Vigente</h2>
            </td>
        </tr>
    </table>

    <!-- Tabla informativa de la clase -->
    <table class="info-table">
        <tr>
            <td class="label">Asignatura:</td>
            <td class="value" colspan="3"><strong>{{ $materia }}</strong></td>
        </tr>
        <tr>
            <td class="label">Docente:</td>
            <td class="value"><strong>{{ $docente }}</strong></td>
            <td class="label" style="width: 10%;">Grupo:</td>
            <td class="value" style="width: 25%;"><strong>{{ $grupo }}</strong></td>
        </tr>
    </table>

    <!-- Tabla con los Alumnos y los días para marcar asistencia -->
    <table class="attendance-table">
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th style="width: 75px;">No. Control</th>
                <th>Nombre del Alumno</th>
                <!-- Creamos 10 columnas en blanco para que el maestro marque la fecha y pase lista -->
                @for ($i = 1; $i <= 10; $i++)
                    <th class="col-check">{{ $i }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach($alumnos as $alumno)
                <tr>
                    <td class="text-center">{{ $alumno['num'] }}</td>
                    <td class="text-center">{{ $alumno['num_control'] }}</td>
                    <td class="text-left">{{ $alumno['nombre'] }}</td>
                    <!-- Columnas vacías para pase de lista -->
                    @for ($i = 1; $i <= 10; $i++)
                        <td></td>
                    @endfor
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
