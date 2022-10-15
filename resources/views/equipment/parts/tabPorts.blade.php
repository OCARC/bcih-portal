<div role="tabpanel" class="tab-pane" id="ports">

    <div class="table-responsive">

        <table class="table sortable table-responsive table-condensed table-striped table-bordered">

            <thead>
            <tr>
                <th style="width: 20px"></th>
                <th style="width: 50px"></th>
                <th>ifName</th>
                <th>Status</th>
                <th>Speed</th>
                <th>Type</th>
                <th>MAC Address</th>
                <th>IP Address</th>

            </tr>
            </thead>
            <tbody>
            @foreach ($equipment->libre_ports->sortBy('ifPhysAddress') as $row)
                <tr>
<td style="position: relative;">
    @if( $row->ifAdminStatus == 'down' )
        <div style="position: absolute; top: 5px; bottom: 5px; left:5px; right: 5px; background: #f0ad4e"></div>
    @elseif( $row->ifOperStatus == 'down')
        <div style="position: absolute; top: 5px; bottom: 5px; left:5px; right: 5px; background: #d9534f"></div>

    @else
        <div style="position: absolute; top: 5px; bottom: 5px; left:5px; right: 5px; background: #5cb85c"></div>

    @endif
</td>
<td></td>
                    <td>{{$row->ifName }}
                        <br>{{( $row->ifName != $row->ifAlias) ? $row->ifAlias : "" }}
                    </td>
                    <td class="text-center">
                        @if( $row->ifAdminStatus == 'down')
                        shutdown
                        @else
                        {{ $row->ifOperStatus  }}
                        @endif
                    </td>
                    <td class="text-end">{{$row->ifSpeed}}</td>
                    <td>
                        @if( $row->ifType == 'l2vlan')
                            VLAN
                        @elseif( $row->ifType == 'bridge')
                            Bridge
                        @elseif( $row->ifType == 'ieee80211')
                            Wireless
                        @elseif( $row->ifType == 'ethernetCsmacd')
                            Ethernet
                        @elseif( $row->ifType == 'tunnel')
                            Tunnel
                        @else
                            {{ $row->ifType  }}
                        @endif
                    </td>
                    <td class="">{{$row->ifPhysAddress}}</td>
<td class="text-end">
    @foreach ($row->libre_ipv4Addresses as $ip)
{{$ip->ipv4_address}} / {{$ip->ipv4_prefixlen}}<br>
    @endforeach
</td>
                    {{--<td style="position: relative;">--}}
                    {{--<td style="position: relative;">--}}

                        {{--<div style="position: absolute; top: 5px; bottom: 5px; left:5px; right: 5px; background: {{$row->statusColor() }}"></div>--}}

                    {{--</td>--}}

                    {{--<td style="height: 50px; background-repeat: no-repeat; background-position:center; background-size: auto 38px; background-image: url('{{$row->map_icon}}')">--}}

                    {{--<td><a href="{{ url("site/" . $row->id ) }}">{{ $row->name }}</td>--}}
                    {{--<td>{{ $row->sitecode }}</td>--}}
                    {{--<td>{{ $row->status }}</td>--}}

                    {{--@if (! Auth::guest())--}}
                        {{--<td>{{$row->comment}}</td>--}}
                    {{--@else--}}
                        {{--<td>Login to view comments</td>--}}
                    {{--@endif--}}
                    {{--@if (! Auth::guest())--}}
                        {{--<td>--}}
                            {{--<a href="http://www.google.com/maps/?q={{ $row->latitude }},{{ $row->longitude }}">{{ number_format($row->latitude,3) }},&nbsp;{{ number_format($row->longitude,3) }}</a></td>--}}
                    {{--@else--}}
                        {{--<td>n/a</td>--}}
                    {{--@endif--}}
                    {{--<td class="text-end">{{ $row->altitude }}m</td>--}}
                </tr>
            @endforeach
            </tbody>

        </table>
    </div>
</div>
