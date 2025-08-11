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
            $table->string('arch_nomina_spyt')->nullable()->change();
            $table->string('arch_nomina_montana')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('archivonominas', function (Blueprint $table) {
            $table->string('arch_nomina_spyt')->change();
            $table->string('arch_nomina_montana')->change();
        });
    }
};
