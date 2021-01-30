@php ( $librenms_mapping = $row->librenms_mapping )

<tr style="background-color: {{$row->getHealthColor(0.15,true) }}">
{{--    <td class="status-col {{ $row->libre_status_class() }}">--}}

{{--    </td>--}}
    <td class="ping-col text-right @if( $row->hc_ping_result >= 0)success @else danger @endif">
        @if( $row->hc_ping_result >= 0){{ $row->hc_ping_result }}ms @else Offline @endif

    </td>
    <td style="height: 50px; background-repeat: no-repeat; background-position:center; background-size: contain; background-image: url('{{ $row->icon() }}'); border-left: 10px solid {{$row->getHealthColor(1)}}">

    </td>
    <td>
{{--        <a href="{{url("equipment/" . $row->id ) }}">{{ ($librenms_mapping) ? $row->libre_device['hostname'] : $row->hostname }}</a><br>--}}
{{--        {{ ($librenms_mapping) ? $row->libre_device['sysName'] : $row->snmp_sysName }}--}}
        <a href="{{url("equipment/" . $row->id ) }}">{{  $row->hostname }}</a>
        @if( $row->librenms_mapping )
        <a href="//nms.if.hamwan.ca/device/{{$row->librenms_mapping}}" target="_blank"><img style="height: 1em; float:right" src="/images/librenmsicon.png"></a>
            @endif
            <br>
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

{{--    <td  sorttable_customkey="{{ ($librenms_mapping) ? $row->libre_device['version'] : '-' }}">--}}
{{--        {{ ($librenms_mapping) ? $row->libre_device['version'] : '-' }}--}}

{{--    </td>--}}
    <td  sorttable_customkey="{{ $row->snmp_version }}">
        {{ $row->snmp_version }}

    </td>


    <td class="text-right">
{{--        {{ ($librenms_mapping) ? number_format( $row->libre_sensor('voltage')->sensor_current,1) : 'n/a' }}--}}
        {{ $row->snmp_voltage }}

    </td>
    <td class="text-right">
{{--        {{ ($librenms_mapping) ?  $row->libre_sensor('temperature')->sensor_current : 'n/a' }}--}}
        {{ $row->snmp_temperature }}

    </td>
    <td class="text-right">
{{--        {{ ($librenms_mapping) ? $row->libre_wireless('frequency')->sensor_current : 'n/a' }}--}}
        {{ $row->snmp_frequency }}

    </td>
    <td class="text-right">
        {{ ($librenms_mapping) ? $row->libre_wireless('ccq')->sensor_current : 'n/a' }}
    </td>
    <td class="text-right">
        {{ ($librenms_mapping) ? $row->libre_wireless('clients')->sensor_current : 'n/a' }}
    </td>
    <td class="text-left">
{{--        {{ ($librenms_mapping) ? str_replace("SSID: ", "", $row->libre_wireless('clients')->sensor_descr) : 'n/a' }}--}}
        {{ $row->snmp_ssid }}
    </td>
</tr>
