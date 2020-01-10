( function( $ ) {
	$( window ).on( 'load', function() {
		var $sticky = $( '.boostify-sticky-yes' );
		console.log( $sticky );
		if ( $sticky.length != 0 ) {
			
			
			$sticky.each( function ( index ) {
				var sticky = $( this );
				var data = sticky.attr('data-settings');
				console.log(data);
				if ( typeof data != 'undefined' ) {

				}
				var top = sticky.offset().top;
				var left = sticky.offset().left;
				var width = sticky.width();
				console.log(top);

				$(window).scroll(function() {
					if ( $(window).scrollTop() > top ) {

						sticky.addClass( 'show-sticky' );
						sticky.css( { 'left' : left + 'px', 'width' : width + 'px' } );
					}
					else {
						sticky.removeClass('show-sticky');
						sticky.attr('style', '');
					}
				} );
			} )
		}
	});
} )( jQuery );