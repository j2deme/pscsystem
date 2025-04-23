<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('solicitud_vacaciones', function (Blueprint $table) {
            $table->string('autorizado_por')->nullable()->after('tipo');
        });
    }

    public function down(): void
    {
        Schema::table('solicitud_vacaciones', function (Blueprint $table) {
            $table->dropColumn('autorizado_por');
        });
    }
};
