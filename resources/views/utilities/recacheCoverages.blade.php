@extends('common.master')
@section('title')
    Update Coverage Caches
    @endsection
@section('content')


    <h2>Update Coverage Caches</h2>

    <img id="outputImg" onload="dequeue();" onerror="dequeue();" style="max-width: 500px;">
    <div id="output">


    </div>

@endsection

@section('scripts')
    <script>
        var speeds = [
                '1','3','6','10'
        ]
var az = [
        'ALL',
        '000','120','240',
        '000.120','120.240',
        '000.240'
];
var dbs = [
    '000',
    '001',
    '002',
    '003',
    '004',
    '005',
    '006',
    '007',
    '008',
    '009',
    '010',
    '011',
    '012',
    '013',
    '014',
    '015',
    '016',
    '017',
    '018',
    '019',
    '020',
    '021',
    '022',
    '023',
    '024',
    '025',
    '026',
    '027',
    '028',
    '029',
    '030'
];
var queue = [];

        $(document).ready(function() {
            jQuery.getJSON("{{url("/coverages")}}", function(data) {

                speeds.forEach( function(s) {
                for (var k in data) {
                        az.forEach(function (e) {
                            dbs.forEach(function (db) {
                                queue.push(k + '-' + e + "-" + db + ".png?speed=" + s);
                                //= "Updating " + k + " @ " + e + "&deg; - " + db + "dB Client Gain";
                            });
                        });

                }
                });
dequeue();

            });
        });

        function dequeue() {

            var rec = queue.pop();
            $('#output').html('Processing ' + rec + "...");
            $('#outputImg').attr('src', '{{url('/coverages')}}/' + rec + "");
        }

    </script>
    @endsection