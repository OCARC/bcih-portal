<?php namespace App;

use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\Model;
use Nelisys\Snmp;
use App\Client;

class Infrastructure extends Model {
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
    protected $guarded = [];

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
                $c->fill([

                    'mac_address' => $key,
                    'site_id' => $this->site_id,
                    'infrastructure_id' => $this->id,


                ]);
                $c->save();
            } else {
                //Create

                \App\Client::create([

                    'mac_address' => $key,
                    'site_id' => $this->site_id,
                    'infrastructure_id' => $this->id,


                ]);
            }
        }

        print_r( $results );
    }

    public function pollSNMP( $community = 'hamwan') {

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
        print_r($r);
    }
}
