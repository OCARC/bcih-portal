@extends('common.nonav')
@section('title')
    Coverage Map
@endsection
@section('content')
    <style>
        @import url("/css/map.css");

    </style>
    <script src="http://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDYYYa-Ux3bra8o_52tzUXd2rd_Bvlz4cQ&v=3.exp&libraries=places"></script>
    <script>
        var statusSourceURL = "{{url("/status.json")}}";

    </script>


    <form class="form-inline" style="display: none;">
        <div class="form-group" >
            <label for="exampleInputName2">Client Gain</label>
            <select class="form-control" id="clientGain" onChange="updateOverlays();">
                <option value="001">1dB</option>
                <option value="002">2dB</option>
                <option value="003">3dB</option>
                <option value="004">4dB</option>
                <option value="005">5dB</option>

                <option value="006">6dB</option>
                <option value="007">7dB</option>
                <option value="008">8dB</option>
                <option value="009">9dB</option>
                <option value="010">10dB</option>

                <option value="010">10dB</option>
                <option value="011">11dB</option>
                <option value="012">12dB</option>
                <option value="013">13dB</option>
                <option value="014">14dB</option>
                <option value="015"  selected="selected">15dB</option>
                <option value="016">16dB</option>
                <option value="017">17dB</option>
                <option value="018">18dB</option>
                <option value="019">19dB</option>
                <option value="020">20dB</option>

                <option value="020">20dB</option>
                <option value="021">21dB</option>
                <option value="022">22dB</option>
                <option value="023">23dB</option>
                <option value="024">24dB</option>
                <option value="025">25dB</option>
                <option value="026">26dB</option>
                <option value="027">27dB</option>
                <option value="028">28dB</option>
                <option value="029">29dB</option>
                <option value="030">30dB</option>


            </select>

            <label for="exampleInputName2">Overlay Quality</label>
            <select class="form-control" id="quality" onChange="updateOverlays();">
                <option value="10">Fast</option>
                <option value="6">Medium</option>
                <option value="3">High</option>
                <option value="1">Extreme</option>
            </select>
        
        </div>

        <div id="siteSelect" style="display: inline-block; font-family: courier">
            <div id="siteSelect000"></div>
            <div id="siteSelect120"></div>
            <div id="siteSelect240"></div>
        </div>

        (Slow to Update Be Patient)
    </form>




    <div id="map_container" class="map_container">

        <div id="map_canvas" style="width:100%;height:100%;">
        </div>
    </div>
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