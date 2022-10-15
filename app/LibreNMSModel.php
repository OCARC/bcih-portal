<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LibreNMSModel extends Model
{
    public $timestamps = false;

    // Block writes to LibreNMS Database
    public function save(array $options = array())
    {

        $error = 'LibreNMS Models are Read Only';
        $this->refresh();
        throw new Exception($error);
    }

}