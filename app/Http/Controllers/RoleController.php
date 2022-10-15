<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use \Spatie\Permission\Models\Role;
use \App\Role;
class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index( Request $request )
    {

        //
        $roles = Role::all();
//        if ( $request->json ) {
//            return $roles;
//        } else {
//            return view('roles.index', compact('roles'));
//
//        }
        return view('roles.index', compact('roles'));

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

    public function update_perms( Role $role, Request $request)
    {
        //

        $role->syncPermissions( $request->permissions );

        return redirect("/roles/" . $role->id . "#perms");

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Role $role)
    {

        $tabs = array(
            'info' => array(
                'title' => "Role Info",
                'active' => true,
                'visible' => true,
                'template' => 'roles.parts.tabInfo'
            ),
            'permissions' => array(
                'title' => "Permissions",
                'active' => false,
                'visible' => true,
                'template' => 'roles.parts.tabPermissions'
            ),
            'users' => array(
                'title' => "Users",
                'active' => false,
                'visible' => true,
                'template' => 'roles.parts.tabUsers'
            ),
            'equipment' => array(
                'title' => "Equipment",
                'active' => false,
                'visible' => true,
                'template' => 'roles.parts.tabEquipment'
            ),
            'Sites' => array(
                'title' => "Sites",
                'active' => false,
                'visible' => true,
                'template' => 'roles.parts.tabSites'
            ),
        );

        return view('roles.show', [
            'tabs' => $tabs,
            'role' => $role,
            'permissions' => \App\Permission::all(),
            'users' => $role->users()->get(),
            'sites' => $role->sites(),
            'equipment' => $role->equipment()
        ] );


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
