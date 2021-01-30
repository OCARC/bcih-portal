<form class="" method="POST" action="{{ url("/dns-records") }}">

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $record->id }}">


    <div class="panel panel-default">
        <div class="panel-heading">
            Address Information
        </div>
        <div class="panel-body">

            <div class="form-group col-md-6">
                <lable for="name">Descriptive Name</lable>
                <input type="text" name="name" class="form-control" value="{{ $record->name }}">
            </div>


            <div class="form-group col-md-6">
                <lable for="name">Zone</lable>
                <select name="dns_zones_id" class="form-control">
                    <option value=""></option>
                    @foreach( $record->dnsZones() as $zone )

                    <option value="{{$zone->id}}" @if( $record->dns_zones_id == $zone->id ) selected="true" @endif>
                        {{$zone->domain}} ({{$zone->name}})
                    </option>
                    @endforeach


                </select>
            </div>



            <div class="form-group col-md-12">
                <lable for="name">Description</lable>
                <textarea rows=5 name="description" class="form-control">{{ $record->description }}</textarea>
            </div>
            <div class="form-group col-md-6">
                <lable for="name">Time To Live</lable>
                <input type="text" name="ttl" class="form-control" value="{{ $record->ttl or 300 }}">
            </div>

            <div class="form-group col-md-6">
                <lable for="name">Record Type</lable>
                <select name="record_type" class="form-control">
                    <option value=""></option>

                @foreach( $record->recordTypes() as $type => $desc )

                    <option value="{{ $type  }}" @if( $record->record_type == $type) selected="true" @endif >
                        {{ $type }} - {{ $desc }}
                    </option>
                    @endforeach


                </select>
                <p class="help-block">Only <strong>A</strong> and <strong>PTR</strong> are supported and tested, Other types may or may not work.</p>
            </div>

            <div class="form-group col-md-6">
                <lable for="name">Hostname/Key</lable>
                <input type="text" name="hostname" class="form-control" value="{{ $record->hostname }}">
            </div>
            <div class="form-group col-md-6">
                <lable for="name">Target/Value</lable>
                <input type="text" name="target" class="form-control" value="{{ $record->target }}">
            </div>
        </div>
    </div>











    <div class="col-md-12">
        <button type="submit" class="btn btn-success">@if( $record->id ) Save Record @else Create
            Record @endif</button>
        <button type="cancel" class="btn btn-danger"> Cancel</button>
    </div>
</form>