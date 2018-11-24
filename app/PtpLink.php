<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Client;
use Spatie\Permission\Traits\HasRoles;

class PtpLink extends Model
{
    //
    use HasRoles;
    protected $guard_name = 'web'; // or whatever guard you want to use
    protected $guarded = [];
    protected $hidden = [
        'comments'
    ];

    function ap_client() {



        $client = $this->hasOne('App\Client', 'id', 'ap_client_id');
//
        if ( $client ) {
            return $client;
        } else {
            return new \App\Client();
        }
    }
    function cl_client() {

        $client =  $this->hasOne('App\Client', 'id', 'cl_client_id');

        if ( $client ) {
            return $client;
        } else {
            return new \App\Client();
        }
    }

    function ap_equipment() {



        $equipment = $this->hasOne('App\Equipment', 'id', 'ap_equipment_id');
//
        if ( $equipment ) {
            return $equipment;
        } else {
            return new \App\Equipment();
        }
    }
    function cl_equipment() {

        $equipment =  $this->hasOne('App\Equipment', 'id', 'cl_equipment_id');

        if ( $equipment ) {
            return $equipment;
        } else {
            return new \App\Equipment();
        }
    }

    function ap_site() {
        return  $this->hasOne('App\Site', 'id', 'ap_site_id');

        $site = \App\Site::all()->find( $this->ap_site_id );
        if ( $site ) {
            return $site;
        } else {
            return new \App\Site();
        }
    }
    function cl_site() {
        return  $this->hasOne('App\Site', 'id', 'cl_site_id');


        $site = \App\Site::all()->find( $this->cl_site_id );
        if ( $site ) {
            return $site;
        } else {
            return new \App\Site();
        }
    }


    function rx_speed() {

        $eqCL = $this->hasOne('App\Equipment', 'id','cl_equipment_id')->first();
        if ( ! $eqCL ) {
            return null;
        }
        if (! $eqCL->librenms_mapping ) {
            return null;
        }
        if ( $eqCL->os != 'RouterOS' ) {
            return null;
        }

        $sensors = $eqCL->hasMany('\App\LibreNMSWirelessSensor', 'device_id', 'librenms_mapping');
        $sensor = $sensors->where('sensor_type','=','mikrotik-rx')->first();

        if ( ! $sensor ) {
            return null;
        }
        return $sensor->sensor_current;
    }

    function tx_speed() {

        $eqCL = $this->hasOne('App\Equipment', 'id','cl_equipment_id')->first();
        if ( ! $eqCL ) {
            return null;
        }
        if (! $eqCL->librenms_mapping ) {
            return null;
        }
        if ( $eqCL->os != 'RouterOS' ) {
            return null;
        }

        $sensors = $eqCL->hasMany('\App\LibreNMSWirelessSensor', 'device_id', 'librenms_mapping');
        $sensor = $sensors->where('sensor_type','=','mikrotik-tx')->first();

        if ( ! $sensor ) {
            return null;
        }
        return $sensor->sensor_current;
    }

    public function distance() {
        if ( ! $this->ap_site()->first() ) {
            return 0;
        }
        if ( ! $this->cl_site()->first() ) {
            return 0;
        }
        $latitude1 = $this->ap_site->latitude;
        $longitude1 = $this->ap_site->longitude;



        $latitude2 = $this->cl_site->latitude;
        $longitude2 = $this->cl_site->longitude;

        if ( $longitude1 == 0 || $longitude2 == 0 || $latitude1 ==0 || $latitude2 == 0 ) {
            return null;
        }

        //function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {
        $earth_radius = 6371;

        $dLat = deg2rad($latitude2 - $latitude1);
        $dLon = deg2rad($longitude2 - $longitude1);

        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * asin(sqrt($a));
        $d = $earth_radius * $c;

        return number_format($d,2);
        //}
    }
}
