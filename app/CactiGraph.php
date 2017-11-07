<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CactiGraph extends Model
{
    protected $table = 'cacti.graph_local';
    public $timestamps = false;
    //

public function url() {
    return "http://44.135.216.2/cacti/graph_image.php?local_graph_id=" . $this->id. "&graph_start=1509855712&graph_end=1510028512&graph_width=500&graph_height=120";
}
}
