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
        Schema::create('incapacidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con la tabla users
            $table->string('motivo');
            $table->string('tipo_incapacidad');
            $table->string('ramo_seguro');
            $table->integer('dias_incapacidad');
            $table->date('fecha_inicio');
            $table->string('folio')->unique(); // Folio debe ser único
            $table->string('ruta_archivo_pdf')->nullable(); // Ruta del archivo PDF en storage
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('incapacidades');
    }
};
