<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('siniestros', function (Blueprint $table) {
      $table->id();
      $table->enum('tipo_siniestro', ['vehiculo', 'personal']);
      $table->unsignedBigInteger('unidad_id')->nullable(); // Solo para vehiculos
      $table->date('fecha');
      $table->string('tipo'); // accidente, incidencia, ataque, etc.
      $table->string('zona');
      $table->text('descripcion');
      $table->text('seguimiento')->nullable(); // Solo para vehiculos
      $table->decimal('costo', 15, 2)->nullable(); // Solo para vehiculos
      $table->timestamps();

      $table->foreign('unidad_id')->references('id')->on('unidades')->onDelete('set null');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('siniestros');
  }
};
