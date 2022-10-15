@extends('common.master')
@section('title')
    Static Leases
@endsection
@section('content')

                    <h2>@yield('title')</h2>

                        <table class="table table-responsive table-condensed table-striped">

                            <thead>
                            <tr>
                                <th>Hostname</th>
                                <th>IP Address</th>
                                <th>MAC Address</th>
                                <th>Server</th>

                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($leases as $lease)
                            <tr>
                                <td>{{ $lease->hostname }}</td>
                                <td>{{ $lease->ip }}</td>
                                <td>{{ $lease->mac_address }}</td>
                                <td>{{ $lease->dhcp_server }}</td>
                            </tr>
                            @endforeach
                            </tbody>

                        </table>


@endsection
