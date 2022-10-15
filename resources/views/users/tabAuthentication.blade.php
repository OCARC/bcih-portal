<div role="tabpanel" class="tab-pane" id="authentication">

<br>

    <div class="card card-default">
        <div class="card-header">
            Password
        </div>
        <div class="card-body">

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

    <div class="card card-default">
        <div class="card-header">
            Keys
        </div>
    @include('keys.list', ['keys' => $user->keys ])

    </div>
</div>
