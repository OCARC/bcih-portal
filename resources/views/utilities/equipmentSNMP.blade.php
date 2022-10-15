@extends('common.master')
@section('title')
    RouterOS Update Manager
@endsection
@section('content')


    <h2>Equipment SNMP Tester</h2>

    <h3>Equipment</h3>
    <table class="table sortable table-responsive table-condensed table-striped table-bordered table-hover">
        <thead>

        </thead>
        <tbody>
        @foreach ($equipment as $row)

            <tr id="equipment-{{ $row->id }}" class="ajaxAction">
                <td>
                    <a href="{{url("equipment/" . $row->id ) }}">{{ ($row->librenms_mapping) ? $row->libre_device['hostname'] : $row->hostname }}</a><br>
                    {{ ($row->librenms_mapping) ? $row->libre_device['sysName'] : $row->snmp_sysName }}

                </td>

                <td class="output ajaxResult" style="">

                </td>
                <td>
                    <div class="ajaxButtons">
                        <button class="btn btn-default btn-pollSNMP"
                                onClick="ajaxAction(this,'{{url('equipment/' . $row->id . "/pollSNMP")}}', pollSNMPCallback )">
                            Poll SNMP
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
            $('.btn-pollSNMP').each(function (k, v) {
                $(v).click();
            });
        });

        function pollSNMPCallback(target, data) {
            $(target).closest('.ajaxAction').find('.ajaxResult pre').scrollTop($(target).closest('.ajaxAction').find('.ajaxResult pre').height());

            if (data.data) {
                    $(target).closest('tr').addClass('success');


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
