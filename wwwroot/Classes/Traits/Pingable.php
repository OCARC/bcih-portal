<?php

namespace Traits;
use \JJG\Ping;


trait Pingable {

    /** @Column(type="datetime", nullable=true) **/
    protected $last_ping_timestamp;

    /** @Column(type="integer", nullable=true, options={"default" : -1}) **/
    protected $last_ping;


    public function pingCheck() {


        if ( ! $this->getManagementIP( true ) ) {
         $latency = false;
        } else {
        $ping = new \JJG\Ping($this->getManagementIP( true ) );
        $ping->setTimeout(1);
        $latency = $ping->ping();
        }

        if ($latency !== false) {
            $this->last_ping = $latency;
            $this->last_ping_timestamp = new \DateTime('now');

        } else {
            $this->last_ping = -1;
//            $this->last_ping_timestamp = new \DateTime('now');

        }
        \App::entityManager()->persist($this);
        \App::entityManager()->flush();

        return $latency;
    }

    public function getLastPing( $format = false ) {
        if ($format) {
            if ( $this->last_ping == -1 ) {
                return 'timeout';
            } else {
                return $this->last_ping . " ms";
            }
        }
        return $this->last_ping;
    }
    public function getLastPingTimestamp() {

        if ( $this->last_ping_timestamp ) {
            $t = $this->last_ping_timestamp;
            return $t->getTimestamp();
        }
        return 0;
    }

}