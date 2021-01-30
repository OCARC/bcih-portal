<?php
// src/Site.php

namespace Models;

use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity @Table(name="sites")
 **/
class Site extends Model
{

    /**
     * @var int
     */
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;

    /** @Column(type="integer", options={"default" : "0"})  **/
    protected $userId = 0;

    /** @Column(type="string") **/
    public $name;

    /** @Column(type="string") **/
    public $shortName;

    /** @Column(type="string", options={"default" : "public"}) **/
    public $visibility = "public";


    /** @Column(type="string", options={"default" : "red"}) **/
    protected $color = "red";

    /** @Column(type="text") **/
    public $description;

    /** @Column(type="decimal", precision=18, scale=12) **/
    public $latitude;

    /** @Column(type="decimal", precision=18, scale=12) **/
    public $longitude;

    /** @Column(type="integer", nullable=true)  **/
    protected $altitude;

    public function __construct()
    {
    }


public function getAltitude() {
        return $this->altitude;
}


    public function getId()
    {
        return $this->id;
    }
    public function getUserId()
    {
        return $this->userId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    public function getShortName($auto=true)
    {
        if ( strlen($this->shortName) == 0 && $auto == true) {
            return substr($this->name,0,12);
        }

        return $this->shortName;
    }

    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    }
    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }



    public function getLongitude()
    {
        return $this->longitude;
    }

    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }


    public function getColor()
    {
        return $this->color;
    }

    public function setColor($color)
    {
        $this->color = $color;
    }



    public function getEquipment() {
        $query = \App::entityManager()->createQuery('SELECT e FROM \Models\Equipment e WHERE e.siteId = ' . $this->id);
        $equipment = $query->getResult();
        return $equipment;
    }

    public function getClients() {
        $query = \App::entityManager()->createQuery('SELECT c FROM \Models\Client c WHERE c.siteId = ' . $this->id);
        $clients = $query->getResult();
        return $clients;
    }


    public function getWeatherMapHTML() {

        return file_get_contents("http://nms.if.hamwan.ca/plugins/Weathermap/output/site-lmk.html");
    }
}