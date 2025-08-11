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
        Schema::table('archivonominas', function (Blueprint $table) {
            $table->string('arch_nomina_spyt')->after('arch_nomina');
            $table->string('arch_nomina_montana')->after('arch_nomina_spyt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('archivonominas', function (Blueprint $table) {
            $table->dropColumn('arch_nomina_spyt');
            $table->dropColumn('arch_nomina_montana');
        });
    }
};
