<?php

// database/migrations/YYYY_MM_DD_HHMMSS_create_riesgo_trabajos_table.php

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
        Schema::create('riesgo_trabajos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relaciona con la tabla de usuarios
            $table->enum('tipo_riesgo', ['En el trabajo', 'En trayecto']);
            $table->text('descripcion_observaciones')->nullable();
            $table->string('ruta_archivo_pdf')->nullable(); // Para guardar la ruta del archivo PDF
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('riesgo_trabajos');
    }
};
