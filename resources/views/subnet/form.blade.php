<form class="" method="POST" action="{{ url("/subnets") }}">

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $subnet->id }}">


    <div class="form-group col-md-6">
        <label for="name">Name</label>
        <input type="text" name="name" class="form-control" value="{{ $subnet->name }}">
    </div>
    <div class="form-group col-md-6">
        <label for="name">Status</label>
        <select name="status" class="form-select">
            <option value=""></option>
            <option @if( $subnet->status == "Subdivided") selected="true" @endif style="background-color: #e1e1e1">
                Subdivided
            </option>
            <option @if( $subnet->status == "Planning") selected="true" @endif style="background-color: #fff6a6">
                Planning
            </option>
            <option @if( $subnet->status == "In Use") selected="true" @endif style="background-color: #aaffaa">
                In Use
            </option>
            <option @if( $subnet->status == "Do Not Use") selected="true" @endif style="background-color: #ff6666">
                Do Not Use
            </option>
            <option @if( $subnet->status == "Routing Problem") selected="true" @endif style="background-color: #ffd355">
                Routing Problem
            </option>
            <option @if( $subnet->status == "Placeholder") selected="true" @endif style="background-color: #979797"
                    value="Placeholder">Placeholder
            </option>

        </select></div>

    <div class="form-group col-md-6">
        <label for="name">Group</label>
        <select name="category" class="form-select">
            <option value=""></option>
            <option @if( $subnet->category == "OSPF Routing") selected="true" @endif
                value="OSPF Routing">OSPF Routing
            </option>
            <option @if( $subnet->category == "Clients") selected="true" @endif
                value="Clients">Clients
            </option>
            <option @if( $subnet->category == "Client Subnet") selected="true" @endif
            value="Client Subnet">Client Subnet
            </option>
        </select></div>
    <div class="form-group col-md-6">
        <label for="name">Network Address</label>
        <input type="text" name="ip" class="form-control" value="{{ $subnet->ip }}">
    </div>

    <div class="form-group col-md-6">
        <label for="name">Gateway</label>
        <input type="text" name="gateway" class="form-control" value="{{ $subnet->gateway }}">
    </div>

    <div class="form-group col-md-6">
        <label for="name">Netmask</label>
        <input type="text" name="netmask" class="form-control" value="{{ $subnet->netmask }}">
    </div>

    <div class="form-group col-md-6">
        <label for="name">DHCP Server</label>
        <input type="text" name="dhcp" class="form-control" value="{{ $subnet->dhcp }}">
    </div>


    <div class="col-md-6">

        <div class="form-group">
            <label for="name">Site</label>
            <select name="site_id" class="form-select">
                <option></option>

            @foreach( $sites as $site)
                    <option @if ($subnet->site_id == $site->id) selected="true"
                            @endif value="{{ $site->id }}">{{$site->name}} ({{$site->sitecode}})
                    </option>
                @endforeach
            </select></div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="name">Owner</label>
            <select name="user_id" class="form-select" required>
                <option value="0"></option>
                @foreach( $users as $user)
                    <option @if ($subnet->user_id == $user->id) selected="true"
                            @endif value="{{ $user->id }}">{{$user->name}} ({{$user->callsign}})
                    </option>
                @endforeach
            </select>
            <p class="form-text">Be careful when assigning ownership. If you assign ownership to someone else you may
                not be able to access the subnet anymore.</p>
        </div>
    </div>



        <div class="form-group col-md-6">
            <label for="name">Comments</label>
            <textarea rows=5  name="comment" class="form-control">{{ $subnet->comment }}</textarea>
        </div>
        <div class="form-group col-md-6">
            <label for="name">Description</label>
            <textarea rows=5 name="description" class="form-control">{{ $subnet->description }}</textarea>
            <p class="form-text">This content may be used to describe the site publicly</p>
        </div>

    <div class="col-md-12">
        <button type="submit" class="btn btn-success">@if( $subnet->id ) Save Subnet @else Create
            Subnet @endif</button>
        <button type="cancel" class="btn btn-danger"> Cancel</button>
    </div>
</form>