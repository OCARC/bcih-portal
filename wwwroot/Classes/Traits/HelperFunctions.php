<?php namespace Traits;


trait HelperFunctions
{


    public function convert_oidmac_to_hex( $oidmac ) {
        $result = "";
        $parts = array_slice(explode( ".", $oidmac),0,6);
        foreach ($parts as $part ) {
            $result .= str_pad( dechex( $part ), 2,"0", STR_PAD_LEFT);
        }
        return $result;
    }
    public function formatMacAddress( $str ) {
        $str = strtoupper($str);
        $str = preg_replace('~..(?!$)~', '\0:', str_replace(".", "", $str));

        return $str;
    }

    private function mask2cidr($mask){
        $long = ip2long($mask);
        $base = ip2long('255.255.255.255');
        return 32-log(($long ^ $base)+1,2);
    }
}