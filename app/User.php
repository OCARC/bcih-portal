<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasRoles;

    use Notifiable;

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


    public function rsa_keys() {
        return $this->hasMany('App\RsaKey');
    }

    public function equipment() {
        return $this->hasMany(Equipment::class)->orderBy('hostname');
    }
    public function clients() {
        return $this->hasMany(Client::class)->orderBy('radio_name');
    }
    public function ips() {
        return $this->hasMany(IP::class);
    }
    public function sites() {
        return $this->hasMany(Site::class)->orderBy('name');
    }
    public function keys() {
        return $this->hasMany(RsaKey::class);
    }
}
