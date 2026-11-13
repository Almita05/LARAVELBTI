<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Bloquea a las cuentas DOCENTE en operaciones administrativas
 * (altas/bajas/ediciones de alumnos, grupos, materias, docentes, horarios, etc.).
 * Las pantallas propias del docente (horario, pendientes, captura, asistencia) no lo usan.
 */
class SinDocente
{
    public function handle(Request $request, Closure $next): Response
    {
        if (strtoupper(trim((string) session('rol'))) === 'DOCENTE') {
            return Permiso::denegar($request, 'Esta acción no está disponible para cuentas de docente.');
        }
        return $next($request);
    }
}
