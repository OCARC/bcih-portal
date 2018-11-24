<?php

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call( RolesAndPermissionsSeeder::class );



        $user = new \App\User();

        $user->callsign = "manage";
        $user->name = "manage";
        $user->email = "manage@hamwan.ca";

        $user->administrator = 1;
        $user->password = bcrypt(md5(time()));
        $user->save();
        $user->assignRole( 'network_operator' );

        $user = new \App\User();

        $user->callsign = "admin";
        $user->name = "admin";
        $user->email = "admin@hamwan.ca";

        $user->administrator = 1;
        $user->password = bcrypt("password");
        $user->save();
        $user->assignRole( 'network_operator' );

    }
}




class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        $netop_role = Role::create(['name' => 'network_operator', 'friendly_name'=> 'Network Operator']);
        $default_org = Role::create(['name' => 'default_org', 'friendly_name'=> 'Default Organization', 'category'=>'Organization']);

        Permission::create(['name' => 'roles.user_change', 'friendly_name' => "User Change", 'category' => 'Roles' ]);
        Permission::create(['name' => 'permissions.user_change', 'friendly_name' => "User Change", 'category' => 'Permissions' ]);
        Permission::create(['name' => 'permissions.role_change', 'friendly_name' => "Role Change", 'category' => 'Permissions' ]);


        $netop_role->givePermissionTo('permissions.user_change');
        $netop_role->givePermissionTo('permissions.role_change');
        $netop_role->givePermissionTo('roles.user_change');

        $perms = array(
            'create ip',
            'delete ip',
            'edit own ip',
            'edit all ip',
            'view own ip',
            'view all ip',

            'create device',
            'edit own devices',
            'edit all devices',
            'view own devices',
            'view all devices',

            'create site',
            'edit own sites',
            'edit all sites',
            'view own sites',
            'view all sites',
            );


        foreach( $perms as $perm ) {

            try {
                Permission::create(['name' => $perm, 'friendly_name' => $perm ]);
                $netop_role->givePermissionTo($perm);

            } catch (  Spatie\Permission\Exceptions\PermissionAlreadyExists  $e ) {
                $this->command->info("\t Permission * $perm * already exists... doing nothing!");
            }
        }

        // create permissions

//        // create roles and assign existing permissions
//        $role = Role::create(['name' => 'hamwan user']);
//        $role->givePermissionTo('view own devives');
//        $role->givePermissionTo('delete articles');
//
//        $role = Role::create(['name' => 'admin']);
//        $role->givePermissionTo('publish articles');
//        $role->givePermissionTo('unpublish articles');
    }
}