@extends('common.master')
@section('title')
    Sites
@endsection
@section('content')

    <h2>Sites</h2>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Sites</li>
    </ol>
    @include('site.list')


    @can('sites.create')
        <a href="{{ url("/site/create") }}"><button type="button" class="btn btn-sm btn-success">Create Site</button></a>
    @else
        <p>If you need to create a new site please contact a member over the BC Interior HamWAN team.</p>
    @endcan

@endsection
