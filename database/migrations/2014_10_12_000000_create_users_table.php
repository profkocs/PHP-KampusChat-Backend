<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('department_id');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('fullname');
            $table->string('gender');
            $table->timestamp('date_of_birth');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('bio')->nullable();
            $table->longText('profile_photo_url')->nullable();
            $table->timestamps();
           // $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
