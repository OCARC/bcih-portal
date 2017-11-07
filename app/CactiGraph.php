<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CactiGraph extends Model
{
    protected $table = 'cacti.graph_local';
    public $timestamps = false;
    //

public function url( $days = 5, $height = 120, $width = 400) {
    $end = time();
    $start = $end  - ( $days * 86400 );
    return "http://44.135.216.2/cacti/graph_image.php?local_graph_id=" . $this->id. "&graph_start=$start&graph_end=$end&graph_width=$width&graph_height=$height";
}
}

