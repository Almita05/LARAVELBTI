<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cabeceras HTTP de seguridad para todas las respuestas.
 *  - X-Frame-Options: impide que otro sitio meta la plataforma en un iframe (clickjacking).
 *  - X-Content-Type-Options: el navegador no "adivina" tipos (evita ejecutar archivos como script).
 *  - Referrer-Policy: no envía URLs internas completas a sitios externos.
 *  - Permissions-Policy: desactiva cámara, micrófono y ubicación.
 *  - Strict-Transport-Security: solo en HTTPS; obliga al navegador a usar siempre HTTPS.
 */
class CabecerasSeguridad
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $h = $response->headers;
        $h->set('X-Frame-Options', 'SAMEORIGIN');
        $h->set('X-Content-Type-Options', 'nosniff');
        $h->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $h->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $h->set('Cross-Origin-Opener-Policy', 'same-origin');
        if ($request->isSecure()) {
            $h->set('Strict-Transport-Security', 'max-age=31536000');
        }
        $h->remove('X-Powered-By');
        header_remove('X-Powered-By');

        return $response;
    }
}
