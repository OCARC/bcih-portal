@extends('common.master')
@section('title')
    Sites
@endsection
@section('content')

    <h2>Keys: Create Key Pair</h2>


                    <form class="" method="POST" action="/sites">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label for="name">Key Pair Name</label>
                            <div class="input-group">
                                <span class="input-group-addon">VA7STV_</span>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name">Owner</label>
                            <input type="text" name="owner" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Public Key</label>
                            <textarea rows=6 class="form-control" name="publicKey" id="publicKey">
                            </textarea>
                            <span id="helpBlock" class="form-text">Either click <a href="javascript:generate()">Generate Keys</a> to generate a new set of keys in the browser or paste the keys you wish to use.</span>
                        </div>

                        <div class="form-group">
                            <label for="name">Private Key</label>
                            <textarea rows=15 class="form-control" name="privateKey" id="privateKey">
                            </textarea>
                            <span id="helpBlock" class="form-text">Click <a href="javascript:download( 'RSAPrivateKey.key', $( '#privateKey' ).text() );">here</a> to download this private key.</span>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" checked="true"> Do NOT send my private key to the server (recomended)
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success">Create</button>
                    </form>
    <script src="/js/jsrsasign-all-min.js"></script>

    <script>
        function generate() {

            var rsaKeypair = KEYUTIL.generateKeypair("RSA", 1024);

            jQuery('#publicKey').text( KEYUTIL.getPEM(rsaKeypair['pubKeyObj']));
            jQuery('#privateKey').text( KEYUTIL.getPEM(rsaKeypair['prvKeyObj'], "PKCS1PRV"));
            jQuery('#keyActions').show();
        }
        function storeKeys( id ) {

            var pack = { pubkey: $('.public').text(), keyname: $('#keyname').text() };
            if ( $('#sendPrivate:checked').length >= 1 ) {
                pack['privkey'] =  $('.private').text();
            }
            {{--$.ajax({--}}
                {{--url: "{{ basePath }}user/" + id + "/ajax-save-key",--}}
                {{--dataType : 'json',--}}
                {{--method: 'POST',--}}
                {{--data: pack--}}

            {{--}).done(function(data) {--}}
                {{--$( '.public' ).html("<div style='color:green'>Key Sent To Server</div>" + $( '.public' ).text() );--}}


            {{--});--}}

            download( 'RSAPrivateKey.key', $( '.private' ).html() );

        }
        function download(filename, text) {
            var pom = document.createElement('a');
            pom.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
            pom.setAttribute('download', filename);

            if (document.createEvent) {
                var event = document.createEvent('MouseEvents');
                event.initEvent('click', true, true);
                pom.dispatchEvent(event);
            }
            else {
                pom.click();
            }
        }
    </script>

@endsection

