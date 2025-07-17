<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('placas', function (Blueprint $table) {
      $table->id();
      $table->foreignId('unidad_id')->constrained('unidades')->onDelete('cascade');
      $table->string('numero')->unique();
      $table->date('fecha_asignacion');
      $table->date('fecha_baja')->nullable();
      $table->string('estado')->default('vigente');
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('placas');
  }
};
