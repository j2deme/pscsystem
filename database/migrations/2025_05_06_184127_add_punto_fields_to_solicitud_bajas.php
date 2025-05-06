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
        Schema::table('solicitud_bajas', function (Blueprint $table) {
            $table->bigInteger('descuento')->nullable();
            $table->string('archivo_baja')->nullable();
            $table->string('arch_equipo_entregado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitud_bajas', function (Blueprint $table) {
            $table->dropColumn('descuento');
            $table->dropColumn('archivo_baja');
            $table->dropColumn('arch_equipo_entregado');
        });
    }
};
