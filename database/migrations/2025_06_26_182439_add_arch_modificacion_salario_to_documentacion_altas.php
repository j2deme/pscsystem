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
        Schema::table('documentacion_altas', function (Blueprint $table) {
            $table->string('arch_modificacion_salario')->nullable()->after('arch_nss');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentacion_altas', function (Blueprint $table) {
            $table->dropColumn('arch_modificacion_salario');
        });
    }
};
