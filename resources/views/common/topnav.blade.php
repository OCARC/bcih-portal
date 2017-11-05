<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">HamWAN Portal  <br>
                @if (explode( "." , $_SERVER['REMOTE_ADDR'])[0] == '44')
                <span style="font-size: 12px; margin-top: -4px; display: block; color: #00CC00">HamWAN : {{$_SERVER['REMOTE_ADDR']}}</span>
                @else
                <span style="font-size: 12px; margin-top: -4px; display: block; color: #cc0000">WAN : {{$_SERVER['REMOTE_ADDR']}}</span>
                    @endif

            </a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="{{ url('/') }}">Home</a></li>
                @if (! Auth::guest())<li><a href="{{ url('/site') }}">Sites</a></li>@endif
                @if (! Auth::guest())<li><a href="{{ url('/users') }}">Users</a></li>@endif
                @if (! Auth::guest())<li><a href="{{ url('/equipment') }}">Equipment</a></li>@endif
                @if (! Auth::guest())<li><a href="{{ url('/clients') }}">Clients</a></li>@endif
                @if (! Auth::guest())<li><a href="{{ url('/keys') }}">Keys</a></li>@endif
                @if (! Auth::guest())<li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">IPs <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ url('/lease-ip') }}">DHCP Leases</a></li>
                        <li><a href="{{ url('/static-ip') }}">Static IPs</a></li>
                    </ul>
                </li>@endif
                @if (! Auth::guest())<li><a href="{{ url('/links') }}">Links</a></li>@endif
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
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
