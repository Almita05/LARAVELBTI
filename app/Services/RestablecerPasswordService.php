<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

/**
 * Regla de negocio para restablecer contraseñas de usuarios administrativos (tb_users, vía API).
 *
 * Si la cuenta destino es "admin", se exige además el correo maestro de recuperación
 * (config('auth.master_recovery_email') ← MASTER_RECOVERY_EMAIL). Si no se envía, no coincide
 * o no está configurado en el servidor, el cambio se aborta.
 *
 * Importante: este servicio NO autentica a quien hace el cambio. Quien lo llame debe
 * garantizarlo (ruta con solo.admin en la web, acceso al servidor en el comando artisan).
 */
class RestablecerPasswordService
{
    public function buscar(string $usuario): ?User
    {
        $res = Http::timeout(5)->get(config('services.api.base_url') . '/usuarios/by-username/' . rawurlencode(trim($usuario)));
        if ($res->status() === 404) return null;
        $res->throw();

        $user = (new User)->forceFill($res->json('data'));
        $user->exists = true;
        return $user;
    }

    public function restablecer(User $usuario, string $nuevaPassword, ?string $correoMaestro, string $origen): void
    {
        if ($usuario->esAdmin()) {
            $this->validarCorreoMaestro($usuario, $correoMaestro, $origen);
        }

        $res = Http::timeout(8)->put(config('services.api.base_url') . "/usuarios/{$usuario->id}/password", [
            'password' => $nuevaPassword, // la API la cifra; viaja solo entre Laravel y la API
        ]);

        if ($res->status() === 400) {
            throw ValidationException::withMessages(['password' => $res->json('error') ?? 'Contraseña inválida.']);
        }
        $res->throw();

        Log::notice('Contraseña restablecida', [
            'usuario' => $usuario->usuario,
            'rol' => $usuario->rol,
            'origen' => $origen,
        ]);
    }

    private function validarCorreoMaestro(User $usuario, ?string $correoMaestro, string $origen): void
    {
        $esperado = (string) config('auth.master_recovery_email');
        $recibido = (string) $correoMaestro;

        $normalizar = fn (string $v) => mb_strtolower(trim($v));
        $valido = $esperado !== ''
            && $recibido !== ''
            && hash_equals($normalizar($esperado), $normalizar($recibido)); // comparación en tiempo constante

        if (!$valido) {
            Log::warning('Intento de restablecer contraseña de admin rechazado', [
                'usuario' => $usuario->usuario,
                'origen' => $origen,
                'motivo' => $esperado === '' ? 'MASTER_RECOVERY_EMAIL no configurado' : 'correo maestro ausente o incorrecto',
            ]);

            throw ValidationException::withMessages([
                'correo_maestro' => $esperado === ''
                    ? 'La recuperación de cuentas de administrador no está habilitada en este servidor.'
                    : 'El correo maestro de recuperación no es válido.',
            ]);
        }
    }
}
