jQuery( document ).ready( function( $ ) {
    function check_balance( force = 0 ) {
        let data = {
            action: 'ccf_check_balance',
            force: force,
            _wpnonce: $( '#ccf-check-balance' ).data( 'nonce' )
        };
        $( '.ccf-settings-page .spinner' ).addClass( 'is-active' );
        $.post( ajaxurl, data, function( response ) {
            if ( response.success ) {
                $( '#ccf-balance-block' ).show();
                $( '#ccf-error-block' ).hide();
                $( '#ccf-balance' ).text( response.data.balance + ' ' + response.data.currency );
                $( '#ccf-tarif' ).text( response.data.tarif );
                $( '#ccf-tarif-limit' ).text( response.data.limit );
                $( '.ccf-settings-page .spinner' ).removeClass( 'is-active' );
            } else {
                $( '#ccf-balance-block' ).hide();
                $( '#ccf-error-block' ).show();
                $( '#ccf-error-block strong' ).text( response.data.message );
            }
            
        } );
    }
    if ( $( '#ccf-check-balance' ).length ) {
        $( document ).on( 'click', '#ccf-check-balance', function() {
            check_balance( 1 );
        } );
        check_balance();
    }
} );