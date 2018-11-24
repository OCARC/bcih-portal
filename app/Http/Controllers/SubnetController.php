<?php

namespace App\Http\Controllers;

use App\Subnet;
use Illuminate\Http\Request;

class SubnetController extends Controller
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
        $subnets = Subnet::all()->sortBy( function($subnet) { return ip2long( $subnet->ip); });
//        if ( request('json') ) {
//            return $subnet;
//        } else {
            return view('subnet.index', compact('subnets'));
//        }
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('subnet.create', ['subnet' => new \App\Subnet() , 'sites' => \App\Site::all(), 'users' => \App\User::all() ]);

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
            $subnet = \App\Subnet::find($request['id']);
            $subnet->update($request->all());
        } else {
            $subnet = \App\Subnet::create(
                $request->all()
            );
        }

        return redirect("/subnets/" . $subnet->id);    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subnet  $subnet
     * @return \Illuminate\Http\Response
     */
    public function show(Subnet $subnet)
    {
        //
        return view('subnet.show', compact('subnet'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subnet  $subnet
     * @return \Illuminate\Http\Response
     */
    public function edit(Subnet $subnet)
    {
        //
        return view('subnet.edit', ['subnet' => $subnet , 'sites' => \App\Site::all(), 'users' => \App\User::all() ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subnet  $subnet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subnet $subnet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subnet  $subnet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subnet $subnet)
    {
        //
    }
}
