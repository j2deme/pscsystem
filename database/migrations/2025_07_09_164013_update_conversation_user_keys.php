<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateConversationUserKeys extends Migration
{
    public function up()
    {
        Schema::table('conversation_user', function (Blueprint $table) {
            $table->unsignedBigInteger('api_user_id')->nullable();

            // Agregamos la FK a api_user_id
            $table->foreign('api_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('conversation_user', function (Blueprint $table) {
            $table->dropForeign(['api_user_id']);
            $table->dropColumn('api_user_id');
        });
    }

}
