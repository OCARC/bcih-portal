<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use App\StaticLease;
use Illuminate\Http\Request;

class StaticLeaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $leases = StaticLease::all();
//        if ( $request->json ) {
//            return $users;
//        } else {
//            return view('users.index', compact('users'));
//
//        }
        return view('staticlease.index', compact('leases'));
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StaticLease  $staticLease
     * @return \Illuminate\Http\Response
     */
    public function show(StaticLease $staticLease)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StaticLease  $staticLease
     * @return \Illuminate\Http\Response
     */
    public function edit(StaticLease $staticLease)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StaticLease  $staticLease
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StaticLease $staticLease)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StaticLease  $staticLease
     * @return \Illuminate\Http\Response
     */
    public function destroy(StaticLease $staticLease)
    {
        //
    }

    public function generateFiles() {

        $output = "";
// Update Reservations File

// Write to new file
        $output .=  "### STOP : this file is generated by the portal. Use http://portal.hamwan.ca to edit it\n";

        $statics = StaticLease::all();//where('dhcp_server', "44.135.216.2");
        foreach( $statics as $static ) {
            print "Generating record for " . $static->hostname . "...\n";
            $output .= "host " . $static->hostname . " {\n";
            $output .= "\thardware ethernet " . $static->mac_address . " \n"; //TODO: format this correctly
            $output .= "\tfixed-address " . $static->ip . " \n";
            $output .= "}\n\n";
        }
        //$rows = $db->getRows("SELECT * FROM dhcp_statics WHERE server = ?", array( "44.135.216.2" ));
//        foreach( $rows as $r ) {
//            $output .= "host " . $r['name'] . " {\n";
//            $output .= "\thardware ethernet " . format_mac($r['mac']) . " \n";
//            $output .= "\tfixed-address " . $r['ip'] . " \n";
//            $output .= "}\n\n";
//        }
        Storage::disk('local')->put('dhcpd_reservations.44.135.216.2.conf', $output);

    }
}
