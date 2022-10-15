<div class="table-responsive">

    <table class="table sortable table-responsive table-condensed table-striped table-bordered">

    <thead>
    <tr>
        <th style="width: 20px"></th>
        <th>Site Name</th>
        <th>Code</th>
        <th>Status</th>
        <th>Comments</th>
        <th>Location</th>
        <th>Altitude</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($sites->sortBy('status') as $row)
        <tr>
            <td style="position: relative;">

                        <div style="position: absolute; top: 5px; bottom: 5px; left:5px; right: 5px; background: {{$row->statusColor() }}"></div>

            </td>

            <td class="text-nowrap text-truncate">
                <img class="" src="{{$row->icon()}}" style="max-height: 2.75em;max-width: 2.75em; float:left; margin-right: 0.5em">

                <a href="{{url("site/" . $row->id )}}">{{$row->name }}</a>
                @if( $row->librenms_mapping )
                    <a href="//nms.if.hamwan.ca/device/{{$row->librenms_mapping}}" target="_blank"><img style="height: 1em; float:right" src="/images/librenmsicon.png"></a>
                @endif
                <div class="text-muted small text-nowrap text-truncate">{{$row->description}}</div>
            </td>

            <td>{{ $row->sitecode }}</td>
            <td>{{ $row->status }}</td>

            @if (! Auth::guest())
                <td>{{$row->comment}}</td>
            @else
                <td>Login to view comments</td>
            @endif
            @if (! Auth::guest())
                <td>
                    <a href="http://www.google.com/maps/?q={{ $row->latitude }},{{ $row->longitude }}">{{ number_format($row->latitude,3) }},&nbsp;{{ number_format($row->longitude,3) }}</a></td>
            @else
                <td>n/a</td>
            @endif
            <td class="text-end">{{ $row->altitude }}m</td>
        </tr>
    @endforeach
    </tbody>

</table>
    </div>