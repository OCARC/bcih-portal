<?php namespace App\Traits;


trait LibreNMS
{


private function api_call($path ) {

    $url = env('LIBRENMS_URL');
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
    return $output;
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