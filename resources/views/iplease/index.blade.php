@extends('common.master')
@section('title')
    DHCP Leases
@endsection
@section('content')

                    <h2>@yield('title')</h2>
                    <div class="table-responsive">

                        <table class="table table-responsive table-condensed table-striped">

                            <thead>
                            <tr>
                                <th>Hostname</th>
                                <th>IP Address</th>
                                <th>MAC Address</th>
                                <th>Starts</th>
                                <th>Ends</th>
                                <th>Server</th>
                                <th>Vendor</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($leases as $lease)
                            <tr>
                                <td>{{ $lease->hostname }}</td>
                                <td>{{ $lease->ip }}</td>
                                <td>{{ $lease->mac_address }}</td>
                                <td>{{ $lease->starts() }}</td>
                                <td>{{ $lease->ends() }}</td>
                                <td>{{ $lease->dhcp_server }}</td>
                                <td>{{ $lease->mac_oui_vendor }}</td>
                            </tr>
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
