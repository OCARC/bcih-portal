<?php

// bootstrap.php

// TODO: make this work better

global $project_root;
$project_root =  getcwd();

spl_autoload_register(function ($className) {
    global $project_root;

    $namespaces = explode('\\', $className);
    if (count($namespaces) > 1) {
        $classPath = $project_root. "/Classes/" .  implode('/', $namespaces) . '.php';

        if (file_exists($classPath)) {
            require_once($classPath);

        }


    }
});

require_once "vendor/autoload.php";

require_once "Classes/App.php";






// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;

$conn = array(
    'driver'   => 'pdo_mysql',
    'user'     => 'portal',
    'password' => 'g00g1e',
    'host' => 'localhost',

    'dbname'   => 'portal',
);

$app = new \App( $conn, $isDevMode );

