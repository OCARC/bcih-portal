<?php

namespace App;

use App\Console\Commands\pollClients;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use League\Flysystem\Exception;
use Nelisys\Snmp;
use App\Client;
use App\Site;
use App\IP;
use Spatie\Permission\Traits\HasRoles;
use \phpseclib\Crypt\RSA;
use \phpseclib\File\ANSI;
use \phpseclib\Net\SSH2;


class Equipment extends Model
{
    use HasRoles;

    CONST OID_NEIGHBOR_IP = "iso.3.6.1.4.1.14988.1.1.11.1.1.2";
    CONST OID_NEIGHBOR_MAC = "iso.3.6.1.4.1.14988.1.1.11.1.1.3";
    CONST OID_NEIGHBOR_ID = "iso.3.6.1.4.1.14988.1.1.11.1.1.6";
    CONST OID_REG_TABLE = ".1.3.6.1.4.1.14988.1.1.1.2.1";
    CONST OID_REG_TABLE_SIGNAL_STRENGTH = ".1.3.6.1.4.1.14988.1.1.1.2.1.3";
    CONST OID_REG_TABLE_TX_RATE = ".1.3.6.1.4.1.14988.1.1.1.2.1.8";
    CONST OID_REG_TABLE_RX_RATE = ".1.3.6.1.4.1.14988.1.1.1.2.1.9";
    CONST OID_REG_TABLE_SIGNAL_TO_NOISE = ".1.3.6.1.4.1.14988.1.1.1.2.1.12";
    CONST OID_LOCATION = ".1.3.6.1.2.1.1.6.0";
    CONST OID_SERIAL = ".1.3.6.1.4.1.14988.1.1.7.3.0";
    const OID_SYSDESC = ".1.3.6.1.2.1.1.1.0";

    CONST OID_SNR = ".1.3.6.1.4.1.14988.1.1.1.2.1.12";
    CONST OID_TEMPERATURE = ".1.3.6.1.4.1.14988.1.1.3.10.0";
    CONST OID_VOLTAGE = ".1.3.6.1.4.1.14988.1.1.3.8.0";
    CONST OID_BAND = ".1.3.6.1.4.1.14988.1.1.1.3.1.8.2";
    CONST OID_FREQUENCY = ".1.3.6.1.4.1.14988.1.1.1.3.1.7.2";
    const OID_SSID = ".1.3.6.1.4.1.14988.1.1.1.3.1.4.2";
    const OID_UPTIME = ".1.3.6.1.2.1.1.3.0";
    //

    CONST OID_mtxrWlRtabSignalToNoise = ".1.3.6.1.4.1.14988.1.1.1.2.1.12";

    CONST OID_mtxrWlRtabTxStrengthCh0 = ".1.3.6.1.4.1.14988.1.1.1.2.1.13";
    CONST OID_mtxrWlRtabRxStrengthCh0 = ".1.3.6.1.4.1.14988.1.1.1.2.1.14";
    CONST OID_mtxrWlRtabTxStrengthCh1 = ".1.3.6.1.4.1.14988.1.1.1.2.1.15";
    CONST OID_mtxrWlRtabRxStrengthCh1 = ".1.3.6.1.4.1.14988.1.1.1.2.1.16";
    CONST OID_mtxrWlRtabTxStrengthCh2 = ".1.3.6.1.4.1.14988.1.1.1.2.1.17";
    CONST OID_mtxrWlRtabRxStrengthCh2 = ".1.3.6.1.4.1.14988.1.1.1.2.1.18";

    protected $guarded = [];
    use \App\Traits\SSHConnection;
    use \App\Traits\LibreNMS;
    use \App\Traits\DeviceIcon;
    use \App\Traits\DAEnetIP4;

    protected $hidden = [
        'snmp_community',
        'comments'
    ];


    /**
     * Returns permission appropriate version of serial number for equipment
     */
    public function get_serial_number() {
        $user = auth()->user();


        $serial = "";

        if ( $this->librenms_mapping ) {
            $serial = $this->libre_device['serial'];
        } else {
            $serial = $this->snmp_serial;
        }


        if (!$user) {
            // Not logged in
            return substr_replace($serial, 'xxxx', -6, 4);
        } else {
            if ($user->can('equipment.view_serial_numbers') ) {
                return $serial;
            } else {
                return substr_replace($serial, 'xxxx', -6, 4);
            }
        }

    }


    protected $guard_name = 'web'; // or whatever guard you want to use

    public function graphs(  ) {

        $results = array();


        $cacti_graphs = $this->hasMany(\App\CactiGraph::class,"host_id","cacti_id");
        foreach( $cacti_graphs as $graph) {
            $results[] = array( 'type' => 'cacti', 'url' => $graph->url(2) );
        }

        if ($this->librenms_mapping ) {
            $libre_graphs = $this->libreGetHealthList();
            foreach( $libre_graphs as $graph ) {
                $results[] = array( 'category' => 'Health', 'type' => 'librenms', 'description' => $graph['desc'], 'url' => '/equipment/' . $this->id . "/graph/" . $graph['name'] . ""  );
            }

            $libre_graphs = $this->libreGetHealthList();
            foreach( $libre_graphs as $graph ) {
                $results[] = array( 'category' => 'Ports', 'type' => 'librenms', 'description' => $graph['desc'], 'url' => '/equipment/' . $this->id . "/graph/" . $graph['name'] . "" );
            }
        }

        if ( $results ) {
            return $results;
        } else {
            return array();
        }
    }

    public function getHealthColor()
    {


        if ($this->librenms_mapping) {
            if ($this->libre_alerts(1)->count() >= 1) {
                return "#d9534f";
            } elseif ($this->libre_alerts(2)->count() >= 1) {
                return "#f0ad4e";
            } else {
                return "#5cb85c";
            }

        } else {
            if ($this->status == "Planning" || $this->status == "Potential" || $this->status == "No Install") {
                return "inherit";
            }

            if ($this::getHealthStatus() == "Error") {
                return '#ffad2f'; // Error
            } else if ($this::getHealthStatus() == "High Temp") {
                return '#ffad2f'; // Error
            } else if ($this::getHealthStatus() == "Offline") {
                return '#ffaaaa';
            } else if ($this::getHealthStatus() == "OK") {
                return '#aaffaa'; // OK
            } else {
                return '#cccccc'; // Unknown

            }


        }
    }


    public function eirp() {
if ( ! $this->ant_gain && ! $this->radio_power ) { return null;}
        $p = $this->ant_gain + $this->radio_power;
        return number_format( pow(10,( $p /10))/1000, 2);

    }

    public function clients() {
        return $this->hasMany(Client::class);
    }
    public function ips() {
        return \App\IP::all()->where("equipment_id", $this->id);
    }
    public function user() {
        $user = $this->belongsTo(User::class);
        if ( $user ) {
            return $user;
        } else {
            return new \App\User();
        }



    }

    public function site() {
        $site = $this->belongsTo(Site::class);
        if ( $site ) {
            return $site;
        } else {
            return new \App\Site();
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


    public function convert_oidmac_to_hex( $oidmac ) {
        $result = "";
        $parts = array_slice(explode( ".", $oidmac),0,6);
        foreach ($parts as $part ) {
            $result .= str_pad( dechex( $part ), 2,"0", STR_PAD_LEFT);
        }
        return $result;
    }


    /**
     * Interrogate the equipment and return client records.
     */
    public function discoverClients( $community = 'hamwan' ) {


        if ($this->discover_clients != 1 ) {
            session()->flash('msg', 'This Equipment Does Not Allow Discovery Of Clients' );
            $result = array();
            $result['status'] = 'failed';
            $result['reason'] = 'This Equipment Does Not Allow Discovery Of Clients';
            $result['data'] =  array();

            return $result;
        }
        if ($this->os != 'RouterOS' ) {
            session()->flash('msg', 'Client Discovery Only Works on RouterOS Devices' );
            $result = array();
            $result['status'] = 'failed';
            $result['reason'] = 'Client Discovery Only Works on RouterOS Devices';
            $result['data'] =  array();

            return $result;
        }



        // Create SNMP Connection to Equipment
        $snmp = new Snmp($this->management_ip, $this->snmp_community ? $this->snmp_community : 'hamwan', "2c" );

        // Create Array to store clients in as they are discovered
        $clients = array();

        $snmp_result = $snmp->walk( $this::OID_REG_TABLE );

        // Sort results
        foreach( $snmp_result as $key => $value ) {

            // split into oid, mac
            preg_match('/^(.+?).(\d+\.\d+\.\d+\.\d+\.\d+\.\d+\.\d+)$/', $key, $m);

            $client_mac = $this->convert_oidmac_to_hex( $m[2] );
            $oid = $m[1];

            if ( isset($clients[$client_mac]) === false ) {
                // Lets create it
                $clients[ $client_mac] = array();
            }
            $clients[ $client_mac][$oid] = $value;


        }

        // Lets find the existing client records
        foreach( $clients as $key => $client ) {

            $c = Client::where('mac_address', $key)->first();

            // If the client exists we need to update it
            if ($c) {

                $update = $c->getAttributes();

                $update['mac_address'] = $key;
                $update['site_id'] = $this->site_id;
                $update['equipment_id'] = $this->id;

                $update['snmp_strength'] = ( isset($client[$this::OID_REG_TABLE_SIGNAL_STRENGTH]) ) ? $client[$this::OID_REG_TABLE_SIGNAL_STRENGTH] : null;
                $update['snmp_signal_to_noise'] = ( isset($client[$this::OID_REG_TABLE_SIGNAL_TO_NOISE]) ) ? $client[$this::OID_REG_TABLE_SIGNAL_TO_NOISE] : null;
                $update['snmp_tx_rate'] = ( isset($client[$this::OID_REG_TABLE_TX_RATE]) ) ? $client[$this::OID_REG_TABLE_TX_RATE] : null;
                $update['snmp_rx_rate'] = ( isset($client[$this::OID_REG_TABLE_RX_RATE]) ) ? $client[$this::OID_REG_TABLE_RX_RATE] : null;


                $update['snmp_tx_strength_ch0'] = ( isset($client[$this::OID_mtxrWlRtabTxStrengthCh0]) ) ? $client[$this::OID_mtxrWlRtabTxStrengthCh0] : null;
                $update['snmp_rx_strength_ch0'] = ( isset($client[$this::OID_mtxrWlRtabRxStrengthCh0]) ) ? $client[$this::OID_mtxrWlRtabRxStrengthCh0] : null;
                $update['snmp_tx_strength_ch1'] = ( isset($client[$this::OID_mtxrWlRtabTxStrengthCh1]) ) ? $client[$this::OID_mtxrWlRtabTxStrengthCh1] : null;
                $update['snmp_rx_strength_ch1'] = ( isset($client[$this::OID_mtxrWlRtabRxStrengthCh1]) ) ? $client[$this::OID_mtxrWlRtabRxStrengthCh1] : null;
                $update['snmp_tx_strength_ch2'] = ( isset($client[$this::OID_mtxrWlRtabTxStrengthCh2]) ) ? $client[$this::OID_mtxrWlRtabTxStrengthCh2] : null;
                $update['snmp_rx_strength_ch2'] = ( isset($client[$this::OID_mtxrWlRtabRxStrengthCh2]) ) ? $client[$this::OID_mtxrWlRtabRxStrengthCh2] : null;

                // If the equipment id is changing generate a log entry
                if ( $c['equipment_id'] != $update['equipment_id']) {
                    $log = new \App\LogEntry;

                    $log->description = "Client Associated With Sector";
                    $log->event_type = "ClientAssoc";
                    $log->event_level = 0;
                    $log->site_id = $this->site_id;
                    $log->equipment_id = $update['equipment_id'];
                    $log->client_id = $c->id;
                    $log->save();
                }

                $c->fill($update);
                $c->save();

            } else {
                //Create it

                //Create
                $update = array();

                $update['mac_address'] = $key;
                $update['site_id'] = $this->site_id;
                $update['equipment_id'] = $this->id;

                $update['snmp_strength'] = ( isset($client[$this::OID_REG_TABLE_SIGNAL_STRENGTH]) ) ? $client[$this::OID_REG_TABLE_SIGNAL_STRENGTH] : null;
                $update['snmp_signal_to_noise'] = ( isset($client[$this::OID_REG_TABLE_SIGNAL_TO_NOISE]) ) ? $client[$this::OID_REG_TABLE_SIGNAL_TO_NOISE] : null;
                $update['snmp_tx_rate'] = ( isset($client[$this::OID_REG_TABLE_TX_RATE]) ) ? $client[$this::OID_REG_TABLE_TX_RATE] : null;
                $update['snmp_rx_rate'] = ( isset($client[$this::OID_REG_TABLE_RX_RATE]) ) ? $client[$this::OID_REG_TABLE_RX_RATE] : null;


                $update['snmp_tx_strength_ch0'] = ( isset($client[$this::OID_mtxrWlRtabTxStrengthCh0]) ) ? $client[$this::OID_mtxrWlRtabTxStrengthCh0] : null;
                $update['snmp_rx_strength_ch0'] = ( isset($client[$this::OID_mtxrWlRtabRxStrengthCh0]) ) ? $client[$this::OID_mtxrWlRtabRxStrengthCh0] : null;
                $update['snmp_tx_strength_ch1'] = ( isset($client[$this::OID_mtxrWlRtabTxStrengthCh1]) ) ? $client[$this::OID_mtxrWlRtabTxStrengthCh1] : null;
                $update['snmp_rx_strength_ch1'] = ( isset($client[$this::OID_mtxrWlRtabRxStrengthCh1]) ) ? $client[$this::OID_mtxrWlRtabRxStrengthCh1] : null;
                $update['snmp_tx_strength_ch2'] = ( isset($client[$this::OID_mtxrWlRtabTxStrengthCh2]) ) ? $client[$this::OID_mtxrWlRtabTxStrengthCh2] : null;
                $update['snmp_rx_strength_ch2'] = ( isset($client[$this::OID_mtxrWlRtabRxStrengthCh2]) ) ? $client[$this::OID_mtxrWlRtabRxStrengthCh2] : null;

                $c = \App\Client::create($update);


                $log = new \App\LogEntry;

                $log->description = "New Client Associated With Sector";
                $log->event_type = "NewClientAssoc";
                $log->event_level = 0;
                $log->site_id = $this->site_id;
                $log->equipment_id = $update['equipment_id'];
                $log->client_id = $c->id;
                $log->save();
            }
        }


        $result = array();
        $result['status'] = 'complete';
        $result['reason'] = '';
        $result['data'] =  $clients;

        return $result;
       // print_r( $results );
    }
    /**
     * Interrogate the equipment and return DHCP records.
     */
    public function pollDHCP( $community = 'hamwan' ) {

        if ($this->dhcp_server != 1 ) {
            session()->flash('msg', 'This Equipment Does Not Allow Polling of DHCP Records' );
            $result = array();
            $result['status'] = 'failed';
            $result['reason'] = 'This Equipment Does Not Allow Discovery Of Clients';
            $result['data'] =  array();

            return $result;
        }
        if ($this->os != 'RouterOS' ) {
            session()->flash('msg', 'DHCP Polling Only Works on RouterOS Devices' );
            $result = array();
            $result['status'] = 'failed';
            $result['reason'] = 'Client Discovery Only Works on RouterOS Devices';
            $result['data'] =  array();

            return $result;
        }

$hosts = array();

       // $equipment = Equipment::where('dhcp_server',true)->get();
       // foreach( $equipment as $e ) {
            $server = $this->management_ip;

            $snmp = new Snmp($server, 'hamwan', "2c" );

            $r = $snmp->walk(
                ".1.3.6.1.2.1.9999.1.1.6.4.1"
            );
            // Reshape

            foreach( $r as $key=>$value ) {
                preg_match ('/(\.1\.3\.6\.1\.2\.1\.9999\.1\.1\.6\.4\.1)\.(.)\.(.+)/', $key,$parts );

                if ($parts) {
                    if ($parts[2] == 5) {
                        $hosts[$server . "-" . $parts[3]]['ttl'] = $value;
                        $hosts[$server . "-" . $parts[3]]['ip'] = $parts[3];
                        $hosts[$server . "-" . $parts[3]]['server'] = $server;
                        $hosts[$server . "-" . $parts[3]]['equipment_id'] = $this->id;
                    }

                    if ($parts[2] == 8) {
                        $hosts[$server . "-" . $parts[3]]['mac'] = str_replace(" ", "", substr($value, 5));
                        $hosts[$server . "-" . $parts[3]]['ip'] = $parts[3];
                        $hosts[$server . "-" . $parts[3]]['server'] = $server;
                        $hosts[$server . "-" . $parts[3]]['equipment_id'] = $this->id;


                    }
                }
            }



        //}


        // Update
        foreach ($hosts as $host) {
            $l = DhcpLease::where('mac_address', $host['mac'])->where('dhcp_server', $host['server'])->first();

            if ($l) {
                // Update
                if ( $l->ip != $host['ip'] || $l->mac_address != $l['mac']) {
                    $l->removeDNS();
                }
                $l->fill([
                    'id' => $l->id,
                    'owner' => -1,
                    'ip' => $host['ip'],
                    'hostname' => '',
                    'mac_oui_vendor' => 'unk',
                    'mac_address' => $host['mac'],
                    'dhcp_server' => $host['server'],
                    'ends' => time() + $host['ttl'],
                ]);
                $l->save();
                $l->updateDNS();
            } else {
                $l = \App\DhcpLease::create([
                    'owner' => -1,
                    'ip' => $host['ip'],
                    'hostname' => '',
                    'mac_oui_vendor' => 'unk',
                    'mac_address' => $host['mac'],
                    'dhcp_server' => $host['server'],
                    'starts' => time(),

                    'ends' => time() + $host['ttl'],
                ]);
                $log = new \App\LogEntry;

                $log->description = "New DHCP Lease " . $l->ip . " leased to " . $l->mac_address;
                $log->event_type = "DHCP";
                $log->event_level = 0;
                $log->equipment_id = $host['equipment_id'];
                if( $l->client() ) {
                    $log->client_id = $l->client()->id;
                }
                $log->save();

                $l->updateDNS();
            }
        }

        $result = array();
        $result['status'] = 'complete';
        $result['reason'] = '';
        $result['data'] =  $hosts;

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
    public function sshFetchOSPFRoutes(){
        if ( $this->os != 'RouterOS' ) {
            return array('status' => 'fail', 'reason' => 'command not supported for os type', 'data' => null);
        }

        // Load management Key
        $key = \App\User::where('callsign','manage')->first()->rsa_keys->where('publish',1)->first();

        $result = $this->executeSSH( 'manage', $key, "/routing ospf route print") ;


        return $result;

    }
    public function sshCheckForUpdates(){
        if ( $this->os != 'RouterOS' ) {
            return array('status' => 'fail', 'reason' => 'command not supported for os type', 'data' => null);
        }

        // Load management Key
        $key = \App\User::where('callsign','manage')->first()->rsa_keys->where('publish',1)->first();

        $result = $this->executeSSH( 'manage', $key, "/system package update check-for-updates") ;


        return $result;

    }
    public function sshDownloadUpdates(){
        if ( $this->os != 'RouterOS' ) {
            return array('status' => 'fail', 'reason' => 'command not supported for os type', 'data' => null);
        }

        // Load management Key
        $key = \App\User::where('callsign','manage')->first()->rsa_keys->where('publish',1)->first();

        $result = $this->executeSSH( 'manage', $key, "/system package update download") ;


        return $result;

    }
    public function sshInstallUpdates(){
        if ( $this->os != 'RouterOS' ) {
            return array('status' => 'fail', 'reason' => 'command not supported for os type', 'data' => null);
        }

        // Load management Key
        $key = \App\User::where('callsign','manage')->first()->rsa_keys->where('publish',1)->first();

        $result = $this->executeSSH( 'manage', $key, "/system package update install") ;


        return $result;

    }
    public function sshFetchSpectralHistory(){
        if ( $this->os != 'RouterOS' ) {
            return array('status' => 'fail', 'reason' => 'command not supported for os type', 'data' => null);
        }

        // Load management Key
        $key = \App\User::where('callsign','manage')->first()->rsa_keys->where('publish',1)->first();

        if ( $this->radio_model == "RB Metal 9HPn") {
            // Do 900mhz Scan
            $result = $this->executeSSH( 'manage', $key, "/interface wireless spectral-history range=892-938 duration=15 number=wlan1 buckets=100") ;
        } else {
            // Assume 5.9ghz
            $result = $this->executeSSH( 'manage', $key, "/interface wireless spectral-history range=5850-5950 duration=15 number=wlan1 buckets=100") ;
        }

        $result['data'] = str_replace("#" ,"<span style='background-color: #9b0000'>#</span>", $result['data']);

        $result['data'] = str_replace("." ,"<span style='background-color: #00009b'>#</span>", $result['data']);
        $result['data'] = str_replace("+" ,"<span style='background-color: #009b00'>#</span>", $result['data']);
        $result['data'] = str_replace("*" ,"<span style='background-color: #9b9b00'>#</span>", $result['data']);
        $result['data'] = str_replace("%" ,"<span style='background-color: #9b9b9b'>#</span>", $result['data']);
        return $result;

    }
    public function sshFetchPOE() {

        if ( $this->os != 'RouterOS' ) {
            return array('status' => 'fail', 'reason' => 'command not supported for os type', 'data' => null);
        }

        // Load management Key
        $key = \App\User::where('callsign','manage')->first()->rsa_keys->where('publish',1)->first();

        // Open SSH
        $result = $this->executeSSH( 'manage', $key, "/interface ethernet poe monitor 0,1,2,3 once") ;
        return $result;



    }
    public function sshQuerySerialStatus() {

        if ( $this->os != 'RouterOS' ) {
            return array('status' => 'fail', 'reason' => 'command not supported for os type', 'data' => null);
        }

        // Load management Key
        $key = \App\User::where('callsign','manage')->first()->rsa_keys->where('publish',1)->first();

        // Open SSH
        $result = $this->executeSSH( 'manage', $key, "/port remote-access print detail") ;
        return $result;



    }
    public function sshAuthorizeSerialIP() {

        if ( $this->os != 'RouterOS' ) {
            return array('status' => 'fail', 'reason' => 'command not supported for os type', 'data' => null);
        }
        $ip = $_GET['ip'];
        $ip =  preg_replace('/\D\./', '', $ip);


        // Load management Key
        $key = \App\User::where('callsign','manage')->first()->rsa_keys->where('publish',1)->first();

        // Open SSH
        $result = $this->executeSSH( 'manage', $key, "/port remote-access add port=\"usb1\" allowed-addresses=\"" . $ip . "\" tcp-port=5555") ;
        $result['data'] .= "<pre width=\"200\" style=\"color: green; background: black\">Request Complete, Run Query Status To Confirm</pre>";


        return $result;



    }
    public function sshResetSerialAuthentication() {

        if ( $this->os != 'RouterOS' ) {
            return array('status' => 'fail', 'reason' => 'command not supported for os type', 'data' => null);
        }

        // Load management Key
        $key = \App\User::where('callsign','manage')->first()->rsa_keys->where('publish',1)->first();

        // Open SSH
        $result = $this->executeSSH( 'manage', $key, "/port remote-access remove [find]") ;
        $result['data'] .= "<pre width=\"200\" style=\"color: green; background: black\">Request Complete, Run Query Status To Confirm</pre>";

        return $result;



    }


    public function updateOSPF() {

        if ( ! $this->librenms_mapping ) {
            print  "No Lobre\n";
            return;
        }
        // Load management Key
        $key = \App\User::where('callsign','manage')->first()->rsa_keys->where('publish',1)->first();

        $result = $this->executeSSH( 'manage', $key, "/routing ospf instance print without-paging detail") ;
        $instance = strip_tags( html_entity_decode($result['data']));
        $instance = str_replace("\r\n", "", $instance);

        preg_match('/default\s+(?<ospf_instance_id>\d+)\s+[X|\*]/',$instance,$matches);


        print_r($matches);

        print "\n\n";

    }

    public function sshFetchConfig() {


        if ( $this->os != 'RouterOS' ) {
            return array('status' => 'fail', 'reason' => 'command not supported for os type', 'data' => null);
        }

        // Load management Key
        $key = \App\User::where('callsign','manage')->first()->rsa_keys->where('publish',1)->first();



    $result = $this->executeSSH( 'manage', $key, "/export") ;



        return $result;


    }

    public function pollSNMP( $community = 'hamwan') {

        /**
         * Do Some Libre Polling Here
         */

//        if ( $this->librenms_mapping ) {
//
//            // Check for IPs we need to create
//            $result = $this->libre_query('devices/' . $this->librenms_mapping . "/ip");
//            if ($result) {
//                if ($result['status'] == 'ok') {
//                    foreach ($result['addresses'] as $address) {
//                        $ip = IP::where('ip', $address['ipv4_address'])->first();
//                        if ($ip) {
//                            // Do nothing for now
//                        } else {
//                            // Create missing record
//                            $ip = new IP();
//                            $ip->ip = $address['ipv4_address'];
//                            $ip->equipment_id = $this->id;
//                            $ip->netmask = $ip->cidr2mask($address["ipv4_prefixlen"]);
//                            $ip->comment = "Automatically Created From LibreNMS Data";
//                            $ip->category = "Discovered";
//                            $ip->save();
//                        }
//                    }
//                }
//            }
//
//        }

        /*******************/

        $updated = $this->updated_at;

        $snmp = new Snmp($this->management_ip, $community, "2c" );

        $r = $snmp->get(
            array(
                '-r 1 -t 1 ', // Hack to limit timeout to 1 s and retries to 1
                $this::OID_VOLTAGE,
                $this::OID_TEMPERATURE,
                $this::OID_UPTIME,
                $this::OID_FREQUENCY,
                $this::OID_BAND,
                $this::OID_SERIAL,
                $this::OID_SSID,
                $this::OID_SNR,
                $this::OID_SERIAL,
                $this::OID_SYSDESC,
                $this::OID_FREQUENCY,
                $this::OID_SSID,
                $this::OID_BAND,
                $this::OID_SNR,

            )
        );

        if ( isset($r[$this::OID_FREQUENCY]) ) {
            $this->snmp_frequency = $r[$this::OID_FREQUENCY];
        }
        if ( isset($r[$this::OID_SSID]) ) {
            $this->snmp_ssid = $r[$this::OID_SSID];
        }
        if ( isset($r[$this::OID_BAND]) ) {
            $this->snmp_band = $r[$this::OID_BAND];
        }
        if ( isset($r[$this::OID_SNR]) ) {
            $this->snmp_snr = $r[$this::OID_SNR];
        }

        if ( isset($r[$this::OID_VOLTAGE]) ) {
            $this->snmp_voltage = (int)$r[$this::OID_VOLTAGE]/10;
        }


        if ( isset($r[$this::OID_SYSDESC]) ) {
        if ( $r[$this::OID_SYSDESC] != "" ) {
            $this->radio_model = substr(str_replace("RouterOS ", "", $r[$this::OID_SYSDESC]),0,190);
        }
        }
        if ( isset($r[$this::OID_TEMPERATURE]) ) {
            $this->snmp_temperature = (int)$r[$this::OID_TEMPERATURE]/10;
        }
        if ( isset($r[$this::OID_SERIAL]) ) {
            if ( $r[$this::OID_SERIAL] != "" ) {
                $this->snmp_serial = $r[$this::OID_SERIAL];
            }
        }
        if ( isset($r[$this::OID_TEMPERATURE]) ) {
            $this->snmp_uptime = $r[$this::OID_UPTIME];
        } else {
            $this->snmp_uptime = 0;
        }
        if ( $this->snmp_uptime != 0) {
            $this->snmp_timestamp = \DB::raw('now()');
        }

        // Do not update timestamps
        $this->timestamps = false;

        $this->save();

        $result = array();
        $result['status'] = 'complete';
        $result['reason'] = '';
        $result['data'] =  $r;


       return $result;
    }


    // Currently only supports LibreNMS
    public function ports() {

        if ( $this->librenms_mapping ) {
            $ports = \App\LibreNMSPort::all()->where('device_id', '=', $this->librenms_mapping)->sortBy('ifPhysAddress');
            return $ports;

        }

        return new Collection();
    }


    public function __get($key)
    {

        if ( $key == 'librenms_mapping') {
            return $this->getLibreNMSMapping(false); // too slow to search
        }
        return $this->getAttribute($key);


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
            if ( $allowSearch == false ) {
                return false;
            } else {

                // Try and figure it out
                if ( $this->management_ip ) {
                    $mac = LibreNMSIPv4Mac::where('ipv4_address','=', $this->management_ip)->first();
                    if ( $mac ) {
                        $this->librenms_mapping = $mac->device_id;
                        return $mac->device_id;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }
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
        //TODO: Fix timezone cludge
        return ((strtotime("now")-strtotime($this->snmp_timestamp))/60) -420;
    }
}
