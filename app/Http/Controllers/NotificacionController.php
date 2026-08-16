<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NotificacionController extends Controller
{
    public function index()
    {
        $url = config('services.api.base_url') . '/notificaciones';
        
        try {
            $response = Http::get($url);
            
            $notificaciones = [
                'documentos' => [],
                'equivalencias' => [],
                'grupos' => [],
                'totales' => [
                    'documentos' => 0,
                    'equivalencias' => 0,
                    'grupos' => 0,
                    'total' => 0
                ]
            ];

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['success']) && $data['success'] && isset($data['data'])) {
                    $notificaciones = $data['data'];
                }
            }
        } catch (\Exception $e) {
            $notificaciones = [
                'documentos' => [],
                'equivalencias' => [],
                'grupos' => [],
                'totales' => ['documentos' => 0, 'equivalencias' => 0, 'grupos' => 0, 'total' => 0],
                'error' => 'No se pudo establecer conexión con el servicio de notificaciones.'
            ];
        }

        return view('notificaciones.index', compact('notificaciones'));
    }

    public function count()
    {
        $url = config('services.api.base_url') . '/notificaciones';
        try {
            $response = Http::get($url);
            if ($response->successful()) {
                $data = $response->json();
                $total = $data['data']['totales']['total'] ?? 0;
                return response()->json(['total' => $total]);
            }
        } catch (\Exception $e) {}
        return response()->json(['total' => 0]);
    }
}
