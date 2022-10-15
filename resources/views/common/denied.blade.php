@extends('common.master')
@section('title')
    Access Denied
@endsection
@section('content')

    <h2>Access Denied</h2>

    <p>{{ $message ?? 'You do not have enough permissions to access this resource.' }}</p>

    <p><a href="javascript:window.history.back();">&laquo; Go Back</a></p>
@endsection
