<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
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

    /**
     * Retrieve the SSH key this user has chosen to publish
     * @param $callsign
     */
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

    public function update_perms( User $user, Request $request)
    {
        //
        $user->syncPermissions( $request->permissions );
        return redirect("/users/" . $user->id . "#perms")->with('success', 'Permissions Modified Successfully');

    }
    public function update_roles( User $user, Request $request)
    {
        //

        $user->syncRoles( $request->roles );

        return redirect("/users/" . $user->id . "#perms")->with('success', 'Roles Modified Successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(User $user)
    {

        //

        return view('users.show', array( 'user' => $user, 'permissions' => Permission::all(), 'roles' => Role::all() ));


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
