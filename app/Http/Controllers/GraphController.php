<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GraphController extends Controller
{
    //

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('graph.create', ['subnet' => new \App\Subnet() , 'sites' => \App\Site::all(), 'users' => \App\User::all(), 'cactiHosts' => \App\CactiHost::all() ]);

    }

    public function index()
    {
        //
        return view('graph.index');

    }
}
