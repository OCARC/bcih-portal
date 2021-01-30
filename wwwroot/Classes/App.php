<?php


use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;


class App
{


        static $twigLoader;

        /**
         * @var $entityManager \Doctrine\ORM\EntityManager
         */
        static $entityManager;

        static $routeName;

        static $currentUser;

        static $topNav = array( );


        static $notices = array();




        function __construct( $conn, $isDevMode)
        {



            $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/Models"), $isDevMode);
// or if you prefer yaml or XML
//$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
//$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

// the connection configuration

// obtaining the entity manager
            App::$entityManager = EntityManager::create($conn, $config);




        }


        static function setCurrentUser( $user ) {
            App::$currentUser = $user;


        }

        /**
         * @return \Models\User
         */
        static function getCurrentUser( ) {
            return App::$currentUser;
        }
        static function setRouteName( $rname ) {
            App::$routeName = $rname;
        }
        static function setTopNav( $topNav) {
            App::$topNav = $topNav;
        }
        static function getTopNav( ) {
            return App::$topNav;
        }
        static function appendTopNav( $topNav) {
            App::$topNav[] = $topNav;

        }

        static function query($dql) {

            $query = App::$entityManager->createQuery($dql);
            return $query;
        }



        static function getRepository( $repository ) {
            return App::$entityManager->getRepository( $repository );
        }

        static function getOutputDirectory(  ) {
            return App::$output_directory;
        }
        static function getSignalServerDirectory(  ) {
            return App::$signal_server_directory;
        }

        static function entityManager() {
            return App::$entityManager;
        }

        static function render($template, $args = array()) {

            if ( isset( $args['format'] ) ) {
                if ($args['format'] == 'json') {
                    return json_encode($args);
                }
            }

            App::$twigLoader = new Twig\Loader\FilesystemLoader(getcwd() . '/resources/templates/');

            $args['routeName'] = App::$routeName;
            $args['currentUser'] = App::$currentUser;
            $args['topNav'] = App::getTopNav();
            if ( isset($_GET['print']) ) {
                $args['layout'] = "printLayout.twig";
            }

            $twig = new Twig\Environment(App::$twigLoader, array(
                'cache' => false
            ));
            $twig->addExtension(new Twig_Extensions_Extension_Date());

            $twigFunction = new Twig\TwigFunction('App', function($method) {
                return App::$method();
            });
            $twig->addFunction($twigFunction);

            return $twig->render($template, $args);
        }


        static function setNotice($message, $type='info') {
            App::$notices[] = array('message'=> $message, 'type' => $type);
        }

        static function setNotices($array ) {
            App::$notices = $array;
        }
        static function getNotices( $keep = false) {
            $ns = App::$notices;
            if ($keep === false ) {
                App::$notices = array();
            }
            return $ns;

    }

}