<div role="tabpanel" class="tab-pane" id="snmp">
    <h3>Get SNMP</h3>
    <div class="ajaxAction">
        <div class="ajaxResult"></div>
        <div class="ajaxButtons">
            <button class="btn btn-default defaultButton"
                    onClick="ajaxAction(this,'{{url('clients/' . $client->id . "/snmpPoll")}}')">
                Fetch SNMP
            </button>
        </div>
    </div>
</div>
