<form class="" method="POST" action="{{ url("/site") }}">

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $site->id }}">

    <div class=" col-md-12">
        <div class="panel panel-default">
        <div class="panel-heading">
            Site Information
        </div>
        <div class="panel-body">

            <div class="form-group col-md-12">
                <lable for="name">Site Name</lable>
                <input type="text" name="name" class="form-control" value="{{ $site->name }}">
            </div>
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
                    <option @if( $site->status == "Equip Failed") selected="true"
                            @endif style="background-color: #ff6666">
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
                <select name="user_id" class="form-control" required>
                    <option value="0"></option>
                    @foreach( $users as $user)
                        <option @if ($site->user_id == $user->id) selected="true"
                                @endif value="{{ $user->id }}">{{$user->name}} ({{$user->callsign}})
                        </option>
                    @endforeach
                </select>
                <p class="help-block">This ownership field has been depreciated. Use Organizations instead.</p>
            </div>
        </div>
    </div>
    </div>
    <div class=" col-md-12">
        <div class="panel panel-default">
        <div class="panel-heading">
            Site Location
        </div>
        <div class="panel-body">

            <div class="form-group col-md-3">
                <lable for="name">Altitude</lable>
                <div class="input-group">
                    <input type="text" name="altitude" class="form-control" value="{{ $site->altitude }}">
                    <div class="input-group-addon">meters</div>
                </div>
                <p class="help-block">Ground above sea level</p>

            </div>
            <div class="form-group col-md-3">
                <lable for="name">Latitude</lable>
                <div class="input-group">
                    <input type="number" step="0.000001" name="latitude" class="form-control"
                           value="{{ $site->latitude }}">
                    <div class="input-group-addon">&deg;</div>
                </div>
            </div>
            <div class="form-group col-md-3">
                <lable for="name">Longitude</lable>
                <div class="input-group">
                    <input type="number" step="0.000001" name="longitude" class="form-control"
                           value="{{ $site->longitude }}">
                    <div class="input-group-addon">&deg;</div>
                </div>
            </div>

            <div class="form-group col-md-3">
                <lable for="map_visible">Map Visibility</lable>
                <select name="map_visible" class="form-control">
                    <option value=""></option>
                    <option value="yes" @if( $site->map_visible == "yes") selected="true" @endif>
                        Yes (public)
                    </option>
                    <option value="hide" @if( $site->map_visible == "hide") selected="true" @endif>
                        Hide
                    </option>


                </select></div>

        </div>

    </div>
    </div>
    <div class=" col-md-12">

    @include('common.rolesForm', ['target' => $site])
    </div>

    <div class=" col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Notations
            </div>
            <div class="panel-body">
                <div class="form-group col-md-4">
                    <lable for="comments">Comments</lable>
                    <textarea rows=5 name="comments" class="form-control">{{ $site->comments }}</textarea>
                </div>
                <div class="form-group col-md-4">
                    <lable for="name">Description</lable>
                    <textarea rows=5 name="description" class="form-control">{{ $site->description }}</textarea>
                    <p class="help-block">This content may be used to describe the site publicly</p>
                </div>


                <div class="form-group col-md-4">
                    <lable for="name">Access Notes</lable>
                    <textarea rows=5 name="access_note" class="form-control">{{ $site->access_note }}</textarea>
                </div>

            </div>
        </div>
    </div>


    <button type="submit" class="btn btn-success">@if( $site->id ) Save Site @else Create Site @endif</button>
    <button type="cancel" class="btn btn-danger"> Cancel</button>
</form>