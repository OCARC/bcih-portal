<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Equipment;

class rosOSPF extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hamwan:rosOSPF';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update LibreNMS with RouterOS OSPF Data';

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
            $e->updateOSPF();
        }


    }
}
