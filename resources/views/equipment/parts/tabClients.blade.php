
<div role="tabpanel" class="tab-pane" id="clients">

@include('clients.list', ['clients' => $equipment->clients ])


@if ($equipment->os == 'RouterOS' and $equipment->discover_clients == true )
    @if (! Auth::guest())

        <div class="card card-default">
            <div class="card-header">Discover Clients</div>
            <div class="card-body">
                <p>The portal periodically scans for new clients on configured devices. But this tool allows you to force a scan in realtime.</p>

                <div class="ajaxAction">
                    <div class="ajaxResult"></div>
                    <div class="ajaxButtons">

                        <button class="btn btn-default"
                                onClick="ajaxAction(this,'{{url('equipment/' . $equipment->id . "/discoverClients")}}')">
                            Scan For Connected Devices
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @else
    <div class="alert alert-warning">
        Automatic client discovery is disabled for this device.
    </div>
@endif



    </div>
