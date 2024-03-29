<?php namespace App\Traits;

use Nelisys\Snmp;

trait DAEnetIP4
{



    public function doDenkoviCurrentState() {


        $snmp = new Snmp($this->management_ip, ( $this->snmp_community ) ? $this->snmp_community : 'hamwan', "2c" );


        $r = $snmp->get(
            array(
                '-r 1 -t 1 ', // Hack to limit timeout to 1 s and retries to 1
                '.1.3.6.1.4.1.42505.1.2.2.1.1.0',
    '.1.3.6.1.4.1.42505.1.2.2.1.1.1',
    '.1.3.6.1.4.1.42505.1.2.2.1.1.2',
    '.1.3.6.1.4.1.42505.1.2.2.1.1.3',
    '.1.3.6.1.4.1.42505.1.2.2.1.1.4',
    '.1.3.6.1.4.1.42505.1.2.2.1.1.5',
    '.1.3.6.1.4.1.42505.1.2.2.1.1.6',
    '.1.3.6.1.4.1.42505.1.2.2.1.1.7',
    '.1.3.6.1.4.1.42505.1.2.2.1.2.0',
    '.1.3.6.1.4.1.42505.1.2.2.1.2.1',
    '.1.3.6.1.4.1.42505.1.2.2.1.2.2',
    '.1.3.6.1.4.1.42505.1.2.2.1.2.3',
    '.1.3.6.1.4.1.42505.1.2.2.1.2.4',
    '.1.3.6.1.4.1.42505.1.2.2.1.2.5',
        '.1.3.6.1.4.1.42505.1.2.2.1.2.6',
    '.1.3.6.1.4.1.42505.1.2.2.1.2.7',
        '.1.3.6.1.4.1.42505.1.2.2.1.3.0',
    '.1.3.6.1.4.1.42505.1.2.2.1.3.1',
    '.1.3.6.1.4.1.42505.1.2.2.1.3.2',
    '.1.3.6.1.4.1.42505.1.2.2.1.3.3',
    '.1.3.6.1.4.1.42505.1.2.2.1.3.4',
    '.1.3.6.1.4.1.42505.1.2.2.1.3.5',
    '.1.3.6.1.4.1.42505.1.2.2.1.3.6',
    '.1.3.6.1.4.1.42505.1.2.2.1.3.7',
    '.1.3.6.1.4.1.42505.1.2.2.1.4.0',
    '.1.3.6.1.4.1.42505.1.2.2.1.4.1',
    '.1.3.6.1.4.1.42505.1.2.2.1.4.2',
    '.1.3.6.1.4.1.42505.1.2.2.1.4.3',
    '.1.3.6.1.4.1.42505.1.2.2.1.4.4',
    '.1.3.6.1.4.1.42505.1.2.2.1.4.5',
    '.1.3.6.1.4.1.42505.1.2.2.1.4.6',
    '.1.3.6.1.4.1.42505.1.2.2.1.4.7',
    '.1.3.6.1.4.1.42505.1.2.2.1.5.0',
    '.1.3.6.1.4.1.42505.1.2.2.1.5.1',
    '.1.3.6.1.4.1.42505.1.2.2.1.5.2',
    '.1.3.6.1.4.1.42505.1.2.2.1.5.3',
    '.1.3.6.1.4.1.42505.1.2.2.1.5.4',
    '.1.3.6.1.4.1.42505.1.2.2.1.5.5',
    '.1.3.6.1.4.1.42505.1.2.2.1.5.6',
    '.1.3.6.1.4.1.42505.1.2.2.1.5.7',
    '.1.3.6.1.4.1.42505.1.2.2.1.6.0',
    '.1.3.6.1.4.1.42505.1.2.2.1.6.1',
    '.1.3.6.1.4.1.42505.1.2.2.1.6.2',
    '.1.3.6.1.4.1.42505.1.2.2.1.6.3',
    '.1.3.6.1.4.1.42505.1.2.2.1.6.4',
    '.1.3.6.1.4.1.42505.1.2.2.1.6.5',
    '.1.3.6.1.4.1.42505.1.2.2.1.6.6',
    '.1.3.6.1.4.1.42505.1.2.2.1.6.7',
    '.1.3.6.1.4.1.42505.1.2.2.1.7.0',
    '.1.3.6.1.4.1.42505.1.2.2.1.7.1',
    '.1.3.6.1.4.1.42505.1.2.2.1.7.2',
    '.1.3.6.1.4.1.42505.1.2.2.1.7.3',
    '.1.3.6.1.4.1.42505.1.2.2.1.7.4',
    '.1.3.6.1.4.1.42505.1.2.2.1.7.5',
    '.1.3.6.1.4.1.42505.1.2.2.1.7.6',
    '.1.3.6.1.4.1.42505.1.2.2.1.7.7',
    '.1.3.6.1.4.1.42505.1.2.2.1.8.0',
    '.1.3.6.1.4.1.42505.1.2.2.1.8.1',
    '.1.3.6.1.4.1.42505.1.2.2.1.8.2',
    '.1.3.6.1.4.1.42505.1.2.2.1.8.3',
    '.1.3.6.1.4.1.42505.1.2.2.1.8.4',
    '.1.3.6.1.4.1.42505.1.2.2.1.8.5',
    '.1.3.6.1.4.1.42505.1.2.2.1.8.6',
    '.1.3.6.1.4.1.42505.1.2.2.1.8.7',
    '.1.3.6.1.4.1.42505.1.2.2.1.9.0',
        '.1.3.6.1.4.1.42505.1.2.2.1.9.1',
        '.1.3.6.1.4.1.42505.1.2.2.1.9.2',
        '.1.3.6.1.4.1.42505.1.2.2.1.9.3',
        '.1.3.6.1.4.1.42505.1.2.2.1.9.4',
        '.1.3.6.1.4.1.42505.1.2.2.1.9.5',
        '.1.3.6.1.4.1.42505.1.2.2.1.9.6',
        '.1.3.6.1.4.1.42505.1.2.2.1.9.7',
        '.1.3.6.1.4.1.42505.1.2.2.1.10.0',
        '.1.3.6.1.4.1.42505.1.2.2.1.10.1',
        '.1.3.6.1.4.1.42505.1.2.2.1.10.2',
        '.1.3.6.1.4.1.42505.1.2.2.1.10.3',
        '.1.3.6.1.4.1.42505.1.2.2.1.10.4',
        '.1.3.6.1.4.1.42505.1.2.2.1.10.5',
        '.1.3.6.1.4.1.42505.1.2.2.1.10.6',
        '.1.3.6.1.4.1.42505.1.2.2.1.10.7'

            )
        );

        $output['CurrentState']['DigitalInput'] = array();
        $output['CurrentState']['AnalogInput'] = array();
        $output['CurrentState']['Output'] = array();
        $output['CurrentState']['PWM'] = array();
        // Get Names
        for ($x = 0; $x <= 7; $x++) {
            $output['CurrentState']['AnalogInput'][$x]['Name'] = $r['.1.3.6.1.4.1.42505.1.2.2.1.2.' . $x];
        }
        // Get Values
        for ($x = 0; $x <= 7; $x++) {
            $output['CurrentState']['AnalogInput'][$x]['Value'] = $r['.1.3.6.1.4.1.42505.1.2.2.1.6.' . $x];
        }
        // Get Measure
        for ($x = 0; $x <= 7; $x++) {
            $output['CurrentState']['AnalogInput'][$x]['Measure'] = $r['.1.3.6.1.4.1.42505.1.2.2.1.10.' . $x];
        }
//        $url = $this->management_ip;
//        $password = 'fl0rida1';
//
//        // create curl resource
//        $ch = curl_init();
//
//        if (! $_SERVER['QUERY_STRING'] ) {
//            $_SERVER['QUERY_STRING'] = "1=1";
//        }
//        // set url
//        curl_setopt($ch, CURLOPT_URL, "http://" . $url . "/current_state.json?" . $_SERVER['QUERY_STRING'] . "&pw=" . $password );
//        //return the transfer as a string
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//
//
//        // $output contains the output string
//        $output = curl_exec($ch);
//
//        // close curl resource to free up system resources
//        curl_close($ch);
//        $output = str_replace('!DEF','',$output);

//        print_r($r);
        return json_encode($output);
    }

}