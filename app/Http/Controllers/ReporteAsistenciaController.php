<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReporteAsistenciaController extends Controller
{
    public function index(Request $request)
    {
        $baseApiUrl = config('services.api.base_url');
        
        // Obtener listado de grupos activos a través de la API
        $response = Http::get($baseApiUrl . '/grupos', [
            'limit' => 1000,
            'status_grupo' => 'ACTIVO'
        ]);

        $grupos = [];
        if ($response->successful()) {
            $data = $response->json();
            $grupos = $data['data'] ?? [];
        }

        return view('alumnos.reportes_asistencias', compact('grupos'));
    }

    public function getGrupoReport(Request $request, $id_grupo)
    {
        $baseApiUrl = config('services.api.base_url');
        try {
            $response = Http::get($baseApiUrl . "/reportes/asistencias/grupo/{$id_grupo}");
            if ($response->successful()) {
                return response()->json($response->json());
            }
            return response()->json(
                $response->json() ?? ['error' => 'Error al obtener reporte del grupo'],
                $response->status()
            );
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al obtener reporte del grupo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAlumnoHistorial(Request $request, $id_grupo, $id_alumno)
    {
        $baseApiUrl = config('services.api.base_url');
        try {
            $response = Http::get($baseApiUrl . "/reportes/asistencias/grupo/{$id_grupo}/alumno/{$id_alumno}");
            if ($response->successful()) {
                return response()->json($response->json());
            }
            return response()->json(
                $response->json() ?? ['error' => 'Error al obtener historial del alumno'],
                $response->status()
            );
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al obtener historial del alumno: ' . $e->getMessage()
            ], 500);
        }
    }
}
