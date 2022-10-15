<form class="" method="POST" action="{{ url("/site") }}">

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $site->id }}">

    <div class=" col-12">
        <div class="card card-default m-2">
        <div class="card-header">
            Site Information
        </div>
        <div class="card-body row">

            <div class="col-12">
                <label class="fw-bold col-form-label" for="name">Site Name</label>
                <input type="text" name="name" class="form-control" value="{{ $site->name }}">
            </div>
            <div class="col-4">
                <label class="fw-bold col-form-label" for="name">Site Code</label>
                <input type="text" maxlength="3" name="sitecode" class="form-control" value="{{ $site->sitecode }}">

            </div>


            <div class="col-4">
                <label class="fw-bold col-form-label" for="name">Status</label>
                <select name="status" class="form-select" required>
                    <option value=""></option>
                    <option @if( $site->status == "Potential") selected="true" @endif style="background-color: #e1e1e1">
                        Potential
                    </option>
                    <option @if( $site->status == "Planning") selected="true" @endif style="background-color: #fff6a6">
                        Planning
                    </option>
                    <option @if( $site->status == "Installed") selected="true" @endif style="background-color: #aaffaa">
                        Installed
                    </option>
                    <option @if( $site->status == "Equip Failed") selected="true"
                            @endif style="background-color: #ff6666">
                        Equip Failed
                    </option>
                    <option @if( $site->status == "Problems") selected="true" @endif style="background-color: #ffd355">
                        Problems
                    </option>
                    <option @if( $site->status == "No Install") selected="true" @endif style="background-color: #979797"
                            value="No Install">No Install - Equipment will never be installed
                    </option>

                </select></div>

            <div class="col-4">
                <label class="fw-bold col-form-label" for="name">Owner</label>
                <select name="user_id" class="form-select" required>
                    <option value="0"></option>
                    @foreach( $users as $user)
                        <option @if ($site->user_id == $user->id) selected="true"
                                @endif value="{{ $user->id }}">{{$user->name}} ({{$user->callsign}})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    </div>
    <div class=" col-12">
        <div class="card card-default m-2">
        <div class="card-header">
            Site Location
        </div>
        <div class="card-body row">

            <div class="col-3">
                <label class="fw-bold col-form-label" for="name">Altitude</label>
                <div class="input-group">
                    <input type="text" name="altitude" class="form-control" value="{{ $site->altitude }}">
                    <div class="input-group-addon">meters</div>
                </div>
                <p class="form-text">Ground above sea level</p>

            </div>
            <div class="col-3">
                <label class="fw-bold col-form-label" for="name">Latitude</label>
                <div class="input-group">
                    <input type="number" step="0.000001" name="latitude" class="form-control"
                           value="{{ $site->latitude }}">
                    <div class="input-group-addon">&deg;</div>
                </div>
            </div>
            <div class="col-3">
                <label class="fw-bold col-form-label" for="name">Longitude</label>
                <div class="input-group">
                    <input type="number" step="0.000001" name="longitude" class="form-control"
                           value="{{ $site->longitude }}">
                    <div class="input-group-addon">&deg;</div>
                </div>
            </div>

            <div class="col-3">
                <label class="fw-bold col-form-label" for="map_visible">Map Visibility</label>
                <select name="map_visible" class="form-select">
                    <option value=""></option>
                    <option value="yes" @if( $site->map_visible == "yes") selected="true" @endif>
                        Yes (public)
                    </option>
                    <option value="hide" @if( $site->map_visible == "hide") selected="true" @endif>
                        Hide
                    </option>


                </select></div>

        </div>

    </div>
    </div>
    <div class=" col-12">

    @include('common.rolesForm', ['target' => $site])
    </div>

    <div class=" col-12">
        <div class="card card-default m-2">
            <div class="card-header">
                Notations
            </div>
            <div class="card-body row">
                <div class="col-4">
                    <label class="fw-bold col-form-label" for="comments">Comments</label>
                    <textarea rows=5 name="comments" class="form-control">{{ $site->comments }}</textarea>
                </div>
                <div class="col-4">
                    <label class="fw-bold col-form-label" for="name">Description</label>
                    <textarea rows=5 name="description" class="form-control"  aria-describedby="descriptionHelp">{{ $site->description }}</textarea>
                    <small class="text-muted" id="descriptionHelp">This content may be used to describe the site publicly</small>
                </div>


                <div class="col-4">
                    <label class="fw-bold col-form-label" for="name">Access Notes</label>
                    <textarea rows=5 name="access_note" class="form-control">{{ $site->access_note }}</textarea>
                </div>

                <div class="col-12">
                    <label class="fw-bold col-form-label" for="name">HTML</label>
                    <div id="html_editor">
                    <div id="html_content"></div>
                    </div>
                    <textarea rows=5 name="html" id="html" class="form-control" style="">{{ $site->html }}</textarea>
                    <small class="text-muted" id="descriptionHelp">This content can be seen publicly</small>

                </div>
                <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

                <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.25.2/trumbowyg.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.25.2/plugins/fontsize/trumbowyg.fontsize.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.25.2/plugins/fontfamily/trumbowyg.fontfamily.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.25.2/plugins/colors/trumbowyg.colors.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.25.2/plugins/table/trumbowyg.table.min.js"></script>

                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.25.2/ui/trumbowyg.min.css">

                <script>(function() {
                        // your page initialization code here
                        // the DOM will be available here
                        $('#html').trumbowyg({
                            resetCss: true,
                            semantic: {
                                'div': 'div' // Editor does nothing on div tags now
                            },
                            btns: [
                                ['viewHTML'],
                                ['undo', 'redo'], // Only supported in Blink browsers
                                ['formatting'],
                                ['strong', 'em', 'del'],
                                ['superscript', 'subscript'],
                                ['link'],
                                ['insertImage'],
                                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                                ['unorderedList', 'orderedList'],
                                ['horizontalRule'],
                                ['removeformat'],
                                ['fullscreen'],
                                ['fontsize'],['fontfamily'],['foreColor', 'backColor'],['table']
                            ],

                        });

                    })();


                </script>
            </div>
        </div>
    </div>


    <button type="submit" class="btn btn-success">@if( $site->id ) Save Site @else Create Site @endif</button>
    <button type="cancel" class="btn btn-danger"> Cancel</button>
</form>