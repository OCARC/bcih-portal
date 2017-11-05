@extends('common.wide')
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
    var statusSourceURL = "http://portal.hamwan.ca/status.json";

</script>
<script src="js/map.js"></script>


<form class="form-inline">
    <div class="form-group">
        <label for="exampleInputName2">Client Gain</label>
        <select class="form-control" id="clientGain" onChange="initialize();">
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
        </div>

    <div class="form-group">
        <label for="exampleInputName2">Client Gain</label>
        <select class="form-control" id="showSectors" onChange="initialize();">
            <option value="ALL">All</option>
            <option value="000">000&deg;</option>
            <option value="120">120&deg;</option>
            <option value="240">240&deg;</option>

            <option value="000.120">000&deg; and 120&deg;</option>
            <option value="000.240">000&deg; and 240&deg;</option>

            <option value="120.240">120&deg; and 240&deg;</option>

        </select>
            </div>
    <div class="checkbox">
        <label>
            <input name="showSites[]"  onChange="initialize();" value="BGM" type="checkbox"> BGM
        </label>
        <label>
            <input name="showSites[]" checked="true" onChange="initialize();" value="LMK" type="checkbox"> LMK
        </label>
        <label>
            <input name="showSites[]"  onChange="initialize();" value="BKM" type="checkbox"> BKM
        </label>
        <label>
            <input name="showSites[]" onChange="initialize();" value="KUI" type="checkbox"> KUI
        </label>

        <label>
            <input name="showSites[]"  onChange="initialize();" value="APX" type="checkbox"> APX
        </label>

        <label>
            <input name="showSites[]"  onChange="initialize();" value="OKM" type="checkbox"> OKM
        </label>
    </div>
    (Slow to Update Be Patient)
        </form>




<div id="map_container" class="map_container">

    <div id="map_canvas" style="width:100%;height:100%;">
    </div>
</div>

<script>
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