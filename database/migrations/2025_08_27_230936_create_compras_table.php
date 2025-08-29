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
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unidad_id')->nullable(); // Relación con unidades, puede ser null
            $table->dateTime('fecha_hora'); // Fecha y hora combinadas
            $table->string('tipo'); // Tipo de compra/gasto (Refacción, Insumo, Servicio Menor, etc.)
            $table->text('descripcion'); // Descripción detallada
            $table->string('proveedor')->nullable(); // Proveedor o entidad
            $table->decimal('costo', 10, 2)->nullable(); // Costo con 2 decimales, puede ser null
            $table->integer('kilometraje')->nullable(); // Km del vehículo, puede ser null
            $table->boolean('garantia')->default(false); // Indica si es bajo garantía
            $table->text('notas')->nullable(); // Notas adicionales
            $table->timestamps(); // created_at y updated_at

            // Índices para mejorar el rendimiento de las consultas
            $table->index('unidad_id');
            $table->index('fecha_hora');
            $table->index('tipo');
            $table->index('proveedor');
            $table->index('garantia');

            // Relación con la tabla de unidades (ajusta el nombre de la tabla si es diferente)
            $table->foreign('unidad_id')
                ->references('id')
                ->on('unidades')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};