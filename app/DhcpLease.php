<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

class DhcpLease extends Model
{
    protected $guarded = [];

    //

public function starts() {
    return Carbon::createFromTimestamp( $this->starts );
}
    public function ends() {
        return Carbon::createFromTimestamp( $this->ends );
    }
}
