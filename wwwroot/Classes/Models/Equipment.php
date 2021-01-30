<?php
// src/Site.php

namespace Models;


use Controllers\IPController;
use \Models\Client;
use Nelisys\Snmp;

/**
 * @Entity @Table(name="equipment")
 **/
class Equipment extends Model
{
    use \Traits\DeviceIcon;
    use \Traits\Pingable;
    use \Traits\SNMPPollable;
    use \Traits\HelperFunctions;

    CONST OID_NEIGHBOR_IP = "iso.3.6.1.4.1.14988.1.1.11.1.1.2";
    CONST OID_NEIGHBOR_MAC = "iso.3.6.1.4.1.14988.1.1.11.1.1.3";
    CONST OID_NEIGHBOR_ID = "iso.3.6.1.4.1.14988.1.1.11.1.1.6";
    CONST OID_REG_TABLE = ".1.3.6.1.4.1.14988.1.1.1.2.1";
    CONST OID_REG_TABLE_SIGNAL_STRENGTH = ".1.3.6.1.4.1.14988.1.1.1.2.1.3";
    CONST OID_REG_TABLE_TX_RATE = ".1.3.6.1.4.1.14988.1.1.1.2.1.8";
    CONST OID_REG_TABLE_RX_RATE = ".1.3.6.1.4.1.14988.1.1.1.2.1.9";
    CONST OID_REG_TABLE_SIGNAL_TO_NOISE = ".1.3.6.1.4.1.14988.1.1.1.2.1.12";
    CONST OID_REG_TABLE_RADIO_NAME = ".1.3.6.1.4.1.14988.1.1.1.2.1.20";
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

    const OID_VERSION = '.1.3.6.1.4.1.14988.1.1.4.4.0';
    //

    CONST OID_mtxrWlRtabSignalToNoise = ".1.3.6.1.4.1.14988.1.1.1.2.1.12";

    CONST OID_mtxrWlRtabTxStrengthCh0 = ".1.3.6.1.4.1.14988.1.1.1.2.1.13";
    CONST OID_mtxrWlRtabRxStrengthCh0 = ".1.3.6.1.4.1.14988.1.1.1.2.1.14";
    CONST OID_mtxrWlRtabTxStrengthCh1 = ".1.3.6.1.4.1.14988.1.1.1.2.1.15";
    CONST OID_mtxrWlRtabRxStrengthCh1 = ".1.3.6.1.4.1.14988.1.1.1.2.1.16";
    CONST OID_mtxrWlRtabTxStrengthCh2 = ".1.3.6.1.4.1.14988.1.1.1.2.1.17";
    CONST OID_mtxrWlRtabRxStrengthCh2 = ".1.3.6.1.4.1.14988.1.1.1.2.1.18";


    /**
     * @var int
     */
    /** @Id @Column(type="integer") @GeneratedValue **/
    public $id;


    /** @Column(type="integer", nullable=true) **/
    protected $siteId;

    /** @Column(type="integer", nullable=true) **/
    protected $userId;


    /** @Column(type="string",length=128, nullable=true) **/
    protected $hostname;

    /** @Column(type="string", length=128, nullable=true) **/
    protected $managementIp;

    /** @Column(type="string", length=128, nullable=true ) **/
    protected $antennaModel;

    /** @Column(type="string", length=128, nullable=true ) **/
    protected $radioModel;

    /** @Column(type="string", length=128, nullable=true ) **/
    protected $os;

    /** @Column(type="string", length=128, nullable=true, options={"default": "hamwan"}) **/
    protected $snmp_community;


    /** @Column(type="datetime", nullable=true) **/
    protected $snmp_timestamp;

    /** @Column(type="string",length=128, nullable=true) **/
    protected $snmp_serial;

    /** @Column(type="string",length=32, nullable=true) **/
    protected $snmp_voltage;

    /** @Column(type="string",length=32, nullable=true) **/
    protected $snmp_temperature;




    public $permissions;

    public function __construct()
    {



    }



    public function getAntennaModel() {
        return $this->antennaModel;
    }
    public function getRadioModel() {
        return $this->radioModel;
    }
    public function getOS() {
        return $this->os;
    }
    public function getSNMPSysDesc() {

    }
    public function getManagementIP() {
        return $this->managementIp;
    }

    public function getHostname() {
        return $this->hostname;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }
    public function getLevel()
    {
        return $this->level;
    }

    public function getSNMPCommunity() {
        return $this->snmp_community;
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }
    public function getEquipment( ) {

        $query = \App::entityManager()->createQuery("SELECT e FROM \Models\Equipment e WHERE e.userId = ?1 OR e.visibility = 'public'")
            ->setParameter(1,  \App::getCurrentUser()->getId());

        $equipment = $query->getResult();
        return $equipment;
    }
    public function getEquipmentByVisibility( $visibility = 'public' ) {

        $query = \App::entityManager()->createQuery("SELECT e FROM \Models\Equipment e WHERE e.userId != ?1 AND e.visibility LIKE ?2")
            ->setParameter(1,  \App::getCurrentUser()->getId())
            ->setParameter(2,  $visibility);

        $equipment = $query->getResult();
        return $equipment;
    }
    public function getSites( ) {

        $query = \App::entityManager()->createQuery("SELECT e FROM \Models\Site e WHERE e.userId = ?1 OR e.visibility = 'public'")
            ->setParameter(1,  \App::getCurrentUser()->getId());

        $sites = $query->getResult();
        return $sites;
    }
    public function getSitesByVisibility( $visibility = 'public' ) {

        $query = \App::entityManager()->createQuery("SELECT e FROM \Models\Site e WHERE e.userId != ?1 AND e.visibility LIKE ?2")
            ->setParameter(1,  \App::getCurrentUser()->getId())
            ->setParameter(2,  $visibility);

        $sites = $query->getResult();
        return $sites;
    }
    public function getProjections()
    {
        // TODO: filter by user
        $projectionsRepository = \App::entityManager()->getRepository('\Models\Projection');
        $projections = $projectionsRepository->findAll();

        return $projections;
    }

        public function getSite() {
            if (! $this->siteId ) {
                return new \Models\Site;
            }
            $site = \App::entityManager()->find("\Models\Site", $this->siteId);
            if (! $site) {
                return new \Models\Site;
            }
            return $site;

        }

    public function discoverClients( $community = 'hamwan' ) {


//        if ($this->discover_clients != 1 ) {
//            session()->flash('msg', 'This Equipment Does Not Allow Discovery Of Clients' );
//            $result = array();
//            $result['status'] = 'failed';
//            $result['reason'] = 'This Equipment Does Not Allow Discovery Of Clients';
//            $result['data'] =  array();
//
//            return $result;
//        }
//        if ($this->os != 'RouterOS' ) {
//            session()->flash('msg', 'Client Discovery Only Works on RouterOS Devices' );
//            $result = array();
//            $result['status'] = 'failed';
//            $result['reason'] = 'Client Discovery Only Works on RouterOS Devices';
//            $result['data'] =  array();
//
//            return $result;
//        }



        // Create SNMP Connection to Equipment
        $snmp = new Snmp($this->getManagementIP(), $this->getSNMPCommunity() ? $this->getSNMPCommunity() : 'hamwan', "2c" );

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
        foreach( $clients as $key => $clientData ) {

            $client = \Controllers\ClientsController::getClientByMac( $key );

            // If the client exists we need to update it
            if ($client) {
$client->setEquipmentId( $this->id );
$client->setSiteId( $this->siteId );
if ( isset($clientData[self::OID_REG_TABLE_RADIO_NAME])) {
    $client->setRadioName($clientData[self::OID_REG_TABLE_RADIO_NAME]);
}


            } else {
                //Create it
                $client = new \Models\Client();

                $client->setMacAddress( $key );
                $client->setEquipmentId( $this->id );
                $client->setSiteId( $this->siteId );
                $client->setRadioName( $clientData[self::OID_REG_TABLE_RADIO_NAME] );

                \App::entityManager()->persist($client);
                \App::entityManager()->flush();

            }
            $client->storeSNMPResults( $clientData, $client->getKnownOIDS(), get_class( $client ), $this->id );

        }


//        $result = array();
//        $result['status'] = 'complete';
//        $result['reason'] = '';
//        $result['data'] =  $clients;
//
//        return $result;
    }

    public function pollDHCP( $community = 'hamwan' )
    {

//        if ($this->dhcp_server != 1 ) {
//            session()->flash('msg', 'This Equipment Does Not Allow Polling of DHCP Records' );
//            $result = array();
//            $result['status'] = 'failed';
//            $result['reason'] = 'This Equipment Does Not Allow Discovery Of Clients';
//            $result['data'] =  array();
//
//            return $result;
//        }
//        if ($this->os != 'RouterOS' ) {
//            session()->flash('msg', 'DHCP Polling Only Works on RouterOS Devices' );
//            $result = array();
//            $result['status'] = 'failed';
//            $result['reason'] = 'Client Discovery Only Works on RouterOS Devices';
//            $result['data'] =  array();
//
//            return $result;
//        }

        $hosts = array();

        // $equipment = Equipment::where('dhcp_server',true)->get();
        // foreach( $equipment as $e ) {
        $server = $this->getManagementIP();

        $snmp = new Snmp($server, 'hamwan', "2c");

        $r = $snmp->walk(
            ".1.3.6.1.2.1.9999.1.1.6.4.1"
        );
        // Reshape

        foreach ($r as $key => $value) {
            preg_match('/(\.1\.3\.6\.1\.2\.1\.9999\.1\.1\.6\.4\.1)\.(.)\.(.+)/', $key, $parts);

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
        foreach ($hosts as $host) {
            if ( $host['ttl'] >= 10000000) {
                $ttl = null;
                $type = 'dhcp-static';
            } else {
                $ttl = new \DateTime( '@' . (time() + $host['ttl']) );
                $type = 'dhcp';
            }

        $ip = IPController::getByIPAddress( $host['ip'] );
        if ( $ip ) {
            $ip->setType( $type );
            $ip->setMacAddress( $host['mac'] );
            $ip->setExpires( $ttl );

            $ip->setDHCPServer( $server );

        } else {
            $ip = new \Models\IP();
            $ip->setType( $type );
            $ip->setMacAddress( $host['mac'] );
            $ip->setIPAddress( $host['ip'] );
            $ip->setDHCPServer( $server );
            $ip->setExpires( $ttl );

        }


            \App::entityManager()->persist($ip);
            \App::entityManager()->flush();

            }
        // Update
//        foreach ($hosts as $host) {
//            $l = DhcpLease::where('mac_address', $host['mac'])->where('dhcp_server', $host['server'])->first();
//
//            if ($l) {
//                // Update
//                if ( $l->ip != $host['ip'] || $l->mac_address != $l['mac']) {
//                    $l->removeDNS();
//                }
//                $l->fill([
//                    'id' => $l->id,
//                    'owner' => -1,
//                    'ip' => $host['ip'],
//                    'hostname' => '',
//                    'mac_oui_vendor' => 'unk',
//                    'mac_address' => $host['mac'],
//                    'dhcp_server' => $host['server'],
//                    'ends' => time() + $host['ttl'],
//                ]);
//                $l->save();
//                $l->updateDNS();
//            } else {
//                $l = \App\DhcpLease::create([
//                    'owner' => -1,
//                    'ip' => $host['ip'],
//                    'hostname' => '',
//                    'mac_oui_vendor' => 'unk',
//                    'mac_address' => $host['mac'],
//                    'dhcp_server' => $host['server'],
//                    'starts' => time(),
//
//                    'ends' => time() + $host['ttl'],
//                ]);
//                $log = new \App\LogEntry;
//
//                $log->description = "New DHCP Lease " . $l->ip . " leased to " . $l->mac_address;
//                $log->event_type = "DHCP";
//                $log->event_level = 0;
//                $log->equipment_id = $host['equipment_id'];
//                if( $l->client() ) {
//                    $log->client_id = $l->client()->id;
//                }
//                $log->save();
//
//                $l->updateDNS();
//            }
//        }
//
//        $result = array();
//        $result['status'] = 'complete';
//        $result['reason'] = '';
//        $result['data'] =  $hosts;
//
//        return $result;


    }


}