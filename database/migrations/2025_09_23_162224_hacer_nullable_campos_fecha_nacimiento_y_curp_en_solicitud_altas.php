<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('solicitud_altas', function (Blueprint $table) {
            $table->date('fecha_nacimiento')->nullable()->change();
            $table->string('curp')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('solicitud_altas', function (Blueprint $table) {
            $table->date('fecha_nacimiento')->nullable(false)->change();
            $table->string('curp')->nullable(false)->change();
        });
    }
};
