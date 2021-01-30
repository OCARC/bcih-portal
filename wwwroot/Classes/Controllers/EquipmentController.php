<?php

namespace Controllers;

class EquipmentController extends Controller {

    static function registerRoutes( &$router)
    {
        $router->map( 'GET', '/equipment.[:format]?', '\Controllers\EquipmentController#index', 'equipment.index' );
        $router->map( 'GET', '/equipment/[:id]', '\Controllers\EquipmentController#show', 'equipment.show' );

        \App::appendTopNav(array('icon' => 'server', 'text' => 'Equipment', 'routeName' => 'equipment.index', 'link' => $router->generate('equipment.index' ) ));


    }

    public function index( $params  = array() ) {

        $siteRepository = \App::entityManager()->getRepository('\Models\Equipment');
        $params['equipment'] = $siteRepository->findAll();


        print \App::render('equipment/index.twig', $params );

    }


    public function show( $params  = array() ) {

        $params['equipment'] = \App::entityManager()->find("\Models\Equipment", $params['id']);


        print \App::render('equipment/show.twig', $params );


    }



}
