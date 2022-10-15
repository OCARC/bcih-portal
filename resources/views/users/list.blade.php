<div class="table-responsive">

<table class="table sortable table-responsive table-condensed table-striped table-bordered table-hover">


        <thead>
        <tr>
            <th>Callsign</th>
            <th>User Name</th>
            <th>Realm</th>
            <th>Organization</th>
            <th>Role</th>
            <th>Equipment</th>
            <th>Sites</th>
            <th>Clients</th>
            <th>IPs</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($users as $user)
            <tr>
                <td><a href="{{url("users/" . $user->id )}}">{{ $user->callsign }}</a></td>
                <td>{{ $user->name }}</td>
                <td>
                    @if( $user->realm == 'local')
                        Local
                    @elseif( $user->realm == 'ldap')
                        LDAP
                    @else
                        {{ $user->realm }}
                    @endif
                </td>
                <td>
                        @foreach( $user->roles as $role )
                                @if($role->category == "Organizations")
                            &bull; {{ $role->friendly_name ?? $role->name }}<br>
                                @endif
                        @endforeach

                </td>
                <td>
                        @foreach( $user->roles as $role )
                        @if($role->category != "Organizations")
                        &bull; {{ $role->friendly_name ?? $role->name }}<br>
                        @endif
                        @endforeach

                </td>
                <td><a href="{{url("users/" . $user->id ) . "#equipment"}}">{{ count($user->equipment) }}</a></td>
                <td><a href="{{url("users/" . $user->id ) . "#sites"}}">{{ count($user->sites) }}</a></td>
                <td><a href="{{url("users/" . $user->id ) . "#clients"}}">{{ count($user->clients) }}</a></td>
                <td><a href="{{url("users/" . $user->id ) . "#ips"}}">{{ count($user->ips) }}</a></td>
            </tr>
        @endforeach

        </tbody>


</table>
    </div>