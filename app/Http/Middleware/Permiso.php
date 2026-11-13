<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Valida en el SERVIDOR los permisos de la matriz de "Gestión de personal".
 * (Antes solo se ocultaban opciones del menú; cualquier URL se podía abrir a mano.)
 *
 * Uso en rutas:
 *   ->middleware('permiso:alumnos_list')                        acción según el método HTTP
 *   ->middleware('permiso:formatos_boletas_bti|formatos_kardex_bgne,ver')   cualquiera de los módulos
 *
 * Acción por método: GET/HEAD → ver · DELETE → eliminar · POST/PUT/PATCH → crear
 * (igual que los botones de las vistas: editar usa "crear", borrar usa "eliminar").
 *
 * ADMIN pasa siempre. DOCENTE no usa la matriz: sus restricciones van con el middleware "sin.docente".
 */
class Permiso
{
    public function handle(Request $request, Closure $next, string $modulos, ?string $accion = null): Response
    {
        $rol = strtoupper(trim((string) session('rol')));

        if ($rol === 'ADMIN' || $rol === 'DOCENTE') {
            return $next($request);
        }

        $accion ??= match (strtoupper($request->method())) {
            'GET', 'HEAD' => 'ver',
            'DELETE' => 'eliminar',
            default => 'crear',
        };

        foreach (explode('|', $modulos) as $modulo) {
            if (has_perm(trim($modulo), $accion)) {
                return $next($request);
            }
        }

        return self::denegar($request, 'No tienes permiso para realizar esta acción.');
    }

    public static function denegar(Request $request, string $mensaje): Response
    {
        if ($request->expectsJson() || $request->ajax() || !$request->isMethod('GET')) {
            return response()->json(['success' => false, 'message' => $mensaje], 403);
        }
        abort(403, $mensaje);
    }
}
