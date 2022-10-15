@extends('common.master')
@section('title')
    Create DNS Record
@endsection
@section('content')

    <h2>Create DNS Record</h2>

    <div>
        @include('dnsrecords.form')
    </div>
@endsection