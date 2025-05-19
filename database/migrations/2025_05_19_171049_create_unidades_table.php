<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('unidades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_propietario')->nullable();
            $table->string('zona')->nullable();
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->string('placas')->nullable();
            $table->string('kms')->nullable();
            $table->string('asignacion_punto')->nullable();
            $table->string('estado_vehiculo')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidades');
    }
};
