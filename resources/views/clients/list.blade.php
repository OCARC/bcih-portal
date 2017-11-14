<style>
    .bg-strength { background-color: rgba(0,0,0,1.0); color: white; }


    .bg-strength-50 { background-color: rgba( 000, 255, 000, 1.0); }
    .bg-strength-51 { background-color: rgba( 000, 255, 000, 1.0); }
    .bg-strength-52 { background-color: rgba( 001, 255, 000, 1.0); }
    .bg-strength-53 { background-color: rgba( 002, 255, 000, 1.0); }
    .bg-strength-54 { background-color: rgba( 004, 255, 000, 1.0); }
    .bg-strength-55 { background-color: rgba( 008, 255, 000, 1.0); }
    .bg-strength-56 { background-color: rgba( 014, 255, 000, 1.0); }
    .bg-strength-57 { background-color: rgba( 022, 255, 000, 1.0); }
    .bg-strength-58 { background-color: rgba( 032, 255, 000, 1.0); }
    .bg-strength-59 { background-color: rgba( 046, 255, 000, 1.0); }
    .bg-strength-60 { background-color: rgba( 063, 255, 000, 1.0); }
    .bg-strength-61 { background-color: rgba( 084, 255, 000, 1.0); }
    .bg-strength-62 { background-color: rgba( 108, 255, 000, 1.0); }
    .bg-strength-63 { background-color: rgba( 133, 255, 000, 1.0); }
    .bg-strength-64 { background-color: rgba( 159, 255, 000, 1.0); }
    .bg-strength-65 { background-color: rgba( 183, 255, 000, 1.0); }
    .bg-strength-66 { background-color: rgba( 206, 255, 000, 1.0); }
    .bg-strength-67 { background-color: rgba( 226, 255, 000, 1.0); }
    .bg-strength-68 { background-color: rgba( 242, 255, 000, 1.0); }
    .bg-strength-69 { background-color: rgba( 252, 255, 000, 1.0); }
    .bg-strength-70 { background-color: rgba( 255, 255, 000, 1.0); }
    .bg-strength-71 { background-color: rgba( 255, 252, 000, 1.0); }
    .bg-strength-72 { background-color: rgba( 255, 242, 000, 1.0); }
    .bg-strength-73 { background-color: rgba( 255, 226, 000, 1.0); }
    .bg-strength-74 { background-color: rgba( 255, 206, 000, 1.0); }
    .bg-strength-75 { background-color: rgba( 255, 183, 000, 1.0); }
    .bg-strength-76 { background-color: rgba( 255, 159, 000, 1.0); }
    .bg-strength-77 { background-color: rgba( 255, 133, 000, 1.0); }
    .bg-strength-78 { background-color: rgba( 255, 108, 000, 1.0); }
    .bg-strength-79 { background-color: rgba( 255, 084, 000, 1.0); }
    .bg-strength-80 { background-color: rgba( 255, 063, 000, 1.0); }
    .bg-strength-81 { background-color: rgba( 255, 046, 000, 1.0); }
    .bg-strength-82 { background-color: rgba( 255, 032, 000, 1.0); }
    .bg-strength-83 { background-color: rgba( 255, 014, 000, 1.0); }
    .bg-strength-84 { background-color: rgba( 255, 008, 000, 1.0); }
    .bg-strength-85 { background-color: rgba( 255, 004, 000, 1.0); }
    .bg-strength-86 { background-color: rgba( 255, 003, 000, 1.0); }
    .bg-strength-87 { background-color: rgba( 255, 002, 000, 1.0); }
    .bg-strength-88 { background-color: rgba( 255, 001, 000, 1.0); }
    .bg-strength-89 { background-color: rgba( 255, 001, 000, 1.0); }
    .bg-strength-90 { background-color: rgba( 255, 001, 000, 1.0); }





</style>
<div class="table-responsive">

<table class="table table-responsive table-condensed table-striped table-bordered">

    <thead>
    <tr>
        <th>Radio Name</th>
        <th>Address</th>
        <th>Connected To</th>
        <th>Signal</th>
        <th>SNR</th>

        <th></th>
        <th>Ch0</th>
        <th>Ch1</th>

        <th>Rates</th>

        <th>Last Seen</th>
    </tr>
    </thead>
    <tbody>

    @foreach ($clients as $row)
        <tr class="@if ($row->type == 'link')info @endif @if ( $row->updated_at->diffInMinutes(\Carbon\Carbon::now()) >= 60) warning @endif  @if ( $row->updated_at->diffInMinutes(\Carbon\Carbon::now()) >= 1440) danger @endif">
            <td><a href="{{url("clients/" . $row->id )}}">
                    @if ( $row->snmp_sysName )
                    {{ $row->snmp_sysName}}
                        @elseif( $row->dhcp_lease )
                    {{ $row->dhcp_lease->hostname }}
                        @else
                    Name Not Found
                    @endif
                </a><br>
                {{ str_replace("RouterOS","",$row->snmp_sysDesc) }}

            </td>
                <td>
                    <span style="font-family: 'Courier New'">{{ isset($row->dhcp_lease) ? $row->dhcp_lease->ip : $row->management_ip }}</span><br>

                    <span style="font-family: 'Courier New'">{{ chop(chunk_split($row->mac_address,2,":"),":") }}@if ($row->type == 'link')</span> <span class="label label-info">{{ $row->type }}</span>@endif
            </td>
            <td><a href="{{url("sites/" . $row->site_id )}}">{{ $row->site->name }}</a><br>
                @if ( $row->equipment )
<a href="{{url("infrastructure/" . $row->equipment_id )}}">{{ $row->equipment->hostname }}</a> @if($row->distanceToAP())({{$row->distanceToAP()}} km)@endif</td>
            @endif
            <td style="vertical-align: middle; font-size: 16px;" class="text-right">
                <span class="label bg-strength bg-strength{{ $row->snmp_strength }}">{{ $row->snmp_strength }} dBm</span></td>

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

            <td class="text-right" >
                {{ number_format($row->snmp_tx_rate/1000000,1) }} Mbps<br>
            {{ number_format($row->snmp_rx_rate/1000000,1) }} Mbps</td>
            <td>
                {{ $row->updated_at }}
                <br>{{ $row->updated_at->diffForHumans(\Carbon\Carbon::now(),true) }} ago
                </td>


        </tr>
    @endforeach
    </tbody>
</table>
    </div>