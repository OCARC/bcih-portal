@extends('common.master')
@section('title')
    RouterOS Update Manager
@endsection
@section('content')

    <script>
        var requirements = {
            // '': {
            //     "name": "",
            //     "shortName": "",
            //     "description": "",
            //     "require-regex": [],
            //     "disallow-regex": []
            // },
            'sysLog': {
                "name": "Central Logging",
                "description": "Log Server",
                "require-regex": [
                    "system logging action",
                    "set 3 remote=44.135.216.4.*",
                    "system logging",
                    "add action=remote topics=info",
                    "add action=remote topics=warning",
                    "add action=remote topics=error"
                ],
                "disallow-regex": [],
                "failResult" : '&#9888;',
                "failResultClass" : 'warning',
                "passResult" : '&#10004;',
                "passResultClass" : 'success',
            },
            'services': {
                "name": "Service Configuration",
                "description": "Insecure Services Are Disabled",
                "require-regex": [
                    "set telnet disabled=yes",
                    "set ftp disabled=yes",
                    "set www disabled=yes",
                    "set api disabled=yes",
                    "set api-ssl disabled=yes"
                ],
                "disallow-regex": [],
                "failResult" : '&#128293;',
                "failResultClass" : 'danger',
                "passResult" : '&#10004;',
                "passResultClass" : 'success',
            },
            'firewallFilters': {
                "name": "Firewall Filters",
                "description": "Common Firewall Filters Are Configured",
                "require-regex": [
                    /add action=accept chain=input connection-state=established/,
                    /add action=reject chain=input dst-address=44.135.216.0\/23 protocol=!icmp \D+reject-with=icmp-network-unreachable src-address=!44.0.0.0\/8/,
                    /add action=reject chain=input dst-address=44.135.160.0\/21 protocol=!icmp \D+reject-with=icmp-network-unreachable src-address=!44.0.0.0\/8/

                ],
                "disallow-regex": [],
                "failResult" : '&#128293;',

                "failResultClass" : 'danger',
                "passResult" : '&#10004;',
                "passResultClass" : 'success',
            },
            'snmp': {
                "name": "SNMP",
                "description": "SNMP Config",
                "require-regex": [
                    /\/snmp community\r\nset (\[ find default=yes \] )*addresses=\d+.0.0.0\/0 name=hamwan/gm,
                ],
                "disallow-regex": [],
                "failResult" : '&#9888;',
                "failResultClass" : 'warning',
                "passResult" : '&#10004;',
                "passResultClass" : 'success',
            },
            'radius': {
                "name": "Radius",
                "description": "Radius Config",
                "require-regex": [
                    /add address=44.135.217.99 secret=AmprNET service=login/gm,
                ],
                "disallow-regex": [],
                "failResult" : '&#9888;',
                "failResultClass" : 'warning',
                "passResult" : '&#10004;',
                "passResultClass" : 'success',
            },
            'boot-device' : {
                "name": "Netboot",
                "description": "Network Boot Enabled",
                "require-regex": [
                    /set boot-device=try-ethernet-once-then-nand/gm,
                ],
                "disallow-regex": [],
                "failResult" : '&#128293;',
                "failResultClass" : 'danger',
                "passResult" : '&#10004;',
                "passResultClass" : 'success',
            }
        }



    </script>

    <h2>RouterOS Configuration Validator</h2>


    <table class="table sortable table-responsive table-condensed table-striped table-bordered table-hover">
        <thead>
<th>Host</th>
<th>Version</th>
<th class="validationResult-online" style="display: none;" ><div><span>Check: Online</span></div></th>
        </thead>
    <tbody>
    @foreach ($equipment as $row)

        <tr id="equipment-{{ $row->id }}" class="ajaxAction">
            <td>
                <a href="{{url("equipment/" . $row->id ) }}">{{ ($row->librenms_mapping) ? $row->libre_device['hostname'] : $row->hostname }}</a><br>
                {{ ($row->librenms_mapping) ? $row->libre_device['sysName'] : $row->snmp_sysName }}

            </td>
            <td class="version">
                {{ ($row->librenms_mapping) ? $row->libre_device['version'] : '-' }}

            </td>
            <td class="output ajaxResult" style="display:none">

            </td>
            <td  style="display: none;" class="validationResult validationResult-online" style="">
                ?
            </td>
            <td  style="">
                <div class="ajaxButtons">
                <button class="btn btn-default btn-checkConfig"
                        onClick="ajaxAction(this,'{{url('equipment/' . $row->id . "/fetchConfig")}}?strip=1', checkConfigCallback )">
                    Check Config
                </button>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
    </table>
@endsection

@section('scripts')

    <script>
        $( document ).ready( function() {
            // Create columns

            Object.keys(requirements).forEach(function(key) {
                var req = requirements[key];
                $('thead .validationResult-online').after('<th class="validationResult validationResult-'+ key +'">' + key + '</th>');
                $('tbody .validationResult-online').after('<td class="validationResult validationResult-'+ key +'">-</td>');

            });

            $('.btn-checkConfig').each(function(k,v) {
                $(v).click();
            });
        });

        function checkConfigCallback( target,data ) {

            if ( ! data.data  ) {
                $(target).closest('tr').find('td.validationResult').html("n/c");
                return "";
            }
            Object.keys(requirements).forEach(function(key) {
                var req = requirements[key];

                // Check Pass
                var required = 0;
                var requireCount = 0;
                Object.keys(req['require-regex']).forEach(function (requireKey) {
                    required++;
var re;
                    if ( req['require-regex'][requireKey].exec ) {
                        re = req['require-regex'][requireKey];


                    } else {
                        // make it a regex
                        re = new RegExp(req['require-regex'][requireKey]);

                    }

                    var config = $(target).closest('tr').find('td.ajaxResult pre').text();

                    var r = re.exec(data.data);
                    if ( r ) {
                        requireCount++;
                    }
                });
                if ( required == requireCount) {
                    $(target).closest('tr').find('td.validationResult-' + key).html( req.passResult );
                    $(target).closest('tr').find('td.validationResult-' + key).removeClass( req.failResultClass );
                    $(target).closest('tr').find('td.validationResult-' + key).addClass( req.passResultClass );
                    $(target).closest('tr').find('td.validationResult-' + key).append('<div class="failInfo">' + requireCount + ' of ' + required + '</div>');
                    $(target).closest('tr').find('td.validationResult-' + key).removeClass( 'info' );

                } else {
                    $(target).closest('tr').find('td.validationResult-' + key).html( req.failResult );
                    $(target).closest('tr').find('td.validationResult-' + key).append('<div class="failInfo">' + requireCount + ' of ' + required + '</div>');
                    $(target).closest('tr').find('td.validationResult-' + key).removeClass( req.passResultClass );
                    $(target).closest('tr').find('td.validationResult-' + key).removeClass( 'info' );

                    $(target).closest('tr').find('td.validationResult-' + key).addClass( req.failResultClass );
                }
                if ( requireCount > required ) {
                    $(target).closest('tr').find('td.validationResult-' + key).addClass('info');
                }
                });
        }
    </script>
    <style>
        td.output.ajaxResult pre {
            max-height: 60px;
            padding: 0px;
            margin: 0px;
            font-size:10px;
        }
        td.validationResult.danger {
            color:red;
        }
        td.validationResult.success {
            color:green;
        }
        td.validationResult.warning {
            color:orange;
        }
        td.validationResult {
            text-align: center;
            vertical-align: middle !important;
            font-size: 1.5em;
        }

        .failInfo {
            font-size: 0.5em;
            color:black;
        }
    </style>
@endsection
