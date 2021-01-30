<?php

namespace Traits;

use \Nelisys\Snmp;


trait SNMPPollable {

    var $snmpValues;

public function getSNMPValues( $refresh = false) {
    if ( $this->snmpValues && $refresh == false ) {
        return $this->snmpValues;
    }
    $classType = get_called_class();

    $query = \App::entityManager()->createQuery("SELECT s FROM \Models\SNMPValue s WHERE s.classId = ?1 AND s.classType = ?2")
        ->setParameter(1,  $this->id )
        ->setParameter(2,  $classType );


    $results = $query->getResult();

    $snmp_values = array();
    foreach( $results as $result ) {
        $this->snmpValues[$result->getKeyName() ] = $result;
    }


    return $this->snmpValues;
}

public function storeSNMPResults( $r, $oids, $classType, $classId ) {

    foreach( $oids as $name => $oid ) {
//        print $name . " > " . $oid . " = $r[$oid]\n\n";
        if ( isset($r[$oid]) ) {
            if($r[$oid]) {
                $snmpValue = new \Models\SNMPValue();
                $snmpValue->setClassType($classType);
                $snmpValue->setClassId($classId);
                $snmpValue->setKeyName($name);
                $snmpValue->setValue($r[$oid]);
                $snmpValue->setOID($oid);

                \App::entityManager()->merge($snmpValue);
                \App::entityManager()->flush();
            }
        }
    }
}

public function getKnownOIDS() {

    $oids = array(
        "OID_NEIGHBOR_IP" => "iso.3.6.1.4.1.14988.1.1.11.1.1.2",
        "OID_NEIGHBOR_MAC" => "iso.3.6.1.4.1.14988.1.1.11.1.1.3",
        "OID_NEIGHBOR_ID" => "iso.3.6.1.4.1.14988.1.1.11.1.1.6",
        "OID_REG_TABLE" => ".1.3.6.1.4.1.14988.1.1.1.2.1",
        "OID_REG_TABLE_SIGNAL_STRENGTH" => ".1.3.6.1.4.1.14988.1.1.1.2.1.3",
        "OID_REG_TABLE_TX_RATE" => ".1.3.6.1.4.1.14988.1.1.1.2.1.8",
        "OID_REG_TABLE_RX_RATE" => ".1.3.6.1.4.1.14988.1.1.1.2.1.9",
        "OID_REG_TABLE_SIGNAL_TO_NOISE" => ".1.3.6.1.4.1.14988.1.1.1.2.1.12",
        "OID_REG_TABLE_RADIO_NAME" => ".1.3.6.1.4.1.14988.1.1.1.2.1.20",

        "OID_LOCATION" => ".1.3.6.1.2.1.1.6.0",
        "OID_SERIAL" => ".1.3.6.1.4.1.14988.1.1.7.3.0",
        "OID_SYSDESC" => ".1.3.6.1.2.1.1.1.0",

        "OID_SNR" => ".1.3.6.1.4.1.14988.1.1.1.2.1.12",
        "OID_TEMPERATURE" => ".1.3.6.1.4.1.14988.1.1.3.10.0",
        "OID_VOLTAGE" => ".1.3.6.1.4.1.14988.1.1.3.8.0",
        "OID_BAND" => ".1.3.6.1.4.1.14988.1.1.1.3.1.8.2",
        "OID_FREQUENCY" => ".1.3.6.1.4.1.14988.1.1.1.3.1.7.2",
        "OID_SSID" => ".1.3.6.1.4.1.14988.1.1.1.3.1.4.2",
        "OID_UPTIME" => ".1.3.6.1.2.1.1.3.0",

        "OID_VERSION" => '.1.3.6.1.4.1.14988.1.1.4.4.0',

        "OID_mtxrWlRtabSignalToNoise" => ".1.3.6.1.4.1.14988.1.1.1.2.1.12",

        "OID_mtxrWlRtabTxStrengthCh0" => ".1.3.6.1.4.1.14988.1.1.1.2.1.13",
        "OID_mtxrWlRtabRxStrengthCh0" => ".1.3.6.1.4.1.14988.1.1.1.2.1.14",
        "OID_mtxrWlRtabTxStrengthCh1" => ".1.3.6.1.4.1.14988.1.1.1.2.1.15",
        "OID_mtxrWlRtabRxStrengthCh1" => ".1.3.6.1.4.1.14988.1.1.1.2.1.16",
        "OID_mtxrWlRtabTxStrengthCh2" => ".1.3.6.1.4.1.14988.1.1.1.2.1.17",
        "OID_mtxrWlRtabRxStrengthCh2" => ".1.3.6.1.4.1.14988.1.1.1.2.1.18",
    );
    return $oids;
}

public function pollSNMP( $community = 'hamwan') {


$oids = $this->getKnownOIDS();

$snmp = new \Nelisys\Snmp($this->getManagementIP(true), $community, "2c" );


$getOIDs =     array(
    '-r 1 -t 1 ', // Hack to limit timeout to 1 s and retries to 1
);

    foreach( $oids as $oid ) {
$getOIDs[] = $oid;
    }

$r = $snmp->get(
    $getOIDs
);
## handle responses
$this->storeSNMPResults( $r, $oids, get_called_class(), $this->id );



//if ( isset($r[$this::OID_SYSDESC]) ) {
//if ( $r[$this::OID_SYSDESC] != "" ) {
//$this->radio_model = substr(str_replace("RouterOS ", "", $r[$this::OID_SYSDESC]),0,190);
//}
//}
//if ( isset($r[$this::OID_TEMPERATURE]) ) {
//$this->snmp_temperature = (int)$r[$this::OID_TEMPERATURE]/10;
//}
//if ( isset($r[$this::OID_SERIAL]) ) {
//if ( $r[$this::OID_SERIAL] != "" ) {
//$this->snmp_serial = $r[$this::OID_SERIAL];
//}
//}
//if ( isset($r[$this::OID_TEMPERATURE]) ) {
//$this->snmp_uptime = $r[$this::OID_UPTIME];
//} else {
//$this->snmp_uptime = 0;
//}
//if ( $this->snmp_uptime != 0) {
//$this->snmp_timestamp = \DB::raw('now()');
//}
//
//// Do not update timestamps
//$this->timestamps = false;
//
//$this->save();
//
//$result = array();
//$result['status'] = 'complete';
//$result['reason'] = '';
//$result['data'] =  $r;


}

}