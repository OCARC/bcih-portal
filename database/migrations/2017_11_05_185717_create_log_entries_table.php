<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->nullable(true);
            $table->integer('equipment_id')->nullable(true);
            $table->integer('user_id')->nullable(true);
            $table->integer('client_id')->nullable(true);

            $table->integer('actor_site_id')->nullable(true);
            $table->integer('actor_equipment_id')->nullable(true);
            $table->integer('actor_user_id')->nullable(true);
            $table->integer('actor_client_id')->nullable(true);
            
            $table->string('event_type')->nullable(true);
            $table->integer('event_level')->nullable(true);
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
        Schema::dropIfExists('log_entries');
    }
}
