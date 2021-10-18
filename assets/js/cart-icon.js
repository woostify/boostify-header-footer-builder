( function ($) {
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
			var sidebar = $( 'body' ).find( '.boostify-cart-icon' ).find( '.boostify-cart-detail' );
			sidebar.removeClass( 'active' );
			overlay.removeClass( 'active' );
		}

		function openSidebar( e, $this ) {
			var parents = $this.parents( '.boostify-cart-icon' );
			if ( parents.hasClass( 'boostify-action-hover' ) ) {
				return;
			}
			e.preventDefault();
			var sidebar = parents.find( '.boostify-cart-detail' );
			sidebar.addClass( 'active' );
			overlay.addClass( 'active' );
		}

		$( document.body ).on(
			'removed_from_cart',// removed_from_cart added_to_cart
			function () {
				var sidebar = $scope.find( '.boostify-cart-detail' );
				if ( ! overlay.hasClass() ) {
					overlay.addClass( 'active' );
				}
				sidebar.addClass( 'active' );
				var miniCart = $scope.find( '.boostify-cart-detail' );
				var parents  = $scope.parents( '.boostify-cart-icon' );
				if ( parents.hasClass( 'boostify-action-hover' ) ) {
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
						url: admin.url,
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
							$( 'body' ).addClass( 'remove-item-mini-cart' );
							sidebar.addClass( 'active' );
							var fragments = response.fragments;

							// if ( ! response || response.error ) {
							// 	$( '.boostify-cart-detail' ).html( response.error );
							// }
							// var fragments = response.fragments;
							// if ( fragments ) {
							// 	$.each(
							// 		fragments,
							// 		function( key, value ) {
							// 			$( key ).replaceWith( value );
							// 		}
							// 	);
							// }
						}
					}
				);
			}
		);

		if ( widget.hasClass( 'boostify-ajax-add-to-cart' ) ) {
			$( 'body' ).on(
				'click',
				'.ajax_add_to_cart',
				function (e) {
					e.preventDefault();
					var btn      = $( this ),
					$qty         = btn.attr( 'data-quantity' ),
					$id          = btn.attr( 'data-product_id' ),
					variation_id = 0,
					data         = {
						action: 'boostify_ajax_add_to_cart',
						product_id: $id,
						variation_id: variation_id,
						sku: '',
						quantity: $qty,
						_ajax_nonce: admin.nonce,
					};
					$.ajax(
						{
							type: 'post',
							url: admin.url,
							data: data,
							beforeSend: function (data) {
								var sidebar = $scope.find( '.boostify-cart-detail' );
								if ( ! overlay.hasClass() ) {
									overlay.addClass( 'active' );
								}
								sidebar.addClass( 'active' );
								sidebar.addClass( 'loading' );
							},
							success: function (data) {
								sidebar.removeClass( 'loading' );
							},
						}
					);
				return false;
				}
			);

			$( document.body ).on(
				'added_to_cart',// removed_from_cart added_to_cart
				function () {
					var sidebar = $scope.find( '.boostify-cart-detail' );
					if ( ! overlay.hasClass() ) {
						overlay.addClass( 'active' );
					}
					sidebar.addClass( 'active' );
				}
			);
		}

	};

	$( window ).on(
		'elementor/frontend/init',
		function () {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/ht-hf-cart-icon.default', WidgetCartIcon );
		}
	);
} )( jQuery );
