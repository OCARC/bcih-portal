<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Client;
class PtpLink extends Model
{
    //


    function ap_client() {
        return $this->hasOne(\App\Client::class,'id','ap_client_id');
    }
    function cl_client() {
        return $this->hasOne(\App\Client::class,'id','cl_client_id');
    }
}
