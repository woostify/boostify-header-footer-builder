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
			elementorFrontend.hooks.addAction( 'frontend/element_ready/ht-hf-mega-menu-vertical.default', WidgetMegaMenuVertical );
		}
	);
} )( jQuery );
