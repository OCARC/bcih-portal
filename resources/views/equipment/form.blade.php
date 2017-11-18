<form class="" method="POST" action="{{ url("/equipment") }}">

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $equipment->id }}">


    <div class="form-group col-md-6">
        <lable for="name">Hostname</lable>
        <input type="text" name="hostname" class="form-control" value="{{ $equipment->hostname }}">
    </div>
    <div class="form-group col-md-6">
        <lable for="name">Management IP</lable>
        <input type="text" name="management_ip" class="form-control" value="{{ $equipment->management_ip }}">

    </div>
    <div class="form-group col-md-6">
        <lable for="name">Operating System</lable>
        <select name="os" class="form-control" required>
            <option value=""></option>
            <option @if ($equipment->os == "other") selected="true" @endif v value="other">Other</option>
            <option @if ($equipment->os == "RouterOS") selected="true" @endif v value="RouterOS">RouterOS</option>

        </select>
        <p class="help-block"></p>
    </div>

    <div class="form-group col-md-6">
        <lable for="name">Status</lable>
        <select name="status" class="form-control" required>
            <option value=""></option>
            <option @if( $equipment->status == "Potential") selected="true" @endif style="background-color: #e1e1e1">
                Potential
            </option>
            <option @if( $equipment->status == "Planning") selected="true" @endif style="background-color: #fff6a6">
                Planning
            </option>
            <option @if( $equipment->status == "Installed") selected="true" @endif style="background-color: #aaffaa">
                Installed
            </option>
            <option @if( $equipment->status == "Equip Failed") selected="true" @endif style="background-color: #ff6666">
                Equip Failed
            </option>
            <option @if( $equipment->status == "Problems") selected="true" @endif style="background-color: #ffd355">
                Problems
            </option>
            <option @if( $equipment->status == "No Install") selected="true" @endif style="background-color: #979797"
                    value="No Install">No Install - Equipment will never be installed
            </option>

        </select></div>

    <div class="form-group col-md-6">
        <lable for="name">Cacti Mapping</lable>
        <select name="cacti_id" class="form-control" required>
            <option value="0"></option>
            @foreach( $cactiHosts as $cactiHost)
                <option @if ($equipment->cacti_id == $cactiHost->id) selected="true"
                        @endif value="{{ $cactiHost->id }}">{{$cactiHost->description}} ({{$cactiHost->hostname}})
                </option>
            @endforeach
        </select></div>


    <div class="col-md-6">

        <div class="form-group">
            <lable for="name">Site</lable>
            <select name="site_id" class="form-control" required>
                @foreach( $sites as $site)
                    <option @if ($equipment->site_id == $site->id) selected="true"
                            @endif value="{{ $site->id }}">{{$site->name}} ({{$site->sitecode}})
                    </option>
                @endforeach
            </select></div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <lable for="name">Owner</lable>
            <select name="owner_id" class="form-control" required>
                <option value="0"></option>
                @foreach( $users as $user)
                    <option @if ($equipment->owner_id == $user->id) selected="true"
                            @endif value="{{ $user->id }}">{{$user->name}} ({{$user->callsign}})
                    </option>
                @endforeach
            </select>
            <p class="help-block">Be careful when assigning ownership. If you assign ownership to someone else you may
                not be able to access the device anymore.</p>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <lable for="name">Antenna Model</lable>
            <input type="text" name="ant_model" class="form-control" value="{{ $equipment->ant_model }}">
        </div>
        <div class="form-group">
            <lable for="name">Antenna Gain</lable>
            <div class="input-group">
                <input type="number" step="0.01" name="ant_gain" class="form-control"
                       value="{{ $equipment->ant_gain }}">
                <div class="input-group-addon">dBi</div>
            </div>
        </div>
        <div class="form-group">
            <lable for="name">Antenna Height</lable>
            <div class="input-group">
                <input type="text" name="ant_height" class="form-control" value="{{ $equipment->ant_height }}">
                <div class="input-group-addon">meters</div>
            </div>
            <p class="help-block">Antenna height above ground level at the site</p>

        </div>
        <div class="form-group">
            <lable for="name">Antenna Azimuth</lable>
            <div class="input-group">
                <input type="number" step="0.01" name="ant_azimuth" class="form-control"
                       value="{{ $equipment->ant_azimuth }}">
                <div class="input-group-addon">&deg; TRUE</div>
            </div>
        </div>
        <div class="form-group">
            <lable for="name">Antenna Tilt</lable>
            <div class="input-group">
                <input type="number" step="0.01" name="ant_tilt" class="form-control"
                       value="{{ $equipment->ant_tilt }}">
                <div class="input-group-addon">&deg; </div>
            </div>
        </div>


    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <lable for="name">Radio / Equipment Model</lable>
            <input type="text" name="radio_model" class="form-control" value="{{ $equipment->radio_model }}">
            <p class="help-block">In most cases the portal will <strong>automatically populate</strong> this field from SNMP data</p>

        </div>

        <div class="form-group col-md-6">
            <lable for="name">Radio Power</lable>
            <div class="input-group">
                <input type="number" step="0.01" name="radio_power" class="form-control"
                       value="{{ $equipment->radio_power }}">
                <div class="input-group-addon">dBm</div>
            </div>
            <p class="help-block">Enter the maximum power the radio is capable of unless limited to another value</p>

        </div>
    </div>


        <div class="form-group col-md-6">
            <lable for="name">Comments</lable>
            <textarea rows=5  name="comments" class="form-control">{{ $equipment->comment }}</textarea>
        </div>
        <div class="form-group col-md-6">
            <lable for="name">Description</lable>
            <textarea rows=5 name="description" class="form-control">{{ $equipment->description }}</textarea>
            <p class="help-block">This content may be used to describe the site publically</p>
        </div>

    <div class="col-md-12">
        <button type="submit" class="btn btn-success">@if( $equipment->id ) Save Equipment @else Create
            Equipment @endif</button>
        <button type="cancel" class="btn btn-danger"> Cancel</button>
    </div>
</form>