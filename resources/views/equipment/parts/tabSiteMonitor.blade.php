<div role="tabpanel" class="tab-pane" id="sitemon">

    <div class="table-responsive">
<h4>Digital Inputs</h4>
        <table class="table sortable table-responsive table-condensed table-striped table-bordered" id="digitalInput">

            <thead>
            <tr>

                <th>Name</th>
                <th>Value</th>
                <th>Count</th>

            </tr>
            </thead>
            <tbody>
<tr class="loading"><td colspan="3" class="text-center">Loading...</td></tr>

            </tbody>

        </table>
        <p>Note: Inputs prefixed with <strong>!</strong> are hidden.</p>

<h4>Analog Inputs</h4>
        <table class="table sortable table-responsive table-condensed table-striped table-bordered" id="analogInput">

            <thead>
            <tr>

                <th>Name</th>
                <th>Value</th>
                <th>Measure</th>

            </tr>
            </thead>
            <tbody>

<tr class="loading"><td colspan="3" class="text-center">Loading...</td></tr>
            </tbody>

        </table>
        <p>Note: Inputs prefixed with <strong>!</strong> are hidden.</p>

        <h4>Outputs</h4>
        <table class="table sortable table-responsive table-condensed table-striped table-bordered" id="output">

            <thead>
            <tr>

                <th>Name</th>
                <th>Value</th>
                <th>Actions</th>

            </tr>
            </thead>
            <tbody>

<tr class="loading"><td colspan="3" class="text-center">Loading...</td></tr>
            </tbody>

        </table>
        <p>Note: Outputs prefixed with <strong>!</strong> are hidden.</p>

        <h4>PWM Outputs</h4>
        <table class="table sortable table-responsive table-condensed table-striped table-bordered" id="pwm">

            <thead>
            <tr>

                <th>Name</th>
                <th>Value</th>
                <th>Actions</th>

            </tr>
            </thead>
            <tbody>

            <tr class="loading"><td colspan="3" class="text-center">Loading...</td></tr>
            </tbody>

        </table>
        <p>Note: Outputs prefixed with <strong>!</strong> are hidden.</p>
    </div>
</div>

@section('scripts')
    @parent

<script>


function updateMonitor() {
    $.getJSON("/equipment/{{ $equipment->id }}/denkovi/current_state.json", function (data) {

    parseResponse(data);
    });

    setTimeout( function() {updateMonitor()}, 10000);
}
function change(button, type, id, value ) {
    $(button).replaceWith('Waiting...');
    $.getJSON("/equipment/{{ $equipment->id }}/denkovi/current_state.json?" + type + id + "=" + value, function (data) {
        parseResponse(data);

    });
}

function parseResponse( data ) {

    // Digital Input

    var $di = $('#digitalInput');
    var id = 1;
    jQuery.each(data.CurrentState.DigitalInput, function(i,r) {
        $di.find('.loading').remove();

        if ( r.Name.substring(0,1) != "!") {
            if ($di.find('#digitalInput-' + id).length >= 1) {

                $di.find('tbody').find('#digitalInput-' + id).replaceWith('<tr id="digitalInput-' + id + '">' +
                    '<td>' + r.Name + '</td>' +
                    '<td>' + r.Value + '</td>' +
                    '<td>' + r.Count + '</td>' +

                    '</tr>');
            } else {

                $di.find('tbody').append('<tr id="digitalInput-' + id + '">' +
                    '<td>' + r.Name + '</td>' +
                    '<td>' + r.Value + '</td>' +
                    '<td>' + r.Count + '</td>' +

                    '</tr>');
            }
        }
        id++;
    });

    // Analog Input

    var $di = $('#analogInput');
    var id = 1;
    jQuery.each(data.CurrentState.AnalogInput, function(i,r) {
        $di.find('.loading').remove();
        if ( r.Name.substring(0,1) != "!") {

            if ($di.find('#analogInput-' + id).length >= 1) {
                $di.find('tbody').find('#analogInput-' + id).replaceWith('<tr id="analogInput-' + id + '">' +
                    '<td>' + r.Name + '</td>' +
                    '<td>' + r.Value + '</td>' +
                    '<td>' + r.Measure + '</td>' +

                    '</tr>');
            } else {

                $di.find('tbody').append('<tr id="analogInput-' + id + '">' +
                    '<td>' + r.Name + '</td>' +
                    '<td>' + r.Value + '</td>' +
                    '<td>' + r.Measure + '</td>' +

                    '</tr>');
            }
        }
        id++;
    });

    // Outputs

    var $di = $('#output');
    var id = 1;
    jQuery.each(data.CurrentState.Output, function(i,r) {
        $di.find('.loading').remove();

        button = "";

        if ( r.Value == 1 ) {
            button = '<button class="btn btn-sm btn-danger" onClick="change(this,\'Output\',' + id +',0);">Turn Off</button>';
        } else {
            button = '<button class="btn btn-sm btn-success" onClick="change(this,\'Output\',' + id +',1);">Turn On</button>';

        }
        if ( r.Name.substring(0,1) != "!") {

            if ($di.find('#output-' + id).length >= 1) {
                $di.find('tbody').find('#output-' + id).replaceWith('<tr id="output-' + id + '">' +
                    '<td>' + r.Name + '</td>' +
                    '<td>' + r.Value + '</td>' +
                    '<td class="outputButtons">' +
                    button +
                    '' + '</td>' +

                    '</tr>');
            } else {

                $di.find('tbody').append('<tr id="output-' + id + '">' +
                    '<td>' + r.Name + '</td>' +
                    '<td>' + r.Value + '</td>' +
                    '<td class="outputButtons">' +
                    button +
                    '' + '</td>' +

                    '</tr>');
            }
        }
        id++;
    });

    // Outputs

    var $di = $('#pwm');
    var id = 1;
    jQuery.each(data.CurrentState.PWM, function(i,r) {
        $di.find('.loading').remove();
        if ( r.Name.substring(0,1) != "!") {

            if ($di.find('#pwm-' + id).length >= 1) {
                $di.find('tbody').find('#pwm-' + id).replaceWith('<tr id="pwm-' + id + '">' +
                    '<td>' + r.Name + '</td>' +
                    '<td>' + r.Value + '</td>' +
                    '<td class="outputButtons">' + '</td>' +

                    '</tr>');
            } else {

                $di.find('tbody').append('<tr id="pwm-' + id + '">' +
                    '<td>' + r.Name + '</td>' +
                    '<td>' + r.Value + '</td>' +
                    '<td class="outputButtons">' + '</td>' +

                    '</tr>');
            }
        }
        id++;
    });

}

$( document ).ready(function() {
    updateMonitor();

});
</script>
    @endsection