<form class="" method="POST" action="{{ url("/equipment") }}">

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $equipment->id }}">

    <div class="form-group">
        <lable for="name">Hostname</lable>
        <input type="text" name="hostname" class="form-control" value="{{ $equipment->hostname }}">
    </div>
    <div class="form-group">
        <lable for="name">Management IP</lable>
        <input type="text" name="management_ip" class="form-control" value="{{ $equipment->management_ip }}">

    </div>
    <div class="form-group">
        <lable for="name">Operating System</lable>
        <select name="os" class="form-control" required >
            <option value=""></option>
            <option @if ($equipment->os == "other") selected="true" @endif v value="other">Other</option>
            <option @if ($equipment->os == "RouterOS") selected="true" @endif v value="RouterOS">RouterOS</option>

        </select>
        <p class="help-block">Be careful when assigning ownership. If you assign ownership to someone else you may not be able to access the device anymore.</p>
    </div>

    <div class="form-group">
        <lable for="name">Cacti Mapping</lable>
        <select name="cacti_id" class="form-control" required >
            <option value="0"></option>
            @foreach( $cactiHosts as $cactiHost)
                <option @if ($equipment->cacti_id == $cactiHost->id) selected="true" @endif value="{{ $cactiHost->id }}">{{$cactiHost->description}} ({{$cactiHost->hostname}})</option>
            @endforeach
        </select>    </div>

    <div class="form-group">
        <lable for="name">Site</lable>
        <select name="site_id" class="form-control" required >
            @foreach( $sites as $site)
                <option @if ($equipment->site_id == $site->id) selected="true" @endif value="{{ $site->id }}">{{$site->name}} ({{$site->sitecode}})</option>
            @endforeach
        </select>    </div>



    <div class="form-group">
        <lable for="name">Owner</lable>
        <select name="owner_id" class="form-control" required >
            <option value="0"></option>
            @foreach( $users as $user)
                <option @if ($equipment->owner_id == $user->id) selected="true" @endif value="{{ $user->id }}">{{$user->name}} ({{$user->callsign}})</option>
            @endforeach
        </select>
        <p class="help-block">Be careful when assigning ownership. If you assign ownership to someone else you may not be able to access the device anymore.</p>
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
            <input type="number" name="ant_azimuth" class="form-control" value="{{ $equipment->ant_azimuth }}">
            <div class="input-group-addon">&deg;</div>
        </div>
    </div>
    <div class="form-group">
        <lable for="name">Antenna Tilt</lable>
        <div class="input-group">
            <input type="number" name="ant_tilt" class="form-control" value="{{ $equipment->ant_tilt }}">
            <div class="input-group-addon">&deg;</div>
        </div>
    </div>

    {{--<div class="form-group">--}}
        {{--<lable for="name">Antenna Model</lable>--}}
        {{--<input type="text" name="ant_model" class="form-control" value="{{ $equipment->ant_model }}">--}}
    {{--</div>--}}
    <button type="submit" class="btn btn-success">@if( $equipment->id ) Save Equipment @else Create Equipment @endif</button>
    <button type="cancel" class="btn btn-danger"> Cancel </button>
</form>