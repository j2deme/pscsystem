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
        Schema::table('solicitud_vacaciones', function (Blueprint $table) {
            $table -> unsignedBigInteger('supervisor_id') -> nullable() -> after('fecha_inicio');
            $table -> foreign('supervisor_id') -> references('id') -> on('users') -> onUpdate('cascade') -> onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitud_vacaciones', function (Blueprint $table) {
            $table -> dropForeign(['supervisor_id']);
            $table -> dropColumn('supervisor_id');
        });
    }
};
