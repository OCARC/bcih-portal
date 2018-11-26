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
            $table->string('name');
            $table->string('callsign')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('realm')->default('local');
            $table->boolean('approved')->default(0);
            $table->boolean('allow_password')->default(1);
            $table->boolean('allow_advanced')->default(1);
            $table->boolean('administrator')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
