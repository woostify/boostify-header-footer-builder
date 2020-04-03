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
		$scope.on(
			'click',
			'.boostify-btn--cart',
			function ( e ) {
				var btn = $( this );
				openSidebar( e, btn );
			}
		);

		$scope.on(
			'click',
			'#boostify-close-cart-sidebar',
			function(e) {
				var btn = $( this );
				closeSidebar( btn );
			}
		);

		$scope.on(
			'click',
			'.boostify-cart-overlay',
			function(e) {
				var btn = $( this );
				closeSidebar( btn );
			}
		);

		function closeSidebar( $this ) {
			var sidebar = $this.parents( '.boostify-cart-icon' ).find( '.boostify-cart-detail' );
			sidebar.removeClass( 'active' );
			overlay.removeClass( 'active' );
		}

		function openSidebar( e, $this ) {
			e.preventDefault();
			var sidebar = $this.parents( '.boostify-cart-icon' ).find( '.boostify-cart-detail' );
			sidebar.addClass( 'active' );
			overlay.addClass( 'active' );
		}
	};

	$( window ).on(
		'elementor/frontend/init',
		function () {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/ht-hf-cart-icon.default', WidgetCartIcon );
		}
	);
} )( jQuery );
