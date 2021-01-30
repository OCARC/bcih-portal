<form class="" method="POST" action="{{ url("/dns-zones") }}">

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
                <lable for="name">Status</lable>
                <select name="status" class="form-control">
                    <option value=""></option>
                    <option @if( $record->status == "Subdivided") selected="true" @endif style="background-color: #e1e1e1">
                        Subdivided
                    </option>
                    <option @if( $record->status == "Planning") selected="true" @endif style="background-color: #fff6a6">
                        Planning
                    </option>
                    <option @if( $record->status == "In Use") selected="true" @endif style="background-color: #aaffaa">
                        In Use
                    </option>
                    <option @if( $record->status == "Do Not Use") selected="true" @endif style="background-color: #ff6666">
                        Do Not Use
                    </option>
                    <option @if( $record->status == "Routing Problem") selected="true" @endif style="background-color: #ffd355">
                        Routing Problem
                    </option>
                    <option @if( $record->status == "Placeholder") selected="true" @endif style="background-color: #979797"
                            value="Placeholder">Placeholder
                    </option>

                </select>
            </div>



            <div class="form-group col-md-12">
                <lable for="name">Description</lable>
                <textarea rows=5 name="description" class="form-control">{{ $record->description }}</textarea>
            </div>

        </div>
    </div>


    <div class="panel panel-default">

        <div class="panel-heading">
            Zone Information
        </div>
        <div class="panel-body">
            <div class="form-group col-md-6">
                <lable for="name">Domain</lable>
                <input type="text" name="domain" class="form-control" value="{{ $record->domain }}">
            </div>


            <div class="form-group col-md-6">
                <lable for="name">Server</lable>
                <input type="text" name="server" class="form-control" value="{{ $record->server }}">
            </div>


            <div class="form-group col-md-12">
                <lable for="name">DNS Key</lable>
                <textarea rows=5 name="dns_key" class="form-control">{{ $record->dns_key }}</textarea>
            </div>
        </div>
    </div>











    <div class="col-md-12">
        <button type="submit" class="btn btn-success">@if( $record->id ) Save Zone @else Create
            Zone @endif</button>
        <button type="cancel" class="btn btn-danger"> Cancel</button>
    </div>
</form>