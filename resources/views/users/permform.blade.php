<form class="form-horizontal" method="POST" action="{{ url( "/users/" . $user->id) . "/perms" }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $user->id }}">

    <fieldset>

        <!-- Form Name -->
        <legend>Permissions</legend>


    @php($cur_cat = "first run")
    <!-- Multiple Checkboxes -->
        <div class="form-group row">

            @foreach ($permissions->sortBy('category') as $row)
                @if( $cur_cat != $row->category)
                    @if( $cur_cat != "first run") </div>
        </div>
        @endif
        @php($cur_cat = $row->category)

        <div class="col-3">
            <h4>{{ $cur_cat ?? "Uncategorized" }}</h4>
            <div>
                @endif
                <div class="checkbox">
                    <label for="permission-{{$row->id}}">
                        @php($inherited = false )

                        @if( ! $user->hasDirectPermission( $row->name ) )
                            @if( $user->can($row->name) )
                                @php($inherited = true )
                            @endif
                        @endif
                        <input name="permissions[]" id="permission-{{$row->id}}" value="{{$row->name}}"
                               @if( ! Auth::user()->can('permissions.user_change') )disabled="true" @endif
                               @if( $inherited )disabled="true" checked="true" @endif
                               @if( $user->can($row->name) )checked="true" @endif
                               type="checkbox">
                        {{ $row->friendly_name ?? $row->name }} @if( $inherited )<span
                                class="text-success">(✔ Inherited)</span>@endif<br>
                        <span class="text-muted">{{$row->description }}</span>


                    </label>
                </div>
                @endforeach
            </div>

        </div>
<div style="clear: both"></div>
        <div class="form-group row">
            <label class="col-4 control-label" for="singlebutton"></label>
            <div class="col-4 text-center">
                @if( ! Auth::user()->can('permissions.user_change') )
                    <p class="text-center">You do not have the required permissions to change this users permissions</p>
                @else
                    <button id="singlebutton" name="singlebutton" class="btn btn-primary">Save Permissions</button>
                @endif
            </div>
        </div>

    </fieldset>
</form>


<form class="form-horizontal" method="POST" action="{{ url( "/users/" . $user->id) . "/roles" }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $user->id }}">

    <fieldset>

        <!-- Form Name -->
        <legend>Roles</legend>

    @php($cur_cat = "first run")
    <!-- Multiple Checkboxes -->
        <div class="form-group row">

            @foreach ($roles->sortBy('category') as $row)
                @if( $cur_cat != $row->category)
                    @if( $cur_cat != "first run") </div>
        </div>
        @endif
        @php($cur_cat = $row->category)

        <div class="col-3">
            <h4>{{ $cur_cat ?? "Uncategorized" }}</h4>
            <div>
                @endif
                <div class="checkbox">
                    <label for="roles-{{$row->id}}">
                        <input name="roles[]" id="roles-{{$row->id}}" value="{{$row->name}}"
                               @if( ! Auth::user()->can('roles.user_change') )disabled="true"
                               @endif  @if( $user->hasrole($row->name) )checked="true" @endif type="checkbox">
                        {{ $row->friendly_name ?? $row->name }}<br>
                        <span class="text-muted">{{$row->description }}</span>
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        </div>


        <div class="form-group row">
            <label class="col-4 control-label" for="singlebutton"></label>
            <div class="col-4">
                @if( ! Auth::user()->can('roles.user_change') )
                    <p class="text-center">You do not have the required permissions to change this users roles</p>
                @else
                    <button id="singlebutton" name="singlebutton" class="btn btn-primary">Save Roles</button>
                @endif
            </div>
        </div>

    </fieldset>
</form>
