<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DocenteOAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $rol = strtoupper(session('rol'));

        if (!in_array($rol, ['DOCENTE', 'ADMINISTRADOR'])) {
            abort(403, 'No tienes permisos para acceder.');
        }

        return $next($request);
    }
}