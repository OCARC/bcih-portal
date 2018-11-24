<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ips', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip')->nullable(true);
            $table->string('mac_address')->nullable(true);
            $table->string('hostname')->nullable(true);
            $table->string('gateway')->nullable(true);
            $table->string('name')->nullable(true);
            $table->string('netmask')->nullable(true);
            $table->string('category')->nullable(true);
            $table->string("status")->nullable(true);

            $table->integer('user_id')->nullable(true);
            $table->integer('site_id')->nullable(true);
            $table->integer('equipment_id')->nullable(true);

            $table->text('description')->nullable(true);
            $table->text('comment')->nullable(true);
            $table->string('dns')->nullable(true);
            $table->string('dns_zone')->nullable(true);
            $table->string('dhcp')->nullable(true);


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
