<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Asistencia - {{ $materia }}</title>
    <style>
        @page { margin: 1.5cm 1.2cm; }

        @include('pdf.partials.estilos-base')

        .col-num { width: 26px; }
        .col-control { width: 78px; }
        .col-check { width: 24px; }

        .pdf-table td.td-control {
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            font-size: 7.5pt;
            color: #334155;
            text-align: center;
            padding: 6px 4px;
        }

        .pdf-table thead th.th-nombre { text-align: left; padding-left: 8px; }
    </style>
</head>
<body>

@include('pdf.partials.encabezado', [
    'titulo' => 'Bachillerato Tecnológico Interamericano',
    'subtitulo' => 'Lista de Asistencia para Docentes',
    'badge' => 'Ciclo escolar vigente',
])

@include('pdf.partials.metadatos', ['items' => [
    'Docente' => $docente,
    'Asignatura' => $materia,
    'Grupo' => $grupo,
]])

<table class="pdf-table">
    <thead>
        <tr>
            <th class="th-primario col-num">#</th>
            <th class="th-primario col-control">No. Control</th>
            <th class="th-primario th-nombre">Nombre del alumno</th>
            @for ($i = 1; $i <= 10; $i++)
                <th class="th-secundario col-check">{{ $i }}</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach($alumnos as $alumno)
            <tr>
                <td class="td-num">{{ $alumno['num'] }}</td>
                <td class="td-control">{{ $alumno['num_control'] }}</td>
                <td class="td-nombre">{{ $alumno['nombre'] }}</td>
                @for ($i = 1; $i <= 10; $i++)
                    <td class="td-check"></td>
                @endfor
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
