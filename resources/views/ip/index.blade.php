@extends('common.master')
@section('title')
    Reserved IP Addresses
@endsection
@section('content')


    <h2>Reserved IP Addresses    </h2>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">IP Addresses</li>
    </ol>
              @include('ip.list')

    <a href="{{ url("/ips/create") }}"><button type="button" class="btn btn-sm btn-success">Create IP Record</button></a>


@endsection
