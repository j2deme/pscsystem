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
        Schema::create('documentacion_altas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solicitud_id');
            $table->string('arch_acta_nacimiento');
            $table->string('arch_curp');
            $table->string('arch_ine');
            $table->string('arch_comprobante_domicilio');
            $table->string('arch_rfc');
            $table->string('arch_comprobante_estudios');
            $table->string('arch_carta_rec_laboral');
            $table->string('arch_carta_rec_personal');
            $table->string('arch_cartilla_militar');
            $table->string('arch_infonavit');
            $table->string('arch_fonacot');
            $table->string('arch_licencia_conducir');
            $table->string('arch_carta_no_penales');
            $table->string('arch_foto');
            $table->timestamps();

            $table->foreign('solicitud_id')
            ->references('id')
            ->on('solicitud_altas')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentacion_altas');
    }
};
