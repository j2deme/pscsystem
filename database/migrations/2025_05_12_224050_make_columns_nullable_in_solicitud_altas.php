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
        Schema::table('solicitud_altas', function (Blueprint $table) {
            $table->string('estado_civil')->nullable()->change();
            $table->string('domicilio_calle')->nullable()->change();
            $table->integer('domicilio_numero')->nullable()->change();
            $table->string('domicilio_colonia')->nullable()->change();
            $table->string('domicilio_ciudad')->nullable()->change();
            $table->string('domicilio_estado')->nullable()->change();
            $table->string('telefono')->nullable()->change();
            $table->string('estatura')->nullable()->change();
            $table->string('peso')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitud_altas', function (Blueprint $table) {
            //
        });
    }
};
