<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUniversityDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('university_departments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('university_id');
            $table->bigInteger('department_id');
            $table->unique(['university_id','department_id']);
            $table->timestamps();
            //$table->foreign('university_id')->references('id')->on('universities')->onDelete('cascade');
            //$table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('university_departments');
    }
}
