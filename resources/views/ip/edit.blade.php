@extends('common.master')
@section('title')
    Edit IP: {{ $ip->ip }}
@endsection
@section('content')

    <h2>Edit IP: {{ $ip->ip }}</h2>

    <div>
@include('ip.form')
        </div>
        @endsection