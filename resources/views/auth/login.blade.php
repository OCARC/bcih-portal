@extends('common.master')




@section('content')
    <script src="{{ url('/js/jsrsasign-all-min.js') }}"></script>

    <script>
        var openFile = function(event) {

            var input = event.target;

            var reader = new FileReader();
            reader.onload = function(){

                var text = reader.result;
                var node = document.getElementById('output');
                var rsa = new RSAKey();
                rsa.readPrivateKeyFromPEMString( text );

                var username = $('#username').val() + "";
                var token = $('input[name=_token]').val() + "";

                var sig =  rsa.sign( token, "sha1");
                $('#password').val(  sig );

                //     $('#postMsg').submit();
            };

            reader.readAsText(input.files[0]);
        };
    </script>



    <div class="container">
    <div class="row">
        <div class="col-8 col-offset-2">
            <div class="card card-default">
                <div class="card-header">Login</div>

                <div class="card-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}


                        <div class="form-group{{ $errors->has('callsign') ? ' has-error' : '' }}">
                            <label for="callsign" class="col-md-4 control-label">Realm</label>

                            <div class="col-md-6">
                                <select id="realm" type="text" class="form-select" name="realm" required>
                                    <option @if( env('APP_AUTO_DEFAULT_REALM',true) === true ) selected="yes" @endif value="auto">Automatic (LDAP First)</option>

                                @if( env('APP_LOCAL_AUTH_ENABLED','false') === true )
                                    <option @if( env('APP_LOCAL_DEFAULT_REALM',true) === true ) selected="yes" @endif value="local">Local Authentication</option>
                                    @endif
                                    @if( env('APP_LDAP_AUTH_ENABLED','false') === true )
                                    <option @if( env('APP_LDAP_DEFAULT_REALM',true) === true ) selected="yes" @endif value="ldap">LDAP Authentication</option>
                                    @endif
                                </select>

                                @if ($errors->has('email'))
                                    <span class="form-text">
                                        <strong>{{ $errors->first('realm') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('callsign') ? ' has-error' : '' }}">
                            <label for="callsign" class="col-md-4 control-label">Callsign</label>

                            <div class="col-md-6">
                                <input id="callsign" type="text" class="form-control" name="callsign" value="{{ old('callsign') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="form-text">
                                        <strong>{{ $errors->first('callsign') }}</strong>
                                    </span>
                                @endif

                                <a class="usePassword btn btn-link" href="#" onClick="jQuery('.usePassword').hide();jQuery('.useKey').show();">
                                    Use key to login
                                </a>
                            </div>
                        </div>




                        <div class="useKey form-group{{ $errors->has('file') ? ' has-error' : '' }}" style="display: none">
                            <label for="password" class="col-md-4 control-label">Private Key File</label>

                            <div class="col-md-6">
                                <input type="file" class="form-control" onchange='openFile(event)'>
                                <p class="form-text">Your private key is used to generate a one time password and is not transmitted to the server.</p>

                                @if ($errors->has('file'))
                                    <span class="form-text">
                                        <strong>{{ $errors->first('file') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="usePassword form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" >
                                <p class="form-text">Passwords are transmitted in plaintext. It is recommended to use a key file to login.</p>

                                @if ($errors->has('password'))
                                    <span class="form-text">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>



                                <a class="useKey btn btn-link" href="#" onclick="jQuery('.usePassword').show();jQuery('.useKey').hide();"  style="display: none">
                                    Use password to login
                                </a>

                                <a class="usePassword btn btn-link" href="#" onClick="jQuery('.usePassword').hide();jQuery('.useKey').show();">
                                    Use key to login
                                </a>

                                <a class="usePassword btn btn-link" href="{{ route('password.request') }}" >
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
