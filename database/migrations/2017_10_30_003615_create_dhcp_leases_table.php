<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDhcpLeasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dhcp_leases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owner');
            $table->string('ip');
            $table->string('hostname');
            $table->string('mac_address');
            $table->string('starts');
            $table->string('ends');
            $table->string('dhcp_server');
            $table->string('mac_oui_vendor');
            $table->timestamps();
        });

        try {
            $c = new \App\Http\Controllers\DhcpLeaseController();
            $c->refresh();
        } catch ( Exception $e) {

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dhcp_leases');
    }
}
