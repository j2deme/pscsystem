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
            $table->string('arch_acuse_imss')->nullable()->after('arch_nss');
            $table->string('arch_retencion_infonavit')->nullable()->after('arch_acuse_imss');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentacion_altas', function (Blueprint $table) {
            $table->dropColumn('arch_acuse_imss');
            $table->dropColumn('arch_retencion_infonavit');
        });
    }
};
