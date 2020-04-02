(function ($) {
	'use strict';

	/**
	 * @param $scope The widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */
	var WidgetCartIcon = function ($scope, $) {
		var cart    = $scope.find( '.boostify-btn--cart' );
		var sidebar = $scope.find( '.boostify-cart-detail' );
		var overlay = $scope.find( '.boostify-cart-overlay' );
		var close   = $scope.find( '#boostify-close-cart-sidebar' );
		cart.on(
			'click',
			function ( e ) {
				e.preventDefault();
				sidebar.addClass( 'active' );
				overlay.addClass( 'active' );
			}
		);


		close.on(
			'click',
			function(e) {
				closeSidebar();
			}
		);

		overlay.on(
			'click',
			function(e) {
				closeSidebar();
			}
		);

		function closeSidebar() {
			sidebar.removeClass( 'active' );
			overlay.removeClass( 'active' );
		}
	};

	$( window ).on(
		'elementor/frontend/init',
		function () {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/ht-hf-cart-icon.default', WidgetCartIcon );
		}
	);
} )( jQuery );
