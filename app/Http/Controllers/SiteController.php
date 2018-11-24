<?php

namespace App\Http\Controllers;

use App\Site;
use Illuminate\Http\Request;
use Auth;
use App\Role;

class SiteController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'role:network_operator'])->except('index','indexJSON', 'show');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //



        $sites = Site::all();
        return view('site.index', compact('sites'));
    }

    public function indexJSON() {


        $sites = Site::all();

        foreach( $sites as &$site) {
            $site->equipment = $site->equipment;
        }
        return $sites;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        //
        return view('site.create', ['site' => new \App\Site() , 'sites' => \App\Site::all(), 'users' => \App\User::all(), 'roles' => Role::all()  ]);

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
        $site = \App\Site::find($request['id']);
        $user = Auth::user();

        if ( $user->can('view all sites') ) {
            $authOK = true;
        }elseif ( $user->can('view own sites') && $site->user_id == $user->id ) {
            $authOK = true;
        }

        if ( $authOK ) {
        } else {
            return view('common.denied');
        }

        $roles = $request->roles;
        unset($request['roles']);

        if ($request['id'] ) {
            $site->syncRoles( $roles );

            $site->update($request->all());

        } else {
            $site = \App\Site::create(
                $request->all()
            );
            $site->syncRoles( $roles );

        }

        return redirect("/site/" . $site->id);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
    {
//        $user = Auth::user();
//        $authOK = false;
//
//        if ( $user->can('view all sites') ) {
//            $authOK = true;
//        }elseif ( $user->can('view own sites') && $site->user_id == $user->id ) {
//            $authOK = true;
//        }
//
//        if ( $authOK ) {
//        } else {
//            return view('common.denied');
//        }


//        return view('site.show', [ 'site' => $site, 'freq_track' => \App\FreqTrack::all()->where('site_code', $site->sitecode) ] );
        return view('site.show', [ 'site' => $site ] );

    }
    public function showJSON(Site $site)
    {
        return $site->json();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function edit(Site $site)
    {        $authOK = false;


        $user = Auth::user();

        if ( $user->can('edit all sites') ) {
            $authOK = true;
        }elseif ( $user->can('edit own sites') && $site->user_id == $user->id ) {
            $authOK = true;
        }

        if ( $authOK ) {
        } else {
            return view('common.denied');
        }


        return view('site.edit', ['site' => $site , 'sites' => \App\Site::all(), 'users' => \App\User::all(), 'roles' => Role::all() ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Site $site)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Site $site)
    {
        //
    }
}
