@extends('common.master')
@section('title')
    User: {{ $user->name }} {{$user->callsign}}
@endsection
@section('content')


    <h2>User: {{ $user->name }} {{$user->callsign}}</h2>
    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#siteInfo" aria-controls="siteInfo" role="tab" data-toggle="tab">User Info</a></li>
            <li role="presentation"><a href="#authentication" aria-controls="perms" role="tab" data-toggle="tab">Authentication</a></li>
            <li role="presentation"><a href="#perms" aria-controls="perms" role="tab" data-toggle="tab">Permissions</a></li>

            <li role="presentation"><a href="#ips" aria-controls="ips" role="tab" data-toggle="tab">IPs</a></li>
            <li role="presentation"><a href="#equipment" aria-controls="equipment" role="tab" data-toggle="tab">Equipment</a></li>
            <li role="presentation"><a href="#clients" aria-controls="clients" role="tab" data-toggle="tab">Clients</a></li>
            <li role="presentation"><a href="#sites" aria-controls="sites" role="tab" data-toggle="tab">Sites</a></li>

        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="siteInfo">

                <Table class="table table-responsive table-condensed table-striped table-bordered">
                    <tr>
                        <th>Callsign</th>
                        <td>{{$user->callsign}}</td>
                    </tr>
                    <tr>
                        <th>Realm</th>
                        <td>
                            @if( $user->realm == 'local')
                                Local
                            @elseif( $user->realm == 'ldap')
                                LDAP
                            @else
                                {{ $user->realm }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{$user->name}}</td>
                    </tr>


                    <tr>
                        <th>Comment</th>
                        <td>{{$user->comment}}</td>
                    </tr>
                </table>

                <form method="POST" action="{{ url("/site/" . $user->id . "") }}" accept-charset="UTF-8">
                    @if( $user->realm == 'ldap')
                        <div class="alert alert-info">
                            Some attributes of this user account cannot be managed through this portal and must be managed through LDAP.
                        </div>
                    @else
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE"/>
                    <a href="{{ url("/user/" . $user->id . "/edit") }}"><button type="button" class="btn btn-sm btn-success">Edit User</button></a>

                    <button type="submit" class="btn btn-sm btn-danger" disabled="true">Delete User</button>
                    @endif
                </form>
            </div>
            <div role="tabpanel" class="tab-pane " id="ips">
                @include('ip.list', ['ips' => $user->ips ])

                </div>
            <div role="tabpanel" class="tab-pane" id="perms">
                @include('users.permform')

            </div>
            <div role="tabpanel" class="tab-pane" id="equipment">

                @include('equipment.list', ['equipment' => $user->equipment ])

            </div>

            <div role="tabpanel" class="tab-pane" id="clients">

                @include('clients.list', ['clients' => $user->clients ])

            </div>
            <div role="tabpanel" class="tab-pane" id="sites">

                @include('site.list', ['sites' => $user->sites ])

            </div>

            {{--<div role="tabpanel" class="tab-pane" id="graphs">--}}
                {{--@foreach( $user->equipment as $equipment )--}}
                    {{--<h3>{{$equipment->hostname}} Graphs</h3>--}}
                    {{--@foreach( $equipment->graphs as $graph )--}}
                        {{--<img src="{{$graph->url(2)}}" style="width: 48%; min-width: 300px;">--}}
                    {{--@endforeach--}}
                {{--@endforeach--}}
            {{--</div>--}}

            <div role="tabpanel" class="tab-pane" id="tools">...</div>

            @include('users.tabAuthentication')

        </div>

    </div>

@endsection

