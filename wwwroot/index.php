<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//use \SiteController;

require_once('bootstrap.php');


$router = new AltoRouter();

session_start();



\Controllers\HomeController::registerRoutes($router);
\Controllers\SitesController::registerRoutes($router);
\Controllers\UserController::registerRoutes($router);
\Controllers\ClientsController::registerRoutes($router);
\Controllers\EquipmentController::registerRoutes($router);
\Controllers\IPController::registerRoutes($router);
\Controllers\SubnetController::registerRoutes($router);




$match = $router->match();

// handke users here
//TODO: actually handle users
if ( isset($_SESSION['current_user_id']) ) {
    $current_user = \App::entityManager()->find("\Models\User", $_SESSION['current_user_id']);

} else {
    $current_user = new \Models\User();
}
//$current_user = \App::entityManager()->find("\Models\User", 1);

\App::setCurrentUser( $current_user );


App::setNotices( (isset($_SESSION['notices']) ? $_SESSION['notices'] : array()) );
App::setRouteName( $match['name'] );


if ($match === false) {
    header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    print \App::render("errors/404.twig");

} else {
    list( $controller, $action ) = explode( '#', $match['target'] );

    $obj = new $controller();
    if ( is_callable(array($controller, $action)) ) {

        if ( \App::getCurrentUser()->checkPermission( $match['name'] ) == false ) {
            if ( \App::getCurrentUser()->getId() == 0 ) {
                // No one is logged in send to login page instead
                header("Location: /login");
            } else {
                print call_user_func_array( array( new \Controllers\HomeController() ,'notAllowed'), array($match) );

            }
        } else {
            print call_user_func_array(array($obj, $action), array($match['params']));
        }
    } else {
        // here your routes are wrong.
        // Throw an exception in debug, send a  500 error in production
        header( $_SERVER["SERVER_PROTOCOL"] . ' 500 Function Not Found');
        print \App::render("errors/500.twig");
    }
}

$_SESSION['notices'] = \App::getNotices(true);