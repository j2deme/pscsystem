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
        Schema::create('subpuntos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('punto_id');
            $table->string('nombre');
            $table->integer('codigo')->nullable();
            $table->timestamps();

            $table->foreign('punto_id')->references('id')->on('puntos')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subpuntos');
    }
};
