<?php

namespace App\Http\Controllers;

use App\DhcpLease;
use App\Equipment;
use App\LogEntry;
use Illuminate\Http\Request;
use Nelisys\Snmp;

class DhcpLeaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $leases = DHCPLease::all()->sortBy('ends',SORT_REGULAR,true);
//        if ( $request->json ) {
//            return $users;
//        } else {
//            return view('users.index', compact('users'));
//
//        }
        return view('iplease.index', compact('leases'));
        //
    }

    public function refresh()
    {
        //
        // Update DHCP Table
//        $leases = json_decode(file_get_contents("http://44.135.216.2:3000/api/get_active_leases"),true);
//
//        foreach( $leases as $ip => $lease ) {
//            //$hash = md5( $leases['ip'] . $lease['start'] . $lease['end'] . $lease['host'] . $leases['mac']);
//            $l =  DhcpLease::where('mac_address', str_replace(":", "", $lease['mac']))->first();
//
//            if( $l ) {
//                // Update
//
//                if ( $l->ip != $ip || $l->mac_address != $lease['mac']) {
//                    $l->removeDNS();
//                }
//
//                $l->fill([
//                    'id' => $l->id,
//                    'owner' => -1,
//                    'ip' => $ip,
//                    'hostname' => ( isset($lease['host']) ? $lease['host'] : ''),
//                    'mac_oui_vendor' => ( isset($lease['mac_oui_vendor']) ? $lease['mac_oui_vendor'] : ''),
//                    'mac_address' => str_replace(":", "", $lease['mac']),
//                    'dhcp_server' => '44.135.216.2',
//                    'starts' => $lease['start'],
//                    'ends' => $lease['end'],
//                ]);
//                $l->save();
//                $l->updateDNS();
//            } else {
//                $lease = \App\DhcpLease::create([
//                    'owner' => -1,
//                    'ip' => $ip,
//                    'hostname' => ( isset($lease['host']) ? $lease['host'] : ''),
//                    'mac_oui_vendor' => ( isset($lease['mac_oui_vendor']) ? $lease['mac_oui_vendor'] : ''),
//                    'mac_address' => str_replace(":", "", $lease['mac']),
//                    'dhcp_server' => '44.135.216.2',
//                    'starts' => $lease['start'],
//                    'ends' => $lease['end'],
//                ]);
//                $lease->updateDNS();
//            }
//
////            $db->doQuery("REPLACE INTO `dhcp_leases` (starts,ends,mac_address,client_hostname,dhcp_server,ip,hash) VALUES (FROM_UNIXTIME(?),FROM_UNIXTIME(?),?,?,?,?,?)",
////                array( $lease['start'],
////                    $lease['end'],
////                    str_replace(":","",$lease['mac']),
////                    $lease['host'],
////                    '44.135.216.2',
////                    $ip,
////                    $hash
////                ) );
//        }


        // New Way
    $servers = array('10.246.1.1','10.246.2.1','10.246.3.1','10.246.4.1','10.246.5.1');
        $hosts = array();

        $equipment = Equipment::where('dhcp_server',true)->get();
        foreach( $equipment as $e ) {
            $server = $e->management_ip;

            $snmp = new Snmp($server, 'hamwan', "2c" );

            $r = $snmp->walk(
                ".1.3.6.1.2.1.9999.1.1.6.4.1"
            );
            // Reshape

                foreach( $r as $key=>$value ) {
                    preg_match ('/(\.1\.3\.6\.1\.2\.1\.9999\.1\.1\.6\.4\.1)\.(.)\.(.+)/', $key,$parts );

                    if ($parts) {
                        if ($parts[2] == 5) {
                            $hosts[$server . "-" . $parts[3]]['ttl'] = $value;
                            $hosts[$server . "-" . $parts[3]]['ip'] = $parts[3];
                            $hosts[$server . "-" . $parts[3]]['server'] = $server;
                            $hosts[$server . "-" . $parts[3]]['equipment_id'] = $e->id;
                        }

                        if ($parts[2] == 8) {
                            $hosts[$server . "-" . $parts[3]]['mac'] = str_replace(" ", "", substr($value, 5));
                            $hosts[$server . "-" . $parts[3]]['ip'] = $parts[3];
                            $hosts[$server . "-" . $parts[3]]['server'] = $server;
                            $hosts[$server . "-" . $parts[3]]['equipment_id'] = $e->id;


                        }
                    }
                }



        }

        // Update
        foreach ($hosts as $host) {
            $l = DhcpLease::where('mac_address', $host['mac'])->where('dhcp_server', $host['server'])->first();

            if ($l) {
                // Update
                if ( $l->ip != $host['ip'] || $l->mac_address != $l['mac']) {
                    $l->removeDNS();
                }
                $l->fill([
                    'id' => $l->id,
                    'owner' => -1,
                    'ip' => $host['ip'],
                    'hostname' => '',
                    'mac_oui_vendor' => 'unk',
                    'mac_address' => $host['mac'],
                    'dhcp_server' => $host['server'],
                    'ends' => time() + $host['ttl'],
                ]);
                $l->save();
                $l->updateDNS();
            } else {
                $l = \App\DhcpLease::create([
                    'owner' => -1,
                    'ip' => $host['ip'],
                    'hostname' => '',
                    'mac_oui_vendor' => 'unk',
                    'mac_address' => $host['mac'],
                    'dhcp_server' => $host['server'],
                    'starts' => time(),

                    'ends' => time() + $host['ttl'],
                ]);
                $log = new \App\LogEntry;

                $log->description = "New DHCP Lease " . $l->ip . " leased to " . $l->mac_address;
                $log->event_type = "DHCP";
                $log->event_level = 0;
                $log->equipment_id = $host['equipment_id'];
                if( $l->client() ) {
                    $log->client_id = $l->client()->id;
                }
                $log->save();

                $l->updateDNS();
            }
        }
        return redirect('lease-ip');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
