(function($, _, window){
	var $faculty_slug = $( "#faculty_slug" );
	var faculty_template = $( "#faculty-template" ).html();

	function remove_faculty_member( e ) {
		$( e.target ).parent().remove();
	}

	$( "#add-faculty" ).on( 'click', function() {

		var request_url = "https://people.wsu.edu/wp-json/wp/v2/people?filter[name]=" + $faculty_slug.val();

		$.get( request_url ).
			fail( function() {

			} ).
			done( function( data, textStatus, request ) {
				if ( 1 != request.getResponseHeader("X-WP-Total") ) {
				} else {
					var faculty = {
						id: data[0].id,
						slug: data[0].slug,
						first_name: data[0].first_name,
						last_name: data[0].last_name
					};

					_.templateSettings.variable = "faculty";

					$( ".faculty_added" ).append( _.template( faculty_template )( faculty ) );
					$( ".remove-faculty" ).off( "click", remove_faculty_member ).on( "click", remove_faculty_member );
				}
			} );

		// empty input field.
		$faculty_slug.val( "" );
	} );

	$( ".remove-faculty" ).on( "click", remove_faculty_member );
}(jQuery, _, window));
