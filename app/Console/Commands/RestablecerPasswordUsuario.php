<?php

namespace App\Console\Commands;

use App\Services\RestablecerPasswordService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

/**
 * Recuperación de emergencia desde el servidor (p. ej. el admin olvidó su contraseña).
 * Tener acceso al servidor es la prueba de identidad; para cuentas admin además se
 * exige el correo maestro de recuperación, igual que en la web. Requiere la API en marcha.
 */
class RestablecerPasswordUsuario extends Command
{
    protected $signature = 'usuarios:restablecer-password {usuario : Nombre de usuario administrativo}';

    protected $description = 'Restablece la contraseña de un usuario administrativo (cuentas admin exigen el correo maestro)';

    public function handle(RestablecerPasswordService $servicio): int
    {
        try {
            $cuenta = $servicio->buscar($this->argument('usuario'));
        } catch (\Throwable $e) {
            $this->error('No se pudo consultar la API: ' . $e->getMessage());
            return self::FAILURE;
        }
        if (!$cuenta) {
            $this->error('No existe ese usuario.');
            return self::FAILURE;
        }

        $correo = $cuenta->esAdmin() ? $this->secret('Correo maestro de recuperación') : null;
        $password = $this->secret('Nueva contraseña');
        $confirmacion = $this->secret('Confirma la nueva contraseña');

        $v = Validator::make(
            ['password' => $password, 'password_confirmation' => $confirmacion],
            ['password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()]]
        );
        if ($v->fails()) {
            foreach ($v->errors()->all() as $error) $this->error($error);
            return self::FAILURE;
        }

        try {
            $servicio->restablecer($cuenta, $password, $correo, 'cli');
        } catch (ValidationException $e) {
            foreach ($e->errors() as $mensajes) foreach ($mensajes as $m) $this->error($m);
            return self::FAILURE;
        } catch (\Throwable $e) {
            $this->error('No se pudo guardar: ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->info("Contraseña de \"{$cuenta->usuario}\" restablecida.");
        return self::SUCCESS;
    }
}
