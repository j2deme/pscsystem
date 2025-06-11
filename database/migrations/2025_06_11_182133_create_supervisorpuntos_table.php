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
        Schema::create('supervisorpuntos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subpunto_id');
            $table->unsignedBigInteger('supervisor_id');

            $table->foreign('subpunto_id')->references('id')->on('subpuntos')->onDelete('cascade');
            $table->foreign('supervisor_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervisorpuntos');
    }
};
