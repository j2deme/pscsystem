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
        Schema::table('solicitud_altas', function (Blueprint $table) {
            $table->string('registro_patronal')->nullable()->after('fecha_ingreso');
            $table->string('tipo_cotizacion')->nullable()->after('registro_patronal');
            $table->string('sbc_fijo')->nullable()->after('tipo_cotizacion');
            $table->string('sbc_variable')->nullable()->after('sbc_fijo');
            $table->string('sbc_topado')->nullable()->after('sbc_variable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitud_altas', function (Blueprint $table) {
            $table->dropColumn('registro_patronal');
            $table->dropColumn('tipo_cotizacion');
            $table->dropColumn('sbc_fijo');
            $table->dropColumn('sbc_variable');
            $table->dropColumn('sbc_topado');
        });
    }
};
