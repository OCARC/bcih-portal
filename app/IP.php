<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IP extends Model
{
    protected $table = 'ips';
    protected $guarded = [];
    //

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
    public function cidr2mask($netmask) {
        $netmask_result="";
        for($i=1; $i <= $netmask; $i++) {
            $netmask_result .= "1";
        }
        for($i=$netmask+1; $i <= 32; $i++) {
            $netmask_result .= "0";
        }
        $netmask_ip_binary_array = str_split( $netmask_result, 8 );
        $netmask_ip_decimal_array = array();
        foreach( $netmask_ip_binary_array as $k => $v ){
            $netmask_ip_decimal_array[$k] = bindec( $v ); // "100" => 4
        }
        $subnet = join( ".", $netmask_ip_decimal_array );
        return $subnet;
    }
    public function equipment() {
        $equipment = $this->belongsTo(Equipment::class);
        if ( $equipment ) {
            return $equipment;
        } else {
            return new \App\Equipment();
        }



    }
    public function dhcp_lease( $expired = false) {
        $lease = DhcpLease::where('ip', $this->ip)->first();


        if ( $lease ) {

            if ( $expired == false && $lease->ttl() <= 0 ) {
                return null;
            }
            return $lease;
        } else {
            return null;
        }


    }

    function ipv4Breakout ($ip_address, $ip_nmask) {
        $hosts = array();
        //convert ip addresses to long form
        $ip_address_long = ip2long($ip_address);
        $ip_nmask_long = ip2long($ip_nmask);

        //caculate network address
        $ip_net = $ip_address_long & $ip_nmask_long;

        //caculate first usable address
        $ip_host_first = ((~$ip_nmask_long) & $ip_address_long);
        $ip_first = ($ip_address_long ^ $ip_host_first) + 1;

        //caculate last usable address
        $ip_broadcast_invert = ~$ip_nmask_long;
        $ip_last = ($ip_address_long | $ip_broadcast_invert) - 1;

        //caculate broadcast address
        $ip_broadcast = $ip_address_long | $ip_broadcast_invert;

        foreach (range($ip_first, $ip_last) as $ip) {
            array_push($hosts, $ip);
        }

        $block_info = array(array("network" => "$ip_net"),
            array("first_host" => "$ip_first"),
            array("last_host" => "$ip_last"),
            array("broadcast" => "$ip_broadcast"),
            $hosts);

        return $block_info;
    }

function removeDNS() {
    // This should be called before saving dns changes otherwise old records may be orphaned

//    $server = "44.135.216.2";

    $server = env('APP_DNS_SERVER', false);
    $key_file = env('APP_DNS_KEY', false);
    $subdomain = $this->hostname;
    $ip = $this->ip;
    $zone = $this->dns_zone;
    $parts = explode(".", $ip);

    $rzone = $parts[2] . "." . $parts[1] . "." . $parts[0] . ".in-addr.arpa";
    $rsubdomain = $parts[3];

    if ($this->dns == "Yes" ) {
        // Forward
//        $pipe = popen("/usr/bin/nsupdate" . " -d -D -k " . "/hamwan/dns/Kif.hamwan.ca.+157+07954.private", 'w');
        $pipe = popen("/usr/bin/nsupdate" . " -d -D -k " . $key_file, 'w');

        fwrite($pipe, "server " . $server . "\n");
        fwrite($pipe, "zone " . $zone . "\n");
        fwrite($pipe, "update delete " . "$subdomain.$zone" . " A\n");
        fwrite($pipe, "send\n");
        $int = pclose($pipe);

        // Reverse
//        $pipe = popen("/usr/bin/nsupdate" . " -d -D -k " . "/hamwan/dns/Kif.hamwan.ca.+157+07954.private", 'w');
        $pipe = popen("/usr/bin/nsupdate" . " -d -D -k " . $key_file, 'w');

        fwrite($pipe, "server " . $server . "\n");
        fwrite($pipe, "zone " . $rzone . "\n");
        fwrite($pipe, "update delete " . "$rsubdomain.$rzone" . " PTR\n");
        fwrite($pipe, "send\n");
        $int = pclose($pipe);

    }

    if ($this->dns == "ReverseOnly" ) {
        // Reverse
//        $pipe = popen("/usr/bin/nsupdate" . " -d -D -k " . "/hamwan/dns/Kif.hamwan.ca.+157+07954.private", 'w');
        $pipe = popen("/usr/bin/nsupdate" . " -d -D -k " . $key_file, 'w');

        fwrite($pipe, "server " . $server . "\n");
        fwrite($pipe, "zone " . $rzone . "\n");
        fwrite($pipe, "update delete " . "$rsubdomain.$rzone" . " A\n");
        fwrite($pipe, "send\n");
        $int = pclose($pipe);

        //
    }
}

    function updateDNS() {
        // DNS
//        $server = "44.135.216.2";
        $server = env('APP_DNS_SERVER', false);
        $key_file = env('APP_DNS_KEY', false);

        $subdomain = $this->hostname;
        $ip = $this->ip;
        $zone = $this->dns_zone;

        $parts = explode(".", $ip);

        $rzone = $parts[2] . "." . $parts[1] . "." . $parts[0] . ".in-addr.arpa";
        $rsubdomain = $parts[3];

        if ($this->dns == "Yes" ) {
            // Forward
//            $pipe = popen("/usr/bin/nsupdate" . " -d -D -k " . "/hamwan/dns/Kif.hamwan.ca.+157+07954.private", 'w');
            $pipe = popen("/usr/bin/nsupdate" . " -d -D -k " . $key_file, 'w');
            fwrite($pipe, "server " . $server . "\n");
            fwrite($pipe, "zone " . $zone . "\n");
            fwrite($pipe, "update delete " . "$subdomain.$zone" . " A\n");
            fwrite($pipe, "update add " . "$subdomain.$zone" . " 300 A " . $ip . "\n");
            fwrite($pipe, "send\n");
            $int = pclose($pipe);

            // Reverse
//            $pipe = popen("/usr/bin/nsupdate" . " -d -D -k " . "/hamwan/dns/Kif.hamwan.ca.+157+07954.private", 'w');
            $pipe = popen("/usr/bin/nsupdate" . " -d -D -k " . $key_file, 'w');
            fwrite($pipe, "server " . $server . "\n");
            fwrite($pipe, "zone " . $rzone . "\n");
            fwrite($pipe, "update delete " . "$rsubdomain.$rzone" . " PTR\n");
            fwrite($pipe, "update add " . "$rsubdomain.$rzone" . " 300 PTR " . $subdomain . "." . $zone . "\n");
            fwrite($pipe, "send\n");
            $int = pclose($pipe);

        }

        if ($this->dns == "ReverseOnly" ) {
            // Reverse
//            $pipe = popen("/usr/bin/nsupdate" . " -d -D -k " . "/hamwan/dns/Kif.hamwan.ca.+157++07954.private", 'w');
            $pipe = popen("/usr/bin/nsupdate" . " -d -D -k " . $key_file, 'w');
            fwrite($pipe, "server " . $server . "\n");
            fwrite($pipe, "zone " . $rzone . "\n");
            fwrite($pipe, "update delete " . "$rsubdomain.$rzone" . " A\n");
            fwrite($pipe, "update add " . "$rsubdomain.$rzone" . " 300 PTR " . $subdomain . "." . $zone . "\n");
            fwrite($pipe, "send\n");
            $int = pclose($pipe);

            //
        }

        if ($this->dns == "" ) {
            // Forward
//            $pipe = popen("/usr/bin/nsupdate" . " -d -D -k " . "/hamwan/dns/Kif.hamwan.ca.+157+07954.private", 'w');
            $pipe = popen("/usr/bin/nsupdate" . " -d -D -k " . $key_file, 'w');
            fwrite($pipe, "server " . $server . "\n");
            fwrite($pipe, "zone " . $zone . "\n");
            fwrite($pipe, "update delete " . "$subdomain.$zone" . " A\n");
            fwrite($pipe, "send\n");
            $int = pclose($pipe);

            // Reverse
        }


        // Run nsupdate

        //print $this->nsupdate( $server, $subdomain, $ip, $zone);

    }
}
