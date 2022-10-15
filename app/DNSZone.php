<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DNSZone extends Model
{
    protected $table = 'dns_zones';
    protected $guarded = [];
    //

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


    public function dnsrecords() {
        return $this->hasMany(DNSRecord::class, 'dns_zones_id');


    }

    public function save(array $options = [])
    {



        foreach( $this->dnsrecords as $record ) {
            $record->_removeDNS();
        }
        $result = parent::save($options);
        foreach( $this->dnsrecords as $record ) {
            $record->refresh();
            $record->_updateDNS();
        }

        return $result;
    }


}
