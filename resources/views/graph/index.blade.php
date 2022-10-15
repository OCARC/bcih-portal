@extends('common.master')
@section('title')
    Graphs
@endsection
@section('content')


    <h2>Graphs</h2>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Graphs</li>
    </ol>
    @include('graph.list')



@endsection
