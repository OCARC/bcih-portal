@extends('common.master')

@section('content')

                <div class="panel panel-default">
                    <div class="panel-heading">Create New Site</div>
                    <div class="panel-body">
                    <form class="" method="POST" action="/sites">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <lable for="name">Site Name</lable>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <lable for="name">Site Code</lable>
                            <input type="text" name="sitecode" maxlength="3" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <lable for="name">Latitude</lable>
                            <input type="text" name="latitude" maxlength="10" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <lable for="name">Longitude</lable>
                            <input type="text" name="longitude" maxlength="10" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success">Create</button>
                    </form>
                    </div>
                </div>
@endsection

