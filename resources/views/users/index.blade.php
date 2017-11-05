@extends('common.master')
@section('title')
    Users
@endsection
@section('content')

                    <h2>@yield('title')</h2>

                        <table class="table table-responsive table-condensed table-striped">

                            <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Callsign</th>
                                <th>Approved</th>
                                <th>Altitude</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->callsign }}</td>
                                <td>{{ $user->approved }}</td>
                                <td>{{ $user->altitude }}</td>
                            </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="3">
                                    <a href="{{ url("/users/create") }}">create new user</a>
                                </td>
                            </tr>
                            </tfoot>
                        </table>


@endsection
