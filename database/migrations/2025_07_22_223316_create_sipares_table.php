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
        Schema::create('sipares', function (Blueprint $table) {
            $table->id();
            $table->string('pdf_spyt');
            $table->string('pdf_psc');
            $table->string('pdf_montana');
            $table->date('mes'); // El mes de la carga (por ejemplo: 2025-07-01)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sipares');
    }
};
