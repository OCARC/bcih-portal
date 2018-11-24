
<div class="table-responsive">

    <table class="table sortable table-responsive table-condensed table-striped table-bordered table-hover">

        <thead>
        <tr>
            <th>Link</th>
            <th>Status</th>

            <th>Frequency</th>
            <th>Distance</th>
            <th style="text-align: center;">AP<br>Site</th>
            <th>AP Equipment</th>
            <th style="text-align: center;">Client<br>Site</th>
            <th>Client Equipment</th>
            <th>Signal</th>
            <th>SNR</th>
            <th></th>
            <th>Rates</th>
            <th>CH0</th>
            <th>CH1</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($links as $row)
            <tr>

                <td>
                    <a href="/links/{{$row->id}}">{{ $row->name }}</a><br>
                    {{ $row->comments }}
                </td>

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


                <td>
                    @if( $row->ap_equipment )
                        {{ $row->ap_equipment->snmp_band }}
                    @endif
                </td>
                <td class="text-right">
                    {{$row->distance() }} KM
                </td>
                <td style="text-align: center; font-family: monospace; vertical-align: middle">
                    @if( $row->ap_site )
                        <a href="/site/{{$row->ap_site_id}}">{{ $row->ap_site->sitecode }}</a>
                    @endif
                </td>
                <td>
                    @if( $row->ap_equipment )
                        <img src="{{$row->ap_equipment->icon()}}" style="height: 2.75em; float:left; margin-right: 0.5em">

                <a href="/equipment/{{$row->ap_equipment_id}}">{{ $row->ap_equipment->hostname }}</a>
                        <div class="text-muted small">{{ $row->ap_equipment->radio_model }}</div>
                        @endif
                </td>
                <td style="text-align: center; font-family: monospace; vertical-align: middle">
                    @if( $row->cl_site )
                        <a href="/site/{{$row->cl_site_id}}">{{ $row->cl_site->sitecode }}</a>
                    @endif
                </td>
                <td>
                    @if( $row->cl_equipment )
                        <img src="{{$row->cl_equipment->icon()}}" style="height: 2.75em; float:left; margin-right: 0.5em">

                        <a href="/equipment/{{$row->cl_equipment_id}}">{{ $row->cl_equipment->hostname }}</a>
                        <div class="text-muted small">{{ $row->cl_equipment->radio_model }}</div>
                        @endif
                </td>
                <td>
                    @if( $row->cl_equipment )
                        {{ $row->cl_equipment->snmp_signal_to_noise }}
                    @endif
                </td>
                <td>
                    -
                </td>
                <td class="text-right" style="font-weight: bold;">
                    TX<br>
                    RX
                </td>
                <td>
                    {{ number_format($row->tx_speed()/1000000,1) }} Mbps<br>
                    {{ number_format($row->rx_speed()/1000000,1) }} Mbps<br>
                </td>
                <td>
                    @if( $row->cl_equipment )
                        {{ $row->cl_equipment->snmp_signal_to_noise }}
                    @endif
                </td>
                <td>
                    -
                </td>

            </tr>
        @endforeach
        </tbody>

    </table>
</div>