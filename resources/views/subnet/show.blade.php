@extends('common.master')

@section('content')


    <h2>Subnet: {{ $subnet->name }} ({{$subnet->ip}}/{{$subnet->CIDR()}})</h2>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="{{url("/subnets")}}">Subnets</a></li>
        <li class="breadcrumb-item active">{{ $subnet->name }}</li>
    </ol>
    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#subnetInfo" aria-controls="subnetInfo" role="tab" data-toggle="tab">Subnet Info</a></li>
            <li role="presentation"><a href="#addresses" aria-controls="addresses" role="tab" data-toggle="tab">IP Addresses</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="subnetInfo">

                <Table class="table table-responsive table-condensed table-striped table-bordered">
                    <tr>
                        <th>Name</th>
                        <td>{{$subnet->name}}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{$subnet->description}}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{$subnet->ip}}</td>
                    </tr>
                    <tr>
                        <th>Netmask</th>
                        <td>{{$subnet->netmask}}</td>
                    </tr>
                    <tr>
                        <th>Routed To</th>
                        <td>{{$subnet->netmask}}</td>
                    </tr>
                </table>

                <form method="POST" action="{{ url("/subnets/" . $subnet->id . "") }}" accept-charset="UTF-8">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE"/>
                    <a href="{{ url("/subnets/" . $subnet->id . "/edit") }}">
                        <button type="button" class="btn btn-sm btn-success">Edit Subnet</button>
                    </a>

                    <button type="button" class="btn btn-sm btn-danger">Delete Subnet</button>
                </form>
            </div>
            <div role="tabpanel" class="tab-pane" id="addresses">
                @include('ip.list', ['ips' => $subnet->ips() ])

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

