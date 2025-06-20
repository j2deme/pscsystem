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
        Schema::create('finiquitos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('baja_id');
            $table->double('monto');
            $table->timestamps();

            $table->foreign('baja_id')->references('id')->on('solicitud_bajas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finiquitos');
    }
};
