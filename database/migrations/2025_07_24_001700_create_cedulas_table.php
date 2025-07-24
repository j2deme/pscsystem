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
        Schema::create('cedulas', function (Blueprint $table) {
            $table->id();
            $table->string('ema_spyt')->nullable();
            $table->string('ema_psc')->nullable();
            $table->string('ema_montana')->nullable();
            $table->string('eva_spyt')->nullable();
            $table->string('eva_psc')->nullable();
            $table->string('eva_montana')->nullable();
            $table->date('mes_ema')->nullable();
            $table->string('periodo_eva')->nullable(); // e.g., "2025-B3"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cedulas');
    }
};
