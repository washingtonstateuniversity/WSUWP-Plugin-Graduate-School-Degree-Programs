(function($, window){
	$( "#add-faculty" ).on( 'click', function() {
		var $faculty_slug = $( "#faculty_slug" );

		var ajax_nonce = $('#gsdp_verify_nonce').val();

		// process slug against people.wsu.edu
		var data = {
			action: 'gsdp_verify_person',
			gsdp_person:  $faculty_slug.val(),
			ajax_nonce: ajax_nonce
		};

		// Make the ajax call
		$.post( window.ajaxurl, data, function( response ) {
			response = JSON.parse( response );

			if ( response.success ) {
				// add slug to existing list display
				// add slug to hidden input containing all slugs
			} else {
				// show an error about invalid user
			}
		});

		// empty input field.
		$faculty_slug.val( "" );
	} );
}(jQuery, window));
