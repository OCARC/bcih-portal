<?php

namespace App\Http\Controllers;

use App\Client;
use App\PtpLink;
use Illuminate\Http\Request;
use App\Role;

class PtpLinkController extends Controller
{
    //

    public function index()
    {
        //
        $links = PtpLink::all();
        $clients = Client::all()->where("type", "link");
        return view('links.index', array( 'links' => $links, 'clients' => $clients ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        return view('links.create', ['link' => new \App\PtpLink(), 'equipments' => \App\Equipment::all()->sortBy('hostname') , 'sites' => \App\Site::all(), 'roles' => Role::all() ]);

    }
    public function edit(PtpLink $link)
    {
        //

        return view('links.edit',  ['link' => $link, 'equipments' => \App\Equipment::all()->sortBy('hostname') , 'sites' => \App\Site::all(), 'roles' => Role::all() ]);

    }
    public function show(PtpLink $link)
    {
        //

        return view('links.show',  ['link' => $link, 'equipments' => \App\Equipment::all()->sortBy('hostname') , 'sites' => \App\Site::all(), 'roles' => Role::all() ]);

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $authOK = false;
        $link = \App\PtpLink::find($request['id']);

        $roles = $request->roles;
        unset($request['roles']);

        if ($request['id'] ) {


            $link->update($request->all());

        } else {
            $link = \App\PtpLink::create(
                $request->all()
            );

        }
        if ( count($roles) >= 1 ) {
            $link->syncRoles($roles);
        }
        return redirect("/links");

    }
}
