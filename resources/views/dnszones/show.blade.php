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
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#ipInfo" aria-controls="ipInfo" role="tab" data-toggle="tab">Zone Info</a></li>
            <li role="presentation"><a href="#records" aria-controls="records" role="tab" data-toggle="tab">Records</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="ipInfo">

                <Table class="table table-responsive table-condensed table-striped table-bordered">
                    <tr>
                        <th>Name</th>
                        <td>{{$record->name}}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{$record->description}}</td>
                    </tr>
                    <tr>
                        <th>Server</th>
                        <td>{{$record->server}}</td>
                    </tr>
                    <tr>
                        <th>DNS Key</th>
                        <td>{{$record->dns_key}}</td>
                    </tr>
                    <tr>
                        <th>Domain</th>
                        <td>{{$record->domain}}</td>
                    </tr>
                    <tr>
                        <th>Created</th>
                        <td>{{$record->created_at}}</td>
                    </tr>
                    <tr>
                        <th>Updated</th>
                        <td>{{$record->updated_at}}</td>
                    </tr>
                </table>

                <form method="POST" action="{{ url("/dns-zones/" . $record->id . "") }}" accept-charset="UTF-8">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE"/>
                    <a href="{{ url("/dns-zones/" . $record->id . "/edit") }}">
                        <button type="button" class="btn btn-sm btn-success">Edit DNS Zone</button>
                    </a>

                    <button type="button" class="btn btn-sm btn-danger">Delete DNS Zone</button>
                </form>
            </div>
            <div role="tabpanel" class="tab-pane" id="records">

                @include('dnsrecords.list', ['records' => $record->dnsrecords ])

            </div>

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

