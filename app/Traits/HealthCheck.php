<?php namespace App\Traits;


use JJG\Ping;

trait HealthCheck
{


    /**
     * return true if device is healthy
     * return false if something is wrong
     * @return bool|null
     */

    public function isHealthy(): ?bool  {

        if ( $this->hc_ping_result == -1 ) {
            return false;
        }
        return true;
    }

    public function performHealthCheck() {


        $this->hc_last_run = \Carbon\Carbon::now()->toDateTimeString();
        $this->hc_ping_result = $this->ping();
        if( $this->hc_ping_result >= 0) {
            $this->hc_last_ping_success = \Carbon\Carbon::now()->toDateTimeString();
        }
        $this->save();

        return $this->isHealthy();
    }


    public function ping() {

$ip = $this->getManagementIP();
if ( ! $ip ) {
    return -1;
}

        $ping = new Ping($ip);
        $ping->setTimeout(1);
        $latency = $ping->ping();

if ( ! $latency ) {
    return -1;
}
        return $latency;
    }


}
