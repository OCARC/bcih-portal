<!DOCTYPE html>
<!-- master.blade.php -->
<html lang="en">
<head>
    @include('common.head')
</head>
<body>
@include('common.topnav')
<div id="globalMessages">
    @yield('globalMessages')

</div>
@section('mainContainer')
<div class="container">
    <div class="row">
        <div class="">
        @yield('content')
        </div>
    </div>
</div>
@show

<!-- Scripts -->
{{--<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>--}}
<script src="/js/jquery-3.6.0.min.js"></script>
<script src="{{ url("js/sorttable.js") }}"></script>
<script src="{{ url("js/site.js") }}"></script>
{{--<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>--}}
@yield('scripts')
<script>

    /*

      1. Getting the hash value from current URL
      2. Traversing all tab buttons
      3. checking hash attribute with in each href of tab buttons
      4. if there click on it to perform hash change
       //akhil
     */
    $(".nav-tabs").find("li a").last().click();

    var url = document.URL;
    var hash = url.substring(url.indexOf('#'));

    $(".nav-tabs").find("li button").each(function(key, val) {

        if (hash == $(val).attr('data-bs-target')) {

            $(val).click();

        }
        $(val).click(function(ky, vl) {
            location.hash = $(this).attr('data-bs-target');
        });

    });
</script>
</body>
</html>
