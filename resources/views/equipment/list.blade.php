<div class="table-responsive">

<table class="table sortable table-responsive table-condensed table-striped table-bordered table-hover">

    <thead>
    <tr>
        <th>Health</th>
        <th>Hostname</th>
        <th>Site</th>
        <th>Status</th>
        <th>Equipment / Radio</th>
        <th>Power</th>
        <th>Antenna</th>
        <th>Gain</th>
        <th>EIRP</th>
        <th>HAT</th>
        <th>Volt</th>
        <th>Temp</th>
        {{--<th>Uptime</th>--}}
        {{--<th>Last Queried</th>--}}
    </tr>
    </thead>
    <tbody>

    @foreach ($equipment as $row)
        <tr>
            <td class="text-center" style="vertical-align:middle; background-color: {{ $row->getHealthColor() }}">
                {{ $row->getHealthStatus() }}</td>
            <td>
                <img src="{{ $row->icon() }}" style="height: 38px; margin-right: 5px; float:left">
<span><a href="{{url("equipment/" . $row->id )}}">{{ $row->hostname }}</a></span> <br>
<span>{{ $row->management_ip }}</span>
               </td>
            <td class="text-center" style="vertical-align:middle;" ><a href="{{url("sites/" . $row->site_id )}}">{{ $row->site->sitecode }}</a></td>
            @if ($row->status == "Potential")
                <td style="vertical-align:middle;background-color: #e1e1e1">{{ $row->status }}</td>
            @elseif( $row->status == "Planning")
                <td style="vertical-align:middle;background-color: #fff6a6">{{ $row->status }}</td>
            @elseif( $row->status == "Installed")
                <td style="vertical-align:middle;background-color: #aaffaa">{{ $row->status }}</td>
            @elseif( $row->status == "Equip Failed")
                <td style="vertical-align:middle;background-color: #ff6666">{{ $row->status }}</td>
            @elseif( $row->status == "Problems")
                <td style="vertical-align:middle;background-color: #ffd355">{{ $row->status }}</td>
            @elseif( $row->status == "No Install")
                <td style="vertical-align:middle;background-color: #979797">{{ $row->status }}</td>
            @else
                <td style="vertical-align:middle;">{{ $row->status }}</td>
            @endif
            <td>{{ $row->radio_model }}@if ($row->snmp_serial)<br><span style="font-family: courier; font-size: 12px;">S/N {{ $row->snmp_serial }} </span>@endif</td>
            <td class="text-right">@if ($row->radio_power){{ $row->radio_power }}&nbsp;dBm @endif</td>
            <td>{{ $row->ant_model }}</td>
            <td class="text-right">@if ($row->ant_gain){{ $row->ant_gain }}&nbsp;dBi @endif</td>
            <td class="text-right">@if ($row->eirp() ){{ $row->eirp() }}&nbsp;W @endif</td>
            <td class="text-right">@if ($row->ant_height ){{ $row->ant_height }}&nbsp;m @endif</td>
            <td class="text-right">@if ($row->snmp_voltage){{ number_format($row->snmp_voltage,1) }}&nbsp;V @else - @endif</td>
            <td class="text-right">@if ($row->snmp_temperature){{ $row->snmp_temperature }}&nbsp;&deg;C @else - @endif</td>
            {{--<td class="text-right">@if ($row->snmp_uptime){{ $row->snmp_uptime }}@else <span class=" text-danger">error</span> @endif</td>--}}
            {{--<td>{{ $row->snmp_timestamp  }}</td>--}}

        </tr>
    @endforeach
    </tbody>

</table>
    </div>