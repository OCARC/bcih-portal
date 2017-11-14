@extends('common.master')
@section('title')
    Equipment
@endsection
@section('content')

    <h2>Equipment: {{ $equipment->hostname }}</h2>

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
                    </tr>
                    <tr>
                        <th>Management IP</th>
                        <td>{{$equipment->management_ip}}</td>
                    </tr>
                    <tr>
                        <th>Operating System</th>
                        <td>{{$equipment->os or "unset"}}</td>
                    </tr>
                    <tr>
                        <th>Site</th>
                        <td><a href="{{url("/site/" . $equipment->site_id ) }}">{{$equipment->site['name'] }}
                                ({{$equipment->site['sitecode'] }})</a></td>
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
                        <th>Antenna Height</th>
                        <td>{{$equipment->ant_height or "?"}} meters</td>
                    </tr>

                    <tr>
                        <th>Antenna Azimuth</th>
                        <td>{{$equipment->ant_azimuth or "?"}}&deg;</td>
                    </tr>
                    <tr>
                        <th>Antenna Tilt</th>
                        <td>{{$equipment->ant_tilt or "?"}}&deg;</td>
                    </tr>
                    <tr>
                        <th>Antenna Model</th>
                        <td>{{$equipment->ant_model or "?"}}</td>
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


                <img src="{{url('/equipment/' . $equipment->id . "/graph/temperature")}}" style="width: 100%; min-width: 300px;">
                <img src="{{url('/equipment/' . $equipment->id . "/graph/voltage")}}" style="width: 100%; min-width: 300px;">

            @foreach( $equipment->graphs as $graph )
                    <img src="{{$graph->url(2)}}" style="width: 48%; min-width: 300px;">
                @endforeach
            </div>
            <div role="tabpanel" class="tab-pane" id="tools">

                @if ($equipment->os == 'RouterOS')
                    <h2>Get Configuration</h2>
                    <div class="ajaxAction">
                        <div class="ajaxResult"></div>
                        <button class="btn btn-default"
                                onClick="ajaxAction(this,'{{url('equipment/' . $equipment->id . "/fetchConfig")}}')">
                            Fetch Configuration
                        </button>
                    </div>

                    <h2>Get POE Status</h2>
                    <div class="ajaxAction">
                        <div class="ajaxResult"></div>
                        <button class="btn btn-default"
                                onClick="ajaxAction(this,'{{url('equipment/' . $equipment->id . "/fetchPOE")}}')">Fetch
                            POE Status
                        </button>
                    </div>

                    <h2>Get Spectral History</h2>
                    <span class="text-warning"><strong>CAUTION:</strong> This will disconnect clients, be careful using on link radios.</span>
                    <div class="ajaxAction">
                        <div class="ajaxResult"></div>
                        <button class="btn btn-default"
                                onClick="ajaxAction(this,'{{url('equipment/' . $equipment->id . "/fetchSpectralHistory")}}')">
                            Fetch Spectral History
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
