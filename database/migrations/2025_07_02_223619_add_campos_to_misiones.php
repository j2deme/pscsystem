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
        Schema::table('misiones', function (Blueprint $table) {
            $table->string('armados')->nullable()->after('tipo_vehiculos');
            $table->json('datos_hotel')->nullable()->after('armados');
            $table->json('datos_aeropuerto')->nullable()->after('datos_hotel');
            $table->json('datos_vuelo')->nullable()->after('datos_aeropuerto');
            $table->json('datos_hospital')->nullable()->after('datos_vuelo');
            $table->json('datos_embajada')->nullable()->after('datos_hospital');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('misiones', function (Blueprint $table) {
            $table->dropColumn('armados');
            $table->dropColumn('datos_hotel');
            $table->dropColumn('datos_aeropuerto');
            $table->dropColumn('datos_vuelo');
            $table->dropColumn('datos_hospital');
            $table->dropColumn('datos_embajada');
        });
    }
};
