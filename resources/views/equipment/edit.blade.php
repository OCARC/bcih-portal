@extends('common.master')
@section('title')
    Edit Equipment: {{ $equipment->hostname }}
@endsection
@section('content')

    <h2>Edit Equipment: {{ $equipment->hostname }}</h2>

    <div>
@include('equipment.form')
        </div>
        @endsection