<?php
// src/Site.php

namespace Models;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\MappedSuperclass
 * @ORM\Entity()

 */
abstract class Model
{
    /** @Column(type="datetime", nullable=true)  **/
    protected $createdAt;
    /** @Column(type="datetime", nullable=true)  **/
    protected $updatedAt;

    protected $id;

    public function __toArray() {
        return array('d'=>'d');
        return get_object_vars($this);
    }

    public function autoFill($data = array()) {

        foreach( $data as $field => $value ) {

            $method = 'set' . ucwords($field);
            if ( method_exists ($this, $method) ) {
                $this->$method( $value );
            }

        }
    }

    public function getID() {
        return $this->id;
    }





    public function canView() {

        if ( method_exists($this, 'getVisibility') ) {
            if ( $this->getVisibility() == 'public') {
                // Is a public object
                return true;
            }
        }

        if ( method_exists($this, 'getUserId') ) {
            if ( $this->getUserId() == \App::getCurrentUser()->getId() ) {
                // Current user owns it
                return true;
            }
        }
        return null;
    }

    public function canEdit() {


        if ( method_exists($this, 'getUserId') ) {
            if ( $this->getUserId() == \App::getCurrentUser()->getId() ) {
                // Current user owns it
                return true;
            }
        }
        return null;
    }

    public function canDelete()
    {

        if (\App::getCurrentUser()->checkPermission("Super Administrator") === true) {
            return true;
        }

        if ( method_exists($this, 'getUserId') ) {
            if ( $this->getUserId() == \App::getCurrentUser()->getId() ) {
                // Current user owns it
                return true;
            }
            if ( $this->getUserId() == 0 ) {
                // nobody owns it
                return true;
            }
        }
        return null;
    }
    public function getOwnerUsername() {


        if ( method_exists($this, 'getUserId') ) {
            $userID = $this->getUserId();
            $user = \App::entityManager()->find("\Models\User", $userID);
            if ( $user ) {
                return $user->getUsername();
            } else {
                return 'Unknown';
            }
        }
        return null;
    }
    public function getOwnerUserID() {


        if ( method_exists($this, 'getUserId') ) {
            $userID = $this->getUserId();
            return $userID;
        }
        return null;
    }

    public function getUserId() {

        return 1;
    }

    public function getVisibility() {
        return 'public';
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
        print "created";
    }
    public function getCreatedAt() {
        return $this->createdAt;
    }
    /**
     * @ORM\PreUpdate()
     */
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTime();
        print "updated";
    }
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

}