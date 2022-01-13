/**
 * Mega Menu Vertical JS
 *
 * @package boostify
 */

(function ($) {
	'use strict';

	/**
	 * WidgetMegaMenuVertical
	 *
	 * @param $scope The widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */
	var WidgetMegaMenuVertical = function ($scope, $) {
		var main          = $scope.find( '.boostify-main-navigation' ),
			sub           = main.find( '.sub-menu' ),
			itemMega      = main.find( '.menu-item-has-mega' ),
			toggle        = $scope.find( '.boostify-menu-toggle' ),
			toggleVetical = $scope.find( '.boostify-menu-toggle-vertical' ),
			nav           = $scope.find( '.boostify-menu-sidebar' ),
			overlay       = $scope.find( '.boostify-overlay' ),
			close         = $scope.find( '.boostify--close-menu-side-bar' );

		sub.each(
			function ( index ) {
				if ( sub.hasClass( 'sub-mega-menu' ) ) {
					$( this ).wrap( '<div class="boostify-menu-child boostify-mega-sub">' );
				} else {
					$( this ).wrap( '<div class="boostify-menu-child">' );
				}
			}
		);

		// Set With Mega Menu.
		megaWidth();

		$( window ).resize(
			function() {
				megaWidth();
			}
		);
		function megaWidth() {
			itemMega.each(
				function( index ) {
					var item  = $( this );
					var mega  = item.find( '.boostify-mega-sub' );
					var width = $( window ).width();
					var left  = item.offset().left;
					var padding, contentWith, position;
					if ( item.hasClass( 'sub-width-default' ) ) {
						contentWith = 500;
						padding     = ( width - 500 ) / 2;
						mega.css( { 'width' : contentWith + 'px' } );
					} else if ( item.hasClass( 'sub-width-container' ) ) {
						if ( width > 1170 ) {
							padding     = ( width - 1170 ) / 2;
							contentWith = 1170;
							position    = padding - left;
						} else {
							padding     = 15;
							position    = padding - left;
							contentWith = ( width - 30 );
						}

						mega.css( { 'width' : contentWith + 'px' } );
					} else if ( item.hasClass( 'sub-width-custom' ) ) {
						contentWith = parseInt( item.attr( 'data-custom-width' ) );

						if ( ! contentWith && contentWith < 200 ) {
							contentWith = 500;
						}

						padding = ( width - contentWith ) / 2;
						mega.css( { 'width' : contentWith + 'px' } );
					} else {
						mega.css( { 'width' : width } );
					}

				}
			);
		}

		// Menu Vertical toggle.
		toggleVetical.on(
			'click',
			function(e) {
				e.preventDefault();
				if ( main.hasClass( 'show' ) ) {
					main.removeClass( 'show' );
				} else {
					main.addClass( 'show' );
				}
			}
		);

		// Show Menu Mobile.
		toggle.on(
			'click',
			function(e) {
				e.preventDefault();
				if ( nav.hasClass( 'show' ) ) {
					nav.removeClass( 'show' );
					overlay.removeClass( 'show' );
				} else {
					nav.addClass( 'show' );
					overlay.addClass( 'show' );
				}
			}
		);

		// Close menu mobile when click overlay.
		overlay.on(
			'click',
			function(e) {
				nav.removeClass( 'show' );
				overlay.removeClass( 'show' );
			}
		);

		// Close menu mobile when click icon close.
		close.on(
			'click',
			function(e) {
				nav.removeClass( 'show' );
				overlay.removeClass( 'show' );
			}
		);

		// Close menu mobile when click ESC.
		$( document ).on(
			'keyup',
			function (e) {
				if (e.keyCode === 27) {
					nav.removeClass( 'show' );
					overlay.removeClass( 'show' );
				};
			}
		);

		/* MOBILE MENU */
		// Add arrow.
		var itemHasChild = nav.find( 'ul >.menu-item-has-children>a' );
		itemHasChild.append( '<span class="arrow"></span>' );
		var btn = nav.find( 'ul >.menu-item-has-children .arrow' );
		btn.on(
			'click',
			function (e) {
				e.preventDefault();
				var item   = $( this ).parent().siblings( 'ul.sub-menu' );
				var active = item.hasClass( 'active' );
				if ( active ) {
					item.removeClass( 'active' );
					$( this ).removeClass( 'up' );
					item.slideUp( 300 );
				} else {
					item.addClass( 'active' );
					item.slideDown( 300 );
					$( this ).addClass( 'up' );
				}
			}
		);

		if ( main.hasClass( 'primary-menu' ) ) {
			main.removeClass( 'primary-menu' );
		}
	};

	$( window ).on(
		'elementor/frontend/init',
		function () {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/ht-hf-mega-menu-vertical.default', WidgetMegaMenuVertical );
		}
	);
} )( jQuery );
