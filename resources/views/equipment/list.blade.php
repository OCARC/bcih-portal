<div class="table-responsive">

<table class="table table-responsive table-condensed table-striped">

    <thead>
    <tr>
        <th>Site Name</th>
        <th>Health</th>
        <th>Management IP</th>
        <th>Voltage</th>
        <th>Temperature</th>
        <th>Uptime</th>
        <th>Last Queried</th>
    </tr>
    </thead>
    <tbody>

    @foreach ($equipment as $row)
        <tr>
            <td><a href="{{url("equipment/" . $row->id )}}">{{ $row->hostname }}</a></td>
            <td style="background-color: {{ $row->getHealthColor() }}">{{ $row->getHealthStatus() }}</td>
            <td>{{ $row->management_ip }}</td>
            <td class="text-right">@if ($row->snmp_voltage){{ number_format($row->snmp_voltage,1) }} V @else n/a @endif</td>
            <td class="text-right">@if ($row->snmp_voltage){{ $row->snmp_temperature }} &deg;C @else n/a @endif</td>
            <td class="text-right">@if ($row->snmp_voltage){{ $row->snmp_uptime }}@else <span class=" text-danger">error</span> @endif</td>
            <td>{{ $row->snmp_timestamp  }}</td>

        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="3">
            <a href="{{ url("/equipment/create") }}">create new equipment</a> |
            <a href="{{ url("/equipment/refresh") }}">poll equipment</a> |
        </td>
    </tr>
    </tfoot>
</table>
    </div>