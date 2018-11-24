{{--<div class="table-responsive">--}}

{{--<table class="table sortable table-responsive table-condensed table-striped table-bordered table-hover">--}}

    {{--<thead>--}}
    {{--<tr>--}}
        {{--<th>Timestamp</th>--}}
        {{--<th>Description</th>--}}
        {{--<th>Type</th>--}}
        {{--<th>Level</th>--}}
        {{--<th>User</th>--}}
        {{--<th>Client</th>--}}
        {{--<th>Equipment</th>--}}
        {{--<th>Site</th>--}}

        {{--<th>Uptime</th>--}}
        {{--<th>Last Queried</th>--}}
    {{--</tr>--}}
    {{--</thead>--}}
    {{--<tbody>--}}

    {{--@foreach ($logEvents as $row)--}}
{{--<tr>--}}
{{--<td>{{ $row->created_at }}</td>--}}
{{--<td>{{ $row->description }}</td>--}}
{{--<td>{{ $row->event_type }}</td>--}}
{{--<td>--}}
    {{--@if($row->event_level == 0 )--}}
        {{--<span class="label label-info">Info</span>--}}
        {{--@else--}}
    {{--{{ $row->event_level }}--}}
    {{--@endif--}}
{{--</td>--}}
    {{--<td>    @if ( $row->user )--}}
            {{--<a href="{{url("user/" . $row->user->id )}}">{{$row->user->username}}</a>--}}
        {{--@else--}}
            {{-----}}

        {{--@endif--}}
    {{--</td>    <td>    @if ( $row->client )--}}
        {{--<a href="{{url("client/" . $row->client->id )}}">{{$row->client->snmp_sysName}}</a>--}}
    {{--@else--}}
        {{-----}}

    {{--@endif--}}
    {{--</td>--}}

    {{--<td>--}}

    {{--@if ( $row->equipment )--}}
        {{--<a href="{{url("equipment/" . $row->equipment->id )}}">{{$row->equipment->hostname}}</a>--}}
    {{--@else--}}
        {{-----}}

    {{--@endif--}}
{{--</td>--}}
    {{--<td>--}}

        {{--@if ( $row->site )--}}
            {{--<a href="{{url("site/" . $row->site->id )}}">{{$row->site->sitecode}}</a>--}}
        {{--@else--}}
            {{-----}}

        {{--@endif--}}
    {{--</td>--}}
        {{--</tr>--}}
    {{--@endforeach--}}
    {{--</tbody>--}}

{{--</table>--}}
    {{--</div>--}}