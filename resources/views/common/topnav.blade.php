<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">
        HamWAN Portal  <br>
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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/site') }}">Sites</a>
                </li>

                @if (! Auth::guest())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-nowrap" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="mdi mdi-account-group me-1"></span>Auth <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="{{ url('/users') }}"><span class="mdi mdi-account-group"></span>&nbsp;Users</a></li>
                            <li><a class="dropdown-item" href="{{ url('/ldap') }}"><span class="mdi mdi-crop-square"></span>&nbsp;LDAP</a></li>
                            <li><a class="dropdown-item" href="{{ url('/roles') }}"><span class="mdi mdi-crop-square"></span>&nbsp;Roles</a></li>
                            @if (! Auth::guest())
                                <li><a class="dropdown-item" href="{{ url('/keys') }}"><span class="mdi mdi-key"></span>&nbsp;Keys</a></li>
                            @endif

                        </ul>
                    </li>
                @endif

                <li class="nav-item"><a class="nav-link text-nowrap" href="{{ url('/equipment') }}"><span class="mdi mdi-access-point-network me-1"></span>Equipment</a></li>
                <li class="nav-item"><a class="nav-link text-nowrap" href="{{ url('/clients') }}"><span class="mdi mdi-satellite-uplink me-1"></span>Clients</a></li>

                @if (! Auth::guest())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-nowrap" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="mdi mdi-ip"></span> Addressing<span class="caret"></span>
                        </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a class="dropdown-item" href="{{ url('/ips') }}"><span class="mdi mdi-ip"></span>&nbsp;IP Addresses</a></li>
                        <li><a class="dropdown-item" href="{{ url('/subnets') }}"><span class="mdi mdi-ip-network"></span>&nbsp;Subnets</a></li>

                        <li><a class="dropdown-item" href="{{ url('/lease-ip') }}"><span class="mdi mdi-crop-square"></span>&nbsp;DHCP Leases</a></li>
                        <li><a class="dropdown-item" href="{{ url('/static-ip') }}"><span class="mdi mdi-crop-square"></span>&nbsp;Static IPs</a></li>
                        <li><a class="dropdown-item" href="{{ url('/dns-zones') }}"><span class="mdi mdi-crop-square"></span>&nbsp;DNS Zones</a></li>
                        <li><a class="dropdown-item" href="{{ url('/dns-records') }}"><span class="mdi mdi-crop-square"></span>&nbsp;DNS Records</a></li>
                    </ul>
                </li>
                @endif


                @if (! Auth::guest())
                    <li class="nav-item"><a class="nav-link text-nowrap" href="{{ url('/links') }}"><span class="mdi mdi-link me-1"></span>Links</a></li>
                @endif
                @if (! ( ( Auth::user() ) ? Auth::user()->can('view cameras') : false ))
                    <li class="nav-item"><a class="nav-link text-nowrap" href="{{ url('/cameras') }}"><span class="mdi mdi-camera me-1"></span>Cameras</a></li>
                @endif


                @if (! Auth::guest())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-nowrap" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="mdi mdi-wrench"></span> Utilities <span class="caret"></span>
                        </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a class="dropdown-item" href="{{ url('/graphs') }}"><span class="mdi mdi-crop-square"></span>&nbsp;Manage Graphs</a></li>
                        <li><a class="dropdown-item" href="{{ url('/log') }}"><span class="mdi mdi-crop-square"></span>&nbsp;Logs</a></li>
                        <li><a class="dropdown-item" href="{{ url('/utilities/recache-coverages') }}"><span class="mdi mdi-crop-square"></span>&nbsp;Update Coverages Cache</a></li>
                        <li><a class="dropdown-item" href="{{ url('/utilities/routeros-upgrade-manager') }}"><span class="mdi mdi-crop-square"></span>&nbsp;RouterOS Upgrade Manager</a></li>
                        <li><a class="dropdown-item" href="{{ url('/utilities/routeros-config-check') }}"><span class="mdi mdi-crop-square"></span>&nbsp;RouterOS Config Check</a></li>
                        <li><a class="dropdown-item" href="{{ url('/utilities/frequency-planning') }}"><span class="mdi mdi-crop-square"></span>&nbsp;Frequency Planning</a></li>
                    </ul>
                </li>
                @endif

            </ul>
            <ul class="navbar-nav d-flex">

                <!-- Authentication Links -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/aim') }}">Aim</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/map') }}">Map</a>
                </li>

                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>


                        <ul class="dropdown-menu" role="menu">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                            <li>
                                <a  class="dropdown-item" href="{{ route('logout') }}"
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


<div class="container-fluid bg-body">
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
