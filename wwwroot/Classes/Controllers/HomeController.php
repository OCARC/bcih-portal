<?php

namespace Controllers;

class HomeController extends Controller {

    static function registerRoutes( &$router)
    {
        $router->map( 'GET', '/', '\Controllers\HomeController#index', 'home.index' );
        \App::appendTopNav(array('icon' => 'home', 'text' => 'Home', 'routeName' => 'home.index', 'link' => $router->generate('home.index' ) ));

    }

    public function index() {




        print \App::render('home/index.twig', array()  );

    }


    public function notAllowed( $match ) {
        header( $_SERVER["SERVER_PROTOCOL"] . ' 403 Forbidden ');

        print \App::render('errors/403.twig' );

    }
}
