@extends('common.master')
@section('title')
    Edit Client:
    @if ( $client->snmp_sysName )
        {{ $client->snmp_sysName}}
    @elseif( $client->dhcp_lease() )
        {{ $client->dhcp_lease()->hostname }}
    @else
        Name Not Found
    @endif
@endsection
@section('content')

    <h2>Edit Client:
        @if ( $client->snmp_sysName )
            {{ $client->snmp_sysName}}
        @elseif( $client->dhcp_lease() )
            {{ $client->dhcp_lease()->hostname }}
        @else
            Name Not Found
        @endif
    </h2>

    <div>
@include('clients.form')
        </div>
        @endsection