<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AlumnoController extends Controller
{
     public function index()
{
    $url = config('services.api.base_url') . '/generaciones';

    $response = Http::get($url);

    if ($response->failed()) {
        $generaciones = [];
    } else {
        $generaciones = $response->json();
    }

    return view('alumnos.index', compact('generaciones'));
}

   public function lista(Request $request)
{
    $url = config('services.api.base_url') . '/alumnos';

    $response = Http::get($url, [
        'page' => $request->page ?? 1,
        'limit' => $request->limit ?? 5,
        'search' => $request->search ?? '',
        'generacion' => $request->generacion ?? ''
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
    // Grupos
    $responseGrupos = Http::get(config('services.api.base_url') . '/grupos');

    if ($responseGrupos->failed()) {
        $grupos = [];
    } else {
        $grupos = $responseGrupos->json()['data'];
    }

    // Generaciones
    $responseGeneraciones = Http::get(config('services.api.base_url') . '/generaciones');

    $generaciones = $responseGeneraciones->successful()
        ? $responseGeneraciones->json()
        : [];

    return view('alumnos.modalAlta', compact('grupos', 'generaciones'));
}


public function store(Request $request)
{
    $request->merge(json_decode($request->getContent(), true));
    \Log::info('Store request payload:', $request->all());

    $url = config('services.api.base_url') . '/crealumnos';

    $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ])->post($url, [
        "nombre" => $request->nombre,
        "apPaterno" => $request->apPaterno,
        "apMaterno" => $request->apMaterno ?: null,
        "fechaNacimiento" => $request->fechaNacimiento ?: null,
        "tutor" => $request->tutor ?: null,
        "parentesco" => $request->parentesco ?: null,
        "calle" => $request->calle ?: null,
        "colonia" => $request->colonia ?: null,
        "localidad" => $request->localidad ?: null,
        "municipio" => $request->municipio ?: null,
        "telefonoTutor" => $request->telefonoTutor ?: null,
        "celularAlumno" => $request->celularAlumno ?: null,
        "correoAlumno" => $request->correoAlumno ?: null,
        "escuelaProcedencia" => $request->escuelaProcedencia ?: null,
        "observaciones" => $request->observaciones ?: null,
        "id_Generacion" => $request->id_Generacion ? (int)$request->id_Generacion : null,
        "id_Grupo" => $request->id_Grupo ? (int)$request->id_Grupo : null,
        "equivalencia" => ($request->equivalencia === 'SI' || $request->equivalencia === 'NO') ? $request->equivalencia : null,
        "numeroControl" => $request->numeroControl ?: null,
        "statusAlumno" => $request->statusAlumno ?: 'ACTIVO',
        "folioCertificado" => $request->folioCertificado ?: null,
        "curp" => $request->curp ?: null,
        "fechaRecogioCertificado" => $request->fechaRecogioCertificado ?: null,
        "recogioCertificado" => $request->recogioCertificado ?: null
    ]);

    if ($response->failed()) {
        return response()->json([
            'success' => false,
            'message' => 'Error al guardar alumno',
            'error' => $response->body()
        ], 500);
    }

    $resData = $response->json();
    if (isset($resData['error'])) {
        return response()->json([
            'success' => false,
            'message' => 'Error en la base de datos: ' . $resData['error']
        ], 400);
    }

    return response()->json([
        'success' => true,
        'message' => 'Alumno guardado correctamente',
        'data' => $resData
    ]);
}

public function destroy($id)
{
    $url = config('services.api.base_url') . '/deleteAlumno/' . $id;

    $response = Http::delete($url);

    if ($response->failed()) {
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar alumno'
        ], 500);
    }

    return response()->json([
        'success' => true,
        'message' => 'Alumno eliminado correctamente'
    ]);
}

public function show($id)
{
    $url = config('services.api.base_url') . '/alumno/' . $id;
    $response = Http::get($url);

    if ($response->failed()) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener los detalles del alumno.'
        ], $response->status());
    }

    return response()->json([
        'success' => true,
        'data' => $response->json()['data'] ?? null
    ]);
}

public function update(Request $request, $id)
{
    $request->merge(json_decode($request->getContent(), true));
    \Log::info('Update request payload ID ' . $id . ':', $request->all());
    
    $url = config('services.api.base_url') . '/updateAlumno/' . $id;
    
    $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ])->put($url, [
        "nombre" => $request->nombre,
        "apPaterno" => $request->apPaterno,
        "apMaterno" => $request->apMaterno ?: null,
        "fechaNacimiento" => $request->fechaNacimiento ?: null,
        "tutor" => $request->tutor ?: null,
        "parentesco" => $request->parentesco ?: null,
        "calle" => $request->calle ?: null,
        "colonia" => $request->colonia ?: null,
        "localidad" => $request->localidad ?: null,
        "municipio" => $request->municipio ?: null,
        "telefonoTutor" => $request->telefonoTutor ?: null,
        "celularAlumno" => $request->celularAlumno ?: null,
        "correoAlumno" => $request->correoAlumno ?: null,
        "escuelaProcedencia" => $request->escuelaProcedencia ?: null,
        "observaciones" => $request->observaciones ?: null,
        "idGeneracion" => $request->id_Generacion ? (int)$request->id_Generacion : null,
        "idGrupo" => $request->id_Grupo ? (int)$request->id_Grupo : null,
        "equivalencia" => ($request->equivalencia === 'SI' || $request->equivalencia === 'NO') ? $request->equivalencia : null,
        "numeroControl" => $request->numeroControl ?: null,
        "statusAlumno" => $request->statusAlumno ?: 'ACTIVO',
        "folioCertificado" => $request->folioCertificado ?: null,
        "curp" => $request->curp ?: null,
        "fechaRecogioCertificado" => $request->fechaRecogioCertificado ?: null,
        "recogioCertificado" => $request->recogioCertificado ?: null
    ]);

    if ($response->failed()) {
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar alumno',
            'error' => $response->body()
        ], 500);
    }

    $resData = $response->json();
    if (isset($resData['error'])) {
        return response()->json([
            'success' => false,
            'message' => 'Error en la base de datos: ' . $resData['error']
        ], 400);
    }

    return response()->json([
        'success' => true,
        'message' => 'Alumno actualizado correctamente',
        'data' => $resData
    ]);
}

public function alumnosGrupo($id_grupo)
{
    // Fetch group details to get its clave/name
    $urlGrupo = config('services.api.base_url') . '/getGrupo/' . $id_grupo;
    $responseGrupo = Http::get($urlGrupo);
    $grupoClave = $responseGrupo->successful() ? ($responseGrupo->json()['data']['clave'] ?? '') : '';

    // Fetch generations (needed by the view select elements)
    $responseGeneraciones = Http::get(config('services.api.base_url') . '/generaciones');
    $generaciones = $responseGeneraciones->successful() ? $responseGeneraciones->json() : [];

    return view('alumnos.index', [
        'generaciones' => $generaciones,
        'grupoId' => $id_grupo,
        'grupoClave' => $grupoClave
    ]);
}

public function alumnosPorGrupo($id_grupo)
{
    $url = config('services.api.base_url') . '/alumnos_by_grupo/' . $id_grupo;
    $response = Http::get($url);

    if ($response->failed()) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener alumnos del grupo',
            'data' => []
        ]);
    }

    return response()->json([
        'success' => true,
        'data' => $response->json()['data'] ?? []
    ]);
}
}
