


<form class="form-horizontal" method="POST" action="{{ url( "/roles/" . $role->id) . "/perms" }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $role->id }}">

    <fieldset>

        <!-- Form Name -->
        <legend>Permissions</legend>

    @php($cur_cat = "first run")
    <!-- Multiple Checkboxes -->
        <div class="form-group">

            @foreach ($permissions->sortBy('category') as $row)
                @if( $cur_cat != $row->category)
                    @if( $cur_cat != "first run") </div>                             </div>
        @endif
        @php($cur_cat = $row->category)

        <div class="col-md-3">
            <h4>{{ $cur_cat ?? "Uncategorized" }}</h4>
            <div>
                @endif
                <div class="checkbox">
                    <label for="permission-{{$row->id}}" >
                        <input name="permissions[]" id="permission-{{$row->id}}" value="{{$row->name}}" @if( ! Auth::user()->can('permissions.user_change') )disabled="true"@endif @if( $role->hasPermissionTo($row->name) )checked="true"@endif type="checkbox">
                        {{ $row->friendly_name ?? $row->name }}<br>
                        <span class="text-muted">{{$row->description }}</span>
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        </div>




        <div class="form-group">
            <label class="col-md-4 control-label" for="singlebutton"></label>
            <div class="col-md-4">
                @if( ! Auth::user()->can('permissions.role_change') )
                    <p class="text-center">You do not have the required permissions to change this users permissions</p>
                @else
                    <button id="singlebutton" name="singlebutton" class="btn btn-primary">Save Permissions</button>
                @endif
            </div>
        </div>

    </fieldset>
</form>

