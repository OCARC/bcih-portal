<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogEntry extends Model
{
    protected $guarded = [];
    //
    public function client() {
        $client = $this->belongsTo(Client::class);
        if ( $client ) {
            return $client;
        } else {
            return new \App\Client();
        }



    }
    public function site() {
        $site = $this->belongsTo(Site::class);
        if ( $site ) {
            return $site;
        } else {
            return new \App\Site();
        }



    }
    public function user() {
        $site = $this->belongsTo(User::class);
        if ( $site ) {
            return $site;
        } else {
            return new \App\User();
        }



    }
    public function equipment() {
        $equipment = $this->belongsTo(Equipment::class);
        if ( $equipment ) {
            return $equipment;
        } else {
            return new \App\Equipment();
        }



    }
}
