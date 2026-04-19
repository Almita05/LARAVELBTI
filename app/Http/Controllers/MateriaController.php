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
        "docentes" => $request->input('docentes', [])
    ];

    $response = Http::post($url, $payload);

    return response()->json([
        'success' => true,
        'data' => $response->json()
    ]);
}
}