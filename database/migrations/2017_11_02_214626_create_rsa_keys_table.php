<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRsaKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rsa_keys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->text('public_key');
            $table->text('public_ssh');
            $table->text('private_key');
            $table->string('name');
            $table->string('status')->default('active');
            $table->boolean('publish')->default(0);
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
        Schema::dropIfExists('rsa_keys');
    }
}
