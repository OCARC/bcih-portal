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

    public function client() {

        $l = Client::where('mac_address', $this->mac_address)->first();

        return $l;
    }

    public function server() {
        $l = Equipment::where('management_ip', $this->dhcp_server)->first();

        return $l;
    }

    public function ttl() {

        return $this->ends -time();
    }


   public function removeDNS() {
        // This should be called before saving dns changes otherwise old records may be orphaned

        $server = "44.135.216.2";
       $subdomain = $this->mac_address;
       if ($this->client() ) {
           if ($this->client()->snmp_sysName) {
               $subdomain = $this->client()->snmp_sysName;
               $subdomain = preg_replace("/[^A-Za-z0-9\.\-\_]/", '-', $subdomain);
           }
       }
       $ip = $this->ip;
        $zone = "cl.hamwan.ca";
        $parts = explode(".", $ip);

        $rzone = $parts[2] . "." . $parts[1] . "." . $parts[0] . ".in-addr.arpa";
        $rsubdomain = $parts[3];

            // Forward
            $pipe = popen("/usr/bin/nsupdate" . " -d -D -k " . "/hamwan/dns/Kif.hamwan.ca.+157+07954.private", 'w');
            fwrite($pipe, "server " . $server . "\n");
            fwrite($pipe, "zone " . $zone . "\n");
            fwrite($pipe, "update delete " . "$subdomain.$zone" . " A\n");
            fwrite($pipe, "send\n");
            $int = pclose($pipe);

            // Reverse
            $pipe = popen("/usr/bin/nsupdate" . " -d -D -k " . "/hamwan/dns/Kif.hamwan.ca.+157+07954.private", 'w');
            fwrite($pipe, "server " . $server . "\n");
            fwrite($pipe, "zone " . $rzone . "\n");
            fwrite($pipe, "update delete " . "$rsubdomain.$rzone" . " PTR\n");
            fwrite($pipe, "send\n");
            $int = pclose($pipe);


    }

    public function updateDNS() {
        // DNS
        $server = "44.135.216.2";
        $subdomain = $this->mac_address;
        if ($this->client() ) {
            if ($this->client()->snmp_sysName) {
                $subdomain = $this->client()->snmp_sysName;
                $subdomain = preg_replace("/[^A-Za-z0-9\.\-\_]/", '-', $subdomain);
            }
        }
        $ip = $this->ip;
        $zone = "cl.hamwan.ca";

        $parts = explode(".", $ip);

        $rzone = $parts[2] . "." . $parts[1] . "." . $parts[0] . ".in-addr.arpa";
        $rsubdomain = $parts[3];

            // Forward
            $pipe = popen("/usr/bin/nsupdate" . " -d -D -k " . "/hamwan/dns/Kif.hamwan.ca.+157+07954.private", 'w');
            fwrite($pipe, "server " . $server . "\n");
            fwrite($pipe, "zone " . $zone . "\n");
            fwrite($pipe, "update delete " . "$subdomain.$zone" . " A\n");
            fwrite($pipe, "update add " . "$subdomain.$zone" . " 300 A " . $ip . "\n");
            fwrite($pipe, "send\n");
            $int = pclose($pipe);

            // Reverse
            $pipe = popen("/usr/bin/nsupdate" . " -d -D -k " . "/hamwan/dns/Kif.hamwan.ca.+157+07954.private", 'w');
            fwrite($pipe, "server " . $server . "\n");
            fwrite($pipe, "zone " . $rzone . "\n");
            fwrite($pipe, "update delete " . "$rsubdomain.$rzone" . " PTR\n");
            fwrite($pipe, "update add " . "$rsubdomain.$rzone" . " 300 PTR " . $subdomain . "." . $zone . "\n");
            fwrite($pipe, "send\n");
            $int = pclose($pipe);



        // Run nsupdate

        //print $this->nsupdate( $server, $subdomain, $ip, $zone);

    }


}
