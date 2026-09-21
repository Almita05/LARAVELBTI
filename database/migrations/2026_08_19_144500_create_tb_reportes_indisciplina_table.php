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
        Schema::create('tb_reportes_indisciplina', function (Blueprint $table) {
            $table->id();
            $table->string('folio')->unique();
            $table->integer('id_alumno')->index();
            $table->string('alumno_nombre');
            $table->string('tutor_nombre')->nullable();
            $table->date('fecha');
            $table->text('incidente');
            $table->integer('parcial'); // 1, 2, o 3
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_reportes_indisciplina');
    }
};
