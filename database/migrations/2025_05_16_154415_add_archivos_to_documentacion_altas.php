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
        Schema::table('documentacion_altas', function (Blueprint $table) {
            $table->string('arch_contrato')->nullable()->after('arch_foto');
            $table->string('arch_solicitud_empleo')->nullable()->after('arch_contrato');
            $table->string('arch_antidoping')->nullable()->after('arch_solicitud_empleo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentacion_altas', function (Blueprint $table) {
            $table->dropColumn('arch_contrato');
            $table->dropColumn('arch_solicitud_empleo');
            $table->dropColumn('arch_antidoping');

        });
    }
};
