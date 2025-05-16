<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitud_altas', function (Blueprint $table) {
            $table->string('domicilio_numero')->nullable()->change();
            $table->string('domicilio_comprobante')->nullable()->after('domicilio_estado');
        });
    }

    public function down(): void
    {
        Schema::table('solicitud_altas', function (Blueprint $table) {
            $table->integer('domicilio_numero')->nullable()->change();
            $table->dropColumn('domicilio_comprobante');
        });
    }
};
