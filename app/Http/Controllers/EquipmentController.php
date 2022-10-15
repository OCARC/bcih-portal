<?php

namespace App\Http\Controllers;
use App\Camera;
use App\User;
use Illuminate\Http\Response;

use App\Equipment;
use Illuminate\Http\Request;
use App\Role;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(['auth', 'role:network_operator'])->except('index', 'show', 'getGraph','doDenkoviCurrentState');

    }


    public function index()
    {

        $equipment =  User::current()->getEntities(Equipment::class, true );


        if ( request('json') ) {
            return $equipment;
        } else {
            return view('equipment.index', compact('equipment'));
        }
    }


    public function refresh() {
        $equipment = Equipment::all();
        foreach( $equipment as $i) {
            $i->pollSNMP();
            $i->discoverClients();
        }
        //return redirect('equipment');
        return redirect("/equipment");


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('equipment.create', ['equipment' => new \App\Equipment() , 'sites' => \App\Site::all(), 'users' => \App\User::all(), 'roles' => Role::all()  ]);

        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // TODO: Permission Checking

        $roles = $request->roles;
        unset($request['roles']);

       if ($request['id'] ) {
        $equipment = \App\Equipment::find($request['id']);
        $equipment->syncRoles( $roles );
        $equipment->update($request->all());
    } else {
        $equipment = \App\Equipment::create(
            $request->all()
        );
        $equipment->syncRoles( $roles );
    }

        return redirect("/equipment/" . $equipment->id);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function show(Equipment $equipment)
    {
        //

        $tabs = array(
            'info' => array(
                'title' => "Equipment Info",
                'active' => true,
                'visible' => true,
                'template' => 'equipment.parts.tabInfo'
            ),
            'clients' => array(
                'title' => "Clients",
                'active' => false,
                'visible' => ( $equipment->os == 'RouterOS' ) && ( $equipment->hasRadio() ),
                'template' => 'equipment.parts.tabClients'

            ),
            'ips' => array(
                'title' => "IP Addresses",
                'active' => false,
                'visible' => true,
                'template' => 'equipment.parts.tabIPs'
            ),
            'graphs' => array(
                'title' => "Graphs",
                'active' => false,
                'visible' => true,
                'template' => 'equipment.parts.tabGraphs'
            ),
            'tools' => array(
                'title' => "Tools",
                'active' => false,
                'visible' => (( $equipment->os == "RouterOS") || ( $equipment->os == "EdgeRouter")),
                'template' => 'equipment.parts.tabTools'
            ),
            'ospf' => array(
                'title' => "OSPF",
                'active' => false,
                'visible' => true,
                'template' => 'equipment.parts.tabOSPF'
            ),
            'dhcp' => array(
                'title' => "DHCP",
                'active' => false,
                'visible' => ($equipment->os == "RouterOS" ),
                'template' => 'equipment.parts.tabDHCP'
            ),
            'ports' => array(
                'title' => "Ports",
                'active' => false,
                'visible' => (($equipment->os == "RouterOS" ) || ( $equipment->os == "Cisco IOS" ) || ( $equipment->os == "EdgeRouter")),
                'template' => 'equipment.parts.tabPorts'


            ),
            'sitemon' => array(
                'title' => "Site Monitor",
                'active' => false,
                'visible' => ($equipment->os == "DAEnetIP4" ),
                'template' => 'equipment.parts.tabSiteMonitor'
            ),
            'pdu' => array(
                'title' => "PDU",
                'active' => false,
                'visible' => ($equipment->os == "CyberPower" ),
                'template' => 'equipment.parts.tabPDU'
            ),
            'stardot' => array(
                'title' => "Cameras",
                'active' => false,
                'visible' => ($equipment->os == "Stardot" ),
                'template' => 'equipment.parts.tabStardot'
            ),
            'health' => array(
                'title' => "Health",
                'active' => false,
                'visible' => ($equipment->librenms_mapping >= 1 ),
                'template' => 'equipment.parts.tabHealth'
            ),
            'libreNMS' => array(
                'title' => 'LibreNMS',
                'type' => 'link',
                'align' => 'right',
                'active' => false,
                'visible' => ($equipment->librenms_mapping),
                'href' => env('LIBRENMS_URL') . "/device/device=" . $equipment->librenms_mapping,
                'target' => '_blank',
                'class' => 'ms-auto'
            )
        );


        return view('equipment.show', ['tabs' => $tabs, 'equipment' =>$equipment , 'bwtest_servers' => \App\Equipment::all()->where('bwtest_server','!=','')]);

    }

//    public function libreGetGraph(Equipment $equipment, $type)
//    {
//        return $equipment->libreGetGraph($type);
////        return view('equipment.show', compact('equipment'));
////
////        print "ddd";
////        return true;
////        $ch = curl_init("www.example.com/curl.php?option=test");
////        curl_setopt($ch, CURLOPT_HEADER, 0);
////        curl_setopt($ch, CURLOPT_POST, 1);
////        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
////        $output = curl_exec($ch);
////        curl_close($ch);
////        echo $output;
////
////        return "dd";
////        //
//
////        /return view('equipment.show', compact('equipment'));
//
//    }
    public function showAjax(Equipment $equipment, $method)
    {


        $result = array(
            'method' => $method,
            'status' => 'fail'
        );

        //SSH
        if ($method == "fetchConfig") {
            $r = $equipment->sshFetchConfig();
        }
        if ($method == "fetchPOE") {
            $r =$equipment->sshFetchPOE();
        }
        if ($method == "fetchSpectralHistory") {
            $r =$equipment->sshFetchSpectralHistory();
        }
        if ($method == "fetchSpectralHistory") {
            $r =$equipment->sshFetchSpectralHistory();
        }
        if ($method == "fetchOSPFRoutes") {
            $r =$equipment->sshFetchOSPFRoutes();
        }
        if ($method == "bwTest") {
            $r =$equipment->SSHBWTest();
        }
        if ($method == "checkForUpdates") {
            $r =$equipment->sshCheckForUpdates();
        }
        if ($method == "downloadUpdates") {
            $r =$equipment->sshDownloadUpdates();
        }
        if ($method == "installUpdates") {
            $r =$equipment->sshInstallUpdates();
        }
        if ($method == "querySerialStatus") {
            $r =$equipment->sshQuerySerialStatus();
        }
        if ($method == "resetSerialAuthorizations") {
            $r =$equipment->sshResetSerialAuthentication();
        }
        if ($method == "authorizeSerialIP") {
            $r =$equipment->sshAuthorizeSerialIP();
        }

        // SNMP
        if ($method == "discoverClients") {
            $r =$equipment->discoverClients();
        }
        if ($method == "fetchDHCPLeases") {
            $r =$equipment->pollDHCP();
        }
        if ($method == "pollSNMP") {
            $r =$equipment->pollSNMP();
            $r =  array('status' => 'complete', 'method'=> 'pollSNMP', 'reason' => '', 'data' => $r);
            if( $r['data'] == array() ) {
                $r['status'] = 'failed';
            }

        }

        if ( isset($r)) {
            $result['data'] = $r['data'];
            $result['status'] = $r['status'];
            $result['reason'] = $r['reason'];
        }
        return $result ;

    }
    public function doDenkoviCurrentState( Equipment $equipment ) {

        return $equipment->doDenkoviCurrentState();
    }
    /**
     *
     * @param  \App\Equipment  $equipment
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function doCyberPowerPDUCurrentState( Equipment $equipment, Request $request ) {

        return $equipment->doCyberPowerPDUCurrentState( $request );
    }


    /**
     *
     * @param  \App\Equipment  $equipment
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function doStardotCameraImage( Equipment $equipment, Request $request ) {

        return $equipment->doStardotCameraImage( $request );
    }


     /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function edit(Equipment $equipment)
    {
        //

        return view('equipment.edit', ['equipment' => $equipment , 'sites' => \App\Site::all(), 'users' => \App\User::all(),  'roles' => Role::all()  ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Equipment $equipment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Equipment $equipment)
    {
        //
        $equipment->delete();
        return redirect("/equipment");
    }



    public function libreGetGraph(Equipment $equipment, $type)
    {

        return (new Response($equipment->libreGetGraph($type), 200))
            ->header('Content-Type','image/png');
    }

    public function getGraph(Equipment $equipment, $type = 'libre', $url = '') {
//
        if ( $type == 'libre') {
//print "devices/" . $equipment->librenms_mapping . "/" . $url . "?" . $_SERVER['QUERY_STRING']
//

            return $equipment->libre_query( "devices/" . $equipment->librenms_mapping . "/" . $url . "?" . $_SERVER['QUERY_STRING'], true );
        }



        //print_r( $equipment->libre_api( "devices/hex1.kui.if.hamwan.ca/") );

        //return response('Not Implemented', 501);


    }
}
