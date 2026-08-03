<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HorariosController extends Controller
{
    public function index()
    {
        return view('horarios.index');
    }

    public function escolarizado()
    {
        return view('horarios.escolarizado');
    }

    public function sabado()
    {
        return view('horarios.sabado');
    }

    public function domingo()
    {
        return view('horarios.domingo');
    }

    public function getHorariosGrupo($id_grupo)
    {
        $url = config('services.api.base_url') . '/getHorariosGrupo/' . $id_grupo;
        $response = Http::get($url);

        if ($response->failed()) {
            return response()->json([], $response->status());
        }

        return response()->json($response->json());
    }

    public function store(Request $request)
    {
        $request->merge(json_decode($request->getContent(), true));

        $url = config('services.api.base_url') . '/createHorarioGrupo';

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->post($url, [
            'id_grupo' => $request->input('id_grupo'),
            'id_materia' => $request->input('id_materia'),
            'id_docente' => $request->input('id_docente'),
            'diaSemana' => $request->input('diaSemana'),
            'horaInicio' => $request->input('horaInicio'),
            'horaFin' => $request->input('horaFin'),
        ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el horario en el backend',
                'error' => $response->body()
            ], $response->status());
        }

        return response()->json([
            'success' => true,
            'data' => $response->json()
        ]);
    }

    public function destroy($id_horario)
    {
        $url = config('services.api.base_url') . '/deleteHorarioGrupo/' . $id_horario;
        $response = Http::delete($url);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el horario en el backend',
                'error' => $response->body()
            ], $response->status());
        }

        return response()->json([
            'success' => true,
            'data' => $response->json()
        ]);
    }
}
