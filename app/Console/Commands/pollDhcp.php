<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\DhcpLeaseController;

class pollDhcp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hamwan:pollDhcp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve records from the DHCP Servers and update local database';

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
        $dhcp = new DhcpLeaseController();
        $dhcp->refresh();


    }
}
