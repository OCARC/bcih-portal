<?php

namespace Controllers;

class SitesController extends Controller {

    static function registerRoutes( &$router)
    {
        $router->map( 'GET', '/sites.[:format]?', '\Controllers\SitesController#index', 'sites.index' );
        $router->map( 'GET', '/sites/[:id]', '\Controllers\SitesController#show', 'sites.show' );

        \App::appendTopNav(array('icon' => 'broadcast-tower', 'text' => 'Sites', 'routeName' => 'sites.index', 'link' => $router->generate('sites.index' ) ));


    }

    public function index( $params  = array() ) {

        $siteRepository = \App::entityManager()->getRepository('\Models\Site');
        $params['sites'] = $siteRepository->findAll();


        print \App::render('sites/list.twig', $params );

    }


    public function show( $params  = array() ) {

        $params['site'] = \App::entityManager()->find("\Models\Site", $params['id']);


        print \App::render('sites/show.twig', $params );

    }


}
