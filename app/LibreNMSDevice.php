<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LibreNMSDevice extends LibreNMSModel
{
    protected $table = 'librenms.devices';
    protected $primaryKey = 'device_id';

    var $hostname = "";

//
//    public function __construct($hostname = "")
//    {
//        $this->hostname = $hostname;
//
//        $r = $this->api_call( "devices/" . $this->hostname . "");
//        if ( $r ) {
//            if ($r->devices[0] ) {
//                $this->attributes = $r->devices[0];
//            }
//        }
//    }

    private function api_call($path ) {

        $url = env('LIBRENMS_URL') . "/api/v0/";
        $token = env('LIBRENMS_TOKEN');

        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url . $path );

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "X-Auth-Token: $token"
        ));
        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);
        return json_decode($output);
    }


    public function getGraphs($type) {
        if ( $type == 'voltage') {
            return $this->api_call("devices/" . $this->management_ip . "/graphs/health/device_voltage/1");
        }
        if ( $type == 'temperature') {
            return $this->api_call("devices/" . $this->management_ip . "/graphs/health/device_temperature/1");
        }
    }



}
