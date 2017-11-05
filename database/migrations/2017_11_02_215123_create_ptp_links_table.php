<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePtpLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ptp_links', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('owner_id');
            $table->integer('ap_client_id');
            $table->integer('cl_client_id');
            $table->text('comments');
            $table->string('link_color');
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
        Schema::dropIfExists('ptp_links');
    }
}
