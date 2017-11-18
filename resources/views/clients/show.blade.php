@extends('common.master')
@section('title')
    Client: @if( $client->dhcp_lease() ){{ $client->dhcp_lease()->hostname }} @else Name Not Found @endif
@endsection
@section('content')

    <h2><img src="{{ $client->icon() }}" style="height: 2em; margin-top: -1em"> Client: @if( $client->dhcp_lease() ){{ $client->dhcp_lease()->hostname }} @else Name Not Found @endif</h2>

    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#info" aria-controls="info" role="tab" data-toggle="tab">Client
                    Info</a></li>
            <li role="presentation"><a href="#tools" aria-controls="tools" role="tab" data-toggle="tab">Tools</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="info">

                <Table class="table table-responsive table-condensed table-striped table-bordered">
                    <tr>
                        <th>Hostname</th>
                        <td>{{$client->dhcp_lease()->hostname or "No Lease Found" }}</td>
                    </tr>
                    <tr>
                        <th>DHCP IP</th>
                        <td>{{$client->dhcp_lease()->ip}}</td>
                    </tr>
                    <tr>
                        <th>Management IP</th>
                        <td>{{$client->management_ip or $client->dhcp_lease()->ip}}</td>
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

                <form method="POST" action="{{ url("/client/" . $client->id . "") }}" accept-charset="UTF-8">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE"/>
                    <a href="{{ url("/client/" . $client->id . "/edit") }}">
                        <button type="button" class="btn btn-sm btn-success">Edit Client</button>
                    </a>

                    <button type="submit" class="btn btn-sm btn-danger">Delete Client</button>
                    <span class="help-block">Clients are automatically discovered and updated it generally does not make sense to delete them.</span>
                </form>
            </div>


            <div role="tabpanel" class="tab-pane" id="tools">
                <h3>Get Configuration</h3>
                <div class="ajaxAction">
                    <div class="ajaxResult"></div>
                    <button class="btn btn-default"
                            onClick="ajaxAction(this,'{{url('clients/' . $client->id . "/fetchConfig")}}')">Fetch
                        Configuration
                    </button>
                </div>
                <h3>Perform Quick Scan</h3>
                <span class="text-danger"><strong>CAUTION:</strong> This action will cause the client to disconnect from the network. If it does not reconnect quickly enough you might not get a result.</span>

                <div class="ajaxAction">
                    <div class="ajaxResult"></div>
                    <button class="btn btn-default"
                            onClick="ajaxAction(this,'{{url('clients/' . $client->id . "/quickScan")}}')">Run Quick Scan
                    </button>
                </div>
                <h3>Retrieve Wireless Stats</h3>

                <div class="ajaxAction">
                    <div class="ajaxResult"></div>
                    <button class="btn btn-default"
                            onClick="ajaxAction(this,'{{url('clients/' . $client->id . "/quickMonitor")}}')">Retrieve
                        Wireless Stats
                    </button>
                </div>

                <h3>Get Spectral History</h3>
                <span class="text-danger"><strong>CAUTION:</strong> This action will cause the client to disconnect from the network. If it does not reconnect quickly enough you might not get a result.</span>
                <div class="ajaxAction">
                    <div class="ajaxResult"></div>
                    <button class="btn btn-default"
                            onClick="ajaxAction(this,'{{url('clients/' . $client->id . "/fetchSpectralHistory")}}')">
                        Fetch Spectral History
                    </button>
                </div>

                <h3>Perform Bandwidth Test</h3>
                <span class="text-warning"><strong>Note:</strong> This can consume a lot of network link bandwidth. Please only test when needed.</span>
                <div class="ajaxAction">
                    <div class="ajaxResult"></div>
                    Target : <select id="target">
                        <option value="44.135.217.21">HEX1.LMK</option>
                        <option value="44.135.217.22">HEX1.BKM</option>
                        <option value="44.135.217.23">HEX1.KUI</option>
                        <option value="44.135.217.24">HEX1.BGM</option>
                    </select>

                    Duration :
                    <select id="duration">
                        <option value="1">1 Seconds</option>
                        <option value="5">5 Seconds</option>
                        <option value="10" selected="selected">10 Seconds</option>
                        <option value="30">30 Seconds</option>
                        <option value="45">45 Seconds</option>
                        <option value="60">60 Seconds</option>
                    </select>

                    Direction :
                    <select id="direction">
                        <option value="both">Both</option>
                        <option value="transmit">Send</option>
                        <option value="receive" selected="selected">Receive</option>
                    </select>
                    <button class="btn btn-default"
                            onClick="ajaxAction(this,'{{url('clients/' . $client->id . "/bwTest")}}?target=' + $('#target').val() + '&duration=' + $('#duration').val() + '&direction=' + $('#direction').val() + '')">
                        Perform Bandwidth Test
                    </button>
                </div>
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
