@extends('common.master')
@section('title')
    Roles
@endsection
@section('content')

                    <h2>@yield('title')</h2>

                    @include('roles.list')


@endsection
