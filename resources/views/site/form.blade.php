<form class="" method="POST" action="{{ url("/site") }}">

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $site->id }}">

    <div class="form-group">
        <lable for="name">Site Name</lable>
        <input type="text" name="name" class="form-control" value="{{ $site->name }}">
    </div>
    <div class="row">
    <div class="form-group col-md-4">
        <lable for="name">Site Code</lable>
        <input type="text" maxlength="3" name="sitecode" class="form-control" value="{{ $site->sitecode }}">

    </div>


    <div class="form-group col-md-4">
        <lable for="name">Status</lable>
        <select name="status" class="form-control" required>
            <option value=""></option>
            <option @if( $site->status == "Potential") selected="true" @endif style="background-color: #e1e1e1">
                Potential
            </option>
            <option @if( $site->status == "Planning") selected="true" @endif style="background-color: #fff6a6">
                Planning
            </option>
            <option @if( $site->status == "Installed") selected="true" @endif style="background-color: #aaffaa">
                Installed
            </option>
            <option @if( $site->status == "Equip Failed") selected="true" @endif style="background-color: #ff6666">
                Equip Failed
            </option>
            <option @if( $site->status == "Problems") selected="true" @endif style="background-color: #ffd355">
                Problems
            </option>
            <option @if( $site->status == "No Install") selected="true" @endif style="background-color: #979797"
                    value="No Install">No Install - Equipment will never be installed
            </option>

        </select></div>

    <div class="form-group col-md-4">
        <lable for="name">Owner</lable>
        <select name="owner" class="form-control" required>
            <option value="0"></option>
            @foreach( $users as $user)
                <option @if ($site->owner_id == $user->id) selected="true"
                        @endif value="{{ $user->id }}">{{$user->name}} ({{$user->callsign}})
                </option>
            @endforeach
        </select>
        <p class="help-block">Be careful when assigning ownership. If you assign ownership to someone else you may not
            be able to access this site anymore.</p>
    </div>
    </div>

    <div class="row">
        <div class="form-group col-md-4">
            <lable for="name">Altitude</lable>
            <div class="input-group">
                <input type="text" name="altitude" class="form-control" value="{{ $site->altitude }}">
                <div class="input-group-addon">meters</div>
            </div>
            <p class="help-block">Ground above sea level</p>

        </div>
        <div class="form-group col-md-4">
            <lable for="name">Latitude</lable>
            <div class="input-group">
                <input type="number" step="0.000001" name="latitude" class="form-control" value="{{ $site->latitude }}">
                <div class="input-group-addon">&deg;</div>
            </div>
        </div>
        <div class="form-group col-md-4">
            <lable for="name">Longitude</lable>
            <div class="input-group">
                <input type="number" step="0.000001"name="longitude" class="form-control" value="{{ $site->longitude }}">
                <div class="input-group-addon">&deg;</div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="form-group col-md-6">
            <lable for="name">Comments</lable>
            <textarea rows=5  name="comment" class="form-control">{{ $site->comment }}</textarea>
        </div>
        <div class="form-group col-md-6">
            <lable for="name">Description</lable>
            <textarea rows=5 name="description" class="form-control">{{ $site->description }}</textarea>
            <p class="help-block">This content may be used to describe the site publically</p>
        </div>
    </div>
    <button type="submit" class="btn btn-success">@if( $site->id ) Save Equipment @else Create Equipment @endif</button>
    <button type="cancel" class="btn btn-danger"> Cancel</button>
</form>