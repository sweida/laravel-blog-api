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
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->text('avatar_url')->nullable();
            $table->string('password');
            $table->integer('captcha')->nullable();
            $table->text('intro')->nullable();
            $table->string('is_admin')->nullable();
            $table->timestamps();
            // unique 唯一
            // nullable 可以为空
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
