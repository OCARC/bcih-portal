<div role="tabpanel" class="tab-pane" id="ospf">

    @if ($equipment->os == 'RouterOS')


        <h3>Get OSPF Routes</h3>
        <div class="ajaxAction">
            <div class="ajaxResult"></div>
            <div class="ajaxButtons">
            <button class="btn btn-default defaultButton"
                    onClick="ajaxAction(this,'{{url('equipment/' . $equipment->id . "/fetchOSPFRoutes")}}')">
                Fetch OSPF Routes
            </button>
            </div>
        </div>


    @endif
</div>
