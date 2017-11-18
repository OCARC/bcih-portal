@extends('common.master')
@section('title')
    Sites
@endsection
@section('content')

    <h2>Sites</h2>

    @include('site.list')


    <a href="{{ url("/site/create") }}"><button type="button" class="btn btn-sm btn-success">Create Site</button></a>


@endsection
