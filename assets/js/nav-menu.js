(function ($) {
	'use strict';

	/**
	 * @param $scope The widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */
	var WidgetNavMenu = function ($scope, $) {
		var toggle = $scope.find( '.boostify-menu-toggle' );
		var nav = $scope.find( '.boostify-menu-sidebar' );
		var overlay = $scope.find( '.boostify-overlay' );
		var close = $scope.find( '.boostify--close-menu-side-bar' );

		// Show Menu Mobile
		toggle.on('click', function(e) {
			e.preventDefault();

			if ( nav.hasClass( 'show' ) ) {
				nav.removeClass( 'show' );
				overlay.removeClass('show');

			} else {
				nav.addClass('show');
				overlay.addClass('show');
			}
		});

		// Close menu mobile when click overlay
		overlay.on('click', function(e) {
			nav.removeClass( 'show' );
			overlay.removeClass('show');
		});

		// Close menu mobile when click icon close
		close.on('click', function(e) {
			nav.removeClass( 'show' );
			overlay.removeClass('show');
		});

		// Close menu mobile when click ESC
		$(document).on('keyup', function (e) {
			if (e.keyCode === 27) {
				nav.removeClass( 'show' );
				overlay.removeClass('show');
			};
		});

	/* MOBILE MENU */

		$( '.boostify-menu-sidebar ul >.menu-item-has-children>a' ).on( 'click', function (e) {
			e.preventDefault();
			var item = $( this ).siblings('ul.sub-menu');
			var active = item.hasClass('active');
			if ( active ) {
				item.removeClass('active');
				$(this).removeClass('up');
				item.slideUp( 300 );
			} else {
				item.addClass('active');
				item.slideDown( 300 );
				$(this).addClass('up');
			}
		} );

	};


	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/ht-hf-nav-menu.default', WidgetNavMenu);
	});
})(jQuery);