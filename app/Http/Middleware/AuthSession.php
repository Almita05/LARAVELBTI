<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthSession
{
    /** Cada cuántos segundos se vuelve a consultar en la API el estado y permisos de la cuenta. */
    private const REVALIDAR_CADA = 60;

    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('usuario_id')) {
            return $this->sinSesion($request, 'Tu sesión ha expirado. Por favor inicia sesión de nuevo.');
        }

        // Si la cuenta se desactivó (o le cambiaron permisos) mientras tenía la sesión abierta
        if ($motivo = $this->revalidarCuenta()) {
            Auth::logout();
            $request->session()->flush();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return $this->sinSesion($request, $motivo);
        }

        $response = $next($request);

        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        return $response;
    }

    private function sinSesion(Request $request, string $mensaje): Response
    {
        if ($request->expectsJson() || $request->is('*/calificaciones*') || $request->ajax()) {
            return response()->json(['success' => false, 'message' => $mensaje], 401);
        }
        return redirect()->route('login')->with('error', $mensaje);
    }

    /**
     * Devuelve un mensaje si la sesión debe cerrarse; null si sigue vigente.
     * Personal: refresca permisos y estado. Usuarios administrativos: refresca rol y estado.
     * Si la API no responde, no se cierra la sesión (se reintenta en la siguiente petición).
     */
    private function revalidarCuenta(): ?string
    {
        $origen = session('origen');
        if (!in_array($origen, ['personal', 'usuarios'], true)) {
            return null;
        }
        if (time() - (int) session('acceso_verificado_en', 0) < self::REVALIDAR_CADA) {
            return null;
        }

        $api = config('services.api.base_url');
        $id = (int) session('usuario_id');

        try {
            if ($origen === 'personal') {
                $res = Http::timeout(4)->get("{$api}/personal/{$id}/acceso");
                if ($res->status() === 404) return 'Tu cuenta ya no existe.';
                if (!$res->successful()) return null;
                if (($res->json('data.status') ?? '') !== 'ACTIVO') return 'Tu cuenta fue desactivada.';
                session(['modulos' => (string) $res->json('data.permisos_modulos'), 'rol_nombre' => $res->json('data.rol')]);
            } else {
                $res = Http::timeout(4)->get("{$api}/usuarios/{$id}");
                if ($res->status() === 404) return 'Tu cuenta ya no existe.';
                if (!$res->successful()) return null;
                if (!$res->json('data.activo')) return 'Tu cuenta fue desactivada.';
                $rol = (string) $res->json('data.rol');
                session(['rol' => $rol === 'admin' ? 'ADMIN' : strtoupper($rol)]);
            }
        } catch (\Throwable $e) {
            Log::warning("No se pudo revalidar la sesión ({$origen} #{$id}): " . $e->getMessage());
            return null;
        }

        session(['acceso_verificado_en' => time()]);
        return null;
    }
}
