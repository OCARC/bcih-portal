
<div class="table-responsive">

    <table class="table sortable table-responsive table-condensed table-striped table-bordered table-hover">

        <thead>
        <tr>
            <th>Name</th>
            <th>Domain</th>
            <th>Server</th>
            <th>Key</th>
            <th>Records</th>

        </tr>
        </thead>
        <tbody>
        @foreach ($records as $row)
            <tr>
                <td>
                    <span class="mdi mdi-dns"></span>
                    <a href="/dns-zones/{{$row->id}}">{{ $row->name  }}</a>
                </td>
                <td>
                    {{ $row->domain  }}
                </td>
                <td>
                    {{ $row->server  }}
                </td>
                <td>
                    @if( $row->dns_key)
                    Yes
                    @endif
                </td>

               <td class="text-end">
                   {{ count($row->dnsrecords)}}
               </td>


            </tr>
        @endforeach
        </tbody>

    </table>
</div>

