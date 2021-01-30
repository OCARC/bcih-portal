<div role="tabpanel" class="tab-pane" id="tools">
    <br>
    <p>The tools available here vary based on your privileges and the device type.</p>


    @if ($equipment->os == 'RouterOS')
        @if ($equipment->remote_serial_port != '')
            @if (! Auth::guest())
                <div class="panel panel-default">
                    <div class="panel-heading">Manage Remote Serial Port Access</div>
                    <div class="panel-body">
                        <p>Using this tool you can add/remove IP addresses that are authroized to access the serial port
                            on this device remotely.</p>

                        <div class="ajaxAction">
                            <div class="ajaxResult"></div>
                            <div class="ajaxButtons form-inline">
                                <button class="btn btn-default"
                                        onClick="ajaxAction(this,'{{url('equipment/' . $equipment->id . "/querySerialStatus")}}')">
                                    Query Status
                                </button>

                                <button class="btn btn-default btn-danger"
                                        onClick="ajaxAction(this,'{{url('equipment/' . $equipment->id . "/resetSerialAuthorizations")}}')">
                                    Reset Authorizations
                                </button>

                                <div class="input-group col-md-4" style="max-width:250px">
                                    <input type="text" class="form-control" id="authSerialIP"
                                           value="{{ ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )  ?  $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'] }}">
                                    <div class="input-group-btn">
                                        <button class="btn btn-default btn-success"
                                                onClick="ajaxAction(this,'{{url('equipment/' . $equipment->id . "/authorizeSerialIP")}}?ip=' + jQuery('#authSerialIP').val() )">
                                            Authorize IP
                                        </button>
                                    </div>
                                </div>
                                <br><br>
                                <p class="text-info">
                                    <strong>Note:</strong> Please Reset Authorizations after you have finished using the
                                    serial port, This ensures that all connections are closed and secure</p>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            @endif
        @endif
    @endif

    @if ($equipment->os == 'RouterOS')
        @if (! Auth::guest())

            <div class="panel panel-default">
                <div class="panel-heading">Get Configuration</div>
                <div class="panel-body">
                    <p>Retrieve the current configuration from the device.</p>

                    <div class="ajaxAction">
                        <div class="ajaxResult"></div>
                        <div class="ajaxButtons">

                            <button class="btn btn-default"
                                    onClick="ajaxAction(this,'{{url('equipment/' . $equipment->id . "/fetchConfig")}}')">
                                Fetch Configuration
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif


    @if ($equipment->os == 'RouterOS')
        @if (! Auth::guest())

            @include('common.tools.bandwidth_test', ['bwtest_servers' => $bwtest_servers])


        @endif
    @endif


@if ($equipment->os == 'RouterOS')

        <h3>Get POE Status</h3>
        <div class="ajaxAction">
            <div class="ajaxResult"></div>
            <div class="ajaxButtons">

                <button class="btn btn-default"
                        onClick="ajaxAction(this,'{{url('equipment/' . $equipment->id . "/fetchPOE")}}')">Fetch
                    POE Status
                </button>
            </div>
        </div>

        <h3>Get Spectral History</h3>
        <span class="text-warning"><strong>CAUTION:</strong> This will disconnect clients, be careful using on link radios.</span>
        <div class="ajaxAction">
            <div class="ajaxResult"></div>
            <div class="ajaxButtons">

                <button class="btn btn-default"
                        onClick="ajaxAction(this,'{{url('equipment/' . $equipment->id . "/fetchSpectralHistory")}}')">
                    Fetch Spectral History
                </button>
            </div>
        </div>


        <h3>Check For Updates</h3>
        <span class="text-warning"><strong>CAUTION:</strong> This function will only work correctly if the remote device has working internet access. Ensure you fully understand the update before performing remote updates.</span>

        <div class="ajaxAction">
            <div class="ajaxResult"></div>
            <div class="ajaxButtons">

                <button class="btn btn-default"
                        onClick="ajaxAction(this,'{{url('equipment/' . $equipment->id . "/checkForUpdates")}}')">
                    Check For Updates
                </button>
                <button class="btn btn-default btn-warning"
                        onClick="ajaxAction(this,'{{url('equipment/' . $equipment->id . "/downloadUpdates")}}')">
                    Download Updates
                </button>
                <button class="btn btn-default btn-danger"
                        onClick="ajaxAction(this,'{{url('equipment/' . $equipment->id . "/installUpdates")}}')">
                    Install Updates
                </button>
            </div>
        </div>
        <br>

    @endif


</div>