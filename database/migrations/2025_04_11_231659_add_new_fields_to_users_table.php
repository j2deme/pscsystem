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
        Schema::table('users', function (Blueprint $table) {
            $table->date('fecha_ingreso')->nullable();
            $table->string('punto')->nullable();
            $table->string('rol')->nullable();
            $table->string('estatus')->nullable();
            $table->string('empresa')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('fecha_ingreso');
            $table->dropColumn('punto');
            $table->dropColumn('rol');
            $table->dropColumn('estatus');
            $table->dropColumn('empresa');
        });
    }
};
