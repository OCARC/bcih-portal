<?php

chdir(__DIR__);
require_once( 'bootstrap.php');




if (! isset($argv[1]) ) {

        // Start forking
    $query = \App::entityManager()->createQuery('SELECT c FROM \Models\Client c  ');
    $clients = $query->getResult();


    foreach ($clients as $c) {
        exec( 'php pollClients.php ' . $c->id . " >> /tmp/pollingLog &" );

    }

} else {


    // Process client
    $c = \App::entityManager()->find("\Models\Client", $argv[1] );


    print pollLog('CLIENT', 'pingCheck', $c->getRadioName(), $c->pingCheck() );
    print pollLog('CLIENT', 'pollSNMP', $c->getRadioName(), $c->pollSNMP() );

}

function pollLog( $type, $action, $target, $result ) {

    print str_pad( $type, 15," " ) .str_pad( $action, 15," " ) .str_pad( $target, 15," " ) . " Result: " . str_pad( $result, 15," " ) ."\n";

}