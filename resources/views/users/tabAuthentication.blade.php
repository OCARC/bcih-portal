<div role="tabpanel" class="tab-pane" id="authentication">

<br>

    <div class="panel panel-default">
        <div class="panel-heading">
            Password
        </div>
        <div class="panel-body">

            @if( $user->realm == 'ldap')
                <div class="alert alert-info">
                    This users password is managed through LDAP and cannot be changed here.
                </div>
            @else
                <div class="alert alert-info">
                    You cannot change this users password.
                </div>

            @endif

        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Keys
        </div>
    @include('keys.list', ['keys' => $user->keys ])

    </div>
</div>
