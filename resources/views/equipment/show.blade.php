@extends('common.master')
@section('title')
    Equipment
@endsection
@section('content')

    <div class="d-flex align-items-center p-3 my-3 rounded shadow-sm bg-light">
        <img class="me-3" src="{{ $equipment->icon() }}" alt="" width="64" height="64" style="margin-top: -1.5rem;    margin-left: -1rem;    margin-bottom: -1.5rem;">
        <div class="lh-1">
            <h2 class="h2 mb-0 lh-1"><span class="text-muted">Equipment:</span> {{ $equipment->hostname }}
        </div>
    </div>


    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="{{url("/equipment")}}">Equipment</a></li>
        <li class="breadcrumb-item active">{{ $equipment->hostname }}</li>
    </ol>
    <div>

        <!-- Nav tabs -->
        @include('common.tabs')

        <!-- Tab panes -->
        @include('common.tabpanels')


    </div>

@endsection
