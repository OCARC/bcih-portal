@extends('common.master')
@section('title')
    DHCP Leases
@endsection
@section('content')

                    <h2>@yield('title')</h2>
                    <div class="table-responsive">

                        <table class="table sortable table-responsive table-condensed table-striped table-bordered table-hover">

                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>IP Address</th>
                                <th>MAC Address</th>
                                <th>Expires</th>
                                <th>TTL</th>
                                <th>Server</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($leases as $lease)
                                @if ( $lease->client() )
                            <tr @if ( $lease->ttl() <= 0)style="opacity: 0.5"@endif>
                                <td><a href="{{ url("/clients/" . $lease->client()->id ) }}">{{ ( $lease->client()->snmp_sysName ) ? $lease->client()->snmp_sysName : $lease->hostname }}</a></td>
                                <td style="font-family: 'Courier New'">{{ $lease->ip }}</td>
                                <td style="font-family: 'Courier New'"><a href="{{ url("/clients/" . $lease->client()->id ) }}">{{ implode(":",str_split(strtoupper($lease->mac_address),2)) }}</a></td>
                                <td>{{ $lease->ends() }}</td>
                                <td class="text-right">{{ $lease->ttl() }}</td>

                                @if ( $lease->server() )
                                    <td><a href="{{ url("/equipment/" . $lease->server()->id ) }}">{{ $lease->server()->hostname  }}</a></td>
                                @else
                                    <td>{{ $lease->dhcp_server }}</td>

                                @endif
                            </tr>
                                    @else
                                    <tr @if ( $lease->ttl() <= 0)style="opacity: 0.5"@endif>
                                        <td>{{ $lease->hostname }}</td>
                                        <td style="font-family: 'Courier New'">{{ $lease->ip }}</td>
                                        <td style="font-family: 'Courier New'">{{ implode(":",str_split(strtoupper($lease->mac_address),2)) }}</td>
                                        <td>{{ $lease->ends() }}</td>
                                        <td class="text-right">{{ $lease->ttl() }}</td>
                                        @if ( $lease->server() )
                                        <td><a href="{{ url("/equipment/" . $lease->server()->id ) }}">{{ $lease->server()->hostname  }}</a></td>
                                        @else
                                            <td>{{ $lease->dhcp_server }}</td>

                                        @endif
                                    </tr>
                            @endif
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="3">
                                    <a href="{{ url("/lease-ip/refresh") }}">poll dhcp server</a>
                                </td>
                            </tr>
                            </tfoot>
                        </table>
</div>

@endsection
