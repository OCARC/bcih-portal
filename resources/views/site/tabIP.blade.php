<div role="tabpanel" class="tab-pane" id="ips">
    <h3>Subnets</h3>
    @include('subnet.list', ['subnets' => $site->subnets ])

    <h3>IP Addresses</h3>
    @include('ip.list', ['ips' => $site->ips ])

</div>