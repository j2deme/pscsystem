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
        $table->string('arch_carta_rec_laboral')->nullable()->change();
        $table->string('arch_carta_rec_personal')->nullable()->change();
        $table->string('arch_cartilla_militar')->nullable()->change();
        $table->string('arch_infonavit')->nullable()->change();
        $table->string('arch_fonacot')->nullable()->change();
        $table->string('arch_licencia_conducir')->nullable()->change();
        $table->string('arch_carta_no_penales')->nullable()->change();
        $table->string('visa')->nullable()->change();
        $table->string('pasaporte')->nullable()->change();
    });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentacion_altas', function (Blueprint $table) {
            //
        });
    }
};
