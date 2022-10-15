@extends('common.master')
@section('title')
    RouterOS Update Manager
@endsection
@section('content')


    <h2>RouterOS Update Manager</h2>

    <h3>Equipment</h3>
    <table class="table sortable table-responsive table-condensed table-striped table-bordered table-hover">
        <thead>
            <th>Device</th>
        <th>Installed</th>
        <th>Latest</th>
        <th>Channel</th>
        <th>Result</th>
        <th>Actions</th>
        </thead>
        <tbody>
        @foreach ($equipment as $row)

            <tr id="equipment-{{ $row->id }}" class="ajaxAction">
                <td>
                    <a href="{{url("equipment/" . $row->id ) }}">{{ $row->hostname  }}</a><br>
                </td>
                <td class="version">
                    -
                </td>
                <td class="latestVersion">
                    -
                </td>
                <td class="channel">
                    -
                </td>
                <td class="output ajaxResult" style="">

                </td>
                <td>
                    <div class="ajaxButtons">
                        <button class="btn btn-default btn-checkForUpdates"
                                onClick="ajaxAction(this,'{{url('equipment/' . $row->id . "/checkForUpdates")}}', updateCheckCallback )">
                            Check
                        </button>
                        <button class="btn btn-default btn-warning"
                                onClick="ajaxAction(this,'{{url('equipment/' . $row->id . "/downloadUpdates")}}', updateCheckCallback)">
                            Download
                        </button>
                        <button class="btn btn-default btn-danger"
                                onClick="ajaxAction(this,'{{url('equipment/' . $row->id . "/installUpdates")}}'), updateCheckCallback">
                            Install
                        </button>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h3>Client Radios</h3>
    <div class="alert alert-danger">DO NOT USE THIS TOOL YET</div>
    <table class="table sortable table-responsive table-condensed table-striped table-bordered table-hover">
        <thead>
        <th>Device</th>
        <th>Installed</th>
        <th>Latest</th>
        <th>Channel</th>
        <th>Result</th>
        <th>Actions</th>
        </thead>
        <tbody>
        @foreach ($clients as $row)

            <tr id="client-{{ $row->id }}" class="ajaxAction">
                <td>
                    <a href="{{url("client/" . $row->id ) }}">{{ ($row->librenms_mapping) ? $row->libre_device['hostname'] : $row->hostname }}</a><br>
                    {{ ($row->librenms_mapping) ? $row->libre_device['sysName'] : $row->snmp_sysName }}
                </td>
                <td class="version">
                    -
                </td>
                <td class="latestVersion">
                    -
                </td>
                <td class="channel">
                    -
                </td>
                <td class="output ajaxResult" style="">

                </td>
                <td>
                    <div class="ajaxButtons">
                        <button class="btn btn-default btn-checkForUpdates"
                                onClick="ajaxAction(this,'{{url('equipment/' . $row->id . "/checkForUpdates")}}', updateCheckCallback )">
                            Check
                        </button>
                        <button class="btn btn-default btn-warning"
                                onClick="ajaxAction(this,'{{url('equipment/' . $row->id . "/downloadUpdates")}}', updateCheckCallback)">
                            Download
                        </button>
                        <button class="btn btn-default btn-danger"
                                onClick="ajaxAction(this,'{{url('equipment/' . $row->id . "/installUpdates")}}'), updateCheckCallback">
                            Install
                        </button>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@section('scripts')

    <script>
        $(document).ready(function () {
            $('.btn-checkForUpdates').each(function (k, v) {
                $(v).click();
            });
        });

        function updateCheckCallback(target, data) {
            $(target).closest('.ajaxAction').find('.ajaxResult pre').scrollTop($(target).closest('.ajaxAction').find('.ajaxResult pre').height());

            if (data.data) {
                if (data.data.indexOf('System is already up to date') !== -1) {
                    $(target).closest('tr').addClass('success');
                    $(target).closest('tr').find('.ajaxButtons').hide();


                } else {
                    $(target).closest('tr').removeClass('success');
                }

                if (data.data.indexOf('New version is available') !== -1) {
                    $(target).closest('tr').addClass('warning');
                } else {
                    $(target).closest('tr').removeClass('warning');
                }

                // Find installed Version
                var rx = /installed-version:\s(.+)\n/g;
                var arr = rx.exec($(target).closest('tr').find('.ajaxResult pre').html());
                if (arr) {
                    $(target).closest('tr').find('.version').html(arr[1]);
                } else {
                    $(target).closest('tr').find('.version').html('???');
                }

                // Find Latest Version
                var rx = /latest-version:\s(.+)\n/g;
                var arr = rx.exec($(target).closest('tr').find('.ajaxResult pre').html());
                if (arr) {
                    $(target).closest('tr').find('.latestVersion').html(arr[1]);
                } else {
                    $(target).closest('tr').find('.latestVersion').html('???');
                }

                // Find Channel
                var rx = /channel:\s(.+)\n/g;
                var arr = rx.exec($(target).closest('tr').find('.ajaxResult pre').html());
                console.log(data.data);
                if (arr) {
                    $(target).closest('tr').find('.channel').html(arr[1]);
                } else {
                    $(target).closest('tr').find('.channel').html('???');
                }
            }
            if (data.status == 'failed') {
                $(target).closest('tr').addClass('danger');
                $(target).closest('tr').find('.output').html('An Error Occurred Communicating With This Device');
            } else {
                $(target).closest('tr').removeClass('danger');
            }

        }
    </script>
    <style>
        td.output.ajaxResult pre {
            max-height: 60px;
            padding: 0px;
            margin: 0px;
            font-size: 10px;
        }
    </style>
@endsection
