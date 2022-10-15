<?php

namespace App\Http\Controllers;

use App\LogEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogEntryController extends Controller
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

                $logEvents = \App\LogEntry::orderBy('created_at', 'desc')->limit(100)->get(); //->sortBy("site_id")->sortBy("type");

//        if ( $request->json ) {
//            return $users;
//        } else {
//            return view('users.index', compact('users'));
//
//        }
        return view('log.index', compact('logEvents'));
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
