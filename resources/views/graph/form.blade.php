<form class="" method="POST" action="{{ url("/ips") }}">

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $ip->id }}">


    <div class="card card-default">
        <div class="card-header">
            Address Information
        </div>
        <div class="card-body">

            <div class="form-group col-md-6">
                <label for="name">Descriptive Name</label>
                <input type="text" name="name" class="form-control" value="{{ $ip->name }}">
            </div>


            <div class="form-group col-md-6">
                <label for="name">Status</label>
                <select name="status" class="form-select">
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

                    <label for="name">Site</label>
                    <select name="site_id" class="form-select" required>
                        @foreach( $sites as $site)
                            <option @if ($ip->site_id == $site->id) selected="true"
                                    @endif value="{{ $site->id }}">{{$site->name}} ({{$site->sitecode}})
                            </option>
                        @endforeach
                    </select>
            </div>

            <div class="form-group col-md-6">
                    <label for="name">Owner</label>
                    <select name="user_id" class="form-select" required>
                        <option value="0"></option>
                        @foreach( $users as $user)
                            <option @if ($ip->user_id == $user->id) selected="true"
                                    @endif value="{{ $user->id }}">{{$user->name}} ({{$user->callsign}})
                            </option>
                        @endforeach
                    </select>
                    <p class="form-text">Be careful when assigning ownership. If you assign ownership to someone else you may
                        not be able to access the ip anymore.</p>

            </div>

            <div class="form-group col-md-6">
                <label for="mac_address">MAC Address</label>
                <input type="text" name="mac_address" class="form-control" value="{{ $ip->mac_address }}">

            </div>

            <div class="form-group col-md-6">

                <label for="name">Equipment</label>
                <select name="equipment_id" class="form-select">
                    <option value=""></option>
                    @foreach( $equipments as $equipment)
                        <option @if ($ip->equipment_id == $equipment->id) selected="true"
                                @endif value="{{ $equipment->id }}">{{$equipment->hostname}}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="form-group col-md-6">
                <label for="name">Comments</label>
                <textarea rows=5  name="comment" class="form-control">{{ $ip->comment }}</textarea>
            </div>
            <div class="form-group col-md-6">
                <label for="name">Description</label>
                <textarea rows=5 name="description" class="form-control">{{ $ip->description }}</textarea>
                <p class="form-text">This content may be used to describe the site publicly</p>
            </div>

        </div>
    </div>


    <div class="card card-default">

        <div class="card-header">
            Network Information
        </div>
        <div class="card-body">
            <div class="form-group col-md-6">
                <label for="name">IP Address</label>
                <input type="text" name="ip" class="form-control" value="{{ $ip->ip }}">
            </div>


        <div class="form-group col-md-6">
            <label for="name">Gateway</label>
            <input type="text" name="gateway" class="form-control" value="{{ $ip->gateway }}">
        </div>

        <div class="form-group col-md-6">
            <label for="name">Netmask</label>
            <input type="text" name="netmask" class="form-control" value="{{ $ip->netmask }}">
        </div>

        <div class="form-group col-md-6">
            <label for="name">DHCP Server</label>
            <input type="text" name="dhcp" class="form-control" value="{{ $ip->dhcp }}">
        </div>
        </div>
    </div>

    <div class="card card-default">

        <div class="card-header">
            DNS
        </div>
        <div class="card-body">


            <div class="form-group col-md-6">
                <label for="name">Maintain Record</label>
                <select name="dns" class="form-select">
                    <option value="">No</option>
                    <option @if( $ip->dns == "Yes") selected="true" @endif>
                        Yes
                    </option>
                    <option @if( $ip->dns == "ReverseOnly") selected="true"@endif value="ReverseOnly">
                        Reverse Only
                    </option>
                </select></div>
            <div class="form-group col-md-3">
                <label for="name">Hostname</label>
                <input type="text" name="hostname" class="form-control" value="{{ $ip->hostname }}">
            </div>
            <div class="form-group col-md-3">
                <label for="name">DNS Zone</label>
                <select name="dns_zone" class="form-select">
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
        <label for="name">Category</label>
        <select name="category" class="form-select">
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