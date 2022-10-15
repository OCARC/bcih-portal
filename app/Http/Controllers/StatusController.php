<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\PtpLink;
use Illuminate\Http\Request;
use Auth;

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
            if ( $site->status == 'Potential'  ) {
                if ( ! Auth::user() ) {
                    continue;
                }
            }
            $result['SITES'][ $site->id ] = array(
                "NAME" => $site->name,
                "LATITUDE" => $site->latitude,
                "LONGITUDE" => $site->longitude,
                "ICON" => url($site->map_icon),
                "COMMENT" => $site->description,
                "STATUS" => $site->status,
                "STATUS_COLOR" => "#00ff00",
                "CLIENTS" => array(),
                "LINKS" => array(),
                "VISIBLE" => $site->map_visible
            );

            // Lets grab links
            foreach( $site->ptplinks as $link ) {
                if ( $link->status != 'Installed') {
                    if ( ! Auth::user() ) {
                        continue;
                    }
                }
                    $result['SITES'][$site->id]['LINKS'][$link->id] = array(
                        "NAME" => $link->name,
                        "SITE1_ID" => $link->ap_site_id,
                        "SITE2_ID" => $link->cl_site_id,
//                        "SPEED" => ($link->tx_speed() + $link->rx_speed()) / 2,
//                            "STRENGTH" => $client->snmp_strength,
                        "LINK_COLOR" => $link->link_color,
                        "COMMENT" => $link->comments . "ddd",
                        "LINESTYLE" => $link->line_style,
                    );
                    $cl = $link->cl_equipment()->first();;

                    if  ( $cl ) {
                            $result['SITES'][$site->id]['LINKS'][$link->id]["SPEED"] = ($cl->snmp_rx_rate + $cl->snmp_tx_rate) / 2;
                            $result['SITES'][$site->id]['LINKS'][$link->id]["SPEED_RX"] = $cl->snmp_rx_rate ;
                            $result['SITES'][$site->id]['LINKS'][$link->id]["SPEED_TX"] =  $cl->snmp_tx_rate;
                        }
                    if ($link->cl_site) {
                        $result['SITES'][$site->id]['COMMENT'] .= "<br>Link to " . $link->cl_site->name;
                    }

            }
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
//                    $link = $client->ptp_link();
//                    if ($link) {
//                        $result['SITES'][$site->id]['LINKS'][$client->id] = array(
//                            "NAME" => $link->name,
//                            "SITE1_ID" => $link->ap_client->site_id,
//                            "SITE2_ID" => $link->cl_client->site_id,
//                            "SPEED" => $client->snmp_rx_rate,
//                            "STRENGTH" => $client->snmp_strength,
//                            "LINK_COLOR" => $link->link_color,
//                            "COMMENT" => $link->comments,
//                            "LINESTYLE" => $link->line_style
//                        );
//                    }
                    continue;
                }
                $result['SITES'][ $site->id ]['CLIENTS'][ $client->id ] = array(
                    "NAME" => $client->snmp_sysName,
                    "LATITUDE" => $client->latitude,
                    "LONGITUDE" => $client->longitude,
                    "COMMENT" => $client->snmp_sysDesc,
                    "TYPE" => $client->type,
                    "SPEED" => $client->snmp_rx_rate,
                    "STRENGTH" => $client->snmp_strength,
                    "LINK_COLOR" => "#0086DB",
                    "UPDATED_AT" => $client->updated_at->timestamp,
                    "AGE" => time() - $client->updated_at->timestamp
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

    public function icon( $type ) {

        if ( $type == 'station') {
            $icon = array();
            if ( isset($_GET['clientID'])) {

                $client = \App\Client::find( $_GET['clientID'] );

                if($client) {
                    if ( time() - $client->updated_at->timestamp >= 86400) {
                        $icon['client']['fill'] = $client->strengthColor(0.5);

                    } elseif ( time() - $client->updated_at->timestamp >= 86400 * 7 ) {
                        $icon['client']['fill'] = $client->strengthColor(0);

                    } else {
                        $icon['client']['fill'] = $client->strengthColor(1);

                    }

                    $icon['client']['sysName'] = $client->snmp_sysName;
                }
            }

            return response(view("svg.station", ['icon' => $icon])->render(), 200)
                ->header('Content-Type', 'image/svg+xml')->header('Cache-Control','max-age=300');
        }
        if ( $type == 'site') {


            $icon = array();
            if ( isset($_GET['siteID'])) {

                $site = \App\Site::find( $_GET['siteID'] );

                if($site) {
                    $icon['site']['fill'] = "rgba(255,255,255,1)";
                    $icon['site']['code'] = $site->sitecode;

                    $equip = $site->equipment();
                    //foreach( array(0,120,240) as $az) {

                        //$equip = \App\Equipment::where('site_id','=',$site->id)->where('ant_azimuth','=', $az)->all();
                        $equipment = \App\Equipment::where('site_id','=',$site->id)->get();
                        foreach( $equipment as $equip) {
                        if ($equip) {
//                            if ( strtoupper(substr($equip->hostname, 0, 4)) == 'HEX1') {
//                                $icon['site']['fill'] = "rgba(255,255,255,1)";
//
//                            }
                                //if ($equip->ant_azimuth) {

                                if ( strtoupper(substr($equip->hostname, 0, 4)) !== 'RADI' && isset($equip->ant_azimuth)) {
                                    if ($equip->status == 'Installed' || $equip->status == 'Equip Failed' || $equip->status == 'Problems') {
                                        $icon['links'][] = array(
                                            'fill' => $equip->getHealthColor(0.5), //"rgba(0,0,0,0.5)";
                                            'azimuth' => $equip->ant_azimuth
                                        );
                                    }
                                    if ($equip->status == 'Planning') {
                                        $icon['links'][] = array(
                                            'fill' => 'white', //"rgba(0,0,0,0.5)";
                                            'stroke-dasharray' => '5,5', //"rgba(0,0,0,0.5)";
                                            'azimuth' => $equip->ant_azimuth
                                        );
                                    }

                                }
                                if ( strtoupper(substr($equip->hostname, 0, 5)) == 'RADIO') {

                                    if ($equip->status == 'Installed' || $equip->status == 'Equip Failed' || $equip->status == 'Problems') {
                                        $icon['sectors'][] = array(
                                            'fill' => $equip->getHealthColor(0.5), //"rgba(0,0,0,0.5)";
                                            'azimuth' => $equip->ant_azimuth
                                        );
                                    }
                                    if ($equip->status == 'Planning') {
                                        $icon['sectors'][] = array(
                                            'fill' => 'white', //"rgba(0,0,0,0.5)";
                                            'stroke-dasharray' => '5,5', //"rgba(0,0,0,0.5)";
                                            'azimuth' => $equip->ant_azimuth
                                        );
                                    }

                                }
                            //}
                        }
                    }

                }
            }

            return response(view("svg.site", ['icon' => $icon])->render(), 200)
                ->header('Content-Type', 'image/svg+xml')->header('Cache-Control','max-age=300');
        }
    }
}
