<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreqTrack extends Model
{

    protected $table = 'freqtrack';

    public function strengthColor( $opacity = "1.0") {
        if ( $this->signal >= -49 ) { return "rgba( 000, 255, 000, $opacity)"; }

        if ( $this->signal == -50 ) { return "rgba( 000, 255, 000, $opacity)"; }
        if ( $this->signal == -51 ) { return "rgba( 000, 255, 000, $opacity)"; }
        if ( $this->signal == -52 ) { return "rgba( 001, 255, 000, $opacity)"; }
        if ( $this->signal == -53 ) { return "rgba( 002, 255, 000, $opacity)"; }
        if ( $this->signal == -54 ) { return "rgba( 004, 255, 000, $opacity)"; }
        if ( $this->signal == -55 ) { return "rgba( 008, 255, 000, $opacity)"; }
        if ( $this->signal == -56 ) { return "rgba( 014, 255, 000, $opacity)"; }
        if ( $this->signal == -57 ) { return "rgba( 022, 255, 000, $opacity)"; }
        if ( $this->signal == -58 ) { return "rgba( 032, 255, 000, $opacity)"; }
        if ( $this->signal == -59 ) { return "rgba( 046, 255, 000, $opacity)"; }
        if ( $this->signal == -60 ) { return "rgba( 063, 255, 000, $opacity)"; }
        if ( $this->signal == -61 ) { return "rgba( 084, 255, 000, $opacity)"; }
        if ( $this->signal == -62 ) { return "rgba( 108, 255, 000, $opacity)"; }
        if ( $this->signal == -63 ) { return "rgba( 133, 255, 000, $opacity)"; }
        if ( $this->signal == -64 ) { return "rgba( 159, 255, 000, $opacity)"; }
        if ( $this->signal == -65 ) { return "rgba( 183, 255, 000, $opacity)"; }
        if ( $this->signal == -66 ) { return "rgba( 206, 255, 000, $opacity)"; }
        if ( $this->signal == -67 ) { return "rgba( 226, 255, 000, $opacity)"; }
        if ( $this->signal == -68 ) { return "rgba( 242, 255, 000, $opacity)"; }
        if ( $this->signal == -69 ) { return "rgba( 252, 255, 000, $opacity)"; }
        if ( $this->signal == -70 ) { return "rgba( 255, 255, 000, $opacity)"; }
        if ( $this->signal == -71 ) { return "rgba( 255, 252, 000, $opacity)"; }
        if ( $this->signal == -72 ) { return "rgba( 255, 242, 000, $opacity)"; }
        if ( $this->signal == -73 ) { return "rgba( 255, 226, 000, $opacity)"; }
        if ( $this->signal == -74 ) { return "rgba( 255, 206, 000, $opacity)"; }
        if ( $this->signal == -75 ) { return "rgba( 255, 183, 000, $opacity)"; }
        if ( $this->signal == -76 ) { return "rgba( 255, 159, 000, $opacity)"; }
        if ( $this->signal == -77 ) { return "rgba( 255, 133, 000, $opacity)"; }
        if ( $this->signal == -78 ) { return "rgba( 255, 108, 000, $opacity)"; }
        if ( $this->signal == -79 ) { return "rgba( 255, 084, 000, $opacity)"; }
        if ( $this->signal == -80 ) { return "rgba( 255, 063, 000, $opacity)"; }
        if ( $this->signal == -81 ) { return "rgba( 255, 046, 000, $opacity)"; }
        if ( $this->signal == -82 ) { return "rgba( 255, 032, 000, $opacity)"; }
        if ( $this->signal == -83 ) { return "rgba( 255, 014, 000, $opacity)"; }
        if ( $this->signal == -84 ) { return "rgba( 255, 008, 000, $opacity)"; }
        if ( $this->signal == -85 ) { return "rgba( 255, 004, 000, $opacity)"; }
        if ( $this->signal == -86 ) { return "rgba( 255, 003, 000, $opacity)"; }
        if ( $this->signal == -87 ) { return "rgba( 255, 002, 000, $opacity)"; }
        if ( $this->signal == -88 ) { return "rgba( 255, 001, 000, $opacity)"; }
        if ( $this->signal == -89 ) { return "rgba( 255, 001, 000, $opacity)"; }
        if ( $this->signal == -90 ) { return "rgba( 255, 001, 000, $opacity)"; }
        return "rgba( 255, 000, 000, $opacity)";
    }
}
