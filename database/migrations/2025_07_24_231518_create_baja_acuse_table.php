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
        Schema::create('baja_acuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solicitud_baja_id');
            $table->string('archivo'); // Ruta del PDF
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('baja_acuses');
    }
};
