<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('site_id');
            $table->integer('owner_id')->nullable(true);;
            $table->integer('cacti_id')->nullable(true);;
            $table->string('os')->nullable(true);
            $table->string('hostname');
            $table->string('management_ip');
            $table->integer('ant_height')->nullable(true);
            $table->integer('ant_azimuth')->nullable(true);
            $table->integer('ant_tilt')->nullable(true);


            $table->string('snmp_community')->default("hamwan");
            $table->timestamp('snmp_timestamp')->nullable( true);
            $table->integer('snmp_uptime')->nullable( true);
            $table->string('snmp_serial')->nullable( true);
            $table->string('snmp_voltage')->nullable( true);
            $table->string('snmp_temperature')->nullable( true);
            $table->timestamps();
        });

        \App\Equipment::create([
            'site_id' => 1,
            'owner_id' => 0,
            'hostname' => 'HEX1.LMK',
            'management_ip' => '10.246.1.1'
        ]);

        \App\Equipment::create([
            'site_id' => 1,
            'owner_id' => 0,
            'hostname' => 'RADIO0.LMK',
            'management_ip' => '10.246.1.2'
        ]);
        \App\Equipment::create([
            'site_id' => 1,
            'owner_id' => 0,
            'hostname' => 'RADIO120.LMK',
            'management_ip' => '10.246.1.3'
        ]);
        \App\Equipment::create([
            'site_id' => 1,
            'owner_id' => 0,
            'hostname' => 'RADIO240.LMK',
            'management_ip' => '10.246.1.4'
        ]);
        \App\Equipment::create([
            'site_id' => 1,
            'owner_id' => 0,
            'hostname' => 'LINK-KUI.LMK',
            'management_ip' => '10.246.1.5'
        ]);

        \App\Equipment::create([
            'site_id' => 1,
            'owner_id' => 0,
            'hostname' => 'LINK-BKM.LMK',
            'management_ip' => '10.246.1.6'
        ]);

        \App\Equipment::create([
            'site_id' => 2,
            'owner_id' => 0,
            'hostname' => 'HEX1.BKM',
            'management_ip' => '10.246.2.1'
        ]);


        \App\Equipment::create([
            'site_id' => 2,
            'owner_id' => 0,
            'hostname' => 'RADIO0.BKM',
            'management_ip' => '10.246.2.2'
        ]);

        \App\Equipment::create([
            'site_id' => 2,
            'owner_id' => 0,
            'hostname' => 'RADIO240.BKM',
            'management_ip' => '10.246.2.4'
        ]);

        \App\Equipment::create([
            'site_id' => 3,
            'owner_id' => 0,
            'hostname' => 'HEX1.KUI',
            'management_ip' => '10.246.3.1'
        ]);


        \App\Equipment::create([
            'site_id' => 3,
            'owner_id' => 0,
            'hostname' => 'RADIO0.KUI',
            'management_ip' => '10.246.3.2'
        ]);
        \App\Equipment::create([
            'site_id' => 3,
            'owner_id' => 0,
            'hostname' => 'RADIO120.KUI',
            'management_ip' => '10.246.3.3'
        ]);
        \App\Equipment::create([
            'site_id' => 3,
            'owner_id' => 0,
            'hostname' => 'RADIO240.KUI',
            'management_ip' => '10.246.3.4'
        ]);

        \App\Equipment::create([
            'site_id' => 3,
            'owner_id' => 0,
            'hostname' => 'LINK-LMK.KUI',
            'management_ip' => '10.246.3.5'
        ]);

        \App\Equipment::create([
            'site_id' => 3,
            'owner_id' => 0,
            'hostname' => 'LINK-BGM.KUI',
            'management_ip' => '10.246.3.7'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment');
    }
}
