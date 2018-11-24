<div role="tabpanel" class="tab-pane" id="authentication">

<br>

    <div class="panel panel-default">
        <div class="panel-heading">
            Password
        </div>
        <div class="panel-body">


        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Keys
        </div>
    @include('keys.list', ['keys' => $user->keys ])

    </div>
</div>
