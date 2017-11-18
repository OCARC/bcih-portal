<div class="table-responsive">

    <table class="table sortable table-responsive table-condensed table-striped table-bordered">

    <thead>
    <tr>
        <th>Site Name</th>
        <th>Site Code</th>
        <th>Status</th>
        <th>Comments</th>
        <th>Location</th>
        <th>Altitude</th>
    </tr>
    </thead>
    <tbody>

    @foreach ($sites as $row)
        <tr>
            <td><a href="{{ url("site/" . $row->id ) }}">{{ $row->name }}</td>
            <td>{{ $row->sitecode }}</td>
            @if ($row->status == "Potential")
                <td style="vertical-align:middle;background-color: #e1e1e1">{{ $row->status }}</td>
            @elseif( $row->status == "Planning")
                <td style="vertical-align:middle;background-color: #fff6a6">{{ $row->status }}</td>
            @elseif( $row->status == "Installed")
                <td style="vertical-align:middle;background-color: #aaffaa">{{ $row->status }}</td>
            @elseif( $row->status == "Equip Failed")
                <td style="vertical-align:middle;background-color: #ff6666">{{ $row->status }}</td>
            @elseif( $row->status == "Problems")
                <td style="vertical-align:middle;background-color: #ffd355">{{ $row->status }}</td>
            @elseif( $row->status == "No Install")
                <td style="vertical-align:middle;background-color: #979797">{{ $row->status }}</td>
            @else
                <td style="vertical-align:middle;">{{ $row->status }}</td>
            @endif
            @if (! Auth::guest())
                <td>{{$row->comment}}</td>
            @else
                <td>Login to view comments</td>
            @endif
            @if (! Auth::guest())
                <td>
                    <a href="http://www.google.com/maps/?q={{ $row->latitude }},{{ $row->longitude }}">{{ number_format($row->latitude,3) }}
                        , {{ number_format($row->longitude,3) }}</a></td>
            @else
                <td>n/a</td>
            @endif
            <td class="text-right">{{ $row->altitude }} meters</td>
        </tr>
    @endforeach
    </tbody>

</table>
    </div>