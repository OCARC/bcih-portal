@extends('common.master')

@section('content')


    <div class="d-flex align-items-center p-3 my-3 rounded shadow-sm bg-light">
{{--        <img class="me-3" src="{{ $site->icon() }}" alt="" width="64" height="64" style="margin-top: -1.5rem;    margin-left: -1rem;    margin-bottom: -1.5rem;">--}}
        <div class="lh-1">
            <h2 class="h2 mb-0 lh-1"><span class="text-muted">Role:</span> {{ $role->friendly_name ?? $role->name}} ({{$role->category ?? 'Uncategorized'}})
        </div>
    </div>
    <div>

        <!-- Nav tabs -->
        @include('common.tabs')

{{--        <ul class="nav nav-tabs" role="tablist">--}}
{{--            <li role="presentation" class="active"><a href="#roleInfo" aria-controls="roleInfo" role="tab" data-toggle="tab">Role Info</a></li>--}}
{{--            <li role="presentation"><a href="#ips" aria-controls="ips" role="tab" data-toggle="tab">IPs</a></li>--}}
{{--            <li role="presentation"><a href="#users" aria-controls="users" role="tab" data-toggle="tab">Users</a></li>--}}
{{--            <li role="presentation"><a href="#equipment" aria-controls="equipment" role="tab" data-toggle="tab">Equipment</a></li>--}}
{{--            <li role="presentation"><a href="#clients" aria-controls="clients" role="tab" data-toggle="tab">Clients</a></li>--}}
{{--            <li role="presentation"><a href="#sites" aria-controls="sites" role="tab" data-toggle="tab">Sites</a></li>--}}
{{--            <li role="presentation"><a href="#perms" aria-controls="perms" role="tab" data-toggle="tab">Permissions</a></li>--}}

{{--        </ul>--}}

        {{--<!-- Tab panes -->--}}
        @include('common.tabpanels')


    </div>

@endsection

