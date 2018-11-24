<?php
/**
 * Created by PhpStorm.
 * User: steph
 * Date: 11/7/2017
 * Time: 9:07 AM
 */

namespace App\Http\Controllers;
//use App\FreqTrack;
use Nelisys\Snmp;
use JJG\Ping;

class UtilitiesController
{
    public function aim() {
        return view('utilities.aim');

    }
    function snmpint($snmpstr) {
        return (int) explode(' ', $snmpstr)[1];
    }
    function calculate_median($arr) {
        $count = count($arr); //total numbers in array
        $middleval = floor(($count-1)/2); // find the middle value, or the lowest middle value
        if($count % 2) { // odd number, middle is the median
            $median = $arr[$middleval];
        } else { // even number, calculate avg of 2 medians
            $low = $arr[$middleval];
            $high = $arr[$middleval+1];
            $median = (($low+$high)/2);
        }
        return $median;
    }
    public function aimRSSI()
    {
        $hostname = $_GET['host'];
        $community = 'hamwan';
        $oidrssi = '1.3.6.1.4.1.14988.1.1.1.1.1.4.2';
        $oidrssiac = '1.3.6.1.4.1.14988.1.1.1.1.1.4.3';
        $linkrssi = '1.3.6.1.4.1.14988.1.1.1.2.1.3';
        $output = array(
            'host' => $hostname,
            'rssi' => NULL,
        );

// try each oid
        foreach (array($oidrssiac, $oidrssi, $linkrssi) as $oid) {
            $snmp = new Snmp($hostname, $community, "1");
            if ( $oid ==$linkrssi ) {
                $rssi = $snmp->walk($oid);
                if ( isset($rssi[0]) ) {
                    $rssi = array( $linkrssi => $rssi[0] );
                }
            } else {
                $rssi = $snmp->get($oid);
            }
            $rssi = -array_shift($rssi);
            if ($rssi) {
                // smoothing
                $avg[0] = $rssi;
                for ($i = 1; $i < 3; $i++) {
                    usleep(200000);
                    //$avg[$i] = $snmp->get($oid);


                    if ( $oid ==$linkrssi ) {
                        $avg[$i]  = $snmp->walk($oid);
                        if ( isset($rssi[0]) ) {
                            $avg[$i]  = array( $linkrssi => $rssi[0] );
                        }
                    } else {
                        $avg[$i]  = $snmp->get($oid);
                    }

                    $avg[$i] = -array_shift($avg[$i]);
                }
                    $output['rssi'] = $this->calculate_median($avg);
                    break;
                }
            }
            header('Content-Type: application/json');
            echo json_encode($output);
        }

    public function recacheCoverages() {
        return view('utilities.recacheCoverages');

    }

    public function routerOSUpgradeManager() {
        return view('utilities.routerosupdate', ["clients" => \App\Client::all(), "equipment" =>  \App\Equipment::all()->where('os','=','RouterOS')->where('status','!=','Planning')->where('status', '!=', 'Potential')]);

    }
    public function routerOSConfigCheck() {
        return view('utilities.routerosconfigcheck', ["equipment" =>  \App\Equipment::all()->where('os','=','RouterOS')]);

    }
    public function frequencyPlanning() {
//        return view('utilities.frequencyPlanning', ["sites" =>  \App\Site::all(), "freqtrack" =>  \App\FreqTrack::all() ]);

    }

    public function equipmentSNMP() {
        return view('utilities.equipmentSNMP', ["equipment" =>  \App\Equipment::all() ]);


    }

    public function ping() {

        $host = $_GET['host'];
        if ( isset( $_GET['count'] ) ) {
            $count = $_GET['count']/1;

        } else {
            $count = 1;
        }

        $ping = new Ping($host);
        $ping->setTimeout(1);
        $latency = $ping->ping();
        if ($latency !== false) {
            $ret = array(
                'count' => $count,
                'max' => $latency,
                'average' => $latency,
                'min' => $latency,
                'online' => true,
            );
        }
        else {
            $ret = array(
                'count' => $count,
                'max' => $latency,
                'average' => $latency,
                'min' => $latency,
                'online' => false,
            );        }


        return json_encode( $ret );
    }


    public function ingestScan() {


        if (($handle = fopen('php://input', "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                FreqTrack::where('mac', $data[0])->where('radio_name', $_GET['host'])->delete();

                $f = new FreqTrack();



                $f->mac = $data[0];
                $f->ssid = $data[1];
                $f->channel = $data[2];
                $f->frequency = explode("/",$data[2])[0];
                $f->channel_width = explode("/",$data[2])[1];
                $f->signal = $data[3];
                $f->protocol = $data[4];
                $f->radio_name = $_GET['host'];
                $f->site_code = explode(".",$_GET['host'])[1];

                $f->save();

            }
            fclose($handle);
        }

        return "ddd";
    }
}