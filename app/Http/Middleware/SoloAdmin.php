<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Solo cuentas ADMIN (gestión de personal, restablecer contraseñas, rutas de prueba). */
class SoloAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (strtoupper(trim((string) session('rol'))) !== 'ADMIN') {
            return Permiso::denegar($request, 'Solo un administrador puede acceder a esta sección.');
        }
        return $next($request);
    }
}
