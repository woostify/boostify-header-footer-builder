/**
 * Mega Menu JS
 *
 * @package boostify
 */

(function ($) {
	'use strict';

	/**
	 * WidgetMegaMenu
	 *
	 * @param $scope The widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */
	var WidgetMegaMenu = function ($scope, $) {
		var main     = $scope.find( '.boostify-main-navigation' );
		var sub      = main.find( '.sub-menu' );
		var itemMega = main.find( '.menu-item-has-mega' );
		var toggle   = $scope.find( '.boostify-menu-toggle' );
		var nav      = $scope.find( '.boostify-menu-sidebar' );
		var overlay  = $scope.find( '.boostify-overlay' );
		var close    = $scope.find( '.boostify--close-menu-side-bar' );

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
					console.log( width );
					console.log(left);
					if ( item.hasClass( 'sub-width-default' ) ) {
						contentWith = 500;
						padding     = ( width - 500 ) / 2;
						left        = padding - left;
						mega.css( { 'left' : 'calc( ( 100% - 500px ) /2 )', 'width' : contentWith + 'px' } );
					} else if ( item.hasClass( 'sub-width-container' ) ) {
						if ( width > 1170 ) {
							padding     = ( width - 1170 ) / 2;
							contentWith = 1170;
							position    = padding - left;
							console.log(padding);

						} else {
							padding     = 15;
							position    = padding - left;
							contentWith = ( width - 30 );

						}
						console.log( left );
						mega.css( { 'left' : position + 'px', 'width' : contentWith + 'px' } );
					} else {
						console.log( left );
						mega.css( { 'left' : '-' + left + 'px', 'width' : width } );
					}

				}
			);
		}

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

		var btn = nav.find( 'ul >.menu-item-has-children>a' );
		btn.on(
			'click',
			function (e) {
				e.preventDefault();
				var item   = $( this ).siblings( 'ul.sub-menu' );
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
			elementorFrontend.hooks.addAction( 'frontend/element_ready/ht-hf-mega-menu.default', WidgetMegaMenu );
		}
	);
} )( jQuery );
