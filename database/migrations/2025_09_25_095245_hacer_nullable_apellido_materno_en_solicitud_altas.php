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
            $table->string('apellido_materno')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('solicitud_altas', function (Blueprint $table) {
            $table->string('apellido_materno')->nullable(false)->change();
        });
    }
};
