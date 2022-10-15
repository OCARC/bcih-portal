@extends('common.master')
@section('title')
    Subnet
@endsection
@section('content')

    <div class="d-flex align-items-center p-3 my-3 rounded shadow-sm bg-light">
        <div class="lh-1">
            <h2 class="h2 mb-0 lh-1">Subnets</h2>
        </div>
        <div class="ms-auto">
            <a href="{{ url("/subnets/create") }}"><button type="button" class="btn btn-sm btn-success">Create Subnet</button></a>

            {{--            @can('sites.create')--}}
            {{--                <a href="{{ url("/site/create") }}" ><button type="button" class="btn btn-sm btn-success">Create Site</button></a>--}}
            {{--            @endcan--}}
        </div>

    </div>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Subnets</li>
    </ol>
              @include('subnet.list')



@endsection
