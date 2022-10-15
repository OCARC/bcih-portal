@extends('common.master')

@section('content')

    <div class="d-flex align-items-center p-3 my-3 rounded shadow-sm bg-light">
        <img class="me-3" src="{{ $site->icon() }}" alt="" width="64" height="64" style="margin-top: -1.5rem;    margin-left: -1rem;    margin-bottom: -1.5rem;">
        <div class="lh-1">
            <h2 class="h2 mb-0 lh-1"><span class="text-muted">Site:</span> {{ $site->name }}
        </div>
    </div>

    <div>

        <!-- Nav tabs -->
        @include('common.tabs')


        <!-- Tab panes -->
        @include('common.tabpanels')


    </div>

@endsection

