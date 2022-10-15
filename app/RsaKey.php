<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RsaKey extends Model
{
    protected $hidden = [
        'private_key',
    ];
    //

public function user()
{
    return $this->belongsTo('App\User');
}
}

