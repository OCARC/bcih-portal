<form class="" method="POST" action="{{ url("/ips") }}">

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $ip->id }}">


    <div class="panel panel-default">
        <div class="panel-heading">
            Address Information
        </div>
        <div class="panel-body">

            <div class="form-group col-md-6">
                <lable for="name">Descriptive Name</lable>
                <input type="text" name="name" class="form-control" value="{{ $ip->name }}">
            </div>


            <div class="form-group col-md-6">
                <lable for="name">Status</lable>
                <select name="status" class="form-control">
                    <option value=""></option>
                    <option @if( $ip->status == "Subdivided") selected="true" @endif style="background-color: #e1e1e1">
                        Subdivided
                    </option>
                    <option @if( $ip->status == "Planning") selected="true" @endif style="background-color: #fff6a6">
                        Planning
                    </option>
                    <option @if( $ip->status == "In Use") selected="true" @endif style="background-color: #aaffaa">
                        In Use
                    </option>
                    <option @if( $ip->status == "Do Not Use") selected="true" @endif style="background-color: #ff6666">
                        Do Not Use
                    </option>
                    <option @if( $ip->status == "Routing Problem") selected="true" @endif style="background-color: #ffd355">
                        Routing Problem
                    </option>
                    <option @if( $ip->status == "Placeholder") selected="true" @endif style="background-color: #979797"
                            value="Placeholder">Placeholder
                    </option>

                </select></div>

            <div class="form-group col-md-6">

                    <lable for="name">Site</lable>
                    <select name="site_id" class="form-control" required>
                        @foreach( $sites as $site)
                            <option @if ($ip->site_id == $site->id) selected="true"
                                    @endif value="{{ $site->id }}">{{$site->name}} ({{$site->sitecode}})
                            </option>
                        @endforeach
                    </select>
            </div>

            <div class="form-group col-md-6">
                    <lable for="name">Owner</lable>
                    <select name="user_id" class="form-control" required>
                        <option value="0"></option>
                        @foreach( $users as $user)
                            <option @if ($ip->user_id == $user->id) selected="true"
                                    @endif value="{{ $user->id }}">{{$user->name}} ({{$user->callsign}})
                            </option>
                        @endforeach
                    </select>
                    <p class="help-block">Be careful when assigning ownership. If you assign ownership to someone else you may
                        not be able to access the ip anymore.</p>

            </div>

            <div class="form-group col-md-6">
                <lable for="mac_address">MAC Address</lable>
                <input type="text" name="mac_address" class="form-control" value="{{ implode(":",str_split(strtoupper($ip->mac_address),2)) }}">

            </div>

            <div class="form-group col-md-6">

                <lable for="name">Equipment</lable>
                <select name="equipment_id" class="form-control">
                    <option value=""></option>
                    @foreach( $equipments as $equipment)
                        <option @if ($ip->equipment_id == $equipment->id) selected="true"
                                @endif value="{{ $equipment->id }}">{{$equipment->hostname}}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="form-group col-md-6">
                <lable for="name">Comments</lable>
                <textarea rows=5  name="comment" class="form-control">{{ $ip->comment }}</textarea>
            </div>
            <div class="form-group col-md-6">
                <lable for="name">Description</lable>
                <textarea rows=5 name="description" class="form-control">{{ $ip->description }}</textarea>
                <p class="help-block">This content may be used to describe the site publicly</p>
            </div>

        </div>
    </div>


    <div class="panel panel-default">

        <div class="panel-heading">
            Network Information
        </div>
        <div class="panel-body">
            <div class="form-group col-md-6">
                <lable for="name">IP Address</lable>
                <input type="text" name="ip" class="form-control" value="{{ $ip->ip }}">
            </div>


        <div class="form-group col-md-6">
            <lable for="name">Gateway</lable>
            <input type="text" name="gateway" class="form-control" value="{{ $ip->gateway }}">
        </div>

        <div class="form-group col-md-6">
            <lable for="name">Netmask</lable>
            <input type="text" name="netmask" class="form-control" value="{{ $ip->netmask }}">
        </div>

        <div class="form-group col-md-6">
            <lable for="name">DHCP Server</lable>
            <input type="text" name="dhcp" class="form-control" value="{{ $ip->dhcp }}">
        </div>
        </div>
    </div>

    <div class="panel panel-default">

        <div class="panel-heading">
            DNS
        </div>
        <div class="panel-body">


            <div class="form-group col-md-6">
                <lable for="name">Maintain Record</lable>
                <select name="dns" class="form-control">
                    <option value="">No</option>
                    <option @if( $ip->dns == "Yes") selected="true" @endif>
                        Yes
                    </option>
                    <option @if( $ip->dns == "ReverseOnly") selected="true"@endif value="ReverseOnly">
                        Reverse Only
                    </option>
                </select></div>
            <div class="form-group col-md-3">
                <lable for="name">Hostname</lable>
                <input type="text" name="hostname" class="form-control" value="{{ $ip->hostname }}">
            </div>
            <div class="form-group col-md-3">
                <lable for="name">DNS Zone</lable>
                <select name="dns_zone" class="form-control">
                    <option value=""></option>
                    <option @if( $ip->dns_zone == "if.hamwan.ca.") selected="true" @endif value="if.hamwan.ca." >
                        .if.hamwan.ca.
                    </option>
                    <option @if( $ip->dns_zone == "cl.hamwan.ca.") selected="true" @endif value="cl.hamwan.ca." >
                        .cl.hamwan.ca.
                    </option>
                    <option @if( $ip->dns_zone == "if.ocarc.ca.") selected="true" @endif value="if.ocarc.ca." >
                        .if.ocarc.ca.
                    </option>
                    <option @if( $ip->dns_zone == "cl.ocarc.ca.") selected="true" @endif value="cl.ocarc.ca." >
                        .cl.ocarc.ca.
                    </option>
                </select></div>
            </div>


        </div>



    <div class="form-group col-md-6">
        <lable for="name">Category</lable>
        <select name="category" class="form-control">
            <option value=""></option>
            <option @if( $ip->category == "OSPF Routing") selected="true" @endif
                value="OSPF Routing">OSPF Routing
            </option>
            <option @if( $ip->category == "Clients") selected="true" @endif
                value="Clients">Clients
            </option>
            <option @if( $ip->category == "Client ip") selected="true" @endif
            value="Client ip">Client ip
            </option>
            <option @if( $ip->category == "Infrastructure") selected="true" @endif
            value="Infrastructure">Infrastructure
            </option>
        </select></div>









    <div class="col-md-12">
        <button type="submit" class="btn btn-success">@if( $ip->id ) Save ip @else Create
            ip @endif</button>
        <button type="cancel" class="btn btn-danger"> Cancel</button>
    </div>
</form>