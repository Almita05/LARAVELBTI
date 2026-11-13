<?php

namespace App\Auth;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Proveedor de usuarios para Auth::attempt que consume la API Flask (/usuarios/*)
 * en lugar de leer tb_users directo. La contraseña se verifica en la API: el hash nunca llega a Laravel.
 *
 * El modelo App\Models\User se usa solo como contenedor de datos (no se guarda con Eloquent).
 */
class ApiUserProvider implements UserProvider
{
    private function api(string $ruta): string
    {
        return config('services.api.base_url') . $ruta;
    }

    private function aUsuario(?array $datos): ?User
    {
        if (!$datos) return null;
        $user = (new User)->forceFill($datos);
        $user->exists = true;
        return $user;
    }

    public function retrieveById($identifier): ?Authenticatable
    {
        try {
            $res = Http::timeout(5)->get($this->api('/usuarios/' . (int) $identifier));
            return $res->successful() ? $this->aUsuario($res->json('data')) : null;
        } catch (\Throwable $e) {
            Log::error('ApiUserProvider::retrieveById: ' . $e->getMessage());
            return null;
        }
    }

    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        $usuario = trim((string) ($credentials['usuario'] ?? ''));
        if ($usuario === '') return null;

        try {
            $res = Http::timeout(5)->get($this->api('/usuarios/by-username/' . rawurlencode($usuario)));
        } catch (\Throwable $e) {
            Log::error('ApiUserProvider::retrieveByCredentials: ' . $e->getMessage());
            return null;
        }
        if (!$res->successful()) return null;

        $user = $this->aUsuario($res->json('data'));
        // Condiciones extra del attempt (p. ej. 'activo' => true)
        foreach ($credentials as $campo => $valor) {
            if (in_array($campo, ['usuario', 'password'], true)) continue;
            if ((string) $user->getAttribute($campo) !== (string) (is_bool($valor) ? (int) $valor : $valor)) return null;
        }
        return $user;
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        try {
            $res = Http::timeout(8)->post($this->api('/usuarios/login'), [
                'usuario' => $user->getAttribute('usuario'),
                'password' => (string) ($credentials['password'] ?? ''),
            ]);
            return $res->successful() && (int) $res->json('data.id') === (int) $user->getAuthIdentifier();
        } catch (\Throwable $e) {
            Log::error('ApiUserProvider::validateCredentials: ' . $e->getMessage());
            return false;
        }
    }

    // Sin "recordarme": la sesión expira normalmente
    public function retrieveByToken($identifier, $token): ?Authenticatable
    {
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token): void
    {
    }

    // El cifrado lo administra la API
    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false): void
    {
    }
}
