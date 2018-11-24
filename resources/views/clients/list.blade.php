<div class="table-responsive">

    <table class="table sortable table-responsive table-condensed table-striped table-bordered table-hover">

    <thead>
    <tr>
        <th style="width: 25px"><span class="glyphicon glyphicon-info-sign"></span></th>
        <th style="width: 50px"></th>
        <th>Radio Name</th>
        <th>Address</th>
        <th>Connected To</th>
        <th>Signal</th>
        <th>SNR</th>

        <th></th>
        <th>Ch0</th>
        <th>Ch1</th>

        <th>Rates</th>

        <th>Last Seen</th>
    </tr>
    </thead>
    <tbody>

    @each('clients.parts.list-row', $clients, 'row')

    </tbody>
</table>
    </div>