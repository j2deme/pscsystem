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
    if (!Schema::hasTable('turno')) {
      Schema::create('turno', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('User_id');
        $table->string('Nombre_elemento');
        $table->enum('Tipo', ['Entrada', 'Salida']);
        $table->time('Hora_inicio')->nullable();
        $table->time('Hora_final')->nullable();
        $table->decimal('Km_inicio', 10, 2)->nullable();
        $table->decimal('Km_final', 10, 2)->nullable();
        $table->string('Punto');
        $table->string('Placas_unidad');
        $table->decimal('Rayas_gasolina_inicio', 10, 2)->nullable();
        $table->decimal('Rayas_gasolina_final', 10, 2)->nullable();
        $table->string('Evidencia_inicio')->nullable();
        $table->string('Evidencia_final')->nullable();
        $table->rememberToken();
        $table->timestamps();

        $table->foreign('User_id')->references('id')->on('users')->onDelete('cascade');
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    // Sólo eliminar si la tabla esta vacía
    if (Schema::hasTable('turno') && DB::table('turno')->count() === 0) {
      Schema::dropIfExists('turno');
    }
  }
};
