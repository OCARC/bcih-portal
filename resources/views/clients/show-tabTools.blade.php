<div role="tabpanel" class="tab-pane" id="tools">
    <h3>Get Configuration</h3>
    <div class="ajaxAction">
        <div class="ajaxResult"></div>
        <button class="btn btn-default"
                onClick="ajaxAction(this,'{{url('clients/' . $client->id . "/fetchConfig")}}')">Fetch
            Configuration
        </button>
    </div>
    <h3>Perform Quick Scan</h3>
    <span class="text-danger"><strong>CAUTION:</strong> This action will cause the client to disconnect from the network. If it does not reconnect quickly enough you might not get a result.</span>

    <div class="ajaxAction">
        <div class="ajaxResult"></div>
        <button class="btn btn-default"
                onClick="ajaxAction(this,'{{url('clients/' . $client->id . "/quickScan")}}')">Run Quick Scan
        </button>
    </div>
    <h3>Retrieve Wireless Stats</h3>

    <div class="ajaxAction">
        <div class="ajaxResult"></div>
        <button class="btn btn-default"
                onClick="ajaxAction(this,'{{url('clients/' . $client->id . "/quickMonitor")}}')">Retrieve
            Wireless Stats
        </button>
    </div>

    <h3>Get Spectral History</h3>
    <span class="text-danger"><strong>CAUTION:</strong> This action will cause the client to disconnect from the network. If it does not reconnect quickly enough you might not get a result.</span>
    <div class="ajaxAction">
        <div class="ajaxResult"></div>
        <button class="btn btn-default"
                onClick="ajaxAction(this,'{{url('clients/' . $client->id . "/fetchSpectralHistory")}}')">
            Fetch Spectral History
        </button>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Check For Updates</div>
        <div class="panel-body">
            <div class="col-md-12">

            <p class="text-warning"><strong>CAUTION:</strong> This function will only work correctly if the remote device has working internet access. Ensure you fully understand the update before performing remote updates.</p>
            </div>
            <div class="col-md-6">

            <div class="ajaxAction">
                <div class="ajaxResult"></div>
                <button class="btn btn-default"
                        onClick="ajaxAction(this,'{{url('clients/' . $client->id . "/checkForUpdates")}}')">
                    Check For Updates
                </button>
                <button class="btn btn-default btn-warning"
                        onClick="ajaxAction(this,'{{url('clients/' . $client->id . "/downloadUpdates")}}')">
                    Download Updates
                </button>
                <button class="btn btn-default btn-danger"
                        onClick="ajaxAction(this,'{{url('clients/' . $client->id . "/installUpdates")}}')">
                    Install Updates
                </button>
            </div>
            </div>
            <div class="col-md-6">
                The owner of this device has opted to manage updates themselves.
            </div>
        </div>
    </div>

    @include('common.tools.bandwidth_test', ['bwtest_servers' => $bwtest_servers])
</div>
