@extends('common.master')
@section('title')
    Equipment
@endsection
@section('content')


    <h2>Equipment</h2>

              @include('equipment.list')

    <a href="{{ url("/equipment/create") }}"><button type="button" class="btn btn-sm btn-success">Create Equipment</button></a>
    <a href="{{ url("/equipment/refresh") }}"><button type="button" class="btn btn-sm btn-info">Poll Equipment</button></a>


@endsection
