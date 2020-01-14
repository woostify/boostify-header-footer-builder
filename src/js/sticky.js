var $j = jQuery.noConflict();

$j( window ).on( 'load', function() {
	"use strict";
	stickyHeader();

});

function stickyHeader() {
	var $sticky = $j( '.boostify-sticky-yes' );
	if ( $sticky.length != 0 ) {
		$sticky.each( function ( index ) {
			var sticky = $j( this );
			var copyElem = sticky.siblings('.elementor-section');
			if ( copyElem.hasClass( 'elementor-sticky--active' ) || ( copyElem.hasClass( 'elementor-sticky--active' ) && copyElem.hasClass( 'she-header-yes' ) ) ) {
				return;
			}
			var top = sticky.offset().top;
			var left = sticky.offset().left;
			var width = sticky.width();
			var height = sticky.height();
			var widthScreen = $j(window).width();
			var data = sticky.attr('data-settings');
			data = JSON.parse(data);
			var screen = data.bstick_on;
			var wrap = sticky.parents('.elementor-section-wrap');
			var element = sticky.html();
			var classSticky = sticky.attr('class');
			var margin = data.bsticky_distance;
			var logo = sticky.find('.custom-logo');
			var logoUrl = logo.attr('src');
			var logoStick = data.image.url;
			var tranparent = sticky.hasClass( 'boostify-header-tranparent-yes' );
			var enabled = "";
			if ( widthScreen >= 1025 ) {
				var enabled = 'desktop';
			} else if ( widthScreen  > 767 && widthScreen < 1025 ) {
				var enabled = 'tablet';
			} else if ( widthScreen <= 767 ) {
				var enabled = "mobile";
			}

			if ( screen.includes( enabled ) ) {
				if ( ! tranparent ) {
					sticky.after(
						'<section class="boostify-header--default elementor-section">' +
						element +
						'</section>'
					);
					var copy = sticky.siblings('.boostify-header--default');
					copy.css({ 'visibility' : 'hidden', 'transition' : 'none 0s ease 0s', 'margin-top' : margin.size + margin.unit });
				}

				sticky.css({ 
					'position' : 'fixed',
					'left' : left + 'px',
					'width' : width + 'px',
					'margin-top' : margin.size + margin.unit,
					'margin-bottom' : '0px',
				});
				var copyMargin = top + margin.size;

				$j(window).scroll(function() {
					var scroll = $j(window).scrollTop();
					if ( scroll > ( top + height ) ) {
						sticky.addClass('boostify-sticky--active');
						sticky.css({'background-color' : data.bsticky_background});
						logo.attr( 'src', logoStick );
					} else {
						sticky.removeClass('boostify-sticky--active');
						sticky.css("background-color", '');
						logo.attr( 'src', logoUrl );
					}
				} );
			}

			$j(window).resize(function() {
				width = $j(window).width();
				sticky.css({ 'width' : width + 'px'});
				widthScreen = $j(window).width();
				if ( widthScreen >= 1025 ) {
					var enabled = 'desktop';
				} else if ( widthScreen  > 767 && widthScreen < 1025 ) {
					var enabled = 'tablet';
				} else if ( widthScreen <= 767 ) {
					var enabled = "mobile";
				}

				if ( screen.includes( enabled ) ) {
					if ( ! tranparent ) {
						sticky.after(
							'<section class="boostify-header--default elementor-section">' +
							element +
							'</section>'
						);
						var copy = sticky.siblings('.boostify-header--default');
						copy.css({ 'visibility' : 'hidden', 'transition' : 'none 0s ease 0s', 'margin-top' : margin.size + margin.unit });
					}

					sticky.css({ 
						'position' : 'fixed',
						'left' : left + 'px',
						'width' : width + 'px',
						'margin-top' : margin.size + margin.unit,
						'margin-bottom' : '0px',
					});
					var copyMargin = top + margin.size;

					$j(window).scroll(function() {
						var scroll = $j(window).scrollTop();
						if ( scroll > ( top + height ) ) {
							sticky.css({'background-color' : data.bsticky_background});
							logo.attr( 'src', logoStick );
						} else {
							sticky.removeClass('boostify-sticky--active');
							sticky.css("background-color", '');
							logo.attr( 'src', logoUrl );
						}
					} );
				}
			} );
		} );
	}

}
