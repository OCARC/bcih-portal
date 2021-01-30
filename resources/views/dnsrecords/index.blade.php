@extends('common.master')
@section('title')
    DNS Records
@endsection
@section('content')

    <a class="pull-right" href="{{ url("/dns-records/create") }}"><button type="button" class="btn btn-xs btn-success">Create DNS Record</button></a>

    <h2>DNS Records</h2>

    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">DNS Records</li>
    </ol>

    @include('dnsrecords.list')



@endsection
