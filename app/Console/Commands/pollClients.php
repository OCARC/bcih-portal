<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ClientController;
use App\Client;

class pollClients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hamwan:pollClients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Poll Clients for stats and clients';

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
        $Clients = Client::all();
        foreach( $Clients as $e) {
            print "Start Polling " . $e->snmp_sysName . " (" . $e->getManagementIP() . ")\n";


            print "\t Retrieving SNMP Data...\t";
            $result =      $e->pollSNMP();
            print $result['status'] . "\n";


        print "\t Perform Health Check...\t";
        $result =      $e->performHealthCheck();
        print $result['status'] . "\n";
        }

    }
}
