<div role="tabpanel" class="tab-pane" id="dhcp">

    @if ($equipment->os == 'RouterOS')


        <h3>DHCP Leases</h3>
        @include('ip.list', ['ips' => $equipment->DHCPLeases() ])

        <h3>Get DHCP Leases</h3>
        <div class="ajaxAction">
            <div class="ajaxResult"></div>
            <div class="ajaxButtons">
            <button class="btn btn-default defaultButton"
                    onClick="ajaxAction(this,'{{url('equipment/' . $equipment->id . "/fetchDHCPLeases")}}')">
                Fetch DHCP Leases
            </button>
            </div>
        </div>


    @endif
</div>
