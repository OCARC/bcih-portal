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
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="siteInfo">

                <Table class="table table-responsive table-condensed table-striped table-bordered">
                    <tr>
                        <th>Site Name</th>
                        <td>{{$site->name}}</td>
                    </tr>
                    <tr>
                        <th>Site Code</th>
                        <td>{{$site->sitecode}}</td>
                    </tr>
                    <tr>
                        <th>Latitude</th>
                        <td>{{$site->latitude}}</td>
                    </tr>
                    <tr>
                        <th>Longitude</th>
                        <td>{{$site->longitude}}</td>
                    </tr>
                    <tr>
                        <th>Altitude</th>
                        <td>{{$site->altitude}}m</td>
                    </tr>
                    <tr>
                        <th>Owner</th>
                        <td>{{$site->owner_id}}</td>
                    </tr>
                    <tr>
                        <th>Comment</th>
                        <td>{{$site->comment}}</td>
                    </tr>
                </table>

                <form method="POST" action="{{ url("/site/" . $site->id . "") }}" accept-charset="UTF-8">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE"/>
                    <a href="{{ url("/site/" . $site->id . "/edit") }}"><button type="button" class="btn btn-sm btn-success">Edit Site</button></a>

                    <button type="submit" class="btn btn-sm btn-danger" disabled="true">Delete Site</button>
                </form>
            </div>
            <div role="tabpanel" class="tab-pane" id="equipment">

                @include('equipment.list', ['equipment' => $site->equipment ])

            </div>

            <div role="tabpanel" class="tab-pane" id="clients">

                @include('clients.list', ['clients' => $site->clients ])

            </div>

            <div role="tabpanel" class="tab-pane" id="graphs">
                @foreach( $site->equipment as $equipment )
                    <h3>{{$equipment->hostname}} Graphs</h3>
                    @foreach( $equipment->graphs as $graph )
                        <img src="{{$graph->url(2)}}" style="width: 48%; min-width: 300px;">
                    @endforeach
                @endforeach
            </div>

            <div role="tabpanel" class="tab-pane" id="tools">...</div>
        </div>

    </div>

@endsection

