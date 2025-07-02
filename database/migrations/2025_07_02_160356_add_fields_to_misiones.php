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
        Schema::table('misiones', function (Blueprint $table) {
            $table->string('nivel_amenaza')->after('agentes_id');
            $table->string('arch_mision')->nullable()->after('itinerarios');
            $table->string('nombre_clave')->nullable()->after('pasajeros');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('misiones', function (Blueprint $table) {
            $table->dropColumn('nivel_amenaza');
            $table->dropColumn('arch_mision');
            $table->dropColumn('nombre_clave');
        });
    }
};
