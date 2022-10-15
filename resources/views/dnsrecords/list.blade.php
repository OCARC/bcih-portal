<script>

    var activePings;
    var checkingAll;
    function dnsAll(start) {
        if ( start ) {
            checkingAll = true;
        }
        if( checkingAll == true ) {


            if ($('.dnsButton').length >= 1) {
                var b = $('.dnsButton')[0];
                $(b).click();

                //
                // pingHost(b, $(b).attr('host'), function () {
                //     pingAll();
                // })
            }
        }
    }
    function dnsCheck(result,hostname,zone,target,type,callback) {
        $(result).hide();
        $.getJSON( "/dnsCheck?hostname=" + hostname + "&zone=" + zone + "&target=" + target + "&type=" + type, function( data ) {
            $(result).show();


            if (data.status == 'OK') {
                color = " bg-success";
            }
            else if( data.status == "Missing") {
                color = " bg-warning";
            }
            else if( data.status == "Different") {
                    color = " bg-danger";

            }
            else if( data.status == "Not Supported") {
                color = " bg-info";

            }
            $( result ).replaceWith('<span class="badge ' + color + '">' + data.status + "</span>");

            callback();
        });
    }
</script>

<div class="table-responsive">

    <table class="table sortable table-responsive table-condensed table-striped table-bordered table-hover">

        <thead>
        <tr>
            <th>Hostname</th>
            <th>IP Record</th>
            <th>Type</th>
            <th>TTL</th>
            <th>Target</th>
            <th class="text-center"><a href="#" onclick="dnsAll(true);" class="btn btn-xs btn-default">Check</a></th>
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
                <td class="text-center">
                    <button class="dnsButton" onclick="dnsCheck(this,'{{ $row->hostname }}','{{ $row->dnsZone ? $row->dnsZone->domain : '' }}', '{{$row->target}}', '{{$row->record_type}}', dnsAll)">Check</button>

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

