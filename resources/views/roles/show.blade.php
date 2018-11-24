@extends('common.master')

@section('content')


    <h2>Role: {{ $role->friendly_name or $role->name}} ({{$role->category or 'Uncategorized'}})</h2>
    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#siteInfo" aria-controls="siteInfo" role="tab" data-toggle="tab">Site Info</a></li>
            <li role="presentation"><a href="#ips" aria-controls="ips" role="tab" data-toggle="tab">IPs</a></li>
            <li role="presentation"><a href="#equipment" aria-controls="equipment" role="tab" data-toggle="tab">Equipment</a></li>
            <li role="presentation"><a href="#clients" aria-controls="clients" role="tab" data-toggle="tab">Clients</a></li>
            <li role="presentation"><a href="#sites" aria-controls="sites" role="tab" data-toggle="tab">Sites</a></li>
            <li role="presentation"><a href="#perms" aria-controls="perms" role="tab" data-toggle="tab">Permissions</a></li>

        </ul>

        {{--<!-- Tab panes -->--}}
        {{--<div class="tab-content">--}}
            {{--<div role="tabpanel" class="tab-pane active" id="siteInfo">--}}

                {{--<Table class="table table-responsive table-condensed table-striped table-bordered">--}}
                    {{--<tr>--}}
                        {{--<th>Name</th>--}}
                        {{--<td>{{$user->name}}</td>--}}
                    {{--</tr>--}}
                    {{--<tr>--}}
                        {{--<th>Callsign</th>--}}
                        {{--<td>{{$user->callsign}}</td>--}}
                    {{--</tr>--}}

                    {{--<tr>--}}
                        {{--<th>Comment</th>--}}
                        {{--<td>{{$user->comment}}</td>--}}
                    {{--</tr>--}}
                {{--</table>--}}

                {{--<form method="POST" action="{{ url("/site/" . $user->id . "") }}" accept-charset="UTF-8">--}}
                    {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
                    {{--<input type="hidden" name="_method" value="DELETE"/>--}}
                    {{--<a href="{{ url("/site/" . $user->id . "/edit") }}"><button type="button" class="btn btn-sm btn-success">Edit Site</button></a>--}}

                    {{--<button type="submit" class="btn btn-sm btn-danger" disabled="true">Delete Site</button>--}}
                {{--</form>--}}
            {{--</div>--}}
            {{--<div role="tabpanel" class="tab-pane " id="ips">--}}

            {{--</div>--}}
            <div role="tabpanel" class="tab-pane" id="perms">
                @include('roles.permform')

            </div>
            {{--<div role="tabpanel" class="tab-pane" id="equipment">--}}

                {{--@include('equipment.list', ['equipment' => $user->equipment ])--}}

            {{--</div>--}}

            {{--<div role="tabpanel" class="tab-pane" id="clients">--}}

                {{--@include('clients.list', ['clients' => $user->clients ])--}}

            {{--</div>--}}
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

            {{--@include('users.tabKeys')--}}

        {{--</div>--}}

    </div>

@endsection

