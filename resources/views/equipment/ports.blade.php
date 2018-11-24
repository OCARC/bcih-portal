<style>
    a.port-icon{
        text-decoration: none;

    }
    .l2vlan:before {
        position: relative;
        top: .25em;
        content: "VLAN";
        color: white;
        font-weight:bold;
    }
    .ethernetCsmacd:before {
        position: relative;
        top: .25em;
             content: "ETH";
             color: white;
             font-weight:bold;
         }
    .bridge:before {
        position: relative;
        top: .25em;        content: "BR";
        color: white;
        font-weight:bold;
    }
    .bridge, .ethernetCsmacd, .l2vlan {
        width: 3em;
        height: 1.9em;
        margin-right: .5em;
        margin-left: .5em;
        position: relative;
        background-color: white;
        display: inline-block;
    }

    .ethernetCsmacd:after {
        content: "";
        position: absolute;
        top: 1.7em;
        height: .65em;
        background-color: white;
        padding: 1px;
        left: .8em;
        right: .8em;
    }

    .bridge.ifOperStatus-up, .bridge.ifOperStatus-up:after,
    .l2vlan.ifOperStatus-up, .l2vlan.ifOperStatus-up:after,
    .ethernetCsmacd.ifOperStatus-up, .ethernetCsmacd.ifOperStatus-up:after {

        background: #5cb85c;
    }

    .bridge.ifOperStatus-down, .bridge.ifOperStatus-down:after,
    .l2vlan.ifOperStatus-down, .l2vlan.ifOperStatus-down:after,
    .ethernetCsmacd.ifOperStatus-down, .ethernetCsmacd.ifOperStatus-down:after {

        background: #d9534f;
    }



    .ieee80211 {
        padding: 4px;
        padding-bottom: 0px;
        margin-bottom: -18px;
        margin-top: 0.7em;
    }

    .ieee80211, .ieee80211:before {
        display: inline-block;
        border: 12px double transparent;
        border-top-color: currentColor;
        border-radius: 50%;
    }

    .ieee80211:before {
        content: '';
        width: 0; height: 0;
    }

    .ieee80211.ifOperStatus-up, .ieee80211.ifOperStatus-up:before {

        border-top-color: #5cb85c;
    }
    .ieee80211.ifOperStatus-down, .ieee80211.ifOperStatus-down:before {

        border-top-color:  #d9534f;
    }

    .ieee80211.ifOperStatus-down, .ieee80211.ifOperStatus-down:before {

        border-top-color:  #d9534f;
    }
    .ports-table {
        border: 1px solid grey;
        margin: .5em;

    }
    .ports-table td {

        padding-left:.5em;
        padding-right:.5em;
        width:100px;
    }

    .ports-table td.port-ifAlias {
        vertical-align: middle;
        padding-top: .5em;
        font-size: 10px;
        border-bottom:none;

    }
    .ports-table td.port-info {
        border-top:none;
        padding-top: .5em;
    }
</style>

{{--{{ dump( $ports ) }}--}}

@php ($i = 1)

<table class="ports-table table table-bordered">
    <tr>
        @foreach( $ports as $port )
                <td class="text-center port-ifAlias" style="">{{ $port->ifAlias }}</td>
        @endforeach
    </tr>
    <tr>
        @foreach( $ports as $port )
                <td class="text-center port-info">

                    <a data-toggle="tooltip" class="port-icon {{ $port->ifType }} ifOperStatus-{{ $port->ifOperStatus }} ifAdminStatus-{{ $port->ifAdminStatus }}"></a>
                </td>

        @endforeach
    </tr>

</table>