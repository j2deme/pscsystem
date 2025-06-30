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
        Schema::create('misiones', function (Blueprint $table) {
            $table->id();
            $table->json('agentes_id');
            $table->string('tipo_servicio');
            $table->string('ubicacion');
            $table->string('fecha_inicio');
            $table->string('fecha_fin');
            $table->string('cliente')->nullable();
            $table->string('pasajeros')->nullable();
            $table->string('tipo_operacion')->nullable();
            $table->integer('num_vehiculos')->nullable();
            $table->json('tipo_vehiculos')->nullable(); //Marca, color, modelo, etc
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('misiones');
    }
};
