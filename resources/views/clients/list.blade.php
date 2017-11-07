<style>
    .bg-strength { background-color: rgba(0,0,0,1.0); color: white; }

.bg-strength-56 { background-color: rgba(034, 255, 000,1.0); color: black; }
.bg-strength-57 { background-color: rgba(034, 255, 000,1.0); color: black; }
.bg-strength-58 { background-color: rgba(056, 255, 000,1.0); color: black; }
.bg-strength-59 { background-color: rgba(056, 255, 000,1.0); color: black; }
.bg-strength-60 { background-color: rgba(090, 255, 000,1.0); color: black; }
.bg-strength-61 { background-color: rgba(090, 255, 000,1.0); color: black; }
.bg-strength-62 { background-color: rgba(124, 255, 000,1.0); color: black; }
.bg-strength-63 { background-color: rgba(124, 255, 000,1.0); color: black; }
.bg-strength-64 { background-color: rgba(158, 255, 000,1.0); color: black; }
.bg-strength-65 { background-color: rgba(158, 255, 000,1.0); color: black; }
.bg-strength-66 { background-color: rgba(192, 255, 000,1.0); color: black; }
.bg-strength-67 { background-color: rgba(192, 255, 000,1.0); color: black; }
.bg-strength-68 { background-color: rgba(226, 255, 000,1.0); color: black; }
.bg-strength-69 { background-color: rgba(226, 255, 000,1.0); color: black; }
.bg-strength-70 { background-color: rgba(255, 226, 000,1.0); color: black; }
.bg-strength-71 { background-color: rgba(255, 226, 000,1.0); color: black; }
.bg-strength-72 { background-color: rgba(255, 192, 000,1.0); color: black; }
.bg-strength-73 { background-color: rgba(255, 192, 000,1.0); color: black; }
.bg-strength-74 { background-color: rgba(255, 158, 000,1.0); color: black; }
.bg-strength-75 { background-color: rgba(255, 158, 000,1.0); color: black; }
.bg-strength-76 { background-color: rgba(255, 124, 000,1.0); color: black; }
.bg-strength-77 { background-color: rgba(255, 124, 000,1.0); color: black; }
.bg-strength-78 { background-color: rgba(255, 090, 000,1.0); color: black; }
.bg-strength-79 { background-color: rgba(255, 090, 000,1.0); color: black; }
.bg-strength-80 { background-color: rgba(255, 056, 000,1.0); color: black; }
.bg-strength-81 { background-color: rgba(255, 056, 000,1.0); color: black; }
.bg-strength-82 { background-color: rgba(255, 034, 000,1.0); color: black; }
.bg-strength-83 { background-color: rgba(255, 034, 000,1.0); color: black; }
.bg-strength-84 { background-color: rgba(255, 000, 000,1.0); color: black; }
.bg-strength-85 { background-color: rgba(255, 000, 000,1.0); color: black; }
.bg-strength-86 { background-color: rgba(255, 000, 000,1.0); color: black; }
.bg-strength-87 { background-color: rgba(255, 000, 000,1.0); color: black; }
.bg-strength-88 { background-color: rgba(255, 000, 000,1.0); color: black; }
.bg-strength-89 { background-color: rgba(255, 000, 000,1.0); color: black; }
.bg-strength-90 { background-color: rgba(255, 000, 000,1.0); color: black; }




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
<a href="{{url("infrastructure/" . $row->equipment_id )}}">{{ $row->equipment->hostname }}</a> @if($row->distanceToAP())({{$row->distanceToAP()}} km)@endif</td>
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