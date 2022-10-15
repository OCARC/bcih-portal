@extends('common.master')
@section('title')
    Edit DNZ Zone: {{ $record->names }}
@endsection
@section('content')

    <h2>Edit DNZ Zone: {{ $record->names }}</h2>

    <div>
@include('dnszones.form')
        </div>
        @endsection