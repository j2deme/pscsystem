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
        Schema::table('deducciones', function (Blueprint $table) {
            $table->double('monto_pendiente')->nullable()->after('monto');
            $table->string('status')->nullable()->after('monto_pendiente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deducciones', function (Blueprint $table) {
            $table->dropColumn('monto_pendiente');
            $table->dropColumn('status');
        });
    }
};
