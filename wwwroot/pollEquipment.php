<?php

chdir(__DIR__);
require_once( 'bootstrap.php');




if (! isset($argv[1]) ) {

    // Start forking
    $query = \App::entityManager()->createQuery('SELECT e FROM \Models\Equipment e  ');
    $equipment = $query->getResult();


    foreach ($equipment as $e ) {
        exec( 'php pollEquipment.php ' . $e->id . " >> /tmp/pollingLog &" );

    }

} else {


    // Process client
    $e = \App::entityManager()->find("\Models\Equipment", $argv[1] );

    print pollLog('EQUIPMENT', 'pingCheck', $e->getHostname(), $e->pingCheck() );
    print pollLog('EQUIPMENT', 'pollSNMP', $e->getHostname(), $e->pollSNMP() );
    print pollLog('EQUIPMENT', 'discoverClients', $e->getHostname(), $e->discoverClients() );
    print pollLog('EQUIPMENT', 'pollDHCP', $e->getHostname(), $e->pollDHCP() );
}

function pollLog( $type, $action, $target, $result ) {

    print str_pad( $type, 15," " ) .str_pad( $action, 15," " ) .str_pad( $target, 15," " ) . " Result: " . str_pad( $result, 15," " ) ."\n";

}