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
            $table->boolean('approved')->default(0);
            $table->boolean('allow_password')->default(1);
            $table->rememberToken();
            $table->timestamps();
        });

//        $user = new \App\User();
//        $user->setAttribute('id', 1);
//        $user->setAttribute('name', 'Stephen');
//        $user->setAttribute('email', 'stephen@compucomp.net');
//        $user->setAttribute('password', '$2y$10$9hvaEvRTtTm1MbhOci21jeCEy3z9xZcSW288h6fByN0Swk78rzZXC');
//
//        $user->save();
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
