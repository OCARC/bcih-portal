<div class="table-responsive">

<table class="table sortable table-responsive table-condensed table-striped table-bordered table-hover">

    <thead>
    <tr>
        <th colspan="2">IP</th>
        <th>Count</th>
        <th>Name</th>
        <th>Status</th>
        <th>Group</th>
        <th>Gateway</th>
        <th>DHCP</th>
        <th>Site</th>
        <th>Assigned User</th>

        {{--<th>Uptime</th>--}}
        {{--<th>Last Queried</th>--}}
    </tr>
    </thead>
    <tbody>

    @foreach ($subnets as $row)
<tr>
<td class="text-right" style="font-family: courier"><a href="{{url("subnets/" . $row->id )}}">{{ $row->ip  }}</a></td><td style="font-family: courier">/{{ $row->CIDR()  }}</td>
            <td class="text-right">{{ $row->count()  }}</td>

            <td>

    <strong>{{ $row->name  }}</strong><br>
{{ $row->description  }}&nbsp;</td>
            @if ($row->status == "Subdivided")
                <td style="vertical-align:middle;background-color: #e1e1e1" sorttable_customkey="{{ $row->status }}">{{ $row->status }}</td>
            @elseif( $row->status == "Planning")
                <td style="vertical-align:middle;background-color: #fff6a6" sorttable_customkey="{{ $row->status }}">{{ $row->status }}</td>
            @elseif( $row->status == "In Use")
                <td style="vertical-align:middle;background-color: #aaffaa" sorttable_customkey="{{ $row->status }}">{{ $row->status }}</td>
            @elseif( $row->status == "Do Not Use")
                <td style="vertical-align:middle;background-color: #ff6666" sorttable_customkey="{{ $row->status }}">{{ $row->status }}</td>
            @elseif( $row->status == "Routing Problem")
                <td style="vertical-align:middle;background-color: #ffd355" sorttable_customkey="{{ $row->status }}">{{ $row->status }}</td>
            @elseif( $row->status == "Placeholder")
                <td style="vertical-align:middle;background-color: #979797" sorttable_customkey="{{ $row->status }}">{{ $row->status }}</td>
            @else
                <td style="vertical-align:middle;" sorttable_customkey="{{ $row->status }}">{{ $row->status }}</td>
            @endif
            <td style="text-align: center; vertical-align:middle">
                @if ($row->category == "OSPF Routing")
                    <span class="label label-success">{{ $row->category }}</span>
            @elseif( $row->category == "Clients")
                    <span class="label label-warning">{{ $row->category }}</span>
                @elseif( $row->category == "Client Subnet")
                    <span class="label label-primary">{{ $row->category }}</span>
            @else
                    <span class="label label-default">{{ $row->category }}</span>
                @endif
</td>
            <td style=" vertical-align:middle">
{{ $row->gateway  }}</td>
            <td style="text-align: center; vertical-align:middle">
        @if ( $row->dhcp )
                    <span class="label label-success">Yes</span><br>
        @else
                    <span class="label label-danger">No</span>
                @endif
            </td>
            <td class="text-center" style="vertical-align:middle;" >
                @if ($row->site_id )
                <a href="{{url("sites/" . $row->site_id )}}">{{ $row->site->sitecode }}</a>
                @else
                -
                    @endif
            </td>
            <td class="text-center" style="vertical-align:middle;" ><a href="{{url("users/" . $row->user_id )}}">{{ $row->user->callsign }}</a></td>

        </tr>
    @endforeach
    </tbody>

</table>
    </div>