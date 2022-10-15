@extends('common.master')
@section('title')
    Edit Subnet: {{ $subnet->name }}
@endsection
@section('content')

    <h2>Edit Subnet: {{ $subnet->name }}</h2>

    <div>
@include('subnet.form')
        </div>
        @endsection