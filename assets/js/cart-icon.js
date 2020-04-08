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
			$( 'body' ).removeClass( 'remove-item-mini-cart' );
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
			'removed_from_cart',// removed_from_cart
			function () {
				var sidebar = $scope.find( '.boostify-cart-detail' );
				sidebar.addClass( 'active' );
				if ( ! overlay.hasClass() ) {
					overlay.addClass( 'active' );
				}
			}
		);

		$( document.body ).on(
			'added_to_cart',// removed_from_cart
			function () {

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

		$( document ).on(
			'click',
			'.boostify-mini-cart-item a.remove',
			function (e) {
				e.preventDefault();
				var product_id    = $( this ).attr( "data-product_id" ),
				cart_item_key     = $( this ).attr( "data-cart_item_key" ),
				product_container = $( this ).parents( '.boostify-mini-cart-item' );
				$.ajax(
					{
						type: 'POST',
						dataType: 'json',
						url: wc_add_to_cart_params.ajax_url,
						data: {
							action: "boostify_product_remove",
							product_id: product_id,
							cart_item_key: cart_item_key
						},
						beforeSend: function (response) {
							product_container.addClass( 'remove' );
							var sidebar = $scope.find( '.boostify-cart-detail' );
							$( 'body' ).addClass( 'remove-item-mini-cart' );
							sidebar.addClass( 'active' );
						},
						success: function(response) {
							var fragments = response.fragments;
							var sidebar   = $scope.find( '.boostify-cart-detail' );
							$( 'body' ).addClass( 'remove-item-mini-cart' );
							sidebar.addClass( 'active' );
							if ( ! response || response.error ) {
								$( '.boostify-cart-detail' ).html( response.error );
							}
							var fragments = response.fragments;
							if ( fragments ) {
								$.each(
									fragments,
									function( key, value ) {
										$( key ).replaceWith( value );
									}
								);
							}
						}
					}
				);
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
