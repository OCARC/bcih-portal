@extends('common.master')
@section('title')
    Equipment
@endsection
@section('content')

    <h2>Equipment: {{ $equipment->hostname }}</h2>

    <div>
<form class="" method="POST" action="/sites">

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="form-group">
        <lable for="name">Hostname</lable>
        <input type="text" name="name" class="form-control" value="{{ $equipment->hostname }}">
    </div>
    <div class="form-group">
        <lable for="name">Management IP</lable>
        <input type="text" name="name" class="form-control" value="{{ $equipment->management_ip }}">
    </div>
    <div class="form-group">
        <lable for="name">Site</lable>
        <input type="text" name="name" class="form-control" value="{{ $equipment->site_id }}">
    </div>

    <div class="form-group">
        <lable for="name">Owner</lable>
        <input type="text" name="name" class="form-control" value="{{ $equipment->owner_id }}">
    </div>

    <div class="form-group">
        <lable for="name">Antenna Height</lable>
        <div class="input-group">
            <input type="text" name="name" class="form-control" value="{{ $equipment->ant_height }}">
            <div class="input-group-addon">meters</div>
        </div>
    </div>
    <div class="form-group">
        <lable for="name">Antenna Azimuth</lable>
        <div class="input-group">
            <input type="text" name="name" class="form-control" value="{{ $equipment->ant_azimuth }}">
            <div class="input-group-addon">&deg;</div>
        </div>
    </div>
    <div class="form-group">
        <lable for="name">Antenna Tilt</lable>
        <div class="input-group">
            <input type="text" name="name" class="form-control" value="{{ $equipment->ant_tilt }}">
            <div class="input-group-addon">&deg;</div>
        </div>
    </div>

    <div class="form-group">
        <lable for="name">Antenna Model</lable>
        <input type="text" name="name" class="form-control" value="{{ $equipment->ant_model }}">
    </div>
    <button type="submit" class="btn btn-success">Save</button>
</form>
        </div>
        @endsection