@php ( $librenms_mapping = $row->librenms_mapping )

<tr style="background-color: {{$row->getHealthColor(0.15,true) }}" class="small">
{{--    <td class="status-col {{ $row->libre_status_class() }}">--}}

{{--    </td>--}}
    <td class="ping-col text-end @if( $row->hc_ping_result >= 0)success @else danger @endif">
        @if( $row->hc_ping_result >= 0){{ $row->hc_ping_result }}ms @else Offline @endif

    </td>

    <td class="text-nowrap text-truncate">
        <img class="" src="{{$row->icon()}}" style="max-height: 2.75em;max-width: 2.75em; float:left; margin-right: 0.5em">

        <a href="{{url("equipment/" . $row->id )}}">{{$row->hostname }}</a>
        @if( $row->librenms_mapping )
            <a href="//nms.if.hamwan.ca/device/{{$row->librenms_mapping}}" target="_blank"><img style="height: 1em; float:right" src="/images/librenmsicon.png"></a>
        @endif
        <div class="text-muted small text-nowrap text-truncate" style="max-width: 120px;">{{ str_replace("RouterOS","",$row->snmp_sysDesc) }}</div>
    </td>





    <td class="text-nowrap text-truncate" style="position: relative;">
        <div class="absoluteElipsis">{{ str_replace("RouterOS","",$row->snmp_sysDesc) }}</div><br>
        <strong>S/N:</strong> {{ $row->get_serial_number() }}

    </td>

    <td class="text-center text-capitalize">
        @if ($row->site())
            <a href="{{url("site/" . $row->site->id ) }}">{{ $row->site->sitecode }}</a>
        @else
            -
        @endif
    </td>

{{--    <td  sorttable_customkey="{{ ($librenms_mapping) ? $row->libre_device['version'] : '-' }}">--}}
{{--        {{ ($librenms_mapping) ? $row->libre_device['version'] : '-' }}--}}

{{--    </td>--}}
    <td  sorttable_customkey="{{ $row->snmp_version }}">
        {{ $row->snmp_version }}

    </td>


    <td class="text-end">
{{--        {{ ($librenms_mapping) ? number_format( $row->libre_sensor('voltage')->sensor_current,1) : 'n/a' }}--}}
        {{ $row->snmp_voltage }}

    </td>
    <td class="text-end">
{{--        {{ ($librenms_mapping) ?  $row->libre_sensor('temperature')->sensor_current : 'n/a' }}--}}
        {{ $row->snmp_temperature }}

    </td>
    <td class="text-end">
{{--        {{ ($librenms_mapping) ? $row->libre_wireless('frequency')->sensor_current : 'n/a' }}--}}
        {{ $row->snmp_frequency }}

    </td>

    <td class="text-end">
        {{ ($librenms_mapping) ? $row->libre_wireless('clients')->sensor_current : 'n/a' }}
    </td>
    <td class="text-left">
{{--        {{ ($librenms_mapping) ? str_replace("SSID: ", "", $row->libre_wireless('clients')->sensor_descr) : 'n/a' }}--}}
        {{ $row->snmp_ssid }}
    </td>
</tr>
