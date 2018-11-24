<div role="tabpanel" class="tab-pane" id="rf">


<h3>Other Equipment Detected At This Site</h3>

    <style>
        .spectrum {
            position: relative;
            height: 200px;
            border: 1px solid black;
            overflow: hidden;

        }
        .startFreq {
            text-align: left;
            position: absolute;
            font-size: 10px;
            border-left: 1px dotted grey;
            padding-left: 5px;
            height: 3000px;
        }
.aps {
    position: relative;
    height: 1em;
    top: 1em;
}
.ap {
    background-color: rgba(255,0,0,0.2);
    text-align: center;
    position: absolute;

    font-size: 10px;
    margin-left: 1px;
    height: 1em;
    display: inline-block;
}
    </style>

    @php
        $startFreq = 5650;
        $endFreq = 5925;
        $step = 100 / ($endFreq - $startFreq);

        $currentSite = '';
    $count = 0;
    $scanners = array();

    @endphp
    <div class="spectrum">
        <div class="labels">

            <div class="startFreq" style="left: {{ 0 * $step }}%;">
                {{$startFreq}}
            </div>
            @for ($i = 1; $i < intval(($endFreq-$startFreq)/10)+1; $i++)
                <div class="startFreq" style="left: {{ ($i*10) * $step }}%;">
                    {{$startFreq + ($i*10)}}
                </div>
            @endfor
            <div class="endFreq" style="right: {{ 100- (($endFreq - $startFreq) * $step) }}%;">
                {{--{{$endFreq}}--}}
            </div>

        </div>
        @foreach ( $freq_track as $row)
            @php
                if ( ! isset($scanners[ $row->radio_name]) ) { $scanners[ $row->radio_name] = "<div style=\"position: relative; top: 0em;\">" . $row->radio_name . "</div>"; }
                list($freq, $bw, $mode) = explode("/", $row->channel );
                $draw_freq = $freq;
                if ( strpos($bw,"-") >= 1 ) {
                    list($bw,$ext) = explode("-",$bw);

                    if ($ext == "Ceee") {
                        $draw_freq = $draw_freq + 30;
                        $bw = 80;
                    }
                    if ($ext == "eCee") {
                        $draw_freq = $draw_freq + 10;
                        $bw = 80;
                    }
                    if ($ext == "eeCe") {
                        $draw_freq = $draw_freq - 10;
                        $bw = 80;
                    }

                    if ($ext == "eeeC") {
                        $draw_freq = $draw_freq - 30;
                        $bw = 80;
                    }
                     if ($ext == "Ce") {
                        $draw_freq = $draw_freq + 10;
                        $bw = 40;
                    }
                     if ($ext == "eC") {
                        $draw_freq = $draw_freq - 10;
                        $bw = 40;
                    }
                }
                $lfreq = $draw_freq - ( intval($bw)/2);
                $hfreq = $draw_freq + ( intval($bw)/2);
                if(strpos($row->ssid, 'HamWAN') === false) {
                $scanners[ $row->radio_name] .= "<div class=\"ap\" style=\" left: " . ($lfreq - $startFreq) * $step . "%; width: " .  intval($bw) * $step . "%\"></div>";
                } else {
$scanners[ $row->radio_name] .= "<div class=\"ap\" style=\" top: 1em; background-color: rgb(0,255,0,.2); left: " . ($lfreq - $startFreq) * $step . "%; width: " .  intval($bw) * $step . "%\"></div>";

                }
            @endphp

        @endforeach
        @foreach ( $scanners as $row)
            @php
                            $count++;
            @endphp
            <div class="aps" style="top: {{ $count}}em" id="">
                {!! $row !!}
            </div>
        @endforeach

        <div class="apshw"></div>
        <div class="labels"></div>
    </div>

    <div class="table-responsive">

        <table class="table sortable table-responsive table-condensed table-striped table-bordered">

            <thead>
            <tr>

                <th>SSID</th>
                <th>MAC</th>
                <th>Channel</th>
                <th>Frequency</th>
                <th>Width</th>
                <th>Protocol</th>
                <th>Signal</th>


                <th>Detected By</th>
                <th>Last Heard</th>
            </tr>
            </thead>
            <tbody>
            @foreach ( $freq_track as $row)
                <tr>

                    <td>{{$row->ssid}}</td>
                    <td>{{$row->mac}}</td>
                    <td>@if( $row->frequency >= 5000){{($row->frequency-5000)/5}}@endif</td>
                    @if ( $row->frequency >= 5826 )
                        @if (strpos($row->ssid, 'HamWAN') === false)
                            <td class="danger">{{$row->frequency}}</td>
                        @else
                            <td class="success">{{$row->frequency}}</td>
                        @endif
                    @else
                        <td>{{$row->frequency}}</td>
                    @endif

                    <td>{{$row->channel_width}}</td>
                    <td>{{$row->protocol}}</td>
                    <td style="background-color: {{ $row->strengthColor(0.5) }}">{{$row->signal}}</td>
                    <td>{{$row->radio_name}}</td>
                    <td>{{$row->updated_at}}</td>

                </tr>
            @endforeach
            </tbody>

        </table>
    </div>

</div>