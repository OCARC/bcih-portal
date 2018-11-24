@extends('common.master')
@section('title')
    Equipment
@endsection
@section('content')

    <h2><img src="{{ $equipment->icon() }}" style="height: 2em; margin-top: -1em"> Equipment: {{ $equipment->hostname }}
    </h2>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="{{url("/subnets")}}">Equipment</a></li>
        <li class="breadcrumb-item active">{{ $equipment->hostname }}</li>
    </ol>
    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#info" aria-controls="info" role="tab" data-toggle="tab">Equipment
                    Info</a></li>
            @if ($equipment->os == "RouterOS")
            <li role="presentation"><a href="#clients" aria-controls="clients" role="tab" data-toggle="tab">Clients</a></li>
            @endif

            <li role="presentation"><a href="#ips" aria-controls="ips" role="tab" data-toggle="tab">IP Addresses</a>
            </li>
            <li role="presentation"><a href="#graphs" aria-controls="graphs" role="tab" data-toggle="tab">Graphs</a>
            </li>
            <li role="presentation"><a href="#tools" aria-controls="tools" role="tab" data-toggle="tab">Tools</a></li>
            @if ($equipment->os == "RouterOS" or $equipment->os == "EdgeRouter")
            <li role="presentation"><a href="#ospf" aria-controls="tools" role="tab" data-toggle="tab" onClick="$('#ospf .defaultButton').click();">OSPF</a></li>
            @endif
            @if ($equipment->os == "RouterOS" or $equipment->os == "Cisco IOS" or $equipment->os == "EdgeRouter")

            <li role="presentation"><a href="#ports" aria-controls="ports" role="tab" data-toggle="tab">Ports</a></li>
@endif
            @if ($equipment->os == "DAEnetIP4")

                <li role="presentation"><a href="#sitemon" aria-controls="sitemon" role="tab" data-toggle="tab">Site Monitor</a></li>
            @endif
            @if( $equipment->librenms_mapping)
                <li role="presentation" style="float:right">
                    <a target="_blank"
                       href="http://portal.hamwan.ca/librenms/device/device={{$equipment->librenms_mapping}}">
                        LibreNMS</a></li>
            @endif
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="info">

                {{--@include('equipment.ports', ['ports' => $equipment->ports() ])--}}
<br>
                <form method="POST" action="{{ url("/equipment/" . $equipment->id . "") }}" accept-charset="UTF-8">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE"/>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Equipment Information
                    </div>
                    <div class="panel-body">
                        <div class="form-group col-xs-4">
                            <label for="name">Hostname</label>
                            <p class="form-control-static">{{ $equipment->hostname }}</p>
                        </div>
                        <div class="form-group col-xs-4">
                            <label for="name">Management IP</label>
                            <p class="form-control-static">{{ $equipment->management_ip }}</p>

                        </div>
                        <div class="form-group col-xs-4">
                            <label for="name">Operating System</label>
                            <p class="form-control-static">{{$equipment->os}}</p>
                        </div>

                        <div class="form-group col-xs-4">
                            <label for="name">Status</label>
                            <p  class="form-control-static">
                                @if ($equipment->status == "Potential")
                                    <span style="vertical-align:middle;background-color: #e1e1e1">{{ $equipment->status }}</span>
                                @elseif( $equipment->status == "Planning")
                                    <span style="vertical-align:middle;background-color: #fff6a6">{{ $equipment->status }}</span>
                                @elseif( $equipment->status == "Installed")
                                    <span style="vertical-align:middle;background-color: #aaffaa">{{ $equipment->status }}</span>
                                @elseif( $equipment->status == "Equip Failed")
                                    <span style="vertical-align:middle;background-color: #ff6666">{{ $equipment->status }}</span>
                                @elseif( $equipment->status == "Problems")
                                    <span style="vertical-align:middle;background-color: #ffd355">{{ $equipment->status }}</span>
                                @elseif( $equipment->status == "No Install")
                                    <span style="vertical-align:middle;background-color: #979797">{{ $equipment->status }}</span>
                                @else
                                    <span style="vertical-align:middle;">{{ $equipment->status }}</span>
                                @endif

                            </p>
                        </div>

                        <div class="form-group col-xs-4">

                            <label for="name">Site</label>
                            <p class="form-control-static">
                                <a href="{{url("/site/" . $equipment->site_id ) }}">{{$equipment->site['name'] }}
                                    ({{$equipment->site['sitecode'] }})</a>
                            </p>
                        </div>
                        <div class="form-group col-xs-4">

                            <label for="name">Owner</label>
                            <p class="form-control-static">
                                <a href="{{url("users/" . $equipment->user_id )}}">{{ $equipment->user->callsign }}</a>
                            </p>
                        </div>

                    </div>
                    <div class="panel-footer text-right">

                            <a href="{{ url("/equipment/" . $equipment->id . "/edit") }}"><button type="button" class="btn btn-xs btn-success">Edit Equipment</button></a>
                            <button type="submit" class="btn btn-xs btn-danger">Delete Equipment</button>
                    </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Hardware Information
                        </div>
                        <div class="panel-body">
                            <div class="form-group col-xs-4">
                                <label for="name">Equipment Model</label>
                                <p class="form-control-static">{{$equipment->radio_model or ""}}</p>
                            </div>
                            <div class="form-group col-xs-4">
                                <label for="name">Serial Number</label>
                                <p class="form-control-static">{{ $equipment->get_serial_number() }}</p>

                            </div>
                            <div class="form-group col-xs-4">
                                {{--<label for="name">Operating System</label>--}}
                                {{--<p class="form-control-static">{{$equipment->os}}</p>--}}
                            </div>

                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Antenna Information
                        </div>
                        <div class="panel-body">
                            <div class="form-group col-xs-4">
                                <label for="name">Antenna Model</label>
                                <p class="form-control-static">{{ $equipment->ant_model }}</p>
                            </div>
                            <div class="form-group col-xs-4">
                                <label for="name">Antenna Gain</label>
                                <p class="form-control-static">{{ $equipment->ant_gain }} dBi</p>

                            </div>
                            <div class="form-group col-xs-4">
                                <label for="name">Antenna Height</label>
                                <p class="form-control-static">{{$equipment->ant_height}} meters / {{ $equipment->ant_height +  $equipment->site['altitude'] }} meters</p>
                            </div>

                            <div class="form-group col-xs-4">
                                <label for="name">Antenna Azimuth</label>
                                <p class="form-control-static">{{ $equipment->ant_azimuth }}&deg; true</p>
                            </div>
                            <div class="form-group col-xs-4">
                                <label for="name">Antenna Down Tilt</label>
                                <p class="form-control-static">{{ $equipment->ant_tilt }}&deg;</p>

                            </div>



                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Radio Information
                        </div>
                        <div class="panel-body">
                            <div class="form-group col-xs-4">
                                <label for="name">Radio Power</label>
                                <p class="form-control-static">{{ $equipment->radio_power }} dBi</p>
                            </div>
                            <div class="form-group col-xs-4">
                                <label for="name">EIRP</label>
                                <p class="form-control-static">@if( $equipment->eirp() ){{$equipment->eirp() }} W @endif</p>

                            </div>
                            <div class="form-group col-xs-4">
                                <label for="name">Frequency</label>
                                <p class="form-control-static">{{$equipment->snmp_band}}</p>
                            </div>

                            <div class="form-group col-xs-4">
                                <label for="name">SSID</label>
                                <p class="form-control-static">{{ $equipment->snmp_ssid }}</p>
                            </div>
                            <div class="form-group col-xs-4">
                                <label for="name">Antenna Tilt</label>
                                <p class="form-control-static">{{ $equipment->ant_tilt }}&deg;</p>

                            </div>



                        </div>
                    </div>


                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Notations
                        </div>
                        <div class="panel-body">
                            <div class="form-group col-sm-6">
                                <label for="name">Comments</label>
                                <p class="form-control-static">{{ $equipment->comments }}</p>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="name">Description</label>
                                <p class="form-control-static">{{ $equipment->description }}</p>

                            </div>




                        </div>
                    </div>

                    {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
                    {{--<input type="hidden" name="_method" value="DELETE"/>--}}
                    {{--<a href="{{ url("/equipment/" . $equipment->id . "/edit") }}">--}}
                        {{--<button type="button" class="btn btn-sm btn-success">Edit Equipment</button>--}}
                    {{--</a>--}}

                    {{--<button type="submit" class="btn btn-sm btn-danger">Delete Equipment</button>--}}
                </form>
            </div>


            @if ($equipment->os == "RouterOS" or $equipment->os == "EdgeRouter")
                @include('equipment.parts.tabClients')
            @endif

            <div role="tabpanel" class="tab-pane" id="ips">
                @include('ip.list', ['ips' => $equipment->ips() ])
            </div>

            <div role="tabpanel" class="tab-pane" id="graphs">
                @if( $equipment->librenms_mapping )
                    <?php $wireless = $equipment->libre_query('devices/' . $equipment->librenms_mapping . "/wireless"); ?>

                @if( count($wireless['graphs']))

                    <h3>Wireless</h3>
                    @foreach( $wireless['graphs'] as $graph )
                        @if( $graph['name'] == "device_wireless_clients"  )
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>{{ $graph['desc'] }}</strong><br>

                                    <img src="{{ url("/equipment/" . $equipment->id . "/graph/libre/graphs/wireless/" . $graph['name']) . "/1?height=150&from=-7d" }}">
                                </div>
                            </div>
                        @endif
                    @endforeach
@endif

                <?php $health = $equipment->libre_query('devices/' . $equipment->librenms_mapping . "/health"); ?>
                @if( count($health['graphs']))
                        <h3>Health</h3>

                    @foreach( $health['graphs'] as $graph )
                    @if( $graph['name'] == 'device_temperature'  )
                                    <?php $temperature = $equipment->libre_query('devices/' . $equipment->librenms_mapping . "/health/" . $graph['name']); ?>

                                    <div class="panel panel-default">
                            <div class="panel-body">
                                <strong>{{ $graph['desc'] }}</strong><br>

                                <img src="{{ url("/equipment/" . $equipment->id . "/graph/libre/graphs/health/" . $graph['name']) . "/" . $temperature['graphs'][0]['sensor_id'] . "?height=150&from=-7d" }}">
                            </div>
                        </div>
                    @endif
                        @if(  $graph['name'] == 'device_voltage'  )
                            <?php $voltage = $equipment->libre_query('devices/' . $equipment->librenms_mapping . "/health/" . $graph['name']); ?>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>{{ $graph['desc'] }}</strong><br>

                                    <img src="{{ url("/equipment/" . $equipment->id . "/graph/libre/graphs/health/" . $graph['name']) . "/" . $voltage['graphs'][0]['sensor_id'] . "?height=150&from=-7d" }}">
                                </div>
                            </div>
                        @endif
                @endforeach
                    @endif

                <?php $ports = $equipment->libre_query('devices/' . $equipment->librenms_mapping . "/ports?columns=ifName,ifType,port_id,ifVlan,ifPhysAddress,ifOperStatus"); ?>
                    @if( count($ports['ports']))
                        <h3>Network Ports</h3>

                @foreach( $ports['ports'] as $port )
                    @if( $port['ifType'] == 'ethernetCsmacd' or $port['ifType'] == "ieee80211")
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <strong>
                                    @if( $port['ifOperStatus'] == 'up')
                                        <div style="width: 1em; height: 1em; background-color: green; border-radius: 0.5em; display: inline-block"></div>
                                    @else
                                        <div style="width: 1em; height: 1em; background-color: red; border-radius: 0.5em; display: inline-block"></div>
                                    @endif
                                    {{ $port['ifName'] }}</strong><br>
                                @foreach( $port as $key => $var )

                                    <span><strong>{{$key}}:</strong> {{ $var }}&nbsp;&nbsp;&nbsp;</span>
                                @endforeach

                                <img src="{{ url("/equipment/" . $equipment->id . "/graph/libre/ports/" . $port['ifName']) . "/port_bits?height=150&from=-7d&id=" . $port['port_id'] }}">
                            </div>
                        </div>
                    @endif
                @endforeach
                        @endif
                    @else
                    <div class="alert alert-warning">
                        No LibreNMS Device mapping has been created for this device
                    </div>
@endif
                {{--@foreach( $equipment->graphs() as $graph )--}}
                {{--<div style="float:left">--}}
                {{--<strong>{{$graph['description']}}</strong><br>--}}
                {{--<img src="{{$graph['url']}}?width=260&height=121">--}}
                {{--</div>--}}
                {{--@endforeach--}}
            </div>
            @include('equipment.parts.tabTools')
            @if ($equipment->os == "RouterOS" or $equipment->os == "EdgeRouter")
                @include('equipment.parts.tabOSPF')
            @endif
            @if ($equipment->os == "RouterOS" or $equipment->os == "Cisco IOS" or $equipment->os == "EdgeRouter")
             @include('equipment.parts.tabPorts')
            @endif
            @if ($equipment->os == "DAEnetIP4" )
             @include('equipment.parts.tabSiteMonitor')
            @endif

            {{--<div role="tabpanel" class="tab-pane" id="clients">--}}

            {{--@include('clients.list', ['clients' => $site->clients ])--}}

            {{--</div>--}}
            {{--<div role="tabpanel" class="tab-pane" id="tools">...</div>--}}
        </div>

    </div>


@endsection
