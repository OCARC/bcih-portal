@extends('common.master')
@section('title')
    Edit Equipment: {{ $equipment->hostname }}
@endsection
@section('content')

    <h2><img src="{{ $equipment->icon() }}" style="height: 2em; margin-top: -1em"> Equipment: {{ $equipment->hostname }}
    </h2>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="{{url("/subnets")}}">Equipment</a></li>
        <li class="breadcrumb-item active">{{ $equipment->hostname }}</li>
    </ol>
    <div>
@include('equipment.form')
        </div>
        @endsection