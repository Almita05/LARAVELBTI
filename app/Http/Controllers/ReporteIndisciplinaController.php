<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReporteIndisciplinaController extends Controller
{
    private function getBaseUrl()
    {
        return config('services.api.base_url');
    }

    /**
     * Display a listing of disciplinary reports via Flask API.
     */
    public function index(Request $request)
    {
        try {
            $url = $this->getBaseUrl() . '/reportes-indisciplina';
            $response = Http::timeout(15)->get($url, $request->all());

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al consultar los reportes en el servicio backend.',
                    'data' => []
                ], $response->status());
            }

            return response()->json($response->json());
        } catch (\Exception $e) {
            Log::error('Error al conectar con API de reportes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error de conexión con el backend: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created disciplinary report via Flask API.
     */
    public function store(Request $request)
    {
        try {
            $url = $this->getBaseUrl() . '/reportes-indisciplina';
            $response = Http::timeout(15)->post($url, $request->all());

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            Log::error('Error al registrar reporte en API: ' . $e->getMessage(), [
                'payload' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error de conexión con el backend: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified disciplinary report via Flask API.
     */
    public function destroy($id)
    {
        try {
            $url = $this->getBaseUrl() . "/reportes-indisciplina/{$id}";
            $response = Http::timeout(15)->delete($url);

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            Log::error('Error al eliminar reporte en API: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error de conexión con el backend: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the count of disciplinary reports for a student grouped by partial via Flask API.
     */
    public function getStudentReportsCount($id_alumno)
    {
        try {
            $url = $this->getBaseUrl() . "/reportes-indisciplina/conteo/{$id_alumno}";
            $response = Http::timeout(10)->get($url);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'counts' => [1 => 0, 2 => 0, 3 => 0]
                ]);
            }

            return response()->json($response->json());
        } catch (\Exception $e) {
            Log::error('Error al obtener conteo en API: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'counts' => [1 => 0, 2 => 0, 3 => 0]
            ], 500);
        }
    }

    /**
     * Render the view for BTI Disciplinary Reports.
     */
    public function indexView()
    {
        return view('reportes.indisciplina_bti');
    }
}
