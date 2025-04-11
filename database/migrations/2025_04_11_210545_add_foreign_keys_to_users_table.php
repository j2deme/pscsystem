<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'sol_alta_id')) {
                $table->unsignedBigInteger('sol_alta_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('users', 'sol_docs_id')) {
                $table->unsignedBigInteger('sol_docs_id')->nullable()->after('sol_alta_id');
            }

            if (!Schema::hasColumn('users', 'sol_alta_id')) {
                $table->foreign('sol_alta_id')
                    ->references('id')
                    ->on('solicitud_altas');
            }

            if (!Schema::hasColumn('users', 'sol_docs_id')) {
                $table->foreign('sol_docs_id')
                    ->references('id')
                    ->on('documentacion_altas');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['sol_alta_id']);
            $table->dropForeign(['sol_docs_id']);

            $table->dropColumn('sol_alta_id');
            $table->dropColumn('sol_docs_id');
        });
    }
};
