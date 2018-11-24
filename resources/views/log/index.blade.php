@extends('common.master')
@section('title')
    Log Events
@endsection
@section('content')


    <h2>Log Events</h2>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Log Events</li>
    </ol>
    @include('log.list')



@endsection
