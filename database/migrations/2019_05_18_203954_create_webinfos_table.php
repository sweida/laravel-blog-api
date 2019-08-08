<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebinfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webinfos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('keyword')->nullable();
            $table->text('description')->nullable();
            $table->text('personinfo')->nullable();
            $table->string('github')->nullable();
            $table->string('icp')->nullable();
            $table->string('weixin')->nullable();
            $table->string('zhifubao')->nullable();
            $table->string('qq')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->date('startTime')->nullable();
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
        Schema::dropIfExists('webinfos');
    }
}
