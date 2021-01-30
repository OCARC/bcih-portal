<?php

namespace App\Http\Controllers;

use App\DNSZone;
use App\Equipment;
use Illuminate\Http\Request;

use Auth;


class DNSZoneController extends Controller
{



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//
        //
        $zones = DNSZone::all();
//        if ( request('json') ) {
//            return $zone;
//        } else {
        return view('dnszones.index', ['records' => $zones]);
//        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        $dnszone = new \App\DNSZone();

        return view('dnszones.create', ['record' => $dnszone ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // TODO: Permission Checking

        if ($request['id'] ) {
            $zone = \App\DNSZone::find($request['id']);

            $zone->update($request->all());

        } else {
            $zone = \App\DNSZone::create(
                $request->all()
            );

        }


        return redirect("/dns-zones/" . $zone->id);
    }




    /**
     * Display the specified resource.
     *
     * @param  \App\DNSZone  $zone
     * @return \Illuminate\Http\Response
     */
    public function show(DNSZone $dnszone)
    {
        //
        return view('dnszones.show', ['record' => $dnszone  ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DNSZone  $dnszone
     * @return \Illuminate\Http\Response
     */
    public function edit(DNSZone $dnszone)
    {
        //
        return view('dnszones.edit', ['record' => $dnszone  ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DNSZone  $zone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DNSZone $zone)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DNSZone  $zone
     * @return \Illuminate\Http\Response
     */
    public function destroy(DNSZone $zone)
    {
        //
    }
}
