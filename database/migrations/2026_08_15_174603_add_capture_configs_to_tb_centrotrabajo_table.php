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
        Schema::table('tb_centrotrabajo', function (Blueprint $table) {
            $table->boolean('captura_habilitada')->default(true)->after('idTipoPeriodo');
            $table->boolean('captura_p1')->default(true)->after('captura_habilitada');
            $table->boolean('captura_p2')->default(true)->after('captura_p1');
            $table->boolean('captura_p3')->default(true)->after('captura_p2');
            $table->boolean('captura_semestral')->default(true)->after('captura_p3');
            $table->boolean('captura_extraordinario')->default(true)->after('captura_semestral');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_centrotrabajo', function (Blueprint $table) {
            $table->dropColumn([
                'captura_habilitada',
                'captura_p1',
                'captura_p2',
                'captura_p3',
                'captura_semestral',
                'captura_extraordinario'
            ]);
        });
    }
};
