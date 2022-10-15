@extends('common.master')

@section('content')


    <h2>DNS Zone: {{$record->name}} ({{ $record->domain}})</h2>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="{{url("/dns-zones")}}">DNS Zones</a></li>
        <li class="breadcrumb-item active">{{ $record->name }}</li>
    </ol>
    <div>

        <!-- Nav tabs -->
        @include('common.tabs')

        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#ipInfo" aria-controls="ipInfo" role="tab" data-toggle="tab">Zone Info</a></li>
            <li role="presentation"><a href="#records" aria-controls="records" role="tab" data-toggle="tab">Records</a></li>
        </ul>

        <!-- Tab panes -->
        @include('common.tabpanels')

        <div class="tab-content">

            <div role="tabpanel" class="tab-pane" id="clients">

                {{--@include('clients.list', ['clients' => $user->clients ])--}}

            </div>
            {{--<div role="tabpanel" class="tab-pane" id="sites">--}}

                {{--@include('site.list', ['sites' => $user->sites ])--}}

            {{--</div>--}}

            {{--<div role="tabpanel" class="tab-pane" id="graphs">--}}
                {{--@foreach( $user->equipment as $equipment )--}}
                    {{--<h3>{{$equipment->hostname}} Graphs</h3>--}}
                    {{--@foreach( $equipment->graphs as $graph )--}}
                        {{--<img src="{{$graph->url(2)}}" style="width: 48%; min-width: 300px;">--}}
                    {{--@endforeach--}}
                {{--@endforeach--}}
            {{--</div>--}}

            {{--<div role="tabpanel" class="tab-pane" id="tools">...</div>--}}
        </div>

    </div>

@endsection

