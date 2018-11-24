@extends('common.master')
@section('title')
    Subnet
@endsection
@section('content')


    <h2>Subnets</h2>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Subnets</li>
    </ol>
              @include('subnet.list')

    <a href="{{ url("/subnets/create") }}"><button type="button" class="btn btn-sm btn-success">Create Subnet</button></a>


@endsection
