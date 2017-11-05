<?php

namespace App\Http\Controllers;

use App\RsaKey;
use Illuminate\Http\Request;
use \App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index( Request $request )
    {

        //
        $users = User::all();
//        if ( $request->json ) {
//            return $users;
//        } else {
//            return view('users.index', compact('users'));
//
//        }
        return view('users.index', compact('users'));

    }

    function get_pub_sshkey( $callsign ) {
        $user = User::where('callsign', $callsign)->first();

        if (! $user) {
            abort(404, 'No Certificate Published For That User');
            return;
        }

        $key = $user->rsa_keys->where('publish',1)->first();

        if (! $key) {
            abort(404, 'No Certificate Published For That User');
            return;
        }
        header('Content-Disposition:attachment; filename="sshkey-' . $user->callsign . '.pub'. '"');
        return $key->public_key;
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
    public function show($id)
    {
        //
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
