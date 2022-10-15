<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LibreNMSPort extends LibreNMSModel
{
    protected $table = 'librenms.ports';
    public $timestamps = false;
    //


    public function libre_ipv4Addresses() {
        return $this->hasMany(\App\LibreNMSIPv4Address::class,'port_id', 'port_id' );

    }
}

