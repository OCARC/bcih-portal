function ajaxAction( target, url ) {

    $(target).hide();
    $(target).closest('.ajaxAction').find('.ajaxResult').html("Please Wait...");

    $.getJSON( url, function( data ) {

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

        $(target).show();

    });
}