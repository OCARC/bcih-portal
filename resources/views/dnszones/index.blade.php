@extends('common.master')
@section('title')
    DNS Zones
@endsection
@section('content')


    <h2>DNS Zones</h2>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">DNS Zones</li>
    </ol>
              @include('dnszones.list')

    <a href="{{ url("/dns-zones/create") }}"><button type="button" class="btn btn-sm btn-success">Create DNS Zone</button></a>


@endsection
