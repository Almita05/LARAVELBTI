<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

/**
 * Usuario administrador inicial (se crea vía API; requiere el backend Flask en marcha).
 *
 * Si el usuario ya existe NO se toca, para no revertir una contraseña que ya se haya
 * cambiado al volver a correr los seeders. Cambia esta contraseña inicial después del primer acceso.
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $api = config('services.api.base_url');

        if (Http::timeout(5)->get("{$api}/usuarios/by-username/administrador")->successful()) {
            $this->command?->info('El usuario "administrador" ya existía; no se modificó.');
            return;
        }

        $res = Http::timeout(8)->post("{$api}/usuarios", [
            'name' => 'Administrador',
            'usuario' => 'administrador',
            'rol' => User::ROL_ADMIN,
            'activo' => true,
            // Se envía ya cifrada con el hash de Laravel
            'password_hash' => Hash::make('ice_#20'),
        ]);

        $res->status() === 201
            ? $this->command?->info('Administrador "administrador" creado.')
            : $this->command?->error('No se pudo crear el administrador: ' . ($res->json('error') ?? "HTTP {$res->status()}"));
    }
}
