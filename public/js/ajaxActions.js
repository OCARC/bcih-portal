function ajaxAction( target, url, callback ) {

    $(target).closest('.ajaxButtons').hide();
    $(target).closest('.ajaxAction').find('.ajaxResult').html("Please Wait...");

    $.getJSON( url, function( data ) {
        var display = "blank";
        if (typeof data['data'] === 'string' || data.data instanceof String) {
            // no processing needed

        } else {

            data['data'] = "<pre>" + JSON.stringify( data['data'], null, 2  ) + "</pre>";
        }

        if ( data.message == 'Unauthenticated.' ) {
            $(target).closest('.ajaxAction').find('.ajaxResult').addClass('text-danger');
            $(target).closest('.ajaxAction').find('.ajaxResult').html( data.message );
            return;
        }

        if ( data.status == 'complete') {
            $(target).closest('.ajaxAction').find('.ajaxResult').html(data.data);
            $(target).closest('.ajaxAction').find('.ajaxResult').removeClass('text-danger');



        } else {
            $(target).closest('.ajaxAction').find('.ajaxResult').addClass('text-danger');
            $(target).closest('.ajaxAction').find('.ajaxResult').html( data.status + " : " + data.reason);
        }


        $(target).closest('.ajaxAction').find('.ajaxResult').find('pre').each( function(k,v) {
            if ( $(v).html() == "") {
                $(v).remove();
            }
        })



        if ( $(target).closest('.ajaxAction').find('.ajaxResult').find('pre').length == 0 ) {
            $(target).closest('.ajaxAction').find('.ajaxResult').html('Action Returned A Blank Result');
        }

        $(target).closest('.ajaxButtons').show();

        callback(target,data);
    });
}