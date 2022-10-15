@extends('common.master')
@section('title')
    Reserved IP Addresses
@endsection
@section('content')

    <div class="d-flex align-items-center p-3 my-3 rounded shadow-sm bg-light">
        <div class="lh-1">
            <h2 class="h2 mb-0 lh-1">IP Addresses</h2>
        </div>
        <div class="ms-auto">
            <a href="{{ url("/ips/create") }}"><button type="button" class="btn btn-sm btn-success">Create IP Record</button></a>

            {{--            @can('sites.create')--}}
            {{--                <a href="{{ url("/site/create") }}" ><button type="button" class="btn btn-sm btn-success">Create Site</button></a>--}}
            {{--            @endcan--}}
        </div>

    </div>

    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">IP Addresses</li>
    </ol>
              @include('ip.list')



@endsection
