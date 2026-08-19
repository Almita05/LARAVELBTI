<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermisosCapturaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (strtoupper(session('rol')) !== 'ADMIN') {
                    abort(403, 'Acceso denegado. Solo administradores pueden gestionar permisos.');
                }
                return $next($request);
            }),
        ];
    }

    public function index()
    {
        return view('permisos_captura.index');
    }

    public function lista()
    {
        $response = Http::get(config('services.api.base_url') . '/permisos-captura/lista');
        if ($response->failed()) {
            return response()->json(['error' => 'Error al comunicarse con la API de control escolar.'], 500);
        }
        return response()->json($response->json());
    }

    public function getDocentes()
    {
        $response = Http::get(config('services.api.base_url') . '/permisos-captura/docentes');
        if ($response->failed()) {
            return response()->json(['error' => 'Error al obtener docentes.'], 500);
        }
        return response()->json($response->json());
    }

    public function getGrupos()
    {
        $response = Http::get(config('services.api.base_url') . '/permisos-captura/grupos');
        if ($response->failed()) {
            return response()->json(['error' => 'Error al obtener grupos.'], 500);
        }
        return response()->json($response->json());
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_docente' => 'required|integer',
            'id_grupo' => 'required|integer',
            'id_materia' => 'required|integer',
            'fecha_limite' => 'nullable|date',
            'permitir_modificar_pasados' => 'required|boolean',
        ]);

        $response = Http::post(config('services.api.base_url') . '/permisos-captura', $request->all());
        if ($response->status() === 422) {
            return response()->json($response->json(), 422);
        }
        if ($response->failed()) {
            return response()->json(['error' => 'Error al guardar el permiso.'], 500);
        }
        return response()->json($response->json());
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fecha_limite' => 'nullable|date',
            'permitir_modificar_pasados' => 'required|boolean',
            'habilitado' => 'required|boolean',
        ]);

        $response = Http::put(config('services.api.base_url') . '/permisos-captura/' . $id, $request->all());
        if ($response->status() === 422) {
            return response()->json($response->json(), 422);
        }
        if ($response->failed()) {
            return response()->json(['error' => 'Error al actualizar el permiso.'], 500);
        }
        return response()->json($response->json());
    }

    public function destroy($id)
    {
        $response = Http::delete(config('services.api.base_url') . '/permisos-captura/' . $id);
        if ($response->status() === 422) {
            return response()->json($response->json(), 422);
        }
        if ($response->failed()) {
            return response()->json(['error' => 'Error al eliminar el permiso.'], 500);
        }
        return response()->json($response->json());
    }

    public function getMatrizAvance()
    {
        $response = Http::get(config('services.api.base_url') . '/permisos-captura/matriz');
        if ($response->failed()) {
            return response()->json(['error' => 'Error al generar la matriz de avance.'], 500);
        }
        return response()->json($response->json());
    }

    public function getGruposPorCct($cct_id)
    {
        $response = Http::get(config('services.api.base_url') . '/permisos-captura/cct/' . $cct_id . '/grupos');
        if ($response->failed()) {
            return response()->json(['error' => 'Error al obtener grupos por CCT.'], 500);
        }
        return response()->json($response->json());
    }

    public function getGrupoConfig($grupo_id)
    {
        $response = Http::get(config('services.api.base_url') . '/permisos-captura/grupo-config/' . $grupo_id);
        if ($response->status() === 404) {
            return response()->json($response->json(), 404);
        }
        if ($response->failed()) {
            return response()->json(['error' => 'Error al obtener configuración del grupo.'], 500);
        }
        return response()->json($response->json());
    }

    public function saveGrupoConfig(Request $request, $grupo_id)
    {
        $response = Http::post(config('services.api.base_url') . '/permisos-captura/grupo-config/' . $grupo_id, $request->all());
        if ($response->status() === 422) {
            return response()->json($response->json(), 422);
        }
        if ($response->failed()) {
            return response()->json(['error' => 'Error al guardar la configuración del grupo.'], 500);
        }
        return response()->json($response->json());
    }

    public function getCcts()
    {
        $response = Http::get(config('services.api.base_url') . '/permisos-captura/ccts');
        if ($response->failed()) {
            return response()->json(['error' => 'Error al obtener CCTs.'], 500);
        }
        return response()->json($response->json());
    }
}
