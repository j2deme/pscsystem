<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitud_altas', function (Blueprint $table) {
            $table->string('fonacot')->nullable()->after('nss');//Fonacot
            $table->string('reingreso')->nullable()->after('fonacot');
            $table->string('entra_por')->nullable()->after('reingreso');
            $table->double('sueldo_mensual')->nullable()->after('email');//Sueldo mensual
            $table->double('sd')->nullable()->after('sueldo_mensual');//Sueldo diario
            $table->double('sdi')->nullable()->after('sd');//Sueldo diario Integrado
            $table->double('fdi')->nullable()->after('sdi');//Factor de Integración
            $table->string('modificacion_salario')->nullable()->after('fdi');//Modificación salario
            $table->string('cuota_fija')->nullable()->after('modificacion_salario');
            $table->string('factor_descuento')->nullable()->after('cuota_fija');
        });
    }

    public function down(): void
    {
        Schema::table('solicitud_altas', function (Blueprint $table) {
            $table->dropColumn('fonacot');
            $table->dropColumn('reingreso');
            $table->dropColumn('entra_por');
            $table->dropColumn('sueldo_mensual');
            $table->dropColumn('sd');
            $table->dropColumn('sdi');
            $table->dropColumn('fdi');
            $table->dropColumn('modificacion_salario');
            $table->dropColumn('cuota_fija');
            $table->dropColumn('factor_descuento');
        });
    }
};
