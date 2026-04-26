<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GrupoController extends Controller
{
     public function index()
    {
        return view('grupos.index');
    }

   public function lista(Request $request)
{
    $url = config('services.api.base_url') . '/grupos';

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
    $base = config('services.api.base_url');

    // Centros
    $resCentros = Http::get($base . '/centroTrabajo');
    $centros = $resCentros->successful() ? $resCentros->json() : [];

    // Planes
    $resPlanes = Http::get($base . '/getPlanesEstudio');
    $planes = $resPlanes->successful() ? $resPlanes->json() : [];

    return view('grupos.modalAlta', compact('centros', 'planes'));
}

public function store(Request $request)
{
    $url = config('services.api.base_url') . '/grupos';

    $response = Http::post($url, [
        'clave' => $request->clave,
        'fechaCreacion' => $request->fechaCreacion,
        'fechaInicio' => $request->fechaInicio,
        'fechaFin' => $request->fechaFin,
        'id_centroTrabajo' => $request->id_centroTrabajo,
        'id_planEstudios' => $request->id_planEstudios,
        'id_tipoPeriodo' => $request->id_tipoPeriodo,
    ]);

    if ($response->failed()) {
        return response()->json([
            'message' => 'Error al guardar'
        ], 500);
    }

    return response()->json([
        'message' => 'Guardado correctamente'
    ]);
}
}
