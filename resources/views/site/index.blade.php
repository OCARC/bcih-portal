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
                                <td>{{ $site->latitude }}, {{ $site->longitude }}</td>
                                <td>{{ $site->altitude }}</td>
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
