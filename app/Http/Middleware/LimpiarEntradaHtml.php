<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest;

/**
 * Defensa contra XSS almacenado: quita los caracteres "<" y ">" de todo texto que llega
 * al sistema (formularios, query string y cuerpos JSON).
 *
 * Muchas vistas arman HTML en JavaScript con los datos de la API (nombres, observaciones,
 * motivos…). Si un usuario guardara "<img src=x onerror=…>" en un campo, ese código se
 * ejecutaría en el navegador de quien abra la pantalla (por ejemplo, un administrador).
 * Ningún campo del sistema necesita HTML, así que se eliminan esos caracteres en la entrada.
 *
 * Las contraseñas no se tocan (pueden contener cualquier carácter).
 */
class LimpiarEntradaHtml extends TransformsRequest
{
    /** Campos que se dejan intactos. */
    private const EXCEPTO = [
        'password',
        'password_confirmation',
        'current_password',
        'password_actual',
        'nueva_password',
        '_token',
    ];

    protected function transform($key, $value)
    {
        if (!is_string($value)) {
            return $value;
        }
        $campo = strtolower((string) last(explode('.', (string) $key)));
        if (in_array($campo, self::EXCEPTO, true) || str_contains($campo, 'password')) {
            return $value;
        }
        return self::limpiar($value);
    }

    public static function limpiar(string $valor): string
    {
        return str_replace(['<', '>'], '', $valor);
    }

    /**
     * Para controladores que vuelven a leer el cuerpo crudo con json_decode($request->getContent()).
     */
    public static function limpiarArreglo(mixed $datos): array
    {
        if (!is_array($datos)) {
            return [];
        }
        array_walk_recursive($datos, function (&$v, $k) {
            if (is_string($v) && !str_contains(strtolower((string) $k), 'password')) {
                $v = self::limpiar($v);
            }
        });
        return $datos;
    }
}
