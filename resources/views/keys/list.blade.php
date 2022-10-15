<table class="table table-responsive table-condensed table-striped">

    <thead>
    <tr>
        <th>Key Name</th>
        <th>Owner</th>
        <th>Public</th>
        <th>Private</th>
        <th>SSH</th>
        <th>Status</th>
        <th>Published</th>
        <th>Created</th>
    </tr>
    </thead>
    <tbody>

    @foreach ($keys as $row)
        <tr>
            <td><a href="{{url("keys/" . $row->id )}}">{{ $row->name }}</a></td>
            <td>{{ $row->user->callsign }}</td>
            <td>@if ($row->public_key) Yes @endif</td>
            <td>@if ($row->public_ssh) Yes @endif</td>
            <td>@if ($row->private_key) Yes @endif</td>
            <td>{{ $row->status }}</td>
            <td>@if ($row->publish) Yes @endif</td>
            <td>{{ $row->created_at }}</td>

        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="3">
            <a href="{{ url("keys/create") }}">create new key</a> |
        </td>
    </tr>
    </tfoot>
</table>