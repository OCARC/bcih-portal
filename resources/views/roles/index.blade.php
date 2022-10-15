@extends('common.master')
@section('title')
    Roles
@endsection
@section('content')

    <div class="d-flex align-items-center p-3 my-3 rounded shadow-sm bg-light">
        <div class="lh-1">
            <h2 class="h2 mb-0 lh-1">Roles</h2>
        </div>
        <div class="ms-auto">
{{--            @can('sites.create')--}}
{{--                <a href="{{ url("/site/create") }}" ><button type="button" class="btn btn-sm btn-success">Create Site</button></a>--}}
{{--            @endcan--}}
        </div>

    </div>
                    @include('roles.list')


@endsection
