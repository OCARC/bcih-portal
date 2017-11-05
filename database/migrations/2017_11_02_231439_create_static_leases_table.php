<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaticLeasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('static_leases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_id')->nullable(true);
            $table->string('ip');
            $table->string('hostname');
            $table->string('mac_address');
            $table->string('dhcp_server');
            $table->timestamps();
        });

        \App\StaticLease::create([
            'owner_id' => 0,
            'hostname' => 'CAMERA1.KUI',
            'mac_address' => '0030F4EF01AA',
            'ip' => '44.125.216.11',
            'dhcp_server' => '44.125.216.12',
        ]);
        \App\StaticLease::create([
            'owner_id' => 0,
            'hostname' => 'CAMERA1.BKM',
            'mac_address' => '0030F4EF00E3',
            'ip' => '44.125.216.12',
            'dhcp_server' => '44.125.216.12',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('static_leases');
    }
}
