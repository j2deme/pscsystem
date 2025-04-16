<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('solicitud_bajas', function (Blueprint $table) {
            $table->string('incapacidad')->nullable()->after('motivo');
            $table->date('ultima_asistencia')->nullable()->change();
            $table->string('motivo')->nullable()->change();
            $table->text('observaciones')->nullable()->change();
            $table->date('fecha_baja')->nullable()->change();

            $table->unsignedBigInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('solicitud_bajas', function (Blueprint $table) {
            $table->dropColumn('incapacidad');
            $table->date('ultima_asistencia')->nullable(false)->change();
            $table->string('motivo')->nullable(false)->change();
            $table->text('observaciones')->nullable(false)->change();
            $table->date('fecha_baja')->nullable(false)->change();

            $table->dropForeign(['user_id']);
        });
    }
};
