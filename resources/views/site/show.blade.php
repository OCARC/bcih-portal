@extends('common.master')

@section('content')


<h2>Site: {{ $site->name }} {{$site->sitecode}}</h2>
            <div>

                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#siteInfo" aria-controls="siteInfo" role="tab" data-toggle="tab">Site Info</a></li>
                    <li role="presentation"><a href="#equipment" aria-controls="equipment" role="tab" data-toggle="tab">Equipment</a></li>
                    <li role="presentation"><a href="#clients" aria-controls="clients" role="tab" data-toggle="tab">Clients</a></li>
                    <li role="presentation"><a href="#graphs" aria-controls="graphs" role="tab" data-toggle="tab">Graphs</a></li>
                    <li role="presentation"><a href="#tools" aria-controls="tools" role="tab" data-toggle="tab">Tools</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="siteInfo">
                        <form class="" method="POST" action="/sites">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <lable for="name">Site Name</lable>
                                <input type="text" name="name" class="form-control" value="{{ $site->name }}">
                            </div>
                            <div class="form-group">
                                <lable for="name">Site Code</lable>
                                <input type="text" name="sitecode" maxlength="3" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-success">Create</button>
                        </form>


                    </div>
                    <div role="tabpanel" class="tab-pane" id="equipment">

                            @include('equipment.list', ['equipment' => $site->equipment ])

                    </div>

                    <div role="tabpanel" class="tab-pane" id="clients">

                        @include('clients.list', ['clients' => $site->clients ])

                    </div>
                    <div role="tabpanel" class="tab-pane" id="tools">...</div>
                </div>

            </div>

@endsection

