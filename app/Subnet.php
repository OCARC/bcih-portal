<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Subnet extends Model
{
    //
    protected $guard_name = 'web'; // or whatever guard you want to use
    protected $guarded = [];

    public function site() {
        $site = $this->belongsTo(Site::class);
        if ( $site ) {
            return $site;
        } else {
            return new \App\Site();
        }



    }
    public function user() {
        $site = $this->belongsTo(User::class);
        if ( $site ) {
            return $site;
        } else {
            return new \App\User();
        }



    }

    public function ips( $onlyAssigned = false) {
        //$records = $this->hasMany(IP::class)->orderBy('ip');


        $ips = $this->getEachIpInRange( $this->ip . "/" . $this->CIDR() );

        $db_ips = IP::all();
        $db_leases = DhcpLease::all();

        $result = array();


        $result = new Collection();

        foreach( $ips as $ip_address ) {

            $ip = $db_ips->where('ip', $ip_address)->first();

            if ( $ip ) {
                if ( !  $ip->category  ) {
                    $ip->category = "Reserved";
                }

            } else {

                //if ( count( $ips) <= 256 || $lease ) {
                if ( count( $ips) <= 256 && $onlyAssigned === false ) {
                    $ip = new IP();
                    $ip->ip = $ip_address;

                }
            }

//            if ( count( $ips) <= 256 || $lease ) {
//
//                if ($lease) {
//                    if ($lease->ttl() >= 0) {
//                        $ip->mac_address = $lease->mac_address;
//                        $ip->dhcp = "Yes";
//                        if (!$ip->category) {
//                            $ip->category = "Leased";
//                        }
//
//                        if (!$ip->hostname) {
//                            $ip->hostname = $lease->mac_address;
//
//                            if ( $lease->client() ) {
//                                if ($lease->client()->snmp_sysName) {
//                                    $ip->hostname = preg_replace("/[^A-Za-z0-9\.\-\_]/", '-', $lease->client()->snmp_sysName);
//                                }
//                            }
//                            $ip->dns_zone = "cl.hamwan.ca.";
//                        }
//
//                    }
//                }
//            }
            if ( $ip ) {

                $result->add($ip);
            }

        }

        return $result;
    }

    public function getIpRange(  $cidr) {

        list($ip, $mask) = explode('/', $cidr);

        $maskBinStr =str_repeat("1", $mask ) . str_repeat("0", 32-$mask );      //net mask binary string
        $inverseMaskBinStr = str_repeat("0", $mask ) . str_repeat("1",  32-$mask ); //inverse mask

        $ipLong = ip2long( $ip );
        $ipMaskLong = bindec( $maskBinStr );
        $inverseIpMaskLong = bindec( $inverseMaskBinStr );
        $netWork = $ipLong & $ipMaskLong;

        //Handle /31s nicely
        if ( explode('/',$cidr)[1] == '31') {
            $start = $netWork ;//include network ID(eg: 192.168.1.0)

            $end = ($netWork | $inverseIpMaskLong); //include brocast IP(eg: 192.168.1.255)

        } else {
            $start = $netWork + 1;//ignore network ID(eg: 192.168.1.0)

            $end = ($netWork | $inverseIpMaskLong) - 1; //ignore brocast IP(eg: 192.168.1.255)
        }

        return array('firstIP' => $start, 'lastIP' => $end );
    }

    public function getEachIpInRange ( $cidr) {
        $ips = array();
        $range = $this->getIpRange($cidr);
        for ($ip = $range['firstIP']; $ip <= $range['lastIP']; $ip++) {
            $ips[] = long2ip($ip);
        }
        return $ips;
    }

    public function CIDR( $spaces = false ) {
return  $this->mask2cidr( $this->netmask );


    }
    private function mask2cidr($mask){
        $long = ip2long($mask);
        $base = ip2long('255.255.255.255');
        return 32-log(($long ^ $base)+1,2);
    }

    public function count() {

        $range = $this->getIpRange(
            $this->ip . "/" .  $this->mask2cidr(  $this->netmask )
        );
        return ($range['lastIP'] - $range['firstIP'])+1;
           //return count($this->getEachIpInRange( $this->ip . "/" . $this->CIDR() ));
    }
    public function countUsed() {

        return count($this->ips( true ) );
    }
}