@extends('common.master')
@section('title')
    Edit Link: {{ $link->link }}
@endsection
@section('content')

    <h2>Edit Link: {{ $link->link }}</h2>

    <div>
@include('links.form')
        </div>




        @endsection

