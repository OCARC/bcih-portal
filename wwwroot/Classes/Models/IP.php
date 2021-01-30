<?php
// src/Site.php

namespace Models;

/**
 * @Entity @Table(name="ips")
 **/
class IP extends Model
{

    use \Traits\Pingable;
    use \Traits\SNMPPollable;
    use \Traits\HelperFunctions;

    /**
     * @var int
     */
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;

    /** @Column(type="string",length=32, nullable=true ) **/
    protected $ipAddress;

    /** @Column(type="string",length=32, nullable=true ) **/
    protected $macAddress;

    /** @Column(type="string",length=64, nullable=true ) **/
    protected $hostname;

    /** @Column(type="string",length=32, nullable=true ) **/
    protected $gateway;

    /** @Column(type="string",length=32, nullable=true ) **/
    protected $netmask;


    /** @Column(type="string",length=32, nullable=true ) **/
    protected $type;

    /** @Column(type="string",length=32, nullable=true ) **/
    protected $dhcpServer;

    /** @Column(type="datetime", nullable=true ) **/
    protected $expires;

    public $permissions;

    public function __construct()
    {

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
    public function getType(  ) {
    return    $this->type;
}
    public function setExpires( $expires ) {
        $this->expires = $expires;
    }

    public function getExpires() {
        return $this->expires;
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

    public function getIpAddress() {
            return $this->ipAddress;
    }
}