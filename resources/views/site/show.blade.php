@extends('common.master')

@section('content')


    <h2>Site: {{ $site->name }} {{$site->sitecode}}</h2>
    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#siteInfo" aria-controls="siteInfo" role="tab" data-toggle="tab">Site Info</a></li>
            <li role="presentation"><a href="#equipment" aria-controls="equipment" role="tab" data-toggle="tab">Equipment</a></li>
            <li role="presentation"><a href="#clients" aria-controls="clients" role="tab" data-toggle="tab">Clients</a></li>
            <li role="presentation"><a href="#graphs" aria-controls="graphs" role="tab" data-toggle="tab">Graphs</a></li>
            <li role="presentation"><a href="#tools" aria-controls="tools" role="tab" data-toggle="tab">Tools</a></li>
            <li role="presentation"><a href="#ips" aria-controls="ips" role="tab" data-toggle="tab">IPs</a></li>
            <li role="presentation"><a href="#rf" aria-controls="rf" role="tab" data-toggle="tab">RF</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">

            @include('site.tabInfo')
            <div role="tabpanel" class="tab-pane" id="equipment">

                @include('equipment.list', ['equipment' => $site->equipment ])

            </div>

            <div role="tabpanel" class="tab-pane" id="clients">

                @include('clients.list', ['clients' => $site->clients ])

            </div>

            <div role="tabpanel" class="tab-pane" id="graphs">
                {{--@foreach( $site->equipment as $equipment )--}}
                    {{--<h3>{{$equipment->hostname}} Graphs</h3>--}}
                    {{--@foreach( $equipment->graphs as $graph )--}}
                        {{--<img src="{{$graph->url(2)}}" style="width: 48%; min-width: 300px;">--}}
                    {{--@endforeach--}}
                {{--@endforeach--}}
            </div>

            <div role="tabpanel" class="tab-pane" id="tools">...</div>
            @include('site.tabIP')
            {{--@include('site.tabRF')--}}

        </div>

    </div>

@endsection

