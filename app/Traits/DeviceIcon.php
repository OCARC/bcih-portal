<?php namespace App\Traits;

use \phpseclib\Crypt\RSA;
use \phpseclib\File\ANSI;
use \phpseclib\Net\SSH2;
trait DeviceIcon
{
    public function icon() {

        $model = ( $this->radio_model != "" ) ? $this->radio_model : $this->snmp_sysDesc;

        $model = str_replace("RouterOS ", "" ,$model);

        $model = preg_replace("/^EdgeOS.+/i", "edgeos" , $model);

        if ( $this->os == "DAEnetIP4") {

                return url("/images/device-icons/Denkovi-200x200.png");

        }

        if ( $this->os == "Proxmox") {

            return url("/images/device-icons/proxmox.svg");

        }


        if ( $this->os == "Cisco IOS") {

            return url("/images/device-icons/cisco.svg");

        }

        if ( file_exists( public_path("/images/device-icons/" . $model. ".svg"))) {
            return url("/images/device-icons/" .  $model . ".svg");

        }
        if ( file_exists( public_path("/images/device-icons/" . $model. "-200x200.png"))) {
            return url("/images/device-icons/" .  $model . "-200x200.png");

        }


            return url("/images/device-icons/unknown-200x200.png");

    }
}