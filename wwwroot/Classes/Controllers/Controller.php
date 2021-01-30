<?php

namespace Controllers;

class Controller   {

    static function registerRoutes( &$router)
    {


    }

    public function notAllowed( $match ) {
        header( $_SERVER["SERVER_PROTOCOL"] . ' 403 Forbidden ');

        print \App::render('errors/403.twig' );

    }
}