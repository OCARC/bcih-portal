@extends('common.master')
@section('title')
    Client: @if( $client->dhcp_lease() ){{ $client->dhcp_lease()->hostname }} @else Name Not Found @endif
@endsection
@section('content')



    <h2><img src="{{ $client->icon() }}" style="height: 2em; margin-top: -1em"> Client: @if ( $client->snmp_sysName )
            {{ $client->snmp_sysName}}
        @elseif( $client->dhcp_lease() )
            {{ $client->dhcp_lease()->hostname }}
        @else
            Name Not Found
        @endif</h2>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="{{url("/clients")}}">Clients</a></li>
        <li class="breadcrumb-item active">@if ( $client->snmp_sysName )
                {{ $client->snmp_sysName}}
            @elseif( $client->dhcp_lease() )
                {{ $client->dhcp_lease()->hostname }}
            @else
                Name Not Found
            @endif</li>
    </ol>
    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#info" aria-controls="info" role="tab" data-toggle="tab">Client
                    Info</a></li>
            <li role="presentation"><a href="#tools" aria-controls="tools" role="tab" data-toggle="tab">Tools</a></li>
            <li role="presentation"><a href="#snmp" aria-controls="snmp" role="tab" data-toggle="tab">SNMP</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="info">

<br>
                <form method="POST" action="{{ url("/client/" . $client->id . "") }}" accept-charset="UTF-8">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE"/>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Client Information
                        </div>
                        <div class="panel-body">
                            <div class="form-group col-xs-4">
                                <label for="name">Name</label>
                                <p class="form-control-static">{{ $client->friendly_name() }}</p>
                            </div>
                            <div class="form-group col-xs-4">
                                <label for="name">Management IP</label>
                                <p class="form-control-static">{{ $client->management_ip ? $client->management_ip : $client->dhcp_lease()->ip }}</p>

                            </div>
                            <div class="form-group col-xs-4">
                                <label for="name">DHCP IP</label>
                                <p class="form-control-static">{{$client->dhcp_lease()->ip}}</p>
                            </div>

                            <div class="form-group col-xs-4">
                                <label for="name">LibreNMS Status</label>
                                <p  class="form-control-static">
                                    <span style="display: inline-block;" class="status-col {{ $client->libre_status_class() }}"></span>
                                    {{ $client->libre_alerts(2)->count()  }} Warnings, {{ $client->libre_alerts(1)->count()  }} Alerts
                                </p>
                            </div>

                            <div class="form-group col-xs-4">
                                <label for="name">Last Heard</label>
                                <p  class="form-control-static">
                                    <span style="display: inline-block;" class="status-col success {{ $client->last_heard_class() }}"></span>
                                    {{ number_format($client->last_heard_ago(),0) }} minutes ago

                                </p>
                            </div>
                            <div class="form-group col-xs-4">

                                <label for="name">Connected Site</label>
                                <p class="form-control-static">
                                    <a href="{{url("/site/" . $client->site_id ) }}">{{$client->site['name'] }}
                                        ({{$client->site['sitecode'] }})</a>
                                </p>
                            </div>
                            <div class="form-group col-xs-4">

                                <label for="name">Owner</label>
                                <p class="form-control-static">
                                    <a href="{{url("users/" . $client->user_id )}}">{{ $client->user->callsign }}</a>
                                </p>
                            </div>

{{--                            <div class="form-group col-xs-4">--}}

{{--                                <label for="name">Last Seen</label>--}}
{{--                                <p class="form-control-static">--}}
{{--                                    {{ $client->last_ping_timestamp }} ({{$client->last_ping}}ms)--}}
{{--                                </p>--}}
{{--                            </div>--}}


                            {{--<div class="form-group col-xs-4">--}}

                                {{--<label for="name">Owner</label>--}}
                                {{--<p class="form-control-static">--}}
                                    {{--<a href="{{url("users/" . $client->user_id )}}">{{ $client->user->callsign }}</a>--}}
                                {{--</p>--}}
                            {{--</div>--}}

                        </div>
                        <div class="panel-footer text-right">
                            @if( $client->canBeClaimed() == true )
                            <a class="btn btn-xs btn-info" href="{{ url("/clients/" . $client->id . "/claim") }}">Claim Client</a>
                            @endif
                                @if( $client->canBeReleased() ==true )
                            <a class="btn btn-xs btn-warning" href="{{ url("/clients/" . $client->id . "/release") }}">Release Client</a>
                                @endif
|
                            <a href="{{ url("/clients/" . $client->id . "/edit") }}"><button type="button" class="btn btn-xs btn-success">Edit Client</button></a>
                            <button type="submit" class="btn btn-xs btn-danger">Delete Client</button>
                        </div>
                    </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Connection Information
                    </div>

                    <div class="panel-body">
                        <div class="form-group col-xs-3">

                            <label for="name">Chain 0</label>
                            <p class="form-control-static">
                                <strong>RX:</strong> {{ $client->snmp_rx_strength_ch0 }} dBm /
                                <strong>TX:</strong> {{ $client->snmp_tx_strength_ch0 }} dBm
                            </p>
                        </div>

                        <div class="form-group col-xs-3">

                            <label for="name">Chain 1</label>
                            <p class="form-control-static">
                                <strong>RX:</strong> {{ $client->snmp_rx_strength_ch1 }} dBm /
                                <strong>TX:</strong> {{ $client->snmp_tx_strength_ch1 }} dBm
                            </p>
                        </div>

                        <div class="form-group col-xs-3">

                            <label for="name">                                    Signal to Noise</label>
                            <p class="form-control-static">
                                {{ $client->snmp_signal_to_noise }} dB
                            </p>
                        </div>

                        <div class="form-group col-xs-3">

                            <label for="name">                                    Signal Strength</label>
                            <p class="form-control-static">
                                {{ $client->snmp_strength }} dB
                            </p>
                        </div>
                    </div>
                </div>
                </form>


            </div>



            @include('clients.show-tabTools')
            @include('clients.show-tabSNMP')

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
