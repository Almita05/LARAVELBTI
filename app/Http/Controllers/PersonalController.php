<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class PersonalController extends Controller
{
    private function checkAdmin()
    {
        if (strtoupper(session('rol')) !== 'ADMIN') {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }
    }

    public function index()
    {
        $this->checkAdmin();
        return view('personal.index');
    }

    public function lista()
    {
        $this->checkAdmin();
        $url = config('services.api.base_url') . '/personal';
        $response = Http::get($url);

        if ($response->failed()) {
            return response()->json([
                'data' => []
            ]);
        }

        return $response->json();
    }

    public function store(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'nombre' => 'required|string|max:255',
            'usuario' => 'required|string|max:100',
            'password' => 'required|string|min:4',
            'rol' => 'required|string|max:100',
            'permisos_modulos' => 'nullable|array'
        ]);

        $modulosList = '';
        if ($request->has('permisos_modulos') && is_array($request->permisos_modulos)) {
            $modulosList = implode(',', $request->permisos_modulos);
        }

        $payload = [
            'nombre' => $request->nombre,
            'usuario' => trim($request->usuario),
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'permisos_modulos' => $modulosList,
            'status' => 'ACTIVO'
        ];

        $url = config('services.api.base_url') . '/personal';
        $response = Http::post($url, $payload);

        if ($response->failed()) {
            $resData = $response->json();
            return response()->json([
                'success' => false,
                'message' => $resData['error'] ?? 'Error al registrar el personal'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $response->json()
        ]);
    }

    public function show($id)
    {
        $this->checkAdmin();
        $url = config('services.api.base_url') . '/personal/' . $id;
        $response = Http::get($url);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los detalles del personal'
            ], 500);
        }

        return $response->json();
    }

    public function update(Request $request, $id)
    {
        $this->checkAdmin();
        $request->validate([
            'nombre' => 'required|string|max:255',
            'usuario' => 'required|string|max:100',
            'rol' => 'required|string|max:100',
            'status' => 'required|string|max:20',
            'permisos_modulos' => 'nullable|array',
            'password' => 'nullable|string|min:4'
        ]);

        $modulosList = '';
        if ($request->has('permisos_modulos') && is_array($request->permisos_modulos)) {
            $modulosList = implode(',', $request->permisos_modulos);
        }

        $payload = [
            'nombre' => $request->nombre,
            'usuario' => trim($request->usuario),
            'rol' => $request->rol,
            'permisos_modulos' => $modulosList,
            'status' => $request->status
        ];

        if ($request->filled('password')) {
            $payload['password'] = Hash::make($request->password);
        }

        $url = config('services.api.base_url') . '/personal/' . $id;
        $response = Http::put($url, $payload);

        if ($response->failed()) {
            $resData = $response->json();
            return response()->json([
                'success' => false,
                'message' => $resData['error'] ?? 'Error al actualizar el personal'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $response->json()
        ]);
    }

    public function destroy($id)
    {
        $this->checkAdmin();
        $url = config('services.api.base_url') . '/personal/' . $id;
        $response = Http::delete($url);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la cuenta de personal'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cuenta de personal eliminada correctamente'
        ]);
    }
}
