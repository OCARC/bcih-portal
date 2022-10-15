<div class="card card-default m-2">
    <div class="card-header">
        Organization
    </div>
    <div class="card-body">
                <span class="text-info">
                    Members of the following organizations will have access to manage this equipment.</span><br>
        <span class="text-warning">Use caution as removing your organization may make it impossible for you to manage this equipment.</span>
        <div class="row">

            @foreach ($roles->sortBy('friendly_name') as $row)
                @if($row->category == 'Organizations')
                    <div class="col-3">
                        <div class="checkbox">
                            <label for="role-{{$row->id}}">
                                <input name="roles[]" id="role-{{$row->id}}" value="{{$row->name}}"
                                       @if( $target->hasrole($row->name) )checked="true" @endif  type="checkbox">
                                {{$row->getFriendlyName() }} <br>
                                <div class="text-muted">{{$row->description }}</div>
                            </label>
                        </div>
                    </div>
                @endif
            @endforeach

        </div>


    </div>

</div>
