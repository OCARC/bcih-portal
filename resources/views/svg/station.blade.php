<svg width="128" height="128" xmlns="http://www.w3.org/2000/svg">

        <rect fill="none" id="canvas_background" height="130" width="130" y="-1" x="-1"/>

        @if( isset($icon['client']) )

        <ellipse ry="20" rx="20" id="svg_1" cy="64" cx="64" stroke-opacity="null" stroke-width="1.5" stroke="#000" fill="{{ $icon['client']['fill'] or "#ff0000" }}"/>
        <text fill="black" x="64" y="106" stroke-width="1" fill-opacity="null" id="svg_1" font-size="20" font-family="arial" text-anchor="middle" xml:space="preserve" stroke="black">{{$icon['client']['sysName']}}</text>

    @endif
</svg>