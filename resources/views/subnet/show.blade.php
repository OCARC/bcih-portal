@extends('common.master')

@section('content')

    <div class="d-flex align-items-center p-3 my-3 rounded shadow-sm bg-light">
        <div class="lh-1">
            <h2 class="h2 mb-0 lh-1"><span class="text-muted">Subnet:</span> {{ $subnet->name }} ({{$subnet->ip}}/{{$subnet->CIDR()}})</h2>
        </div>
        <div class="ms-auto">

            {{--            @can('sites.create')--}}
            {{--                <a href="{{ url("/site/create") }}" ><button type="button" class="btn btn-sm btn-success">Create Site</button></a>--}}
            {{--            @endcan--}}
        </div>

    </div>

    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="{{url("/subnets")}}">Subnets</a></li>
        <li class="breadcrumb-item active">{{ $subnet->name }}</li>
    </ol>
    <div>

        <!-- Nav tabs -->
        @include('common.tabs')


        <!-- Tab panes -->
        @include('common.tabpanels')

    </div>

@endsection

