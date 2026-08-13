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

    public function capturaCalificaciones()
    {
        return view('grupos.captura_calificaciones');
    }

    public function getCalificacionesGrupoMateria(Request $request, $id_grupo)
    {
        $url = config('services.api.base_url') . '/grupos/' . $id_grupo . '/calificaciones-materia';
        $params = [];
        if ($request->filled('id_materia')) {
            $params['id_materia'] = $request->id_materia;
        }
        $response = Http::get($url, $params);
        return response()->json($response->json(), $response->status());
    }

    public function saveCalificacionesGrupoMateria(Request $request, $id_grupo, $id_materia)
    {
        $url = config('services.api.base_url') . '/grupos/' . $id_grupo . '/calificaciones-materia/' . $id_materia;
        $response = Http::post($url, $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function lista(Request $request)
    {
        $url = config('services.api.base_url') . '/grupos';

        $params = [
            'page' => $request->page ?? 1,
            'limit' => $request->limit ?? 50,
            'search' => $request->search ?? ''
        ];

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
        if (str_contains($modalidad, 'SABADO')) {
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
        if (str_contains($modalidad, 'SABADO')) {
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
}
