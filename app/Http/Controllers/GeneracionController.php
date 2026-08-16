<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeneracionController extends Controller
{
    public function index()
    {
        return view('generaciones.index');
    }

    public function lista()
    {
        $url = config('services.api.base_url') . '/generaciones';
        $response = Http::get($url);

        if ($response->failed()) {
            return response()->json([], 500);
        }

        return response()->json($response->json());
    }

    public function getUltima(Request $request)
    {
        $cctId = $request->query('idCentroTrabajo');
        $url = config('services.api.base_url') . '/generaciones/ultima?idCentroTrabajo=' . $cctId;
        $response = Http::get($url);

        if ($response->failed()) {
            return response()->json(null, 500);
        }

        return response()->json($response->json());
    }

    public function store(Request $request)
    {
        $url = config('services.api.base_url') . '/createGeneraciones';
        
        $payload = [
            'idCentroTrabajo' => $request->idCentroTrabajo,
            'nombreGeneracion' => $request->nombreGeneracion,
            'mesInicio' => $request->mesInicio,
            'mesFin' => $request->mesFin,
            'anioInicio' => $request->anioInicio,
            'anioFin' => $request->anioFin,
            'generacion' => $request->generacion,
            'createBy' => session('usuario')
        ];

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->post($url, $payload);

        if ($response->failed()) {
            $err = $response->json();
            return response()->json(['success' => false, 'message' => $err['error'] ?? 'Error al guardar la generación.'], 400);
        }

        return response()->json(['success' => true, 'message' => 'Generación creada con éxito.']);
    }

    public function update(Request $request, $id)
    {
        $url = config('services.api.base_url') . '/generaciones/' . $id;

        $payload = [
            'idCentroTrabajo' => $request->idCentroTrabajo,
            'nombreGeneracion' => $request->nombreGeneracion,
            'mesInicio' => $request->mesInicio,
            'mesFin' => $request->mesFin,
            'anioInicio' => $request->anioInicio,
            'anioFin' => $request->anioFin,
            'generacion' => $request->generacion,
            'updateBy' => session('usuario')
        ];

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->put($url, $payload);

        if ($response->failed()) {
            $err = $response->json();
            return response()->json(['success' => false, 'message' => $err['error'] ?? 'Error al actualizar la generación.'], 400);
        }

        return response()->json(['success' => true, 'message' => 'Generación actualizada con éxito.']);
    }

    public function destroy($id)
    {
        $url = config('services.api.base_url') . '/generaciones/' . $id;
        $response = Http::delete($url);

        if ($response->failed()) {
            $err = $response->json();
            return response()->json(['success' => false, 'message' => $err['error'] ?? 'Error al eliminar la generación.'], 400);
        }

        return response()->json(['success' => true, 'message' => 'Generación eliminada con éxito.']);
    }
}
