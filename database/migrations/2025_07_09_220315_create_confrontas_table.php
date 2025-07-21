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
        Schema::create('confrontas', function (Blueprint $table) {
            $table->id();
            $table->string('inf_psc');
            $table->string('inf_spyt');
            $table->string('inf_montana');
            $table->string('exc_psc');
            $table->string('exc_spyt');
            $table->string('exc_montana');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('confrontas');
    }
};
