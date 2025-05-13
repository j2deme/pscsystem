<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitud_altas', function (Blueprint $table) {
            $table->string('rol')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('solicitud_altas', function (Blueprint $table) {
            $table->string('rol')->nullable(false)->change();
        });
    }
};
