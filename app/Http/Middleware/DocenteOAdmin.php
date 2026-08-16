<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DocenteOAdmin
{public function handle(Request $request, Closure $next): Response
{
    if (!session()->has('usuario_id')) {
        return redirect()->route('login');
    }

    $rol = strtoupper(trim(session('rol')));

    if (!in_array($rol, ['ADMIN', 'DOCENTE', 'PERSONAL'])) {
        abort(403, 'No tienes permisos para acceder.');
    }

    return $next($request);
}
}