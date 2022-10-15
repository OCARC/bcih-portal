@extends('common.master')
@section('title')
    DNS Zones
@endsection
@section('content')


    <div class="d-flex align-items-center p-3 my-3 rounded shadow-sm bg-light">
        <div class="lh-1">
            <h2 class="h2 mb-0 lh-1">DNS Zones</h2>
        </div>
        <div class="ms-auto">
            <a href="{{ url("/dns-zones/create") }}"><button type="button" class="btn btn-sm btn-success">Create DNS Zone</button></a>

            {{--            @can('sites.create')--}}
            {{--                <a href="{{ url("/site/create") }}" ><button type="button" class="btn btn-sm btn-success">Create Site</button></a>--}}
            {{--            @endcan--}}
        </div>

    </div>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">DNS Zones</li>
    </ol>
              @include('dnszones.list')



@endsection
