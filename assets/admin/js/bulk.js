jQuery(function($){
	$('.jwp-dh-start').jwpdh();

    var progressbar = $( "#progressbar" );

    progressbar.progressbar({
        value: false,
    });
	
	$(document).on('jwpdh.start', function(e, event_info) { 
        var button = event_info.self;
        button.data( 'dh-custom', {
            'post_type' : $('.ccf-post-type').val()
        } );
		event_info.self.val( event_info.self.data( 'stop' ) );
	});
	
	$(document).on('jwpdh.stop', function(e, event_info) { 
		event_info.self.val( event_info.self.data( 'continue' ) );
	});
	
	$(document).on('jwpdh.continue', function(e, event_info) { 
		event_info.self.val( event_info.self.data( 'stop' ) );
	});
	
	$(document).on('jwpdh.responce', function(e, event_info) { 
		var response = event_info.responce;
        var result = '';
        if ( 'checked' == response.output[0].status ) {
            result = response.output[0].percent + '%';
        } else if( 'error' == response.output[0].status ) {
            result = response.output[0].error_msg;
        }
        $('.ccf-results-table tbody').prepend(
            '<tr>\
                <td>'+response.output[0].post_title+'</td>\
                <td>'+result+'</td>\
            </tr>'
        );
		if ( $('.jwp-dh-total').length ) {
			$('.jwp-dh-total').text(response.total);
		}
		if ( $('.jwp-dh-offset').length ) {
			$('.jwp-dh-offset').text(response.offset);
		}
        var percent = Math.round( 100 / ( response.total / response.offset ) );
        progressbar.progressbar( "value", percent );
	});
	
	$(document).on('jwpdh.first_calculate_total', function(e, event_info) { 
		var response = event_info.responce;
		if ( $('.jwp-dh-total').length ) {
			$('.jwp-dh-total').text(response.total);
		}
        $('.ccf-results-container').show();
        $('.ccf-bulk-check-settings').hide();
        progressbar.progressbar( "value", 0 );
	});
	
	$(document).on('jwpdh.finish', function(e, event_info) { 
		event_info.self.hide();
        $('.ccf-results-container h3').hide();
		alert( event_info.self.data( 'finish' ) );
	});
	
	
});