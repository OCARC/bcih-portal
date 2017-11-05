<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Http\Controllers\StaticLeaseController;
class generateDhcp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hamwan:generateDhcp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate dhcp configuration files';

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
        $static = new StaticLeaseController();
        $static->generateFiles();




    }
}
