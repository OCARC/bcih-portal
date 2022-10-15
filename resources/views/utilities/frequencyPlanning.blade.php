@extends('common.master')
@section('title')
    Frequency Planning
@endsection
@section('content')


    <h2>Frequency Planning</h2>


<h3>5Ghz</h3>
    @php
        $startFreq = 5650;
        $endFreq = 5925;
        $step = 100 / ($endFreq - $startFreq);

        $currentSite = '';
    @endphp

        <div class="frequencyChart">
            <div class="bands">
                <div class="freqBand" style="left: {{ (5725 - $startFreq) * $step }}%; right: {{ ( $endFreq - 5850 ) * $step }}%;">
                    RADIOLOCATION, Amateur, 5.150 C39A
                </div>
                <div class="freqBand" style="left: {{ (5850 - $startFreq) * $step }}%; right: {{ ( $endFreq - 5925 ) * $step }}%;">
                    FIXED, FIXED-SAT, MOBILE, Amateur, 5.150 C39A
                </div>
            </div>

            <div class="key">
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



            @foreach ($sites as $site)
                <div class="equipment">
                    <div class="siteName">
                    {{$site->name}}
                    </div>
                    @foreach ($site->equipment as $row)

                    @if( $row->snmp_band == '' )
                        @continue
                        @endif
                        @php

                            list($freq, $bw, $mode) = explode("/", $row->snmp_band );
                            $class = "";

                            if ( $row->snmp_ssid == 'HamWAN') {
                              $class .= " sector ";
                            }
                            if ( strpos( $row->snmp_ssid, 'HamWAN-PTP') !== false ) {
                              $class  .= " link ";
                            }
                            $lfreq = $freq - ( intval($bw)/2);
                            $hfreq = $freq + ( intval($bw)/2);

                        @endphp

                        <div class="freqEquip {{$class}}" style=" left: {{ ($lfreq - $startFreq) * $step }}%; width: {{ intval($bw) * $step }}%">
                            {{$row->hostname}}<br>
                        </div>

                    @endforeach


                </div>


        @endforeach
            <div class="key" style="border-top: 1px solid grey">
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

    </div>






    @php
        $startFreq = 5100;
        $endFreq = 5925;
        $step = 100 / ($endFreq - $startFreq);

        $currentSite = '';
    @endphp


    <script>

        var channels = {
            10: [
                {
                    'number': 7,
                    'status': 'no',
                    'center': 5035,
                    'bandwidth': 10,
                    'dfs': false,
                    'band': 'N/A'
                }

            ]
        }

    </script>
    <div class="frequencyChart" style="width: 3000px">
        <div class="channels">
            <div class="chan" style="left: {{ (5170 - $startFreq) * $step }}%; right: {{ ( $endFreq - 5250 ) * $step }}%;">
                U-NII-1 Indoors
            </div>
            <div class="chan" style="left: {{ (5260 - $startFreq) * $step }}%; right: {{ ( $endFreq - 5330 ) * $step }}%;">
                U-NII-2A DFS
            </div>
            <div class="chan" style="left: {{ (5490 - $startFreq) * $step }}%; right: {{ ( $endFreq - 5590 ) * $step }}%;">
                U-NII-2C DFS
            </div>

        </div>
        <div class="bands">
            <div class="freqBand" style="left: {{ (5725 - $startFreq) * $step }}%; right: {{ ( $endFreq - 5850 ) * $step }}%;">
                RADIOLOCATION, Amateur, 5.150 C39A
            </div>

            <div class="freqBand" style="left: {{ (5850 - $startFreq) * $step }}%; right: {{ ( $endFreq - 5925 ) * $step }}%;">
                FIXED, FIXED-SAT, MOBILE, Amateur, 5.150 C39A
            </div>
        </div>
        <div class="bands">
            <div class="freqBand" style="left: {{ (5725 - $startFreq) * $step }}%; right: {{ ( $endFreq - 5825 ) * $step }}%;">
                C39A - The band 5725–5825MHz is designated for use bylicence-exempt wireless local area networks and devices with established maximum power levels and based upon not interfering with, or claiming protection from, licensed services.
            </div>
            <div style="clear: both;"></div>
        </div>
        <div class="key">
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



            <div class="equipment">
                <div class="siteName">
                    Others
                </div>
                @foreach ($freqtrack as $row)


                    @php

                        list($freq, $bw, $mode) = explode("/", $row->channel );
                        $class = "";

                          $class .= " other ";

if ( $row->protocol == 'nstreme') {
                          $class = " nstreme ";

}
if ( $row->protocol == 'nv2') {
                          $class = " nv2 ";

}
                        $lfreq = $freq - ( intval($bw)/2);
                        $hfreq = $freq + ( intval($bw)/2);

                    @endphp

                    <div class="freqEquip {{$class}}" style=" left: {{ ($lfreq - $startFreq) * $step }}%; width: {{ intval($bw) * $step }}%">
                        {{$row->ssid}}<br>ch {{ ($freq-5000)/5 }}
                    </div>

                @endforeach


            </div>


        <div class="key" style="border-top: 1px solid grey">
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

    </div>

@endsection

@section('scripts')

<style>
    .frequencyChart {
        position: relative;
        overflow: hidden;
    }
    .frequencyChart .equipment {
        position: relative;
        border-top: 1px solid grey;
        min-height: 22px;
    }
    .channels {
        height:22px;
    }
    .bands {
        height:22px;
    }
    .freqBand {
        text-align: center;
        height:22px;
        position: absolute;
        line-height: 2em;
        font-size: 10px;
        margin-left: 1px;
        border-left: 1px solid black;
        border-right: 1px solid black;
    }

    .chan {
        text-align: center;
        height:22px;
        position: absolute;
        line-height: 2em;
        font-size: 10px;
        margin-left: 1px;
        border-left: 1px solid black;
        border-right: 1px solid black;
    }
    .freqEquip {
        text-align: center;
        position: relative;
        line-height: 2em;
        font-size: 10px;
        margin-left: 1px;
    }
    .frequencyChart .key {
        height: 1em;
    }
    .endFreq {
        text-align: right;
        position: absolute;
        font-size: 10px;
        border-right: 1px dotted grey;
        padding-right: 5px;
        height: 3000px;
    }
    .startFreq {
        text-align: left;
        position: absolute;
        font-size: 10px;
        border-left: 1px dotted grey;
        padding-left: 5px;
height: 3000px;
    }
        .siteName {
            position: absolute;
            left: 1px;
            top: 1px;
        padding: 2px;
        color: black;
            font-size: 10px;
            background: rgba(255,255,255,0.75)
    }

    .freqEquip.link {
        background-color: rgba(91, 192, 222, 0.50)
    }
    .freqEquip.sector {
        background-color: rgba(100, 222, 0, 0.5)
    }
    .freqEquip.other {
        background-color: rgb(255, 189, 92);
        outline: 1px solid rgb(109, 44, 0);
        line-height: 1.2em;
        cursor: pointer;
        margin-bottom: -10px;
    }
    .freqEquip.nstreme {
        background-color: rgb(130, 228, 255);
        outline: 1px solid rgb(24, 154, 255);
        line-height: 1.2em;
        cursor: pointer;
        margin-bottom: -10px;
    }
    .freqEquip.nv2 {
        background-color: rgb(255, 175, 169);
        outline: 1px solid rgb(255, 107, 170);
        line-height: 1.2em;
        cursor: pointer;
        margin-bottom: -10px;
    }
    .freqEquip:hover {
        opacity: 1;
        z-index: 1999;
        background: white;
    }
</style>
@endsection
