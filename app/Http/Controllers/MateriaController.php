<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;    

class MateriaController extends Controller
{
    public function index()
    {
        return view('materias.index');
    }

    public function lista()
    {
        $url = config('services.api.base_url') . '/materias';

        $response = Http::get($url);

        if ($response->failed()) {
            return response()->json([
                'data' => []
            ]);
        }

        return $response->json();
    }

   
public function store(Request $request)
{
    $request->merge(json_decode($request->getContent(), true));

    $url = config('services.api.base_url') . '/createMateria';

    $payload = [
        "nombreMateria" => $request->input('nombreMateria'),
        "descripcionMateria" => $request->input('descripcionMateria'),
        "estatusMateria" => $request->input('estatusMateria'),
        "clave" => $request->input('clave'),
        "idCentroTrabajo" => $request->input('idCentroTrabajo'),
        "id_nivel_academico" => $request->input('id_nivel_academico'),
        "docentes" => $request->input('docentes', [])
    ];

    $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ])->post($url, $payload);

    if ($response->failed()) {
        return response()->json([
            'success' => false,
            'status' => $response->status(),
            'error' => $response->body()
        ], 500);
    }

    return response()->json([
        'success' => true,
        'data' => $response->json()
    ]);
}

public function show($id)
{
    $url = config('services.api.base_url') . '/materias';

    $response = Http::get($url, [
        'id_materia' => $id
    ]);

    if ($response->failed()) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener los detalles de la materia'
        ], 500);
    }

    $materiaData = $response->json();
    $materia = null;
    if (isset($materiaData['data']) && count($materiaData['data']) > 0) {
        $materia = $materiaData['data'][0];
    }

    return response()->json([
        'success' => true,
        'data' => $materia
    ]);
}

public function update(Request $request, $id)
{
    $request->merge(json_decode($request->getContent(), true));

    $url = config('services.api.base_url') . '/updateMateria/' . $id;

    $payload = [
        "nombreMateria" => $request->input('nombreMateria'),
        "descripcionMateria" => $request->input('descripcionMateria'),
        "estatusMateria" => $request->input('estatusMateria'),
        "clave" => $request->input('clave'),
        "idCentroTrabajo" => $request->input('idCentroTrabajo'),
        "id_nivel_academico" => $request->input('id_nivel_academico'),
        "docentes" => $request->input('docentes', [])
    ];

    $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ])->put($url, $payload);

    if ($response->failed()) {
        return response()->json([
            'success' => false,
            'status' => $response->status(),
            'error' => $response->body()
        ], 500);
    }

    return response()->json([
        'success' => true,
        'data' => $response->json()
    ]);
}

public function destroy($id)
{
    $url = config('services.api.base_url') . '/deleteMateria/' . $id;

    $response = Http::delete($url);

    if ($response->failed()) {
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar la materia'
        ], 500);
    }

    return response()->json([
        'success' => true,
        'message' => 'Materia eliminada correctamente'
    ]);
}
}