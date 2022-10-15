<form class="" method="POST" action="{{ url("/client") }}">

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $client->id }}">

    <div class="card card-default">
        <div class="card-header">
            Device Information
        </div>
        <div class="card-body">

            <div class="form-group col-md-6">
                <label for="name">Owner</label>
                <select name="user_id" class="form-select" required>
                    <option value="0"></option>
                    @foreach( $users as $user)
                        <option @if ($client->user_id == $user->id) selected="true"
                                @endif value="{{ $user->id }}">{{$user->name}} ({{$user->callsign}})
                        </option>
                    @endforeach
                </select>
                <p class="form-text">Be careful when assigning ownership. If you assign ownership to someone else
                    you may
                    not be able to access the device anymore.</p>

            </div>

            <div class="form-group col-md-6">
                <label for="name">Client Type</label>
                <select name="type" class="form-select" required>
                    <option value="0"></option>
                    @foreach( ['client','link'] as $type)
                        <option @if ($client->type == $type) selected="true"
                                @endif value="{{ $type }}">{{$type}}
                        </option>
                    @endforeach
                </select>
                <p class="form-text">Only change this to 'link' for radios that are part of a point to point link.</p>

            </div>
        </div>
    </div>



    <div class="card card-default">
        <div class="card-header">
            Device Privacy
        </div>
        <div class="card-body">

            <div class="form-group col-md-6">
                <label for="coordinate_privacy">Location</label>
                <select name="coordinate_privacy" class="form-select" required>
                    <option @if ($client->coordinate_privacy == "public") selected="true" @endif value="public">Public</option>
                    <option @if ($client->coordinate_privacy == "users") selected="true" @endif value="users">Logged In Users
                    <option @if ($client->coordinate_privacy == "hidden") selected="true" @endif value="hidden">Hidden</option>
                    <option @if ($client->coordinate_privacy == "hidden_site") selected="true" @endif value="hidden_site">Hide Site Equipment</option>

                </select>
            </div>

            {{--<div class="form-group col-md-6">--}}
                {{--<label for="privacy_status">Status</label>--}}
                {{--<select name="privacy_status" class="form-select" required>--}}
                    {{--<option @if ($client->privacy_status == "public") selected="true" @endif value="public">Public</option>--}}
                    {{--<option @if ($client->privacy_status == "users") selected="true" @endif value="users">Logged In Users--}}
                    {{--<option @if ($client->privacy_status == "hidden") selected="true" @endif value="hidden">Hidden</option>--}}
                {{--</select>--}}
            {{--</div>--}}

            <div class="form-group col-md-12">
            <p class="form-text">Note: Even when set to hidden users with network operator privileges will be able to see the location and status information reported by your device.</p>
            </div>


        </div>
    </div>


    <div class="card card-default">
        <div class="card-header">
            Device Management
        </div>
        <div class="card-body">

            <div class="form-group col-md-12">
                <label for="name">Management IP</label>
                <input type="text" name="management_ip" class="form-control" value="{{ $client->management_ip }}">
                <span class="form-text">
                    Leave blank to auto detect
                </span>
            </div>


            <div class="form-group col-md-6">
                <label for="software_updates">Software Updates</label>
                <select name="software_updates" class="form-select" required>
                    <option @if ($client->software_updates == "manual") selected="true" @endif value="manual">Manual</option>
                    <option @if ($client->software_updates == "netops") selected="true" @endif value="netops">Netops</option>
                    <option @if ($client->software_updates == "auto") selected="true" @endif value="auto">Auto</option>

                </select>
                <span class="form-text">

                    Performing software updates in a timely manor is crucial to ensuring your networking and equipment remains secure. Equipment that has not been adequately updated may be blocked from joining the network or face other restrictions.
                    </span>
    <span class="help-block alert-warning alert">
        This setting is experimental and users should still be checking that there equipment is receiving updates regardless of this setting.
    </span>
            </div>
            <span class="form-text">

            <div class="form-group col-md-6">
                <ul>
                <li><strong>Manual</strong> - If you set updates to manual <strong style="color:red">YOU</strong> are responsible for updating the software on your device.</li>
                <li><strong>Auto/Netops</strong> - If you set updates to Auto/Network Operators you are authorizing updates to be applied to your hardware by network operators
                    and/or automatically by the portal software. These updates will have had general testing but could possibly break your specific network configuration.<br> <em style="color:red">Netops are not responsible in the unlikely event that your client goes offline or is damaged by an update.</em></li>
                </ul>
            </div>
            </span>
        </div>
    </div>

    <div class="card card-default">
        <div class="card-header">
            Location
        </div>
        <div class="card-body">

            <div class="form-group col-md-6">
                <label for="coordinate_source">Location</label>
                <select name="coordinate_source" class="form-select" required>
                    <option value="none">None</option>
                    <option @if ($client->coordinate_source == "snmp") selected="true" @endif value="snmp">Query Radio (SNMP, Preferred)</option>
                    <option @if ($client->coordinate_source == "aprs") selected="true" @endif  value="aprs">Query APRS (not working)
                    <option @if ($client->coordinate_source == "manual") selected="true" @endif  value="manual">Manual</option>

                </select>
            </div>

            <div class="form-group col-md-6">
                <p class="form-text">
                    <strong>Query Radio</strong> - Query the radio for location information provided during setup<br>
                    <strong>Query APRS</strong> - Intended for mobile stations (doesn't work yet)<br>
                    <strong>Manual</strong> - Set in the portal<br>
                </p>

            </div>

            <div class="form-group col-md-4">
                <label for="name">Latitude</label>
                <div class="input-group">
                    <input type="number" step="0.000001" name="latitude" class="form-control" value="{{ $client->latitude }}">
                    <div class="input-group-addon">&deg;</div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="name">Longitude</label>
                <div class="input-group">
                    <input type="number" step="0.000001"name="longitude" class="form-control" value="{{ $client->longitude }}">
                    <div class="input-group-addon">&deg;</div>
                </div>
            </div>

            <div class="form-group col-md-12">
                <p class="form-text">Note: Even when set to hidden users with network operator privileges will be able to see the location and status information reported by your device.</p>
            </div>


        </div>
    </div>
    <div class="col-md-12">
        <button type="submit" class="btn btn-success">@if( $client->id ) Save Equipment @else Create
            Equipment @endif</button>
        <button type="cancel" class="btn btn-danger"> Cancel</button>
    </div>
</form>