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

    public function __construct()
    {
        $this->middleware(['auth', 'role:network_operator'])->except('index', 'show');

    }

    public function index()
    {

        $clients = Client::all()->where("type", "client")->sortBy("site_id");
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
        if ($method == "resetGain") {
            $r = $client->sshResetGain();
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
        if ($method == "bwTest") {
            $r = $client->sshBWTest();
        }
        if ($method == "checkForUpdates") {
            $r =$client->sshCheckForUpdates();
        }
        if ($method == "downloadUpdates") {
            $r =$client->sshDownloadUpdates();
        }
        if ($method == "installUpdates") {
            $r =$client->sshInstallUpdates();
        }
        if ($method == "snmpPoll") {
            return $client->pollSNMP();
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
        return $this->index();
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
    public function store(Request $request)
    {
        //

        // TODO: Permission Checking

        $roles = $request->roles;
        unset($request['roles']);

        if ($request['id'] ) {
            $client = \App\Client::find($request['id']);
           // $client->syncRoles( $roles );

            // Check location
            $update_location = false;
            if ( $request->coordinate_source == 'snmp' ) {
                if ($request->longitude != $client->longitude || $request->latitude != $client->latitude) {
                    $update_location = true;
                }
            }


            $client->update($request->all());

            //Update location
            if( $update_location === true ) {
                $client->sshSetLocation();
            }

        } else {
            $client = \App\Client::create(
                $request->all()
            );
            //$client->syncRoles( $roles );
        }

        return redirect("/clients/" . $client->id);

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
        return view('clients.show', ['client' => $client, 'bwtest_servers' => \App\Equipment::all()->where('bwtest_server','!=','')]);
     //   return view('equipment.create', ['equipment' => new \App\Equipment() , 'sites' => \App\Site::all(), 'users' => \App\User::all(), 'cactiHosts' => \App\CactiHost::all() ]);

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
    public function edit(Client $client)
    {
        //
        return view('clients.edit', ['client' => $client , 'sites' => \App\Site::all(), 'users' => \App\User::all() ]);

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
