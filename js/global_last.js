jQuery(document).ready(function() { 
	jQuery( 'a' ).click( function() {
		this.blur();
	} );
} );

// Fixes issue with IE delayed loading of Cufon 
Cufon.now();