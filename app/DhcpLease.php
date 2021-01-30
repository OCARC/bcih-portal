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


//   public function removeDNS() {
//        // This should be called before saving dns changes otherwise old records may be orphaned
//
//        $server = "44.135.216.2";
//       $subdomain = $this->mac_address;
//       if ($this->client() ) {
//           if ($this->client()->snmp_sysName) {
//               $subdomain = $this->client()->snmp_sysName;
//               $subdomain = preg_replace("/[^A-Za-z0-9\.\-\_]/", '-', $subdomain);
//           }
//       }
//       $ip = $this->ip;
//        $zone = "cl.hamwan.ca";
//        $parts = explode(".", $ip);
//
//        $rzone = $parts[2] . "." . $parts[1] . "." . $parts[0] . ".in-addr.arpa";
//        $rsubdomain = $parts[3];
//
//            // Forward
//            $pipe = popen("/usr/bin/nsupdate" . " -d -D -k " . "/hamwan/dns/Kif.hamwan.ca.+157+07954.private", 'w');
//            fwrite($pipe, "server " . $server . "\n");
//            fwrite($pipe, "zone " . $zone . "\n");
//            fwrite($pipe, "update delete " . "$subdomain.$zone" . " A\n");
//            fwrite($pipe, "send\n");
//            $int = pclose($pipe);
//
//            // Reverse
//            $pipe = popen("/usr/bin/nsupdate" . " -d -D -k " . "/hamwan/dns/Kif.hamwan.ca.+157+07954.private", 'w');
//            fwrite($pipe, "server " . $server . "\n");
//            fwrite($pipe, "zone " . $rzone . "\n");
//            fwrite($pipe, "update delete " . "$rsubdomain.$rzone" . " PTR\n");
//            fwrite($pipe, "send\n");
//            $int = pclose($pipe);
//
//
//    }
    function removeDNS() {
            // Forward

            // Also create/Update local record
            $record = DNSRecord::where( ['dhcp_lease_id' => $this->id, 'record_type' => 'A'])->first();
            if ($record) {
                $record->delete();
            }

            $record = DNSRecord::where(['dhcp_lease_id' => $this->id, 'record_type' => 'PTR'])->first();
            if ($record) {
                $record->delete();
            }


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
        $record = DNSRecord::where( ['ips_id' => $this->id, 'record_type' => 'A'])->first();
        if (!$record) {
            $record = new DNSRecord();
            $record->dhcp_lease_id = $this->id;
        }
        $record->record_type = 'A';
        $record->hostname = $subdomain;
        $record->ttl = 600;
        $record->target = $ip;
        // TODO: This is a temporary cludge, New records should have this already
        if ( !$record->dns_zones_id) {
            if ($zone == "if.hamwan.ca") {
                $record->dns_zones_id = 2;
            }
            if ($zone == "cl.hamwan.ca") {
                $record->dns_zones_id = 1;
            }

            if ($zone == "if.ocarc.ca") {
                $record->dns_zones_id = 8;
            }
            if ($zone == "cl.ocarc.ca") {
                $record->dns_zones_id = 9;
            }
        }
        $record->save();

            // Reverse
// Make sure there is a zone for this
        $parts = explode(".", $ip);

        $rzone = $parts[2] . "." . $parts[1] . "." . $parts[0] . ".in-addr.arpa";
        $rsubdomain = $parts[3];

        $dnsZone = DNSZone::where('domain', $rzone)->first();
        if ( ! $dnsZone ) {
            $dnsZone = new DNSZone();
            $dnsZone->name = "$rzone (auto created)";
            $dnsZone->domain = "$rzone";
            $dnsZone->save();
        }

        $record = DNSRecord::where( ['ips_id' => $this->id, 'record_type' => 'PTR'])->first();
        if (!$record) {
            $record = new DNSRecord();
            $record->dhcp_lease_id = $this->id;
        }
        $record->record_type = 'PTR';

        $record->hostname = "$rsubdomain.$rzone";
        $record->ttl = 600;
        $record->target = $subdomain . "." . $zone;
        $record->dns_zones_id = $dnsZone->id;

        $record->save();



        // Run nsupdate

        //print $this->nsupdate( $server, $subdomain, $ip, $zone);

    }


}
