<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Bitácora de cambios por alumno (alta, cambio de grupo/estatus/modalidad, edición de perfil).
 * Se llena desde Laravel, que es quien conoce al usuario en sesión.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_alumnos_historial', function (Blueprint $table) {
            $table->id();
            $table->integer('id_alumno')->index();
            $table->string('tipo', 20);              // ALTA | GRUPO | ESTATUS | MODALIDAD | PERFIL
            $table->string('titulo', 120);
            $table->json('cambios')->nullable();     // [{ campo, antes, despues }]
            $table->string('usuario', 150)->nullable();
            $table->string('usuario_id', 50)->nullable();
            $table->dateTime('created_at')->useCurrent()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_alumnos_historial');
    }
};
