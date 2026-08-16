<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_docente_permisos_captura', function (Blueprint $table) {
            $table->id();
            $table->integer('id_docente');
            $table->integer('id_grupo');
            $table->integer('id_materia');
            $table->date('fecha_limite')->nullable();
            $table->boolean('permitir_modificar_pasados')->default(false);
            $table->boolean('habilitado')->default(true);
            $table->timestamps();

            // Index for performance optimization
            $table->index(['id_docente', 'id_grupo', 'id_materia'], 'idx_docente_grupo_materia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_docente_permisos_captura');
    }
};
