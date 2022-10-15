<?php

namespace App\Console\Commands;
use App\FreqTrack;
use \phpseclib\Crypt\RSA;
use \phpseclib\File\ANSI;
use \phpseclib\Net\SSH2;
use \phpseclib\Net\SFTP;
use Illuminate\Console\Command;
use \App\Equipment;

class wifiScan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hamwan:wifiScan';

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

        $key = \App\User::where('id',0)->first()->rsa_keys->where('publish',1)->first();

        $rsa = new RSA();
        $rsa->loadKey($key->private_key);
        $ansi = new ANSI();
        $ansi->setDimensions(200, 200);
        // Open SSH

        try {

                $ssh = new SSH2("monitor.lmk.if.hamwan.ca");

            $ssh->setTimeout(600);

            $ssh->encryption_algorithms_server_to_client = array('none');
            $ssh->encryption_algorithms_client_to_server = array('none');

            if (!$ssh->login('manage', $rsa)) {
                print_r( array('status' => 'failed', 'reason' => 'connection error', 'data' => null) );

            } else {

                $ansi->appendString($ssh->exec("/interface wireless scan save-file=ScanData rounds=2 number=wlan1; /interface wireless scan save-file=ScanData rounds=2 number=wlan1"));

                $text = $ansi->getHistory();

                $text = htmlspecialchars_decode(strip_tags($text));

            }
        } catch (\ErrorException $e) {
            return array('status' => 'failed', 'reason' => $e->getMessage(), 'data' => null);

        }


        try {

            $sftp = new SFTP("monitor.lmk.if.hamwan.ca");

            $sftp->setTimeout(30);

            $sftp->encryption_algorithms_server_to_client = array('none');
            $sftp->encryption_algorithms_client_to_server = array('none');

            if (!$sftp->login('manage', $rsa)) {
                print_r( array('status' => 'failed', 'reason' => 'connection error', 'data' => null) );

            } else {

                $sftp->get('ScanData', '/hamwan/tmp/ScanData');

                readfile("/hamwan/tmp/ScanData");



            }
        } catch (\ErrorException $e) {
            return array('status' => 'failed', 'reason' => $e->getMessage(), 'data' => null);

        }

$row =0;
        if (($handle = fopen("/hamwan/tmp/ScanData", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                FreqTrack::where('mac', $data[0])->delete();

                $f = new FreqTrack();

                $f->mac = $data[0];
                $f->ssid = $data[1];
                $f->channel = $data[2];
                $f->signal = $data[3];
                $f->protocol = $data[4];

                $f->save();

            }
            fclose($handle);
        }

    }
}
