@extends('common.master')
@section('title')
    Client: @if( $client->dhcp_lease() ){{ $client->dhcp_lease()->hostname }} @else Name Not Found @endif
@endsection
@section('content')


    <div class="d-flex align-items-center p-3 my-3 rounded shadow-sm bg-light">
        <img class="me-3" src="{{ $client->icon() }}" alt="" width="64" height="64" style="margin-top: -1.5rem;    margin-left: -1rem;    margin-bottom: -1.5rem;">
        <div class="lh-1">
            <h2 class="h2 mb-0 lh-1"><span class="text-muted">Client:</span> @if ( $client->snmp_sysName )
                    {{ $client->snmp_sysName}}
                @elseif( $client->dhcp_lease() )
                    {{ $client->dhcp_lease()->hostname }}
                @else
                    Name Not Found
                @endif</h2>
        </div>
    </div>


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
        @include('common.tabs')


        <!-- Tab panes -->
        @include('common.tabpanels')


    </div>



@endsection
