<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Subnet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subnets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip')->nullable(true);
            $table->string('name')->nullable(true);
            $table->string('netmask')->nullable(true);
            $table->string('src_router')->nullable(true);
            $table->string('dst_router')->nullable(true);

            $table->integer('user_id')->nullable(true);
            $table->integer('site_id')->nullable(true);

            $table->text('description')->nullable(true);


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
        //
    }
}
