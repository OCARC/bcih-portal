<?php

namespace App\Http\Controllers;

use App\DNSRecord;
use App\Equipment;
use Illuminate\Http\Request;

use Auth;


class DNSRecordController extends Controller
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
        $dnsrecords = DNSRecord::all();
//        if ( request('json') ) {
//            return $zone;
//        } else {
        return view('dnsrecords.index', ['records' => $dnsrecords]);
//        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        $dnsrecord = new \App\DNSRecord();

        return view('dnsrecords.create', ['record' => $dnsrecord ]);

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
            $dnsrecord = \App\DNSRecord::find($request['id']);

            $dnsrecord->update($request->all());

        } else {
            $dnsrecord = \App\DNSRecord::create(
                $request->all()
            );

        }


        return redirect("/dns-records/" . $dnsrecord->id);
    }




    /**
     * Display the specified resource.
     *
     * @param  \App\DNSRecord  $dnsrecord
     * @return \Illuminate\Http\Response
     */
    public function show(DNSRecord $dnsrecord)
    {
        //
        return view('dnsrecords.show', ['record' => $dnsrecord  ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DNSRecord  $dnsrecord
     * @return \Illuminate\Http\Response
     */
    public function edit(DNSRecord $dnsrecord)
    {
        //
        return view('dnsrecords.edit', ['record' => $dnsrecord  ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DNSRecord  $dnsrecord
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DNSRecord $dnsrecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DNSRecord  $dnsrecord
     * @return \Illuminate\Http\Response
     */
    public function destroy(DNSRecord $dnsrecord)
    {
        //
    }
}
