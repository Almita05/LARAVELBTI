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
    $url = config('services.api.base_url') . '/createGrupos';

    $response = Http::get($url); 

    if ($response->failed()) {
        $grupos = [];
    } else {
        $grupos = $response->json()['data']; 
    }

    return view('grupos.modalAlta', compact('grupos'));
}
}
