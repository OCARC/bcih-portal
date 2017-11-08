<form class="" method="POST" action="{{ url("/site") }}">

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $site->id }}">

    <div class="form-group">
        <lable for="name">Site Name</lable>
        <input type="text" name="name" class="form-control" value="{{ $site->name }}">
    </div>
    <div class="form-group">
        <lable for="name">Site Code</lable>
        <input type="text" maxlength="3" name="sitecode" class="form-control" value="{{ $site->sitecode }}">

    </div>


    <div class="form-group">
        <lable for="name">Owner</lable>
        <select name="owner" class="form-control" required >
            <option value="0"></option>
            @foreach( $users as $user)
                <option @if ($site->owner_id == $user->id) selected="true" @endif value="{{ $user->id }}">{{$user->name}} ({{$user->callsign}})</option>
            @endforeach
        </select>
        <p class="help-block">Be careful when assigning ownership. If you assign ownership to someone else you may not be able to access the device anymore.</p>
    </div>

    <div class="form-group">
        <lable for="name">Altitude</lable>
        <div class="input-group">
            <input type="text" name="altitude" class="form-control" value="{{ $site->altitude }}">
            <div class="input-group-addon">meters</div>
        </div>
        <p class="help-block">Ground above sea level</p>

    </div>
    <div class="form-group">
        <lable for="name">Latitude</lable>
        <div class="input-group">
            <input type="number" name="latitude" class="form-control" value="{{ $site->latitude }}">
            <div class="input-group-addon">&deg;</div>
        </div>
    </div>
    <div class="form-group">
        <lable for="name">Longitude</lable>
        <div class="input-group">
            <input type="number" name="longitude" class="form-control" value="{{ $site->longitude }}">
            <div class="input-group-addon">&deg;</div>
        </div>
    </div>
    <div class="form-group">
        <lable for="name">Comments</lable>
            <input type="text" name="comment" class="form-control" value="{{ $site->comment }}">
    </div>

    <button type="submit" class="btn btn-success">@if( $site->id ) Save Equipment @else Create Equipment @endif</button>
    <button type="cancel" class="btn btn-danger"> Cancel </button>
</form>