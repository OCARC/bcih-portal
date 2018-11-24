<div role="tabpanel" class="tab-pane active" id="siteInfo">



    <div id="map_container" class="map_container" style="height: 300px; width: 100%;  border-left: 1px solid #dddddd;  border-right: 1px solid #dddddd; position: relative">
            <div id="map_status" style="position: absolute; top:0px; bottom: 0px; right: 0px; left:0px; line-height: 100%; z-index: 1;">
                Loading...
            </div>
            <div id="map_canvas" style="width:100%;height:100%;">
            </div>
        </div>
    <div class="row" style="padding: .25em;;
    border: 1px solid #dddddd;
    margin-left: 0px;
    margin-right: 0px;
    border-top: none;
    border-radius: 0px 0px 1em 1em; background-color: #f9f9f9">
        <div class="col-md-2">
            <strong>Site Code: </strong> {{$site->sitecode}}
        </div>
        <div class="col-md-2">
            <strong>Altitude: </strong> {{$site->altitude}}m
        </div>

        <div class="col-md-3">
            <strong>Coordinates: </strong> {{ number_format($site->latitude,3)}}, {{ number_format($site->longitude,3) }}
        </div>
        <div class="col-md-2">
            <strong>Status: </strong> {{$site->status}}
        </div>
        <div class="col-md-3 text-right">
            <form method="POST" action="{{ url("/site/" . $site->id . "") }}" accept-charset="UTF-8">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE"/>
                <a href="{{ url("/site/" . $site->id . "/edit") }}"><button type="button" class="btn btn-xs btn-success">Edit Site</button></a>

                <button type="submit" class="btn btn-xs btn-danger" disabled="true">Delete Site</button>
            </form>
        </div>
    </div>
    <div id="equipment_container" class="row">
        <div class="col-lg-12">
            <h5>Equipment <a href="{{ url("/equipment/create") }}?site={{ $site->id}}"><button type="button" class="pull-right btn btn-xs btn-success">Create Equipment</button></a></h5>
        </div>
        <div class="col-lg-12">
            @foreach ($site->equipment as $equipment)
                <div class="text-center equipment-block" style=" border-color: {{ $equipment->getHealthColor() }}">

                    <a href="{{ url( "/equipment/" . $equipment->id ) }}" style="font-size: 11px;">
                        @if( $equipment->libre_wireless('clients')->sensor_current >= 1 )
                            <div class="tl-corner-badge" style=" border-color: {{ $equipment->getHealthColor() }}">
                                {{$equipment->libre_wireless('clients')->sensor_current}}
                            </div>
                        @endif
                        {{--<div class="text-center" style="width: 126px; display:inline-block; background-color: {{ $equipment->getHealthColor() }}">--}}

                        {{--{{ $equipment->getHealthStatus() }}</div>--}}
                        <img src="{{$equipment->icon() }}" style="width:64px; height: 64px;"><br>
                        {{$equipment->hostname}}</a><br>

                </div>
            @endforeach
        </div>

    </div>

    <div id="equipment_container" class="row">
        <div class="col-lg-12">
            <h5>Clients</h5>
        </div>
        <div class="col-lg-12">
            @foreach ($site->clients as $client)

                 @if ( $client->updated_at->diffInMinutes(\Carbon\Carbon::now()) <= 30)
                <div class="text-center equipment-block" style=" border-color: {{ $client->strengthColor() }}; @if ($client->type == 'link')border-style: dashed @endif">

                    <a href="{{ url( "/clients/" . $client->id ) }}" style="font-size: 11px;">
                        <div class="tl-corner-badge" style=" border-color: {{ $client->strengthColor() }}">
                        {{$client->snmp_strength}}dBm
                        </div>
                        @if ($client->type == 'link')
                            <div class="tr-corner-badge" style=" border-color: {{ $client->strengthColor() }}">
                                Link
                            </div>
                        @endif
                            {{--@if( $client->libre_wireless('clients')->sensor_current >= 1 )--}}
                            {{--<div class="tl-corner-badge" style=" border-color: {{ $client->getHealthColor() }}">--}}
                                {{--{{$client->libre_wireless('clients')->sensor_current}}--}}
                            {{--</div>--}}
                        {{--@endif--}}
                        {{--<div class="text-center" style="width: 126px; display:inline-block; background-color: {{ $client->getHealthColor() }}">--}}

                        {{--{{ $client->getHealthStatus() }}</div>--}}
                        <img src="{{$client->icon() }}" style="width:64px; height: 64px;"><br>
                        {{$client->snmp_sysName or $client->mac_address}}</a><br>

                </div>
                @endif
            @endforeach
        </div>
    </div>
    {{--<Table class="table table-responsive table-condensed table-striped table-bordered">--}}
        {{--<tr>--}}
            {{--<th>Site Name</th>--}}
            {{--<td>{{$site->name}}</td>--}}
        {{--</tr>--}}
        {{--<tr>--}}
            {{--<th>Site Code</th>--}}
            {{--<td>{{$site->sitecode}}</td>--}}
        {{--</tr>--}}
        {{--<tr>--}}
            {{--<th>Latitude</th>--}}
            {{--<td>{{$site->latitude}}</td>--}}
        {{--</tr>--}}
        {{--<tr>--}}
            {{--<th>Longitude</th>--}}
            {{--<td>{{$site->longitude}}</td>--}}
        {{--</tr>--}}
        {{--<tr>--}}
            {{--<th>Altitude</th>--}}
            {{--<td>{{$site->altitude}}m</td>--}}
        {{--</tr>--}}
        {{--<tr>--}}
            {{--<th>Owner</th>--}}
            {{--<td>{{$site->user_id}}</td>--}}
        {{--</tr>--}}
        {{--<tr>--}}
            {{--<th>Comment</th>--}}
            {{--<td>{{$site->comment}}</td>--}}
        {{--</tr>--}}
    {{--</table>--}}





</div>
<script>
    var statusSourceURL = "{{url("/status.json")}}";

</script>
<script src="http://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDYYYa-Ux3bra8o_52tzUXd2rd_Bvlz4cQ&v=3.exp&libraries=places"></script>
<script src="/js/map.js"></script>

