<?php
// src/Site.php

namespace Models;

/**
 * @Entity @Table(name="users")
 **/
class User extends Model
{
    /**
     * @var int
     */
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;


    /** @Column(type="string") **/
    protected $username;

    /** @Column(type="string") **/
    protected $password;

    /** @Column(type="string", options={"default": "First"}) **/
    protected $firstName = "First";

    /** @Column(type="string", options={"default": "Last"}) **/
    protected $lastName = "Last";


    /** @Column(type="string") **/
    protected $email = "red";

    /** @Column(type="string", options={"default": "User"}) **/
    protected $level;

    public $permissions;

    public function __construct()
    {



    }

    public function checkPassword( $passwordInput = "") {


        if ( md5($passwordInput) == $this->password ) {
            return true;
        }

        return false;
    }

    public function checkPermission($permission) {
        $permissions= $this->getPermissions();


        if ( isset($permissions[$permission]) === true ) {
            if ( $permissions[$permission] === true ) {
                return true;
            }
        }

        if ( isset($permissions['Super Administrator']) === true ) {
            if ( $permissions['Super Administrator'] === true ) {
                \App::setNotice("You performed an action without the required permission ($permission) and it was allowed because you are a Super Administrator", 'warning' );
                return true;
            }
        }

        return false;

    }

    public function getPermissions() {
        if ( ! isset( $this->permissions ) ) {
            // Build permissions

            $this->permissions['overlays.live'] = true;

            if ( $this->getLevel() == '' ) {
                $this->permissions['home.index'] = true;
                $this->permissions['users.login'] = true;
                $this->permissions['users.register'] = true;
                $this->permissions['users.login_post'] = true;

                $this->permissions['clients.index'] = true;
                $this->permissions['sites.index'] = true;
                $this->permissions['sites.show'] = true;



            }

            if ( $this->getLevel() == 'User' ) {
                $this->permissions['sdf.index'] = true;
                $this->permissions['home.index'] = true;

                $this->permissions['users.logout'] = true;

                $this->permissions['projections.show'] = true;
                $this->permissions['projections.create'] = true;
                $this->permissions['projections.edit'] = true;
                $this->permissions['projections.save'] = true;
                $this->permissions['projections.getFile'] = true;
                $this->permissions['projections.explore'] = true;

                $this->permissions['sites.index'] = true;
                $this->permissions['sites.show'] = true;
                $this->permissions['sites.create'] = true;
                $this->permissions['sites.edit'] = true;
                $this->permissions['sites.save'] = true;

                $this->permissions['projections.index'] = true;
                $this->permissions['projections.show'] = true;
                $this->permissions['projections.save'] = true;
                $this->permissions['projections.getFile'] = true;

                $this->permissions['equipment.index'] = true;
                $this->permissions['equipment.show'] = true;
                $this->permissions['equipment.create'] = true;
                $this->permissions['equipment.edit'] = true;
                $this->permissions['equipment.save'] = true;

                $this->permissions['maps.index'] = true;
                $this->permissions['links.index'] = true;
                $this->permissions['links.create'] = true;
                $this->permissions['links.edit'] = true;
                $this->permissions['links.save'] = true;
                $this->permissions['links.show'] = true;
                $this->permissions['links.getFile'] = true;

                $this->permissions['overlays.index'] = true;
                $this->permissions['overlays.show'] = true;
                $this->permissions['overlays.getFile'] = true;

            }

            if ( $this->getLevel() == 'Administrator' ) {

                $this->permissions['Super Administrator'] = true;

                $this->permissions['home.index'] = true;

                $this->permissions['users.index'] = true;
                $this->permissions['users.logout'] = true;

                $this->permissions['sites.index'] = true;
                $this->permissions['sites.show'] = true;
                $this->permissions['sites.create'] = true;
                $this->permissions['sites.edit'] = true;
                $this->permissions['sites.save'] = true;

                $this->permissions['clients.index'] = true;


                $this->permissions['projections.index'] = true;
                $this->permissions['projections.show'] = true;
                $this->permissions['projections.save'] = true;
                $this->permissions['projections.getFile'] = true;
                $this->permissions['projections.explore'] = true;

                $this->permissions['equipment.index'] = true;
                $this->permissions['equipment.show'] = true;
                $this->permissions['equipment.create'] = true;
                $this->permissions['equipment.edit'] = true;
                $this->permissions['equipment.save'] = true;

                $this->permissions['links.index'] = true;
                $this->permissions['links.show'] = true;
                $this->permissions['links.getFile'] = true;

                $this->permissions['antennas.index'] = true;
                $this->permissions['antennas.show'] = true;

                $this->permissions['users.index'] = true;
                $this->permissions['users.show'] = true;

                $this->permissions['jobs.index'] = true;
                $this->permissions['jobs.show'] = true;


                $this->permissions['projections.index'] = true;
                $this->permissions['projections.show'] = true;
                $this->permissions['projections.getFile'] = true;

                $this->permissions['sites.index'] = true;
                $this->permissions['sites.show'] = true;

                $this->permissions['equipment.index'] = true;
                $this->permissions['equipment.show'] = true;
                $this->permissions['equipment.edit'] = true;

                $this->permissions['maps.index'] = true;

                $this->permissions['overlays.index'] = true;
                $this->permissions['overlays.show'] = true;
                $this->permissions['overlays.getFile'] = true;

            }

        }

        return $this->permissions;
    }

    private function calculatePermissions() {

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
}