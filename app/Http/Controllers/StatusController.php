<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\PtpLink;
use Illuminate\Http\Request;

class StatusController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        //

        $result = array(
            "SITES" => array()
        );

        header("Access-Control-Allow-Origin: *");
        $sites = \App\Site::all();
        foreach( $sites as $site ) {

            $result['SITES'][ $site->id ] = array(
                "NAME" => $site->name,
                "LATITUDE" => $site->latitude,
                "LONGITUDE" => $site->longitude,
                "ICON" => url($site->map_icon),
                "COMMENT" => $site->description,
                "STATUS" => $site->status,
                "STATUS_COLOR" => "#00ff00",
                "CLIENTS" => array(),
                "LINKS" => array()
            );

//            if ( $site->id == 5 ) {
//
//                if (($handle = fopen("local.csv", "r")) !== FALSE) {
//                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
//
//                        $result['SITES'][$site->id]['CLIENTS'][$data[0]] = array(
//                            "NAME" => $data[0],
//                            "LATITUDE" => $data[1],
//                            "LONGITUDE" => $data[2],
//                            "COMMENT" => $data[3],
//                        );
//
//                    }
//                    fclose($handle);
//                }
//            }


            foreach( $site->clients as $client ) {

                if ( $client->latitude == 0) {
                    continue;
                }
                if ( $client->longitude == 0) {
                    continue;
                }
                if ( $client->type == "link" ) {
                    $link = $client->ptp_link();
                    if ($link) {
                        $result['SITES'][$site->id]['LINKS'][$client->id] = array(
                            "NAME" => $link->name,
                            "SITE1_ID" => $link->ap_client->site_id,
                            "SITE2_ID" => $link->cl_client->site_id,
                            "SPEED" => $client->snmp_rx_rate,
                            "STRENGTH" => $client->snmp_strength,
                            "LINK_COLOR" => $link->link_color,
                            "COMMENT" => $link->comments,
                            "LINESTYLE" => $link->line_style
                        );
                    }
                    continue;
                }
                $result['SITES'][ $site->id ]['CLIENTS'][ $client->id ] = array(
                    "NAME" => ($client->dhcp_lease()) ? $client->dhcp_lease()->hostname : $client->snmp_sysName,
                    "LATITUDE" => $client->latitude,
                    "LONGITUDE" => $client->longitude,
                    "COMMENT" => $client->snmp_sysDesc,
                    "TYPE" => $client->type,
                    "SPEED" => $client->snmp_rx_rate,
                    "STRENGTH" => $client->snmp_strength,
                    "LINK_COLOR" => "#0086DB",
                );
            }

        }




            return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Returns the map view
     */
    public function map() {

        return view("status.map");
    }

    public function mapEmbed() {
        return view("status.mapEmbed");

    }
}
