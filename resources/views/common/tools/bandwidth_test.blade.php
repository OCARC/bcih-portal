<div class="panel panel-default">
    <div class="panel-heading">Perform Bandwidth Test From This Device</div>
    <div class="panel-body">
        <p>You can use this tool to perform a speed test from this device to another device on the network. You should be able to find a device at any of our sites that is available for testing.</p>
        <div class="ajaxAction">
            <div class="ajaxResult"></div>
            <div class="ajaxButtons form-inline">

                <div class="form-group">
                    <label for="target">Target</label>
                    <select class="form-control" id="target">
                        @foreach( $bwtest_servers as $bws )
                            <option value="{{$bws->management_ip}}">{{$bws->hostname}}
                                ({{$bws->management_ip}})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="duration">Duration</label>

                    <select class="form-control" id="duration">
                        <option value="1">1 Seconds</option>
                        <option value="5">5 Seconds</option>
                        <option value="10" selected="selected">10 Seconds</option>
                        <option value="30">30 Seconds</option>
                        <option value="45">45 Seconds</option>
                        <option value="60">60 Seconds</option>
                    </select>
                </div>

                <div class="form-group">

                    <label for="direction">Direction</label>
                    <select class="form-control" id="direction">
                        <option value="both">Both</option>
                        <option value="transmit">Send</option>
                        <option value="receive" selected="selected">Receive</option>
                    </select>
                </div>
                @if( isset($equipment) )
                <button class="btn btn-default"
                        onClick="ajaxAction(this,'{{ url('equipment/' . $equipment->id . "/bwTest")}}?target=' + $('#target').val() + '&duration=' + $('#duration').val() + '&direction=' + $('#direction').val() + '')">
                    Perform Bandwidth Test
                </button>
                    @endif
                @if( isset($client) )
                    <button class="btn btn-default"
                            onClick="ajaxAction(this,'{{ url('clients/' . $client->id . "/bwTest")}}?target=' + $('#target').val() + '&duration=' + $('#duration').val() + '&direction=' + $('#direction').val() + '')">
                        Perform Bandwidth Test
                    </button>
                @endif
            </div>
        </div>
        <br>
        <p class="text-info"><strong>Note:</strong> This can consume a lot of network link bandwidth. Please only test when needed.</p>

    </div>
</div>
