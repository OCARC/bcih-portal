<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\EquipmentController;
use App\Equipment;

class pollEquipment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hamwan:pollEquipment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Poll equipment for stats and clients';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $equipment = Equipment::all();
        foreach( $equipment as $e) {
            print "Start Polling " . $e->hostname . " (" . $e->management_ip . ")\n";


            print "\t Retrieving SNMP Data...\t";
            $result =      $e->pollSNMP();
            print $result['status'] . "\n";


            if ($e->discover_clients ==1 && $e->os == 'RouterOS') {
                print "\t Discovering Clients...\t";
                $result = $e->discoverClients();
                print $result['status'] . "\n";

            }

            if ($e->dhcp_server == 1 && $e->os == 'RouterOS') {
                print "\t Polling DHCP Leases...\t";
                $result = $e->pollDHCP();
                print $result['status'] . "\n";

            }

            print "\n\n";
        }

    }
}
