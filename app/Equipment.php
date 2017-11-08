<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use League\Flysystem\Exception;
use Nelisys\Snmp;
use App\Client;
use App\Site;
use \phpseclib\Crypt\RSA;
use \phpseclib\File\ANSI;
use \phpseclib\Net\SSH2;

class Equipment extends Model
{
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

    protected $hidden = [
        'snmp_community'
    ];

    public function graphs() {
        $graphs = $this->hasMany(\App\CactiGraph::class,"host_id","cacti_id");
        if ( $graphs ) {
            return $graphs;
        } else {
            return new \App\CactiGraph();
        }
    }

    public function getHealthColor() {

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

    public function clients() {
       return $this->hasMany(Client::class) ;
    }
    public function owner() {
        return $this->belongsTo(User::class);

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


    public function discoverClients( $community = 'hamwan' ) {
        $snmp = new Snmp($this->management_ip, $community, "2c" );

        $clients = array();
        $results = $snmp->walk( $this::OID_REG_TABLE );

        foreach( $results as $key => $value ) {

            // split into oid, mac
            preg_match('/^(.+?).(\d+\.\d+\.\d+\.\d+\.\d+\.\d+\.\d+)$/', $key, $m);

            if ( ! isset($clients[ $this->convert_oidmac_to_hex( $m[2]) ])) {
                $clients[ $this->convert_oidmac_to_hex( $m[2]) ] = array();
            }

            $clients[ $this->convert_oidmac_to_hex( $m[2]) ][ $m[1] ] = $value;

        }

        foreach( $clients as $key => $client ) {

            $c = Client::where('mac_address', str_replace(":", "", $key))->first();

            if ($c) {
                // Update
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

                $c->fill($update);
                $c->save();
            } else {
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

                \App\Client::create($update);
            }
        }

       // print_r( $results );
    }

    public function sshFetchSpectralHistory(){
        if ( $this->os != 'RouterOS' ) {
            return array('status' => 'fail', 'reason' => 'command not supported for os type', 'data' => null);
        }

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
    public function sshFetchPOE() {

        if ( $this->os != 'RouterOS' ) {
            return array('status' => 'fail', 'reason' => 'command not supported for os type', 'data' => null);
        }

        // Load management Key
        $key = \App\User::where('id',0)->first()->rsa_keys->where('publish',1)->first();

        // Open SSH
        $result = $this->executeSSH( 'manage', $key, "/interface ethernet poe monitor ether2,ether3,ether4,ether5 once") ;
        return $result;



    }
    public function sshFetchConfig() {


        if ( $this->os != 'RouterOS' ) {
            return array('status' => 'fail', 'reason' => 'command not supported for os type', 'data' => null);
        }

        // Load management Key
        $key = \App\User::where('id',0)->first()->rsa_keys->where('publish',1)->first();



        $result = $this->executeSSH( 'manage', $key, "/export") ;
        return $result;


    }

    public function pollSNMP( $community = 'hamwan') {

        $snmp = new Snmp($this->management_ip, $community, "2c" );

        $r = $snmp->get(
            array(
                $this::OID_VOLTAGE,
                $this::OID_TEMPERATURE,
                $this::OID_UPTIME,
                $this::OID_FREQUENCY,
                $this::OID_BAND,
                $this::OID_SERIAL,
                $this::OID_SSID,
                $this::OID_SNR
            )
        );
        if ( isset($r[$this::OID_VOLTAGE]) ) {
            $this->snmp_voltage = (int)$r[$this::OID_VOLTAGE]/10;
        }
        if ( isset($r[$this::OID_TEMPERATURE]) ) {
            $this->snmp_temperature = (int)$r[$this::OID_TEMPERATURE]/10;
        }
        if ( isset($r[$this::OID_TEMPERATURE]) ) {
            $this->snmp_uptime = $r[$this::OID_UPTIME];
        } else {
            $this->snmp_uptime = 0;
        }
        if ( $this->snmp_uptime != 0) {
            $this->snmp_timestamp = \DB::raw('now()');
        }
        $this->save();
       // print_r($r);
    }
}
