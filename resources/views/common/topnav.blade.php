<nav class="navbar navbar-inverse" style="">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">HamWAN Portal  <br>
                @if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR']) )
                    @if (explode( "." , $_SERVER['REMOTE_ADDR'])[0] == '44')
                    <span style="font-size: 12px; margin-top: -4px; display: block; color: #00CC00">44Net : {{ ( $_SERVER['HTTP_X_FORWARDED_FOR'] )  ?  $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'] }}</span>
                    @else
                    <span style="font-size: 12px; margin-top: -4px; display: block; color: #cc0000">WAN : {{ ( $_SERVER['HTTP_X_FORWARDED_FOR'] )  ?  $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'] }}</span>
                    @endif

                @else
                    @if (explode( "." , $_SERVER['REMOTE_ADDR'])[0] == '44')
                        <span style="font-size: 12px; margin-top: -4px; display: block; color: #00CC00">44Net : {{ $_SERVER['REMOTE_ADDR'] }}</span>
                    @else
                        <span style="font-size: 12px; margin-top: -4px; display: block; color: #cc0000">WAN : {{  $_SERVER['REMOTE_ADDR'] }}</span>
                    @endif
                @endif

            </a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                @php
                    $sites = \App\Site::all()->sortBy('sitecode');
                @endphp
                <li><a href="{{ url('/site') }}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="mdi mdi-radio-tower"></span> Sites <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ url('/site') }}">List All</a></li>
                        <li class="divider"></li>
                        @foreach( $sites as $site )
                            <li style="background: {{$site->statusColor() }}; "><a href="{{ url('/site/' . $site->id ) }}">
                                    <span style="font-family: monospace;">{{ $site->sitecode }}</span>&nbsp;&nbsp;{{ $site->name }}</a></li>
                        @endforeach
                    </ul>

                </li>
                @if (! Auth::guest())
                <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="mdi mdi-account-group"></span> Users <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ url('/users') }}">Users</a></li>
                        <li><a href="{{ url('/ldap') }}">LDAP</a></li>

                        <li><a href="{{ url('/roles') }}">Roles</a></li>

                    </ul>
                </li>@endif
                <li><a href="{{ url('/equipment') }}"><span class="mdi mdi-access-point-network"></span> Equipment</a></li>
                <li><a href="{{ url('/clients') }}"><span class="mdi mdi-satellite-uplink"></span> Clients</a></li>
                @if (! Auth::guest())<li><a href="{{ url('/keys') }}"><span class="mdi mdi-key"></span> Keys</a></li>@endif
                @if (! Auth::guest())<li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="mdi mdi-ip"></span> IPs <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ url('/ips') }}"><span class="mdi mdi-ip"></span> IP Addresses</a></li>
                        <li><a href="{{ url('/subnets') }}"><span class="mdi mdi-ip-network"></span> Subnets</a></li>

                        <li><a href="{{ url('/lease-ip') }}">DHCP Leases</a></li>
                        <li><a href="{{ url('/static-ip') }}">Static IPs</a></li>

                    </ul>
                </li>@endif
                @if (! Auth::guest())<li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="mdi mdi-dns"></span> DNS <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ url('/dns-zones') }}">DNS Zones</a></li>
                        <li><a href="{{ url('/dns-records') }}">DNS Records</a></li>

                    </ul>
                </li>@endif
                @if (! Auth::guest())<li><a href="{{ url('/links') }}"><span class="mdi mdi-link"></span> Links</a></li>@endif

            @if (! Auth::guest())<li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="mdi mdi-wrench"></span> Utilities <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ url('/graphs') }}">Manage Graphs</a></li>
                        <li><a href="{{ url('/log') }}">Logs</a></li>
                        <li><a href="{{ url('/utilities/recache-coverages') }}">Update Coverages Cache</a></li>
                        <li><a href="{{ url('/utilities/routeros-upgrade-manager') }}">RouterOS Upgrade Manager</a></li>
                        <li><a href="{{ url('/utilities/routeros-config-check') }}">RouterOS Config Check</a></li>
                        <li><a href="{{ url('/utilities/frequency-planning') }}">Frequency Planning</a></li>
                    </ul>
                </li>@endif
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                <li><a href="{{ url('/aim') }}">Aim</a></li>
                <li><a href="{{ url('/map') }}">Map</a></li>

                @guest
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Register</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu">
                            <li>
                                {{--<a href="{{ route('my-account') }}">--}}
                                    {{--My Account--}}
                                {{--</a>--}}

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endguest
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <div class="row">
@if ( Session::has('msg') )
<div class="alert alert-warning">
    {!! Session::has('msg') ? Session::get("msg") : '' !!}
</div>
    @endif
    @if ( Session::has('success') )
        <div class="alert alert-success">
            {!! Session::has('success') ? Session::get("success") : '' !!}
        </div>
    @endif
</div>
    </div>
