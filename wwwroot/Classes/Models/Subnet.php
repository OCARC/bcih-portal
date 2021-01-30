<?php
// src/Site.php

namespace Models;

/**
 * @Entity @Table(name="subnets")
 **/
class Subnet extends Model
{

    use \Traits\HelperFunctions;

    /**
     * @var int
     */
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;

    /** @Column(type="string",length=32, nullable=true ) **/
    protected $ipAddress;

    /** @Column(type="string",length=32, nullable=true ) **/
    protected $gateway;
    /** @Column(type="string",length=64, nullable=true ) **/
    protected $name;

    /** @Column(type="string",length=32, nullable=true ) **/
    protected $netmask;

    /** @Column(type="string",length=32, nullable=true ) **/
    protected $category;

    /** @Column(type="string",length=32, nullable=true ) **/
    protected $srcRouter;

    /** @Column(type="string",length=32, nullable=true ) **/
    protected $dstRouter;

    /** @Column(type="string",length=32, nullable=true ) **/
    protected $status;

    /** @Column(type="integer", nullable=true ) **/
    protected $userId;

    /** @Column(type="integer", nullable=true ) **/
    protected $siteId;

    /** @Column(type="string", nullable=true ) **/
    protected $description;

    /** @Column(type="string", nullable=true ) **/
    protected $comment;

    /** @Column(type="string",length=32, nullable=true ) **/
    protected $dhcpServer;




    public $permissions;

    public function __construct()
    {

    }
    public function getName(  ) {
    return $this->name;
    }
    public function setIPAddress( $ipAddress ) {
        $this->ipAddress = $ipAddress;
    }
    public function setMacAddress( $macAddress ) {
        $this->macAddress = $macAddress;
    }
    public function setType( $type ) {
        $this->type = $type;
    }
    public function setExpires( $expires ) {
        $this->expires = $expires;
    }

    public function getGateway() {
        return $this->gateway;
    }
    public function getExpires() {
        return $this->expires;
    }
    public function getCIDR() {
            return  $this->mask2cidr( $this->netmask );



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
    public function getUser() {
        if (! $this->userId ) {
            return new \Models\User;
        }
        $user = \App::entityManager()->find("\Models\User", $this->userId);
        if (! $user) {
            return new \Models\User;
        }
        return $user;

    }
    public function getIpRange( ) {

        $cidr = $this->getIpAddress() . "/" . $this->getCIDR();
        list($ip, $mask) = explode('/', $cidr);

        $maskBinStr =str_repeat("1", $mask ) . str_repeat("0", 32-$mask );      //net mask binary string
        $inverseMaskBinStr = str_repeat("0", $mask ) . str_repeat("1",  32-$mask ); //inverse mask

        $ipLong = ip2long( $ip );
        $ipMaskLong = bindec( $maskBinStr );
        $inverseIpMaskLong = bindec( $inverseMaskBinStr );
        $netWork = $ipLong & $ipMaskLong;

        //Handle /31s nicely
        if ( explode('/',$cidr)[1] == '31') {
            $start = $netWork ;//include network ID(eg: 192.168.1.0)

            $end = ($netWork | $inverseIpMaskLong); //include brocast IP(eg: 192.168.1.255)

        } else {
            $start = $netWork + 1;//ignore network ID(eg: 192.168.1.0)

            $end = ($netWork | $inverseIpMaskLong) - 1; //ignore brocast IP(eg: 192.168.1.255)
        }

        return array('firstIP' => $start, 'lastIP' => $end );
    }

    public function getEachIpInRange ( ) {
        $ips = array();
        $range = $this->getIpRange();
        for ($ip = $range['firstIP']; $ip <= $range['lastIP']; $ip++) {
            $ips[] = long2ip($ip);
        }
        return $ips;
    }
    public function setDHCPServer( $dhcpServer ) {
        $this->dhcpServer = $dhcpServer;
    }
    public function getMacAddress( $format=false) {
        if ($format) {
            $str = $this->formatMacAddress($this->macAddress);
            return $str;
        }
        return $this->macAddress;
    }

    public function getIPAddresses($onlyAssigned = false) {
        $output= array();
        $ips = $this->getEachIpInRange(  );
        $ip_list = "";



        $query = \App::entityManager()->createQuery("SELECT i FROM \Models\IP i WHERE i.ipAddress IN (?1)")
            ->setParameter(1,  $ips );

        $dbresults = $query->getResult();

        foreach( $dbresults as $ip_address ) {
            $output[$ip_address->getIPAddress()] = $ip_address;

        }
        if ( $onlyAssigned == false ) {
            foreach ($ips as $ip_address) {
                if (isset($output[$ip_address])) {
                    continue;
                }
                $output[$ip_address] = new \Models\IP();
                $output[$ip_address]->setIPAddress($ip_address);

            }
        }

        uksort( $output, function($a,$b) {
            return ip2long($a) >= ip2long($b);
        });

  return $output;

    }

    public function getIpAddress() {
        return $this->ipAddress;
    }
}