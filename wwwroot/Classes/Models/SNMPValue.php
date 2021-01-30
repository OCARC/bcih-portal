<?php
// src/Site.php

namespace Models;

use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity @Table(name="snmpValues")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class SNMPValue extends Model
{

    /**
     * @var int
     */


    /** @Id @Column(type="integer", nullable=true)  **/
    protected $classId = 0;

    /** @Id @Column(type="string") **/
    public $classType;

    /** @Id @Column(type="string") **/
    public $oid;

    /** @Column(type="string") **/
    public $keyName;

    /** @Column(type="string", nullable=true) **/
    public $value;



    public function __construct()
    {

    }


    public function setClassId( $classId ) {
        $this->classId = $classId;
    }
    public function setClassType( $classType ) {
        $this->classType = $classType;
    }
    public function setKeyName( $keyName ) {
        $this->keyName = $keyName;
    }
    public function getKeyName() {
        return $this->keyName;
    }
    public function setValue( $value ) {
        $this->value = $value;
    }
    public function getValue() {
        return $this->value;
    }
    public function setOID( $OID ) {
        $this->oid = $OID;
    }
    public function getOID() {
        return $this->oid;
    }


    }
