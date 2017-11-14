<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;

use App\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
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
        $equipment = Equipment::all();
        if ( request('json') ) {
            return $equipment;
        } else {
            return view('equipment.index', compact('equipment'));
        }
    }


    public function refresh() {
        $equipment = Equipment::all();
        foreach( $equipment as $i) {
            $i->pollSNMP();
            $i->discoverClients();
        }
        //return redirect('equipment');
        return redirect("/equipment");


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('equipment.create', ['equipment' => new \App\Equipment() , 'sites' => \App\Site::all(), 'users' => \App\User::all(), 'cactiHosts' => \App\CactiHost::all() ]);

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
        // TODO: Permission Checking

       if ($request['id'] ) {
        $equipment = \App\Equipment::find($request['id']);
        $equipment->update($request->all());
    } else {
        $equipment = \App\Equipment::create(
            $request->all()
        );
    }

        return redirect("/equipment/" . $equipment->id);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function show(Equipment $equipment)
    {
        //
        return view('equipment.show', compact('equipment'));

    }
    public function showAjax(Equipment $equipment, $method)
    {


        $result = array(
            'method' => $method,
            'status' => 'fail'
        );

        if ($method == "fetchConfig") {
            $r = $equipment->sshFetchConfig();
        }
        if ($method == "fetchPOE") {
            $r =$equipment->sshFetchPOE();
        }
        if ($method == "fetchSpectralHistory") {
            $r =$equipment->sshFetchSpectralHistory();
        }

        if ($r) {
            $result['data'] = $r['data'];
            $result['status'] = $r['status'];
            $result['reason'] = $r['reason'];
        }
        return $result ;

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function edit(Equipment $equipment)
    {
        //
        return view('equipment.edit', ['equipment' => $equipment , 'sites' => \App\Site::all(), 'users' => \App\User::all(), 'cactiHosts' => \App\CactiHost::all() ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Equipment $equipment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Equipment $equipment)
    {
        //
        $equipment->delete();
        return redirect("/equipment");
    }


    public function graph(Equipment $equipment, $type)
    {

        return (new Response($equipment->getGraphs($type), 200))
            ->header('Content-Type','image/png');
    }
}
