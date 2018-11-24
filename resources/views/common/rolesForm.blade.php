<div class="panel panel-default">
    <div class="panel-heading">
        Organization
    </div>
    <div class="panel-body">
                <span class="text-info">
                    Members of the following organizations will have access to manage this equipment.</span><br>
        <span class="text-warning">Use caution as removing your organization may make it impossible for you to manage this equipment.</span>
        <div class="form-group">

            @foreach ($roles->sortBy('friendly_name') as $row)
                @if($row->category == 'Organizations')
                    <div class="col-md-3">
                        <div class="checkbox">
                            <label for="role-{{$row->id}}">
                                <input name="roles[]" id="role-{{$row->id}}" value="{{$row->name}}"
                                       @if( $target->hasrole($row->name) )checked="true" @endif  type="checkbox">
                                {{$row->friendly_name or $row->name }} <br>
                                <div class="text-muted">{{$row->description }}</div>
                            </label>
                        </div>
                    </div>
                @endif
            @endforeach

        </div>


    </div>

</div>
