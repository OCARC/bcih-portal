@extends('common.master')
@section('title')
    Sites
@endsection
@section('content')

    <div class="d-flex align-items-center p-3 my-3 rounded shadow-sm bg-light">
        <div class="lh-1">
            <h2 class="h2 mb-0 lh-1">Sites</h2>
        </div>
        <div class="ms-auto">
            @can('sites.create')
                <a href="{{ url("/site/create") }}" ><button type="button" class="btn btn-sm btn-success">Create Site</button></a>
            @endcan
        </div>

    </div>


    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Sites</li>
        </ol>
    </nav>
    @include('site.list')


    @can('sites.create')
    @else
        <p>If you need to create a new site please contact a member over the BC Interior HamWAN team.</p>
    @endcan

@endsection
