jQuery( function( $ ) {
    $( document ).on( 'click', '.column-ccf_column .ccf-check-btn', function() {
        let post_id = $( this ).data( 'id' );
        let data = {
            action: 'ccf_check_post_by_id',
            post_id: post_id,
            from: 'column',
            _wpnonce: $( this ).data( 'nonce' ),
            check: $( this ).data( 'check' )
        };

        let column = $( '#post-' + post_id + ' .ccf-column-content' );
        column.html( column.data( 'progress-text' ) + '...' );
        $.post( ajaxurl, data, function( response ) {
            column.html( response );
        } );
        return false;
    } );

    $( document ).on( 'click', '.ccf-check-post-btn', function() {
        let post_id = $( this ).data( 'id' );
        let data = {
            action: 'ccf_check_post_by_id',
            post_id: post_id,
            from: 'metabox',
            _wpnonce: $( this ).data( 'nonce' ),
            check: $( this ).data( 'check' )
        };

        let metabox = $( '#content_copy_finder .inside' );
        metabox.html( $( '#ccf-progress-text' ).val() + '...' );
        $.post( ajaxurl, data, function( response ) {
            metabox.html( response );
        } );
        return false;
    } );

    $( document ).on( 'click', '.ccf-show-matches', function() {
        if ( $( '.ccf-text-matches' ).is( ':visible' ) ) {
            $( this ).text( $( this ).data( 'show' ) );
        } else {
            $( this ).text( $( this ).data( 'hide' ) );
        }
        $( '.ccf-text-matches, .ccf-text-info' ).toggle();
        return false;
    } );

} );