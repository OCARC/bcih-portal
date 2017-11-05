<?php

namespace App\Http\Controllers;

use App\DhcpLease;
use Illuminate\Http\Request;

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
        $leases = DHCPLease::all();
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
        $leases = json_decode(file_get_contents("http://44.135.216.2:3000/api/get_active_leases"),true);

        foreach( $leases as $ip => $lease ) {
            //$hash = md5( $leases['ip'] . $lease['start'] . $lease['end'] . $lease['host'] . $leases['mac']);
            $l =  DhcpLease::where('mac_address', str_replace(":", "", $lease['mac']))->first();

            if( $l ) {
                // Update

                $l->fill([
                    'id' => $l->id,
                    'owner' => -1,
                    'ip' => $ip,
                    'hostname' => ( isset($lease['host']) ? $lease['host'] : ''),
                    'mac_oui_vendor' => ( isset($lease['mac_oui_vendor']) ? $lease['mac_oui_vendor'] : ''),
                    'mac_address' => str_replace(":", "", $lease['mac']),
                    'dhcp_server' => '44.135.216.2',
                    'starts' => $lease['start'],
                    'ends' => $lease['end'],
                ]);
                $l->save();
            } else {
                \App\DhcpLease::create([
                    'owner' => -1,
                    'ip' => $ip,
                    'hostname' => ( isset($lease['host']) ? $lease['host'] : ''),
                    'mac_oui_vendor' => ( isset($lease['mac_oui_vendor']) ? $lease['mac_oui_vendor'] : ''),
                    'mac_address' => str_replace(":", "", $lease['mac']),
                    'dhcp_server' => '44.135.216.2',
                    'starts' => $lease['start'],
                    'ends' => $lease['end'],
                ]);

            }

//            $db->doQuery("REPLACE INTO `dhcp_leases` (starts,ends,mac_address,client_hostname,dhcp_server,ip,hash) VALUES (FROM_UNIXTIME(?),FROM_UNIXTIME(?),?,?,?,?,?)",
//                array( $lease['start'],
//                    $lease['end'],
//                    str_replace(":","",$lease['mac']),
//                    $lease['host'],
//                    '44.135.216.2',
//                    $ip,
//                    $hash
//                ) );
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
