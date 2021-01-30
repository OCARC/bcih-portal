<?php namespace Traits;


trait DeviceIcon
{
    public function getIcon() {

    $os = '';
    $ant_model = '';
    $model = '';
        if ( method_exists( $this, 'getRadioModel') ) {
            $model = $this->getRadioModel();
        }



        if ( method_exists( $this, 'getAntennaModel') ) {
            $ant_model = $this->getAntennaModel();
        }

        if ( method_exists( $this, 'getOS') ) {
            $os = $this->getOS();
        }

        $model = str_replace("RouterOS ", "" ,$model);

        $model = preg_replace("/^EdgeOS.+/i", "edgeos" , $model);

        if ( $os == "DAEnetIP4") {

            return ("/img/device-icons/Denkovi-200x200.png");

        }

        if ( $os == "Proxmox") {

            return ("/img/device-icons/proxmox.svg");

        }


        if ( $os == "Cisco IOS") {

            return ("/img/device-icons/cisco.svg");

        }

        if ( file_exists( getcwd() . ("/img/device-icons/" . $model. ".svg"))) {
            return ("/img/device-icons/" .  $model . ".svg");

        }
        if ( file_exists( getcwd() . ("/img/device-icons/" . $model. "-" . $ant_model . "-200x200.png"))) {
            return ("/img/device-icons/" .  $model . "-" . $ant_model . "-200x200.png");

        }
        if ( file_exists( getcwd() . ("/img/device-icons/" . $model. "-200x200.png"))) {
            return ("/img/device-icons/" .  $model . "-200x200.png");

        }


        return ("/img/device-icons/unknown-200x200.png");

    }
}