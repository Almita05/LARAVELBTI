<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/** Métricas del plantel (BTI / BGNE) para Administración. */
class MetricasController extends Controller
{
    public function index()
    {
        return view('metricas.index');
    }

    public function datos(Request $request)
    {
        $params = array_filter([
            'id_centro_trabajo' => in_array((int) $request->query('id_centro_trabajo'), [2, 3], true) ? (int) $request->query('id_centro_trabajo') : 2,
            'id_grupo' => ctype_digit((string) $request->query('id_grupo')) ? $request->query('id_grupo') : null,
            'periodo' => in_array($request->query('periodo'), ['actual', '4s', '12s', 'personalizado'], true) ? $request->query('periodo') : 'actual',
            'desde' => preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $request->query('desde')) ? $request->query('desde') : null,
            'hasta' => preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $request->query('hasta')) ? $request->query('hasta') : null,
        ], fn ($v) => $v !== null);

        try {
            $response = Http::timeout(60)->get(config('services.api.base_url') . '/metricas', $params);
            return response()->json(
                $response->json() ?? ['success' => false, 'error' => 'Respuesta inválida de la API.'],
                $response->status()
            );
        } catch (\Throwable $e) {
            \Log::error('Error al obtener métricas: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'No se pudo conectar con la API de métricas.'], 500);
        }
    }
}
