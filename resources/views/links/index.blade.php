@extends('common.master')
@section('title')
    PtP Links
@endsection
@section('content')

    <div class="d-flex align-items-center p-3 my-3 rounded shadow-sm bg-light">
        <div class="lh-1">
            <h2 class="h2 mb-0 lh-1">Point to Point Links</h2>
        </div>
        <div class="ms-auto">
            <a href="{{ url("/links/create") }}"><button type="button" class="btn btn-sm btn-success">Create Link Record</button></a>

            {{--            @can('sites.create')--}}
            {{--                <a href="{{ url("/site/create") }}" ><button type="button" class="btn btn-sm btn-success">Create Site</button></a>--}}
            {{--            @endcan--}}
        </div>

    </div>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">PtP Links</li>
    </ol>

    @include('links.list')
    <div class="d-flex align-items-center p-3 my-3 rounded shadow-sm bg-light">
        <div class="lh-1">
            <h2 class="h2 mb-0 lh-1">Link Radios</h2>
        </div>
    </div>
    @include('clients.list')



@endsection
