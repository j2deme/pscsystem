<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeSolicitudBajasFieldsNullable extends Migration
{
    public function up(): void
    {
        Schema::table('documentacion_altas', function (Blueprint $table) {
            $table->string('arch_acta_nacimiento')->nullable()->change();
            $table->string('arch_curp')->nullable()->change();
            $table->string('arch_ine')->nullable()->change();
            $table->string('arch_comprobante_domicilio')->nullable()->change();
            $table->string('arch_rfc')->nullable()->change();
            $table->string('arch_comprobante_estudios')->nullable()->change();
            $table->string('arch_foto')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('solicitud_bajas', function (Blueprint $table) {
            $table->string('arch_acta_nacimiento')->nullable(false)->change();
            $table->string('arch_curp')->nullable(false)->change();
            $table->string('arch_ine')->nullable(false)->change();
            $table->string('arch_comprobante_domicilio')->nullable(false)->change();
            $table->string('arch_rfc')->nullable(false)->change();
            $table->string('arch_comprobante_estudios')->nullable(false)->change();
            $table->string('arch_foto')->nullable(false)->change();
        });
    }
}
