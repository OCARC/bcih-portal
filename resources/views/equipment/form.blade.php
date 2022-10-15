<form class="" method="POST" action="{{ url("/equipment") }}">

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $equipment->id }}">

    <div class="card card-default m-2">
        <div class="card-header">
            Device Information
        </div>
        <div class="card-body row">
            <div class="form-group col-6">
                <label class="fw-bold col-form-label" for="name">Hostname</label>
                <input type="text" name="hostname" class="form-control" value="{{ $equipment->hostname }}">
            </div>
            <div class="form-group col-6">
                <label class="fw-bold col-form-label" for="name">Management IP</label>
                <input type="text" name="management_ip" class="form-control" value="{{ $equipment->management_ip }}">

            </div>
            <div class="form-group col-6">
                <label class="fw-bold col-form-label" for="name">Operating System</label>
                <select name="os" class="form-select" required>
                    <option value=""></option>
                    <option @if ($equipment->os == "other") selected="true" @endif value="other">Other</option>
                    <option @if ($equipment->os == "RouterOS") selected="true" @endif value="RouterOS">RouterOS
                    <option @if ($equipment->os == "CyberPower") selected="true" @endif value="CyberPower">CyberPower
                    <option @if ($equipment->os == "EdgeRouter") selected="true" @endif value="EdgeRouter">EdgeRouter
                    <option @if ($equipment->os == "DAEnetIP4") selected="true" @endif value="DAEnetIP4">DAEnetIP4 (Denkovi)
                    <option @if ($equipment->os == "Cisco IOS") selected="true" @endif value="Cisco IOS">Cisco IOS
                    <option @if ($equipment->os == "Proxmox") selected="true" @endif value="Proxmox">Proxmox
                    <option @if ($equipment->os == "Stardot") selected="true" @endif value="Stardot">Stardot
                    </option>

                </select>
            </div>

            <div class="form-group col-6">
                <label class="fw-bold col-form-label" for="name">Status</label>
                <select name="status" class="form-select" required>
                    <option value=""></option>
                    <option @if( $equipment->status == "Potential") selected="true"
                            @endif style="background-color: #e1e1e1">
                        Potential
                    </option>
                    <option @if( $equipment->status == "Planning") selected="true"
                            @endif style="background-color: #fff6a6">
                        Planning
                    </option>
                    <option @if( $equipment->status == "Installed") selected="true"
                            @endif style="background-color: #aaffaa">
                        Installed
                    </option>
                    <option @if( $equipment->status == "Equip Failed") selected="true"
                            @endif style="background-color: #ff6666">
                        Equip Failed
                    </option>
                    <option @if( $equipment->status == "Problems") selected="true"
                            @endif style="background-color: #ffd355">
                        Problems
                    </option>
                    <option @if( $equipment->status == "No Install") selected="true"
                            @endif style="background-color: #979797"
                            value="No Install">No Install - Equipment will never be installed
                    </option>

                </select></div>

            <div class="form-group col-6">

                <label class="fw-bold col-form-label" for="name">Site</label>
                <select name="site_id" class="form-select" style="font-family: courier new" required>
                    @foreach( $sites as $site)
                        <option @if ($equipment->site_id == $site->id) selected="true"
                                @endif value="{{ $site->id }}">{{$site->sitecode}}  {{$site->name}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-6">
                <label class="fw-bold col-form-label" for="name">Owner</label>
                <select name="user_id" class="form-select" required>
                    <option value="0"></option>
                    @foreach( $users as $user)
                        <option @if ($equipment->user_id == $user->id) selected="true"
                                @endif value="{{ $user->id }}">{{$user->name}} ({{$user->callsign}})
                        </option>
                    @endforeach
                </select>
                <p class="form-text">Be careful when assigning ownership. If you assign ownership to someone else
                    you may
                    not be able to access the device anymore.</p>

            </div>

            <div class="form-group col-6">
                <label class="fw-bold col-form-label" for="name">Health Indicator Type</label>
                <select name="health_from" class="form-select">
                    <option value="">Disabled</option>
                    <option @if ($equipment->health_from == "snmp") selected="true" @endif v value="snmp">from SNMP timestamp</option>
                    <option @if ($equipment->health_from == "icmp") selected="true" @endif v value="icmp">from Ping timestamp</option>

                </select>
            </div>
            <div class="form-group col-3">
                <label class="fw-bold col-form-label" for="name">SNMP Community</label>
                <input type="text" name="snmp_community" class="form-control" value="{{ $equipment->snmp_community }}">
            </div>
            <div class="form-group col-3">
            @if( $equipment->os == 'CyberPower')
                <label class="fw-bold col-form-label" for="name">SNMP Community RW</label>
                <input type="password" name="snmp_community_rw" class="form-control" value="{{ $equipment->snmp_community_rw }}">
                @endif
            </div>
        </div>
    </div>

    @include('common.rolesForm', ['target' => $equipment])

    <div class="card card-default m-2">
        <div class="card-header">
            Analytics
        </div>
        <div class="card-body row">


            <div class="form-group col-6">
                <label class="fw-bold col-form-label" for="name">LibreNMS Mapping</label>
                <select name="librenms_mapping" class="form-select">
                    <option value=""></option>
                    @foreach( $equipment->libreGetDeviceList() as $libreHost)
                        <option @if ($equipment->librenms_mapping == $libreHost['device_id']) selected="true"
                                @endif value="{{ $libreHost['device_id'] }}">{{$libreHost['hostname'] }}
                            ({{$libreHost['sysName'] }})
                        </option>
                    @endforeach
                </select></div>
        </div>
    </div>

    <div class="card card-default m-2">
        <div class="card-header">
            Equipment Information
        </div>

        <div class="card-body row">
            <div class="col-6">
                <label class="fw-bold col-form-label" for="name">Equipment Model</label>
                <input type="text" name="radio_model" class="form-control" value="{{ $equipment->radio_model }}">
                <p class="form-text">In most cases the portal will <strong>automatically populate</strong> this
                    field from
                    SNMP data</p>

            </div>
        </div>
    </div>

    <div class="card card-default m-2">
        <div class="card-header">
            <input type="hidden" value="0" name="has_radio">
            <label  for="has_radio"> <input id="has_radio" {{ $equipment->has_radio ? 'checked="checked"' : '' }} value="1" name="has_radio" type="checkbox" onClick="radioDetails()" onLdoad="radioDetails()"> Radio Information</label>
            <script>
                function radioDetails() {
                    if ( $('#has_radio').prop('checked')) {

                        $('#radio_details').removeClass('collapse');
                    } else {

                        $('#radio_details').addClass('collapse');
                    }
                }
            </script>
        </div>

        <div id="radio_details" class="card-body row {{ $equipment->has_radio == 1 ? '': 'collapse' }}" style="">


            <div class="col-6">

                    <label class="fw-bold col-form-label" for="name">Antenna Model</label>
                    <input type="text" name="ant_model" class="form-control" value="{{ $equipment->ant_model }}">
                </div>
                <div class="col-6">
                    <label class="fw-bold col-form-label" for="name">Antenna Gain</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="ant_gain" class="form-control"
                               value="{{ $equipment->ant_gain }}">
                        <div class="input-group-addon">dBi</div>
                    </div>
                </div>
            <div class="col-6">
                    <label class="fw-bold col-form-label" for="name">Antenna Height</label>
                    <div class="input-group">
                        <input type="text" name="ant_height" class="form-control" value="{{ $equipment->ant_height }}">
                        <div class="input-group-addon">meters</div>
                    </div>
                    <p class="form-text">Antenna height above ground level at the site</p>

                </div>
            <div class="col-6">
                    <label class="fw-bold col-form-label" for="name">Antenna Azimuth</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="ant_azimuth" class="form-control"
                               value="{{ $equipment->ant_azimuth }}">
                        <div class="input-group-addon">&deg; TRUE</div>
                    </div>
                </div>
            <div class="col-6">
                    <label class="fw-bold col-form-label" for="name">Antenna Down Tilt</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="ant_tilt" class="form-control"
                               value="{{ $equipment->ant_tilt }}">
                        <div class="input-group-addon">&deg;</div>

                    </div>
                    <p class="form-text">Positive values reflect downwards tilt.</p>

                </div>





                <div class="col-6">
                    <label class="fw-bold col-form-label" for="name">Radio Power</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="radio_power" class="form-control"
                               value="{{ $equipment->radio_power }}">
                        <div class="input-group-addon">dBm</div>
                    </div>
                    <p class="form-text">Enter the maximum power the radio is capable of unless limited to another
                        value</p>

                </div>
        </div>
    </div>

    <div class="card card-default m-2">
        <div class="card-header">
            Additional
        </div>

        <div class="card-body row">


            <div class=" col-12"></div>
            <div class=" col-6">
                <label class="fw-bold col-form-label" for="name">Comments</label>
                <textarea rows=5 name="comments" class="form-control">{{ $equipment->comment }}</textarea>
            </div>
            <div class="col-6">
                <label class="fw-bold col-form-label" for="name">Description</label>
                <textarea rows=5 name="description" class="form-control">{{ $equipment->description }}</textarea>
                <p class="form-text">This content may be used to describe the site publically</p>
            </div>
        </div>
    </div>

    <div class="card card-default m-2">
        <div class="card-header">
            Services
        </div>
        <div class="card-body row">

            <div class="form-group col-4">
                <label class="fw-bold col-form-label" for="dhcp_server">DHCP Server</label>
                <select name="dhcp_server" class="form-select" required>
                    <option @if ($equipment->dhcp_server == "0") selected="true" @endif value="0">No</option>
                    <option @if ($equipment->dhcp_server == "1") selected="true" @endif value="1">Yes</option>
                </select>
                <p class="form-text">Does this device run a DHCP Server?</p>
            </div>
{{--            <div class="form-group col-4">--}}
{{--                <label class="fw-bold col-form-label" for="dhcp_server_dns">DHCP Server DNS Updates</label>--}}
{{--                <select name="dhcp_server_dns" class="form-select" required>--}}
{{--                    <option @if ($equipment->dhcp_server_dns == "0") selected="true" @endif value="0">No</option>--}}
{{--                    <option @if ($equipment->dhcp_server_dns == "1") selected="true" @endif value="1">Yes</option>--}}
{{--                </select>--}}
{{--                <p class="form-text">Update DNS records for DHCP addresses assigned by this server?</p>--}}
{{--            </div>--}}
{{--            <div class="form-group col-4">--}}
{{--                <label class="fw-bold col-form-label" for="dhcp_server_dns_ttl">DHCP Server DNS TTL</label>--}}
{{--                <select name="dhcp_server_dns_ttl" class="form-select" required>--}}
{{--                    <option @if ($equipment->dhcp_server_dns_ttl == "60") selected="true" @endif value="60">60 seconds</option>--}}
{{--                    <option @if ($equipment->dhcp_server_dns_ttl == "600") selected="true" @endif value="600">600 seconds</option>--}}
{{--                    <option @if ($equipment->dhcp_server_dns_ttl == "3600") selected="true" @endif value="3600">3600 seconds</option>--}}
{{--                    <option @if ($equipment->dhcp_server_dns_ttl == "7200") selected="true" @endif value="7200">7200 seconds</option>--}}
{{--                    <option @if ($equipment->dhcp_server_dns_ttl == "14400") selected="true" @endif value="14400">14400 seconds</option>--}}
{{--                </select>--}}
{{--                <p class="form-text">Time to live for DHCP DNS records</p>--}}
{{--            </div>--}}

            <div class="form-group col-4">
                <label class="fw-bold col-form-label" for="bwtest_server">Bandwidth Test Server</label>
                <select name="bwtest_server" class="form-select" required>
                    <option value=""></option>
                    <option @if ($equipment->bwtest_server == "") selected="true" @endif value="">No</option>
                    <option @if ($equipment->bwtest_server == "RouterOS") selected="true" @endif value="RouterOS">
                        RouterOS
                    </option>
                </select>
                <p class="form-text">Does this device run a Bandwidth Test Server?</p>
            </div>
            <div class="form-group col-4">
                <label class="fw-bold col-form-label" for="discover_clients">Allow Client Discovery</label>
                <select name="discover_clients" class="form-select" required>
                    <option @if ($equipment->discover_clients == "1") selected="true" @endif value="1">Yes</option>
                    <option @if ($equipment->discover_clients == "0") selected="true" @endif value="0">No</option>
                </select>
                <p class="form-text">Allow the portal to discover clients connected to this device?</p>
            </div>

            <div class="form-group col-4">
                <label class="fw-bold col-form-label" for="remote_serial_port">Remote Serial Port</label>
                <select name="remote_serial_port" class="form-select">
                    <option @if ($equipment->remote_serial_port == "") selected="true" @endif value=""></option>
                    <option @if ($equipment->remote_serial_port == "usb1") selected="true" @endif value="usb1">usb1</option>
                    <option @if ($equipment->remote_serial_port == "usb2") selected="true" @endif value="usb2">usb2</option>
                    <option @if ($equipment->remote_serial_port == "usb3") selected="true" @endif value="usb3">usb3</option>
                    <option @if ($equipment->remote_serial_port == "usb4") selected="true" @endif value="usb4">usb4</option>
                </select>
                <p class="form-text">Does this device expose a remote serial port.</p>
            </div>
        </div>
    </div>
    <div class="col-12 d-flex">

        <div class="m-2 ms-auto">
        <button type="submit" class="btn btn-success">@if( $equipment->id ) Save Equipment @else Create
            Equipment @endif</button>
        <button type="cancel" class="btn btn-danger"> Cancel</button>
    </div>
    </div>
</form>