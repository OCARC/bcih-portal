<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Object_;

use \App\DhcpLease;
use \App\PtpLink;
use \App\LibreNMSIPv4Address;
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
    use \App\Traits\DeviceIcon;

    public function getHealthColor() {

        if ( $this->status == "Planning" || $this->status == "Potential" || $this->status == "No Install") {
            return "inherit";
        }

        if ( $this::getHealthStatus() == "Error" ) {
            return '#ffad2f'; // Error
        } else if ( $this::getHealthStatus() == "High Temp" ) {
            return '#ffad2f'; // Error
        }else if ( $this::getHealthStatus() == "Offline" ) {
            return '#ffaaaa';
        } else if ( $this::getHealthStatus() == "OK" ) {
            return '#aaffaa'; // OK
        } else {
            return '#cccccc'; // Unknown

        }

    }

    public function getHealthStatus() {
        if ( $this->status == "Planning" || $this->status == "Potential" || $this->status == "No Install") {
            return "-";
        }
        $status = "OK";

        if ( $this->snmp_temperature >= 60 ) {
            return "High Temp";
        }
        if ( $this->snmp_uptime == 0 ) {
            return "Offline";
        }

        return $status;
    }

    public function strengthColor( $opacity = "1.0") {
        if ( $this->snmp_strength >= -49 ) { return "rgba( 000, 255, 000, $opacity)"; }

        if ( $this->snmp_strength == -50 ) { return "rgba( 000, 255, 000, $opacity)"; }
	if ( $this->snmp_strength == -51 ) { return "rgba( 000, 255, 000, $opacity)"; }
	if ( $this->snmp_strength == -52 ) { return "rgba( 001, 255, 000, $opacity)"; }
	if ( $this->snmp_strength == -53 ) { return "rgba( 002, 255, 000, $opacity)"; }
	if ( $this->snmp_strength == -54 ) { return "rgba( 004, 255, 000, $opacity)"; }
	if ( $this->snmp_strength == -55 ) { return "rgba( 008, 255, 000, $opacity)"; }
	if ( $this->snmp_strength == -56 ) { return "rgba( 014, 255, 000, $opacity)"; }
	if ( $this->snmp_strength == -57 ) { return "rgba( 022, 255, 000, $opacity)"; }
	if ( $this->snmp_strength == -58 ) { return "rgba( 032, 255, 000, $opacity)"; }
	if ( $this->snmp_strength == -59 ) { return "rgba( 046, 255, 000, $opacity)"; }
	if ( $this->snmp_strength == -60 ) { return "rgba( 063, 255, 000, $opacity)"; }
	if ( $this->snmp_strength == -61 ) { return "rgba( 084, 255, 000, $opacity)"; }
	if ( $this->snmp_strength == -62 ) { return "rgba( 108, 255, 000, $opacity)"; }
	if ( $this->snmp_strength == -63 ) { return "rgba( 133, 255, 000, $opacity)"; }
	if ( $this->snmp_strength == -64 ) { return "rgba( 159, 255, 000, $opacity)"; }
	if ( $this->snmp_strength == -65 ) { return "rgba( 183, 255, 000, $opacity)"; }
	if ( $this->snmp_strength == -66 ) { return "rgba( 206, 255, 000, $opacity)"; }
	if ( $this->snmp_strength == -67 ) { return "rgba( 226, 255, 000, $opacity)"; }
	if ( $this->snmp_strength == -68 ) { return "rgba( 242, 255, 000, $opacity)"; }
	if ( $this->snmp_strength == -69 ) { return "rgba( 252, 255, 000, $opacity)"; }
	if ( $this->snmp_strength == -70 ) { return "rgba( 255, 255, 000, $opacity)"; }
	if ( $this->snmp_strength == -71 ) { return "rgba( 255, 252, 000, $opacity)"; }
	if ( $this->snmp_strength == -72 ) { return "rgba( 255, 242, 000, $opacity)"; }
	if ( $this->snmp_strength == -73 ) { return "rgba( 255, 226, 000, $opacity)"; }
	if ( $this->snmp_strength == -74 ) { return "rgba( 255, 206, 000, $opacity)"; }
	if ( $this->snmp_strength == -75 ) { return "rgba( 255, 183, 000, $opacity)"; }
	if ( $this->snmp_strength == -76 ) { return "rgba( 255, 159, 000, $opacity)"; }
	if ( $this->snmp_strength == -77 ) { return "rgba( 255, 133, 000, $opacity)"; }
	if ( $this->snmp_strength == -78 ) { return "rgba( 255, 108, 000, $opacity)"; }
	if ( $this->snmp_strength == -79 ) { return "rgba( 255, 084, 000, $opacity)"; }
	if ( $this->snmp_strength == -80 ) { return "rgba( 255, 063, 000, $opacity)"; }
	if ( $this->snmp_strength == -81 ) { return "rgba( 255, 046, 000, $opacity)"; }
	if ( $this->snmp_strength == -82 ) { return "rgba( 255, 032, 000, $opacity)"; }
	if ( $this->snmp_strength == -83 ) { return "rgba( 255, 014, 000, $opacity)"; }
	if ( $this->snmp_strength == -84 ) { return "rgba( 255, 008, 000, $opacity)"; }
	if ( $this->snmp_strength == -85 ) { return "rgba( 255, 004, 000, $opacity)"; }
	if ( $this->snmp_strength == -86 ) { return "rgba( 255, 003, 000, $opacity)"; }
	if ( $this->snmp_strength == -87 ) { return "rgba( 255, 002, 000, $opacity)"; }
	if ( $this->snmp_strength == -88 ) { return "rgba( 255, 001, 000, $opacity)"; }
	if ( $this->snmp_strength == -89 ) { return "rgba( 255, 001, 000, $opacity)"; }
	if ( $this->snmp_strength == -90 ) { return "rgba( 255, 001, 000, $opacity)"; }
    }

    public function sshResetGain() {
// Temp function to reset gain
        // Load management Key
        $key = \App\User::where('callsign','manage')->first()->rsa_keys->where('publish',1)->first();


        $result = $this->executeSSH( 'manage', $key, "/interface wireless set antenna-gain=0 numbers=0") ;
        return $result;

        }

    /**
     * Remotely Update Radio Location Information
     * @return array
     */
    public function sshSetLocation() {

        // Load management Key
        $key = \App\User::where('callsign','manage')->first()->rsa_keys->where('publish',1)->first();


        $result = $this->executeSSH( 'manage', $key, "/snmp set location=" . $this->latitude . "," . $this->longitude) ;
        return $result;

    }

    public function sshQuickScan() {

        // Load management Key
        $key = \App\User::where('callsign','manage')->first()->rsa_keys->where('publish',1)->first();


        $result = $this->executeSSH( 'manage', $key, "/interface wireless scan 0 duration=5") ;
        return $result;

    }

    public function sshBWTest() {
        $target = escapeshellcmd($_GET['target']);
        $duration = escapeshellcmd($_GET['duration']);
        $direction = escapeshellcmd($_GET['direction']);

        // Load management Key
        $key = \App\User::where('callsign','manage')->first()->rsa_keys->where('publish',1)->first();

        $result = $this->executeSSH( 'manage', $key, "/tool bandwidth-test address=" . $target . " direction=" . $direction . " duration=" .$duration, true) ;

        $result['data'] = explode("\r\n\r\n", $result['data']);
        $result['data'] = "<pre width=\"200\" style=\"color: white; background: black\">" . $result['data'][ count($result['data'] )-1 ] . "</pre>";
        return $result;

    }

    public function sshFetchSpectralHistory(){


    // Load management Key
    $key = \App\User::where('callsign','manage')->first()->rsa_keys->where('publish',1)->first();

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
        $key = \App\User::where('callsign','manage')->first()->rsa_keys->where('publish',1)->first();



        $result = $this->executeSSH( 'manage', $key, "/interface wireless monitor numbers=wlan1 once") ;
        return $result;
    }

    public function sshFetchConfig() {


//        if ( $this->os != 'RouterOS' ) {
//            return array('status' => 'fail', 'reason' => 'command not supported for os type', 'data' => null);
//        }

        // Load management Key
        $key = \App\User::where('callsign','manage')->first()->rsa_keys->where('publish',1)->first();



        $result = $this->executeSSH( 'manage', $key, "/export") ;
        return $result;


    }
    public function sshCheckForUpdates(){


        // Load management Key
        $key = \App\User::where('callsign','manage')->first()->rsa_keys->where('publish',1)->first();

        $result = $this->executeSSH( 'manage', $key, "/system package update set channel=current") ;
        $result = $this->executeSSH( 'manage', $key, "/system package update check-for-updates") ;


        return $result;

    }
    public function sshDownloadUpdates(){


        // Load management Key
        $key = \App\User::where('callsign','manage')->first()->rsa_keys->where('publish',1)->first();

        $result = $this->executeSSH( 'manage', $key, "/system package update download") ;


        return $result;

    }
    public function sshInstallUpdates(){


        // Load management Key
        $key = \App\User::where('callsign','manage')->first()->rsa_keys->where('publish',1)->first();

        $result = $this->executeSSH( 'manage', $key, "/system package update install") ;


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


        $lease = $this->hasOne(\App\DhcpLease::class,'mac_address','mac_address')->orderBy('ends','desc');
        if ( $lease ) {
            return $lease->first();
        } else {
            $fake = new \App\DhcpLease();

            return $fake;
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

    public function __get($key)
    {
        if ( $key == 'librenms_mapping') {
            return $this->getLibreNMSMapping(true);
        }
        return $this->getAttribute($key);


    }
    public function libre_ports() {
        return $this->hasMany(\App\LibreNMSPort::class,'device_id', 'librenms_mapping' );


    }
    public function libre_sensor( $class ) {
        if ( $this->librenms_mapping ) {

            $result =  \App\LibreNMSSensor::all()->where('device_id', '=', $this->librenms_mapping)->where('sensor_class', '=', $class )->first();
            if ($result) {
                return $result;
            }
        }

        return new \App\LibreNMSSensor();
    }

    public function libre_wireless(  ) {

//        $this->librenms_mapping = $this->getLibreNMSMapping(true);
        print $this->librenms_mapping;
        if ( $this->librenms_mapping ) {

            $result =  \App\LibreNMSWirelessSensor::all()->where('device_id', '=',  $this->librenms_mapping);
            if ($result) {
                return $result;
            }
        }

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

        $alerts = \App\LibreNMSAlert::where('device_id', $this->getLibreNMSMapping(true))->where('state','=', $state);
        return $alerts;

    }

    /*
         *
         * Returns the ID of the device in LibreNMS
         * Tries to find it in the local database and then optionally tries to discover it
         */

    public function getLibreNMSMapping( $allowSearch = true) {

        if ( $this->getAttribute('librenms_mapping') != 0 ) {
            return $this->getAttribute('librenms_mapping');
        } else {
            if ($allowSearch == false) {
                return false;
            } else {
                // Figure it out
                $port = $this->hasMany(LibreNMSPort::class, 'ifPhysAddress', 'mac_address')->first();
                if ($port) {
                    return $port->device_id;
                } else {
                    return false;
                }
            }
        }
        //////
//        if ( $this->getAttribute('librenms_mapping') != 0 ) {
//            return $this->getAttribute('librenms_mapping');
//        } else {
//            if ( $allowSearch === false ) {
//                return false;
//            } else {
//                // Try and figure it out
//                if ( $this->management_ip ) {
//                    $ip = LibreNMSIPv4Address::where('ipv4_address','=', $this->management_ip)->first();
//                    if (! $ip) {
//                        return false;
//                    }
//                    $port = \App\LibreNMSPort::where('port_id','=', $ip->port_id )->first();
//                    if (! $port) {
//                        return false;
//                    }
//                        $this->librenms_mapping = $port->device_id;
//                        return $port->device_id;
//                } else {
//                    return false;
//                }
//            }
//        }
    }

    public function get_management_ip() {
        if ( ! $this->management_ip ) {
            $dhcp = $this->dhcp_lease();
            if (!$dhcp) {
                return null;
            }
            $ip = $dhcp->ip;
        } else {
            $ip = $this->management_ip;
        }

        return $ip;
    }

    public function pollSNMP( $community = 'hamwan') {

        $ip = $this->get_management_ip();

        if ( !$ip ) {
            $result = array();
            $result['status'] = 'failed';
            $result['reason'] = 'No IP Address';
            $result['data'] =  array();


            return $result;
        }
        $snmp = new Snmp($ip, $community, "2c" );

        $r = $snmp->get(
            array(
                '-r 1 -t 1 ', // Hack to limit timeout to 1 s and retries to 1
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

        $result = array();
        $result['status'] = 'complete';
        $result['reason'] = '';
        $result['data'] =  $r;


        return $result;
    }

    /**
     * Returns a friendly name for the device
     */
public function friendly_name() {
    if ( strlen( $this->radio_name) >= 1 ) {
        return $this->radio_name;
    } elseif ( strlen( $this->snmp_sysName) >= 1 )  {
        return $this->snmp_sysName ;
    } elseif ( $this->dhcp_lease() ) {
        if(  strlen($this->dhcp_lease()->hostname) >= 1 ) {
            return $this->dhcp_lease()->hostname;
        }
    }

    return strtoupper($this->mac_address);

}

    /**
     * Returns a class based on the number of alerts in LibreNMS
     * @return string
     */
    public function libre_status_class() {
        if( $this->librenms_mapping ) {
            if( $this->libre_alerts(1)->count() >= 1) {
                return "danger";
            } elseif( $this->libre_alerts(2)->count() >= 1 ) {
                return "warning";
            } else {
                return "success";
            }
        } else {
            return "";
        }
    }

    /**
     * Returns a class based on the time since last heard
     * @return string
     */

    public function last_heard_class() {
            if( $this->last_heard_ago() >= 60) {
                return "danger";
            } elseif( $this->last_heard_ago() >= 6 ) {
                return "warning";
            } else {
                return "";
            }

    }

    public function last_heard_ago() {
        return ((strtotime("now")-strtotime($this->updated_at))/60);
    }


}
