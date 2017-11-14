@extends('common.master')
@section('title')
Sites
@endsection
@section('content')

<h2>Sites</h2>

                        <table class="table table-responsive table-condensed table-striped">

                            <thead>
                            <tr>
                                <th>Site Name</th>
                                <th>Site Code</th>
                                <th>Location</th>
                                <th>Altitude</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($sites as $site)
                            <tr>
                                <td><a href="{{ url("site/" . $site->id ) }}">{{ $site->name }}</td>
                                <td>{{ $site->sitecode }}</td>
                                @if (! Auth::guest())
                                <td><a href="http://www.google.com/maps/?q={{ $site->latitude }},{{ $site->longitude }}">{{ $site->latitude }}, {{ $site->longitude }}</a></td>
                                        @else
                                    <td>n/a</td>
@endif
                                    <td class="text-right">{{ $site->altitude }} meters</td>
                            </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="3">
                                    <a href="{{ url("/site/create") }}">create new site</a>
                                </td>
                            </tr>
                            </tfoot>
                        </table>


@endsection
