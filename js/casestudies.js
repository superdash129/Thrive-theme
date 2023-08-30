Cufon.replace('h4', { fontFamily: 'FS Me' } );Cufon.replace('.thrive_title', { fontFamily: 'FS Me' } );Cufon.replace('.thrive_subtitle', { fontFamily: 'FS Me Light' });
jQuery( document ).ready( function() { 
	jQuery( '.thrive_swapbx' ).click( function() {
		var url = jQuery( this ).find( 'form' ).find( 'input' ).val();
		
		if ( 'undefined' === typeof( url ) || 'javascript:void();' == url ) {
			return;
		}
		
		window.open( url );
		//window.location.href = url;
	} );
} );