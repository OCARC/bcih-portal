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


    //
}
