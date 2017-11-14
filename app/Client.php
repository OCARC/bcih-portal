<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Object_;

use \App\DhcpLease;
use \App\PtpLink;
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

    
    public function strengthColor() {
if( $this->snmp_strength == -56 ) { return "rgba(034, 255, 000,1.0)"; }
if( $this->snmp_strength == -57 ) { return "rgba(034, 255, 000,1.0)"; }
if( $this->snmp_strength == -58 ) { return "rgba(056, 255, 000,1.0)"; }
if( $this->snmp_strength == -59 ) { return "rgba(056, 255, 000,1.0)"; }
if( $this->snmp_strength == -60 ) { return "rgba(090, 255, 000,1.0)"; }
if( $this->snmp_strength == -61 ) { return "rgba(090, 255, 000,1.0)"; }
if( $this->snmp_strength == -62 ) { return "rgba(124, 255, 000,1.0)"; }
if( $this->snmp_strength == -63 ) { return "rgba(124, 255, 000,1.0)"; }
if( $this->snmp_strength == -64 ) { return "rgba(158, 255, 000,1.0)"; }
if( $this->snmp_strength == -65 ) { return "rgba(158, 255, 000,1.0)"; }
if( $this->snmp_strength == -66 ) { return "rgba(192, 255, 000,1.0)"; }
if( $this->snmp_strength == -67 ) { return "rgba(192, 255, 000,1.0)"; }
if( $this->snmp_strength == -68 ) { return "rgba(226, 255, 000,1.0)"; }
if( $this->snmp_strength == -69 ) { return "rgba(226, 255, 000,1.0)"; }
if( $this->snmp_strength == -70 ) { return "rgba(255, 226, 000,1.0)"; }
if( $this->snmp_strength == -71 ) { return "rgba(255, 226, 000,1.0)"; }
if( $this->snmp_strength == -72 ) { return "rgba(255, 192, 000,1.0)"; }
if( $this->snmp_strength == -73 ) { return "rgba(255, 192, 000,1.0)"; }
if( $this->snmp_strength == -74 ) { return "rgba(255, 158, 000,1.0)"; }
if( $this->snmp_strength == -75 ) { return "rgba(255, 158, 000,1.0)"; }
if( $this->snmp_strength == -76 ) { return "rgba(255, 124, 000,1.0)"; }
if( $this->snmp_strength == -77 ) { return "rgba(255, 124, 000,1.0)"; }
if( $this->snmp_strength == -78 ) { return "rgba(255, 090, 000,1.0)"; }
if( $this->snmp_strength == -79 ) { return "rgba(255, 090, 000,1.0)"; }
if( $this->snmp_strength == -80 ) { return "rgba(255, 056, 000,1.0)"; }
if( $this->snmp_strength == -81 ) { return "rgba(255, 056, 000,1.0)"; }
if( $this->snmp_strength == -82 ) { return "rgba(255, 034, 000,1.0)"; }
if( $this->snmp_strength == -83 ) { return "rgba(255, 034, 000,1.0)"; }
if( $this->snmp_strength == -84 ) { return "rgba(255, 000, 000,1.0)"; }
if( $this->snmp_strength == -85 ) { return "rgba(255, 000, 000,1.0)"; }
if( $this->snmp_strength == -86 ) { return "rgba(255, 000, 000,1.0)"; }
if( $this->snmp_strength == -87 ) { return "rgba(255, 000, 000,1.0)"; }
if( $this->snmp_strength == -88 ) { return "rgba(255, 000, 000,1.0)"; }
if( $this->snmp_strength == -89 ) { return "rgba(255, 000, 000,1.0)"; }
if( $this->snmp_strength == -90 ) { return "rgba(255, 000, 000,1.0)"; }
    }


    public function sshQuickScan() {

        // Load management Key
        $key = \App\User::where('id',0)->first()->rsa_keys->where('publish',1)->first();


        $result = $this->executeSSH( 'manage', $key, "/interface wireless scan 0 duration=5") ;
        return $result;

    }

    public function sshFetchSpectralHistory(){


    // Load management Key
    $key = \App\User::where('id',0)->first()->rsa_keys->where('publish',1)->first();

    $result = $this->executeSSH( 'manage', $key, "/interface wireless spectral-history range=5850-5950 duration=15 number=wlan1 buckets=100") ;

    $result['data'] = str_replace("#" ,"<span style='background-color: #9b0000'>#</span>", $result['data']);

    $result['data'] = str_replace("." ,"<span style='background-color: #00009b'>#</span>", $result['data']);
    $result['data'] = str_replace("+" ,"<span style='background-color: #009b00'>#</span>", $result['data']);
    $result['data'] = str_replace("*" ,"<span style='background-color: #9b9b00'>#</span>", $result['data']);
    $result['data'] = str_replace("%" ,"<span style='background-color: #9b9b9b'>#</span>", $result['data']);
    return $result;

}

    public function sshQuickMonitor() {
            // Load management Key
        $key = \App\User::where('id',0)->first()->rsa_keys->where('publish',1)->first();



        $result = $this->executeSSH( 'manage', $key, "/interface wireless monitor numbers=wlan1 once") ;
        return $result;
    }

    public function sshFetchConfig() {


//        if ( $this->os != 'RouterOS' ) {
//            return array('status' => 'fail', 'reason' => 'command not supported for os type', 'data' => null);
//        }

        // Load management Key
        $key = \App\User::where('id',0)->first()->rsa_keys->where('publish',1)->first();



        $result = $this->executeSSH( 'manage', $key, "/export") ;
        return $result;


    }

    public function ptp_link() {
        $ptp = $this->hasOne(PtpLink::class,'ap_client_id')->first();
        if ( $ptp ) {
            return $ptp;
        }

        $ptp = $this->hasOne(PtpLink::class,'cl_client_id')->first();
        if ( $ptp ) {
            return $ptp;
        }
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

        //return $this->hasMany(Equipment::class);

//
//
//        $lease = \App\DhcpLease::where('mac_address',$this->mac_address)->first();
//        return $lease;
//


        $lease = $this->hasOne(\App\DhcpLease::class,'mac_address','mac_address');
        if ( $lease ) {
            return $lease;
        } else {
            return new \App\DhcpLease();
        }


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
