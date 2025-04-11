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
        Schema::create('solicitud_altas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido_paterno');
            $table->string('apellido_materno');
            $table->date('fecha_nacimiento');
            $table->string('curp');
            $table->string('rfc');
            $table->string('nss');
            $table->string('estado_civil');
            $table->string('domicilio_calle');
            $table->integer('domicilio_numero');
            $table->string('domicilio_colonia');
            $table->string('domicilio_ciudad');
            $table->string('domicilio_estado');
            $table->string('telefono');
            $table->string('email');
            $table->double('estatura');
            $table->double('peso');
            $table->string('status');
            $table->string('observaciones');
            $table->string('rol');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_altas');
    }
};
