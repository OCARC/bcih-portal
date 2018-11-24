@extends('common.master')
@section('title')
    PTP Link
@endsection
@section('content')

    <h2>Link: {{ $link->name }}
    </h2>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="{{url("/links")}}">PtP Links</a></li>
        <li class="breadcrumb-item active">{{ $link->name }}</li>
    </ol>
    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#info" aria-controls="info" role="tab" data-toggle="tab">Equipment
                    Info</a></li>

            <li role="presentation"><a href="#ips" aria-controls="ips" role="tab" data-toggle="tab">IP Addresses</a>
            </li>
            <li role="presentation"><a href="#graphs" aria-controls="graphs" role="tab" data-toggle="tab">Graphs</a>
            </li>
            <li role="presentation"><a href="#tools" aria-controls="tools" role="tab" data-toggle="tab">Tools</a></li>

        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="info">

<br>
                <form method="POST" action="{{ url("/links/" . $link->id . "") }}" accept-charset="UTF-8">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE"/>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Link Information
                    </div>
                    <div class="panel-body">
                        <div class="form-group col-xs-4">
                            <label for="name">Link Name</label>
                            <p class="form-control-static">{{ $link->name }}</p>
                        </div>


                        <div class="form-group col-xs-4">
                            <label for="name">Status</label>
                            <p  class="form-control-static">
                                @if ($link->status == "Potential")
                                    <span style="vertical-align:middle;background-color: #e1e1e1">{{ $link->status }}</span>
                                @elseif( $link->status == "Planning")
                                    <span style="vertical-align:middle;background-color: #fff6a6">{{ $link->status }}</span>
                                @elseif( $link->status == "Installed")
                                    <span style="vertical-align:middle;background-color: #aaffaa">{{ $link->status }}</span>
                                @elseif( $link->status == "Equip Failed")
                                    <span style="vertical-align:middle;background-color: #ff6666">{{ $link->status }}</span>
                                @elseif( $link->status == "Problems")
                                    <span style="vertical-align:middle;background-color: #ffd355">{{ $link->status }}</span>
                                @elseif( $link->status == "No Install")
                                    <span style="vertical-align:middle;background-color: #979797">{{ $link->status }}</span>
                                @else
                                    <span style="vertical-align:middle;">{{ $link->status }}</span>
                                @endif

                            </p>
                        </div>

                        {{--<div class="form-group col-xs-4">--}}

                            {{--<label for="name">Site</label>--}}
                            {{--<p class="form-control-static">--}}
                                {{--<a href="{{url("/site/" . $equipment->site_id ) }}">{{$equipment->site['name'] }}--}}
                                    {{--({{$equipment->site['sitecode'] }})</a>--}}
                            {{--</p>--}}
                        {{--</div>--}}
                        {{--<div class="form-group col-xs-4">--}}

                            {{--<label for="name">Owner</label>--}}
                            {{--<p class="form-control-static">--}}
                                {{--<a href="{{url("users/" . $equipment->user_id )}}">{{ $equipment->user->callsign }}</a>--}}
                            {{--</p>--}}
                        {{--</div>--}}

                    </div>
                    <div class="panel-footer text-right">

                            <a href="{{ url("/links/" . $link->id . "/edit") }}"><button type="button" class="btn btn-xs btn-success">Edit Link</button></a>
                            <button type="submit" class="btn btn-xs btn-danger">Delete Link</button>
                    </div>
                    </div>

                </form>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Link Projection
                        </div>
                        <div class="panel-body">
                            <form id="latlong" name="latlong">
                                Lat AP: <input type="text" id="lat1" value="@if($link->ap_site){{$link->ap_site->latitude}}@endif"> <br />
                                Long AP: <input type="text" id="long1" value="@if($link->ap_site){{$link->ap_site->longitude}}@endif"><br /><br />
                                Lat CL: <input type="text" id="lat2" value="@if($link->ap_site){{$link->cl_site->latitude}}@endif"> <br />
                                Long CL: <input type="text" id="long2" value="@if($link->ap_site){{$link->cl_site->longitude}}@endif"><br /><br />
                                Radio Frequency (GHz): <input type="text" id="radio-freq" value="@if($link->ap_equipment){{ ($link->ap_equipment->snmp_frequency/1000) }}@else 5.800 @endif"><br /><br />
                                <div id="slider"></div>
                                <div id="slider-label"></div>
                                <div id="first-tower"></div><div id="first-tower-label"></div>
                                <div id="second-tower"></div><div id="second-tower-label"></div>
                                <button id="submit-lat-long">Generate Elevation Profile</button>
                            </form>
                            <!-- <div id="map-canvas"></div> -->
                            <div id="fresnel-zone-data"></div>
                            <div id="elevationGraphPaper" style=""></div>
                        </div>
                    </div>




                    {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
                    {{--<input type="hidden" name="_method" value="DELETE"/>--}}
                    {{--<a href="{{ url("/equipment/" . $equipment->id . "/edit") }}">--}}
                        {{--<button type="button" class="btn btn-sm btn-success">Edit Equipment</button>--}}
                    {{--</a>--}}

                    {{--<button type="submit" class="btn btn-sm btn-danger">Delete Equipment</button>--}}
            </div>

        </div>

    </div>


@endsection
@section('scripts')
    @parent
    <script data-require="raphael@2.1.0" data-semver="2.1.0" src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/graphael/0.5.1/g.raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/graphael/0.5.1/g.line-min.js"></script>
    <script data-require="jquery@2.0.3" data-semver="2.0.3" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    {{--<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>--}}
    <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDYYYa-Ux3bra8o_52tzUXd2rd_Bvlz4cQ&v=3.exp&libraries=places"></script>
    <link rel="stylesheet" href="style.css" />
    <script src="/js/elevationanalysis.js"></script>
<script>
    /*
* This file is part of RFAnalysisJS.
*
* RFAnalysisJS is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* RFAnalysisJS is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with RFAnalysisJS.  If not, see <http://www.gnu.org/licenses/>.
*/

    $(function() {


        //just for the slider stuff
        var firstTowerHeight = 0;
        var secondTowerHeight = 0;
        var pointsVal = 0;
        $( "#slider" ).slider({
            min:10,
            max:512,
            value:412,
            range:false,
            slide: function(event,ui){
                pointsVal = ui.value;
                $("#slider-label").html("Number of elevation data points are " + pointsVal);
            }});
        pointsVal = $( "#slider" ).slider( "option", "value" );
        $("#slider-label").html("Number of elevation data points are " + pointsVal);


        $("#latlong").on('submit', function (e) { e.preventDefault(); });


        //tower height sliders
        $( "#first-tower" ).slider({
            min:0,
            max:100,
            value:@if($link->ap_equipment){{$link->ap_equipment->ant_height or 0}}@else 0 @endif,
            range:false,
            slide: function(event,ui){
                firstTowerHeight = ui.value;
                $("#first-tower-label").html("First tower is " + ui.value);
            }});
        firstTowerHeight = $( "#first-tower" ).slider( "option", "value" );
        $("#first-tower-label").html("First tower is " + firstTowerHeight + "m");
        $( "#second-tower" ).slider({
            min:0,
            max:100,
            value:@if($link->cl_equipment){{$link->cl_equipment->ant_height or 0}}@else 0 @endif,
            range:false,
            slide: function(event,ui){
                secondTowerHeight = ui.value;
                $("#second-tower-label").html("Second tower is " + ui.value);
            }});
        secondTowerHeight = $( "#second-tower" ).slider( "option", "value" );
        $("#second-tower-label").html("Second tower is " + secondTowerHeight  + "m");

        $('#submit-lat-long').click(function(){
            createRfAnalysis(pointsVal,firstTowerHeight,secondTowerHeight);
        });
    });

    function createRfAnalysis(dataPointsInt,tHeight1,tHeight2)
    {
        var lat1 = $("#lat1").val();
        var long1 = $("#long1").val();
        var lat2 = $("#lat2").val();
        var long2 = $("#long2").val();
        var frequency = $("#radio-freq").val();

        var currentLink = new Link(lat1,long1,lat2,long2,frequency,tHeight1,tHeight2,1);
        var elevationDataArray = getElevationDataArray(lat1,long1,lat2,long2,dataPointsInt,currentLink);
    }

    function getElevationDataArray(lat1,long1,lat2,long2,dataPointsInt,currentLink)
    {
        var elevator = new google.maps.ElevationService;

        var path = [
            {lat: parseFloat(lat1), lng: parseFloat(long1)},
            {lat: parseFloat(lat2), lng: parseFloat(long2)}];

        elevator.getElevationAlongPath({
            'path': path,
            'samples': dataPointsInt
        }, function(elevations, status) {
            currentLink.elevationAnalysis(elevations);
        });
    }

    var map;
    function initialize() {

    }

    google.maps.event.addDomListener(window, 'load', initialize);

</script>
@endsection