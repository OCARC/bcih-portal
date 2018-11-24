<?php namespace App\Traits;


trait LibreNMS
{


    private function api_call($path)
    {

        $url = env('LIBRENMS_URL') . "/api/v0/";
        $token = env('LIBRENMS_TOKEN');

        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url . $path);
        error_log($url . $path);
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "X-Auth-Token: $token"
        ));
        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);
        error_log($output);
        return $output;
    }

    public function libreGetDeviceList()
    {
        $r = json_decode($this->api_call("devices?type=all"), true);
        if ($r['status'] == 'ok') {
            return $r['devices'];
        } else {
            return array();
        }
    }

    public function libreGetHealthList()
    {
        $r = json_decode($this->api_call("devices/" . $this->librenms_mapping . "/health"), true);
        if ($r['status'] == 'ok') {
            return $r['graphs'];
        } else {
            return array();
        }
    }

    public function libreGetGraph($type)
    {
        if ($type == 'voltage') {
            return $this->api_call("devices/" . $this->librenms_mapping . "/graphs/health/device_voltage/1?" . $_SERVER['QUERY_STRING']);
        } elseif ($type == 'temperature') {
            return $this->api_call("devices/" . $this->librenms_mapping . "/graphs/health/device_temperature/1?" . $_SERVER['QUERY_STRING']);
        } else {
            return $this->api_call("devices/" . $this->librenms_mapping . "/graphs/health/" . $type . "/1?" . $_SERVER['QUERY_STRING']);
        }

    }

    public function libre_query( $path, $raw = false ) {
        $r = $this->api_call($path);
        if ( $raw === true ) {
            return $r;
        }
        $r = json_decode($r, true);

        if ($r['status'] == 'ok') {
            return $r;
        } else {
            return array();
        }
    }




    public function libre_ports() {
        return $this->hasMany(\App\LibreNMSPort::class,'device_id', 'librenms_mapping' );


    }
    public function libre_sensor( $class ) {
        $this->librenms_mapping = $this->getLibreNMSMapping(true);

        if ( $this->librenms_mapping ) {

          //  $sensors = $this->hasMany('App\LibreNMSSensor', 'device_id','librenms_mapping')->where('sensor_class', '=', $class )->first();
          //  dump($sensors);

            $result =   $this->hasMany('App\LibreNMSSensor', 'device_id','librenms_mapping')->where('sensor_class', '=', $class )->first();
            //\App\LibreNMSSensor::all()->where('device_id', '=', $this->librenms_mapping)->where('sensor_class', '=', $class )->first();
            if ($result) {
                return $result;
            }
        }

        return new \App\LibreNMSSensor();
    }

    public function libre_wireless( $class ) {
        $this->librenms_mapping = $this->getLibreNMSMapping(true);
        if ( $this->librenms_mapping ) {

            $result = $this->hasMany('App\LibreNMSWirelessSensor', 'device_id','librenms_mapping')->where('sensor_class', '=', $class )->first();
//            $result =  \App\LibreNMSWirelessSensor::all()->where('device_id', '=', $this->librenms_mapping)->where('sensor_class', '=', $class )->first();
            if ($result) {
                return $result;
            }
        }

        return new \App\LibreNMSWirelessSensor();
    }

    public function libre_device() {
        $this->librenms_mapping = $this->getLibreNMSMapping(true);
        if ( $this->librenms_mapping ) {
            $site = $this->belongsTo(\App\LibreNMSDevice::class, 'librenms_mapping', 'device_id');
            return $site;
        } else {
            return new Collection();
        }

    }

    public function libre_alerts($state=1) {
//        $site = $this->belongsTo(\App\LibreNMSAlert::class,'librenms_mapping', 'device_id' )->where('state','=', $state);

        $alerts = \App\LibreNMSAlert::where('device_id', $this->getLibreNMSMapping(false) )->where('state','=', $state);
        return $alerts;

    }

}