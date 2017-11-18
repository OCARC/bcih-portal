<?php namespace App\Traits;

use \phpseclib\Crypt\RSA;
use \phpseclib\File\ANSI;
use \phpseclib\Net\SSH2;
trait DeviceIcon
{
    public function icon() {

        $model = ( $this->radio_model != "" ) ? str_replace("RouterOS ", "" ,$this->radio_model) : str_replace("RouterOS ", "" ,$this->snmp_sysDesc);


            if ( file_exists( public_path("/images/device-icons/" . $model. "-200x200.png"))) {
                return url("/images/device-icons/" .  $model . "-200x200.png");

            }


            return url("/images/device-icons/unknown-200x200.png");

    }
}