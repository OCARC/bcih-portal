@extends('common.master')
@section('title')
    Equipment
@endsection
@section('content')



    <div class="d-flex align-items-center p-3 my-3 rounded shadow-sm bg-light">
        <div class="lh-1">
            <h2 class="h2 mb-0 lh-1">Equipment</h2>
        </div>
        <div class="ms-auto">
            @can('equipment.create')
                <a href="{{ url("/equipment/create") }}" ><button type="button" class="btn btn-sm btn-success">Create</button></a>
            @endcan
            @canany(['equipment.poll','equipment.poll_own','equipment.poll_all'])
                <a href="{{ url("/equipment/refresh") }}" ><button type="button" class="btn btn-sm btn-info">Poll</button></a>
            @endcanany
        </div>

    </div>
              @include('equipment.list')


@endsection
