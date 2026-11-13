<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Usuarios administrativos del sistema (antes en Google Sheets "Usuarios").
 *  - Renombra la tabla por defecto de Laravel `users` → `tb_users` (convención tb_ del proyecto).
 *  - `usuario`: nombre de acceso del formulario de login (no es un correo).
 *  - `rol`: admin | coordinador (por defecto coordinador).
 *  - `activo`: equivalente a la columna "Estado" de la hoja.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users') && !Schema::hasTable('tb_users')) {
            Schema::rename('users', 'tb_users');
        }

        Schema::table('tb_users', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_users', 'usuario')) {
                $table->string('usuario', 100)->unique()->after('name');
            }
            if (!Schema::hasColumn('tb_users', 'rol')) {
                $table->string('rol', 30)->default('coordinador')->after('password');
            }
            if (!Schema::hasColumn('tb_users', 'activo')) {
                $table->boolean('activo')->default(true)->after('rol');
            }
            if (!Schema::hasColumn('tb_users', 'ultimo_acceso')) {
                $table->timestamp('ultimo_acceso')->nullable()->after('activo');
            }
            // El acceso es por `usuario`; el correo pasa a ser opcional
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tb_users', function (Blueprint $table) {
            $table->dropUnique(['usuario']);
            $table->dropColumn(['usuario', 'rol', 'activo', 'ultimo_acceso']);
        });
        Schema::rename('tb_users', 'users');
    }
};
