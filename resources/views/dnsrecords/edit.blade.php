@extends('common.master')
@section('title')
    Edit DNS Record: {{ $record->name }}
@endsection
@section('content')

    <h2>    Edit DNS Record: {{ $record->name }}
    </h2>

    <div>
@include('dnsrecords.form')
        </div>
        @endsection