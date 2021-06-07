jQuery(function ($) {
    'use strict';
    $( document ).on( 'click', '.google-login-js' , function(){
        var google_params = {
            'clientid' : ajax_googleplus_oauth_object.google_oauth_id,
            'cookiepolicy' : 'single_host_origin',
            'callback' : 'OnGoogleAuth',
            'scope' : 'email profile'
        };
        gapi.auth.signIn( google_params );
        return false;
    });
    function GoggleOnLoad() {
        gapi.client.setApiKey( ajax_googleplus_oauth_object.google_api_key );
        gapi.client.load( 'plus', 'king', function(){} );
    }

});
function OnGoogleAuth( googleUser ) {
    var container    = jQuery( this ).closest('.page-site-main');

    var redirect_url = ajax_googleplus_oauth_object.login_redirect_url;

    if(googleUser['status']['signed_in'] && googleUser['status']['method'] == 'PROMPT') {

        jQuery.post(
            ajax_googleplus_oauth_object.ajaxurl,
            {
                'action': 'king_googleplus_oauth_callback',
                'social_type': 'google',
                'access_token': googleUser.access_token,
                '_nonce': ajax_googleplus_oauth_object.nonce
            }, function( googleplus ) {
                var me = jQuery.parseJSON( googleplus );
                if( me.error != '' ) {
                    return container.find( 'p.status' ).show().text( me.error );
                } else {
                    document.location.href = redirect_url;
                }
            }
        );
    }
}