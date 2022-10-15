@extends('common.master')
@section('title')
    User: {{ $user->name }} {{$user->callsign}}
@endsection
@section('content')


    <h2>User: {{ $user->name }} {{$user->callsign}}</h2>
    <div>

        <!-- Nav tabs -->
        @include('common.tabs')


        <!-- Tab panes -->
        @include('common.tabpanels')


    </div>

@endsection

