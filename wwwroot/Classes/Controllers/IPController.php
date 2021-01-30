<?php

namespace Controllers;

class IPController extends Controller {

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
        $router->map( 'GET', '/ip.[:format]?', '\Controllers\IPController#index', 'ip.index' );
        $router->map( 'GET', '/ip/[:id]', '\Controllers\IPController#show', 'ip.show' );

        \App::appendTopNav(array( 'group' => true, 'icon' => 'circle', 'text' => 'IP Addressing', 'routeName' => 'ip' ));
        \App::appendTopNav(array('parent' => 'ip', 'icon' => 'circle', 'text' => 'IP Addresses', 'routeName' => 'ip.index', 'link' => $router->generate('ip.index' ) ));


    }

    public function index( $params  = array() ) {

        $siteRepository = \App::entityManager()->getRepository('\Models\IP');
        $params['ips'] = $siteRepository->findAll();


        print \App::render('ips/index.twig', $params );

    }


    public function show( $params  = array() ) {

        $params['client'] = \App::entityManager()->find("\Models\Client", $params['id']);


        print \App::render('clients/show.twig', $params );


    }



}
