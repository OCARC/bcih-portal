@extends('common.master')
@section('title')
    Create DNS Zone
@endsection
@section('content')

    <h2>Create DNS Zone</h2>

    <div>
        @include('dnszones.form')
    </div>
@endsection