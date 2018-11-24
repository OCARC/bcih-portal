<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role as baseRole;


class Role extends baseRole
{



    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'callsign', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


//    public function rsa_keys() {
//        return $this->hasMany('App\RsaKey');
//    }
//
//    public function equipment() {
//        return $this->hasMany(Equipment::class)->orderBy('hostname');
//    }
//    public function clients() {
//        return $this->hasMany(Client::class)->orderBy('radio_name');
//    }
//    public function sites() {
//
//        dd( $this  );
//        return Site::HasRoles();
//    }
//
//    /**
//     * A role belongs to some users of the model associated with its guard.
//     */
//    public function equipment()
//    {
//
//
//        return Equipment::hasRole( $this );
//    }

//    public function users() {
//
//    }
}
