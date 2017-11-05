<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Object_;

use \App\DhcpLease;
use Nelisys\Snmp;


class Client extends Model
{
    const OID_SYSLOCATION = ".1.3.6.1.2.1.1.6.0";
    const OID_SYSNAME = ".1.3.6.1.2.1.1.5.0";
    const OID_SYSDESC = ".1.3.6.1.2.1.1.1.0";
    const OID_STRENGTH = ".1.3.6.1.4.1.14988.1.1.1.1.1.4.1";
    const OID_mtxrWlStatTxRate = ".1.3.6.1.4.1.14988.1.1.1.1.1.2.1";
    const OID_mtxrWlStatRxRate = ".1.3.6.1.4.1.14988.1.1.1.1.1.3.1";

    protected $guarded = [];
    use \App\Traits\SSHConnection;

    public function sshFetchConfig() {


//        if ( $this->os != 'RouterOS' ) {
//            return array('status' => 'fail', 'reason' => 'command not supported for os type', 'data' => null);
//        }

        // Load management Key
        $key = \App\User::where('id',0)->first()->rsa_keys->where('publish',1)->first();



        $result = $this->executeSSH( 'manage', $key, "/export") ;
        return $result;


    }

    public function site() {
        return $this->belongsTo(Site::class);
    }
    public function equipment() {
        return $this->belongsTo(Equipment::class);
    }
    //

    public function distanceToAP() {
        $latitude1 = $this->latitude;
        $longitude1 = $this->longitude;


        $site = $this->site()->first();
        if ( ! $site ) {
            return null;
        }
        $latitude2 = $site->latitude;
        $longitude2 = $site->longitude;

        if ( $longitude1 == 0 || $longitude2 == 2 || $latitude1 ==0 || $latitude2 == 0 ) {
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

    public function dhcp_lease() {

       return $this::hasOne(DhcpLease::class,'mac_address','mac_address');
//        $lease = DhcpLease::where('mac_address', $this->mac_address )->first();
//
        if ( $lease == null) {
            return new DhcpLease();
        } else {
            return $lease;
        }
////        dd($lease);
        return new \stdClass();
    }

    public function getSNMP( $community = 'hamwan') {
        if ( ! $this->management_ip ) {
            $dhcp = $this->dhcp_lease();

            if (!$dhcp) {
                return "";
            }
            $ip = $dhcp->ip;
        } else {
            $ip = $this->management_ip;
        }
        $snmp = new Snmp($ip, $community, "2c" );

        $r = $snmp->walk(
                ".1.3.6.1.4.1.14988.1.1.1.2"
        );
        return $r;
    }

    public function pollSNMP( $community = 'hamwan') {

        if ( ! $this->management_ip ) {
            $dhcp = $this->dhcp_lease();

            if (!$dhcp) {
                return "";
            }
            $ip = $dhcp->ip;
        } else {
            $ip = $this->management_ip;
        }
        $snmp = new Snmp($ip, $community, "2c" );

        $r = $snmp->get(
            array(
               $this::OID_SYSLOCATION,
                $this::OID_SYSNAME,
                $this::OID_SYSDESC
//                $this::OID_TEMPERATURE,
//                $this::OID_UPTIME,
//                $this::OID_FREQUENCY,
//                $this::OID_BAND,
//                $this::OID_SERIAL,
//                $this::OID_SSID,
//                $this::OID_SNR
            )
        );

        if ( isset($r[$this::OID_SYSLOCATION]) ) {
            $this->snmp_sysLocation = $r[$this::OID_SYSLOCATION];

            $location = $r[$this::OID_SYSLOCATION];
            $location = str_replace(" ", "", $location); // Strip any spaces
            if ( strpos($location,",") ) {
                list($location_lat, $location_lon) = explode(",", $location);

                if ($this->coordinate_source == '' || $this->coordinate_source == 'none' || $this->coordinate_source == 'snmp') {
                    $this->latitude = $location_lat;
                    $this->longitude = $location_lon;
                    $this->coordinate_source == 'snmp';
                }
            }
        }
        if ( isset($r[$this::OID_SYSNAME]) ) {
            $this->snmp_sysName = $r[$this::OID_SYSNAME];
        }
        if ( isset($r[$this::OID_SYSDESC]) ) {
            $this->snmp_sysDesc = $r[$this::OID_SYSDESC];
        }

//        if ( isset($r[$this::OID_TEMPERATURE]) ) {
//            $this->snmp_temperature = (int)$r[$this::OID_TEMPERATURE]/10;
//        }
//        if ( isset($r[$this::OID_TEMPERATURE]) ) {
//            $this->snmp_uptime = $r[$this::OID_UPTIME];
//        } else {
//            $this->snmp_uptime = 0;
//        }
//        if ( $this->snmp_uptime != 0) {
//            $this->snmp_timestamp = \DB::raw('now()');
//        }
        $this->save();
        // print_r($r);
    }
}
