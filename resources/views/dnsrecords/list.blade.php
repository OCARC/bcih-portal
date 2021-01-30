
<div class="table-responsive">

    <table class="table sortable table-responsive table-condensed table-striped table-bordered table-hover">

        <thead>
        <tr>
            <th>Hostname</th>
            <th>IP Record</th>
            <th>Type</th>
            <th>TTL</th>
            <th>Target</th>
            <th>Zone</th>

        </tr>
        </thead>
        <tbody>
        @foreach ($records as $row)
            <tr>
                <td>
                    <a href="/dns-records/{{$row->id}}">{{ $row->hostname  }}</a>
                </td>
                <td>
                    @if ( $row->ip )
                        <a href="/ips/{{$row->ip->id}}">{{ $row->ip->name  }} ({{ $row->ip->ip  }})</a>

                    @endif
                        @if ( $row->dhcpLease )
                            DHCP

                        @endif
                </td>
                <td>
                    {{ $row->record_type  }}
                </td>
                <td>
                    {{ $row->ttl  }}
                </td>
                <td>
                    {{ $row->target  }}
                </td>
                <td>
                    @if ( $row->dnsZone )
                        <a href="/dns-zones/{{$row->dnsZone->id}}">{{ $row->dnsZone->name  }}</a> ({{ $row->dnsZone->domain  }})
                    @else
                        none
                    @endif
                </td>


            </tr>
        @endforeach
        </tbody>

    </table>
</div>

