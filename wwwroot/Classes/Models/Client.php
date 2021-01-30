<?php
// src/Site.php

namespace Models;

use Controllers\IPController;

/**
 * @Entity @Table(name="clients")
 **/
class Client extends Model
{

    use \Traits\Pingable;
    use \Traits\SNMPPollable;
    use \Traits\HelperFunctions;
    use \Traits\DeviceIcon;

    /**
     * @var int
     */
    /** @Id @Column(type="integer") @GeneratedValue **/
    public $id;

    /**  @Column(type="integer", nullable=true) **/
    protected $siteId;
    /**  @Column(type="integer", nullable=true) **/
    protected $equipmentId;

    /** @Column(type="string",length=32, nullable=true, options={"default": "First"}) **/
    protected $macAddress;

    /** @Column(type="string", length=128, nullable=true, options={"default": "First"}) **/
    protected $radioName;


    /** @Column(type="string", length=128, nullable=true) **/
    protected $managementIp;

    public $permissions;

    public function __construct()
    {



    }


    public function getMacAddress( $format=false) {
        if ($format) {
            $str = $this->formatMacAddress($this->macAddress);
            return $str;
        }
        return $this->macAddress;
    }

    public function setMacAddress( $macAddress ) {
        $this->macAddress = $macAddress;
    }
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }
    public function getRadioModel() {
        $snmpValues = $this->getSNMPValues();

        if ( !isset( $snmpValues['OID_SYSDESC']) ) {
            return null;
        }
        $snmpValue = $snmpValues['OID_SYSDESC'];

        return str_replace('RouterOS ', '', $snmpValue->getValue() );


    }

    public function getOS() {

        return "RouterOS";
    }

    public function getSignalStrength( $format = false) {
        $snmpValues = $this->getSNMPValues();

        if ( !isset( $snmpValues['OID_REG_TABLE_SIGNAL_STRENGTH']) ) {
            return null;
        }
        $snmpValue = $snmpValues['OID_REG_TABLE_SIGNAL_STRENGTH'];
            if ($format) {
                return $snmpValue->getValue() . " dBm";;
            } else {
                return $snmpValue->getValue();


            }

    }

    public function getStrength( $format = false, $channel = 0, $direction = 'Rx') {
        $snmpValues = $this->getSNMPValues();

        if ( !isset( $snmpValues['OID_mtxrWlRtab' . $direction . 'StrengthCh' . $channel]) ) {
            return null;
        }
        $snmpValue = $snmpValues['OID_mtxrWlRtab' . $direction . 'StrengthCh' . $channel];
        if ($format) {
            return $snmpValue->getValue() . " dBm";
        } else {
            return $snmpValue->getValue();


        }

    }

    public function getRate( $format = false, $direction = 'Rx') {
        $snmpValues = $this->getSNMPValues();

        if ( !isset( $snmpValues['OID_REG_TABLE_'. strtoupper($direction).'_RATE']) ) {
            return null;
        }
        $snmpValue = $snmpValues['OID_REG_TABLE_'. strtoupper($direction).'_RATE'];
        if ($format) {
            return ($snmpValue->getValue()/1000/1000) . " Mbps";;
        } else {
            return $snmpValue->getValue();


        }

    }


    public function getSNR( $format = false) {
    $snmpValues = $this->getSNMPValues();

    if ( !isset( $snmpValues['OID_mtxrWlRtabSignalToNoise']) ) {
        return null;
    }
    $snmpValue = $snmpValues['OID_mtxrWlRtabSignalToNoise'];
            if ($format) {
                return $snmpValue->getValue() . " dB";;
            } else {
                return $snmpValue->getValue();


            }

}

    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }
    public function setEquipmentId($equipmentId)
    {
        $this->equipmentId = $equipmentId;
    }
    public function setRadioName($radioName)
    {
        $this->radioName = $radioName;
    }
    public function getRadioName() {
        return $this->radioName;
    }
    public function getLevel()
    {
        return $this->level;
    }

    public function getManagementIp( $discover = false) {
       if ( $this->managementIp ) {
           return $this->managementIp;
       }

       if ( $discover ) {
           $ip = IPController::getByMacAddress( $this->getMacAddress() );
           if ($ip) {
               return $ip->getIpAddress();
           }
       }

       return null;
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
    public function getProjections() {
        // TODO: filter by user
        $projectionsRepository = \App::entityManager()->getRepository('\Models\Projection');
        $projections = $projectionsRepository->findAll();

        return $projections;
    }
    public function getSite() {
        $site = \App::entityManager()->find("\Models\Site", $this->siteId);
        if (! $site) {
            return new \Models\Site;
        }
        return $site;

    }
}