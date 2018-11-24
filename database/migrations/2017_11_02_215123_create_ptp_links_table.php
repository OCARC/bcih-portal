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
            $table->string('line_style')->default('solid');
            $table->integer('owner_id')->nullable(true);
            $table->integer('ap_site_id')->nullable(true);
            $table->integer('ap_client_id')->nullable(true);
            $table->integer('ap_equipment_id')->nullable(true);
            $table->integer('cl_site_id')->nullable(true);
            $table->integer('cl_client_id')->nullable(true);
            $table->integer('cl_equipment_id')->nullable(true);
            $table->text('comments')->nullable(true);
            $table->string('link_color')->nullable(true);
            $table->string('status')->nullable(true);
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
