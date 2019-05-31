jQuery( function( $ ) {
	$( '.jwp-dh-start' ).jwpdh();

    var progressbar = $( '#progressbar' );

    progressbar.progressbar( {
        value: false,
	} );
	
	$( '.ccf-check-type' ).on( 'change', function() {
		$( this ).closest( 'td' ).find( 'p' ).text( $( this ).find( 'option:selected' ).data( 'descr' ) );
	} );
	
	$( document ).on( 'jwpdh.start', function( e, event_info ) { 
		var button = event_info.self;
		var post_statuses = [];
		if ( $( '.ccf-post-statuses:checked' ).length ) {
			$( '.ccf-post-statuses:checked' ).each(function(){
				post_statuses.push( $(this).val() );
			} );
		}
        button.data( 'dh-custom', {
			'post_type' : $( '.ccf-post-type' ).val(),
			'post_statuses' : post_statuses,
			'check_type' : $( '.ccf-check-type' ).val()
        } );
		event_info.self.val( event_info.self.data( 'stop' ) );
	} );
	
	$( document ).on( 'jwpdh.stop', function( e, event_info ) { 
		event_info.self.val( event_info.self.data( 'continue' ) );
	} );
	
	$( document ).on( 'jwpdh.continue', function( e, event_info ) { 
		event_info.self.val( event_info.self.data( 'stop' ) );
	} );
	
	$( document ).on( 'jwpdh.responce', function( e, event_info ) { 
		var response = event_info.responce;
		var result = '';
		if ( typeof response.output[0] === 'undefined' ) {
			return;
		}
        if ( 'checked' == response.output[0].status ) {
            result = response.output[0].percent + '%';
        } else if( 'error' == response.output[0].status ) {
            result = response.output[0].error_msg;
        }
        $( '.ccf-results-table tbody' ).prepend(
            '<tr>\
                <td>' + response.output[0].post_title + '</td>\
                <td>' + result + '</td>\
            </tr>'
        );
		if ( $( '.jwp-dh-total' ).length ) {
			$( '.jwp-dh-total' ).text( response.total );
		}
		if ( $( '.jwp-dh-offset' ).length ) {
			$( '.jwp-dh-offset' ).text( response.offset );
		}
        var percent = Math.round( 100 / ( response.total / response.offset ) );
        progressbar.progressbar( 'value', percent );
	} );
	
	$(document).on( 'jwpdh.first_calculate_total', function( e, event_info ) { 
		var response = event_info.responce;
		if ( $( '.jwp-dh-total' ).length ) {
			$( '.jwp-dh-total' ).text( response.total );
		}
        $( '.ccf-results-container' ).show();
        $( '.ccf-bulk-check-settings' ).hide();
        progressbar.progressbar( 'value', 0 );
	} );
	
	$( document ).on( 'jwpdh.finish', function( e, event_info ) { 
		event_info.self.hide();
		$( '.ccf-results-container h3' ).hide();
		progressbar.progressbar( 'value', 100 );
		alert( event_info.self.data( 'finish' ) );
	} );
	
	
} );