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
        Schema::table('solicitud_vacaciones', function (Blueprint $table) {
            $table->string('archivo_solicitud')->nullable()->after('estatus');
        });
    }

    public function down()
    {
        Schema::table('solicitud_vacaciones', function (Blueprint $table) {
            $table->dropColumn('archivo_solicitud');
        });
    }

};
