<?php namespace App\Traits;


trait DAEnetIP4
{



    public function doDenkoviCurrentState() {
        $url = $this->management_ip;
        $password = 'ocarctest1';

        // create curl resource
        $ch = curl_init();

        if (! $_SERVER['QUERY_STRING'] ) {
            $_SERVER['QUERY_STRING'] = "1=1";
        }
        // set url
        curl_setopt($ch, CURLOPT_URL, "http://" . $url . "/current_state.json?" . $_SERVER['QUERY_STRING'] . "&pw=" . $password );
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);
        error_log($output);
        return $output;
    }

}