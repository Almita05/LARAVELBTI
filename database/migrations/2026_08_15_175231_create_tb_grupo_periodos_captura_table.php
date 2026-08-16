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
        Schema::create('tb_grupo_periodos_captura', function (Blueprint $table) {
            $table->id();
            $table->integer('id_grupo')->unique()->index();
            $table->boolean('captura_habilitada')->default(true);
            
            $table->boolean('p1_habilitado')->default(true);
            $table->date('p1_fecha_inicio')->nullable();
            $table->date('p1_fecha_fin')->nullable();

            $table->boolean('p2_habilitado')->default(true);
            $table->date('p2_fecha_inicio')->nullable();
            $table->date('p2_fecha_fin')->nullable();

            $table->boolean('p3_habilitado')->default(true);
            $table->date('p3_fecha_inicio')->nullable();
            $table->date('p3_fecha_fin')->nullable();

            $table->boolean('semestral_habilitado')->default(true);
            $table->date('semestral_fecha_inicio')->nullable();
            $table->date('semestral_fecha_fin')->nullable();

            $table->boolean('extraordinario_habilitado')->default(true);
            $table->date('extraordinario_fecha_inicio')->nullable();
            $table->date('extraordinario_fecha_fin')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_grupo_periodos_captura');
    }
};
