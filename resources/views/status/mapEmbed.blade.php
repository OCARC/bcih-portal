@extends('common.nonav')
@section('title')
    Coverage Map
@endsection
@section('content')
    @include('status.parts.mapContainer')
@endsection
@section('scripts')
    <script src="{{ url("js/map.js") }}"></script>

    <script>
        $(document).ready( function() {
            $.getJSON( "{{ url('coverages') }}", function(data) {
                for( k in data ) {
                    $('#siteSelect000').append(" &nbsp;<label style='margin-bottom: 0px;'><input name='showSites[]' onChange='updateOverlays();' value='"+k+"|000' type='checkbox'> " + k + " 000&deg;</label>");
                    $('#siteSelect120').append(" &nbsp;<label style='margin-bottom: 0px;'><input name='showSites[]' onChange='updateOverlays();' value='"+k+"|120' type='checkbox'> " + k + " 120&deg;</label>");
                    $('#siteSelect240').append(" &nbsp;<label style='margin-bottom: 0px;'><input name='showSites[]' onChange='updateOverlays();' value='"+k+"|240' type='checkbox'> " + k + " 240&deg;</label>");

                }
            });

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