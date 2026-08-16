<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class GrupoController extends Controller
{
    public function index()
    {
        return view('grupos.index');
    }

    public function busqueda()
    {
        $url = config('services.api.base_url') . '/getNivelAcademico';
        $response = Http::get($url);

        if ($response->failed()) {
            $niveles = [];
        } else {
            // Convertir array asociativo a objetos stdClass para compatibilidad con la vista
            $niveles = array_map(function ($nivel) {
                return (object)$nivel;
            }, $response->json());
        }

        return view('grupos.busqueda', compact('niveles'));
    }

    public function capturaCalificaciones()
    {
        return view('grupos.captura_calificaciones');
    }

    public function getCalificacionesGrupoMateria(Request $request, $id_grupo)
    {
        $idDocente = $request->query('id_docente') ?: session('id_docente');
        $rol = session('rol');
        $idMateria = $request->id_materia;

        $url = config('services.api.base_url') . '/grupos/' . $id_grupo . '/calificaciones-materia';
        $params = [];
        if ($idMateria) {
            $params['id_materia'] = $idMateria;
        }
        if ($idDocente) {
            $params['id_docente'] = $idDocente;
        }
        if ($rol) {
            $params['rol'] = $rol;
        }

        $response = Http::get($url, $params);
        
        if ($response->failed()) {
            return response()->json($response->json(), $response->status());
        }

        return response()->json($response->json(), $response->status());
    }

    public function saveCalificacionesGrupoMateria(Request $request, $id_grupo, $id_materia)
    {
        $payload = $request->all();
        $payload['id_docente'] = session('id_docente');
        $payload['rol'] = session('rol');

        $url = config('services.api.base_url') . '/grupos/' . $id_grupo . '/calificaciones-materia/' . $id_materia;
        $response = Http::post($url, $payload);
        return response()->json($response->json(), $response->status());
    }

    /**
     * Helper to validate if a docente has grade capturing permissions.
     */
    public function checkCapturaPermission($idGrupo, $idMateria)
    {
        $idDocente = session('id_docente');
        $rol = session('rol');

        $url = config('services.api.base_url') . '/grupos/' . $idGrupo . '/check-captura-permission/' . $idMateria;
        $response = Http::get($url, [
            'id_docente' => $idDocente,
            'rol' => $rol
        ]);

        if ($response->failed()) {
            return [
                'allowed' => false,
                'reason' => 'Error de comunicación con el servidor de autenticación.'
            ];
        }

        return $response->json();
    }

    public function lista(Request $request)
    {
        $url = config('services.api.base_url') . '/grupos';

        $params = [
            'page' => $request->page ?? 1,
            'limit' => $request->limit ?? 50,
            'search' => $request->search ?? ''
        ];

        if (session('rol') === 'DOCENTE' && session()->has('id_docente')) {
            $params['id_docente'] = session('id_docente');
        }

        if ($request->filled('id_centro_trabajo')) {
            $params['id_centro_trabajo'] = $request->id_centro_trabajo;
        }
        if ($request->filled('status_grupo')) {
            $params['status_grupo'] = $request->status_grupo;
        }
        if ($request->filled('modalidad_horario')) {
            $params['modalidadHorario'] = $request->modalidad_horario;
        }
        if ($request->filled('dia')) {
            $params['dia'] = $request->dia;
        }
        if ($request->filled('id_nivel_academico')) {
            $params['idNivelAcademico'] = $request->id_nivel_academico;
        }

        $response = Http::get($url, $params);

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

        // Periodos
        $resPeriodos = Http::get($base . '/tipoPeriodo');
        $periodos = $resPeriodos->successful() ? $resPeriodos->json() : [];

        // Niveles Académicos
        $resNiveles = Http::get($base . '/getNivelAcademico');
        $niveles = $resNiveles->successful() ? $resNiveles->json() : [];

        return view('grupos.modalAlta', compact('centros', 'planes', 'periodos', 'niveles'));
    }

    public function store(Request $request)
    {
        $url = config('services.api.base_url') . '/createGrupos';
        
        $modalidad = strtoupper($request->modalidadHorario);
        $diasClase = [];
        if (str_contains($modalidad, 'SABADO') || str_contains($modalidad, 'SÁBADO')) {
            $diasClase = ['SABADO'];
        } elseif (str_contains($modalidad, 'DOMINGO')) {
            $diasClase = ['DOMINGO'];
        } else {
            $diasClase = ['LUNES-VIERNES'];
        }

        $response = Http::post($url, [
            'clave' => $request->clave,
            'fechaCreacion' => $request->fechaCreacion,
            'fechaInicio' => $request->fechaInicio,
            'fechaFin' => $request->fechaFin,
            'id_centroTrabajo' => $request->id_centroTrabajo,
            'id_planEstudios' => $request->id_planEstudios,
            'id_tipoPeriodo' => $request->id_tipoPeriodo,
            'modalidadHorario' => $request->modalidadHorario,
            'id_nivel_academico' => $request->id_nivel_academico,
            'statusGrupo' => $request->statusGrupo ?? 'ACTIVO',
            'diasClase' => $diasClase,
        ]);

        if ($response->failed()) {
            $errData = $response->json();
            $errMsg = $errData['error'] ?? 'Error al guardar el grupo en el servidor backend';
            return response()->json([
                'message' => $errMsg
            ], 500);
        }

        return response()->json([
            'message' => 'Guardado correctamente'
        ]);
    }

    public function show($id)
    {
        $url = config('services.api.base_url') . '/getGrupo/' . $id;

        $response = Http::get($url);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los detalles del grupo'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $response->json()
        ]);
    }

    public function update(Request $request, $id)
    {
        $url = config('services.api.base_url') . '/updateGrupo/' . $id;
        
        $modalidad = strtoupper($request->modalidadHorario);
        $diasClase = [];
        if (str_contains($modalidad, 'SABADO') || str_contains($modalidad, 'SÁBADO')) {
            $diasClase = ['SABADO'];
        } elseif (str_contains($modalidad, 'DOMINGO')) {
            $diasClase = ['DOMINGO'];
        } else {
            $diasClase = ['LUNES-VIERNES'];
        }

        $response = Http::put($url, [
            'clave' => $request->clave,
            'fechaCreacion' => $request->fechaCreacion,
            'fechaInicio' => $request->fechaInicio,
            'fechaFin' => $request->fechaFin,
            'id_centroTrabajo' => $request->id_centroTrabajo,
            'id_planEstudios' => $request->id_planEstudios,
            'id_tipoPeriodo' => $request->id_tipoPeriodo,
            'modalidadHorario' => $request->modalidadHorario,
            'id_nivel_academico' => $request->id_nivel_academico,
            'statusGrupo' => $request->statusGrupo ?? 'ACTIVO',
            'diasClase' => $diasClase,
        ]);

        if ($response->failed()) {
            $errData = $response->json();
            $errMsg = $errData['error'] ?? 'Error al actualizar el grupo en el servidor backend';
            return response()->json([
                'success' => false,
                'message' => $errMsg
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Grupo actualizado correctamente'
        ]);
    }

    public function destroy($id)
    {
        $url = config('services.api.base_url') . '/deleteGrupo/' . $id;
        $response = Http::delete($url);

        if ($response->failed()) {
            $err = $response->json();
            return response()->json([
                'success' => false,
                'message' => $err['error'] ?? 'Error al eliminar el grupo en el servidor backend'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Grupo eliminado correctamente'
        ]);
    }

    private function isPeriodOpen($enabled, $start, $end)
    {
        if (!$enabled) return false;
        
        $hoy = date('Y-m-d');
        if ($start && $hoy < $start) return false;
        if ($end && $hoy > $end) return false;
        
        return true;
    }
}
