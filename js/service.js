Cufon.replace('h4', { fontFamily: 'FS Me' } );Cufon.replace('.thrive_title', { fontFamily: 'FS Me' } );Cufon.replace('.thrive_subtitle', { fontFamily: 'FS Me Bold'});

function click_casestudy() {
	var url = jQuery( this ).find( 'form' ).find( 'input' ).val();
	
	if ( 'undefined' === typeof( url ) || 'javascript:void();' == url ) {
		return;
	}
	
	window.open( url );
}

/* 
 * @param direction - next|prev increments or decrements the global page number for the gallery. 
 * Next increases the page number and prev decreases the page number. 
 * If the page number is 1 then prev does nothing.
 * If the page number is greater then the global max pages then next does nothing.
*/
function get_gallery_page( direction ) {
	var cache_count = jQuery( '#thrive_gallery_viewport .thrive_gallery_slide' ).length;

	// Reasons to do nothing
	// Test: for valid direction = prev|next
	if ( ! ( 'prev' == direction || 'next' == direction ) ) {
		return;
	}
	
	// Test: on the first page and asking for the previous direction
	if ( 'prev' == direction && 1 == Thrive.gallery_page ) {
		return;
	}
	
	// Test: on the last page and asking for the next direction
	if ( 'next' == direction && Thrive.gallery_page == Thrive.max_pages ) {
		return;
	}
	
	
	// Actually increment the page we are on
	Thrive.gallery_page = ( 'next' == direction ) ? 1 + parseInt( Thrive.gallery_page ) : parseInt( Thrive.gallery_page ) - 1;
	
	if ( 1 == Thrive.gallery_page ) {
		// disable prev
		jQuery( '#thrive_prev div' ).removeClass( 'thrive_pagination_prev' );
		jQuery( '#thrive_prev div' ).addClass( 'thrive_pagination_prev_disabled' );
		// enable next
		jQuery( '#thrive_next div' ).removeClass( 'thrive_pagination_next_disabled' );
		jQuery( '#thrive_next div' ).addClass( 'thrive_pagination_next' );
	} else if ( Thrive.max_pages == Thrive.gallery_page ) {
		// disable next
		jQuery( '#thrive_next div' ).removeClass( 'thrive_pagination_next' );
		jQuery( '#thrive_next div' ).addClass( 'thrive_pagination_next_disabled' );
		
		// enable prev
		jQuery( '#thrive_prev div' ).removeClass( 'thrive_pagination_prev_disabled' );
		jQuery( '#thrive_prev div' ).addClass( 'thrive_pagination_prev' );
	} else {
		// enable both prev and next!
		jQuery( '#thrive_next div' ).removeClass( 'thrive_pagination_next_disabled' );
		jQuery( '#thrive_next div' ).addClass( 'thrive_pagination_next' );
		
		// enable prev
		jQuery( '#thrive_prev div' ).removeClass( 'thrive_pagination_prev_disabled' );
		jQuery( '#thrive_prev div' ).addClass( 'thrive_pagination_prev' );
	}
	
	
	if ( 'prev' == direction ) { // "previous" page requests are always in cache since we start at the beginning...a very good place to start.
		jQuery( '#thrive_gallery_slides' ).animate( { "left" : "+=960px" }, "slow" );
		return;
	}
	
	if ( 'next' == direction && Thrive.gallery_page <= cache_count ) { // "next" page request is in cache
		jQuery( '#thrive_gallery_slides' ).animate( { "left" : "-=960px" }, "slow" );
		return;	
	}
	
	// "next" page request is not in cache; call out to DB
	jQuery( '#thrive_gallery_wait_indicator' ).show();
	jQuery.post(
		Thrive.ajaxurl,
		{
			action : 'thrive-gallery-page',
			page : Thrive.gallery_page.toString(),
			exclude : Thrive.gallery_exclude
		},
		function( jsonString ) {
			var response = JSON.parse( jsonString );
			if ( response.success ) {
				jQuery( '#thrive_gallery_slides' ).append( '<div class="thrive_tpad30 thrive_gallery_slide">' + response.page_result + '</div>' ).show();
			} else {
				jQuery( '#thrive_gallery_slides' ).append( '<div class="thrive_tpad30 thrive_gallery_slide">Error loading case studies. Please try again later.</div>' ).show();
			}
		},
		'html'
	)
	.error( function() {
		jQuery( '#thrive_gallery_slides' ).append( '<div class="thrive_tpad30 thrive_gallery_slide">Error loading case studies. Please try again later.</div>' ).show();
	} )
	.complete( function() {
		jQuery( '#thrive_gallery_slides' ).width( function( i, w ) { return w + 960; } );
		jQuery( '#thrive_gallery_slides' ).animate( { "left" : "-=960px" }, "slow" );
		jQuery( '.thrive_swapbx' ).unbind( 'click', click_casestudy );
		jQuery( '.thrive_swapbx' ).bind( 'click', click_casestudy ); 
		jQuery( '#thrive_gallery_wait_indicator' ).hide();
	} );
}

jQuery(document).ready(function($) {
	$( '#thrive_next' ).click( function( evnt ) {
		evnt.preventDefault();
		get_gallery_page( 'next' );
	} );
	$( '#thrive_prev' ).click( function( evnt ) {
		evnt.preventDefault();
		get_gallery_page( 'prev' );
	} );

	jQuery( '.thrive_swapbx' ).bind( 'click', click_casestudy );
	jQuery( '#thrive_gallery_wait_indicator' ).hide();
});