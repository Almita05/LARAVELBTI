<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DocenteController extends Controller
{
    public function index()
    {
        return view('docentes.index');
    }

    public function lista()
    {
        $url = config('services.api.base_url') . '/docentes';

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
    $url = config('services.api.base_url') . '/createDocentes';

    $payload = [
        "nombreDocente" => $request->nombreDocente,
        "apPaternoDocente" => $request->apPaternoDocente,
        "apMaternoDocente" => $request->apMaternoDocente,
        "correoDocente" => $request->correoDocente,
        "telefonoDocente" => $request->telefonoDocente,
        "statusDocente" => $request->statusDocente,
        "observacionesDocente" => $request->observacionesDocente,
        "nivelEstudios" => $request->nivelEstudios,
        "fechaNacimiento" => $request->fechaNacimiento
    ];

    $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ])->post($url, $payload);

    // 🔥 DEBUG REAL (IMPORTANTE)
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
}