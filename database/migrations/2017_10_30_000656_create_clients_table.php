<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mac_address');
            $table->string('radio_name')->nullable( true);
            $table->integer('user_id')->nullable( true);
            $table->integer('site_id')->nullable( true);
            $table->integer('equipment_id')->nullable( true);

            $table->float('latitude',9,6)->nullable( true);
            $table->float('longitude',9,6)->nullable( true);
            $table->string('coordinate_source')->default( 'none');
            $table->string('coordinate_privacy')->default( 'public');
            $table->string('management_ip')->nullable(true);

            $table->string('type')->default( 'client');

            $table->integer('snmp_strength')->nullable( true);

            $table->integer('snmp_tx_rate')->nullable( true);
            $table->integer('snmp_rx_rate')->nullable( true);

            $table->string('snmp_router_os_version')->nullable( true);
            $table->string('software_updates')->default( 'manual');

            $table->integer('snmp_uptime')->nullable( true);

            $table->integer('snmp_signal_to_noise')->nullable( true);

            $table->integer('snmp_tx_strength_ch0')->nullable( true);
            $table->integer('snmp_rx_strength_ch0')->nullable( true);
            $table->integer('snmp_tx_strength_ch1')->nullable( true);
            $table->integer('snmp_rx_strength_ch1')->nullable( true);
            $table->integer('snmp_tx_strength_ch2')->nullable( true);
            $table->integer('snmp_rx_strength_ch2')->nullable( true);

            $table->string('snmp_sysDesc')->nullable( true);
            $table->string('snmp_sysName')->nullable( true);
            $table->string('snmp_sysLocation')->nullable( true);


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
        Schema::dropIfExists('clients');
    }
}
