@extends('common.master')
@section('title')
    PTP Link
@endsection
@section('content')
    <div class="d-flex align-items-center p-3 my-3 rounded shadow-sm bg-light">
{{--        <img class="me-3" src="{{ $equipment->icon() }}" alt="" width="64" height="64" style="margin-top: -1.5rem;    margin-left: -1rem;    margin-bottom: -1.5rem;">--}}
        <div class="lh-1">
            <h2 class="h2 mb-0 lh-1"><span class="text-muted">Link:</span> {{ $link->name }}
        </div>
    </div>

    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="{{url("/links")}}">PtP Links</a></li>
        <li class="breadcrumb-item active">{{ $link->name }}</li>
    </ol>
    <div>

        <!-- Nav tabs -->
        @include('common.tabs')

        <!-- Tab panes -->
        @include('common.tabpanels')


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
    <script src="http://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_MAPS_API_KEY')}}&v=3.exp&libraries=places"></script>
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
            value:@if($link->ap_equipment){{$link->ap_equipment->ant_height ?? 0}}@else 0 @endif,
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
            value:@if($link->cl_equipment){{$link->cl_equipment->ant_height ?? 0}}@else 0 @endif,
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
        console.log(currentLink);
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

        console.log(elevator);
    }

    var map;
    function initialize() {

    }

    google.maps.event.addDomListener(window, 'load', initialize);

</script>
@endsection