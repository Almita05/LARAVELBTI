<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AsistenciaDocenteController extends Controller
{
    public function index()
    {
        return view('asistencias_docentes.index');
    }

    public function getHorasDocentes(Request $request)
    {
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin = $request->query('fecha_fin');

        $url = config('services.api.base_url') . '/horasDocentes?fecha_inicio=' . $fechaInicio . '&fecha_fin=' . $fechaFin;
        $response = Http::get($url);

        if ($response->failed()) {
            return response()->json([], $response->status());
        }

        return response()->json($response->json());
    }

    public function getDetalleHorasDocente(Request $request)
    {
        $fecha = $request->query('fecha');
        $idDocente = $request->query('id_docente');

        $url = config('services.api.base_url') . '/detalleHorasDocente?fecha=' . $fecha . '&id_docente=' . $idDocente;
        $response = Http::get($url);

        if ($response->failed()) {
            return response()->json([], $response->status());
        }

        return response()->json($response->json());
    }

    // Asistencia de un docente en un día: llegada y salida en cada clase según el biométrico
    public function getDetalleAsistenciaDocente(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date_format:Y-m-d',
            'id_docente' => 'required|integer',
        ]);

        $response = Http::get(config('services.api.base_url') . '/asistencias/detalle_docente', [
            'fecha' => $request->query('fecha'),
            'id_docente' => $request->query('id_docente'),
        ]);

        if ($response->failed()) {
            return response()->json(
                $response->json() ?? ['error' => 'No se pudo consultar el detalle de asistencia'],
                $response->status()
            );
        }

        return response()->json($response->json());
    }

    public function getAsistencias(Request $request)
    {
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin = $request->query('fecha_fin');
        $idDocente = $request->query('id_docente');

        $url = config('services.api.base_url') . '/asistencias?fecha_inicio=' . $fechaInicio . '&fecha_fin=' . $fechaFin;
        if ($idDocente) {
            $url .= '&id_docente=' . $idDocente;
        }

        $response = Http::get($url);

        if ($response->failed()) {
            return response()->json([], $response->status());
        }

        return response()->json($response->json());
    }

    public function uploadBiometrico(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No se recibió ningún archivo'], 400);
        }

        // Solo Excel del biométrico y de tamaño razonable (la API también valida la extensión)
        $archivo = $request->file('file');
        $ext = strtolower($archivo->getClientOriginalExtension());
        if (!$archivo->isValid() || !in_array($ext, ['xlsx', 'xls'], true)) {
            return response()->json(['error' => 'El archivo debe ser un Excel (.xlsx o .xls).'], 422);
        }
        if ($archivo->getSize() > 20 * 1024 * 1024) {
            return response()->json(['error' => 'El archivo no puede superar los 20 MB.'], 422);
        }

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $filePath = $file->getPathname();

        $url = config('services.api.base_url') . '/asistencias/upload';

        $response = Http::attach(
            'file',
            file_get_contents($filePath),
            $originalName
        )->post($url);

        if ($response->failed()) {
            return response()->json(
                $response->json() ?? ['error' => 'Error al subir el archivo al backend'],
                $response->status()
            );
        }

        return response()->json($response->json());
    }

    public function clearAsistencias()
    {
        $url = config('services.api.base_url') . '/clear_asistencias';
        $response = Http::delete($url);

        if ($response->failed()) {
            return response()->json(
                $response->json() ?? ['error' => 'Error al limpiar las asistencias en el backend'],
                $response->status()
            );
        }

        return response()->json($response->json());
    }
}
