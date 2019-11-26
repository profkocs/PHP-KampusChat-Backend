<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('owner_user_id');
            $table->bigInteger('guest_user_id');
            $table->boolean('is_owner_typing');
            $table->boolean('is_guest_typing');
            $table->timestamp('owner_deleted_at');
            $table->timestamp('guest_deleted_at');
            $table->unique(['owner_user_id','guest_user_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chats');
    }
}
