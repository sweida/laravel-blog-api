<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAnswerUsertable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer_usertable', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('usertable_id');
            $table->unsignedInteger('answer_id');
            $table->unsignedSmallInteger('vote');
            $table->timestamps();

            $table->foreign('usertable_id')->references('id')->on('usertables');
            $table->foreign('answer_id')->references('id')->on('answers');
            $table->unique(['usertable_id', 'answer_id', 'vote']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answer_usertable');
    }
}
