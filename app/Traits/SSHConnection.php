<?php namespace App\Traits;

use \phpseclib\Crypt\RSA;
use \phpseclib\File\ANSI;
use \phpseclib\Net\SSH2;
trait SSHConnection
{

    public function executeSSH($user, $key, $command, $stripANSI = false)
    {
        $rsa = new RSA();
        $rsa->loadKey($key->private_key);
        $ansi = new ANSI();
        $ansi->setDimensions(200, 200);
        // Open SSH

        try {
            if ($this->management_ip) {
                $ssh = new SSH2($this->management_ip);
            } else {
                $ssh = new SSH2($this->dhcp_lease()->ip);
            }
            $ssh->setTimeout(30);

            $ssh->encryption_algorithms_server_to_client = array('none');
            $ssh->encryption_algorithms_client_to_server = array('none');

            if (!$ssh->login($user, $rsa)) {
                return array('status' => 'failed', 'reason' => 'connection error', 'data' => null);

            } else {

                $ansi->appendString($ssh->exec($command));

                $text = $ansi->getHistory();

                if ($stripANSI == true) {
                    $text = htmlspecialchars_decode(strip_tags($text));
                }
                return array('status' => 'complete', 'reason' => null, 'data' => $text);
            }
        } catch (\ErrorException $e) {
            return array('status' => 'failed', 'reason' => $e->getMessage(), 'data' => null);

        }
    }




}