<?php

namespace Controllers;

class ClientsController extends Controller {

    static function getClientByMac( $mac ) {

        $query = \App::entityManager()->createQuery("SELECT c FROM \Models\Client c WHERE c.macAddress LIKE ?1")
            ->setParameter(1,  $mac);

        $clients = $query->getResult();

        if ( isset($clients[0]) ) {
            return $clients[0];
        } else {
             return null;
        }
    }

    static function registerRoutes( &$router)
    {
        $router->map( 'GET', '/clients.[:format]?', '\Controllers\ClientsController#index', 'clients.index' );
        $router->map( 'GET', '/clients/[:id]', '\Controllers\ClientsController#show', 'clients.show' );

        \App::appendTopNav(array('icon' => 'satellite-dish', 'text' => 'Clients', 'routeName' => 'clients.index', 'link' => $router->generate('clients.index' ) ));


    }

    public function index( $params  = array() ) {

        $siteRepository = \App::entityManager()->getRepository('\Models\Client');
        $params['clients'] = $siteRepository->findAll();


        print \App::render('clients/list.twig', $params );

    }


    public function show( $params  = array() ) {

        $params['client'] = \App::entityManager()->find("\Models\Client", $params['id']);


        print \App::render('clients/show.twig', $params );


    }



}
