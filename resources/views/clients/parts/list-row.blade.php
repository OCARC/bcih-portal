@php ( $librenms_mapping = $row->librenms_mapping )

<tr class="{{ $row->last_heard_class() }}">
    {{-- START LibreNMS Status --}}
    <td class="status-col {{ $row->libre_status_class() }}">

    </td>
    {{-- END LibreNMS Status --}}

    {{-- START Client Icon/Info --}}
    {{-- TODO: Merge these 2 coloums --}}
    <td style="height: 50px;">
        <a href="{{url("clients/" . $row->id )}}"
           style="height: 50px; display:block; width: 50px; margin: -5px; background-repeat: no-repeat; background-position:center; background-size: auto 38px; background-image: url('{{$row->icon()}}')"></a>
    </td>
    <td>
        <span><a href="{{url("clients/" . $row->id )}}">{{$row->friendly_name() }}</a></span><br>
        <span>{{ str_replace("RouterOS","",$row->snmp_sysDesc) }}</span>

    </td>
    {{-- END Client Icon/Info --}}

    <td>
        <span style="font-family: 'Courier New'">{{ null !== ($row->dhcp_lease()) ? $row->dhcp_lease()->ip : $row->management_ip }}</span><br>

        <span style="font-family: 'Courier New'">{{ implode(":",str_split(strtoupper($row->mac_address),2)) }}@if ($row->type == 'link')</span>
        <span class="label label-info">{{ $row->type }}</span>@endif
    </td>
    <td><a href="{{url("site/" . $row->site_id )}}">{{ $row->site->name }}</a><br>
        @if ( $row->equipment )
            <a href="{{url("equipment/" . $row->equipment_id )}}">{{ $row->equipment->hostname }}</a> @if($row->distanceToAP())
                ({{$row->distanceToAP()}} km)@endif</td>
    @endif
    <td style="background-color: {{ $row->strengthColor(0.5) }}; vertical-align: middle; font-size: 16px;"
        class="text-right">{{ $row->snmp_strength }} dBm
    </td>

    <td style="vertical-align: middle; font-size: 16px;" class="text-right">{{ $row->snmp_signal_to_noise }} dB</td>

    <td class="text-right">
        <strong>TX<br>
            RX</strong>
    </td>
    <td class="text-right">
        {{ $row->snmp_tx_strength_ch0 or "-" }} dBm<br>
        {{ $row->snmp_rx_strength_ch0 or "-" }} dBm
    </td>

    <td class="text-right">
        {{ $row->snmp_tx_strength_ch1 or "-" }} dBm<br>
        {{ $row->snmp_rx_strength_ch1 or "-" }} dBm

    <td class="text-right">
        {{ number_format($row->snmp_tx_rate/1000000,1) }} Mbps<br>
        {{ number_format($row->snmp_rx_rate/1000000,1) }} Mbps
    </td>
    <td>
        {{ $row->updated_at }}
        <br>{{ $row->updated_at->diffForHumans(\Carbon\Carbon::now(),true) }} ago
    </td>


</tr>
