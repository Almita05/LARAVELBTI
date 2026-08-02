<?php

namespace App\Http\Controllers;

use App\Repositories\SchoolRepository;
use Illuminate\Http\Request;
use App\Services\GoogleSheetsService;

class CalificacionesController extends Controller
{
    protected SchoolRepository $school;

    public function __construct(SchoolRepository $school)
    {
        $this->school = $school;
    }




public function materiasUsuario(Request $request)
{
    $usuario = session('usuario');
    $rol = session('rol');

    $materias = $this->school->obtenerMateriasPorUsuario($usuario, $rol);

    return response()->json($materias);
}

public function alumnos($grado, $grupo, $materia)
{
    return response()->json(
        $this->school->obtenerAlumnos($grado, $grupo, $materia)
    );
}


public function materias($grado)
{

    $usuario = session('usuario');
    $rol = session('rol');

    $materias = app(GoogleSheetsService::class)
        ->getSchoolRows('MATERIAS');

    $resultado = collect($materias)
        ->filter(function ($materia) use ($usuario, $rol, $grado) {

            // Si es administrador, mostrar todas las materias del grado
            if (strtolower(trim($rol)) === 'admin') {
                return trim($materia['GRADO']) == trim($grado);
            }

            // Si es docente, solo las materias asignadas
            return trim(strtolower($materia['USUARIO'])) == trim(strtolower($usuario))
                && trim($materia['GRADO']) == trim($grado);

        })
        ->values();

    return response()->json($resultado);
}


public function guardar(Request $request)
{

    try {

        $resultado = app(\App\Services\GoogleSheetsService::class)
            ->guardarCalificaciones(
                $request->datos,
                session('usuario')
            );


        return response()->json([
            'ok'=>true,
            'mensaje'=>$resultado
        ]);


    } catch(\Exception $e) {


        return response()->json([

            'error'=>$e->getMessage(),
            'linea'=>$e->getLine(),
            'archivo'=>$e->getFile()

        ],500);


    }

}
}