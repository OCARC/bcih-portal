<script>

    var activePings;
    function pingAll() {
        event.stopPropagation();

        if( $('.pingButton').length >= 1 ) {
            var b = $('.pingButton')[0];

            pingHost(b, $(b).attr('host'), function () {
                pingAll();
            })
        }

    }
    function pingHost(result,host,callback) {
        $(result).hide();
        $.getJSON( "/ping?host=" + host, function( data ) {
            $(result).show();

            if ( data.online == true ) {
                $( result ).replaceWith('<span class="label label-success">' + data.average + " ms</span>");
            } else {
                $( result ).replaceWith('<span class="label label-danger">DOWN</span>');
            }
            callback();
        });
    }
</script>

<div class="table-responsive">

    <table class="table sortable table-responsive table-condensed table-striped table-bordered table-hover">

        <thead>
        <tr >
            <th>IP</th>
            <th>Hostname</th>
            <th class="text-center"><a href="#" onclick="pingAll();" class="btn btn-xs btn-default">Ping</a></th>
            <th class="text-center">DNS</th>
            <th style="    width: 128px;">MAC</th>

            {{--<th>Gateway</th>--}}
            {{--<th>Netmask</th>--}}
            <th>Descriptive Name</th>
            <th class="text-center">Group</th>
            <th class="text-center">DHCP</th>
            <th>Expires</th>
            <th>-</th>
            <th class="text-center">Site</th>
{{--            <th class="text-center">User</th>--}}

            {{--<th>Uptime</th>--}}
            {{--<th>Last Queried</th>--}}
        </tr>
        </thead>
        <tbody>
        @foreach ($ips as $row)
            <tr class="@if($row->type =='dhcp')info @endif ">
                <td sorttable_customkey="{{ ip2long($row->ip) }}">

                    @if( $row->type =='dhcp')
                        <span class="mdi mdi-ip">&nbsp;</span>{{ $row->ip  }}
                    @else
                    @if ($row->id)
                        <span class="mdi mdi-ip">&nbsp;</span><a href="{{url("ips/" . $row->id )}}">{{ $row->ip  }}</a>
                    @else
                        <span class="mdi mdi-ip">&nbsp;</span>{{ $row->ip  }}
                    @endif
                    @endif

                </td>
                <td><span style="font-weight: bold;">{{$row->hostname}}</span>.{{$row->dns_zone}}

                    @if ( $row->equipment )
                        <a href="{{url("equipment/" . $row->equipment->id )}}">(E)</a>
                    @else

                    @endif

                    @if ( $row->dhcp_lease() )
                        @if ( $row->dhcp_lease()->client() )
                            <a href="{{url("clients/" . $row->dhcp_lease()->client()->id )}}">(C)</a>
                        @else

                        @endif
                    @else

                    @endif
                </td>
                <td  class="text-center">
                    <a href="#" class="pingButton" host="{{ $row->ip  }}" onClick="pingHost(this,'{{ $row->ip  }}')" class="btn btn-xs btn-default">Ping</a>
                </td>

                <td style="text-align: center; vertical-align:middle" sorttable_customkey="{{$row->dns}}">
                    @if ( $row->dns == 'Yes' )
                        <span class="label label-success">Yes</span><br>
                    @elseif ( $row->dns == 'ReverseOnly' )
                        <span class="label label-warning">Rev</span><br>
                    @elseif ( $row->dns == 'No' )
                        <span class="label label-danger">No</span><br>
                    @else
                        <span class="label label-default">{{ $row->dns }}</span>
                    @endif
                </td>
                <td class="font-family: monospace">{{ implode(":",str_split(strtoupper($row->mac_address),2)) }}</td>

                {{--<td sorttable_customkey="{{ ip2long($row->gateway) }}">{{$row->gateway}}</td>--}}
                {{--<td sorttable_customkey="{{ ip2long($row->netmask) }}">{{$row->netmask}}</td>--}}
                <td>

                    @if ( $row->name )
                    {{$row->name}}
                    @else
                    {{$row->description}}
                @endif
                </td>

                <td style="text-align: center; vertical-align:middle" sorttable_customkey="{{$row->category}}">
                    @if ($row->category == "DHCP Reserved")

                        <span class="label label-info">{{ $row->category }}</span>

                    @elseif( $row->category == "Static")
                        <span class="label label-warning">{{ $row->category }}</span>
                    @elseif( $row->category == "Infrastructure")
                        <span class="label label-warning">{{ $row->category }}</span>
                    @elseif( $row->category == "Leased")
                        <span class="label label-info">{{ $row->category }}</span>
                    @elseif( $row->category == "Reserved")
                        <span class="label label-primary">{{ $row->category }}</span>
                    @elseif( $row->category == "OSPF Routing")
                        <span class="label label-danger">{{ $row->category }}</span>

                    @else
                        <span class="label label-default">{{ $row->category }}</span>

                    @endif
                </td>

                <td style="text-align: center; vertical-align:middle">
{{--                    @if ( $row->dhcp )--}}
{{--                        @if ( $row->dhcp_lease() )--}}
{{--                            <span class="label label-info">In Use</span><br>--}}
{{--                        @else--}}
{{--                            <span class="label label-success">Yes</span><br>--}}
{{--                        @endif--}}
{{--                    @elseif ( $row->dhcp == "No" )--}}

{{--                        <span class="label label-danger">No</span>--}}
{{--                    @endif--}}
                    @if ( $row->type == 'dhcp')
                                                <span class="label label-info">Yes</span>
                    @endif
                </td>

                <td class="text-right">
                    @if ( $row->type == 'dhcp')
                    @if( $row->dhcp_expires === -1 )
                        Reserved
                    @else
                    {{ $row->dhcp_expires - time() }} s
                    @endif
                        @endif
                </td>
                <td>



                </td>
                <td class="text-center" style="vertical-align:middle;">
                    @if ($row->site )
                        <a href="{{url("sites/" . $row->site_id )}}">{{ $row->site->sitecode }}</a>
                    @else
                        -
                    @endif
                </td>
{{--                <td class="text-center" style="vertical-align:middle;">--}}
{{--                    @if ($row->user)--}}
{{--                        <a href="{{url("users/" . $row->user_id )}}">{{ $row->user->callsign }}</a>--}}
{{--                    @else--}}
{{--                        ---}}
{{--                    @endif--}}
{{--                </td>--}}

            </tr>
        @endforeach
        </tbody>

    </table>
</div>

