jQuery(document).ready( function(){ 
	simpleSlide({
		'callback' : function() {
			jQuery( '.thrive_hpsldr_left_btn' ).hide();
			jQuery( '.thrive_hpsldr_right_btn' ).hide();
			jQuery( '.simpleSlide-window' ).hover( function() {
				if( '1' == Thrive.can_hover ) {
					jQuery( '.thrive_hpsldr_left_btn' ).fadeIn( 'fast', 'linear' );
					jQuery( '.thrive_hpsldr_right_btn' ).fadeIn( 'fast', 'linear' );
					Thrive.can_hover = '0';
					return;
				}
				
				if( '0' == Thrive.can_hover ) {
					jQuery( '.thrive_hpsldr_left_btn' ).fadeOut( 'fast', 'linear' );
					jQuery( '.thrive_hpsldr_right_btn' ).fadeOut( 'fast', 'linear' );
					Thrive.can_hover = '1';
					return;
				}
			} );
			Cufon.replace('.thrive_hpgal_title', { fontFamily: 'FS Me Heavy' });
			Cufon.replace('.thrive_hpgal_blurb', { fontFamily: 'FS Me' });
			Cufon.replace('.thrive_hpgal_more', { fontFamily: 'FS Me Bold' });
			Cufon.replace('.thrive_hpfea_title', { fontFamily: 'FS Me' });
		}
	});
	
	jQuery( '.thrive_sldr_selector' ).click( function() {
		jQuery( '#thrive_hpsldr_nav .thrive_sldr_selector_selected' ).toggleClass( 'thrive_sldr_selector_selected' );
		jQuery( this ).toggleClass( 'thrive_sldr_selector_selected' );
	} );
	
	jQuery( '.left-button' ).click( function() {
		thrive_nav_select_page( 'prev' );
	} );

	jQuery( '.right-button' ).click( function() {
		thrive_nav_select_page( 'next' );
	} );
});