@extends('common.master')
@section('title')
    PtP Links
@endsection
@section('content')


    <h2>Point to Point Links</h2>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">PtP Links</li>
    </ol>
    <h3>Links</h3>

    @include('links.list')
    <a href="{{ url("/links/create") }}"><button type="button" class="btn btn-sm btn-success">Create Link Record</button></a>

    <h3>Radios</h3>
    @include('clients.list')



@endsection
