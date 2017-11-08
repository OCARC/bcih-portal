@extends('common.master')
@section('title')
    Edit Site: {{ $site->name }}
@endsection
@section('content')

    <h2>Edit Site: {{ $site->name }}</h2>

    <div>
@include('site.form')
        </div>
        @endsection