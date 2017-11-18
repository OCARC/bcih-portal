@extends('common.master')
@section('title')
    Equipment
@endsection
@section('content')

    <h2><img src="{{ $equipment->icon() }}" style="height: 2em; margin-top: -1em"> Equipment: {{ $equipment->hostname }}</h2>

    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#info" aria-controls="info" role="tab" data-toggle="tab">Equipment
                    Info</a></li>
            <li role="presentation"><a href="#clients" aria-controls="clients" role="tab" data-toggle="tab">Clients</a>
            </li>
            <li role="presentation"><a href="#graphs" aria-controls="graphs" role="tab" data-toggle="tab">Graphs</a>
            </li>
            <li role="presentation"><a href="#tools" aria-controls="tools" role="tab" data-toggle="tab">Tools</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="info">

                <Table class="table table-responsive table-condensed table-striped table-bordered">
                    <tr>
                        <th>Hostname</th>
                        <td>{{$equipment->hostname}}</td>
                        <th>Management IP</th>
                        <td>{{$equipment->management_ip}}</td>
                    </tr>
                    <tr>
                        <th>Operating System</th>
                        <td>{{$equipment->os or "unset"}}</td>
                        <th>Cacti Mapping</th>
                        <td>{{$equipment->cacti_id or "unset"}}</td>
                    </tr>
                    <tr>
                        <th>Site</th>
                        <td><a href="{{url("/site/" . $equipment->site_id ) }}">{{$equipment->site['name'] }}
                                ({{$equipment->site['sitecode'] }})</a></td>
                        <th>Status</th>
                        @if ($equipment->status == "Potential")
                            <td style="vertical-align:middle;background-color: #e1e1e1">{{ $equipment->status }}</td>
                        @elseif( $equipment->status == "Planning")
                            <td style="vertical-align:middle;background-color: #fff6a6">{{ $equipment->status }}</td>
                        @elseif( $equipment->status == "Installed")
                            <td style="vertical-align:middle;background-color: #aaffaa">{{ $equipment->status }}</td>
                        @elseif( $equipment->status == "Equip Failed")
                            <td style="vertical-align:middle;background-color: #ff6666">{{ $equipment->status }}</td>
                        @elseif( $equipment->status == "Problems")
                            <td style="vertical-align:middle;background-color: #ffd355">{{ $equipment->status }}</td>
                        @elseif( $equipment->status == "No Install")
                            <td style="vertical-align:middle;background-color: #979797">{{ $equipment->status }}</td>
                        @else
                            <td style="vertical-align:middle;">{{ $equipment->status }}</td>
                        @endif
                    </tr>
                    <tr>
                        <th>Owner</th>
                        <td><a href="{{url("/users/" . $equipment->owner_id ) }}">{{$equipment->owner->callsign}}</a>
                        </td>
                    </tr>

                    <tr>
                        <th>Site Altitude</th>
                        <td>{{$equipment->site->altitude or "?" }} meters</td>
                    </tr>



                    <tr>
                        <th>Antenna Model</th>
                        <td>{{$equipment->ant_model or ""}}</td>
                        <th>Equipment Model</th>
                        <td>{{$equipment->radio_model or ""}}</td>
                    </tr>
                    <tr>
                        <th>Antenna Gain</th>
                        <td>@if( $equipment->ant_gain ){{$equipment->ant_gain }} dBi @endif</td>
                        <th>Radio Power</th>
                        <td>@if( $equipment->radio_power ){{$equipment->radio_power }} dBm @endif</td>
                    </tr>

                    <tr>
                        <th>Antenna Height</th>
                        <td>{{$equipment->ant_height or "?"}} meters</td>
                        <th>EIRP</th>
                        <td>@if( $equipment->eirp() ){{$equipment->eirp() }} W @endif</td>
                    </tr>

                    <tr>
                        <th>Antenna Azimuth</th>
                        <td>{{$equipment->ant_azimuth or "?"}}&deg; True</td>
                    </tr>
                    <tr>
                        <th>Antenna Tilt</th>
                        <td>{{$equipment->ant_tilt or "?"}}&deg;</td>
                    </tr>

                    <tr>
                        <th>Comment</th>
                        <td colspan="3">{{$equipment->comments}}</td>
                    </tr>

                    <tr>
                        <th>Description</th>
                        <td colspan="3">{{$equipment->description}}</td>
                    </tr>
                </Table>


                <form method="POST" action="{{ url("/equipment/" . $equipment->id . "") }}" accept-charset="UTF-8">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE"/>
                    <a href="{{ url("/equipment/" . $equipment->id . "/edit") }}">
                        <button type="button" class="btn btn-sm btn-success">Edit Equipment</button>
                    </a>

                    <button type="submit" class="btn btn-sm btn-danger">Delete Equipment</button>
                </form>
            </div>
            <div role="tabpanel" class="tab-pane" id="clients">

                @include('clients.list', ['clients' => $equipment->clients ])

            </div>
            <div role="tabpanel" class="tab-pane text-center" id="graphs">


                {{--<img src="{{url('/equipment/' . $equipment->id . "/graph/temperature")}}" style="width: 100%; min-width: 300px;">--}}
                {{--<img src="{{url('/equipment/' . $equipment->id . "/graph/voltage")}}" style="width: 100%; min-width: 300px;">--}}

            @foreach( $equipment->graphs as $graph )
                    <img src="{{$graph->url(2)}}" style="width: 48%; min-width: 300px;">
                @endforeach
            </div>
            <div role="tabpanel" class="tab-pane" id="tools">

                @if ($equipment->os == 'RouterOS')
                    <h3>Get Configuration</h3>
                    <div class="ajaxAction">
                        <div class="ajaxResult"></div>
                        <button class="btn btn-default"
                                onClick="ajaxAction(this,'{{url('equipment/' . $equipment->id . "/fetchConfig")}}')">
                            Fetch Configuration
                        </button>
                    </div>

                    <h3>Get POE Status</h3>
                    <div class="ajaxAction">
                        <div class="ajaxResult"></div>
                        <button class="btn btn-default"
                                onClick="ajaxAction(this,'{{url('equipment/' . $equipment->id . "/fetchPOE")}}')">Fetch
                            POE Status
                        </button>
                    </div>

                    <h3>Get Spectral History</h3>
                    <span class="text-warning"><strong>CAUTION:</strong> This will disconnect clients, be careful using on link radios.</span>
                    <div class="ajaxAction">
                        <div class="ajaxResult"></div>
                        <button class="btn btn-default"
                                onClick="ajaxAction(this,'{{url('equipment/' . $equipment->id . "/fetchSpectralHistory")}}')">
                            Fetch Spectral History
                        </button>
                    </div>

                    <h3>Perform Bandwidth Test</h3>
                    <span class="text-warning"><strong>Note:</strong> This can consume a lot of network link bandwidth. Please only test when needed.</span>
                    <div class="ajaxAction">
                        <div class="ajaxResult"></div>
                        Target : <select id="target">
                            <option value="44.135.217.21">HEX1.LMK</option>
                            <option value="44.135.217.22">HEX1.BKM</option>
                            <option value="44.135.217.23">HEX1.KUI</option>
                            <option value="44.135.217.24">HEX1.BGM</option>
                        </select>

                        Duration :
                        <select id="duration">
                            <option value="1">1 Seconds</option>
                            <option value="5">5 Seconds</option>
                            <option value="10" selected="selected">10 Seconds</option>
                            <option value="30">30 Seconds</option>
                            <option value="45">45 Seconds</option>
                            <option value="60">60 Seconds</option>
                        </select>

                        Direction :
                        <select id="direction">
                            <option value="both">Both</option>
                            <option value="transmit">Send</option>
                            <option value="receive" selected="selected">Receive</option>
                        </select>
                        <button class="btn btn-default"
                                onClick="ajaxAction(this,'{{url('equipment/' . $equipment->id . "/bwTest")}}?target=' + $('#target').val() + '&duration=' + $('#duration').val() + '&direction=' + $('#direction').val() + '')">
                            Perform Bandwidth Test
                        </button>
                    </div>
                @endif
            </div>

            {{--<div role="tabpanel" class="tab-pane" id="clients">--}}

            {{--@include('clients.list', ['clients' => $site->clients ])--}}

            {{--</div>--}}
            {{--<div role="tabpanel" class="tab-pane" id="tools">...</div>--}}
        </div>

    </div>


@endsection
