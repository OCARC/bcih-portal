<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $guarded = [];

    public function clients() {
        return $this->hasMany(Client::class);
    }


    public function equipment() {
        return $this->hasMany(Equipment::class);
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

    //
}
