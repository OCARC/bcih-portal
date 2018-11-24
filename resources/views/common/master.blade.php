<!DOCTYPE html>
<html lang="en">
<head>
    @include('common.head')
</head>
<body>
@include('common.topnav')
<div class="container">
    <div class="row">
        <div class="">
@yield('content')
</div>
        </div>
    </div>
<!-- Scripts -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="{{ url("js/sorttable.js") }}"></script>
<script src="{{ url("js/site.js") }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
@yield('scripts')

</body>
</html>
