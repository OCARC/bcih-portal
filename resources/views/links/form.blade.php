<form class="" method="POST" action="{{ url("/links") }}">

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $link->id }}">

    <div class=" col-md-12">

    <div class="panel panel-default">
        <div class="panel-heading">
            Link Information
        </div>
        <div class="panel-body">

            <div class="form-group col-md-4">
                <lable for="name">Link Name</lable>
                <input type="text" name="name" class="form-control" value="{{ $link->name }}">
            </div>

            <div class="form-group col-md-4">
                <lable for="name">Link Status</lable>
                <select name="status" class="form-control" >
                    <option value=""></option>
                    <option @if( $link->status == "Potential") selected="true" @endif style="background-color: #e1e1e1">
                        Potential
                    </option>
                    <option @if( $link->status == "Planning") selected="true" @endif style="background-color: #fff6a6">
                        Planning
                    </option>
                    <option @if( $link->status == "Installed") selected="true" @endif style="background-color: #aaffaa">
                        Installed
                    </option>
                    <option @if( $link->status == "Equip Failed") selected="true"
                            @endif style="background-color: #ff6666">
                        Equip Failed
                    </option>
                    <option @if( $link->status == "Problems") selected="true" @endif style="background-color: #ffd355">
                        Problems
                    </option>
                    <option @if( $link->status == "No Install") selected="true" @endif style="background-color: #979797"
                            value="No Install">No Install - Equipment will never be installed
                    </option>

                </select>
            </div>

        </div>
    </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                Access Point
            </div>
            <div class="panel-body">

                <div class="form-group col-md-4">
                    <lable for="name">AP Site</lable>
                    <select name="ap_site_id" class="form-control"  style="font-family: courier">
                        <option value="">- not set -</option>

                    @foreach( $sites as $site)
                            <option @if ($link->ap_site_id == $site->id) selected="true"
                                    @endif value="{{ $site->id }}">{{$site->sitecode}} - {{$site->name}}
                            </option>
                        @endforeach
                    </select></div>

                <div class="form-group col-md-4">
                    <lable for="name">AP Equipment</lable>
                    <select name="ap_equipment_id" class="form-control" >
                        <option value="">- not set -</option>
                        @foreach( $equipments as $equipment)
                            <option @if ($link->ap_equipment_id == $equipment->id) selected="true"
                                    @endif value="{{ $equipment->id }}">{{$equipment->hostname}}
                            </option>
                        @endforeach

                    </select></div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                Client Point
            </div>
            <div class="panel-body">


                <div class="form-group col-md-4">
                    <lable for="name">AP Site</lable>
                    <select name="cl_site_id" class="form-control" style="font-family: courier">
                        <option value="">- not set -</option>

                        @foreach( $sites as $site)
                            <option @if ($link->cl_site_id == $site->id) selected="true"
                                    @endif value="{{ $site->id }}">{{$site->sitecode}} - {{$site->name}}
                            </option>
                        @endforeach
                    </select></div>
                <div class="form-group col-md-4">
                    <lable for="name">Client Equipment</lable>
                        <select name="cl_equipment_id" class="form-control" >
                            <option value="">- not set -</option>
                            @foreach( $equipments as $equipment)
                                <option @if ($link->cl_equipment_id == $equipment->id) selected="true"
                                        @endif value="{{ $equipment->id }}">{{$equipment->hostname}}
                                </option>
                            @endforeach

                    </select></div>
            </div>
        </div>

        @include('common.rolesForm', ['target' => $link]);


    </div>

    <button type="submit" class="btn btn-success">@if( $link->id ) Save Site @else Create Site @endif</button>
    <button type="cancel" class="btn btn-danger"> Cancel</button>
</form>