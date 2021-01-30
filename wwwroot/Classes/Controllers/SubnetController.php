<?php

namespace Controllers;

class SubnetController extends Controller {

    static function getByIPAddress( $ip ) {

        $query = \App::entityManager()->createQuery("SELECT i FROM \Models\IP i WHERE i.ipAddress LIKE ?1")
            ->setParameter(1,  $ip);

        $addresses = $query->getResult();

        if ( isset($addresses[0]) ) {
            return $addresses[0];
        } else {
             return null;
        }
    }
    static function getByMacAddress( $mac ) {

        $query = \App::entityManager()->createQuery("SELECT i FROM \Models\IP i WHERE i.macAddress LIKE ?1")
            ->setParameter(1,  $mac);

        $addresses = $query->getResult();

        if ( isset($addresses[0]) ) {
            return $addresses[0];
        } else {
            return null;
        }
    }
    static function registerRoutes( &$router)
    {
        $router->map( 'GET', '/subnet.[:format]?', '\Controllers\SubnetController#index', 'subnet.index' );
        $router->map( 'GET', '/subnet/[:id]', '\Controllers\SubnetController#show', 'subnet.show' );

        \App::appendTopNav(array('parent' => 'ip', 'icon' => 'circle', 'text' => 'Subnets', 'routeName' => 'subnet.index', 'link' => $router->generate('subnet.index' ) ));


    }

    public function index( $params  = array() ) {

        $siteRepository = \App::entityManager()->getRepository('\Models\Subnet');
        $params['subnets'] = $siteRepository->findAll();


        print \App::render('subnets/index.twig', $params );

    }


    public function show( $params  = array() ) {

        $params['subnet'] = \App::entityManager()->find("\Models\Subnet", $params['id']);


        print \App::render('subnets/show.twig', $params );


    }



}
