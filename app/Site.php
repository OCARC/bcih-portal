<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Site extends Model
{
    use HasRoles;
    protected $guard_name = 'web'; // or whatever guard you want to use

    protected $guarded = [];
    protected $hidden = [
        'comments'
    ];
    public function clients() {
        return $this->hasMany(Client::class)->orderBy('radio_name');
    }

    public function ptplinks() {
        return $this->hasMany(PtpLink::class, 'ap_site_id');
    }

    public function equipment() {
        return $this->hasMany(Equipment::class)->orderBy('hostname');
    }

    public function ips() {
        //TODO: add support for leases
        return $this->hasMany(IP::class)->orderBy('hostname');


    }
    public function subnets() {
        //TODO: add support for leases
        return $this->hasMany(Subnet::class)->orderBy('ip');


    }
    public function json() {

        $equipment = $this->equipment;
     $result = [];


        $result['site']['name'] = $this->name;
        $result['site']['code'] = $this->sitecode;
        $result['site']['description'] = $this->description;
        $result['site']['comment'] = $this->comment;

        foreach( $equipment as $e ) {
            $result['site']['equipment'][] = $e->jsonSerialize();

        }

        return $result;
    }

    
    function statusColor() {
        if ($this->status == "Potential") {
            return "#e1e1e1";
        } elseif( $this->status == "Planning") {
            return "#fff6a6";
        } elseif( $this->status == "Installed") {
            return "#aaffaa";
        } elseif( $this->status == "Equip Failed") {
            return "#ff6666";
        } elseif( $this->status == "Problems") {
            return "#ffd355";
        } elseif( $this->status == "No Install") {
            return "#979797";
        } else {
                return "inherit";
            }
    }
}
