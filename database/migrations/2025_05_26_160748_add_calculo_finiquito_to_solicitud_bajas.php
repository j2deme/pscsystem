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
            $table->string('calculo_finiquito')->nullable()->after('arch_renuncia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitud_bajas', function (Blueprint $table) {
            $table->dropColumn('calculo_finiquito');
        });
    }
};
