<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string("name")->default("New Site");
            $table->string("map_icon")->default("");
            $table->string("status")->nullable(true);
            $table->text("description")->nullable(true);
            $table->text("access_note")->nullable(true);
            $table->text("comments")->nullable(true);
            $table->string("sitecode",3);
            $table->float("latitude",9,6);
            $table->float("longitude",9,6);
            $table->integer("altitude");
            $table->integer("user_id")->default(0);


            $table->string("map_visible")->default('yes');

            $table->timestamps();
        });

        \App\Site::create([
            'id' => 1,
            'name' => 'Landmark 2',
            'sitecode' => 'LMK',
            'latitude' => 49.879452,
            'longitude' => -119.460495,
            'altitude' => -1,
        ]);

        \App\Site::create([
            'id' => 2,
            'name' => 'Black Knight Mountain',
            'sitecode' => 'BKM',
            'latitude' => 49.876682,
            'longitude' => 	-119.306618,
            'altitude' => 1281,
        ]);

        \App\Site::create([
            'id' => 3,
            'name' => 'Kuipers Peak',
            'sitecode' => 'KUI',
            'latitude' => 	49.796680,
            'longitude' => -119.477493	,
            'altitude' => 652,
        ]);

        \App\Site::create([
            'id' => 4,
            'name' => 'Blue Grouse Mountain',
            'sitecode' => 'BGM',
            'latitude' => 	49.957092,
            'longitude' => -119.530533	,
            'altitude' => 1280,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sites');
    }
}
