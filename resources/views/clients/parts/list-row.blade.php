@php ( $librenms_mapping = $row->librenms_mapping )

<tr class="{{ $row->last_heard_class() }} small" >
    <td class="ping-col text-end @if( $row->hc_ping_result >= 0)success @else danger @endif" sorttable_customkey="@if( $row->hc_ping_result >= 0){{ $row->hc_ping_result }}ms @else Offline @endif">
        @if( $row->hc_ping_result >= 0){{ $row->hc_ping_result }}ms @else Offline @endif

    </td>

    {{-- START Client Icon/Info --}}
    <td>
        <img class="" src="{{$row->icon()}}" style="height: 2.75em; float:left; margin-right: 0.5em">

        <a href="{{url("clients/" . $row->id )}}">{{$row->friendly_name() }}</a>
        <div class="text-muted small text-nowrap text-truncate">{{ str_replace("RouterOS","",$row->snmp_sysDesc) }}</div>
    </td>

    {{-- END Client Icon/Info --}}

    <td class="small">
        <span style="font-family: 'Courier New'">{{ null !== ($row->dhcp_lease()) ? $row->dhcp_lease()->ip : $row->management_ip }}</span><br>

        <span style="font-family: 'Courier New'">{{ implode(":",str_split(strtoupper($row->mac_address),2)) }}@if ($row->type == 'link')</span>
        <span class="badge bg-info">{{ $row->type }}</span>@endif
    </td>
    <td class="text-muted small text-nowrap text-truncate">
        <a href="{{url("site/" . $row->site_id )}}">{{ $row->site->name }}</a><br>
        @if ( $row->equipment )
            <a href="{{url("equipment/" . $row->equipment_id )}}">{{ $row->equipment->hostname }}</a> @if($row->distanceToAP())
                ({{$row->distanceToAP()}} km)@endif</td>
        @endif
    <td style="background-color: {{ $row->strengthColor(0.5) }}; vertical-align: middle;"
        class="text-end">{{ $row->snmp_strength }}&nbsp;dBm
    </td>

    <td style="vertical-align: middle;" class="text-end">{{ $row->snmp_signal_to_noise }}&nbsp;dB</td>

    <td class="text-end">
        <div class="text-muted">TX</div>
        <div class="text-muted">RX</div>
    </td>
    <td class="text-end">
        {{ $row->snmp_tx_strength_ch0  }}&nbsp;dBm<br>
        {{ $row->snmp_rx_strength_ch0 }}&nbsp;dBm
    </td>

    <td class="text-end">
        {{ $row->snmp_tx_strength_ch1 }}&nbsp;dBm<br>
        {{ $row->snmp_rx_strength_ch1  }}&nbsp;dBm
    </td>

    <td class="text-end">
        {{ number_format($row->snmp_tx_rate/1000000,1) }}&nbsp;Mbps<br>
        {{ number_format($row->snmp_rx_rate/1000000,1) }}&nbsp;Mbps
    </td>
    <td class="text-nowrap">
        @if ( $row->hc_last_ping_success )
            {{ $row->hc_last_ping_success->diffForHumans(\Carbon\Carbon::now(),true) }}&nbsp;ago
        @endif
    </td>


</tr>
