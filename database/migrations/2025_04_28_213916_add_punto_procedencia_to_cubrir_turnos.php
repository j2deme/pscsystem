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
        Schema::table('cubrir_turnos', function (Blueprint $table) {
            $table->string('punto_procedencia')->nullable()->after('user_id');
            $table->unsignedBigInteger('autorizado_por')->nullable()->after('hora_fin');

            $table->foreign('autorizado_por')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cubrir_turnos', function (Blueprint $table) {
            $table->dropColumn('punto_procedencia');
            $table->dropColumn('autorizado_por');
        });
    }
};
