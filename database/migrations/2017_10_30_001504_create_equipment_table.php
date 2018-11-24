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
            $table->integer('user_id')->nullable(true);;
            $table->integer('cacti_id')->nullable(true);
            $table->string('librenms_mapping')->nullable(true);
            $table->string('os')->nullable(true);
            $table->string('hostname');
            $table->string('management_ip');
            $table->integer('ant_height')->nullable(true);
            $table->float('ant_azimuth',6,2)->nullable(true);
            $table->float('ant_tilt',6,2)->nullable(true);
            $table->float('ant_gain',6,2)->nullable(true);
            $table->string('ant_model')->nullable(true);
            $table->string("status")->nullable(true);
            $table->string("type")->nullable(true);
            $table->float("radio_power",6,2)->nullable(true);
            $table->string("radio_model")->nullable(true);
            $table->string("role")->nullable(true);

            $table->string('snmp_community')->default("hamwan");
            $table->timestamp('snmp_timestamp')->nullable( true);
            $table->integer('snmp_uptime')->nullable( true);
            $table->string('snmp_serial')->nullable( true);
            $table->string('snmp_voltage')->nullable( true);
            $table->string('snmp_temperature')->nullable( true);
            $table->string('snmp_frequency')->nullable( true);
            $table->string('snmp_ssid')->nullable( true);
            $table->string('snmp_band')->nullable( true);
            $table->string('snmp_snr')->nullable( true);

            $table->text('comments')->nullable(true);
            $table->text('description')->nullable(true);
            $table->boolean('dhcp_server')->nullable(true);;
            $table->boolean('discover_clients')->default(true);
            $table->string('bwtest_server')->nullable( true);


            $table->string('remote_serial_port')->nullable( true);

            $table->timestamps();
        });

        \App\Equipment::create([
            'site_id' => 1,
            
            'hostname' => 'HEX1.LMK',
            'management_ip' => '10.246.1.1'
        ]);

        \App\Equipment::create([
            'site_id' => 1,
            
            'hostname' => 'RADIO0.LMK',
            'management_ip' => 'radio0.lmk.if.hamwan.ca'
        ]);
        \App\Equipment::create([
            'site_id' => 1,
            
            'hostname' => 'RADIO120.LMK',
            'management_ip' => 'radio120.lmk.if.hamwan.ca'
        ]);
        \App\Equipment::create([
            'site_id' => 1,
            
            'hostname' => 'RADIO240.LMK',
            'management_ip' => 'radio240.lmk.if.hamwan.ca'
        ]);
        \App\Equipment::create([
            'site_id' => 1,
            
            'hostname' => 'LINK-KUI.LMK',
            'management_ip' => '10.246.1.5'
        ]);

        \App\Equipment::create([
            'site_id' => 1,
            
            'hostname' => 'LINK-BKM.LMK',
            'management_ip' => '10.246.1.6'
        ]);

        \App\Equipment::create([
            'site_id' => 2,
            
            'hostname' => 'HEX1.BKM',
            'management_ip' => '10.246.2.1'
        ]);


        \App\Equipment::create([
            'site_id' => 2,
            
            'hostname' => 'RADIO0.BKM',
            'management_ip' => '10.246.2.2'
        ]);

        \App\Equipment::create([
            'site_id' => 2,
            
            'hostname' => 'RADIO240.BKM',
            'management_ip' => '10.246.2.4'
        ]);

        \App\Equipment::create([
            'site_id' => 3,
            
            'hostname' => 'HEX1.KUI',
            'management_ip' => '10.246.3.1'
        ]);


        \App\Equipment::create([
            'site_id' => 3,
            
            'hostname' => 'RADIO0.KUI',
            'management_ip' => '10.246.3.2'
        ]);
        \App\Equipment::create([
            'site_id' => 3,
            
            'hostname' => 'RADIO120.KUI',
            'management_ip' => '10.246.3.3'
        ]);
        \App\Equipment::create([
            'site_id' => 3,
            
            'hostname' => 'RADIO240.KUI',
            'management_ip' => '10.246.3.4'
        ]);

        \App\Equipment::create([
            'site_id' => 3,
            
            'hostname' => 'LINK-LMK.KUI',
            'management_ip' => '10.246.3.5'
        ]);

        \App\Equipment::create([
            'site_id' => 3,
            
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
