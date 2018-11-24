@extends('common.master')
@section('title')
    Users
@endsection
@section('content')

                    <h2>@yield('title')</h2>

                    @include('users.list')


@endsection
