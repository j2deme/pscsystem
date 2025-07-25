<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unidad_id');
            $table->date('fecha');
            $table->text('descripcion');
            $table->decimal('costo', 10, 2)->nullable();
            $table->string('responsable')->nullable();
            $table->string('tipo');
            $table->unsignedBigInteger('siniestro_id')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('unidad_id')->references('id')->on('unidades')->onDelete('cascade');
            // siniestro_id es opcional, la relación se agregará cuando el módulo esté listo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
