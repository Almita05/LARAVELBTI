<?php

namespace App\Repositories;

use App\Services\GoogleSheetsService;

class SchoolRepository
{
    protected GoogleSheetsService $googleSheets;

    public function __construct(GoogleSheetsService $googleSheets)
    {
        $this->googleSheets = $googleSheets;
    }

    public function materias()
    {
        return $this->googleSheets->getSchoolRows('MATERIAS');
    }

    public function alumnos()
    {
        return $this->googleSheets->getSchoolRows('ALUMNOS');
    }

    public function calificaciones()
    {
        return $this->googleSheets->getSchoolRows('CALIFICACIONES');
    }

    public function boletas()
    {
        return $this->googleSheets->getSchoolRows('BOLETAS');
    }

    public function obtenerMateriasPorUsuario(string $usuario, string $rol)
{
    $materias = $this->materias();

    if (strtoupper($rol) === 'ADMIN') {
        return $materias;
    }

    return array_values(array_filter($materias, function ($materia) use ($usuario) {
        return strtolower(trim($materia['USUARIO'])) === strtolower(trim($usuario));
    }));
}

public function obtenerAlumnos(string $grado, string $grupo, string $materiaId)
{
    $alumnos = $this->alumnos();
    $calificaciones = $this->calificaciones();

    $resultado = [];

    foreach ($alumnos as $alumno) {

        if (
            trim($alumno['GRADO']) != trim($grado) ||
            trim($alumno['GRUPO']) != trim($grupo)
        ) {
            continue;
        }

        $calificacion = null;

        foreach ($calificaciones as $calif) {

            if (
                trim($calif['ID_ALUMNO']) == trim($alumno['ID']) &&
                trim($calif['ID_MATERIA']) == trim($materiaId)
            ) {
                $calificacion = $calif;
                break;
            }
        }

        $resultado[] = [
            'id' => $alumno['ID'],
            'nombre' => $alumno['NOMBRE'],
            'p1' => $calificacion['PARCIAL 1'] ?? '',
            'p2' => $calificacion['PARCIAL 2'] ?? '',
            'p3' => $calificacion['PARCIAL 3'] ?? '',
            'semestral' => $calificacion['SEMESTRAL'] ?? '',
            'extra' => $calificacion['EXTRAORDINARIO'] ?? '',
        ];
    }

    return $resultado;
}
}