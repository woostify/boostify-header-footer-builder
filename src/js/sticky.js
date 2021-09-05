(function($) {
	var $ = jQuery.noConflict();

	if ( window.top != window.self ) {
		// window.elementorEditor = true;
		$( window ).on(
			'load',
			function() {
				"use strict";
				stickyHeaderEditer();
			}
		);
	} else {
		$( window ).on(
			'load',
			function() {
				"use strict";
				stickyHeader();
			}
		);
	}



	function stickyHeader() {
		var $sticky = $( '.boostify-sticky-yes' );
		if ( $sticky.length != 0 ) {
			$sticky.each(
				function ( index ) {
					var sticky   = $( this );
					var copyElem = sticky.siblings( '.elementor-section' );
					if ( copyElem.hasClass( 'elementor-sticky--active' ) || ( copyElem.hasClass( 'elementor-sticky--active' ) && copyElem.hasClass( 'she-header-yes' ) ) ) {
						return;
					}
					var bodyTop     = $( 'body' ).offset().top;
					var top         = sticky.offset().top;
					var left        = sticky.offset().left;
					var width       = sticky.width();
					var height      = sticky.height();
					var widthScreen = $( window ).width();
					var data        = sticky.attr( 'data-settings' );
					data            = JSON.parse( data );
					var screen      = data.bstick_on;
					var wrap        = sticky.parents( '.elementor-section-wrap' );
					var element     = sticky.html();
					var classSticky = sticky.attr( 'class' );
					var margin      = data.bsticky_distance;
					var logo        = sticky.find( '.custom-logo' );
					var logoUrl     = logo.attr( 'src' );
					var logoStick   = data.image.url;
					var menuColor   = data.bsticky_menu_color;
					var tranparent  = sticky.hasClass( 'boostify-header-tranparent-yes' );
					if ( widthScreen >= 1025 ) {
						var enabled = 'desktop';
					} else if ( widthScreen > 767 && widthScreen < 1025 ) {
						var enabled = 'tablet';
					} else if ( widthScreen <= 767 ) {
						var enabled = "mobile";
					}
					if ( screen.includes( enabled ) ) {
						if ( ! tranparent ) {
							var coppyClass = 'boostify-header--default elementor-section';
							if ( sticky.hasClass( 'elementor-hidden-phone' ) ) {
								coppyClass += ' elementor-hidden-phone';
							}

							if ( sticky.hasClass( 'elementor-hidden-tablet' ) ) {
								coppyClass += ' elementor-hidden-tablet';
							}

							if ( sticky.hasClass( 'elementor-hidden-desktop' ) ) {
								coppyClass += ' elementor-hidden-desktop';
							}

							sticky.after(
								'<section class="' + coppyClass + '">' +
								element +
								'</section>'
							);

							var copy = sticky.siblings( '.boostify-header--default' );

							copy.css(
								{
									'visibility' : 'hidden',
									'transition' : 'none 0s ease 0s',
								}
							);
						}

						sticky.css(
							{
								'top' : top + 'px',
								'position' : 'fixed',
								'left' : left + 'px',
								'width' : width + 'px',
							}
						);
						var copyMargin = top + margin.size;
						if ( $( window ).scrollTop() > top ) {
							sticky.css( {'top' : bodyTop + 'px' } );
							if ( menuColor && menuColor !== '' ) {
								sticky.find( '.boostify-menu > li > a' ).css( { 'color' : menuColor } );
							}
							if ( logoStick != '' ) {
								logo.attr( 'src', logoStick );
							} else {
								logo.attr( 'src', logoUrl );
							}
							sticky.addClass( 'boostify-sticky--active' );
							sticky.css( {'background-color' : data.bsticky_background } );
						} else {
							var defaultTop = sticky.offset().top;
							sticky.css( 'top', defaultTop + 'px' );
							if ( ( top - $( window ).scrollTop() ) > 0 ) {
								sticky.css( {'top': ( top - $( window ).scrollTop() ) + 'px'} );
							}
						}

						$( window ).scroll(
							function() {
								var scroll = $( window ).scrollTop();

								if ( ( top - scroll ) >= 0 ) {
									sticky.css( {'top': ( top - scroll ) + 'px'} );

									sticky.removeClass( 'boostify-sticky--active' );
									sticky.css( { "background-color" : '' } );
									logo.attr( 'src', logoUrl );
									sticky.find( '.boostify-menu > li > a' ).css( { 'color' : '' } );

								} else {
									sticky.css( {'top' : bodyTop + 'px' } );

									sticky.addClass( 'boostify-sticky--active' );
									sticky.css( {'background-color' : data.bsticky_background } );
									if ( menuColor && menuColor != '' ) {
										sticky.find( '.boostify-menu > li > a' ).css( { 'color' : menuColor } );
									}

									if ( logoStick != '' ) {
										logo.attr( 'src', logoStick );
										logo.attr('srcset', '');
									} else {
										logo.attr( 'src', logoUrl );
									}
								}
							}
						);
					}

					$( window ).resize(
						function() {
							width = $( window ).width();
							sticky.css( { 'width' : width + 'px' } );
							var bodyTop = $( 'body' ).offset().top;
							sticky.css( { 'top' : bodyTop + 'px' } );
							if ( $( window ).scrollTop() > top ) {
								sticky.css( {'top' : bodyTop + 'px' } );
							} else {
								if ( ( top - $( window ).scrollTop() ) > 0 ) {
									sticky.css( {'top': ( top - $( window ).scrollTop() ) + 'px'} );
									// sticky.css({'top': 0 + 'px'});
								}
							}
						}
					);
				}
			);
		}
	}


	function stickyHeaderEditer() {
		var $sticky = $( '.boostify-sticky-yes' );
		if ( $sticky.length != 0 ) {
			$sticky.each(
				function ( index ) {
					var sticky   = $( this );
					var copyElem = sticky.siblings( '.elementor-section' );
					if ( copyElem.hasClass( 'elementor-sticky--active' ) || ( copyElem.hasClass( 'elementor-sticky--active' ) && copyElem.hasClass( 'she-header-yes' ) ) ) {
						return;
					}
					var bodyTop     = 0;
					var top         = sticky.offset().top;
					var left        = sticky.offset().left;
					var width       = sticky.width();
					var height      = sticky.height();
					var widthScreen = $( window ).width();
					var data        = sticky.attr( 'data-settings' );
					data            = JSON.parse( data );
					var screen      = data.bstick_on;
					var wrap        = sticky.parents( '.elementor-section-wrap' );
					var element     = sticky.html();
					var margin      = data.bsticky_distance;
					var logo        = sticky.find( '.custom-logo' );
					var logoUrl     = logo.attr( 'src' );
					var logoStick   = data.image.url;
					var menuColor   = data.bsticky_menu_color;
					var tranparent  = sticky.hasClass( 'boostify-header-tranparent-yes' );
					if ( widthScreen >= 1025 ) {
						var enabled = 'desktop';
					} else if ( widthScreen > 767 && widthScreen < 1025 ) {
						var enabled = 'tablet';
					} else if ( widthScreen <= 767 ) {
						var enabled = "mobile";
					}
					if ( screen.includes( enabled ) ) {
						if ( ! tranparent ) {
							var coppyClass = 'boostify-header--default elementor-section';
							if ( sticky.hasClass( 'elementor-hidden-phone' ) ) {
								coppyClass += ' elementor-hidden-phone';
							}

							if ( sticky.hasClass( 'elementor-hidden-tablet' ) ) {
								coppyClass += ' elementor-hidden-tablet';
							}

							if ( sticky.hasClass( 'elementor-hidden-desktop' ) ) {
								coppyClass += ' elementor-hidden-desktop';
							}

							sticky.after(
								'<section class="' + coppyClass + '">' +
								element +
								'</section>'
							);

							var copy = sticky.siblings( '.boostify-header--default' );

							copy.css(
								{
									'visibility' : 'hidden',
									'transition' : 'none 0s ease 0s',
								}
							);
						}
						top = sticky.offset().top - $( 'body' ).offset().top - 76;
						console.log( $( 'body' ).offset().top );
						sticky.css(
							{
								'top' : top + 'px',
								'position' : 'fixed',
								'left' : left + 'px',
								'width' : width + 'px',
							}
						);
						var copyMargin = top + margin.size;
						if ( $( window ).scrollTop() > top ) {
							sticky.css( {'top' : bodyTop + 'px' } );
							if ( menuColor && menuColor !== '' ) {
								sticky.find( '.boostify-menu > li > a' ).css( { 'color' : menuColor } );
							}
							if ( logoStick != '' ) {
								logo.attr( 'src', logoStick );
							} else {
								logo.attr( 'src', logoUrl );
							}
							sticky.addClass( 'boostify-sticky--active' );
							sticky.css( {'background-color' : data.bsticky_background } );
						} else {
							var defaultTop = sticky.offset().top - $( 'body' ).offset().top;
							sticky.css( 'top', defaultTop + 'px' );
							if ( ( top - $( window ).scrollTop() ) > 0 ) {
								sticky.css( {'top': ( top - $( window ).scrollTop() ) + 'px'} );
							}
						}

						$( window ).scroll(
							function() {
								var scroll = $( window ).scrollTop();
									top    = copy.offset().top;

								if ( ( top - scroll ) >= 0 ) {
									sticky.css( {'top': ( top - scroll ) + 'px'} );

									sticky.removeClass( 'boostify-sticky--active' );
									sticky.css( { "background-color" : '' } );
									logo.attr( 'src', logoUrl );
									sticky.find( '.boostify-menu > li > a' ).css( { 'color' : '' } );

								} else {
									sticky.css( {'top' : bodyTop + 'px' } );

									sticky.addClass( 'boostify-sticky--active' );
									sticky.css( {'background-color' : data.bsticky_background } );
									if ( menuColor && menuColor != '' ) {
										sticky.find( '.boostify-menu > li > a' ).css( { 'color' : menuColor } );
									}

									if ( logoStick != '' ) {
										logo.attr( 'src', logoStick );
										logo.attr('srcset', '');
									} else {
										logo.attr( 'src', logoUrl );
									}
								}
							}
						);
					}

					$( window ).resize(
						function() {
							width = $( window ).width();
							sticky.css( { 'width' : width + 'px' } );
							var bodyTop = $( 'body' ).offset().top;
							sticky.css( { 'top' : bodyTop + 'px' } );
							if ( $( window ).scrollTop() > top ) {
								sticky.css( {'top' : bodyTop + 'px' } );
							} else {
								if ( ( top - $( window ).scrollTop() ) > 0 ) {
									sticky.css( {'top': ( top - $( window ).scrollTop() ) + 'px'} );
									// sticky.css({'top': 0 + 'px'});
								}
							}
						}
					);
				}
			);
		}
	}
})(jQuery);