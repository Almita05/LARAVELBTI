<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AlumnoController extends Controller
{
     public function index()
    {
        return view('alumnos.index');
    }

   public function lista(Request $request)
{
    $url = config('services.api.base_url') . '/alumnos';

    $response = Http::get($url, [
        'page' => $request->page ?? 1,
        'limit' => $request->limit ?? 5,
        'search' => $request->search ?? ''
    ]);

    if ($response->failed()) {
    return response()->json([
        'data' => [],
        'total' => 0,
        'page' => 1,
        'total_pages' => 1
    ]);
}

    return $response->json();
}


public function modalAlta()
{
    $url = config('services.api.base_url') . '/grupos';

    $response = Http::get($url); 

    if ($response->failed()) {
        $grupos = [];
    } else {
        $grupos = $response->json()['data']; 
    }

    return view('alumnos.modalAlta', compact('grupos'));
}



public function store(Request $request)
{
    $url = config('services.api.base_url') . '/alumnos';

    $response = Http::post($url, [
        "nombre" => $request->nombre,
        "apPaterno" => $request->apPaterno,
        "apMaterno" => $request->apMaterno,
        "fechaNacimiento" => $request->fechaNacimiento,
        "tutor" => $request->tutor,
        "parentesco" => $request->parentesco,
        "calle" => $request->calle,
        "colonia" => $request->colonia,
        "localidad" => $request->localidad,
        "municipio" => $request->municipio,
        "telefonoTutor" => $request->telefonoTutor,
        "celularAlumno" => $request->celularAlumno,
        "correoAlumno" => $request->correoAlumno,
        "escuelaProcedencia" => $request->escuelaProcedencia,
        "observaciones" => $request->observaciones,
        "id_Generacion" => $request->id_Generacion,
        "id_Grupo" => $request->id_Grupo
    ]);

    if ($response->failed()) {
        return response()->json([
            'success' => false,
            'message' => 'Error al guardar alumno'
        ], 500);
    }

    return response()->json([
        'success' => true,
        'data' => $response->json()
    ]);
}
}
