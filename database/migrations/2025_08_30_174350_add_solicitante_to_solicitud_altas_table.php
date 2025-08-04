<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('solicitud_altas', function (Blueprint $table) {
            $table->string('solicitante')->nullable()->after('id');
            //$table->foreign('solicitante')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('solicitud_altas', function (Blueprint $table) {
            $table->dropForeign(['solicitante']);
            $table->dropColumn('solicitante');
        });
    }
};
