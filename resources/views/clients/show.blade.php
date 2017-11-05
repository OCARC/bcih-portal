@extends('common.master')
@section('title')
    Client
@endsection
@section('content')

    <h2>Client: {{ $client->radio_name }}</h2>

    <a href="{{ url('client/' . $client->id . "/edit") }}">edit</a>
    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#info" aria-controls="info" role="tab" data-toggle="tab">Client Info</a></li>
            <li role="presentation"><a href="#equipment" aria-controls="equipment" role="tab" data-toggle="tab">Equipment</a></li>
            <li role="presentation"><a href="#clients" aria-controls="clients" role="tab" data-toggle="tab">Clients</a></li>
            <li role="presentation"><a href="#graphs" aria-controls="graphs" role="tab" data-toggle="tab">Graphs</a></li>
            <li role="presentation"><a href="#tools" aria-controls="tools" role="tab" data-toggle="tab">Tools</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="info">

<Table class="table table-responsive table-condensed table-striped table-bordered">
    <tr>
        <th>Hostname</th>
        <td>{{$client->dhcp_lease->hostname or "No Lease Found" }}</td>
    </tr>
    <tr>
        <th>DHCP IP</th>
        <td>{{$client->dhcp_lease->ip}}</td>
    </tr>
    <tr>
        <th>Management IP</th>
        <td>{{$client->management_ip or $client->dhcp_lease->ip}}</td>
    </tr>

    <tr>
        <th>Owner</th>
        <td>{{$client->owner_id}}</td>
    </tr>

    <tr>
        <th>Location</th>
        <td>{{$client->latitude}}, {{$client->longitude}}</td>
    </tr>

    </table>
                <Table class="table table-responsive table-condensed table-striped table-bordered">
                    <tr>
                        <th>Signal to Noise</th>
                        <td> {{ $client->snmp_signal_to_noise }} dBm</td>
                    </tr>
                    <tr>
        <th>Chain 0 TX</th>
        <td> {{ $client->snmp_tx_strength_ch0 }} dBm</td>
    </tr>

    <tr>
        <th>Chain 0 RX</th>
        <td> {{ $client->snmp_rx_strength_ch0 }} dBm</td>
    </tr>

                    <tr>
                        <th>Chain 1 TX</th>
                        <td> {{ $client->snmp_tx_strength_ch1 }} dBm</td>
                    </tr>

                    <tr>
                        <th>Chain 1 RX</th>
                        <td> {{ $client->snmp_rx_strength_ch1 }} dBm</td>
                    </tr>

</Table>


            </div>

            <div role="tabpanel" class="tab-pane" id="tools">
                <h2>Get Configuration</h2>
                <div class="ajaxAction">
                    <div class="ajaxResult"></div>
                    <button class="btn btn-default" onClick="ajaxAction(this,'{{url('clients/' . $client->id . "/fetchConfig")}}')">Fetch Configuration</button>
                </div>
                </div>

        </div>

    </div>


    {{--<script>--}}

        {{--function updateSNMP( loop ) {--}}
            {{--jQuery.getJSON( "{{ $client->id }}/snmp", function( data ) {--}}
{{--console.log(data);--}}


                {{--//                var items = [];--}}
                {{--$.each( data, function( key, val ) {--}}
                    {{--if ( key.indexOf(".1.3.6.1.4.1.14988.1.1.1.2.1.13") != -1 ) {--}}
                        {{--jQuery('#chain0rx').html( val );--}}
                    {{--}--}}
                    {{--if ( key.indexOf(".1.3.6.1.4.1.14988.1.1.1.2.1.14") != -1 ) {--}}
                        {{--jQuery('#chain0tx').html( val );--}}
                    {{--}--}}
                    {{--//items.push( "<li id='" + key + "'>" + val + "</li>" );--}}
                {{--});--}}
{{--//--}}
{{--//                $( "<ul/>", {--}}
{{--//                    "class": "my-new-list",--}}
{{--//                    html: items.join( "" )--}}
{{--//                }).appendTo( "body" );--}}
            {{--});--}}
            {{--if (loop) {--}}
                {{--setTimeout( function() { updateSNMP(true), 5000});--}}
            {{--}--}}
        {{--}--}}

        {{--$(document).ready( function() {--}}
            {{--updateSNMP(true);--}}
        {{--});--}}


    {{--</script>--}}

@endsection
