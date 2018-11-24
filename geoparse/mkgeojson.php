<?php

//
//
//var sites = {'LMK' : ['000','120','240'],'BGM' : ['120'],'KUI' : ['000','240'],'BKM' : ['000','240']};
//var futureSites = {'TUR': ['000','120','240'], 'BKM' : ['120']};
//var sectors = ['000','120','240'];


$size = 100;
$resolution = 0.00001;
$centerLat = 49.879;
$centerLon = -119.460;


$output = array();



for ($x = $centerLat- ($size*$resolution) ; $x <= $centerLat + ($size*$resolution) ; $x = $x + $resolution) {
    for ($y = $centerLon- ($size*$resolution) ; $y <= $centerLon + ($size*$resolution) ; $y = $y + $resolution) {

        print "$x, $y\n";

        usleep(100);
    }
}



?>