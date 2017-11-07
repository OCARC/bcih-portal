@extends('common.master')
@section('title')
    Clients
@endsection
@section('content')

    <h2>Clients</h2>

            @include('clients.list')



    <a href="{{ url("/clients/refresh") }}"><button type="button" class="btn btn-sm btn-info">Poll Clients</button></a>

@endsection
