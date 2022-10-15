@extends('common.master')
@section('title')
    Edit Site: {{ $site->name }}
@endsection
@section('content')

    <div class="d-flex align-items-center p-3 my-3 rounded shadow-sm bg-light">
        <img class="me-3" src="{{ $site->icon() }}" alt="" width="64" height="64" style="margin-top: -1.5rem;    margin-left: -1rem;    margin-bottom: -1.5rem;">

        <div class="lh-1">
            <h2 class="h2 mb-0 lh-1"><span class="text-muted">Edit Site:</span> {{ $site->name }}</h2>
        </div>
        <div class="ms-auto">
{{--            @can('sites.create')--}}
{{--                <a href="{{ url("/site/create") }}" ><button type="button" class="btn btn-sm btn-success">Create Site</button></a>--}}
{{--            @endcan--}}
        </div>

    </div>
    <div>
@include('site.form')
        </div>
        @endsection