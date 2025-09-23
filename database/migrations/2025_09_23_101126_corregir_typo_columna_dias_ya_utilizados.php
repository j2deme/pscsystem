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
        DB::statement("
            ALTER TABLE solicitud_vacaciones
            CHANGE COLUMN dias_ya_utlizados dias_ya_utilizados INT NOT NULL DEFAULT 0
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE solicitud_vacaciones
            CHANGE COLUMN dias_ya_utilizados dias_ya_utlizados INT NOT NULL
        ");
    }
};
