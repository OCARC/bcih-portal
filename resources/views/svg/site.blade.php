<svg width="128" height="128" xmlns="http://www.w3.org/2000/svg">
    <!-- Created with Method Draw - http://github.com/duopixel/Method-Draw/ -->

    <g>
        <title>background</title>
        <rect x="-1" y="-1" width="130" height="130" id="canvas_background" fill="none"/>
    </g>
    <g>
        <title>Sectors</title>
        @if( isset($icon['sectors']))
        @foreach( $icon['sectors'] as $sector)
        <g id="svg_3" transform="rotate({{ $sector['azimuth'] }} 64,64) ">
            <ellipse stroke-dasharray="{{ $sector['stroke-dasharray'] or ''}}" fill="{{ $sector['fill'] or '#00ff00'}}" stroke="#000" stroke-width="2" fill-opacity="null" cx="64" cy="32" id="svg_15" rx="42" ry="18"/>
        </g>
        @endforeach
        @endif
    </g>

    <g>
        <title>Links</title>
        @if( isset($icon['links']))
            @foreach( $icon['links'] as $link)
                <g id="svg_3" transform="rotate({{ $link['azimuth'] }} 64,64) ">
                    <ellipse stroke-dasharray="{{ $link['stroke-dasharray'] or ''}}" fill="{{ $link['fill'] or '#00ff00'}}" stroke="#000" stroke-width="2" fill-opacity="null" cx="64" cy="12" id="svg_15" rx="12" ry="12"/>
                </g>
            @endforeach
        @endif
    </g>
    <g>
        <title>Layer 2</title>
        @if( isset($icon['site']))
        <ellipse stroke-width="2" stroke="#000" fill="{{$icon['site']['fill'] or 'white'}}" cx="64" cy="64" id="SITE" rx="32" ry="32"/>
            <text fill="black" x="64" y="74" stroke-width="1" fill-opacity="null" id="svg_1" font-size="31" font-family="monospace" text-anchor="middle" xml:space="preserve" stroke="black">{{$icon['site']['code']}}</text>
        @endif
    </g>
</svg>