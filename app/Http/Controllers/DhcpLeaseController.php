<?php

namespace App\Http\Controllers;

use App\Client;
use App\DhcpLease;
use App\IP;
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



        // New Way
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
                            $hosts[$server . "-" . $parts[3]]['site_id'] = $e->site_id;
                        }

                        if ($parts[2] == 8) {
                            $hosts[$server . "-" . $parts[3]]['mac'] = str_replace(" ", "", substr($value, 5));
                            $hosts[$server . "-" . $parts[3]]['ip'] = $parts[3];
                            $hosts[$server . "-" . $parts[3]]['server'] = $server;
                            $hosts[$server . "-" . $parts[3]]['equipment_id'] = $e->id;
                            $hosts[$server . "-" . $parts[3]]['site_id'] = $e->site_id;


                        }
                    }
                }



        }

        // Update
        foreach ($hosts as $host) {
            // New Way
            //TODO: Find any IP entity where mac matches and is type=dhcp
            $ip = IP::where('type','dhcp')->where('mac_address', $host['mac'])->first();

            print $host['ttl'] . "<hr>";

            $ttl = $host['ttl'];
            if ( $ttl >= 1000000 ) {
                $ttl = -1;
            } elseif ( $ttl <= -1000000 ) {
                $ttl = -1;
            } else {
                $ttl = time() + $host['ttl'];
            }

            // Try to find a client record
            $client = Client::where('mac_address', $host['mac'])->first();
            if( $client ) {
                $hostname = $client->friendly_name();
            } else {
                $hostname = $host['mac'];
            }


            if ( $ip ) {
                // We have a record to update
                $ip->fill([
                    'site_id' => $host['site_id'],
                    'hostname' => $hostname,
                    'ip' => $host['ip'],
                    'mac_address' => $host['mac'],
                    'dhcp_server' => $host['server'],
                    'dhcp_expires' =>  $ttl,
                    'dns_zone' => 'cl.ocarc.ca.',
                    'dns' => 'Yes'

                ]);
                $ip->save();
                $ip->updateDNS();


            } else {
                // Need to create a new record
                $ip = \App\IP::create([
                    'user_id' => 0,
                    'hostname' => $hostname,
                    'site_id' => $host['site_id'],
                    'ip' => $host['ip'],
                    'hostname' => '',
//                    'mac_oui_vendor' => 'unk',
                    'mac_address' => $host['mac'],
                    'dhcp_server' => $host['server'],
                    'dhcp_expires' =>  $ttl,
                    'type' => 'dhcp',
                    'dns_zone' => 'cl.ocarc.ca.',
                    'dns' => 'Yes'

                ]);
                $ip->updateDNS();

            }


            // Old way
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




        // Handle expired
        $expired_ips = IP::where('type','dhcp')->where('type','dhcp')->where('dhcp_expires', "<=",  time()-(24*60*60) )->get();

        foreach( $expired_ips as $ip ) {
            $ip->delete();
        }
      //  and dhcp_expires >= unix_timestamp()

       // return redirect('lease-ip');

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
