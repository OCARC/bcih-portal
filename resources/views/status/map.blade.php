@extends('common.mapwide')
@section('title')
    Coverage Map
@endsection
@section('content')
<style>
    @import url("/css/map.css");

</style>
<script src="http://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="http://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_MAPS_API_KEY')}}&v=3.exp&libraries=places"></script>
<script>
    var statusSourceURL = "{{url("/status.json")}}";

</script>
<div id="map_container" class="map_container" style="position: absolute; top: 52px; bottom: 0px; right: 0px; left: 0px;">
    <div id="map_status" style="position: absolute; top:0px; bottom: 0px; right: 0px; left:0px; line-height: 100%; z-index: 1;">
        Loading...
    </div>
    <div id="map_canvas" style="width:100%;height:100%;">
    </div>
</div>
@endsection
@section('scripts')
    {{--<script src="js/turf.min.js"></script>--}}

<script src="js/map.js"></script>

    <script>
    $(document).ready( function() {

    });

    // Save with and height for full screen mode
    var googleMapWidth = $("#map_container").css('width');
    var googleMapHeight = $("#map_container").css('height');

    $('#btn-enter-full-screen').click(function() {

        $("#map_container").addClass('fullscreen');

        $("#map-canvas").css({
            height: '100%'
        });

        //google.maps.event.trigger(map, 'resize');
        // map.setCenter(newyork);

        // Gui
        $('#btn-enter-full-screen').toggle();
        $('#btn-exit-full-screen').toggle();

        return false;
    });

    $('#btn-exit-full-screen').click(function() {

        $("#map_container").removeClass('fullscreen');


        //google.maps.event.trigger(map, 'resize');
        //map.setCenter(newyork);

        // Gui
        $('#btn-enter-full-screen').toggle();
        $('#btn-exit-full-screen').toggle();
        return false;
    });
</script>
    @endsection