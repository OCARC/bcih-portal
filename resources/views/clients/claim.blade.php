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
                <a href="{{url("/client/".$client->id)}}">{{ $client->snmp_sysName}}</a>
            @elseif( $client->dhcp_lease() )
                <a href="{{url("/client/".$client->id)}}">{{ $client->dhcp_lease()->hostname }}</a>
            @else
                Name Not Found
            @endif</li>
        <li class="breadcrumb-item">Claim</li>

    </ol>
    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#info" aria-controls="info" role="tab" data-toggle="tab">Claim Client</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="info">

<br>
                <form method="POST" action="{{ url("/client/" . $client->id . "/claim") }}" accept-charset="UTF-8">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="action" value="claim"/>

                    <p>To claim this device please provide its complete serial number</p>
                    Serial: <input type="text" name="serial" id="serial">


                </form>


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
