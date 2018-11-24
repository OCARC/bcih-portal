@php ( $librenms_mapping = $row->librenms_mapping )

<tr class="{{ $row->last_heard_class() }}">
    <td class="status-col {{ $row->libre_status_class() }}">

    </td>

    <td style="height: 50px; background-repeat: no-repeat; background-position:center; background-size: auto 38px; background-image: url('{{ $row->icon() }}')">

    </td>
    <td>
        <a href="{{url("equipment/" . $row->id ) }}">{{ ($librenms_mapping) ? $row->libre_device['hostname'] : $row->hostname }}</a><br>
        {{ ($librenms_mapping) ? $row->libre_device['sysName'] : $row->snmp_sysName }}

    </td>




    <td>
        {{ ($librenms_mapping) ? $row->libre_device['hardware'] : 'No LNMS Mapping' }}<br>
        S/N: {{ $row->get_serial_number() }}

    </td>

    <td class="text-center text-capitalize">
        @if ($row->site())
            <a href="{{url("site/" . $row->site->id ) }}">{{ $row->site->sitecode }}</a>
        @else
            -
        @endif
    </td>

    <td  sorttable_customkey="{{ ($librenms_mapping) ? $row->libre_device['version'] : '-' }}">
        {{ ($librenms_mapping) ? $row->libre_device['version'] : '-' }}

    </td>
    <td class="text-right">
        {{ ($librenms_mapping) ? number_format( $row->libre_sensor('voltage')->sensor_current,1) : 'n/a' }}
    </td>
    <td class="text-right">
        {{ ($librenms_mapping) ?  $row->libre_sensor('temperature')->sensor_current : 'n/a' }}
    </td>
    <td class="text-right">
        {{ ($librenms_mapping) ? $row->libre_wireless('frequency')->sensor_current : 'n/a' }}
    </td>
    <td class="text-right">
        {{ ($librenms_mapping) ? $row->libre_wireless('ccq')->sensor_current : 'n/a' }}
    </td>
    <td class="text-right">
        {{ ($librenms_mapping) ? $row->libre_wireless('clients')->sensor_current : 'n/a' }}
    </td>
    <td class="text-left">
        {{ ($librenms_mapping) ? str_replace("SSID: ", "", $row->libre_wireless('clients')->sensor_descr) : 'n/a' }}
    </td>
</tr>
