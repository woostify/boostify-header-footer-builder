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
		var widget  = $scope.find( '.boostify-cart-icon' );
		// var content = widget.attr( 'icon-content' );
		// var font    = widget.attr( 'icon-font' );
		// var icon    = widget.find( '.icon-cart' );
		// $scope.find( '.icon-cart' ).addClass( content );
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
			var parents = $this.parents( '.boostify-cart-icon' );
			console.log( parents.hasClass( '.boostify-action-hover' ) );
			if ( parents.hasClass( 'boostify-action-hover' ) ) {
				return;
			}
			e.preventDefault();
			var sidebar = parents.find( '.boostify-cart-detail' );
			sidebar.addClass( 'active' );
			overlay.addClass( 'active' );
		}

		$( document.body ).on(
			'added_to_cart removed_from_cart',
			function () {
				// $scope.find( '.icon-cart' ).addClass( content );
				var sidebar = $scope.find( '.boostify-cart-detail' );
				sidebar.addClass( 'active' );
				if ( ! overlay.hasClass() ) {
					overlay.addClass( 'active' );
				}

				var miniCart = $scope.find( '.boostify-cart-detail' );
				miniCart.css(
					{
						'z-index' : '99999',
						'opacity' : '1',
						'visibility' : 'visible',
						'transform' : 'translateY(0)',
						'height' : 'auto',
					}
				);
			}
		);

		$( 'body' ).on(
			'click',
			function () {
				var miniCart = $scope.find( '.boostify-cart-detail' );
				miniCart.attr( 'style', '' );
			}
		);
	};

	$( window ).on(
		'elementor/frontend/init',
		function () {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/ht-hf-cart-icon.default', WidgetCartIcon );
		}
	);
} )( jQuery );
