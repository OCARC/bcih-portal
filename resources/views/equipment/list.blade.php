
<div class="table-responsive">

    <table class="table sortable table-responsive table-condensed table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th style="width: 50px">Ping</th>
            <th style="width: 50px"></th>
            <th>Hostname</th>

            <th>Platform</th>
            <th>Site</th>
            <th class="sorttable_numeric">Version</th>

            <th class="sorttable_numeric">Volt</th>
            <th class="sorttable_numeric">Temp</th>
            <th class="sorttable_numeric">Freq</th>
            <th>CCQ</th>
            <th>Clients</th>
            <th>SSID</th>


            {{--<th>Uptime</th>--}}
            {{--<th>Last Queried</th>--}}
        </tr>
        </thead>
        <tbody>

            @each('equipment.parts.list-row', $equipment, 'row')
        </tbody>

    </table>

    <a href="{{ url("/equipment/create") }}"><button type="button" class="btn btn-sm btn-success">Create Equipment</button></a>
    <a href="{{ url("/equipment/refresh") }}"><button type="button" class="btn btn-sm btn-info">Poll Equipment</button></a>

</div>
