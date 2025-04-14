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
            $table->string('visa');
            $table->string('pasaporte');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentacion_altas', function (Blueprint $table) {
            $table->dropColumn('visa');
            $table->dropColumn('pasaporte');
        });
    }
};
