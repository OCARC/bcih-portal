@extends('common.master')

@section('content')


    <h2>IP: {{$ip->ip}} ({{ $ip->name ?? $ip->description}})</h2>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="{{url("/ips")}}">IP Addresses</a></li>
        <li class="breadcrumb-item active">{{ $ip->ip }}</li>
    </ol>
    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#ipInfo" aria-controls="ipInfo" role="tab" data-toggle="tab">IP Info</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="ipInfo">


                <form method="POST" action="{{ url("/ips/" . $ip->id . "") }}" accept-charset="UTF-8">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE"/>
                    <div class="card card-default">
                        <div class="card-header">
                            IP Address Information
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-xs-4">
                                    <label for="name">Name</label>
                                    <p class="form-control-static">{{ $ip->name }}</p>
                                </div>
                                <div class="form-group col-xs-4">
                                    <label for="name">IP</label>
                                    <p class="form-control-static">{{ $ip->ip }}</p>

                                </div>
                                <div class="form-group col-xs-4">
                                    <label for="name">MAC Address</label>
                                    <p class="form-control-static">{{ implode(":",str_split(strtoupper($ip->mac_address),2)) }}</p>
                                </div>
                            </div>
                            <div class="row">

                                <div class="form-group col-xs-4">
                                    <label for="name">Equipment</label>
                                    @if ( $ip->equipment )
                                    <a href="{{url("equipment/" . $ip->equipment_id )}}">{{ $ip->equipment->name }}</a>
                                        @else
                                        <p class="form-control-static">not assigned</p>
                                    @endif
                                </div>
                                <div class="form-group col-xs-4">
                                    <label for="name">Subnet Mask</label>
                                    <p class="form-control-static">{{$ip->netmask ?? 'not set'}}</p>
                                </div>
                                <div class="form-group col-xs-4">
                                    <label for="name">Gateway</label>
                                    <p class="form-control-static">{{$ip->gateway ?? 'not set'}}</p>
                                </div>
                                </div>
                            <div class="row">

                                <div class="form-group col-xs-4">
                                    <label for="name">Status</label>
                                    <p  class="form-control-static">
                                        @if ($ip->status == "Subdivided")
                                            <span style="vertical-align:middle;background-color: #e1e1e1">{{ $ip->status }}</span>
                                        @elseif( $ip->status == "Planning")
                                            <span style="vertical-align:middle;background-color: #fff6a6">{{ $ip->status }}</span>
                                        @elseif( $ip->status == "In Use")
                                            <span style="vertical-align:middle;background-color: #aaffaa">{{ $ip->status }}</span>
                                        @elseif( $ip->status == "Do Not Use")
                                            <span style="vertical-align:middle;background-color: #ff6666">{{ $ip->status }}</span>
                                        @elseif( $ip->status == "Routing Problem")
                                            <span style="vertical-align:middle;background-color: #ffd355">{{ $ip->status }}</span>
                                        @elseif( $ip->status == "NOT ASSIGNED")
                                            <span style="vertical-align:middle;background-color: #979797">{{ $ip->status }}</span>
                                        @else
                                            <span style="vertical-align:middle;">{{ $ip->status }}</span>
                                        @endif

                                    </p>
                                </div>


                            <div class="form-group col-xs-4">

                                <label for="name">Site</label>
                                <p class="form-control-static">
                                    <a href="{{url("/site/" . $ip->site_id ) }}">{{$ip->site['name'] }}
                                        ({{$ip->site['sitecode'] }})</a>
                                </p>
                            </div>
                            <div class="form-group col-xs-4">

                                <label for="name">Owner</label>
                                <p class="form-control-static">
                                    <a href="{{url("users/" . $ip->user_id )}}">{{ $ip->user->callsign }}</a>
                                </p>
                            </div>
                            </div>

                            <div class="row">
                            <div class="form-group col-xs-6">

                                <label for="name">Description (public)</label>
                                <p class="form-control-static">
                                    {{ $ip->description ?? "nil" }}
                                </p>
                            </div>
                            <div class="form-group col-xs-6">

                                <label for="name">Comments</label>
                                <p class="form-control-static">
                                    {{ $ip->comment ?? "nil" }}
                                </p>
                            </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">

                            <a href="{{ url("/ips/" . $ip->id . "/edit") }}"><button type="button" class="btn btn-xs btn-success">Edit IP</button></a>
                            <a href="{{ url("/ips/" . $ip->id . "/delete") }}"><button type="submit" class="btn btn-xs btn-danger">Delete IP</button></a>
                        </div>
                    </div>

                    {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
                    {{--<input type="hidden" name="_method" value="DELETE"/>--}}
                    {{--<a href="{{ url("/equipment/" . $equipment->id . "/edit") }}">--}}
                    {{--<button type="button" class="btn btn-sm btn-success">Edit Equipment</button>--}}
                    {{--</a>--}}

                    {{--<button type="submit" class="btn btn-sm btn-danger">Delete Equipment</button>--}}
                </form>




{{--                <form method="POST" action="{{ url("/ips/" . $ip->id . "") }}" accept-charset="UTF-8">--}}
{{--                    <input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
{{--                    <input type="hidden" name="_method" value="DELETE"/>--}}
{{--                    <a href="{{ url("/ips/" . $ip->id . "/edit") }}">--}}
{{--                        <button type="button" class="btn btn-sm btn-success">Edit ip</button>--}}
{{--                    </a>--}}

{{--                    <button type="button" class="btn btn-sm btn-danger">Delete ip</button>--}}
{{--                </form>--}}
            </div>

        </div>

    </div>

@endsection

