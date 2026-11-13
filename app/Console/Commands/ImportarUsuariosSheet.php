<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

/**
 * Migración única: copia los usuarios de la hoja "Usuarios" (Google Sheets) a tb_users vía API.
 * Conserva el hash de contraseña tal cual (nadie necesita cambiar su contraseña).
 * No sobrescribe usuarios que ya existan. Requiere la API en marcha.
 */
class ImportarUsuariosSheet extends Command
{
    protected $signature = 'usuarios:importar-sheet {--dry-run : Muestra lo que haría sin guardar nada}';

    protected $description = 'Importa los usuarios administrativos de Google Sheets ("Usuarios") a tb_users (vía API)';

    public function handle(UserRepository $repo): int
    {
        try {
            $filas = $repo->all();
        } catch (\Throwable $e) {
            $this->error('No se pudo leer la hoja "Usuarios": ' . $e->getMessage());
            return self::FAILURE;
        }

        $api = config('services.api.base_url');
        $creados = 0;
        foreach ($filas as $fila) {
            $usuario = trim((string) ($fila['Usuario'] ?? ''));
            $hash = (string) ($fila['Password'] ?? '');
            if ($usuario === '') continue;

            // Solo se aceptan contraseñas ya cifradas (bcrypt); nunca texto plano
            if (!Hash::isHashed($hash)) {
                $this->warn("· {$usuario}: la contraseña de la hoja no está cifrada; se omite.");
                continue;
            }

            $rol = strtolower(trim((string) ($fila['Rol'] ?? ''))) === 'admin' ? User::ROL_ADMIN : User::ROL_COORDINADOR;
            $activo = strcasecmp(trim((string) ($fila['Estado'] ?? '')), 'Activo') === 0;

            if ($this->option('dry-run')) {
                $existe = Http::timeout(5)->get("{$api}/usuarios/by-username/" . rawurlencode($usuario))->successful();
                $this->line(sprintf('· %s: %s (%s, %s)', $usuario, $existe ? 'ya existe, se omitiría' : 'se importaría', $rol, $activo ? 'activo' : 'inactivo'));
                continue;
            }

            $res = Http::timeout(8)->post("{$api}/usuarios", [
                'name' => trim((string) ($fila['Nombre'] ?? '')) ?: $usuario,
                'usuario' => $usuario,
                'rol' => $rol,
                'activo' => $activo,
                'password_hash' => $hash,
            ]);

            if ($res->status() === 201) {
                $creados++;
                $this->line("· {$usuario}: importado ({$rol}, " . ($activo ? 'activo' : 'inactivo') . ')');
            } else {
                $this->warn("· {$usuario}: se omite — " . ($res->json('error') ?? "HTTP {$res->status()}"));
            }
        }

        $this->info($this->option('dry-run') ? 'Simulación terminada (no se guardó nada).' : "Listo: {$creados} usuario(s) importado(s).");
        return self::SUCCESS;
    }
}
