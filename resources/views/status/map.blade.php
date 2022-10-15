@extends('common.mapwide')
@section('title')
    Coverage Map
@endsection
@section('content')

    @include('status.parts.mapContainer')
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