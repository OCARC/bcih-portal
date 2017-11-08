<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Client;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        $clients = Client::all()->sortBy("site_id")->sortBy("type");
//        if ( $request->json ) {
//            return $users;
//        } else {
//            return view('users.index', compact('users'));
//
//        }
        return view('clients.index', compact('clients'));
    }

    public function showAjax(Client $client, $method)
    {


        $result = array(
            'method' => $method,
            'status' => 'fail'
        );

        if ($method == "fetchConfig") {
            $r = $client->sshFetchConfig();
        }
        if ($method == "quickScan") {
            $r = $client->sshQuickScan();
        }
        if ($method == "quickMonitor") {
            $r = $client->sshQuickMonitor();
        }
        if ($method == "fetchSpectralHistory") {
            $r = $client->sshFetchSpectralHistory();
        }
        if ($r) {
            $result['data'] = $r['data'];
            $result['status'] = $r['status'];
            $result['reason'] = $r['reason'];
        }
        return $result ;

    }
    public function refresh() {
        $clients = Client::all();
        foreach( $clients as $i) {
            $i->pollSNMP();
        }
        //return redirect('equipment');


    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
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
    public function show(Client $client)
    {
        //
        return view('clients.show', compact('client'));

    }

    public function showSNMP(Client $client)
    {
        //

        return $client->getSNMP();
        //return view('clients.show', compact('client'));

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
    }}
