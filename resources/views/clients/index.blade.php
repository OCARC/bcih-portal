@extends('common.master')
@section('title')
    Clients
@endsection
@section('content')


    <div class="d-flex align-items-center p-3 my-3 rounded shadow-sm bg-light">
        <div class="lh-1">
            <h2 class="h2 mb-0 lh-1">Clients</h2>
        </div>
        <div class="ms-auto">

            @canany(['clients.poll','clients.poll_own','clients.poll_all'])
                    <a href="{{ url("/clients/refresh") }}"><button type="button" class="btn btn-sm btn-info">Poll</button></a>
            @endcanany
        </div>

    </div>

            @include('clients.list')




@endsection
